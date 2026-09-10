-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : mer. 09 sep. 2026 à 10:24
-- Version du serveur : 10.4.32-MariaDB
-- Version de PHP : 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `kosi_pointage`
--

-- --------------------------------------------------------

--
-- Structure de la table `activity_logs`
--

CREATE TABLE `activity_logs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `user_email` varchar(255) NOT NULL,
  `user_role` varchar(50) DEFAULT NULL,
  `action` varchar(50) NOT NULL,
  `model_type` varchar(100) DEFAULT NULL,
  `model_id` bigint(20) UNSIGNED DEFAULT NULL,
  `model_label` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `ip_address` varchar(45) NOT NULL,
  `user_agent` varchar(500) DEFAULT NULL,
  `SiegeID` int(10) UNSIGNED DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `activity_logs`
--

INSERT INTO `activity_logs` (`id`, `user_id`, `user_email`, `user_role`, `action`, `model_type`, `model_id`, `model_label`, `description`, `ip_address`, `user_agent`, `SiegeID`, `created_at`) VALUES
(1, 1, 'admin@kosi-time.com', 'superadmin', 'login_success', 'Administration', 1, 'admin@kosi-time.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-06 07:19:49'),
(2, 1, 'admin@kosi-time.com', 'superadmin', 'update', 'EntrepriseSiege', 8, 'Réel Electrique TEST', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-06 07:57:10'),
(3, 1, 'admin@kosi-time.com', 'superadmin', 'toggle_active', 'Administration', 12, 'sel@kosi-time.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-06 07:58:03'),
(4, 1, 'admin@kosi-time.com', 'superadmin', 'delete', 'Entreprise', 24, 'TEST SITE', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-06 07:58:24'),
(5, 1, 'admin@kosi-time.com', 'superadmin', 'delete', 'Employe', 55, 'Emp 2', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-06 07:58:47'),
(6, 1, 'admin@kosi-time.com', 'superadmin', 'export_excel', 'JourNonTravaille', NULL, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-06 07:59:56'),
(7, 1, 'admin@kosi-time.com', 'superadmin', 'export_csv', 'ActivityLog', NULL, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-06 08:12:58'),
(8, 1, 'admin@kosi-time.com', 'superadmin', 'update', 'Employe', 35, 'abdouroihamane saidal', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-06 08:18:05'),
(9, 1, 'admin@kosi-time.com', 'superadmin', 'logout', 'Administration', 1, 'admin@kosi-time.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-06 08:51:04'),
(10, 1, 'admin@kosi-time.com', 'superadmin', 'logout', 'Administration', 1, 'admin@kosi-time.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-06 08:51:04'),
(11, NULL, 'admin@kosi-time.cdom', NULL, 'login_failed', NULL, NULL, NULL, 'Identifiant introuvable', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-06 08:51:11'),
(12, 1, 'admin@kosi-time.com', 'superadmin', 'login_success', 'Administration', 1, 'admin@kosi-time.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-06 08:51:20'),
(13, 1, 'admin@kosi-time.com', 'superadmin', 'login_success', 'Administration', 1, 'admin@kosi-time.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-06 08:51:20'),
(14, 1, 'admin@kosi-time.com', 'superadmin', 'delete', 'Pointage', 1519, '42', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-06 09:01:44'),
(15, 1, 'admin@kosi-time.com', 'superadmin', 'update', 'Employe', 35, 'abdouroihamane saidal', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-06 10:18:57'),
(16, 1, 'admin@kosi-time.com', 'superadmin', 'export_excel', 'EntrepriseSiege', NULL, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-06 10:54:32'),
(17, 1, 'admin@kosi-time.com', 'superadmin', 'export_excel', 'EntrepriseSiege', NULL, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-06 11:05:52'),
(18, 1, 'admin@kosi-time.com', 'superadmin', 'export_pdf', 'EntrepriseSiege', NULL, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-06 11:06:39'),
(19, 1, 'admin@kosi-time.com', 'superadmin', 'export_pdf', 'EntrepriseSiege', NULL, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-06 11:11:58'),
(20, 1, 'admin@kosi-time.com', 'superadmin', 'update', 'EntrepriseSiege', 8, 'Réel Electrique TEST', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-06 11:30:00'),
(21, 1, 'admin@kosi-time.com', 'superadmin', 'export_excel', 'EntrepriseSiege', NULL, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-06 11:46:15'),
(22, 1, 'admin@kosi-time.com', 'superadmin', 'update', 'EntrepriseSiege', 4, 'PRO ELEC SARL', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-06 11:49:51'),
(23, 1, 'admin@kosi-time.com', 'superadmin', 'update', 'EntrepriseSiege', 2, 'Run-Telemat SIEGE', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-06 11:50:50'),
(24, 1, 'admin@kosi-time.com', 'superadmin', 'update', 'EntrepriseSiege', 4, 'PRO ELEC SARL', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-06 11:53:18'),
(25, 1, 'admin@kosi-time.com', 'superadmin', 'update', 'EntrepriseSiege', 2, 'Run-Telemat SIEGE', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-06 11:53:26'),
(26, 1, 'admin@kosi-time.com', 'superadmin', 'update', 'EntrepriseSiege', 8, 'Réel Electrique TEST', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-06 11:53:31'),
(27, 1, 'admin@kosi-time.com', 'superadmin', 'delete', 'Administration', 7, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-06 12:50:44'),
(28, 1, 'admin@kosi-time.com', 'superadmin', 'delete', 'Administration', 4, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-06 12:50:49'),
(29, 1, 'admin@kosi-time.com', 'superadmin', 'delete', 'Administration', 12, 'sel@kosi-time.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-06 12:52:19'),
(30, 1, 'admin@kosi-time.com', 'superadmin', 'create', 'Administration', 13, 'admin@sel.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-06 12:52:42'),
(31, 1, 'admin@kosi-time.com', 'superadmin', 'delete', 'Administration', 13, 'admin@sel.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-06 12:55:01'),
(32, 1, 'admin@kosi-time.com', 'superadmin', 'delete', 'Entreprise', NULL, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-06 13:07:03'),
(33, 1, 'admin@kosi-time.com', 'superadmin', 'update', 'Employe', 36, 'ahmed mohamed roukia', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-06 13:09:51'),
(34, 1, 'admin@kosi-time.com', 'superadmin', 'update', 'Employe', 38, 'ahmed wassim', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-06 13:11:48'),
(35, 1, 'admin@kosi-time.com', 'superadmin', 'update', 'EntrepriseSiege', 1, 'Geotrack Solution SIEGE', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-06 13:14:00'),
(36, 1, 'admin@kosi-time.com', 'superadmin', 'logout', 'Administration', 1, 'admin@kosi-time.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-06 13:15:11'),
(37, 1, 'admin@kosi-time.com', 'superadmin', 'logout', 'Administration', 1, 'admin@kosi-time.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-06 13:15:11'),
(38, NULL, 'admin@geotrack.com', NULL, 'login_failed', NULL, NULL, NULL, 'Compte désactivé', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-06 13:15:44'),
(39, NULL, 'admin@geotrack.com', NULL, 'login_failed', NULL, NULL, NULL, 'Compte désactivé', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-06 13:15:55'),
(40, NULL, 'admin@geotrack.com', NULL, 'login_failed', NULL, NULL, NULL, 'Compte désactivé', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-06 13:16:23'),
(41, NULL, 'admin@geotrack.com', NULL, 'login_failed', NULL, NULL, NULL, 'Compte désactivé', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-06 13:16:40'),
(42, 1, 'admin@kosi-time.com', 'superadmin', 'login_success', 'Administration', 1, 'admin@kosi-time.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-06 13:16:52'),
(43, 1, 'admin@kosi-time.com', 'superadmin', 'login_success', 'Administration', 1, 'admin@kosi-time.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-06 13:16:52'),
(44, 1, 'admin@kosi-time.com', 'superadmin', 'update', 'Employe', 38, 'ahmed wassim', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-06 13:23:57'),
(45, 1, 'admin@kosi-time.com', 'superadmin', 'update', 'EntrepriseSiege', 8, 'Réel Electrique TEST', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-06 13:55:01'),
(46, 1, 'admin@kosi-time.com', 'superadmin', 'reset', 'Administration', 2, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-06 14:02:36'),
(47, 1, 'admin@kosi-time.com', 'superadmin', 'reset', 'Administration', 7, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-06 14:02:41'),
(48, 1, 'admin@kosi-time.com', 'superadmin', 'login_success', 'Administration', 1, 'admin@kosi-time.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-09 05:28:22'),
(49, 1, 'admin@kosi-time.com', 'superadmin', 'login_success', 'Administration', 1, 'admin@kosi-time.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-09 05:28:22'),
(50, 1, 'admin@kosi-time.com', 'superadmin', 'update', 'EntrepriseSiege', 4, 'PRO ELEC SARL', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-09 05:38:49'),
(51, 1, 'admin@kosi-time.com', 'superadmin', 'update', 'EntrepriseSiege', 3, 'ISLAND FOOD', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-09 05:38:55'),
(52, 1, 'admin@kosi-time.com', 'superadmin', 'delete', 'Administration', 7, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-09 05:39:41'),
(53, 1, 'admin@kosi-time.com', 'superadmin', 'delete', 'Administration', 2, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-09 05:39:47'),
(54, 1, 'admin@kosi-time.com', 'superadmin', 'reset', 'Administration', 2, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-09 05:39:52'),
(55, 1, 'admin@kosi-time.com', 'superadmin', 'toggle_active', 'Administration', 13, 'admin@sel.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-09 05:43:23'),
(56, 1, 'admin@kosi-time.com', 'superadmin', 'delete', 'Administration', 13, 'admin@sel.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-09 05:48:12'),
(57, 1, 'admin@kosi-time.com', 'superadmin', 'delete', 'Administration', 13, 'admin@sel.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-09 05:52:07'),
(58, 1, 'admin@kosi-time.com', 'superadmin', 'delete', 'Administration', 13, 'admin@sel.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-09 05:52:13'),
(59, 1, 'admin@kosi-time.com', 'superadmin', 'delete', 'Administration', 13, 'admin@sel.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-09 05:57:31'),
(60, 1, 'admin@kosi-time.com', 'superadmin', 'delete', 'Administration', 13, 'admin@sel.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-09 05:57:35'),
(61, 1, 'admin@kosi-time.com', 'superadmin', 'delete', 'Administration', 13, 'admin@sel.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-09 05:58:07'),
(62, 1, 'admin@kosi-time.com', 'superadmin', 'delete', 'Administration', 13, 'admin@sel.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-09 05:58:12'),
(63, 1, 'admin@kosi-time.com', 'superadmin', 'delete', 'Entreprise', 17, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-09 05:58:23'),
(64, 1, 'admin@kosi-time.com', 'superadmin', 'delete', 'Entreprise', 7, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-09 05:58:33'),
(65, 1, 'admin@kosi-time.com', 'superadmin', 'delete', 'Administration', 13, 'admin@sel.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-09 06:00:23'),
(66, 1, 'admin@kosi-time.com', 'superadmin', 'delete', 'Administration', 13, 'admin@sel.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-09 06:02:29'),
(67, 1, 'admin@kosi-time.com', 'superadmin', 'reset', 'Entreprise', 22, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-09 06:09:38'),
(68, 1, 'admin@kosi-time.com', 'superadmin', 'reset', 'Entreprise', 1, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-09 06:09:46'),
(69, 1, 'admin@kosi-time.com', 'superadmin', 'delete', 'Administration', 13, 'admin@sel.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-09 06:09:54'),
(70, 1, 'admin@kosi-time.com', 'superadmin', 'delete', 'Administration', 13, 'admin@sel.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-09 06:11:22'),
(71, 1, 'admin@kosi-time.com', 'superadmin', 'reset', 'Entreprise', 15, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-09 06:12:04'),
(72, 1, 'admin@kosi-time.com', 'superadmin', 'update', 'Employe', 35, 'abdouroihamane saidal', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-09 06:15:43'),
(73, 1, 'admin@kosi-time.com', 'superadmin', 'delete', 'Entreprise', 35, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-09 06:15:43'),
(74, 1, 'admin@kosi-time.com', 'superadmin', 'delete', 'Pointage', 1518, '5', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-09 07:03:24'),
(75, 1, 'admin@kosi-time.com', 'superadmin', 'delete', 'Administration', 13, 'admin@sel.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-09 07:16:07'),
(76, 1, 'admin@kosi-time.com', 'superadmin', 'delete', 'Administration', 13, 'admin@sel.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-09 07:16:12'),
(77, 1, 'admin@kosi-time.com', 'superadmin', 'logout', 'Administration', 1, 'admin@kosi-time.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-09 07:19:09'),
(78, 1, 'admin@kosi-time.com', 'superadmin', 'logout', 'Administration', 1, 'admin@kosi-time.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-09 07:19:09'),
(79, NULL, 'admin@geotrack.com', NULL, 'login_failed', NULL, NULL, NULL, 'Mot de passe incorrect', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-09 07:19:33'),
(80, 2, 'admin@geotrack.com', 'simple_admin', 'login_success', 'Administration', 2, 'admin@geotrack.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', 1, '2026-03-09 07:19:47'),
(81, 2, 'admin@geotrack.com', 'simple_admin', 'login_success', 'Administration', 2, 'admin@geotrack.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', 1, '2026-03-09 07:19:47'),
(82, 2, 'admin@geotrack.com', 'simple_admin', 'logout', 'Administration', 2, 'admin@geotrack.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', 1, '2026-03-09 07:36:48'),
(83, 2, 'admin@geotrack.com', 'simple_admin', 'logout', 'Administration', 2, 'admin@geotrack.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', 1, '2026-03-09 07:36:48'),
(84, 1, 'admin@kosi-time.com', 'superadmin', 'login_success', 'Administration', 1, 'admin@kosi-time.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-09 07:36:59'),
(85, 1, 'admin@kosi-time.com', 'superadmin', 'login_success', 'Administration', 1, 'admin@kosi-time.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-09 07:36:59'),
(86, 1, 'admin@kosi-time.com', 'superadmin', 'create', 'Employe', 57, 'test  ffff', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-09 07:37:24'),
(87, 1, 'admin@kosi-time.com', 'superadmin', 'logout', 'Administration', 1, 'admin@kosi-time.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-09 07:37:45'),
(88, 1, 'admin@kosi-time.com', 'superadmin', 'logout', 'Administration', 1, 'admin@kosi-time.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-09 07:37:45'),
(89, 2, 'admin@geotrack.com', 'simple_admin', 'login_success', 'Administration', 2, 'admin@geotrack.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', 1, '2026-03-09 07:37:57'),
(90, 2, 'admin@geotrack.com', 'simple_admin', 'login_success', 'Administration', 2, 'admin@geotrack.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', 1, '2026-03-09 07:37:57'),
(91, 2, 'admin@geotrack.com', 'simple_admin', 'delete', 'Entreprise', 1, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', 1, '2026-03-09 07:43:18'),
(92, 2, 'admin@geotrack.com', 'simple_admin', 'reset', 'Entreprise', 1, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', 1, '2026-03-09 07:43:26'),
(93, 2, 'admin@geotrack.com', 'simple_admin', 'delete', 'Entreprise', 1, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', 1, '2026-03-09 07:44:23'),
(94, 2, 'admin@geotrack.com', 'simple_admin', 'logout', 'Administration', 2, 'admin@geotrack.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', 1, '2026-03-09 08:25:23'),
(95, 2, 'admin@geotrack.com', 'simple_admin', 'logout', 'Administration', 2, 'admin@geotrack.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', 1, '2026-03-09 08:25:23'),
(96, 1, 'admin@kosi-time.com', 'superadmin', 'login_success', 'Administration', 1, 'admin@kosi-time.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-09 08:25:35'),
(97, 1, 'admin@kosi-time.com', 'superadmin', 'login_success', 'Administration', 1, 'admin@kosi-time.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-09 08:25:35'),
(98, 1, 'admin@kosi-time.com', 'superadmin', 'logout', 'Administration', 1, 'admin@kosi-time.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-09 11:25:48'),
(99, 1, 'admin@kosi-time.com', 'superadmin', 'logout', 'Administration', 1, 'admin@kosi-time.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-09 11:25:48'),
(100, NULL, 'admin@sel.com', NULL, 'login_failed', NULL, NULL, NULL, 'Mot de passe incorrect', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-09 11:25:59'),
(101, 13, 'admin@sel.com', 'seller', 'login_success', 'Administration', 13, 'admin@sel.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-09 11:26:08'),
(102, 13, 'admin@sel.com', 'seller', 'login_success', 'Administration', 13, 'admin@sel.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-09 11:26:08'),
(103, 13, 'admin@sel.com', 'seller', 'logout', 'Administration', 13, 'admin@sel.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-09 11:26:46'),
(104, 13, 'admin@sel.com', 'seller', 'logout', 'Administration', 13, 'admin@sel.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-09 11:26:47'),
(105, NULL, 'admin@geotrack.com', NULL, 'login_failed', NULL, NULL, NULL, 'Mot de passe incorrect', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-09 14:20:26'),
(106, 2, 'admin@geotrack.com', 'simple_admin', 'login_success', 'Administration', 2, 'admin@geotrack.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', 1, '2026-03-09 14:20:35'),
(107, 2, 'admin@geotrack.com', 'simple_admin', 'login_success', 'Administration', 2, 'admin@geotrack.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', 1, '2026-03-09 14:20:35'),
(108, 2, 'admin@geotrack.com', 'simple_admin', 'acknowledge', 'PointageEvent', NULL, 'pointage_weekend - employé #3 - 2026-02-28', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', 1, '2026-03-09 14:40:51'),
(109, 2, 'admin@geotrack.com', 'simple_admin', 'acknowledge', 'PointageEvent', NULL, 'pointage_weekend - employé #3 - 2025-09-06', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', 1, '2026-03-09 14:41:04'),
(110, 2, 'admin@geotrack.com', 'simple_admin', 'acknowledge', 'PointageEvent', NULL, 'pointage_weekend - employé #3 - 2025-09-13', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', 1, '2026-03-09 14:41:09'),
(111, 2, 'admin@geotrack.com', 'simple_admin', 'delete', 'Pointage', 887, '2', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', 1, '2026-03-09 14:41:37'),
(112, 2, 'admin@geotrack.com', 'simple_admin', 'delete', 'Pointage', 889, '2', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', 1, '2026-03-09 14:41:42'),
(113, 2, 'admin@geotrack.com', 'simple_admin', 'delete', 'Pointage', 891, '2', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', 1, '2026-03-09 14:41:47'),
(114, 2, 'admin@geotrack.com', 'simple_admin', 'delete', 'Pointage', 1075, '42', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', 1, '2026-03-09 14:42:06'),
(115, 2, 'admin@geotrack.com', 'simple_admin', 'delete', 'Pointage', 1175, '42', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', 1, '2026-03-09 14:42:12'),
(116, 2, 'admin@geotrack.com', 'simple_admin', 'delete', 'Pointage', 1178, '42', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', 1, '2026-03-09 14:42:18'),
(117, 2, 'admin@geotrack.com', 'simple_admin', 'delete', 'Pointage', 1181, '42', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', 1, '2026-03-09 14:42:22'),
(118, 2, 'admin@geotrack.com', 'simple_admin', 'delete', 'Pointage', 1429, '5', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', 1, '2026-03-09 14:42:31'),
(119, 2, 'admin@geotrack.com', 'simple_admin', 'logout', 'Administration', 2, 'admin@geotrack.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', 1, '2026-03-09 14:44:47'),
(120, 2, 'admin@geotrack.com', 'simple_admin', 'logout', 'Administration', 2, 'admin@geotrack.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', 1, '2026-03-09 14:44:47'),
(121, NULL, 'admin@geotrack.com', NULL, 'login_failed', NULL, NULL, NULL, 'Mot de passe incorrect', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-10 05:31:15'),
(122, 2, 'admin@geotrack.com', 'simple_admin', 'login_success', 'Administration', 2, 'admin@geotrack.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', 1, '2026-03-10 05:31:23'),
(123, 2, 'admin@geotrack.com', 'simple_admin', 'login_success', 'Administration', 2, 'admin@geotrack.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', 1, '2026-03-10 05:31:23'),
(124, 2, 'admin@geotrack.com', 'simple_admin', 'create', 'PointageEventException', 4, '1', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', 1, '2026-03-10 06:21:18'),
(125, 2, 'admin@geotrack.com', 'simple_admin', 'acknowledge', 'PointageEvent', NULL, 'manque_sortie - employé #1 - 2025-09-19', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', 1, '2026-03-10 06:21:18'),
(126, 2, 'admin@geotrack.com', 'simple_admin', 'update', 'PointageEventException', 4, '1', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', 1, '2026-03-10 06:21:54'),
(127, 2, 'admin@geotrack.com', 'simple_admin', 'acknowledge', 'PointageEvent', NULL, 'manque_sortie - employé #1 - 2025-09-19', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', 1, '2026-03-10 06:21:54'),
(128, 2, 'admin@geotrack.com', 'simple_admin', 'update', 'PointageEventException', 4, '1', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', 1, '2026-03-10 06:22:05'),
(129, 2, 'admin@geotrack.com', 'simple_admin', 'acknowledge', 'PointageEvent', NULL, 'manque_sortie - employé #1 - 2025-09-19', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', 1, '2026-03-10 06:22:05'),
(130, 2, 'admin@geotrack.com', 'simple_admin', 'update', 'PointageEventException', 4, '1', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', 1, '2026-03-10 06:22:25'),
(131, 2, 'admin@geotrack.com', 'simple_admin', 'acknowledge', 'PointageEvent', NULL, 'manque_sortie - employé #1 - 2025-09-19', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', 1, '2026-03-10 06:22:25'),
(132, 2, 'admin@geotrack.com', 'simple_admin', 'create', 'PointageEventException', 5, '3', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', 1, '2026-03-10 06:22:43'),
(133, 2, 'admin@geotrack.com', 'simple_admin', 'acknowledge', 'PointageEvent', NULL, 'pointage_weekend - employé #3 - 2025-09-20', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', 1, '2026-03-10 06:22:43'),
(134, 2, 'admin@geotrack.com', 'simple_admin', 'create', 'PointageEventException', 6, '1', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', 1, '2026-03-10 06:22:53'),
(135, 2, 'admin@geotrack.com', 'simple_admin', 'acknowledge', 'PointageEvent', NULL, 'pointage_weekend - employé #1 - 2025-10-19', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', 1, '2026-03-10 06:22:53'),
(136, 2, 'admin@geotrack.com', 'simple_admin', 'logout', 'Administration', 2, 'admin@geotrack.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', 1, '2026-03-10 06:27:47'),
(137, 2, 'admin@geotrack.com', 'simple_admin', 'logout', 'Administration', 2, 'admin@geotrack.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', 1, '2026-03-10 06:27:47'),
(138, 1, 'admin@kosi-time.com', 'superadmin', 'login_success', 'Administration', 1, 'admin@kosi-time.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-10 06:28:06'),
(139, 1, 'admin@kosi-time.com', 'superadmin', 'login_success', 'Administration', 1, 'admin@kosi-time.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-10 06:28:06'),
(140, 1, 'admin@kosi-time.com', 'superadmin', 'logout', 'Administration', 1, 'admin@kosi-time.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-10 06:54:35'),
(141, 1, 'admin@kosi-time.com', 'superadmin', 'logout', 'Administration', 1, 'admin@kosi-time.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-10 06:54:35'),
(142, NULL, 'admin@geotrack.com', NULL, 'login_failed', NULL, NULL, NULL, 'Mot de passe incorrect', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-10 06:54:50'),
(143, 2, 'admin@geotrack.com', 'simple_admin', 'login_success', 'Administration', 2, 'admin@geotrack.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', 1, '2026-03-10 06:54:57'),
(144, 2, 'admin@geotrack.com', 'simple_admin', 'login_success', 'Administration', 2, 'admin@geotrack.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', 1, '2026-03-10 06:54:57'),
(145, 2, 'admin@geotrack.com', 'simple_admin', 'create', 'Pointage', 1521, '57', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', 1, '2026-03-10 06:56:19'),
(146, 2, 'admin@geotrack.com', 'simple_admin', 'create', 'Pointage', 1521, 'Employé #57 — entry à 10/03/2026 06:56', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', 1, '2026-03-10 06:56:19'),
(147, 2, 'admin@geotrack.com', 'simple_admin', 'create', 'Pointage', 1522, '57', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', 1, '2026-03-10 06:56:37'),
(148, 2, 'admin@geotrack.com', 'simple_admin', 'create', 'Pointage', 1522, 'Employé #57 — exit à 10/03/2026 08:56', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', 1, '2026-03-10 06:56:37'),
(149, 2, 'admin@geotrack.com', 'simple_admin', 'create', 'Pointage', 1523, '57', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', 1, '2026-03-10 06:56:53'),
(150, 2, 'admin@geotrack.com', 'simple_admin', 'create', 'Pointage', 1523, 'Employé #57 — entry à 10/03/2026 09:56', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', 1, '2026-03-10 06:56:53'),
(151, 2, 'admin@geotrack.com', 'simple_admin', 'create', 'Pointage', 1524, '57', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', 1, '2026-03-10 06:57:06'),
(152, 2, 'admin@geotrack.com', 'simple_admin', 'create', 'Pointage', 1524, 'Employé #57 — exit à 10/03/2026 10:56', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', 1, '2026-03-10 06:57:06'),
(153, 2, 'admin@geotrack.com', 'simple_admin', 'create', 'Pointage', 1525, '57', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', 1, '2026-03-10 06:58:18'),
(154, 2, 'admin@geotrack.com', 'simple_admin', 'create', 'Pointage', 1525, 'Employé #57 — entry à 10/03/2026 13:58', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', 1, '2026-03-10 06:58:18'),
(155, 2, 'admin@geotrack.com', 'simple_admin', 'create', 'PointageEventException', 7, '57', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', 1, '2026-03-10 07:26:37'),
(156, 2, 'admin@geotrack.com', 'simple_admin', 'acknowledge', 'PointageEvent', NULL, 'manque_sortie - employé #57 - 2026-03-10', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', 1, '2026-03-10 07:26:37'),
(157, 2, 'admin@geotrack.com', 'simple_admin', 'delete', 'Pointage', 1525, 'Employé #57 — entry à 10/03/2026 13:58', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', 1, '2026-03-10 07:26:47'),
(158, 2, 'admin@geotrack.com', 'simple_admin', 'delete', 'Pointage', 1525, '57', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', 1, '2026-03-10 07:26:47'),
(159, 2, 'admin@geotrack.com', 'simple_admin', 'delete', 'Pointage', 154, 'Employé #1 — entry à 19/09/2025 11:42', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', 1, '2026-03-10 07:27:00'),
(160, 2, 'admin@geotrack.com', 'simple_admin', 'delete', 'Pointage', 154, '1', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', 1, '2026-03-10 07:27:00'),
(161, 2, 'admin@geotrack.com', 'simple_admin', 'delete', 'Pointage', 372, 'Employé #1 — entry à 19/10/2025 10:13', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', 1, '2026-03-10 07:27:08'),
(162, 2, 'admin@geotrack.com', 'simple_admin', 'delete', 'Pointage', 372, '1', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', 1, '2026-03-10 07:27:08'),
(163, 2, 'admin@geotrack.com', 'simple_admin', 'create', 'PointageEventException', 8, '2', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', 1, '2026-03-10 07:37:51'),
(164, 2, 'admin@geotrack.com', 'simple_admin', 'acknowledge', 'PointageEvent', NULL, 'pointage_weekend - employé #2 - 2025-11-15', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', 1, '2026-03-10 07:37:51'),
(165, 2, 'admin@geotrack.com', 'simple_admin', 'create', 'PointageEventException', 9, '3', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', 1, '2026-03-10 07:37:57'),
(166, 2, 'admin@geotrack.com', 'simple_admin', 'acknowledge', 'PointageEvent', NULL, 'pointage_weekend - employé #3 - 2025-11-15', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', 1, '2026-03-10 07:37:57'),
(167, 2, 'admin@geotrack.com', 'simple_admin', 'create', 'PointageEventException', 10, '2', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', 1, '2026-03-10 07:39:25'),
(168, 2, 'admin@geotrack.com', 'simple_admin', 'acknowledge', 'PointageEvent', NULL, 'manque_sortie - employé #2 - 2025-12-10', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', 1, '2026-03-10 07:39:25'),
(169, 2, 'admin@geotrack.com', 'simple_admin', 'delete', 'Pointage', 704, 'Employé #2 — exit à 06/12/2025 12:25', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', 1, '2026-03-10 07:48:04'),
(170, 2, 'admin@geotrack.com', 'simple_admin', 'delete', 'Pointage', 704, '2', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', 1, '2026-03-10 07:48:04'),
(171, 2, 'admin@geotrack.com', 'simple_admin', 'delete', 'Pointage', 702, 'Employé #2 — entry à 06/12/2025 09:23', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', 1, '2026-03-10 07:48:13'),
(172, 2, 'admin@geotrack.com', 'simple_admin', 'delete', 'Pointage', 702, '2', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', 1, '2026-03-10 07:48:13'),
(173, 2, 'admin@geotrack.com', 'simple_admin', 'delete', 'Pointage', 724, 'Employé #2 — entry à 10/12/2025 12:58', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', 1, '2026-03-10 07:48:44'),
(174, 2, 'admin@geotrack.com', 'simple_admin', 'delete', 'Pointage', 724, '2', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', 1, '2026-03-10 07:48:44'),
(175, 2, 'admin@geotrack.com', 'simple_admin', 'logout', 'Administration', 2, 'admin@geotrack.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', 1, '2026-03-10 08:28:45'),
(176, 2, 'admin@geotrack.com', 'simple_admin', 'logout', 'Administration', 2, 'admin@geotrack.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', 1, '2026-03-10 08:28:45'),
(177, 1, 'admin@kosi-time.com', 'superadmin', 'login_success', 'Administration', 1, 'admin@kosi-time.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-10 08:28:55'),
(178, 1, 'admin@kosi-time.com', 'superadmin', 'login_success', 'Administration', 1, 'admin@kosi-time.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-10 08:28:55'),
(179, 1, 'admin@kosi-time.com', 'superadmin', 'login_success', 'Administration', 1, 'admin@kosi-time.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-10 10:52:59'),
(180, 1, 'admin@kosi-time.com', 'superadmin', 'login_success', 'Administration', 1, 'admin@kosi-time.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-10 10:52:59'),
(181, 1, 'admin@kosi-time.com', 'superadmin', 'login_success', 'Administration', 1, 'admin@kosi-time.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-11 05:18:33'),
(182, 1, 'admin@kosi-time.com', 'superadmin', 'login_success', 'Administration', 1, 'admin@kosi-time.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-11 05:18:33'),
(183, 1, 'admin@kosi-time.com', 'superadmin', 'create', 'Administration', 14, 'adminkosi@gmail.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-11 05:19:17'),
(184, 1, 'admin@kosi-time.com', 'superadmin', 'create', 'Entreprise', 30, 'Nav', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-11 05:20:23'),
(185, 1, 'admin@kosi-time.com', 'superadmin', 'create', 'Entreprise', 31, 'SALIO', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-11 05:30:00'),
(186, 1, 'admin@kosi-time.com', 'superadmin', 'create', 'Entreprise', 32, 'test v3', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-11 05:37:47'),
(187, 1, 'admin@kosi-time.com', 'superadmin', 'create', 'EntrepriseSiege', 9, 'N8N', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-11 05:39:01'),
(188, 1, 'admin@kosi-time.com', 'superadmin', 'create', 'EntrepriseSiege', 10, 'test test test', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-11 07:51:37'),
(189, 1, 'admin@kosi-time.com', 'superadmin', 'create', 'Administration', 15, 'ad@gmail.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-11 07:56:46'),
(190, 1, 'admin@kosi-time.com', 'superadmin', 'create', 'EntrepriseSiege', 11, 'fdffdfd', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-11 08:49:23'),
(191, NULL, 'admin@geotrack.com', NULL, 'login_failed', NULL, NULL, NULL, 'Mot de passe incorrect', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-11 12:54:58'),
(192, NULL, 'admin@geotrack.com', NULL, 'login_failed', NULL, NULL, NULL, 'Mot de passe incorrect', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-11 12:55:38'),
(193, 2, 'admin@geotrack.com', 'simple_admin', 'login_success', 'Administration', 2, 'admin@geotrack.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', 1, '2026-03-11 12:55:47');
INSERT INTO `activity_logs` (`id`, `user_id`, `user_email`, `user_role`, `action`, `model_type`, `model_id`, `model_label`, `description`, `ip_address`, `user_agent`, `SiegeID`, `created_at`) VALUES
(194, 2, 'admin@geotrack.com', 'simple_admin', 'login_success', 'Administration', 2, 'admin@geotrack.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', 1, '2026-03-11 12:55:47'),
(195, NULL, 'admin@geotrack.com', NULL, 'login_failed', NULL, NULL, NULL, 'Mot de passe incorrect', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', NULL, '2026-03-30 08:23:05'),
(196, 2, 'admin@geotrack.com', 'simple_admin', 'login_success', 'Administration', 2, 'admin@geotrack.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', 1, '2026-03-30 08:23:22'),
(197, 2, 'admin@geotrack.com', 'simple_admin', 'login_success', 'Administration', 2, 'admin@geotrack.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', 1, '2026-03-30 08:23:22'),
(198, NULL, 'admin@geotrack.com', NULL, 'login_failed', NULL, NULL, NULL, 'Mot de passe incorrect', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', NULL, '2026-05-21 07:57:12'),
(199, NULL, 'admin@geotrack.com', NULL, 'login_failed', NULL, NULL, NULL, 'Mot de passe incorrect', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', NULL, '2026-05-21 08:02:03'),
(200, NULL, 'admin@geotrack.com', NULL, 'login_failed', NULL, NULL, NULL, 'Mot de passe incorrect', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', NULL, '2026-05-21 08:02:13'),
(201, 1, 'admin@kosi-time.com', 'superadmin', 'login_success', 'Administration', 1, 'admin@kosi-time.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', NULL, '2026-05-21 08:02:32'),
(202, 1, 'admin@kosi-time.com', 'superadmin', 'login_success', 'Administration', 1, 'admin@kosi-time.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', NULL, '2026-05-21 08:02:32'),
(203, 1, 'admin@kosi-time.com', 'superadmin', 'update', 'Administration', 2, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', NULL, '2026-05-21 08:03:22'),
(204, 1, 'admin@kosi-time.com', 'superadmin', 'logout', 'Administration', 1, 'admin@kosi-time.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', NULL, '2026-05-21 08:03:26'),
(205, 1, 'admin@kosi-time.com', 'superadmin', 'logout', 'Administration', 1, 'admin@kosi-time.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', NULL, '2026-05-21 08:03:26'),
(206, 2, 'admin@geotrack.com', 'simple_admin', 'login_success', 'Administration', 2, 'admin@geotrack.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 1, '2026-05-21 08:03:35'),
(207, 2, 'admin@geotrack.com', 'simple_admin', 'login_success', 'Administration', 2, 'admin@geotrack.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 1, '2026-05-21 08:03:35'),
(208, 2, 'admin@geotrack.com', 'simple_admin', 'logout', 'Administration', 2, 'admin@geotrack.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 1, '2026-05-21 08:06:02'),
(209, 2, 'admin@geotrack.com', 'simple_admin', 'logout', 'Administration', 2, 'admin@geotrack.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 1, '2026-05-21 08:06:02'),
(210, 1, 'admin@kosi-time.com', 'superadmin', 'login_success', 'Administration', 1, 'admin@kosi-time.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', NULL, '2026-05-21 08:28:10'),
(211, 1, 'admin@kosi-time.com', 'superadmin', 'login_success', 'Administration', 1, 'admin@kosi-time.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', NULL, '2026-05-21 08:28:10'),
(212, 1, 'admin@kosi-time.com', 'superadmin', 'logout', 'Administration', 1, 'admin@kosi-time.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', NULL, '2026-05-21 10:52:37'),
(213, 1, 'admin@kosi-time.com', 'superadmin', 'logout', 'Administration', 1, 'admin@kosi-time.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', NULL, '2026-05-21 10:52:37'),
(214, 1, 'admin@kosi-time.com', 'superadmin', 'login_success', 'Administration', 1, 'admin@kosi-time.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', NULL, '2026-05-21 10:52:46'),
(215, 1, 'admin@kosi-time.com', 'superadmin', 'login_success', 'Administration', 1, 'admin@kosi-time.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', NULL, '2026-05-21 10:52:46'),
(216, 1, 'admin@kosi-time.com', 'superadmin', 'logout', 'Administration', 1, 'admin@kosi-time.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', NULL, '2026-05-21 10:52:50'),
(217, 1, 'admin@kosi-time.com', 'superadmin', 'logout', 'Administration', 1, 'admin@kosi-time.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', NULL, '2026-05-21 10:52:50'),
(218, 2, 'admin@geotrack.com', 'simple_admin', 'login_success', 'Administration', 2, 'admin@geotrack.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 1, '2026-05-21 10:53:01'),
(219, 2, 'admin@geotrack.com', 'simple_admin', 'login_success', 'Administration', 2, 'admin@geotrack.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 1, '2026-05-21 10:53:01'),
(220, 2, 'admin@geotrack.com', 'simple_admin', 'logout', 'Administration', 2, 'admin@geotrack.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 1, '2026-05-21 12:05:39'),
(221, 2, 'admin@geotrack.com', 'simple_admin', 'logout', 'Administration', 2, 'admin@geotrack.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 1, '2026-05-21 12:05:39'),
(222, 1, 'admin@kosi-time.com', 'superadmin', 'login_success', 'Administration', 1, 'admin@kosi-time.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', NULL, '2026-05-21 12:05:47'),
(223, 1, 'admin@kosi-time.com', 'superadmin', 'login_success', 'Administration', 1, 'admin@kosi-time.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', NULL, '2026-05-21 12:05:47'),
(224, 1, 'admin@kosi-time.com', 'superadmin', 'export_excel', 'Report', NULL, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', NULL, '2026-05-21 12:59:03'),
(225, 1, 'admin@kosi-time.com', 'superadmin', 'update', 'Employe', 35, 'abdouroihamane saidal', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', NULL, '2026-05-21 13:38:03'),
(226, 1, 'admin@kosi-time.com', 'superadmin', 'reset', 'Entreprise', 35, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', NULL, '2026-05-21 13:38:03'),
(227, 1, 'admin@kosi-time.com', 'superadmin', 'logout', 'Administration', 1, 'admin@kosi-time.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', NULL, '2026-05-21 14:05:33'),
(228, 1, 'admin@kosi-time.com', 'superadmin', 'logout', 'Administration', 1, 'admin@kosi-time.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', NULL, '2026-05-21 14:05:33'),
(229, 1, 'admin@kosi-time.com', 'superadmin', 'login_success', 'Administration', 1, 'admin@kosi-time.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', NULL, '2026-05-21 14:05:39'),
(230, 1, 'admin@kosi-time.com', 'superadmin', 'login_success', 'Administration', 1, 'admin@kosi-time.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', NULL, '2026-05-21 14:05:39'),
(231, 1, 'admin@kosi-time.com', 'superadmin', 'logout', 'Administration', 1, 'admin@kosi-time.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', NULL, '2026-05-21 14:05:44'),
(232, 1, 'admin@kosi-time.com', 'superadmin', 'logout', 'Administration', 1, 'admin@kosi-time.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', NULL, '2026-05-21 14:05:44'),
(233, 2, 'admin@geotrack.com', 'simple_admin', 'login_success', 'Administration', 2, 'admin@geotrack.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 1, '2026-05-21 14:06:06'),
(234, 2, 'admin@geotrack.com', 'simple_admin', 'login_success', 'Administration', 2, 'admin@geotrack.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 1, '2026-05-21 14:06:06'),
(235, 2, 'admin@geotrack.com', 'simple_admin', 'delete', 'Pointage', 1516, 'Employé #2 — entry à 02/03/2026 08:09', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 1, '2026-05-21 14:06:23'),
(236, 2, 'admin@geotrack.com', 'simple_admin', 'delete', 'Pointage', 1516, '2', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 1, '2026-05-21 14:06:23'),
(237, 2, 'admin@geotrack.com', 'simple_admin', 'delete', 'Pointage', 1517, 'Employé #3 — entry à 02/03/2026 08:09', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 1, '2026-05-21 14:06:28'),
(238, 2, 'admin@geotrack.com', 'simple_admin', 'delete', 'Pointage', 1517, '3', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 1, '2026-05-21 14:06:28'),
(239, 2, 'admin@geotrack.com', 'simple_admin', 'logout', 'Administration', 2, 'admin@geotrack.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 1, '2026-05-21 14:06:36'),
(240, 2, 'admin@geotrack.com', 'simple_admin', 'logout', 'Administration', 2, 'admin@geotrack.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 1, '2026-05-21 14:06:36'),
(241, 1, 'admin@kosi-time.com', 'superadmin', 'login_success', 'Administration', 1, 'admin@kosi-time.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', NULL, '2026-05-21 14:06:40'),
(242, 1, 'admin@kosi-time.com', 'superadmin', 'login_success', 'Administration', 1, 'admin@kosi-time.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', NULL, '2026-05-21 14:06:40'),
(243, 1, 'admin@kosi-time.com', 'superadmin', 'logout', 'Administration', 1, 'admin@kosi-time.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', NULL, '2026-05-21 14:09:37'),
(244, 1, 'admin@kosi-time.com', 'superadmin', 'logout', 'Administration', 1, 'admin@kosi-time.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', NULL, '2026-05-21 14:09:37'),
(245, 2, 'admin@geotrack.com', 'simple_admin', 'login_success', 'Administration', 2, 'admin@geotrack.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 1, '2026-05-21 14:09:53'),
(246, 2, 'admin@geotrack.com', 'simple_admin', 'login_success', 'Administration', 2, 'admin@geotrack.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 1, '2026-05-21 14:09:53'),
(247, 2, 'admin@geotrack.com', 'simple_admin', 'delete', 'Pointage', 897, 'Employé #1 — entry à 23/01/2026 08:36', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 1, '2026-05-21 14:10:28'),
(248, 2, 'admin@geotrack.com', 'simple_admin', 'delete', 'Pointage', 897, '1', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 1, '2026-05-21 14:10:28'),
(249, 2, 'admin@geotrack.com', 'simple_admin', 'delete', 'Pointage', 1510, 'Employé #2 — exit à 27/02/2026 17:34', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 1, '2026-05-21 14:10:36'),
(250, 2, 'admin@geotrack.com', 'simple_admin', 'delete', 'Pointage', 1510, '2', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 1, '2026-05-21 14:10:36'),
(251, 2, 'admin@geotrack.com', 'simple_admin', 'delete', 'Pointage', 1513, 'Employé #3 — exit à 27/02/2026 17:45', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 1, '2026-05-21 14:10:41'),
(252, 2, 'admin@geotrack.com', 'simple_admin', 'delete', 'Pointage', 1513, '3', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 1, '2026-05-21 14:10:41'),
(253, 2, 'admin@geotrack.com', 'simple_admin', 'delete', 'Pointage', 1512, 'Employé #5 — exit à 27/02/2026 17:24', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 1, '2026-05-21 14:10:46'),
(254, 2, 'admin@geotrack.com', 'simple_admin', 'delete', 'Pointage', 1512, '5', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 1, '2026-05-21 14:10:46'),
(255, 2, 'admin@geotrack.com', 'simple_admin', 'delete', 'Pointage', 1502, 'Employé #42 — exit à 27/02/2026 12:05', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 1, '2026-05-21 14:10:51'),
(256, 2, 'admin@geotrack.com', 'simple_admin', 'delete', 'Pointage', 1502, '42', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 1, '2026-05-21 14:10:51'),
(257, 2, 'admin@geotrack.com', 'simple_admin', 'delete', 'Pointage', 1482, 'Employé #2 — entry à 26/02/2026 08:27', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 1, '2026-05-21 14:10:56'),
(258, 2, 'admin@geotrack.com', 'simple_admin', 'delete', 'Pointage', 1482, '2', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 1, '2026-05-21 14:10:56'),
(259, 2, 'admin@geotrack.com', 'simple_admin', 'delete', 'Pointage', 1480, 'Employé #3 — entry à 26/02/2026 13:34', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 1, '2026-05-21 14:10:59'),
(260, 2, 'admin@geotrack.com', 'simple_admin', 'delete', 'Pointage', 1480, '3', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 1, '2026-05-21 14:10:59'),
(261, 2, 'admin@geotrack.com', 'simple_admin', 'delete', 'Pointage', 1479, 'Employé #5 — entry à 26/02/2026 12:44', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 1, '2026-05-21 14:11:04'),
(262, 2, 'admin@geotrack.com', 'simple_admin', 'delete', 'Pointage', 1479, '5', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 1, '2026-05-21 14:11:04'),
(263, 2, 'admin@geotrack.com', 'simple_admin', 'delete', 'Pointage', 1478, 'Employé #42 — entry à 26/02/2026 12:43', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 1, '2026-05-21 14:11:09'),
(264, 2, 'admin@geotrack.com', 'simple_admin', 'delete', 'Pointage', 1478, '42', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 1, '2026-05-21 14:11:09'),
(265, 2, 'admin@geotrack.com', 'simple_admin', 'delete', 'Pointage', 1420, 'Employé #1 — entry à 23/02/2026 14:23', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 1, '2026-05-21 14:11:12'),
(266, 2, 'admin@geotrack.com', 'simple_admin', 'delete', 'Pointage', 1420, '1', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 1, '2026-05-21 14:11:12'),
(267, 2, 'admin@geotrack.com', 'simple_admin', 'delete', 'Pointage', 1250, 'Employé #2 — entry à 19/02/2026 08:15', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 1, '2026-05-21 14:11:17'),
(268, 2, 'admin@geotrack.com', 'simple_admin', 'delete', 'Pointage', 1250, '2', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 1, '2026-05-21 14:11:17'),
(269, 2, 'admin@geotrack.com', 'simple_admin', 'delete', 'Pointage', 1227, 'Employé #3 — entry à 17/02/2026 13:37', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 1, '2026-05-21 14:11:21'),
(270, 2, 'admin@geotrack.com', 'simple_admin', 'delete', 'Pointage', 1227, '3', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 1, '2026-05-21 14:11:21'),
(271, 2, 'admin@geotrack.com', 'simple_admin', 'create', 'PointageEventException', 11, '3', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 1, '2026-05-21 14:11:25'),
(272, 2, 'admin@geotrack.com', 'simple_admin', 'acknowledge', 'PointageEvent', NULL, 'pointage_weekend - employé #3 - 2026-02-14', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 1, '2026-05-21 14:11:25'),
(273, 2, 'admin@geotrack.com', 'simple_admin', 'delete', 'Pointage', 1179, 'Employé #3 — exit à 12/02/2026 17:36', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 1, '2026-05-21 14:11:31'),
(274, 2, 'admin@geotrack.com', 'simple_admin', 'delete', 'Pointage', 1179, '3', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 1, '2026-05-21 14:11:31'),
(275, 2, 'admin@geotrack.com', 'simple_admin', 'create', 'PointageEventException', 12, '2', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 1, '2026-05-21 14:11:37'),
(276, 2, 'admin@geotrack.com', 'simple_admin', 'acknowledge', 'PointageEvent', NULL, 'pointage_weekend - employé #2 - 2026-02-07', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 1, '2026-05-21 14:11:37'),
(277, 2, 'admin@geotrack.com', 'simple_admin', 'create', 'PointageEventException', 13, '3', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 1, '2026-05-21 14:11:41'),
(278, 2, 'admin@geotrack.com', 'simple_admin', 'acknowledge', 'PointageEvent', NULL, 'pointage_weekend - employé #3 - 2026-02-07', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 1, '2026-05-21 14:11:41'),
(279, 2, 'admin@geotrack.com', 'simple_admin', 'logout', 'Administration', 2, 'admin@geotrack.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 1, '2026-05-21 14:11:45'),
(280, 2, 'admin@geotrack.com', 'simple_admin', 'logout', 'Administration', 2, 'admin@geotrack.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 1, '2026-05-21 14:11:45'),
(281, 1, 'admin@kosi-time.com', 'superadmin', 'login_success', 'Administration', 1, 'admin@kosi-time.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', NULL, '2026-05-21 14:11:49'),
(282, 1, 'admin@kosi-time.com', 'superadmin', 'login_success', 'Administration', 1, 'admin@kosi-time.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', NULL, '2026-05-21 14:11:49'),
(283, 1, 'admin@kosi-time.com', 'superadmin', 'export_excel', 'Report', NULL, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', NULL, '2026-05-21 14:12:18'),
(284, 1, 'admin@kosi-time.com', 'superadmin', 'export_pdf', 'Report', NULL, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', NULL, '2026-05-21 14:13:48'),
(285, 1, 'admin@kosi-time.com', 'superadmin', 'export_excel', 'Report', NULL, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', NULL, '2026-05-21 14:14:56'),
(286, 1, 'admin@kosi-time.com', 'superadmin', 'export_pdf', 'Report', NULL, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', NULL, '2026-05-21 14:14:59'),
(287, 1, 'admin@kosi-time.com', 'superadmin', 'export_excel', 'Report', NULL, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', NULL, '2026-05-21 14:15:43'),
(288, 1, 'admin@kosi-time.com', 'superadmin', 'export_excel', 'Pointage', NULL, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', NULL, '2026-05-21 14:16:38'),
(289, 1, 'admin@kosi-time.com', 'superadmin', 'export_pdf', 'Pointage', NULL, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', NULL, '2026-05-21 14:17:15'),
(290, 1, 'admin@kosi-time.com', 'superadmin', 'login_success', 'Administration', 1, 'admin@kosi-time.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', NULL, '2026-05-22 12:34:14'),
(291, 1, 'admin@kosi-time.com', 'superadmin', 'login_success', 'Administration', 1, 'admin@kosi-time.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', NULL, '2026-05-22 12:34:14'),
(292, 1, 'admin@kosi-time.com', 'superadmin', 'export_excel', 'Report', NULL, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', NULL, '2026-05-22 12:35:17'),
(293, 1, 'admin@kosi-time.com', 'superadmin', 'export_excel', 'Report', NULL, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', NULL, '2026-05-22 12:35:42'),
(294, 1, 'admin@kosi-time.com', 'superadmin', 'export_excel', 'Report', NULL, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', NULL, '2026-05-22 12:36:19'),
(295, 1, 'admin@kosi-time.com', 'superadmin', 'export_pdf', 'Report', NULL, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', NULL, '2026-05-22 12:40:38'),
(296, 1, 'admin@kosi-time.com', 'superadmin', 'export_excel', 'Report', NULL, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', NULL, '2026-05-22 12:42:43'),
(297, 1, 'admin@kosi-time.com', 'superadmin', 'export_pdf', 'Report', NULL, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', NULL, '2026-05-22 12:42:46'),
(298, 1, 'admin@kosi-time.com', 'superadmin', 'logout', 'Administration', 1, 'admin@kosi-time.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', NULL, '2026-05-22 13:32:24'),
(299, 1, 'admin@kosi-time.com', 'superadmin', 'logout', 'Administration', 1, 'admin@kosi-time.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', NULL, '2026-05-22 13:32:24'),
(300, 2, 'admin@geotrack.com', 'simple_admin', 'login_success', 'Administration', 2, 'admin@geotrack.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 1, '2026-05-22 13:32:36'),
(301, 2, 'admin@geotrack.com', 'simple_admin', 'login_success', 'Administration', 2, 'admin@geotrack.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 1, '2026-05-22 13:32:36'),
(302, 2, 'admin@geotrack.com', 'simple_admin', 'logout', 'Administration', 2, 'admin@geotrack.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 1, '2026-05-22 14:16:52'),
(303, 2, 'admin@geotrack.com', 'simple_admin', 'logout', 'Administration', 2, 'admin@geotrack.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 1, '2026-05-22 14:16:52'),
(304, 1, 'admin@kosi-time.com', 'superadmin', 'login_success', 'Administration', 1, 'admin@kosi-time.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', NULL, '2026-05-22 14:16:57'),
(305, 1, 'admin@kosi-time.com', 'superadmin', 'login_success', 'Administration', 1, 'admin@kosi-time.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', NULL, '2026-05-22 14:16:57'),
(306, 2, 'admin@geotrack.com', 'simple_admin', 'login_success', 'Administration', 2, 'admin@geotrack.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 1, '2026-05-23 07:38:43'),
(307, 2, 'admin@geotrack.com', 'simple_admin', 'login_success', 'Administration', 2, 'admin@geotrack.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 1, '2026-05-23 07:38:43'),
(308, 2, 'admin@geotrack.com', 'simple_admin', 'create', 'Conge', 25, '5', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 1, '2026-05-23 07:47:12'),
(309, 2, 'admin@geotrack.com', 'simple_admin', 'logout', 'Administration', 2, 'admin@geotrack.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 1, '2026-05-23 08:09:12'),
(310, 2, 'admin@geotrack.com', 'simple_admin', 'logout', 'Administration', 2, 'admin@geotrack.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 1, '2026-05-23 08:09:12'),
(311, 1, 'admin@kosi-time.com', 'superadmin', 'login_success', 'Administration', 1, 'admin@kosi-time.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', NULL, '2026-05-23 08:09:18'),
(312, 1, 'admin@kosi-time.com', 'superadmin', 'login_success', 'Administration', 1, 'admin@kosi-time.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', NULL, '2026-05-23 08:09:18'),
(313, NULL, 'admin@geotrack.com', NULL, 'login_failed', NULL, NULL, NULL, 'Mot de passe incorrect', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', NULL, '2026-07-15 07:18:00'),
(314, 1, 'admin@kosi-time.com', 'superadmin', 'login_success', 'Administration', 1, 'admin@kosi-time.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', NULL, '2026-07-15 07:26:06'),
(315, 1, 'admin@kosi-time.com', 'superadmin', 'login_success', 'Administration', 1, 'admin@kosi-time.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', NULL, '2026-07-15 07:26:06'),
(316, 1, 'admin@kosi-time.com', 'superadmin', 'create', 'Administration', 16, 'admin@manage-sup.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', NULL, '2026-07-15 08:46:40'),
(317, 1, 'admin@kosi-time.com', 'superadmin', 'logout', 'Administration', 1, 'admin@kosi-time.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', NULL, '2026-07-15 08:46:56'),
(318, 1, 'admin@kosi-time.com', 'superadmin', 'logout', 'Administration', 1, 'admin@kosi-time.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', NULL, '2026-07-15 08:46:56'),
(319, 16, 'admin@manage-sup.com', 'superadmin', 'login_success', 'Administration', 16, 'admin@manage-sup.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', NULL, '2026-07-15 08:47:05'),
(320, 16, 'admin@manage-sup.com', 'superadmin', 'login_success', 'Administration', 16, 'admin@manage-sup.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', NULL, '2026-07-15 08:47:05'),
(321, 16, 'admin@manage-sup.com', 'superadmin', 'logout', 'Administration', 16, 'admin@manage-sup.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', NULL, '2026-07-15 13:45:34'),
(322, 16, 'admin@manage-sup.com', 'superadmin', 'logout', 'Administration', 16, 'admin@manage-sup.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', NULL, '2026-07-15 13:45:34'),
(323, 1, 'admin@kosi-time.com', 'superadmin', 'login_success', 'Administration', 1, 'admin@kosi-time.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', NULL, '2026-07-15 13:45:43'),
(324, 1, 'admin@kosi-time.com', 'superadmin', 'login_success', 'Administration', 1, 'admin@kosi-time.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', NULL, '2026-07-15 13:45:43'),
(325, 1, 'admin@kosi-time.com', 'superadmin', 'login_success', 'Administration', 16, 'admin@manage-sup.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', NULL, '2026-07-15 13:53:52'),
(326, 1, 'admin@kosi-time.com', 'superadmin', 'login_success', 'Administration', 16, 'admin@manage-sup.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', NULL, '2026-07-15 13:53:52'),
(327, 16, 'admin@manage-sup.com', 'superadmin', 'login_success', 'Administration', 1, 'admin@kosi-time.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', NULL, '2026-07-15 13:54:11'),
(328, 16, 'admin@manage-sup.com', 'superadmin', 'login_success', 'Administration', 1, 'admin@kosi-time.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', NULL, '2026-07-15 13:54:11'),
(329, 1, 'admin@kosi-time.com', 'superadmin', 'login_success', 'Administration', 13, 'admin@sel.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', NULL, '2026-07-15 13:54:22'),
(330, 1, 'admin@kosi-time.com', 'superadmin', 'login_success', 'Administration', 13, 'admin@sel.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', NULL, '2026-07-15 13:54:22'),
(331, 13, 'admin@sel.com', 'seller', 'login_success', 'Administration', 1, 'admin@kosi-time.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', NULL, '2026-07-15 13:55:13'),
(332, 13, 'admin@sel.com', 'seller', 'login_success', 'Administration', 1, 'admin@kosi-time.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', NULL, '2026-07-15 13:55:13'),
(333, 1, 'admin@kosi-time.com', 'superadmin', 'login_success', 'Administration', 2, 'admin@geotrack.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', NULL, '2026-07-15 13:57:03'),
(334, 1, 'admin@kosi-time.com', 'superadmin', 'login_success', 'Administration', 2, 'admin@geotrack.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', NULL, '2026-07-15 13:57:03'),
(335, 1, 'admin@kosi-time.com', 'superadmin', 'login_success', 'Administration', 1, 'admin@kosi-time.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', NULL, '2026-07-16 06:28:22'),
(336, 1, 'admin@kosi-time.com', 'superadmin', 'login_success', 'Administration', 1, 'admin@kosi-time.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', NULL, '2026-07-16 06:28:22'),
(337, 1, 'admin@kosi-time.com', 'superadmin', 'login_success', 'Administration', 1, 'admin@kosi-time.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', NULL, '2026-07-16 14:39:48'),
(338, 1, 'admin@kosi-time.com', 'superadmin', 'login_success', 'Administration', 1, 'admin@kosi-time.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', NULL, '2026-07-16 14:39:48'),
(339, NULL, 'admin@geotrack.com', NULL, 'login_failed', NULL, NULL, NULL, 'Mot de passe incorrect', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', NULL, '2026-07-17 11:12:16'),
(340, NULL, 'admin@geotrack.com', NULL, 'login_failed', NULL, NULL, NULL, 'Mot de passe incorrect', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', NULL, '2026-07-17 11:12:19'),
(341, 1, 'admin@kosi-time.com', 'superadmin', 'login_success', 'Administration', 1, 'admin@kosi-time.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', NULL, '2026-07-17 11:12:25'),
(342, 1, 'admin@kosi-time.com', 'superadmin', 'login_success', 'Administration', 1, 'admin@kosi-time.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', NULL, '2026-07-17 11:12:25'),
(343, 1, 'admin@kosi-time.com', 'superadmin', 'update', 'Employe', 3, 'Ramarotafika Hedi Franco', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', NULL, '2026-07-17 11:12:58'),
(344, 1, 'admin@kosi-time.com', 'superadmin', 'assign_web_access', 'Employe', 3, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', NULL, '2026-07-17 11:12:58'),
(345, 1, 'admin@kosi-time.com', 'superadmin', 'logout', 'Administration', 1, 'admin@kosi-time.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', NULL, '2026-07-17 11:13:06'),
(346, 1, 'admin@kosi-time.com', 'superadmin', 'logout', 'Administration', 1, 'admin@kosi-time.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', NULL, '2026-07-17 11:13:06'),
(347, NULL, 'ramarotafika1999@gmail.com', NULL, 'login_failed', NULL, NULL, NULL, 'Identifiant introuvable', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', NULL, '2026-07-17 11:13:09'),
(348, NULL, 'ramarotafika1999@gmail.com', NULL, 'login_failed', NULL, NULL, NULL, 'Identifiant introuvable', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', NULL, '2026-07-17 11:13:18'),
(349, NULL, 'ramarotafika1999@gmail.com', NULL, 'login_failed', NULL, NULL, NULL, 'Identifiant introuvable', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', NULL, '2026-07-17 11:13:50'),
(350, NULL, 'ramarotafika1999@gmail.com', NULL, 'login_failed', NULL, NULL, NULL, 'Identifiant introuvable', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', NULL, '2026-07-17 12:19:52'),
(351, NULL, 'ramarotafika1999@gmail.com', NULL, 'login_failed', NULL, NULL, NULL, 'Identifiant introuvable', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', NULL, '2026-07-17 12:20:07'),
(352, NULL, 'unknown', NULL, 'login_success', 'Administration', 3, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', NULL, '2026-07-17 12:31:41'),
(353, NULL, 'unknown', NULL, 'login_success', 'Administration', 3, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', NULL, '2026-07-17 12:31:41'),
(354, NULL, 'unknown', NULL, 'login_success', 'Administration', 3, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', NULL, '2026-07-17 13:18:51'),
(355, NULL, 'unknown', NULL, 'login_success', 'Administration', 3, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', NULL, '2026-07-17 13:18:51'),
(356, NULL, 'unknown', NULL, 'login_success', 'Administration', 3, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', NULL, '2026-07-17 14:02:41'),
(357, NULL, 'unknown', NULL, 'login_success', 'Administration', 3, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', NULL, '2026-07-17 14:02:41'),
(358, NULL, 'ramarotafika1999@gmail.com', NULL, 'login_failed', NULL, NULL, NULL, 'Identifiant introuvable', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', NULL, '2026-07-18 09:04:29'),
(359, NULL, 'admin@geotrack.com', NULL, 'login_failed', NULL, NULL, NULL, 'Mot de passe incorrect', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', NULL, '2026-07-18 09:04:42'),
(360, 1, 'admin@kosi-time.com', 'superadmin', 'login_success', 'Administration', 1, 'admin@kosi-time.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', NULL, '2026-07-18 09:04:51'),
(361, 1, 'admin@kosi-time.com', 'superadmin', 'login_success', 'Administration', 1, 'admin@kosi-time.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', NULL, '2026-07-18 09:04:51'),
(362, 1, 'admin@kosi-time.com', 'superadmin', 'login_success', 'Administration', 3, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', NULL, '2026-07-18 09:09:42'),
(363, 1, 'admin@kosi-time.com', 'superadmin', 'login_success', 'Administration', 3, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', NULL, '2026-07-18 09:09:42'),
(364, 1, 'admin@kosi-time.com', 'superadmin', 'logout', 'Administration', 3, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', NULL, '2026-07-18 09:09:46'),
(365, 1, 'admin@kosi-time.com', 'superadmin', 'logout', 'Administration', 3, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', NULL, '2026-07-18 09:09:46'),
(366, 1, 'admin@kosi-time.com', 'superadmin', 'login_success', 'Administration', 1, 'admin@kosi-time.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', NULL, '2026-07-22 14:49:29'),
(367, 1, 'admin@kosi-time.com', 'superadmin', 'login_success', 'Administration', 1, 'admin@kosi-time.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', NULL, '2026-07-22 14:49:29'),
(368, 1, 'admin@kosi-time.com', 'superadmin', 'logout', 'Administration', 1, 'admin@kosi-time.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', NULL, '2026-07-22 14:49:43'),
(369, 1, 'admin@kosi-time.com', 'superadmin', 'logout', 'Administration', 1, 'admin@kosi-time.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', NULL, '2026-07-22 14:49:43'),
(370, 2, 'admin@geotrack.com', 'simple_admin', 'login_success', 'Administration', 2, 'admin@geotrack.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 1, '2026-07-22 14:50:31'),
(371, 2, 'admin@geotrack.com', 'simple_admin', 'login_success', 'Administration', 2, 'admin@geotrack.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 1, '2026-07-22 14:50:31'),
(372, 2, 'admin@geotrack.com', 'simple_admin', 'update', 'Employe', 5, 'MALALANIRINA Emile Noeline', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 1, '2026-07-22 14:51:23'),
(373, 2, 'admin@geotrack.com', 'simple_admin', 'assign_web_access', 'Employe', 5, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 1, '2026-07-22 14:51:23'),
(374, 2, 'admin@geotrack.com', 'simple_admin', 'logout', 'Administration', 2, 'admin@geotrack.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 1, '2026-07-22 14:51:29'),
(375, 2, 'admin@geotrack.com', 'simple_admin', 'logout', 'Administration', 2, 'admin@geotrack.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 1, '2026-07-22 14:51:29'),
(376, 2, 'admin@geotrack.com', 'simple_admin', 'login_success', 'Administration', 2, 'admin@geotrack.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 1, '2026-07-22 14:52:12'),
(377, 2, 'admin@geotrack.com', 'simple_admin', 'login_success', 'Administration', 2, 'admin@geotrack.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 1, '2026-07-22 14:52:12'),
(378, 2, 'admin@geotrack.com', 'simple_admin', 'logout', 'Administration', 2, 'admin@geotrack.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 1, '2026-07-22 14:53:08'),
(379, 2, 'admin@geotrack.com', 'simple_admin', 'logout', 'Administration', 2, 'admin@geotrack.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 1, '2026-07-22 14:53:08'),
(380, NULL, 'malalanirinaemile2@gmail.com', NULL, 'login_failed', NULL, NULL, NULL, 'Identifiant introuvable', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', NULL, '2026-07-22 14:53:20'),
(381, NULL, 'unknown', NULL, 'login_success', 'Administration', 5, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', NULL, '2026-07-22 14:54:34'),
(382, NULL, 'unknown', NULL, 'login_success', 'Administration', 5, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', NULL, '2026-07-22 14:54:34'),
(383, 2, 'admin@geotrack.com', 'simple_admin', 'login_success', 'Administration', 2, 'admin@geotrack.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 1, '2026-07-22 14:56:14'),
(384, 2, 'admin@geotrack.com', 'simple_admin', 'login_success', 'Administration', 2, 'admin@geotrack.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 1, '2026-07-22 14:56:14');
INSERT INTO `activity_logs` (`id`, `user_id`, `user_email`, `user_role`, `action`, `model_type`, `model_id`, `model_label`, `description`, `ip_address`, `user_agent`, `SiegeID`, `created_at`) VALUES
(385, 2, 'admin@geotrack.com', 'simple_admin', 'create', 'Conge', 26, '5', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 1, '2026-07-22 14:57:33'),
(386, 2, 'admin@geotrack.com', 'simple_admin', 'logout', 'Administration', 2, 'admin@geotrack.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 1, '2026-07-22 15:04:06'),
(387, 2, 'admin@geotrack.com', 'simple_admin', 'logout', 'Administration', 2, 'admin@geotrack.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 1, '2026-07-22 15:04:06'),
(388, 1, 'admin@kosi-time.com', 'superadmin', 'login_success', 'Administration', 1, 'admin@kosi-time.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', NULL, '2026-07-22 15:05:04'),
(389, 1, 'admin@kosi-time.com', 'superadmin', 'login_success', 'Administration', 1, 'admin@kosi-time.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', NULL, '2026-07-22 15:05:04'),
(390, 1, 'admin@kosi-time.com', 'superadmin', 'logout', 'Administration', 1, 'admin@kosi-time.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', NULL, '2026-07-22 15:25:38'),
(391, 1, 'admin@kosi-time.com', 'superadmin', 'logout', 'Administration', 1, 'admin@kosi-time.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', NULL, '2026-07-22 15:25:38'),
(392, 2, 'admin@geotrack.com', 'simple_admin', 'login_success', 'Administration', 2, 'admin@geotrack.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 1, '2026-07-22 15:26:14'),
(393, 2, 'admin@geotrack.com', 'simple_admin', 'login_success', 'Administration', 2, 'admin@geotrack.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 1, '2026-07-22 15:26:14'),
(394, 2, 'admin@geotrack.com', 'simple_admin', 'logout', 'Administration', 2, 'admin@geotrack.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 1, '2026-07-22 15:26:42'),
(395, 2, 'admin@geotrack.com', 'simple_admin', 'logout', 'Administration', 2, 'admin@geotrack.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 1, '2026-07-22 15:26:42'),
(396, 1, 'admin@kosi-time.com', 'superadmin', 'login_success', 'Administration', 1, 'admin@kosi-time.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', NULL, '2026-07-22 15:27:55'),
(397, 1, 'admin@kosi-time.com', 'superadmin', 'login_success', 'Administration', 1, 'admin@kosi-time.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', NULL, '2026-07-22 15:27:55'),
(398, 1, 'admin@kosi-time.com', 'superadmin', 'login_success', 'Administration', 1, 'admin@kosi-time.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', NULL, '2026-07-23 07:44:33'),
(399, 1, 'admin@kosi-time.com', 'superadmin', 'login_success', 'Administration', 1, 'admin@kosi-time.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', NULL, '2026-07-23 07:44:33'),
(400, 1, 'admin@kosi-time.com', 'superadmin', 'logout', 'Administration', 1, 'admin@kosi-time.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', NULL, '2026-07-23 09:17:53'),
(401, 1, 'admin@kosi-time.com', 'superadmin', 'logout', 'Administration', 1, 'admin@kosi-time.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', NULL, '2026-07-23 09:17:53'),
(402, NULL, 'admin@geotrack.com', NULL, 'login_failed', NULL, NULL, NULL, 'Mot de passe incorrect', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', NULL, '2026-07-23 09:29:18'),
(403, 2, 'admin@geotrack.com', 'simple_admin', 'login_success', 'Administration', 2, 'admin@geotrack.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 1, '2026-07-23 09:29:45'),
(404, 2, 'admin@geotrack.com', 'simple_admin', 'login_success', 'Administration', 2, 'admin@geotrack.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 1, '2026-07-23 09:29:45'),
(405, 2, 'admin@geotrack.com', 'simple_admin', 'logout', 'Administration', 2, 'admin@geotrack.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 1, '2026-07-23 10:50:00'),
(406, 2, 'admin@geotrack.com', 'simple_admin', 'logout', 'Administration', 2, 'admin@geotrack.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 1, '2026-07-23 10:50:00'),
(407, 1, 'admin@kosi-time.com', 'superadmin', 'login_success', 'Administration', 1, 'admin@kosi-time.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', NULL, '2026-07-23 10:51:16'),
(408, 1, 'admin@kosi-time.com', 'superadmin', 'login_success', 'Administration', 1, 'admin@kosi-time.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', NULL, '2026-07-23 10:51:16'),
(409, 1, 'admin@kosi-time.com', 'superadmin', 'logout', 'Administration', 1, 'admin@kosi-time.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', NULL, '2026-07-23 14:43:14'),
(410, 1, 'admin@kosi-time.com', 'superadmin', 'logout', 'Administration', 1, 'admin@kosi-time.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', NULL, '2026-07-23 14:43:14'),
(411, NULL, 'malalanirinaemile2@gmail.com', NULL, 'login_failed', NULL, NULL, NULL, 'Identifiant introuvable', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', NULL, '2026-07-23 14:43:26'),
(412, NULL, 'unknown', NULL, 'login_success', 'Administration', 5, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', NULL, '2026-07-23 14:43:47'),
(413, NULL, 'unknown', NULL, 'login_success', 'Administration', 5, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', NULL, '2026-07-23 14:43:47'),
(414, 1, 'admin@kosi-time.com', 'superadmin', 'login_success', 'Administration', 1, 'admin@kosi-time.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', NULL, '2026-07-28 09:22:07'),
(415, 1, 'admin@kosi-time.com', 'superadmin', 'login_success', 'Administration', 1, 'admin@kosi-time.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', NULL, '2026-07-28 09:22:07'),
(416, 1, 'admin@kosi-time.com', 'superadmin', 'login_success', 'Administration', 1, 'admin@kosi-time.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', NULL, '2026-07-29 08:14:47'),
(417, 1, 'admin@kosi-time.com', 'superadmin', 'login_success', 'Administration', 1, 'admin@kosi-time.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', NULL, '2026-07-29 08:14:47'),
(418, 1, 'admin@kosi-time.com', 'superadmin', 'login_success', 'Administration', 1, 'admin@kosi-time.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', NULL, '2026-07-30 16:19:56'),
(419, 1, 'admin@kosi-time.com', 'superadmin', 'login_success', 'Administration', 1, 'admin@kosi-time.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', NULL, '2026-07-30 16:19:56'),
(420, 1, 'admin@kosi-time.com', 'superadmin', 'login_success', 'Administration', 1, 'admin@kosi-time.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', NULL, '2026-07-31 07:38:47'),
(421, 1, 'admin@kosi-time.com', 'superadmin', 'login_success', 'Administration', 1, 'admin@kosi-time.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', NULL, '2026-07-31 07:38:47'),
(422, 2, 'admin@geotrack.com', 'simple_admin', 'login_success', 'Administration', 2, 'admin@geotrack.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:153.0) Gecko/20100101 Firefox/153.0', 1, '2026-07-31 08:53:26'),
(423, 2, 'admin@geotrack.com', 'simple_admin', 'login_success', 'Administration', 2, 'admin@geotrack.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:153.0) Gecko/20100101 Firefox/153.0', 1, '2026-07-31 08:53:26'),
(424, 1, 'admin@kosi-time.com', 'superadmin', 'logout', 'Administration', 1, 'admin@kosi-time.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', NULL, '2026-07-31 08:54:13'),
(425, 1, 'admin@kosi-time.com', 'superadmin', 'logout', 'Administration', 1, 'admin@kosi-time.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', NULL, '2026-07-31 08:54:13'),
(426, 2, 'admin@geotrack.com', 'simple_admin', 'login_success', 'Administration', 2, 'admin@geotrack.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 1, '2026-07-31 08:54:34'),
(427, 2, 'admin@geotrack.com', 'simple_admin', 'login_success', 'Administration', 2, 'admin@geotrack.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 1, '2026-07-31 08:54:34'),
(428, 2, 'admin@geotrack.com', 'simple_admin', 'logout', 'Administration', 2, 'admin@geotrack.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 1, '2026-07-31 09:33:37'),
(429, 2, 'admin@geotrack.com', 'simple_admin', 'logout', 'Administration', 2, 'admin@geotrack.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 1, '2026-07-31 09:33:37'),
(430, 1, 'admin@kosi-time.com', 'superadmin', 'login_success', 'Administration', 1, 'admin@kosi-time.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', NULL, '2026-07-31 09:35:23'),
(431, 1, 'admin@kosi-time.com', 'superadmin', 'login_success', 'Administration', 1, 'admin@kosi-time.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', NULL, '2026-07-31 09:35:23'),
(432, 1, 'admin@kosi-time.com', 'superadmin', 'logout', 'Administration', 1, 'admin@kosi-time.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', NULL, '2026-07-31 12:19:54'),
(433, 1, 'admin@kosi-time.com', 'superadmin', 'logout', 'Administration', 1, 'admin@kosi-time.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', NULL, '2026-07-31 12:19:54'),
(434, 2, 'admin@geotrack.com', 'simple_admin', 'login_success', 'Administration', 2, 'admin@geotrack.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 1, '2026-07-31 12:20:31'),
(435, 2, 'admin@geotrack.com', 'simple_admin', 'login_success', 'Administration', 2, 'admin@geotrack.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 1, '2026-07-31 12:20:31'),
(436, 2, 'admin@geotrack.com', 'simple_admin', 'logout', 'Administration', 2, 'admin@geotrack.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 1, '2026-07-31 13:40:49'),
(437, 2, 'admin@geotrack.com', 'simple_admin', 'logout', 'Administration', 2, 'admin@geotrack.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 1, '2026-07-31 13:40:49'),
(438, NULL, 'admin@kosi-time.com', NULL, 'login_failed', NULL, NULL, NULL, 'Mot de passe incorrect', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', NULL, '2026-07-31 13:41:19'),
(439, 1, 'admin@kosi-time.com', 'superadmin', 'login_success', 'Administration', 1, 'admin@kosi-time.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', NULL, '2026-07-31 13:41:41'),
(440, 1, 'admin@kosi-time.com', 'superadmin', 'login_success', 'Administration', 1, 'admin@kosi-time.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', NULL, '2026-07-31 13:41:41'),
(441, 2, 'admin@geotrack.com', 'simple_admin', 'login_success', 'Administration', 2, 'admin@geotrack.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:153.0) Gecko/20100101 Firefox/153.0', 1, '2026-07-31 13:42:45'),
(442, 2, 'admin@geotrack.com', 'simple_admin', 'login_success', 'Administration', 2, 'admin@geotrack.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:153.0) Gecko/20100101 Firefox/153.0', 1, '2026-07-31 13:42:45'),
(443, 1, 'admin@kosi-time.com', 'superadmin', 'login_success', 'Administration', 1, 'admin@kosi-time.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', NULL, '2026-08-03 07:35:29'),
(444, 1, 'admin@kosi-time.com', 'superadmin', 'login_success', 'Administration', 1, 'admin@kosi-time.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', NULL, '2026-08-03 07:35:29'),
(445, 1, 'admin@kosi-time.com', 'superadmin', 'login_success', 'Administration', 1, 'admin@kosi-time.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', NULL, '2026-08-03 08:55:50'),
(446, 1, 'admin@kosi-time.com', 'superadmin', 'login_success', 'Administration', 1, 'admin@kosi-time.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', NULL, '2026-08-03 08:55:50'),
(447, 2, 'admin@geotrack.com', 'simple_admin', 'login_success', 'Administration', 2, 'admin@geotrack.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:153.0) Gecko/20100101 Firefox/153.0', 1, '2026-08-03 09:27:44'),
(448, 2, 'admin@geotrack.com', 'simple_admin', 'login_success', 'Administration', 2, 'admin@geotrack.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:153.0) Gecko/20100101 Firefox/153.0', 1, '2026-08-03 09:27:44'),
(449, 2, 'admin@geotrack.com', 'simple_admin', 'login_success', 'Administration', 2, 'admin@geotrack.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:153.0) Gecko/20100101 Firefox/153.0', 1, '2026-08-03 14:41:09'),
(450, 2, 'admin@geotrack.com', 'simple_admin', 'login_success', 'Administration', 2, 'admin@geotrack.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:153.0) Gecko/20100101 Firefox/153.0', 1, '2026-08-03 14:41:09'),
(451, 1, 'admin@kosi-time.com', 'superadmin', 'login_success', 'Administration', 1, 'admin@kosi-time.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', NULL, '2026-08-04 07:44:46'),
(452, 1, 'admin@kosi-time.com', 'superadmin', 'login_success', 'Administration', 1, 'admin@kosi-time.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', NULL, '2026-08-04 07:44:46'),
(453, 1, 'admin@kosi-time.com', 'superadmin', 'logout', 'Administration', 1, 'admin@kosi-time.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', NULL, '2026-08-04 15:18:04'),
(454, 1, 'admin@kosi-time.com', 'superadmin', 'logout', 'Administration', 1, 'admin@kosi-time.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', NULL, '2026-08-04 15:18:04'),
(455, 2, 'admin@geotrack.com', 'simple_admin', 'login_success', 'Administration', 2, 'admin@geotrack.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 1, '2026-08-04 15:18:33'),
(456, 2, 'admin@geotrack.com', 'simple_admin', 'login_success', 'Administration', 2, 'admin@geotrack.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 1, '2026-08-04 15:18:33'),
(457, 1, 'admin@kosi-time.com', 'superadmin', 'login_success', 'Administration', 1, 'admin@kosi-time.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', NULL, '2026-08-05 07:40:20'),
(458, 1, 'admin@kosi-time.com', 'superadmin', 'login_success', 'Administration', 1, 'admin@kosi-time.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', NULL, '2026-08-05 07:40:20'),
(459, 1, 'admin@kosi-time.com', 'superadmin', 'logout', 'Administration', 1, 'admin@kosi-time.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', NULL, '2026-08-05 12:54:33'),
(460, 1, 'admin@kosi-time.com', 'superadmin', 'logout', 'Administration', 1, 'admin@kosi-time.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', NULL, '2026-08-05 12:54:33'),
(461, 2, 'admin@geotrack.com', 'simple_admin', 'login_success', 'Administration', 2, 'admin@geotrack.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 1, '2026-08-05 12:54:55'),
(462, 2, 'admin@geotrack.com', 'simple_admin', 'login_success', 'Administration', 2, 'admin@geotrack.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 1, '2026-08-05 12:54:55'),
(463, 2, 'admin@geotrack.com', 'simple_admin', 'logout', 'Administration', 2, 'admin@geotrack.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 1, '2026-08-05 13:16:31'),
(464, 2, 'admin@geotrack.com', 'simple_admin', 'logout', 'Administration', 2, 'admin@geotrack.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 1, '2026-08-05 13:16:31'),
(465, 1, 'admin@kosi-time.com', 'superadmin', 'login_success', 'Administration', 1, 'admin@kosi-time.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', NULL, '2026-08-05 13:16:48'),
(466, 1, 'admin@kosi-time.com', 'superadmin', 'login_success', 'Administration', 1, 'admin@kosi-time.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', NULL, '2026-08-05 13:16:48'),
(467, 1, 'admin@kosi-time.com', 'superadmin', 'logout', 'Administration', 1, 'admin@kosi-time.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', NULL, '2026-08-05 14:32:40'),
(468, 1, 'admin@kosi-time.com', 'superadmin', 'logout', 'Administration', 1, 'admin@kosi-time.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', NULL, '2026-08-05 14:32:40'),
(469, NULL, 'admin@geotrack.com', NULL, 'login_failed', NULL, NULL, NULL, 'Mot de passe incorrect', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', NULL, '2026-08-05 14:33:08'),
(470, 1, 'admin@kosi-time.com', 'superadmin', 'login_success', 'Administration', 1, 'admin@kosi-time.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', NULL, '2026-08-05 14:33:27'),
(471, 1, 'admin@kosi-time.com', 'superadmin', 'login_success', 'Administration', 1, 'admin@kosi-time.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', NULL, '2026-08-05 14:33:27'),
(472, 1, 'admin@kosi-time.com', 'superadmin', 'login_success', 'Administration', 1, 'admin@kosi-time.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', NULL, '2026-08-06 07:37:44'),
(473, 1, 'admin@kosi-time.com', 'superadmin', 'login_success', 'Administration', 1, 'admin@kosi-time.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', NULL, '2026-08-06 07:37:45'),
(474, 1, 'admin@kosi-time.com', 'superadmin', 'create', 'Employe', 58, 'Emma solo', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', NULL, '2026-08-06 08:04:49'),
(475, 1, 'admin@kosi-time.com', 'superadmin', 'logout', 'Administration', 1, 'admin@kosi-time.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', NULL, '2026-08-06 08:48:19'),
(476, 1, 'admin@kosi-time.com', 'superadmin', 'logout', 'Administration', 1, 'admin@kosi-time.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', NULL, '2026-08-06 08:48:19'),
(477, 2, 'admin@geotrack.com', 'simple_admin', 'login_success', 'Administration', 2, 'admin@geotrack.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 1, '2026-08-06 08:48:53'),
(478, 2, 'admin@geotrack.com', 'simple_admin', 'login_success', 'Administration', 2, 'admin@geotrack.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 1, '2026-08-06 08:48:53'),
(479, 2, 'admin@geotrack.com', 'simple_admin', 'logout', 'Administration', 2, 'admin@geotrack.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 1, '2026-08-06 08:58:06'),
(480, 2, 'admin@geotrack.com', 'simple_admin', 'logout', 'Administration', 2, 'admin@geotrack.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 1, '2026-08-06 08:58:06'),
(481, 1, 'admin@kosi-time.com', 'superadmin', 'login_success', 'Administration', 1, 'admin@kosi-time.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', NULL, '2026-08-06 08:58:35'),
(482, 1, 'admin@kosi-time.com', 'superadmin', 'login_success', 'Administration', 1, 'admin@kosi-time.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', NULL, '2026-08-06 08:58:35'),
(483, 2, 'admin@geotrack.com', 'simple_admin', 'login_success', 'Administration', 2, 'admin@geotrack.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:153.0) Gecko/20100101 Firefox/153.0', 1, '2026-08-06 09:20:48'),
(484, 2, 'admin@geotrack.com', 'simple_admin', 'login_success', 'Administration', 2, 'admin@geotrack.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:153.0) Gecko/20100101 Firefox/153.0', 1, '2026-08-06 09:20:48'),
(485, 1, 'admin@kosi-time.com', 'superadmin', 'logout', 'Administration', 1, 'admin@kosi-time.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', NULL, '2026-08-06 10:00:03'),
(486, 1, 'admin@kosi-time.com', 'superadmin', 'logout', 'Administration', 1, 'admin@kosi-time.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', NULL, '2026-08-06 10:00:03'),
(487, 2, 'admin@geotrack.com', 'simple_admin', 'login_success', 'Administration', 2, 'admin@geotrack.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 1, '2026-08-06 10:00:35'),
(488, 2, 'admin@geotrack.com', 'simple_admin', 'login_success', 'Administration', 2, 'admin@geotrack.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 1, '2026-08-06 10:00:35'),
(489, 2, 'admin@geotrack.com', 'simple_admin', 'logout', 'Administration', 2, 'admin@geotrack.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 1, '2026-08-06 12:18:20'),
(490, 2, 'admin@geotrack.com', 'simple_admin', 'logout', 'Administration', 2, 'admin@geotrack.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 1, '2026-08-06 12:18:20'),
(491, 1, 'admin@kosi-time.com', 'superadmin', 'login_success', 'Administration', 1, 'admin@kosi-time.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', NULL, '2026-08-06 12:18:54'),
(492, 1, 'admin@kosi-time.com', 'superadmin', 'login_success', 'Administration', 1, 'admin@kosi-time.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', NULL, '2026-08-06 12:18:54'),
(493, 1, 'admin@kosi-time.com', 'superadmin', 'update', 'Employe', 58, 'Emma solofo', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', NULL, '2026-08-06 12:52:41'),
(494, 1, 'admin@kosi-time.com', 'superadmin', 'update', 'Employe', 58, 'Emma solofo', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', NULL, '2026-08-06 12:53:42'),
(495, 1, 'admin@kosi-time.com', 'superadmin', 'delete', 'Entreprise', 58, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', NULL, '2026-08-06 12:53:42'),
(496, 1, 'admin@kosi-time.com', 'superadmin', 'update', 'Employe', 58, 'Emma solofo', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', NULL, '2026-08-06 12:54:25'),
(497, 1, 'admin@kosi-time.com', 'superadmin', 'reset', 'Entreprise', 58, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', NULL, '2026-08-06 12:54:25'),
(498, 1, 'admin@kosi-time.com', 'superadmin', 'update', 'Employe', 54, 'Emp 1', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', NULL, '2026-08-06 12:54:36'),
(499, 1, 'admin@kosi-time.com', 'superadmin', 'delete', 'Entreprise', 54, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', NULL, '2026-08-06 12:54:36'),
(500, 1, 'admin@kosi-time.com', 'superadmin', 'update', 'Employe', 54, 'Emp 1', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', NULL, '2026-08-06 12:54:54'),
(501, 1, 'admin@kosi-time.com', 'superadmin', 'reset', 'Entreprise', 54, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', NULL, '2026-08-06 12:54:54'),
(502, 2, 'admin@geotrack.com', 'simple_admin', 'login_success', 'Administration', 2, 'admin@geotrack.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:153.0) Gecko/20100101 Firefox/153.0', 1, '2026-08-06 13:41:43'),
(503, 2, 'admin@geotrack.com', 'simple_admin', 'login_success', 'Administration', 2, 'admin@geotrack.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:153.0) Gecko/20100101 Firefox/153.0', 1, '2026-08-06 13:41:43'),
(504, 1, 'admin@kosi-time.com', 'superadmin', 'logout', 'Administration', 1, 'admin@kosi-time.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', NULL, '2026-08-06 16:27:50'),
(505, 1, 'admin@kosi-time.com', 'superadmin', 'logout', 'Administration', 1, 'admin@kosi-time.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', NULL, '2026-08-06 16:27:50'),
(506, 2, 'admin@geotrack.com', 'simple_admin', 'login_success', 'Administration', 2, 'admin@geotrack.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:153.0) Gecko/20100101 Firefox/153.0', 1, '2026-08-06 16:28:28'),
(507, 2, 'admin@geotrack.com', 'simple_admin', 'login_success', 'Administration', 2, 'admin@geotrack.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:153.0) Gecko/20100101 Firefox/153.0', 1, '2026-08-06 16:28:28'),
(508, 1, 'admin@kosi-time.com', 'superadmin', 'login_success', 'Administration', 1, 'admin@kosi-time.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', NULL, '2026-08-06 16:29:56'),
(509, 1, 'admin@kosi-time.com', 'superadmin', 'login_success', 'Administration', 1, 'admin@kosi-time.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', NULL, '2026-08-06 16:29:56'),
(510, 1, 'admin@kosi-time.com', 'superadmin', 'login_success', 'Administration', 1, 'admin@kosi-time.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', NULL, '2026-08-10 07:38:05'),
(511, 1, 'admin@kosi-time.com', 'superadmin', 'login_success', 'Administration', 1, 'admin@kosi-time.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', NULL, '2026-08-10 07:38:05'),
(512, 1, 'admin@kosi-time.com', 'superadmin', 'logout', 'Administration', 1, 'admin@kosi-time.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', NULL, '2026-08-10 07:52:17'),
(513, 1, 'admin@kosi-time.com', 'superadmin', 'logout', 'Administration', 1, 'admin@kosi-time.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', NULL, '2026-08-10 07:52:17'),
(514, 2, 'admin@geotrack.com', 'simple_admin', 'login_success', 'Administration', 2, 'admin@geotrack.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 1, '2026-08-10 07:52:44'),
(515, 2, 'admin@geotrack.com', 'simple_admin', 'login_success', 'Administration', 2, 'admin@geotrack.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 1, '2026-08-10 07:52:44'),
(516, 2, 'admin@geotrack.com', 'simple_admin', 'logout', 'Administration', 2, 'admin@geotrack.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 1, '2026-08-10 07:53:31'),
(517, 2, 'admin@geotrack.com', 'simple_admin', 'logout', 'Administration', 2, 'admin@geotrack.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 1, '2026-08-10 07:53:31'),
(518, 1, 'admin@kosi-time.com', 'superadmin', 'login_success', 'Administration', 1, 'admin@kosi-time.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', NULL, '2026-08-10 07:53:51'),
(519, 1, 'admin@kosi-time.com', 'superadmin', 'login_success', 'Administration', 1, 'admin@kosi-time.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', NULL, '2026-08-10 07:53:51'),
(520, 1, 'admin@kosi-time.com', 'superadmin', 'login_success', 'Administration', 1, 'admin@kosi-time.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', NULL, '2026-08-10 12:18:51'),
(521, 1, 'admin@kosi-time.com', 'superadmin', 'login_success', 'Administration', 1, 'admin@kosi-time.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', NULL, '2026-08-10 12:18:51'),
(522, 1, 'admin@kosi-time.com', 'superadmin', 'login_success', 'Administration', 1, 'admin@kosi-time.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', NULL, '2026-08-10 15:57:53'),
(523, 1, 'admin@kosi-time.com', 'superadmin', 'login_success', 'Administration', 1, 'admin@kosi-time.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', NULL, '2026-08-10 15:57:53'),
(524, 1, 'admin@kosi-time.com', 'superadmin', 'login_success', 'Administration', 1, 'admin@kosi-time.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', NULL, '2026-08-11 08:09:09'),
(525, 1, 'admin@kosi-time.com', 'superadmin', 'login_success', 'Administration', 1, 'admin@kosi-time.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', NULL, '2026-08-11 08:09:10'),
(526, 1, 'admin@kosi-time.com', 'superadmin', 'create', 'Employe', 59, 'RASOLOFOMANDIMBY EMILE ODON', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', NULL, '2026-08-11 09:24:49'),
(527, 1, 'admin@kosi-time.com', 'superadmin', 'login_success', 'Administration', 1, 'admin@kosi-time.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', NULL, '2026-08-11 09:28:46'),
(528, 1, 'admin@kosi-time.com', 'superadmin', 'login_success', 'Administration', 1, 'admin@kosi-time.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', NULL, '2026-08-11 09:28:46'),
(529, 1, 'admin@kosi-time.com', 'superadmin', 'create', 'Employe', 60, 'RAKOTONIRINA Manankasina', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', NULL, '2026-08-11 10:03:50'),
(530, 1, 'admin@kosi-time.com', 'superadmin', 'export_excel', 'Employe', NULL, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', NULL, '2026-08-11 10:08:49'),
(531, 1, 'admin@kosi-time.com', 'superadmin', 'login_success', 'Administration', 1, 'admin@kosi-time.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', NULL, '2026-08-12 16:36:00'),
(532, 1, 'admin@kosi-time.com', 'superadmin', 'login_success', 'Administration', 1, 'admin@kosi-time.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', NULL, '2026-08-12 16:36:00'),
(533, 1, 'admin@kosi-time.com', 'superadmin', 'login_success', 'Administration', 1, 'admin@kosi-time.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', NULL, '2026-08-13 07:32:24'),
(534, 1, 'admin@kosi-time.com', 'superadmin', 'login_success', 'Administration', 1, 'admin@kosi-time.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', NULL, '2026-08-13 07:32:24'),
(535, 2, 'admin@geotrack.com', 'simple_admin', 'login_success', 'Administration', 2, 'admin@geotrack.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:153.0) Gecko/20100101 Firefox/153.0', 1, '2026-08-13 10:58:35'),
(536, 2, 'admin@geotrack.com', 'simple_admin', 'login_success', 'Administration', 2, 'admin@geotrack.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:153.0) Gecko/20100101 Firefox/153.0', 1, '2026-08-13 10:58:35'),
(537, 1, 'admin@kosi-time.com', 'superadmin', 'login_success', 'Administration', 1, 'admin@kosi-time.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', NULL, '2026-08-13 15:57:23'),
(538, 1, 'admin@kosi-time.com', 'superadmin', 'login_success', 'Administration', 1, 'admin@kosi-time.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', NULL, '2026-08-13 15:57:23'),
(539, 1, 'admin@kosi-time.com', 'superadmin', 'login_success', 'Administration', 1, 'admin@kosi-time.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', NULL, '2026-08-14 07:33:30'),
(540, 1, 'admin@kosi-time.com', 'superadmin', 'login_success', 'Administration', 1, 'admin@kosi-time.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', NULL, '2026-08-14 07:33:30'),
(541, 2, 'admin@geotrack.com', 'simple_admin', 'login_success', 'Administration', 2, 'admin@geotrack.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:153.0) Gecko/20100101 Firefox/153.0', 1, '2026-08-14 07:59:26'),
(542, 2, 'admin@geotrack.com', 'simple_admin', 'login_success', 'Administration', 2, 'admin@geotrack.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:153.0) Gecko/20100101 Firefox/153.0', 1, '2026-08-14 07:59:26'),
(543, 1, 'admin@kosi-time.com', 'superadmin', 'login_success', 'Administration', 1, 'admin@kosi-time.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', NULL, '2026-08-17 07:39:23'),
(544, 1, 'admin@kosi-time.com', 'superadmin', 'login_success', 'Administration', 1, 'admin@kosi-time.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', NULL, '2026-08-17 07:39:23'),
(545, 2, 'admin@geotrack.com', 'simple_admin', 'login_success', 'Administration', 2, 'admin@geotrack.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:153.0) Gecko/20100101 Firefox/153.0', 1, '2026-08-17 09:00:00'),
(546, 2, 'admin@geotrack.com', 'simple_admin', 'login_success', 'Administration', 2, 'admin@geotrack.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:153.0) Gecko/20100101 Firefox/153.0', 1, '2026-08-17 09:00:00'),
(547, 1, 'admin@kosi-time.com', 'superadmin', 'login_success', 'Administration', 1, 'admin@kosi-time.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', NULL, '2026-08-17 14:42:27'),
(548, 1, 'admin@kosi-time.com', 'superadmin', 'login_success', 'Administration', 1, 'admin@kosi-time.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', NULL, '2026-08-17 14:42:27'),
(549, 1, 'admin@kosi-time.com', 'superadmin', 'login_success', 'Administration', 1, 'admin@kosi-time.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', NULL, '2026-08-18 07:38:23'),
(550, 1, 'admin@kosi-time.com', 'superadmin', 'login_success', 'Administration', 1, 'admin@kosi-time.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', NULL, '2026-08-18 07:38:23'),
(551, 2, 'admin@geotrack.com', 'simple_admin', 'login_success', 'Administration', 2, 'admin@geotrack.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:153.0) Gecko/20100101 Firefox/153.0', 1, '2026-08-18 07:41:15'),
(552, 2, 'admin@geotrack.com', 'simple_admin', 'login_success', 'Administration', 2, 'admin@geotrack.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:153.0) Gecko/20100101 Firefox/153.0', 1, '2026-08-18 07:41:15'),
(553, 2, 'admin@geotrack.com', 'simple_admin', 'login_success', 'Administration', 2, 'admin@geotrack.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:153.0) Gecko/20100101 Firefox/153.0', 1, '2026-08-18 10:56:22'),
(554, 2, 'admin@geotrack.com', 'simple_admin', 'login_success', 'Administration', 2, 'admin@geotrack.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:153.0) Gecko/20100101 Firefox/153.0', 1, '2026-08-18 10:56:22'),
(555, 1, 'admin@kosi-time.com', 'superadmin', 'update', 'Employe', 5, 'MALALANIRINA Emile Noeline', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', NULL, '2026-08-18 12:49:38'),
(556, 1, 'admin@kosi-time.com', 'superadmin', 'delete', 'LeavePolicyAssignment', 2, 'TST', 'Assignation supprimée', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', NULL, '2026-08-18 12:54:10'),
(557, 1, 'admin@kosi-time.com', 'superadmin', 'export_excel', 'Report', NULL, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', NULL, '2026-08-18 14:49:58'),
(558, 1, 'admin@kosi-time.com', 'superadmin', 'export_excel', 'Report', NULL, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', NULL, '2026-08-18 15:48:40'),
(559, 1, 'admin@kosi-time.com', 'superadmin', 'export_excel', 'Report', NULL, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', NULL, '2026-08-18 15:50:22'),
(560, 1, 'admin@kosi-time.com', 'superadmin', 'login_success', 'Administration', 1, 'admin@kosi-time.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', NULL, '2026-08-19 07:54:58'),
(561, 1, 'admin@kosi-time.com', 'superadmin', 'login_success', 'Administration', 1, 'admin@kosi-time.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', NULL, '2026-08-19 07:54:59'),
(562, 2, 'admin@geotrack.com', 'simple_admin', 'login_success', 'Administration', 2, 'admin@geotrack.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:153.0) Gecko/20100101 Firefox/153.0', 1, '2026-08-19 10:08:33'),
(563, 2, 'admin@geotrack.com', 'simple_admin', 'login_success', 'Administration', 2, 'admin@geotrack.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:153.0) Gecko/20100101 Firefox/153.0', 1, '2026-08-19 10:08:33'),
(564, 1, 'admin@kosi-time.com', 'superadmin', 'update', 'Employe', 1, 'Willy Tarkin', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', NULL, '2026-08-19 10:13:56'),
(565, 2, 'admin@geotrack.com', 'simple_admin', 'logout', 'Administration', 2, 'admin@geotrack.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:153.0) Gecko/20100101 Firefox/153.0', 1, '2026-08-19 10:39:59'),
(566, 2, 'admin@geotrack.com', 'simple_admin', 'logout', 'Administration', 2, 'admin@geotrack.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:153.0) Gecko/20100101 Firefox/153.0', 1, '2026-08-19 10:39:59'),
(567, NULL, 'unknown', NULL, 'login_success', 'Administration', 5, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:153.0) Gecko/20100101 Firefox/153.0', NULL, '2026-08-19 10:40:53'),
(568, NULL, 'unknown', NULL, 'login_success', 'Administration', 5, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:153.0) Gecko/20100101 Firefox/153.0', NULL, '2026-08-19 10:40:53'),
(569, 1, 'admin@kosi-time.com', 'superadmin', 'logout', 'Administration', 1, 'admin@kosi-time.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', NULL, '2026-08-19 10:50:19'),
(570, 1, 'admin@kosi-time.com', 'superadmin', 'logout', 'Administration', 1, 'admin@kosi-time.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', NULL, '2026-08-19 10:50:19'),
(571, NULL, 'unknown', NULL, 'update', 'Employe', 5, 'MALALANIRINA Emile Noeline', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', NULL, '2026-08-19 10:50:38'),
(572, NULL, 'unknown', NULL, 'login_success', 'Administration', 5, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', NULL, '2026-08-19 10:50:38'),
(573, NULL, 'unknown', NULL, 'login_success', 'Administration', 5, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', NULL, '2026-08-19 10:50:38'),
(574, NULL, 'malalanirinaemile2@gmail.com', NULL, 'login_failed', NULL, NULL, NULL, 'Identifiant introuvable', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', NULL, '2026-08-19 10:54:34'),
(575, NULL, 'malalanirinaemile2@gmail.com', NULL, 'login_failed', NULL, NULL, NULL, 'Identifiant introuvable', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', NULL, '2026-08-19 10:54:55'),
(576, NULL, 'malalanirinaemile2@gmail.com', NULL, 'login_failed', NULL, NULL, NULL, 'Identifiant introuvable', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', NULL, '2026-08-19 10:55:35'),
(577, NULL, 'malalanirinaemile2@gmail.com', NULL, 'login_failed', NULL, NULL, NULL, 'Identifiant introuvable', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:153.0) Gecko/20100101 Firefox/153.0', NULL, '2026-08-19 10:57:25');
INSERT INTO `activity_logs` (`id`, `user_id`, `user_email`, `user_role`, `action`, `model_type`, `model_id`, `model_label`, `description`, `ip_address`, `user_agent`, `SiegeID`, `created_at`) VALUES
(578, NULL, 'malalanirinaemile2@gmail.com', NULL, 'login_failed', NULL, NULL, NULL, 'Identifiant introuvable', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:153.0) Gecko/20100101 Firefox/153.0', NULL, '2026-08-19 10:57:42'),
(579, NULL, 'malalanirinaemile2@gmail.com', NULL, 'login_failed', NULL, NULL, NULL, 'Identifiant introuvable', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', NULL, '2026-08-19 10:58:08'),
(580, NULL, 'malalanirinaemile1@gmail.com', NULL, 'login_failed', NULL, NULL, NULL, 'Identifiant introuvable', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', NULL, '2026-08-19 10:58:23'),
(581, NULL, 'malalanirinaemile2@gmail.com', NULL, 'login_failed', NULL, NULL, NULL, 'Identifiant introuvable', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', NULL, '2026-08-19 12:02:20'),
(582, NULL, 'malalanirinaemile2@gmail.com', NULL, 'login_failed', NULL, NULL, NULL, 'Identifiant introuvable', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', NULL, '2026-08-19 12:02:41'),
(583, NULL, 'malalanirinaemile1@gmail.com', NULL, 'login_failed', NULL, NULL, NULL, 'Identifiant introuvable', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', NULL, '2026-08-19 12:02:51'),
(584, NULL, 'malalanirinaemile2@gmail.com', NULL, 'login_failed', NULL, NULL, NULL, 'Identifiant introuvable', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', NULL, '2026-08-19 12:03:42'),
(585, NULL, 'malalanirinaemile2@gmail.com', NULL, 'login_failed', NULL, NULL, NULL, 'Identifiant introuvable', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', NULL, '2026-08-19 12:04:26'),
(586, NULL, 'malalanirinaemile2@gmail.com', NULL, 'login_failed', NULL, NULL, NULL, 'Identifiant introuvable', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', NULL, '2026-08-19 12:04:51'),
(587, 2, 'admin@geotrack.com', 'simple_admin', 'login_success', 'Administration', 2, 'admin@geotrack.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:153.0) Gecko/20100101 Firefox/153.0', 1, '2026-08-19 12:05:17'),
(588, 2, 'admin@geotrack.com', 'simple_admin', 'login_success', 'Administration', 2, 'admin@geotrack.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:153.0) Gecko/20100101 Firefox/153.0', 1, '2026-08-19 12:05:17'),
(589, 2, 'admin@geotrack.com', 'simple_admin', 'logout', 'Administration', 2, 'admin@geotrack.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:153.0) Gecko/20100101 Firefox/153.0', 1, '2026-08-19 12:05:23'),
(590, 2, 'admin@geotrack.com', 'simple_admin', 'logout', 'Administration', 2, 'admin@geotrack.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:153.0) Gecko/20100101 Firefox/153.0', 1, '2026-08-19 12:05:23'),
(591, NULL, 'unknown', NULL, 'login_success', 'Administration', 5, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:153.0) Gecko/20100101 Firefox/153.0', NULL, '2026-08-19 12:06:07'),
(592, NULL, 'unknown', NULL, 'login_success', 'Administration', 5, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:153.0) Gecko/20100101 Firefox/153.0', NULL, '2026-08-19 12:06:07'),
(593, NULL, 'malalanirnaemile2@gmail.com', NULL, 'login_failed', NULL, NULL, NULL, 'Identifiant introuvable', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:153.0) Gecko/20100101 Firefox/153.0', NULL, '2026-08-19 12:43:53'),
(594, NULL, 'malalanirinaemile2@gmail.com', NULL, 'login_failed', NULL, NULL, NULL, 'Identifiant introuvable', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:153.0) Gecko/20100101 Firefox/153.0', NULL, '2026-08-19 12:44:00'),
(595, NULL, 'malalanirinaemile2@gmail.com', NULL, 'login_failed', NULL, NULL, NULL, 'Identifiant introuvable', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:153.0) Gecko/20100101 Firefox/153.0', NULL, '2026-08-19 12:44:07'),
(596, NULL, 'malalanirinaemile2@gmail.com', NULL, 'login_failed', NULL, NULL, NULL, 'Identifiant introuvable', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', NULL, '2026-08-19 12:45:39'),
(597, NULL, 'malalanirinaemile2@gmail.com', NULL, 'login_failed', NULL, NULL, NULL, 'Identifiant introuvable', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', NULL, '2026-08-19 12:46:17'),
(598, NULL, 'malalanirinaemile2@gmail.com', NULL, 'login_failed', NULL, NULL, NULL, 'Identifiant introuvable', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', NULL, '2026-08-19 12:47:58'),
(599, 1, 'admin@kosi-time.com', 'superadmin', 'login_success', 'Administration', 1, 'admin@kosi-time.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', NULL, '2026-08-19 12:49:40'),
(600, 1, 'admin@kosi-time.com', 'superadmin', 'login_success', 'Administration', 1, 'admin@kosi-time.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', NULL, '2026-08-19 12:49:41'),
(601, 1, 'admin@kosi-time.com', 'superadmin', 'logout', 'Administration', 1, 'admin@kosi-time.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', NULL, '2026-08-19 12:49:48'),
(602, 1, 'admin@kosi-time.com', 'superadmin', 'logout', 'Administration', 1, 'admin@kosi-time.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', NULL, '2026-08-19 12:49:48'),
(603, NULL, 'unknown', NULL, 'login_success', 'Administration', 5, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', NULL, '2026-08-19 12:49:51'),
(604, NULL, 'unknown', NULL, 'login_success', 'Administration', 5, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', NULL, '2026-08-19 12:49:51'),
(605, NULL, 'malalanirinaemile2@gmail.com', NULL, 'login_failed', NULL, NULL, NULL, 'Identifiant introuvable', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', NULL, '2026-08-19 12:50:14'),
(606, NULL, 'malalanirnaemile2@gmail.com', NULL, 'login_failed', NULL, NULL, NULL, 'Identifiant introuvable', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:153.0) Gecko/20100101 Firefox/153.0', NULL, '2026-08-19 12:51:13'),
(607, NULL, 'malalanirinaemile2@gmail.com', NULL, 'login_failed', NULL, NULL, NULL, 'Identifiant introuvable', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:153.0) Gecko/20100101 Firefox/153.0', NULL, '2026-08-19 12:51:25'),
(608, NULL, 'malalanirinaemile2@gmail.com', NULL, 'login_failed', NULL, NULL, NULL, 'Identifiant introuvable', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:153.0) Gecko/20100101 Firefox/153.0', NULL, '2026-08-19 12:51:43'),
(609, NULL, 'malalanirinaemile1@gmail.com', NULL, 'login_failed', NULL, NULL, NULL, 'Identifiant introuvable', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', NULL, '2026-08-19 12:52:20'),
(610, NULL, 'malalanirinaemile2@gmail.com', NULL, 'login_failed', NULL, NULL, NULL, 'Identifiant introuvable', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', NULL, '2026-08-19 12:52:35'),
(611, NULL, 'malalanirinaemile2@gmail.com', NULL, 'login_failed', NULL, NULL, NULL, 'Identifiant introuvable', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:153.0) Gecko/20100101 Firefox/153.0', NULL, '2026-08-19 12:53:39'),
(612, NULL, 'malalanirinaemile2@gmail.com', NULL, 'login_failed', NULL, NULL, NULL, 'Identifiant introuvable', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:153.0) Gecko/20100101 Firefox/153.0', NULL, '2026-08-19 12:53:47'),
(613, NULL, 'malalanirinaemile2@gmail.com', NULL, 'login_failed', NULL, NULL, NULL, 'Identifiant introuvable', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:153.0) Gecko/20100101 Firefox/153.0', NULL, '2026-08-19 12:54:34'),
(614, NULL, 'malalanirinaemile2@gmail.com', NULL, 'login_failed', NULL, NULL, NULL, 'Identifiant introuvable', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', NULL, '2026-08-19 13:11:35'),
(615, NULL, 'unknown', NULL, 'login_success', 'Administration', 5, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', NULL, '2026-08-19 13:13:16'),
(616, NULL, 'unknown', NULL, 'login_success', 'Administration', 5, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', NULL, '2026-08-19 13:13:16'),
(617, 2, 'admin@geotrack.com', 'simple_admin', 'login_success', 'Administration', 2, 'admin@geotrack.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:153.0) Gecko/20100101 Firefox/153.0', 1, '2026-08-19 13:28:15'),
(618, 2, 'admin@geotrack.com', 'simple_admin', 'login_success', 'Administration', 2, 'admin@geotrack.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:153.0) Gecko/20100101 Firefox/153.0', 1, '2026-08-19 13:28:15'),
(619, 1, 'admin@kosi-time.com', 'superadmin', 'login_success', 'Administration', 1, 'admin@kosi-time.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', NULL, '2026-08-19 13:51:42'),
(620, 1, 'admin@kosi-time.com', 'superadmin', 'login_success', 'Administration', 1, 'admin@kosi-time.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', NULL, '2026-08-19 13:51:42'),
(621, 1, 'admin@kosi-time.com', 'superadmin', 'logout', 'Administration', 1, 'admin@kosi-time.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', NULL, '2026-08-19 13:51:53'),
(622, 1, 'admin@kosi-time.com', 'superadmin', 'logout', 'Administration', 1, 'admin@kosi-time.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', NULL, '2026-08-19 13:51:53'),
(623, 1, 'admin@kosi-time.com', 'superadmin', 'login_success', 'Administration', 1, 'admin@kosi-time.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', NULL, '2026-08-19 13:52:02'),
(624, 1, 'admin@kosi-time.com', 'superadmin', 'login_success', 'Administration', 1, 'admin@kosi-time.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', NULL, '2026-08-19 13:52:02'),
(625, 1, 'admin@kosi-time.com', 'superadmin', 'logout', 'Administration', 1, 'admin@kosi-time.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', NULL, '2026-08-19 14:15:17'),
(626, 1, 'admin@kosi-time.com', 'superadmin', 'logout', 'Administration', 1, 'admin@kosi-time.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', NULL, '2026-08-19 14:15:17'),
(627, NULL, 'unknown', NULL, 'login_success', 'Administration', 5, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', NULL, '2026-08-19 14:15:29'),
(628, NULL, 'unknown', NULL, 'login_success', 'Administration', 5, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', NULL, '2026-08-19 14:15:29'),
(629, NULL, 'malalanirinaemile2@gmail.com', NULL, 'login_failed', NULL, NULL, NULL, 'Identifiant introuvable', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', NULL, '2026-08-19 14:49:12'),
(630, 2, 'admin@geotrack.com', 'simple_admin', 'logout', 'Administration', 2, 'admin@geotrack.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:153.0) Gecko/20100101 Firefox/153.0', 1, '2026-08-19 15:03:47'),
(631, 2, 'admin@geotrack.com', 'simple_admin', 'logout', 'Administration', 2, 'admin@geotrack.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:153.0) Gecko/20100101 Firefox/153.0', 1, '2026-08-19 15:03:47'),
(632, NULL, 'unknown', NULL, 'login_success', 'Administration', 5, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:153.0) Gecko/20100101 Firefox/153.0', NULL, '2026-08-19 15:04:05'),
(633, NULL, 'unknown', NULL, 'login_success', 'Administration', 5, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:153.0) Gecko/20100101 Firefox/153.0', NULL, '2026-08-19 15:04:05'),
(634, 1, 'admin@kosi-time.com', 'superadmin', 'login_success', 'Administration', 1, 'admin@kosi-time.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', NULL, '2026-08-20 07:43:06'),
(635, 1, 'admin@kosi-time.com', 'superadmin', 'login_success', 'Administration', 1, 'admin@kosi-time.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', NULL, '2026-08-20 07:43:06'),
(636, NULL, 'unknown', NULL, 'login_success', 'Administration', 5, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:154.0) Gecko/20100101 Firefox/154.0', NULL, '2026-08-20 07:44:14'),
(637, NULL, 'unknown', NULL, 'login_success', 'Administration', 5, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:154.0) Gecko/20100101 Firefox/154.0', NULL, '2026-08-20 07:44:14'),
(638, NULL, 'malalanirinaemile2@gmail.com', NULL, 'login_failed', NULL, NULL, NULL, 'Identifiant introuvable', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:154.0) Gecko/20100101 Firefox/154.0', NULL, '2026-08-20 08:25:13'),
(639, NULL, 'malalanirinaemile2@gmail.com', NULL, 'login_failed', NULL, NULL, NULL, 'Identifiant introuvable', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:154.0) Gecko/20100101 Firefox/154.0', NULL, '2026-08-20 08:31:25'),
(640, NULL, 'malalanirinaemile2@gmail.com', NULL, 'login_failed', NULL, NULL, NULL, 'Identifiant introuvable', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:154.0) Gecko/20100101 Firefox/154.0', NULL, '2026-08-20 08:31:32'),
(641, 2, 'admin@geotrack.com', 'simple_admin', 'login_success', 'Administration', 2, 'admin@geotrack.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:154.0) Gecko/20100101 Firefox/154.0', 1, '2026-08-20 10:48:20'),
(642, 2, 'admin@geotrack.com', 'simple_admin', 'login_success', 'Administration', 2, 'admin@geotrack.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:154.0) Gecko/20100101 Firefox/154.0', 1, '2026-08-20 10:48:20'),
(643, 2, 'admin@geotrack.com', 'simple_admin', 'logout', 'Administration', 2, 'admin@geotrack.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:154.0) Gecko/20100101 Firefox/154.0', 1, '2026-08-20 10:48:27'),
(644, 2, 'admin@geotrack.com', 'simple_admin', 'logout', 'Administration', 2, 'admin@geotrack.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:154.0) Gecko/20100101 Firefox/154.0', 1, '2026-08-20 10:48:27'),
(645, 2, 'admin@geotrack.com', 'simple_admin', 'login_success', 'Administration', 2, 'admin@geotrack.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:154.0) Gecko/20100101 Firefox/154.0', 1, '2026-08-20 10:48:36'),
(646, 2, 'admin@geotrack.com', 'simple_admin', 'login_success', 'Administration', 2, 'admin@geotrack.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:154.0) Gecko/20100101 Firefox/154.0', 1, '2026-08-20 10:48:36'),
(647, 17, 'manager.geotrack@kosi-time.com', 'simple_admin', 'login_success', 'Administration', 17, 'manager.geotrack@kosi-time.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 1, '2026-08-20 12:07:34'),
(648, 17, 'manager.geotrack@kosi-time.com', 'simple_admin', 'login_success', 'Administration', 17, 'manager.geotrack@kosi-time.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 1, '2026-08-20 12:07:34'),
(649, 17, 'manager.geotrack@kosi-time.com', 'simple_admin', 'logout', 'Administration', 17, 'manager.geotrack@kosi-time.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 1, '2026-08-20 12:53:21'),
(650, 17, 'manager.geotrack@kosi-time.com', 'simple_admin', 'logout', 'Administration', 17, 'manager.geotrack@kosi-time.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 1, '2026-08-20 12:53:21'),
(651, 2, 'admin@geotrack.com', 'simple_admin', 'login_success', 'Administration', 2, 'admin@geotrack.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 1, '2026-08-20 12:53:38'),
(652, 2, 'admin@geotrack.com', 'simple_admin', 'login_success', 'Administration', 2, 'admin@geotrack.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 1, '2026-08-20 12:53:38'),
(653, 2, 'admin@geotrack.com', 'simple_admin', 'logout', 'Administration', 2, 'admin@geotrack.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 1, '2026-08-20 12:54:18'),
(654, 2, 'admin@geotrack.com', 'simple_admin', 'logout', 'Administration', 2, 'admin@geotrack.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 1, '2026-08-20 12:54:18'),
(655, NULL, 'unknown', NULL, 'login_success', 'Administration', 5, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', NULL, '2026-08-20 12:54:32'),
(656, NULL, 'unknown', NULL, 'login_success', 'Administration', 5, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', NULL, '2026-08-20 12:54:32'),
(657, NULL, 'manager.geotrack@kosi-time.com', NULL, 'login_failed', NULL, NULL, NULL, 'Mot de passe incorrect', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', NULL, '2026-08-20 13:02:44'),
(658, NULL, 'manager.geotrack@kosi-time.com', NULL, 'login_failed', NULL, NULL, NULL, 'Mot de passe incorrect', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', NULL, '2026-08-20 13:04:39'),
(659, NULL, 'manager.geotrack@kosi-time.com', NULL, 'login_failed', NULL, NULL, NULL, 'Mot de passe incorrect', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', NULL, '2026-08-20 13:05:35'),
(660, 2, 'admin@geotrack.com', 'simple_admin', 'logout', 'Administration', 2, 'admin@geotrack.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:154.0) Gecko/20100101 Firefox/154.0', 1, '2026-08-20 13:05:44'),
(661, 2, 'admin@geotrack.com', 'simple_admin', 'logout', 'Administration', 2, 'admin@geotrack.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:154.0) Gecko/20100101 Firefox/154.0', 1, '2026-08-20 13:05:44'),
(662, NULL, 'manager.geotrack@kosi-time.com', NULL, 'login_failed', NULL, NULL, NULL, 'Mot de passe incorrect', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:154.0) Gecko/20100101 Firefox/154.0', NULL, '2026-08-20 13:06:07'),
(663, NULL, 'manager.geotrack@kosi-time.com', NULL, 'login_failed', NULL, NULL, NULL, 'Mot de passe incorrect', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', NULL, '2026-08-20 13:07:00'),
(664, NULL, 'manager.geotrack@kosi-time.com', NULL, 'login_failed', NULL, NULL, NULL, 'Mot de passe incorrect', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', NULL, '2026-08-20 13:07:21'),
(665, NULL, 'manager.geotrack@kosi-time.com', NULL, 'login_failed', NULL, NULL, NULL, 'Mot de passe incorrect', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:154.0) Gecko/20100101 Firefox/154.0', NULL, '2026-08-20 13:08:06'),
(666, NULL, 'manager.geotrack@kosi-time.com', NULL, 'login_failed', NULL, NULL, NULL, 'Mot de passe incorrect', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', NULL, '2026-08-20 13:09:15'),
(667, NULL, 'manager.geotrack@kosi-time.com', NULL, 'login_failed', NULL, NULL, NULL, 'Mot de passe incorrect', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', NULL, '2026-08-20 13:10:49'),
(668, 17, 'manager.geotrack@kosi-time.com', 'simple_admin', 'login_success', 'Administration', 17, 'manager.geotrack@kosi-time.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 1, '2026-08-20 13:12:11'),
(669, 17, 'manager.geotrack@kosi-time.com', 'simple_admin', 'login_success', 'Administration', 17, 'manager.geotrack@kosi-time.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 1, '2026-08-20 13:12:11'),
(670, NULL, 'unknown', NULL, 'login_success', 'Administration', 5, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:154.0) Gecko/20100101 Firefox/154.0', NULL, '2026-08-20 13:22:28'),
(671, NULL, 'unknown', NULL, 'login_success', 'Administration', 5, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:154.0) Gecko/20100101 Firefox/154.0', NULL, '2026-08-20 13:22:28'),
(672, 17, 'manager.geotrack@kosi-time.com', 'simple_admin', 'logout', 'Administration', 17, 'manager.geotrack@kosi-time.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 1, '2026-08-20 14:16:28'),
(673, 17, 'manager.geotrack@kosi-time.com', 'simple_admin', 'logout', 'Administration', 17, 'manager.geotrack@kosi-time.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 1, '2026-08-20 14:16:28'),
(674, 1, 'admin@kosi-time.com', 'superadmin', 'login_success', 'Administration', 1, 'admin@kosi-time.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', NULL, '2026-08-20 14:17:10'),
(675, 1, 'admin@kosi-time.com', 'superadmin', 'login_success', 'Administration', 1, 'admin@kosi-time.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', NULL, '2026-08-20 14:17:10'),
(676, 1, 'admin@kosi-time.com', 'superadmin', 'update', 'EntrepriseSiege', 10, 'test test test', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', NULL, '2026-08-20 14:17:38'),
(677, 1, 'admin@kosi-time.com', 'superadmin', 'update', 'EntrepriseSiege', 11, 'fdffdfd', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', NULL, '2026-08-20 14:17:42'),
(678, 1, 'admin@kosi-time.com', 'superadmin', 'update', 'EntrepriseSiege', 9, 'N8N', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', NULL, '2026-08-20 14:17:54'),
(679, 1, 'admin@kosi-time.com', 'superadmin', 'logout', 'Administration', 1, 'admin@kosi-time.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', NULL, '2026-08-20 14:25:27'),
(680, 1, 'admin@kosi-time.com', 'superadmin', 'logout', 'Administration', 1, 'admin@kosi-time.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', NULL, '2026-08-20 14:25:27'),
(681, 2, 'admin@geotrack.com', 'simple_admin', 'login_success', 'Administration', 2, 'admin@geotrack.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 1, '2026-08-20 14:25:42'),
(682, 2, 'admin@geotrack.com', 'simple_admin', 'login_success', 'Administration', 2, 'admin@geotrack.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 1, '2026-08-20 14:25:42'),
(683, 2, 'admin@geotrack.com', 'simple_admin', 'logout', 'Administration', 2, 'admin@geotrack.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 1, '2026-08-20 14:33:20'),
(684, 2, 'admin@geotrack.com', 'simple_admin', 'logout', 'Administration', 2, 'admin@geotrack.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 1, '2026-08-20 14:33:20'),
(685, 1, 'admin@kosi-time.com', 'superadmin', 'login_success', 'Administration', 1, 'admin@kosi-time.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', NULL, '2026-08-20 14:33:39'),
(686, 1, 'admin@kosi-time.com', 'superadmin', 'login_success', 'Administration', 1, 'admin@kosi-time.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', NULL, '2026-08-20 14:33:39'),
(687, 2, 'admin@geotrack.com', 'simple_admin', 'login_success', 'Administration', 2, 'admin@geotrack.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:154.0) Gecko/20100101 Firefox/154.0', 1, '2026-08-20 14:34:40'),
(688, 2, 'admin@geotrack.com', 'simple_admin', 'login_success', 'Administration', 2, 'admin@geotrack.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:154.0) Gecko/20100101 Firefox/154.0', 1, '2026-08-20 14:34:40'),
(689, 2, 'admin@geotrack.com', 'simple_admin', 'logout', 'Administration', 2, 'admin@geotrack.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:154.0) Gecko/20100101 Firefox/154.0', 1, '2026-08-20 15:04:25'),
(690, 2, 'admin@geotrack.com', 'simple_admin', 'logout', 'Administration', 2, 'admin@geotrack.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:154.0) Gecko/20100101 Firefox/154.0', 1, '2026-08-20 15:04:25'),
(691, NULL, 'unknown', NULL, 'login_success', 'Administration', 5, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:154.0) Gecko/20100101 Firefox/154.0', NULL, '2026-08-20 15:04:41'),
(692, NULL, 'unknown', NULL, 'login_success', 'Administration', 5, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:154.0) Gecko/20100101 Firefox/154.0', NULL, '2026-08-20 15:04:41'),
(693, 1, 'admin@kosi-time.com', 'superadmin', 'logout', 'Administration', 1, 'admin@kosi-time.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', NULL, '2026-08-20 15:23:10'),
(694, 1, 'admin@kosi-time.com', 'superadmin', 'logout', 'Administration', 1, 'admin@kosi-time.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', NULL, '2026-08-20 15:23:10'),
(695, 2, 'admin@geotrack.com', 'simple_admin', 'login_success', 'Administration', 2, 'admin@geotrack.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 1, '2026-08-20 15:23:31'),
(696, 2, 'admin@geotrack.com', 'simple_admin', 'login_success', 'Administration', 2, 'admin@geotrack.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 1, '2026-08-20 15:23:31'),
(697, NULL, 'malalanirnaemile2@gmail.com', NULL, 'login_failed', NULL, NULL, NULL, 'Identifiant introuvable', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:154.0) Gecko/20100101 Firefox/154.0', NULL, '2026-08-20 16:07:45'),
(698, 2, 'admin@geotrack.com', 'simple_admin', 'logout', 'Administration', 2, 'admin@geotrack.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 1, '2026-08-20 16:08:31'),
(699, 2, 'admin@geotrack.com', 'simple_admin', 'logout', 'Administration', 2, 'admin@geotrack.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 1, '2026-08-20 16:08:31'),
(700, NULL, 'unknown', NULL, 'login_success', 'Administration', 5, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', NULL, '2026-08-20 16:08:44'),
(701, NULL, 'unknown', NULL, 'login_success', 'Administration', 5, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', NULL, '2026-08-20 16:08:44'),
(702, NULL, 'unknown', NULL, 'login_success', 'Administration', 5, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', NULL, '2026-08-21 07:45:51'),
(703, NULL, 'unknown', NULL, 'login_success', 'Administration', 5, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', NULL, '2026-08-21 07:45:51'),
(704, NULL, 'unknown', NULL, 'login_success', 'Administration', 5, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:154.0) Gecko/20100101 Firefox/154.0', NULL, '2026-08-21 07:52:13'),
(705, NULL, 'unknown', NULL, 'login_success', 'Administration', 5, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:154.0) Gecko/20100101 Firefox/154.0', NULL, '2026-08-21 07:52:13'),
(706, NULL, 'unknown', NULL, 'login_success', 'Administration', 5, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:154.0) Gecko/20100101 Firefox/154.0', NULL, '2026-08-21 08:16:19'),
(707, NULL, 'unknown', NULL, 'login_success', 'Administration', 5, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:154.0) Gecko/20100101 Firefox/154.0', NULL, '2026-08-21 08:16:19'),
(708, 2, 'admin@geotrack.com', 'simple_admin', 'login_success', 'Administration', 2, 'admin@geotrack.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 1, '2026-08-21 08:30:12'),
(709, 2, 'admin@geotrack.com', 'simple_admin', 'login_success', 'Administration', 2, 'admin@geotrack.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 1, '2026-08-21 08:30:13'),
(710, 2, 'admin@geotrack.com', 'simple_admin', 'logout', 'Administration', 2, 'admin@geotrack.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 1, '2026-08-21 08:59:28'),
(711, 2, 'admin@geotrack.com', 'simple_admin', 'logout', 'Administration', 2, 'admin@geotrack.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 1, '2026-08-21 08:59:28'),
(712, 2, 'admin@geotrack.com', 'simple_admin', 'login_success', 'Administration', 2, 'admin@geotrack.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 1, '2026-08-21 08:59:45'),
(713, 2, 'admin@geotrack.com', 'simple_admin', 'login_success', 'Administration', 2, 'admin@geotrack.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 1, '2026-08-21 08:59:45'),
(714, 2, 'admin@geotrack.com', 'simple_admin', 'logout', 'Administration', 2, 'admin@geotrack.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 1, '2026-08-21 09:37:18'),
(715, 2, 'admin@geotrack.com', 'simple_admin', 'logout', 'Administration', 2, 'admin@geotrack.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 1, '2026-08-21 09:37:18'),
(716, 1, 'admin@kosi-time.com', 'superadmin', 'login_success', 'Administration', 1, 'admin@kosi-time.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', NULL, '2026-08-21 09:37:37'),
(717, 1, 'admin@kosi-time.com', 'superadmin', 'login_success', 'Administration', 1, 'admin@kosi-time.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', NULL, '2026-08-21 09:37:37'),
(718, 1, 'admin@kosi-time.com', 'superadmin', 'logout', 'Administration', 1, 'admin@kosi-time.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', NULL, '2026-08-21 09:38:58'),
(719, 1, 'admin@kosi-time.com', 'superadmin', 'logout', 'Administration', 1, 'admin@kosi-time.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', NULL, '2026-08-21 09:38:58'),
(720, 2, 'admin@geotrack.com', 'simple_admin', 'login_success', 'Administration', 2, 'admin@geotrack.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 1, '2026-08-21 09:39:13'),
(721, 2, 'admin@geotrack.com', 'simple_admin', 'login_success', 'Administration', 2, 'admin@geotrack.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 1, '2026-08-21 09:39:13'),
(722, 2, 'admin@geotrack.com', 'simple_admin', 'logout', 'Administration', 2, 'admin@geotrack.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 1, '2026-08-21 09:47:58'),
(723, 2, 'admin@geotrack.com', 'simple_admin', 'logout', 'Administration', 2, 'admin@geotrack.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 1, '2026-08-21 09:47:58'),
(724, 1, 'admin@kosi-time.com', 'superadmin', 'login_success', 'Administration', 1, 'admin@kosi-time.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', NULL, '2026-08-21 09:48:13'),
(725, 1, 'admin@kosi-time.com', 'superadmin', 'login_success', 'Administration', 1, 'admin@kosi-time.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', NULL, '2026-08-21 09:48:13'),
(726, 1, 'admin@kosi-time.com', 'superadmin', 'logout', 'Administration', 1, 'admin@kosi-time.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', NULL, '2026-08-21 09:49:58'),
(727, 1, 'admin@kosi-time.com', 'superadmin', 'logout', 'Administration', 1, 'admin@kosi-time.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', NULL, '2026-08-21 09:49:58'),
(728, 2, 'admin@geotrack.com', 'simple_admin', 'login_success', 'Administration', 2, 'admin@geotrack.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 1, '2026-08-21 09:50:12'),
(729, 2, 'admin@geotrack.com', 'simple_admin', 'login_success', 'Administration', 2, 'admin@geotrack.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 1, '2026-08-21 09:50:12'),
(730, 2, 'admin@geotrack.com', 'simple_admin', 'logout', 'Administration', 2, 'admin@geotrack.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 1, '2026-08-21 13:29:20'),
(731, 2, 'admin@geotrack.com', 'simple_admin', 'logout', 'Administration', 2, 'admin@geotrack.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 1, '2026-08-21 13:29:20'),
(732, NULL, 'unknown', NULL, 'login_success', 'Administration', 5, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', NULL, '2026-08-21 13:29:40'),
(733, NULL, 'unknown', NULL, 'login_success', 'Administration', 5, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', NULL, '2026-08-21 13:29:40'),
(734, NULL, 'admin@geotrack.com', NULL, 'login_failed', NULL, NULL, NULL, 'Mot de passe incorrect', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', NULL, '2026-08-21 13:31:46'),
(735, 2, 'admin@geotrack.com', 'simple_admin', 'login_success', 'Administration', 2, 'admin@geotrack.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 1, '2026-08-21 13:32:00'),
(736, 2, 'admin@geotrack.com', 'simple_admin', 'login_success', 'Administration', 2, 'admin@geotrack.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 1, '2026-08-21 13:32:00'),
(737, 2, 'admin@geotrack.com', 'simple_admin', 'logout', 'Administration', 2, 'admin@geotrack.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 1, '2026-08-21 13:32:10'),
(738, 2, 'admin@geotrack.com', 'simple_admin', 'logout', 'Administration', 2, 'admin@geotrack.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 1, '2026-08-21 13:32:10'),
(739, 2, 'admin@geotrack.com', 'simple_admin', 'login_success', 'Administration', 2, 'admin@geotrack.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 1, '2026-08-21 13:32:20'),
(740, 2, 'admin@geotrack.com', 'simple_admin', 'login_success', 'Administration', 2, 'admin@geotrack.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 1, '2026-08-21 13:32:20'),
(741, 1, 'admin@kosi-time.com', 'superadmin', 'login_success', 'Administration', 1, 'admin@kosi-time.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', NULL, '2026-08-24 07:42:08'),
(742, 1, 'admin@kosi-time.com', 'superadmin', 'login_success', 'Administration', 1, 'admin@kosi-time.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', NULL, '2026-08-24 07:42:08'),
(743, NULL, 'unknown', NULL, 'login_success', 'Administration', 5, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:154.0) Gecko/20100101 Firefox/154.0', NULL, '2026-08-24 07:48:10'),
(744, NULL, 'unknown', NULL, 'login_success', 'Administration', 5, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:154.0) Gecko/20100101 Firefox/154.0', NULL, '2026-08-24 07:48:10'),
(745, 1, 'admin@kosi-time.com', 'superadmin', 'logout', 'Administration', 1, 'admin@kosi-time.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', NULL, '2026-08-24 07:49:23'),
(746, 1, 'admin@kosi-time.com', 'superadmin', 'logout', 'Administration', 1, 'admin@kosi-time.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', NULL, '2026-08-24 07:49:23'),
(747, 2, 'admin@geotrack.com', 'simple_admin', 'login_success', 'Administration', 2, 'admin@geotrack.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 1, '2026-08-24 07:49:31'),
(748, 2, 'admin@geotrack.com', 'simple_admin', 'login_success', 'Administration', 2, 'admin@geotrack.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 1, '2026-08-24 07:49:31'),
(749, NULL, 'malalanirinaemile2@gmail.com', NULL, 'login_failed', NULL, NULL, NULL, 'Identifiant introuvable', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:154.0) Gecko/20100101 Firefox/154.0', NULL, '2026-08-24 08:18:38'),
(750, 2, 'admin@geotrack.com', 'simple_admin', 'logout', 'Administration', 2, 'admin@geotrack.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 1, '2026-08-24 08:45:18'),
(751, 2, 'admin@geotrack.com', 'simple_admin', 'logout', 'Administration', 2, 'admin@geotrack.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 1, '2026-08-24 08:45:18'),
(752, 2, 'admin@geotrack.com', 'simple_admin', 'login_success', 'Administration', 2, 'admin@geotrack.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 1, '2026-08-24 08:45:38'),
(753, 2, 'admin@geotrack.com', 'simple_admin', 'login_success', 'Administration', 2, 'admin@geotrack.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 1, '2026-08-24 08:45:38'),
(754, NULL, 'malalanirinaemile2@gmail.com', NULL, 'login_failed', NULL, NULL, NULL, 'Identifiant introuvable', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:154.0) Gecko/20100101 Firefox/154.0', NULL, '2026-08-24 09:21:40'),
(755, NULL, 'unknown', NULL, 'login_success', 'Administration', 5, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:154.0) Gecko/20100101 Firefox/154.0', NULL, '2026-08-24 09:22:36'),
(756, NULL, 'unknown', NULL, 'login_success', 'Administration', 5, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:154.0) Gecko/20100101 Firefox/154.0', NULL, '2026-08-24 09:22:36'),
(757, NULL, 'malalanirnaemile2@gmail.com', NULL, 'login_failed', NULL, NULL, NULL, 'Identifiant introuvable', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:154.0) Gecko/20100101 Firefox/154.0', NULL, '2026-08-24 13:51:39'),
(758, 2, 'admin@geotrack.com', 'simple_admin', 'login_success', 'Administration', 5, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 1, '2026-08-24 13:52:35'),
(759, 2, 'admin@geotrack.com', 'simple_admin', 'login_success', 'Administration', 5, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 1, '2026-08-24 13:52:35'),
(760, 2, 'admin@geotrack.com', 'simple_admin', 'logout', 'Administration', 2, 'admin@geotrack.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 1, '2026-08-24 14:14:33'),
(761, 2, 'admin@geotrack.com', 'simple_admin', 'logout', 'Administration', 2, 'admin@geotrack.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 1, '2026-08-24 14:14:33'),
(762, NULL, 'unknown', NULL, 'login_success', 'Administration', 5, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', NULL, '2026-08-24 14:14:49'),
(763, NULL, 'unknown', NULL, 'login_success', 'Administration', 5, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', NULL, '2026-08-24 14:14:49'),
(764, 2, 'admin@geotrack.com', 'simple_admin', 'login_success', 'Administration', 2, 'admin@geotrack.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 1, '2026-08-24 14:16:44'),
(765, 2, 'admin@geotrack.com', 'simple_admin', 'login_success', 'Administration', 2, 'admin@geotrack.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 1, '2026-08-24 14:16:44'),
(766, 2, 'admin@geotrack.com', 'simple_admin', 'login_success', 'Administration', 2, 'admin@geotrack.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:154.0) Gecko/20100101 Firefox/154.0', 1, '2026-08-24 15:03:40'),
(767, 2, 'admin@geotrack.com', 'simple_admin', 'login_success', 'Administration', 2, 'admin@geotrack.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:154.0) Gecko/20100101 Firefox/154.0', 1, '2026-08-24 15:03:40'),
(768, 2, 'admin@geotrack.com', 'simple_admin', 'logout', 'Administration', 2, 'admin@geotrack.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:154.0) Gecko/20100101 Firefox/154.0', 1, '2026-08-24 15:04:03'),
(769, 2, 'admin@geotrack.com', 'simple_admin', 'logout', 'Administration', 2, 'admin@geotrack.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:154.0) Gecko/20100101 Firefox/154.0', 1, '2026-08-24 15:04:03'),
(770, 2, 'admin@geotrack.com', 'simple_admin', 'login_success', 'Administration', 2, 'admin@geotrack.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:154.0) Gecko/20100101 Firefox/154.0', 1, '2026-08-24 15:04:07'),
(771, 2, 'admin@geotrack.com', 'simple_admin', 'login_success', 'Administration', 2, 'admin@geotrack.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:154.0) Gecko/20100101 Firefox/154.0', 1, '2026-08-24 15:04:07'),
(772, NULL, 'unknown', NULL, 'login_success', 'Administration', 5, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:154.0) Gecko/20100101 Firefox/154.0', NULL, '2026-08-26 07:45:03'),
(773, NULL, 'unknown', NULL, 'login_success', 'Administration', 5, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:154.0) Gecko/20100101 Firefox/154.0', NULL, '2026-08-26 07:45:03'),
(774, 1, 'admin@kosi-time.com', 'superadmin', 'login_success', 'Administration', 1, 'admin@kosi-time.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', NULL, '2026-08-26 07:45:22'),
(775, 1, 'admin@kosi-time.com', 'superadmin', 'login_success', 'Administration', 1, 'admin@kosi-time.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', NULL, '2026-08-26 07:45:22'),
(776, 1, 'admin@kosi-time.com', 'superadmin', 'logout', 'Administration', 1, 'admin@kosi-time.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', NULL, '2026-08-26 08:21:23');
INSERT INTO `activity_logs` (`id`, `user_id`, `user_email`, `user_role`, `action`, `model_type`, `model_id`, `model_label`, `description`, `ip_address`, `user_agent`, `SiegeID`, `created_at`) VALUES
(777, 1, 'admin@kosi-time.com', 'superadmin', 'logout', 'Administration', 1, 'admin@kosi-time.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', NULL, '2026-08-26 08:21:23'),
(778, 2, 'admin@geotrack.com', 'simple_admin', 'login_success', 'Administration', 2, 'admin@geotrack.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 1, '2026-08-26 08:21:57'),
(779, 2, 'admin@geotrack.com', 'simple_admin', 'login_success', 'Administration', 2, 'admin@geotrack.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 1, '2026-08-26 08:21:57'),
(780, 1, 'admin@kosi-time.com', 'superadmin', 'login_success', 'Administration', 1, 'admin@kosi-time.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', NULL, '2026-08-26 13:03:55'),
(781, 1, 'admin@kosi-time.com', 'superadmin', 'login_success', 'Administration', 1, 'admin@kosi-time.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', NULL, '2026-08-26 13:03:55'),
(782, 1, 'admin@kosi-time.com', 'superadmin', 'logout', 'Administration', 1, 'admin@kosi-time.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', NULL, '2026-08-26 13:10:52'),
(783, 1, 'admin@kosi-time.com', 'superadmin', 'logout', 'Administration', 1, 'admin@kosi-time.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', NULL, '2026-08-26 13:10:52'),
(784, 2, 'admin@geotrack.com', 'simple_admin', 'login_success', 'Administration', 2, 'admin@geotrack.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 1, '2026-08-26 13:11:11'),
(785, 2, 'admin@geotrack.com', 'simple_admin', 'login_success', 'Administration', 2, 'admin@geotrack.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 1, '2026-08-26 13:11:11'),
(786, NULL, 'admin@kosi-time.com', NULL, 'login_failed', NULL, NULL, NULL, 'Mot de passe incorrect', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', NULL, '2026-08-27 07:40:29'),
(787, 1, 'admin@kosi-time.com', 'superadmin', 'login_success', 'Administration', 1, 'admin@kosi-time.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', NULL, '2026-08-27 07:40:48'),
(788, 1, 'admin@kosi-time.com', 'superadmin', 'login_success', 'Administration', 1, 'admin@kosi-time.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', NULL, '2026-08-27 07:40:48'),
(789, NULL, 'unknown', NULL, 'login_success', 'Administration', 5, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:154.0) Gecko/20100101 Firefox/154.0', NULL, '2026-08-27 07:51:28'),
(790, NULL, 'unknown', NULL, 'login_success', 'Administration', 5, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:154.0) Gecko/20100101 Firefox/154.0', NULL, '2026-08-27 07:51:28'),
(791, 1, 'admin@kosi-time.com', 'superadmin', 'login_success', 'Administration', 1, 'admin@kosi-time.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', NULL, '2026-08-27 09:57:46'),
(792, 1, 'admin@kosi-time.com', 'superadmin', 'login_success', 'Administration', 1, 'admin@kosi-time.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', NULL, '2026-08-27 09:57:46'),
(793, NULL, 'unknown', NULL, 'login_success', 'Administration', 5, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:154.0) Gecko/20100101 Firefox/154.0', NULL, '2026-08-27 13:02:24'),
(794, NULL, 'unknown', NULL, 'login_success', 'Administration', 5, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:154.0) Gecko/20100101 Firefox/154.0', NULL, '2026-08-27 13:02:24'),
(795, 1, 'admin@kosi-time.com', 'superadmin', 'logout', 'Administration', 1, 'admin@kosi-time.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', NULL, '2026-08-27 16:03:59'),
(796, 1, 'admin@kosi-time.com', 'superadmin', 'logout', 'Administration', 1, 'admin@kosi-time.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', NULL, '2026-08-27 16:03:59'),
(797, NULL, 'unknown', NULL, 'login_success', 'Administration', 5, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', NULL, '2026-08-27 16:04:14'),
(798, NULL, 'unknown', NULL, 'login_success', 'Administration', 5, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', NULL, '2026-08-27 16:04:14'),
(799, 2, 'admin@geotrack.com', 'simple_admin', 'login_success', 'Administration', 2, 'admin@geotrack.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:154.0) Gecko/20100101 Firefox/154.0', 1, '2026-08-27 16:06:51'),
(800, 2, 'admin@geotrack.com', 'simple_admin', 'login_success', 'Administration', 2, 'admin@geotrack.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:154.0) Gecko/20100101 Firefox/154.0', 1, '2026-08-27 16:06:51'),
(801, 2, 'admin@geotrack.com', 'simple_admin', 'login_success', 'Administration', 2, 'admin@geotrack.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 1, '2026-08-27 16:08:01'),
(802, 2, 'admin@geotrack.com', 'simple_admin', 'login_success', 'Administration', 2, 'admin@geotrack.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 1, '2026-08-27 16:08:01'),
(803, 2, 'admin@geotrack.com', 'simple_admin', 'logout', 'Administration', 2, 'admin@geotrack.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:154.0) Gecko/20100101 Firefox/154.0', 1, '2026-08-27 16:08:10'),
(804, 2, 'admin@geotrack.com', 'simple_admin', 'logout', 'Administration', 2, 'admin@geotrack.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:154.0) Gecko/20100101 Firefox/154.0', 1, '2026-08-27 16:08:10'),
(805, 2, 'admin@geotrack.com', 'simple_admin', 'login_success', 'Administration', 2, 'admin@geotrack.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:154.0) Gecko/20100101 Firefox/154.0', 1, '2026-08-27 16:08:15'),
(806, 2, 'admin@geotrack.com', 'simple_admin', 'login_success', 'Administration', 2, 'admin@geotrack.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:154.0) Gecko/20100101 Firefox/154.0', 1, '2026-08-27 16:08:15'),
(807, NULL, 'malalanirinaemile2@gmail.com', NULL, 'login_failed', NULL, NULL, NULL, 'Identifiant introuvable', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:154.0) Gecko/20100101 Firefox/154.0', NULL, '2026-08-28 14:46:32'),
(808, NULL, 'unknown', NULL, 'login_success', 'Administration', 5, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:154.0) Gecko/20100101 Firefox/154.0', NULL, '2026-08-28 14:46:48'),
(809, NULL, 'unknown', NULL, 'login_success', 'Administration', 5, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:154.0) Gecko/20100101 Firefox/154.0', NULL, '2026-08-28 14:46:48'),
(810, 1, 'admin@kosi-time.com', 'superadmin', 'login_success', 'Administration', 1, 'admin@kosi-time.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', NULL, '2026-08-28 14:50:06'),
(811, 1, 'admin@kosi-time.com', 'superadmin', 'login_success', 'Administration', 1, 'admin@kosi-time.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', NULL, '2026-08-28 14:50:06'),
(812, 1, 'admin@kosi-time.com', 'superadmin', 'logout', 'Administration', 1, 'admin@kosi-time.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', NULL, '2026-08-28 15:09:37'),
(813, 1, 'admin@kosi-time.com', 'superadmin', 'logout', 'Administration', 1, 'admin@kosi-time.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', NULL, '2026-08-28 15:09:37'),
(814, 2, 'admin@geotrack.com', 'simple_admin', 'login_success', 'Administration', 2, 'admin@geotrack.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', 1, '2026-08-28 15:10:12'),
(815, 2, 'admin@geotrack.com', 'simple_admin', 'login_success', 'Administration', 2, 'admin@geotrack.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', 1, '2026-08-28 15:10:12'),
(816, 2, 'admin@geotrack.com', 'simple_admin', 'logout', 'Administration', 2, 'admin@geotrack.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', 1, '2026-08-28 15:11:06'),
(817, 2, 'admin@geotrack.com', 'simple_admin', 'logout', 'Administration', 2, 'admin@geotrack.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', 1, '2026-08-28 15:11:06'),
(818, 1, 'admin@kosi-time.com', 'superadmin', 'login_success', 'Administration', 1, 'admin@kosi-time.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', NULL, '2026-08-28 15:11:23'),
(819, 1, 'admin@kosi-time.com', 'superadmin', 'login_success', 'Administration', 1, 'admin@kosi-time.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', NULL, '2026-08-28 15:11:23'),
(820, 1, 'admin@kosi-time.com', 'superadmin', 'logout', 'Administration', 1, 'admin@kosi-time.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', NULL, '2026-08-28 15:45:10'),
(821, 1, 'admin@kosi-time.com', 'superadmin', 'logout', 'Administration', 1, 'admin@kosi-time.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', NULL, '2026-08-28 15:45:10'),
(822, 2, 'admin@geotrack.com', 'simple_admin', 'login_success', 'Administration', 2, 'admin@geotrack.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', 1, '2026-08-28 15:45:35'),
(823, 2, 'admin@geotrack.com', 'simple_admin', 'login_success', 'Administration', 2, 'admin@geotrack.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', 1, '2026-08-28 15:45:35'),
(824, 2, 'admin@geotrack.com', 'simple_admin', 'logout', 'Administration', 2, 'admin@geotrack.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', 1, '2026-08-28 15:47:10'),
(825, 2, 'admin@geotrack.com', 'simple_admin', 'logout', 'Administration', 2, 'admin@geotrack.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', 1, '2026-08-28 15:47:10'),
(826, NULL, 'admin@geotrack.com', NULL, 'login_failed', NULL, NULL, NULL, 'Mot de passe incorrect', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', NULL, '2026-08-28 15:49:08'),
(827, 2, 'admin@geotrack.com', 'simple_admin', 'login_success', 'Administration', 2, 'admin@geotrack.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', 1, '2026-08-28 15:49:40'),
(828, 2, 'admin@geotrack.com', 'simple_admin', 'login_success', 'Administration', 2, 'admin@geotrack.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', 1, '2026-08-28 15:49:40'),
(829, 2, 'admin@geotrack.com', 'simple_admin', 'logout', 'Administration', 2, 'admin@geotrack.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', 1, '2026-08-28 15:57:05'),
(830, 2, 'admin@geotrack.com', 'simple_admin', 'logout', 'Administration', 2, 'admin@geotrack.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', 1, '2026-08-28 15:57:05'),
(831, 1, 'admin@kosi-time.com', 'superadmin', 'login_success', 'Administration', 1, 'admin@kosi-time.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', NULL, '2026-08-28 15:57:25'),
(832, 1, 'admin@kosi-time.com', 'superadmin', 'login_success', 'Administration', 1, 'admin@kosi-time.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', NULL, '2026-08-28 15:57:25'),
(833, 1, 'admin@kosi-time.com', 'superadmin', 'create', 'Administration', 18, 'testautreadmin@gmail.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', NULL, '2026-08-28 15:58:45'),
(834, 1, 'admin@kosi-time.com', 'superadmin', 'logout', 'Administration', 1, 'admin@kosi-time.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', NULL, '2026-08-28 15:58:51'),
(835, 1, 'admin@kosi-time.com', 'superadmin', 'logout', 'Administration', 1, 'admin@kosi-time.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', NULL, '2026-08-28 15:58:51'),
(836, 18, 'testautreadmin@gmail.com', 'simple_admin', 'login_success', 'Administration', 18, 'testautreadmin@gmail.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', 2, '2026-08-28 15:58:57'),
(837, 18, 'testautreadmin@gmail.com', 'simple_admin', 'login_success', 'Administration', 18, 'testautreadmin@gmail.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', 2, '2026-08-28 15:58:57'),
(838, 18, 'testautreadmin@gmail.com', 'simple_admin', 'create', 'Employe', 61, 'TEST EMPLOYE POUR ASSIGNER UN MANAGER', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', 2, '2026-08-28 16:17:41'),
(839, 18, 'testautreadmin@gmail.com', 'simple_admin', 'update', 'Department', 34, 'Service informatique', 'Service mis à jour : Service informatique', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', 2, '2026-08-28 16:20:15'),
(840, 18, 'testautreadmin@gmail.com', 'simple_admin', 'logout', 'Administration', 18, 'testautreadmin@gmail.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', 2, '2026-08-28 16:26:32'),
(841, 18, 'testautreadmin@gmail.com', 'simple_admin', 'logout', 'Administration', 18, 'testautreadmin@gmail.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', 2, '2026-08-28 16:26:32'),
(842, 1, 'admin@kosi-time.com', 'superadmin', 'login_success', 'Administration', 1, 'admin@kosi-time.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', NULL, '2026-08-28 16:26:59'),
(843, 1, 'admin@kosi-time.com', 'superadmin', 'login_success', 'Administration', 1, 'admin@kosi-time.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', NULL, '2026-08-28 16:26:59'),
(844, 1, 'admin@kosi-time.com', 'superadmin', 'update', 'Employe', 16, 'Tarkin Willy', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', NULL, '2026-08-28 16:28:21'),
(845, 1, 'admin@kosi-time.com', 'superadmin', 'logout', 'Administration', 1, 'admin@kosi-time.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', NULL, '2026-08-28 16:28:28'),
(846, 1, 'admin@kosi-time.com', 'superadmin', 'logout', 'Administration', 1, 'admin@kosi-time.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', NULL, '2026-08-28 16:28:28'),
(847, 18, 'testautreadmin@gmail.com', 'simple_admin', 'login_success', 'Administration', 18, 'testautreadmin@gmail.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', 2, '2026-08-28 16:28:44'),
(848, 18, 'testautreadmin@gmail.com', 'simple_admin', 'login_success', 'Administration', 18, 'testautreadmin@gmail.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', 2, '2026-08-28 16:28:44'),
(849, NULL, 'unknown', NULL, 'login_success', 'Administration', 5, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', NULL, '2026-08-31 08:39:37'),
(850, NULL, 'unknown', NULL, 'login_success', 'Administration', 5, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', NULL, '2026-08-31 08:39:37'),
(851, NULL, 'unknown', NULL, 'login_success', 'Administration', 5, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:154.0) Gecko/20100101 Firefox/154.0', NULL, '2026-08-31 08:39:54'),
(852, NULL, 'unknown', NULL, 'login_success', 'Administration', 5, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:154.0) Gecko/20100101 Firefox/154.0', NULL, '2026-08-31 08:39:54'),
(853, 2, 'admin@geotrack.com', 'simple_admin', 'login_success', 'Administration', 2, 'admin@geotrack.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', 1, '2026-08-31 08:50:08'),
(854, 2, 'admin@geotrack.com', 'simple_admin', 'login_success', 'Administration', 2, 'admin@geotrack.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', 1, '2026-08-31 08:50:08'),
(855, 2, 'admin@geotrack.com', 'simple_admin', 'login_success', 'Administration', 2, 'admin@geotrack.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:154.0) Gecko/20100101 Firefox/154.0', 1, '2026-08-31 08:52:35'),
(856, 2, 'admin@geotrack.com', 'simple_admin', 'login_success', 'Administration', 2, 'admin@geotrack.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:154.0) Gecko/20100101 Firefox/154.0', 1, '2026-08-31 08:52:35'),
(857, 2, 'admin@geotrack.com', 'simple_admin', 'logout', 'Administration', 2, 'admin@geotrack.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:154.0) Gecko/20100101 Firefox/154.0', 1, '2026-08-31 08:52:42'),
(858, 2, 'admin@geotrack.com', 'simple_admin', 'logout', 'Administration', 2, 'admin@geotrack.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:154.0) Gecko/20100101 Firefox/154.0', 1, '2026-08-31 08:52:42'),
(859, 2, 'admin@geotrack.com', 'simple_admin', 'login_success', 'Administration', 2, 'admin@geotrack.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:154.0) Gecko/20100101 Firefox/154.0', 1, '2026-08-31 08:52:52'),
(860, 2, 'admin@geotrack.com', 'simple_admin', 'login_success', 'Administration', 2, 'admin@geotrack.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:154.0) Gecko/20100101 Firefox/154.0', 1, '2026-08-31 08:52:52'),
(861, 2, 'admin@geotrack.com', 'simple_admin', 'logout', 'Administration', 2, 'admin@geotrack.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', 1, '2026-08-31 09:20:59'),
(862, 2, 'admin@geotrack.com', 'simple_admin', 'logout', 'Administration', 2, 'admin@geotrack.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', 1, '2026-08-31 09:20:59'),
(863, 18, 'testautreadmin@gmail.com', 'simple_admin', 'login_success', 'Administration', 18, 'testautreadmin@gmail.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', 2, '2026-08-31 09:21:10'),
(864, 18, 'testautreadmin@gmail.com', 'simple_admin', 'login_success', 'Administration', 18, 'testautreadmin@gmail.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', 2, '2026-08-31 09:21:10'),
(865, 2, 'admin@geotrack.com', 'simple_admin', 'create', 'Employe', 62, 'RAHARIMANANA ALEX', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:154.0) Gecko/20100101 Firefox/154.0', 1, '2026-08-31 09:29:24'),
(866, 2, 'admin@geotrack.com', 'simple_admin', 'update', 'Employe', 62, 'RAHARIMANANA ALEX', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:154.0) Gecko/20100101 Firefox/154.0', 1, '2026-08-31 09:31:05'),
(867, 2, 'admin@geotrack.com', 'simple_admin', 'assign_web_access', 'Employe', 62, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:154.0) Gecko/20100101 Firefox/154.0', 1, '2026-08-31 09:31:05'),
(868, 18, 'testautreadmin@gmail.com', 'simple_admin', 'logout', 'Administration', 18, 'testautreadmin@gmail.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', 2, '2026-08-31 09:31:15'),
(869, 18, 'testautreadmin@gmail.com', 'simple_admin', 'logout', 'Administration', 18, 'testautreadmin@gmail.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', 2, '2026-08-31 09:31:15'),
(870, NULL, 'alex@gmail.com', NULL, 'login_failed', NULL, NULL, NULL, 'Identifiant introuvable', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', NULL, '2026-08-31 09:31:25'),
(871, NULL, 'admin@kosi-time.com', NULL, 'login_failed', NULL, NULL, NULL, 'Mot de passe incorrect', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', NULL, '2026-08-31 09:32:52'),
(872, 1, 'admin@kosi-time.com', 'superadmin', 'login_success', 'Administration', 1, 'admin@kosi-time.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', NULL, '2026-08-31 09:33:06'),
(873, 1, 'admin@kosi-time.com', 'superadmin', 'login_success', 'Administration', 1, 'admin@kosi-time.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', NULL, '2026-08-31 09:33:06'),
(874, 1, 'admin@kosi-time.com', 'superadmin', 'update', 'Employe', 62, 'RAHARIMANANA ALEX', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', NULL, '2026-08-31 09:34:00'),
(875, 1, 'admin@kosi-time.com', 'superadmin', 'logout', 'Administration', 1, 'admin@kosi-time.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', NULL, '2026-08-31 09:34:09'),
(876, 1, 'admin@kosi-time.com', 'superadmin', 'logout', 'Administration', 1, 'admin@kosi-time.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', NULL, '2026-08-31 09:34:09'),
(877, NULL, 'unknown', NULL, 'login_success', 'Administration', 62, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', NULL, '2026-08-31 09:34:28'),
(878, NULL, 'unknown', NULL, 'login_success', 'Administration', 62, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', NULL, '2026-08-31 09:34:29'),
(879, 18, 'testautreadmin@gmail.com', 'simple_admin', 'login_success', 'Administration', 18, 'testautreadmin@gmail.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', 2, '2026-08-31 09:45:46'),
(880, 18, 'testautreadmin@gmail.com', 'simple_admin', 'login_success', 'Administration', 18, 'testautreadmin@gmail.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', 2, '2026-08-31 09:45:46'),
(881, 18, 'testautreadmin@gmail.com', 'simple_admin', 'update', 'Employe', 61, 'TEST EMPLOYE POUR ASSIGNER UN MANAGER', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', 2, '2026-08-31 09:57:37'),
(882, 18, 'testautreadmin@gmail.com', 'simple_admin', 'assign_web_access', 'Employe', 61, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', 2, '2026-08-31 09:57:37'),
(883, 18, 'testautreadmin@gmail.com', 'simple_admin', 'update', 'Employe', 16, 'Tarkin Willy', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', 2, '2026-08-31 10:14:28'),
(884, 18, 'testautreadmin@gmail.com', 'simple_admin', 'assign_web_access', 'Employe', 16, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', 2, '2026-08-31 10:14:28'),
(885, 18, 'testautreadmin@gmail.com', 'simple_admin', 'logout', 'Administration', 18, 'testautreadmin@gmail.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', 2, '2026-08-31 10:14:50'),
(886, 18, 'testautreadmin@gmail.com', 'simple_admin', 'logout', 'Administration', 18, 'testautreadmin@gmail.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', 2, '2026-08-31 10:14:50'),
(887, NULL, 'testautreadminrun@gmail.com', NULL, 'login_failed', NULL, NULL, NULL, 'Identifiant introuvable', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', NULL, '2026-08-31 10:14:56'),
(888, 1, 'admin@kosi-time.com', 'superadmin', 'login_success', 'Administration', 1, 'admin@kosi-time.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', NULL, '2026-08-31 10:15:36'),
(889, 1, 'admin@kosi-time.com', 'superadmin', 'login_success', 'Administration', 1, 'admin@kosi-time.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', NULL, '2026-08-31 10:15:36'),
(890, 1, 'admin@kosi-time.com', 'superadmin', 'update', 'Administration', 4, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', NULL, '2026-08-31 10:16:50'),
(891, 1, 'admin@kosi-time.com', 'superadmin', 'logout', 'Administration', 1, 'admin@kosi-time.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', NULL, '2026-08-31 10:16:56'),
(892, 1, 'admin@kosi-time.com', 'superadmin', 'logout', 'Administration', 1, 'admin@kosi-time.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', NULL, '2026-08-31 10:16:56'),
(893, NULL, 'testautreadminrun@gmail.com', NULL, 'login_failed', NULL, NULL, NULL, 'Identifiant introuvable', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', NULL, '2026-08-31 10:17:10'),
(894, NULL, 'testautreadminrun@gmail.com', NULL, 'login_failed', NULL, NULL, NULL, 'Identifiant introuvable', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', NULL, '2026-08-31 10:17:20'),
(895, 1, 'admin@kosi-time.com', 'superadmin', 'login_success', 'Administration', 1, 'admin@kosi-time.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', NULL, '2026-08-31 10:17:44'),
(896, 1, 'admin@kosi-time.com', 'superadmin', 'login_success', 'Administration', 1, 'admin@kosi-time.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', NULL, '2026-08-31 10:17:44'),
(897, 1, 'admin@kosi-time.com', 'superadmin', 'login_success', 'Administration', 18, 'testautreadmin@gmail.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', NULL, '2026-08-31 10:18:42'),
(898, 1, 'admin@kosi-time.com', 'superadmin', 'login_success', 'Administration', 18, 'testautreadmin@gmail.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', NULL, '2026-08-31 10:18:42'),
(899, 2, 'admin@geotrack.com', 'simple_admin', 'logout', 'Administration', 2, 'admin@geotrack.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:154.0) Gecko/20100101 Firefox/154.0', 1, '2026-08-31 10:19:30'),
(900, 2, 'admin@geotrack.com', 'simple_admin', 'logout', 'Administration', 2, 'admin@geotrack.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:154.0) Gecko/20100101 Firefox/154.0', 1, '2026-08-31 10:19:30'),
(901, NULL, 'unknown', NULL, 'login_success', 'Administration', 62, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:154.0) Gecko/20100101 Firefox/154.0', NULL, '2026-08-31 10:19:48'),
(902, NULL, 'unknown', NULL, 'login_success', 'Administration', 62, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:154.0) Gecko/20100101 Firefox/154.0', NULL, '2026-08-31 10:19:48'),
(903, 18, 'testautreadmin@gmail.com', 'simple_admin', 'logout', 'Administration', 18, 'testautreadmin@gmail.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', 2, '2026-08-31 10:22:48'),
(904, 18, 'testautreadmin@gmail.com', 'simple_admin', 'logout', 'Administration', 18, 'testautreadmin@gmail.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', 2, '2026-08-31 10:22:48'),
(905, 1, 'admin@kosi-time.com', 'superadmin', 'login_success', 'Administration', 1, 'admin@kosi-time.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', NULL, '2026-08-31 10:25:11'),
(906, 1, 'admin@kosi-time.com', 'superadmin', 'login_success', 'Administration', 1, 'admin@kosi-time.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', NULL, '2026-08-31 10:25:11'),
(907, 1, 'admin@kosi-time.com', 'superadmin', 'update', 'Employe', 62, 'RAHARIMANANA ALEX', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', NULL, '2026-08-31 10:26:07'),
(908, 1, 'admin@kosi-time.com', 'superadmin', 'logout', 'Administration', 1, 'admin@kosi-time.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', NULL, '2026-08-31 10:27:08'),
(909, 1, 'admin@kosi-time.com', 'superadmin', 'logout', 'Administration', 1, 'admin@kosi-time.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', NULL, '2026-08-31 10:27:08'),
(910, 1, 'admin@kosi-time.com', 'superadmin', 'login_success', 'Administration', 1, 'admin@kosi-time.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', NULL, '2026-08-31 10:27:33'),
(911, 1, 'admin@kosi-time.com', 'superadmin', 'login_success', 'Administration', 1, 'admin@kosi-time.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', NULL, '2026-08-31 10:27:33'),
(912, 1, 'admin@kosi-time.com', 'superadmin', 'logout', 'Administration', 1, 'admin@kosi-time.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', NULL, '2026-08-31 10:27:55'),
(913, 1, 'admin@kosi-time.com', 'superadmin', 'logout', 'Administration', 1, 'admin@kosi-time.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', NULL, '2026-08-31 10:27:55'),
(914, 18, 'testautreadmin@gmail.com', 'simple_admin', 'login_success', 'Administration', 18, 'testautreadmin@gmail.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', 2, '2026-08-31 10:28:00'),
(915, 18, 'testautreadmin@gmail.com', 'simple_admin', 'login_success', 'Administration', 18, 'testautreadmin@gmail.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', 2, '2026-08-31 10:28:00'),
(916, 18, 'testautreadmin@gmail.com', 'simple_admin', 'login_success', 'Administration', 18, 'testautreadmin@gmail.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', 2, '2026-08-31 10:33:04'),
(917, 18, 'testautreadmin@gmail.com', 'simple_admin', 'login_success', 'Administration', 18, 'testautreadmin@gmail.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', 2, '2026-08-31 10:33:04'),
(918, 18, 'testautreadmin@gmail.com', 'simple_admin', 'logout', 'Administration', 18, 'testautreadmin@gmail.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', 2, '2026-08-31 10:40:35'),
(919, 18, 'testautreadmin@gmail.com', 'simple_admin', 'logout', 'Administration', 18, 'testautreadmin@gmail.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', 2, '2026-08-31 10:40:35'),
(920, 1, 'admin@kosi-time.com', 'superadmin', 'login_success', 'Administration', 1, 'admin@kosi-time.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', NULL, '2026-08-31 10:40:50'),
(921, 1, 'admin@kosi-time.com', 'superadmin', 'login_success', 'Administration', 1, 'admin@kosi-time.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', NULL, '2026-08-31 10:40:50'),
(922, 1, 'admin@kosi-time.com', 'superadmin', 'login_success', 'Administration', 16, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', NULL, '2026-08-31 10:41:56'),
(923, 1, 'admin@kosi-time.com', 'superadmin', 'login_success', 'Administration', 16, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', NULL, '2026-08-31 10:41:56'),
(924, 1, 'admin@kosi-time.com', 'superadmin', 'logout', 'Administration', 1, 'admin@kosi-time.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', NULL, '2026-08-31 10:46:35'),
(925, 1, 'admin@kosi-time.com', 'superadmin', 'logout', 'Administration', 1, 'admin@kosi-time.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', NULL, '2026-08-31 10:46:35'),
(926, 18, 'testautreadmin@gmail.com', 'simple_admin', 'login_success', 'Administration', 18, 'testautreadmin@gmail.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', 2, '2026-08-31 10:46:51'),
(927, 18, 'testautreadmin@gmail.com', 'simple_admin', 'login_success', 'Administration', 18, 'testautreadmin@gmail.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', 2, '2026-08-31 10:46:51'),
(928, 18, 'testautreadmin@gmail.com', 'simple_admin', 'logout', 'Administration', 18, 'testautreadmin@gmail.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', 2, '2026-08-31 12:01:28'),
(929, 18, 'testautreadmin@gmail.com', 'simple_admin', 'logout', 'Administration', 18, 'testautreadmin@gmail.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', 2, '2026-08-31 12:01:28'),
(930, 18, 'testautreadmin@gmail.com', 'simple_admin', 'login_success', 'Administration', 18, 'testautreadmin@gmail.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', 2, '2026-08-31 12:02:44'),
(931, 18, 'testautreadmin@gmail.com', 'simple_admin', 'login_success', 'Administration', 18, 'testautreadmin@gmail.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', 2, '2026-08-31 12:02:44'),
(932, 18, 'testautreadmin@gmail.com', 'simple_admin', 'logout', 'Administration', 18, 'testautreadmin@gmail.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', 2, '2026-08-31 12:03:12'),
(933, 18, 'testautreadmin@gmail.com', 'simple_admin', 'logout', 'Administration', 18, 'testautreadmin@gmail.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', 2, '2026-08-31 12:03:12'),
(934, 1, 'admin@kosi-time.com', 'superadmin', 'login_success', 'Administration', 1, 'admin@kosi-time.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', NULL, '2026-08-31 12:03:23'),
(935, 1, 'admin@kosi-time.com', 'superadmin', 'login_success', 'Administration', 1, 'admin@kosi-time.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', NULL, '2026-08-31 12:03:23'),
(936, 1, 'admin@kosi-time.com', 'superadmin', 'login_success', 'Administration', 16, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', NULL, '2026-08-31 12:07:18'),
(937, 1, 'admin@kosi-time.com', 'superadmin', 'login_success', 'Administration', 16, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', NULL, '2026-08-31 12:07:18'),
(938, 1, 'admin@kosi-time.com', 'superadmin', 'logout', 'Administration', 1, 'admin@kosi-time.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', NULL, '2026-08-31 12:09:42'),
(939, 1, 'admin@kosi-time.com', 'superadmin', 'logout', 'Administration', 1, 'admin@kosi-time.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', NULL, '2026-08-31 12:09:42'),
(940, NULL, 'testautreadminrun@gmail.com', NULL, 'login_failed', NULL, NULL, NULL, 'Identifiant introuvable', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', NULL, '2026-08-31 12:09:51'),
(941, 1, 'admin@kosi-time.com', 'superadmin', 'login_success', 'Administration', 1, 'admin@kosi-time.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', NULL, '2026-08-31 12:19:44'),
(942, 1, 'admin@kosi-time.com', 'superadmin', 'login_success', 'Administration', 1, 'admin@kosi-time.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', NULL, '2026-08-31 12:19:44'),
(943, 1, 'admin@kosi-time.com', 'superadmin', 'logout', 'Administration', 1, 'admin@kosi-time.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', NULL, '2026-08-31 13:07:45'),
(944, 1, 'admin@kosi-time.com', 'superadmin', 'logout', 'Administration', 1, 'admin@kosi-time.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', NULL, '2026-08-31 13:07:45'),
(945, NULL, 'admin@geotrack.com', NULL, 'login_failed', NULL, NULL, NULL, 'Mot de passe incorrect', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', NULL, '2026-08-31 13:08:13'),
(946, NULL, 'admin@geotrack.com', NULL, 'login_failed', NULL, NULL, NULL, 'Mot de passe incorrect', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', NULL, '2026-08-31 13:08:27'),
(947, 2, 'admin@geotrack.com', 'simple_admin', 'login_success', 'Administration', 2, 'admin@geotrack.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', 1, '2026-08-31 13:08:40'),
(948, 2, 'admin@geotrack.com', 'simple_admin', 'login_success', 'Administration', 2, 'admin@geotrack.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', 1, '2026-08-31 13:08:40'),
(949, NULL, 'malalanirinaemile2@gmail.com', NULL, 'login_failed', NULL, NULL, NULL, 'Identifiant introuvable', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:154.0) Gecko/20100101 Firefox/154.0', NULL, '2026-08-31 13:10:44'),
(950, 2, 'admin@geotrack.com', 'simple_admin', 'login_success', 'Administration', 5, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', 1, '2026-08-31 13:12:22'),
(951, 2, 'admin@geotrack.com', 'simple_admin', 'login_success', 'Administration', 5, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', 1, '2026-08-31 13:12:22'),
(952, 1, 'admin@kosi-time.com', 'superadmin', 'login_success', 'Administration', 1, 'admin@kosi-time.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:154.0) Gecko/20100101 Firefox/154.0', NULL, '2026-08-31 13:18:32'),
(953, 1, 'admin@kosi-time.com', 'superadmin', 'login_success', 'Administration', 1, 'admin@kosi-time.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:154.0) Gecko/20100101 Firefox/154.0', NULL, '2026-08-31 13:18:32'),
(954, 1, 'admin@kosi-time.com', 'superadmin', 'create', 'Employe', 63, 'testemanager', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:154.0) Gecko/20100101 Firefox/154.0', NULL, '2026-08-31 13:20:34'),
(955, 2, 'admin@geotrack.com', 'simple_admin', 'create', 'Employe', 64, 'tetsecreeremployedepuis adminsiege', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', 1, '2026-08-31 13:22:37'),
(956, 2, 'admin@geotrack.com', 'simple_admin', 'create', 'Employe', 65, 'tetsecreeremployedepuis adminsiege2', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', 1, '2026-08-31 13:25:19'),
(957, 1, 'admin@kosi-time.com', 'superadmin', 'update', 'Employe', 61, 'TEST EMPLOYE POUR ASSIGNER UN MANAGER', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:154.0) Gecko/20100101 Firefox/154.0', NULL, '2026-08-31 13:30:00'),
(958, 1, 'admin@kosi-time.com', 'superadmin', 'update', 'Employe', 16, 'Tarkin Willy', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:154.0) Gecko/20100101 Firefox/154.0', NULL, '2026-08-31 13:32:44'),
(959, 1, 'admin@kosi-time.com', 'superadmin', 'update', 'Employe', 62, 'RAHARIMANANA ALEX', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:154.0) Gecko/20100101 Firefox/154.0', NULL, '2026-08-31 13:33:45'),
(960, 1, 'admin@kosi-time.com', 'superadmin', 'logout', 'Administration', 1, 'admin@kosi-time.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:154.0) Gecko/20100101 Firefox/154.0', NULL, '2026-08-31 14:24:53'),
(961, 1, 'admin@kosi-time.com', 'superadmin', 'logout', 'Administration', 1, 'admin@kosi-time.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:154.0) Gecko/20100101 Firefox/154.0', NULL, '2026-08-31 14:24:53'),
(962, 18, 'testautreadmin@gmail.com', 'simple_admin', 'login_success', 'Administration', 18, 'testautreadmin@gmail.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:154.0) Gecko/20100101 Firefox/154.0', 2, '2026-08-31 14:25:02'),
(963, 18, 'testautreadmin@gmail.com', 'simple_admin', 'login_success', 'Administration', 18, 'testautreadmin@gmail.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:154.0) Gecko/20100101 Firefox/154.0', 2, '2026-08-31 14:25:02'),
(964, 2, 'admin@geotrack.com', 'simple_admin', 'logout', 'Administration', 2, 'admin@geotrack.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', 1, '2026-08-31 15:22:38'),
(965, 2, 'admin@geotrack.com', 'simple_admin', 'logout', 'Administration', 2, 'admin@geotrack.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', 1, '2026-08-31 15:22:38'),
(966, 18, 'testautreadmin@gmail.com', 'simple_admin', 'logout', 'Administration', 18, 'testautreadmin@gmail.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:154.0) Gecko/20100101 Firefox/154.0', 2, '2026-08-31 15:22:43'),
(967, 18, 'testautreadmin@gmail.com', 'simple_admin', 'logout', 'Administration', 18, 'testautreadmin@gmail.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:154.0) Gecko/20100101 Firefox/154.0', 2, '2026-08-31 15:22:43'),
(968, NULL, 'unknown', NULL, 'login_success', 'Administration', 5, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:154.0) Gecko/20100101 Firefox/154.0', NULL, '2026-08-31 15:23:21'),
(969, NULL, 'unknown', NULL, 'login_success', 'Administration', 5, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:154.0) Gecko/20100101 Firefox/154.0', NULL, '2026-08-31 15:23:21'),
(970, 2, 'admin@geotrack.com', 'simple_admin', 'login_success', 'Administration', 2, 'admin@geotrack.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', 1, '2026-08-31 15:27:54');
INSERT INTO `activity_logs` (`id`, `user_id`, `user_email`, `user_role`, `action`, `model_type`, `model_id`, `model_label`, `description`, `ip_address`, `user_agent`, `SiegeID`, `created_at`) VALUES
(971, 2, 'admin@geotrack.com', 'simple_admin', 'login_success', 'Administration', 2, 'admin@geotrack.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', 1, '2026-08-31 15:27:54'),
(972, 2, 'admin@geotrack.com', 'simple_admin', 'update', 'Employe', 58, 'Emma solofo', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', 1, '2026-08-31 16:05:10'),
(973, 2, 'admin@geotrack.com', 'simple_admin', 'logout', 'Administration', 2, 'admin@geotrack.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', 1, '2026-08-31 16:22:52'),
(974, 2, 'admin@geotrack.com', 'simple_admin', 'logout', 'Administration', 2, 'admin@geotrack.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', 1, '2026-08-31 16:22:52'),
(975, 1, 'admin@kosi-time.com', 'superadmin', 'login_success', 'Administration', 1, 'admin@kosi-time.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:154.0) Gecko/20100101 Firefox/154.0', NULL, '2026-08-31 16:23:48'),
(976, 1, 'admin@kosi-time.com', 'superadmin', 'login_success', 'Administration', 1, 'admin@kosi-time.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:154.0) Gecko/20100101 Firefox/154.0', NULL, '2026-08-31 16:23:48'),
(977, NULL, 'admin@kosi-time.com', NULL, 'login_failed', NULL, NULL, NULL, 'Mot de passe incorrect', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', NULL, '2026-08-31 16:24:14'),
(978, 1, 'admin@kosi-time.com', 'superadmin', 'login_success', 'Administration', 1, 'admin@kosi-time.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', NULL, '2026-08-31 16:24:29'),
(979, 1, 'admin@kosi-time.com', 'superadmin', 'login_success', 'Administration', 1, 'admin@kosi-time.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', NULL, '2026-08-31 16:24:29'),
(980, NULL, 'malalanirinaemile2@gmail.com', NULL, 'login_failed', NULL, NULL, NULL, 'Identifiant introuvable', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:154.0) Gecko/20100101 Firefox/154.0', NULL, '2026-09-01 14:01:28'),
(981, NULL, 'unknown', NULL, 'login_success', 'Administration', 5, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:154.0) Gecko/20100101 Firefox/154.0', NULL, '2026-09-01 14:01:37'),
(982, NULL, 'unknown', NULL, 'login_success', 'Administration', 5, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:154.0) Gecko/20100101 Firefox/154.0', NULL, '2026-09-01 14:01:37'),
(983, 1, 'admin@kosi-time.com', 'superadmin', 'login_success', 'Administration', 1, 'admin@kosi-time.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', NULL, '2026-09-01 14:05:43'),
(984, 1, 'admin@kosi-time.com', 'superadmin', 'login_success', 'Administration', 1, 'admin@kosi-time.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', NULL, '2026-09-01 14:05:43'),
(985, 1, 'admin@kosi-time.com', 'superadmin', 'logout', 'Administration', 1, 'admin@kosi-time.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', NULL, '2026-09-01 14:10:20'),
(986, 1, 'admin@kosi-time.com', 'superadmin', 'logout', 'Administration', 1, 'admin@kosi-time.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', NULL, '2026-09-01 14:10:20'),
(987, 2, 'admin@geotrack.com', 'simple_admin', 'login_success', 'Administration', 2, 'admin@geotrack.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', 1, '2026-09-01 14:10:35'),
(988, 2, 'admin@geotrack.com', 'simple_admin', 'login_success', 'Administration', 2, 'admin@geotrack.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', 1, '2026-09-01 14:10:35'),
(989, 2, 'admin@geotrack.com', 'simple_admin', 'create', 'Department', 35, 'Service informatique 2.0', 'Service créé : Service informatique 2.0 (SRI)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', 1, '2026-09-01 15:10:47'),
(990, 2, 'admin@geotrack.com', 'simple_admin', 'create', 'JobTitle', 345, 'Directrice RH', 'Poste créé : Directrice RH (DRH GEO)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', 1, '2026-09-01 15:16:05'),
(991, 2, 'admin@geotrack.com', 'simple_admin', 'create', 'JobTitle', 346, 'Directrice RH FEMME', 'Poste créé : Directrice RH FEMME (DRF)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', 1, '2026-09-01 15:16:58'),
(992, 2, 'admin@geotrack.com', 'simple_admin', 'login_success', 'Administration', 2, 'admin@geotrack.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', 1, '2026-09-02 08:21:15'),
(993, 2, 'admin@geotrack.com', 'simple_admin', 'login_success', 'Administration', 2, 'admin@geotrack.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', 1, '2026-09-02 08:21:15'),
(994, 2, 'admin@geotrack.com', 'simple_admin', 'update', 'Department', 12, 'Ressources humaines', 'Service mis à jour : Ressources humaines', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', 1, '2026-09-02 08:25:29'),
(995, NULL, 'unknown', NULL, 'login_success', 'Administration', 5, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:154.0) Gecko/20100101 Firefox/154.0', NULL, '2026-09-02 08:55:34'),
(996, NULL, 'unknown', NULL, 'login_success', 'Administration', 5, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:154.0) Gecko/20100101 Firefox/154.0', NULL, '2026-09-02 08:55:34'),
(997, 2, 'admin@geotrack.com', 'simple_admin', 'create', 'JobTitle', 347, 'test NOUVEAU POSTE AVEC SERVICE', 'Poste créé : test NOUVEAU POSTE AVEC SERVICE (test PAS)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', 1, '2026-09-02 10:10:05'),
(998, 2, 'admin@geotrack.com', 'simple_admin', 'update', 'JobTitle', 347, 'test NOUVEAU POSTE AVEC SERVICE', 'Poste mis à jour : test NOUVEAU POSTE AVEC SERVICE', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', 1, '2026-09-02 10:54:16'),
(999, 2, 'admin@geotrack.com', 'simple_admin', 'update', 'Employe', 60, 'RAKOTONIRINA Manankasina', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', 1, '2026-09-02 10:56:16'),
(1000, NULL, 'unknown', NULL, 'login_success', 'Administration', 5, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:154.0) Gecko/20100101 Firefox/154.0', NULL, '2026-09-02 12:27:50'),
(1001, NULL, 'unknown', NULL, 'login_success', 'Administration', 5, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:154.0) Gecko/20100101 Firefox/154.0', NULL, '2026-09-02 12:27:50'),
(1002, 2, 'admin@geotrack.com', 'simple_admin', 'logout', 'Administration', 2, 'admin@geotrack.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', 1, '2026-09-02 13:18:48'),
(1003, 2, 'admin@geotrack.com', 'simple_admin', 'logout', 'Administration', 2, 'admin@geotrack.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', 1, '2026-09-02 13:18:48'),
(1004, 1, 'admin@kosi-time.com', 'superadmin', 'login_success', 'Administration', 1, 'admin@kosi-time.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', NULL, '2026-09-02 13:19:34'),
(1005, 1, 'admin@kosi-time.com', 'superadmin', 'login_success', 'Administration', 1, 'admin@kosi-time.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', NULL, '2026-09-02 13:19:34'),
(1006, 1, 'admin@kosi-time.com', 'superadmin', 'logout', 'Administration', 1, 'admin@kosi-time.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', NULL, '2026-09-02 14:28:11'),
(1007, 1, 'admin@kosi-time.com', 'superadmin', 'logout', 'Administration', 1, 'admin@kosi-time.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', NULL, '2026-09-02 14:28:11'),
(1008, 18, 'testautreadmin@gmail.com', 'simple_admin', 'login_success', 'Administration', 18, 'testautreadmin@gmail.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', 2, '2026-09-02 14:28:26'),
(1009, 18, 'testautreadmin@gmail.com', 'simple_admin', 'login_success', 'Administration', 18, 'testautreadmin@gmail.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', 2, '2026-09-02 14:28:26'),
(1010, NULL, 'unknown', NULL, 'login_success', 'Administration', 5, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:154.0) Gecko/20100101 Firefox/154.0', NULL, '2026-09-02 15:03:02'),
(1011, NULL, 'unknown', NULL, 'login_success', 'Administration', 5, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:154.0) Gecko/20100101 Firefox/154.0', NULL, '2026-09-02 15:03:02'),
(1012, 18, 'testautreadmin@gmail.com', 'simple_admin', 'logout', 'Administration', 18, 'testautreadmin@gmail.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', 2, '2026-09-02 15:12:05'),
(1013, 18, 'testautreadmin@gmail.com', 'simple_admin', 'logout', 'Administration', 18, 'testautreadmin@gmail.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', 2, '2026-09-02 15:12:05'),
(1014, 2, 'admin@geotrack.com', 'simple_admin', 'login_success', 'Administration', 2, 'admin@geotrack.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', 1, '2026-09-02 15:12:24'),
(1015, 2, 'admin@geotrack.com', 'simple_admin', 'login_success', 'Administration', 2, 'admin@geotrack.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', 1, '2026-09-02 15:12:24'),
(1016, 2, 'admin@geotrack.com', 'simple_admin', 'login_success', 'Administration', 2, 'admin@geotrack.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', 1, '2026-09-03 07:10:51'),
(1017, 2, 'admin@geotrack.com', 'simple_admin', 'login_success', 'Administration', 2, 'admin@geotrack.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', 1, '2026-09-03 07:10:51'),
(1018, NULL, 'unknown', NULL, 'login_success', 'Administration', 5, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:155.0) Gecko/20100101 Firefox/155.0', NULL, '2026-09-03 07:33:21'),
(1019, NULL, 'unknown', NULL, 'login_success', 'Administration', 5, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:155.0) Gecko/20100101 Firefox/155.0', NULL, '2026-09-03 07:33:21'),
(1020, 2, 'admin@geotrack.com', 'simple_admin', 'update', 'Employe', 58, 'Emma solofo', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', 1, '2026-09-03 08:19:36'),
(1021, 2, 'admin@geotrack.com', 'simple_admin', 'assign_web_access', 'Employe', 58, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', 1, '2026-09-03 08:19:36'),
(1022, 2, 'admin@geotrack.com', 'simple_admin', 'logout', 'Administration', 2, 'admin@geotrack.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', 1, '2026-09-03 08:20:24'),
(1023, 2, 'admin@geotrack.com', 'simple_admin', 'logout', 'Administration', 2, 'admin@geotrack.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', 1, '2026-09-03 08:20:24'),
(1024, NULL, 'unknown', NULL, 'login_success', 'Administration', 58, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', NULL, '2026-09-03 08:20:34'),
(1025, NULL, 'unknown', NULL, 'login_success', 'Administration', 58, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', NULL, '2026-09-03 08:20:34'),
(1026, 2, 'admin@geotrack.com', 'simple_admin', 'login_success', 'Administration', 2, 'admin@geotrack.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', 1, '2026-09-03 08:44:58'),
(1027, 2, 'admin@geotrack.com', 'simple_admin', 'login_success', 'Administration', 2, 'admin@geotrack.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', 1, '2026-09-03 08:44:58'),
(1028, 2, 'admin@geotrack.com', 'simple_admin', 'update', 'Employe', 5, 'MALALANIRINA Emile Noeline', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', 1, '2026-09-03 08:47:17'),
(1029, 2, 'admin@geotrack.com', 'simple_admin', 'update', 'Employe', 5, 'MALALANIRINA Emile Noeline', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', 1, '2026-09-03 08:50:00'),
(1030, 2, 'admin@geotrack.com', 'simple_admin', 'update', 'Employe', 3, 'Ramarotafika Hedi Franco', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', 1, '2026-09-03 08:53:58'),
(1031, 2, 'admin@geotrack.com', 'simple_admin', 'assign_web_access', 'Employe', 3, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', 1, '2026-09-03 08:53:58'),
(1032, 2, 'admin@geotrack.com', 'simple_admin', 'logout', 'Administration', 2, 'admin@geotrack.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', 1, '2026-09-03 08:54:15'),
(1033, 2, 'admin@geotrack.com', 'simple_admin', 'logout', 'Administration', 2, 'admin@geotrack.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', 1, '2026-09-03 08:54:15'),
(1034, NULL, 'unknown', NULL, 'login_success', 'Administration', 3, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', NULL, '2026-09-03 08:54:23'),
(1035, NULL, 'unknown', NULL, 'login_success', 'Administration', 3, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', NULL, '2026-09-03 08:54:23'),
(1036, 2, 'admin@geotrack.com', 'simple_admin', 'login_success', 'Administration', 2, 'admin@geotrack.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', 1, '2026-09-03 08:55:33'),
(1037, 2, 'admin@geotrack.com', 'simple_admin', 'login_success', 'Administration', 2, 'admin@geotrack.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', 1, '2026-09-03 08:55:33'),
(1038, 2, 'admin@geotrack.com', 'simple_admin', 'update', 'Employe', 2, 'Solofoniaina Vololonirina Emma Joeline', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', 1, '2026-09-03 08:57:14'),
(1039, 2, 'admin@geotrack.com', 'simple_admin', 'assign_web_access', 'Employe', 2, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', 1, '2026-09-03 08:57:14'),
(1040, 2, 'admin@geotrack.com', 'simple_admin', 'logout', 'Administration', 2, 'admin@geotrack.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', 1, '2026-09-03 08:57:44'),
(1041, 2, 'admin@geotrack.com', 'simple_admin', 'logout', 'Administration', 2, 'admin@geotrack.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', 1, '2026-09-03 08:57:44'),
(1042, NULL, 'unknown', NULL, 'login_success', 'Administration', 2, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', NULL, '2026-09-03 08:57:51'),
(1043, NULL, 'unknown', NULL, 'login_success', 'Administration', 2, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', NULL, '2026-09-03 08:57:51'),
(1044, 2, 'admin@geotrack.com', 'simple_admin', 'login_success', 'Administration', 2, 'admin@geotrack.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', 1, '2026-09-03 09:19:36'),
(1045, 2, 'admin@geotrack.com', 'simple_admin', 'login_success', 'Administration', 2, 'admin@geotrack.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', 1, '2026-09-03 09:19:36'),
(1046, 2, 'admin@geotrack.com', 'simple_admin', 'logout', 'Administration', 2, 'admin@geotrack.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', 1, '2026-09-03 09:25:48'),
(1047, 2, 'admin@geotrack.com', 'simple_admin', 'logout', 'Administration', 2, 'admin@geotrack.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', 1, '2026-09-03 09:25:48'),
(1048, NULL, 'unknown', NULL, 'login_success', 'Administration', 58, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', NULL, '2026-09-03 09:26:05'),
(1049, NULL, 'unknown', NULL, 'login_success', 'Administration', 58, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', NULL, '2026-09-03 09:26:05'),
(1050, 2, 'admin@geotrack.com', 'simple_admin', 'login_success', 'Administration', 2, 'admin@geotrack.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', 1, '2026-09-03 09:47:41'),
(1051, 2, 'admin@geotrack.com', 'simple_admin', 'login_success', 'Administration', 2, 'admin@geotrack.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', 1, '2026-09-03 09:47:41'),
(1052, 2, 'admin@geotrack.com', 'simple_admin', 'logout', 'Administration', 2, 'admin@geotrack.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', 1, '2026-09-03 10:03:07'),
(1053, 2, 'admin@geotrack.com', 'simple_admin', 'logout', 'Administration', 2, 'admin@geotrack.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', 1, '2026-09-03 10:03:07'),
(1054, NULL, 'unknown', NULL, 'login_success', 'Administration', 3, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', NULL, '2026-09-03 10:03:58'),
(1055, NULL, 'unknown', NULL, 'login_success', 'Administration', 3, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', NULL, '2026-09-03 10:03:58'),
(1056, NULL, 'Emmavalidator@gmail.com', NULL, 'login_failed', NULL, NULL, NULL, 'Identifiant introuvable', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', NULL, '2026-09-03 10:04:20'),
(1057, NULL, 'unknown', NULL, 'login_success', 'Administration', 58, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', NULL, '2026-09-03 10:05:54'),
(1058, NULL, 'unknown', NULL, 'login_success', 'Administration', 58, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', NULL, '2026-09-03 10:05:54'),
(1059, NULL, 'unknown', NULL, 'login_success', 'Administration', 58, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', NULL, '2026-09-03 10:06:30'),
(1060, NULL, 'unknown', NULL, 'login_success', 'Administration', 58, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', NULL, '2026-09-03 10:06:30'),
(1061, NULL, 'unknown', NULL, 'login_success', 'Administration', 58, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', NULL, '2026-09-03 10:09:59'),
(1062, NULL, 'unknown', NULL, 'login_success', 'Administration', 58, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', NULL, '2026-09-03 10:09:59'),
(1063, 2, 'admin@geotrack.com', 'simple_admin', 'login_success', 'Administration', 2, 'admin@geotrack.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', 1, '2026-09-03 10:34:22'),
(1064, 2, 'admin@geotrack.com', 'simple_admin', 'login_success', 'Administration', 2, 'admin@geotrack.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', 1, '2026-09-03 10:34:22'),
(1065, 2, 'admin@geotrack.com', 'simple_admin', 'logout', 'Administration', 2, 'admin@geotrack.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', 1, '2026-09-03 12:03:18'),
(1066, 2, 'admin@geotrack.com', 'simple_admin', 'logout', 'Administration', 2, 'admin@geotrack.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', 1, '2026-09-03 12:03:18'),
(1067, NULL, 'unknown', NULL, 'login_success', 'Administration', 58, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', NULL, '2026-09-03 12:03:30'),
(1068, NULL, 'unknown', NULL, 'login_success', 'Administration', 58, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', NULL, '2026-09-03 12:03:30'),
(1069, 2, 'admin@geotrack.com', 'simple_admin', 'login_success', 'Administration', 2, 'admin@geotrack.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', 1, '2026-09-03 12:17:43'),
(1070, 2, 'admin@geotrack.com', 'simple_admin', 'login_success', 'Administration', 2, 'admin@geotrack.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', 1, '2026-09-03 12:17:43'),
(1071, 2, 'admin@geotrack.com', 'simple_admin', 'logout', 'Administration', 2, 'admin@geotrack.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', 1, '2026-09-03 12:49:41'),
(1072, 2, 'admin@geotrack.com', 'simple_admin', 'logout', 'Administration', 2, 'admin@geotrack.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', 1, '2026-09-03 12:49:41'),
(1073, NULL, 'unknown', NULL, 'update', 'Employe', 3, 'Ramarotafika Hedi Franco', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', NULL, '2026-09-03 12:49:56'),
(1074, NULL, 'unknown', NULL, 'login_success', 'Administration', 3, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', NULL, '2026-09-03 12:49:56'),
(1075, NULL, 'unknown', NULL, 'login_success', 'Administration', 3, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', NULL, '2026-09-03 12:49:56'),
(1076, 2, 'admin@geotrack.com', 'simple_admin', 'login_success', 'Administration', 2, 'admin@geotrack.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', 1, '2026-09-03 12:59:32'),
(1077, 2, 'admin@geotrack.com', 'simple_admin', 'login_success', 'Administration', 2, 'admin@geotrack.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', 1, '2026-09-03 12:59:32'),
(1078, 2, 'admin@geotrack.com', 'simple_admin', 'update', 'Employe', 2, 'Solofoniaina Vololonirina Emma Joeline', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', 1, '2026-09-03 14:12:11'),
(1079, NULL, 'unknown', NULL, 'login_success', 'Administration', 2, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', NULL, '2026-09-03 14:15:29'),
(1080, NULL, 'unknown', NULL, 'login_success', 'Administration', 2, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', NULL, '2026-09-03 14:15:29'),
(1081, 2, 'admin@geotrack.com', 'simple_admin', 'logout', 'Administration', 2, 'admin@geotrack.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', 1, '2026-09-03 14:16:03'),
(1082, 2, 'admin@geotrack.com', 'simple_admin', 'logout', 'Administration', 2, 'admin@geotrack.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', 1, '2026-09-03 14:16:03'),
(1083, NULL, 'unknown', NULL, 'login_success', 'Administration', 3, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', NULL, '2026-09-03 14:16:06'),
(1084, NULL, 'unknown', NULL, 'login_success', 'Administration', 3, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', NULL, '2026-09-03 14:16:06'),
(1085, NULL, 'unknown', NULL, 'login_success', 'Administration', 2, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36 Edg/152.0.0.0', NULL, '2026-09-03 14:17:02'),
(1086, NULL, 'unknown', NULL, 'login_success', 'Administration', 2, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36 Edg/152.0.0.0', NULL, '2026-09-03 14:17:02'),
(1087, NULL, 'Emmavalidator@gmail.com', NULL, 'login_failed', NULL, NULL, NULL, 'Identifiant introuvable', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', NULL, '2026-09-03 14:21:02'),
(1088, NULL, 'Emmavalidator@gmail.com', NULL, 'login_failed', NULL, NULL, NULL, 'Identifiant introuvable', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36 Edg/152.0.0.0', NULL, '2026-09-03 14:21:28'),
(1089, NULL, 'unknown', NULL, 'login_success', 'Administration', 58, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', NULL, '2026-09-03 14:24:01'),
(1090, NULL, 'unknown', NULL, 'login_success', 'Administration', 58, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', NULL, '2026-09-03 14:24:01'),
(1091, NULL, 'unknown', NULL, 'login_success', 'Administration', 58, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', NULL, '2026-09-03 14:24:38'),
(1092, NULL, 'unknown', NULL, 'login_success', 'Administration', 58, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', NULL, '2026-09-03 14:24:38'),
(1093, NULL, 'EmmavalidatorDRH@gmail.com', NULL, 'login_failed', NULL, NULL, NULL, 'Identifiant introuvable', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36 Edg/152.0.0.0', NULL, '2026-09-03 14:25:53'),
(1094, 2, 'admin@geotrack.com', 'simple_admin', 'login_success', 'Administration', 2, 'admin@geotrack.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:155.0) Gecko/20100101 Firefox/155.0', 1, '2026-09-03 14:27:26'),
(1095, 2, 'admin@geotrack.com', 'simple_admin', 'login_success', 'Administration', 2, 'admin@geotrack.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:155.0) Gecko/20100101 Firefox/155.0', 1, '2026-09-03 14:27:26'),
(1096, 2, 'admin@geotrack.com', 'simple_admin', 'login_success', 'Administration', 2, 'admin@geotrack.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', 1, '2026-09-03 14:27:46'),
(1097, 2, 'admin@geotrack.com', 'simple_admin', 'login_success', 'Administration', 2, 'admin@geotrack.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', 1, '2026-09-03 14:27:46'),
(1098, 2, 'admin@geotrack.com', 'simple_admin', 'logout', 'Administration', 2, 'admin@geotrack.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', 1, '2026-09-03 14:41:34'),
(1099, 2, 'admin@geotrack.com', 'simple_admin', 'logout', 'Administration', 2, 'admin@geotrack.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', 1, '2026-09-03 14:41:34'),
(1100, NULL, 'unknown', NULL, 'login_success', 'Administration', 3, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', NULL, '2026-09-03 14:41:44'),
(1101, NULL, 'unknown', NULL, 'login_success', 'Administration', 3, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', NULL, '2026-09-03 14:41:44'),
(1102, 2, 'admin@geotrack.com', 'simple_admin', 'logout', 'Administration', 2, 'admin@geotrack.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:155.0) Gecko/20100101 Firefox/155.0', 1, '2026-09-03 14:43:38'),
(1103, 2, 'admin@geotrack.com', 'simple_admin', 'logout', 'Administration', 2, 'admin@geotrack.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:155.0) Gecko/20100101 Firefox/155.0', 1, '2026-09-03 14:43:38'),
(1104, NULL, 'unknown', NULL, 'login_success', 'Administration', 58, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:155.0) Gecko/20100101 Firefox/155.0', NULL, '2026-09-03 14:44:23'),
(1105, NULL, 'unknown', NULL, 'login_success', 'Administration', 58, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:155.0) Gecko/20100101 Firefox/155.0', NULL, '2026-09-03 14:44:23'),
(1106, 2, 'admin@geotrack.com', 'simple_admin', 'login_success', 'Administration', 2, 'admin@geotrack.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', 1, '2026-09-03 14:45:30'),
(1107, 2, 'admin@geotrack.com', 'simple_admin', 'login_success', 'Administration', 2, 'admin@geotrack.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', 1, '2026-09-03 14:45:30'),
(1108, NULL, 'admin@geotrack.com', NULL, 'login_failed', NULL, NULL, NULL, 'Mot de passe incorrect', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', NULL, '2026-09-03 14:47:07'),
(1109, 2, 'admin@geotrack.com', 'simple_admin', 'login_success', 'Administration', 2, 'admin@geotrack.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:155.0) Gecko/20100101 Firefox/155.0', 1, '2026-09-03 14:47:22'),
(1110, 2, 'admin@geotrack.com', 'simple_admin', 'login_success', 'Administration', 2, 'admin@geotrack.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:155.0) Gecko/20100101 Firefox/155.0', 1, '2026-09-03 14:47:22'),
(1111, NULL, 'unknown', NULL, 'login_success', 'Administration', 3, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', NULL, '2026-09-03 15:03:48'),
(1112, NULL, 'unknown', NULL, 'login_success', 'Administration', 3, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', NULL, '2026-09-03 15:03:48'),
(1113, NULL, 'admin@geotrack.com', NULL, 'login_failed', NULL, NULL, NULL, 'Mot de passe incorrect', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', NULL, '2026-09-03 15:05:51'),
(1114, NULL, 'admin@geotrack.com', NULL, 'login_failed', NULL, NULL, NULL, 'Mot de passe incorrect', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', NULL, '2026-09-03 15:05:56'),
(1115, NULL, 'admin@geotrack.com', NULL, 'login_failed', NULL, NULL, NULL, 'Mot de passe incorrect', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', NULL, '2026-09-03 15:06:25'),
(1116, NULL, 'admin@geotrack.com', NULL, 'login_failed', NULL, NULL, NULL, 'Mot de passe incorrect', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', NULL, '2026-09-03 15:06:48'),
(1117, NULL, 'admin@geotrack.com', NULL, 'login_failed', NULL, NULL, NULL, 'Mot de passe incorrect', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', NULL, '2026-09-03 15:31:47'),
(1118, 2, 'admin@geotrack.com', 'simple_admin', 'login_success', 'Administration', 2, 'admin@geotrack.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', 1, '2026-09-04 07:37:54'),
(1119, 2, 'admin@geotrack.com', 'simple_admin', 'login_success', 'Administration', 2, 'admin@geotrack.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', 1, '2026-09-04 07:37:54'),
(1120, NULL, 'unknown', NULL, 'login_success', 'Administration', 2, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:155.0) Gecko/20100101 Firefox/155.0', NULL, '2026-09-04 07:51:14'),
(1121, NULL, 'unknown', NULL, 'login_success', 'Administration', 2, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:155.0) Gecko/20100101 Firefox/155.0', NULL, '2026-09-04 07:51:14'),
(1122, NULL, 'EmmavalidatorDRH@gmail.com', NULL, 'login_failed', NULL, NULL, NULL, 'Identifiant introuvable', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36 Edg/152.0.0.0', NULL, '2026-09-04 07:52:32'),
(1123, NULL, 'unknown', NULL, 'login_success', 'Administration', 2, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36 Edg/152.0.0.0', NULL, '2026-09-04 07:52:38'),
(1124, NULL, 'unknown', NULL, 'login_success', 'Administration', 2, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36 Edg/152.0.0.0', NULL, '2026-09-04 07:52:38'),
(1125, NULL, 'unknown', NULL, 'login_success', 'Administration', 3, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', NULL, '2026-09-04 07:55:02'),
(1126, NULL, 'unknown', NULL, 'login_success', 'Administration', 3, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', NULL, '2026-09-04 07:55:02'),
(1127, 2, 'admin@geotrack.com', 'simple_admin', 'logout', 'Administration', 2, 'admin@geotrack.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', 1, '2026-09-04 07:57:29'),
(1128, 2, 'admin@geotrack.com', 'simple_admin', 'logout', 'Administration', 2, 'admin@geotrack.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', 1, '2026-09-04 07:57:29'),
(1129, NULL, 'unknown', NULL, 'login_success', 'Administration', 58, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', NULL, '2026-09-04 07:57:42'),
(1130, NULL, 'unknown', NULL, 'login_success', 'Administration', 58, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', NULL, '2026-09-04 07:57:42'),
(1131, 2, 'admin@geotrack.com', 'simple_admin', 'login_success', 'Administration', 2, 'admin@geotrack.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:155.0) Gecko/20100101 Firefox/155.0', 1, '2026-09-04 08:02:40'),
(1132, 2, 'admin@geotrack.com', 'simple_admin', 'login_success', 'Administration', 2, 'admin@geotrack.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:155.0) Gecko/20100101 Firefox/155.0', 1, '2026-09-04 08:02:40'),
(1133, 2, 'admin@geotrack.com', 'simple_admin', 'logout', 'Administration', 2, 'admin@geotrack.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:155.0) Gecko/20100101 Firefox/155.0', 1, '2026-09-04 08:03:02'),
(1134, 2, 'admin@geotrack.com', 'simple_admin', 'logout', 'Administration', 2, 'admin@geotrack.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:155.0) Gecko/20100101 Firefox/155.0', 1, '2026-09-04 08:03:02'),
(1135, 2, 'admin@geotrack.com', 'simple_admin', 'login_success', 'Administration', 2, 'admin@geotrack.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:155.0) Gecko/20100101 Firefox/155.0', 1, '2026-09-04 08:03:06'),
(1136, 2, 'admin@geotrack.com', 'simple_admin', 'login_success', 'Administration', 2, 'admin@geotrack.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:155.0) Gecko/20100101 Firefox/155.0', 1, '2026-09-04 08:03:06'),
(1137, 2, 'admin@geotrack.com', 'simple_admin', 'logout', 'Administration', 2, 'admin@geotrack.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:155.0) Gecko/20100101 Firefox/155.0', 1, '2026-09-04 09:47:10'),
(1138, 2, 'admin@geotrack.com', 'simple_admin', 'logout', 'Administration', 2, 'admin@geotrack.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:155.0) Gecko/20100101 Firefox/155.0', 1, '2026-09-04 09:47:10'),
(1139, 18, 'testautreadmin@gmail.com', 'simple_admin', 'login_success', 'Administration', 18, 'testautreadmin@gmail.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', 2, '2026-09-04 09:48:18'),
(1140, 18, 'testautreadmin@gmail.com', 'simple_admin', 'login_success', 'Administration', 18, 'testautreadmin@gmail.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', 2, '2026-09-04 09:48:18'),
(1141, 2, 'admin@geotrack.com', 'simple_admin', 'login_success', 'Administration', 2, 'admin@geotrack.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:155.0) Gecko/20100101 Firefox/155.0', 1, '2026-09-04 09:50:30'),
(1142, 2, 'admin@geotrack.com', 'simple_admin', 'login_success', 'Administration', 2, 'admin@geotrack.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:155.0) Gecko/20100101 Firefox/155.0', 1, '2026-09-04 09:50:30'),
(1143, 18, 'testautreadmin@gmail.com', 'simple_admin', 'logout', 'Administration', 18, 'testautreadmin@gmail.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', 2, '2026-09-04 10:41:13'),
(1144, 18, 'testautreadmin@gmail.com', 'simple_admin', 'logout', 'Administration', 18, 'testautreadmin@gmail.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', 2, '2026-09-04 10:41:13'),
(1145, 1, 'admin@kosi-time.com', 'superadmin', 'login_success', 'Administration', 1, 'admin@kosi-time.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', NULL, '2026-09-04 10:41:30'),
(1146, 1, 'admin@kosi-time.com', 'superadmin', 'login_success', 'Administration', 1, 'admin@kosi-time.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', NULL, '2026-09-04 10:41:30'),
(1147, 1, 'admin@kosi-time.com', 'superadmin', 'create', 'EntrepriseSiege', 12, 'Entreprise A', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', NULL, '2026-09-04 10:44:05'),
(1148, 1, 'admin@kosi-time.com', 'superadmin', 'create', 'Entreprise', 33, 'SIEGE ENTREPRISE A', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', NULL, '2026-09-04 10:45:32'),
(1149, 1, 'admin@kosi-time.com', 'superadmin', 'create', 'Administration', 19, 'entreprisenouveau@gmail.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', NULL, '2026-09-04 10:47:54'),
(1150, 1, 'admin@kosi-time.com', 'superadmin', 'logout', 'Administration', 1, 'admin@kosi-time.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', NULL, '2026-09-04 10:48:03'),
(1151, 1, 'admin@kosi-time.com', 'superadmin', 'logout', 'Administration', 1, 'admin@kosi-time.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', NULL, '2026-09-04 10:48:03'),
(1152, 19, 'entreprisenouveau@gmail.com', 'simple_admin', 'login_success', 'Administration', 19, 'entreprisenouveau@gmail.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', 12, '2026-09-04 10:48:10'),
(1153, 19, 'entreprisenouveau@gmail.com', 'simple_admin', 'login_success', 'Administration', 19, 'entreprisenouveau@gmail.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', 12, '2026-09-04 10:48:10'),
(1154, 2, 'admin@geotrack.com', 'simple_admin', 'create', 'JobTitle', 348, 'Stagiaire Dev Web', 'Poste créé : Stagiaire Dev Web (STG)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:155.0) Gecko/20100101 Firefox/155.0', 1, '2026-09-04 10:50:51'),
(1155, 2, 'admin@geotrack.com', 'simple_admin', 'create', 'Employe', 66, 'MALALANIRINA Emile', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:155.0) Gecko/20100101 Firefox/155.0', 1, '2026-09-04 10:52:46'),
(1156, 19, 'entreprisenouveau@gmail.com', 'simple_admin', 'logout', 'Administration', 19, 'entreprisenouveau@gmail.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', 12, '2026-09-04 12:12:28'),
(1157, 19, 'entreprisenouveau@gmail.com', 'simple_admin', 'logout', 'Administration', 19, 'entreprisenouveau@gmail.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', 12, '2026-09-04 12:12:28'),
(1158, 1, 'admin@kosi-time.com', 'superadmin', 'login_success', 'Administration', 1, 'admin@kosi-time.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', NULL, '2026-09-04 12:16:56'),
(1159, 1, 'admin@kosi-time.com', 'superadmin', 'login_success', 'Administration', 1, 'admin@kosi-time.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', NULL, '2026-09-04 12:16:56'),
(1160, 1, 'admin@kosi-time.com', 'superadmin', 'update', 'Employe', 66, 'MALALANIRINA Emile', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', NULL, '2026-09-04 12:17:28'),
(1161, 1, 'admin@kosi-time.com', 'superadmin', 'logout', 'Administration', 1, 'admin@kosi-time.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', NULL, '2026-09-04 12:19:26'),
(1162, 1, 'admin@kosi-time.com', 'superadmin', 'logout', 'Administration', 1, 'admin@kosi-time.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', NULL, '2026-09-04 12:19:26'),
(1163, 19, 'entreprisenouveau@gmail.com', 'simple_admin', 'login_success', 'Administration', 19, 'entreprisenouveau@gmail.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', 12, '2026-09-04 12:20:29'),
(1164, 19, 'entreprisenouveau@gmail.com', 'simple_admin', 'login_success', 'Administration', 19, 'entreprisenouveau@gmail.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', 12, '2026-09-04 12:20:29'),
(1165, 2, 'admin@geotrack.com', 'simple_admin', 'create', 'JobTitle', 349, 'Responsable RH', 'Poste créé : Responsable RH (RRH)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:155.0) Gecko/20100101 Firefox/155.0', 1, '2026-09-04 12:24:11'),
(1166, 2, 'admin@geotrack.com', 'simple_admin', 'create', 'Employe', 67, 'Emma Solofo', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:155.0) Gecko/20100101 Firefox/155.0', 1, '2026-09-04 12:25:13');
INSERT INTO `activity_logs` (`id`, `user_id`, `user_email`, `user_role`, `action`, `model_type`, `model_id`, `model_label`, `description`, `ip_address`, `user_agent`, `SiegeID`, `created_at`) VALUES
(1167, 2, 'admin@geotrack.com', 'simple_admin', 'create', 'JobTitle', 350, 'Lead Dev', 'Poste créé : Lead Dev (LDEV)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:155.0) Gecko/20100101 Firefox/155.0', 1, '2026-09-04 12:26:26'),
(1168, 19, 'entreprisenouveau@gmail.com', 'simple_admin', 'logout', 'Administration', 19, 'entreprisenouveau@gmail.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', 12, '2026-09-04 12:27:52'),
(1169, 19, 'entreprisenouveau@gmail.com', 'simple_admin', 'logout', 'Administration', 19, 'entreprisenouveau@gmail.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', 12, '2026-09-04 12:27:52'),
(1170, 1, 'admin@kosi-time.com', 'superadmin', 'login_success', 'Administration', 1, 'admin@kosi-time.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', NULL, '2026-09-04 12:28:08'),
(1171, 1, 'admin@kosi-time.com', 'superadmin', 'login_success', 'Administration', 1, 'admin@kosi-time.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', NULL, '2026-09-04 12:28:08'),
(1172, 1, 'admin@kosi-time.com', 'superadmin', 'update', 'Employe', 67, 'Emma Solofo', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', NULL, '2026-09-04 12:28:36'),
(1173, 2, 'admin@geotrack.com', 'simple_admin', 'create', 'Employe', 68, 'Franco Hedi', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:155.0) Gecko/20100101 Firefox/155.0', 1, '2026-09-04 12:30:01'),
(1174, 1, 'admin@kosi-time.com', 'superadmin', 'update', 'Employe', 68, 'Franco Hedi', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', NULL, '2026-09-04 12:30:38'),
(1175, 2, 'admin@geotrack.com', 'simple_admin', 'create', 'JobTitle', 351, 'Directeur G', 'Poste créé : Directeur G (DG)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:155.0) Gecko/20100101 Firefox/155.0', 1, '2026-09-04 12:32:15'),
(1176, 2, 'admin@geotrack.com', 'simple_admin', 'create', 'Employe', 69, 'TARKIN WILLY', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:155.0) Gecko/20100101 Firefox/155.0', 1, '2026-09-04 12:33:10'),
(1177, 1, 'admin@kosi-time.com', 'superadmin', 'update', 'Employe', 69, 'TARKIN WILLY', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', NULL, '2026-09-04 12:33:38'),
(1178, 2, 'admin@geotrack.com', 'simple_admin', 'update', 'Employe', 67, 'Emma Solofo', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:155.0) Gecko/20100101 Firefox/155.0', 1, '2026-09-04 12:34:05'),
(1179, 2, 'admin@geotrack.com', 'simple_admin', 'update', 'Employe', 66, 'MALALANIRINA Emile', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:155.0) Gecko/20100101 Firefox/155.0', 1, '2026-09-04 12:34:54'),
(1180, 1, 'admin@kosi-time.com', 'superadmin', 'login_success', 'Administration', 67, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', NULL, '2026-09-04 12:51:25'),
(1181, 1, 'admin@kosi-time.com', 'superadmin', 'login_success', 'Administration', 67, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', NULL, '2026-09-04 12:51:25'),
(1182, 1, 'admin@kosi-time.com', 'superadmin', 'update', 'Employe', 67, 'Emma Solofo', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', NULL, '2026-09-04 12:52:42'),
(1183, 1, 'admin@kosi-time.com', 'superadmin', 'assign_web_access', 'Employe', 67, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', NULL, '2026-09-04 12:52:42'),
(1184, 1, 'admin@kosi-time.com', 'superadmin', 'update', 'Employe', 68, 'Franco Hedi', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', NULL, '2026-09-04 12:53:01'),
(1185, 1, 'admin@kosi-time.com', 'superadmin', 'assign_web_access', 'Employe', 68, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', NULL, '2026-09-04 12:53:01'),
(1186, 1, 'admin@kosi-time.com', 'superadmin', 'update', 'Employe', 66, 'MALALANIRINA Emile', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', NULL, '2026-09-04 12:53:13'),
(1187, 1, 'admin@kosi-time.com', 'superadmin', 'assign_web_access', 'Employe', 66, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', NULL, '2026-09-04 12:53:13'),
(1188, 1, 'admin@kosi-time.com', 'superadmin', 'update', 'Employe', 69, 'TARKIN WILLY', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', NULL, '2026-09-04 12:53:37'),
(1189, 1, 'admin@kosi-time.com', 'superadmin', 'assign_web_access', 'Employe', 69, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', NULL, '2026-09-04 12:53:37'),
(1190, 1, 'admin@kosi-time.com', 'superadmin', 'logout', 'Administration', 1, 'admin@kosi-time.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', NULL, '2026-09-04 13:01:26'),
(1191, 1, 'admin@kosi-time.com', 'superadmin', 'logout', 'Administration', 1, 'admin@kosi-time.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', NULL, '2026-09-04 13:01:26'),
(1192, NULL, 'unknown', NULL, 'login_success', 'Administration', 68, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', NULL, '2026-09-04 13:01:38'),
(1193, NULL, 'unknown', NULL, 'login_success', 'Administration', 68, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', NULL, '2026-09-04 13:01:38'),
(1194, 2, 'admin@geotrack.com', 'simple_admin', 'logout', 'Administration', 2, 'admin@geotrack.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:155.0) Gecko/20100101 Firefox/155.0', 1, '2026-09-04 13:01:58'),
(1195, 2, 'admin@geotrack.com', 'simple_admin', 'logout', 'Administration', 2, 'admin@geotrack.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:155.0) Gecko/20100101 Firefox/155.0', 1, '2026-09-04 13:01:58'),
(1196, NULL, 'unknown', NULL, 'login_success', 'Administration', 67, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:155.0) Gecko/20100101 Firefox/155.0', NULL, '2026-09-04 13:02:22'),
(1197, NULL, 'unknown', NULL, 'login_success', 'Administration', 67, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:155.0) Gecko/20100101 Firefox/155.0', NULL, '2026-09-04 13:02:23'),
(1198, NULL, 'unknown', NULL, 'login_success', 'Administration', 69, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36 Edg/152.0.0.0', NULL, '2026-09-04 13:04:58'),
(1199, NULL, 'unknown', NULL, 'login_success', 'Administration', 69, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36 Edg/152.0.0.0', NULL, '2026-09-04 13:04:58'),
(1200, NULL, 'EmmavalidatorDRH@gmail.com', NULL, 'login_failed', NULL, NULL, NULL, 'Identifiant introuvable', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36 Edg/152.0.0.0', NULL, '2026-09-04 13:05:27'),
(1201, NULL, 'ceo@gmail.com', NULL, 'login_failed', NULL, NULL, NULL, 'Identifiant introuvable', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36 Edg/152.0.0.0', NULL, '2026-09-04 13:05:50'),
(1202, NULL, 'ceo@gmail.com', NULL, 'login_failed', NULL, NULL, NULL, 'Identifiant introuvable', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36 Edg/152.0.0.0', NULL, '2026-09-04 13:05:56'),
(1203, 2, 'admin@geotrack.com', 'simple_admin', 'login_success', 'Administration', 2, 'admin@geotrack.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:155.0) Gecko/20100101 Firefox/155.0', 1, '2026-09-04 13:07:31'),
(1204, 2, 'admin@geotrack.com', 'simple_admin', 'login_success', 'Administration', 2, 'admin@geotrack.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:155.0) Gecko/20100101 Firefox/155.0', 1, '2026-09-04 13:07:31'),
(1205, NULL, 'unknown', NULL, 'login_success', 'Administration', 69, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', NULL, '2026-09-04 13:08:49'),
(1206, NULL, 'unknown', NULL, 'login_success', 'Administration', 69, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', NULL, '2026-09-04 13:08:49'),
(1207, 2, 'admin@geotrack.com', 'simple_admin', 'logout', 'Administration', 2, 'admin@geotrack.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:155.0) Gecko/20100101 Firefox/155.0', 1, '2026-09-04 13:46:43'),
(1208, 2, 'admin@geotrack.com', 'simple_admin', 'logout', 'Administration', 2, 'admin@geotrack.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:155.0) Gecko/20100101 Firefox/155.0', 1, '2026-09-04 13:46:43'),
(1209, NULL, 'unknown', NULL, 'login_success', 'Administration', 67, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:155.0) Gecko/20100101 Firefox/155.0', NULL, '2026-09-04 13:52:57'),
(1210, NULL, 'unknown', NULL, 'login_success', 'Administration', 67, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:155.0) Gecko/20100101 Firefox/155.0', NULL, '2026-09-04 13:52:57'),
(1211, 2, 'admin@geotrack.com', 'simple_admin', 'login_success', 'Administration', 2, 'admin@geotrack.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:155.0) Gecko/20100101 Firefox/155.0', 1, '2026-09-04 14:44:40'),
(1212, 2, 'admin@geotrack.com', 'simple_admin', 'login_success', 'Administration', 2, 'admin@geotrack.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:155.0) Gecko/20100101 Firefox/155.0', 1, '2026-09-04 14:44:40'),
(1213, 2, 'admin@geotrack.com', 'simple_admin', 'login_success', 'Administration', 2, 'admin@geotrack.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:155.0) Gecko/20100101 Firefox/155.0', 1, '2026-09-08 08:12:38'),
(1214, 2, 'admin@geotrack.com', 'simple_admin', 'login_success', 'Administration', 2, 'admin@geotrack.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:155.0) Gecko/20100101 Firefox/155.0', 1, '2026-09-08 08:12:38'),
(1215, 2, 'admin@geotrack.com', 'simple_admin', 'create', 'Department', 36, 'nouveau service', 'Service créé : nouveau service (nvs)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:155.0) Gecko/20100101 Firefox/155.0', 1, '2026-09-08 08:19:41'),
(1216, NULL, 'unknown', NULL, 'login_success', 'Administration', 66, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', NULL, '2026-09-08 08:28:43'),
(1217, NULL, 'unknown', NULL, 'login_success', 'Administration', 66, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', NULL, '2026-09-08 08:28:43'),
(1218, NULL, 'unknown', NULL, 'login_success', 'Administration', 69, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36 Edg/152.0.0.0', NULL, '2026-09-08 08:31:29'),
(1219, NULL, 'unknown', NULL, 'login_success', 'Administration', 69, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36 Edg/152.0.0.0', NULL, '2026-09-08 08:31:29'),
(1220, 2, 'admin@geotrack.com', 'simple_admin', 'login_success', 'Administration', 66, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:155.0) Gecko/20100101 Firefox/155.0', 1, '2026-09-08 08:44:43'),
(1221, 2, 'admin@geotrack.com', 'simple_admin', 'login_success', 'Administration', 66, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:155.0) Gecko/20100101 Firefox/155.0', 1, '2026-09-08 08:44:43'),
(1222, 2, 'admin@geotrack.com', 'simple_admin', 'logout', 'Administration', 2, 'admin@geotrack.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:155.0) Gecko/20100101 Firefox/155.0', 1, '2026-09-08 08:44:56'),
(1223, 2, 'admin@geotrack.com', 'simple_admin', 'logout', 'Administration', 2, 'admin@geotrack.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:155.0) Gecko/20100101 Firefox/155.0', 1, '2026-09-08 08:44:56'),
(1224, NULL, 'unknown', NULL, 'login_success', 'Administration', 66, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:155.0) Gecko/20100101 Firefox/155.0', NULL, '2026-09-08 08:46:24'),
(1225, NULL, 'unknown', NULL, 'login_success', 'Administration', 66, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:155.0) Gecko/20100101 Firefox/155.0', NULL, '2026-09-08 08:46:24'),
(1226, 2, 'admin@geotrack.com', 'simple_admin', 'login_success', 'Administration', 2, 'admin@geotrack.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:155.0) Gecko/20100101 Firefox/155.0', 1, '2026-09-08 10:27:44'),
(1227, 2, 'admin@geotrack.com', 'simple_admin', 'login_success', 'Administration', 2, 'admin@geotrack.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:155.0) Gecko/20100101 Firefox/155.0', 1, '2026-09-08 10:27:44'),
(1228, 2, 'admin@geotrack.com', 'simple_admin', 'create', 'HierarchyLevel', 16, 'CEOoc', 'Création du niveau hiérarchique : CEOoc', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:155.0) Gecko/20100101 Firefox/155.0', 1, '2026-09-08 10:53:24'),
(1229, 2, 'admin@geotrack.com', 'simple_admin', 'logout', 'Administration', 2, 'admin@geotrack.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:155.0) Gecko/20100101 Firefox/155.0', 1, '2026-09-08 15:09:49'),
(1230, 2, 'admin@geotrack.com', 'simple_admin', 'logout', 'Administration', 2, 'admin@geotrack.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:155.0) Gecko/20100101 Firefox/155.0', 1, '2026-09-08 15:09:49'),
(1231, NULL, 'unknown', NULL, 'login_success', 'Administration', 66, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:155.0) Gecko/20100101 Firefox/155.0', NULL, '2026-09-08 15:10:08'),
(1232, NULL, 'unknown', NULL, 'login_success', 'Administration', 66, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:155.0) Gecko/20100101 Firefox/155.0', NULL, '2026-09-08 15:10:08'),
(1233, 2, 'admin@geotrack.com', 'simple_admin', 'login_success', 'Administration', 2, 'admin@geotrack.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', 1, '2026-09-08 15:12:24'),
(1234, 2, 'admin@geotrack.com', 'simple_admin', 'login_success', 'Administration', 2, 'admin@geotrack.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', 1, '2026-09-08 15:12:24'),
(1235, NULL, 'unknown', NULL, 'login_success', 'Administration', 69, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36 Edg/152.0.0.0', NULL, '2026-09-08 15:16:52'),
(1236, NULL, 'unknown', NULL, 'login_success', 'Administration', 69, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36 Edg/152.0.0.0', NULL, '2026-09-08 15:16:52'),
(1237, 2, 'admin@geotrack.com', 'simple_admin', 'login_success', 'Administration', 68, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', 1, '2026-09-08 15:17:25'),
(1238, 2, 'admin@geotrack.com', 'simple_admin', 'login_success', 'Administration', 68, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', 1, '2026-09-08 15:17:25'),
(1239, 2, 'admin@geotrack.com', 'simple_admin', 'logout', 'Administration', 2, 'admin@geotrack.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', 1, '2026-09-08 15:19:45'),
(1240, 2, 'admin@geotrack.com', 'simple_admin', 'logout', 'Administration', 2, 'admin@geotrack.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', 1, '2026-09-08 15:19:45'),
(1241, NULL, 'unknown', NULL, 'login_success', 'Administration', 67, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', NULL, '2026-09-08 15:20:23'),
(1242, NULL, 'unknown', NULL, 'login_success', 'Administration', 67, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', NULL, '2026-09-08 15:20:23'),
(1243, 2, 'admin@geotrack.com', 'simple_admin', 'login_success', 'Administration', 2, 'admin@geotrack.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:155.0) Gecko/20100101 Firefox/155.0', 1, '2026-09-08 15:21:40'),
(1244, 2, 'admin@geotrack.com', 'simple_admin', 'login_success', 'Administration', 2, 'admin@geotrack.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:155.0) Gecko/20100101 Firefox/155.0', 1, '2026-09-08 15:21:40'),
(1245, 2, 'admin@geotrack.com', 'simple_admin', 'login_success', 'Administration', 2, 'admin@geotrack.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', 1, '2026-09-08 15:22:36'),
(1246, 2, 'admin@geotrack.com', 'simple_admin', 'login_success', 'Administration', 2, 'admin@geotrack.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', 1, '2026-09-08 15:22:36'),
(1247, 2, 'admin@geotrack.com', 'simple_admin', 'login_success', 'Administration', 2, 'admin@geotrack.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', 1, '2026-09-09 05:37:08'),
(1248, 2, 'admin@geotrack.com', 'simple_admin', 'login_success', 'Administration', 2, 'admin@geotrack.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', 1, '2026-09-09 05:37:08'),
(1249, 2, 'admin@geotrack.com', 'simple_admin', 'logout', 'Administration', 2, 'admin@geotrack.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', 1, '2026-09-09 05:41:25'),
(1250, 2, 'admin@geotrack.com', 'simple_admin', 'logout', 'Administration', 2, 'admin@geotrack.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', 1, '2026-09-09 05:41:25'),
(1251, NULL, 'unknown', NULL, 'login_success', 'Administration', 66, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', NULL, '2026-09-09 05:41:44'),
(1252, NULL, 'unknown', NULL, 'login_success', 'Administration', 66, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', NULL, '2026-09-09 05:41:44'),
(1253, NULL, 'malalanirinaemile2@gmail.com', NULL, 'login_failed', NULL, NULL, NULL, 'Identifiant introuvable', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', NULL, '2026-09-09 05:50:24'),
(1254, NULL, 'malalanirinaemile2@gmail.com', NULL, 'login_failed', NULL, NULL, NULL, 'Identifiant introuvable', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', NULL, '2026-09-09 05:50:31'),
(1255, NULL, 'unknown', NULL, 'update', 'Employe', 66, 'MALALANIRINA Emile', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', NULL, '2026-09-09 06:03:38'),
(1256, NULL, 'unknown', NULL, 'login_success', 'Administration', 66, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', NULL, '2026-09-09 06:03:38'),
(1257, NULL, 'unknown', NULL, 'login_success', 'Administration', 66, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', NULL, '2026-09-09 06:03:38'),
(1258, NULL, 'unknown', NULL, 'login_success', 'Administration', 66, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', NULL, '2026-09-09 06:06:38'),
(1259, NULL, 'unknown', NULL, 'login_success', 'Administration', 66, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', NULL, '2026-09-09 06:06:38'),
(1260, NULL, 'malalanirinaemile2@gmail.com', NULL, 'login_failed', NULL, NULL, NULL, 'Identifiant introuvable', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', NULL, '2026-09-09 06:10:04'),
(1261, NULL, 'unknown', NULL, 'login_success', 'Administration', 66, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36 Edg/152.0.0.0', NULL, '2026-09-09 06:11:47'),
(1262, NULL, 'unknown', NULL, 'login_success', 'Administration', 66, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36 Edg/152.0.0.0', NULL, '2026-09-09 06:11:47'),
(1263, 2, 'admin@geotrack.com', 'simple_admin', 'login_success', 'Administration', 2, 'admin@geotrack.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', 1, '2026-09-09 06:19:07'),
(1264, 2, 'admin@geotrack.com', 'simple_admin', 'login_success', 'Administration', 2, 'admin@geotrack.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', 1, '2026-09-09 06:19:07'),
(1265, 2, 'admin@geotrack.com', 'simple_admin', 'logout', 'Administration', 2, 'admin@geotrack.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', 1, '2026-09-09 06:51:54'),
(1266, 2, 'admin@geotrack.com', 'simple_admin', 'logout', 'Administration', 2, 'admin@geotrack.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', 1, '2026-09-09 06:51:54'),
(1267, NULL, 'unknown', NULL, 'login_success', 'Administration', 68, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', NULL, '2026-09-09 06:57:12'),
(1268, NULL, 'unknown', NULL, 'login_success', 'Administration', 68, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', NULL, '2026-09-09 06:57:12'),
(1269, 2, 'admin@geotrack.com', 'simple_admin', 'login_success', 'Administration', 2, 'admin@geotrack.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', 1, '2026-09-09 07:28:36'),
(1270, 2, 'admin@geotrack.com', 'simple_admin', 'login_success', 'Administration', 2, 'admin@geotrack.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', 1, '2026-09-09 07:28:36'),
(1271, 2, 'admin@geotrack.com', 'simple_admin', 'logout', 'Administration', 2, 'admin@geotrack.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', 1, '2026-09-09 07:28:44'),
(1272, 2, 'admin@geotrack.com', 'simple_admin', 'logout', 'Administration', 2, 'admin@geotrack.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', 1, '2026-09-09 07:28:44'),
(1273, 2, 'admin@geotrack.com', 'simple_admin', 'login_success', 'Administration', 2, 'admin@geotrack.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', 1, '2026-09-09 07:28:49'),
(1274, 2, 'admin@geotrack.com', 'simple_admin', 'login_success', 'Administration', 2, 'admin@geotrack.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', 1, '2026-09-09 07:28:50'),
(1275, 2, 'admin@geotrack.com', 'simple_admin', 'logout', 'Administration', 2, 'admin@geotrack.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', 1, '2026-09-09 07:29:09'),
(1276, 2, 'admin@geotrack.com', 'simple_admin', 'logout', 'Administration', 2, 'admin@geotrack.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', 1, '2026-09-09 07:29:09'),
(1277, NULL, 'unknown', NULL, 'login_success', 'Administration', 67, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', NULL, '2026-09-09 07:30:49'),
(1278, NULL, 'unknown', NULL, 'login_success', 'Administration', 67, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', NULL, '2026-09-09 07:30:49'),
(1279, 2, 'admin@geotrack.com', 'simple_admin', 'login_success', 'Administration', 2, 'admin@geotrack.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', 1, '2026-09-09 07:39:27'),
(1280, 2, 'admin@geotrack.com', 'simple_admin', 'login_success', 'Administration', 2, 'admin@geotrack.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', 1, '2026-09-09 07:39:27'),
(1281, 2, 'admin@geotrack.com', 'simple_admin', 'logout', 'Administration', 2, 'admin@geotrack.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', 1, '2026-09-09 07:39:31'),
(1282, 2, 'admin@geotrack.com', 'simple_admin', 'logout', 'Administration', 2, 'admin@geotrack.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', 1, '2026-09-09 07:39:31'),
(1283, 2, 'admin@geotrack.com', 'simple_admin', 'login_success', 'Administration', 2, 'admin@geotrack.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', 1, '2026-09-09 07:39:33'),
(1284, 2, 'admin@geotrack.com', 'simple_admin', 'login_success', 'Administration', 2, 'admin@geotrack.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', 1, '2026-09-09 07:39:33'),
(1285, 2, 'admin@geotrack.com', 'simple_admin', 'logout', 'Administration', 2, 'admin@geotrack.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', 1, '2026-09-09 08:02:10'),
(1286, 2, 'admin@geotrack.com', 'simple_admin', 'logout', 'Administration', 2, 'admin@geotrack.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', 1, '2026-09-09 08:02:10'),
(1287, 2, 'admin@geotrack.com', 'simple_admin', 'login_success', 'Administration', 2, 'admin@geotrack.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', 1, '2026-09-09 08:09:06'),
(1288, 2, 'admin@geotrack.com', 'simple_admin', 'login_success', 'Administration', 2, 'admin@geotrack.com', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', 1, '2026-09-09 08:09:06');

-- --------------------------------------------------------

--
-- Structure de la table `administration`
--

CREATE TABLE `administration` (
  `ID` int(10) UNSIGNED NOT NULL,
  `Identifiant_email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `Password_` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `IsSuperAdmin` tinyint(1) NOT NULL DEFAULT 0,
  `IsSeller` tinyint(1) NOT NULL DEFAULT 0,
  `IsManager` tinyint(1) NOT NULL DEFAULT 0,
  `SiegeID` int(10) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp(),
  `remember_token` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `Actived` tinyint(1) DEFAULT 0,
  `deleted` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `administration`
--

INSERT INTO `administration` (`ID`, `Identifiant_email`, `Password_`, `IsSuperAdmin`, `IsSeller`, `IsManager`, `SiegeID`, `created_at`, `updated_at`, `remember_token`, `Actived`, `deleted`) VALUES
(1, 'admin@kosi-time.com', '2a8e1455bec3ce1b71c396d0e9c2cbca58b05f84', 1, 0, 0, NULL, NULL, NULL, NULL, 1, 0),
(2, 'admin@geotrack.com', '24b891f2915f09661cabc28ddf63f336c8c8f517', 0, 0, 1, 1, NULL, NULL, NULL, 1, 0),
(4, 'admin@run-telemat.com', 'ee8377fa4b53905f95a64f12a57391a1241089b7', 0, 0, 0, 2, NULL, NULL, NULL, 1, 1),
(7, 'admin@pro-elec.com', 'b1855e280f37c532d694415842b0ea31fd9f6e0f', 0, 0, 0, 4, NULL, NULL, NULL, 0, 1),
(8, 'admin@island-food.com', '7d94404e1ca839e241a4dc75602f2df20a367b1a', 0, 0, 0, 3, NULL, NULL, NULL, 0, 1),
(13, 'admin@sel.com', 'f39077bbae80d8fb5f639e1fd3a422479ae4a725', 1, 1, 0, NULL, '2026-03-06 12:52:42', '2026-03-06 12:52:42', NULL, 1, 0),
(14, 'adminkosi@gmail.com', 'b7642ee606274722a63f570a1232902bb110cc45', 1, 0, 0, NULL, '2026-03-11 05:19:17', '2026-03-11 05:19:17', NULL, 1, 0),
(15, 'ad@gmail.com', 'c7c8b6e52f202e0fc0b73ec79c7cf7da5c02a7ab', 1, 0, 0, NULL, '2026-03-11 07:56:46', '2026-03-11 07:56:46', NULL, 1, 0),
(16, 'admin@manage-sup.com', '9efc51fb58ef74257cf303e2be33660633d34ca9', 1, 0, 1, NULL, '2026-07-15 08:46:40', '2026-07-15 08:46:40', NULL, 1, 0),
(17, 'manager.geotrack@kosi-time.com', 'cbfdac6008f9cab4083784cbd1874f76618d2a97', 0, 0, 1, 1, '2026-08-20 12:06:03', '2026-08-20 12:06:03', NULL, 1, 0),
(18, 'testautreadmin@gmail.com', 'c4edb3216ae52510b16f308fe3e5ffcefac11821', 0, 0, 1, 2, '2026-08-28 15:58:45', '2026-08-28 15:58:45', NULL, 1, 0),
(19, 'entreprisenouveau@gmail.com', '1b93baa77c7671a1038a8f27bf5b5feee041999c', 0, 0, 1, 12, '2026-09-04 10:47:54', '2026-09-04 10:47:54', NULL, 1, 0);

-- --------------------------------------------------------

--
-- Structure de la table `admin_roles`
--

CREATE TABLE `admin_roles` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `admin_id` int(10) UNSIGNED NOT NULL,
  `role` enum('superadmin','company_admin','rh','manager','direction','drh') NOT NULL,
  `company_id` int(10) UNSIGNED DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `company_holidays`
--

CREATE TABLE `company_holidays` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `site_id` int(10) UNSIGNED DEFAULT NULL,
  `date` date NOT NULL,
  `name` varchar(255) NOT NULL,
  `is_recurring` tinyint(1) NOT NULL DEFAULT 0 COMMENT 'Récurrent chaque année',
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `is_customizable` tinyint(1) NOT NULL DEFAULT 0,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `company_holidays`
--

INSERT INTO `company_holidays` (`id`, `site_id`, `date`, `name`, `is_recurring`, `is_active`, `is_customizable`, `deleted_at`, `created_at`, `updated_at`) VALUES
(10, 1, '2026-12-25', 'Noel', 1, 1, 0, NULL, '2026-09-04 12:36:29', '2026-09-04 12:36:29'),
(11, 1, '2026-01-01', 'Jour de l \'an', 1, 1, 0, NULL, '2026-09-04 12:36:55', '2026-09-04 12:36:55'),
(12, 1, '2026-06-26', 'fete de l\' independance  Malagasy', 1, 1, 0, NULL, '2026-09-04 12:37:40', '2026-09-04 12:37:40');

-- --------------------------------------------------------

--
-- Structure de la table `comparaisons_planning`
--

CREATE TABLE `comparaisons_planning` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `planning_detail_id` bigint(20) UNSIGNED NOT NULL,
  `pointage_id` int(10) UNSIGNED DEFAULT NULL,
  `heure_debut_prevue` time NOT NULL,
  `heure_fin_prevue` time NOT NULL,
  `heure_debut_reelle` time DEFAULT NULL,
  `heure_fin_reelle` time DEFAULT NULL,
  `ecart_debut_minutes` int(11) DEFAULT NULL,
  `ecart_fin_minutes` int(11) DEFAULT NULL,
  `ecart_total_minutes` int(11) DEFAULT NULL,
  `statut` enum('ponctuel','retard','avance','absent','pause_manquante','inconnu') NOT NULL DEFAULT 'inconnu',
  `date_comparaison` date NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `conges`
--

CREATE TABLE `conges` (
  `id` int(10) UNSIGNED NOT NULL,
  `employee_id` int(10) UNSIGNED NOT NULL,
  `SiegeID` int(10) UNSIGNED DEFAULT NULL,
  `date_debut` datetime(6) NOT NULL,
  `date_fin` datetime(6) NOT NULL,
  `type_conge` varchar(25) NOT NULL,
  `commentaire` text DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `conge_validations`
--

CREATE TABLE `conge_validations` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `matricule` varchar(255) DEFAULT NULL,
  `nom_prenom` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `telephone` varchar(255) DEFAULT NULL,
  `SiegeID` int(10) UNSIGNED DEFAULT NULL COMMENT 'FK vers Entreprises_sieges',
  `date_heure_debut` datetime NOT NULL,
  `date_heure_fin` datetime NOT NULL,
  `status` enum('en_cours','validated','not_validated') NOT NULL DEFAULT 'en_cours',
  `raison` text DEFAULT NULL,
  `raison_rejection` text DEFAULT NULL,
  `date_creation` timestamp NOT NULL DEFAULT current_timestamp(),
  `date_validation` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `conge_validations`
--

INSERT INTO `conge_validations` (`id`, `matricule`, `nom_prenom`, `email`, `telephone`, `SiegeID`, `date_heure_debut`, `date_heure_fin`, `status`, `raison`, `raison_rejection`, `date_creation`, `date_validation`, `created_at`, `updated_at`) VALUES
(1, 'EMP-001', 'Jean-Marc Dupont', 'jm.dupont@example.com', '+33 6 01 02 03 04', 1, '2026-06-02 08:00:00', '2026-06-06 17:00:00', 'validated', NULL, NULL, '2026-05-12 13:23:32', '2026-05-14 13:23:32', '2026-05-22 13:23:32', '2026-05-22 13:23:32'),
(2, 'EMP-002', 'Marie Kouassi', 'm.kouassi@example.com', '+225 07 12 34 56', 2, '2026-06-09 08:00:00', '2026-06-13 17:00:00', 'en_cours', NULL, NULL, '2026-05-19 13:23:32', NULL, '2026-05-22 13:23:32', '2026-05-22 13:23:32'),
(3, 'EMP-003', 'Franck Nguyen', 'f.nguyen@example.com', NULL, 3, '2026-05-19 08:00:00', '2026-05-23 17:00:00', 'not_validated', NULL, NULL, '2026-05-02 13:23:32', '2026-05-04 13:23:32', '2026-05-22 13:23:32', '2026-05-22 13:23:32'),
(4, NULL, 'Aïcha Traoré', 'a.traore@example.com', '+221 77 98 76 54', 1, '2026-07-14 00:00:00', '2026-07-25 23:59:00', 'validated', NULL, NULL, '2026-05-21 13:23:32', '2026-05-23 07:47:12', '2026-05-22 13:23:32', '2026-05-23 07:47:12'),
(5, 'EMP-005', 'Stéphane Bernard', 's.bernard@example.com', '+33 6 55 44 33 22', 4, '2026-08-03 08:00:00', '2026-08-14 17:00:00', 'validated', NULL, NULL, '2026-05-17 13:23:32', '2026-05-20 13:23:32', '2026-05-22 13:23:32', '2026-05-22 13:23:32'),
(7, NULL, 'MALALANIRINA Emile Noeline', 'malalanirinaemile2@gmail.com', NULL, 1, '2026-07-27 08:55:00', '2026-07-31 15:55:00', 'validated', 'test', NULL, '2026-07-22 14:55:49', '2026-07-22 14:57:33', '2026-07-22 14:55:49', '2026-07-22 14:57:33');

-- --------------------------------------------------------

--
-- Structure de la table `departments`
--

CREATE TABLE `departments` (
  `id` int(10) UNSIGNED NOT NULL,
  `company_id` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `site_id` int(10) UNSIGNED NOT NULL,
  `name` varchar(100) NOT NULL,
  `code` varchar(20) DEFAULT NULL,
  `manager_employee_id` int(10) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `departments`
--

INSERT INTO `departments` (`id`, `company_id`, `site_id`, `name`, `code`, `manager_employee_id`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 0, 1, 'service de menage', 'SM', 2, '2026-08-10 14:33:35', '2026-08-11 09:58:23', NULL),
(2, 0, 4, 'PRODUCTION SARL', 'SARL', NULL, '2026-08-10 14:33:35', '2026-08-11 09:39:07', NULL),
(3, 0, 4, 'PROELECSARL', 'PRLC', 35, '2026-08-10 14:33:35', '2026-08-20 10:43:26', NULL),
(4, 0, 1, 'Service electronique', 'SE', NULL, '2026-08-10 14:33:35', '2026-08-11 09:27:14', NULL),
(8, 0, 1, 'Service informatique', 'SI', 2, '2026-08-10 16:18:53', '2026-08-10 16:18:53', NULL),
(9, 0, 4, 'Serivice de mise en boite', 'SDMEB', 36, '2026-08-11 09:40:54', '2026-08-11 09:41:09', NULL),
(10, 0, 1, 'Direction générale', 'DG', NULL, '2026-08-18 09:15:33', '2026-08-18 09:15:33', NULL),
(11, 0, 1, 'Administration', 'ADM', NULL, '2026-08-18 09:15:33', '2026-08-18 09:15:33', NULL),
(12, 0, 1, 'Ressources humaines', 'RH', 2, '2026-08-18 09:15:33', '2026-09-02 08:25:29', NULL),
(13, 0, 1, 'Comptabilité / Finance', 'CPT', NULL, '2026-08-18 09:15:33', '2026-08-18 09:15:33', NULL),
(14, 0, 1, 'Commercial', 'COM', NULL, '2026-08-18 09:15:33', '2026-08-18 09:15:33', NULL),
(15, 0, 1, 'Marketing / Communication', 'MKT', NULL, '2026-08-18 09:15:33', '2026-08-18 09:15:33', NULL),
(16, 0, 1, 'Informatique / Système d\'information', 'IT', NULL, '2026-08-18 09:15:33', '2026-08-18 09:15:33', NULL),
(17, 0, 1, 'Production', 'PROD', NULL, '2026-08-18 09:15:33', '2026-08-18 09:15:33', NULL),
(18, 0, 1, 'Exploitation', 'EXP', NULL, '2026-08-18 09:15:33', '2026-08-18 09:15:33', NULL),
(19, 0, 1, 'Logistique', 'LOG', NULL, '2026-08-18 09:15:33', '2026-08-18 09:15:33', NULL),
(20, 0, 1, 'Stock / Magasin', 'STK', NULL, '2026-08-18 09:15:33', '2026-08-18 09:15:33', NULL),
(21, 0, 1, 'Maintenance', 'MAINT', NULL, '2026-08-18 09:15:33', '2026-08-18 09:15:33', NULL),
(22, 0, 1, 'Technique', 'TECH', NULL, '2026-08-18 09:15:33', '2026-08-18 09:15:33', NULL),
(23, 0, 1, 'Qualité', 'QUAL', NULL, '2026-08-18 09:15:33', '2026-08-18 09:15:33', NULL),
(24, 0, 1, 'QHSE / HSE', 'HSE', NULL, '2026-08-18 09:15:33', '2026-08-18 09:15:33', NULL),
(25, 0, 1, 'Service client', 'SC', NULL, '2026-08-18 09:15:33', '2026-08-18 09:15:33', NULL),
(26, 0, 1, 'Formation', 'FORM', NULL, '2026-08-18 09:15:33', '2026-08-18 09:15:33', NULL),
(27, 0, 1, 'Pédagogie', 'PED', NULL, '2026-08-18 09:15:33', '2026-08-18 09:15:33', NULL),
(28, 0, 1, 'Transport', 'TRANS', NULL, '2026-08-18 09:15:33', '2026-08-18 09:15:33', NULL),
(29, 0, 1, 'Parc automobile', 'PARC', NULL, '2026-08-18 09:15:33', '2026-08-18 09:15:33', NULL),
(30, 0, 1, 'Achats', 'ACH', NULL, '2026-08-18 09:15:33', '2026-08-18 09:15:33', NULL),
(31, 0, 1, 'Sécurité', 'SEC', NULL, '2026-08-18 09:15:33', '2026-08-18 09:15:33', NULL),
(32, 0, 1, 'Entretien', 'ENT', NULL, '2026-08-18 09:15:33', '2026-08-18 09:15:33', NULL),
(33, 0, 1, 'Juridique', 'JUR', NULL, '2026-08-18 09:15:33', '2026-08-18 09:15:33', NULL),
(34, 0, 2, 'Service informatique', 'DSI', 61, '2026-08-28 16:03:42', '2026-08-28 16:20:15', NULL),
(35, 0, 1, 'Service informatique 2.0', 'SRI', 2, '2026-09-01 15:10:47', '2026-09-01 15:10:47', NULL),
(36, 0, 1, 'nouveau service', 'nvs', 66, '2026-09-08 08:19:41', '2026-09-08 08:19:41', NULL);

-- --------------------------------------------------------

--
-- Structure de la table `employee_managers`
--

CREATE TABLE `employee_managers` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `employee_id` int(10) UNSIGNED NOT NULL,
  `manager_id` int(10) UNSIGNED NOT NULL,
  `company_id` int(10) UNSIGNED NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `employes`
--

CREATE TABLE `employes` (
  `ID` int(10) UNSIGNED NOT NULL,
  `company_id` int(10) UNSIGNED DEFAULT NULL,
  `site_id` int(10) UNSIGNED DEFAULT NULL,
  `department_id` int(10) UNSIGNED DEFAULT NULL,
  `job_title_id` int(10) UNSIGNED DEFAULT NULL,
  `hierarchy_level_id` int(10) UNSIGNED DEFAULT NULL,
  `manager_id` int(10) UNSIGNED DEFAULT NULL,
  `user_id` int(10) UNSIGNED DEFAULT NULL,
  `employment_status` enum('actif','suspendu','sorti') DEFAULT 'actif',
  `hire_date` date DEFAULT NULL,
  `Nom` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `telephone` varchar(255) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `BadgeID` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `HasBiometricSetup` tinyint(1) NOT NULL DEFAULT 0,
  `HasFaceSetup` tinyint(1) NOT NULL DEFAULT 0,
  `FaceEncodingPath` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `Pin` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `num_mat` text DEFAULT NULL,
  `CreatedAt` datetime NOT NULL DEFAULT current_timestamp(),
  `Actived` tinyint(1) NOT NULL DEFAULT 0,
  `SiegeID` int(10) UNSIGNED NOT NULL,
  `deleted` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `employes`
--

INSERT INTO `employes` (`ID`, `company_id`, `site_id`, `department_id`, `job_title_id`, `hierarchy_level_id`, `manager_id`, `user_id`, `employment_status`, `hire_date`, `Nom`, `email`, `telephone`, `password`, `remember_token`, `BadgeID`, `HasBiometricSetup`, `HasFaceSetup`, `FaceEncodingPath`, `Pin`, `num_mat`, `CreatedAt`, `Actived`, `SiegeID`, `deleted`) VALUES
(66, 1, 1, 16, 348, 1, 68, NULL, 'actif', '2026-01-23', 'MALALANIRINA Emile', 'malalanirinaemile2@gmail.com', NULL, '$2y$12$oS0PBv68skmanMki8kLOF.ZgBs4P9GOCJKHakLAPfWAZJR2ODA0IG', 'ggzIHa73SJcS89ySuG8ZKaTxLoGvZxE8jS9DoK9dfJwnXCaMDlRIL2JjegGk', 'd3a4337d53252dc675af653f03a1ae3bb2f1ae659fdb8d6420bc1061fa9a9456', 0, 0, NULL, NULL, 'IM 1562', '2026-09-04 10:52:46', 1, 1, 0),
(67, 1, 1, 12, 349, 5, 69, NULL, 'actif', '2023-10-23', 'Emma Solofo', 'Emmasolofo@gmail.com', NULL, '$2y$12$Q2UvWSvzLk5kVR./Q33UYeomjSTOKxJ1x7xBgT.Rlz9i8m9LEY.xa', NULL, '6447fd17883351814e6069601557841ecee9de85ea6e1d9996f99354d7da368f', 0, 0, NULL, NULL, 'IM 1561', '2026-09-04 12:25:13', 1, 1, 0),
(68, 1, 1, 16, 350, 4, 67, NULL, 'actif', '2025-05-01', 'Franco Hedi', 'ramarotafika1999@gmail.com', NULL, '$2y$12$Oi/PapkirmbVKsxUwHQHzeGO.YskRKK84hxSOCBiMcfNcm9vkmvT6', NULL, '6e73f6f76b1b3b59ce8bda8756ae3ad0fe227750fb096bdeff7c620a14daef6a', 0, 0, NULL, NULL, 'IM 1560', '2026-09-04 12:30:01', 1, 1, 0),
(69, 1, 1, 11, 351, 7, NULL, NULL, 'actif', '2020-01-01', 'TARKIN WILLY', 'ceo@gmail.com', NULL, '$2y$12$CgazZKzA7SBX7/bS9lEuJe.yqB7JqtL.g9hQIytFsj8Ze4.5q4mki', NULL, '98392a2e53aaf0d12d5f7d1c51a1357667da03eea46cde50fe7be43ef64fa662', 0, 0, NULL, NULL, 'IM 0000', '2026-09-04 12:33:10', 1, 1, 0);

-- --------------------------------------------------------

--
-- Structure de la table `entreprises`
--

CREATE TABLE `entreprises` (
  `ID` int(10) UNSIGNED NOT NULL,
  `Nom` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `Logo` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `Nom_Lieu_Ville` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `Latitude` decimal(18,8) NOT NULL,
  `Longitude` decimal(18,8) NOT NULL,
  `RadiusInMeters` decimal(18,8) NOT NULL DEFAULT 10.00000000,
  `CreatedAt` datetime NOT NULL DEFAULT current_timestamp(),
  `Actived` tinyint(1) NOT NULL DEFAULT 0,
  `SiegeID` int(10) UNSIGNED NOT NULL,
  `deleted` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `entreprises`
--

INSERT INTO `entreprises` (`ID`, `Nom`, `Logo`, `Nom_Lieu_Ville`, `Latitude`, `Longitude`, `RadiusInMeters`, `CreatedAt`, `Actived`, `SiegeID`, `deleted`) VALUES
(1, 'Geotrack Solutions', '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', 'ANTANANARIVO/IAVOLOHA', -19.01889060, 47.53933250, 30.00000000, '2025-08-29 06:48:51', 0, 1, 1);
INSERT INTO `entreprises` (`ID`, `Nom`, `Logo`, `Nom_Lieu_Ville`, `Latitude`, `Longitude`, `RadiusInMeters`, `CreatedAt`, `Actived`, `SiegeID`, `deleted`) VALUES
(7, 'RUN TELEMAT', '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', 'STE-ANDRE', -20.92583060, 55.65736430, 30.00000000, '2025-10-03 12:26:06', 0, 2, 1),
(15, 'Noodle Box', NULL, 'ST-BENOIT', -20.92806200, 55.66172360, 30.00000000, '2025-10-25 10:16:00', 1, 3, 0),
(17, 'PRO-ELEC', NULL, 'MAMOUDZOU', -12.76510560, 45.22580620, 30.00000000, '2025-10-31 09:28:17', 0, 4, 1),
(22, 'bureau geotrack', NULL, NULL, -19.01888974, 47.53938474, 50.00000000, '2026-02-03 14:57:57', 1, 2, 0),
(30, 'Nav', NULL, 'Anosy', -12.00000000, 20.00000000, 30.00000000, '2026-03-11 05:20:23', 0, 1, 0),
(31, 'SALIO', NULL, 'Mayotte', -12.00000000, 20.00000000, 30.00000000, '2026-03-11 05:30:00', 0, 2, 0),
(32, 'test v3', NULL, 'LA REUNION', -12.00000000, 20.00000000, 30.00000000, '2026-03-11 05:37:47', 0, 1, 0),
(33, 'SIEGE ENTREPRISE A', NULL, 'MARSEILLE', 75.00000000, 140.00000000, 100.00000000, '2026-09-04 10:45:32', 1, 12, 0);

-- --------------------------------------------------------

--
-- Structure de la table `entreprises_sieges`
--

CREATE TABLE `entreprises_sieges` (
  `ID` int(10) UNSIGNED NOT NULL,
  `Nom` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `Nom_Lieu_Ville` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `Actived` tinyint(1) NOT NULL DEFAULT 0,
  `CreatedAt` timestamp NOT NULL DEFAULT current_timestamp(),
  `Pays` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `deleted` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `entreprises_sieges`
--

INSERT INTO `entreprises_sieges` (`ID`, `Nom`, `Nom_Lieu_Ville`, `Actived`, `CreatedAt`, `Pays`, `deleted`) VALUES
(1, 'Geotrack Solution SIEGE', 'Antananarivo Madagascar', 0, '2025-11-04 06:58:50', NULL, 1),
(2, 'Run-Telemat SIEGE', 'LA REUNION', 0, '2025-11-04 06:58:50', NULL, 1),
(3, 'ISLAND FOOD', 'ISLAND FOOD - LA REUNION', 0, '2025-11-04 06:58:50', NULL, 1),
(4, 'PRO ELEC SARL', 'PRO ELEC SARL - Mayotte', 1, '2025-11-04 06:58:50', NULL, 0),
(8, 'Réel Electrique TEST', 'TANANARIVO', 1, '2026-02-20 08:09:49', 'ae', 0),
(9, 'N8N', 'TANANARIVO', 0, '2026-03-11 05:39:01', 'ae', 1),
(10, 'test test test', NULL, 0, '2026-03-11 07:51:37', 'ad', 1),
(11, 'fdffdfd', NULL, 0, '2026-03-11 08:49:23', 'ae', 1),
(12, 'Entreprise A', 'PARIS', 1, '2026-09-04 10:44:05', 'fr', 0);

-- --------------------------------------------------------

--
-- Structure de la table `evenements_planning`
--

CREATE TABLE `evenements_planning` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `siege_id` int(10) UNSIGNED NOT NULL,
  `service_id` int(10) UNSIGNED DEFAULT NULL,
  `poste_id` int(10) UNSIGNED DEFAULT NULL,
  `titre` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `type` enum('formation','deplacement','reunion','conges_exceptionnel','autre') NOT NULL,
  `debut` datetime NOT NULL,
  `fin` datetime NOT NULL,
  `toute_la_journee` tinyint(1) NOT NULL DEFAULT 0,
  `couleur` varchar(50) DEFAULT NULL,
  `cree_par` int(10) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `evenement_employes`
--

CREATE TABLE `evenement_employes` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `evenement_id` bigint(20) UNSIGNED NOT NULL,
  `employe_id` int(10) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `hierarchy_levels`
--

CREATE TABLE `hierarchy_levels` (
  `id` int(10) UNSIGNED NOT NULL,
  `company_id` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `name` varchar(50) NOT NULL,
  `code` varchar(10) DEFAULT NULL,
  `rank` int(11) NOT NULL DEFAULT 1,
  `is_managerial` tinyint(1) NOT NULL DEFAULT 0,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `hierarchy_levels`
--

INSERT INTO `hierarchy_levels` (`id`, `company_id`, `name`, `code`, `rank`, `is_managerial`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, 0, 'Employé / Opérationnel', 'N0', 0, 0, NULL, '2026-08-18 09:18:48', '2026-08-18 09:18:48'),
(2, 0, 'Référent / Senior / Chef d\'équipe', 'N1', 1, 0, NULL, '2026-08-18 09:18:48', '2026-08-18 09:18:48'),
(3, 0, 'Superviseur / Encadrement de proximité', 'N2', 2, 1, NULL, '2026-08-18 09:18:48', '2026-08-18 09:18:48'),
(4, 0, 'Responsable de service', 'N3', 3, 1, NULL, '2026-08-18 09:18:48', '2026-08-18 09:18:48'),
(5, 0, 'Manager / Chef de département', 'N4', 4, 1, NULL, '2026-08-18 09:18:48', '2026-08-18 09:18:48'),
(6, 0, 'Directeur', 'N5', 5, 1, NULL, '2026-08-18 09:18:48', '2026-08-18 09:18:48'),
(7, 0, 'Directeur général / Direction', 'N6', 6, 1, NULL, '2026-08-18 09:18:48', '2026-08-18 09:18:48'),
(15, 0, 'CEO', 'N7', 7, 0, NULL, '2026-09-08 10:29:58', '2026-09-08 10:29:58'),
(16, 0, 'CEOoc', 'n9', 7, 0, NULL, '2026-09-08 10:53:24', '2026-09-08 10:53:24');

-- --------------------------------------------------------

--
-- Structure de la table `horaires_types`
--

CREATE TABLE `horaires_types` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `poste_id` int(10) UNSIGNED NOT NULL,
  `jours_travailles` varchar(100) DEFAULT NULL,
  `heure_debut` time NOT NULL,
  `heure_fin` time NOT NULL,
  `pause_debut` time DEFAULT NULL,
  `pause_fin` time DEFAULT NULL,
  `deuxieme_debut` time DEFAULT NULL,
  `deuxieme_fin` time DEFAULT NULL,
  `par_defaut` tinyint(1) NOT NULL DEFAULT 1,
  `cree_par` int(10) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `horaires_types`
--

INSERT INTO `horaires_types` (`id`, `poste_id`, `jours_travailles`, `heure_debut`, `heure_fin`, `pause_debut`, `pause_fin`, `deuxieme_debut`, `deuxieme_fin`, `par_defaut`, `cree_par`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 348, 'lundi,mardi,mercredi,jeudi,vendredi', '08:30:00', '17:00:00', '12:00:00', '13:00:00', '13:00:00', '17:00:00', 1, 2, '2026-09-08 15:06:43', '2026-09-08 15:06:43', NULL),
(2, 348, 'lundi,mardi,mercredi,jeudi,vendredi', '08:00:00', '12:00:00', '12:00:00', '13:00:00', '13:00:00', '16:30:00', 1, 2, '2026-09-08 15:07:37', '2026-09-08 15:07:37', NULL);

-- --------------------------------------------------------

--
-- Structure de la table `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) UNSIGNED NOT NULL,
  `reserved_at` int(10) UNSIGNED DEFAULT NULL,
  `available_at` int(10) UNSIGNED NOT NULL,
  `created_at` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `job_batches`
--

CREATE TABLE `job_batches` (
  `id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `total_jobs` int(11) NOT NULL,
  `pending_jobs` int(11) NOT NULL,
  `failed_jobs` int(11) NOT NULL,
  `failed_job_ids` longtext NOT NULL,
  `options` mediumtext DEFAULT NULL,
  `cancelled_at` int(11) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `finished_at` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `job_titles`
--

CREATE TABLE `job_titles` (
  `id` int(10) UNSIGNED NOT NULL,
  `company_id` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `name` varchar(100) NOT NULL,
  `code` varchar(20) DEFAULT NULL,
  `hierarchy_level_id` int(10) UNSIGNED DEFAULT NULL COMMENT 'Niveau KOSI associé',
  `department_id` int(10) UNSIGNED DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `job_titles`
--

INSERT INTO `job_titles` (`id`, `company_id`, `name`, `code`, `hierarchy_level_id`, `department_id`, `deleted_at`, `created_at`, `updated_at`) VALUES
(348, 0, 'Stagiaire Dev Web', 'STG', 1, 16, NULL, '2026-09-04 10:50:51', '2026-09-04 10:50:51'),
(349, 0, 'Responsable RH', 'RRH', 5, 12, NULL, '2026-09-04 12:24:11', '2026-09-04 12:24:11'),
(350, 0, 'Lead Dev', 'LDEV', 4, 16, NULL, '2026-09-04 12:26:26', '2026-09-04 12:26:26'),
(351, 0, 'Directeur G', 'DG', 7, 11, NULL, '2026-09-04 12:32:15', '2026-09-04 12:32:15');

-- --------------------------------------------------------

--
-- Structure de la table `jours_non_travailles`
--

CREATE TABLE `jours_non_travailles` (
  `ID` bigint(20) UNSIGNED NOT NULL,
  `Date` date NOT NULL,
  `Nom` varchar(255) NOT NULL,
  `Type` enum('ferie','fermeture','CT','autre') NOT NULL DEFAULT 'ferie',
  `SiegeID` int(10) UNSIGNED DEFAULT NULL,
  `Recurrent` tinyint(1) NOT NULL DEFAULT 0,
  `Description` text DEFAULT NULL,
  `Actived` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `jours_non_travailles`
--

INSERT INTO `jours_non_travailles` (`ID`, `Date`, `Nom`, `Type`, `SiegeID`, `Recurrent`, `Description`, `Actived`, `created_at`, `updated_at`) VALUES
(11, '2025-12-25', 'Noel', 'ferie', NULL, 1, NULL, 1, '2025-12-23 16:15:51', '2025-12-23 16:15:51'),
(12, '2026-01-01', 'Jour de l\'An', 'ferie', NULL, 1, NULL, 1, '2025-12-23 16:16:29', '2025-12-23 16:16:29'),
(13, '2026-04-06', 'Lundi de Pâques', 'ferie', NULL, 0, NULL, 1, '2026-01-10 06:30:35', '2026-01-10 06:30:35'),
(14, '2026-05-01', 'Fête du Travail', 'ferie', NULL, 1, NULL, 1, '2026-01-10 06:32:23', '2026-01-10 06:32:23'),
(15, '2026-05-14', 'Ascension', 'ferie', NULL, 0, NULL, 1, '2026-01-10 06:33:36', '2026-01-10 06:33:36'),
(16, '2026-05-25', 'Lundi de Pentecôte', 'ferie', NULL, 0, NULL, 1, '2026-01-10 06:34:40', '2026-01-10 06:34:40'),
(17, '2026-07-14', 'Fête nationale', 'ferie', 4, 0, NULL, 1, '2026-01-10 06:35:38', '2026-01-10 06:35:38'),
(18, '2026-06-26', 'Fête nationale de l’indépendance', 'ferie', 1, 0, NULL, 1, '2026-01-10 06:36:23', '2026-01-10 06:36:23'),
(19, '2026-08-15', 'Assomption', 'ferie', NULL, 0, NULL, 1, '2026-01-10 06:36:51', '2026-01-10 06:36:51'),
(20, '2026-10-14', 'Proclamation de la Première République', 'ferie', 1, 0, NULL, 1, '2026-01-10 06:37:26', '2026-01-10 06:37:26'),
(21, '2026-11-01', 'Toussaint', 'ferie', NULL, 1, NULL, 1, '2026-01-10 06:37:54', '2026-01-10 06:37:54'),
(22, '2026-12-11', 'Proclamation de la Quatrième République', 'ferie', 1, 0, NULL, 1, '2026-01-10 06:38:26', '2026-01-10 06:38:26'),
(23, '2026-01-28', 'site du chaudron', 'CT', 2, 0, 'Panne d\'électricité', 1, '2026-01-20 05:15:29', '2026-01-20 05:15:29'),
(24, '2026-03-04', 'aniv', 'fermeture', 1, 0, NULL, 1, '2026-03-03 09:33:08', '2026-03-03 09:33:08');

-- --------------------------------------------------------

--
-- Structure de la table `leave_approvals`
--

CREATE TABLE `leave_approvals` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `leave_request_id` bigint(20) UNSIGNED NOT NULL,
  `workflow_step_id` bigint(20) UNSIGNED DEFAULT NULL COMMENT 'Référence à l''étape, peut être NULL si on utilise le JSON du workflow',
  `approver_id` int(10) UNSIGNED NOT NULL COMMENT 'Employé approbateur',
  `step_order` int(11) NOT NULL COMMENT 'Ordre de l''étape dans ce workflow',
  `status` enum('pending','approved','rejected') NOT NULL DEFAULT 'pending',
  `is_current` tinyint(1) NOT NULL DEFAULT 0 COMMENT 'Étape active en cours',
  `approved_at` timestamp NULL DEFAULT NULL,
  `rejected_at` timestamp NULL DEFAULT NULL,
  `rejection_reason` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `leave_approvals`
--

INSERT INTO `leave_approvals` (`id`, `leave_request_id`, `workflow_step_id`, `approver_id`, `step_order`, `status`, `is_current`, `approved_at`, `rejected_at`, `rejection_reason`, `created_at`, `updated_at`) VALUES
(56, 82, NULL, 69, 1, 'rejected', 0, NULL, '2026-09-04 14:43:55', 'Beuacoup de travail', '2026-09-04 14:43:25', '2026-09-04 14:43:55'),
(57, 82, NULL, 67, 2, 'approved', 0, '2026-09-04 14:48:05', NULL, NULL, '2026-09-04 14:43:25', '2026-09-04 14:48:05'),
(58, 82, NULL, 69, 3, 'pending', 1, NULL, NULL, NULL, '2026-09-04 14:43:25', '2026-09-04 14:48:06'),
(59, 83, NULL, 68, 1, 'approved', 0, '2026-09-08 15:17:48', NULL, NULL, '2026-09-08 15:16:16', '2026-09-08 15:17:48'),
(60, 83, NULL, 67, 2, 'approved', 0, '2026-09-08 15:20:39', NULL, NULL, '2026-09-08 15:16:16', '2026-09-08 15:20:39'),
(61, 83, NULL, 69, 3, 'approved', 0, '2026-09-08 15:21:09', NULL, NULL, '2026-09-08 15:16:16', '2026-09-08 15:21:09');

-- --------------------------------------------------------

--
-- Structure de la table `leave_balances`
--

CREATE TABLE `leave_balances` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `employee_id` int(10) UNSIGNED NOT NULL,
  `leave_type_id` bigint(20) UNSIGNED NOT NULL,
  `period_id` bigint(20) UNSIGNED NOT NULL,
  `total_entitled` decimal(10,2) NOT NULL DEFAULT 0.00 COMMENT 'Jours acquis',
  `total_taken` decimal(10,2) NOT NULL DEFAULT 0.00 COMMENT 'Jours pris',
  `total_pending` decimal(10,2) NOT NULL DEFAULT 0.00 COMMENT 'Jours en attente',
  `remaining` decimal(10,2) NOT NULL DEFAULT 0.00 COMMENT 'Jours restants',
  `carryover_from_previous` decimal(10,2) NOT NULL DEFAULT 0.00 COMMENT 'Jours reportés',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `leave_balances`
--

INSERT INTO `leave_balances` (`id`, `employee_id`, `leave_type_id`, `period_id`, `total_entitled`, `total_taken`, `total_pending`, `remaining`, `carryover_from_previous`, `created_at`, `updated_at`) VALUES
(58, 67, 20, 17, 19.00, 9.00, 0.00, 10.00, 0.00, '2026-09-04 12:58:24', '2026-09-04 14:41:37'),
(59, 66, 20, 17, 20.00, 5.00, 0.00, 15.00, 0.00, '2026-09-08 15:15:36', '2026-09-08 15:21:09');

-- --------------------------------------------------------

--
-- Structure de la table `leave_balance_transactions`
--

CREATE TABLE `leave_balance_transactions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `employee_id` int(10) UNSIGNED NOT NULL,
  `leave_type_id` bigint(20) UNSIGNED NOT NULL,
  `period_id` bigint(20) UNSIGNED NOT NULL,
  `amount` decimal(10,2) NOT NULL COMMENT 'Montant (positif=credit, negatif=debit)',
  `type` enum('opening','credit','debit','pending_debit','adjustment','carryover','reversal') NOT NULL,
  `source` varchar(100) DEFAULT NULL COMMENT 'Source de la transaction',
  `reference_id` bigint(20) UNSIGNED DEFAULT NULL COMMENT 'ID de référence (leave_request, import, etc)',
  `reference_type` varchar(100) DEFAULT NULL COMMENT 'Type de référence',
  `description` text DEFAULT NULL,
  `metadata` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL COMMENT 'Données additionnelles' CHECK (json_valid(`metadata`)),
  `created_by` int(10) UNSIGNED DEFAULT NULL COMMENT 'Qui a créé la transaction',
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `leave_balance_transactions`
--

INSERT INTO `leave_balance_transactions` (`id`, `employee_id`, `leave_type_id`, `period_id`, `amount`, `type`, `source`, `reference_id`, `reference_type`, `description`, `metadata`, `created_by`, `created_at`) VALUES
(116, 67, 20, 17, 19.00, 'opening', NULL, NULL, NULL, 'solde initial  cp 2026-2027', '\"{\\\"type\\\":\\\"initialization\\\"}\"', 2, '2026-09-04 12:58:23'),
(117, 67, 20, 17, -3.00, 'debit', NULL, 78, 'leave_request', 'Validation de congé - ', '\"{\\\"type\\\":\\\"debit\\\"}\"', 69, '2026-09-04 13:10:29'),
(118, 67, 20, 17, -3.00, 'debit', NULL, 79, 'leave_request', 'Validation de congé - RDV', '\"{\\\"type\\\":\\\"debit\\\"}\"', 69, '2026-09-04 13:14:11'),
(119, 67, 20, 17, -3.00, 'debit', NULL, 81, 'leave_request', 'Validation de congé - ', '\"{\\\"type\\\":\\\"debit\\\"}\"', 69, '2026-09-04 14:41:37'),
(120, 66, 20, 17, 20.00, 'opening', NULL, NULL, NULL, 'Solde initial', '\"{\\\"type\\\":\\\"initialization\\\"}\"', 2, '2026-09-08 15:15:36'),
(121, 66, 20, 17, -5.00, 'debit', NULL, 83, 'leave_request', 'Validation de congé - ', '\"{\\\"type\\\":\\\"debit\\\"}\"', 69, '2026-09-08 15:21:09');

-- --------------------------------------------------------

--
-- Structure de la table `leave_import_batches`
--

CREATE TABLE `leave_import_batches` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `site_id` int(10) UNSIGNED NOT NULL,
  `batch_number` varchar(50) NOT NULL COMMENT 'Numéro de lot unique',
  `type` enum('opening_balance','historical_leave','future_leave') NOT NULL,
  `status` enum('pending','processing','completed','failed','cancelled') NOT NULL DEFAULT 'pending',
  `total_records` int(11) DEFAULT 0,
  `successful_records` int(11) DEFAULT 0,
  `failed_records` int(11) DEFAULT 0,
  `errors` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL COMMENT 'Erreurs par ligne' CHECK (json_valid(`errors`)),
  `file_name` varchar(255) DEFAULT NULL,
  `file_path` varchar(255) DEFAULT NULL,
  `created_by` int(10) UNSIGNED DEFAULT NULL,
  `cancelled_at` timestamp NULL DEFAULT NULL,
  `completed_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `leave_import_batches`
--

INSERT INTO `leave_import_batches` (`id`, `site_id`, `batch_number`, `type`, `status`, `total_records`, `successful_records`, `failed_records`, `errors`, `file_name`, `file_path`, `created_by`, `cancelled_at`, `completed_at`, `created_at`, `updated_at`) VALUES
(1, 1, 'IMP-20260818-2SOMPF', 'opening_balance', 'completed', 0, 0, 72, '[{\"row\":2,\"field\":\"matricule\",\"message\":\"Le matricule est obligatoire.\"},{\"row\":3,\"field\":\"matricule\",\"message\":\"Le matricule est obligatoire.\"},{\"row\":4,\"field\":\"matricule\",\"message\":\"Le matricule est obligatoire.\"},{\"row\":5,\"field\":\"matricule\",\"message\":\"Le matricule est obligatoire.\"},{\"row\":6,\"field\":\"matricule\",\"message\":\"Le matricule est obligatoire.\"},{\"row\":7,\"field\":\"matricule\",\"message\":\"Le matricule est obligatoire.\"},{\"row\":8,\"field\":\"matricule\",\"message\":\"Le matricule est obligatoire.\"},{\"row\":9,\"field\":\"matricule\",\"message\":\"Le matricule est obligatoire.\"},{\"row\":10,\"field\":\"matricule\",\"message\":\"Le matricule est obligatoire.\"},{\"row\":11,\"field\":\"matricule\",\"message\":\"Le matricule est obligatoire.\"},{\"row\":12,\"field\":\"matricule\",\"message\":\"Le matricule est obligatoire.\"},{\"row\":13,\"field\":\"matricule\",\"message\":\"Le matricule est obligatoire.\"},{\"row\":14,\"field\":\"matricule\",\"message\":\"Le matricule est obligatoire.\"},{\"row\":15,\"field\":\"matricule\",\"message\":\"Le matricule est obligatoire.\"},{\"row\":16,\"field\":\"matricule\",\"message\":\"Le matricule est obligatoire.\"},{\"row\":17,\"field\":\"matricule\",\"message\":\"Le matricule est obligatoire.\"},{\"row\":18,\"field\":\"matricule\",\"message\":\"Le matricule est obligatoire.\"},{\"row\":19,\"field\":\"matricule\",\"message\":\"Le matricule est obligatoire.\"},{\"row\":20,\"field\":\"matricule\",\"message\":\"Le matricule est obligatoire.\"},{\"row\":21,\"field\":\"matricule\",\"message\":\"Le matricule est obligatoire.\"},{\"row\":22,\"field\":\"matricule\",\"message\":\"Le matricule est obligatoire.\"},{\"row\":23,\"field\":\"matricule\",\"message\":\"Le matricule est obligatoire.\"},{\"row\":24,\"field\":\"matricule\",\"message\":\"Le matricule est obligatoire.\"},{\"row\":25,\"field\":\"matricule\",\"message\":\"Le matricule est obligatoire.\"},{\"row\":2,\"field\":\"code_conge\",\"message\":\"Le code cong\\u00e9 est obligatoire.\"},{\"row\":3,\"field\":\"code_conge\",\"message\":\"Le code cong\\u00e9 est obligatoire.\"},{\"row\":4,\"field\":\"code_conge\",\"message\":\"Le code cong\\u00e9 est obligatoire.\"},{\"row\":5,\"field\":\"code_conge\",\"message\":\"Le code cong\\u00e9 est obligatoire.\"},{\"row\":6,\"field\":\"code_conge\",\"message\":\"Le code cong\\u00e9 est obligatoire.\"},{\"row\":7,\"field\":\"code_conge\",\"message\":\"Le code cong\\u00e9 est obligatoire.\"},{\"row\":8,\"field\":\"code_conge\",\"message\":\"Le code cong\\u00e9 est obligatoire.\"},{\"row\":9,\"field\":\"code_conge\",\"message\":\"Le code cong\\u00e9 est obligatoire.\"},{\"row\":10,\"field\":\"code_conge\",\"message\":\"Le code cong\\u00e9 est obligatoire.\"},{\"row\":11,\"field\":\"code_conge\",\"message\":\"Le code cong\\u00e9 est obligatoire.\"},{\"row\":12,\"field\":\"code_conge\",\"message\":\"Le code cong\\u00e9 est obligatoire.\"},{\"row\":13,\"field\":\"code_conge\",\"message\":\"Le code cong\\u00e9 est obligatoire.\"},{\"row\":14,\"field\":\"code_conge\",\"message\":\"Le code cong\\u00e9 est obligatoire.\"},{\"row\":15,\"field\":\"code_conge\",\"message\":\"Le code cong\\u00e9 est obligatoire.\"},{\"row\":16,\"field\":\"code_conge\",\"message\":\"Le code cong\\u00e9 est obligatoire.\"},{\"row\":17,\"field\":\"code_conge\",\"message\":\"Le code cong\\u00e9 est obligatoire.\"},{\"row\":18,\"field\":\"code_conge\",\"message\":\"Le code cong\\u00e9 est obligatoire.\"},{\"row\":19,\"field\":\"code_conge\",\"message\":\"Le code cong\\u00e9 est obligatoire.\"},{\"row\":20,\"field\":\"code_conge\",\"message\":\"Le code cong\\u00e9 est obligatoire.\"},{\"row\":21,\"field\":\"code_conge\",\"message\":\"Le code cong\\u00e9 est obligatoire.\"},{\"row\":22,\"field\":\"code_conge\",\"message\":\"Le code cong\\u00e9 est obligatoire.\"},{\"row\":23,\"field\":\"code_conge\",\"message\":\"Le code cong\\u00e9 est obligatoire.\"},{\"row\":24,\"field\":\"code_conge\",\"message\":\"Le code cong\\u00e9 est obligatoire.\"},{\"row\":25,\"field\":\"code_conge\",\"message\":\"Le code cong\\u00e9 est obligatoire.\"},{\"row\":2,\"field\":\"solde\",\"message\":\"Le solde est obligatoire.\"},{\"row\":3,\"field\":\"solde\",\"message\":\"Le solde est obligatoire.\"},{\"row\":4,\"field\":\"solde\",\"message\":\"Le solde est obligatoire.\"},{\"row\":5,\"field\":\"solde\",\"message\":\"Le solde est obligatoire.\"},{\"row\":6,\"field\":\"solde\",\"message\":\"Le solde est obligatoire.\"},{\"row\":7,\"field\":\"solde\",\"message\":\"Le solde est obligatoire.\"},{\"row\":8,\"field\":\"solde\",\"message\":\"Le solde est obligatoire.\"},{\"row\":9,\"field\":\"solde\",\"message\":\"Le solde est obligatoire.\"},{\"row\":10,\"field\":\"solde\",\"message\":\"Le solde est obligatoire.\"},{\"row\":11,\"field\":\"solde\",\"message\":\"Le solde est obligatoire.\"},{\"row\":12,\"field\":\"solde\",\"message\":\"Le solde est obligatoire.\"},{\"row\":13,\"field\":\"solde\",\"message\":\"Le solde est obligatoire.\"},{\"row\":14,\"field\":\"solde\",\"message\":\"Le solde est obligatoire.\"},{\"row\":15,\"field\":\"solde\",\"message\":\"Le solde est obligatoire.\"},{\"row\":16,\"field\":\"solde\",\"message\":\"Le solde est obligatoire.\"},{\"row\":17,\"field\":\"solde\",\"message\":\"Le solde est obligatoire.\"},{\"row\":18,\"field\":\"solde\",\"message\":\"Le solde est obligatoire.\"},{\"row\":19,\"field\":\"solde\",\"message\":\"Le solde est obligatoire.\"},{\"row\":20,\"field\":\"solde\",\"message\":\"Le solde est obligatoire.\"},{\"row\":21,\"field\":\"solde\",\"message\":\"Le solde est obligatoire.\"},{\"row\":22,\"field\":\"solde\",\"message\":\"Le solde est obligatoire.\"},{\"row\":23,\"field\":\"solde\",\"message\":\"Le solde est obligatoire.\"},{\"row\":24,\"field\":\"solde\",\"message\":\"Le solde est obligatoire.\"},{\"row\":25,\"field\":\"solde\",\"message\":\"Le solde est obligatoire.\"}]', NULL, NULL, 1, NULL, '2026-08-18 15:30:48', '2026-08-18 15:30:48', '2026-08-18 15:30:48'),
(2, 1, 'IMP-20260818-fBMdV3', 'opening_balance', 'completed', 0, 0, 1, '[{\"row\":2,\"field\":\"matricule\",\"message\":\"Le matricule est obligatoire.\"}]', NULL, NULL, 1, NULL, '2026-08-18 15:48:58', '2026-08-18 15:48:58', '2026-08-18 15:48:58'),
(3, 1, 'IMP-20260818-VHTYFV', 'opening_balance', 'completed', 0, 0, 1, '[{\"row\":2,\"field\":\"matricule\",\"message\":\"Le matricule est obligatoire.\"}]', NULL, NULL, 1, NULL, '2026-08-18 15:51:02', '2026-08-18 15:51:02', '2026-08-18 15:51:02'),
(4, 1, 'IMP-20260819-YAsJMV', 'opening_balance', 'completed', 31, 0, 31, '[{\"row\":2,\"field\":\"matricule\",\"message\":\"Le matricule est obligatoire.\"},{\"row\":3,\"field\":\"matricule\",\"message\":\"Le matricule est obligatoire.\"},{\"row\":4,\"field\":\"matricule\",\"message\":\"Le matricule est obligatoire.\"},{\"row\":5,\"field\":\"matricule\",\"message\":\"Le matricule est obligatoire.\"},{\"row\":6,\"field\":\"matricule\",\"message\":\"Le matricule est obligatoire.\"},{\"row\":7,\"field\":\"matricule\",\"message\":\"Le matricule est obligatoire.\"},{\"row\":8,\"field\":\"matricule\",\"message\":\"Le matricule est obligatoire.\"},{\"row\":9,\"field\":\"matricule\",\"message\":\"Le matricule est obligatoire.\"},{\"row\":10,\"field\":\"matricule\",\"message\":\"Le matricule est obligatoire.\"},{\"row\":11,\"field\":\"matricule\",\"message\":\"Le matricule est obligatoire.\"},{\"row\":12,\"field\":\"matricule\",\"message\":\"Le matricule est obligatoire.\"},{\"row\":13,\"field\":\"matricule\",\"message\":\"Le matricule est obligatoire.\"},{\"row\":14,\"field\":\"matricule\",\"message\":\"Le matricule est obligatoire.\"},{\"row\":15,\"field\":\"matricule\",\"message\":\"Le matricule est obligatoire.\"},{\"row\":16,\"field\":\"matricule\",\"message\":\"Le matricule est obligatoire.\"},{\"row\":17,\"field\":\"matricule\",\"message\":\"Le matricule est obligatoire.\"},{\"row\":18,\"field\":\"matricule\",\"message\":\"Le matricule est obligatoire.\"},{\"row\":19,\"field\":\"matricule\",\"message\":\"Le matricule est obligatoire.\"},{\"row\":20,\"field\":\"matricule\",\"message\":\"Le matricule est obligatoire.\"},{\"row\":21,\"field\":\"matricule\",\"message\":\"Le matricule est obligatoire.\"},{\"row\":22,\"field\":\"matricule\",\"message\":\"Le matricule est obligatoire.\"},{\"row\":23,\"field\":\"matricule\",\"message\":\"Le matricule est obligatoire.\"},{\"row\":24,\"field\":\"matricule\",\"message\":\"Le matricule est obligatoire.\"},{\"row\":25,\"field\":\"matricule\",\"message\":\"Le matricule est obligatoire.\"},{\"row\":26,\"field\":\"matricule\",\"message\":\"Le matricule est obligatoire.\"},{\"row\":27,\"field\":\"matricule\",\"message\":\"Le matricule est obligatoire.\"},{\"row\":28,\"field\":\"matricule\",\"message\":\"Le matricule est obligatoire.\"},{\"row\":29,\"field\":\"matricule\",\"message\":\"Le matricule est obligatoire.\"},{\"row\":30,\"field\":\"matricule\",\"message\":\"Le matricule est obligatoire.\"},{\"row\":31,\"field\":\"matricule\",\"message\":\"Le matricule est obligatoire.\"},{\"row\":32,\"field\":\"matricule\",\"message\":\"Le matricule est obligatoire.\"}]', NULL, NULL, 1, NULL, '2026-08-19 08:48:16', '2026-08-19 08:48:11', '2026-08-19 08:48:16'),
(5, 1, 'IMP-20260819-63MHha', 'opening_balance', 'completed', 31, 0, 31, '[{\"row\":2,\"field\":\"matricule\",\"message\":\"Le matricule est obligatoire.\"},{\"row\":3,\"field\":\"matricule\",\"message\":\"Le matricule est obligatoire.\"},{\"row\":4,\"field\":\"matricule\",\"message\":\"Le matricule est obligatoire.\"},{\"row\":5,\"field\":\"matricule\",\"message\":\"Le matricule est obligatoire.\"},{\"row\":6,\"field\":\"matricule\",\"message\":\"Le matricule est obligatoire.\"},{\"row\":7,\"field\":\"matricule\",\"message\":\"Le matricule est obligatoire.\"},{\"row\":8,\"field\":\"matricule\",\"message\":\"Le matricule est obligatoire.\"},{\"row\":9,\"field\":\"matricule\",\"message\":\"Le matricule est obligatoire.\"},{\"row\":10,\"field\":\"matricule\",\"message\":\"Le matricule est obligatoire.\"},{\"row\":11,\"field\":\"matricule\",\"message\":\"Le matricule est obligatoire.\"},{\"row\":12,\"field\":\"matricule\",\"message\":\"Le matricule est obligatoire.\"},{\"row\":13,\"field\":\"matricule\",\"message\":\"Le matricule est obligatoire.\"},{\"row\":14,\"field\":\"matricule\",\"message\":\"Le matricule est obligatoire.\"},{\"row\":15,\"field\":\"matricule\",\"message\":\"Le matricule est obligatoire.\"},{\"row\":16,\"field\":\"matricule\",\"message\":\"Le matricule est obligatoire.\"},{\"row\":17,\"field\":\"matricule\",\"message\":\"Le matricule est obligatoire.\"},{\"row\":18,\"field\":\"matricule\",\"message\":\"Le matricule est obligatoire.\"},{\"row\":19,\"field\":\"matricule\",\"message\":\"Le matricule est obligatoire.\"},{\"row\":20,\"field\":\"matricule\",\"message\":\"Le matricule est obligatoire.\"},{\"row\":21,\"field\":\"matricule\",\"message\":\"Le matricule est obligatoire.\"},{\"row\":22,\"field\":\"matricule\",\"message\":\"Le matricule est obligatoire.\"},{\"row\":23,\"field\":\"matricule\",\"message\":\"Le matricule est obligatoire.\"},{\"row\":24,\"field\":\"matricule\",\"message\":\"Le matricule est obligatoire.\"},{\"row\":25,\"field\":\"matricule\",\"message\":\"Le matricule est obligatoire.\"},{\"row\":26,\"field\":\"matricule\",\"message\":\"Le matricule est obligatoire.\"},{\"row\":27,\"field\":\"matricule\",\"message\":\"Le matricule est obligatoire.\"},{\"row\":28,\"field\":\"matricule\",\"message\":\"Le matricule est obligatoire.\"},{\"row\":29,\"field\":\"matricule\",\"message\":\"Le matricule est obligatoire.\"},{\"row\":30,\"field\":\"matricule\",\"message\":\"Le matricule est obligatoire.\"},{\"row\":31,\"field\":\"matricule\",\"message\":\"Le matricule est obligatoire.\"},{\"row\":32,\"field\":\"matricule\",\"message\":\"Le matricule est obligatoire.\"}]', NULL, NULL, 1, NULL, '2026-08-19 08:50:56', '2026-08-19 08:50:56', '2026-08-19 08:50:56'),
(6, 1, 'IMP-20260819-9ifrNh', 'opening_balance', 'completed', 5, 3, 2, '[{\"row\":3,\"field\":\"code_conge\",\"message\":\"Type de cong\\u00e9 \'RTT\' non trouv\\u00e9.\"},{\"row\":5,\"field\":\"matricule\",\"message\":\"Employ\\u00e9 \'EMP0004\' non trouv\\u00e9 dans ce si\\u00e8ge.\"}]', NULL, NULL, 1, NULL, '2026-08-19 08:59:16', '2026-08-19 08:59:16', '2026-08-19 08:59:16'),
(7, 1, 'IMP-20260819-vqlhbY', 'opening_balance', 'completed', 5, 4, 1, '[{\"row\":5,\"field\":\"matricule\",\"message\":\"Employ\\u00e9 \'EMP0004\' non trouv\\u00e9 dans ce si\\u00e8ge.\"}]', NULL, NULL, 1, NULL, '2026-08-19 09:00:09', '2026-08-19 09:00:09', '2026-08-19 09:00:09'),
(8, 1, 'IMP-20260819-3hfzo8', 'opening_balance', 'completed', 4, 4, 0, '[]', NULL, NULL, 1, NULL, '2026-08-19 09:00:56', '2026-08-19 09:00:56', '2026-08-19 09:00:56'),
(9, 1, 'IMP-20260819-VfGC1V', 'opening_balance', 'completed', 4, 4, 0, '[]', NULL, NULL, 1, NULL, '2026-08-19 09:14:39', '2026-08-19 09:14:39', '2026-08-19 09:14:39'),
(10, 1, 'IMP-20260819-m3rAhb', 'opening_balance', 'completed', 4, 4, 0, '[]', NULL, NULL, 1, NULL, '2026-08-19 09:18:52', '2026-08-19 09:18:52', '2026-08-19 09:18:52'),
(11, 1, 'IMP-20260819-nsHocS', 'opening_balance', 'completed', 4, 4, 0, '[]', NULL, NULL, 1, NULL, '2026-08-19 09:19:25', '2026-08-19 09:19:25', '2026-08-19 09:19:25'),
(12, 1, 'IMP-20260819-zw6vsL', 'opening_balance', 'completed', 4, 4, 0, '[]', NULL, NULL, 1, NULL, '2026-08-19 09:23:56', '2026-08-19 09:23:56', '2026-08-19 09:23:56'),
(13, 1, 'IMP-20260819-ynoRtj', 'opening_balance', 'completed', 4, 4, 0, '[]', NULL, NULL, 1, NULL, '2026-08-19 09:25:19', '2026-08-19 09:25:19', '2026-08-19 09:25:19'),
(14, 1, 'IMP-20260819-knEhBx', 'opening_balance', 'completed', 4, 3, 1, '[{\"row\":4,\"field\":\"solde\",\"message\":\"Solde d\\u00e9j\\u00e0 existant pour Ramarotafika Hedi Franco\"}]', NULL, NULL, 1, NULL, '2026-08-19 09:31:18', '2026-08-19 09:31:18', '2026-08-19 09:31:18'),
(15, 1, 'IMP-20260819-8AbH3v', 'opening_balance', 'completed', 4, 0, 4, '[{\"row\":2,\"field\":\"solde\",\"message\":\"Solde d\\u00e9j\\u00e0 existant pour Willy Tarkin\"},{\"row\":3,\"field\":\"solde\",\"message\":\"Solde d\\u00e9j\\u00e0 existant pour Solofoniaina Vololonirina Emma Joeline\"},{\"row\":4,\"field\":\"solde\",\"message\":\"Solde d\\u00e9j\\u00e0 existant pour Ramarotafika Hedi Franco\"},{\"row\":5,\"field\":\"solde\",\"message\":\"Solde d\\u00e9j\\u00e0 existant pour MALALANIRINA Emile Noeline\"}]', NULL, NULL, 1, NULL, '2026-08-19 13:54:45', '2026-08-19 13:54:44', '2026-08-19 13:54:45'),
(16, 1, 'IMP-20260821-Sfndw5', 'opening_balance', 'completed', 4, 4, 0, '[]', NULL, NULL, 2, NULL, '2026-08-21 12:30:42', '2026-08-21 12:30:39', '2026-08-21 12:30:42'),
(17, 1, 'IMP-20260824-wIderV', 'opening_balance', 'completed', 4, 4, 0, '[]', NULL, NULL, 2, NULL, '2026-08-24 10:47:56', '2026-08-24 10:47:52', '2026-08-24 10:47:56'),
(18, 1, 'IMP-20260824-DRD1KF', 'opening_balance', 'completed', 4, 4, 0, '[]', NULL, NULL, 2, NULL, '2026-08-24 10:50:41', '2026-08-24 10:50:41', '2026-08-24 10:50:41'),
(19, 1, 'IMP-20260824-IFNm37', 'opening_balance', 'completed', 4, 0, 4, '[{\"row\":2,\"field\":\"G\\u00e9n\\u00e9ral\",\"message\":\"Method Illuminate\\\\Database\\\\Eloquent\\\\Collection::orWhere does not exist.\"},{\"row\":3,\"field\":\"G\\u00e9n\\u00e9ral\",\"message\":\"Method Illuminate\\\\Database\\\\Eloquent\\\\Collection::orWhere does not exist.\"},{\"row\":4,\"field\":\"G\\u00e9n\\u00e9ral\",\"message\":\"Method Illuminate\\\\Database\\\\Eloquent\\\\Collection::orWhere does not exist.\"},{\"row\":5,\"field\":\"G\\u00e9n\\u00e9ral\",\"message\":\"Method Illuminate\\\\Database\\\\Eloquent\\\\Collection::orWhere does not exist.\"}]', NULL, NULL, 2, NULL, '2026-08-24 12:29:29', '2026-08-24 12:29:25', '2026-08-24 12:29:29'),
(20, 1, 'IMP-20260824-UcMjy7', 'opening_balance', 'completed', 4, 0, 4, '[{\"row\":2,\"field\":\"G\\u00e9n\\u00e9ral\",\"message\":\"Method Illuminate\\\\Database\\\\Eloquent\\\\Collection::orWhere does not exist.\"},{\"row\":3,\"field\":\"G\\u00e9n\\u00e9ral\",\"message\":\"Method Illuminate\\\\Database\\\\Eloquent\\\\Collection::orWhere does not exist.\"},{\"row\":4,\"field\":\"G\\u00e9n\\u00e9ral\",\"message\":\"Method Illuminate\\\\Database\\\\Eloquent\\\\Collection::orWhere does not exist.\"},{\"row\":5,\"field\":\"G\\u00e9n\\u00e9ral\",\"message\":\"Method Illuminate\\\\Database\\\\Eloquent\\\\Collection::orWhere does not exist.\"}]', NULL, NULL, 2, NULL, '2026-08-24 12:31:16', '2026-08-24 12:31:16', '2026-08-24 12:31:16'),
(21, 1, 'IMP-20260824-OLYyuj', 'opening_balance', 'completed', 4, 4, 0, '[]', NULL, NULL, 2, NULL, '2026-08-24 12:32:45', '2026-08-24 12:32:45', '2026-08-24 12:32:45'),
(22, 1, 'IMP-20260824-Qocb3Z', 'opening_balance', 'completed', 4, 4, 0, '[]', NULL, NULL, 2, NULL, '2026-08-24 12:33:34', '2026-08-24 12:33:34', '2026-08-24 12:33:34'),
(23, 1, 'IMP-20260824-iwmjG7', 'opening_balance', 'completed', 4, 4, 0, '[]', NULL, NULL, 2, NULL, '2026-08-24 12:35:03', '2026-08-24 12:35:03', '2026-08-24 12:35:03'),
(24, 1, 'IMP-20260824-ue7wG5', 'opening_balance', 'completed', 4, 4, 0, '[]', NULL, NULL, 2, NULL, '2026-08-24 12:49:43', '2026-08-24 12:49:42', '2026-08-24 12:49:43'),
(25, 1, 'IMP-20260824-Oyqwvp', 'opening_balance', 'completed', 4, 4, 0, '[]', NULL, NULL, 2, NULL, '2026-08-24 13:04:02', '2026-08-24 13:04:02', '2026-08-24 13:04:02'),
(26, 1, 'IMP-20260826-0yjEfy', 'opening_balance', 'completed', 4, 4, 0, '[]', NULL, NULL, 1, NULL, '2026-08-26 08:08:03', '2026-08-26 08:08:00', '2026-08-26 08:08:03'),
(27, 1, 'IMP-20260826-XYEoGF', 'opening_balance', 'completed', 4, 4, 0, '[]', NULL, NULL, 2, NULL, '2026-08-26 09:27:27', '2026-08-26 09:27:27', '2026-08-26 09:27:27'),
(28, 1, 'IMP-20260826-FBj2mD', 'opening_balance', 'completed', 4, 4, 0, '[]', NULL, NULL, 2, NULL, '2026-08-26 15:00:43', '2026-08-26 15:00:40', '2026-08-26 15:00:43');

-- --------------------------------------------------------

--
-- Structure de la table `leave_periods`
--

CREATE TABLE `leave_periods` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `site_id` int(10) UNSIGNED DEFAULT NULL,
  `leave_type_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(100) NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `submission_deadline` date DEFAULT NULL COMMENT 'Date limite de pose',
  `allow_rollover` tinyint(1) NOT NULL DEFAULT 0,
  `max_rollover_days` int(11) DEFAULT NULL,
  `rollover_expiry_date` date DEFAULT NULL COMMENT 'Expiration des jours reportés',
  `is_default` tinyint(1) NOT NULL DEFAULT 0,
  `status` enum('preparing','open','closed') NOT NULL DEFAULT 'preparing',
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `is_customizable` tinyint(1) NOT NULL DEFAULT 0,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `leave_periods`
--

INSERT INTO `leave_periods` (`id`, `site_id`, `leave_type_id`, `name`, `start_date`, `end_date`, `submission_deadline`, `allow_rollover`, `max_rollover_days`, `rollover_expiry_date`, `is_default`, `status`, `is_active`, `is_customizable`, `deleted_at`, `created_at`, `updated_at`) VALUES
(16, 2, 19, 'Periode année 2026-2027', '2026-09-01', '2026-12-31', '2026-10-30', 0, NULL, NULL, 0, 'open', 1, 0, NULL, '2026-09-04 10:18:47', '2026-09-04 10:18:47'),
(17, 1, 20, 'Periode année 2026-2027', '2026-09-08', '2026-10-20', '2026-09-15', 0, NULL, NULL, 0, 'open', 1, 0, NULL, '2026-09-04 10:22:00', '2026-09-08 15:14:20');

-- --------------------------------------------------------

--
-- Structure de la table `leave_policies`
--

CREATE TABLE `leave_policies` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `site_id` int(10) UNSIGNED DEFAULT NULL,
  `leave_type_id` bigint(20) UNSIGNED DEFAULT NULL,
  `name` varchar(100) NOT NULL,
  `calculation_method` enum('working_days','business_days','hours') NOT NULL DEFAULT 'working_days',
  `reference_schedule_id` int(10) UNSIGNED DEFAULT NULL,
  `holiday_handling` enum('skip','count','split') NOT NULL DEFAULT 'skip',
  `rounding_rule` enum('none','half_day','full_day','quarter_hour','half_hour') NOT NULL DEFAULT 'none',
  `weekend_days` enum('saturday_sunday','friday_saturday','sunday_only','none') NOT NULL DEFAULT 'saturday_sunday',
  `exclude_holidays` tinyint(1) NOT NULL DEFAULT 1,
  `is_default` tinyint(1) NOT NULL DEFAULT 0,
  `is_customizable` tinyint(1) NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_by` bigint(20) UNSIGNED DEFAULT NULL,
  `updated_by` bigint(20) UNSIGNED DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `leave_policies`
--

INSERT INTO `leave_policies` (`id`, `site_id`, `leave_type_id`, `name`, `calculation_method`, `reference_schedule_id`, `holiday_handling`, `rounding_rule`, `weekend_days`, `exclude_holidays`, `is_default`, `is_customizable`, `is_active`, `created_by`, `updated_by`, `deleted_at`, `created_at`, `updated_at`) VALUES
(7, 1, NULL, 'Politique pour CP Geotrack 2026', 'working_days', NULL, 'skip', 'none', 'saturday_sunday', 1, 0, 0, 1, NULL, NULL, NULL, '2026-09-04 10:12:12', '2026-09-04 10:12:12'),
(8, 2, NULL, 'Politique pour CP Geotrack 2026', 'business_days', NULL, 'skip', 'none', 'sunday_only', 1, 0, 0, 1, NULL, NULL, NULL, '2026-09-04 10:16:16', '2026-09-04 10:16:16');

-- --------------------------------------------------------

--
-- Structure de la table `leave_policy_assignments`
--

CREATE TABLE `leave_policy_assignments` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `leave_policy_id` bigint(20) UNSIGNED NOT NULL,
  `company_id` int(10) UNSIGNED DEFAULT NULL,
  `site_id` int(10) UNSIGNED DEFAULT NULL,
  `department_id` int(10) UNSIGNED DEFAULT NULL,
  `job_title_id` int(10) UNSIGNED DEFAULT NULL,
  `hierarchy_level_id` int(10) UNSIGNED DEFAULT NULL,
  `employee_id` int(10) UNSIGNED DEFAULT NULL,
  `priority` int(11) NOT NULL DEFAULT 0 COMMENT 'Plus haut = prioritaire',
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `assignment_type` enum('individual','department','site','company','global') DEFAULT 'individual',
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `created_by` bigint(20) UNSIGNED DEFAULT NULL,
  `updated_by` bigint(20) UNSIGNED DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `leave_policy_assignments`
--

INSERT INTO `leave_policy_assignments` (`id`, `leave_policy_id`, `company_id`, `site_id`, `department_id`, `job_title_id`, `hierarchy_level_id`, `employee_id`, `priority`, `is_active`, `assignment_type`, `start_date`, `end_date`, `created_by`, `updated_by`, `deleted_at`, `created_at`, `updated_at`) VALUES
(16, 7, NULL, 1, NULL, NULL, NULL, NULL, 100, 1, 'site', NULL, NULL, NULL, NULL, NULL, '2026-09-04 12:38:27', '2026-09-04 12:38:27');

-- --------------------------------------------------------

--
-- Structure de la table `leave_requests`
--

CREATE TABLE `leave_requests` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `employee_id` int(10) UNSIGNED NOT NULL,
  `leave_type_id` bigint(20) UNSIGNED NOT NULL,
  `period_id` bigint(20) UNSIGNED NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `duration` decimal(10,2) NOT NULL DEFAULT 0.00,
  `status` enum('draft','pending','approved','rejected','cancelled') NOT NULL DEFAULT 'draft',
  `submitted_at` timestamp NULL DEFAULT NULL,
  `reason` text DEFAULT NULL,
  `comment` text DEFAULT NULL,
  `attachment_path` varchar(255) DEFAULT NULL,
  `approved_by` int(10) UNSIGNED DEFAULT NULL,
  `approved_at` timestamp NULL DEFAULT NULL,
  `rejected_by` int(10) UNSIGNED DEFAULT NULL,
  `rejected_at` timestamp NULL DEFAULT NULL,
  `rejection_reason` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `balance_impact` tinyint(1) DEFAULT 1 COMMENT 'La demande impacte-t-elle le solde ? (false pour les imports historiques)',
  `workflow_id` bigint(20) UNSIGNED DEFAULT NULL,
  `workflow_step` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `leave_requests`
--

INSERT INTO `leave_requests` (`id`, `employee_id`, `leave_type_id`, `period_id`, `start_date`, `end_date`, `duration`, `status`, `submitted_at`, `reason`, `comment`, `attachment_path`, `approved_by`, `approved_at`, `rejected_by`, `rejected_at`, `rejection_reason`, `created_at`, `updated_at`, `deleted_at`, `balance_impact`, `workflow_id`, `workflow_step`) VALUES
(82, 67, 20, 17, '2026-10-12', '2026-10-14', 3.00, 'pending', '2026-09-04 14:43:25', 'JE VEUX TESTER LE VALIDATION DU CONGE AYE EN 3 ETAPE', 'JUSTE TEST', NULL, NULL, NULL, NULL, NULL, NULL, '2026-09-04 14:43:25', '2026-09-04 14:43:25', NULL, 1, 5, 0),
(83, 66, 20, 17, '2026-09-08', '2026-09-14', 5.00, 'approved', '2026-09-08 15:16:16', NULL, 'test planning', NULL, NULL, '2026-09-08 15:21:09', NULL, NULL, NULL, '2026-09-08 15:16:16', '2026-09-08 15:21:09', NULL, 1, 5, 0);

-- --------------------------------------------------------

--
-- Structure de la table `leave_request_attachments`
--

CREATE TABLE `leave_request_attachments` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `leave_request_id` bigint(20) UNSIGNED NOT NULL,
  `file_name` varchar(255) NOT NULL,
  `file_path` varchar(255) NOT NULL,
  `file_size` int(11) DEFAULT NULL,
  `mime_type` varchar(100) DEFAULT NULL,
  `uploaded_by` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `leave_roles`
--

CREATE TABLE `leave_roles` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `site_id` int(10) UNSIGNED DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `label` varchar(255) NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `leave_roles`
--

INSERT INTO `leave_roles` (`id`, `site_id`, `name`, `label`, `is_active`, `created_at`, `updated_at`) VALUES
(7, 1, 'rh', 'Responsable RH', 1, '2026-09-04 12:46:57', '2026-09-04 12:46:57'),
(8, 1, 'manager', 'Manager site', 1, '2026-09-04 12:47:18', '2026-09-04 12:47:18'),
(9, 1, 'direction', 'Direction', 1, '2026-09-04 12:47:42', '2026-09-04 12:47:42');

-- --------------------------------------------------------

--
-- Structure de la table `leave_types`
--

CREATE TABLE `leave_types` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `site_id` int(10) UNSIGNED DEFAULT NULL,
  `name` varchar(100) NOT NULL,
  `code` varchar(20) NOT NULL,
  `unit` enum('days','half_days','hours') NOT NULL DEFAULT 'days',
  `deducts_balance` tinyint(1) NOT NULL DEFAULT 1,
  `requires_attachment` enum('never','always','after_duration') NOT NULL DEFAULT 'never',
  `requires_attachment_after` int(11) DEFAULT NULL,
  `allow_negative_balance` tinyint(1) NOT NULL DEFAULT 0,
  `max_negative_limit` int(11) DEFAULT NULL,
  `color` varchar(7) NOT NULL DEFAULT '#10B981',
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `is_customizable` tinyint(1) NOT NULL DEFAULT 0,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `min_notice_days` int(11) DEFAULT 0 COMMENT 'Délai de prévenance en jours',
  `max_duration_per_request` decimal(8,2) DEFAULT NULL COMMENT 'Durée maximale par demande',
  `allow_overlap` tinyint(1) NOT NULL DEFAULT 0 COMMENT 'Autoriser les chevauchements',
  `accrual_type` enum('manual','monthly_fixed','yearly_fixed','schedule_based') DEFAULT 'manual' COMMENT 'Type d''acquisition du solde',
  `accrual_amount` decimal(8,2) DEFAULT NULL COMMENT 'Montant de l''acquisition',
  `affects_team_availability` tinyint(1) DEFAULT 1 COMMENT 'L''absence affecte-t-elle l''effectif de l''équipe ?'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `leave_types`
--

INSERT INTO `leave_types` (`id`, `site_id`, `name`, `code`, `unit`, `deducts_balance`, `requires_attachment`, `requires_attachment_after`, `allow_negative_balance`, `max_negative_limit`, `color`, `is_active`, `is_customizable`, `deleted_at`, `created_at`, `updated_at`, `min_notice_days`, `max_duration_per_request`, `allow_overlap`, `accrual_type`, `accrual_amount`, `affects_team_availability`) VALUES
(19, 2, 'Conge paye RUN TELEMAT 2026-2027', 'CPR', 'days', 1, 'never', NULL, 0, NULL, '#106fb7', 1, 0, NULL, '2026-09-04 10:05:43', '2026-09-04 10:05:43', 30, 7.00, 1, 'manual', NULL, 1),
(20, 1, 'Conge paye Geotrrack  2026-2027', 'CPG', 'days', 1, 'never', NULL, 0, NULL, '#10b981', 1, 0, NULL, '2026-09-04 10:08:22', '2026-09-08 15:13:34', 0, 6.00, 0, 'manual', NULL, 1);

-- --------------------------------------------------------

--
-- Structure de la table `leave_type_siege_settings`
--

CREATE TABLE `leave_type_siege_settings` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `SiegeID` int(10) UNSIGNED NOT NULL,
  `leave_type_id` bigint(20) UNSIGNED NOT NULL,
  `is_visible` tinyint(1) NOT NULL DEFAULT 1,
  `forked_type_id` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `leave_type_site_activations`
--

CREATE TABLE `leave_type_site_activations` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `site_id` int(10) UNSIGNED NOT NULL,
  `leave_type_id` bigint(20) UNSIGNED NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `leave_validators`
--

CREATE TABLE `leave_validators` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `employee_id` int(10) UNSIGNED NOT NULL COMMENT 'Employé qui valide',
  `site_id` int(10) UNSIGNED NOT NULL COMMENT 'Site sur lequel il a ce rôle',
  `role` enum('manager','rh','drh','direction') NOT NULL COMMENT 'Rôle de validation',
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `leave_validators`
--

INSERT INTO `leave_validators` (`id`, `employee_id`, `site_id`, `role`, `is_active`, `created_at`, `updated_at`) VALUES
(4, 68, 1, 'manager', 1, '2026-09-04 12:48:08', '2026-09-04 12:48:08'),
(5, 67, 1, 'rh', 1, '2026-09-04 12:48:35', '2026-09-04 12:48:35'),
(6, 69, 1, 'direction', 1, '2026-09-04 12:48:46', '2026-09-04 12:48:46');

-- --------------------------------------------------------

--
-- Structure de la table `leave_workflows`
--

CREATE TABLE `leave_workflows` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `site_id` int(10) UNSIGNED DEFAULT NULL,
  `leave_type_id` bigint(20) UNSIGNED DEFAULT NULL,
  `name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `steps` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL COMMENT 'Structure: [{"role": "manager", "order": 1, "label": "Validation Manager"}, {"role": "hr", "order": 2, "label": "Validation RH"}]' CHECK (json_valid(`steps`)),
  `is_default` tinyint(1) NOT NULL DEFAULT 0,
  `is_customizable` tinyint(1) NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `leave_workflows`
--

INSERT INTO `leave_workflows` (`id`, `site_id`, `leave_type_id`, `name`, `description`, `steps`, `is_default`, `is_customizable`, `is_active`, `deleted_at`, `created_at`, `updated_at`) VALUES
(5, 1, 20, 'workflow CPG', 'Validation conge  paye geotrack', '[{\"order\":1,\"role\":\"manager\",\"label\":\"Validation Manager\",\"description\":\"Le manager valide la demande\"},{\"order\":2,\"role\":\"rh\",\"label\":\"Validation RH\",\"description\":\"Le service RH approuve la demande\"},{\"order\":3,\"role\":\"direction\",\"label\":\"Validation  Directeur\",\"description\":\"Le service Direction approuve la demande\"}]', 0, 0, 1, NULL, '2026-09-04 12:50:46', '2026-09-04 12:50:46');

-- --------------------------------------------------------

--
-- Structure de la table `leave_workflow_steps`
--

CREATE TABLE `leave_workflow_steps` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `workflow_id` bigint(20) UNSIGNED NOT NULL,
  `step_order` int(11) NOT NULL DEFAULT 1,
  `name` varchar(100) NOT NULL COMMENT 'Nom de l''étape (ex: Validation Manager)',
  `description` text DEFAULT NULL,
  `approver_role` enum('manager','rh','drh','direction','custom') DEFAULT 'manager' COMMENT 'Rôle attendu pour cette étape',
  `approver_employee_id` int(10) UNSIGNED DEFAULT NULL COMMENT 'Si approbateur fixe (au lieu d''un rôle)',
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2025_10_27_055942_create_all_tables', 1),
(5, '2025_11_06_122721_create_jours_non_travailles_table', 2),
(6, '2025_11_10_063737_create_seler_sieges_table', 3),
(7, '2026_03_09_000000_create_pointage_event_exceptions_table', 4),
(8, '2026_05_22_000000_create_conge_validations_table', 5),
(9, '2026_05_22_000001_add_siege_id_to_conge_validations_table', 6),
(10, '2026_07_15_070309_add_is_manager_to_administration_table', 7),
(11, '2026_07_16_123321_add_auth_fields_to_employes_table', 8),
(20, '2026_07_28_000001_create_employee_managers_table', 9),
(21, '2026_07_28_000002_create_employee_meta_table', 9),
(22, '2026_07_28_000003_create_admin_roles_table', 9),
(23, '2026_07_28_000004_create_leave_types_table', 9),
(24, '2026_07_28_000005_create_leave_policies_table', 9),
(25, '2026_07_28_000006_create_leave_policy_assignments_table', 9),
(26, '2026_07_28_000007_create_leave_periods_table', 9),
(27, '2026_07_28_000008_create_company_holidays_table', 9),
(28, '2026_07_28_000009_create_leave_balance_transactions_table', 9),
(29, '2026_07_28_000010_create_leave_import_batches_table', 9),
(30, '2026_07_29_093331_create_personal_access_tokens_table', 10),
(31, '2026_07_29_124100_add_entreprise_siege_id_to_leave_settings_tables', 11),
(32, '2026_07_30_152316_alter_leave_policies_add_rules_isactive_unique', 12),
(33, '2026_07_30_153223_migrate_leave_types_to_policies', 13),
(34, '2026_07_30_155215_make_company_id_nullable_in_leave_types', 14),
(35, '2026_07_30_161021_clean_leave_types_columns', 15),
(36, '2026_08_03_082922_create_rule_fields_table', 16),
(37, '2026_08_03_083005_create_policy_values_table', 16),
(38, '2026_08_03_134359_create_calculation_rules_table', 17),
(39, '2026_08_05_092626_add_checkbox_to_rule_fields_field_type_enum', 18),
(40, '2026_08_05_093227_add_checkbox_to_rule_fields_enum', 19),
(41, '2026_09_02_094737_add_department_id_to_job_titles_table', 20),
(42, '2026_09_03_071641_add_leave_type_id_to_leave_workflows_table', 21),
(44, '2026_09_03_121016_create_leave_roles_table', 22),
(45, '2026_08_11_161820_make_leave_types_siegeid_nullable', 23),
(46, '2026_08_12_125415_create_leave_type_siege_settings_table', 23),
(47, '2026_09_03_000001_create_horaires_types_table', 23),
(48, '2026_09_03_000002_create_plannings_table', 23),
(49, '2026_09_03_000003_create_planning_details_table', 23),
(50, '2026_09_03_000004_create_evenements_planning_table', 23),
(51, '2026_09_03_000005_create_evenement_employes_table', 23),
(52, '2026_09_03_000006_create_comparaisons_planning_table', 23),
(53, '2026_09_03_062132_add_department_id_to_job_titles_table', 23);

-- --------------------------------------------------------

--
-- Structure de la table `notifications`
--

CREATE TABLE `notifications` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL COMMENT 'ID de l''utilisateur (administration.ID)',
  `type` varchar(50) NOT NULL COMMENT 'leave_pending, leave_approved, leave_rejected',
  `title` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `leave_request_id` bigint(20) UNSIGNED DEFAULT NULL,
  `is_read` tinyint(1) NOT NULL DEFAULT 0,
  `read_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tokenable_type` varchar(255) NOT NULL,
  `tokenable_id` bigint(20) UNSIGNED NOT NULL,
  `name` text NOT NULL,
  `token` varchar(64) NOT NULL,
  `abilities` text DEFAULT NULL,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `plannings`
--

CREATE TABLE `plannings` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `siege_id` int(10) UNSIGNED NOT NULL,
  `service_id` int(10) UNSIGNED DEFAULT NULL,
  `poste_id` int(10) UNSIGNED DEFAULT NULL,
  `date_debut_semaine` date NOT NULL,
  `date_fin_semaine` date NOT NULL,
  `nom` varchar(255) NOT NULL,
  `statut` enum('brouillon','genere','valide','publie','archive') NOT NULL DEFAULT 'brouillon',
  `cree_par` int(10) UNSIGNED NOT NULL,
  `valide_par` int(10) UNSIGNED DEFAULT NULL,
  `valide_le` timestamp NULL DEFAULT NULL,
  `publie_le` timestamp NULL DEFAULT NULL,
  `commentaire` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `plannings`
--

INSERT INTO `plannings` (`id`, `siege_id`, `service_id`, `poste_id`, `date_debut_semaine`, `date_fin_semaine`, `nom`, `statut`, `cree_par`, `valide_par`, `valide_le`, `publie_le`, `commentaire`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 1, 16, 348, '2026-09-07', '2026-09-13', 'Planning 07/09/2026', 'genere', 2, NULL, NULL, NULL, NULL, '2026-09-08 15:09:21', '2026-09-08 15:09:21', NULL);

-- --------------------------------------------------------

--
-- Structure de la table `planning_details`
--

CREATE TABLE `planning_details` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `planning_id` bigint(20) UNSIGNED NOT NULL,
  `employe_id` int(10) UNSIGNED NOT NULL,
  `date` date NOT NULL,
  `heure_debut` time NOT NULL,
  `heure_fin` time NOT NULL,
  `pause_debut` time DEFAULT NULL,
  `pause_fin` time DEFAULT NULL,
  `deuxieme_debut` time DEFAULT NULL,
  `deuxieme_fin` time DEFAULT NULL,
  `commentaire` text DEFAULT NULL,
  `statut` enum('planifie','confirme','effectue','annule','absent') NOT NULL DEFAULT 'planifie',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `planning_details`
--

INSERT INTO `planning_details` (`id`, `planning_id`, `employe_id`, `date`, `heure_debut`, `heure_fin`, `pause_debut`, `pause_fin`, `deuxieme_debut`, `deuxieme_fin`, `commentaire`, `statut`, `created_at`, `updated_at`) VALUES
(1, 1, 66, '2026-09-07', '08:30:00', '17:00:00', '12:00:00', '13:00:00', '13:00:00', '17:00:00', NULL, 'planifie', '2026-09-08 15:09:21', '2026-09-08 15:09:21'),
(2, 1, 66, '2026-09-08', '08:30:00', '17:00:00', '12:00:00', '13:00:00', '13:00:00', '17:00:00', NULL, 'planifie', '2026-09-08 15:09:21', '2026-09-08 15:09:21'),
(3, 1, 66, '2026-09-09', '08:30:00', '17:00:00', '12:00:00', '13:00:00', '13:00:00', '17:00:00', NULL, 'planifie', '2026-09-08 15:09:21', '2026-09-08 15:09:21'),
(4, 1, 66, '2026-09-10', '08:30:00', '17:00:00', '12:00:00', '13:00:00', '13:00:00', '17:00:00', NULL, 'planifie', '2026-09-08 15:09:21', '2026-09-08 15:09:21'),
(5, 1, 66, '2026-09-11', '08:30:00', '17:00:00', '12:00:00', '13:00:00', '13:00:00', '17:00:00', NULL, 'planifie', '2026-09-08 15:09:21', '2026-09-08 15:09:21');

-- --------------------------------------------------------

--
-- Structure de la table `pointages`
--

CREATE TABLE `pointages` (
  `ID` int(10) UNSIGNED NOT NULL,
  `employee_id` int(10) UNSIGNED NOT NULL,
  `type_` varchar(25) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `auth_method` varchar(25) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `timestamp_` datetime DEFAULT current_timestamp(),
  `latitude` decimal(18,8) NOT NULL,
  `longitude` decimal(18,8) NOT NULL,
  `photo_path` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `synced` tinyint(1) NOT NULL DEFAULT 0,
  `SiegeID` int(10) UNSIGNED NOT NULL,
  `company_id` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Doublure de structure pour la vue `pointages_with_employee`
-- (Voir ci-dessous la vue réelle)
--
CREATE TABLE `pointages_with_employee` (
`id` int(10) unsigned
,`type_` varchar(25)
,`auth_method` varchar(25)
,`timestamp_` datetime
,`latitude` decimal(18,8)
,`longitude` decimal(18,8)
,`employee_name` varchar(255)
,`num_mat` text
,`SiegeID` int(10) unsigned
);

-- --------------------------------------------------------

--
-- Structure de la table `pointage_event_exceptions`
--

CREATE TABLE `pointage_event_exceptions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `employee_id` bigint(20) UNSIGNED NOT NULL,
  `date` date NOT NULL,
  `error_type` enum('doublon_entree','doublon_sortie','manque_sortie','manque_entree','pointage_jour_ferie','pointage_weekend') NOT NULL,
  `SiegeID` int(10) UNSIGNED NOT NULL,
  `acknowledged_by` bigint(20) UNSIGNED NOT NULL,
  `acknowledged_at` datetime NOT NULL,
  `note` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `pointage_event_exceptions`
--

INSERT INTO `pointage_event_exceptions` (`id`, `employee_id`, `date`, `error_type`, `SiegeID`, `acknowledged_by`, `acknowledged_at`, `note`) VALUES
(1, 3, '2026-02-28', 'pointage_weekend', 1, 2, '2026-03-09 14:40:51', NULL),
(2, 3, '2025-09-06', 'pointage_weekend', 1, 2, '2026-03-09 14:41:04', NULL),
(3, 3, '2025-09-13', 'pointage_weekend', 1, 2, '2026-03-09 14:41:09', NULL),
(4, 1, '2025-09-19', 'manque_sortie', 1, 2, '2026-03-10 06:22:25', NULL),
(5, 3, '2025-09-20', 'pointage_weekend', 1, 2, '2026-03-10 06:22:43', NULL),
(6, 1, '2025-10-19', 'pointage_weekend', 1, 2, '2026-03-10 06:22:53', NULL),
(7, 57, '2026-03-10', 'manque_sortie', 1, 2, '2026-03-10 07:26:37', NULL),
(8, 2, '2025-11-15', 'pointage_weekend', 1, 2, '2026-03-10 07:37:51', NULL),
(9, 3, '2025-11-15', 'pointage_weekend', 1, 2, '2026-03-10 07:37:57', NULL),
(10, 2, '2025-12-10', 'manque_sortie', 1, 2, '2026-03-10 07:39:25', NULL),
(11, 3, '2026-02-14', 'pointage_weekend', 1, 2, '2026-05-21 14:11:25', NULL),
(12, 2, '2026-02-07', 'pointage_weekend', 1, 2, '2026-05-21 14:11:37', NULL),
(13, 3, '2026-02-07', 'pointage_weekend', 1, 2, '2026-05-21 14:11:41', NULL);

-- --------------------------------------------------------

--
-- Doublure de structure pour la vue `rapports`
-- (Voir ci-dessous la vue réelle)
--
CREATE TABLE `rapports` (
`SiegeID` int(10) unsigned
,`employee_id` int(10) unsigned
,`date_pointage` date
,`heure_entree` varchar(13)
,`pause_dejeuner` varchar(29)
,`heure_sortie` varchar(13)
,`total_heure_journee` time
);

-- --------------------------------------------------------

--
-- Doublure de structure pour la vue `rapports_details`
-- (Voir ci-dessous la vue réelle)
--
CREATE TABLE `rapports_details` (
`date_pointage` varchar(10)
,`date_reel` date
,`SiegeID` int(10) unsigned
,`siege_nom` varchar(255)
,`employee_id` int(10) unsigned
,`num_mat` text
,`employee_nom` varchar(255)
,`heure_entree` varchar(13)
,`pause_dejeuner` varchar(29)
,`heure_sortie` varchar(13)
,`total_heure_journee` time
);

-- --------------------------------------------------------

--
-- Doublure de structure pour la vue `rapports_details_jour_nuit`
-- (Voir ci-dessous la vue réelle)
--
CREATE TABLE `rapports_details_jour_nuit` (
`date_pointage` varchar(10)
,`date_reel` date
,`SiegeID` int(10) unsigned
,`siege_nom` varchar(255)
,`employee_id` int(10) unsigned
,`employee_nom` varchar(255)
,`num_mat` text
,`type_travail` varchar(4)
,`heure_entree` varchar(13)
,`pause_dejeuner` varchar(29)
,`heure_sortie` varchar(19)
,`total_heure_journee` time
);

-- --------------------------------------------------------

--
-- Doublure de structure pour la vue `rapports_details_with_adress`
-- (Voir ci-dessous la vue réelle)
--
CREATE TABLE `rapports_details_with_adress` (
`date_pointage` varchar(10)
,`date_reel` date
,`SiegeID` int(10) unsigned
,`siege_nom` varchar(255)
,`employee_id` int(10) unsigned
,`employee_nom` varchar(255)
,`num_mat` text
,`heure_entree` varchar(13)
,`adresse_entree_matin` mediumtext
,`pause_dejeuner` varchar(29)
,`adresse_sortie_matin` mediumtext
,`adresse_entree_apres_midi` mediumtext
,`heure_sortie` varchar(13)
,`adresse_sortie_apres_midi` mediumtext
,`total_heure_journee` time
);

-- --------------------------------------------------------

--
-- Doublure de structure pour la vue `rapports_jour_nuit`
-- (Voir ci-dessous la vue réelle)
--
CREATE TABLE `rapports_jour_nuit` (
`SiegeID` int(10) unsigned
,`employee_id` int(10) unsigned
,`date_pointage` date
,`type_travail` varchar(4)
,`heure_entree` varchar(13)
,`pause_dejeuner` varchar(29)
,`heure_sortie` varchar(19)
,`total_heure_journee` time
);

-- --------------------------------------------------------

--
-- Structure de la table `seller_sieges`
--

CREATE TABLE `seller_sieges` (
  `ID` bigint(20) UNSIGNED NOT NULL,
  `SellerID` int(10) UNSIGNED NOT NULL,
  `SiegeID` int(10) UNSIGNED NOT NULL,
  `CreatedAt` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `seller_sieges`
--

INSERT INTO `seller_sieges` (`ID`, `SellerID`, `SiegeID`, `CreatedAt`) VALUES
(11, 13, 3, '2026-03-06 15:52:42');

-- --------------------------------------------------------

--
-- Structure de la table `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('ewS9zDVyvj9ZXSrav1w73r2h1U1FnRYXBxfSoZZT', 2, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiZWF2STdSRlBhZ0hBNjUxWVVWMkZ4Y3g0QUxlUGdUV0Y4SHJiTDhTTyI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mzk6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9hZG1pbi9sZWF2ZS10eXBlcyI7czo1OiJyb3V0ZSI7czoyMzoiYWRtaW4ubGVhdmUtdHlwZXMuaW5kZXgiO31zOjUwOiJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI7aToyO30=', 1788941431),
('FkYu8Ct8nglHVfTy923IkXAAIMigJBtsDskrKNEM', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', 'YTo2OntzOjY6Il90b2tlbiI7czo0MDoibDE0T0FXbDFkYk8yVGl2cjdFb3ZObTlRTm9QSTZteUh1cUpMNlo3RyI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mjc6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9sb2dpbiI7czo1OiJyb3V0ZSI7czo1OiJsb2dpbiI7fXM6NTQ6ImxvZ2luX2VtcGxveWVfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI7aTo2NjtzOjExOiJlbXBsb3llZV9pZCI7aTo2NjtzOjM6InVybCI7YToxOntzOjg6ImludGVuZGVkIjtzOjI4OiJodHRwOi8vMTI3LjAuMC4xOjgwMDAvc2llZ2VzIjt9fQ==', 1788933766),
('TufZnAOrMwg4JEpDd6097xsFwvXGZODObHPnkaPC', 66, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36 Edg/152.0.0.0', 'YTo2OntzOjY6Il90b2tlbiI7czo0MDoiYkNMWkRWRERkT2QxbUZXOHV4T1NwZlFJQ0xKUDJEa040YmEyUjhDUSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MTIzOiJodHRwOi8vMTI3LjAuMC4xOjgwMDAvZW1wbG95ZS9jYWxlbmRhci9ldmVudHM/ZW5kPTIwMjYtMTAtMTJUMDAlM0EwMCUzQTAwJTJCMDMlM0EwMCZzdGFydD0yMDI2LTA4LTMxVDAwJTNBMDAlM0EwMCUyQjAzJTNBMDAiO3M6NToicm91dGUiO3M6MjM6ImVtcGxveWUuY2FsZW5kYXIuZXZlbnRzIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo1NDoibG9naW5fZW1wbG95ZV81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjtpOjY2O3M6MTE6ImVtcGxveWVlX2lkIjtpOjY2O3M6MzoidXJsIjthOjE6e3M6ODoiaW50ZW5kZWQiO3M6MzE6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9kYXNoYm9hcmQiO319', 1788939014),
('WNBQG6JTZnwovvMylTgj4uuY6OVpASscBQ9Bah4P', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', 'YTo1OntzOjY6Il90b2tlbiI7czo0MDoiWkE2MjhOa3E2ZUhCMkNoQzlhVTZVWGtEQkRYcmM5VHdWSXh2dTFaTyI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mjc6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9sb2dpbiI7czo1OiJyb3V0ZSI7czo1OiJsb2dpbiI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fXM6MzoidXJsIjthOjE6e3M6ODoiaW50ZW5kZWQiO3M6Mjg6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9zaWVnZXMiO31zOjU0OiJsb2dpbl9lbXBsb3llXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6NjY7fQ==', 1788933905);

-- --------------------------------------------------------

--
-- Structure de la table `site_company_holiday_settings`
--

CREATE TABLE `site_company_holiday_settings` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `site_id` int(10) UNSIGNED NOT NULL,
  `company_holiday_id` bigint(20) UNSIGNED NOT NULL,
  `date` date DEFAULT NULL COMMENT 'NULL = hérite du global',
  `name` varchar(255) DEFAULT NULL COMMENT 'NULL = hérite du global',
  `is_recurring` tinyint(1) DEFAULT NULL COMMENT 'NULL = hérite du global',
  `is_active` tinyint(1) DEFAULT NULL COMMENT 'NULL = hérite du global',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `site_leave_periods`
--

CREATE TABLE `site_leave_periods` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `site_id` int(10) UNSIGNED NOT NULL,
  `leave_period_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(100) DEFAULT NULL COMMENT 'NULL = hérite du global',
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `submission_deadline` date DEFAULT NULL,
  `allow_rollover` tinyint(1) DEFAULT NULL COMMENT 'NULL = hérite',
  `max_rollover_days` int(11) DEFAULT NULL,
  `rollover_expiry_date` date DEFAULT NULL,
  `is_default` tinyint(1) DEFAULT NULL,
  `status` enum('preparing','open','closed') DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `site_leave_policy_settings`
--

CREATE TABLE `site_leave_policy_settings` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `site_id` int(10) UNSIGNED NOT NULL,
  `leave_policy_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `calculation_method` enum('working_days','business_days','hours') DEFAULT NULL,
  `reference_schedule_id` int(10) UNSIGNED DEFAULT NULL,
  `holiday_handling` enum('skip','count','split') DEFAULT NULL,
  `rounding_rule` enum('none','half_day','full_day','quarter_hour','half_hour') DEFAULT NULL,
  `weekend_days` enum('saturday_sunday','friday_saturday','sunday_only','none') DEFAULT NULL,
  `exclude_holidays` tinyint(1) DEFAULT NULL,
  `is_default` tinyint(1) DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `site_leave_type_settings`
--

CREATE TABLE `site_leave_type_settings` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `site_id` int(10) UNSIGNED NOT NULL,
  `leave_type_id` bigint(20) UNSIGNED NOT NULL,
  `is_enabled` tinyint(1) DEFAULT NULL COMMENT 'NULL = hérite du global',
  `local_name` varchar(100) DEFAULT NULL COMMENT 'NULL = hérite du global',
  `local_color` varchar(7) DEFAULT NULL COMMENT 'NULL = hérite du global',
  `local_requires_attachment` enum('never','always','after_duration') DEFAULT NULL,
  `local_requires_attachment_after` int(11) DEFAULT NULL,
  `local_allow_negative_balance` tinyint(1) DEFAULT NULL,
  `local_max_negative_limit` int(11) DEFAULT NULL,
  `local_min_notice_days` int(11) DEFAULT NULL,
  `local_max_duration_per_request` decimal(8,2) DEFAULT NULL,
  `local_allow_overlap` tinyint(1) DEFAULT NULL,
  `local_deducts_balance` tinyint(1) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `site_leave_workflow_settings`
--

CREATE TABLE `site_leave_workflow_settings` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `site_id` int(10) UNSIGNED NOT NULL,
  `leave_workflow_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(100) DEFAULT NULL COMMENT 'NULL = hérite du global',
  `description` text DEFAULT NULL COMMENT 'NULL = hérite du global',
  `steps` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL COMMENT 'NULL = hérite du global' CHECK (json_valid(`steps`)),
  `is_default` tinyint(1) DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Doublure de structure pour la vue `v_leave_balance_anomalies`
-- (Voir ci-dessous la vue réelle)
--
CREATE TABLE `v_leave_balance_anomalies` (
`employee_id` int(10) unsigned
,`leave_type_id` bigint(20) unsigned
,`period_id` bigint(20) unsigned
,`transaction_count` bigint(21)
,`total_opening` decimal(32,2)
,`total_carryover` decimal(32,2)
,`total_adjustment_positive` decimal(32,2)
,`total_debits` decimal(32,2)
,`total_reversals` decimal(32,2)
,`total_credits_remaining` decimal(32,2)
);

-- --------------------------------------------------------

--
-- Structure de la vue `pointages_with_employee`
--
DROP TABLE IF EXISTS `pointages_with_employee`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `pointages_with_employee`  AS SELECT `p`.`ID` AS `id`, `p`.`type_` AS `type_`, `p`.`auth_method` AS `auth_method`, `p`.`timestamp_` AS `timestamp_`, `p`.`latitude` AS `latitude`, `p`.`longitude` AS `longitude`, `e`.`Nom` AS `employee_name`, `e`.`num_mat` AS `num_mat`, `p`.`SiegeID` AS `SiegeID` FROM (`pointages` `p` join `employes` `e` on(`p`.`employee_id` = `e`.`ID`)) ORDER BY `p`.`timestamp_` DESC ;

-- --------------------------------------------------------

--
-- Structure de la vue `rapports`
--
DROP TABLE IF EXISTS `rapports`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `rapports`  AS SELECT `pointages`.`SiegeID` AS `SiegeID`, `pointages`.`employee_id` AS `employee_id`, cast(`pointages`.`timestamp_` as date) AS `date_pointage`, date_format(min(case when `pointages`.`type_` = 'entry' then `pointages`.`timestamp_` end),'%H:%i:%s') AS `heure_entree`, concat(date_format(max(case when `pointages`.`type_` = 'exit' and cast(`pointages`.`timestamp_` as time) < '14:00:00' then `pointages`.`timestamp_` end),'%H:%i:%s'),' - ',date_format(min(case when `pointages`.`type_` = 'entry' and cast(`pointages`.`timestamp_` as time) > '12:00:00' then `pointages`.`timestamp_` end),'%H:%i:%s')) AS `pause_dejeuner`, date_format(max(case when `pointages`.`type_` = 'exit' then `pointages`.`timestamp_` end),'%H:%i:%s') AS `heure_sortie`, sec_to_time(coalesce(sum(case when `pointages`.`type_` = 'exit' then time_to_sec(cast(`pointages`.`timestamp_` as time)) else 0 end) - sum(case when `pointages`.`type_` = 'entry' then time_to_sec(cast(`pointages`.`timestamp_` as time)) else 0 end),0)) AS `total_heure_journee` FROM `pointages` GROUP BY `pointages`.`SiegeID`, `pointages`.`employee_id`, cast(`pointages`.`timestamp_` as date) ;

-- --------------------------------------------------------

--
-- Structure de la vue `rapports_details`
--
DROP TABLE IF EXISTS `rapports_details`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `rapports_details`  AS SELECT date_format(cast(`rapports`.`date_pointage` as date),'%d-%m-%Y') AS `date_pointage`, `rapports`.`date_pointage` AS `date_reel`, `rapports`.`SiegeID` AS `SiegeID`, `entreprises_sieges`.`Nom` AS `siege_nom`, `rapports`.`employee_id` AS `employee_id`, `employes`.`num_mat` AS `num_mat`, `employes`.`Nom` AS `employee_nom`, `rapports`.`heure_entree` AS `heure_entree`, `rapports`.`pause_dejeuner` AS `pause_dejeuner`, `rapports`.`heure_sortie` AS `heure_sortie`, `rapports`.`total_heure_journee` AS `total_heure_journee` FROM ((`rapports` join `entreprises_sieges` on(`rapports`.`SiegeID` = `entreprises_sieges`.`ID`)) join `employes` on(`rapports`.`employee_id` = `employes`.`ID`)) ORDER BY `rapports`.`date_pointage` ASC ;

-- --------------------------------------------------------

--
-- Structure de la vue `rapports_details_jour_nuit`
--
DROP TABLE IF EXISTS `rapports_details_jour_nuit`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `rapports_details_jour_nuit`  AS SELECT date_format(cast(`r`.`date_pointage` as date),'%d-%m-%Y') AS `date_pointage`, `r`.`date_pointage` AS `date_reel`, `r`.`SiegeID` AS `SiegeID`, `es`.`Nom` AS `siege_nom`, `r`.`employee_id` AS `employee_id`, `emp`.`Nom` AS `employee_nom`, `emp`.`num_mat` AS `num_mat`, `r`.`type_travail` AS `type_travail`, `r`.`heure_entree` AS `heure_entree`, `r`.`pause_dejeuner` AS `pause_dejeuner`, `r`.`heure_sortie` AS `heure_sortie`, `r`.`total_heure_journee` AS `total_heure_journee` FROM ((`rapports_jour_nuit` `r` join `entreprises_sieges` `es` on(`r`.`SiegeID` = `es`.`ID`)) join `employes` `emp` on(`r`.`employee_id` = `emp`.`ID`)) ORDER BY `r`.`date_pointage` ASC, `r`.`heure_entree` ASC ;

-- --------------------------------------------------------

--
-- Structure de la vue `rapports_details_with_adress`
--
DROP TABLE IF EXISTS `rapports_details_with_adress`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `rapports_details_with_adress`  AS SELECT date_format(cast(`r`.`date_pointage` as date),'%d-%m-%Y') AS `date_pointage`, `r`.`date_pointage` AS `date_reel`, `r`.`SiegeID` AS `SiegeID`, `es`.`Nom` AS `siege_nom`, `r`.`employee_id` AS `employee_id`, `emp`.`Nom` AS `employee_nom`, `emp`.`num_mat` AS `num_mat`, `r`.`heure_entree` AS `heure_entree`, (select `e`.`Nom_Lieu_Ville` from (`pointages` `p` join `entreprises` `e` on(`p`.`company_id` = `e`.`ID`)) where `p`.`employee_id` = `r`.`employee_id` and cast(`p`.`timestamp_` as date) = `r`.`date_pointage` and `p`.`type_` = 'entry' and cast(`p`.`timestamp_` as time) <= '12:00:00' order by `p`.`timestamp_` limit 1) AS `adresse_entree_matin`, `r`.`pause_dejeuner` AS `pause_dejeuner`, (select `e`.`Nom_Lieu_Ville` from (`pointages` `p` join `entreprises` `e` on(`p`.`company_id` = `e`.`ID`)) where `p`.`employee_id` = `r`.`employee_id` and cast(`p`.`timestamp_` as date) = `r`.`date_pointage` and `p`.`type_` = 'exit' and cast(`p`.`timestamp_` as time) <= '14:00:00' order by `p`.`timestamp_` limit 1) AS `adresse_sortie_matin`, (select `e`.`Nom_Lieu_Ville` from (`pointages` `p` join `entreprises` `e` on(`p`.`company_id` = `e`.`ID`)) where `p`.`employee_id` = `r`.`employee_id` and cast(`p`.`timestamp_` as date) = `r`.`date_pointage` and `p`.`type_` = 'entry' and cast(`p`.`timestamp_` as time) >= '12:00:00' order by `p`.`timestamp_` limit 1) AS `adresse_entree_apres_midi`, `r`.`heure_sortie` AS `heure_sortie`, (select `e`.`Nom_Lieu_Ville` from (`pointages` `p` join `entreprises` `e` on(`p`.`company_id` = `e`.`ID`)) where `p`.`employee_id` = `r`.`employee_id` and cast(`p`.`timestamp_` as date) = `r`.`date_pointage` and `p`.`type_` = 'exit' and cast(`p`.`timestamp_` as time) >= '14:00:00' order by `p`.`timestamp_` desc limit 1) AS `adresse_sortie_apres_midi`, `r`.`total_heure_journee` AS `total_heure_journee` FROM ((`rapports` `r` join `entreprises_sieges` `es` on(`r`.`SiegeID` = `es`.`ID`)) join `employes` `emp` on(`r`.`employee_id` = `emp`.`ID`)) ORDER BY `r`.`date_pointage` ASC ;

-- --------------------------------------------------------

--
-- Structure de la vue `rapports_jour_nuit`
--
DROP TABLE IF EXISTS `rapports_jour_nuit`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `rapports_jour_nuit`  AS SELECT `pointages`.`SiegeID` AS `SiegeID`, `pointages`.`employee_id` AS `employee_id`, CASE WHEN cast(min(`pointages`.`timestamp_`) as time) between '00:00:00' and '06:59:59' THEN cast(min(`pointages`.`timestamp_`) as date) - interval 1 day ELSE cast(min(`pointages`.`timestamp_`) as date) END AS `date_pointage`, CASE WHEN cast(min(`pointages`.`timestamp_`) as time) >= '21:00:00' OR cast(min(`pointages`.`timestamp_`) as time) <= '06:59:59' THEN 'NUIT' ELSE 'JOUR' END AS `type_travail`, date_format(min(case when `pointages`.`type_` = 'entry' then `pointages`.`timestamp_` end),'%H:%i:%s') AS `heure_entree`, CASE WHEN cast(min(`pointages`.`timestamp_`) as time) >= '21:00:00' OR cast(min(`pointages`.`timestamp_`) as time) <= '06:59:59' THEN concat(coalesce(date_format(max(case when `pointages`.`type_` = 'exit' and (cast(`pointages`.`timestamp_` as time) between '22:00:00' and '23:59:59' or cast(`pointages`.`timestamp_` as time) between '00:00:00' and '03:00:00') then `pointages`.`timestamp_` end),'%H:%i:%s'),''),case when max(case when `pointages`.`type_` = 'exit' and (cast(`pointages`.`timestamp_` as time) between '22:00:00' and '23:59:59' or cast(`pointages`.`timestamp_` as time) between '00:00:00' and '03:00:00') then `pointages`.`timestamp_` end) is not null and min(case when `pointages`.`type_` = 'entry' and (cast(`pointages`.`timestamp_` as time) between '23:00:00' and '23:59:59' or cast(`pointages`.`timestamp_` as time) between '00:00:00' and '04:00:00') then `pointages`.`timestamp_` end) is not null then concat(' - ',date_format(min(case when `pointages`.`type_` = 'entry' and (cast(`pointages`.`timestamp_` as time) between '23:00:00' and '23:59:59' or cast(`pointages`.`timestamp_` as time) between '00:00:00' and '04:00:00') then `pointages`.`timestamp_` end),'%H:%i:%s')) else '' end) ELSE concat(coalesce(date_format(max(case when `pointages`.`type_` = 'exit' and cast(`pointages`.`timestamp_` as time) < '14:00:00' then `pointages`.`timestamp_` end),'%H:%i:%s'),''),case when max(case when `pointages`.`type_` = 'exit' and cast(`pointages`.`timestamp_` as time) < '14:00:00' then `pointages`.`timestamp_` end) is not null and min(case when `pointages`.`type_` = 'entry' and cast(`pointages`.`timestamp_` as time) > '12:00:00' then `pointages`.`timestamp_` end) is not null then concat(' - ',date_format(min(case when `pointages`.`type_` = 'entry' and cast(`pointages`.`timestamp_` as time) > '12:00:00' then `pointages`.`timestamp_` end),'%H:%i:%s')) else '' end) END AS `pause_dejeuner`, CASE WHEN cast(min(`pointages`.`timestamp_`) as date) <> cast(max(`pointages`.`timestamp_`) as date) THEN concat(date_format(max(case when `pointages`.`type_` = 'exit' then `pointages`.`timestamp_` end),'%H:%i:%s'),' (+1j)') ELSE date_format(max(case when `pointages`.`type_` = 'exit' then `pointages`.`timestamp_` end),'%H:%i:%s') END AS `heure_sortie`, sec_to_time(case when cast(min(`pointages`.`timestamp_`) as date) <> cast(max(`pointages`.`timestamp_`) as date) then timestampdiff(SECOND,min(case when `pointages`.`type_` = 'entry' then `pointages`.`timestamp_` end),max(case when `pointages`.`type_` = 'exit' then `pointages`.`timestamp_` end)) else coalesce(sum(case when `pointages`.`type_` = 'exit' then time_to_sec(cast(`pointages`.`timestamp_` as time)) else 0 end) - sum(case when `pointages`.`type_` = 'entry' then time_to_sec(cast(`pointages`.`timestamp_` as time)) else 0 end),0) end) AS `total_heure_journee` FROM `pointages` GROUP BY `pointages`.`SiegeID`, `pointages`.`employee_id`, CASE WHEN cast(`pointages`.`timestamp_` as time) between '00:00:00' and '06:59:59' THEN cast(`pointages`.`timestamp_` as date) - interval 1 day ELSE cast(`pointages`.`timestamp_` as date) END, CASE WHEN cast(`pointages`.`timestamp_` as time) >= '21:00:00' OR cast(`pointages`.`timestamp_` as time) <= '06:59:59' THEN 'NUIT' ELSE 'JOUR' END ;

-- --------------------------------------------------------

--
-- Structure de la vue `v_leave_balance_anomalies`
--
DROP TABLE IF EXISTS `v_leave_balance_anomalies`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_leave_balance_anomalies`  AS SELECT `lbt`.`employee_id` AS `employee_id`, `lbt`.`leave_type_id` AS `leave_type_id`, `lbt`.`period_id` AS `period_id`, count(0) AS `transaction_count`, sum(case when `lbt`.`type` = 'opening' then `lbt`.`amount` else 0 end) AS `total_opening`, sum(case when `lbt`.`type` = 'carryover' then `lbt`.`amount` else 0 end) AS `total_carryover`, sum(case when `lbt`.`type` = 'adjustment' and `lbt`.`amount` > 0 then `lbt`.`amount` else 0 end) AS `total_adjustment_positive`, sum(case when `lbt`.`type` = 'debit' then abs(`lbt`.`amount`) else 0 end) AS `total_debits`, sum(case when `lbt`.`type` = 'reversal' then `lbt`.`amount` else 0 end) AS `total_reversals`, sum(case when `lbt`.`type` = 'credit' and `lbt`.`amount` > 0 then `lbt`.`amount` else 0 end) AS `total_credits_remaining` FROM `leave_balance_transactions` AS `lbt` GROUP BY `lbt`.`employee_id`, `lbt`.`leave_type_id`, `lbt`.`period_id` HAVING `total_credits_remaining` > 0 ;

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `activity_logs`
--
ALTER TABLE `activity_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_created_at` (`created_at`),
  ADD KEY `idx_user_id` (`user_id`),
  ADD KEY `idx_SiegeID` (`SiegeID`),
  ADD KEY `idx_action` (`action`);

--
-- Index pour la table `administration`
--
ALTER TABLE `administration`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `fk_sieges_administration` (`SiegeID`);

--
-- Index pour la table `admin_roles`
--
ALTER TABLE `admin_roles`
  ADD PRIMARY KEY (`id`),
  ADD KEY `admin_roles_admin_id_is_active_index` (`admin_id`,`is_active`),
  ADD KEY `admin_roles_company_id_role_is_active_index` (`company_id`,`role`,`is_active`);

--
-- Index pour la table `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`);

--
-- Index pour la table `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`);

--
-- Index pour la table `company_holidays`
--
ALTER TABLE `company_holidays`
  ADD PRIMARY KEY (`id`),
  ADD KEY `company_holidays_site_id_index` (`site_id`),
  ADD KEY `company_holidays_date_index` (`date`),
  ADD KEY `company_holidays_site_date_index` (`site_id`,`date`);

--
-- Index pour la table `comparaisons_planning`
--
ALTER TABLE `comparaisons_planning`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `comparaisons_planning_planning_detail_id_date_comparaison_unique` (`planning_detail_id`,`date_comparaison`),
  ADD KEY `comparaisons_planning_pointage_id_foreign` (`pointage_id`);

--
-- Index pour la table `conges`
--
ALTER TABLE `conges`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_conges_employee` (`employee_id`),
  ADD KEY `fk_sieges_conges` (`SiegeID`);

--
-- Index pour la table `conge_validations`
--
ALTER TABLE `conge_validations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `conge_validations_siegeid_foreign` (`SiegeID`);

--
-- Index pour la table `departments`
--
ALTER TABLE `departments`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uk_dept_site_code` (`site_id`,`code`),
  ADD KEY `fk_dept_site` (`site_id`),
  ADD KEY `departments_deleted_at_index` (`deleted_at`);

--
-- Index pour la table `employee_managers`
--
ALTER TABLE `employee_managers`
  ADD PRIMARY KEY (`id`),
  ADD KEY `employee_managers_manager_id_foreign` (`manager_id`),
  ADD KEY `employee_managers_company_id_is_active_index` (`company_id`,`is_active`),
  ADD KEY `employee_managers_employee_id_is_active_index` (`employee_id`,`is_active`);

--
-- Index pour la table `employes`
--
ALTER TABLE `employes`
  ADD PRIMARY KEY (`ID`),
  ADD UNIQUE KEY `employes_email_unique` (`email`),
  ADD KEY `fk_sieges_Employes` (`SiegeID`),
  ADD KEY `fk_emp_site` (`site_id`),
  ADD KEY `fk_emp_dept` (`department_id`),
  ADD KEY `fk_emp_job` (`job_title_id`),
  ADD KEY `fk_emp_hlvl` (`hierarchy_level_id`),
  ADD KEY `fk_emp_mgr` (`manager_id`);

--
-- Index pour la table `entreprises`
--
ALTER TABLE `entreprises`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `fk_sieges_company` (`SiegeID`);

--
-- Index pour la table `entreprises_sieges`
--
ALTER TABLE `entreprises_sieges`
  ADD PRIMARY KEY (`ID`);

--
-- Index pour la table `evenements_planning`
--
ALTER TABLE `evenements_planning`
  ADD PRIMARY KEY (`id`),
  ADD KEY `evenements_planning_siege_id_foreign` (`siege_id`),
  ADD KEY `evenements_planning_service_id_foreign` (`service_id`),
  ADD KEY `evenements_planning_poste_id_foreign` (`poste_id`),
  ADD KEY `evenements_planning_cree_par_foreign` (`cree_par`),
  ADD KEY `evenements_planning_debut_fin_index` (`debut`,`fin`);

--
-- Index pour la table `evenement_employes`
--
ALTER TABLE `evenement_employes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `evenement_employes_evenement_id_employe_id_unique` (`evenement_id`,`employe_id`),
  ADD KEY `evenement_employes_employe_id_foreign` (`employe_id`);

--
-- Index pour la table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Index pour la table `hierarchy_levels`
--
ALTER TABLE `hierarchy_levels`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `horaires_types`
--
ALTER TABLE `horaires_types`
  ADD PRIMARY KEY (`id`),
  ADD KEY `horaires_types_cree_par_foreign` (`cree_par`),
  ADD KEY `horaires_types_poste_id_foreign` (`poste_id`);

--
-- Index pour la table `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Index pour la table `job_batches`
--
ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `job_titles`
--
ALTER TABLE `job_titles`
  ADD PRIMARY KEY (`id`),
  ADD KEY `job_titles_hierarchy_level_id_index` (`hierarchy_level_id`),
  ADD KEY `job_titles_department_id_foreign` (`department_id`);

--
-- Index pour la table `jours_non_travailles`
--
ALTER TABLE `jours_non_travailles`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `jours_non_travailles_siegeid_foreign` (`SiegeID`),
  ADD KEY `jours_non_travailles_date_siegeid_index` (`Date`,`SiegeID`),
  ADD KEY `jours_non_travailles_actived_index` (`Actived`);

--
-- Index pour la table `leave_approvals`
--
ALTER TABLE `leave_approvals`
  ADD PRIMARY KEY (`id`),
  ADD KEY `leave_approvals_leave_request_id_index` (`leave_request_id`),
  ADD KEY `leave_approvals_approver_id_index` (`approver_id`),
  ADD KEY `leave_approvals_is_current_index` (`is_current`),
  ADD KEY `leave_approvals_status_index` (`status`),
  ADD KEY `leave_approvals_workflow_step_id_foreign` (`workflow_step_id`);

--
-- Index pour la table `leave_balances`
--
ALTER TABLE `leave_balances`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `employee_type_period_unique` (`employee_id`,`leave_type_id`,`period_id`),
  ADD KEY `leave_balances_employee_index` (`employee_id`),
  ADD KEY `leave_balances_type_index` (`leave_type_id`),
  ADD KEY `leave_balances_period_index` (`period_id`);

--
-- Index pour la table `leave_balance_transactions`
--
ALTER TABLE `leave_balance_transactions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `lbt_employee_index` (`employee_id`),
  ADD KEY `lbt_type_index` (`leave_type_id`),
  ADD KEY `lbt_period_index` (`period_id`),
  ADD KEY `lbt_employee_period_index` (`employee_id`,`period_id`),
  ADD KEY `lbt_reference_index` (`reference_id`,`reference_type`);

--
-- Index pour la table `leave_import_batches`
--
ALTER TABLE `leave_import_batches`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `batch_number_unique` (`batch_number`),
  ADD KEY `site_import_index` (`site_id`);

--
-- Index pour la table `leave_periods`
--
ALTER TABLE `leave_periods`
  ADD PRIMARY KEY (`id`),
  ADD KEY `lper_site_index` (`site_id`),
  ADD KEY `lper_type_index` (`leave_type_id`),
  ADD KEY `lper_main_index` (`site_id`,`leave_type_id`,`status`,`is_active`);

--
-- Index pour la table `leave_policies`
--
ALTER TABLE `leave_policies`
  ADD PRIMARY KEY (`id`),
  ADD KEY `leave_policies_site_id_index` (`site_id`),
  ADD KEY `leave_policies_name_index` (`name`),
  ADD KEY `leave_policies_site_active_index` (`site_id`,`is_active`);

--
-- Index pour la table `leave_policy_assignments`
--
ALTER TABLE `leave_policy_assignments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `lpa_policy_index` (`leave_policy_id`),
  ADD KEY `lpa_employee_index` (`employee_id`),
  ADD KEY `lpa_department_index` (`department_id`),
  ADD KEY `lpa_job_title_index` (`job_title_id`),
  ADD KEY `lpa_hierarchy_index` (`hierarchy_level_id`),
  ADD KEY `lpa_site_index` (`site_id`),
  ADD KEY `lpa_company_index` (`company_id`);

--
-- Index pour la table `leave_requests`
--
ALTER TABLE `leave_requests`
  ADD PRIMARY KEY (`id`),
  ADD KEY `lr_employee_index` (`employee_id`),
  ADD KEY `lr_type_index` (`leave_type_id`),
  ADD KEY `lr_period_index` (`period_id`),
  ADD KEY `lr_status_index` (`status`),
  ADD KEY `lr_dates_index` (`start_date`,`end_date`);

--
-- Index pour la table `leave_request_attachments`
--
ALTER TABLE `leave_request_attachments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `lra_request_index` (`leave_request_id`);

--
-- Index pour la table `leave_roles`
--
ALTER TABLE `leave_roles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `leave_roles_name_unique` (`name`);

--
-- Index pour la table `leave_types`
--
ALTER TABLE `leave_types`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `leave_types_code_site_id_unique` (`code`,`site_id`),
  ADD KEY `leave_types_site_id_is_active_index` (`site_id`,`is_active`),
  ADD KEY `leave_types_deleted_at_index` (`deleted_at`);

--
-- Index pour la table `leave_type_siege_settings`
--
ALTER TABLE `leave_type_siege_settings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `leave_type_siege_settings_siegeid_leave_type_id_unique` (`SiegeID`,`leave_type_id`),
  ADD KEY `leave_type_siege_settings_leave_type_id_foreign` (`leave_type_id`),
  ADD KEY `leave_type_siege_settings_forked_type_id_foreign` (`forked_type_id`);

--
-- Index pour la table `leave_type_site_activations`
--
ALTER TABLE `leave_type_site_activations`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `ltsa_site_leave_unique` (`site_id`,`leave_type_id`),
  ADD KEY `leave_type_site_activations_leave_type_id_foreign` (`leave_type_id`);

--
-- Index pour la table `leave_validators`
--
ALTER TABLE `leave_validators`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_validator` (`employee_id`,`site_id`,`role`) COMMENT 'Un même employé ne peut avoir qu''un seul rôle par site',
  ADD KEY `leave_validators_site_id_index` (`site_id`),
  ADD KEY `leave_validators_role_index` (`role`);

--
-- Index pour la table `leave_workflows`
--
ALTER TABLE `leave_workflows`
  ADD PRIMARY KEY (`id`),
  ADD KEY `leave_workflows_site_id_index` (`site_id`),
  ADD KEY `leave_workflows_name_index` (`name`),
  ADD KEY `leave_workflows_site_active_index` (`site_id`,`is_active`),
  ADD KEY `leave_workflows_leave_type_id_foreign` (`leave_type_id`);

--
-- Index pour la table `leave_workflow_steps`
--
ALTER TABLE `leave_workflow_steps`
  ADD PRIMARY KEY (`id`),
  ADD KEY `workflow_id` (`workflow_id`),
  ADD KEY `approver_role` (`approver_role`),
  ADD KEY `leave_workflow_steps_approver_employee_id_foreign` (`approver_employee_id`);

--
-- Index pour la table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `notifications_user_id_index` (`user_id`),
  ADD KEY `notifications_is_read_index` (`is_read`),
  ADD KEY `notifications_created_at_index` (`created_at`),
  ADD KEY `notifications_leave_request_id_index` (`leave_request_id`);

--
-- Index pour la table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Index pour la table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`),
  ADD KEY `personal_access_tokens_expires_at_index` (`expires_at`);

--
-- Index pour la table `plannings`
--
ALTER TABLE `plannings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `plannings_siege_id_foreign` (`siege_id`),
  ADD KEY `plannings_service_id_foreign` (`service_id`),
  ADD KEY `plannings_poste_id_foreign` (`poste_id`),
  ADD KEY `plannings_cree_par_foreign` (`cree_par`),
  ADD KEY `plannings_valide_par_foreign` (`valide_par`),
  ADD KEY `plannings_date_debut_semaine_date_fin_semaine_index` (`date_debut_semaine`,`date_fin_semaine`),
  ADD KEY `plannings_statut_index` (`statut`);

--
-- Index pour la table `planning_details`
--
ALTER TABLE `planning_details`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `planning_details_planning_id_employe_id_date_unique` (`planning_id`,`employe_id`,`date`),
  ADD KEY `planning_details_employe_id_date_index` (`employe_id`,`date`);

--
-- Index pour la table `pointages`
--
ALTER TABLE `pointages`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `fk_pointages_employee` (`employee_id`),
  ADD KEY `fk_sieges_Pointages` (`SiegeID`),
  ADD KEY `fk_pointages_company` (`company_id`);

--
-- Index pour la table `pointage_event_exceptions`
--
ALTER TABLE `pointage_event_exceptions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_exception` (`employee_id`,`date`,`error_type`),
  ADD KEY `pointage_event_exceptions_siegeid_index` (`SiegeID`),
  ADD KEY `pointage_event_exceptions_employee_id_index` (`employee_id`);

--
-- Index pour la table `seller_sieges`
--
ALTER TABLE `seller_sieges`
  ADD PRIMARY KEY (`ID`),
  ADD UNIQUE KEY `unique_seller_siege` (`SellerID`,`SiegeID`),
  ADD KEY `seller_sieges_siegeid_foreign` (`SiegeID`);

--
-- Index pour la table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Index pour la table `site_company_holiday_settings`
--
ALTER TABLE `site_company_holiday_settings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `site_holiday_unique` (`site_id`,`company_holiday_id`),
  ADD KEY `site_company_holiday_id_index` (`company_holiday_id`);

--
-- Index pour la table `site_leave_periods`
--
ALTER TABLE `site_leave_periods`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `site_period_unique` (`site_id`,`leave_period_id`),
  ADD KEY `site_period_period_index` (`leave_period_id`),
  ADD KEY `site_period_active_index` (`site_id`,`is_active`);

--
-- Index pour la table `site_leave_policy_settings`
--
ALTER TABLE `site_leave_policy_settings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `site_policy_unique` (`site_id`,`leave_policy_id`),
  ADD KEY `site_policy_policy_index` (`leave_policy_id`),
  ADD KEY `site_policy_active_index` (`site_id`,`is_active`);

--
-- Index pour la table `site_leave_type_settings`
--
ALTER TABLE `site_leave_type_settings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `site_type_unique` (`site_id`,`leave_type_id`),
  ADD KEY `site_type_type_index` (`leave_type_id`),
  ADD KEY `site_type_site_active_index` (`site_id`,`is_enabled`);

--
-- Index pour la table `site_leave_workflow_settings`
--
ALTER TABLE `site_leave_workflow_settings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `site_workflow_unique` (`site_id`,`leave_workflow_id`),
  ADD KEY `site_workflow_workflow_index` (`leave_workflow_id`),
  ADD KEY `site_workflow_active_index` (`site_id`,`is_active`);

--
-- Index pour la table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `activity_logs`
--
ALTER TABLE `activity_logs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1289;

--
-- AUTO_INCREMENT pour la table `administration`
--
ALTER TABLE `administration`
  MODIFY `ID` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT pour la table `admin_roles`
--
ALTER TABLE `admin_roles`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `company_holidays`
--
ALTER TABLE `company_holidays`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT pour la table `comparaisons_planning`
--
ALTER TABLE `comparaisons_planning`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `conges`
--
ALTER TABLE `conges`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT pour la table `conge_validations`
--
ALTER TABLE `conge_validations`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT pour la table `departments`
--
ALTER TABLE `departments`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT pour la table `employee_managers`
--
ALTER TABLE `employee_managers`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `employes`
--
ALTER TABLE `employes`
  MODIFY `ID` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=70;

--
-- AUTO_INCREMENT pour la table `entreprises`
--
ALTER TABLE `entreprises`
  MODIFY `ID` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- AUTO_INCREMENT pour la table `entreprises_sieges`
--
ALTER TABLE `entreprises_sieges`
  MODIFY `ID` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT pour la table `evenements_planning`
--
ALTER TABLE `evenements_planning`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `evenement_employes`
--
ALTER TABLE `evenement_employes`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `hierarchy_levels`
--
ALTER TABLE `hierarchy_levels`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT pour la table `horaires_types`
--
ALTER TABLE `horaires_types`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT pour la table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `job_titles`
--
ALTER TABLE `job_titles`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=352;

--
-- AUTO_INCREMENT pour la table `jours_non_travailles`
--
ALTER TABLE `jours_non_travailles`
  MODIFY `ID` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT pour la table `leave_approvals`
--
ALTER TABLE `leave_approvals`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=62;

--
-- AUTO_INCREMENT pour la table `leave_balances`
--
ALTER TABLE `leave_balances`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=60;

--
-- AUTO_INCREMENT pour la table `leave_balance_transactions`
--
ALTER TABLE `leave_balance_transactions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=122;

--
-- AUTO_INCREMENT pour la table `leave_import_batches`
--
ALTER TABLE `leave_import_batches`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT pour la table `leave_periods`
--
ALTER TABLE `leave_periods`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT pour la table `leave_policies`
--
ALTER TABLE `leave_policies`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT pour la table `leave_policy_assignments`
--
ALTER TABLE `leave_policy_assignments`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT pour la table `leave_requests`
--
ALTER TABLE `leave_requests`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=84;

--
-- AUTO_INCREMENT pour la table `leave_request_attachments`
--
ALTER TABLE `leave_request_attachments`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT pour la table `leave_roles`
--
ALTER TABLE `leave_roles`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT pour la table `leave_types`
--
ALTER TABLE `leave_types`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT pour la table `leave_type_siege_settings`
--
ALTER TABLE `leave_type_siege_settings`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `leave_type_site_activations`
--
ALTER TABLE `leave_type_site_activations`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT pour la table `leave_validators`
--
ALTER TABLE `leave_validators`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT pour la table `leave_workflows`
--
ALTER TABLE `leave_workflows`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT pour la table `leave_workflow_steps`
--
ALTER TABLE `leave_workflow_steps`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=54;

--
-- AUTO_INCREMENT pour la table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=92;

--
-- AUTO_INCREMENT pour la table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `plannings`
--
ALTER TABLE `plannings`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT pour la table `planning_details`
--
ALTER TABLE `planning_details`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT pour la table `pointages`
--
ALTER TABLE `pointages`
  MODIFY `ID` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1526;

--
-- AUTO_INCREMENT pour la table `pointage_event_exceptions`
--
ALTER TABLE `pointage_event_exceptions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT pour la table `seller_sieges`
--
ALTER TABLE `seller_sieges`
  MODIFY `ID` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT pour la table `site_company_holiday_settings`
--
ALTER TABLE `site_company_holiday_settings`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT pour la table `site_leave_periods`
--
ALTER TABLE `site_leave_periods`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT pour la table `site_leave_policy_settings`
--
ALTER TABLE `site_leave_policy_settings`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT pour la table `site_leave_type_settings`
--
ALTER TABLE `site_leave_type_settings`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT pour la table `site_leave_workflow_settings`
--
ALTER TABLE `site_leave_workflow_settings`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT pour la table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `administration`
--
ALTER TABLE `administration`
  ADD CONSTRAINT `fk_sieges_administration` FOREIGN KEY (`SiegeID`) REFERENCES `entreprises_sieges` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `admin_roles`
--
ALTER TABLE `admin_roles`
  ADD CONSTRAINT `admin_roles_admin_id_foreign` FOREIGN KEY (`admin_id`) REFERENCES `administration` (`ID`) ON DELETE CASCADE;

--
-- Contraintes pour la table `company_holidays`
--
ALTER TABLE `company_holidays`
  ADD CONSTRAINT `company_holidays_site_id_foreign` FOREIGN KEY (`site_id`) REFERENCES `entreprises_sieges` (`ID`) ON DELETE CASCADE;

--
-- Contraintes pour la table `comparaisons_planning`
--
ALTER TABLE `comparaisons_planning`
  ADD CONSTRAINT `comparaisons_planning_planning_detail_id_foreign` FOREIGN KEY (`planning_detail_id`) REFERENCES `planning_details` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `comparaisons_planning_pointage_id_foreign` FOREIGN KEY (`pointage_id`) REFERENCES `pointages` (`ID`) ON DELETE SET NULL;

--
-- Contraintes pour la table `conges`
--
ALTER TABLE `conges`
  ADD CONSTRAINT `fk_conges_employee` FOREIGN KEY (`employee_id`) REFERENCES `employes` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_sieges_conges` FOREIGN KEY (`SiegeID`) REFERENCES `entreprises_sieges` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `conge_validations`
--
ALTER TABLE `conge_validations`
  ADD CONSTRAINT `conge_validations_siegeid_foreign` FOREIGN KEY (`SiegeID`) REFERENCES `entreprises_sieges` (`ID`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Contraintes pour la table `employee_managers`
--
ALTER TABLE `employee_managers`
  ADD CONSTRAINT `employee_managers_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `entreprises_sieges` (`ID`) ON DELETE CASCADE,
  ADD CONSTRAINT `employee_managers_employee_id_foreign` FOREIGN KEY (`employee_id`) REFERENCES `employes` (`ID`) ON DELETE CASCADE,
  ADD CONSTRAINT `employee_managers_manager_id_foreign` FOREIGN KEY (`manager_id`) REFERENCES `employes` (`ID`) ON DELETE CASCADE;

--
-- Contraintes pour la table `employes`
--
ALTER TABLE `employes`
  ADD CONSTRAINT `fk_sieges_Employes` FOREIGN KEY (`SiegeID`) REFERENCES `entreprises_sieges` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `entreprises`
--
ALTER TABLE `entreprises`
  ADD CONSTRAINT `fk_sieges_company` FOREIGN KEY (`SiegeID`) REFERENCES `entreprises_sieges` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `evenements_planning`
--
ALTER TABLE `evenements_planning`
  ADD CONSTRAINT `evenements_planning_cree_par_foreign` FOREIGN KEY (`cree_par`) REFERENCES `administration` (`ID`) ON DELETE CASCADE,
  ADD CONSTRAINT `evenements_planning_poste_id_foreign` FOREIGN KEY (`poste_id`) REFERENCES `job_titles` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `evenements_planning_service_id_foreign` FOREIGN KEY (`service_id`) REFERENCES `departments` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `evenements_planning_siege_id_foreign` FOREIGN KEY (`siege_id`) REFERENCES `entreprises_sieges` (`ID`) ON DELETE CASCADE;

--
-- Contraintes pour la table `evenement_employes`
--
ALTER TABLE `evenement_employes`
  ADD CONSTRAINT `evenement_employes_employe_id_foreign` FOREIGN KEY (`employe_id`) REFERENCES `employes` (`ID`) ON DELETE CASCADE,
  ADD CONSTRAINT `evenement_employes_evenement_id_foreign` FOREIGN KEY (`evenement_id`) REFERENCES `evenements_planning` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `horaires_types`
--
ALTER TABLE `horaires_types`
  ADD CONSTRAINT `horaires_types_cree_par_foreign` FOREIGN KEY (`cree_par`) REFERENCES `administration` (`ID`) ON DELETE CASCADE,
  ADD CONSTRAINT `horaires_types_poste_id_foreign` FOREIGN KEY (`poste_id`) REFERENCES `job_titles` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `job_titles`
--
ALTER TABLE `job_titles`
  ADD CONSTRAINT `job_titles_department_id_foreign` FOREIGN KEY (`department_id`) REFERENCES `departments` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `job_titles_hierarchy_level_id_foreign` FOREIGN KEY (`hierarchy_level_id`) REFERENCES `hierarchy_levels` (`id`) ON DELETE SET NULL;

--
-- Contraintes pour la table `jours_non_travailles`
--
ALTER TABLE `jours_non_travailles`
  ADD CONSTRAINT `jours_non_travailles_siegeid_foreign` FOREIGN KEY (`SiegeID`) REFERENCES `entreprises_sieges` (`ID`) ON DELETE CASCADE;

--
-- Contraintes pour la table `leave_approvals`
--
ALTER TABLE `leave_approvals`
  ADD CONSTRAINT `leave_approvals_approver_id_foreign` FOREIGN KEY (`approver_id`) REFERENCES `employes` (`ID`) ON DELETE CASCADE,
  ADD CONSTRAINT `leave_approvals_leave_request_id_foreign` FOREIGN KEY (`leave_request_id`) REFERENCES `leave_requests` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `leave_approvals_workflow_step_id_foreign` FOREIGN KEY (`workflow_step_id`) REFERENCES `leave_workflow_steps` (`id`) ON DELETE SET NULL;

--
-- Contraintes pour la table `leave_balances`
--
ALTER TABLE `leave_balances`
  ADD CONSTRAINT `lb_employee_foreign` FOREIGN KEY (`employee_id`) REFERENCES `employes` (`ID`) ON DELETE CASCADE,
  ADD CONSTRAINT `lb_period_foreign` FOREIGN KEY (`period_id`) REFERENCES `leave_periods` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `lb_type_foreign` FOREIGN KEY (`leave_type_id`) REFERENCES `leave_types` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `leave_balance_transactions`
--
ALTER TABLE `leave_balance_transactions`
  ADD CONSTRAINT `lbt_employee_foreign` FOREIGN KEY (`employee_id`) REFERENCES `employes` (`ID`) ON DELETE CASCADE,
  ADD CONSTRAINT `lbt_period_foreign` FOREIGN KEY (`period_id`) REFERENCES `leave_periods` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `lbt_type_foreign` FOREIGN KEY (`leave_type_id`) REFERENCES `leave_types` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `leave_periods`
--
ALTER TABLE `leave_periods`
  ADD CONSTRAINT `lper_leave_type_foreign` FOREIGN KEY (`leave_type_id`) REFERENCES `leave_types` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `lper_site_foreign` FOREIGN KEY (`site_id`) REFERENCES `entreprises_sieges` (`ID`) ON DELETE CASCADE;

--
-- Contraintes pour la table `leave_policy_assignments`
--
ALTER TABLE `leave_policy_assignments`
  ADD CONSTRAINT `lpa_policy_foreign` FOREIGN KEY (`leave_policy_id`) REFERENCES `leave_policies` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `leave_requests`
--
ALTER TABLE `leave_requests`
  ADD CONSTRAINT `lr_employee_foreign` FOREIGN KEY (`employee_id`) REFERENCES `employes` (`ID`) ON DELETE CASCADE,
  ADD CONSTRAINT `lr_period_foreign` FOREIGN KEY (`period_id`) REFERENCES `leave_periods` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `lr_type_foreign` FOREIGN KEY (`leave_type_id`) REFERENCES `leave_types` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `leave_request_attachments`
--
ALTER TABLE `leave_request_attachments`
  ADD CONSTRAINT `lra_request_foreign` FOREIGN KEY (`leave_request_id`) REFERENCES `leave_requests` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `leave_types`
--
ALTER TABLE `leave_types`
  ADD CONSTRAINT `leave_types_site_id_foreign` FOREIGN KEY (`site_id`) REFERENCES `entreprises_sieges` (`ID`) ON DELETE SET NULL;

--
-- Contraintes pour la table `leave_type_siege_settings`
--
ALTER TABLE `leave_type_siege_settings`
  ADD CONSTRAINT `leave_type_siege_settings_forked_type_id_foreign` FOREIGN KEY (`forked_type_id`) REFERENCES `leave_types` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `leave_type_siege_settings_leave_type_id_foreign` FOREIGN KEY (`leave_type_id`) REFERENCES `leave_types` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `leave_type_site_activations`
--
ALTER TABLE `leave_type_site_activations`
  ADD CONSTRAINT `leave_type_site_activations_leave_type_id_foreign` FOREIGN KEY (`leave_type_id`) REFERENCES `leave_types` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `leave_type_site_activations_site_id_foreign` FOREIGN KEY (`site_id`) REFERENCES `entreprises_sieges` (`ID`) ON DELETE CASCADE,
  ADD CONSTRAINT `ltsa_leave_type_id_foreign` FOREIGN KEY (`leave_type_id`) REFERENCES `leave_types` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `ltsa_site_id_foreign` FOREIGN KEY (`site_id`) REFERENCES `entreprises_sieges` (`ID`) ON DELETE CASCADE;

--
-- Contraintes pour la table `leave_validators`
--
ALTER TABLE `leave_validators`
  ADD CONSTRAINT `leave_validators_employee_id_foreign` FOREIGN KEY (`employee_id`) REFERENCES `employes` (`ID`) ON DELETE CASCADE,
  ADD CONSTRAINT `leave_validators_site_id_foreign` FOREIGN KEY (`site_id`) REFERENCES `entreprises_sieges` (`ID`) ON DELETE CASCADE;

--
-- Contraintes pour la table `leave_workflows`
--
ALTER TABLE `leave_workflows`
  ADD CONSTRAINT `leave_workflows_leave_type_id_foreign` FOREIGN KEY (`leave_type_id`) REFERENCES `leave_types` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `leave_workflows_site_id_foreign` FOREIGN KEY (`site_id`) REFERENCES `entreprises_sieges` (`ID`) ON DELETE CASCADE;

--
-- Contraintes pour la table `leave_workflow_steps`
--
ALTER TABLE `leave_workflow_steps`
  ADD CONSTRAINT `leave_workflow_steps_approver_employee_id_foreign` FOREIGN KEY (`approver_employee_id`) REFERENCES `employes` (`ID`) ON DELETE SET NULL,
  ADD CONSTRAINT `leave_workflow_steps_workflow_id_foreign` FOREIGN KEY (`workflow_id`) REFERENCES `leave_workflows` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `notifications`
--
ALTER TABLE `notifications`
  ADD CONSTRAINT `notifications_leave_request_id_foreign` FOREIGN KEY (`leave_request_id`) REFERENCES `leave_requests` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `notifications_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `administration` (`ID`) ON DELETE CASCADE;

--
-- Contraintes pour la table `plannings`
--
ALTER TABLE `plannings`
  ADD CONSTRAINT `plannings_cree_par_foreign` FOREIGN KEY (`cree_par`) REFERENCES `administration` (`ID`) ON DELETE CASCADE,
  ADD CONSTRAINT `plannings_poste_id_foreign` FOREIGN KEY (`poste_id`) REFERENCES `job_titles` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `plannings_service_id_foreign` FOREIGN KEY (`service_id`) REFERENCES `departments` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `plannings_siege_id_foreign` FOREIGN KEY (`siege_id`) REFERENCES `entreprises_sieges` (`ID`) ON DELETE CASCADE,
  ADD CONSTRAINT `plannings_valide_par_foreign` FOREIGN KEY (`valide_par`) REFERENCES `administration` (`ID`) ON DELETE SET NULL;

--
-- Contraintes pour la table `planning_details`
--
ALTER TABLE `planning_details`
  ADD CONSTRAINT `planning_details_employe_id_foreign` FOREIGN KEY (`employe_id`) REFERENCES `employes` (`ID`) ON DELETE CASCADE,
  ADD CONSTRAINT `planning_details_planning_id_foreign` FOREIGN KEY (`planning_id`) REFERENCES `plannings` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `pointages`
--
ALTER TABLE `pointages`
  ADD CONSTRAINT `fk_pointages_company` FOREIGN KEY (`company_id`) REFERENCES `entreprises` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_pointages_employee` FOREIGN KEY (`employee_id`) REFERENCES `employes` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_sieges_Pointages` FOREIGN KEY (`SiegeID`) REFERENCES `entreprises_sieges` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `seller_sieges`
--
ALTER TABLE `seller_sieges`
  ADD CONSTRAINT `seller_sieges_sellerid_foreign` FOREIGN KEY (`SellerID`) REFERENCES `administration` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `seller_sieges_siegeid_foreign` FOREIGN KEY (`SiegeID`) REFERENCES `entreprises_sieges` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `site_company_holiday_settings`
--
ALTER TABLE `site_company_holiday_settings`
  ADD CONSTRAINT `sch_holiday_foreign` FOREIGN KEY (`company_holiday_id`) REFERENCES `company_holidays` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `sch_site_foreign` FOREIGN KEY (`site_id`) REFERENCES `entreprises_sieges` (`ID`) ON DELETE CASCADE;

--
-- Contraintes pour la table `site_leave_periods`
--
ALTER TABLE `site_leave_periods`
  ADD CONSTRAINT `slp_period_foreign` FOREIGN KEY (`leave_period_id`) REFERENCES `leave_periods` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `slp_site_foreign` FOREIGN KEY (`site_id`) REFERENCES `entreprises_sieges` (`ID`) ON DELETE CASCADE;

--
-- Contraintes pour la table `site_leave_type_settings`
--
ALTER TABLE `site_leave_type_settings`
  ADD CONSTRAINT `slt_site_foreign` FOREIGN KEY (`site_id`) REFERENCES `entreprises_sieges` (`ID`) ON DELETE CASCADE,
  ADD CONSTRAINT `slt_type_foreign` FOREIGN KEY (`leave_type_id`) REFERENCES `leave_types` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `site_leave_workflow_settings`
--
ALTER TABLE `site_leave_workflow_settings`
  ADD CONSTRAINT `sw_site_foreign` FOREIGN KEY (`site_id`) REFERENCES `entreprises_sieges` (`ID`) ON DELETE CASCADE,
  ADD CONSTRAINT `sw_workflow_foreign` FOREIGN KEY (`leave_workflow_id`) REFERENCES `leave_workflows` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
