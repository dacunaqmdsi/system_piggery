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

insert  into `tblaccounts`(`accountid`,`username`,`account_password`,`account_type`,`is_blocked`) values (1,'darren','darren','Farm Owner',0),(2,'Care Taker','Care Taker','Care Taker',0),(3,'DA','DA','Care Taker',0);

/*Table structure for table `tblaudittrail` */

DROP TABLE IF EXISTS `tblaudittrail`;

CREATE TABLE `tblaudittrail` (
  `audit_id` int(11) NOT NULL AUTO_INCREMENT,
  `activity` varchar(128) DEFAULT '',
  `description` varchar(128) DEFAULT '',
  `accountid` int(11) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`audit_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Data for the table `tblaudittrail` */

/*Table structure for table `tblbirth` */

DROP TABLE IF EXISTS `tblbirth`;

CREATE TABLE `tblbirth` (
  `birth_id` int(11) NOT NULL AUTO_INCREMENT,
  `dob` date DEFAULT NULL,
  `total_piglets` int(11) DEFAULT 0,
  `deaths` int(11) DEFAULT 0,
  `alive` int(11) DEFAULT 0,
  `pen_number` varchar(128) DEFAULT '',
  PRIMARY KEY (`birth_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Data for the table `tblbirth` */

/*Table structure for table `tblcategory` */

DROP TABLE IF EXISTS `tblcategory`;

CREATE TABLE `tblcategory` (
  `categoryid` int(11) NOT NULL AUTO_INCREMENT,
  `category` varchar(128) DEFAULT '',
  `is_archived` varchar(128) DEFAULT 'No',
  PRIMARY KEY (`categoryid`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Data for the table `tblcategory` */

insert  into `tblcategory`(`categoryid`,`category`,`is_archived`) values (1,'Feeds','Yes'),(2,'Medicine/Vitamins','No'),(3,'Utilities (Water, Electricity)','No'),(4,'Salaries/Labor','Yes'),(5,'Maintenance/Repairs','Yes'),(6,'Equipment Purchase','Yes'),(7,'Transportation','Yes'),(8,'Other','No');

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Data for the table `tblcustomer` */

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Data for the table `tblexpense` */

/*Table structure for table `tblinventory` */

DROP TABLE IF EXISTS `tblinventory`;

CREATE TABLE `tblinventory` (
  `inventory_id` int(11) NOT NULL AUTO_INCREMENT,
  `pen_number` varchar(128) DEFAULT '',
  `count_` int(11) DEFAULT 0,
  `pen_type` varchar(128) DEFAULT '',
  `mothers_pen` varchar(128) DEFAULT '',
  `created_at` datetime DEFAULT current_timestamp(),
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Data for the table `tblmonitor` */

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Data for the table `tblproducts` */

/*Table structure for table `tblsales` */

DROP TABLE IF EXISTS `tblsales`;

CREATE TABLE `tblsales` (
  `sale_id` int(11) NOT NULL AUTO_INCREMENT,
  `receipt_id` varchar(128) DEFAULT '',
  `sale_date` date DEFAULT NULL,
  `customerid` int(11) DEFAULT 0,
  `inventory_id` int(11) DEFAULT 0,
  `qty` int(11) DEFAULT 0,
  `price` double DEFAULT 0,
  `is_void` varchar(128) DEFAULT 'No',
  PRIMARY KEY (`sale_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Data for the table `tblsales` */

/*Table structure for table `tblsymptom` */

DROP TABLE IF EXISTS `tblsymptom`;

CREATE TABLE `tblsymptom` (
  `symptom_id` int(11) NOT NULL AUTO_INCREMENT,
  `symptom` varchar(255) DEFAULT '',
  `suggested_action` varchar(255) DEFAULT '',
  `is_archived` varchar(128) DEFAULT 'No',
  PRIMARY KEY (`symptom_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Data for the table `tblsymptom` */

/*Table structure for table `tmp_sale1` */

DROP TABLE IF EXISTS `tmp_sale1`;

CREATE TABLE `tmp_sale1` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `inventory_id` int(11) DEFAULT 0,
  `qty` double DEFAULT 0,
  `price` double DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

/*Data for the table `tmp_sale1` */

/*Table structure for table `tmp_sale2` */

DROP TABLE IF EXISTS `tmp_sale2`;

CREATE TABLE `tmp_sale2` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `inventory_id` int(11) DEFAULT 0,
  `qty` double DEFAULT 0,
  `price` double DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

/*Data for the table `tmp_sale2` */

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
