-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 19, 2025 at 10:05 PM
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
-- Database: `quiatsonclinic`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin_staff`
--

CREATE TABLE `admin_staff` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `role` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `date_created` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin_staff`
--

INSERT INTO `admin_staff` (`id`, `username`, `email`, `role`, `password`, `date_created`) VALUES
(1, 'hyacynth', 'hyacynth.mulaveintern@gmail.com', 'Clinic Assistant', '$2y$10$CwhpZsDPewy247qgBRWS0.r91MFnDKWyGVXEZlQg2MdMEIPzvA8ci', '2025-05-02 00:14:54');

-- --------------------------------------------------------

--
-- Table structure for table `appointments`
--

CREATE TABLE `appointments` (
  `id` int(11) NOT NULL,
  `patient_id` varchar(255) NOT NULL,
  `severity` varchar(150) NOT NULL,
  `lastname` varchar(50) DEFAULT NULL,
  `firstname` varchar(50) DEFAULT NULL,
  `middle_initial` char(1) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `age` int(11) DEFAULT NULL,
  `sex` enum('Male','Female') DEFAULT NULL,
  `birthdate` date DEFAULT NULL,
  `civil_status` varchar(20) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `weight` varchar(10) DEFAULT NULL,
  `height` varchar(10) DEFAULT NULL,
  `bloodtype` varchar(3) DEFAULT NULL,
  `appointment_date` date DEFAULT NULL,
  `time_slot` varchar(50) DEFAULT NULL,
  `symptom` varchar(100) DEFAULT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'Pending',
  `remarks` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `appointments`
--

INSERT INTO `appointments` (`id`, `patient_id`, `severity`, `lastname`, `firstname`, `middle_initial`, `address`, `age`, `sex`, `birthdate`, `civil_status`, `phone`, `weight`, `height`, `bloodtype`, `appointment_date`, `time_slot`, `symptom`, `status`, `remarks`, `created_at`) VALUES
(1, '05012025-01', 'Regular', 'asdas', 'sadasd', 's', 'sadsad', 24, 'Male', '2001-05-07', 'Single', '09898390281', '50', '150', 'A-', '2025-05-17', '10:30 AM - 11:30 AM', 'Fever', 'Approved', '', '2025-05-01 10:26:12'),
(2, '05012025-02', 'Regular', 'asdas', 'sadasd', 's', 'sadsad', 24, 'Male', '2001-05-07', 'Single', '09898390281', '50', '150', 'A-', '2025-05-17', '10:30 AM - 11:30 AM', 'Fever', 'Pending', '', '2025-05-01 10:27:12'),
(3, '05182025-03', 'Regular', 'akjdkj', 'lkjkldjaskl', 'k', 'kjsakdj', 15, 'Male', '2000-01-04', 'Single', '90909090090', '50', '150', 'A+', '2025-05-19', '8:30 AM - 9:30 AM', 'Chest Pain (Moderate to\\r\\n                                        severe)', 'Pending', '', '2025-05-18 03:54:39'),
(4, '05182025-04', 'Urgent', 'sajhjdkjhsa', 'jhsjdh', 'k', 'jkasjdjsd', 15, 'Male', '2002-05-09', 'Single', '90909090090', '50', '162', 'B+', '2025-05-28', '9:30 AM - 10:30 AM', 'Abdominal Pain (Moderate to severe)', 'Pending', '', '2025-05-18 04:25:58'),
(5, '05182025-05', 'Regular', 'ahsdhjah', 'hasdhasg', 'H', 'asmndhkjasd', 15, 'Male', '2002-05-09', 'Single', '90909090090', '50', '162', 'B+', '2025-05-28', '9:30 AM - 10:30 AM', 'Abdominal Pain (Moderate to severe)', 'Pending', '', '2025-05-18 04:27:32'),
(6, '05182025-06', 'Urgent', 'ahsdhjah', 'hasdhasg', 'H', 'a,s.dma.', 15, 'Male', '2002-05-09', 'Single', '09090990', '50', '162', 'B+', '2025-05-28', '9:30 AM - 10:30 AM', 'Toxic Looking', 'Approved', '', '2025-05-20 04:27:58');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin_staff`
--
ALTER TABLE `admin_staff`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `appointments`
--
ALTER TABLE `appointments`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin_staff`
--
ALTER TABLE `admin_staff`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `appointments`
--
ALTER TABLE `appointments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
