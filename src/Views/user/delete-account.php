<?php
require_once __DIR__ . '/../../Core/Auth.php';
require_once __DIR__ . '/../../Core/Csrf.php';
?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-6 col-md-8">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="/">Accueil</a></li>
                    <li class="breadcrumb-item"><a href="/user/profile">Mon profil</a></li>
                    <li class="breadcrumb-item active">Supprimer mon compte</li>
                </ol>
            </nav>

            <div class="card shadow border-danger">
                <div class="card-header bg-danger text-white">
                    <h4 class="mb-0" style="font-family: 'Playfair Display', serif;">
                        <i class="fas fa-exclamation-triangle me-2"></i>Supprimer mon compte
                    </h4>
                </div>
                <div class="card-body p-4">

                    <div class="alert alert-warning">
                        <h5><i class="fas fa-warning me-2"></i>Attention</h5>
                        <p class="mb-0">La suppression de votre compte est <strong>definitive et irreversible</strong>.</p>
                    </div>

                    <div class="mb-4">
                        <h6>Ce qui sera supprime :</h6>
                        <ul class="text-muted">
                            <li>Vos informations personnelles (nom, email, telephone, adresse)</li>
                            <li>Vos avis et commentaires</li>
                            <li>Votre acces a l'historique des commandes</li>
                        </ul>

                        <h6>Ce qui sera conserve (obligation legale) :</h6>
                        <ul class="text-muted">
                            <li>L'historique de vos commandes (anonymise, pour la comptabilite)</li>
                            <li>Les factures associees (10 ans)</li>
                        </ul>
                    </div>

                    <?php if (!empty($errors)): ?>
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                <?php foreach ($errors as $error): ?>
                                    <li><?php echo htmlspecialchars($error); ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    <?php endif; ?>

                    <form method="POST" action="/user/deleteAccount">
                        <?php echo $csrf; ?>

                        <div class="mb-3">
                            <label for="password" class="form-label">
                                Mot de passe actuel <span class="text-danger">*</span>
                            </label>
                            <input type="password" class="form-control" id="password" name="password" required
                                   placeholder="Entrez votre mot de passe pour confirmer">
                        </div>

                        <div class="mb-4">
                            <label for="confirmation" class="form-label">
                                Tapez <strong class="text-danger">SUPPRIMER</strong> pour confirmer <span class="text-danger">*</span>
                            </label>
                            <input type="text" class="form-control" id="confirmation" name="confirmation" required
                                   placeholder="SUPPRIMER" autocomplete="off">
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="/user/profile" class="btn btn-outline-secondary">
                                <i class="fas fa-arrow-left me-2"></i>Annuler
                            </a>
                            <button type="submit" class="btn btn-danger">
                                <i class="fas fa-trash-alt me-2"></i>Supprimer definitivement
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="card mt-4 shadow-sm">
                <div class="card-body">
                    <h6><i class="fas fa-download me-2" style="color: #5DA99A;"></i>Avant de partir...</h6>
                    <p class="text-muted mb-2">
                        Vous pouvez telecharger une copie de vos donnees personnelles avant de supprimer votre compte.
                    </p>
                    <a href="/user/exportData" class="btn btn-sm btn-outline-primary">
                        <i class="fas fa-file-download me-2"></i>Telecharger mes donnees
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
