<?php

class BesoinsModel {
    private PDO $db;

    public function __construct() {
        $this->db = getDB();
    }

    /**
     * Récupérer tous les besoins avec les infos ville et catégorie
     */
    public function getAll(): array {
        $sql = "SELECT b.*, v.nom_ville, c.nom_categorie
                FROM BNGRC_Besoins b
                JOIN BNGRC_Ville v ON b.id_ville = v.id_ville
                JOIN BNGRC_CategoriesBesoins c ON b.id_categorie = c.id_categorie
                ORDER BY v.nom_ville, c.nom_categorie";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll();
    }

    /**
     * Récupérer les besoins par ville
     */
    public function getByVille(int $idVille): array {
        $sql = "SELECT b.*, v.nom_ville, c.nom_categorie
                FROM BNGRC_Besoins b
                JOIN BNGRC_Ville v ON b.id_ville = v.id_ville
                JOIN BNGRC_CategoriesBesoins c ON b.id_categorie = c.id_categorie
                WHERE b.id_ville = ?
                ORDER BY c.nom_categorie";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$idVille]);
        return $stmt->fetchAll();
    }

    /**
     * Récupérer le résumé des besoins par ville (total quantité et montant)
     */
    public function getResumParVille(): array {
        $sql = "SELECT v.id_ville, v.nom_ville,
                       COUNT(b.id_besoin) AS nb_besoins,
                       SUM(b.quantite) AS total_quantite,
                       SUM(b.prix_unitaire * b.quantite) AS montant_total
                FROM BNGRC_Ville v
                LEFT JOIN BNGRC_Besoins b ON v.id_ville = b.id_ville
                GROUP BY v.id_ville, v.nom_ville
                ORDER BY montant_total DESC";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll();
    }

    /**
     * Récupérer les besoins par catégorie (pour les stats)
     */
    public function getResumParCategorie(): array {
        $sql = "SELECT c.id_categorie, c.nom_categorie,
                       COUNT(b.id_besoin) AS nb_besoins,
                       SUM(b.quantite) AS total_quantite,
                       SUM(b.prix_unitaire * b.quantite) AS montant_total
                FROM BNGRC_CategoriesBesoins c
                LEFT JOIN BNGRC_Besoins b ON c.id_categorie = b.id_categorie
                GROUP BY c.id_categorie, c.nom_categorie
                ORDER BY montant_total DESC";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll();
    }

    /**
     * Stats globales
     */
    public function getStats(): array {
        $sql = "SELECT 
                    (SELECT COUNT(*) FROM BNGRC_Ville) AS total_villes,
                    (SELECT COUNT(*) FROM BNGRC_Besoins) AS total_besoins,
                    (SELECT COALESCE(SUM(prix_unitaire * quantite), 0) FROM BNGRC_Besoins) AS montant_total_besoins,
                    (SELECT COUNT(*) FROM BNGRC_Dons) AS total_dons,
                    (SELECT COALESCE(SUM(montant), 0) FROM BNGRC_Dons) AS montant_total_dons,
                    (SELECT COUNT(*) FROM BNGRC_Dispatch) AS total_dispatches";
        $stmt = $this->db->query($sql);
        return $stmt->fetch();
    }
}
