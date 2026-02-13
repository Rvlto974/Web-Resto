-- Migration: Permettre les avis sans commande associee
-- Date: 2026-02-13

-- Modifier order_id pour accepter NULL
ALTER TABLE reviews MODIFY COLUMN order_id INT NULL;

-- Supprimer l'ancienne contrainte unique (user_id, order_id)
ALTER TABLE reviews DROP INDEX unique_review_per_order;

-- Ajouter une nouvelle contrainte unique sur (user_id, menu_id)
ALTER TABLE reviews ADD UNIQUE KEY unique_review_per_menu (user_id, menu_id);
