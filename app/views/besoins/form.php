<?php
/**
 * Formulaire d'ajout de besoin
 * @var array $villes
 * @var array $categories
 */
$pageTitle = 'Saisir un besoin';
include __DIR__ . '/../layout_header.php';
$error = $_GET['error'] ?? null;
?>

<div class="page-header animate-in">
    <h2><i class="bi bi-plus-circle"></i> Saisir un Besoin</h2>
    <p>Enregistrer un nouveau besoin pour une ville sinistrée (nature, matériels ou argent)</p>
</div>

<?php if ($error): ?>
    <div class="alert alert-danger animate-in"><i class="bi bi-exclamation-triangle"></i> Veuillez remplir correctement tous les champs.</div>
<?php endif; ?>

<div class="data-card animate-in">
    <div class="data-card-header">
        <i class="bi bi-pencil-square"></i> Formulaire de saisie
    </div>
    <div class="data-card-body with-padding">
        <form method="POST" action="<?= BASE_URL ?>/besoins/ajouter">
            <div class="row g-3">
                <div class="col-md-4">
                    <label for="id_ville" class="form-label">Ville</label>
                    <select name="id_ville" id="id_ville" class="form-select" required>
                        <option value="">-- Choisir une ville --</option>
                        <?php foreach ($villes as $v): ?>
                            <option value="<?= $v['id_ville'] ?>"><?= htmlspecialchars($v['nom_ville']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-4">
                    <label for="id_categorie" class="form-label">Catégorie</label>
                    <select name="id_categorie" id="id_categorie" class="form-select" required onchange="toggleBesoinType()">
                        <option value="">-- Choisir une catégorie --</option>
                        <?php foreach ($categories as $c): ?>
                            <option value="<?= $c['id_categorie'] ?>"><?= htmlspecialchars($c['nom_categorie']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <!-- Section Nature / Matériels -->
            <div id="sectionNatureMateriels">
                <div class="row g-3 mt-2">
                    <div class="col-md-4">
                        <label for="nom_besoin" class="form-label">Nom du besoin</label>
                        <input type="text" name="nom_besoin" id="nom_besoin" class="form-control" placeholder="Ex: Riz, Tôles...">
                    </div>
                    <div class="col-md-3">
                        <label for="prix_unitaire" class="form-label">Prix unitaire (Ar)</label>
                        <input type="number" name="prix_unitaire" id="prix_unitaire" class="form-control" min="1" step="1">
                    </div>
                    <div class="col-md-2">
                        <label for="quantite" class="form-label">Quantité</label>
                        <input type="number" name="quantite" id="quantite" class="form-control" min="1" step="1">
                    </div>
                </div>
            </div>

            <!-- Section Argent -->
            <div id="sectionArgent" style="display:none;">
                <div class="row g-3 mt-2">
                    <div class="col-md-4">
                        <label for="nom_besoin_argent" class="form-label">Description du besoin</label>
                        <input type="text" name="nom_besoin_argent" id="nom_besoin_argent" class="form-control" placeholder="Ex: Fonds reconstruction, Aide alimentaire...">
                    </div>
                    <div class="col-md-3">
                        <label for="montant_besoin" class="form-label">Montant total nécessaire (Ar)</label>
                        <input type="number" name="montant_besoin" id="montant_besoin" class="form-control" min="1" step="1">
                    </div>
                </div>
            </div>

            <div class="mt-3">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-check-circle"></i> Enregistrer
                </button>
                <a href="<?= BASE_URL ?>/besoins" class="btn btn-outline-secondary ms-2">
                    <i class="bi bi-arrow-left"></i> Retour à la liste
                </a>
            </div>
        </form>
    </div>
</div>

<script>
function toggleBesoinType() {
    const cat = document.getElementById('id_categorie').value;
    const isArgent = (cat === '3');
    document.getElementById('sectionNatureMateriels').style.display = isArgent ? 'none' : '';
    document.getElementById('sectionArgent').style.display = isArgent ? '' : 'none';
}
document.addEventListener('DOMContentLoaded', toggleBesoinType);
</script>

<?php include __DIR__ . '/../layout_footer.php'; ?>
