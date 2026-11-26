
CREATE DATABASE IF NOT EXISTS greenmarket ;
USE greenmarket;

CREATE TABLE utilisateur (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100) NOT NULL,
    prenom VARCHAR(100) NOT NULL,
    email VARCHAR(150) UNIQUE NOT NULL,
    mot_de_passe VARCHAR(255) NOT NULL,
    adresse VARCHAR(255) NOT NULL,
    telephone VARCHAR(10),
    role ENUM('client', 'producteur', 'administrateur') NOT NULL,
    date_inscription DATE,
    statut_compte VARCHAR(50)
);

CREATE TABLE categorie (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom_categorie VARCHAR(100) NOT NULL,
    description TEXT
);

CREATE TABLE produit (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_user INT NOT NULL,
    id_categorie INT,
    nom VARCHAR(100) NOT NULL,
    description TEXT,
    prix DECIMAL(10,2) NOT NULL,
    stock INT NOT NULL,
    photo VARCHAR(255),
    date_ajout TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    statut_publication VARCHAR(50) DEFAULT 'publie',
    FOREIGN KEY (id_user) REFERENCES utilisateur(id) ON DELETE CASCADE,
    FOREIGN KEY (id_categorie) REFERENCES categorie(id) ON DELETE SET NULL
);

CREATE TABLE boutique (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom_boutique VARCHAR(100) NOT NULL,
    statut_validation VARCHAR(50),
    id_user INT NOT NULL,
    FOREIGN KEY (id_user) REFERENCES utilisateur(id) ON DELETE CASCADE
);

CREATE TABLE avis (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_user INT NOT NULL,
    id_produit INT NOT NULL,
    note INT CHECK (note BETWEEN 1 AND 5),
    commentaire TEXT,
    date_avis TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    statut VARCHAR(50),
    FOREIGN KEY (id_user) REFERENCES utilisateur(id) ON DELETE CASCADE,
    FOREIGN KEY (id_produit) REFERENCES produit(id) ON DELETE CASCADE
);

CREATE TABLE panier (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_client INT NOT NULL,
    date_creation TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    statut VARCHAR(50),
    FOREIGN KEY (id_client) REFERENCES utilisateur(id) ON DELETE CASCADE
);

CREATE TABLE ligne_panier (
    id_panier INT NOT NULL,
    id_produit INT NOT NULL,
    quantite INT NOT NULL CHECK (quantite > 0),
    prix_unitaire DECIMAL(10,2) NOT NULL,
    PRIMARY KEY (id_panier, id_produit),
    FOREIGN KEY (id_panier) REFERENCES panier(id) ON DELETE CASCADE,
    FOREIGN KEY (id_produit) REFERENCES produit(id) ON DELETE CASCADE
);

CREATE TABLE facteur (
    id INT AUTO_INCREMENT PRIMARY KEY,
    date_facteur TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    montant_facteur DECIMAL(10,2) NOT NULL
);

CREATE TABLE paiement (
    id INT AUTO_INCREMENT PRIMARY KEY,
    mode_paiement ENUM('carte', 'livraison') DEFAULT 'livraison',
    date_paiement TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    montant DECIMAL(10,2) NOT NULL,
    statut_paiement VARCHAR(50)
);

CREATE TABLE commande (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_utilisateur INT NOT NULL,
    id_facteur INT,
    id_paiement INT,
    date_commande TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    total DECIMAL(10,2) NOT NULL,
    statut_commande ENUM('en_preparation', 'expedie', 'livre', 'annule') DEFAULT 'en_preparation',
    statut_paiement ENUM('en_attente', 'paye', 'annule') DEFAULT 'en_attente',
    FOREIGN KEY (id_utilisateur) REFERENCES utilisateur(id) ON DELETE CASCADE,
    FOREIGN KEY (id_facteur) REFERENCES facteur(id) ON DELETE SET NULL,
    FOREIGN KEY (id_paiement) REFERENCES paiement(id) ON DELETE SET NULL
);

CREATE TABLE ligne_commande (
    id_commande INT NOT NULL,
    id_produit INT NOT NULL,
    quantite INT NOT NULL CHECK (quantite > 0),
    prix_unitaire DECIMAL(10,2) NOT NULL,
    PRIMARY KEY (id_commande, id_produit),
    FOREIGN KEY (id_commande) REFERENCES commande(id) ON DELETE CASCADE,
    FOREIGN KEY (id_produit) REFERENCES produit(id) ON DELETE CASCADE
);

CREATE TABLE favoris (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_utilisateur INT NOT NULL,
    id_produit INT NOT NULL,
    date_ajout TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY unique_favori (id_utilisateur, id_produit),
    FOREIGN KEY (id_utilisateur) REFERENCES utilisateur(id) ON DELETE CASCADE,
    FOREIGN KEY (id_produit) REFERENCES produit(id) ON DELETE CASCADE
);
