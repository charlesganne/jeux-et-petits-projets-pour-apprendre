-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : mer. 08 mai 2024 à 18:12
-- Version du serveur : 10.4.32-MariaDB
-- Version de PHP : 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `jeu_echec`
--

-- --------------------------------------------------------

--
-- Structure de la table `clients`
--

CREATE TABLE `clients` (
  `id_client` int(11) NOT NULL,
  `nom` varchar(100) DEFAULT NULL,
  `email` varchar(200) DEFAULT NULL,
  `mdp` varchar(30) DEFAULT NULL,
  `elo` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `clients`
--

INSERT INTO `clients` (`id_client`, `nom`, `email`, `mdp`, `elo`) VALUES
(11, 'test', 'test', 'test', 0),
(12, 'paul', 'paul', 'paul', 0),
(13, 'tom', 'tom', 'tom', 0);

-- --------------------------------------------------------

--
-- Structure de la table `coup`
--

CREATE TABLE `coup` (
  `num_coup` int(11) NOT NULL,
  `id_partie` int(11) NOT NULL,
  `id_piece` int(11) DEFAULT NULL,
  `case_depart` varchar(2) DEFAULT NULL,
  `case_arrivee` varchar(2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `partie`
--

CREATE TABLE `partie` (
  `id_partie` int(11) NOT NULL,
  `joueur_1` int(11) DEFAULT NULL,
  `joueur_2` int(11) DEFAULT NULL,
  `statut` varchar(32) DEFAULT 'En attente',
  `pendule_j1` int(11) DEFAULT NULL,
  `pendule_j2` int(11) DEFAULT NULL,
  `temps_coup` int(11) DEFAULT NULL,
  `niveau` varchar(32) DEFAULT NULL,
  `point_1` int(11) DEFAULT NULL,
  `point_2` int(11) DEFAULT NULL,
  `id_vainqueur` int(11) DEFAULT NULL,
  `joueur_dont_c_est_le_tour` int(11) DEFAULT NULL,
  `etat_echiquier` text DEFAULT NULL,
  `propose_null` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `partie`
--

INSERT INTO `partie` (`id_partie`, `joueur_1`, `joueur_2`, `statut`, `pendule_j1`, `pendule_j2`, `temps_coup`, `niveau`, `point_1`, `point_2`, `id_vainqueur`, `joueur_dont_c_est_le_tour`, `etat_echiquier`, `propose_null`) VALUES
(5, 11, NULL, 'en attente', 19999, 123, 123, 'facile', 4, 7, NULL, NULL, NULL, NULL),
(7, NULL, 12, 'En attente', 34, 34, 456, 'facile', 0, 0, NULL, NULL, NULL, NULL),
(10, 11, 12, 'en cours', 150, 150, 5, 'difficile', 0, 0, NULL, 1, 'a:8:{i:0;a:8:{i:0;a:3:{i:0;s:4:\"tour\";i:1;s:4:\"noir\";i:2;s:38:\"<span style=\"color: black;\">♜</span>\";}i:1;a:3:{i:0;s:2:\"pv\";i:1;s:2:\"pv\";i:2;s:0:\"\";}i:2;a:3:{i:0;s:3:\"fou\";i:1;s:4:\"noir\";i:2;s:38:\"<span style=\"color: black;\">♝</span>\";}i:3;a:3:{i:0;s:2:\"pv\";i:1;s:2:\"pv\";i:2;s:0:\"\";}i:4;a:3:{i:0;s:3:\"roi\";i:1;s:4:\"noir\";i:2;s:38:\"<span style=\"color: black;\">♚</span>\";}i:5;a:3:{i:0;s:3:\"fou\";i:1;s:4:\"noir\";i:2;s:38:\"<span style=\"color: black;\">♝</span>\";}i:6;a:3:{i:0;s:8:\"cavalier\";i:1;s:4:\"noir\";i:2;s:38:\"<span style=\"color: black;\">♞</span>\";}i:7;a:3:{i:0;s:4:\"tour\";i:1;s:4:\"noir\";i:2;s:38:\"<span style=\"color: black;\">♜</span>\";}}i:1;a:8:{i:0;a:3:{i:0;s:2:\"pv\";i:1;s:2:\"pv\";i:2;s:0:\"\";}i:1;a:3:{i:0;s:2:\"pv\";i:1;s:2:\"pv\";i:2;s:0:\"\";}i:2;a:3:{i:0;s:2:\"pv\";i:1;s:2:\"pv\";i:2;s:0:\"\";}i:3;a:3:{i:0;s:4:\"pion\";i:1;s:4:\"noir\";i:2;s:38:\"<span style=\"color: black;\">♟</span>\";}i:4;a:3:{i:0;s:4:\"pion\";i:1;s:4:\"noir\";i:2;s:38:\"<span style=\"color: black;\">♟</span>\";}i:5;a:3:{i:0;s:2:\"pv\";i:1;s:2:\"pv\";i:2;s:0:\"\";}i:6;a:3:{i:0;s:2:\"pv\";i:1;s:2:\"pv\";i:2;s:0:\"\";}i:7;a:3:{i:0;s:4:\"pion\";i:1;s:4:\"noir\";i:2;s:38:\"<span style=\"color: black;\">♟</span>\";}}i:2;a:8:{i:0;a:3:{i:0;s:2:\"pv\";i:1;s:2:\"pv\";i:2;s:0:\"\";}i:1;a:3:{i:0;s:4:\"pion\";i:1;s:4:\"noir\";i:2;s:38:\"<span style=\"color: black;\">♟</span>\";}i:2;a:3:{i:0;s:2:\"pv\";i:1;s:2:\"pv\";i:2;s:0:\"\";}i:3;a:3:{i:0;s:2:\"pv\";i:1;s:2:\"pv\";i:2;s:0:\"\";}i:4;a:3:{i:0;s:4:\"pion\";i:1;s:4:\"noir\";i:2;s:38:\"<span style=\"color: black;\">♟</span>\";}i:5;a:3:{i:0;s:2:\"pv\";i:1;s:2:\"pv\";i:2;s:0:\"\";}i:6;a:3:{i:0;s:2:\"pv\";i:1;s:2:\"pv\";i:2;s:0:\"\";}i:7;a:3:{i:0;s:2:\"pv\";i:1;s:2:\"pv\";i:2;s:0:\"\";}}i:3;a:8:{i:0;a:3:{i:0;s:2:\"pv\";i:1;s:2:\"pv\";i:2;s:0:\"\";}i:1;a:3:{i:0;s:2:\"pv\";i:1;s:2:\"pv\";i:2;s:0:\"\";}i:2;a:3:{i:0;s:2:\"pv\";i:1;s:2:\"pv\";i:2;s:0:\"\";}i:3;a:3:{i:0;s:2:\"pv\";i:1;s:2:\"pv\";i:2;s:0:\"\";}i:4;a:3:{i:0;s:5:\"reine\";i:1;s:5:\"blanc\";i:2;s:38:\"<span style=\"color: white;\">♕</span>\";}i:5;a:3:{i:0;s:2:\"pv\";i:1;s:2:\"pv\";i:2;s:0:\"\";}i:6;a:3:{i:0;s:2:\"pv\";i:1;s:2:\"pv\";i:2;s:0:\"\";}i:7;a:3:{i:0;s:2:\"pv\";i:1;s:2:\"pv\";i:2;s:0:\"\";}}i:4;a:8:{i:0;a:3:{i:0;s:2:\"pv\";i:1;s:2:\"pv\";i:2;s:0:\"\";}i:1;a:3:{i:0;s:2:\"pv\";i:1;s:2:\"pv\";i:2;s:0:\"\";}i:2;a:3:{i:0;s:2:\"pv\";i:1;s:2:\"pv\";i:2;s:0:\"\";}i:3;a:3:{i:0;s:2:\"pv\";i:1;s:2:\"pv\";i:2;s:0:\"\";}i:4;a:3:{i:0;s:4:\"pion\";i:1;s:5:\"blanc\";i:2;s:38:\"<span style=\"color: white;\">♙</span>\";}i:5;a:3:{i:0;s:2:\"pv\";i:1;s:2:\"pv\";i:2;s:0:\"\";}i:6;a:3:{i:0;s:4:\"pion\";i:1;s:4:\"noir\";i:2;s:38:\"<span style=\"color: black;\">♟</span>\";}i:7;a:3:{i:0;s:2:\"pv\";i:1;s:2:\"pv\";i:2;s:0:\"\";}}i:5;a:8:{i:0;a:3:{i:0;s:8:\"cavalier\";i:1;s:5:\"blanc\";i:2;s:38:\"<span style=\"color: white;\">♘</span>\";}i:1;a:3:{i:0;s:2:\"pv\";i:1;s:2:\"pv\";i:2;s:0:\"\";}i:2;a:3:{i:0;s:4:\"pion\";i:1;s:5:\"blanc\";i:2;s:38:\"<span style=\"color: white;\">♙</span>\";}i:3;a:3:{i:0;s:2:\"pv\";i:1;s:2:\"pv\";i:2;s:0:\"\";}i:4;a:3:{i:0;s:2:\"pv\";i:1;s:2:\"pv\";i:2;s:0:\"\";}i:5;a:3:{i:0;s:2:\"pv\";i:1;s:2:\"pv\";i:2;s:0:\"\";}i:6;a:3:{i:0;s:2:\"pv\";i:1;s:2:\"pv\";i:2;s:0:\"\";}i:7;a:3:{i:0;s:2:\"pv\";i:1;s:2:\"pv\";i:2;s:0:\"\";}}i:6;a:8:{i:0;a:3:{i:0;s:4:\"pion\";i:1;s:5:\"blanc\";i:2;s:38:\"<span style=\"color: white;\">♙</span>\";}i:1;a:3:{i:0;s:3:\"fou\";i:1;s:5:\"blanc\";i:2;s:38:\"<span style=\"color: white;\">♗</span>\";}i:2;a:3:{i:0;s:2:\"pv\";i:1;s:2:\"pv\";i:2;s:0:\"\";}i:3;a:3:{i:0;s:5:\"reine\";i:1;s:4:\"noir\";i:2;s:38:\"<span style=\"color: black;\">♛</span>\";}i:4;a:3:{i:0;s:2:\"pv\";i:1;s:2:\"pv\";i:2;s:0:\"\";}i:5;a:3:{i:0;s:4:\"pion\";i:1;s:5:\"blanc\";i:2;s:38:\"<span style=\"color: white;\">♙</span>\";}i:6;a:3:{i:0;s:4:\"pion\";i:1;s:5:\"blanc\";i:2;s:38:\"<span style=\"color: white;\">♙</span>\";}i:7;a:3:{i:0;s:4:\"pion\";i:1;s:5:\"blanc\";i:2;s:38:\"<span style=\"color: white;\">♙</span>\";}}i:7;a:8:{i:0;a:3:{i:0;s:4:\"tour\";i:1;s:5:\"blanc\";i:2;s:38:\"<span style=\"color: white;\">♖</span>\";}i:1;a:3:{i:0;s:2:\"pv\";i:1;s:2:\"pv\";i:2;s:0:\"\";}i:2;a:3:{i:0;s:2:\"pv\";i:1;s:2:\"pv\";i:2;s:0:\"\";}i:3;a:3:{i:0;s:2:\"pv\";i:1;s:2:\"pv\";i:2;s:0:\"\";}i:4;a:3:{i:0;s:3:\"roi\";i:1;s:5:\"blanc\";i:2;s:38:\"<span style=\"color: white;\">♔</span>\";}i:5;a:3:{i:0;s:2:\"pv\";i:1;s:2:\"pv\";i:2;s:0:\"\";}i:6;a:3:{i:0;s:2:\"pv\";i:1;s:2:\"pv\";i:2;s:0:\"\";}i:7;a:3:{i:0;s:4:\"tour\";i:1;s:5:\"blanc\";i:2;s:38:\"<span style=\"color: white;\">♖</span>\";}}}', NULL),
(12, 12, NULL, 'en attente', 100, 100, 100, 'facile', 0, 0, NULL, NULL, NULL, NULL),
(13, 13, 12, 'en cours', 200, 200, 200, 'moyen', 0, 0, NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Structure de la table `piece`
--

CREATE TABLE `piece` (
  `id_piece` int(11) NOT NULL,
  `nom` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `piece`
--

INSERT INTO `piece` (`id_piece`, `nom`) VALUES
(1, 'Roi'),
(2, 'Reine'),
(3, 'Tour'),
(4, 'Cavalier'),
(5, 'Fou'),
(6, 'Pion');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `clients`
--
ALTER TABLE `clients`
  ADD PRIMARY KEY (`id_client`);

--
-- Index pour la table `coup`
--
ALTER TABLE `coup`
  ADD PRIMARY KEY (`num_coup`,`id_partie`),
  ADD KEY `id_partie` (`id_partie`),
  ADD KEY `id_piece` (`id_piece`);

--
-- Index pour la table `partie`
--
ALTER TABLE `partie`
  ADD PRIMARY KEY (`id_partie`),
  ADD KEY `joueur_1` (`joueur_1`),
  ADD KEY `joueur_2` (`joueur_2`),
  ADD KEY `id_vainqueur` (`id_vainqueur`);

--
-- Index pour la table `piece`
--
ALTER TABLE `piece`
  ADD PRIMARY KEY (`id_piece`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `clients`
--
ALTER TABLE `clients`
  MODIFY `id_client` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT pour la table `partie`
--
ALTER TABLE `partie`
  MODIFY `id_partie` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `coup`
--
ALTER TABLE `coup`
  ADD CONSTRAINT `coup_ibfk_1` FOREIGN KEY (`id_partie`) REFERENCES `partie` (`id_partie`),
  ADD CONSTRAINT `coup_ibfk_2` FOREIGN KEY (`id_piece`) REFERENCES `piece` (`id_piece`);

--
-- Contraintes pour la table `partie`
--
ALTER TABLE `partie`
  ADD CONSTRAINT `partie_ibfk_1` FOREIGN KEY (`joueur_1`) REFERENCES `clients` (`id_client`),
  ADD CONSTRAINT `partie_ibfk_2` FOREIGN KEY (`joueur_2`) REFERENCES `clients` (`id_client`),
  ADD CONSTRAINT `partie_ibfk_3` FOREIGN KEY (`id_vainqueur`) REFERENCES `clients` (`id_client`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
