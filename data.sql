-- ============================================
-- BASE DE DONNÉES BNGRC - Suivi des Dons
-- Bureau National de Gestion des Risques
-- et des Catastrophes
-- ============================================
DROP DATABASE IF EXISTS BNGRC;
CREATE DATABASE BNGRC;
USE BNGRC;

-- ============================================
-- Table des villes sinistrées
-- ============================================
CREATE TABLE IF NOT EXISTS BNGRC_Ville (
    id_ville INT AUTO_INCREMENT PRIMARY KEY,
    nom_ville VARCHAR(100) NOT NULL
);

-- ============================================
-- Catégories de besoins
-- ============================================
CREATE TABLE IF NOT EXISTS BNGRC_CategoriesBesoins (
    id_categorie INT AUTO_INCREMENT PRIMARY KEY,
    nom_categorie VARCHAR(50) NOT NULL
);

-- ============================================
-- Besoins des sinistrés par ville
-- ============================================
CREATE TABLE IF NOT EXISTS BNGRC_Besoins (
    id_besoin INT AUTO_INCREMENT PRIMARY KEY,
    id_ville INT NOT NULL,
    id_categorie INT NOT NULL,
    nom_besoin VARCHAR(100) NOT NULL,
    prix_unitaire DECIMAL(12,2) NOT NULL,
    quantite DECIMAL(12,2) NOT NULL,
    date_saisie DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_ville) REFERENCES BNGRC_Ville(id_ville),
    FOREIGN KEY (id_categorie) REFERENCES BNGRC_CategoriesBesoins(id_categorie)
);

-- ============================================
-- Utilisateurs (donateurs, admin)
-- ============================================
CREATE TABLE IF NOT EXISTS BNGRC_User (
    id_user INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL UNIQUE,
    mot_de_passe VARCHAR(255) NOT NULL,
    role VARCHAR(50) DEFAULT 'donateur',
    date_inscription DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
);

-- ============================================
-- Dons reçus
-- ============================================
CREATE TABLE IF NOT EXISTS BNGRC_Dons (
    id_don INT AUTO_INCREMENT PRIMARY KEY,
    id_user INT NOT NULL,
    id_categorie INT NOT NULL,
    nom_don VARCHAR(100) NOT NULL,
    quantite DECIMAL(12,2) NULL,
    montant DECIMAL(12,2) NULL,
    date_don DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_user) REFERENCES BNGRC_User(id_user),
    FOREIGN KEY (id_categorie) REFERENCES BNGRC_CategoriesBesoins(id_categorie)
);

-- ============================================
-- Configuration (frais d'achat, etc.)
-- ============================================
CREATE TABLE IF NOT EXISTS BNGRC_Configuration (
    cle VARCHAR(50) PRIMARY KEY,
    valeur VARCHAR(100) NOT NULL
);

-- ============================================
-- Dispatch (attribution des dons aux besoins)
-- ============================================
CREATE TABLE IF NOT EXISTS BNGRC_Dispatch (
    id_dispatch INT AUTO_INCREMENT PRIMARY KEY,
    id_don INT NOT NULL,
    id_besoin INT NOT NULL,
    quantite_attribuee DECIMAL(12,2) NOT NULL,
    date_dispatch DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_don) REFERENCES BNGRC_Dons(id_don),
    FOREIGN KEY (id_besoin) REFERENCES BNGRC_Besoins(id_besoin)
);

-- ============================================
-- Achats via dons en argent
-- ============================================
CREATE TABLE IF NOT EXISTS BNGRC_Achat (
    id_achat INT AUTO_INCREMENT PRIMARY KEY,
    id_besoin INT NOT NULL,
    quantite DECIMAL(12,2) NOT NULL,
    prix_unitaire DECIMAL(12,2) NOT NULL,
    frais_pourcent DECIMAL(5,2) NOT NULL,
    montant_total DECIMAL(12,2) NOT NULL,
    date_achat DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_besoin) REFERENCES BNGRC_Besoins(id_besoin)
);

-- ============================================
-- VUES
-- ============================================

-- Vue détaillée des besoins
CREATE OR REPLACE VIEW v_besoins_details AS
SELECT 
    b.id_besoin, v.id_ville, v.nom_ville,
    c.id_categorie, c.nom_categorie,
    b.nom_besoin, b.prix_unitaire,
    b.quantite, (b.quantite * b.prix_unitaire) AS montant_total, b.date_saisie
FROM BNGRC_Besoins b
JOIN BNGRC_Ville v ON b.id_ville = v.id_ville
JOIN BNGRC_CategoriesBesoins c ON b.id_categorie = c.id_categorie;

-- Vue des besoins restants (non satisfaits) - prend en compte dispatch ET achats
CREATE OR REPLACE VIEW v_besoins_restants AS
SELECT 
    b.id_besoin, v.id_ville, v.nom_ville,
    c.id_categorie, c.nom_categorie,
    b.nom_besoin, b.prix_unitaire,
    b.quantite AS quantite_demandee,
    (COALESCE((SELECT SUM(d.quantite_attribuee) FROM BNGRC_Dispatch d WHERE d.id_besoin = b.id_besoin), 0)
     + COALESCE((SELECT SUM(a.quantite) FROM BNGRC_Achat a WHERE a.id_besoin = b.id_besoin), 0)) AS quantite_attribuee,
    (b.quantite 
     - COALESCE((SELECT SUM(d.quantite_attribuee) FROM BNGRC_Dispatch d WHERE d.id_besoin = b.id_besoin), 0)
     - COALESCE((SELECT SUM(a.quantite) FROM BNGRC_Achat a WHERE a.id_besoin = b.id_besoin), 0)) AS quantite_restante,
    ((b.quantite 
     - COALESCE((SELECT SUM(d.quantite_attribuee) FROM BNGRC_Dispatch d WHERE d.id_besoin = b.id_besoin), 0)
     - COALESCE((SELECT SUM(a.quantite) FROM BNGRC_Achat a WHERE a.id_besoin = b.id_besoin), 0)) * b.prix_unitaire) AS montant_restant
FROM BNGRC_Besoins b
JOIN BNGRC_Ville v ON b.id_ville = v.id_ville
JOIN BNGRC_CategoriesBesoins c ON b.id_categorie = c.id_categorie;

-- Vue des dons restants (non distribués)
CREATE OR REPLACE VIEW v_dons_restants AS
SELECT 
    dn.id_don, u.id_user, u.nom AS nom_user,
    c.id_categorie, c.nom_categorie,
    dn.nom_don, dn.quantite AS quantite_totale,
    COALESCE(SUM(di.quantite_attribuee), 0) AS quantite_distribuee,
    (COALESCE(dn.quantite, 0) - COALESCE(SUM(di.quantite_attribuee), 0)) AS quantite_restante,
    dn.montant, dn.date_don
FROM BNGRC_Dons dn
JOIN BNGRC_User u ON dn.id_user = u.id_user
JOIN BNGRC_CategoriesBesoins c ON dn.id_categorie = c.id_categorie
LEFT JOIN BNGRC_Dispatch di ON di.id_don = dn.id_don
GROUP BY dn.id_don, u.id_user, u.nom, c.id_categorie, c.nom_categorie, dn.nom_don, dn.quantite, dn.montant, dn.date_don;

-- Vue argent disponible
CREATE OR REPLACE VIEW v_argent_disponible AS
SELECT 
    COALESCE(SUM(dn.montant), 0) AS total_dons_argent,
    COALESCE((SELECT SUM(montant_total) FROM BNGRC_Achat), 0) AS total_achats,
    (COALESCE(SUM(dn.montant), 0) - COALESCE((SELECT SUM(montant_total) FROM BNGRC_Achat), 0)) AS argent_disponible
FROM BNGRC_Dons dn
WHERE dn.id_categorie = 3;

-- Vue récapitulative par ville
CREATE OR REPLACE VIEW v_recap_ville AS
SELECT 
    v.id_ville, v.nom_ville,
    COALESCE(SUM(b.quantite * b.prix_unitaire), 0) AS total_besoins,
    COALESCE(
        (SELECT SUM(di.quantite_attribuee * b2.prix_unitaire) 
         FROM BNGRC_Dispatch di 
         JOIN BNGRC_Besoins b2 ON di.id_besoin = b2.id_besoin 
         WHERE b2.id_ville = v.id_ville), 0
    ) + COALESCE(
        (SELECT SUM(ac.quantite * ac.prix_unitaire) 
         FROM BNGRC_Achat ac 
         JOIN BNGRC_Besoins b3 ON ac.id_besoin = b3.id_besoin 
         WHERE b3.id_ville = v.id_ville), 0
    ) AS total_satisfaits
FROM BNGRC_Ville v
LEFT JOIN BNGRC_Besoins b ON b.id_ville = v.id_ville
GROUP BY v.id_ville, v.nom_ville;

-- ============================================
-- DONNÉES DE TEST
-- ============================================

-- Configuration
INSERT INTO BNGRC_Configuration (cle, valeur) VALUES ('frais_achat_pourcent', '10');

-- 15 villes sinistrées
INSERT INTO BNGRC_Ville (nom_ville) VALUES
('Mananjary'), ('Manakara'), ('Farafangana'), ('Vangaindrano'), ('Ikongo'),
('Nosy Varika'), ('Ifanadiana'), ('Fort-Dauphin'), ('Morondava'), ('Antsirabe'),
('Ambositra'), ('Fianarantsoa'), ('Toamasina'), ('Mahanoro'), ('Vatomandry');

-- 3 catégories
INSERT INTO BNGRC_CategoriesBesoins (nom_categorie) VALUES
('En nature'), ('En matériels'), ('En argent');

-- 10 utilisateurs
INSERT INTO BNGRC_User (nom, email, mot_de_passe, role) VALUES
('ONG Hope', 'contact@onghope.mg', 'pass123', 'donateur'),
('Croix-Rouge Madagascar', 'dons@croixrouge.mg', 'pass123', 'donateur'),
('UNICEF Madagascar', 'aide@unicef.mg', 'pass123', 'donateur'),
('PAM', 'contact@pam.org', 'pass123', 'donateur'),
('Ambassade de France', 'aide@ambafrance.mg', 'pass123', 'donateur'),
('Fondation Telma', 'rse@telma.mg', 'pass123', 'donateur'),
('Diaspora Malgache', 'solidarite@diaspora.mg', 'pass123', 'donateur'),
('Entreprise SMTP', 'direction@smtp.mg', 'pass123', 'donateur'),
('Supermarché Jumbo', 'dons@jumbo.mg', 'pass123', 'donateur'),
('Admin BNGRC', 'admin@bngrc.mg', 'admin123', 'admin');

-- 42 besoins répartis sur les villes
INSERT INTO BNGRC_Besoins (id_ville, id_categorie, nom_besoin, prix_unitaire, quantite, date_saisie) VALUES
-- Mananjary (1)
(1, 1, 'Riz', 2500.00, 500, '2025-01-10 08:00:00'),
(1, 1, 'Huile', 8000.00, 100, '2025-01-10 08:05:00'),
(1, 2, 'Tôles', 35000.00, 200, '2025-01-10 08:10:00'),
(1, 2, 'Bâches', 20000.00, 150, '2025-01-10 08:15:00'),
-- Manakara (2)
(2, 1, 'Riz', 2500.00, 400, '2025-01-11 09:00:00'),
(2, 1, 'Sucre', 4000.00, 200, '2025-01-11 09:05:00'),
(2, 2, 'Clous', 12000.00, 80, '2025-01-11 09:10:00'),
(2, 2, 'Ciment', 45000.00, 50, '2025-01-11 09:15:00'),
-- Farafangana (3)
(3, 1, 'Eau', 500.00, 2000, '2025-01-12 10:00:00'),
(3, 1, 'Conserves', 6000.00, 300, '2025-01-12 10:05:00'),
(3, 2, 'Bois', 15000.00, 100, '2025-01-12 10:10:00'),
-- Vangaindrano (4)
(4, 1, 'Riz', 2500.00, 300, '2025-01-12 11:00:00'),
(4, 1, 'Médicaments', 15000.00, 50, '2025-01-12 11:05:00'),
(4, 2, 'Tôles', 35000.00, 150, '2025-01-12 11:10:00'),
(4, 2, 'Outils', 30000.00, 30, '2025-01-12 11:15:00'),
-- Ikongo (5)
(5, 1, 'Sucre', 4000.00, 150, '2025-01-13 08:00:00'),
(5, 1, 'Savon', 3000.00, 200, '2025-01-13 08:05:00'),
(5, 2, 'Pelles', 18000.00, 40, '2025-01-13 08:10:00'),
(5, 2, 'Seaux', 8000.00, 100, '2025-01-13 08:15:00'),
-- Nosy Varika (6)
(6, 1, 'Riz', 2500.00, 600, '2025-01-13 10:00:00'),
(6, 1, 'Huile', 8000.00, 150, '2025-01-13 10:05:00'),
(6, 2, 'Bâches', 20000.00, 200, '2025-01-13 10:10:00'),
(6, 1, 'Couvertures', 35000.00, 100, '2025-01-13 10:15:00'),
-- Ifanadiana (7)
(7, 1, 'Eau', 500.00, 1500, '2025-01-14 08:00:00'),
(7, 1, 'Vêtements', 25000.00, 80, '2025-01-14 08:05:00'),
(7, 2, 'Tôles', 35000.00, 100, '2025-01-14 08:10:00'),
(7, 2, 'Brouettes', 85000.00, 20, '2025-01-14 08:15:00'),
-- Fort-Dauphin (8)
(8, 1, 'Riz', 2500.00, 800, '2025-01-14 10:00:00'),
(8, 1, 'Conserves', 6000.00, 200, '2025-01-14 10:05:00'),
(8, 2, 'Ciment', 45000.00, 100, '2025-01-14 10:10:00'),
-- Morondava (9)
(9, 1, 'Sucre', 4000.00, 300, '2025-01-15 08:00:00'),
(9, 1, 'Médicaments', 15000.00, 30, '2025-01-15 08:05:00'),
(9, 2, 'Bois', 15000.00, 150, '2025-01-15 08:10:00'),
-- Antsirabe (10)
(10, 1, 'Riz', 2500.00, 200, '2025-01-15 10:00:00'),
(10, 2, 'Clous', 12000.00, 50, '2025-01-15 10:05:00'),
-- Fianarantsoa (12)
(12, 1, 'Eau', 500.00, 1000, '2025-01-16 08:00:00'),
(12, 1, 'Couvertures', 35000.00, 50, '2025-01-16 08:05:00'),
(12, 2, 'Outils', 30000.00, 20, '2025-01-16 08:10:00'),
-- Toamasina (13)
(13, 1, 'Riz', 2500.00, 500, '2025-01-16 10:00:00'),
(13, 1, 'Vêtements', 25000.00, 100, '2025-01-16 10:05:00'),
(13, 2, 'Bâches', 20000.00, 100, '2025-01-16 10:10:00'),
-- Mahanoro (14)
(14, 1, 'Conserves', 6000.00, 150, '2025-01-17 08:00:00'),
(14, 2, 'Pelles', 18000.00, 25, '2025-01-17 08:05:00');

-- 25 dons
INSERT INTO BNGRC_Dons (id_user, id_categorie, nom_don, quantite, montant, date_don) VALUES
-- Dons en nature (catégorie 1)
(1, 1, 'Riz', 200, NULL, '2025-01-15 08:00:00'),
(2, 1, 'Huile', 50, NULL, '2025-01-15 09:00:00'),
(3, 1, 'Conserves', 150, NULL, '2025-01-15 10:00:00'),
(4, 1, 'Riz', 500, NULL, '2025-01-16 08:00:00'),
(7, 1, 'Vêtements', 60, NULL, '2025-01-16 09:00:00'),
(9, 1, 'Sucre', 200, NULL, '2025-01-16 10:00:00'),
(1, 1, 'Savon', 300, NULL, '2025-01-17 08:00:00'),
(3, 1, 'Médicaments', 40, NULL, '2025-01-17 09:00:00'),
(4, 1, 'Riz', 300, NULL, '2025-01-17 10:00:00'),
(2, 1, 'Couvertures', 80, NULL, '2025-01-18 08:00:00'),
(7, 1, 'Eau', 1000, NULL, '2025-01-18 09:00:00'),
-- Dons en matériels (catégorie 2)
(8, 2, 'Tôles', 100, NULL, '2025-01-18 10:00:00'),
(6, 2, 'Bâches', 150, NULL, '2025-01-18 11:00:00'),
(8, 2, 'Tôles', 80, NULL, '2025-01-19 08:00:00'),
(9, 2, 'Outils', 25, NULL, '2025-01-19 09:00:00'),
(2, 2, 'Ciment', 60, NULL, '2025-01-19 10:00:00'),
(6, 2, 'Bois', 100, NULL, '2025-01-19 11:00:00'),
-- Dons en argent (catégorie 3)
(5, 3, 'Don financier', NULL, 5000000, '2025-01-16 11:00:00'),
(6, 3, 'Don financier', NULL, 3000000, '2025-01-17 14:00:00'),
(7, 3, 'Don financier', NULL, 2000000, '2025-01-18 16:00:00'),
(1, 3, 'Aide urgente', NULL, 1500000, '2025-01-19 10:00:00'),
(3, 3, 'Aide urgente', NULL, 8000000, '2025-01-20 08:00:00'),
(5, 3, 'Aide reconstruction', NULL, 10000000, '2025-01-20 14:00:00'),
(4, 3, 'Fonds alimentaire', NULL, 4000000, '2025-01-21 08:00:00'),
(9, 3, 'Don entreprise', NULL, 1000000, '2025-01-21 10:00:00');

-- Quelques dispatches validés
INSERT INTO BNGRC_Dispatch (id_don, id_besoin, quantite_attribuee, date_dispatch) VALUES
-- Don 1 (Riz 200) → Besoin 1 (Mananjary Riz 500)
(1, 1, 200, '2025-01-20 10:00:00'),
-- Don 4 (Riz 500) → Besoin 5 (Manakara Riz 400) + Besoin 12 (Vangaindrano Riz 300)
(4, 5, 400, '2025-01-20 10:05:00'),
(4, 12, 100, '2025-01-20 10:10:00'),
-- Don 2 (Huile 50) → Besoin 2 (Mananjary Huile 100)
(2, 2, 50, '2025-01-20 10:15:00'),
-- Don 12 (Tôles 100) → Besoin 3 (Mananjary Tôles 200)
(12, 3, 100, '2025-01-20 10:20:00'),
-- Don 13 (Bâches 150) → Besoin 4 (Mananjary Bâches 150)
(13, 4, 150, '2025-01-20 10:25:00');
