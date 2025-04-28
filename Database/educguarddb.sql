-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 28, 2025 at 08:11 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `educguarddb`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin_acc`
--

CREATE TABLE `admin_acc` (
  `admin_id` int(11) NOT NULL,
  `admin_email` varchar(255) NOT NULL,
  `admin_password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `advisers`
--

CREATE TABLE `advisers` (
  `ID` int(11) NOT NULL,
  `user_role` varchar(50) DEFAULT 'adviser',
  `adviserFullName` varchar(250) DEFAULT NULL,
  `adviserContactNumber` varchar(15) DEFAULT NULL,
  `adviserEmailAddress` varchar(250) DEFAULT NULL,
  `adviserPassword` varchar(255) DEFAULT NULL,
  `adviserGrLvl` varchar(10) DEFAULT NULL,
  `adviserSection` varchar(250) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `advisers`
--

INSERT INTO `advisers` (`ID`, `user_role`, `adviserFullName`, `adviserContactNumber`, `adviserEmailAddress`, `adviserPassword`, `adviserGrLvl`, `adviserSection`) VALUES
(16, 'adviser', 'John Doe', '09123456789', 'johndoe@gmail.com', 'password123', '10', 'ROBOT'),
(17, 'adviser', 'Kian Daenielle Garcia', '09761202372', 'kian@gmail.com', 'kian123', '10', 'BRONZE'),
(18, 'adviser', 'Jesusa Saavedra', '09451202372', 'jesusa@gmail.com', 'jesusa123', '9', 'METAL'),
(19, 'adviser', 'Maria Josefa Cruz', '09761202372', 'maria@gmail.com', 'maria123', '9', 'COPPER'),
(20, 'adviser', 'Marvin Cyrill R. Palomar', '0963258465', 'marvin@gmail.com', 'marvin123', '10', 'GOLD');

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
-- Table structure for table `class_section`
--

CREATE TABLE `class_section` (
  `section_id` int(11) NOT NULL,
  `section_name` varchar(100) DEFAULT NULL,
  `section_grade_level` varchar(50) DEFAULT NULL,
  `section_year_start_level` year(4) DEFAULT NULL,
  `section_year_end_level` year(4) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `class_section`
--

INSERT INTO `class_section` (`section_id`, `section_name`, `section_grade_level`,`section_year_start_level`,`section_year_end_level`) VALUES
(14, 'GOLD', '7', '2024','2025'),
(15, 'SILVER', '10', '2024','2025'),
(16, 'BRONZE', '9', '2024','2025'),
(17, 'DIAMOND', '10', '2024','2025'),
(18, 'COPPER', '7', '2024','2025'),
(19, 'METAL', '8', '2024','2025'),
(20, 'ROBOT', '10', '2024','2025'),
(21, 'PLATINUM', '9', '2024','2025'),
(22, 'SILVER', '7', '2024','2025');

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
  `year_level` varchar(20) DEFAULT NULL,
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

INSERT INTO `students` (`id`, `lrn`, `rfid_number`, `first_name`, `middle_name`, `last_name`, `birthdate`, `sex`, `contact_number`, `grade_level`,`year_level`, `address`, `email`, `parent_guardian_name`, `parent_guardian_number`, `parent_guardian_email`, `student_username`, `status`, `created_at`, `section`) VALUES
(17, '954527163', '1308825062', 'Jerry', 'Libero', 'Castrudes', '2004-07-20', '', '09325520318', '9', '2024-2025','5264 STARAPPLE STREET GEN. T. DE LEON', 'blenderjk9@gmail.com', 'Kirby Castrudes', '09763026128', 'kirbyragasajo09@gmail.com', 'jk0903', 'Active', '2025-02-24 16:56:46', 'SILVER'),
(19, '987654321', '1308396166', 'Juliana', 'Libero', 'Castrudes', '2003-01-16', '', '09325520318', '7','2024-2025', '5264 STARAPPLE STREET GEN. T. DE LEON', '110903kirby@gmail.com', 'Kirby Castrudes', '09763026128', 'kirbyragasajo09@gmail.com', 'wow', 'Active', '2025-02-24 17:25:18', 'COPPER'),
(20, '104961090109', '1601793334', 'rianne', 'gonzales', 'saquez', '2004-09-13', '', '09463182232', '7','2024-2025', 'oslo, norway', 'rianne@gmail.com', 'Kirby Castrudes', '09763026128', 'kirbyragasajo09@gmail.com', 'assie', 'Active', '2025-02-24 17:53:36', 'COPPER'),
(21, '104961090105', '1603665222', 'Marvin', 'Cyrill', 'Palomar', '2003-02-10', '', '09605470308', '7','2024-2025', '5264 STARAPPLE STREET GEN. T. DE LEON, VALENZUELA', 'marvin20@gmail.com', 'Kirby Castrudes', '09763026128', 'kirbyragasajo09@gmail.com', 'marvs', 'Active', '2025-02-24 19:26:05', 'METAL'),
(23, '987654321444', '1077729142', 'gfghfgf', 'hjghjghq', 'ghffgfghfqq', '2025-02-13', '', '09325520318', '7','2024-2025','nnbnbnmbnbnm', '123@gmail.com', 'ghhgfhgfgfdf', '22412154561654', 'fghfghfgf@gmail.com', 'hghgjhgg', 'Active', '2025-02-24 20:04:07', 'COPPER'),
(31, '101909202303', '1309220678', 'Roger', 'Espina', 'Cabaylo', '2004-08-27', '', '09761206372', '7','2024-2025', '5264 STARAPPLE STREET GEN. T. DE LEON', 'rogerpogi@gmail.com', 'Kirby Castrudes', '09763026128', 'kirbyragasajo09@gmail.com', 'rogerpogi', 'Active', '2025-03-03 20:07:52', 'DIAMOND'),
(32, 't6345345', '4523423', 'fqweff', 'ewfcewf', 'qwedfqf', '2025-03-14', '', '123123213', '9', 'fedvwv', '2024-2025','fdsg@gmail.com', 'evcwwe', '312412', 'svsD@gmail.com', 'dfgberhbt', 'Inactive', '2025-03-26 04:58:26', 'METAL'),
(33, '34123412', '4234124312', 'visayass', 'luzonn', 'mindanao', '2025-03-13', '', '2321421321412', '8','2024-2025', 'bfciabfciuqwoucbqj', 'dbgrehrtb@gmail.com', 'csdvavcas', '324214312', 'asdasvsd@gmail.com', 'ibibibibi', 'Active', '2025-03-26 04:59:32', 'COPPER');

-- --------------------------------------------------------

--
-- Table structure for table `subjects`
--

CREATE TABLE `subjects` (
  `id` int(11) NOT NULL,
  `subject_name` varchar(255) NOT NULL,
  `subject_picture` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `subjects`
--

INSERT INTO `subjects` (`id`, `subject_name`, `subject_picture`, `created_at`) VALUES
(17, 'AP', 'ap.jpg', '2025-03-24 19:14:34'),
(18, 'Science', 'science.jpg', '2025-03-24 19:15:19'),
(19, 'Filipino', 'filipino.jpg', '2025-03-24 19:18:29'),
(21, 'science biology', 'calendar_747310.png', '2025-03-24 22:04:31'),
(24, 'science', '15538954589_734e94866f_h.jpg', '2025-03-26 09:12:55'),
(26, 'ewfwefgv', '481991918_3857668494501477_7232819675018823351_n.jpg', '2025-03-26 09:33:36'),
(27, 'ewfwefgv', '481991918_3857668494501477_7232819675018823351_n.jpg', '2025-03-26 09:33:36'),
(28, 'vfbtgnryne', 'uneviled 8-1 Productions  group  1.png', '2025-03-26 09:34:15');

-- --------------------------------------------------------

--
-- Table structure for table `user_acc`
--

CREATE TABLE `user_acc` (
  `user_id` int(11) NOT NULL,
  `user_email` varchar(50) DEFAULT NULL,
  `user_password` varchar(250) DEFAULT NULL,
  `user_role` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user_acc`
--

INSERT INTO `user_acc` (`user_id`, `user_email`, `user_password`, `user_role`) VALUES
(1, 'admin@gmail.com', 'admin123', 'admin'),
(2, 'adviser@gmail.com', 'adviser123', 'adviser'),
(3, 'student@gmail.com', 'student123', 'student');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin_acc`
--
ALTER TABLE `admin_acc`
  ADD PRIMARY KEY (`admin_id`),
  ADD UNIQUE KEY `unique_email` (`admin_email`);

--
-- Indexes for table `advisers`
--
ALTER TABLE `advisers`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `attendance`
--
ALTER TABLE `attendance`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `class_section`
--
ALTER TABLE `class_section`
  ADD PRIMARY KEY (`section_id`);

--
-- Indexes for table `students`
--
ALTER TABLE `students`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `lrn` (`lrn`),
  ADD UNIQUE KEY `rfid_number` (`rfid_number`),
  ADD UNIQUE KEY `student_username` (`student_username`);

--
-- Indexes for table `subjects`
--
ALTER TABLE `subjects`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user_acc`
--
ALTER TABLE `user_acc`
  ADD PRIMARY KEY (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin_acc`
--
ALTER TABLE `admin_acc`
  MODIFY `admin_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `advisers`
--
ALTER TABLE `advisers`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=47;

--
-- AUTO_INCREMENT for table `attendance`
--
ALTER TABLE `attendance`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=142;

--
-- AUTO_INCREMENT for table `class_section`
--
ALTER TABLE `class_section`
  MODIFY `section_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `students`
--
ALTER TABLE `students`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT for table `subjects`
--
ALTER TABLE `subjects`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT for table `user_acc`
--
ALTER TABLE `user_acc`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
