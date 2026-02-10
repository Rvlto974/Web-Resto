<?php
require_once __DIR__ . '/../Views/core/Controller.php';
require_once __DIR__ . '/../Core/Auth.php';
require_once __DIR__ . '/../Core/Csrf.php';
require_once __DIR__ . '/../Models/Order.php';
require_once __DIR__ . '/../Models/Menu.php';

/**
 * OrderController - Gestion des commandes clients
 */
class OrderController extends Controller {

    /**
     * Formulaire de commande
     * @param int $menuId
     */
    public function create($menuId = null) {
        Auth::requireAuth();

        if (!$menuId) {
            Auth::setFlash('error', 'Menu non specifie.');
            $this->redirect('/menu');
        }

        $menu = Menu::findById($menuId);

        if (!$menu || !$menu['is_available'] || $menu['stock_quantity'] <= 0) {
            Auth::setFlash('error', 'Ce menu n\'est pas disponible.');
            $this->redirect('/menu');
        }

        $user = Auth::user();

        // Date minimum de livraison
        $minDelayDays = $menu['min_order_delay_days'];
        $minDate = new DateTime();
        $minDate->modify("+{$minDelayDays} days");

        $this->view('order/create', [
            'title' => 'Commander - ' . $menu['title'],
            'menu' => $menu,
            'user' => $user,
            'minDate' => $minDate->format('Y-m-d'),
            'minPeople' => $menu['min_people'],
            'csrfToken' => Csrf::getToken()
        ]);
    }

    /**
     * Calcul du prix en AJAX
     */
    public function calculatePrice() {
        if (!$this->isPost()) {
            $this->json(['error' => 'Methode non autorisee'], 405);
        }

        $menuId = (int)($_POST['menu_id'] ?? 0);
        $numberOfPeople = (int)($_POST['number_of_people'] ?? 0);
        $postalCode = $_POST['postal_code'] ?? '';
        $distanceKm = (float)($_POST['distance_km'] ?? 0);

        if (!$menuId || !$numberOfPeople || !$postalCode) {
            $this->json(['error' => 'Parametres manquants'], 400);
        }

        $price = Order::calculatePrice($menuId, $numberOfPeople, $postalCode, $distanceKm);

        if (!$price) {
            $this->json(['error' => 'Menu introuvable'], 404);
        }

        $this->json($price);
    }

    /**
     * Enregistrement de la commande
     */
    public function store() {
        Auth::requireAuth();

        if (!$this->isPost()) {
            $this->redirect('/menu');
        }

        // Verification CSRF
        if (!Csrf::validateRequest()) {
            Auth::setFlash('error', 'Token de securite invalide. Veuillez reessayer.');
            $this->redirect('/menu');
        }

        $user = Auth::user();
        $menuId = (int)($_POST['menu_id'] ?? 0);

        // Verifier le menu
        $menu = Menu::findById($menuId);
        if (!$menu || !$menu['is_available'] || $menu['stock_quantity'] <= 0) {
            Auth::setFlash('error', 'Ce menu n\'est pas disponible.');
            $this->redirect('/menu');
        }

        // Validation des donnees
        $errors = [];

        $numberOfPeople = (int)($_POST['number_of_people'] ?? 0);
        if ($numberOfPeople < $menu['min_people']) {
            $errors[] = "Le nombre minimum de personnes est {$menu['min_people']}.";
        }

        $deliveryDate = $_POST['delivery_date'] ?? '';
        $dateValidation = Order::validateDeliveryDate($menuId, $deliveryDate);
        if (!$dateValidation['valid']) {
            $errors[] = $dateValidation['message'];
        }

        $deliveryTime = $_POST['delivery_time'] ?? '';
        if (empty($deliveryTime)) {
            $errors[] = "L'heure de livraison est obligatoire.";
        }

        $customerFirstName = trim($_POST['customer_first_name'] ?? '');
        $customerLastName = trim($_POST['customer_last_name'] ?? '');
        $customerEmail = trim($_POST['customer_email'] ?? '');
        $customerPhone = trim($_POST['customer_phone'] ?? '');

        if (empty($customerFirstName) || empty($customerLastName)) {
            $errors[] = "Le nom et prenom sont obligatoires.";
        }

        if (empty($customerEmail) || !filter_var($customerEmail, FILTER_VALIDATE_EMAIL)) {
            $errors[] = "L'email est invalide.";
        }

        if (empty($customerPhone)) {
            $errors[] = "Le telephone est obligatoire.";
        }

        $deliveryAddress = trim($_POST['delivery_address'] ?? '');
        $deliveryCity = trim($_POST['delivery_city'] ?? '');
        $deliveryPostalCode = trim($_POST['delivery_postal_code'] ?? '');

        if (empty($deliveryAddress) || empty($deliveryCity) || empty($deliveryPostalCode)) {
            $errors[] = "L'adresse de livraison complete est obligatoire.";
        }

        if (!preg_match('/^[0-9]{5}$/', $deliveryPostalCode)) {
            $errors[] = "Le code postal doit contenir 5 chiffres.";
        }

        // Si erreurs, retour au formulaire
        if (!empty($errors)) {
            Auth::setFlash('error', implode('<br>', $errors));
            $this->redirect("/order/create/{$menuId}");
        }

        // Calcul du prix
        $distanceKm = (float)($_POST['distance_km'] ?? 0);
        $priceData = Order::calculatePrice($menuId, $numberOfPeople, $deliveryPostalCode, $distanceKm);

        if (!$priceData) {
            Auth::setFlash('error', 'Erreur lors du calcul du prix.');
            $this->redirect("/order/create/{$menuId}");
        }

        // Creation de la commande
        $orderData = [
            'user_id' => $user['id'],
            'menu_id' => $menuId,
            'number_of_people' => $numberOfPeople,
            'customer_first_name' => $customerFirstName,
            'customer_last_name' => $customerLastName,
            'customer_email' => $customerEmail,
            'customer_phone' => $customerPhone,
            'delivery_address' => $deliveryAddress,
            'delivery_city' => $deliveryCity,
            'delivery_postal_code' => $deliveryPostalCode,
            'delivery_date' => $deliveryDate,
            'delivery_time' => $deliveryTime,
            'delivery_location' => trim($_POST['delivery_location'] ?? ''),
            'base_price' => $priceData['subtotal'],
            'discount_amount' => $priceData['discount'],
            'delivery_fee' => $priceData['delivery_fee'],
            'total_price' => $priceData['total'],
            'customer_notes' => trim($_POST['customer_notes'] ?? '')
        ];

        $orderId = Order::create($orderData);

        if (!$orderId) {
            Auth::setFlash('error', 'Erreur lors de la creation de la commande.');
            $this->redirect("/order/create/{$menuId}");
        }

        // Decrementer le stock
        Menu::decrementStock($menuId);

        // TODO: Envoyer email de confirmation

        Auth::setFlash('success', 'Votre commande a ete enregistree avec succes !');
        $this->redirect("/order/confirmation/{$orderId}");
    }

    /**
     * Page de confirmation de commande
     * @param int $id
     */
    public function confirmation($id = null) {
        Auth::requireAuth();

        if (!$id) {
            $this->redirect('/order/history');
        }

        $order = Order::findById($id);

        if (!$order || !Order::belongsToUser($id, Auth::id())) {
            Auth::setFlash('error', 'Commande non trouvee.');
            $this->redirect('/order/history');
        }

        $this->view('order/confirmation', [
            'title' => 'Confirmation de commande',
            'order' => $order,
            'statusLabels' => Order::getStatusLabels()
        ]);
    }

    /**
     * Historique des commandes du client
     */
    public function history() {
        Auth::requireAuth();

        $orders = Order::findByUser(Auth::id());

        $this->view('order/history', [
            'title' => 'Mes commandes',
            'orders' => $orders,
            'statusLabels' => Order::getStatusLabels(),
            'statusColors' => Order::getStatusColors()
        ]);
    }

    /**
     * Detail d'une commande
     * @param int $id
     */
    public function show($id = null) {
        Auth::requireAuth();

        if (!$id) {
            $this->redirect('/order/history');
        }

        $order = Order::findById($id);

        // Verification proprietaire ou employe
        $isOwner = Order::belongsToUser($id, Auth::id());
        $isEmployee = Auth::isEmployee();

        if (!$order || (!$isOwner && !$isEmployee)) {
            Auth::setFlash('error', 'Commande non trouvee.');
            $this->redirect('/order/history');
        }

        // Verifier si un avis peut etre laisse
        $canReview = $isOwner && Order::canBeReviewed($order);

        // Verifier si un avis existe deja
        $hasReview = false;
        if ($canReview) {
            require_once __DIR__ . '/../Models/Review.php';
            $hasReview = Review::existsForOrder($order['id'], Auth::id());
        }

        $this->view('order/show', [
            'title' => 'Commande ' . $order['order_number'],
            'order' => $order,
            'statusLabels' => Order::getStatusLabels(),
            'statusColors' => Order::getStatusColors(),
            'canCancel' => $isOwner && Order::canBeCancelled($order),
            'canReview' => $canReview && !$hasReview,
            'isEmployee' => $isEmployee,
            'csrfToken' => Csrf::getToken()
        ]);
    }

    /**
     * Annulation d'une commande
     * @param int $id
     */
    public function cancel($id = null) {
        Auth::requireAuth();

        if (!$this->isPost()) {
            $this->redirect('/order/history');
        }

        if (!Csrf::validateRequest()) {
            Auth::setFlash('error', 'Token de securite invalide.');
            $this->redirect('/order/history');
        }

        if (!$id) {
            Auth::setFlash('error', 'Commande non specifiee.');
            $this->redirect('/order/history');
        }

        $result = Order::cancel($id, Auth::id());

        if ($result) {
            Auth::setFlash('success', 'Votre commande a ete annulee.');
        } else {
            Auth::setFlash('error', 'Impossible d\'annuler cette commande. Elle a peut-etre deja ete acceptee.');
        }

        $this->redirect('/order/history');
    }
}
