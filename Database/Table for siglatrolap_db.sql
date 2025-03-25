-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 25, 2025 at 06:39 PM
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
-- Database: `siglatrolap_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `full_name` varchar(255) NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','employee') NOT NULL DEFAULT 'employee',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `username` varchar(100) NOT NULL,
  `user_id` int(100) NOT NULL,
  `date` date DEFAULT NULL,
  `check_in_time` time DEFAULT NULL,
  `check_out_time` time DEFAULT NULL,
  `status` varchar(20) NOT NULL DEFAULT 'On Time'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `full_name`, `email`, `password`, `role`, `created_at`, `username`, `user_id`, `date`, `check_in_time`, `check_out_time`, `status`) VALUES
(39, 'admin', 'dmins@gmail.com', '$2y$10$DuZhv03BTmNiT5Hnukjko.bgkalBsjF8ems9qR5kBC57KdOjyk1Mu', 'admin', '2025-03-25 17:00:00', 'adminer', 0, NULL, NULL, NULL, 'On Time'),
(40, 'admin2', 'admirers@gmail.com', '$2y$10$qfSB9nAIGsYmeTSaBKzuxOAw1JBLklTbCaI3LWZGzxx4ubbyp1jgu', 'admin', '2025-03-25 17:01:41', 'adminer2', 0, NULL, NULL, NULL, 'On Time'),
(41, 'Joshua arncel', 'sierra@gmail.com', '$2y$10$YX0xiPQGvWRaw0bZiFaqBeDqV204NRjCbiff3Ow3Qxo1CHfOdd1zG', 'employee', '2025-03-25 17:03:09', 'josh123', 0, NULL, NULL, NULL, 'On Time'),
(42, 'rhyzon', 'rhyzon@gmail.com', '$2y$10$d7nt6dYj0Obmf7nSIkNGNuJBWM8QBoPYTNee6Kbe5hfrYB4o4fC8.', 'employee', '2025-03-25 17:03:53', 'rhyrhy', 0, NULL, NULL, NULL, 'On Time'),
(43, 'raffy', 'raffy@gmail.com', '$2y$10$w4N4sOM8Rwtdb7OZoqA0DON36mBlh79vWUXjSBPIifQyC43Vah9DC', 'employee', '2025-03-25 17:04:27', 'raffy11', 0, NULL, NULL, NULL, 'On Time');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=44;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
