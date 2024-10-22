-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 19, 2024 at 12:22 PM
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
-- Table structure for table `tblfaculty`
--

CREATE TABLE `tblfaculty` (
  `ID` int(11) NOT NULL,
  `FacultyID` varchar(100) NOT NULL,
  `Fname` varchar(100) NOT NULL,
  `Mname` varchar(100) NOT NULL,
  `Lname` varchar(100) NOT NULL,
  `Department` varchar(100) NOT NULL,
  `ContactNo` bigint(11) NOT NULL,
  `Email` varchar(120) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tblfaculty`
--

INSERT INTO `tblfaculty` (`ID`, `FacultyID`, `Fname`, `Mname`, `Lname`, `Department`, `ContactNo`, `Email`) VALUES
(1, 'FC9876543211', 'Stephanieh', 'Anne', 'Rodriguez', 'Science', 55598765431, 'stephanie.rodriguez@example.com'),
(2, 'FC876543210', 'Michael', 'James', 'Turner', 'Arts', 5558765432, 'michael.turner@example.com'),
(3, 'FC765432109', 'Jennifer', 'Lee', 'Peterson', 'Humanities', 5557654321, 'jennifer.peterson@example.com'),
(4, 'FC654321098', 'Christopher', 'Robert', 'Adams', 'Technology', 5556543210, 'chris.adams@example.com'),
(5, 'FC543210987', 'Emily', 'Grace', 'Martinez', 'Commerce', 5555432109, 'emily.martinez@example.com'),
(6, '4001', 'Alice', 'Marie', 'Johnson', 'Math', 5551234567, 'alice.johnson@example.com'),
(7, '4002', 'Bob', 'Richard', 'Smith', 'History', 5559876543, 'bob.smith@example.com'),
(8, '4003', 'Carol', 'Jane', 'Brown', 'English', 5555557890, 'carol.brown@example.com'),
(9, '4004', 'David', 'Samuel', 'Lee', 'Science', 5552223333, 'david.lee@example.com'),
(10, 'FC987654321', 'Stephanie', 'Anne', 'Rodriguez', 'Science', 5559876543, 'stephanie.rodriguez@example.com');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tblfaculty`
--
ALTER TABLE `tblfaculty`
  ADD PRIMARY KEY (`ID`),
  ADD UNIQUE KEY `FacultyID` (`FacultyID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tblfaculty`
--
ALTER TABLE `tblfaculty`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
