# product

create table `product`(
    `id` int unsigned not null auto_increment,
    `uuid` varchar(36) NOT NULL,
    `name` varchar(128),
    `active` tinyint unsigned not null default 1,
    `deleted` tinyint unsigned not null default 0,
    `created_date` timestamp NULL DEFAULT NULL,
    `modified_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    KEY (`uuid`)
) ENGINE=INNODB;