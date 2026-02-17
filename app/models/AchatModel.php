<?php

namespace app\models;

use Flight;
use PDO;

class AchatModel
{
    public function all(?int $idVille = null): array
    {
        $sql = "
            SELECT ac.*, b.nom_besoin, v.nom_ville
            FROM BNGRC_Achat ac
            JOIN BNGRC_Besoins b ON ac.id_besoin = b.id_besoin
            JOIN BNGRC_Ville v ON b.id_ville = v.id_ville
        ";
        $params = [];

        if ($idVille) {
            $sql .= " WHERE b.id_ville = :idVille";
            $params['idVille'] = $idVille;
        }

        $sql .= " ORDER BY ac.date_achat DESC";

        $stmt = Flight::db()->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function ajouter(int $idBesoin, float $quantite, float $prixUnitaire, float $fraisPourcent): bool
    {
        $montantHT = $quantite * $prixUnitaire;
        $montantTotal = $montantHT * (1 + $fraisPourcent / 100);

        $stmt = Flight::db()->prepare("
            INSERT INTO BNGRC_Achat (id_besoin, quantite, prix_unitaire, frais_pourcent, montant_total)
            VALUES (:idBesoin, :quantite, :prixUnitaire, :fraisPourcent, :montantTotal)
        ");
        return $stmt->execute([
            'idBesoin' => $idBesoin,
            'quantite' => $quantite,
            'prixUnitaire' => $prixUnitaire,
            'fraisPourcent' => $fraisPourcent,
            'montantTotal' => $montantTotal,
        ]);
    }
}
