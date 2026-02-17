<?php

namespace app\models;

use Flight;
use PDO;

class DispatchModel
{
    /**
     * Simule le dispatch des dons aux besoins par ordre de date de saisie
     */
    public function simuler(): array
    {
        $resultats = [];

        // Besoins restants par ordre d'id
        $besoins = Flight::db()->query("
            SELECT *
            FROM v_besoins_restants
            WHERE quantite_restante > 0
            ORDER BY id_besoin ASC
        ")->fetchAll(PDO::FETCH_ASSOC);

        // ===== DISPATCH NATURE / MATÉRIELS =====
        $dons = Flight::db()->query("
            SELECT * FROM v_dons_restants 
            WHERE id_categorie != 3 AND quantite_restante > 0
            ORDER BY date_don ASC
        ")->fetchAll(PDO::FETCH_ASSOC);

        // Copie mutable
        $donsDisponibles = [];
        foreach ($dons as $don) {
            $donsDisponibles[$don['id_don']] = $don;
        }

        foreach ($besoins as $besoin) {
            if ($besoin['id_categorie'] == 3) continue; // traité séparément
            $qteRestante = (float) $besoin['quantite_restante'];

            foreach ($donsDisponibles as &$don) {
                if ($qteRestante <= 0) break;
                // Match par nom et catégorie
                if ($don['nom_don'] != $besoin['nom_besoin'] || $don['id_categorie'] != $besoin['id_categorie']) continue;
                if ($don['quantite_restante'] <= 0) continue;

                $qteAttribuee = min($qteRestante, (float) $don['quantite_restante']);
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
                    'nom_user' => $don['nom_user'],
                    'quantite_attribuee' => $qteAttribuee,
                ];
            }
            unset($don);
        }

        // ===== DISPATCH BESOINS EN ARGENT =====
        $donsArgent = Flight::db()->query("
            SELECT * FROM v_dons_restants 
            WHERE id_categorie = 3 AND montant > 0
            ORDER BY date_don ASC
        ")->fetchAll(PDO::FETCH_ASSOC);

        $donsArgentDisponibles = [];
        foreach ($donsArgent as $da) {
            $donsArgentDisponibles[$da['id_don']] = $da;
            $donsArgentDisponibles[$da['id_don']]['montant_restant'] = (float) $da['montant'];
        }

        foreach ($besoins as $besoin) {
            if ($besoin['id_categorie'] != 3) continue;
            $montantRestant = (float) $besoin['quantite_restante'] * (float) $besoin['prix_unitaire'];

            foreach ($donsArgentDisponibles as &$donA) {
                if ($montantRestant <= 0) break;
                if ($donA['montant_restant'] <= 0) continue;

                $montantAttribue = min($montantRestant, $donA['montant_restant']);
                $donA['montant_restant'] -= $montantAttribue;
                $montantRestant -= $montantAttribue;

                $prixUnit = (float) $besoin['prix_unitaire'];
                $qteAttribuee = $prixUnit > 0 ? $montantAttribue / $prixUnit : 0;

                $resultats[] = [
                    'id_besoin' => $besoin['id_besoin'],
                    'nom_ville' => $besoin['nom_ville'],
                    'nom_besoin' => $besoin['nom_besoin'],
                    'nom_categorie' => 'En argent',
                    'id_categorie' => 3,
                    'prix_unitaire' => $besoin['prix_unitaire'],
                    'id_don' => $donA['id_don'],
                    'nom_user' => $donA['nom_user'],
                    'quantite_attribuee' => $qteAttribuee,
                ];
            }
            unset($donA);
        }

        return $resultats;
    }

    /**
     * Valide le dispatch (insertion réelle en base)
     */
    public function valider(): int
    {
        $simulation = $this->simuler();
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
