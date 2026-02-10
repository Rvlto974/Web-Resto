<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-5 col-md-7">
            <div class="card shadow">
                <div class="card-header text-center" style="background-color: #5DA99A; color: white;">
                    <h4 class="mb-0" style="font-family: 'Playfair Display', serif;">
                        <i class="fas fa-key me-2"></i>Nouveau mot de passe
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

                    <p class="text-muted mb-4">
                        Choisissez un nouveau mot de passe pour votre compte.
                    </p>

                    <form method="POST" action="/user/reset-password/<?php echo htmlspecialchars($token); ?>">
                        <?php echo $csrf; ?>

                        <div class="mb-3">
                            <label for="password" class="form-label">Nouveau mot de passe</label>
                            <input type="password" class="form-control" id="password" name="password" required autofocus>
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
                            <label for="password_confirm" class="form-label">Confirmer le mot de passe</label>
                            <input type="password" class="form-control" id="password_confirm" name="password_confirm" required>
                        </div>

                        <button type="submit" class="btn btn-lg w-100" style="background-color: #5DA99A; color: white;">
                            <i class="fas fa-save me-2"></i>Reinitialiser le mot de passe
                        </button>
                    </form>

                    <hr class="my-4">

                    <div class="text-center">
                        <a href="/user/login" class="text-decoration-none" style="color: #5DA99A;">
                            <i class="fas fa-arrow-left me-2"></i>Retour a la connexion
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
