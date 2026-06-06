-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 06, 2026 at 02:55 PM
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
-- Database: `cmms`
--

-- --------------------------------------------------------

--
-- Table structure for table `activity_logs`
--

CREATE TABLE `activity_logs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `description` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `activity_logs`
--

INSERT INTO `activity_logs` (`id`, `description`, `created_at`, `updated_at`) VALUES
(1, 'Berhasil menambahkan cabang Gedung AA', '2026-05-06 03:12:06', '2026-05-06 03:12:06'),
(2, 'Berhasil menambahkan cabang Gedung GSG', '2026-05-06 03:12:23', '2026-05-06 03:12:23'),
(3, 'Berhasil menambahkan cabang Gedung Q', '2026-05-06 03:12:33', '2026-05-06 03:12:33'),
(4, 'Berhasil menambahkan cabang Gedung F', '2026-05-06 03:12:45', '2026-05-06 03:12:45'),
(5, 'Menambahkan user PIC Gedung GSG', '2026-05-06 03:15:15', '2026-05-06 03:15:15'),
(6, 'Menambahkan user Admin GA PNJ', '2026-05-06 03:15:55', '2026-05-06 03:15:55'),
(7, 'Menambahkan user Teknisi Kelistrikan', '2026-05-06 23:35:10', '2026-05-06 23:35:10'),
(8, 'Menambahkan user Teknisi  Sipil', '2026-05-06 23:36:52', '2026-05-06 23:36:52'),
(9, 'Menambahkan user Management', '2026-05-07 00:50:32', '2026-05-07 00:50:32'),
(10, 'Berhasil menambahkan cabang Jakarta Selatan', '2026-06-02 20:03:23', '2026-06-02 20:03:23'),
(11, 'Berhasil menambahkan cabang Bali RE', '2026-06-02 20:03:36', '2026-06-02 20:03:36'),
(12, 'Menambahkan user PIC RE Bali', '2026-06-02 20:04:24', '2026-06-02 20:04:24'),
(13, 'Menambahkan user Teknisi Kelistrikan', '2026-06-02 20:13:24', '2026-06-02 20:13:24');

-- --------------------------------------------------------

--
-- Table structure for table `assets`
--

CREATE TABLE `assets` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `company_id` bigint(20) UNSIGNED DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `specification` text DEFAULT NULL,
  `category_id` bigint(20) UNSIGNED DEFAULT NULL,
  `sub_category_id` bigint(20) UNSIGNED DEFAULT NULL,
  `quantity` int(10) UNSIGNED NOT NULL DEFAULT 1,
  `serial_number` varchar(255) DEFAULT NULL,
  `condition` enum('baik','rusak ringan','rusak berat') NOT NULL DEFAULT 'baik',
  `brand` varchar(255) DEFAULT NULL,
  `branch_id` bigint(20) UNSIGNED DEFAULT NULL,
  `value` decimal(15,2) DEFAULT NULL,
  `acquisition_year` year(4) DEFAULT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `pic_id` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `photo` varchar(255) DEFAULT NULL,
  `room_id` bigint(20) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `assets`
--

INSERT INTO `assets` (`id`, `company_id`, `name`, `specification`, `category_id`, `sub_category_id`, `quantity`, `serial_number`, `condition`, `brand`, `branch_id`, `value`, `acquisition_year`, `user_id`, `pic_id`, `created_at`, `updated_at`, `photo`, `room_id`) VALUES
(2, 1, 'Meja', NULL, 1, 1, 13, NULL, 'baik', NULL, 1, NULL, NULL, NULL, NULL, '2026-06-04 06:59:35', '2026-06-04 06:59:35', 'assets/XxhQSQ0W3svw8O3BomMAQ8YQ5I2vDJitvtZfmtym.jpg', 1);

-- --------------------------------------------------------

--
-- Table structure for table `asset_categories`
--

CREATE TABLE `asset_categories` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `asset_categories`
--

INSERT INTO `asset_categories` (`id`, `name`, `created_at`, `updated_at`) VALUES
(1, 'Furniture', '2026-05-06 22:25:39', '2026-05-06 22:25:39'),
(2, 'Elektronik', '2026-05-06 22:25:48', '2026-05-06 22:25:48');

-- --------------------------------------------------------

--
-- Table structure for table `asset_sub_categories`
--

CREATE TABLE `asset_sub_categories` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `asset_category_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `asset_sub_categories`
--

INSERT INTO `asset_sub_categories` (`id`, `asset_category_id`, `name`, `created_at`, `updated_at`) VALUES
(1, 1, 'Meja', '2026-05-06 22:25:58', '2026-05-06 22:25:58'),
(2, 1, 'Kursi', '2026-05-06 22:26:07', '2026-05-06 22:26:07'),
(3, 2, 'Smart TV', '2026-05-06 22:26:27', '2026-05-06 22:26:27');

-- --------------------------------------------------------

--
-- Table structure for table `borrowings`
--

CREATE TABLE `borrowings` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `asset_id` bigint(20) UNSIGNED NOT NULL,
  `request_branch_id` bigint(20) UNSIGNED NOT NULL,
  `source_branch_id` bigint(20) UNSIGNED DEFAULT NULL,
  `status` enum('requested','approved','picked','returned','rejected') NOT NULL DEFAULT 'requested',
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `destination_branch_id` bigint(20) UNSIGNED DEFAULT NULL,
  `destination_room_id` bigint(20) UNSIGNED DEFAULT NULL,
  `qty` int(10) NOT NULL DEFAULT 1,
  `reason` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `borrowings`
--

INSERT INTO `borrowings` (`id`, `user_id`, `asset_id`, `request_branch_id`, `source_branch_id`, `status`, `start_date`, `end_date`, `notes`, `created_at`, `updated_at`, `destination_branch_id`, `destination_room_id`, `qty`, `reason`) VALUES
(3, 3, 2, 2, 1, 'returned', '2026-06-04', '2026-06-05', NULL, '2026-06-04 07:16:56', '2026-06-04 07:17:31', 2, 4, 5, 'coba  aja');

-- --------------------------------------------------------

--
-- Table structure for table `branches`
--

CREATE TABLE `branches` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `company_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `type` varchar(255) NOT NULL DEFAULT 'branch',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `status` enum('active','inactive') NOT NULL DEFAULT 'active'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `branches`
--

INSERT INTO `branches` (`id`, `company_id`, `name`, `type`, `created_at`, `updated_at`, `status`) VALUES
(1, 1, 'Gedung AA', 'branch', '2026-05-06 03:12:06', '2026-05-06 03:12:06', 'active'),
(2, 1, 'Gedung GSG', 'branch', '2026-05-06 03:12:23', '2026-05-06 03:12:23', 'active'),
(3, 1, 'Gedung Q', 'ho', '2026-05-06 03:12:32', '2026-05-06 03:12:32', 'active'),
(4, 1, 'Gedung F', 'branch', '2026-05-06 03:12:45', '2026-05-06 03:12:45', 'active'),
(5, 2, 'Jakarta Selatan', 'ho', '2026-06-02 20:03:23', '2026-06-02 20:03:23', 'active'),
(6, 2, 'Bali RE', 'branch', '2026-06-02 20:03:36', '2026-06-02 20:03:51', 'active');

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
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `company_id` bigint(20) UNSIGNED DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `company_id`, `name`, `created_at`, `updated_at`) VALUES
(1, 1, 'Kelistrikan', '2026-05-06 03:16:31', '2026-05-06 03:16:31'),
(2, 1, 'Sipil/Bangunan', '2026-05-06 03:16:43', '2026-05-06 03:16:43');

-- --------------------------------------------------------

--
-- Table structure for table `companies`
--

CREATE TABLE `companies` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `logo` varchar(255) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `manager_signature` varchar(255) DEFAULT NULL,
  `manager_name` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `companies`
--

INSERT INTO `companies` (`id`, `name`, `logo`, `address`, `phone`, `email`, `manager_signature`, `manager_name`, `created_at`, `updated_at`) VALUES
(1, 'Politeknik Negeri Jakarta', NULL, 'jl coba-coba', '00000001', 'email@perusahaan.com', NULL, 'Admin General Affair', '2026-05-06 03:11:08', '2026-05-22 00:56:31'),
(2, 'Nusantara Group', NULL, NULL, NULL, NULL, NULL, NULL, '2026-06-02 19:56:17', '2026-06-02 19:56:17');

-- --------------------------------------------------------

--
-- Table structure for table `material_requests`
--

CREATE TABLE `material_requests` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `repair_request_id` bigint(20) UNSIGNED NOT NULL,
  `item_name` varchar(255) NOT NULL,
  `qty` int(11) NOT NULL,
  `unit` varchar(255) DEFAULT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'pending',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `material_requests`
--

INSERT INTO `material_requests` (`id`, `repair_request_id`, `item_name`, `qty`, `unit`, `status`, `created_at`, `updated_at`) VALUES
(1, 1, 'Kabel', 2, 'meter', 'approved', '2026-05-13 00:29:20', '2026-05-13 00:34:08');

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
(1, '2026_03_17_065706_create_personal_access_tokens_table', 1),
(2, '2026_03_19_173037_create_companies_table', 1),
(3, '2026_03_19_173220_create_branches_table', 1),
(4, '2026_03_19_180014_create_users_table', 1),
(5, '2026_03_25_062436_create_activity_logs_table', 1),
(6, '2026_03_27_062954_create_cache_table', 1),
(7, '2026_03_27_065352_add_type_to_branches_table', 1),
(8, '2026_04_06_235901_update_status_enum_in_users_table', 1),
(9, '2026_04_07_025016_create_requests_table', 1),
(10, '2026_04_15_080858_add_technician_id_to_repair_requests', 1),
(11, '2026_04_15_085912_add_schedule_date_to_repair_requests', 1),
(12, '2026_04_15_172251_create_material_requests_table', 1),
(13, '2026_04_17_022918_create_categories_table', 1),
(14, '2026_04_17_023213_create_sub_categories_table', 1),
(15, '2026_04_20_041839_create_assets_table', 1),
(16, '2026_04_20_043304_add_photo_to_assets_table', 1),
(17, '2026_04_20_070056_create_asset_categories_table', 1),
(18, '2026_04_20_070117_create_asset_sub_categories_table', 1),
(19, '2026_04_20_070141_update_assets_category_relation', 1),
(20, '2026_05_02_035221_add_urgency_to_repair_requests_table', 1),
(21, '2026_05_02_050536_add_category_id_to_users_table', 1),
(22, '2026_05_02_052959_add_unit_to_material_requests_table', 1),
(23, '2026_05_04_011228_create_scheduled_maintenances_table', 1),
(24, '2026_05_05_000734_add_category_id_to_repair_requests_table', 1),
(25, '2026_05_05_000836_add_photo_to_repair_requests_table', 1),
(26, '2026_05_05_000935_add_branch_id_to_repair_requests_table', 1),
(27, '2026_05_05_003322_add_company_id_to_all_tables', 1),
(28, '2026_05_05_035004_create_rooms_table', 1),
(29, '2026_05_06_095117_update_assets_replace_location_with_room', 1),
(30, '2026_05_07_053832_add_quantity_to_assets_table', 2),
(31, '2026_05_07_065636_create_borrowings_table', 3),
(32, '2026_05_07_072934_update_role_enum_on_users_table', 4),
(33, '2026_05_13_072829_add_schedule_date_to_repair_requests_table', 5),
(34, '2026_05_18_044139_create_scheduled_sub_categories_table', 6),
(35, '2026_05_18_044312_add_scheduled_sub_category_id_to_scheduled_maintenances_table', 7),
(36, '2026_05_21_063121_add_sub_category_id_to_repair_requests_table', 8),
(37, '2026_05_18_094943_add_spk_fields_to_requests_and_scheduled_maintenances', 9),
(38, '2026_05_21_070400_add_company_fields_and_create_work_orders_table', 10),
(39, '2026_05_26_091427_add_company_id_to_scheduled_maintenances_table', 11),
(40, '2026_05_26_092717_create_notifications_table', 12),
(41, '2026_05_27_051119_add_completion_fields_to_repair_requests_table', 13);

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `body` text DEFAULT NULL,
  `type` varchar(255) NOT NULL DEFAULT 'general',
  `data` text DEFAULT NULL,
  `read_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `notifications`
--

INSERT INTO `notifications` (`id`, `user_id`, `title`, `body`, `type`, `data`, `read_at`, `created_at`, `updated_at`) VALUES
(1, 5, 'SPK Diterima: AC Mati', 'Nomor SPK: SPK-REP-2026-0003. Jadwal: 2026-05-27', 'spk', '{\"work_order_id\":4}', NULL, '2026-05-26 22:20:38', '2026-05-26 22:20:38'),
(2, 10, 'SPK Diterima: AC mati', 'Nomor SPK: SPK-REP-2026-0004. Jadwal: 2026-06-03', 'spk', '{\"work_order_id\":5}', NULL, '2026-06-02 20:16:15', '2026-06-02 20:16:15'),
(3, 5, 'SPK Maintenance: Cek AC', 'Nomor SPK: SPK-SCH-2026-0002. Jadwal: 2026-06-04 00:00:00', 'spk', '{\"work_order_id\":6}', NULL, '2026-06-02 20:29:55', '2026-06-02 20:29:55');

-- --------------------------------------------------------

--
-- Table structure for table `personal_access_tokens`
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

--
-- Dumping data for table `personal_access_tokens`
--

INSERT INTO `personal_access_tokens` (`id`, `tokenable_type`, `tokenable_id`, `name`, `token`, `abilities`, `last_used_at`, `expires_at`, `created_at`, `updated_at`) VALUES
(1, 'App\\Models\\User', 1, 'auth_token', 'b55288f8d2e22f878c55347a0864b9918a10b5915157457f3e2eedc59706013b', '[\"*\"]', '2026-05-06 03:15:58', NULL, '2026-05-06 03:11:38', '2026-05-06 03:15:58'),
(2, 'App\\Models\\User', 4, 'auth_token', '10fef1a0b362dcd97da5dd944b2a5c9f34d31f4b3361732cc9a0d2ab6b8c969b', '[\"*\"]', '2026-05-06 03:24:04', NULL, '2026-05-06 03:16:16', '2026-05-06 03:24:04'),
(3, 'App\\Models\\User', 4, 'auth_token', '0bff9d3e7acba6b14f337da36dd053e5b9b4b9719e0342362bca57f65112ff1d', '[\"*\"]', '2026-05-06 23:34:27', NULL, '2026-05-06 22:15:38', '2026-05-06 23:34:27'),
(4, 'App\\Models\\User', 1, 'auth_token', '3b65d729d247dc317d232052cae94431b16fe70a0c9070ef04c3422b5007ff3b', '[\"*\"]', '2026-05-06 23:36:58', NULL, '2026-05-06 23:34:32', '2026-05-06 23:36:58'),
(5, 'App\\Models\\User', 4, 'auth_token', '5f73bfa18a9b6a22bd9ca76bb2823b30ce3973afaa92e6215d65dc73a9f8e7bd', '[\"*\"]', '2026-05-06 23:40:39', NULL, '2026-05-06 23:37:09', '2026-05-06 23:40:39'),
(6, 'App\\Models\\User', 2, 'auth_token', '40a6b4d41d5a989004cd869d631d5790327a4026f6a4d1c1dc8d42aa07370fce', '[\"*\"]', '2026-05-06 23:45:01', NULL, '2026-05-06 23:41:37', '2026-05-06 23:45:01'),
(7, 'App\\Models\\User', 4, 'auth_token', 'fa6d0bac39af8eed1ca87fe085fc9e1e0b867fc3d193878c7661001c8e5a93aa', '[\"*\"]', '2026-05-07 00:14:38', NULL, '2026-05-06 23:52:29', '2026-05-07 00:14:38'),
(8, 'App\\Models\\User', 2, 'auth_token', '696f48074ca9a26e6eac4844a5fd5e1a6e3b95df42dab0a0ce7d9d4a5be82cb8', '[\"*\"]', '2026-05-07 00:17:52', NULL, '2026-05-07 00:14:51', '2026-05-07 00:17:52'),
(9, 'App\\Models\\User', 1, 'auth_token', '8e719660d37cc70874d9b57dabf737a024d468d6093c7992f78c2ae18e66d1c4', '[\"*\"]', '2026-05-07 00:50:37', NULL, '2026-05-07 00:32:38', '2026-05-07 00:50:37'),
(10, 'App\\Models\\User', 1, 'auth_token', '266ba41a3b3ee0ad75822f78e39556d896d456edf011b0460f5a046e574653bc', '[\"*\"]', '2026-05-12 23:32:12', NULL, '2026-05-12 23:32:03', '2026-05-12 23:32:12'),
(11, 'App\\Models\\User', 4, 'auth_token', '56940f6b7da130cc183a28f944d6b7137b26a68aae43ee840bbdaed89149d325', '[\"*\"]', '2026-05-12 23:32:40', NULL, '2026-05-12 23:32:24', '2026-05-12 23:32:40'),
(12, 'App\\Models\\User', 2, 'auth_token', '559e479fee28e54b601289e1ac04157f29c3fb3bb72079c2b2360c129853bb2c', '[\"*\"]', '2026-05-12 23:50:27', NULL, '2026-05-12 23:33:08', '2026-05-12 23:50:27'),
(13, 'App\\Models\\User', 4, 'auth_token', '9678232f83d6185a23b190e19807bebc0a22847e9e3e6f331fe1c0355ce9f3b7', '[\"*\"]', '2026-05-13 00:26:15', NULL, '2026-05-12 23:50:47', '2026-05-13 00:26:15'),
(14, 'App\\Models\\User', 5, 'auth_token', '0689da9524f1fa437bac4f76bc16fcbbf1c548965b8c1c34d2b146de087dd086', '[\"*\"]', '2026-05-13 00:29:22', NULL, '2026-05-13 00:27:13', '2026-05-13 00:29:22'),
(15, 'App\\Models\\User', 4, 'auth_token', '90c689e488b066a6713e55b4a9bb62cc747edbced25d21622db2b94794f287d5', '[\"*\"]', '2026-05-13 00:34:10', NULL, '2026-05-13 00:29:30', '2026-05-13 00:34:10'),
(16, 'App\\Models\\User', 5, 'auth_token', '199bc4b25e07531a16a467b07dc9092020dd9a2bfcd9bff2ab64ba4926b9b2bf', '[\"*\"]', '2026-05-13 00:34:43', NULL, '2026-05-13 00:34:22', '2026-05-13 00:34:43'),
(17, 'App\\Models\\User', 2, 'auth_token', '0af8604869b592f589739b14228aba93875d56aafb0b160c767171d4faf2b546', '[\"*\"]', '2026-05-13 00:42:35', NULL, '2026-05-13 00:34:53', '2026-05-13 00:42:35'),
(18, 'App\\Models\\User', 2, 'auth_token', 'a93064277d9af0deda63478070f18a007a7d0f10a7d882b179e472ef36933f28', '[\"*\"]', '2026-05-17 21:31:49', NULL, '2026-05-17 21:24:44', '2026-05-17 21:31:49'),
(19, 'App\\Models\\User', 4, 'auth_token', 'c1f810e3a17a5ee60c1be76c369e942325a48e00d03567fd9e83aecc63506d5b', '[\"*\"]', '2026-05-17 22:21:15', NULL, '2026-05-17 21:32:13', '2026-05-17 22:21:15'),
(20, 'App\\Models\\User', 4, 'auth_token', 'bf01d3d6e05149cb377532b2a66641cbe44885ee9c9be568d2eebf5bb6f04610', '[\"*\"]', '2026-05-17 22:21:32', NULL, '2026-05-17 22:21:25', '2026-05-17 22:21:32'),
(21, 'App\\Models\\User', 5, 'auth_token', '3e0dfc5c01c83d59ff9b0d71ebc3f89ae3ebe930fbb1673f9612fc7bd9f546cd', '[\"*\"]', '2026-05-17 22:53:59', NULL, '2026-05-17 22:21:47', '2026-05-17 22:53:59'),
(22, 'App\\Models\\User', 2, 'auth_token', '96b37d27b289e301cd203721988a4bd517e9efe7fa95261bcf07f8d8c1a0e09d', '[\"*\"]', '2026-05-17 23:17:44', NULL, '2026-05-17 22:54:40', '2026-05-17 23:17:44'),
(23, 'App\\Models\\User', 5, 'auth_token', '9c99143c9de8882b00c74af8ca52178025f590ac83cca62c2f34bdb04f51d52e', '[\"*\"]', '2026-05-17 23:20:35', NULL, '2026-05-17 23:20:06', '2026-05-17 23:20:35'),
(24, 'App\\Models\\User', 5, 'auth_token', '875625c6c5b21719095507edebab9254de15194814d7dcf7a392c2ff2af03146', '[\"*\"]', '2026-05-17 23:20:50', NULL, '2026-05-17 23:20:40', '2026-05-17 23:20:50'),
(25, 'App\\Models\\User', 5, 'auth_token', '908a36ff4e99cd9226099e8c84b27efd16fd80f1f632e3c3d35d2601974da107', '[\"*\"]', '2026-05-18 01:50:00', NULL, '2026-05-17 23:22:09', '2026-05-18 01:50:00'),
(26, 'App\\Models\\User', 4, 'auth_token', '01d133cc9eb60cb36c11dbda1a54c3a0599f9d20244b1f25a535677b29f7d7c1', '[\"*\"]', '2026-05-18 01:53:02', NULL, '2026-05-18 01:51:45', '2026-05-18 01:53:02'),
(27, 'App\\Models\\User', 1, 'auth_token', '2c873dfe54ad624df492ebb8ae62a82f022e7e9ea588a8cc24aa8df44774951f', '[\"*\"]', '2026-05-18 01:53:53', NULL, '2026-05-18 01:53:41', '2026-05-18 01:53:53'),
(28, 'App\\Models\\User', 3, 'auth_token', 'de782d1a67724f0933380fd7777e832102763cd3e267bdf056924418db84fb5b', '[\"*\"]', '2026-05-18 01:54:56', NULL, '2026-05-18 01:54:21', '2026-05-18 01:54:56'),
(29, 'App\\Models\\User', 4, 'auth_token', '7a46ac78752a2dc1d007f598590a07273e50b1fda887a738bcabb402400f05b3', '[\"*\"]', '2026-05-18 01:55:12', NULL, '2026-05-18 01:55:04', '2026-05-18 01:55:12'),
(30, 'App\\Models\\User', 5, 'auth_token', 'cc1699ccd9704458d3a4b059dc0b99b5c33816d2cafc6555b3304dfa5d96dedf', '[\"*\"]', '2026-05-18 02:24:07', NULL, '2026-05-18 01:57:43', '2026-05-18 02:24:07'),
(31, 'App\\Models\\User', 1, 'auth_token', '4c2f5350400229fbe3cfef47a5dcfbb70c5667a1d464f1ba766cbb1e2c731032', '[\"*\"]', '2026-05-18 02:25:38', NULL, '2026-05-18 02:25:37', '2026-05-18 02:25:38'),
(32, 'App\\Models\\User', 5, 'auth_token', '7487648ea113ed944384a92fa330f286666d4d3892faf2f26bfbe959a4ad1d86', '[\"*\"]', '2026-05-18 02:28:45', NULL, '2026-05-18 02:28:44', '2026-05-18 02:28:45'),
(33, 'App\\Models\\User', 4, 'auth_token', '45c4c8772bf8d14f2204c464e5430848b9c95513a4c1e6d7073c51eceae06e93', '[\"*\"]', '2026-05-18 06:02:49', NULL, '2026-05-18 02:45:08', '2026-05-18 06:02:49'),
(34, 'App\\Models\\User', 2, 'auth_token', '48aae3aba5763d707c0e2e069decbb4b6ed86596b4229e1906c1423158f7fd66', '[\"*\"]', NULL, NULL, '2026-05-20 23:16:50', '2026-05-20 23:16:50'),
(35, 'App\\Models\\User', 2, 'auth_token', '19fe5ddd83e304b247d79fd748d7d4e50dba772e4d060a5251bace1b6da9cbc3', '[\"*\"]', '2026-05-20 23:16:59', NULL, '2026-05-20 23:16:51', '2026-05-20 23:16:59'),
(36, 'App\\Models\\User', 2, 'auth_token', 'fcc8b7495793080a2a75ddf66b32cfe1c6889f3c9eecc2cda8b012ec195b8638', '[\"*\"]', '2026-05-20 23:37:39', NULL, '2026-05-20 23:17:03', '2026-05-20 23:37:39'),
(37, 'App\\Models\\User', 4, 'auth_token', '1060f5d8f005e7005a6e799d68af0283a5368eafc641792e68abefe7eba8eec4', '[\"*\"]', '2026-05-20 23:39:24', NULL, '2026-05-20 23:37:46', '2026-05-20 23:39:24'),
(38, 'App\\Models\\User', 2, 'auth_token', 'fac57cb662c1ab9eec3ca917c1f7f67f7336ab34938aa54300d0bd2840736f98', '[\"*\"]', '2026-05-21 00:04:31', NULL, '2026-05-20 23:39:37', '2026-05-21 00:04:31'),
(39, 'App\\Models\\User', 5, 'auth_token', '67cf45e544862183eb75a95048394f12ae41b47673abc7c370730fa8214fd9ea', '[\"*\"]', '2026-05-21 00:04:43', NULL, '2026-05-21 00:04:39', '2026-05-21 00:04:43'),
(40, 'App\\Models\\User', 4, 'auth_token', 'b1ddb683bce04a6d76f9f1cad819bbfd377df46b9aba5b257f9049124610c845', '[\"*\"]', '2026-05-21 00:18:05', NULL, '2026-05-21 00:05:08', '2026-05-21 00:18:05'),
(41, 'App\\Models\\User', 5, 'auth_token', '73dd7b50e0355008414817eb6268d5dedd20d0863f4204a222c280a2c7c73dac', '[\"*\"]', '2026-05-21 00:18:25', NULL, '2026-05-21 00:18:14', '2026-05-21 00:18:25'),
(42, 'App\\Models\\User', 5, 'auth_token', '2900cda8c1f9043ef8db792578e94b306f2a3dce5bb57545a6c9ffe7bc43505d', '[\"*\"]', '2026-05-21 00:19:50', NULL, '2026-05-21 00:19:00', '2026-05-21 00:19:50'),
(43, 'App\\Models\\User', 5, 'auth_token', 'c7e8dbc257ea2960b6eaf1cfbe524bd335ddadb800a1d8fd544b831b389e0c13', '[\"*\"]', '2026-05-21 00:20:27', NULL, '2026-05-21 00:19:59', '2026-05-21 00:20:27'),
(44, 'App\\Models\\User', 5, 'auth_token', '59486b6ea15aea3468c9be73ca71d7133239ab636000d2ec6497a4c162ca29da', '[\"*\"]', '2026-05-21 00:24:15', NULL, '2026-05-21 00:24:01', '2026-05-21 00:24:15'),
(45, 'App\\Models\\User', 4, 'auth_token', 'db5fe038ff876fc958c3ac1a240c32a97ae158bc5f3db1e17ef662274f4f65a2', '[\"*\"]', '2026-05-21 00:24:44', NULL, '2026-05-21 00:24:32', '2026-05-21 00:24:44'),
(46, 'App\\Models\\User', 5, 'auth_token', '6a7371b21d36341fd8b5a7ee54137f9648c8bb266d15a1683f7ddc1da9981479', '[\"*\"]', '2026-05-21 00:24:56', NULL, '2026-05-21 00:24:49', '2026-05-21 00:24:56'),
(47, 'App\\Models\\User', 5, 'auth_token', '0145748fe4bee6da9cdab8d208bb1af9396905afc2267aab1e5eab28a60dc6bd', '[\"*\"]', '2026-05-21 00:25:44', NULL, '2026-05-21 00:25:02', '2026-05-21 00:25:44'),
(48, 'App\\Models\\User', 4, 'auth_token', '0c9e93f06b58160470baea9539f429f3eb33dc98f1955be401f0750fb834f7fc', '[\"*\"]', '2026-05-21 00:34:33', NULL, '2026-05-21 00:27:51', '2026-05-21 00:34:33'),
(49, 'App\\Models\\User', 5, 'auth_token', '68ed3191a49751ad7693c7f428078ac383f1e621afeb0ad2287779338f4aefe9', '[\"*\"]', '2026-05-21 00:50:37', NULL, '2026-05-21 00:34:39', '2026-05-21 00:50:37'),
(50, 'App\\Models\\User', 4, 'auth_token', 'e586108eeb0d9646cda4b50eb596930fc2365b98c0ee331d1e0b4bfa96c72bab', '[\"*\"]', '2026-05-21 00:50:57', NULL, '2026-05-21 00:50:43', '2026-05-21 00:50:57'),
(51, 'App\\Models\\User', 4, 'auth_token', '3cb0649c8ed8751dcaccde35eb2a235a6578752ad235fbf1d55a36041f92f509', '[\"*\"]', '2026-05-21 23:51:20', NULL, '2026-05-21 22:41:44', '2026-05-21 23:51:20'),
(52, 'App\\Models\\User', 4, 'auth_token', 'd695145415dd4026e9e75e0fc53e9b65c5d63ac72d790a2d3868e3f474859104', '[\"*\"]', '2026-05-22 00:13:54', NULL, '2026-05-22 00:13:52', '2026-05-22 00:13:54'),
(53, 'App\\Models\\User', 4, 'auth_token', 'c51a88448fc43de2c133818853a668f4fe01ddf2f8c0b36f8262e5b340e27401', '[\"*\"]', '2026-05-22 00:37:53', NULL, '2026-05-22 00:15:10', '2026-05-22 00:37:53'),
(54, 'App\\Models\\User', 4, 'auth_token', '9098f3a6144d6d7a0cd11291d38e41a04dd3238bad757fae25495d801a19ba12', '[\"*\"]', '2026-05-22 04:12:10', NULL, '2026-05-22 00:43:02', '2026-05-22 04:12:10'),
(55, 'App\\Models\\User', 4, 'auth_token', 'beabd244804422505b0f8f4866acc432b2eea161955c9301f08d4212e2355227', '[\"*\"]', '2026-05-23 00:41:46', NULL, '2026-05-23 00:34:16', '2026-05-23 00:41:46'),
(56, 'App\\Models\\User', 5, 'auth_token', 'f29f358e013976d0e5d849168d47538d89ad51f179e27f5f30595819440a625c', '[\"*\"]', '2026-05-23 00:49:09', NULL, '2026-05-23 00:42:52', '2026-05-23 00:49:09'),
(57, 'App\\Models\\User', 4, 'auth_token', '3abecb1ad726f2429846da75558231580101ca9d8db740788246a440cff98473', '[\"*\"]', '2026-05-23 05:31:09', NULL, '2026-05-23 00:50:39', '2026-05-23 05:31:09'),
(58, 'App\\Models\\User', 5, 'auth_token', 'c2422d4f01f3fcd24ef8239c9ab252824662c1af0c0cb15f7c41b7ff220e2c87', '[\"*\"]', '2026-05-23 05:36:04', NULL, '2026-05-23 05:35:58', '2026-05-23 05:36:04'),
(59, 'App\\Models\\User', 4, 'auth_token', '5d59405673735af2a9b7418a4873886a96f0d6e3002a277a76156856592aa8eb', '[\"*\"]', '2026-05-23 07:35:50', NULL, '2026-05-23 07:25:43', '2026-05-23 07:35:50'),
(60, 'App\\Models\\User', 4, 'auth_token', 'ceaa43d88700f84ae08d36dd53ce23c0c7b28fe84c46c5fcfe86386d47524185', '[\"*\"]', '2026-05-26 03:28:32', NULL, '2026-05-26 01:39:27', '2026-05-26 03:28:32'),
(61, 'App\\Models\\User', 5, 'auth_token', 'cf2f24df07632b16b8be6487020e1e363807ca5e2c840ce7ceb1a42d38b01a58', '[\"*\"]', '2026-05-26 03:45:10', NULL, '2026-05-26 03:44:43', '2026-05-26 03:45:10'),
(62, 'App\\Models\\User', 4, 'auth_token', '2c7b58dbc4ef968ce03d7abb8cac43c25a3c61795bc885ef7ab77ce1a7b82c9d', '[\"*\"]', '2026-05-26 06:57:01', NULL, '2026-05-26 03:45:23', '2026-05-26 06:57:01'),
(63, 'App\\Models\\User', 2, 'auth_token', 'f093ab8e95cc29a209e6f84aa93db528a5889d633d8c9e2480cda1cb6d976970', '[\"*\"]', '2026-05-26 07:18:40', NULL, '2026-05-26 06:57:16', '2026-05-26 07:18:40'),
(64, 'App\\Models\\User', 4, 'auth_token', 'bb28c3523c2893679b2886d69e0a90e685ae6fde2ac60eecc41cd8a23a788fbd', '[\"*\"]', '2026-05-26 07:21:05', NULL, '2026-05-26 07:18:54', '2026-05-26 07:21:05'),
(65, 'App\\Models\\User', 2, 'auth_token', 'e1cb904b2bfbca84654082adae0e62dbb30eb133e51985e27babebca4f157605', '[\"*\"]', '2026-05-26 22:19:05', NULL, '2026-05-26 22:11:58', '2026-05-26 22:19:05'),
(66, 'App\\Models\\User', 4, 'auth_token', '62c02ed4a8ea2343feeb6f4e3e67cce5a84a4dc08d75524dedc1a2a33e589eab', '[\"*\"]', '2026-05-26 22:19:31', NULL, '2026-05-26 22:19:15', '2026-05-26 22:19:31'),
(67, 'App\\Models\\User', 2, 'auth_token', 'e196223a34bb8eb55a7006d08069978925cee3e43b509755aac49fb934187a90', '[\"*\"]', '2026-05-26 22:19:46', NULL, '2026-05-26 22:19:44', '2026-05-26 22:19:46'),
(68, 'App\\Models\\User', 5, 'auth_token', '372787be4d6df3f0d538e0d9ec76a69149120922bb6cf0834286db5cc7551a02', '[\"*\"]', '2026-05-26 22:20:09', NULL, '2026-05-26 22:19:56', '2026-05-26 22:20:09'),
(69, 'App\\Models\\User', 2, 'auth_token', '2908ca5afab1a346395cb5eea8278f3115c69fe22c2b302dda4ff1ed734b3c8d', '[\"*\"]', '2026-05-26 22:20:22', NULL, '2026-05-26 22:20:18', '2026-05-26 22:20:22'),
(70, 'App\\Models\\User', 4, 'auth_token', '9f35cbc90512817a6193d3a8f1201b6676a74ca0eb63286110f29e9f8df147a5', '[\"*\"]', '2026-05-26 22:37:55', NULL, '2026-05-26 22:20:30', '2026-05-26 22:37:55'),
(71, 'App\\Models\\User', 4, 'auth_token', 'a6403633551fdddcf2d4fe6500da57075c073d6ee8b52c09bd2d3cd3ba88ece6', '[\"*\"]', '2026-05-26 22:40:46', NULL, '2026-05-26 22:37:59', '2026-05-26 22:40:46'),
(72, 'App\\Models\\User', 4, 'auth_token', '5d3987765f4e6a8f7525d8c9cd9857bb04b61d3c460ee475c30b0ee429045ffb', '[\"*\"]', '2026-05-26 22:41:17', NULL, '2026-05-26 22:41:16', '2026-05-26 22:41:17'),
(73, 'App\\Models\\User', 5, 'auth_token', '362c413d3b9692c821703bbedc5c8670460fbe94e52d09037b4764f54563fb54', '[\"*\"]', '2026-05-26 22:44:48', NULL, '2026-05-26 22:41:21', '2026-05-26 22:44:48'),
(74, 'App\\Models\\User', 4, 'auth_token', '44f8ddbb655b6e2264aaa754aad4b189e40ef6fc9b96fc9e0e97f13ca50e508c', '[\"*\"]', '2026-05-26 22:45:01', NULL, '2026-05-26 22:45:00', '2026-05-26 22:45:01'),
(75, 'App\\Models\\User', 2, 'auth_token', 'c05f71548597f9dfb3d6b8ef63a5f9369a663d2f98c742629de82e0ffff8ee8b', '[\"*\"]', '2026-05-26 22:45:19', NULL, '2026-05-26 22:45:06', '2026-05-26 22:45:19'),
(76, 'App\\Models\\User', 5, 'auth_token', '4712d47c00ce9b855b311c73a56b2c8fab58af45ed4e7518441942fbe44f1892', '[\"*\"]', '2026-05-26 22:46:11', NULL, '2026-05-26 22:45:30', '2026-05-26 22:46:11'),
(77, 'App\\Models\\User', 2, 'auth_token', '86b94d49695fb8587345939de7adb67f1b4114cdba18ff03a17659e2f77af47c', '[\"*\"]', '2026-05-26 22:46:19', NULL, '2026-05-26 22:46:18', '2026-05-26 22:46:19'),
(78, 'App\\Models\\User', 2, 'auth_token', '4ed8c3c18a66eaa6079ece2f1bba46a024515ebdfaeeae581cc40ecfcb99cf66', '[\"*\"]', '2026-05-26 22:46:30', NULL, '2026-05-26 22:46:23', '2026-05-26 22:46:30'),
(79, 'App\\Models\\User', 4, 'auth_token', '32ed54dfeead0afaea3fecca6acf3847dcaaf765d78399216135e25ccc5f3938', '[\"*\"]', '2026-05-27 00:14:20', NULL, '2026-05-26 22:46:39', '2026-05-27 00:14:20'),
(80, 'App\\Models\\User', 5, 'auth_token', '80c6bd82388ff26ba16abf91c5ba5c104869c4c77b8b2cd3b4a688357201fc5a', '[\"*\"]', '2026-05-27 00:16:34', NULL, '2026-05-27 00:16:30', '2026-05-27 00:16:34'),
(81, 'App\\Models\\User', 4, 'auth_token', '73d55e93b147db45b6704feec4fea05e7e032bc49da93f064dbed528a147a2ed', '[\"*\"]', '2026-05-27 00:22:56', NULL, '2026-05-27 00:22:53', '2026-05-27 00:22:56'),
(82, 'App\\Models\\User', 2, 'auth_token', '786026d7c80e28676f07b49a6c3b83f4520b05999bed107d131bb91c595d5c6f', '[\"*\"]', '2026-05-27 04:21:17', NULL, '2026-05-27 04:18:43', '2026-05-27 04:21:17'),
(83, 'App\\Models\\User', 4, 'auth_token', 'b6996baf1457773e680e49b1d76ecad44012f616a948f6458adfbdde675e80d4', '[\"*\"]', '2026-05-27 04:23:11', NULL, '2026-05-27 04:22:45', '2026-05-27 04:23:11'),
(84, 'App\\Models\\User', 1, 'auth_token', '1dfef1fe8e7775be6941ab83028166e46ffa3fcbc8600b8ce83d475047db0c64', '[\"*\"]', '2026-05-27 08:35:45', NULL, '2026-05-27 04:23:52', '2026-05-27 08:35:45'),
(85, 'App\\Models\\User', 4, 'auth_token', '81966e45e9e7c7b6654429ae1e358f5bae787f734e27c097dbc2aa483f53224a', '[\"*\"]', '2026-05-27 08:38:45', NULL, '2026-05-27 08:35:55', '2026-05-27 08:38:45'),
(86, 'App\\Models\\User', 4, 'auth_token', '038b7bae18b168de5c34c21bb063725d590e9652b92c0f1d7c8bec29c284265c', '[\"*\"]', '2026-05-27 21:34:30', NULL, '2026-05-27 21:29:18', '2026-05-27 21:34:30'),
(87, 'App\\Models\\User', 1, 'auth_token', 'c2bd206d3a8c43803d7cf61afda405e8e25ac8209dc83539986716d4f188acf1', '[\"*\"]', NULL, NULL, '2026-05-27 21:34:37', '2026-05-27 21:34:37'),
(88, 'App\\Models\\User', 1, 'auth_token', '309275e10513474d5ca29a0b673ed09ca6d830b7fa834bca649960743af5a517', '[\"*\"]', '2026-05-27 21:34:39', NULL, '2026-05-27 21:34:38', '2026-05-27 21:34:39'),
(89, 'App\\Models\\User', 1, 'auth_token', '00179ed043a2b2a6fafd873c0380e5102bd57b5ff4be72af3be1f0f8f5b14692', '[\"*\"]', '2026-05-27 21:35:29', NULL, '2026-05-27 21:35:28', '2026-05-27 21:35:29'),
(90, 'App\\Models\\User', 2, 'auth_token', '50f533e1e81877ebaf232afd9c5eccb643a8c81efa240df471f74d394057fc98', '[\"*\"]', '2026-05-27 21:35:38', NULL, '2026-05-27 21:35:37', '2026-05-27 21:35:38'),
(91, 'App\\Models\\User', 5, 'auth_token', '82a44aa71db4fc04bdcc5f0ecdd22b9bd18419cdd2a22eafc26609ce3ba6917a', '[\"*\"]', '2026-05-27 21:36:11', NULL, '2026-05-27 21:36:10', '2026-05-27 21:36:11'),
(92, 'App\\Models\\User', 4, 'auth_token', 'aaa7ce8c223f2d7c302eaadd722b4ae4fd6cdc590a4e06cfcf4f481c9efd8608', '[\"*\"]', '2026-05-27 21:38:20', NULL, '2026-05-27 21:36:49', '2026-05-27 21:38:20'),
(93, 'App\\Models\\User', 4, 'auth_token', '7756ca1c8f407b0164af00f6f153075b8fc3957ce7813508c6239784af7867a4', '[\"*\"]', '2026-05-27 22:06:35', NULL, '2026-05-27 21:38:25', '2026-05-27 22:06:35'),
(94, 'App\\Models\\User', 5, 'auth_token', '415b19dbac92d4a03034b6f3513806fff9b3037d849160acca38fd69957c958d', '[\"*\"]', '2026-05-27 22:09:23', NULL, '2026-05-27 22:07:03', '2026-05-27 22:09:23'),
(95, 'App\\Models\\User', 5, 'auth_token', '4cc9d4fc529fa466e256fc6882f6d5e6de740d748d324730fedac02be8d6063f', '[\"*\"]', '2026-05-27 22:13:25', NULL, '2026-05-27 22:11:51', '2026-05-27 22:13:25'),
(96, 'App\\Models\\User', 2, 'auth_token', '45bc4dcb6da1d2855e29b28884fd43741f5028cd2b74ad46ad07e17f608b3771', '[\"*\"]', '2026-05-27 22:14:20', NULL, '2026-05-27 22:13:39', '2026-05-27 22:14:20'),
(97, 'App\\Models\\User', 1, 'auth_token', '16ecd8f6eed4ee3c7a850dc1f8d01f81c6bb99a31631c333c77627ea6f853fdb', '[\"*\"]', '2026-05-27 22:31:49', NULL, '2026-05-27 22:15:42', '2026-05-27 22:31:49'),
(98, 'App\\Models\\User', 1, 'auth_token', '6a549300b265e27fe1af35629b1aa33562e8282527a8acf10dcf4275fd62fa1b', '[\"*\"]', '2026-05-27 22:52:41', NULL, '2026-05-27 22:52:32', '2026-05-27 22:52:41'),
(99, 'App\\Models\\User', 7, 'auth_token', 'ab954c5255375c6dff1f036f44f72fe8a5d508aa8dc3b29437eaa82bacad3177', '[\"*\"]', NULL, NULL, '2026-05-27 22:52:53', '2026-05-27 22:52:53'),
(100, 'App\\Models\\User', 7, 'auth_token', '7abcb7fedb7372115345fb1743a57c5217e79390a300d92da2a7d49d4dcf6e10', '[\"*\"]', NULL, NULL, '2026-05-27 22:52:55', '2026-05-27 22:52:55'),
(101, 'App\\Models\\User', 7, 'auth_token', '46194f9f0e788f142fcf42e4b4d75e081ba993cafa3f2a77c3d41b2ab88b14ed', '[\"*\"]', NULL, NULL, '2026-05-27 22:53:00', '2026-05-27 22:53:00'),
(102, 'App\\Models\\User', 7, 'auth_token', '8f6378975fdf00c8e54159af676146b9a5d2adce8a825f503e5253253a725dfd', '[\"*\"]', NULL, NULL, '2026-05-27 22:53:00', '2026-05-27 22:53:00'),
(103, 'App\\Models\\User', 7, 'auth_token', '8ef93db025df5f44bbbe2e6fa09d9c8b6b22ed88df92773d3d7d6d1e4777f8c4', '[\"*\"]', NULL, NULL, '2026-05-27 22:53:03', '2026-05-27 22:53:03'),
(104, 'App\\Models\\User', 7, 'auth_token', '6bcdabd3e6d41bea0ac960eb6512ce1890e4f5acb759beb93ae8f6f0ceb072c4', '[\"*\"]', NULL, NULL, '2026-05-27 22:53:06', '2026-05-27 22:53:06'),
(105, 'App\\Models\\User', 7, 'auth_token', 'd53eff555d73091494c7338e54e5a88e4ef7f35dffab915298fb3c7625c1c56e', '[\"*\"]', NULL, NULL, '2026-05-27 22:53:11', '2026-05-27 22:53:11'),
(106, 'App\\Models\\User', 7, 'auth_token', '85609b3c62007bf62d4a50e9379ae5ca5da02a07495ee936bff6e1e906b4640c', '[\"*\"]', NULL, NULL, '2026-05-27 22:53:13', '2026-05-27 22:53:13'),
(107, 'App\\Models\\User', 1, 'auth_token', 'cbd7289e86707aeba671bac340aa20a9e6402e8b1cc1f8e31548f2edf4dc5948', '[\"*\"]', '2026-05-27 22:53:36', NULL, '2026-05-27 22:53:23', '2026-05-27 22:53:36'),
(108, 'App\\Models\\User', 7, 'auth_token', '5448daa9d08729d51d65d2736cfee1b2d11d33e11f90ec713daba26b64efc868', '[\"*\"]', NULL, NULL, '2026-05-27 22:53:56', '2026-05-27 22:53:56'),
(109, 'App\\Models\\User', 7, 'auth_token', '6bf1aeb4b3a35c708eac347716d984ad637b3d65e6d3326b5346dcaecafe70c1', '[\"*\"]', '2026-05-27 22:58:44', NULL, '2026-05-27 22:58:21', '2026-05-27 22:58:44'),
(110, 'App\\Models\\User', 7, 'auth_token', '5eaeda3e03bc7451226bad27f1af5b98a73d85597d23edb5b9071093ed1997f5', '[\"*\"]', '2026-06-02 19:49:27', NULL, '2026-06-02 19:49:25', '2026-06-02 19:49:27'),
(111, 'App\\Models\\User', 1, 'auth_token', '71fc7e6007f0380870da20575936409b82ea618aceb97d748db4e2fb2d48363d', '[\"*\"]', '2026-06-02 19:49:51', NULL, '2026-06-02 19:49:39', '2026-06-02 19:49:51'),
(112, 'App\\Models\\User', 8, 'auth_token', '588675cac5074e716591bc0681860c40f8fe2b750bd5c0ddc31ed8c924565929', '[\"*\"]', '2026-06-02 19:56:49', NULL, '2026-06-02 19:56:38', '2026-06-02 19:56:49'),
(113, 'App\\Models\\User', 8, 'auth_token', 'b25f32a3870ead5fffa30062d56330d33b8b88044f245bda25801bb4fdb33c3e', '[\"*\"]', '2026-06-02 20:04:35', NULL, '2026-06-02 20:02:04', '2026-06-02 20:04:35'),
(114, 'App\\Models\\User', 9, 'auth_token', '1e7e4255801360d9379e09320d84c69ab18afba506feb1efaa55c00cf691bf3b', '[\"*\"]', '2026-06-02 20:05:32', NULL, '2026-06-02 20:05:04', '2026-06-02 20:05:32'),
(115, 'App\\Models\\User', 8, 'auth_token', '471f2f5719db4ba7a520de8f382118a53fe7c421e0f292581087de786f0f3838', '[\"*\"]', '2026-06-02 20:08:18', NULL, '2026-06-02 20:05:46', '2026-06-02 20:08:18'),
(116, 'App\\Models\\User', 4, 'auth_token', '7e175929bc47a9d7a3e337bfacb5718fa56c376ffa7c2536c93d31b65b4d7948', '[\"*\"]', '2026-06-02 20:09:52', NULL, '2026-06-02 20:09:46', '2026-06-02 20:09:52'),
(117, 'App\\Models\\User', 9, 'auth_token', 'f1f291d4d7246d5bb484658856af4fc135c365b6e02da53fb0ef98bbaac72058', '[\"*\"]', '2026-06-02 20:12:31', NULL, '2026-06-02 20:11:35', '2026-06-02 20:12:31'),
(118, 'App\\Models\\User', 8, 'auth_token', '3f47068be894c2e181abf8d8604a5e6759c90e47c874aac58f7c26f4dbc1a4af', '[\"*\"]', '2026-06-02 20:14:09', NULL, '2026-06-02 20:12:52', '2026-06-02 20:14:09'),
(119, 'App\\Models\\User', 10, 'auth_token', 'd950661633d57b66c77edba4be0109d7598d6b86eee8b73494090ea048ef5eff', '[\"*\"]', '2026-06-02 20:15:43', NULL, '2026-06-02 20:14:40', '2026-06-02 20:15:43'),
(120, 'App\\Models\\User', 8, 'auth_token', '6568fe13bba0f6bd73cb9f7946fe94d955a2e9cb327dd4ab00752f133cad3ba3', '[\"*\"]', '2026-06-02 20:16:21', NULL, '2026-06-02 20:15:51', '2026-06-02 20:16:21'),
(121, 'App\\Models\\User', 10, 'auth_token', '10fc96d04aace827e97dacb013cc24ed53fa587ee31b51f91d39f3a458297317', '[\"*\"]', '2026-06-02 20:17:46', NULL, '2026-06-02 20:16:42', '2026-06-02 20:17:46'),
(122, 'App\\Models\\User', 8, 'auth_token', '212f1c12e187a85eed17d214bb5cbcfb55403242a85dd7a779aa70593d601278', '[\"*\"]', '2026-06-02 20:18:52', NULL, '2026-06-02 20:18:33', '2026-06-02 20:18:52'),
(123, 'App\\Models\\User', 10, 'auth_token', 'b1b0037b472e985b62756cf54a8fd121ebb1a117387fabfe9941eb78327d4561', '[\"*\"]', '2026-06-02 20:19:18', NULL, '2026-06-02 20:19:00', '2026-06-02 20:19:18'),
(124, 'App\\Models\\User', 9, 'auth_token', 'a7c73840a9a4c8d23705ca1a0926502f228994b10a27cacbeb9ae13c02c68ee4', '[\"*\"]', '2026-06-02 20:20:50', NULL, '2026-06-02 20:20:46', '2026-06-02 20:20:50'),
(125, 'App\\Models\\User', 10, 'auth_token', '92ac9604478e2c12d59c6a7ce2b2ad56aa9d83b382e2cb7baa69b9bf441c72d0', '[\"*\"]', '2026-06-02 20:21:33', NULL, '2026-06-02 20:21:00', '2026-06-02 20:21:33'),
(126, 'App\\Models\\User', 8, 'auth_token', '46c25f2ec2078ff14c2e5ec9087920666fce8deed76886a6fcaad8c41f5f232c', '[\"*\"]', '2026-06-02 20:22:07', NULL, '2026-06-02 20:22:06', '2026-06-02 20:22:07'),
(127, 'App\\Models\\User', 9, 'auth_token', '8e5f81b303ecf5574cfad35c8204296d81a1e0bc429aaeffe11a092272d2f383', '[\"*\"]', '2026-06-02 20:22:16', NULL, '2026-06-02 20:22:15', '2026-06-02 20:22:16'),
(128, 'App\\Models\\User', 8, 'auth_token', '4f7da9d9f5594bd4c66095e243f7f18570beef41b5057be84f728d90f16de103', '[\"*\"]', '2026-06-02 20:22:39', NULL, '2026-06-02 20:22:27', '2026-06-02 20:22:39'),
(129, 'App\\Models\\User', 9, 'auth_token', '4921ffea0a71cc80d72f4d60c9a6f26c4d6406ba7cdf0501572bc10e4ca8df99', '[\"*\"]', '2026-06-02 20:25:04', NULL, '2026-06-02 20:24:52', '2026-06-02 20:25:04'),
(130, 'App\\Models\\User', 8, 'auth_token', 'b3efea3ba16b4756e48fe8509fe7300160c06c48654c399b171529487db3edc2', '[\"*\"]', '2026-06-02 20:28:40', NULL, '2026-06-02 20:26:07', '2026-06-02 20:28:40'),
(131, 'App\\Models\\User', 10, 'auth_token', '407654b69bdacbc911e84b844a86304a2d075bcdd108240e4406e4f170b2267b', '[\"*\"]', '2026-06-02 20:28:57', NULL, '2026-06-02 20:28:53', '2026-06-02 20:28:57'),
(132, 'App\\Models\\User', 5, 'auth_token', '9fed624b42b78e628679bf6708fbdbf4863f280a3f6e73b21bef55c92cacb50f', '[\"*\"]', '2026-06-02 20:29:31', NULL, '2026-06-02 20:29:08', '2026-06-02 20:29:31'),
(133, 'App\\Models\\User', 8, 'auth_token', '4b7e666a446f52ba2fdf871eb7aff70ec278545f1b3c974575ea4867a1d38d2d', '[\"*\"]', '2026-06-02 20:29:56', NULL, '2026-06-02 20:29:43', '2026-06-02 20:29:56'),
(134, 'App\\Models\\User', 5, 'auth_token', '22c560bfa1075a99ae6686361cf5a1bed0d0f4d4c416af0a81d2def0a757299e', '[\"*\"]', '2026-06-02 20:32:31', NULL, '2026-06-02 20:30:36', '2026-06-02 20:32:31'),
(135, 'App\\Models\\User', 7, 'auth_token', '78a19b56b5d8ad18fd31ad47a5cd170c96b24250cad65e11193cb8932b36754a', '[\"*\"]', '2026-06-02 20:37:34', NULL, '2026-06-02 20:33:24', '2026-06-02 20:37:34'),
(136, 'App\\Models\\User', 8, 'auth_token', '1dfb5119dab513478723cc4d899f04aba7edb93f0b7de78a61517bba962ee5e9', '[\"*\"]', '2026-06-02 20:38:46', NULL, '2026-06-02 20:38:32', '2026-06-02 20:38:46'),
(137, 'App\\Models\\User', 9, 'auth_token', '1fc114c11a1113dabc462deef5d3cca6c861c2b2bd4b27f56b2e8ce3b811f2e5', '[\"*\"]', '2026-06-02 20:38:58', NULL, '2026-06-02 20:38:57', '2026-06-02 20:38:58'),
(138, 'App\\Models\\User', 8, 'auth_token', 'd57bdeaa26adbc4d0f8ecff6a233d8ada4221e0f4a04be11f945dfd894f78188', '[\"*\"]', '2026-06-02 20:41:27', NULL, '2026-06-02 20:41:26', '2026-06-02 20:41:27'),
(139, 'App\\Models\\User', 4, 'auth_token', '543f002119af7c8d72875a2d960cf1986b33c8cbe732ac204fc564c77c2a75de', '[\"*\"]', '2026-06-02 20:44:01', NULL, '2026-06-02 20:41:55', '2026-06-02 20:44:01'),
(140, 'App\\Models\\User', 8, 'auth_token', '0e0b83ae6627a4568363d561786d4e8d2d1ddaf57a0d4590ae2a52e983e8a6c4', '[\"*\"]', '2026-06-02 20:45:29', NULL, '2026-06-02 20:44:35', '2026-06-02 20:45:29'),
(141, 'App\\Models\\User', 5, 'auth_token', '319327d82fe7fc17935f4726b38d0080ab52eb6d1d7094ced1b065f437cf4a7e', '[\"*\"]', '2026-06-02 20:46:03', NULL, '2026-06-02 20:45:55', '2026-06-02 20:46:03'),
(142, 'App\\Models\\User', 8, 'auth_token', '42b13f9db5c54299ad9a55f7d8ed4cfd2f169ea379a3a41e2b1bed094098263c', '[\"*\"]', '2026-06-02 20:49:16', NULL, '2026-06-02 20:49:15', '2026-06-02 20:49:16'),
(143, 'App\\Models\\User', 1, 'auth_token', 'b9171262cdd4e706cf4a19981c190c262947d5a0572bec5f90385f63a2cf23e7', '[\"*\"]', '2026-06-02 20:49:50', NULL, '2026-06-02 20:49:34', '2026-06-02 20:49:50'),
(144, 'App\\Models\\User', 8, 'auth_token', 'e70541f6147d0408cba3026745adeba684c8d4acf2369f94c47c0c4da310bce5', '[\"*\"]', '2026-06-02 20:50:30', NULL, '2026-06-02 20:50:30', '2026-06-02 20:50:30'),
(145, 'App\\Models\\User', 7, 'auth_token', '19d155bf382d28b7340f166a864ff7752b06dab82c6deb01f3a41ac4bf2c8bee', '[\"*\"]', '2026-06-02 20:54:25', NULL, '2026-06-02 20:51:00', '2026-06-02 20:54:25'),
(146, 'App\\Models\\User', 4, 'auth_token', 'abce6c6f80d0ec7534f161d062fe36997415884401125b056e2d0290823ba495', '[\"*\"]', NULL, NULL, '2026-06-03 00:10:03', '2026-06-03 00:10:03'),
(147, 'App\\Models\\User', 4, 'auth_token', 'c591218bdf6ca8b78e9262bd3aad5185da6fbb9b3d973f802f4ad764e60eaeed', '[\"*\"]', '2026-06-03 00:10:06', NULL, '2026-06-03 00:10:03', '2026-06-03 00:10:06'),
(148, 'App\\Models\\User', 2, 'auth_token', 'b5313e951407049b70806719160636e5d614a9a889866ed7f61bd073a8b367bf', '[\"*\"]', '2026-06-03 01:30:25', NULL, '2026-06-03 00:27:02', '2026-06-03 01:30:25'),
(149, 'App\\Models\\User', 10, 'auth_token', '6ef105f13c57e1d2d619aaea9605d0812fc94a6754c46a63571ba67643fc1e66', '[\"*\"]', '2026-06-03 04:47:54', NULL, '2026-06-03 04:47:51', '2026-06-03 04:47:54'),
(150, 'App\\Models\\User', 5, 'auth_token', '5973e25221b0f871b7788cce3fc88557e07f3e6f832a710fe91209901a27c87c', '[\"*\"]', '2026-06-03 04:49:37', NULL, '2026-06-03 04:49:26', '2026-06-03 04:49:37'),
(151, 'App\\Models\\User', 1, 'auth_token', '80db6e3580fe333bb6853931404ca44788f331077912f98ed2e54292c253b44b', '[\"*\"]', '2026-06-03 05:01:41', NULL, '2026-06-03 04:50:26', '2026-06-03 05:01:41'),
(152, 'App\\Models\\User', 5, 'auth_token', '6e18e93e45d26b53028ec52f9a9c05dec2ac1aac997422c3e8120b58192ffaf6', '[\"*\"]', '2026-06-03 05:26:31', NULL, '2026-06-03 05:01:51', '2026-06-03 05:26:31'),
(153, 'App\\Models\\User', 4, 'auth_token', 'c53c66148d79da289695459c5cf669787693b3c190824db334816d34d47b56f0', '[\"*\"]', '2026-06-03 05:29:15', NULL, '2026-06-03 05:27:17', '2026-06-03 05:29:15'),
(154, 'App\\Models\\User', 2, 'auth_token', '8aa534c867fd602258afa62b0ab734083794c41ec56ecc54dfef55f83489f394', '[\"*\"]', '2026-06-03 05:31:06', NULL, '2026-06-03 05:29:34', '2026-06-03 05:31:06'),
(155, 'App\\Models\\User', 7, 'auth_token', '195327db55e573600defc190809de0d27271c1ff1def44496839ba265a50a785', '[\"*\"]', '2026-06-03 05:38:41', NULL, '2026-06-03 05:31:53', '2026-06-03 05:38:41'),
(156, 'App\\Models\\User', 7, 'auth_token', '19d2798bb5cce499d78d786691ce36b9ca7e15ff3d69650f4795b4f27d464446', '[\"*\"]', '2026-06-03 05:40:09', NULL, '2026-06-03 05:40:08', '2026-06-03 05:40:09'),
(157, 'App\\Models\\User', 4, 'auth_token', 'fb45ab84dcab74d5ec1bde504cd8d49236678f1307504f69d4043c4d08f60a6c', '[\"*\"]', '2026-06-03 05:40:45', NULL, '2026-06-03 05:40:38', '2026-06-03 05:40:45'),
(158, 'App\\Models\\User', 4, 'auth_token', 'dd26afdc80178d7ad5c24df32afd0bcfa17363cb5acf568d8620ec09afa3fe05', '[\"*\"]', NULL, NULL, '2026-06-03 05:40:39', '2026-06-03 05:40:39'),
(159, 'App\\Models\\User', 8, 'auth_token', 'df3de24074a27d9ef5168b661ca9dec236f374cd946d65575a40efa7a3209e6d', '[\"*\"]', '2026-06-03 05:43:23', NULL, '2026-06-03 05:42:51', '2026-06-03 05:43:23'),
(160, 'App\\Models\\User', 8, 'auth_token', '297763931416f7bad370b2044d8cf662aef3c0921bc7c719006111effb9ddfc2', '[\"*\"]', '2026-06-04 01:06:32', NULL, '2026-06-04 01:06:31', '2026-06-04 01:06:32'),
(161, 'App\\Models\\User', 4, 'auth_token', 'af9eaadb5d5b17908c849f1e3a0baa4930e2353e484d971f6b21d6aba45146db', '[\"*\"]', '2026-06-04 01:06:48', NULL, '2026-06-04 01:06:43', '2026-06-04 01:06:48'),
(162, 'App\\Models\\User', 8, 'auth_token', 'eb525e9c43394059e9afa3ad86f07337bbc0ab6ea0cdf43c75f59f6b7e77ec07', '[\"*\"]', '2026-06-04 02:16:34', NULL, '2026-06-04 01:06:58', '2026-06-04 02:16:34'),
(163, 'App\\Models\\User', 4, 'auth_token', 'ea2d57bbfa9fd89061e11f73b649563ca541c21ab8592d5d107f27846f747c71', '[\"*\"]', '2026-06-04 02:17:57', NULL, '2026-06-04 02:16:45', '2026-06-04 02:17:57'),
(164, 'App\\Models\\User', 8, 'auth_token', '4a03992d2fc3a54e205c15977b0a896f5d391913623aed33d8e2426d12bb41a8', '[\"*\"]', '2026-06-04 02:18:45', NULL, '2026-06-04 02:18:04', '2026-06-04 02:18:45'),
(165, 'App\\Models\\User', 4, 'auth_token', 'ffe7df4028f6110a3420d620c36fb6129190e42731b65c29d7aada60efc8a6f6', '[\"*\"]', '2026-06-04 02:27:12', NULL, '2026-06-04 02:19:01', '2026-06-04 02:27:12'),
(166, 'App\\Models\\User', 8, 'auth_token', 'aa61000adedced7628d06eca02771f2eb238ba3010d179ff70ccdf3ef76f4692', '[\"*\"]', '2026-06-04 02:28:08', NULL, '2026-06-04 02:27:50', '2026-06-04 02:28:08'),
(167, 'App\\Models\\User', 2, 'auth_token', '754721b727b7409b2de842eee4edcb8e076256753089a7baadf48b4cb50563cf', '[\"*\"]', '2026-06-04 02:29:43', NULL, '2026-06-04 02:29:41', '2026-06-04 02:29:43'),
(168, 'App\\Models\\User', 4, 'auth_token', 'c2c0f712c9b943f8030853aab8c95f5238173d0a5a5946ecaff88cdd41f777aa', '[\"*\"]', '2026-06-04 06:59:35', NULL, '2026-06-04 06:52:01', '2026-06-04 06:59:35'),
(169, 'App\\Models\\User', 2, 'auth_token', '88e1f83848afd8acb7bfb6507e44f405eab12182663166c6c2015661023209e9', '[\"*\"]', '2026-06-04 06:59:52', NULL, '2026-06-04 06:59:51', '2026-06-04 06:59:52'),
(170, 'App\\Models\\User', 2, 'auth_token', 'e3c590e80f852f152f5f1aecb65e865f53968d6f24136f59a129035dd951cbad', '[\"*\"]', '2026-06-04 07:07:49', NULL, '2026-06-04 07:07:47', '2026-06-04 07:07:49'),
(171, 'App\\Models\\User', 4, 'auth_token', 'e61f785b43f62b82c7f527db1390da86c9c5e52eac75f75ca69e9dd01c68657e', '[\"*\"]', '2026-06-04 07:10:04', NULL, '2026-06-04 07:07:54', '2026-06-04 07:10:04'),
(172, 'App\\Models\\User', 2, 'auth_token', '539f45c0610092eee924e86005c2def92ccd0048d2cc38055e536fef61dee1cd', '[\"*\"]', '2026-06-04 07:12:56', NULL, '2026-06-04 07:10:26', '2026-06-04 07:12:56'),
(173, 'App\\Models\\User', 4, 'auth_token', '2652319dabbf16091b629145ed0d5f9d06a4299309062bfbd5000bf0957ba226', '[\"*\"]', '2026-06-04 07:14:50', NULL, '2026-06-04 07:14:49', '2026-06-04 07:14:50'),
(174, 'App\\Models\\User', 1, 'auth_token', 'c98b4f57ba3d2022144d2b0ef508747c5c98af482480a842254ad7dcaf4e37a7', '[\"*\"]', '2026-06-04 07:15:12', NULL, '2026-06-04 07:15:01', '2026-06-04 07:15:12'),
(175, 'App\\Models\\User', 3, 'auth_token', 'dcf57eb91cce0f9eb67e7c55555c3ed4e4e5c78ed55244c3bd4245cb3263851b', '[\"*\"]', '2026-06-04 07:15:49', NULL, '2026-06-04 07:15:30', '2026-06-04 07:15:49'),
(176, 'App\\Models\\User', 4, 'auth_token', 'af8b91510c6d458182f8b44003b7865c0fd9e741944369b2dd13dd233a152ff9', '[\"*\"]', '2026-06-04 07:16:14', NULL, '2026-06-04 07:16:00', '2026-06-04 07:16:14'),
(177, 'App\\Models\\User', 3, 'auth_token', 'b52e10255845cb90da3b95f96253273e8bdcfd98ccfad7043a1f0a4e0a6ed16a', '[\"*\"]', '2026-06-04 07:16:58', NULL, '2026-06-04 07:16:20', '2026-06-04 07:16:58'),
(178, 'App\\Models\\User', 4, 'auth_token', '65db2b86286330b5a6048c1a43a3b78713c9ab5aeca646fccf49b6518ef237e6', '[\"*\"]', '2026-06-04 07:17:31', NULL, '2026-06-04 07:17:06', '2026-06-04 07:17:31'),
(179, 'App\\Models\\User', 3, 'auth_token', 'e6a407fe944af098d52f92545ca0b4e788f49e2743389dfbc95535d433e0d8e0', '[\"*\"]', '2026-06-04 07:17:52', NULL, '2026-06-04 07:17:45', '2026-06-04 07:17:52'),
(180, 'App\\Models\\User', 3, 'auth_token', '36595c27774809a19b834ab6ad8bf36c2f17af1487130e3ff241e94f6a9e6327', '[\"*\"]', '2026-06-04 07:18:15', NULL, '2026-06-04 07:17:54', '2026-06-04 07:18:15'),
(181, 'App\\Models\\User', 4, 'auth_token', '9405d222f2aa5e8b13595a531a030d971ca122a44ce4b45dfd36e5d04281af1c', '[\"*\"]', '2026-06-04 07:19:15', NULL, '2026-06-04 07:18:23', '2026-06-04 07:19:15'),
(182, 'App\\Models\\User', 4, 'auth_token', '861132d40ab203728df3f96e3ca0b25721cba95d03ed4a74491847a5383365c5', '[\"*\"]', '2026-06-04 07:19:30', NULL, '2026-06-04 07:19:29', '2026-06-04 07:19:30'),
(183, 'App\\Models\\User', 7, 'auth_token', '230cdd8ab01727921760bbb7deaa11324a4be4fa31e9efeb6aeedfff0e119b62', '[\"*\"]', '2026-06-04 07:46:03', NULL, '2026-06-04 07:19:45', '2026-06-04 07:46:03'),
(184, 'App\\Models\\User', 7, 'auth_token', '3868a78720f7ff5e5c7a2afbf16897f240f456ab7e60d8e2b338ea175f3317ea', '[\"*\"]', '2026-06-04 23:30:00', NULL, '2026-06-04 18:10:39', '2026-06-04 23:30:00'),
(185, 'App\\Models\\User', 2, 'auth_token', 'ecb4c90311496e88453c21d6f4c1718d0d33362f5f024141682d493c539fcc4f', '[\"*\"]', '2026-06-04 23:34:24', NULL, '2026-06-04 23:34:23', '2026-06-04 23:34:24'),
(186, 'App\\Models\\User', 4, 'auth_token', '1214ec945fe374dfef985c342ad8fc38dbc73b7df98c4b1414c13537eea00cbe', '[\"*\"]', '2026-06-04 23:35:11', NULL, '2026-06-04 23:34:37', '2026-06-04 23:35:11'),
(187, 'App\\Models\\User', 7, 'auth_token', '39b33de1c7123d70c11da2312da9aa23c29101ad8928fdd986b71a11aa3994bf', '[\"*\"]', '2026-06-04 23:38:53', NULL, '2026-06-04 23:35:59', '2026-06-04 23:38:53'),
(188, 'App\\Models\\User', 2, 'auth_token', '0f27b67aa17d7fc54883dfdf1ccdbd983624a87d7f105978469626a9b5f3f138', '[\"*\"]', '2026-06-04 23:43:40', NULL, '2026-06-04 23:43:35', '2026-06-04 23:43:40'),
(189, 'App\\Models\\User', 2, 'auth_token', '45cc93914a92bbd6847c71029382571ee357fc659989f79ecc8c42400ccfc3b3', '[\"*\"]', '2026-06-04 23:45:06', NULL, '2026-06-04 23:45:04', '2026-06-04 23:45:06'),
(190, 'App\\Models\\User', 4, 'auth_token', '52650fea2a4717644663edb77bccf6dfa5a44b4fbd6168275e90aaefe0457bd0', '[\"*\"]', '2026-06-04 23:45:21', NULL, '2026-06-04 23:45:13', '2026-06-04 23:45:21'),
(191, 'App\\Models\\User', 7, 'auth_token', 'd45b42019912e5bdaebd5c233bb876376b6d7fc8892160f76e2da42e2bbf35ac', '[\"*\"]', '2026-06-05 04:43:27', NULL, '2026-06-05 04:43:24', '2026-06-05 04:43:27'),
(192, 'App\\Models\\User', 4, 'auth_token', '21c9e987f45dc2e413b8aa22ec08e8dceb505a49ea4fcbd82aae0cd6b9b26694', '[\"*\"]', '2026-06-05 06:36:45', NULL, '2026-06-05 06:36:44', '2026-06-05 06:36:45');

-- --------------------------------------------------------

--
-- Table structure for table `repair_requests`
--

CREATE TABLE `repair_requests` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `company_id` bigint(20) UNSIGNED DEFAULT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `branch_id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `category_id` bigint(20) UNSIGNED NOT NULL,
  `sub_category_id` bigint(20) UNSIGNED DEFAULT NULL,
  `status` varchar(255) NOT NULL,
  `spk_number` varchar(255) DEFAULT NULL,
  `spk_sent_at` timestamp NULL DEFAULT NULL,
  `spk_sent_by` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `reject_reason` text DEFAULT NULL,
  `approved_at` timestamp NULL DEFAULT NULL,
  `technician_id` bigint(20) UNSIGNED DEFAULT NULL,
  `inspection_notes` text DEFAULT NULL,
  `urgency` enum('low','medium','high') DEFAULT NULL,
  `schedule_date` date DEFAULT NULL,
  `photo` varchar(255) DEFAULT NULL,
  `completion_note` text DEFAULT NULL,
  `completion_photo` varchar(255) DEFAULT NULL,
  `material_used` varchar(255) DEFAULT NULL,
  `completed_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `repair_requests`
--

INSERT INTO `repair_requests` (`id`, `company_id`, `user_id`, `branch_id`, `title`, `description`, `category_id`, `sub_category_id`, `status`, `spk_number`, `spk_sent_at`, `spk_sent_by`, `created_at`, `updated_at`, `reject_reason`, `approved_at`, `technician_id`, `inspection_notes`, `urgency`, `schedule_date`, `photo`, `completion_note`, `completion_photo`, `material_used`, `completed_at`) VALUES
(1, 1, 2, 1, 'AC Ruangan 301 Rusak', 'Jadi ketika dicoba untuk dihidupkan ac tidak bisa dingin dan ada 3 ac', 1, NULL, 'done', 'SPK-REP-2026-0002', '2026-05-23 00:41:30', 4, '2026-05-12 23:49:40', '2026-05-23 00:41:30', NULL, NULL, 5, NULL, 'high', '2026-05-13', 'requests/Xled4blNsRfGAm8HnpA3gUzIQCwRrGK5tWU8v8Wr.png', NULL, NULL, NULL, NULL),
(2, 1, 2, 1, 'AC ga dingin', 'gak dingin', 1, 3, 'done', 'SPK-REP-2026-0001', '2026-05-23 00:41:12', 4, '2026-05-20 23:37:04', '2026-05-26 03:45:09', NULL, NULL, 5, NULL, 'high', '2026-05-21', 'requests/FWO3XKdBTBBePJJbumFRmmFFv0gLPEVGcShz8UVo.jpg', NULL, NULL, NULL, NULL),
(3, 1, 2, 1, 'AC Mati', 'Ac tiba2 mati .. padahal 2 hari lalu baru maintenance', 1, 3, 'done', 'SPK-REP-2026-0003', '2026-05-26 22:20:38', 4, '2026-05-26 22:16:41', '2026-05-26 22:46:09', NULL, NULL, 5, NULL, 'high', '2026-05-27', 'requests/Etmn77XcYmP9fgjFQEYL30v4T84FNujYWlWSl0Al.jpg', 'semua sudah beres hanya ada part yang di pasang kurang bagus makanya mati', 'completion-photos/EC6Vdg4gU6Tlosekp9N0dWeMPlqZXxPjUAWUbAoV.jpg', NULL, '2026-05-26 22:46:09'),
(4, 2, 9, 6, 'AC mati', 'ac tiba2 mati', 1, 3, 'done', 'SPK-REP-2026-0004', '2026-06-02 20:16:15', 8, '2026-06-02 20:12:12', '2026-06-02 20:21:28', NULL, NULL, 10, NULL, 'high', '2026-06-03', 'requests/p74rwFbAUIQoaDOoPIRC8jZ7mmZNSdQ7NSMWZ20t.jpg', 'karena kotor', 'completion-photos/EBc8VKWOLqzGzKUsURZ9QFaup6qG2ZJZoUUgs0RD.jpg', NULL, '2026-06-02 20:21:28');

-- --------------------------------------------------------

--
-- Table structure for table `rooms`
--

CREATE TABLE `rooms` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `branch_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `rooms`
--

INSERT INTO `rooms` (`id`, `branch_id`, `name`, `created_at`, `updated_at`) VALUES
(1, 1, 'Ruangan 301', '2026-05-06 22:23:32', '2026-05-06 22:23:32'),
(2, 6, 'Meeting', '2026-06-02 20:07:33', '2026-06-02 20:07:33'),
(3, 1, 'Ruangan 203', '2026-06-03 05:29:09', '2026-06-03 05:29:09'),
(4, 2, '201', '2026-06-04 07:16:14', '2026-06-04 07:16:14');

-- --------------------------------------------------------

--
-- Table structure for table `scheduled_maintenances`
--

CREATE TABLE `scheduled_maintenances` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `company_id` bigint(20) UNSIGNED DEFAULT NULL,
  `title` varchar(255) NOT NULL,
  `category_id` bigint(20) UNSIGNED NOT NULL,
  `scheduled_sub_category_id` bigint(20) UNSIGNED DEFAULT NULL,
  `note` text DEFAULT NULL,
  `period` varchar(255) NOT NULL,
  `is_auto` tinyint(1) NOT NULL DEFAULT 0,
  `parent_id` bigint(20) UNSIGNED DEFAULT NULL,
  `scheduled_date` date NOT NULL,
  `created_by` bigint(20) UNSIGNED NOT NULL,
  `worker_id` bigint(20) UNSIGNED NOT NULL,
  `status` enum('pending','confirmed','in_progress','done') NOT NULL DEFAULT 'pending',
  `spk_number` varchar(255) DEFAULT NULL,
  `spk_sent_at` timestamp NULL DEFAULT NULL,
  `spk_sent_by` bigint(20) UNSIGNED DEFAULT NULL,
  `worker_confirmed_at` timestamp NULL DEFAULT NULL,
  `completed_at` timestamp NULL DEFAULT NULL,
  `completion_note` text DEFAULT NULL,
  `completion_photo` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `scheduled_maintenances`
--

INSERT INTO `scheduled_maintenances` (`id`, `company_id`, `title`, `category_id`, `scheduled_sub_category_id`, `note`, `period`, `is_auto`, `parent_id`, `scheduled_date`, `created_by`, `worker_id`, `status`, `spk_number`, `spk_sent_at`, `spk_sent_by`, `worker_confirmed_at`, `completed_at`, `completion_note`, `completion_photo`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 1, 'Cek AC', 1, 1, 'Cucu dan cek kerusakan', 'monthly', 0, NULL, '2026-05-18', 4, 5, 'done', 'SPK-SCH-2026-0001', '2026-05-26 02:26:24', 4, '2026-05-17 22:29:15', '2026-05-18 01:49:43', 'semua amaan', 'maintenance/completion/uGrxL5l4tbmTD0aXHENzWV4Ontct2jEjpZwKqvfq.jpg', '2026-05-17 22:21:12', '2026-06-04 02:26:40', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `scheduled_sub_categories`
--

CREATE TABLE `scheduled_sub_categories` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `company_id` bigint(20) UNSIGNED DEFAULT NULL,
  `category_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `scheduled_sub_categories`
--

INSERT INTO `scheduled_sub_categories` (`id`, `company_id`, `category_id`, `name`, `description`, `is_active`, `created_at`, `updated_at`) VALUES
(1, NULL, 1, 'Cuci AC+Freon', 'Dilakukan 2 Bulan Sekali', 1, '2026-05-17 22:20:12', '2026-05-17 22:20:12');

-- --------------------------------------------------------

--
-- Table structure for table `sub_categories`
--

CREATE TABLE `sub_categories` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `company_id` bigint(20) UNSIGNED DEFAULT NULL,
  `category_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sub_categories`
--

INSERT INTO `sub_categories` (`id`, `company_id`, `category_id`, `name`, `created_at`, `updated_at`) VALUES
(1, NULL, 1, 'Perawatan Kabel Listrik', '2026-05-06 03:17:10', '2026-05-06 03:17:10'),
(2, NULL, 1, 'Perawatan Lampu', '2026-05-06 03:17:21', '2026-05-06 03:17:21'),
(3, NULL, 1, 'Perawatan Elektronik', '2026-05-06 03:17:44', '2026-05-06 03:17:44'),
(4, NULL, 1, 'Lain-lain', '2026-05-06 03:17:57', '2026-05-06 03:17:57'),
(5, NULL, 2, 'Perawatan Dinding', '2026-05-06 03:18:14', '2026-05-06 03:18:14'),
(6, NULL, 2, 'Perawatan Lantai', '2026-05-06 03:18:24', '2026-05-06 03:18:24'),
(7, NULL, 2, 'Perawatan Atap/Plafon', '2026-05-06 03:18:36', '2026-05-06 03:18:36'),
(8, NULL, 2, 'Perawatan Pintu/Paggar/Jendela', '2026-05-06 03:18:56', '2026-05-06 03:18:56'),
(9, NULL, 2, 'Perawatan Saluran Air/Drainase', '2026-05-06 03:19:15', '2026-05-06 03:19:15'),
(10, NULL, 2, 'Perawatan Furniture', '2026-05-06 03:19:29', '2026-05-06 03:19:29'),
(11, NULL, 2, 'Lain-lain', '2026-05-06 03:20:12', '2026-05-06 03:20:12');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `mode` enum('company') NOT NULL DEFAULT 'company',
  `system_type` enum('full','lite') NOT NULL DEFAULT 'lite',
  `company_id` bigint(20) UNSIGNED NOT NULL,
  `branch_id` bigint(20) UNSIGNED DEFAULT NULL,
  `role` enum('super_admin','admin','pic','technician','management') NOT NULL DEFAULT 'pic',
  `status` enum('pending','active','rejected','inactive') NOT NULL DEFAULT 'active',
  `must_change_password` tinyint(1) NOT NULL DEFAULT 1,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `reset_token` varchar(255) DEFAULT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `category_id` bigint(20) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `mode`, `system_type`, `company_id`, `branch_id`, `role`, `status`, `must_change_password`, `email_verified_at`, `reset_token`, `phone`, `created_at`, `updated_at`, `category_id`) VALUES
(1, 'Super Admin', 'admingedq@gmail.com', '$2y$12$ba1bxfJQ7mykZlbcGgZIeekPJBsqqBHVCaglYZ0Xo2oW5bwH0zQ82', 'company', 'full', 1, NULL, 'super_admin', 'active', 1, NULL, NULL, NULL, '2026-05-06 03:11:09', '2026-05-06 03:11:09', NULL),
(2, 'PIC Gedung AA', 'pictik@gmail.com', '$2y$12$/FmX.XSz4FlajRuqW46Gre1nEEm0nq9hp/oRElg/.3GHqvJ3Iwl5a', 'company', 'full', 1, 1, 'pic', 'active', 1, NULL, NULL, NULL, '2026-05-06 03:13:25', '2026-05-06 03:14:32', NULL),
(3, 'PIC Gedung GSG', 'picgsg@gmail.com', '$2y$12$Ksb6.f3YcRJJ1Rba8vCUgOcNfWXSYIibVs6h9z7MBZV7XcZ7Jl8Ju', 'company', 'full', 1, 2, 'pic', 'active', 1, NULL, NULL, NULL, '2026-05-06 03:15:03', '2026-05-06 03:15:19', NULL),
(4, 'Admin GA PNJ', 'admingapnj@gmail.com', '$2y$12$knSzObdaJHBPubC31m.XgeKhlFGbKxCIQcWjtw3NTu8NlNiUGiYcC', 'company', 'full', 1, 3, 'admin', 'active', 1, NULL, NULL, NULL, '2026-05-06 03:15:50', '2026-05-06 03:15:58', NULL),
(5, 'Teknisi Kelistrikan', 'tenisilistrik@gmail.com', '$2y$12$7R23crUmHboEo3ZduutxPOlAEha7dIpRRja.0NJ57hdukJ6ZzAWfK', 'company', 'full', 1, NULL, 'technician', 'active', 1, NULL, NULL, NULL, '2026-05-06 23:35:04', '2026-05-06 23:36:56', NULL),
(6, 'Teknisi  Sipil', 'teknisisipil@gmail.com', '$2y$12$KGpbb044AD.TI1f9Ep.5LeJTB84G8/H..udYHXNpRWazfFC96RJrq', 'company', 'full', 1, NULL, 'technician', 'active', 1, NULL, NULL, NULL, '2026-05-06 23:36:25', '2026-05-06 23:36:58', NULL),
(7, 'Management', 'managementpnj@gmail.com', '$2y$12$heTTcEr74vh0TadVskqpNO3.XRjWk/ko4grx5EErp1E/9Z01Y3Emu', 'company', 'full', 1, 3, 'management', 'active', 1, NULL, NULL, NULL, '2026-05-07 00:50:24', '2026-05-27 22:53:36', NULL),
(8, 'Admin GA', 'adminga@gmail.com', '$2y$12$7W.4Sjf3Qcdbtu.3yUk.rOpclXIwltxptugWDacepUDfxJEOAU7qm', 'company', 'lite', 2, NULL, 'admin', 'active', 1, NULL, NULL, NULL, '2026-06-02 19:56:18', '2026-06-02 19:56:18', NULL),
(9, 'PIC RE Bali', 'pic1@gmail.com', '$2y$12$IErtMRCCeBYmDvYZRr2BYuGeWeWsBF34qo/Cc9Zw7GqtsR6JnXsXi', 'company', 'lite', 2, 6, 'pic', 'active', 1, NULL, NULL, NULL, '2026-06-02 20:04:18', '2026-06-02 20:04:35', NULL),
(10, 'Teknisi Kelistrikan', 'teknisi@gmail.com', '$2y$12$5U./KCAofvSKDfa9uKS6muHgoopmSjv7QTvTufnSEX7mX920Tpwm6', 'company', 'lite', 2, NULL, 'technician', 'active', 1, NULL, NULL, NULL, '2026-06-02 20:13:19', '2026-06-02 20:13:26', 1);

-- --------------------------------------------------------

--
-- Table structure for table `work_orders`
--

CREATE TABLE `work_orders` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `wo_number` varchar(255) NOT NULL,
  `type` enum('repair','scheduled') NOT NULL,
  `repair_request_id` bigint(20) UNSIGNED DEFAULT NULL,
  `scheduled_maintenance_id` bigint(20) UNSIGNED DEFAULT NULL,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `note` text DEFAULT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'issued',
  `company_id` bigint(20) UNSIGNED NOT NULL,
  `category_id` bigint(20) UNSIGNED DEFAULT NULL,
  `sub_category_id` bigint(20) UNSIGNED DEFAULT NULL,
  `branch_id` bigint(20) UNSIGNED DEFAULT NULL,
  `worker_id` bigint(20) UNSIGNED DEFAULT NULL,
  `created_by` bigint(20) UNSIGNED NOT NULL,
  `schedule_date` date DEFAULT NULL,
  `urgency` varchar(255) DEFAULT NULL,
  `period` varchar(255) DEFAULT NULL,
  `worker_confirmed_at` timestamp NULL DEFAULT NULL,
  `completed_at` timestamp NULL DEFAULT NULL,
  `completion_note` text DEFAULT NULL,
  `completion_photo` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `work_orders`
--

INSERT INTO `work_orders` (`id`, `wo_number`, `type`, `repair_request_id`, `scheduled_maintenance_id`, `title`, `description`, `note`, `status`, `company_id`, `category_id`, `sub_category_id`, `branch_id`, `worker_id`, `created_by`, `schedule_date`, `urgency`, `period`, `worker_confirmed_at`, `completed_at`, `completion_note`, `completion_photo`, `created_at`, `updated_at`) VALUES
(1, 'SPK-REP-2026-0001', 'repair', 2, NULL, 'AC ga dingin', 'gak dingin', NULL, 'issued', 1, 1, 3, 1, 5, 4, '2026-05-21', 'high', NULL, NULL, NULL, NULL, NULL, '2026-05-23 00:41:12', '2026-05-23 00:41:12'),
(2, 'SPK-REP-2026-0002', 'repair', 1, NULL, 'AC Ruangan 301 Rusak', 'Jadi ketika dicoba untuk dihidupkan ac tidak bisa dingin dan ada 3 ac', NULL, 'issued', 1, 1, NULL, 1, 5, 4, '2026-05-13', 'high', NULL, NULL, NULL, NULL, NULL, '2026-05-23 00:41:30', '2026-05-23 00:41:30'),
(3, 'SPK-SCH-2026-0001', 'scheduled', NULL, 1, 'Cek AC', NULL, 'Cucu dan cek kerusakan', 'issued', 1, 1, NULL, NULL, 5, 4, '2026-05-18', NULL, 'monthly', NULL, NULL, NULL, NULL, '2026-05-26 02:26:24', '2026-05-26 02:26:24'),
(4, 'SPK-REP-2026-0003', 'repair', 3, NULL, 'AC Mati', 'Ac tiba2 mati .. padahal 2 hari lalu baru maintenance', NULL, 'issued', 2, 1, 3, 1, 5, 4, '2026-05-27', 'high', NULL, NULL, NULL, NULL, NULL, '2026-05-26 22:20:38', '2026-05-26 22:20:38'),
(5, 'SPK-REP-2026-0004', 'repair', 4, NULL, 'AC mati', 'ac tiba2 mati', NULL, 'issued', 2, 1, 3, 6, 10, 8, '2026-06-03', 'high', NULL, NULL, NULL, NULL, NULL, '2026-06-02 20:16:15', '2026-06-02 20:16:15');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `activity_logs`
--
ALTER TABLE `activity_logs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `assets`
--
ALTER TABLE `assets`
  ADD PRIMARY KEY (`id`),
  ADD KEY `assets_branch_id_foreign` (`branch_id`),
  ADD KEY `assets_user_id_foreign` (`user_id`),
  ADD KEY `assets_pic_id_foreign` (`pic_id`),
  ADD KEY `assets_category_id_foreign` (`category_id`),
  ADD KEY `assets_sub_category_id_foreign` (`sub_category_id`),
  ADD KEY `assets_room_id_foreign` (`room_id`),
  ADD KEY `fk_assets_company` (`company_id`);

--
-- Indexes for table `asset_categories`
--
ALTER TABLE `asset_categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `asset_sub_categories`
--
ALTER TABLE `asset_sub_categories`
  ADD PRIMARY KEY (`id`),
  ADD KEY `asset_sub_categories_asset_category_id_foreign` (`asset_category_id`);

--
-- Indexes for table `borrowings`
--
ALTER TABLE `borrowings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `borrowings_asset_id_foreign` (`asset_id`),
  ADD KEY `borrowings_request_branch_id_foreign` (`request_branch_id`),
  ADD KEY `borrowings_source_branch_id_foreign` (`source_branch_id`),
  ADD KEY `fk_borrowings_user` (`user_id`);

--
-- Indexes for table `branches`
--
ALTER TABLE `branches`
  ADD PRIMARY KEY (`id`),
  ADD KEY `branches_company_id_foreign` (`company_id`);

--
-- Indexes for table `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`),
  ADD KEY `cache_expiration_index` (`expiration`);

--
-- Indexes for table `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`),
  ADD KEY `cache_locks_expiration_index` (`expiration`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `companies`
--
ALTER TABLE `companies`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `material_requests`
--
ALTER TABLE `material_requests`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `notifications_user_id_foreign` (`user_id`);

--
-- Indexes for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`),
  ADD KEY `personal_access_tokens_expires_at_index` (`expires_at`);

--
-- Indexes for table `repair_requests`
--
ALTER TABLE `repair_requests`
  ADD PRIMARY KEY (`id`),
  ADD KEY `repair_requests_spk_sent_by_foreign` (`spk_sent_by`);

--
-- Indexes for table `rooms`
--
ALTER TABLE `rooms`
  ADD PRIMARY KEY (`id`),
  ADD KEY `rooms_branch_id_foreign` (`branch_id`);

--
-- Indexes for table `scheduled_maintenances`
--
ALTER TABLE `scheduled_maintenances`
  ADD PRIMARY KEY (`id`),
  ADD KEY `scheduled_maintenances_category_id_foreign` (`category_id`),
  ADD KEY `scheduled_maintenances_created_by_foreign` (`created_by`),
  ADD KEY `scheduled_maintenances_worker_id_foreign` (`worker_id`),
  ADD KEY `scheduled_maintenances_scheduled_sub_category_id_foreign` (`scheduled_sub_category_id`),
  ADD KEY `scheduled_maintenances_spk_sent_by_foreign` (`spk_sent_by`),
  ADD KEY `scheduled_maintenances_company_id_foreign` (`company_id`),
  ADD KEY `fk_sm_parent` (`parent_id`);

--
-- Indexes for table `scheduled_sub_categories`
--
ALTER TABLE `scheduled_sub_categories`
  ADD PRIMARY KEY (`id`),
  ADD KEY `scheduled_sub_categories_category_id_foreign` (`category_id`),
  ADD KEY `fk_ssc_company` (`company_id`);

--
-- Indexes for table `sub_categories`
--
ALTER TABLE `sub_categories`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sub_categories_category_id_foreign` (`category_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`),
  ADD KEY `users_company_id_foreign` (`company_id`),
  ADD KEY `users_branch_id_foreign` (`branch_id`),
  ADD KEY `users_category_id_foreign` (`category_id`);

--
-- Indexes for table `work_orders`
--
ALTER TABLE `work_orders`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `work_orders_wo_number_unique` (`wo_number`),
  ADD KEY `work_orders_repair_request_id_foreign` (`repair_request_id`),
  ADD KEY `work_orders_scheduled_maintenance_id_foreign` (`scheduled_maintenance_id`),
  ADD KEY `work_orders_company_id_foreign` (`company_id`),
  ADD KEY `work_orders_category_id_foreign` (`category_id`),
  ADD KEY `work_orders_sub_category_id_foreign` (`sub_category_id`),
  ADD KEY `work_orders_branch_id_foreign` (`branch_id`),
  ADD KEY `work_orders_worker_id_foreign` (`worker_id`),
  ADD KEY `work_orders_created_by_foreign` (`created_by`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `activity_logs`
--
ALTER TABLE `activity_logs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `assets`
--
ALTER TABLE `assets`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `asset_categories`
--
ALTER TABLE `asset_categories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `asset_sub_categories`
--
ALTER TABLE `asset_sub_categories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `borrowings`
--
ALTER TABLE `borrowings`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `branches`
--
ALTER TABLE `branches`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `companies`
--
ALTER TABLE `companies`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `material_requests`
--
ALTER TABLE `material_requests`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=42;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=193;

--
-- AUTO_INCREMENT for table `repair_requests`
--
ALTER TABLE `repair_requests`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `rooms`
--
ALTER TABLE `rooms`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `scheduled_maintenances`
--
ALTER TABLE `scheduled_maintenances`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `scheduled_sub_categories`
--
ALTER TABLE `scheduled_sub_categories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `sub_categories`
--
ALTER TABLE `sub_categories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `work_orders`
--
ALTER TABLE `work_orders`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `assets`
--
ALTER TABLE `assets`
  ADD CONSTRAINT `assets_branch_id_foreign` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`),
  ADD CONSTRAINT `assets_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `asset_categories` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `assets_pic_id_foreign` FOREIGN KEY (`pic_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `assets_room_id_foreign` FOREIGN KEY (`room_id`) REFERENCES `rooms` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `assets_sub_category_id_foreign` FOREIGN KEY (`sub_category_id`) REFERENCES `asset_sub_categories` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `assets_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `fk_assets_company` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`);

--
-- Constraints for table `asset_sub_categories`
--
ALTER TABLE `asset_sub_categories`
  ADD CONSTRAINT `asset_sub_categories_asset_category_id_foreign` FOREIGN KEY (`asset_category_id`) REFERENCES `asset_categories` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `borrowings`
--
ALTER TABLE `borrowings`
  ADD CONSTRAINT `borrowings_asset_id_foreign` FOREIGN KEY (`asset_id`) REFERENCES `assets` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `borrowings_request_branch_id_foreign` FOREIGN KEY (`request_branch_id`) REFERENCES `branches` (`id`),
  ADD CONSTRAINT `borrowings_source_branch_id_foreign` FOREIGN KEY (`source_branch_id`) REFERENCES `branches` (`id`),
  ADD CONSTRAINT `fk_borrowings_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `branches`
--
ALTER TABLE `branches`
  ADD CONSTRAINT `branches_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `notifications`
--
ALTER TABLE `notifications`
  ADD CONSTRAINT `notifications_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `repair_requests`
--
ALTER TABLE `repair_requests`
  ADD CONSTRAINT `repair_requests_spk_sent_by_foreign` FOREIGN KEY (`spk_sent_by`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `rooms`
--
ALTER TABLE `rooms`
  ADD CONSTRAINT `rooms_branch_id_foreign` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `scheduled_maintenances`
--
ALTER TABLE `scheduled_maintenances`
  ADD CONSTRAINT `fk_sm_parent` FOREIGN KEY (`parent_id`) REFERENCES `scheduled_maintenances` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `scheduled_maintenances_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `scheduled_maintenances_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`),
  ADD CONSTRAINT `scheduled_maintenances_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `scheduled_maintenances_scheduled_sub_category_id_foreign` FOREIGN KEY (`scheduled_sub_category_id`) REFERENCES `scheduled_sub_categories` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `scheduled_maintenances_spk_sent_by_foreign` FOREIGN KEY (`spk_sent_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `scheduled_maintenances_worker_id_foreign` FOREIGN KEY (`worker_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `scheduled_sub_categories`
--
ALTER TABLE `scheduled_sub_categories`
  ADD CONSTRAINT `fk_ssc_company` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `scheduled_sub_categories_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `sub_categories`
--
ALTER TABLE `sub_categories`
  ADD CONSTRAINT `sub_categories_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_branch_id_foreign` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `users_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`),
  ADD CONSTRAINT `users_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `work_orders`
--
ALTER TABLE `work_orders`
  ADD CONSTRAINT `work_orders_branch_id_foreign` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`),
  ADD CONSTRAINT `work_orders_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`),
  ADD CONSTRAINT `work_orders_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`),
  ADD CONSTRAINT `work_orders_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `work_orders_repair_request_id_foreign` FOREIGN KEY (`repair_request_id`) REFERENCES `repair_requests` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `work_orders_scheduled_maintenance_id_foreign` FOREIGN KEY (`scheduled_maintenance_id`) REFERENCES `scheduled_maintenances` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `work_orders_sub_category_id_foreign` FOREIGN KEY (`sub_category_id`) REFERENCES `sub_categories` (`id`),
  ADD CONSTRAINT `work_orders_worker_id_foreign` FOREIGN KEY (`worker_id`) REFERENCES `users` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
