<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-6 col-md-8">
            <div class="card shadow">
                <div class="card-header text-center" style="background-color: #5DA99A; color: white;">
                    <h2 class="mb-0" style="font-family: 'Playfair Display', serif;">
                        <i class="fas fa-user-plus me-2"></i>Inscription
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

                    <form method="POST" action="/user/register">
                        <?php echo $csrf; ?>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="first_name" class="form-label">Prenom <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="first_name" name="first_name"
                                       value="<?php echo htmlspecialchars($old['first_name'] ?? ''); ?>" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="last_name" class="form-label">Nom <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="last_name" name="last_name"
                                       value="<?php echo htmlspecialchars($old['last_name'] ?? ''); ?>" required>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                            <input type="email" class="form-control" id="email" name="email"
                                   value="<?php echo htmlspecialchars($old['email'] ?? ''); ?>" required>
                        </div>

                        <div class="mb-3">
                            <label for="phone" class="form-label">Telephone <span class="text-danger">*</span></label>
                            <input type="tel" class="form-control" id="phone" name="phone"
                                   value="<?php echo htmlspecialchars($old['phone'] ?? ''); ?>" required
                                   placeholder="06 12 34 56 78">
                        </div>

                        <hr class="my-4">
                        <h5 class="mb-3"><i class="fas fa-map-marker-alt me-2"></i>Adresse (optionnel)</h5>

                        <div class="mb-3">
                            <label for="address" class="form-label">Adresse</label>
                            <input type="text" class="form-control" id="address" name="address"
                                   value="<?php echo htmlspecialchars($old['address'] ?? ''); ?>">
                        </div>

                        <div class="row">
                            <div class="col-md-8 mb-3">
                                <label for="city" class="form-label">Ville</label>
                                <input type="text" class="form-control" id="city" name="city"
                                       value="<?php echo htmlspecialchars($old['city'] ?? ''); ?>">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="postal_code" class="form-label">Code postal</label>
                                <input type="text" class="form-control" id="postal_code" name="postal_code"
                                       value="<?php echo htmlspecialchars($old['postal_code'] ?? ''); ?>">
                            </div>
                        </div>

                        <hr class="my-4">
                        <h5 class="mb-3"><i class="fas fa-lock me-2"></i>Mot de passe</h5>

                        <div class="mb-3">
                            <label for="password" class="form-label">Mot de passe <span class="text-danger">*</span></label>
                            <input type="password" class="form-control" id="password" name="password" required>
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
                            <label for="password_confirm" class="form-label">Confirmer le mot de passe <span class="text-danger">*</span></label>
                            <input type="password" class="form-control" id="password_confirm" name="password_confirm" required>
                        </div>

                        <div class="mb-3 form-check">
                            <input type="checkbox" class="form-check-input" id="terms" required>
                            <label class="form-check-label" for="terms">
                                J'accepte les <a href="/pages/cgv" target="_blank">conditions generales de vente</a>
                                et la <a href="/pages/politique-confidentialite" target="_blank">politique de confidentialite</a>
                            </label>
                        </div>

                        <button type="submit" class="btn btn-lg w-100" style="background-color: #5DA99A; color: white;">
                            <i class="fas fa-user-plus me-2"></i>Creer mon compte
                        </button>
                    </form>

                    <hr class="my-4">

                    <p class="text-center mb-0">
                        Deja inscrit ?
                        <a href="/user/login" style="color: #5DA99A; font-weight: 600;">Connectez-vous</a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
