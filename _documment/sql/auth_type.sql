-- phpMyAdmin SQL Dump
-- version 4.2.7.1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Mar 04, 2017 at 08:19 AM
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
-- Table structure for table `auth_type`
--

CREATE TABLE IF NOT EXISTS `auth_type` (
`id_ty` bigint(20) unsigned NOT NULL,
  `ty_name` char(100) NOT NULL,
  `ty_notation` char(20) NOT NULL,
  `ty_active` int(11) NOT NULL DEFAULT '1'
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `auth_type`
--

INSERT INTO `auth_type` (`id_ty`, `ty_name`, `ty_notation`, `ty_active`) VALUES
(1, 'Personal', 'person', 1),
(2, 'Corporate body', 'coletive', 1),
(3, 'Family Name', 'family', 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `auth_type`
--
ALTER TABLE `auth_type`
 ADD UNIQUE KEY `id_ty` (`id_ty`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `auth_type`
--
ALTER TABLE `auth_type`
MODIFY `id_ty` bigint(20) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=4;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
