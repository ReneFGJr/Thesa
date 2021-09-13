CREATE TABLE `thesa` (
  `id_thesa` serial NOT NULL,
  `thesa_name` varchar(200) NOT NULL,
  `thesa_url` varchar(255) NOT NULL,
  `thesa_prefix` varchar(30) NOT NULL,
  `thesa_contact` varchar(200) NOT NULL,
  `thesa_contact_email` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

