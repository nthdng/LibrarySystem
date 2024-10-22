-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 10, 2024 at 10:22 PM
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

-- --------------------------------------------------------

--
-- Table structure for table `tblstudent`
--

CREATE TABLE `tblstudent` (
  `ID` int(11) NOT NULL,
  `StudentID` varchar(100) NOT NULL,
  `Fname` varchar(100) NOT NULL,
  `Mname` varchar(100) NOT NULL,
  `Lname` varchar(100) NOT NULL,
  `CourseStrand` varchar(120) NOT NULL,
  `YrLevel` varchar(120) NOT NULL,
  `ContactNo` bigint(11) DEFAULT NULL,
  `Email` varchar(120) NOT NULL,
  `hAddress` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tblstudent`
--

INSERT INTO `tblstudent` (`ID`, `StudentID`, `Fname`, `Mname`, `Lname`, `CourseStrand`, `YrLevel`, `ContactNo`, `Email`, `hAddress`) VALUES
(1, 'ST1234', 'Kenneth Daniel', 'Estrada', 'Deang', 'BSIS', '41', 9387199430, 'Kennethkennethkenneth@kenneth.kenneth', '180 Mabolo Street'),
(2, 'A12321232', 'Peter', 'Buskin', 'Parker', 'Engineering', '31', 9892184319, 'PeterMan@Oscorp.gg', 'New York'),
(3, 'A12321233', 'John', 'Justin', 'Joven', 'Arts', '41', 9892184329, 'JustIN@news.corp', 'Manila'),
(4, 'A12321234', 'Wade', 'Winston', 'Willer', 'Education', '21', 9892134329, 'Deadpool@Marver.cc', 'There'),
(5, 'A12321235', 'Wade', 'Collins', 'Baster', 'Accounting', '11', 9992184319, 'WCB@Money.org', 'Hehe'),
(6, '2001', 'Olivia', 'Grace', 'Martinez', 'Humanities', '11', 555, 'olivia.martinez@example.com', '567 Willow Lane, Suburbia, State 56789'),
(7, '2002', 'Ethan', 'Michael', 'Adams', 'Technology', '10', 555, 'ethan.adams@example.com', '789 Oak Street, Urbanville, State 78901'),
(8, '2003', 'Sophia', 'Elizabeth', 'Collins', 'Arts', '12', 555, 'sophia.collins@example.com', '123 Maple Avenue, Metropolis, State 12345'),
(9, '2004', 'Liam', 'Alexander', 'Turner', 'Science', '9', 555, 'liam.turner@example.com', '456 Elm Road, Countryside, State 45678'),
(10, '2005', 'Ava', 'Rose', 'Peterson', 'Commerce', '10', 555, 'ava.peterson@example.com', '789 Pine Court, Townsville, State 78910');

-- --------------------------------------------------------

--
-- Table structure for table `tbluser`
--

CREATE TABLE `tbluser` (
  `ID` int(11) NOT NULL,
  `Fname` varchar(100) NOT NULL,
  `Mname` varchar(100) NOT NULL,
  `Lname` varchar(100) NOT NULL,
  `EmailAdd` varchar(120) NOT NULL,
  `Username` varchar(100) NOT NULL,
  `Password` varchar(100) NOT NULL,
  `Role` varchar(100) NOT NULL,
  `updateDate` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp(),
  `creationDate` timestamp NOT NULL DEFAULT current_timestamp(),
  `Status` int(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tblfaculty`
--
ALTER TABLE `tblfaculty`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `tblstudent`
--
ALTER TABLE `tblstudent`
  ADD PRIMARY KEY (`ID`),
  ADD UNIQUE KEY `StudentID` (`StudentID`);

--
-- Indexes for table `tbluser`
--
ALTER TABLE `tbluser`
  ADD PRIMARY KEY (`ID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tblfaculty`
--
ALTER TABLE `tblfaculty`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tblstudent`
--
ALTER TABLE `tblstudent`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `tbluser`
--
ALTER TABLE `tbluser`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
