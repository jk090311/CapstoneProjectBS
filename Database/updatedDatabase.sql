-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: May 18, 2025 at 04:59 PM
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
(16, 'adviser', 'John Doe', '09123456789', 'johndoe@gmail.com', 'password', '10', 'COPPER'),
(18, 'adviser', 'Jesusa Saavedra', '09451202372', 'jesusa@gmail.com', 'jesusa123', '9', 'BRONZE'),
(19, 'adviser', 'Maria Josefa Cruz', '09761202372', 'maria@gmail.com', 'maria123', '7', 'GOLD'),
(51, 'adviser', 'metal prof', '06515656', 'fbsdbvsd@gmail.com', 'mvdjkvnjoklsd', '8', 'METAL'),
(52, 'adviser', 'copper proff', '0656232515', 'copper@gmail.com', 'copper123', '10', 'YOUTUBE'),
(53, 'adviser', 'Edjelyn Rose Tapac', '09605471308', 'Edjelyn@gmail.com', 'Edjelyn123', '8', 'NIKIEL'),
(57, 'adviser', 'Janella Marie Ragasajo', '09325520318', 'ella@gmail.com', 'ella123', '7', 'GOLD'),
(58, 'adviser', 'Johann Kirby Ragasajo', '09784563210', 'ybrik@gmail.com', '$2y$10$07gisNTieHmB0UyM8VdI0e9gLUMBdtbmWl0pP38VcwqGBXDn53/8u', '7', 'GOLD');

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
(183, '1601793334', 'JOHN KENETH', 'BRILLANTES', '17:52:44', '17:53:29', '2025-05-15'),
(184, '1309142790', 'JOHN ZEL', 'DE GUZMAN', '17:53:02', '17:53:25', '2025-05-15'),
(185, '1314269462', 'IAN CARL LUNAS', 'ALGABRE', '17:53:11', '17:53:22', '2025-05-15'),
(186, '1603665222', 'DESTINY', 'BALELA', '18:00:52', '18:02:09', '2025-05-15'),
(187, '1083328758', 'ROGER', 'CABAYLO', '18:01:09', '18:02:16', '2025-05-15'),
(188, '1309142790', 'JOHN ZEL', 'DE GUZMAN', '18:01:12', NULL, '2025-05-15'),
(189, '1308825062', 'JOHN KURT', 'CAAMPUED', '18:01:13', '18:01:51', '2025-05-15'),
(190, '1309220678', 'CARL JONAS', 'DELA CRUZ', '18:01:14', '18:02:13', '2025-05-15'),
(191, '1310097286', 'JERRY LIBERO', 'CASTRUDES', '18:01:22', '18:01:53', '2025-05-15'),
(192, '1601793334', 'JOHN KENETH', 'BRILLANTES', '18:01:46', NULL, '2025-05-15'),
(193, '1308396166', 'KHURSTEN BHRYLLE', 'BARREDO', '18:01:55', '18:01:56', '2025-05-15'),
(194, '1077729142', 'JOHN KARLO', 'DE GUZMAN', '18:02:00', NULL, '2025-05-15'),
(195, '1309142790', 'JOHN ZEL', 'DE GUZMAN', '18:02:03', NULL, '2025-05-15'),
(196, '1314269462', 'IAN CARL LUNAS', 'ALGABRE', '18:02:05', NULL, '2025-05-15'),
(197, '1601793334', 'JOHN KENETH', 'BRILLANTES', '18:02:18', NULL, '2025-05-15');

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

INSERT INTO `class_section` (`section_id`, `section_name`, `section_grade_level`, `section_year_start_level`, `section_year_end_level`) VALUES
(14, 'GOLD', '7', '2015', '2016'),
(16, 'BRONZE', '8', '2016', '2017'),
(17, 'DIAMOND', '9', '2017', '2018'),
(18, 'COPPER', '10', '2018', '2019'),
(19, 'METAL', '7', '2019', '2020'),
(20, 'ROBOT', '7', '2024', '2025'),
(21, 'PLATINUM', '7', '2024', '2025'),
(22, 'SILVER', '7', '2024', '2025'),
(25, 'NIKIEL', '7', '2024', '2025'),
(26, 'THINKVISION', '7', '2023', '2024'),
(36, 'GOOGLE', '7', '2025', '2023'),
(37, 'YOUTUBE', '10', '2025', '2026');

-- --------------------------------------------------------

--
-- Table structure for table `final_grades`
--

CREATE TABLE `final_grades` (
  `final_grade_id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `subject_id` int(11) NOT NULL,
  `school_year` varchar(20) DEFAULT NULL,
  `final_grade` decimal(5,2) NOT NULL,
  `remarks` varchar(255) DEFAULT NULL,
  `date_computed` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `grades`
--

CREATE TABLE `grades` (
  `grade_id` int(11) NOT NULL AUTO_INCREMENT,
  `student_id` int(11) NOT NULL,
  `subject_id` int(11) NOT NULL, 
  `quarter_id` int(11) NOT NULL,
  `grade` decimal(5,2) NOT NULL,
  `remarks` varchar(255) DEFAULT NULL,
  `date_recorded` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`grade_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `mesages`
--

CREATE TABLE `mesages` (
  `msg_id` int(11) NOT NULL,
  `incoming_msg_id` int(255) NOT NULL,
  `outgoing_msg_id` int(255) NOT NULL,
  `msg` varchar(1000) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `mesages`
--

INSERT INTO `mesages` (`msg_id`, `incoming_msg_id`, `outgoing_msg_id`, `msg`) VALUES
(1, 0, 0, 'Hello'),
(2, 0, 0, 'Hi'),
(3, 0, 0, 'Ha'),
(4, 0, 0, 'Ho'),
(5, 0, 0, 'Hu'),
(6, 0, 0, 'Wow'),
(7, 0, 0, 'Ba'),
(8, 0, 0, 'fsdfsdfsd'),
(9, 0, 0, 'dasdasda'),
(10, 0, 0, 'dsadas'),
(11, 0, 0, 'sadasd'),
(12, 0, 0, 'sadas');

-- --------------------------------------------------------

--
-- Table structure for table `quarters`
--

CREATE TABLE `quarters` (
  `quarter_id` int(11) NOT NULL,
  `quarter_name` varchar(20) NOT NULL,
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `school_year` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `quarters`
--

INSERT INTO `quarters` (`quarter_id`, `quarter_name`, `start_date`, `end_date`, `school_year`) VALUES
(1, 'Quarter 1', NULL, NULL, NULL),
(2, 'Quarter 2', NULL, NULL, NULL),
(3, 'Quarter 3', NULL, NULL, NULL),
(4, 'Quarter 4', NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `students`
--

CREATE TABLE `students` (
  `student_id` int(11) NOT NULL,
  `lrn` varchar(15) NOT NULL,
  `rfid_number` varchar(20) DEFAULT NULL,
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
  `section` varchar(255) DEFAULT NULL,
  `student_password` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `students`
--

INSERT INTO `students` (`student_id`, `lrn`, `rfid_number`, `first_name`, `middle_name`, `last_name`, `birthdate`, `sex`, `contact_number`, `grade_level`, `year_level`, `address`, `email`, `parent_guardian_name`, `parent_guardian_number`, `parent_guardian_email`, `student_username`, `status`, `created_at`, `section`, `student_password`) VALUES
(38, '22-0540', '', ' JEAN ANN ', 'PEÑAREDOND', 'ABAY', '2025-05-31', 'Female', '09155047985', '', NULL, 'Valenzuela City', 'jean@gmail.com', 'DE ASIS, MARK JOHN PARIÑAS', '09635471308', 'castrudesjerry02@gmail.com', '22-0540', 'Active', '2025-05-01 18:18:05', 'GOLD', '$2y$10$pdQg6R9U.ovqZJVAkQfi2.tzTUxXi38GV62hug5sJBi'),
(46, '22-3176', '1314269462', 'IAN CARL LUNAS', 'LUNAS', 'ALGABRE', '2025-02-01', 'Male', '09995009618', '', NULL, 'Valenzuela City', 'castrudesjerry02@gmail.com', 'ALGABRE, IAN CARL LUNAS', '09995009618', 'castrudesjerry02@gmail.com', 'algabre02', 'Active', '2025-05-01 19:25:53', 'GOLD', '$2y$10$8cKW4BERHsjezJYEtXP28e.7QJb3IRzGfYYij9nvJ4P'),
(47, '20-0103', NULL, 'KYLA', 'HERNANDEZ', 'ANCHETA', '2018-02-15', 'Female', '09270450956', '', NULL, 'Valenzuela City', 'anchetakyla09@gmail.com', 'ANCHETA, KYLA H.', '09270450956', 'anchetakyla09@gmail.com', 'anchetakyla09', 'Active', '2025-05-01 19:27:43', 'GOLD', '$2y$10$4iZ30sMJ1tqr9nyQFlm4k.MPT4kcizu3Nx3OnUX0o2A'),
(48, '22-0461', '1603665222', 'DESTINY', 'PEÑA', 'BALELA', '2020-07-24', 'Female', '09052595870', '', NULL, 'Valenzuela City', 'destiny.balela31@gmail.com', 'BALELA, DESTINY PEÑA', '09052595870', 'destiny.balela31@gmail.com', 'destiny.balela31', 'Active', '2025-05-01 19:34:26', 'GOLD', '$2y$10$zCbdBXPBIApluna1H3SuXOO5IT8OCYE1Z9S46YBZeth'),
(49, '22-2857', '1308396166', 'KHURSTEN BHRYLLE', 'PACLEB', 'BARREDO', '2015-03-26', 'Male', '09690640056', '', NULL, 'Valenzuela City', 'destiny.balela31@gmail.com', 'BARREDO, KHURSTEN BHRYLLE PACLEB', '09690640056', 'destiny.balela31@gmail.com', 'destiny', 'Active', '2025-05-01 19:36:03', 'GOLD', '$2y$10$B/HoKisUJEgVO3IqOHh8c.Omc3owOVr.KToZRkX5H5L'),
(50, '22-0188', '0', 'ALIJANDRO', 'MAGLENTE', 'BATAC', '2012-07-18', 'Male', '09971154714', '', NULL, 'Valenzuela City', 'silver.batacam@gmail.com', 'BATAC, ALIJANDRO MAGLENTE', '09971154714', 'silver.batacam@gmail.com', 'batacam', 'Active', '2025-05-01 19:37:18', 'GOLD', '$2y$10$sH7LXTNMA72LL6Ha9DOK6OzAkmSABR3UFjddUvqdqQ8'),
(51, '22-1458', NULL, 'ROSE JEAN', '', 'BESINGA', '2017-07-06', 'Female', '09949129289', '', NULL, 'Valenzuela City', 'besingarosejean@gmail.com', 'BESINGA, ROSE JEAN, COMPARATIVO', '09949129289', 'besingarosejean@gmail.com', 'besingarosejean', 'Active', '2025-05-01 19:38:48', 'GOLD', '$2y$10$bY67oe8L6MdUHouq1rCKH.P.V0.skwtFVaQAD3vbI5C'),
(52, '22-2638', '1601793334', 'JOHN KENETH', 'GAMATERO', 'BRILLANTES', '2017-07-13', 'Male', '09273307778', '7', '', 'Valenzuela City', 'brillantesjohnkeneth@gmail.com', 'BRILLANTES, JOHN KENETH GAMATERO', '09273307778', 'brillantesjohnkeneth@gmail.com', 'brillantes', 'Active', '2025-05-01 19:40:32', 'GOLD', '$2y$10$7KHMxCoBYd5jKwih3idnHOpqprx.V05bWBARYaBjOb5'),
(53, '22-2702', '1308825062', 'JOHN KURT', 'MORALES', 'CAAMPUED', '2018-07-14', 'Male', '09355329909', '', NULL, 'Valenzuela City', 'jkurtcaampued@gmail.com', 'CAAMPUED, JOHN KURT MORALES', '09355329909', 'jkurtcaampued@gmail.com', 'jkurtcaampued', 'Active', '2025-05-01 19:42:32', 'GOLD', '$2y$10$Au0zqxntB.W7Qpi1SRLO3ultPRDTejZ2nPiUKipt4qF'),
(54, '22-2716', '1083328758', 'ROGER', 'ESPINA', 'CABAYLO', '2014-02-02', 'Male', '09634979322', '', NULL, 'Valenzuela City', 'rogercabaylo.edu@gmail.com', 'CABAYLO, ROGER ESPINA', '09634979322', 'rogercabaylo.edu@gmail.com', 'rogercabaylo', 'Active', '2025-05-01 19:44:17', 'GOLD', '$2y$10$ENZMihSNzL0ciouQ4iK30eO3RbHqDMFVFI1SW1IydW2'),
(55, '22-0782', '1310097286', 'JERRY LIBERO', 'LIBERO', 'CASTRUDES', '2009-03-04', 'Male', '09065060366', '', NULL, 'Valenzuela City', 'castrudesjerry02@gmail.com', 'CASTRUDES, JERRY LIBERO', '09065060366', 'castrudesjerry02@gmail.com', 'castrudesjerry', 'Active', '2025-05-01 19:45:58', 'GOLD', '$2y$10$4u2WMNzG9InDxziCakH0MeGdaOcwlqRUrQhZ3M.Cr3Y'),
(56, '22-0654', '1077729142', 'JOHN KARLO', 'ELNAS', 'DE GUZMAN', '2012-07-19', 'Male', '09982506364', '7', '', 'Valenzuela City', 'karlodg32@gmail.com', 'DE GUZMAN, JOHN KARLO ELNAS', '09982506364', 'karlodg32@gmail.com', 'karlodg', 'Active', '2025-05-01 19:47:46', 'GOLD', '$2y$10$CA0gv9jDYActguMQ3f.I9ek5b7N3pL5cd/G708K2JGh'),
(57, '22-0651', '1309142790', 'JOHN ZEL', 'DORADO', 'DE GUZMAN', '2016-02-09', 'Male', '09069129374', '', NULL, 'Valenzuela City', 'johnzelthegreat18@gmail.com', 'DE GUZMAN, JOHN ZEL DORADO', '09069129374', 'johnzelthegreat18@gmail.com', 'johnzel', 'Active', '2025-05-01 19:49:14', 'GOLD', '$2y$10$pQY2QrmEn6rbCS5axK.MWeB3ebhJqfnEfzfizRHjQQx'),
(58, '22-0713', '1309220678', 'CARL JONAS', 'EBITNER', 'DELA CRUZ', '2025-04-28', 'Male', '09691750960', '', NULL, 'Valenzuela City', 'carlsanojaledcruz@gmail.com', 'DELA CRUZ, CARL JONAS EBITNER', '09691750960', 'carlsanojaledcruz@gmail.com', 'carl', 'Active', '2025-05-01 19:51:08', 'GOLD', '$2y$10$YPc7SCgH52PzpVqsyggKp.NBAYILDlwjldGqABVj.l4'),
(59, '22-3110', NULL, 'RED', 'BASON', 'DENISADO', '2014-02-26', 'Male', '09475014354', '', NULL, 'Valenzuela City', 'reddenisado534@gmail.com', 'DENISADO, RED BASON', '09475014354', 'reddenisado534@gmail.com', 'reddenisado', 'Active', '2025-05-01 19:52:46', 'GOLD', '$2y$10$yXpVfSPd9ZX4.9h2FS5nEOGkpd8YAw2nBwyvbCam3gC'),
(60, '22-0926', NULL, 'JAN ALFRED', 'RUFA', 'DOMINGO', '2012-01-02', 'Male', '09155047890', '', NULL, 'Valenzuela City', 'janalfreddomingo082004@gmail.com', 'DOMINGO, JAN ALFRED RUFA', '09155047890', 'janalfreddomingo082004@gmail.com', 'domingo', 'Active', '2025-05-01 19:54:07', 'GOLD', '$2y$10$TTtr6Gms25mi0kBc7pXfPujmIB64lFYPOQCDQHkZ3gR'),
(61, '22-2969', NULL, 'KRIS RAINIER', 'SEROY', 'ESTEBAN', '2014-01-30', 'Male', '09513129522', '', NULL, 'Valenzuela City', 'krisrainieresteban@gmail.com', 'ESTEBAN, KRIS RAINIER, SEROY', '09513129522', 'krisrainieresteban@gmail.com', 'resteban', 'Active', '2025-05-01 19:55:39', 'GOLD', '$2y$10$LAUZnfKo7S9XJdtSOgbUU.szjkQcLeHyjUN224i/8We'),
(62, '22-1025', NULL, 'CYMON EMANUELLE', 'ACUÑA', 'FLAMEÑO', '2014-06-02', 'Male', '09208444034', '', NULL, 'Valenzuela City', 'cymonflameno12@gmail.com', 'FLAMEÑO, CYMON EMANUELLE ACUÑA', '09208444034', 'cymonflameno12@gmail.com', 'flameno', 'Active', '2025-05-01 19:57:01', 'GOLD', '$2y$10$P4SkK/9DA9ZzM.y34sP44.H1TctVMCONvasHG/.WEJP'),
(63, '22-0025', NULL, 'SHAMANTHA MAE', 'VILLAMOR', 'FUENTES', '2017-02-15', 'Female', '09327830324', '', NULL, 'Valenzuela City', 'shammyfuentes@gmail.com', 'FUENTES, SHAMANTHA MAE VILLAMOR', '09327830324', 'shammyfuentes@gmail.com', 'fuentes', 'Active', '2025-05-01 19:59:27', 'GOLD', '$2y$10$BKPQ43vBlKIJy5RoAyPS4ejZN3MURjSNvmbUgsIzY3c'),
(64, '22-3238', NULL, 'GERINELLE', 'AGUINALDO', 'GABRILLO', '2015-02-11', 'Male', '09603902096', '', NULL, 'Valenzuela City', 'gerinellegabrillo028@gmail.com', 'GABRILLO, GERINELLE AGUINALDO', '09603902096', 'gerinellegabrillo028@gmail.com', 'gabrillo', 'Active', '2025-05-01 20:00:52', 'GOLD', '$2y$10$c.k7umfasOzT7xqP7H2mcO43m23lideMHlGFCoRgBrF'),
(65, '22-0151', '521515432', 'KIAN DAENIELLE', 'BUENTIPO', 'GARCIA', '2019-11-20', 'Male', '09301574609', '', NULL, 'Valenzuela City', 'garciakiandaenielle@gmail.com', 'GARCIA, KIAN DAENIELLE BUENTIPO', '09301574609', 'garciakiandaenielle@gmail.com', 'garciakian', 'Active', '2025-05-01 20:03:23', 'GOLD', '$2y$10$LAtm4E4H0ZGQo8qF1ULK7ePT6J9JzCoyFGF.A8E.591'),
(66, '22-3113', NULL, 'MARK JOSEPH', 'FERNANDEZ', 'HIPOLITO', '2019-07-11', 'Male', '09925436047', '', NULL, 'Valenzuela City', 'hipolitomarkjoseph29@gmail.com', 'HIPOLITO, MARK JOSEPH FERNANDEZ', '09925436047', 'hipolitomarkjoseph29@gmail.com', 'markjoseph', 'Active', '2025-05-01 20:04:43', 'GOLD', '$2y$10$HemDqJka.emy2/alLfM5U.xZ5VMtGyZad/mC7j7Km42'),
(68, '22-0124', NULL, 'LHEMUEL', 'PALERMO', 'LAURENCIANA', '2016-03-09', 'Male', '09055945827', '', NULL, 'Valenzuela City', 'laurencianamuel@gmail.com', 'LAURENCIANA, LHEMUEL PALERMO', '09055945827', 'laurencianamuel@gmail.com', 'laurenciana', 'Active', '2025-05-01 20:10:20', 'GOLD', '$2y$10$CjiT9zvRFEnib6yLofiImuUEmFts90FWF8b4CRL6r9i'),
(69, '22-1755', NULL, 'EMAR', 'FLORENCIO', 'LOZADA', '2017-02-02', 'Male', '09942133991', '', NULL, 'Valenzuela City', 'lozadaemcyy@gmail.com', 'LOZADA, EMAR FLORENCIO', '09942133991', 'lozadaemcyy@gmail.com', 'lozadae', 'Active', '2025-05-01 20:11:53', 'GOLD', '$2y$10$A/7PmFU8.4wZns3KuxIdGOANBJZnd1Zjj0WBzE5Vcb6'),
(70, '06516', NULL, 'marvin', 'marvin', 'marvin', '2025-05-14', 'Male', '510651603', '7', '', 'marvin', 'marvin@gmail.com', 'marvin', '2056102', 'marvin@gmail.com', 'marvin', 'Active', '2025-05-01 20:47:32', 'YOUTUBE', '$2y$10$Vv5ocIgP2iawsv8TMmiuue9kmicLcUC9zq89yhG.pMY'),
(74, '101909202303', NULL, 'Juliana', 'Libero', 'Cabaylo', '2025-02-03', 'Male', '09325520318', '', NULL, '5264 STARAPPLE STREET GEN. T. DE LEON', 'admin@gmail.com', 'sdqwdqw', 'dqwdqw', 'dqwdqw@gmail.com', 'canvas26', 'Active', '2025-05-15 09:41:19', 'GOLD', '$2y$10$jwvmb2rLE2aBZPiUF7LFi.70edNMomfYJpebyksrZ7A');

-- --------------------------------------------------------

--
-- Table structure for table `subjects`
--

CREATE TABLE `subjects` (
  `subject_id` int(11) NOT NULL,
  `subject_name` varchar(255) NOT NULL,
  `subject_picture` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `subjects`
--

INSERT INTO `subjects` (`subject_id`, `subject_name`, `subject_picture`, `created_at`) VALUES
(17, 'AP', 'ap.jpg', '2025-03-24 11:14:34'),
(18, 'Science', 'science.jpg', '2025-03-24 11:15:19'),
(19, 'Filipino', 'filipino.jpg', '2025-03-24 11:18:29'),
(21, 'science biology', 'calendar_747310.png', '2025-03-24 14:04:31'),
(24, 'science', '15538954589_734e94866f_h.jpg', '2025-03-26 01:12:55'),
(26, 'ewfwefgv', '481991918_3857668494501477_7232819675018823351_n.jpg', '2025-03-26 01:33:36'),
(27, 'ewfwefgv', '481991918_3857668494501477_7232819675018823351_n.jpg', '2025-03-26 01:33:36'),
(28, 'vfbtgnryne', 'uneviled 8-1 Productions  group  1.png', '2025-03-26 01:34:15');

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
(1, 'admin@gmail.com', '$2y$10$Jy3r22tFcA4RK9Wi9JmZEeUZQJlEirF1vlBq80HEGiqYabmC.ssQW', 'admin'),
(2, 'adviser@gmail.com', '$2y$10$3FZxu7DB2xIRXs7vcbWIY.V/ueTVBr4tZBuwgt0shNtAaQX5lKqpm', 'adviser'),
(3, 'student@gmail.com', '$2y$10$XF/wxlEdVl2EfdJ6sOxgNuqtdVSFMeIov6SS108FA3ny.l0BmDCAa', 'student'),
(45, 'jean@gmail.com', '$2y$10$pdQg6R9U.ovqZJVAkQfi2.tzTUxXi38GV62hug5sJBi6qFe0vhs7a', 'student'),
(46, 'castrudesjerry02@gmail.com', '$2y$10$8cKW4BERHsjezJYEtXP28e.7QJb3IRzGfYYij9nvJ4PLWEDtLZwki', 'student'),
(47, 'anchetakyla09@gmail.com', '$2y$10$4iZ30sMJ1tqr9nyQFlm4k.MPT4kcizu3Nx3OnUX0o2AbHbtewFczq', 'student'),
(48, 'destiny.balela31@gmail.com', '$2y$10$zCbdBXPBIApluna1H3SuXOO5IT8OCYE1Z9S46YBZethY8hhQHjxxK', 'student'),
(49, 'destiny.balela31@gmail.com', '$2y$10$B/HoKisUJEgVO3IqOHh8c.Omc3owOVr.KToZRkX5H5LA1HTeLoVCe', 'student'),
(50, 'silver.batacam@gmail.com', '$2y$10$sH7LXTNMA72LL6Ha9DOK6OzAkmSABR3UFjddUvqdqQ8UDu7VjvglG', 'student'),
(51, 'besingarosejean@gmail.com', '$2y$10$bY67oe8L6MdUHouq1rCKH.P.V0.skwtFVaQAD3vbI5CiXvMbYvJp2', 'student'),
(52, 'brillantesjohnkeneth@gmail.com', '$2y$10$7KHMxCoBYd5jKwih3idnHOpqprx.V05bWBARYaBjOb5AtNXX3etTC', 'student'),
(53, 'jkurtcaampued@gmail.com', '$2y$10$Au0zqxntB.W7Qpi1SRLO3ultPRDTejZ2nPiUKipt4qFUjtEumDzaa', 'student'),
(54, 'rogercabaylo.edu@gmail.com', '$2y$10$ENZMihSNzL0ciouQ4iK30eO3RbHqDMFVFI1SW1IydW24CLlc0Xip6', 'student'),
(55, 'castrudesjerry02@gmail.com', '$2y$10$4u2WMNzG9InDxziCakH0MeGdaOcwlqRUrQhZ3M.Cr3YVA5AhkZS4.', 'student'),
(56, 'karlodg32@gmail.com', '$2y$10$CA0gv9jDYActguMQ3f.I9ek5b7N3pL5cd/G708K2JGhuXgmPMULTq', 'student'),
(57, 'johnzelthegreat18@gmail.com', '$2y$10$pQY2QrmEn6rbCS5axK.MWeB3ebhJqfnEfzfizRHjQQxDQNthj0aKe', 'student'),
(58, 'carlsanojaledcruz@gmail.com', '$2y$10$YPc7SCgH52PzpVqsyggKp.NBAYILDlwjldGqABVj.l4dRoXmBTPWO', 'student'),
(59, 'reddenisado534@gmail.com', '$2y$10$yXpVfSPd9ZX4.9h2FS5nEOGkpd8YAw2nBwyvbCam3gCpLSwBOnv.i', 'student'),
(60, 'janalfreddomingo082004@gmail.com', '$2y$10$TTtr6Gms25mi0kBc7pXfPujmIB64lFYPOQCDQHkZ3gRvGjD7SSpjW', 'student'),
(61, 'krisrainieresteban@gmail.com', '$2y$10$LAUZnfKo7S9XJdtSOgbUU.szjkQcLeHyjUN224i/8WelZoEjqVLse', 'student'),
(62, 'cymonflameno12@gmail.com', '$2y$10$P4SkK/9DA9ZzM.y34sP44.H1TctVMCONvasHG/.WEJPeMuDvSADfa', 'student'),
(63, 'shammyfuentes@gmail.com', '$2y$10$BKPQ43vBlKIJy5RoAyPS4ejZN3MURjSNvmbUgsIzY3cUxqcOSi7Ym', 'student'),
(64, 'gerinellegabrillo028@gmail.com', '$2y$10$c.k7umfasOzT7xqP7H2mcO43m23lideMHlGFCoRgBrF76qYD8.LGC', 'student'),
(65, 'garciakiandaenielle@gmail.com', '$2y$10$LAtm4E4H0ZGQo8qF1ULK7ePT6J9JzCoyFGF.A8E.591IszWmkJ9B2', 'student'),
(66, 'hipolitomarkjoseph29@gmail.com', '$2y$10$HemDqJka.emy2/alLfM5U.xZ5VMtGyZad/mC7j7Km42WyijWCSZGm', 'student'),
(67, 'laurencianamuel@gmail.com', '$2y$10$TQxmt2EYvzpGMSHS53xqO.BdUJgpsIphCrRupQTvdTnAm1MJBkGoO', 'student'),
(68, 'laurencianamuel@gmail.com', '$2y$10$CjiT9zvRFEnib6yLofiImuUEmFts90FWF8b4CRL6r9itXYR2grIPy', 'student'),
(69, 'lozadaemcyy@gmail.com', '$2y$10$A/7PmFU8.4wZns3KuxIdGOANBJZnd1Zjj0WBzE5Vcb6QJU6.Dq2wO', 'student'),
(70, 'marvin@gmail.com', '$2y$10$Vv5ocIgP2iawsv8TMmiuue9kmicLcUC9zq89yhG.pMY1dt/dxET0e', 'student'),
(72, 'marvin@gmail.com', '$2y$10$X0zLpL0TWz8kWmAChNXe4O/JK1H30d1N6OS0K/12Q01y8cCIxmK6i', 'student'),
(73, 'copper@gmail.com', '$2y$10$rcgXgHPO9IGVG9eD9hkobO.Qo3/dKGFbsvAqaEbFelEAM2Zmv0bVu', 'adviser'),
(74, 'Edjelyn@gmail.com', '$2y$10$15BQ6O.joE/WX9kI7QGdI.CHcU7WrTBcBj8YWiuWqSQIS3XhGhbGO', 'adviser'),
(78, 'ella@gmail.com', '$2y$10$W6z.LCsuh0/ry75DzxmbpeHyFdKSh5rJ9YX3rdLR6FCNBGEXxE8mq', 'adviser'),
(79, 'ybrik@gmail.com', '$2y$10$07gisNTieHmB0UyM8VdI0e9gLUMBdtbmWl0pP38VcwqGBXDn53/8u', 'adviser'),
(80, 'admin@gmail.com', '$2y$10$jwvmb2rLE2aBZPiUF7LFi.70edNMomfYJpebyksrZ7AMHM/f1OMJW', 'student');

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
-- Indexes for table `final_grades`
--
ALTER TABLE `final_grades`
  ADD PRIMARY KEY (`final_grade_id`),
  ADD UNIQUE KEY `student_id` (`student_id`,`subject_id`,`school_year`),
  ADD KEY `subject_id` (`subject_id`);

--
-- Indexes for table `grades`
--
ALTER TABLE `grades`
  ADD PRIMARY KEY (`grade_id`),
  ADD UNIQUE KEY `student_id` (`student_id`,`subject_id`,`quarter_id`),
  ADD KEY `subject_id` (`subject_id`),
  ADD KEY `quarter_id` (`quarter_id`);

--
-- Indexes for table `mesages`
--
ALTER TABLE `mesages`
  ADD PRIMARY KEY (`msg_id`);

--
-- Indexes for table `quarters`
--
ALTER TABLE `quarters`
  ADD PRIMARY KEY (`quarter_id`);

--
-- Indexes for table `students`
--
ALTER TABLE `students`
  ADD PRIMARY KEY (`student_id`),
  ADD UNIQUE KEY `lrn` (`lrn`),
  ADD UNIQUE KEY `rfid_number` (`rfid_number`),
  ADD UNIQUE KEY `student_username` (`student_username`);

--
-- Indexes for table `subjects`
--
ALTER TABLE `subjects`
  ADD PRIMARY KEY (`subject_id`);

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
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=59;

--
-- AUTO_INCREMENT for table `attendance`
--
ALTER TABLE `attendance`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=198;

--
-- AUTO_INCREMENT for table `class_section`
--
ALTER TABLE `class_section`
  MODIFY `section_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;

--
-- AUTO_INCREMENT for table `mesages`
--
ALTER TABLE `mesages`
  MODIFY `msg_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `students`
--
ALTER TABLE `students`
  MODIFY `student_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=75;

--
-- AUTO_INCREMENT for table `subjects`
--
ALTER TABLE `subjects`
  MODIFY `subject_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT for table `user_acc`
--
ALTER TABLE `user_acc`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=81;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `final_grades`
--
ALTER TABLE `final_grades`
  ADD CONSTRAINT `final_grades_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `students` (`student_id`),
  ADD CONSTRAINT `final_grades_ibfk_2` FOREIGN KEY (`subject_id`) REFERENCES `subjects` (`subject_id`);

--
-- Constraints for table `grades`
--
ALTER TABLE `grades`
  ADD CONSTRAINT `grades_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `students` (`student_id`),
  ADD CONSTRAINT `grades_ibfk_2` FOREIGN KEY (`subject_id`) REFERENCES `subjects` (`subject_id`),
  ADD CONSTRAINT `grades_ibfk_3` FOREIGN KEY (`quarter_id`) REFERENCES `quarters` (`quarter_id`);
COMMIT;

-- Link for Subjects
ALTER TABLE subjects ADD COLUMN link VARCHAR(255);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;



-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: May 18, 2025 at 04:59 PM
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
(16, 'adviser', 'John Doe', '09123456789', 'johndoe@gmail.com', 'password', '10', 'COPPER'),
(18, 'adviser', 'Jesusa Saavedra', '09451202372', 'jesusa@gmail.com', 'jesusa123', '9', 'BRONZE'),
(19, 'adviser', 'Maria Josefa Cruz', '09761202372', 'maria@gmail.com', 'maria123', '7', 'GOLD'),
(51, 'adviser', 'metal prof', '06515656', 'fbsdbvsd@gmail.com', 'mvdjkvnjoklsd', '8', 'METAL'),
(52, 'adviser', 'copper proff', '0656232515', 'copper@gmail.com', 'copper123', '10', 'YOUTUBE'),
(53, 'adviser', 'Edjelyn Rose Tapac', '09605471308', 'Edjelyn@gmail.com', 'Edjelyn123', '8', 'NIKIEL'),
(57, 'adviser', 'Janella Marie Ragasajo', '09325520318', 'ella@gmail.com', 'ella123', '7', 'GOLD'),
(58, 'adviser', 'Johann Kirby Ragasajo', '09784563210', 'ybrik@gmail.com', '$2y$10$07gisNTieHmB0UyM8VdI0e9gLUMBdtbmWl0pP38VcwqGBXDn53/8u', '7', 'GOLD');

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
(183, '1601793334', 'JOHN KENETH', 'BRILLANTES', '17:52:44', '17:53:29', '2025-05-15'),
(184, '1309142790', 'JOHN ZEL', 'DE GUZMAN', '17:53:02', '17:53:25', '2025-05-15'),
(185, '1314269462', 'IAN CARL LUNAS', 'ALGABRE', '17:53:11', '17:53:22', '2025-05-15'),
(186, '1603665222', 'DESTINY', 'BALELA', '18:00:52', '18:02:09', '2025-05-15'),
(187, '1083328758', 'ROGER', 'CABAYLO', '18:01:09', '18:02:16', '2025-05-15'),
(188, '1309142790', 'JOHN ZEL', 'DE GUZMAN', '18:01:12', NULL, '2025-05-15'),
(189, '1308825062', 'JOHN KURT', 'CAAMPUED', '18:01:13', '18:01:51', '2025-05-15'),
(190, '1309220678', 'CARL JONAS', 'DELA CRUZ', '18:01:14', '18:02:13', '2025-05-15'),
(191, '1310097286', 'JERRY LIBERO', 'CASTRUDES', '18:01:22', '18:01:53', '2025-05-15'),
(192, '1601793334', 'JOHN KENETH', 'BRILLANTES', '18:01:46', NULL, '2025-05-15'),
(193, '1308396166', 'KHURSTEN BHRYLLE', 'BARREDO', '18:01:55', '18:01:56', '2025-05-15'),
(194, '1077729142', 'JOHN KARLO', 'DE GUZMAN', '18:02:00', NULL, '2025-05-15'),
(195, '1309142790', 'JOHN ZEL', 'DE GUZMAN', '18:02:03', NULL, '2025-05-15'),
(196, '1314269462', 'IAN CARL LUNAS', 'ALGABRE', '18:02:05', NULL, '2025-05-15'),
(197, '1601793334', 'JOHN KENETH', 'BRILLANTES', '18:02:18', NULL, '2025-05-15');

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

INSERT INTO `class_section` (`section_id`, `section_name`, `section_grade_level`, `section_year_start_level`, `section_year_end_level`) VALUES
(14, 'GOLD', '7', '2015', '2016'),
(16, 'BRONZE', '8', '2016', '2017'),
(17, 'DIAMOND', '9', '2017', '2018'),
(18, 'COPPER', '10', '2018', '2019'),
(19, 'METAL', '7', '2019', '2020'),
(20, 'ROBOT', '7', '2024', '2025'),
(21, 'PLATINUM', '7', '2024', '2025'),
(22, 'SILVER', '7', '2024', '2025'),
(25, 'NIKIEL', '7', '2024', '2025'),
(26, 'THINKVISION', '7', '2023', '2024'),
(36, 'GOOGLE', '7', '2025', '2023'),
(37, 'YOUTUBE', '10', '2025', '2026');

-- --------------------------------------------------------

--
-- Table structure for table `final_grades`
--

CREATE TABLE `final_grades` (
  `final_grade_id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `subject_id` int(11) NOT NULL,
  `school_year` varchar(20) DEFAULT NULL,
  `final_grade` decimal(5,2) NOT NULL,
  `remarks` varchar(255) DEFAULT NULL,
  `date_computed` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `grades`
--

CREATE TABLE `grades` (
  `grade_id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `subject_id` int(11) NOT NULL,
  `quarter_id` int(11) NOT NULL,
  `grade` decimal(5,2) NOT NULL,
  `remarks` varchar(255) DEFAULT NULL,
  `date_recorded` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `mesages`
--

CREATE TABLE `mesages` (
  `msg_id` int(11) NOT NULL,
  `incoming_msg_id` int(255) NOT NULL,
  `outgoing_msg_id` int(255) NOT NULL,
  `msg` varchar(1000) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `mesages`
--

INSERT INTO `mesages` (`msg_id`, `incoming_msg_id`, `outgoing_msg_id`, `msg`) VALUES
(1, 0, 0, 'Hello'),
(2, 0, 0, 'Hi'),
(3, 0, 0, 'Ha'),
(4, 0, 0, 'Ho'),
(5, 0, 0, 'Hu'),
(6, 0, 0, 'Wow'),
(7, 0, 0, 'Ba'),
(8, 0, 0, 'fsdfsdfsd'),
(9, 0, 0, 'dasdasda'),
(10, 0, 0, 'dsadas'),
(11, 0, 0, 'sadasd'),
(12, 0, 0, 'sadas');

-- --------------------------------------------------------

--
-- Table structure for table `quarters`
--

CREATE TABLE `quarters` (
  `quarter_id` int(11) NOT NULL,
  `quarter_name` varchar(20) NOT NULL,
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `school_year` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `quarters`
--

INSERT INTO `quarters` (`quarter_id`, `quarter_name`, `start_date`, `end_date`, `school_year`) VALUES
(1, 'Quarter 1', NULL, NULL, NULL),
(2, 'Quarter 2', NULL, NULL, NULL),
(3, 'Quarter 3', NULL, NULL, NULL),
(4, 'Quarter 4', NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `students`
--

CREATE TABLE `students` (
  `student_id` int(11) NOT NULL,
  `lrn` varchar(15) NOT NULL,
  `rfid_number` varchar(20) DEFAULT NULL,
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
  `section` varchar(255) DEFAULT NULL,
  `student_password` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `students`
--

INSERT INTO `students` (`student_id`, `lrn`, `rfid_number`, `first_name`, `middle_name`, `last_name`, `birthdate`, `sex`, `contact_number`, `grade_level`, `year_level`, `address`, `email`, `parent_guardian_name`, `parent_guardian_number`, `parent_guardian_email`, `student_username`, `status`, `created_at`, `section`, `student_password`) VALUES
(38, '22-0540', '', ' JEAN ANN ', 'PEÑAREDOND', 'ABAY', '2025-05-31', 'Female', '09155047985', '', NULL, 'Valenzuela City', 'jean@gmail.com', 'DE ASIS, MARK JOHN PARIÑAS', '09635471308', 'castrudesjerry02@gmail.com', '22-0540', 'Active', '2025-05-01 18:18:05', 'GOLD', '$2y$10$pdQg6R9U.ovqZJVAkQfi2.tzTUxXi38GV62hug5sJBi'),
(46, '22-3176', '1314269462', 'IAN CARL LUNAS', 'LUNAS', 'ALGABRE', '2025-02-01', 'Male', '09995009618', '', NULL, 'Valenzuela City', 'castrudesjerry02@gmail.com', 'ALGABRE, IAN CARL LUNAS', '09995009618', 'castrudesjerry02@gmail.com', 'algabre02', 'Active', '2025-05-01 19:25:53', 'GOLD', '$2y$10$8cKW4BERHsjezJYEtXP28e.7QJb3IRzGfYYij9nvJ4P'),
(47, '20-0103', NULL, 'KYLA', 'HERNANDEZ', 'ANCHETA', '2018-02-15', 'Female', '09270450956', '', NULL, 'Valenzuela City', 'anchetakyla09@gmail.com', 'ANCHETA, KYLA H.', '09270450956', 'anchetakyla09@gmail.com', 'anchetakyla09', 'Active', '2025-05-01 19:27:43', 'GOLD', '$2y$10$4iZ30sMJ1tqr9nyQFlm4k.MPT4kcizu3Nx3OnUX0o2A'),
(48, '22-0461', '1603665222', 'DESTINY', 'PEÑA', 'BALELA', '2020-07-24', 'Female', '09052595870', '', NULL, 'Valenzuela City', 'destiny.balela31@gmail.com', 'BALELA, DESTINY PEÑA', '09052595870', 'destiny.balela31@gmail.com', 'destiny.balela31', 'Active', '2025-05-01 19:34:26', 'GOLD', '$2y$10$zCbdBXPBIApluna1H3SuXOO5IT8OCYE1Z9S46YBZeth'),
(49, '22-2857', '1308396166', 'KHURSTEN BHRYLLE', 'PACLEB', 'BARREDO', '2015-03-26', 'Male', '09690640056', '', NULL, 'Valenzuela City', 'destiny.balela31@gmail.com', 'BARREDO, KHURSTEN BHRYLLE PACLEB', '09690640056', 'destiny.balela31@gmail.com', 'destiny', 'Active', '2025-05-01 19:36:03', 'GOLD', '$2y$10$B/HoKisUJEgVO3IqOHh8c.Omc3owOVr.KToZRkX5H5L'),
(50, '22-0188', '0', 'ALIJANDRO', 'MAGLENTE', 'BATAC', '2012-07-18', 'Male', '09971154714', '', NULL, 'Valenzuela City', 'silver.batacam@gmail.com', 'BATAC, ALIJANDRO MAGLENTE', '09971154714', 'silver.batacam@gmail.com', 'batacam', 'Active', '2025-05-01 19:37:18', 'GOLD', '$2y$10$sH7LXTNMA72LL6Ha9DOK6OzAkmSABR3UFjddUvqdqQ8'),
(51, '22-1458', NULL, 'ROSE JEAN', '', 'BESINGA', '2017-07-06', 'Female', '09949129289', '', NULL, 'Valenzuela City', 'besingarosejean@gmail.com', 'BESINGA, ROSE JEAN, COMPARATIVO', '09949129289', 'besingarosejean@gmail.com', 'besingarosejean', 'Active', '2025-05-01 19:38:48', 'GOLD', '$2y$10$bY67oe8L6MdUHouq1rCKH.P.V0.skwtFVaQAD3vbI5C'),
(52, '22-2638', '1601793334', 'JOHN KENETH', 'GAMATERO', 'BRILLANTES', '2017-07-13', 'Male', '09273307778', '7', '', 'Valenzuela City', 'brillantesjohnkeneth@gmail.com', 'BRILLANTES, JOHN KENETH GAMATERO', '09273307778', 'brillantesjohnkeneth@gmail.com', 'brillantes', 'Active', '2025-05-01 19:40:32', 'GOLD', '$2y$10$7KHMxCoBYd5jKwih3idnHOpqprx.V05bWBARYaBjOb5'),
(53, '22-2702', '1308825062', 'JOHN KURT', 'MORALES', 'CAAMPUED', '2018-07-14', 'Male', '09355329909', '', NULL, 'Valenzuela City', 'jkurtcaampued@gmail.com', 'CAAMPUED, JOHN KURT MORALES', '09355329909', 'jkurtcaampued@gmail.com', 'jkurtcaampued', 'Active', '2025-05-01 19:42:32', 'GOLD', '$2y$10$Au0zqxntB.W7Qpi1SRLO3ultPRDTejZ2nPiUKipt4qF'),
(54, '22-2716', '1083328758', 'ROGER', 'ESPINA', 'CABAYLO', '2014-02-02', 'Male', '09634979322', '', NULL, 'Valenzuela City', 'rogercabaylo.edu@gmail.com', 'CABAYLO, ROGER ESPINA', '09634979322', 'rogercabaylo.edu@gmail.com', 'rogercabaylo', 'Active', '2025-05-01 19:44:17', 'GOLD', '$2y$10$ENZMihSNzL0ciouQ4iK30eO3RbHqDMFVFI1SW1IydW2'),
(55, '22-0782', '1310097286', 'JERRY LIBERO', 'LIBERO', 'CASTRUDES', '2009-03-04', 'Male', '09065060366', '', NULL, 'Valenzuela City', 'castrudesjerry02@gmail.com', 'CASTRUDES, JERRY LIBERO', '09065060366', 'castrudesjerry02@gmail.com', 'castrudesjerry', 'Active', '2025-05-01 19:45:58', 'GOLD', '$2y$10$4u2WMNzG9InDxziCakH0MeGdaOcwlqRUrQhZ3M.Cr3Y'),
(56, '22-0654', '1077729142', 'JOHN KARLO', 'ELNAS', 'DE GUZMAN', '2012-07-19', 'Male', '09982506364', '7', '', 'Valenzuela City', 'karlodg32@gmail.com', 'DE GUZMAN, JOHN KARLO ELNAS', '09982506364', 'karlodg32@gmail.com', 'karlodg', 'Active', '2025-05-01 19:47:46', 'GOLD', '$2y$10$CA0gv9jDYActguMQ3f.I9ek5b7N3pL5cd/G708K2JGh'),
(57, '22-0651', '1309142790', 'JOHN ZEL', 'DORADO', 'DE GUZMAN', '2016-02-09', 'Male', '09069129374', '', NULL, 'Valenzuela City', 'johnzelthegreat18@gmail.com', 'DE GUZMAN, JOHN ZEL DORADO', '09069129374', 'johnzelthegreat18@gmail.com', 'johnzel', 'Active', '2025-05-01 19:49:14', 'GOLD', '$2y$10$pQY2QrmEn6rbCS5axK.MWeB3ebhJqfnEfzfizRHjQQx'),
(58, '22-0713', '1309220678', 'CARL JONAS', 'EBITNER', 'DELA CRUZ', '2025-04-28', 'Male', '09691750960', '', NULL, 'Valenzuela City', 'carlsanojaledcruz@gmail.com', 'DELA CRUZ, CARL JONAS EBITNER', '09691750960', 'carlsanojaledcruz@gmail.com', 'carl', 'Active', '2025-05-01 19:51:08', 'GOLD', '$2y$10$YPc7SCgH52PzpVqsyggKp.NBAYILDlwjldGqABVj.l4'),
(59, '22-3110', NULL, 'RED', 'BASON', 'DENISADO', '2014-02-26', 'Male', '09475014354', '', NULL, 'Valenzuela City', 'reddenisado534@gmail.com', 'DENISADO, RED BASON', '09475014354', 'reddenisado534@gmail.com', 'reddenisado', 'Active', '2025-05-01 19:52:46', 'GOLD', '$2y$10$yXpVfSPd9ZX4.9h2FS5nEOGkpd8YAw2nBwyvbCam3gC'),
(60, '22-0926', NULL, 'JAN ALFRED', 'RUFA', 'DOMINGO', '2012-01-02', 'Male', '09155047890', '', NULL, 'Valenzuela City', 'janalfreddomingo082004@gmail.com', 'DOMINGO, JAN ALFRED RUFA', '09155047890', 'janalfreddomingo082004@gmail.com', 'domingo', 'Active', '2025-05-01 19:54:07', 'GOLD', '$2y$10$TTtr6Gms25mi0kBc7pXfPujmIB64lFYPOQCDQHkZ3gR'),
(61, '22-2969', NULL, 'KRIS RAINIER', 'SEROY', 'ESTEBAN', '2014-01-30', 'Male', '09513129522', '', NULL, 'Valenzuela City', 'krisrainieresteban@gmail.com', 'ESTEBAN, KRIS RAINIER, SEROY', '09513129522', 'krisrainieresteban@gmail.com', 'resteban', 'Active', '2025-05-01 19:55:39', 'GOLD', '$2y$10$LAUZnfKo7S9XJdtSOgbUU.szjkQcLeHyjUN224i/8We'),
(62, '22-1025', NULL, 'CYMON EMANUELLE', 'ACUÑA', 'FLAMEÑO', '2014-06-02', 'Male', '09208444034', '', NULL, 'Valenzuela City', 'cymonflameno12@gmail.com', 'FLAMEÑO, CYMON EMANUELLE ACUÑA', '09208444034', 'cymonflameno12@gmail.com', 'flameno', 'Active', '2025-05-01 19:57:01', 'GOLD', '$2y$10$P4SkK/9DA9ZzM.y34sP44.H1TctVMCONvasHG/.WEJP'),
(63, '22-0025', NULL, 'SHAMANTHA MAE', 'VILLAMOR', 'FUENTES', '2017-02-15', 'Female', '09327830324', '', NULL, 'Valenzuela City', 'shammyfuentes@gmail.com', 'FUENTES, SHAMANTHA MAE VILLAMOR', '09327830324', 'shammyfuentes@gmail.com', 'fuentes', 'Active', '2025-05-01 19:59:27', 'GOLD', '$2y$10$BKPQ43vBlKIJy5RoAyPS4ejZN3MURjSNvmbUgsIzY3c'),
(64, '22-3238', NULL, 'GERINELLE', 'AGUINALDO', 'GABRILLO', '2015-02-11', 'Male', '09603902096', '', NULL, 'Valenzuela City', 'gerinellegabrillo028@gmail.com', 'GABRILLO, GERINELLE AGUINALDO', '09603902096', 'gerinellegabrillo028@gmail.com', 'gabrillo', 'Active', '2025-05-01 20:00:52', 'GOLD', '$2y$10$c.k7umfasOzT7xqP7H2mcO43m23lideMHlGFCoRgBrF'),
(65, '22-0151', '521515432', 'KIAN DAENIELLE', 'BUENTIPO', 'GARCIA', '2019-11-20', 'Male', '09301574609', '', NULL, 'Valenzuela City', 'garciakiandaenielle@gmail.com', 'GARCIA, KIAN DAENIELLE BUENTIPO', '09301574609', 'garciakiandaenielle@gmail.com', 'garciakian', 'Active', '2025-05-01 20:03:23', 'GOLD', '$2y$10$LAtm4E4H0ZGQo8qF1ULK7ePT6J9JzCoyFGF.A8E.591'),
(66, '22-3113', NULL, 'MARK JOSEPH', 'FERNANDEZ', 'HIPOLITO', '2019-07-11', 'Male', '09925436047', '', NULL, 'Valenzuela City', 'hipolitomarkjoseph29@gmail.com', 'HIPOLITO, MARK JOSEPH FERNANDEZ', '09925436047', 'hipolitomarkjoseph29@gmail.com', 'markjoseph', 'Active', '2025-05-01 20:04:43', 'GOLD', '$2y$10$HemDqJka.emy2/alLfM5U.xZ5VMtGyZad/mC7j7Km42'),
(68, '22-0124', NULL, 'LHEMUEL', 'PALERMO', 'LAURENCIANA', '2016-03-09', 'Male', '09055945827', '', NULL, 'Valenzuela City', 'laurencianamuel@gmail.com', 'LAURENCIANA, LHEMUEL PALERMO', '09055945827', 'laurencianamuel@gmail.com', 'laurenciana', 'Active', '2025-05-01 20:10:20', 'GOLD', '$2y$10$CjiT9zvRFEnib6yLofiImuUEmFts90FWF8b4CRL6r9i'),
(69, '22-1755', NULL, 'EMAR', 'FLORENCIO', 'LOZADA', '2017-02-02', 'Male', '09942133991', '', NULL, 'Valenzuela City', 'lozadaemcyy@gmail.com', 'LOZADA, EMAR FLORENCIO', '09942133991', 'lozadaemcyy@gmail.com', 'lozadae', 'Active', '2025-05-01 20:11:53', 'GOLD', '$2y$10$A/7PmFU8.4wZns3KuxIdGOANBJZnd1Zjj0WBzE5Vcb6'),
(70, '06516', NULL, 'marvin', 'marvin', 'marvin', '2025-05-14', 'Male', '510651603', '7', '', 'marvin', 'marvin@gmail.com', 'marvin', '2056102', 'marvin@gmail.com', 'marvin', 'Active', '2025-05-01 20:47:32', 'YOUTUBE', '$2y$10$Vv5ocIgP2iawsv8TMmiuue9kmicLcUC9zq89yhG.pMY'),
(74, '101909202303', NULL, 'Juliana', 'Libero', 'Cabaylo', '2025-02-03', 'Male', '09325520318', '', NULL, '5264 STARAPPLE STREET GEN. T. DE LEON', 'admin@gmail.com', 'sdqwdqw', 'dqwdqw', 'dqwdqw@gmail.com', 'canvas26', 'Active', '2025-05-15 09:41:19', 'GOLD', '$2y$10$jwvmb2rLE2aBZPiUF7LFi.70edNMomfYJpebyksrZ7A');

-- --------------------------------------------------------

--
-- Table structure for table `subjects`
--

CREATE TABLE `subjects` (
  `subject_id` int(11) NOT NULL,
  `subject_name` varchar(255) NOT NULL,
  `subject_picture` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `subjects`
--

INSERT INTO `subjects` (`subject_id`, `subject_name`, `subject_picture`, `created_at`) VALUES
(17, 'AP', 'ap.jpg', '2025-03-24 11:14:34'),
(18, 'Science', 'science.jpg', '2025-03-24 11:15:19'),
(19, 'Filipino', 'filipino.jpg', '2025-03-24 11:18:29'),
(21, 'science biology', 'calendar_747310.png', '2025-03-24 14:04:31'),
(24, 'science', '15538954589_734e94866f_h.jpg', '2025-03-26 01:12:55'),
(26, 'ewfwefgv', '481991918_3857668494501477_7232819675018823351_n.jpg', '2025-03-26 01:33:36'),
(27, 'ewfwefgv', '481991918_3857668494501477_7232819675018823351_n.jpg', '2025-03-26 01:33:36'),
(28, 'vfbtgnryne', 'uneviled 8-1 Productions  group  1.png', '2025-03-26 01:34:15');

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
(1, 'admin@gmail.com', '$2y$10$Jy3r22tFcA4RK9Wi9JmZEeUZQJlEirF1vlBq80HEGiqYabmC.ssQW', 'admin'),
(2, 'adviser@gmail.com', '$2y$10$3FZxu7DB2xIRXs7vcbWIY.V/ueTVBr4tZBuwgt0shNtAaQX5lKqpm', 'adviser'),
(3, 'student@gmail.com', '$2y$10$XF/wxlEdVl2EfdJ6sOxgNuqtdVSFMeIov6SS108FA3ny.l0BmDCAa', 'student'),
(45, 'jean@gmail.com', '$2y$10$pdQg6R9U.ovqZJVAkQfi2.tzTUxXi38GV62hug5sJBi6qFe0vhs7a', 'student'),
(46, 'castrudesjerry02@gmail.com', '$2y$10$8cKW4BERHsjezJYEtXP28e.7QJb3IRzGfYYij9nvJ4PLWEDtLZwki', 'student'),
(47, 'anchetakyla09@gmail.com', '$2y$10$4iZ30sMJ1tqr9nyQFlm4k.MPT4kcizu3Nx3OnUX0o2AbHbtewFczq', 'student'),
(48, 'destiny.balela31@gmail.com', '$2y$10$zCbdBXPBIApluna1H3SuXOO5IT8OCYE1Z9S46YBZethY8hhQHjxxK', 'student'),
(49, 'destiny.balela31@gmail.com', '$2y$10$B/HoKisUJEgVO3IqOHh8c.Omc3owOVr.KToZRkX5H5LA1HTeLoVCe', 'student'),
(50, 'silver.batacam@gmail.com', '$2y$10$sH7LXTNMA72LL6Ha9DOK6OzAkmSABR3UFjddUvqdqQ8UDu7VjvglG', 'student'),
(51, 'besingarosejean@gmail.com', '$2y$10$bY67oe8L6MdUHouq1rCKH.P.V0.skwtFVaQAD3vbI5CiXvMbYvJp2', 'student'),
(52, 'brillantesjohnkeneth@gmail.com', '$2y$10$7KHMxCoBYd5jKwih3idnHOpqprx.V05bWBARYaBjOb5AtNXX3etTC', 'student'),
(53, 'jkurtcaampued@gmail.com', '$2y$10$Au0zqxntB.W7Qpi1SRLO3ultPRDTejZ2nPiUKipt4qFUjtEumDzaa', 'student'),
(54, 'rogercabaylo.edu@gmail.com', '$2y$10$ENZMihSNzL0ciouQ4iK30eO3RbHqDMFVFI1SW1IydW24CLlc0Xip6', 'student'),
(55, 'castrudesjerry02@gmail.com', '$2y$10$4u2WMNzG9InDxziCakH0MeGdaOcwlqRUrQhZ3M.Cr3YVA5AhkZS4.', 'student'),
(56, 'karlodg32@gmail.com', '$2y$10$CA0gv9jDYActguMQ3f.I9ek5b7N3pL5cd/G708K2JGhuXgmPMULTq', 'student'),
(57, 'johnzelthegreat18@gmail.com', '$2y$10$pQY2QrmEn6rbCS5axK.MWeB3ebhJqfnEfzfizRHjQQxDQNthj0aKe', 'student'),
(58, 'carlsanojaledcruz@gmail.com', '$2y$10$YPc7SCgH52PzpVqsyggKp.NBAYILDlwjldGqABVj.l4dRoXmBTPWO', 'student'),
(59, 'reddenisado534@gmail.com', '$2y$10$yXpVfSPd9ZX4.9h2FS5nEOGkpd8YAw2nBwyvbCam3gCpLSwBOnv.i', 'student'),
(60, 'janalfreddomingo082004@gmail.com', '$2y$10$TTtr6Gms25mi0kBc7pXfPujmIB64lFYPOQCDQHkZ3gRvGjD7SSpjW', 'student'),
(61, 'krisrainieresteban@gmail.com', '$2y$10$LAUZnfKo7S9XJdtSOgbUU.szjkQcLeHyjUN224i/8WelZoEjqVLse', 'student'),
(62, 'cymonflameno12@gmail.com', '$2y$10$P4SkK/9DA9ZzM.y34sP44.H1TctVMCONvasHG/.WEJPeMuDvSADfa', 'student'),
(63, 'shammyfuentes@gmail.com', '$2y$10$BKPQ43vBlKIJy5RoAyPS4ejZN3MURjSNvmbUgsIzY3cUxqcOSi7Ym', 'student'),
(64, 'gerinellegabrillo028@gmail.com', '$2y$10$c.k7umfasOzT7xqP7H2mcO43m23lideMHlGFCoRgBrF76qYD8.LGC', 'student'),
(65, 'garciakiandaenielle@gmail.com', '$2y$10$LAtm4E4H0ZGQo8qF1ULK7ePT6J9JzCoyFGF.A8E.591IszWmkJ9B2', 'student'),
(66, 'hipolitomarkjoseph29@gmail.com', '$2y$10$HemDqJka.emy2/alLfM5U.xZ5VMtGyZad/mC7j7Km42WyijWCSZGm', 'student'),
(67, 'laurencianamuel@gmail.com', '$2y$10$TQxmt2EYvzpGMSHS53xqO.BdUJgpsIphCrRupQTvdTnAm1MJBkGoO', 'student'),
(68, 'laurencianamuel@gmail.com', '$2y$10$CjiT9zvRFEnib6yLofiImuUEmFts90FWF8b4CRL6r9itXYR2grIPy', 'student'),
(69, 'lozadaemcyy@gmail.com', '$2y$10$A/7PmFU8.4wZns3KuxIdGOANBJZnd1Zjj0WBzE5Vcb6QJU6.Dq2wO', 'student'),
(70, 'marvin@gmail.com', '$2y$10$Vv5ocIgP2iawsv8TMmiuue9kmicLcUC9zq89yhG.pMY1dt/dxET0e', 'student'),
(72, 'marvin@gmail.com', '$2y$10$X0zLpL0TWz8kWmAChNXe4O/JK1H30d1N6OS0K/12Q01y8cCIxmK6i', 'student'),
(73, 'copper@gmail.com', '$2y$10$rcgXgHPO9IGVG9eD9hkobO.Qo3/dKGFbsvAqaEbFelEAM2Zmv0bVu', 'adviser'),
(74, 'Edjelyn@gmail.com', '$2y$10$15BQ6O.joE/WX9kI7QGdI.CHcU7WrTBcBj8YWiuWqSQIS3XhGhbGO', 'adviser'),
(78, 'ella@gmail.com', '$2y$10$W6z.LCsuh0/ry75DzxmbpeHyFdKSh5rJ9YX3rdLR6FCNBGEXxE8mq', 'adviser'),
(79, 'ybrik@gmail.com', '$2y$10$07gisNTieHmB0UyM8VdI0e9gLUMBdtbmWl0pP38VcwqGBXDn53/8u', 'adviser'),
(80, 'admin@gmail.com', '$2y$10$jwvmb2rLE2aBZPiUF7LFi.70edNMomfYJpebyksrZ7AMHM/f1OMJW', 'student');

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
-- Indexes for table `final_grades`
--
ALTER TABLE `final_grades`
  ADD PRIMARY KEY (`final_grade_id`),
  ADD UNIQUE KEY `student_id` (`student_id`,`subject_id`,`school_year`),
  ADD KEY `subject_id` (`subject_id`);

--
-- Indexes for table `grades`
--
ALTER TABLE `grades`
  ADD PRIMARY KEY (`grade_id`),
  ADD UNIQUE KEY `student_id` (`student_id`,`subject_id`,`quarter_id`),
  ADD KEY `subject_id` (`subject_id`),
  ADD KEY `quarter_id` (`quarter_id`);

--
-- Indexes for table `mesages`
--
ALTER TABLE `mesages`
  ADD PRIMARY KEY (`msg_id`);

--
-- Indexes for table `quarters`
--
ALTER TABLE `quarters`
  ADD PRIMARY KEY (`quarter_id`);

--
-- Indexes for table `students`
--
ALTER TABLE `students`
  ADD PRIMARY KEY (`student_id`),
  ADD UNIQUE KEY `lrn` (`lrn`),
  ADD UNIQUE KEY `rfid_number` (`rfid_number`),
  ADD UNIQUE KEY `student_username` (`student_username`);

--
-- Indexes for table `subjects`
--
ALTER TABLE `subjects`
  ADD PRIMARY KEY (`subject_id`);

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
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=59;

--
-- AUTO_INCREMENT for table `attendance`
--
ALTER TABLE `attendance`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=198;

--
-- AUTO_INCREMENT for table `class_section`
--
ALTER TABLE `class_section`
  MODIFY `section_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;

--
-- AUTO_INCREMENT for table `mesages`
--
ALTER TABLE `mesages`
  MODIFY `msg_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `students`
--
ALTER TABLE `students`
  MODIFY `student_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=75;

--
-- AUTO_INCREMENT for table `subjects`
--
ALTER TABLE `subjects`
  MODIFY `subject_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT for table `user_acc`
--
ALTER TABLE `user_acc`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=81;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `final_grades`
--
ALTER TABLE `final_grades`
  ADD CONSTRAINT `final_grades_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `students` (`student_id`),
  ADD CONSTRAINT `final_grades_ibfk_2` FOREIGN KEY (`subject_id`) REFERENCES `subjects` (`subject_id`);

--
-- Constraints for table `grades`
--
ALTER TABLE `grades`
  ADD CONSTRAINT `grades_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `students` (`student_id`),
  ADD CONSTRAINT `grades_ibfk_2` FOREIGN KEY (`subject_id`) REFERENCES `subjects` (`subject_id`),
  ADD CONSTRAINT `grades_ibfk_3` FOREIGN KEY (`quarter_id`) REFERENCES `quarters` (`quarter_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
