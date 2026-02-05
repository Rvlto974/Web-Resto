USE vite_et_gourmand;

INSERT INTO users (first_name, last_name, email, phone, password_hash, role, address, city, postal_code, is_active, email_verified) VALUES
('Julie', 'Dupont', 'admin@viteetgourmand.fr', '0556123456', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin', '123 Rue de la Paix', 'Bordeaux', '33000', TRUE, TRUE),
('Pierre', 'Durand', 'client@email.fr', '0612345678', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'client', '789 Rue des Fleurs', 'Bordeaux', '33000', TRUE, TRUE);

INSERT INTO allergens (name, icon) VALUES
('Gluten', 'wheat'),
('Oeufs', 'egg'),
('Poisson', 'fish'),
('Lait', 'milk');

INSERT INTO dishes (name, description, category, base_price) VALUES
('Foie gras', 'Foie gras de canard', 'starter', 18.50),
('Dinde aux marrons', 'Roti de dinde', 'main', 22.00),
('Buche de Noel', 'Buche chocolat', 'dessert', 12.00);

INSERT INTO menus (title, description, theme, dietary_type, min_people, base_price, price_per_extra_person, stock_quantity, is_available) VALUES
('Menu Noel Premium', 'Menu festif', 'christmas', 'classic', 4, 180.00, 45.00, 15, TRUE);

INSERT INTO menu_dishes (menu_id, dish_id, category, display_order) VALUES
(1, 1, 'starter', 1),
(1, 2, 'main', 2),
(1, 3, 'dessert', 3);