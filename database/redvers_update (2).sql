-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 13, 2025 at 10:01 AM
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
(1, 'redversbattery1', 'in_use', NULL, NULL, '2025-05-29 05:48:21', '2025-06-04 09:46:57'),
(2, 'redversbattery2', 'in_use', NULL, NULL, '2025-05-29 05:48:21', '2025-06-11 06:43:37'),
(3, 'redversbattery3', 'in_use', NULL, NULL, '2025-05-29 05:48:21', '2025-06-09 13:53:28'),
(4, 'redversbattery4', 'in_use', NULL, 12, '2025-05-29 05:48:21', '2025-06-12 04:42:04'),
(5, 'redversbattery5', 'in_use', NULL, NULL, '2025-05-29 05:48:21', '2025-06-05 07:39:35'),
(6, 'redversbattery6', 'in_use', NULL, NULL, '2025-05-29 06:07:52', '2025-06-02 15:21:16'),
(7, 'redversbattery7', 'in_use', NULL, NULL, '2025-06-01 15:33:15', '2025-06-05 14:03:24'),
(8, 'redversbattery8', 'in_use', NULL, NULL, '2025-06-09 14:40:39', '2025-06-12 05:18:17'),
(9, 'redversbattery9', 'charging', 1, NULL, '2025-06-11 10:08:54', '2025-06-12 05:18:17');

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
(58, 4, 76, 1, 1, '2025-06-09 14:43:06'),
(59, 8, 77, 1, 1, '2025-06-09 14:43:37'),
(68, 4, 86, 1, 1, '2025-06-12 04:42:04');

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

--
-- Dumping data for table `discounts`
--

INSERT INTO `discounts` (`id`, `purchase_id`, `amount`, `percentage`, `reason`, `created_at`, `updated_at`) VALUES
(17, 17, 3231000.00, NULL, NULL, '2025-06-12 14:33:20', '2025-06-12 14:33:20');

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
-- Table structure for table `messages`
--

CREATE TABLE `messages` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `sender_id` bigint(20) UNSIGNED NOT NULL,
  `receiver_id` bigint(20) UNSIGNED NOT NULL,
  `message` text NOT NULL,
  `is_read` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `messages`
--

INSERT INTO `messages` (`id`, `sender_id`, `receiver_id`, `message`, `is_read`, `created_at`, `updated_at`) VALUES
(1, 1, 12, 'hi', 0, '2025-06-09 10:42:31', '2025-06-09 10:42:31'),
(2, 1, 2, 'hlo', 0, '2025-06-09 10:42:53', '2025-06-09 10:42:53'),
(5, 2, 1, 'hi', 0, '2025-06-09 11:26:07', '2025-06-09 11:26:07'),
(9, 6, 12, 'hi', 0, '2025-06-09 12:00:15', '2025-06-09 12:00:15'),
(10, 6, 1, 'hi', 0, '2025-06-09 12:00:25', '2025-06-09 12:00:25'),
(11, 1, 12, 'hlo', 0, '2025-06-09 12:00:49', '2025-06-09 12:00:49'),
(12, 2, 1, 'hau', 0, '2025-06-09 12:01:12', '2025-06-09 12:01:12'),
(14, 1, 2, 'im okay', 0, '2025-06-09 13:44:37', '2025-06-09 13:44:37');

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
(38, '2025_06_03_101153_add_motorcycle_unit_id_to_swaps_table', 17),
(39, '2025_06_04_121525_add_pesapal_transaction_id_to_payments_table', 18),
(40, '2025_06_09_124110_create_messages_table', 19);

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
(2, 'retrofitted', NULL, 4500000.00, 7708571.43, 12000.00, 72000.00, 730, '2025-05-30 09:12:31', '2025-05-30 09:12:31');

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
(35, 17, '2025-06-12', 12000.00, 'daily', NULL, '2025-06-12 14:32:21', '2025-06-12 14:32:21');

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
(8, 2, 'UAZ 900', 'assigned', '2025-06-12 11:51:39', '2025-06-12 14:31:58');

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
  `pesapal_transaction_id` varchar(255) DEFAULT NULL,
  `initiated_by` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `payments`
--

INSERT INTO `payments` (`id`, `swap_id`, `amount`, `method`, `created_at`, `updated_at`, `status`, `reference`, `pesapal_transaction_id`, `initiated_by`) VALUES
(53, 77, 13350.00, 'airtel', '2025-06-09 14:43:37', '2025-06-09 14:43:37', 'pending', 'SWAP-PESAPAL-68471d4933041', NULL, 'agent'),
(60, 86, 3300.00, 'pesapal', '2025-06-12 04:42:12', '2025-06-12 04:42:12', 'pending', 'SWAP-PESAPAL-684a84cca79d3', NULL, 'agent');

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

--
-- Dumping data for table `personal_access_tokens`
--

INSERT INTO `personal_access_tokens` (`id`, `tokenable_type`, `tokenable_id`, `name`, `token`, `abilities`, `last_used_at`, `expires_at`, `created_at`, `updated_at`) VALUES
(1, 'App\\Models\\User', 1, 'FlutterWeb', 'f705c90c361ec2ea11370c5833ba82bcf0394b16f27a0121b44372a57563c1ce', '[\"*\"]', NULL, NULL, '2025-06-05 12:04:09', '2025-06-05 12:04:09'),
(2, 'App\\Models\\User', 1, 'FlutterWeb', 'be682ab69a5d2503efd2a98d8513dd2ed1fb5f9a6eb419d9ecd9a35505e0314e', '[\"*\"]', NULL, NULL, '2025-06-05 12:06:26', '2025-06-05 12:06:26');

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
(17, 12, 2, 'hire', 200000.00, 7708571.43, 212000.00, 4265571.43, 'active', '2024-08-01', '2025-06-12 14:31:58', '2025-06-12 14:33:20', 8);

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
('VT1PAayLYzF9zA0N30mvPDgjB2edJ5MgJiRJXH6c', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiUFlpOEJHaWNBU0hTczZWd1V2Qm9zYUJGN1AxRURVUXljS3RJOVBSeiI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6NDA6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9hZG1pbi9wdXJjaGFzZXMvMTciO31zOjUwOiJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI7aToxO30=', 1749749600);

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
(76, 12, 1, 2, 4, NULL, NULL, '2025-06-09 14:43:06', '2025-06-09 14:43:06', '2025-06-09 14:43:06', 100.00, 0.00, NULL),
(77, 12, 1, 2, 8, NULL, 4, '2025-06-09 14:43:37', '2025-06-09 14:43:37', '2025-06-09 14:43:37', 11.00, 13350.00, 'airtel'),
(86, 12, 1, 2, 4, NULL, 8, '2025-06-12 04:42:04', '2025-06-12 04:42:04', '2025-06-12 04:42:04', 78.00, 3300.00, 'pesapal');

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
(6, 'Waruda Head Finance', 'finance@redvers.com', NULL, '$2y$12$ZfqDUsgsQLRy6w1XmApqk.bphgvrxTcyC6DLw/JxqOZ.B4xkkG56u', 'finance', '0788329992', NULL, NULL, NULL, NULL, NULL, NULL, '2025-05-29 05:48:21', '2025-05-29 10:54:28'),
(10, 'Mr. Kizza', 'kizza@redvers.com', NULL, '$2y$12$yLY0Q10KmjDVXq1GbNaaf.zNosVYfu86CXF4JYMz3rB8P8aQsI.aq', 'agent', '0783223322', NULL, NULL, NULL, NULL, 5, NULL, '2025-06-03 06:24:03', '2025-06-03 06:24:03'),
(12, 'Malamu', 'aziz@gmail.com', NULL, '$2y$12$wRANWKoGESOJh/uijgKSAOqt5yM1uaseeBdOyVulhn0g5hDqKINsS', 'rider', 'Aziz', 'CM000UZ23JHNQ', 'riders/photos/jO6ihu7myKye6XlUJrYg2kRMc0ykJ46ZNorjB7Y2.png', 'riders/ids/front/DHcRoDsSigJQkZyGggA2JYTRkxypam4m069Lppl4.jpg', 'riders/ids/back/pH2tyvbcOJw2QPrvAHgqHvAkAhVoVa1Nk83wSosA.jpg', NULL, NULL, '2025-06-09 05:21:39', '2025-06-09 05:21:39');

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
-- Indexes for table `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `messages_sender_id_foreign` (`sender_id`),
  ADD KEY `messages_receiver_id_foreign` (`receiver_id`);

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
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `battery_swaps`
--
ALTER TABLE `battery_swaps`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=76;

--
-- AUTO_INCREMENT for table `discounts`
--
ALTER TABLE `discounts`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `follow_ups`
--
ALTER TABLE `follow_ups`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `messages`
--
ALTER TABLE `messages`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;

--
-- AUTO_INCREMENT for table `missed_payments`
--
ALTER TABLE `missed_payments`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `motorcycles`
--
ALTER TABLE `motorcycles`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `motorcycle_payments`
--
ALTER TABLE `motorcycle_payments`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT for table `motorcycle_units`
--
ALTER TABLE `motorcycle_units`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=68;

--
-- AUTO_INCREMENT for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `purchases`
--
ALTER TABLE `purchases`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `stations`
--
ALTER TABLE `stations`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `swaps`
--
ALTER TABLE `swaps`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=94;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

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
-- Constraints for table `messages`
--
ALTER TABLE `messages`
  ADD CONSTRAINT `messages_receiver_id_foreign` FOREIGN KEY (`receiver_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `messages_sender_id_foreign` FOREIGN KEY (`sender_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

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
