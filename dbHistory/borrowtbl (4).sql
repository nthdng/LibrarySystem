-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 20, 2024 at 08:27 PM
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
-- Table structure for table `borrowtbl`
--

CREATE TABLE `borrowtbl` (
  `borrowID` int(11) NOT NULL,
  `bookID` int(11) NOT NULL,
  `Username` varchar(100) DEFAULT NULL COMMENT 'Library and Admins only',
  `Name` varchar(255) DEFAULT NULL COMMENT 'Name of borrowers',
  `Email` varchar(255) DEFAULT NULL COMMENT 'email to send the confirm status',
  `FacultyID` varchar(100) DEFAULT NULL,
  `StudentID` varchar(100) DEFAULT NULL,
  `borrowDate` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp(),
  `expectedReturnDate` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp(),
  `status` enum('pending','borrowed','confirmed') NOT NULL DEFAULT 'pending',
  `borrowarc` enum('Archived','Existing') NOT NULL DEFAULT 'Existing',
  `arcReason` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `borrowtbl`
--

INSERT INTO `borrowtbl` (`borrowID`, `bookID`, `Username`, `Name`, `Email`, `FacultyID`, `StudentID`, `borrowDate`, `expectedReturnDate`, `status`, `borrowarc`, `arcReason`) VALUES
(1, 2, 'DefaultAdmin', NULL, NULL, NULL, 'A12321232', '2024-12-16 22:17:00', '2024-12-16 22:17:00', 'borrowed', 'Archived', 'Returned'),
(2, 2, 'DefaultAdmin', NULL, NULL, NULL, 'A12321232', '2024-12-17 09:32:25', '2024-12-17 09:32:25', 'borrowed', 'Archived', 'Returned'),
(3, 2, 'DefaultAdmin', NULL, NULL, NULL, 'A1212', '2024-12-15 11:48:41', '2024-12-15 11:48:41', 'borrowed', 'Archived', 'Returned'),
(4, 26, 'DefaultAdmin', NULL, NULL, 'Admin-123', NULL, '2024-12-15 22:26:51', '2024-12-15 22:26:51', 'borrowed', 'Archived', 'Returned'),
(5, 26, 'DefaultAdmin', NULL, NULL, NULL, 'A12321232', '2024-12-18 12:31:22', '2024-12-18 12:31:22', 'borrowed', 'Archived', 'Returned'),
(6, 8, 'MjLibrarian', NULL, NULL, NULL, 'A21010451', '2024-12-20 13:56:09', '2024-12-20 13:56:09', 'borrowed', 'Archived', 'Returned'),
(7, 30, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'pending', 'Existing', NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `borrowtbl`
--
ALTER TABLE `borrowtbl`
  ADD PRIMARY KEY (`borrowID`),
  ADD KEY `bookID` (`bookID`),
  ADD KEY `Username` (`Username`),
  ADD KEY `FacultyID` (`FacultyID`),
  ADD KEY `StudentID` (`StudentID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `borrowtbl`
--
ALTER TABLE `borrowtbl`
  MODIFY `borrowID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `borrowtbl`
--
ALTER TABLE `borrowtbl`
  ADD CONSTRAINT `borrowtbl_ibfk_1` FOREIGN KEY (`bookID`) REFERENCES `booktbl` (`bookID`),
  ADD CONSTRAINT `borrowtbl_ibfk_2` FOREIGN KEY (`Username`) REFERENCES `tbluser` (`Username`),
  ADD CONSTRAINT `borrowtbl_ibfk_3` FOREIGN KEY (`FacultyID`) REFERENCES `tblfaculty` (`FacultyID`),
  ADD CONSTRAINT `borrowtbl_ibfk_4` FOREIGN KEY (`StudentID`) REFERENCES `tblstudent` (`StudentID`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
