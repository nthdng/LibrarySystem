-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 20, 2024 at 07:43 PM
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
-- Table structure for table `shelftbl`
--

CREATE TABLE `shelftbl` (
  `shelfID` int(11) NOT NULL,
  `shelfLoc` varchar(100) NOT NULL,
  `createDate` timestamp NOT NULL DEFAULT current_timestamp(),
  `updateDate` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp(),
  `archShelf` enum('Active','Removed') NOT NULL DEFAULT 'Active'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `shelftbl`
--

INSERT INTO `shelftbl` (`shelfID`, `shelfLoc`, `createDate`, `updateDate`, `archShelf`) VALUES
(1, 'Shelf 27', '2024-12-13 15:33:42', NULL, 'Active'),
(2, 'Shelf 10', '2024-12-13 15:33:42', NULL, 'Active'),
(3, 'Shelf 15', '2024-12-13 15:33:42', NULL, 'Active'),
(4, 'Shelf 20', '2024-12-13 15:33:42', NULL, 'Active'),
(5, 'Shelf 25', '2024-12-13 15:33:42', NULL, 'Active'),
(6, 'Shelf 30', '2024-12-13 15:33:42', NULL, 'Active'),
(7, 'Shelf 35', '2024-12-13 15:33:42', NULL, 'Active'),
(8, 'Shelf 40', '2024-12-13 15:33:42', NULL, 'Active'),
(9, 'Shelf 45', '2024-12-13 15:33:42', NULL, 'Active'),
(10, 'Shelf 50', '2024-12-13 15:33:42', NULL, 'Active'),
(11, 'Shelf 55', '2024-12-13 15:33:42', NULL, 'Active'),
(12, 'Shelf 60', '2024-12-13 15:33:42', NULL, 'Active'),
(13, 'Shelf 65', '2024-12-13 15:33:42', NULL, 'Active'),
(14, 'Shelf 70', '2024-12-13 15:33:42', NULL, 'Active'),
(15, 'Shelf 75', '2024-12-13 15:33:42', NULL, 'Active'),
(16, 'Shelf 80', '2024-12-13 15:33:42', NULL, 'Active'),
(17, 'Shelf 1', '2024-12-13 15:33:42', NULL, 'Active'),
(18, 'Shelf 2', '2024-12-13 15:33:42', NULL, 'Active'),
(19, 'Shelf 3', '2024-12-13 15:33:42', NULL, 'Active'),
(20, 'Shelf 4', '2024-12-13 15:33:42', NULL, 'Active'),
(21, 'Shelf 5', '2024-12-13 15:33:42', NULL, 'Active'),
(22, 'Shelf sf', '2024-12-13 15:33:42', NULL, 'Active'),
(23, 'Shelf 90', '2024-12-18 13:46:37', NULL, 'Active'),
(24, 'Shelf 85', '2024-12-18 13:46:37', NULL, 'Active'),
(25, 'Shelf 95', '2024-12-18 13:46:37', NULL, 'Active'),
(26, 'Shelf 87', '2024-12-18 13:46:37', NULL, 'Active'),
(27, 'Shelf 78', '2024-12-18 13:46:37', NULL, 'Active');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `shelftbl`
--
ALTER TABLE `shelftbl`
  ADD PRIMARY KEY (`shelfID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `shelftbl`
--
ALTER TABLE `shelftbl`
  MODIFY `shelfID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
