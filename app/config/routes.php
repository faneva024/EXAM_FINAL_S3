<?php

use app\controllers\DashboardController;
use app\controllers\BesoinController;
use app\controllers\DonController;
use app\controllers\AchatController;
use app\controllers\DispatchController;
use app\controllers\RecapController;
use app\middlewares\SecurityHeadersMiddleware;
use flight\Engine;
use flight\net\Router;

/** 
 * @var Router $router 
 * @var Engine $app
 */

$router->group('', function(Router $router) use ($app) {

	// ========== TABLEAU DE BORD ==========
	$dashboard = new DashboardController($app);
	$router->get('/', [$dashboard, 'dashboard']);

	// ========== BESOINS ==========
	$besoin = new BesoinController($app);
	$router->get('/besoins', [$besoin, 'liste']);
	$router->get('/besoins/ajouter', [$besoin, 'formulaire']);
	$router->post('/besoins/ajouter', [$besoin, 'ajouter']);
	$router->get('/besoins/restants', [$besoin, 'restants']);

	// ========== DONS ==========
	$don = new DonController($app);
	$router->get('/dons', [$don, 'liste']);
	$router->get('/dons/ajouter', [$don, 'formulaire']);
	$router->post('/dons/ajouter', [$don, 'ajouter']);

	// ========== ACHATS ==========
	$achat = new AchatController($app);
	$router->get('/achats', [$achat, 'liste']);
	$router->post('/achats/ajouter', [$achat, 'ajouter']);

	// ========== DISPATCH ==========
	$dispatch = new DispatchController($app);
	$router->get('/dispatch', [$dispatch, 'index']);
	$router->post('/dispatch/simuler', [$dispatch, 'simuler']);
	$router->post('/dispatch/valider', [$dispatch, 'valider']);

	// ========== RÉCAPITULATION & CONFIG ==========
	$recap = new RecapController($app);
	$router->get('/recapitulation', [$recap, 'recapitulation']);
	$router->get('/api/recapitulation', [$recap, 'apiRecapitulation']);
	$router->get('/configuration', [$recap, 'pageConfiguration']);
	$router->post('/configuration', [$recap, 'sauverConfiguration']);

});
