<?php
/**
 * Classe Auth - Gestion de l'authentification
 *
 * Cette classe gere les sessions utilisateur et les verifications de roles.
 * Toutes les methodes sont statiques pour un acces facile.
 */
class Auth {

    /**
     * Demarre la session si elle n'est pas deja active
     */
    public static function init() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    /**
     * Verifie si un utilisateur est connecte
     * @return bool
     */
    public static function check() {
        self::init();
        return isset($_SESSION['user_id']) && !empty($_SESSION['user_id']);
    }

    /**
     * Retourne l'ID de l'utilisateur connecte
     * @return int|null
     */
    public static function id() {
        self::init();
        return $_SESSION['user_id'] ?? null;
    }

    /**
     * Retourne les donnees de l'utilisateur connecte
     * @return array|null
     */
    public static function user() {
        self::init();
        if (!self::check()) {
            return null;
        }

        // Charger les donnees depuis la BDD si pas en cache
        if (!isset($_SESSION['user_data'])) {
            require_once __DIR__ . '/../Models/User.php';
            $_SESSION['user_data'] = User::findById(self::id());
        }

        return $_SESSION['user_data'];
    }

    /**
     * Retourne le role de l'utilisateur
     * @return string|null
     */
    public static function role() {
        $user = self::user();
        return $user['role'] ?? null;
    }

    /**
     * Verifie si l'utilisateur est admin
     * @return bool
     */
    public static function isAdmin() {
        return self::role() === 'admin';
    }

    /**
     * Verifie si l'utilisateur est employe (ou admin)
     * @return bool
     */
    public static function isEmployee() {
        return in_array(self::role(), ['employee', 'admin']);
    }

    /**
     * Verifie si l'utilisateur est client
     * @return bool
     */
    public static function isClient() {
        return self::role() === 'client';
    }

    /**
     * Connecte un utilisateur
     * @param array $user Donnees de l'utilisateur
     */
    public static function login($user) {
        self::init();
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_data'] = $user;

        // Regenerer l'ID de session pour la securite
        session_regenerate_id(true);
    }

    /**
     * Deconnecte l'utilisateur
     */
    public static function logout() {
        self::init();

        // Supprimer toutes les donnees de session
        $_SESSION = [];

        // Supprimer le cookie de session
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                $params["path"], $params["domain"],
                $params["secure"], $params["httponly"]
            );
        }

        // Detruire la session
        session_destroy();
    }

    /**
     * Rafraichit les donnees utilisateur en session
     */
    public static function refresh() {
        self::init();
        if (self::check()) {
            unset($_SESSION['user_data']);
            self::user(); // Recharge depuis la BDD
        }
    }

    /**
     * Redirige si l'utilisateur n'est pas connecte
     * @param string $redirect URL de redirection
     */
    public static function requireAuth($redirect = '/user/login') {
        if (!self::check()) {
            self::setFlash('error', 'Vous devez etre connecte pour acceder a cette page.');
            header("Location: $redirect");
            exit();
        }
    }

    /**
     * Redirige si l'utilisateur n'a pas le role requis
     * @param string|array $roles Role(s) autorise(s)
     * @param string $redirect URL de redirection
     */
    public static function requireRole($roles, $redirect = '/') {
        self::requireAuth();

        if (is_string($roles)) {
            $roles = [$roles];
        }

        if (!in_array(self::role(), $roles)) {
            self::setFlash('error', 'Vous n\'avez pas les droits pour acceder a cette page.');
            header("Location: $redirect");
            exit();
        }
    }

    /**
     * Redirige si l'utilisateur n'est pas employe ou admin
     */
    public static function requireEmployee() {
        self::requireRole(['employee', 'admin'], '/');
    }

    /**
     * Redirige si l'utilisateur n'est pas admin
     */
    public static function requireAdmin() {
        self::requireRole(['admin'], '/');
    }

    /**
     * Definit un message flash
     * @param string $type Type de message (success, error, warning, info)
     * @param string $message Le message
     */
    public static function setFlash($type, $message) {
        self::init();
        $_SESSION['flash'][$type] = $message;
    }

    /**
     * Recupere et supprime un message flash
     * @param string $type Type de message
     * @return string|null
     */
    public static function getFlash($type) {
        self::init();
        if (isset($_SESSION['flash'][$type])) {
            $message = $_SESSION['flash'][$type];
            unset($_SESSION['flash'][$type]);
            return $message;
        }
        return null;
    }

    /**
     * Recupere tous les messages flash
     * @return array
     */
    public static function getAllFlash() {
        self::init();
        $messages = $_SESSION['flash'] ?? [];
        $_SESSION['flash'] = [];
        return $messages;
    }

    /**
     * Verifie si un message flash existe
     * @param string $type Type de message
     * @return bool
     */
    public static function hasFlash($type) {
        self::init();
        return isset($_SESSION['flash'][$type]);
    }
}
