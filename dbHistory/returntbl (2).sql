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
-- Table structure for table `returntbl`
--

CREATE TABLE `returntbl` (
  `returnID` int(11) NOT NULL,
  `borrowID` int(11) NOT NULL,
  `returnDate` timestamp NULL DEFAULT NULL,
  `status` enum('borrowed','returned','overdue') NOT NULL DEFAULT 'borrowed',
  `penalty` decimal(10,2) DEFAULT NULL,
  `returnarc` enum('Archived','Existing') NOT NULL DEFAULT 'Existing',
  `arcReasonrt` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `returntbl`
--

INSERT INTO `returntbl` (`returnID`, `borrowID`, `returnDate`, `status`, `penalty`, `returnarc`, `arcReasonrt`) VALUES
(1, 1, '2024-12-16 22:17:00', 'overdue', 20.00, 'Existing', NULL),
(2, 2, '2024-12-17 09:32:25', 'overdue', 2000.00, 'Existing', NULL),
(3, 3, '2024-12-15 11:48:41', 'returned', 0.00, 'Existing', NULL),
(4, 4, '2024-12-15 22:26:51', 'returned', 0.00, 'Existing', NULL),
(5, 5, '2024-12-18 12:31:22', 'returned', 1000.00, 'Existing', NULL),
(6, 6, '2024-12-20 13:56:09', 'overdue', 10.00, 'Existing', NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `returntbl`
--
ALTER TABLE `returntbl`
  ADD PRIMARY KEY (`returnID`),
  ADD KEY `borrowID` (`borrowID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `returntbl`
--
ALTER TABLE `returntbl`
  MODIFY `returnID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `returntbl`
--
ALTER TABLE `returntbl`
  ADD CONSTRAINT `returntbl_ibfk_1` FOREIGN KEY (`borrowID`) REFERENCES `borrowtbl` (`borrowID`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
