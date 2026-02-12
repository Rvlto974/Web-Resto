<div class="container py-5">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/">Accueil</a></li>
            <li class="breadcrumb-item active" aria-current="page">Nos Menus</li>
        </ol>
    </nav>

    <div class="row">
        <!-- Bouton filtres mobile -->
        <div class="col-12">
            <button class="btn mobile-filter-btn" style="background-color: #5DA99A; color: white;" onclick="toggleFilters()">
                <i class="fas fa-filter me-2"></i>Filtres
                <i class="fas fa-chevron-down ms-2" id="filterArrow"></i>
            </button>
        </div>

        <!-- Sidebar Filtres -->
        <div class="col-lg-3 mb-4 filter-sidebar" id="filterSidebar">
            <div class="card shadow-sm">
                <div class="card-header" style="background-color: #5DA99A; color: white;">
                    <h5 class="mb-0"><i class="fas fa-filter me-2"></i>Filtres</h5>
                </div>
                <div class="card-body">
                    <!-- Theme -->
                    <div class="mb-4">
                        <label class="form-label fw-bold">Theme</label>
                        <select class="form-select filter-input" id="filterTheme">
                            <option value="">Tous les themes</option>
                            <?php foreach ($themeLabels as $value => $label): ?>
                                <option value="<?php echo $value; ?>"><?php echo htmlspecialchars($label); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <!-- Regime alimentaire -->
                    <div class="mb-4">
                        <label class="form-label fw-bold">Regime alimentaire</label>
                        <select class="form-select filter-input" id="filterDietary">
                            <option value="">Tous les regimes</option>
                            <?php foreach ($dietaryTypeLabels as $value => $label): ?>
                                <option value="<?php echo $value; ?>"><?php echo htmlspecialchars($label); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <!-- Fourchette de prix -->
                    <div class="mb-4">
                        <label class="form-label fw-bold">Fourchette de prix</label>
                        <div class="row g-2">
                            <div class="col-6">
                                <div class="input-group input-group-sm">
                                    <span class="input-group-text">Min</span>
                                    <input type="number" class="form-control filter-input" id="filterMinPrice"
                                           placeholder="0" min="0" step="10">
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="input-group input-group-sm">
                                    <span class="input-group-text">Max</span>
                                    <input type="number" class="form-control filter-input" id="filterMaxPrice"
                                           placeholder="1000" min="0" step="10">
                                </div>
                            </div>
                        </div>
                        <div class="mt-2">
                            <input type="range" class="form-range" id="priceRange" min="0" max="1000" step="50" value="1000">
                            <div class="d-flex justify-content-between">
                                <small class="text-muted">0 EUR</small>
                                <small class="text-muted" id="priceRangeValue">1000 EUR</small>
                            </div>
                        </div>
                    </div>

                    <!-- Nombre de personnes -->
                    <div class="mb-4">
                        <label class="form-label fw-bold">Nombre de convives minimum</label>
                        <select class="form-select filter-input" id="filterPeople">
                            <option value="">Peu importe</option>
                            <option value="4">4 personnes ou moins</option>
                            <option value="6">6 personnes ou moins</option>
                            <option value="8">8 personnes ou moins</option>
                            <option value="10">10 personnes ou moins</option>
                            <option value="15">15 personnes ou moins</option>
                            <option value="20">20 personnes ou moins</option>
                        </select>
                    </div>

                    <!-- Tri -->
                    <div class="mb-4">
                        <label class="form-label fw-bold">Trier par</label>
                        <select class="form-select filter-input" id="filterSort">
                            <option value="created_at">Plus recents</option>
                            <option value="base_price">Prix croissant</option>
                            <option value="base_price_desc">Prix decroissant</option>
                            <option value="title">Nom A-Z</option>
                            <option value="min_people">Nb personnes</option>
                        </select>
                    </div>

                    <div class="d-grid gap-2">
                        <button type="button" class="btn" style="background-color: #5DA99A; color: white;" id="btnApplyFilters">
                            <i class="fas fa-search me-2"></i>Appliquer
                        </button>
                        <button type="button" class="btn btn-outline-secondary" id="btnResetFilters">
                            <i class="fas fa-times me-2"></i>Reinitialiser
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Liste des menus -->
        <div class="col-lg-9">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 style="font-family: 'Playfair Display', serif; color: #5DA99A;">
                    <i class="fas fa-utensils me-2"></i>Nos Menus
                </h1>
                <span class="badge bg-secondary fs-6" id="menuCount"><?php echo count($menus); ?> menu(s)</span>
            </div>

            <!-- Loader -->
            <div class="text-center py-5 d-none" id="loader">
                <div class="spinner-border" style="color: #5DA99A;" role="status">
                    <span class="visually-hidden">Chargement...</span>
                </div>
                <p class="mt-2 text-muted">Recherche en cours...</p>
            </div>

            <!-- Conteneur des menus -->
            <div id="menusContainer">
                <?php if (empty($menus)): ?>
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i>
                        Aucun menu ne correspond a vos criteres.
                    </div>
                <?php else: ?>
                    <div class="row row-cols-1 row-cols-md-2 row-cols-xl-3 g-4" id="menusGrid">
                        <?php foreach ($menus as $menu): ?>
                            <div class="col">
                                <div class="card h-100 menu-card">
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
</div>

<style>
.bg-purple {
    background-color: #7B1FA2 !important;
}
.menu-card {
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}
.menu-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 30px rgba(0,0,0,0.15) !important;
}
.price {
    color: #FF8F00;
    font-weight: 700;
    font-size: 1.1rem;
}

/* Mobile filter toggle */
.mobile-filter-btn {
    display: none;
    width: 100%;
    margin-bottom: 15px;
}

@media (max-width: 991px) {
    .mobile-filter-btn {
        display: block;
    }
    .filter-sidebar {
        display: none;
    }
    .filter-sidebar.show {
        display: block;
    }
}
</style>

<script>
// Toggle filters on mobile
function toggleFilters() {
    const sidebar = document.getElementById('filterSidebar');
    const arrow = document.getElementById('filterArrow');
    sidebar.classList.toggle('show');
    arrow.classList.toggle('fa-chevron-down');
    arrow.classList.toggle('fa-chevron-up');
}

document.addEventListener('DOMContentLoaded', function() {
    const loader = document.getElementById('loader');
    const menusContainer = document.getElementById('menusContainer');
    const menuCount = document.getElementById('menuCount');
    const priceRange = document.getElementById('priceRange');
    const priceRangeValue = document.getElementById('priceRangeValue');
    const filterMaxPrice = document.getElementById('filterMaxPrice');

    // Theme badges mapping
    const themeBadges = {
        'christmas': 'bg-danger',
        'easter': 'bg-purple',
        'classic': 'bg-success',
        'event': 'bg-primary',
        'seasonal': 'bg-warning text-dark'
    };

    // Synchroniser le slider avec le champ max price
    priceRange.addEventListener('input', function() {
        priceRangeValue.textContent = this.value + ' EUR';
        filterMaxPrice.value = this.value;
    });

    filterMaxPrice.addEventListener('input', function() {
        if (this.value && this.value <= 1000) {
            priceRange.value = this.value;
            priceRangeValue.textContent = this.value + ' EUR';
        }
    });

    // Fonction pour filtrer les menus via Ajax
    function filterMenus() {
        const theme = document.getElementById('filterTheme').value;
        const dietary = document.getElementById('filterDietary').value;
        const minPrice = document.getElementById('filterMinPrice').value;
        const maxPrice = document.getElementById('filterMaxPrice').value;
        const minPeople = document.getElementById('filterPeople').value;
        const sortValue = document.getElementById('filterSort').value;

        // Construire les parametres
        let params = new URLSearchParams();
        if (theme) params.append('theme', theme);
        if (dietary) params.append('dietary_type', dietary);
        if (minPrice) params.append('min_price', minPrice);
        if (maxPrice) params.append('max_price', maxPrice);
        if (minPeople) params.append('min_people', minPeople);

        // Gerer le tri
        if (sortValue === 'base_price_desc') {
            params.append('sort', 'base_price');
            params.append('order', 'DESC');
        } else if (sortValue === 'base_price') {
            params.append('sort', 'base_price');
            params.append('order', 'ASC');
        } else {
            params.append('sort', sortValue);
        }

        // Afficher le loader
        loader.classList.remove('d-none');
        menusContainer.style.opacity = '0.5';

        // Appel Ajax
        fetch('/menu/filter?' + params.toString())
            .then(response => response.json())
            .then(data => {
                loader.classList.add('d-none');
                menusContainer.style.opacity = '1';

                if (data.success) {
                    menuCount.textContent = data.count + ' menu(s)';
                    renderMenus(data.menus);
                }
            })
            .catch(error => {
                console.error('Erreur:', error);
                loader.classList.add('d-none');
                menusContainer.style.opacity = '1';
            });
    }

    // Fonction pour afficher les menus
    function renderMenus(menus) {
        if (menus.length === 0) {
            menusContainer.innerHTML = `
                <div class="alert alert-info">
                    <i class="fas fa-info-circle me-2"></i>
                    Aucun menu ne correspond a vos criteres.
                </div>
            `;
            return;
        }

        let html = '<div class="row row-cols-1 row-cols-md-2 row-cols-xl-3 g-4">';

        menus.forEach(menu => {
            const badgeClass = themeBadges[menu.theme] || 'bg-secondary';
            const imageHtml = menu.main_image_url
                ? `<img src="${menu.main_image_url}" class="card-img-top" alt="${menu.title}" style="height: 180px; object-fit: cover;">`
                : `<div class="card-img-top d-flex align-items-center justify-content-center" style="height: 180px; background: linear-gradient(135deg, #5DA99A, #7DBFB2);"><i class="fas fa-utensils fa-3x text-white"></i></div>`;

            let starsHtml = '';
            if (menu.rating && menu.rating.count > 0) {
                for (let i = 1; i <= 5; i++) {
                    starsHtml += `<i class="fas fa-star ${i <= Math.round(menu.rating.average) ? 'text-warning' : 'text-muted'}"></i>`;
                }
                starsHtml = `<div class="mb-2">${starsHtml} <small class="text-muted">(${menu.rating.count} avis)</small></div>`;
            }

            const price = parseFloat(menu.base_price).toFixed(2).replace('.', ',');

            html += `
                <div class="col">
                    <div class="card h-100 menu-card">
                        <div class="position-relative">
                            ${imageHtml}
                            <span class="badge ${badgeClass} position-absolute top-0 end-0 m-2">${menu.theme_label}</span>
                        </div>
                        <div class="card-body">
                            <h5 class="card-title">${menu.title}</h5>
                            ${starsHtml}
                            <p class="card-text text-muted small">${menu.description.substring(0, 100)}...</p>
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="badge bg-light text-dark"><i class="fas fa-users me-1"></i>${menu.min_people} pers. min</span>
                                <span class="badge bg-light text-dark">${menu.dietary_type_label}</span>
                            </div>
                            <p class="price mb-0">A partir de ${price} EUR</p>
                        </div>
                        <div class="card-footer bg-white border-top-0">
                            <a href="/menu/show/${menu.id}" class="btn w-100" style="background-color: #5DA99A; color: white;">
                                <i class="fas fa-eye me-2"></i>Voir le menu
                            </a>
                        </div>
                    </div>
                </div>
            `;
        });

        html += '</div>';
        menusContainer.innerHTML = html;
    }

    // Evenements
    document.getElementById('btnApplyFilters').addEventListener('click', filterMenus);

    document.getElementById('btnResetFilters').addEventListener('click', function() {
        document.getElementById('filterTheme').value = '';
        document.getElementById('filterDietary').value = '';
        document.getElementById('filterMinPrice').value = '';
        document.getElementById('filterMaxPrice').value = '';
        document.getElementById('filterPeople').value = '';
        document.getElementById('filterSort').value = 'created_at';
        priceRange.value = 1000;
        priceRangeValue.textContent = '1000 EUR';
        filterMenus();
    });

    // Filtrage automatique lors du changement des selects
    document.querySelectorAll('.filter-input').forEach(input => {
        input.addEventListener('change', filterMenus);
    });
});
</script>
