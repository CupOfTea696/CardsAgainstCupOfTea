# ************************************************************
# Sequel Pro SQL dump
# Version 4500
#
# http://www.sequelpro.com/
# https://github.com/sequelpro/sequelpro
#
# Host: 127.0.0.1 (MySQL 5.7.10)
# Database: cah
# Generation Time: 2016-01-29 5:07:13 pm +0000
# ************************************************************


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


# Dump of table migrations
# ------------------------------------------------------------

DROP TABLE IF EXISTS `migrations`;

CREATE TABLE `migrations` (
  `migration` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;

INSERT INTO `migrations` (`migration`, `batch`)
VALUES
	('2014_10_12_000000_create_users_table',1),
	('2014_10_12_100000_create_password_resets_table',1),
	('2016_01_13_124420_create_rooms_table',1),
	('2016_01_14_100856_add_invalid_email_field_to_users_table',2),
	('2016_01_21_151556_add_fields_to_rooms_table',2);

/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table Passwords
# ------------------------------------------------------------

DROP TABLE IF EXISTS `Passwords`;

CREATE TABLE `Passwords` (
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NOT NULL,
  KEY `passwords_email_index` (`email`),
  KEY `passwords_token_index` (`token`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



# Dump of table Rooms
# ------------------------------------------------------------

DROP TABLE IF EXISTS `Rooms`;

CREATE TABLE `Rooms` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `private` tinyint(1) NOT NULL DEFAULT '0',
  `password` varchar(60) COLLATE utf8_unicode_ci DEFAULT NULL,
  `capacity` int(11) NOT NULL,
  `spec_capacity` int(11) NOT NULL,
  `permanent` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NOT NULL,
  `updated_at` timestamp NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `rooms_name_unique` (`name`),
  UNIQUE KEY `rooms_slug_unique` (`slug`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

LOCK TABLES `Rooms` WRITE;
/*!40000 ALTER TABLE `Rooms` DISABLE KEYS */;

INSERT INTO `Rooms` (`id`, `name`, `slug`, `private`, `password`, `capacity`, `spec_capacity`, `permanent`, `created_at`, `updated_at`)
VALUES
	(1,'CupOfTea696\'s Room','cupoftea696s-room',0,'',10,10,0,'2016-01-21 17:20:25','2016-01-21 17:20:25'),
	(2,'The Tea Room','the-tea-room',0,'',10,10,0,'2016-01-21 17:22:37','2016-01-21 17:22:37'),
	(3,'The Second Tea Room','the-second-tea-room',0,'',10,10,0,'2016-01-21 17:23:05','2016-01-21 17:23:05'),
	(4,'Another room','another-room',0,'',10,10,0,'2016-01-21 17:24:09','2016-01-21 17:24:09'),
	(5,'fsdhgkjsdg','fsdhgkjsdg',0,'',10,10,0,'2016-01-21 17:41:21','2016-01-21 17:41:21'),
	(6,'gsgfsgdfgerg','gsgfsgdfgerg',0,'',10,10,0,'2016-01-21 17:58:19','2016-01-21 17:58:19'),
	(7,'dfdf','dfdf',0,'',10,10,0,'2016-01-21 18:00:02','2016-01-21 18:00:02'),
	(8,'eefesfsdfd','eefesfsdfd',0,'',10,10,0,'2016-01-21 18:04:09','2016-01-21 18:04:09'),
	(9,'dfgesgsdfgdgfdfff','dfgesgsdfgdgfdfff',0,'',10,10,0,'2016-01-22 11:23:57','2016-01-22 11:23:57'),
	(10,'dfffefqerqer23423sa','dfffefqerqer23423sa',0,'',10,10,0,'2016-01-22 11:28:50','2016-01-22 11:28:50'),
	(11,'jqxhr','jqxhr',0,'',10,10,0,'2016-01-22 11:30:08','2016-01-22 11:30:08'),
	(12,'defeater','defeater',0,'',10,10,0,'2016-01-22 11:34:42','2016-01-22 11:34:42'),
	(13,'jerghejrgh','jerghejrgh',0,'',10,10,0,'2016-01-22 11:36:28','2016-01-22 11:36:28'),
	(14,'create','create',0,'',0,0,0,'2016-01-22 11:41:45','2016-01-22 11:41:45');

/*!40000 ALTER TABLE `Rooms` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table Users
# ------------------------------------------------------------

DROP TABLE IF EXISTS `Users`;

CREATE TABLE `Users` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(60) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` timestamp NOT NULL,
  `updated_at` timestamp NOT NULL,
  `email_invalid` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

LOCK TABLES `Users` WRITE;
/*!40000 ALTER TABLE `Users` DISABLE KEYS */;

INSERT INTO `Users` (`id`, `username`, `password`, `email`, `remember_token`, `created_at`, `updated_at`, `email_invalid`)
VALUES
	(1,'CupOfTea696','$2y$10$KM3d4Lwurxrct.tL7mKEBu.ZRaMdBIxNA7YSYf.vJEU8zwq1rg.tu','cupoftea696@gmail.com','EwSXByX2egYTS7iHlGVNeptu1nE9w7MEHQMpCjQDUf09DnhSrG48HRFBw2Yr','2016-01-13 13:47:00','2016-01-14 10:34:03',0),
	(2,'Bob','$2y$10$uYC1Bnt/ztvc5g5j80cd8O0xa2..xze.bmBT0JIypPWNI9XsVf1aO','bob@gmail.com',NULL,'2016-01-14 10:34:27','2016-01-14 10:34:27',0);

/*!40000 ALTER TABLE `Users` ENABLE KEYS */;
UNLOCK TABLES;



/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
