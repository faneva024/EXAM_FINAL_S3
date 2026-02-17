<?php

namespace app\controllers;

use app\models\DonModel;
use app\models\CategorieModel;
use app\models\UserModel;
use app\models\BesoinModel;
use Flight;
use flight\Engine;

class DonController extends BaseController
{
    protected DonModel $donModel;
    protected CategorieModel $categorieModel;
    protected UserModel $userModel;
    protected BesoinModel $besoinModel;

    public function __construct(?Engine $app = null)
    {
        parent::__construct($app);
        $this->donModel = new DonModel();
        $this->categorieModel = new CategorieModel();
        $this->userModel = new UserModel();
        $this->besoinModel = new BesoinModel();
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
        $idUser = (int) ($data->id_user ?? 0);
        $nouveauNom = trim($data->nouveau_nom ?? '');
        $nouveauEmail = trim($data->nouveau_email ?? '');
        $idCategorie = (int) $data->id_categorie;
        $nomDon = trim($data->nom_don ?? '');

        // Si un nouveau donateur est saisi, on le crée ou on le retrouve
        if ($nouveauNom !== '') {
            $existant = $this->userModel->findByNom($nouveauNom);
            if ($existant) {
                $idUser = (int) $existant['id_user'];
            } else {
                $idUser = $this->userModel->ajouter($nouveauNom, $nouveauEmail);
            }
        }

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
            // Montant = prix_unitaire (du besoin) * quantité
            $montantCalc = (float) ($data->montant_calcule ?? 0);
            $this->donModel->ajouter($idUser, $idCategorie, $nomDon, $quantite, $montantCalc > 0 ? $montantCalc : null);
        }

        $this->redirect('/dons');
    }

    /**
     * API : retourne les noms de besoins existants pour une catégorie (suggestions)
     */
    public function suggestionBesoins(): void
    {
        $idCategorie = (int) (Flight::request()->query->id_categorie ?? 0);
        if ($idCategorie <= 0) {
            Flight::json([]);
            return;
        }
        $noms = $this->besoinModel->nomsParCategorie($idCategorie);
        Flight::json($noms);
    }
}
