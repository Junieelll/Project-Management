-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 17, 2024 at 02:20 PM
-- Server version: 10.4.25-MariaDB
-- PHP Version: 8.1.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `devsphere`
--

-- --------------------------------------------------------

--
-- Table structure for table `announcement`
--

CREATE TABLE `announcement` (
  `announcement_id` int(255) NOT NULL,
  `title` varchar(100) DEFAULT NULL,
  `critical_message` text DEFAULT NULL,
  `file_name` varchar(255) DEFAULT NULL,
  `upload_date` datetime DEFAULT NULL,
  `project_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `announcement`
--

INSERT INTO `announcement` (`announcement_id`, `title`, `critical_message`, `file_name`, `upload_date`, `project_id`) VALUES
(6, 'test', 'asfdasf', '', '2024-11-17 09:29:14', 24),
(7, 'test', 'asfdasf', '', '2024-11-17 09:29:18', 24),
(8, 'test', 'asfdasf', '', '2024-11-17 09:29:33', 24),
(9, 'gfsdgs', 'sdfgsg', '', '2024-11-17 09:29:37', 24);

-- --------------------------------------------------------

--
-- Table structure for table `comments`
--

CREATE TABLE `comments` (
  `comment_id` int(11) NOT NULL,
  `content` text DEFAULT NULL,
  `user_id` varchar(255) DEFAULT NULL,
  `announcement_id` int(11) DEFAULT NULL,
  `created_date` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `projects`
--

CREATE TABLE `projects` (
  `project_id` int(11) NOT NULL,
  `project_manager` varchar(128) NOT NULL,
  `project_name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `created_date` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `projects`
--

INSERT INTO `projects` (`project_id`, `project_manager`, `project_name`, `description`, `created_date`) VALUES
(6, 'A5rjljNBtUS4tF9h3kThZwjozcb2', 'Test', 'test', '2024-11-17 13:45:29');

-- --------------------------------------------------------

--
-- Table structure for table `project_members`
--

CREATE TABLE `project_members` (
  `member_id` int(11) NOT NULL,
  `project_id` int(11) NOT NULL,
  `user_id` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `project_members`
--

INSERT INTO `project_members` (`member_id`, `project_id`, `user_id`) VALUES
(9, 6, 'A5rjljNBtUS4tF9h3kThZwjozcb2'),
(10, 6, 'uqvsbe3NwuaAgwc39xfoLwQrXAO2');

-- --------------------------------------------------------

--
-- Table structure for table `task`
--

CREATE TABLE `task` (
  `task_id` int(255) NOT NULL,
  `task_title` varchar(100) DEFAULT NULL,
  `note` text DEFAULT NULL,
  `due_date` datetime DEFAULT NULL,
  `assigned_user` varchar(255) DEFAULT NULL,
  `priority` varchar(20) DEFAULT NULL,
  `status` varchar(20) DEFAULT NULL,
  `file` varchar(255) DEFAULT NULL,
  `created_date` datetime DEFAULT NULL,
  `project_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` varchar(255) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `profile_picture` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `name`, `email`, `profile_picture`) VALUES
('A5rjljNBtUS4tF9h3kThZwjozcb2', 'Reckless', 'junielcardenas9@gmail.com', 'https://lh3.googleusercontent.com/a/ACg8ocLWmCGvv6ZIksyNmlwMX0LWRLiWfcnmrSjAF5EQcd9LEshGq9EmDQ=s96-c'),
('TkwlGjRRvVMgxeq7cIqyaB4XW9C2', 'Lan Durr', 'imlanderbose@gmail.com', 'https://lh3.googleusercontent.com/a/ACg8ocIUrZs5uKSxESWINQdvJo0EjKL2XqxqB_WQVkNXJ1al2yv7f2c=s96-c'),
('uqvsbe3NwuaAgwc39xfoLwQrXAO2', 'Bose, John Lander G.', 'bose.johnlander.guingab@gmail.com', 'https://lh3.googleusercontent.com/a/ACg8ocJ1oqdtL57AhShMMQRIvUPb4m0CAaz9aZr2hM4OjijX6QRA-Npk=s96-c');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `announcement`
--
ALTER TABLE `announcement`
  ADD PRIMARY KEY (`announcement_id`),
  ADD KEY `project_id` (`project_id`);

--
-- Indexes for table `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`comment_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `announcement_id` (`announcement_id`);

--
-- Indexes for table `projects`
--
ALTER TABLE `projects`
  ADD PRIMARY KEY (`project_id`);

--
-- Indexes for table `project_members`
--
ALTER TABLE `project_members`
  ADD PRIMARY KEY (`member_id`),
  ADD KEY `project_id` (`project_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `task`
--
ALTER TABLE `task`
  ADD PRIMARY KEY (`task_id`),
  ADD KEY `assigned_user` (`assigned_user`),
  ADD KEY `project_id` (`project_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `announcement`
--
ALTER TABLE `announcement`
  MODIFY `announcement_id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `comments`
--
ALTER TABLE `comments`
  MODIFY `comment_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `projects`
--
ALTER TABLE `projects`
  MODIFY `project_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `project_members`
--
ALTER TABLE `project_members`
  MODIFY `member_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `task`
--
ALTER TABLE `task`
  MODIFY `task_id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `announcement`
--
ALTER TABLE `announcement`
  ADD CONSTRAINT `announcement_ibfk_1` FOREIGN KEY (`project_id`) REFERENCES `project` (`project_id`);

--
-- Constraints for table `comments`
--
ALTER TABLE `comments`
  ADD CONSTRAINT `comments_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`),
  ADD CONSTRAINT `comments_ibfk_2` FOREIGN KEY (`announcement_id`) REFERENCES `announcement` (`announcement_id`);

--
-- Constraints for table `project_members`
--
ALTER TABLE `project_members`
  ADD CONSTRAINT `project_members_ibfk_1` FOREIGN KEY (`project_id`) REFERENCES `projects` (`project_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `project_members_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `task`
--
ALTER TABLE `task`
  ADD CONSTRAINT `task_ibfk_1` FOREIGN KEY (`assigned_user`) REFERENCES `users` (`user_id`),
  ADD CONSTRAINT `task_ibfk_2` FOREIGN KEY (`project_id`) REFERENCES `project` (`project_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
