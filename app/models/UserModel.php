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

    public function findByNom(string $nom): ?array
    {
        $stmt = Flight::db()->prepare("SELECT * FROM BNGRC_User WHERE nom = :nom LIMIT 1");
        $stmt->execute(['nom' => $nom]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        return $data ?: null;
    }

    public function ajouter(string $nom, string $email = '', string $role = 'donateur'): int
    {
        if ($email === '') {
            $email = strtolower(str_replace(' ', '.', $nom)) . '@donateur.local';
        }
        $stmt = Flight::db()->prepare(
            "INSERT INTO BNGRC_User (nom, email, mot_de_passe, role) VALUES (:nom, :email, :mdp, :role)"
        );
        $stmt->execute([
            'nom' => $nom,
            'email' => $email,
            'mdp' => password_hash('donateur', PASSWORD_DEFAULT),
            'role' => $role,
        ]);
        return (int) Flight::db()->lastInsertId();
    }
}
