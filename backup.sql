-- MySQL dump 10.13  Distrib 8.0.41, for Linux (x86_64)
--
-- Host: localhost    Database: queue_manager_db
-- ------------------------------------------------------
-- Server version	8.0.41-0ubuntu0.24.04.1

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `companies`
--

DROP TABLE IF EXISTS `companies`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `companies` (
  `id` int NOT NULL AUTO_INCREMENT,
  `username` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `avatar` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`),
  UNIQUE KEY `name` (`username`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `username_2` (`username`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `companies`
--

LOCK TABLES `companies` WRITE;
/*!40000 ALTER TABLE `companies` DISABLE KEYS */;
INSERT INTO `companies` VALUES (1,'CompanyNamePlaceholder','test@test.com','$2y$10$gqLMZQ/udUa9HUzQHZmZf.1sObjhLHlj4aOMWjfrsxbEoXzRaZJkm','2025-04-18 22:31:44','avatar_68054103e1c30.png'),(2,'test_company2','test2@test.com','$2y$10$uwVplBgT/H7MT3Vzy2IKBuKtUOgprlDs9Bpb5SkuRUiB0sN6vgJAS','2025-04-20 15:17:48',NULL),(4,'test3','test3@test.com','$2y$10$cwpqo0OgctQzpNp6GFKNwOnHJPR/2vQ3syVAoJre1vP1c4AF0npNO','2025-04-20 18:01:28',NULL);
/*!40000 ALTER TABLE `companies` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `queue_places`
--

DROP TABLE IF EXISTS `queue_places`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `queue_places` (
  `id` int NOT NULL AUTO_INCREMENT,
  `queue_id` int NOT NULL,
  `position` int NOT NULL,
  `occupied` tinyint(1) DEFAULT '0',
  `user_id` int DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `queue_id` (`queue_id`,`position`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `queue_places_ibfk_1` FOREIGN KEY (`queue_id`) REFERENCES `queues` (`id`) ON DELETE CASCADE,
  CONSTRAINT `queue_places_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=10275 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `queue_places`
--

LOCK TABLES `queue_places` WRITE;
/*!40000 ALTER TABLE `queue_places` DISABLE KEYS */;
INSERT INTO `queue_places` VALUES (106,9,1,0,NULL,'2025-04-19 21:28:33'),(107,9,2,0,NULL,'2025-04-19 21:28:33'),(108,9,3,0,NULL,'2025-04-19 21:28:33'),(109,9,4,0,NULL,'2025-04-19 21:28:33'),(110,9,5,0,NULL,'2025-04-19 21:28:33'),(111,9,6,0,NULL,'2025-04-19 21:28:33'),(112,9,7,0,NULL,'2025-04-19 21:28:33'),(126,10,1,1,1,'2025-04-19 21:29:44'),(127,10,2,0,NULL,'2025-04-19 21:29:44'),(10231,14,1,1,1,'2025-04-20 15:43:23'),(10232,14,2,0,NULL,'2025-04-20 15:43:23'),(10233,14,3,0,NULL,'2025-04-20 15:43:23'),(10234,14,4,0,NULL,'2025-04-20 15:43:23'),(10235,14,5,0,NULL,'2025-04-20 15:43:23');
/*!40000 ALTER TABLE `queue_places` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `queues`
--

DROP TABLE IF EXISTS `queues`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `queues` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `company_id` int NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `company_id` (`company_id`),
  CONSTRAINT `queues_ibfk_1` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `queues`
--

LOCK TABLES `queues` WRITE;
/*!40000 ALTER TABLE `queues` DISABLE KEYS */;
INSERT INTO `queues` VALUES (9,'test_queue2',1,'2025-04-19 21:28:33'),(10,'test_queue3',1,'2025-04-19 21:29:44'),(14,'new queue',2,'2025-04-20 15:43:23');
/*!40000 ALTER TABLE `queues` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user_queue`
--

DROP TABLE IF EXISTS `user_queue`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `user_queue` (
  `user_id` int NOT NULL,
  `queue_id` int NOT NULL,
  `joined_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`user_id`,`queue_id`),
  KEY `queue_id` (`queue_id`),
  CONSTRAINT `user_queue_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `user_queue_ibfk_2` FOREIGN KEY (`queue_id`) REFERENCES `queues` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user_queue`
--

LOCK TABLES `user_queue` WRITE;
/*!40000 ALTER TABLE `user_queue` DISABLE KEYS */;
INSERT INTO `user_queue` VALUES (1,10,'2025-04-20 02:04:54'),(1,14,'2025-04-20 18:50:00');
/*!40000 ALTER TABLE `user_queue` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `id` int NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `avatar` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'UserNamePlaceholder','test@test.com','$2y$10$UJIyFvnRe7z52G0F6yG5b.iACLP7lLtA50m.hSWFaEO5Brfcx/tc.','2025-04-18 16:21:06','avatar_6805402f66b7e.png'),(4,'TEST2','test2@test.com','$2y$10$lyct9QRYY8KhEaXSQE1Kt.Re2eJmSDsMYInPxbgpDqQ1OCdp5OsxS','2025-04-18 16:34:50',NULL),(5,'','','$2y$10$io2ya/1bHGceZkhMg0iQaOwPBDH86Xek4RTWo27u5FdEri85/6aUe','2025-04-18 17:10:52',NULL),(13,'test3','test3@test.com','$2y$10$7ti3TtBicwXZP/FWtyEcn.kSdJ1C9pybIdQW69QNUKP42K582mKme','2025-04-20 18:00:12',NULL);
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2025-04-20 21:17:23
