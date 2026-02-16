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
