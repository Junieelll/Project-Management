-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 20, 2024 at 11:32 PM
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
  `announcement_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `critical_message` text NOT NULL,
  `upload_date` datetime NOT NULL DEFAULT current_timestamp(),
  `project_id` int(11) NOT NULL,
  `user_id` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `announcement`
--

INSERT INTO `announcement` (`announcement_id`, `title`, `critical_message`, `upload_date`, `project_id`, `user_id`) VALUES
(19, 'sdfsdf', 'sdfsdfsdf', '2024-11-20 20:52:22', 8, 'A5rjljNBtUS4tF9h3kThZwjozcb2'),
(20, 'gfdfg', 'dfgdfgd', '2024-11-20 20:53:37', 10, '2QpQHGsSfMVMdEv41nOehKLUoJ62'),
(21, 'testing', 'dfgdfg', '2024-11-20 20:53:46', 10, '2QpQHGsSfMVMdEv41nOehKLUoJ62'),
(22, 'announcement1', 'critical mesaasge', '2024-11-20 21:17:04', 10, '2QpQHGsSfMVMdEv41nOehKLUoJ62');

-- --------------------------------------------------------

--
-- Table structure for table `announcement_file`
--

CREATE TABLE `announcement_file` (
  `file_id` int(11) NOT NULL,
  `announcement_id` int(11) NOT NULL,
  `file_name` varchar(255) NOT NULL,
  `file_path` varchar(255) NOT NULL,
  `upload_date` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `announcement_file`
--

INSERT INTO `announcement_file` (`file_id`, `announcement_id`, `file_name`, `file_path`, `upload_date`) VALUES
(36, 22, 'Wall-03.png', '/PROJECT-MANAGEMENT/uploads/announcement_file/673de150761a7_Wall-03.png', '2024-11-20 21:17:04'),
(37, 22, 'Wall-02.png', '/PROJECT-MANAGEMENT/uploads/announcement_file/673de1507726a_Wall-02.png', '2024-11-20 21:17:04'),
(38, 22, 'opportunities.jpg', '/PROJECT-MANAGEMENT/uploads/announcement_file/673de1507784a_opportunities.jpg', '2024-11-20 21:17:04'),
(39, 22, 'instant-job.jpg', '/PROJECT-MANAGEMENT/uploads/announcement_file/673de1507833e_instant-job.jpg', '2024-11-20 21:17:04'),
(40, 22, 'success.jpg', '/PROJECT-MANAGEMENT/uploads/announcement_file/673de15078838_success.jpg', '2024-11-20 21:17:04'),
(41, 22, 'Landing Page (1).png', '/PROJECT-MANAGEMENT/uploads/announcement_file/673de15078c1f_Landing Page (1).png', '2024-11-20 21:17:04'),
(42, 22, 'Landing Page.png', '/PROJECT-MANAGEMENT/uploads/announcement_file/673de15078f7f_Landing Page.png', '2024-11-20 21:17:04');

-- --------------------------------------------------------

--
-- Table structure for table `comments`
--

CREATE TABLE `comments` (
  `comment_id` int(11) NOT NULL,
  `content` text NOT NULL,
  `user_id` varchar(255) NOT NULL,
  `announcement_id` int(11) NOT NULL,
  `created_date` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `comments`
--

INSERT INTO `comments` (`comment_id`, `content`, `user_id`, `announcement_id`, `created_date`) VALUES
(12, 'nice', '2QpQHGsSfMVMdEv41nOehKLUoJ62', 21, '2024-11-20 20:53:50'),
(13, 'working', '2QpQHGsSfMVMdEv41nOehKLUoJ62', 21, '2024-11-20 20:53:53'),
(18, 'nice', 'A5rjljNBtUS4tF9h3kThZwjozcb2', 20, '2024-11-20 21:08:12'),
(19, 'okay na', 'A5rjljNBtUS4tF9h3kThZwjozcb2', 20, '2024-11-20 21:08:18'),
(20, 'gumagana', '2QpQHGsSfMVMdEv41nOehKLUoJ62', 22, '2024-11-20 21:17:21'),
(21, 'testting', 'A5rjljNBtUS4tF9h3kThZwjozcb2', 21, '2024-11-20 21:53:56'),
(22, 'asfasdfghd', 'A5rjljNBtUS4tF9h3kThZwjozcb2', 20, '2024-11-20 21:54:56');

-- --------------------------------------------------------

--
-- Table structure for table `projects`
--

CREATE TABLE `projects` (
  `project_id` int(11) NOT NULL,
  `project_manager` varchar(128) NOT NULL,
  `project_name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `created_date` datetime DEFAULT current_timestamp(),
  `due_date` date DEFAULT NULL,
  `status` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `projects`
--

INSERT INTO `projects` (`project_id`, `project_manager`, `project_name`, `description`, `created_date`, `due_date`, `status`) VALUES
(7, 'A5rjljNBtUS4tF9h3kThZwjozcb2', 'Project 1 - Devsphere', 'hahahah tumesting ka', '2024-11-18 01:00:54', '2024-12-07', 'In-Progress'),
(8, 'A5rjljNBtUS4tF9h3kThZwjozcb2', 'Project 2', 'ahahahaagh', '2024-11-18 01:52:08', '2024-11-29', 'In-Progress'),
(9, 'A5rjljNBtUS4tF9h3kThZwjozcb2', 'hjahahahaha', 'adfgadfgasf', '2024-11-18 01:52:37', '2024-11-22', 'In-Progress'),
(10, '2QpQHGsSfMVMdEv41nOehKLUoJ62', 'Project name', 'project description', '2024-11-20 00:31:19', '2024-11-20', 'In-Progress');

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
(11, 8, 'A5rjljNBtUS4tF9h3kThZwjozcb2'),
(12, 9, 'uqvsbe3NwuaAgwc39xfoLwQrXAO2'),
(13, 9, 'TkwlGjRRvVMgxeq7cIqyaB4XW9C2'),
(14, 10, 'A5rjljNBtUS4tF9h3kThZwjozcb2');

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
('2QpQHGsSfMVMdEv41nOehKLUoJ62', 'Cardenas, Juniel P.', 'cardenas.juniel.permito@gmail.com', 'https://lh3.googleusercontent.com/a/ACg8ocJ6cC1aU3NWQSpoDBFrWjFIGOFTXftRUKuUbYMBjPbJsRX8pgNQ=s96-c'),
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
  ADD KEY `project_id` (`project_id`),
  ADD KEY `announcement_ibfk_2` (`user_id`);

--
-- Indexes for table `announcement_file`
--
ALTER TABLE `announcement_file`
  ADD PRIMARY KEY (`file_id`),
  ADD KEY `announcement_id` (`announcement_id`);

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
  MODIFY `announcement_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `announcement_file`
--
ALTER TABLE `announcement_file`
  MODIFY `file_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=43;

--
-- AUTO_INCREMENT for table `comments`
--
ALTER TABLE `comments`
  MODIFY `comment_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `projects`
--
ALTER TABLE `projects`
  MODIFY `project_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `project_members`
--
ALTER TABLE `project_members`
  MODIFY `member_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

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
  ADD CONSTRAINT `announcement_ibfk_1` FOREIGN KEY (`project_id`) REFERENCES `projects` (`project_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `announcement_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `announcement_file`
--
ALTER TABLE `announcement_file`
  ADD CONSTRAINT `announcement_file_ibfk_1` FOREIGN KEY (`announcement_id`) REFERENCES `announcement` (`announcement_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `comments`
--
ALTER TABLE `comments`
  ADD CONSTRAINT `comments_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `comments_ibfk_2` FOREIGN KEY (`announcement_id`) REFERENCES `announcement` (`announcement_id`) ON DELETE CASCADE;

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
