-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 21, 2024 at 10:17 AM
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
  `bookName` varchar(255) NOT NULL,
  `formatID` int(11) NOT NULL,
  `categoryID` int(11) NOT NULL,
  `isbnNumber` varchar(255) NOT NULL,
  `bookImage` varchar(255) NOT NULL,
  `count` int(11) NOT NULL,
  `bookDesc` text NOT NULL,
  `regDate` timestamp NOT NULL DEFAULT current_timestamp(),
  `updateDate` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `categorytbl`
--

CREATE TABLE `categorytbl` (
  `ID` int(11) NOT NULL,
  `categoryName` varchar(150) NOT NULL,
  `status` int(1) NOT NULL,
  `createDate` timestamp NOT NULL DEFAULT current_timestamp(),
  `updateDate` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `formattbl`
--

CREATE TABLE `formattbl` (
  `ID` int(11) NOT NULL,
  `formatName` varchar(150) NOT NULL,
  `status` int(1) NOT NULL,
  `createDate` timestamp NOT NULL DEFAULT current_timestamp(),
  `updateDate` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
(3, 7, 'Admin1', '25ac9880ebb92b6f71f3e44b5d782b4c', 0, '2024-09-21 14:56:31'),
(4, 8, 'Admin1', 'f85757e49fe26f270af54f83ea3244ed', 0, '2024-09-21 15:56:39');

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
(1, '2010', 'Olivia', 'Grace', 'Martinez', 'Humanities', '11', 555, 'olivia.martinez@example.com', '567 Willow Lane, Suburbia, State 56789'),
(2, '2002', 'Ethan', 'Michael', 'Adams', 'Technology', '10', 555, 'ethan.adams@example.com', '789 Oak Street, Urbanville, State 78901'),
(3, '2003', 'Sophia', 'Elizabeth', 'Collins', 'Arts', '12', 555, 'sophia.collins@example.com', '123 Maple Avenue, Metropolis, State 12345'),
(4, '2004', 'Liam', 'Alexander', 'Turner', 'Science', '9', 555, 'liam.turner@example.com', '456 Elm Road, Countryside, State 45678'),
(5, '2005', 'Ava', 'Rose', 'Peterson', 'Commerce', '10', 555, 'ava.peterson@example.com', '789 Pine Court, Townsville, State 78910'),
(7, 'A12321232', 'Peter', 'Buskin', 'Parker', 'Engineering', '31', 9892184319, 'PeterMan@Oscorp.gg', 'New York'),
(8, 'A12321233', 'John', 'Justin', 'Joven', 'Arts', '41', 9892184329, 'JustIN@news.corp', 'Manila'),
(9, 'A12321234', 'Wade', 'Winston', 'Willer', 'Education', '21', 9892134329, 'Deadpool@Marver.cc', 'There'),
(10, 'A12321235', 'Wade', 'Collins', 'Baster', 'Accounting', '11', 9992184319, 'WCB@Money.org', 'Hehe'),
(11, '2006', 'Kenneth Daniel', 'Estrada', 'Deang', 'BSIS', '41', 9387199430, 'Email@email.com', 'House'),
(12, '2007', 'aa', 'bb', 'cc', 'dd', 'ee', 55, 'e@e.e', 'e');

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
(3, 'nestor', 'cantillas', 'osmena', 'kendeangsec@gmail.com', 5485165174, 'Lib', '739b9c22938d83c461bfc2d973e8ec6c', 'Librarian', '2024-09-21 04:25:21', '2024-09-20 11:48:12', 0, 1),
(4, 'Kenneth', 'Estrada', 'Deang', 'Kennethdaniel70@gmail.com', 9387199430, 'Admin', 'e64b78fc3bc91bcbc7dc232ba8ec59e0', 'Admin', '2024-09-21 03:24:03', '2024-09-20 13:14:00', 1, 0),
(5, 'Ace', 'Malazarte', 'Tria', 'acetria28@gmail.com', 2719036135, 'LibrarianAce', '250d34cd0d3beaf396efa8127b1ae196', 'Librarian', '2024-09-21 06:51:56', '2024-09-21 06:41:54', 1, 0),
(9, 'Kenneth Daniel', 'Estrada', 'Deang', 'kennethdaniel70@gmail.com', 9387199430, 'Librarian-Guest', 'f600ebee2bd5878b732904c9e5a065db', 'Librarian', '2024-09-21 07:25:10', '2024-09-21 07:19:43', 1, 0),
(10, 'Anjella Marrie', 'Alvarez', 'Alarcon', 'anjellaalarcon18@gmail.com', 18395139714, 'Lib2', '73882ab1fa529d7273da0db6b49cc4f3', 'Librarian', '2024-09-21 07:36:27', '2024-09-21 07:30:46', 1, 0),
(11, 'Janette', 'Sinongco', 'Ambito', 'Jambito1906@gmail.com', 9878786851, 'Admin1', '867828277d8660bbb44ebbd860d2f020', 'Admin', '2024-09-21 08:00:37', '2024-09-21 07:58:38', 1, 0);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `booktbl`
--
ALTER TABLE `booktbl`
  ADD PRIMARY KEY (`ID`),
  ADD UNIQUE KEY `isbnNumber` (`isbnNumber`),
  ADD KEY `categoryID` (`categoryID`),
  ADD KEY `formatID` (`formatID`);

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
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `categorytbl`
--
ALTER TABLE `categorytbl`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `formattbl`
--
ALTER TABLE `formattbl`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;

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
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `tblstudent`
--
ALTER TABLE `tblstudent`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `tbluser`
--
ALTER TABLE `tbluser`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `booktbl`
--
ALTER TABLE `booktbl`
  ADD CONSTRAINT `booktbl_ibfk_1` FOREIGN KEY (`categoryID`) REFERENCES `categorytbl` (`ID`),
  ADD CONSTRAINT `booktbl_ibfk_2` FOREIGN KEY (`formatID`) REFERENCES `formattbl` (`ID`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
