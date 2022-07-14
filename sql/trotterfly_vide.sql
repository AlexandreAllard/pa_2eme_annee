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
