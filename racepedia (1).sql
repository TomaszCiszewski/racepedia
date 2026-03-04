-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Host: localhost:8889
-- Generation Time: Mar 04, 2026 at 07:00 AM
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

--
-- Dumping data for table `drivers`
--

INSERT INTO `drivers` (`id`, `full_name`, `number`, `country`, `team_id`, `birth_date`, `birth_place`, `height_cm`, `weight_kg`, `debut_year`, `world_titles`, `race_wins`, `podiums`, `pole_positions`, `fastest_laps`, `points_total`, `bio`, `image_path`, `is_active`) VALUES
(1, 'Max Verstappen', 1, 'Holandia', 1, NULL, NULL, NULL, NULL, NULL, 4, 0, 0, 0, 0, 0, '4-krotny mistrz świata F1. Obrońca tytułu.', 'verstappen.png', 1),
(2, 'Lewis Hamilton', 44, 'Wielka Brytania', 2, NULL, NULL, NULL, NULL, NULL, 7, 0, 0, 0, 0, 0, '7-krotny mistrz świata. Jeden z najbardziej utytułowanych kierowców w historii F1.', 'hamilton.png', 1),
(3, 'Fernando Alonso', 14, 'Hiszpania', 5, NULL, NULL, NULL, NULL, NULL, 2, 0, 0, 0, 0, 0, '2-krotny mistrz świata. Legenda F1, znany z niesamowitej determinacji i doświadczenia.', 'alonso.png', 1),
(4, 'Charles Leclerc', 16, 'Monako', 2, NULL, NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0, 'Utalentowany kierowca Ferrari, wielokrotny zwycięzca Grand Prix.', 'leclerc.png', 1),
(5, 'Lando Norris', 4, 'Wielka Brytania', 4, NULL, NULL, NULL, NULL, NULL, 1, 0, 0, 0, 0, 0, 'Młody talent McLarena, znany z szybkości i aktywnej obecności w mediach.', 'norris.png', 1),
(6, 'Oscar Piastri', 81, 'Australia', 4, NULL, NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0, 'Utalentowany Australijczyk, mistrz F2 i F3, przyszłość McLarena.', 'piastri.png', 1),
(7, 'George Russell', 63, 'Wielka Brytania', 3, NULL, NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0, 'Lider Mercedesa, dyrektor GPDA. Jeden z najjaśniejszych talentów w stawce.', 'russell.png', 1),
(8, 'Kimi Antonelli', 12, 'Włochy', 3, NULL, NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0, 'Młody włoski talent, mistrz FRECA i F4, przyszłość Mercedesa.', 'antonelli.png', 1),
(9, 'Carlos Sainz', 55, 'Hiszpania', 7, NULL, NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0, 'Doświadczony Hiszpan, zwycięzca Grand Prix, teraz w Williamsie.', 'sainz.png', 1),
(10, 'Alexander Albon', 23, 'Wielka Brytania', 7, NULL, NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0, 'Utalentowany kierowca, kilkukrotnie na podium, lider Williamsa.', 'albon.png', 1),
(11, 'Pierre Gasly', 10, 'Francja', 6, NULL, NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0, 'Zwycięzca Grand Prix, mistrz determinacji, teraz w Alpine.', 'gasly.png', 1),
(12, 'Franco Colapinto', NULL, 'Argentyna', 6, NULL, NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0, 'Młody argentyński talent, przyszłość Alpine.', 'colapinto.png', 1),
(13, 'Lance Stroll', 18, 'Kanada', 5, NULL, NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0, 'Doświadczony Kanadyjczyk, kilkukrotnie na podium w F1.', 'stroll.png', 1),
(14, 'Nico Hülkenberg', 27, 'Niemcy', 8, NULL, NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0, 'Doświadczony Niemiec, znany z niesamowitych kwalifikacji, teraz w Audi.', 'hulkenberg.png', 1),
(15, 'Gabriel Bortoleto', NULL, 'Brazylia', 8, NULL, NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0, 'Młody brazylijski talent, mistrz F3, przyszłość Audi.', 'bortoleto.png', 1),
(16, 'Sergio Perez', 11, 'Meksyk', 9, NULL, NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0, 'Specjalista od opon, wielokrotny zwycięzca Grand Prix, teraz w Cadillac.', 'perez.png', 1),
(17, 'Valtteri Bottas', 77, 'Finlandia', 9, NULL, NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0, '10-krotny zwycięzca Grand Prix, doświadczenie z Mercedesa, teraz w Cadillac.', 'bottas.png', 1),
(18, 'Esteban Ocon', 31, 'Francja', 10, NULL, NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0, 'Zwycięzca Grand Prix, utalentowany Francuz, teraz w Haas.', 'ocon.png', 1),
(19, 'Oliver Bearman', NULL, 'Wielka Brytania', 10, NULL, NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0, 'Młody brytyjski talent, przyszłość Haasa.', 'bearman.png', 1),
(20, 'Liam Lawson', 30, 'Nowa Zelandia', 11, NULL, NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0, 'Młody Nowozelandczyk, utalentowany kierowca Red Bulla.', 'lawson.png', 1),
(21, 'Arvid Lindblad', NULL, 'Szwecja', 11, NULL, NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0, 'Młody szwedzki talent, przyszłość Red Bulla.', 'lindblad.png', 1),
(22, 'Isack Hadjar', NULL, 'Francja', 1, NULL, NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0, 'Młody francuski talent, członek akademii Red Bulla.', 'hadjar.png', 1);

-- --------------------------------------------------------

--
-- Table structure for table `f1_constructor_standings`
--

CREATE TABLE `f1_constructor_standings` (
  `id` int NOT NULL,
  `team_id` int NOT NULL,
  `season` year NOT NULL,
  `points` decimal(6,2) NOT NULL DEFAULT '0.00',
  `position` int DEFAULT NULL,
  `wins` int NOT NULL DEFAULT '0',
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `f1_driver_standings`
--

CREATE TABLE `f1_driver_standings` (
  `id` int NOT NULL,
  `driver_id` int NOT NULL,
  `season` year NOT NULL,
  `points` decimal(6,2) NOT NULL DEFAULT '0.00',
  `position` int DEFAULT NULL,
  `wins` int NOT NULL DEFAULT '0',
  `podiums` int NOT NULL DEFAULT '0',
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `f1_races_2026`
--

CREATE TABLE `f1_races_2026` (
  `id` int NOT NULL,
  `round` int NOT NULL,
  `grand_prix` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `circuit` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `country` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `flag_code` varchar(5) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `date` date NOT NULL,
  `qualifying_date` date DEFAULT NULL,
  `sprint_date` date DEFAULT NULL,
  `status` enum('upcoming','completed','ongoing') COLLATE utf8mb4_unicode_ci DEFAULT 'upcoming',
  `winner_driver_id` int DEFAULT NULL,
  `winner_team_id` int DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `f1_races_2026`
--

INSERT INTO `f1_races_2026` (`id`, `round`, `grand_prix`, `circuit`, `country`, `flag_code`, `date`, `qualifying_date`, `sprint_date`, `status`, `winner_driver_id`, `winner_team_id`, `created_at`, `updated_at`) VALUES
(1, 1, 'Grand Prix Australii', 'Albert Park Circuit', 'Australia', 'au', '2026-03-15', '2026-03-14', NULL, 'upcoming', NULL, NULL, '2026-02-16 19:18:26', NULL),
(2, 2, 'Grand Prix Chin', 'Shanghai International Circuit', 'Chiny', 'cn', '2026-03-22', '2026-03-21', '2026-03-21', 'upcoming', NULL, NULL, '2026-02-16 19:18:26', NULL),
(3, 3, 'Grand Prix Japonii', 'Suzuka International Racing Course', 'Japonia', 'jp', '2026-04-05', '2026-04-04', NULL, 'upcoming', NULL, NULL, '2026-02-16 19:18:26', NULL),
(4, 4, 'Grand Prix Bahrajnu', 'Bahrain International Circuit', 'Bahrajn', 'bh', '2026-04-12', '2026-04-11', '2026-04-11', 'upcoming', NULL, NULL, '2026-02-16 19:18:26', NULL),
(5, 5, 'Grand Prix Arabii Saudyjskiej', 'Jeddah Corniche Circuit', 'Arabia Saudyjska', 'sa', '2026-04-19', '2026-04-18', NULL, 'upcoming', NULL, NULL, '2026-02-16 19:18:26', NULL),
(6, 6, 'Grand Prix Miami', 'Miami International Autodrome', 'USA', 'us', '2026-05-03', '2026-05-02', '2026-05-02', 'upcoming', NULL, NULL, '2026-02-16 19:18:26', NULL),
(7, 7, 'Grand Prix Emilii-Romanii', 'Imola Circuit', 'Włochy', 'it', '2026-05-17', '2026-05-16', NULL, 'upcoming', NULL, NULL, '2026-02-16 19:18:26', NULL),
(8, 8, 'Grand Prix Monako', 'Circuit de Monaco', 'Monako', 'mc', '2026-05-24', '2026-05-23', NULL, 'upcoming', NULL, NULL, '2026-02-16 19:18:26', NULL),
(9, 9, 'Grand Prix Hiszpanii', 'Circuit de Barcelona-Catalunya', 'Hiszpania', 'es', '2026-06-01', '2026-05-31', NULL, 'upcoming', NULL, NULL, '2026-02-16 19:18:26', NULL),
(10, 10, 'Grand Prix Kanady', 'Circuit Gilles Villeneuve', 'Kanada', 'ca', '2026-06-15', '2026-06-14', '2026-06-14', 'upcoming', NULL, NULL, '2026-02-16 19:18:26', NULL),
(11, 11, 'Grand Prix Austrii', 'Red Bull Ring', 'Austria', 'at', '2026-06-29', '2026-06-28', '2026-06-28', 'upcoming', NULL, NULL, '2026-02-16 19:18:26', NULL),
(12, 12, 'Grand Prix Wielkiej Brytanii', 'Silverstone Circuit', 'Wielka Brytania', 'gb', '2026-07-06', '2026-07-05', '2026-07-05', 'upcoming', NULL, NULL, '2026-02-16 19:18:26', NULL),
(13, 13, 'Grand Prix Belgii', 'Circuit de Spa-Francorchamps', 'Belgia', 'be', '2026-07-27', '2026-07-26', NULL, 'upcoming', NULL, NULL, '2026-02-16 19:18:26', NULL),
(14, 14, 'Grand Prix Węgier', 'Hungaroring', 'Węgry', 'hu', '2026-08-03', '2026-08-02', NULL, 'upcoming', NULL, NULL, '2026-02-16 19:18:26', NULL),
(15, 15, 'Grand Prix Holandii', 'Circuit Zandvoort', 'Holandia', 'nl', '2026-08-24', '2026-08-23', '2026-08-23', 'upcoming', NULL, NULL, '2026-02-16 19:18:26', NULL),
(16, 16, 'Grand Prix Włoch', 'Autodromo Nazionale Monza', 'Włochy', 'it', '2026-09-07', '2026-09-06', NULL, 'upcoming', NULL, NULL, '2026-02-16 19:18:26', NULL),
(17, 17, 'Grand Prix Azerbejdżanu', 'Baku City Circuit', 'Azerbejdżan', 'az', '2026-09-21', '2026-09-20', NULL, 'upcoming', NULL, NULL, '2026-02-16 19:18:26', NULL),
(18, 18, 'Grand Prix Singapuru', 'Marina Bay Street Circuit', 'Singapur', 'sg', '2026-10-05', '2026-10-04', '2026-10-04', 'upcoming', NULL, NULL, '2026-02-16 19:18:26', NULL),
(19, 19, 'Grand Prix Stanów Zjednoczonych', 'Circuit of the Americas', 'USA', 'us', '2026-10-19', '2026-10-18', '2026-10-18', 'upcoming', NULL, NULL, '2026-02-16 19:18:26', NULL),
(20, 20, 'Grand Prix Meksyku', 'Autódromo Hermanos Rodríguez', 'Meksyk', 'mx', '2026-10-26', '2026-10-25', NULL, 'upcoming', NULL, NULL, '2026-02-16 19:18:26', NULL),
(21, 21, 'Grand Prix Brazylii', 'Autódromo José Carlos Pace', 'Brazylia', 'br', '2026-11-09', '2026-11-08', '2026-11-08', 'upcoming', NULL, NULL, '2026-02-16 19:18:26', NULL),
(22, 22, 'Grand Prix Las Vegas', 'Las Vegas Strip Circuit', 'USA', 'us', '2026-11-23', '2026-11-22', NULL, 'upcoming', NULL, NULL, '2026-02-16 19:18:26', NULL),
(23, 23, 'Grand Prix Kataru', 'Lusail International Circuit', 'Katar', 'qa', '2026-12-01', '2026-11-30', '2026-11-30', 'upcoming', NULL, NULL, '2026-02-16 19:18:26', NULL),
(24, 24, 'Grand Prix Abu Zabi', 'Yas Marina Circuit', 'ZEA', 'ae', '2026-12-08', '2026-12-07', NULL, 'upcoming', NULL, NULL, '2026-02-16 19:18:26', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `f1_race_results`
--

CREATE TABLE `f1_race_results` (
  `id` int NOT NULL,
  `race_id` int NOT NULL,
  `driver_id` int NOT NULL,
  `team_id` int NOT NULL,
  `position` int DEFAULT NULL,
  `grid` int DEFAULT NULL,
  `points` decimal(5,2) NOT NULL DEFAULT '0.00',
  `status` enum('finished','dnf','dsq') COLLATE utf8mb4_unicode_ci DEFAULT 'finished',
  `fastest_lap` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
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
-- Dumping data for table `forum_posts`
--

INSERT INTO `forum_posts` (`id`, `thread_id`, `content`, `author_id`, `created_at`, `updated_at`, `is_edited`, `is_solution`) VALUES
(1, 1, 'Testuje treść i sposób działania wątków', 2, '2026-02-19 14:46:01', NULL, 0, 0),
(2, 1, 'Działają odpowiedzi', 2, '2026-02-19 14:46:20', NULL, 0, 0),
(3, 1, '[quote=\"ciszewa\"]Działają odpowiedzi[/quote]\r\n\r\nDziałają cytaty.', 2, '2026-02-19 14:46:34', NULL, 0, 0),
(4, 1, '[quote=\"ciszewa\"][quote=\"ciszewa\"]Działają odpowiedzi[/quote]\r\n\r\nDziałają cytaty.[/quote]\r\n\r\nNie wiem czy działa', 2, '2026-02-19 14:49:44', '2026-02-19 15:00:49', 1, 0),
(5, 1, 'Zażółć gęślą jaźń - test polskich znaków', 1, '2026-02-19 15:34:41', NULL, 0, 0);

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

--
-- Dumping data for table `forum_threads`
--

INSERT INTO `forum_threads` (`id`, `category_id`, `title`, `content`, `author_id`, `created_at`, `updated_at`, `views`, `is_pinned`, `is_locked`, `last_post_id`, `last_post_at`) VALUES
(1, 5, 'Testy', 'Testuje treść i sposób działania wątków', 2, '2026-02-19 14:46:01', '2026-02-19 15:40:46', 16, 0, 0, 5, '2026-02-19 15:34:41');

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

--
-- Dumping data for table `teams`
--

INSERT INTO `teams` (`id`, `name`, `full_name`, `country`, `base_location`, `team_principal`, `chassis`, `power_unit`, `debut_year`, `world_titles`, `logo_path`, `car_image_path`, `bio`, `is_active`) VALUES
(1, 'Red Bull Racing', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, 1),
(2, 'Ferrari', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, 1),
(3, 'Mercedes', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, 1),
(4, 'McLaren', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, 1),
(5, 'Aston Martin', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, 1),
(6, 'Alpine', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, 1),
(7, 'Williams', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, 1),
(8, 'Audi', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, 1),
(9, 'Cadillac', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, 1),
(10, 'Haas F1 Team', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, 1),
(11, 'Racing Bulls', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, 1);

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

--
-- Dumping data for table `tracks`
--

INSERT INTO `tracks` (`id`, `name`, `country`, `city`, `length_km`, `turns`, `laps`, `lap_record`, `lap_record_driver`, `lap_record_year`, `first_gp`, `capacity`, `image_path`, `circuit_map_path`, `bio`, `is_active`) VALUES
(1, 'Albert Park Circuit', 'Australia', 'Melbourne', 5.278, NULL, 58, '1:20.235', 'Lewis Hamilton', '2021', '1996', NULL, 'australia.png', NULL, 'Uliczny tor w Melbourne. Szybki, częściowo stały, częściowo uliczny, z słynnym zakrętem 11 i 12. Rozpoczyna sezon 2026.', 1),
(2, 'Shanghai International Circuit', 'Chiny', 'Szanghaj', 5.451, NULL, 56, '1:31.095', 'Sebastian Vettel', '2018', '2004', NULL, 'chiny.png', NULL, 'Nowoczesny tor zaprojektowany przez Hermanna Tilke. Charakteryzuje się unikalnym układem i długą prostą startową.', 1),
(3, 'Suzuka International Racing Course', 'Japonia', 'Suzuka', 5.807, NULL, 53, '1:27.064', 'Lewis Hamilton', '2019', '1962', NULL, 'japonia.png', NULL, 'Kultowa ósemka. Jeden z najbardziej wymagających technicznie torów w kalendarzu, uwielbiany przez kierowców.', 1),
(4, 'Bahrain International Circuit', 'Bahrajn', 'Sakhir', 5.412, NULL, 57, '1:27.264', 'Lewis Hamilton', '2021', '2004', NULL, 'bahrajn.png', NULL, 'Tor w Sakhir, tradycyjne miejsce testów przedsezonowych. Nowoczesny obiekt z długimi prostymi i wymagającymi zakrętami.', 1),
(5, 'Jeddah Corniche Circuit', 'Arabia Saudyjska', 'Dżudda', 6.174, NULL, 50, '1:27.511', 'Lewis Hamilton', '2021', '2021', NULL, 'arabiasaudyjska.png', NULL, 'Najszybszy uliczny tor w kalendarzu. Wysokie prędkości i ciasne bariery tworzą niesamowite widowisko.', 1),
(6, 'Miami International Autodrome', 'USA', 'Miami', 5.412, NULL, 57, '1:27.241', 'Max Verstappen', '2024', '2022', NULL, 'usa1.png', NULL, 'Tor wokół stadionu Hard Rock. Mieszanka szybkich sekcji i technicznych zakrętów w klimacie Miami.', 1),
(7, 'Circuit Gilles Villeneuve', 'Kanada', 'Montreal', 4.361, NULL, 70, '1:13.078', 'Valtteri Bottas', '2019', '1978', NULL, 'kanada.png', NULL, 'Półstały tor w Montrealu. Słynie z długich prostych, \'ściany mistrzów\' i nieprzewidywalnej pogody.', 1),
(8, 'Circuit de Monaco', 'Monako', 'Monte Carlo', 3.337, NULL, 78, '1:12.909', 'Lewis Hamilton', '2021', '1950', NULL, 'monako.png', NULL, 'Perła koronna F1. Wąskie ulice, prestiż i glamour. Najwolniejszy, ale najbardziej prestiżowy tor w kalendarzu.', 1),
(9, 'Circuit de Barcelona-Catalunya', 'Hiszpania', 'Barcelona', 4.657, NULL, 66, '1:16.330', 'Max Verstappen', '2023', '1991', NULL, 'barcelona.png', NULL, 'Klasyczny tor używany do testów zimowych. Różnorodne zakręty sprawdzają wszystkie aspekty bolidu.', 1),
(10, 'Red Bull Ring', 'Austria', 'Spielberg', 4.318, NULL, 71, '1:02.939', 'Carlos Sainz', '2024', '1970', NULL, 'austria.png', NULL, 'Krótki, ale szybki tor w Alpach. Krótkie okrążenia oznaczają dużo akcji i walkę na dystansie.', 1),
(11, 'Silverstone Circuit', 'Wielka Brytania', 'Silverstone', 5.891, NULL, 52, '1:24.303', 'Lewis Hamilton', '2019', '1950', NULL, 'uk.png', NULL, 'Home of British Motor Racing. Szybkie, płynne zakręty jak Maggots, Becketts i Chapel to marzenie każdego kierowcy.', 1),
(12, 'Circuit de Spa-Francorchamps', 'Belgia', 'Spa', 7.004, NULL, 44, '1:44.701', 'Lewis Hamilton', '2017', '1950', NULL, 'belgia.png', NULL, 'Kultowe Eau Rouge i Raidillon. Najdłuższy i jeden z najbardziej wymagających torów w kalendarzu.', 1),
(13, 'Hungaroring', 'Węgry', 'Budapeszt', 4.381, NULL, 70, '1:16.627', 'Lewis Hamilton', '2020', '1986', NULL, 'wegry.png', NULL, 'Ciasny, kręty tor pod Budapesztem. Trudny do wyprzedzania, ale technicznie wymagający dla kierowców.', 1),
(14, 'Circuit Zandvoort', 'Holandia', 'Zandvoort', 4.259, NULL, 72, '1:09.837', 'Lewis Hamilton', '2021', '1952', NULL, 'holandia.png', NULL, 'Odnowiony tor z bandami i nachylonymi zakrętami. Kibice w pomarańczowym szale tworzą niesamowitą atmosferę.', 1),
(15, 'Autodromo Nazionale Monza', 'Włochy', 'Monza', 5.793, NULL, 53, '1:19.119', 'Lewis Hamilton', '2020', '1950', NULL, 'wlochy.png', NULL, 'Świątynia szybkości. Długie proste i szykany, gdzie liczy się moc silnika i mały opór powietrza.', 1),
(16, 'Madrid Street Circuit (Madring)', 'Hiszpania', 'Madryt', 5.470, NULL, 55, 'Brak danych', 'Debiut w 2026', NULL, '2026', NULL, 'madryt.png', NULL, 'Nowy hybrydowy tor uliczny w Madrycie. Debiutuje w kalendarzu F1 w 2026 roku, zastępując Imolę.', 1),
(17, 'Baku City Circuit', 'Azerbejdżan', 'Baku', 6.003, NULL, 51, '1:40.203', 'Max Verstappen', '2024', '2016', NULL, 'azerbejdzan.png', NULL, 'Uliczny tor w Baku. Mieszanka wąskich sekcji i długiej prostej, gdzie prędkości sięgają 350 km/h.', 1),
(18, 'Marina Bay Street Circuit', 'Singapur', 'Singapur', 5.063, NULL, 61, '1:35.867', 'Lewis Hamilton', '2023', '2008', NULL, 'singapur.png', NULL, 'Pierwszy nocny wyścig w F1. Wilgotność i temperatura sprawiają, że to jeden z najbardziej wymagających fizycznie wyścigów.', 1),
(19, 'Circuit of the Americas', 'USA', 'Austin', 5.513, NULL, 56, '1:34.260', 'Charles Leclerc', '2019', '2012', NULL, 'usa2.png', NULL, 'Nowoczesny tor w Teksasie. Słynie z podjazdu na pierwszy zakręt i sekencji szybkich esów.', 1),
(20, 'Autódromo Hermanos Rodríguez', 'Meksyk', 'Meksyk', 4.304, NULL, 71, '1:14.758', 'Lando Norris', '2024', '1963', NULL, 'meksyk.png', NULL, 'Tor na dużej wysokości, co wpływa na aerodynamikę. Słynie z głośnych i oddanych kibiców.', 1),
(21, 'Autódromo José Carlos Pace (Interlagos)', 'Brazylia', 'São Paulo', 4.309, NULL, 71, '1:08.540', 'Valtteri Bottas', '2018', '1973', NULL, 'brazylia.png', NULL, 'Tor w Interlagos. Kultowe zakręty, zmienna pogoda i niesamowita atmosfera tworzą legendę tego miejsca.', 1),
(22, 'Las Vegas Strip Circuit', 'USA', 'Las Vegas', 6.201, NULL, 50, '1:33.412', 'Oscar Piastri', '2024', '2023', NULL, 'usa3.png', NULL, 'Tor na Stripie w Las Vegas. Najdłuższa prosta w kalendarzu i nocny wyścig wśród kasyn.', 1),
(23, 'Lusail International Circuit', 'Katar', 'Lusail', 5.419, NULL, 57, '1:22.384', 'Lando Norris', '2024', '2021', NULL, 'katar.png', NULL, 'Nowoczesny tor w Katarze. Szybkie zakręty i wymagające warunki fizyczne dla kierowców.', 1),
(24, 'Yas Marina Circuit', 'Zjednoczone Emiraty Arabskie', 'Abu Zabi', 5.281, NULL, 58, '1:24.319', 'Lewis Hamilton', '2021', '2009', NULL, 'zea.png', NULL, 'Finał sezonu. Nowoczesny tor z tunelem i wyścigiem o zachodzie słońca, kończący się pod pałacem.', 1);

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
`country` varchar(50)
,`fastest_laps` int
,`full_name` varchar(100)
,`id` int
,`number` int
,`podiums` int
,`points_total` int
,`pole_positions` int
,`race_wins` int
,`team_name` varchar(100)
,`world_titles` int
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `v_last_races`
-- (See below for the actual view)
--
CREATE TABLE `v_last_races` (
`id` int
,`name` varchar(200)
,`race_date` date
,`round` int
,`season` year
,`track_country` varchar(50)
,`track_name` varchar(100)
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
-- Indexes for table `f1_constructor_standings`
--
ALTER TABLE `f1_constructor_standings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `team_season` (`team_id`,`season`),
  ADD KEY `idx_position` (`position`);

--
-- Indexes for table `f1_driver_standings`
--
ALTER TABLE `f1_driver_standings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `driver_season` (`driver_id`,`season`),
  ADD KEY `idx_position` (`position`);

--
-- Indexes for table `f1_races_2026`
--
ALTER TABLE `f1_races_2026`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `round` (`round`),
  ADD KEY `idx_date` (`date`),
  ADD KEY `idx_status` (`status`),
  ADD KEY `fk_winner_driver` (`winner_driver_id`),
  ADD KEY `fk_winner_team` (`winner_team_id`);

--
-- Indexes for table `f1_race_results`
--
ALTER TABLE `f1_race_results`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_race` (`race_id`),
  ADD KEY `idx_driver` (`driver_id`),
  ADD KEY `idx_team` (`team_id`);

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
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `f1_constructor_standings`
--
ALTER TABLE `f1_constructor_standings`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `f1_driver_standings`
--
ALTER TABLE `f1_driver_standings`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `f1_races_2026`
--
ALTER TABLE `f1_races_2026`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `f1_race_results`
--
ALTER TABLE `f1_race_results`
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
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `forum_threads`
--
ALTER TABLE `forum_threads`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

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
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `tracks`
--
ALTER TABLE `tracks`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

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
-- Constraints for table `f1_constructor_standings`
--
ALTER TABLE `f1_constructor_standings`
  ADD CONSTRAINT `fk_constructor_standings_team` FOREIGN KEY (`team_id`) REFERENCES `teams` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `f1_driver_standings`
--
ALTER TABLE `f1_driver_standings`
  ADD CONSTRAINT `fk_standings_driver` FOREIGN KEY (`driver_id`) REFERENCES `drivers` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `f1_races_2026`
--
ALTER TABLE `f1_races_2026`
  ADD CONSTRAINT `fk_f1_races_winner_driver` FOREIGN KEY (`winner_driver_id`) REFERENCES `drivers` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_f1_races_winner_team` FOREIGN KEY (`winner_team_id`) REFERENCES `teams` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `f1_race_results`
--
ALTER TABLE `f1_race_results`
  ADD CONSTRAINT `fk_results_driver` FOREIGN KEY (`driver_id`) REFERENCES `drivers` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_results_race` FOREIGN KEY (`race_id`) REFERENCES `f1_races_2026` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_results_team` FOREIGN KEY (`team_id`) REFERENCES `teams` (`id`) ON DELETE CASCADE;

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
