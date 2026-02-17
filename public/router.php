<?php
/**
 * Router pour le serveur de développement PHP intégré (Windows/Linux)
 * 
 * Usage: php -S localhost:8000 -t public public/router.php
 * 
 * Ce fichier remplace le .htaccess (qui ne fonctionne qu'avec Apache)
 * pour router les requêtes vers FlightPHP.
 */

$uri = urldecode(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));
$file = __DIR__ . $uri;

// Si le fichier statique existe (CSS, images, JS, etc.), le servir directement
if ($uri !== '/' && is_file($file)) {
    // Corriger le Content-Type pour certains fichiers
    $ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));
    $mimeTypes = [
        'css'  => 'text/css',
        'js'   => 'application/javascript',
        'json' => 'application/json',
        'png'  => 'image/png',
        'jpg'  => 'image/jpeg',
        'jpeg' => 'image/jpeg',
        'gif'  => 'image/gif',
        'svg'  => 'image/svg+xml',
        'ico'  => 'image/x-icon',
        'woff' => 'font/woff',
        'woff2'=> 'font/woff2',
        'ttf'  => 'font/ttf',
    ];
    if (isset($mimeTypes[$ext])) {
        header('Content-Type: ' . $mimeTypes[$ext]);
    }
    return false; // Laisser le serveur intégré PHP servir le fichier
}

// Pour toutes les autres requêtes, router vers FlightPHP
$_SERVER['SCRIPT_NAME'] = '/index.php';
require __DIR__ . '/index.php';
