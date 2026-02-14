<?php
require_once __DIR__ . '/../Views/core/Model.php';

/**
 * Modele Review - Gestion des avis clients
 */
class Review extends Model {

    /**
     * Trouve un avis par son ID
     * @param int $id
     * @return array|null
     */
    public static function findById($id) {
        return self::queryOne(
            "SELECT r.*, u.first_name, u.last_name, u.email,
                    m.title as menu_title, o.order_number
             FROM reviews r
             INNER JOIN users u ON r.user_id = u.id
             INNER JOIN menus m ON r.menu_id = m.id
             LEFT JOIN orders o ON r.order_id = o.id
             WHERE r.id = ?",
            [$id]
        );
    }

    /**
     * Trouve les avis d'un menu
     * @param int $menuId
     * @param bool $approvedOnly
     * @param int $limit
     * @return array
     */
    public static function findByMenu($menuId, $approvedOnly = true, $limit = 20) {
        $sql = "SELECT r.*, u.first_name, u.last_name
                FROM reviews r
                INNER JOIN users u ON r.user_id = u.id
                WHERE r.menu_id = ?";

        if ($approvedOnly) {
            $sql .= " AND r.is_approved = 1";
        }

        $sql .= " ORDER BY r.created_at DESC LIMIT ?";

        return self::query($sql, [$menuId, $limit]);
    }

    /**
     * Trouve les avis d'un utilisateur
     * @param int $userId
     * @return array
     */
    public static function findByUser($userId) {
        return self::query(
            "SELECT r.*, m.title as menu_title, m.main_image_url as menu_image, o.order_number
             FROM reviews r
             INNER JOIN menus m ON r.menu_id = m.id
             LEFT JOIN orders o ON r.order_id = o.id
             WHERE r.user_id = ?
             ORDER BY r.created_at DESC",
            [$userId]
        );
    }

    /**
     * Trouve les avis en attente d'approbation
     * @return array
     */
    public static function findPendingApproval() {
        return self::query(
            "SELECT r.*, u.first_name, u.last_name, u.email,
                    m.title as menu_title, o.order_number
             FROM reviews r
             INNER JOIN users u ON r.user_id = u.id
             INNER JOIN menus m ON r.menu_id = m.id
             INNER JOIN orders o ON r.order_id = o.id
             WHERE r.is_approved = 0
             ORDER BY r.created_at ASC"
        );
    }

    /**
     * Trouve tous les avis (admin)
     * @param array $filters
     * @return array
     */
    public static function findAll($filters = []) {
        $sql = "SELECT r.*, u.first_name, u.last_name, u.email,
                       m.title as menu_title, o.order_number,
                       a.first_name as approver_first_name, a.last_name as approver_last_name
                FROM reviews r
                INNER JOIN users u ON r.user_id = u.id
                INNER JOIN menus m ON r.menu_id = m.id
                LEFT JOIN orders o ON r.order_id = o.id
                LEFT JOIN users a ON r.approved_by = a.id
                WHERE 1=1";
        $params = [];

        if (isset($filters['is_approved'])) {
            $sql .= " AND r.is_approved = ?";
            $params[] = (int)$filters['is_approved'];
        }

        if (!empty($filters['menu_id'])) {
            $sql .= " AND r.menu_id = ?";
            $params[] = (int)$filters['menu_id'];
        }

        if (!empty($filters['rating'])) {
            $sql .= " AND r.rating = ?";
            $params[] = (int)$filters['rating'];
        }

        $sql .= " ORDER BY r.created_at DESC";

        if (!empty($filters['limit'])) {
            $sql .= " LIMIT ?";
            $params[] = (int)$filters['limit'];
        }

        return self::query($sql, $params);
    }

    /**
     * Verifie si un avis existe deja pour une commande
     * @param int $orderId
     * @param int $userId
     * @return bool
     */
    public static function existsForOrder($orderId, $userId) {
        $result = self::queryOne(
            "SELECT id FROM reviews WHERE order_id = ? AND user_id = ?",
            [$orderId, $userId]
        );
        return $result !== false;
    }

    /**
     * Verifie si un avis existe deja pour un menu par un utilisateur
     * @param int $menuId
     * @param int $userId
     * @return bool
     */
    public static function existsForMenu($menuId, $userId) {
        $result = self::queryOne(
            "SELECT id FROM reviews WHERE menu_id = ? AND user_id = ?",
            [$menuId, $userId]
        );
        return $result !== false;
    }

    /**
     * Cree un nouvel avis
     * @param array $data
     * @return int|false
     */
    public static function create($data) {
        // Verifier qu'un avis n'existe pas deja pour ce menu
        if (self::existsForMenu($data['menu_id'], $data['user_id'])) {
            return false;
        }

        $sql = "INSERT INTO reviews (
            user_id, order_id, menu_id, rating, comment, is_approved, created_at
        ) VALUES (?, ?, ?, ?, ?, 0, NOW())";

        $result = self::execute($sql, [
            $data['user_id'],
            $data['order_id'],
            $data['menu_id'],
            $data['rating'],
            $data['comment'] ?? null
        ]);

        if ($result) {
            return self::lastInsertId();
        }
        return false;
    }

    /**
     * Approuve un avis
     * @param int $id
     * @param int $employeeId
     * @return bool
     */
    public static function approve($id, $employeeId) {
        return self::execute(
            "UPDATE reviews SET is_approved = 1, approved_by = ?, approved_at = NOW() WHERE id = ?",
            [$employeeId, $id]
        );
    }

    /**
     * Rejette un avis (le supprime)
     * @param int $id
     * @return bool
     */
    public static function reject($id) {
        return self::execute("DELETE FROM reviews WHERE id = ?", [$id]);
    }

    /**
     * Compte les avis par statut
     * @return array
     */
    public static function countByStatus() {
        $result = self::query(
            "SELECT is_approved, COUNT(*) as count FROM reviews GROUP BY is_approved"
        );

        $counts = ['pending' => 0, 'approved' => 0];
        foreach ($result as $row) {
            if ($row['is_approved']) {
                $counts['approved'] = (int)$row['count'];
            } else {
                $counts['pending'] = (int)$row['count'];
            }
        }

        return $counts;
    }

    /**
     * Calcule les statistiques d'un menu
     * @param int $menuId
     * @return array
     */
    public static function getMenuStats($menuId) {
        $result = self::queryOne(
            "SELECT
                COUNT(*) as total,
                AVG(rating) as average,
                SUM(CASE WHEN rating = 5 THEN 1 ELSE 0 END) as five_stars,
                SUM(CASE WHEN rating = 4 THEN 1 ELSE 0 END) as four_stars,
                SUM(CASE WHEN rating = 3 THEN 1 ELSE 0 END) as three_stars,
                SUM(CASE WHEN rating = 2 THEN 1 ELSE 0 END) as two_stars,
                SUM(CASE WHEN rating = 1 THEN 1 ELSE 0 END) as one_star
             FROM reviews
             WHERE menu_id = ? AND is_approved = 1",
            [$menuId]
        );

        return [
            'total' => (int)$result['total'],
            'average' => $result['average'] ? round($result['average'], 1) : 0,
            'distribution' => [
                5 => (int)$result['five_stars'],
                4 => (int)$result['four_stars'],
                3 => (int)$result['three_stars'],
                2 => (int)$result['two_stars'],
                1 => (int)$result['one_star']
            ]
        ];
    }

    /**
     * Recupere les meilleurs avis pour la page d'accueil
     * @param int $limit
     * @return array
     */
    public static function getFeatured($limit = 6) {
        return self::query(
            "SELECT r.*, u.first_name, u.last_name, m.title as menu_title
             FROM reviews r
             INNER JOIN users u ON r.user_id = u.id
             INNER JOIN menus m ON r.menu_id = m.id
             WHERE r.is_approved = 1 AND r.rating >= 4
             ORDER BY r.rating DESC, r.created_at DESC
             LIMIT ?",
            [$limit]
        );
    }
}
