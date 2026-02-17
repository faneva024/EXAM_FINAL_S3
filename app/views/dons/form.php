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
                <!-- Choix : donateur existant ou nouveau -->
                <div class="col-md-6">
                    <label class="form-label">Donateur</label>
                    <div class="form-check form-switch mb-2">
                        <input class="form-check-input" type="checkbox" id="toggleNouveauDonateur" onchange="toggleDonateur()">
                        <label class="form-check-label" for="toggleNouveauDonateur">Nouveau donateur</label>
                    </div>
                    <!-- Sélection donateur existant -->
                    <div id="selectDonateur">
                        <select name="id_user" id="id_user" class="form-select">
                            <option value="">-- Choisir un donateur existant --</option>
                            <?php foreach ($users as $u): ?>
                                <option value="<?= $u['id_user'] ?>"><?= htmlspecialchars($u['nom']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <!-- Saisie nouveau donateur -->
                    <div id="nouveauDonateur" style="display:none;">
                        <input type="text" name="nouveau_nom" id="nouveau_nom" class="form-control mb-2" placeholder="Nom du donateur">
                        <input type="email" name="nouveau_email" id="nouveau_email" class="form-control" placeholder="Email (optionnel)">
                    </div>
                </div>
                <div class="col-md-3">
                    <label for="id_categorie" class="form-label">Catégorie</label>
                    <select name="id_categorie" id="id_categorie" class="form-select" required onchange="toggleDonType()">
                        <option value="">-- Choisir une catégorie --</option>
                        <?php foreach ($categories as $c): ?>
                            <option value="<?= $c['id_categorie'] ?>"><?= htmlspecialchars($c['nom_categorie']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="nom_don" class="form-label">Nom du don</label>
                    <input type="text" name="nom_don" id="nom_don" class="form-control" list="suggestionsBesoins" placeholder="Tapez ou choisissez..." required autocomplete="off">
                    <datalist id="suggestionsBesoins"></datalist>
                    <small class="text-muted">Suggestions basées sur les besoins existants</small>
                </div>
            </div>

            <!-- Section Nature / Matériels -->
            <div id="sectionNature" class="row g-3 mt-2">
                <div class="col-md-4">
                    <label for="quantite" class="form-label">Quantité</label>
                    <input type="number" name="quantite" id="quantite" class="form-control" min="1" step="1" oninput="calculerMontant()">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Prix unitaire (Ar)</label>
                    <input type="text" id="prix_unitaire_affiche" class="form-control" readonly disabled>
                    <input type="hidden" name="prix_unitaire" id="prix_unitaire" value="0">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Montant total (Ar)</label>
                    <input type="text" id="montant_calcule_affiche" class="form-control fw-bold text-success" readonly disabled>
                    <input type="hidden" name="montant_calcule" id="montant_calcule" value="0">
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
                <button type="button" class="btn btn-primary" onclick="confirmerDon()">
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
// Données des suggestions : { nom_besoin: prix_unitaire }
let suggestionsData = {};

function toggleDonType() {
    const cat = document.getElementById('id_categorie').value;
    document.getElementById('sectionNature').style.display = (cat !== '3') ? '' : 'none';
    document.getElementById('sectionArgent').style.display = (cat === '3') ? '' : 'none';
    chargerSuggestions(cat);
    resetMontant();
}

function chargerSuggestions(idCategorie) {
    const datalist = document.getElementById('suggestionsBesoins');
    const nomDon = document.getElementById('nom_don');
    datalist.innerHTML = '';
    nomDon.value = '';
    suggestionsData = {};

    if (!idCategorie) return;

    fetch('<?= BASE_URL ?>/api/besoins/suggestions?id_categorie=' + idCategorie)
        .then(r => r.json())
        .then(items => {
            items.forEach(item => {
                suggestionsData[item.nom_besoin] = parseFloat(item.prix_unitaire);
                const opt = document.createElement('option');
                opt.value = item.nom_besoin;
                opt.textContent = item.nom_besoin + ' (PU: ' + formatNombre(item.prix_unitaire) + ' Ar)';
                datalist.appendChild(opt);
            });
        });
}

// Quand le nom du don change, mettre à jour le prix unitaire
document.getElementById('nom_don').addEventListener('input', function() {
    const nom = this.value;
    const pu = suggestionsData[nom] || 0;
    document.getElementById('prix_unitaire').value = pu;
    document.getElementById('prix_unitaire_affiche').value = pu > 0 ? formatNombre(pu) + ' Ar' : '';
    calculerMontant();
});

function calculerMontant() {
    const cat = document.getElementById('id_categorie').value;
    if (cat === '3') return;

    const pu = parseFloat(document.getElementById('prix_unitaire').value) || 0;
    const qte = parseFloat(document.getElementById('quantite').value) || 0;
    const montant = pu * qte;

    document.getElementById('montant_calcule').value = montant;
    document.getElementById('montant_calcule_affiche').value = montant > 0 ? formatNombre(montant) + ' Ar' : '';
}

function resetMontant() {
    document.getElementById('prix_unitaire').value = 0;
    document.getElementById('prix_unitaire_affiche').value = '';
    document.getElementById('montant_calcule').value = 0;
    document.getElementById('montant_calcule_affiche').value = '';
    document.getElementById('quantite').value = '';
}

function formatNombre(n) {
    return Number(n).toLocaleString('fr-FR');
}

function confirmerDon() {
    const cat = document.getElementById('id_categorie').value;
    const nomDon = document.getElementById('nom_don').value;

    if (!nomDon || !cat) {
        alert('Veuillez remplir tous les champs obligatoires.');
        return;
    }

    let message = '';

    if (cat === '3') {
        // Don en argent
        const montant = parseFloat(document.getElementById('montant').value) || 0;
        if (montant <= 0) {
            alert('Veuillez saisir un montant valide.');
            return;
        }
        message = 'Confirmer le don en argent :\n\n'
            + '  Don : ' + nomDon + '\n'
            + '  Montant : ' + formatNombre(montant) + ' Ar\n\n'
            + 'Voulez-vous enregistrer ce don ?';
    } else {
        // Don en nature / matériels
        const qte = parseFloat(document.getElementById('quantite').value) || 0;
        const pu = parseFloat(document.getElementById('prix_unitaire').value) || 0;
        const montantTotal = pu * qte;

        if (qte <= 0) {
            alert('Veuillez saisir une quantité valide.');
            return;
        }

        message = 'Confirmer le don :\n\n'
            + '  Don : ' + nomDon + '\n'
            + '  Quantité : ' + formatNombre(qte) + '\n';
        
        if (pu > 0) {
            message += '  Prix unitaire : ' + formatNombre(pu) + ' Ar\n'
                + '  Montant total : ' + formatNombre(montantTotal) + ' Ar\n';
        } else {
            message += '  (Prix unitaire non défini dans les besoins)\n';
        }

        message += '\nVoulez-vous enregistrer ce don ?';
    }

    if (confirm(message)) {
        document.getElementById('formDon').submit();
    }
}

function toggleDonateur() {
    const isNew = document.getElementById('toggleNouveauDonateur').checked;
    document.getElementById('selectDonateur').style.display = isNew ? 'none' : '';
    document.getElementById('nouveauDonateur').style.display = isNew ? '' : 'none';
    if (isNew) {
        document.getElementById('id_user').value = '';
        document.getElementById('nouveau_nom').focus();
    } else {
        document.getElementById('nouveau_nom').value = '';
        document.getElementById('nouveau_email').value = '';
    }
}
</script>

<?php include __DIR__ . '/../layout_footer.php'; ?>
