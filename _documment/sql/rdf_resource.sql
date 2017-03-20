-- phpMyAdmin SQL Dump
-- version 4.2.7.1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Mar 20, 2017 at 10:22 AM
-- Server version: 5.6.20-log
-- PHP Version: 5.4.31

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `books`
--

-- --------------------------------------------------------

--
-- Table structure for table `rdf_resource`
--

CREATE TABLE IF NOT EXISTS `rdf_resource` (
`id_rs` bigint(20) unsigned NOT NULL,
  `rs_prefix` int(11) NOT NULL,
  `rs_propriety` char(100) NOT NULL,
  `rs_propriety_inverse` char(100) NOT NULL,
  `rs_type` char(1) NOT NULL,
  `rs_mandatory` int(11) NOT NULL DEFAULT '0',
  `rs_marc` varchar(30) NOT NULL,
  `rs_group` varchar(10) NOT NULL,
  `rs_public` int(11) NOT NULL DEFAULT '0',
  `rs_source` char(255) NOT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=78 ;

--
-- Dumping data for table `rdf_resource`
--

INSERT INTO `rdf_resource` (`id_rs`, `rs_prefix`, `rs_propriety`, `rs_propriety_inverse`, `rs_type`, `rs_mandatory`, `rs_marc`, `rs_group`, `rs_public`, `rs_source`) VALUES
(1, 12, 'class', '', 'C', 0, '', '', 0, ''),
(2, 1, 'creator', '', 'P', 0, '', '', 0, ''),
(3, 1, 'subject', '', 'P', 0, '', '', 0, ''),
(4, 1, 'description', '', 'P', 0, '', '', 0, ''),
(5, 1, 'publisher', '', 'P', 0, '', '', 0, ''),
(6, 1, 'contributor', '', 'P', 0, '', '', 0, ''),
(7, 1, 'date', '', 'P', 0, '', '', 0, ''),
(8, 1, 'type', '', 'P', 0, '', '', 0, ''),
(9, 1, 'format', '', 'P', 0, '', '', 0, ''),
(10, 1, 'identifier', '', 'P', 0, '', '', 0, ''),
(11, 1, 'source', '', 'P', 0, '', '', 0, ''),
(12, 1, 'language', '', 'P', 0, '', '', 0, ''),
(13, 1, 'relation', '', 'P', 0, '', '', 0, ''),
(14, 1, 'coverage', '', 'P', 0, '', '', 0, ''),
(15, 1, 'rights', '', 'P', 0, '', '', 0, ''),
(16, 11, 'filename', '', 'P', 1, '', '', 0, ''),
(17, 11, 'fileSize', '', 'P', 1, '', '', 0, ''),
(18, 11, 'dateCreated', '', 'P', 1, '', '', 0, ''),
(19, 11, 'md5', '', 'P', 1, '', '', 0, ''),
(20, 11, 'hasMimeType', '', 'P', 1, '', '', 0, ''),
(21, 11, 'dateModified', '', 'P', 1, '', '', 0, ''),
(22, 4, 'Concept Scheme', '', 'C', 0, '', '', 0, ''),
(23, 4, 'Concept', '', 'C', 0, '', 'FRSAD', 0, ''),
(24, 12, 'type', '', 'P', 0, '', '', 0, ''),
(25, 4, 'prefLabel', '', 'P', 0, '650', 'LABEL', 1, ''),
(26, 4, 'broader', 'narrower', 'P', 0, '', '', 0, ''),
(27, 4, 'altLabel', '', 'P', 0, '', 'TE', 1, ''),
(28, 13, 'coordinationOf', '', 'P', 0, '', 'TR', 0, ''),
(29, 13, 'unionOf', '', 'P', 0, '', 'TR', 0, ''),
(30, 4, 'definition', '', 'P', 0, '', 'NT', 0, ''),
(31, 4, 'scopeNote', '', 'P', 0, '', 'NT', 0, ''),
(32, 4, 'example', '', 'P', 0, '', 'NT', 0, ''),
(33, 4, 'changeNote', '', 'P', 0, '', 'NT', 0, ''),
(34, 4, 'hiddenLabel', '', 'P', 0, '', 'TH', 0, ''),
(35, 14, 'isPlural', 'isSingular', 'P', 0, '', 'FE', 0, ''),
(36, 14, 'literalForm', 'abbreviation_of', 'P', 0, '', 'FE', 1, ''),
(37, 14, 'isSingular', 'isPlural', 'P', 0, '', 'FE', 0, ''),
(38, 14, 'isFeminine', 'isMasculine', 'P', 0, '', 'FE', 0, ''),
(39, 14, 'isMasculine', 'isFeminine', 'P', 0, '', 'FE', 0, ''),
(40, 14, 'abbreviation_of', 'literalForm', 'P', 0, '', 'FE', 1, ''),
(41, 14, 'produto_produtor', 'produtor_produto', 'P', 0, '', 'FE', 1, ''),
(42, 14, 'garantiaLiteraria', '', 'P', 0, '', 'NT', 1, ''),
(43, 14, 'noteCited', '', 'P', 0, '', 'NT', 1, ''),
(44, 14, 'code', '', 'P', 0, '', 'TE', 1, ''),
(45, 14, 'acronym', '', 'P', 0, '', 'TE', 0, ''),
(46, 15, 'Agent', '', 'C', 0, '', 'FOAF', 0, ''),
(47, 15, 'Document', '', 'C', 0, '', 'FOAF', 0, ''),
(48, 15, 'Group', '', 'C', 0, '', 'FOAF', 0, ''),
(49, 15, 'Image', '', 'C', 0, '', 'FOAF', 0, ''),
(50, 15, 'LabelProperty', '', 'C', 0, '', 'FOAF', 0, ''),
(51, 15, 'OnlineAccount', '', 'C', 0, '', 'FOAF', 0, ''),
(52, 15, 'OnlineChatAccount', '', 'C', 0, '', 'FOAF', 0, ''),
(53, 15, 'OnlineEcommerceAccount', '', 'C', 0, '', 'FOAF', 0, ''),
(54, 15, 'OnlineGamingAccount', '', 'C', 0, '', 'FOAF', 0, ''),
(55, 15, 'Organization', '', 'C', 0, '', 'FOAF', 1, ''),
(56, 15, 'Person', '', 'C', 0, '', 'FOAF', 1, ''),
(57, 15, 'PersonalProfileDocument', '', 'C', 0, '', 'FOAF', 0, ''),
(58, 15, 'Project', '', 'C', 0, '', 'FOAF', 1, ''),
(59, 16, 'Work', '', 'C', 0, '', 'ENTY', 0, ''),
(60, 16, 'Manifestation', '', 'C', 0, '', '', 0, ''),
(61, 16, 'Expression', '', 'C', 0, '', 'ENTY', 0, ''),
(62, 16, 'Item', '', 'C', 0, '', 'ENTY', 0, ''),
(63, 16, 'has_date_of_the_work', '', 'P', 0, '', '', 0, ''),
(64, 17, 'Year', '', 'C', 0, '', 'TIME', 1, ''),
(65, 16, 'has_place_of_publication', '', 'P', 0, '', '', 0, ''),
(66, 16, 'has_date_of_publication', '', 'P', 0, '', '', 0, ''),
(67, 16, 'has_title', 'is_title_of', 'P', 0, '', '', 0, ''),
(68, 16, 'Nomen', '', 'C', 0, '', '', 0, ''),
(69, 16, 'has_form_of_work', '', 'p', 0, '', '', 0, ''),
(70, 16, 'Form', '', 'C', 0, '', '', 0, ''),
(71, 16, 'has_medium_of_performance', '', 'P', 0, '', '', 0, ''),
(72, 16, 'has_subject_of_the_work', '', 'P', 0, '', '', 0, ''),
(73, 16, 'Medium_of_performance', '', 'C', 0, '', '', 0, ''),
(74, 16, 'has_numeric_designation', '', 'P', 0, '', '', 0, ''),
(75, 16, 'Numeric_designation', '', 'C', 0, '', '', 0, ''),
(76, 16, 'has_place_of_origin_of_the_work', '', 'P', 0, '', '', 0, ''),
(77, 16, 'Place', '', 'C', 0, '', '', 0, '');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `rdf_resource`
--
ALTER TABLE `rdf_resource`
 ADD UNIQUE KEY `id_rs` (`id_rs`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `rdf_resource`
--
ALTER TABLE `rdf_resource`
MODIFY `id_rs` bigint(20) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=78;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
