-- Supprimer et recréer proprement
DROP DATABASE IF EXISTS livraisonNoelS3;
CREATE DATABASE IF NOT EXISTS livraisonNoelS3;
USE livraisonNoelS3;

-- Tables (garder votre structure originale)
CREATE TABLE livraison_User(
    id INT AUTO_INCREMENT PRIMARY KEY,
    nomUser VARCHAR(50),
    loginUser VARCHAR(20),
    mdp VARCHAR(20)
);

CREATE TABLE livraison_Societes(
    id INT AUTO_INCREMENT PRIMARY KEY ,
    nomSociete VARCHAR(25),
    addresseSociete VARCHAR(20)
);

CREATE TABLE livraison_Vehicules(
    id INT AUTO_INCREMENT PRIMARY KEY,
    nomVehicule VARCHAR(50),
    idSociete INT,
    idTypeVehicule INT 
);

CREATE TABLE livraison_Chauffeur (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nomChauffeur VARCHAR(75),
    idSociete INT,
    salaire DOUBLE
);

CREATE TABLE livraison_Livraison(
    id INT AUTO_INCREMENT PRIMARY KEY,
    idColis INT,
    idEntrepot INT,
    destination VARCHAR(30),
    idVehicule INT,
    idEtat INT,
    coutVoiture DOUBLE,
    salaireJournalier DOUBLE,
    idChauffeur INT,
    dateLivraison DATETIME
);

CREATE TABLE livraison_TypeVehicules(
    id INT AUTO_INCREMENT PRIMARY KEY,
    poidsMax DOUBLE
);

CREATE TABLE livraison_Colis(
    id INT AUTO_INCREMENT PRIMARY KEY,
    descriptionColi VARCHAR(50),
    prixUnitaire DOUBLE,
    poidsColis DOUBLE
);

CREATE TABLE livraison_Entrepot(
    id INT AUTO_INCREMENT PRIMARY KEY,
    adresseEntrepot VARCHAR(20),
    nomEntrepot VARCHAR(50)
);

CREATE TABLE livraison_EtatLivraison(
    id INT AUTO_INCREMENT PRIMARY KEY,
    etatlivraison VARCHAR(20)
);

CREATE TABLE livraison_ZoneLivraison(
    id INT AUTO_INCREMENT PRIMARY KEY,
    zoneLivraison VARCHAR(20)
);

CREATE TABLE livraison_HistoriqueBenefice(
    id INT AUTO_INCREMENT PRIMARY KEY,
    dateDonnee DATETIME
);

-- Insertion des données de base
INSERT INTO livraison_EtatLivraison (etatlivraison) VALUES
('EN_ATTENTE'),
('LIVRE'),
('ANNULE');

INSERT INTO livraison_Societes (nomSociete, addresseSociete) VALUES
('FAST TRANS', 'Antananarivo'),
('MADALOG', 'Toamasina');

INSERT INTO livraison_TypeVehicules (poidsMax) VALUES
(500),
(1000),
(3000);

--  VEHICULE
INSERT INTO livraison_Vehicules (nomVehicule, idSociete, idTypeVehicule) VALUES
('Toyota Hiace', 1, 2),    -- id 1
('Isuzu Cargo', 1, 3),     -- id 2
('Kia Bongo', 2, 1),       -- id 3
('Renault Master', 1, 2),  -- id 4
('Mercedes Sprinter', 2, 3); -- id 5

INSERT INTO livraison_Vehicules (nomVehicule, idSociete, idTypeVehicule) VALUES
('Hyundai Porter', 1, 1),
('Peugeot Boxer', 2, 2),
('Fiat Ducato', 1, 3),
('Mazda Titan', 2, 2),
('Nissan Caravan', 1, 1);

-- CHAUFFEUR
-- 5 à 15000 Ar
INSERT INTO livraison_Chauffeur (nomChauffeur, idSociete, salaire) VALUES
('Alain RABE', 1, 15000),
('Niry ANDO', 2, 15000),
('Tiana RAKOTONIRINA', 1, 15000),
('Fanja RAZAFINDRAKOTO', 2, 15000),
('Joel RAMAROZAKA', 1, 15000);

-- 3 à 18000 Ar
INSERT INTO livraison_Chauffeur (nomChauffeur, idSociete, salaire) VALUES
('Eric RANDRIAMAMPIANINA', 2, 18000),
('Mamy RAKOTOBE', 1, 18000),
('Sandra RAZANAMASY', 2, 18000);

-- 4 à 20000 Ar
INSERT INTO livraison_Chauffeur (nomChauffeur, idSociete, salaire) VALUES
('Lova RAKOTONIAINA', 1, 20000),
('Hery RAZAFINDRAIBE', 2, 20000),
('Natacha RAMANANTSOA', 1, 20000),
('Bema RASOLOFO', 2, 20000);

INSERT INTO livraison_Entrepot (adresseEntrepot, nomEntrepot) VALUES
('Anosibe', 'Entrepot Central'),  -- id 1
('Ivato', 'Depot Nord');          -- id 2

INSERT INTO livraison_Colis (descriptionColi, poidsColis, prixUnitaire) VALUES
('Cartons alimentaires', 200, 1500),         -- id 1
('Materiel electronique', 100, 5000),        -- id 2
('Meubles', 800, 2000),                      -- id 3
('Vêtements', 150, 3000),                    -- id 4
('Médicaments', 50, 8000),                   -- id 5
('Fournitures bureau', 300, 2500),           -- id 6
('Matériaux construction', 2000, 1200),      -- id 7
('Produits agricoles', 500, 1800);           -- id 8

INSERT INTO livraison_User (nomUser, loginUser, mdp) VALUES
('Estelle', 'estelle', '1234'),
('Andry', 'andry', '1234');

-- Maintenant les livraisons avec des clés valides
INSERT INTO livraison_Livraison (idColis, idEntrepot, destination, idVehicule, idEtat, coutVoiture, salaireJournalier, idChauffeur, dateLivraison) VALUES
(1, 1, 'Antananarivo Centre', 1, 2, 50000, 15000, 1, '2024-12-10 08:30:00'),
(2, 2, 'Analamanga', 2, 2, 75000, 20000, 2, '2024-12-11 10:15:00'),
(3, 1, 'Itasy', 3, 1, 60000, 18000, 3, '2024-12-12 09:00:00'),
(4, 1, 'Vakinankaratra', 4, 2, 85000, 22000, 4, '2024-12-09 14:45:00'),
(5, 2, 'Haute Matsiatra', 5, 3, 90000, 25000, 5, '2024-12-08 11:30:00'),
(6, 1, 'Atsinanana', 1, 2, 70000, 19000, 1, '2024-12-13 16:20:00'),
(7, 2, 'Antananarivo Centre', 2, 1, 55000, 16000, 2, '2024-12-14 08:00:00'),
(8, 1, 'Analamanga', 3, 2, 80000, 21000, 3, '2024-12-07 13:15:00');

DROP VIEW IF EXISTS livraison_v_HistoriqueBenefice;

CREATE VIEW livraison_v_HistoriqueBenefice AS
SELECT 
    DATE(l.dateLivraison) AS jour,
    MONTH(l.dateLivraison) AS mois,
    YEAR(l.dateLivraison) AS annee,

    SUM(c.prixUnitaire * c.poidsColis) AS chiffreAffaire,

    SUM(l.coutVoiture + l.salaireJournalier) AS coutRevient,

    SUM(
        (c.prixUnitaire * c.poidsColis) 
        - (l.coutVoiture + l.salaireJournalier)
    ) AS benefice

FROM livraison_Livraison l
JOIN livraison_Colis c ON l.idColis = c.id
WHERE l.idEtat = 2   -- LIVRÉ
GROUP BY 
    jour, mois, annee;



DROP VIEW IF EXISTS livraison_v_livraison_detail;

CREATE VIEW livraison_v_livraison_detail AS
SELECT 
    l.id,
    c.descriptionColi,
    v.nomVehicule,
    ch.nomChauffeur,
    e.nomEntrepot,
    el.etatlivraison,
    l.dateLivraison,
    l.coutVoiture,
    l.salaireJournalier
FROM livraison_Livraison l
JOIN livraison_Colis c ON l.idColis = c.id
JOIN livraison_Vehicules v ON l.idVehicule = v.id
JOIN livraison_Chauffeur ch ON l.idChauffeur = ch.id
JOIN livraison_Entrepot e ON l.idEntrepot = e.id
JOIN livraison_EtatLivraison el ON l.idEtat = el.id;

ALTER TABLE livraison_ZoneLivraison ADD pourcentageZone;

-- ZONE LIVRAISON
-- 3 zones à 12.5%
INSERT INTO livraison_ZoneLivraison (zoneLivraison, pourcentageZone) VALUES
('Centre Ville', 12.5),
('Zone Nord', 12.5),
('Zone Sud', 12.5);

-- 2 zones à 0%
INSERT INTO livraison_ZoneLivraison (zoneLivraison, pourcentageZone) VALUES
('Zone Est', 0),
('Zone Ouest', 0);

