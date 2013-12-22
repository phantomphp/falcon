CREATE TABLE `product` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(36) NOT NULL,
  `name` varchar(128) DEFAULT NULL,
  `year` smallint(6) DEFAULT NULL,
  `designer` varchar(128) DEFAULT NULL,
  `publisher` varchar(128) DEFAULT NULL,
  `sku` varchar(64) DEFAULT NULL,
  `upc` varchar(64) DEFAULT NULL,
  `msrp` decimal(15,4) DEFAULT NULL,
  `active` tinyint(3) unsigned NOT NULL DEFAULT '1',
  `deleted` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `created_date` timestamp NULL DEFAULT NULL,
  `modified_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `uuid` (`uuid`)
) ENGINE=InnoDB;