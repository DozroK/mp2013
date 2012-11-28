# ************************************************************
# Sequel Pro SQL dump
# Version 3408
#
# http://www.sequelpro.com/
# http://code.google.com/p/sequel-pro/
#
# Host: 127.0.0.1 (MySQL 5.5.25)
# Database: mp
# Generation Time: 2012-11-21 13:53:54 +0000
# ************************************************************


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


# Dump of table event
# ------------------------------------------------------------

DROP TABLE IF EXISTS `event`;

CREATE TABLE `event` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT 'http://schema.org/Event',
  `name` varchar(255) DEFAULT '' COMMENT 'http://schema.org/Event',
  `lang` char(2) DEFAULT NULL COMMENT 'http://en.wikipedia.org/wiki/ISO_639-1',
  `type` varchar(255) DEFAULT NULL,
  `description` text COMMENT 'http://schema.org/Event',
  `image` text COMMENT 'URL type, http://schema.org/Event',
  `place_id` int(11) unsigned NOT NULL COMMENT 'relation to place table',
  PRIMARY KEY (`id`),
  KEY `place_id` (`place_id`),
  CONSTRAINT `event_ibfk_1` FOREIGN KEY (`place_id`) REFERENCES `place` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

LOCK TABLES `event` WRITE;
/*!40000 ALTER TABLE `event` DISABLE KEYS */;

INSERT INTO `event` (`id`, `name`, `lang`, `type`, `description`, `image`, `place_id`)
VALUES
	(6,'',NULL,NULL,NULL,NULL,1);

/*!40000 ALTER TABLE `event` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table place
# ------------------------------------------------------------

DROP TABLE IF EXISTS `place`;

CREATE TABLE `place` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT 'http://schema.org/Place',
  `postal_code` char(5) DEFAULT NULL COMMENT 'http://schema.org/PostalAddress',
  `latitude` decimal(10,8) DEFAULT NULL COMMENT 'http://schema.org/GeoCoordinates\nhttp://stackoverflow.com/questions/12504208/what-mysql-data-type-should-be-used-for-latitude-longitude-with-8-decimal-places',
  `longitude` decimal(11,8) DEFAULT NULL COMMENT 'http://schema.org/GeoCoordinates\nhttp://stackoverflow.com/questions/12504208/what-mysql-data-type-should-be-used-for-latitude-longitude-with-8-decimal-places',
  `address_locality` varchar(58) DEFAULT NULL COMMENT 'http://schema.org/PostalAddress\nhttp://en.wikipedia.org/wiki/Llanfairpwllgwyngyll',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

LOCK TABLES `place` WRITE;
/*!40000 ALTER TABLE `place` DISABLE KEYS */;

INSERT INTO `place` (`id`, `postal_code`, `latitude`, `longitude`, `address_locality`)
VALUES
	(1,NULL,43.31539730,-999.99999999,NULL);

/*!40000 ALTER TABLE `place` ENABLE KEYS */;
UNLOCK TABLES;



/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
