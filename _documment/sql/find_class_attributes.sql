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
-- Table structure for table `find_class_attributes`
--

CREATE TABLE IF NOT EXISTS `find_class_attributes` (
`id_fcs` bigint(20) unsigned NOT NULL,
  `fcs_class` int(11) NOT NULL,
  `fcs_propriety` int(11) NOT NULL,
  `fcs_created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `fcs_agency` int(11) NOT NULL,
  `fcs_active` int(11) NOT NULL,
  `fcs_order` int(11) NOT NULL,
  `fcs_group` char(20) NOT NULL,
  `fcs_rule` int(11) NOT NULL,
  `fcs_range` int(11) NOT NULL
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=7 ;

--
-- Dumping data for table `find_class_attributes`
--

INSERT INTO `find_class_attributes` (`id_fcs`, `fcs_class`, `fcs_propriety`, `fcs_created`, `fcs_agency`, `fcs_active`, `fcs_order`, `fcs_group`, `fcs_rule`, `fcs_range`) VALUES
(1, 59, 67, '2017-03-20 00:31:36', 0, 0, 0, '', 0, 68),
(2, 59, 63, '2017-03-20 00:42:24', 0, 0, 0, '', 0, 64),
(3, 59, 69, '2017-03-20 00:47:12', 0, 0, 0, '', 0, 70),
(4, 59, 71, '2017-03-20 01:02:11', 0, 0, 0, '', 0, 73),
(5, 59, 74, '2017-03-20 01:05:36', 0, 0, 0, '', 0, 68),
(6, 59, 76, '2017-03-20 01:10:14', 0, 0, 0, '', 0, 77);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `find_class_attributes`
--
ALTER TABLE `find_class_attributes`
 ADD UNIQUE KEY `id_fcs` (`id_fcs`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `find_class_attributes`
--
ALTER TABLE `find_class_attributes`
MODIFY `id_fcs` bigint(20) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=7;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
