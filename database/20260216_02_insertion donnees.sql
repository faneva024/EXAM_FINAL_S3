USE BNGRC;

-- =============================================
-- INSERTION DES DONNÉES DE TEST
-- Contexte : Dons pour les villes touchées par des cyclones à Madagascar
-- =============================================

-- =============================================
-- 1. VILLES TOUCHÉES PAR LES CYCLONES
-- =============================================
INSERT INTO BNGRC_Ville (nom_ville) VALUES
('Brickaville'),
('Foulpointe'),
('Mananjary'),
('Manakara'),
('Farafangana'),   
('Vatomandry'),
('Mahanoro'),
('Nosy Varika'),
('Toamasina'),
('Antalaha'),
('Sambava'),
('Sainte-Marie'),
('Fenoarivo Atsinanana'),
('Soanierana Ivongo'),
('Manompana');

-- =============================================
-- 2. CATÉGORIES DE BESOINS
-- =============================================
INSERT INTO BNGRC_CategoriesBesoins (nom_categorie) VALUES
('En nature'),
('En matériels'),
('En argent');

-- =============================================
-- 3. BESOINS DES VILLES SINISTRÉES
-- =============================================
INSERT INTO BNGRC_Besoins (id_ville, id_categorie, nom_besoin, prix_unitaire, quantite) VALUES
-- Mananjary (cyclone sévère) - id_ville = 3
(3, 1, 'Riz (sac 50kg)', 120000.00, 500),
(3, 1, 'Huile alimentaire (bidon 20L)', 85000.00, 200),
(3, 1, 'Jerricans eau potable (20L)', 15000.00, 1000),
(3, 1, 'Pastilles de purification eau', 5000.00, 3000),
(3, 2, 'Bâches plastiques', 35000.00, 400),
(3, 2, 'Tentes familiales', 250000.00, 150),
(3, 1, 'Kits de premiers secours', 45000.00, 300),
(3, 1, 'Couvertures', 25000.00, 600),

-- Manakara - id_ville = 4
(4, 1, 'Riz (sac 50kg)', 120000.00, 350),
(4, 1, 'Conserves alimentaires', 8000.00, 1500),
(4, 1, 'Jerricans eau potable (20L)', 15000.00, 800),
(4, 2, 'Bâches plastiques', 35000.00, 300),
(4, 1, 'Médicaments anti-paludisme', 12000.00, 500),
(4, 1, 'Vêtements enfants (lot)', 30000.00, 250),

-- Farafangana - id_ville = 5
(5, 1, 'Riz (sac 50kg)', 120000.00, 400),
(5, 1, 'Filtres à eau portables', 60000.00, 200),
(5, 2, 'Tôles ondulées', 45000.00, 1000),
(5, 1, 'Kits de premiers secours', 45000.00, 200),
(5, 2, 'Sacs de ciment (50kg)', 32000.00, 500),
(5, 2, 'Clous et vis (kg)', 15000.00, 300),

-- Brickaville - id_ville = 1
(1, 1, 'Riz (sac 50kg)', 120000.00, 200),
(1, 1, 'Légumes secs (sac 25kg)', 55000.00, 150),
(1, 2, 'Bâches plastiques', 35000.00, 250),
(1, 1, 'Médicaments anti-diarrhéiques', 8000.00, 800),
(1, 1, 'Ustensiles de cuisine (lot)', 40000.00, 100),

-- Foulpointe - id_ville = 2
(2, 1, 'Riz (sac 50kg)', 120000.00, 300),
(2, 1, 'Jerricans eau potable (20L)', 15000.00, 600),
(2, 2, 'Tentes familiales', 250000.00, 100),
(2, 1, 'Couvertures', 25000.00, 400),
(2, 1, 'Fournitures scolaires (kit)', 20000.00, 350),

-- Mahanoro - id_ville = 7
(7, 1, 'Riz (sac 50kg)', 120000.00, 250),
(7, 2, 'Tôles ondulées', 45000.00, 600),
(7, 2, 'Planches de bois (lot)', 80000.00, 200),
(7, 2, 'Sacs de ciment (50kg)', 32000.00, 400),

-- Toamasina - id_ville = 9
(9, 1, 'Conserves alimentaires', 8000.00, 2000),
(9, 1, 'Pastilles de purification eau', 5000.00, 5000),
(9, 2, 'Bâches plastiques', 35000.00, 500),
(9, 1, 'Kits médicaux d''urgence', 150000.00, 50),

-- Antalaha - id_ville = 10
(10, 1, 'Riz (sac 50kg)', 120000.00, 180),
(10, 2, 'Tentes familiales', 250000.00, 80),
(10, 1, 'Vêtements adultes (lot)', 45000.00, 200),
(10, 1, 'Matelas', 60000.00, 150);

-- =============================================
-- 4. UTILISATEURS (mot de passe hashé = "password123")
-- =============================================
INSERT INTO BNGRC_User (nom, email, mot_de_passe, role) VALUES
('Admin BNGRC', 'admin@bngrc.mg', '$2y$10$YQ8R3xG5VH6kLpKJZ0zXOeXk1f9Q2r5e3M7jD8wNpYzR4sTbC1mCu', 'admin'),
('Ravalomanana Jean', 'jean.ravalomanana@gmail.com', '$2y$10$YQ8R3xG5VH6kLpKJZ0zXOeXk1f9Q2r5e3M7jD8wNpYzR4sTbC1mCu', 'donneur'),
('Rakotoarisoa Marie', 'marie.rakotoarisoa@yahoo.fr', '$2y$10$YQ8R3xG5VH6kLpKJZ0zXOeXk1f9Q2r5e3M7jD8wNpYzR4sTbC1mCu', 'donneur'),
('ONG Croix-Rouge Madagascar', 'contact@croixrouge.mg', '$2y$10$YQ8R3xG5VH6kLpKJZ0zXOeXk1f9Q2r5e3M7jD8wNpYzR4sTbC1mCu', 'donneur'),
('UNICEF Madagascar', 'dons@unicef.mg', '$2y$10$YQ8R3xG5VH6kLpKJZ0zXOeXk1f9Q2r5e3M7jD8wNpYzR4sTbC1mCu', 'donneur'),
('Randrianarisoa Paul', 'paul.randria@gmail.com', '$2y$10$YQ8R3xG5VH6kLpKJZ0zXOeXk1f9Q2r5e3M7jD8wNpYzR4sTbC1mCu', 'donneur'),
('Ambassade de France', 'aide@ambafrance-mada.org', '$2y$10$YQ8R3xG5VH6kLpKJZ0zXOeXk1f9Q2r5e3M7jD8wNpYzR4sTbC1mCu', 'donneur'),
('Ratsimbazafy Hery', 'hery.ratsimba@outlook.com', '$2y$10$YQ8R3xG5VH6kLpKJZ0zXOeXk1f9Q2r5e3M7jD8wNpYzR4sTbC1mCu', 'donneur'),
('Care International', 'madagascar@care.org', '$2y$10$YQ8R3xG5VH6kLpKJZ0zXOeXk1f9Q2r5e3M7jD8wNpYzR4sTbC1mCu', 'donneur'),
('Razafindrakoto Lova', 'lova.razafin@gmail.com', '$2y$10$YQ8R3xG5VH6kLpKJZ0zXOeXk1f9Q2r5e3M7jD8wNpYzR4sTbC1mCu', 'donneur');

-- =============================================
-- 5. DONS REÇUS
-- =============================================
INSERT INTO BNGRC_Dons (id_user, id_categorie, nom_don, quantite, montant) VALUES
-- Dons de Jean Ravalomanana
(2, 1, 'Riz (sac 50kg)', 50, 6000000.00),
(2, 1, 'Couvertures', 100, 2500000.00),

-- Dons de Marie Rakotoarisoa
(3, 1, 'Conserves alimentaires', 200, 1600000.00),
(3, 1, 'Jerricans eau potable (20L)', 80, 1200000.00),

-- Dons de la Croix-Rouge
(4, 1, 'Kits de premiers secours', 150, 6750000.00),
(4, 2, 'Tentes familiales', 60, 15000000.00),
(4, 1, 'Riz (sac 50kg)', 200, 24000000.00),

-- Dons de l'UNICEF
(5, 1, 'Fournitures scolaires (kit)', 300, 6000000.00),
(5, 1, 'Médicaments anti-paludisme', 400, 4800000.00),
(5, 1, 'Pastilles de purification eau', 5000, 25000000.00),

-- Dons de Paul Randrianarisoa
(6, 2, 'Tôles ondulées', 100, 4500000.00),
(6, 2, 'Sacs de ciment (50kg)', 80, 2560000.00),

-- Dons de l'Ambassade de France
(7, 2, 'Bâches plastiques', 300, 10500000.00),
(7, 1, 'Kits médicaux d''urgence', 30, 4500000.00),
(7, 1, 'Matelas', 100, 6000000.00),

-- Dons de Hery Ratsimbazafy
(8, 1, 'Huile alimentaire (bidon 20L)', 40, 3400000.00),
(8, 1, 'Vêtements enfants (lot)', 80, 2400000.00),

-- Dons de Care International
(9, 1, 'Filtres à eau portables', 100, 6000000.00),
(9, 2, 'Tentes familiales', 40, 10000000.00),
(9, 1, 'Riz (sac 50kg)', 150, 18000000.00),

-- Dons de Lova Razafindrakoto
(10, 1, 'Ustensiles de cuisine (lot)', 50, 2000000.00),
(10, 1, 'Vêtements adultes (lot)', 60, 2700000.00),

-- Dons en argent
(2, 3, 'Don en argent', 1, 500000.00),
(5, 3, 'Don en argent', 1, 10000000.00),
(7, 3, 'Don en argent', 1, 5000000.00);

-- =============================================
-- 6. DISPATCHING DES DONS VERS LES ZONES SINISTRÉES
-- id_besoin mapping:
--   Mananjary:    1-8   | Manakara:     9-14
--   Farafangana: 15-20  | Brickaville: 21-25
--   Foulpointe:  26-30  | Mahanoro:    31-34
--   Toamasina:   35-38  | Antalaha:    39-42
-- =============================================
INSERT INTO BNGRC_Dispatch (id_don, id_besoin, quantite_attribuee) VALUES
-- Riz de Jean → Mananjary (besoin 1)
(1, 1, 30),
-- Riz de Jean → Brickaville (besoin 21)
(1, 21, 20),

-- Couvertures de Jean → Foulpointe (besoin 29)
(2, 29, 60),
-- Couvertures de Jean → Mananjary (besoin 8)
(2, 8, 40),

-- Conserves de Marie → Manakara (besoin 10)
(3, 10, 120),
-- Conserves de Marie → Toamasina (besoin 35)
(3, 35, 80),

-- Jerricans de Marie → Mananjary (besoin 3)
(4, 3, 50),
-- Jerricans de Marie → Foulpointe (besoin 27)
(4, 27, 30),

-- Kits premiers secours Croix-Rouge → Mananjary (besoin 7)
(5, 7, 80),
-- Kits premiers secours Croix-Rouge → Farafangana (besoin 18)
(5, 18, 70),

-- Tentes Croix-Rouge → Mananjary (besoin 6)
(6, 6, 35),
-- Tentes Croix-Rouge → Foulpointe (besoin 28)
(6, 28, 25),

-- Riz Croix-Rouge → Manakara (besoin 9)
(7, 9, 80),
-- Riz Croix-Rouge → Farafangana (besoin 15)
(7, 15, 70),
-- Riz Croix-Rouge → Mahanoro (besoin 31)
(7, 31, 50),

-- Fournitures scolaires UNICEF → Foulpointe (besoin 30)
(8, 30, 200),

-- Anti-paludisme UNICEF → Manakara (besoin 13)
(9, 13, 250),
-- Anti-diarrhéiques UNICEF → Brickaville (besoin 24)
(9, 24, 150),

-- Pastilles eau UNICEF → Mananjary (besoin 4)
(10, 4, 2500),
-- Pastilles eau UNICEF → Toamasina (besoin 36)
(10, 36, 2500),

-- Tôles de Paul → Farafangana (besoin 17)
(11, 17, 60),
-- Tôles de Paul → Mahanoro (besoin 32)
(11, 32, 40),

-- Ciment de Paul → Farafangana (besoin 19)
(12, 19, 50),
-- Ciment de Paul → Mahanoro (besoin 34)
(12, 34, 30),

-- Bâches Ambassade → Mananjary (besoin 5)
(13, 5, 120),
-- Bâches Ambassade → Manakara (besoin 12)
(13, 12, 100),
-- Bâches Ambassade → Toamasina (besoin 37)
(13, 37, 80),

-- Kits médicaux Ambassade → Toamasina (besoin 38)
(14, 38, 20),

-- Matelas Ambassade → Antalaha (besoin 42)
(15, 42, 70),

-- Huile de Hery → Mananjary (besoin 2)
(16, 2, 40),

-- Vêtements enfants Hery → Manakara (besoin 14)
(17, 14, 80),

-- Filtres à eau Care → Farafangana (besoin 16)
(18, 16, 60),

-- Tentes Care → Antalaha (besoin 40)
(19, 40, 40),

-- Riz Care → Mananjary (besoin 1)
(20, 1, 80),
-- Riz Care → Foulpointe (besoin 26)
(20, 26, 70),

-- Ustensiles Lova → Brickaville (besoin 25)
(21, 25, 50),

-- Vêtements adultes Lova → Antalaha (besoin 41)
(22, 41, 60);
