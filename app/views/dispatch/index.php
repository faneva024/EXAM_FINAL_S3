<?php
/**
 * Page de dispatch / simulation
 * @var array $historique
 */
$pageTitle = 'Dispatch des dons';
include __DIR__ . '/../layout_header.php';
$validated = $_GET['validated'] ?? null;
?>

<div class="page-header animate-in">
    <h2><i class="bi bi-truck"></i> Dispatch des Dons</h2>
    <p>Simuler et valider la distribution des dons aux villes sinistrées</p>
</div>

<?php if ($validated !== null): ?>
    <div class="alert alert-success alert-dismissible fade show animate-in">
        <i class="bi bi-check-circle"></i> Dispatch validé ! <?= (int)$validated ?> attribution(s) enregistrée(s).
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<div class="row g-3 mb-4">
    <div class="col-md-6">
        <form method="POST" action="<?= BASE_URL ?>/dispatch/simuler">
            <div class="stat-card warning animate-in" style="cursor:pointer;" onclick="this.closest('form').submit();">
                <i class="bi bi-eye stat-icon"></i>
                <div class="stat-label">Simulation</div>
                <div class="stat-value" style="font-size:1.1rem;">
                    <i class="bi bi-eye"></i> Simuler le dispatch
                </div>
                <small class="text-muted">Voir les attributions possibles</small>
            </div>
        </form>
    </div>
    <div class="col-md-6">
        <form method="POST" action="<?= BASE_URL ?>/dispatch/valider" 
              onsubmit="return confirm('Confirmer la validation du dispatch ? Cette action est irréversible.')">
            <div class="stat-card success animate-in" style="cursor:pointer;" onclick="this.closest('form').submit();">
                <i class="bi bi-check2-all stat-icon"></i>
                <div class="stat-label">Validation</div>
                <div class="stat-value" style="font-size:1.1rem;">
                    <i class="bi bi-check2-all"></i> Valider le dispatch
                </div>
                <small class="text-muted">Enregistrer les attributions</small>
            </div>
        </form>
    </div>
</div>

<!-- Historique des dispatch validés -->
<div class="data-card animate-in">
    <div class="data-card-header">
        <i class="bi bi-clock-history"></i> Historique des distributions validées
    </div>
    <div class="data-card-body">
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Ville</th>
                        <th>Besoin</th>
                        <th>Catégorie</th>
                        <th>Donateur</th>
                        <th class="text-end">Quantité attribuée</th>
                        <th>Date dispatch</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($historique)): ?>
                        <tr><td colspan="7" class="text-center text-muted py-4">Aucun dispatch validé</td></tr>
                    <?php else: ?>
                        <?php foreach ($historique as $h): ?>
                            <tr>
                                <td><?= $h['id_dispatch'] ?></td>
                                <td><strong><?= htmlspecialchars($h['nom_ville']) ?></strong></td>
                                <td><?= htmlspecialchars($h['nom_besoin']) ?></td>
                                <td>
                                    <?php if (($h['id_categorie'] ?? 0) == 3): ?>
                                        <span class="badge-argent"><?= htmlspecialchars($h['nom_categorie'] ?? 'En argent') ?></span>
                                    <?php elseif (($h['nom_categorie'] ?? '') === 'En nature'): ?>
                                        <span class="badge-nature"><?= htmlspecialchars($h['nom_categorie']) ?></span>
                                    <?php else: ?>
                                        <span class="badge-materiaux"><?= htmlspecialchars($h['nom_categorie'] ?? 'En matériels') ?></span>
                                    <?php endif; ?>
                                </td>
                                <td><?= htmlspecialchars($h['nom_user'] ?? 'N/A') ?></td>
                                <td class="text-end"><?= number_format($h['quantite_attribuee'], 2, ',', ' ') ?></td>
                                <td><?= date('d/m/Y H:i', strtotime($h['date_dispatch'])) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../layout_footer.php'; ?>
