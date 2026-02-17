<?php

namespace app\controllers;

use Flight;
use flight\Engine;

abstract class BaseController
{
    protected Engine $app;

    public function __construct(?Engine $app = null)
    {
        $this->app = $app ?? Flight::app();
    }

    /**
     * Redirection fiable sans doublement du BASE_URL
     */
    protected function redirect(string $path): void
    {
        $scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
        $host = $_SERVER['HTTP_HOST'];
        $url = $scheme . '://' . $host . BASE_URL . $path;
        header('Location: ' . $url);
        exit;
    }
}
