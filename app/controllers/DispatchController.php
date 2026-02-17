<?php

namespace app\controllers;

use app\models\DispatchModel;
use Flight;
use flight\Engine;

class DispatchController extends BaseController
{
    protected DispatchModel $dispatchModel;

    public function __construct(?Engine $app = null)
    {
        parent::__construct($app);
        $this->dispatchModel = new DispatchModel();
    }

    public function index(): void
    {
        $historique = $this->dispatchModel->historique();
        Flight::render('dispatch/index', ['historique' => $historique]);
    }

    public function simuler(): void
    {
        $resultats = $this->dispatchModel->simuler();
        Flight::render('dispatch/simulation', ['resultats' => $resultats]);
    }

    public function valider(): void
    {
        $count = $this->dispatchModel->valider();
        $this->redirect('/dispatch?validated=' . $count);
    }
}
