<?php
require_once __DIR__ . '/../Views/core/Model.php';

/**
 * Modele Order - Gestion des commandes
 */
class Order extends Model {

    /**
     * Statuts disponibles
     */
    const STATUS_PENDING = 'pending';
    const STATUS_ACCEPTED = 'accepted';
    const STATUS_PREPARING = 'preparing';
    const STATUS_DELIVERING = 'delivering';
    const STATUS_DELIVERED = 'delivered';
    const STATUS_WAITING_RETURN = 'waiting_return';
    const STATUS_COMPLETED = 'completed';
    const STATUS_CANCELLED = 'cancelled';

    /**
     * Frais de livraison
     */
    const DELIVERY_BASE_FEE = 5.00;
    const DELIVERY_PER_KM = 0.59;
    const BORDEAUX_POSTAL_CODES = ['33000', '33100', '33200', '33300', '33800'];

    /**
     * Trouve une commande par son ID
     * @param int $id
     * @return array|null
     */
    public static function findById($id) {
        return self::queryOne(
            "SELECT o.*, m.title as menu_title, m.main_image_url as menu_image,
                    u.first_name as user_first_name, u.last_name as user_last_name
             FROM orders o
             INNER JOIN menus m ON o.menu_id = m.id
             INNER JOIN users u ON o.user_id = u.id
             WHERE o.id = ?",
            [$id]
        );
    }

    /**
     * Trouve une commande par son numero
     * @param string $orderNumber
     * @return array|null
     */
    public static function findByOrderNumber($orderNumber) {
        return self::queryOne(
            "SELECT o.*, m.title as menu_title, m.main_image_url as menu_image
             FROM orders o
             INNER JOIN menus m ON o.menu_id = m.id
             WHERE o.order_number = ?",
            [$orderNumber]
        );
    }

    /**
     * Trouve les commandes d'un utilisateur
     * @param int $userId
     * @param int $limit
     * @return array
     */
    public static function findByUser($userId, $limit = 50) {
        return self::query(
            "SELECT o.*, m.title as menu_title, m.main_image_url as menu_image
             FROM orders o
             INNER JOIN menus m ON o.menu_id = m.id
             WHERE o.user_id = ?
             ORDER BY o.created_at DESC
             LIMIT ?",
            [$userId, $limit]
        );
    }

    /**
     * Trouve toutes les commandes (admin)
     * @param array $filters
     * @return array
     */
    public static function findAll($filters = []) {
        $sql = "SELECT o.*, m.title as menu_title,
                       u.first_name as user_first_name, u.last_name as user_last_name, u.email as user_email
                FROM orders o
                INNER JOIN menus m ON o.menu_id = m.id
                INNER JOIN users u ON o.user_id = u.id
                WHERE 1=1";
        $params = [];

        // Filtre par statut
        if (!empty($filters['status'])) {
            $sql .= " AND o.status = ?";
            $params[] = $filters['status'];
        }

        // Filtre par date de livraison
        if (!empty($filters['delivery_date'])) {
            $sql .= " AND o.delivery_date = ?";
            $params[] = $filters['delivery_date'];
        }

        // Filtre par date de creation
        if (!empty($filters['date_from'])) {
            $sql .= " AND DATE(o.created_at) >= ?";
            $params[] = $filters['date_from'];
        }
        if (!empty($filters['date_to'])) {
            $sql .= " AND DATE(o.created_at) <= ?";
            $params[] = $filters['date_to'];
        }

        $sql .= " ORDER BY o.created_at DESC";

        if (!empty($filters['limit'])) {
            $sql .= " LIMIT ?";
            $params[] = (int)$filters['limit'];
        }

        return self::query($sql, $params);
    }

    /**
     * Trouve les commandes par statut
     * @param string $status
     * @return array
     */
    public static function findByStatus($status) {
        return self::query(
            "SELECT o.*, m.title as menu_title,
                    u.first_name as user_first_name, u.last_name as user_last_name
             FROM orders o
             INNER JOIN menus m ON o.menu_id = m.id
             INNER JOIN users u ON o.user_id = u.id
             WHERE o.status = ?
             ORDER BY o.delivery_date ASC, o.delivery_time ASC",
            [$status]
        );
    }

    /**
     * Trouve les commandes en attente de retour materiel
     * @return array
     */
    public static function findPendingEquipmentReturn() {
        return self::query(
            "SELECT o.*, m.title as menu_title,
                    u.first_name as user_first_name, u.last_name as user_last_name, u.email as user_email
             FROM orders o
             INNER JOIN menus m ON o.menu_id = m.id
             INNER JOIN users u ON o.user_id = u.id
             WHERE o.status = 'waiting_return' AND o.equipment_returned = 0
             ORDER BY o.delivery_date ASC"
        );
    }

    /**
     * Cree une nouvelle commande
     * @param array $data
     * @return int|false L'ID de la commande ou false
     */
    public static function create($data) {
        $orderNumber = self::generateOrderNumber();

        $sql = "INSERT INTO orders (
            order_number, user_id, menu_id, number_of_people,
            customer_first_name, customer_last_name, customer_email, customer_phone,
            delivery_address, delivery_city, delivery_postal_code,
            delivery_date, delivery_time, delivery_location,
            base_price, discount_amount, delivery_fee, total_price,
            status, customer_notes, created_at
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())";

        $result = self::execute($sql, [
            $orderNumber,
            $data['user_id'],
            $data['menu_id'],
            $data['number_of_people'],
            $data['customer_first_name'],
            $data['customer_last_name'],
            $data['customer_email'],
            $data['customer_phone'],
            $data['delivery_address'],
            $data['delivery_city'],
            $data['delivery_postal_code'],
            $data['delivery_date'],
            $data['delivery_time'],
            $data['delivery_location'] ?? null,
            $data['base_price'],
            $data['discount_amount'] ?? 0,
            $data['delivery_fee'] ?? 0,
            $data['total_price'],
            self::STATUS_PENDING,
            $data['customer_notes'] ?? null
        ]);

        if ($result) {
            return self::lastInsertId();
        }
        return false;
    }

    /**
     * Met a jour le statut d'une commande
     * @param int $id
     * @param string $status
     * @param string|null $adminNotes
     * @return bool
     */
    public static function updateStatus($id, $status, $adminNotes = null) {
        $allowedStatuses = [
            self::STATUS_PENDING, self::STATUS_ACCEPTED, self::STATUS_PREPARING,
            self::STATUS_DELIVERING, self::STATUS_DELIVERED, self::STATUS_WAITING_RETURN,
            self::STATUS_COMPLETED, self::STATUS_CANCELLED
        ];

        if (!in_array($status, $allowedStatuses)) {
            return false;
        }

        if ($adminNotes !== null) {
            return self::execute(
                "UPDATE orders SET status = ?, admin_notes = ?, updated_at = NOW() WHERE id = ?",
                [$status, $adminNotes, $id]
            );
        }

        return self::execute(
            "UPDATE orders SET status = ?, updated_at = NOW() WHERE id = ?",
            [$status, $id]
        );
    }

    /**
     * Marque le materiel comme retourne
     * @param int $id
     * @return bool
     */
    public static function markEquipmentReturned($id) {
        return self::execute(
            "UPDATE orders SET equipment_returned = 1, equipment_return_date = NOW(),
                              status = ?, updated_at = NOW() WHERE id = ?",
            [self::STATUS_COMPLETED, $id]
        );
    }

    /**
     * Annule une commande (si pas encore acceptee)
     * @param int $id
     * @param int $userId Pour verifier que c'est bien le proprietaire
     * @return bool
     */
    public static function cancel($id, $userId) {
        $order = self::findById($id);

        if (!$order || $order['user_id'] != $userId) {
            return false;
        }

        // On ne peut annuler que les commandes en attente
        if ($order['status'] !== self::STATUS_PENDING) {
            return false;
        }

        return self::updateStatus($id, self::STATUS_CANCELLED);
    }

    /**
     * Calcule le prix total d'une commande
     * @param int $menuId
     * @param int $numberOfPeople
     * @param string $postalCode
     * @param float $distanceKm Distance en kilometres (optionnel)
     * @return array
     */
    public static function calculatePrice($menuId, $numberOfPeople, $postalCode, $distanceKm = 0) {
        require_once __DIR__ . '/Menu.php';

        // Calcul du prix menu (avec reduction si applicable)
        $menuPrice = Menu::calculatePrice($menuId, $numberOfPeople);

        if (!$menuPrice) {
            return null;
        }

        // Frais de livraison
        $isInBordeaux = in_array($postalCode, self::BORDEAUX_POSTAL_CODES);
        $deliveryFee = 0;

        if (!$isInBordeaux) {
            $deliveryFee = self::DELIVERY_BASE_FEE + (self::DELIVERY_PER_KM * $distanceKm);
        }

        // Total
        $total = $menuPrice['subtotal'] + $deliveryFee;

        return [
            'base_price' => $menuPrice['base_price'],
            'extra_people' => $menuPrice['extra_people'],
            'extra_cost' => $menuPrice['extra_cost'],
            'discount' => $menuPrice['discount'],
            'discount_applied' => $menuPrice['discount_applied'],
            'subtotal' => $menuPrice['subtotal'],
            'is_in_bordeaux' => $isInBordeaux,
            'delivery_fee' => round($deliveryFee, 2),
            'total' => round($total, 2)
        ];
    }

    /**
     * Genere un numero de commande unique
     * @return string
     */
    public static function generateOrderNumber() {
        $date = date('Ymd');
        $random = strtoupper(substr(bin2hex(random_bytes(3)), 0, 6));
        return "VG-{$date}-{$random}";
    }

    /**
     * Verifie si la date de livraison est valide
     * @param int $menuId
     * @param string $deliveryDate Format Y-m-d
     * @return array ['valid' => bool, 'message' => string]
     */
    public static function validateDeliveryDate($menuId, $deliveryDate) {
        require_once __DIR__ . '/Menu.php';
        $menu = Menu::findById($menuId);

        if (!$menu) {
            return ['valid' => false, 'message' => 'Menu introuvable'];
        }

        $minDelayDays = $menu['min_order_delay_days'];
        $minDate = new DateTime();
        $minDate->modify("+{$minDelayDays} days");
        $minDateStr = $minDate->format('Y-m-d');

        $requestedDate = new DateTime($deliveryDate);

        if ($requestedDate < $minDate) {
            return [
                'valid' => false,
                'message' => "La date de livraison doit etre au minimum le {$minDate->format('d/m/Y')} (delai de {$minDelayDays} jours)"
            ];
        }

        return ['valid' => true, 'message' => ''];
    }

    /**
     * Compte les commandes par statut
     * @return array
     */
    public static function countByStatus() {
        $results = self::query(
            "SELECT status, COUNT(*) as count FROM orders GROUP BY status"
        );

        $counts = [];
        foreach ($results as $row) {
            $counts[$row['status']] = (int)$row['count'];
        }

        return $counts;
    }

    /**
     * Statistiques des commandes du jour
     * @return array
     */
    public static function getTodayStats() {
        $today = date('Y-m-d');

        $stats = self::queryOne(
            "SELECT COUNT(*) as count, COALESCE(SUM(total_price), 0) as revenue
             FROM orders
             WHERE DATE(created_at) = ?",
            [$today]
        );

        return [
            'count' => (int)$stats['count'],
            'revenue' => (float)$stats['revenue']
        ];
    }

    /**
     * Labels des statuts en francais
     * @return array
     */
    public static function getStatusLabels() {
        return [
            self::STATUS_PENDING => 'En attente',
            self::STATUS_ACCEPTED => 'Acceptee',
            self::STATUS_PREPARING => 'En preparation',
            self::STATUS_DELIVERING => 'En livraison',
            self::STATUS_DELIVERED => 'Livree',
            self::STATUS_WAITING_RETURN => 'Attente retour materiel',
            self::STATUS_COMPLETED => 'Terminee',
            self::STATUS_CANCELLED => 'Annulee'
        ];
    }

    /**
     * Couleurs Bootstrap des statuts
     * @return array
     */
    public static function getStatusColors() {
        return [
            self::STATUS_PENDING => 'warning',
            self::STATUS_ACCEPTED => 'info',
            self::STATUS_PREPARING => 'primary',
            self::STATUS_DELIVERING => 'info',
            self::STATUS_DELIVERED => 'success',
            self::STATUS_WAITING_RETURN => 'secondary',
            self::STATUS_COMPLETED => 'success',
            self::STATUS_CANCELLED => 'danger'
        ];
    }

    /**
     * Verifie si une commande appartient a un utilisateur
     * @param int $orderId
     * @param int $userId
     * @return bool
     */
    public static function belongsToUser($orderId, $userId) {
        $result = self::queryOne(
            "SELECT id FROM orders WHERE id = ? AND user_id = ?",
            [$orderId, $userId]
        );
        return $result !== false;
    }

    /**
     * Verifie si une commande peut etre annulee
     * @param array $order
     * @return bool
     */
    public static function canBeCancelled($order) {
        return $order['status'] === self::STATUS_PENDING;
    }

    /**
     * Verifie si un avis peut etre laisse sur cette commande
     * @param array $order
     * @return bool
     */
    public static function canBeReviewed($order) {
        $reviewableStatuses = [
            self::STATUS_DELIVERED,
            self::STATUS_WAITING_RETURN,
            self::STATUS_COMPLETED
        ];
        return in_array($order['status'], $reviewableStatuses);
    }
}
