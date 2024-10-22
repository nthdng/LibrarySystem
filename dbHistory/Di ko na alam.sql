-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 18, 2024 at 09:01 PM
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
-- Table structure for table `archivebookstbl`
--

CREATE TABLE `archivebookstbl` (
  `ID` int(11) NOT NULL,
  `bookCode` varchar(100) NOT NULL,
  `bookTitle` varchar(255) NOT NULL,
  `format` int(11) NOT NULL,
  `category` int(11) NOT NULL,
  `shelf` int(11) NOT NULL,
  `isbnNumber` varchar(255) NOT NULL,
  `count` int(11) NOT NULL,
  `accountNumber` int(11) NOT NULL,
  `regDate` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `ArchivedDate` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `archivebookstbl`
--

INSERT INTO `archivebookstbl` (`ID`, `bookCode`, `bookTitle`, `format`, `category`, `shelf`, `isbnNumber`, `count`, `accountNumber`, `regDate`, `ArchivedDate`) VALUES
(2, 'Fil-1232', 'Filipino 12', 4, 4, 4, '', 89, 18291, '2024-10-03 17:03:12', '2024-10-03 17:03:12'),
(3, 'Sci-1012', 'Quantum Physics', 5, 5, 5, '', 50, 10001, '2024-10-03 17:04:50', '2024-10-03 17:04:50'),
(4, 'Lit-2021', 'Shakespeare’s Works', 6, 6, 6, '', 120, 10002, '2024-10-03 17:06:34', '2024-10-03 17:06:34'),
(5, 'Tech-3032', 'AI Revolution', 7, 2, 7, '', 200, 10003, '2024-10-03 17:08:31', '2024-10-03 17:08:31'),
(6, 'Hist-4041', 'World War II', 5, 7, 8, '', 80, 10004, '2024-10-03 17:09:20', '2024-10-03 17:09:20'),
(7, 'Art-5051', 'Modern Art', 6, 8, 9, '', 60, 10005, '2024-10-03 17:30:28', '2024-10-03 17:30:28'),
(8, 'Fil-123', 'Filipino 12', 4, 4, 4, '', 89, 182911, '2024-10-03 17:32:37', '2024-10-03 17:32:37'),
(9, 'Sci-1011', 'Quantum Physics', 5, 5, 5, '', 50, 10001, '2024-10-03 17:35:10', '2024-10-03 17:35:10'),
(10, 'Lit-2021', 'Shakespeare’s Works', 6, 6, 6, '', 120, 10002, '2024-10-03 17:37:52', '2024-10-03 17:37:52'),
(11, 'Tech-303?', 'AI Revolution', 7, 2, 7, '', 200, 10003, '2024-10-03 17:38:08', '2024-10-03 17:38:08'),
(12, 'Fil-123', 'Filipino 12', 1, 4, 4, '', 89, 18291, '2024-10-03 17:43:55', '2024-10-03 17:43:55'),
(13, 'Sci-1011', 'Quantum Physics', 5, 5, 5, '', 50, 10001, '2024-10-03 17:51:06', '2024-10-03 17:51:06'),
(14, 'Lit-20211', 'Shakespeare’s Works', 6, 1, 6, '5678', 120, 10002, '2024-10-03 17:53:11', '2024-10-03 17:53:11'),
(15, 'Hist-404', 'World War II', 5, 7, 8, '1121', 80, 10004, '2024-10-03 17:53:14', '2024-10-03 17:53:14'),
(16, 'Art-505', 'Modern Art', 6, 8, 9, '3141', 60, 10005, '2024-10-03 17:53:16', '2024-10-03 17:53:16');

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
(17, 'Fil-123', 'Filipino 12', 4, 4, 4, '9182', 89, 18291, '2024-10-03 17:55:30', NULL),
(18, 'Sci-101', 'Quantum Physics', 5, 5, 5, '978-3-16-148410-0', 50, 10001, '2024-10-03 17:55:30', NULL),
(19, 'Lit-202', 'Shakespeare’s Works', 6, 6, 6, '978-0-14-043908-3', 120, 10002, '2024-10-03 17:55:30', NULL),
(20, 'Tech-303', 'AI Revolution', 7, 2, 7, '978-1-59327-584-6', 200, 10003, '2024-10-03 17:55:30', NULL),
(21, 'Hist-404', 'World War II', 5, 7, 8, '978-0-19-280670-3', 80, 10004, '2024-10-03 17:55:30', NULL),
(22, 'Art-505', 'Modern Art', 6, 8, 9, '978-0-500-20483-1', 60, 10005, '2024-10-03 17:55:30', NULL),
(23, 'Med-606', 'Human Anatomy', 5, 9, 10, '978-0-323-35378-6', 40, 10006, '2024-10-03 17:55:30', NULL),
(24, 'Eng-707', 'English Grammar', 6, 10, 11, '978-0-19-457980-3', 90, 10007, '2024-10-03 17:55:30', NULL),
(25, 'Math-808', 'Calculus', 5, 11, 12, '978-0-321-71674-8', 70, 10008, '2024-10-03 17:55:30', NULL),
(26, 'Bio-909', 'Genetics', 7, 12, 13, '978-0-13-404779-7', 110, 10009, '2024-10-03 17:55:30', NULL),
(27, 'Chem-010', 'Organic Chemistry', 6, 13, 9, '978-0-13-404228-0', 30, 10010, '2024-10-03 17:55:30', '2024-10-03 18:05:13'),
(28, 'Phil-111', 'Philosophy 101', 5, 14, 15, '978-0-19-285421-6', 85, 10011, '2024-10-03 17:55:30', NULL),
(29, 'Geo-212', 'Earth Science', 6, 15, 16, '978-0-13-467971-7', 95, 10012, '2024-10-03 17:55:30', NULL),
(30, 'Hist-313', 'Ancient Civilizations', 7, 7, 17, '978-0-19-280458-7', 55, 10013, '2024-10-03 17:55:30', NULL),
(31, 'Lit-414', 'Modern Poetry', 5, 6, 18, '978-0-19-953556-9', 65, 10014, '2024-10-03 17:55:30', NULL),
(32, 'Tech-515', 'Cybersecurity', 6, 2, 19, '978-1-59327-487-0', 150, 10015, '2024-10-03 17:55:30', NULL),
(33, 'Sci-102', 'Fundamentals of Physics', 5, 5, 1, '978-0-471-32000-5', 6, 40001, '2024-10-03 18:10:43', NULL),
(34, 'Lit-203', 'The Great Gatsby', 6, 6, 20, '978-0-7432-7356-5', 4, 40002, '2024-10-03 18:10:43', NULL),
(35, 'Tech-304', 'Computer Networking', 5, 2, 21, '978-0-13-212695-3', 9, 40003, '2024-10-03 18:10:43', NULL),
(36, 'Hist-405', 'The History of the Philippines', 6, 7, 22, '978-0-19-515741-3', 7, 40004, '2024-10-03 18:10:43', NULL),
(37, 'Art-506', 'Art Through the Ages', 5, 8, 23, '978-0-495-57355-5', 5, 40005, '2024-10-03 18:10:43', NULL),
(38, 'Med-607', 'Essentials of Medical Pharmacology', 5, 9, 1, '978-0-07-160567-0', 8, 40006, '2024-10-03 18:10:43', NULL),
(39, 'Eng-708', 'Academic Writing', 6, 10, 20, '978-0-19-933399-7', 3, 40007, '2024-10-03 18:10:43', NULL),
(40, 'Math-809', 'Linear Algebra', 5, 11, 21, '978-0-13-600926-9', 2, 40008, '2024-10-03 18:10:43', NULL),
(41, 'Bio-910', 'Molecular Biology of the Cell', 5, 12, 22, '978-0-8153-4106-2', 10, 40009, '2024-10-03 18:10:43', NULL),
(42, 'Chem-011', 'Inorganic Chemistry', 6, 13, 23, '978-0-19-926463-5', 1, 40010, '2024-10-03 18:10:43', NULL),
(43, 'Phil-112', 'Meditations', 5, 14, 1, '978-0-14-044933-4', 7, 40011, '2024-10-03 18:10:43', NULL),
(44, 'Geo-213', 'Physical Geology', 6, 15, 20, '978-0-13-444662-2', 6, 40012, '2024-10-03 18:10:43', NULL),
(45, 'Hist-314', 'The Silk Roads', 5, 7, 21, '978-1-4088-3997-3', 5, 40013, '2024-10-03 18:10:43', NULL),
(46, 'Tech-516', 'The Pragmatic Programmer', 6, 2, 23, '978-0-13-595705-9', 8, 40015, '2024-10-03 18:10:43', NULL),
(47, 'Tech-321', 'The Pragmatic Programmer', 8, 16, 24, '575478785', 8, 40017, '2024-10-17 23:01:03', NULL),
(48, 'Geo-214', 'Meteorology 101', 9, 17, 25, '978-0-321-73713-0', 40, 10020, '2024-10-17 23:01:03', NULL),
(49, 'Mus-101', 'The Symphony', 10, 18, 26, '978-0-385-72143-6', 25, 10021, '2024-10-17 23:01:03', NULL),
(50, 'Lit-415', 'Gothic Tales', 9, 19, 27, '978-1-5040-3122-4', 55, 10022, '2024-10-17 23:01:03', NULL),
(51, 'Bot-123', 'Flora of Earth', 5, 20, 28, '978-0-691-15656-2', 18, 10023, '2024-10-17 23:01:03', NULL),
(52, 'Soc-202', 'Social Dynamics', 9, 21, 29, '978-1-118-01719-6', 65, 10024, '2024-10-17 23:01:03', NULL);

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
(2, 'Technology', '2024-09-26 09:10:10', NULL),
(4, 'Health', '2024-09-26 12:41:59', NULL),
(5, 'Science', '2024-10-03 16:53:36', NULL),
(6, 'Literature', '2024-10-03 16:53:36', NULL),
(7, 'History', '2024-10-03 16:53:36', NULL),
(8, 'Arts', '2024-10-03 16:53:36', NULL),
(9, 'Medicine', '2024-10-03 17:55:30', NULL),
(10, 'Education', '2024-10-03 17:55:30', NULL),
(11, 'Mathematics', '2024-10-03 17:55:30', NULL),
(12, 'Biology', '2024-10-03 17:55:30', NULL),
(13, 'Chemistry', '2024-10-03 17:55:30', NULL),
(14, 'Philosophy', '2024-10-03 17:55:30', NULL),
(15, 'Geology', '2024-10-03 17:55:30', NULL),
(16, 'ctTest', '2024-10-17 23:01:03', NULL),
(17, 'Environmental Science', '2024-10-17 23:01:03', NULL),
(18, 'Music', '2024-10-17 23:01:03', NULL),
(19, 'Horror', '2024-10-17 23:01:03', NULL),
(20, 'Botany', '2024-10-17 23:01:03', NULL),
(21, 'Sociology', '2024-10-17 23:01:03', NULL);

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
(2, 'Magazine', '2024-09-26 09:12:13', NULL),
(4, 'Thesis', '2024-09-26 12:41:59', NULL),
(5, 'Hardcover', '2024-10-03 16:53:36', NULL),
(6, 'Paperback', '2024-10-03 16:53:36', NULL),
(7, 'eBook', '2024-10-03 16:53:36', NULL),
(8, 'FormatTest', '2024-10-17 23:01:03', NULL),
(9, 'Digital', '2024-10-17 23:01:03', NULL),
(10, 'CD', '2024-10-17 23:01:03', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `othersarchivedtbl`
--

CREATE TABLE `othersarchivedtbl` (
  `ID` int(11) NOT NULL,
  `name` varchar(150) NOT NULL,
  `archivedDate` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `othersarchivedtbl`
--

INSERT INTO `othersarchivedtbl` (`ID`, `name`, `archivedDate`) VALUES
(1, '1', '2024-10-04 06:31:01'),
(2, '1', '2024-10-04 18:47:57'),
(3, 'Shelf 40', '2024-10-04 18:48:26');

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
(2, 'Shelf 67', '2024-09-26 09:20:39', NULL),
(4, 'Shelf 27', '2024-09-26 12:41:59', NULL),
(5, 'Shelf 10', '2024-10-03 16:53:36', NULL),
(6, 'Shelf 15', '2024-10-03 16:53:36', NULL),
(7, 'Shelf 20', '2024-10-03 16:53:36', NULL),
(8, 'Shelf 25', '2024-10-03 16:53:36', NULL),
(9, 'Shelf 30', '2024-10-03 16:53:36', NULL),
(10, 'Shelf 35', '2024-10-03 17:55:30', NULL),
(11, 'Shelf 40', '2024-10-03 17:55:30', NULL),
(12, 'Shelf 45', '2024-10-03 17:55:30', NULL),
(13, 'Shelf 50', '2024-10-03 17:55:30', NULL),
(14, 'Shelf 55', '2024-10-03 17:55:30', NULL),
(15, 'Shelf 60', '2024-10-03 17:55:30', NULL),
(16, 'Shelf 65', '2024-10-03 17:55:30', NULL),
(17, 'Shelf 70', '2024-10-03 17:55:30', NULL),
(18, 'Shelf 75', '2024-10-03 17:55:30', NULL),
(19, 'Shelf 80', '2024-10-03 17:55:30', NULL),
(20, 'Shelf 2', '2024-10-03 18:10:43', NULL),
(21, 'Shelf 3', '2024-10-03 18:10:43', NULL),
(22, 'Shelf 4', '2024-10-03 18:10:43', NULL),
(23, 'Shelf 5', '2024-10-03 18:10:43', NULL),
(24, 'Shelf sf', '2024-10-17 23:01:03', NULL),
(25, 'Shelf 90', '2024-10-17 23:01:03', NULL),
(26, 'Shelf 85', '2024-10-17 23:01:03', NULL),
(27, 'Shelf 95', '2024-10-17 23:01:03', NULL),
(28, 'Shelf 87', '2024-10-17 23:01:03', NULL),
(29, 'Shelf 78', '2024-10-17 23:01:03', NULL);

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
(1, '2001', 'Olivia', 'Grace', 'Martinez', 'Humanities', '11', 555, 'ganonpuro@gmail.com', '567 Willow Lane, Suburbia, State 56789', '2024-10-18 21:32:51');

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
(2, '2001', 'Olivia', 'Grace', 'Martinez', 'Humanities', '11', 555, 'ganonpuro@gmail.com', '567 Willow Lane, Suburbia, State 56789');

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
-- Indexes for dumped tables
--

--
-- Indexes for table `archivebookstbl`
--
ALTER TABLE `archivebookstbl`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `categoryID` (`category`),
  ADD KEY `formatID` (`format`),
  ADD KEY `shelf` (`shelf`);

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
-- Indexes for table `othersarchivedtbl`
--
ALTER TABLE `othersarchivedtbl`
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
-- AUTO_INCREMENT for table `archivebookstbl`
--
ALTER TABLE `archivebookstbl`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `booktbl`
--
ALTER TABLE `booktbl`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=53;

--
-- AUTO_INCREMENT for table `categorytbl`
--
ALTER TABLE `categorytbl`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `formattbl`
--
ALTER TABLE `formattbl`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `othersarchivedtbl`
--
ALTER TABLE `othersarchivedtbl`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `shelftbl`
--
ALTER TABLE `shelftbl`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT for table `tblarchivefaculty`
--
ALTER TABLE `tblarchivefaculty`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tblarchivestudent`
--
ALTER TABLE `tblarchivestudent`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `tblfaculty`
--
ALTER TABLE `tblfaculty`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tblrchiveusers`
--
ALTER TABLE `tblrchiveusers`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tblstudent`
--
ALTER TABLE `tblstudent`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `tbluser`
--
ALTER TABLE `tbluser`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `archivebookstbl`
--
ALTER TABLE `archivebookstbl`
  ADD CONSTRAINT `archiveBookstbl_ibfk_1` FOREIGN KEY (`category`) REFERENCES `categorytbl` (`ID`),
  ADD CONSTRAINT `archiveBookstbl_ibfk_2` FOREIGN KEY (`format`) REFERENCES `formattbl` (`ID`),
  ADD CONSTRAINT `archiveBookstbl_ibfk_3` FOREIGN KEY (`shelf`) REFERENCES `shelftbl` (`ID`);

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
