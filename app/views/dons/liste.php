<?php
/**
 * Liste des dons
 * @var array $dons
 * @var float $argentDispo
 */
$pageTitle = 'Liste des dons';
include __DIR__ . '/../layout_header.php';
?>

<div class="page-header animate-in">
    <div class="d-flex justify-content-between align-items-start">
        <div>
            <h2><i class="bi bi-gift"></i> Liste des Dons</h2>
            <p>Tous les dons reçus (nature, matériaux, argent)</p>
        </div>
        <a href="<?= BASE_URL ?>/dons/ajouter" class="btn btn-primary">
            <i class="bi bi-plus-circle"></i> Nouveau don
        </a>
    </div>
</div>

<div class="info-banner animate-in">
    <i class="bi bi-cash-coin"></i>
    <span>Argent disponible (dons en argent - achats) : <strong><?= number_format($argentDispo, 0, ',', ' ') ?> Ar</strong></span>
</div>

<div class="data-card animate-in">
    <div class="data-card-header">
        <i class="bi bi-list-ul"></i> Dons enregistrés (<?= count($dons) ?>)
    </div>
    <div class="data-card-body">
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Donateur</th>
                        <th>Catégorie</th>
                        <th>Don</th>
                        <th class="text-end">Quantité</th>
                        <th class="text-end">Montant (PU × Qté)</th>
                        <th>Date du don</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($dons)): ?>
                        <tr><td colspan="7" class="text-center text-muted py-4">Aucun don enregistré</td></tr>
                    <?php else: ?>
                        <?php foreach ($dons as $d): ?>
                            <tr>
                                <td><?= $d['id_don'] ?></td>
                                <td><strong><?= htmlspecialchars($d['nom_user']) ?></strong></td>
                                <td>
                                    <?php if ($d['id_categorie'] == 3): ?>
                                        <span class="badge-argent">En argent</span>
                                    <?php else: ?>
                                        <span class="badge-<?= $d['id_categorie'] == 1 ? 'nature' : 'materiaux' ?>">
                                            <?= htmlspecialchars($d['nom_categorie']) ?>
                                        </span>
                                    <?php endif; ?>
                                </td>
                                <td><?= htmlspecialchars($d['nom_don']) ?></td>
                                <td class="text-end"><?= $d['quantite'] ? number_format($d['quantite'], 0, ',', ' ') : '-' ?></td>
                                <td class="text-end fw-bold"><?= !empty($d['montant_affiche']) ? number_format($d['montant_affiche'], 0, ',', ' ') . ' Ar' : '-' ?></td>
                                <td><?= date('d/m/Y H:i', strtotime($d['date_don'])) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../layout_footer.php'; ?>
