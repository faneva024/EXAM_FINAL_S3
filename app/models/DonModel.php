<?php

namespace app\models;

use Flight;
use PDO;

class DonModel
{
    public function all(): array
    {
        $stmt = Flight::db()->query("
            SELECT d.*, u.nom AS nom_user, c.nom_categorie,
                CASE 
                    WHEN d.id_categorie = 3 THEN d.montant
                    WHEN d.montant IS NOT NULL AND d.montant > 0 THEN d.montant
                    ELSE (
                        SELECT b.prix_unitaire * d.quantite 
                        FROM BNGRC_Besoins b 
                        WHERE b.nom_besoin = d.nom_don AND b.id_categorie = d.id_categorie 
                        LIMIT 1
                    )
                END AS montant_affiche
            FROM BNGRC_Dons d
            JOIN BNGRC_User u ON d.id_user = u.id_user
            JOIN BNGRC_CategoriesBesoins c ON d.id_categorie = c.id_categorie
            ORDER BY d.date_don DESC
        ");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function restants(): array
    {
        $stmt = Flight::db()->query("
            SELECT * FROM v_dons_restants 
            WHERE (quantite_restante > 0 OR (id_categorie = 3 AND montant > 0)) 
            ORDER BY date_don ASC
        ");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function ajouter(int $idUser, int $idCategorie, string $nomDon, ?float $quantite, ?float $montant): bool
    {
        $stmt = Flight::db()->prepare(
            "INSERT INTO BNGRC_Dons (id_user, id_categorie, nom_don, quantite, montant) VALUES (:idUser, :idCategorie, :nomDon, :quantite, :montant)"
        );
        return $stmt->execute([
            'idUser' => $idUser,
            'idCategorie' => $idCategorie,
            'nomDon' => $nomDon,
            'quantite' => $quantite,
            'montant' => $montant,
        ]);
    }

    public function argentDisponible(): float
    {
        $stmt = Flight::db()->query("SELECT argent_disponible FROM v_argent_disponible");
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return (float)($row['argent_disponible'] ?? 0);
    }

    public function restantsParNom(string $nomDon, ?int $idCategorie = null): array
    {
        $sql = "
            SELECT * FROM v_dons_restants 
            WHERE nom_don = :nomDon AND id_categorie != 3 AND quantite_restante > 0
        ";
        $params = ['nomDon' => $nomDon];

        if ($idCategorie !== null) {
            $sql .= " AND id_categorie = :idCategorie";
            $params['idCategorie'] = $idCategorie;
        }

        $sql .= " ORDER BY date_don ASC";

        $stmt = Flight::db()->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
