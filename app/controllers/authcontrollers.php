<?php

class AuthController {

    /**
     * Afficher le formulaire de connexion (auto-login)
     */
    public static function loginForm(): void {
        // Si déjà connecté, rediriger vers le dashboard
        if (isset($_SESSION['user'])) {
            Flight::redirect('/');
            return;
        }

        Flight::render('auth/login', [
            'title' => 'Connexion - BNGRC',
            'error' => null,
        ]);
    }

    /**
     * Traitement du formulaire auto-login
     * Si l'email existe → connexion. Sinon → inscription auto + connexion.
     */
    public static function login(): void {
        $nom = trim($_POST['nom'] ?? '');
        $email = trim($_POST['email'] ?? '');

        // Validation
        if (empty($nom) || empty($email)) {
            Flight::render('auth/login', [
                'title' => 'Connexion - BNGRC',
                'error' => 'Veuillez remplir tous les champs.',
            ]);
            return;
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            Flight::render('auth/login', [
                'title' => 'Connexion - BNGRC',
                'error' => 'Veuillez entrer un email valide.',
            ]);
            return;
        }

        $authModel = new AuthModel();
        $user = $authModel->autoLogin($nom, $email);

        // Stocker en session
        $_SESSION['user'] = [
            'id_user' => $user['id_user'],
            'nom' => $user['nom'],
            'email' => $user['email'],
            'role' => $user['role'],
        ];

        // Rediriger vers la page de don ou le dashboard
        $redirect = $_SESSION['redirect_after_login'] ?? '/';
        unset($_SESSION['redirect_after_login']);
        Flight::redirect($redirect);
    }

    /**
     * Déconnexion
     */
    public static function logout(): void {
        unset($_SESSION['user']);
        session_destroy();
        Flight::redirect('/');
    }

    // =============================================
    // ADMIN LOGIN
    // =============================================

    /**
     * Afficher le formulaire de connexion admin
     */
    public static function adminLoginForm(): void {
        if (isset($_SESSION['user']) && $_SESSION['user']['role'] === 'admin') {
            Flight::redirect('/admin/dons');
            return;
        }

        Flight::render('auth/admin_login', [
            'title' => 'Admin - Connexion - BNGRC',
            'error' => null,
        ]);
    }

    /**
     * Traitement du formulaire admin login (email + mot de passe)
     */
    public static function adminLogin(): void {
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';

        if (empty($email) || empty($password)) {
            Flight::render('auth/admin_login', [
                'title' => 'Admin - Connexion - BNGRC',
                'error' => 'Veuillez remplir tous les champs.',
            ]);
            return;
        }

        $authModel = new AuthModel();
        $user = $authModel->adminLogin($email, $password);

        if (!$user) {
            Flight::render('auth/admin_login', [
                'title' => 'Admin - Connexion - BNGRC',
                'error' => 'Email ou mot de passe incorrect, ou vous n\'êtes pas administrateur.',
            ]);
            return;
        }

        $_SESSION['user'] = [
            'id_user' => $user['id_user'],
            'nom' => $user['nom'],
            'email' => $user['email'],
            'role' => $user['role'],
        ];

        Flight::redirect('/admin/dons');
    }

    // =============================================
    // ADMIN PAGES
    // =============================================

    /**
     * Admin : voir tous les dons avec détails des donneurs
     */
    public static function adminDons(): void {
        if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
            Flight::redirect('/admin/login');
            return;
        }

        $donsModel = new DonsModel();
        $authModel = new AuthModel();

        $dons = $donsModel->getAll();
        $stats = $donsModel->getStatsGlobales();
        $donneurs = $authModel->getAllDonneurs();

        Flight::render('admin/dons', [
            'title' => 'Admin - Gestion des dons - BNGRC',
            'dons' => $dons,
            'stats' => $stats,
            'donneurs' => $donneurs,
        ]);
    }
}
