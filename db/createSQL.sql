CREATE DATABASE  IF NOT EXISTS `infos2` /*!40100 DEFAULT CHARACTER SET utf8 */;
USE `infos2`;
-- MySQL dump 10.13  Distrib 5.6.13, for Win32 (x86)
--
-- Host: mariadb55.websupport.sk    Database: infos2
-- ------------------------------------------------------
-- Server version	5.5.28a-MariaDB-log

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `announcements`
--

DROP TABLE IF EXISTS `announcements`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `announcements` (
  `id_announcement` int(11) NOT NULL AUTO_INCREMENT,
  `id_user` varchar(100) NOT NULL,
  `ann_created` datetime NOT NULL,
  `ann_updated` datetime DEFAULT NULL,
  `ann_title` varchar(40) NOT NULL,
  `ann_text` longtext NOT NULL,
  PRIMARY KEY (`id_announcement`),
  KEY `fk_announcements_users1_idx` (`id_user`),
  CONSTRAINT `fk_announcements_users1` FOREIGN KEY (`id_user`) REFERENCES `users` (`id_user`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `announcements`
--

LOCK TABLES `announcements` WRITE;
/*!40000 ALTER TABLE `announcements` DISABLE KEYS */;
INSERT INTO `announcements` VALUES (1,'115058088728776206860','2013-08-20 16:39:13','2013-08-21 19:59:57','Testicek :P','<p>Okej, takže testíček diakritiky. Kecám, kecám a robím čo najdlhší text. Bla, bla dosť nuda. Dufam ze ten logging uz pofrci pretoze ma uz z neho jebne -_-</p>');
/*!40000 ALTER TABLE `announcements` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `controllers`
--

DROP TABLE IF EXISTS `controllers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `controllers` (
  `controller` varchar(50) NOT NULL,
  `active` int(11) NOT NULL,
  PRIMARY KEY (`controller`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `controllers`
--

LOCK TABLES `controllers` WRITE;
/*!40000 ALTER TABLE `controllers` DISABLE KEYS */;
INSERT INTO `controllers` VALUES ('announcements',1),('authenticate',1),('default',1),('docs',1),('events',1),('newsletter',1),('rss',1),('suplo',1);
/*!40000 ALTER TABLE `controllers` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Temporary table structure for view `getCurrentTimetable`
--

DROP TABLE IF EXISTS `getCurrentTimetable`;
/*!50001 DROP VIEW IF EXISTS `getCurrentTimetable`*/;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
/*!50001 CREATE TABLE `getCurrentTimetable` (
  `id_timetable` tinyint NOT NULL,
  `lesson` tinyint NOT NULL,
  `starttime` tinyint NOT NULL,
  `endtime` tinyint NOT NULL,
  `label` tinyint NOT NULL
) ENGINE=MyISAM */;
SET character_set_client = @saved_cs_client;

--
-- Temporary table structure for view `getSuploRecord`
--

DROP TABLE IF EXISTS `getSuploRecord`;
/*!50001 DROP VIEW IF EXISTS `getSuploRecord`*/;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
/*!50001 CREATE TABLE `getSuploRecord` (
  `id_suplo` tinyint NOT NULL,
  `id_user` tinyint NOT NULL,
  `suplo_nick` tinyint NOT NULL,
  `suplo_date` tinyint NOT NULL,
  `suplo_hour` tinyint NOT NULL,
  `suplo_classes` tinyint NOT NULL,
  `suplo_note` tinyint NOT NULL,
  `suplo_classroom` tinyint NOT NULL,
  `suplo_subject` tinyint NOT NULL,
  `suplo_eventId` tinyint NOT NULL,
  `user_firstName` tinyint NOT NULL,
  `user_lastName` tinyint NOT NULL,
  `user_email` tinyint NOT NULL,
  `user_calendarSuplo` tinyint NOT NULL,
  `dateFriendly` tinyint NOT NULL,
  `dateRaw` tinyint NOT NULL
) ENGINE=MyISAM */;
SET character_set_client = @saved_cs_client;

--
-- Temporary table structure for view `getTimeRecord`
--

DROP TABLE IF EXISTS `getTimeRecord`;
/*!50001 DROP VIEW IF EXISTS `getTimeRecord`*/;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
/*!50001 CREATE TABLE `getTimeRecord` (
  `startHour` tinyint NOT NULL,
  `startMinute` tinyint NOT NULL,
  `startSecond` tinyint NOT NULL,
  `endHour` tinyint NOT NULL,
  `endMinute` tinyint NOT NULL,
  `endSecond` tinyint NOT NULL,
  `lesson` tinyint NOT NULL
) ENGINE=MyISAM */;
SET character_set_client = @saved_cs_client;

--
-- Table structure for table `likes`
--

DROP TABLE IF EXISTS `likes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `likes` (
  `id_like` int(11) NOT NULL AUTO_INCREMENT,
  `id_user` varchar(100) NOT NULL,
  `id_announcement` int(11) NOT NULL,
  `like_status` tinyint(1) NOT NULL,
  PRIMARY KEY (`id_like`),
  KEY `fk_likes_users_idx` (`id_user`),
  KEY `fk_likes_oznamy1_idx` (`id_announcement`),
  CONSTRAINT `fk_likes_oznamy1` FOREIGN KEY (`id_announcement`) REFERENCES `announcements` (`id_announcement`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_likes_users` FOREIGN KEY (`id_user`) REFERENCES `users` (`id_user`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `likes`
--

LOCK TABLES `likes` WRITE;
/*!40000 ALTER TABLE `likes` DISABLE KEYS */;
INSERT INTO `likes` VALUES (2,'115058088728776206860',1,1),(3,'101206256018282065189',1,1);
/*!40000 ALTER TABLE `likes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Temporary table structure for view `listAnnouncements`
--

DROP TABLE IF EXISTS `listAnnouncements`;
/*!50001 DROP VIEW IF EXISTS `listAnnouncements`*/;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
/*!50001 CREATE TABLE `listAnnouncements` (
  `id_announcement` tinyint NOT NULL,
  `id_user` tinyint NOT NULL,
  `ann_created` tinyint NOT NULL,
  `ann_updated` tinyint NOT NULL,
  `ann_title` tinyint NOT NULL,
  `ann_text` tinyint NOT NULL,
  `user_firstName` tinyint NOT NULL,
  `user_lastName` tinyint NOT NULL,
  `createdFriendly` tinyint NOT NULL,
  `updatedFriendly` tinyint NOT NULL,
  `createdRaw` tinyint NOT NULL,
  `updatedRaw` tinyint NOT NULL
) ENGINE=MyISAM */;
SET character_set_client = @saved_cs_client;

--
-- Temporary table structure for view `listLikes`
--

DROP TABLE IF EXISTS `listLikes`;
/*!50001 DROP VIEW IF EXISTS `listLikes`*/;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
/*!50001 CREATE TABLE `listLikes` (
  `id_like` tinyint NOT NULL,
  `id_user` tinyint NOT NULL,
  `id_announcement` tinyint NOT NULL,
  `like_status` tinyint NOT NULL,
  `userFullName` tinyint NOT NULL
) ENGINE=MyISAM */;
SET character_set_client = @saved_cs_client;

--
-- Table structure for table `logs`
--

DROP TABLE IF EXISTS `logs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `logs` (
  `id_log` int(11) NOT NULL AUTO_INCREMENT,
  `log_time` datetime NOT NULL,
  `log_type` enum('ERR','INF','WAR') NOT NULL,
  `log_message` text NOT NULL,
  PRIMARY KEY (`id_log`)
) ENGINE=InnoDB AUTO_INCREMENT=69 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `logs`
--

LOCK TABLES `logs` WRITE;
/*!40000 ALTER TABLE `logs` DISABLE KEYS */;
INSERT INTO `logs` VALUES (37,'2013-08-21 19:59:57','INF','[Announcement::save] - Upravený oznam \"Testicek :P\"[1] používateľom Jakub Dubec'),(38,'2013-08-21 20:01:53','INF','[Announcement::save] - Vytvorený oznam \"Chudacik\"[4] používateľom Jakub Dubec'),(39,'2013-08-21 20:03:36','INF','[Announcement::remove] - Odstránený oznam \"Chudacik\"[4] používateľom Jakub Dubec'),(40,'2013-08-22 07:30:37','INF','[AuthenticateController::login] - User dubec@gymmt.sk was logged in'),(41,'2013-08-22 15:08:15','INF','[AuthenticateController::login] - User dubec@gymmt.sk was logged in'),(42,'2013-08-22 15:59:12','INF','[AuthenticateController::login] - User dubec@gymmt.sk was logged in'),(43,'2013-08-26 18:10:11','INF','[AuthenticateController::login] - User dubec@gymmt.sk was logged in'),(44,'2013-08-27 11:25:16','INF','[AuthenticateController::login] - User dubec@gymmt.sk was logged in'),(45,'2013-09-01 11:25:02','INF','[AuthenticateController::login] - User dubec@gymmt.sk was logged in'),(46,'2013-09-06 14:03:26','INF','[AuthenticateController::login] - User dubec@gymmt.sk was logged in'),(47,'2013-09-06 19:59:33','INF','[AuthenticateController::login] - User dubec@gymmt.sk was logged in'),(48,'2013-09-08 11:22:56','INF','[AuthenticateController::login] - User dubec@gymmt.sk was logged in'),(49,'2013-09-08 16:55:54','INF','[AuthenticateController::login] - User dubec@gymmt.sk was logged in'),(50,'2013-09-09 10:04:15','INF','[AuthenticateController::login] - User dubec@gymmt.sk was logged in'),(51,'2013-09-09 11:42:32','INF','[AuthenticateController::login] - User dubec@gymmt.sk was logged in'),(52,'2013-09-10 22:20:05','INF','[AuthenticateController::login] - User dubec@gymmt.sk was logged in'),(53,'2013-09-11 14:09:06','INF','[AuthenticateController::login] - User dubec@gymmt.sk was logged in'),(54,'2013-09-11 19:00:50','INF','[AuthenticateController::login] - User dubec@gymmt.sk was logged in'),(55,'2013-09-12 15:43:39','INF','[AuthenticateController::login] - User dubec@gymmt.sk was logged in'),(56,'2013-09-13 08:15:17','INF','[AuthenticateController::login] - User dubec@gymmt.sk was logged in'),(57,'2013-09-13 08:15:24','INF','[AuthenticateController::login] - User dubec@gymmt.sk was logged in'),(58,'2013-09-13 14:07:18','INF','[AuthenticateController::login] - User dubec@gymmt.sk was logged in'),(59,'2013-09-13 14:14:47','INF','[AuthenticateController::login] - User dubec@gymmt.sk was logged in'),(60,'2013-09-14 08:31:02','INF','[AuthenticateController::login] - User dubec@gymmt.sk was logged in'),(61,'2013-09-14 18:43:57','INF','[AuthenticateController::login] - User dubec@gymmt.sk was logged in'),(62,'2013-09-14 22:19:30','INF','[AuthenticateController::login] - User dubec@gymmt.sk was logged in'),(63,'2013-09-15 09:07:09','INF','[AuthenticateController::login] - User dubec@gymmt.sk was logged in'),(64,'2013-09-15 22:02:07','INF','[AuthenticateController::login] - User dubec@gymmt.sk was logged in'),(65,'2013-09-15 22:19:35','INF','[AuthenticateController::login] - User dubec@gymmt.sk was logged in'),(66,'2013-09-15 22:23:36','INF','[AuthenticateController::login] - User silvia.bodova2402@gmail.com was logged in'),(67,'2013-09-16 07:02:02','INF','[AuthenticateController::login] - User dubec@gymmt.sk was logged in'),(68,'2013-09-16 09:57:01','INF','[AuthenticateController::login] - User dubec@gymmt.sk was logged in');
/*!40000 ALTER TABLE `logs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `newsletter`
--

DROP TABLE IF EXISTS `newsletter`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `newsletter` (
  `id_user` varchar(100) NOT NULL,
  `newsletter_email` varchar(60) NOT NULL,
  `newsletter_oznamy` tinyint(1) NOT NULL,
  `newsletter_suplovanie` enum('ALL','MAIN') NOT NULL,
  `newsletter_terminovnik` tinyint(1) NOT NULL,
  `newsletter_visible` tinyint(1) DEFAULT '1',
  PRIMARY KEY (`id_user`),
  CONSTRAINT `fk_newsletter_users1` FOREIGN KEY (`id_user`) REFERENCES `users` (`id_user`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `newsletter`
--

LOCK TABLES `newsletter` WRITE;
/*!40000 ALTER TABLE `newsletter` DISABLE KEYS */;
/*!40000 ALTER TABLE `newsletter` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Temporary table structure for view `selectAnnouncement`
--

DROP TABLE IF EXISTS `selectAnnouncement`;
/*!50001 DROP VIEW IF EXISTS `selectAnnouncement`*/;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
/*!50001 CREATE TABLE `selectAnnouncement` (
  `id_announcement` tinyint NOT NULL,
  `id_user` tinyint NOT NULL,
  `ann_created` tinyint NOT NULL,
  `ann_updated` tinyint NOT NULL,
  `ann_title` tinyint NOT NULL,
  `ann_text` tinyint NOT NULL,
  `user_firstName` tinyint NOT NULL,
  `user_lastName` tinyint NOT NULL,
  `createdFriendly` tinyint NOT NULL,
  `updatedFriendly` tinyint NOT NULL,
  `createdRaw` tinyint NOT NULL,
  `updatedRaw` tinyint NOT NULL
) ENGINE=MyISAM */;
SET character_set_client = @saved_cs_client;

--
-- Table structure for table `settings`
--

DROP TABLE IF EXISTS `settings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `settings` (
  `key` varchar(50) NOT NULL,
  `value` varchar(70) NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `settings`
--

LOCK TABLES `settings` WRITE;
/*!40000 ALTER TABLE `settings` DISABLE KEYS */;
INSERT INTO `settings` VALUES ('adminEmailAddress','infos@gymmt.sk'),('googleApplicationName','GvptInfos'),('googleClientId','180622801359.apps.googleusercontent.com'),('googleClientSecret','vgf5wAVL8v8BE9qYoDYyfkS8'),('sitename','GVPT Infos'),('siteurl','http://infos2.jakubdubec.me'),('view','default');
/*!40000 ALTER TABLE `settings` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `suplo`
--

DROP TABLE IF EXISTS `suplo`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `suplo` (
  `id_suplo` int(11) NOT NULL AUTO_INCREMENT,
  `id_user` varchar(100) NOT NULL,
  `suplo_nick` varchar(5) NOT NULL,
  `suplo_date` date NOT NULL,
  `suplo_hour` int(11) NOT NULL,
  `suplo_classes` text NOT NULL,
  `suplo_note` text,
  `suplo_classroom` varchar(20) NOT NULL,
  `suplo_subject` varchar(20) NOT NULL,
  `suplo_eventId` varchar(60) DEFAULT NULL,
  PRIMARY KEY (`id_suplo`),
  KEY `fk_suplo_users2_idx` (`id_user`),
  KEY `index3` (`suplo_date`),
  CONSTRAINT `fk_suplo_users2` FOREIGN KEY (`id_user`) REFERENCES `users` (`id_user`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=35 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `suplo`
--

LOCK TABLES `suplo` WRITE;
/*!40000 ALTER TABLE `suplo` DISABLE KEYS */;
INSERT INTO `suplo` VALUES (28,'115058088728776206860','BOD','2013-09-12',1,'3.F','spojí','C1','Bi/p','5a3b0htbi88i8aep2r3a9nugb0'),(29,'115058088728776206860','BOD','2013-09-12',2,'3.F','spojí','C1','Bi/p','a0etiki6sdsivjiv5bvdmijp8s'),(30,'111011317571129810357','BOD','2013-09-12',3,'2.D','','B3','BIO','boef6ae7nk5lfu632lti3c18tc'),(31,'111011317571129810357','BOD','2013-09-12',4,'4.G','spojí','B3','Bi/p','pa059niac7tcgvvth9laksap74'),(32,'115058088728776206860','DUD','2013-09-13',2,'3.F','spojí','C1','Bi/p','b073sgp0j5ej5d2tpn8kh3jemc'),(33,'101206256018282065189','DUD','2013-09-13',3,'2.D','','B3','BIO','usg1cvdbkf5bc45g5978lqi3js'),(34,'101206256018282065189','DUD','2013-09-13',4,'4.G','spojí','B3','Bi/p','8ki42ppt5gq4hjqpurlg7rkato');
/*!40000 ALTER TABLE `suplo` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `timetable`
--

DROP TABLE IF EXISTS `timetable`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `timetable` (
  `id_timetable` int(11) NOT NULL AUTO_INCREMENT,
  `lesson` int(11) DEFAULT NULL,
  `starttime` time NOT NULL,
  `endtime` time NOT NULL,
  `label` varchar(45) NOT NULL,
  PRIMARY KEY (`id_timetable`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `timetable`
--

LOCK TABLES `timetable` WRITE;
/*!40000 ALTER TABLE `timetable` DISABLE KEYS */;
INSERT INTO `timetable` VALUES (1,0,'07:05:00','07:49:59','0. hodina'),(2,NULL,'07:50:00','07:59:59','10min. prestávka'),(3,1,'08:00:00','08:44:59','1. hodina'),(4,NULL,'08:45:00','08:54:59','10min. prestávka'),(5,2,'08:55:00','09:39:59','2. hodina'),(6,NULL,'09:40:00','09:49:59','10min. prestávka'),(7,3,'09:50:00','10:34:59','3. hodina'),(8,NULL,'10:35:00','10:54:59','20min. prestávka'),(9,4,'10:55:00','11:39:59','4. hodina'),(10,NULL,'11:40:00','11:49:59','10min. prestávka'),(11,5,'11:50:00','12:34:59','5. hodina'),(12,NULL,'12:35:00','12:44:59','10min. prestávka'),(13,6,'12:45:00','13:29:59','6. hodina'),(14,NULL,'13:30:00','13:39:59','10min. prestávka'),(15,7,'13:40:00','14:24:59','7. hodina'),(16,NULL,'14:25:00','14:29:59','5min. prestávka'),(17,8,'14:30:00','15:14:59','8. hodina'),(18,NULL,'15:15:00','07:04:59','Neprebieha výuka');
/*!40000 ALTER TABLE `timetable` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `id_user` varchar(100) NOT NULL DEFAULT '',
  `user_nick` varchar(5) NOT NULL,
  `user_email` varchar(60) NOT NULL,
  `user_firstName` varchar(45) DEFAULT NULL,
  `user_lastName` varchar(45) DEFAULT NULL,
  `user_calendarSuplo` varchar(100) DEFAULT NULL,
  `user_admin` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`id_user`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES ('101206256018282065189','BOD','silvia.bodova2402@gmail.com','Silvia','Boďová','gymmt.sk_t19esf07fghfe09hjjn7537oqc@group.calendar.google.com',0),('111011317571129810357','DUD','tdudik@gmail.com','Tomáš','Dudík','gymmt.sk_jjq2p22qi78ahqp03rg273l09s@group.calendar.google.com',1),('115058088728776206860','DUB','dubec@gymmt.sk','Jakub','Dubec','gymmt.sk_6f0mu6kf29puc2aas0mbem25ec@group.calendar.google.com',1);
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping routines for database 'infos2'
--
/*!50003 DROP PROCEDURE IF EXISTS `insertLog` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,STRICT_ALL_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ALLOW_INVALID_DATES,ERROR_FOR_DIVISION_BY_ZERO,TRADITIONAL,NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`infos`@`%` PROCEDURE `insertLog`(IN logType ENUM('INF', 'ERR', 'WAR'), IN logMessage TEXT)
begin
		INSERT INTO logs (log_time, log_type, log_message) VALUES (NOW(), logType, logMessage);
	END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;

--
-- Final view structure for view `getCurrentTimetable`
--

/*!50001 DROP TABLE IF EXISTS `getCurrentTimetable`*/;
/*!50001 DROP VIEW IF EXISTS `getCurrentTimetable`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8 */;
/*!50001 SET character_set_results     = utf8 */;
/*!50001 SET collation_connection      = utf8_general_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`infos`@`%` SQL SECURITY DEFINER */
/*!50001 VIEW `getCurrentTimetable` AS select `timetable`.`id_timetable` AS `id_timetable`,`timetable`.`lesson` AS `lesson`,`timetable`.`starttime` AS `starttime`,`timetable`.`endtime` AS `endtime`,`timetable`.`label` AS `label` from `timetable` where (cast(now() as time) between `timetable`.`starttime` and `timetable`.`endtime`) */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `getSuploRecord`
--

/*!50001 DROP TABLE IF EXISTS `getSuploRecord`*/;
/*!50001 DROP VIEW IF EXISTS `getSuploRecord`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8 */;
/*!50001 SET character_set_results     = utf8 */;
/*!50001 SET collation_connection      = utf8_general_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`infos`@`%` SQL SECURITY DEFINER */
/*!50001 VIEW `getSuploRecord` AS select `suplo`.`id_suplo` AS `id_suplo`,`suplo`.`id_user` AS `id_user`,`suplo`.`suplo_nick` AS `suplo_nick`,`suplo`.`suplo_date` AS `suplo_date`,`suplo`.`suplo_hour` AS `suplo_hour`,`suplo`.`suplo_classes` AS `suplo_classes`,`suplo`.`suplo_note` AS `suplo_note`,`suplo`.`suplo_classroom` AS `suplo_classroom`,`suplo`.`suplo_subject` AS `suplo_subject`,`suplo`.`suplo_eventId` AS `suplo_eventId`,`users`.`user_firstName` AS `user_firstName`,`users`.`user_lastName` AS `user_lastName`,`users`.`user_email` AS `user_email`,`users`.`user_calendarSuplo` AS `user_calendarSuplo`,date_format(`suplo`.`suplo_date`,'%d. %m. %Y') AS `dateFriendly`,date_format(`suplo`.`suplo_date`,'%Y-%m-%d') AS `dateRaw` from (`suplo` left join `users` on((`users`.`id_user` = `suplo`.`id_user`))) */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `getTimeRecord`
--

/*!50001 DROP TABLE IF EXISTS `getTimeRecord`*/;
/*!50001 DROP VIEW IF EXISTS `getTimeRecord`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8 */;
/*!50001 SET character_set_results     = utf8 */;
/*!50001 SET collation_connection      = utf8_general_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`infos`@`%` SQL SECURITY DEFINER */
/*!50001 VIEW `getTimeRecord` AS select extract(hour from `timetable`.`starttime`) AS `startHour`,extract(minute from `timetable`.`starttime`) AS `startMinute`,extract(second from `timetable`.`starttime`) AS `startSecond`,extract(hour from `timetable`.`endtime`) AS `endHour`,extract(minute from `timetable`.`endtime`) AS `endMinute`,extract(second from `timetable`.`endtime`) AS `endSecond`,`timetable`.`lesson` AS `lesson` from `timetable` */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `listAnnouncements`
--

/*!50001 DROP TABLE IF EXISTS `listAnnouncements`*/;
/*!50001 DROP VIEW IF EXISTS `listAnnouncements`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8 */;
/*!50001 SET character_set_results     = utf8 */;
/*!50001 SET collation_connection      = utf8_general_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`infos`@`%` SQL SECURITY DEFINER */
/*!50001 VIEW `listAnnouncements` AS select `announcements`.`id_announcement` AS `id_announcement`,`announcements`.`id_user` AS `id_user`,`announcements`.`ann_created` AS `ann_created`,`announcements`.`ann_updated` AS `ann_updated`,`announcements`.`ann_title` AS `ann_title`,`announcements`.`ann_text` AS `ann_text`,`users`.`user_firstName` AS `user_firstName`,`users`.`user_lastName` AS `user_lastName`,date_format(`announcements`.`ann_created`,'%d. %m. %Y o %H:%i') AS `createdFriendly`,date_format(`announcements`.`ann_updated`,'%d. %m. %Y o %H:%i') AS `updatedFriendly`,date_format(`announcements`.`ann_created`,'%Y-%m-%d') AS `createdRaw`,date_format(`announcements`.`ann_updated`,'%Y-%m-%d') AS `updatedRaw` from (`announcements` left join `users` on((`users`.`id_user` = `announcements`.`id_user`))) order by `announcements`.`ann_created` desc */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `listLikes`
--

/*!50001 DROP TABLE IF EXISTS `listLikes`*/;
/*!50001 DROP VIEW IF EXISTS `listLikes`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8 */;
/*!50001 SET character_set_results     = utf8 */;
/*!50001 SET collation_connection      = utf8_general_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`infos`@`%` SQL SECURITY DEFINER */
/*!50001 VIEW `listLikes` AS select `likes`.`id_like` AS `id_like`,`likes`.`id_user` AS `id_user`,`likes`.`id_announcement` AS `id_announcement`,`likes`.`like_status` AS `like_status`,concat(`users`.`user_firstName`,' ',`users`.`user_lastName`) AS `userFullName` from (`likes` left join `users` on((`users`.`id_user` = `likes`.`id_user`))) */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `selectAnnouncement`
--

/*!50001 DROP TABLE IF EXISTS `selectAnnouncement`*/;
/*!50001 DROP VIEW IF EXISTS `selectAnnouncement`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8 */;
/*!50001 SET character_set_results     = utf8 */;
/*!50001 SET collation_connection      = utf8_general_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`infos`@`%` SQL SECURITY DEFINER */
/*!50001 VIEW `selectAnnouncement` AS select `announcements`.`id_announcement` AS `id_announcement`,`announcements`.`id_user` AS `id_user`,`announcements`.`ann_created` AS `ann_created`,`announcements`.`ann_updated` AS `ann_updated`,`announcements`.`ann_title` AS `ann_title`,`announcements`.`ann_text` AS `ann_text`,`users`.`user_firstName` AS `user_firstName`,`users`.`user_lastName` AS `user_lastName`,date_format(`announcements`.`ann_created`,'%d. %m. %Y o %H:%i') AS `createdFriendly`,date_format(`announcements`.`ann_updated`,'%d. %m. %Y o %H:%i') AS `updatedFriendly`,date_format(`announcements`.`ann_created`,'%Y-%m-%d') AS `createdRaw`,date_format(`announcements`.`ann_updated`,'%Y-%m-%d') AS `updatedRaw` from (`announcements` left join `users` on((`users`.`id_user` = `announcements`.`id_user`))) */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2013-09-16 10:25:02
