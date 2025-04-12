-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le : sam. 12 avr. 2025 à 12:20
-- Version du serveur : 9.0.1
-- Version de PHP : 8.1.31

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `bdbdesidoine`
--

DELIMITER $$
--
-- Procédures
--
DROP PROCEDURE IF EXISTS `procLogin`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `procLogin` (`p_nom` VARCHAR(50), `p_pren` VARCHAR(50), `p_passwd` VARCHAR(300))   BEGIN
  declare response varchar(50) default "ERROR";
  declare tmpKey   varchar(300) default "null";
  declare code     int(1) default -1;
    
  declare count_id int default 0;
  declare res_identifier VARCHAR(100) default null;
  declare res_passwd     VARCHAR(300) default null;
  declare res_email      VARCHAR(150) default null;
  
  SET p_passwd := SHA(concat(p_passwd, "bdesid_service"));
  
  SELECT COUNT(`nom`) INTO count_id FROM `membre` WHERE `nom` = p_nom AND `prenom` = p_pren;
  
  if count_id = 1 then
    -- SELECT `name`, `passwd`, `email` INTO res_identifier, res_passwd, res_email FROM `admin` WHERE `name` = p_id AND `passwd` = p_passwd;
    select c.idC, c.mdp, m.mail INTO res_identifier, res_passwd, res_email FROM membre m inner join compte c on c.idM = m.idM WHERE m.`nom` = p_nom AND m.`prenom` = p_pren AND c.mdp = p_passwd;
    -- select p_id, p_passwd, res_identifier, res_passwd, res_email;
    
    if res_passwd = p_passwd then
      SET response := "SUCCEFUL";
      SET code     := 0;
      SET tmpKey   := SHA(concat(res_passwd, NOW(), res_email));
            
      UPDATE `compte` SET `tmpKey` = tmpKey WHERE `idC` = res_identifier AND `mdp` = res_passwd;
    else
      SET response := CONCAT(response, ": incorrect password.");
      SET code     := 2;
    end if;
  else
    SET response := CONCAT(response, ": incorrect identifier.");
    SET code     := 1;
  end if;
  
  SELECT response as 'response', code as 'code', tmpKey as 'tmpKey', p_passwd as 'pw';
END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Structure de la table `annee`
--

DROP TABLE IF EXISTS `annee`;
CREATE TABLE IF NOT EXISTS `annee` (
  `idA` date NOT NULL,
  `libelle` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`idA`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `annee`
--

INSERT INTO `annee` (`idA`, `libelle`) VALUES
('2024-01-01', '2024'),
('2025-01-01', '2025');

-- --------------------------------------------------------

--
-- Structure de la table `articles`
--

DROP TABLE IF EXISTS `articles`;
CREATE TABLE IF NOT EXISTS `articles` (
  `id` int NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `auteur` varchar(255) NOT NULL,
  `date` datetime NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `article_image`
--

DROP TABLE IF EXISTS `article_image`;
CREATE TABLE IF NOT EXISTS `article_image` (
  `id` int NOT NULL AUTO_INCREMENT,
  `article_id` int NOT NULL,
  `filename` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `article_id` (`article_id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `compte`
--

DROP TABLE IF EXISTS `compte`;
CREATE TABLE IF NOT EXISTS `compte` (
  `idC` int NOT NULL,
  `mdp` varchar(150) DEFAULT NULL,
  `tmpkey` varchar(300) DEFAULT '',
  `idM` int NOT NULL,
  PRIMARY KEY (`idC`),
  UNIQUE KEY `idM` (`idM`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `compte`
--

INSERT INTO `compte` (`idC`, `mdp`, `tmpkey`, `idM`) VALUES
(1, '906b87053567bb4a8a4038832323c5bf4dccf881', '', 1),
(2, 'hashed_password2', '', 2);

-- --------------------------------------------------------

--
-- Structure de la table `contient`
--

DROP TABLE IF EXISTS `contient`;
CREATE TABLE IF NOT EXISTS `contient` (
  `idRo` int NOT NULL,
  `idR` int NOT NULL,
  PRIMARY KEY (`idRo`,`idR`),
  KEY `idR` (`idR`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `contient`
--

INSERT INTO `contient` (`idRo`, `idR`) VALUES
(1, 1),
(2, 1),
(1, 2);

-- --------------------------------------------------------

--
-- Structure de la table `event`
--

DROP TABLE IF EXISTS `event`;
CREATE TABLE IF NOT EXISTS `event` (
  `idE` int NOT NULL,
  `titre` varchar(150) DEFAULT NULL,
  `phrase` varchar(250) DEFAULT NULL,
  `debut` datetime DEFAULT NULL,
  `img` varchar(254) DEFAULT NULL,
  `type` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`idE`),
  KEY `type` (`type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `event`
--

INSERT INTO `event` (`idE`, `titre`, `phrase`, `debut`, `img`, `type`) VALUES
(1, 'Fête Annuelle', 'Grande soirée étudiante', '2025-05-01 20:00:00', 'fete.jpg', 'event'),
(2, 'Réunion Mensuelle', 'Réunion BDE', '2025-04-15 18:00:00', 'reunion.jpg', 'reunion');

-- --------------------------------------------------------

--
-- Structure de la table `image`
--

DROP TABLE IF EXISTS `image`;
CREATE TABLE IF NOT EXISTS `image` (
  `idI` int NOT NULL AUTO_INCREMENT,
  `idP` int NOT NULL,
  `path` text,
  PRIMARY KEY (`idI`,`idP`),
  KEY `idP` (`idP`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `image`
--

INSERT INTO `image` (`idI`, `idP`, `path`) VALUES
(1, 1, 'Photo_groupe.jpg'),
(2, 1, 'Photo_accueil.jpg');

-- --------------------------------------------------------

--
-- Structure de la table `membre`
--

DROP TABLE IF EXISTS `membre`;
CREATE TABLE IF NOT EXISTS `membre` (
  `idM` int NOT NULL AUTO_INCREMENT,
  `nom` varchar(50) DEFAULT NULL,
  `prenom` varchar(50) DEFAULT NULL,
  `mail` varchar(150) DEFAULT NULL,
  `tel` varchar(15) DEFAULT NULL,
  `idRo` int NOT NULL,
  `annee` varchar(9) NOT NULL,
  `photo` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`idM`),
  KEY `idRo` (`idRo`)
) ENGINE=InnoDB AUTO_INCREMENT=72 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `membre`
--

INSERT INTO `membre` (`idM`, `nom`, `prenom`, `mail`, `tel`, `idRo`, `annee`, `photo`) VALUES
(1, 'enzo', 'boitte', 'jean.dupont@mail.com', '3', 1, '2024-2025', 'default.png'),
(2, 'Martin', 'Alice', 'alice.martin@mail.com', '4', 2, '2024-2025', 'default.png'),
(55, 'PLATTEAU', 'Emy', 'emy.platteau@etudiant.fr', '0612345678', 1, '2024-2025', 'default.png'),
(56, 'BOITTE', 'Enzo', 'enzo.boitte@etudiant.fr', '0611121314', 2, '2024-2025', 'default.png'),
(57, 'BOITTE', 'Enzo', 'enzo.admin@etudiant.fr', '0611121314', 15, '2024-2025', 'default.png'),
(58, 'BELLIARD', 'Malou', 'malou.belliard@etudiant.fr', '0622233445', 4, '2024-2025', 'default.png'),
(59, 'SAULUT', 'Caroline', 'caroline.saulut@etudiant.fr', '0666677788', 9, '2024-2025', 'default.png'),
(60, 'TASKIN', 'Selim', 'selim.taskin@etudiant.fr', '0601020304', 15, '2024-2025', 'default.png'),
(61, 'TASKIN', 'Selim', 'selim.com@etudiant.fr', '0601020304', 12, '2024-2025', 'default.png'),
(62, 'TASKIN', 'Selim', 'selim.event@etudiant.fr', '0601020304', 14, '2024-2025', 'default.png'),
(63, 'BUISSON', 'Mathis', 'mathis.buisson@etudiant.fr', '0645464646', 15, '2024-2025', 'default.png'),
(64, 'BUISSON', 'Mathis', 'mathis.respcom@etudiant.fr', '0645464646', 5, '2024-2025', 'default.png'),
(65, 'BAETER', 'Mina', 'mina.baeter@etudiant.fr', '0633344556', 15, '2024-2025', 'default.png'),
(66, 'BOUYII', 'Amine', 'amine.bouyii@etudiant.fr', '0688990011', 8, '2024-2025', 'default.png'),
(67, 'RIVIERE', 'Lucas', 'lucas.riviere@etudiant.fr', '0601010101', 12, '2024-2025', 'default.png'),
(68, 'DURAND', 'Sophie', 'sophie.durand@etudiant.fr', '0602020202', 12, '2024-2025', 'default.png'),
(69, 'PETIT', 'Julien', 'julien.petit@etudiant.fr', '0603030303', 12, '2024-2025', 'default.png'),
(70, 'MOREAU', 'Léa', 'lea.moreau@etudiant.fr', '0604040404', 12, '2024-2025', 'default.png'),
(71, 'GERARD', 'Thomas', 'thomas.gerard@etudiant.fr', '0605050505', 12, '2024-2025', 'default.png');

-- --------------------------------------------------------

--
-- Structure de la table `nommer`
--

DROP TABLE IF EXISTS `nommer`;
CREATE TABLE IF NOT EXISTS `nommer` (
  `idM` int NOT NULL,
  `idRo` int DEFAULT NULL,
  `idA` date NOT NULL,
  PRIMARY KEY (`idM`,`idA`),
  KEY `idRo` (`idRo`),
  KEY `idA` (`idA`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `nommer`
--

INSERT INTO `nommer` (`idM`, `idRo`, `idA`) VALUES
(1, 1, '2024-01-01'),
(1, 2, '2025-01-01');

-- --------------------------------------------------------

--
-- Structure de la table `poste`
--

DROP TABLE IF EXISTS `poste`;
CREATE TABLE IF NOT EXISTS `poste` (
  `idP` int NOT NULL AUTO_INCREMENT,
  `dateP` date DEFAULT NULL,
  `titreP` varchar(50) DEFAULT NULL,
  `descriptionP` text,
  `_imageP` text,
  `idC` int NOT NULL,
  PRIMARY KEY (`idP`),
  KEY `idC` (`idC`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `poste`
--

INSERT INTO `poste` (`idP`, `dateP`, `titreP`, `descriptionP`, `_imageP`, `idC`) VALUES
(1, '2025-03-27', 'Bienvenue', 'Premier post de bienvenue', 'image_post1.jpg', 1),
(2, '2025-03-28', 'Événement à venir', 'Annonce fête annuelle', 'image_post2.jpg', 2);

-- --------------------------------------------------------

--
-- Structure de la table `regle`
--

DROP TABLE IF EXISTS `regle`;
CREATE TABLE IF NOT EXISTS `regle` (
  `idR` int NOT NULL,
  `libelle` varchar(50) DEFAULT NULL,
  `action` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`idR`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `regle`
--

INSERT INTO `regle` (`idR`, `libelle`, `action`) VALUES
(1, 'Créer un post', 'CREATE_POST'),
(2, 'Supprimer un post', 'DELETE_POST'),
(3, 'Modifier un post', 'UPDATE_POST'),
(4, 'Lire un post', 'READ_POST'),
(9, 'Créer un événement', 'CREATE_EVENT'),
(10, 'Supprimer un événement', 'DELETE_EVENT'),
(11, 'Modifier un événement', 'UPDATE_EVENT'),
(12, 'Lire un événement', 'READ_EVENT');

-- --------------------------------------------------------

--
-- Structure de la table `role`
--

DROP TABLE IF EXISTS `role`;
CREATE TABLE IF NOT EXISTS `role` (
  `idRo` int NOT NULL,
  `libelle` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`idRo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `role`
--

INSERT INTO `role` (`idRo`, `libelle`) VALUES
(1, 'Président'),
(2, 'Trésorier'),
(3, 'Vice-président'),
(4, 'Secrétaire'),
(5, 'Responsable Communication'),
(6, 'Responsable Événementiel'),
(7, 'Responsable Partenariats'),
(8, 'Vice-trésorier'),
(9, 'Vice-secrétaire'),
(10, 'Responsable Sécurité'),
(11, 'Community Manager'),
(12, 'Membre Communication'),
(13, 'Membre Sécurité'),
(14, 'Membre Événementiel'),
(15, 'Administrateur');

-- --------------------------------------------------------

--
-- Structure de la table `type_event`
--

DROP TABLE IF EXISTS `type_event`;
CREATE TABLE IF NOT EXISTS `type_event` (
  `code` varchar(50) NOT NULL,
  `libelle` varchar(150) DEFAULT NULL,
  PRIMARY KEY (`code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `type_event`
--

INSERT INTO `type_event` (`code`, `libelle`) VALUES
('event', 'Événement'),
('rdv', 'Rendez-vous'),
('reunion', 'Réunion'),
('tache', 'Tâche');

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `article_image`
--
ALTER TABLE `article_image`
  ADD CONSTRAINT `article_image_ibfk_1` FOREIGN KEY (`article_id`) REFERENCES `articles` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `compte`
--
ALTER TABLE `compte`
  ADD CONSTRAINT `compte_ibfk_1` FOREIGN KEY (`idM`) REFERENCES `membre` (`idM`);

--
-- Contraintes pour la table `contient`
--
ALTER TABLE `contient`
  ADD CONSTRAINT `contient_ibfk_1` FOREIGN KEY (`idRo`) REFERENCES `role` (`idRo`),
  ADD CONSTRAINT `contient_ibfk_2` FOREIGN KEY (`idR`) REFERENCES `regle` (`idR`);

--
-- Contraintes pour la table `event`
--
ALTER TABLE `event`
  ADD CONSTRAINT `event_ibfk_1` FOREIGN KEY (`type`) REFERENCES `type_event` (`code`);

--
-- Contraintes pour la table `image`
--
ALTER TABLE `image`
  ADD CONSTRAINT `image_ibfk_1` FOREIGN KEY (`idP`) REFERENCES `poste` (`idP`);

--
-- Contraintes pour la table `membre`
--
ALTER TABLE `membre`
  ADD CONSTRAINT `membre_ibfk_1` FOREIGN KEY (`idRo`) REFERENCES `role` (`idRo`);

--
-- Contraintes pour la table `nommer`
--
ALTER TABLE `nommer`
  ADD CONSTRAINT `nommer_ibfk_1` FOREIGN KEY (`idM`) REFERENCES `membre` (`idM`),
  ADD CONSTRAINT `nommer_ibfk_2` FOREIGN KEY (`idRo`) REFERENCES `role` (`idRo`),
  ADD CONSTRAINT `nommer_ibfk_3` FOREIGN KEY (`idA`) REFERENCES `annee` (`idA`);

--
-- Contraintes pour la table `poste`
--
ALTER TABLE `poste`
  ADD CONSTRAINT `poste_ibfk_1` FOREIGN KEY (`idC`) REFERENCES `compte` (`idC`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
