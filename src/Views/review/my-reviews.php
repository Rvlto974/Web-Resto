<?php
require_once __DIR__ . '/../../Core/Auth.php';
?>

<div class="container py-5">
    <div class="row">
        <div class="col-lg-10 mx-auto">
            <!-- Breadcrumb -->
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="/">Accueil</a></li>
                    <li class="breadcrumb-item"><a href="/user/profile">Mon compte</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Mes avis</li>
                </ol>
            </nav>

            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 style="font-family: 'Playfair Display', serif; color: #333;">
                    <i class="fas fa-star me-2" style="color: #5DA99A;"></i>Mes avis
                </h2>
                <a href="/menu" class="btn" style="background-color: #5DA99A; color: white;">
                    <i class="fas fa-utensils me-2"></i>Voir les menus
                </a>
            </div>

            <?php if (empty($reviews)): ?>
                <!-- Aucun avis -->
                <div class="card shadow-sm">
                    <div class="card-body text-center py-5">
                        <i class="fas fa-comment-slash fa-4x mb-3" style="color: #ddd;"></i>
                        <h4 class="text-muted">Vous n'avez pas encore laisse d'avis</h4>
                        <p class="text-muted mb-4">Apres avoir recu une commande, vous pourrez partager votre experience.</p>
                        <a href="/order/history" class="btn" style="background-color: #FF8F00; color: white;">
                            <i class="fas fa-history me-2"></i>Voir mes commandes
                        </a>
                    </div>
                </div>
            <?php else: ?>
                <!-- Liste des avis -->
                <div class="row">
                    <?php foreach ($reviews as $review): ?>
                        <div class="col-md-6 mb-4">
                            <div class="card h-100 shadow-sm">
                                <div class="card-body">
                                    <!-- En-tete avec image menu -->
                                    <div class="d-flex mb-3">
                                        <?php if (!empty($review['menu_image'])): ?>
                                            <img src="<?php echo htmlspecialchars($review['menu_image']); ?>"
                                                 alt="<?php echo htmlspecialchars($review['menu_title']); ?>"
                                                 class="rounded me-3"
                                                 style="width: 80px; height: 60px; object-fit: cover;">
                                        <?php else: ?>
                                            <div class="rounded me-3 d-flex align-items-center justify-content-center"
                                                 style="width: 80px; height: 60px; background-color: #f5f5f5;">
                                                <i class="fas fa-utensils text-muted"></i>
                                            </div>
                                        <?php endif; ?>
                                        <div class="flex-grow-1">
                                            <h6 class="mb-1">
                                                <a href="/menu/show/<?php echo $review['menu_id']; ?>" class="text-decoration-none" style="color: #333;">
                                                    <?php echo htmlspecialchars($review['menu_title']); ?>
                                                </a>
                                            </h6>
                                            <?php if (!empty($review['order_number'])): ?>
                                                <small class="text-muted">
                                                    Commande <?php echo htmlspecialchars($review['order_number']); ?>
                                                </small>
                                            <?php endif; ?>
                                        </div>
                                    </div>

                                    <!-- Etoiles -->
                                    <div class="mb-2">
                                        <?php for ($i = 1; $i <= 5; $i++): ?>
                                            <i class="fas fa-star <?php echo $i <= $review['rating'] ? '' : 'text-muted'; ?>"
                                               style="<?php echo $i <= $review['rating'] ? 'color: #FFD700;' : ''; ?>"></i>
                                        <?php endfor; ?>
                                        <span class="ms-2 text-muted"><?php echo $review['rating']; ?>/5</span>
                                    </div>

                                    <!-- Commentaire -->
                                    <?php if (!empty($review['comment'])): ?>
                                        <p class="card-text mb-3">
                                            <?php echo nl2br(htmlspecialchars($review['comment'])); ?>
                                        </p>
                                    <?php else: ?>
                                        <p class="card-text text-muted fst-italic mb-3">Aucun commentaire</p>
                                    <?php endif; ?>

                                    <!-- Statut et date -->
                                    <div class="d-flex justify-content-between align-items-center">
                                        <small class="text-muted">
                                            <i class="fas fa-calendar-alt me-1"></i>
                                            <?php echo date('d/m/Y', strtotime($review['created_at'])); ?>
                                        </small>
                                        <?php if ($review['is_approved']): ?>
                                            <span class="badge bg-success">
                                                <i class="fas fa-check me-1"></i>Publie
                                            </span>
                                        <?php else: ?>
                                            <span class="badge bg-warning text-dark">
                                                <i class="fas fa-clock me-1"></i>En attente
                                            </span>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <!-- Info moderation -->
                <div class="alert alert-info mt-3" role="alert">
                    <i class="fas fa-info-circle me-2"></i>
                    Les avis en attente sont visibles uniquement par vous. Ils seront publies apres validation par notre equipe.
                </div>
            <?php endif; ?>

            <!-- Bouton retour -->
            <div class="mt-4">
                <a href="/user/profile" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left me-2"></i>Retour au profil
                </a>
            </div>
        </div>
    </div>
</div>
