-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Sep 15, 2021 at 02:58 PM
-- Server version: 5.7.31
-- PHP Version: 7.4.9

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `mi_project_core`
--

-- --------------------------------------------------------

--
-- Table structure for table `tbl_token_auth`
--

DROP TABLE IF EXISTS `tbl_token_auth`;
CREATE TABLE IF NOT EXISTS `tbl_token_auth` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(255) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `selector_hash` varchar(255) NOT NULL,
  `is_expired` int(11) NOT NULL DEFAULT '0',
  `expiry_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=26 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_token_auth`
--

INSERT INTO `tbl_token_auth` (`id`, `username`, `password_hash`, `selector_hash`, `is_expired`, `expiry_date`) VALUES
(17, 'admin', '$2y$10$G5q2cvL4uVXCtoDVBsDcD.maZrhZJAUsvc/zsiSYPp1a7YEmEUjOG', '$2y$10$lGCGKYsEpZatdRGR7ryFCuLCHhhdlzTY5c85NGRr/F0ScuhZlz3Be', 1, '2021-08-24 14:24:04'),
(20, 'admin', '$2y$10$.N7z38TlIAMmbvPQYXbxVOLlbjucJLQXcQGlDVuSCuP/iMBvRyBD.', '$2y$10$G.4mTWjcqN5GK955ePSKZewGRXzUDoMCw7LctykIkPSPcUa.ZvLPa', 1, '2021-08-26 15:30:52'),
(21, 'admin', '$2y$10$wShZ2WlyiLT/0HpjbYw0iuWMYq3jR91Wai2JkpSVy/ev.IF3vccFW', '$2y$10$m4lanpXrAzHqF2kg4Z8z9.VxsT7mOdrbLLLMBrj3mRBra4nZ.ac4.', 1, '2021-08-26 15:33:10'),
(22, 'admin', '$2y$10$T2nTPdAnO9028mTc9/uzIepBFHMABOeUen.PS3FQcewiVro.Qd4Ea', '$2y$10$3LvMJ8zwnXlcA0sqcifz3OXsBBAuRWLWEVwwGqEK3WoRfIexYafXy', 1, '2021-09-04 13:31:04'),
(23, 'admin', '$2y$10$U5q2QLhWpcEoE2rIN4gDV.lej7CAG4xmNkjBhw35aH0EcAfo.JR0.', '$2y$10$TGNVRhbzofzLd043kkFgHO6NzAj24xsSBxxEi5iArBmZ4tUvJU.S6', 1, '2021-09-04 14:44:53'),
(24, 'admin', '$2y$10$Dwsf1mbQHqBNEI2NXzNHAur3VxP4MCipcawxplZavY95cdKKUQTn.', '$2y$10$Ih6IOdGzC7AvuPikT2aCteWAjVgpVY61bHDaz76gVJD1h8kQMMyZ6', 1, '2021-09-13 14:54:42'),
(25, 'admin', '$2y$10$iOHGY7ptpw4/425ecSE5XO1o04g/RfzV/dnPCtPBeVGVbyOvXWnZq', '$2y$10$/jCin/uPyC49JTsPyji8S.cK92RBQaw28TwPHWkuducO29TZRgIki', 0, '2021-09-13 09:26:42');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `user_id` int(8) NOT NULL AUTO_INCREMENT,
  `user_name` varchar(255) CHARACTER SET utf8 NOT NULL,
  `first_name` varchar(40) DEFAULT NULL,
  `last_name` varchar(40) DEFAULT NULL,
  `user_password` varchar(64) DEFAULT NULL,
  `user_email` varchar(255) CHARACTER SET utf8 NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `oauth_type` varchar(10) DEFAULT 'website',
  `google_id` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `user_name`, `first_name`, `last_name`, `user_password`, `user_email`, `created_at`, `updated_at`, `oauth_type`, `google_id`) VALUES
(1, 'admin', NULL, NULL, '$2a$10$0FHEQ5/cplO3eEKillHvh.y009Wsf4WCKvQHsZntLamTUToIBe.fG', 'admin@mail.com', '2021-08-19 03:27:26', NULL, 'website', NULL),
(3, 'user1', NULL, NULL, '$2y$10$tnU64QgBP5I3KyZP.uAwcOeZnt.jeDYmLhZHBPD/vRLVJ2Zc4Tzua', 'user1@mail.com', '2021-08-26 03:27:26', NULL, 'website', NULL),
(18, 'atulsharma', NULL, NULL, NULL, 'atul.acewebx@gmail.com', '2021-09-14 07:28:14', '2021-09-10 07:41:10', 'google', '117157013424856368471');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
