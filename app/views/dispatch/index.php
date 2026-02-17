<?php
/**
 * Page de dispatch / simulation
 * @var array $historique
 */
$pageTitle = 'Dispatch des dons';
include __DIR__ . '/../layout_header.php';
$validated = $_GET['validated'] ?? null;
$reset = $_GET['reset'] ?? null;
$error = $_GET['error'] ?? null;
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

<?php if ($reset): ?>
    <div class="alert alert-info alert-dismissible fade show animate-in">
        <i class="bi bi-arrow-counterclockwise"></i> Toutes les données ont été réinitialisées avec succès.
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<?php if ($error === 'reset'): ?>
    <div class="alert alert-danger alert-dismissible fade show animate-in">
        <i class="bi bi-exclamation-triangle"></i> Erreur lors de la réinitialisation.
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<!-- Section Simulation avec 3 stratégies -->
<div class="data-card animate-in mb-4">
    <div class="data-card-header" style="background: linear-gradient(135deg, #d69e2e 0%, #ecc94b 100%);">
        <i class="bi bi-eye"></i> Simulation du Dispatch
    </div>
    <div class="data-card-body with-padding">
        <p class="text-muted mb-3">Choisissez une stratégie de distribution pour voir les attributions possibles :</p>
        
        <div class="row g-3">
            <!-- Par ordre de date -->
            <div class="col-md-4">
                <form method="POST" action="<?= BASE_URL ?>/dispatch/simuler">
                    <input type="hidden" name="strategy" value="date">
                    <div class="card h-100 border-primary" style="cursor:pointer;" onclick="this.closest('form').submit();">
                        <div class="card-body text-center">
                            <i class="bi bi-calendar-date text-primary" style="font-size: 2.5rem;"></i>
                            <h5 class="card-title mt-2">Par Date</h5>
                            <p class="card-text small text-muted">
                                Le plus <strong>récent</strong> bénéficie en premier.<br>
                                Priorité aux derniers besoins enregistrés.
                            </p>
                            <button type="submit" class="btn btn-outline-primary btn-sm">
                                <i class="bi bi-eye"></i> Simuler
                            </button>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Par ordre d'infériorité -->
            <div class="col-md-4">
                <form method="POST" action="<?= BASE_URL ?>/dispatch/simuler">
                    <input type="hidden" name="strategy" value="inferieur">
                    <div class="card h-100 border-success" style="cursor:pointer;" onclick="this.closest('form').submit();">
                        <div class="card-body text-center">
                            <i class="bi bi-sort-numeric-down text-success" style="font-size: 2.5rem;"></i>
                            <h5 class="card-title mt-2">Par Infériorité</h5>
                            <p class="card-text small text-muted">
                                Le plus <strong>petit besoin</strong> bénéficie en premier.<br>
                                Satisfaire d'abord les petites demandes.
                            </p>
                            <button type="submit" class="btn btn-outline-success btn-sm">
                                <i class="bi bi-eye"></i> Simuler
                            </button>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Par proportionnalité -->
            <div class="col-md-4">
                <form method="POST" action="<?= BASE_URL ?>/dispatch/simuler">
                    <input type="hidden" name="strategy" value="proportionnel">
                    <div class="card h-100 border-warning" style="cursor:pointer;" onclick="this.closest('form').submit();">
                        <div class="card-body text-center">
                            <i class="bi bi-pie-chart text-warning" style="font-size: 2.5rem;"></i>
                            <h5 class="card-title mt-2">Par Proportionnalité</h5>
                            <p class="card-text small text-muted">
                                Chacun reçoit selon son <strong>% de besoin</strong>.<br>
                                Distribution équitable et proportionnelle.
                            </p>
                            <button type="submit" class="btn btn-outline-warning btn-sm">
                                <i class="bi bi-eye"></i> Simuler
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Bouton Réinitialiser -->
<div class="data-card animate-in mb-4">
    <div class="data-card-header" style="background: linear-gradient(135deg, #dc3545 0%, #c82333 100%); color: white;">
        <i class="bi bi-arrow-counterclockwise"></i> Réinitialisation
    </div>
    <div class="data-card-body with-padding">
        <p class="text-muted mb-3">
            <i class="bi bi-exclamation-triangle text-warning"></i>
            Cette action supprimera <strong>tous les dispatches et achats</strong> enregistrés. 
            Les besoins et les dons seront conservés mais leurs attributions seront remises à zéro.
        </p>
        <form method="POST" action="<?= BASE_URL ?>/dispatch/reinitialiser" 
              onsubmit="return confirm('⚠️ ATTENTION !\n\nCette action va réinitialiser :\n- Tous les dispatches validés\n- Tous les achats effectués\n\nLes besoins et dons seront conservés.\n\nÊtes-vous sûr de vouloir continuer ?');">
            <button type="submit" class="btn btn-danger">
                <i class="bi bi-arrow-counterclockwise"></i> Réinitialiser toutes les actions
            </button>
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
