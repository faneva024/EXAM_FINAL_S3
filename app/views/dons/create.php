<?php include __DIR__ . '/../../inc/header.php'; ?>

<div class="row justify-content-center">
    <div class="col-md-8">

        <div class="d-flex align-items-center mb-4">
            <h2 class="mb-0"><i class="bi bi-heart-fill text-danger me-2"></i>Faire un don</h2>
            <a href="/dons/mes-dons" class="btn btn-outline-primary btn-sm ms-auto">
                <i class="bi bi-list-check me-1"></i>Mes dons
            </a>
        </div>

        <?php if (!empty($success)): ?>
            <div class="alert alert-success d-flex align-items-center" role="alert">
                <i class="bi bi-check-circle-fill me-2"></i>
                <?= $success ?>
            </div>
        <?php endif; ?>

        <?php if (!empty($error)): ?>
            <div class="alert alert-danger d-flex align-items-center" role="alert">
                <i class="bi bi-exclamation-triangle-fill me-2"></i>
                <?= $error ?>
            </div>
        <?php endif; ?>

        <div class="card table-card">
            <div class="card-header py-3">
                <h5 class="mb-0"><i class="bi bi-gift me-2"></i>Formulaire de don</h5>
            </div>
            <div class="card-body p-4">
                <div class="alert alert-info mb-4">
                    <i class="bi bi-info-circle me-2"></i>
                    Connecté en tant que <strong><?= htmlspecialchars($_SESSION['user']['nom']) ?></strong>
                    (<?= htmlspecialchars($_SESSION['user']['email']) ?>)
                </div>

                <form method="POST" action="/dons/nouveau">
                    <div class="mb-3">
                        <label for="id_categorie" class="form-label">
                            <i class="bi bi-tag me-1"></i>Catégorie du don <span class="text-danger">*</span>
                        </label>
                        <select class="form-select" id="id_categorie" name="id_categorie" required>
                            <option value="">-- Choisir une catégorie --</option>
                            <?php foreach ($categories as $cat): ?>
                                <option value="<?= $cat['id_categorie'] ?>"
                                    <?= (isset($_POST['id_categorie']) && $_POST['id_categorie'] == $cat['id_categorie']) ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($cat['nom_categorie']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="nom_don" class="form-label">
                            <i class="bi bi-box me-1"></i>Description du don <span class="text-danger">*</span>
                        </label>
                        <input type="text" class="form-control" id="nom_don" name="nom_don"
                               placeholder="Ex: Riz (sac 50kg), Bâches plastiques, etc."
                               required value="<?= htmlspecialchars($_POST['nom_don'] ?? '') ?>">
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="quantite" class="form-label">
                                <i class="bi bi-123 me-1"></i>Quantité <span class="text-danger">*</span>
                            </label>
                            <input type="number" class="form-control" id="quantite" name="quantite"
                                   min="1" required placeholder="Ex: 10"
                                   value="<?= htmlspecialchars($_POST['quantite'] ?? '') ?>">
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="montant" class="form-label">
                                <i class="bi bi-cash me-1"></i>Montant total (Ar) <small class="text-muted">(optionnel)</small>
                            </label>
                            <input type="number" class="form-control" id="montant" name="montant"
                                   min="0" step="0.01" placeholder="Ex: 500000"
                                   value="<?= htmlspecialchars($_POST['montant'] ?? '') ?>">
                        </div>
                    </div>

                    <hr>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-success btn-lg">
                            <i class="bi bi-heart-fill me-2"></i>Envoyer mon don
                        </button>
                        <a href="/" class="btn btn-outline-secondary btn-lg">
                            <i class="bi bi-arrow-left me-1"></i>Annuler
                        </a>
                    </div>
                </form>
            </div>
        </div>

        <!-- Résumé des besoins par catégorie -->
        <div class="card table-card mt-4">
            <div class="card-header py-3">
                <h5 class="mb-0"><i class="bi bi-bar-chart me-2"></i>Besoins actuels par catégorie</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Catégorie</th>
                                <th class="text-center">Nb besoins</th>
                                <th class="text-end">Quantité totale</th>
                                <th class="text-end">Montant total</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($categories as $cat): ?>
                                <tr>
                                    <td>
                                        <span class="badge bg-primary badge-ville"><?= htmlspecialchars($cat['nom_categorie']) ?></span>
                                    </td>
                                    <td class="text-center"><?= number_format($cat['nb_besoins'] ?? 0) ?></td>
                                    <td class="text-end"><?= number_format($cat['total_quantite'] ?? 0) ?></td>
                                    <td class="text-end"><?= number_format($cat['montant_total'] ?? 0, 0, ',', ' ') ?> Ar</td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>
</div>

<?php include __DIR__ . '/../../inc/footer.php'; ?>
