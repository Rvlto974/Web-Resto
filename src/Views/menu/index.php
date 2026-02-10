<div class="container py-5">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/">Accueil</a></li>
            <li class="breadcrumb-item active" aria-current="page">Nos Menus</li>
        </ol>
    </nav>

    <div class="row">
        <!-- Sidebar Filtres (Desktop) -->
        <div class="col-lg-3 mb-4">
            <div class="card shadow-sm">
                <div class="card-header" style="background-color: #5DA99A; color: white;">
                    <h5 class="mb-0"><i class="fas fa-filter me-2"></i>Filtres</h5>
                </div>
                <div class="card-body">
                    <form method="GET" action="/menu" id="filterForm">
                        <!-- Theme -->
                        <div class="mb-4">
                            <label class="form-label fw-bold">Theme</label>
                            <?php foreach ($themeLabels as $value => $label): ?>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="theme"
                                           id="theme_<?php echo $value; ?>" value="<?php echo $value; ?>"
                                           <?php echo ($filters['theme'] ?? '') === $value ? 'checked' : ''; ?>>
                                    <label class="form-check-label" for="theme_<?php echo $value; ?>">
                                        <?php echo htmlspecialchars($label); ?>
                                    </label>
                                </div>
                            <?php endforeach; ?>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="theme" id="theme_all" value=""
                                       <?php echo empty($filters['theme']) ? 'checked' : ''; ?>>
                                <label class="form-check-label" for="theme_all">Tous</label>
                            </div>
                        </div>

                        <!-- Regime alimentaire -->
                        <div class="mb-4">
                            <label class="form-label fw-bold">Regime alimentaire</label>
                            <?php foreach ($dietaryTypeLabels as $value => $label): ?>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="dietary_type"
                                           id="dietary_<?php echo $value; ?>" value="<?php echo $value; ?>"
                                           <?php echo ($filters['dietary_type'] ?? '') === $value ? 'checked' : ''; ?>>
                                    <label class="form-check-label" for="dietary_<?php echo $value; ?>">
                                        <?php echo htmlspecialchars($label); ?>
                                    </label>
                                </div>
                            <?php endforeach; ?>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="dietary_type" id="dietary_all" value=""
                                       <?php echo empty($filters['dietary_type']) ? 'checked' : ''; ?>>
                                <label class="form-check-label" for="dietary_all">Tous</label>
                            </div>
                        </div>

                        <!-- Prix -->
                        <div class="mb-4">
                            <label class="form-label fw-bold">Budget maximum</label>
                            <div class="input-group">
                                <input type="number" class="form-control" name="max_price"
                                       value="<?php echo htmlspecialchars($filters['max_price'] ?? ''); ?>"
                                       placeholder="Ex: 500" min="0" step="10">
                                <span class="input-group-text">EUR</span>
                            </div>
                        </div>

                        <!-- Nombre de personnes -->
                        <div class="mb-4">
                            <label class="form-label fw-bold">Nombre de convives</label>
                            <input type="number" class="form-control" name="min_people"
                                   value="<?php echo htmlspecialchars($filters['min_people'] ?? ''); ?>"
                                   placeholder="Ex: 10" min="1">
                            <div class="form-text">Minimum de personnes pour la commande</div>
                        </div>

                        <!-- Tri -->
                        <div class="mb-4">
                            <label class="form-label fw-bold">Trier par</label>
                            <select class="form-select" name="sort">
                                <option value="created_at" <?php echo ($filters['sort'] ?? '') === 'created_at' ? 'selected' : ''; ?>>Plus recents</option>
                                <option value="base_price" <?php echo ($filters['sort'] ?? '') === 'base_price' ? 'selected' : ''; ?>>Prix</option>
                                <option value="title" <?php echo ($filters['sort'] ?? '') === 'title' ? 'selected' : ''; ?>>Nom</option>
                                <option value="min_people" <?php echo ($filters['sort'] ?? '') === 'min_people' ? 'selected' : ''; ?>>Nb personnes</option>
                            </select>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn" style="background-color: #5DA99A; color: white;">
                                <i class="fas fa-search me-2"></i>Filtrer
                            </button>
                            <a href="/menu" class="btn btn-outline-secondary">
                                <i class="fas fa-times me-2"></i>Reinitialiser
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Liste des menus -->
        <div class="col-lg-9">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 style="font-family: 'Playfair Display', serif; color: #5DA99A;">
                    <i class="fas fa-utensils me-2"></i>Nos Menus
                </h1>
                <span class="badge bg-secondary fs-6"><?php echo count($menus); ?> menu(s)</span>
            </div>

            <?php if (empty($menus)): ?>
                <div class="alert alert-info">
                    <i class="fas fa-info-circle me-2"></i>
                    Aucun menu ne correspond a vos criteres.
                    <a href="/menu" class="alert-link">Voir tous les menus</a>
                </div>
            <?php else: ?>
                <div class="row row-cols-1 row-cols-md-2 row-cols-xl-3 g-4">
                    <?php foreach ($menus as $menu): ?>
                        <div class="col">
                            <div class="card h-100 menu-card">
                                <!-- Image -->
                                <div class="position-relative">
                                    <?php if ($menu['main_image_url']): ?>
                                        <img src="<?php echo htmlspecialchars($menu['main_image_url']); ?>"
                                             class="card-img-top" alt="<?php echo htmlspecialchars($menu['title']); ?>"
                                             style="height: 180px; object-fit: cover;">
                                    <?php else: ?>
                                        <div class="card-img-top d-flex align-items-center justify-content-center"
                                             style="height: 180px; background: linear-gradient(135deg, #5DA99A, #7DBFB2);">
                                            <i class="fas fa-utensils fa-3x text-white"></i>
                                        </div>
                                    <?php endif; ?>

                                    <!-- Badge theme -->
                                    <?php
                                    $themeBadges = [
                                        'christmas' => 'bg-danger',
                                        'easter' => 'bg-purple',
                                        'classic' => 'bg-success',
                                        'event' => 'bg-primary',
                                        'seasonal' => 'bg-warning text-dark'
                                    ];
                                    $badgeClass = $themeBadges[$menu['theme']] ?? 'bg-secondary';
                                    ?>
                                    <span class="badge <?php echo $badgeClass; ?> position-absolute top-0 end-0 m-2">
                                        <?php echo htmlspecialchars($themeLabels[$menu['theme']] ?? $menu['theme']); ?>
                                    </span>
                                </div>

                                <div class="card-body">
                                    <h5 class="card-title"><?php echo htmlspecialchars($menu['title']); ?></h5>

                                    <!-- Note -->
                                    <?php if ($menu['rating']['count'] > 0): ?>
                                        <div class="mb-2">
                                            <?php for ($i = 1; $i <= 5; $i++): ?>
                                                <i class="fas fa-star <?php echo $i <= round($menu['rating']['average']) ? 'text-warning' : 'text-muted'; ?>"></i>
                                            <?php endfor; ?>
                                            <small class="text-muted">(<?php echo $menu['rating']['count']; ?> avis)</small>
                                        </div>
                                    <?php endif; ?>

                                    <p class="card-text text-muted small">
                                        <?php echo htmlspecialchars(substr($menu['description'], 0, 100)); ?>...
                                    </p>

                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <span class="badge bg-light text-dark">
                                            <i class="fas fa-users me-1"></i><?php echo $menu['min_people']; ?> pers. min
                                        </span>
                                        <span class="badge bg-light text-dark">
                                            <?php echo htmlspecialchars($dietaryTypeLabels[$menu['dietary_type']] ?? $menu['dietary_type']); ?>
                                        </span>
                                    </div>

                                    <p class="price mb-0">
                                        A partir de <?php echo number_format($menu['base_price'], 2, ',', ' '); ?> EUR
                                    </p>
                                </div>

                                <div class="card-footer bg-white border-top-0">
                                    <a href="/menu/show/<?php echo $menu['id']; ?>" class="btn w-100" style="background-color: #5DA99A; color: white;">
                                        <i class="fas fa-eye me-2"></i>Voir le menu
                                    </a>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<style>
.bg-purple {
    background-color: #7B1FA2 !important;
}
</style>
