<?php
require_once __DIR__ . '/../Views/core/Model.php';

/**
 * Modele User - Gestion des utilisateurs
 */
class User extends Model {

    protected static $table = 'users';

    /**
     * Trouve un utilisateur par son ID
     * @param int $id
     * @return array|null
     */
    public static function findById($id) {
        return self::queryOne(
            "SELECT * FROM users WHERE id = ?",
            [$id]
        );
    }

    /**
     * Trouve un utilisateur par son email
     * @param string $email
     * @return array|null
     */
    public static function findByEmail($email) {
        return self::queryOne(
            "SELECT * FROM users WHERE email = ?",
            [$email]
        );
    }

    /**
     * Trouve tous les utilisateurs
     * @param array $filters Filtres (role, is_active)
     * @return array
     */
    public static function findAll($filters = []) {
        $sql = "SELECT * FROM users WHERE 1=1";
        $params = [];

        // Filtre par role (string ou array)
        if (!empty($filters['role'])) {
            if (is_array($filters['role'])) {
                $placeholders = str_repeat('?,', count($filters['role']) - 1) . '?';
                $sql .= " AND role IN ($placeholders)";
                $params = array_merge($params, $filters['role']);
            } else {
                $sql .= " AND role = ?";
                $params[] = $filters['role'];
            }
        }

        // Filtre par statut actif
        if (isset($filters['is_active'])) {
            $sql .= " AND is_active = ?";
            $params[] = $filters['is_active'] ? 1 : 0;
        }

        $sql .= " ORDER BY created_at DESC";

        return self::query($sql, $params);
    }

    /**
     * Trouve tous les employes
     * @return array
     */
    public static function findEmployees() {
        return self::query(
            "SELECT * FROM users WHERE role = 'employee' ORDER BY last_name, first_name"
        );
    }

    /**
     * Cree un nouvel utilisateur
     * @param array $data
     * @return int ID du nouvel utilisateur
     */
    public static function create($data) {
        $sql = "INSERT INTO users (
            first_name, last_name, email, phone, password_hash, role,
            address, city, postal_code, email_verification_token, created_at
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())";

        self::execute($sql, [
            $data['first_name'],
            $data['last_name'],
            $data['email'],
            $data['phone'],
            self::hashPassword($data['password']),
            $data['role'] ?? 'client',
            $data['address'] ?? null,
            $data['city'] ?? null,
            $data['postal_code'] ?? null,
            $data['verification_token'] ?? null
        ]);

        return self::lastInsertId();
    }

    /**
     * Met a jour un utilisateur
     * @param int $id
     * @param array $data
     * @return bool
     */
    public static function update($id, $data) {
        $fields = [];
        $values = [];

        // Champs autorises
        $allowedFields = [
            'first_name', 'last_name', 'phone', 'address',
            'city', 'postal_code', 'is_active', 'email_verified'
        ];

        foreach ($allowedFields as $field) {
            if (isset($data[$field])) {
                $fields[] = "$field = ?";
                $values[] = $data[$field];
            }
        }

        if (empty($fields)) {
            return false;
        }

        $fields[] = "updated_at = NOW()";
        $values[] = $id;

        $sql = "UPDATE users SET " . implode(', ', $fields) . " WHERE id = ?";
        return self::execute($sql, $values);
    }

    /**
     * Met a jour le mot de passe
     * @param int $id
     * @param string $password Nouveau mot de passe en clair
     * @return bool
     */
    public static function updatePassword($id, $password) {
        return self::execute(
            "UPDATE users SET password_hash = ?, updated_at = NOW() WHERE id = ?",
            [self::hashPassword($password), $id]
        );
    }

    /**
     * Met a jour la date de derniere connexion
     * @param int $id
     * @return bool
     */
    public static function updateLastLogin($id) {
        return self::execute(
            "UPDATE users SET last_login = NOW() WHERE id = ?",
            [$id]
        );
    }

    /**
     * Active ou desactive un compte
     * @param int $id
     * @param bool $active
     * @return bool
     */
    public static function setActive($id, $active) {
        return self::execute(
            "UPDATE users SET is_active = ?, updated_at = NOW() WHERE id = ?",
            [$active ? 1 : 0, $id]
        );
    }

    /**
     * Hash un mot de passe
     * @param string $password
     * @return string
     */
    public static function hashPassword($password) {
        return password_hash($password, PASSWORD_DEFAULT);
    }

    /**
     * Verifie un mot de passe
     * @param string $password Mot de passe en clair
     * @param string $hash Hash stocke
     * @return bool
     */
    public static function verifyPassword($password, $hash) {
        return password_verify($password, $hash);
    }

    /**
     * Valide la complexite du mot de passe
     * Regles : 10 caracteres min, 1 majuscule, 1 minuscule, 1 chiffre, 1 special
     *
     * @param string $password
     * @return array ['valid' => bool, 'errors' => array]
     */
    public static function validatePassword($password) {
        $errors = [];

        if (strlen($password) < 10) {
            $errors[] = 'Le mot de passe doit contenir au moins 10 caracteres.';
        }

        if (!preg_match('/[A-Z]/', $password)) {
            $errors[] = 'Le mot de passe doit contenir au moins une majuscule.';
        }

        if (!preg_match('/[a-z]/', $password)) {
            $errors[] = 'Le mot de passe doit contenir au moins une minuscule.';
        }

        if (!preg_match('/[0-9]/', $password)) {
            $errors[] = 'Le mot de passe doit contenir au moins un chiffre.';
        }

        if (!preg_match('/[!@#$%^&*()_+\-=\[\]{};\':"\\|,.<>\/?]/', $password)) {
            $errors[] = 'Le mot de passe doit contenir au moins un caractere special (!@#$%^&*...).';
        }

        return [
            'valid' => empty($errors),
            'errors' => $errors
        ];
    }

    /**
     * Genere un token de verification email
     * @return string
     */
    public static function generateVerificationToken() {
        return bin2hex(random_bytes(32));
    }

    /**
     * Verifie l'email d'un utilisateur
     * @param string $token
     * @return bool
     */
    public static function verifyEmail($token) {
        $user = self::queryOne(
            "SELECT id FROM users WHERE email_verification_token = ? AND email_verified = 0",
            [$token]
        );

        if (!$user) {
            return false;
        }

        return self::execute(
            "UPDATE users SET email_verified = 1, email_verification_token = NULL, updated_at = NOW() WHERE id = ?",
            [$user['id']]
        );
    }

    /**
     * Cree un token de reinitialisation de mot de passe
     * @param int $userId
     * @return string Token
     */
    public static function createPasswordResetToken($userId) {
        $token = bin2hex(random_bytes(32));
        $expiration = date('Y-m-d H:i:s', strtotime('+1 hour'));

        // Supprimer les anciens tokens
        self::execute(
            "DELETE FROM password_resets WHERE user_id = ?",
            [$userId]
        );

        // Creer le nouveau token
        self::execute(
            "INSERT INTO password_resets (user_id, token, expires_at, created_at) VALUES (?, ?, ?, NOW())",
            [$userId, $token, $expiration]
        );

        return $token;
    }

    /**
     * Verifie un token de reinitialisation
     * @param string $token
     * @return array|null Donnees du reset si valide
     */
    public static function verifyPasswordResetToken($token) {
        return self::queryOne(
            "SELECT * FROM password_resets WHERE token = ? AND expires_at > NOW() AND used = 0",
            [$token]
        );
    }

    /**
     * Marque un token de reset comme utilise
     * @param string $token
     * @return bool
     */
    public static function markResetTokenUsed($token) {
        return self::execute(
            "UPDATE password_resets SET used = 1 WHERE token = ?",
            [$token]
        );
    }

    /**
     * Retourne le nombre total d'utilisateurs par role
     * @param string|null $role Si specifie, compte uniquement ce role
     * @return int|array
     */
    public static function countByRole($role = null) {
        if ($role) {
            $result = self::queryOne(
                "SELECT COUNT(*) as count FROM users WHERE role = ?",
                [$role]
            );
            return (int)$result['count'];
        }

        $results = self::query(
            "SELECT role, COUNT(*) as count FROM users GROUP BY role"
        );

        $counts = [];
        foreach ($results as $row) {
            $counts[$row['role']] = (int)$row['count'];
        }
        return $counts;
    }

    /**
     * Anonymise les donnees d'un utilisateur (RGPD - Droit a l'effacement)
     * Les commandes sont conservees pour la comptabilite mais anonymisees
     * @param int $userId
     * @return bool
     */
    public static function anonymize($userId) {
        $anonymousEmail = 'deleted_' . $userId . '_' . time() . '@anonymous.local';

        // Anonymiser l'utilisateur
        $result = self::execute(
            "UPDATE users SET
                email = ?,
                first_name = 'Utilisateur',
                last_name = 'Supprime',
                phone = '0000000000',
                address = NULL,
                city = NULL,
                postal_code = NULL,
                password_hash = '',
                is_active = 0,
                email_verified = 0,
                email_verification_token = NULL,
                updated_at = NOW()
            WHERE id = ?",
            [$anonymousEmail, $userId]
        );

        if (!$result) {
            return false;
        }

        // Anonymiser les avis (supprimer les commentaires, garder les notes pour stats)
        self::execute(
            "UPDATE reviews SET comment = '[Avis supprime]' WHERE user_id = ?",
            [$userId]
        );

        // Supprimer les tokens de reinitialisation
        self::execute(
            "DELETE FROM password_resets WHERE user_id = ?",
            [$userId]
        );

        return true;
    }
}
