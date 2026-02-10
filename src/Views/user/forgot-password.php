<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-5 col-md-7">
            <div class="card shadow">
                <div class="card-header text-center" style="background-color: #5DA99A; color: white;">
                    <h4 class="mb-0" style="font-family: 'Playfair Display', serif;">
                        <i class="fas fa-unlock-alt me-2"></i>Mot de passe oublie
                    </h4>
                </div>
                <div class="card-body p-4">

                    <?php if ($success): ?>
                        <div class="alert alert-success">
                            <i class="fas fa-check-circle me-2"></i>
                            <strong>Email envoye !</strong><br>
                            Si un compte existe avec cette adresse email, vous recevrez un lien de reinitialisation.
                            <br><br>
                            <small>Pensez a verifier vos spams si vous ne recevez pas l'email.</small>
                        </div>
                        <div class="text-center">
                            <a href="/user/login" class="btn" style="background-color: #5DA99A; color: white;">
                                <i class="fas fa-sign-in-alt me-2"></i>Retour a la connexion
                            </a>
                        </div>
                    <?php else: ?>

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
                            Entrez votre adresse email. Si un compte existe, vous recevrez un lien pour reinitialiser votre mot de passe.
                        </p>

                        <form method="POST" action="/user/forgot-password">
                            <?php echo $csrf; ?>

                            <div class="mb-4">
                                <label for="email" class="form-label">Adresse email</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                    <input type="email" class="form-control" id="email" name="email" required autofocus
                                           placeholder="votre@email.com">
                                </div>
                            </div>

                            <button type="submit" class="btn btn-lg w-100" style="background-color: #5DA99A; color: white;">
                                <i class="fas fa-paper-plane me-2"></i>Envoyer le lien
                            </button>
                        </form>

                        <hr class="my-4">

                        <div class="text-center">
                            <a href="/user/login" class="text-decoration-none" style="color: #5DA99A;">
                                <i class="fas fa-arrow-left me-2"></i>Retour a la connexion
                            </a>
                        </div>

                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
