-- phpMyAdmin SQL Dump
-- version 4.1.6
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Aug 11, 2024 at 02:46 PM
-- Server version: 5.6.16
-- PHP Version: 5.5.9

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `final`
--

-- --------------------------------------------------------

--
-- Table structure for table `data`
--

CREATE TABLE IF NOT EXISTS `data` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `gender` varchar(10) NOT NULL,
  `age` int(11) NOT NULL,
  `address` text NOT NULL,
  `tc_file` varchar(255) DEFAULT NULL,
  `mark_list_file` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

--
-- Dumping data for table `data`
--

INSERT INTO `data` (`id`, `username`, `name`, `email`, `gender`, `age`, `address`, `tc_file`, `mark_list_file`) VALUES
(1, 'user1', 'nikhil', 'nikhil@gmail.com', 'Male', 22, 'nikhilhouse', NULL, NULL),
(2, 'user1', 'akhil', 'akhil@gmail.com', 'Male', 24, 'abcdedfghouse', NULL, NULL),
(3, 'user2', 'aju', 'aju@gmail.com', 'Male', 34, 'ajuveedu', NULL, NULL),
(4, 'user3', 'raju', 'raju@gmail.com', 'Male', 49, 'rajuhouse', 'uploads/administrator,+unblj_v69_forum02.pdf', 'uploads/M V Rahul - GNLU PhD Admission Application Form for Year 2024.pdf');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
