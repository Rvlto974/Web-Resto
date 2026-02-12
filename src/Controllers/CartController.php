<?php
require_once __DIR__ . '/../Views/core/Controller.php';
require_once __DIR__ . '/../Core/Auth.php';
require_once __DIR__ . '/../Core/Csrf.php';
require_once __DIR__ . '/../Models/Cart.php';
require_once __DIR__ . '/../Models/Menu.php';

/**
 * CartController - Gestion du panier
 */
class CartController extends Controller {

    /**
     * Affiche le panier
     */
    public function index() {
        $totals = Cart::calculateTotals();

        $this->view('cart/index', [
            'title' => 'Mon Panier',
            'cart' => $totals,
            'csrf' => Csrf::getInputField()
        ]);
    }

    /**
     * Ajoute un menu au panier
     */
    public function add() {
        if (!$this->isPost()) {
            $this->redirect('/menu');
        }

        if (!Csrf::validateRequest()) {
            Auth::setFlash('error', 'Session expiree. Veuillez reessayer.');
            $this->redirect('/menu');
        }

        $menuId = (int)($_POST['menu_id'] ?? 0);
        $numberOfPeople = (int)($_POST['number_of_people'] ?? 1);
        $quantity = (int)($_POST['quantity'] ?? 1);

        if ($menuId <= 0) {
            Auth::setFlash('error', 'Menu invalide.');
            $this->redirect('/menu');
        }

        // Verifier le menu
        $menu = Menu::findById($menuId);
        if (!$menu) {
            Auth::setFlash('error', 'Menu introuvable.');
            $this->redirect('/menu');
        }

        // Verifier le nombre minimum de personnes
        if ($numberOfPeople < $menu['min_people']) {
            $numberOfPeople = $menu['min_people'];
        }

        if (Cart::add($menuId, $numberOfPeople, $quantity)) {
            Auth::setFlash('success', "\"" . htmlspecialchars($menu['title']) . "\" a ete ajoute au panier.");
        } else {
            Auth::setFlash('error', 'Impossible d\'ajouter ce menu au panier.');
        }

        // Redirection AJAX ou normale
        if ($this->isAjax()) {
            $this->json([
                'success' => true,
                'cart_count' => Cart::count(),
                'message' => 'Menu ajoute au panier'
            ]);
        } else {
            $this->redirect('/cart');
        }
    }

    /**
     * Met a jour un article du panier
     */
    public function update() {
        if (!$this->isPost()) {
            $this->redirect('/cart');
        }

        if (!Csrf::validateRequest()) {
            if ($this->isAjax()) {
                $this->json(['success' => false, 'message' => 'Session expiree']);
            }
            Auth::setFlash('error', 'Session expiree.');
            $this->redirect('/cart');
        }

        $index = (int)($_POST['index'] ?? -1);
        $quantity = isset($_POST['quantity']) ? (int)$_POST['quantity'] : null;
        $numberOfPeople = isset($_POST['number_of_people']) ? (int)$_POST['number_of_people'] : null;

        if ($index < 0) {
            if ($this->isAjax()) {
                $this->json(['success' => false, 'message' => 'Article invalide']);
            }
            Auth::setFlash('error', 'Article invalide.');
            $this->redirect('/cart');
        }

        if (Cart::update($index, $quantity, $numberOfPeople)) {
            if ($this->isAjax()) {
                $totals = Cart::calculateTotals();
                $this->json([
                    'success' => true,
                    'cart' => $totals,
                    'cart_count' => Cart::count()
                ]);
            }
            Auth::setFlash('success', 'Panier mis a jour.');
        } else {
            if ($this->isAjax()) {
                $this->json(['success' => false, 'message' => 'Erreur de mise a jour']);
            }
            Auth::setFlash('error', 'Erreur lors de la mise a jour.');
        }

        $this->redirect('/cart');
    }

    /**
     * Supprime un article du panier
     */
    public function remove() {
        if (!$this->isPost()) {
            $this->redirect('/cart');
        }

        if (!Csrf::validateRequest()) {
            Auth::setFlash('error', 'Session expiree.');
            $this->redirect('/cart');
        }

        $index = (int)($_POST['index'] ?? -1);

        if ($index >= 0 && Cart::remove($index)) {
            if ($this->isAjax()) {
                $totals = Cart::calculateTotals();
                $this->json([
                    'success' => true,
                    'cart' => $totals,
                    'cart_count' => Cart::count()
                ]);
            }
            Auth::setFlash('success', 'Article supprime du panier.');
        } else {
            if ($this->isAjax()) {
                $this->json(['success' => false, 'message' => 'Erreur de suppression']);
            }
            Auth::setFlash('error', 'Erreur lors de la suppression.');
        }

        $this->redirect('/cart');
    }

    /**
     * Vide le panier
     */
    public function clear() {
        if (!$this->isPost()) {
            $this->redirect('/cart');
        }

        if (!Csrf::validateRequest()) {
            Auth::setFlash('error', 'Session expiree.');
            $this->redirect('/cart');
        }

        Cart::clear();
        Auth::setFlash('success', 'Le panier a ete vide.');

        if ($this->isAjax()) {
            $this->json(['success' => true, 'cart_count' => 0]);
        }

        $this->redirect('/cart');
    }

    /**
     * Page de checkout (paiement)
     */
    public function checkout() {
        // Verifier que l'utilisateur est connecte
        Auth::requireAuth();

        // Verifier que le panier n'est pas vide
        if (Cart::isEmpty()) {
            Auth::setFlash('error', 'Votre panier est vide.');
            $this->redirect('/menu');
        }

        // Verifier le stock
        $stockValidation = Cart::validateStock();
        if (!$stockValidation['valid']) {
            foreach ($stockValidation['errors'] as $error) {
                Auth::setFlash('error', $error);
            }
            $this->redirect('/cart');
        }

        $user = Auth::user();
        $totals = Cart::calculateTotals($user['postal_code'] ?? null);
        $minDeliveryDate = Cart::getMinDeliveryDate();

        $this->view('cart/checkout', [
            'title' => 'Finaliser la commande',
            'cart' => $totals,
            'user' => $user,
            'minDeliveryDate' => $minDeliveryDate,
            'csrf' => Csrf::getInputField()
        ]);
    }

    /**
     * Retourne le nombre d'articles (API)
     */
    public function count() {
        $this->json([
            'count' => Cart::count()
        ]);
    }

    /**
     * Verifie si la requete est AJAX
     * @return bool
     */
    private function isAjax() {
        return !empty($_SERVER['HTTP_X_REQUESTED_WITH']) &&
               strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
    }
}
