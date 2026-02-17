<?php

namespace app\controllers;

use app\models\AchatModel;
use app\models\VilleModel;
use app\models\DonModel;
use app\models\BesoinModel;
use app\models\ConfigurationModel;
use Flight;
use flight\Engine;

class AchatController extends BaseController
{
    protected AchatModel $achatModel;
    protected VilleModel $villeModel;
    protected DonModel $donModel;
    protected BesoinModel $besoinModel;
    protected ConfigurationModel $configModel;

    public function __construct(?Engine $app = null)
    {
        parent::__construct($app);
        $this->achatModel = new AchatModel();
        $this->villeModel = new VilleModel();
        $this->donModel = new DonModel();
        $this->besoinModel = new BesoinModel();
        $this->configModel = new ConfigurationModel();
    }

    public function liste(): void
    {
        $idVille = Flight::request()->query->idVille ? (int) Flight::request()->query->idVille : null;
        $achats = $this->achatModel->all($idVille);
        $villes = $this->villeModel->all();
        $argentDispo = $this->donModel->argentDisponible();
        Flight::render('achats/liste', [
            'achats' => $achats,
            'villes' => $villes,
            'argentDispo' => $argentDispo,
            'filtreVille' => $idVille,
        ]);
    }

    public function ajouter(): void
    {
        $data = Flight::request()->data;
        $idBesoin = (int) $data->id_besoin;
        $quantite = (float) $data->quantite;

        if ($idBesoin <= 0 || $quantite <= 0) {
            $this->redirect('/besoins/restants?error=' . urlencode('Données invalides'));
            return;
        }

        // Récupérer le besoin pour avoir le prix unitaire et la catégorie
        $besoin = $this->besoinModel->findById($idBesoin);
        if (!$besoin) {
            $this->redirect('/besoins/restants?error=' . urlencode('Besoin introuvable'));
            return;
        }

        // On ne peut acheter que des besoins en nature (1) ou en matériels (2), pas en argent (3)
        if ($besoin['id_categorie'] == 3) {
            $this->redirect('/besoins/restants?error=' . urlencode('Les besoins en argent ne peuvent pas être achetés.'));
            return;
        }

        // Vérifier que la quantité ne dépasse pas le restant
        if ($quantite > $besoin['quantite_restante']) {
            $this->redirect('/besoins/restants?error=' . urlencode('Quantité demandée supérieure au besoin restant.'));
            return;
        }

        // Vérifier s'il existe encore des dons en nature/matériels pour cet article
        // qui n'ont pas été dispatchés (on doit d'abord dispatcher avant d'acheter)
        $donsRestants = $this->donModel->restantsParNom($besoin['nom_besoin'], $besoin['id_categorie']);
        if (!empty($donsRestants)) {
            $this->redirect('/besoins/restants?error=' . urlencode('Des dons en nature/matériels existent encore pour "' . $besoin['nom_besoin'] . '". Faites le dispatch avant d\'acheter.'));
            return;
        }

        $frais = $this->configModel->getFraisAchat();
        $montantTotal = $quantite * $besoin['prix_unitaire'] * (1 + $frais / 100);

        // Vérifier argent disponible
        $argentDispo = $this->donModel->argentDisponible();
        if ($montantTotal > $argentDispo) {
            $this->redirect('/besoins/restants?error=' . urlencode('Argent insuffisant. Coût: ' . number_format($montantTotal, 0, ',', ' ') . ' Ar, Disponible: ' . number_format($argentDispo, 0, ',', ' ') . ' Ar'));
            return;
        }

        $this->achatModel->ajouter($idBesoin, $quantite, $besoin['prix_unitaire'], $frais);
        $this->redirect('/achats?success=1');
    }
}
