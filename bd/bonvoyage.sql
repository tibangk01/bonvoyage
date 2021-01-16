-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le :  Dim 23 juin 2019 à 13:25
-- Version du serveur :  10.1.38-MariaDB
-- Version de PHP :  7.3.2

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données :  `bonvoyage`
--

-- --------------------------------------------------------

--
-- Structure de la table `annuler`
--

CREATE TABLE `annuler` (
  `numcaiss` int(11) NOT NULL,
  `numTicket` int(11) NOT NULL,
  `dateAnnTicket` datetime DEFAULT NULL,
  `montantPenalite` double DEFAULT NULL,
  `codeAnnTicket` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `annuler`
--

INSERT INTO `annuler` (`numcaiss`, `numTicket`, `dateAnnTicket`, `montantPenalite`, `codeAnnTicket`) VALUES
(1, 3, '2019-06-07 20:58:45', 57, '2019-TA-3'),
(1, 7, '2019-06-07 21:11:52', 2463.75, '2019-TA-7'),
(1, 8, '2019-06-07 21:23:13', 1552.5, '2019-TA-8');

-- --------------------------------------------------------

--
-- Structure de la table `avoir`
--

CREATE TABLE `avoir` (
  `distVilleEscaleVilleDepartTrajet` int(11) DEFAULT NULL,
  `numVilleEscale` int(11) NOT NULL,
  `numTrajet` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `avoir`
--

INSERT INTO `avoir` (`distVilleEscaleVilleDepartTrajet`, `numVilleEscale`, `numTrajet`) VALUES
(623, 1, 1),
(657, 2, 1),
(414, 3, 1),
(38, 4, 1),
(94, 5, 1),
(128, 6, 1),
(161, 7, 1),
(287, 8, 1),
(340, 9, 1),
(549, 10, 1),
(38, 11, 2),
(249, 12, 2),
(324, 13, 2),
(506, 14, 2),
(663, 15, 2);

-- --------------------------------------------------------

--
-- Structure de la table `bus`
--

CREATE TABLE `bus` (
  `numBus` int(11) NOT NULL,
  `marqueBus` varchar(30) DEFAULT NULL,
  `nbPlacesBus` int(2) DEFAULT NULL,
  `statutBus` tinyint(1) DEFAULT '1',
  `dateModifBus` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `bus`
--

INSERT INTO `bus` (`numBus`, `marqueBus`, `nbPlacesBus`, `statutBus`, `dateModifBus`) VALUES
(1, 'LIU-LAO', 15, 1, '2019-05-10 15:27:10'),
(2, 'MAZDA', 65, 1, '2019-05-10 15:27:18'),
(3, 'KING-LUNG', 54, 1, '2019-05-10 15:27:25'),
(4, 'TOYOTA', 52, 1, '2019-05-10 15:27:30'),
(5, 'MAZDA', 15, 1, '2019-05-10 15:27:36'),
(6, 'LIU-LAO', 53, 1, '2019-05-10 15:27:49'),
(7, 'KUNGLONG', 52, 1, '2019-05-10 15:28:10');

-- --------------------------------------------------------

--
-- Structure de la table `caissiers`
--

CREATE TABLE `caissiers` (
  `numCaiss` int(11) NOT NULL,
  `nomCaiss` varchar(25) DEFAULT NULL,
  `prenomCaiss` varchar(35) DEFAULT NULL,
  `telCaiss` varchar(20) DEFAULT NULL,
  `identifiantCaiss` varchar(30) DEFAULT NULL,
  `mdpCaiss` varchar(30) DEFAULT NULL,
  `dateCreationCaiss` datetime DEFAULT NULL,
  `statutCaiss` tinyint(1) DEFAULT '1',
  `numRes` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `caissiers`
--

INSERT INTO `caissiers` (`numCaiss`, `nomCaiss`, `prenomCaiss`, `telCaiss`, `identifiantCaiss`, `mdpCaiss`, `dateCreationCaiss`, `statutCaiss`, `numRes`) VALUES
(1, 'JOHN', 'DOE', '+22890909090', 'johndoe', 'azertyuiop', '2019-05-10 15:30:16', 1, 1),
(2, 'MOE', 'MARRY', '98989898', 'marrymoe', 'azertyuiop', '2019-05-10 15:31:34', 1, 1);

-- --------------------------------------------------------

--
-- Structure de la table `enregistrer`
--

CREATE TABLE `enregistrer` (
  `numcaiss` int(11) NOT NULL,
  `numTicket` int(11) NOT NULL,
  `dateEmission` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `enregistrer`
--

INSERT INTO `enregistrer` (`numcaiss`, `numTicket`, `dateEmission`) VALUES
(1, 1, '2019-05-09 16:08:25'),
(2, 2, '2019-05-10 17:07:33'),
(1, 3, '2019-05-13 02:09:11'),
(1, 4, '2019-06-07 20:57:33'),
(1, 5, '2019-06-07 21:00:22'),
(1, 6, '2019-06-07 21:00:53'),
(1, 7, '2019-06-07 21:10:20'),
(1, 8, '2019-06-07 21:22:25');

-- --------------------------------------------------------

--
-- Structure de la table `responsable`
--

CREATE TABLE `responsable` (
  `numRes` int(11) NOT NULL,
  `nomRes` varchar(25) DEFAULT NULL,
  `prenomRes` varchar(35) DEFAULT NULL,
  `identifiantRes` varchar(30) DEFAULT NULL,
  `mdpRes` varchar(30) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `responsable`
--

INSERT INTO `responsable` (`numRes`, `nomRes`, `prenomRes`, `identifiantRes`, `mdpRes`) VALUES
(1, 'roottoor', 'roottoor', 'roottoor', 'roottoor');

-- --------------------------------------------------------

--
-- Structure de la table `tarifs`
--

CREATE TABLE `tarifs` (
  `numTarif` int(11) NOT NULL,
  `valeurTarif` decimal(8,2) DEFAULT NULL,
  `numRes` int(11) NOT NULL,
  `dateCreationTarif` date DEFAULT NULL,
  `statutTarif` tinyint(1) DEFAULT '1',
  `dateModifTarif` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `tarifs`
--

INSERT INTO `tarifs` (`numTarif`, `valeurTarif`, `numRes`, `dateCreationTarif`, `statutTarif`, `dateModifTarif`) VALUES
(1, '10.00', 1, '2019-05-10', 1, '2019-05-10 15:33:43'),
(2, '15.00', 1, '2019-05-10', 1, '2019-05-10 15:33:48'),
(3, '25.00', 1, '2019-05-10', 1, '2019-05-10 15:33:52'),
(4, '20.00', 1, '2019-05-10', 1, '2019-05-10 15:33:58'),
(5, '45.00', 1, '2019-05-10', 1, '2019-05-10 15:34:20'),
(6, '50.00', 1, '2019-05-10', 1, '2019-05-10 15:34:39');

-- --------------------------------------------------------

--
-- Structure de la table `ticket`
--

CREATE TABLE `ticket` (
  `numTicket` int(11) NOT NULL,
  `prenomPassager` varchar(35) DEFAULT NULL,
  `nomPassager` varchar(25) DEFAULT NULL,
  `telPassager` varchar(20) DEFAULT NULL,
  `statutTicket` tinyint(1) DEFAULT '1',
  `numVoyage` int(11) NOT NULL,
  `numVilleEscale` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `ticket`
--

INSERT INTO `ticket` (`numTicket`, `prenomPassager`, `nomPassager`, `telPassager`, `statutTicket`, `numVoyage`, `numVilleEscale`) VALUES
(1, 'Donald', 'Trump', '+1 100 120 100', 1, 1, 4),
(2, 'fefe', 'efe', '+546', 1, 1, 1),
(3, 'Poutine', 'vladmir', '22 22 22 22', 0, 2, 11),
(4, 'kwatcha', 'ama', '96000088', 1, 1, 2),
(5, 'Donald', 'Trump', '+543535', 1, 2, 13),
(6, 'vladmir', 'poutine', '+79565656', 1, 2, 14),
(7, 'nam', 'kim', '90471528', 0, 1, 2),
(8, 'hussain', 'obama', '+1 45 45 81 45', 0, 1, 3);

-- --------------------------------------------------------

--
-- Structure de la table `trajets`
--

CREATE TABLE `trajets` (
  `numTrajet` int(11) NOT NULL,
  `libVilleDepTrajet` varchar(20) DEFAULT NULL,
  `libVilleArrTrajet` varchar(20) DEFAULT NULL,
  `distTotalTrajet` double DEFAULT NULL,
  `dateModifTrajet` datetime DEFAULT NULL,
  `statutTrajet` tinyint(1) DEFAULT '1',
  `numRes` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `trajets`
--

INSERT INTO `trajets` (`numTrajet`, `libVilleDepTrajet`, `libVilleArrTrajet`, `distTotalTrajet`, `dateModifTrajet`, `statutTrajet`, `numRes`) VALUES
(1, 'LOME', 'CINKANSSE', 663, '2019-05-10 15:49:19', 1, 1),
(2, 'CINKANSSE', 'LOME', 663, '2019-05-10 15:38:37', 1, 1);

-- --------------------------------------------------------

--
-- Structure de la table `villes_escales`
--

CREATE TABLE `villes_escales` (
  `numVilleEscale` int(11) NOT NULL,
  `libVilleEscale` varchar(20) DEFAULT NULL,
  `statutVilleEscale` tinyint(1) DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `villes_escales`
--

INSERT INTO `villes_escales` (`numVilleEscale`, `libVilleEscale`, `statutVilleEscale`) VALUES
(1, 'DAPAONG', 1),
(2, 'CINKANSSE', 1),
(3, 'KARA', 1),
(4, 'TSEVIE', 1),
(5, 'NOTSE', 1),
(6, 'KPALIME', 1),
(7, 'ATAKPAME', 1),
(8, 'SOTOUBOA', 1),
(9, 'SOKODE', 1),
(10, 'MANGO', 1),
(11, 'DAPAONG', 1),
(12, 'KARA', 1),
(13, 'SOKODE', 1),
(14, 'ATAKPAME', 1),
(15, 'LOME', 1);

-- --------------------------------------------------------

--
-- Structure de la table `voyages`
--

CREATE TABLE `voyages` (
  `numVoyage` int(11) NOT NULL,
  `nbPlacesDispo` int(11) DEFAULT NULL,
  `dateVoyage` date DEFAULT NULL,
  `dateModifVoyage` datetime DEFAULT NULL,
  `dateCreation` datetime DEFAULT NULL,
  `statutVoyage` tinyint(1) DEFAULT '1',
  `numRes` int(11) NOT NULL,
  `numTrajet` int(11) NOT NULL,
  `numBus` int(11) NOT NULL,
  `numTarif` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `voyages`
--

INSERT INTO `voyages` (`numVoyage`, `nbPlacesDispo`, `dateVoyage`, `dateModifVoyage`, `dateCreation`, `statutVoyage`, `numRes`, `numTrajet`, `numBus`, `numTarif`) VALUES
(1, 12, '2019-07-02', '2019-05-09 16:07:07', '2019-05-09 16:07:07', 1, 1, 1, 1, 3),
(2, 13, '2019-07-31', '2019-05-09 16:23:47', '2019-05-09 16:23:47', 1, 1, 2, 1, 1);

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `annuler`
--
ALTER TABLE `annuler`
  ADD UNIQUE KEY `numAnn` (`codeAnnTicket`),
  ADD UNIQUE KEY `numAnnTicket` (`codeAnnTicket`),
  ADD UNIQUE KEY `codeAnnTicket` (`codeAnnTicket`),
  ADD KEY `fk_caissier_annuler` (`numcaiss`),
  ADD KEY `fk_ticket_annuler` (`numTicket`);

--
-- Index pour la table `avoir`
--
ALTER TABLE `avoir`
  ADD KEY `fk_villeEscale_avoir` (`numVilleEscale`),
  ADD KEY `fk_trajet_avoir` (`numTrajet`);

--
-- Index pour la table `bus`
--
ALTER TABLE `bus`
  ADD PRIMARY KEY (`numBus`);

--
-- Index pour la table `caissiers`
--
ALTER TABLE `caissiers`
  ADD PRIMARY KEY (`numCaiss`),
  ADD KEY `fk_responsable_caissiers` (`numRes`);

--
-- Index pour la table `enregistrer`
--
ALTER TABLE `enregistrer`
  ADD KEY `fk_caissier_enregistrer` (`numcaiss`),
  ADD KEY `fk_ticket_enregistrer` (`numTicket`);

--
-- Index pour la table `responsable`
--
ALTER TABLE `responsable`
  ADD PRIMARY KEY (`numRes`);

--
-- Index pour la table `tarifs`
--
ALTER TABLE `tarifs`
  ADD PRIMARY KEY (`numTarif`),
  ADD KEY `fk_responsable_tarif` (`numRes`);

--
-- Index pour la table `ticket`
--
ALTER TABLE `ticket`
  ADD PRIMARY KEY (`numTicket`),
  ADD KEY `fk_voyage` (`numVoyage`),
  ADD KEY `fk_ve` (`numVilleEscale`);

--
-- Index pour la table `trajets`
--
ALTER TABLE `trajets`
  ADD PRIMARY KEY (`numTrajet`),
  ADD KEY `fk_responsable_trajets` (`numRes`);

--
-- Index pour la table `villes_escales`
--
ALTER TABLE `villes_escales`
  ADD PRIMARY KEY (`numVilleEscale`);

--
-- Index pour la table `voyages`
--
ALTER TABLE `voyages`
  ADD PRIMARY KEY (`numVoyage`),
  ADD KEY `fk_responsable_voyages` (`numRes`),
  ADD KEY `fk_trajets_voyages` (`numTrajet`),
  ADD KEY `fk_bus_voyages` (`numBus`),
  ADD KEY `fk_tarifs_voyages` (`numTarif`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `bus`
--
ALTER TABLE `bus`
  MODIFY `numBus` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT pour la table `caissiers`
--
ALTER TABLE `caissiers`
  MODIFY `numCaiss` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT pour la table `responsable`
--
ALTER TABLE `responsable`
  MODIFY `numRes` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT pour la table `tarifs`
--
ALTER TABLE `tarifs`
  MODIFY `numTarif` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT pour la table `ticket`
--
ALTER TABLE `ticket`
  MODIFY `numTicket` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT pour la table `trajets`
--
ALTER TABLE `trajets`
  MODIFY `numTrajet` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT pour la table `villes_escales`
--
ALTER TABLE `villes_escales`
  MODIFY `numVilleEscale` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT pour la table `voyages`
--
ALTER TABLE `voyages`
  MODIFY `numVoyage` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `annuler`
--
ALTER TABLE `annuler`
  ADD CONSTRAINT `fk_caissier_annuler` FOREIGN KEY (`numcaiss`) REFERENCES `caissiers` (`numCaiss`),
  ADD CONSTRAINT `fk_ticket_annuler` FOREIGN KEY (`numTicket`) REFERENCES `ticket` (`numTicket`);

--
-- Contraintes pour la table `avoir`
--
ALTER TABLE `avoir`
  ADD CONSTRAINT `fk_trajet_avoir` FOREIGN KEY (`numTrajet`) REFERENCES `trajets` (`numTrajet`),
  ADD CONSTRAINT `fk_villeEscale_avoir` FOREIGN KEY (`numVilleEscale`) REFERENCES `villes_escales` (`numVilleEscale`);

--
-- Contraintes pour la table `caissiers`
--
ALTER TABLE `caissiers`
  ADD CONSTRAINT `fk_responsable_caissiers` FOREIGN KEY (`numRes`) REFERENCES `responsable` (`numRes`);

--
-- Contraintes pour la table `enregistrer`
--
ALTER TABLE `enregistrer`
  ADD CONSTRAINT `fk_caissier_enregistrer` FOREIGN KEY (`numcaiss`) REFERENCES `caissiers` (`numCaiss`),
  ADD CONSTRAINT `fk_ticket_enregistrer` FOREIGN KEY (`numTicket`) REFERENCES `ticket` (`numTicket`);

--
-- Contraintes pour la table `tarifs`
--
ALTER TABLE `tarifs`
  ADD CONSTRAINT `fk_responsable_tarif` FOREIGN KEY (`numRes`) REFERENCES `responsable` (`numRes`);

--
-- Contraintes pour la table `ticket`
--
ALTER TABLE `ticket`
  ADD CONSTRAINT `fk_ve` FOREIGN KEY (`numVilleEscale`) REFERENCES `villes_escales` (`numVilleEscale`),
  ADD CONSTRAINT `fk_voyage` FOREIGN KEY (`numVoyage`) REFERENCES `voyages` (`numVoyage`);

--
-- Contraintes pour la table `trajets`
--
ALTER TABLE `trajets`
  ADD CONSTRAINT `fk_responsable_trajets` FOREIGN KEY (`numRes`) REFERENCES `responsable` (`numRes`);

--
-- Contraintes pour la table `voyages`
--
ALTER TABLE `voyages`
  ADD CONSTRAINT `fk_bus_voyages` FOREIGN KEY (`numBus`) REFERENCES `bus` (`numBus`),
  ADD CONSTRAINT `fk_responsable_voyages` FOREIGN KEY (`numRes`) REFERENCES `responsable` (`numRes`),
  ADD CONSTRAINT `fk_tarifs_voyages` FOREIGN KEY (`numTarif`) REFERENCES `tarifs` (`numTarif`),
  ADD CONSTRAINT `fk_trajets_voyages` FOREIGN KEY (`numTrajet`) REFERENCES `trajets` (`numTrajet`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
