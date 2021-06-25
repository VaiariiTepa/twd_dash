-- phpMyAdmin SQL Dump
-- version 5.0.4
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : lun. 21 juin 2021 à 10:33
-- Version du serveur :  10.4.17-MariaDB
-- Version de PHP : 7.4.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `json_data_twd`
--
CREATE DATABASE IF NOT EXISTS `json_data_twd` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
USE `json_data_twd`;

-- --------------------------------------------------------

--
-- Structure de la table `data_twd`
--

CREATE TABLE `data_twd` (
  `rowid` int(11) NOT NULL,
  `ile` varchar(2) NOT NULL,
  `code_societe` varchar(255) NOT NULL,
  `date` date DEFAULT NULL,
  `y` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `data_twd_travel`
--

CREATE TABLE `data_twd_travel` (
  `rowid` int(11) NOT NULL,
  `code_societe` varchar(20) NOT NULL,
  `date` date NOT NULL,
  `ile` varchar(255) NOT NULL,
  `y` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `data_twd`
--
ALTER TABLE `data_twd`
  ADD PRIMARY KEY (`rowid`);

--
-- Index pour la table `data_twd_travel`
--
ALTER TABLE `data_twd_travel`
  ADD PRIMARY KEY (`rowid`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `data_twd`
--
ALTER TABLE `data_twd`
  MODIFY `rowid` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `data_twd_travel`
--
ALTER TABLE `data_twd_travel`
  MODIFY `rowid` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
