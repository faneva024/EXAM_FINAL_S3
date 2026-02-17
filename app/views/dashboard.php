<?php
/**
 * Tableau de bord BNGRC
 * @var array $recap
 * @var array $totaux
 * @var float $argentDispo
 */
$pageTitle = 'Tableau de bord';
include __DIR__ . '/layout_header.php';
?>

<div class="page-header animate-in">
    <h2><i class="bi bi-speedometer2"></i> Tableau de bord</h2>
    <p>Vue d'ensemble de la situation des collectes et distributions</p>
</div>

<!-- Stat Cards -->
<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="stat-card primary animate-in">
            <i class="bi bi-clipboard-data stat-icon"></i>
            <div class="stat-label">Total Besoins</div>
            <div class="stat-value"><?= number_format($totaux['total_besoins'] ?? 0, 0, ',', ' ') ?> Ar</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card success animate-in">
            <i class="bi bi-check-circle stat-icon"></i>
            <div class="stat-label">Besoins Satisfaits</div>
            <div class="stat-value"><?= number_format($totaux['total_satisfaits'] ?? 0, 0, ',', ' ') ?> Ar</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card danger animate-in">
            <i class="bi bi-exclamation-circle stat-icon"></i>
            <div class="stat-label">Besoins Restants</div>
            <div class="stat-value"><?= number_format($totaux['total_restants'] ?? 0, 0, ',', ' ') ?> Ar</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card warning animate-in">
            <i class="bi bi-cash-coin stat-icon"></i>
            <div class="stat-label">Argent Disponible</div>
            <div class="stat-value"><?= number_format($argentDispo, 0, ',', ' ') ?> Ar</div>
        </div>
    </div>
</div>

<!-- Tableau par ville -->
<div class="data-card animate-in">
    <div class="data-card-header">
        <i class="bi bi-geo-alt"></i> Récapitulatif par Ville
    </div>
    <div class="data-card-body">
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>Ville</th>
                        <th class="text-end">Total Besoins</th>
                        <th class="text-end">Dons Attribués</th>
                        <th class="text-end">Restants</th>
                        <th class="text-center" style="min-width:140px;">Progression</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($recap)): ?>
                        <tr><td colspan="5" class="text-center text-muted py-4">Aucune donnée disponible</td></tr>
                    <?php else: ?>
                        <?php foreach ($recap as $row): ?>
                            <?php
                                $restant = $row['total_besoins'] - $row['total_satisfaits'];
                                $pourcent = $row['total_besoins'] > 0 ? round(($row['total_satisfaits'] / $row['total_besoins']) * 100) : 0;
                            ?>
                            <tr>
                                <td><strong><?= htmlspecialchars($row['nom_ville']) ?></strong></td>
                                <td class="text-end"><?= number_format($row['total_besoins'], 0, ',', ' ') ?> Ar</td>
                                <td class="text-end text-success"><?= number_format($row['total_satisfaits'], 0, ',', ' ') ?> Ar</td>
                                <td class="text-end text-danger"><?= number_format($restant, 0, ',', ' ') ?> Ar</td>
                                <td>
                                    <div class="progress">
                                        <div class="progress-bar bg-success" style="width: <?= $pourcent ?>%"><?= $pourcent ?>%</div>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include __DIR__ . '/layout_footer.php'; ?>
