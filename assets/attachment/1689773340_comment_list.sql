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
-- Table structure for table `comment_list`
--

CREATE TABLE `comment_list` (
  `id` int(11) NOT NULL,
  `task_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `comment` text NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `comment_list`
--

INSERT INTO `comment_list` (`id`, `task_id`, `user_id`, `comment`, `created_at`, `updated_at`) VALUES
(10, 48, 1, 'This chapter describes the different types for the HTM', '2023-07-18 23:59:07', '2023-07-19 17:50:40'),
(11, 48, 1, 'This chapter describes the different', '2023-07-18 23:59:32', '2023-07-19 17:50:48'),
(12, 49, 1, ' In this query, we are using the ', '2023-07-19 11:24:29', '2023-07-19 11:24:29'),
(13, 6, 1, ' In this query, we are using the', '2023-07-19 11:25:30', '2023-07-19 11:25:30'),
(14, 48, 1, 'let me check it', '2023-07-19 12:03:53', '2023-07-19 18:17:58'),
(18, 49, 1, ' hello guys we are here ', '2023-07-19 16:42:28', '2023-07-19 16:55:39'),
(19, 9, 0, ' hello', '2023-07-19 18:18:31', '0000-00-00 00:00:00'),
(21, 9, 1, 'hello', '2023-07-19 18:22:35', '0000-00-00 00:00:00'),
(22, 9, 1, ' how are you', '2023-07-19 18:31:36', '0000-00-00 00:00:00');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `comment_list`
--
ALTER TABLE `comment_list`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `comment_list`
--
ALTER TABLE `comment_list`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
