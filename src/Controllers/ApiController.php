<?php
require_once __DIR__ . '/../Views/core/Controller.php';
require_once __DIR__ . '/../Core/Auth.php';
require_once __DIR__ . '/../Models/Menu.php';

/**
 * ApiController - API JSON pour les requetes AJAX
 */
class ApiController extends Controller {

    /**
     * Liste des menus (avec filtres)
     * GET /api/menus
     */
    public function menus() {
        // Recuperer les filtres
        $filters = [
            'theme' => $_GET['theme'] ?? null,
            'dietary_type' => $_GET['dietary_type'] ?? null,
            'max_price' => $_GET['max_price'] ?? null,
            'min_price' => $_GET['min_price'] ?? null,
            'min_people' => $_GET['min_people'] ?? null,
            'sort' => $_GET['sort'] ?? 'created_at',
            'order' => $_GET['order'] ?? 'DESC'
        ];

        // Nettoyer les filtres vides
        $filters = array_filter($filters, function($value) {
            return $value !== null && $value !== '';
        });

        // Recuperer les menus
        $menus = Menu::search($filters);

        // Ajouter les informations supplementaires
        $result = [];
        foreach ($menus as $menu) {
            $rating = Menu::getAverageRating($menu['id']);
            $result[] = [
                'id' => (int)$menu['id'],
                'title' => $menu['title'],
                'description' => $menu['description'],
                'theme' => $menu['theme'],
                'theme_label' => Menu::getThemeLabels()[$menu['theme']] ?? $menu['theme'],
                'dietary_type' => $menu['dietary_type'],
                'dietary_type_label' => Menu::getDietaryTypeLabels()[$menu['dietary_type']] ?? $menu['dietary_type'],
                'min_people' => (int)$menu['min_people'],
                'base_price' => (float)$menu['base_price'],
                'price_per_extra_person' => (float)$menu['price_per_extra_person'],
                'main_image_url' => $menu['main_image_url'],
                'rating' => [
                    'average' => $rating['average'],
                    'count' => $rating['count']
                ],
                'url' => '/menu/show/' . $menu['id']
            ];
        }

        $this->json([
            'success' => true,
            'count' => count($result),
            'data' => $result
        ]);
    }

    /**
     * Detail d'un menu
     * GET /api/menu/{id}
     */
    public function menu($id = null) {
        if (!$id) {
            $this->json(['success' => false, 'error' => 'ID requis'], 400);
        }

        $menu = Menu::findById($id);

        if (!$menu || !$menu['is_available']) {
            $this->json(['success' => false, 'error' => 'Menu non trouve'], 404);
        }

        // Recuperer les donnees associees
        $dishes = Menu::getDishes($id);
        $images = Menu::getImages($id);
        $allergens = Menu::getAllergens($id);
        $rating = Menu::getAverageRating($id);

        $this->json([
            'success' => true,
            'data' => [
                'id' => (int)$menu['id'],
                'title' => $menu['title'],
                'description' => $menu['description'],
                'theme' => $menu['theme'],
                'theme_label' => Menu::getThemeLabels()[$menu['theme']] ?? $menu['theme'],
                'dietary_type' => $menu['dietary_type'],
                'dietary_type_label' => Menu::getDietaryTypeLabels()[$menu['dietary_type']] ?? $menu['dietary_type'],
                'min_people' => (int)$menu['min_people'],
                'base_price' => (float)$menu['base_price'],
                'price_per_extra_person' => (float)$menu['price_per_extra_person'],
                'discount_threshold' => (int)$menu['discount_threshold'],
                'stock_quantity' => (int)$menu['stock_quantity'],
                'min_order_delay_days' => (int)$menu['min_order_delay_days'],
                'storage_instructions' => $menu['storage_instructions'],
                'main_image_url' => $menu['main_image_url'],
                'images' => array_map(function($img) {
                    return $img['image_url'];
                }, $images),
                'dishes' => $dishes,
                'allergens' => $allergens,
                'rating' => [
                    'average' => $rating['average'],
                    'count' => $rating['count']
                ]
            ]
        ]);
    }

    /**
     * Calcul de prix pour une commande
     * POST /api/calculate-price
     */
    public function calculatePrice() {
        if (!$this->isPost()) {
            $this->json(['success' => false, 'error' => 'Methode POST requise'], 405);
        }

        $menuId = (int)($_POST['menu_id'] ?? 0);
        $numberOfPeople = (int)($_POST['number_of_people'] ?? 0);
        $postalCode = $_POST['postal_code'] ?? '';
        $distanceKm = (float)($_POST['distance_km'] ?? 0);

        if (!$menuId || !$numberOfPeople) {
            $this->json(['success' => false, 'error' => 'Parametres manquants'], 400);
        }

        require_once __DIR__ . '/../Models/Order.php';
        $price = Order::calculatePrice($menuId, $numberOfPeople, $postalCode, $distanceKm);

        if (!$price) {
            $this->json(['success' => false, 'error' => 'Menu introuvable'], 404);
        }

        $this->json([
            'success' => true,
            'data' => $price
        ]);
    }

    /**
     * Liste des themes disponibles
     * GET /api/themes
     */
    public function themes() {
        $themes = Menu::getThemes();
        $labels = Menu::getThemeLabels();

        $result = [];
        foreach ($themes as $theme) {
            $result[] = [
                'value' => $theme['theme'],
                'label' => $labels[$theme['theme']] ?? $theme['theme']
            ];
        }

        $this->json([
            'success' => true,
            'data' => $result
        ]);
    }

    /**
     * Liste des regimes disponibles
     * GET /api/dietary-types
     */
    public function dietaryTypes() {
        $types = Menu::getDietaryTypes();
        $labels = Menu::getDietaryTypeLabels();

        $result = [];
        foreach ($types as $type) {
            $result[] = [
                'value' => $type['dietary_type'],
                'label' => $labels[$type['dietary_type']] ?? $type['dietary_type']
            ];
        }

        $this->json([
            'success' => true,
            'data' => $result
        ]);
    }
}
