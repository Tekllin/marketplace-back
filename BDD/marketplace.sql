-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Hôte : localhost
-- Généré le : dim. 19 fév. 2023 à 16:17
-- Version du serveur : 8.0.27
-- Version de PHP : 8.1.0

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

CREATE TABLE `actualites` (
  `idActualite` int NOT NULL,
  `libelleActualite` longtext NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

-- --------------------------------------------------------

--
-- Structure de la table `annonce`
--

CREATE TABLE `annonce` (
  `idAnnonceProduit` int NOT NULL,
  `idAnnonceAnnoFacture` varchar(100) NOT NULL,
  `idEtatAnnonce` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

--
-- Déchargement des données de la table `annonce`
--

INSERT INTO `annonce` (`idAnnonceProduit`, `idAnnonceAnnoFacture`, `idEtatAnnonce`) VALUES
(29, 'pi_3MZzGXGKuCg1RS3J1dRoQSLG', 1);

-- --------------------------------------------------------

--
-- Structure de la table `annoncefacture`
--

CREATE TABLE `annoncefacture` (
  `idAnnonceFacture` varchar(100) NOT NULL,
  `refFacture` varchar(10) NOT NULL,
  `idAdresseAnnoFacture` int NOT NULL,
  `idAnnonceurAnnoFacture` int NOT NULL,
  `dateDebutAnnonce` date DEFAULT NULL,
  `dateAnnoFacture` varchar(45) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

--
-- Déchargement des données de la table `annoncefacture`
--

INSERT INTO `annoncefacture` (`idAnnonceFacture`, `refFacture`, `idAdresseAnnoFacture`, `idAnnonceurAnnoFacture`, `dateDebutAnnonce`, `dateAnnoFacture`) VALUES
('pi_3MbnTtGKuCg1RS3J1LIPC7HS', 'AD8A3B2A2F', 1, 1, NULL, '15-02-23'),
('pi_3McAT9GKuCg1RS3J03MKIZmK', '9FCE01FC67', 1, 1, '1990-01-01', '16-02-23'),
('pi_3McAXhGKuCg1RS3J1yCYEjxK', 'F086AD944A', 2, 2, NULL, '16-02-23'),
('pi_3McpgXGKuCg1RS3J0aVX6H0Y', '2D8CA6A56E', 1, 1, NULL, '18-02-23'),
('pi_3McqH9GKuCg1RS3J06lda4Ed', '8FA4C058C3', 1, 1, NULL, '18-02-23'),
('pi_3McWt3GKuCg1RS3J0iooHI0P', 'DD1577DB7E', 1, 1, NULL, '17-02-23'),
('pi_3MZzGXGKuCg1RS3J1dRoQSLG', 'F6F03B1002', 1, 1, '2023-02-10', '10-02-23');

-- --------------------------------------------------------

--
-- Structure de la table `annonceurs`
--

CREATE TABLE `annonceurs` (
  `idAnnonceur` int NOT NULL,
  `libelleNomAnnonceur` varchar(45) DEFAULT NULL,
  `adresseAnnonceur` varchar(255) NOT NULL,
  `villeAnnonceur` varchar(20) NOT NULL,
  `cpAnnonceur` int NOT NULL,
  `paysAnnonceur` varchar(30) NOT NULL,
  `siretAnnonceur` varchar(14) NOT NULL,
  `logoAnnonceur` varchar(40) DEFAULT NULL,
  `motivAnnonceur` text NOT NULL,
  `idEtat` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

--
-- Déchargement des données de la table `annonceurs`
--

INSERT INTO `annonceurs` (`idAnnonceur`, `libelleNomAnnonceur`, `adresseAnnonceur`, `villeAnnonceur`, `cpAnnonceur`, `paysAnnonceur`, `siretAnnonceur`, `logoAnnonceur`, `motivAnnonceur`, `idEtat`) VALUES
(1, 'SwerkINC', 'gfrdfg 48', 'gfdfdg', 12345, 'Belgique', '12345678912345', '', '', 2),
(2, 'Entreprise', '', '', 0, '', '', '', '', 2),
(3, 'fdsfsd', 'fsddsf 16', 'fdsdfs', 12345, 'Burundi', '12345678912345', 'src/assets/img/logo/fdsfsd.png', 'Ajouté par un super administrateur.', 1),
(4, 'Administration', 'Aucune', 'Aucune', 44444, 'Bénin', '44444444444444', 'logo.png', '', 2);

-- --------------------------------------------------------

--
-- Structure de la table `avis`
--

CREATE TABLE `avis` (
  `idAvis` int NOT NULL,
  `libelleAvis` longtext NOT NULL,
  `noteAvis` int NOT NULL,
  `valid` tinyint DEFAULT NULL,
  `idProduitAvis` int NOT NULL,
  `idUserAvis` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

--
-- Déchargement des données de la table `avis`
--

INSERT INTO `avis` (`idAvis`, `libelleAvis`, `noteAvis`, `valid`, `idProduitAvis`, `idUserAvis`) VALUES
(0, 'rgerg', 4, 0, 29, 1),
(0, '<p>ezg<strong>zeg</strong></p><p><strong>reg</strong>erg</p>', 3, 1, 31, 1),
(0, 'GFFGH', 3, 1, 28, 4),
(0, '<p>efz</p>', 3, 0, 31, 4);

-- --------------------------------------------------------

--
-- Structure de la table `categories`
--

CREATE TABLE `categories` (
  `idCategorie` int NOT NULL,
  `libelleCategorie` varchar(45) NOT NULL,
  `idGammeCategorie` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

--
-- Déchargement des données de la table `categories`
--

INSERT INTO `categories` (`idCategorie`, `libelleCategorie`, `idGammeCategorie`) VALUES
(1, 'Mobilité3', 1),
(3, 'Femme', 2);

-- --------------------------------------------------------

--
-- Structure de la table `cattutoactu`
--

CREATE TABLE `cattutoactu` (
  `idCategorie` int NOT NULL,
  `libelleCategorie` varchar(45) NOT NULL,
  `couleurCategorie` varchar(15) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

--
-- Déchargement des données de la table `cattutoactu`
--

INSERT INTO `cattutoactu` (`idCategorie`, `libelleCategorie`, `couleurCategorie`) VALUES
(1, 'klklj', '#ff0000');

-- --------------------------------------------------------

--
-- Structure de la table `coefpointscredit`
--

CREATE TABLE `coefpointscredit` (
  `idCoef` int NOT NULL,
  `coefApplicable` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

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

CREATE TABLE `commandefacture` (
  `numFacture` varchar(45) NOT NULL,
  `dateFacture` date DEFAULT NULL,
  `prixTotalFacture` decimal(10,2) NOT NULL,
  `isCreditable` tinyint(1) NOT NULL,
  `idLivraisonFacture` int NOT NULL,
  `idFacturationFacture` int NOT NULL,
  `idUserFacture` int NOT NULL,
  `idEtatCommandeFacture` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

--
-- Déchargement des données de la table `commandefacture`
--

INSERT INTO `commandefacture` (`numFacture`, `dateFacture`, `prixTotalFacture`, `isCreditable`, `idLivraisonFacture`, `idFacturationFacture`, `idUserFacture`, `idEtatCommandeFacture`) VALUES
('342', '0000-00-00', '0.00', 0, 1, 1, 1, 2),
('6350AE2A28', '2023-02-18', '45.80', 0, 3, 4, 3, 2);

-- --------------------------------------------------------

--
-- Structure de la table `commander`
--

CREATE TABLE `commander` (
  `numFactComm` varchar(45) NOT NULL,
  `idProduitComm` int NOT NULL,
  `qtProduitComm` int NOT NULL,
  `idUserComm` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

--
-- Déchargement des données de la table `commander`
--

INSERT INTO `commander` (`numFactComm`, `idProduitComm`, `qtProduitComm`, `idUserComm`) VALUES
('342', 31, 3, 1),
('6350AE2A28', 29, 1, 3);

-- --------------------------------------------------------

--
-- Structure de la table `concerner`
--

CREATE TABLE `concerner` (
  `idAnnonceFactureConcerne` varchar(100) NOT NULL,
  `idTypeOffreConcerne` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

--
-- Déchargement des données de la table `concerner`
--

INSERT INTO `concerner` (`idAnnonceFactureConcerne`, `idTypeOffreConcerne`) VALUES
('pi_3MbnTtGKuCg1RS3J1LIPC7HS', 1),
('pi_3McAT9GKuCg1RS3J03MKIZmK', 1),
('pi_3McAXhGKuCg1RS3J1yCYEjxK', 1),
('pi_3McpgXGKuCg1RS3J0aVX6H0Y', 1),
('pi_3McqH9GKuCg1RS3J06lda4Ed', 1),
('pi_3McWt3GKuCg1RS3J0iooHI0P', 1),
('pi_3MZzGXGKuCg1RS3J1dRoQSLG', 1);

-- --------------------------------------------------------

--
-- Structure de la table `continent`
--

CREATE TABLE `continent` (
  `idContinent` int NOT NULL,
  `libelleContinent` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

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

CREATE TABLE `droits` (
  `idDroit` int NOT NULL,
  `libelleDroit` varchar(45) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

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

CREATE TABLE `etatannonce` (
  `idEtatAnnonce` int NOT NULL,
  `libelleEtatAnnonce` varchar(45) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

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

CREATE TABLE `etatcommande` (
  `idEtatCommande` int NOT NULL,
  `libelleEtatCommande` varchar(45) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

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

CREATE TABLE `etatcompte` (
  `idEtatCompte` int NOT NULL,
  `libelleEtat` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

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

CREATE TABLE `facturations` (
  `idAdresse` int NOT NULL,
  `idUserAdresse` int NOT NULL,
  `libelleAdresse` varchar(45) NOT NULL,
  `cpAdresse` varchar(45) NOT NULL,
  `villeAdresse` varchar(45) NOT NULL,
  `paysAdresse` varchar(45) NOT NULL,
  `etiquetteAdresse` varchar(45) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

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

CREATE TABLE `fraisdeport` (
  `idTransporteurFdp` int NOT NULL,
  `idContinentFdp` int NOT NULL,
  `poids` decimal(10,2) NOT NULL,
  `tarif` decimal(10,2) NOT NULL
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

CREATE TABLE `gammes` (
  `idGamme` int NOT NULL,
  `libelleGamme` varchar(45) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

--
-- Déchargement des données de la table `gammes`
--

INSERT INTO `gammes` (`idGamme`, `libelleGamme`) VALUES
(1, 'Parfum'),
(2, 'Ordinateur');

-- --------------------------------------------------------

--
-- Structure de la table `historique`
--

CREATE TABLE `historique` (
  `idAnnonceHist` int NOT NULL,
  `dateDebutAnnonce` datetime NOT NULL,
  `dateFinAnnonceHist` datetime NOT NULL,
  `idAnnonceProduitHist` int NOT NULL,
  `descriptionAnnonceHist` longtext NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

-- --------------------------------------------------------

--
-- Structure de la table `image`
--

CREATE TABLE `image` (
  `idImage` int NOT NULL,
  `libelleImage` varchar(45) DEFAULT NULL,
  `idProduitImage` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

--
-- Déchargement des données de la table `image`
--

INSERT INTO `image` (`idImage`, `libelleImage`, `idProduitImage`) VALUES
(21, '28-0.jpg', 28),
(22, '29-0.jpg', 29),
(23, '30-0.jpg', 30),
(25, '31-0.jpg', 31),
(27, '32-0.jpg', 32),
(31, '31-1.png', 31);

-- --------------------------------------------------------

--
-- Structure de la table `imagepub`
--

CREATE TABLE `imagepub` (
  `idImagePub` int NOT NULL,
  `libelleImagePub` varchar(225) NOT NULL,
  `idPubliciteImage` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

--
-- Déchargement des données de la table `imagepub`
--

INSERT INTO `imagepub` (`idImagePub`, `libelleImagePub`, `idPubliciteImage`) VALUES
(35, '1-0.jpg', 1);

-- --------------------------------------------------------

--
-- Structure de la table `imgtutoactu`
--

CREATE TABLE `imgtutoactu` (
  `idImg` int NOT NULL,
  `idTutoActuImg` int NOT NULL,
  `lienImg` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

-- --------------------------------------------------------

--
-- Structure de la table `information`
--

CREATE TABLE `information` (
  `idInformation` int NOT NULL,
  `libelleInformation` longtext NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

-- --------------------------------------------------------

--
-- Structure de la table `infosite`
--

CREATE TABLE `infosite` (
  `logoSite` varchar(255) NOT NULL,
  `logoEnteteSite` varchar(255) NOT NULL,
  `descriptionSite` varchar(255) NOT NULL,
  `emailSite` varchar(45) NOT NULL,
  `adresseSite` varchar(45) NOT NULL,
  `cpSite` varchar(5) NOT NULL,
  `villeSite` varchar(45) NOT NULL,
  `paysSite` varchar(45) NOT NULL,
  `numeroSite` varchar(15) NOT NULL,
  `copyright` varchar(45) NOT NULL,
  `qsnSite` text NOT NULL,
  `facebookSite` varchar(255) DEFAULT NULL,
  `instagramSite` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL,
  `linkdinSite` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

--
-- Déchargement des données de la table `infosite`
--

INSERT INTO `infosite` (`logoSite`, `logoEnteteSite`, `descriptionSite`, `emailSite`, `adresseSite`, `cpSite`, `villeSite`, `paysSite`, `numeroSite`, `copyright`, `qsnSite`, `facebookSite`, `instagramSite`, `linkdinSite`) VALUES
('assets/img/logo/globale/logo.png', 'assets/img/logo/header/logo-header.png', 'description', 'email', 'adresse', '12345', 'ville', 'Azerbaïdjan', 'numero', 'copyright', '&amp;lt;p&amp;gt;&amp;lt;span style=&amp;quot;color:hsl(0,75%,60%);&amp;quot;&amp;gt;&amp;lt;strong&amp;gt;Qui sommes-nous ?&amp;lt;/strong&amp;gt;&amp;lt;/span&amp;gt;&amp;lt;/p&amp;gt;', 'https://m.facebook.com/', 'https://www.instagram.com/', 'https://fr.linkedin.com/');

-- --------------------------------------------------------

--
-- Structure de la table `livraisons`
--

CREATE TABLE `livraisons` (
  `idAdresse` int NOT NULL,
  `idUserAdresse` int NOT NULL,
  `libelleAdresse` varchar(45) NOT NULL,
  `cpAdresse` varchar(45) NOT NULL,
  `villeAdresse` varchar(45) NOT NULL,
  `paysAdresse` varchar(45) NOT NULL,
  `etiquetteAdresse` varchar(45) DEFAULT NULL,
  `codePaysAdresse` varchar(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

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

CREATE TABLE `pagesconnexes` (
  `idConnexe` int NOT NULL,
  `imageConnexe` varchar(200) DEFAULT NULL,
  `titreConnexe` varchar(200) NOT NULL,
  `descriptionConnexe` text NOT NULL,
  `lienConnexe` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

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

CREATE TABLE `panier` (
  `idPanier` int NOT NULL,
  `idUser` int NOT NULL,
  `idProduitPanier` int NOT NULL,
  `qtProduit` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

--
-- Déchargement des données de la table `panier`
--

INSERT INTO `panier` (`idPanier`, `idUser`, `idProduitPanier`, `qtProduit`) VALUES
(7, 1, 28, 1),
(8, 4, 29, 1),
(9, 4, 28, 1),
(10, 1, 29, 1),
(11, 1, 31, 1);

-- --------------------------------------------------------

--
-- Structure de la table `partenaires`
--

CREATE TABLE `partenaires` (
  `idPartenaire` int NOT NULL,
  `nomSociete` varchar(45) DEFAULT NULL,
  `adresseSociete` varchar(45) DEFAULT NULL,
  `villeSociete` varchar(45) DEFAULT NULL,
  `cpSociete` varchar(45) DEFAULT NULL,
  `pays` varchar(45) DEFAULT NULL,
  `logo` varchar(45) DEFAULT NULL,
  `siret` varchar(14) NOT NULL,
  `motivPartenaire` text NOT NULL,
  `idEtatPartenaire` int NOT NULL,
  `titreFiche` text,
  `descriptionFiche` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

--
-- Déchargement des données de la table `partenaires`
--

INSERT INTO `partenaires` (`idPartenaire`, `nomSociete`, `adresseSociete`, `villeSociete`, `cpSociete`, `pays`, `logo`, `siret`, `motivPartenaire`, `idEtatPartenaire`, `titreFiche`, `descriptionFiche`) VALUES
(1, 'gfdfgd', 'fgdfgd 15', 'fgdfgd', '12345', 'Anguilla', 'src/assets/img/logo/gfdfgd.png', '12345678912345', 'Ajouté par un super administrateur.', 1, NULL, NULL),
(13, 'fdsfds', 'fsddfs 48', 'fdssdf', '12356', 'Belgique', 'src/assets/img/logo/fdsfds.png', '11234567891234', 'Ajouté par un super administrateur.', 1, NULL, NULL);

-- --------------------------------------------------------

--
-- Structure de la table `pays`
--

CREATE TABLE `pays` (
  `codePays` varchar(4) NOT NULL,
  `libellePays` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL,
  `idContinentPays` int NOT NULL
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

CREATE TABLE `produits` (
  `idProduit` int NOT NULL,
  `idAnnonceurProduit` int NOT NULL,
  `libelleProduit` varchar(45) NOT NULL,
  `qtStock` varchar(45) NOT NULL,
  `descriptionProduit` longtext NOT NULL,
  `poidsProduit` decimal(11,2) NOT NULL,
  `prixUnitHT` decimal(10,2) NOT NULL,
  `miseEnAvant` tinyint(1) NOT NULL,
  `miseEnAvantCat` tinyint(1) NOT NULL,
  `adminProduit` tinyint(1) NOT NULL,
  `idCategorieProduit` int NOT NULL,
  `idTvaProduit` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

--
-- Déchargement des données de la table `produits`
--

INSERT INTO `produits` (`idProduit`, `idAnnonceurProduit`, `libelleProduit`, `qtStock`, `descriptionProduit`, `poidsProduit`, `prixUnitHT`, `miseEnAvant`, `miseEnAvantCat`, `adminProduit`, `idCategorieProduit`, `idTvaProduit`) VALUES
(28, 4, 'Produit test', '0', 'ZRAAAAAAAAAzef', '234.00', '43.00', 0, 1, 1, 1, 1),
(29, 1, 'Sauvage', '233', '2EF', '234.00', '34.00', 1, 1, 0, 1, 1),
(30, 1, 'Sauvage', '433', '<p>RGERGf<strong>ff</strong></p>', '3423.00', '2323.00', 0, 0, 0, 1, 1),
(31, 1, 'Parfumée', '234', 'ZEFZEFDSEZFEZZEFEZFml', '234.00', '423.00', 1, 1, 1, 3, 1),
(32, 1, 'ezf', '3432', 'ZEF', '32.00', '3.00', 1, 1, 0, 1, 1);

-- --------------------------------------------------------

--
-- Structure de la table `publicites`
--

CREATE TABLE `publicites` (
  `idPub` int NOT NULL,
  `libellePub` varchar(255) NOT NULL,
  `ligne1` varchar(255) DEFAULT NULL,
  `ligne2` varchar(255) DEFAULT NULL,
  `ligne3` varchar(255) DEFAULT NULL,
  `lien` varchar(225) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

--
-- Déchargement des données de la table `publicites`
--

INSERT INTO `publicites` (`idPub`, `libellePub`, `ligne1`, `ligne2`, `ligne3`, `lien`) VALUES
(1, 'erzsg', 'ZQESGDFs', 'szegqrGDEZSQ', 'ZSGER', 'https://www.swerk.dev');

-- --------------------------------------------------------

--
-- Structure de la table `session_paiement`
--

CREATE TABLE `session_paiement` (
  `idSession` int NOT NULL,
  `hashSession` varchar(256) NOT NULL,
  `idUserSession` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

--
-- Déchargement des données de la table `session_paiement`
--

INSERT INTO `session_paiement` (`idSession`, `hashSession`, `idUserSession`) VALUES
(12, 'e1481c763d0dc13de931ef08976b8149', 3);

-- --------------------------------------------------------

--
-- Structure de la table `transporteur`
--

CREATE TABLE `transporteur` (
  `idTransporteur` int NOT NULL,
  `nomTransporteur` varchar(100) NOT NULL
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

CREATE TABLE `tutoactu` (
  `idActuTuto` int NOT NULL,
  `titreActuTuto` varchar(255) NOT NULL,
  `contenuActuTuto` longtext NOT NULL,
  `resumeActuTuto` varchar(400) NOT NULL,
  `datePublication` datetime DEFAULT NULL,
  `dateModification` datetime DEFAULT NULL,
  `imgPreview` varchar(255) NOT NULL,
  `boolActuTuto` tinyint(1) NOT NULL,
  `idCatActuTuto` int NOT NULL
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

CREATE TABLE `tva` (
  `idTva` int NOT NULL,
  `libelleTva` varchar(30) NOT NULL,
  `pourcTva` decimal(11,2) DEFAULT NULL
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

CREATE TABLE `typeoffre` (
  `idTypeOffre` int NOT NULL,
  `libelleOffre` varchar(45) NOT NULL,
  `descriptionOffre` varchar(255) DEFAULT NULL,
  `dureeOffre` varchar(45) NOT NULL,
  `prixOffre` double DEFAULT NULL,
  `nbProduit` varchar(45) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

--
-- Déchargement des données de la table `typeoffre`
--

INSERT INTO `typeoffre` (`idTypeOffre`, `libelleOffre`, `descriptionOffre`, `dureeOffre`, `prixOffre`, `nbProduit`) VALUES
(1, 'Offre Standard', 'EDGZE', '1', 4.99, '3'),
(2, 'FF', '<p>Desczefz<strong>gerg</strong></p>', '3', 33, '3'),
(3, 'EFZEF', 'fg\'gvfdg', '3', 234, '3');

-- --------------------------------------------------------

--
-- Structure de la table `user`
--

CREATE TABLE `user` (
  `idUser` int NOT NULL,
  `nomUser` varchar(45) NOT NULL,
  `prenomUser` varchar(45) NOT NULL,
  `emailUser` varchar(45) NOT NULL,
  `passwordUser` varchar(45) NOT NULL,
  `telUser` varchar(45) NOT NULL,
  `tokenInsc` varchar(255) DEFAULT NULL,
  `tokenConn` varchar(340) DEFAULT NULL,
  `newsUser` tinyint(1) NOT NULL,
  `idDroitUser` int NOT NULL,
  `idCoefPointsCredit` int NOT NULL,
  `soldePointsCredit` decimal(11,0) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

--
-- Déchargement des données de la table `user`
--

INSERT INTO `user` (`idUser`, `nomUser`, `prenomUser`, `emailUser`, `passwordUser`, `telUser`, `tokenInsc`, `tokenConn`, `newsUser`, `idDroitUser`, `idCoefPointsCredit`, `soldePointsCredit`) VALUES
(1, 'Annonceur', '1', 'anno1@gmail.com', 'Ybx7bF97B+z3L/xXwuCtqw==', '0673424323', '0', 'ZXlKMGVYQWlPaUpLVjFRaUxDSmhiR2NpT2lKSVV6STFOaUo5LmV5SnBZWFFpT2pFMk56WTNNall3T1Rjc0ltVjRjQ0k2TVRZM05qY3pNekk1Tnl3aVpHRjBZU0k2ZXlKbGJXRnBiQ0k2SW05c2FYZGxjamN5TVVCbmJXRnBiQzVqYjIwaWZYMC5GanJYSXJkNlh3NjV0UUp1Q1p3Z3otbzVydHZ3Szkzek5ELUJvZzZrbG5n', 0, 6, 1, '0'),
(2, 'Annonceur', '2', 'anno2@gmail.com', 'Ybx7bF97B+z3L/xXwuCtqw==', '042372392', '0', 'ZXlKMGVYQWlPaUpLVjFRaUxDSmhiR2NpT2lKSVV6STFOaUo5LmV5SnBZWFFpT2pFMk56WTFOalUzTlRjc0ltVjRjQ0k2TVRZM05qVTNNamsxTnl3aVpHRjBZU0k2ZXlKbGJXRnBiQ0k2SW1GdWJtOHlRR2R0WVdsc0xtTnZiU0o5ZlEuZEFzUVkzRFFXSXZsRGNRMlJ3ejJWbGFPdDZUZVVPNlExaFhUSkZlOVZxMA==', 0, 6, 1, '0'),
(3, 'Client', 'Marketplace', 'client@gmail.com', 'Ybx7bF97B+z3L/xXwuCtqw==', '443534234', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpYXQiOjE2NzY3Nzc1OTQsImV4cCI6MTY3Njc4MTE5NCwiZGF0YSI6eyJlbWFpbCI6InVuZGVmaW5lZCJ9fQ.smuX9jpQOJcGtEwxz60V4bHFoKLLZ7ALbsUvX8u2rqQ', 'ZXlKMGVYQWlPaUpLVjFRaUxDSmhiR2NpT2lKSVV6STFOaUo5LmV5SnBZWFFpT2pFMk56WTNNalV4TkRRc0ltVjRjQ0k2TVRZM05qY3pNak0wTkN3aVpHRjBZU0k2ZXlKbGJXRnBiQ0k2SW1Oc2FXVnVkRUJuYldGcGJDNWpiMjBpZlgwLjRTLXBTY2pBMFNvQ0hKUUEwcEx2TFJZNWlIaG9vZnRDREM4eUQ0dUZfU2c=', 0, 6, 1, '92'),
(4, 'Super', 'Admin', 'suadmin@gmail.com', 'Ybx7bF97B+z3L/xXwuCtqw==', '1', '0', 'ZXlKMGVYQWlPaUpLVjFRaUxDSmhiR2NpT2lKSVV6STFOaUo5LmV5SnBZWFFpT2pFMk56WTRNakkyTmpjc0ltVjRjQ0k2TVRZM05qZ3lPVGcyTnl3aVpHRjBZU0k2ZXlKbGJXRnBiQ0k2SW5OMVlXUnRhVzVBWjIxaGFXd3VZMjl0SW4xOS5PdHNSNE56N3NVaWgwajVwYVNORk5fNklkMzR3SVVVTzBNcUNrZ2ozS3BV', 0, 1, 1, '0'),
(12, 'Partenaire', 'De fou', 'swerk.pro@gmail.com', 'Ybx7bF97B+z3L/xXwuCtqw==', '0640810044', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpYXQiOjE2NzY3NzUwNTUsImV4cCI6MTY3Njc3ODY1NSwiZGF0YSI6eyJlbWFpbCI6InVuZGVmaW5lZCJ9fQ.JWcpnOZhRtfsMGbo8ac5s8oAth8fvpdEpuBMJzfdCKM', NULL, 0, 4, 1, '0'),
(13, 'zsedfgdf', 'zsedf', 'oliwer721@gmail.com', 'Motdepasse123!', '0640810044', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpYXQiOjE2NzY3NzUwNjQsImV4cCI6MTY3Njc3ODY2NCwiZGF0YSI6eyJlbWFpbCI6InVuZGVmaW5lZCJ9fQ.u2M1dn50c6yiZDuvh4OeGLOw-GYfeCfZNIfST9GsmRw', 'ZXlKMGVYQWlPaUpLVjFRaUxDSmhiR2NpT2lKSVV6STFOaUo5LmV5SnBZWFFpT2pFMk56WTNNall3T1Rjc0ltVjRjQ0k2TVRZM05qY3pNekk1Tnl3aVpHRjBZU0k2ZXlKbGJXRnBiQ0k2SW05c2FYZGxjamN5TVVCbmJXRnBiQzVqYjIwaWZYMC5GanJYSXJkNlh3NjV0UUp1Q1p3Z3otbzVydHZ3Szkzek5ELUJvZzZrbG5n', 1, 6, 1, '0'),
(14, 'fsdqhgb', 'dsergE', 'swerk200480@gmail.com', '1KOgt3TQe/H6FAGCFX+acA==', '0640810044', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpYXQiOjE2NzYyODY1NDUsImV4cCI6MTY3NjI5MDE0NSwiZGF0YSI6eyJlbWFpbCI6InN3ZXJrMjAwNDgwQGdtYWlsLmNvbSJ9fQ.8hBjd-FtLO3K40zAEN3naMiAKy450p1FRJnHL_CYs8g', NULL, 0, 5, 1, '0'),
(19, 'dsfrqhg', 'sdfg', 'lkjhndsfqgbng@dfrhgf.com4', 'bs8MCCt52kaRjgdo/LET6w==', '0640810044', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpYXQiOjE2NzY3NzQ4OTksImV4cCI6MTY3Njc3ODQ5OSwiZGF0YSI6eyJlbWFpbCI6InVuZGVmaW5lZCJ9fQ.oY2y42Vgb5YIaBYLEWoreuelWJn7ECKqfY7JgtRBQng', NULL, 0, 4, 1, '0'),
(21, 'fsdfds', 'sfdfdsfds', 'fdnsj@jfdlk.fr', 'X5yT1z0Cy/faaxew/UC0Ww==', '0102030405', '0', NULL, 0, 1, 1, '0'),
(22, 'sdffds', 'fsdfsd', 'fdsfsd@fhdsj.fr', 'hY97nSUIAIfoPw6oSwI9H+vNp5u0GAlP8pgmCsLrJVg=', '0203040506', '0', NULL, 0, 2, 1, '0');

-- --------------------------------------------------------

--
-- Structure de la table `views`
--

CREATE TABLE `views` (
  `hash` varchar(100) NOT NULL,
  `idProduitView` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

--
-- Déchargement des données de la table `views`
--

INSERT INTO `views` (`hash`, `idProduitView`) VALUES
('08b80a6304516e6f5ad749ad9287eb44e9c0afca340eb9bc2f97967e485f5d51', 28),
('7d1ee29c676777396088aa9107e03b152da8248555443de8ec0603d0de2432a7', 28),
('a39636a4dbf7772677416f5cde94c51323e3a0acebc3ff4fc3420927b007b6f2', 28),
('f9aace1b6712836bdac99b9741894904bb3aeb9ef98c17b2f6bd1c86cd032ef6', 28),
('08b80a6304516e6f5ad749ad9287eb44e9c0afca340eb9bc2f97967e485f5d51', 29),
('7b6d0ef3ab7d34f6af3610758dfd6539cc634b7a67d3b6ae5c53c30c1acccd55', 29),
('7d1ee29c676777396088aa9107e03b152da8248555443de8ec0603d0de2432a7', 29),
('a39636a4dbf7772677416f5cde94c51323e3a0acebc3ff4fc3420927b007b6f2', 29),
('b7b9dfbdf36329d03798420ce9b7392cca80b6c33add8e48689938fa9184564c', 29),
('f9aace1b6712836bdac99b9741894904bb3aeb9ef98c17b2f6bd1c86cd032ef6', 29),
('08b80a6304516e6f5ad749ad9287eb44e9c0afca340eb9bc2f97967e485f5d51', 30),
('b7b9dfbdf36329d03798420ce9b7392cca80b6c33add8e48689938fa9184564c', 30),
('08b80a6304516e6f5ad749ad9287eb44e9c0afca340eb9bc2f97967e485f5d51', 31),
('7b6d0ef3ab7d34f6af3610758dfd6539cc634b7a67d3b6ae5c53c30c1acccd55', 31),
('a39636a4dbf7772677416f5cde94c51323e3a0acebc3ff4fc3420927b007b6f2', 31),
('b7b9dfbdf36329d03798420ce9b7392cca80b6c33add8e48689938fa9184564c', 31),
('f9aace1b6712836bdac99b9741894904bb3aeb9ef98c17b2f6bd1c86cd032ef6', 31);

-- --------------------------------------------------------

--
-- Structure de la table `wishlist`
--

CREATE TABLE `wishlist` (
  `idUserWishList` int NOT NULL,
  `idProduitWishList` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

--
-- Déchargement des données de la table `wishlist`
--

INSERT INTO `wishlist` (`idUserWishList`, `idProduitWishList`) VALUES
(1, 28),
(1, 29),
(4, 29),
(1, 31);

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `actualites`
--
ALTER TABLE `actualites`
  ADD PRIMARY KEY (`idActualite`);

--
-- Index pour la table `annonce`
--
ALTER TABLE `annonce`
  ADD PRIMARY KEY (`idAnnonceProduit`,`idAnnonceAnnoFacture`),
  ADD KEY `fk_Fournisseurs_has_Produits_Produits1_idx` (`idAnnonceProduit`),
  ADD KEY `fk_Annonce_AnnonceFacture1_idx` (`idAnnonceAnnoFacture`);

--
-- Index pour la table `annoncefacture`
--
ALTER TABLE `annoncefacture`
  ADD PRIMARY KEY (`idAnnonceFacture`),
  ADD KEY `fk_AnnonceFacture_Facturations1_idx` (`idAdresseAnnoFacture`),
  ADD KEY `fk_AnnonceFacture_Annonceurs1_idx` (`idAnnonceurAnnoFacture`);

--
-- Index pour la table `annonceurs`
--
ALTER TABLE `annonceurs`
  ADD PRIMARY KEY (`idAnnonceur`),
  ADD KEY `fk_Fournisseur_User1_idx` (`idAnnonceur`),
  ADD KEY `annonceurs` (`idEtat`);

--
-- Index pour la table `avis`
--
ALTER TABLE `avis`
  ADD PRIMARY KEY (`idUserAvis`,`idProduitAvis`) USING BTREE,
  ADD KEY `fk_Satisfaction_Produits1_idx` (`idProduitAvis`),
  ADD KEY `fk_Satisfaction_User1_idx` (`idUserAvis`);

--
-- Index pour la table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`idCategorie`),
  ADD KEY `fk_Categories_Gammes1_idx` (`idGammeCategorie`);

--
-- Index pour la table `cattutoactu`
--
ALTER TABLE `cattutoactu`
  ADD PRIMARY KEY (`idCategorie`);

--
-- Index pour la table `coefpointscredit`
--
ALTER TABLE `coefpointscredit`
  ADD PRIMARY KEY (`idCoef`);

--
-- Index pour la table `commandefacture`
--
ALTER TABLE `commandefacture`
  ADD PRIMARY KEY (`numFacture`),
  ADD KEY `fk_Commande_Livraisons1_idx` (`idLivraisonFacture`),
  ADD KEY `fk_Commande_Facturations1_idx` (`idFacturationFacture`),
  ADD KEY `fk_Commande_User1_idx` (`idUserFacture`),
  ADD KEY `fk_CommandeFacture_EtatCommande1_idx` (`idEtatCommandeFacture`);

--
-- Index pour la table `commander`
--
ALTER TABLE `commander`
  ADD PRIMARY KEY (`numFactComm`,`idProduitComm`,`idUserComm`) USING BTREE,
  ADD KEY `fk_Commande_has_Produits_Produits1_idx` (`idProduitComm`),
  ADD KEY `fk_Commande_has_Produits_Commande1_idx` (`numFactComm`),
  ADD KEY `FK_idUserComm` (`idUserComm`);

--
-- Index pour la table `concerner`
--
ALTER TABLE `concerner`
  ADD PRIMARY KEY (`idAnnonceFactureConcerne`,`idTypeOffreConcerne`),
  ADD KEY `fk_AnnonceFacture_has_typeOffre_typeOffre1_idx` (`idTypeOffreConcerne`),
  ADD KEY `fk_AnnonceFacture_has_typeOffre_AnnonceFacture1_idx` (`idAnnonceFactureConcerne`);

--
-- Index pour la table `continent`
--
ALTER TABLE `continent`
  ADD PRIMARY KEY (`idContinent`);

--
-- Index pour la table `droits`
--
ALTER TABLE `droits`
  ADD PRIMARY KEY (`idDroit`);

--
-- Index pour la table `etatannonce`
--
ALTER TABLE `etatannonce`
  ADD PRIMARY KEY (`idEtatAnnonce`);

--
-- Index pour la table `etatcommande`
--
ALTER TABLE `etatcommande`
  ADD PRIMARY KEY (`idEtatCommande`);

--
-- Index pour la table `etatcompte`
--
ALTER TABLE `etatcompte`
  ADD PRIMARY KEY (`idEtatCompte`);

--
-- Index pour la table `facturations`
--
ALTER TABLE `facturations`
  ADD PRIMARY KEY (`idAdresse`),
  ADD KEY `fk_table1_Facturation1_idx` (`idUserAdresse`);

--
-- Index pour la table `fraisdeport`
--
ALTER TABLE `fraisdeport`
  ADD PRIMARY KEY (`idTransporteurFdp`,`idContinentFdp`,`poids`),
  ADD KEY `fkContinentFdp` (`idContinentFdp`),
  ADD KEY `fkTransporteurFdp` (`idTransporteurFdp`);

--
-- Index pour la table `gammes`
--
ALTER TABLE `gammes`
  ADD PRIMARY KEY (`idGamme`);

--
-- Index pour la table `historique`
--
ALTER TABLE `historique`
  ADD PRIMARY KEY (`idAnnonceHist`);

--
-- Index pour la table `image`
--
ALTER TABLE `image`
  ADD PRIMARY KEY (`idImage`),
  ADD KEY `fk_image_Produits1_idx` (`idProduitImage`);

--
-- Index pour la table `imagepub`
--
ALTER TABLE `imagepub`
  ADD PRIMARY KEY (`idImagePub`),
  ADD KEY `idPubliciteImage` (`idPubliciteImage`) USING BTREE;

--
-- Index pour la table `imgtutoactu`
--
ALTER TABLE `imgtutoactu`
  ADD PRIMARY KEY (`idImg`),
  ADD KEY `fk_idTutoActuImg_has_idTutoActu` (`idTutoActuImg`);

--
-- Index pour la table `information`
--
ALTER TABLE `information`
  ADD PRIMARY KEY (`idInformation`);

--
-- Index pour la table `livraisons`
--
ALTER TABLE `livraisons`
  ADD PRIMARY KEY (`idAdresse`),
  ADD KEY `fk_table1_Facturation1_idx` (`idUserAdresse`),
  ADD KEY `FK_CODE_PAYS` (`codePaysAdresse`);

--
-- Index pour la table `pagesconnexes`
--
ALTER TABLE `pagesconnexes`
  ADD PRIMARY KEY (`idConnexe`);

--
-- Index pour la table `panier`
--
ALTER TABLE `panier`
  ADD PRIMARY KEY (`idPanier`);

--
-- Index pour la table `partenaires`
--
ALTER TABLE `partenaires`
  ADD PRIMARY KEY (`idPartenaire`);

--
-- Index pour la table `pays`
--
ALTER TABLE `pays`
  ADD PRIMARY KEY (`codePays`),
  ADD KEY `fk2` (`idContinentPays`);

--
-- Index pour la table `produits`
--
ALTER TABLE `produits`
  ADD PRIMARY KEY (`idProduit`),
  ADD KEY `fk_Produits_Fournisseurs1_idx` (`idAnnonceurProduit`),
  ADD KEY `fk_Produits_Categories1_idx` (`idCategorieProduit`),
  ADD KEY `produits` (`idTvaProduit`);

--
-- Index pour la table `publicites`
--
ALTER TABLE `publicites`
  ADD PRIMARY KEY (`idPub`);

--
-- Index pour la table `session_paiement`
--
ALTER TABLE `session_paiement`
  ADD PRIMARY KEY (`idSession`),
  ADD KEY `FK_User_id` (`idUserSession`);

--
-- Index pour la table `transporteur`
--
ALTER TABLE `transporteur`
  ADD PRIMARY KEY (`idTransporteur`);

--
-- Index pour la table `tutoactu`
--
ALTER TABLE `tutoactu`
  ADD PRIMARY KEY (`idActuTuto`),
  ADD KEY `fk_idcategorie` (`idCatActuTuto`);

--
-- Index pour la table `tva`
--
ALTER TABLE `tva`
  ADD PRIMARY KEY (`idTva`);

--
-- Index pour la table `typeoffre`
--
ALTER TABLE `typeoffre`
  ADD PRIMARY KEY (`idTypeOffre`);

--
-- Index pour la table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`idUser`),
  ADD KEY `fk_utilisateurs_Droits_idx` (`idDroitUser`),
  ADD KEY `FK_idCoefPoints` (`idCoefPointsCredit`);

--
-- Index pour la table `views`
--
ALTER TABLE `views`
  ADD PRIMARY KEY (`hash`,`idProduitView`) USING BTREE,
  ADD KEY `fk_views_Produits1_idx` (`idProduitView`);

--
-- Index pour la table `wishlist`
--
ALTER TABLE `wishlist`
  ADD PRIMARY KEY (`idUserWishList`,`idProduitWishList`),
  ADD KEY `FK2` (`idProduitWishList`),
  ADD KEY `FK1` (`idUserWishList`) USING BTREE;

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `actualites`
--
ALTER TABLE `actualites`
  MODIFY `idActualite` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `categories`
--
ALTER TABLE `categories`
  MODIFY `idCategorie` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT pour la table `cattutoactu`
--
ALTER TABLE `cattutoactu`
  MODIFY `idCategorie` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT pour la table `coefpointscredit`
--
ALTER TABLE `coefpointscredit`
  MODIFY `idCoef` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT pour la table `continent`
--
ALTER TABLE `continent`
  MODIFY `idContinent` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT pour la table `droits`
--
ALTER TABLE `droits`
  MODIFY `idDroit` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT pour la table `etatannonce`
--
ALTER TABLE `etatannonce`
  MODIFY `idEtatAnnonce` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT pour la table `etatcommande`
--
ALTER TABLE `etatcommande`
  MODIFY `idEtatCommande` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT pour la table `etatcompte`
--
ALTER TABLE `etatcompte`
  MODIFY `idEtatCompte` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT pour la table `facturations`
--
ALTER TABLE `facturations`
  MODIFY `idAdresse` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT pour la table `gammes`
--
ALTER TABLE `gammes`
  MODIFY `idGamme` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT pour la table `historique`
--
ALTER TABLE `historique`
  MODIFY `idAnnonceHist` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `image`
--
ALTER TABLE `image`
  MODIFY `idImage` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- AUTO_INCREMENT pour la table `imagepub`
--
ALTER TABLE `imagepub`
  MODIFY `idImagePub` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT pour la table `imgtutoactu`
--
ALTER TABLE `imgtutoactu`
  MODIFY `idImg` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `information`
--
ALTER TABLE `information`
  MODIFY `idInformation` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `livraisons`
--
ALTER TABLE `livraisons`
  MODIFY `idAdresse` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT pour la table `pagesconnexes`
--
ALTER TABLE `pagesconnexes`
  MODIFY `idConnexe` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT pour la table `panier`
--
ALTER TABLE `panier`
  MODIFY `idPanier` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT pour la table `partenaires`
--
ALTER TABLE `partenaires`
  MODIFY `idPartenaire` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT pour la table `produits`
--
ALTER TABLE `produits`
  MODIFY `idProduit` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- AUTO_INCREMENT pour la table `publicites`
--
ALTER TABLE `publicites`
  MODIFY `idPub` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT pour la table `session_paiement`
--
ALTER TABLE `session_paiement`
  MODIFY `idSession` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT pour la table `transporteur`
--
ALTER TABLE `transporteur`
  MODIFY `idTransporteur` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT pour la table `tutoactu`
--
ALTER TABLE `tutoactu`
  MODIFY `idActuTuto` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT pour la table `tva`
--
ALTER TABLE `tva`
  MODIFY `idTva` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT pour la table `typeoffre`
--
ALTER TABLE `typeoffre`
  MODIFY `idTypeOffre` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT pour la table `user`
--
ALTER TABLE `user`
  MODIFY `idUser` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `annonce`
--
ALTER TABLE `annonce`
  ADD CONSTRAINT `fk_Annonce_AnnonceFacture1` FOREIGN KEY (`idAnnonceAnnoFacture`) REFERENCES `annoncefacture` (`idAnnonceFacture`),
  ADD CONSTRAINT `fk_Fournisseurs_has_Produits_Produits1` FOREIGN KEY (`idAnnonceProduit`) REFERENCES `produits` (`idProduit`);

--
-- Contraintes pour la table `annoncefacture`
--
ALTER TABLE `annoncefacture`
  ADD CONSTRAINT `fk_AnnonceFacture_Annonceurs1` FOREIGN KEY (`idAnnonceurAnnoFacture`) REFERENCES `annonceurs` (`idAnnonceur`),
  ADD CONSTRAINT `fk_AnnonceFacture_Facturations1` FOREIGN KEY (`idAdresseAnnoFacture`) REFERENCES `facturations` (`idAdresse`);

--
-- Contraintes pour la table `annonceurs`
--
ALTER TABLE `annonceurs`
  ADD CONSTRAINT `annonceurs` FOREIGN KEY (`idEtat`) REFERENCES `etatcompte` (`idEtatCompte`),
  ADD CONSTRAINT `fk_Fournisseur_User1` FOREIGN KEY (`idAnnonceur`) REFERENCES `user` (`idUser`);

--
-- Contraintes pour la table `avis`
--
ALTER TABLE `avis`
  ADD CONSTRAINT `fk_Satisfaction_Produits1` FOREIGN KEY (`idProduitAvis`) REFERENCES `produits` (`idProduit`),
  ADD CONSTRAINT `fk_Satisfaction_User1` FOREIGN KEY (`idUserAvis`) REFERENCES `user` (`idUser`);

--
-- Contraintes pour la table `categories`
--
ALTER TABLE `categories`
  ADD CONSTRAINT `fk_Categories_Gammes1` FOREIGN KEY (`idGammeCategorie`) REFERENCES `gammes` (`idGamme`);

--
-- Contraintes pour la table `commandefacture`
--
ALTER TABLE `commandefacture`
  ADD CONSTRAINT `fk_Commande_Facturations1` FOREIGN KEY (`idFacturationFacture`) REFERENCES `facturations` (`idAdresse`),
  ADD CONSTRAINT `fk_Commande_Livraisons1` FOREIGN KEY (`idLivraisonFacture`) REFERENCES `livraisons` (`idAdresse`),
  ADD CONSTRAINT `fk_Commande_User1` FOREIGN KEY (`idUserFacture`) REFERENCES `user` (`idUser`),
  ADD CONSTRAINT `fk_CommandeFacture_EtatCommande1` FOREIGN KEY (`idEtatCommandeFacture`) REFERENCES `etatcommande` (`idEtatCommande`);

--
-- Contraintes pour la table `commander`
--
ALTER TABLE `commander`
  ADD CONSTRAINT `fk_Commande_has_Produits_Commande1` FOREIGN KEY (`numFactComm`) REFERENCES `commandefacture` (`numFacture`),
  ADD CONSTRAINT `fk_Commande_has_Produits_Produits1` FOREIGN KEY (`idProduitComm`) REFERENCES `produits` (`idProduit`),
  ADD CONSTRAINT `FK_idUserComm` FOREIGN KEY (`idUserComm`) REFERENCES `user` (`idUser`);

--
-- Contraintes pour la table `concerner`
--
ALTER TABLE `concerner`
  ADD CONSTRAINT `fk_AnnonceFacture_has_typeOffre_AnnonceFacture1` FOREIGN KEY (`idAnnonceFactureConcerne`) REFERENCES `annoncefacture` (`idAnnonceFacture`),
  ADD CONSTRAINT `fk_AnnonceFacture_has_typeOffre_typeOffre1` FOREIGN KEY (`idTypeOffreConcerne`) REFERENCES `typeoffre` (`idTypeOffre`);

--
-- Contraintes pour la table `facturations`
--
ALTER TABLE `facturations`
  ADD CONSTRAINT `fk_table1_Facturation10` FOREIGN KEY (`idUserAdresse`) REFERENCES `user` (`idUser`);

--
-- Contraintes pour la table `fraisdeport`
--
ALTER TABLE `fraisdeport`
  ADD CONSTRAINT `fkContinentFdp` FOREIGN KEY (`idContinentFdp`) REFERENCES `continent` (`idContinent`),
  ADD CONSTRAINT `fkTransporteurFdp` FOREIGN KEY (`idTransporteurFdp`) REFERENCES `transporteur` (`idTransporteur`);

--
-- Contraintes pour la table `image`
--
ALTER TABLE `image`
  ADD CONSTRAINT `fk_image_Produits1` FOREIGN KEY (`idProduitImage`) REFERENCES `produits` (`idProduit`);

--
-- Contraintes pour la table `imgtutoactu`
--
ALTER TABLE `imgtutoactu`
  ADD CONSTRAINT `fk_idTutoActuImg_has_idTutoActu` FOREIGN KEY (`idTutoActuImg`) REFERENCES `tutoactu` (`idActuTuto`);

--
-- Contraintes pour la table `livraisons`
--
ALTER TABLE `livraisons`
  ADD CONSTRAINT `FK_CODE_PAYS` FOREIGN KEY (`codePaysAdresse`) REFERENCES `pays` (`codePays`),
  ADD CONSTRAINT `fk_table1_Facturation1` FOREIGN KEY (`idUserAdresse`) REFERENCES `user` (`idUser`);

--
-- Contraintes pour la table `partenaires`
--
ALTER TABLE `partenaires`
  ADD CONSTRAINT `parternaire` FOREIGN KEY (`idPartenaire`) REFERENCES `user` (`idUser`);

--
-- Contraintes pour la table `pays`
--
ALTER TABLE `pays`
  ADD CONSTRAINT `fk2` FOREIGN KEY (`idContinentPays`) REFERENCES `continent` (`idContinent`);

--
-- Contraintes pour la table `produits`
--
ALTER TABLE `produits`
  ADD CONSTRAINT `fk_Produits_Categories1` FOREIGN KEY (`idCategorieProduit`) REFERENCES `categories` (`idCategorie`),
  ADD CONSTRAINT `fk_Produits_Fournisseurs1` FOREIGN KEY (`idAnnonceurProduit`) REFERENCES `annonceurs` (`idAnnonceur`),
  ADD CONSTRAINT `produits` FOREIGN KEY (`idTvaProduit`) REFERENCES `tva` (`idTva`);

--
-- Contraintes pour la table `session_paiement`
--
ALTER TABLE `session_paiement`
  ADD CONSTRAINT `FK_User_id` FOREIGN KEY (`idUserSession`) REFERENCES `user` (`idUser`);

--
-- Contraintes pour la table `tutoactu`
--
ALTER TABLE `tutoactu`
  ADD CONSTRAINT `fk_idcategorie` FOREIGN KEY (`idCatActuTuto`) REFERENCES `cattutoactu` (`idCategorie`);

--
-- Contraintes pour la table `user`
--
ALTER TABLE `user`
  ADD CONSTRAINT `FK_idCoefPoints` FOREIGN KEY (`idCoefPointsCredit`) REFERENCES `coefpointscredit` (`idCoef`),
  ADD CONSTRAINT `fk_utilisateurs_Droits` FOREIGN KEY (`idDroitUser`) REFERENCES `droits` (`idDroit`);

--
-- Contraintes pour la table `views`
--
ALTER TABLE `views`
  ADD CONSTRAINT `fk_views_Produits1` FOREIGN KEY (`idProduitView`) REFERENCES `produits` (`idProduit`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
