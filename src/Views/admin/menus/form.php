<?php
require_once __DIR__ . '/../../../Core/Auth.php';
require_once __DIR__ . '/../../../Core/Csrf.php';
$isEdit = !empty($menu);
?>

<div class="container-fluid py-4">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/admin/dashboard">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="/admin/menus">Menus</a></li>
            <li class="breadcrumb-item active"><?php echo $isEdit ? 'Modifier' : 'Creer'; ?></li>
        </ol>
    </nav>

    <h1 class="mb-4" style="font-family: 'Playfair Display', serif; color: #5DA99A;">
        <i class="fas fa-<?php echo $isEdit ? 'edit' : 'plus'; ?> me-2"></i>
        <?php echo $isEdit ? 'Modifier le menu' : 'Creer un menu'; ?>
    </h1>

    <div class="card shadow">
        <div class="card-body">
            <form action="<?php echo $isEdit ? '/admin/menuUpdate/' . $menu['id'] : '/admin/menuStore'; ?>" method="POST">
                <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($csrfToken); ?>">

                <div class="row">
                    <!-- Informations de base -->
                    <div class="col-lg-8">
                        <h5 class="border-bottom pb-2 mb-3" style="color: #5DA99A;">
                            <i class="fas fa-info-circle me-2"></i>Informations generales
                        </h5>

                        <div class="mb-3">
                            <label for="title" class="form-label">Titre du menu *</label>
                            <input type="text" class="form-control" id="title" name="title" required
                                   value="<?php echo htmlspecialchars($menu['title'] ?? ''); ?>">
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Description *</label>
                            <textarea class="form-control" id="description" name="description" rows="4" required><?php echo htmlspecialchars($menu['description'] ?? ''); ?></textarea>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="theme" class="form-label">Theme</label>
                                <select class="form-select" id="theme" name="theme">
                                    <?php foreach ($themes as $value => $label): ?>
                                        <option value="<?php echo $value; ?>" <?php echo ($menu['theme'] ?? 'classic') === $value ? 'selected' : ''; ?>>
                                            <?php echo htmlspecialchars($label); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="dietary_type" class="form-label">Regime alimentaire</label>
                                <select class="form-select" id="dietary_type" name="dietary_type">
                                    <?php foreach ($dietaryTypes as $value => $label): ?>
                                        <option value="<?php echo $value; ?>" <?php echo ($menu['dietary_type'] ?? 'classic') === $value ? 'selected' : ''; ?>>
                                            <?php echo htmlspecialchars($label); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="main_image_url" class="form-label">URL de l'image principale</label>
                            <input type="url" class="form-control" id="main_image_url" name="main_image_url"
                                   value="<?php echo htmlspecialchars($menu['main_image_url'] ?? ''); ?>"
                                   placeholder="https://...">
                        </div>

                        <div class="mb-3">
                            <label for="storage_instructions" class="form-label">Instructions de conservation</label>
                            <textarea class="form-control" id="storage_instructions" name="storage_instructions" rows="2"><?php echo htmlspecialchars($menu['storage_instructions'] ?? ''); ?></textarea>
                        </div>
                    </div>

                    <!-- Tarification et disponibilite -->
                    <div class="col-lg-4">
                        <h5 class="border-bottom pb-2 mb-3" style="color: #5DA99A;">
                            <i class="fas fa-euro-sign me-2"></i>Tarification
                        </h5>

                        <div class="mb-3">
                            <label for="base_price" class="form-label">Prix de base (EUR) *</label>
                            <input type="number" class="form-control" id="base_price" name="base_price"
                                   step="0.01" min="0" required
                                   value="<?php echo $menu['base_price'] ?? ''; ?>">
                        </div>

                        <div class="mb-3">
                            <label for="min_people" class="form-label">Nombre minimum de personnes *</label>
                            <input type="number" class="form-control" id="min_people" name="min_people"
                                   min="1" required
                                   value="<?php echo $menu['min_people'] ?? 1; ?>">
                        </div>

                        <div class="mb-3">
                            <label for="price_per_extra_person" class="form-label">Prix par personne supplementaire (EUR)</label>
                            <input type="number" class="form-control" id="price_per_extra_person" name="price_per_extra_person"
                                   step="0.01" min="0"
                                   value="<?php echo $menu['price_per_extra_person'] ?? 0; ?>">
                        </div>

                        <div class="mb-3">
                            <label for="discount_threshold" class="form-label">Seuil reduction 10% (pers. supplementaires)</label>
                            <input type="number" class="form-control" id="discount_threshold" name="discount_threshold"
                                   min="0"
                                   value="<?php echo $menu['discount_threshold'] ?? 5; ?>">
                            <div class="form-text">Reduction de 10% appliquee a partir de ce nombre de personnes supplementaires</div>
                        </div>

                        <hr>

                        <h5 class="border-bottom pb-2 mb-3" style="color: #5DA99A;">
                            <i class="fas fa-cog me-2"></i>Disponibilite
                        </h5>

                        <div class="mb-3">
                            <label for="stock_quantity" class="form-label">Stock disponible</label>
                            <input type="number" class="form-control" id="stock_quantity" name="stock_quantity"
                                   min="0"
                                   value="<?php echo $menu['stock_quantity'] ?? 10; ?>">
                        </div>

                        <div class="mb-3">
                            <label for="min_order_delay_days" class="form-label">Delai minimum de commande (jours)</label>
                            <input type="number" class="form-control" id="min_order_delay_days" name="min_order_delay_days"
                                   min="1"
                                   value="<?php echo $menu['min_order_delay_days'] ?? 3; ?>">
                        </div>

                        <div class="mb-3 form-check form-switch">
                            <input type="checkbox" class="form-check-input" id="is_available" name="is_available"
                                   <?php echo ($menu['is_available'] ?? true) ? 'checked' : ''; ?>>
                            <label class="form-check-label" for="is_available">Menu disponible a la vente</label>
                        </div>
                    </div>
                </div>

                <hr>

                <div class="d-flex justify-content-between">
                    <a href="/admin/menus" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left me-2"></i>Retour
                    </a>
                    <button type="submit" class="btn" style="background-color: #5DA99A; color: white;">
                        <i class="fas fa-save me-2"></i><?php echo $isEdit ? 'Enregistrer les modifications' : 'Creer le menu'; ?>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
