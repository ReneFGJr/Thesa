-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Tempo de geração: 13-Abr-2022 às 15:43
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
-- Banco de dados: `thesa_skos`
--

-- --------------------------------------------------------

--
-- Estrutura da tabela `th_literal_th`
--

DROP TABLE IF EXISTS `th_literal_th`;
CREATE TABLE IF NOT EXISTS `th_literal_th` (
  `id_lt` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `lt_term` int(11) NOT NULL,
  `lt_th` int(11) NOT NULL,
  `lt_status` int(11) NOT NULL DEFAULT '1',
  UNIQUE KEY `id_lt` (`id_lt`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
