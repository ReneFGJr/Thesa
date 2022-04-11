-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Tempo de geração: 10-Abr-2022 às 13:44
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
-- Estrutura da tabela `th_concept`
--

DROP TABLE IF EXISTS `th_concept`;
CREATE TABLE IF NOT EXISTS `th_concept` (
  `id_c` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `c_th` int(11) NOT NULL,
  `c_concept` char(40) NOT NULL,
  `c_created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `c_agency` int(11) NOT NULL DEFAULT '1',
  `c_ativo` int(11) NOT NULL DEFAULT '1',
  UNIQUE KEY `id_c` (`id_c`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
