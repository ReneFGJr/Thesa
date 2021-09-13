CREATE TABLE `rdf_literal_th` (
  `id_lt` serial NOT NULL,
  `lt_term` int(11) NOT NULL,
  `lt_thesauros` int(11) NOT NULL,
  `lt_status` int(11) NOT NULL DEFAULT '1'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
