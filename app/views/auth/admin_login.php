<?php include __DIR__ . '/../../inc/header.php'; ?>

<div class="row justify-content-center mt-5">
    <div class="col-md-5">
        <div class="card table-card">
            <div class="card-header text-center py-3">
                <h4 class="mb-0"><i class="bi bi-shield-lock me-2"></i>Administration</h4>
                <small class="text-muted">Connexion réservée aux administrateurs</small>
            </div>
            <div class="card-body p-4">

                <?php if (!empty($error)): ?>
                    <div class="alert alert-danger d-flex align-items-center" role="alert">
                        <i class="bi bi-exclamation-triangle-fill me-2"></i>
                        <?= htmlspecialchars($error) ?>
                    </div>
                <?php endif; ?>

                <form method="POST" action="/admin/login">
                    <div class="mb-3">
                        <label for="email" class="form-label"><i class="bi bi-envelope me-1"></i>Email</label>
                        <input type="email" class="form-control" id="email" name="email" 
                               placeholder="admin@bngrc.mg" required
                               value="<?= htmlspecialchars($_POST['email'] ?? '') ?>">
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label"><i class="bi bi-key me-1"></i>Mot de passe</label>
                        <input type="password" class="form-control" id="password" name="password" 
                               placeholder="Votre mot de passe" required>
                    </div>

                    <div class="d-grid mt-4">
                        <button type="submit" class="btn btn-dark btn-lg">
                            <i class="bi bi-shield-check me-2"></i>Connexion Admin
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
