<?php

<<<<<<< HEAD
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
=======
namespace app\controllers;

use app\models\DonModel;
use app\models\CategorieModel;
use app\models\UserModel;
use Flight;
use flight\Engine;

class DonController extends BaseController
{
    protected DonModel $donModel;
    protected CategorieModel $categorieModel;
    protected UserModel $userModel;

    public function __construct(?Engine $app = null)
    {
        parent::__construct($app);
        $this->donModel = new DonModel();
        $this->categorieModel = new CategorieModel();
        $this->userModel = new UserModel();
    }

    public function liste(): void
    {
        $dons = $this->donModel->all();
        $argentDispo = $this->donModel->argentDisponible();
        Flight::render('dons/liste', [
            'dons' => $dons,
            'argentDispo' => $argentDispo,
        ]);
    }

    public function formulaire(): void
    {
        $categories = $this->categorieModel->all();
        $users = $this->userModel->all();
        Flight::render('dons/form', [
            'categories' => $categories,
            'users' => $users,
        ]);
    }

    public function ajouter(): void
    {
        $data = Flight::request()->data;
        $idUser = (int) $data->id_user;
        $idCategorie = (int) $data->id_categorie;
        $nomDon = trim($data->nom_don ?? '');

        if ($idUser <= 0 || $idCategorie <= 0 || $nomDon === '') {
            $this->redirect('/dons/ajouter?error=1');
            return;
        }

        if ($idCategorie == 3) {
            // Don en argent
            $montant = (float) $data->montant;
            if ($montant <= 0) {
                $this->redirect('/dons/ajouter?error=1');
                return;
            }
            $this->donModel->ajouter($idUser, $idCategorie, $nomDon, null, $montant);
        } else {
            // Don en nature ou matériels
            $quantite = (float) $data->quantite;
            if ($quantite <= 0) {
                $this->redirect('/dons/ajouter?error=1');
                return;
            }
            $this->donModel->ajouter($idUser, $idCategorie, $nomDon, $quantite, null);
        }

        $this->redirect('/dons');
>>>>>>> DEV
    }
}
