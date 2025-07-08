-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 20, 2024 at 07:44 PM
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
-- Table structure for table `formattbl`
--

CREATE TABLE `formattbl` (
  `formatID` int(11) NOT NULL,
  `formatName` varchar(150) NOT NULL,
  `createDate` timestamp NOT NULL DEFAULT current_timestamp(),
  `updateDate` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp(),
  `archFor` enum('Removed','Active') NOT NULL DEFAULT 'Active'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `formattbl`
--

INSERT INTO `formattbl` (`formatID`, `formatName`, `createDate`, `updateDate`, `archFor`) VALUES
(1, 'Thesis', '2024-12-13 15:33:42', NULL, 'Active'),
(2, 'Hardcover', '2024-12-13 15:33:42', NULL, 'Active'),
(3, 'Paperback', '2024-12-13 15:33:42', NULL, 'Active'),
(4, 'eBook', '2024-12-13 15:33:42', NULL, 'Active'),
(5, 'FormatTest', '2024-12-13 15:33:42', NULL, 'Active'),
(6, 'asa', '2024-12-17 10:14:32', NULL, 'Active'),
(7, 'Magazine', '2024-12-18 13:44:32', NULL, 'Active'),
(8, 'Digital', '2024-12-18 13:46:37', NULL, 'Active'),
(9, 'CD', '2024-12-18 13:46:37', NULL, 'Active');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `formattbl`
--
ALTER TABLE `formattbl`
  ADD PRIMARY KEY (`formatID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `formattbl`
--
ALTER TABLE `formattbl`
  MODIFY `formatID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
