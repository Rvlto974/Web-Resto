<?php
require_once __DIR__ . '/../../../Core/Auth.php';
require_once __DIR__ . '/../../../Core/Csrf.php';
?>

<div class="container-fluid py-4">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/admin/dashboard">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="/admin/employees">Employes</a></li>
            <li class="breadcrumb-item active">Creer</li>
        </ol>
    </nav>

    <div class="row justify-content-center">
        <div class="col-lg-6">
            <div class="card shadow">
                <div class="card-header" style="background-color: #5DA99A; color: white;">
                    <h4 class="mb-0">
                        <i class="fas fa-user-plus me-2"></i>Creer un employe
                    </h4>
                </div>
                <div class="card-body">
                    <form action="/admin/employee/store" method="POST">
                        <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($csrfToken); ?>">

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="first_name" class="form-label">Prenom *</label>
                                <input type="text" class="form-control" id="first_name" name="first_name" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="last_name" class="form-label">Nom *</label>
                                <input type="text" class="form-control" id="last_name" name="last_name" required>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label">Email *</label>
                            <input type="email" class="form-control" id="email" name="email" required>
                        </div>

                        <div class="mb-3">
                            <label for="phone" class="form-label">Telephone</label>
                            <input type="tel" class="form-control" id="phone" name="phone"
                                   pattern="[0-9]{10}" placeholder="0612345678">
                        </div>

                        <div class="mb-3">
                            <label for="role" class="form-label">Role *</label>
                            <select class="form-select" id="role" name="role" required>
                                <option value="employee">Employe</option>
                                <option value="admin">Administrateur</option>
                            </select>
                            <div class="form-text">
                                <strong>Employe</strong> : Peut gerer les menus, commandes et avis.<br>
                                <strong>Administrateur</strong> : Acces complet incluant les statistiques et la gestion des employes.
                            </div>
                        </div>

                        <div class="alert alert-info" role="alert">
                            <i class="fas fa-info-circle me-2"></i>
                            Un mot de passe temporaire sera genere automatiquement. L'employe devra le modifier a sa premiere connexion.
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="/admin/employees" class="btn btn-outline-secondary">
                                <i class="fas fa-arrow-left me-2"></i>Retour
                            </a>
                            <button type="submit" class="btn" style="background-color: #5DA99A; color: white;">
                                <i class="fas fa-save me-2"></i>Creer l'employe
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
