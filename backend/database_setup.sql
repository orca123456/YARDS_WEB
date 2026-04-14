-- phpMyAdmin SQL Dump
-- Database: `yardhandicraft`

CREATE DATABASE IF NOT EXISTS `yardhandicraft` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `yardhandicraft`;

-- --------------------------------------------------------
-- Table: contacts
-- --------------------------------------------------------

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
-- Table: preorders  (updated with payment & status columns)
-- --------------------------------------------------------

CREATE TABLE IF NOT EXISTS `preorders` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `product` varchar(255) NOT NULL,
  `price` double NOT NULL,
  `name` varchar(255) NOT NULL,
  `address` text NOT NULL,
  `contact` varchar(50) NOT NULL,
  `fb_link` varchar(255) NOT NULL,
  `notes` text DEFAULT NULL,
  `payment_method` varchar(100) DEFAULT NULL,
  `payment_proof` varchar(255) DEFAULT NULL,
  `order_status` enum('pending','confirmed','processing','shipped','delivered','cancelled') NOT NULL DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------
-- Table: admins
-- --------------------------------------------------------

CREATE TABLE IF NOT EXISTS `admins` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(100) NOT NULL UNIQUE,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------
-- NOTE: Do NOT insert admin credentials here.
-- Instead, open this URL in your browser ONCE after importing:
--   http://localhost/YARDS_WEB/backend/create_admin.php
-- That script will create the admin account securely.
-- Then DELETE create_admin.php from your project.
-- --------------------------------------------------------
