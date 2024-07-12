-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jul 12, 2024 at 03:40 AM
-- Server version: 10.6.18-MariaDB
-- PHP Version: 7.4.33

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `proshik1_twilio_with_php`
--

-- --------------------------------------------------------

--
-- Table structure for table `contact_list`
--

CREATE TABLE `contact_list` (
  `id` int(11) NOT NULL,
  `contact_number` varchar(255) DEFAULT NULL,
  `contact_mode` int(11) DEFAULT NULL,
  `contact_name` varchar(255) DEFAULT NULL,
  `contact_address` varchar(255) DEFAULT NULL,
  `contact_details` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `is_delete` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `contact_list`
--

INSERT INTO `contact_list` (`id`, `contact_number`, `contact_mode`, `contact_name`, `contact_address`, `contact_details`, `created_at`, `updated_at`, `is_delete`) VALUES
(1, 'whatsapp:+8801643177674', NULL, NULL, NULL, NULL, '2024-07-11 12:26:57', '2024-07-11 12:26:57', 0),
(8, 'whatsapp:+19499293776', NULL, NULL, NULL, NULL, '2024-07-11 17:38:53', '2024-07-11 17:38:53', 0);

-- --------------------------------------------------------

--
-- Table structure for table `threads`
--

CREATE TABLE `threads` (
  `id` int(11) NOT NULL,
  `contact_id` int(11) DEFAULT NULL,
  `message_body` varchar(255) DEFAULT NULL,
  `inbound` int(11) DEFAULT 0,
  `outbound` int(11) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `is_delete` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `threads`
--

INSERT INTO `threads` (`id`, `contact_id`, `message_body`, `inbound`, `outbound`, `created_at`, `updated_at`, `is_delete`) VALUES
(2, 1, 'hello again...', 1, 0, '2024-07-11 16:26:42', '2024-07-11 16:26:42', 0),
(3, 1, 'hi there again!', 1, 0, '2024-07-11 16:39:09', '2024-07-11 16:39:09', 0),
(4, 1, 'its a reponse from twilio agent!', 0, 1, '2024-07-11 16:41:22', '2024-07-11 16:41:22', 0),
(5, 1, 'its a reponse from twilio agent!', 0, 1, '2024-07-11 16:46:16', '2024-07-11 16:46:16', 0),
(6, 1, 'message from user', 1, 0, '2024-07-11 16:54:40', '2024-07-11 16:54:40', 0),
(7, 1, 'response from twilio', 0, 1, '2024-07-11 16:54:53', '2024-07-11 16:54:53', 0),
(8, 8, 'Test message', 1, 0, '2024-07-11 17:38:53', '2024-07-11 17:38:53', 0),
(9, 8, 'test response!', 0, 1, '2024-07-11 19:20:52', '2024-07-11 19:20:52', 0),
(10, 1, 'new response...', 0, 1, '2024-07-11 19:39:27', '2024-07-11 19:39:27', 0),
(11, 1, 'new response...', 0, 1, '2024-07-11 19:41:02', '2024-07-11 19:41:02', 0),
(12, 8, 'test alex', 0, 1, '2024-07-11 20:35:35', '2024-07-11 20:35:35', 0),
(13, 8, 'Got it', 1, 0, '2024-07-11 20:36:27', '2024-07-11 20:36:27', 0),
(14, 8, 'test alex', 0, 1, '2024-07-11 20:36:37', '2024-07-11 20:36:37', 0),
(15, 8, 'another test', 0, 1, '2024-07-11 20:37:02', '2024-07-11 20:37:02', 0);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `contact_list`
--
ALTER TABLE `contact_list`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `contact_number` (`contact_number`),
  ADD UNIQUE KEY `contact_number_2` (`contact_number`),
  ADD UNIQUE KEY `contact_number_3` (`contact_number`),
  ADD UNIQUE KEY `contact_number_4` (`contact_number`);

--
-- Indexes for table `threads`
--
ALTER TABLE `threads`
  ADD PRIMARY KEY (`id`),
  ADD KEY `contact_id` (`contact_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `contact_list`
--
ALTER TABLE `contact_list`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `threads`
--
ALTER TABLE `threads`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `threads`
--
ALTER TABLE `threads`
  ADD CONSTRAINT `threads_ibfk_1` FOREIGN KEY (`contact_id`) REFERENCES `contact_list` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
