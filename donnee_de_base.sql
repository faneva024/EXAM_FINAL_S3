-- ============================================
-- DONNÉES DE BASE BNGRC
-- Script d'insertion des données initiales
-- ============================================

USE BNGRC;

-- Désactiver les vérifications de clés étrangères
SET FOREIGN_KEY_CHECKS = 0;

-- Vider toutes les tables
TRUNCATE TABLE BNGRC_Dispatch;
TRUNCATE TABLE BNGRC_Achat;
TRUNCATE TABLE BNGRC_Dons;
TRUNCATE TABLE BNGRC_Besoins;
TRUNCATE TABLE BNGRC_User;
TRUNCATE TABLE BNGRC_Ville;
TRUNCATE TABLE BNGRC_CategoriesBesoins;
TRUNCATE TABLE BNGRC_Configuration;

-- Réactiver les vérifications de clés étrangères
SET FOREIGN_KEY_CHECKS = 1;

-- ============================================
-- CONFIGURATION
-- ============================================
INSERT INTO BNGRC_Configuration (cle, valeur) VALUES ('frais_achat_pourcent', '10');

-- ============================================
-- CATÉGORIES (id_categorie)
-- 1 = En nature
-- 2 = En matériels
-- 3 = En argent
-- ============================================
INSERT INTO BNGRC_CategoriesBesoins (nom_categorie) VALUES
('En nature'),       -- id = 1
('En matériels'),    -- id = 2
('En argent');       -- id = 3

-- ============================================
-- VILLES SINISTRÉES (id_ville)
-- ============================================
INSERT INTO BNGRC_Ville (nom_ville) VALUES
('Toamasina'),       -- id = 1
('Mananjary'),       -- id = 2
('Farafangana'),     -- id = 3
('Nosy Be'),         -- id = 4
('Morondava');        -- id = 5

-- ============================================
-- UTILISATEURS (donateurs)
-- ============================================
-- INSERT INTO BNGRC_User (nom, email, mot_de_passe, role) VALUES
-- ('ONG Hope', 'contact@onghope.mg', 'pass123', 'donateur'),
-- ('Croix-Rouge Madagascar', 'dons@croixrouge.mg', 'pass123', 'donateur'),
-- ('UNICEF Madagascar', 'aide@unicef.mg', 'pass123', 'donateur'),
-- ('PAM', 'contact@pam.org', 'pass123', 'donateur'),
-- ('Ambassade de France', 'aide@ambafrance.mg', 'pass123', 'donateur'),
-- ('Fondation Telma', 'rse@telma.mg', 'pass123', 'donateur'),
-- ('Diaspora Malgache', 'solidarite@diaspora.mg', 'pass123', 'donateur'),
-- ('Admin BNGRC', 'admin@bngrc.mg', 'admin123', 'admin');

-- ============================================
-- BESOINS (26 besoins - triés par Ordre de saisie)
-- ============================================
INSERT INTO BNGRC_Besoins (id_ville, id_categorie, nom_besoin, prix_unitaire, quantite, date_saisie) VALUES
-- Ordre 1 : Toamasina, matériel, Bâche
(1, 2, 'Bâche', 15000.00, 200, '2026-02-15 08:01:00'),
-- Ordre 2 : Nosy Be, matériel, Tôle
(4, 2, 'Tôle', 25000.00, 40, '2026-02-15 08:02:00'),
-- Ordre 3 : Mananjary, argent, Argent
(2, 3, 'Argent', 1.00, 6000000, '2026-02-15 08:03:00'),
-- Ordre 4 : Toamasina, nature, Eau (L)
(1, 1, 'Eau (L)', 1000.00, 1500, '2026-02-15 08:04:00'),
-- Ordre 5 : Nosy Be, nature, Riz (kg)
(4, 1, 'Riz (kg)', 3000.00, 300, '2026-02-15 08:05:00'),
-- Ordre 6 : Mananjary, matériel, Tôle
(2, 2, 'Tôle', 25000.00, 80, '2026-02-15 08:06:00'),
-- Ordre 7 : Nosy Be, argent, Argent
(4, 3, 'Argent', 1.00, 4000000, '2026-02-15 08:07:00'),
-- Ordre 8 : Farafangana, matériel, Bâche
(3, 2, 'Bâche', 15000.00, 150, '2026-02-16 08:08:00'),
-- Ordre 9 : Mananjary, nature, Riz (kg)
(2, 1, 'Riz (kg)', 3000.00, 500, '2026-02-15 08:09:00'),
-- Ordre 10 : Farafangana, argent, Argent
(3, 3, 'Argent', 1.00, 8000000, '2026-02-16 08:10:00'),
-- Ordre 11 : Morondava, nature, Riz (kg)
(5, 1, 'Riz (kg)', 3000.00, 700, '2026-02-16 08:11:00'),
-- Ordre 12 : Toamasina, argent, Argent
(1, 3, 'Argent', 1.00, 12000000, '2026-02-16 08:12:00'),
-- Ordre 13 : Morondava, argent, Argent
(5, 3, 'Argent', 1.00, 10000000, '2026-02-16 08:13:00'),
-- Ordre 14 : Farafangana, nature, Eau (L)
(3, 1, 'Eau (L)', 1000.00, 1000, '2026-02-15 08:14:00'),
-- Ordre 15 : Morondava, matériel, Bâche
(5, 2, 'Bâche', 15000.00, 180, '2026-02-16 08:15:00'),
-- Ordre 16 : Toamasina, matériel, Groupe électrogène
(1, 2, 'Groupe électrogène', 6750000.00, 3, '2026-02-15 08:16:00'),
-- Ordre 17 : Toamasina, nature, Riz (kg)
(1, 1, 'Riz (kg)', 3000.00, 800, '2026-02-16 08:17:00'),
-- Ordre 18 : Nosy Be, nature, Haricots
(4, 1, 'Haricots', 4000.00, 200, '2026-02-16 08:18:00'),
-- Ordre 19 : Mananjary, matériel, Clous (kg)
(2, 2, 'Clous (kg)', 8000.00, 60, '2026-02-16 08:19:00'),
-- Ordre 20 : Morondava, nature, Eau (L)
(5, 1, 'Eau (L)', 1000.00, 1200, '2026-02-15 08:20:00'),
-- Ordre 21 : Farafangana, nature, Riz (kg)
(3, 1, 'Riz (kg)', 3000.00, 600, '2026-02-16 08:21:00'),
-- Ordre 22 : Morondava, matériel, Bois
(5, 2, 'Bois', 10000.00, 150, '2026-02-15 08:22:00'),
-- Ordre 23 : Toamasina, matériel, Tôle
(1, 2, 'Tôle', 25000.00, 120, '2026-02-16 08:23:00'),
-- Ordre 24 : Nosy Be, matériel, Clous (kg)
(4, 2, 'Clous (kg)', 8000.00, 30, '2026-02-16 08:24:00'),
-- Ordre 25 : Mananjary, nature, Huile (L)
(2, 1, 'Huile (L)', 6000.00, 120, '2026-02-16 08:25:00'),
-- Ordre 26 : Farafangana, matériel, Bois
(3, 2, 'Bois', 10000.00, 100, '2026-02-15 08:26:00');
