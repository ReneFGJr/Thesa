-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Tempo de geração: 11-Abr-2022 às 01:29
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
-- Estrutura da tabela `th_proprieties_prefix`
--

DROP TABLE IF EXISTS `th_proprieties_prefix`;
CREATE TABLE IF NOT EXISTS `th_proprieties_prefix` (
  `id_prefix` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `prefix_name` char(50) COLLATE utf8_bin NOT NULL,
  `prefix_url` char(100) COLLATE utf8_bin NOT NULL,
  UNIQUE KEY `id_prefix` (`id_prefix`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Extraindo dados da tabela `h_proprieties_prefix`
--

INSERT INTO `th_proprieties_prefix` (`id_prefix`, `prefix_name`, `prefix_url`) VALUES
(1, 'skos', ''),
(2, 'skosxl', '');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
