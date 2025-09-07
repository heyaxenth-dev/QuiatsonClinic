-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 07, 2025 at 02:48 PM
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
  `mobile_no` varchar(255) NOT NULL,
  `role` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `password_token` varchar(255) NOT NULL,
  `date_created` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin_staff`
--

INSERT INTO `admin_staff` (`id`, `username`, `email`, `mobile_no`, `role`, `password`, `password_token`, `date_created`) VALUES
(1, 'hyacynth', 'hyacynth.mulaveintern@gmail.com', '', 'Clinic Assistant', '$2y$10$CwhpZsDPewy247qgBRWS0.r91MFnDKWyGVXEZlQg2MdMEIPzvA8ci', '', '2025-05-02 00:14:54');

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
(1, '09042025-01', 'senior_pwd', 'Urgent', 'hasdhja', 'dhjasgdhjg', 'h', 'jdahjds4', 65, 'Female', '1955-12-25', 'Married', '09651168472', '50', '150', 'B+', '2025-09-05', '9:30 AM - 10:30 AM', 'Chest Pain', 'uploads/uploaded_ids/uploaded_id_68b949d77dce47.56443506.jpg', 'Concluded', 'Patient: dhjasgdhjg h. hasdhja\nAge: 65\nSex: Female\nAddress: jdahjds4\nCivil Status: Married\nPhone: 09651168472\nSchedule: Friday September 5, 2025 9:30 AM - 10:30 AM\nRemarks: Testing the remarks form', '2025-09-04 08:12:07'),
(2, '09042025-02', 'regular', 'Regular', 'ahdjhasd', 'jsahdjh', 'j', 'ajhksdjas', 25, 'Female', '2000-08-04', 'Single', '09651168472', '50', '150', 'A+', '2025-09-12', '10:30 AM - 11:30 AM', 'Fever', '', 'Rescheduled', '', '2025-09-04 08:54:05'),
(3, '09042025-03', 'regular', 'Regular', 'ahdjhasd', 'jsahdjh', 'j', 'shjdgahjsd', 25, 'Female', '2000-08-04', 'Single', '09651168472', '50', '150', 'A+', '2025-09-05', '10:30 AM - 11:30 AM', 'Fever', '', 'Approved', '', '2025-09-04 08:55:49'),
(4, '09052025-04', 'senior_pwd', 'Regular', 'Dojillo', 'Hya Cynth', 'G', 'Villavert-Jimenez, Hamtic, Antique', 25, 'Female', '2000-08-04', 'Single', '09651168472', '65', '150', 'A+', '2025-09-08', '11:30 AM - 12:30 PM', 'Cough', '', 'Approved', '', '2025-09-05 08:26:05');

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
(1, 'Hya Cynth', 'Dojillo', '09651168472', 'hyacynth.mulaveintern@gmail.com', '2000-08-04', 'Female', '$2y$10$khUtiE09.R9ZMLVVoaK1A.uEw/siTheQ9p6FLgpbeHHHLJ8NX8rge', 'Kksajdksajd', '', 0, ''),
(2, 'asjdhjkas', 'hsdsakjdhk', '09651168472', 'hyacynth.dev@gmail.com', '2000-09-13', 'Female', '$2y$10$M0BdCeRmvWNKn8rOJb8vRephKRXNS83S4SsaMVUSXUNq5EMUnYcb6', 'jkashdjas\\r\\n', '', 0, '');

-- --------------------------------------------------------

--
-- Table structure for table `lab_results`
--

CREATE TABLE `lab_results` (
  `id` int(11) NOT NULL,
  `appointment_id` int(11) NOT NULL,
  `patient_id` varchar(255) NOT NULL,
  `file_path` varchar(500) NOT NULL,
  `original_name` varchar(255) DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `uploaded_by` int(11) DEFAULT NULL,
  `uploaded_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `lab_results`
--

INSERT INTO `lab_results` (`id`, `appointment_id`, `patient_id`, `file_path`, `original_name`, `notes`, `uploaded_by`, `uploaded_at`) VALUES
(1, 1, '09042025-01', 'uploads/lab_results/lab_result_68bd78216a95a9.74539524.jpg', 'cHJpdmF0ZS9sci9pbWFnZXMvd2Vic2l0ZS8yMDIyLTA3L2pvYjEwMjgtYmctMTQuanBn.jpg', 'testing', 1, '2025-09-07 12:18:41');

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
-- Indexes for table `lab_results`
--
ALTER TABLE `lab_results`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uniq_appt` (`appointment_id`);

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `client`
--
ALTER TABLE `client`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `lab_results`
--
ALTER TABLE `lab_results`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
