-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: 2019 年 7 月 13 日 17:24
-- サーバのバージョン： 5.5.60-MariaDB
-- PHP Version: 5.6.40

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `e-portfolio`
--

-- --------------------------------------------------------

--
-- テーブルの構造 `assignments`
--

CREATE TABLE `assignments` (
  `class_id` varchar(16) NOT NULL,
  `id` int(11) NOT NULL,
  `title` varchar(128) NOT NULL,
  `explanation` text NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- テーブルの構造 `comments`
--

CREATE TABLE `comments` (
  `class_id` varchar(16) NOT NULL,
  `commenter_id` varchar(16) NOT NULL,
  `user_id` varchar(16) NOT NULL,
  `assignment_id` int(11) NOT NULL,
  `comment` text NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- テーブルの構造 `evaluations`
--

CREATE TABLE `evaluations` (
  `class_id` varchar(16) NOT NULL,
  `evaluator_id` varchar(16) NOT NULL,
  `user_id` varchar(16) NOT NULL,
  `assignment_id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  `item_id` int(11) NOT NULL,
  `grade_id` int(11) NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- テーブルの構造 `evaluation_categories`
--

CREATE TABLE `evaluation_categories` (
  `id` int(11) NOT NULL,
  `name` varchar(32) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- テーブルの構造 `evaluation_grades`
--

CREATE TABLE `evaluation_grades` (
  `id` int(11) NOT NULL,
  `explanation` varchar(16) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- テーブルの構造 `evaluation_items`
--

CREATE TABLE `evaluation_items` (
  `category_id` int(11) NOT NULL,
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `explanation` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- テーブルの構造 `users`
--

CREATE TABLE `users` (
  `id` varchar(16) NOT NULL,
  `password` varchar(255) NOT NULL,
  `student_id` varchar(16) NOT NULL,
  `name` varchar(32) NOT NULL,
  `permission_level` int(11) NOT NULL,
  `class_id` varchar(16) NOT NULL,
  `created_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- 初期の教師ユーザー
--

INSERT INTO `users` (`id`, `password`, `student_id`, `name`, `permission_level`, `class_id`, `created_at`) VALUES
('teacher', '$2y$10$NvThMhY7JtHAGsA5Ccl8t.yMiY6/MpACb6JDn.5rruvDk92Insq0K', '0123456789', 'teacher', 1, 'teacher', '2019-07-13 18:35:17');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `assignments`
--
ALTER TABLE `assignments`
  ADD PRIMARY KEY (`id`,`class_id`) USING BTREE;

--
-- Indexes for table `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`class_id`,`commenter_id`,`user_id`,`assignment_id`);

--
-- Indexes for table `evaluations`
--
ALTER TABLE `evaluations`
  ADD PRIMARY KEY (`class_id`,`evaluator_id`,`user_id`,`assignment_id`,`category_id`,`item_id`) USING BTREE;

--
-- Indexes for table `evaluation_categories`
--
ALTER TABLE `evaluation_categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `evaluation_grades`
--
ALTER TABLE `evaluation_grades`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `evaluation_items`
--
ALTER TABLE `evaluation_items`
  ADD PRIMARY KEY (`category_id`,`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `assignments`
--
ALTER TABLE `assignments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
