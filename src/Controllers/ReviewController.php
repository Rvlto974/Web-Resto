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
            $this->redirect('/order/history');
        }

        // Verification CSRF
        if (!Csrf::validateRequest()) {
            Auth::setFlash('error', 'Token de securite invalide.');
            $this->redirect('/order/history');
        }

        $orderId = (int)($_POST['order_id'] ?? 0);
        $order = Order::findById($orderId);

        // Verifications
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

        // Validation
        $rating = (int)($_POST['rating'] ?? 0);
        $comment = trim($_POST['comment'] ?? '');

        if ($rating < 1 || $rating > 5) {
            Auth::setFlash('error', 'La note doit etre comprise entre 1 et 5.');
            $this->redirect('/review/create/' . $orderId);
        }

        // Creation de l'avis
        $reviewData = [
            'user_id' => Auth::id(),
            'order_id' => $orderId,
            'menu_id' => $order['menu_id'],
            'rating' => $rating,
            'comment' => $comment
        ];

        $reviewId = Review::create($reviewData);

        if ($reviewId) {
            Auth::setFlash('success', 'Merci pour votre avis ! Il sera publie apres validation.');
        } else {
            Auth::setFlash('error', 'Une erreur est survenue lors de l\'enregistrement.');
        }

        $this->redirect('/order/show/' . $orderId);
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
