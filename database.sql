-- =====================================================
-- KORE E-COMMERCE - SCHÉMA & DONNÉES DE DÉPART (V2 - ENRICHI)
-- Style: Minimaliste, Premium, "Apple-like"
-- Langue: Français
-- =====================================================

DROP DATABASE IF EXISTS kore_shop;
CREATE DATABASE kore_shop CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE kore_shop;

-- =====================================================
-- 1. TABLE: users (Utilisateurs)
-- =====================================================
CREATE TABLE users (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    firstname VARCHAR(50) NOT NULL,
    lastname VARCHAR(50) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_email (email)
) ENGINE=InnoDB;

-- =====================================================
-- 2. TABLE: products (Produits)
-- =====================================================
CREATE TABLE products (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    slug VARCHAR(255) NOT NULL UNIQUE,
    description TEXT,
    price DECIMAL(10,2) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_slug (slug)
) ENGINE=InnoDB;

-- =====================================================
-- 3. TABLE: variants (Gestion des stocks)
-- =====================================================
CREATE TABLE variants (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    product_id INT UNSIGNED NOT NULL,
    size VARCHAR(10) NOT NULL COMMENT 'XS, S, M, L, XL, 40, 41, 42...',
    color VARCHAR(50) NOT NULL COMMENT 'Noir, Blanc, Bleu Marine, etc.',
    color_hex VARCHAR(7) DEFAULT NULL COMMENT "Code Hex pour l'affichage UI",
    stock INT UNSIGNED DEFAULT 0,
    sku VARCHAR(100) UNIQUE NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE,
    INDEX idx_product (product_id),
    INDEX idx_sku (sku)
) ENGINE=InnoDB;

-- =====================================================
-- 4. TABLE: images (Galerie produits)
-- =====================================================
CREATE TABLE images (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    product_id INT UNSIGNED NOT NULL,
    image_url VARCHAR(512) NOT NULL,
    display_order TINYINT UNSIGNED DEFAULT 0,
    is_primary BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE,
    INDEX idx_product (product_id)
) ENGINE=InnoDB;

-- =====================================================
-- 5. TABLE: reviews (Avis clients)
-- =====================================================
CREATE TABLE reviews (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    product_id INT UNSIGNED NOT NULL,
    user_id INT UNSIGNED NOT NULL,
    rating TINYINT UNSIGNED NOT NULL CHECK (rating BETWEEN 1 AND 5),
    comment TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- =====================================================
-- 6. TABLE: orders & items (Commandes)
-- =====================================================
CREATE TABLE orders (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id INT UNSIGNED NOT NULL,
    total DECIMAL(10,2) NOT NULL,
    status ENUM('pending', 'processing', 'shipped', 'delivered', 'cancelled') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE order_items (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    order_id INT UNSIGNED NOT NULL,
    product_id INT UNSIGNED NOT NULL,
    variant_id INT UNSIGNED NOT NULL,
    quantity TINYINT UNSIGNED NOT NULL DEFAULT 1,
    price DECIMAL(10,2) NOT NULL,
    FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE,
    FOREIGN KEY (variant_id) REFERENCES variants(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- =====================================================
-- 7. TABLE: favorites (Favoris/Wishlist)
-- =====================================================
CREATE TABLE favorites (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id INT UNSIGNED NOT NULL,
    product_id INT UNSIGNED NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE,
    UNIQUE KEY unique_favorite (user_id, product_id)
) ENGINE=InnoDB;


-- =====================================================
-- DONNÉES DE DÉMONSTRATION (SEED DATA)
-- =====================================================

-- 1. Utilisateurs (Mot de passe : 'password' hashé)
INSERT INTO users (firstname, lastname, email, password) VALUES
('Salim', 'Bouskine', 'salim@gmail.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi'), -- ID 1
('Barta', 'Boucherrougui', 'barta@gmail.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi'), -- ID 2
('Motawassim', 'Lahmadi', 'mota@gmail.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi'), -- ID 3
('Aimen', 'Bouaoudja', 'aimen@gmail.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi'), -- ID 4
('Mohamed', 'Sadok', 'moha@gmail.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi'), -- ID 5
('Rayan', 'Demoulin', 'demoulin@gmail.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi'),
('Rayan', 'Bourgou', 'bourgou@gmail.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi'),
('Wesley', 'Lubin', 'wesley@gmail.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi'),
('Vaks', 'Sritharan', 'vaks@gmail.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi'),
('Abdel', 'Bouskine', 'abdel@gmail.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi');

-- 2. Produits (50 références)
INSERT INTO products (id, name, slug, description, price) VALUES
-- HAUTS BASIQUES (1-10)
(1, 'T-Shirt Coton Épais', 't-shirt-coton-epais', "Coupé dans un jersey de coton substantiel de 240g/m², ce t-shirt offre un tombé structuré et un toucher sec. Une base de garde-robe qui s'améliore à chaque lavage.", 45.00),
(2, 'T-Shirt Lin Premium', 't-shirt-lin-premium', "Léger et respirant, notre t-shirt en lin est idéal pour les journées chaudes. Texture naturelle et coupe décontractée.", 55.00),
(3, 'Polo Piqué Classique', 'polo-pique-classique', "Le polo intemporel en coton piqué. Col structuré qui ne gondole pas, coupe ajustée sans être serrée.", 65.00),
(4, 'Henley Manches Longues', 'henley-manches-longues', "Alternative décontractée à la chemise, ce henley en coton gaufré apporte texture et confort à vos tenues.", 60.00),
(5, 'Débardeur Côtelé', 'debardeur-cotele', "Coton côtelé extensible. Parfait sous une chemise ou seul en été. Coupe près du corps.", 35.00),
(6, 'T-Shirt Oversize', 't-shirt-oversize', "Coupe ample et épaules tombantes pour un look streetwear maîtrisé. Coton lourd de 280g/m².", 50.00),
(7, 'Marinière Bretonne', 'mariniere-bretonne', "L'authenticité française. Coton épais, rayures tissées (et non imprimées), coupe droite traditionnelle.", 70.00),
(8, 'Sweatshirt Col Rond', 'sweatshirt-col-rond', "Molleton bouclette 400g/m². Prélavé pour éviter le rétrécissement. Un classique du vestiaire sport chic.", 85.00),
(9, 'Hoodie Lourd', 'hoodie-lourd', "Capuche doublée généreuse, poche kangourou renforcée. Le sweat à capuche ultime pour le confort.", 95.00),
(10, 'Chemise Flanelle', 'chemise-flanelle', "Coton gratté ultra-doux. Motif écossais subtil. La chaleur réconfortante pour l'automne.", 90.00),

-- PULLS & MAILLES (11-20)
(11, 'Col Roulé Mérinos', 'col-roule-merinos', "Laine mérinos extra-fine. Thermorégulant et anti-odeur. L'élégance sous une veste.", 130.00),
(12, 'Pull Maille Torsadée', 'pull-maille-torsadee', "Inspiration irlandaise. Laine vierge épaisse pour une texture riche et une chaleur incomparable.", 145.00),
(13, 'Cardigan Col Châle', 'cardigan-col-chale', "Le confort d'un peignoir, l'élégance d'un blazer. Maille épaisse et boutons en cuir.", 160.00),
(14, 'Pull Col V Cachemire', 'pull-col-v-cachemire', "100% Cachemire grade A. Une douceur exceptionnelle directement sur la peau.", 180.00),
(15, 'Pull Marin Boutonné', 'pull-marin-boutonne', "Laine sèche traditionnelle, boutons épaule aspect corne. Robuste et authentique.", 140.00),
(16, 'Gilet Zippé Laine', 'gilet-zippe-laine', "Pratique et chaud. Zip double curseur YKK. Col montant pour protéger du vent.", 120.00),
(17, 'Pull Raglan Chiné', 'pull-raglan-chine', "Manches raglan pour une liberté de mouvement totale. Fil chiné apportant de la profondeur à la couleur.", 110.00),
(18, 'Col Roulé Chunky', 'col-roule-chunky', "Grosse maille jauge 3. Le pull d'hiver par excellence pour affronter le grand froid.", 155.00),
(19, 'Pull Sans Manches', 'pull-sans-manches', "La pièce layering tendance. Laine et alpaga mélangés. Idéal sur une chemise oversized.", 95.00),
(20, 'Polo Maille Fine', 'polo-maille-fine', "Maille de coton et soie. Col polo sans boutons pour un style rétro-moderne.", 115.00),

-- PANTALONS (21-30)
(21, 'Chino Technique', 'chino-technique', "Tissu déperlant et extensible. Coupe fuselée moderne. Poche zippée invisible.", 95.00),
(22, 'Jean Brut Selvedge', 'jean-brut-selvedge', "Toile japonaise 14oz. Liseré rouge. Se patine et s'embellit avec le temps.", 160.00),
(23, 'Pantalon Laine Flanelle', 'pantalon-laine-flanelle', "Coupe droite élégante. Laine vitale Barberis Canonico. Le pantalon d'hiver chic.", 180.00),
(24, 'Cargo Pant Ajusté', 'cargo-pant-ajuste', "Poches cargo plaquées discrètes. Coton ripstop résistant aux déchirures.", 110.00),
(25, 'Jean Droit Délavé', 'jean-droit-delave', "Délavage naturel à la pierre ponce. Coton bio. La coupe 501 revisitée.", 130.00),
(26, 'Pantalon à Pinces', 'pantalon-pinces', "Volume confortable aux cuisses, resserré en bas. Tissu fluide tencel/coton.", 125.00),
(27, 'Jogging Premium', 'jogging-premium', "Molleton lourd, coupe soignée. Assez classe pour sortir acheter du pain, assez confort pour Netflix.", 80.00),
(28, 'Short Chino', 'short-chino', "Coton twill léger. Longueur mi-cuisse idéale. Revers cousus.", 65.00),
(29, 'Short Bain Technique', 'short-bain-technique', "Séchage express. Slip filet doux. Imprimé discret ton sur ton.", 70.00),
(30, 'Pantalon Velours Côtelé', 'pantalon-velours', "Velours 500 raies. Texture riche et vintage. Coupe carotte.", 135.00),

-- VESTES & MANTEAUX (31-40)
(31, "Trench Coat l'Architecte", 'trench-coat-architecte', 'Nylon japonais déperlant, patte cachée. Le manteau de pluie urbain ultime.', 350.00),
(32, 'Veste de Travail', 'veste-de-travail', 'Moleskine de coton inusable. 3 poches plaquées. Le bleu de travail sublimé.', 210.00),
(33, 'Blouson Harrington', 'blouson-harrington', 'Doublure tartan, col cheminée boutonné. La légende britannique revisitée.', 190.00),
(34, 'Parka Grand Froid', 'parka-grand-froid', "Duvet et plumes éthiques. Tissu technique imperméable. Résiste jusqu'à -20°C.", 450.00),
(35, 'Veste Denim', 'veste-denim', "Type III classique. Denim italien. Boutons métalliques gravés.", 150.00),
(36, 'Manteau Laine Droit', 'manteau-laine-droit', "Mélange laine et cachemire. Coupe 3/4 structurée. Indispensable sur un costume.", 390.00),
(37, 'Bomber MA-1', 'bomber-ma1', "Nylon satiné, doublure orange de secours. Coupe courte et bouffante fidèle à l'original.", 180.00),
(38, 'Surchemise Militaire', 'surchemise-militaire', "Coton épais chevrons. Deux grandes poches poitrine. Se porte sur un t-shirt ou un pull.", 120.00),
(39, 'Blazer Non Doublé', 'blazer-non-double', "Laine hopsack aérée. Construction souple sans épaulettes. Le chic décontracté.", 280.00),
(40, 'Veste en Cuir', 'veste-cuir', "Cuir d'agneau pleine fleur. Coupe motard épurée. Zips argentés.", 490.00),

-- CHAUSSURES & ACCESSOIRES (41-50)
(41, 'Baskets Runner KORE', 'baskets-runner-kore', "Cuir italien et mesh technique. Semelle Vibram. Confort tout terrain.", 180.00),
(42, 'Boots Chelsea', 'boots-chelsea', "Cuir suédé, montage Goodyear. Élastiques latéraux robustes.", 250.00),
(43, 'Mocassins Penny', 'mocassins-penny', "Cuir lisse bordeaux. Semelle cuir cousue Blake. L'élégance preppy.", 220.00),
(44, 'Sneakers Minimalistes', 'sneakers-minimalistes', "Tout cuir blanc. Aucune marque apparente. La basket qui va avec tout.", 160.00),
(45, 'Bonnet Cachemire', 'bonnet-cachemire', "100% Cachemire. Maille serrée. Ne gratte pas.", 55.00),
(46, 'Echarpe Laine Vierge', 'echarpe-laine', "Dimensions généreuses (180x30cm). Franges finies main.", 70.00),
(47, 'Ceinture Cuir', 'ceinture-cuir', "Cuir tannage végétal qui se patine. Boucle laiton massif.", 60.00),
(48, 'Sac Week-end', 'sac-weekend-cuir', "Cuir pleine fleur. Format cabine. Bandoulière amovible.", 290.00),
(49, 'Porte-Cartes', 'porte-cartes', "Minimaliste. 4 fentes + poche centrale. Cuir grainé.", 45.00),
(50, 'Casquette Baseball', 'casquette-baseball', "Coton sergé lavé. Non structurée pour un look vintage. Fermeture réglable cuir.", 35.00);

-- 3. Images (URLs réelles unsplash/sites mode)
INSERT INTO images (product_id, image_url, is_primary, display_order) VALUES
-- T-Shirt
(1, 'https://images.unsplash.com/photo-1521572163474-6864f9cf17ab?auto=format&fit=crop&w=800&q=80', 1, 1),
(1, 'https://images.unsplash.com/photo-1583743814966-8936f5b7be1a?auto=format&fit=crop&w=800&q=80', 0, 2),
-- Lin
(2, 'https://images.unsplash.com/photo-1576566588028-4147f3842f27?auto=format&fit=crop&w=800&q=80', 1, 1),
-- Polo
(3, 'https://images.unsplash.com/photo-1626557981101-aae6f84aa6ff?auto=format&fit=crop&w=800&q=80', 1, 1),
-- Henley
(4, 'https://images.unsplash.com/photo-1618354691373-d851c5c3a990?auto=format&fit=crop&w=800&q=80', 1, 1),
-- Debardeur
(5, 'https://images.unsplash.com/photo-1503342394128-c104d54dba01?auto=format&fit=crop&w=800&q=80', 1, 1),
-- Oversize
(6, 'https://images.unsplash.com/photo-1554568218-0f1715e72254?auto=format&fit=crop&w=800&q=80', 1, 1),
-- Mariniere
(7, 'https://images.unsplash.com/photo-1620799140408-ed5341cd2431?auto=format&fit=crop&w=800&q=80', 1, 1),
-- Sweat
(8, 'https://images.unsplash.com/photo-1620799140187-575030e461b2?auto=format&fit=crop&w=800&q=80', 1, 1),
-- Hoodie
(9, 'https://images.unsplash.com/photo-1556821840-3a63f95609a7?auto=format&fit=crop&w=800&q=80', 1, 1),
-- Flanelle
(10, 'https://images.unsplash.com/photo-1596755094514-f87e34085b2c?auto=format&fit=crop&w=800&q=80', 1, 1),
-- Col Roulé Merinos
(11, 'https://images.unsplash.com/photo-1620799139507-2a76f79a2f4d?auto=format&fit=crop&w=800&q=80', 1, 1),
-- Torsadé
(12, 'https://images.unsplash.com/photo-1631541909061-71e349d1f203?auto=format&fit=crop&w=800&q=80', 1, 1),
-- Cardigan
(13, 'https://images.unsplash.com/photo-1434389677669-e08b4cac3105?auto=format&fit=crop&w=800&q=80', 1, 1),
-- Pull V
(14, 'https://images.unsplash.com/photo-1516762689617-e1cffcef479d?auto=format&fit=crop&w=800&q=80', 1, 1),
-- Pull Marin
(15, 'https://images.unsplash.com/photo-1481325545291-94394fe1e9d4?auto=format&fit=crop&w=800&q=80', 1, 1),
-- Gilet Zippé
(16, 'https://images.unsplash.com/photo-1611312449408-fcece27cdbb7?auto=format&fit=crop&w=800&q=80', 1, 1),
-- Raglan
(17, 'https://images.unsplash.com/photo-1608248597279-f99d160bfbc8?auto=format&fit=crop&w=800&q=80', 1, 1),
-- Chunky
(18, 'https://images.unsplash.com/photo-1605763240004-7e93b172d754?auto=format&fit=crop&w=800&q=80', 1, 1),
-- Sans Manches
(19, 'https://images.unsplash.com/photo-1624225205261-0b5c192f0227?auto=format&fit=crop&w=800&q=80', 1, 1),
-- Polo Maille
(20, 'https://images.unsplash.com/photo-1624225205261-0b5c192f0227?auto=format&fit=crop&w=800&q=80', 1, 1), -- Placeholder similaire
-- Chino
(21, 'https://images.unsplash.com/photo-1473966968600-fa801b869a1a?auto=format&fit=crop&w=800&q=80', 1, 1),
-- Jean Brut
(22, 'https://images.unsplash.com/photo-1542272617-08f08637533d?auto=format&fit=crop&w=800&q=80', 1, 1),
-- Pantalon Flanelle
(23, 'https://images.unsplash.com/photo-1594633312681-425c7b97ccd1?auto=format&fit=crop&w=800&q=80', 1, 1),
-- Cargo
(24, 'https://images.unsplash.com/photo-1517445312882-5660c14b293d?auto=format&fit=crop&w=800&q=80', 1, 1),
-- Jean Délavé
(25, 'https://images.unsplash.com/photo-1541099649105-f69ad21f3246?auto=format&fit=crop&w=800&q=80', 1, 1),
-- Pinces
(26, 'https://images.unsplash.com/photo-1473966968600-fa801b869a1a?auto=format&fit=crop&w=800&q=80', 1, 1),
-- Jogging
(27, 'https://images.unsplash.com/photo-1552902865-b72c031ac5ea?auto=format&fit=crop&w=800&q=80', 1, 1),
-- Short Chino
(28, 'https://images.unsplash.com/photo-1591195853828-11db59a44f6b?auto=format&fit=crop&w=800&q=80', 1, 1),
-- Short Bain
(29, 'https://images.unsplash.com/photo-1565299624946-b28f40a0ae38?auto=format&fit=crop&w=800&q=80', 1, 1),
-- Velours
(30, 'https://images.unsplash.com/photo-1594633312681-425c7b97ccd1?auto=format&fit=crop&w=800&q=80', 1, 1),
-- Trench
(31, 'https://images.unsplash.com/photo-1487222477894-8943e31ef7b2?auto=format&fit=crop&w=800&q=80', 1, 1),
-- Veste Travail
(32, 'https://images.unsplash.com/photo-1591047139829-d91aecb6caea?auto=format&fit=crop&w=800&q=80', 1, 1),
-- Harrington
(33, 'https://images.unsplash.com/photo-1559551409-dadc959f76b8?auto=format&fit=crop&w=800&q=80', 1, 1),
-- Parka
(34, 'https://images.unsplash.com/photo-1544022613-e87ca19202d8?auto=format&fit=crop&w=800&q=80', 1, 1),
-- Veste Denim
(35, 'https://images.unsplash.com/photo-1576871337622-98d48d1cf531?auto=format&fit=crop&w=800&q=80', 1, 1),
-- Manteau Laine
(36, 'https://images.unsplash.com/photo-1539533018447-63fcce2678e3?auto=format&fit=crop&w=800&q=80', 1, 1),
-- Bomber
(37, 'https://images.unsplash.com/photo-1591047139829-d91aecb6caea?auto=format&fit=crop&w=800&q=80', 1, 1),
-- Surchemise
(38, 'https://images.unsplash.com/photo-1596755094514-f87e34085b2c?auto=format&fit=crop&w=800&q=80', 1, 1),
-- Blazer
(39, 'https://images.unsplash.com/photo-1507679799987-c73779587ccf?auto=format&fit=crop&w=800&q=80', 1, 1),
-- Cuir
(40, 'https://images.unsplash.com/photo-1487222477894-8943e31ef7b2?auto=format&fit=crop&w=800&q=80', 1, 1),
-- Runner
(41, 'https://images.unsplash.com/photo-1539185441755-769473a23570?auto=format&fit=crop&w=800&q=80', 1, 1),
-- Chelsea
(42, 'https://images.unsplash.com/photo-1638247025967-b4e38f787b76?auto=format&fit=crop&w=800&q=80', 1, 1),
-- Mocassins
(43, 'https://images.unsplash.com/photo-1614252369475-531eba835eb1?auto=format&fit=crop&w=800&q=80', 1, 1),
-- Sneakers Min
(44, 'https://images.unsplash.com/photo-1549298916-b41d501d3772?auto=format&fit=crop&w=800&q=80', 1, 1),
-- Bonnet
(45, 'https://images.unsplash.com/photo-1576871337632-b9aef4c17ab9?auto=format&fit=crop&w=800&q=80', 1, 1),
-- Echarpe
(46, 'https://images.unsplash.com/photo-1520903920243-00d872a2d1c9?auto=format&fit=crop&w=800&q=80', 1, 1),
-- Ceinture
(47, 'https://images.unsplash.com/photo-1624222247344-550fb60583dc?auto=format&fit=crop&w=800&q=80', 1, 1),
-- Sac
(48, 'https://images.unsplash.com/photo-1547949003-9792a18a2601?auto=format&fit=crop&w=800&q=80', 1, 1),
-- Porte-cartes
(49, 'https://images.unsplash.com/photo-1627123424574-18bd758e563d?auto=format&fit=crop&w=800&q=80', 1, 1),
-- Casquette
(50, 'https://images.unsplash.com/photo-1588850561407-ed78c282e89b?auto=format&fit=crop&w=800&q=80', 1, 1);


-- 4. Variantes (Génération de stock)
INSERT INTO variants (product_id, size, color, color_hex, stock, sku)
SELECT id, 'S', 'Noir', '#000000', 10, CONCAT('P', id, '-S-BLK') FROM products;

INSERT INTO variants (product_id, size, color, color_hex, stock, sku)
SELECT id, 'M', 'Noir', '#000000', 15, CONCAT('P', id, '-M-BLK') FROM products;

INSERT INTO variants (product_id, size, color, color_hex, stock, sku)
SELECT id, 'L', 'Noir', '#000000', 8, CONCAT('P', id, '-L-BLK') FROM products;

INSERT INTO variants (product_id, size, color, color_hex, stock, sku)
SELECT id, 'M', 'Blanc', '#FFFFFF', 12, CONCAT('P', id, '-M-WHT') FROM products WHERE id <= 20;

INSERT INTO variants (product_id, size, color, color_hex, stock, sku)
SELECT id, 'M', 'Navy', '#000080', 10, CONCAT('P', id, '-M-NVY') FROM products WHERE id > 20 AND id <= 40;

INSERT INTO variants (product_id, size, color, color_hex, stock, sku)
SELECT id, 'TU', 'Brun', '#8B4513', 20, CONCAT('P', id, '-TU-BRN') FROM products WHERE id > 45;


-- 5. Avis Clients (Aléatoires)
INSERT INTO reviews (product_id, user_id, rating, comment, created_at) VALUES
(1, 1, 5, "Incroyable qualité pour le prix.", NOW()),
(1, 2, 4, "Tissu un peu épais mais très bien coupé.", NOW()),
(2, 3, 5, "Parfait pour l'été.", NOW()),
(2, 4, 3, "Se froisse vite (normal pour du lin).", NOW()),
(11, 5, 5, "Mon pull préféré. Doux et chaud.", NOW()),
(11, 1, 5, "Je l'ai pris en 3 couleurs.", NOW()),
(22, 2, 5, "Le denim est rigide au début mais devient top.", NOW()),
(31, 3, 4, "Très beau trench, manches un peu longues.", NOW()),
(41, 4, 1, "Taille petit, attention.", NOW()),
(41, 5, 5, "Design minimaliste comme j'aime.", NOW()),
(5, 1, 5, "Excellent basique.", NOW()),
(15, 2, 4, "Boutons solides, bonne laine.", NOW()),
(34, 3, 5, "Testé par -10°C, nickel.", NOW()),
(48, 4, 5, "Le cuir sent bon, finitions parfaites.", NOW()),
(50, 5, 4, "Sympa pour le weekend.", NOW());