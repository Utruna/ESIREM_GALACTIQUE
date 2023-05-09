-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3307
-- Généré le : mer. 03 mai 2023 à 16:30
-- Version du serveur : 10.10.2-MariaDB
-- Version de PHP : 8.0.26

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `esirem galactique`
--

-- --------------------------------------------------------

--
-- Structure de la table `chantier_spatial`
--

DROP TABLE IF EXISTS `chantier_spatial`;
CREATE TABLE IF NOT EXISTS `chantier_spatial` (
  `idChantier` int(11) NOT NULL AUTO_INCREMENT,
  `niveau` int(11) NOT NULL,
  `production` int(11) NOT NULL,
  PRIMARY KEY (`idChantier`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Structure de la table `contrainte_recherche`
--

DROP TABLE IF EXISTS `contrainte_recherche`;
CREATE TABLE IF NOT EXISTS `contrainte_recherche` (
  `idContrainteRecherche` int(11) NOT NULL AUTO_INCREMENT,
  `idRecherche` int(11) NOT NULL,
  `idRechercheSouhaiter` int(11) NOT NULL,
  PRIMARY KEY (`idContrainteRecherche`),
  KEY `idRecherche` (`idRecherche`),
  KEY `idRechercheSouhaiter` (`idRechercheSouhaiter`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Structure de la table `cout`
--

DROP TABLE IF EXISTS `cout`;
CREATE TABLE IF NOT EXISTS `cout` (
  `idCout` int(11) NOT NULL AUTO_INCREMENT,
  `structureType` varchar(255) NOT NULL,
  `coutMetal` int(11) NOT NULL,
  `coutEnergie` int(11) NOT NULL,
  `coutDeutérium` int(11) NOT NULL,
  `augmentationParNiveau` int(11) NOT NULL,
  PRIMARY KEY (`idCout`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Structure de la table `defense`
--

DROP TABLE IF EXISTS `defense`;
CREATE TABLE IF NOT EXISTS `defense` (
  `idDefence` int(11) NOT NULL AUTO_INCREMENT,
  `type` varchar(255) NOT NULL,
  `niveau` int(11) NOT NULL,
  PRIMARY KEY (`idDefence`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Structure de la table `fil_de_construction_infrastructure`
--

DROP TABLE IF EXISTS `fil_de_construction_infrastructure`;
CREATE TABLE IF NOT EXISTS `fil_de_construction_infrastructure` (
  `idFilDeConstructionInfrastructure` int(11) NOT NULL AUTO_INCREMENT,
  `idStatu` int(11) NOT NULL,
  `idPlanete` int(11) NOT NULL,
  `niveau` int(11) NOT NULL,
  `ordreDePriorite` int(11) NOT NULL,
  `nombre` int(11) NOT NULL,
  `type` varchar(255) NOT NULL,
  PRIMARY KEY (`idFilDeConstructionInfrastructure`),
  KEY `idStatu` (`idStatu`),
  KEY `idPlanete` (`idPlanete`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Structure de la table `fil_de_construction_vaisseau`
--

DROP TABLE IF EXISTS `fil_de_construction_vaisseau`;
CREATE TABLE IF NOT EXISTS `fil_de_construction_vaisseau` (
  `idFilDeConstructionVaisseau` int(11) NOT NULL AUTO_INCREMENT,
  `idStatu` int(11) NOT NULL,
  `idPlanete` int(11) NOT NULL,
  `nombre` int(11) NOT NULL,
  `type` varchar(255) NOT NULL,
  `ordreDePriorite` int(11) NOT NULL,
  PRIMARY KEY (`idFilDeConstructionVaisseau`),
  KEY `idStatu` (`idStatu`),
  KEY `idPlanete` (`idPlanete`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Structure de la table `fil_de_recherche`
--

DROP TABLE IF EXISTS `fil_de_recherche`;
CREATE TABLE IF NOT EXISTS `fil_de_recherche` (
  `idFilDeRecherche` int(11) NOT NULL AUTO_INCREMENT,
  `idStatu` int(11) NOT NULL,
  `idPlanete` int(11) NOT NULL,
  `niveau` int(11) NOT NULL,
  `ordreDePriorite` int(11) NOT NULL,
  `type` varchar(255) NOT NULL,
  PRIMARY KEY (`idFilDeRecherche`),
  KEY `idStatu` (`idStatu`),
  KEY `idPlanete` (`idPlanete`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Structure de la table `flotte`
--

DROP TABLE IF EXISTS `flotte`;
CREATE TABLE IF NOT EXISTS `flotte` (
  `idFlotte` int(11) NOT NULL AUTO_INCREMENT,
  `idPlanete` int(11) NOT NULL,
  `numeroFlotte` int(11) NOT NULL,
  `idStatFlotte` int(11) NOT NULL,
  PRIMARY KEY (`idFlotte`),
  KEY `idPlanete` (`idPlanete`),
  KEY `fk_stat_flotte` (`idStatFlotte`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Structure de la table `galaxie`
--

DROP TABLE IF EXISTS `galaxie`;
CREATE TABLE IF NOT EXISTS `galaxie` (
  `idGalaxie` int(11) NOT NULL AUTO_INCREMENT,
  `idUnivers` int(11) NOT NULL,
  `nom` varchar(255) NOT NULL,
  `numero` int(11) NOT NULL,
  PRIMARY KEY (`idGalaxie`),
  KEY `idUnivers` (`idUnivers`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Structure de la table `joueur`
--

DROP TABLE IF EXISTS `joueur`;
CREATE TABLE IF NOT EXISTS `joueur` (
  `idJoueur` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(255) NOT NULL,
  `nom` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  PRIMARY KEY (`idJoueur`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Structure de la table `parametre_planete`
--

DROP TABLE IF EXISTS `parametre_planete`;
CREATE TABLE IF NOT EXISTS `parametre_planete` (
  `idParametre` int(11) NOT NULL AUTO_INCREMENT,
  `position` int(11) NOT NULL,
  `taille` int(11) NOT NULL,
  `bonusSolaire` int(11) NOT NULL,
  `bonusMetaliques` int(11) NOT NULL,
  `bonusAquatique` int(11) NOT NULL,
  PRIMARY KEY (`idParametre`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Structure de la table `planete`
--

DROP TABLE IF EXISTS `planete`;
CREATE TABLE IF NOT EXISTS `planete` (
  `idPlanete` int(11) NOT NULL AUTO_INCREMENT,
  `idSysteme` int(11) NOT NULL,
  `position` int(11) NOT NULL,
  `nom` varchar(255) NOT NULL,
  `idType` int(11) NOT NULL,
  `idJoueur` int(11) NOT NULL,
  PRIMARY KEY (`idPlanete`),
  KEY `idSysteme` (`idSysteme`),
  KEY `idJoueur` (`idJoueur`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Structure de la table `prod_ressource`
--

DROP TABLE IF EXISTS `prod_ressource`;
CREATE TABLE IF NOT EXISTS `prod_ressource` (
  `idProdRessource` int(11) NOT NULL AUTO_INCREMENT,
  `niveau` int(11) NOT NULL,
  `production` int(11) NOT NULL,
  PRIMARY KEY (`idProdRessource`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Structure de la table `recherche`
--

DROP TABLE IF EXISTS `recherche`;
CREATE TABLE IF NOT EXISTS `recherche` (
  `idRecherche` int(11) NOT NULL AUTO_INCREMENT,
  `typeRecherche` varchar(255) NOT NULL,
  `niveau` int(11) NOT NULL,
  PRIMARY KEY (`idRecherche`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Structure de la table `ressource`
--

DROP TABLE IF EXISTS `ressource`;
CREATE TABLE IF NOT EXISTS `ressource` (
  `idRessource` int(11) NOT NULL AUTO_INCREMENT,
  `idUnivers` int(11) NOT NULL,
  `idJoueur` int(11) NOT NULL,
  `stockMétal` int(11) NOT NULL DEFAULT 0,
  `stockEnergie` int(11) NOT NULL DEFAULT 0,
  `stockDeutérium` int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (`idRessource`),
  KEY `idUnivers` (`idUnivers`),
  KEY `idJoueur` (`idJoueur`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Structure de la table `statue_flotte`
--

DROP TABLE IF EXISTS `statue_flotte`;
CREATE TABLE IF NOT EXISTS `statue_flotte` (
  `idStatFlotte` int(11) NOT NULL AUTO_INCREMENT,
  `statutFlotte` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  PRIMARY KEY (`idStatFlotte`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Structure de la table `statu_fil`
--

DROP TABLE IF EXISTS `statu_fil`;
CREATE TABLE IF NOT EXISTS `statu_fil` (
  `idStatFil` int(11) NOT NULL AUTO_INCREMENT,
  `étatStatu` varchar(255) NOT NULL,
  PRIMARY KEY (`idStatFil`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Structure de la table `systeme_solaire`
--

DROP TABLE IF EXISTS `systeme_solaire`;
CREATE TABLE IF NOT EXISTS `systeme_solaire` (
  `idSysteme` int(11) NOT NULL AUTO_INCREMENT,
  `idGalaxie` int(11) NOT NULL,
  `nom` varchar(255) NOT NULL,
  `numero` int(11) NOT NULL,
  PRIMARY KEY (`idSysteme`),
  KEY `idGalaxie` (`idGalaxie`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Structure de la table `type_planete`
--

DROP TABLE IF EXISTS `type_planete`;
CREATE TABLE IF NOT EXISTS `type_planete` (
  `idTypePlanete` int(11) NOT NULL AUTO_INCREMENT,
  `type` varchar(255) NOT NULL,
  PRIMARY KEY (`idTypePlanete`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Structure de la table `univers`
--

DROP TABLE IF EXISTS `univers`;
CREATE TABLE IF NOT EXISTS `univers` (
  `idUnivers` int(11) NOT NULL AUTO_INCREMENT,
  `nom` varchar(255) NOT NULL,
  PRIMARY KEY (`idUnivers`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Structure de la table `vaisseau`
--

DROP TABLE IF EXISTS `vaisseau`;
CREATE TABLE IF NOT EXISTS `vaisseau` (
  `idVaisseau` int(11) NOT NULL AUTO_INCREMENT,
  `typeVaisseau` varchar(255) NOT NULL,
  `flotte` int(11) NOT NULL,
  PRIMARY KEY (`idVaisseau`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
