-- phpMyAdmin SQL Dump
-- Database: `yardhandicraft`

CREATE DATABASE IF NOT EXISTS `yardhandicraft` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `yardhandicraft`;

-- --------------------------------------------------------

--
-- Table structure for table `contacts`
--

CREATE TABLE IF NOT EXISTS `contacts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `fb_link` varchar(255) NOT NULL,
  `number` varchar(50) NOT NULL,
  `message` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `preorders`
--

CREATE TABLE IF NOT EXISTS `preorders` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `product` varchar(255) NOT NULL,
  `price` double NOT NULL,
  `name` varchar(255) NOT NULL,
  `address` text NOT NULL,
  `contact` varchar(50) NOT NULL,
  `fb_link` varchar(255) NOT NULL,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
