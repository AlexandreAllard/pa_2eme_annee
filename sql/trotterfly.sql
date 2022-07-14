-- phpMyAdmin SQL Dump
-- version 5.0.4
-- https://www.phpmyadmin.net/
--
-- Hôte : localhost:3306
-- Généré le : jeu. 14 juil. 2022 à 22:03
-- Version du serveur :  5.7.24
-- Version de PHP : 8.0.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `trotterfly`
--

-- --------------------------------------------------------

--
-- Structure de la table `caf_categorie_produit`
--

CREATE TABLE `caf_categorie_produit` (
  `id_categorie` int(11) NOT NULL,
  `nom_categorie` varchar(255) NOT NULL,
  `description_categorie` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `caf_categorie_produit`
--

INSERT INTO `caf_categorie_produit` (`id_categorie`, `nom_categorie`, `description_categorie`) VALUES
(1, 'Sécurité', 'Sécurité'),
(2, 'Textile', 'Textile'),
(3, 'Sac et housses', 'Sac et housses'),
(6, 'Divers', 'Divers'),
(7, 'Animaux', 'Animaux');

-- --------------------------------------------------------

--
-- Structure de la table `caf_collecteur`
--

CREATE TABLE `caf_collecteur` (
  `id_collecteur` int(11) NOT NULL,
  `etat` tinyint(1) NOT NULL DEFAULT '1',
  `nom` varchar(100) NOT NULL,
  `prenom` varchar(50) NOT NULL,
  `adresse_mail` varchar(255) NOT NULL,
  `mot_de_passe` varchar(255) NOT NULL,
  `numero_de_telephone` char(10) NOT NULL,
  `statut` varchar(25) NOT NULL DEFAULT 'collecteur',
  `latitude` double DEFAULT NULL,
  `longitude` double DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `caf_collecteur`
--

INSERT INTO `caf_collecteur` (`id_collecteur`, `etat`, `nom`, `prenom`, `adresse_mail`, `mot_de_passe`, `numero_de_telephone`, `statut`, `latitude`, `longitude`) VALUES
(1, 1, 'Kouach', 'Ambroise', 'azerty@gmail.com', '$2y$10$5BhncL9a2pArRf0w8KEA2.dSox3kBCzb3h4Ch7BGQn3QX.l9GHinO', '0102030405', 'collecteur', 45.777931, 4.82199),
(2, 1, 'Casimir', 'Evi', 'evi@gmail.com', '$2y$10$5BhncL9a2pArRf0w8KEA2.dSox3kBCzb3h4Ch7BGQn3QX.l9GHinO', '0102030405', 'collecteur', 45, 4.8),
(4, 1, 'FEYDEAU', 'Monique', 'monique@gmail.com', '$2y$10$5BhncL9a2pArRf0w8KEA2.dSox3kBCzb3h4Ch7BGQn3QX.l9GHinO', '0125252525', 'collecteur', 45.1, 4.831);

-- --------------------------------------------------------

--
-- Structure de la table `caf_commande`
--

CREATE TABLE `caf_commande` (
  `id_commande` int(11) NOT NULL,
  `type_commande` varchar(50) DEFAULT NULL,
  `montant_commande` int(11) NOT NULL,
  `point_fidelite_utilise` int(11) DEFAULT NULL,
  `id_client` int(11) NOT NULL,
  `id_produit` int(11) NOT NULL,
  `nom_produit` varchar(255) NOT NULL,
  `date_commande` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `date_livraison` date DEFAULT NULL,
  `numero_de_commande` int(7) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `caf_commande`
--

INSERT INTO `caf_commande` (`id_commande`, `type_commande`, `montant_commande`, `point_fidelite_utilise`, `id_client`, `id_produit`, `nom_produit`, `date_commande`, `date_livraison`, `numero_de_commande`) VALUES
(1, 'produit', 500, NULL, 4, 4, 'Sac Noir', '2022-07-12 19:47:16', '2022-07-19', 4591223),
(2, 'produit', 499, NULL, 4, 4, 'Sac Noir', '2022-07-12 19:54:24', '2022-07-19', 5723515),
(3, 'produit', 56, NULL, 4, 2, 'Casque', '2022-07-12 20:08:46', '2022-07-19', 3856877),
(4, 'produit', 380, 600, 4, 4, 'Sac Noir', '2022-07-12 20:26:20', '2022-07-19', 9500469),
(6, 'produit', 29, 153, 4, 2, 'Casque', '2022-07-12 20:55:26', '2022-07-19', 2372141),
(7, 'produit', 9999, 0, 2, 3, 'Casque Oreille Lapin', '2022-07-12 23:10:26', '2022-07-20', 1234567),
(8, 'forfait', 10, 1, 4, 2, 'Rider', '2022-07-14 19:28:59', NULL, 2466342),
(12, 'forfait', 19, 5, 4, 3, 'Passionné', '2022-07-14 19:51:39', NULL, 7014104),
(13, 'forfait', 8, 8, 4, 2, 'Rider', '2022-07-14 19:57:35', NULL, 7250523),
(14, 'forfait', 19, 4, 4, 3, 'Passionné', '2022-07-14 20:51:28', NULL, 6929476);

-- --------------------------------------------------------

--
-- Structure de la table `caf_facture`
--

CREATE TABLE `caf_facture` (
  `id_facture` int(11) NOT NULL,
  `montant_facture` int(11) NOT NULL,
  `date_facture` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `id-reservation` int(11) DEFAULT NULL,
  `id_client` int(11) NOT NULL,
  `prenom_client` varchar(50) NOT NULL,
  `nom_client` varchar(100) NOT NULL,
  `adresse_mail_client` varchar(255) NOT NULL,
  `id_produit` int(11) NOT NULL,
  `nom_produit` varchar(255) NOT NULL,
  `numero_de_commande` int(7) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `caf_facture`
--

INSERT INTO `caf_facture` (`id_facture`, `montant_facture`, `date_facture`, `id-reservation`, `id_client`, `prenom_client`, `nom_client`, `adresse_mail_client`, `id_produit`, `nom_produit`, `numero_de_commande`) VALUES
(3, 60, '2022-07-12 00:05:26', NULL, 4, 'Jackie', 'CHAN', 'test_user@gmail.com', 2, 'Casque', 4524915),
(4, 500, '2022-07-12 00:14:47', NULL, 4, 'Jackie', 'CHAN', 'test_user@gmail.com', 4, 'Sac Noir', 9571765),
(5, 500, '2022-07-12 09:33:19', NULL, 4, 'Jackie', 'CHAN', 'test_user@gmail.com', 4, 'Sac Noir', 8046306),
(6, 100, '2022-07-12 09:34:29', NULL, 4, 'Jackie', 'CHAN', 'test_user@gmail.com', 10, 'Gants', 1605638),
(7, 500, '2022-07-12 09:36:12', NULL, 4, 'Jackie', 'CHAN', 'test_user@gmail.com', 4, 'Sac Noir', 3883492),
(8, 100, '2022-07-12 09:38:30', NULL, 4, 'Jackie', 'CHAN', 'test_user@gmail.com', 10, 'Gants', 3968582),
(9, 100, '2022-07-12 10:13:45', NULL, 4, 'Jackie', 'CHAN', 'test_user@gmail.com', 10, 'Gants', 3863968),
(10, 60, '2022-07-12 14:49:38', NULL, 4, 'Jackie', 'CHAN', 'test_user@gmail.com', 2, 'Casque', 3755693),
(11, 60, '2022-07-12 15:05:52', NULL, 4, 'Jackie', 'CHAN', 'test_user@gmail.com', 2, 'Casque', 7030306),
(12, 100, '2022-07-12 16:42:27', NULL, 4, 'Jackie', 'CHAN', 'test_user@gmail.com', 10, 'Gants', 7940545),
(13, 96, '2022-07-12 16:59:50', NULL, 4, 'Jackie', 'CHAN', 'test_user@gmail.com', 10, 'Gants', 8925660),
(14, 96, '2022-07-12 18:56:18', NULL, 4, 'Jackie', 'CHAN', 'test_user@gmail.com', 10, 'Gants', 7092998),
(15, 40, '2022-07-12 19:04:14', NULL, 4, 'Jackie', 'CHAN', 'test_user@gmail.com', 2, 'Casque', 8248730),
(16, 19, '2022-07-12 19:10:53', NULL, 4, 'Jackie', 'CHAN', 'test_user@gmail.com', 1, 'Clignotant', 9287048),
(17, 97, '2022-07-12 19:11:52', NULL, 4, 'Jackie', 'CHAN', 'test_user@gmail.com', 10, 'Gants', 1057968),
(18, 494, '2022-07-12 19:13:01', NULL, 4, 'Jackie', 'CHAN', 'test_user@gmail.com', 4, 'Sac Noir', 2184841),
(19, 90, '2022-07-12 19:14:28', NULL, 4, 'Jackie', 'CHAN', 'test_user@gmail.com', 10, 'Gants', 9878021),
(20, 96, '2022-07-12 19:17:18', NULL, 4, 'Jackie', 'CHAN', 'test_user@gmail.com', 10, 'Gants', 1300418),
(21, 57, '2022-07-12 19:25:16', NULL, 4, 'Jackie', 'CHAN', 'test_user@gmail.com', 2, 'Casque', 7960173),
(22, 495, '2022-07-12 19:33:42', NULL, 4, 'Jackie', 'CHAN', 'test_user@gmail.com', 4, 'Sac Noir', 2354495),
(23, 490, '2022-07-12 19:35:21', NULL, 4, 'Jackie', 'CHAN', 'test_user@gmail.com', 4, 'Sac Noir', 9891600),
(24, 56, '2022-07-12 19:36:33', NULL, 4, 'Jackie', 'CHAN', 'test_user@gmail.com', 2, 'Casque', 9756807),
(25, 100, '2022-07-12 19:44:29', NULL, 4, 'Jackie', 'CHAN', 'test_user@gmail.com', 10, 'Gants', 1553432),
(26, 80, '2022-07-12 19:46:17', NULL, 4, 'Jackie', 'CHAN', 'test_user@gmail.com', 10, 'Gants', 1717478),
(27, 500, '2022-07-12 19:47:16', NULL, 4, 'Jackie', 'CHAN', 'test_user@gmail.com', 4, 'Sac Noir', 4591223),
(28, 499, '2022-07-12 19:54:24', NULL, 4, 'Jackie', 'CHAN', 'test_user@gmail.com', 4, 'Sac Noir', 5723515),
(29, 56, '2022-07-12 20:08:46', NULL, 4, 'Jackie', 'CHAN', 'test_user@gmail.com', 2, 'Casque', 3856877),
(30, 380, '2022-07-12 20:26:20', NULL, 4, 'Jackie', 'CHAN', 'test_user@gmail.com', 4, 'Sac Noir', 9500469),
(31, 10, '2022-07-12 20:49:19', NULL, 4, 'Jackie', 'CHAN', 'test_user@gmail.com', 10, 'Gants', 6434196),
(32, 29, '2022-07-12 20:55:26', NULL, 4, 'Jackie', 'CHAN', 'test_user@gmail.com', 2, 'Casque', 2372141),
(33, 10, '2022-07-14 19:26:12', NULL, 4, 'Lo', 'MARIE', 'marie@gmail.com', 2, 'Rider', 7702469),
(34, 10, '2022-07-14 19:28:59', NULL, 4, 'Lo', 'MARIE', 'marie@gmail.com', 2, 'Rider', 2466342),
(35, 19, '2022-07-14 19:36:47', NULL, 4, 'Lo', 'MARIE', 'marie@gmail.com', 3, 'Passionné', 5770490),
(36, 19, '2022-07-14 19:48:55', NULL, 4, 'Lo', 'MARIE', 'marie@gmail.com', 3, 'Passionné', 1350403),
(37, 19, '2022-07-14 19:50:10', NULL, 4, 'Lo', 'MARIE', 'marie@gmail.com', 3, 'Passionné', 4551262),
(38, 19, '2022-07-14 19:51:39', NULL, 4, 'Lo', 'MARIE', 'marie@gmail.com', 3, 'Passionné', 7014104),
(39, 8, '2022-07-14 19:57:35', NULL, 4, 'Lo', 'MARIE', 'marie@gmail.com', 2, 'Rider', 7250523),
(40, 19, '2022-07-14 20:51:28', NULL, 4, 'Lo', 'MARIE', 'marie@gmail.com', 3, 'Passionné', 6929476);

-- --------------------------------------------------------

--
-- Structure de la table `caf_forfait`
--

CREATE TABLE `caf_forfait` (
  `id_forfait` int(11) NOT NULL,
  `paiement_immediat` tinyint(1) NOT NULL DEFAULT '1',
  `nom_forfait` varchar(25) NOT NULL,
  `type_forfait` varchar(20) NOT NULL,
  `prix_deverrouillage` float DEFAULT NULL,
  `prix_minute` float DEFAULT NULL,
  `prix_jour` float DEFAULT NULL,
  `prix_mois` float DEFAULT NULL,
  `prix_an` float DEFAULT NULL,
  `nb_courses` int(11) DEFAULT NULL,
  `temps_course` float DEFAULT NULL,
  `temps_expiration_heures` float DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `caf_forfait`
--

INSERT INTO `caf_forfait` (`id_forfait`, `paiement_immediat`, `nom_forfait`, `type_forfait`, `prix_deverrouillage`, `prix_minute`, `prix_jour`, `prix_mois`, `prix_an`, `nb_courses`, `temps_course`, `temps_expiration_heures`) VALUES
(1, 0, 'Timide', 'minute', 1, 0.23, NULL, NULL, NULL, NULL, NULL, NULL),
(2, 1, 'Rider', 'journalier', 0, NULL, 9.99, NULL, NULL, NULL, 30, 24),
(3, 1, 'Passionné', 'mensuel', 0, NULL, NULL, 19.99, NULL, 8, 30, 720),
(4, 1, 'Passionné', 'mensuel', 0, NULL, NULL, 44.99, NULL, 25, 30, 720),
(5, 1, 'Passionné', 'mensuel', 0, NULL, NULL, 79.99, NULL, 50, 30, 720);

-- --------------------------------------------------------

--
-- Structure de la table `caf_meteo`
--

CREATE TABLE `caf_meteo` (
  `heure` int(11) DEFAULT NULL,
  `date` date DEFAULT NULL,
  `temperature` int(11) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `caf_meteo`
--

INSERT INTO `caf_meteo` (`heure`, `date`, `temperature`, `description`) VALUES
(0, '2022-07-15', 20, 'Clear'),
(3, '2022-07-15', 17, 'Clear'),
(6, '2022-07-15', 19, 'Clear'),
(9, '2022-07-15', 25, 'Clear'),
(12, '2022-07-15', 29, 'Clear'),
(15, '2022-07-14', 34, 'Clear'),
(18, '2022-07-14', 33, 'Clear'),
(21, '2022-07-14', 26, 'Clear');

-- --------------------------------------------------------

--
-- Structure de la table `caf_point_collecte`
--

CREATE TABLE `caf_point_collecte` (
  `id_point_collecte` int(11) NOT NULL,
  `numero_rue` int(11) NOT NULL,
  `rue` varchar(255) NOT NULL,
  `ville` varchar(255) NOT NULL,
  `latitude` double DEFAULT NULL,
  `longitude` double DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `caf_point_collecte`
--

INSERT INTO `caf_point_collecte` (`id_point_collecte`, `numero_rue`, `rue`, `ville`, `latitude`, `longitude`) VALUES
(1, 7, 'rue Smith', 'Lyon', 45.787339, 4.839324),
(2, 2, 'rue Ferdinand Buisson', 'Lyon', 45.739, 4.839325),
(3, 10, 'rue Grognard', 'Lyon', 45.7339, 4.839326),
(4, 7, 'rue de Champvert', 'Lyon', 45.87339, 4.839327);

-- --------------------------------------------------------

--
-- Structure de la table `caf_produit`
--

CREATE TABLE `caf_produit` (
  `id_produit` int(11) NOT NULL,
  `prix_produit` float NOT NULL,
  `nom_produit` varchar(255) NOT NULL,
  `description_produit` varchar(255) NOT NULL,
  `photo_produit` varchar(255) DEFAULT NULL,
  `id_categorie` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `caf_produit`
--

INSERT INTO `caf_produit` (`id_produit`, `prix_produit`, `nom_produit`, `description_produit`, `photo_produit`, `id_categorie`) VALUES
(1, 20, 'Clignotant', 'Pour voir et surtout être vu !', 'uploads/clignotant.jpg', 1),
(2, 60, 'Casque', 'Incroyable', 'uploads/casque.jpg', 1),
(3, 9999, 'Casque Oreille Lapin', 'trop cool', 'uploads/oreilleslapin.jpg', 1),
(4, 500, 'Sac Noir', 'Pour transporter... pendant qu\'on est transporté !!', 'uploads/bag.jpg', 3);

-- --------------------------------------------------------

--
-- Structure de la table `caf_reservation`
--

CREATE TABLE `caf_reservation` (
  `id_reservation` int(11) NOT NULL,
  `id_client` int(11) NOT NULL,
  `etat_reservation` tinyint(1) NOT NULL DEFAULT '1',
  `prix_reservation` int(11) NOT NULL,
  `duree_reservation` int(11) NOT NULL,
  `date_reservation` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `date_expiration` timestamp NULL DEFAULT NULL,
  `nb_courses` int(11) DEFAULT NULL,
  `heure_debut` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `heure_fin` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `id_trottinette` int(11) DEFAULT NULL,
  `id_forfait` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `caf_reservation`
--

INSERT INTO `caf_reservation` (`id_reservation`, `id_client`, `etat_reservation`, `prix_reservation`, `duree_reservation`, `date_reservation`, `date_expiration`, `nb_courses`, `heure_debut`, `heure_fin`, `id_trottinette`, `id_forfait`) VALUES
(2, 4, 0, 10, 30, '2022-07-14 18:06:59', '2022-07-13 22:00:00', 1, '2022-07-13 02:57:22', '2022-07-13 02:57:22', 3, 2),
(3, 4, 0, 10, 30, '2022-07-14 18:59:12', '2022-07-14 02:59:26', 1, '2022-07-13 02:59:27', '2022-07-13 02:59:27', 4, 2),
(4, 4, 0, 10, 30, '2022-07-13 18:15:12', '2022-07-14 13:51:19', 1, NULL, NULL, NULL, 2),
(5, 4, 0, 10, 30, '2022-07-14 19:18:26', '2022-07-15 15:46:54', 0, '2022-07-14 19:18:25', '2022-07-14 19:18:26', NULL, 2),
(6, 4, 1, 45, 30, '2022-07-14 20:00:50', '2022-08-14 16:52:22', 15, '2022-07-14 20:00:47', '2022-07-14 20:00:50', NULL, 4),
(10, 4, 0, 19, 30, '2022-07-14 20:00:27', '2022-08-13 19:51:38', 0, '2022-07-14 20:00:26', '2022-07-14 20:00:27', NULL, 3),
(11, 4, 0, 8, 30, '2022-07-14 20:01:32', '2022-07-15 19:57:34', NULL, '2022-07-14 20:01:31', '2022-07-14 20:01:32', NULL, 2),
(12, 4, 1, 19, 30, '2022-07-14 20:51:27', '2022-08-13 20:51:27', 8, '2022-07-14 20:51:28', '2022-07-14 20:51:28', NULL, 3);

-- --------------------------------------------------------

--
-- Structure de la table `caf_retour`
--

CREATE TABLE `caf_retour` (
  `id_retour` int(11) NOT NULL,
  `date_retour` date NOT NULL,
  `nb_article_retoune` int(11) NOT NULL,
  `montant_produit_retourne` int(11) NOT NULL,
  `motif` varchar(255) NOT NULL,
  `numero_de_suivi` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `caf_trottinette`
--

CREATE TABLE `caf_trottinette` (
  `id_trottinette` int(11) NOT NULL,
  `etat_trottinette` int(1) DEFAULT NULL,
  `km` int(11) NOT NULL DEFAULT '0',
  `latitude` double DEFAULT NULL,
  `longitude` double DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `caf_trottinette`
--

INSERT INTO `caf_trottinette` (`id_trottinette`, `etat_trottinette`, `km`, `latitude`, `longitude`) VALUES
(3, 1, 200, 45.759954, 4.832113),
(4, 0, 3, 45.76055, 4.833254),
(5, 1, 345, 45.753632, 4.847843);

-- --------------------------------------------------------

--
-- Structure de la table `caf_utilisateur`
--

CREATE TABLE `caf_utilisateur` (
  `id_client` int(11) NOT NULL,
  `statut` varchar(25) NOT NULL DEFAULT 'client',
  `etat` tinyint(4) NOT NULL DEFAULT '0',
  `cle` int(9) DEFAULT NULL,
  `nom` varchar(100) NOT NULL,
  `prenom` varchar(50) NOT NULL,
  `adresse_mail` varchar(255) NOT NULL,
  `mot_de_passe` varchar(255) NOT NULL,
  `numero_de_telephone` char(10) NOT NULL,
  `date_de_naissance` date NOT NULL,
  `numero_voie` int(11) NOT NULL,
  `type_voie` varchar(255) NOT NULL,
  `nom_voie` varchar(255) NOT NULL,
  `code_postal` char(5) NOT NULL,
  `ville` varchar(255) NOT NULL,
  `indication_supplementaire` varchar(255) DEFAULT NULL,
  `stock_trajet` int(11) DEFAULT '0',
  `points_fidelite` int(11) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `caf_utilisateur`
--

INSERT INTO `caf_utilisateur` (`id_client`, `statut`, `etat`, `cle`, `nom`, `prenom`, `adresse_mail`, `mot_de_passe`, `numero_de_telephone`, `date_de_naissance`, `numero_voie`, `type_voie`, `nom_voie`, `code_postal`, `ville`, `indication_supplementaire`, `stock_trajet`, `points_fidelite`) VALUES
(1, 'admin', 1, 6010343, 'GUILLEMONT', 'Fnny', 'fanny@gmail.com', '$2y$10$aE9PBbpPvNz4diT8w.yfYOoUyiidee/lKsMSFwnxzthSf/8SH9tnu', '0125245694', '2000-01-01', 21, 'Boulevard', 'Fanny', '95170', 'Deuil-la-Barre', 'Ok', 0, 0),
(2, 'client', 1, 5106704, 'ALLARD', 'Alexandre', 'alexandre@gmail.com', '$2y$10$F1VAAa3JV2Lh6n4RYHxxPudmPsSEoXXo87Zief59w7Cvaq.woogra', '0125242526', '2002-01-01', 1, 'Boulevard', 'Blé', '75008', 'Paris', '', 0, 0),
(3, 'client', 1, 6368228, 'LANCE', 'Justine', 'justine@gmail.com', '$2y$10$UCuxQx8/wuNnLpuMqkSRrePCQ98Lkvbe8XoQ0JPrzri5TDxGXu4M.', '0125252525', '2000-02-12', 1, 'Rue', 'Orge', '75002', 'Paris', '', 0, 0),
(4, 'client', 1, 5679791, 'MARIE', 'Lo', 'marie@gmail.com', '$2y$10$vqDo88JMw/5/Byo1uHe9W.TZLn0BuUi5Oda0NcJZw6o/Mnnxg5ugO', '0145454545', '1999-02-05', 8, 'Boulevard', 'Victor Hugo', '75003', 'Paris', '', 0, 6);

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `caf_categorie_produit`
--
ALTER TABLE `caf_categorie_produit`
  ADD PRIMARY KEY (`id_categorie`);

--
-- Index pour la table `caf_collecteur`
--
ALTER TABLE `caf_collecteur`
  ADD PRIMARY KEY (`id_collecteur`);

--
-- Index pour la table `caf_commande`
--
ALTER TABLE `caf_commande`
  ADD PRIMARY KEY (`id_commande`),
  ADD KEY `id_client` (`id_client`),
  ADD KEY `id_produit` (`id_produit`);

--
-- Index pour la table `caf_facture`
--
ALTER TABLE `caf_facture`
  ADD PRIMARY KEY (`id_facture`),
  ADD KEY `id_reservation` (`id-reservation`);

--
-- Index pour la table `caf_forfait`
--
ALTER TABLE `caf_forfait`
  ADD PRIMARY KEY (`id_forfait`);

--
-- Index pour la table `caf_point_collecte`
--
ALTER TABLE `caf_point_collecte`
  ADD PRIMARY KEY (`id_point_collecte`);

--
-- Index pour la table `caf_produit`
--
ALTER TABLE `caf_produit`
  ADD PRIMARY KEY (`id_produit`),
  ADD KEY `id_categorie` (`id_categorie`);

--
-- Index pour la table `caf_reservation`
--
ALTER TABLE `caf_reservation`
  ADD PRIMARY KEY (`id_reservation`),
  ADD KEY `id_trottinette` (`id_trottinette`) USING BTREE,
  ADD KEY `id_forfait` (`id_forfait`);

--
-- Index pour la table `caf_retour`
--
ALTER TABLE `caf_retour`
  ADD PRIMARY KEY (`id_retour`);

--
-- Index pour la table `caf_trottinette`
--
ALTER TABLE `caf_trottinette`
  ADD PRIMARY KEY (`id_trottinette`);

--
-- Index pour la table `caf_utilisateur`
--
ALTER TABLE `caf_utilisateur`
  ADD PRIMARY KEY (`id_client`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `caf_categorie_produit`
--
ALTER TABLE `caf_categorie_produit`
  MODIFY `id_categorie` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT pour la table `caf_collecteur`
--
ALTER TABLE `caf_collecteur`
  MODIFY `id_collecteur` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT pour la table `caf_commande`
--
ALTER TABLE `caf_commande`
  MODIFY `id_commande` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT pour la table `caf_facture`
--
ALTER TABLE `caf_facture`
  MODIFY `id_facture` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;

--
-- AUTO_INCREMENT pour la table `caf_forfait`
--
ALTER TABLE `caf_forfait`
  MODIFY `id_forfait` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT pour la table `caf_point_collecte`
--
ALTER TABLE `caf_point_collecte`
  MODIFY `id_point_collecte` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT pour la table `caf_produit`
--
ALTER TABLE `caf_produit`
  MODIFY `id_produit` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT pour la table `caf_reservation`
--
ALTER TABLE `caf_reservation`
  MODIFY `id_reservation` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT pour la table `caf_retour`
--
ALTER TABLE `caf_retour`
  MODIFY `id_retour` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `caf_trottinette`
--
ALTER TABLE `caf_trottinette`
  MODIFY `id_trottinette` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT pour la table `caf_utilisateur`
--
ALTER TABLE `caf_utilisateur`
  MODIFY `id_client` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `caf_commande`
--
ALTER TABLE `caf_commande`
  ADD CONSTRAINT `id_client` FOREIGN KEY (`id_client`) REFERENCES `caf_utilisateur` (`id_client`),
  ADD CONSTRAINT `id_produit` FOREIGN KEY (`id_produit`) REFERENCES `caf_produit` (`id_produit`);

--
-- Contraintes pour la table `caf_facture`
--
ALTER TABLE `caf_facture`
  ADD CONSTRAINT `id_reservation` FOREIGN KEY (`id-reservation`) REFERENCES `caf_reservation` (`id_reservation`);

--
-- Contraintes pour la table `caf_produit`
--
ALTER TABLE `caf_produit`
  ADD CONSTRAINT `id_categorie` FOREIGN KEY (`id_categorie`) REFERENCES `caf_categorie_produit` (`id_categorie`);

--
-- Contraintes pour la table `caf_reservation`
--
ALTER TABLE `caf_reservation`
  ADD CONSTRAINT `id_forfait` FOREIGN KEY (`id_forfait`) REFERENCES `caf_forfait` (`id_forfait`),
  ADD CONSTRAINT `id_trottinette` FOREIGN KEY (`id_trottinette`) REFERENCES `caf_trottinette` (`id_trottinette`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
