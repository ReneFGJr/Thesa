-- phpMyAdmin SQL Dump
-- version 4.4.10
-- http://www.phpmyadmin.net
--
-- Host: bdlivre.ufrgs.br
-- Generation Time: Nov 13, 2019 at 04:44 PM
-- Server version: 5.5.31
-- PHP Version: 5.6.20

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `tesauros`
--

-- --------------------------------------------------------

--
-- Table structure for table `th_users_perfil`
--

CREATE TABLE IF NOT EXISTS `th_users_perfil` (
  `id_up` bigint(20) unsigned NOT NULL,
  `up_tipo` char(100) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `th_users_perfil`
--

INSERT INTO `th_users_perfil` (`id_up`, `up_tipo`) VALUES
(1, 'Autor'),
(2, 'Orientador'),
(3, 'Colaborador');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `th_users_perfil`
--
ALTER TABLE `th_users_perfil`
  ADD UNIQUE KEY `id_up` (`id_up`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `th_users_perfil`
--
ALTER TABLE `th_users_perfil`
  MODIFY `id_up` bigint(20) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=4;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
