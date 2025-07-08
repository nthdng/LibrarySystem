-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 13, 2024 at 07:18 AM
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
  `archBook` enum('Archived','Existing','','') NOT NULL DEFAULT 'Existing'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `booktbl`
--

INSERT INTO `booktbl` (`bookID`, `bookCode`, `bookTitle`, `format`, `category`, `shelf`, `isbnNumber`, `count`, `accountNumber`, `regDate`, `updateDate`, `archBook`) VALUES
(1, 'Fil-123', 'Filipino 12', 1, 1, 1, '9182', 89, 18291, '2024-11-08 15:29:14', '2024-11-08 16:40:36', ''),
(2, 'Sci-101', 'Quantum Physics', 2, 2, 2, '978-3-16-148410-0', 50, 10001, '2024-11-08 16:39:49', '2024-11-08 17:43:35', 'Archived'),
(3, 'Lit-202', 'Shakespeare’s Works', 3, 3, 3, '978-0-14-043908-3', 120, 10002, '2024-11-08 16:39:49', NULL, 'Existing'),
(4, 'Tech-303', 'AI Revolution', 4, 4, 4, '978-1-59327-584-6', 200, 10003, '2024-11-08 16:39:49', NULL, 'Existing'),
(5, 'Hist-404', 'World War II', 2, 5, 5, '978-0-19-280670-3', 80, 10004, '2024-11-08 16:39:49', NULL, 'Existing'),
(6, 'Art-505', 'Modern Art', 3, 6, 6, '978-0-500-20483-1', 60, 10005, '2024-11-08 16:39:49', NULL, 'Existing'),
(7, 'Med-606', 'Human Anatomy', 2, 7, 7, '978-0-323-35378-6', 40, 10006, '2024-11-08 16:39:49', NULL, 'Existing'),
(8, 'Eng-707', 'English Grammar', 3, 8, 8, '978-0-19-457980-3', 90, 10007, '2024-11-08 16:39:49', NULL, 'Existing'),
(9, 'Math-808', 'Calculus', 2, 9, 9, '978-0-321-71674-8', 70, 10008, '2024-11-08 16:39:49', NULL, 'Existing'),
(10, 'Bio-909', 'Genetics', 4, 10, 10, '978-0-13-404779-7', 110, 10009, '2024-11-08 16:39:49', NULL, 'Existing'),
(11, 'Chem-010', 'Organic Chemistry', 3, 11, 11, '978-0-13-404228-0', 30, 10010, '2024-11-08 16:39:49', NULL, 'Existing'),
(12, 'Phil-111', 'Philosophy 101', 2, 12, 12, '978-0-19-285421-6', 85, 10011, '2024-11-08 16:39:49', NULL, 'Existing'),
(13, 'Geo-212', 'Earth Science', 3, 13, 13, '978-0-13-467971-7', 95, 10012, '2024-11-08 16:39:49', NULL, 'Existing'),
(14, 'Hist-313', 'Ancient Civilizations', 4, 5, 14, '978-0-19-280458-7', 55, 10013, '2024-11-08 16:39:49', NULL, 'Existing'),
(15, 'Lit-414', 'Modern Poetry', 2, 3, 15, '978-0-19-953556-9', 65, 10014, '2024-11-08 16:39:49', NULL, 'Existing'),
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
(27, 'Phil-112', 'Meditations', 2, 12, 17, '978-0-14-044933-4', 7, 40011, '2024-11-08 16:39:49', NULL, 'Existing'),
(28, 'Geo-213', 'Physical Geology', 3, 13, 18, '978-0-13-444662-2', 6, 40012, '2024-11-08 16:39:49', NULL, 'Existing'),
(29, 'Hist-314', 'The Silk Roads', 2, 5, 19, '978-1-4088-3997-3', 5, 40013, '2024-11-08 16:39:49', NULL, 'Existing'),
(30, 'Tech-516', 'The Pragmatic Programmer', 3, 4, 21, '978-0-13-595705-9', 8, 40015, '2024-11-08 16:39:49', NULL, 'Existing'),
(31, 'Tech-321', 'The Pragmatic Programmer', 5, 14, 22, '575478785', 8, 40017, '2024-11-08 16:39:49', NULL, 'Existing');

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
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `booktbl`
--
ALTER TABLE `booktbl`
  MODIFY `bookID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

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
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
