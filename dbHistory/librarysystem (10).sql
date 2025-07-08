-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 21, 2024 at 09:15 PM
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
-- Table structure for table `booktbl`
--

CREATE TABLE `booktbl` (
  `bookID` int(11) NOT NULL,
  `bookCode` varchar(100) NOT NULL,
  `bookTitle` varchar(255) NOT NULL,
  `format` int(11) NOT NULL,
  `category` int(11) NOT NULL,
  `shelf` int(11) NOT NULL,
  `isbnNumber` varchar(255) NOT NULL,
  `count` int(11) NOT NULL,
  `accountNumber` int(11) NOT NULL,
  `regDate` timestamp NOT NULL DEFAULT current_timestamp(),
  `updateDate` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp(),
  `archBook` enum('Archived','Existing') NOT NULL DEFAULT 'Existing'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `booktbl`
--

INSERT INTO `booktbl` (`bookID`, `bookCode`, `bookTitle`, `format`, `category`, `shelf`, `isbnNumber`, `count`, `accountNumber`, `regDate`, `updateDate`, `archBook`) VALUES
(1, 'Fil-123', 'Filipino 12', 1, 1, 1, '9182', 89, 18291, '2024-11-08 15:29:14', '2024-11-08 16:40:36', ''),
(2, 'Sci-101', 'Quantum Physics', 2, 2, 2, '978-3-16-148410-0', 50, 10001, '2024-11-08 16:39:49', '2024-11-08 17:43:35', 'Archived'),
(3, 'Lit-202', 'Shakespeare’s Works', 3, 3, 3, '978-0-14-043908-3', 116, 10002, '2024-11-08 16:39:49', '2024-11-21 16:53:25', 'Existing'),
(4, 'Tech-303', 'AI Revolution', 4, 4, 4, '978-1-59327-584-6', 198, 10003, '2024-11-08 16:39:49', '2024-11-15 16:45:00', 'Existing'),
(5, 'Hist-404', 'World War II', 2, 5, 5, '978-0-19-280670-3', 80, 10004, '2024-11-08 16:39:49', '2024-11-15 16:45:11', 'Existing'),
(6, 'Art-505', 'Modern Art', 3, 6, 6, '978-0-500-20483-1', 60, 10005, '2024-11-08 16:39:49', NULL, 'Existing'),
(7, 'Med-606', 'Human Anatomy', 2, 7, 7, '978-0-323-35378-6', 40, 10006, '2024-11-08 16:39:49', NULL, 'Existing'),
(8, 'Eng-707', 'English Grammar', 3, 8, 8, '978-0-19-457980-3', 90, 10007, '2024-11-08 16:39:49', NULL, 'Existing'),
(9, 'Math-808', 'Calculus', 2, 9, 9, '978-0-321-71674-8', 70, 10008, '2024-11-08 16:39:49', NULL, 'Existing'),
(10, 'Bio-909', 'Genetics', 4, 10, 10, '978-0-13-404779-7', 110, 10009, '2024-11-08 16:39:49', NULL, 'Existing'),
(11, 'Chem-010', 'Organic Chemistry', 3, 11, 11, '978-0-13-404228-0', 29, 10010, '2024-11-08 16:39:49', '2024-11-21 16:53:30', 'Existing'),
(12, 'Phil-111', 'Philosophy 101', 2, 12, 12, '978-0-19-285421-6', 85, 10011, '2024-11-08 16:39:49', NULL, 'Existing'),
(13, 'Geo-212', 'Earth Science', 3, 13, 13, '978-0-13-467971-7', 95, 10012, '2024-11-08 16:39:49', NULL, 'Existing'),
(14, 'Hist-313', 'Ancient Civilizations', 4, 5, 14, '978-0-19-280458-7', 55, 10013, '2024-11-08 16:39:49', NULL, 'Existing'),
(15, 'Lit-414', 'Modern Poetry', 2, 3, 15, '978-0-19-953556-9', 64, 10014, '2024-11-08 16:39:49', '2024-11-15 16:45:11', 'Existing'),
(16, 'Tech-515', 'Cybersecurity', 3, 4, 16, '978-1-59327-487-0', 150, 10015, '2024-11-08 16:39:49', NULL, 'Existing'),
(17, 'Sci-102', 'Fundamentals of Physics', 2, 2, 17, '978-0-471-32000-5', 6, 40001, '2024-11-08 16:39:49', NULL, 'Existing'),
(18, 'Lit-203', 'The Great Gatsby', 3, 3, 18, '978-0-7432-7356-5', 4, 40002, '2024-11-08 16:39:49', NULL, 'Existing'),
(19, 'Tech-304', 'Computer Networking', 2, 4, 19, '978-0-13-212695-3', 9, 40003, '2024-11-08 16:39:49', NULL, 'Existing'),
(20, 'Hist-405', 'The History of the Philippines', 3, 5, 20, '978-0-19-515741-3', 7, 40004, '2024-11-08 16:39:49', NULL, 'Existing'),
(21, 'Art-506', 'Art Through the Ages', 2, 6, 21, '978-0-495-57355-5', 5, 40005, '2024-11-08 16:39:49', NULL, 'Existing'),
(22, 'Med-607', 'Essentials of Medical Pharmacology', 2, 7, 17, '978-0-07-160567-0', 8, 40006, '2024-11-08 16:39:49', NULL, 'Existing'),
(23, 'Eng-708', 'Academic Writing', 3, 8, 18, '978-0-19-933399-7', 3, 40007, '2024-11-08 16:39:49', NULL, 'Existing'),
(24, 'Math-809', 'Linear Algebra', 2, 9, 19, '978-0-13-600926-9', 2, 40008, '2024-11-08 16:39:49', NULL, 'Existing'),
(25, 'Bio-910', 'Molecular Biology of the Cell', 2, 10, 20, '978-0-8153-4106-2', 10, 40009, '2024-11-08 16:39:49', NULL, 'Existing'),
(26, 'Chem-011', 'Inorganic Chemistry', 3, 11, 21, '978-0-19-926463-5', 1, 40010, '2024-11-08 16:39:49', NULL, 'Existing'),
(27, 'Phil-112', 'Meditations', 2, 12, 17, '978-0-14-044933-4', 6, 40011, '2024-11-08 16:39:49', '2024-11-15 16:44:11', 'Existing'),
(28, 'Geo-213', 'Physical Geology', 3, 13, 18, '978-0-13-444662-2', 6, 40012, '2024-11-08 16:39:49', NULL, 'Existing'),
(29, 'Hist-314', 'The Silk Roads', 2, 5, 19, '978-1-4088-3997-3', 5, 40013, '2024-11-08 16:39:49', NULL, 'Existing'),
(30, 'Tech-516', 'The Pragmatic Programmer', 3, 4, 21, '978-0-13-595705-9', 8, 40015, '2024-11-08 16:39:49', NULL, 'Existing'),
(31, 'Tech-321', 'The Pragmatic Programmer', 5, 14, 22, '575478785', 8, 40017, '2024-11-08 16:39:49', NULL, 'Existing');

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
(1, 3, 'DefaultAdmin', NULL, '2002', '2024-11-21 20:04:32', '2024-11-22 13:04:32', 'borrowed', 'Existing'),
(2, 11, 'DefaultAdmin', 'FC987654321', NULL, '2024-11-21 19:13:26', '2024-11-22 12:13:26', 'borrowed', 'Existing');

-- --------------------------------------------------------

--
-- Table structure for table `categorytbl`
--

CREATE TABLE `categorytbl` (
  `categoryID` int(11) NOT NULL,
  `categoryName` varchar(150) NOT NULL,
  `createDate` timestamp NOT NULL DEFAULT current_timestamp(),
  `updateDate` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp(),
  `archCat` enum('Removed','Active') NOT NULL DEFAULT 'Active'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `categorytbl`
--

INSERT INTO `categorytbl` (`categoryID`, `categoryName`, `createDate`, `updateDate`, `archCat`) VALUES
(1, 'Health', '2024-11-08 15:29:14', '2024-11-08 16:58:36', 'Removed'),
(2, 'Science', '2024-11-08 16:39:49', '2024-11-08 17:04:12', ''),
(3, 'Literature', '2024-11-08 16:39:49', '2024-11-08 17:07:22', ''),
(4, 'Technology', '2024-11-08 16:39:49', '2024-11-08 17:09:42', ''),
(5, 'History', '2024-11-08 16:39:49', '2024-11-08 17:31:46', 'Removed'),
(6, 'Arts', '2024-11-08 16:39:49', NULL, 'Active'),
(7, 'Medicine', '2024-11-08 16:39:49', NULL, 'Active'),
(8, 'Education', '2024-11-08 16:39:49', NULL, 'Active'),
(9, 'Mathematics', '2024-11-08 16:39:49', NULL, 'Active'),
(10, 'Biology', '2024-11-08 16:39:49', NULL, 'Active'),
(11, 'Chemistry', '2024-11-08 16:39:49', '2024-11-08 17:02:53', ''),
(12, 'Philosophy', '2024-11-08 16:39:49', NULL, 'Active'),
(13, 'Geology', '2024-11-08 16:39:49', NULL, 'Active'),
(14, 'ctTest', '2024-11-08 16:39:49', NULL, 'Active'),
(15, 'Yes', '2024-11-08 16:42:13', '2024-11-08 16:45:16', ''),
(16, 'Yes', '2024-11-08 16:42:32', '2024-11-08 16:45:19', '');

-- --------------------------------------------------------

--
-- Table structure for table `formattbl`
--

CREATE TABLE `formattbl` (
  `formatID` int(11) NOT NULL,
  `formatName` varchar(150) NOT NULL,
  `createDate` timestamp NOT NULL DEFAULT current_timestamp(),
  `updateDate` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp(),
  `archFor` enum('Removed','Active') NOT NULL DEFAULT 'Active'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `formattbl`
--

INSERT INTO `formattbl` (`formatID`, `formatName`, `createDate`, `updateDate`, `archFor`) VALUES
(1, 'Thesis', '2024-11-08 15:29:14', '2024-11-08 17:34:30', 'Removed'),
(2, 'Hardcover', '2024-11-08 16:39:49', NULL, 'Active'),
(3, 'Paperback', '2024-11-08 16:39:49', NULL, 'Active'),
(4, 'eBook', '2024-11-08 16:39:49', NULL, 'Active'),
(5, 'FormatTest', '2024-11-08 16:39:49', NULL, 'Active');

-- --------------------------------------------------------

--
-- Table structure for table `returntbl`
--

CREATE TABLE `returntbl` (
  `returnID` int(11) NOT NULL,
  `borrowID` int(11) NOT NULL,
  `returnDate` timestamp NULL DEFAULT NULL,
  `status` enum('borrowed','returned','overdue') NOT NULL DEFAULT 'borrowed',
  `penalty` decimal(10,2) NOT NULL DEFAULT 10.00,
  `returnarc` enum('Archived','Existing') NOT NULL DEFAULT 'Existing'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `returntbl`
--

INSERT INTO `returntbl` (`returnID`, `borrowID`, `returnDate`, `status`, `penalty`, `returnarc`) VALUES
(1, 1, NULL, 'borrowed', 10.00, 'Existing');

-- --------------------------------------------------------

--
-- Table structure for table `shelftbl`
--

CREATE TABLE `shelftbl` (
  `shelfID` int(11) NOT NULL,
  `shelfLoc` varchar(100) NOT NULL,
  `createDate` timestamp NOT NULL DEFAULT current_timestamp(),
  `updateDate` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp(),
  `archShelf` enum('Active','Removed') NOT NULL DEFAULT 'Active'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `shelftbl`
--

INSERT INTO `shelftbl` (`shelfID`, `shelfLoc`, `createDate`, `updateDate`, `archShelf`) VALUES
(1, 'Shelf 27', '2024-11-08 15:29:14', '2024-11-08 17:43:51', 'Removed'),
(2, 'Shelf 10', '2024-11-08 16:39:49', NULL, 'Active'),
(3, 'Shelf 15', '2024-11-08 16:39:49', NULL, 'Active'),
(4, 'Shelf 20', '2024-11-08 16:39:49', NULL, 'Active'),
(5, 'Shelf 25', '2024-11-08 16:39:49', NULL, 'Active'),
(6, 'Shelf 30', '2024-11-08 16:39:49', NULL, 'Active'),
(7, 'Shelf 35', '2024-11-08 16:39:49', NULL, 'Active'),
(8, 'Shelf 40', '2024-11-08 16:39:49', NULL, 'Active'),
(9, 'Shelf 45', '2024-11-08 16:39:49', NULL, 'Active'),
(10, 'Shelf 50', '2024-11-08 16:39:49', NULL, 'Active'),
(11, 'Shelf 55', '2024-11-08 16:39:49', NULL, 'Active'),
(12, 'Shelf 60', '2024-11-08 16:39:49', NULL, 'Active'),
(13, 'Shelf 65', '2024-11-08 16:39:49', NULL, 'Active'),
(14, 'Shelf 70', '2024-11-08 16:39:49', NULL, 'Active'),
(15, 'Shelf 75', '2024-11-08 16:39:49', NULL, 'Active'),
(16, 'Shelf 80', '2024-11-08 16:39:49', NULL, 'Active'),
(17, 'Shelf 1', '2024-11-08 16:39:49', NULL, 'Active'),
(18, 'Shelf 2', '2024-11-08 16:39:49', NULL, 'Active'),
(19, 'Shelf 3', '2024-11-08 16:39:49', NULL, 'Active'),
(20, 'Shelf 4', '2024-11-08 16:39:49', NULL, 'Active'),
(21, 'Shelf 5', '2024-11-08 16:39:49', NULL, 'Active'),
(22, 'Shelf sf', '2024-11-08 16:39:49', NULL, 'Active');

-- --------------------------------------------------------

--
-- Table structure for table `tblfaculty`
--

CREATE TABLE `tblfaculty` (
  `fcID` int(11) NOT NULL,
  `FacultyID` varchar(100) NOT NULL,
  `Fname` varchar(100) NOT NULL,
  `Mname` varchar(100) NOT NULL,
  `Lname` varchar(100) NOT NULL,
  `ContactNo` bigint(11) NOT NULL,
  `Email` varchar(120) NOT NULL,
  `archFc` enum('Active','Archived') NOT NULL DEFAULT 'Active'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tblfaculty`
--

INSERT INTO `tblfaculty` (`fcID`, `FacultyID`, `Fname`, `Mname`, `Lname`, `ContactNo`, `Email`, `archFc`) VALUES
(1, '4001', 'Alice', 'Marie', 'Johnson', 5551234567, 'kendeangsec@gmail.com', 'Archived'),
(2, 'FC987654321', 'Stephanie', 'Anne', 'Rodriguez', 5559876543, 'ganonpuro@gmail.com', 'Active');

-- --------------------------------------------------------

--
-- Table structure for table `tblstudent`
--

CREATE TABLE `tblstudent` (
  `stID` int(11) NOT NULL,
  `StudentID` varchar(100) NOT NULL,
  `Fname` varchar(100) NOT NULL,
  `Mname` varchar(100) NOT NULL,
  `Lname` varchar(100) NOT NULL,
  `CourseStrand` varchar(120) NOT NULL,
  `YrLevel` varchar(120) NOT NULL,
  `ContactNo` bigint(11) DEFAULT NULL,
  `Email` varchar(120) NOT NULL,
  `hAddress` varchar(255) NOT NULL,
  `archStd` enum('Active','Archived') NOT NULL DEFAULT 'Active'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tblstudent`
--

INSERT INTO `tblstudent` (`stID`, `StudentID`, `Fname`, `Mname`, `Lname`, `CourseStrand`, `YrLevel`, `ContactNo`, `Email`, `hAddress`, `archStd`) VALUES
(1, '2001', 'Olivia', 'Grace', 'Martinez', 'Humanities', '11', 555, 'kendeangsec@gmail.com', '567 Willow Lane, Suburbia, State 56789', 'Archived'),
(2, '2002', 'Ethan', 'Michael', 'Adams', 'Technology', '10', 555, 'ganonpuro@gmail.com', '789 Oak Street, Urbanville, State 78901', 'Active'),
(3, '2003', 'Sophia', 'Elizabeth', 'Collins', 'Arts', '12', 555, 'kennethdaniel70@gmail.com', '123 Maple Avenue, Metropolis, State 12345', 'Active'),
(4, '2004', 'Liam', 'Alexander', 'Turner', 'Science', '9', 555, 'dr360gamer@gmail.com', '456 Elm Road, Countryside, State 45678', 'Active'),
(5, '2005', 'Ava', 'Rose', 'Peterson', 'Commerce', '10', 555, 'kendrm70@gmail.com', '789 Pine Court, Townsville, State 78910', 'Active');

-- --------------------------------------------------------

--
-- Table structure for table `tbluser`
--

CREATE TABLE `tbluser` (
  `userID` int(11) NOT NULL,
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
  `Status` enum('Activated','Inactive') DEFAULT 'Activated',
  `force_password_change` enum('Unchanged','Changed') DEFAULT 'Unchanged',
  `archUser` enum('Active','Archived') NOT NULL DEFAULT 'Active'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbluser`
--

INSERT INTO `tbluser` (`userID`, `Fname`, `Mname`, `Lname`, `EmailAdd`, `ContactNo`, `Username`, `Password`, `Role`, `updateDate`, `creationDate`, `Status`, `force_password_change`, `archUser`) VALUES
(1, 'DefaultAdmin', 'DefaultAdmin', 'DefaultAdmin', 'librarytestbsis41@gmail.com', 9387199430, 'DefaultAdmin', '751cb3f4aa17c36186f4856c8982bf27', 'Admin', '2024-11-08 20:37:43', '2024-10-25 18:28:38', 'Activated', 'Changed', 'Active'),
(2, 'DefaultLib', 'DefaultLib', 'DefaultLib', 'librarytestbsis41@gmail.com', 9387199430, 'DefaultLib', '899ac6cc1e96e8de58fa73b8fed8603a', 'Librarian', '2024-11-08 20:02:14', '2024-10-25 18:28:38', 'Activated', 'Changed', 'Active'),
(3, 'Ken', 'Dan', 'Deang', 'kendeangsec@gmail.com', 8774178971, 'AdminTest', '68eacb97d86f0c4621fa2b0e17cabd8c', 'Admin', '2024-11-08 18:14:02', '2024-11-01 16:06:32', 'Inactive', 'Unchanged', 'Archived'),
(4, '3', '3', '3', 'kennethdaniel70@gmail.com', 3123123123, '3', '31d582589517ace1e79817a08259d8f7', 'Librarian', '2024-11-08 19:51:06', '2024-11-08 18:16:17', 'Inactive', 'Unchanged', 'Archived'),
(5, 'Test', 'Test', 'Test', 'librarytestbsis41@gmail.com', 2141412412, 'Test', '3a25a74fffce826d6c4f99397244024f', 'Admin', NULL, '2024-11-12 14:26:39', 'Activated', 'Unchanged', 'Active');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `booktbl`
--
ALTER TABLE `booktbl`
  ADD PRIMARY KEY (`bookID`),
  ADD UNIQUE KEY `isbnNumber` (`isbnNumber`),
  ADD UNIQUE KEY `bookCode` (`bookCode`),
  ADD KEY `categoryID` (`category`),
  ADD KEY `formatID` (`format`),
  ADD KEY `shelf` (`shelf`);

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
-- Indexes for table `categorytbl`
--
ALTER TABLE `categorytbl`
  ADD PRIMARY KEY (`categoryID`);

--
-- Indexes for table `formattbl`
--
ALTER TABLE `formattbl`
  ADD PRIMARY KEY (`formatID`);

--
-- Indexes for table `returntbl`
--
ALTER TABLE `returntbl`
  ADD PRIMARY KEY (`returnID`),
  ADD KEY `borrowID` (`borrowID`);

--
-- Indexes for table `shelftbl`
--
ALTER TABLE `shelftbl`
  ADD PRIMARY KEY (`shelfID`);

--
-- Indexes for table `tblfaculty`
--
ALTER TABLE `tblfaculty`
  ADD PRIMARY KEY (`fcID`),
  ADD UNIQUE KEY `FacultyID` (`FacultyID`);

--
-- Indexes for table `tblstudent`
--
ALTER TABLE `tblstudent`
  ADD PRIMARY KEY (`stID`),
  ADD UNIQUE KEY `StudentID` (`StudentID`);

--
-- Indexes for table `tbluser`
--
ALTER TABLE `tbluser`
  ADD PRIMARY KEY (`userID`),
  ADD UNIQUE KEY `Username` (`Username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `booktbl`
--
ALTER TABLE `booktbl`
  MODIFY `bookID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT for table `borrowtbl`
--
ALTER TABLE `borrowtbl`
  MODIFY `borrowID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `categorytbl`
--
ALTER TABLE `categorytbl`
  MODIFY `categoryID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `formattbl`
--
ALTER TABLE `formattbl`
  MODIFY `formatID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `returntbl`
--
ALTER TABLE `returntbl`
  MODIFY `returnID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `shelftbl`
--
ALTER TABLE `shelftbl`
  MODIFY `shelfID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `tblfaculty`
--
ALTER TABLE `tblfaculty`
  MODIFY `fcID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `tblstudent`
--
ALTER TABLE `tblstudent`
  MODIFY `stID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `tbluser`
--
ALTER TABLE `tbluser`
  MODIFY `userID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `booktbl`
--
ALTER TABLE `booktbl`
  ADD CONSTRAINT `booktbl_ibfk_1` FOREIGN KEY (`category`) REFERENCES `categorytbl` (`categoryID`),
  ADD CONSTRAINT `booktbl_ibfk_2` FOREIGN KEY (`format`) REFERENCES `formattbl` (`formatID`),
  ADD CONSTRAINT `booktbl_ibfk_3` FOREIGN KEY (`shelf`) REFERENCES `shelftbl` (`shelfID`);

--
-- Constraints for table `borrowtbl`
--
ALTER TABLE `borrowtbl`
  ADD CONSTRAINT `borrowtbl_ibfk_1` FOREIGN KEY (`bookID`) REFERENCES `booktbl` (`bookID`),
  ADD CONSTRAINT `borrowtbl_ibfk_2` FOREIGN KEY (`Username`) REFERENCES `tbluser` (`Username`),
  ADD CONSTRAINT `borrowtbl_ibfk_3` FOREIGN KEY (`FacultyID`) REFERENCES `tblfaculty` (`FacultyID`),
  ADD CONSTRAINT `borrowtbl_ibfk_4` FOREIGN KEY (`StudentID`) REFERENCES `tblstudent` (`StudentID`);

--
-- Constraints for table `returntbl`
--
ALTER TABLE `returntbl`
  ADD CONSTRAINT `returntbl_ibfk_1` FOREIGN KEY (`borrowID`) REFERENCES `borrowtbl` (`borrowID`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
