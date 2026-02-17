<?php
/**
 * Résultat de la simulation de dispatch
 * @var array $resultats
 */
$pageTitle = 'Simulation du dispatch';
include __DIR__ . '/../layout_header.php';
?>

<div class="page-header animate-in">
    <h2><i class="bi bi-eye"></i> Résultat de la Simulation</h2>
    <p>Attributions possibles basées sur les dons disponibles (nature, matériels et argent)</p>
</div>

<?php if (empty($resultats)): ?>
    <div class="alert alert-info animate-in">
        <i class="bi bi-info-circle"></i> Aucune attribution possible. Il n'y a pas de don correspondant aux besoins restants.
    </div>
<?php else: ?>
    <div class="alert alert-warning animate-in">
        <i class="bi bi-exclamation-triangle"></i> Ceci est une <strong>simulation</strong>. 
        Les attributions ci-dessous ne sont pas encore enregistrées. Cliquez sur "Valider" pour confirmer.
    </div>

    <div class="data-card animate-in">
        <div class="data-card-header" style="background: linear-gradient(135deg, #d69e2e 0%, #ecc94b 100%);">
            <i class="bi bi-lightning"></i> Attributions simulées
        </div>
        <div class="data-card-body">
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Ville</th>
                            <th>Besoin</th>
                            <th>Catégorie</th>
                            <th>Donateur</th>
                            <th class="text-end">Quantité attribuée</th>
                            <th class="text-end">Valeur</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $totalValeur = 0; ?>
                        <?php foreach ($resultats as $r): ?>
                            <?php $valeur = $r['quantite_attribuee'] * $r['prix_unitaire']; $totalValeur += $valeur; ?>
                            <tr>
                                <td><strong><?= htmlspecialchars($r['nom_ville']) ?></strong></td>
                                <td><?= htmlspecialchars($r['nom_besoin']) ?></td>
                                <td>
                                    <?php $idCat = $r['id_categorie'] ?? 0; $cat = $r['nom_categorie'] ?? ''; ?>
                                    <?php if ($idCat == 3): ?>
                                        <span class="badge-argent"><?= htmlspecialchars($cat) ?></span>
                                    <?php elseif ($cat === 'En nature'): ?>
                                        <span class="badge-nature"><?= htmlspecialchars($cat) ?></span>
                                    <?php else: ?>
                                        <span class="badge-materiaux"><?= htmlspecialchars($cat) ?></span>
                                    <?php endif; ?>
                                </td>
                                <td><?= htmlspecialchars($r['nom_user'] ?? 'N/A') ?></td>
                                <td class="text-end"><?= number_format($r['quantite_attribuee'], 2, ',', ' ') ?></td>
                                <td class="text-end"><?= number_format($valeur, 0, ',', ' ') ?> Ar</td>
                            </tr>
                        <?php endforeach; ?>
                        <tr style="background: var(--primary); color: #fff;">
                            <td colspan="4" class="text-end fw-bold">TOTAL :</td>
                            <td class="text-end fw-bold"><?= count($resultats) ?> attribution(s)</td>
                            <td class="text-end fw-bold"><?= number_format($totalValeur, 0, ',', ' ') ?> Ar</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="mt-4">
        <form method="POST" action="<?= BASE_URL ?>/dispatch/valider" class="d-inline"
              onsubmit="return confirm('Confirmer la validation du dispatch ?')">
            <button type="submit" class="btn btn-success btn-lg">
                <i class="bi bi-check2-all"></i> Valider le dispatch
            </button>
        </form>
    </div>
<?php endif; ?>

<a href="<?= BASE_URL ?>/dispatch" class="btn btn-outline-secondary mt-3">
    <i class="bi bi-arrow-left"></i> Retour
</a>

<?php include __DIR__ . '/../layout_footer.php'; ?>
