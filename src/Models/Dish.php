<?php
require_once __DIR__ . '/../Views/core/Model.php';

/**
 * Modele Dish - Gestion des plats
 */
class Dish extends Model {

    /**
     * Categories de plats
     */
    const CATEGORY_STARTER = 'starter';
    const CATEGORY_MAIN = 'main';
    const CATEGORY_DESSERT = 'dessert';
    const CATEGORY_DRINK = 'drink';
    const CATEGORY_SIDE = 'side';

    /**
     * Trouve un plat par son ID
     * @param int $id
     * @return array|null
     */
    public static function findById($id) {
        return self::queryOne(
            "SELECT * FROM dishes WHERE id = ?",
            [$id]
        );
    }

    /**
     * Trouve tous les plats
     * @param string|null $category Filtrer par categorie
     * @return array
     */
    public static function findAll($category = null) {
        if ($category) {
            return self::query(
                "SELECT * FROM dishes WHERE category = ? ORDER BY name",
                [$category]
            );
        }
        return self::query("SELECT * FROM dishes ORDER BY category, name");
    }

    /**
     * Trouve les plats par categorie
     * @param string $category
     * @return array
     */
    public static function findByCategory($category) {
        return self::findAll($category);
    }

    /**
     * Cree un nouveau plat
     * @param array $data
     * @return int
     */
    public static function create($data) {
        $sql = "INSERT INTO dishes (name, description, category, base_price, image_url, created_at)
                VALUES (?, ?, ?, ?, ?, NOW())";

        self::execute($sql, [
            $data['name'],
            $data['description'] ?? null,
            $data['category'],
            $data['base_price'] ?? null,
            $data['image_url'] ?? null
        ]);

        return self::lastInsertId();
    }

    /**
     * Met a jour un plat
     * @param int $id
     * @param array $data
     * @return bool
     */
    public static function update($id, $data) {
        $fields = [];
        $values = [];

        $allowedFields = ['name', 'description', 'category', 'base_price', 'image_url'];

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

        $sql = "UPDATE dishes SET " . implode(', ', $fields) . " WHERE id = ?";
        return self::execute($sql, $values);
    }

    /**
     * Supprime un plat
     * @param int $id
     * @return bool
     */
    public static function delete($id) {
        return self::execute("DELETE FROM dishes WHERE id = ?", [$id]);
    }

    /**
     * Recupere les allergenes d'un plat
     * @param int $dishId
     * @return array
     */
    public static function getAllergens($dishId) {
        return self::query(
            "SELECT a.* FROM allergens a
             INNER JOIN dish_allergens da ON a.id = da.allergen_id
             WHERE da.dish_id = ?
             ORDER BY a.name",
            [$dishId]
        );
    }

    /**
     * Associe des allergenes a un plat
     * @param int $dishId
     * @param array $allergenIds
     * @return bool
     */
    public static function setAllergens($dishId, $allergenIds) {
        // Supprimer les associations existantes
        self::execute("DELETE FROM dish_allergens WHERE dish_id = ?", [$dishId]);

        // Ajouter les nouvelles associations
        if (!empty($allergenIds)) {
            $values = [];
            $params = [];
            foreach ($allergenIds as $allergenId) {
                $values[] = "(?, ?)";
                $params[] = $dishId;
                $params[] = $allergenId;
            }

            $sql = "INSERT INTO dish_allergens (dish_id, allergen_id) VALUES " . implode(', ', $values);
            return self::execute($sql, $params);
        }

        return true;
    }

    /**
     * Recupere les menus contenant ce plat
     * @param int $dishId
     * @return array
     */
    public static function getMenus($dishId) {
        return self::query(
            "SELECT m.* FROM menus m
             INNER JOIN menu_dishes md ON m.id = md.menu_id
             WHERE md.dish_id = ?
             ORDER BY m.title",
            [$dishId]
        );
    }

    /**
     * Labels des categories en francais
     * @return array
     */
    public static function getCategoryLabels() {
        return [
            self::CATEGORY_STARTER => 'Entrees',
            self::CATEGORY_MAIN => 'Plats',
            self::CATEGORY_DESSERT => 'Desserts',
            self::CATEGORY_DRINK => 'Boissons',
            self::CATEGORY_SIDE => 'Accompagnements'
        ];
    }
}
