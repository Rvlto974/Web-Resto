<?php
require_once __DIR__ . '/../../../Core/Auth.php';
?>

<div class="container-fluid py-4">
    <h1 class="mb-4" style="font-family: 'Playfair Display', serif; color: #2E7D32;">
        <i class="fas fa-chart-bar me-2"></i>Statistiques
    </h1>

    <!-- Stats du jour -->
    <div class="row g-4 mb-4">
        <div class="col-md-4">
            <div class="card shadow text-center" style="border-top: 4px solid #2E7D32;">
                <div class="card-body">
                    <i class="fas fa-shopping-cart fa-2x mb-3" style="color: #2E7D32;"></i>
                    <h3><?php echo $todayStats['count']; ?></h3>
                    <p class="text-muted mb-0">Commandes aujourd'hui</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card shadow text-center" style="border-top: 4px solid #FF8F00;">
                <div class="card-body">
                    <i class="fas fa-euro-sign fa-2x mb-3" style="color: #FF8F00;"></i>
                    <h3><?php echo number_format($todayStats['revenue'], 2, ',', ' '); ?> EUR</h3>
                    <p class="text-muted mb-0">Chiffre d'affaires aujourd'hui</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card shadow text-center" style="border-top: 4px solid #1565C0;">
                <div class="card-body">
                    <i class="fas fa-ticket-alt fa-2x mb-3" style="color: #1565C0;"></i>
                    <h3><?php echo $todayStats['count'] > 0 ? number_format($todayStats['revenue'] / $todayStats['count'], 2, ',', ' ') : '0,00'; ?> EUR</h3>
                    <p class="text-muted mb-0">Panier moyen</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Repartition par statut -->
    <div class="row g-4 mb-4">
        <div class="col-lg-6">
            <div class="card shadow h-100">
                <div class="card-header" style="background-color: #2E7D32; color: white;">
                    <h5 class="mb-0"><i class="fas fa-list-ol me-2"></i>Commandes par statut</h5>
                </div>
                <div class="card-body">
                    <table class="table table-hover mb-0">
                        <tbody>
                            <?php foreach ($statusLabels as $status => $label): ?>
                                <tr>
                                    <td>
                                        <span class="badge bg-<?php echo $statusColors[$status] ?? 'secondary'; ?> me-2">
                                            <?php echo htmlspecialchars($label); ?>
                                        </span>
                                    </td>
                                    <td class="text-end">
                                        <strong><?php echo $ordersByStatus[$status] ?? 0; ?></strong>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                        <tfoot class="table-light">
                            <tr>
                                <td><strong>Total</strong></td>
                                <td class="text-end"><strong><?php echo array_sum($ordersByStatus); ?></strong></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="card shadow h-100">
                <div class="card-header" style="background-color: #2E7D32; color: white;">
                    <h5 class="mb-0"><i class="fas fa-chart-pie me-2"></i>Visualisation</h5>
                </div>
                <div class="card-body d-flex align-items-center justify-content-center">
                    <canvas id="statusChart" style="max-height: 300px;"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Placeholder pour stats MongoDB -->
    <div class="card shadow mb-4">
        <div class="card-header">
            <h5 class="mb-0"><i class="fas fa-database me-2"></i>Statistiques avancees (MongoDB)</h5>
        </div>
        <div class="card-body text-center py-5">
            <i class="fas fa-chart-line fa-4x text-muted mb-3"></i>
            <p class="text-muted mb-0">
                Les statistiques avancees (CA par menu, tendances mensuelles, etc.) seront disponibles
                une fois la connexion MongoDB configuree.
            </p>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const ctx = document.getElementById('statusChart').getContext('2d');

    const data = {
        labels: <?php echo json_encode(array_values($statusLabels)); ?>,
        datasets: [{
            data: [
                <?php
                $values = [];
                foreach ($statusLabels as $status => $label) {
                    $values[] = $ordersByStatus[$status] ?? 0;
                }
                echo implode(',', $values);
                ?>
            ],
            backgroundColor: [
                '#ffc107', // pending - warning
                '#17a2b8', // accepted - info
                '#007bff', // preparing - primary
                '#17a2b8', // delivering - info
                '#28a745', // delivered - success
                '#6c757d', // waiting_return - secondary
                '#28a745', // completed - success
                '#dc3545'  // cancelled - danger
            ]
        }]
    };

    new Chart(ctx, {
        type: 'doughnut',
        data: data,
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'bottom'
                }
            }
        }
    });
});
</script>
