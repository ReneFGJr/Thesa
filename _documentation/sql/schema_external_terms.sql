-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Tempo de geração: 10-Abr-2022 às 13:46
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
-- Estrutura da tabela `th_concept_term`
--

DROP TABLE IF EXISTS `th_concept_term`;
CREATE TABLE IF NOT EXISTS `th_concept_term` (
  `id_ct` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `ct_concept` int(11) NOT NULL,
  `ct_th` int(11) NOT NULL,
  `ct_term` int(11) NOT NULL,
  `ct_use` int(11) NOT NULL DEFAULT '0',
  `ct_propriety` int(11) NOT NULL,
  `ct_created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  UNIQUE KEY `id_ct` (`id_ct`),
  KEY `ct_term` (`ct_term`),
  KEY `ct_th` (`ct_th`),
  KEY `ct_th_term` (`ct_term`,`ct_th`),
  KEY `ct_concept` (`ct_concept`,`ct_use`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
