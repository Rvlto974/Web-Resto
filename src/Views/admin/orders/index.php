<?php
require_once __DIR__ . '/../../../Core/Auth.php';
?>

<div class="container-fluid py-4">
    <h1 class="mb-4" style="font-family: 'Playfair Display', serif; color: #5DA99A;">
        <i class="fas fa-shopping-cart me-2"></i>Gestion des commandes
    </h1>

    <!-- Filtres -->
    <div class="card shadow mb-4">
        <div class="card-body">
            <form action="/admin/orders" method="GET" class="row g-3">
                <div class="col-md-3">
                    <label for="status" class="form-label">Statut</label>
                    <select class="form-select" id="status" name="status">
                        <option value="">Tous les statuts</option>
                        <?php foreach ($statusLabels as $value => $label): ?>
                            <option value="<?php echo $value; ?>" <?php echo ($filters['status'] ?? '') === $value ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($label); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="date_from" class="form-label">Date du</label>
                    <input type="date" class="form-control" id="date_from" name="date_from"
                           value="<?php echo $filters['date_from'] ?? ''; ?>">
                </div>
                <div class="col-md-3">
                    <label for="date_to" class="form-label">Date au</label>
                    <input type="date" class="form-control" id="date_to" name="date_to"
                           value="<?php echo $filters['date_to'] ?? ''; ?>">
                </div>
                <div class="col-md-3 d-flex align-items-end">
                    <button type="submit" class="btn me-2" style="background-color: #5DA99A; color: white;">
                        <i class="fas fa-search me-1"></i>Filtrer
                    </button>
                    <a href="/admin/orders" class="btn btn-outline-secondary">
                        <i class="fas fa-times"></i>
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Liste des commandes -->
    <div class="card shadow">
        <div class="card-body p-0">
            <?php if (empty($orders)): ?>
                <p class="text-muted text-center py-5">Aucune commande trouvee.</p>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead style="background-color: #5DA99A; color: white;">
                            <tr>
                                <th>Commande</th>
                                <th>Client</th>
                                <th>Menu</th>
                                <th>Livraison</th>
                                <th>Total</th>
                                <th>Statut</th>
                                <th class="text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($orders as $order): ?>
                                <tr>
                                    <td>
                                        <strong style="color: #FF8F00;"><?php echo htmlspecialchars($order['order_number']); ?></strong>
                                        <br><small class="text-muted"><?php echo date('d/m/Y H:i', strtotime($order['created_at'])); ?></small>
                                    </td>
                                    <td>
                                        <?php echo htmlspecialchars($order['user_first_name'] . ' ' . $order['user_last_name']); ?>
                                        <br><small class="text-muted"><?php echo htmlspecialchars($order['user_email']); ?></small>
                                    </td>
                                    <td>
                                        <?php echo htmlspecialchars($order['menu_title']); ?>
                                        <br><small class="text-muted"><?php echo $order['number_of_people']; ?> pers.</small>
                                    </td>
                                    <td>
                                        <i class="fas fa-calendar me-1"></i><?php echo date('d/m/Y', strtotime($order['delivery_date'])); ?>
                                        <br><small class="text-muted"><?php echo date('H:i', strtotime($order['delivery_time'])); ?> - <?php echo htmlspecialchars($order['delivery_city']); ?></small>
                                    </td>
                                    <td>
                                        <strong><?php echo number_format($order['total_price'], 2, ',', ' '); ?> EUR</strong>
                                    </td>
                                    <td>
                                        <span class="badge bg-<?php echo $statusColors[$order['status']] ?? 'secondary'; ?>">
                                            <?php echo htmlspecialchars($statusLabels[$order['status']] ?? $order['status']); ?>
                                        </span>
                                    </td>
                                    <td class="text-end">
                                        <a href="/admin/orderShow/<?php echo $order['id']; ?>" class="btn btn-sm btn-outline-primary">
                                            <i class="fas fa-eye me-1"></i>Details
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>
