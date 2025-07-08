-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 08, 2024 at 05:49 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `librarysystem`
--

-- --------------------------------------------------------

--
-- Table structure for table `categorytbl`
--

CREATE TABLE `categorytbl` (
  `categoryID` int(11) NOT NULL,
  `categoryName` varchar(150) NOT NULL,
  `createDate` timestamp NOT NULL DEFAULT current_timestamp(),
  `updateDate` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp(),
  `archCat` enum('Removed','Active') NOT NULL DEFAULT 'Active'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `categorytbl`
--

INSERT INTO `categorytbl` (`categoryID`, `categoryName`, `createDate`, `updateDate`, `archCat`) VALUES
(1, 'Health', '2024-11-08 15:29:14', '2024-11-08 16:47:06', ''),
(2, 'Science', '2024-11-08 16:39:49', NULL, 'Active'),
(3, 'Literature', '2024-11-08 16:39:49', NULL, 'Active'),
(4, 'Technology', '2024-11-08 16:39:49', NULL, 'Active'),
(5, 'History', '2024-11-08 16:39:49', NULL, 'Active'),
(6, 'Arts', '2024-11-08 16:39:49', NULL, 'Active'),
(7, 'Medicine', '2024-11-08 16:39:49', NULL, 'Active'),
(8, 'Education', '2024-11-08 16:39:49', NULL, 'Active'),
(9, 'Mathematics', '2024-11-08 16:39:49', NULL, 'Active'),
(10, 'Biology', '2024-11-08 16:39:49', NULL, 'Active'),
(11, 'Chemistry', '2024-11-08 16:39:49', NULL, 'Active'),
(12, 'Philosophy', '2024-11-08 16:39:49', NULL, 'Active'),
(13, 'Geology', '2024-11-08 16:39:49', NULL, 'Active'),
(14, 'ctTest', '2024-11-08 16:39:49', NULL, 'Active'),
(15, 'Yes', '2024-11-08 16:42:13', '2024-11-08 16:45:16', ''),
(16, 'Yes', '2024-11-08 16:42:32', '2024-11-08 16:45:19', '');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `categorytbl`
--
ALTER TABLE `categorytbl`
  ADD PRIMARY KEY (`categoryID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `categorytbl`
--
ALTER TABLE `categorytbl`
  MODIFY `categoryID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
