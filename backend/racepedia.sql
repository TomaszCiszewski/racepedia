-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Host: localhost:8889
-- Generation Time: Feb 15, 2026 at 10:31 PM
-- Server version: 8.0.44
-- PHP Version: 8.3.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `racepedia`
--

-- --------------------------------------------------------

--
-- Table structure for table `drivers`
--

CREATE TABLE `drivers` (
  `id` int NOT NULL,
  `full_name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `number` int DEFAULT NULL,
  `country` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `team_id` int DEFAULT NULL,
  `birth_date` date DEFAULT NULL,
  `birth_place` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `height_cm` int DEFAULT NULL,
  `weight_kg` int DEFAULT NULL,
  `debut_year` year DEFAULT NULL,
  `world_titles` int NOT NULL DEFAULT '0',
  `race_wins` int NOT NULL DEFAULT '0',
  `podiums` int NOT NULL DEFAULT '0',
  `pole_positions` int NOT NULL DEFAULT '0',
  `fastest_laps` int NOT NULL DEFAULT '0',
  `points_total` int NOT NULL DEFAULT '0',
  `bio` text COLLATE utf8mb4_unicode_ci,
  `image_path` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `forum_categories`
--

CREATE TABLE `forum_categories` (
  `id` int NOT NULL,
  `name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `icon` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `display_order` int NOT NULL DEFAULT '0',
  `is_active` tinyint(1) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `forum_categories`
--

INSERT INTO `forum_categories` (`id`, `name`, `description`, `icon`, `display_order`, `is_active`) VALUES
(1, 'Ogólne', 'Dyskusje ogólne o motorsporcie', 'fa-comments', 1, 1),
(2, 'Formuła 1', 'Wszystko o F1 - dyskusje, analizy, plotki', 'fa-flag-checkered', 2, 1),
(3, 'WRC', 'Rajdowe Mistrzostwa Świata', 'fa-mountain', 3, 1),
(4, 'WEC', 'Długodystansowe wyścigi samochodów', 'fa-clock', 4, 1),
(5, 'Pomoc', 'Pomoc techniczna i pytania o stronę', 'fa-question-circle', 5, 1);

-- --------------------------------------------------------

--
-- Table structure for table `forum_likes`
--

CREATE TABLE `forum_likes` (
  `id` int NOT NULL,
  `post_id` int NOT NULL,
  `user_id` int NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `forum_posts`
--

CREATE TABLE `forum_posts` (
  `id` int NOT NULL,
  `thread_id` int NOT NULL,
  `content` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `author_id` int NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `is_edited` tinyint(1) NOT NULL DEFAULT '0',
  `is_solution` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Triggers `forum_posts`
--
DELIMITER $$
CREATE TRIGGER `after_forum_post_insert` AFTER INSERT ON `forum_posts` FOR EACH ROW BEGIN
    UPDATE forum_threads 
    SET last_post_id = NEW.id, 
        last_post_at = NEW.created_at 
    WHERE id = NEW.thread_id;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `forum_threads`
--

CREATE TABLE `forum_threads` (
  `id` int NOT NULL,
  `category_id` int NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `content` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `author_id` int NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `views` int NOT NULL DEFAULT '0',
  `is_pinned` tinyint(1) NOT NULL DEFAULT '0',
  `is_locked` tinyint(1) NOT NULL DEFAULT '0',
  `last_post_id` int DEFAULT NULL,
  `last_post_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `news`
--

CREATE TABLE `news` (
  `id` int NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `content` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `author_id` int NOT NULL,
  `image_path` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `published_at` timestamp NULL DEFAULT NULL,
  `is_published` tinyint(1) NOT NULL DEFAULT '0',
  `views` int NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `races`
--

CREATE TABLE `races` (
  `id` int NOT NULL,
  `name` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL,
  `season` year NOT NULL,
  `round` int NOT NULL,
  `track_id` int NOT NULL,
  `race_date` date NOT NULL,
  `qualifying_date` date DEFAULT NULL,
  `sprint_date` date DEFAULT NULL,
  `winner_driver_id` int DEFAULT NULL,
  `winner_team_id` int DEFAULT NULL,
  `pole_driver_id` int DEFAULT NULL,
  `fastest_lap_driver_id` int DEFAULT NULL,
  `laps_completed` int DEFAULT NULL,
  `status` enum('planned','completed','cancelled') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'planned'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `teams`
--

CREATE TABLE `teams` (
  `id` int NOT NULL,
  `name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `full_name` varchar(200) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `country` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `base_location` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `team_principal` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `chassis` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `power_unit` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `debut_year` year DEFAULT NULL,
  `world_titles` int NOT NULL DEFAULT '0',
  `logo_path` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `car_image_path` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `bio` text COLLATE utf8mb4_unicode_ci,
  `is_active` tinyint(1) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tracks`
--

CREATE TABLE `tracks` (
  `id` int NOT NULL,
  `name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `country` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `city` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `length_km` decimal(6,3) NOT NULL,
  `turns` int DEFAULT NULL,
  `laps` int DEFAULT NULL,
  `lap_record` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `lap_record_driver` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `lap_record_year` year DEFAULT NULL,
  `first_gp` year DEFAULT NULL,
  `capacity` int DEFAULT NULL,
  `image_path` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `circuit_map_path` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `bio` text COLLATE utf8mb4_unicode_ci,
  `is_active` tinyint(1) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int NOT NULL,
  `username` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password_hash` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `role` enum('Administrator','Noob','Niedzielny Kierowca','Dobry Kierowca') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Noob',
  `avatar` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'default.jpg',
  `bio` text COLLATE utf8mb4_unicode_ci,
  `join_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `last_login` timestamp NULL DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password_hash`, `role`, `avatar`, `bio`, `join_date`, `last_login`, `is_active`) VALUES
(1, 'admin', 'admin@racepedia.pl', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Administrator', 'default.jpg', NULL, '2026-02-15 13:08:20', NULL, 1),
(2, 'ciszewa', 'ciszewski.tomaszek@gmail.com', '$2y$10$bMjNhcl7vccEV.XhP7KpuuImovDV3lbwQvRjqPPr6E.TFtbsT17YS', 'Administrator', 'default.jpg', NULL, '2026-02-15 13:24:18', NULL, 1),
(3, 'noob', 'aok@kaw.pl', '$2y$10$BxCoE.NfnUx8/2k5rCZWO.YcENZlvmMQr3KtXdskJS3Gyz9pwMSWS', 'Noob', 'default.jpg', NULL, '2026-02-15 14:19:11', NULL, 1);

-- --------------------------------------------------------

--
-- Table structure for table `user_sessions`
--

CREATE TABLE `user_sessions` (
  `id` int NOT NULL,
  `user_id` int NOT NULL,
  `session_token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `expires_at` timestamp NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Stand-in structure for view `v_driver_stats`
-- (See below for the actual view)
--
CREATE TABLE `v_driver_stats` (
`id` int
,`full_name` varchar(100)
,`number` int
,`country` varchar(50)
,`team_name` varchar(100)
,`world_titles` int
,`race_wins` int
,`podiums` int
,`pole_positions` int
,`fastest_laps` int
,`points_total` int
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `v_last_races`
-- (See below for the actual view)
--
CREATE TABLE `v_last_races` (
`id` int
,`name` varchar(200)
,`season` year
,`round` int
,`race_date` date
,`track_name` varchar(100)
,`track_country` varchar(50)
,`winner_name` varchar(100)
,`winner_team` varchar(100)
);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `drivers`
--
ALTER TABLE `drivers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `number` (`number`),
  ADD KEY `idx_team` (`team_id`),
  ADD KEY `idx_country` (`country`),
  ADD KEY `idx_active` (`is_active`),
  ADD KEY `idx_team_active` (`team_id`,`is_active`);
ALTER TABLE `drivers` ADD FULLTEXT KEY `ft_search` (`full_name`,`bio`);

--
-- Indexes for table `forum_categories`
--
ALTER TABLE `forum_categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`),
  ADD KEY `idx_display_order` (`display_order`);

--
-- Indexes for table `forum_likes`
--
ALTER TABLE `forum_likes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `post_user` (`post_id`,`user_id`),
  ADD KEY `idx_post` (`post_id`),
  ADD KEY `idx_user` (`user_id`);

--
-- Indexes for table `forum_posts`
--
ALTER TABLE `forum_posts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_thread` (`thread_id`),
  ADD KEY `idx_author` (`author_id`),
  ADD KEY `idx_created` (`created_at`),
  ADD KEY `idx_solution` (`is_solution`);

--
-- Indexes for table `forum_threads`
--
ALTER TABLE `forum_threads`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_category` (`category_id`),
  ADD KEY `idx_author` (`author_id`),
  ADD KEY `idx_created` (`created_at`),
  ADD KEY `idx_pinned` (`is_pinned`),
  ADD KEY `idx_last_post` (`last_post_at`);
ALTER TABLE `forum_threads` ADD FULLTEXT KEY `ft_search` (`title`,`content`);

--
-- Indexes for table `news`
--
ALTER TABLE `news`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_author` (`author_id`),
  ADD KEY `idx_published` (`published_at`),
  ADD KEY `idx_created` (`created_at`);
ALTER TABLE `news` ADD FULLTEXT KEY `ft_search` (`title`,`content`);

--
-- Indexes for table `races`
--
ALTER TABLE `races`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `season_round` (`season`,`round`),
  ADD KEY `idx_season` (`season`),
  ADD KEY `idx_track` (`track_id`),
  ADD KEY `idx_date` (`race_date`),
  ADD KEY `idx_status` (`status`),
  ADD KEY `fk_races_winner_driver` (`winner_driver_id`),
  ADD KEY `fk_races_winner_team` (`winner_team_id`),
  ADD KEY `fk_races_pole_driver` (`pole_driver_id`),
  ADD KEY `fk_races_fastest_lap` (`fastest_lap_driver_id`);

--
-- Indexes for table `teams`
--
ALTER TABLE `teams`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`),
  ADD KEY `idx_country` (`country`),
  ADD KEY `idx_active` (`is_active`);

--
-- Indexes for table `tracks`
--
ALTER TABLE `tracks`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_country` (`country`),
  ADD KEY `idx_active` (`is_active`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `idx_username` (`username`),
  ADD KEY `idx_email` (`email`),
  ADD KEY `idx_role` (`role`);

--
-- Indexes for table `user_sessions`
--
ALTER TABLE `user_sessions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `session_token` (`session_token`),
  ADD KEY `idx_user` (`user_id`),
  ADD KEY `idx_expires` (`expires_at`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `drivers`
--
ALTER TABLE `drivers`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `forum_categories`
--
ALTER TABLE `forum_categories`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `forum_likes`
--
ALTER TABLE `forum_likes`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `forum_posts`
--
ALTER TABLE `forum_posts`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `forum_threads`
--
ALTER TABLE `forum_threads`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `news`
--
ALTER TABLE `news`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `races`
--
ALTER TABLE `races`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `teams`
--
ALTER TABLE `teams`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tracks`
--
ALTER TABLE `tracks`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `user_sessions`
--
ALTER TABLE `user_sessions`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

-- --------------------------------------------------------

--
-- Structure for view `v_driver_stats`
--
DROP TABLE IF EXISTS `v_driver_stats`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_driver_stats`  AS SELECT `d`.`id` AS `id`, `d`.`full_name` AS `full_name`, `d`.`number` AS `number`, `d`.`country` AS `country`, `t`.`name` AS `team_name`, `d`.`world_titles` AS `world_titles`, `d`.`race_wins` AS `race_wins`, `d`.`podiums` AS `podiums`, `d`.`pole_positions` AS `pole_positions`, `d`.`fastest_laps` AS `fastest_laps`, `d`.`points_total` AS `points_total` FROM (`drivers` `d` left join `teams` `t` on((`d`.`team_id` = `t`.`id`))) WHERE (`d`.`is_active` = 1) ORDER BY `d`.`race_wins` DESC ;

-- --------------------------------------------------------

--
-- Structure for view `v_last_races`
--
DROP TABLE IF EXISTS `v_last_races`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_last_races`  AS SELECT `r`.`id` AS `id`, `r`.`name` AS `name`, `r`.`season` AS `season`, `r`.`round` AS `round`, `r`.`race_date` AS `race_date`, `t`.`name` AS `track_name`, `t`.`country` AS `track_country`, `d`.`full_name` AS `winner_name`, `tm`.`name` AS `winner_team` FROM (((`races` `r` left join `tracks` `t` on((`r`.`track_id` = `t`.`id`))) left join `drivers` `d` on((`r`.`winner_driver_id` = `d`.`id`))) left join `teams` `tm` on((`r`.`winner_team_id` = `tm`.`id`))) WHERE (`r`.`status` = 'completed') ORDER BY `r`.`race_date` DESC LIMIT 0, 10 ;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `drivers`
--
ALTER TABLE `drivers`
  ADD CONSTRAINT `fk_drivers_team` FOREIGN KEY (`team_id`) REFERENCES `teams` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `forum_likes`
--
ALTER TABLE `forum_likes`
  ADD CONSTRAINT `fk_likes_post` FOREIGN KEY (`post_id`) REFERENCES `forum_posts` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_likes_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `forum_posts`
--
ALTER TABLE `forum_posts`
  ADD CONSTRAINT `fk_posts_author` FOREIGN KEY (`author_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_posts_thread` FOREIGN KEY (`thread_id`) REFERENCES `forum_threads` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `forum_threads`
--
ALTER TABLE `forum_threads`
  ADD CONSTRAINT `fk_threads_author` FOREIGN KEY (`author_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_threads_category` FOREIGN KEY (`category_id`) REFERENCES `forum_categories` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `news`
--
ALTER TABLE `news`
  ADD CONSTRAINT `fk_news_author` FOREIGN KEY (`author_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `races`
--
ALTER TABLE `races`
  ADD CONSTRAINT `fk_races_fastest_lap` FOREIGN KEY (`fastest_lap_driver_id`) REFERENCES `drivers` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_races_pole_driver` FOREIGN KEY (`pole_driver_id`) REFERENCES `drivers` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_races_track` FOREIGN KEY (`track_id`) REFERENCES `tracks` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_races_winner_driver` FOREIGN KEY (`winner_driver_id`) REFERENCES `drivers` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_races_winner_team` FOREIGN KEY (`winner_team_id`) REFERENCES `teams` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `user_sessions`
--
ALTER TABLE `user_sessions`
  ADD CONSTRAINT `fk_sessions_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
