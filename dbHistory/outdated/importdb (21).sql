-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 26, 2024 at 11:42 AM
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
-- Table structure for table `booktbl`
--

CREATE TABLE `booktbl` (
  `ID` int(11) NOT NULL,
  `bookCode` varchar(100) NOT NULL,
  `bookTitle` varchar(255) NOT NULL,
  `format` int(11) NOT NULL,
  `category` int(11) NOT NULL,
  `shelf` int(11) NOT NULL,
  `isbnNumber` varchar(255) NOT NULL,
  `count` int(11) NOT NULL,
  `accountNumber` int(11) NOT NULL,
  `regDate` timestamp NOT NULL DEFAULT current_timestamp(),
  `updateDate` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `booktbl`
--

INSERT INTO `booktbl` (`ID`, `bookCode`, `bookTitle`, `format`, `category`, `shelf`, `isbnNumber`, `count`, `accountNumber`, `regDate`, `updateDate`) VALUES
(1, 'Fil-123', 'Filipino 12', 1, 1, 1, '9182', 89, 18291, '2024-09-25 23:31:52', NULL);

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
(2, 'Technology', '2024-09-26 09:10:10', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `formattbl`
--

CREATE TABLE `formattbl` (
  `ID` int(11) NOT NULL,
  `formatName` varchar(150) NOT NULL,
  `createDate` timestamp NOT NULL DEFAULT current_timestamp(),
  `updateDate` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `formattbl`
--

INSERT INTO `formattbl` (`ID`, `formatName`, `createDate`, `updateDate`) VALUES
(1, 'Books', '2024-09-25 22:49:22', NULL),
(2, 'Magazine', '2024-09-26 09:12:13', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `shelftbl`
--

CREATE TABLE `shelftbl` (
  `ID` int(11) NOT NULL,
  `shelfLoc` varchar(100) NOT NULL,
  `createDate` timestamp NOT NULL DEFAULT current_timestamp(),
  `updateDate` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `shelftbl`
--

INSERT INTO `shelftbl` (`ID`, `shelfLoc`, `createDate`, `updateDate`) VALUES
(1, 'Shelf 1', '2024-09-25 22:49:32', NULL),
(2, 'Shelf 67', '2024-09-26 09:20:39', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `tblarchivefaculty`
--

CREATE TABLE `tblarchivefaculty` (
  `ID` int(11) NOT NULL,
  `FacultyID` varchar(100) NOT NULL,
  `Fname` varchar(100) NOT NULL,
  `Mname` varchar(100) NOT NULL,
  `Lname` varchar(100) NOT NULL,
  `Department` varchar(100) NOT NULL,
  `ContactNo` bigint(11) NOT NULL,
  `Email` varchar(120) NOT NULL,
  `ArchivedDate` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tblarchivefaculty`
--

INSERT INTO `tblarchivefaculty` (`ID`, `FacultyID`, `Fname`, `Mname`, `Lname`, `Department`, `ContactNo`, `Email`, `ArchivedDate`) VALUES
(1, '123456781', 'keni', 'neth', 'len', 'kay', 9653248652, 'jwu@gmail.com', '2024-09-20 19:43:56'),
(2, '4004', 'David', 'Samuel', 'Lee', 'Science', 5552223333, 'david.lee@example.com', '2024-09-20 19:44:00');

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

--
-- Dumping data for table `tblarchivestudent`
--

INSERT INTO `tblarchivestudent` (`ID`, `StudentID`, `Fname`, `Mname`, `Lname`, `CourseStrand`, `YrLevel`, `ContactNo`, `Email`, `hAddress`, `archived_date`) VALUES
(1, 'a21010451', 'juan', 'salsalani', 'dela cruz', 'anything', 'dont know', 11121548659, 'mwewmemwe@gmail.com', 'mamamopalo123', '2024-09-20 19:40:33');

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
(1, '4001', 'Alice', 'Marie', 'Johnson', 'Math', 5551234567, 'alice.johnson@example.com'),
(2, '4002', 'Bob', 'Richard', 'Smith', 'History', 5559876543, 'bob.smith@example.com'),
(3, '4003', 'Carol', 'Jane', 'Brown', 'English', 5555557890, 'carol.brown@example.com');

-- --------------------------------------------------------

--
-- Table structure for table `tblrchiveusers`
--

CREATE TABLE `tblrchiveusers` (
  `ID` int(11) NOT NULL,
  `UserID` int(11) NOT NULL,
  `Username` varchar(100) NOT NULL,
  `Password` varchar(255) NOT NULL,
  `RoleID` int(11) NOT NULL,
  `ArchivedDate` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tblrchiveusers`
--

INSERT INTO `tblrchiveusers` (`ID`, `UserID`, `Username`, `Password`, `RoleID`, `ArchivedDate`) VALUES
(1, 2, 'Admin1', '7e6c8d094f971a53a5496b19dcd4e3cb', 0, '2024-09-20 19:49:02'),
(2, 1, 'Admin', 'a3b83598971a0fe0723d0e9b81034f3f', 0, '2024-09-20 21:13:27'),
(3, 7, 'fpass', '32250170a0dca92d53ec9624f336ca24', 0, '2024-09-24 00:01:06');

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
(1, '2001', 'Olivia', 'Grace', 'Martinez', 'Humanities', '11', 555, 'olivia.martinez@example.com', '567 Willow Lane, Suburbia, State 56789'),
(2, '2002', 'Ethan', 'Michael', 'Adams', 'Technology', '10', 555, 'ethan.adams@example.com', '789 Oak Street, Urbanville, State 78901'),
(3, '2003', 'Sophia', 'Elizabeth', 'Collins', 'Arts', '12', 555, 'sophia.collins@example.com', '123 Maple Avenue, Metropolis, State 12345'),
(4, '2004', 'Liam', 'Alexander', 'Turner', 'Science', '9', 555, 'liam.turner@example.com', '456 Elm Road, Countryside, State 45678'),
(5, '2005', 'Ava', 'Rose', 'Peterson', 'Commerce', '10', 555, 'ava.peterson@example.com', '789 Pine Court, Townsville, State 78910'),
(7, 'A12321232', 'Peter', 'Buskin', 'Parker', 'Engineering', '31', 9892184319, 'PeterMan@Oscorp.gg', 'New York'),
(8, 'A12321233', 'John', 'Justin', 'Joven', 'Arts', '41', 9892184329, 'JustIN@news.corp', 'Manila'),
(9, 'A12321234', 'Wade', 'Winston', 'Willer', 'Education', '21', 9892134329, 'Deadpool@Marver.cc', 'There'),
(10, 'A12321235', 'Wade', 'Collins', 'Baster', 'Accounting', '11', 9992184319, 'WCB@Money.org', 'Hehe');

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
  `Status` int(1) DEFAULT NULL,
  `force_password_change` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbluser`
--

INSERT INTO `tbluser` (`ID`, `Fname`, `Mname`, `Lname`, `EmailAdd`, `ContactNo`, `Username`, `Password`, `Role`, `updateDate`, `creationDate`, `Status`, `force_password_change`) VALUES
(3, 'nestor', 'cantillas', 'osmena', 'kendeangsec@gmail.com', 5485165174, 'Lib', '739b9c22938d83c461bfc2d973e8ec6c', 'Librarian', '2024-09-22 08:10:20', '2024-09-20 11:48:12', 1, 1),
(4, 'Kenneth', 'Estrada', 'Deang', 'Kennethdaniel70@gmail.com', 9387199430, 'Admin', '5281f8b5b213c30967212aac742b2673', 'Admin', NULL, '2024-09-20 13:14:00', 1, 1),
(5, 'Kenneth Daniel', 'Estrada', 'Deang', 'Kennethdaniel70@gmail.com', 93491269456, 'Admin1', 'e64b78fc3bc91bcbc7dc232ba8ec59e0', 'Admin', '2024-09-22 07:21:23', '2024-09-22 07:20:12', 1, 0),
(6, 'Kenneth', 'Estrada', 'Deang', 'Kennethdaniel70@gmail.com', 9387199430, 'Librarian-Test', 'f600ebee2bd5878b732904c9e5a065db', 'Librarian', '2024-09-23 15:58:06', '2024-09-23 14:37:39', 1, 0),
(8, 'a', 'a', 'a', 'Kennethdaniel70@gmail.com', 223423424, 'a', '8a8bb7cd343aa2ad99b7d762030857a2', 'Librarian', '2024-09-23 16:02:04', '2024-09-23 16:01:20', 1, 0);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `booktbl`
--
ALTER TABLE `booktbl`
  ADD PRIMARY KEY (`ID`),
  ADD UNIQUE KEY `isbnNumber` (`isbnNumber`),
  ADD UNIQUE KEY `bookCode` (`bookCode`),
  ADD KEY `categoryID` (`category`),
  ADD KEY `formatID` (`format`),
  ADD KEY `shelf` (`shelf`);

--
-- Indexes for table `categorytbl`
--
ALTER TABLE `categorytbl`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `formattbl`
--
ALTER TABLE `formattbl`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `shelftbl`
--
ALTER TABLE `shelftbl`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `tblarchivefaculty`
--
ALTER TABLE `tblarchivefaculty`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `tblarchivestudent`
--
ALTER TABLE `tblarchivestudent`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `tblfaculty`
--
ALTER TABLE `tblfaculty`
  ADD PRIMARY KEY (`ID`),
  ADD UNIQUE KEY `FacultyID` (`FacultyID`);

--
-- Indexes for table `tblrchiveusers`
--
ALTER TABLE `tblrchiveusers`
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
  ADD PRIMARY KEY (`ID`),
  ADD UNIQUE KEY `Username` (`Username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `booktbl`
--
ALTER TABLE `booktbl`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `categorytbl`
--
ALTER TABLE `categorytbl`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `formattbl`
--
ALTER TABLE `formattbl`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `shelftbl`
--
ALTER TABLE `shelftbl`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `tblarchivefaculty`
--
ALTER TABLE `tblarchivefaculty`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `tblarchivestudent`
--
ALTER TABLE `tblarchivestudent`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `tblfaculty`
--
ALTER TABLE `tblfaculty`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `tblrchiveusers`
--
ALTER TABLE `tblrchiveusers`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `tblstudent`
--
ALTER TABLE `tblstudent`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `tbluser`
--
ALTER TABLE `tbluser`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `booktbl`
--
ALTER TABLE `booktbl`
  ADD CONSTRAINT `booktbl_ibfk_1` FOREIGN KEY (`category`) REFERENCES `categorytbl` (`ID`),
  ADD CONSTRAINT `booktbl_ibfk_2` FOREIGN KEY (`format`) REFERENCES `formattbl` (`ID`),
  ADD CONSTRAINT `booktbl_ibfk_3` FOREIGN KEY (`shelf`) REFERENCES `shelftbl` (`ID`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
