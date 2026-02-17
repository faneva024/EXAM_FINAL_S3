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
    id_user INT NULL,
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
