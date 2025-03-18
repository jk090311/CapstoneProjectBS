-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Mar 18, 2025 at 03:11 PM
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
-- Database: `attendance_tracking`
--

-- --------------------------------------------------------

--
-- Table structure for table `attendance`
--

CREATE TABLE `attendance` (
  `id` int(11) NOT NULL,
  `rfid_number` varchar(20) NOT NULL,
  `first_name` varchar(50) NOT NULL,
  `last_name` varchar(50) NOT NULL,
  `time_in` time DEFAULT NULL,
  `time_out` time DEFAULT NULL,
  `date_logged` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `attendance`
--

INSERT INTO `attendance` (`id`, `rfid_number`, `first_name`, `last_name`, `time_in`, `time_out`, `date_logged`) VALUES
(136, '1601793334', 'rianne', 'saquez', '11:16:57', '11:17:25', '2025-03-04'),
(137, '1308396166', 'Juliana', 'Castrudes', '11:17:08', '11:17:28', '2025-03-04'),
(138, '1603665222', 'Marvin', 'Palomar', '11:17:14', '11:17:32', '2025-03-04'),
(139, '1308825062', 'Jerry', 'Castrudes', '11:17:18', '11:17:35', '2025-03-04'),
(140, '1077729142', 'gfghfgf', 'ghffgfghfqq', '11:17:21', '11:17:38', '2025-03-04'),
(141, '1309220678', 'Roger', 'Cabaylo', '12:10:48', '12:11:03', '2025-03-04');

-- --------------------------------------------------------

--
-- Table structure for table `students`
--

CREATE TABLE `students` (
  `id` int(11) NOT NULL,
  `lrn` varchar(15) NOT NULL,
  `rfid_number` varchar(20) NOT NULL,
  `first_name` varchar(50) NOT NULL,
  `middle_name` varchar(50) DEFAULT NULL,
  `last_name` varchar(50) NOT NULL,
  `birthdate` date DEFAULT NULL,
  `sex` enum('Male','Female') DEFAULT NULL,
  `contact_number` varchar(15) DEFAULT NULL,
  `grade_level` varchar(20) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `parent_guardian_name` varchar(100) DEFAULT NULL,
  `parent_guardian_number` varchar(15) DEFAULT NULL,
  `parent_guardian_email` varchar(100) DEFAULT NULL,
  `student_username` varchar(50) DEFAULT NULL,
  `status` enum('Active','Inactive') DEFAULT 'Active',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `section` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `students`
--

INSERT INTO `students` (`id`, `lrn`, `rfid_number`, `first_name`, `middle_name`, `last_name`, `birthdate`, `sex`, `contact_number`, `grade_level`, `address`, `email`, `parent_guardian_name`, `parent_guardian_number`, `parent_guardian_email`, `student_username`, `status`, `created_at`, `section`) VALUES
(17, '954527163', '1308825062', 'Jerry', 'Libero', 'Castrudes', '2004-07-20', '', '09325520318', '7', '5264 STARAPPLE STREET GEN. T. DE LEON', 'blenderjk9@gmail.com', 'Kirby Castrudes', '09763026128', 'kirbyragasajo09@gmail.com', 'jk0903', 'Active', '2025-02-25 00:56:46', 'Aqua'),
(19, '987654321', '1308396166', 'Juliana', 'Libero', 'Castrudes', '2003-01-16', '', '09325520318', '7', '5264 STARAPPLE STREET GEN. T. DE LEON', '110903kirby@gmail.com', 'Kirby Castrudes', '09763026128', 'kirbyragasajo09@gmail.com', 'wow', 'Active', '2025-02-25 01:25:18', 'Aqua'),
(20, '104961090109', '1601793334', 'rianne', 'gonzales', 'saquez', '2004-09-13', '', '09463182232', '7', 'oslo, norway', 'rianne@gmail.com', 'Kirby Castrudes', '09763026128', 'kirbyragasajo09@gmail.com', 'assie', 'Active', '2025-02-25 01:53:36', 'Aqua'),
(21, '104961090105', '1603665222', 'Marvin', 'Cyrill', 'Palomar', '2003-02-10', '', '09605470308', '7', '5264 STARAPPLE STREET GEN. T. DE LEON, VALENZUELA', 'marvin20@gmail.com', 'Kirby Castrudes', '09763026128', 'kirbyragasajo09@gmail.com', 'marvs', 'Active', '2025-02-25 03:26:05', 'Aqua'),
(23, '987654321444', '1077729142', 'gfghfgf', 'hjghjghq', 'ghffgfghfqq', '2025-02-13', '', '09325520318', '7', 'nnbnbnmbnbnm', '123@gmail.com', 'ghhgfhgfgfdf', '22412154561654', 'fghfghfgf@gmail.com', 'hghgjhgg', 'Active', '2025-02-25 04:04:07', 'Aqua'),
(31, '101909202303', '1309220678', 'Roger', 'Espina', 'Cabaylo', '2004-08-27', '', '09761206372', '7', '5264 STARAPPLE STREET GEN. T. DE LEON', 'rogerpogi@gmail.com', 'Kirby Castrudes', '09763026128', 'kirbyragasajo09@gmail.com', 'rogerpogi', 'Active', '2025-03-04 04:07:52', 'Aqua');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `attendance`
--
ALTER TABLE `attendance`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `students`
--
ALTER TABLE `students`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `lrn` (`lrn`),
  ADD UNIQUE KEY `rfid_number` (`rfid_number`),
  ADD UNIQUE KEY `student_username` (`student_username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `attendance`
--
ALTER TABLE `attendance`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=142;

--
-- AUTO_INCREMENT for table `students`
--
ALTER TABLE `students`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
