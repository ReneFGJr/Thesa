CREATE TABLE `rdf_resource` (
  `id_rs` serial NOT NULL,
  `rs_prefix` int(11) NOT NULL,
  `rs_propriety` char(100) NOT NULL,
  `rs_propriety_inverse` char(100) NOT NULL,
  `rs_type` char(1) NOT NULL,
  `rs_mandatory` int(11) NOT NULL DEFAULT '0',
  `rs_marc` varchar(30) NOT NULL,
  `rs_group` varchar(10) NOT NULL,
  `rs_public` int(11) NOT NULL DEFAULT '0',
  `rs_part_of` char(15) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
