<?php
/**
 * Formulaire d'ajout de don
 * @var array $categories
 * @var array $users
 */
$pageTitle = 'Saisir un don';
include __DIR__ . '/../layout_header.php';
$error = $_GET['error'] ?? null;
?>

<div class="page-header animate-in">
    <h2><i class="bi bi-plus-circle"></i> Saisir un Don</h2>
    <p>Enregistrer un nouveau don (nature, matériels ou argent)</p>
</div>

<?php if ($error): ?>
    <div class="alert alert-danger animate-in"><i class="bi bi-exclamation-triangle"></i> Veuillez remplir correctement tous les champs.</div>
<?php endif; ?>

<div class="data-card animate-in">
    <div class="data-card-header">
        <i class="bi bi-pencil-square"></i> Formulaire de saisie
    </div>
    <div class="data-card-body with-padding">
        <form method="POST" action="<?= BASE_URL ?>/dons/ajouter" id="formDon">
            <div class="row g-3">
                <div class="col-md-4">
                    <label for="id_user" class="form-label">Donateur</label>
                    <select name="id_user" id="id_user" class="form-select" required>
                        <option value="">-- Choisir un donateur --</option>
                        <?php foreach ($users as $u): ?>
                            <option value="<?= $u['id_user'] ?>"><?= htmlspecialchars($u['nom']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-4">
                    <label for="id_categorie" class="form-label">Catégorie</label>
                    <select name="id_categorie" id="id_categorie" class="form-select" required onchange="toggleDonType()">
                        <option value="">-- Choisir une catégorie --</option>
                        <?php foreach ($categories as $c): ?>
                            <option value="<?= $c['id_categorie'] ?>"><?= htmlspecialchars($c['nom_categorie']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-4">
                    <label for="nom_don" class="form-label">Nom du don</label>
                    <input type="text" name="nom_don" id="nom_don" class="form-control" placeholder="Ex: Riz, Tôles, Don financier..." required>
                </div>
            </div>

            <!-- Section Nature / Matériels -->
            <div id="sectionNature" class="row g-3 mt-2">
                <div class="col-md-4">
                    <label for="quantite" class="form-label">Quantité</label>
                    <input type="number" name="quantite" id="quantite" class="form-control" min="1" step="1">
                </div>
            </div>

            <!-- Section Argent -->
            <div id="sectionArgent" class="row g-3 mt-2" style="display:none;">
                <div class="col-md-4">
                    <label for="montant" class="form-label">Montant (Ar)</label>
                    <input type="number" name="montant" id="montant" class="form-control" min="1" step="1">
                </div>
            </div>

            <div class="mt-4">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-check-circle"></i> Enregistrer
                </button>
                <a href="<?= BASE_URL ?>/dons" class="btn btn-outline-secondary ms-2">
                    <i class="bi bi-arrow-left"></i> Retour
                </a>
            </div>
        </form>
    </div>
</div>

<script>
function toggleDonType() {
    const cat = document.getElementById('id_categorie').value;
    document.getElementById('sectionNature').style.display = (cat !== '3') ? '' : 'none';
    document.getElementById('sectionArgent').style.display = (cat === '3') ? '' : 'none';
}
</script>

<?php include __DIR__ . '/../layout_footer.php'; ?>
