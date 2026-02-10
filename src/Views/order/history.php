<?php
require_once __DIR__ . '/../../Core/Auth.php';
?>

<div class="container py-5">
    <h1 class="mb-4" style="font-family: 'Playfair Display', serif; color: #5DA99A;">
        <i class="fas fa-history me-2"></i>Mes commandes
    </h1>

    <?php if (empty($orders)): ?>
        <div class="card shadow">
            <div class="card-body text-center py-5">
                <i class="fas fa-shopping-bag fa-4x text-muted mb-4"></i>
                <h4>Aucune commande</h4>
                <p class="text-muted mb-4">Vous n'avez pas encore passe de commande.</p>
                <a href="/menu" class="btn btn-lg" style="background-color: #5DA99A; color: white;">
                    <i class="fas fa-utensils me-2"></i>Decouvrir nos menus
                </a>
            </div>
        </div>
    <?php else: ?>
        <div class="card shadow">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead style="background-color: #5DA99A; color: white;">
                        <tr>
                            <th scope="col">Commande</th>
                            <th scope="col">Menu</th>
                            <th scope="col">Date de livraison</th>
                            <th scope="col">Total</th>
                            <th scope="col">Statut</th>
                            <th scope="col" class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($orders as $order): ?>
                            <tr>
                                <td>
                                    <strong style="color: #FF8F00;"><?php echo htmlspecialchars($order['order_number']); ?></strong>
                                    <br>
                                    <small class="text-muted"><?php echo date('d/m/Y H:i', strtotime($order['created_at'])); ?></small>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <?php if ($order['menu_image']): ?>
                                            <img src="<?php echo htmlspecialchars($order['menu_image']); ?>"
                                                 class="rounded me-2" style="width: 50px; height: 40px; object-fit: cover;">
                                        <?php endif; ?>
                                        <div>
                                            <?php echo htmlspecialchars($order['menu_title']); ?>
                                            <br><small class="text-muted"><?php echo $order['number_of_people']; ?> pers.</small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <i class="fas fa-calendar me-1" style="color: #5DA99A;"></i>
                                    <?php echo date('d/m/Y', strtotime($order['delivery_date'])); ?>
                                    <br>
                                    <small class="text-muted"><?php echo date('H:i', strtotime($order['delivery_time'])); ?></small>
                                </td>
                                <td>
                                    <strong><?php echo number_format($order['total_price'], 2, ',', ' '); ?> EUR</strong>
                                </td>
                                <td>
                                    <?php
                                    $statusColor = $statusColors[$order['status']] ?? 'secondary';
                                    $statusLabel = $statusLabels[$order['status']] ?? $order['status'];
                                    ?>
                                    <span class="badge bg-<?php echo $statusColor; ?>">
                                        <?php echo htmlspecialchars($statusLabel); ?>
                                    </span>
                                </td>
                                <td class="text-end">
                                    <a href="/order/show/<?php echo $order['id']; ?>" class="btn btn-sm btn-outline-primary">
                                        <i class="fas fa-eye"></i> Details
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Legende des statuts -->
        <div class="card mt-4">
            <div class="card-header">
                <h6 class="mb-0"><i class="fas fa-info-circle me-2"></i>Legende des statuts</h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <?php foreach ($statusLabels as $status => $label): ?>
                        <div class="col-md-3 col-6 mb-2">
                            <span class="badge bg-<?php echo $statusColors[$status] ?? 'secondary'; ?> me-2">
                                <?php echo htmlspecialchars($label); ?>
                            </span>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>
