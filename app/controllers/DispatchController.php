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
        $strategy = Flight::request()->data->strategy ?? DispatchModel::STRATEGY_DATE;
        $resultats = $this->dispatchModel->simuler($strategy);
        Flight::render('dispatch/simulation', [
            'resultats' => $resultats,
            'strategy' => $strategy,
        ]);
    }

    public function valider(): void
    {
        $strategy = Flight::request()->data->strategy ?? DispatchModel::STRATEGY_DATE;
        $count = $this->dispatchModel->valider($strategy);
        $this->redirect('/dispatch?validated=' . $count);
    }

    public function reinitialiser(): void
    {
        $success = $this->dispatchModel->reinitialiser();
        if ($success) {
            $this->redirect('/dispatch?reset=1');
        } else {
            $this->redirect('/dispatch?error=reset');
        }
    }
}
