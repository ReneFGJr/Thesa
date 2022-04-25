-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Tempo de geração: 25-Abr-2022 às 15:04
-- Versão do servidor: 5.7.36
-- versão do PHP: 7.4.26

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `thesa2`
--

-- --------------------------------------------------------

--
-- Estrutura da tabela `th_concept_image_type`
--

DROP TABLE IF EXISTS `th_concept_image_type`;
CREATE TABLE IF NOT EXISTS `th_concept_image_type` (
  `id_tcit` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `tcit_description` char(100) COLLATE utf8_bin NOT NULL,
  `tcit_contenttype` char(50) COLLATE utf8_bin NOT NULL,
  `tcit_player` char(100) COLLATE utf8_bin NOT NULL,
  `tcit_th` int(11) NOT NULL DEFAULT '0',
  UNIQUE KEY `id_tcit` (`id_tcit`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Extraindo dados da tabela `th_concept_image_type`
--

INSERT INTO `th_concept_image_type` (`id_tcit`, `tcit_description`, `tcit_contenttype`, `tcit_player`, `tcit_th`) VALUES
(1, 'thesa.images_type', 'image/png', '<img src=\"#url\" class=\"img-fluid\">', 0),
(2, 'thesa.images_type', 'image/jpeg', '<img src=\"#url\" class=\"img-fluid\">', 0);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
