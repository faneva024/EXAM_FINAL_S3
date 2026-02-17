<?php
/**
 * Liste des achats
 * @var array $achats
 * @var array $villes
 * @var float $argentDispo
 * @var int|null $filtreVille
 */
$pageTitle = 'Liste des achats';
include __DIR__ . '/../layout_header.php';
$success = $_GET['success'] ?? null;
?>

<div class="page-header animate-in">
    <h2><i class="bi bi-cart"></i> Liste des Achats</h2>
    <p>Achats effectués avec les dons en argent</p>
</div>

<?php if ($success): ?>
    <div class="alert alert-success alert-dismissible fade show animate-in">
        <i class="bi bi-check-circle"></i> Achat enregistré avec succès !
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<div class="info-banner animate-in">
    <i class="bi bi-cash-coin"></i>
    <span>Argent disponible : <strong><?= number_format($argentDispo, 0, ',', ' ') ?> Ar</strong></span>
</div>

<!-- Filtre par ville -->
<form method="GET" action="<?= BASE_URL ?>/achats" class="mb-4 animate-in">
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
        <i class="bi bi-receipt"></i> Achats enregistrés
    </div>
    <div class="data-card-body">
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Besoin</th>
                        <th>Ville</th>
                        <th class="text-end">Quantité</th>
                        <th class="text-end">Prix Unit.</th>
                        <th class="text-end">Frais (%)</th>
                        <th class="text-end">Montant Total</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($achats)): ?>
                        <tr><td colspan="8" class="text-center text-muted py-4">Aucun achat enregistré</td></tr>
                    <?php else: ?>
                        <?php $total = 0; ?>
                        <?php foreach ($achats as $a): ?>
                            <?php $total += $a['montant_total']; ?>
                            <tr>
                                <td><?= $a['id_achat'] ?></td>
                                <td><strong><?= htmlspecialchars($a['nom_besoin']) ?></strong></td>
                                <td><?= htmlspecialchars($a['nom_ville']) ?></td>
                                <td class="text-end"><?= number_format($a['quantite'], 0, ',', ' ') ?></td>
                                <td class="text-end"><?= number_format($a['prix_unitaire'], 0, ',', ' ') ?> Ar</td>
                                <td class="text-end"><?= $a['frais_pourcent'] ?>%</td>
                                <td class="text-end fw-bold"><?= number_format($a['montant_total'], 0, ',', ' ') ?> Ar</td>
                                <td><?= date('d/m/Y H:i', strtotime($a['date_achat'])) ?></td>
                            </tr>
                        <?php endforeach; ?>
                        <tr style="background: var(--primary); color: #fff;">
                            <td colspan="6" class="text-end fw-bold">TOTAL :</td>
                            <td class="text-end fw-bold"><?= number_format($total, 0, ',', ' ') ?> Ar</td>
                            <td></td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../layout_footer.php'; ?>
