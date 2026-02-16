<?php
/**
 * Configuration des routes Flight
 */

// Page d'accueil - Dashboard
Flight::route('/', ['DashboardController', 'index']);

// Liste de tous les besoins
Flight::route('/besoins', ['DashboardController', 'besoins']);

// Détail des besoins d'une ville
Flight::route('/ville/@id:[0-9]+', ['DashboardController', 'villeDetail']);

// =============================================
// Routes Auth (auto-login)
// =============================================
Flight::route('GET /login', ['AuthController', 'loginForm']);
Flight::route('POST /login', ['AuthController', 'login']);
Flight::route('/logout', ['AuthController', 'logout']);

// =============================================
// Routes Dons
// =============================================
Flight::route('GET /dons/liste', ['DonController', 'listeDons']);
Flight::route('GET /dons/nouveau', ['DonController', 'create']);
Flight::route('POST /dons/nouveau', ['DonController', 'store']);
Flight::route('GET /dons/mes-dons', ['DonController', 'mesDons']);

// =============================================
// Routes Admin
// =============================================
Flight::route('GET /admin/login', ['AuthController', 'adminLoginForm']);
Flight::route('POST /admin/login', ['AuthController', 'adminLogin']);
Flight::route('GET /admin/dons', ['AuthController', 'adminDons']);

// Page 404
Flight::map('notFound', function () {
    include __DIR__ . '/../inc/header.php';
    echo '<div class="text-center py-5">';
    echo '<h1 class="display-1 text-muted">404</h1>';
    echo '<p class="lead">Page non trouvée</p>';
    echo '<a href="/" class="btn btn-primary"><i class="bi bi-house me-2"></i>Retour au Dashboard</a>';
    echo '</div>';
    include __DIR__ . '/../inc/footer.php';
});
