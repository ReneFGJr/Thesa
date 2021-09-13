CREATE TABLE `rdf_literal_note` (
  `id_rl` serial NOT NULL,
  `rl_type` int(11) NOT NULL,
  `rl_value` longtext NOT NULL,
  `rl_lang` varchar(6) NOT NULL,
  `rl_created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `rl_c` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
