-- MySQL dump 10.13  Distrib 5.7.22, for Linux (x86_64)
--
-- Host: localhost    Database: vineft
-- ------------------------------------------------------
-- Server version	5.7.22-0ubuntu0.16.04.1

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
-- Table structure for table `community`
--

DROP TABLE IF EXISTS `community`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `community` (
  `id` int(9) NOT NULL AUTO_INCREMENT,
  `name` varchar(128) NOT NULL,
  `cover` varchar(64) NOT NULL,
  `avatar` varchar(64) NOT NULL,
  `conditions` varchar(32) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `community`
--

LOCK TABLES `community` WRITE;
/*!40000 ALTER TABLE `community` DISABLE KEYS */;
/*!40000 ALTER TABLE `community` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `contacts`
--

DROP TABLE IF EXISTS `contacts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `contacts` (
  `id` int(9) NOT NULL,
  `array_friends` varchar(512) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `contacts`
--

LOCK TABLES `contacts` WRITE;
/*!40000 ALTER TABLE `contacts` DISABLE KEYS */;
INSERT INTO `contacts` VALUES (1,'48,49,17,8,27,50,47'),(3,'1'),(18,'17'),(20,''),(21,''),(22,''),(23,'8,17,22'),(24,''),(25,''),(26,'17,27,1,2,3,8,22'),(27,'2'),(28,''),(29,''),(30,''),(31,''),(32,''),(33,''),(34,''),(35,''),(36,''),(37,''),(38,''),(39,''),(40,''),(41,''),(42,''),(43,''),(44,'2'),(45,''),(46,'2,47,1,22'),(47,'8,17,2,1,3,22,27'),(48,'1'),(49,'1'),(50,'1');
/*!40000 ALTER TABLE `contacts` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `dialogs`
--

DROP TABLE IF EXISTS `dialogs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `dialogs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `dialog_id` int(9) NOT NULL,
  `client1` int(11) NOT NULL,
  `client2` int(11) NOT NULL,
  `type` varchar(32) NOT NULL,
  `user_delete` varchar(255) NOT NULL,
  `date` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=570 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `dialogs`
--

LOCK TABLES `dialogs` WRITE;
/*!40000 ALTER TABLE `dialogs` DISABLE KEYS */;
INSERT INTO `dialogs` VALUES (553,553,1,87,'public','','2018-06-15 10:08:49'),(554,554,1,115,'public','','2018-06-15 10:18:01'),(557,554,50,115,'public','','2018-06-15 10:18:01'),(558,554,47,115,'public','','2018-06-15 10:18:01'),(559,554,49,115,'public','','2018-06-15 10:18:01'),(560,553,46,87,'public','','2018-06-15 10:08:49'),(561,561,1,6,'public','','2018-06-15 10:08:16'),(562,562,1,8,'students','','2018-06-17 02:38:30'),(563,563,1,48,'students','','2018-06-15 10:04:47'),(564,564,1,116,'public','','2018-06-15 10:07:01'),(565,553,50,87,'public','','2018-06-15 10:08:49'),(566,566,1,47,'students','1 ','2018-06-17 02:33:09'),(567,567,1,50,'students','','2018-06-17 02:29:38'),(568,568,1,27,'students','27 ','2018-06-17 02:36:03'),(569,569,1,17,'students','','2018-06-17 03:08:45');
/*!40000 ALTER TABLE `dialogs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `invite`
--

DROP TABLE IF EXISTS `invite`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `invite` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `invited` int(11) NOT NULL,
  `user` int(11) NOT NULL,
  `public` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=154 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `invite`
--

LOCK TABLES `invite` WRITE;
/*!40000 ALTER TABLE `invite` DISABLE KEYS */;
INSERT INTO `invite` VALUES (119,49,1,93),(121,49,1,94),(123,49,1,95),(124,47,1,95),(126,48,1,96),(127,47,1,96),(134,22,46,102),(143,49,1,86),(144,48,1,86),(145,8,1,86),(146,3,1,86),(147,2,1,86),(152,27,1,116),(153,47,1,6);
/*!40000 ALTER TABLE `invite` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `messages`
--

DROP TABLE IF EXISTS `messages`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `messages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `dialog_id` int(11) NOT NULL,
  `user` int(11) NOT NULL,
  `message` varchar(512) NOT NULL,
  `type` varchar(55) NOT NULL,
  `date` datetime NOT NULL,
  `user_read` varchar(255) NOT NULL,
  `user_delete` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1556 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `messages`
--

LOCK TABLES `messages` WRITE;
/*!40000 ALTER TABLE `messages` DISABLE KEYS */;
INSERT INTO `messages` VALUES (1512,553,1,'Какое расписание?','text','2018-06-15 07:32:45','1 46 50 ','46 50 '),(1513,552,1,'Тут все программисты со всех курсов','text','2018-06-15 07:33:02','1 ',''),(1514,552,1,'4','sticker','2018-06-15 07:33:06','1 ',''),(1515,554,1,'15 защита','text','2018-06-15 07:51:22','1 47 ',''),(1516,554,1,'привет','text','2018-06-15 08:01:10','1 47 ',''),(1517,554,1,'привет','text','2018-06-15 08:01:33','1 47 ',''),(1518,554,1,'6','sticker','2018-06-15 08:01:57','1 47 ',''),(1519,554,1,'5','sticker','2018-06-15 08:02:04','1 47 ',''),(1520,554,1,'2','sticker','2018-06-15 08:02:32','1 47 ',''),(1521,553,1,'Здравствуйте','text','2018-06-15 08:03:22','1 46 50 ','50 '),(1522,553,46,'6','sticker','2018-06-15 08:03:49','46 1 50 ','50 '),(1523,553,46,'Привет','text','2018-06-15 08:04:01','46 1 50 ','50 '),(1524,553,1,'авп','text','2018-06-15 08:25:09','1 46 50 ','50 '),(1525,553,1,'1','sticker','2018-06-15 08:25:18','1 46 50 ','50 '),(1526,562,1,'4','sticker','2018-06-15 08:34:46','1 ',''),(1527,554,1,'Здравствуйте','text','2018-06-15 09:24:22','1 47 ',''),(1528,554,1,'Здарова','text','2018-06-15 09:48:56','1 47 ',''),(1529,563,1,'привет','text','2018-06-15 10:04:47','1 ',''),(1530,561,1,'Здравствуйте','text','2018-06-15 10:08:16','1 ',''),(1531,553,50,'Привет','text','2018-06-15 10:08:31','50 1 ',''),(1532,553,50,'6','sticker','2018-06-15 10:08:49','50 1 ',''),(1533,554,47,'Здарова','text','2018-06-15 10:18:01','47 1 ',''),(1534,566,1,'Куда ты пишешь епта','text','2018-06-15 10:21:01','1 47 ','1 '),(1535,566,1,'8','sticker','2018-06-15 10:21:13','1 47 ','1 '),(1536,567,1,'Ало','text','2018-06-15 10:24:41','1 ',''),(1537,567,1,'8','sticker','2018-06-15 10:24:49','1 ',''),(1538,566,47,'хватит блевать','text','2018-06-15 10:26:45','47 1 ','1 '),(1539,566,47,'Это я Фролкова','text','2018-06-15 10:26:48','47 1 ','1 '),(1540,566,47,'Короче Ванек','text','2018-06-15 10:26:50','47 1 ','1 '),(1541,566,47,'Четверка сойдет?','text','2018-06-15 10:26:53','47 1 ','1 '),(1542,566,1,'Да Пашею ты нахуй','text','2018-06-15 10:40:09','1 47 ','1 '),(1543,566,1,'9','sticker','2018-06-15 10:40:23','1 47 ','1 '),(1544,567,1,'2','text','2018-06-16 17:53:26','1 ',''),(1545,567,1,'Ygg','text','2018-06-17 02:29:25','1 ',''),(1546,567,1,'4','sticker','2018-06-17 02:29:38','1 ',''),(1547,566,1,'1','text','2018-06-17 02:32:56','1 ','1 '),(1548,566,1,'6','sticker','2018-06-17 02:33:09','1 ','1 '),(1549,562,1,'8','sticker','2018-06-17 02:38:19','1 ',''),(1550,562,1,'27','text','2018-06-17 02:38:30','1 ',''),(1551,569,1,'Rnxhc','text','2018-06-17 02:41:56','1 ',''),(1552,569,1,'7','sticker','2018-06-17 02:42:09','1 ',''),(1553,569,1,'Телеграмм гавно','text','2018-06-17 02:53:35','1 ',''),(1554,569,1,'3','sticker','2018-06-17 02:53:47','1 ',''),(1555,569,1,'4','sticker','2018-06-17 03:08:45','1 ','');
/*!40000 ALTER TABLE `messages` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `posts`
--

DROP TABLE IF EXISTS `posts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `posts` (
  `id` int(9) NOT NULL AUTO_INCREMENT,
  `id_group` int(9) NOT NULL,
  `text` varchar(1024) NOT NULL,
  `likes` int(9) NOT NULL,
  `date` date NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `posts`
--

LOCK TABLES `posts` WRITE;
/*!40000 ALTER TABLE `posts` DISABLE KEYS */;
/*!40000 ALTER TABLE `posts` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `public`
--

DROP TABLE IF EXISTS `public`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `public` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `avatar` varchar(64) NOT NULL,
  `type` varchar(32) NOT NULL,
  `param` varchar(32) NOT NULL,
  `number_user` int(11) NOT NULL,
  `admin` varchar(64) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=117 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `public`
--

LOCK TABLES `public` WRITE;
/*!40000 ALTER TABLE `public` DISABLE KEYS */;
INSERT INTO `public` VALUES (1,'Аналитики','1d92babb.jpg','2','1',0,''),(2,'Банкиры','59efe6ff.jpg','2','2',0,''),(3,'Бухгалтера','bdcc264e.jpg','2','3',0,''),(4,'Монтажники','9e51644c.jpg','2','4',0,''),(5,'Пожарники','83ee6e88.jpg','2','5',0,''),(6,'Программисты','ef3b64a2.jpg','2','6',1,''),(7,'Нефтяники','449ef744.jpg','2','7',0,''),(8,'Экологи','ea35ab8a.jpg','2','8',0,''),(70,'Выпускники','c0aae4f2.jpg','1','',0,'47'),(87,'ПКС-6','3efdbdfb.jpg','1','',3,'1'),(115,'Подготовка к диплому','8923b810.jpg','1','',4,'1'),(116,'Защита','','2','6',1,'1');
/*!40000 ALTER TABLE `public` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `specialties`
--

DROP TABLE IF EXISTS `specialties`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `specialties` (
  `id` int(9) NOT NULL,
  `name` varchar(256) NOT NULL,
  `abridged` varchar(56) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `specialties`
--

LOCK TABLES `specialties` WRITE;
/*!40000 ALTER TABLE `specialties` DISABLE KEYS */;
INSERT INTO `specialties` VALUES (1,'Аналитический контроль качества химических соединений','Ак'),(2,'Банковское дело','Бд'),(3,'Экономика и бухгалтерский учет','Б'),(4,'Монтаж и техническая эксплуатация промышленного оборудования','М'),(5,'Пожарная безопасность','Пб'),(6,'Программирование в компьютерных системах','Пкс'),(7,'Переработка нефти и газа','Пнг'),(8,'Рациональное использование природохозяйственных комплексов','Рипк');
/*!40000 ALTER TABLE `specialties` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `students`
--

DROP TABLE IF EXISTS `students`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `students` (
  `id` int(9) NOT NULL AUTO_INCREMENT,
  `vk` varchar(32) NOT NULL,
  `facebook` varchar(32) NOT NULL,
  `instagram` varchar(32) NOT NULL,
  `twitter` varchar(32) NOT NULL,
  `verification` enum('false','true') NOT NULL,
  `avatar` varchar(64) NOT NULL,
  `name` varchar(64) NOT NULL,
  `birthday` date NOT NULL,
  `city` varchar(64) NOT NULL,
  `stud_ticket` int(5) NOT NULL,
  `specialty` int(2) NOT NULL,
  `course` int(2) NOT NULL,
  `pol` varchar(64) NOT NULL,
  `about_me` varchar(512) NOT NULL,
  `date` date NOT NULL,
  `settings` varchar(128) NOT NULL,
  `last_activity` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=51 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `students`
--

LOCK TABLES `students` WRITE;
/*!40000 ALTER TABLE `students` DISABLE KEYS */;
INSERT INTO `students` VALUES (1,'260510958','25fgb','123466','Tyg','true','gf3ko863b.jpg','Ванёк Леонтьев','1998-08-15','Орск',5,6,1,'male','Увлекаюсь web-программированием','2018-06-01','0,0,0','2018-06-17 17:44:48'),(2,'26051095','','325','','false','456ffmj63b.jpg','Ваня авыпва','1998-10-20','Орск',685,8,4,'male','Да я тут','2018-04-24','1,1,1','0000-00-00 00:00:00'),(3,'160177616','','','','false','ac37d3b5.jpg','Константин Дудин','1998-08-16','Орск',1,6,4,'male','Если кормить дотера говно это будет каннибализм','2018-05-04','1,1,1','2018-06-09 20:53:53'),(8,'1177616','','','','true','fd327zf8.jpg','Костя Дудин','1997-12-03','Орск',777,4,4,'male','Занимаюсь киберспортом','2018-04-11','1,1,1','2018-06-04 18:42:00'),(17,'1','','','YouTube','true','a7bb1492.jpg','Павел Дуров','1998-09-19','Москва',436,5,3,'male','делаю телеграм','2018-05-15','0,1,0','0000-00-00 00:00:00'),(22,'26051','','1','YouTube','true','d7d23f05.jpg','Алексей Иванов','1998-12-09','Адамовка',325,5,3,'male','нету интересов','2018-05-05','1,1,0','2018-06-04 18:42:00'),(27,'6938512','100001847528272','','','true','e86819d5.jpg','Игорь Финк','1998-08-20','Орск',1222,6,4,'male','Программист','2018-05-07','1,1,0','2018-06-07 06:31:41'),(45,'2605109582','','','','true','4f265dba7.jpg','Артём Ягудин','1998-11-02','Орск',435,4,3,'male','Качаюсь','2018-05-09','1,1,0','2018-06-04 18:42:00'),(46,'74801319','','','','true','xdg356hba7.jpg','Алексей Захаров','1998-08-31','Орск',363,3,3,'male','один ноль один ютуб','2018-06-09','1,1,0','2018-06-15 08:25:18'),(47,'405402606','','','','true','fgh4465dba7.jpg','Денис Федорюк','1998-11-01','Орск',189,6,4,'male','Меня интересуют велосипеды','2018-06-10','1,1,0','2018-06-15 10:47:22'),(48,'154362877','','1488','','true','cd7d4e10.jpg','Андрей Иванов','1998-10-26','Орск',322,2,4,'male','занимаюсь киберспортом','2018-06-13','1,1,0','2018-06-13 22:55:56'),(49,'202542241','','egirlanov','','true','cc0968eb.jpg','Евгений  Горланов','1998-10-13','Санкт-Петербург',332,3,2,'male','программирую','2018-06-14','1,1,0','2018-06-14 02:57:36'),(50,'382447534','','','','true','5515g65dba7.jpg','Михаил Макаров','1998-06-09','Германия',32432,6,3,'male','фанат toyota rav4','2018-06-14','1,1,0','2018-06-15 10:24:41');
/*!40000 ALTER TABLE `students` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2018-06-17 19:55:26
