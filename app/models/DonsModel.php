<?php

class DonsModel {
    private PDO $db;

    public function __construct() {
        $this->db = getDB();
    }

    /**
     * Récupérer tous les dons avec infos utilisateur et catégorie
     */
    public function getAll(): array {
        $sql = "SELECT d.*, u.nom AS nom_donneur, c.nom_categorie
                FROM BNGRC_Dons d
                JOIN BNGRC_User u ON d.id_user = u.id_user
                JOIN BNGRC_CategoriesBesoins c ON d.id_categorie = c.id_categorie
                ORDER BY d.date_don DESC";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll();
    }

    /**
     * Insérer un nouveau don
     */
    public function insert(int $idUser, int $idCategorie, string $nomDon, int $quantite, ?float $montant): bool {
        $stmt = $this->db->prepare(
            "INSERT INTO BNGRC_Dons (id_user, id_categorie, nom_don, quantite, montant) 
             VALUES (?, ?, ?, ?, ?)"
        );
        return $stmt->execute([$idUser, $idCategorie, $nomDon, $quantite, $montant]);
    }

    /**
     * Récupérer les dons d'un utilisateur
     */
    public function getByUser(int $idUser): array {
        $sql = "SELECT d.*, c.nom_categorie
                FROM BNGRC_Dons d
                JOIN BNGRC_CategoriesBesoins c ON d.id_categorie = c.id_categorie
                WHERE d.id_user = ?
                ORDER BY d.date_don DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$idUser]);
        return $stmt->fetchAll();
    }

    /**
     * Récupérer tous les dons ANONYMES (sans info donneur) pour les users simples
     */
    public function getAllAnonymous(): array {
        $sql = "SELECT d.id_don, d.nom_don, d.quantite, d.montant, d.date_don, c.nom_categorie
                FROM BNGRC_Dons d
                JOIN BNGRC_CategoriesBesoins c ON d.id_categorie = c.id_categorie
                ORDER BY d.date_don DESC";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll();
    }

    /**
     * Récupérer les stats globales des dons
     */
    public function getStatsGlobales(): array {
        $sql = "SELECT 
                    COUNT(DISTINCT d.id_user) AS nb_donneurs,
                    COUNT(d.id_don) AS nb_dons,
                    COALESCE(SUM(d.quantite), 0) AS total_quantite,
                    COALESCE(SUM(d.montant), 0) AS montant_total
                FROM BNGRC_Dons d";
        $stmt = $this->db->query($sql);
        return $stmt->fetch();
    }

    /**
     * Résumé des dons par catégorie
     */
    public function getResumParCategorie(): array {
        $sql = "SELECT c.nom_categorie,
                       COUNT(d.id_don) AS nb_dons,
                       SUM(d.quantite) AS total_quantite,
                       SUM(d.montant) AS montant_total
                FROM BNGRC_CategoriesBesoins c
                LEFT JOIN BNGRC_Dons d ON c.id_categorie = d.id_categorie
                GROUP BY c.id_categorie, c.nom_categorie
                ORDER BY montant_total DESC";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll();
    }


}
