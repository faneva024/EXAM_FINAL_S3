<?php
/**
 * Takalo-takalo - Plateforme d'échange d'objets
 * Point d'entrée principal
 */

// Démarrage de la session
session_start();

// Autoloader de Composer
require __DIR__ . '/../vendor/autoload.php';

// Chargement des fichiers de configuration
require __DIR__ . '/../app/config/bootstrap.php';
require __DIR__ . '/../app/config/config.php';
require __DIR__ . '/../app/config/services.php';
require __DIR__ . '/../app/config/routes.php';

// Démarrage de l'application Flight
Flight::start();
