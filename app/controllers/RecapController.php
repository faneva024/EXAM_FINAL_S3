<?php

namespace app\controllers;

use app\models\RecapModel;
use app\models\ConfigurationModel;
use Flight;
use flight\Engine;

class RecapController extends BaseController
{
    protected RecapModel $recapModel;
    protected ConfigurationModel $configModel;

    public function __construct(?Engine $app = null)
    {
        parent::__construct($app);
        $this->recapModel = new RecapModel();
        $this->configModel = new ConfigurationModel();
    }

    public function recapitulation(): void
    {
        $recap = $this->recapModel->parVille();
        $totaux = $this->recapModel->totaux();
        Flight::render('recapitulation', [
            'recap' => $recap,
            'totaux' => $totaux,
        ]);
    }

    public function apiRecapitulation(): void
    {
        $recap = $this->recapModel->parVille();
        $totaux = $this->recapModel->totaux();
        Flight::json([
            'recap' => $recap,
            'totaux' => $totaux,
        ]);
    }

    public function pageConfiguration(): void
    {
        $frais = $this->configModel->getFraisAchat();
        Flight::render('configuration', ['frais' => $frais]);
    }

    public function sauverConfiguration(): void
    {
        $data = Flight::request()->data;
        $frais = (float) $data->frais_achat_pourcent;
        if ($frais < 0) $frais = 0;
        $this->configModel->set('frais_achat_pourcent', (string) $frais);
        $this->redirect('/configuration?success=1');
    }
}
