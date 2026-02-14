<?php
require_once __DIR__ . '/../Views/core/Controller.php';
require_once __DIR__ . '/../Core/Auth.php';
require_once __DIR__ . '/../Core/Csrf.php';
require_once __DIR__ . '/../Models/Review.php';
require_once __DIR__ . '/../Models/Order.php';

/**
 * ReviewController - Gestion des avis clients
 */
class ReviewController extends Controller {

    /**
     * Formulaire de creation d'avis
     * @param int $orderId
     */
    public function create($orderId = null) {
        Auth::requireAuth();

        if (!$orderId) {
            Auth::setFlash('error', 'Commande non specifiee.');
            $this->redirect('/order/history');
        }

        $order = Order::findById($orderId);

        // Verifier que la commande appartient a l'utilisateur
        if (!$order || !Order::belongsToUser($orderId, Auth::id())) {
            Auth::setFlash('error', 'Commande non trouvee.');
            $this->redirect('/order/history');
        }

        // Verifier que la commande peut recevoir un avis
        if (!Order::canBeReviewed($order)) {
            Auth::setFlash('error', 'Vous ne pouvez pas encore laisser d\'avis sur cette commande.');
            $this->redirect('/order/show/' . $orderId);
        }

        // Verifier qu'un avis n'existe pas deja
        if (Review::existsForOrder($orderId, Auth::id())) {
            Auth::setFlash('info', 'Vous avez deja laisse un avis pour cette commande.');
            $this->redirect('/order/show/' . $orderId);
        }

        $this->view('review/create', [
            'title' => 'Laisser un avis',
            'order' => $order,
            'csrfToken' => Csrf::getToken()
        ]);
    }

    /**
     * Enregistrement de l'avis
     */
    public function store() {
        Auth::requireAuth();

        if (!$this->isPost()) {
            $this->redirect('/menu');
        }

        // Verification CSRF
        if (!Csrf::validateRequest()) {
            Auth::setFlash('error', 'Token de securite invalide.');
            $this->redirect('/menu');
        }

        $orderId = (int)($_POST['order_id'] ?? 0);
        $menuId = (int)($_POST['menu_id'] ?? 0);

        // Cas 1: Avis depuis la page commande (avec order_id)
        if ($orderId > 0) {
            $order = Order::findById($orderId);

            if (!$order || !Order::belongsToUser($orderId, Auth::id())) {
                Auth::setFlash('error', 'Commande non trouvee.');
                $this->redirect('/order/history');
            }

            if (!Order::canBeReviewed($order)) {
                Auth::setFlash('error', 'Vous ne pouvez pas laisser d\'avis sur cette commande.');
                $this->redirect('/order/show/' . $orderId);
            }

            if (Review::existsForOrder($orderId, Auth::id())) {
                Auth::setFlash('info', 'Vous avez deja laisse un avis pour cette commande.');
                $this->redirect('/order/show/' . $orderId);
            }

            $menuId = $order['menu_id'];
            $redirectUrl = '/order/show/' . $orderId;
        }
        // Cas 2: Avis depuis la page menu (avec menu_id)
        elseif ($menuId > 0) {
            // Verifier que l'utilisateur n'a pas deja laisse un avis sur ce menu
            if (Review::existsForMenu($menuId, Auth::id())) {
                Auth::setFlash('info', 'Vous avez deja laisse un avis sur ce menu.');
                $this->redirect('/menu/show/' . $menuId);
            }
            $redirectUrl = '/menu/show/' . $menuId;
        } else {
            Auth::setFlash('error', 'Menu non specifie.');
            $this->redirect('/menu');
        }

        // Validation
        $rating = (int)($_POST['rating'] ?? 0);
        $comment = trim($_POST['comment'] ?? '');

        if ($rating < 1 || $rating > 5) {
            Auth::setFlash('error', 'La note doit etre comprise entre 1 et 5.');
            $this->redirect($redirectUrl);
        }

        // Creation de l'avis
        $reviewData = [
            'user_id' => Auth::id(),
            'order_id' => $orderId ?: null,
            'menu_id' => $menuId,
            'rating' => $rating,
            'comment' => $comment
        ];

        $reviewId = Review::create($reviewData);

        if ($reviewId) {
            Auth::setFlash('success', 'Merci pour votre avis ! Il sera visible apres validation par notre equipe.');
        } else {
            Auth::setFlash('error', 'Une erreur est survenue lors de l\'enregistrement.');
        }

        $this->redirect($redirectUrl);
    }

    /**
     * Liste des avis de l'utilisateur
     */
    public function myReviews() {
        Auth::requireAuth();

        $reviews = Review::findByUser(Auth::id());

        $this->view('review/my-reviews', [
            'title' => 'Mes avis',
            'reviews' => $reviews
        ]);
    }
}
