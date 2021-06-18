CREATE TABLE `th_concept` (
  `id_c` serial NOT NULL,
  `c_th` int(11) NOT NULL,
  `c_concept` char(40) NOT NULL,
  `c_created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `c_agency` int(11) NOT NULL DEFAULT '1',
  `c_ativo` int(11) NOT NULL DEFAULT '1'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
