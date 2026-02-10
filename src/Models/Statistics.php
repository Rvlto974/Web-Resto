<?php
/**
 * Modele Statistics - Gestion des statistiques avec MongoDB
 *
 * Ce modele gere les statistiques de l'application en utilisant MongoDB
 * pour le stockage de donnees non-relationnelles (logs, analytics).
 */
class Statistics {

    private static $client = null;
    private static $database = null;

    /**
     * Obtient la connexion MongoDB
     * @return \MongoDB\Database|null
     */
    private static function getDatabase() {
        if (self::$database === null) {
            try {
                // Verifier que l'extension MongoDB est installee
                if (!class_exists('MongoDB\Client')) {
                    error_log('Extension MongoDB non installee');
                    return null;
                }

                $uri = defined('MONGODB_URI') ? MONGODB_URI : 'mongodb://mongodb:27017';
                $dbName = defined('MONGODB_DATABASE') ? MONGODB_DATABASE : 'vite_et_gourmand_stats';

                self::$client = new \MongoDB\Client($uri);
                self::$database = self::$client->selectDatabase($dbName);
            } catch (\Exception $e) {
                error_log('Erreur connexion MongoDB: ' . $e->getMessage());
                return null;
            }
        }

        return self::$database;
    }

    /**
     * Enregistre une commande dans MongoDB
     * @param array $orderData
     * @return bool
     */
    public static function logOrder($orderData) {
        $db = self::getDatabase();
        if (!$db) {
            return false;
        }

        try {
            $collection = $db->selectCollection('orders');

            $document = [
                'order_id' => (int)$orderData['id'],
                'order_number' => $orderData['order_number'],
                'user_id' => (int)$orderData['user_id'],
                'menu_id' => (int)$orderData['menu_id'],
                'menu_title' => $orderData['menu_title'] ?? '',
                'number_of_people' => (int)$orderData['number_of_people'],
                'total_price' => (float)$orderData['total_price'],
                'delivery_city' => $orderData['delivery_city'],
                'delivery_postal_code' => $orderData['delivery_postal_code'],
                'status' => $orderData['status'],
                'created_at' => new \MongoDB\BSON\UTCDateTime(strtotime($orderData['created_at']) * 1000),
                'delivery_date' => new \MongoDB\BSON\UTCDateTime(strtotime($orderData['delivery_date']) * 1000),
                'year' => (int)date('Y', strtotime($orderData['created_at'])),
                'month' => (int)date('m', strtotime($orderData['created_at'])),
                'day' => (int)date('d', strtotime($orderData['created_at'])),
                'day_of_week' => (int)date('N', strtotime($orderData['created_at']))
            ];

            $collection->insertOne($document);
            return true;
        } catch (\Exception $e) {
            error_log('Erreur logOrder: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Obtient le nombre de commandes par menu
     * @param array $filters ['date_from', 'date_to']
     * @return array
     */
    public static function getOrdersByMenu($filters = []) {
        $db = self::getDatabase();
        if (!$db) {
            return [];
        }

        try {
            $collection = $db->selectCollection('orders');

            $match = ['status' => ['$ne' => 'cancelled']];

            if (!empty($filters['date_from'])) {
                $match['created_at']['$gte'] = new \MongoDB\BSON\UTCDateTime(strtotime($filters['date_from']) * 1000);
            }
            if (!empty($filters['date_to'])) {
                $match['created_at']['$lte'] = new \MongoDB\BSON\UTCDateTime(strtotime($filters['date_to']) * 1000);
            }

            $pipeline = [
                ['$match' => $match],
                ['$group' => [
                    '_id' => '$menu_id',
                    'menu_title' => ['$first' => '$menu_title'],
                    'count' => ['$sum' => 1],
                    'total_revenue' => ['$sum' => '$total_price'],
                    'avg_people' => ['$avg' => '$number_of_people']
                ]],
                ['$sort' => ['count' => -1]]
            ];

            $results = $collection->aggregate($pipeline)->toArray();

            return array_map(function($doc) {
                return [
                    'menu_id' => $doc['_id'],
                    'menu_title' => $doc['menu_title'],
                    'count' => $doc['count'],
                    'total_revenue' => round($doc['total_revenue'], 2),
                    'avg_people' => round($doc['avg_people'], 1)
                ];
            }, $results);
        } catch (\Exception $e) {
            error_log('Erreur getOrdersByMenu: ' . $e->getMessage());
            return [];
        }
    }

    /**
     * Obtient le chiffre d'affaires par menu
     * @param array $filters
     * @return array
     */
    public static function getRevenueByMenu($filters = []) {
        return self::getOrdersByMenu($filters);
    }

    /**
     * Obtient les statistiques mensuelles
     * @param int $year
     * @return array
     */
    public static function getMonthlyStats($year = null) {
        $db = self::getDatabase();
        if (!$db) {
            return [];
        }

        $year = $year ?: (int)date('Y');

        try {
            $collection = $db->selectCollection('orders');

            $pipeline = [
                ['$match' => [
                    'year' => $year,
                    'status' => ['$ne' => 'cancelled']
                ]],
                ['$group' => [
                    '_id' => '$month',
                    'count' => ['$sum' => 1],
                    'revenue' => ['$sum' => '$total_price'],
                    'avg_order' => ['$avg' => '$total_price']
                ]],
                ['$sort' => ['_id' => 1]]
            ];

            $results = $collection->aggregate($pipeline)->toArray();

            // Initialiser tous les mois
            $monthlyData = [];
            for ($m = 1; $m <= 12; $m++) {
                $monthlyData[$m] = [
                    'month' => $m,
                    'month_name' => self::getMonthName($m),
                    'count' => 0,
                    'revenue' => 0,
                    'avg_order' => 0
                ];
            }

            // Remplir avec les donnees
            foreach ($results as $doc) {
                $m = $doc['_id'];
                $monthlyData[$m] = [
                    'month' => $m,
                    'month_name' => self::getMonthName($m),
                    'count' => $doc['count'],
                    'revenue' => round($doc['revenue'], 2),
                    'avg_order' => round($doc['avg_order'], 2)
                ];
            }

            return array_values($monthlyData);
        } catch (\Exception $e) {
            error_log('Erreur getMonthlyStats: ' . $e->getMessage());
            return [];
        }
    }

    /**
     * Obtient les statistiques par jour de la semaine
     * @return array
     */
    public static function getStatsByDayOfWeek() {
        $db = self::getDatabase();
        if (!$db) {
            return [];
        }

        try {
            $collection = $db->selectCollection('orders');

            $pipeline = [
                ['$match' => ['status' => ['$ne' => 'cancelled']]],
                ['$group' => [
                    '_id' => '$day_of_week',
                    'count' => ['$sum' => 1],
                    'revenue' => ['$sum' => '$total_price']
                ]],
                ['$sort' => ['_id' => 1]]
            ];

            $results = $collection->aggregate($pipeline)->toArray();

            $dayNames = [
                1 => 'Lundi', 2 => 'Mardi', 3 => 'Mercredi', 4 => 'Jeudi',
                5 => 'Vendredi', 6 => 'Samedi', 7 => 'Dimanche'
            ];

            return array_map(function($doc) use ($dayNames) {
                return [
                    'day' => $doc['_id'],
                    'day_name' => $dayNames[$doc['_id']] ?? '',
                    'count' => $doc['count'],
                    'revenue' => round($doc['revenue'], 2)
                ];
            }, $results);
        } catch (\Exception $e) {
            error_log('Erreur getStatsByDayOfWeek: ' . $e->getMessage());
            return [];
        }
    }

    /**
     * Obtient les villes les plus commandees
     * @param int $limit
     * @return array
     */
    public static function getTopCities($limit = 10) {
        $db = self::getDatabase();
        if (!$db) {
            return [];
        }

        try {
            $collection = $db->selectCollection('orders');

            $pipeline = [
                ['$match' => ['status' => ['$ne' => 'cancelled']]],
                ['$group' => [
                    '_id' => '$delivery_city',
                    'count' => ['$sum' => 1],
                    'revenue' => ['$sum' => '$total_price']
                ]],
                ['$sort' => ['count' => -1]],
                ['$limit' => $limit]
            ];

            $results = $collection->aggregate($pipeline)->toArray();

            return array_map(function($doc) {
                return [
                    'city' => $doc['_id'],
                    'count' => $doc['count'],
                    'revenue' => round($doc['revenue'], 2)
                ];
            }, $results);
        } catch (\Exception $e) {
            error_log('Erreur getTopCities: ' . $e->getMessage());
            return [];
        }
    }

    /**
     * Obtient un resume global des statistiques
     * @return array
     */
    public static function getSummary() {
        $db = self::getDatabase();
        if (!$db) {
            return [
                'total_orders' => 0,
                'total_revenue' => 0,
                'avg_order' => 0,
                'total_people_served' => 0
            ];
        }

        try {
            $collection = $db->selectCollection('orders');

            $pipeline = [
                ['$match' => ['status' => ['$ne' => 'cancelled']]],
                ['$group' => [
                    '_id' => null,
                    'total_orders' => ['$sum' => 1],
                    'total_revenue' => ['$sum' => '$total_price'],
                    'avg_order' => ['$avg' => '$total_price'],
                    'total_people' => ['$sum' => '$number_of_people']
                ]]
            ];

            $results = $collection->aggregate($pipeline)->toArray();

            if (empty($results)) {
                return [
                    'total_orders' => 0,
                    'total_revenue' => 0,
                    'avg_order' => 0,
                    'total_people_served' => 0
                ];
            }

            $doc = $results[0];
            return [
                'total_orders' => $doc['total_orders'],
                'total_revenue' => round($doc['total_revenue'], 2),
                'avg_order' => round($doc['avg_order'], 2),
                'total_people_served' => $doc['total_people']
            ];
        } catch (\Exception $e) {
            error_log('Erreur getSummary: ' . $e->getMessage());
            return [
                'total_orders' => 0,
                'total_revenue' => 0,
                'avg_order' => 0,
                'total_people_served' => 0
            ];
        }
    }

    /**
     * Nom du mois en francais
     * @param int $month
     * @return string
     */
    private static function getMonthName($month) {
        $months = [
            1 => 'Janvier', 2 => 'Fevrier', 3 => 'Mars', 4 => 'Avril',
            5 => 'Mai', 6 => 'Juin', 7 => 'Juillet', 8 => 'Aout',
            9 => 'Septembre', 10 => 'Octobre', 11 => 'Novembre', 12 => 'Decembre'
        ];
        return $months[$month] ?? '';
    }
}
