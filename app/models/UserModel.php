<?php

namespace app\models;

use Flight;
use PDO;

class UserModel
{
    public function all(): array
    {
        $stmt = Flight::db()->query("SELECT * FROM BNGRC_User ORDER BY nom");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function findById(int $id): ?array
    {
        $stmt = Flight::db()->prepare("SELECT * FROM BNGRC_User WHERE id_user = :id");
        $stmt->execute(['id' => $id]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        return $data ?: null;
    }
}
