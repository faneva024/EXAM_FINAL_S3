<?php

namespace app\controllers;

use app\models\BesoinModel;
use app\models\VilleModel;
use app\models\CategorieModel;
use app\models\DonModel;
use app\models\ConfigurationModel;
use Flight;
use flight\Engine;

class BesoinController extends BaseController
{
    protected BesoinModel $besoinModel;
    protected VilleModel $villeModel;
    protected CategorieModel $categorieModel;
    protected DonModel $donModel;
    protected ConfigurationModel $configModel;

    public function __construct(?Engine $app = null)
    {
        parent::__construct($app);
        $this->besoinModel = new BesoinModel();
        $this->villeModel = new VilleModel();
        $this->categorieModel = new CategorieModel();
        $this->donModel = new DonModel();
        $this->configModel = new ConfigurationModel();
    }

    public function liste(): void
    {
        $besoins = $this->besoinModel->all();
        Flight::render('besoins/liste', ['besoins' => $besoins]);
    }

    public function formulaire(): void
    {
        $villes = $this->villeModel->all();
        $categories = $this->categorieModel->all();
        Flight::render('besoins/form', [
            'villes' => $villes,
            'categories' => $categories,
        ]);
    }

    public function ajouter(): void
    {
        $data = Flight::request()->data;
        $idVille = (int) $data->id_ville;
        $idCategorie = (int) $data->id_categorie;

        if ($idVille <= 0 || $idCategorie <= 0) {
            $this->redirect('/besoins/ajouter?error=1');
            return;
        }

        if ($idCategorie == 3) {
            // Besoin en argent : quantité = 1, prix_unitaire = montant total
            $nomBesoin = trim($data->nom_besoin_argent ?? '');
            $montant = (float) $data->montant_besoin;
            if ($nomBesoin === '' || $montant <= 0) {
                $this->redirect('/besoins/ajouter?error=1');
                return;
            }
            $this->besoinModel->ajouter($idVille, $idCategorie, $nomBesoin, $montant, 1);
        } else {
            // Besoin en nature ou matériels
            $nomBesoin = trim($data->nom_besoin ?? '');
            $prixUnitaire = (float) $data->prix_unitaire;
            $quantite = (float) $data->quantite;
            if ($nomBesoin === '' || $prixUnitaire <= 0 || $quantite <= 0) {
                $this->redirect('/besoins/ajouter?error=1');
                return;
            }
            $this->besoinModel->ajouter($idVille, $idCategorie, $nomBesoin, $prixUnitaire, $quantite);
        }

        $this->redirect('/besoins');
    }

    public function restants(): void
    {
        $idVille = Flight::request()->query->idVille ? (int) Flight::request()->query->idVille : null;
        $besoins = $this->besoinModel->restants($idVille);
        $villes = $this->villeModel->all();
        $argentDispo = $this->donModel->argentDisponible();
        $frais = $this->configModel->getFraisAchat();
        Flight::render('besoins/restants', [
            'besoins' => $besoins,
            'villes' => $villes,
            'argentDispo' => $argentDispo,
            'frais' => $frais,
            'filtreVille' => $idVille,
        ]);
    }
}
