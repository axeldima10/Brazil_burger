-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le : ven. 18 fév. 2022 à 09:34
-- Version du serveur :  5.7.31
-- Version de PHP : 7.3.21

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `burger2`
--

-- --------------------------------------------------------

--
-- Structure de la table `boisson`
--

DROP TABLE IF EXISTS `boisson`;
CREATE TABLE IF NOT EXISTS `boisson` (
  `id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `boisson`
--

INSERT INTO `boisson` (`id`) VALUES
(2),
(3),
(4);

-- --------------------------------------------------------

--
-- Structure de la table `burger`
--

DROP TABLE IF EXISTS `burger`;
CREATE TABLE IF NOT EXISTS `burger` (
  `id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `burger`
--

INSERT INTO `burger` (`id`) VALUES
(1),
(7),
(8);

-- --------------------------------------------------------

--
-- Structure de la table `client`
--

DROP TABLE IF EXISTS `client`;
CREATE TABLE IF NOT EXISTS `client` (
  `id` int(11) NOT NULL,
  `telephone` bigint(20) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `client`
--

INSERT INTO `client` (`id`, `telephone`) VALUES
(6, 221773595596),
(8, 221773595597),
(9, 221787454142),
(10, 221787535951),
(11, 221777455665);

-- --------------------------------------------------------

--
-- Structure de la table `commande`
--

DROP TABLE IF EXISTS `commande`;
CREATE TABLE IF NOT EXISTS `commande` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `etat` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `nombre` int(11) DEFAULT NULL,
  `total` double DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `commande`
--

INSERT INTO `commande` (`id`, `etat`, `nombre`, `total`) VALUES
(2, 'validé', 2, 2000),
(3, 'validé', 1, 4500),
(4, 'archivé', 3, 6500),
(6, 'archivé', 1, 1000),
(7, 'validé', 2, 2000),
(8, 'validé', 3, 4500),
(12, 'validé', 2, 5500);

-- --------------------------------------------------------

--
-- Structure de la table `complement`
--

DROP TABLE IF EXISTS `complement`;
CREATE TABLE IF NOT EXISTS `complement` (
  `id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `complement`
--

INSERT INTO `complement` (`id`) VALUES
(9);

-- --------------------------------------------------------

--
-- Structure de la table `complement_repas`
--

DROP TABLE IF EXISTS `complement_repas`;
CREATE TABLE IF NOT EXISTS `complement_repas` (
  `complement_id` int(11) NOT NULL,
  `repas_id` int(11) NOT NULL,
  PRIMARY KEY (`complement_id`,`repas_id`),
  KEY `IDX_1B0E7D4D40D9D0AA` (`complement_id`),
  KEY `IDX_1B0E7D4D1D236AAA` (`repas_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `doctrine_migration_versions`
--

DROP TABLE IF EXISTS `doctrine_migration_versions`;
CREATE TABLE IF NOT EXISTS `doctrine_migration_versions` (
  `version` varchar(191) COLLATE utf8_unicode_ci NOT NULL,
  `executed_at` datetime DEFAULT NULL,
  `execution_time` int(11) DEFAULT NULL,
  PRIMARY KEY (`version`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Déchargement des données de la table `doctrine_migration_versions`
--

INSERT INTO `doctrine_migration_versions` (`version`, `executed_at`, `execution_time`) VALUES
('DoctrineMigrations\\Version20220127134412', '2022-01-27 13:44:24', 21333),
('DoctrineMigrations\\Version20220208022959', '2022-02-08 02:30:49', 17660);

-- --------------------------------------------------------

--
-- Structure de la table `frite`
--

DROP TABLE IF EXISTS `frite`;
CREATE TABLE IF NOT EXISTS `frite` (
  `id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `frite`
--

INSERT INTO `frite` (`id`) VALUES
(5),
(6);

-- --------------------------------------------------------

--
-- Structure de la table `gestionnaire`
--

DROP TABLE IF EXISTS `gestionnaire`;
CREATE TABLE IF NOT EXISTS `gestionnaire` (
  `id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `gestionnaire`
--

INSERT INTO `gestionnaire` (`id`) VALUES
(1),
(2),
(3),
(4),
(5);

-- --------------------------------------------------------

--
-- Structure de la table `image`
--

DROP TABLE IF EXISTS `image`;
CREATE TABLE IF NOT EXISTS `image` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `repas_id` int(11) DEFAULT NULL,
  `libelle` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_C53D045F1D236AAA` (`repas_id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `image`
--

INSERT INTO `image` (`id`, `repas_id`, `libelle`) VALUES
(1, 1, '95b7f986c21201c8644023e3263d4495.jpg'),
(2, 2, 'ab0164d6a4ed25a4199c55a161b872c4.jpg'),
(3, 3, '58462b8352ce156ef291e3e514874d20.jpg'),
(4, 4, '9ac167d198fc60fa00af73db19271894.jpg'),
(5, 5, '29c36a69d930a73c4e3173ae10d6fd35.jpg'),
(6, 6, 'b0ce14df378b3de97922c1703db4476a.jpg'),
(7, 7, '2aa839d99115c661e0a9f51d5ed2c6a9.jpg'),
(8, 8, 'f509a3402926d5cacd2128939343125a.jpg'),
(9, 9, '720ad97ca80c44872059565284e23312.jpg'),
(10, 10, 'f086d26d9826804e86aa1e32fca72493.jpg'),
(11, 11, '1a18ecf9910e4c0eb143382b580e7b96.jpg');

-- --------------------------------------------------------

--
-- Structure de la table `menu`
--

DROP TABLE IF EXISTS `menu`;
CREATE TABLE IF NOT EXISTS `menu` (
  `id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `menu`
--

INSERT INTO `menu` (`id`) VALUES
(10),
(11);

-- --------------------------------------------------------

--
-- Structure de la table `menu_repas`
--

DROP TABLE IF EXISTS `menu_repas`;
CREATE TABLE IF NOT EXISTS `menu_repas` (
  `menu_id` int(11) NOT NULL,
  `repas_id` int(11) NOT NULL,
  PRIMARY KEY (`menu_id`,`repas_id`),
  KEY `IDX_397F72D4CCD7E912` (`menu_id`),
  KEY `IDX_397F72D41D236AAA` (`repas_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `menu_repas`
--

INSERT INTO `menu_repas` (`menu_id`, `repas_id`) VALUES
(10, 1),
(10, 3),
(10, 5),
(11, 4),
(11, 5),
(11, 8);

-- --------------------------------------------------------

--
-- Structure de la table `message`
--

DROP TABLE IF EXISTS `message`;
CREATE TABLE IF NOT EXISTS `message` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nom` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `mail` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `message` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `message`
--

INSERT INTO `message` (`id`, `nom`, `mail`, `message`) VALUES
(1, 'Max', 'anonyme@gmail.com', 'j\'adore vos burgers'),
(2, 'arame', 'arame23@gmail.com', 'j\'adore');

-- --------------------------------------------------------

--
-- Structure de la table `paiement`
--

DROP TABLE IF EXISTS `paiement`;
CREATE TABLE IF NOT EXISTS `paiement` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `commande_id` int(11) DEFAULT NULL,
  `client_id` int(11) DEFAULT NULL,
  `date` datetime DEFAULT NULL,
  `montant` double NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_B1DC7A1E82EA2E54` (`commande_id`),
  KEY `IDX_B1DC7A1E19EB6921` (`client_id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `paiement`
--

INSERT INTO `paiement` (`id`, `commande_id`, `client_id`, `date`, `montant`) VALUES
(1, 2, 6, '2022-02-08 03:10:50', 2000),
(2, 3, 6, '2022-02-08 03:54:17', 4500),
(3, 7, 8, '2022-02-17 19:24:51', 2000),
(4, 8, 8, '2022-02-17 19:25:06', 4500),
(7, 12, 11, NULL, 5500);

-- --------------------------------------------------------

--
-- Structure de la table `panier`
--

DROP TABLE IF EXISTS `panier`;
CREATE TABLE IF NOT EXISTS `panier` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `commande_id` int(11) DEFAULT NULL,
  `repas_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_24CC0DF282EA2E54` (`commande_id`),
  KEY `IDX_24CC0DF21D236AAA` (`repas_id`)
) ENGINE=InnoDB AUTO_INCREMENT=26 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `panier`
--

INSERT INTO `panier` (`id`, `commande_id`, `repas_id`) VALUES
(5, 2, 2),
(6, 2, 2),
(8, 3, 1),
(10, 4, 3),
(11, 4, 1),
(12, 4, 4),
(15, 6, 4),
(16, 7, 4),
(17, 7, 6),
(18, 8, 5),
(19, 8, 8),
(20, 8, 5),
(24, 12, 1),
(25, 12, 3);

-- --------------------------------------------------------

--
-- Structure de la table `repas`
--

DROP TABLE IF EXISTS `repas`;
CREATE TABLE IF NOT EXISTS `repas` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `libelle` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `prix` double NOT NULL,
  `disponibilite` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `categorie` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `repas`
--

INSERT INTO `repas` (`id`, `libelle`, `prix`, `disponibilite`, `categorie`, `description`, `type`) VALUES
(1, 'burger salade', 4500, '1', 'BURGER', 'Pain grillé, burger de 130g, fromage, \r\noignon caramélisé, salade tomate, oignons \r\nrouge, cornichon, mayonnaise sauce et sauce spéciale', 'burger'),
(2, 'coca cola', 1000, '1', 'BOISSON', 'boisson gazeuse de 33cl', 'boisson'),
(3, 'fanta', 1000, '1', 'BOISSON', 'boisson gazeuse de 33cl', 'boisson'),
(4, 'sprite', 1000, '1', 'BOISSON', 'boisson gazeuse de 33cl', 'boisson'),
(5, 'frite simple', 500, '1', 'FRITE', 'pommes de terre émincies', 'frite'),
(6, 'potatoes', 1000, '1', 'FRITE', 'grosse tranche de pomme de terre', 'frite'),
(7, 'chicken burger', 4000, '1', 'BURGER', 'viande de poulet, fromage, sauce barbecue,\r\n pain grillé, salade, tomate', 'burger'),
(8, 'double smash', 3500, '1', 'BURGER', 'pain grillé, double viande, \r\nfromage double, oignon, ketchup', 'burger'),
(9, 'mousse au citron', 1000, '1', 'COMPLEMENT', 'dessert à la brésilienne', 'complement'),
(10, 'burger salad menu', 6000, '1', 'MENU', 'menu formé d\'un burger salade, boisson et frite', 'menu'),
(11, 'smash menu', 5000, '1', 'MENU', 'smash boisson et frite', 'menu');

-- --------------------------------------------------------

--
-- Structure de la table `user`
--

DROP TABLE IF EXISTS `user`;
CREATE TABLE IF NOT EXISTS `user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(180) COLLATE utf8mb4_unicode_ci NOT NULL,
  `roles` longtext COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '(DC2Type:json)',
  `password` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `nom_complet` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_8D93D649E7927C74` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `user`
--

INSERT INTO `user` (`id`, `email`, `roles`, `password`, `nom_complet`, `type`) VALUES
(1, 'gestionnaire@gmail.com', '[\"ROLE_GESTIONNAIRE\",\"ROLE_CLIENT\"]', '$2y$13$NE7YKKM8XyrbEH4IddRBIOxyXk.MWGHOxH/g7OVhb5/92h9uaaRcu', 'gestionnaire 1', 'gestionnaire'),
(2, 'gestionnaire2@gmail.com', '[\"ROLE_GESTIONNAIRE\",\"ROLE_CLIENT\"]', '$2y$13$pofNI5rnkKpD0v9hM/.GCOQCnMF7UJNhx.oA/AduhOlPbwhXyakYm', 'gestionnaire 2', 'gestionnaire'),
(3, 'gestionnaire3@gmail.com', '[\"ROLE_GESTIONNAIRE\",\"ROLE_CLIENT\"]', '$2y$13$LcJUpFzsyDu2ZyyOpkZSNOm80fGos2Ymaidy4xsEPyotvwXkMmLvS', 'gestionnaire 3', 'gestionnaire'),
(4, 'gestionnaire4@gmail.com', '[\"ROLE_GESTIONNAIRE\",\"ROLE_CLIENT\"]', '$2y$13$kNr86.tZev6Oh3bwSC132.Y93dmxzGxid2rvHjrkTKtIiZd1P6mAy', 'gestionnaire 4', 'gestionnaire'),
(5, 'gestionnaire5@gmail.com', '[\"ROLE_GESTIONNAIRE\",\"ROLE_CLIENT\"]', '$2y$13$Pz9hv1pVXYv6TxOglPOTf.HoKmjcGd9QxkHVcDOx1W8wT8x8xtWuW', 'gestionnaire 5', 'gestionnaire'),
(6, 'default237@gmail.com', '[\"ROLE_CLIENT\"]', NULL, 'Ivan', 'client'),
(8, 'ivan@gmail.com', '[\"ROLE_CLIENT\"]', '$2y$13$SuoS8TKbR6UFhfvP6SG7auD.htfniFrH2sndmUknmY9PJEs4FwI7e', 'Yameogo', 'client'),
(9, 'default71@gmail.com', '[\"ROLE_CLIENT\"]', '$2y$13$r92tHWxJGkV4/k4COz0cPeConFUJLH92jBU1ETHrgzN34yOYo/It.', 'Sami', 'client'),
(10, 'default160@gmail.com', '[\"ROLE_CLIENT\"]', '$2y$13$H.weVdGhDv9dzdQKShN.ZeqJPfSZJ9wxfaP0/s1DcZ3pYxeTuJTba', 'Pascal', 'client'),
(11, 'default235@gmail.com', '[\"ROLE_CLIENT\"]', '$2y$13$sF7S6nmjpz3GrhuyH9DbcegTVVNQBdDMzzri4LyqpGdkuoJ2W/RJS', 'mr baila', 'client');

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `boisson`
--
ALTER TABLE `boisson`
  ADD CONSTRAINT `FK_8B97C84DBF396750` FOREIGN KEY (`id`) REFERENCES `repas` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `burger`
--
ALTER TABLE `burger`
  ADD CONSTRAINT `FK_EFE35A0DBF396750` FOREIGN KEY (`id`) REFERENCES `repas` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `client`
--
ALTER TABLE `client`
  ADD CONSTRAINT `FK_C7440455BF396750` FOREIGN KEY (`id`) REFERENCES `user` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `complement`
--
ALTER TABLE `complement`
  ADD CONSTRAINT `FK_F8A41E34BF396750` FOREIGN KEY (`id`) REFERENCES `repas` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `complement_repas`
--
ALTER TABLE `complement_repas`
  ADD CONSTRAINT `FK_1B0E7D4D1D236AAA` FOREIGN KEY (`repas_id`) REFERENCES `repas` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `FK_1B0E7D4D40D9D0AA` FOREIGN KEY (`complement_id`) REFERENCES `complement` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `frite`
--
ALTER TABLE `frite`
  ADD CONSTRAINT `FK_20EBC46DBF396750` FOREIGN KEY (`id`) REFERENCES `repas` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `gestionnaire`
--
ALTER TABLE `gestionnaire`
  ADD CONSTRAINT `FK_F4461B20BF396750` FOREIGN KEY (`id`) REFERENCES `user` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `image`
--
ALTER TABLE `image`
  ADD CONSTRAINT `FK_C53D045F1D236AAA` FOREIGN KEY (`repas_id`) REFERENCES `repas` (`id`);

--
-- Contraintes pour la table `menu`
--
ALTER TABLE `menu`
  ADD CONSTRAINT `FK_7D053A93BF396750` FOREIGN KEY (`id`) REFERENCES `repas` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `menu_repas`
--
ALTER TABLE `menu_repas`
  ADD CONSTRAINT `FK_397F72D41D236AAA` FOREIGN KEY (`repas_id`) REFERENCES `repas` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `FK_397F72D4CCD7E912` FOREIGN KEY (`menu_id`) REFERENCES `menu` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `paiement`
--
ALTER TABLE `paiement`
  ADD CONSTRAINT `FK_B1DC7A1E19EB6921` FOREIGN KEY (`client_id`) REFERENCES `client` (`id`),
  ADD CONSTRAINT `FK_B1DC7A1E82EA2E54` FOREIGN KEY (`commande_id`) REFERENCES `commande` (`id`);

--
-- Contraintes pour la table `panier`
--
ALTER TABLE `panier`
  ADD CONSTRAINT `FK_24CC0DF21D236AAA` FOREIGN KEY (`repas_id`) REFERENCES `repas` (`id`),
  ADD CONSTRAINT `FK_24CC0DF282EA2E54` FOREIGN KEY (`commande_id`) REFERENCES `commande` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
