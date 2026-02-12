<?php
require_once __DIR__ . '/../Views/core/Model.php';

/**
 * Modele OrderItem - Gestion des articles de commande
 */
class OrderItem extends Model {

    /**
     * Trouve tous les articles d'une commande
     * @param int $orderId
     * @return array
     */
    public static function findByOrder($orderId) {
        return self::query(
            "SELECT oi.*, m.main_image_url as menu_image, m.theme, m.dietary_type
             FROM order_items oi
             LEFT JOIN menus m ON oi.menu_id = m.id
             WHERE oi.order_id = ?
             ORDER BY oi.id",
            [$orderId]
        );
    }

    /**
     * Trouve un article par son ID
     * @param int $id
     * @return array|null
     */
    public static function findById($id) {
        return self::queryOne(
            "SELECT oi.*, m.main_image_url as menu_image
             FROM order_items oi
             LEFT JOIN menus m ON oi.menu_id = m.id
             WHERE oi.id = ?",
            [$id]
        );
    }

    /**
     * Cree un article de commande
     * @param array $data
     * @return int ID de l'article cree
     */
    public static function create($data) {
        $sql = "INSERT INTO order_items (
            order_id, menu_id, quantity, number_of_people,
            unit_price, subtotal, menu_title, created_at
        ) VALUES (?, ?, ?, ?, ?, ?, ?, NOW())";

        self::execute($sql, [
            $data['order_id'],
            $data['menu_id'],
            $data['quantity'],
            $data['number_of_people'],
            $data['unit_price'],
            $data['subtotal'],
            $data['menu_title']
        ]);

        return self::lastInsertId();
    }

    /**
     * Cree plusieurs articles de commande en une fois
     * @param int $orderId
     * @param array $items Tableau d'articles
     * @return bool
     */
    public static function createMultiple($orderId, $items) {
        if (empty($items)) {
            return false;
        }

        foreach ($items as $item) {
            $item['order_id'] = $orderId;
            self::create($item);
        }

        return true;
    }

    /**
     * Supprime tous les articles d'une commande
     * @param int $orderId
     * @return bool
     */
    public static function deleteByOrder($orderId) {
        return self::execute(
            "DELETE FROM order_items WHERE order_id = ?",
            [$orderId]
        );
    }

    /**
     * Compte les articles d'une commande
     * @param int $orderId
     * @return int
     */
    public static function countByOrder($orderId) {
        $result = self::queryOne(
            "SELECT SUM(quantity) as total FROM order_items WHERE order_id = ?",
            [$orderId]
        );
        return (int)($result['total'] ?? 0);
    }

    /**
     * Calcule le total des articles d'une commande
     * @param int $orderId
     * @return float
     */
    public static function sumByOrder($orderId) {
        $result = self::queryOne(
            "SELECT SUM(subtotal) as total FROM order_items WHERE order_id = ?",
            [$orderId]
        );
        return (float)($result['total'] ?? 0);
    }
}
