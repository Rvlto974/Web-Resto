<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-5 col-md-7">
            <div class="card shadow">
                <div class="card-header text-center" style="background-color: #2E7D32; color: white;">
                    <h2 class="mb-0" style="font-family: 'Playfair Display', serif;">
                        <i class="fas fa-sign-in-alt me-2"></i>Connexion
                    </h2>
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

                    <form method="POST" action="/user/login">
                        <?php echo $csrf; ?>

                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                <input type="email" class="form-control" id="email" name="email"
                                       value="<?php echo htmlspecialchars($email ?? ''); ?>" required autofocus>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label">Mot de passe</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-lock"></i></span>
                                <input type="password" class="form-control" id="password" name="password" required>
                            </div>
                        </div>

                        <div class="mb-3 d-flex justify-content-between align-items-center">
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" id="remember" name="remember">
                                <label class="form-check-label" for="remember">Se souvenir de moi</label>
                            </div>
                            <a href="/user/forgot-password" class="text-decoration-none" style="color: #2E7D32;">
                                Mot de passe oublie ?
                            </a>
                        </div>

                        <button type="submit" class="btn btn-lg w-100" style="background-color: #2E7D32; color: white;">
                            <i class="fas fa-sign-in-alt me-2"></i>Se connecter
                        </button>
                    </form>

                    <hr class="my-4">

                    <p class="text-center mb-0">
                        Pas encore de compte ?
                        <a href="/user/register" style="color: #FF8F00; font-weight: 600;">Inscrivez-vous</a>
                    </p>
                </div>
            </div>

            <!-- Info box -->
            <div class="card mt-4 border-0" style="background-color: #F5F5F5;">
                <div class="card-body text-center">
                    <h5 style="color: #2E7D32;"><i class="fas fa-info-circle me-2"></i>Premiere visite ?</h5>
                    <p class="mb-0">
                        Creez votre compte pour commander nos delicieux menus et profiter de nos services de traiteur.
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
