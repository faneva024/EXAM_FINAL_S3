<?php

<<<<<<< HEAD
class DashboardController {

    /**
     * Page d'accueil - Dashboard principal
     */
    public static function index(): void {
        $besoinsModel = new BesoinsModel();
        $donsModel = new DonsModel();

        $stats = $besoinsModel->getStats();
        $resumVilles = $besoinsModel->getResumParVille();
        $resumCategories = $besoinsModel->getResumParCategorie();
        $donsCategories = $donsModel->getResumParCategorie();

        Flight::render('dashboard/index', [
            'title' => 'Dashboard - BNGRC',
            'stats' => $stats,
            'resumVilles' => $resumVilles,
            'resumCategories' => $resumCategories,
            'donsCategories' => $donsCategories,
        ]);
    }

    /**
     * Liste de tous les besoins
     */
    public static function besoins(): void {
        $besoinsModel = new BesoinsModel();
        $villeModel = new VilleModel();

        $besoins = $besoinsModel->getAll();
        $villes = $villeModel->getAll();

        // Filtrage par ville si paramètre GET
        $filtreVille = isset($_GET['ville']) ? (int)$_GET['ville'] : null;
        if ($filtreVille) {
            $besoins = $besoinsModel->getByVille($filtreVille);
        }

        Flight::render('dashboard/besoins', [
            'title' => 'Tous les besoins - BNGRC',
            'besoins' => $besoins,
            'villes' => $villes,
            'filtreVille' => $filtreVille,
        ]);
    }

    /**
     * Détail des besoins d'une ville
     */
    public static function villeDetail(int $id): void {
        $besoinsModel = new BesoinsModel();
        $villeModel = new VilleModel();

        $ville = $villeModel->getById($id);
        if (!$ville) {
            Flight::notFound();
            return;
        }

        $besoins = $besoinsModel->getByVille($id);

        Flight::render('dashboard/ville_detail', [
            'title' => $ville['nom_ville'] . ' - Besoins',
            'ville' => $ville,
            'besoins' => $besoins,
=======
namespace app\controllers;

use app\models\RecapModel;
use app\models\DonModel;
use Flight;
use flight\Engine;

class DashboardController extends BaseController
{
    protected RecapModel $recapModel;
    protected DonModel $donModel;

    public function __construct(?Engine $app = null)
    {
        parent::__construct($app);
        $this->recapModel = new RecapModel();
        $this->donModel = new DonModel();
    }

    public function dashboard(): void
    {
        $recap = $this->recapModel->parVille();
        $totaux = $this->recapModel->totaux();
        $argentDispo = $this->donModel->argentDisponible();
        Flight::render('dashboard', [
            'recap' => $recap,
            'totaux' => $totaux,
            'argentDispo' => $argentDispo,
>>>>>>> DEV
        ]);
    }
}
