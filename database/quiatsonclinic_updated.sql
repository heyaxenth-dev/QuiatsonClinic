-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 04, 2025 at 10:23 AM
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
  `patient_type` varchar(50) NOT NULL,
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
  `uploaded_id` varchar(255) NOT NULL,
  `status` varchar(255) NOT NULL,
  `remarks` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `appointments`
--

INSERT INTO `appointments` (`id`, `patient_id`, `patient_type`, `severity`, `lastname`, `firstname`, `middle_initial`, `address`, `age`, `sex`, `birthdate`, `civil_status`, `phone`, `weight`, `height`, `bloodtype`, `appointment_date`, `time_slot`, `symptom`, `uploaded_id`, `status`, `remarks`, `created_at`) VALUES
(1, '09042025-01', 'senior', 'Urgent', 'hasdhja', 'dhjasgdhjg', 'h', '0', 65, 'Female', '1955-12-25', 'Married', '09651168472', '50', '150', 'B+', '2025-09-05', '9:30 AM - 10:30 AM', 'Chest Pain', 'uploads/uploaded_ids/uploaded_id_68b949d77dce47.56443506.jpg', 'Approved', '', '2025-09-04 08:12:07');

-- --------------------------------------------------------

--
-- Table structure for table `client`
--

CREATE TABLE `client` (
  `id` int(11) NOT NULL,
  `firstname` varchar(255) NOT NULL,
  `lastname` varchar(255) NOT NULL,
  `mobile_no` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `dob` varchar(255) NOT NULL,
  `sex` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `address` varchar(255) NOT NULL,
  `password_token` varchar(255) NOT NULL,
  `verification_status` int(11) NOT NULL DEFAULT 0,
  `created_at` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `client`
--

INSERT INTO `client` (`id`, `firstname`, `lastname`, `mobile_no`, `email`, `dob`, `sex`, `password`, `address`, `password_token`, `verification_status`, `created_at`) VALUES
(1, 'Hya Cynth', 'Dojillo', '09651168472', 'hyacynth.mulaveintern@gmail.com', '2000-08-04', 'Female', '$2y$10$xxE.Nn/y6jacC9IZ5vTn.O2f23BDlt/xXCXczJKNDEABJCMzSuwwO', 'Kksajdksajd', '', 0, ''),
(2, 'asjdhjkas', 'hsdsakjdhk', '09651168472', 'hyacynth.dev@gmail.com', '2000-09-13', 'Female', '$2y$10$M0BdCeRmvWNKn8rOJb8vRephKRXNS83S4SsaMVUSXUNq5EMUnYcb6', 'jkashdjas\\r\\n', '', 0, '');

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
-- Indexes for table `client`
--
ALTER TABLE `client`
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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `client`
--
ALTER TABLE `client`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
