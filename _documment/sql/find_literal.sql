-- phpMyAdmin SQL Dump
-- version 4.2.7.1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Mar 20, 2017 at 10:24 AM
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
-- Table structure for table `find_literal`
--

CREATE TABLE IF NOT EXISTS `find_literal` (
`id_l` bigint(20) unsigned NOT NULL,
  `l_value` text NOT NULL,
  `l_language` char(5) NOT NULL
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `find_literal`
--

INSERT INTO `find_literal` (`id_l`, `l_value`, `l_language`) VALUES
(1, 'catalogação no plural', 'pt_BR'),
(2, 'Eliana Serrão Alves Mey', 'pt_BR'),
(3, 'Naira Christofoletti Silveira', 'pt_BR');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `find_literal`
--
ALTER TABLE `find_literal`
 ADD UNIQUE KEY `id_l` (`id_l`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `find_literal`
--
ALTER TABLE `find_literal`
MODIFY `id_l` bigint(20) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=4;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
