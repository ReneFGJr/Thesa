-- phpMyAdmin SQL Dump
-- version 4.2.7.1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Mar 20, 2017 at 10:23 AM
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
-- Table structure for table `find_rdf`
--

CREATE TABLE IF NOT EXISTS `find_rdf` (
`id_fr` bigint(20) unsigned NOT NULL,
  `fr_id_1` int(11) NOT NULL,
  `fr_id_2` int(11) NOT NULL,
  `fr_literal` int(11) NOT NULL,
  `fr_propriety` int(11) NOT NULL,
  `fr_created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `fr_agency` int(11) NOT NULL
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=6 ;

--
-- Dumping data for table `find_rdf`
--

INSERT INTO `find_rdf` (`id_fr`, `fr_id_1`, `fr_id_2`, `fr_literal`, `fr_propriety`, `fr_created`, `fr_agency`) VALUES
(1, 1, 0, 1, 1, '2017-03-19 22:44:50', 1),
(2, 2, 0, 2, 1, '2017-03-19 22:50:14', 1),
(3, 3, 0, 3, 1, '2017-03-19 22:50:29', 1),
(4, 1, 2, 0, 59, '2017-03-19 22:50:51', 1),
(5, 1, 3, 0, 59, '2017-03-19 22:50:54', 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `find_rdf`
--
ALTER TABLE `find_rdf`
 ADD UNIQUE KEY `id_fr` (`id_fr`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `find_rdf`
--
ALTER TABLE `find_rdf`
MODIFY `id_fr` bigint(20) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=6;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
