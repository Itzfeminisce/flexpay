-- Adminer 4.3.1 MySQL dump

SET NAMES utf8;
SET time_zone = '+00:00';
SET foreign_key_checks = 0;
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';

DROP TABLE IF EXISTS `user_biodata`;
CREATE TABLE `user_biodata` (
  `id` int(100) NOT NULL AUTO_INCREMENT,
  `fn` varchar(200) NOT NULL,
  `ln` varchar(200) NOT NULL,
  `em` varchar(200) NOT NULL,
  `ph` int(200) NOT NULL,
  `rf` varchar(200) NOT NULL,
  `pwd` varchar(200) NOT NULL,
  `pm` varchar(200) NOT NULL,
  `pmc` int(100) NOT NULL,
  `fprid` varchar(200) NOT NULL,
  `date` varchar(200) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


-- 2019-09-08 13:10:31