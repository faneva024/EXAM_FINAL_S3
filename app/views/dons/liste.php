<?php include __DIR__ . '/../../inc/header.php'; ?>

<div class="row">
    <div class="col-12">
        <div class="d-flex align-items-center mb-4">
            <h2 class="mb-0"><i class="bi bi-heart-fill text-danger me-2"></i>Liste des dons reçus</h2>
            <a href="/dons/nouveau" class="btn btn-success btn-sm ms-auto">
                <i class="bi bi-plus-circle me-1"></i>Faire un don
            </a>
        </div>

        <!-- Stats résumé -->
        <div class="row mb-4">
            <div class="col-md-4">
                <div class="card stat-card p-3">
                    <div class="d-flex align-items-center">
                        <div class="icon-box bg-success bg-opacity-10 text-success me-3">
                            <i class="bi bi-heart-fill"></i>
                        </div>
                        <div>
                            <div class="stat-value"><?= number_format($stats['nb_dons'] ?? 0) ?></div>
                            <div class="stat-label">Dons reçus</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card stat-card p-3">
                    <div class="d-flex align-items-center">
                        <div class="icon-box bg-info bg-opacity-10 text-info me-3">
                            <i class="bi bi-box-seam"></i>
                        </div>
                        <div>
                            <div class="stat-value"><?= number_format($stats['total_quantite'] ?? 0) ?></div>
                            <div class="stat-label">Quantité totale</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card stat-card p-3">
                    <div class="d-flex align-items-center">
                        <div class="icon-box bg-warning bg-opacity-10 text-warning me-3">
                            <i class="bi bi-cash-stack"></i>
                        </div>
                        <div>
                            <div class="stat-value"><?= number_format($stats['montant_total'] ?? 0, 0, ',', ' ') ?></div>
                            <div class="stat-label">Montant total (Ar)</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Liste anonyme des dons -->
        <div class="card table-card">
            <div class="card-header py-3">
                <h5 class="mb-0">
                    <i class="bi bi-list-stars me-2"></i>Dons reçus
                    <span class="badge bg-success ms-2"><?= count($dons) ?> don(s)</span>
                    <small class="text-muted ms-2">- Donateurs anonymes</small>
                </h5>
            </div>
            <div class="card-body">
                <?php if (empty($dons)): ?>
                    <div class="text-center py-5">
                        <i class="bi bi-inbox display-1 text-muted"></i>
                        <p class="lead text-muted mt-3">Aucun don enregistré pour le moment</p>
                        <a href="/dons/nouveau" class="btn btn-success">
                            <i class="bi bi-heart-fill me-1"></i>Soyez le premier à donner
                        </a>
                    </div>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>#</th>
                                    <th>Donateur</th>
                                    <th>Catégorie</th>
                                    <th>Description du don</th>
                                    <th class="text-center">Quantité</th>
                                    <th class="text-end">Montant</th>
                                    <th>Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($dons as $i => $don): ?>
                                    <tr>
                                        <td><?= $i + 1 ?></td>
                                        <td>
                                            <i class="bi bi-person-fill text-muted me-1"></i>
                                            <em class="text-muted">Anonyme</em>
                                        </td>
                                        <td>
                                            <span class="badge bg-primary badge-ville"><?= htmlspecialchars($don['nom_categorie']) ?></span>
                                        </td>
                                        <td><?= htmlspecialchars($don['nom_don']) ?></td>
                                        <td class="text-center"><?= number_format($don['quantite']) ?></td>
                                        <td class="text-end">
                                            <?= $don['montant'] ? number_format($don['montant'], 0, ',', ' ') . ' Ar' : '-' ?>
                                        </td>
                                        <td>
                                            <small class="text-muted"><?= date('d/m/Y', strtotime($don['date_don'])) ?></small>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        </div>

    </div>
</div>

<?php include __DIR__ . '/../../inc/footer.php'; ?>
