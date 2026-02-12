<?php
require_once __DIR__ . '/../Core/Auth.php';
require_once __DIR__ . '/Menu.php';

/**
 * Modele Cart - Gestion du panier en session
 */
class Cart {

    const SESSION_KEY = 'cart';

    /**
     * Codes postaux de Bordeaux (livraison gratuite)
     */
    const BORDEAUX_POSTAL_CODES = ['33000', '33100', '33200', '33300', '33800'];

    /**
     * Frais de livraison
     */
    const DELIVERY_BASE_FEE = 5.00;
    const DELIVERY_PER_KM = 0.59;

    /**
     * Initialise le panier en session si necessaire
     */
    public static function init() {
        Auth::init();
        if (!isset($_SESSION[self::SESSION_KEY])) {
            $_SESSION[self::SESSION_KEY] = [];
        }
    }

    /**
     * Ajoute un menu au panier
     * @param int $menuId
     * @param int $numberOfPeople
     * @param int $quantity
     * @return bool
     */
    public static function add($menuId, $numberOfPeople, $quantity = 1) {
        self::init();

        // Verifier que le menu existe et est disponible
        $menu = Menu::findById($menuId);
        if (!$menu || !$menu['is_available'] || $menu['stock_quantity'] <= 0) {
            return false;
        }

        // Verifier le nombre minimum de personnes
        if ($numberOfPeople < $menu['min_people']) {
            $numberOfPeople = $menu['min_people'];
        }

        // Chercher si le menu existe deja dans le panier avec le meme nombre de personnes
        $found = false;
        foreach ($_SESSION[self::SESSION_KEY] as &$item) {
            if ($item['menu_id'] == $menuId && $item['number_of_people'] == $numberOfPeople) {
                $item['quantity'] += $quantity;
                $found = true;
                break;
            }
        }

        // Sinon ajouter un nouvel article
        if (!$found) {
            $_SESSION[self::SESSION_KEY][] = [
                'menu_id' => (int)$menuId,
                'quantity' => (int)$quantity,
                'number_of_people' => (int)$numberOfPeople,
                'added_at' => date('Y-m-d H:i:s')
            ];
        }

        return true;
    }

    /**
     * Met a jour un article du panier
     * @param int $index Index de l'article dans le panier
     * @param int|null $quantity Nouvelle quantite (null = ne pas modifier)
     * @param int|null $numberOfPeople Nouveau nombre de personnes (null = ne pas modifier)
     * @return bool
     */
    public static function update($index, $quantity = null, $numberOfPeople = null) {
        self::init();

        if (!isset($_SESSION[self::SESSION_KEY][$index])) {
            return false;
        }

        $item = &$_SESSION[self::SESSION_KEY][$index];

        // Mettre a jour la quantite
        if ($quantity !== null) {
            if ($quantity <= 0) {
                return self::remove($index);
            }
            $item['quantity'] = (int)$quantity;
        }

        // Mettre a jour le nombre de personnes
        if ($numberOfPeople !== null) {
            $menu = Menu::findById($item['menu_id']);
            if ($menu && $numberOfPeople >= $menu['min_people']) {
                $item['number_of_people'] = (int)$numberOfPeople;
            }
        }

        return true;
    }

    /**
     * Supprime un article du panier
     * @param int $index Index de l'article
     * @return bool
     */
    public static function remove($index) {
        self::init();

        if (!isset($_SESSION[self::SESSION_KEY][$index])) {
            return false;
        }

        unset($_SESSION[self::SESSION_KEY][$index]);
        // Reindexer le tableau
        $_SESSION[self::SESSION_KEY] = array_values($_SESSION[self::SESSION_KEY]);

        return true;
    }

    /**
     * Vide le panier
     */
    public static function clear() {
        self::init();
        $_SESSION[self::SESSION_KEY] = [];
    }

    /**
     * Recupere le contenu du panier
     * @return array
     */
    public static function get() {
        self::init();
        return $_SESSION[self::SESSION_KEY];
    }

    /**
     * Recupere le panier avec les details des menus
     * @return array
     */
    public static function getWithDetails() {
        self::init();
        $items = [];

        foreach ($_SESSION[self::SESSION_KEY] as $index => $item) {
            $menu = Menu::findById($item['menu_id']);
            if ($menu) {
                $priceData = Menu::calculatePrice($item['menu_id'], $item['number_of_people']);
                $items[] = [
                    'index' => $index,
                    'menu_id' => $item['menu_id'],
                    'menu' => $menu,
                    'quantity' => $item['quantity'],
                    'number_of_people' => $item['number_of_people'],
                    'unit_price' => $priceData['subtotal'],
                    'subtotal' => $priceData['subtotal'] * $item['quantity'],
                    'discount_applied' => $priceData['discount_applied'],
                    'discount' => $priceData['discount'] * $item['quantity'],
                    'added_at' => $item['added_at'] ?? null
                ];
            }
        }

        return $items;
    }

    /**
     * Compte le nombre d'articles dans le panier
     * @return int
     */
    public static function count() {
        self::init();
        $count = 0;
        foreach ($_SESSION[self::SESSION_KEY] as $item) {
            $count += $item['quantity'];
        }
        return $count;
    }

    /**
     * Verifie si le panier est vide
     * @return bool
     */
    public static function isEmpty() {
        self::init();
        return empty($_SESSION[self::SESSION_KEY]);
    }

    /**
     * Calcule les totaux du panier
     * @param string|null $postalCode Code postal de livraison
     * @param float $distanceKm Distance en km (si hors Bordeaux)
     * @return array
     */
    public static function calculateTotals($postalCode = null, $distanceKm = 0) {
        $items = self::getWithDetails();
        $subtotal = 0;
        $totalDiscount = 0;

        foreach ($items as $item) {
            $subtotal += $item['subtotal'];
            $totalDiscount += $item['discount'];
        }

        // Calcul des frais de livraison
        $deliveryFee = 0;
        $isInBordeaux = in_array($postalCode, self::BORDEAUX_POSTAL_CODES);

        if (!self::isEmpty() && $postalCode && !$isInBordeaux) {
            $deliveryFee = self::DELIVERY_BASE_FEE + (self::DELIVERY_PER_KM * $distanceKm);
        }

        $total = $subtotal + $deliveryFee;

        return [
            'items' => $items,
            'items_count' => self::count(),
            'subtotal' => round($subtotal, 2),
            'total_discount' => round($totalDiscount, 2),
            'delivery_fee' => round($deliveryFee, 2),
            'total' => round($total, 2),
            'is_in_bordeaux' => $isInBordeaux
        ];
    }

    /**
     * Verifie le stock disponible pour tous les articles
     * @return array ['valid' => bool, 'errors' => array]
     */
    public static function validateStock() {
        $items = self::get();
        $errors = [];
        $valid = true;

        foreach ($items as $index => $item) {
            $menu = Menu::findById($item['menu_id']);
            if (!$menu) {
                $errors[] = "Un menu n'existe plus.";
                $valid = false;
            } elseif (!$menu['is_available']) {
                $errors[] = "Le menu \"{$menu['title']}\" n'est plus disponible.";
                $valid = false;
            } elseif ($menu['stock_quantity'] < $item['quantity']) {
                $errors[] = "Stock insuffisant pour \"{$menu['title']}\" (disponible: {$menu['stock_quantity']}).";
                $valid = false;
            }
        }

        return [
            'valid' => $valid,
            'errors' => $errors
        ];
    }

    /**
     * Obtient la date de livraison minimum basee sur tous les menus du panier
     * @return string|null Date au format Y-m-d
     */
    public static function getMinDeliveryDate() {
        $items = self::get();
        $maxDelay = 0;

        foreach ($items as $item) {
            $menu = Menu::findById($item['menu_id']);
            if ($menu && $menu['min_order_delay_days'] > $maxDelay) {
                $maxDelay = $menu['min_order_delay_days'];
            }
        }

        if ($maxDelay > 0) {
            return date('Y-m-d', strtotime("+{$maxDelay} days"));
        }

        return date('Y-m-d', strtotime('+2 days'));
    }
}
