-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 20, 2024 at 01:00 PM
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
  `isIssued` int(1) NOT NULL,
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
(1, 'FC9876543211', 'Stephanieh', 'Anne', 'Rodriguez', 'Science', 55598765431, 'stephanie.rodriguez@example.com', '2024-09-19 19:36:20'),
(2, 'FC876543210', 'Michael', 'James', 'Turner', 'Arts', 5558765432, 'michael.turner@example.com', '2024-09-19 19:36:24'),
(3, 'FC765432109', 'Jennifer', 'Lee', 'Peterson', 'Humanities', 5557654321, 'jennifer.peterson@example.coma', '2024-09-19 22:10:35');

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
(4, 'FC654321098', 'Christopher', 'Robert', 'Adams', 'Technology', 5556543210, 'chris.adams@example.com'),
(5, 'FC543210987', 'Emily', 'Grace', 'Martinez', 'Commerce', 5555432109, 'emily.martinez@example.com'),
(6, '4001', 'Alice', 'Marie', 'Johnson', 'Math', 5551234567, 'alice.johnson@example.com'),
(7, '4002', 'Bob', 'Richard', 'Smith', 'History', 5559876543, 'bob.smith@example.com'),
(8, '4003', 'Carol', 'Jane', 'Brown', 'English', 5555557890, 'carol.brown@example.com'),
(9, '4004', 'David', 'Samuel', 'Lee', 'Science', 5552223333, 'david.lee@example.com'),
(10, 'FC987654321', 'Stephanie', 'Anne', 'Rodriguez', 'Science', 5559876543, 'stephanie.rodriguez@example.com'),
(11, 'sawaw', 'awawaw', 'waawa', 'waw', 'awaw', 323232, 'aweaw@wawe.fsf');

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
(1, 3, 'Admin1', 'd87d6172c7d1726f098098635c1128da', 0, '2024-09-20 01:30:10'),
(2, 4, 'Admin1', 'a0c558ca8bf86b35bb8a8ed794143b9c', 0, '2024-09-20 01:44:13'),
(3, 1, 'Librarian', '29ba3cf532be861cbfd890f4c1804ed2', 0, '2024-09-20 01:45:16'),
(4, 2, 'Admin', '7e81ce0e6a048e05fba810cfbba3307d', 0, '2024-09-20 01:45:19');

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
(26, '2003', 'Sophia', 'Elizabeth', 'Collins', 'Arts', '12', 555, 'sophia.collins@example.com', '123 Maple Avenue, Metropolis, State 12345'),
(27, '2004', 'Liam', 'Alexander', 'Turner', 'Science', '9', 555, 'liam.turner@example.com', '456 Elm Road, Countryside, State 45678'),
(28, '2005', 'Ava', 'Rose', 'Peterson', 'Commerce', '10', 555, 'ava.peterson@example.com', '789 Pine Court, Townsville, State 78910'),
(30, 'A12321233', 'John', 'Justin', 'Joven', 'Arts', '41', 9892184329, 'JustIN@news.corp', 'Manila'),
(31, 'A12321234', 'Wade', 'Winston', 'Willer', 'Education', '21', 9892134329, 'Deadpool@Marver.cc', 'There'),
(32, 'A12321235', 'Wade', 'Collins', 'Baster', 'Accounting', '11', 9992184319, 'WCB@Money.org', 'Hehe'),
(33, '2001', 'Olivia', 'Grace', 'Martinez', 'Humanities', '11', 555, 'olivia.martinez@example.com', '567 Willow Lane, Suburbia, State 56789'),
(34, '2002', 'Ethan', 'Michael', 'Adams', 'Technology', '10', 555, 'ethan.adams@example.com', '789 Oak Street, Urbanville, State 78901'),
(36, 'we32', '3213wq', 'wq32we', 'q322q3e', 'q232q', '3qa233', 13213131231, 'waeqe@eaew.AEAW', 'eqweq'),
(37, 'wqeeqeqw', 'eqweqwe', 'qweq', 'weqweq', 'qeqw', 'eqweqw', 32131231231, 'qweqw@eqw.Ea', 'eqwewq'),
(38, 'weqewqe', 'qwewqeqw', 'eqw', 'eqweqwe', 'qweqw', 'eqwewqe', 231312312, '3weqeweqwe@wq.fdad', '3123123'),
(40, 'A12321232', 'Peter', 'Buskin', 'Parker', 'Engineering', '31', 9892184319, 'PeterMan@Oscorp.gg', 'New York'),
(41, 'sasa', 'sasa', 'asasa', 'sas', 'asa', 'sasasa', 12231243541, 'eweqw@aeaweawr.sdf', 'eaewaea');

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
(5, 'Kenneth Daniel', 'Estrada', 'Deang', 'Kennethdaniel70@gmail.com', 14134135, 'MainAdmin', '7929eb3486cdc506e7d2648de6593846', 'Admin', '2024-09-19 17:46:57', '2024-09-19 17:45:00', 1, 0),
(6, 'Test', 'Test', 'Test', 'Kennethdaniel70@gmail.com', 31630164010, 'Librarian', '7929eb3486cdc506e7d2648de6593846', 'Librarian', '2024-09-19 17:53:24', '2024-09-19 17:48:07', 1, 0);

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
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `tblarchivestudent`
--
ALTER TABLE `tblarchivestudent`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tblfaculty`
--
ALTER TABLE `tblfaculty`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `tblrchiveusers`
--
ALTER TABLE `tblrchiveusers`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `tblstudent`
--
ALTER TABLE `tblstudent`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=42;

--
-- AUTO_INCREMENT for table `tbluser`
--
ALTER TABLE `tbluser`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

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
