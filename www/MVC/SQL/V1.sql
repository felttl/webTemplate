-- @autor felix TTL

-- base de données par défaut

DROP DATABASE IF EXISTS `dbWebTemplateName`;
CREATE DATABASE `dbWebTemplateName`;
USE `dbWebTemplateName`;

-- /////////// tables ///////////

-- Personnes

CREATE TABLE `personnes` (
  `id` int(11) AUTO_INCREMENT NOT NULL PRIMARY KEY,
  `nom` varchar(20) DEFAULT NULL,
  `prenom` varchar(20) DEFAULT NULL,
  `mail` varchar(30) NOT NULL,
  `mdp` varchar(100) NOT NULL,
  `statut` varchar(30) NOT NULL,
  `dateInscription` date NOT NULL,
  `dateNaissance` date NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- test valeurs de bases

-- attention : faire des tests avec les champs nulls car si on est obligé d'avoir toutes les informations
-- la page de connexion ne sera pas la même que pour la page de creation de compte et ça peut créer
-- des problèmes de selection si tous les champs doivent être saisis

INSERT INTO `personnes` (`nom`, `prenom`, `mail`, `mdp`, `statut`, `dateInscription`, `dateNaissance`) VALUES
("etud", "etud", "etud", "etud", 1, NOW(),STR_TO_DATE("1/1/1967 04:59:16", "%d/%m/%Y %H:%i:%s")), 
("ens", "ens", "ens", "ens", 2, NOW(),STR_TO_DATE("4/10/1987 16:09:11", "%d/%m/%Y %H:%i:%s"));