-- MariaDB dump 10.19  Distrib 10.4.32-MariaDB, for Win64 (AMD64)
--
-- Host: 127.0.0.1    Database: esras
-- ------------------------------------------------------
-- Server version	10.4.32-MariaDB

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `course_enrollments`
--

DROP TABLE IF EXISTS `course_enrollments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `course_enrollments` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `student_id` int(10) unsigned NOT NULL,
  `course_id` int(10) unsigned NOT NULL,
  `academic_year` year(4) DEFAULT NULL,
  `semester` tinyint(3) NOT NULL,
  `status` enum('active','completed','withdrawn','inactive') NOT NULL DEFAULT 'active',
  PRIMARY KEY (`id`),
  KEY `student_id` (`student_id`),
  KEY `course_id` (`course_id`),
  CONSTRAINT `course_enrollments_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`),
  CONSTRAINT `course_enrollments_ibfk_2` FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `course_enrollments`
--

/*!40000 ALTER TABLE `course_enrollments` DISABLE KEYS */;
INSERT INTO `course_enrollments` (`id`, `student_id`, `course_id`, `academic_year`, `semester`, `status`) VALUES (2,2,1,NULL,0,'active'),(3,3,1,NULL,0,'active'),(4,4,1,NULL,0,'active'),(5,5,1,NULL,0,'active'),(6,6,1,NULL,0,'active'),(7,7,1,NULL,0,'active'),(8,8,1,NULL,0,'active'),(9,9,1,NULL,0,'active'),(10,10,1,NULL,0,'active');
/*!40000 ALTER TABLE `course_enrollments` ENABLE KEYS */;

--
-- Table structure for table `course_professors`
--

DROP TABLE IF EXISTS `course_professors`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `course_professors` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `course_id` int(10) unsigned NOT NULL,
  `employee_id` int(10) unsigned NOT NULL,
  `academic_year` varchar(9) NOT NULL,
  `semester` tinyint(3) NOT NULL,
  `status` enum('active','inactive') DEFAULT 'active',
  PRIMARY KEY (`id`),
  KEY `course_id` (`course_id`),
  KEY `employee_id` (`employee_id`),
  CONSTRAINT `course_professors_ibfk_1` FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`),
  CONSTRAINT `course_professors_ibfk_2` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `course_professors`
--

/*!40000 ALTER TABLE `course_professors` DISABLE KEYS */;
/*!40000 ALTER TABLE `course_professors` ENABLE KEYS */;

--
-- Table structure for table `courses`
--

DROP TABLE IF EXISTS `courses`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `courses` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `code` varchar(10) NOT NULL,
  `name` varchar(255) NOT NULL,
  `ects` decimal(3,1) NOT NULL,
  `academic_level` enum('Bachelor','Master','Doctorate') DEFAULT NULL,
  `faculty_id` int(10) unsigned DEFAULT NULL,
  `department_id` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `department_id` (`department_id`),
  KEY `faculty_id` (`faculty_id`),
  CONSTRAINT `courses_ibfk_1` FOREIGN KEY (`department_id`) REFERENCES `departments` (`id`),
  CONSTRAINT `courses_ibfk_2` FOREIGN KEY (`faculty_id`) REFERENCES `faculty` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `courses`
--

/*!40000 ALTER TABLE `courses` DISABLE KEYS */;
INSERT INTO `courses` (`id`, `code`, `name`, `ects`, `academic_level`, `faculty_id`, `department_id`) VALUES (1,'IT 2001','Web Programming',8.0,'Bachelor',1,1);
/*!40000 ALTER TABLE `courses` ENABLE KEYS */;

--
-- Table structure for table `departments`
--

DROP TABLE IF EXISTS `departments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `departments` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `faculty_id` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `faculty_id` (`faculty_id`),
  CONSTRAINT `departments_ibfk_1` FOREIGN KEY (`faculty_id`) REFERENCES `faculty` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `departments`
--

/*!40000 ALTER TABLE `departments` DISABLE KEYS */;
INSERT INTO `departments` (`id`, `name`, `faculty_id`) VALUES (1,'Information Technology',1),(2,'Electircal and Electronics Engineering',1),(3,'Genetics and Bioengineering',1),(4,'Arhitecture',1),(5,'Civil Engineering',1),(6,'Dentistry',1),(7,'Management',2),(8,'International Relations and European Studies',2),(9,'Economics and Finance',2),(10,'Graphic Design and Multimedia',3),(11,'Digital Communications and Public Relations',3),(12,'Film and Video Production',3),(13,'English Language and Literature',3);
/*!40000 ALTER TABLE `departments` ENABLE KEYS */;

--
-- Table structure for table `employees`
--

DROP TABLE IF EXISTS `employees`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `employees` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `first_name` varchar(50) NOT NULL,
  `last_name` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','professor','assistant') NOT NULL,
  `status` enum('active','inactive') DEFAULT NULL,
  `faculty_id` int(10) unsigned NOT NULL,
  `department_id` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `faculty_id` (`faculty_id`),
  KEY `department_id` (`department_id`),
  CONSTRAINT `employees_ibfk_1` FOREIGN KEY (`faculty_id`) REFERENCES `faculty` (`id`),
  CONSTRAINT `employees_ibfk_2` FOREIGN KEY (`department_id`) REFERENCES `departments` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `employees`
--

/*!40000 ALTER TABLE `employees` DISABLE KEYS */;
INSERT INTO `employees` (`id`, `first_name`, `last_name`, `email`, `password`, `role`, `status`, `faculty_id`, `department_id`) VALUES (1,'Haris','Skeledzija','haris.skeledzija@stu.ibu.edu.ba','$2y$10$L2kGTz2hcIFzG71UzlvzDucUXX762DTZJnvFfubCjMhSTdAb3sgHu','admin',NULL,1,NULL),(4,'Web Programming','Testing','testing@gmail.com','$2y$10$hzewnNPPvZJX4PWyQaJBMOrBhCI9ocuFMwPDjIK2eNnd9EdsZtOc6','admin',NULL,1,1);
/*!40000 ALTER TABLE `employees` ENABLE KEYS */;

--
-- Table structure for table `exam_rooms`
--

DROP TABLE IF EXISTS `exam_rooms`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `exam_rooms` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `exam_id` int(10) unsigned NOT NULL,
  `room_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `exam_id` (`exam_id`),
  KEY `room_id` (`room_id`),
  CONSTRAINT `exam_rooms_ibfk_1` FOREIGN KEY (`exam_id`) REFERENCES `exams` (`id`),
  CONSTRAINT `exam_rooms_ibfk_2` FOREIGN KEY (`room_id`) REFERENCES `rooms` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `exam_rooms`
--

/*!40000 ALTER TABLE `exam_rooms` DISABLE KEYS */;
/*!40000 ALTER TABLE `exam_rooms` ENABLE KEYS */;

--
-- Table structure for table `exams`
--

DROP TABLE IF EXISTS `exams`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `exams` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `date` date NOT NULL,
  `start` time NOT NULL,
  `end` time NOT NULL,
  `type` enum('midterm','final','makeup_midterm','makeup_final') DEFAULT NULL,
  `room_type` enum('standard','it','lecturehall') NOT NULL,
  `course_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `course_id` (`course_id`),
  CONSTRAINT `exams_ibfk_1` FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `exams`
--

/*!40000 ALTER TABLE `exams` DISABLE KEYS */;
INSERT INTO `exams` (`id`, `date`, `start`, `end`, `type`, `room_type`, `course_id`) VALUES (1,'2025-12-20','10:00:00','12:00:00','final','standard',1),(3,'2025-12-20','10:00:00','12:00:00','final','standard',1),(4,'2025-12-22','10:00:00','12:00:00','final','standard',1),(5,'2025-12-23','00:00:00','13:00:00','final','standard',1),(6,'2025-12-25','00:00:00','02:00:00','midterm','it',1);
/*!40000 ALTER TABLE `exams` ENABLE KEYS */;

--
-- Table structure for table `faculty`
--

DROP TABLE IF EXISTS `faculty`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `faculty` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `faculty`
--

/*!40000 ALTER TABLE `faculty` DISABLE KEYS */;
INSERT INTO `faculty` (`id`, `name`) VALUES (1,'Faculty of Engineering, Natural and Medical Sciences'),(2,'Faculty of Economics and Social Sciences'),(3,'Faculty of Education and Humanities'),(4,'Faculty of Engineering');
/*!40000 ALTER TABLE `faculty` ENABLE KEYS */;

--
-- Table structure for table `rooms`
--

DROP TABLE IF EXISTS `rooms`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `rooms` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `code` int(3) NOT NULL,
  `type` enum('standard','it','lecturehall') DEFAULT NULL,
  `seat_capacity` int(10) unsigned NOT NULL,
  `coord_x` int(11) DEFAULT NULL,
  `coord_y` int(11) DEFAULT NULL,
  `coord_z` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `rooms`
--

/*!40000 ALTER TABLE `rooms` DISABLE KEYS */;
INSERT INTO `rooms` (`id`, `code`, `type`, `seat_capacity`, `coord_x`, `coord_y`, `coord_z`) VALUES (5,320,'standard',50,10,20,1),(11,123,'standard',31,1,2,3);
/*!40000 ALTER TABLE `rooms` ENABLE KEYS */;

--
-- Table structure for table `students`
--

DROP TABLE IF EXISTS `students`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `students` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `first_name` varchar(50) NOT NULL,
  `last_name` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `academic_level` enum('bachelor','master','doctorate') DEFAULT NULL,
  `semester` int(2) DEFAULT NULL,
  `status` enum('active','inactive') DEFAULT NULL,
  `department_id` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `department_id` (`department_id`),
  CONSTRAINT `students_ibfk_1` FOREIGN KEY (`department_id`) REFERENCES `departments` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `students`
--

/*!40000 ALTER TABLE `students` DISABLE KEYS */;
INSERT INTO `students` (`id`, `first_name`, `last_name`, `email`, `academic_level`, `semester`, `status`, `department_id`) VALUES (1,'Jane','Doe','jane.doe@test.com','bachelor',4,'inactive',1),(2,'Nadza','Hasanovic','nadza@test.com','bachelor',3,'active',1),(3,'Alice','Smith','alice.smith@test.com','bachelor',3,'active',1),(4,'Bob','Johnson','bob.johnson@test.com','bachelor',3,'active',1),(5,'Charlie','Williams','charlie.williams@test.com','bachelor',3,'active',1),(6,'David','Brown','david.brown@test.com','bachelor',3,'active',1),(7,'Emily','Jones','emily.jones@test.com','bachelor',3,'active',1),(8,'Frank','Garcia','frank.garcia@test.com','bachelor',3,'active',1),(9,'Grace','Miller','grace.miller@test.com','bachelor',3,'active',1),(10,'Henry','Davis','henry.davis@test.com','bachelor',3,'active',1),(11,'Isabella','Rodriguez','isabella.rodriguez@test.com','bachelor',3,'active',1),(12,'Jack','Martinez','jack.martinez@test.com','bachelor',3,'active',1),(13,'Kate','Hernandez','kate.hernandez@test.com','bachelor',3,'active',1),(14,'','','',NULL,NULL,'inactive',NULL),(15,'Mia','Gonzalez','mia.gonzalez@test.com','bachelor',3,'active',1),(16,'Noah','Wilson','noah.wilson@test.com','bachelor',3,'active',1),(17,'Olivia','Anderson','olivia.anderson@test.com','bachelor',3,'active',1),(18,'Peter','Thomas','peter.thomas@test.com','bachelor',3,'active',1),(19,'Quinn','Taylor','quinn.taylor@test.com','bachelor',3,'active',1),(20,'Test','test','test@gmail.com','master',6,'active',1);
/*!40000 ALTER TABLE `students` ENABLE KEYS */;

--
-- Dumping routines for database 'esras'
--
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2025-12-14 20:28:57
