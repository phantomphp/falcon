# person

CREATE TABLE `person` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(36) NOT NULL,
  `firstname` varchar(64) DEFAULT NULL,
  `middlename` varchar(64) DEFAULT NULL,
  `lastname` varchar(64) DEFAULT NULL,
  `gender` tinyint(4) NOT NULL DEFAULT '0',
  `dob` datetime DEFAULT NULL,
  `active` tinyint(3) unsigned NOT NULL DEFAULT '1',
  `deleted` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `created_date` timestamp NULL DEFAULT NULL,
  `modified_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `uuid` (`uuid`)
) ENGINE=InnoDB;