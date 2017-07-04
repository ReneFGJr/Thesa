-- phpMyAdmin SQL Dump
-- version 4.2.7.1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Jul 04, 2017 at 03:51 PM
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
-- Table structure for table `find_id`
--

CREATE TABLE IF NOT EXISTS `find_id` (
`id_f` bigint(20) unsigned NOT NULL,
  `f_literal` int(11) NOT NULL,
  `f_class` int(11) NOT NULL,
  `f_created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `f_agency` int(11) NOT NULL COMMENT 'f',
  `f_source` varchar(150) NOT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=10 ;

--
-- Dumping data for table `find_id`
--

INSERT INTO `find_id` (`id_f`, `f_literal`, `f_class`, `f_created`, `f_agency`, `f_source`) VALUES
(1, 1, 59, '2017-03-19 22:44:50', 1, '0'),
(2, 2, 56, '2017-03-19 22:50:14', 1, '0'),
(3, 3, 56, '2017-03-19 22:50:29', 1, '0'),
(6, 6, 56, '2017-07-04 05:04:56', 1, '0'),
(7, 27, 56, '2017-07-04 05:34:16', 1, '0'),
(8, 123, 56, '2017-07-04 05:50:24', 1, '0'),
(9, 127, 56, '2017-07-04 15:43:26', 1, '0');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `find_id`
--
ALTER TABLE `find_id`
 ADD UNIQUE KEY `id_f` (`id_f`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `find_id`
--
ALTER TABLE `find_id`
MODIFY `id_f` bigint(20) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=10;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
