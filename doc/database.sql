SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;


CREATE TABLE IF NOT EXISTS `categories` (
  `id_category` int(11) NOT NULL AUTO_INCREMENT,
  `category` varchar(64) NOT NULL,
  PRIMARY KEY (`id_category`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=17 ;

CREATE TABLE IF NOT EXISTS `items` (
  `id_item` int(11) NOT NULL AUTO_INCREMENT,
  `subscription_id` int(11) NOT NULL,
  `title` varchar(256) NOT NULL,
  `link` varchar(256) NOT NULL,
  `author` varchar(64) NOT NULL,
  `published` double NOT NULL,
  `content` longtext NOT NULL,
  PRIMARY KEY (`id_item`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3511 ;

CREATE TABLE IF NOT EXISTS `log` (
  `id_log` int(11) NOT NULL AUTO_INCREMENT,
  `date` datetime NOT NULL,
  `user_id` int(11) NOT NULL,
  `ip` varchar(64) NOT NULL,
  `action` varchar(32) NOT NULL,
  `description` varchar(128) NOT NULL,
  PRIMARY KEY (`id_log`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

CREATE TABLE IF NOT EXISTS `subscriptions` (
  `id_subscription` int(11) NOT NULL AUTO_INCREMENT,
  `subscription` varchar(128) NOT NULL,
  `url` varchar(256) NOT NULL,
  `name` varchar(128) NOT NULL,
  `last_refresh` datetime NOT NULL,
  PRIMARY KEY (`id_subscription`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=326 ;

CREATE TABLE IF NOT EXISTS `users` (
  `id_user` int(11) NOT NULL AUTO_INCREMENT,
  `user` varchar(48) NOT NULL,
  `email` varchar(128) NOT NULL,
  `password` varchar(128) NOT NULL,
  `hide_readed` int(11) NOT NULL,
  PRIMARY KEY (`id_user`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

CREATE TABLE IF NOT EXISTS `user_category` (
  `id_user_category` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  PRIMARY KEY (`id_user_category`,`user_id`,`category_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=18 ;

CREATE TABLE IF NOT EXISTS `user_category_subscription` (
  `id_user_category_subscription` int(11) NOT NULL AUTO_INCREMENT,
  `user_category_id` int(11) NOT NULL,
  `subscription_id` int(11) NOT NULL,
  PRIMARY KEY (`id_user_category_subscription`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=327 ;

CREATE TABLE IF NOT EXISTS `user_item` (
  `id_user_item` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `item_id` int(11) NOT NULL,
  `reader` int(11) NOT NULL,
  `fav` int(11) NOT NULL,
  PRIMARY KEY (`id_user_item`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
