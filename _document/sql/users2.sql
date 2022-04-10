CREATE TABLE `users2` (
  `id_us` serial AUTO_INCREMENT,
  `us_nome` varchar(100) NOT NULL,
  `us_email` varchar(100) NOT NULL,
  `us_image` varchar(100) NOT NULL,
  `us_genero` varchar(1) NOT NULL,
  `us_verificado` int(11) NOT NULL DEFAULT '0',
  `us_rdf` int(11) NOT NULL DEFAULT '0',
  `us_login` varchar(100) NOT NULL,
  `us_password` varchar(50) NOT NULL,
  `us_password_method` varchar(3) NOT NULL,
  `us_oauth2` varchar(20) NOT NULL,
  `us_lastaccess` int(11) NOT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) 
