<?php include __DIR__ . '/../../inc/header.php'; ?>

<!-- Titre -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="fw-bold text-dark mb-1">
            <i class="bi bi-speedometer2 me-2 text-primary"></i>Dashboard BNGRC
        </h3>
        <p class="text-muted mb-0">Suivi des besoins et dons - Villes touchées par les cyclones (Côte Est)</p>
    </div>
    <span class="badge bg-primary bg-opacity-10 text-primary fs-6">
        <i class="bi bi-calendar3 me-1"></i><?= date('d/m/Y') ?>
    </span>
</div>

<!-- Cartes statistiques -->
<div class="row g-3 mb-4">
    <div class="col-xl-2 col-md-4 col-sm-6">
        <div class="card stat-card p-3">
            <div class="d-flex align-items-center">
                <div class="icon-box bg-primary bg-opacity-10 text-primary me-3">
                    <i class="bi bi-geo-alt-fill"></i>
                </div>
                <div>
                    <div class="stat-value"><?= number_format($stats['total_villes']) ?></div>
                    <div class="stat-label">Villes</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-2 col-md-4 col-sm-6">
        <a href="/besoins" class="text-decoration-none">
        <div class="card stat-card p-3">
            <div class="d-flex align-items-center">
                <div class="icon-box bg-danger bg-opacity-10 text-danger me-3">
                    <i class="bi bi-exclamation-triangle-fill"></i>
                </div>
                <div>
                    <div class="stat-value"><?= number_format($stats['total_besoins']) ?></div>
                    <div class="stat-label">Besoins</div>
                </div>
            </div>
        </div>
        </a>
    </div>
    <div class="col-xl-2 col-md-4 col-sm-6">
        <div class="card stat-card p-3">
            <div class="d-flex align-items-center">
                <div class="icon-box bg-warning bg-opacity-10 text-warning me-3">
                    <i class="bi bi-cash-stack"></i>
                </div>
                <div>
                    <div class="stat-value"><?= number_format($stats['montant_total_besoins'], 0, ',', ' ') ?></div>
                    <div class="stat-label">Ar Besoins</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-2 col-md-4 col-sm-6">
        <div class="card stat-card p-3">
            <div class="d-flex align-items-center">
                <div class="icon-box bg-success bg-opacity-10 text-success me-3">
                    <i class="bi bi-heart-fill"></i>
                </div>
                <div>
                    <div class="stat-value"><?= number_format($stats['total_dons']) ?></div>
                    <div class="stat-label">Dons</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-2 col-md-4 col-sm-6">
        <div class="card stat-card p-3">
            <div class="d-flex align-items-center">
                <div class="icon-box bg-info bg-opacity-10 text-info me-3">
                    <i class="bi bi-cash-coin"></i>
                </div>
                <div>
                    <div class="stat-value"><?= number_format($stats['montant_total_dons'], 0, ',', ' ') ?></div>
                    <div class="stat-label">Ar Dons</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-2 col-md-4 col-sm-6">
        <div class="card stat-card p-3">
            <div class="d-flex align-items-center">
                <div class="icon-box bg-secondary bg-opacity-10 text-secondary me-3">
                    <i class="bi bi-truck"></i>
                </div>
                <div>
                    <div class="stat-value"><?= number_format($stats['total_dispatches']) ?></div>
                    <div class="stat-label">Dispatches</div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Tableau des besoins par ville -->
<div class="row g-4">
    <div class="col-lg-8">
        <div class="card table-card">
            <div class="card-header d-flex justify-content-between align-items-center py-3">
                <h5 class="mb-0"><i class="bi bi-building me-2 text-primary"></i>Besoins par ville</h5>
                <span class="badge bg-primary"><?= count($resumVilles) ?> villes</span>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0 align-middle">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-4">Ville</th>
                                <th class="text-center">Nb Besoins</th>
                                <th class="text-center">Quantité totale</th>
                                <th class="text-end">Montant estimé (Ar)</th>
                                <th class="text-center">Couverture</th>
                                <th class="text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $maxMontant = max(array_column($resumVilles, 'montant_total') ?: [1]);
                            foreach ($resumVilles as $ville): 
                                $pct = $maxMontant > 0 ? ($ville['montant_total'] / $maxMontant) * 100 : 0;
                                $colorClass = $pct > 70 ? 'bg-danger' : ($pct > 40 ? 'bg-warning' : 'bg-success');
                            ?>
                            <tr>
                                <td class="ps-4">
                                    <div class="d-flex align-items-center">
                                        <div class="icon-box bg-primary bg-opacity-10 text-primary me-2" style="width:35px;height:35px;border-radius:8px;display:flex;align-items:center;justify-content:center;">
                                            <i class="bi bi-geo-alt" style="font-size:0.9rem;"></i>
                                        </div>
                                        <strong><?= htmlspecialchars($ville['nom_ville']) ?></strong>
                                    </div>
                                </td>
                                <td class="text-center">
                                    <span class="badge bg-danger bg-opacity-10 text-danger"><?= $ville['nb_besoins'] ?? 0 ?></span>
                                </td>
                                <td class="text-center"><?= number_format($ville['total_quantite'] ?? 0, 0, ',', ' ') ?></td>
                                <td class="text-end fw-semibold"><?= number_format($ville['montant_total'] ?? 0, 0, ',', ' ') ?></td>
                                <td style="width: 120px;">
                                    <div class="progress">
                                        <div class="progress-bar <?= $colorClass ?>" style="width: <?= round($pct) ?>%"></div>
                                    </div>
                                </td>
                                <td class="text-center">
                                    <?php if (($ville['nb_besoins'] ?? 0) > 0): ?>
                                        <a href="/ville/<?= $ville['id_ville'] ?>" class="btn btn-outline-primary btn-sm btn-detail">
                                            <i class="bi bi-eye me-1"></i>Détail
                                        </a>
                                    <?php else: ?>
                                        <span class="text-muted">-</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Besoins par catégorie -->
    <div class="col-lg-4">
        <div class="card table-card mb-4">
            <div class="card-header py-3">
                <h5 class="mb-0"><i class="bi bi-tags me-2 text-warning"></i>Besoins par catégorie</h5>
            </div>
            <div class="card-body">
                <?php 
                $maxCat = max(array_column($resumCategories, 'montant_total') ?: [1]);
                foreach ($resumCategories as $cat): 
                    $pctCat = $maxCat > 0 ? ($cat['montant_total'] / $maxCat) * 100 : 0;
                    $colors = ['bg-primary', 'bg-success', 'bg-warning', 'bg-danger', 'bg-info', 'bg-secondary', 'bg-dark'];
                    $color = $colors[$cat['id_categorie'] % count($colors)];
                ?>
                <div class="mb-3">
                    <div class="d-flex justify-content-between mb-1">
                        <small class="fw-semibold"><?= htmlspecialchars($cat['nom_categorie']) ?></small>
                        <small class="text-muted"><?= number_format($cat['montant_total'] ?? 0, 0, ',', ' ') ?> Ar</small>
                    </div>
                    <div class="progress">
                        <div class="progress-bar <?= $color ?>" style="width: <?= round($pctCat) ?>%"></div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- Dons par catégorie -->
        <div class="card table-card">
            <div class="card-header py-3">
                <h5 class="mb-0"><i class="bi bi-heart me-2 text-success"></i>Dons par catégorie</h5>
            </div>
            <div class="card-body">
                <?php 
                $maxDon = max(array_column($donsCategories, 'montant_total') ?: [1]);
                foreach ($donsCategories as $don): 
                    if (($don['montant_total'] ?? 0) == 0) continue;
                    $pctDon = $maxDon > 0 ? ($don['montant_total'] / $maxDon) * 100 : 0;
                ?>
                <div class="mb-3">
                    <div class="d-flex justify-content-between mb-1">
                        <small class="fw-semibold"><?= htmlspecialchars($don['nom_categorie']) ?></small>
                        <small class="text-muted"><?= number_format($don['montant_total'] ?? 0, 0, ',', ' ') ?> Ar</small>
                    </div>
                    <div class="progress">
                        <div class="progress-bar bg-success" style="width: <?= round($pctDon) ?>%"></div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../../inc/footer.php'; ?>
