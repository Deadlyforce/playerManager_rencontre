-- phpMyAdmin SQL Dump
-- version 4.1.14
-- http://www.phpmyadmin.net
--
-- Client :  127.0.0.1
-- Généré le :  Ven 05 Décembre 2014 à 17:13
-- Version du serveur :  5.6.17
-- Version de PHP :  5.5.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de données :  `playermanager`
--

-- --------------------------------------------------------

--
-- Structure de la table `girls`
--

CREATE TABLE IF NOT EXISTS `girls` (
  `id_girl` int(11) NOT NULL AUTO_INCREMENT,
  `id` int(10) NOT NULL,
  `id_from` int(12) NOT NULL,
  `date_last_message` datetime NOT NULL,
  `pseudo` varchar(100) NOT NULL,
  `description` text NOT NULL,
  `shopping_list` text NOT NULL,
  `age` int(2) NOT NULL,
  `ville` varchar(100) NOT NULL,
  `pays` varchar(100) NOT NULL,
  `thumbnail` varchar(255) NOT NULL,
  `photo` varchar(255) NOT NULL,
  PRIMARY KEY (`id_girl`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Structure de la table `girls_details`
--

CREATE TABLE IF NOT EXISTS `girls_details` (
  `id_girls_details` int(3) NOT NULL AUTO_INCREMENT,
  `id_from` int(12) NOT NULL,
  `yeux` varchar(255) NOT NULL,
  `cheveux` varchar(255) NOT NULL,
  `mensurations` varchar(255) NOT NULL,
  `taille` int(3) NOT NULL COMMENT 'cm',
  `silhouette` varchar(255) NOT NULL,
  `style` varchar(255) NOT NULL,
  `origines` varchar(255) NOT NULL,
  `hobbies` varchar(255) NOT NULL,
  `profession` varchar(255) NOT NULL,
  `alcool` varchar(255) NOT NULL,
  `tabac` varchar(255) NOT NULL,
  `alim` varchar(255) NOT NULL,
  `manger` varchar(255) NOT NULL,
  `signes` varchar(255) NOT NULL,
  PRIMARY KEY (`id_girls_details`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Structure de la table `girls_gouts`
--

CREATE TABLE IF NOT EXISTS `girls_gouts` (
  `id_girls_gouts` int(3) NOT NULL AUTO_INCREMENT,
  `id_from` int(12) NOT NULL,
  `musique` varchar(255) NOT NULL,
  `livres` varchar(255) NOT NULL,
  `cine` varchar(255) NOT NULL,
  `tv` varchar(255) NOT NULL,
  PRIMARY KEY (`id_girls_gouts`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Structure de la table `girls_photos`
--

CREATE TABLE IF NOT EXISTS `girls_photos` (
  `id_girls_photos` int(3) NOT NULL AUTO_INCREMENT,
  `id_from` int(12) NOT NULL,
  `photo_0` varchar(255) NOT NULL,
  `photo_1` varchar(255) NOT NULL,
  `photo_2` varchar(255) NOT NULL,
  `photo_3` varchar(255) NOT NULL,
  `photo_4` varchar(255) NOT NULL,
  `photo_5` varchar(255) NOT NULL,
  `photo_6` varchar(255) NOT NULL,
  `photo_7` varchar(255) NOT NULL,
  `photo_8` varchar(255) NOT NULL,
  `photo_9` varchar(255) NOT NULL,
  `photoname_0` varchar(255) NOT NULL,
  `photoname_1` varchar(255) NOT NULL,
  `photoname_2` varchar(255) NOT NULL,
  `photoname_3` varchar(255) NOT NULL,
  `photoname_4` varchar(255) NOT NULL,
  `photoname_5` varchar(255) NOT NULL,
  `photoname_6` varchar(255) NOT NULL,
  `photoname_7` varchar(255) NOT NULL,
  `photoname_8` varchar(255) NOT NULL,
  `photoname_9` varchar(255) NOT NULL,
  PRIMARY KEY (`id_girls_photos`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
