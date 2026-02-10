<?php
require_once __DIR__ . '/../../../Core/Auth.php';
require_once __DIR__ . '/../../../Core/Csrf.php';
?>

<div class="container-fluid py-4">
    <h1 class="mb-4" style="font-family: 'Playfair Display', serif; color: #2E7D32;">
        <i class="fas fa-star me-2"></i>Gestion des avis
    </h1>

    <!-- Filtres -->
    <div class="card shadow mb-4">
        <div class="card-body">
            <div class="btn-group" role="group">
                <a href="/admin/reviews" class="btn <?php echo empty($filters['status']) ? 'btn-primary' : 'btn-outline-primary'; ?>">
                    Tous
                </a>
                <a href="/admin/reviews?status=pending" class="btn <?php echo ($filters['status'] ?? '') === 'pending' ? 'btn-warning' : 'btn-outline-warning'; ?>">
                    <i class="fas fa-clock me-1"></i>En attente
                </a>
                <a href="/admin/reviews?status=approved" class="btn <?php echo ($filters['status'] ?? '') === 'approved' ? 'btn-success' : 'btn-outline-success'; ?>">
                    <i class="fas fa-check me-1"></i>Approuves
                </a>
            </div>
        </div>
    </div>

    <!-- Liste des avis -->
    <div class="card shadow">
        <div class="card-body p-0">
            <?php if (empty($reviews)): ?>
                <p class="text-muted text-center py-5">Aucun avis trouve.</p>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead style="background-color: #2E7D32; color: white;">
                            <tr>
                                <th>Client</th>
                                <th>Menu</th>
                                <th>Note</th>
                                <th>Commentaire</th>
                                <th>Date</th>
                                <th>Statut</th>
                                <th class="text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($reviews as $review): ?>
                                <tr class="<?php echo !$review['is_approved'] ? 'table-warning' : ''; ?>">
                                    <td>
                                        <strong><?php echo htmlspecialchars($review['first_name'] . ' ' . $review['last_name']); ?></strong>
                                        <br><small class="text-muted"><?php echo htmlspecialchars($review['email']); ?></small>
                                    </td>
                                    <td>
                                        <?php echo htmlspecialchars($review['menu_title']); ?>
                                        <br><small class="text-muted">Commande: <?php echo htmlspecialchars($review['order_number']); ?></small>
                                    </td>
                                    <td>
                                        <?php for ($i = 1; $i <= 5; $i++): ?>
                                            <i class="fas fa-star <?php echo $i <= $review['rating'] ? 'text-warning' : 'text-muted'; ?>"></i>
                                        <?php endfor; ?>
                                    </td>
                                    <td>
                                        <?php if ($review['comment']): ?>
                                            <span title="<?php echo htmlspecialchars($review['comment']); ?>">
                                                <?php echo htmlspecialchars(substr($review['comment'], 0, 80)); ?>
                                                <?php echo strlen($review['comment']) > 80 ? '...' : ''; ?>
                                            </span>
                                        <?php else: ?>
                                            <span class="text-muted">-</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php echo date('d/m/Y H:i', strtotime($review['created_at'])); ?>
                                    </td>
                                    <td>
                                        <?php if ($review['is_approved']): ?>
                                            <span class="badge bg-success">
                                                <i class="fas fa-check me-1"></i>Approuve
                                            </span>
                                            <?php if ($review['approver_first_name']): ?>
                                                <br><small class="text-muted">par <?php echo htmlspecialchars($review['approver_first_name']); ?></small>
                                            <?php endif; ?>
                                        <?php else: ?>
                                            <span class="badge bg-warning text-dark">
                                                <i class="fas fa-clock me-1"></i>En attente
                                            </span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-end">
                                        <?php if (!$review['is_approved']): ?>
                                            <div class="btn-group">
                                                <form action="/admin/review/approve/<?php echo $review['id']; ?>" method="POST" class="d-inline">
                                                    <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($csrfToken); ?>">
                                                    <button type="submit" class="btn btn-sm btn-success" title="Approuver">
                                                        <i class="fas fa-check"></i>
                                                    </button>
                                                </form>
                                                <form action="/admin/review/reject/<?php echo $review['id']; ?>" method="POST" class="d-inline"
                                                      onsubmit="return confirm('Supprimer cet avis ?');">
                                                    <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($csrfToken); ?>">
                                                    <button type="submit" class="btn btn-sm btn-danger" title="Rejeter">
                                                        <i class="fas fa-times"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        <?php else: ?>
                                            <span class="text-muted">-</span>
                                        <?php endif; ?>
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
