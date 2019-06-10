SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Datenbank: `abschlussprojekt`
--
CREATE DATABASE IF NOT EXISTS `abschlussprojekt` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
USE `abschlussprojekt`;

DROP TABLE IF EXISTS `image`;
DROP TABLE IF EXISTS `tag`;
DROP TABLE IF EXISTS `image_tag`;
DROP TABLE IF EXISTS `user`;
DROP TABLE IF EXISTS `user_image`;

CREATE TABLE `image` (
  `id` int(11) PRIMARY KEY AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
   `latitude` varchar(50) DEFAULT NULL,
   `longitude` varchar(50) DEFAULT NULL,
`capture_date` datetime,
`owner_id` int(11) REFERENCES `user`(`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `tag` (
  `id` int(11) PRIMARY KEY AUTO_INCREMENT,
  `name` varchar(50) NOT NULL UNIQUE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `image_tag` (
  `image_id` int(11) REFERENCES `image`(`id`),
  `tag_id` int(11) REFERENCES `tag`(`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `user`(
`id` int(11) PRIMARY KEY,
`username` varchar(50) UNIQUE,
`firstname` varchar(50) NOT NULL,
`lastname` varchar(50) NOT NULL,
`email` varchar(50) NOT NULL,
`pwd` varchar(100) NOT NULL,
`isAdmin` bool DEFAULT 0,
`isActive` bool DEFAULT 1
);

CREATE TABLE `user_image` (
  `image_id` int(11) REFERENCES `image`(`id`),
  `user_id` int(11) REFERENCES `user`(`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

