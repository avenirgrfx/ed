-- phpMyAdmin SQL Dump
-- version 4.3.8
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: May 06, 2015 at 01:49 PM
-- Server version: 5.5.42-37.1
-- PHP Version: 5.4.23

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `khwab_energydas`
--

-- --------------------------------------------------------

--
-- Table structure for table `t_controls`
--

CREATE TABLE IF NOT EXISTS `t_controls` (
  `control_id` int(11) NOT NULL,
  `controls` varchar(50) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=MyISAM AUTO_INCREMENT=16 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `t_controls`
--

INSERT INTO `t_controls` (`control_id`, `controls`) VALUES
(14, 'AIR TURNOVERS'),
(15, 'ELECTRICAL SYSTEMS'),
(10, 'NATURAL GAS SYSTEMS');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `t_controls`
--
ALTER TABLE `t_controls`
  ADD PRIMARY KEY (`control_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `t_controls`
--
ALTER TABLE `t_controls`
  MODIFY `control_id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=16;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
