<?php
/**
 * Besoins restants + formulaire d'achat
 * @var array $besoins
 * @var array $villes
 * @var float $argentDispo
 * @var float $frais
 * @var int|null $filtreVille
 */
$pageTitle = 'Besoins restants';
include __DIR__ . '/../layout_header.php';
$error = $_GET['error'] ?? null;
?>

<div class="page-header animate-in">
    <h2><i class="bi bi-exclamation-triangle"></i> Besoins Restants</h2>
    <p>Besoins non encore satisfaits avec possibilité d'achat</p>
</div>

<?php if ($error): ?>
    <div class="alert alert-danger alert-dismissible fade show animate-in">
        <i class="bi bi-exclamation-triangle"></i> <?= htmlspecialchars($error) ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<div class="info-banner animate-in">
    <i class="bi bi-cash-coin"></i>
    <span>Argent disponible pour achats : <strong><?= number_format($argentDispo, 0, ',', ' ') ?> Ar</strong> | Frais d'achat : <strong><?= $frais ?>%</strong></span>
</div>

<!-- Filtre par ville -->
<form method="GET" action="<?= BASE_URL ?>/besoins/restants" class="mb-4 animate-in">
    <div class="row g-2 align-items-end">
        <div class="col-md-4">
            <label class="form-label">Filtrer par ville</label>
            <select name="idVille" class="form-select">
                <option value="">-- Toutes les villes --</option>
                <?php foreach ($villes as $v): ?>
                    <option value="<?= $v['id_ville'] ?>" <?= ($filtreVille == $v['id_ville']) ? 'selected' : '' ?>>
                        <?= htmlspecialchars($v['nom_ville']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="col-md-2">
            <button type="submit" class="btn btn-outline-primary w-100">
                <i class="bi bi-funnel"></i> Filtrer
            </button>
        </div>
    </div>
</form>

<div class="data-card animate-in">
    <div class="data-card-header">
        <i class="bi bi-list-check"></i> Besoins non satisfaits
    </div>
    <div class="data-card-body">
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>Ville</th>
                        <th>Besoin</th>
                        <th>Catégorie</th>
                        <th class="text-end">Demandé</th>
                        <th class="text-end">Attribué</th>
                        <th class="text-end">Restant</th>
                        <th class="text-end">Montant Restant</th>
                        <th class="text-center">Achat</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($besoins)): ?>
                        <tr><td colspan="8" class="text-center text-muted py-4">Aucun besoin restant</td></tr>
                    <?php else: ?>
                        <?php foreach ($besoins as $b): ?>
                            <tr>
                                <td><strong><?= htmlspecialchars($b['nom_ville']) ?></strong></td>
                                <td><?= htmlspecialchars($b['nom_besoin']) ?></td>
                                <td>
                                    <?php if ($b['id_categorie'] == 3): ?>
                                        <span class="badge-argent"><?= $b['nom_categorie'] ?></span>
                                    <?php elseif ($b['nom_categorie'] === 'En nature'): ?>
                                        <span class="badge-nature"><?= $b['nom_categorie'] ?></span>
                                    <?php else: ?>
                                        <span class="badge-materiaux"><?= $b['nom_categorie'] ?></span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-end"><?= number_format($b['quantite_demandee'], 0, ',', ' ') ?></td>
                                <td class="text-end text-success"><?= number_format($b['quantite_attribuee'], 0, ',', ' ') ?></td>
                                <td class="text-end text-danger fw-bold"><?= number_format($b['quantite_restante'], 0, ',', ' ') ?></td>
                                <td class="text-end"><?= number_format($b['montant_restant'], 0, ',', ' ') ?> Ar</td>
                                <td class="text-center">
                                    <?php if ($b['id_categorie'] != 3): ?>
                                        <form method="POST" action="<?= BASE_URL ?>/achats/ajouter" class="d-inline">
                                            <input type="hidden" name="id_besoin" value="<?= $b['id_besoin'] ?>">
                                            <div class="input-group input-group-sm" style="width: 160px; display: inline-flex;">
                                                <input type="number" name="quantite" class="form-control" 
                                                       min="1" max="<?= $b['quantite_restante'] ?>" 
                                                       value="<?= $b['quantite_restante'] ?>" required>
                                                <button type="submit" class="btn btn-warning btn-sm" title="Acheter avec l'argent disponible">
                                                    <i class="bi bi-cart-plus"></i>
                                                </button>
                                            </div>
                                        </form>
                                    <?php else: ?>
                                        <span class="text-muted" title="Les besoins en argent sont satisfaits par le dispatch">—</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../layout_footer.php'; ?>
