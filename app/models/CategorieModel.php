<?php

namespace app\models;

use Flight;
use PDO;

class CategorieModel
{
    public function all(): array
    {
        $stmt = Flight::db()->query("SELECT * FROM BNGRC_CategoriesBesoins ORDER BY id_categorie");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function nonArgent(): array
    {
        $stmt = Flight::db()->query("SELECT * FROM BNGRC_CategoriesBesoins WHERE id_categorie != 3 ORDER BY id_categorie");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function findById(int $id): ?array
    {
        $stmt = Flight::db()->prepare("SELECT * FROM BNGRC_CategoriesBesoins WHERE id_categorie = :id");
        $stmt->execute(['id' => $id]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        return $data ?: null;
    }
}
