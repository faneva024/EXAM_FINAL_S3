<?php

namespace app\models;

use Flight;
use PDO;

class BesoinModel
{
    public function all(): array
    {
        $stmt = Flight::db()->query("SELECT * FROM v_besoins_details ORDER BY date_saisie DESC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function restants(?int $idVille = null): array
    {
        $sql = "SELECT * FROM v_besoins_restants WHERE quantite_restante > 0";
        $params = [];
        if ($idVille) {
            $sql .= " AND id_ville = :idVille";
            $params['idVille'] = $idVille;
        }
        $sql .= " ORDER BY nom_ville, nom_besoin";
        $stmt = Flight::db()->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function ajouter(int $idVille, int $idCategorie, string $nomBesoin, float $prixUnitaire, float $quantite): bool
    {
        $stmt = Flight::db()->prepare(
            "INSERT INTO BNGRC_Besoins (id_ville, id_categorie, nom_besoin, prix_unitaire, quantite) VALUES (:idVille, :idCategorie, :nomBesoin, :prixUnitaire, :quantite)"
        );
        return $stmt->execute([
            'idVille' => $idVille,
            'idCategorie' => $idCategorie,
            'nomBesoin' => $nomBesoin,
            'prixUnitaire' => $prixUnitaire,
            'quantite' => $quantite,
        ]);
    }

    public function findById(int $id): ?array
    {
        $stmt = Flight::db()->prepare("SELECT * FROM v_besoins_restants WHERE id_besoin = :id");
        $stmt->execute(['id' => $id]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        return $data ?: null;
    }
}
