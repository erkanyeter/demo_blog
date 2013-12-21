
USE `demo_blog`;

/*Table structure for table `comments` */

DROP TABLE IF EXISTS `comments`;

CREATE TABLE `comments` (
  `comment_id` int(11) NOT NULL AUTO_INCREMENT,
  `comment_post_id` int(11) NOT NULL,
  `comment_name` varchar(50) NOT NULL,
  `comment_email` varchar(255) DEFAULT NULL,
  `comment_website` varchar(255) DEFAULT NULL,
  `comment_comment` text NOT NULL,
  `comment_creation_date` datetime DEFAULT NULL,
  `comment_modification_date` datetime DEFAULT NULL,
  `comment_status` enum('0','1') DEFAULT '0',
  PRIMARY KEY (`comment_id`),
  KEY `comment_post_id` (`comment_post_id`),
  CONSTRAINT `comments_ibfk_1` FOREIGN KEY (`comment_post_id`) REFERENCES `posts` (`post_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `comments` */

/*Table structure for table `contacts` */

DROP TABLE IF EXISTS `contacts`;

CREATE TABLE `contacts` (
  `contact_id` int(11) NOT NULL AUTO_INCREMENT,
  `contact_name` varchar(50) DEFAULT NULL,
  `contact_email` varchar(50) DEFAULT NULL,
  `contact_subject` varchar(255) DEFAULT NULL,
  `contact_body` text,
  `contact_creation_date` datetime DEFAULT NULL,
  PRIMARY KEY (`contact_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `contacts` */

/*Table structure for table `posts` */

DROP TABLE IF EXISTS `posts`;

CREATE TABLE `posts` (
  `post_id` int(11) NOT NULL AUTO_INCREMENT,
  `post_user_id` int(11) DEFAULT NULL,
  `post_title` varchar(50) NOT NULL,
  `post_content` varchar(50) NOT NULL,
  `post_tags` varchar(255) DEFAULT NULL,
  `post_status` enum('Draft','Published','Archived') NOT NULL,
  `post_creation_date` datetime DEFAULT NULL,
  `post_modification_date` datetime DEFAULT NULL,
  PRIMARY KEY (`post_id`),
  KEY `post_user_id` (`post_user_id`),
  CONSTRAINT `posts_ibfk_1` FOREIGN KEY (`post_user_id`) REFERENCES `users` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `posts` */

/*Table structure for table `users` */

DROP TABLE IF EXISTS `users`;

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_name` varbinary(50) DEFAULT NULL,
  `user_email` varchar(50) NOT NULL,
  `user_password` varchar(50) NOT NULL,
  `user_creation_date` datetime DEFAULT NULL,
  `user_modification_date` datetime DEFAULT NULL,
  PRIMARY KEY (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `users` */

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
