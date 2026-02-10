<div class="container py-5">
    <div class="row">
        <!-- Sidebar -->
        <div class="col-lg-3 mb-4">
            <div class="card shadow-sm">
                <div class="card-header text-center" style="background-color: #2E7D32; color: white;">
                    <i class="fas fa-user-circle fa-3x mb-2"></i>
                    <h5 class="mb-0"><?php echo htmlspecialchars($user['first_name'] . ' ' . $user['last_name']); ?></h5>
                    <small><?php echo htmlspecialchars($user['email']); ?></small>
                </div>
                <div class="list-group list-group-flush">
                    <a href="/user/profile" class="list-group-item list-group-item-action active">
                        <i class="fas fa-user me-2"></i>Mon profil
                    </a>
                    <a href="/user/password" class="list-group-item list-group-item-action">
                        <i class="fas fa-key me-2"></i>Changer mot de passe
                    </a>
                    <a href="/order/history" class="list-group-item list-group-item-action">
                        <i class="fas fa-history me-2"></i>Mes commandes
                    </a>
                    <?php if ($user['role'] === 'admin' || $user['role'] === 'employee'): ?>
                        <a href="/admin" class="list-group-item list-group-item-action">
                            <i class="fas fa-tachometer-alt me-2"></i>Administration
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Contenu principal -->
        <div class="col-lg-9">
            <div class="card shadow">
                <div class="card-header">
                    <h4 class="mb-0" style="font-family: 'Playfair Display', serif; color: #2E7D32;">
                        <i class="fas fa-user-edit me-2"></i>Modifier mon profil
                    </h4>
                </div>
                <div class="card-body">

                    <?php if (!empty($errors)): ?>
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                <?php foreach ($errors as $error): ?>
                                    <li><?php echo htmlspecialchars($error); ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    <?php endif; ?>

                    <form method="POST" action="/user/profile">
                        <?php echo $csrf; ?>

                        <h5 class="mb-3 text-muted"><i class="fas fa-id-card me-2"></i>Informations personnelles</h5>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="first_name" class="form-label">Prenom <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="first_name" name="first_name"
                                       value="<?php echo htmlspecialchars($user['first_name']); ?>" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="last_name" class="form-label">Nom <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="last_name" name="last_name"
                                       value="<?php echo htmlspecialchars($user['last_name']); ?>" required>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email"
                                   value="<?php echo htmlspecialchars($user['email']); ?>" disabled>
                            <div class="form-text">L'email ne peut pas etre modifie.</div>
                        </div>

                        <div class="mb-3">
                            <label for="phone" class="form-label">Telephone <span class="text-danger">*</span></label>
                            <input type="tel" class="form-control" id="phone" name="phone"
                                   value="<?php echo htmlspecialchars($user['phone']); ?>" required>
                        </div>

                        <hr class="my-4">
                        <h5 class="mb-3 text-muted"><i class="fas fa-map-marker-alt me-2"></i>Adresse de livraison</h5>

                        <div class="mb-3">
                            <label for="address" class="form-label">Adresse</label>
                            <input type="text" class="form-control" id="address" name="address"
                                   value="<?php echo htmlspecialchars($user['address'] ?? ''); ?>">
                        </div>

                        <div class="row">
                            <div class="col-md-8 mb-3">
                                <label for="city" class="form-label">Ville</label>
                                <input type="text" class="form-control" id="city" name="city"
                                       value="<?php echo htmlspecialchars($user['city'] ?? ''); ?>">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="postal_code" class="form-label">Code postal</label>
                                <input type="text" class="form-control" id="postal_code" name="postal_code"
                                       value="<?php echo htmlspecialchars($user['postal_code'] ?? ''); ?>">
                            </div>
                        </div>

                        <hr class="my-4">

                        <div class="d-flex justify-content-between">
                            <a href="/user/password" class="btn btn-outline-secondary">
                                <i class="fas fa-key me-2"></i>Changer mot de passe
                            </a>
                            <button type="submit" class="btn" style="background-color: #2E7D32; color: white;">
                                <i class="fas fa-save me-2"></i>Enregistrer les modifications
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Informations du compte -->
            <div class="card mt-4 shadow-sm">
                <div class="card-body">
                    <h5 class="text-muted"><i class="fas fa-info-circle me-2"></i>Informations du compte</h5>
                    <div class="row">
                        <div class="col-md-4">
                            <p class="mb-1"><strong>Role :</strong></p>
                            <p>
                                <?php
                                $roleLabels = [
                                    'client' => '<span class="badge bg-primary">Client</span>',
                                    'employee' => '<span class="badge bg-info">Employe</span>',
                                    'admin' => '<span class="badge bg-danger">Administrateur</span>'
                                ];
                                echo $roleLabels[$user['role']] ?? $user['role'];
                                ?>
                            </p>
                        </div>
                        <div class="col-md-4">
                            <p class="mb-1"><strong>Membre depuis :</strong></p>
                            <p><?php echo date('d/m/Y', strtotime($user['created_at'])); ?></p>
                        </div>
                        <div class="col-md-4">
                            <p class="mb-1"><strong>Derniere connexion :</strong></p>
                            <p><?php echo $user['last_login'] ? date('d/m/Y H:i', strtotime($user['last_login'])) : 'Jamais'; ?></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
