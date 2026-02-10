<?php
class Model {
    protected static $pdo = null;

    protected static function getPdo() {
        if (self::$pdo === null) {
            try {
                self::$pdo = new PDO(DB_DSN, DB_USER, DB_PASS, DB_OPTIONS);
                self::$pdo->exec("SET NAMES utf8mb4 COLLATE utf8mb4_unicode_ci");
            } catch (PDOException $e) {
                error_log("Erreur de connexion : " . $e->getMessage());
                die("Erreur de connexion à la base de données.");
            }
        }
        return self::$pdo;
    }

    protected static function query($sql, $params = []) {
        $pdo = self::getPdo();
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    protected static function queryOne($sql, $params = []) {
        $pdo = self::getPdo();
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetch();
    }

    protected static function execute($sql, $params = []) {
        $pdo = self::getPdo();
        $stmt = $pdo->prepare($sql);
        return $stmt->execute($params);
    }

    protected static function lastInsertId() {
        return self::getPdo()->lastInsertId();
    }
}