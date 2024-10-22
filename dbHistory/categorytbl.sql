-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 04, 2024 at 07:39 AM
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
-- Database: `importdb`
--

-- --------------------------------------------------------

--
-- Table structure for table `categorytbl`
--

CREATE TABLE `categorytbl` (
  `ID` int(11) NOT NULL,
  `categoryName` varchar(150) NOT NULL,
  `createDate` timestamp NOT NULL DEFAULT current_timestamp(),
  `updateDate` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `categorytbl`
--

INSERT INTO `categorytbl` (`ID`, `categoryName`, `createDate`, `updateDate`) VALUES
(1, 'Minor Subject', '2024-09-25 22:49:14', NULL),
(2, 'Technology', '2024-09-26 09:10:10', NULL),
(3, '1', '2024-09-26 12:40:29', NULL),
(4, 'Health', '2024-09-26 12:41:59', NULL),
(5, 'Science', '2024-10-03 16:53:36', NULL),
(6, 'Literature', '2024-10-03 16:53:36', NULL),
(7, 'History', '2024-10-03 16:53:36', NULL),
(8, 'Arts', '2024-10-03 16:53:36', NULL),
(9, 'Medicine', '2024-10-03 17:55:30', NULL),
(10, 'Education', '2024-10-03 17:55:30', NULL),
(11, 'Mathematics', '2024-10-03 17:55:30', NULL),
(12, 'Biology', '2024-10-03 17:55:30', NULL),
(13, 'Chemistry', '2024-10-03 17:55:30', NULL),
(14, 'Philosophy', '2024-10-03 17:55:30', NULL),
(15, 'Geology', '2024-10-03 17:55:30', NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `categorytbl`
--
ALTER TABLE `categorytbl`
  ADD PRIMARY KEY (`ID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `categorytbl`
--
ALTER TABLE `categorytbl`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
