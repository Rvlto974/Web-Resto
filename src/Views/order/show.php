<?php
require_once __DIR__ . '/../../Core/Auth.php';
require_once __DIR__ . '/../../Core/Csrf.php';
?>

<div class="container py-5">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/">Accueil</a></li>
            <li class="breadcrumb-item"><a href="/order/history">Mes commandes</a></li>
            <li class="breadcrumb-item active" aria-current="page"><?php echo htmlspecialchars($order['order_number']); ?></li>
        </ol>
    </nav>

    <div class="row">
        <!-- Details de la commande -->
        <div class="col-lg-8 mb-4">
            <div class="card shadow">
                <div class="card-header d-flex justify-content-between align-items-center" style="background-color: #2E7D32; color: white;">
                    <h4 class="mb-0">
                        <i class="fas fa-receipt me-2"></i>Commande <?php echo htmlspecialchars($order['order_number']); ?>
                    </h4>
                    <?php
                    $statusColor = $statusColors[$order['status']] ?? 'secondary';
                    $statusLabel = $statusLabels[$order['status']] ?? $order['status'];
                    ?>
                    <span class="badge bg-<?php echo $statusColor; ?> fs-6">
                        <?php echo htmlspecialchars($statusLabel); ?>
                    </span>
                </div>
                <div class="card-body">
                    <!-- Menu commande -->
                    <div class="d-flex mb-4 p-3 rounded" style="background-color: #F5F5F5;">
                        <?php if ($order['menu_image']): ?>
                            <img src="<?php echo htmlspecialchars($order['menu_image']); ?>"
                                 class="rounded me-3" style="width: 100px; height: 75px; object-fit: cover;"
                                 alt="<?php echo htmlspecialchars($order['menu_title']); ?>">
                        <?php else: ?>
                            <div class="rounded me-3 d-flex align-items-center justify-content-center"
                                 style="width: 100px; height: 75px; background-color: #2E7D32;">
                                <i class="fas fa-utensils fa-2x text-white"></i>
                            </div>
                        <?php endif; ?>
                        <div>
                            <h5 class="mb-1"><?php echo htmlspecialchars($order['menu_title']); ?></h5>
                            <p class="mb-0 text-muted">
                                <i class="fas fa-users me-1"></i><?php echo $order['number_of_people']; ?> personnes
                            </p>
                            <a href="/menu/show/<?php echo $order['menu_id']; ?>" class="btn btn-sm btn-outline-primary mt-2">
                                <i class="fas fa-eye me-1"></i>Voir le menu
                            </a>
                        </div>
                    </div>

                    <!-- Livraison -->
                    <h5 class="border-bottom pb-2 mb-3" style="color: #2E7D32;">
                        <i class="fas fa-truck me-2"></i>Livraison
                    </h5>
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <p class="mb-1"><strong>Date et heure</strong></p>
                            <p class="mb-0">
                                <i class="fas fa-calendar me-2 text-muted"></i>
                                <?php echo date('d/m/Y', strtotime($order['delivery_date'])); ?>
                                a <?php echo date('H:i', strtotime($order['delivery_time'])); ?>
                            </p>
                        </div>
                        <div class="col-md-6">
                            <p class="mb-1"><strong>Adresse</strong></p>
                            <p class="mb-0">
                                <i class="fas fa-map-marker-alt me-2 text-muted"></i>
                                <?php echo htmlspecialchars($order['delivery_address']); ?><br>
                                <span class="ms-4"><?php echo htmlspecialchars($order['delivery_postal_code'] . ' ' . $order['delivery_city']); ?></span>
                            </p>
                            <?php if ($order['delivery_location']): ?>
                                <p class="mb-0 ms-4 text-muted">
                                    <small><?php echo htmlspecialchars($order['delivery_location']); ?></small>
                                </p>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- Contact -->
                    <h5 class="border-bottom pb-2 mb-3" style="color: #2E7D32;">
                        <i class="fas fa-user me-2"></i>Contact
                    </h5>
                    <div class="row mb-4">
                        <div class="col-md-4">
                            <p class="mb-1"><strong>Nom</strong></p>
                            <p class="mb-0"><?php echo htmlspecialchars($order['customer_first_name'] . ' ' . $order['customer_last_name']); ?></p>
                        </div>
                        <div class="col-md-4">
                            <p class="mb-1"><strong>Email</strong></p>
                            <p class="mb-0"><?php echo htmlspecialchars($order['customer_email']); ?></p>
                        </div>
                        <div class="col-md-4">
                            <p class="mb-1"><strong>Telephone</strong></p>
                            <p class="mb-0"><?php echo htmlspecialchars($order['customer_phone']); ?></p>
                        </div>
                    </div>

                    <!-- Notes -->
                    <?php if ($order['customer_notes']): ?>
                        <h5 class="border-bottom pb-2 mb-3" style="color: #2E7D32;">
                            <i class="fas fa-comment me-2"></i>Notes du client
                        </h5>
                        <p class="mb-4"><?php echo nl2br(htmlspecialchars($order['customer_notes'])); ?></p>
                    <?php endif; ?>

                    <?php if ($isEmployee && $order['admin_notes']): ?>
                        <h5 class="border-bottom pb-2 mb-3" style="color: #2E7D32;">
                            <i class="fas fa-clipboard me-2"></i>Notes internes
                        </h5>
                        <p class="mb-4"><?php echo nl2br(htmlspecialchars($order['admin_notes'])); ?></p>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Actions -->
            <?php if ($canCancel || $canReview): ?>
                <div class="card shadow mt-4">
                    <div class="card-body">
                        <div class="d-flex gap-2 flex-wrap">
                            <?php if ($canReview): ?>
                                <a href="/review/create/<?php echo $order['id']; ?>" class="btn btn-success">
                                    <i class="fas fa-star me-2"></i>Laisser un avis
                                </a>
                            <?php endif; ?>

                            <?php if ($canCancel): ?>
                                <form action="/order/cancel/<?php echo $order['id']; ?>" method="POST"
                                      onsubmit="return confirm('Etes-vous sur de vouloir annuler cette commande ?');">
                                    <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($csrfToken); ?>">
                                    <button type="submit" class="btn btn-outline-danger">
                                        <i class="fas fa-times me-2"></i>Annuler la commande
                                    </button>
                                </form>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </div>

        <!-- Recapitulatif prix -->
        <div class="col-lg-4">
            <div class="card shadow sticky-top" style="top: 20px;">
                <div class="card-header" style="background-color: #FF8F00; color: white;">
                    <h5 class="mb-0">
                        <i class="fas fa-receipt me-2"></i>Recapitulatif
                    </h5>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between mb-2">
                        <span>Sous-total menu</span>
                        <span><?php echo number_format($order['base_price'], 2, ',', ' '); ?> EUR</span>
                    </div>

                    <?php if ($order['discount_amount'] > 0): ?>
                        <div class="d-flex justify-content-between mb-2 text-success">
                            <span><i class="fas fa-percent me-1"></i>Reduction</span>
                            <span>-<?php echo number_format($order['discount_amount'], 2, ',', ' '); ?> EUR</span>
                        </div>
                    <?php endif; ?>

                    <div class="d-flex justify-content-between mb-2">
                        <span><i class="fas fa-truck me-1"></i>Livraison</span>
                        <span>
                            <?php if ($order['delivery_fee'] > 0): ?>
                                <?php echo number_format($order['delivery_fee'], 2, ',', ' '); ?> EUR
                            <?php else: ?>
                                <span class="text-success">Gratuit</span>
                            <?php endif; ?>
                        </span>
                    </div>

                    <hr>

                    <div class="d-flex justify-content-between align-items-center">
                        <strong class="fs-5">Total</strong>
                        <strong class="fs-4" style="color: #FF8F00;">
                            <?php echo number_format($order['total_price'], 2, ',', ' '); ?> EUR
                        </strong>
                    </div>

                    <!-- Timeline -->
                    <hr>
                    <h6><i class="fas fa-history me-2"></i>Suivi</h6>
                    <div class="timeline-small">
                        <div class="d-flex align-items-center mb-2">
                            <div class="rounded-circle me-2 d-flex align-items-center justify-content-center"
                                 style="width: 24px; height: 24px; background-color: #2E7D32;">
                                <i class="fas fa-check text-white" style="font-size: 10px;"></i>
                            </div>
                            <small>Commande passee le <?php echo date('d/m/Y a H:i', strtotime($order['created_at'])); ?></small>
                        </div>
                        <?php if ($order['status'] !== 'pending' && $order['status'] !== 'cancelled'): ?>
                            <div class="d-flex align-items-center mb-2">
                                <div class="rounded-circle me-2 d-flex align-items-center justify-content-center"
                                     style="width: 24px; height: 24px; background-color: #2E7D32;">
                                    <i class="fas fa-check text-white" style="font-size: 10px;"></i>
                                </div>
                                <small>Commande acceptee</small>
                            </div>
                        <?php endif; ?>
                        <?php if (in_array($order['status'], ['preparing', 'delivering', 'delivered', 'waiting_return', 'completed'])): ?>
                            <div class="d-flex align-items-center mb-2">
                                <div class="rounded-circle me-2 d-flex align-items-center justify-content-center"
                                     style="width: 24px; height: 24px; background-color: #2E7D32;">
                                    <i class="fas fa-check text-white" style="font-size: 10px;"></i>
                                </div>
                                <small>En preparation</small>
                            </div>
                        <?php endif; ?>
                        <?php if (in_array($order['status'], ['delivered', 'waiting_return', 'completed'])): ?>
                            <div class="d-flex align-items-center mb-2">
                                <div class="rounded-circle me-2 d-flex align-items-center justify-content-center"
                                     style="width: 24px; height: 24px; background-color: #2E7D32;">
                                    <i class="fas fa-check text-white" style="font-size: 10px;"></i>
                                </div>
                                <small>Livree</small>
                            </div>
                        <?php endif; ?>
                        <?php if ($order['status'] === 'completed'): ?>
                            <div class="d-flex align-items-center mb-2">
                                <div class="rounded-circle me-2 d-flex align-items-center justify-content-center"
                                     style="width: 24px; height: 24px; background-color: #2E7D32;">
                                    <i class="fas fa-check text-white" style="font-size: 10px;"></i>
                                </div>
                                <small>Terminee</small>
                            </div>
                        <?php endif; ?>
                        <?php if ($order['status'] === 'cancelled'): ?>
                            <div class="d-flex align-items-center mb-2">
                                <div class="rounded-circle me-2 d-flex align-items-center justify-content-center"
                                     style="width: 24px; height: 24px; background-color: #dc3545;">
                                    <i class="fas fa-times text-white" style="font-size: 10px;"></i>
                                </div>
                                <small>Annulee</small>
                            </div>
                        <?php endif; ?>
                    </div>

                    <!-- Paiement -->
                    <hr>
                    <div class="alert alert-info mb-0" role="alert">
                        <i class="fas fa-info-circle me-2"></i>
                        <small>Paiement a la livraison</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
