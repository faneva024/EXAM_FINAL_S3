<?php include __DIR__ . '/../../inc/header.php'; ?>

<!-- Fil d'Ariane -->
<nav aria-label="breadcrumb" class="mb-3">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="/" class="text-decoration-none"><i class="bi bi-speedometer2 me-1"></i>Dashboard</a></li>
        <li class="breadcrumb-item active"><?= htmlspecialchars($ville['nom_ville']) ?></li>
    </ol>
</nav>

<!-- Titre -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="fw-bold text-dark mb-1">
            <i class="bi bi-geo-alt-fill me-2 text-danger"></i><?= htmlspecialchars($ville['nom_ville']) ?>
        </h3>
        <p class="text-muted mb-0">Liste des besoins identifiés pour cette ville sinistrée</p>
    </div>
    <a href="/" class="btn btn-outline-primary">
        <i class="bi bi-arrow-left me-1"></i>Retour
    </a>
</div>

<!-- Résumé rapide -->
<?php
    $totalQte = array_sum(array_column($besoins, 'quantite'));
    $totalMontant = 0;
    foreach ($besoins as $b) {
        $totalMontant += $b['prix_unitaire'] * $b['quantite'];
    }
    $categories = array_unique(array_column($besoins, 'nom_categorie'));
?>
<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="card stat-card p-3">
            <div class="d-flex align-items-center">
                <div class="icon-box bg-danger bg-opacity-10 text-danger me-3">
                    <i class="bi bi-exclamation-triangle-fill"></i>
                </div>
                <div>
                    <div class="stat-value"><?= count($besoins) ?></div>
                    <div class="stat-label">Besoins</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card stat-card p-3">
            <div class="d-flex align-items-center">
                <div class="icon-box bg-primary bg-opacity-10 text-primary me-3">
                    <i class="bi bi-tags-fill"></i>
                </div>
                <div>
                    <div class="stat-value"><?= count($categories) ?></div>
                    <div class="stat-label">Catégories</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card stat-card p-3">
            <div class="d-flex align-items-center">
                <div class="icon-box bg-info bg-opacity-10 text-info me-3">
                    <i class="bi bi-boxes"></i>
                </div>
                <div>
                    <div class="stat-value"><?= number_format($totalQte, 0, ',', ' ') ?></div>
                    <div class="stat-label">Quantité totale</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card stat-card p-3">
            <div class="d-flex align-items-center">
                <div class="icon-box bg-warning bg-opacity-10 text-warning me-3">
                    <i class="bi bi-cash-stack"></i>
                </div>
                <div>
                    <div class="stat-value"><?= number_format($totalMontant, 0, ',', ' ') ?></div>
                    <div class="stat-label">Montant total (Ar)</div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Tableau détaillé des besoins -->
<div class="card table-card">
    <div class="card-header d-flex justify-content-between align-items-center py-3">
        <h5 class="mb-0">
            <i class="bi bi-list-check me-2 text-primary"></i>Détail des besoins
        </h5>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0 align-middle">
                <thead class="table-light">
                    <tr>
                        <th class="ps-4">#</th>
                        <th>Besoin</th>
                        <th>Catégorie</th>
                        <th class="text-end">Prix unitaire (Ar)</th>
                        <th class="text-center">Quantité</th>
                        <th class="text-end">Montant total (Ar)</th>
                        <th>Date saisie</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($besoins as $i => $b): 
                        $montant = $b['prix_unitaire'] * $b['quantite'];
                    ?>
                    <tr>
                        <td class="ps-4 text-muted"><?= $i + 1 ?></td>
                        <td class="fw-semibold"><?= htmlspecialchars($b['nom_besoin']) ?></td>
                        <td>
                            <span class="badge bg-primary bg-opacity-10 text-primary badge-ville">
                                <?= htmlspecialchars($b['nom_categorie']) ?>
                            </span>
                        </td>
                        <td class="text-end"><?= number_format($b['prix_unitaire'], 0, ',', ' ') ?></td>
                        <td class="text-center fw-semibold"><?= number_format($b['quantite'], 0, ',', ' ') ?></td>
                        <td class="text-end fw-bold text-danger"><?= number_format($montant, 0, ',', ' ') ?></td>
                        <td class="text-muted">
                            <small><?= date('d/m/Y', strtotime($b['date_saisie'])) ?></small>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
                <tfoot class="table-light">
                    <tr class="fw-bold">
                        <td colspan="4" class="ps-4 text-end">TOTAL</td>
                        <td class="text-center"><?= number_format($totalQte) ?></td>
                        <td class="text-end text-danger"><?= number_format($totalMontant) ?></td>
                        <td></td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../../inc/footer.php'; ?>
