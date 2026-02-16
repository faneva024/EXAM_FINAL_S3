<?php include __DIR__ . '/../../inc/header.php'; ?>

<!-- Titre -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="fw-bold text-dark mb-1">
            <i class="bi bi-exclamation-triangle-fill me-2 text-danger"></i>Tous les Besoins
        </h3>
        <p class="text-muted mb-0">Liste complète des besoins identifiés dans les villes sinistrées</p>
    </div>
    <span class="badge bg-danger bg-opacity-10 text-danger fs-6">
        <?= count($besoins) ?> besoin<?= count($besoins) > 1 ? 's' : '' ?>
    </span>
</div>

<!-- Filtre par ville -->
<div class="card table-card mb-4">
    <div class="card-body py-3">
        <form method="GET" action="/besoins" class="row g-2 align-items-center">
            <div class="col-auto">
                <label class="fw-semibold"><i class="bi bi-funnel me-1"></i>Filtrer par ville :</label>
            </div>
            <div class="col-md-4">
                <select name="ville" class="form-select form-select-sm">
                    <option value="">-- Toutes les villes --</option>
                    <?php foreach ($villes as $v): ?>
                        <option value="<?= $v['id_ville'] ?>" <?= ($filtreVille == $v['id_ville']) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($v['nom_ville']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-auto">
                <button type="submit" class="btn btn-primary btn-sm"><i class="bi bi-search me-1"></i>Filtrer</button>
                <?php if ($filtreVille): ?>
                    <a href="/besoins" class="btn btn-outline-secondary btn-sm ms-1"><i class="bi bi-x-lg me-1"></i>Réinitialiser</a>
                <?php endif; ?>
            </div>
        </form>
    </div>
</div>

<!-- Tableau des besoins -->
<div class="card table-card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0 align-middle">
                <thead class="table-light">
                    <tr>
                        <th class="ps-4">#</th>
                        <th>Besoin</th>
                        <th>Ville</th>
                        <th>Catégorie</th>
                        <th class="text-end">Prix unitaire (Ar)</th>
                        <th class="text-center">Quantité</th>
                        <th class="text-end">Montant total (Ar)</th>
                        <th>Date saisie</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($besoins)): ?>
                        <tr>
                            <td colspan="8" class="text-center text-muted py-4">
                                <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                                Aucun besoin trouvé
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($besoins as $i => $b): 
                            $montant = $b['prix_unitaire'] * $b['quantite'];
                        ?>
                        <tr>
                            <td class="ps-4 text-muted"><?= $i + 1 ?></td>
                            <td class="fw-semibold"><?= htmlspecialchars($b['nom_besoin']) ?></td>
                            <td>
                                <a href="/ville/<?= $b['id_ville'] ?>" class="text-decoration-none">
                                    <span class="badge bg-primary bg-opacity-10 text-primary badge-ville">
                                        <i class="bi bi-geo-alt me-1"></i><?= htmlspecialchars($b['nom_ville']) ?>
                                    </span>
                                </a>
                            </td>
                            <td>
                                <span class="badge bg-warning bg-opacity-10 text-dark badge-ville">
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
                    <?php endif; ?>
                </tbody>
                <?php if (!empty($besoins)): ?>
                <tfoot class="table-light">
                    <?php
                        $totalQte = array_sum(array_column($besoins, 'quantite'));
                        $totalMontant = 0;
                        foreach ($besoins as $b) {
                            $totalMontant += $b['prix_unitaire'] * $b['quantite'];
                        }
                    ?>
                    <tr class="fw-bold">
                        <td colspan="5" class="ps-4 text-end">TOTAL</td>
                        <td class="text-center"><?= number_format($totalQte, 0, ',', ' ') ?></td>
                        <td class="text-end text-danger"><?= number_format($totalMontant, 0, ',', ' ') ?></td>
                        <td></td>
                    </tr>
                </tfoot>
                <?php endif; ?>
            </table>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../../inc/footer.php'; ?>
