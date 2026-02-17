<?php
/**
 * Sidebar navigation - BNGRC
 */
$currentUri = $_SERVER['REQUEST_URI'] ?? '/';
$base = BASE_URL;

function isActive(string $currentUri, string $path): string {
    $base = BASE_URL;
    $full = $base . $path;
    if ($path === '/') {
        return ($currentUri === $full || $currentUri === $base || $currentUri === $base . '/') ? 'active' : '';
    }
    return str_starts_with($currentUri, $full) ? 'active' : '';
}
?>
<aside class="sidebar" id="sidebar">
    <div class="sidebar-brand">
        <img src="<?= BASE_URL ?>/assets/BNGRC.png" alt="BNGRC" class="sidebar-logo">
        <span>BNGRC</span>
    </div>
    <div class="sidebar-subtitle">Gestion des Dons</div>

    <nav class="sidebar-nav">
        <div class="nav-section-title">Principal</div>
        <a href="<?= $base ?>/" class="sidebar-link <?= isActive($currentUri, '/') ?>">
            <i class="bi bi-speedometer2"></i>
            <span>Tableau de bord</span>
        </a>

        <div class="nav-section-title">Collecte</div>
        <a href="<?= $base ?>/besoins" class="sidebar-link <?= isActive($currentUri, '/besoins') ?>">
            <i class="bi bi-clipboard-check"></i>
            <span>Besoins</span>
        </a>
        <a href="<?= $base ?>/besoins/ajouter" class="sidebar-link <?= isActive($currentUri, '/besoins/ajouter') ?>">
            <i class="bi bi-plus-circle"></i>
            <span>Saisir un besoin</span>
        </a>
        <a href="<?= $base ?>/besoins/restants" class="sidebar-link <?= isActive($currentUri, '/besoins/restants') ?>">
            <i class="bi bi-exclamation-triangle"></i>
            <span>Besoins restants</span>
        </a>

        <div class="nav-section-title">Dons</div>
        <a href="<?= $base ?>/dons" class="sidebar-link <?= isActive($currentUri, '/dons') ?>">
            <i class="bi bi-gift"></i>
            <span>Liste des dons</span>
        </a>
        <a href="<?= $base ?>/dons/ajouter" class="sidebar-link <?= isActive($currentUri, '/dons/ajouter') ?>">
            <i class="bi bi-plus-circle"></i>
            <span>Enregistrer un don</span>
        </a>

        <div class="nav-section-title">Distribution</div>
        <a href="<?= $base ?>/achats" class="sidebar-link <?= isActive($currentUri, '/achats') ?>">
            <i class="bi bi-cart"></i>
            <span>Achats effectues</span>
        </a>
        <a href="<?= $base ?>/dispatch" class="sidebar-link <?= isActive($currentUri, '/dispatch') ?>">
            <i class="bi bi-truck"></i>
            <span>Dispatch</span>
        </a>

        <div class="nav-section-title">Rapports</div>
        <a href="<?= $base ?>/recapitulation" class="sidebar-link <?= isActive($currentUri, '/recapitulation') ?>">
            <i class="bi bi-bar-chart-line"></i>
            <span>Récapitulation</span>
        </a>
        <a href="<?= $base ?>/configuration" class="sidebar-link <?= isActive($currentUri, '/configuration') ?>">
            <i class="bi bi-gear"></i>
            <span>Configuration</span>
        </a>
    </nav>

    <div class="sidebar-footer">
        <small>&copy; <?= date('Y') ?> BNGRC</small>
    </div>
</aside>
