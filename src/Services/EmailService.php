<?php
/**
 * EmailService - Gestion de l'envoi d'emails
 *
 * Cette classe gere l'envoi d'emails transactionnels.
 * En production, elle devrait utiliser un service comme SendGrid, Mailgun, etc.
 */
class EmailService {

    private static $fromEmail = 'noreply@viteetgourmand.fr';
    private static $fromName = 'Vite & Gourmand';

    /**
     * Configuration
     */
    private static $config = [
        'site_name' => 'Vite & Gourmand',
        'site_url' => '',
        'support_email' => 'contact@viteetgourmand.fr',
        'phone' => '05 56 78 90 12'
    ];

    /**
     * Initialise l'URL du site depuis les variables d'environnement
     */
    private static function init() {
        if (empty(self::$config['site_url'])) {
            $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
            $host = $_SERVER['HTTP_HOST'] ?? 'localhost';
            self::$config['site_url'] = $protocol . '://' . $host;
        }
    }

    /**
     * Envoie un email
     * @param string $to Adresse du destinataire
     * @param string $subject Sujet
     * @param string $htmlBody Corps HTML
     * @return bool
     */
    private static function send($to, $subject, $htmlBody) {
        self::init();

        // Headers
        $headers = [
            'MIME-Version: 1.0',
            'Content-type: text/html; charset=UTF-8',
            'From: ' . self::$fromName . ' <' . self::$fromEmail . '>',
            'Reply-To: ' . self::$config['support_email'],
            'X-Mailer: PHP/' . phpversion()
        ];

        // Envoyer l'email
        $result = mail($to, $subject, $htmlBody, implode("\r\n", $headers));

        // Log en cas d'echec
        if (!$result) {
            error_log("Echec envoi email a {$to}: {$subject}");
        }

        return $result;
    }

    /**
     * Genere le template de base des emails
     * @param string $content Contenu HTML
     * @return string
     */
    private static function getTemplate($content) {
        self::init();

        return '
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>' . self::$config['site_name'] . '</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333; margin: 0; padding: 0; background-color: #f5f5f5;">
    <table width="100%" cellpadding="0" cellspacing="0" style="max-width: 600px; margin: 0 auto; background-color: #ffffff;">
        <!-- Header -->
        <tr>
            <td style="background-color: #2E7D32; padding: 20px; text-align: center;">
                <h1 style="color: #ffffff; margin: 0; font-family: Georgia, serif;">
                    Vite & Gourmand
                </h1>
                <p style="color: #ffffff; margin: 5px 0 0; font-size: 14px;">
                    Traiteur depuis 25 ans
                </p>
            </td>
        </tr>

        <!-- Content -->
        <tr>
            <td style="padding: 30px;">
                ' . $content . '
            </td>
        </tr>

        <!-- Footer -->
        <tr>
            <td style="background-color: #333; color: #fff; padding: 20px; text-align: center; font-size: 12px;">
                <p style="margin: 0 0 10px;">
                    <strong>Vite & Gourmand</strong><br>
                    123 Rue de la Gastronomie, 33000 Bordeaux<br>
                    Tel: ' . self::$config['phone'] . '
                </p>
                <p style="margin: 0;">
                    <a href="' . self::$config['site_url'] . '" style="color: #FF8F00;">Visitez notre site</a>
                </p>
            </td>
        </tr>
    </table>
</body>
</html>';
    }

    /**
     * Email de bienvenue apres inscription
     * @param array $user
     * @return bool
     */
    public static function sendWelcome($user) {
        $content = '
            <h2 style="color: #2E7D32; margin-top: 0;">Bienvenue ' . htmlspecialchars($user['first_name']) . ' !</h2>

            <p>Nous sommes ravis de vous compter parmi nos clients.</p>

            <p>
                Votre compte a ete cree avec succes. Vous pouvez desormais :
            </p>
            <ul>
                <li>Consulter nos menus traiteur</li>
                <li>Passer des commandes en ligne</li>
                <li>Suivre vos commandes</li>
                <li>Laisser des avis</li>
            </ul>

            <p style="text-align: center; margin: 30px 0;">
                <a href="' . self::$config['site_url'] . '/menu" style="display: inline-block; background-color: #FF8F00; color: #fff; padding: 12px 30px; text-decoration: none; border-radius: 5px; font-weight: bold;">
                    Decouvrir nos menus
                </a>
            </p>

            <p>A bientot !</p>
            <p>L\'equipe Vite & Gourmand</p>
        ';

        return self::send(
            $user['email'],
            'Bienvenue chez Vite & Gourmand !',
            self::getTemplate($content)
        );
    }

    /**
     * Email de confirmation de commande
     * @param array $order
     * @return bool
     */
    public static function sendOrderConfirmation($order) {
        $deliveryDate = date('d/m/Y', strtotime($order['delivery_date']));
        $deliveryTime = date('H:i', strtotime($order['delivery_time']));

        $content = '
            <h2 style="color: #2E7D32; margin-top: 0;">Confirmation de commande</h2>

            <p>Bonjour ' . htmlspecialchars($order['customer_first_name']) . ',</p>

            <p>Nous avons bien recu votre commande. Voici le recapitulatif :</p>

            <table width="100%" cellpadding="10" style="border: 1px solid #ddd; margin: 20px 0;">
                <tr style="background-color: #f5f5f5;">
                    <td><strong>Numero de commande</strong></td>
                    <td style="color: #FF8F00; font-weight: bold;">' . htmlspecialchars($order['order_number']) . '</td>
                </tr>
                <tr>
                    <td><strong>Menu</strong></td>
                    <td>' . htmlspecialchars($order['menu_title']) . '</td>
                </tr>
                <tr style="background-color: #f5f5f5;">
                    <td><strong>Nombre de personnes</strong></td>
                    <td>' . $order['number_of_people'] . ' personnes</td>
                </tr>
                <tr>
                    <td><strong>Date de livraison</strong></td>
                    <td>' . $deliveryDate . ' a ' . $deliveryTime . '</td>
                </tr>
                <tr style="background-color: #f5f5f5;">
                    <td><strong>Adresse</strong></td>
                    <td>' . htmlspecialchars($order['delivery_address']) . '<br>' . htmlspecialchars($order['delivery_postal_code'] . ' ' . $order['delivery_city']) . '</td>
                </tr>
                <tr>
                    <td><strong>Total</strong></td>
                    <td style="font-size: 18px; color: #FF8F00; font-weight: bold;">' . number_format($order['total_price'], 2, ',', ' ') . ' EUR</td>
                </tr>
            </table>

            <p>
                <strong>Rappel :</strong> Le paiement s\'effectue a la livraison.
            </p>

            <p style="text-align: center; margin: 30px 0;">
                <a href="' . self::$config['site_url'] . '/order/show/' . $order['id'] . '" style="display: inline-block; background-color: #2E7D32; color: #fff; padding: 12px 30px; text-decoration: none; border-radius: 5px; font-weight: bold;">
                    Voir ma commande
                </a>
            </p>

            <p>Merci pour votre confiance !</p>
            <p>L\'equipe Vite & Gourmand</p>
        ';

        return self::send(
            $order['customer_email'],
            'Confirmation de commande ' . $order['order_number'],
            self::getTemplate($content)
        );
    }

    /**
     * Email de creation de compte employe
     * @param array $employee
     * @param string $tempPassword
     * @return bool
     */
    public static function sendEmployeeCreated($employee, $tempPassword) {
        $content = '
            <h2 style="color: #2E7D32; margin-top: 0;">Bienvenue dans l\'equipe !</h2>

            <p>Bonjour ' . htmlspecialchars($employee['first_name']) . ',</p>

            <p>Un compte employe a ete cree pour vous sur la plateforme Vite & Gourmand.</p>

            <table width="100%" cellpadding="10" style="background-color: #f5f5f5; margin: 20px 0;">
                <tr>
                    <td><strong>Email de connexion</strong></td>
                    <td>' . htmlspecialchars($employee['email']) . '</td>
                </tr>
                <tr>
                    <td><strong>Mot de passe temporaire</strong></td>
                    <td style="font-family: monospace; font-size: 16px; color: #FF8F00;">' . htmlspecialchars($tempPassword) . '</td>
                </tr>
            </table>

            <p style="color: #dc3545;">
                <strong>Important :</strong> Veuillez modifier votre mot de passe lors de votre premiere connexion.
            </p>

            <p style="text-align: center; margin: 30px 0;">
                <a href="' . self::$config['site_url'] . '/user/login" style="display: inline-block; background-color: #2E7D32; color: #fff; padding: 12px 30px; text-decoration: none; border-radius: 5px; font-weight: bold;">
                    Se connecter
                </a>
            </p>

            <p>Bienvenue dans l\'equipe !</p>
            <p>L\'administration Vite & Gourmand</p>
        ';

        return self::send(
            $employee['email'],
            'Votre compte employe Vite & Gourmand',
            self::getTemplate($content)
        );
    }

    /**
     * Email de rappel retour materiel
     * @param array $order
     * @return bool
     */
    public static function sendEquipmentReminder($order) {
        $deliveryDate = date('d/m/Y', strtotime($order['delivery_date']));

        $content = '
            <h2 style="color: #FF8F00; margin-top: 0;">Rappel : Retour du materiel</h2>

            <p>Bonjour ' . htmlspecialchars($order['customer_first_name']) . ',</p>

            <p>
                Nous esperons que votre evenement s\'est bien passe !
            </p>

            <p>
                Pour rappel, le materiel de service prete avec votre commande du <strong>' . $deliveryDate . '</strong>
                (commande n ' . htmlspecialchars($order['order_number']) . ') doit nous etre retourne.
            </p>

            <p>
                Merci de nous contacter pour organiser la recuperation du materiel :
            </p>
            <ul>
                <li>Telephone : ' . self::$config['phone'] . '</li>
                <li>Email : ' . self::$config['support_email'] . '</li>
            </ul>

            <p>
                <strong>Rappel :</strong> Le materiel non retourne ou endommage sera facture.
            </p>

            <p>Merci de votre comprehension.</p>
            <p>L\'equipe Vite & Gourmand</p>
        ';

        return self::send(
            $order['customer_email'],
            'Rappel : Retour du materiel - Commande ' . $order['order_number'],
            self::getTemplate($content)
        );
    }

    /**
     * Email d'invitation a laisser un avis
     * @param array $order
     * @return bool
     */
    public static function sendReviewInvitation($order) {
        $content = '
            <h2 style="color: #2E7D32; margin-top: 0;">Votre avis nous interesse !</h2>

            <p>Bonjour ' . htmlspecialchars($order['customer_first_name']) . ',</p>

            <p>
                Nous esperons que vous avez apprecie votre menu <strong>' . htmlspecialchars($order['menu_title']) . '</strong>.
            </p>

            <p>
                Votre avis est precieux pour nous aider a ameliorer nos services et guider les autres clients dans leurs choix.
            </p>

            <p style="text-align: center; margin: 30px 0;">
                <a href="' . self::$config['site_url'] . '/review/create/' . $order['id'] . '" style="display: inline-block; background-color: #FF8F00; color: #fff; padding: 12px 30px; text-decoration: none; border-radius: 5px; font-weight: bold;">
                    Donner mon avis
                </a>
            </p>

            <p>
                Cela ne prend que quelques secondes !
            </p>

            <p>Merci pour votre confiance.</p>
            <p>L\'equipe Vite & Gourmand</p>
        ';

        return self::send(
            $order['customer_email'],
            'Votre avis sur votre commande Vite & Gourmand',
            self::getTemplate($content)
        );
    }

    /**
     * Email de reinitialisation de mot de passe
     * @param array $user
     * @param string $token
     * @return bool
     */
    public static function sendPasswordReset($user, $token) {
        self::init();
        $resetUrl = self::$config['site_url'] . '/user/reset-password/' . $token;

        $content = '
            <h2 style="color: #2E7D32; margin-top: 0;">Reinitialisation de mot de passe</h2>

            <p>Bonjour ' . htmlspecialchars($user['first_name']) . ',</p>

            <p>
                Vous avez demande la reinitialisation de votre mot de passe.
                Cliquez sur le bouton ci-dessous pour choisir un nouveau mot de passe :
            </p>

            <p style="text-align: center; margin: 30px 0;">
                <a href="' . htmlspecialchars($resetUrl) . '" style="display: inline-block; background-color: #2E7D32; color: #fff; padding: 12px 30px; text-decoration: none; border-radius: 5px; font-weight: bold;">
                    Reinitialiser mon mot de passe
                </a>
            </p>

            <p>
                <strong>Ce lien expire dans 1 heure.</strong>
            </p>

            <p>
                Si vous n\'avez pas demande cette reinitialisation, vous pouvez ignorer cet email.
                Votre mot de passe actuel restera inchange.
            </p>

            <p>L\'equipe Vite & Gourmand</p>
        ';

        return self::send(
            $user['email'],
            'Reinitialisation de votre mot de passe',
            self::getTemplate($content)
        );
    }

    /**
     * Email de changement de statut de commande
     * @param array $order
     * @param string $newStatus
     * @return bool
     */
    public static function sendOrderStatusUpdate($order, $newStatus) {
        $statusLabels = [
            'accepted' => 'acceptee',
            'preparing' => 'en preparation',
            'delivering' => 'en cours de livraison',
            'delivered' => 'livree',
            'cancelled' => 'annulee'
        ];

        $statusLabel = $statusLabels[$newStatus] ?? $newStatus;

        $content = '
            <h2 style="color: #2E7D32; margin-top: 0;">Mise a jour de votre commande</h2>

            <p>Bonjour ' . htmlspecialchars($order['customer_first_name']) . ',</p>

            <p>
                Votre commande <strong>' . htmlspecialchars($order['order_number']) . '</strong>
                est maintenant <strong style="color: #FF8F00;">' . $statusLabel . '</strong>.
            </p>

            <p style="text-align: center; margin: 30px 0;">
                <a href="' . self::$config['site_url'] . '/order/show/' . $order['id'] . '" style="display: inline-block; background-color: #2E7D32; color: #fff; padding: 12px 30px; text-decoration: none; border-radius: 5px; font-weight: bold;">
                    Voir ma commande
                </a>
            </p>

            <p>Merci pour votre confiance !</p>
            <p>L\'equipe Vite & Gourmand</p>
        ';

        return self::send(
            $order['customer_email'],
            'Mise a jour de votre commande ' . $order['order_number'],
            self::getTemplate($content)
        );
    }
}
