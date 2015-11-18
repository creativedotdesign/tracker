-- phpMyAdmin SQL Dump
-- version 3.4.11.1deb2+deb7u1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Nov 18, 2015 at 04:21 PM
-- Server version: 5.6.25
-- PHP Version: 5.6.13-1~dotdeb+7.1

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

-- --------------------------------------------------------

--
-- Table structure for table `theme_data`
--

CREATE TABLE IF NOT EXISTS `theme_data` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` text NOT NULL,
  `theme_name` text NOT NULL,
  `theme_version` text NOT NULL,
  `theme_author` text NOT NULL,
  `site_name` text NOT NULL,
  `site_url` text NOT NULL,
  `ip_address` text NOT NULL,
  `environment` text NOT NULL,
  `wordpress_version` text NOT NULL,
  `date_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=21 ;
