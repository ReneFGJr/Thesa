-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Tempo de geração: 24/04/2025 às 11:31
-- Versão do servidor: 8.3.0
-- Versão do PHP: 8.2.18

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
-- Estrutura para tabela `thesa_exactmatch`
--

DROP TABLE IF EXISTS `thesa_exactmatch`;
CREATE TABLE IF NOT EXISTS `thesa_exactmatch` (
  `id_em` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `em_concept` int NOT NULL,
  `em_link` varchar(150) NOT NULL,
  `em_type` int NOT NULL,
  `em_lastupdate` timestamp NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  UNIQUE KEY `id_em` (`id_em`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estrutura para tabela `thesa_linked_data`
--

DROP TABLE IF EXISTS `thesa_linked_data`;
CREATE TABLE IF NOT EXISTS `thesa_linked_data` (
  `id_ld` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `ld_concept` int NOT NULL,
  `ld_url` char(150) NOT NULL,
  `ld_source` int NOT NULL,
  `ld_visible` int NOT NULL DEFAULT '0',
  `created_atualmente` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  UNIQUE KEY `id_ld` (`id_ld`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4;

--
-- Despejando dados para a tabela `thesa_linked_data`
--

INSERT INTO `thesa_linked_data` (`id_ld`, `ld_concept`, `ld_url`, `ld_source`, `ld_visible`, `created_atualmente`) VALUES
(1, 322, 'https://pt.wikipedia.org/wiki/Anan%C3%A1s', 1, 1, '2025-04-24 10:36:41');

-- --------------------------------------------------------

--
-- Estrutura para tabela `thesa_linked_data_source`
--

DROP TABLE IF EXISTS `thesa_linked_data_source`;
CREATE TABLE IF NOT EXISTS `thesa_linked_data_source` (
  `id_lds` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `lds_icone` char(100) NOT NULL,
  `lds_name` char(100) NOT NULL,
  `lds_url` char(100) NOT NULL,
  UNIQUE KEY `lds` (`id_lds`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4;

--
-- Despejando dados para a tabela `thesa_linked_data_source`
--

INSERT INTO `thesa_linked_data_source` (`id_lds`, `lds_icone`, `lds_name`, `lds_url`) VALUES
(1, '/assets/icone/icone_wikipedia.png', 'Wikipédia', 'https://pt.wikipedia.org/');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
