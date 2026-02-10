<?php
require_once __DIR__ . '/../../../Core/Auth.php';
require_once __DIR__ . '/../../../Core/Csrf.php';
?>

<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 style="font-family: 'Playfair Display', serif; color: #2E7D32;">
            <i class="fas fa-users-cog me-2"></i>Gestion des employes
        </h1>
        <a href="/admin/employee/create" class="btn" style="background-color: #2E7D32; color: white;">
            <i class="fas fa-plus me-2"></i>Nouvel employe
        </a>
    </div>

    <div class="card shadow">
        <div class="card-body p-0">
            <?php if (empty($employees)): ?>
                <p class="text-muted text-center py-5">Aucun employe enregistre.</p>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead style="background-color: #2E7D32; color: white;">
                            <tr>
                                <th>Employe</th>
                                <th>Email</th>
                                <th>Telephone</th>
                                <th>Role</th>
                                <th>Derniere connexion</th>
                                <th>Statut</th>
                                <th class="text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($employees as $employee): ?>
                                <tr class="<?php echo !$employee['is_active'] ? 'table-secondary' : ''; ?>">
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="rounded-circle d-flex align-items-center justify-content-center me-3"
                                                 style="width: 40px; height: 40px; background-color: <?php echo $employee['role'] === 'admin' ? '#dc3545' : '#2E7D32'; ?>; color: white;">
                                                <?php echo strtoupper(substr($employee['first_name'], 0, 1) . substr($employee['last_name'], 0, 1)); ?>
                                            </div>
                                            <div>
                                                <strong><?php echo htmlspecialchars($employee['first_name'] . ' ' . $employee['last_name']); ?></strong>
                                                <?php if ($employee['id'] == Auth::id()): ?>
                                                    <span class="badge bg-info ms-1">Vous</span>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <a href="mailto:<?php echo htmlspecialchars($employee['email']); ?>">
                                            <?php echo htmlspecialchars($employee['email']); ?>
                                        </a>
                                    </td>
                                    <td>
                                        <?php echo $employee['phone'] ? htmlspecialchars($employee['phone']) : '<span class="text-muted">-</span>'; ?>
                                    </td>
                                    <td>
                                        <?php if ($employee['role'] === 'admin'): ?>
                                            <span class="badge bg-danger">Administrateur</span>
                                        <?php else: ?>
                                            <span class="badge bg-primary">Employe</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if ($employee['last_login']): ?>
                                            <?php echo date('d/m/Y H:i', strtotime($employee['last_login'])); ?>
                                        <?php else: ?>
                                            <span class="text-muted">Jamais</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if ($employee['is_active']): ?>
                                            <span class="badge bg-success">Actif</span>
                                        <?php else: ?>
                                            <span class="badge bg-secondary">Inactif</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-end">
                                        <?php if ($employee['id'] != Auth::id()): ?>
                                            <form action="/admin/employee/toggle/<?php echo $employee['id']; ?>" method="POST" class="d-inline"
                                                  onsubmit="return confirm('<?php echo $employee['is_active'] ? 'Desactiver' : 'Activer'; ?> cet employe ?');">
                                                <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($csrfToken); ?>">
                                                <button type="submit" class="btn btn-sm <?php echo $employee['is_active'] ? 'btn-outline-warning' : 'btn-outline-success'; ?>">
                                                    <i class="fas fa-<?php echo $employee['is_active'] ? 'ban' : 'check'; ?>"></i>
                                                </button>
                                            </form>
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
