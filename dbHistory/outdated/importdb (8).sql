-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 18, 2024 at 10:32 PM
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
-- Table structure for table `tblarchivestudent`
--

CREATE TABLE `tblarchivestudent` (
  `ID` int(11) NOT NULL,
  `StudentID` varchar(100) NOT NULL,
  `Fname` varchar(100) NOT NULL,
  `Mname` varchar(100) NOT NULL,
  `Lname` varchar(100) NOT NULL,
  `CourseStrand` varchar(120) NOT NULL,
  `YrLevel` varchar(120) NOT NULL,
  `ContactNo` bigint(11) DEFAULT NULL,
  `Email` varchar(120) NOT NULL,
  `hAddress` varchar(255) NOT NULL,
  `archived_date` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
(1, '2001aa', 'Olivia', 'Grace', 'Martinez', 'Humanities', '11', 555, 'olivia.martinez@example.com', '567 Willow Lane, Suburbia, State 56789'),
(2, '2002', 'Ethan', 'Michael', 'Adams', 'Technology', '10', 555, 'ethan.adams@example.com', '789 Oak Street, Urbanville, State 78901'),
(3, '2003', 'Sophia', 'Elizabeth', 'Collins', 'Arts', '12', 555, 'sophia.collins@example.com', '123 Maple Avenue, Metropolis, State 12345'),
(4, '2004', 'Liam', 'Alexander', 'Turner', 'Science', '9', 555, 'liam.turner@example.com', '456 Elm Road, Countryside, State 45678'),
(5, '2005', 'Ava', 'Rose', 'Peterson', 'Commerce', '10', 555, 'ava.peterson@example.com', '789 Pine Court, Townsville, State 78910'),
(6, 'A12321232', 'Peter', 'Buskin', 'Parker', 'Engineering', '31', 9892184319, 'PeterMan@Oscorp.gg', 'New York'),
(7, 'A12321233', 'John', 'Justin', 'Joven', 'Arts', '41', 9892184329, 'JustIN@news.corp', 'Manila'),
(8, 'A12321234', 'Wade', 'Winston', 'Willer', 'Education', '21', 9892134329, 'Deadpool@Marver.cc', 'There'),
(9, 'A12321235', 'Wade', 'Collins', 'Baster', 'Accounting', '11', 9992184319, 'WCB@Money.org', 'Hehe');

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
  `ContactNo` bigint(11) NOT NULL,
  `Username` varchar(100) NOT NULL,
  `Password` varchar(100) NOT NULL,
  `Role` varchar(100) NOT NULL,
  `updateDate` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp(),
  `creationDate` timestamp NOT NULL DEFAULT current_timestamp(),
  `Status` int(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbluser`
--

INSERT INTO `tbluser` (`ID`, `Fname`, `Mname`, `Lname`, `EmailAdd`, `ContactNo`, `Username`, `Password`, `Role`, `updateDate`, `creationDate`, `Status`) VALUES
(1, 'Kenneth', 'Estrada', 'Deang', 'Kennethdaniel70@gmail.com', 9387199430, 'Test123', 'b95d0afa777810bee35d346d53c8d132', 'Librarian', NULL, '2024-09-11 20:19:54', 1),
(2, 'Ken', 'Ken', 'Ken', 'Kenneth@ken.ken', 9129201920, 'Admin', 'cc748467c562909f9e8aa56152657f2a', 'Admin', NULL, '2024-09-11 21:41:13', 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tblarchivestudent`
--
ALTER TABLE `tblarchivestudent`
  ADD PRIMARY KEY (`ID`),
  ADD UNIQUE KEY `StudentID` (`StudentID`);

--
-- Indexes for table `tblfaculty`
--
ALTER TABLE `tblfaculty`
  ADD PRIMARY KEY (`ID`),
  ADD UNIQUE KEY `FacultyID` (`FacultyID`);

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
  ADD PRIMARY KEY (`ID`),
  ADD UNIQUE KEY `Username` (`Username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tblarchivestudent`
--
ALTER TABLE `tblarchivestudent`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tblfaculty`
--
ALTER TABLE `tblfaculty`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `tblstudent`
--
ALTER TABLE `tblstudent`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `tbluser`
--
ALTER TABLE `tbluser`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
