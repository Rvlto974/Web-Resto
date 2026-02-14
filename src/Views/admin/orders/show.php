<?php
require_once __DIR__ . '/../../../Core/Auth.php';
require_once __DIR__ . '/../../../Core/Csrf.php';
?>

<div class="container-fluid py-4">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/admin/dashboard">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="/admin/orders">Commandes</a></li>
            <li class="breadcrumb-item active"><?php echo htmlspecialchars($order['order_number']); ?></li>
        </ol>
    </nav>

    <div class="row">
        <!-- Details de la commande -->
        <div class="col-lg-8 mb-4">
            <div class="card shadow">
                <div class="card-header d-flex justify-content-between align-items-center" style="background-color: #5DA99A; color: white;">
                    <h4 class="mb-0">
                        <i class="fas fa-receipt me-2"></i>Commande <?php echo htmlspecialchars($order['order_number']); ?>
                    </h4>
                    <span class="badge bg-<?php echo $statusColors[$order['status']] ?? 'secondary'; ?> fs-6">
                        <?php echo htmlspecialchars($statusLabels[$order['status']] ?? $order['status']); ?>
                    </span>
                </div>
                <div class="card-body">
                    <!-- Menu -->
                    <h5 class="border-bottom pb-2 mb-3"><i class="fas fa-utensils me-2"></i>Menu commande</h5>
                    <div class="d-flex align-items-center mb-4 p-3 rounded" style="background-color: #F5F5F5;">
                        <?php if ($order['menu_image']): ?>
                            <img src="<?php echo htmlspecialchars($order['menu_image']); ?>"
                                 class="rounded me-3" style="width: 100px; height: 75px; object-fit: cover;">
                        <?php endif; ?>
                        <div>
                            <h5 class="mb-1"><?php echo htmlspecialchars($order['menu_title']); ?></h5>
                            <p class="mb-0">
                                <i class="fas fa-users me-2"></i><?php echo $order['number_of_people']; ?> personnes
                            </p>
                        </div>
                    </div>

                    <!-- Client -->
                    <h5 class="border-bottom pb-2 mb-3"><i class="fas fa-user me-2"></i>Client</h5>
                    <div class="row mb-4">
                        <div class="col-md-4">
                            <p class="mb-1"><strong>Nom</strong></p>
                            <p class="mb-0"><?php echo htmlspecialchars($order['customer_first_name'] . ' ' . $order['customer_last_name']); ?></p>
                        </div>
                        <div class="col-md-4">
                            <p class="mb-1"><strong>Email</strong></p>
                            <p class="mb-0">
                                <a href="mailto:<?php echo htmlspecialchars($order['customer_email']); ?>">
                                    <?php echo htmlspecialchars($order['customer_email']); ?>
                                </a>
                            </p>
                        </div>
                        <div class="col-md-4">
                            <p class="mb-1"><strong>Telephone</strong></p>
                            <p class="mb-0">
                                <a href="tel:<?php echo htmlspecialchars($order['customer_phone']); ?>">
                                    <?php echo htmlspecialchars($order['customer_phone']); ?>
                                </a>
                            </p>
                        </div>
                    </div>

                    <!-- Livraison -->
                    <h5 class="border-bottom pb-2 mb-3"><i class="fas fa-truck me-2"></i>Livraison</h5>
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
                                <?php echo htmlspecialchars($order['delivery_address']); ?><br>
                                <?php echo htmlspecialchars($order['delivery_postal_code'] . ' ' . $order['delivery_city']); ?>
                                <?php if ($order['delivery_location']): ?>
                                    <br><small class="text-muted"><?php echo htmlspecialchars($order['delivery_location']); ?></small>
                                <?php endif; ?>
                            </p>
                        </div>
                    </div>

                    <!-- Notes -->
                    <?php if ($order['customer_notes']): ?>
                        <h5 class="border-bottom pb-2 mb-3"><i class="fas fa-comment me-2"></i>Notes du client</h5>
                        <div class="alert alert-info mb-4">
                            <?php echo nl2br(htmlspecialchars($order['customer_notes'])); ?>
                        </div>
                    <?php endif; ?>

                    <?php if ($order['admin_notes']): ?>
                        <h5 class="border-bottom pb-2 mb-3"><i class="fas fa-clipboard me-2"></i>Notes internes</h5>
                        <div class="alert alert-secondary mb-4">
                            <?php echo nl2br(htmlspecialchars($order['admin_notes'])); ?>
                        </div>
                    <?php endif; ?>

                    <!-- Prix -->
                    <h5 class="border-bottom pb-2 mb-3"><i class="fas fa-euro-sign me-2"></i>Prix</h5>
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-sm">
                                <tr>
                                    <td>Sous-total</td>
                                    <td class="text-end"><?php echo number_format($order['base_price'], 2, ',', ' '); ?> EUR</td>
                                </tr>
                                <?php if ($order['discount_amount'] > 0): ?>
                                    <tr class="text-success">
                                        <td>Reduction</td>
                                        <td class="text-end">-<?php echo number_format($order['discount_amount'], 2, ',', ' '); ?> EUR</td>
                                    </tr>
                                <?php endif; ?>
                                <tr>
                                    <td>Frais de livraison</td>
                                    <td class="text-end">
                                        <?php echo $order['delivery_fee'] > 0 ? number_format($order['delivery_fee'], 2, ',', ' ') . ' EUR' : 'Gratuit'; ?>
                                    </td>
                                </tr>
                                <tr class="table-active">
                                    <td><strong>Total</strong></td>
                                    <td class="text-end"><strong style="color: #FF8F00;"><?php echo number_format($order['total_price'], 2, ',', ' '); ?> EUR</strong></td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Actions et statut -->
        <div class="col-lg-4">
            <!-- Changer le statut -->
            <div class="card shadow mb-4">
                <div class="card-header" style="background-color: #FF8F00; color: white;">
                    <h5 class="mb-0"><i class="fas fa-cog me-2"></i>Gestion</h5>
                </div>
                <div class="card-body">
                    <form action="/admin/orderUpdateStatus/<?php echo $order['id']; ?>" method="POST">
                        <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($csrfToken); ?>">

                        <div class="mb-3">
                            <label for="status" class="form-label">Changer le statut</label>
                            <select class="form-select" id="status" name="status">
                                <?php foreach ($statusLabels as $value => $label): ?>
                                    <option value="<?php echo $value; ?>" <?php echo $order['status'] === $value ? 'selected' : ''; ?>>
                                        <?php echo htmlspecialchars($label); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="admin_notes" class="form-label">Notes internes</label>
                            <textarea class="form-control" id="admin_notes" name="admin_notes" rows="3"><?php echo htmlspecialchars($order['admin_notes'] ?? ''); ?></textarea>
                        </div>

                        <button type="submit" class="btn w-100" style="background-color: #5DA99A; color: white;">
                            <i class="fas fa-save me-2"></i>Enregistrer
                        </button>
                    </form>

                    <?php if ($order['status'] === 'waiting_return' && !$order['equipment_returned']): ?>
                        <hr>
                        <form action="/admin/orderEquipmentReturned/<?php echo $order['id']; ?>" method="POST">
                            <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($csrfToken); ?>">
                            <button type="submit" class="btn btn-outline-success w-100">
                                <i class="fas fa-check me-2"></i>Marquer materiel retourne
                            </button>
                        </form>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Timeline -->
            <div class="card shadow">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-history me-2"></i>Historique</h5>
                </div>
                <div class="card-body">
                    <div class="timeline-small">
                        <div class="d-flex mb-3">
                            <div class="rounded-circle me-3 d-flex align-items-center justify-content-center flex-shrink-0"
                                 style="width: 32px; height: 32px; background-color: #5DA99A;">
                                <i class="fas fa-plus text-white" style="font-size: 12px;"></i>
                            </div>
                            <div>
                                <strong>Commande creee</strong>
                                <br><small class="text-muted"><?php echo date('d/m/Y a H:i', strtotime($order['created_at'])); ?></small>
                            </div>
                        </div>
                        <?php if ($order['updated_at'] !== $order['created_at']): ?>
                            <div class="d-flex mb-3">
                                <div class="rounded-circle me-3 d-flex align-items-center justify-content-center flex-shrink-0"
                                     style="width: 32px; height: 32px; background-color: #1565C0;">
                                    <i class="fas fa-edit text-white" style="font-size: 12px;"></i>
                                </div>
                                <div>
                                    <strong>Derniere modification</strong>
                                    <br><small class="text-muted"><?php echo date('d/m/Y a H:i', strtotime($order['updated_at'])); ?></small>
                                </div>
                            </div>
                        <?php endif; ?>
                        <?php if ($order['equipment_returned']): ?>
                            <div class="d-flex mb-3">
                                <div class="rounded-circle me-3 d-flex align-items-center justify-content-center flex-shrink-0"
                                     style="width: 32px; height: 32px; background-color: #7DBFB2;">
                                    <i class="fas fa-check text-white" style="font-size: 12px;"></i>
                                </div>
                                <div>
                                    <strong>Materiel retourne</strong>
                                    <br><small class="text-muted"><?php echo date('d/m/Y a H:i', strtotime($order['equipment_return_date'])); ?></small>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
