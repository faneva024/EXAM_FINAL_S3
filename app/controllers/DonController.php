<?php

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
    }
}
