# product description

create table `product_description`(
    `product_id` int unsigned not null,
    `title` varchar(128),
    `description` text,
    KEY (`product_id`)
) ENGINE=INNODB;