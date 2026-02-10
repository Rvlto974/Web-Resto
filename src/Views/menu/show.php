<?php
require_once __DIR__ . '/../../Core/Auth.php';
require_once __DIR__ . '/../../Core/Csrf.php';
?>

<div class="container py-5">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/">Accueil</a></li>
            <li class="breadcrumb-item"><a href="/menu">Nos Menus</a></li>
            <li class="breadcrumb-item active" aria-current="page"><?php echo htmlspecialchars($menu['title']); ?></li>
        </ol>
    </nav>

    <div class="row">
        <!-- Galerie d'images -->
        <div class="col-lg-6 mb-4">
            <div class="card shadow">
                <!-- Image principale -->
                <?php if ($menu['main_image_url']): ?>
                    <img src="<?php echo htmlspecialchars($menu['main_image_url']); ?>"
                         class="card-img-top" alt="<?php echo htmlspecialchars($menu['title']); ?>"
                         id="mainImage" style="height: 400px; object-fit: cover;">
                <?php else: ?>
                    <div class="card-img-top d-flex align-items-center justify-content-center"
                         style="height: 400px; background: linear-gradient(135deg, #2E7D32, #4CAF50);">
                        <i class="fas fa-utensils fa-5x text-white"></i>
                    </div>
                <?php endif; ?>

                <!-- Miniatures -->
                <?php if (!empty($images)): ?>
                    <div class="card-body">
                        <div class="d-flex gap-2 overflow-auto">
                            <?php if ($menu['main_image_url']): ?>
                                <img src="<?php echo htmlspecialchars($menu['main_image_url']); ?>"
                                     class="img-thumbnail thumbnail-img active" style="width: 80px; height: 60px; object-fit: cover; cursor: pointer;"
                                     onclick="changeImage(this.src)">
                            <?php endif; ?>
                            <?php foreach ($images as $img): ?>
                                <img src="<?php echo htmlspecialchars($img['image_url']); ?>"
                                     class="img-thumbnail thumbnail-img" style="width: 80px; height: 60px; object-fit: cover; cursor: pointer;"
                                     onclick="changeImage(this.src)">
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Informations du menu -->
        <div class="col-lg-6 mb-4">
            <div class="card shadow h-100">
                <div class="card-body">
                    <!-- Titre et badges -->
                    <h1 class="mb-3" style="font-family: 'Playfair Display', serif; color: #2E7D32;">
                        <?php echo htmlspecialchars($menu['title']); ?>
                    </h1>

                    <div class="mb-3">
                        <?php
                        $themeBadges = [
                            'christmas' => 'bg-danger',
                            'easter' => 'bg-purple',
                            'classic' => 'bg-success',
                            'event' => 'bg-primary',
                            'seasonal' => 'bg-warning text-dark'
                        ];
                        $badgeClass = $themeBadges[$menu['theme']] ?? 'bg-secondary';
                        ?>
                        <span class="badge <?php echo $badgeClass; ?> me-2">
                            <i class="fas fa-tag me-1"></i><?php echo htmlspecialchars($themeLabels[$menu['theme']] ?? $menu['theme']); ?>
                        </span>
                        <span class="badge bg-info">
                            <i class="fas fa-leaf me-1"></i><?php echo htmlspecialchars($dietaryTypeLabels[$menu['dietary_type']] ?? $menu['dietary_type']); ?>
                        </span>
                    </div>

                    <!-- Note -->
                    <?php if ($rating['count'] > 0): ?>
                        <div class="mb-3">
                            <?php for ($i = 1; $i <= 5; $i++): ?>
                                <i class="fas fa-star fa-lg <?php echo $i <= round($rating['average']) ? 'text-warning' : 'text-muted'; ?>"></i>
                            <?php endfor; ?>
                            <span class="ms-2 fw-bold"><?php echo $rating['average']; ?>/5</span>
                            <span class="text-muted">(<?php echo $rating['count']; ?> avis)</span>
                        </div>
                    <?php endif; ?>

                    <!-- Prix -->
                    <div class="mb-4 p-3 rounded" style="background-color: #F5F5F5;">
                        <p class="mb-1">
                            <span class="h2 fw-bold" style="color: #FF8F00;">
                                <?php echo number_format($menu['base_price'], 2, ',', ' '); ?> EUR
                            </span>
                        </p>
                        <p class="mb-0 text-muted">
                            Pour <?php echo $menu['min_people']; ?> personnes minimum
                            <?php if ($menu['price_per_extra_person'] > 0): ?>
                                <br><small>(+<?php echo number_format($menu['price_per_extra_person'], 2, ',', ' '); ?> EUR/personne supplementaire)</small>
                            <?php endif; ?>
                        </p>
                        <?php if ($menu['discount_threshold'] > 0): ?>
                            <p class="mb-0 mt-2">
                                <span class="badge bg-success">
                                    <i class="fas fa-percent me-1"></i>-10% a partir de +<?php echo $menu['discount_threshold']; ?> personnes
                                </span>
                            </p>
                        <?php endif; ?>
                    </div>

                    <!-- Description -->
                    <div class="mb-4">
                        <h5><i class="fas fa-info-circle me-2" style="color: #2E7D32;"></i>Description</h5>
                        <p><?php echo nl2br(htmlspecialchars($menu['description'])); ?></p>
                    </div>

                    <!-- Allergenes -->
                    <?php if (!empty($allergens)): ?>
                        <div class="mb-4">
                            <h5><i class="fas fa-exclamation-triangle me-2 text-warning"></i>Allergenes</h5>
                            <div class="d-flex flex-wrap gap-2">
                                <?php foreach ($allergens as $allergen): ?>
                                    <span class="badge bg-warning text-dark">
                                        <?php if ($allergen['icon']): ?>
                                            <i class="<?php echo htmlspecialchars($allergen['icon']); ?> me-1"></i>
                                        <?php endif; ?>
                                        <?php echo htmlspecialchars($allergen['name']); ?>
                                    </span>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    <?php endif; ?>

                    <!-- Bouton Commander -->
                    <?php if (Auth::check()): ?>
                        <?php if ($menu['stock_quantity'] > 0): ?>
                            <a href="/order/create/<?php echo $menu['id']; ?>" class="btn btn-lg w-100" style="background-color: #FF8F00; color: white;">
                                <i class="fas fa-shopping-cart me-2"></i>Commander ce menu
                            </a>
                        <?php else: ?>
                            <button class="btn btn-lg w-100 btn-secondary" disabled>
                                <i class="fas fa-times-circle me-2"></i>Rupture de stock
                            </button>
                        <?php endif; ?>
                    <?php else: ?>
                        <div class="alert alert-info mb-0">
                            <i class="fas fa-info-circle me-2"></i>
                            <a href="/user/login" class="alert-link">Connectez-vous</a> pour commander ce menu.
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Composition du menu -->
    <div class="card shadow mb-4">
        <div class="card-header" style="background-color: #2E7D32; color: white;">
            <h4 class="mb-0" style="font-family: 'Playfair Display', serif;">
                <i class="fas fa-list-alt me-2"></i>Composition du menu
            </h4>
        </div>
        <div class="card-body">
            <div class="row">
                <?php foreach ($categoryLabels as $catKey => $catLabel): ?>
                    <?php if (!empty($dishesByCategory[$catKey])): ?>
                        <div class="col-md-4 mb-4">
                            <h5 class="border-bottom pb-2" style="color: #2E7D32;">
                                <i class="fas fa-<?php echo $catKey === 'starter' ? 'seedling' : ($catKey === 'main' ? 'drumstick-bite' : ($catKey === 'dessert' ? 'ice-cream' : 'glass-cheers')); ?> me-2"></i>
                                <?php echo $catLabel; ?>
                            </h5>
                            <?php foreach ($dishesByCategory[$catKey] as $dish): ?>
                                <div class="mb-3">
                                    <div class="d-flex align-items-start">
                                        <?php if ($dish['image_url']): ?>
                                            <img src="<?php echo htmlspecialchars($dish['image_url']); ?>"
                                                 class="rounded me-3" style="width: 60px; height: 60px; object-fit: cover;">
                                        <?php endif; ?>
                                        <div>
                                            <h6 class="mb-1"><?php echo htmlspecialchars($dish['name']); ?></h6>
                                            <?php if ($dish['description']): ?>
                                                <p class="small text-muted mb-0"><?php echo htmlspecialchars($dish['description']); ?></p>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

    <!-- Conditions et informations -->
    <div class="card shadow mb-4">
        <div class="card-header">
            <h4 class="mb-0" style="font-family: 'Playfair Display', serif; color: #2E7D32;">
                <i class="fas fa-clipboard-list me-2"></i>Conditions et informations
            </h4>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-4 mb-3">
                    <div class="d-flex align-items-center">
                        <div class="rounded-circle p-3 me-3" style="background-color: #E8F5E9;">
                            <i class="fas fa-clock fa-lg" style="color: #2E7D32;"></i>
                        </div>
                        <div>
                            <h6 class="mb-0">Delai de commande</h6>
                            <p class="text-muted mb-0"><?php echo $menu['min_order_delay_days']; ?> jours minimum</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-3">
                    <div class="d-flex align-items-center">
                        <div class="rounded-circle p-3 me-3" style="background-color: #E3F2FD;">
                            <i class="fas fa-snowflake fa-lg" style="color: #1565C0;"></i>
                        </div>
                        <div>
                            <h6 class="mb-0">Conservation</h6>
                            <p class="text-muted mb-0"><?php echo $menu['storage_instructions'] ? htmlspecialchars($menu['storage_instructions']) : 'Entre 0 et 4 degres C'; ?></p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-3">
                    <div class="d-flex align-items-center">
                        <div class="rounded-circle p-3 me-3" style="background-color: #FFF3E0;">
                            <i class="fas fa-truck fa-lg" style="color: #FF8F00;"></i>
                        </div>
                        <div>
                            <h6 class="mb-0">Livraison</h6>
                            <p class="text-muted mb-0">Gratuite a Bordeaux<br><small>5 EUR + 0,59 EUR/km ailleurs</small></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Avis clients -->
    <div class="card shadow">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h4 class="mb-0" style="font-family: 'Playfair Display', serif; color: #2E7D32;">
                <i class="fas fa-comments me-2"></i>Avis clients
            </h4>
            <?php if ($rating['count'] > 0): ?>
                <span class="badge bg-warning text-dark fs-6">
                    <i class="fas fa-star me-1"></i><?php echo $rating['average']; ?>/5 (<?php echo $rating['count']; ?> avis)
                </span>
            <?php endif; ?>
        </div>
        <div class="card-body">
            <?php if (empty($reviews)): ?>
                <p class="text-muted text-center py-4">
                    <i class="fas fa-comment-slash fa-2x mb-3 d-block"></i>
                    Aucun avis pour le moment. Soyez le premier a donner votre avis !
                </p>
            <?php else: ?>
                <?php foreach ($reviews as $review): ?>
                    <div class="border-bottom pb-3 mb-3">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <div class="d-flex align-items-center mb-2">
                                    <div class="rounded-circle d-flex align-items-center justify-content-center me-2"
                                         style="width: 40px; height: 40px; background-color: #2E7D32; color: white;">
                                        <?php echo strtoupper(substr($review['first_name'], 0, 1)); ?>
                                    </div>
                                    <div>
                                        <strong><?php echo htmlspecialchars($review['first_name'] . ' ' . substr($review['last_name'], 0, 1) . '.'); ?></strong>
                                        <br>
                                        <small class="text-muted"><?php echo date('d/m/Y', strtotime($review['created_at'])); ?></small>
                                    </div>
                                </div>
                            </div>
                            <div>
                                <?php for ($i = 1; $i <= 5; $i++): ?>
                                    <i class="fas fa-star <?php echo $i <= $review['rating'] ? 'text-warning' : 'text-muted'; ?>"></i>
                                <?php endfor; ?>
                            </div>
                        </div>
                        <?php if ($review['comment']): ?>
                            <p class="mb-0 fst-italic">"<?php echo htmlspecialchars($review['comment']); ?>"</p>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</div>

<style>
.bg-purple {
    background-color: #7B1FA2 !important;
}
.thumbnail-img {
    transition: all 0.3s ease;
    border: 2px solid transparent;
}
.thumbnail-img:hover, .thumbnail-img.active {
    border-color: #2E7D32;
}
</style>

<script>
function changeImage(src) {
    document.getElementById('mainImage').src = src;
    document.querySelectorAll('.thumbnail-img').forEach(img => {
        img.classList.remove('active');
        if (img.src === src) {
            img.classList.add('active');
        }
    });
}
</script>
