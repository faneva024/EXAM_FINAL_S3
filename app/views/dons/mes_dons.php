<?php include __DIR__ . '/../../inc/header.php'; ?>

<div class="row justify-content-center">
    <div class="col-md-10">

        <div class="d-flex align-items-center mb-4">
            <h2 class="mb-0"><i class="bi bi-list-check me-2"></i>Mes dons</h2>
            <a href="/dons/nouveau" class="btn btn-success btn-sm ms-auto">
                <i class="bi bi-plus-circle me-1"></i>Faire un nouveau don
            </a>
        </div>

        <div class="card table-card">
            <div class="card-header py-3">
                <h5 class="mb-0">
                    <i class="bi bi-person-circle me-2"></i>
                    Dons de <?= htmlspecialchars($_SESSION['user']['nom']) ?>
                    <span class="badge bg-primary ms-2"><?= count($dons) ?> don(s)</span>
                </h5>
            </div>
            <div class="card-body">
                <?php if (empty($dons)): ?>
                    <div class="text-center py-5">
                        <i class="bi bi-inbox display-1 text-muted"></i>
                        <p class="lead text-muted mt-3">Vous n'avez pas encore fait de don</p>
                        <a href="/dons/nouveau" class="btn btn-success">
                            <i class="bi bi-heart-fill me-1"></i>Faire un premier don
                        </a>
                    </div>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>#</th>
                                    <th>Catégorie</th>
                                    <th>Description</th>
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
                                            <span class="badge bg-primary badge-ville"><?= htmlspecialchars($don['nom_categorie']) ?></span>
                                        </td>
                                        <td><?= htmlspecialchars($don['nom_don']) ?></td>
                                        <td class="text-center"><?= number_format($don['quantite']) ?></td>
                                        <td class="text-end">
                                            <?= $don['montant'] ? number_format($don['montant'], 0, ',', ' ') . ' Ar' : '-' ?>
                                        </td>
                                        <td>
                                            <small class="text-muted"><?= date('d/m/Y H:i', strtotime($don['date_don'])) ?></small>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <div class="mt-3">
            <a href="/" class="text-muted text-decoration-none">
                <i class="bi bi-arrow-left me-1"></i>Retour au Dashboard
            </a>
        </div>

    </div>
</div>

<?php include __DIR__ . '/../../inc/footer.php'; ?>
