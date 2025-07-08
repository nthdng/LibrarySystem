-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 21, 2024 at 05:57 PM
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
  `Username` varchar(100) DEFAULT NULL,
  `FacultyID` varchar(100) DEFAULT NULL,
  `StudentID` varchar(100) DEFAULT NULL,
  `borrowDate` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp(),
  `expectedReturnDate` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp(),
  `status` enum('pending','borrowed') NOT NULL DEFAULT 'pending',
  `borrowarc` enum('Archived','Existing') NOT NULL DEFAULT 'Existing'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `borrowtbl`
--

INSERT INTO `borrowtbl` (`borrowID`, `bookID`, `Username`, `FacultyID`, `StudentID`, `borrowDate`, `expectedReturnDate`, `status`, `borrowarc`) VALUES
(1, 3, NULL, NULL, NULL, NULL, NULL, 'pending', 'Existing'),
(2, 11, NULL, NULL, NULL, NULL, NULL, 'pending', 'Existing');

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
  MODIFY `borrowID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

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
