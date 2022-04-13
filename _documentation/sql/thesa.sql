-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Tempo de geração: 13-Abr-2022 às 02:25
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
-- Estrutura da tabela `language`
--

DROP TABLE IF EXISTS `language`;
CREATE TABLE IF NOT EXISTS `language` (
  `id_lg` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `lg_code` varchar(6) DEFAULT NULL,
  `lg_language` varchar(80) DEFAULT NULL,
  `lg_order` int(11) DEFAULT NULL,
  `lg_active` int(11) NOT NULL DEFAULT '1',
  `lg_cod_marc` char(5) NOT NULL,
  UNIQUE KEY `id_lg` (`id_lg`)
) ENGINE=InnoDB AUTO_INCREMENT=512 DEFAULT CHARSET=utf8;

--
-- Extraindo dados da tabela `language`
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
(143, 'fre', 'French', 6, 1, 'fre'),
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
(159, 'ger', 'Alemão / German', 10, 1, 'ger'),
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
(178, 'gwi', 'Gwich\'in', 99, 0, 'gwi'),
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
(333, 'nqo', 'N\'Ko', 99, 0, 'nqo'),
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
(421, 'spa', 'Español', 5, 1, 'spa'),
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
-- Estrutura da tabela `language_th`
--

DROP TABLE IF EXISTS `language_th`;
CREATE TABLE IF NOT EXISTS `language_th` (
  `id_lgt` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `lgt_th` int(11) NOT NULL,
  `lgt_language` int(11) NOT NULL,
  `lgt_order` int(11) NOT NULL DEFAULT '99',
  `lgt_created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  UNIQUE KEY `id_th_lg` (`id_lgt`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Extraindo dados da tabela `language_th`
--

INSERT INTO `language_th` (`id_lgt`, `lgt_th`, `lgt_language`, `lgt_order`, `lgt_created`) VALUES
(1, 1, 364, 0, '2022-04-10 16:16:19');

-- --------------------------------------------------------

--
-- Estrutura da tabela `mensagem_own`
--

DROP TABLE IF EXISTS `mensagem_own`;
CREATE TABLE IF NOT EXISTS `mensagem_own` (
  `id_m` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
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
  `mailtype` char(5) NOT NULL,
  UNIQUE KEY `id_m` (`id_m`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estrutura da tabela `th_concept`
--

DROP TABLE IF EXISTS `th_concept`;
CREATE TABLE IF NOT EXISTS `th_concept` (
  `id_c` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `c_th` int(11) NOT NULL,
  `c_concept` char(40) NOT NULL,
  `c_created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `c_agency` int(11) NOT NULL DEFAULT '1',
  `c_ativo` int(11) NOT NULL DEFAULT '1',
  UNIQUE KEY `id_c` (`id_c`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura da tabela `th_concept_relation`
--

DROP TABLE IF EXISTS `th_concept_relation`;
CREATE TABLE IF NOT EXISTS `th_concept_relation` (
  `id_tg` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `tg_concept_1` int(11) NOT NULL,
  `tg_concept_2` int(11) NOT NULL,
  `tg_propriety` int(11) NOT NULL,
  `tg_th` int(11) NOT NULL DEFAULT '0',
  `tg_active` int(11) NOT NULL DEFAULT '1',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL,
  UNIQUE KEY `id_tg` (`id_tg`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Extraindo dados da tabela `th_concept_relation`
--

INSERT INTO `th_concept_relation` (`id_tg`, `tg_concept_1`, `tg_concept_2`, `tg_propriety`, `tg_th`, `tg_active`, `created_at`, `updated_at`) VALUES
(1, 1, 2, 2, 2, 1, '2022-04-13 02:02:42', NULL);

-- --------------------------------------------------------

--
-- Estrutura da tabela `th_concept_term`
--

DROP TABLE IF EXISTS `th_concept_term`;
CREATE TABLE IF NOT EXISTS `th_concept_term` (
  `id_ct` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `ct_concept` int(11) NOT NULL,
  `ct_th` int(11) NOT NULL,
  `ct_term` int(11) NOT NULL,
  `ct_use` int(11) NOT NULL DEFAULT '0',
  `ct_propriety` int(11) NOT NULL,
  `ct_created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  UNIQUE KEY `id_ct` (`id_ct`),
  KEY `ct_term` (`ct_term`),
  KEY `ct_th` (`ct_th`),
  KEY `ct_th_term` (`ct_term`,`ct_th`),
  KEY `ct_concept` (`ct_concept`,`ct_use`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura da tabela `th_literal`
--

DROP TABLE IF EXISTS `th_literal`;
CREATE TABLE IF NOT EXISTS `th_literal` (
  `id_n` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `n_type` int(11) NOT NULL,
  `n_name` longtext NOT NULL,
  `n_lang` varchar(6) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL,
  UNIQUE KEY `id_rl` (`id_n`),
  KEY `rl_index` (`id_n`),
  KEY `rl_value` (`n_name`(20))
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura da tabela `th_literal_th`
--

DROP TABLE IF EXISTS `th_literal_th`;
CREATE TABLE IF NOT EXISTS `th_literal_th` (
  `id_lt` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `lt_term` int(11) NOT NULL,
  `lt_th` int(11) NOT NULL,
  `lt_status` int(11) NOT NULL DEFAULT '1',
  UNIQUE KEY `id_lt` (`id_lt`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Estrutura da tabela `th_note`
--

DROP TABLE IF EXISTS `th_note`;
CREATE TABLE IF NOT EXISTS `th_note` (
  `id_nt` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `nt_prop` int(11) DEFAULT NULL,
  `nt_term` int(11) DEFAULT NULL,
  `nt_th` int(11) DEFAULT NULL,
  `nt_user` int(11) DEFAULT NULL,
  `nt_contentet` text COLLATE utf8_bin,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `update_at` timestamp NULL DEFAULT NULL,
  UNIQUE KEY `id_nt` (`id_nt`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

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

-- --------------------------------------------------------

--
-- Estrutura da tabela `th_proprieties_prefix`
--

DROP TABLE IF EXISTS `th_proprieties_prefix`;
CREATE TABLE IF NOT EXISTS `th_proprieties_prefix` (
  `id_prefix` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `prefix_name` char(50) COLLATE utf8_bin NOT NULL,
  `prefix_url` char(100) COLLATE utf8_bin NOT NULL,
  UNIQUE KEY `id_prefix` (`id_prefix`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Extraindo dados da tabela `th_proprieties_prefix`
--

INSERT INTO `th_proprieties_prefix` (`id_prefix`, `prefix_name`, `prefix_url`) VALUES
(1, 'skos', ''),
(2, 'skosxl', '');

-- --------------------------------------------------------

--
-- Estrutura da tabela `th_thesaurus`
--

DROP TABLE IF EXISTS `th_thesaurus`;
CREATE TABLE IF NOT EXISTS `th_thesaurus` (
  `id_pa` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `pa_name` char(150) NOT NULL,
  `pa_achronic` char(100) NOT NULL,
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
  `pa_type` int(11) NOT NULL DEFAULT '1',
  `pa_language` int(11) NOT NULL DEFAULT '364',
  `pa_multi_language` int(11) NOT NULL DEFAULT '0',
  UNIQUE KEY `id_pa` (`id_pa`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura da tabela `th_thesaurus_class`
--

DROP TABLE IF EXISTS `th_thesaurus_class`;
CREATE TABLE IF NOT EXISTS `th_thesaurus_class` (
  `id_pac` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `pac_name` char(50) NOT NULL,
  UNIQUE KEY `id_pac` (`id_pac`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

--
-- Extraindo dados da tabela `th_thesaurus_class`
--

INSERT INTO `th_thesaurus_class` (`id_pac`, `pac_name`) VALUES
(1, 'Vocabulary');

-- --------------------------------------------------------

--
-- Estrutura da tabela `th_users`
--

DROP TABLE IF EXISTS `th_users`;
CREATE TABLE IF NOT EXISTS `th_users` (
  `id_ust` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `ust_user_id` int(11) NOT NULL,
  `ust_user_role` int(11) NOT NULL,
  `ust_th` int(11) NOT NULL,
  `ust_status` int(11) NOT NULL,
  `ust_created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `ust_tipo` int(11) NOT NULL DEFAULT '1',
  UNIQUE KEY `id_ust` (`id_ust`),
  KEY `ust_user_id` (`ust_user_id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

--
-- Extraindo dados da tabela `th_users`
--

INSERT INTO `th_users` (`id_ust`, `ust_user_id`, `ust_user_role`, `ust_th`, `ust_status`, `ust_created`, `ust_tipo`) VALUES
(1, 1, 1, 1, 1, '2022-04-10 14:25:20', 1),
(2, 1, 1, 2, 1, '2022-04-12 22:04:47', 1);

-- --------------------------------------------------------

--
-- Estrutura da tabela `th_users_perfil`
--

DROP TABLE IF EXISTS `th_users_perfil`;
CREATE TABLE IF NOT EXISTS `th_users_perfil` (
  `id_up` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `up_tipo` char(100) NOT NULL,
  `up_order` int(11) NOT NULL DEFAULT '0',
  UNIQUE KEY `id_up` (`id_up`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

--
-- Extraindo dados da tabela `th_users_perfil`
--

INSERT INTO `th_users_perfil` (`id_up`, `up_tipo`, `up_order`) VALUES
(1, 'Author', 1),
(2, 'Collaborator', 2);

-- --------------------------------------------------------

--
-- Estrutura da tabela `users2`
--

DROP TABLE IF EXISTS `users2`;
CREATE TABLE IF NOT EXISTS `users2` (
  `id_us` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `us_nome` varchar(100) COLLATE utf8_bin NOT NULL,
  `us_email` varchar(100) COLLATE utf8_bin NOT NULL,
  `us_image` varchar(100) COLLATE utf8_bin NOT NULL,
  `us_genero` varchar(1) COLLATE utf8_bin NOT NULL,
  `us_verificado` int(11) NOT NULL DEFAULT '0',
  `us_rdf` int(11) NOT NULL DEFAULT '0',
  `us_login` varchar(100) COLLATE utf8_bin NOT NULL,
  `us_password` varchar(50) COLLATE utf8_bin NOT NULL,
  `us_password_method` varchar(3) COLLATE utf8_bin NOT NULL,
  `us_oauth2` varchar(20) COLLATE utf8_bin NOT NULL,
  `us_lastaccess` int(11) NOT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  UNIQUE KEY `id_us` (`id_us`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Extraindo dados da tabela `users2`
--

INSERT INTO `users2` (`id_us`, `us_nome`, `us_email`, `us_image`, `us_genero`, `us_verificado`, `us_rdf`, `us_login`, `us_password`, `us_password_method`, `us_oauth2`, `us_lastaccess`, `created_at`, `updated_at`) VALUES
(1, 'admin', 'admin', '', '', 1, 0, 'admin', '21232f297a57a5a743894a0e4a801fc3', 'MD5', '', 2147483647, '2022-03-02 12:57:52', '2022-03-12 12:42:05'),
(4, 'Rene Faustino Gabriel Junior', 'renefgj@gmail.com', '', '', 0, 0, '', '7fa630f8121046da3d4fbfc91e6dc9e4', 'MD5', '', 0, '2022-04-10 08:44:56', '2022-04-10 08:44:56');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
