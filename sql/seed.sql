SET NAMES utf8mb4;
USE vite_et_gourmand;

-- =====================================================
-- UTILISATEURS DE TEST
-- Mot de passe pour tous : "password" (pour les tests)
-- =====================================================
INSERT INTO users (first_name, last_name, email, phone, password_hash, role, address, city, postal_code, is_active, email_verified) VALUES
('Julie', 'Dupont', 'admin@viteetgourmand.fr', '0556123456', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin', '123 Rue de la Paix', 'Bordeaux', '33000', TRUE, TRUE),
('José', 'Martin', 'employe@viteetgourmand.fr', '0556789012', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'employee', '456 Avenue Victor Hugo', 'Bordeaux', '33000', TRUE, TRUE),
('Marie', 'Durand', 'client@test.fr', '0612345678', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'client', '789 Rue des Fleurs', 'Bordeaux', '33000', TRUE, TRUE),
('Pierre', 'Lambert', 'pierre.lambert@email.fr', '0623456789', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'client', '12 Place de la Bourse', 'Bordeaux', '33000', TRUE, TRUE);

-- =====================================================
-- ALLERGENES
-- =====================================================
INSERT INTO allergens (name, icon) VALUES
('Gluten', 'wheat'),
('Crustaces', 'shrimp'),
('Oeufs', 'egg'),
('Poisson', 'fish'),
('Arachides', 'peanut'),
('Soja', 'soy'),
('Lait', 'milk'),
('Fruits a coque', 'nut'),
('Celeri', 'celery'),
('Moutarde', 'mustard'),
('Sesame', 'sesame'),
('Sulfites', 'sulfite'),
('Lupin', 'lupin'),
('Mollusques', 'mollusk');

-- =====================================================
-- PLATS
-- =====================================================
INSERT INTO dishes (name, description, category, base_price) VALUES
-- Entrees
('Foie gras maison', 'Foie gras de canard mi-cuit, chutney de figues et pain depices', 'starter', 18.50),
('Saumon fume', 'Saumon fume artisanal, blinis et creme fraiche citronnee', 'starter', 14.00),
('Veloute de butternut', 'Veloute onctueux de butternut aux eclats de noisettes', 'starter', 8.50),
('Salade de chevre chaud', 'Mesclun, toasts de chevre, miel et noix', 'starter', 11.00),
('Terrine de legumes', 'Terrine provencale aux legumes du soleil', 'starter', 9.50),
-- Plats
('Dinde aux marrons', 'Roti de dinde fermiere, farce aux marrons et jus de cuisson', 'main', 22.00),
('Filet de boeuf Wellington', 'Filet de boeuf en croute, sauce Perigueux', 'main', 32.00),
('Saumon en croute', 'Pave de saumon en croute depinards, beurre blanc', 'main', 24.00),
('Risotto aux champignons', 'Risotto cremeux aux cepes et parmesan', 'main', 18.00),
('Agneau de 7 heures', 'Epaule dagneau confite, legumes racines', 'main', 28.00),
-- Desserts
('Buche de Noel', 'Buche traditionnelle chocolat-marrons', 'dessert', 12.00),
('Tarte Tatin', 'Tarte aux pommes caramelisees, creme fraiche', 'dessert', 9.00),
('Mousse au chocolat', 'Mousse au chocolat noir 70%, tuile croustillante', 'dessert', 8.50),
('Panna cotta', 'Panna cotta vanille, coulis de fruits rouges', 'dessert', 7.50),
('Assiette de fromages', 'Selection de fromages affines, confiture de cerises noires', 'dessert', 11.00);

-- =====================================================
-- MENUS
-- =====================================================
INSERT INTO menus (title, description, theme, dietary_type, min_people, base_price, price_per_extra_person, discount_threshold, stock_quantity, is_available, min_order_delay_days, storage_instructions, main_image_url) VALUES
('Menu Noel Premium', 'Un menu festif et raffine pour celebrer Noel en famille. Des produits nobles et une presentation soignee pour un moment inoubliable.', 'christmas', 'classic', 8, 320.00, 40.00, 5, 15, TRUE, 3, 'Conserver entre 0 et 4 degres C. Consommer dans les 48h.', 'https://images.unsplash.com/photo-1576402187878-974f70c890a5?w=800'),
('Menu Paques Tradition', 'Celebrez Paques avec un menu traditionnel aux saveurs printanieres. Agneau de saison et desserts festifs.', 'easter', 'classic', 6, 280.00, 35.00, 5, 20, TRUE, 3, 'Conserver entre 0 et 4 degres C. Consommer dans les 48h.', 'https://images.unsplash.com/photo-1490645935967-10de6ba17061?w=800'),
('Menu Printanier Vegetal', 'Un menu 100% vegetarien aux saveurs du printemps. Legumes de saison et creativite culinaire.', 'seasonal', 'vegetarian', 4, 200.00, 25.00, 5, 25, TRUE, 2, 'Conserver entre 0 et 4 degres C. Consommer dans les 24h.', 'https://images.unsplash.com/photo-1512621776951-a57141f2eefd?w=800'),
('Menu Prestige', 'Notre menu le plus raffine pour vos evenements exceptionnels. Produits dexception et service premium.', 'event', 'classic', 10, 450.00, 45.00, 5, 10, TRUE, 5, 'Conserver entre 0 et 4 degres C. Consommer dans les 24h.', 'https://images.unsplash.com/photo-1414235077428-338989a2e8c0?w=800'),
('Menu Ete Fraicheur', 'Un menu leger et frais pour les beaux jours. Saveurs mediterraneennes et produits de saison.', 'seasonal', 'classic', 6, 250.00, 30.00, 5, 18, TRUE, 2, 'Conserver entre 0 et 4 degres C. Consommer le jour meme.', 'https://images.unsplash.com/photo-1467003909585-2f8a72700288?w=800'),
('Menu Vegan Gourmand', 'Un menu 100% vegan sans compromis sur le gout. Creativite et saveurs vegetales.', 'classic', 'vegan', 4, 180.00, 22.00, 5, 30, TRUE, 2, 'Conserver entre 0 et 4 degres C. Consommer dans les 24h.', 'https://images.unsplash.com/photo-1546069901-ba9599a7e63c?w=800');

-- =====================================================
-- ASSOCIATION MENUS - PLATS
-- =====================================================
-- Menu Noel Premium (id=1)
INSERT INTO menu_dishes (menu_id, dish_id, category, display_order) VALUES
(1, 1, 'starter', 1),
(1, 6, 'main', 2),
(1, 11, 'dessert', 3);

-- Menu Paques Tradition (id=2)
INSERT INTO menu_dishes (menu_id, dish_id, category, display_order) VALUES
(2, 4, 'starter', 1),
(2, 10, 'main', 2),
(2, 12, 'dessert', 3);

-- Menu Printanier Vegetal (id=3)
INSERT INTO menu_dishes (menu_id, dish_id, category, display_order) VALUES
(3, 3, 'starter', 1),
(3, 9, 'main', 2),
(3, 14, 'dessert', 3);

-- Menu Prestige (id=4)
INSERT INTO menu_dishes (menu_id, dish_id, category, display_order) VALUES
(4, 1, 'starter', 1),
(4, 7, 'main', 2),
(4, 15, 'dessert', 3);

-- Menu Ete Fraicheur (id=5)
INSERT INTO menu_dishes (menu_id, dish_id, category, display_order) VALUES
(5, 2, 'starter', 1),
(5, 8, 'main', 2),
(5, 14, 'dessert', 3);

-- Menu Vegan Gourmand (id=6)
INSERT INTO menu_dishes (menu_id, dish_id, category, display_order) VALUES
(6, 5, 'starter', 1),
(6, 9, 'main', 2),
(6, 14, 'dessert', 3);

-- =====================================================
-- ALLERGENES DES PLATS
-- =====================================================
INSERT INTO dish_allergens (dish_id, allergen_id) VALUES
(1, 1),   -- Foie gras: Gluten
(2, 4),   -- Saumon: Poisson
(2, 1),   -- Saumon: Gluten
(3, 8),   -- Veloute: Fruits a coque
(4, 7),   -- Chevre: Lait
(4, 8),   -- Chevre: Fruits a coque
(6, 3),   -- Dinde: Oeufs
(6, 8),   -- Dinde: Fruits a coque
(7, 1),   -- Boeuf Wellington: Gluten
(7, 3),   -- Boeuf Wellington: Oeufs
(8, 4),   -- Saumon croute: Poisson
(8, 1),   -- Saumon croute: Gluten
(9, 7),   -- Risotto: Lait
(11, 7),  -- Buche: Lait
(11, 3),  -- Buche: Oeufs
(11, 1),  -- Buche: Gluten
(12, 1),  -- Tarte Tatin: Gluten
(12, 7),  -- Tarte Tatin: Lait
(13, 3),  -- Mousse chocolat: Oeufs
(13, 7),  -- Mousse chocolat: Lait
(14, 7),  -- Panna cotta: Lait
(15, 7);  -- Fromages: Lait

-- =====================================================
-- COMMANDES DE TEST
-- =====================================================
INSERT INTO orders (user_id, menu_id, quantity, total_price, delivery_address, delivery_city, delivery_postal_code, delivery_date, delivery_time, status, notes, created_at) VALUES
(3, 1, 8, 320.00, '789 Rue des Fleurs', 'Bordeaux', '33000', '2026-02-15', '12:00:00', 'delivered', 'Merci de livrer avant midi', '2026-01-15 10:30:00'),
(3, 2, 10, 350.00, '789 Rue des Fleurs', 'Bordeaux', '33000', '2026-04-01', '13:00:00', 'pending', 'Menu pour Paques', '2026-02-08 14:20:00'),
(4, 4, 12, 540.00, '12 Place de la Bourse', 'Bordeaux', '33000', '2026-03-20', '19:00:00', 'confirmed', 'Evenement professionnel', '2026-02-05 09:15:00');

-- =====================================================
-- AVIS DE TEST
-- =====================================================
INSERT INTO reviews (user_id, menu_id, order_id, rating, comment, status, created_at) VALUES
(3, 1, 1, 5, 'Excellent menu pour notre repas de Noel en famille. Tout etait parfait, de lentree au dessert. Le foie gras etait exceptionnel !', 'approved', '2026-01-20 15:30:00'),
(4, 4, 3, 4, 'Tres bon menu prestige, presentation soignee. Service de livraison impeccable. Je recommande !', 'approved', '2026-02-06 11:00:00');
