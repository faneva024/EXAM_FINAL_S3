<?php

class DonController {

    /**
     * Vérifier que l'utilisateur est connecté
     * Sinon, rediriger vers le login
     */
    private static function requireLogin(): bool {
        if (!isset($_SESSION['user'])) {
            $_SESSION['redirect_after_login'] = '/dons/nouveau';
            Flight::redirect('/login');
            return false;
        }
        return true;
    }

    /**
     * Formulaire pour faire un don
     */
    public static function create(): void {
        if (!self::requireLogin()) return;

        $besoinsModel = new BesoinsModel();
        $categories = $besoinsModel->getResumParCategorie();

        Flight::render('dons/create', [
            'title' => 'Faire un don - BNGRC',
            'categories' => $categories,
            'success' => null,
            'error' => null,
        ]);
    }

    /**
     * Traitement du formulaire de don
     */
    public static function store(): void {
        if (!self::requireLogin()) return;

        $idCategorie = (int)($_POST['id_categorie'] ?? 0);
        $nomDon = trim($_POST['nom_don'] ?? '');
        $quantite = (int)($_POST['quantite'] ?? 0);
        $montant = !empty($_POST['montant']) ? (float)$_POST['montant'] : null;

        // Validation
        $errors = [];
        if ($idCategorie <= 0) $errors[] = 'Veuillez choisir une catégorie.';
        if (empty($nomDon)) $errors[] = 'Veuillez entrer le nom du don.';
        if ($quantite <= 0) $errors[] = 'La quantité doit être supérieure à 0.';

        if (!empty($errors)) {
            $besoinsModel = new BesoinsModel();
            $categories = $besoinsModel->getResumParCategorie();

            Flight::render('dons/create', [
                'title' => 'Faire un don - BNGRC',
                'categories' => $categories,
                'success' => null,
                'error' => implode('<br>', $errors),
            ]);
            return;
        }

        $donsModel = new DonsModel();
        $idUser = $_SESSION['user']['id_user'];
        $donsModel->insert($idUser, $idCategorie, $nomDon, $quantite, $montant);

        $besoinsModel = new BesoinsModel();
        $categories = $besoinsModel->getResumParCategorie();

        Flight::render('dons/create', [
            'title' => 'Faire un don - BNGRC',
            'categories' => $categories,
            'success' => 'Votre don a été enregistré avec succès ! Merci pour votre générosité.',
            'error' => null,
        ]);
    }

    /**
     * Historique des dons de l'utilisateur connecté
     */
    public static function mesDons(): void {
        if (!self::requireLogin()) return;

        $donsModel = new DonsModel();
        $dons = $donsModel->getByUser($_SESSION['user']['id_user']);

        Flight::render('dons/mes_dons', [
            'title' => 'Mes dons - BNGRC',
            'dons' => $dons,
        ]);
    }

    /**
     * Liste publique des dons (anonyme - sans nom du donneur)
     * Accessible à tous les utilisateurs
     */
    public static function listeDons(): void {
        $donsModel = new DonsModel();
        $dons = $donsModel->getAllAnonymous();
        $stats = $donsModel->getStatsGlobales();

        Flight::render('dons/liste', [
            'title' => 'Liste des dons - BNGRC',
            'dons' => $dons,
            'stats' => $stats,
        ]);
    }
}
