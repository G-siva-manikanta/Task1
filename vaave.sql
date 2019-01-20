-- phpMyAdmin SQL Dump
-- version 4.5.1
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Jan 13, 2019 at 06:12 AM
-- Server version: 10.1.16-MariaDB
-- PHP Version: 7.0.9

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `vaave`
--

-- --------------------------------------------------------

--
-- Table structure for table `user_places`
--

CREATE TABLE `user_places` (
  `id` int(11) NOT NULL,
  `lat` varchar(25) NOT NULL,
  `longitude` varchar(25) NOT NULL,
  `city` varchar(55) NOT NULL,
  `country` varchar(55) NOT NULL,
  `date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `user_id` bigint(20) NOT NULL,
  `address` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `user_places`
--

INSERT INTO `user_places` (`id`, `lat`, `longitude`, `city`, `country`, `date`, `user_id`, `address`) VALUES
(1, '16.9640608', '82.2279031', 'Kakinada', 'India', '2019-01-13 10:16:23', 190113053911, '10-6-14, Subbayya Hotel Road, Rama Rao Peta, Kakinada, Andhra Pradesh 533001, India'),
(2, '17.4251879', '78.4503301', 'Hyderabad', 'India', '2019-01-13 10:17:38', 190113053911, '6-3-347/17/11a, Dwarakapuri, Punjagutta, Hyderabad, Telangana 500082, India'),
(3, '37.7415419', '-122.3872629', 'San Francisco', 'United States', '2019-01-13 10:19:03', 190113053911, '3801 3rd St, San Francisco, CA 94124, USA');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `user_places`
--
ALTER TABLE `user_places`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `user_places`
--
ALTER TABLE `user_places`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
