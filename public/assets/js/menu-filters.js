/**
 * Menu Filters - Filtrage AJAX des menus
 */
document.addEventListener('DOMContentLoaded', function() {
    // Elements du DOM
    const filtersForm = document.getElementById('filtersForm');
    const menusContainer = document.getElementById('menusContainer');
    const menusCount = document.getElementById('menusCount');
    const loadingSpinner = document.getElementById('loadingSpinner');
    const resetFiltersBtn = document.getElementById('resetFilters');

    // Ne rien faire si les elements n'existent pas
    if (!filtersForm || !menusContainer) {
        return;
    }

    // Debounce pour eviter trop de requetes
    let debounceTimer;
    const debounce = (callback, time) => {
        clearTimeout(debounceTimer);
        debounceTimer = setTimeout(callback, time);
    };

    // Afficher le loading
    const showLoading = () => {
        if (loadingSpinner) {
            loadingSpinner.style.display = 'block';
        }
        menusContainer.style.opacity = '0.5';
    };

    // Cacher le loading
    const hideLoading = () => {
        if (loadingSpinner) {
            loadingSpinner.style.display = 'none';
        }
        menusContainer.style.opacity = '1';
    };

    // Construire la carte d'un menu
    const buildMenuCard = (menu) => {
        const ratingStars = [];
        for (let i = 1; i <= 5; i++) {
            const filled = i <= Math.round(menu.rating.average);
            ratingStars.push(`<i class="fas fa-star ${filled ? 'text-warning' : 'text-muted'}"></i>`);
        }

        const themeBadges = {
            'christmas': 'bg-danger',
            'easter': 'bg-purple',
            'classic': 'bg-success',
            'event': 'bg-primary',
            'seasonal': 'bg-warning text-dark'
        };
        const badgeClass = themeBadges[menu.theme] || 'bg-secondary';

        return `
            <div class="col-md-6 col-lg-4 mb-4">
                <div class="card h-100 shadow-sm menu-card">
                    ${menu.main_image_url ?
                        `<img src="${menu.main_image_url}" class="card-img-top" alt="${menu.title}" style="height: 200px; object-fit: cover;">` :
                        `<div class="card-img-top d-flex align-items-center justify-content-center" style="height: 200px; background: linear-gradient(135deg, #2E7D32, #4CAF50);">
                            <i class="fas fa-utensils fa-4x text-white"></i>
                        </div>`
                    }
                    <div class="card-body d-flex flex-column">
                        <div class="mb-2">
                            <span class="badge ${badgeClass} me-1">${menu.theme_label}</span>
                            <span class="badge bg-info">${menu.dietary_type_label}</span>
                        </div>
                        <h5 class="card-title">${menu.title}</h5>
                        <p class="card-text text-muted flex-grow-1">${menu.description.substring(0, 100)}...</p>
                        <div class="mb-2">
                            ${ratingStars.join('')}
                            ${menu.rating.count > 0 ? `<span class="text-muted ms-1">(${menu.rating.count})</span>` : ''}
                        </div>
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <span class="h5 mb-0" style="color: #FF8F00;">${menu.base_price.toFixed(2).replace('.', ',')} EUR</span>
                                <br><small class="text-muted">${menu.min_people} pers. min</small>
                            </div>
                            <a href="${menu.url}" class="btn" style="background-color: #2E7D32; color: white;">
                                <i class="fas fa-eye me-1"></i>Voir
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        `;
    };

    // Mettre a jour l'affichage des menus
    const updateMenus = (menus) => {
        if (menus.length === 0) {
            menusContainer.innerHTML = `
                <div class="col-12 text-center py-5">
                    <i class="fas fa-search fa-3x text-muted mb-3"></i>
                    <h4>Aucun menu trouve</h4>
                    <p class="text-muted">Essayez de modifier vos criteres de recherche.</p>
                </div>
            `;
        } else {
            menusContainer.innerHTML = menus.map(buildMenuCard).join('');
        }

        if (menusCount) {
            menusCount.textContent = menus.length;
        }
    };

    // Recuperer les valeurs des filtres
    const getFiltersValues = () => {
        const formData = new FormData(filtersForm);
        const params = new URLSearchParams();

        for (const [key, value] of formData.entries()) {
            if (value) {
                params.append(key, value);
            }
        }

        return params.toString();
    };

    // Charger les menus via AJAX
    const loadMenus = () => {
        showLoading();

        const queryString = getFiltersValues();
        const url = `/api/menus${queryString ? '?' + queryString : ''}`;

        fetch(url)
            .then(response => {
                if (!response.ok) {
                    throw new Error('Erreur reseau');
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    updateMenus(data.data);
                } else {
                    console.error('Erreur API:', data.error);
                }
            })
            .catch(error => {
                console.error('Erreur:', error);
                menusContainer.innerHTML = `
                    <div class="col-12 text-center py-5">
                        <i class="fas fa-exclamation-triangle fa-3x text-danger mb-3"></i>
                        <h4>Erreur de chargement</h4>
                        <p class="text-muted">Impossible de charger les menus. Veuillez rafraichir la page.</p>
                    </div>
                `;
            })
            .finally(() => {
                hideLoading();
            });
    };

    // Ecouter les changements de filtres
    const filterInputs = filtersForm.querySelectorAll('select, input');
    filterInputs.forEach(input => {
        input.addEventListener('change', () => {
            debounce(loadMenus, 300);
        });

        // Pour les inputs de type range ou number
        if (input.type === 'range' || input.type === 'number') {
            input.addEventListener('input', () => {
                debounce(loadMenus, 500);
            });
        }
    });

    // Reset des filtres
    if (resetFiltersBtn) {
        resetFiltersBtn.addEventListener('click', (e) => {
            e.preventDefault();
            filtersForm.reset();
            loadMenus();
        });
    }

    // Empecher le submit classique du formulaire
    filtersForm.addEventListener('submit', (e) => {
        e.preventDefault();
        loadMenus();
    });
});
