-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Tempo de geração: 13-Abr-2022 às 15:44
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
-- Estrutura da tabela `th_note`
--

DROP TABLE IF EXISTS `th_note`;
CREATE TABLE IF NOT EXISTS `th_note` (
  `id_nt` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `nt_prop` int(11) DEFAULT NULL,
  `nt_term` int(11) DEFAULT NULL,
  `nt_th` int(11) DEFAULT NULL,
  `nt_user` int(11) DEFAULT NULL,
  `nt_contentet` text COLLATE utf8_bin,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `update_at` timestamp NULL DEFAULT NULL,
  UNIQUE KEY `id_nt` (`id_nt`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
