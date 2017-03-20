-- phpMyAdmin SQL Dump
-- version 4.5.4.1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Mar 20, 2017 at 08:47 PM
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
-- Table structure for table `find_class_attributes`
--

CREATE TABLE `find_class_attributes` (
  `id_fcs` bigint(20) UNSIGNED NOT NULL,
  `fcs_class` int(11) NOT NULL,
  `fcs_propriety` int(11) NOT NULL,
  `fcs_created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `fcs_agency` int(11) NOT NULL,
  `fcs_active` int(11) NOT NULL,
  `fcs_order` int(11) NOT NULL,
  `fcs_group` char(20) NOT NULL,
  `fcs_rule` int(11) NOT NULL,
  `fcs_range` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `find_class_attributes`
--

INSERT INTO `find_class_attributes` (`id_fcs`, `fcs_class`, `fcs_propriety`, `fcs_created`, `fcs_agency`, `fcs_active`, `fcs_order`, `fcs_group`, `fcs_rule`, `fcs_range`) VALUES
(1, 59, 67, '2017-03-20 00:31:36', 0, 0, 0, '', 0, 68),
(2, 59, 63, '2017-03-20 00:42:24', 0, 0, 0, '', 0, 64),
(3, 59, 69, '2017-03-20 00:47:12', 0, 0, 0, '', 0, 70),
(4, 59, 71, '2017-03-20 01:02:11', 0, 0, 0, '', 0, 73),
(5, 59, 74, '2017-03-20 01:05:36', 0, 0, 0, '', 0, 68),
(6, 59, 76, '2017-03-20 01:10:14', 0, 0, 0, '', 0, 77),
(7, 59, 78, '2017-03-20 17:42:41', 0, 0, 0, '', 0, 79),
(8, 59, 78, '2017-03-20 17:43:17', 0, 0, 0, '', 0, 79),
(9, 60, 78, '2017-03-20 20:12:53', 0, 0, 0, '', 0, 79),
(10, 60, 65, '2017-03-20 20:15:06', 0, 0, 0, '', 0, 77),
(11, 60, 81, '2017-03-20 20:17:19', 0, 0, 0, '', 0, 80),
(12, 60, 66, '2017-03-20 20:17:44', 0, 0, 0, '', 0, 64),
(13, 60, 83, '2017-03-20 20:18:55', 0, 0, 0, '', 0, 82),
(14, 60, 85, '2017-03-20 20:20:45', 0, 0, 0, '', 0, 79),
(15, 61, 86, '2017-03-20 20:23:18', 0, 0, 0, '', 0, 70),
(16, 61, 87, '2017-03-20 20:24:29', 0, 0, 0, '', 0, 64),
(17, 61, 71, '2017-03-20 20:29:28', 0, 0, 0, '', 0, 73),
(18, 61, 88, '2017-03-20 20:30:24', 0, 0, 0, '', 0, 89),
(19, 61, 91, '2017-03-20 20:31:19', 0, 0, 0, '', 0, 90),
(20, 61, 92, '2017-03-20 20:38:41', 0, 0, 0, '', 0, 93),
(21, 62, 94, '2017-03-20 20:40:56', 0, 0, 0, '', 0, 77),
(22, 62, 95, '2017-03-20 20:42:40', 0, 0, 0, '', 0, 77),
(23, 62, 96, '2017-03-20 20:43:43', 0, 0, 0, '', 0, 77);

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
  MODIFY `id_fcs` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
