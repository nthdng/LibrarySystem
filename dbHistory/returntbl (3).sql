-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 01, 2025 at 11:33 PM
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
  `arcReasonrt` varchar(255) DEFAULT NULL,
  `penaltyStatus` enum('Paid','Unpaid','No penalty') NOT NULL DEFAULT 'No penalty'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `returntbl`
--

INSERT INTO `returntbl` (`returnID`, `borrowID`, `returnDate`, `status`, `penalty`, `returnarc`, `arcReasonrt`, `penaltyStatus`) VALUES
(1, 1, NULL, 'borrowed', 10.00, 'Existing', NULL, 'No penalty'),
(2, 2, '2025-01-01 21:02:07', 'returned', 0.00, 'Existing', NULL, 'No penalty');

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
  MODIFY `returnID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

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
