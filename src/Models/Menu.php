<?php
require_once __DIR__ . '/../Views/core/Model.php';

/**
 * Modele Menu - Gestion des menus du traiteur
 */
class Menu extends Model {

    /**
     * Trouve un menu par son ID avec toutes ses relations
     * @param int $id
     * @return array|null
     */
    public static function findById($id) {
        return self::queryOne(
            "SELECT * FROM menus WHERE id = ?",
            [$id]
        );
    }

    /**
     * Trouve tous les menus disponibles
     * @return array
     */
    public static function findAvailable() {
        return self::query(
            "SELECT * FROM menus WHERE is_available = 1 AND stock_quantity > 0 ORDER BY created_at DESC"
        );
    }

    /**
     * Trouve tous les menus (admin)
     * @return array
     */
    public static function findAll() {
        return self::query("SELECT * FROM menus ORDER BY created_at DESC");
    }

    /**
     * Recherche avec filtres
     * @param array $filters
     * @return array
     */
    public static function search($filters = []) {
        $sql = "SELECT * FROM menus WHERE is_available = 1 AND stock_quantity > 0";
        $params = [];

        // Filtre par theme
        if (!empty($filters['theme'])) {
            $sql .= " AND theme = ?";
            $params[] = $filters['theme'];
        }

        // Filtre par regime alimentaire
        if (!empty($filters['dietary_type'])) {
            $sql .= " AND dietary_type = ?";
            $params[] = $filters['dietary_type'];
        }

        // Filtre par prix maximum
        if (!empty($filters['max_price'])) {
            $sql .= " AND base_price <= ?";
            $params[] = (float)$filters['max_price'];
        }

        // Filtre par prix minimum
        if (!empty($filters['min_price'])) {
            $sql .= " AND base_price >= ?";
            $params[] = (float)$filters['min_price'];
        }

        // Filtre par nombre de personnes minimum
        if (!empty($filters['min_people'])) {
            $sql .= " AND min_people <= ?";
            $params[] = (int)$filters['min_people'];
        }

        // Tri
        $orderBy = $filters['sort'] ?? 'created_at';
        $orderDir = $filters['order'] ?? 'DESC';
        $allowedSort = ['base_price', 'title', 'created_at', 'min_people'];
        $allowedOrder = ['ASC', 'DESC'];

        if (in_array($orderBy, $allowedSort) && in_array(strtoupper($orderDir), $allowedOrder)) {
            $sql .= " ORDER BY $orderBy $orderDir";
        } else {
            $sql .= " ORDER BY created_at DESC";
        }

        return self::query($sql, $params);
    }

    /**
     * Recupere les plats d'un menu
     * @param int $menuId
     * @return array
     */
    public static function getDishes($menuId) {
        return self::query(
            "SELECT d.*, md.display_order
             FROM dishes d
             INNER JOIN menu_dishes md ON d.id = md.dish_id
             WHERE md.menu_id = ?
             ORDER BY md.display_order, d.category",
            [$menuId]
        );
    }

    /**
     * Recupere les images d'un menu
     * @param int $menuId
     * @return array
     */
    public static function getImages($menuId) {
        return self::query(
            "SELECT * FROM menu_images WHERE menu_id = ? ORDER BY display_order",
            [$menuId]
        );
    }

    /**
     * Recupere les allergenes d'un menu (via les plats)
     * @param int $menuId
     * @return array
     */
    public static function getAllergens($menuId) {
        return self::query(
            "SELECT DISTINCT a.*
             FROM allergens a
             INNER JOIN dish_allergens da ON a.id = da.allergen_id
             INNER JOIN menu_dishes md ON da.dish_id = md.dish_id
             WHERE md.menu_id = ?
             ORDER BY a.name",
            [$menuId]
        );
    }

    /**
     * Recupere les avis approuves d'un menu
     * @param int $menuId
     * @param int $limit
     * @return array
     */
    public static function getReviews($menuId, $limit = 10) {
        return self::query(
            "SELECT r.*, u.first_name, u.last_name
             FROM reviews r
             INNER JOIN users u ON r.user_id = u.id
             WHERE r.menu_id = ? AND r.is_approved = 1
             ORDER BY r.created_at DESC
             LIMIT ?",
            [$menuId, $limit]
        );
    }

    /**
     * Calcule la note moyenne d'un menu
     * @param int $menuId
     * @return array ['average' => float, 'count' => int]
     */
    public static function getAverageRating($menuId) {
        $result = self::queryOne(
            "SELECT AVG(rating) as average, COUNT(*) as count
             FROM reviews
             WHERE menu_id = ? AND is_approved = 1",
            [$menuId]
        );

        return [
            'average' => $result['average'] ? round($result['average'], 1) : 0,
            'count' => (int)$result['count']
        ];
    }

    /**
     * Recupere les themes distincts
     * @return array
     */
    public static function getThemes() {
        return self::query(
            "SELECT DISTINCT theme FROM menus WHERE is_available = 1 ORDER BY theme"
        );
    }

    /**
     * Recupere les regimes distincts
     * @return array
     */
    public static function getDietaryTypes() {
        return self::query(
            "SELECT DISTINCT dietary_type FROM menus WHERE is_available = 1 ORDER BY dietary_type"
        );
    }

    /**
     * Calcule le prix pour un nombre de personnes
     * @param int $menuId
     * @param int $numberOfPeople
     * @return array
     */
    public static function calculatePrice($menuId, $numberOfPeople) {
        $menu = self::findById($menuId);
        if (!$menu) {
            return null;
        }

        $minPeople = $menu['min_people'];
        $basePrice = (float)$menu['base_price'];
        $pricePerExtra = (float)$menu['price_per_extra_person'];
        $discountThreshold = (int)$menu['discount_threshold'];

        // Nombre de personnes supplementaires
        $extraPeople = max(0, $numberOfPeople - $minPeople);

        // Prix de base + extras
        $subtotal = $basePrice + ($extraPeople * $pricePerExtra);

        // Reduction de 10% si +5 personnes au-dessus du minimum
        $discount = 0;
        if ($extraPeople >= $discountThreshold) {
            $discount = $subtotal * 0.10;
            $subtotal -= $discount;
        }

        return [
            'base_price' => $basePrice,
            'extra_people' => $extraPeople,
            'extra_cost' => $extraPeople * $pricePerExtra,
            'discount' => $discount,
            'discount_applied' => $extraPeople >= $discountThreshold,
            'subtotal' => round($subtotal, 2)
        ];
    }

    /**
     * Cree un nouveau menu (admin)
     * @param array $data
     * @return int
     */
    public static function create($data) {
        $sql = "INSERT INTO menus (
            title, description, theme, dietary_type, min_people,
            base_price, price_per_extra_person, discount_threshold,
            stock_quantity, is_available, min_order_delay_days,
            storage_instructions, main_image_url, created_at
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())";

        self::execute($sql, [
            $data['title'],
            $data['description'],
            $data['theme'],
            $data['dietary_type'],
            $data['min_people'],
            $data['base_price'],
            $data['price_per_extra_person'] ?? 0,
            $data['discount_threshold'] ?? 5,
            $data['stock_quantity'] ?? 10,
            $data['is_available'] ?? 1,
            $data['min_order_delay_days'] ?? 2,
            $data['storage_instructions'] ?? null,
            $data['main_image_url'] ?? null
        ]);

        return self::lastInsertId();
    }

    /**
     * Met a jour un menu
     * @param int $id
     * @param array $data
     * @return bool
     */
    public static function update($id, $data) {
        $fields = [];
        $values = [];

        $allowedFields = [
            'title', 'description', 'theme', 'dietary_type', 'min_people',
            'base_price', 'price_per_extra_person', 'discount_threshold',
            'stock_quantity', 'is_available', 'min_order_delay_days',
            'storage_instructions', 'main_image_url'
        ];

        foreach ($allowedFields as $field) {
            if (isset($data[$field])) {
                $fields[] = "$field = ?";
                $values[] = $data[$field];
            }
        }

        if (empty($fields)) {
            return false;
        }

        $fields[] = "updated_at = NOW()";
        $values[] = $id;

        $sql = "UPDATE menus SET " . implode(', ', $fields) . " WHERE id = ?";
        return self::execute($sql, $values);
    }

    /**
     * Decremente le stock
     * @param int $id
     * @param int $quantity
     * @return bool
     */
    public static function decrementStock($id, $quantity = 1) {
        return self::execute(
            "UPDATE menus SET stock_quantity = stock_quantity - ? WHERE id = ? AND stock_quantity >= ?",
            [$quantity, $id, $quantity]
        );
    }

    /**
     * Supprime un menu (soft delete en le rendant indisponible)
     * @param int $id
     * @return bool
     */
    public static function delete($id) {
        return self::execute(
            "UPDATE menus SET is_available = 0, updated_at = NOW() WHERE id = ?",
            [$id]
        );
    }

    /**
     * Labels des themes en francais
     * @return array
     */
    public static function getThemeLabels() {
        return [
            'christmas' => 'Noel',
            'easter' => 'Paques',
            'classic' => 'Classique',
            'event' => 'Evenement',
            'seasonal' => 'Saisonnier'
        ];
    }

    /**
     * Labels des regimes en francais
     * @return array
     */
    public static function getDietaryTypeLabels() {
        return [
            'classic' => 'Classique',
            'vegetarian' => 'Vegetarien',
            'vegan' => 'Vegan'
        ];
    }
}
