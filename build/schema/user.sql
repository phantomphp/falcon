# user
create table `user` (
    `id` int unsigned not null auto_increment,
    `uuid` varchar(36) NOT NULL,
    `username` varchar(128),
    `password` varchar(64), /* this will be sha1 hash */
    `admin` tinyint unsigned not null default 0,
    `active` tinyint unsigned not null default 1,
    `deleted` tinyint unsigned not null default 0,
    `created_date` timestamp NULL DEFAULT NULL,
    `modified_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    KEY (`uuid`),
    UNIQUE KEY (username(16))
) ENGINE=INNODB;