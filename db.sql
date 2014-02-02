-- phpMyAdmin SQL Dump
-- version 4.0.6deb1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Dec 21, 2013 at 01:17 PM
-- Server version: 5.5.34-0ubuntu0.13.10.1
-- PHP Version: 5.5.3-1ubuntu2.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `demo_blog`
--


-- --------------------------------------------------------

--
-- Table structure for table `comments`
--

CREATE TABLE IF NOT EXISTS `comments` (
  `comment_id` int(11) NOT NULL AUTO_INCREMENT,
  `comment_post_id` int(11) NOT NULL,
  `comment_name` varchar(50) NOT NULL,
  `comment_email` varchar(255) DEFAULT NULL,
  `comment_website` varchar(255) DEFAULT NULL,
  `comment_comment` text NOT NULL,
  `comment_creation_date` datetime DEFAULT NULL,
  `comment_modification_date` datetime DEFAULT NULL,
  `comment_status` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`comment_id`),
  KEY `comment_post_id` (`comment_post_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `contacts`
--

CREATE TABLE IF NOT EXISTS `contacts` (
  `contact_id` int(11) NOT NULL AUTO_INCREMENT,
  `contact_name` varchar(50) DEFAULT NULL,
  `contact_email` varchar(50) DEFAULT NULL,
  `contact_subject` varchar(255) DEFAULT NULL,
  `contact_body` text,
  `contact_creation_date` datetime DEFAULT NULL,
  PRIMARY KEY (`contact_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `posts`
--

CREATE TABLE IF NOT EXISTS `posts` (
  `post_id` int(11) NOT NULL AUTO_INCREMENT,
  `post_user_id` int(11) DEFAULT NULL,
  `post_title` varchar(50) NOT NULL,
  `post_content` varchar(50) NOT NULL,
  `post_tags` varchar(255) DEFAULT NULL,
  `post_status` enum('Draft','Published','Archived') NOT NULL,
  `post_creation_date` datetime DEFAULT NULL,
  `post_modification_date` datetime DEFAULT NULL,
  PRIMARY KEY (`post_id`),
  KEY `post_user_id` (`post_user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `user_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_name` varbinary(50) DEFAULT NULL,
  `user_email` varchar(50) NOT NULL,
  `user_password` varchar(50) NOT NULL,
  `user_creation_date` datetime DEFAULT NULL,
  `user_modification_date` datetime DEFAULT NULL,
  PRIMARY KEY (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `comments`
--
ALTER TABLE `comments`
  ADD CONSTRAINT `comments_ibfk_1` FOREIGN KEY (`comment_post_id`) REFERENCES `posts` (`post_id`);

--
-- Constraints for table `posts`
--
ALTER TABLE `posts`
  ADD CONSTRAINT `posts_ibfk_1` FOREIGN KEY (`post_user_id`) REFERENCES `users` (`user_id`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
