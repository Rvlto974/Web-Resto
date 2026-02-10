<?php
/**
 * Classe Csrf - Protection contre les attaques CSRF
 *
 * Genere et verifie des tokens pour proteger les formulaires.
 */
class Csrf {

    private static $tokenName = 'csrf_token';

    /**
     * Genere un nouveau token CSRF
     * @return string
     */
    public static function generateToken() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $token = bin2hex(random_bytes(32));
        $_SESSION[self::$tokenName] = $token;

        return $token;
    }

    /**
     * Recupere le token actuel ou en genere un nouveau
     * @return string
     */
    public static function getToken() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION[self::$tokenName])) {
            return self::generateToken();
        }

        return $_SESSION[self::$tokenName];
    }

    /**
     * Verifie si le token fourni est valide
     * @param string $token Token a verifier
     * @return bool
     */
    public static function verifyToken($token) {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION[self::$tokenName])) {
            return false;
        }

        // Comparaison securisee pour eviter les timing attacks
        return hash_equals($_SESSION[self::$tokenName], $token);
    }

    /**
     * Verifie le token et regenere un nouveau
     * @param string $token Token a verifier
     * @return bool
     */
    public static function verifyAndRefresh($token) {
        $valid = self::verifyToken($token);

        // Regenerer le token apres verification
        self::generateToken();

        return $valid;
    }

    /**
     * Retourne le champ input HTML pour le formulaire
     * @return string
     */
    public static function getInputField() {
        $token = self::getToken();
        return '<input type="hidden" name="' . self::$tokenName . '" value="' . htmlspecialchars($token) . '">';
    }

    /**
     * Verifie le token depuis les donnees POST
     * @return bool
     */
    public static function validateRequest() {
        $token = $_POST[self::$tokenName] ?? '';
        return self::verifyToken($token);
    }

    /**
     * Leve une exception si le token est invalide
     * @throws Exception
     */
    public static function requireValidToken() {
        if (!self::validateRequest()) {
            http_response_code(403);
            throw new Exception('Token CSRF invalide. Veuillez rafraichir la page et reessayer.');
        }
    }
}
