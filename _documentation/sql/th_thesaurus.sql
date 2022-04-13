-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Tempo de geração: 10-Abr-2022 às 10:58
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
-- Estrutura da tabela `th_thesaurus`
--

DROP TABLE IF EXISTS `th_thesaurus`;
CREATE TABLE IF NOT EXISTS `th_thesaurus` (
  `id_pa` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `pa_name` char(150) NOT NULL,
  `pa_achronic` char(100) NOT NULL,
  `pa_description` text NOT NULL,
  `pa_status` int(11) NOT NULL,
  `pa_created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `pa_update` date NOT NULL,
  `pa_classe` int(11) NOT NULL,
  `pa_creator` int(11) NOT NULL,
  `pa_hidden` int(11) NOT NULL DEFAULT '0',
  `pa_avaliacao` double NOT NULL,
  `pa_class` char(60) NOT NULL,
  `pa_version` varchar(10) NOT NULL,
  `pa_introdution` longtext NOT NULL,
  `pa_methodology` longtext NOT NULL,
  `pa_icone` char(50) NOT NULL,
  `pa_header` char(100) NOT NULL,
  `pa_audience` text NOT NULL,
  `pa_type` int(11) NOT NULL DEFAULT '1',
  `pa_language` int(11) NOT NULL DEFAULT '364',
  `pa_multi_language` int(11) NOT NULL DEFAULT '0',
  UNIQUE KEY `id_pa` (`id_pa`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
