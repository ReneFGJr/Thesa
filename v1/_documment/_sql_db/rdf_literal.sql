CREATE TABLE `rdf_literal` (
  `id_rl` serial NOT NULL,
  `rl_type` int(11) NOT NULL,
  `rl_value` longtext NOT NULL,
  `rl_lang` varchar(6) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
