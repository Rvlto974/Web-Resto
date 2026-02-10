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
            <li class="breadcrumb-item"><a href="/menu/show/<?php echo $menu['id']; ?>"><?php echo htmlspecialchars($menu['title']); ?></a></li>
            <li class="breadcrumb-item active" aria-current="page">Commander</li>
        </ol>
    </nav>

    <div class="row">
        <!-- Formulaire de commande -->
        <div class="col-lg-8 mb-4">
            <div class="card shadow">
                <div class="card-header" style="background-color: #5DA99A; color: white;">
                    <h4 class="mb-0" style="font-family: 'Playfair Display', serif;">
                        <i class="fas fa-shopping-cart me-2"></i>Formulaire de commande
                    </h4>
                </div>
                <div class="card-body">
                    <form action="/order/store" method="POST" id="orderForm">
                        <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($csrfToken); ?>">
                        <input type="hidden" name="menu_id" value="<?php echo $menu['id']; ?>">
                        <input type="hidden" name="distance_km" id="distance_km" value="0">

                        <!-- Nombre de personnes -->
                        <div class="mb-4">
                            <h5 class="border-bottom pb-2" style="color: #5DA99A;">
                                <i class="fas fa-users me-2"></i>Nombre de personnes
                            </h5>
                            <div class="row">
                                <div class="col-md-6">
                                    <label for="number_of_people" class="form-label">Nombre de convives *</label>
                                    <input type="number" class="form-control form-control-lg" id="number_of_people"
                                           name="number_of_people" min="<?php echo $minPeople; ?>"
                                           value="<?php echo $minPeople; ?>" required
                                           aria-describedby="peopleHelp">
                                    <div id="peopleHelp" class="form-text">
                                        Minimum <?php echo $minPeople; ?> personnes pour ce menu.
                                        <?php if ($menu['discount_threshold'] > 0): ?>
                                            <br><span class="text-success"><i class="fas fa-percent"></i> -10% a partir de +<?php echo $menu['discount_threshold']; ?> personnes supplementaires</span>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Informations de contact -->
                        <div class="mb-4">
                            <h5 class="border-bottom pb-2" style="color: #5DA99A;">
                                <i class="fas fa-user me-2"></i>Vos informations
                            </h5>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label for="customer_first_name" class="form-label">Prenom *</label>
                                    <input type="text" class="form-control" id="customer_first_name"
                                           name="customer_first_name" required
                                           value="<?php echo htmlspecialchars($user['first_name'] ?? ''); ?>">
                                </div>
                                <div class="col-md-6">
                                    <label for="customer_last_name" class="form-label">Nom *</label>
                                    <input type="text" class="form-control" id="customer_last_name"
                                           name="customer_last_name" required
                                           value="<?php echo htmlspecialchars($user['last_name'] ?? ''); ?>">
                                </div>
                                <div class="col-md-6">
                                    <label for="customer_email" class="form-label">Email *</label>
                                    <input type="email" class="form-control" id="customer_email"
                                           name="customer_email" required
                                           value="<?php echo htmlspecialchars($user['email'] ?? ''); ?>">
                                </div>
                                <div class="col-md-6">
                                    <label for="customer_phone" class="form-label">Telephone *</label>
                                    <input type="tel" class="form-control" id="customer_phone"
                                           name="customer_phone" required
                                           value="<?php echo htmlspecialchars($user['phone'] ?? ''); ?>"
                                           pattern="[0-9]{10}" placeholder="0612345678">
                                </div>
                            </div>
                        </div>

                        <!-- Adresse de livraison -->
                        <div class="mb-4">
                            <h5 class="border-bottom pb-2" style="color: #5DA99A;">
                                <i class="fas fa-truck me-2"></i>Adresse de livraison
                            </h5>
                            <div class="row g-3">
                                <div class="col-12">
                                    <label for="delivery_address" class="form-label">Adresse *</label>
                                    <input type="text" class="form-control" id="delivery_address"
                                           name="delivery_address" required
                                           value="<?php echo htmlspecialchars($user['address'] ?? ''); ?>"
                                           placeholder="Numero et nom de rue">
                                </div>
                                <div class="col-md-8">
                                    <label for="delivery_city" class="form-label">Ville *</label>
                                    <input type="text" class="form-control" id="delivery_city"
                                           name="delivery_city" required
                                           value="<?php echo htmlspecialchars($user['city'] ?? ''); ?>">
                                </div>
                                <div class="col-md-4">
                                    <label for="delivery_postal_code" class="form-label">Code postal *</label>
                                    <input type="text" class="form-control" id="delivery_postal_code"
                                           name="delivery_postal_code" required
                                           value="<?php echo htmlspecialchars($user['postal_code'] ?? ''); ?>"
                                           pattern="[0-9]{5}" maxlength="5" placeholder="33000">
                                </div>
                                <div class="col-12">
                                    <label for="delivery_location" class="form-label">Lieu de l'evenement (optionnel)</label>
                                    <input type="text" class="form-control" id="delivery_location"
                                           name="delivery_location"
                                           placeholder="Ex: Salle des fetes, Chateau...">
                                </div>
                            </div>
                        </div>

                        <!-- Date et heure de livraison -->
                        <div class="mb-4">
                            <h5 class="border-bottom pb-2" style="color: #5DA99A;">
                                <i class="fas fa-calendar-alt me-2"></i>Date et heure de livraison
                            </h5>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label for="delivery_date" class="form-label">Date de livraison *</label>
                                    <input type="date" class="form-control" id="delivery_date"
                                           name="delivery_date" required
                                           min="<?php echo $minDate; ?>">
                                    <div class="form-text">
                                        Delai minimum : <?php echo $menu['min_order_delay_days']; ?> jours
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label for="delivery_time" class="form-label">Heure de livraison *</label>
                                    <input type="time" class="form-control" id="delivery_time"
                                           name="delivery_time" required
                                           min="08:00" max="20:00">
                                    <div class="form-text">
                                        Entre 08h00 et 20h00
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Notes -->
                        <div class="mb-4">
                            <h5 class="border-bottom pb-2" style="color: #5DA99A;">
                                <i class="fas fa-comment me-2"></i>Instructions supplementaires
                            </h5>
                            <textarea class="form-control" id="customer_notes" name="customer_notes"
                                      rows="3" placeholder="Allergies specifiques, instructions de livraison..."></textarea>
                        </div>

                        <!-- Bouton de soumission -->
                        <div class="d-grid">
                            <button type="submit" class="btn btn-lg" style="background-color: #FF8F00; color: white;">
                                <i class="fas fa-check me-2"></i>Confirmer la commande
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Recapitulatif -->
        <div class="col-lg-4">
            <div class="card shadow sticky-top" style="top: 20px;">
                <div class="card-header" style="background-color: #FF8F00; color: white;">
                    <h5 class="mb-0">
                        <i class="fas fa-receipt me-2"></i>Recapitulatif
                    </h5>
                </div>
                <div class="card-body">
                    <!-- Menu -->
                    <div class="d-flex mb-3">
                        <?php if ($menu['main_image_url']): ?>
                            <img src="<?php echo htmlspecialchars($menu['main_image_url']); ?>"
                                 class="rounded me-3" style="width: 80px; height: 60px; object-fit: cover;"
                                 alt="<?php echo htmlspecialchars($menu['title']); ?>">
                        <?php else: ?>
                            <div class="rounded me-3 d-flex align-items-center justify-content-center"
                                 style="width: 80px; height: 60px; background-color: #5DA99A;">
                                <i class="fas fa-utensils text-white"></i>
                            </div>
                        <?php endif; ?>
                        <div>
                            <h6 class="mb-0"><?php echo htmlspecialchars($menu['title']); ?></h6>
                            <small class="text-muted"><?php echo $minPeople; ?> pers. minimum</small>
                        </div>
                    </div>

                    <hr>

                    <!-- Details prix -->
                    <div id="priceDetails">
                        <div class="d-flex justify-content-between mb-2">
                            <span>Prix de base</span>
                            <span id="basePrice"><?php echo number_format($menu['base_price'], 2, ',', ' '); ?> EUR</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2" id="extraPeopleRow" style="display: none !important;">
                            <span>Personnes supplementaires (<span id="extraPeopleCount">0</span>)</span>
                            <span id="extraCost">0,00 EUR</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2 text-success" id="discountRow" style="display: none !important;">
                            <span><i class="fas fa-percent me-1"></i>Reduction 10%</span>
                            <span id="discountAmount">-0,00 EUR</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2" id="deliveryRow">
                            <span><i class="fas fa-truck me-1"></i>Livraison</span>
                            <span id="deliveryFee" class="text-success">Gratuit</span>
                        </div>
                    </div>

                    <hr>

                    <div class="d-flex justify-content-between align-items-center">
                        <strong class="fs-5">Total</strong>
                        <strong class="fs-4" style="color: #FF8F00;" id="totalPrice">
                            <?php echo number_format($menu['base_price'], 2, ',', ' '); ?> EUR
                        </strong>
                    </div>

                    <!-- Informations -->
                    <div class="mt-4">
                        <div class="alert alert-info mb-2" role="alert">
                            <i class="fas fa-info-circle me-2"></i>
                            <small>Livraison gratuite a Bordeaux (33000, 33100, 33200, 33300, 33800)</small>
                        </div>
                        <div class="alert alert-warning mb-0" role="alert">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            <small>Le paiement s'effectue a la livraison</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const menuId = <?php echo $menu['id']; ?>;
    const minPeople = <?php echo $minPeople; ?>;
    const basePrice = <?php echo $menu['base_price']; ?>;
    const pricePerExtra = <?php echo $menu['price_per_extra_person'] ?? 0; ?>;
    const discountThreshold = <?php echo $menu['discount_threshold'] ?? 5; ?>;
    const bordeauxCodes = ['33000', '33100', '33200', '33300', '33800'];

    const numberOfPeopleInput = document.getElementById('number_of_people');
    const postalCodeInput = document.getElementById('delivery_postal_code');

    function updatePrice() {
        const numberOfPeople = parseInt(numberOfPeopleInput.value) || minPeople;
        const postalCode = postalCodeInput.value.trim();

        const extraPeople = Math.max(0, numberOfPeople - minPeople);
        const extraCost = extraPeople * pricePerExtra;
        let subtotal = basePrice + extraCost;

        // Reduction 10% si +5 personnes supplementaires
        let discount = 0;
        if (extraPeople >= discountThreshold) {
            discount = subtotal * 0.10;
            subtotal -= discount;
        }

        // Frais de livraison
        let deliveryFee = 0;
        const isInBordeaux = bordeauxCodes.includes(postalCode);
        if (!isInBordeaux && postalCode.length === 5) {
            deliveryFee = 5; // Frais de base, distance non calculee en JS
        }

        const total = subtotal + deliveryFee;

        // Mise a jour de l'affichage
        document.getElementById('basePrice').textContent = basePrice.toFixed(2).replace('.', ',') + ' EUR';

        const extraRow = document.getElementById('extraPeopleRow');
        if (extraPeople > 0) {
            extraRow.style.display = 'flex';
            document.getElementById('extraPeopleCount').textContent = extraPeople;
            document.getElementById('extraCost').textContent = extraCost.toFixed(2).replace('.', ',') + ' EUR';
        } else {
            extraRow.style.display = 'none';
        }

        const discountRow = document.getElementById('discountRow');
        if (discount > 0) {
            discountRow.style.display = 'flex';
            document.getElementById('discountAmount').textContent = '-' + discount.toFixed(2).replace('.', ',') + ' EUR';
        } else {
            discountRow.style.display = 'none';
        }

        const deliveryFeeEl = document.getElementById('deliveryFee');
        if (deliveryFee > 0) {
            deliveryFeeEl.textContent = deliveryFee.toFixed(2).replace('.', ',') + ' EUR';
            deliveryFeeEl.classList.remove('text-success');
        } else {
            deliveryFeeEl.textContent = 'Gratuit';
            deliveryFeeEl.classList.add('text-success');
        }

        document.getElementById('totalPrice').textContent = total.toFixed(2).replace('.', ',') + ' EUR';
    }

    numberOfPeopleInput.addEventListener('input', updatePrice);
    postalCodeInput.addEventListener('input', updatePrice);

    // Calcul initial
    updatePrice();
});
</script>
