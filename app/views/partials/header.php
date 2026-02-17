<?php
/**
 * Top header bar - BNGRC
 */
?>
<header class="top-header">
    <div class="header-left">
        <button class="sidebar-toggle" onclick="document.getElementById('sidebar').classList.toggle('collapsed'); document.querySelector('.main-content').classList.toggle('expanded');">
            <i class="bi bi-list"></i>
        </button>

        <h1 class="page-title-header"><?= htmlspecialchars($pageTitle ?? 'BNGRC') ?></h1>
    </div>
    <div class="header-right">
        <span class="header-badge">
            <i class="bi bi-circle-fill text-success"></i> En ligne
        </span>
        <span class="header-date">
            <i class="bi bi-calendar3"></i> <?= date('d/m/Y') ?>
        </span>
    </div>
</header>
