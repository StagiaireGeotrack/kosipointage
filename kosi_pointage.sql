-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : mer. 02 sep. 2026 à 10:36
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
(994, 2, 'admin@geotrack.com', 'simple_admin', 'update', 'Department', 12, 'Ressources humaines', 'Service mis à jour : Ressources humaines', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', 1, '2026-09-02 08:25:29');

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
(18, 'testautreadmin@gmail.com', 'c4edb3216ae52510b16f308fe3e5ffcefac11821', 0, 0, 1, 2, '2026-08-28 15:58:45', '2026-08-28 15:58:45', NULL, 1, 0);

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
(1, 1, '2026-07-14', 'fete de independanceny', 1, 1, 0, '2026-08-21 09:36:44', '2026-08-17 14:41:30', '2026-08-21 09:36:44'),
(2, NULL, '2026-06-26', 'malagasy', 1, 1, 0, '2026-08-21 09:38:48', '2026-08-17 14:42:52', '2026-08-21 09:38:48'),
(3, NULL, '2026-07-26', 'malagasy', 1, 1, 0, '2026-08-21 09:38:52', '2026-08-17 14:50:04', '2026-08-21 09:38:52'),
(4, NULL, '2011-11-11', 'MALALANIRINA', 1, 1, 0, '2026-08-21 09:38:44', '2026-08-17 14:53:07', '2026-08-21 09:38:44'),
(5, NULL, '2002-08-05', 'ann', 1, 1, 1, '2026-08-21 09:38:39', '2026-08-17 15:01:52', '2026-08-21 09:38:39'),
(6, 1, '2026-06-26', 'Fete de l\'independance Malagasy 2026', 1, 1, 0, NULL, '2026-08-21 09:40:24', '2026-08-31 14:55:07'),
(7, 1, '2026-05-01', 'fete de travail', 1, 1, 0, '2026-08-31 14:55:49', '2026-08-21 09:41:31', '2026-08-31 14:55:49'),
(8, 1, '2026-12-25', 'Noel', 0, 1, 0, NULL, '2026-08-21 12:16:53', '2026-08-21 12:16:53'),
(9, 1, '2026-01-01', 'jour de l\'an', 1, 1, 0, NULL, '2026-08-21 12:17:38', '2026-08-21 12:17:38');

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

--
-- Déchargement des données de la table `conges`
--

INSERT INTO `conges` (`id`, `employee_id`, `SiegeID`, `date_debut`, `date_fin`, `type_conge`, `commentaire`, `created_at`) VALUES
(12, 2, 1, '2025-12-20 08:00:00.000000', '2026-01-20 08:00:00.000000', 'CP', 'Congé annuel', '2025-12-23 18:12:51'),
(13, 3, 1, '2025-12-20 08:00:00.000000', '2026-01-05 08:00:00.000000', 'CP', 'Congé annuel anticipé', '2025-12-23 18:13:47'),
(21, 1, 1, '2026-01-30 08:00:00.000000', '2026-02-02 08:00:00.000000', 'RTT', NULL, '2026-01-29 08:07:50'),
(22, 2, 1, '2026-02-05 08:00:00.000000', '2026-02-05 17:00:00.000000', 'CP', NULL, '2026-02-11 15:05:36'),
(23, 3, 1, '2026-02-11 08:00:00.000000', '2026-02-11 17:30:00.000000', 'Autres', 'Congé cyclonique', '2026-02-11 15:06:38'),
(24, 1, 1, '2026-02-12 08:00:00.000000', '2026-02-16 08:00:00.000000', 'Maladie', 'COVID', '2026-02-20 08:49:09'),
(25, 5, 1, '2026-07-14 00:00:00.000000', '2026-07-25 23:59:00.000000', 'CP', NULL, '2026-05-23 07:47:12'),
(26, 5, 1, '2026-07-27 08:55:00.000000', '2026-07-31 15:55:00.000000', 'CP', NULL, '2026-07-22 14:57:33');

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
(35, 0, 1, 'Service informatique 2.0', 'SRI', 2, '2026-09-01 15:10:47', '2026-09-01 15:10:47', NULL);

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
(1, 1, 1, 1, 1, 1, 2, NULL, 'actif', NULL, 'Willy Tarkin', NULL, NULL, NULL, NULL, '96e6195793afdb9f3c54a4ffb7c20c21d37deb0a8b2b819f74473e68af152c95', 0, 0, NULL, NULL, 'EMP0001', '2025-08-30 08:03:43', 1, 1, 1),
(2, NULL, 1, 1, 1, 5, 2, 2, 'actif', NULL, 'Solofoniaina Vololonirina Emma Joeline', NULL, NULL, NULL, NULL, 'b2c8a55a9aaa2d903b7732fb1dcc46e027aac65ed4f2098dd7611c3d6723a0ff', 0, 1, 'EMB_V1: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', '691f59ef9b5c3e2e905873c5b386645e698d579f4133ee78e75e49e97db7da9b', 'EMP0002', '2025-08-29 11:08:29', 1, 1, 0),
(3, NULL, 1, 1, 1, 1, 2, NULL, 'actif', NULL, 'Ramarotafika Hedi Franco', 'ramarotafika1999@gmail.com', NULL, '$2y$12$FlXPAxcC01j8tvK73QDBn.K650J995LlnOw73nT9Xj0HDbcO7oDo2', NULL, '3eb5cc214bf67c7e05df5606f27483c7e0d6a1d98ee8f41c2b010065b88475ee', 0, 1, 'EMB_V1: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', '823a2a3dec8c781b4f239bb2c92108a66150bc163d4292d549616478c071d04c', 'EMP0003', '2025-08-29 11:00:20', 1, 1, 0),
(5, 1, 1, 16, 1, 1, 2, NULL, 'actif', NULL, 'MALALANIRINA Emile Noeline', 'malalanirinaemile2@gmail.com', NULL, '$2y$12$9S1WZLx1Rt.M1O0b0Am/Q.ty8Vk/l7FiiXdoDRL20Yiizaz3XfLje', 'muAp7jAwqn7ZVe3WIgPmvwEh5zQLJHj4rPfv1p5XSlYB62U4S3ED785QlobR', '03e4458621af91d0325724dbe776bf522611b729f4d3d8b0d3e8c1fd07665d31', 0, 0, NULL, NULL, 'EMP0005', '2025-08-29 06:49:12', 1, 1, 0),
(16, 2, 2, NULL, 1, 1, 62, NULL, 'actif', NULL, 'Tarkin Willy', 'testautreadminrun@gmail.com', NULL, '$2y$12$p8h7NbYx4yTlyhR65YnePeiOgME/hF7loS03epiKTJzmz0zib7CHu', NULL, '37E8476C', 0, 1, NULL, NULL, 'EMP0016', '2025-10-03 14:15:35', 1, 2, 0),
(17, NULL, 2, 2, 1, 1, 16, NULL, 'actif', NULL, 'Wilfried Payet', NULL, NULL, NULL, NULL, 'AD5689', 0, 1, NULL, NULL, 'EMP0017', '2025-10-04 14:40:43', 0, 2, 0),
(35, NULL, 4, 3, 1, 1, 35, NULL, 'actif', NULL, 'abdouroihamane saidal', NULL, NULL, NULL, NULL, 'a57a369aac783150bb22e308627817b7089b3e2badbd383fb4d7ac632236dfab', 0, 0, NULL, NULL, '14', '2025-11-03 14:22:51', 1, 4, 0),
(36, NULL, 4, 3, 1, 1, 35, NULL, 'actif', NULL, 'ahmed mohamed roukia', NULL, NULL, NULL, NULL, 'EEB15625', 0, 0, NULL, NULL, 'EMP0036', '2025-11-03 14:22:51', 1, 4, 0),
(37, NULL, 4, 3, 1, 1, 35, NULL, 'actif', NULL, 'ousseni combo issouf', NULL, NULL, NULL, NULL, 'CE7E4425', 0, 0, NULL, NULL, 'EMP0037', '2025-11-03 14:22:51', 1, 4, 0),
(38, NULL, 4, 3, 1, 1, 35, NULL, 'actif', NULL, 'ahmed wassim', NULL, NULL, NULL, NULL, 'BE8B3B25', 0, 0, NULL, NULL, 'EMP0038', '2025-11-03 14:22:51', 1, 4, 0),
(39, NULL, 2, 2, 1, 1, 16, NULL, 'actif', NULL, 'Turpin Pascal', NULL, NULL, NULL, NULL, '047CB69AD01191', 0, 1, NULL, NULL, 'EMP0039', '2025-11-05 12:02:13', 0, 2, 0),
(40, NULL, 1, 1, 1, 1, 2, NULL, 'actif', NULL, 'Mme Fanja', NULL, NULL, NULL, NULL, '783daa24570d0c2adce279d467ac25367e706fa64bd9284042b49e8e0803fc95', 0, 0, NULL, NULL, 'EMP0040', '2026-01-28 05:35:26', 0, 1, 1),
(42, NULL, 1, 1, 1, 1, 2, NULL, 'actif', NULL, 'RANDRIAMBELOMANANA Safidinantenaina Nirintsoa Roger', NULL, NULL, NULL, NULL, '9198d215a53452cd296fa04fed7547b2cbddbfff92335fb6796cbb4b81e02c39', 0, 0, NULL, NULL, 'EMP0042', '2026-01-30 08:27:00', 0, 1, 1),
(54, NULL, 8, 4, 1, 1, 54, NULL, 'actif', NULL, 'Emp 1', NULL, NULL, NULL, NULL, '112233', 1, 0, NULL, NULL, 'EMP0054', '2026-02-20 08:11:07', 1, 8, 0),
(56, NULL, 8, 4, 1, 1, 54, NULL, 'actif', NULL, 'Emp 3', NULL, NULL, NULL, NULL, '113355', 1, 0, NULL, NULL, 'EMP0056', '2026-02-20 08:11:24', 1, 8, 0),
(57, NULL, 1, 1, 1, 1, 2, NULL, 'actif', NULL, 'test  ffff', NULL, NULL, NULL, NULL, '8d969eef6ecad3c29a3a629280e686cf0c3f5d5a86aff3ca12020c923adc6c92', 0, 0, NULL, NULL, '877', '2026-03-09 07:37:24', 1, 1, 0),
(58, NULL, 1, 12, 242, 1, 2, NULL, 'actif', NULL, 'Emma solofo', NULL, NULL, NULL, NULL, 'ef797c8118f02dfb649607dd5d3f8c7623048c9c063d532cc95c5ed7a898a64f', 0, 0, NULL, NULL, '22', '2026-08-06 08:04:49', 1, 1, 0),
(60, 1, 1, 8, 2, 1, 2, NULL, 'actif', '2026-08-12', 'RAKOTONIRINA Manankasina', NULL, NULL, NULL, NULL, 'af3b7a26bddd5de85f59064ba3533a078aef0c8402b6e5533fd70b9c5820b0eb', 0, 0, NULL, NULL, '125487', '2026-08-11 10:03:50', 1, 1, 0),
(61, 2, 2, 34, 266, NULL, 16, NULL, 'actif', '2023-09-14', 'TEST EMPLOYE POUR ASSIGNER UN MANAGER', 'employerun@gmailcom', NULL, '$2y$12$xZlxRpp93Tdtpk90RXAG/.RD6X0zCDY1KNFIVqT.wpWurxLk.WfD.', NULL, '64800a72cdfd8e7dfe65fbcfb49b04f8858b294c55374d4a30fc1ab8bf676003', 0, 0, NULL, NULL, '1', '2026-08-28 16:17:41', 1, 2, 0),
(62, 2, 2, NULL, 264, NULL, 16, NULL, 'actif', '2023-12-14', 'RAHARIMANANA ALEX', 'alex@gmail.com', NULL, '$2y$12$xkVBqOzApbnTKxjWe2anSeVTOBjyCEaH42pCcn5i.SQps0o/wNwrK', NULL, '9df33304046cf50b54a3198b2bb6bc5681d9b671ccb99cb6a0961f22b65db37f', 0, 0, NULL, NULL, 'IM 1562', '2026-08-31 09:29:24', 1, 2, 0),
(63, 2, 2, 34, 236, NULL, 16, NULL, 'actif', '2023-12-14', 'testemanager', NULL, NULL, NULL, NULL, '2680d8152dabba84c80a50e98afbcd84885b01a67ae276fdb1807fb3cfa66abb', 0, 0, NULL, NULL, '13', '2026-08-31 13:20:34', 0, 2, 0),
(64, 1, 1, 33, 234, NULL, NULL, NULL, 'actif', '2026-07-14', 'tetsecreeremployedepuis adminsiege', NULL, NULL, NULL, NULL, '9718cb0ca7d6704e6683cbc76a45bedd8ad09cd8903b37e77ed9b69e1569bb00', 0, 0, NULL, NULL, 'EMP00161', '2026-08-31 13:22:37', 0, 1, 0),
(65, 1, 1, 12, 279, NULL, 2, NULL, 'actif', '2023-09-03', 'tetsecreeremployedepuis adminsiege2', NULL, NULL, NULL, NULL, 'f3b47ca7ccc39e01686991ab6b8fcb035bb726f190d996063c72ba2ed040a211', 0, 0, NULL, NULL, '471', '2026-08-31 13:25:19', 0, 1, 0);

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
(32, 'test v3', NULL, 'LA REUNION', -12.00000000, 20.00000000, 30.00000000, '2026-03-11 05:37:47', 0, 1, 0);

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
(11, 'fdffdfd', NULL, 0, '2026-03-11 08:49:23', 'ae', 1);

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
(7, 0, 'Directeur général / Direction', 'N6', 6, 1, NULL, '2026-08-18 09:18:48', '2026-08-18 09:18:48');

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
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `job_titles`
--

INSERT INTO `job_titles` (`id`, `company_id`, `name`, `code`, `hierarchy_level_id`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, 0, 'Manœuvre / Ouvrier', 'BTP-MAN', 1, NULL, '2026-08-18 09:22:04', '2026-08-18 09:22:04'),
(3, 0, 'Maçon / Électricien / Plombier', 'BTP-ART', 1, NULL, '2026-08-18 09:22:04', '2026-08-18 09:22:04'),
(5, 0, 'Ouvrier qualifié / Chef d\'équipe', 'BTP-OQ', 2, NULL, '2026-08-18 09:22:04', '2026-08-18 09:22:04'),
(7, 0, 'Chef de chantier', 'BTP-CC', 3, NULL, '2026-08-18 09:22:04', '2026-08-18 09:22:04'),
(9, 0, 'Conducteur de travaux', 'BTP-CT', 4, NULL, '2026-08-18 09:22:04', '2026-08-18 09:22:04'),
(11, 0, 'Responsable travaux', 'BTP-RT', 5, NULL, '2026-08-18 09:22:04', '2026-08-18 09:22:04'),
(13, 0, 'Directeur technique / Directeur travaux', 'BTP-DT', 6, NULL, '2026-08-18 09:22:04', '2026-08-18 09:22:04'),
(15, 0, 'Directeur général / Gérant', 'BTP-DG', 7, NULL, '2026-08-18 09:22:04', '2026-08-18 09:22:04'),
(63, 0, 'Téléconseiller / Agent', 'CC-AGT', 1, NULL, '2026-08-18 10:08:27', '2026-08-18 10:08:27'),
(65, 0, 'Agent senior / Référent', 'CC-SNR', 2, NULL, '2026-08-18 10:08:27', '2026-08-18 10:08:27'),
(67, 0, 'Team Leader', 'CC-TL', 2, NULL, '2026-08-18 10:08:27', '2026-08-18 10:08:27'),
(69, 0, 'Superviseur', 'CC-SUP', 3, NULL, '2026-08-18 10:08:27', '2026-08-18 10:08:27'),
(71, 0, 'Responsable plateau', 'CC-RP', 4, NULL, '2026-08-18 10:08:27', '2026-08-18 10:08:27'),
(73, 0, 'Operations Manager', 'CC-OM', 5, NULL, '2026-08-18 10:08:27', '2026-08-18 10:08:27'),
(75, 0, 'Directeur des opérations', 'CC-DO', 6, NULL, '2026-08-18 10:08:27', '2026-08-18 10:08:27'),
(77, 0, 'Directeur général', 'CC-DG', 7, NULL, '2026-08-18 10:08:27', '2026-08-18 10:08:27'),
(94, 0, 'Formateur', 'FOR-FORM', 1, NULL, '2026-08-18 10:08:27', '2026-08-18 10:08:27'),
(96, 0, 'Assistant administratif', 'FOR-AST', 1, NULL, '2026-08-18 10:08:27', '2026-08-18 10:08:27'),
(98, 0, 'Formateur référent', 'FOR-FR', 2, NULL, '2026-08-18 10:08:27', '2026-08-18 10:08:27'),
(100, 0, 'Coordinateur pédagogique', 'FOR-CP', 3, NULL, '2026-08-18 10:08:27', '2026-08-18 10:08:27'),
(102, 0, 'Responsable pédagogique', 'FOR-RP', 4, NULL, '2026-08-18 10:08:27', '2026-08-18 10:08:27'),
(104, 0, 'Responsable administratif', 'FOR-RA', 4, NULL, '2026-08-18 10:08:27', '2026-08-18 10:08:27'),
(106, 0, 'Responsable de centre', 'FOR-RC', 5, NULL, '2026-08-18 10:08:27', '2026-08-18 10:08:27'),
(108, 0, 'Directeur de centre', 'FOR-DC', 6, NULL, '2026-08-18 10:08:27', '2026-08-18 10:08:27'),
(110, 0, 'Directeur général', 'FOR-DG', 7, NULL, '2026-08-18 10:08:27', '2026-08-18 10:08:27'),
(125, 0, 'Vendeur / Caissier / Magasinier', 'COM-VEN', 1, NULL, '2026-08-18 10:08:27', '2026-08-18 10:08:27'),
(127, 0, 'Vendeur senior / Chef de rayon', 'COM-VS', 2, NULL, '2026-08-18 10:08:27', '2026-08-18 10:08:27'),
(129, 0, 'Responsable rayon', 'COM-RR', 3, NULL, '2026-08-18 10:08:27', '2026-08-18 10:08:27'),
(131, 0, 'Responsable magasin', 'COM-RM', 4, NULL, '2026-08-18 10:08:27', '2026-08-18 10:08:27'),
(133, 0, 'Responsable régional', 'COM-RR', 5, NULL, '2026-08-18 10:08:27', '2026-08-18 10:08:27'),
(135, 0, 'Directeur commercial / Directeur réseau', 'COM-DC', 6, NULL, '2026-08-18 10:08:27', '2026-08-18 10:08:27'),
(137, 0, 'Direction générale', 'COM-DG', 7, NULL, '2026-08-18 10:08:27', '2026-08-18 10:08:27'),
(140, 0, 'Ouvrier / Opérateur', 'PROD-OP', 1, NULL, '2026-08-18 10:08:27', '2026-08-18 10:08:27'),
(142, 0, 'Opérateur senior / Chef d\'équipe', 'PROD-OS', 2, NULL, '2026-08-18 10:08:27', '2026-08-18 10:08:27'),
(144, 0, 'Superviseur production', 'PROD-SP', 3, NULL, '2026-08-18 10:08:27', '2026-08-18 10:08:27'),
(146, 0, 'Responsable production', 'PROD-RP', 4, NULL, '2026-08-18 10:08:27', '2026-08-18 10:08:27'),
(148, 0, 'Responsable usine', 'PROD-RU', 5, NULL, '2026-08-18 10:08:27', '2026-08-18 10:08:27'),
(150, 0, 'Directeur industriel', 'PROD-DI', 6, NULL, '2026-08-18 10:08:27', '2026-08-18 10:08:27'),
(152, 0, 'Direction générale', 'PROD-DG', 7, NULL, '2026-08-18 10:08:27', '2026-08-18 10:08:27'),
(155, 0, 'Chauffeur / Livreur', 'LOG-CHAUF', 1, NULL, '2026-08-18 10:08:27', '2026-08-18 10:08:27'),
(157, 0, 'Manutentionnaire', 'LOG-MAN', 1, NULL, '2026-08-18 10:08:27', '2026-08-18 10:08:27'),
(159, 0, 'Chef d\'équipe', 'LOG-CE', 2, NULL, '2026-08-18 10:08:27', '2026-08-18 10:08:27'),
(161, 0, 'Chef de parc / Superviseur', 'LOG-CP', 3, NULL, '2026-08-18 10:08:27', '2026-08-18 10:08:27'),
(163, 0, 'Responsable exploitation', 'LOG-RE', 4, NULL, '2026-08-18 10:08:27', '2026-08-18 10:08:27'),
(165, 0, 'Responsable logistique', 'LOG-RL', 4, NULL, '2026-08-18 10:08:27', '2026-08-18 10:08:27'),
(167, 0, 'Directeur exploitation', 'LOG-DE', 5, NULL, '2026-08-18 10:08:27', '2026-08-18 10:08:27'),
(169, 0, 'Directeur transport / Directeur opérations', 'LOG-DT', 6, NULL, '2026-08-18 10:08:27', '2026-08-18 10:08:27'),
(171, 0, 'Direction générale', 'LOG-DG', 7, NULL, '2026-08-18 10:08:27', '2026-08-18 10:08:27'),
(186, 0, 'Serveur', 'HOT-SERV', 1, NULL, '2026-08-18 10:08:27', '2026-08-18 10:08:27'),
(188, 0, 'Femme/Valet de chambre', 'HOT-VAL', 1, NULL, '2026-08-18 10:08:27', '2026-08-18 10:08:27'),
(190, 0, 'Commis', 'HOT-COM', 1, NULL, '2026-08-18 10:08:27', '2026-08-18 10:08:27'),
(192, 0, 'Chef de rang / Référent', 'HOT-CR', 2, NULL, '2026-08-18 10:08:27', '2026-08-18 10:08:27'),
(194, 0, 'Maître d\'hôtel / Chef de cuisine', 'HOT-MH', 3, NULL, '2026-08-18 10:08:27', '2026-08-18 10:08:27'),
(196, 0, 'Responsable restauration', 'HOT-RR', 4, NULL, '2026-08-18 10:08:27', '2026-08-18 10:08:27'),
(198, 0, 'Responsable hébergement', 'HOT-RH', 4, NULL, '2026-08-18 10:08:27', '2026-08-18 10:08:27'),
(200, 0, 'Directeur d\'exploitation', 'HOT-DE', 5, NULL, '2026-08-18 10:08:27', '2026-08-18 10:08:27'),
(202, 0, 'Directeur hôtel', 'HOT-DH', 6, NULL, '2026-08-18 10:08:27', '2026-08-18 10:08:27'),
(204, 0, 'Direction générale / Groupe', 'HOT-DG', 7, NULL, '2026-08-18 10:08:27', '2026-08-18 10:08:27'),
(217, 0, 'Employé / Agent', 'GEN-EMP', 1, NULL, '2026-08-18 10:08:27', '2026-08-18 10:08:27'),
(219, 0, 'Agent senior / Référent', 'GEN-AS', 2, NULL, '2026-08-18 10:08:27', '2026-08-18 10:08:27'),
(221, 0, 'Chef d\'équipe / Superviseur', 'GEN-CE', 3, NULL, '2026-08-18 10:08:27', '2026-08-18 10:08:27'),
(223, 0, 'Responsable de service', 'GEN-RS', 4, NULL, '2026-08-18 10:08:27', '2026-08-18 10:08:27'),
(225, 0, 'Manager / Chef de département', 'GEN-MGR', 5, NULL, '2026-08-18 10:08:27', '2026-08-18 10:08:27'),
(227, 0, 'Directeur', 'GEN-DIR', 6, NULL, '2026-08-18 10:08:27', '2026-08-18 10:08:27'),
(229, 0, 'Directeur général', 'GEN-DG', 7, NULL, '2026-08-18 10:08:27', '2026-08-18 10:08:27'),
(232, 0, 'Assistant RH', 'RH-AST', 1, NULL, '2026-08-18 10:08:28', '2026-08-18 10:08:28'),
(234, 0, 'Assistant administratif', 'RH-ADM', 1, NULL, '2026-08-18 10:08:28', '2026-08-18 10:08:28'),
(236, 0, 'Chargé de recrutement', 'RH-REC', 2, NULL, '2026-08-18 10:08:28', '2026-08-18 10:08:28'),
(238, 0, 'Responsable RH', 'RH-RH', 4, NULL, '2026-08-18 10:08:28', '2026-08-18 10:08:28'),
(240, 0, 'Manager RH', 'RH-MGR', 5, NULL, '2026-08-18 10:08:28', '2026-08-18 10:08:28'),
(242, 0, 'Directeur RH', 'RH-DIR', 6, NULL, '2026-08-18 10:08:28', '2026-08-18 10:08:28'),
(247, 0, 'Comptable', 'FIN-CPT', 1, NULL, '2026-08-18 10:08:28', '2026-08-18 10:08:28'),
(249, 0, 'Assistant comptable', 'FIN-AST', 1, NULL, '2026-08-18 10:08:28', '2026-08-18 10:08:28'),
(251, 0, 'Responsable comptable', 'FIN-RC', 4, NULL, '2026-08-18 10:08:28', '2026-08-18 10:08:28'),
(253, 0, 'Directeur financier', 'FIN-DF', 6, NULL, '2026-08-18 10:08:28', '2026-08-18 10:08:28'),
(262, 0, 'Technicien support', 'IT-SUP', 1, NULL, '2026-08-18 10:08:28', '2026-08-18 10:08:28'),
(264, 0, 'Développeur', 'IT-DEV', 2, NULL, '2026-08-18 10:08:28', '2026-08-18 10:08:28'),
(266, 0, 'Analyste', 'IT-ANA', 3, NULL, '2026-08-18 10:08:28', '2026-08-18 10:08:28'),
(268, 0, 'Chef de projet IT', 'IT-CP', 4, NULL, '2026-08-18 10:08:28', '2026-08-18 10:08:28'),
(270, 0, 'Responsable informatique', 'IT-RI', 5, NULL, '2026-08-18 10:08:28', '2026-08-18 10:08:28'),
(272, 0, 'Directeur technique', 'IT-DT', 6, NULL, '2026-08-18 10:08:28', '2026-08-18 10:08:28'),
(277, 0, 'Assistant marketing', 'MKT-AST', 1, NULL, '2026-08-18 10:08:28', '2026-08-18 10:08:28'),
(279, 0, 'Chargé de communication', 'MKT-COM', 2, NULL, '2026-08-18 10:08:28', '2026-08-18 10:08:28'),
(281, 0, 'Responsable marketing', 'MKT-RM', 4, NULL, '2026-08-18 10:08:28', '2026-08-18 10:08:28'),
(283, 0, 'Directeur marketing', 'MKT-DM', 6, NULL, '2026-08-18 10:08:28', '2026-08-18 10:08:28'),
(292, 0, 'Agent qualité', 'QHSE-AGT', 1, NULL, '2026-08-18 10:08:28', '2026-08-18 10:08:28'),
(294, 0, 'Technicien qualité', 'QHSE-TECH', 2, NULL, '2026-08-18 10:08:28', '2026-08-18 10:08:28'),
(296, 0, 'Responsable qualité', 'QHSE-RQ', 4, NULL, '2026-08-18 10:08:28', '2026-08-18 10:08:28'),
(298, 0, 'Responsable QHSE', 'QHSE-RHSE', 5, NULL, '2026-08-18 10:08:28', '2026-08-18 10:08:28'),
(307, 0, 'Acheteur', 'ACH-ACH', 2, NULL, '2026-08-18 10:08:28', '2026-08-18 10:08:28'),
(309, 0, 'Responsable achats', 'ACH-RA', 4, NULL, '2026-08-18 10:08:28', '2026-08-18 10:08:28'),
(311, 0, 'Directeur achats', 'ACH-DA', 6, NULL, '2026-08-18 10:08:28', '2026-08-18 10:08:28'),
(314, 0, 'Technicien maintenance', 'MAINT-TECH', 1, NULL, '2026-08-18 10:08:28', '2026-08-18 10:08:28'),
(316, 0, 'Chef d\'équipe maintenance', 'MAINT-CE', 3, NULL, '2026-08-18 10:08:28', '2026-08-18 10:08:28'),
(318, 0, 'Responsable maintenance', 'MAINT-RM', 4, NULL, '2026-08-18 10:08:28', '2026-08-18 10:08:28'),
(320, 0, 'Directeur maintenance', 'MAINT-DM', 6, NULL, '2026-08-18 10:08:28', '2026-08-18 10:08:28'),
(329, 0, 'Agent de sécurité', 'SEC-AGT', 1, NULL, '2026-08-18 10:08:28', '2026-08-18 10:08:28'),
(331, 0, 'Chef d\'équipe sécurité', 'SEC-CE', 3, NULL, '2026-08-18 10:08:28', '2026-08-18 10:08:28'),
(333, 0, 'Responsable sécurité', 'SEC-RS', 4, NULL, '2026-08-18 10:08:28', '2026-08-18 10:08:28'),
(336, 0, 'Conseiller client', 'SC-CONS', 1, NULL, '2026-08-18 10:08:28', '2026-08-18 10:08:28'),
(338, 0, 'Agent service client', 'SC-AGT', 1, NULL, '2026-08-18 10:08:28', '2026-08-18 10:08:28'),
(340, 0, 'Responsable service client', 'SC-RSC', 4, NULL, '2026-08-18 10:08:28', '2026-08-18 10:08:28'),
(342, 0, 'Directrice RH', 'DRHF', 6, NULL, '2026-08-31 16:07:05', '2026-08-31 16:07:05'),
(343, 0, 'Directrice RH', 'Directrice', NULL, NULL, '2026-08-31 16:07:50', '2026-08-31 16:07:50'),
(344, 0, 'Directrice RH', 'DRH', 6, NULL, '2026-09-01 15:11:44', '2026-09-01 15:11:44'),
(345, 0, 'Directrice RH', 'DRH GEO', 6, NULL, '2026-09-01 15:16:04', '2026-09-01 15:16:04'),
(346, 0, 'Directrice RH FEMME', 'DRF', 6, NULL, '2026-09-01 15:16:58', '2026-09-01 15:16:58');

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
(50, 1, 13, 13, 25.00, 0.00, 0.00, 25.00, 0.00, '2026-08-26 15:00:43', '2026-08-26 15:00:43'),
(51, 2, 13, 13, 8.00, 0.00, 0.00, 8.00, 0.00, '2026-08-26 15:00:43', '2026-08-26 15:00:43'),
(52, 3, 13, 13, 25.00, 0.00, 0.00, 25.00, 0.00, '2026-08-26 15:00:43', '2026-08-26 15:00:43'),
(53, 5, 13, 13, 25.00, 5.00, 0.00, 20.00, 0.00, '2026-08-26 15:00:43', '2026-08-31 08:57:34'),
(54, 62, 17, 14, 30.00, 0.00, 0.00, 30.00, 0.00, '2026-08-31 10:33:36', '2026-08-31 10:33:36'),
(55, 5, 14, 15, 30.00, 0.00, 0.00, 30.00, 0.00, '2026-08-31 15:54:22', '2026-08-31 16:02:43');

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
(90, 1, 13, 13, 25.00, 'opening', NULL, NULL, NULL, 'initial CP 2026', '\"{\\\"type\\\":\\\"initialization\\\"}\"', 2, '2026-08-26 15:00:43'),
(91, 2, 13, 13, 8.00, 'opening', NULL, NULL, NULL, 'initial CP 2026', '\"{\\\"type\\\":\\\"initialization\\\"}\"', 2, '2026-08-26 15:00:43'),
(92, 3, 13, 13, 25.00, 'opening', NULL, NULL, NULL, 'initial CP 2026', '\"{\\\"type\\\":\\\"initialization\\\"}\"', 2, '2026-08-26 15:00:43'),
(93, 5, 13, 13, 25.00, 'opening', NULL, NULL, NULL, 'initial CP 2026', '\"{\\\"type\\\":\\\"initialization\\\"}\"', 2, '2026-08-26 15:00:43'),
(94, 5, 13, 13, -2.00, 'debit', NULL, 47, 'leave_request', 'Validation de congé - ', '\"{\\\"type\\\":\\\"debit\\\"}\"', 2, '2026-08-27 16:08:35'),
(95, 5, 13, 13, 2.00, 'reversal', NULL, 47, 'leave_request', 'Annulation de congé - ', '\"{\\\"type\\\":\\\"reversal\\\",\\\"cancelled_request\\\":true}\"', 5, '2026-08-27 16:09:05'),
(96, 5, 13, 13, -5.00, 'debit', NULL, 46, 'leave_request', 'Validation de congé - teste', '\"{\\\"type\\\":\\\"debit\\\"}\"', 2, '2026-08-31 08:57:34'),
(97, 62, 17, 14, 30.00, 'opening', NULL, NULL, NULL, 'solde initial CP 2026', '\"{\\\"type\\\":\\\"initialization\\\"}\"', 18, '2026-08-31 10:33:36'),
(98, 5, 14, 15, 30.00, 'opening', NULL, NULL, NULL, 'solde initial Maladie 2026', '\"{\\\"type\\\":\\\"initialization\\\"}\"', 2, '2026-08-31 15:54:22'),
(99, 5, 14, 15, -9.00, 'debit', NULL, 55, 'leave_request', 'Validation de congé - ', '\"{\\\"type\\\":\\\"debit\\\"}\"', 2, '2026-08-31 15:56:59'),
(100, 5, 14, 15, 9.00, 'reversal', NULL, 55, 'leave_request', 'Annulation de congé - ', '\"{\\\"type\\\":\\\"reversal\\\",\\\"cancelled_request\\\":true}\"', 5, '2026-08-31 16:02:43');

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
(13, 1, 13, 'Periode année 2026-2027', '2026-09-01', '2026-12-30', '2026-09-11', 0, NULL, NULL, 0, 'open', 1, 0, NULL, '2026-08-21 12:14:41', '2026-08-31 14:53:58'),
(14, 2, 17, 'Conge paye RUN 2026-2027', '2026-09-01', '2026-11-30', '2026-10-31', 0, NULL, NULL, 0, 'open', 1, 0, NULL, '2026-08-31 10:29:48', '2026-08-31 10:29:48'),
(15, 1, 14, 'Periode année 2026-2027 Maladie', '2026-01-01', '2026-12-31', NULL, 0, NULL, NULL, 0, 'open', 1, 0, NULL, '2026-08-31 15:36:26', '2026-08-31 15:36:26');

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
(3, 1, NULL, 'Politique Standar  CP', 'business_days', NULL, 'skip', 'none', 'sunday_only', 1, 0, 0, 1, NULL, NULL, NULL, '2026-08-21 12:04:59', '2026-08-24 10:05:28'),
(4, 1, NULL, 'Politique Standar Maladie', 'working_days', NULL, 'skip', 'none', 'saturday_sunday', 1, 0, 0, 1, NULL, NULL, NULL, '2026-08-21 12:05:50', '2026-08-21 12:05:50'),
(5, NULL, NULL, 'Politique par défaut (auto-créée)', 'working_days', NULL, 'skip', 'none', 'saturday_sunday', 1, 1, 0, 1, NULL, NULL, NULL, '2026-08-24 08:06:55', '2026-08-24 08:06:55'),
(6, 1, NULL, 'Politique Standar  Sans solde', 'working_days', NULL, 'skip', 'none', 'saturday_sunday', 1, 0, 0, 1, NULL, NULL, NULL, '2026-08-31 14:34:17', '2026-08-31 14:34:17');

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
(12, 3, NULL, 1, NULL, NULL, NULL, NULL, 50, 1, 'site', NULL, NULL, NULL, NULL, NULL, '2026-08-24 10:06:12', '2026-08-24 10:06:12'),
(13, 4, NULL, 1, NULL, NULL, NULL, 5, 50, 1, 'individual', NULL, NULL, NULL, NULL, NULL, '2026-08-31 15:55:12', '2026-08-31 15:55:12'),
(14, 4, NULL, 1, NULL, NULL, NULL, NULL, 50, 1, 'site', NULL, NULL, NULL, NULL, NULL, '2026-09-01 14:12:01', '2026-09-01 14:12:01');

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
(46, 5, 13, 13, '2026-10-17', '2026-10-22', 5.00, 'approved', NULL, 'teste', NULL, NULL, 2, '2026-08-31 08:57:34', NULL, NULL, NULL, '2026-08-27 13:09:51', '2026-08-31 08:57:34', NULL, 1, NULL, 0),
(47, 5, 13, 13, '2026-09-11', '2026-09-12', 2.00, 'cancelled', NULL, NULL, NULL, NULL, 2, '2026-08-27 16:08:35', NULL, NULL, NULL, '2026-08-27 13:48:37', '2026-08-27 16:09:05', NULL, 1, NULL, 0),
(48, 5, 13, 13, '2026-09-13', '2026-09-15', 2.00, 'draft', NULL, 'test', 'test', NULL, NULL, NULL, NULL, NULL, NULL, '2026-08-27 15:45:55', '2026-08-27 15:45:55', NULL, 1, NULL, 0),
(49, 62, 13, 13, '2026-12-10', '2026-12-16', 6.00, 'draft', NULL, NULL, 'test autre utilisateur', NULL, NULL, NULL, NULL, NULL, NULL, '2026-08-31 09:37:39', '2026-08-31 10:42:38', '2026-08-31 10:42:38', 1, NULL, 0),
(50, 62, 17, 14, '2026-09-11', '2026-09-14', 2.00, 'draft', NULL, 'TEST VER AUTRE ADMIN', 'TEST POUR VOIR OU LE DEMANDE A ETE ENVOYER', NULL, NULL, NULL, NULL, NULL, NULL, '2026-08-31 10:43:23', '2026-08-31 10:48:39', '2026-08-31 10:48:39', 1, NULL, 0),
(51, 62, 17, 14, '2026-10-10', '2026-10-14', 3.00, 'pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-08-31 10:45:38', '2026-08-31 10:46:00', NULL, 1, NULL, 0),
(52, 62, 17, 14, '2026-11-12', '2026-11-13', 2.00, 'draft', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-08-31 10:49:20', '2026-08-31 10:49:20', NULL, 1, NULL, 0),
(53, 62, 17, 14, '2026-09-15', '2026-09-25', 9.00, 'draft', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-08-31 12:36:57', '2026-08-31 12:36:57', NULL, 1, NULL, 0),
(54, 5, 13, 13, '2026-11-11', '2026-11-30', 17.00, 'pending', NULL, NULL, 'TEST', NULL, NULL, NULL, NULL, NULL, NULL, '2026-08-31 15:31:52', '2026-08-31 15:31:52', NULL, 1, NULL, 0),
(55, 5, 14, 15, '2026-09-14', '2026-09-24', 9.00, 'cancelled', NULL, NULL, NULL, NULL, 2, '2026-08-31 15:56:59', NULL, NULL, NULL, '2026-08-31 15:56:25', '2026-08-31 16:02:43', NULL, 1, NULL, 0),
(56, 5, 14, 15, '2026-09-01', '2026-09-03', 3.00, 'approved', NULL, 'je suis malade', NULL, NULL, 2, '2026-09-01 14:46:44', NULL, NULL, NULL, '2026-09-01 14:46:17', '2026-09-01 14:46:44', NULL, 1, NULL, 0);

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

--
-- Déchargement des données de la table `leave_request_attachments`
--

INSERT INTO `leave_request_attachments` (`id`, `leave_request_id`, `file_name`, `file_path`, `file_size`, `mime_type`, `uploaded_by`, `created_at`, `updated_at`) VALUES
(14, 51, 'Capture d’écran 2026-06-03 153354.png', 'leave_attachments/51/1788173138_Capture d’écran 2026-06-03 153354.png', 265720, 'image/png', ' ', '2026-08-31 10:45:39', '2026-08-31 10:45:39'),
(15, 52, 'Capture d’écran 2026-06-03 153354.png', 'leave_attachments/52/1788173360_Capture d’écran 2026-06-03 153354.png', 265720, 'image/png', ' ', '2026-08-31 10:49:20', '2026-08-31 10:49:20'),
(16, 55, 'Capture d’écran 2026-06-09 154545.png', 'leave_attachments/55/1788191785_Capture d’écran 2026-06-09 154545.png', 246849, 'image/png', ' ', '2026-08-31 15:56:26', '2026-08-31 15:56:26'),
(17, 56, 'Capture d’écran 2026-06-03 153354.png', 'leave_attachments/56/1788273977_Capture d’écran 2026-06-03 153354.png', 265720, 'image/png', ' ', '2026-09-01 14:46:17', '2026-09-01 14:46:17');

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
(13, 1, 'Conge paye', 'CP', 'days', 1, 'never', NULL, 0, NULL, '#85d96d', 1, 1, NULL, '2026-08-21 12:00:05', '2026-08-31 15:30:57', 15, 17.00, 0, 'manual', NULL, 1),
(14, 1, 'MALADIE', 'MLD', 'days', 1, 'always', NULL, 0, NULL, '#ee1111', 1, 1, NULL, '2026-08-21 12:01:26', '2026-09-01 14:47:28', 0, NULL, 1, 'manual', NULL, 1),
(15, NULL, 'TST conge', 'TST', 'days', 1, 'never', NULL, 0, NULL, '#b7104a', 1, 1, '2026-08-27 10:43:46', '2026-08-27 10:34:55', '2026-08-27 10:43:46', 0, NULL, 0, 'manual', NULL, 1),
(16, NULL, 'TST', 'test', 'days', 1, 'never', NULL, 0, NULL, '#10B981', 1, 0, '2026-08-27 12:25:07', '2026-08-27 10:44:12', '2026-08-27 12:25:07', 0, NULL, 0, 'manual', NULL, 1),
(17, 2, 'Congé payés Run telemat', 'CPR', 'days', 1, 'always', NULL, 0, NULL, '#10B981', 1, 0, NULL, '2026-08-31 09:54:38', '2026-08-31 09:54:38', 10, 15.00, 0, 'manual', NULL, 1),
(18, 1, 'Sans solde', 'SS', 'days', 0, 'never', NULL, 1, 2, '#10B981', 1, 0, '2026-08-31 14:24:01', '2026-08-31 14:22:30', '2026-08-31 14:24:01', 2, 3.00, 0, 'manual', NULL, 1);

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
-- Structure de la table `leave_workflows`
--

CREATE TABLE `leave_workflows` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `site_id` int(10) UNSIGNED DEFAULT NULL,
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

INSERT INTO `leave_workflows` (`id`, `site_id`, `name`, `description`, `steps`, `is_default`, `is_customizable`, `is_active`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, NULL, 'rien', 'rie', '[{\"order\":1,\"role\":\"manager\",\"label\":\"Validation Manager\",\"description\":\"Le manager valide la demande\"},{\"order\":2,\"role\":\"hr\",\"label\":\"Validation RH\",\"description\":\"Le service RH approuve la demande\"},{\"order\":3,\"role\":\"manager\",\"label\":\"validation\",\"description\":\"f\"}]', 0, 1, 1, NULL, '2026-08-17 16:39:11', '2026-08-17 16:39:11');

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
(40, '2026_08_05_093227_add_checkbox_to_rule_fields_enum', 19);

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

--
-- Déchargement des données de la table `notifications`
--

INSERT INTO `notifications` (`id`, `user_id`, `type`, `title`, `message`, `leave_request_id`, `is_read`, `read_at`, `created_at`, `updated_at`) VALUES
(69, 2, 'leave_pending', ' Nouvelle demande de congé', 'MALALANIRINA Emile Noeline a soumis une demande de Conge paye du 17/10/2026 au 22/10/2026 (5.0 jours)', 46, 0, NULL, '2026-08-27 13:09:51', '2026-08-27 13:09:51'),
(70, 2, 'leave_pending', ' Nouvelle demande de congé', 'MALALANIRINA Emile Noeline a soumis une demande de Conge paye du 11/09/2026 au 12/09/2026 (2.0 jours)', 47, 0, NULL, '2026-08-27 13:48:37', '2026-08-27 13:48:37'),
(71, 2, 'leave_approved', 'Demande de congé approuvée', 'Votre demande de Conge paye du 11/09/2026 au 12/09/2026 a été approuvée.', 47, 0, NULL, '2026-08-27 16:08:35', '2026-08-27 16:08:35'),
(72, 2, 'leave_cancelled', ' Congé annulé', 'Votre congé de Conge paye du 11/09/2026 au 12/09/2026 a été annulé. Vos jours ont été recrédités.', 47, 0, NULL, '2026-08-27 16:09:05', '2026-08-27 16:09:05'),
(73, 2, 'leave_cancelled_manager', ' Congé annulé par l\'employé', 'MALALANIRINA Emile Noeline a annulé son congé de Conge paye du 11/09/2026 au 12/09/2026.', 47, 0, NULL, '2026-08-27 16:09:05', '2026-08-27 16:09:05'),
(74, 2, 'leave_approved', 'Demande de congé approuvée', 'Votre demande de Conge paye du 17/10/2026 au 22/10/2026 a été approuvée.', 46, 0, NULL, '2026-08-31 08:57:34', '2026-08-31 08:57:34'),
(75, 18, 'leave_pending', ' Nouvelle demande de congé', 'RAHARIMANANA ALEX a soumis une demande de Congé payés Run telemat du 10/10/2026 au 14/10/2026 (3.0 jours)', 51, 0, NULL, '2026-08-31 10:46:00', '2026-08-31 10:46:00'),
(76, 2, 'leave_pending', ' Nouvelle demande de congé', 'MALALANIRINA Emile Noeline a soumis une demande de Conge paye du 11/11/2026 au 30/11/2026 (17.0 jours)', 54, 0, NULL, '2026-08-31 15:31:52', '2026-08-31 15:31:52'),
(77, 2, 'leave_pending', ' Nouvelle demande de congé', 'MALALANIRINA Emile Noeline a soumis une demande de MALADIE du 14/09/2026 au 24/09/2026 (9.0 jours)', 55, 0, NULL, '2026-08-31 15:56:35', '2026-08-31 15:56:35'),
(78, 2, 'leave_approved', 'Demande de congé approuvée', 'Votre demande de MALADIE du 14/09/2026 au 24/09/2026 a été approuvée.', 55, 0, NULL, '2026-08-31 15:56:59', '2026-08-31 15:56:59'),
(79, 2, 'leave_cancelled', ' Congé annulé', 'Votre congé de MALADIE du 14/09/2026 au 24/09/2026 a été annulé. Vos jours ont été recrédités.', 55, 0, NULL, '2026-08-31 16:02:43', '2026-08-31 16:02:43'),
(80, 2, 'leave_cancelled_manager', ' Congé annulé par l\'employé', 'MALALANIRINA Emile Noeline a annulé son congé de MALADIE du 14/09/2026 au 24/09/2026.', 55, 0, NULL, '2026-08-31 16:02:43', '2026-08-31 16:02:43'),
(81, 2, 'leave_pending', ' Nouvelle demande de congé', 'MALALANIRINA Emile Noeline a soumis une demande de MALADIE du 01/09/2026 au 03/09/2026 (3.0 jours)', 56, 0, NULL, '2026-09-01 14:46:21', '2026-09-01 14:46:21'),
(82, 2, 'leave_approved', 'Demande de congé approuvée', 'Votre demande de MALADIE du 01/09/2026 au 03/09/2026 a été approuvée.', 56, 0, NULL, '2026-09-01 14:46:44', '2026-09-01 14:46:44');

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

--
-- Déchargement des données de la table `pointages`
--

INSERT INTO `pointages` (`ID`, `employee_id`, `type_`, `auth_method`, `timestamp_`, `latitude`, `longitude`, `photo_path`, `synced`, `SiegeID`, `company_id`) VALUES
(1, 3, 'entry', 'pin', '2025-09-01 08:03:11', -19.01889290, 47.53932970, '', 0, 1, 1),
(2, 2, 'entry', 'rfid', '2025-09-01 08:07:27', -19.01889350, 47.53935320, '', 0, 1, 1),
(3, 2, 'exit', 'rfid', '2025-09-01 12:26:03', -19.01890200, 47.53932950, '', 0, 1, 1),
(4, 3, 'exit', 'pin', '2025-09-01 12:30:03', -19.01888810, 47.53936520, '', 0, 1, 1),
(5, 3, 'entry', 'pin', '2025-09-01 13:59:14', -19.01888780, 47.53935930, '', 0, 1, 1),
(6, 2, 'entry', 'rfid', '2025-09-01 13:59:44', -19.01889590, 47.53933060, '', 0, 1, 1),
(7, 2, 'exit', 'rfid', '2025-09-01 16:30:54', -19.01890090, 47.53933290, '', 0, 1, 1),
(8, 3, 'exit', 'pin', '2025-09-01 17:25:55', -19.01889170, 47.53934090, '', 0, 1, 1),
(9, 3, 'entry', 'pin', '2025-09-02 08:04:31', -19.01889830, 47.53934810, '', 0, 1, 1),
(10, 2, 'entry', 'rfid', '2025-09-02 08:07:19', -19.01889630, 47.53934440, '', 0, 1, 1),
(11, 5, 'entry', 'rfid', '2025-09-02 08:53:17', -19.01889630, 47.53934440, '', 0, 1, 1),
(12, 2, 'exit', 'rfid', '2025-09-02 12:07:20', -19.01890150, 47.53933040, '', 0, 1, 1),
(13, 3, 'exit', 'rfid', '2025-09-02 12:07:44', -19.01889990, 47.53932910, '', 0, 1, 1),
(14, 5, 'exit', 'rfid', '2025-09-02 12:08:43', -19.01889750, 47.53932420, '', 0, 1, 1),
(15, 5, 'entry', 'rfid', '2025-09-02 13:07:39', -19.01889940, 47.53933030, '', 0, 1, 1),
(16, 2, 'entry', 'rfid', '2025-09-02 13:32:31', -19.01889600, 47.53933370, '', 0, 1, 1),
(17, 3, 'entry', 'rfid', '2025-09-02 13:59:22', -19.01889540, 47.53932970, '', 0, 1, 1),
(18, 5, 'exit', 'rfid', '2025-09-02 16:47:49', -19.01889200, 47.53933960, '', 0, 1, 1),
(19, 2, 'exit', 'rfid', '2025-09-02 17:00:19', -19.01890020, 47.53933520, '', 0, 1, 1),
(20, 3, 'exit', 'rfid', '2025-09-02 17:44:51', -19.01889630, 47.53932930, '', 0, 1, 1),
(21, 3, 'entry', 'rfid', '2025-09-03 08:02:12', -19.01889910, 47.53936010, '', 0, 1, 1),
(22, 2, 'entry', 'rfid', '2025-09-03 08:08:18', -19.01889910, 47.53936010, '', 0, 1, 1),
(23, 5, 'entry', 'rfid', '2025-09-03 09:11:13', -19.01889830, 47.53932990, '', 0, 1, 1),
(24, 2, 'exit', 'rfid', '2025-09-03 12:06:57', -19.01888830, 47.53935910, '', 0, 1, 1),
(25, 5, 'exit', 'rfid', '2025-09-03 12:10:37', -19.01889750, 47.53933790, '', 0, 1, 1),
(26, 3, 'exit', 'rfid', '2025-09-03 12:22:23', -19.01889730, 47.53934090, '', 0, 1, 1),
(27, 5, 'entry', 'rfid', '2025-09-03 13:00:23', -19.01889570, 47.53932980, '', 0, 1, 1),
(28, 2, 'entry', 'rfid', '2025-09-03 13:00:35', -19.01889570, 47.53932980, '', 0, 1, 1),
(29, 3, 'entry', 'rfid', '2025-09-03 14:01:13', -19.01888920, 47.53934440, '', 0, 1, 1),
(30, 5, 'exit', 'rfid', '2025-09-03 16:41:06', -19.01889430, 47.53932840, '', 0, 1, 1),
(31, 2, 'exit', 'rfid', '2025-09-03 17:00:21', -19.01888870, 47.53934130, '', 0, 1, 1),
(32, 3, 'exit', 'rfid', '2025-09-03 17:40:28', -19.01889710, 47.53936240, '', 0, 1, 1),
(33, 2, 'entry', 'rfid', '2025-09-04 08:03:01', -19.01889660, 47.53932960, '', 0, 1, 1),
(34, 3, 'entry', 'rfid', '2025-09-04 08:08:19', -19.01889270, 47.53935770, '', 0, 1, 1),
(35, 5, 'entry', 'rfid', '2025-09-04 08:53:29', -19.01889960, 47.53933580, '', 0, 1, 1),
(36, 2, 'exit', 'rfid', '2025-09-04 12:11:39', -19.01889820, 47.53935130, '', 0, 1, 1),
(37, 3, 'exit', 'rfid', '2025-09-04 12:12:20', -19.01890220, 47.53933060, '', 0, 1, 1),
(38, 5, 'exit', 'rfid', '2025-09-04 12:15:35', -19.01889450, 47.53934670, '', 0, 1, 1),
(39, 5, 'entry', 'rfid', '2025-09-04 12:54:38', -19.01889710, 47.53932940, '', 0, 1, 1),
(40, 2, 'entry', 'rfid', '2025-09-04 13:40:08', -19.01889710, 47.53932940, '', 0, 1, 1),
(41, 3, 'entry', 'rfid', '2025-09-04 14:04:46', -19.01889160, 47.53936230, '', 0, 1, 1),
(42, 5, 'exit', 'rfid', '2025-09-04 16:37:14', -19.01890060, 47.53932680, '', 0, 1, 1),
(43, 2, 'exit', 'rfid', '2025-09-04 17:32:05', -19.01889930, 47.53933430, '', 0, 1, 1),
(44, 3, 'exit', 'rfid', '2025-09-04 17:39:16', -19.01888830, 47.53933830, '', 0, 1, 1),
(45, 3, 'entry', 'rfid', '2025-09-05 08:02:29', -19.01889930, 47.53932450, '', 0, 1, 1),
(46, 2, 'entry', 'rfid', '2025-09-05 08:10:08', -19.01889810, 47.53932770, '', 0, 1, 1),
(47, 2, 'exit', 'rfid', '2025-09-05 12:11:00', -19.01890140, 47.53933260, '', 0, 1, 1),
(48, 5, 'exit', 'rfid', '2025-09-05 12:20:40', -19.01889430, 47.53935940, '', 0, 1, 1),
(49, 3, 'exit', 'rfid', '2025-09-05 12:31:54', -19.01890330, 47.53933050, '', 0, 1, 1),
(50, 5, 'entry', 'rfid', '2025-09-05 12:57:52', -19.01889900, 47.53933770, '', 0, 1, 1),
(51, 2, 'entry', 'rfid', '2025-09-05 13:36:53', -19.01889900, 47.53933770, '', 0, 1, 1),
(52, 3, 'entry', 'rfid', '2025-09-05 14:00:07', -19.01889840, 47.53936220, '', 0, 1, 1),
(53, 5, 'exit', 'rfid', '2025-09-05 16:33:54', -19.01889830, 47.53935140, '', 0, 1, 1),
(54, 2, 'exit', 'rfid', '2025-09-05 17:06:36', -19.01889510, 47.53933370, '', 0, 1, 1),
(55, 3, 'exit', 'rfid', '2025-09-05 17:47:02', -19.01888080, 47.53935940, '', 0, 1, 1),
(58, 3, 'entry', 'rfid', '2025-09-06 10:00:07', -19.01889840, 47.53936220, '', 0, 1, 1),
(59, 3, 'exit', 'rfid', '2025-09-06 11:24:59', -19.01888870, 47.53933660, '', 0, 1, 1),
(60, 3, 'entry', 'rfid', '2025-09-08 08:05:48', -19.01888940, 47.53933680, '', 0, 1, 1),
(61, 2, 'entry', 'rfid', '2025-09-08 08:06:11', -19.01889190, 47.53936190, '', 0, 1, 1),
(62, 5, 'entry', 'rfid', '2025-09-08 08:52:58', -19.01889000, 47.53936710, '', 0, 1, 1),
(63, 5, 'exit', 'rfid', '2025-09-08 12:10:21', -19.01889530, 47.53936450, '', 0, 1, 1),
(64, 2, 'exit', 'rfid', '2025-09-08 12:10:57', -19.01889100, 47.53936350, '', 0, 1, 1),
(65, 3, 'exit', 'rfid', '2025-09-08 12:19:01', -19.01889190, 47.53936260, '', 0, 1, 1),
(66, 5, 'entry', 'rfid', '2025-09-08 12:46:44', -19.01889670, 47.53936180, '', 0, 1, 1),
(67, 3, 'entry', 'rfid', '2025-09-08 13:59:37', -19.01889790, 47.53933380, '', 0, 1, 1),
(68, 2, 'entry', 'rfid', '2025-09-08 14:00:05', -19.01889130, 47.53936650, '', 0, 1, 1),
(69, 5, 'exit', 'rfid', '2025-09-08 16:36:49', -19.01889320, 47.53935410, '', 0, 1, 1),
(70, 2, 'exit', 'rfid', '2025-09-08 17:21:49', -19.01889940, 47.53933430, '', 0, 1, 1),
(71, 3, 'exit', 'rfid', '2025-09-08 17:53:51', -19.01889130, 47.53935100, '', 0, 1, 1),
(72, 2, 'entry', 'rfid', '2025-09-09 07:58:32', -19.01889610, 47.53932860, '', 0, 1, 1),
(73, 3, 'entry', 'rfid', '2025-09-09 08:05:08', -19.01888760, 47.53934250, '', 0, 1, 1),
(74, 5, 'entry', 'rfid', '2025-09-09 09:09:46', -19.01888900, 47.53936520, '', 0, 1, 1),
(75, 5, 'exit', 'rfid', '2025-09-09 12:12:18', -19.01889250, 47.53935280, '', 0, 1, 1),
(76, 2, 'exit', 'rfid', '2025-09-09 12:12:47', -19.01889250, 47.53935280, '', 0, 1, 1),
(77, 3, 'exit', 'rfid', '2025-09-09 12:18:33', -19.01889250, 47.53935280, '', 0, 1, 1),
(78, 5, 'entry', 'rfid', '2025-09-09 12:59:13', -19.01888980, 47.53933650, '', 0, 1, 1),
(79, 2, 'entry', 'rfid', '2025-09-09 13:41:28', -19.01888980, 47.53933650, '', 0, 1, 1),
(80, 3, 'entry', 'rfid', '2025-09-09 14:02:23', -19.01889420, 47.53932450, '', 0, 1, 1),
(81, 5, 'exit', 'rfid', '2025-09-09 16:37:56', -19.01888950, 47.53934880, '', 0, 1, 1),
(82, 2, 'exit', 'rfid', '2025-09-09 17:24:32', -19.01889880, 47.53934880, '', 0, 1, 1),
(83, 3, 'exit', 'rfid', '2025-09-09 17:35:07', -19.01890030, 47.53933110, '', 0, 1, 1),
(84, 2, 'entry', 'rfid', '2025-09-10 08:02:44', -19.01889170, 47.53932990, '', 0, 1, 1),
(85, 3, 'entry', 'rfid', '2025-09-10 08:04:53', -19.01889310, 47.53935220, '', 0, 1, 1),
(86, 2, 'exit', 'rfid', '2025-09-10 12:09:50', -19.01888960, 47.53933960, '', 0, 1, 1),
(87, 3, 'exit', 'rfid', '2025-09-10 12:23:49', -19.01889910, 47.53933200, '', 0, 1, 1),
(88, 5, 'entry', 'rfid', '2025-09-10 12:24:06', -19.01889780, 47.53935060, '', 0, 1, 1),
(89, 5, 'exit', 'rfid', '2025-09-10 12:24:33', -19.01889390, 47.53934090, '', 0, 1, 1),
(90, 5, 'entry', 'rfid', '2025-09-10 13:01:18', -19.01889430, 47.53933880, '', 0, 1, 1),
(91, 2, 'entry', 'rfid', '2025-09-10 13:06:49', -19.01889620, 47.53932420, '', 0, 1, 1),
(92, 3, 'entry', 'rfid', '2025-09-10 14:02:47', -19.01888610, 47.53934910, '', 0, 1, 1),
(93, 5, 'exit', 'rfid', '2025-09-10 16:21:24', -19.01889750, 47.53932900, '', 0, 1, 1),
(94, 3, 'exit', 'rfid', '2025-09-10 17:32:07', -19.01889480, 47.53935780, '', 0, 1, 1),
(95, 2, 'exit', 'rfid', '2025-09-10 17:32:29', -19.01889960, 47.53935220, '', 0, 1, 1),
(96, 3, 'entry', 'rfid', '2025-09-11 08:07:18', -19.01889500, 47.53935700, '', 0, 1, 1),
(97, 5, 'entry', 'rfid', '2025-09-11 08:59:02', -19.01890020, 47.53933910, '', 0, 1, 1),
(98, 3, 'exit', 'rfid', '2025-09-11 12:22:49', -19.01889670, 47.53933280, '', 0, 1, 1),
(99, 5, 'exit', 'rfid', '2025-09-11 12:25:34', -19.01889570, 47.53933480, '', 0, 1, 1),
(100, 5, 'entry', 'rfid', '2025-09-11 12:52:34', -19.01889570, 47.53933480, '', 0, 1, 1),
(101, 3, 'entry', 'rfid', '2025-09-11 14:00:59', -19.01889670, 47.53933480, '', 0, 1, 1),
(102, 5, 'exit', 'rfid', '2025-09-11 16:39:11', -19.01888640, 47.53933100, '', 0, 1, 1),
(103, 3, 'exit', 'rfid', '2025-09-11 18:01:19', -19.01889160, 47.53936490, '', 0, 1, 1),
(104, 3, 'entry', 'rfid', '2025-09-12 08:06:01', -19.01888450, 47.53935220, '', 0, 1, 1),
(105, 2, 'entry', 'rfid', '2025-09-12 08:06:22', -19.01889570, 47.53932120, '', 0, 1, 1),
(106, 5, 'entry', 'rfid', '2025-09-12 09:10:07', -19.01889790, 47.53935540, '', 0, 1, 1),
(107, 2, 'exit', 'rfid', '2025-09-12 12:14:17', -19.01890000, 47.53933910, '', 0, 1, 1),
(108, 5, 'exit', 'rfid', '2025-09-12 12:15:07', -19.01888820, 47.53936570, '', 0, 1, 1),
(109, 3, 'exit', 'rfid', '2025-09-12 12:25:35', -19.01888880, 47.53936740, '', 0, 1, 1),
(110, 5, 'entry', 'rfid', '2025-09-12 12:48:40', -19.01888490, 47.53936070, '', 0, 1, 1),
(111, 2, 'entry', 'rfid', '2025-09-12 13:35:43', -19.01888610, 47.53936480, '', 0, 1, 1),
(112, 3, 'entry', 'rfid', '2025-09-12 13:59:01', -19.01888560, 47.53936540, '', 0, 1, 1),
(113, 5, 'exit', 'rfid', '2025-09-12 16:40:35', -19.01889350, 47.53936100, '', 0, 1, 1),
(114, 2, 'exit', 'rfid', '2025-09-12 17:11:10', -19.01889220, 47.53932870, '', 0, 1, 1),
(115, 3, 'exit', 'rfid', '2025-09-12 17:27:59', -19.01889180, 47.53936140, '', 0, 1, 1),
(116, 3, 'entry', 'rfid', '2025-09-13 09:09:25', -19.01889520, 47.53932740, '', 0, 1, 1),
(117, 3, 'exit', 'rfid', '2025-09-13 11:16:16', -19.01889200, 47.53933180, '', 0, 1, 1),
(118, 3, 'entry', 'rfid', '2025-09-15 08:07:04', -19.01888680, 47.53934840, '', 0, 1, 1),
(119, 2, 'entry', 'rfid', '2025-09-15 08:10:03', -19.01888680, 47.53934840, '', 0, 1, 1),
(120, 5, 'entry', 'rfid', '2025-09-15 08:51:10', -19.01889300, 47.53935310, '', 0, 1, 1),
(121, 3, 'exit', 'rfid', '2025-09-15 12:17:48', -19.01889650, 47.53933820, '', 0, 1, 1),
(122, 2, 'exit', 'rfid', '2025-09-15 12:18:03', -19.01888190, 47.53936470, '', 0, 1, 1),
(123, 5, 'exit', 'rfid', '2025-09-15 12:18:41', -19.01888980, 47.53936390, '', 0, 1, 1),
(124, 2, 'entry', 'rfid', '2025-09-15 13:14:07', -19.01890190, 47.53933270, '', 0, 1, 1),
(125, 5, 'entry', 'rfid', '2025-09-15 13:16:26', -19.01890190, 47.53933270, '', 0, 1, 1),
(126, 3, 'entry', 'rfid', '2025-09-15 13:48:41', -19.01889130, 47.53935400, '', 0, 1, 1),
(127, 5, 'exit', 'rfid', '2025-09-15 16:48:27', -19.01888860, 47.53935160, '', 0, 1, 1),
(128, 2, 'exit', 'rfid', '2025-09-15 17:11:03', -19.01889250, 47.53936070, '', 0, 1, 1),
(129, 3, 'exit', 'rfid', '2025-09-15 17:20:29', -19.01889830, 47.53933850, '', 0, 1, 1),
(130, 2, 'entry', 'rfid', '2025-09-17 07:52:38', -19.01886500, 47.53940010, '', 0, 1, 1),
(131, 5, 'entry', 'rfid', '2025-09-17 08:48:54', -19.01885310, 47.53939740, '', 0, 1, 1),
(133, 2, 'exit', 'rfid', '2025-09-17 12:07:15', -19.01886300, 47.53940420, '', 0, 1, 1),
(134, 5, 'exit', 'rfid', '2025-09-17 12:08:27', -19.01885580, 47.53939130, '', 0, 1, 1),
(135, 5, 'entry', 'rfid', '2025-09-17 12:39:19', -19.01885690, 47.53938920, '', 0, 1, 1),
(136, 2, 'entry', 'rfid', '2025-09-17 13:36:21', -19.01885690, 47.53938920, '', 0, 1, 1),
(137, 5, 'exit', 'rfid', '2025-09-17 16:30:35', -19.01885810, 47.53939080, '', 0, 1, 1),
(138, 2, 'exit', 'rfid', '2025-09-17 16:41:39', -19.01885720, 47.53940110, '', 0, 1, 1),
(139, 3, 'entry', 'rfid', '2025-09-18 08:01:27', -19.01886210, 47.53938530, '', 0, 1, 1),
(140, 2, 'entry', 'rfid', '2025-09-18 08:06:58', -19.01885850, 47.53938750, '', 0, 1, 1),
(141, 5, 'entry', 'rfid', '2025-09-18 08:50:51', -19.01885600, 47.53938940, '', 0, 1, 1),
(142, 2, 'exit', 'rfid', '2025-09-18 12:13:18', -19.01886130, 47.53938250, '', 0, 1, 1),
(143, 3, 'exit', 'rfid', '2025-09-18 12:13:28', -19.01886130, 47.53938250, '', 0, 1, 1),
(144, 5, 'exit', 'rfid', '2025-09-18 12:13:43', -19.01885290, 47.53940000, '', 0, 1, 1),
(145, 5, 'entry', 'rfid', '2025-09-18 12:47:30', -19.01885980, 47.53938360, '', 0, 1, 1),
(146, 2, 'entry', 'rfid', '2025-09-18 12:54:08', -19.01886080, 47.53938250, '', 0, 1, 1),
(147, 3, 'entry', 'rfid', '2025-09-18 13:54:46', -19.01885840, 47.53938630, '', 0, 1, 1),
(148, 5, 'exit', 'rfid', '2025-09-18 17:14:24', -19.01885680, 47.53939200, '', 0, 1, 1),
(149, 2, 'exit', 'rfid', '2025-09-18 17:15:15', -19.01885540, 47.53938650, '', 0, 1, 1),
(150, 3, 'exit', 'rfid', '2025-09-18 17:29:57', -19.01885790, 47.53939020, '', 0, 1, 1),
(151, 2, 'entry', 'rfid', '2025-09-19 07:59:54', -19.01885920, 47.53940460, '', 0, 1, 1),
(152, 3, 'entry', 'rfid', '2025-09-19 08:04:53', -19.01885840, 47.53939000, '', 0, 1, 1),
(153, 5, 'entry', 'rfid', '2025-09-19 08:53:23', -19.01885640, 47.53941760, '', 0, 1, 1),
(155, 2, 'exit', 'rfid', '2025-09-19 12:09:41', -19.01885430, 47.53941050, '', 0, 1, 1),
(156, 5, 'exit', 'rfid', '2025-09-19 12:19:57', -19.01885400, 47.53940310, '', 0, 1, 1),
(157, 3, 'exit', 'rfid', '2025-09-19 12:23:22', -19.01885590, 47.53939220, '', 0, 1, 1),
(158, 5, 'entry', 'rfid', '2025-09-19 12:44:30', -19.01885030, 47.53941480, '', 0, 1, 1),
(159, 2, 'entry', 'rfid', '2025-09-19 13:06:02', -19.01885030, 47.53941480, '', 0, 1, 1),
(160, 3, 'entry', 'rfid', '2025-09-19 14:03:08', -19.01885950, 47.53939930, '', 0, 1, 1),
(161, 2, 'exit', 'rfid', '2025-09-19 17:21:22', -19.01885700, 47.53939090, '', 0, 1, 1),
(162, 5, 'exit', 'rfid', '2025-09-19 17:21:37', -19.01886200, 47.53939020, '', 0, 1, 1),
(163, 3, 'exit', 'rfid', '2025-09-19 17:36:16', -19.01885590, 47.53940680, '', 0, 1, 1),
(164, 3, 'entry', 'rfid', '2025-09-20 08:30:11', -19.01885210, 47.53940300, '', 0, 1, 1),
(167, 3, 'exit', 'rfid', '2025-09-20 11:22:15', -19.01885330, 47.53940790, '', 0, 1, 1),
(170, 3, 'entry', 'rfid', '2025-09-22 08:01:25', -19.01885720, 47.53940760, '', 0, 1, 1),
(171, 2, 'entry', 'rfid', '2025-09-22 08:02:53', -19.01885660, 47.53940120, '', 0, 1, 1),
(172, 5, 'entry', 'rfid', '2025-09-22 09:09:15', -19.01885470, 47.53941740, '', 0, 1, 1),
(173, 2, 'exit', 'rfid', '2025-09-22 12:01:56', -19.01885770, 47.53940940, '', 0, 1, 1),
(174, 5, 'exit', 'rfid', '2025-09-22 12:03:36', -19.01885960, 47.53939590, '', 0, 1, 1),
(175, 3, 'exit', 'rfid', '2025-09-22 12:04:15', -19.01886320, 47.53938650, '', 0, 1, 1),
(176, 5, 'entry', 'rfid', '2025-09-22 13:06:19', -19.01886060, 47.53938990, '', 0, 1, 1),
(177, 2, 'entry', 'rfid', '2025-09-22 13:06:43', -19.01886280, 47.53938880, '', 0, 1, 1),
(178, 3, 'entry', 'rfid', '2025-09-22 13:26:44', -19.01886280, 47.53938880, '', 0, 1, 1),
(179, 5, 'exit', 'rfid', '2025-09-22 16:53:45', -19.01885750, 47.53939820, '', 0, 1, 1),
(180, 2, 'exit', 'rfid', '2025-09-22 17:06:22', -19.01885810, 47.53940240, '', 0, 1, 1),
(181, 3, 'exit', 'rfid', '2025-09-22 17:40:25', -19.01885920, 47.53940000, '', 0, 1, 1),
(182, 2, 'entry', 'rfid', '2025-09-23 07:59:37', -19.01886740, 47.53940670, '', 0, 1, 1),
(183, 3, 'entry', 'rfid', '2025-09-23 07:59:47', -19.01886740, 47.53940670, '', 0, 1, 1),
(184, 5, 'entry', 'rfid', '2025-09-23 08:52:56', -19.01886290, 47.53940670, '', 0, 1, 1),
(187, 3, 'exit', 'rfid', '2025-09-23 12:00:47', -19.01886130, 47.53938790, '', 0, 1, 1),
(188, 2, 'exit', 'rfid', '2025-09-23 12:02:09', -19.01886110, 47.53939100, '', 0, 1, 1),
(189, 5, 'exit', 'rfid', '2025-09-23 12:02:36', -19.01885670, 47.53941320, '', 0, 1, 1),
(190, 2, 'entry', 'rfid', '2025-09-23 13:00:02', -19.01886600, 47.53938710, '', 0, 1, 1),
(191, 5, 'entry', 'rfid', '2025-09-23 13:03:19', -19.01886380, 47.53938630, '', 0, 1, 1),
(192, 3, 'entry', 'rfid', '2025-09-23 13:30:24', -19.01886490, 47.53938520, '', 0, 1, 1),
(193, 5, 'exit', 'rfid', '2025-09-23 17:00:13', -19.01886210, 47.53938930, '', 0, 1, 1),
(194, 2, 'exit', 'rfid', '2025-09-23 17:13:33', -19.01886300, 47.53938740, '', 0, 1, 1),
(199, 3, 'exit', 'rfid', '2025-09-23 17:34:26', -19.01886300, 47.53938740, '', 0, 1, 1),
(200, 3, 'entry', 'rfid', '2025-09-24 08:00:19', -19.01886100, 47.53939380, '', 0, 1, 1),
(201, 2, 'entry', 'rfid', '2025-09-24 08:02:28', -19.01886050, 47.53938960, '', 0, 1, 1),
(202, 5, 'entry', 'rfid', '2025-09-24 08:51:33', -19.01886050, 47.53938960, '', 0, 1, 1),
(205, 2, 'entry', 'rfid', '2025-09-24 13:14:59', -19.01884000, 47.53939950, '', 0, 1, 1),
(206, 5, 'entry', 'rfid', '2025-09-24 13:15:59', -19.01885510, 47.53939250, '', 0, 1, 1),
(207, 3, 'entry', 'rfid', '2025-09-24 13:32:26', -19.01885050, 47.53940610, '', 0, 1, 1),
(208, 2, 'exit', 'rfid', '2025-09-24 17:11:48', -19.01882330, 47.53938770, '', 0, 1, 1),
(209, 5, 'exit', 'rfid', '2025-09-24 17:12:26', -19.01881460, 47.53938020, '', 0, 1, 1),
(210, 3, 'exit', 'rfid', '2025-09-24 17:45:50', -19.01882330, 47.53938020, '', 0, 1, 1),
(211, 3, 'entry', 'rfid', '2025-09-25 08:03:15', -19.01882350, 47.53935700, '', 0, 1, 1),
(212, 2, 'entry', 'rfid', '2025-09-25 08:17:43', -19.01885290, 47.53941170, '', 0, 1, 1),
(213, 3, 'exit', 'rfid', '2025-09-25 12:01:55', -19.01882800, 47.53938320, '', 0, 1, 1),
(214, 2, 'exit', 'rfid', '2025-09-25 12:02:32', -19.01893940, 47.53929740, '', 0, 1, 1),
(215, 5, 'entry', 'rfid', '2025-09-25 13:06:20', -19.01891220, 47.53928630, '', 0, 1, 1),
(216, 2, 'entry', 'rfid', '2025-09-25 13:07:11', -19.01888930, 47.53933660, '', 0, 1, 1),
(221, 2, 'exit', 'rfid', '2025-09-29 17:10:11', -19.01884460, 47.53945790, NULL, 0, 1, 1),
(222, 3, 'exit', 'rfid', '2025-09-29 17:53:53', -19.01882940, 47.53953450, NULL, 0, 1, 1),
(223, 3, 'entry', 'rfid', '2025-09-30 08:01:30', -19.01882900, 47.53953230, NULL, 0, 1, 1),
(224, 2, 'entry', 'rfid', '2025-09-30 08:07:30', -19.01883070, 47.53953260, NULL, 0, 1, 1),
(230, 3, 'exit', 'rfid', '2025-09-24 12:05:00', -19.01883070, 47.53953260, NULL, 0, 1, 1),
(231, 2, 'exit', 'rfid', '2025-09-24 12:12:00', -19.01883070, 47.53953260, NULL, 0, 1, 1),
(232, 5, 'exit', 'rfid', '2025-09-24 12:13:00', -19.01883070, 47.53953260, NULL, 0, 1, 1),
(233, 5, 'entry', 'rfid', '2025-09-25 09:00:00', -19.01883070, 47.53953260, NULL, 0, 1, 1),
(234, 3, 'entry', 'rfid', '2025-09-25 13:21:00', -19.01883070, 47.53953260, NULL, 0, 1, 1),
(236, 5, 'exit', 'rfid', '2025-09-25 16:13:00', -19.01883070, 47.53953260, NULL, 0, 1, 1),
(237, 3, 'exit', 'rfid', '2025-09-25 17:30:00', -19.01883070, 47.53953260, NULL, 0, 1, 1),
(238, 2, 'exit', 'rfid', '2025-09-25 17:30:00', -19.01883070, 47.53953260, NULL, 0, 1, 1),
(239, 3, 'entry', 'rfid', '2025-09-26 08:01:00', -19.01883070, 47.53953260, NULL, 0, 1, 1),
(240, 2, 'entry', 'rfid', '2025-09-26 08:07:00', -19.01883070, 47.53953260, NULL, 0, 1, 1),
(241, 5, 'entry', 'rfid', '2025-09-26 09:25:00', -19.01883070, 47.53953260, NULL, 0, 1, 1),
(242, 2, 'exit', 'rfid', '2025-09-26 12:02:00', -19.01883070, 47.53953260, NULL, 0, 1, 1),
(243, 3, 'exit', 'rfid', '2025-09-26 12:02:00', -19.01883070, 47.53953260, NULL, 0, 1, 1),
(244, 5, 'exit', 'rfid', '2025-09-26 12:06:00', -19.01883070, 47.53953260, NULL, 0, 1, 1),
(245, 2, 'entry', 'rfid', '2025-09-26 13:11:00', -19.01883070, 47.53953260, NULL, 0, 1, 1),
(246, 3, 'entry', 'rfid', '2025-09-26 13:31:00', -19.01883070, 47.53953260, NULL, 0, 1, 1),
(247, 5, 'exit', 'rfid', '2025-09-26 15:30:00', -19.01883070, 47.53953260, NULL, 0, 1, 1),
(248, 2, 'exit', 'rfid', '2025-09-26 17:00:00', -19.01883070, 47.53953260, NULL, 0, 1, 1),
(249, 3, 'exit', 'rfid', '2025-09-26 17:31:00', -19.01883070, 47.53953260, NULL, 0, 1, 1),
(250, 3, 'entry', 'rfid', '2025-09-29 08:02:00', -19.01883070, 47.53953260, NULL, 0, 1, 1),
(251, 2, 'entry', 'rfid', '2025-09-29 08:10:00', -19.01883070, 47.53953260, NULL, 0, 1, 1),
(252, 5, 'entry', 'rfid', '2025-09-29 09:02:00', -19.01883070, 47.53953260, NULL, 0, 1, 1),
(253, 3, 'exit', 'rfid', '2025-09-29 12:02:00', -19.01883070, 47.53953260, NULL, 0, 1, 1),
(254, 2, 'exit', 'rfid', '2025-09-29 12:02:00', -19.01883070, 47.53953260, NULL, 0, 1, 1),
(255, 5, 'exit', 'rfid', '2025-09-29 12:04:00', -19.01883070, 47.53953260, NULL, 0, 1, 1),
(256, 5, 'entry', 'rfid', '2025-09-29 12:58:00', -19.01883070, 47.53953260, NULL, 0, 1, 1),
(257, 2, 'entry', 'rfid', '2025-09-29 13:25:00', -19.01883070, 47.53953260, NULL, 0, 1, 1),
(258, 3, 'entry', 'rfid', '2025-09-29 13:50:00', -19.01883070, 47.53953260, NULL, 0, 1, 1),
(259, 5, 'entry', 'rfid', '2025-09-30 09:04:00', -19.01883070, 47.53953260, NULL, 0, 1, 1),
(260, 3, 'exit', 'rfid', '2025-09-30 12:02:58', -19.01882750, 47.53953670, NULL, 0, 1, 1),
(261, 2, 'exit', 'rfid', '2025-09-30 12:03:17', -19.01882960, 47.53953510, NULL, 0, 1, 1),
(262, 5, 'exit', 'rfid', '2025-09-30 12:03:36', -19.01882760, 47.53953500, NULL, 0, 1, 1),
(263, 2, 'entry', 'rfid', '2025-09-30 13:02:56', -19.01882990, 47.53953330, NULL, 0, 1, 1),
(264, 5, 'entry', 'rfid', '2025-09-30 13:12:04', -19.01882990, 47.53953330, NULL, 0, 1, 1),
(265, 3, 'entry', 'rfid', '2025-09-30 13:32:04', -19.01884880, 47.53930570, NULL, 0, 1, 1),
(266, 5, 'exit', 'rfid', '2025-09-30 15:24:00', -19.01882720, 47.53953450, NULL, 0, 1, 1),
(267, 2, 'exit', 'rfid', '2025-09-30 17:19:38', -19.01882830, 47.53953540, NULL, 0, 1, 1),
(268, 3, 'exit', 'rfid', '2025-09-30 18:02:33', -19.01882830, 47.53953540, NULL, 0, 1, 1),
(271, 3, 'entry', 'rfid', '2025-10-01 08:00:00', -19.01883070, 47.53953260, NULL, 0, 1, 1),
(272, 2, 'entry', 'rfid', '2025-10-01 08:00:00', -19.01883070, 47.53953260, NULL, 0, 1, 1),
(273, 5, 'exit', 'rfid', '2025-09-25 12:00:00', -19.01883070, 47.53953260, NULL, 0, 1, 1),
(278, 5, 'entry', 'rfid', '2025-09-26 13:00:00', -19.01883070, 47.53953260, NULL, 0, 1, 1),
(279, 5, 'entry', 'rfid', '2025-09-05 09:00:00', -19.01883070, 47.53953260, NULL, 0, 1, 1),
(280, 5, 'exit', 'rfid', '2025-09-29 16:00:00', -19.01883070, 47.53953260, NULL, 0, 1, 1),
(281, 3, 'exit', 'rfid', '2025-10-01 12:04:49', -19.01885610, 47.53940400, NULL, 0, 1, 1),
(282, 2, 'exit', 'rfid', '2025-10-01 12:09:00', -19.01885290, 47.53940950, NULL, 0, 1, 1),
(283, 3, 'entry', 'rfid', '2025-10-01 13:38:20', -19.01883270, 47.53952000, NULL, 0, 1, 1),
(284, 2, 'entry', 'rfid', '2025-10-01 13:38:41', -19.01882900, 47.53953530, NULL, 0, 1, 1),
(285, 2, 'exit', 'rfid', '2025-10-01 16:53:38', -19.01886440, 47.53928720, NULL, 0, 1, 1),
(286, 3, 'exit', 'rfid', '2025-10-01 17:36:42', -19.01885600, 47.53937930, NULL, 0, 1, 1),
(287, 2, 'entry', 'rfid', '2025-10-02 07:57:29', -19.01882790, 47.53954220, NULL, 0, 1, 1),
(288, 3, 'entry', 'rfid', '2025-10-02 08:02:45', -19.01885120, 47.53940120, NULL, 0, 1, 1),
(289, 5, 'entry', 'rfid', '2025-10-02 09:51:58', -19.01884070, 47.53950750, NULL, 0, 1, 1),
(291, 3, 'exit', 'rfid', '2025-10-02 12:02:09', -19.01883920, 47.53949170, NULL, 0, 1, 1),
(292, 5, 'exit', 'rfid', '2025-10-02 12:06:59', -19.01884850, 47.53947220, NULL, 0, 1, 1),
(293, 2, 'exit', 'rfid', '2025-10-02 12:14:45', -19.01884250, 47.53948940, NULL, 0, 1, 1),
(294, 5, 'entry', 'rfid', '2025-10-02 12:46:46', -19.01885040, 47.53944450, NULL, 0, 1, 1),
(295, 2, 'entry', 'rfid', '2025-10-02 13:15:13', -19.01882850, 47.53954180, NULL, 0, 1, 1),
(296, 3, 'entry', 'rfid', '2025-10-02 13:28:29', -19.01886400, 47.53938400, NULL, 0, 1, 1),
(297, 5, 'exit', 'rfid', '2025-10-02 15:44:44', -19.01882860, 47.53954490, NULL, 0, 1, 1),
(298, 2, 'exit', 'rfid', '2025-10-02 17:17:12', -19.01884190, 47.53949350, NULL, 0, 1, 1),
(299, 3, 'exit', 'rfid', '2025-10-02 17:32:10', -19.01884370, 47.53948590, NULL, 0, 1, 1),
(300, 2, 'entry', 'rfid', '2025-10-03 07:58:02', -19.01882900, 47.53953970, NULL, 0, 1, 1),
(301, 3, 'entry', 'rfid', '2025-10-03 08:03:19', -19.01883990, 47.53948970, NULL, 0, 1, 1),
(302, 3, 'exit', 'pin', '2025-10-03 12:04:10', -19.01885310, 47.53940130, NULL, 0, 1, 1),
(303, 2, 'exit', 'rfid', '2025-10-03 12:06:40', -19.01883940, 47.53950420, NULL, 0, 1, 1),
(304, 2, 'entry', 'rfid', '2025-10-03 12:49:28', -19.01884280, 47.53949460, NULL, 0, 1, 1),
(305, 3, 'entry', 'pin', '2025-10-03 12:59:49', -19.01883420, 47.53952030, NULL, 0, 1, 1),
(306, 16, 'entry', 'pin', '2025-10-03 16:20:01', -20.92565730, 55.65744770, NULL, 0, 2, 7),
(308, 16, 'exit', 'pin', '2025-10-03 16:21:04', -20.92565720, 55.65744740, NULL, 0, 2, 7),
(309, 16, 'entry', 'face', '2025-10-03 16:23:12', -20.92586310, 55.65735060, NULL, 0, 2, 7),
(310, 16, 'exit', 'face', '2025-10-03 16:25:17', -20.92584600, 55.65735300, NULL, 0, 2, 7),
(311, 2, 'exit', 'rfid', '2025-10-03 17:03:30', -19.01884080, 47.53945760, NULL, 0, 1, 1),
(312, 3, 'exit', 'rfid', '2025-10-03 17:07:46', -19.01883920, 47.53943400, NULL, 0, 1, 1),
(313, 16, 'entry', 'face', '2025-10-04 16:30:14', -20.92582780, 55.65736060, NULL, 0, 2, 7),
(314, 16, 'exit', 'pin', '2025-10-04 16:30:47', -20.92584550, 55.65735410, NULL, 0, 2, 7),
(315, 3, 'entry', 'rfid', '2025-10-06 08:05:00', -19.01883070, 47.53953260, NULL, 0, 1, 1),
(316, 3, 'exit', 'rfid', '2025-10-06 12:09:24', -19.01885300, 47.53941280, NULL, 0, 1, 1),
(317, 3, 'entry', 'rfid', '2025-10-06 13:32:55', -19.01883070, 47.53953260, NULL, 0, 1, 1),
(318, 3, 'exit', 'rfid', '2025-10-06 17:35:34', -19.01884200, 47.53949570, NULL, 0, 1, 1),
(319, 3, 'entry', 'rfid', '2025-10-07 08:02:08', -19.01884350, 47.53949000, NULL, 0, 1, 1),
(320, 3, 'exit', 'pin', '2025-10-07 12:00:50', -19.01885110, 47.53943670, NULL, 0, 1, 1),
(321, 3, 'entry', 'rfid', '2025-10-07 13:25:35', -19.01884340, 47.53949560, NULL, 0, 1, 1),
(322, 3, 'exit', 'rfid', '2025-10-07 17:35:44', -19.01883310, 47.53952750, NULL, 0, 1, 1),
(323, 3, 'entry', 'rfid', '2025-10-09 08:02:37', -19.01883250, 47.53952620, NULL, 0, 1, 1),
(324, 3, 'entry', 'rfid', '2025-10-09 13:30:55', -19.01883070, 47.53953260, NULL, 0, 1, 1),
(325, 3, 'exit', 'rfid', '2025-10-09 12:01:00', -19.01883070, 47.53953260, NULL, 0, 1, 1),
(326, 3, 'exit', 'rfid', '2025-10-09 17:31:06', -19.01882780, 47.53952930, NULL, 0, 1, 1),
(327, 3, 'entry', 'rfid', '2025-10-10 07:57:56', -19.01882970, 47.53953870, NULL, 0, 1, 1),
(328, 3, 'exit', 'rfid', '2025-10-10 12:01:14', -19.01882940, 47.53954000, NULL, 0, 1, 1),
(329, 3, 'entry', 'rfid', '2025-10-10 12:42:50', -19.01882940, 47.53954000, NULL, 0, 1, 1),
(330, 3, 'exit', 'rfid', '2025-10-10 16:03:48', -19.01882900, 47.53953880, NULL, 0, 1, 1),
(331, 3, 'entry', 'rfid', '2025-10-13 08:02:49', -19.01882780, 47.53954310, NULL, 0, 1, 1),
(333, 2, 'entry', 'rfid', '2025-10-13 08:09:21', -19.01882280, 47.53955350, NULL, 0, 1, 1),
(334, 2, 'exit', 'rfid', '2025-10-13 12:07:39', -19.01882330, 47.53955370, NULL, 0, 1, 1),
(335, 3, 'exit', 'rfid', '2025-10-13 12:11:32', -19.01882970, 47.53953680, NULL, 0, 1, 1),
(336, 2, 'entry', 'rfid', '2025-10-13 13:58:33', -19.01882290, 47.53955360, NULL, 0, 1, 1),
(337, 3, 'entry', 'rfid', '2025-10-13 13:30:07', -19.01883070, 47.53953260, NULL, 0, 1, 1),
(338, 2, 'exit', 'rfid', '2025-10-13 17:36:54', -19.01882330, 47.53955370, NULL, 0, 1, 1),
(339, 3, 'exit', 'rfid', '2025-10-13 17:58:23', -19.01882330, 47.53955370, NULL, 0, 1, 1),
(340, 2, 'entry', 'rfid', '2025-10-14 08:01:41', -19.01882980, 47.53953830, NULL, 0, 1, 1),
(341, 3, 'entry', 'rfid', '2025-10-14 08:06:12', -19.01882990, 47.53953810, NULL, 0, 1, 1),
(342, 3, 'exit', 'rfid', '2025-10-14 12:09:39', -19.01882240, 47.53955330, NULL, 0, 1, 1),
(343, 2, 'exit', 'rfid', '2025-10-14 12:09:51', -19.01882290, 47.53955360, NULL, 0, 1, 1),
(344, 3, 'entry', 'rfid', '2025-10-14 13:32:43', -19.01882290, 47.53955360, NULL, 0, 1, 1),
(345, 2, 'entry', 'rfid', '2025-10-14 13:34:12', -19.01882870, 47.53953540, NULL, 0, 1, 1),
(346, 2, 'exit', 'rfid', '2025-10-14 17:47:18', -19.01882320, 47.53955300, NULL, 0, 1, 1),
(347, 3, 'exit', 'rfid', '2025-10-14 17:47:33', -19.01882830, 47.53954070, NULL, 0, 1, 1),
(348, 3, 'entry', 'rfid', '2025-10-15 08:07:34', -19.01883020, 47.53954040, NULL, 0, 1, 1),
(349, 2, 'entry', 'rfid', '2025-10-15 08:14:47', -19.01882830, 47.53954210, NULL, 0, 1, 1),
(350, 2, 'exit', 'rfid', '2025-10-15 12:05:13', -19.01882880, 47.53954120, NULL, 0, 1, 1),
(351, 3, 'exit', 'rfid', '2025-10-15 12:08:32', -19.01882960, 47.53954000, NULL, 0, 1, 1),
(352, 2, 'entry', 'rfid', '2025-10-15 13:33:53', -19.01882310, 47.53955370, NULL, 0, 1, 1),
(353, 3, 'entry', 'rfid', '2025-10-15 13:41:28', -19.01882830, 47.53953990, NULL, 0, 1, 1),
(354, 3, 'exit', 'rfid', '2025-10-15 17:45:47', -19.01882980, 47.53953800, NULL, 0, 1, 1),
(355, 2, 'exit', 'rfid', '2025-10-15 17:52:02', -19.01882830, 47.53953990, NULL, 0, 1, 1),
(356, 3, 'entry', 'rfid', '2025-10-16 08:03:42', -19.01882890, 47.53954100, NULL, 0, 1, 1),
(357, 2, 'entry', 'rfid', '2025-10-16 08:05:58', -19.01882510, 47.53955450, NULL, 0, 1, 1),
(358, 2, 'exit', 'rfid', '2025-10-16 12:04:59', -19.01882650, 47.53955330, NULL, 0, 1, 1),
(359, 3, 'exit', 'rfid', '2025-10-16 12:05:17', -19.01882510, 47.53953860, NULL, 0, 1, 1),
(360, 3, 'entry', 'rfid', '2025-10-16 13:36:31', -19.01882630, 47.53954010, NULL, 0, 1, 1),
(361, 2, 'entry', 'rfid', '2025-10-16 13:36:45', -19.01882630, 47.53954220, NULL, 0, 1, 1),
(362, 2, 'exit', 'rfid', '2025-10-16 17:22:03', -19.01882830, 47.53954140, NULL, 0, 1, 1),
(363, 3, 'exit', 'rfid', '2025-10-16 17:44:09', -19.01882830, 47.53954140, NULL, 0, 1, 1),
(364, 2, 'entry', 'rfid', '2025-10-17 08:05:46', -19.01882790, 47.53954230, NULL, 0, 1, 1),
(365, 3, 'entry', 'rfid', '2025-10-17 08:06:14', -19.01882540, 47.53955290, NULL, 0, 1, 1),
(366, 3, 'exit', 'rfid', '2025-10-17 12:05:14', -19.01882480, 47.53955080, NULL, 0, 1, 1),
(367, 2, 'exit', 'rfid', '2025-10-17 12:05:37', -19.01882300, 47.53955370, NULL, 0, 1, 1),
(368, 2, 'entry', 'rfid', '2025-10-17 13:00:13', -19.01882210, 47.53955590, NULL, 0, 1, 1),
(369, 3, 'entry', 'rfid', '2025-10-17 13:01:12', -19.01882700, 47.53953820, NULL, 0, 1, 1),
(370, 2, 'exit', 'rfid', '2025-10-17 16:31:44', -19.01882500, 47.53955190, NULL, 0, 1, 1),
(371, 3, 'exit', 'rfid', '2025-10-17 16:32:29', -19.01882550, 47.53954850, NULL, 0, 1, 1),
(373, 3, 'entry', 'rfid', '2025-10-20 08:05:01', -19.01882370, 47.53955410, NULL, 0, 1, 1),
(374, 2, 'entry', 'rfid', '2025-10-20 08:07:54', -19.01882480, 47.53955120, NULL, 0, 1, 1),
(375, 3, 'exit', 'rfid', '2025-10-20 12:10:18', -19.01882550, 47.53955210, NULL, 0, 1, 1),
(376, 2, 'exit', 'rfid', '2025-10-20 12:15:48', -19.01882580, 47.53955230, NULL, 0, 1, 1),
(377, 2, 'entry', 'rfid', '2025-10-20 13:31:43', -19.01882490, 47.53954880, NULL, 0, 1, 1),
(378, 3, 'entry', 'rfid', '2025-10-20 13:35:09', -19.01882370, 47.53955410, NULL, 0, 1, 1),
(379, 2, 'exit', 'rfid', '2025-10-20 17:35:17', -19.01882500, 47.53955190, NULL, 0, 1, 1),
(380, 3, 'exit', 'rfid', '2025-10-20 17:42:46', -19.01882490, 47.53955160, NULL, 0, 1, 1),
(381, 2, 'entry', 'rfid', '2025-10-21 08:03:11', -19.01882390, 47.53955320, NULL, 0, 1, 1),
(382, 3, 'entry', 'rfid', '2025-10-21 08:06:10', -19.01882350, 47.53955470, NULL, 0, 1, 1),
(383, 2, 'exit', 'rfid', '2025-10-21 12:14:24', -19.01882560, 47.53955000, NULL, 0, 1, 1),
(384, 3, 'exit', 'rfid', '2025-10-21 12:14:44', -19.01882460, 47.53955080, NULL, 0, 1, 1),
(385, 3, 'entry', 'rfid', '2025-10-21 13:37:23', -19.01882370, 47.53955470, NULL, 0, 1, 1),
(386, 2, 'entry', 'rfid', '2025-10-21 13:42:09', -19.01882570, 47.53954730, NULL, 0, 1, 1),
(387, 2, 'exit', 'rfid', '2025-10-21 17:33:24', -19.01882240, 47.53955540, NULL, 0, 1, 1),
(388, 3, 'exit', 'rfid', '2025-10-21 17:38:30', -19.01882370, 47.53955400, NULL, 0, 1, 1),
(389, 2, 'entry', 'rfid', '2025-10-22 08:05:21', -19.01882410, 47.53955470, NULL, 0, 1, 1),
(390, 3, 'entry', 'rfid', '2025-10-22 08:06:48', -19.01882350, 47.53955380, NULL, 0, 1, 1),
(391, 16, 'entry', 'face', '2025-10-22 10:39:21', -20.92585080, 55.65735240, NULL, 0, 2, 7),
(392, 16, 'exit', 'face', '2025-10-22 10:40:47', -20.92568760, 55.65743070, NULL, 0, 2, 7),
(393, 16, 'entry', 'face', '2025-10-22 12:01:33', -20.92583170, 55.65738050, NULL, 0, 2, 7),
(394, 16, 'exit', 'face', '2025-10-22 12:10:09', -20.92584000, 55.65736720, NULL, 0, 2, 7),
(395, 16, 'entry', 'face', '2025-10-22 12:16:44', -20.92578350, 55.65738620, NULL, 0, 2, 7),
(396, 16, 'entry', 'face', '2025-10-22 12:26:01', -20.92568730, 55.65743000, NULL, 0, 2, 7),
(397, 3, 'exit', 'rfid', '2025-10-22 12:05:41', -19.01882520, 47.53955040, NULL, 0, 1, 1),
(398, 2, 'exit', 'rfid', '2025-10-22 12:06:12', -19.01882330, 47.53955540, NULL, 0, 1, 1),
(399, 16, 'exit', 'face', '2025-10-22 13:26:11', -20.92584690, 55.65736340, NULL, 0, 2, 7),
(400, 2, 'entry', 'rfid', '2025-10-22 13:20:05', -19.01882340, 47.53955250, NULL, 0, 1, 1),
(401, 3, 'entry', 'rfid', '2025-10-22 13:32:13', -19.01882360, 47.53955540, NULL, 0, 1, 1),
(402, 3, 'exit', 'rfid', '2025-10-22 17:52:13', -19.01882580, 47.53955020, NULL, 0, 1, 1),
(403, 2, 'exit', 'rfid', '2025-10-22 17:53:22', -19.01882410, 47.53955440, NULL, 0, 1, 1),
(404, 2, 'entry', 'rfid', '2025-10-23 08:00:57', -19.01882660, 47.53955270, NULL, 0, 1, 1),
(405, 3, 'entry', 'rfid', '2025-10-23 08:06:40', -19.01882490, 47.53955340, NULL, 0, 1, 1),
(406, 3, 'exit', 'rfid', '2025-10-23 12:05:09', -19.01881680, 47.53954490, NULL, 0, 1, 1),
(407, 3, 'entry', 'rfid', '2025-10-23 13:35:27', -19.01881930, 47.53953230, NULL, 0, 1, 1),
(408, 2, 'exit', 'rfid', '2025-10-23 12:05:00', -19.01883070, 47.53953260, NULL, 0, 1, 1),
(409, 2, 'entry', 'rfid', '2025-10-23 13:33:31', -19.01889600, 47.53933370, '', 0, 1, 1),
(410, 3, 'exit', 'rfid', '2025-10-23 17:40:25', -19.01881730, 47.53954500, NULL, 0, 1, 1),
(411, 2, 'exit', 'rfid', '2025-10-23 17:54:00', -19.01881540, 47.53954390, NULL, 0, 1, 1),
(412, 3, 'entry', 'rfid', '2025-10-24 08:03:38', -19.01882110, 47.53953370, NULL, 0, 1, 1),
(413, 2, 'entry', 'rfid', '2025-10-24 08:10:07', -19.01881540, 47.53954390, NULL, 0, 1, 1),
(417, 3, 'exit', 'face', '2025-10-24 12:06:07', -19.01881830, 47.53954470, NULL, 0, 1, 1),
(418, 2, 'exit', 'rfid', '2025-10-24 12:09:24', -19.01881880, 47.53953190, NULL, 0, 1, 1),
(419, 3, 'entry', 'rfid', '2025-10-24 12:57:47', -19.01881640, 47.53954460, NULL, 0, 1, 1),
(420, 2, 'entry', 'rfid', '2025-10-24 13:02:15', -19.01881640, 47.53954460, NULL, 0, 1, 1),
(421, 16, 'entry', 'rfid', '2025-10-24 20:00:13', -20.92582860, 55.65731950, NULL, 0, 2, 7),
(422, 16, 'exit', 'face', '2025-10-24 20:01:10', -20.92582880, 55.65733270, NULL, 0, 2, 7),
(423, 16, 'entry', 'face', '2025-10-24 21:12:47', -20.92579770, 55.65734900, NULL, 0, 2, 7),
(424, 16, 'exit', 'face', '2025-10-24 23:13:35', -20.92582330, 55.65733190, NULL, 0, 2, 7),
(425, 2, 'exit', 'rfid', '2025-10-24 16:58:16', -19.01881730, 47.53954500, NULL, 0, 1, 1),
(426, 3, 'exit', 'rfid', '2025-10-24 17:00:31', -19.01881640, 47.53954460, NULL, 0, 1, 1),
(427, 16, 'entry', 'face', '2025-10-25 09:19:53', -20.92583120, 55.65733830, NULL, 0, 2, 7),
(428, 2, 'entry', 'rfid', '2025-10-27 07:57:21', -19.01881720, 47.53954500, NULL, 0, 1, 1),
(429, 3, 'entry', 'rfid', '2025-10-27 08:04:55', -19.01881720, 47.53954500, NULL, 0, 1, 1),
(430, 3, 'exit', 'rfid', '2025-10-27 12:04:19', -19.01881590, 47.53954410, NULL, 0, 1, 1),
(431, 3, 'entry', 'rfid', '2025-10-27 13:31:53', -19.01881610, 47.53954420, NULL, 0, 1, 1),
(432, 2, 'entry', 'rfid', '2025-10-27 12:30:21', -19.01881720, 47.53954500, NULL, 0, 1, 1),
(433, 2, 'exit', 'rfid', '2025-10-27 12:04:21', -19.01881720, 47.53954500, NULL, 0, 1, 1),
(434, 2, 'exit', 'rfid', '2025-10-27 16:56:53', -19.01881680, 47.53954490, NULL, 0, 1, 1),
(435, 3, 'exit', 'rfid', '2025-10-27 17:28:05', -19.01881910, 47.53952970, NULL, 0, 1, 1),
(436, 3, 'entry', 'rfid', '2025-10-28 08:04:17', -19.01882070, 47.53953020, NULL, 0, 1, 1),
(437, 2, 'entry', 'rfid', '2025-10-28 08:07:21', -19.01882050, 47.53952980, NULL, 0, 1, 1),
(438, 3, 'exit', 'rfid', '2025-10-28 12:06:30', -19.01881780, 47.53954490, NULL, 0, 1, 1),
(439, 2, 'exit', 'rfid', '2025-10-28 12:08:00', -19.01881780, 47.53954490, NULL, 0, 1, 1),
(440, 2, 'entry', 'rfid', '2025-10-28 13:31:45', -19.01881730, 47.53954500, NULL, 0, 1, 1),
(441, 3, 'entry', 'rfid', '2025-10-28 13:34:21', -19.01881610, 47.53954420, NULL, 0, 1, 1),
(443, 2, 'exit', 'rfid', '2025-10-28 17:29:08', -19.01881640, 47.53954460, NULL, 0, 1, 1),
(444, 3, 'exit', 'rfid', '2025-10-28 17:53:56', -19.01881920, 47.53953030, NULL, 0, 1, 1),
(445, 2, 'entry', 'rfid', '2025-10-29 08:03:58', -19.01881870, 47.53953050, NULL, 0, 1, 1),
(446, 3, 'entry', 'rfid', '2025-10-29 08:06:43', -19.01881530, 47.53954390, NULL, 0, 1, 1),
(447, 16, 'entry', 'rfid', '2025-10-29 11:57:52', -20.92584050, 55.65734010, NULL, 0, 2, 7),
(448, 16, 'exit', 'face', '2025-10-29 12:07:17', -20.92581250, 55.65733490, NULL, 0, 2, 7),
(449, 3, 'exit', 'face', '2025-10-29 12:08:14', -19.01881640, 47.53954460, NULL, 0, 1, 1),
(450, 2, 'exit', 'face', '2025-10-29 12:06:28', -19.01881680, 47.53954490, NULL, 0, 1, 1),
(451, 3, 'entry', 'face', '2025-10-29 13:36:07', -19.01881680, 47.53954490, NULL, 0, 1, 1),
(452, 2, 'entry', 'rfid', '2025-10-29 13:41:23', -19.01881730, 47.53954500, NULL, 0, 1, 1),
(453, 16, 'entry', 'pin', '2025-10-29 14:55:26', -20.92572080, 55.65738460, NULL, 0, 2, 7),
(454, 16, 'exit', 'rfid', '2025-10-29 15:39:19', -20.92570910, 55.65739080, NULL, 0, 2, 7),
(455, 16, 'entry', 'pin', '2025-10-29 15:40:31', -20.92580960, 55.65733890, NULL, 0, 2, 7),
(456, 16, 'entry', 'face', '2025-10-29 15:42:42', -20.92580290, 55.65734590, NULL, 0, 2, 7),
(457, 2, 'exit', 'rfid', '2025-10-29 17:20:34', -19.01881570, 47.53954410, NULL, 0, 1, 1),
(458, 3, 'exit', 'rfid', '2025-10-29 17:39:23', -19.01881640, 47.53954460, NULL, 0, 1, 1),
(459, 3, 'entry', 'rfid', '2025-10-30 08:02:49', -19.01881840, 47.53953140, NULL, 0, 1, 1),
(460, 2, 'entry', 'rfid', '2025-10-30 08:04:39', -19.01881640, 47.53954460, NULL, 0, 1, 1),
(461, 3, 'entry', 'pin', '2025-10-30 13:35:50', -19.01882010, 47.53952980, NULL, 0, 1, 1),
(462, 2, 'entry', 'rfid', '2025-10-30 13:45:31', -19.01881730, 47.53954500, NULL, 0, 1, 1),
(463, 3, 'exit', 'rfid', '2025-10-30 12:10:07', -19.01883070, 47.53953260, NULL, 0, 1, 1),
(464, 2, 'exit', 'rfid', '2025-10-30 12:14:07', -19.01883070, 47.53953260, NULL, 0, 1, 1),
(465, 3, 'exit', 'rfid', '2025-10-30 17:36:52', -19.01881640, 47.53954460, NULL, 0, 1, 1),
(466, 2, 'exit', 'rfid', '2025-10-30 17:41:52', -19.01881640, 47.53954460, NULL, 0, 1, 1),
(467, 3, 'entry', 'rfid', '2025-10-31 08:07:10', -19.01881830, 47.53954470, NULL, 0, 1, 1),
(468, 2, 'entry', 'rfid', '2025-10-31 08:11:15', -19.01881680, 47.53954490, NULL, 0, 1, 1),
(469, 16, 'exit', 'face', '2025-10-31 10:56:02', -20.92584870, 55.65732860, NULL, 0, 2, 7),
(470, 16, 'exit', 'face', '2025-10-31 10:56:43', -20.92584630, 55.65733160, NULL, 0, 2, 7),
(471, 3, 'exit', 'rfid', '2025-10-31 12:10:37', -19.01882060, 47.53953070, NULL, 0, 1, 1),
(472, 2, 'entry', 'rfid', '2025-10-31 12:58:50', -19.01881830, 47.53953300, NULL, 0, 1, 1),
(473, 3, 'entry', 'rfid', '2025-10-31 13:04:32', -19.01882030, 47.53953330, NULL, 0, 1, 1),
(474, 2, 'exit', 'rfid', '2025-10-31 12:14:15', -19.01881610, 47.53954420, NULL, 0, 1, 1),
(475, 2, 'exit', 'rfid', '2025-10-31 17:10:29', -19.01881640, 47.53954460, NULL, 0, 1, 1),
(476, 3, 'exit', 'pin', '2025-10-31 17:13:21', -19.01882110, 47.53953060, NULL, 0, 1, 1),
(477, 16, 'entry', 'rfid', '2025-11-01 15:58:48', -20.92582890, 55.65732880, NULL, 0, 2, 7),
(478, 16, 'exit', 'rfid', '2025-11-01 16:02:06', -20.92582650, 55.65732940, NULL, 0, 2, 7),
(479, 16, 'entry', 'rfid', '2025-11-01 16:07:13', -20.92582160, 55.65731550, NULL, 0, 2, 7),
(480, 16, 'exit', 'rfid', '2025-11-01 16:08:36', -20.92584720, 55.65732880, NULL, 0, 2, 7),
(481, 16, 'entry', 'face', '2025-11-01 16:09:16', -20.92584800, 55.65732920, NULL, 0, 2, 7),
(482, 2, 'entry', 'rfid', '2025-11-03 07:15:51', -19.01897330, 47.53932580, NULL, 0, 1, 1),
(483, 3, 'entry', 'rfid', '2025-11-03 08:06:32', -19.01897350, 47.53932510, NULL, 0, 1, 1),
(485, 3, 'exit', 'face', '2025-11-03 12:09:25', -19.01897350, 47.53932510, NULL, 0, 1, 1),
(486, 3, 'entry', 'face', '2025-11-03 13:35:51', -19.01897310, 47.53932050, NULL, 0, 1, 1),
(489, 2, 'exit', 'rfid', '2025-11-03 12:11:59', -19.01897300, 47.53931900, NULL, 0, 1, 1),
(490, 2, 'entry', 'rfid', '2025-11-03 13:03:16', -19.01897300, 47.53931880, NULL, 0, 1, 1),
(491, 2, 'exit', 'rfid', '2025-11-03 16:53:27', -19.01897360, 47.53932400, NULL, 0, 1, 1),
(492, 3, 'exit', 'rfid', '2025-11-03 17:31:58', -19.01897260, 47.53932690, NULL, 0, 1, 1),
(493, 2, 'entry', 'rfid', '2025-11-04 08:05:02', -19.01879620, 47.53943080, NULL, 0, 1, 1),
(494, 3, 'entry', 'rfid', '2025-11-04 08:12:53', -19.01897350, 47.53932510, NULL, 0, 1, 1),
(495, 3, 'exit', 'rfid', '2025-11-04 12:08:35', -19.01897340, 47.53932160, NULL, 0, 1, 1),
(496, 3, 'entry', 'rfid', '2025-11-04 13:33:28', -19.01897340, 47.53932160, NULL, 0, 1, 1),
(497, 2, 'entry', 'rfid', '2025-11-04 14:03:04', -19.01897360, 47.53932400, NULL, 0, 1, 1),
(498, 2, 'exit', 'rfid', '2025-11-04 12:53:51', -19.01897310, 47.53932050, NULL, 0, 1, 1),
(499, 2, 'exit', 'rfid', '2025-11-04 17:11:42', -19.01895940, 47.53957730, NULL, 0, 1, 1),
(501, 3, 'exit', 'rfid', '2025-11-04 17:36:24', -19.01904450, 47.53949090, NULL, 0, 1, 1),
(502, 3, 'entry', 'rfid', '2025-11-05 08:07:16', -19.01904450, 47.53949090, NULL, 0, 1, 1),
(503, 2, 'entry', 'rfid', '2025-11-05 08:10:54', -19.01904450, 47.53949090, NULL, 0, 1, 1),
(504, 3, 'exit', 'rfid', '2025-11-05 12:11:56', -19.01895460, 47.53936022, NULL, 0, 1, 1),
(505, 2, 'entry', 'rfid', '2025-11-05 13:32:49', -19.01895460, 47.53936022, NULL, 0, 1, 1),
(506, 2, 'exit', 'rfid', '2025-11-05 12:11:19', -19.01887986, 47.53942637, NULL, 0, 1, 1),
(507, 3, 'entry', 'rfid', '2025-11-05 13:33:29', -19.01887986, 47.53942637, NULL, 0, 1, 1),
(509, 16, 'entry', 'face', '2025-11-05 15:00:51', -20.92584790, 55.65733130, NULL, 0, 2, 7),
(510, 16, 'exit', 'face', '2025-11-05 15:06:01', -20.92584001, 55.65733717, NULL, 0, 2, 7),
(511, 39, 'entry', 'pin', '2025-11-05 15:24:32', -20.92584019, 55.65733625, NULL, 0, 2, 7),
(512, 16, 'entry', 'face', '2025-11-05 15:25:01', -20.92584019, 55.65733625, NULL, 0, 2, 7),
(513, 39, 'exit', 'face', '2025-11-05 15:26:00', -20.92584238, 55.65733506, NULL, 0, 2, 7),
(514, 2, 'exit', 'rfid', '2025-11-05 17:34:50', -19.01890366, 47.53941699, NULL, 0, 1, 1),
(515, 3, 'exit', 'pin', '2025-11-05 17:38:35', -19.01890366, 47.53941699, NULL, 0, 1, 1),
(516, 3, 'entry', 'rfid', '2025-11-06 08:03:54', -19.01892140, 47.53939860, NULL, 0, 1, 1),
(517, 2, 'entry', 'rfid', '2025-11-06 08:10:54', -19.01892140, 47.53939860, NULL, 0, 1, 1),
(518, 3, 'exit', 'rfid', '2025-11-06 12:03:24', -19.01894326, 47.53937126, NULL, 0, 1, 1),
(519, 2, 'exit', 'rfid', '2025-11-06 12:08:30', -19.01889663, 47.53944083, NULL, 0, 1, 1),
(520, 3, 'entry', 'rfid', '2025-11-06 13:28:34', -19.01884304, 47.53943870, NULL, 0, 1, 1),
(521, 2, 'entry', 'rfid', '2025-11-06 13:32:07', -19.01886562, 47.53940030, NULL, 0, 1, 1),
(522, 3, 'exit', 'rfid', '2025-11-06 17:34:24', -19.01889657, 47.53936030, NULL, 0, 1, 1),
(523, 3, 'entry', 'rfid', '2025-11-07 08:07:26', -19.01889364, 47.53935724, NULL, 0, 1, 1),
(525, 3, 'exit', 'rfid', '2025-11-07 12:03:16', -19.01889489, 47.53936178, NULL, 0, 1, 1),
(526, 3, 'entry', 'rfid', '2025-11-07 13:00:45', -19.01892770, 47.53934684, NULL, 0, 1, 1),
(527, 3, 'exit', 'rfid', '2025-11-07 16:35:05', -19.01893450, 47.53935960, NULL, 0, 1, 1),
(528, 3, 'entry', 'rfid', '2025-11-10 07:59:52', -19.01891821, 47.53936599, NULL, 0, 1, 1),
(529, 2, 'entry', 'rfid', '2025-11-10 08:06:55', -19.01891821, 47.53936599, NULL, 0, 1, 1),
(530, 3, 'exit', 'rfid', '2025-11-10 12:06:45', -19.01891091, 47.53936959, NULL, 0, 1, 1),
(531, 2, 'exit', 'rfid', '2025-11-10 12:06:57', -19.01891091, 47.53936959, NULL, 0, 1, 1),
(532, 3, 'entry', 'rfid', '2025-11-10 13:34:06', -19.01890263, 47.53937430, NULL, 0, 1, 1),
(533, 2, 'entry', 'rfid', '2025-11-10 13:41:37', -19.01890263, 47.53937430, NULL, 0, 1, 1),
(534, 3, 'exit', 'rfid', '2025-11-10 17:03:10', -19.01889371, 47.53939260, NULL, 0, 1, 1),
(535, 2, 'exit', 'rfid', '2025-11-10 17:03:20', -19.01889371, 47.53939260, NULL, 0, 1, 1),
(536, 3, 'entry', 'rfid', '2025-11-11 08:05:12', -19.01888620, 47.53939322, NULL, 0, 1, 1),
(537, 2, 'entry', 'rfid', '2025-11-11 08:05:25', -19.01888620, 47.53939322, NULL, 0, 1, 1),
(538, 3, 'exit', 'rfid', '2025-11-11 12:08:15', -19.01888274, 47.53939421, NULL, 0, 1, 1),
(539, 2, 'exit', 'rfid', '2025-11-11 12:09:40', -19.01888274, 47.53939421, NULL, 0, 1, 1),
(540, 3, 'entry', 'pin', '2025-11-11 13:35:15', -19.01887943, 47.53939628, NULL, 0, 1, 1),
(541, 2, 'entry', 'rfid', '2025-11-11 13:50:38', -19.01887412, 47.53939697, NULL, 0, 1, 1),
(542, 2, 'exit', 'rfid', '2025-11-11 17:43:01', -19.01887198, 47.53939776, NULL, 0, 1, 1),
(543, 3, 'exit', 'rfid', '2025-11-11 17:47:43', -19.01886952, 47.53939813, NULL, 0, 1, 1),
(544, 3, 'entry', 'rfid', '2025-11-12 08:06:41', -19.01886378, 47.53939845, NULL, 0, 1, 1),
(545, 2, 'entry', 'rfid', '2025-11-12 08:06:54', -19.01886378, 47.53939845, NULL, 0, 1, 1),
(546, 1, 'entry', 'rfid', '2025-11-12 09:04:25', -19.01886020, 47.53939835, NULL, 0, 1, 1),
(547, 3, 'exit', 'rfid', '2025-11-12 12:09:14', -19.01885845, 47.53939927, NULL, 0, 1, 1),
(548, 2, 'exit', 'rfid', '2025-11-12 12:11:26', -19.01885845, 47.53939927, NULL, 0, 1, 1),
(549, 3, 'entry', 'rfid', '2025-11-12 13:31:03', -19.01885669, 47.53939972, NULL, 0, 1, 1),
(550, 2, 'entry', 'rfid', '2025-11-12 14:00:24', -19.01885669, 47.53939972, NULL, 0, 1, 1),
(551, 2, 'exit', 'rfid', '2025-11-12 17:18:28', -19.01885585, 47.53939977, NULL, 0, 1, 1),
(552, 1, 'exit', 'rfid', '2025-11-12 17:18:48', -19.01885585, 47.53939977, NULL, 0, 1, 1),
(553, 3, 'exit', 'rfid', '2025-11-12 17:41:35', -19.01885585, 47.53939977, NULL, 0, 1, 1),
(554, 2, 'entry', 'rfid', '2025-11-13 13:00:00', -19.01885318, 47.53940004, NULL, 0, 1, 1),
(555, 3, 'entry', 'rfid', '2025-11-13 08:05:00', -19.01885318, 47.53940004, NULL, 0, 1, 1),
(556, 1, 'entry', 'rfid', '2025-11-13 08:16:37', -19.01885194, 47.53939943, NULL, 0, 1, 1),
(557, 1, 'exit', 'rfid', '2025-11-13 12:03:41', -19.01885128, 47.53939943, NULL, 0, 1, 1),
(558, 3, 'exit', 'rfid', '2025-11-13 12:06:14', -19.01885061, 47.53939982, NULL, 0, 1, 1),
(559, 2, 'exit', 'rfid', '2025-11-13 12:06:24', -19.01885061, 47.53939982, NULL, 0, 1, 1),
(560, 2, 'exit', 'rfid', '2025-11-13 16:48:56', -19.01882452, 47.53942104, NULL, 0, 1, 1),
(561, 3, 'exit', 'rfid', '2025-11-13 16:49:03', -19.01882452, 47.53942104, NULL, 0, 1, 1),
(564, 2, 'exit', 'rfid', '2025-11-14 12:03:30', -19.01883644, 47.53937612, NULL, 0, 1, 1),
(565, 3, 'exit', 'rfid', '2025-11-14 12:09:47', -19.01883644, 47.53937612, NULL, 0, 1, 1),
(566, 2, 'entry', 'rfid', '2025-11-14 13:17:50', -19.01883843, 47.53937933, NULL, 0, 1, 1),
(567, 3, 'entry', 'rfid', '2025-11-14 13:31:12', -19.01883843, 47.53937933, NULL, 0, 1, 1),
(568, 3, 'entry', 'rfid', '2025-11-13 13:31:00', -19.01885318, 47.53940004, NULL, 0, 1, 1),
(569, 2, 'exit', 'rfid', '2025-11-06 17:35:30', -19.01889663, 47.53944083, NULL, 0, 1, 1),
(571, 2, 'entry', 'rfid', '2025-11-13 07:57:00', -19.01885318, 47.53940004, NULL, 0, 1, 1),
(572, 3, 'exit', 'rfid', '2025-11-14 17:53:24', -19.01884557, 47.53938918, NULL, 0, 1, 1),
(573, 2, 'exit', 'rfid', '2025-11-14 19:07:48', -19.01884646, 47.53939844, NULL, 0, 1, 1),
(574, 3, 'entry', 'rfid', '2025-11-15 09:05:21', -19.01884527, 47.53940500, NULL, 0, 1, 1),
(575, 2, 'entry', 'rfid', '2025-11-15 09:18:08', -19.01884461, 47.53940520, NULL, 0, 1, 1),
(576, 2, 'exit', 'rfid', '2025-11-15 11:57:01', -19.01884169, 47.53940774, NULL, 0, 1, 1),
(577, 3, 'exit', 'rfid', '2025-11-15 13:04:25', -19.01884169, 47.53940774, NULL, 0, 1, 1),
(578, 3, 'entry', 'rfid', '2025-11-17 08:01:02', -19.01884701, 47.53940595, NULL, 0, 1, 1),
(579, 2, 'entry', 'rfid', '2025-11-17 08:04:09', -19.01884521, 47.53940701, NULL, 0, 1, 1),
(581, 2, 'exit', 'rfid', '2025-11-17 12:06:29', -19.01884455, 47.53940705, NULL, 0, 1, 1),
(582, 3, 'exit', 'rfid', '2025-11-17 12:09:36', -19.01884455, 47.53940705, NULL, 0, 1, 1),
(583, 3, 'entry', 'rfid', '2025-11-18 13:36:10', -19.01885664, 47.53941334, NULL, 0, 1, 1),
(584, 2, 'entry', 'rfid', '2025-11-18 13:41:45', -19.01885664, 47.53941334, NULL, 0, 1, 1),
(585, 2, 'entry', 'rfid', '2025-11-17 13:00:00', -19.01889350, 47.53935320, '', 0, 1, 1),
(586, 2, 'exit', 'rfid', '2025-11-17 17:00:00', -19.01890200, 47.53932950, '', 0, 1, 1),
(587, 3, 'exit', 'pin', '2025-11-17 17:00:00', -19.01889170, 47.53934090, '', 0, 1, 1),
(588, 3, 'entry', 'pin', '2025-11-17 13:45:00', -19.01889830, 47.53934810, '', 0, 1, 1),
(591, 3, 'entry', 'pin', '2025-11-14 08:05:00', -19.01880830, 47.53935763, NULL, 0, 1, 1),
(592, 2, 'entry', 'rfid', '2025-11-14 08:00:00', -19.01880830, 47.53935763, NULL, 0, 1, 1),
(593, 3, 'entry', 'rfid', '2025-11-18 08:00:00', -19.01884701, 47.53940595, NULL, 0, 1, 1),
(594, 2, 'entry', 'rfid', '2025-11-18 08:00:00', -19.01884521, 47.53940701, NULL, 0, 1, 1),
(595, 2, 'exit', 'rfid', '2025-11-18 12:00:00', -19.01884455, 47.53940705, NULL, 0, 1, 1),
(596, 3, 'exit', 'rfid', '2025-11-18 12:00:00', -19.01884455, 47.53940705, NULL, 0, 1, 1),
(597, 2, 'exit', 'rfid', '2025-11-18 17:09:10', -19.01885865, 47.53941565, NULL, 0, 1, 1),
(598, 3, 'exit', 'rfid', '2025-11-18 17:36:29', -19.01886528, 47.53938724, NULL, 0, 1, 1),
(599, 2, 'entry', 'rfid', '2025-11-19 08:02:10', -19.01886584, 47.53938997, NULL, 0, 1, 1),
(600, 3, 'entry', 'rfid', '2025-11-19 08:04:42', -19.01886740, 47.53939066, NULL, 0, 1, 1),
(601, 2, 'entry', 'rfid', '2025-11-19 13:33:40', -19.01886688, 47.53939495, NULL, 0, 1, 1),
(602, 3, 'entry', 'rfid', '2025-11-19 13:37:10', -19.01886688, 47.53939495, NULL, 0, 1, 1),
(603, 2, 'exit', 'rfid', '2025-11-19 12:00:00', -19.01890200, 47.53932950, '', 0, 1, 1),
(604, 3, 'exit', 'pin', '2025-11-19 12:00:00', -19.01888810, 47.53936520, '', 0, 1, 1),
(605, 2, 'exit', 'rfid', '2025-11-19 17:12:10', -19.01886768, 47.53939755, NULL, 0, 1, 1),
(606, 3, 'exit', 'rfid', '2025-11-19 17:47:01', -19.01886768, 47.53939755, NULL, 0, 1, 1),
(607, 2, 'entry', 'rfid', '2025-11-20 08:07:40', -19.01886741, 47.53940150, NULL, 0, 1, 1),
(608, 3, 'entry', 'rfid', '2025-11-20 08:07:52', -19.01886741, 47.53940150, NULL, 0, 1, 1),
(609, 2, 'exit', 'rfid', '2025-11-20 12:11:27', -19.01886669, 47.53940291, NULL, 0, 1, 1),
(610, 2, 'entry', 'rfid', '2025-11-20 13:38:44', -19.01887610, 47.53939796, NULL, 0, 1, 1),
(611, 3, 'entry', 'rfid', '2025-11-20 14:00:00', -19.01887559, 47.53940162, NULL, 0, 1, 1),
(612, 2, 'exit', 'rfid', '2025-11-20 17:30:15', -19.01887774, 47.53940586, NULL, 0, 1, 1),
(613, 3, 'exit', 'rfid', '2025-11-20 12:00:00', -19.01887774, 47.53940586, NULL, 0, 1, 1),
(614, 3, 'exit', 'rfid', '2025-11-20 17:45:21', -19.01887774, 47.53940586, NULL, 0, 1, 1);
INSERT INTO `pointages` (`ID`, `employee_id`, `type_`, `auth_method`, `timestamp_`, `latitude`, `longitude`, `photo_path`, `synced`, `SiegeID`, `company_id`) VALUES
(615, 2, 'entry', 'rfid', '2025-11-21 08:14:22', -19.01887643, 47.53941336, NULL, 0, 1, 1),
(616, 3, 'entry', 'rfid', '2025-11-21 08:05:00', -19.01887643, 47.53941336, NULL, 0, 1, 1),
(617, 3, 'exit', 'rfid', '2025-11-21 12:10:00', -19.01887643, 47.53941336, NULL, 0, 1, 1),
(618, 3, 'entry', 'rfid', '2025-11-21 13:32:00', -19.01887700, 47.53941544, NULL, 0, 1, 1),
(619, 3, 'exit', 'rfid', '2025-11-21 18:00:55', -19.01887353, 47.53942182, NULL, 0, 1, 1),
(620, 3, 'entry', 'rfid', '2025-11-24 08:05:00', -19.01889290, 47.53932970, '', 0, 1, 1),
(621, 2, 'entry', 'rfid', '2025-11-24 08:05:00', -19.01889350, 47.53935320, '', 0, 1, 1),
(622, 2, 'exit', 'rfid', '2025-11-21 12:00:00', -19.01890200, 47.53932950, '', 0, 1, 1),
(623, 2, 'entry', 'rfid', '2025-11-21 13:00:00', -19.01889590, 47.53933060, '', 0, 1, 1),
(624, 2, 'exit', 'rfid', '2025-11-21 17:00:00', -19.01890090, 47.53933290, '', 0, 1, 1),
(625, 3, 'exit', 'rfid', '2025-11-24 12:08:03', -19.01880873, 47.53952382, NULL, 0, 1, 1),
(626, 2, 'exit', 'rfid', '2025-11-24 12:09:28', -19.01882492, 47.53954798, NULL, 0, 1, 1),
(627, 3, 'entry', 'rfid', '2025-11-24 13:32:40', -19.01882322, 47.53955505, NULL, 0, 1, 1),
(628, 2, 'entry', 'rfid', '2025-11-24 13:37:54', -19.01882322, 47.53955505, NULL, 0, 1, 1),
(629, 2, 'exit', 'rfid', '2025-11-24 17:09:04', -19.01883726, 47.53954973, NULL, 0, 1, 1),
(630, 3, 'exit', 'rfid', '2025-11-24 17:38:25', -19.01883726, 47.53954973, NULL, 0, 1, 1),
(631, 2, 'entry', 'rfid', '2025-11-25 08:06:25', -19.01883407, 47.53955295, NULL, 0, 1, 1),
(632, 3, 'entry', 'rfid', '2025-11-25 08:20:33', -19.01883407, 47.53955295, NULL, 0, 1, 1),
(633, 3, 'exit', 'rfid', '2025-11-25 12:12:30', -19.01883443, 47.53955047, NULL, 0, 1, 1),
(634, 3, 'entry', 'rfid', '2025-11-25 13:20:00', -19.01130470, 47.53737280, NULL, 0, 1, 1),
(635, 2, 'exit', 'rfid', '2025-11-25 17:27:37', -19.01885416, 47.53950637, NULL, 0, 1, 1),
(636, 3, 'exit', 'rfid', '2025-11-25 17:38:25', -19.01885416, 47.53950637, NULL, 0, 1, 1),
(637, 2, 'exit', 'rfid', '2025-11-26 12:06:33', -19.01883581, 47.53950643, NULL, 0, 1, 1),
(638, 3, 'entry', 'rfid', '2025-11-26 08:05:00', -19.01883581, 47.53950643, NULL, 0, 1, 1),
(639, 3, 'exit', 'rfid', '2025-11-26 12:05:00', -19.01883581, 47.53950643, NULL, 0, 1, 1),
(640, 2, 'entry', 'rfid', '2025-11-26 13:28:45', -19.01872123, 47.53935236, NULL, 0, 1, 1),
(641, 2, 'exit', 'rfid', '2025-11-25 12:00:00', -19.01890200, 47.53932950, '', 0, 1, 1),
(642, 2, 'entry', 'rfid', '2025-11-25 13:00:00', -19.01889590, 47.53933060, '', 0, 1, 1),
(644, 2, 'entry', 'rfid', '2025-11-26 08:00:00', -19.01872123, 47.53935236, NULL, 0, 1, 1),
(645, 3, 'entry', 'rfid', '2025-11-26 13:30:00', -19.01883581, 47.53950643, NULL, 0, 1, 1),
(646, 2, 'exit', 'rfid', '2025-11-26 17:27:58', -19.01872339, 47.53936300, NULL, 0, 1, 1),
(647, 3, 'exit', 'pin', '2025-11-26 17:40:30', -19.01872339, 47.53936300, NULL, 0, 1, 1),
(648, 2, 'entry', 'rfid', '2025-11-27 08:06:18', -19.01875736, 47.53937224, NULL, 0, 1, 1),
(649, 3, 'entry', 'pin', '2025-11-27 08:06:54', -19.01875736, 47.53937224, NULL, 0, 1, 1),
(650, 2, 'exit', 'rfid', '2025-11-27 12:07:01', -19.01881055, 47.53938809, NULL, 0, 1, 1),
(651, 3, 'exit', 'pin', '2025-11-27 12:12:10', -19.01881055, 47.53938809, NULL, 0, 1, 1),
(652, 3, 'entry', 'pin', '2025-11-27 13:32:56', -19.01881884, 47.53939546, NULL, 0, 1, 1),
(653, 2, 'entry', 'rfid', '2025-11-27 13:33:04', -19.01881884, 47.53939546, NULL, 0, 1, 1),
(654, 2, 'exit', 'rfid', '2025-11-27 16:59:36', -19.01882754, 47.53939773, NULL, 0, 1, 1),
(655, 3, 'exit', 'pin', '2025-11-27 17:41:57', -19.01882754, 47.53939773, NULL, 0, 1, 1),
(656, 2, 'entry', 'rfid', '2025-11-28 07:56:47', -19.01882689, 47.53940543, NULL, 0, 1, 1),
(657, 2, 'exit', 'rfid', '2025-11-28 12:00:00', -19.01130470, 47.53737280, NULL, 0, 1, 1),
(658, 2, 'entry', 'rfid', '2025-11-28 13:02:02', -19.01884630, 47.53944523, NULL, 0, 1, 1),
(659, 3, 'entry', 'pin', '2025-11-28 13:38:43', -19.01885035, 47.53944035, NULL, 0, 1, 1),
(660, 3, 'exit', 'pin', '2025-11-28 12:00:00', -19.01130470, 47.53737280, NULL, 0, 1, 1),
(661, 3, 'entry', 'rfid', '2025-11-28 08:00:00', -19.01130470, 47.53737280, NULL, 0, 1, 1),
(663, 2, 'exit', 'rfid', '2025-11-28 17:47:41', -19.01885245, 47.53944522, NULL, 0, 1, 1),
(664, 3, 'exit', 'rfid', '2025-11-28 17:48:37', -19.01885245, 47.53944522, NULL, 0, 1, 1),
(665, 3, 'entry', 'rfid', '2025-12-01 08:03:31', -19.01885459, 47.53944492, NULL, 0, 1, 1),
(666, 3, 'exit', 'rfid', '2025-12-01 12:04:00', -19.01891310, 47.53939430, NULL, 0, 1, 1),
(667, 3, 'entry', 'rfid', '2025-12-01 13:30:00', -19.01130470, 47.53737280, NULL, 0, 1, 1),
(668, 3, 'exit', 'rfid', '2025-12-01 17:47:11', -19.01885626, 47.53944396, NULL, 0, 1, 1),
(669, 3, 'entry', 'rfid', '2025-12-02 08:05:00', -19.01885558, 47.53944865, NULL, 0, 1, 1),
(670, 2, 'entry', 'rfid', '2025-12-02 08:10:28', -19.01885558, 47.53944865, NULL, 0, 1, 1),
(671, 2, 'entry', 'rfid', '2025-12-02 13:23:52', -19.01885558, 47.53944865, NULL, 0, 1, 1),
(672, 3, 'entry', 'rfid', '2025-12-02 13:23:59', -19.01885558, 47.53944865, NULL, 0, 1, 1),
(673, 2, 'exit', 'rfid', '2025-12-02 12:00:00', -19.01130470, 47.53737280, NULL, 0, 1, 1),
(674, 3, 'exit', 'rfid', '2025-12-02 12:00:00', -19.01130470, 47.53737280, NULL, 0, 1, 1),
(675, 2, 'entry', 'rfid', '2025-12-01 08:00:00', -19.01130470, 47.53737280, NULL, 0, 1, 1),
(676, 2, 'exit', 'rfid', '2025-12-01 17:00:00', -19.01130470, 47.53737280, NULL, 0, 1, 1),
(677, 2, 'exit', 'rfid', '2025-12-02 17:30:59', -19.01885448, 47.53944824, NULL, 0, 1, 1),
(678, 3, 'exit', 'rfid', '2025-12-02 17:35:17', -19.01885448, 47.53944824, NULL, 0, 1, 1),
(679, 3, 'entry', 'rfid', '2025-12-03 08:04:36', -19.01885708, 47.53944594, NULL, 0, 1, 1),
(680, 2, 'entry', 'rfid', '2025-12-03 08:05:21', -19.01885708, 47.53944594, NULL, 0, 1, 1),
(681, 2, 'exit', 'rfid', '2025-12-03 11:40:33', -19.01885380, 47.53945186, NULL, 0, 1, 1),
(682, 3, 'exit', 'rfid', '2025-12-03 12:02:05', -19.01885380, 47.53945186, NULL, 0, 1, 1),
(683, 2, 'entry', 'rfid', '2025-12-03 12:24:26', -19.01885201, 47.53944128, NULL, 0, 1, 1),
(684, 3, 'entry', 'rfid', '2025-12-03 13:31:23', -19.01885201, 47.53944128, NULL, 0, 1, 1),
(685, 3, 'exit', 'rfid', '2025-12-03 17:45:55', -19.01886432, 47.53944137, NULL, 0, 1, 1),
(686, 2, 'exit', 'rfid', '2025-12-03 18:23:33', -19.01886432, 47.53944137, NULL, 0, 1, 1),
(687, 2, 'entry', 'rfid', '2025-12-04 07:31:13', -19.01886084, 47.53944752, NULL, 0, 1, 1),
(688, 3, 'entry', 'rfid', '2025-12-04 08:06:10', -19.01886084, 47.53944752, NULL, 0, 1, 1),
(689, 3, 'exit', 'rfid', '2025-12-04 12:03:33', -19.01885785, 47.53945364, NULL, 0, 1, 1),
(690, 3, 'entry', 'rfid', '2025-12-04 13:31:00', -18.98441280, 47.53737280, NULL, 0, 1, 1),
(691, 2, 'exit', 'rfid', '2025-12-04 16:41:07', -19.01885785, 47.53945364, NULL, 0, 1, 1),
(692, 3, 'exit', 'rfid', '2025-12-04 17:45:43', -19.01885785, 47.53945364, NULL, 0, 1, 1),
(693, 2, 'entry', 'rfid', '2025-12-05 07:59:39', -19.01885355, 47.53946014, NULL, 0, 1, 1),
(694, 3, 'entry', 'rfid', '2025-12-05 08:02:12', -19.01885355, 47.53946014, NULL, 0, 1, 1),
(695, 3, 'exit', 'rfid', '2025-12-05 12:02:14', -19.01884551, 47.53946055, NULL, 0, 1, 1),
(696, 2, 'exit', 'rfid', '2025-12-05 12:02:29', -19.01884551, 47.53946055, NULL, 0, 1, 1),
(697, 2, 'entry', 'rfid', '2025-12-05 13:04:11', -19.01883953, 47.53946397, NULL, 0, 1, 1),
(698, 3, 'entry', 'rfid', '2025-12-05 13:37:31', -19.01883953, 47.53946397, NULL, 0, 1, 1),
(699, 3, 'exit', 'pin', '2025-12-05 17:21:01', -19.01880180, 47.53957700, NULL, 0, 1, 1),
(700, 2, 'exit', 'rfid', '2025-12-05 17:33:55', -19.01880180, 47.53957700, NULL, 0, 1, 1),
(701, 3, 'entry', 'rfid', '2025-12-06 09:05:00', -18.98441280, 47.53737280, NULL, 0, 1, 1),
(703, 3, 'exit', 'rfid', '2025-12-06 11:57:00', -19.01879775, 47.53955849, NULL, 0, 1, 1),
(705, 3, 'entry', 'rfid', '2025-12-08 08:08:27', -19.01879979, 47.53955288, NULL, 0, 1, 1),
(706, 2, 'entry', 'rfid', '2025-12-08 08:20:53', -19.01879979, 47.53955288, NULL, 0, 1, 1),
(708, 3, 'exit', 'rfid', '2025-12-08 12:05:00', -19.01854720, 47.54309120, NULL, 0, 1, 1),
(709, 2, 'exit', 'rfid', '2025-12-08 12:06:30', -19.01881468, 47.53954901, NULL, 0, 1, 1),
(710, 2, 'entry', 'rfid', '2025-12-08 13:13:41', -19.01884079, 47.53950583, NULL, 0, 1, 1),
(711, 3, 'entry', 'rfid', '2025-12-08 13:34:09', -19.01884079, 47.53950583, NULL, 0, 1, 1),
(712, 2, 'exit', 'rfid', '2025-12-08 17:26:59', -19.01882895, 47.53950786, NULL, 0, 1, 1),
(713, 3, 'exit', 'rfid', '2025-12-08 17:53:07', -19.01882895, 47.53950786, NULL, 0, 1, 1),
(714, 2, 'entry', 'rfid', '2025-12-09 07:57:32', -19.01883224, 47.53951379, NULL, 0, 1, 1),
(715, 3, 'entry', 'rfid', '2025-12-09 08:09:08', -19.01883224, 47.53951379, NULL, 0, 1, 1),
(716, 2, 'exit', 'rfid', '2025-12-09 12:07:47', -19.01882632, 47.53950797, NULL, 0, 1, 1),
(717, 3, 'exit', 'rfid', '2025-12-09 12:08:19', -19.01882632, 47.53950797, NULL, 0, 1, 1),
(718, 3, 'entry', 'rfid', '2025-12-09 13:30:00', -19.02182400, 47.54309120, NULL, 0, 1, 1),
(719, 3, 'exit', 'rfid', '2025-12-09 17:48:58', -19.01882293, 47.53951408, NULL, 0, 1, 1),
(720, 3, 'entry', 'rfid', '2025-12-10 08:21:57', -19.01883237, 47.53950159, NULL, 0, 1, 1),
(721, 2, 'entry', 'rfid', '2025-12-10 08:34:25', -19.01885482, 47.53947189, NULL, 0, 1, 1),
(722, 2, 'exit', 'rfid', '2025-12-10 12:04:15', -19.01885961, 47.53946482, NULL, 0, 1, 1),
(723, 3, 'exit', 'rfid', '2025-12-10 12:05:01', -19.01885961, 47.53946482, NULL, 0, 1, 1),
(725, 3, 'entry', 'rfid', '2025-12-10 13:29:47', -19.01886730, 47.53946329, NULL, 0, 1, 1),
(726, 3, 'exit', 'rfid', '2025-12-10 17:00:00', -19.01892320, 47.53936570, NULL, 0, 1, 1),
(727, 3, 'entry', 'rfid', '2025-12-11 08:05:00', -19.01892320, 47.53936570, NULL, 0, 1, 1),
(728, 3, 'exit', 'rfid', '2025-12-11 12:05:00', -19.01892320, 47.53936570, NULL, 0, 1, 1),
(729, 3, 'entry', 'rfid', '2025-12-11 13:30:00', -19.01072790, 47.53737290, NULL, 0, 1, 1),
(730, 3, 'exit', 'rfid', '2025-12-11 18:11:47', -19.01893445, 47.53937561, NULL, 0, 1, 1),
(731, 2, 'entry', 'rfid', '2025-12-12 07:50:12', -19.01887283, 47.53941684, NULL, 0, 1, 1),
(732, 3, 'entry', 'rfid', '2025-12-12 08:06:23', -19.01887283, 47.53941684, NULL, 0, 1, 1),
(734, 3, 'exit', 'rfid', '2025-12-12 12:02:27', -19.01887722, 47.53941442, NULL, 0, 1, 1),
(735, 3, 'entry', 'rfid', '2025-12-12 13:30:42', -19.01888684, 47.53940950, NULL, 0, 1, 1),
(736, 3, 'exit', 'rfid', '2025-12-12 17:53:40', -19.01890379, 47.53940554, NULL, 0, 1, 1),
(737, 3, 'entry', 'face', '2025-12-13 09:24:18', -19.01890048, 47.53940490, NULL, 0, 1, 1),
(738, 3, 'exit', 'pin', '2025-12-13 13:52:56', -19.01889425, 47.53941712, NULL, 0, 1, 1),
(739, 2, 'entry', 'rfid', '2025-12-15 08:06:45', -19.01889321, 47.53943293, NULL, 0, 1, 1),
(740, 3, 'entry', 'rfid', '2025-12-15 08:07:12', -19.01889321, 47.53943293, NULL, 0, 1, 1),
(741, 2, 'exit', 'rfid', '2025-12-15 12:05:28', -19.01893536, 47.53942194, NULL, 0, 1, 1),
(742, 3, 'exit', 'rfid', '2025-12-15 12:06:06', -19.01893536, 47.53942194, NULL, 0, 1, 1),
(743, 2, 'entry', 'rfid', '2025-12-15 13:15:33', -19.01891371, 47.53943500, NULL, 0, 1, 1),
(744, 3, 'entry', 'rfid', '2025-12-15 13:31:35', -19.01891371, 47.53943500, NULL, 0, 1, 1),
(745, 3, 'exit', 'pin', '2025-12-15 17:36:27', -19.01888918, 47.53945770, NULL, 0, 1, 1),
(746, 2, 'exit', 'rfid', '2025-12-15 17:38:57', -19.01888918, 47.53945770, NULL, 0, 1, 1),
(747, 2, 'entry', 'rfid', '2025-12-16 08:07:46', -19.01889116, 47.53945817, NULL, 0, 1, 1),
(748, 3, 'entry', 'rfid', '2025-12-16 08:07:00', -18.99039180, 47.53798670, NULL, 0, 1, 1),
(749, 2, 'exit', 'rfid', '2025-12-16 12:10:27', -19.01890885, 47.53943856, NULL, 0, 1, 1),
(750, 3, 'exit', 'rfid', '2025-12-16 12:11:37', -19.01890885, 47.53943856, NULL, 0, 1, 1),
(751, 2, 'entry', 'rfid', '2025-12-16 13:07:59', -19.01890443, 47.53943905, NULL, 0, 1, 1),
(752, 3, 'entry', 'rfid', '2025-12-16 13:23:39', -19.01890443, 47.53943905, NULL, 0, 1, 1),
(753, 2, 'exit', 'rfid', '2025-12-16 17:24:06', -19.01889956, 47.53944309, NULL, 0, 1, 1),
(754, 3, 'exit', 'rfid', '2025-12-16 17:47:16', -19.01889956, 47.53944309, NULL, 0, 1, 1),
(755, 2, 'entry', 'rfid', '2025-12-17 07:59:47', -19.01889821, 47.53944281, NULL, 0, 1, 1),
(756, 3, 'entry', 'rfid', '2025-12-17 08:10:10', -19.01889821, 47.53944281, NULL, 0, 1, 1),
(757, 2, 'exit', 'rfid', '2025-12-17 12:07:11', -19.01889854, 47.53944221, NULL, 0, 1, 1),
(758, 3, 'exit', 'rfid', '2025-12-17 12:11:16', -19.01889854, 47.53944221, NULL, 0, 1, 1),
(759, 2, 'entry', 'rfid', '2025-12-17 13:07:33', -19.01887979, 47.53944568, NULL, 0, 1, 1),
(760, 3, 'entry', 'pin', '2025-12-17 13:27:44', -19.01887979, 47.53944568, NULL, 0, 1, 1),
(761, 2, 'exit', 'rfid', '2025-12-17 17:29:19', -19.01891234, 47.53944636, NULL, 0, 1, 1),
(762, 3, 'exit', 'rfid', '2025-12-17 17:34:52', -19.01891234, 47.53944636, NULL, 0, 1, 1),
(763, 3, 'entry', 'rfid', '2025-12-18 08:08:28', -19.01890359, 47.53944033, NULL, 0, 1, 1),
(764, 2, 'entry', 'rfid', '2025-12-18 08:55:21', -19.01890359, 47.53944033, NULL, 0, 1, 1),
(765, 3, 'exit', 'rfid', '2025-12-18 12:08:28', -19.01889896, 47.53943850, NULL, 0, 1, 1),
(766, 3, 'entry', 'pin', '2025-12-18 13:36:38', -19.01889556, 47.53944010, NULL, 0, 1, 1),
(767, 3, 'exit', 'pin', '2025-12-18 17:50:51', -19.01889150, 47.53943172, NULL, 0, 1, 1),
(768, 3, 'entry', 'rfid', '2025-12-19 08:30:06', -19.01889094, 47.53943272, NULL, 0, 1, 1),
(769, 2, 'entry', 'rfid', '2025-12-19 08:35:27', -19.01889094, 47.53943272, NULL, 0, 1, 1),
(770, 2, 'exit', 'rfid', '2025-12-19 10:57:52', -19.01889026, 47.53943091, NULL, 0, 1, 1),
(771, 3, 'exit', 'rfid', '2025-12-19 12:14:15', -19.01889026, 47.53943091, NULL, 0, 1, 1),
(772, 39, 'entry', 'face', '2025-12-26 11:27:21', -20.92585008, 55.65735034, NULL, 0, 2, 7),
(773, 3, 'entry', 'rfid', '2026-01-05 08:05:00', -18.92352000, 47.54309120, NULL, 0, 1, 1),
(774, 2, 'entry', 'rfid', '2026-01-05 08:05:00', -18.92352000, 47.54309120, NULL, 0, 1, 1),
(775, 2, 'exit', 'rfid', '2026-01-05 12:05:00', -18.92352000, 47.54309120, NULL, 0, 1, 1),
(776, 3, 'exit', 'rfid', '2026-01-05 12:05:00', -18.92352000, 47.54309120, NULL, 0, 1, 1),
(777, 3, 'entry', 'rfid', '2026-01-05 13:31:38', -19.01886780, 47.53941035, NULL, 0, 1, 1),
(778, 2, 'entry', 'rfid', '2026-01-05 13:32:23', -19.01886780, 47.53941035, NULL, 0, 1, 1),
(779, 2, 'exit', 'rfid', '2026-01-05 17:34:53', -19.01885171, 47.53941357, NULL, 0, 1, 1),
(780, 3, 'exit', 'pin', '2026-01-05 17:35:40', -19.01885171, 47.53941357, NULL, 0, 1, 1),
(781, 3, 'entry', 'pin', '2026-01-06 08:05:38', -19.01885605, 47.53941515, NULL, 0, 1, 1),
(782, 2, 'entry', 'rfid', '2026-01-06 08:10:38', -19.01885605, 47.53941515, NULL, 0, 1, 1),
(783, 3, 'exit', 'rfid', '2026-01-06 12:06:00', -18.91368960, 47.53653760, NULL, 0, 1, 1),
(784, 2, 'exit', 'rfid', '2026-01-06 12:07:00', -18.91368960, 47.53653760, NULL, 0, 1, 1),
(785, 3, 'entry', 'rfid', '2026-01-06 13:29:00', -18.91368960, 47.53653760, NULL, 0, 1, 1),
(786, 2, 'entry', 'rfid', '2026-01-06 12:29:00', -18.91368960, 47.53653760, NULL, 0, 1, 1),
(787, 3, 'exit', 'pin', '2026-01-06 17:31:12', -19.01885849, 47.53941636, NULL, 0, 1, 1),
(788, 2, 'exit', 'rfid', '2026-01-06 17:32:04', -19.01885849, 47.53941636, NULL, 0, 1, 1),
(789, 2, 'entry', 'rfid', '2026-01-07 08:05:33', -19.01885266, 47.53941436, NULL, 0, 1, 1),
(790, 3, 'entry', 'pin', '2026-01-07 08:10:08', -19.01885266, 47.53941436, NULL, 0, 1, 1),
(791, 3, 'exit', 'pin', '2026-01-07 12:04:41', -19.01885345, 47.53941371, NULL, 0, 1, 1),
(792, 2, 'exit', 'rfid', '2026-01-07 12:00:00', -18.91368960, 47.53653760, NULL, 0, 1, 1),
(793, 2, 'entry', 'rfid', '2026-01-07 13:00:00', -18.91368960, 47.53653760, NULL, 0, 1, 1),
(794, 3, 'entry', 'rfid', '2026-01-07 13:30:00', -18.91368960, 47.53653760, NULL, 0, 1, 1),
(795, 2, 'exit', 'rfid', '2026-01-07 17:11:55', -19.01886350, 47.53939053, NULL, 0, 1, 1),
(796, 3, 'exit', 'rfid', '2026-01-07 17:33:17', -19.01886350, 47.53939053, NULL, 0, 1, 1),
(797, 2, 'entry', 'rfid', '2026-01-08 08:00:44', -19.01895533, 47.53929200, NULL, 0, 1, 1),
(798, 3, 'entry', 'pin', '2026-01-08 08:02:30', -19.01895533, 47.53929200, NULL, 0, 1, 1),
(799, 3, 'exit', 'pin', '2026-01-08 12:04:13', -19.01888902, 47.53938272, NULL, 0, 1, 1),
(800, 2, 'exit', 'rfid', '2026-01-08 12:09:12', -19.01888902, 47.53938272, NULL, 0, 1, 1),
(801, 3, 'entry', 'pin', '2026-01-08 13:33:33', -19.01887866, 47.53939671, NULL, 0, 1, 1),
(802, 2, 'entry', 'rfid', '2026-01-08 13:45:06', -19.01887866, 47.53939671, NULL, 0, 1, 1),
(803, 2, 'exit', 'rfid', '2026-01-08 17:26:04', -19.01887449, 47.53940246, NULL, 0, 1, 1),
(804, 3, 'exit', 'face', '2026-01-08 17:34:55', -19.01887449, 47.53940246, NULL, 0, 1, 1),
(805, 3, 'entry', 'pin', '2026-01-09 08:06:53', -19.01887190, 47.53940698, NULL, 0, 1, 1),
(806, 2, 'entry', 'rfid', '2026-01-09 08:11:52', -19.01887190, 47.53940698, NULL, 0, 1, 1),
(807, 3, 'exit', 'pin', '2026-01-09 12:03:35', -19.01887046, 47.53940941, NULL, 0, 1, 1),
(808, 2, 'exit', 'rfid', '2026-01-09 12:09:58', -19.01887046, 47.53940941, NULL, 0, 1, 1),
(809, 3, 'entry', 'pin', '2026-01-09 13:04:39', -19.01886960, 47.53941062, NULL, 0, 1, 1),
(810, 2, 'entry', 'rfid', '2026-01-09 13:04:50', -19.01886960, 47.53941062, NULL, 0, 1, 1),
(811, 3, 'exit', 'pin', '2026-01-09 17:07:49', -19.01886935, 47.53941186, NULL, 0, 1, 1),
(812, 2, 'exit', 'rfid', '2026-01-09 17:08:18', -19.01886935, 47.53941186, NULL, 0, 1, 1),
(813, 2, 'entry', 'rfid', '2026-01-12 07:57:33', -19.01887224, 47.53941998, NULL, 0, 1, 1),
(814, 3, 'entry', 'pin', '2026-01-12 08:05:33', -19.01887224, 47.53941998, NULL, 0, 1, 1),
(815, 3, 'exit', 'pin', '2026-01-12 12:03:15', -19.01886640, 47.53941726, NULL, 0, 1, 1),
(816, 2, 'exit', 'rfid', '2026-01-12 12:11:23', -19.01886640, 47.53941726, NULL, 0, 1, 1),
(817, 2, 'entry', 'rfid', '2026-01-12 13:13:30', -19.01886414, 47.53942499, NULL, 0, 1, 1),
(818, 3, 'entry', 'pin', '2026-01-12 13:26:06', -19.01886414, 47.53942499, NULL, 0, 1, 1),
(819, 2, 'exit', 'rfid', '2026-01-12 17:01:04', -19.01886399, 47.53942289, NULL, 0, 1, 1),
(820, 3, 'exit', 'face', '2026-01-12 17:33:09', -19.01886399, 47.53942289, NULL, 0, 1, 1),
(821, 3, 'entry', 'pin', '2026-01-13 08:03:24', -19.01886792, 47.53943794, NULL, 0, 1, 1),
(822, 2, 'entry', 'rfid', '2026-01-13 08:07:29', -19.01886792, 47.53943794, NULL, 0, 1, 1),
(823, 3, 'exit', 'pin', '2026-01-13 12:02:19', -19.01886420, 47.53942030, NULL, 0, 1, 1),
(824, 2, 'exit', 'rfid', '2026-01-13 12:33:34', -19.01886420, 47.53942030, NULL, 0, 1, 1),
(825, 2, 'entry', 'rfid', '2026-01-13 13:30:30', -19.01886535, 47.53942065, NULL, 0, 1, 1),
(826, 2, 'exit', 'rfid', '2026-01-13 17:42:02', -19.01886413, 47.53941126, NULL, 0, 1, 1),
(827, 3, 'exit', 'pin', '2026-01-13 17:55:10', -19.01886413, 47.53941126, NULL, 0, 1, 1),
(828, 3, 'entry', 'pin', '2026-01-14 08:05:36', -19.01886360, 47.53941368, NULL, 0, 1, 1),
(829, 2, 'entry', 'rfid', '2026-01-14 08:05:43', -19.01886360, 47.53941368, NULL, 0, 1, 1),
(830, 3, 'exit', 'pin', '2026-01-14 12:00:26', -19.01886362, 47.53941491, NULL, 0, 1, 1),
(831, 2, 'exit', 'rfid', '2026-01-14 12:25:51', -19.01886362, 47.53941491, NULL, 0, 1, 1),
(832, 3, 'entry', 'face', '2026-01-14 13:30:39', -19.01886553, 47.53942007, NULL, 0, 1, 1),
(833, 2, 'entry', 'rfid', '2026-01-14 13:33:58', -19.01886587, 47.53942030, NULL, 0, 1, 1),
(834, 2, 'exit', 'rfid', '2026-01-14 17:22:20', -19.01885506, 47.53942041, NULL, 0, 1, 1),
(835, 3, 'exit', 'pin', '2026-01-14 17:33:57', -19.01885506, 47.53942041, NULL, 0, 1, 1),
(836, 3, 'entry', 'pin', '2026-01-15 08:05:17', -19.01885712, 47.53940968, NULL, 0, 1, 1),
(837, 2, 'entry', 'rfid', '2026-01-15 08:08:49', -19.01885928, 47.53941362, NULL, 0, 1, 1),
(838, 3, 'exit', 'pin', '2026-01-15 12:03:48', -19.01885998, 47.53941402, NULL, 0, 1, 1),
(839, 2, 'exit', 'rfid', '2026-01-15 12:04:32', -19.01885998, 47.53941402, NULL, 0, 1, 1),
(840, 2, 'entry', 'rfid', '2026-01-15 13:33:04', -19.01886044, 47.53941454, NULL, 0, 1, 1),
(841, 3, 'entry', 'pin', '2026-01-15 13:33:42', -19.01886044, 47.53941454, NULL, 0, 1, 1),
(842, 3, 'entry', 'pin', '2026-01-13 13:30:00', -20.92590500, 55.65733000, NULL, 0, 1, 1),
(843, 3, 'exit', 'pin', '2026-01-15 17:38:38', -19.01886089, 47.53941492, NULL, 0, 1, 1),
(844, 2, 'exit', 'rfid', '2026-01-15 18:40:33', -19.01886089, 47.53941492, NULL, 0, 1, 1),
(845, 2, 'entry', 'rfid', '2026-01-16 08:10:09', -19.01886155, 47.53941428, NULL, 0, 1, 1),
(846, 3, 'entry', 'pin', '2026-01-16 08:22:58', -19.01886155, 47.53941428, NULL, 0, 1, 1),
(847, 3, 'exit', 'pin', '2026-01-16 12:07:02', -19.01886307, 47.53941330, NULL, 0, 1, 1),
(848, 2, 'exit', 'rfid', '2026-01-16 12:07:51', -19.01886307, 47.53941330, NULL, 0, 1, 1),
(849, 3, 'entry', 'pin', '2026-01-16 13:00:44', -19.01886404, 47.53941208, NULL, 0, 1, 1),
(850, 2, 'entry', 'rfid', '2026-01-16 13:00:51', -19.01886404, 47.53941208, NULL, 0, 1, 1),
(851, 2, 'exit', 'rfid', '2026-01-16 17:04:12', -19.01886496, 47.53941116, NULL, 0, 1, 1),
(852, 3, 'exit', 'pin', '2026-01-16 17:13:27', -19.01886496, 47.53941116, NULL, 0, 1, 1),
(853, 16, 'entry', 'face', '2026-01-18 15:43:22', -20.92582060, 55.65734666, NULL, 0, 2, 7),
(854, 16, 'exit', 'rfid', '2026-01-18 16:14:00', -20.92582610, 55.65734669, NULL, 0, 2, 7),
(855, 2, 'entry', 'rfid', '2026-01-19 08:06:49', -19.01886546, 47.53941010, NULL, 0, 1, 1),
(856, 3, 'entry', 'pin', '2026-01-19 08:09:26', -19.01886546, 47.53941010, NULL, 0, 1, 1),
(857, 16, 'entry', 'rfid', '2026-01-19 09:41:17', -20.92582373, 55.65733550, NULL, 0, 2, 7),
(858, 39, 'entry', 'rfid', '2026-01-19 09:47:31', -20.92582373, 55.65733550, NULL, 0, 2, 7),
(859, 39, 'exit', 'rfid', '2026-01-19 09:48:44', -20.92583691, 55.65734779, NULL, 0, 2, 7),
(860, 3, 'exit', 'pin', '2026-01-19 12:00:57', -19.01886603, 47.53940941, NULL, 0, 1, 1),
(861, 2, 'exit', 'rfid', '2026-01-19 12:09:42', -19.01886603, 47.53940941, NULL, 0, 1, 1),
(862, 3, 'entry', 'pin', '2026-01-19 13:34:00', -20.92590500, 55.65733000, NULL, 0, 1, 1),
(863, 2, 'entry', 'rfid', '2026-01-19 13:41:06', -19.01886723, 47.53940868, NULL, 0, 1, 1),
(864, 3, 'exit', 'pin', '2026-01-19 17:41:13', -19.01887490, 47.53939780, NULL, 0, 1, 1),
(865, 2, 'exit', 'rfid', '2026-01-19 17:46:48', -19.01887490, 47.53939780, NULL, 0, 1, 1),
(866, 2, 'entry', 'rfid', '2026-01-20 08:04:30', -19.01890324, 47.53939127, NULL, 0, 1, 1),
(867, 3, 'entry', 'pin', '2026-01-20 08:20:44', -19.01890324, 47.53939127, NULL, 0, 1, 1),
(869, 39, 'entry', 'rfid', '2026-01-20 11:06:49', -20.92579378, 55.65735366, NULL, 0, 2, 7),
(870, 16, 'exit', 'rfid', '2026-01-20 11:32:31', -20.92578540, 55.65733220, NULL, 0, 2, 7),
(871, 3, 'exit', 'pin', '2026-01-20 12:05:53', -19.01887093, 47.53939970, NULL, 0, 1, 1),
(872, 2, 'exit', 'rfid', '2026-01-20 12:06:45', -19.01887264, 47.53940088, NULL, 0, 1, 1),
(873, 2, 'entry', 'rfid', '2026-01-20 13:32:05', -19.01887309, 47.53939991, NULL, 0, 1, 1),
(874, 3, 'entry', 'pin', '2026-01-20 13:35:28', -19.01887309, 47.53939991, NULL, 0, 1, 1),
(875, 3, 'exit', 'pin', '2026-01-20 17:51:27', -19.01887381, 47.53940102, NULL, 0, 1, 1),
(876, 2, 'exit', 'rfid', '2026-01-20 17:54:03', -19.01887381, 47.53940102, NULL, 0, 1, 1),
(877, 2, 'entry', 'rfid', '2026-01-21 07:53:47', -19.01888239, 47.53940655, NULL, 0, 1, 1),
(878, 3, 'entry', 'pin', '2026-01-21 08:06:54', -19.01888239, 47.53940655, NULL, 0, 1, 1),
(879, 2, 'exit', 'rfid', '2026-01-21 12:02:51', -19.01888121, 47.53940632, NULL, 0, 1, 1),
(880, 3, 'exit', 'pin', '2026-01-21 12:06:58', -19.01888121, 47.53940632, NULL, 0, 1, 1),
(881, 3, 'entry', 'face', '2026-01-21 13:37:03', -19.01888017, 47.53940609, NULL, 0, 1, 1),
(882, 2, 'entry', 'rfid', '2026-01-21 13:39:16', -19.01888017, 47.53940609, NULL, 0, 1, 1),
(883, 2, 'exit', 'rfid', '2026-01-21 17:40:04', -19.01887966, 47.53940589, NULL, 0, 1, 1),
(884, 3, 'exit', 'pin', '2026-01-21 17:53:17', -19.01887966, 47.53940589, NULL, 0, 1, 1),
(885, 3, 'entry', 'pin', '2026-01-22 08:06:24', -19.01887905, 47.53940565, NULL, 0, 1, 1),
(886, 2, 'entry', 'rfid', '2026-01-22 08:06:57', -19.01887905, 47.53940565, NULL, 0, 1, 1),
(888, 16, 'entry', 'rfid', '2026-01-22 12:35:01', -20.92579020, 55.65727930, NULL, 0, 2, 7),
(890, 3, 'exit', 'rfid', '2026-01-22 11:57:52', -19.01887740, 47.53940229, NULL, 0, 1, 1),
(892, 3, 'entry', 'pin', '2026-01-22 13:28:23', -19.01887675, 47.53940243, NULL, 0, 1, 1),
(893, 2, 'exit', 'rfid', '2026-01-22 17:06:19', -19.01887657, 47.53940255, NULL, 0, 1, 1),
(894, 3, 'exit', 'face', '2026-01-22 17:35:26', -19.01887657, 47.53940255, NULL, 0, 1, 1),
(895, 2, 'entry', 'rfid', '2026-01-23 07:56:21', -19.01887641, 47.53940268, NULL, 0, 1, 1),
(896, 3, 'entry', 'pin', '2026-01-23 08:06:59', -19.01887641, 47.53940268, NULL, 0, 1, 1),
(898, 5, 'entry', 'rfid', '2026-01-23 08:37:04', -19.01887641, 47.53940268, NULL, 0, 1, 1),
(899, 3, 'exit', 'rfid', '2026-01-23 12:03:07', -19.01887644, 47.53940278, NULL, 0, 1, 1),
(900, 2, 'exit', 'rfid', '2026-01-23 12:05:53', -19.01887639, 47.53940242, NULL, 0, 1, 1),
(901, 5, 'exit', 'rfid', '2026-01-23 12:06:10', -19.01887639, 47.53940242, NULL, 0, 1, 1),
(902, 3, 'entry', 'rfid', '2026-01-23 13:01:26', -19.01888229, 47.53940270, NULL, 0, 1, 1),
(903, 5, 'entry', 'rfid', '2026-01-23 13:03:58', -19.01888229, 47.53940270, NULL, 0, 1, 1),
(904, 2, 'entry', 'rfid', '2026-01-23 13:08:14', -19.01888229, 47.53940270, NULL, 0, 1, 1),
(905, 5, 'exit', 'rfid', '2026-01-23 16:24:51', -19.01888140, 47.53940251, NULL, 0, 1, 1),
(906, 2, 'exit', 'rfid', '2026-01-23 16:59:58', -19.01888140, 47.53940251, NULL, 0, 1, 1),
(907, 3, 'exit', 'rfid', '2026-01-23 17:00:05', -19.01888140, 47.53940251, NULL, 0, 1, 1),
(908, 2, 'entry', 'rfid', '2026-01-26 07:59:28', -19.01888191, 47.53940312, NULL, 0, 1, 1),
(909, 3, 'entry', 'pin', '2026-01-26 08:04:52', -19.01888191, 47.53940312, NULL, 0, 1, 1),
(910, 5, 'entry', 'rfid', '2026-01-26 08:12:07', -19.01888191, 47.53940312, NULL, 0, 1, 1),
(911, 3, 'exit', 'pin', '2026-01-26 12:04:11', -19.01888321, 47.53940022, NULL, 0, 1, 1),
(912, 5, 'exit', 'rfid', '2026-01-26 12:05:10', -19.01888321, 47.53940022, NULL, 0, 1, 1),
(913, 2, 'exit', 'rfid', '2026-01-26 12:05:25', -19.01888321, 47.53940022, NULL, 0, 1, 1),
(914, 5, 'entry', 'rfid', '2026-01-26 12:58:37', -19.01887039, 47.53940668, NULL, 0, 1, 1),
(915, 2, 'entry', 'rfid', '2026-01-26 13:00:45', -19.01887039, 47.53940668, NULL, 0, 1, 1),
(916, 3, 'entry', 'pin', '2026-01-26 13:32:58', -19.01887039, 47.53940668, NULL, 0, 1, 1),
(917, 5, 'exit', 'rfid', '2026-01-26 16:15:52', -19.01887026, 47.53940972, NULL, 0, 1, 1),
(918, 2, 'exit', 'rfid', '2026-01-26 17:19:10', -19.01887026, 47.53940972, NULL, 0, 1, 1),
(919, 3, 'exit', 'pin', '2026-01-26 17:52:33', -19.01887026, 47.53940972, NULL, 0, 1, 1),
(920, 2, 'entry', 'rfid', '2026-01-27 08:18:09', -19.01886757, 47.53941384, NULL, 0, 1, 1),
(921, 3, 'entry', 'pin', '2026-01-27 08:18:25', -19.01886757, 47.53941384, NULL, 0, 1, 1),
(922, 5, 'entry', 'rfid', '2026-01-27 08:31:38', -19.01886757, 47.53941384, NULL, 0, 1, 1),
(923, 3, 'exit', 'pin', '2026-01-27 12:04:45', -19.01886947, 47.53941071, NULL, 0, 1, 1),
(924, 2, 'exit', 'rfid', '2026-01-27 12:05:18', -19.01886947, 47.53941071, NULL, 0, 1, 1),
(925, 5, 'exit', 'rfid', '2026-01-27 12:05:45', -19.01886947, 47.53941071, NULL, 0, 1, 1),
(926, 5, 'entry', 'rfid', '2026-01-27 13:02:12', -19.01887021, 47.53940953, NULL, 0, 1, 1),
(927, 2, 'entry', 'rfid', '2026-01-27 13:07:43', -19.01887021, 47.53940953, NULL, 0, 1, 1),
(928, 3, 'entry', 'pin', '2026-01-27 13:33:15', -19.01887021, 47.53940953, NULL, 0, 1, 1),
(929, 5, 'exit', 'rfid', '2026-01-27 16:14:54', -19.01887586, 47.53940530, NULL, 0, 1, 1),
(930, 2, 'exit', 'rfid', '2026-01-27 17:18:04', -19.01887586, 47.53940530, NULL, 0, 1, 1),
(931, 3, 'exit', 'pin', '2026-01-27 17:32:41', -19.01887586, 47.53940530, NULL, 0, 1, 1),
(932, 2, 'entry', 'rfid', '2026-01-28 08:03:06', -19.01887626, 47.53940440, NULL, 0, 1, 1),
(933, 3, 'entry', 'pin', '2026-01-28 08:08:33', -19.01887626, 47.53940440, NULL, 0, 1, 1),
(934, 5, 'entry', 'rfid', '2026-01-28 08:28:10', -19.01887626, 47.53940440, NULL, 0, 1, 1),
(935, 3, 'exit', 'pin', '2026-01-28 12:04:38', -19.01887657, 47.53940359, NULL, 0, 1, 1),
(936, 2, 'exit', 'rfid', '2026-01-28 12:05:24', -19.01887657, 47.53940359, NULL, 0, 1, 1),
(937, 5, 'exit', 'rfid', '2026-01-28 12:09:23', -19.01887657, 47.53940359, NULL, 0, 1, 1),
(938, 5, 'entry', 'rfid', '2026-01-28 13:01:24', -19.01888665, 47.53938805, NULL, 0, 1, 1),
(939, 2, 'entry', 'rfid', '2026-01-28 13:10:50', -19.01888665, 47.53938805, NULL, 0, 1, 1),
(940, 3, 'entry', 'pin', '2026-01-28 13:35:32', -19.01888665, 47.53938805, NULL, 0, 1, 1),
(941, 5, 'exit', 'rfid', '2026-01-28 16:08:03', -19.01886934, 47.53942932, NULL, 0, 1, 1),
(942, 39, 'entry', 'face', '2026-01-28 17:36:19', -20.92572056, 55.65734832, NULL, 0, 2, 7),
(943, 16, 'entry', 'face', '2026-01-28 17:36:55', -20.92572056, 55.65734832, NULL, 0, 2, 7),
(944, 2, 'exit', 'rfid', '2026-01-28 17:23:48', -19.01886934, 47.53942932, NULL, 0, 1, 1),
(945, 3, 'exit', 'pin', '2026-01-28 17:53:46', -19.01886934, 47.53942932, NULL, 0, 1, 1),
(946, 2, 'entry', 'rfid', '2026-01-29 07:56:51', -19.01880434, 47.53951042, NULL, 0, 1, 1),
(947, 3, 'entry', 'pin', '2026-01-29 08:02:50', -19.01880434, 47.53951042, NULL, 0, 1, 1),
(948, 5, 'entry', 'rfid', '2026-01-29 08:23:21', -19.01880434, 47.53951042, NULL, 0, 1, 1),
(949, 3, 'exit', 'pin', '2026-01-29 12:04:43', -19.01883941, 47.53945659, NULL, 0, 1, 1),
(950, 5, 'exit', 'rfid', '2026-01-29 12:11:41', -19.01883941, 47.53945659, NULL, 0, 1, 1),
(951, 5, 'entry', 'rfid', '2026-01-29 13:02:34', -19.01884765, 47.53944505, NULL, 0, 1, 1),
(952, 3, 'entry', 'pin', '2026-01-29 13:30:26', -19.01884765, 47.53944505, NULL, 0, 1, 1),
(953, 39, 'entry', 'face', '2026-01-29 15:54:02', -20.92584980, 55.65735740, NULL, 0, 2, 7),
(954, 16, 'entry', 'face', '2026-01-29 15:54:28', -20.92584980, 55.65735740, NULL, 0, 2, 7),
(957, 39, 'exit', 'face', '2026-01-29 15:55:19', -20.92584532, 55.65735692, NULL, 0, 2, 7),
(958, 16, 'exit', 'rfid', '2026-01-29 15:55:35', -20.92584532, 55.65735692, NULL, 0, 2, 7),
(959, 16, 'entry', 'rfid', '2026-01-29 15:58:35', -20.92584591, 55.65735652, NULL, 0, 2, 7),
(960, 39, 'entry', 'face', '2026-01-29 15:59:08', -20.92584591, 55.65735652, NULL, 0, 2, 7),
(963, 16, 'exit', 'face', '2026-01-29 16:09:55', -20.92584090, 55.65734603, NULL, 0, 2, 7),
(964, 5, 'exit', 'rfid', '2026-01-29 16:38:50', -19.01885119, 47.53943585, NULL, 0, 1, 1),
(966, 39, 'exit', 'face', '2026-01-29 17:56:44', -20.92584090, 55.65734603, NULL, 0, 2, 7),
(967, 39, 'entry', 'face', '2026-01-29 18:05:50', -20.92588440, 55.65735830, NULL, 0, 2, 7),
(968, 16, 'entry', 'face', '2026-01-29 18:06:25', -20.92588440, 55.65735830, NULL, 0, 2, 7),
(969, 39, 'entry', 'face', '2026-01-29 18:06:56', -20.92588440, 55.65735830, NULL, 0, 2, 7),
(971, 3, 'exit', 'face', '2026-01-29 17:30:43', -19.01885119, 47.53943585, NULL, 0, 1, 1),
(972, 2, 'exit', 'rfid', '2026-01-29 18:25:02', -19.01885119, 47.53943585, NULL, 0, 1, 1),
(973, 2, 'entry', 'rfid', '2026-01-30 07:54:34', -19.01885541, 47.53942953, NULL, 0, 1, 1),
(974, 3, 'entry', 'pin', '2026-01-30 08:07:20', -19.01885916, 47.53942459, NULL, 0, 1, 1),
(975, 5, 'entry', 'rfid', '2026-01-30 08:27:03', -19.01885916, 47.53942459, NULL, 0, 1, 1),
(976, 42, 'entry', 'pin', '2026-01-30 08:34:31', -19.01886223, 47.53942048, NULL, 0, 1, 1),
(977, 2, 'exit', 'rfid', '2026-01-30 12:04:40', -19.01886428, 47.53941728, NULL, 0, 1, 1),
(978, 5, 'exit', 'rfid', '2026-01-30 12:04:53', -19.01886428, 47.53941728, NULL, 0, 1, 1),
(979, 3, 'exit', 'pin', '2026-01-30 12:05:10', -19.01886428, 47.53941728, NULL, 0, 1, 1),
(980, 42, 'exit', 'pin', '2026-01-30 12:05:57', -19.01886428, 47.53941728, NULL, 0, 1, 1),
(981, 5, 'entry', 'rfid', '2026-01-30 12:58:59', -19.01888358, 47.53938674, NULL, 0, 1, 1),
(982, 42, 'entry', 'pin', '2026-01-30 12:59:15', -19.01888358, 47.53938674, NULL, 0, 1, 1),
(983, 3, 'entry', 'pin', '2026-01-30 12:59:26', -19.01888358, 47.53938674, NULL, 0, 1, 1),
(984, 2, 'entry', 'rfid', '2026-01-30 13:02:41', -19.01888538, 47.53938821, NULL, 0, 1, 1),
(985, 5, 'exit', 'rfid', '2026-01-30 16:15:17', -19.01888346, 47.53938720, NULL, 0, 1, 1),
(986, 3, 'exit', 'pin', '2026-01-30 16:28:32', -19.01888346, 47.53938720, NULL, 0, 1, 1),
(987, 42, 'exit', 'pin', '2026-01-30 16:31:12', -19.01888346, 47.53938720, NULL, 0, 1, 1),
(988, 2, 'exit', 'rfid', '2026-01-30 16:31:25', -19.01888346, 47.53938720, NULL, 0, 1, 1),
(989, 3, 'entry', 'pin', '2026-02-02 08:14:33', -19.01888330, 47.53938770, NULL, 0, 1, 1),
(990, 2, 'entry', 'rfid', '2026-02-02 08:16:12', -19.01888330, 47.53938770, NULL, 0, 1, 1),
(991, 42, 'entry', 'pin', '2026-02-02 08:20:19', -19.01888330, 47.53938770, NULL, 0, 1, 1),
(992, 5, 'entry', 'rfid', '2026-02-02 08:23:26', -19.01888330, 47.53938770, NULL, 0, 1, 1),
(993, 1, 'entry', 'rfid', '2026-02-02 08:35:50', -19.01888330, 47.53938770, NULL, 0, 1, 1),
(999, 3, 'exit', 'rfid', '2026-02-02 12:03:15', -19.01891887, 47.53934726, NULL, 0, 1, 1),
(1001, 5, 'exit', 'rfid', '2026-02-02 12:07:18', -19.01891887, 47.53934726, NULL, 0, 1, 1),
(1002, 2, 'exit', 'rfid', '2026-02-02 12:07:32', -19.01891887, 47.53934726, NULL, 0, 1, 1),
(1003, 1, 'exit', 'rfid', '2026-02-02 12:07:45', -19.01891887, 47.53934726, NULL, 0, 1, 1),
(1004, 42, 'exit', 'rfid', '2026-02-02 12:09:47', -19.01891342, 47.53935542, NULL, 0, 1, 1),
(1005, 5, 'entry', 'rfid', '2026-02-02 12:51:47', -19.01891056, 47.53936042, NULL, 0, 1, 1),
(1006, 42, 'entry', 'rfid', '2026-02-02 12:59:46', -19.01891056, 47.53936042, NULL, 0, 1, 1),
(1007, 2, 'entry', 'rfid', '2026-02-02 13:05:59', -19.01891056, 47.53936042, NULL, 0, 1, 1),
(1008, 3, 'entry', 'pin', '2026-02-02 13:30:02', -19.01891056, 47.53936042, NULL, 0, 1, 1),
(1034, 5, 'exit', 'rfid', '2026-02-02 17:21:00', -20.92590500, 55.65733000, NULL, 0, 1, 1),
(1035, 42, 'exit', 'rfid', '2026-02-02 17:21:00', -20.92590500, 55.65733000, NULL, 0, 1, 1),
(1036, 3, 'exit', 'rfid', '2026-02-02 17:34:00', -20.92590500, 55.65733000, NULL, 0, 1, 1),
(1037, 2, 'exit', 'rfid', '2026-02-02 17:50:00', -20.92590500, 55.65733000, NULL, 0, 1, 1),
(1038, 2, 'entry', 'rfid', '2026-02-03 08:00:00', -20.92590500, 55.65733000, NULL, 0, 1, 1),
(1039, 5, 'entry', 'rfid', '2026-02-03 08:17:00', -20.92590500, 55.65733000, NULL, 0, 1, 1),
(1040, 42, 'entry', 'rfid', '2026-02-03 08:26:00', -20.92590500, 55.65733000, NULL, 0, 1, 1),
(1041, 2, 'exit', 'rfid', '2026-02-03 12:10:00', -20.92590500, 55.65733000, NULL, 0, 1, 1),
(1042, 5, 'exit', 'rfid', '2026-02-03 12:10:00', -20.92590500, 55.65733000, NULL, 0, 1, 1),
(1043, 42, 'exit', 'rfid', '2026-02-03 12:10:00', -20.92590500, 55.65733000, NULL, 0, 1, 1),
(1044, 2, 'entry', 'rfid', '2026-02-03 13:00:00', -20.92590500, 55.65733000, NULL, 0, 1, 1),
(1045, 5, 'entry', 'rfid', '2026-02-03 13:00:00', -20.92590500, 55.65733000, NULL, 0, 1, 1),
(1046, 42, 'entry', 'rfid', '2026-02-03 13:00:00', -20.92590500, 55.65733000, NULL, 0, 1, 1),
(1053, 3, 'entry', 'rfid', '2026-02-03 08:00:00', -18.90000000, 47.53000000, NULL, 0, 1, 1),
(1054, 3, 'exit', 'rfid', '2026-02-03 12:00:00', -19.01610300, 47.53638600, NULL, 0, 1, 1),
(1055, 3, 'entry', 'rfid', '2026-02-03 14:00:00', -19.01610300, 47.53638600, NULL, 0, 1, 1),
(1057, 5, 'exit', 'rfid', '2026-02-03 16:41:39', -19.01888960, 47.53937862, NULL, 0, 1, 1),
(1058, 39, 'entry', 'pin', '2026-02-03 17:02:12', -19.01888746, 47.53938629, NULL, 0, 2, 22),
(1059, 39, 'exit', 'pin', '2026-02-03 17:03:02', -19.01889029, 47.53938640, NULL, 0, 2, 22),
(1060, 2, 'exit', 'rfid', '2026-02-03 17:21:25', -19.01890884, 47.53939594, NULL, 0, 1, 1),
(1061, 42, 'exit', 'rfid', '2026-02-03 17:29:04', -19.01890884, 47.53939594, NULL, 0, 1, 1),
(1062, 3, 'exit', 'pin', '2026-02-03 17:41:21', -19.01890884, 47.53939594, NULL, 0, 1, 1),
(1063, 2, 'entry', 'rfid', '2026-02-04 08:03:13', -19.01891072, 47.53938780, NULL, 0, 1, 1),
(1064, 3, 'entry', 'pin', '2026-02-04 08:08:29', -19.01891072, 47.53938780, NULL, 0, 1, 1),
(1065, 5, 'entry', 'rfid', '2026-02-04 08:22:52', -19.01891271, 47.53938469, NULL, 0, 1, 1),
(1066, 42, 'entry', 'rfid', '2026-02-04 08:23:55', -19.01891271, 47.53938469, NULL, 0, 1, 1),
(1072, 3, 'exit', 'pin', '2026-02-04 12:05:16', -19.01888996, 47.53938281, NULL, 0, 1, 1),
(1073, 5, 'exit', 'rfid', '2026-02-04 12:06:33', -19.01888996, 47.53938281, NULL, 0, 1, 1),
(1074, 42, 'exit', 'rfid', '2026-02-04 12:08:11', -19.01888996, 47.53938281, NULL, 0, 1, 1),
(1076, 42, 'entry', 'rfid', '2026-02-04 12:35:49', -19.01889209, 47.53948282, NULL, 0, 1, 1),
(1077, 5, 'entry', 'rfid', '2026-02-04 12:51:02', -19.01889209, 47.53948282, NULL, 0, 1, 1),
(1078, 3, 'entry', 'pin', '2026-02-04 13:33:55', -19.01889209, 47.53948282, NULL, 0, 1, 1),
(1085, 5, 'exit', 'rfid', '2026-02-04 16:55:29', -19.01888114, 47.53939292, NULL, 0, 1, 1),
(1086, 42, 'exit', 'rfid', '2026-02-04 16:55:23', -19.01888114, 47.53939292, NULL, 0, 1, 1),
(1087, 2, 'exit', 'rfid', '2026-02-04 17:24:41', -19.01888321, 47.53939165, NULL, 0, 1, 1),
(1088, 3, 'exit', 'pin', '2026-02-04 17:30:17', -19.01888321, 47.53939165, NULL, 0, 1, 1),
(1089, 3, 'entry', 'pin', '2026-02-05 08:17:34', -19.01888533, 47.53938960, NULL, 0, 1, 1),
(1090, 42, 'entry', 'rfid', '2026-02-05 08:20:37', -19.01888533, 47.53938960, NULL, 0, 1, 1),
(1091, 5, 'entry', 'rfid', '2026-02-05 08:19:54', -19.01888533, 47.53938960, NULL, 0, 1, 1),
(1092, 3, 'exit', 'pin', '2026-02-05 12:04:22', -19.01883476, 47.53937612, NULL, 0, 1, 1),
(1093, 5, 'exit', 'rfid', '2026-02-05 12:07:08', -19.01883476, 47.53937612, NULL, 0, 1, 1),
(1094, 42, 'exit', 'rfid', '2026-02-05 12:07:00', -20.92590500, 55.65733000, NULL, 0, 1, 1),
(1095, 5, 'entry', 'rfid', '2026-02-05 13:00:54', -19.01894504, 47.53937417, NULL, 0, 1, 1),
(1096, 42, 'entry', 'rfid', '2026-02-05 13:01:20', -19.01894504, 47.53937417, NULL, 0, 1, 1),
(1097, 3, 'entry', 'pin', '2026-02-05 13:35:37', -19.01891388, 47.53936952, NULL, 0, 1, 1),
(1098, 42, 'exit', 'rfid', '2026-02-05 17:22:44', -19.01892768, 47.53936696, NULL, 0, 1, 1),
(1099, 5, 'exit', 'rfid', '2026-02-05 17:23:17', -19.01892768, 47.53936696, NULL, 0, 1, 1),
(1100, 3, 'exit', 'pin', '2026-02-05 17:56:49', -19.01888254, 47.53938777, NULL, 0, 1, 1),
(1101, 3, 'entry', 'pin', '2026-02-06 08:10:37', -19.01889488, 47.53939153, NULL, 0, 1, 1),
(1102, 2, 'entry', 'rfid', '2026-02-06 08:12:55', -19.01889488, 47.53939153, NULL, 0, 1, 1),
(1103, 42, 'entry', 'rfid', '2026-02-06 08:27:03', -19.01889488, 47.53939153, NULL, 0, 1, 1),
(1104, 5, 'entry', 'rfid', '2026-02-06 08:35:53', -19.01889488, 47.53939153, NULL, 0, 1, 1),
(1105, 2, 'exit', 'rfid', '2026-02-06 12:06:31', -19.01888486, 47.53939117, NULL, 0, 1, 1),
(1106, 3, 'exit', 'pin', '2026-02-06 12:06:40', -19.01888486, 47.53939117, NULL, 0, 1, 1),
(1107, 42, 'exit', 'rfid', '2026-02-06 12:09:03', -19.01888486, 47.53939117, NULL, 0, 1, 1),
(1108, 5, 'exit', 'rfid', '2026-02-06 12:09:26', -19.01888486, 47.53939117, NULL, 0, 1, 1),
(1109, 42, 'entry', 'rfid', '2026-02-06 12:54:49', -19.01889203, 47.53938598, NULL, 0, 1, 1),
(1110, 5, 'entry', 'rfid', '2026-02-06 12:54:56', -19.01889203, 47.53938598, NULL, 0, 1, 1),
(1111, 2, 'entry', 'rfid', '2026-02-06 13:00:12', -19.01889203, 47.53938598, NULL, 0, 1, 1),
(1112, 3, 'entry', 'pin', '2026-02-06 13:35:31', -19.01889203, 47.53938598, NULL, 0, 1, 1),
(1113, 42, 'exit', 'rfid', '2026-02-06 16:43:40', -19.01888769, 47.53938807, NULL, 0, 1, 1),
(1114, 2, 'exit', 'rfid', '2026-02-06 17:17:46', -19.01888659, 47.53938888, NULL, 0, 1, 1),
(1115, 5, 'exit', 'rfid', '2026-02-06 17:35:20', -19.01888659, 47.53938888, NULL, 0, 1, 1),
(1116, 3, 'exit', 'pin', '2026-02-06 17:36:06', -19.01888659, 47.53938888, NULL, 0, 1, 1),
(1117, 3, 'entry', 'pin', '2026-02-07 09:11:42', -19.01888886, 47.53938425, NULL, 0, 1, 1),
(1118, 2, 'entry', 'rfid', '2026-02-07 10:00:00', -20.92590500, 55.65733000, NULL, 0, 1, 1),
(1119, 3, 'exit', 'pin', '2026-02-07 12:33:18', -19.01888278, 47.53938932, NULL, 0, 1, 1),
(1120, 3, 'entry', 'pin', '2026-02-09 08:09:16', -19.01888604, 47.53938834, NULL, 0, 1, 1),
(1121, 2, 'entry', 'rfid', '2026-02-09 08:13:23', -19.01888604, 47.53938834, NULL, 0, 1, 1),
(1122, 2, 'exit', 'rfid', '2026-02-07 12:30:00', -20.92590500, 55.65733000, NULL, 0, 1, 1),
(1123, 5, 'entry', 'rfid', '2026-02-09 08:25:13', -19.01888604, 47.53938834, NULL, 0, 1, 1),
(1124, 42, 'entry', 'rfid', '2026-02-09 08:29:08', -19.01888604, 47.53938834, NULL, 0, 1, 1),
(1125, 3, 'exit', 'pin', '2026-02-09 12:03:02', -19.01889820, 47.53938310, NULL, 0, 1, 1),
(1126, 2, 'exit', 'rfid', '2026-02-09 12:05:14', -19.01888694, 47.53938799, NULL, 0, 1, 1),
(1127, 5, 'exit', 'rfid', '2026-02-09 12:06:05', -19.01888694, 47.53938799, NULL, 0, 1, 1),
(1128, 42, 'exit', 'rfid', '2026-02-09 12:06:28', -19.01888694, 47.53938799, NULL, 0, 1, 1),
(1130, 42, 'entry', 'rfid', '2026-02-09 12:57:54', -19.01888658, 47.53938829, NULL, 0, 1, 1),
(1131, 5, 'entry', 'rfid', '2026-02-09 12:59:35', -19.01888700, 47.53938766, NULL, 0, 1, 1),
(1132, 2, 'entry', 'rfid', '2026-02-09 13:04:16', -19.01888700, 47.53938766, NULL, 0, 1, 1),
(1133, 3, 'entry', 'pin', '2026-02-09 13:27:48', -19.01888700, 47.53938766, NULL, 0, 1, 1),
(1134, 42, 'exit', 'rfid', '2026-02-09 17:17:20', -19.01888595, 47.53938797, NULL, 0, 1, 1),
(1135, 5, 'exit', 'rfid', '2026-02-09 17:19:10', -19.01888595, 47.53938797, NULL, 0, 1, 1),
(1136, 2, 'exit', 'rfid', '2026-02-09 17:21:31', -19.01888595, 47.53938797, NULL, 0, 1, 1),
(1137, 3, 'exit', 'pin', '2026-02-09 17:33:49', -19.01888510, 47.53938873, NULL, 0, 1, 1),
(1138, 3, 'exit', 'pin', '2026-02-10 12:04:06', -19.01888472, 47.53938862, NULL, 0, 1, 1),
(1139, 5, 'entry', 'rfid', '2026-02-10 08:27:26', -19.01888571, 47.53938820, NULL, 0, 1, 1),
(1140, 2, 'entry', 'rfid', '2026-02-10 08:11:27', -19.01888571, 47.53938820, NULL, 0, 1, 1),
(1141, 42, 'entry', 'rfid', '2026-02-10 08:27:59', -19.01888571, 47.53938820, NULL, 0, 1, 1),
(1142, 3, 'entry', 'pin', '2026-02-10 08:08:47', -19.01888571, 47.53938820, NULL, 0, 1, 1),
(1143, 2, 'exit', 'rfid', '2026-02-10 12:07:24', -19.01888472, 47.53938862, NULL, 0, 1, 1),
(1144, 42, 'exit', 'rfid', '2026-02-10 12:07:45', -19.01888472, 47.53938862, NULL, 0, 1, 1),
(1145, 5, 'entry', 'rfid', '2026-02-10 12:57:43', -19.01888635, 47.53938748, NULL, 0, 1, 1),
(1146, 5, 'exit', 'rfid', '2026-02-10 12:08:15', -19.01888472, 47.53938862, NULL, 0, 1, 1),
(1147, 42, 'entry', 'rfid', '2026-02-10 12:57:54', -19.01888635, 47.53938748, NULL, 0, 1, 1),
(1148, 42, 'exit', 'rfid', '2026-02-10 16:06:40', -19.01888634, 47.53938758, NULL, 0, 1, 1),
(1149, 3, 'entry', 'pin', '2026-02-10 13:33:37', -19.01888635, 47.53938748, NULL, 0, 1, 1),
(1150, 2, 'entry', 'rfid', '2026-02-10 13:03:45', -19.01888635, 47.53938748, NULL, 0, 1, 1),
(1151, 2, 'exit', 'rfid', '2026-02-10 17:08:38', -19.01888634, 47.53938758, NULL, 0, 1, 1),
(1152, 5, 'exit', 'rfid', '2026-02-10 16:08:57', -19.01888634, 47.53938758, NULL, 0, 1, 1),
(1153, 3, 'exit', 'pin', '2026-02-10 17:46:18', -19.01888634, 47.53938758, NULL, 0, 1, 1),
(1154, 5, 'entry', 'rfid', '2026-02-11 08:30:16', -19.01888707, 47.53938738, NULL, 0, 1, 1),
(1155, 42, 'entry', 'rfid', '2026-02-11 08:30:27', -19.01888707, 47.53938738, NULL, 0, 1, 1),
(1156, 2, 'entry', 'rfid', '2026-02-11 08:50:54', -19.01888707, 47.53938738, NULL, 0, 1, 1),
(1157, 2, 'exit', 'rfid', '2026-02-11 12:09:45', -19.01888842, 47.53938636, NULL, 0, 1, 1),
(1158, 5, 'exit', 'rfid', '2026-02-11 12:13:41', -19.01888842, 47.53938636, NULL, 0, 1, 1),
(1159, 42, 'exit', 'rfid', '2026-02-11 12:13:52', -19.01888842, 47.53938636, NULL, 0, 1, 1),
(1160, 5, 'entry', 'rfid', '2026-02-11 13:01:26', -19.01888940, 47.53938683, NULL, 0, 1, 1),
(1161, 42, 'entry', 'rfid', '2026-02-11 13:01:41', -19.01888940, 47.53938683, NULL, 0, 1, 1),
(1162, 2, 'entry', 'rfid', '2026-02-11 13:01:57', -19.01888940, 47.53938683, NULL, 0, 1, 1),
(1163, 42, 'exit', 'rfid', '2026-02-11 16:41:06', -19.01889128, 47.53938609, NULL, 0, 1, 1),
(1164, 5, 'exit', 'rfid', '2026-02-11 17:02:58', -19.01889128, 47.53938609, NULL, 0, 1, 1),
(1165, 2, 'exit', 'rfid', '2026-02-11 17:52:17', -19.01889128, 47.53938609, NULL, 0, 1, 1),
(1166, 2, 'entry', 'rfid', '2026-02-12 08:04:44', -19.01889085, 47.53938626, NULL, 0, 1, 1),
(1167, 2, 'entry', 'rfid', '2026-02-12 13:10:00', -20.92590500, 55.65733000, NULL, 0, 1, 1),
(1168, 42, 'entry', 'rfid', '2026-02-12 08:28:40', -19.01889085, 47.53938626, NULL, 0, 1, 1),
(1169, 2, 'exit', 'rfid', '2026-02-12 12:06:01', -19.01889088, 47.53938607, NULL, 0, 1, 1),
(1170, 5, 'exit', 'rfid', '2026-02-12 12:07:28', -19.01889088, 47.53938607, NULL, 0, 1, 1),
(1171, 3, 'exit', 'pin', '2026-02-12 12:06:25', -19.01889088, 47.53938607, NULL, 0, 1, 1),
(1172, 5, 'entry', 'rfid', '2026-02-12 08:22:49', -19.01889085, 47.53938626, NULL, 0, 1, 1),
(1173, 2, 'exit', 'rfid', '2026-02-12 17:04:24', -19.01889292, 47.53938736, NULL, 0, 1, 1),
(1174, 3, 'entry', 'pin', '2026-02-12 13:31:50', -19.01889263, 47.53938484, NULL, 0, 1, 1),
(1176, 42, 'exit', 'rfid', '2026-02-12 12:10:18', -19.01889088, 47.53938607, NULL, 0, 1, 1),
(1177, 5, 'entry', 'rfid', '2026-02-12 12:51:23', -19.01889263, 47.53938484, NULL, 0, 1, 1),
(1180, 2, 'entry', 'rfid', '2026-02-13 08:06:07', -19.01889246, 47.53938709, NULL, 0, 1, 1),
(1182, 5, 'exit', 'rfid', '2026-02-12 17:10:54', -19.01889292, 47.53938736, NULL, 0, 1, 1),
(1183, 3, 'entry', 'pin', '2026-02-13 08:09:44', -19.01889246, 47.53938709, NULL, 0, 1, 1),
(1184, 5, 'entry', 'rfid', '2026-02-13 08:22:54', -19.01889246, 47.53938709, NULL, 0, 1, 1),
(1185, 42, 'entry', 'rfid', '2026-02-13 08:29:16', -19.01889246, 47.53938709, NULL, 0, 1, 1),
(1186, 3, 'exit', 'pin', '2026-02-13 12:08:00', -20.92590500, 55.65733000, NULL, 0, 1, 1),
(1187, 42, 'exit', 'rfid', '2026-02-13 12:08:47', -19.01889199, 47.53938696, NULL, 0, 1, 1),
(1188, 42, 'entry', 'rfid', '2026-02-13 12:56:51', -19.01889207, 47.53938696, NULL, 0, 1, 1),
(1189, 5, 'entry', 'rfid', '2026-02-13 12:58:02', -19.01889207, 47.53938696, NULL, 0, 1, 1),
(1190, 5, 'exit', 'rfid', '2026-02-13 12:09:26', -19.01889199, 47.53938696, NULL, 0, 1, 1),
(1191, 2, 'exit', 'rfid', '2026-02-13 12:08:28', -19.01889199, 47.53938696, NULL, 0, 1, 1),
(1192, 3, 'entry', 'pin', '2026-02-13 13:35:00', -20.92590500, 55.65733000, NULL, 0, 1, 1),
(1193, 42, 'exit', 'rfid', '2026-02-13 17:00:10', -19.01889136, 47.53938194, NULL, 0, 1, 1),
(1194, 2, 'entry', 'rfid', '2026-02-13 12:30:00', -20.92590500, 55.65733000, NULL, 0, 1, 1),
(1195, 2, 'exit', 'rfid', '2026-02-13 17:34:02', -19.01889136, 47.53938194, NULL, 0, 1, 1),
(1196, 5, 'exit', 'rfid', '2026-02-13 17:34:12', -19.01889136, 47.53938194, NULL, 0, 1, 1),
(1197, 3, 'exit', 'pin', '2026-02-13 17:45:26', -19.01889136, 47.53938194, NULL, 0, 1, 1),
(1198, 3, 'entry', 'pin', '2026-02-14 08:59:16', -19.01889015, 47.53938261, NULL, 0, 1, 1),
(1199, 3, 'exit', 'pin', '2026-02-14 11:34:12', -19.01889304, 47.53939072, NULL, 0, 1, 1),
(1200, 2, 'entry', 'rfid', '2026-02-16 08:04:23', -19.01888537, 47.53938663, NULL, 0, 1, 1),
(1201, 3, 'entry', 'pin', '2026-02-16 08:09:55', -19.01888139, 47.53938408, NULL, 0, 1, 1),
(1202, 5, 'entry', 'rfid', '2026-02-16 08:23:03', -19.01888139, 47.53938408, NULL, 0, 1, 1),
(1203, 42, 'entry', 'rfid', '2026-02-16 08:29:37', -19.01888139, 47.53938408, NULL, 0, 1, 1),
(1204, 3, 'exit', 'pin', '2026-02-16 12:04:08', -19.01888193, 47.53938055, NULL, 0, 1, 1),
(1205, 2, 'exit', 'rfid', '2026-02-16 12:04:32', -19.01888193, 47.53938055, NULL, 0, 1, 1),
(1206, 42, 'exit', 'rfid', '2026-02-16 12:06:55', -19.01888193, 47.53938055, NULL, 0, 1, 1),
(1207, 42, 'entry', 'rfid', '2026-02-16 12:59:12', -19.01888022, 47.53936331, NULL, 0, 1, 1),
(1208, 5, 'entry', 'rfid', '2026-02-16 12:59:22', -19.01888022, 47.53936331, NULL, 0, 1, 1),
(1209, 2, 'entry', 'rfid', '2026-02-16 13:04:01', -19.01888201, 47.53937231, NULL, 0, 1, 1),
(1210, 5, 'exit', 'rfid', '2026-02-16 12:05:00', -20.92590500, 55.65733000, NULL, 0, 1, 1),
(1211, 3, 'entry', 'pin', '2026-02-16 13:33:07', -19.01888201, 47.53937231, NULL, 0, 1, 1),
(1212, 5, 'exit', 'rfid', '2026-02-16 16:44:05', -19.01888279, 47.53937416, NULL, 0, 1, 1),
(1213, 42, 'exit', 'rfid', '2026-02-16 16:44:13', -19.01888279, 47.53937416, NULL, 0, 1, 1),
(1214, 2, 'exit', 'rfid', '2026-02-16 17:14:14', -19.01888279, 47.53937416, NULL, 0, 1, 1),
(1215, 3, 'exit', 'pin', '2026-02-16 18:06:37', -19.01888279, 47.53937416, NULL, 0, 1, 1),
(1216, 2, 'entry', 'rfid', '2026-02-17 08:06:54', -19.01888372, 47.53937521, NULL, 0, 1, 1),
(1217, 5, 'entry', 'rfid', '2026-02-17 08:27:49', -19.01888372, 47.53937521, NULL, 0, 1, 1),
(1218, 42, 'entry', 'rfid', '2026-02-17 08:27:56', -19.01888372, 47.53937521, NULL, 0, 1, 1),
(1219, 3, 'entry', 'pin', '2026-02-17 08:40:18', -19.01888372, 47.53937521, NULL, 0, 1, 1),
(1220, 3, 'exit', 'pin', '2026-02-17 12:06:22', -19.01888429, 47.53937625, NULL, 0, 1, 1),
(1221, 42, 'exit', 'rfid', '2026-02-17 12:06:55', -19.01888429, 47.53937625, NULL, 0, 1, 1),
(1222, 5, 'exit', 'rfid', '2026-02-17 12:07:13', -19.01888429, 47.53937625, NULL, 0, 1, 1),
(1223, 2, 'exit', 'rfid', '2026-02-17 12:08:52', -19.01888429, 47.53937625, NULL, 0, 1, 1),
(1224, 42, 'entry', 'rfid', '2026-02-17 12:59:46', -19.01888339, 47.53937579, NULL, 0, 1, 1),
(1225, 5, 'entry', 'rfid', '2026-02-17 13:00:18', -19.01888339, 47.53937579, NULL, 0, 1, 1),
(1226, 2, 'entry', 'rfid', '2026-02-17 13:12:54', -19.01888339, 47.53937579, NULL, 0, 1, 1),
(1228, 42, 'exit', 'rfid', '2026-02-17 16:53:09', -19.01888459, 47.53937014, NULL, 0, 1, 1),
(1229, 5, 'exit', 'rfid', '2026-02-17 17:36:23', -19.01888459, 47.53937014, NULL, 0, 1, 1),
(1230, 2, 'exit', 'rfid', '2026-02-17 18:13:56', -19.01888459, 47.53937014, NULL, 0, 1, 1),
(1231, 2, 'entry', 'rfid', '2026-02-18 08:02:17', -19.01888655, 47.53936771, NULL, 0, 1, 1),
(1232, 3, 'entry', 'pin', '2026-02-18 08:12:00', -19.01888655, 47.53936771, NULL, 0, 1, 1),
(1233, 5, 'entry', 'rfid', '2026-02-18 08:26:52', -19.01888631, 47.53937324, NULL, 0, 1, 1),
(1234, 42, 'entry', 'rfid', '2026-02-18 08:29:33', -19.01888631, 47.53937324, NULL, 0, 1, 1),
(1237, 3, 'exit', 'pin', '2026-02-18 12:05:39', -19.01889290, 47.53939020, NULL, 0, 1, 1),
(1238, 42, 'exit', 'rfid', '2026-02-18 12:06:23', -19.01889290, 47.53939020, NULL, 0, 1, 1),
(1239, 5, 'exit', 'rfid', '2026-02-18 12:07:31', -19.01889290, 47.53939020, NULL, 0, 1, 1),
(1240, 42, 'entry', 'rfid', '2026-02-18 12:49:39', -19.01889240, 47.53938927, NULL, 0, 1, 1),
(1241, 5, 'entry', 'rfid', '2026-02-18 12:50:25', -19.01889240, 47.53938927, NULL, 0, 1, 1),
(1242, 3, 'entry', 'pin', '2026-02-18 13:32:12', -19.01889239, 47.53938930, NULL, 0, 1, 1),
(1243, 42, 'exit', 'rfid', '2026-02-18 17:11:21', -19.01890326, 47.53935864, NULL, 0, 1, 1),
(1244, 5, 'exit', 'rfid', '2026-02-18 17:15:54', -19.01890326, 47.53935864, NULL, 0, 1, 1),
(1245, 3, 'exit', 'pin', '2026-02-18 17:55:13', -19.01888890, 47.53939178, NULL, 0, 1, 1);
INSERT INTO `pointages` (`ID`, `employee_id`, `type_`, `auth_method`, `timestamp_`, `latitude`, `longitude`, `photo_path`, `synced`, `SiegeID`, `company_id`) VALUES
(1246, 3, 'entry', 'pin', '2026-02-19 08:12:15', -19.01886190, 47.53940635, NULL, 0, 1, 1),
(1247, 5, 'entry', 'rfid', '2026-02-19 08:26:46', -19.01886190, 47.53940635, NULL, 0, 1, 1),
(1248, 42, 'entry', 'rfid', '2026-02-19 08:31:05', -19.01886190, 47.53940635, NULL, 0, 1, 1),
(1249, 2, 'exit', 'rfid', '2026-02-18 16:30:00', -19.01370000, 47.53690000, NULL, 0, 1, 1),
(1251, 3, 'exit', 'pin', '2026-02-19 12:04:35', -19.01889020, 47.53939200, NULL, 0, 1, 1),
(1252, 42, 'exit', 'rfid', '2026-02-19 12:08:27', -19.01888966, 47.53939150, NULL, 0, 1, 1),
(1253, 5, 'exit', 'rfid', '2026-02-19 12:08:39', -19.01888966, 47.53939150, NULL, 0, 1, 1),
(1254, 5, 'entry', 'rfid', '2026-02-19 13:02:24', -19.01888958, 47.53939138, NULL, 0, 1, 1),
(1255, 42, 'entry', 'rfid', '2026-02-19 13:02:53', -19.01888958, 47.53939138, NULL, 0, 1, 1),
(1256, 3, 'entry', 'pin', '2026-02-19 13:32:45', -19.01888958, 47.53939138, NULL, 0, 1, 1),
(1257, 42, 'exit', 'rfid', '2026-02-19 16:55:47', -19.01888650, 47.53939340, NULL, 0, 1, 1),
(1258, 5, 'exit', 'rfid', '2026-02-19 16:55:52', -19.01888650, 47.53939340, NULL, 0, 1, 1),
(1259, 3, 'exit', 'pin', '2026-02-19 17:39:07', -19.01888650, 47.53939340, NULL, 0, 1, 1),
(1260, 3, 'entry', 'pin', '2026-02-20 08:08:57', -19.01889107, 47.53941406, NULL, 0, 1, 1),
(1261, 5, 'entry', 'rfid', '2026-02-20 08:26:50', -19.01888230, 47.53938590, NULL, 0, 1, 1),
(1262, 42, 'entry', 'rfid', '2026-02-20 08:29:09', -19.01888230, 47.53938590, NULL, 0, 1, 1),
(1273, 3, 'exit', 'pin', '2026-02-20 12:06:48', -19.01888680, 47.53938926, NULL, 0, 1, 1),
(1274, 5, 'exit', 'rfid', '2026-02-20 12:07:01', -19.01888680, 47.53938926, NULL, 0, 1, 1),
(1275, 42, 'exit', 'rfid', '2026-02-20 12:07:33', -19.01888680, 47.53938926, NULL, 0, 1, 1),
(1276, 5, 'entry', 'rfid', '2026-02-20 13:00:54', -19.01888886, 47.53938996, NULL, 0, 1, 1),
(1277, 42, 'entry', 'rfid', '2026-02-20 13:02:07', -19.01888901, 47.53939042, NULL, 0, 1, 1),
(1278, 3, 'entry', 'pin', '2026-02-20 13:35:06', -19.01888901, 47.53939042, NULL, 0, 1, 1),
(1392, 42, 'exit', 'rfid', '2026-02-20 16:40:08', -19.01888896, 47.53939061, NULL, 0, 1, 1),
(1393, 5, 'exit', 'rfid', '2026-02-20 16:40:32', -19.01888896, 47.53939061, NULL, 0, 1, 1),
(1394, 3, 'exit', 'pin', '2026-02-20 17:20:50', -19.01888896, 47.53939061, NULL, 0, 1, 1),
(1402, 3, 'entry', 'pin', '2026-02-23 08:26:16', -19.01887292, 47.53935946, NULL, 0, 1, 1),
(1403, 5, 'entry', 'rfid', '2026-02-23 08:26:27', -19.01887292, 47.53935946, NULL, 0, 1, 1),
(1404, 2, 'entry', 'rfid', '2026-02-23 08:26:40', -19.01887292, 47.53935946, NULL, 0, 1, 1),
(1406, 42, 'entry', 'rfid', '2026-02-23 08:27:59', -19.01891498, 47.53930124, NULL, 0, 1, 1),
(1407, 2, 'exit', 'rfid', '2026-02-23 12:08:31', -19.01888240, 47.53939000, NULL, 0, 1, 1),
(1408, 3, 'exit', 'pin', '2026-02-23 12:08:52', -19.01888240, 47.53939000, NULL, 0, 1, 1),
(1409, 42, 'exit', 'rfid', '2026-02-23 12:09:12', -19.01888240, 47.53939000, NULL, 0, 1, 1),
(1411, 42, 'entry', 'rfid', '2026-02-23 12:47:41', -19.01885883, 47.53942468, NULL, 0, 1, 1),
(1413, 2, 'entry', 'rfid', '2026-02-23 13:05:17', -19.01885883, 47.53942468, NULL, 0, 1, 1),
(1414, 3, 'entry', 'pin', '2026-02-23 13:35:36', -19.01885883, 47.53942468, NULL, 0, 1, 1),
(1418, 1, 'entry', 'face', '2026-02-23 14:19:32', -19.01887284, 47.53940440, NULL, 0, 1, 1),
(1419, 1, 'exit', 'face', '2026-02-23 14:21:37', -19.01887443, 47.53940227, NULL, 0, 1, 1),
(1428, 2, 'exit', 'rfid', '2026-02-23 17:08:43', -19.01889615, 47.53939038, NULL, 0, 1, 1),
(1430, 42, 'exit', 'rfid', '2026-02-23 17:21:23', -19.01889606, 47.53938906, NULL, 0, 1, 1),
(1431, 3, 'exit', 'pin', '2026-02-23 17:41:38', -19.01889050, 47.53938710, NULL, 0, 1, 1),
(1432, 2, 'entry', 'rfid', '2026-02-24 08:08:21', -19.01885885, 47.53941172, NULL, 0, 1, 1),
(1433, 3, 'entry', 'pin', '2026-02-24 08:12:16', -19.01885885, 47.53941172, NULL, 0, 1, 1),
(1434, 42, 'entry', 'rfid', '2026-02-24 08:24:02', -19.01885885, 47.53941172, NULL, 0, 1, 1),
(1435, 5, 'entry', 'rfid', '2026-02-24 08:30:17', -19.01889658, 47.53937247, NULL, 0, 1, 1),
(1442, 3, 'exit', 'pin', '2026-02-24 12:06:21', -19.01888620, 47.53939140, NULL, 0, 1, 1),
(1443, 2, 'exit', 'rfid', '2026-02-24 12:07:47', -19.01888620, 47.53939140, NULL, 0, 1, 1),
(1444, 5, 'exit', 'rfid', '2026-02-24 12:08:28', -19.01888620, 47.53939140, NULL, 0, 1, 1),
(1445, 42, 'exit', 'rfid', '2026-02-24 12:08:52', -19.01888620, 47.53939140, NULL, 0, 1, 1),
(1446, 5, 'entry', 'rfid', '2026-02-24 12:55:01', -19.01890237, 47.53937230, NULL, 0, 1, 1),
(1447, 42, 'entry', 'rfid', '2026-02-24 13:00:22', -19.01890237, 47.53937230, NULL, 0, 1, 1),
(1448, 2, 'entry', 'rfid', '2026-02-24 13:02:00', -19.01890237, 47.53937230, NULL, 0, 1, 1),
(1449, 3, 'entry', 'pin', '2026-02-24 13:33:53', -19.01890237, 47.53937230, NULL, 0, 1, 1),
(1461, 42, 'exit', 'rfid', '2026-02-24 16:43:36', -19.01889115, 47.53939799, NULL, 0, 1, 1),
(1462, 5, 'exit', 'rfid', '2026-02-24 16:45:18', -19.01889115, 47.53939799, NULL, 0, 1, 1),
(1463, 2, 'exit', 'rfid', '2026-02-24 17:01:42', -19.01889270, 47.53937820, NULL, 0, 1, 1),
(1464, 3, 'exit', 'pin', '2026-02-24 18:02:51', -19.01889120, 47.53938413, NULL, 0, 1, 1),
(1465, 3, 'entry', 'pin', '2026-02-25 08:11:07', -19.01889290, 47.53938260, NULL, 0, 1, 1),
(1468, 2, 'entry', 'rfid', '2026-02-25 08:00:00', -20.92590500, 55.65733000, NULL, 0, 1, 1),
(1470, 2, 'exit', 'rfid', '2026-02-25 17:41:00', -20.92590500, 55.65733000, NULL, 0, 1, 1),
(1471, 3, 'exit', 'pin', '2026-02-25 17:56:24', -19.01904152, 47.53929453, NULL, 0, 1, 1),
(1472, 42, 'entry', 'rfid', '2026-02-26 08:28:34', -19.01899668, 47.53933977, NULL, 0, 1, 1),
(1473, 5, 'entry', 'rfid', '2026-02-26 08:29:08', -19.01899668, 47.53933977, NULL, 0, 1, 1),
(1474, 3, 'entry', 'pin', '2026-02-26 09:12:45', -19.01899668, 47.53933977, NULL, 0, 1, 1),
(1475, 5, 'exit', 'rfid', '2026-02-26 12:07:01', -19.01896594, 47.53935023, NULL, 0, 1, 1),
(1476, 3, 'exit', 'pin', '2026-02-26 12:07:22', -19.01896594, 47.53935023, NULL, 0, 1, 1),
(1477, 42, 'exit', 'rfid', '2026-02-26 12:07:44', -19.01896594, 47.53935023, NULL, 0, 1, 1),
(1500, 5, 'exit', 'rfid', '2026-02-23 12:00:00', -19.01460000, 47.53660000, NULL, 0, 1, 1),
(1501, 3, 'exit', 'pin', '2026-02-27 12:04:19', -19.01887186, 47.53936495, NULL, 0, 1, 1),
(1503, 5, 'exit', 'rfid', '2026-02-27 12:05:48', -19.01887203, 47.53936840, NULL, 0, 1, 1),
(1504, 42, 'entry', 'rfid', '2026-02-27 12:46:56', -19.01887290, 47.53937034, NULL, 0, 1, 1),
(1505, 5, 'entry', 'rfid', '2026-02-27 12:47:15', -19.01887290, 47.53937034, NULL, 0, 1, 1),
(1506, 3, 'entry', 'pin', '2026-02-27 13:35:09', -19.01887327, 47.53937175, NULL, 0, 1, 1),
(1509, 42, 'exit', 'rfid', '2026-02-27 17:25:31', -19.01887853, 47.53938296, NULL, 0, 1, 1),
(1514, 3, 'entry', 'pin', '2026-02-28 08:49:47', -19.01889040, 47.53937696, NULL, 0, 1, 1),
(1515, 3, 'exit', 'pin', '2026-02-28 11:48:25', -19.01887580, 47.53938190, NULL, 0, 1, 1),
(1521, 57, 'entry', 'rfid', '2026-03-10 06:56:00', -25.03000000, 46.99000000, NULL, 0, 1, 1),
(1522, 57, 'exit', 'rfid', '2026-03-10 08:56:00', -20.92590000, 55.65730000, NULL, 0, 1, 1),
(1523, 57, 'entry', 'rfid', '2026-03-10 09:56:00', -20.92590000, 55.65730000, NULL, 0, 1, 1),
(1524, 57, 'exit', 'rfid', '2026-03-10 10:56:00', -20.92590000, 55.65730000, NULL, 0, 1, 1);

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
('f6vl9sP26gVCp8Soi8x43a4vTHcEzFif79qN3a2D', 5, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:154.0) Gecko/20100101 Firefox/154.0', 'YTo1OntzOjY6Il90b2tlbiI7czo0MDoibmU1NVU3VVhXUUtGMzNlTEoyck4waXgwYXNMTHZySUt2WEtQUnV4TCI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NTE6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9lbXBsb3llL2xlYXZlLXJlcXVlc3RzL2NyZWF0ZSI7czo1OiJyb3V0ZSI7czoyOToiZW1wbG95ZS5sZWF2ZS1yZXF1ZXN0cy5jcmVhdGUiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX1zOjU0OiJsb2dpbl9lbXBsb3llXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6NTtzOjExOiJlbXBsb3llZV9pZCI7aTo1O30=', 1788274334),
('jC76XQ1eBQAZySR6PuKqyLbE9Mhi3b7ljj6Ki9w9', 2, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiYWdoaXVqTzU2MVB2OFdZZzJ2U214cm1KZmgwdjNFRWNVaWMzVEhrNSI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NDM6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9hZG1pbi9sZWF2ZS13b3JrZmxvd3MiO3M6NToicm91dGUiO3M6Mjc6ImFkbWluLmxlYXZlLXdvcmtmbG93cy5pbmRleCI7fXM6NTA6ImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjtpOjI7fQ==', 1788279800),
('z09SlbuQ0wpJjz7wp8uLWT99PaUhLc3a15rZy4jt', 2, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', 'YTo1OntzOjY6Il90b2tlbiI7czo0MDoiek81UHNtaDlqSUYzcXVPemc4WjZUVkJtSU9QWUJBdlZJVTN4OXk4UiI7czozOiJ1cmwiO2E6MDp7fXM6OToiX3ByZXZpb3VzIjthOjI6e3M6MzoidXJsIjtzOjU5OiJodHRwOi8vMTI3LjAuMC4xOjgwMDAvYWRtaW4vZGVwYXJ0bWVudHM/c2VhcmNoPVJIJnNpdGVfaWQ9MSI7czo1OiJyb3V0ZSI7czoyMzoiYWRtaW4uZGVwYXJ0bWVudHMuaW5kZXgiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX1zOjUwOiJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI7aToyO30=', 1788337539);

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

--
-- Déchargement des données de la table `site_leave_workflow_settings`
--

INSERT INTO `site_leave_workflow_settings` (`id`, `site_id`, `leave_workflow_id`, `name`, `description`, `steps`, `is_default`, `is_active`, `created_at`, `updated_at`) VALUES
(2, 1, 1, 'Validation standar', NULL, '[{\"order\":1,\"role\":\"director\",\"label\":\"Validation Directeur\",\"description\":\"DR\"},{\"order\":2,\"role\":\"hr\",\"label\":\"Validation RH\",\"description\":\"Le service RH approuve la demande\"},{\"order\":3,\"role\":\"manager\",\"label\":\"validation\",\"description\":\"\"}]', 0, 1, '2026-08-21 12:21:10', '2026-08-21 12:21:10');

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
  ADD KEY `job_titles_hierarchy_level_id_index` (`hierarchy_level_id`);

--
-- Index pour la table `jours_non_travailles`
--
ALTER TABLE `jours_non_travailles`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `jours_non_travailles_siegeid_foreign` (`SiegeID`),
  ADD KEY `jours_non_travailles_date_siegeid_index` (`Date`,`SiegeID`),
  ADD KEY `jours_non_travailles_actived_index` (`Actived`);

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
-- Index pour la table `leave_types`
--
ALTER TABLE `leave_types`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `leave_types_code_site_id_unique` (`code`,`site_id`),
  ADD KEY `leave_types_site_id_is_active_index` (`site_id`,`is_active`),
  ADD KEY `leave_types_deleted_at_index` (`deleted_at`);

--
-- Index pour la table `leave_type_site_activations`
--
ALTER TABLE `leave_type_site_activations`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `ltsa_site_leave_unique` (`site_id`,`leave_type_id`),
  ADD KEY `leave_type_site_activations_leave_type_id_foreign` (`leave_type_id`);

--
-- Index pour la table `leave_workflows`
--
ALTER TABLE `leave_workflows`
  ADD PRIMARY KEY (`id`),
  ADD KEY `leave_workflows_site_id_index` (`site_id`),
  ADD KEY `leave_workflows_name_index` (`name`),
  ADD KEY `leave_workflows_site_active_index` (`site_id`,`is_active`);

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
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=995;

--
-- AUTO_INCREMENT pour la table `administration`
--
ALTER TABLE `administration`
  MODIFY `ID` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT pour la table `admin_roles`
--
ALTER TABLE `admin_roles`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `company_holidays`
--
ALTER TABLE `company_holidays`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

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
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT pour la table `employee_managers`
--
ALTER TABLE `employee_managers`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `employes`
--
ALTER TABLE `employes`
  MODIFY `ID` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=66;

--
-- AUTO_INCREMENT pour la table `entreprises`
--
ALTER TABLE `entreprises`
  MODIFY `ID` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT pour la table `entreprises_sieges`
--
ALTER TABLE `entreprises_sieges`
  MODIFY `ID` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT pour la table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `hierarchy_levels`
--
ALTER TABLE `hierarchy_levels`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT pour la table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `job_titles`
--
ALTER TABLE `job_titles`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=347;

--
-- AUTO_INCREMENT pour la table `jours_non_travailles`
--
ALTER TABLE `jours_non_travailles`
  MODIFY `ID` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT pour la table `leave_balances`
--
ALTER TABLE `leave_balances`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=56;

--
-- AUTO_INCREMENT pour la table `leave_balance_transactions`
--
ALTER TABLE `leave_balance_transactions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=101;

--
-- AUTO_INCREMENT pour la table `leave_import_batches`
--
ALTER TABLE `leave_import_batches`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT pour la table `leave_periods`
--
ALTER TABLE `leave_periods`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT pour la table `leave_policies`
--
ALTER TABLE `leave_policies`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT pour la table `leave_policy_assignments`
--
ALTER TABLE `leave_policy_assignments`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT pour la table `leave_requests`
--
ALTER TABLE `leave_requests`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=57;

--
-- AUTO_INCREMENT pour la table `leave_request_attachments`
--
ALTER TABLE `leave_request_attachments`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT pour la table `leave_types`
--
ALTER TABLE `leave_types`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT pour la table `leave_type_site_activations`
--
ALTER TABLE `leave_type_site_activations`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT pour la table `leave_workflows`
--
ALTER TABLE `leave_workflows`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT pour la table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;

--
-- AUTO_INCREMENT pour la table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=83;

--
-- AUTO_INCREMENT pour la table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

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
-- Contraintes pour la table `job_titles`
--
ALTER TABLE `job_titles`
  ADD CONSTRAINT `job_titles_hierarchy_level_id_foreign` FOREIGN KEY (`hierarchy_level_id`) REFERENCES `hierarchy_levels` (`id`) ON DELETE SET NULL;

--
-- Contraintes pour la table `jours_non_travailles`
--
ALTER TABLE `jours_non_travailles`
  ADD CONSTRAINT `jours_non_travailles_siegeid_foreign` FOREIGN KEY (`SiegeID`) REFERENCES `entreprises_sieges` (`ID`) ON DELETE CASCADE;

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
-- Contraintes pour la table `leave_type_site_activations`
--
ALTER TABLE `leave_type_site_activations`
  ADD CONSTRAINT `leave_type_site_activations_leave_type_id_foreign` FOREIGN KEY (`leave_type_id`) REFERENCES `leave_types` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `leave_type_site_activations_site_id_foreign` FOREIGN KEY (`site_id`) REFERENCES `entreprises_sieges` (`ID`) ON DELETE CASCADE,
  ADD CONSTRAINT `ltsa_leave_type_id_foreign` FOREIGN KEY (`leave_type_id`) REFERENCES `leave_types` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `ltsa_site_id_foreign` FOREIGN KEY (`site_id`) REFERENCES `entreprises_sieges` (`ID`) ON DELETE CASCADE;

--
-- Contraintes pour la table `leave_workflows`
--
ALTER TABLE `leave_workflows`
  ADD CONSTRAINT `leave_workflows_site_id_foreign` FOREIGN KEY (`site_id`) REFERENCES `entreprises_sieges` (`ID`) ON DELETE CASCADE;

--
-- Contraintes pour la table `notifications`
--
ALTER TABLE `notifications`
  ADD CONSTRAINT `notifications_leave_request_id_foreign` FOREIGN KEY (`leave_request_id`) REFERENCES `leave_requests` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `notifications_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `administration` (`ID`) ON DELETE CASCADE;

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
