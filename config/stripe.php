<?php
/**
 * Configuration Stripe
 *
 * Pour obtenir vos cles API :
 * 1. Connectez-vous a https://dashboard.stripe.com
 * 2. Allez dans Developers > API keys
 * 3. Copiez les cles (test pour developpement, live pour production)
 *
 * Variables d'environnement requises :
 * - STRIPE_PUBLIC_KEY : Cle publique (pk_test_... ou pk_live_...)
 * - STRIPE_SECRET_KEY : Cle secrete (sk_test_... ou sk_live_...)
 * - STRIPE_WEBHOOK_SECRET : Secret du webhook (whsec_...)
 */

// Cle publique (utilisee cote client)
$stripePublicKey = getenv('STRIPE_PUBLIC_KEY');
if (!$stripePublicKey) {
    $stripePublicKey = 'pk_test_VOTRE_CLE_PUBLIQUE_ICI';
}
define('STRIPE_PUBLIC_KEY', $stripePublicKey);

// Cle secrete (utilisee cote serveur)
$stripeSecretKey = getenv('STRIPE_SECRET_KEY');
if (!$stripeSecretKey) {
    $stripeSecretKey = 'sk_test_VOTRE_CLE_SECRETE_ICI';
}
define('STRIPE_SECRET_KEY', $stripeSecretKey);

// Secret du webhook (pour verifier les signatures)
$stripeWebhookSecret = getenv('STRIPE_WEBHOOK_SECRET');
if (!$stripeWebhookSecret) {
    $stripeWebhookSecret = 'whsec_VOTRE_SECRET_WEBHOOK_ICI';
}
define('STRIPE_WEBHOOK_SECRET', $stripeWebhookSecret);

// Devise
define('STRIPE_CURRENCY', 'eur');

// Locale
define('STRIPE_LOCALE', 'fr');

// URL de base du site (pour les redirections)
$appUrl = getenv('APP_URL');
if (!$appUrl) {
    $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
    $host = $_SERVER['HTTP_HOST'] ?? 'localhost';
    $appUrl = $protocol . '://' . $host;
}
define('APP_URL', $appUrl);
