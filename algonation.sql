-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Sep 08, 2026 at 03:33 PM
-- Server version: 8.0.30
-- PHP Version: 8.3.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `algonation`
--

-- --------------------------------------------------------

--
-- Table structure for table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `cache`
--

INSERT INTO `cache` (`key`, `value`, `expiration`) VALUES
('algo-nation-cache-514931aef739bf2574234d22818586a9', 'i:3;', 1788866843),
('algo-nation-cache-514931aef739bf2574234d22818586a9:timer', 'i:1788866843;', 1788866843),
('algo-nation-cache-57335dc4d2dfdb8fe64bed675b99f7d2', 'i:3;', 1788866843),
('algo-nation-cache-57335dc4d2dfdb8fe64bed675b99f7d2:timer', 'i:1788866843;', 1788866843),
('algo-nation-cache-5c785c036466adea360111aa28563bfd556b5fba', 'i:2;', 1788864018),
('algo-nation-cache-5c785c036466adea360111aa28563bfd556b5fba:timer', 'i:1788864018;', 1788864018),
('algo-nation-cache-b4c654695f6bc2661b10fa4755b07271', 'i:1;', 1788842543),
('algo-nation-cache-b4c654695f6bc2661b10fa4755b07271:timer', 'i:1788842543;', 1788842543),
('algo-nation-cache-fd471a4a2e6cc225649450976c31d8cc', 'i:3;', 1788838861),
('algo-nation-cache-fd471a4a2e6cc225649450976c31d8cc:timer', 'i:1788838861;', 1788838861),
('algo-nation-cache-forgot_password:127.0.0.1:coba-satu-email-1788869087457@example.com', 'i:5;', 1788869148),
('algo-nation-cache-forgot_password:127.0.0.1:coba-satu-email-1788869087457@example.com:timer', 'i:1788869148;', 1788869148),
('algo-nation-cache-forgot_password:127.0.0.1:count@test.com', 'i:2;', 1788866322),
('algo-nation-cache-forgot_password:127.0.0.1:count@test.com:timer', 'i:1788866322;', 1788866322),
('algo-nation-cache-forgot_password:127.0.0.1:final-test@example.com', 'i:1;', 1788866609),
('algo-nation-cache-forgot_password:127.0.0.1:final-test@example.com:timer', 'i:1788866609;', 1788866609),
('algo-nation-cache-forgot_password:127.0.0.1:flow-test@example.com', 'i:5;', 1788866272),
('algo-nation-cache-forgot_password:127.0.0.1:flow-test@example.com:timer', 'i:1788866272;', 1788866272),
('algo-nation-cache-forgot_password:127.0.0.1:priatuaqw@gmail.com', 'i:1;', 1788880724),
('algo-nation-cache-forgot_password:127.0.0.1:priatuaqw@gmail.com:timer', 'i:1788880724;', 1788880724),
('algo-nation-cache-forgot_password:127.0.0.1:rate@test.com', 'i:5;', 1788865982),
('algo-nation-cache-forgot_password:127.0.0.1:rate@test.com:timer', 'i:1788865982;', 1788865982),
('algo-nation-cache-forgot_password:127.0.0.1:ratelimit-browser-1788868606934@example.com', 'i:5;', 1788868668),
('algo-nation-cache-forgot_password:127.0.0.1:ratelimit-browser-1788868606934@example.com:timer', 'i:1788868668;', 1788868668),
('algo-nation-cache-forgot_password:127.0.0.1:ratelimit-fixed@test.com', 'i:1;', 1788866103),
('algo-nation-cache-forgot_password:127.0.0.1:ratelimit-fixed@test.com:timer', 'i:1788866103;', 1788866103),
('algo-nation-cache-forgot_password:127.0.0.1:ratelimit-http-185425@example.com', 'i:5;', 1788868526),
('algo-nation-cache-forgot_password:127.0.0.1:ratelimit-http-185425@example.com:timer', 'i:1788868526;', 1788868526),
('algo-nation-cache-forgot_password:127.0.0.1:test@example.com', 'i:1;', 1788865970),
('algo-nation-cache-forgot_password:127.0.0.1:test@example.com:timer', 'i:1788865970;', 1788865970),
('algo-nation-cache-forgot_password:127.0.0.1:unique0@test.com', 'i:1;', 1788866043),
('algo-nation-cache-forgot_password:127.0.0.1:unique0@test.com:timer', 'i:1788866043;', 1788866043),
('algo-nation-cache-forgot_password:127.0.0.1:unique1@test.com', 'i:1;', 1788866045),
('algo-nation-cache-forgot_password:127.0.0.1:unique1@test.com:timer', 'i:1788866045;', 1788866045),
('algo-nation-cache-forgot_password:127.0.0.1:unique2@test.com', 'i:1;', 1788866048),
('algo-nation-cache-forgot_password:127.0.0.1:unique2@test.com:timer', 'i:1788866048;', 1788866048),
('algo-nation-cache-forgot_password:127.0.0.1:yyyy150909@gmail.com', 'i:4;', 1788880978),
('algo-nation-cache-forgot_password:127.0.0.1:yyyy150909@gmail.com:timer', 'i:1788880977;', 1788880977),
('algo-nation-cache-forgot_password:blocked:127.0.0.1:priatuaqw@gmail.com:timer', 'i:1788871312;', 1788871312),
('algo-nation-cache-forgot_password:blocked:127.0.0.1:yyyy150909@gmail.com', 'i:1;', 1788882764),
('algo-nation-cache-forgot_password:blocked:127.0.0.1:yyyy150909@gmail.com:timer', 'i:1788882764;', 1788882764),
('algo-nation-cache-forgot_password:blocked:ip:127.0.0.1', 'i:1;', 1788882765),
('algo-nation-cache-forgot_password:blocked:ip:127.0.0.1:timer', 'i:1788882765;', 1788882765),
('algo-nation-cache-forgot_password:ip:127.0.0.1', 'i:4;', 1788880978),
('algo-nation-cache-forgot_password:ip:127.0.0.1:timer', 'i:1788880978;', 1788880978),
('algo-nation-cache-opencode-key', 'i:6;', 1788866987),
('algo-nation-cache-opencode-key:timer', 'i:1788866987;', 1788866987);

-- --------------------------------------------------------

--
-- Table structure for table `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `featured_products`
--

CREATE TABLE `featured_products` (
  `id` bigint UNSIGNED NOT NULL,
  `product_id` bigint UNSIGNED NOT NULL,
  `is_featured` tinyint(1) NOT NULL DEFAULT '1',
  `sort_order` int UNSIGNED NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `featured_products`
--

INSERT INTO `featured_products` (`id`, `product_id`, `is_featured`, `sort_order`, `created_at`, `updated_at`) VALUES
(5, 31, 1, 0, '2026-09-07 11:22:22', '2026-09-07 11:22:22'),
(6, 30, 1, 1, '2026-09-08 05:51:45', '2026-09-08 05:52:37'),
(7, 28, 1, 0, '2026-09-08 06:23:23', '2026-09-08 06:23:23'),
(8, 23, 1, 0, '2026-09-08 06:23:37', '2026-09-08 06:23:37'),
(9, 29, 1, 0, '2026-09-08 12:53:31', '2026-09-08 12:53:31'),
(10, 22, 1, 0, '2026-09-08 13:04:43', '2026-09-08 13:04:43');

-- --------------------------------------------------------

--
-- Table structure for table `flash_sales`
--

CREATE TABLE `flash_sales` (
  `id` bigint UNSIGNED NOT NULL,
  `product_id` bigint UNSIGNED NOT NULL,
  `normal_price` decimal(12,2) NOT NULL,
  `sale_price` decimal(12,2) NOT NULL,
  `discount_percentage` decimal(5,2) NOT NULL,
  `stock` int UNSIGNED NOT NULL,
  `start_at` datetime NOT NULL,
  `end_at` datetime NOT NULL,
  `status` enum('scheduled','active','expired','inactive') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'scheduled',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `flash_sales`
--

INSERT INTO `flash_sales` (`id`, `product_id`, `normal_price`, `sale_price`, `discount_percentage`, `stock`, `start_at`, `end_at`, `status`, `created_at`, `updated_at`) VALUES
(1, 31, '950000.00', '499999.99', '47.37', 6, '2026-09-03 14:13:00', '2026-09-15 17:15:00', 'active', '2026-09-03 07:13:15', '2026-09-08 13:34:12'),
(3, 24, '1890000.00', '1000000.00', '47.09', 7, '2026-09-03 14:13:00', '2026-09-15 17:15:00', 'active', '2026-09-06 16:16:11', '2026-09-07 09:30:55'),
(4, 19, '500000.00', '200000.00', '60.00', 5, '2026-09-08 19:52:00', '2026-10-02 19:52:00', 'active', '2026-09-08 12:53:06', '2026-09-08 12:53:07');

-- --------------------------------------------------------

--
-- Table structure for table `international_regions`
--

CREATE TABLE `international_regions` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `rate_per_kg` decimal(12,2) NOT NULL DEFAULT '0.00',
  `min_charge` decimal(12,2) NOT NULL DEFAULT '0.00',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `international_regions`
--

INSERT INTO `international_regions` (`id`, `name`, `rate_per_kg`, `min_charge`, `created_at`, `updated_at`) VALUES
(1, 'Asia', '150000.00', '100000.00', '2026-09-07 10:59:25', '2026-09-07 10:59:25'),
(2, 'Asia Tenggara', '90000.00', '75000.00', '2026-09-07 10:59:25', '2026-09-07 10:59:25'),
(3, 'Eropa', '250000.00', '200000.00', '2026-09-07 10:59:25', '2026-09-07 10:59:25'),
(4, 'Amerika Utara', '300000.00', '250000.00', '2026-09-07 10:59:25', '2026-09-07 10:59:25');

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int UNSIGNED NOT NULL,
  `migration` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2025_01_01_000001_add_username_and_role_to_users_table', 1),
(5, '2025_01_01_000002_create_products_table', 1),
(6, '2025_01_01_000003_create_product_variants_table', 1),
(7, '2025_01_01_000004_create_transactions_table', 1),
(8, '2025_01_01_000005_create_transaction_details_table', 1),
(11, '2026_09_03_000001_create_flash_sales_table', 2),
(12, '2026_09_06_000001_add_midtrans_fields_to_transactions_table', 3),
(13, '2026_09_06_000002_add_payment_status_and_paid_at_to_transactions_table', 4),
(14, '2026_09_07_000001_add_shipping_fields_to_transactions_table', 5),
(15, '2026_09_07_000002_add_payment_due_at_to_transactions_table', 6),
(16, '2026_09_07_000003_add_weight_and_dimensions_to_products_table', 7),
(17, '2026_09_07_000004_create_featured_products_table', 7),
(18, '2026_09_07_000005_create_shipping_tables', 7),
(19, '2026_09_07_000006_add_shipping_snapshot_to_transactions_table', 7);

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `password_reset_tokens`
--

INSERT INTO `password_reset_tokens` (`email`, `token`, `created_at`) VALUES
('flowtest@example.com', '$2y$12$0.XNxO/8zK87jB.KjemYNeQPz/GbmTg7kP2wKZik1R3lMSm32HW42', '2026-09-08 04:41:24'),
('priatuaqw@gmail.com', '$2y$12$hXx3nOynZS12uwEMdaxQ5OH.ooqEuAlsDy8j3ZfusocMJj4BZ.jJ6', '2026-09-08 15:17:45'),
('yyyy150909@gmail.com', '$2y$12$3lzkvIymtQ6w7znITgqjx.NEtYWwqJ.46HUlUE5zhel20hx6KnSwG', '2026-09-08 15:22:35');

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `category` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `image` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `stock` int NOT NULL DEFAULT '0',
  `price` decimal(12,2) NOT NULL DEFAULT '0.00',
  `weight` decimal(10,2) DEFAULT NULL,
  `length` decimal(10,2) DEFAULT NULL,
  `width` decimal(10,2) DEFAULT NULL,
  `height` decimal(10,2) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `name`, `category`, `image`, `description`, `stock`, `price`, `weight`, `length`, `width`, `height`, `created_at`, `updated_at`) VALUES
(1, 'Celana Jeans', 'Bottoms', 'products/Fbc8UQUgpwvCxmiR8fbJRwMlsbQ8ZiXPk7s7LYhi.jpg', 'celana jeans kekinian dan terbaru 2026', 8, '300000.00', NULL, NULL, NULL, NULL, '2026-08-05 04:55:23', '2026-09-08 15:28:05'),
(16, 'Hoodie Black mamba', 'Outerwear', 'products/MMGkdTmHX3HtdQU73bMmAkoCqlcyPaueJFXygFQ7.jpg', NULL, 101, '250000.00', NULL, NULL, NULL, NULL, '2026-08-19 21:04:45', '2026-09-07 04:55:22'),
(17, 'Topi Merah', 'Hats', 'products/dhDNPcGSdGvkrYOuEVIHnf0JHwwnpnvAIhpRA4cd.jpg', 'topi merah polos', 52, '75000.00', '300.00', '10.00', '13.00', '15.00', '2026-08-19 21:21:58', '2026-09-08 05:15:28'),
(19, 'Sepatu Kulit', 'Footwear', 'products/CPau4usMBGuNUxLdnZrqYjkdlY7h1yp4OWAIAUqJ.jpg', 'sepatu kulit warna hitam', 98, '500000.00', NULL, NULL, NULL, NULL, '2026-08-19 21:28:48', '2026-09-07 08:15:53'),
(22, 'Sepatu lari', 'Footwear', 'products/CSC4ir1CgWbjiyjTLsQdKz2hFnn6eUP2jlwko2Dh.jpg', NULL, 101, '400000.00', NULL, NULL, NULL, NULL, '2026-08-25 03:54:46', '2026-09-07 08:23:00'),
(23, 'Nike Air Jordan Wanita', 'Footwear', 'products/ucpvuE3z6uGYQeExweXKlTtZLVJifmtGdT8PNdvP.jpg', 'Nike Air Jordan Gorpcore Indie Sneakers Wanita white pink', 97, '700000.00', NULL, NULL, NULL, NULL, '2026-08-25 07:42:33', '2026-08-27 04:57:25'),
(24, 'Stay Bag', 'Bags', 'products/JApW0FN7fcX1esggJWOY8gNWTVBzKeZsTBvpuMpk.webp', 'Stay Bag Black and Brown', 993, '1890000.00', '300.00', '40.00', '30.00', '20.00', '2026-08-25 07:45:00', '2026-09-08 01:53:32'),
(25, 'Berto\'s Hat', 'Hats', 'products/F0BSfxSn0nM0IodKu7zjgWpzbQ2EtojF51T3g5EM.webp', NULL, 109, '1250000.00', NULL, NULL, NULL, NULL, '2026-08-25 07:52:39', '2026-09-08 01:53:46'),
(26, 'Kaos Kasual Pria', 'Casual T-Shirt', 'products/2j4rh4MDlV84hAvvH9Rrn3DY1vIvZ6CtnDKmPazm.webp', 'Kaos kasual pria warna putih', 99, '75000.00', NULL, NULL, NULL, NULL, '2026-08-25 09:19:11', '2026-08-27 04:58:01'),
(27, 'Kaos Kasual Pria', 'Casual T-Shirt', 'products/pNNyFcN4llcCid6fn0byzEKVyyO97EypzCctW8Tk.webp', 'Kaos kasual pria warna putih', 100, '75000.00', NULL, NULL, NULL, NULL, '2026-08-25 09:21:02', '2026-08-25 09:21:02'),
(28, 'Jaket The North Face', 'Outerwear', 'products/FjsxbEyu7IDrip2ezo6aU89WXLhslTyB0XkDFRbl.jpg', NULL, 101, '850000.00', NULL, NULL, NULL, NULL, '2026-08-25 09:29:03', '2026-09-08 06:46:54'),
(29, 'Tas Coklat', 'Bags', 'products/htNA5swNtrpJT9UXmZrcibIZzJIGX10EF7Jhpwks.jpg', NULL, 100, '250000.00', NULL, NULL, NULL, NULL, '2026-08-25 09:30:23', '2026-08-25 09:30:23'),
(30, 'Jas Hitam', 'Formal Wear', 'products/Q4c1RfIgfiIEZI0HTeQhgbie0mxiXwujuRhHs8zX.jpg', NULL, 100, '500000.00', NULL, NULL, NULL, NULL, '2026-08-25 09:44:48', '2026-08-25 09:44:48'),
(31, 'Sepatu Nike Merah', 'Footwear', 'products/SZIXgjMhAMTxU34ajEJUNd2HSVcR8zrmNSxJ7poh.jpg', NULL, 1002, '950000.00', NULL, NULL, NULL, NULL, '2026-08-27 06:56:09', '2026-09-08 13:34:30'),
(32, 'Jaket Kulit Hitam', 'Outerwear', 'products/H1PpCwgLIpbSgkHhWGq8D7s9XIXGTbJCwxwPtPQS.jpg', NULL, 100, '500000.00', NULL, NULL, NULL, NULL, '2026-08-27 06:58:25', '2026-09-06 17:56:31');

-- --------------------------------------------------------

--
-- Table structure for table `product_variants`
--

CREATE TABLE `product_variants` (
  `id` bigint UNSIGNED NOT NULL,
  `product_id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `stock` int NOT NULL DEFAULT '0',
  `price` decimal(12,2) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `product_variants`
--

INSERT INTO `product_variants` (`id`, `product_id`, `name`, `stock`, `price`, `created_at`, `updated_at`) VALUES
(1, 1, 'Warna Putih', 5, NULL, '2026-08-05 04:55:23', '2026-08-05 04:55:23'),
(2, 1, 'Warna Abu', 7, NULL, '2026-08-05 04:55:23', '2026-08-19 20:55:00');

-- --------------------------------------------------------

--
-- Table structure for table `shipping_countries`
--

CREATE TABLE `shipping_countries` (
  `id` bigint UNSIGNED NOT NULL,
  `region_id` bigint UNSIGNED NOT NULL,
  `country` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `rate_per_kg` decimal(12,2) DEFAULT NULL,
  `min_charge` decimal(12,2) DEFAULT NULL,
  `active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `shipping_countries`
--

INSERT INTO `shipping_countries` (`id`, `region_id`, `country`, `rate_per_kg`, `min_charge`, `active`, `created_at`, `updated_at`) VALUES
(1, 2, 'Singapura', '90000.00', '75000.00', 1, '2026-09-07 10:59:25', '2026-09-07 10:59:25'),
(2, 2, 'Malaysia', '95000.00', '75000.00', 1, '2026-09-07 10:59:25', '2026-09-07 10:59:25'),
(3, 2, 'Thailand', '110000.00', '90000.00', 1, '2026-09-07 10:59:25', '2026-09-07 10:59:25'),
(4, 1, 'Jepang', '180000.00', '150000.00', 1, '2026-09-07 10:59:25', '2026-09-07 10:59:25'),
(5, 1, 'Korea Selatan', '180000.00', '150000.00', 1, '2026-09-07 10:59:25', '2026-09-07 10:59:25'),
(6, 3, 'Belanda', '250000.00', '200000.00', 1, '2026-09-07 10:59:25', '2026-09-07 10:59:25'),
(7, 3, 'Inggris', '280000.00', '220000.00', 1, '2026-09-07 10:59:25', '2026-09-07 10:59:25'),
(8, 4, 'Amerika Serikat', '300000.00', '250000.00', 1, '2026-09-07 10:59:25', '2026-09-07 10:59:25');

-- --------------------------------------------------------

--
-- Table structure for table `shipping_couriers`
--

CREATE TABLE `shipping_couriers` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` enum('domestic','international') COLLATE utf8mb4_unicode_ci NOT NULL,
  `active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `shipping_couriers`
--

INSERT INTO `shipping_couriers` (`id`, `name`, `type`, `active`, `created_at`, `updated_at`) VALUES
(1, 'JNE', 'domestic', 1, '2026-09-07 10:59:25', '2026-09-07 10:59:25'),
(2, 'J&T Express', 'domestic', 1, '2026-09-07 10:59:25', '2026-09-07 10:59:25'),
(3, 'DHL Express', 'international', 1, '2026-09-07 10:59:25', '2026-09-07 10:59:25'),
(4, 'POS Indonesia', 'domestic', 1, '2026-09-07 10:59:25', '2026-09-07 10:59:25');

-- --------------------------------------------------------

--
-- Table structure for table `shipping_settings`
--

CREATE TABLE `shipping_settings` (
  `id` bigint UNSIGNED NOT NULL,
  `origin_country` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Indonesia',
  `origin_city` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `origin_latitude` decimal(10,7) DEFAULT NULL,
  `origin_longitude` decimal(10,7) DEFAULT NULL,
  `routing_provider` enum('none','osrm','google','here','mapbox') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'none',
  `enable_routing` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `shipping_settings`
--

INSERT INTO `shipping_settings` (`id`, `origin_country`, `origin_city`, `origin_latitude`, `origin_longitude`, `routing_provider`, `enable_routing`, `created_at`, `updated_at`) VALUES
(1, 'Indonesia', 'Jakarta', '-6.2000000', '106.8166660', 'none', 0, '2026-09-07 10:59:25', '2026-09-07 10:59:25');

-- --------------------------------------------------------

--
-- Table structure for table `shipping_zones`
--

CREATE TABLE `shipping_zones` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `min_distance_km` int UNSIGNED NOT NULL DEFAULT '0',
  `max_distance_km` int UNSIGNED DEFAULT NULL,
  `rate_per_kg` decimal(12,2) NOT NULL DEFAULT '0.00',
  `min_charge` decimal(12,2) NOT NULL DEFAULT '0.00',
  `active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `shipping_zones`
--

INSERT INTO `shipping_zones` (`id`, `name`, `min_distance_km`, `max_distance_km`, `rate_per_kg`, `min_charge`, `active`, `created_at`, `updated_at`) VALUES
(1, 'Zona 1', 0, 10, '10000.00', '10000.00', 1, '2026-09-07 10:59:25', '2026-09-08 12:28:03'),
(2, 'Zona 2', 10, 20, '12000.00', '12000.00', 1, '2026-09-07 10:59:25', '2026-09-08 05:21:14'),
(3, 'Zona 3', 21, 30, '15000.00', '15000.00', 1, '2026-09-07 10:59:25', '2026-09-08 05:23:13'),
(4, 'Zona 4', 31, 100, '18000.00', '18000.00', 1, '2026-09-07 10:59:25', '2026-09-08 05:41:22'),
(8, 'Zona 5', 101, 200, '22000.00', '22000.00', 1, '2026-09-08 05:43:30', '2026-09-08 05:43:30'),
(9, 'Zona 6', 201, 500, '25000.00', '25000.00', 1, '2026-09-08 05:49:48', '2026-09-08 05:49:48'),
(10, 'Zona 7', 501, 700, '30000.00', '30000.00', 1, '2026-09-08 06:01:36', '2026-09-08 06:01:36'),
(11, 'Zona 8', 701, 1060, '35000.00', '35000.00', 1, '2026-09-08 06:02:46', '2026-09-08 06:02:46'),
(12, 'Zona 9', 1061, NULL, '100000.00', '60000.00', 1, '2026-09-08 12:26:07', '2026-09-08 12:26:24');

-- --------------------------------------------------------

--
-- Table structure for table `transactions`
--

CREATE TABLE `transactions` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `total_price` decimal(12,2) NOT NULL DEFAULT '0.00',
  `shipping_cost` decimal(12,2) NOT NULL DEFAULT '0.00',
  `payment_method` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'COD',
  `midtrans_order_id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `midtrans_snap_token` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `shipping_address` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `shipping_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `origin_country` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `destination_country` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `destination_city` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `destination_state` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `destination_postal_code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `shipping_distance` decimal(12,2) DEFAULT NULL,
  `actual_weight` decimal(12,2) DEFAULT NULL,
  `volumetric_weight` decimal(12,2) DEFAULT NULL,
  `billable_weight` decimal(12,2) DEFAULT NULL,
  `shipping_zone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `payment_status` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `shipping_courier` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tracking_number` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `shipping_status` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'menunggu_diproses',
  `estimated_delivery_start` date DEFAULT NULL,
  `estimated_delivery_end` date DEFAULT NULL,
  `shipped_at` timestamp NULL DEFAULT NULL,
  `delivered_at` timestamp NULL DEFAULT NULL,
  `shipping_updated_at` timestamp NULL DEFAULT NULL,
  `paid_at` timestamp NULL DEFAULT NULL,
  `payment_due_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `transactions`
--

INSERT INTO `transactions` (`id`, `user_id`, `total_price`, `shipping_cost`, `payment_method`, `midtrans_order_id`, `midtrans_snap_token`, `shipping_address`, `shipping_type`, `origin_country`, `destination_country`, `destination_city`, `destination_state`, `destination_postal_code`, `shipping_distance`, `actual_weight`, `volumetric_weight`, `billable_weight`, `shipping_zone`, `status`, `payment_status`, `shipping_courier`, `tracking_number`, `shipping_status`, `estimated_delivery_start`, `estimated_delivery_end`, `shipped_at`, `delivered_at`, `shipping_updated_at`, `paid_at`, `payment_due_at`, `created_at`, `updated_at`) VALUES
(53, 11, '250000.00', '25000.00', 'midtrans', 'ORDER-11-1788715772', 'b65ab648-ddf6-47d0-9e32-7b109ff7face', 'user\n0895347660703\ndfv, jember 40123', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'cancelled', 'cancelled', 'POS', 'JNE123456789', 'diserahkan_ke_kurir', '2026-09-09', '2026-09-11', '2026-09-06 17:00:00', NULL, '2026-09-06 17:50:03', '2026-09-06 17:30:27', NULL, '2026-09-06 17:29:33', '2026-09-06 17:51:37'),
(58, 11, '499999.99', '25000.00', 'midtrans', 'ORDER-11-1788718655', 'd7821417-e3f0-4d7a-8333-01428571e1f5', 'user\n0895347660703\ndfv, jember 40123', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'processing', 'paid', 'J&amp;T', 'JNE0987263', 'diserahkan_ke_kurir', '2026-09-07', '2026-09-10', '2026-09-06 17:00:00', NULL, '2026-09-06 18:20:31', '2026-09-06 18:19:13', NULL, '2026-09-06 18:17:36', '2026-09-06 18:20:31'),
(59, 11, '8000000.00', '0.00', 'midtrans', 'ORDER-11-1788721058', 'a29ec322-7317-458b-b1b7-788f111a20bc', 'user\n0895347660703\ndfv, jember 40123', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'cancelled', 'cancelled', NULL, NULL, 'dibatalkan', NULL, NULL, NULL, NULL, '2026-09-07 08:15:53', '2026-09-06 18:58:31', NULL, '2026-09-06 18:57:39', '2026-09-07 08:15:53'),
(60, 11, '1000000.00', '0.00', 'midtrans', 'ORDER-11-1788752847', '5a06ce94-6c75-48f0-8277-b1b83e170d5b', 'user\n0895347660703\njaan, jember 40123', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'completed', 'paid', NULL, 'JNE1234544', 'pesanan_diterima', '2026-09-07', '2026-09-08', '2026-09-07 17:00:00', '2026-09-07 03:50:49', '2026-09-07 03:50:49', '2026-09-07 03:48:14', NULL, '2026-09-07 03:47:28', '2026-09-07 03:50:49'),
(61, 11, '250000.00', '25000.00', 'midtrans', 'ORDER-11-1788756556', '3f5c8ea5-a222-434d-9d00-18075d9336da', 'user\n0895347660703\njember, jember 40123', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'cancelled', 'cancelled', NULL, NULL, 'dibatalkan', NULL, NULL, NULL, NULL, '2026-09-07 04:55:22', NULL, NULL, '2026-09-07 04:49:17', '2026-09-07 04:55:22'),
(62, 11, '499999.99', '25000.00', 'midtrans', 'ORDER-11-1788768855', 'bd72881f-1836-41f6-b1e4-7be959b89640', 'user\n0895347660703\njember, jember 40123', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'cancelled', 'cancelled', NULL, NULL, 'dibatalkan', NULL, NULL, NULL, NULL, '2026-09-07 08:15:12', NULL, NULL, '2026-09-07 08:14:16', '2026-09-07 08:15:12'),
(63, 11, '400000.00', '25000.00', 'midtrans', 'ORDER-11-1788768983', 'a0acf82e-0009-49fa-ac0d-819e53193c5b', 'user\n0895347660703\njember, jember 40123', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'cancelled', 'cancelled', NULL, NULL, 'dibatalkan', NULL, NULL, NULL, NULL, '2026-09-07 08:23:00', NULL, NULL, '2026-09-07 08:16:24', '2026-09-07 08:23:00'),
(64, 11, '500000.00', '0.00', 'midtrans', 'ORDER-11-1788771496', 'fb7e3cf3-3310-4de0-9432-231239fd4e88', 'user\n0895347660703\njember, jember 40123', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'cancelled', 'cancelled', NULL, NULL, 'dibatalkan', NULL, NULL, NULL, NULL, '2026-09-07 09:23:33', NULL, NULL, '2026-09-07 08:58:17', '2026-09-07 09:23:33'),
(65, 11, '1000000.00', '0.00', 'midtrans', 'ORDER-11-1788773030', 'd8e327dc-fcaf-427e-a0fc-7396d171fd42', 'user\n0895347660703\njember, jember 40123', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'cancelled', 'cancelled', NULL, NULL, 'dibatalkan', NULL, NULL, NULL, NULL, '2026-09-07 09:30:55', NULL, '2026-09-07 09:38:51', '2026-09-07 09:23:51', '2026-09-07 09:30:55'),
(66, 11, '499999.99', '25000.00', 'midtrans', 'ORDER-11-1788773496', '45bcc1cd-fae8-4906-8b5d-d02794dc7657', 'user\n0895347660703\njember, jember 40123', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'cancelled', 'cancelled', NULL, NULL, 'dibatalkan', NULL, NULL, NULL, NULL, '2026-09-07 09:32:31', NULL, '2026-09-07 09:46:37', '2026-09-07 09:31:37', '2026-09-07 09:32:31'),
(67, 11, '499999.99', '25000.00', 'midtrans', 'ORDER-11-1788773583', 'd1e9b3af-0b0f-42d1-b269-4018494a9190', 'user\n0895347660703\njember, jember 40123', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'cancelled', 'cancelled', NULL, NULL, 'dibatalkan', NULL, NULL, NULL, NULL, '2026-09-07 09:35:13', NULL, '2026-09-07 09:48:04', '2026-09-07 09:33:04', '2026-09-07 09:35:13'),
(68, 11, '499999.99', '25000.00', 'midtrans', 'ORDER-11-1788773897', 'f51b6670-8505-498e-acad-42da81dc7798', 'user\n0895347660703\njember, jember 40123', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'cancelled', 'expired', NULL, NULL, 'dibatalkan', NULL, NULL, NULL, NULL, '2026-09-07 10:09:26', NULL, '2026-09-07 09:53:18', '2026-09-07 09:38:18', '2026-09-07 10:09:26'),
(69, 18, '198000.00', '25000.00', 'midtrans', NULL, NULL, 'Prof. Jeromy Wisozk\n089642981971\nJl. Lockman Rapids No. 94, O\'Konview', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'completed', 'pending', NULL, NULL, 'menunggu_diproses', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-07-13 06:59:29', '2026-07-24 10:59:29'),
(70, 16, '2450000.00', '0.00', 'midtrans', NULL, NULL, 'Maryjane West DDS\n086172456515\nJl. Nella Lights No. 185, Port Hester', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'completed', 'pending', NULL, NULL, 'menunggu_diproses', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-08-08 12:59:29', '2026-07-14 10:59:29'),
(72, 18, '198000.00', '25000.00', 'midtrans', NULL, NULL, 'Prof. Jeromy Wisozk\n089948566645\nJl. Padberg Loop No. 102, South Ozella', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'completed', 'pending', NULL, NULL, 'menunggu_diproses', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-08-04 00:59:29', '2026-08-11 10:59:29'),
(73, 19, '5707000.00', '0.00', 'midtrans', NULL, NULL, 'Dr. Dee McGlynn\n085412403747\nJl. Dillon Roads No. 42, North Madelyn', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'cancelled', 'pending', NULL, NULL, 'menunggu_diproses', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-09-05 05:59:29', '2026-09-06 10:59:29'),
(74, 19, '11379000.00', '0.00', 'midtrans', NULL, NULL, 'Dr. Dee McGlynn\n081784966745\nJl. Eriberto Circle No. 84, Smithamhaven', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'processing', 'pending', NULL, NULL, 'menunggu_diproses', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-08-08 01:59:29', '2026-09-02 10:59:29'),
(75, 14, '2450000.00', '0.00', 'midtrans', NULL, NULL, 'Kian Kilback\n087476510509\nJl. Bulah Branch No. 153, North Danechester', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'cancelled', 'pending', NULL, NULL, 'menunggu_diproses', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-09-02 01:59:29', '2026-07-19 10:59:29'),
(76, 16, '2345000.00', '0.00', 'midtrans', NULL, NULL, 'Maryjane West DDS\n085721669749\nJl. Ernser Summit No. 55, Lake Angelitamouth', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'cancelled', 'pending', NULL, NULL, 'menunggu_diproses', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-08-01 10:59:29', '2026-09-04 10:59:29'),
(78, 16, '1648000.00', '0.00', 'midtrans', NULL, NULL, 'Maryjane West DDS\n083809732055\nJl. Marks Neck No. 17, Aufderharchester', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'processing', 'pending', NULL, NULL, 'menunggu_diproses', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-07-29 00:59:29', '2026-07-15 10:59:29'),
(79, 17, '1405000.00', '0.00', 'midtrans', NULL, NULL, 'Renee Carroll\n086138063879\nJl. Jules Green No. 92, Hesselburgh', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'completed', 'pending', NULL, NULL, 'menunggu_diproses', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-08-10 18:59:29', '2026-08-02 10:59:29'),
(80, 19, '3898000.00', '0.00', 'midtrans', NULL, NULL, 'Dr. Dee McGlynn\n089522061288\nJl. Stracke Points No. 4, Randiview', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'completed', 'pending', NULL, NULL, 'menunggu_diproses', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-07-12 04:59:30', '2026-08-10 10:59:30'),
(81, 21, '477000.00', '25000.00', 'midtrans', NULL, NULL, 'Casper Schaefer MD\n083269125877\nJl. Gleichner Lake No. 140, Travisland', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'completed', 'pending', NULL, NULL, 'menunggu_diproses', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-09-03 21:07:55', '2026-08-27 11:07:55'),
(83, 25, '2648000.00', '0.00', 'midtrans', NULL, NULL, 'Dr. Guadalupe Daugherty\n082812941718\nJl. Wilkinson Cliffs No. 143, Laurineport', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'completed', 'pending', NULL, NULL, 'menunggu_diproses', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-08-11 15:07:55', '2026-07-22 11:07:55'),
(84, 21, '2300000.00', '0.00', 'midtrans', NULL, NULL, 'Casper Schaefer MD\n089581536292\nJl. Adams Courts No. 117, Earleneborough', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'completed', 'pending', NULL, NULL, 'menunggu_diproses', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-08-02 20:07:55', '2026-08-18 11:07:55'),
(86, 24, '6758000.00', '0.00', 'midtrans', NULL, NULL, 'Frederik Schulist\n082021231398\nJl. Garth Rue No. 189, New Terrance', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'completed', 'pending', NULL, NULL, 'menunggu_diproses', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-07-31 15:07:55', '2026-08-02 11:07:55'),
(87, 24, '3648000.00', '0.00', 'midtrans', NULL, NULL, 'Frederik Schulist\n080761007662\nJl. Adelia Park No. 100, Fletahaven', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'completed', 'pending', NULL, NULL, 'menunggu_diproses', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-07-25 10:07:55', '2026-08-12 11:07:55'),
(88, 22, '1065000.00', '0.00', 'midtrans', NULL, NULL, 'Marge Daniel\n086557018623\nJl. Dayna Landing No. 120, Lake Treverville', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'cancelled', 'pending', NULL, NULL, 'menunggu_diproses', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-07-14 18:07:55', '2026-07-10 11:07:55'),
(90, 21, '795000.00', '0.00', 'midtrans', NULL, NULL, 'Casper Schaefer MD\n083323730683\nJl. Nolan Lake No. 126, Roselynborough', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'completed', 'pending', NULL, NULL, 'menunggu_diproses', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-07-21 14:07:55', '2026-08-19 11:07:55'),
(93, 11, '75000.00', '22000.00', 'midtrans', 'ORDER-11-1788847450', '78d0680b-6f0b-4329-9d73-75e2650c0e08', 'user\n0895347660703\nJlana, Jawa Barat, Bandung 40123\nIndonesia', 'domestic', 'Indonesia', 'Indonesia', 'Bandung', 'Jawa Barat', '40123', '112.85', '0.30', '0.33', '0.33', 'Zona 5', 'cancelled', 'expired', 'JNE', NULL, 'dibatalkan', NULL, NULL, NULL, NULL, '2026-09-08 06:20:57', NULL, '2026-09-08 06:19:11', '2026-09-08 06:04:11', '2026-09-08 06:20:57'),
(94, 11, '499999.99', '88000.00', 'midtrans', 'ORDER-11-1788848503', '8eaaae41-a4a2-45b3-a82a-cfc9bed7da86', 'user\n0895347660703\njalan, Jawa Barat, Bandung 40123\nIndonesia', 'domestic', 'Indonesia', 'Indonesia', 'Bandung', 'Jawa Barat', '40123', '112.85', '0.30', '4.00', '4.00', 'Zona 5', 'cancelled', 'cancelled', 'JNE', NULL, 'dibatalkan', NULL, NULL, NULL, NULL, '2026-09-08 06:26:36', NULL, '2026-09-08 06:36:44', '2026-09-08 06:21:44', '2026-09-08 06:26:36'),
(95, 11, '850000.00', '88000.00', 'midtrans', 'ORDER-11-1788848836', 'eb27b2f2-474a-4221-b97d-bfb271f74115', 'user\n0895347660703\njalan, Jawa Barat, Bandung 40123\nIndonesia', 'domestic', 'Indonesia', 'Indonesia', 'Bandung', 'Jawa Barat', '40123', '112.85', '0.30', '4.00', '4.00', 'Zona 5', 'cancelled', 'cancelled', 'JNE', NULL, 'dibatalkan', NULL, NULL, NULL, NULL, '2026-09-08 06:31:50', NULL, '2026-09-08 06:42:17', '2026-09-08 06:27:17', '2026-09-08 06:31:50'),
(96, 11, '499999.99', '88000.00', 'midtrans', 'ORDER-11-1788849139', '1c565b04-a5af-42c5-a8cd-9c992aa27195', 'user\n0895347660703\njalan, Jawa Barat, Bandung 40123\nIndonesia', 'domestic', 'Indonesia', 'Indonesia', 'Bandung', 'Jawa Barat', '40123', '112.85', '0.30', '4.00', '4.00', 'Zona 5', 'cancelled', 'cancelled', 'JNE', NULL, 'dibatalkan', NULL, NULL, NULL, NULL, '2026-09-08 06:44:13', NULL, '2026-09-08 06:47:20', '2026-09-08 06:32:20', '2026-09-08 06:44:13'),
(97, 11, '850000.00', '88000.00', 'midtrans', 'ORDER-11-1788849992', 'bc8bc840-9681-4c3a-b540-170d0c9fa351', 'user\n0895347660703\njalan, Jawa Barat, Bandung 40123\nIndonesia', 'domestic', 'Indonesia', 'Indonesia', 'Bandung', 'Jawa Barat', '40123', '112.85', '0.30', '4.00', '4.00', 'Zona 5', 'pending', 'paid', 'J&T', 'JNE5515563', 'diserahkan_ke_kurir', '2026-09-08', '2026-09-12', '2026-09-24 17:00:00', NULL, '2026-09-08 06:49:02', '2026-09-08 06:46:54', '2026-09-08 07:01:33', '2026-09-08 06:46:33', '2026-09-08 06:49:02'),
(98, 11, '499999.99', '88000.00', 'midtrans', 'ORDER-11-1788850050', '732963fc-c48f-4d3e-835d-d1fd41a3ad48', 'user\n0895347660703\njalan, Jawa Barat, Bandung 40123\nIndonesia', 'domestic', 'Indonesia', 'Indonesia', 'Bandung', 'Jawa Barat', '40123', '112.85', '0.30', '4.00', '4.00', 'Zona 5', 'cancelled', 'cancelled', 'JNE', NULL, 'dibatalkan', NULL, NULL, NULL, NULL, '2026-09-08 06:47:57', NULL, '2026-09-08 07:02:31', '2026-09-08 06:47:31', '2026-09-08 06:47:57'),
(99, 11, '499999.99', '400000.00', 'midtrans', 'ORDER-11-1788871729', '96268f5c-2970-4ec7-8ced-a4dfdf4033f4', 'user\n0895347660703\njalan, PAPUA BARAT, MANOKWARI 40123\nIndonesia', 'domestic', 'Indonesia', 'Indonesia', 'MANOKWARI', 'PAPUA BARAT', '40123', '3081.69', '0.30', '4.00', '4.00', 'Zona 9', 'completed', 'paid', 'J&amp;amp;amp;T', 'J7388383', 'pesanan_diterima', '2026-09-08', '2026-09-09', '2026-09-08 17:00:00', '2026-09-08 12:52:00', '2026-09-08 12:52:00', '2026-09-08 12:49:39', '2026-09-08 13:03:51', '2026-09-08 12:48:51', '2026-09-08 12:52:00'),
(100, 11, '499999.99', '400000.00', 'midtrans', 'ORDER-11-1788874451', '6394c51f-3e92-4ccf-bde1-49a173a031a8', 'user\n0895347660703\nJalan, PAPUA BARAT, MANOKWARI 40123\nIndonesia', 'domestic', 'Indonesia', 'Indonesia', 'MANOKWARI', 'PAPUA BARAT', '40123', '3081.69', '0.30', '4.00', '4.00', 'Zona 9', 'pending', 'paid', 'JNE', NULL, 'menunggu_diproses', NULL, NULL, NULL, NULL, NULL, '2026-09-08 13:34:30', '2026-09-08 13:49:12', '2026-09-08 13:34:12', '2026-09-08 13:34:30'),
(101, 30, '300000.00', '400000.00', 'midtrans', 'ORDER-30-1788881226', 'd13a4b7c-4500-4653-97d9-a67c4a767ce9', 'kkk\n0895347660703\nJalan, PAPUA BARAT, MANOKWARI 40123\nIndonesia', 'domestic', 'Indonesia', 'Indonesia', 'MANOKWARI', 'PAPUA BARAT', '40123', '3081.69', '0.30', '4.00', '4.00', 'Zona 9', 'pending', 'paid', 'JNE', NULL, 'menunggu_diproses', NULL, NULL, NULL, NULL, NULL, '2026-09-08 15:28:05', '2026-09-08 15:42:07', '2026-09-08 15:27:07', '2026-09-08 15:28:05');

-- --------------------------------------------------------

--
-- Table structure for table `transaction_details`
--

CREATE TABLE `transaction_details` (
  `id` bigint UNSIGNED NOT NULL,
  `transaction_id` bigint UNSIGNED NOT NULL,
  `product_id` bigint UNSIGNED NOT NULL,
  `variant_id` bigint UNSIGNED DEFAULT NULL,
  `quantity` int NOT NULL,
  `subtotal` decimal(12,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `transaction_details`
--

INSERT INTO `transaction_details` (`id`, `transaction_id`, `product_id`, `variant_id`, `quantity`, `subtotal`, `created_at`, `updated_at`) VALUES
(59, 53, 16, NULL, 1, '250000.00', '2026-09-06 17:29:33', '2026-09-06 17:29:33'),
(64, 58, 31, NULL, 1, '499999.99', '2026-09-06 18:17:36', '2026-09-06 18:17:36'),
(65, 59, 19, NULL, 16, '8000000.00', '2026-09-06 18:57:39', '2026-09-06 18:57:39'),
(66, 60, 24, NULL, 1, '1000000.00', '2026-09-07 03:47:28', '2026-09-07 03:47:28'),
(67, 61, 16, NULL, 1, '250000.00', '2026-09-07 04:49:17', '2026-09-07 04:49:17'),
(68, 62, 31, NULL, 1, '499999.99', '2026-09-07 08:14:16', '2026-09-07 08:14:16'),
(69, 63, 22, NULL, 1, '400000.00', '2026-09-07 08:16:24', '2026-09-07 08:16:24'),
(70, 64, 19, NULL, 1, '500000.00', '2026-09-07 08:58:17', '2026-09-07 08:58:17'),
(71, 65, 24, NULL, 1, '1000000.00', '2026-09-07 09:23:51', '2026-09-07 09:23:51'),
(72, 66, 31, NULL, 1, '499999.99', '2026-09-07 09:31:37', '2026-09-07 09:31:37'),
(73, 67, 31, NULL, 1, '499999.99', '2026-09-07 09:33:04', '2026-09-07 09:33:04'),
(74, 68, 31, NULL, 1, '499999.99', '2026-09-07 09:38:18', '2026-09-07 09:38:18'),
(122, 93, 17, NULL, 1, '75000.00', '2026-09-08 06:04:11', '2026-09-08 06:04:11'),
(123, 94, 31, NULL, 1, '499999.99', '2026-09-08 06:21:44', '2026-09-08 06:21:44'),
(124, 95, 28, NULL, 1, '850000.00', '2026-09-08 06:27:17', '2026-09-08 06:27:17'),
(125, 96, 31, NULL, 1, '499999.99', '2026-09-08 06:32:20', '2026-09-08 06:32:20'),
(126, 97, 28, NULL, 1, '850000.00', '2026-09-08 06:46:33', '2026-09-08 06:46:33'),
(127, 98, 31, NULL, 1, '499999.99', '2026-09-08 06:47:31', '2026-09-08 06:47:31'),
(128, 99, 31, NULL, 1, '499999.99', '2026-09-08 12:48:51', '2026-09-08 12:48:51'),
(129, 100, 31, NULL, 1, '499999.99', '2026-09-08 13:34:12', '2026-09-08 13:34:12'),
(130, 101, 1, NULL, 1, '300000.00', '2026-09-08 15:27:07', '2026-09-08 15:27:07');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `username` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `role` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'user',
  `status` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active',
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `username`, `email`, `role`, `status`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES
(9, 'bsbsshs', 'priatuaqw@gmail.com', 'priatuaqw@gmail.com', 'admin', 'active', NULL, '$2y$12$HSsebjdQnVVEqut8OTahmuOA/gcQUn2A5gytad5bzVKtqP9.ayKim', 'iznusCEEm3h1x1wslVPBzFnR6GEYg9NZgpw6l8kCP1QiMUVSe6yH3V7jAnus', '2026-08-11 23:31:28', '2026-09-08 03:16:17'),
(11, 'user', 'nama gue', 'usernameeee333@gmail.com', 'user', 'active', NULL, '$2y$12$w1TDi57QqD38pPDUBVa2eOLga6p5UCP0vCtSq4fXmHICA4UUBFQtm', 'vXroIWHrl6BY16geIrbPGUIm2NQXEE8lzYwgUddtWj9m4ukfa6UxKsdW9CL3', '2026-08-25 07:26:08', '2026-09-08 13:33:12'),
(14, 'Kian Kilback', 'pollich.hettie0', 'turcotte.karelle@example.net', 'user', 'active', NULL, '$2y$12$r82Lzrlga2/LJdxQY0bqhOTNy3zvWopwyHmjfwoy6np9Hx36hh9g2', NULL, '2026-09-07 10:59:27', '2026-09-07 10:59:27'),
(15, 'Pearlie Kuphal', 'brady.ondricka1', 'zgerhold@example.com', 'user', 'active', NULL, '$2y$12$tf6O8vRuGBQ/11CPJ4xBfOWdVhdDDArmhFhsEp0lAH4GzDH0tcV6u', NULL, '2026-09-07 10:59:27', '2026-09-07 10:59:27'),
(16, 'Maryjane West DDS', 'bkautzer2', 'uabernathy@example.com', 'user', 'active', NULL, '$2y$12$Tp55Odp0H/VUncMOQMRshOx3dYHoqT5FzOe6mOUfh4oL1DrEcgkfm', NULL, '2026-09-07 10:59:28', '2026-09-07 10:59:28'),
(17, 'Renee Carroll', 'kieran673', 'murphy.saul@example.net', 'user', 'active', NULL, '$2y$12$9FXSq8v1BxXde3nK3T7pg.bggij9gaWo1MOia5..YPZCLFaM1.vxe', NULL, '2026-09-07 10:59:28', '2026-09-07 10:59:28'),
(18, 'Prof. Jeromy Wisozk', 'emmanuel.douglas4', 'mikel79@example.com', 'user', 'active', NULL, '$2y$12$aCBI.yFQJyzltnc6v3fe7OLwgy8v/e4tQVpe1m0K1Zhf8kYZHeaHO', NULL, '2026-09-07 10:59:29', '2026-09-07 10:59:29'),
(19, 'Dr. Dee McGlynn', 'lee955', 'domingo53@example.org', 'user', 'active', NULL, '$2y$12$eehGr9xnxtuAi7O6waAyYOukA7jZBjNd0ejy5FCJkOwfCniiyIGX.', NULL, '2026-09-07 10:59:29', '2026-09-07 10:59:29'),
(20, 'Tillman Terry', 'samir.skiles0', 'brock.moen@example.net', 'user', 'active', NULL, '$2y$12$39wvURfeveV1dFJo.7iq3uXyt55vPSMqL11pCEivgnraBWFe1ROei', NULL, '2026-09-07 11:07:53', '2026-09-07 11:07:53'),
(21, 'Casper Schaefer MD', 'haylie.grant1', 'tyson20@example.org', 'user', 'active', NULL, '$2y$12$508u4cwAZQod4cZkPRAOVe4bu6XVDm.KuWhM9duT97ZMdtNFv6WPG', NULL, '2026-09-07 11:07:53', '2026-09-07 11:07:53'),
(22, 'Marge Daniel', 'jairo462', 'aemmerich@example.net', 'user', 'active', NULL, '$2y$12$Nn2cMTz0pAAly0F.KSR3P.9JXhfe/rVD898KbejS171x0VbM7WsBK', NULL, '2026-09-07 11:07:54', '2026-09-07 11:07:54'),
(23, 'Whitney Schowalter Jr.', 'wschaden3', 'kory.oreilly@example.org', 'user', 'suspended', NULL, '$2y$12$Bk.jF4K/WsiX0AaSIDYVLuQ.bSPbrNv6xLA6K1vuMJ/e1o1Sd2ZwK', NULL, '2026-09-07 11:07:54', '2026-09-07 11:07:54'),
(24, 'Frederik Schulist', 'prohaska.rhett4', 'dickinson.verla@example.org', 'user', 'suspended', NULL, '$2y$12$/ddk04MVEAApaTWnHxG1auG0Hd7D4lsh8eGRZMp.6iERTlJgfwR5q', NULL, '2026-09-07 11:07:55', '2026-09-07 11:07:55'),
(25, 'Dr. Guadalupe Daugherty', 'kessler.leland5', 'armando26@example.org', 'user', 'suspended', NULL, '$2y$12$1PTNUC6A1XNAiyTDQ9QLAu0dyBP.bwLZeriILr0Q4Be9SA6ZyFe0a', NULL, '2026-09-07 11:07:55', '2026-09-07 11:07:55'),
(29, 'Flow Test', NULL, 'flowtest@example.com', 'user', 'active', NULL, '$2y$12$OaMohS9JA86JwxnS.QsVou79GFGm9ECqBlaoRghkCRk2kJ.gzpZue', NULL, '2026-09-08 04:41:01', '2026-09-08 04:41:01'),
(30, 'kkk', 'nd', 'yyyy150909@gmail.com', 'user', 'active', NULL, '$2y$12$KvZy2E2F.KhdMtWVtsvesuGi6jR5oCrEp.klhl5bAEOvvNf/o/1Ue', NULL, '2026-09-08 15:18:45', '2026-09-08 15:19:44');

--
-- Indexes for dumped tables
--

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
-- Indexes for table `featured_products`
--
ALTER TABLE `featured_products`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `featured_products_product_id_unique` (`product_id`);

--
-- Indexes for table `flash_sales`
--
ALTER TABLE `flash_sales`
  ADD PRIMARY KEY (`id`),
  ADD KEY `flash_sales_product_id_foreign` (`product_id`),
  ADD KEY `flash_sales_status_start_at_end_at_index` (`status`,`start_at`,`end_at`);

--
-- Indexes for table `international_regions`
--
ALTER TABLE `international_regions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `international_regions_name_unique` (`name`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD KEY `products_category_index` (`category`);

--
-- Indexes for table `product_variants`
--
ALTER TABLE `product_variants`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_variants_product_id_foreign` (`product_id`);

--
-- Indexes for table `shipping_countries`
--
ALTER TABLE `shipping_countries`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `shipping_countries_country_unique` (`country`),
  ADD KEY `shipping_countries_region_id_foreign` (`region_id`);

--
-- Indexes for table `shipping_couriers`
--
ALTER TABLE `shipping_couriers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `shipping_settings`
--
ALTER TABLE `shipping_settings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `shipping_zones`
--
ALTER TABLE `shipping_zones`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `transactions`
--
ALTER TABLE `transactions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `transactions_midtrans_order_id_unique` (`midtrans_order_id`),
  ADD KEY `transactions_user_id_foreign` (`user_id`);

--
-- Indexes for table `transaction_details`
--
ALTER TABLE `transaction_details`
  ADD PRIMARY KEY (`id`),
  ADD KEY `transaction_details_transaction_id_foreign` (`transaction_id`),
  ADD KEY `transaction_details_product_id_foreign` (`product_id`),
  ADD KEY `transaction_details_variant_id_foreign` (`variant_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`),
  ADD UNIQUE KEY `users_username_unique` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `featured_products`
--
ALTER TABLE `featured_products`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `flash_sales`
--
ALTER TABLE `flash_sales`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `international_regions`
--
ALTER TABLE `international_regions`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=64;

--
-- AUTO_INCREMENT for table `product_variants`
--
ALTER TABLE `product_variants`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;

--
-- AUTO_INCREMENT for table `shipping_countries`
--
ALTER TABLE `shipping_countries`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `shipping_couriers`
--
ALTER TABLE `shipping_couriers`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `shipping_settings`
--
ALTER TABLE `shipping_settings`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `shipping_zones`
--
ALTER TABLE `shipping_zones`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `transactions`
--
ALTER TABLE `transactions`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=102;

--
-- AUTO_INCREMENT for table `transaction_details`
--
ALTER TABLE `transaction_details`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=131;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `featured_products`
--
ALTER TABLE `featured_products`
  ADD CONSTRAINT `featured_products_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `flash_sales`
--
ALTER TABLE `flash_sales`
  ADD CONSTRAINT `flash_sales_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `product_variants`
--
ALTER TABLE `product_variants`
  ADD CONSTRAINT `product_variants_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `shipping_countries`
--
ALTER TABLE `shipping_countries`
  ADD CONSTRAINT `shipping_countries_region_id_foreign` FOREIGN KEY (`region_id`) REFERENCES `international_regions` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `transactions`
--
ALTER TABLE `transactions`
  ADD CONSTRAINT `transactions_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `transaction_details`
--
ALTER TABLE `transaction_details`
  ADD CONSTRAINT `transaction_details_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `transaction_details_transaction_id_foreign` FOREIGN KEY (`transaction_id`) REFERENCES `transactions` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `transaction_details_variant_id_foreign` FOREIGN KEY (`variant_id`) REFERENCES `product_variants` (`id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
