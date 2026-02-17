<?php
/**
 * Page de configuration
 * @var float $frais
 */
$pageTitle = 'Configuration';
include __DIR__ . '/layout_header.php';
$success = $_GET['success'] ?? null;
?>

<div class="page-header animate-in">
    <h2><i class="bi bi-gear"></i> Configuration</h2>
    <p>Paramétrage des frais et options de l'application</p>
</div>

<?php if ($success): ?>
    <div class="alert alert-success alert-dismissible fade show animate-in">
        <i class="bi bi-check-circle"></i> Configuration sauvegardée avec succès !
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<div class="data-card animate-in" style="max-width: 550px;">
    <div class="data-card-header">
        <i class="bi bi-sliders"></i> Paramètres
    </div>
    <div class="data-card-body with-padding">
        <form method="POST" action="<?= BASE_URL ?>/configuration">
            <div class="mb-3">
                <label for="frais_achat_pourcent" class="form-label">
                    Frais d'achat (%)
                </label>
                <div class="input-group">
                    <input type="number" name="frais_achat_pourcent" id="frais_achat_pourcent" 
                           class="form-control" value="<?= $frais ?>" min="0" max="100" step="0.1" required>
                    <span class="input-group-text">%</span>
                </div>
                <small class="form-text text-muted">
                    Pourcentage de frais appliqué sur les achats via dons en argent. 
                    Ex: Si 10%, un achat de 100 Ar coûtera 110 Ar.
                </small>
            </div>
            <button type="submit" class="btn btn-primary">
                <i class="bi bi-save"></i> Sauvegarder
            </button>
        </form>
    </div>
</div>

<?php include __DIR__ . '/layout_footer.php'; ?>
