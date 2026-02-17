<?php

namespace app\models;

use Flight;
use PDO;

class DispatchModel
{
    const STRATEGY_DATE = 'date';           // Par ordre de date (plus récent en premier)
    const STRATEGY_INFERIEUR = 'inferieur'; // Par ordre d'infériorité (plus petit besoin en premier)
    const STRATEGY_PROPORTIONNEL = 'proportionnel'; // Par proportionnalité

    /**
     * Simule le dispatch des dons aux besoins selon une stratégie
     */
    public function simuler(string $strategy = self::STRATEGY_DATE): array
    {
        switch ($strategy) {
            case self::STRATEGY_INFERIEUR:
                return $this->simulerParInferiorite();
            case self::STRATEGY_PROPORTIONNEL:
                return $this->simulerParProportionnalite();
            case self::STRATEGY_DATE:
            default:
                return $this->simulerParDate();
        }
    }

    /**
     * Stratégie 1: Par ordre de date (le plus récent bénéficie en premier)
     */
    private function simulerParDate(): array
    {
        $resultats = [];

        // Besoins restants par date décroissante (plus récent en premier)
        // Utiliser id_besoin DESC comme proxy pour la date si date_saisie n'existe pas
        $besoins = Flight::db()->query("
            SELECT *
            FROM v_besoins_restants
            WHERE quantite_restante > 0
            ORDER BY id_besoin DESC
        ")->fetchAll(PDO::FETCH_ASSOC);

        // ===== DISPATCH NATURE / MATÉRIELS =====
        $dons = Flight::db()->query("
            SELECT * FROM v_dons_restants 
            WHERE id_categorie != 3 AND quantite_restante > 0
            ORDER BY id_don ASC
        ")->fetchAll(PDO::FETCH_ASSOC);

        $donsDisponibles = [];
        foreach ($dons as $don) {
            $donsDisponibles[$don['id_don']] = $don;
        }

        foreach ($besoins as $besoin) {
            if ($besoin['id_categorie'] == 3) continue;
            $qteRestante = (float) $besoin['quantite_restante'];

            foreach ($donsDisponibles as &$don) {
                if ($qteRestante <= 0) break;
                if ($don['nom_don'] != $besoin['nom_besoin'] || $don['id_categorie'] != $besoin['id_categorie']) continue;
                if ($don['quantite_restante'] <= 0) continue;

                $qteAttribuee = floor(min($qteRestante, (float) $don['quantite_restante']));
                if ($qteAttribuee <= 0) continue;
                $don['quantite_restante'] -= $qteAttribuee;
                $qteRestante -= $qteAttribuee;

                $resultats[] = [
                    'id_besoin' => $besoin['id_besoin'],
                    'nom_ville' => $besoin['nom_ville'],
                    'nom_besoin' => $besoin['nom_besoin'],
                    'nom_categorie' => $besoin['nom_categorie'] ?? 'En nature',
                    'id_categorie' => $besoin['id_categorie'],
                    'prix_unitaire' => $besoin['prix_unitaire'],
                    'id_don' => $don['id_don'],
                    'nom_user' => $don['nom_user'] ?? 'Anonyme',
                    'quantite_attribuee' => $qteAttribuee,
                ];
            }
            unset($don);
        }

        // ===== DISPATCH BESOINS EN ARGENT =====
        $this->dispatchArgent($besoins, $resultats);

        return $resultats;
    }

    /**
     * Stratégie 2: Par ordre d'infériorité (plus petit besoin en premier)
     */
    private function simulerParInferiorite(): array
    {
        $resultats = [];

        // Besoins restants par quantité croissante (plus petit en premier)
        $besoins = Flight::db()->query("
            SELECT *
            FROM v_besoins_restants
            WHERE quantite_restante > 0
            ORDER BY quantite_restante ASC, id_besoin ASC
        ")->fetchAll(PDO::FETCH_ASSOC);

        // ===== DISPATCH NATURE / MATÉRIELS =====
        $dons = Flight::db()->query("
            SELECT * FROM v_dons_restants 
            WHERE id_categorie != 3 AND quantite_restante > 0
            ORDER BY id_don ASC
        ")->fetchAll(PDO::FETCH_ASSOC);

        $donsDisponibles = [];
        foreach ($dons as $don) {
            $donsDisponibles[$don['id_don']] = $don;
        }

        foreach ($besoins as $besoin) {
            if ($besoin['id_categorie'] == 3) continue;
            $qteRestante = (float) $besoin['quantite_restante'];

            foreach ($donsDisponibles as &$don) {
                if ($qteRestante <= 0) break;
                if ($don['nom_don'] != $besoin['nom_besoin'] || $don['id_categorie'] != $besoin['id_categorie']) continue;
                if ($don['quantite_restante'] <= 0) continue;

                $qteAttribuee = floor(min($qteRestante, (float) $don['quantite_restante']));
                if ($qteAttribuee <= 0) continue;
                $don['quantite_restante'] -= $qteAttribuee;
                $qteRestante -= $qteAttribuee;

                $resultats[] = [
                    'id_besoin' => $besoin['id_besoin'],
                    'nom_ville' => $besoin['nom_ville'],
                    'nom_besoin' => $besoin['nom_besoin'],
                    'nom_categorie' => $besoin['nom_categorie'] ?? 'En nature',
                    'id_categorie' => $besoin['id_categorie'],
                    'prix_unitaire' => $besoin['prix_unitaire'],
                    'id_don' => $don['id_don'],
                    'nom_user' => $don['nom_user'] ?? 'Anonyme',
                    'quantite_attribuee' => $qteAttribuee,
                ];
            }
            unset($don);
        }

        // ===== DISPATCH BESOINS EN ARGENT =====
        $this->dispatchArgent($besoins, $resultats);

        return $resultats;
    }

    /**
     * Stratégie 3: Par proportionnalité (chacun reçoit selon son pourcentage de besoin)
     */
    private function simulerParProportionnalite(): array
    {
        $resultats = [];

        // Récupérer tous les besoins restants
        $besoins = Flight::db()->query("
            SELECT *
            FROM v_besoins_restants
            WHERE quantite_restante > 0
            ORDER BY nom_besoin, id_besoin ASC
        ")->fetchAll(PDO::FETCH_ASSOC);

        // Récupérer tous les dons disponibles
        $dons = Flight::db()->query("
            SELECT * FROM v_dons_restants 
            WHERE id_categorie != 3 AND quantite_restante > 0
            ORDER BY id_don ASC
        ")->fetchAll(PDO::FETCH_ASSOC);

        // Grouper les besoins par nom et catégorie
        $besoinsParType = [];
        foreach ($besoins as $besoin) {
            if ($besoin['id_categorie'] == 3) continue;
            $key = $besoin['nom_besoin'] . '_' . $besoin['id_categorie'];
            if (!isset($besoinsParType[$key])) {
                $besoinsParType[$key] = [];
            }
            $besoinsParType[$key][] = $besoin;
        }

        // Grouper les dons par nom et catégorie
        $donsParType = [];
        foreach ($dons as $don) {
            $key = $don['nom_don'] . '_' . $don['id_categorie'];
            if (!isset($donsParType[$key])) {
                $donsParType[$key] = ['total' => 0, 'dons' => []];
            }
            $donsParType[$key]['total'] += (float) $don['quantite_restante'];
            $donsParType[$key]['dons'][] = $don;
        }

        // Pour chaque type de besoin, distribuer proportionnellement
        foreach ($besoinsParType as $key => $besoinsGroupe) {
            if (!isset($donsParType[$key])) continue;

            $totalDonDisponible = $donsParType[$key]['total'];
            $donsGroupe = $donsParType[$key]['dons'];

            // Calculer le total des besoins pour ce type
            $totalBesoins = 0;
            foreach ($besoinsGroupe as $besoin) {
                $totalBesoins += (float) $besoin['quantite_restante'];
            }

            if ($totalBesoins <= 0) continue;

            // Copie mutable des dons
            $donsDisponibles = [];
            foreach ($donsGroupe as $d) {
                $donsDisponibles[$d['id_don']] = $d;
                $donsDisponibles[$d['id_don']]['quantite_restante'] = (float) $d['quantite_restante'];
            }

            // Distribuer proportionnellement à chaque besoin
            foreach ($besoinsGroupe as $besoin) {
                $qteRestante = (float) $besoin['quantite_restante'];
                // Calcul du pourcentage de ce besoin
                $pourcentage = $qteRestante / $totalBesoins;
                // Quantité à attribuer proportionnellement
                $qteProportionnelle = $totalDonDisponible * $pourcentage;
                // Ne pas dépasser le besoin réel
                $qteAAttribuer = min($qteProportionnelle, $qteRestante);

                $qteRestanteAAttribuer = $qteAAttribuer;

                foreach ($donsDisponibles as &$don) {
                    if ($qteRestanteAAttribuer <= 0) break;
                    if ($don['quantite_restante'] <= 0) continue;

                    $qteAttribuee = floor(min($qteRestanteAAttribuer, $don['quantite_restante']));
                    if ($qteAttribuee <= 0) continue;
                    $don['quantite_restante'] -= $qteAttribuee;
                    $qteRestanteAAttribuer -= $qteAttribuee;

                    if ($qteAttribuee > 0) {
                        $resultats[] = [
                            'id_besoin' => $besoin['id_besoin'],
                            'nom_ville' => $besoin['nom_ville'],
                            'nom_besoin' => $besoin['nom_besoin'],
                            'nom_categorie' => $besoin['nom_categorie'] ?? 'En nature',
                            'id_categorie' => $besoin['id_categorie'],
                            'prix_unitaire' => $besoin['prix_unitaire'],
                            'id_don' => $don['id_don'],
                            'nom_user' => $don['nom_user'] ?? 'Anonyme',
                            'quantite_attribuee' => $qteAttribuee,
                            'pourcentage' => round($pourcentage * 100, 1),
                        ];
                    }
                }
                unset($don);
            }
        }

        // ===== DISPATCH BESOINS EN ARGENT (proportionnel) =====
        $this->dispatchArgentProportionnel($besoins, $resultats);

        return $resultats;
    }

    /**
     * Dispatch des besoins en argent (utilisé par date et infériorité)
     */
    private function dispatchArgent(array $besoins, array &$resultats): void
    {
        $donsArgent = Flight::db()->query("
            SELECT * FROM v_dons_restants 
            WHERE id_categorie = 3 AND montant > 0
            ORDER BY id_don ASC
        ")->fetchAll(PDO::FETCH_ASSOC);

        $donsArgentDisponibles = [];
        foreach ($donsArgent as $da) {
            $donsArgentDisponibles[$da['id_don']] = $da;
            $donsArgentDisponibles[$da['id_don']]['montant_dispo'] = (float) $da['montant'];
        }

        foreach ($besoins as $besoin) {
            if ($besoin['id_categorie'] != 3) continue;
            $montantRestant = (float) $besoin['quantite_restante'] * (float) $besoin['prix_unitaire'];

            foreach ($donsArgentDisponibles as &$donA) {
                if ($montantRestant <= 0) break;
                if ($donA['montant_dispo'] <= 0) continue;

                $montantAttribue = min($montantRestant, $donA['montant_dispo']);
                $donA['montant_dispo'] -= $montantAttribue;
                $montantRestant -= $montantAttribue;

                $prixUnit = (float) $besoin['prix_unitaire'];
                $qteAttribuee = $prixUnit > 0 ? floor($montantAttribue / $prixUnit) : 0;
                if ($qteAttribuee <= 0) continue;

                $resultats[] = [
                    'id_besoin' => $besoin['id_besoin'],
                    'nom_ville' => $besoin['nom_ville'],
                    'nom_besoin' => $besoin['nom_besoin'],
                    'nom_categorie' => 'En argent',
                    'id_categorie' => 3,
                    'prix_unitaire' => $besoin['prix_unitaire'],
                    'id_don' => $donA['id_don'],
                    'nom_user' => $donA['nom_user'] ?? 'Anonyme',
                    'quantite_attribuee' => $qteAttribuee,
                ];
            }
            unset($donA);
        }
    }

    /**
     * Dispatch proportionnel des besoins en argent
     */
    private function dispatchArgentProportionnel(array $besoins, array &$resultats): void
    {
        // Filtrer les besoins en argent
        $besoinsArgent = array_filter($besoins, fn($b) => $b['id_categorie'] == 3);
        if (empty($besoinsArgent)) return;

        $donsArgent = Flight::db()->query("
            SELECT * FROM v_dons_restants 
            WHERE id_categorie = 3 AND montant > 0
            ORDER BY id_don ASC
        ")->fetchAll(PDO::FETCH_ASSOC);

        if (empty($donsArgent)) return;

        // Total des dons en argent disponibles
        $totalDonsArgent = 0;
        foreach ($donsArgent as $da) {
            $totalDonsArgent += (float) $da['montant'];
        }

        // Total des besoins en argent
        $totalBesoinsArgent = 0;
        foreach ($besoinsArgent as $besoin) {
            $totalBesoinsArgent += (float) $besoin['quantite_restante'] * (float) $besoin['prix_unitaire'];
        }

        if ($totalBesoinsArgent <= 0) return;

        $donsArgentDisponibles = [];
        foreach ($donsArgent as $da) {
            $donsArgentDisponibles[$da['id_don']] = $da;
            $donsArgentDisponibles[$da['id_don']]['montant_dispo'] = (float) $da['montant'];
        }

        foreach ($besoinsArgent as $besoin) {
            $montantBesoin = (float) $besoin['quantite_restante'] * (float) $besoin['prix_unitaire'];
            $pourcentage = $montantBesoin / $totalBesoinsArgent;
            $montantProportionnel = $totalDonsArgent * $pourcentage;
            $montantAAttribuer = min($montantProportionnel, $montantBesoin);

            $montantRestantAAttribuer = $montantAAttribuer;

            foreach ($donsArgentDisponibles as &$donA) {
                if ($montantRestantAAttribuer <= 0) break;
                if ($donA['montant_dispo'] <= 0) continue;

                $montantAttribue = min($montantRestantAAttribuer, $donA['montant_dispo']);
                $donA['montant_dispo'] -= $montantAttribue;
                $montantRestantAAttribuer -= $montantAttribue;

                $prixUnit = (float) $besoin['prix_unitaire'];
                $qteAttribuee = $prixUnit > 0 ? floor($montantAttribue / $prixUnit) : 0;

                if ($qteAttribuee > 0) {
                    $resultats[] = [
                        'id_besoin' => $besoin['id_besoin'],
                        'nom_ville' => $besoin['nom_ville'],
                        'nom_besoin' => $besoin['nom_besoin'],
                        'nom_categorie' => 'En argent',
                        'id_categorie' => 3,
                        'prix_unitaire' => $besoin['prix_unitaire'],
                        'id_don' => $donA['id_don'],
                        'nom_user' => $donA['nom_user'] ?? 'Anonyme',
                        'quantite_attribuee' => $qteAttribuee,
                        'pourcentage' => round($pourcentage * 100, 1),
                    ];
                }
            }
            unset($donA);
        }
    }

    /**
     * Valide le dispatch avec une stratégie donnée
     */
    public function valider(string $strategy = self::STRATEGY_DATE): int
    {
        $simulation = $this->simuler($strategy);
        $count = 0;

        $stmt = Flight::db()->prepare("
            INSERT INTO BNGRC_Dispatch (id_besoin, id_don, quantite_attribuee)
            VALUES (:idBesoin, :idDon, :qte)
        ");

        foreach ($simulation as $item) {
            $stmt->execute([
                'idBesoin' => $item['id_besoin'],
                'idDon' => $item['id_don'],
                'qte' => $item['quantite_attribuee'],
            ]);
            $count++;
        }

        return $count;
    }

    /**
     * Réinitialise toutes les données (dispatch, achats)
     */
    public function reinitialiser(): bool
    {
        try {
            Flight::db()->exec("SET FOREIGN_KEY_CHECKS = 0");
            Flight::db()->exec("TRUNCATE TABLE BNGRC_Dispatch");
            Flight::db()->exec("TRUNCATE TABLE BNGRC_Achat");
            Flight::db()->exec("SET FOREIGN_KEY_CHECKS = 1");
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Historique des dispatch validés
     */
    public function historique(): array
    {
        $stmt = Flight::db()->query("
            SELECT d.*, b.id_ville, v.nom_ville, b.nom_besoin, 
                   b.id_categorie, c.nom_categorie, u.nom AS nom_user
            FROM BNGRC_Dispatch d
            JOIN BNGRC_Besoins b ON d.id_besoin = b.id_besoin
            JOIN BNGRC_Ville v ON b.id_ville = v.id_ville
            JOIN BNGRC_CategoriesBesoins c ON b.id_categorie = c.id_categorie
            LEFT JOIN BNGRC_Dons dn ON d.id_don = dn.id_don
            LEFT JOIN BNGRC_User u ON dn.id_user = u.id_user
            ORDER BY d.date_dispatch DESC
        ");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
