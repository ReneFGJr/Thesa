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
-- Estrutura da tabela `th_users_perfil`
--

DROP TABLE IF EXISTS `th_users_perfil`;
CREATE TABLE IF NOT EXISTS `th_users_perfil` (
  `id_up` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `up_tipo` char(100) NOT NULL,
  `up_order` int(11) NOT NULL DEFAULT '0',
  UNIQUE KEY `id_up` (`id_up`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

--
-- Extraindo dados da tabela `th_users_perfil`
--

INSERT INTO `th_users_perfil` (`id_up`, `up_tipo`, `up_order`) VALUES
(1, 'Author', 1),
(2, 'Orientador', 2),
(3, 'Colaborador', 3);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
