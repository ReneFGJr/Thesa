-- phpMyAdmin SQL Dump
-- version 4.2.7.1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Apr 02, 2019 at 07:38 PM
-- Server version: 5.6.20-log
-- PHP Version: 5.4.31

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `thesa`
--

-- --------------------------------------------------------

--
-- Table structure for table `bugs`
--

CREATE TABLE IF NOT EXISTS `bugs` (
`id_bug` bigint(20) unsigned NOT NULL,
  `bug_user` int(11) NOT NULL,
  `bug_text` text NOT NULL,
  `bug_created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `bug_status` int(11) NOT NULL,
  `bug_update` int(11) NOT NULL,
  `bug_answer` text NOT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `bugs`
--

INSERT INTO `bugs` (`id_bug`, `bug_user`, `bug_text`, `bug_created`, `bug_status`, `bug_update`, `bug_answer`) VALUES
(1, 417, 'Não consigo visualizar o mapa conceitual do meu tesauro. ', '2017-06-20 17:48:18', 0, 0, ''),
(2, 441, 'https://www.ufrgs.br/tesauros/index.php/skos/check_all\r\n\r\n404_Page not found', '2017-08-14 14:12:18', 0, 0, ''),
(3, 460, 'O sistema não esta adicionando os descritores. ', '2017-12-16 22:59:52', 0, 0, '');

-- --------------------------------------------------------

--
-- Table structure for table `ci_sessions`
--

CREATE TABLE IF NOT EXISTS `ci_sessions` (
  `id` varchar(40) NOT NULL,
  `ip_address` varchar(45) NOT NULL,
  `timestamp` int(10) unsigned NOT NULL DEFAULT '0',
  `data` blob NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `ci_sessions`
--

INSERT INTO `ci_sessions` (`id`, `ip_address`, `timestamp`, `data`) VALUES
('aa8be54ef439941f2b9acd4d5586d5bb084fdf6a', '::1', 1554233900, 0x5f5f63695f6c6173745f726567656e65726174657c693a313535343233333839343b69647c733a313a2231223b757365727c733a353a225468657361223b656d61696c7c733a353a227468657361223b696d6167657c733a303a22223b70657266696c7c733a383a222341444d23424942223b6e6976656c7c733a313a2239223b);

-- --------------------------------------------------------

--
-- Table structure for table `language`
--

CREATE TABLE IF NOT EXISTS `language` (
`id_lg` bigint(20) unsigned NOT NULL,
  `lg_code` varchar(6) DEFAULT NULL,
  `lg_language` varchar(80) DEFAULT NULL,
  `lg_order` int(11) DEFAULT NULL,
  `lg_active` int(11) NOT NULL DEFAULT '1',
  `lg_cod_marc` char(5) NOT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=512 ;

--
-- Dumping data for table `language`
--

INSERT INTO `language` (`id_lg`, `lg_code`, `lg_language`, `lg_order`, `lg_active`, `lg_cod_marc`) VALUES
(1, 'aar', 'Afar', 99, 0, 'aar'),
(2, 'abk', 'Abkhaz', 99, 0, 'abk'),
(3, 'ace', 'Achinese', 99, 0, 'ace'),
(4, 'ach', 'Acoli', 99, 0, 'ach'),
(5, 'ada', 'Adangme', 99, 0, 'ada'),
(6, 'ady', 'Adygei', 99, 0, 'ady'),
(7, 'afa', 'Afroasiatic (Other)', 99, 0, 'afa'),
(8, 'afh', 'Afrihili (Artificial language)', 99, 0, 'afh'),
(9, 'afr', 'Afrikaans', 99, 0, 'afr'),
(10, 'ain', 'Ainu', 99, 0, 'ain'),
(11, '-ajm', 'Aljamía\n', 99, 0, '-ajm'),
(12, 'aka', 'Akan', 99, 0, 'aka'),
(13, 'akk', 'Akkadian', 99, 0, 'akk'),
(14, 'alb', 'Albanian', 99, 0, 'alb'),
(15, 'ale', 'Aleut', 99, 0, 'ale'),
(16, 'alg', 'Algonquian (Other)', 99, 0, 'alg'),
(17, 'alt', 'Altai', 99, 0, 'alt'),
(18, 'amh', 'Amharic', 99, 0, 'amh'),
(19, 'ang', 'English, Old (ca. 450-1100)', 99, 0, 'ang'),
(20, 'anp', 'Angika', 99, 0, 'anp'),
(21, 'apa', 'Apache languages', 99, 0, 'apa'),
(22, 'ara', 'Arabic', 99, 0, 'ara'),
(23, 'arc', 'Aramaic', 99, 0, 'arc'),
(24, 'arg', 'Aragonese', 99, 0, 'arg'),
(25, 'arm', 'Armenian', 99, 0, 'arm'),
(26, 'arn', 'Mapuche', 99, 0, 'arn'),
(27, 'arp', 'Arapaho', 99, 0, 'arp'),
(28, 'art', 'Artificial (Other)', 99, 0, 'art'),
(29, 'arw', 'Arawak', 99, 0, 'arw'),
(30, 'asm', 'Assamese', 99, 0, 'asm'),
(31, 'ast', 'Bable', 99, 0, 'ast'),
(32, 'ath', 'Athapascan (Other)', 99, 0, 'ath'),
(33, 'aus', 'Australian languages', 99, 0, 'aus'),
(34, 'ava', 'Avaric', 99, 0, 'ava'),
(35, 'ave', 'Avestan', 99, 0, 'ave'),
(36, 'awa', 'Awadhi', 99, 0, 'awa'),
(37, 'aym', 'Aymara', 99, 0, 'aym'),
(38, 'aze', 'Azerbaijani', 99, 0, 'aze'),
(39, 'bad', 'Banda languages', 99, 0, 'bad'),
(40, 'bai', 'Bamileke languages', 99, 0, 'bai'),
(41, 'bak', 'Bashkir', 99, 0, 'bak'),
(42, 'bal', 'Baluchi', 99, 0, 'bal'),
(43, 'bam', 'Bambara', 99, 0, 'bam'),
(44, 'ban', 'Balinese', 99, 0, 'ban'),
(45, 'baq', 'Basque', 99, 0, 'baq'),
(46, 'bas', 'Basa', 99, 0, 'bas'),
(47, 'bat', 'Baltic (Other)', 99, 0, 'bat'),
(48, 'bej', 'Beja', 99, 0, 'bej'),
(49, 'bel', 'Belarusian', 99, 0, 'bel'),
(50, 'bem', 'Bemba', 99, 0, 'bem'),
(51, 'ben', 'Bengali', 99, 0, 'ben'),
(52, 'ber', 'Berber (Other)', 99, 0, 'ber'),
(53, 'bho', 'Bhojpuri', 99, 0, 'bho'),
(54, 'bih', 'Bihari (Other)', 99, 0, 'bih'),
(55, 'bik', 'Bikol', 99, 0, 'bik'),
(56, 'bin', 'Edo', 99, 0, 'bin'),
(57, 'bis', 'Bislama', 99, 0, 'bis'),
(58, 'bla', 'Siksika', 99, 0, 'bla'),
(59, 'bnt', 'Bantu (Other)', 99, 0, 'bnt'),
(60, 'bos', 'Bosnian', 99, 0, 'bos'),
(61, 'bra', 'Braj', 99, 0, 'bra'),
(62, 'bre', 'Breton', 99, 0, 'bre'),
(63, 'btk', 'Batak', 99, 0, 'btk'),
(64, 'bua', 'Buriat', 99, 0, 'bua'),
(65, 'bug', 'Bugis', 99, 0, 'bug'),
(66, 'bul', 'Bulgarian', 99, 0, 'bul'),
(67, 'bur', 'Burmese', 99, 0, 'bur'),
(68, 'byn', 'Bilin', 99, 0, 'byn'),
(69, 'cad', 'Caddo', 99, 0, 'cad'),
(70, 'cai', 'Central American Indian (Other)', 99, 0, 'cai'),
(71, '-cam', 'Khmer', 99, 0, '-cam'),
(72, 'car', 'Carib', 99, 0, 'car'),
(73, 'cat', 'Catalan', 99, 0, 'cat'),
(74, 'cau', 'Caucasian (Other)', 99, 0, 'cau'),
(75, 'ceb', 'Cebuano', 99, 0, 'ceb'),
(76, 'cel', 'Celtic (Other)', 99, 0, 'cel'),
(77, 'cha', 'Chamorro', 99, 0, 'cha'),
(78, 'chb', 'Chibcha', 99, 0, 'chb'),
(79, 'che', 'Chechen', 99, 0, 'che'),
(80, 'chg', 'Chagatai', 99, 0, 'chg'),
(81, 'chi', 'Chinese', 99, 0, 'chi'),
(82, 'chk', 'Chuukese', 99, 0, 'chk'),
(83, 'chm', 'Mari', 99, 0, 'chm'),
(84, 'chn', 'Chinook jargon', 99, 0, 'chn'),
(85, 'cho', 'Choctaw', 99, 0, 'cho'),
(86, 'chp', 'Chipewyan', 99, 0, 'chp'),
(87, 'chr', 'Cherokee', 99, 0, 'chr'),
(88, 'chu', 'Church Slavic', 99, 0, 'chu'),
(89, 'chv', 'Chuvash', 99, 0, 'chv'),
(90, 'chy', 'Cheyenne', 99, 0, 'chy'),
(91, 'cmc', 'Chamic languages', 99, 0, 'cmc'),
(92, 'cop', 'Coptic', 99, 0, 'cop'),
(93, 'cor', 'Cornish', 99, 0, 'cor'),
(94, 'cos', 'Corsican', 99, 0, 'cos'),
(95, 'cpe', 'Creoles and Pidgins, English-based (Other)', 99, 0, 'cpe'),
(96, 'cpf', 'Creoles and Pidgins, French-based (Other)', 99, 0, 'cpf'),
(97, 'cpp', 'Creoles and Pidgins, Portuguese-based (Other)', 99, 0, 'cpp'),
(98, 'cre', 'Cree', 99, 0, 'cre'),
(99, 'crh', 'Crimean Tatar', 99, 0, 'crh'),
(100, 'crp', 'Creoles and Pidgins (Other)', 99, 0, 'crp'),
(101, 'csb', 'Kashubian', 99, 0, 'csb'),
(102, 'cus', 'Cushitic (Other)', 99, 0, 'cus'),
(103, 'cze', 'Czech', 99, 0, 'cze'),
(104, 'dak', 'Dakota', 99, 0, 'dak'),
(105, 'dan', 'Danish', 99, 0, 'dan'),
(106, 'dar', 'Dargwa', 99, 0, 'dar'),
(107, 'day', 'Dayak', 99, 0, 'day'),
(108, 'del', 'Delaware', 99, 0, 'del'),
(109, 'den', 'Slavey', 99, 0, 'den'),
(110, 'dgr', 'Dogrib', 99, 0, 'dgr'),
(111, 'din', 'Dinka', 99, 0, 'din'),
(112, 'div', 'Divehi', 99, 0, 'div'),
(113, 'doi', 'Dogri', 99, 0, 'doi'),
(114, 'dra', 'Dravidian (Other)', 99, 0, 'dra'),
(115, 'dsb', 'Lower Sorbian', 99, 0, 'dsb'),
(116, 'dua', 'Duala', 99, 0, 'dua'),
(117, 'dum', 'Dutch, Middle (ca. 1050-1350)', 99, 0, 'dum'),
(118, 'dut', 'Dutch', 99, 0, 'dut'),
(119, 'dyu', 'Dyula', 99, 0, 'dyu'),
(120, 'dzo', 'Dzongkha', 99, 0, 'dzo'),
(121, 'efi', 'Efik', 99, 0, 'efi'),
(122, 'egy', 'Egyptian', 99, 0, 'egy'),
(123, 'eka', 'Ekajuk', 99, 0, 'eka'),
(124, 'elx', 'Elamite', 99, 0, 'elx'),
(125, 'eng', 'English', 2, 1, 'eng'),
(126, 'enm', 'English, Middle (1100-1500)', 99, 0, 'enm'),
(127, 'epo', 'Esperanto', 99, 0, 'epo'),
(128, '-esk', 'Eskimo languages', 99, 0, '-esk'),
(129, '-esp', 'Esperanto', 99, 0, '-esp'),
(130, 'est', 'Estonian', 99, 0, 'est'),
(131, '-eth', 'Ethiopic', 99, 0, '-eth'),
(132, 'ewe', 'Ewe', 99, 0, 'ewe'),
(133, 'ewo', 'Ewondo', 99, 0, 'ewo'),
(134, 'fan', 'Fang', 99, 0, 'fan'),
(135, 'fao', 'Faroese', 99, 0, 'fao'),
(136, '-far', 'Faroese', 99, 0, '-far'),
(137, 'fat', 'Fanti', 99, 0, 'fat'),
(138, 'fij', 'Fijian', 99, 0, 'fij'),
(139, 'fil', 'Filipino', 99, 0, 'fil'),
(140, 'fin', 'Finnish', 99, 0, 'fin'),
(141, 'fiu', 'Finno-Ugrian (Other)', 99, 0, 'fiu'),
(142, 'fon', 'Fon', 99, 0, 'fon'),
(143, 'fre', 'French', 99, 0, 'fre'),
(144, '-fri', 'Frisian', 99, 0, '-fri'),
(145, 'frm', 'French, Middle (ca. 1300-1600)', 99, 0, 'frm'),
(146, 'fro', 'French, Old (ca. 842-1300)', 99, 0, 'fro'),
(147, 'frr', 'North Frisian', 99, 0, 'frr'),
(148, 'frs', 'East Frisian', 99, 0, 'frs'),
(149, 'fry', 'Frisian', 99, 0, 'fry'),
(150, 'ful', 'Fula', 99, 0, 'ful'),
(151, 'fur', 'Friulian', 99, 0, 'fur'),
(152, 'gaa', 'G?\r\n#NOME?', 99, 0, 'gaa'),
(153, '-gag', 'Galician', 99, 0, '-gag'),
(154, '-gal', 'Oromo', 99, 0, '-gal'),
(155, 'gay', 'Gayo', 99, 0, 'gay'),
(156, 'gba', 'Gbaya', 99, 0, 'gba'),
(157, 'gem', 'Germanic (Other)', 99, 0, 'gem'),
(158, 'geo', 'Georgian', 99, 0, 'geo'),
(159, 'ger', 'German', 99, 0, 'ger'),
(160, 'gez', 'Ethiopic', 99, 0, 'gez'),
(161, 'gil', 'Gilbertese', 99, 0, 'gil'),
(162, 'gla', 'Scottish Gaelic', 99, 0, 'gla'),
(163, 'gle', 'Irish', 99, 0, 'gle'),
(164, 'glg', 'Galician', 99, 0, 'glg'),
(165, 'glv', 'Manx', 99, 0, 'glv'),
(166, 'gmh', 'German, Middle High (ca. 1050-1500)', 99, 0, 'gmh'),
(167, 'goh', 'German, Old High (ca. 750-1050)', 99, 0, 'goh'),
(168, 'gon', 'Gondi', 99, 0, 'gon'),
(169, 'gor', 'Gorontalo', 99, 0, 'gor'),
(170, 'got', 'Gothic', 99, 0, 'got'),
(171, 'grb', 'Grebo', 99, 0, 'grb'),
(172, 'grc', 'Greek, Ancient (to 1453)', 99, 0, 'grc'),
(173, 'gre', 'Greek, Modern (1453-)', 99, 0, 'gre'),
(174, 'grn', 'Guarani', 99, 0, 'grn'),
(175, 'gsw', 'Swiss German', 99, 0, 'gsw'),
(176, '-gua', 'Guarani', 99, 0, '-gua'),
(177, 'guj', 'Gujarati', 99, 0, 'guj'),
(178, 'gwi', 'Gwich''in', 99, 0, 'gwi'),
(179, 'hai', 'Haida', 99, 0, 'hai'),
(180, 'hat', 'Haitian French Creole', 99, 0, 'hat'),
(181, 'hau', 'Hausa', 99, 0, 'hau'),
(182, 'haw', 'Hawaiian', 99, 0, 'haw'),
(183, 'heb', 'Hebrew', 99, 0, 'heb'),
(184, 'her', 'Herero', 99, 0, 'her'),
(185, 'hil', 'Hiligaynon', 99, 0, 'hil'),
(186, 'him', 'Western Pahari languages', 99, 0, 'him'),
(187, 'hin', 'Hindi', 99, 0, 'hin'),
(188, 'hit', 'Hittite', 99, 0, 'hit'),
(189, 'hmn', 'Hmong', 99, 0, 'hmn'),
(190, 'hmo', 'Hiri Motu', 99, 0, 'hmo'),
(191, 'hrv', 'Croatian', 99, 0, 'hrv'),
(192, 'hsb', 'Upper Sorbian', 99, 0, 'hsb'),
(193, 'hun', 'Hungarian', 99, 0, 'hun'),
(194, 'hup', 'Hupa', 99, 0, 'hup'),
(195, 'iba', 'Iban', 99, 0, 'iba'),
(196, 'ibo', 'Igbo', 99, 0, 'ibo'),
(197, 'ice', 'Icelandic', 99, 0, 'ice'),
(198, 'ido', 'Ido', 99, 0, 'ido'),
(199, 'iii', 'Sichuan Yi', 99, 0, 'iii'),
(200, 'ijo', 'Ijo', 99, 0, 'ijo'),
(201, 'iku', 'Inuktitut', 99, 0, 'iku'),
(202, 'ile', 'Interlingue', 99, 0, 'ile'),
(203, 'ilo', 'Iloko', 99, 0, 'ilo'),
(204, 'ina', 'Interlingua (International Auxiliary Language Association)', 99, 0, 'ina'),
(205, 'inc', 'Indic (Other)', 99, 0, 'inc'),
(206, 'ind', 'Indonesian', 99, 0, 'ind'),
(207, 'ine', 'Indo-European (Other)', 99, 0, 'ine'),
(208, 'inh', 'Ingush', 99, 0, 'inh'),
(209, '-int', 'Interlingua (International Auxiliary Language Association)', 99, 0, '-int'),
(210, 'ipk', 'Inupiaq', 99, 0, 'ipk'),
(211, 'ira', 'Iranian (Other)', 99, 0, 'ira'),
(212, '-iri', 'Irish', 99, 0, '-iri'),
(213, 'iro', 'Iroquoian (Other)', 99, 0, 'iro'),
(214, 'ita', 'Italian', 99, 0, 'ita'),
(215, 'jav', 'Javanese', 99, 0, 'jav'),
(216, 'jbo', 'Lojban (Artificial language)', 99, 0, 'jbo'),
(217, 'jpn', 'Japanese', 99, 0, 'jpn'),
(218, 'jpr', 'Judeo-Persian', 99, 0, 'jpr'),
(219, 'jrb', 'Judeo-Arabic', 99, 0, 'jrb'),
(220, 'kaa', 'Kara-Kalpak', 99, 0, 'kaa'),
(221, 'kab', 'Kabyle', 99, 0, 'kab'),
(222, 'kac', 'Kachin', 99, 0, 'kac'),
(223, 'kal', 'Kal?tdlisut', 99, 0, 'kal'),
(224, 'kam', 'Kamba', 99, 0, 'kam'),
(225, 'kan', 'Kannada', 99, 0, 'kan'),
(226, 'kar', 'Karen languages', 99, 0, 'kar'),
(227, 'kas', 'Kashmiri', 99, 0, 'kas'),
(228, 'kau', 'Kanuri', 99, 0, 'kau'),
(229, 'kaw', 'Kawi', 99, 0, 'kaw'),
(230, 'kaz', 'Kazakh', 99, 0, 'kaz'),
(231, 'kbd', 'Kabardian', 99, 0, 'kbd'),
(232, 'kha', 'Khasi', 99, 0, 'kha'),
(233, 'khi', 'Khoisan (Other)', 99, 0, 'khi'),
(234, 'khm', 'Khmer', 99, 0, 'khm'),
(235, 'kho', 'Khotanese', 99, 0, 'kho'),
(236, 'kik', 'Kikuyu', 99, 0, 'kik'),
(237, 'kin', 'Kinyarwanda', 99, 0, 'kin'),
(238, 'kir', 'Kyrgyz', 99, 0, 'kir'),
(239, 'kmb', 'Kimbundu', 99, 0, 'kmb'),
(240, 'kok', 'Konkani', 99, 0, 'kok'),
(241, 'kom', 'Komi', 99, 0, 'kom'),
(242, 'kon', 'Kongo', 99, 0, 'kon'),
(243, 'kor', 'Korean', 99, 0, 'kor'),
(244, 'kos', 'Kosraean', 99, 0, 'kos'),
(245, 'kpe', 'Kpelle', 99, 0, 'kpe'),
(246, 'krc', 'Karachay-Balkar', 99, 0, 'krc'),
(247, 'krl', 'Karelian', 99, 0, 'krl'),
(248, 'kro', 'Kru (Other)', 99, 0, 'kro'),
(249, 'kru', 'Kurukh', 99, 0, 'kru'),
(250, 'kua', 'Kuanyama', 99, 0, 'kua'),
(251, 'kum', 'Kumyk', 99, 0, 'kum'),
(252, 'kur', 'Kurdish', 99, 0, 'kur'),
(253, '-kus', 'Kusaie', 99, 0, '-kus'),
(254, 'kut', 'Kootenai', 99, 0, 'kut'),
(255, 'lad', 'Ladino', 99, 0, 'lad'),
(256, 'lah', 'Lahnda', 99, 0, 'lah'),
(257, 'lam', 'Lamba (Zambia and Congo)', 99, 0, 'lam'),
(258, '-lan', 'Occitan (post 1500)', 99, 0, '-lan'),
(259, 'lao', 'Lao', 99, 0, 'lao'),
(260, '-lap', 'Sami', 99, 0, '-lap'),
(261, 'lat', 'Latin', 99, 0, 'lat'),
(262, 'lav', 'Latvian', 99, 0, 'lav'),
(263, 'lez', 'Lezgian', 99, 0, 'lez'),
(264, 'lim', 'Limburgish', 99, 0, 'lim'),
(265, 'lin', 'Lingala', 99, 0, 'lin'),
(266, 'lit', 'Lithuanian', 99, 0, 'lit'),
(267, 'lol', 'Mongo-Nkundu', 99, 0, 'lol'),
(268, 'loz', 'Lozi', 99, 0, 'loz'),
(269, 'ltz', 'Luxembourgish', 99, 0, 'ltz'),
(270, 'lua', 'Luba-Lulua', 99, 0, 'lua'),
(271, 'lub', 'Luba-Katanga', 99, 0, 'lub'),
(272, 'lug', 'Ganda', 99, 0, 'lug'),
(273, 'lui', 'Luise?o\r\nlun', 99, 0, 'lui'),
(274, 'luo', 'Luo (Kenya and Tanzania)', 99, 0, 'luo'),
(275, 'lus', 'Lushai', 99, 0, 'lus'),
(276, 'mac', 'Macedonian', 99, 0, 'mac'),
(277, 'mad', 'Madurese', 99, 0, 'mad'),
(278, 'mag', 'Magahi', 99, 0, 'mag'),
(279, 'mah', 'Marshallese', 99, 0, 'mah'),
(280, 'mai', 'Maithili', 99, 0, 'mai'),
(281, 'mak', 'Makasar', 99, 0, 'mak'),
(282, 'mal', 'Malayalam', 99, 0, 'mal'),
(283, 'man', 'Mandingo', 99, 0, 'man'),
(284, 'mao', 'Maori', 99, 0, 'mao'),
(285, 'map', 'Austronesian (Other)', 99, 0, 'map'),
(286, 'mar', 'Marathi', 99, 0, 'mar'),
(287, 'mas', 'Maasai', 99, 0, 'mas'),
(288, '-max', 'Manx', 99, 0, '-max'),
(289, 'may', 'Malay', 99, 0, 'may'),
(290, 'mdf', 'Moksha', 99, 0, 'mdf'),
(291, 'mdr', 'Mandar', 99, 0, 'mdr'),
(292, 'men', 'Mende', 99, 0, 'men'),
(293, 'mga', 'Irish, Middle (ca. 1100-1550)', 99, 0, 'mga'),
(294, 'mic', 'Micmac', 99, 0, 'mic'),
(295, 'min', 'Minangkabau', 99, 0, 'min'),
(296, 'mis', 'Miscellaneous languages', 99, 0, 'mis'),
(297, 'mkh', 'Mon-Khmer (Other)', 99, 0, 'mkh'),
(298, '-mla', 'Malagasy', 99, 0, '-mla'),
(299, 'mlg', 'Malagasy', 99, 0, 'mlg'),
(300, 'mlt', 'Maltese', 99, 0, 'mlt'),
(301, 'mnc', 'Manchu', 99, 0, 'mnc'),
(302, 'mni', 'Manipuri', 99, 0, 'mni'),
(303, 'mno', 'Manobo languages', 99, 0, 'mno'),
(304, 'moh', 'Mohawk', 99, 0, 'moh'),
(305, '-mol', 'Moldavian', 99, 0, '-mol'),
(306, 'mon', 'Mongolian', 99, 0, 'mon'),
(307, 'mos', 'Moor?\r\nmul', 99, 0, 'mos'),
(308, 'mun', 'Munda (Other)', 99, 0, 'mun'),
(309, 'mus', 'Creek', 99, 0, 'mus'),
(310, 'mwl', 'Mirandese', 99, 0, 'mwl'),
(311, 'mwr', 'Marwari', 99, 0, 'mwr'),
(312, 'myn', 'Mayan languages', 99, 0, 'myn'),
(313, 'myv', 'Erzya', 99, 0, 'myv'),
(314, 'nah', 'Nahuatl', 99, 0, 'nah'),
(315, 'nai', 'North American Indian (Other)', 99, 0, 'nai'),
(316, 'nap', 'Neapolitan Italian', 99, 0, 'nap'),
(317, 'nau', 'Nauru', 99, 0, 'nau'),
(318, 'nav', 'Navajo', 99, 0, 'nav'),
(319, 'nbl', 'Ndebele (South Africa)', 99, 0, 'nbl'),
(320, 'nde', 'Ndebele (Zimbabwe)', 99, 0, 'nde'),
(321, 'ndo', 'Ndonga', 99, 0, 'ndo'),
(322, 'nds', 'Low German', 99, 0, 'nds'),
(323, 'nep', 'Nepali', 99, 0, 'nep'),
(324, 'new', 'Newari', 99, 0, 'new'),
(325, 'nia', 'Nias', 99, 0, 'nia'),
(326, 'nic', 'Niger-Kordofanian (Other)', 99, 0, 'nic'),
(327, 'niu', 'Niuean', 99, 0, 'niu'),
(328, 'nno', 'Norwegian (Nynorsk)', 99, 0, 'nno'),
(329, 'nob', 'Norwegian (Bokm?l)', 99, 0, 'nob'),
(330, 'nog', 'Nogai', 99, 0, 'nog'),
(331, 'non', 'Old Norse', 99, 0, 'non'),
(332, 'nor', 'Norwegian', 99, 0, 'nor'),
(333, 'nqo', 'N''Ko', 99, 0, 'nqo'),
(334, 'nso', 'Northern Sotho', 99, 0, 'nso'),
(335, 'nub', 'Nubian languages', 99, 0, 'nub'),
(336, 'nwc', 'Newari, Old', 99, 0, 'nwc'),
(337, 'nya', 'Nyanja', 99, 0, 'nya'),
(338, 'nym', 'Nyamwezi', 99, 0, 'nym'),
(339, 'nyn', 'Nyankole', 99, 0, 'nyn'),
(340, 'nyo', 'Nyoro', 99, 0, 'nyo'),
(341, 'nzi', 'Nzima', 99, 0, 'nzi'),
(342, 'oci', 'Occitan (post-1500)', 99, 0, 'oci'),
(343, 'oji', 'Ojibwa', 99, 0, 'oji'),
(344, 'ori', 'Oriya', 99, 0, 'ori'),
(345, 'orm', 'Oromo', 99, 0, 'orm'),
(346, 'osa', 'Osage', 99, 0, 'osa'),
(347, 'oss', 'Ossetic', 99, 0, 'oss'),
(348, 'ota', 'Turkish, Ottoman', 99, 0, 'ota'),
(349, 'oto', 'Otomian languages', 99, 0, 'oto'),
(350, 'paa', 'Papuan (Other)', 99, 0, 'paa'),
(351, 'pag', 'Pangasinan', 99, 0, 'pag'),
(352, 'pal', 'Pahlavi', 99, 0, 'pal'),
(353, 'pam', 'Pampanga', 99, 0, 'pam'),
(354, 'pan', 'Panjabi', 99, 0, 'pan'),
(355, 'pap', 'Papiamento', 99, 0, 'pap'),
(356, 'pau', 'Palauan', 99, 0, 'pau'),
(357, 'peo', 'Old Persian (ca. 600-400 B.C.)', 99, 0, 'peo'),
(358, 'per', 'Persian', 99, 0, 'per'),
(359, 'phi', 'Philippine (Other)', 99, 0, 'phi'),
(360, 'phn', 'Phoenician', 99, 0, 'phn'),
(361, 'pli', 'Pali', 99, 0, 'pli'),
(362, 'pol', 'Polish', 99, 0, 'pol'),
(363, 'pon', 'Pohnpeian', 99, 0, 'pon'),
(364, 'por', 'Portuguese (Brazil)', 1, 1, 'por'),
(365, 'pra', 'Prakrit languages', 99, 0, 'pra'),
(366, 'pro', 'Proven?al (to 1500)', 99, 0, 'pro'),
(367, 'pus', 'Pushto', 99, 0, 'pus'),
(368, 'que', 'Quechua', 99, 0, 'que'),
(369, 'raj', 'Rajasthani', 99, 0, 'raj'),
(370, 'rap', 'Rapanui', 99, 0, 'rap'),
(371, 'rar', 'Rarotongan', 99, 0, 'rar'),
(372, 'roa', 'Romance (Other)', 99, 0, 'roa'),
(373, 'roh', 'Raeto-Romance', 99, 0, 'roh'),
(374, 'rom', 'Romani', 99, 0, 'rom'),
(375, 'rum', 'Romanian', 99, 0, 'rum'),
(376, 'run', 'Rundi', 99, 0, 'run'),
(377, 'rup', 'Aromanian', 99, 0, 'rup'),
(378, 'rus', 'Russian', 99, 0, 'rus'),
(379, 'sad', 'Sandawe', 99, 0, 'sad'),
(380, 'sag', 'Sango (Ubangi Creole)', 99, 0, 'sag'),
(381, 'sah', 'Yakut', 99, 0, 'sah'),
(382, 'sai', 'South American Indian (Other)', 99, 0, 'sai'),
(383, 'sal', 'Salishan languages', 99, 0, 'sal'),
(384, 'sam', 'Samaritan Aramaic', 99, 0, 'sam'),
(385, 'san', 'Sanskrit', 99, 0, 'san'),
(386, '-sao', 'Samoan', 99, 0, '-sao'),
(387, 'sas', 'Sasak', 99, 0, 'sas'),
(388, 'sat', 'Santali', 99, 0, 'sat'),
(389, '-scc', 'Serbian', 99, 0, '-scc'),
(390, 'scn', 'Sicilian Italian', 99, 0, 'scn'),
(391, 'sco', 'Scots', 99, 0, 'sco'),
(392, '-scr', 'Croatian', 99, 0, '-scr'),
(393, 'sel', 'Selkup', 99, 0, 'sel'),
(394, 'sem', 'Semitic (Other)', 99, 0, 'sem'),
(395, 'sga', 'Irish, Old (to 1100)', 99, 0, 'sga'),
(396, 'sgn', 'Sign languages', 99, 0, 'sgn'),
(397, 'shn', 'Shan', 99, 0, 'shn'),
(398, '-sho', 'Shona', 99, 0, '-sho'),
(399, 'sid', 'Sidamo', 99, 0, 'sid'),
(400, 'sin', 'Sinhalese', 99, 0, 'sin'),
(401, 'sio', 'Siouan (Other)', 99, 0, 'sio'),
(402, 'sit', 'Sino-Tibetan (Other)', 99, 0, 'sit'),
(403, 'sla', 'Slavic (Other)', 99, 0, 'sla'),
(404, 'slo', 'Slovak', 99, 0, 'slo'),
(405, 'slv', 'Slovenian', 99, 0, 'slv'),
(406, 'sma', 'Southern Sami', 99, 0, 'sma'),
(407, 'sme', 'Northern Sami', 99, 0, 'sme'),
(408, 'smi', 'Sami', 99, 0, 'smi'),
(409, 'smj', 'Lule Sami', 99, 0, 'smj'),
(410, 'smn', 'Inari Sami', 99, 0, 'smn'),
(411, 'smo', 'Samoan', 99, 0, 'smo'),
(412, 'sms', 'Skolt Sami', 99, 0, 'sms'),
(413, 'sna', 'Shona', 99, 0, 'sna'),
(414, 'snd', 'Sindhi', 99, 0, 'snd'),
(415, '-snh', 'Sinhalese', 99, 0, '-snh'),
(416, 'snk', 'Soninke', 99, 0, 'snk'),
(417, 'sog', 'Sogdian', 99, 0, 'sog'),
(418, 'som', 'Somali', 99, 0, 'som'),
(419, 'son', 'Songhai', 99, 0, 'son'),
(420, 'sot', 'Sotho', 99, 0, 'sot'),
(421, 'spa', 'Spanish', 99, 0, 'spa'),
(422, 'srd', 'Sardinian', 99, 0, 'srd'),
(423, 'srn', 'Sranan', 99, 0, 'srn'),
(424, 'srp', 'Serbian', 99, 0, 'srp'),
(425, 'srr', 'Serer', 99, 0, 'srr'),
(426, 'ssa', 'Nilo-Saharan (Other)', 99, 0, 'ssa'),
(427, '-sso', 'Sotho', 99, 0, '-sso'),
(428, 'ssw', 'Swazi', 99, 0, 'ssw'),
(429, 'suk', 'Sukuma', 99, 0, 'suk'),
(430, 'sun', 'Sundanese', 99, 0, 'sun'),
(431, 'sus', 'Susu', 99, 0, 'sus'),
(432, 'sux', 'Sumerian', 99, 0, 'sux'),
(433, 'swa', 'Swahili', 99, 0, 'swa'),
(434, 'swe', 'Swedish', 99, 0, 'swe'),
(435, '-swz', 'Swazi', 99, 0, '-swz'),
(436, 'syc', 'Syriac', 99, 0, 'syc'),
(437, 'syr', 'Syriac, Modern', 99, 0, 'syr'),
(438, '-tag', 'Tagalog', 99, 0, '-tag'),
(439, 'tah', 'Tahitian', 99, 0, 'tah'),
(440, 'tai', 'Tai (Other)', 99, 0, 'tai'),
(441, '-taj', 'Tajik', 99, 0, '-taj'),
(442, 'tam', 'Tamil', 99, 0, 'tam'),
(443, '-tar', 'Tatar', 99, 0, '-tar'),
(444, 'tat', 'Tatar', 99, 0, 'tat'),
(445, 'tel', 'Telugu', 99, 0, 'tel'),
(446, 'tem', 'Temne', 99, 0, 'tem'),
(447, 'ter', 'Terena', 99, 0, 'ter'),
(448, 'tet', 'Tetum', 99, 0, 'tet'),
(449, 'tgk', 'Tajik', 99, 0, 'tgk'),
(450, 'tgl', 'Tagalog', 99, 0, 'tgl'),
(451, 'tha', 'Thai', 99, 0, 'tha'),
(452, 'tib', 'Tibetan', 99, 0, 'tib'),
(453, 'tig', 'Tigr?\r\ntir', 99, 0, 'tig'),
(454, 'tiv', 'Tiv', 99, 0, 'tiv'),
(455, 'tkl', 'Tokelauan', 99, 0, 'tkl'),
(456, 'tlh', 'Klingon (Artificial language)', 99, 0, 'tlh'),
(457, 'tli', 'Tlingit', 99, 0, 'tli'),
(458, 'tmh', 'Tamashek', 99, 0, 'tmh'),
(459, 'tog', 'Tonga (Nyasa)', 99, 0, 'tog'),
(460, 'ton', 'Tongan', 99, 0, 'ton'),
(461, 'tpi', 'Tok Pisin', 99, 0, 'tpi'),
(462, '-tru', 'Truk', 99, 0, '-tru'),
(463, 'tsi', 'Tsimshian', 99, 0, 'tsi'),
(464, 'tsn', 'Tswana', 99, 0, 'tsn'),
(465, 'tso', 'Tsonga', 99, 0, 'tso'),
(466, '-tsw', 'Tswana', 99, 0, '-tsw'),
(467, 'tuk', 'Turkmen', 99, 0, 'tuk'),
(468, 'tum', 'Tumbuka', 99, 0, 'tum'),
(469, 'tup', 'Tupi languages', 99, 0, 'tup'),
(470, 'tur', 'Turkish', 99, 0, 'tur'),
(471, 'tut', 'Altaic (Other)', 99, 0, 'tut'),
(472, 'tvl', 'Tuvaluan', 99, 0, 'tvl'),
(473, 'twi', 'Twi', 99, 0, 'twi'),
(474, 'tyv', 'Tuvinian', 99, 0, 'tyv'),
(475, 'udm', 'Udmurt', 99, 0, 'udm'),
(476, 'uga', 'Ugaritic', 99, 0, 'uga'),
(477, 'uig', 'Uighur', 99, 0, 'uig'),
(478, 'ukr', 'Ukrainian', 99, 0, 'ukr'),
(479, 'umb', 'Umbundu', 99, 0, 'umb'),
(480, 'und', 'Undetermined', 99, 0, 'und'),
(481, 'urd', 'Urdu', 99, 0, 'urd'),
(482, 'uzb', 'Uzbek', 99, 0, 'uzb'),
(483, 'vai', 'Vai', 99, 0, 'vai'),
(484, 'ven', 'Venda', 99, 0, 'ven'),
(485, 'vie', 'Vietnamese', 99, 0, 'vie'),
(486, 'vol', 'Volap?k', 99, 0, 'vol'),
(487, 'vot', 'Votic', 99, 0, 'vot'),
(488, 'wak', 'Wakashan languages', 99, 0, 'wak'),
(489, 'wal', 'Wolayta', 99, 0, 'wal'),
(490, 'war', 'Waray', 99, 0, 'war'),
(491, 'was', 'Washoe', 99, 0, 'was'),
(492, 'wel', 'Welsh', 99, 0, 'wel'),
(493, 'wen', 'Sorbian (Other)', 99, 0, 'wen'),
(494, 'wln', 'Walloon', 99, 0, 'wln'),
(495, 'wol', 'Wolof', 99, 0, 'wol'),
(496, 'xal', 'Oirat', 99, 0, 'xal'),
(497, 'xho', 'Xhosa', 99, 0, 'xho'),
(498, 'yao', 'Yao (Africa)', 99, 0, 'yao'),
(499, 'yap', 'Yapese', 99, 0, 'yap'),
(500, 'yid', 'Yiddish', 99, 0, 'yid'),
(501, 'yor', 'Yoruba', 99, 0, 'yor'),
(502, 'ypk', 'Yupik languages', 99, 0, 'ypk'),
(503, 'zap', 'Zapotec', 99, 0, 'zap'),
(504, 'zbl', 'Blissymbolics', 99, 0, 'zbl'),
(505, 'zen', 'Zenaga', 99, 0, 'zen'),
(506, 'zha', 'Zhuang', 99, 0, 'zha'),
(507, 'znd', 'Zande languages', 99, 0, 'znd'),
(508, 'zul', 'Zulu', 99, 0, 'zul'),
(509, 'zun', 'Zuni', 99, 0, 'zun'),
(510, 'zxx', 'No linguistic content', 99, 0, 'zxx'),
(511, 'zza', 'Zaza', 99, 0, 'zza');

-- --------------------------------------------------------

--
-- Table structure for table `mensagem_own`
--

CREATE TABLE IF NOT EXISTS `mensagem_own` (
`id_m` bigint(20) unsigned NOT NULL,
  `m_descricao` char(150) NOT NULL,
  `m_header` char(150) NOT NULL,
  `m_foot` char(150) NOT NULL,
  `m_ativo` tinyint(4) NOT NULL,
  `m_email` char(100) NOT NULL,
  `m_own_cod` char(10) NOT NULL,
  `smtp_host` char(80) NOT NULL,
  `smtp_user` char(80) NOT NULL,
  `smtp_pass` char(80) NOT NULL,
  `smtp_protocol` char(5) NOT NULL,
  `smtp_port` char(3) NOT NULL,
  `mailtype` char(5) NOT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `mensagem_own`
--

INSERT INTO `mensagem_own` (`id_m`, `m_descricao`, `m_header`, `m_foot`, `m_ativo`, `m_email`, `m_own_cod`, `smtp_host`, `smtp_user`, `smtp_pass`, `smtp_protocol`, `smtp_port`, `mailtype`) VALUES
(1, 'Thesa', '', '', 1, '00282381@ufrgs.br', '', '', '', '', 'mail', '', '');

-- --------------------------------------------------------

--
-- Table structure for table `rdf`
--

CREATE TABLE IF NOT EXISTS `rdf` (
`id_rdf` bigint(20) unsigned NOT NULL,
  `rdf_r1` text NOT NULL,
  `rdf_prop` int(11) NOT NULL,
  `rdf_r2` text NOT NULL,
  `rdf_create` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `rdf_authority`
--

CREATE TABLE IF NOT EXISTS `rdf_authority` (
`id_au` bigint(20) unsigned NOT NULL,
  `au_descript` char(200) NOT NULL,
  `au_type` int(11) NOT NULL,
  `au_created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `au_active` int(11) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `rdf_class`
--

CREATE TABLE IF NOT EXISTS `rdf_class` (
`id_c` bigint(20) unsigned NOT NULL,
  `c_class` varchar(200) NOT NULL,
  `c_prefix` int(11) NOT NULL,
  `c_created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `c_class_main` int(11) NOT NULL DEFAULT '0',
  `c_type` char(1) NOT NULL,
  `c_order` int(11) NOT NULL DEFAULT '99',
  `c_pa` int(11) NOT NULL DEFAULT '0',
  `c_repetitive` int(11) NOT NULL DEFAULT '1',
  `c_vc` int(11) NOT NULL DEFAULT '0',
  `c_find` int(11) NOT NULL DEFAULT '0',
  `c_identify` int(11) NOT NULL DEFAULT '0',
  `c_contextualize` int(11) NOT NULL DEFAULT '0',
  `c_justify` int(11) NOT NULL DEFAULT '0',
  `c_url` char(100) NOT NULL,
  `c_url_update` date NOT NULL DEFAULT '0000-00-00'
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=109 ;

--
-- Dumping data for table `rdf_class`
--

INSERT INTO `rdf_class` (`id_c`, `c_class`, `c_prefix`, `c_created`, `c_class_main`, `c_type`, `c_order`, `c_pa`, `c_repetitive`, `c_vc`, `c_find`, `c_identify`, `c_contextualize`, `c_justify`, `c_url`, `c_url_update`) VALUES
(1, 'Agent', 0, '2017-11-03 14:33:53', 0, 'C', 10, 0, 1, 0, 1, 0, 0, 0, '', '0000-00-00'),
(2, 'Person', 0, '2017-11-03 14:33:53', 1, 'C', 11, 0, 1, 0, 1, 0, 0, 0, '', '0000-00-00'),
(3, 'Family', 0, '2017-11-03 14:34:34', 1, 'C', 12, 0, 1, 0, 1, 0, 0, 0, '', '0000-00-00'),
(4, 'Corporate Body', 0, '2017-11-03 14:34:34', 1, 'C', 13, 0, 1, 0, 1, 0, 0, 0, '', '0000-00-00'),
(5, 'prefLabel', 4, '2017-11-03 14:51:55', 0, 'P', 20, 1, 1, 0, 1, 0, 0, 0, '', '0000-00-00'),
(6, 'altLabel', 4, '2017-11-03 14:52:07', 0, 'P', 25, 1, 1, 0, 1, 0, 0, 0, '', '0000-00-00'),
(7, 'hasCutter', 0, '2017-11-03 16:44:41', 0, 'P', 28, 0, 1, 0, 0, 0, 0, 0, '', '0000-00-00'),
(8, 'hasBorn', 0, '2017-11-03 16:46:48', 0, 'P', 21, 0, 1, 0, 0, 0, 0, 0, '', '0000-00-00'),
(9, 'hasDie', 0, '2017-11-03 16:46:48', 0, 'P', 22, 0, 1, 0, 0, 0, 0, 0, '', '0000-00-00'),
(10, 'hasISBN', 0, '2017-11-03 17:08:28', 0, 'P', 99, 0, 1, 0, 0, 0, 0, 0, '', '0000-00-00'),
(11, 'hasISSN', 0, '2017-11-03 17:08:28', 0, 'P', 99, 0, 1, 0, 0, 0, 0, 0, '', '0000-00-00'),
(12, 'Date', 0, '2017-11-03 17:23:53', 0, 'C', 100, 0, 1, 0, 0, 0, 0, 0, 'https://www.ufrgs.br/tesauros/index.php/skos/rdf/10', '2018-04-12'),
(13, 'Class', 1, '2017-11-03 17:45:14', 0, 'C', 0, 0, 1, 0, 0, 0, 0, 0, '', '0000-00-00'),
(14, 'sourceNote', 0, '2017-11-04 17:08:03', 0, 'P', 99, 0, 1, 0, 0, 0, 0, 0, '', '0000-00-00'),
(15, 'hiddenLabel', 4, '2017-11-04 17:22:52', 0, 'P', 26, 0, 1, 0, 0, 0, 0, 0, '', '0000-00-00'),
(16, 'Work', 0, '2017-11-04 19:48:58', 0, 'C', 3, 0, 1, 0, 1, 0, 0, 0, '', '0000-00-00'),
(17, 'hasTitle', 0, '2017-11-04 19:57:49', 0, 'P', 8, 1, 1, 0, 1, 0, 0, 0, '', '0000-00-00'),
(18, 'hasSubtitle', 0, '2017-11-04 19:57:49', 0, 'P', 9, 1, 1, 0, 1, 0, 0, 0, '', '0000-00-00'),
(19, 'hasAuthor', 0, '2017-11-04 20:34:16', 0, 'P', 16, 0, 1, 0, 1, 0, 0, 0, '', '0000-00-00'),
(20, 'hasOrganizator', 0, '2017-11-04 20:34:16', 0, 'P', 17, 0, 1, 0, 1, 0, 0, 0, '', '0000-00-00'),
(21, 'hasCover', 0, '2017-11-04 22:00:58', 0, 'P', 999, 0, 1, 0, 1, 0, 0, 0, '', '0000-00-00'),
(22, 'hasTitlePerson', 0, '2017-11-05 16:10:33', 0, 'P', 29, 0, 1, 0, 0, 0, 0, 0, '', '0000-00-00'),
(23, 'hasGender', 1, '2017-11-05 16:11:47', 0, 'P', 29, 0, 1, 0, 0, 0, 0, 0, '', '0000-00-00'),
(24, 'Gender', 0, '2017-11-05 16:11:47', 0, 'C', 99, 0, 1, 0, 1, 0, 0, 0, 'https://www.ufrgs.br/tesauros/index.php/skos/rdf/63', '2018-03-12'),
(25, 'hasPlaceBirth', 0, '2017-11-05 16:13:16', 0, 'P', 99, 0, 1, 0, 0, 0, 0, 0, '', '0000-00-00'),
(26, 'hasPlaceDeath', 0, '2017-11-05 16:13:16', 0, 'P', 99, 0, 1, 0, 0, 0, 0, 0, '', '0000-00-00'),
(27, 'hasLiveCoutry', 0, '2017-11-05 16:47:28', 0, 'P', 99, 0, 1, 0, 0, 0, 0, 0, '', '0000-00-00'),
(28, 'hasAffiliation', 0, '2017-11-05 16:47:28', 0, 'P', 99, 0, 1, 0, 0, 0, 0, 0, '', '0000-00-00'),
(29, 'hasLanguagePerson', 0, '2017-11-05 16:49:26', 0, 'P', 99, 0, 1, 0, 0, 0, 0, 0, '', '0000-00-00'),
(30, 'hasPersonAtivity', 0, '2017-11-05 16:49:26', 0, 'P', 99, 0, 1, 0, 0, 0, 0, 0, '', '0000-00-00'),
(31, 'hasProfission', 0, '2017-11-05 16:50:12', 0, 'P', 99, 0, 1, 0, 0, 0, 0, 0, '', '0000-00-00'),
(32, 'hasPersonBiography', 0, '2017-11-05 16:50:12', 0, 'P', 99, 0, 1, 0, 0, 0, 0, 0, '', '0000-00-00'),
(33, 'Expression', 0, '2017-11-07 22:19:55', 0, 'C', 99, 0, 1, 0, 0, 0, 0, 0, '', '0000-00-00'),
(34, 'Manifestation', 0, '2017-11-07 22:19:55', 0, 'C', 99, 0, 1, 0, 0, 0, 0, 0, '', '0000-00-00'),
(35, 'Item', 0, '2017-11-07 22:20:34', 0, 'C', 99, 0, 1, 0, 1, 0, 0, 0, '', '0000-00-00'),
(36, 'Concept', 0, '2017-11-07 22:20:34', 0, 'C', 99, 0, 1, 1, 0, 0, 0, 0, '', '0000-00-00'),
(37, 'isRealizedThrough', 0, '2017-11-07 22:25:33', 0, 'P', 99, 0, 1, 0, 0, 0, 0, 0, '', '0000-00-00'),
(38, 'hasFormExpression', 0, '2017-11-07 22:49:32', 0, 'P', 99, 0, 0, 0, 0, 0, 0, 0, '', '0000-00-00'),
(39, 'FormWork', 0, '2017-11-07 22:50:50', 0, 'C', 99, 0, 1, 0, 0, 0, 0, 0, 'https://www.ufrgs.br/tesauros/index.php/skos/rdf/12', '2018-04-12'),
(40, 'hasDateFirstWork', 0, '2017-11-07 23:43:06', 0, 'P', 99, 0, 1, 0, 0, 0, 0, 0, '', '0000-00-00'),
(41, 'hasIllustrator', 0, '2017-11-08 00:43:03', 0, 'P', 18, 0, 1, 0, 0, 0, 0, 0, '', '0000-00-00'),
(42, 'Image', 0, '2017-11-08 00:56:26', 0, 'C', 99, 0, 1, 0, 0, 0, 0, 0, '', '0000-00-00'),
(43, 'hasVolumeNumber', 0, '2017-11-08 02:18:28', 0, 'P', 99, 0, 1, 0, 0, 0, 0, 0, '', '0000-00-00'),
(44, 'Linguage', 0, '2017-11-08 02:18:28', 0, 'C', 99, 0, 1, 0, 0, 0, 0, 0, 'https://www.ufrgs.br/tesauros/index.php/skos/rdf/53', '2018-04-12'),
(45, 'Place', 0, '2017-11-08 15:53:34', 0, 'C', 99, 0, 1, 0, 1, 0, 0, 0, '', '2017-12-09'),
(91, 'lat', 12, '2018-01-22 20:48:13', 0, 'P', 99, 0, 1, 0, 0, 0, 0, 0, '', '2017-01-01'),
(46, 'hasTypePlace', 0, '2017-11-08 15:55:34', 0, 'P', 99, 0, 1, 0, 0, 0, 0, 0, '', '0000-00-00'),
(47, 'PlaceType', 0, '2017-11-08 15:56:37', 0, 'C', 99, 0, 1, 0, 0, 0, 0, 0, '', '0000-00-00'),
(48, 'TypeCorporateBody', 0, '2017-11-11 16:50:43', 0, 'C', 99, 0, 1, 0, 0, 0, 0, 0, 'https://www.ufrgs.br/tesauros/index.php/skos/rdf/54', '2018-04-12'),
(49, 'hasCorporateBodyType', 0, '2017-11-11 16:50:43', 0, 'P', 99, 0, 1, 0, 0, 0, 0, 0, '', '0000-00-00'),
(50, 'TypeOfAcquisition', 0, '2017-11-11 18:54:50', 0, 'C', 99, 0, 1, 0, 0, 0, 0, 0, 'https://www.ufrgs.br/tesauros/index.php/skos/rdf/52', '2018-04-12'),
(51, 'hasAcquisitionBy', 0, '2017-11-11 18:54:50', 0, 'P', 99, 0, 1, 0, 0, 0, 0, 0, '', '0000-00-00'),
(52, 'hasIdRegister', 0, '2017-11-11 22:32:06', 0, 'P', 99, 0, 1, 0, 1, 0, 0, 0, '', '0000-00-00'),
(53, 'isAppellationOfWork', 0, '2017-11-11 22:40:01', 0, 'P', 99, 0, 1, 0, 0, 0, 0, 0, '', '0000-00-00'),
(54, 'isAppellationOfExpression', 0, '2017-11-12 00:03:17', 0, 'P', 99, 0, 1, 0, 0, 0, 0, 0, '', '0000-00-00'),
(55, 'isAppellationOfManifestation', 0, '2017-11-12 00:09:54', 0, 'P', 99, 0, 1, 0, 0, 0, 0, 0, '', '0000-00-00'),
(56, 'Edition', 0, '2017-11-12 02:21:42', 0, 'C', 99, 0, 1, 0, 0, 0, 0, 0, 'https://www.ufrgs.br/tesauros/index.php/skos/rdf/13', '2018-02-26'),
(57, 'isEdition', 0, '2017-11-12 02:21:42', 0, 'P', 99, 0, 1, 0, 0, 0, 0, 0, '', '0000-00-00'),
(58, 'isPlaceOfPublication', 0, '2017-11-12 02:26:13', 0, 'P', 99, 0, 1, 0, 0, 0, 0, 0, '', '0000-00-00'),
(59, 'dateOfPublication', 0, '2017-11-12 02:31:21', 0, 'P', 99, 0, 1, 0, 0, 0, 0, 0, '', '0000-00-00'),
(60, 'isPublisher', 0, '2017-11-12 02:31:21', 0, 'P', 99, 0, 1, 0, 0, 0, 0, 0, '', '0000-00-00'),
(61, 'FormOfCarrier', 0, '2017-11-12 02:40:52', 0, 'C', 99, 0, 1, 1, 0, 0, 0, 0, '', '0000-00-00'),
(62, 'isFormOfCarrier', 0, '2017-11-12 02:40:52', 0, 'P', 99, 0, 1, 0, 0, 0, 0, 0, '', '0000-00-00'),
(63, 'ISBN', 0, '2017-11-12 02:48:32', 0, 'C', 99, 0, 1, 1, 1, 0, 0, 0, '', '0000-00-00'),
(64, 'CDU', 0, '2017-11-12 10:47:53', 0, 'C', 99, 0, 1, 0, 1, 0, 0, 0, 'https://www.ufrgs.br/tesauros/index.php/skos/rdf/61', '2018-04-12'),
(65, 'hasClassificationCDU', 0, '2017-11-12 10:47:53', 0, 'P', 99, 0, 1, 0, 1, 0, 0, 0, '', '0000-00-00'),
(66, 'CDD', 0, '2017-11-12 12:01:40', 0, 'C', 99, 0, 1, 1, 1, 0, 0, 0, '', '0000-00-00'),
(67, 'hasClassificationCDD', 0, '2017-11-12 12:01:40', 0, 'P', 99, 0, 1, 0, 1, 0, 0, 0, '', '0000-00-00'),
(68, 'hasTranslator', 0, '2017-11-12 14:10:12', 0, 'P', 21, 0, 1, 0, 1, 0, 0, 0, '', '0000-00-00'),
(69, 'hasImageDescription', 0, '2017-11-13 09:54:27', 0, 'P', 70, 0, 1, 0, 0, 0, 0, 0, '', '0000-00-00'),
(70, 'hasImageSize', 0, '2017-11-13 09:54:27', 0, 'P', 90, 0, 1, 0, 0, 0, 0, 0, '', '0000-00-00'),
(71, 'hasImageChecksum', 0, '2017-11-13 09:54:47', 0, 'P', 99, 0, 1, 0, 0, 0, 0, 0, '', '0000-00-00'),
(72, 'Number', 0, '2017-11-13 10:02:34', 0, 'C', 99, 0, 1, 1, 0, 0, 0, 1, '', '0000-00-00'),
(73, 'Checksum', 0, '2017-11-13 10:06:27', 0, 'C', 99, 0, 1, 0, 0, 0, 0, 0, '', '0000-00-00'),
(74, 'FileType', 0, '2017-11-13 10:11:51', 0, 'C', 99, 0, 1, 1, 0, 0, 0, 0, '', '0000-00-00'),
(75, 'hasFileType', 0, '2017-11-13 10:11:51', 0, 'P', 40, 0, 1, 0, 0, 0, 0, 0, '', '0000-00-00'),
(76, 'hasFileStorage', 0, '2017-11-13 10:14:42', 0, 'P', 99, 0, 1, 0, 0, 0, 0, 0, '', '0000-00-00'),
(77, 'FileStorage', 0, '2017-11-13 10:15:20', 0, 'C', 99, 0, 1, 0, 0, 0, 0, 0, '', '0000-00-00'),
(78, 'hasImageWidth', 0, '2017-11-13 10:43:02', 0, 'P', 80, 0, 1, 0, 0, 0, 0, 0, '', '0000-00-00'),
(79, 'hasImageHeight', 0, '2017-11-13 10:43:02', 0, 'P', 81, 0, 1, 0, 0, 0, 0, 0, '', '0000-00-00'),
(80, 'hasPlaceItem', 0, '2017-11-13 14:31:46', 0, 'P', 99, 0, 1, 0, 0, 0, 0, 0, '', '0000-00-00'),
(81, 'hasWayOfAcquisition', 0, '2017-11-13 14:31:46', 0, 'P', 99, 0, 1, 0, 0, 0, 0, 0, '', '0000-00-00'),
(82, 'hasItemStatusCataloging', 0, '2017-11-15 11:24:46', 0, 'P', 99, 0, 1, 0, 0, 0, 0, 0, '', '0000-00-00'),
(83, 'ItemStatusCataloging', 0, '2017-11-15 11:24:46', 0, 'C', 99, 0, 1, 1, 0, 0, 0, 0, '', '0000-00-00'),
(84, 'DateTime', 0, '2017-11-15 11:32:53', 0, 'C', 99, 0, 1, 0, 0, 0, 0, 0, '', '0000-00-00'),
(85, 'hasDateTime', 0, '2017-11-15 11:32:53', 0, 'P', 99, 0, 1, 0, 0, 0, 0, 0, '', '0000-00-00'),
(86, 'hasLanguageExpression', 0, '2017-12-03 23:50:28', 0, 'P', 99, 0, 1, 0, 0, 0, 0, 0, '', '0000-00-00'),
(87, 'primaryTopic', 10, '2017-12-08 08:17:36', 0, 'P', 99, 0, 1, 0, 0, 0, 0, 0, '', '0000-00-00'),
(88, 'isEmbodiedIn', 0, '2017-12-09 00:27:56', 0, 'P', 99, 0, 1, 0, 0, 0, 0, 0, '', '0000-00-00'),
(89, 'hasPage', 0, '2017-12-11 21:57:47', 0, 'P', 99, 0, 1, 0, 0, 0, 0, 0, '', '0000-00-00'),
(90, 'Pages', 0, '2017-12-11 22:03:20', 0, 'C', 99, 0, 1, 1, 0, 1, 0, 0, '', '0000-00-00'),
(92, 'long', 12, '2018-01-22 20:48:13', 0, 'P', 99, 0, 1, 0, 0, 0, 0, 0, '', '2017-01-01'),
(93, 'Library', 0, '2018-01-30 14:08:33', 0, 'C', 99, 0, 1, 1, 1, 0, 0, 0, '', '0000-00-00'),
(94, 'Bookcase', 0, '2018-01-30 14:10:30', 0, 'C', 99, 0, 1, 1, 1, 0, 0, 0, '', '0000-00-00'),
(95, 'hasRegisterId', 0, '2018-01-30 14:20:53', 0, 'P', 7, 0, 1, 0, 1, 0, 0, 0, '', '0000-00-00'),
(96, 'isExemplifiedBy', 0, '2018-01-30 19:11:50', 0, 'P', 99, 0, 1, 0, 1, 0, 0, 0, '', '0000-00-00'),
(97, 'isOwnedBy', 0, '2018-01-30 19:17:20', 0, 'P', 5, 0, 1, 0, 1, 0, 0, 0, '', '0000-00-00'),
(98, 'hasLocatedIn', 0, '2018-01-30 19:19:00', 0, 'P', 6, 0, 1, 0, 0, 0, 0, 0, '', '0000-00-00'),
(99, 'wayOfAcquisition', 0, '2018-02-01 14:16:47', 0, 'P', 99, 0, 1, 1, 1, 0, 0, 0, '', '0000-00-00'),
(100, 'itHasPrefaceOf', 0, '2018-02-08 14:10:52', 0, 'P', 99, 0, 1, 0, 1, 0, 0, 0, '', '0000-00-00'),
(101, 'itHasIntroductionOf', 0, '2018-02-08 14:13:05', 0, 'P', 99, 0, 1, 0, 1, 0, 0, 0, '', '0000-00-00'),
(102, 'hasSerieName', 0, '2018-02-09 12:08:13', 0, 'P', 99, 0, 1, 0, 1, 0, 0, 0, '', '0000-00-00'),
(103, 'SerieName', 0, '2018-02-09 12:09:25', 0, 'C', 99, 0, 1, 1, 1, 0, 0, 0, '', '0000-00-00'),
(104, 'hasAdaptedBy', 0, '2018-02-09 16:43:00', 0, 'P', 99, 0, 1, 0, 1, 0, 0, 0, '', '0000-00-00'),
(105, 'hasIncludedIn', 0, '2018-02-09 16:48:17', 0, 'P', 99, 0, 1, 1, 1, 0, 0, 0, '', '0000-00-00'),
(106, 'hasTitleAlternative', 0, '2018-02-14 16:44:15', 0, 'P', 99, 0, 1, 0, 1, 0, 0, 0, '', '0000-00-00'),
(107, 'Cutter', 0, '2018-02-22 16:42:44', 0, 'C', 99, 0, 1, 0, 0, 0, 0, 0, '', '0000-00-00'),
(108, 'hasAdvisor', 0, '2018-03-14 12:19:00', 0, 'P', 99, 0, 1, 1, 1, 0, 0, 0, '', '0000-00-00');

-- --------------------------------------------------------

--
-- Table structure for table `rdf_form_rule`
--

CREATE TABLE IF NOT EXISTS `rdf_form_rule` (
`id_rr` bigint(20) unsigned NOT NULL,
  `rr_field` text NOT NULL,
  `rr_description` char(200) NOT NULL,
  `rr_active` int(11) NOT NULL DEFAULT '1'
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=7 ;

--
-- Dumping data for table `rdf_form_rule`
--

INSERT INTO `rdf_form_rule` (`id_rr`, `rr_field`, `rr_description`, `rr_active`) VALUES
(1, '$H', 'Hidden (oculto)', 1),
(2, '$S100', 'String até 100 caracteres', 1),
(3, '$T80:5', 'Texto com 05 linhas', 1),
(4, '$T80:10', 'Texto com 10 linhas', 1),
(5, '$S250', 'String com 250 de tamanho', 1),
(6, '$Q id_au:au_descript:rdf_authority', 'Lista de Autoridades', 1);

-- --------------------------------------------------------

--
-- Table structure for table `rdf_image_concept`
--

CREATE TABLE IF NOT EXISTS `rdf_image_concept` (
`id_ic` bigint(20) unsigned NOT NULL,
  `ic_concept` int(11) NOT NULL,
  `ic_url` varchar(100) NOT NULL,
  `ic_created` int(11) NOT NULL,
  `ic_status` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `rdf_literal`
--

CREATE TABLE IF NOT EXISTS `rdf_literal` (
`id_rl` bigint(20) unsigned NOT NULL,
  `rl_type` int(11) NOT NULL,
  `rl_value` longtext NOT NULL,
  `rl_lang` varchar(6) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `rdf_literal_note`
--

CREATE TABLE IF NOT EXISTS `rdf_literal_note` (
`id_rl` bigint(20) unsigned NOT NULL,
  `rl_type` int(11) NOT NULL,
  `rl_value` longtext NOT NULL,
  `rl_lang` varchar(6) NOT NULL,
  `rl_created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `rl_c` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `rdf_literal_th`
--

CREATE TABLE IF NOT EXISTS `rdf_literal_th` (
`id_lt` bigint(20) unsigned NOT NULL,
  `lt_term` int(11) NOT NULL,
  `lt_thesauros` int(11) NOT NULL,
  `lt_status` int(11) NOT NULL DEFAULT '1'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `rdf_media`
--

CREATE TABLE IF NOT EXISTS `rdf_media` (
`id_rm` bigint(20) unsigned NOT NULL,
  `rm_conecpt` int(11) NOT NULL,
  `rm_filename` text NOT NULL,
  `rm_link` text NOT NULL,
  `rm_type` int(11) NOT NULL,
  `rm_size` double NOT NULL,
  `rm_ext` char(8) NOT NULL,
  `rm_created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `rm_status` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `rdf_perfil_propriety`
--

CREATE TABLE IF NOT EXISTS `rdf_perfil_propriety` (
`id_pa` bigint(20) unsigned NOT NULL,
  `pa_class` int(11) NOT NULL,
  `pa_propriety` int(11) NOT NULL,
  `pa_rule` int(11) NOT NULL,
  `pa_ord` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `rdf_prefil_class`
--

CREATE TABLE IF NOT EXISTS `rdf_prefil_class` (
`id_rpc` bigint(20) unsigned NOT NULL,
  `rpc_name` char(150) NOT NULL,
  `rpc_description` text NOT NULL,
  `rpc_active` int(11) NOT NULL DEFAULT '1'
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `rdf_prefil_class`
--

INSERT INTO `rdf_prefil_class` (`id_rpc`, `rpc_name`, `rpc_description`, `rpc_active`) VALUES
(1, 'Tesauros SKOS', 'skos:ConceptScheme', 1);

-- --------------------------------------------------------

--
-- Table structure for table `rdf_prefix`
--

CREATE TABLE IF NOT EXISTS `rdf_prefix` (
`id_prefix` bigint(20) unsigned NOT NULL,
  `prefix_ref` char(30) NOT NULL,
  `prefix_url` char(250) NOT NULL,
  `prefix_ativo` int(11) NOT NULL DEFAULT '1'
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=17 ;

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
(16, 'frbroo', '', 1);

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
  `rs_part_of` char(15) NOT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=74 ;

--
-- Dumping data for table `rdf_resource`
--

INSERT INTO `rdf_resource` (`id_rs`, `rs_prefix`, `rs_propriety`, `rs_propriety_inverse`, `rs_type`, `rs_mandatory`, `rs_marc`, `rs_group`, `rs_public`, `rs_part_of`) VALUES
(1, 12, 'class', '', 'P', 0, '', '', 0, ''),
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
(27, 4, 'altLabel', '', 'P', 0, '', 'TE', 1, 'FE'),
(28, 13, 'coordinationOf', '', 'P', 0, '', 'TR', 0, ''),
(29, 13, 'unionOf', '', 'P', 0, '', '', 0, ''),
(30, 4, 'definition', '', 'P', 0, '', 'NT', 0, ''),
(31, 4, 'scopeNote', '', 'P', 0, '', 'NT', 0, ''),
(32, 4, 'example', '', 'P', 0, '', 'NT', 0, ''),
(33, 4, 'changeNote', '', 'P', 0, '', 'NT', 0, ''),
(34, 4, 'hiddenLabel', '', 'P', 0, '', 'TH', 0, ''),
(35, 14, 'isPlural', 'isSingular', 'P', 0, '', 'FE', 1, ''),
(36, 14, 'literalForm', 'abbreviation_of', 'P', 0, '', 'FE', 1, ''),
(37, 14, 'isSingular', 'isPlural', 'P', 0, '', 'FE', 0, ''),
(38, 14, 'isFeminine', 'isMasculine', 'P', 0, '', 'FE', 0, ''),
(39, 14, 'isMasculine', 'isFeminine', 'P', 0, '', 'FE', 0, ''),
(40, 14, 'abbreviation_of', 'literalForm', 'P', 0, '', 'FE', 1, ''),
(41, 14, 'hiddenLabel', '', 'P', 0, '', 'FE', 0, ''),
(42, 14, 'garantiaLiteraria', '', 'P', 0, '', 'NT', 1, ''),
(43, 14, 'noteCited', '', 'P', 0, '', 'NT', 1, ''),
(44, 14, 'code', '', 'P', 0, '', 'FE', 1, ''),
(45, 14, 'acronym', '', 'P', 0, '', 'FE', 1, ''),
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
(59, 16, 'work', '', 'C', 0, '', 'ENTY', 0, ''),
(60, 16, 'manifestation', '', 'C', 0, '', '', 0, ''),
(61, 16, 'expression', '', 'C', 0, '', 'ENTY', 0, ''),
(62, 16, 'item', '', 'C', 0, '', 'ENTY', 0, ''),
(63, 14, 'is_gerund', '', 'P', 0, '', 'FE', 0, ''),
(64, 14, 'is_verbal_inflection', '', 'P', 0, '', 'FE', 0, ''),
(65, 14, 'is_synonymous', '', 'P', 0, '', 'FE', 1, ''),
(66, 16, 'nomen', '', 'C', 0, '', '', 1, ''),
(67, 3, 'Literal', '', 'C', 0, '', '', 1, ''),
(68, 13, 'coordinationOfActionProduct', '', 'P', 0, '', 'TR', 0, ''),
(69, 13, 'coordinationOfCauseEffect', '', 'P', 0, '', 'TR', 0, ''),
(70, 13, 'coordinationOfOpposition', '', 'P', 0, '', 'TR', 0, ''),
(71, 13, 'coordinationOfKinship', '', 'P', 0, '', 'TR', 0, ''),
(72, 13, 'coordinationOfPartOf', '', 'P', 0, '', 'TR', 0, ''),
(73, 13, 'coordinationOfProductCharacteristics', '', 'P', 0, '', 'TR', 0, '');

-- --------------------------------------------------------

--
-- Table structure for table `thesa`
--

CREATE TABLE IF NOT EXISTS `thesa` (
`id_thesa` bigint(20) unsigned NOT NULL,
  `thesa_name` varchar(200) NOT NULL,
  `thesa_url` varchar(255) NOT NULL,
  `thesa_prefix` varchar(30) NOT NULL,
  `thesa_contact` varchar(200) NOT NULL,
  `thesa_contact_email` varchar(200) NOT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `thesa`
--

INSERT INTO `thesa` (`id_thesa`, `thesa_name`, `thesa_url`, `thesa_prefix`, `thesa_contact`, `thesa_contact_email`) VALUES
(1, 'Thesa - UFRGS', 'https://www.ufrgs.br/tesauros/', 'thesa', '', '');

-- --------------------------------------------------------

--
-- Table structure for table `th_concept`
--

CREATE TABLE IF NOT EXISTS `th_concept` (
`id_c` bigint(20) unsigned NOT NULL,
  `c_th` int(11) NOT NULL,
  `c_concept` char(40) NOT NULL,
  `c_created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `c_agency` int(11) NOT NULL DEFAULT '1',
  `c_ativo` int(11) NOT NULL DEFAULT '1'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `th_concept_term`
--

CREATE TABLE IF NOT EXISTS `th_concept_term` (
`id_ct` bigint(20) unsigned NOT NULL,
  `ct_concept` int(11) NOT NULL,
  `ct_th` int(11) NOT NULL,
  `ct_term` int(11) NOT NULL,
  `ct_concept_2` int(11) NOT NULL,
  `ct_propriety` int(11) NOT NULL,
  `ct_created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `th_log`
--

CREATE TABLE IF NOT EXISTS `th_log` (
`id_lg` bigint(20) unsigned NOT NULL,
  `lg_c` int(11) NOT NULL,
  `lg_th` int(11) NOT NULL,
  `lg_user` int(11) NOT NULL,
  `lg_action` char(5) NOT NULL,
  `lg_descript` text NOT NULL,
  `lg_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `th_thesaurus`
--

CREATE TABLE IF NOT EXISTS `th_thesaurus` (
`id_pa` bigint(20) unsigned NOT NULL,
  `pa_name` char(150) NOT NULL,
  `pa_description` text NOT NULL,
  `pa_status` int(11) NOT NULL,
  `pa_created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `pa_update` date NOT NULL,
  `pa_classe` int(11) NOT NULL,
  `pa_creator` int(11) NOT NULL,
  `pa_hidden` int(11) NOT NULL DEFAULT '0',
  `pa_avaliacao` double NOT NULL,
  `pa_class` char(60) NOT NULL,
  `pa_version` varchar(10) NOT NULL,
  `pa_introdution` longtext NOT NULL,
  `pa_methodology` longtext NOT NULL,
  `pa_icone` char(50) NOT NULL,
  `pa_header` char(100) NOT NULL,
  `pa_audience` text NOT NULL,
  `pa_type` int(11) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `th_users`
--

CREATE TABLE IF NOT EXISTS `th_users` (
`id_ust` bigint(20) unsigned NOT NULL,
  `ust_user_id` int(11) NOT NULL,
  `ust_user_role` int(11) NOT NULL,
  `ust_th` int(11) NOT NULL,
  `ust_status` int(11) NOT NULL,
  `ust_created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
`id_us` bigint(20) unsigned NOT NULL,
  `us_nome` char(80) NOT NULL,
  `us_email` char(80) NOT NULL,
  `us_cidade` char(40) NOT NULL,
  `us_pais` char(40) NOT NULL,
  `us_codigo` char(7) NOT NULL,
  `us_link` char(80) NOT NULL,
  `us_ativo` int(1) NOT NULL,
  `us_nivel` char(1) NOT NULL,
  `us_image` text NOT NULL,
  `us_genero` char(1) NOT NULL,
  `us_verificado` char(1) NOT NULL,
  `us_autenticador` char(3) NOT NULL,
  `us_cadastro` int(11) NOT NULL,
  `us_revisoes` int(11) NOT NULL,
  `us_colaboracoes` int(11) NOT NULL,
  `us_acessos` int(11) NOT NULL,
  `us_pesquisa` int(11) NOT NULL,
  `us_erros` int(11) NOT NULL,
  `us_outros` int(11) NOT NULL,
  `us_last` int(11) NOT NULL,
  `us_perfil` text NOT NULL,
  `us_login` char(200) NOT NULL,
  `us_password` char(255) NOT NULL,
  `us_created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `us_last_access` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `us_institution` char(50) NOT NULL,
  `us_perfil_check` int(11) NOT NULL,
  `us_badge` char(12) NOT NULL
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=579 ;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id_us`, `us_nome`, `us_email`, `us_cidade`, `us_pais`, `us_codigo`, `us_link`, `us_ativo`, `us_nivel`, `us_image`, `us_genero`, `us_verificado`, `us_autenticador`, `us_cadastro`, `us_revisoes`, `us_colaboracoes`, `us_acessos`, `us_pesquisa`, `us_erros`, `us_outros`, `us_last`, `us_perfil`, `us_login`, `us_password`, `us_created`, `us_last_access`, `us_institution`, `us_perfil_check`, `us_badge`) VALUES
(1, 'Thesa', 'thesa', '', '', '0000001', '', 1, '9', '', 'M', '1', 'F', 20140706, 0, 0, 283, 0, 0, 0, 20170110, '#ADM#BIB', 'thesa', 'admin', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '', 0, '00001');

-- --------------------------------------------------------

--
-- Table structure for table `works`
--

CREATE TABLE IF NOT EXISTS `works` (
`id_w` bigint(20) unsigned NOT NULL,
  `w_id` char(20) NOT NULL,
  `w_status` int(11) NOT NULL DEFAULT '1',
  `w_create` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `w_update` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `works`
--

INSERT INTO `works` (`id_w`, `w_id`, `w_status`, `w_create`, `w_update`) VALUES
(1, 'w00000001', 1, '2016-10-19 20:13:28', '0000-00-00 00:00:00'),
(2, 'w00000002', 1, '2016-10-19 20:13:28', '0000-00-00 00:00:00');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `bugs`
--
ALTER TABLE `bugs`
 ADD UNIQUE KEY `id_bug` (`id_bug`);

--
-- Indexes for table `ci_sessions`
--
ALTER TABLE `ci_sessions`
 ADD PRIMARY KEY (`id`), ADD KEY `ci_sessions_timestamp` (`timestamp`);

--
-- Indexes for table `language`
--
ALTER TABLE `language`
 ADD UNIQUE KEY `id_lg` (`id_lg`);

--
-- Indexes for table `mensagem_own`
--
ALTER TABLE `mensagem_own`
 ADD UNIQUE KEY `id_m` (`id_m`);

--
-- Indexes for table `rdf`
--
ALTER TABLE `rdf`
 ADD UNIQUE KEY `id_rdf` (`id_rdf`);

--
-- Indexes for table `rdf_authority`
--
ALTER TABLE `rdf_authority`
 ADD UNIQUE KEY `id_au` (`id_au`);

--
-- Indexes for table `rdf_class`
--
ALTER TABLE `rdf_class`
 ADD UNIQUE KEY `id_c` (`id_c`), ADD UNIQUE KEY `classes` (`c_class`(30));

--
-- Indexes for table `rdf_form_rule`
--
ALTER TABLE `rdf_form_rule`
 ADD UNIQUE KEY `id_rr` (`id_rr`);

--
-- Indexes for table `rdf_image_concept`
--
ALTER TABLE `rdf_image_concept`
 ADD UNIQUE KEY `id_ic` (`id_ic`);

--
-- Indexes for table `rdf_literal`
--
ALTER TABLE `rdf_literal`
 ADD UNIQUE KEY `id_rl` (`id_rl`), ADD KEY `rl_index` (`id_rl`), ADD KEY `rl_value` (`rl_value`(20));

--
-- Indexes for table `rdf_literal_note`
--
ALTER TABLE `rdf_literal_note`
 ADD UNIQUE KEY `id_rl` (`id_rl`);

--
-- Indexes for table `rdf_literal_th`
--
ALTER TABLE `rdf_literal_th`
 ADD UNIQUE KEY `id_lt` (`id_lt`), ADD KEY `lt_term` (`lt_term`,`lt_thesauros`);

--
-- Indexes for table `rdf_media`
--
ALTER TABLE `rdf_media`
 ADD UNIQUE KEY `id_rm` (`id_rm`);

--
-- Indexes for table `rdf_perfil_propriety`
--
ALTER TABLE `rdf_perfil_propriety`
 ADD UNIQUE KEY `id_pa` (`id_pa`);

--
-- Indexes for table `rdf_prefil_class`
--
ALTER TABLE `rdf_prefil_class`
 ADD UNIQUE KEY `id_rpc` (`id_rpc`);

--
-- Indexes for table `rdf_prefix`
--
ALTER TABLE `rdf_prefix`
 ADD UNIQUE KEY `id_prefix` (`id_prefix`);

--
-- Indexes for table `rdf_resource`
--
ALTER TABLE `rdf_resource`
 ADD UNIQUE KEY `id_rs` (`id_rs`), ADD KEY `rs_prefix` (`rs_prefix`,`rs_propriety`);

--
-- Indexes for table `thesa`
--
ALTER TABLE `thesa`
 ADD UNIQUE KEY `id_thesa` (`id_thesa`);

--
-- Indexes for table `th_concept`
--
ALTER TABLE `th_concept`
 ADD UNIQUE KEY `id_c` (`id_c`);

--
-- Indexes for table `th_concept_term`
--
ALTER TABLE `th_concept_term`
 ADD UNIQUE KEY `id_ct` (`id_ct`), ADD KEY `ct_term` (`ct_term`), ADD KEY `ct_th` (`ct_th`), ADD KEY `ct_th_term` (`ct_term`,`ct_th`), ADD KEY `ct_concept` (`ct_concept`,`ct_concept_2`);

--
-- Indexes for table `th_log`
--
ALTER TABLE `th_log`
 ADD UNIQUE KEY `id_lg` (`id_lg`);

--
-- Indexes for table `th_thesaurus`
--
ALTER TABLE `th_thesaurus`
 ADD UNIQUE KEY `id_pa` (`id_pa`);

--
-- Indexes for table `th_users`
--
ALTER TABLE `th_users`
 ADD UNIQUE KEY `id_ust` (`id_ust`), ADD KEY `ust_user_id` (`ust_user_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
 ADD UNIQUE KEY `id_us` (`id_us`);

--
-- Indexes for table `works`
--
ALTER TABLE `works`
 ADD UNIQUE KEY `id_w` (`id_w`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `bugs`
--
ALTER TABLE `bugs`
MODIFY `id_bug` bigint(20) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `language`
--
ALTER TABLE `language`
MODIFY `id_lg` bigint(20) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=512;
--
-- AUTO_INCREMENT for table `mensagem_own`
--
ALTER TABLE `mensagem_own`
MODIFY `id_m` bigint(20) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `rdf`
--
ALTER TABLE `rdf`
MODIFY `id_rdf` bigint(20) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `rdf_authority`
--
ALTER TABLE `rdf_authority`
MODIFY `id_au` bigint(20) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `rdf_class`
--
ALTER TABLE `rdf_class`
MODIFY `id_c` bigint(20) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=109;
--
-- AUTO_INCREMENT for table `rdf_form_rule`
--
ALTER TABLE `rdf_form_rule`
MODIFY `id_rr` bigint(20) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=7;
--
-- AUTO_INCREMENT for table `rdf_image_concept`
--
ALTER TABLE `rdf_image_concept`
MODIFY `id_ic` bigint(20) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `rdf_literal`
--
ALTER TABLE `rdf_literal`
MODIFY `id_rl` bigint(20) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `rdf_literal_note`
--
ALTER TABLE `rdf_literal_note`
MODIFY `id_rl` bigint(20) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `rdf_literal_th`
--
ALTER TABLE `rdf_literal_th`
MODIFY `id_lt` bigint(20) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `rdf_media`
--
ALTER TABLE `rdf_media`
MODIFY `id_rm` bigint(20) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `rdf_perfil_propriety`
--
ALTER TABLE `rdf_perfil_propriety`
MODIFY `id_pa` bigint(20) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `rdf_prefil_class`
--
ALTER TABLE `rdf_prefil_class`
MODIFY `id_rpc` bigint(20) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `rdf_prefix`
--
ALTER TABLE `rdf_prefix`
MODIFY `id_prefix` bigint(20) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=17;
--
-- AUTO_INCREMENT for table `rdf_resource`
--
ALTER TABLE `rdf_resource`
MODIFY `id_rs` bigint(20) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=74;
--
-- AUTO_INCREMENT for table `thesa`
--
ALTER TABLE `thesa`
MODIFY `id_thesa` bigint(20) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `th_concept`
--
ALTER TABLE `th_concept`
MODIFY `id_c` bigint(20) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `th_concept_term`
--
ALTER TABLE `th_concept_term`
MODIFY `id_ct` bigint(20) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `th_log`
--
ALTER TABLE `th_log`
MODIFY `id_lg` bigint(20) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `th_thesaurus`
--
ALTER TABLE `th_thesaurus`
MODIFY `id_pa` bigint(20) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `th_users`
--
ALTER TABLE `th_users`
MODIFY `id_ust` bigint(20) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
MODIFY `id_us` bigint(20) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=579;
--
-- AUTO_INCREMENT for table `works`
--
ALTER TABLE `works`
MODIFY `id_w` bigint(20) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=3;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
