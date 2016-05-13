# ************************************************************
# Sequel Pro SQL dump
# Version 4499
#
# http://www.sequelpro.com/
# https://github.com/sequelpro/sequelpro
#
# Host: 127.0.0.1 (MySQL 5.5.44-0ubuntu0.14.04.1)
# Database: OpenVDMv2-PortOffice
# Generation Time: 2015-10-08 11:51:47 +0000
# ************************************************************


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


# Dump of table ovdmpo_CoreVars
# ------------------------------------------------------------

DROP TABLE IF EXISTS `ovdmpo_CoreVars`;

CREATE TABLE `ovdmpo_CoreVars` (
  `coreVarID` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` tinytext NOT NULL,
  `value` tinytext,
  PRIMARY KEY (`coreVarID`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;


# Dump of table ovdmpo_Users
# ------------------------------------------------------------

DROP TABLE IF EXISTS `ovdmpo_Users`;

CREATE TABLE `ovdmpo_Users` (
  `userID` tinyint(4) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(255) DEFAULT '',
  `password` varchar(255) DEFAULT '',
  `userRole` tinyint(4) unsigned NOT NULL DEFAULT '2',
  `lastLogin` datetime DEFAULT NULL,
  PRIMARY KEY (`userID`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

LOCK TABLES `ovdmpo_Users` WRITE;
/*!40000 ALTER TABLE `ovdmpo_Users` DISABLE KEYS */;

INSERT INTO `ovdmpo_Users` (`userID`, `username`, `password`, `userRole`, `lastLogin`)
VALUES
	(1,'admin','$2y$12$JviETOQPkNzqZxQpswLb1ONtTLxsqdzQJEoaWjlNzb0/.xfIOVM/C',1,'');

/*!40000 ALTER TABLE `ovdmpo_Users` ENABLE KEYS */;
UNLOCK TABLES;



/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
