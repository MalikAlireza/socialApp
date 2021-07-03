CREATE TABLE `admins` (
  `id` int(11) NOT NULL,
  `username` varchar(125) COLLATE utf8_unicode_ci NOT NULL,
  `avatar` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `cover` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `password` varchar(65) CHARACTER SET latin1 DEFAULT NULL,
  `salt` varbinary(65) DEFAULT NULL,
  `first_name` varchar(100) CHARACTER SET latin1 NOT NULL,
  `last_name` varchar(125) COLLATE utf8_unicode_ci NOT NULL,
  `bio` text COLLATE utf8_unicode_ci,
  `email` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `joined` datetime NOT NULL,
  `group` int(11) NOT NULL,
  `facebook_id` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `gplus_id` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


INSERT INTO `admins` (`id`, `username`, `avatar`, `cover`, `password`, `salt`, `first_name`, `last_name`, `bio`, `email`, `joined`, `group`, `facebook_id`, `gplus_id`) VALUES
(1, 'demouser', NULL, NULL, '3aa09044cd9412bb0d7c7421e00aec05bed787cd666564f3aefb3758ace037bc', 0x83f2c4aee3464819681096e7710444703d448d0aa4e17c5ba788039a41a6fd55, 'admin', 'admin', NULL, NULL, '2017-04-27 09:35:52', 1, NULL, NULL);


CREATE TABLE `admins_session` (
  `user_id` int(11) NOT NULL,
  `hash` varchar(100) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;