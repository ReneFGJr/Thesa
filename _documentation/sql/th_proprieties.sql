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
-- Estrutura da tabela `th_proprieties`
--

DROP TABLE IF EXISTS `th_proprieties`;
CREATE TABLE IF NOT EXISTS `th_proprieties` (
  `id_p` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `p_prefix` int(11) NOT NULL,
  `p_propriey` char(50) COLLATE utf8_bin NOT NULL,
  `p_custom` char(100) COLLATE utf8_bin NOT NULL,
  `p_url` char(100) COLLATE utf8_bin NOT NULL,
  `p_group` char(2) COLLATE utf8_bin NOT NULL,
  `p_order` int(11) NOT NULL DEFAULT '0',
  UNIQUE KEY `id_p` (`id_p`)
) ENGINE=MyISAM AUTO_INCREMENT=14 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Extraindo dados da tabela `th_proprieties`
--

INSERT INTO `th_proprieties` (`id_p`, `p_prefix`, `p_propriey`, `p_custom`, `p_url`, `p_group`, `p_order`) VALUES
(1, 1, 'broader', 'broader', 'http://www.w3.org/2004/02/skos/core#broader', 'TG', 0),
(2, 1, 'narrower', 'narrower', 'http://www.w3.org/2004/02/skos/core#narrower', 'TE', 0),
(3, 1, 'Concept', 'Concept', 'https://www.w3.org/2009/08/skos-reference/skos.html#Concept', 'C', 0),
(4, 1, 'ConceptScheme', 'ConceptScheme', 'https://www.w3.org/2009/08/skos-reference/skos.html#ConceptScheme', 'C', 0),
(5, 1, 'related', 'related', 'related', 'TR', 0),
(6, 1, 'changeNote', 'changeNote', '', 'NT', 5),
(7, 1, 'scopeNote', 'scopeNote', 'scopeNote', 'NT', 2),
(8, 1, 'example', 'example', 'example', 'NT', 3),
(9, 1, 'notation', 'notation', 'notation', 'AC', 1),
(10, 1, 'note', 'note', 'note', 'NT', 9),
(11, 1, 'definition', 'definition', 'definition', 'NT', 1),
(12, 1, 'editorialNote', 'editorialNote', 'editorialNote', 'NT', 4),
(13, 1, 'historyNote', 'historyNote', 'historyNote', 'NT', 7);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
