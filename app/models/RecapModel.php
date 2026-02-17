<?php

namespace app\models;

use Flight;
use PDO;

class RecapModel
{
    public function parVille(): array
    {
        $stmt = Flight::db()->query("SELECT * FROM v_recap_ville ORDER BY nom_ville");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function totaux(): array
    {
        $stmt = Flight::db()->query("
            SELECT 
                COALESCE(SUM(total_besoins), 0) AS total_besoins,
                COALESCE(SUM(total_satisfaits), 0) AS total_satisfaits,
                (COALESCE(SUM(total_besoins), 0) - COALESCE(SUM(total_satisfaits), 0)) AS total_restants
            FROM v_recap_ville
        ");
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
