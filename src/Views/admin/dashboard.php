<?php
require_once __DIR__ . '/../../Core/Auth.php';
?>

<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 style="font-family: 'Playfair Display', serif; color: #5DA99A;">
            <i class="fas fa-tachometer-alt me-2"></i>Tableau de bord
        </h1>
        <span class="badge bg-primary fs-6">
            <?php echo Auth::isAdmin() ? 'Administrateur' : 'Employe'; ?>
        </span>
    </div>

    <!-- Stats Cards -->
    <div class="row g-4 mb-4">
        <!-- Commandes du jour -->
        <div class="col-xl-3 col-md-6">
            <div class="card shadow h-100" style="border-left: 4px solid #5DA99A;">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted text-uppercase small mb-1">Commandes aujourd'hui</h6>
                            <h2 class="mb-0"><?php echo $todayStats['count']; ?></h2>
                        </div>
                        <div class="rounded-circle p-3" style="background-color: #E0F5F1;">
                            <i class="fas fa-shopping-cart fa-2x" style="color: #5DA99A;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- CA du jour -->
        <div class="col-xl-3 col-md-6">
            <div class="card shadow h-100" style="border-left: 4px solid #FF8F00;">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted text-uppercase small mb-1">CA aujourd'hui</h6>
                            <h2 class="mb-0"><?php echo number_format($todayStats['revenue'], 2, ',', ' '); ?> EUR</h2>
                        </div>
                        <div class="rounded-circle p-3" style="background-color: #FFF3E0;">
                            <i class="fas fa-euro-sign fa-2x" style="color: #FF8F00;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Commandes en attente -->
        <div class="col-xl-3 col-md-6">
            <div class="card shadow h-100" style="border-left: 4px solid #ffc107;">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted text-uppercase small mb-1">En attente</h6>
                            <h2 class="mb-0"><?php echo $orderStats['pending'] ?? 0; ?></h2>
                        </div>
                        <div class="rounded-circle p-3" style="background-color: #FFF8E1;">
                            <i class="fas fa-clock fa-2x text-warning"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Avis en attente -->
        <div class="col-xl-3 col-md-6">
            <div class="card shadow h-100" style="border-left: 4px solid #1565C0;">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted text-uppercase small mb-1">Avis a valider</h6>
                            <h2 class="mb-0"><?php echo $pendingReviews; ?></h2>
                        </div>
                        <div class="rounded-circle p-3" style="background-color: #E3F2FD;">
                            <i class="fas fa-star fa-2x" style="color: #1565C0;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Stats utilisateurs (admin only) -->
    <?php if ($isAdmin && !empty($userStats)): ?>
        <div class="row g-4 mb-4">
            <div class="col-md-4">
                <div class="card shadow">
                    <div class="card-body text-center">
                        <i class="fas fa-users fa-2x mb-2 text-primary"></i>
                        <h3><?php echo $userStats['clients']; ?></h3>
                        <p class="text-muted mb-0">Clients</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card shadow">
                    <div class="card-body text-center">
                        <i class="fas fa-user-tie fa-2x mb-2 text-success"></i>
                        <h3><?php echo $userStats['employees']; ?></h3>
                        <p class="text-muted mb-0">Employes</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card shadow">
                    <div class="card-body text-center">
                        <i class="fas fa-user-shield fa-2x mb-2 text-danger"></i>
                        <h3><?php echo $userStats['admins']; ?></h3>
                        <p class="text-muted mb-0">Administrateurs</p>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <div class="row g-4">
        <!-- Raccourcis -->
        <div class="col-lg-4">
            <div class="card shadow h-100">
                <div class="card-header" style="background-color: #5DA99A; color: white;">
                    <h5 class="mb-0"><i class="fas fa-bolt me-2"></i>Raccourcis</h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="/admin/orders?status=pending" class="btn btn-outline-warning">
                            <i class="fas fa-clock me-2"></i>Commandes en attente
                            <?php if (($orderStats['pending'] ?? 0) > 0): ?>
                                <span class="badge bg-warning text-dark"><?php echo $orderStats['pending']; ?></span>
                            <?php endif; ?>
                        </a>
                        <a href="/admin/reviews?status=pending" class="btn btn-outline-primary">
                            <i class="fas fa-star me-2"></i>Avis a valider
                            <?php if ($pendingReviews > 0): ?>
                                <span class="badge bg-primary"><?php echo $pendingReviews; ?></span>
                            <?php endif; ?>
                        </a>
                        <a href="/admin/menus" class="btn btn-outline-success">
                            <i class="fas fa-utensils me-2"></i>Gerer les menus
                        </a>
                        <?php if ($isAdmin): ?>
                            <a href="/admin/employees" class="btn btn-outline-secondary">
                                <i class="fas fa-users-cog me-2"></i>Gerer les employes
                            </a>
                            <a href="/admin/stats" class="btn btn-outline-info">
                                <i class="fas fa-chart-bar me-2"></i>Statistiques
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Commandes recentes -->
        <div class="col-lg-8">
            <div class="card shadow h-100">
                <div class="card-header d-flex justify-content-between align-items-center" style="background-color: #5DA99A; color: white;">
                    <h5 class="mb-0"><i class="fas fa-list me-2"></i>Commandes recentes</h5>
                    <a href="/admin/orders" class="btn btn-sm btn-light">Voir tout</a>
                </div>
                <div class="card-body p-0">
                    <?php if (empty($recentOrders)): ?>
                        <p class="text-muted text-center py-4">Aucune commande</p>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead>
                                    <tr>
                                        <th>Commande</th>
                                        <th>Client</th>
                                        <th>Livraison</th>
                                        <th>Total</th>
                                        <th>Statut</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($recentOrders as $order): ?>
                                        <tr>
                                            <td>
                                                <strong><?php echo htmlspecialchars($order['order_number']); ?></strong>
                                                <br><small class="text-muted"><?php echo htmlspecialchars($order['menu_title']); ?></small>
                                            </td>
                                            <td>
                                                <?php echo htmlspecialchars($order['user_first_name'] . ' ' . $order['user_last_name']); ?>
                                            </td>
                                            <td>
                                                <?php echo date('d/m/Y', strtotime($order['delivery_date'])); ?>
                                            </td>
                                            <td>
                                                <strong><?php echo number_format($order['total_price'], 2, ',', ' '); ?> EUR</strong>
                                            </td>
                                            <td>
                                                <span class="badge bg-<?php echo $statusColors[$order['status']] ?? 'secondary'; ?>">
                                                    <?php echo htmlspecialchars($statusLabels[$order['status']] ?? $order['status']); ?>
                                                </span>
                                            </td>
                                            <td>
                                                <a href="/admin/orderShow/<?php echo $order['id']; ?>" class="btn btn-sm btn-outline-primary">
                                                    <i class="fas fa-eye"></i>
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
    </div>
</div>
