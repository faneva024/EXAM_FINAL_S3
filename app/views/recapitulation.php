<?php
/**
 * Page de récapitulation
 * @var array $recap
 * @var array $totaux
 */
$pageTitle = 'Récapitulation';
include __DIR__ . '/layout_header.php';
?>

<div class="page-header animate-in">
    <div class="d-flex justify-content-between align-items-start">
        <div>
            <h2><i class="bi bi-bar-chart-line"></i> Récapitulation</h2>
            <p>Vue consolidée de la situation par ville</p>
        </div>
        <button class="btn btn-primary" id="btnActualiser" onclick="actualiserRecap()">
            <i class="bi bi-arrow-clockwise"></i> Actualiser
        </button>
    </div>
</div>

<!-- Totaux Globaux -->
<div class="row g-3 mb-4" id="totauxCards">
    <div class="col-md-4">
        <div class="stat-card primary animate-in">
            <i class="bi bi-clipboard-data stat-icon"></i>
            <div class="stat-label">Total Besoins</div>
            <div class="stat-value" id="totalBesoins"><?= number_format($totaux['total_besoins'] ?? 0, 0, ',', ' ') ?> Ar</div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="stat-card success animate-in">
            <i class="bi bi-check-circle stat-icon"></i>
            <div class="stat-label">Besoins Satisfaits</div>
            <div class="stat-value" id="totalSatisfaits"><?= number_format($totaux['total_satisfaits'] ?? 0, 0, ',', ' ') ?> Ar</div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="stat-card danger animate-in">
            <i class="bi bi-exclamation-circle stat-icon"></i>
            <div class="stat-label">Besoins Restants</div>
            <div class="stat-value" id="totalRestants"><?= number_format($totaux['total_restants'] ?? 0, 0, ',', ' ') ?> Ar</div>
        </div>
    </div>
</div>

<!-- Tableau détaillé par ville -->
<div class="data-card animate-in">
    <div class="data-card-header">
        <i class="bi bi-geo-alt"></i> Détail par Ville
    </div>
    <div class="data-card-body">
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>Ville</th>
                        <th class="text-end">Total Besoins</th>
                        <th class="text-end">Satisfaits</th>
                        <th class="text-end">Restants</th>
                        <th class="text-center" style="min-width:140px;">Progression</th>
                    </tr>
                </thead>
                <tbody id="recapTableBody">
                    <?php if (empty($recap)): ?>
                        <tr><td colspan="5" class="text-center text-muted py-4">Aucune donnée</td></tr>
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
                                        <div class="progress-bar bg-success" style="width:<?= $pourcent ?>%"><?= $pourcent ?>%</div>
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

<script>
function formatNumber(num) {
    return new Intl.NumberFormat('fr-FR').format(Math.round(num));
}

function actualiserRecap() {
    const btn = document.getElementById('btnActualiser');
    btn.disabled = true;
    btn.innerHTML = '<i class="bi bi-hourglass-split"></i> Chargement...';

    fetch('<?= BASE_URL ?>/api/recapitulation')
        .then(r => r.json())
        .then(data => {
            document.getElementById('totalBesoins').textContent = formatNumber(data.totaux.total_besoins) + ' Ar';
            document.getElementById('totalSatisfaits').textContent = formatNumber(data.totaux.total_satisfaits) + ' Ar';
            document.getElementById('totalRestants').textContent = formatNumber(data.totaux.total_restants) + ' Ar';

            let html = '';
            if (data.recap.length === 0) {
                html = '<tr><td colspan="5" class="text-center text-muted py-4">Aucune donnée</td></tr>';
            } else {
                data.recap.forEach(row => {
                    const restant = row.total_besoins - row.total_satisfaits;
                    const pourcent = row.total_besoins > 0 ? Math.round((row.total_satisfaits / row.total_besoins) * 100) : 0;
                    html += `<tr>
                        <td><strong>${row.nom_ville}</strong></td>
                        <td class="text-end">${formatNumber(row.total_besoins)} Ar</td>
                        <td class="text-end text-success">${formatNumber(row.total_satisfaits)} Ar</td>
                        <td class="text-end text-danger">${formatNumber(restant)} Ar</td>
                        <td>
                            <div class="progress">
                                <div class="progress-bar bg-success" style="width:${pourcent}%">${pourcent}%</div>
                            </div>
                        </td>
                    </tr>`;
                });
            }
            document.getElementById('recapTableBody').innerHTML = html;

            btn.disabled = false;
            btn.innerHTML = '<i class="bi bi-arrow-clockwise"></i> Actualiser (Ajax)';
        })
        .catch(err => {
            alert('Erreur lors de l\'actualisation');
            btn.disabled = false;
            btn.innerHTML = '<i class="bi bi-arrow-clockwise"></i> Actualiser (Ajax)';
        });
}
</script>

<?php include __DIR__ . '/layout_footer.php'; ?>
