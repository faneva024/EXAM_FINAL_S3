<?php

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
        ]);
    }
}
