<?php include __DIR__ . '/../../inc/header.php'; ?>

<div class="row justify-content-center mt-5">
    <div class="col-md-5">
        <div class="card table-card">
            <div class="card-header text-center py-3">
                <h4 class="mb-0"><i class="bi bi-person-circle me-2"></i>Connexion</h4>
                <small class="text-muted">Entrez vos informations pour continuer</small>
            </div>
            <div class="card-body p-4">

                <?php if (!empty($error)): ?>
                    <div class="alert alert-danger d-flex align-items-center" role="alert">
                        <i class="bi bi-exclamation-triangle-fill me-2"></i>
                        <?= htmlspecialchars($error) ?>
                    </div>
                <?php endif; ?>

                <div class="alert alert-info d-flex align-items-center" role="alert">
                    <i class="bi bi-info-circle-fill me-2"></i>
                    <small>Pas besoin de mot de passe. Entrez simplement votre nom et email pour vous connecter ou créer un compte automatiquement.</small>
                </div>

                <form method="POST" action="/login">
                    <div class="mb-3">
                        <label for="nom" class="form-label"><i class="bi bi-person me-1"></i>Nom complet</label>
                        <input type="text" class="form-control" id="nom" name="nom" 
                               placeholder="Ex: Jean Rakoto" required
                               value="<?= htmlspecialchars($_POST['nom'] ?? '') ?>">
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label"><i class="bi bi-envelope me-1"></i>Email</label>
                        <input type="email" class="form-control" id="email" name="email" 
                               placeholder="Ex: jean@email.com" required
                               value="<?= htmlspecialchars($_POST['email'] ?? '') ?>">
                    </div>

                    <div class="d-grid mt-4">
                        <button type="submit" class="btn btn-primary btn-lg">
                            <i class="bi bi-box-arrow-in-right me-2"></i>Se connecter
                        </button>
                    </div>
                </form>

                <div class="text-center mt-3">
                    <a href="/" class="text-muted text-decoration-none">
                        <i class="bi bi-arrow-left me-1"></i>Retour au Dashboard
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../../inc/footer.php'; ?>
