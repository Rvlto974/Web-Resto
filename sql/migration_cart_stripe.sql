-- Migration: Panier multi-menus et paiement Stripe
-- Date: 2026-02-11
-- Description: Ajoute le support pour commandes multi-menus et paiement en ligne

USE vite_et_gourmand;

-- ============================================
-- 1. Table order_items (articles de commande)
-- ============================================
CREATE TABLE IF NOT EXISTS order_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT NOT NULL,
    menu_id INT NOT NULL,
    quantity INT NOT NULL DEFAULT 1,
    number_of_people INT NOT NULL,
    unit_price DECIMAL(10,2) NOT NULL,
    subtotal DECIMAL(10,2) NOT NULL,
    menu_title VARCHAR(200) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE,
    FOREIGN KEY (menu_id) REFERENCES menus(id) ON DELETE RESTRICT,
    INDEX idx_order (order_id),
    INDEX idx_menu (menu_id)
) ENGINE=InnoDB;

-- ============================================
-- 2. Colonnes paiement dans orders
-- ============================================

-- Methode de paiement (cash = livraison, stripe = carte)
ALTER TABLE orders
ADD COLUMN payment_method ENUM('cash', 'stripe') NOT NULL DEFAULT 'cash' AFTER total_price;

-- Statut du paiement
ALTER TABLE orders
ADD COLUMN payment_status ENUM('pending', 'paid', 'failed', 'refunded') NOT NULL DEFAULT 'pending' AFTER payment_method;

-- ID de session Stripe Checkout
ALTER TABLE orders
ADD COLUMN stripe_session_id VARCHAR(255) NULL AFTER payment_status;

-- ID du Payment Intent Stripe
ALTER TABLE orders
ADD COLUMN stripe_payment_intent_id VARCHAR(255) NULL AFTER stripe_session_id;

-- Date/heure du paiement
ALTER TABLE orders
ADD COLUMN paid_at DATETIME NULL AFTER stripe_payment_intent_id;

-- Index pour recherche par session Stripe
ALTER TABLE orders
ADD INDEX idx_stripe_session (stripe_session_id);

-- ============================================
-- 3. Rendre menu_id nullable (pour multi-menus)
-- ============================================

-- D'abord supprimer la contrainte de cle etrangere
ALTER TABLE orders DROP FOREIGN KEY orders_ibfk_2;

-- Modifier la colonne pour accepter NULL
ALTER TABLE orders MODIFY COLUMN menu_id INT NULL;

-- Recreer la contrainte avec ON DELETE SET NULL
ALTER TABLE orders
ADD CONSTRAINT orders_menu_fk
FOREIGN KEY (menu_id) REFERENCES menus(id) ON DELETE SET NULL;

-- ============================================
-- 4. Mettre a jour les commandes existantes
-- ============================================

-- Pour les commandes existantes, definir payment_method et payment_status
UPDATE orders SET payment_method = 'cash', payment_status = 'pending' WHERE payment_method IS NULL OR payment_method = '';

-- Pour les commandes deja livrees/completees, marquer comme payees
UPDATE orders SET payment_status = 'paid' WHERE status IN ('delivered', 'waiting_return', 'completed');
