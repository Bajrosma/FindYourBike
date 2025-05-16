-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Hôte : localhost
-- Généré le : ven. 09 mai 2025 à 08:49
-- Version du serveur : 5.7.11
-- Version de PHP : 8.0.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `findyourbike`
--
CREATE DATABASE IF NOT EXISTS `db_findyourbike` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
USE `db_findyourbike`;

-- --------------------------------------------------------

--
-- Structure de la table `t_bikedata`
--

CREATE TABLE `t_bikedata` (
  `ID_bikeData` int(11) UNSIGNED NOT NULL,
  `bidPathFile` varchar(260) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `t_bikes`
--

CREATE TABLE `t_bikes` (
  `ID_bike` int(11) UNSIGNED NOT NULL,
  `bikDate` date DEFAULT NULL,
  `bikPlace` varchar(250) DEFAULT NULL,
  `bikFrameNumber` varchar(15) DEFAULT NULL,
  `FK_brand` int(11) UNSIGNED DEFAULT NULL,
  `FK_size` int(11) UNSIGNED DEFAULT NULL,
  `FK_color` int(11) UNSIGNED DEFAULT NULL,
  `FK_commune` int(11) UNSIGNED DEFAULT NULL,
  `FK_personne` int(11) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `t_bikes`
--

INSERT INTO `t_bikes` (`ID_bike`, `bikDate`, `bikPlace`, `bikFrameNumber`, `FK_brand`, `FK_size`, `FK_color`, `FK_commune`, `FK_personne`) VALUES
(1, '2024-05-01', 'Place du Marché 5, 1860 Aigle', 'AG12345678', 1, 2, 5, 1, NULL),
(2, '2024-04-22', 'Gare d\\\'Aigle, Avenue de la Gare 1, 1860 Aigle', 'AG87654321', 2, 1, 8, 1, NULL),
(3, '2024-03-15', 'Rue des Alpes 18, 1860 Aigle', 'AG23456789', 3, 3, 10, 1, NULL),
(4, '2024-05-02', 'Place de la Concorde 2, 1530 Payerne', 'PY98765432', 4, 2, 7, 2, NULL),
(5, '2024-04-10', 'Centre sportif de la Broye, Route du Stade 10, 1530 Payerne', 'PY12349876', 5, 1, 1, 2, NULL),
(6, '2024-03-20', 'Avenue de la Gare 22, 1530 Payerne', 'PY34561234', 6, 3, 12, 2, NULL),
(7, '2024-05-03', 'Port de Rolle, Quai du Port 3, 1180 Rolle', 'RL65432109', 7, 2, 3, 3, NULL),
(8, '2024-04-05', 'Place du Château 1, 1180 Rolle', 'RL01928374', 8, 4, 2, 3, NULL),
(9, '2024-03-18', 'Rue du Lac 7, 1180 Rolle', 'RL56473829', 9, 1, 6, 3, NULL);

-- --------------------------------------------------------

--
-- Structure de la table `t_brand`
--

CREATE TABLE `t_brand` (
  `ID_brand` int(11) UNSIGNED NOT NULL,
  `braName` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `t_brand`
--

INSERT INTO `t_brand` (`ID_brand`, `braName`) VALUES
(1, 'Trek'),
(2, 'Specialized'),
(3, 'Giant'),
(4, 'Cannondale'),
(5, 'Scott'),
(6, 'Bianchi'),
(7, 'Canyon'),
(8, 'Cube'),
(9, 'Santa Cruz'),
(10, 'Orbea'),
(11, 'Merida'),
(12, 'Lapierre'),
(13, 'Focus'),
(14, 'GT Bicycles'),
(15, 'Norco'),
(16, 'Btwin'),
(17, 'Rockrider'),
(18, 'Nakamura'),
(19, 'Scrapper'),
(20, 'Elops'),
(21, 'KS Cycling'),
(22, 'Roadster'),
(23, 'Sunpeed'),
(24, 'Moma Bikes'),
(25, 'Vélo de Ville'),
(26, 'Topbike'),
(27, 'Leader Fox'),
(28, 'Wayscral'),
(29, 'Torpado'),
(30, 'Elliot');

-- --------------------------------------------------------

--
-- Structure de la table `t_color`
--

CREATE TABLE `t_color` (
  `ID_color` int(11) UNSIGNED NOT NULL,
  `colName` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `t_color`
--

INSERT INTO `t_color` (`ID_color`, `colName`) VALUES
(1, 'Noir'),
(2, 'Blanc'),
(3, 'Rouge'),
(4, 'Bleu'),
(5, 'Gris'),
(6, 'Vert'),
(7, 'Jaune'),
(8, 'Orange'),
(9, 'Rose'),
(10, 'Violet'),
(11, 'Marron'),
(12, 'Beige'),
(13, 'Turquoise'),
(14, 'Bleu marine'),
(15, 'Gris anthracite'),
(16, 'Vert kaki'),
(17, 'Rouge bordeaux'),
(18, 'Argenté'),
(19, 'Doré'),
(20, 'Cuivre'),
(21, 'Bleu ciel'),
(22, 'Vert fluo'),
(23, 'Jaune fluo'),
(24, 'Rouge fluo'),
(25, 'Blanc nacré'),
(26, 'Noir mat'),
(27, 'Noir brillant'),
(28, 'Bleu électrique'),
(29, 'Gris métallisé'),
(30, 'Camouflage');

-- --------------------------------------------------------

--
-- Structure de la table `t_communes`
--

CREATE TABLE `t_communes` (
  `ID_commune` int(11) UNSIGNED NOT NULL,
  `comName` varchar(100) NOT NULL,
  `comAdress` varchar(150) NOT NULL,
  `comNPA` int(4) NOT NULL,
  `comCity` varchar(100) NOT NULL,
  `comEmail` varchar(255) NOT NULL,
  `comTel` varchar(20) NOT NULL,
  `comInscription` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `t_communes`
--

INSERT INTO `t_communes` (`ID_commune`, `comName`, `comAdress`, `comNPA`, `comCity`, `comEmail`, `comTel`, `comInscription`) VALUES
(1, 'Commune d\'Aigle', 'Place du Marché 1', 1860, 'Aigle', 'info@aigle.ch', '+41 24 468 41 11', 1),
(2, 'Commune de Payerne', 'Place de la Concorde 1', 1530, 'Payerne', 'info@payerne.ch', '+41 26 662 66 11', 1),
(3, 'Commune de Rolle', 'Grand-Rue 44', 1180, 'Rolle', 'info@rolle.ch', '+41 21 822 44 11', 0);

-- --------------------------------------------------------

--
-- Structure de la table `t_dataproof`
--

CREATE TABLE `t_dataproof` (
  `ID_proofData` int(11) UNSIGNED NOT NULL,
  `proPathFile` varchar(260) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `t_personnes`
--

CREATE TABLE `t_personnes` (
  `ID_personne` int(11) UNSIGNED NOT NULL,
  `perFirstName` varchar(50) DEFAULT NULL,
  `perLastName` varchar(50) DEFAULT NULL,
  `perEmail` varchar(100) DEFAULT NULL,
  `perTel` varchar(20) DEFAULT NULL,
  `perAdress` varchar(150) DEFAULT NULL,
  `perCity` varchar(100) DEFAULT NULL,
  `perNPA` varchar(10) DEFAULT NULL,
  `perRole` varchar(50) DEFAULT NULL,
  `FK_commune` int(11) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `t_personnes`
--

INSERT INTO `t_personnes` (`ID_personne`, `perFirstName`, `perLastName`, `perEmail`, `perTel`, `perAdress`, `perCity`, `perNPA`, `perRole`, `FK_commune`) VALUES
(1, 'Julie', 'Morel', 'julie.morel@gmail.com', '+41 79 456 78 90', 'Rue des Vergers 12', 'Aigle', '1860', 'Citoyen', 1),
(2, 'Marc', 'Durand', 'marc.durand@aigle.ch', '+41 24 468 41 22', 'Place du Bourg 3', 'Aigle', '1860', 'Employé communal', 1),
(3, 'Sophie', 'Weber', 'sophie.weber@aigle.ch', '+41 24 468 42 55', 'Chemin de la Gare 9', 'Aigle', '1860', 'Responsable technique', 1),
(4, 'Thomas', 'Girard', 'thomas.girard@gmail.com', '+41 78 234 56 78', 'Rue des Prés 5', 'Payerne', '1530', 'Citoyen', 2),
(5, 'Nadia', 'Fischer', 'nadia.fischer@payerne.ch', '+41 26 662 66 45', 'Rue du Temple 4', 'Payerne', '1530', 'Employée communale', 2),
(6, 'Kevin', 'Jacot', 'kevin.jacot@payerne.ch', '+41 26 662 67 12', 'Route de la Broye 17', 'Payerne', '1530', 'Responsable voirie', 2),
(7, 'Isabelle', 'Fontana', 'isabelle.fontana@gmail.com', '+41 76 321 54 98', 'Rue du Port 6', 'Rolle', '1180', 'Citoyenne', 3),
(8, 'David', 'Monnier', 'david.monnier@rolle.ch', '+41 21 822 44 78', 'Chemin des Vignes 2', 'Rolle', '1180', 'Employé communal', 3),
(9, 'Lucie', 'Berger', 'lucie.berger@rolle.ch', '+41 21 822 46 10', 'Avenue de la Gare 20', 'Rolle', '1180', 'Responsable technique', 3);

-- --------------------------------------------------------

--
-- Structure de la table `t_size`
--

CREATE TABLE `t_size` (
  `ID_size` int(11) UNSIGNED NOT NULL,
  `sizSize` varchar(2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `t_size`
--

INSERT INTO `t_size` (`ID_size`, `sizSize`) VALUES
(1, 'S'),
(2, 'M'),
(3, 'L'),
(4, 'XL');

-- --------------------------------------------------------

--
-- Structure de la table `t_user`
--

CREATE TABLE `t_user` (
  `ID_user` int(11) UNSIGNED NOT NULL,
  `useName` varchar(20) NOT NULL,
  `usePassword` varchar(64) NOT NULL,
  `usePrivilage` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `t_bikedata`
--
ALTER TABLE `t_bikedata`
  ADD PRIMARY KEY (`ID_bikeData`);

--
-- Index pour la table `t_bikes`
--
ALTER TABLE `t_bikes`
  ADD PRIMARY KEY (`ID_bike`),
  ADD KEY `FK_brand` (`FK_brand`),
  ADD KEY `FK_size` (`FK_size`),
  ADD KEY `FK_color` (`FK_color`),
  ADD KEY `FK_commune` (`FK_commune`),
  ADD KEY `FK_personne` (`FK_personne`);

--
-- Index pour la table `t_brand`
--
ALTER TABLE `t_brand`
  ADD PRIMARY KEY (`ID_brand`);

--
-- Index pour la table `t_color`
--
ALTER TABLE `t_color`
  ADD PRIMARY KEY (`ID_color`);

--
-- Index pour la table `t_communes`
--
ALTER TABLE `t_communes`
  ADD PRIMARY KEY (`ID_commune`);

--
-- Index pour la table `t_dataproof`
--
ALTER TABLE `t_dataproof`
  ADD PRIMARY KEY (`ID_proofData`);

--
-- Index pour la table `t_personnes`
--
ALTER TABLE `t_personnes`
  ADD PRIMARY KEY (`ID_personne`),
  ADD KEY `FK_commune` (`FK_commune`);

--
-- Index pour la table `t_size`
--
ALTER TABLE `t_size`
  ADD PRIMARY KEY (`ID_size`);

--
-- Index pour la table `t_user`
--
ALTER TABLE `t_user`
  ADD PRIMARY KEY (`ID_user`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `t_bikedata`
--
ALTER TABLE `t_bikedata`
  MODIFY `ID_bikeData` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `t_bikes`
--
ALTER TABLE `t_bikes`
  MODIFY `ID_bike` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT pour la table `t_brand`
--
ALTER TABLE `t_brand`
  MODIFY `ID_brand` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT pour la table `t_color`
--
ALTER TABLE `t_color`
  MODIFY `ID_color` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT pour la table `t_communes`
--
ALTER TABLE `t_communes`
  MODIFY `ID_commune` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT pour la table `t_dataproof`
--
ALTER TABLE `t_dataproof`
  MODIFY `ID_proofData` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `t_personnes`
--
ALTER TABLE `t_personnes`
  MODIFY `ID_personne` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT pour la table `t_size`
--
ALTER TABLE `t_size`
  MODIFY `ID_size` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT pour la table `t_user`
--
ALTER TABLE `t_user`
  MODIFY `ID_user` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `t_bikes`
--
ALTER TABLE `t_bikes`
  ADD CONSTRAINT `t_bikes_ibfk_1` FOREIGN KEY (`FK_brand`) REFERENCES `t_brand` (`ID_brand`) ON DELETE CASCADE,
  ADD CONSTRAINT `t_bikes_ibfk_2` FOREIGN KEY (`FK_size`) REFERENCES `t_size` (`ID_size`) ON DELETE CASCADE,
  ADD CONSTRAINT `t_bikes_ibfk_3` FOREIGN KEY (`FK_color`) REFERENCES `t_color` (`ID_color`) ON DELETE CASCADE,
  ADD CONSTRAINT `t_bikes_ibfk_4` FOREIGN KEY (`FK_commune`) REFERENCES `t_communes` (`ID_commune`) ON DELETE CASCADE,
  ADD CONSTRAINT `t_bikes_ibfk_5` FOREIGN KEY (`FK_personne`) REFERENCES `t_personnes` (`ID_personne`) ON DELETE CASCADE;

--
-- Contraintes pour la table `t_personnes`
--
ALTER TABLE `t_personnes`
  ADD CONSTRAINT `t_personnes_ibfk_1` FOREIGN KEY (`FK_commune`) REFERENCES `t_communes` (`ID_commune`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
