-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Tempo de geração: 10-Abr-2022 às 11:53
-- Versão do servidor: 5.7.31
-- versão do PHP: 7.3.21

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `thesa`
--

-- --------------------------------------------------------

--
-- Estrutura da tabela `th_users`
--

DROP TABLE IF EXISTS `th_users`;
CREATE TABLE IF NOT EXISTS `th_users` (
  `id_ust` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `ust_user_id` int(11) NOT NULL,
  `ust_user_role` int(11) NOT NULL,
  `ust_th` int(11) NOT NULL,
  `ust_status` int(11) NOT NULL,
  `ust_created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `ust_tipo` int(11) NOT NULL DEFAULT '1',
  UNIQUE KEY `id_ust` (`id_ust`),
  KEY `ust_user_id` (`ust_user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
