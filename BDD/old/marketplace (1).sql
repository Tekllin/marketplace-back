-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le : lun. 03 avr. 2023 à 07:50
-- Version du serveur : 8.0.31
-- Version de PHP : 8.0.26

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `marketplace`
--

-- --------------------------------------------------------

--
-- Structure de la table `actualites`
--

DROP TABLE IF EXISTS `actualites`;
CREATE TABLE IF NOT EXISTS `actualites` (
  `idActualite` int NOT NULL AUTO_INCREMENT,
  `libelleActualite` longtext COLLATE utf8mb4_bin NOT NULL,
  PRIMARY KEY (`idActualite`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

-- --------------------------------------------------------

--
-- Structure de la table `annonce`
--

DROP TABLE IF EXISTS `annonce`;
CREATE TABLE IF NOT EXISTS `annonce` (
  `idAnnonceProduit` int NOT NULL,
  `idAnnonceAnnoFacture` varchar(100) COLLATE utf8mb4_bin NOT NULL,
  `idEtatAnnonce` int NOT NULL,
  PRIMARY KEY (`idAnnonceProduit`,`idAnnonceAnnoFacture`),
  KEY `fk_Fournisseurs_has_Produits_Produits1_idx` (`idAnnonceProduit`),
  KEY `fk_Annonce_AnnonceFacture1_idx` (`idAnnonceAnnoFacture`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

-- --------------------------------------------------------

--
-- Structure de la table `annoncefacture`
--

DROP TABLE IF EXISTS `annoncefacture`;
CREATE TABLE IF NOT EXISTS `annoncefacture` (
  `idAnnonceFacture` varchar(100) COLLATE utf8mb4_bin NOT NULL,
  `refFacture` varchar(10) COLLATE utf8mb4_bin NOT NULL,
  `idAdresseAnnoFacture` int NOT NULL,
  `idAnnonceurAnnoFacture` int NOT NULL,
  `dateDebutAnnonce` date DEFAULT NULL,
  `dateAnnoFacture` varchar(45) COLLATE utf8mb4_bin NOT NULL,
  PRIMARY KEY (`idAnnonceFacture`),
  KEY `fk_AnnonceFacture_Facturations1_idx` (`idAdresseAnnoFacture`),
  KEY `fk_AnnonceFacture_Annonceurs1_idx` (`idAnnonceurAnnoFacture`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

--
-- Déchargement des données de la table `annoncefacture`
--

INSERT INTO `annoncefacture` (`idAnnonceFacture`, `refFacture`, `idAdresseAnnoFacture`, `idAnnonceurAnnoFacture`, `dateDebutAnnonce`, `dateAnnoFacture`) VALUES
('pi_3MZzGXGKuCg1RS3J1dRoQSLG', 'F6F03B1002', 1, 1, '1990-01-01', '10-02-23'),
('pi_3MbnTtGKuCg1RS3J1LIPC7HS', 'AD8A3B2A2F', 1, 1, NULL, '15-02-23'),
('pi_3McAT9GKuCg1RS3J03MKIZmK', '9FCE01FC67', 1, 1, '1990-01-01', '16-02-23'),
('pi_3McAXhGKuCg1RS3J1yCYEjxK', 'F086AD944A', 2, 2, NULL, '16-02-23'),
('pi_3McWt3GKuCg1RS3J0iooHI0P', 'DD1577DB7E', 1, 1, NULL, '17-02-23'),
('pi_3McpgXGKuCg1RS3J0aVX6H0Y', '2D8CA6A56E', 1, 1, NULL, '18-02-23'),
('pi_3McqH9GKuCg1RS3J06lda4Ed', '8FA4C058C3', 1, 1, NULL, '18-02-23');

-- --------------------------------------------------------

--
-- Structure de la table `annonceurs`
--

DROP TABLE IF EXISTS `annonceurs`;
CREATE TABLE IF NOT EXISTS `annonceurs` (
  `idAnnonceur` int NOT NULL AUTO_INCREMENT,
  `libelleNomAnnonceur` varchar(45) COLLATE utf8mb4_bin DEFAULT NULL,
  `adresseAnnonceur` varchar(255) COLLATE utf8mb4_bin NOT NULL,
  `villeAnnonceur` varchar(20) COLLATE utf8mb4_bin NOT NULL,
  `cpAnnonceur` int NOT NULL,
  `paysAnnonceur` varchar(30) COLLATE utf8mb4_bin NOT NULL,
  `siretAnnonceur` varchar(14) COLLATE utf8mb4_bin NOT NULL,
  `logoAnnonceur` varchar(40) COLLATE utf8mb4_bin DEFAULT NULL,
  `motivAnnonceur` text COLLATE utf8mb4_bin NOT NULL,
  `idEtat` int NOT NULL,
  PRIMARY KEY (`idAnnonceur`),
  KEY `fk_Fournisseur_User1_idx` (`idAnnonceur`),
  KEY `annonceurs` (`idEtat`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

--
-- Déchargement des données de la table `annonceurs`
--

INSERT INTO `annonceurs` (`idAnnonceur`, `libelleNomAnnonceur`, `adresseAnnonceur`, `villeAnnonceur`, `cpAnnonceur`, `paysAnnonceur`, `siretAnnonceur`, `logoAnnonceur`, `motivAnnonceur`, `idEtat`) VALUES
(1, 'SwerkINC', '9B Rue du Puits Carre', 'Evreux', 27000, 'France', '45052219800040', 'src/assets/img/logo/SwerkINC.jpg', '', 2);

-- --------------------------------------------------------

--
-- Structure de la table `avis`
--

DROP TABLE IF EXISTS `avis`;
CREATE TABLE IF NOT EXISTS `avis` (
  `idAvis` int NOT NULL,
  `libelleAvis` longtext COLLATE utf8mb4_bin NOT NULL,
  `noteAvis` int NOT NULL,
  `valid` tinyint DEFAULT NULL,
  `idProduitAvis` int NOT NULL,
  `idUserAvis` int NOT NULL,
  PRIMARY KEY (`idUserAvis`,`idProduitAvis`) USING BTREE,
  KEY `fk_Satisfaction_Produits1_idx` (`idProduitAvis`),
  KEY `fk_Satisfaction_User1_idx` (`idUserAvis`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

--
-- Déchargement des données de la table `avis`
--

INSERT INTO `avis` (`idAvis`, `libelleAvis`, `noteAvis`, `valid`, `idProduitAvis`, `idUserAvis`) VALUES
(0, 'rgerg', 4, 0, 29, 1),
(0, '<p>efz</p>', 3, 0, 31, 4);

-- --------------------------------------------------------

--
-- Structure de la table `categories`
--

DROP TABLE IF EXISTS `categories`;
CREATE TABLE IF NOT EXISTS `categories` (
  `idCategorie` int NOT NULL AUTO_INCREMENT,
  `libelleCategorie` varchar(45) COLLATE utf8mb4_bin NOT NULL,
  `idGammeCategorie` int NOT NULL,
  PRIMARY KEY (`idCategorie`),
  KEY `fk_Categories_Gammes1_idx` (`idGammeCategorie`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

--
-- Déchargement des données de la table `categories`
--

INSERT INTO `categories` (`idCategorie`, `libelleCategorie`, `idGammeCategorie`) VALUES
(1, 'Mobilité', 2),
(3, 'Accessoires', 2),
(5, 'Test Catégorie 2', 2);

-- --------------------------------------------------------

--
-- Structure de la table `cattutoactu`
--

DROP TABLE IF EXISTS `cattutoactu`;
CREATE TABLE IF NOT EXISTS `cattutoactu` (
  `idCategorie` int NOT NULL AUTO_INCREMENT,
  `libelleCategorie` varchar(45) COLLATE utf8mb4_bin NOT NULL,
  `couleurCategorie` varchar(15) COLLATE utf8mb4_bin NOT NULL,
  PRIMARY KEY (`idCategorie`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

--
-- Déchargement des données de la table `cattutoactu`
--

INSERT INTO `cattutoactu` (`idCategorie`, `libelleCategorie`, `couleurCategorie`) VALUES
(1, 'klklj', '#ff0000'),
(2, 'test', '#0080ff');

-- --------------------------------------------------------

--
-- Structure de la table `coefpointscredit`
--

DROP TABLE IF EXISTS `coefpointscredit`;
CREATE TABLE IF NOT EXISTS `coefpointscredit` (
  `idCoef` int NOT NULL AUTO_INCREMENT,
  `coefApplicable` decimal(10,2) NOT NULL,
  PRIMARY KEY (`idCoef`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

--
-- Déchargement des données de la table `coefpointscredit`
--

INSERT INTO `coefpointscredit` (`idCoef`, `coefApplicable`) VALUES
(1, '1.00'),
(2, '0.50');

-- --------------------------------------------------------

--
-- Structure de la table `commandefacture`
--

DROP TABLE IF EXISTS `commandefacture`;
CREATE TABLE IF NOT EXISTS `commandefacture` (
  `numFacture` varchar(45) COLLATE utf8mb4_bin NOT NULL,
  `dateFacture` date DEFAULT NULL,
  `prixTotalFacture` decimal(10,2) NOT NULL,
  `isCreditable` tinyint(1) NOT NULL,
  `idLivraisonFacture` int NOT NULL,
  `idFacturationFacture` int NOT NULL,
  `idUserFacture` int NOT NULL,
  `idEtatCommandeFacture` int NOT NULL,
  PRIMARY KEY (`numFacture`),
  KEY `fk_Commande_Livraisons1_idx` (`idLivraisonFacture`),
  KEY `fk_Commande_Facturations1_idx` (`idFacturationFacture`),
  KEY `fk_Commande_User1_idx` (`idUserFacture`),
  KEY `fk_CommandeFacture_EtatCommande1_idx` (`idEtatCommandeFacture`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

--
-- Déchargement des données de la table `commandefacture`
--

INSERT INTO `commandefacture` (`numFacture`, `dateFacture`, `prixTotalFacture`, `isCreditable`, `idLivraisonFacture`, `idFacturationFacture`, `idUserFacture`, `idEtatCommandeFacture`) VALUES
('6350AE2A28', '2023-02-18', '45.80', 0, 3, 4, 3, 3);

-- --------------------------------------------------------

--
-- Structure de la table `commander`
--

DROP TABLE IF EXISTS `commander`;
CREATE TABLE IF NOT EXISTS `commander` (
  `numFactComm` varchar(45) COLLATE utf8mb4_bin NOT NULL,
  `idProduitComm` int NOT NULL,
  `qtProduitComm` int NOT NULL,
  `idUserComm` int NOT NULL,
  PRIMARY KEY (`numFactComm`,`idProduitComm`,`idUserComm`) USING BTREE,
  KEY `fk_Commande_has_Produits_Produits1_idx` (`idProduitComm`),
  KEY `fk_Commande_has_Produits_Commande1_idx` (`numFactComm`),
  KEY `FK_idUserComm` (`idUserComm`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

--
-- Déchargement des données de la table `commander`
--

INSERT INTO `commander` (`numFactComm`, `idProduitComm`, `qtProduitComm`, `idUserComm`) VALUES
('6350AE2A28', 29, 1, 3);

-- --------------------------------------------------------

--
-- Structure de la table `concerner`
--

DROP TABLE IF EXISTS `concerner`;
CREATE TABLE IF NOT EXISTS `concerner` (
  `idAnnonceFactureConcerne` varchar(100) COLLATE utf8mb4_bin NOT NULL,
  `idTypeOffreConcerne` int NOT NULL,
  PRIMARY KEY (`idAnnonceFactureConcerne`,`idTypeOffreConcerne`),
  KEY `fk_AnnonceFacture_has_typeOffre_typeOffre1_idx` (`idTypeOffreConcerne`),
  KEY `fk_AnnonceFacture_has_typeOffre_AnnonceFacture1_idx` (`idAnnonceFactureConcerne`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

--
-- Déchargement des données de la table `concerner`
--

INSERT INTO `concerner` (`idAnnonceFactureConcerne`, `idTypeOffreConcerne`) VALUES
('pi_3MZzGXGKuCg1RS3J1dRoQSLG', 1),
('pi_3MbnTtGKuCg1RS3J1LIPC7HS', 1),
('pi_3McAT9GKuCg1RS3J03MKIZmK', 1),
('pi_3McAXhGKuCg1RS3J1yCYEjxK', 1),
('pi_3McWt3GKuCg1RS3J0iooHI0P', 1),
('pi_3McpgXGKuCg1RS3J0aVX6H0Y', 1),
('pi_3McqH9GKuCg1RS3J06lda4Ed', 1);

-- --------------------------------------------------------

--
-- Structure de la table `continent`
--

DROP TABLE IF EXISTS `continent`;
CREATE TABLE IF NOT EXISTS `continent` (
  `idContinent` int NOT NULL AUTO_INCREMENT,
  `libelleContinent` varchar(50) COLLATE utf8mb4_bin NOT NULL,
  PRIMARY KEY (`idContinent`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

--
-- Déchargement des données de la table `continent`
--

INSERT INTO `continent` (`idContinent`, `libelleContinent`) VALUES
(1, 'Europe'),
(2, 'Hors Europe');

-- --------------------------------------------------------

--
-- Structure de la table `droits`
--

DROP TABLE IF EXISTS `droits`;
CREATE TABLE IF NOT EXISTS `droits` (
  `idDroit` int NOT NULL AUTO_INCREMENT,
  `libelleDroit` varchar(45) COLLATE utf8mb4_bin NOT NULL,
  PRIMARY KEY (`idDroit`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

--
-- Déchargement des données de la table `droits`
--

INSERT INTO `droits` (`idDroit`, `libelleDroit`) VALUES
(1, 'superadmin'),
(2, 'admin_boutique'),
(3, 'admin_logistique'),
(4, 'partenaire'),
(5, 'client'),
(6, 'annonceur');

-- --------------------------------------------------------

--
-- Structure de la table `etatannonce`
--

DROP TABLE IF EXISTS `etatannonce`;
CREATE TABLE IF NOT EXISTS `etatannonce` (
  `idEtatAnnonce` int NOT NULL AUTO_INCREMENT,
  `libelleEtatAnnonce` varchar(45) COLLATE utf8mb4_bin DEFAULT NULL,
  PRIMARY KEY (`idEtatAnnonce`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

--
-- Déchargement des données de la table `etatannonce`
--

INSERT INTO `etatannonce` (`idEtatAnnonce`, `libelleEtatAnnonce`) VALUES
(1, 'En attente'),
(2, 'Validée'),
(3, 'Refusée');

-- --------------------------------------------------------

--
-- Structure de la table `etatcommande`
--

DROP TABLE IF EXISTS `etatcommande`;
CREATE TABLE IF NOT EXISTS `etatcommande` (
  `idEtatCommande` int NOT NULL AUTO_INCREMENT,
  `libelleEtatCommande` varchar(45) COLLATE utf8mb4_bin NOT NULL,
  PRIMARY KEY (`idEtatCommande`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

--
-- Déchargement des données de la table `etatcommande`
--

INSERT INTO `etatcommande` (`idEtatCommande`, `libelleEtatCommande`) VALUES
(1, 'Préparation'),
(2, 'En transit'),
(3, 'En livraison'),
(4, 'Livré');

-- --------------------------------------------------------

--
-- Structure de la table `etatcompte`
--

DROP TABLE IF EXISTS `etatcompte`;
CREATE TABLE IF NOT EXISTS `etatcompte` (
  `idEtatCompte` int NOT NULL AUTO_INCREMENT,
  `libelleEtat` varchar(50) COLLATE utf8mb4_bin NOT NULL,
  PRIMARY KEY (`idEtatCompte`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

--
-- Déchargement des données de la table `etatcompte`
--

INSERT INTO `etatcompte` (`idEtatCompte`, `libelleEtat`) VALUES
(1, 'En Attente'),
(2, 'Validé'),
(3, 'Refusé');

-- --------------------------------------------------------

--
-- Structure de la table `facturations`
--

DROP TABLE IF EXISTS `facturations`;
CREATE TABLE IF NOT EXISTS `facturations` (
  `idAdresse` int NOT NULL AUTO_INCREMENT,
  `idUserAdresse` int NOT NULL,
  `libelleAdresse` varchar(45) COLLATE utf8mb4_bin NOT NULL,
  `cpAdresse` varchar(45) COLLATE utf8mb4_bin NOT NULL,
  `villeAdresse` varchar(45) COLLATE utf8mb4_bin NOT NULL,
  `paysAdresse` varchar(45) COLLATE utf8mb4_bin NOT NULL,
  `etiquetteAdresse` varchar(45) COLLATE utf8mb4_bin DEFAULT NULL,
  PRIMARY KEY (`idAdresse`),
  KEY `fk_table1_Facturation1_idx` (`idUserAdresse`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

--
-- Déchargement des données de la table `facturations`
--

INSERT INTO `facturations` (`idAdresse`, `idUserAdresse`, `libelleAdresse`, `cpAdresse`, `villeAdresse`, `paysAdresse`, `etiquetteAdresse`) VALUES
(1, 1, 'ZEF', '42322', 'ZEF', 'Åland (les Îles)', NULL),
(2, 2, 'FZEEZF', '24242', 'ZEFZ', 'ZEFZEF', NULL),
(3, 4, 'FZFZE', '43243', 'EZF', 'ZFZEF', NULL),
(4, 3, 'SDGZEG', '423423', 'FEZFZEF', 'France ', '');

-- --------------------------------------------------------

--
-- Structure de la table `fraisdeport`
--

DROP TABLE IF EXISTS `fraisdeport`;
CREATE TABLE IF NOT EXISTS `fraisdeport` (
  `idTransporteurFdp` int NOT NULL,
  `idContinentFdp` int NOT NULL,
  `poids` decimal(10,2) NOT NULL,
  `tarif` decimal(10,2) NOT NULL,
  PRIMARY KEY (`idTransporteurFdp`,`idContinentFdp`,`poids`),
  KEY `fkContinentFdp` (`idContinentFdp`),
  KEY `fkTransporteurFdp` (`idTransporteurFdp`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

--
-- Déchargement des données de la table `fraisdeport`
--

INSERT INTO `fraisdeport` (`idTransporteurFdp`, `idContinentFdp`, `poids`, `tarif`) VALUES
(1, 1, '1.00', '5.00'),
(1, 1, '2.00', '7.00'),
(1, 1, '3.00', '2.00'),
(1, 1, '5.00', '10.00'),
(1, 1, '10.00', '15.50'),
(1, 1, '15.00', '21.00'),
(1, 1, '20.00', '27.00'),
(1, 1, '25.00', '34.70'),
(1, 1, '30.00', '41.90'),
(1, 2, '1.00', '7.50'),
(1, 2, '2.00', '12.00'),
(1, 2, '5.00', '16.85'),
(1, 2, '10.00', '22.36'),
(1, 2, '15.00', '28.41'),
(1, 2, '20.00', '34.30'),
(1, 2, '25.00', '42.94'),
(1, 2, '30.00', '55.33'),
(2, 1, '1.00', '5.50');

-- --------------------------------------------------------

--
-- Structure de la table `gammes`
--

DROP TABLE IF EXISTS `gammes`;
CREATE TABLE IF NOT EXISTS `gammes` (
  `idGamme` int NOT NULL AUTO_INCREMENT,
  `libelleGamme` varchar(45) COLLATE utf8mb4_bin NOT NULL,
  PRIMARY KEY (`idGamme`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

--
-- Déchargement des données de la table `gammes`
--

INSERT INTO `gammes` (`idGamme`, `libelleGamme`) VALUES
(2, 'High Tech');

-- --------------------------------------------------------

--
-- Structure de la table `historique`
--

DROP TABLE IF EXISTS `historique`;
CREATE TABLE IF NOT EXISTS `historique` (
  `idAnnonceHist` int NOT NULL AUTO_INCREMENT,
  `dateDebutAnnonce` datetime NOT NULL,
  `dateFinAnnonceHist` datetime NOT NULL,
  `idAnnonceProduitHist` int NOT NULL,
  `descriptionAnnonceHist` longtext COLLATE utf8mb4_bin NOT NULL,
  PRIMARY KEY (`idAnnonceHist`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

-- --------------------------------------------------------

--
-- Structure de la table `image`
--

DROP TABLE IF EXISTS `image`;
CREATE TABLE IF NOT EXISTS `image` (
  `idImage` int NOT NULL AUTO_INCREMENT,
  `libelleImage` varchar(45) COLLATE utf8mb4_bin DEFAULT NULL,
  `idProduitImage` int NOT NULL,
  PRIMARY KEY (`idImage`),
  KEY `fk_image_Produits1_idx` (`idProduitImage`)
) ENGINE=InnoDB AUTO_INCREMENT=36 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

--
-- Déchargement des données de la table `image`
--

INSERT INTO `image` (`idImage`, `libelleImage`, `idProduitImage`) VALUES
(22, '29-0.jpg', 29),
(23, '30-0.jpg', 30),
(25, '31-0.jpg', 31),
(27, '32-0.jpg', 32),
(31, '31-1.png', 31);

-- --------------------------------------------------------

--
-- Structure de la table `imagepub`
--

DROP TABLE IF EXISTS `imagepub`;
CREATE TABLE IF NOT EXISTS `imagepub` (
  `idImagePub` int NOT NULL AUTO_INCREMENT,
  `libelleImagePub` varchar(225) COLLATE utf8mb4_bin NOT NULL,
  `idPubliciteImage` int NOT NULL,
  PRIMARY KEY (`idImagePub`),
  KEY `idPubliciteImage` (`idPubliciteImage`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=42 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

--
-- Déchargement des données de la table `imagepub`
--

INSERT INTO `imagepub` (`idImagePub`, `libelleImagePub`, `idPubliciteImage`) VALUES
(35, '1-0.jpg', 1);

-- --------------------------------------------------------

--
-- Structure de la table `imgtutoactu`
--

DROP TABLE IF EXISTS `imgtutoactu`;
CREATE TABLE IF NOT EXISTS `imgtutoactu` (
  `idImg` int NOT NULL AUTO_INCREMENT,
  `idTutoActuImg` int NOT NULL,
  `lienImg` varchar(255) COLLATE utf8mb4_bin NOT NULL,
  PRIMARY KEY (`idImg`),
  KEY `fk_idTutoActuImg_has_idTutoActu` (`idTutoActuImg`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

--
-- Déchargement des données de la table `imgtutoactu`
--

INSERT INTO `imgtutoactu` (`idImg`, `idTutoActuImg`, `lienImg`) VALUES
(1, 23, 'src/assets/img/actututo/imgs/23/A-img-23-0-01-04-2023.jpg');

-- --------------------------------------------------------

--
-- Structure de la table `information`
--

DROP TABLE IF EXISTS `information`;
CREATE TABLE IF NOT EXISTS `information` (
  `idInformation` int NOT NULL AUTO_INCREMENT,
  `libelleInformation` longtext COLLATE utf8mb4_bin NOT NULL,
  PRIMARY KEY (`idInformation`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

-- --------------------------------------------------------

--
-- Structure de la table `infosite`
--

DROP TABLE IF EXISTS `infosite`;
CREATE TABLE IF NOT EXISTS `infosite` (
  `logoSite` varchar(255) COLLATE utf8mb4_bin NOT NULL,
  `logoEnteteSite` varchar(255) COLLATE utf8mb4_bin NOT NULL,
  `descriptionSite` varchar(255) COLLATE utf8mb4_bin NOT NULL,
  `emailSite` varchar(45) COLLATE utf8mb4_bin NOT NULL,
  `adresseSite` varchar(45) COLLATE utf8mb4_bin NOT NULL,
  `cpSite` varchar(5) COLLATE utf8mb4_bin NOT NULL,
  `villeSite` varchar(45) COLLATE utf8mb4_bin NOT NULL,
  `paysSite` varchar(45) COLLATE utf8mb4_bin NOT NULL,
  `numeroSite` varchar(15) COLLATE utf8mb4_bin NOT NULL,
  `copyright` varchar(45) COLLATE utf8mb4_bin NOT NULL,
  `qsnSite` text COLLATE utf8mb4_bin NOT NULL,
  `facebookSite` varchar(255) COLLATE utf8mb4_bin DEFAULT NULL,
  `instagramSite` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL,
  `linkdinSite` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

--
-- Déchargement des données de la table `infosite`
--

INSERT INTO `infosite` (`logoSite`, `logoEnteteSite`, `descriptionSite`, `emailSite`, `adresseSite`, `cpSite`, `villeSite`, `paysSite`, `numeroSite`, `copyright`, `qsnSite`, `facebookSite`, `instagramSite`, `linkdinSite`) VALUES
('src/assets/img/logo/globale/logo.png', 'assets/img/logo/header/logo-header.png', 'Une agence numérique née dans le but de réunir plusieurs graphistes, programmeurs, et pros du web pour répondre à tous vos besoins en faisant d\'internet un support de communication accessible. Nous sommes responsables et notre client est notre priorité', 'lschnekenburger@oslab-france.com', '9bis rue du Puits Carré ', '27000', 'Evreux', 'France', '0675586193', 'OSMarket', '&amp;lt;p&amp;gt;&amp;lt;span style=&amp;quot;color:hsl(0,75%,60%);&amp;quot;&amp;gt;&amp;lt;strong&amp;gt;Qui sommes-nous ?&amp;lt;/strong&amp;gt;&amp;lt;/span&amp;gt;&amp;lt;/p&amp;gt;', 'https://www.facebook.com/AltameosMultimedia', 'https://www.instagram.com/altameos_multimedia/', 'https://www.linkedin.com/company/altameos-multimedia/');

-- --------------------------------------------------------

--
-- Structure de la table `livraisons`
--

DROP TABLE IF EXISTS `livraisons`;
CREATE TABLE IF NOT EXISTS `livraisons` (
  `idAdresse` int NOT NULL AUTO_INCREMENT,
  `idUserAdresse` int NOT NULL,
  `libelleAdresse` varchar(45) COLLATE utf8mb4_bin NOT NULL,
  `cpAdresse` varchar(45) COLLATE utf8mb4_bin NOT NULL,
  `villeAdresse` varchar(45) COLLATE utf8mb4_bin NOT NULL,
  `paysAdresse` varchar(45) COLLATE utf8mb4_bin NOT NULL,
  `etiquetteAdresse` varchar(45) COLLATE utf8mb4_bin DEFAULT NULL,
  `codePaysAdresse` varchar(4) COLLATE utf8mb4_bin NOT NULL,
  PRIMARY KEY (`idAdresse`),
  KEY `fk_table1_Facturation1_idx` (`idUserAdresse`),
  KEY `FK_CODE_PAYS` (`codePaysAdresse`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

--
-- Déchargement des données de la table `livraisons`
--

INSERT INTO `livraisons` (`idAdresse`, `idUserAdresse`, `libelleAdresse`, `cpAdresse`, `villeAdresse`, `paysAdresse`, `etiquetteAdresse`, `codePaysAdresse`) VALUES
(1, 1, '31 Rue de la côte blanche', '27000', 'Evreux', '', 'GTDFNF', 'FRA'),
(2, 4, '31 Rue de la côte blanche', '27000', 'Evreux', '', '', 'FRA'),
(3, 3, 'DSG', '43334', 'ZGER', '', 'ZERG', 'FRA');

-- --------------------------------------------------------

--
-- Structure de la table `pagesconnexes`
--

DROP TABLE IF EXISTS `pagesconnexes`;
CREATE TABLE IF NOT EXISTS `pagesconnexes` (
  `idConnexe` int NOT NULL AUTO_INCREMENT,
  `imageConnexe` varchar(200) DEFAULT NULL,
  `titreConnexe` varchar(200) NOT NULL,
  `descriptionConnexe` text NOT NULL,
  `lienConnexe` text NOT NULL,
  PRIMARY KEY (`idConnexe`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `pagesconnexes`
--

INSERT INTO `pagesconnexes` (`idConnexe`, `imageConnexe`, `titreConnexe`, `descriptionConnexe`, `lienConnexe`) VALUES
(9, NULL, 'Rick Roll', 'Never gonna give you up', 'https://www.youtube.com/watch?v=dQw4w9WgXcQ'),
(10, NULL, 'szeg', 'eg', 'https://www.swerk.dev'),
(11, NULL, 'ZEF', 'ZEF', 'lien'),
(12, NULL, 'zerfgg', 'zefg', 'lien'),
(13, NULL, 'zefzef', 'zefzefez', 'fzefzefzefezf');

-- --------------------------------------------------------

--
-- Structure de la table `panier`
--

DROP TABLE IF EXISTS `panier`;
CREATE TABLE IF NOT EXISTS `panier` (
  `idPanier` int NOT NULL AUTO_INCREMENT,
  `idUser` int NOT NULL,
  `idProduitPanier` int NOT NULL,
  `qtProduit` int NOT NULL,
  PRIMARY KEY (`idPanier`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

--
-- Déchargement des données de la table `panier`
--

INSERT INTO `panier` (`idPanier`, `idUser`, `idProduitPanier`, `qtProduit`) VALUES
(7, 1, 28, 1),
(8, 4, 29, 1),
(9, 4, 28, 1),
(10, 1, 29, 1),
(11, 1, 31, 1),
(15, 4, 31, 1);

-- --------------------------------------------------------

--
-- Structure de la table `partenaires`
--

DROP TABLE IF EXISTS `partenaires`;
CREATE TABLE IF NOT EXISTS `partenaires` (
  `idPartenaire` int NOT NULL AUTO_INCREMENT,
  `nomSociete` varchar(45) COLLATE utf8mb4_bin DEFAULT NULL,
  `adresseSociete` varchar(45) COLLATE utf8mb4_bin DEFAULT NULL,
  `villeSociete` varchar(45) COLLATE utf8mb4_bin DEFAULT NULL,
  `cpSociete` varchar(45) COLLATE utf8mb4_bin DEFAULT NULL,
  `pays` varchar(45) COLLATE utf8mb4_bin DEFAULT NULL,
  `logo` varchar(45) COLLATE utf8mb4_bin DEFAULT NULL,
  `siret` varchar(14) COLLATE utf8mb4_bin NOT NULL,
  `motivPartenaire` text COLLATE utf8mb4_bin NOT NULL,
  `idEtatPartenaire` int NOT NULL,
  `titreFiche` text COLLATE utf8mb4_bin,
  `descriptionFiche` text COLLATE utf8mb4_bin,
  PRIMARY KEY (`idPartenaire`)
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

-- --------------------------------------------------------

--
-- Structure de la table `pays`
--

DROP TABLE IF EXISTS `pays`;
CREATE TABLE IF NOT EXISTS `pays` (
  `codePays` varchar(4) COLLATE utf8mb4_bin NOT NULL,
  `libellePays` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL,
  `idContinentPays` int NOT NULL,
  PRIMARY KEY (`codePays`),
  KEY `fk2` (`idContinentPays`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

--
-- Déchargement des données de la table `pays`
--

INSERT INTO `pays` (`codePays`, `libellePays`, `idContinentPays`) VALUES
('ABW', 'Azerbaïdjan', 2),
('AFG', 'Afghanistan ', 2),
('AGO', 'Angola ', 2),
('AIA', 'Anguilla', 2),
('ALA', 'Åland (les Îles)', 2),
('ALB', 'Albanie ', 2),
('AND', 'Andorre ', 2),
('ARE', 'Émirats arabes unis ', 2),
('ARG', 'Argentine ', 2),
('ARM', 'Arménie ', 2),
('ASM', 'Samoa américaines ', 2),
('ATA', 'Antarctique ', 2),
('ATF', 'Terres australes françaises', 2),
('ATG', 'Antigua-et-Barbuda', 2),
('AUS', 'Australie ', 2),
('AUT', 'Autriche ', 1),
('AZE', 'Azerba', 2),
('BDI', 'Burundi ', 2),
('BEL', 'Belgique ', 1),
('BEN', 'Bénin', 2),
('BES', 'Bonaire, Saint-Eustache et Saba', 2),
('BFA', 'Burkina Faso ', 2),
('BGD', 'Bangladesh ', 2),
('BGR', 'Bulgarie ', 1),
('BHR', 'Bahreïn', 2),
('BHS', 'Bahamas ', 2),
('BIH', 'Bosnie-Herzégovine ', 2),
('BLM', 'Saint-Barthélemy', 2),
('BLR', 'Bélarus', 2),
('BLZ', 'Belize ', 2),
('BMU', 'Bermudes ', 2),
('BOL', 'Bolivie', 2),
('BRA', 'Brésil', 2),
('BRB', 'Barbade ', 2),
('BRN', 'Brunéi Darussalam', 2),
('BTN', 'Bhoutan ', 2),
('BVT', 'Bouvet (l\'Île)', 2),
('BWA', 'Botswana ', 2),
('CAF', 'République centrAfricaine', 2),
('CAN', 'Canada ', 2),
('CCK', 'Cocos (les Îles) / Keeling (les Îles)', 2),
('CHE', 'Suisse ', 2),
('CHL', 'Chili ', 2),
('CHN', 'Chine ', 2),
('CIV', 'Côte d\'Ivoire', 2),
('CMR', 'Cameroun ', 2),
('COD', 'Congo (la République démocratique du)', 2),
('COG', 'Congo ', 2),
('COK', 'Cook (les Îles)', 2),
('COL', 'Colombie ', 2),
('COM', 'Comores ', 2),
('CPV', 'Cabo Verde', 2),
('CRI', 'Costa Rica ', 2),
('CUB', 'Cuba', 2),
('CUW', 'Curaçao', 2),
('CXR', 'Christmas (l\'Île)', 2),
('CYM', 'Caïmans (les Îles)', 2),
('CYP', 'Chypre', 1),
('CZE', 'Tchéquie', 1),
('DEU', 'Allemagne ', 1),
('DJI', 'Djibouti', 2),
('DMA', 'Dominique ', 2),
('DNK', 'Danemark ', 1),
('DOM', 'dominicaine (la République)', 2),
('DZA', 'Algérie ', 2),
('ECU', 'Équateur', 2),
('EGY', 'Égypte', 2),
('ERI', 'Érythrée', 2),
('ESH', 'Sahara occidental', 2),
('ESP', 'Espagne ', 1),
('EST', 'Estonie ', 1),
('ETH', 'Éthiopie', 2),
('FIN', 'Finlande ', 1),
('FJI', 'Fidji ', 2),
('FLK', 'Falkland (les ', 2),
('FRA', 'France ', 1),
('FRO', 'Falkland (les Îles) /Malouines (les Îles)', 2),
('FSM', 'Micronésie (États fédérés de)', 2),
('GAB', 'Gabon ', 2),
('GBR', 'Royaume-Uni de Grande-Bretagne et d\'Irlande du Nord ', 2),
('GEO', 'Géorgie', 2),
('GGY', 'Guernesey', 2),
('GHA', 'Ghana ', 2),
('GIB', 'Gibraltar', 2),
('GIN', 'Guinée', 2),
('GLP', 'Guadeloupe ', 2),
('GMB', 'Gambie ', 2),
('GNB', 'Guinée-Bissau', 2),
('GNQ', 'Guinée équatoriale', 2),
('GRC', 'Grèce ', 1),
('GRD', 'Grenade ', 2),
('GRL', 'Groenland ', 2),
('GTM', 'Guatemala ', 2),
('GUF', 'Guyane française ', 2),
('GUM', 'Guam', 2),
('GUY', 'Guyana ', 2),
('HKG', 'Hong Kong', 2),
('HMD', 'Heard-et-Îles MacDonald', 2),
('HND', 'Honduras ', 2),
('HRV', 'Croatie ', 1),
('HTI', 'Haïti', 2),
('HUN', 'Hongrie ', 1),
('IDN', 'Indonésie', 2),
('IMN', 'Île de Man', 2),
('IND', 'Inde ', 2),
('IOT', 'Indien (le Territoire britannique de l\'océan)', 2),
('IRL', 'Irlande ', 1),
('IRN', 'Iran', 2),
('IRQ', 'Iraq ', 2),
('ISL', 'Islande ', 2),
('ISR', 'Israël', 2),
('ITA', 'Italie ', 1),
('JAM', 'Jamaïque', 2),
('JEY', 'Jersey', 2),
('JOR', 'Jordanie ', 2),
('JPN', 'Japon ', 2),
('KAZ', 'Kazakhstan ', 2),
('KEN', 'Kenya ', 2),
('KGZ', 'Kirghizistan ', 2),
('KHM', 'Cambodge ', 2),
('KIR', 'Kiribati', 2),
('KNA', 'Saint-Kitts-et-Nevis', 2),
('KOR', 'Corée (la République de)', 2),
('KWT', 'Koweït', 2),
('LAO', 'Lao (la République démocratique populaire)', 2),
('LBN', 'Liban ', 2),
('LBR', 'Libéria', 2),
('LBY', 'Libye ', 2),
('LCA', 'Sainte-Lucie', 2),
('LIE', 'Liechtenstein ', 2),
('LKA', 'Sri Lanka', 2),
('LSO', 'Lesotho ', 2),
('LTU', 'Lituanie ', 1),
('LUX', 'Luxembourg ', 1),
('LVA', 'Lettonie ', 1),
('MAC', 'Macao', 2),
('MAF', 'Saint-Martin (partie française)', 2),
('MAR', 'Maroc ', 2),
('MCO', 'Monaco', 2),
('MDA', 'Moldavie (la République de)', 2),
('MDG', 'Madagascar', 2),
('MDV', 'Maldives ', 2),
('MEX', 'Mexique ', 2),
('MHL', 'Marshall (les Îles)', 2),
('MKD', 'Macédoine du Nord ', 2),
('MLI', 'Mali ', 2),
('MLT', 'Malte', 1),
('MMR', 'Myanmar ', 2),
('MNE', 'Monténégro ', 2),
('MNG', 'Mongolie ', 2),
('MNP', 'Mariannes du Nord (les Îles)', 2),
('MOZ', 'Mozambique ', 2),
('MRT', 'Mauritanie ', 2),
('MSR', 'Montserrat', 2),
('MTQ', 'Martinique ', 2),
('MUS', 'Maurice', 2),
('MWI', 'Malawi ', 2),
('MYS', 'Malaisie ', 2),
('MYT', 'Mayotte', 2),
('NAM', 'Namibie ', 2),
('NCL', 'Nouvelle-Calédonie', 2),
('NER', 'Niger ', 2),
('NFK', 'Norfolk (l\'Île)', 2),
('NGA', 'Nigéria', 2),
('NIC', 'Nicaragua ', 2),
('NIU', 'Niue', 2),
('NLD', 'Pays-Bas ', 1),
('NOR', 'Norvège', 2),
('NPL', 'Népal ', 2),
('NRU', 'Nauru', 2),
('NZL', 'Nouvelle-Zélande', 2),
('OMN', 'Oman', 2),
('PAK', 'Pakistan ', 2),
('PAN', 'Panama ', 2),
('PCN', 'Pitcairn', 2),
('PER', 'Pérou', 2),
('PHL', 'Philippines ', 2),
('PLW', 'Palaos ', 2),
('PNG', 'Papouasie-Nouvelle-Guinée ', 2),
('POL', 'Pologne ', 1),
('PRI', 'Porto Rico', 2),
('PRK', 'Corée (la République populaire démocratique de)', 2),
('PRT', 'Portugal ', 1),
('PRY', 'Paraguay ', 2),
('PSE', 'Palestine (État de) ', 2),
('PYF', 'Polynésie française', 2),
('QAT', 'Qatar ', 2),
('REU', 'Réunion', 2),
('ROU', 'Roumanie ', 1),
('RUS', 'Russie (la Fédération de)', 2),
('RWA', 'Rwanda ', 2),
('SAU', 'Arabie saoudite ', 2),
('SDN', 'Soudan ', 2),
('SEN', 'Sénégal ', 2),
('SGP', 'Singapour', 2),
('SGS', 'Géorgie du Sud-et-les Îles Sandwich du Sud ', 2),
('SHN', 'Sainte-Hélène, Ascension et Tristan da Cunha', 2),
('SJM', 'Svalbard et l\'Île Jan Mayen ', 2),
('SLB', 'Salomon (les Îles)', 2),
('SLE', 'Sierra Leone ', 2),
('SLV', 'El Salvador', 2),
('SMR', 'Saint-Marin', 2),
('SOM', 'Somalie ', 2),
('SPM', 'Saint-Pierre-et-Miquelon', 2),
('SRB', 'Serbie ', 2),
('SSD', 'Soudan du Sud ', 2),
('STP', 'Sao Tomé-et-Principe', 2),
('SUR', 'Suriname ', 2),
('SVK', 'Slovaquie ', 1),
('SVN', 'Slovénie ', 1),
('SWE', 'Suède ', 1),
('SWZ', 'Eswatini ', 2),
('SXM', 'Saint-Martin (partie néerlandaise)', 2),
('SYC', 'Seychelles ', 2),
('SYR', 'République arabe syrienne', 2),
('TCA', 'Turks-et-Caïcos (les Îles)', 2),
('TCD', 'Tchad ', 2),
('TGO', 'Togo ', 2),
('THA', 'Thaïlande', 2),
('TJK', 'Tadjikistan ', 2),
('TKL', 'Tokelau ', 2),
('TKM', 'Turkm', 2),
('TLS', 'Timor-Leste ', 2),
('TON', 'Tonga ', 2),
('TTO', 'Trinité-et-Tobago ', 2),
('TUN', 'Tunisie ', 2),
('TUR', 'Turquie ', 2),
('TUV', 'Tuvalu ', 2),
('TWN', 'Taïwan (Province de Chine)', 2),
('TZA', 'Tanzanie (la République-Unie de)', 2),
('UGA', 'Ouganda ', 2),
('UKR', 'Ukraine ', 2),
('UMI', 'Îles mineures éloignées des États-Unis ', 2),
('URY', 'Uruguay ', 2),
('USA', 'États-Unis d\'Amérique', 2),
('UZB', 'Ouzbékistan ', 2),
('VAT', 'Saint-Siège', 2),
('VCT', 'Saint-Vincent-et-les Grenadines', 2),
('VEN', 'Venezuela (République bolivarienne du)', 2),
('VGB', 'Vierges britanniques (les Îles)', 2),
('VIR', 'Vierges des États-Unis (les Îles)', 2),
('VNM', 'Viet Nam ', 2),
('VUT', 'Vanuatu ', 2),
('WLF', 'Wallis-et-Futuna', 2),
('WSM', 'Samoa ', 2),
('YEM', 'Yémen', 2),
('ZAF', 'Afrique du Sud ', 2),
('ZMB', 'Zambie ', 2),
('ZWE', 'Zimbabwe ', 2);

-- --------------------------------------------------------

--
-- Structure de la table `produits`
--

DROP TABLE IF EXISTS `produits`;
CREATE TABLE IF NOT EXISTS `produits` (
  `idProduit` int NOT NULL AUTO_INCREMENT,
  `idAnnonceurProduit` int NOT NULL,
  `libelleProduit` varchar(45) COLLATE utf8mb4_bin NOT NULL,
  `qtStock` varchar(45) COLLATE utf8mb4_bin NOT NULL,
  `descriptionProduit` longtext COLLATE utf8mb4_bin NOT NULL,
  `poidsProduit` decimal(11,2) NOT NULL,
  `prixUnitHT` decimal(10,2) NOT NULL,
  `miseEnAvant` tinyint(1) NOT NULL,
  `miseEnAvantCat` tinyint(1) NOT NULL,
  `adminProduit` tinyint(1) NOT NULL,
  `idCategorieProduit` int NOT NULL,
  `idTvaProduit` int NOT NULL,
  PRIMARY KEY (`idProduit`),
  KEY `fk_Produits_Fournisseurs1_idx` (`idAnnonceurProduit`),
  KEY `fk_Produits_Categories1_idx` (`idCategorieProduit`),
  KEY `produits` (`idTvaProduit`)
) ENGINE=InnoDB AUTO_INCREMENT=35 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

--
-- Déchargement des données de la table `produits`
--

INSERT INTO `produits` (`idProduit`, `idAnnonceurProduit`, `libelleProduit`, `qtStock`, `descriptionProduit`, `poidsProduit`, `prixUnitHT`, `miseEnAvant`, `miseEnAvantCat`, `adminProduit`, `idCategorieProduit`, `idTvaProduit`) VALUES
(29, 1, 'Sauvage', '233', '2EF', '234.00', '34.00', 0, 1, 0, 1, 1),
(30, 1, 'Sauvage', '433', 'RGERGfff', '3423.00', '2323.00', 0, 0, 0, 1, 1),
(31, 1, 'Parfumée', '234', 'ZEFZEFDSEZFEZZEFEZFml', '234.00', '423.00', 1, 1, 1, 3, 1);

-- --------------------------------------------------------

--
-- Structure de la table `publicites`
--

DROP TABLE IF EXISTS `publicites`;
CREATE TABLE IF NOT EXISTS `publicites` (
  `idPub` int NOT NULL AUTO_INCREMENT,
  `libellePub` varchar(255) COLLATE utf8mb4_bin NOT NULL,
  `ligne1` varchar(255) COLLATE utf8mb4_bin DEFAULT NULL,
  `ligne2` varchar(255) COLLATE utf8mb4_bin DEFAULT NULL,
  `ligne3` varchar(255) COLLATE utf8mb4_bin DEFAULT NULL,
  `lien` varchar(225) COLLATE utf8mb4_bin NOT NULL,
  PRIMARY KEY (`idPub`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

--
-- Déchargement des données de la table `publicites`
--

INSERT INTO `publicites` (`idPub`, `libellePub`, `ligne1`, `ligne2`, `ligne3`, `lien`) VALUES
(1, 'Altameos Multimédia', 'Ensemble Construisons', 'Un internet pour tous', 'L\'avenir est à nous !', 'https://www.altameos.com');

-- --------------------------------------------------------

--
-- Structure de la table `publicites2`
--

DROP TABLE IF EXISTS `publicites2`;
CREATE TABLE IF NOT EXISTS `publicites2` (
  `idPub` int NOT NULL AUTO_INCREMENT,
  `libellePub` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL,
  `ligne1` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL,
  `ligne2` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL,
  `ligne3` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL,
  `lien` varchar(225) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL,
  PRIMARY KEY (`idPub`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

--
-- Déchargement des données de la table `publicites2`
--

INSERT INTO `publicites2` (`idPub`, `libellePub`, `ligne1`, `ligne2`, `ligne3`, `lien`) VALUES
(1, 'Altameos Multimédia', 'Ensemble Construisons', 'Un internet pour tous', 'L\'avenir est à nous !', 'https://www.altameos.com'),
(7, 'teST', '', '', '', 'https://www.altameos.com');

-- --------------------------------------------------------

--
-- Structure de la table `session_paiement`
--

DROP TABLE IF EXISTS `session_paiement`;
CREATE TABLE IF NOT EXISTS `session_paiement` (
  `idSession` int NOT NULL AUTO_INCREMENT,
  `hashSession` varchar(256) COLLATE utf8mb4_bin NOT NULL,
  `idUserSession` int NOT NULL,
  PRIMARY KEY (`idSession`),
  KEY `FK_User_id` (`idUserSession`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

--
-- Déchargement des données de la table `session_paiement`
--

INSERT INTO `session_paiement` (`idSession`, `hashSession`, `idUserSession`) VALUES
(12, 'e1481c763d0dc13de931ef08976b8149', 3);

-- --------------------------------------------------------

--
-- Structure de la table `transporteur`
--

DROP TABLE IF EXISTS `transporteur`;
CREATE TABLE IF NOT EXISTS `transporteur` (
  `idTransporteur` int NOT NULL,
  `nomTransporteur` varchar(100) COLLATE utf8mb4_bin NOT NULL,
  PRIMARY KEY (`idTransporteur`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

--
-- Déchargement des données de la table `transporteur`
--

INSERT INTO `transporteur` (`idTransporteur`, `nomTransporteur`) VALUES
(1, 'DPD'),
(2, 'UPS');

-- --------------------------------------------------------

--
-- Structure de la table `tutoactu`
--

DROP TABLE IF EXISTS `tutoactu`;
CREATE TABLE IF NOT EXISTS `tutoactu` (
  `idActuTuto` int NOT NULL,
  `titreActuTuto` varchar(255) COLLATE utf8mb4_bin NOT NULL,
  `contenuActuTuto` longtext COLLATE utf8mb4_bin NOT NULL,
  `resumeActuTuto` varchar(400) COLLATE utf8mb4_bin NOT NULL,
  `datePublication` datetime DEFAULT NULL,
  `dateModification` datetime DEFAULT NULL,
  `imgPreview` varchar(255) COLLATE utf8mb4_bin NOT NULL,
  `boolActuTuto` tinyint(1) NOT NULL,
  `idCatActuTuto` int NOT NULL,
  PRIMARY KEY (`idActuTuto`),
  KEY `fk_idcategorie` (`idCatActuTuto`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

--
-- Déchargement des données de la table `tutoactu`
--

INSERT INTO `tutoactu` (`idActuTuto`, `titreActuTuto`, `contenuActuTuto`, `resumeActuTuto`, `datePublication`, `dateModification`, `imgPreview`, `boolActuTuto`, `idCatActuTuto`) VALUES
(23, 'sqQS', '&amp;lt;p&amp;gt;qsQS&amp;lt;/p&amp;gt;', 'qsQS', '2023-02-18 17:42:19', '2023-02-19 12:44:12', 'src/assets/img/actututo/previews/A-18-02-2023-17-42-19.png', 0, 1);

-- --------------------------------------------------------

--
-- Structure de la table `tva`
--

DROP TABLE IF EXISTS `tva`;
CREATE TABLE IF NOT EXISTS `tva` (
  `idTva` int NOT NULL,
  `libelleTva` varchar(30) COLLATE utf8mb4_bin NOT NULL,
  `pourcTva` decimal(11,2) DEFAULT NULL,
  PRIMARY KEY (`idTva`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

--
-- Déchargement des données de la table `tva`
--

INSERT INTO `tva` (`idTva`, `libelleTva`, `pourcTva`) VALUES
(1, 'TVA Normale', '20.00'),
(2, 'TVA Intermédiaire', '10.00'),
(3, 'TVA Réduite', '5.50'),
(4, 'TVA Super Réduite', '2.10');

-- --------------------------------------------------------

--
-- Structure de la table `typeoffre`
--

DROP TABLE IF EXISTS `typeoffre`;
CREATE TABLE IF NOT EXISTS `typeoffre` (
  `idTypeOffre` int NOT NULL,
  `libelleOffre` varchar(45) COLLATE utf8mb4_bin NOT NULL,
  `descriptionOffre` varchar(255) COLLATE utf8mb4_bin DEFAULT NULL,
  `dureeOffre` varchar(45) COLLATE utf8mb4_bin NOT NULL,
  `prixOffre` double DEFAULT NULL,
  `nbProduit` varchar(45) COLLATE utf8mb4_bin NOT NULL,
  PRIMARY KEY (`idTypeOffre`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

--
-- Déchargement des données de la table `typeoffre`
--

INSERT INTO `typeoffre` (`idTypeOffre`, `libelleOffre`, `descriptionOffre`, `dureeOffre`, `prixOffre`, `nbProduit`) VALUES
(1, 'Offre Standard', '<p>Ici la description</p>', '1', 4.99, '3'),
(2, 'Offre Argent', '<p>Ici la description</p>', '3', 29.99, '3'),
(3, 'Offre Gold', '<p>Ici la description</p>', '12', 149.99, '3');

-- --------------------------------------------------------

--
-- Structure de la table `user`
--

DROP TABLE IF EXISTS `user`;
CREATE TABLE IF NOT EXISTS `user` (
  `idUser` int NOT NULL AUTO_INCREMENT,
  `nomUser` varchar(45) COLLATE utf8mb4_bin NOT NULL,
  `prenomUser` varchar(45) COLLATE utf8mb4_bin NOT NULL,
  `emailUser` varchar(45) COLLATE utf8mb4_bin NOT NULL,
  `passwordUser` varchar(45) COLLATE utf8mb4_bin NOT NULL,
  `telUser` varchar(45) COLLATE utf8mb4_bin NOT NULL,
  `tokenInsc` varchar(255) COLLATE utf8mb4_bin DEFAULT NULL,
  `tokenConn` varchar(340) COLLATE utf8mb4_bin DEFAULT NULL,
  `newsUser` tinyint(1) NOT NULL,
  `idDroitUser` int NOT NULL,
  `idCoefPointsCredit` int NOT NULL,
  `soldePointsCredit` decimal(11,0) NOT NULL,
  PRIMARY KEY (`idUser`),
  KEY `fk_utilisateurs_Droits_idx` (`idDroitUser`),
  KEY `FK_idCoefPoints` (`idCoefPointsCredit`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

--
-- Déchargement des données de la table `user`
--

INSERT INTO `user` (`idUser`, `nomUser`, `prenomUser`, `emailUser`, `passwordUser`, `telUser`, `tokenInsc`, `tokenConn`, `newsUser`, `idDroitUser`, `idCoefPointsCredit`, `soldePointsCredit`) VALUES
(1, 'Annonceur', 'Marketplace', 'anno1@gmail.com', 'Ybx7bF97B+z3L/xXwuCtqw==', '0603030303', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpYXQiOjE2ODAyODE4MDEsImV4cCI6MTY4MDI4NTQwMSwiZGF0YSI6eyJlbWFpbCI6InVuZGVmaW5lZCJ9fQ.JfpPPMxg0FiMDHQIF6xJ9UyfUgfIqRnBZw23m6fqFgE', 'ZXlKMGVYQWlPaUpLVjFRaUxDSmhiR2NpT2lKSVV6STFOaUo5LmV5SnBZWFFpT2pFMk56WTNNall3T1Rjc0ltVjRjQ0k2TVRZM05qY3pNekk1Tnl3aVpHRjBZU0k2ZXlKbGJXRnBiQ0k2SW05c2FYZGxjamN5TVVCbmJXRnBiQzVqYjIwaWZYMC5GanJYSXJkNlh3NjV0UUp1Q1p3Z3otbzVydHZ3Szkzek5ELUJvZzZrbG5n', 0, 6, 1, '0'),
(3, 'Client', 'Marketplace', 'client@gmail.com', 'Ybx7bF97B+z3L/xXwuCtqw==', '0602020202', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpYXQiOjE2ODAyODE3NDAsImV4cCI6MTY4MDI4NTM0MCwiZGF0YSI6eyJlbWFpbCI6InVuZGVmaW5lZCJ9fQ.dbdXglPDp-uBEilmlanweeRpCoENNrGaYtuUW6tlh1U', 'ZXlKMGVYQWlPaUpLVjFRaUxDSmhiR2NpT2lKSVV6STFOaUo5LmV5SnBZWFFpT2pFMk56WTNNalV4TkRRc0ltVjRjQ0k2TVRZM05qY3pNak0wTkN3aVpHRjBZU0k2ZXlKbGJXRnBiQ0k2SW1Oc2FXVnVkRUJuYldGcGJDNWpiMjBpZlgwLjRTLXBTY2pBMFNvQ0hKUUEwcEx2TFJZNWlIaG9vZnRDREM4eUQ0dUZfU2c=', 0, 5, 1, '92'),
(4, 'Super', 'Admin', 'suadmin@altameos.com', 'Ybx7bF97B+z3L/xXwuCtqw==', '0600000000', '0', 'ZXlKMGVYQWlPaUpLVjFRaUxDSmhiR2NpT2lKSVV6STFOaUo5LmV5SnBZWFFpT2pFMk9EQTFNRGM1TURVc0ltVjRjQ0k2TVRZNE1EVXhOVEV3TlN3aVpHRjBZU0k2ZXlKbGJXRnBiQ0k2SW5OMVlXUnRhVzVBWVd4MFlXMWxiM011WTI5dEluMTkueG1LT3FxeTE3R3dkazU2RzM1OFhmSHBfSWVwOWFxdkN0c3A0UVhpU090aw==', 0, 1, 1, '0'),
(16, 'Admin', 'Shop', 'altameos2012@gmail.com', 'ooY/nfwSU6PFEAc7cLKMAw==', '0675586193', '0', NULL, 0, 2, 1, '0'),
(17, 'Admin', 'Logistics', 'altameos2021@gmail.com', 'qGWs15+tf/ynmiDsYcVwZA==', '0675000001', '0', NULL, 0, 3, 1, '0'),
(18, 'Partenaire', 'Test', 'logistic@altameos.com', 'qGWs15+tf/ynmiDsYcVwZA==', '0601010101', '0', NULL, 0, 4, 1, '0');

-- --------------------------------------------------------

--
-- Structure de la table `views`
--

DROP TABLE IF EXISTS `views`;
CREATE TABLE IF NOT EXISTS `views` (
  `hash` varchar(100) COLLATE utf8mb4_bin NOT NULL,
  `idProduitView` int NOT NULL,
  PRIMARY KEY (`hash`,`idProduitView`) USING BTREE,
  KEY `fk_views_Produits1_idx` (`idProduitView`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

--
-- Déchargement des données de la table `views`
--

INSERT INTO `views` (`hash`, `idProduitView`) VALUES
('08b80a6304516e6f5ad749ad9287eb44e9c0afca340eb9bc2f97967e485f5d51', 29),
('7b6d0ef3ab7d34f6af3610758dfd6539cc634b7a67d3b6ae5c53c30c1acccd55', 29),
('7d1ee29c676777396088aa9107e03b152da8248555443de8ec0603d0de2432a7', 29),
('a39636a4dbf7772677416f5cde94c51323e3a0acebc3ff4fc3420927b007b6f2', 29),
('b7b9dfbdf36329d03798420ce9b7392cca80b6c33add8e48689938fa9184564c', 29),
('f9aace1b6712836bdac99b9741894904bb3aeb9ef98c17b2f6bd1c86cd032ef6', 29),
('08b80a6304516e6f5ad749ad9287eb44e9c0afca340eb9bc2f97967e485f5d51', 30),
('b7b9dfbdf36329d03798420ce9b7392cca80b6c33add8e48689938fa9184564c', 30),
('08b80a6304516e6f5ad749ad9287eb44e9c0afca340eb9bc2f97967e485f5d51', 31),
('09ccb0a8a7036e46d3e811cf70f1fb9770dfd8aefb36828f9f8f420455ea4b32', 31),
('7b6d0ef3ab7d34f6af3610758dfd6539cc634b7a67d3b6ae5c53c30c1acccd55', 31),
('a39636a4dbf7772677416f5cde94c51323e3a0acebc3ff4fc3420927b007b6f2', 31),
('b7b9dfbdf36329d03798420ce9b7392cca80b6c33add8e48689938fa9184564c', 31),
('f926825bdaa6f9c4dcc693b77aca7be58341b33c9ae579f63ad5674af5249cee', 31),
('f9aace1b6712836bdac99b9741894904bb3aeb9ef98c17b2f6bd1c86cd032ef6', 31);

-- --------------------------------------------------------

--
-- Structure de la table `wishlist`
--

DROP TABLE IF EXISTS `wishlist`;
CREATE TABLE IF NOT EXISTS `wishlist` (
  `idUserWishList` int NOT NULL,
  `idProduitWishList` int NOT NULL,
  PRIMARY KEY (`idUserWishList`,`idProduitWishList`),
  KEY `FK2` (`idProduitWishList`),
  KEY `FK1` (`idUserWishList`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

--
-- Déchargement des données de la table `wishlist`
--

INSERT INTO `wishlist` (`idUserWishList`, `idProduitWishList`) VALUES
(1, 28),
(1, 29),
(4, 29),
(1, 31);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
