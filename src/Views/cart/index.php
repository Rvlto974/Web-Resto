<div class="container py-5">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/">Accueil</a></li>
            <li class="breadcrumb-item"><a href="/menu">Nos Menus</a></li>
            <li class="breadcrumb-item active" aria-current="page">Mon Panier</li>
        </ol>
    </nav>

    <h1 class="mb-4" style="font-family: 'Playfair Display', serif; color: #5DA99A;">
        <i class="fas fa-shopping-cart me-2"></i>Mon Panier
    </h1>

    <?php if (empty($cart['items'])): ?>
        <div class="card shadow-sm">
            <div class="card-body text-center py-5">
                <i class="fas fa-shopping-cart fa-4x text-muted mb-4"></i>
                <h4 class="text-muted">Votre panier est vide</h4>
                <p class="text-muted mb-4">Parcourez nos menus et ajoutez vos favoris !</p>
                <a href="/menu" class="btn btn-lg" style="background-color: #5DA99A; color: white;">
                    <i class="fas fa-utensils me-2"></i>Decouvrir nos menus
                </a>
            </div>
        </div>
    <?php else: ?>
        <div class="row">
            <!-- Liste des articles -->
            <div class="col-lg-8 mb-4">
                <div class="card shadow-sm">
                    <div class="card-header" style="background-color: #5DA99A; color: white;">
                        <h5 class="mb-0">
                            <i class="fas fa-list me-2"></i>
                            <?php echo $cart['items_count']; ?> article(s)
                        </h5>
                    </div>
                    <div class="card-body p-0">
                        <?php foreach ($cart['items'] as $item): ?>
                            <div class="cart-item p-3 border-bottom">
                                <div class="row align-items-center">
                                    <!-- Image -->
                                    <div class="col-md-2 mb-3 mb-md-0">
                                        <?php if ($item['menu']['main_image_url']): ?>
                                            <img src="<?php echo htmlspecialchars($item['menu']['main_image_url']); ?>"
                                                 class="img-fluid rounded" alt="<?php echo htmlspecialchars($item['menu']['title']); ?>"
                                                 style="height: 80px; width: 100%; object-fit: cover;">
                                        <?php else: ?>
                                            <div class="d-flex align-items-center justify-content-center rounded"
                                                 style="height: 80px; background: linear-gradient(135deg, #5DA99A, #7DBFB2);">
                                                <i class="fas fa-utensils fa-2x text-white"></i>
                                            </div>
                                        <?php endif; ?>
                                    </div>

                                    <!-- Infos -->
                                    <div class="col-md-4 mb-3 mb-md-0">
                                        <h6 class="mb-1">
                                            <a href="/menu/show/<?php echo $item['menu_id']; ?>" class="text-decoration-none" style="color: #5DA99A;">
                                                <?php echo htmlspecialchars($item['menu']['title']); ?>
                                            </a>
                                        </h6>
                                        <small class="text-muted">
                                            <i class="fas fa-users me-1"></i>
                                            <?php echo $item['number_of_people']; ?> personne(s)
                                        </small>
                                        <?php if ($item['discount_applied']): ?>
                                            <br><span class="badge bg-success">-10% applique</span>
                                        <?php endif; ?>
                                    </div>

                                    <!-- Quantite -->
                                    <div class="col-md-2 mb-3 mb-md-0">
                                        <form method="POST" action="/cart/update" class="d-flex align-items-center">
                                            <?php echo $csrf; ?>
                                            <input type="hidden" name="index" value="<?php echo $item['index']; ?>">
                                            <div class="input-group input-group-sm" style="width: 100px;">
                                                <button type="button" class="btn btn-outline-secondary btn-qty-minus">-</button>
                                                <input type="number" name="quantity" class="form-control text-center qty-input"
                                                       value="<?php echo $item['quantity']; ?>" min="1" max="10">
                                                <button type="button" class="btn btn-outline-secondary btn-qty-plus">+</button>
                                            </div>
                                        </form>
                                    </div>

                                    <!-- Prix unitaire -->
                                    <div class="col-md-2 mb-3 mb-md-0 text-end">
                                        <small class="text-muted d-block"><?php echo number_format($item['unit_price'], 2, ',', ' '); ?> EUR/u</small>
                                    </div>

                                    <!-- Sous-total + Supprimer -->
                                    <div class="col-md-2 text-end">
                                        <strong style="color: #FF8F00;"><?php echo number_format($item['subtotal'], 2, ',', ' '); ?> EUR</strong>
                                        <form method="POST" action="/cart/remove" class="d-inline">
                                            <?php echo $csrf; ?>
                                            <input type="hidden" name="index" value="<?php echo $item['index']; ?>">
                                            <button type="submit" class="btn btn-sm btn-link text-danger p-0 ms-2"
                                                    onclick="return confirm('Supprimer cet article ?');">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    <div class="card-footer bg-light">
                        <div class="d-flex justify-content-between align-items-center">
                            <form method="POST" action="/cart/clear">
                                <?php echo $csrf; ?>
                                <button type="submit" class="btn btn-outline-danger btn-sm"
                                        onclick="return confirm('Vider tout le panier ?');">
                                    <i class="fas fa-trash me-1"></i>Vider le panier
                                </button>
                            </form>
                            <a href="/menu" class="btn btn-outline-secondary btn-sm">
                                <i class="fas fa-plus me-1"></i>Ajouter d'autres menus
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Resume -->
            <div class="col-lg-4">
                <div class="card shadow-sm sticky-top" style="top: 20px;">
                    <div class="card-header" style="background-color: #5DA99A; color: white;">
                        <h5 class="mb-0"><i class="fas fa-receipt me-2"></i>Resume</h5>
                    </div>
                    <div class="card-body">
                        <div class="d-flex justify-content-between mb-2">
                            <span>Sous-total</span>
                            <span><?php echo number_format($cart['subtotal'], 2, ',', ' '); ?> EUR</span>
                        </div>

                        <?php if ($cart['total_discount'] > 0): ?>
                            <div class="d-flex justify-content-between mb-2 text-success">
                                <span>Remises</span>
                                <span>-<?php echo number_format($cart['total_discount'], 2, ',', ' '); ?> EUR</span>
                            </div>
                        <?php endif; ?>

                        <div class="d-flex justify-content-between mb-2">
                            <span>Livraison</span>
                            <span>
                                <?php if ($cart['delivery_fee'] > 0): ?>
                                    <?php echo number_format($cart['delivery_fee'], 2, ',', ' '); ?> EUR
                                <?php else: ?>
                                    <span class="text-success">Calculee au checkout</span>
                                <?php endif; ?>
                            </span>
                        </div>

                        <hr>

                        <div class="d-flex justify-content-between mb-3">
                            <strong class="fs-5">Total</strong>
                            <strong class="fs-5" style="color: #FF8F00;">
                                <?php echo number_format($cart['subtotal'], 2, ',', ' '); ?> EUR
                            </strong>
                        </div>

                        <small class="text-muted d-block mb-3">
                            <i class="fas fa-info-circle me-1"></i>
                            Les frais de livraison seront calcules selon votre adresse.
                            Livraison gratuite a Bordeaux (33000, 33100, 33200, 33300, 33800).
                        </small>

                        <div class="d-grid">
                            <a href="/cart/checkout" class="btn btn-lg" style="background-color: #FF8F00; color: white;">
                                <i class="fas fa-credit-card me-2"></i>Commander
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>

<style>
.cart-item:last-child {
    border-bottom: none !important;
}
.cart-item:hover {
    background-color: #f8f9fa;
}
.qty-input {
    max-width: 50px;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Boutons +/-
    document.querySelectorAll('.btn-qty-minus').forEach(btn => {
        btn.addEventListener('click', function() {
            const input = this.parentElement.querySelector('.qty-input');
            if (parseInt(input.value) > 1) {
                input.value = parseInt(input.value) - 1;
                this.closest('form').submit();
            }
        });
    });

    document.querySelectorAll('.btn-qty-plus').forEach(btn => {
        btn.addEventListener('click', function() {
            const input = this.parentElement.querySelector('.qty-input');
            if (parseInt(input.value) < 10) {
                input.value = parseInt(input.value) + 1;
                this.closest('form').submit();
            }
        });
    });

    // Changement direct de quantite
    document.querySelectorAll('.qty-input').forEach(input => {
        input.addEventListener('change', function() {
            this.closest('form').submit();
        });
    });
});
</script>
