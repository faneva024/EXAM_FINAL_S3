<?php
/**
 * BNGRC - Plateforme de gestion
 * Point d'entrée principal
 */

// Démarrage de la session
session_start();

// Autoloader de Composer
require __DIR__ . '/../vendor/autoload.php';

// Configuration base de données
require __DIR__ . '/../app/config/database.php';

// Chargement des modèles
require __DIR__ . '/../app/models/VilleModel.php';
require __DIR__ . '/../app/models/BesoinsModel.php';
require __DIR__ . '/../app/models/DonsModel.php';

// Chargement des contrôleurs
require __DIR__ . '/../app/controllers/DashboardController.php';

// Configuration de Flight - chemin des vues
Flight::set('flight.views.path', __DIR__ . '/../app/views');

// Chargement des routes
require __DIR__ . '/../app/config/route.php';

// Démarrage de l'application Flight
Flight::start();
