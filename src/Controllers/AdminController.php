<?php
require_once __DIR__ . '/../Views/core/Controller.php';
require_once __DIR__ . '/../Core/Auth.php';
require_once __DIR__ . '/../Core/Csrf.php';
require_once __DIR__ . '/../Models/Menu.php';
require_once __DIR__ . '/../Models/Order.php';
require_once __DIR__ . '/../Models/Review.php';
require_once __DIR__ . '/../Models/User.php';
require_once __DIR__ . '/../Services/EmailService.php';

/**
 * AdminController - Gestion de l'espace administration
 */
class AdminController extends Controller {

    /**
     * Constructeur - Verifie les droits d'acces
     */
    public function __construct() {
        Auth::requireEmployee();
    }

    /**
     * Point d'entree par defaut - redirige vers le tableau de bord
     */
    public function index() {
        $this->redirect('/admin/dashboard');
    }

    /**
     * Tableau de bord
     */
    public function dashboard() {
        // Statistiques des commandes
        $orderStats = Order::countByStatus();
        $todayStats = Order::getTodayStats();

        // Avis en attente
        $pendingReviews = Review::countByStatus();

        // Commandes recentes
        $recentOrders = Order::findAll(['limit' => 10]);

        // Statistiques utilisateurs (admin seulement)
        $userStats = [];
        if (Auth::isAdmin()) {
            $userStats = [
                'clients' => User::countByRole('client'),
                'employees' => User::countByRole('employee'),
                'admins' => User::countByRole('admin')
            ];
        }

        $this->view('admin/dashboard', [
            'title' => 'Tableau de bord',
            'orderStats' => $orderStats,
            'todayStats' => $todayStats,
            'pendingReviews' => $pendingReviews['pending'] ?? 0,
            'recentOrders' => $recentOrders,
            'userStats' => $userStats,
            'statusLabels' => Order::getStatusLabels(),
            'statusColors' => Order::getStatusColors(),
            'isAdmin' => Auth::isAdmin()
        ]);
    }

    // ==================== GESTION DES MENUS ====================

    /**
     * Liste des menus
     */
    public function menus() {
        $menus = Menu::findAll();

        // Ajouter les statistiques a chaque menu
        foreach ($menus as &$menu) {
            $menu['rating'] = Menu::getAverageRating($menu['id']);
        }

        $this->view('admin/menus/index', [
            'title' => 'Gestion des menus',
            'menus' => $menus,
            'themeLabels' => Menu::getThemeLabels(),
            'dietaryTypeLabels' => Menu::getDietaryTypeLabels()
        ]);
    }

    /**
     * Formulaire de creation de menu
     */
    public function menuCreate() {
        $this->view('admin/menus/form', [
            'title' => 'Creer un menu',
            'menu' => null,
            'themes' => Menu::getThemeLabels(),
            'dietaryTypes' => Menu::getDietaryTypeLabels(),
            'csrfToken' => Csrf::getToken()
        ]);
    }

    /**
     * Enregistrement d'un nouveau menu
     */
    public function menuStore() {
        if (!$this->isPost()) {
            $this->redirect('/admin/menus');
        }

        if (!Csrf::validateRequest()) {
            Auth::setFlash('error', 'Token de securite invalide.');
            $this->redirect('/admin/menus');
        }

        // Validation
        $errors = $this->validateMenuData($_POST);

        if (!empty($errors)) {
            Auth::setFlash('error', implode('<br>', $errors));
            $this->redirect('/admin/menuCreate');
        }

        $menuData = $this->prepareMenuData($_POST);
        $menuId = Menu::create($menuData);

        if ($menuId) {
            Auth::setFlash('success', 'Menu cree avec succes.');
        } else {
            Auth::setFlash('error', 'Erreur lors de la creation du menu.');
        }

        $this->redirect('/admin/menus');
    }

    /**
     * Formulaire de modification de menu
     * @param int $id
     */
    public function menuEdit($id = null) {
        if (!$id) {
            $this->redirect('/admin/menus');
        }

        $menu = Menu::findById($id);

        if (!$menu) {
            Auth::setFlash('error', 'Menu non trouve.');
            $this->redirect('/admin/menus');
        }

        $this->view('admin/menus/form', [
            'title' => 'Modifier le menu',
            'menu' => $menu,
            'themes' => Menu::getThemeLabels(),
            'dietaryTypes' => Menu::getDietaryTypeLabels(),
            'csrfToken' => Csrf::getToken()
        ]);
    }

    /**
     * Mise a jour d'un menu
     * @param int $id
     */
    public function menuUpdate($id = null) {
        if (!$this->isPost() || !$id) {
            $this->redirect('/admin/menus');
        }

        if (!Csrf::validateRequest()) {
            Auth::setFlash('error', 'Token de securite invalide.');
            $this->redirect('/admin/menus');
        }

        $menu = Menu::findById($id);
        if (!$menu) {
            Auth::setFlash('error', 'Menu non trouve.');
            $this->redirect('/admin/menus');
        }

        // Validation
        $errors = $this->validateMenuData($_POST);

        if (!empty($errors)) {
            Auth::setFlash('error', implode('<br>', $errors));
            $this->redirect('/admin/menuEdit/' . $id);
        }

        $menuData = $this->prepareMenuData($_POST);
        $result = Menu::update($id, $menuData);

        if ($result) {
            Auth::setFlash('success', 'Menu mis a jour avec succes.');
        } else {
            Auth::setFlash('error', 'Erreur lors de la mise a jour.');
        }

        $this->redirect('/admin/menus');
    }

    /**
     * Suppression d'un menu (soft delete)
     * @param int $id
     */
    public function menuDelete($id = null) {
        if (!$this->isPost() || !$id) {
            $this->redirect('/admin/menus');
        }

        if (!Csrf::validateRequest()) {
            Auth::setFlash('error', 'Token de securite invalide.');
            $this->redirect('/admin/menus');
        }

        $result = Menu::delete($id);

        if ($result) {
            Auth::setFlash('success', 'Menu desactive avec succes.');
        } else {
            Auth::setFlash('error', 'Erreur lors de la suppression.');
        }

        $this->redirect('/admin/menus');
    }

    /**
     * Validation des donnees de menu
     * @param array $data
     * @return array Erreurs
     */
    private function validateMenuData($data) {
        $errors = [];

        if (empty(trim($data['title'] ?? ''))) {
            $errors[] = 'Le titre est obligatoire.';
        }

        if (empty(trim($data['description'] ?? ''))) {
            $errors[] = 'La description est obligatoire.';
        }

        if (empty($data['base_price']) || (float)$data['base_price'] <= 0) {
            $errors[] = 'Le prix de base doit etre superieur a 0.';
        }

        if (empty($data['min_people']) || (int)$data['min_people'] < 1) {
            $errors[] = 'Le nombre minimum de personnes doit etre au moins 1.';
        }

        return $errors;
    }

    /**
     * Prepare les donnees de menu pour l'enregistrement
     * @param array $data
     * @return array
     */
    private function prepareMenuData($data) {
        // Gerer l'upload d'image
        $imageUrl = $this->handleImageUpload();

        // Si pas de nouvelle image, garder l'ancienne
        if (!$imageUrl && !empty($data['current_image_url'])) {
            $imageUrl = $data['current_image_url'];
        }

        return [
            'title' => trim($data['title']),
            'description' => trim($data['description']),
            'theme' => $data['theme'] ?? 'classic',
            'dietary_type' => $data['dietary_type'] ?? 'classic',
            'min_people' => (int)($data['min_people'] ?? 1),
            'base_price' => (float)($data['base_price'] ?? 0),
            'price_per_extra_person' => (float)($data['price_per_extra_person'] ?? 0),
            'discount_threshold' => (int)($data['discount_threshold'] ?? 5),
            'stock_quantity' => (int)($data['stock_quantity'] ?? 10),
            'is_available' => isset($data['is_available']) ? 1 : 0,
            'min_order_delay_days' => (int)($data['min_order_delay_days'] ?? 3),
            'storage_instructions' => trim($data['storage_instructions'] ?? ''),
            'main_image_url' => $imageUrl
        ];
    }

    /**
     * Gere l'upload d'une image de menu
     * @return string|null URL de l'image ou null
     */
    private function handleImageUpload() {
        if (!isset($_FILES['main_image']) || $_FILES['main_image']['error'] !== UPLOAD_ERR_OK) {
            return null;
        }

        $file = $_FILES['main_image'];

        // Verifier le type MIME
        $allowedTypes = ['image/jpeg', 'image/png', 'image/webp'];
        $finfo = new finfo(FILEINFO_MIME_TYPE);
        $mimeType = $finfo->file($file['tmp_name']);

        if (!in_array($mimeType, $allowedTypes)) {
            Auth::setFlash('error', 'Type de fichier non autorise. Utilisez JPG, PNG ou WebP.');
            return null;
        }

        // Verifier la taille (max 2 Mo)
        if ($file['size'] > 2 * 1024 * 1024) {
            Auth::setFlash('error', 'L\'image ne doit pas depasser 2 Mo.');
            return null;
        }

        // Creer le dossier uploads si necessaire
        $uploadDir = __DIR__ . '/../../public/uploads/menus/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        // Generer un nom unique
        $extension = match($mimeType) {
            'image/jpeg' => 'jpg',
            'image/png' => 'png',
            'image/webp' => 'webp',
            default => 'jpg'
        };
        $filename = 'menu_' . uniqid() . '_' . time() . '.' . $extension;

        // Deplacer le fichier
        $destination = $uploadDir . $filename;
        if (move_uploaded_file($file['tmp_name'], $destination)) {
            return '/uploads/menus/' . $filename;
        }

        Auth::setFlash('error', 'Erreur lors de l\'upload de l\'image.');
        return null;
    }

    // ==================== GESTION DES COMMANDES ====================

    /**
     * Liste des commandes
     */
    public function orders() {
        $filters = [
            'status' => $_GET['status'] ?? null,
            'date_from' => $_GET['date_from'] ?? null,
            'date_to' => $_GET['date_to'] ?? null
        ];

        $orders = Order::findAll(array_filter($filters));

        $this->view('admin/orders/index', [
            'title' => 'Gestion des commandes',
            'orders' => $orders,
            'filters' => $filters,
            'statusLabels' => Order::getStatusLabels(),
            'statusColors' => Order::getStatusColors()
        ]);
    }

    /**
     * Detail d'une commande
     * @param int $id
     */
    public function orderShow($id = null) {
        if (!$id) {
            $this->redirect('/admin/orders');
        }

        $order = Order::findById($id);

        if (!$order) {
            Auth::setFlash('error', 'Commande non trouvee.');
            $this->redirect('/admin/orders');
        }

        $this->view('admin/orders/show', [
            'title' => 'Commande ' . $order['order_number'],
            'order' => $order,
            'statusLabels' => Order::getStatusLabels(),
            'statusColors' => Order::getStatusColors(),
            'csrfToken' => Csrf::getToken()
        ]);
    }

    /**
     * Mise a jour du statut d'une commande
     * @param int $id
     */
    public function orderUpdateStatus($id = null) {
        if (!$this->isPost() || !$id) {
            $this->redirect('/admin/orders');
        }

        if (!Csrf::validateRequest()) {
            Auth::setFlash('error', 'Token de securite invalide.');
            $this->redirect('/admin/orders');
        }

        $status = $_POST['status'] ?? '';
        $adminNotes = trim($_POST['admin_notes'] ?? '');

        $result = Order::updateStatus($id, $status, $adminNotes ?: null);

        if ($result) {
            Auth::setFlash('success', 'Statut mis a jour.');

            // Envoyer email au client
            $order = Order::findById($id);
            if ($order) {
                EmailService::sendOrderStatusUpdate($order, $status);

                // Envoyer invitation a laisser un avis si commande livree
                if ($status === Order::STATUS_DELIVERED) {
                    EmailService::sendReviewInvitation($order);
                }
            }
        } else {
            Auth::setFlash('error', 'Erreur lors de la mise a jour.');
        }

        $this->redirect('/admin/order/show/' . $id);
    }

    /**
     * Marquer le materiel comme retourne
     * @param int $id
     */
    public function orderEquipmentReturned($id = null) {
        if (!$this->isPost() || !$id) {
            $this->redirect('/admin/orders');
        }

        if (!Csrf::validateRequest()) {
            Auth::setFlash('error', 'Token de securite invalide.');
            $this->redirect('/admin/orders');
        }

        $result = Order::markEquipmentReturned($id);

        if ($result) {
            Auth::setFlash('success', 'Materiel marque comme retourne.');
        } else {
            Auth::setFlash('error', 'Erreur lors de la mise a jour.');
        }

        $this->redirect('/admin/order/show/' . $id);
    }

    // ==================== GESTION DES AVIS ====================

    /**
     * Liste des avis
     */
    public function reviews() {
        $filters = [
            'is_approved' => isset($_GET['status']) ? ($_GET['status'] === 'pending' ? 0 : 1) : null
        ];

        $reviews = Review::findAll(array_filter($filters, function($v) { return $v !== null; }));

        $this->view('admin/reviews/index', [
            'title' => 'Gestion des avis',
            'reviews' => $reviews,
            'filters' => $_GET,
            'csrfToken' => Csrf::getToken()
        ]);
    }

    /**
     * Approuver un avis
     * @param int $id
     */
    public function reviewApprove($id = null) {
        if (!$this->isPost() || !$id) {
            $this->redirect('/admin/reviews');
        }

        if (!Csrf::validateRequest()) {
            Auth::setFlash('error', 'Token de securite invalide.');
            $this->redirect('/admin/reviews');
        }

        $result = Review::approve($id, Auth::id());

        if ($result) {
            Auth::setFlash('success', 'Avis approuve.');
        } else {
            Auth::setFlash('error', 'Erreur lors de l\'approbation.');
        }

        $this->redirect('/admin/reviews?status=pending');
    }

    /**
     * Rejeter un avis
     * @param int $id
     */
    public function reviewReject($id = null) {
        if (!$this->isPost() || !$id) {
            $this->redirect('/admin/reviews');
        }

        if (!Csrf::validateRequest()) {
            Auth::setFlash('error', 'Token de securite invalide.');
            $this->redirect('/admin/reviews');
        }

        $result = Review::reject($id);

        if ($result) {
            Auth::setFlash('success', 'Avis rejete.');
        } else {
            Auth::setFlash('error', 'Erreur lors du rejet.');
        }

        $this->redirect('/admin/reviews?status=pending');
    }

    // ==================== GESTION DES EMPLOYES (Admin seulement) ====================

    /**
     * Liste des employes
     */
    public function employees() {
        Auth::requireAdmin();

        $employees = User::findAll(['role' => ['employee', 'admin']]);

        $this->view('admin/employees/index', [
            'title' => 'Gestion des employes',
            'employees' => $employees,
            'csrfToken' => Csrf::getToken()
        ]);
    }

    /**
     * Formulaire de creation d'employe
     */
    public function employeeCreate() {
        Auth::requireAdmin();

        $this->view('admin/employees/form', [
            'title' => 'Creer un employe',
            'employee' => null,
            'csrfToken' => Csrf::getToken()
        ]);
    }

    /**
     * Enregistrement d'un employe
     */
    public function employeeStore() {
        Auth::requireAdmin();

        if (!$this->isPost()) {
            $this->redirect('/admin/employees');
        }

        if (!Csrf::validateRequest()) {
            Auth::setFlash('error', 'Token de securite invalide.');
            $this->redirect('/admin/employees');
        }

        // Validation
        $errors = [];

        $firstName = trim($_POST['first_name'] ?? '');
        $lastName = trim($_POST['last_name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $role = $_POST['role'] ?? 'employee';

        if (empty($firstName) || empty($lastName)) {
            $errors[] = 'Le nom et prenom sont obligatoires.';
        }

        if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'L\'email est invalide.';
        }

        if (User::findByEmail($email)) {
            $errors[] = 'Cet email est deja utilise.';
        }

        if (!in_array($role, ['employee', 'admin'])) {
            $errors[] = 'Role invalide.';
        }

        if (!empty($errors)) {
            Auth::setFlash('error', implode('<br>', $errors));
            $this->redirect('/admin/employee/create');
        }

        // Generation d'un mot de passe temporaire
        $tempPassword = bin2hex(random_bytes(8));

        $userData = [
            'first_name' => $firstName,
            'last_name' => $lastName,
            'email' => $email,
            'phone' => trim($_POST['phone'] ?? ''),
            'password' => $tempPassword,
            'role' => $role,
            'is_active' => 1,
            'email_verified' => 1
        ];

        $userId = User::create($userData);

        if ($userId) {
            // TODO: Envoyer email avec mot de passe temporaire
            Auth::setFlash('success', "Employe cree. Mot de passe temporaire : {$tempPassword}");
        } else {
            Auth::setFlash('error', 'Erreur lors de la creation.');
        }

        $this->redirect('/admin/employees');
    }

    /**
     * Activer/Desactiver un employe
     * @param int $id
     */
    public function employeeToggle($id = null) {
        Auth::requireAdmin();

        if (!$this->isPost() || !$id) {
            $this->redirect('/admin/employees');
        }

        if (!Csrf::validateRequest()) {
            Auth::setFlash('error', 'Token de securite invalide.');
            $this->redirect('/admin/employees');
        }

        // Ne pas pouvoir se desactiver soi-meme
        if ($id == Auth::id()) {
            Auth::setFlash('error', 'Vous ne pouvez pas vous desactiver vous-meme.');
            $this->redirect('/admin/employees');
        }

        $employee = User::findById($id);

        if (!$employee || !in_array($employee['role'], ['employee', 'admin'])) {
            Auth::setFlash('error', 'Employe non trouve.');
            $this->redirect('/admin/employees');
        }

        $newStatus = $employee['is_active'] ? 0 : 1;
        $result = User::setActive($id, $newStatus);

        if ($result) {
            $action = $newStatus ? 'active' : 'desactive';
            Auth::setFlash('success', "Employe {$action} avec succes.");
        } else {
            Auth::setFlash('error', 'Erreur lors de la mise a jour.');
        }

        $this->redirect('/admin/employees');
    }

    // ==================== STATISTIQUES ====================

    /**
     * Page des statistiques (Admin seulement)
     */
    public function stats() {
        Auth::requireAdmin();

        // Statistiques basiques depuis MySQL
        $ordersByStatus = Order::countByStatus();
        $todayStats = Order::getTodayStats();

        // TODO: Recuperer les stats depuis MongoDB

        $this->view('admin/stats/index', [
            'title' => 'Statistiques',
            'ordersByStatus' => $ordersByStatus,
            'todayStats' => $todayStats,
            'statusLabels' => Order::getStatusLabels(),
            'statusColors' => Order::getStatusColors()
        ]);
    }
}
