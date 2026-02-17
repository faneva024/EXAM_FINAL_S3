<?php
<<<<<<< HEAD
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
require __DIR__ . '/../app/models/authmodels.php';

// Chargement des contrôleurs
require __DIR__ . '/../app/controllers/DashboardController.php';
require __DIR__ . '/../app/controllers/authcontrollers.php';
require __DIR__ . '/../app/controllers/DonController.php';

// Configuration de Flight - chemin des vues
Flight::set('flight.views.path', __DIR__ . '/../app/views');

// Chargement des routes
require __DIR__ . '/../app/config/route.php';

// Démarrage de l'application Flight
Flight::start();
=======
ini_set('display_errors',1);

/*
 * FlightPHP Framework
 * @copyright   Copyright (c) 2024, Mike Cao <mike@mikecao.com>, n0nag0n <n0nag0n@sky-9.com>
 * @license     MIT, http://flightphp.com/license
                                                                  .____   __ _
     __o__   _______ _ _  _                                     /     /
     \    ~\                                                  /      /
       \     '\                                         ..../      .'
        . ' ' . ~\                                      ' /       /
       .  _    .  ~ \  .+~\~ ~ ' ' " " ' ' ~ - - - - - -''_      /
      .  <#  .  - - -/' . ' \  __                          '~ - \
       .. -           ~-.._ / |__|  ( )  ( )  ( )  0  o    _ _    ~ .
     .-'                                               .- ~    '-.    -.
    <                      . ~ ' ' .             . - ~             ~ -.__~_. _ _
      ~- .       N121PP  .          . . . . ,- ~
            ' ~ - - - - =.   <#>    .         \.._
                        .     ~      ____ _ .. ..  .- .
                         .         '        ~ -.        ~ -.
                           ' . . '               ~ - .       ~-.
                                                       ~ - .      ~ .
                                                              ~ -...0..~. ____
   Cessna 402  (Wings)
   by Dick Williams, rjw1@tyrell.net
*/
$ds = DIRECTORY_SEPARATOR;
//require(__DIR__. $ds .'app' . $ds . 'config' . $ds . 'bootstrap.php');
require(__DIR__. $ds . '..' . $ds . 'app' . $ds . 'config' . $ds . 'bootstrap.php');
>>>>>>> DEV
