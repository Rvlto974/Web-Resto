<?php
require_once __DIR__ . '/../Views/core/Controller.php';
require_once __DIR__ . '/../Core/Auth.php';
require_once __DIR__ . '/../Models/Menu.php';

/**
 * MenuController - Affichage des menus
 */
class MenuController extends Controller {

    /**
     * Liste des menus avec filtres
     */
    public function index() {
        // Recuperer les filtres depuis l'URL
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

        // Ajouter la note moyenne a chaque menu
        foreach ($menus as &$menu) {
            $menu['rating'] = Menu::getAverageRating($menu['id']);
        }

        // Recuperer les options de filtres
        $themes = Menu::getThemes();
        $dietaryTypes = Menu::getDietaryTypes();

        $this->view('menu/index', [
            'title' => 'Nos Menus',
            'menus' => $menus,
            'filters' => $filters,
            'themes' => $themes,
            'dietaryTypes' => $dietaryTypes,
            'themeLabels' => Menu::getThemeLabels(),
            'dietaryTypeLabels' => Menu::getDietaryTypeLabels()
        ]);
    }

    /**
     * API - Filtrer les menus (Ajax)
     */
    public function filter() {
        // Recuperer les filtres depuis la requete
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

        // Ajouter la note moyenne et les labels
        $themeLabels = Menu::getThemeLabels();
        $dietaryTypeLabels = Menu::getDietaryTypeLabels();

        foreach ($menus as &$menu) {
            $menu['rating'] = Menu::getAverageRating($menu['id']);
            $menu['theme_label'] = $themeLabels[$menu['theme']] ?? $menu['theme'];
            $menu['dietary_type_label'] = $dietaryTypeLabels[$menu['dietary_type']] ?? $menu['dietary_type'];
        }

        // Retourner en JSON
        $this->json([
            'success' => true,
            'count' => count($menus),
            'menus' => $menus
        ]);
    }

    /**
     * Detail d'un menu
     * @param int $id
     */
    public function show($id = null) {
        if (!$id) {
            Auth::setFlash('error', 'Menu non trouve.');
            $this->redirect('/menu');
        }

        $menu = Menu::findById($id);

        if (!$menu || !$menu['is_available']) {
            Auth::setFlash('error', 'Ce menu n\'est pas disponible.');
            $this->redirect('/menu');
        }

        // Recuperer les donnees associees
        $dishes = Menu::getDishes($id);
        $images = Menu::getImages($id);
        $allergens = Menu::getAllergens($id);
        $reviews = Menu::getReviews($id, 5);
        $rating = Menu::getAverageRating($id);

        // Organiser les plats par categorie
        $dishesByCategory = [
            'starter' => [],
            'main' => [],
            'dessert' => [],
            'drink' => [],
            'side' => []
        ];
        foreach ($dishes as $dish) {
            $cat = $dish['category'] ?? 'main';
            if (isset($dishesByCategory[$cat])) {
                $dishesByCategory[$cat][] = $dish;
            }
        }

        // Calculer le prix pour le nombre minimum
        $priceCalculation = Menu::calculatePrice($id, $menu['min_people']);

        $this->view('menu/show', [
            'title' => $menu['title'],
            'menu' => $menu,
            'dishes' => $dishes,
            'dishesByCategory' => $dishesByCategory,
            'images' => $images,
            'allergens' => $allergens,
            'reviews' => $reviews,
            'rating' => $rating,
            'priceCalculation' => $priceCalculation,
            'themeLabels' => Menu::getThemeLabels(),
            'dietaryTypeLabels' => Menu::getDietaryTypeLabels(),
            'categoryLabels' => [
                'starter' => 'Entrees',
                'main' => 'Plats',
                'dessert' => 'Desserts',
                'drink' => 'Boissons',
                'side' => 'Accompagnements'
            ]
        ]);
    }
}
