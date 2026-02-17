<?php

<<<<<<< HEAD
class VilleModel {
    private PDO $db;

    public function __construct() {
        $this->db = getDB();
    }

    /**
     * Récupérer toutes les villes
     */
    public function getAll(): array {
        $stmt = $this->db->query("SELECT * FROM BNGRC_Ville ORDER BY nom_ville");
        return $stmt->fetchAll();
    }

    /**
     * Récupérer une ville par son ID
     */
    public function getById(int $id): ?array {
        $stmt = $this->db->prepare("SELECT * FROM BNGRC_Ville WHERE id_ville = ?");
        $stmt->execute([$id]);
        $result = $stmt->fetch();
        return $result ?: null;
=======
namespace app\models;

use Flight;
use PDO;

class VilleModel
{
    public function all(): array
    {
        $stmt = Flight::db()->query("SELECT * FROM BNGRC_Ville ORDER BY nom_ville");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
>>>>>>> DEV
    }
}
