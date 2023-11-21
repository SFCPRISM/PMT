-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Jul 17, 2023 at 01:05 PM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

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
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `comment_list`
--

CREATE TABLE `comment_list` (
  `id` int(11) NOT NULL,
  `task_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `comment` text NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `project_list`
--

CREATE TABLE `project_list` (
  `id` int(30) NOT NULL,
  `name` varchar(200) NOT NULL,
  `description` text NOT NULL,
  `status` tinyint(2) NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `manager_id` int(30) NOT NULL,
  `user_ids` text NOT NULL,
  `date_created` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `project_list`
--

INSERT INTO `project_list` (`id`, `name`, `description`, `status`, `start_date`, `end_date`, `manager_id`, `user_ids`, `date_created`) VALUES
(1, 'Sample Project', '								&lt;span style=&quot;color: rgb(0, 0, 0); font-family: &amp;quot;Open Sans&amp;quot;, Arial, sans-serif; font-size: 14px; text-align: justify;&quot;&gt;Lorem ipsum dolor sit amet, consectetur adipiscing elit. In elementum, metus vitae malesuada mollis, urna nisi luctus ligula, vitae volutpat massa eros eu ligula. Nunc dui metus, iaculis id dolor non, luctus tristique libero. Aenean et sagittis sem. Nulla facilisi. Mauris at placerat augue. Nullam porttitor felis turpis, ac varius eros placerat et. Nunc ut enim scelerisque, porta lacus vitae, viverra justo. Nam mollis turpis nec dolor feugiat, sed bibendum velit placerat. Etiam in hendrerit leo. Nullam mollis lorem massa, sit amet tincidunt dolor lacinia at.&lt;/span&gt;							', 0, '2020-11-03', '2021-01-20', 2, '3,4,5', '2020-12-03 09:56:56'),
(2, 'Sample Project 102', 'Sample Only', 0, '2020-12-02', '2020-12-31', 2, '3', '2020-12-03 13:51:54'),
(3, 'concored', 'just verifying all the things.&amp;nbsp;', 0, '2023-07-04', '2023-07-05', 2, '7,6', '2023-07-04 11:36:56'),
(4, 'voyce', 'something', 0, '2023-07-04', '2023-07-20', 8, '7,6,3', '2023-07-04 11:40:17'),
(6, 'checking', 'anything', 3, '2023-07-07', '2023-07-22', 8, '7,6', '2023-07-07 16:16:57');

-- --------------------------------------------------------

--
-- Table structure for table `sub_task_list`
--

CREATE TABLE `sub_task_list` (
  `id` int(11) NOT NULL,
  `task_id` int(11) DEFAULT NULL,
  `project_id` int(30) NOT NULL,
  `sub_task` varchar(200) NOT NULL,
  `description` text NOT NULL,
  `status` tinyint(4) NOT NULL,
  `user_id` int(11) NOT NULL,
  `start_date` datetime NOT NULL,
  `end_date` datetime NOT NULL,
  `estimated_time` text NOT NULL,
  `Updated_At` datetime NOT NULL DEFAULT current_timestamp(),
  `attachment_name` varchar(200) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `sub_task_list`
--

INSERT INTO `sub_task_list` (`id`, `task_id`, `project_id`, `sub_task`, `description`, `status`, `user_id`, `start_date`, `end_date`, `estimated_time`, `Updated_At`, `attachment_name`) VALUES
(1, NULL, 6, 'sub task', 'fdgvhbm', 1, 7, '2023-07-11 00:00:00', '2023-07-12 00:00:00', '8 hr', '2023-07-11 18:43:37', NULL),
(2, NULL, 6, 'cvbnm', 'fdxghvbjn', 1, 6, '2023-07-11 00:00:00', '2023-07-11 00:00:00', '10 min', '2023-07-11 18:56:05', NULL),
(3, NULL, 4, 'dfgvhmn', 'dfghvjbn,', 1, 7, '2023-07-12 00:00:00', '2023-07-13 00:00:00', 'fdzgn', '2023-07-12 16:13:54', NULL),
(4, 6, 4, 'sub task 102', '                								anything&amp;nbsp;						            ', 3, 7, '2023-07-14 00:00:00', '2023-07-15 00:00:00', '8 hr', '2023-07-14 14:13:36', NULL),
(5, 6, 4, 'sub task 105', 'checking', 3, 7, '2023-07-14 00:00:00', '2023-07-15 00:00:00', '8 hr', '2023-07-14 14:20:19', NULL),
(6, 9, 3, 'sub task 107', 'sub task for case 756', 2, 7, '2023-07-14 00:00:00', '2023-07-15 00:00:00', '8 hr', '2023-07-14 14:22:34', NULL),
(7, 9, 3, 'sub task 109', 'all task&amp;nbsp;', 2, 7, '2023-07-14 00:00:00', '2023-07-15 00:00:00', '8 hr', '2023-07-14 15:00:11', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `system_settings`
--

CREATE TABLE `system_settings` (
  `id` int(30) NOT NULL,
  `name` text NOT NULL,
  `email` varchar(200) NOT NULL,
  `contact` varchar(20) NOT NULL,
  `address` text NOT NULL,
  `cover_img` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `system_settings`
--

INSERT INTO `system_settings` (`id`, `name`, `email`, `contact`, `address`, `cover_img`) VALUES
(1, 'Task Management System', 'info@sample.comm', '+6948 8542 623', '2102  Caldwell Road, Rochester, New York, 14608', '');

-- --------------------------------------------------------

--
-- Table structure for table `task_list`
--

CREATE TABLE `task_list` (
  `id` int(30) NOT NULL,
  `project_id` int(30) NOT NULL,
  `task` varchar(200) NOT NULL,
  `description` text NOT NULL,
  `status` tinyint(4) NOT NULL,
  `task_type` int(11) NOT NULL DEFAULT 1,
  `user_id` int(11) NOT NULL,
  `start_date` datetime NOT NULL,
  `end_date` datetime NOT NULL,
  `estimated_time` text NOT NULL,
  `Updated_At` datetime NOT NULL DEFAULT current_timestamp(),
  `attachment_name` varchar(200) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `task_list`
--

INSERT INTO `task_list` (`id`, `project_id`, `task`, `description`, `status`, `task_type`, `user_id`, `start_date`, `end_date`, `estimated_time`, `Updated_At`, `attachment_name`) VALUES
(1, 1, 'Sample Task 1', '								&lt;span style=&quot;color: rgb(0, 0, 0); font-family: &amp;quot;Open Sans&amp;quot;, Arial, sans-serif; font-size: 14px; text-align: justify;&quot;&gt;Fusce ullamcorper mattis semper. Nunc vel risus ipsum. Sed maximus dapibus nisl non laoreet. Pellentesque quis mauris odio. Donec fermentum facilisis odio, sit amet aliquet purus scelerisque eget.&amp;nbsp;&lt;/span&gt;													', 3, 0, 7, '0000-00-00 00:00:00', '0000-00-00 00:00:00', '10 hr', '2023-07-07 14:08:27', '0'),
(2, 1, 'Sample Task 2', 'Sample Task 2							', 1, 0, 3, '0000-00-00 00:00:00', '0000-00-00 00:00:00', '9 hr', '2023-07-07 14:08:27', '0'),
(3, 2, 'Task Test', 'Sample', 1, 0, 3, '0000-00-00 00:00:00', '0000-00-00 00:00:00', '15 hr', '2023-07-07 14:08:27', '0'),
(4, 2, 'test 23', 'Sample test 23', 1, 0, 3, '0000-00-00 00:00:00', '0000-00-00 00:00:00', '20 hr', '2023-07-07 14:08:27', '0'),
(5, 1, 'cfjhfr', 'dvfgety awqfuefgyer&amp;nbsp;', 1, 0, 3, '0000-00-00 00:00:00', '0000-00-00 00:00:00', '8 hr', '2023-07-07 14:08:27', '0'),
(6, 4, 'case 365', '												anything&amp;nbsp;									', 3, 1, 7, '2023-07-05 00:00:00', '2023-07-15 00:00:00', '13 hr', '2023-07-07 14:08:27', '0'),
(7, 4, 'case 366', 'all check', 1, 0, 7, '0000-00-00 00:00:00', '0000-00-00 00:00:00', '16 hr', '2023-07-07 14:08:27', '0'),
(9, 3, 'case 756', '																								checking developer																		', 2, 0, 7, '2023-07-07 00:00:00', '2023-07-10 00:00:00', '21 hr', '2023-07-07 14:08:27', '0'),
(11, 4, 'case 378', 'new work', 2, 0, 3, '0000-00-00 00:00:00', '0000-00-00 00:00:00', '22 hr', '2023-07-07 14:08:27', '0'),
(12, 4, 'case 4201', '								checking all the value						', 2, 0, 6, '2023-07-07 00:00:00', '2023-07-15 00:00:00', '40', '2023-07-07 14:28:55', '0'),
(14, 4, 'case 421', 'status checking', 4, 0, 7, '2023-07-07 00:00:00', '2023-07-11 00:00:00', '8 hr', '2023-07-07 14:44:55', '0'),
(15, 6, 'case 102', '																																				write something in desc																											', 4, 0, 7, '2023-07-09 00:00:00', '2023-07-10 00:00:00', '8 hr', '2023-07-07 16:17:43', '0'),
(16, 6, 'case 107', '				wefbbef			', 1, 0, 7, '2023-07-10 00:00:00', '2023-07-11 00:00:00', '5 hr', '2023-07-10 17:07:14', NULL),
(17, 6, 'case 108', '				hgfhvh			', 1, 0, 7, '2023-07-10 00:00:00', '2023-07-10 00:00:00', '1 hr', '2023-07-10 17:10:36', NULL),
(18, 6, 'case 7655', 'hello testing file upload', 1, 0, 7, '2023-07-11 00:00:00', '2023-07-13 00:00:00', '8', '2023-07-11 15:38:06', '1689070080_IMG-5614.jpg'),
(19, 6, 'case 7655', 'hello testing file upload', 1, 0, 7, '2023-07-11 00:00:00', '2023-07-13 00:00:00', '8', '2023-07-11 15:38:31', '1689070080_IMG-5614.jpg'),
(20, 6, 'case 7655', 'hello testing file upload', 1, 0, 7, '2023-07-11 00:00:00', '2023-07-13 00:00:00', '8', '2023-07-11 15:38:38', '1689070080_IMG-5614.jpg'),
(21, 6, 'case 7655', 'hello testing file upload', 1, 0, 7, '2023-07-11 00:00:00', '2023-07-13 00:00:00', '8', '2023-07-11 15:38:39', '1689070080_IMG-5614.jpg'),
(22, 6, 'case 7655', 'hello testing file upload', 1, 0, 7, '2023-07-11 00:00:00', '2023-07-13 00:00:00', '8', '2023-07-11 15:38:40', '1689070080_IMG-5614.jpg'),
(23, 6, 'case 7655', 'hello testing file upload', 1, 0, 7, '2023-07-11 00:00:00', '2023-07-13 00:00:00', '8', '2023-07-11 15:38:40', '1689070080_IMG-5614.jpg'),
(24, 6, 'case 7655', 'hello testing file upload', 1, 0, 7, '2023-07-11 00:00:00', '2023-07-13 00:00:00', '8', '2023-07-11 15:38:40', '1689070080_IMG-5614.jpg'),
(25, 6, 'case 7655', 'hello testing file upload', 1, 0, 7, '2023-07-11 00:00:00', '2023-07-13 00:00:00', '8', '2023-07-11 15:38:40', '1689070080_IMG-5614.jpg'),
(26, 6, 'case 7655', 'hello testing file upload', 1, 0, 7, '2023-07-11 00:00:00', '2023-07-13 00:00:00', '8', '2023-07-11 15:38:41', '1689070080_IMG-5614.jpg'),
(27, 6, 'case 7655', 'hello testing file upload', 1, 0, 7, '2023-07-11 00:00:00', '2023-07-13 00:00:00', '8', '2023-07-11 15:38:41', '1689070080_IMG-5614.jpg'),
(28, 6, 'case 7655', 'hello testing file upload', 1, 0, 7, '2023-07-11 00:00:00', '2023-07-13 00:00:00', '8', '2023-07-11 15:38:41', '1689070080_IMG-5614.jpg'),
(29, 6, 'case 7655', 'hello testing file upload', 1, 0, 7, '2023-07-11 00:00:00', '2023-07-13 00:00:00', '8', '2023-07-11 15:38:42', '1689070080_IMG-5614.jpg'),
(30, 6, 'case 7655', 'hello testing file upload', 1, 0, 7, '2023-07-11 00:00:00', '2023-07-13 00:00:00', '8', '2023-07-11 15:38:42', '1689070080_IMG-5614.jpg'),
(31, 6, 'case 7655', 'hello testing file upload', 1, 0, 7, '2023-07-11 00:00:00', '2023-07-13 00:00:00', '8', '2023-07-11 15:38:42', '1689070080_IMG-5614.jpg'),
(32, 6, 'case 7655', 'hello testing file upload', 1, 0, 7, '2023-07-11 00:00:00', '2023-07-13 00:00:00', '8', '2023-07-11 15:38:44', '1689070080_IMG-5614.jpg'),
(33, 6, 'case 7655', 'hello testing file upload', 1, 0, 7, '2023-07-11 00:00:00', '2023-07-13 00:00:00', '8', '2023-07-11 15:39:34', '1689070140_IMG-5614.jpg'),
(34, 6, 'case 7655', 'hello testing file upload', 1, 0, 7, '2023-07-11 00:00:00', '2023-07-13 00:00:00', '8', '2023-07-11 15:39:36', '1689070140_IMG-5614.jpg'),
(35, 6, 'case 7655', 'hello testing file upload', 1, 0, 7, '2023-07-11 00:00:00', '2023-07-13 00:00:00', '8', '2023-07-11 15:39:46', '1689070140_IMG-5614.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(30) NOT NULL,
  `firstname` varchar(200) NOT NULL,
  `lastname` varchar(200) NOT NULL,
  `email` varchar(200) NOT NULL,
  `password` text NOT NULL,
  `type` tinyint(1) NOT NULL DEFAULT 2 COMMENT '1 = admin, 2 = staff',
  `avatar` varchar(200) NOT NULL DEFAULT 'no-image-available.png',
  `date_created` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `firstname`, `lastname`, `email`, `password`, `type`, `avatar`, `date_created`) VALUES
(1, 'Administrator', '', 'admin@admin.com', '0192023a7bbd73250516f069df18b500', 1, '1688453940_icons-hero.png', '2020-11-26 10:57:04'),
(3, 'Claire', 'Blake', 'cblake@sample.com', '4744ddea876b11dcb1d169fadf494418', 3, '1606958760_47446233-clean-noir-et-gradient-sombre-image-de-fond-abstrait-.jpg', '2020-12-03 09:26:42'),
(5, 'Mike', 'Williams', 'mwilliams@sample.com', '3cc93e9a6741d8b40460457139cf8ced', 3, '1606963620_47446233-clean-noir-et-gradient-sombre-image-de-fond-abstrait-.jpg', '2020-12-03 10:47:06'),
(6, 'Abhishek', 'Verma', 'abhishek.k800.av@gmail.com', '2666771e2ea27f544a109656d8052a5e', 3, '1688461200_IMG_5656.jpg', '2023-07-04 11:31:34'),
(7, 'Abhi', 'shek', 'av800234@gmail.com', '2666771e2ea27f544a109656d8052a5e', 3, 'no-image-available.png', '2023-07-04 11:34:18'),
(8, 'uttam', 'sagar', 'uttamsagar2001@gmail.com', '19b1f2b0be69cee35997a240c99773e6', 2, '1688452980_IMG_5656.jpg', '2023-07-04 11:39:31'),
(16, 'mayank', 'kumar', 'mayank@kumar.com', '2666771e2ea27f544a109656d8052a5e', 5, 'no-image-available.png', '2023-07-07 12:40:46'),
(17, 'Abhishek', 'Yadav', 'abhishek@yadav.com', '2666771e2ea27f544a109656d8052a5e', 4, 'no-image-available.png', '2023-07-07 12:42:20');

-- --------------------------------------------------------

--
-- Table structure for table `user_productivity`
--

CREATE TABLE `user_productivity` (
  `id` int(30) NOT NULL,
  `project_id` int(30) NOT NULL,
  `task_id` int(30) NOT NULL,
  `comment` text NOT NULL,
  `subject` varchar(200) NOT NULL,
  `date` date NOT NULL,
  `start_time` time NOT NULL,
  `end_time` time NOT NULL,
  `user_id` int(30) NOT NULL,
  `time_rendered` float NOT NULL,
  `date_created` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user_productivity`
--

INSERT INTO `user_productivity` (`id`, `project_id`, `task_id`, `comment`, `subject`, `date`, `start_time`, `end_time`, `user_id`, `time_rendered`, `date_created`) VALUES
(1, 1, 1, '							&lt;p&gt;Sample Progress&lt;/p&gt;&lt;ul&gt;&lt;li&gt;Test 1&lt;/li&gt;&lt;li&gt;Test 2&lt;/li&gt;&lt;li&gt;Test 3&lt;/li&gt;&lt;/ul&gt;																			', 'Sample Progress', '2020-12-03', '08:00:00', '10:00:00', 1, 2, '2020-12-03 12:13:28'),
(2, 1, 1, '							Sample Progress						', 'Sample Progress 2', '2020-12-03', '13:00:00', '14:00:00', 1, 1, '2020-12-03 13:48:28'),
(3, 1, 2, '							Sample						', 'Test', '2020-12-03', '08:00:00', '09:00:00', 5, 1, '2020-12-03 13:57:22'),
(4, 1, 2, 'asdasdasd', 'Sample Progress', '2020-12-02', '08:00:00', '10:00:00', 2, 2, '2020-12-03 14:36:30'),
(5, 2, 4, 'frhgtygh', 'rfhrfrh', '2023-07-05', '19:45:00', '18:51:00', 1, -0.9, '2023-07-03 18:45:13'),
(6, 4, 7, '													', 'hmko nhi smjh aa rha h ', '2023-07-04', '11:43:00', '17:43:00', 1, 6, '2023-07-04 11:43:54'),
(7, 4, 6, '													', 'HISTORY', '2023-07-04', '11:45:00', '17:50:00', 1, 6.08333, '2023-07-04 11:44:51'),
(8, 4, 7, 'every thing is good', 'test', '2023-07-04', '00:47:00', '05:47:00', 8, 5, '2023-07-04 11:48:12'),
(9, 4, 6, '													', 'HISTORY', '2023-07-04', '00:55:00', '14:55:00', 8, 14, '2023-07-04 11:56:02'),
(10, 4, 6, '													', 'HISTORY', '2023-06-26', '12:03:00', '17:03:00', 6, 5, '2023-07-04 12:03:51');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `project_list`
--
ALTER TABLE `project_list`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sub_task_list`
--
ALTER TABLE `sub_task_list`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `system_settings`
--
ALTER TABLE `system_settings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `task_list`
--
ALTER TABLE `task_list`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user_productivity`
--
ALTER TABLE `user_productivity`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `project_list`
--
ALTER TABLE `project_list`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `sub_task_list`
--
ALTER TABLE `sub_task_list`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `system_settings`
--
ALTER TABLE `system_settings`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `task_list`
--
ALTER TABLE `task_list`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=44;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `user_productivity`
--
ALTER TABLE `user_productivity`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
