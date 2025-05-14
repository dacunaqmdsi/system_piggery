/*
SQLyog Ultimate v8.55 
MySQL - 5.5.5-10.4.32-MariaDB : Database - piggery
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
/*Table structure for table `tblaccounts` */

DROP TABLE IF EXISTS `tblaccounts`;

CREATE TABLE `tblaccounts` (
  `accountid` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(128) DEFAULT '',
  `account_password` varchar(128) DEFAULT '',
  `account_type` varchar(128) DEFAULT '',
  `is_blocked` tinyint(1) DEFAULT 0,
  PRIMARY KEY (`accountid`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Data for the table `tblaccounts` */

insert  into `tblaccounts`(`accountid`,`username`,`account_password`,`account_type`,`is_blocked`) values (1,'darren','darren','Farm Owner',0),(2,'Kelllyssss','Kelly','Care Taker',0),(3,'DA','DA','Care Taker',0);

/*Table structure for table `tblaudittrail` */

DROP TABLE IF EXISTS `tblaudittrail`;

CREATE TABLE `tblaudittrail` (
  `audit_id` int(11) NOT NULL AUTO_INCREMENT,
  `activity` varchar(128) DEFAULT '',
  `description` varchar(128) DEFAULT '',
  `accountid` int(11) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`audit_id`)
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Data for the table `tblaudittrail` */

insert  into `tblaudittrail`(`audit_id`,`activity`,`description`,`accountid`,`created_at`) values (1,'Logout','Logout',1,'2025-05-14 20:24:01'),(2,'Login','Login',1,'2025-05-14 20:24:10'),(3,'Login','Login',1,'2025-05-14 20:37:53'),(4,'Login','Login',1,'2025-05-14 20:40:50'),(5,'Login','Login',1,'2025-05-14 20:45:53'),(6,'Login','Login',1,'2025-05-14 20:50:06'),(7,'Login','Login',1,'2025-05-14 20:53:11'),(8,'Login','Login',1,'2025-05-14 20:53:12'),(9,'Login','Login',1,'2025-05-14 20:54:46'),(10,'Login','Login',1,'2025-05-14 21:25:36'),(11,'Login','Login',1,'2025-05-14 21:26:07'),(12,'Login','Login',1,'2025-05-14 21:34:40'),(13,'Login','Login',1,'2025-05-14 21:35:39'),(14,'Login','Login',1,'2025-05-14 21:35:43'),(15,'Login','Login',1,'2025-05-14 21:35:46'),(16,'Login','Login',1,'2025-05-14 21:36:04'),(17,'Login','Login',1,'2025-05-14 21:36:18'),(18,'Login','Login',1,'2025-05-14 21:36:25'),(19,'Login','Login',1,'2025-05-14 21:36:56'),(20,'Login','Login',1,'2025-05-14 21:40:07'),(21,'Login','Login',1,'2025-05-14 21:44:16'),(22,'Login','Login',1,'2025-05-14 21:44:18'),(23,'Updated new user','Updated new user',1,'2025-05-14 21:59:45'),(24,'Added new product','Added new product',1,'2025-05-14 22:08:33');

/*Table structure for table `tblcategory` */

DROP TABLE IF EXISTS `tblcategory`;

CREATE TABLE `tblcategory` (
  `categoryid` int(11) NOT NULL AUTO_INCREMENT,
  `category` varchar(128) DEFAULT '',
  `is_archived` varchar(128) DEFAULT 'No',
  PRIMARY KEY (`categoryid`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Data for the table `tblcategory` */

insert  into `tblcategory`(`categoryid`,`category`,`is_archived`) values (1,'Feed','Yes'),(2,'Medicine/Vitamins','No'),(3,'Utilities (Water, Electricity)','No'),(4,'Salaries/Labor','No'),(5,'Maintenance/Repairs','Yes'),(6,'Equipment Purchase','No'),(7,'Transportation','No'),(8,'Other','No');

/*Table structure for table `tblcustomer` */

DROP TABLE IF EXISTS `tblcustomer`;

CREATE TABLE `tblcustomer` (
  `customerid` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `notes` varchar(255) DEFAULT NULL,
  `actions` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`customerid`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Data for the table `tblcustomer` */

insert  into `tblcustomer`(`customerid`,`name`,`phone`,`email`,`address`,`notes`,`actions`) values (1,'kell','0918238123','dar@gmail.com','0923kndfsn','Notes',NULL),(2,'Darren Acuna','09611917651','darrencelzo77@gmail.com','darren','daraewnr',NULL);

/*Table structure for table `tblexpense` */

DROP TABLE IF EXISTS `tblexpense`;

CREATE TABLE `tblexpense` (
  `expenseid` int(11) NOT NULL AUTO_INCREMENT,
  `expense_date` date DEFAULT NULL,
  `categoryid` int(11) DEFAULT 0,
  `amount` double DEFAULT 0,
  `description` varchar(128) DEFAULT '',
  `refnum` varchar(128) DEFAULT '',
  PRIMARY KEY (`expenseid`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Data for the table `tblexpense` */

insert  into `tblexpense`(`expenseid`,`expense_date`,`categoryid`,`amount`,`description`,`refnum`) values (1,'2025-05-14',6,90,'Tirttete',''),(2,'2025-05-14',6,100,'Desc','22'),(3,'2025-05-20',2,3333,'Devvvv','');

/*Table structure for table `tblinventory` */

DROP TABLE IF EXISTS `tblinventory`;

CREATE TABLE `tblinventory` (
  `inventory_id` int(11) NOT NULL AUTO_INCREMENT,
  `pen_number` varchar(128) DEFAULT '',
  `count` int(11) DEFAULT 0,
  `pigtype_id` int(11) DEFAULT 9,
  `mothers_pen` varchar(128) DEFAULT '',
  `count_piglets` int(11) DEFAULT 0,
  PRIMARY KEY (`inventory_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Data for the table `tblinventory` */

/*Table structure for table `tblmonitor` */

DROP TABLE IF EXISTS `tblmonitor`;

CREATE TABLE `tblmonitor` (
  `monitor_id` int(11) NOT NULL AUTO_INCREMENT,
  `pen_number` varchar(128) DEFAULT '',
  `monitor_date` date DEFAULT NULL,
  `symptom_id` int(11) DEFAULT 0,
  `description` varchar(128) DEFAULT '',
  `suggested_action` varchar(255) DEFAULT '',
  PRIMARY KEY (`monitor_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Data for the table `tblmonitor` */

insert  into `tblmonitor`(`monitor_id`,`pen_number`,`monitor_date`,`symptom_id`,`description`,`suggested_action`) values (1,'PEN001','2001-10-01',1,'Sample Desc',''),(2,'PEM002','2025-05-28',2,'Sample','');

/*Table structure for table `tblpen` */

DROP TABLE IF EXISTS `tblpen`;

CREATE TABLE `tblpen` (
  `penid` int(11) NOT NULL AUTO_INCREMENT,
  `pend` varchar(128) DEFAULT '',
  PRIMARY KEY (`penid`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Data for the table `tblpen` */

insert  into `tblpen`(`penid`,`pend`) values (1,'Sow'),(2,'Boar'),(3,'Fattener'),(4,'Piglet');

/*Table structure for table `tblpigtype` */

DROP TABLE IF EXISTS `tblpigtype`;

CREATE TABLE `tblpigtype` (
  `pigtype_id` int(11) NOT NULL AUTO_INCREMENT,
  `pigtype` varchar(128) DEFAULT '',
  PRIMARY KEY (`pigtype_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Data for the table `tblpigtype` */

insert  into `tblpigtype`(`pigtype_id`,`pigtype`) values (1,'Type 1'),(2,'Type 2');

/*Table structure for table `tblproducts` */

DROP TABLE IF EXISTS `tblproducts`;

CREATE TABLE `tblproducts` (
  `product_id` int(11) NOT NULL AUTO_INCREMENT,
  `product_name` varchar(128) DEFAULT '',
  `categoryid` int(11) DEFAULT 0,
  `current_qty` double DEFAULT 0,
  `unit` varchar(128) DEFAULT '',
  `critical_level` varchar(128) DEFAULT '',
  PRIMARY KEY (`product_id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Data for the table `tblproducts` */

insert  into `tblproducts`(`product_id`,`product_name`,`categoryid`,`current_qty`,`unit`,`critical_level`) values (1,'Gasoline',1,10,'LITERS','29'),(2,'Diesel Vin',4,202,'LIT','33'),(3,'Feeds',8,20,'KG','23');

/*Table structure for table `tblsymptom` */

DROP TABLE IF EXISTS `tblsymptom`;

CREATE TABLE `tblsymptom` (
  `symptom_id` int(11) NOT NULL AUTO_INCREMENT,
  `symptom` varchar(255) DEFAULT '',
  `suggested_action` varchar(255) DEFAULT '',
  PRIMARY KEY (`symptom_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Data for the table `tblsymptom` */

insert  into `tblsymptom`(`symptom_id`,`symptom`,`suggested_action`) values (1,'Pagtatae','Diatabs'),(2,'Suka','Tae sggestion');

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
