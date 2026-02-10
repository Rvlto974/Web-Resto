<?php
require_once __DIR__ . '/../../Core/Auth.php';
?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <!-- Message de succes -->
            <div class="text-center mb-5">
                <div class="mb-4">
                    <div class="rounded-circle d-inline-flex align-items-center justify-content-center"
                         style="width: 100px; height: 100px; background-color: #E0F5F1;">
                        <i class="fas fa-check fa-3x" style="color: #5DA99A;"></i>
                    </div>
                </div>
                <h1 style="font-family: 'Playfair Display', serif; color: #5DA99A;">
                    Commande confirmee !
                </h1>
                <p class="lead text-muted">
                    Votre commande a ete enregistree avec succes. Vous recevrez un email de confirmation.
                </p>
            </div>

            <!-- Details de la commande -->
            <div class="card shadow mb-4">
                <div class="card-header" style="background-color: #5DA99A; color: white;">
                    <h4 class="mb-0">
                        <i class="fas fa-receipt me-2"></i>Details de la commande
                    </h4>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-sm-4 text-muted">Numero de commande</div>
                        <div class="col-sm-8">
                            <strong class="fs-5" style="color: #FF8F00;"><?php echo htmlspecialchars($order['order_number']); ?></strong>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-sm-4 text-muted">Statut</div>
                        <div class="col-sm-8">
                            <span class="badge bg-warning text-dark">
                                <i class="fas fa-clock me-1"></i><?php echo htmlspecialchars($statusLabels[$order['status']] ?? $order['status']); ?>
                            </span>
                        </div>
                    </div>
                    <hr>
                    <div class="row mb-3">
                        <div class="col-sm-4 text-muted">Menu</div>
                        <div class="col-sm-8">
                            <div class="d-flex align-items-center">
                                <?php if ($order['menu_image']): ?>
                                    <img src="<?php echo htmlspecialchars($order['menu_image']); ?>"
                                         class="rounded me-3" style="width: 60px; height: 45px; object-fit: cover;">
                                <?php endif; ?>
                                <strong><?php echo htmlspecialchars($order['menu_title']); ?></strong>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-sm-4 text-muted">Nombre de personnes</div>
                        <div class="col-sm-8"><?php echo $order['number_of_people']; ?> personnes</div>
                    </div>
                    <hr>
                    <div class="row mb-3">
                        <div class="col-sm-4 text-muted">Livraison le</div>
                        <div class="col-sm-8">
                            <i class="fas fa-calendar me-2" style="color: #5DA99A;"></i>
                            <?php echo date('d/m/Y', strtotime($order['delivery_date'])); ?>
                            a <?php echo date('H:i', strtotime($order['delivery_time'])); ?>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-sm-4 text-muted">Adresse de livraison</div>
                        <div class="col-sm-8">
                            <i class="fas fa-map-marker-alt me-2" style="color: #5DA99A;"></i>
                            <?php echo htmlspecialchars($order['delivery_address']); ?><br>
                            <?php echo htmlspecialchars($order['delivery_postal_code'] . ' ' . $order['delivery_city']); ?>
                            <?php if ($order['delivery_location']): ?>
                                <br><small class="text-muted"><?php echo htmlspecialchars($order['delivery_location']); ?></small>
                            <?php endif; ?>
                        </div>
                    </div>
                    <hr>
                    <div class="row mb-3">
                        <div class="col-sm-4 text-muted">Sous-total</div>
                        <div class="col-sm-8"><?php echo number_format($order['base_price'], 2, ',', ' '); ?> EUR</div>
                    </div>
                    <?php if ($order['discount_amount'] > 0): ?>
                        <div class="row mb-3">
                            <div class="col-sm-4 text-muted">Reduction</div>
                            <div class="col-sm-8 text-success">-<?php echo number_format($order['discount_amount'], 2, ',', ' '); ?> EUR</div>
                        </div>
                    <?php endif; ?>
                    <?php if ($order['delivery_fee'] > 0): ?>
                        <div class="row mb-3">
                            <div class="col-sm-4 text-muted">Frais de livraison</div>
                            <div class="col-sm-8"><?php echo number_format($order['delivery_fee'], 2, ',', ' '); ?> EUR</div>
                        </div>
                    <?php endif; ?>
                    <div class="row mb-0">
                        <div class="col-sm-4"><strong>Total</strong></div>
                        <div class="col-sm-8">
                            <strong class="fs-4" style="color: #FF8F00;">
                                <?php echo number_format($order['total_price'], 2, ',', ' '); ?> EUR
                            </strong>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Contact -->
            <div class="card shadow mb-4">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3 mb-md-0">
                            <h6><i class="fas fa-user me-2" style="color: #5DA99A;"></i>Contact</h6>
                            <p class="mb-0">
                                <?php echo htmlspecialchars($order['customer_first_name'] . ' ' . $order['customer_last_name']); ?><br>
                                <?php echo htmlspecialchars($order['customer_email']); ?><br>
                                <?php echo htmlspecialchars($order['customer_phone']); ?>
                            </p>
                        </div>
                        <?php if ($order['customer_notes']): ?>
                            <div class="col-md-6">
                                <h6><i class="fas fa-comment me-2" style="color: #5DA99A;"></i>Notes</h6>
                                <p class="mb-0"><?php echo nl2br(htmlspecialchars($order['customer_notes'])); ?></p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div class="d-flex justify-content-center gap-3">
                <a href="/order/history" class="btn btn-outline-primary btn-lg">
                    <i class="fas fa-list me-2"></i>Voir mes commandes
                </a>
                <a href="/menu" class="btn btn-lg" style="background-color: #5DA99A; color: white;">
                    <i class="fas fa-utensils me-2"></i>Continuer mes achats
                </a>
            </div>

            <!-- Information paiement -->
            <div class="alert alert-info mt-4" role="alert">
                <i class="fas fa-info-circle me-2"></i>
                <strong>Paiement a la livraison</strong><br>
                Le paiement s'effectue directement lors de la livraison. Nous acceptons les especes, cheques et cartes bancaires.
            </div>
        </div>
    </div>
</div>
