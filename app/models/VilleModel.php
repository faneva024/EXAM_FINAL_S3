<?php

namespace app\models;

use Flight;
use PDO;

class VilleModel
{
    public function all(): array
    {
        $stmt = Flight::db()->query("SELECT * FROM BNGRC_Ville ORDER BY nom_ville");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
