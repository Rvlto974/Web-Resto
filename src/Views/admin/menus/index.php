<?php
require_once __DIR__ . '/../../../Core/Auth.php';
require_once __DIR__ . '/../../../Core/Csrf.php';
?>

<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 style="font-family: 'Playfair Display', serif; color: #2E7D32;">
            <i class="fas fa-utensils me-2"></i>Gestion des menus
        </h1>
        <a href="/admin/menu/create" class="btn" style="background-color: #2E7D32; color: white;">
            <i class="fas fa-plus me-2"></i>Nouveau menu
        </a>
    </div>

    <div class="card shadow">
        <div class="card-body p-0">
            <?php if (empty($menus)): ?>
                <p class="text-muted text-center py-5">Aucun menu enregistre.</p>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead style="background-color: #2E7D32; color: white;">
                            <tr>
                                <th>Menu</th>
                                <th>Theme / Regime</th>
                                <th>Prix</th>
                                <th>Personnes min.</th>
                                <th>Stock</th>
                                <th>Note</th>
                                <th>Statut</th>
                                <th class="text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($menus as $menu): ?>
                                <tr class="<?php echo !$menu['is_available'] ? 'table-secondary' : ''; ?>">
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <?php if ($menu['main_image_url']): ?>
                                                <img src="<?php echo htmlspecialchars($menu['main_image_url']); ?>"
                                                     class="rounded me-3" style="width: 60px; height: 45px; object-fit: cover;">
                                            <?php else: ?>
                                                <div class="rounded me-3 d-flex align-items-center justify-content-center"
                                                     style="width: 60px; height: 45px; background-color: #E8F5E9;">
                                                    <i class="fas fa-utensils" style="color: #2E7D32;"></i>
                                                </div>
                                            <?php endif; ?>
                                            <div>
                                                <strong><?php echo htmlspecialchars($menu['title']); ?></strong>
                                                <br><small class="text-muted"><?php echo substr(htmlspecialchars($menu['description']), 0, 50); ?>...</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge bg-secondary me-1"><?php echo htmlspecialchars($themeLabels[$menu['theme']] ?? $menu['theme']); ?></span>
                                        <br>
                                        <span class="badge bg-info"><?php echo htmlspecialchars($dietaryTypeLabels[$menu['dietary_type']] ?? $menu['dietary_type']); ?></span>
                                    </td>
                                    <td>
                                        <strong><?php echo number_format($menu['base_price'], 2, ',', ' '); ?> EUR</strong>
                                        <?php if ($menu['price_per_extra_person'] > 0): ?>
                                            <br><small class="text-muted">+<?php echo number_format($menu['price_per_extra_person'], 2, ',', ' '); ?> EUR/pers.</small>
                                        <?php endif; ?>
                                    </td>
                                    <td><?php echo $menu['min_people']; ?></td>
                                    <td>
                                        <?php if ($menu['stock_quantity'] <= 2): ?>
                                            <span class="badge bg-danger"><?php echo $menu['stock_quantity']; ?></span>
                                        <?php elseif ($menu['stock_quantity'] <= 5): ?>
                                            <span class="badge bg-warning text-dark"><?php echo $menu['stock_quantity']; ?></span>
                                        <?php else: ?>
                                            <span class="badge bg-success"><?php echo $menu['stock_quantity']; ?></span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if ($menu['rating']['count'] > 0): ?>
                                            <i class="fas fa-star text-warning"></i>
                                            <?php echo $menu['rating']['average']; ?>/5
                                            <br><small class="text-muted">(<?php echo $menu['rating']['count']; ?> avis)</small>
                                        <?php else: ?>
                                            <span class="text-muted">-</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if ($menu['is_available']): ?>
                                            <span class="badge bg-success">Disponible</span>
                                        <?php else: ?>
                                            <span class="badge bg-danger">Indisponible</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-end">
                                        <div class="btn-group">
                                            <a href="/menu/show/<?php echo $menu['id']; ?>" class="btn btn-sm btn-outline-primary" target="_blank" title="Voir">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="/admin/menu/edit/<?php echo $menu['id']; ?>" class="btn btn-sm btn-outline-warning" title="Modifier">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <?php if ($menu['is_available']): ?>
                                                <form action="/admin/menu/delete/<?php echo $menu['id']; ?>" method="POST" class="d-inline"
                                                      onsubmit="return confirm('Desactiver ce menu ?');">
                                                    <input type="hidden" name="csrf_token" value="<?php echo Csrf::getToken(); ?>">
                                                    <button type="submit" class="btn btn-sm btn-outline-danger" title="Desactiver">
                                                        <i class="fas fa-ban"></i>
                                                    </button>
                                                </form>
                                            <?php endif; ?>
                                        </div>
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
