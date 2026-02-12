<?php
require_once __DIR__ . '/../Views/core/Controller.php';
require_once __DIR__ . '/../Core/Auth.php';
require_once __DIR__ . '/../Models/Order.php';
require_once __DIR__ . '/../Models/OrderItem.php';
require_once __DIR__ . '/../Services/EmailService.php';

/**
 * PaymentController - Gestion des paiements Stripe
 */
class PaymentController extends Controller {

    /**
     * Cree une session Stripe Checkout et redirige
     * @param int $orderId
     */
    public function createSession($orderId = null) {
        Auth::requireAuth();

        if (!$orderId) {
            Auth::setFlash('error', 'Commande invalide.');
            $this->redirect('/cart');
        }

        $order = Order::findById($orderId);
        if (!$order) {
            Auth::setFlash('error', 'Commande introuvable.');
            $this->redirect('/cart');
        }

        // Verifier que la commande appartient a l'utilisateur
        if ($order['user_id'] != Auth::id()) {
            Auth::setFlash('error', 'Acces refuse.');
            $this->redirect('/');
        }

        // Verifier que le paiement est en attente
        if ($order['payment_status'] !== 'pending' || $order['payment_method'] !== 'stripe') {
            Auth::setFlash('error', 'Cette commande ne necessite pas de paiement en ligne.');
            $this->redirect('/order/confirmation/' . $orderId);
        }

        try {
            \Stripe\Stripe::setApiKey(STRIPE_SECRET_KEY);

            // Recuperer les articles de la commande
            $items = OrderItem::findByOrder($orderId);

            $lineItems = [];

            if (!empty($items)) {
                // Commande multi-menus
                foreach ($items as $item) {
                    $lineItems[] = [
                        'price_data' => [
                            'currency' => STRIPE_CURRENCY,
                            'product_data' => [
                                'name' => $item['menu_title'],
                                'description' => $item['number_of_people'] . ' personne(s)',
                            ],
                            'unit_amount' => (int)($item['unit_price'] * 100),
                        ],
                        'quantity' => $item['quantity'],
                    ];
                }
            } else {
                // Commande simple (ancien systeme)
                $lineItems[] = [
                    'price_data' => [
                        'currency' => STRIPE_CURRENCY,
                        'product_data' => [
                            'name' => $order['menu_title'] ?? 'Menu',
                            'description' => $order['number_of_people'] . ' personne(s)',
                        ],
                        'unit_amount' => (int)($order['base_price'] * 100),
                    ],
                    'quantity' => 1,
                ];
            }

            // Ajouter les frais de livraison si applicable
            if ($order['delivery_fee'] > 0) {
                $lineItems[] = [
                    'price_data' => [
                        'currency' => STRIPE_CURRENCY,
                        'product_data' => [
                            'name' => 'Frais de livraison',
                        ],
                        'unit_amount' => (int)($order['delivery_fee'] * 100),
                    ],
                    'quantity' => 1,
                ];
            }

            // Creer la session Checkout
            $session = \Stripe\Checkout\Session::create([
                'payment_method_types' => ['card'],
                'line_items' => $lineItems,
                'mode' => 'payment',
                'success_url' => APP_URL . '/payment/success?session_id={CHECKOUT_SESSION_ID}',
                'cancel_url' => APP_URL . '/payment/cancel?order_id=' . $orderId,
                'customer_email' => $order['customer_email'],
                'metadata' => [
                    'order_id' => $orderId,
                    'order_number' => $order['order_number'],
                ],
                'locale' => STRIPE_LOCALE,
            ]);

            // Sauvegarder l'ID de session
            Order::updateStripeSession($orderId, $session->id);

            // Rediriger vers Stripe
            header('Location: ' . $session->url);
            exit;

        } catch (\Stripe\Exception\ApiErrorException $e) {
            error_log('Stripe API Error: ' . $e->getMessage());
            Auth::setFlash('error', 'Erreur lors de la creation du paiement. Veuillez reessayer.');
            $this->redirect('/order/confirmation/' . $orderId);
        }
    }

    /**
     * Page de succes apres paiement
     */
    public function success() {
        $sessionId = $_GET['session_id'] ?? null;

        if (!$sessionId) {
            Auth::setFlash('error', 'Session de paiement invalide.');
            $this->redirect('/');
        }

        try {
            \Stripe\Stripe::setApiKey(STRIPE_SECRET_KEY);

            // Recuperer la session Stripe
            $session = \Stripe\Checkout\Session::retrieve($sessionId);

            // Trouver la commande
            $orderId = $session->metadata->order_id ?? null;
            if (!$orderId) {
                $order = Order::findByStripeSession($sessionId);
                $orderId = $order['id'] ?? null;
            }

            if ($orderId) {
                $order = Order::findById($orderId);

                // Mettre a jour le statut si pas deja fait par le webhook
                if ($order && $order['payment_status'] === 'pending') {
                    Order::updatePaymentStatus($orderId, 'paid', $session->payment_intent);

                    // Envoyer l'email de confirmation
                    $order = Order::findById($orderId);
                    EmailService::sendOrderConfirmation($order);
                }

                Auth::setFlash('success', 'Paiement reussi ! Votre commande a ete confirmee.');
                $this->redirect('/order/confirmation/' . $orderId);
            }

        } catch (\Exception $e) {
            error_log('Stripe Success Error: ' . $e->getMessage());
        }

        $this->view('payment/success', [
            'title' => 'Paiement reussi'
        ]);
    }

    /**
     * Page d'annulation de paiement
     */
    public function cancel() {
        $orderId = $_GET['order_id'] ?? null;

        if ($orderId) {
            $order = Order::findById($orderId);
            if ($order && $order['payment_status'] === 'pending') {
                Order::updatePaymentStatus($orderId, 'failed');
            }
        }

        $this->view('payment/cancel', [
            'title' => 'Paiement annule',
            'orderId' => $orderId
        ]);
    }

    /**
     * Webhook Stripe
     */
    public function webhook() {
        $payload = file_get_contents('php://input');
        $sigHeader = $_SERVER['HTTP_STRIPE_SIGNATURE'] ?? '';

        try {
            $event = \Stripe\Webhook::constructEvent(
                $payload,
                $sigHeader,
                STRIPE_WEBHOOK_SECRET
            );
        } catch (\UnexpectedValueException $e) {
            // Payload invalide
            http_response_code(400);
            exit;
        } catch (\Stripe\Exception\SignatureVerificationException $e) {
            // Signature invalide
            http_response_code(400);
            exit;
        }

        // Traiter l'evenement
        switch ($event->type) {
            case 'checkout.session.completed':
                $session = $event->data->object;
                $this->handleCheckoutCompleted($session);
                break;

            case 'payment_intent.succeeded':
                $paymentIntent = $event->data->object;
                $this->handlePaymentSucceeded($paymentIntent);
                break;

            case 'payment_intent.payment_failed':
                $paymentIntent = $event->data->object;
                $this->handlePaymentFailed($paymentIntent);
                break;
        }

        http_response_code(200);
        echo json_encode(['received' => true]);
    }

    /**
     * Traite le checkout complete
     */
    private function handleCheckoutCompleted($session) {
        $orderId = $session->metadata->order_id ?? null;

        if (!$orderId) {
            $order = Order::findByStripeSession($session->id);
            $orderId = $order['id'] ?? null;
        }

        if ($orderId) {
            $order = Order::findById($orderId);

            if ($order && $order['payment_status'] === 'pending') {
                Order::updatePaymentStatus($orderId, 'paid', $session->payment_intent);

                // Envoyer l'email de confirmation
                $order = Order::findById($orderId);
                EmailService::sendOrderConfirmation($order);

                error_log("Webhook: Order $orderId marked as paid");
            }
        }
    }

    /**
     * Traite le paiement reussi
     */
    private function handlePaymentSucceeded($paymentIntent) {
        // Le checkout.session.completed gere deja ce cas
        error_log("Payment succeeded: " . $paymentIntent->id);
    }

    /**
     * Traite l'echec de paiement
     */
    private function handlePaymentFailed($paymentIntent) {
        error_log("Payment failed: " . $paymentIntent->id);
        // On pourrait chercher la commande et la marquer comme echouee
    }
}
