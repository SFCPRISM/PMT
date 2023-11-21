-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 19, 2023 at 03:27 PM
-- Server version: 10.4.27-MariaDB
-- PHP Version: 8.1.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `pms_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `attachment_list`
--

CREATE TABLE `attachment_list` (
  `id` int(11) NOT NULL,
  `task_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `url` longtext NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `attachment_list`
--

INSERT INTO `attachment_list` (`id`, `task_id`, `user_id`, `url`, `created_at`, `updated_at`) VALUES
(18, 48, 1, '1689684300_trinity15 (2).png', '2023-07-18 18:15:17', '2023-07-18 18:15:17'),
(21, 48, 1, '1689685500_MicrosoftTeams-image (2) (1) (1).png', '2023-07-18 18:35:47', '2023-07-18 18:35:47'),
(23, 48, 1, '1689768960_SB Collect.pdf', '2023-07-19 17:46:10', '2023-07-19 17:46:10'),
(25, 9, 0, '1689770100_User Journey Flow from Website  (1).pdf', '2023-07-19 18:05:40', '2023-07-19 18:05:40'),
(26, 9, 0, '1689770880_User Journey Flow from Website  (1).pdf', '2023-07-19 18:18:17', '2023-07-19 18:18:17'),
(27, 9, 1, '1689771060_1689665220_user_productivity.sql', '2023-07-19 18:21:53', '2023-07-19 18:21:53'),
(28, 9, 1, '1689771120_MicrosoftTeams-image (2) (1) (1).png', '2023-07-19 18:22:07', '2023-07-19 18:22:07'),
(29, 9, 1, '1689771840_pms_db (1).sql', '2023-07-19 18:34:47', '2023-07-19 18:34:47');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `attachment_list`
--
ALTER TABLE `attachment_list`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `attachment_list`
--
ALTER TABLE `attachment_list`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
