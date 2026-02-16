<?php

class AuthModel {
    private PDO $db;

    public function __construct() {
        $this->db = getDB();
    }

    /**
     * Chercher un utilisateur par email
     */
    public function getByEmail(string $email): ?array {
        $stmt = $this->db->prepare("SELECT * FROM BNGRC_User WHERE email = ?");
        $stmt->execute([$email]);
        $result = $stmt->fetch();
        return $result ?: null;
    }

    /**
     * Créer un utilisateur automatiquement (auto-inscription)
     * Retourne l'utilisateur créé
     */
    public function autoRegister(string $nom, string $email): array {
        // Mot de passe par défaut (auto-login, pas besoin de mot de passe complexe)
        $mdp_ParDefaut = password_hash('auto_' . time(), PASSWORD_DEFAULT);

        $stmt = $this->db->prepare(
            "INSERT INTO BNGRC_User (nom, email, mot_de_passe, role) VALUES (?, ?, ?, 'donneur')"
        );
        $stmt->execute([$nom, $email, $mdp_ParDefaut]);

        return $this->getByEmail($email);
    }

    /**
     * Auto-login : cherche l'utilisateur par email, sinon le crée
     * Retourne l'utilisateur
     */
    public function autoLogin(string $nom, string $email): array {
        $user = $this->getByEmail($email);

        if ($user) {
            return $user;
        }

        return $this->autoRegister($nom, $email);
    }

    /**
     * Récupérer un utilisateur par ID
     */
    public function getById(int $id): ?array {
        $stmt = $this->db->prepare("SELECT * FROM BNGRC_User WHERE id_user = ?");
        $stmt->execute([$id]);
        $result = $stmt->fetch();
        return $result ?: null;
    }

    /**
     * Login admin avec email + mot de passe
     */
    public function adminLogin(string $email, string $password): ?array {
        $user = $this->getByEmail($email);

        if (!$user) return null;
        if ($user['role'] !== 'admin') return null;
        if (!password_verify($password, $user['mot_de_passe'])) return null;

        return $user;
    }

    /**
     * Récupérer tous les donneurs avec le nombre de dons
     */
    public function getAllDonneurs(): array {
        $sql = "SELECT u.*, 
                       COUNT(d.id_don) AS nb_dons,
                       COALESCE(SUM(d.montant), 0) AS total_montant
                FROM BNGRC_User u
                LEFT JOIN BNGRC_Dons d ON u.id_user = d.id_user
                WHERE u.role = 'donneur'
                GROUP BY u.id_user
                ORDER BY total_montant DESC";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll();
    }
}
