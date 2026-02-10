<?php
require_once __DIR__ . '/../../Core/Auth.php';
require_once __DIR__ . '/../../Core/Csrf.php';
?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-6">
            <!-- Breadcrumb -->
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="/">Accueil</a></li>
                    <li class="breadcrumb-item"><a href="/order/history">Mes commandes</a></li>
                    <li class="breadcrumb-item"><a href="/order/show/<?php echo $order['id']; ?>"><?php echo htmlspecialchars($order['order_number']); ?></a></li>
                    <li class="breadcrumb-item active" aria-current="page">Avis</li>
                </ol>
            </nav>

            <div class="card shadow">
                <div class="card-header" style="background-color: #5DA99A; color: white;">
                    <h4 class="mb-0" style="font-family: 'Playfair Display', serif;">
                        <i class="fas fa-star me-2"></i>Laisser un avis
                    </h4>
                </div>
                <div class="card-body">
                    <!-- Recap commande -->
                    <div class="d-flex mb-4 p-3 rounded" style="background-color: #F5F5F5;">
                        <?php if ($order['menu_image']): ?>
                            <img src="<?php echo htmlspecialchars($order['menu_image']); ?>"
                                 class="rounded me-3" style="width: 80px; height: 60px; object-fit: cover;">
                        <?php endif; ?>
                        <div>
                            <h6 class="mb-0"><?php echo htmlspecialchars($order['menu_title']); ?></h6>
                            <small class="text-muted">
                                Commande <?php echo htmlspecialchars($order['order_number']); ?>
                                <br>Livree le <?php echo date('d/m/Y', strtotime($order['delivery_date'])); ?>
                            </small>
                        </div>
                    </div>

                    <form action="/review/store" method="POST" id="reviewForm">
                        <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($csrfToken); ?>">
                        <input type="hidden" name="order_id" value="<?php echo $order['id']; ?>">

                        <!-- Note -->
                        <div class="mb-4 text-center">
                            <label class="form-label fw-bold">Votre note *</label>
                            <div class="star-rating" id="starRating">
                                <?php for ($i = 1; $i <= 5; $i++): ?>
                                    <i class="fas fa-star fa-2x star" data-rating="<?php echo $i; ?>"
                                       style="cursor: pointer; color: #ddd; transition: color 0.2s;"></i>
                                <?php endfor; ?>
                            </div>
                            <input type="hidden" name="rating" id="ratingInput" value="" required>
                            <div id="ratingText" class="text-muted mt-2"></div>
                        </div>

                        <!-- Commentaire -->
                        <div class="mb-4">
                            <label for="comment" class="form-label fw-bold">Votre commentaire (optionnel)</label>
                            <textarea class="form-control" id="comment" name="comment" rows="4"
                                      placeholder="Partagez votre experience avec ce menu..."
                                      maxlength="1000"></textarea>
                            <div class="form-text text-end">
                                <span id="charCount">0</span>/1000 caracteres
                            </div>
                        </div>

                        <!-- Boutons -->
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-lg" style="background-color: #FF8F00; color: white;" id="submitBtn" disabled>
                                <i class="fas fa-paper-plane me-2"></i>Envoyer mon avis
                            </button>
                            <a href="/order/show/<?php echo $order['id']; ?>" class="btn btn-outline-secondary">
                                <i class="fas fa-arrow-left me-2"></i>Retour
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            <div class="alert alert-info mt-4" role="alert">
                <i class="fas fa-info-circle me-2"></i>
                <strong>Information</strong><br>
                Votre avis sera publie apres validation par notre equipe. Merci de rester courtois et constructif.
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const stars = document.querySelectorAll('.star');
    const ratingInput = document.getElementById('ratingInput');
    const ratingText = document.getElementById('ratingText');
    const submitBtn = document.getElementById('submitBtn');
    const commentArea = document.getElementById('comment');
    const charCount = document.getElementById('charCount');

    const ratingTexts = {
        1: 'Tres decu',
        2: 'Decu',
        3: 'Correct',
        4: 'Satisfait',
        5: 'Excellent !'
    };

    // Gestion des etoiles
    function highlightStars(rating) {
        stars.forEach((star, index) => {
            if (index < rating) {
                star.style.color = '#FFD700';
            } else {
                star.style.color = '#ddd';
            }
        });
    }

    stars.forEach(star => {
        star.addEventListener('mouseenter', function() {
            const rating = parseInt(this.dataset.rating);
            highlightStars(rating);
        });

        star.addEventListener('click', function() {
            const rating = parseInt(this.dataset.rating);
            ratingInput.value = rating;
            highlightStars(rating);
            ratingText.textContent = ratingTexts[rating];
            submitBtn.disabled = false;
        });
    });

    document.getElementById('starRating').addEventListener('mouseleave', function() {
        const currentRating = parseInt(ratingInput.value) || 0;
        highlightStars(currentRating);
    });

    // Compteur de caracteres
    commentArea.addEventListener('input', function() {
        charCount.textContent = this.value.length;
    });
});
</script>

<style>
.star-rating .star:hover ~ .star {
    color: #ddd !important;
}
</style>
