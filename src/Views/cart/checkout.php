<div class="container py-5">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/">Accueil</a></li>
            <li class="breadcrumb-item"><a href="/cart">Mon Panier</a></li>
            <li class="breadcrumb-item active" aria-current="page">Finaliser la commande</li>
        </ol>
    </nav>

    <h1 class="mb-4" style="font-family: 'Playfair Display', serif; color: #5DA99A;">
        <i class="fas fa-credit-card me-2"></i>Finaliser la commande
    </h1>

    <form method="POST" action="/order/storeFromCart" id="checkoutForm">
        <?php echo $csrf; ?>

        <div class="row">
            <!-- Formulaire -->
            <div class="col-lg-8 mb-4">
                <!-- Informations de contact -->
                <div class="card shadow-sm mb-4">
                    <div class="card-header" style="background-color: #5DA99A; color: white;">
                        <h5 class="mb-0"><i class="fas fa-user me-2"></i>Informations de contact</h5>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="customer_first_name" class="form-label">Prenom *</label>
                                <input type="text" class="form-control" id="customer_first_name" name="customer_first_name"
                                       value="<?php echo htmlspecialchars($user['first_name'] ?? ''); ?>" required>
                            </div>
                            <div class="col-md-6">
                                <label for="customer_last_name" class="form-label">Nom *</label>
                                <input type="text" class="form-control" id="customer_last_name" name="customer_last_name"
                                       value="<?php echo htmlspecialchars($user['last_name'] ?? ''); ?>" required>
                            </div>
                            <div class="col-md-6">
                                <label for="customer_email" class="form-label">Email *</label>
                                <input type="email" class="form-control" id="customer_email" name="customer_email"
                                       value="<?php echo htmlspecialchars($user['email'] ?? ''); ?>" required>
                            </div>
                            <div class="col-md-6">
                                <label for="customer_phone" class="form-label">Telephone *</label>
                                <input type="tel" class="form-control" id="customer_phone" name="customer_phone"
                                       value="<?php echo htmlspecialchars($user['phone'] ?? ''); ?>" required
                                       pattern="[0-9]{10}" placeholder="0612345678">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Adresse de livraison -->
                <div class="card shadow-sm mb-4">
                    <div class="card-header" style="background-color: #5DA99A; color: white;">
                        <h5 class="mb-0"><i class="fas fa-map-marker-alt me-2"></i>Adresse de livraison</h5>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-12">
                                <label for="delivery_address" class="form-label">Adresse *</label>
                                <input type="text" class="form-control" id="delivery_address" name="delivery_address"
                                       value="<?php echo htmlspecialchars($user['address'] ?? ''); ?>" required
                                       placeholder="Numero et nom de rue">
                            </div>
                            <div class="col-md-6">
                                <label for="delivery_city" class="form-label">Ville *</label>
                                <input type="text" class="form-control" id="delivery_city" name="delivery_city"
                                       value="<?php echo htmlspecialchars($user['city'] ?? ''); ?>" required>
                            </div>
                            <div class="col-md-6">
                                <label for="delivery_postal_code" class="form-label">Code postal *</label>
                                <input type="text" class="form-control" id="delivery_postal_code" name="delivery_postal_code"
                                       value="<?php echo htmlspecialchars($user['postal_code'] ?? ''); ?>" required
                                       pattern="[0-9]{5}" placeholder="33000">
                                <small class="text-muted">Livraison gratuite a Bordeaux</small>
                            </div>
                            <div class="col-12">
                                <label for="delivery_location" class="form-label">Complement (optionnel)</label>
                                <input type="text" class="form-control" id="delivery_location" name="delivery_location"
                                       placeholder="Batiment, etage, digicode...">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Date et heure de livraison -->
                <div class="card shadow-sm mb-4">
                    <div class="card-header" style="background-color: #5DA99A; color: white;">
                        <h5 class="mb-0"><i class="fas fa-calendar-alt me-2"></i>Date et heure de livraison</h5>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="delivery_date" class="form-label">Date de livraison *</label>
                                <input type="date" class="form-control" id="delivery_date" name="delivery_date"
                                       min="<?php echo $minDeliveryDate; ?>" required>
                                <small class="text-muted">Minimum <?php echo date('d/m/Y', strtotime($minDeliveryDate)); ?></small>
                            </div>
                            <div class="col-md-6">
                                <label for="delivery_time" class="form-label">Heure de livraison *</label>
                                <select class="form-select" id="delivery_time" name="delivery_time" required>
                                    <option value="">Choisir un creneau</option>
                                    <option value="09:00">09:00 - 10:00</option>
                                    <option value="10:00">10:00 - 11:00</option>
                                    <option value="11:00">11:00 - 12:00</option>
                                    <option value="12:00">12:00 - 13:00</option>
                                    <option value="14:00">14:00 - 15:00</option>
                                    <option value="15:00">15:00 - 16:00</option>
                                    <option value="16:00">16:00 - 17:00</option>
                                    <option value="17:00">17:00 - 18:00</option>
                                    <option value="18:00">18:00 - 19:00</option>
                                    <option value="19:00">19:00 - 20:00</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Notes -->
                <div class="card shadow-sm mb-4">
                    <div class="card-header" style="background-color: #5DA99A; color: white;">
                        <h5 class="mb-0"><i class="fas fa-sticky-note me-2"></i>Notes (optionnel)</h5>
                    </div>
                    <div class="card-body">
                        <textarea class="form-control" id="customer_notes" name="customer_notes" rows="3"
                                  placeholder="Instructions speciales, allergies, preferences..."></textarea>
                    </div>
                </div>

                <!-- Mode de paiement -->
                <div class="card shadow-sm mb-4">
                    <div class="card-header" style="background-color: #5DA99A; color: white;">
                        <h5 class="mb-0"><i class="fas fa-wallet me-2"></i>Mode de paiement</h5>
                    </div>
                    <div class="card-body">
                        <div class="form-check mb-3">
                            <input class="form-check-input" type="radio" name="payment_method" id="payment_cash"
                                   value="cash" checked>
                            <label class="form-check-label" for="payment_cash">
                                <i class="fas fa-money-bill-wave me-2 text-success"></i>
                                <strong>Paiement a la livraison</strong>
                                <br><small class="text-muted">Payez en especes ou par cheque a la reception</small>
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="payment_method" id="payment_stripe"
                                   value="stripe">
                            <label class="form-check-label" for="payment_stripe">
                                <i class="fas fa-credit-card me-2 text-primary"></i>
                                <strong>Payer par carte bancaire</strong>
                                <br><small class="text-muted">Paiement securise via Stripe</small>
                            </label>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Resume commande -->
            <div class="col-lg-4">
                <div class="card shadow-sm sticky-top" style="top: 20px;">
                    <div class="card-header" style="background-color: #5DA99A; color: white;">
                        <h5 class="mb-0"><i class="fas fa-receipt me-2"></i>Votre commande</h5>
                    </div>
                    <div class="card-body">
                        <!-- Articles -->
                        <?php foreach ($cart['items'] as $item): ?>
                            <div class="d-flex justify-content-between mb-2">
                                <div>
                                    <small>
                                        <?php echo htmlspecialchars($item['menu']['title']); ?>
                                        <span class="text-muted">x<?php echo $item['quantity']; ?></span>
                                    </small>
                                    <br>
                                    <small class="text-muted"><?php echo $item['number_of_people']; ?> pers.</small>
                                </div>
                                <small><?php echo number_format($item['subtotal'], 2, ',', ' '); ?> EUR</small>
                            </div>
                        <?php endforeach; ?>

                        <hr>

                        <div class="d-flex justify-content-between mb-2">
                            <span>Sous-total</span>
                            <span><?php echo number_format($cart['subtotal'], 2, ',', ' '); ?> EUR</span>
                        </div>

                        <div class="d-flex justify-content-between mb-2" id="deliveryFeeRow">
                            <span>Livraison</span>
                            <span id="deliveryFeeValue">
                                <?php if ($cart['is_in_bordeaux']): ?>
                                    <span class="text-success">Gratuite</span>
                                <?php else: ?>
                                    A calculer
                                <?php endif; ?>
                            </span>
                        </div>

                        <hr>

                        <div class="d-flex justify-content-between mb-3">
                            <strong class="fs-5">Total</strong>
                            <strong class="fs-5" style="color: #FF8F00;" id="totalValue">
                                <?php echo number_format($cart['total'], 2, ',', ' '); ?> EUR
                            </strong>
                        </div>

                        <input type="hidden" name="distance_km" id="distance_km" value="0">

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-lg" style="background-color: #FF8F00; color: white;">
                                <i class="fas fa-check me-2"></i>Confirmer la commande
                            </button>
                            <a href="/cart" class="btn btn-outline-secondary">
                                <i class="fas fa-arrow-left me-2"></i>Modifier le panier
                            </a>
                        </div>

                        <small class="text-muted d-block mt-3 text-center">
                            <i class="fas fa-lock me-1"></i>
                            Paiement 100% securise
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const postalCodeInput = document.getElementById('delivery_postal_code');
    const deliveryFeeValue = document.getElementById('deliveryFeeValue');
    const totalValue = document.getElementById('totalValue');
    const distanceInput = document.getElementById('distance_km');
    const subtotal = <?php echo $cart['subtotal']; ?>;

    const bordeauxCodes = ['33000', '33100', '33200', '33300', '33800'];

    function updateDeliveryFee() {
        const postalCode = postalCodeInput.value.trim();

        if (postalCode.length === 5) {
            if (bordeauxCodes.includes(postalCode)) {
                deliveryFeeValue.innerHTML = '<span class="text-success">Gratuite</span>';
                totalValue.textContent = subtotal.toFixed(2).replace('.', ',') + ' EUR';
                distanceInput.value = 0;
            } else {
                // Estimation simple : 10km par defaut hors Bordeaux
                const estimatedDistance = 15;
                const deliveryFee = 5.00 + (0.59 * estimatedDistance);
                deliveryFeeValue.textContent = deliveryFee.toFixed(2).replace('.', ',') + ' EUR';
                totalValue.textContent = (subtotal + deliveryFee).toFixed(2).replace('.', ',') + ' EUR';
                distanceInput.value = estimatedDistance;
            }
        }
    }

    postalCodeInput.addEventListener('input', updateDeliveryFee);
    postalCodeInput.addEventListener('change', updateDeliveryFee);

    // Initial check
    if (postalCodeInput.value) {
        updateDeliveryFee();
    }

    // Validation formulaire
    document.getElementById('checkoutForm').addEventListener('submit', function(e) {
        const phone = document.getElementById('customer_phone').value;
        if (!/^[0-9]{10}$/.test(phone)) {
            e.preventDefault();
            alert('Veuillez entrer un numero de telephone valide (10 chiffres).');
            return false;
        }

        const postalCode = postalCodeInput.value;
        if (!/^[0-9]{5}$/.test(postalCode)) {
            e.preventDefault();
            alert('Veuillez entrer un code postal valide (5 chiffres).');
            return false;
        }
    });
});
</script>
