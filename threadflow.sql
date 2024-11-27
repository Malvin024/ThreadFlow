-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 16, 2024 at 09:25 AM
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
-- Database: `threadflow`
--

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `category_id` int(11) NOT NULL,
  `category_name` varchar(50) NOT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`category_id`, `category_name`, `description`, `created_at`) VALUES
(1, 'Gaming', 'BERMAIN BERSAMA', '2024-11-15 11:22:35'),
(2, 'Tecnologies', 'a', '2024-11-16 08:12:57');

-- --------------------------------------------------------

--
-- Table structure for table `comments`
--

CREATE TABLE `comments` (
  `comment_id` int(11) NOT NULL,
  `post_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `content` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `comments`
--

INSERT INTO `comments` (`comment_id`, `post_id`, `user_id`, `content`, `created_at`) VALUES
(1, 17, 12, 'dwadwa', '2024-11-15 12:30:37'),
(2, 17, 12, 'dwadwa', '2024-11-15 12:30:38'),
(3, 17, 12, 'dwadwa', '2024-11-15 12:30:39'),
(4, 17, 12, 'dwa', '2024-11-15 12:30:39'),
(5, 17, 12, 'dwadwadwa', '2024-11-15 12:30:40');

-- --------------------------------------------------------

--
-- Table structure for table `online_users`
--

CREATE TABLE `online_users` (
  `online_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `last_active` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `posts`
--

CREATE TABLE `posts` (
  `post_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `views` int(11) DEFAULT 0,
  `replies` int(11) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `posts`
--

INSERT INTO `posts` (`post_id`, `user_id`, `category_id`, `title`, `content`, `views`, `replies`, `created_at`) VALUES
(1, 12, 1, 'es', 'dwadwadwa\n<!-- COMMENTS -->\n<strong>binus123:</strong> dwadwadwadwa\n', 12, 1, '2024-11-15 12:45:49'),
(2, 12, 1, 'dwadwa', 'dwadwadwadwa', 2, 0, '2024-11-15 13:07:29'),
(3, 12, 1, 'dwadawdawdwadwadawdwa', 'dwadwadawdwadwadwadascaszda\n<!-- COMMENTS --><strong>binus123:</strong> test <em>on 2024-11-15 17:27:33</em>\n', 46, 0, '2024-11-15 13:09:21'),
(4, 12, 1, 'wdada', 'ddwa\n<!-- COMMENTS --><strong>binus123:</strong> dwa <em>on 2024-11-15 17:13:22</em>\n\n<!-- COMMENTS --><strong>binus123:</strong> dwadwad <em>on 2024-11-15 17:27:20</em>\n\n<!-- COMMENTS --><strong>binus123:</strong> hello <em>on 2024-11-15 17:27:25</em>\n', 78, 0, '2024-11-15 14:42:50'),
(15, 12, 1, 'dwa', 'dwa', 0, 0, '2024-11-15 18:19:44'),
(16, 12, 1, 'dwadwa', 'dwadwa', 0, 0, '2024-11-15 18:24:18'),
(17, 12, 1, 'dwad', 'dwadwa\n<!-- COMMENTS --><strong>binus123:</strong> KONTOL <em>on 2024-11-15 19:27:26</em>\n\n<!-- COMMENTS --><strong>binus123:</strong> MEMEK SAPI <em>on 2024-11-15 19:27:30</em>\n\n<!-- COMMENTS --><strong>binus123:</strong> AWA <em>on 2024-11-15 19:27:36</em>\n\n<!-- COMMENTS --><strong>binus123:</strong> dwada <em>on 2024-11-15 19:28:57</em>\n', 37, 0, '2024-11-15 18:25:43'),
(20, 12, 1, 'dsa', 'dwadasda', 0, 0, '2024-11-15 19:04:06');

-- --------------------------------------------------------

--
-- Table structure for table `replies`
--

CREATE TABLE `replies` (
  `reply_id` int(11) NOT NULL,
  `post_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `content` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(100) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `reset_token` varchar(255) DEFAULT NULL,
  `reset_token_expires` datetime DEFAULT NULL,
  `profile_picture` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `username`, `password`, `email`, `created_at`, `reset_token`, `reset_token_expires`, `profile_picture`) VALUES
(1, 'test', '$2y$10$ZlzIqpAfRzcUCXPZvy7HnuATWdL972ur3yGXevMPLFcr.6K7PcKJC', 'test@gmail.com', '2024-11-14 16:50:12', NULL, NULL, NULL),
(12, 'binus123', '$2y$10$VlyXkwpH0N3i.58ua3nqVutt/cLeYxFB/Cw6xDaZeZlYsFpGxxafS', 'binus@gmail.com', '2024-11-15 10:33:38', '09c5f48ab67756f2c211d8f5943151898d08ed140afe6e5522e45b4588883733', '2024-11-15 20:55:17', 'guardiablock.JPG'),
(13, 'binjai', '$2y$10$XCoVJGAa5TU8rEX3Xm3DUuGhrEf6yEWdzUN8P7sxNV2TQcfLzgive', 'salamdaribinjai692@gmail.com', '2024-11-15 14:31:26', '44e285b41a8e6724bb78a975f32e3e7c6986277883a4869a360c19ffd3be859f', '2024-11-15 16:36:38', NULL),
(14, 'brain', '$2y$10$KjiSHshTOVj18Zd0rMWTGOh94V/oYR2Q0yXpX54InN9QBAiAuFkgm', 'brain@gmail.com', '2024-11-15 18:07:20', NULL, NULL, NULL),
(15, 'brian123', '$2y$10$IZ8D3t3B5MCbOzdHaYCgV./wVy9REAjCSkvvsG6Dl4bEpkIMRNLBm', 'brian123@gmail.com', '2024-11-15 18:09:40', NULL, NULL, NULL),
(16, 'brian321', '$2y$10$xTnTJ9JSuOx.CZTp3.cqyusCtOSpZ8B5ordyyjNoEviXqMUL2.YjW', 'brian@gmail.com', '2024-11-15 18:09:55', NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `user_profile`
--

CREATE TABLE `user_profile` (
  `profile_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `full_name` varchar(100) NOT NULL,
  `bio` text DEFAULT NULL,
  `profile_picture` varchar(255) DEFAULT NULL,
  `phone_number` varchar(20) DEFAULT NULL,
  `social_media_links` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`social_media_links`)),
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user_profile`
--

INSERT INTO `user_profile` (`profile_id`, `user_id`, `full_name`, `bio`, `profile_picture`, `phone_number`, `social_media_links`, `created_at`, `updated_at`) VALUES
(1, 1, 'John', 'This is my bio', 'path/to/profile.jpg', '123-456-7890', '{\"facebook\": \"johndoe\", \"twitter\": \"johndoe\"}', '2024-11-14 17:23:18', '2024-11-14 17:23:18');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`category_id`);

--
-- Indexes for table `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`comment_id`),
  ADD KEY `post_id` (`post_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `online_users`
--
ALTER TABLE `online_users`
  ADD PRIMARY KEY (`online_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `posts`
--
ALTER TABLE `posts`
  ADD PRIMARY KEY (`post_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `category_id` (`category_id`);

--
-- Indexes for table `replies`
--
ALTER TABLE `replies`
  ADD PRIMARY KEY (`reply_id`),
  ADD KEY `post_id` (`post_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `user_profile`
--
ALTER TABLE `user_profile`
  ADD PRIMARY KEY (`profile_id`),
  ADD KEY `user_id` (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `category_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `comments`
--
ALTER TABLE `comments`
  MODIFY `comment_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `online_users`
--
ALTER TABLE `online_users`
  MODIFY `online_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `posts`
--
ALTER TABLE `posts`
  MODIFY `post_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `replies`
--
ALTER TABLE `replies`
  MODIFY `reply_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `user_profile`
--
ALTER TABLE `user_profile`
  MODIFY `profile_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `comments`
--
ALTER TABLE `comments`
  ADD CONSTRAINT `comments_ibfk_1` FOREIGN KEY (`post_id`) REFERENCES `posts` (`post_id`),
  ADD CONSTRAINT `comments_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `online_users`
--
ALTER TABLE `online_users`
  ADD CONSTRAINT `online_users_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `posts`
--
ALTER TABLE `posts`
  ADD CONSTRAINT `posts_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `posts_ibfk_2` FOREIGN KEY (`category_id`) REFERENCES `categories` (`category_id`) ON DELETE CASCADE;

--
-- Constraints for table `replies`
--
ALTER TABLE `replies`
  ADD CONSTRAINT `replies_ibfk_1` FOREIGN KEY (`post_id`) REFERENCES `posts` (`post_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `replies_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `user_profile`
--
ALTER TABLE `user_profile`
  ADD CONSTRAINT `user_profile_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
