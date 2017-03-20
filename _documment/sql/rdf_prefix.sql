-- phpMyAdmin SQL Dump
-- version 4.5.4.1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Mar 20, 2017 at 08:46 PM
-- Server version: 5.7.11
-- PHP Version: 5.6.18

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `books`
--

-- --------------------------------------------------------

--
-- Table structure for table `rdf_prefix`
--

CREATE TABLE `rdf_prefix` (
  `id_prefix` bigint(20) UNSIGNED NOT NULL,
  `prefix_ref` char(30) NOT NULL,
  `prefix_url` char(250) NOT NULL,
  `prefix_ativo` int(11) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `rdf_prefix`
--

INSERT INTO `rdf_prefix` (`id_prefix`, `prefix_ref`, `prefix_url`, `prefix_ativo`) VALUES
(1, 'dc', 'http://purl.org/dc/elements/1.1/', 1),
(2, 'brapci', 'http://basessibi.c3sl.ufpr.br/brapci/index.php/rdf/', 1),
(3, 'rdfs', 'http://www.w3.org/2000/01/rdf-schema', 1),
(4, 'skos', 'http://www.w3.org/2004/02/skos/core', 1),
(5, 'dcterm', 'http://purl.org/dc/terms/', 1),
(6, 'fb', 'http://rdf.freebases.com/ns', 1),
(7, 'gn', 'http://www.geonames.org/ontology#', 1),
(8, 'geo', 'http://www.w3.org/2003/01/geo/wgs84_pos#', 1),
(9, 'lotico', 'http://www.lotico.com/ontology/', 1),
(10, 'bibo', 'http://purl.org/ontology/bibo/', 1),
(11, 'ebucore', 'http://www.ebu.ch/metadata/ontologies/ebucore/ebucore#', 1),
(12, 'rdf', 'http://www.w3.org/1999/02/22-rdf-syntax-ns#', 1),
(13, 'ex', 'ex??', 1),
(14, 'skosxl', '', 1),
(15, 'foaf', 'http://xmlns.com/foaf/spec/', 1),
(16, 'frbroo', '', 1),
(17, 'time', 'https://www.w3.org/TR/owl-time/', 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `rdf_prefix`
--
ALTER TABLE `rdf_prefix`
  ADD UNIQUE KEY `id_prefix` (`id_prefix`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `rdf_prefix`
--
ALTER TABLE `rdf_prefix`
  MODIFY `id_prefix` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
