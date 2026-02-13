<?php
/**
 * Script de migration pour les avis
 * A supprimer apres utilisation
 */

require_once __DIR__ . '/../config/database.php';

// Cle secrete pour executer la migration
$secretKey = 'migrate2026';

if (!isset($_GET['key']) || $_GET['key'] !== $secretKey) {
    die('Acces refuse. Utilisez ?key=migrate2026');
}

try {
    $pdo = new PDO(DB_DSN, DB_USER, DB_PASSWORD, DB_OPTIONS);

    echo "<h2>Migration des avis</h2>";
    echo "<pre>";

    // Etape 1: Modifier order_id pour accepter NULL
    echo "1. Modification de order_id pour accepter NULL... ";
    $pdo->exec("ALTER TABLE reviews MODIFY COLUMN order_id INT NULL");
    echo "OK\n";

    // Etape 2: Supprimer l'ancienne contrainte unique
    echo "2. Suppression de l'ancienne contrainte... ";
    try {
        $pdo->exec("ALTER TABLE reviews DROP INDEX unique_review_per_order");
        echo "OK\n";
    } catch (PDOException $e) {
        echo "Index inexistant (ignore)\n";
    }

    // Etape 3: Ajouter la nouvelle contrainte unique
    echo "3. Ajout de la contrainte unique sur (user_id, menu_id)... ";
    try {
        $pdo->exec("ALTER TABLE reviews ADD UNIQUE KEY unique_review_per_menu (user_id, menu_id)");
        echo "OK\n";
    } catch (PDOException $e) {
        echo "Deja existante (ignore)\n";
    }

    echo "\n<strong style='color:green'>Migration terminee avec succes!</strong>\n";
    echo "</pre>";

    echo "<p><strong>IMPORTANT:</strong> Supprimez ce fichier apres utilisation pour des raisons de securite.</p>";
    echo "<p><a href='/'>Retour a l'accueil</a></p>";

} catch (PDOException $e) {
    echo "<pre style='color:red'>Erreur: " . htmlspecialchars($e->getMessage()) . "</pre>";
}
