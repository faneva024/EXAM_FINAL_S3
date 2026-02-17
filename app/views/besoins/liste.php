<?php
/**
 * Liste de tous les besoins
 * @var array $besoins
 */
$pageTitle = 'Liste des besoins';
include __DIR__ . '/../layout_header.php';
?>

<div class="page-header animate-in">
    <div class="d-flex justify-content-between align-items-start">
        <div>
            <h2><i class="bi bi-clipboard-check"></i> Liste des Besoins</h2>
            <p>Tous les besoins enregistrés par ville et article</p>
        </div>
        <a href="<?= BASE_URL ?>/besoins/ajouter" class="btn btn-primary">
            <i class="bi bi-plus-circle"></i> Nouveau besoin
        </a>
    </div>
</div>

<div class="data-card animate-in">
    <div class="data-card-header">
        <i class="bi bi-list-ul"></i> Besoins enregistrés (<?= count($besoins) ?>)
    </div>
    <div class="data-card-body">
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Ville</th>
                        <th>Catégorie</th>
                        <th>Besoin</th>
                        <th class="text-end">Quantité</th>
                        <th class="text-end">Prix Unit.</th>
                        <th class="text-end">Montant</th>
                        <th>Date saisie</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($besoins)): ?>
                        <tr><td colspan="8" class="text-center text-muted py-4">Aucun besoin enregistré</td></tr>
                    <?php else: ?>
                        <?php foreach ($besoins as $b): ?>
                            <tr>
                                <td><?= $b['id_besoin'] ?></td>
                                <td><strong><?= htmlspecialchars($b['nom_ville']) ?></strong></td>
                                <td>
                                    <?php if ($b['id_categorie'] == 3): ?>
                                        <span class="badge-argent"><?= htmlspecialchars($b['nom_categorie']) ?></span>
                                    <?php elseif ($b['nom_categorie'] === 'En nature'): ?>
                                        <span class="badge-nature"><?= htmlspecialchars($b['nom_categorie']) ?></span>
                                    <?php else: ?>
                                        <span class="badge-materiaux"><?= htmlspecialchars($b['nom_categorie']) ?></span>
                                    <?php endif; ?>
                                </td>
                                <td><?= htmlspecialchars($b['nom_besoin']) ?></td>
                                <td class="text-end"><?= number_format($b['quantite'], 0, ',', ' ') ?></td>
                                <td class="text-end"><?= number_format($b['prix_unitaire'], 0, ',', ' ') ?> Ar</td>
                                <td class="text-end fw-bold"><?= number_format($b['montant_total'], 0, ',', ' ') ?> Ar</td>
                                <td><?= date('d/m/Y H:i', strtotime($b['date_saisie'])) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../layout_footer.php'; ?>
