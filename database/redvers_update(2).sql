-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 03, 2025 at 04:08 PM
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
-- Database: `redvers_update`
--

-- --------------------------------------------------------

--
-- Table structure for table `batteries`
--

CREATE TABLE `batteries` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `serial_number` varchar(255) NOT NULL,
  `status` enum('in_stock','in_use','charging','damaged') NOT NULL DEFAULT 'in_stock',
  `current_station_id` bigint(20) UNSIGNED DEFAULT NULL,
  `current_rider_id` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `batteries`
--

INSERT INTO `batteries` (`id`, `serial_number`, `status`, `current_station_id`, `current_rider_id`, `created_at`, `updated_at`) VALUES
(1, 'redversbattery1', 'in_use', NULL, 5, '2025-05-29 05:48:21', '2025-06-02 06:38:29'),
(2, 'redversbattery2', 'in_use', NULL, 11, '2025-05-29 05:48:21', '2025-06-03 10:50:50'),
(3, 'redversbattery3', 'in_use', NULL, 4, '2025-05-29 05:48:21', '2025-06-03 10:10:10'),
(4, 'redversbattery4', 'in_use', NULL, 7, '2025-05-29 05:48:21', '2025-06-02 15:36:09'),
(5, 'redversbattery5', 'charging', 1, NULL, '2025-05-29 05:48:21', '2025-06-03 10:50:50'),
(6, 'redversbattery6', 'in_use', NULL, 4, '2025-05-29 06:07:52', '2025-06-02 15:21:16'),
(7, 'redversbattery7', 'charging', 1, NULL, '2025-06-01 15:33:15', '2025-06-02 06:38:29');

-- --------------------------------------------------------

--
-- Table structure for table `battery_swaps`
--

CREATE TABLE `battery_swaps` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `battery_id` bigint(20) UNSIGNED NOT NULL,
  `swap_id` bigint(20) UNSIGNED NOT NULL,
  `from_station_id` bigint(20) UNSIGNED DEFAULT NULL,
  `to_station_id` bigint(20) UNSIGNED DEFAULT NULL,
  `swapped_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `battery_swaps`
--

INSERT INTO `battery_swaps` (`id`, `battery_id`, `swap_id`, `from_station_id`, `to_station_id`, `swapped_at`) VALUES
(24, 5, 40, 1, 1, '2025-06-02 05:55:19'),
(25, 7, 41, 1, 1, '2025-06-02 06:06:50'),
(26, 5, 42, 1, 1, '2025-06-02 06:16:02'),
(27, 7, 43, 1, 1, '2025-06-02 06:20:56'),
(28, 5, 44, 1, 1, '2025-06-02 06:24:09'),
(29, 7, 45, 1, 1, '2025-06-02 06:38:29'),
(30, 4, 47, 2, 2, '2025-06-02 15:34:18'),
(31, 2, 48, 2, 2, '2025-06-02 15:35:45'),
(32, 4, 49, 2, 2, '2025-06-02 15:36:09'),
(33, 2, 50, 2, 2, '2025-06-02 15:36:35'),
(34, 5, 51, 2, 2, '2025-06-02 15:37:34'),
(35, 5, 52, 1, 1, '2025-06-03 10:10:10'),
(36, 2, 54, 1, 1, '2025-06-03 10:16:54'),
(37, 2, 55, 1, 1, '2025-06-03 10:50:50');

-- --------------------------------------------------------

--
-- Table structure for table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `discounts`
--

CREATE TABLE `discounts` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `purchase_id` bigint(20) UNSIGNED NOT NULL,
  `amount` decimal(12,2) DEFAULT NULL,
  `percentage` decimal(5,2) DEFAULT NULL,
  `reason` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
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
-- Table structure for table `follow_ups`
--

CREATE TABLE `follow_ups` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `purchase_id` bigint(20) UNSIGNED NOT NULL,
  `contacted_at` datetime DEFAULT NULL,
  `missed_date` date NOT NULL,
  `status` enum('pending','contacted','resolved') NOT NULL DEFAULT 'pending',
  `note` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `follow_ups`
--

INSERT INTO `follow_ups` (`id`, `purchase_id`, `contacted_at`, `missed_date`, `status`, `note`, `created_at`, `updated_at`) VALUES
(4, 6, '2025-06-02 21:17:41', '2025-06-02', 'contacted', 'Contacted by finance team', '2025-06-02 18:17:41', '2025-06-02 18:17:41');

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
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
-- Table structure for table `job_batches`
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
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2025_05_21_163000_create_stations_table', 1),
(5, '2025_05_21_163954_create_swaps_table', 1),
(6, '2025_05_21_163955_create_payments_table', 1),
(7, '2025_05_21_163956_create_notifications_table', 1),
(8, '2025_05_23_064334_add_billing_fields_to_swaps_table', 1),
(9, '2025_05_26_123955_create_personal_access_tokens_table', 1),
(10, '2025_05_27_123959_add_status_to_payments_table', 1),
(11, '2025_05_27_125232_make_email_nullable_in_users_table', 1),
(12, '2025_05_27_144555_update_swap_rider_foreign_key', 1),
(13, '2025_05_27_144756_add_reference_to_payments_table', 1),
(14, '2025_05_27_144949_add_initiated_by_to_payments_table', 1),
(15, '2025_05_27_151741_add_station_id_to_users_table', 1),
(16, '2025_05_27_154808_update_agent_foreign_key_on_swaps_table', 1),
(17, '2025_05_28_154928_create_batteries_table', 1),
(18, '2025_05_28_155050_create_battery_swaps_table', 1),
(19, '2025_05_29_082010_update_foreign_keys_on_battery_swaps_table', 1),
(20, '2025_05_29_165259_add_id_fields_to_users_table', 2),
(21, '2025_05_30_114601_create_motorcycles_table', 3),
(22, '2025_05_30_114603_create_purchases_table', 3),
(23, '2025_05_30_114604_create_motorcycle_payments_table', 3),
(25, '2025_05_30_114605_create_discounts_table', 4),
(26, '2025_05_30_152425_add_number_plate_to_motorcycles_table', 5),
(27, '2025_05_30_153049_create_motorcycle_units_table', 6),
(28, '2025_05_30_153215_create_motorcycle_units_table', 7),
(29, '2025_05_30_154023_add_motorcycle_unit_id_to_purchases_table', 8),
(30, '2025_06_01_143618_add_battery_returned_id_to_swaps_table', 9),
(31, '2025_06_02_080603_add_current_rider_id_to_batteries_table', 10),
(32, '2025_06_02_082054_add_battery_id_to_swaps_table', 11),
(33, '2025_06_02_120327_create_follow_ups_table', 12),
(34, '2025_06_02_121522_create_missed_payments_table', 13),
(35, '2025_06_02_123704_add_start_date_to_purchases_table', 14),
(36, '2025_06_02_153217_create_follow_ups_table', 15),
(37, '2025_06_03_095228_add_motorcycle_id_to_swaps_table', 16),
(38, '2025_06_03_101153_add_motorcycle_unit_id_to_swaps_table', 17);

-- --------------------------------------------------------

--
-- Table structure for table `missed_payments`
--

CREATE TABLE `missed_payments` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `purchase_id` bigint(20) UNSIGNED NOT NULL,
  `missed_date` date NOT NULL,
  `status` enum('missed','followed_up','paid_late') NOT NULL DEFAULT 'missed',
  `note` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `contacted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `missed_payments`
--

INSERT INTO `missed_payments` (`id`, `purchase_id`, `missed_date`, `status`, `note`, `created_at`, `updated_at`, `contacted_at`) VALUES
(5, 5, '2025-06-01', 'missed', NULL, '2025-06-03 10:52:09', '2025-06-03 10:52:09', NULL),
(6, 5, '2025-06-02', 'missed', NULL, '2025-06-03 10:52:09', '2025-06-03 10:52:09', NULL),
(7, 5, '2025-06-03', 'missed', NULL, '2025-06-03 10:52:09', '2025-06-03 10:52:09', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `motorcycles`
--

CREATE TABLE `motorcycles` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `type` enum('brand_new','retrofitted') NOT NULL,
  `number_plate` varchar(255) DEFAULT NULL,
  `cash_price` decimal(12,2) NOT NULL,
  `hire_price_total` decimal(12,2) NOT NULL,
  `daily_payment` decimal(12,2) NOT NULL,
  `weekly_payment` decimal(12,2) NOT NULL,
  `duration_days` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `motorcycles`
--

INSERT INTO `motorcycles` (`id`, `type`, `number_plate`, `cash_price`, `hire_price_total`, `daily_payment`, `weekly_payment`, `duration_days`, `created_at`, `updated_at`) VALUES
(1, 'brand_new', NULL, 5500000.00, 9685714.29, 15000.00, 90000.00, 730, '2025-05-30 09:12:31', '2025-05-30 09:12:31'),
(2, 'retrofitted', NULL, 4500000.00, 7708571.43, 0.00, 72000.00, 730, '2025-05-30 09:12:31', '2025-05-30 09:12:31');

-- --------------------------------------------------------

--
-- Table structure for table `motorcycle_payments`
--

CREATE TABLE `motorcycle_payments` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `purchase_id` bigint(20) UNSIGNED NOT NULL,
  `payment_date` date NOT NULL,
  `amount` decimal(12,2) NOT NULL,
  `type` enum('daily','weekly','lump_sum') NOT NULL,
  `note` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `motorcycle_payments`
--

INSERT INTO `motorcycle_payments` (`id`, `purchase_id`, `payment_date`, `amount`, `type`, `note`, `created_at`, `updated_at`) VALUES
(14, 6, '2025-06-01', 15000.00, 'daily', 'paid on sunday', '2025-06-02 18:16:19', '2025-06-02 18:16:19'),
(15, 6, '2025-05-30', 15000.00, 'daily', NULL, '2025-06-02 18:17:05', '2025-06-02 18:17:05');

-- --------------------------------------------------------

--
-- Table structure for table `motorcycle_units`
--

CREATE TABLE `motorcycle_units` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `motorcycle_id` bigint(20) UNSIGNED NOT NULL,
  `number_plate` varchar(255) NOT NULL,
  `status` enum('available','assigned','damaged') NOT NULL DEFAULT 'available',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `motorcycle_units`
--

INSERT INTO `motorcycle_units` (`id`, `motorcycle_id`, `number_plate`, `status`, `created_at`, `updated_at`) VALUES
(1, 1, 'UAZ 1920', 'assigned', '2025-05-30 13:40:37', '2025-06-02 18:13:14'),
(2, 1, 'UAZ K120', 'assigned', '2025-05-31 06:24:08', '2025-06-03 10:05:48'),
(3, 2, 'UAP I891', 'assigned', '2025-06-02 18:09:34', '2025-06-02 18:09:52'),
(4, 2, 'UGZ 212', 'assigned', '2025-06-03 08:39:11', '2025-06-03 08:39:31');

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `rider_id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `read` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `payments`
--

CREATE TABLE `payments` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `swap_id` bigint(20) UNSIGNED NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `method` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `status` enum('pending','completed','failed') NOT NULL DEFAULT 'pending',
  `reference` varchar(255) DEFAULT NULL,
  `initiated_by` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `payments`
--

INSERT INTO `payments` (`id`, `swap_id`, `amount`, `method`, `created_at`, `updated_at`, `status`, `reference`, `initiated_by`) VALUES
(30, 40, 13800.00, 'airtel', '2025-06-02 05:55:19', '2025-06-02 05:55:19', 'pending', 'SWAP-AIRTEL-683d66f7964f2', 'admin'),
(31, 41, 14100.00, 'airtel', '2025-06-02 06:06:51', '2025-06-02 06:06:51', 'pending', 'SWAP-AIRTEL-683d69ab03a03', 'admin'),
(32, 42, 13800.00, 'airtel', '2025-06-02 06:16:02', '2025-06-02 06:16:02', 'pending', 'SWAP-AIRTEL-683d6bd282f5c', 'admin'),
(33, 43, 13800.00, 'airtel', '2025-06-02 06:20:56', '2025-06-02 06:20:56', 'pending', 'SWAP-AIRTEL-683d6cf8aa2fc', 'admin'),
(34, 44, 13950.00, 'airtel', '2025-06-02 06:24:09', '2025-06-02 06:24:09', 'pending', 'SWAP-AIRTEL-683d6db9e6375', 'admin'),
(35, 45, 14100.00, 'airtel', '2025-06-02 06:38:29', '2025-06-02 06:38:29', 'pending', 'SWAP-AIRTEL-683d7115c897a', 'admin'),
(36, 48, 14850.00, 'airtel', '2025-06-02 15:35:45', '2025-06-02 15:35:45', 'pending', 'SWAP-AIRTEL-683def01377cf', 'agent'),
(37, 49, 13950.00, 'airtel', '2025-06-02 15:36:09', '2025-06-02 15:36:09', 'pending', 'SWAP-AIRTEL-683def19120ba', 'agent'),
(38, 51, 13800.00, 'mtn', '2025-06-02 15:37:34', '2025-06-02 15:37:34', 'pending', 'SWAP-MTN-683def6eaf12c', 'agent'),
(39, 52, 5100.00, 'airtel', '2025-06-03 10:10:10', '2025-06-03 10:10:10', 'pending', 'SWAP-AIRTEL-683ef432bdbc2', 'admin'),
(40, 54, 13200.00, 'airtel', '2025-06-03 10:16:54', '2025-06-03 10:16:54', 'pending', 'SWAP-AIRTEL-683ef5c693398', 'admin'),
(41, 55, 14850.00, 'airtel', '2025-06-03 10:50:50', '2025-06-03 10:50:50', 'pending', 'SWAP-AIRTEL-683efdba1c8cb', 'agent');

-- --------------------------------------------------------

--
-- Table structure for table `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tokenable_type` varchar(255) NOT NULL,
  `tokenable_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `token` varchar(64) NOT NULL,
  `abilities` text DEFAULT NULL,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `purchases`
--

CREATE TABLE `purchases` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `motorcycle_id` bigint(20) UNSIGNED NOT NULL,
  `purchase_type` enum('cash','hire') NOT NULL,
  `initial_deposit` decimal(12,2) DEFAULT NULL,
  `total_price` decimal(12,2) NOT NULL,
  `amount_paid` decimal(12,2) NOT NULL DEFAULT 0.00,
  `remaining_balance` decimal(12,2) NOT NULL,
  `status` enum('active','completed','defaulted') NOT NULL DEFAULT 'active',
  `start_date` date NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `motorcycle_unit_id` bigint(20) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `purchases`
--

INSERT INTO `purchases` (`id`, `user_id`, `motorcycle_id`, `purchase_type`, `initial_deposit`, `total_price`, `amount_paid`, `remaining_balance`, `status`, `start_date`, `created_at`, `updated_at`, `motorcycle_unit_id`) VALUES
(5, 5, 2, 'hire', 200000.00, 7708571.43, 200000.00, 7508571.43, 'active', '2025-06-01', '2025-06-02 18:09:52', '2025-06-02 18:09:52', 3),
(6, 4, 1, 'hire', 300000.00, 9685714.29, 330000.00, 9355714.29, 'active', '2025-05-30', '2025-06-02 18:13:14', '2025-06-02 18:17:05', 1),
(7, 11, 2, 'hire', 200000.00, 7708571.43, 200000.00, 7508571.43, 'active', '2025-05-29', '2025-06-03 08:39:31', '2025-06-03 08:39:31', 4),
(8, 7, 1, 'hire', 300000.00, 9685714.29, 300000.00, 9385714.29, 'active', '2025-06-03', '2025-06-03 10:05:48', '2025-06-03 10:05:48', 2);

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
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
-- Dumping data for table `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('3tLbkIBerNFTI4f8pXoZBRlyFTmwwktvv8idKSOs', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoialV2TFNiTzZxcko2QmxnZjkyVEpmb3lCWE1MZGxPZlNXdzJiTWlaVCI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mzc6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9hZG1pbi9iYXR0ZXJpZXMiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX1zOjUwOiJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI7aToxO30=', 1748958160),
('vyWZKRD6czbVUWs5XX3PRaDcS1n4372MK3lp8JUT', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:139.0) Gecko/20100101 Firefox/139.0', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiWnpoS0pUNTNvSE5XNEJmcGNvVXpYb05GVVFab3ZCVW1ZdDVhTXloWSI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MjE6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMCI7fX0=', 1748959612);

-- --------------------------------------------------------

--
-- Table structure for table `stations`
--

CREATE TABLE `stations` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `latitude` decimal(10,7) NOT NULL,
  `longitude` decimal(10,7) NOT NULL,
  `location` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `stations`
--

INSERT INTO `stations` (`id`, `name`, `latitude`, `longitude`, `location`, `created_at`, `updated_at`) VALUES
(1, 'Nalya Station', 0.3136000, 32.5811000, 'Kampala', '2025-05-29 05:48:19', '2025-05-29 05:51:03'),
(2, 'Ntinda Station', 0.3530000, 32.6123000, 'Ntinda', '2025-05-29 05:48:19', '2025-05-29 05:51:21'),
(5, 'Kyaliwajjala Station', 0.3885120, 32.6444048, NULL, '2025-06-01 18:49:18', '2025-06-01 18:49:18');

-- --------------------------------------------------------

--
-- Table structure for table `swaps`
--

CREATE TABLE `swaps` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `rider_id` bigint(20) UNSIGNED NOT NULL,
  `station_id` bigint(20) UNSIGNED NOT NULL,
  `agent_id` bigint(20) UNSIGNED DEFAULT NULL,
  `battery_id` bigint(20) UNSIGNED DEFAULT NULL,
  `motorcycle_unit_id` bigint(20) UNSIGNED DEFAULT NULL,
  `battery_returned_id` bigint(20) UNSIGNED DEFAULT NULL,
  `swapped_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `percentage_difference` decimal(5,2) DEFAULT NULL,
  `payable_amount` decimal(10,2) DEFAULT NULL,
  `payment_method` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `swaps`
--

INSERT INTO `swaps` (`id`, `rider_id`, `station_id`, `agent_id`, `battery_id`, `motorcycle_unit_id`, `battery_returned_id`, `swapped_at`, `created_at`, `updated_at`, `percentage_difference`, `payable_amount`, `payment_method`) VALUES
(39, 5, 1, 2, 5, NULL, NULL, '2025-06-02 05:50:29', '2025-06-02 05:50:29', '2025-06-02 05:50:29', 6.00, 0.00, 'mtn'),
(40, 5, 1, 2, 7, NULL, 5, '2025-06-02 05:55:19', '2025-06-02 05:55:19', '2025-06-02 05:55:19', 8.00, 13800.00, 'airtel'),
(41, 5, 1, 2, 5, NULL, 7, '2025-06-02 06:06:50', '2025-06-02 06:06:50', '2025-06-02 06:06:50', 6.00, 14100.00, 'airtel'),
(42, 5, 1, 2, 7, NULL, 5, '2025-06-02 06:16:02', '2025-06-02 06:16:02', '2025-06-02 06:16:02', 8.00, 13800.00, 'airtel'),
(43, 5, 1, 2, 5, NULL, 7, '2025-06-02 06:20:56', '2025-06-02 06:20:56', '2025-06-02 06:20:56', 8.00, 13800.00, 'airtel'),
(44, 5, 1, 2, 7, NULL, 5, '2025-06-02 06:24:09', '2025-06-02 06:24:09', '2025-06-02 06:24:09', 7.00, 13950.00, 'airtel'),
(45, 5, 1, 2, 1, NULL, 7, '2025-06-02 06:38:29', '2025-06-02 06:38:29', '2025-06-02 06:38:29', 6.00, 14100.00, 'airtel'),
(46, 4, 2, 3, 6, NULL, NULL, '2025-06-02 15:21:16', '2025-06-02 15:21:16', '2025-06-02 15:21:16', 100.00, 0.00, NULL),
(47, 7, 2, 3, 4, NULL, NULL, '2025-06-02 15:34:18', '2025-06-02 15:34:18', '2025-06-02 15:34:18', 100.00, 0.00, 'airtel'),
(48, 7, 2, 3, 2, NULL, 4, '2025-06-02 15:35:45', '2025-06-02 15:35:45', '2025-06-02 15:35:45', 1.00, 14850.00, 'airtel'),
(49, 7, 2, 3, 4, NULL, 2, '2025-06-02 15:36:09', '2025-06-02 15:36:09', '2025-06-02 15:36:09', 7.00, 13950.00, 'airtel'),
(50, 4, 2, 3, 2, NULL, NULL, '2025-06-02 15:36:35', '2025-06-02 15:36:35', '2025-06-02 15:36:35', 100.00, 0.00, NULL),
(51, 4, 2, 3, 5, NULL, 2, '2025-06-02 15:37:34', '2025-06-02 15:37:34', '2025-06-02 15:37:34', 8.00, 13800.00, 'mtn'),
(52, 4, 1, 2, 3, NULL, 5, '2025-06-03 10:10:10', '2025-06-03 10:10:10', '2025-06-03 10:10:10', 66.00, 5100.00, 'airtel'),
(53, 11, 2, 3, 2, NULL, NULL, '2025-06-03 10:12:12', '2025-06-03 10:12:12', '2025-06-03 10:12:12', 100.00, 0.00, NULL),
(54, 11, 1, 2, 5, 4, 2, '2025-06-03 10:16:54', '2025-06-03 10:16:54', '2025-06-03 10:16:54', 12.00, 13200.00, 'airtel'),
(55, 11, 1, 2, 2, 4, 5, '2025-06-03 10:50:50', '2025-06-03 10:50:50', '2025-06-03 10:50:50', 1.00, 14850.00, 'airtel');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','agent','rider','finance') NOT NULL DEFAULT 'rider',
  `phone` varchar(255) DEFAULT NULL,
  `nin_number` varchar(255) DEFAULT NULL,
  `profile_photo` varchar(255) DEFAULT NULL,
  `id_front` varchar(255) DEFAULT NULL,
  `id_back` varchar(255) DEFAULT NULL,
  `station_id` bigint(20) UNSIGNED DEFAULT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `role`, `phone`, `nin_number`, `profile_photo`, `id_front`, `id_back`, `station_id`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'Admin', 'admin@redvers.com', NULL, '$2y$12$QG4lpA1BJUjRQcX3CdkcBu3fKwM1RQ5m5u0nr2iARO1zyv2rtBovC', 'admin', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-05-29 05:48:19', '2025-05-29 05:48:19'),
(2, 'Nalya Agent Moses', 'agent1@redvers.com', NULL, '$2y$12$b719zC3JBydWl7ynh8qvX.uQbL3p53FEbN4sOoVxDaC.7HUlAz4Ky', 'agent', '078839290', NULL, NULL, NULL, NULL, 1, NULL, '2025-05-29 05:48:20', '2025-05-29 05:50:41'),
(3, 'Agent Two', 'agent2@redvers.com', NULL, '$2y$12$VlSqHqcrAtUHc0ypNrRymepzQmR0zLGX04NN2HbVP7.N/fTggBHy2', 'agent', NULL, NULL, NULL, NULL, NULL, 2, NULL, '2025-05-29 05:48:20', '2025-05-29 05:48:20'),
(4, 'Ssekitto Alex', 'rider1@redvers.com', NULL, '$2y$12$9WhqOz9asJ2tIzZP4Q700Ofjpl/TcHnIgtdMfslIWRNS5VaAzAgjO', 'rider', '0477398882', 'CM00939047837D', 'riders/photos/nJc21RWEnlvmWXNntg8KQHFPEBae3OtEwBE6XmgF.jpg', 'riders/ids/front/KVmzJJP8KeDmnnt9NWXQwkhLPLyR8s6RcLb0yBS9.jpg', 'riders/ids/back/e6P2gq8aRz3aysS6FXSHBalPVByMgHmI21dDaOcd.jpg', NULL, NULL, '2025-05-29 05:48:20', '2025-05-29 14:31:46'),
(5, 'Sserunkuma Muhaiminu', 'rider2@redvers.com', NULL, '$2y$12$ZfqDUsgsQLRy6w1XmApqk.bphgvrxTcyC6DLw/JxqOZ.B4xkkG56u', 'rider', '0788329932', NULL, NULL, NULL, NULL, NULL, NULL, '2025-05-29 05:48:21', '2025-05-29 10:54:28'),
(6, 'Waruda Head Finance', 'finance@redvers.com', NULL, '$2y$12$ZfqDUsgsQLRy6w1XmApqk.bphgvrxTcyC6DLw/JxqOZ.B4xkkG56u', 'finance', '0788329992', NULL, NULL, NULL, NULL, NULL, NULL, '2025-05-29 05:48:21', '2025-05-29 10:54:28'),
(7, 'Ssekitto Hamuza', 'ssekitto@gmail.com', NULL, '$2y$12$sNsvLQ88w1KLx1b97lDPA.TcJ8jI6Xk0b3h2hW8hO5l9HibKFVSjC', 'rider', '0788302017', NULL, NULL, NULL, NULL, NULL, NULL, '2025-05-29 14:06:55', '2025-05-29 14:06:55'),
(8, 'Waswa Robert', 'waswa@gmail.com', NULL, '$2y$12$TatSrCoQejwj8P6GoniGb.7r1m9Zs77h96bvR3XcxCuCYXPxzxMVS', 'rider', '0788811100', 'CM8993NSJNjl', 'riders/photos/CcPH0aNT1s5ttIqmFNIF5bALRCkIPS6Pk3qZBfIK.jpg', 'riders/ids/front/Pfw7oavUoDGAT4d7nWd8m9LFcPHst2b8wzuDuVj5.jpg', 'riders/ids/back/bMWiF4xKGxY2r3m2Wpz5S8xwlNBmLRz3l9hqghdx.jpg', NULL, NULL, '2025-05-29 14:13:03', '2025-06-01 17:47:42'),
(10, 'Mr. Kizza', 'kizza@redvers.com', NULL, '$2y$12$yLY0Q10KmjDVXq1GbNaaf.zNosVYfu86CXF4JYMz3rB8P8aQsI.aq', 'agent', '0783223322', NULL, NULL, NULL, NULL, 5, NULL, '2025-06-03 06:24:03', '2025-06-03 06:24:03'),
(11, 'Higeni Abdulkarim', 'habdulkarimf@gmail.com', NULL, '$2y$12$frmkFKJRUFAUBesnpvGZgefbhRzAZG6yCPAKRTQB38l810SVo4pRa', 'rider', '0707208954', 'CM000UZ23JHN', 'riders/photos/PnYe8ye8dMnE0iAuprYmi5AOLfETPGCOK8itupFa.jpg', 'riders/ids/front/nOkS3ZFeKWoBW4Dur1rkyqplEjlsk0D6IbWuNBjv.jpg', 'riders/ids/back/4bUmslBWcNv3SiPU20Yz2MaH0OGHhpJOZH4nuxyu.jpg', NULL, NULL, '2025-06-03 08:38:11', '2025-06-03 08:38:11');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `batteries`
--
ALTER TABLE `batteries`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `batteries_serial_number_unique` (`serial_number`),
  ADD KEY `batteries_current_station_id_foreign` (`current_station_id`),
  ADD KEY `batteries_current_rider_id_foreign` (`current_rider_id`);

--
-- Indexes for table `battery_swaps`
--
ALTER TABLE `battery_swaps`
  ADD PRIMARY KEY (`id`),
  ADD KEY `battery_swaps_battery_id_foreign` (`battery_id`),
  ADD KEY `battery_swaps_swap_id_foreign` (`swap_id`),
  ADD KEY `battery_swaps_from_station_id_foreign` (`from_station_id`),
  ADD KEY `battery_swaps_to_station_id_foreign` (`to_station_id`);

--
-- Indexes for table `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `discounts`
--
ALTER TABLE `discounts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `discounts_purchase_id_foreign` (`purchase_id`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `follow_ups`
--
ALTER TABLE `follow_ups`
  ADD PRIMARY KEY (`id`),
  ADD KEY `follow_ups_purchase_id_foreign` (`purchase_id`);

--
-- Indexes for table `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Indexes for table `job_batches`
--
ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `missed_payments`
--
ALTER TABLE `missed_payments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `missed_payments_purchase_id_foreign` (`purchase_id`);

--
-- Indexes for table `motorcycles`
--
ALTER TABLE `motorcycles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `motorcycles_number_plate_unique` (`number_plate`);

--
-- Indexes for table `motorcycle_payments`
--
ALTER TABLE `motorcycle_payments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `motorcycle_payments_purchase_id_foreign` (`purchase_id`);

--
-- Indexes for table `motorcycle_units`
--
ALTER TABLE `motorcycle_units`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `motorcycle_units_number_plate_unique` (`number_plate`),
  ADD KEY `motorcycle_units_motorcycle_id_foreign` (`motorcycle_id`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `notifications_rider_id_foreign` (`rider_id`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `payments_swap_id_foreign` (`swap_id`);

--
-- Indexes for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`);

--
-- Indexes for table `purchases`
--
ALTER TABLE `purchases`
  ADD PRIMARY KEY (`id`),
  ADD KEY `purchases_user_id_foreign` (`user_id`),
  ADD KEY `purchases_motorcycle_id_foreign` (`motorcycle_id`),
  ADD KEY `purchases_motorcycle_unit_id_foreign` (`motorcycle_unit_id`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `stations`
--
ALTER TABLE `stations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `swaps`
--
ALTER TABLE `swaps`
  ADD PRIMARY KEY (`id`),
  ADD KEY `swaps_station_id_foreign` (`station_id`),
  ADD KEY `swaps_rider_id_foreign` (`rider_id`),
  ADD KEY `swaps_agent_id_foreign` (`agent_id`),
  ADD KEY `swaps_battery_returned_id_foreign` (`battery_returned_id`),
  ADD KEY `swaps_battery_id_foreign` (`battery_id`),
  ADD KEY `swaps_motorcycle_unit_id_foreign` (`motorcycle_unit_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`),
  ADD KEY `users_station_id_foreign` (`station_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `batteries`
--
ALTER TABLE `batteries`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `battery_swaps`
--
ALTER TABLE `battery_swaps`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;

--
-- AUTO_INCREMENT for table `discounts`
--
ALTER TABLE `discounts`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `follow_ups`
--
ALTER TABLE `follow_ups`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;

--
-- AUTO_INCREMENT for table `missed_payments`
--
ALTER TABLE `missed_payments`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `motorcycles`
--
ALTER TABLE `motorcycles`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `motorcycle_payments`
--
ALTER TABLE `motorcycle_payments`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `motorcycle_units`
--
ALTER TABLE `motorcycle_units`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=42;

--
-- AUTO_INCREMENT for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `purchases`
--
ALTER TABLE `purchases`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `stations`
--
ALTER TABLE `stations`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `swaps`
--
ALTER TABLE `swaps`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=56;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `batteries`
--
ALTER TABLE `batteries`
  ADD CONSTRAINT `batteries_current_rider_id_foreign` FOREIGN KEY (`current_rider_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `batteries_current_station_id_foreign` FOREIGN KEY (`current_station_id`) REFERENCES `stations` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `battery_swaps`
--
ALTER TABLE `battery_swaps`
  ADD CONSTRAINT `battery_swaps_battery_id_foreign` FOREIGN KEY (`battery_id`) REFERENCES `batteries` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `battery_swaps_from_station_id_foreign` FOREIGN KEY (`from_station_id`) REFERENCES `stations` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `battery_swaps_swap_id_foreign` FOREIGN KEY (`swap_id`) REFERENCES `swaps` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `battery_swaps_to_station_id_foreign` FOREIGN KEY (`to_station_id`) REFERENCES `stations` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `discounts`
--
ALTER TABLE `discounts`
  ADD CONSTRAINT `discounts_purchase_id_foreign` FOREIGN KEY (`purchase_id`) REFERENCES `purchases` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `follow_ups`
--
ALTER TABLE `follow_ups`
  ADD CONSTRAINT `follow_ups_purchase_id_foreign` FOREIGN KEY (`purchase_id`) REFERENCES `purchases` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `missed_payments`
--
ALTER TABLE `missed_payments`
  ADD CONSTRAINT `missed_payments_purchase_id_foreign` FOREIGN KEY (`purchase_id`) REFERENCES `purchases` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `motorcycle_payments`
--
ALTER TABLE `motorcycle_payments`
  ADD CONSTRAINT `motorcycle_payments_purchase_id_foreign` FOREIGN KEY (`purchase_id`) REFERENCES `purchases` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `motorcycle_units`
--
ALTER TABLE `motorcycle_units`
  ADD CONSTRAINT `motorcycle_units_motorcycle_id_foreign` FOREIGN KEY (`motorcycle_id`) REFERENCES `motorcycles` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `notifications`
--
ALTER TABLE `notifications`
  ADD CONSTRAINT `notifications_rider_id_foreign` FOREIGN KEY (`rider_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `payments`
--
ALTER TABLE `payments`
  ADD CONSTRAINT `payments_swap_id_foreign` FOREIGN KEY (`swap_id`) REFERENCES `swaps` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `purchases`
--
ALTER TABLE `purchases`
  ADD CONSTRAINT `purchases_motorcycle_id_foreign` FOREIGN KEY (`motorcycle_id`) REFERENCES `motorcycles` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `purchases_motorcycle_unit_id_foreign` FOREIGN KEY (`motorcycle_unit_id`) REFERENCES `motorcycle_units` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `purchases_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `swaps`
--
ALTER TABLE `swaps`
  ADD CONSTRAINT `swaps_agent_id_foreign` FOREIGN KEY (`agent_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `swaps_battery_id_foreign` FOREIGN KEY (`battery_id`) REFERENCES `batteries` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `swaps_battery_returned_id_foreign` FOREIGN KEY (`battery_returned_id`) REFERENCES `batteries` (`id`),
  ADD CONSTRAINT `swaps_motorcycle_unit_id_foreign` FOREIGN KEY (`motorcycle_unit_id`) REFERENCES `motorcycle_units` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `swaps_rider_id_foreign` FOREIGN KEY (`rider_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `swaps_station_id_foreign` FOREIGN KEY (`station_id`) REFERENCES `stations` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_station_id_foreign` FOREIGN KEY (`station_id`) REFERENCES `stations` (`id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
