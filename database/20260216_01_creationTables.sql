create database BNGRC_trinome;

use BNGRC_trinome;

CREATE TABLE Ville (
    id_ville INT AUTO_INCREMENT PRIMARY KEY,
    nom_ville VARCHAR(100) NOT NULL,
    region VARCHAR(100) NOT NULL
);


CREATE TABLE CategoriesBesoins (
    id_categorie INT AUTO_INCREMENT PRIMARY KEY,
    nom_categorie VARCHAR(100) NOT NULL
);

CREATE TABLE Besoins (
    id_besoin INT AUTO_INCREMENT PRIMARY KEY,
    id_ville INT NOT NULL,
    id_categorie INT NOT NULL,
    nom_besoin VARCHAR(100) NOT NULL,
    prix_unitaire DECIMAL(10,2) NOT NULL,
    quantite INT NOT NULL,
    date_saisie DATETIME DEFAULT CURRENT_TIMESTAMP,

    FOREIGN KEY (id_ville) REFERENCES Ville(id_ville),
    FOREIGN KEY (id_categorie) REFERENCES CategoriesBesoins(id_categorie)
);

CREATE TABLE User (
    id_user INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100) NOT NULL,
    email VARCHAR(150),
    telephone VARCHAR(50),
    date_inscription DATETIME DEFAULT CURRENT_TIMESTAMP
);


CREATE TABLE Dons (
    id_don INT AUTO_INCREMENT PRIMARY KEY,
    id_user INT NOT NULL,
    id_categorie INT NOT NULL,
    nom_don VARCHAR(100) NOT NULL,
    quantite INT NOT NULL,
    montant DECIMAL(10,2),
    date_don DATETIME DEFAULT CURRENT_TIMESTAMP,

    FOREIGN KEY (id_user) REFERENCES User(id_user),
    FOREIGN KEY (id_categorie) REFERENCES CategoriesBesoins(id_categorie)
);



----------------
CREATE TABLE Dispatch (
    id_dispatch INT AUTO_INCREMENT PRIMARY KEY,
    id_don INT NOT NULL,
    id_besoin INT NOT NULL,
    quantite_attribuee INT NOT NULL,
    date_dispatch DATETIME DEFAULT CURRENT_TIMESTAMP,

    FOREIGN KEY (id_don) REFERENCES Dons(id_don),
    FOREIGN KEY (id_besoin) REFERENCES Besoins(id_besoin)
);


-- Ville 1---n Besoins
-- CategoriesBesoins 1---n Besoins
-- User 1---n Dons
-- Dons 1---n Dispatch
-- Besoins 1---n Dispatch