<?php

// =============================
// ROUTE PAGE ACCUEIL
// =============================
Flight::route('/', function () {
    DashboardController::index();
});

// =============================
// DONS
// =============================

// Liste publique des dons
Flight::route('GET /dons/liste', function () {
    DonController::listeDons();
});

// Formulaire nouveau don
Flight::route('GET /dons/nouveau', function () {
    DonController::create();
});

// Enregistrer don
Flight::route('POST /dons/nouveau', function () {
    DonController::store();
});

// Mes dons (utilisateur connecté)
Flight::route('GET /dons/mes', function () {
    DonController::mesDons();
});

// =============================
// AUTHENTIFICATION
// =============================

// Page login
Flight::route('GET /login', function () {
    AuthControllers::loginForm();
});

// Traitement login
Flight::route('POST /login', function () {
    AuthControllers::login();
});

// Logout
Flight::route('/logout', function () {
    AuthControllers::logout();
});


// =============================
// DASHBOARD
// =============================
Flight::route('/dashboard', function () {
    DashboardController::index();
});


// =============================
// GESTION DES BESOINS
// =============================

// Liste besoins
Flight::route('/besoins', function () {
    DashboardController::besoins();
});

// Ajouter besoin (formulaire)
Flight::route('GET /besoins/add', function () {
    DashboardController::addBesoinForm();
});

// Enregistrer besoin
Flight::route('POST /besoins/add', function () {
    DashboardController::storeBesoin();
});


// =============================
// GESTION DES DONS
// =============================

// Liste dons
Flight::route('/dons', function () {
    DonController::index();
});

// Ajouter don (formulaire)
Flight::route('GET /dons/add', function () {
    DonController::addForm();
});

// Enregistrer don
Flight::route('POST /dons/add', function () {
    DonController::store();
});


// =============================
// SIMULATION DISPATCH
// =============================
Flight::route('/dispatch', function () {
    DonController::dispatch();
});

// =============================
// DETAIL D'UNE VILLE
// =============================
Flight::route('GET /ville/@id', function ($id) {
    DashboardController::villeDetail((int)$id);
});

