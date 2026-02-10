<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-6 col-md-8">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="/">Accueil</a></li>
                    <li class="breadcrumb-item"><a href="/user/profile">Mon profil</a></li>
                    <li class="breadcrumb-item active">Changer mot de passe</li>
                </ol>
            </nav>

            <div class="card shadow">
                <div class="card-header" style="background-color: #2E7D32; color: white;">
                    <h4 class="mb-0" style="font-family: 'Playfair Display', serif;">
                        <i class="fas fa-key me-2"></i>Changer mon mot de passe
                    </h4>
                </div>
                <div class="card-body p-4">

                    <?php if (!empty($errors)): ?>
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                <?php foreach ($errors as $error): ?>
                                    <li><?php echo htmlspecialchars($error); ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    <?php endif; ?>

                    <form method="POST" action="/user/password">
                        <?php echo $csrf; ?>

                        <div class="mb-3">
                            <label for="current_password" class="form-label">Mot de passe actuel <span class="text-danger">*</span></label>
                            <input type="password" class="form-control" id="current_password" name="current_password" required>
                        </div>

                        <hr class="my-4">

                        <div class="mb-3">
                            <label for="new_password" class="form-label">Nouveau mot de passe <span class="text-danger">*</span></label>
                            <input type="password" class="form-control" id="new_password" name="new_password" required>
                            <div class="form-text">
                                <small>
                                    Le mot de passe doit contenir au moins :
                                    <ul class="mb-0 mt-1">
                                        <li>10 caracteres</li>
                                        <li>1 majuscule</li>
                                        <li>1 minuscule</li>
                                        <li>1 chiffre</li>
                                        <li>1 caractere special (!@#$%^&*...)</li>
                                    </ul>
                                </small>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="confirm_password" class="form-label">Confirmer le nouveau mot de passe <span class="text-danger">*</span></label>
                            <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="/user/profile" class="btn btn-outline-secondary">
                                <i class="fas fa-arrow-left me-2"></i>Retour au profil
                            </a>
                            <button type="submit" class="btn" style="background-color: #2E7D32; color: white;">
                                <i class="fas fa-save me-2"></i>Changer le mot de passe
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
