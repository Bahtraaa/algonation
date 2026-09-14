-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Sep 14, 2026 at 05:49 PM
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
-- Table structure for table `addresses`
--

CREATE TABLE `addresses` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `label` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Rumah',
  `recipient_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `country` varchar(120) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Indonesia',
  `province` varchar(120) COLLATE utf8mb4_unicode_ci NOT NULL,
  `city` varchar(120) COLLATE utf8mb4_unicode_ci NOT NULL,
  `district` varchar(120) COLLATE utf8mb4_unicode_ci NOT NULL,
  `postal_code` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `address` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `note` varchar(500) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_default` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `addresses`
--

INSERT INTO `addresses` (`id`, `user_id`, `label`, `recipient_name`, `phone`, `country`, `province`, `city`, `district`, `postal_code`, `address`, `note`, `is_default`, `created_at`, `updated_at`) VALUES
(4, 43, 'Rumah', 'Agus Suprianto', '085881978741', 'Amerika Serikat', 'Texas', 'Austin', 'shhs', '40123', 'Jalan', NULL, 1, '2026-09-14 15:33:16', '2026-09-14 15:33:16'),
(5, 43, 'Rumah', 'gssggsg hddhhddhhd', '088228822', 'Indonesia', 'Jawa Barat', 'Bandung', 'Coblong', '40123', 'Jalan', NULL, 0, '2026-09-14 16:07:36', '2026-09-14 16:07:36');

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
('algo-nation-cache-shipping.geocode.013631f46b76d7027dd9f34e48acefd8', 's:4:\"MISS\";', 1789406488),
('algo-nation-cache-shipping.geocode.0be0d89a7660a0f90bc223b97924a527', 'a:2:{s:8:\"latitude\";d:4.4137155;s:9:\"longitude\";d:114.5653908;}', 1792000035),
('algo-nation-cache-shipping.geocode.24500fc6c806d8b967621c07c292a81e', 'a:2:{s:8:\"latitude\";d:34.7727746;s:9:\"longitude\";d:-85.1365657;}', 1791994790),
('algo-nation-cache-shipping.geocode.2f7c732167f9ee9823ba4661cb458cd5', 'a:2:{s:8:\"latitude\";d:14.6999457;s:9:\"longitude\";d:121.0331969;}', 1792000037),
('algo-nation-cache-shipping.geocode.2fd17b04bae6b9cd59abf128c4e19fe9', 's:4:\"MISS\";', 1789406387),
('algo-nation-cache-shipping.geocode.3400f5519a927ab8c40b677317099b7d', 's:4:\"MISS\";', 1789406388),
('algo-nation-cache-shipping.geocode.493d9df211ac5d11d90e5082fba22557', 'a:2:{s:8:\"latitude\";d:26.2540493;s:9:\"longitude\";d:29.2675469;}', 1792000109),
('algo-nation-cache-shipping.geocode.562a756572c66c61b14344ef089ed416', 'a:2:{s:8:\"latitude\";d:3.0402665;s:9:\"longitude\";d:101.5647601;}', 1792000032),
('algo-nation-cache-shipping.geocode.6a4120be23c814f80233ecbb34e71adc', 'a:2:{s:8:\"latitude\";d:-10.3333333;s:9:\"longitude\";d:-53.2;}', 1792000108),
('algo-nation-cache-shipping.geocode.7aa473faede10addc3995122b1b29a5f', 'a:2:{s:8:\"latitude\";d:1.3418494;s:9:\"longitude\";d:103.6471373;}', 1792000033),
('algo-nation-cache-shipping.geocode.8b32b82f40fe139de5898d64ce690e3f', 's:4:\"MISS\";', 1789406486),
('algo-nation-cache-shipping.geocode.8e7b4d9a34f5a3a0ebee1b11092d9823', 's:4:\"MISS\";', 1789406386),
('algo-nation-cache-shipping.geocode.9cfe4ec5665195176b3314d7ececd271', 'a:2:{s:8:\"latitude\";d:13.063002;s:9:\"longitude\";d:101.2255726;}', 1792000036),
('algo-nation-cache-shipping.geocode.b4aadc84197c0fa609905736c330d931', 'a:2:{s:8:\"latitude\";d:1.2899175;s:9:\"longitude\";d:103.8519072;}', 1791994791),
('algo-nation-cache-shipping.geocode.de8c12cfc70feb394c42f5efaaef99ec', 's:4:\"MISS\";', 1789411634),
('algo-nation-cache-shipping.geocode.df07057f00e4e7d7251262f859f0bb9e', 'a:2:{s:8:\"latitude\";d:30.2711286;s:9:\"longitude\";d:-97.7436995;}', 1791994784),
('algo-nation-cache-shipping.geocode.df3bf0e3c49776c7438c5962e1d1b15b', 's:4:\"MISS\";', 1789406389),
('algo-nation-cache-shipping.geocode.e0dfc43cca341e602d9307806d71998b', 'a:2:{s:8:\"latitude\";d:1.2899175;s:9:\"longitude\";d:103.8519072;}', 1792000111),
('algo-nation-cache-shipping.geocode.f39d75ab28015cbb0d2714ae0c884546', 'a:2:{s:8:\"latitude\";d:36.5748441;s:9:\"longitude\";d:139.2394179;}', 1792000110),
('algo-nation-cache-shipping.geocode.f76372ef90f91d29dd5cd84631173d18', 'a:2:{s:8:\"latitude\";d:-6.8848474;s:9:\"longitude\";d:107.6152496;}', 1791994785),
('algo-nation-cache-shipping.geocode.fb3b6322b10b320e1ec910d1b088e597', 'a:2:{s:8:\"latitude\";d:20.9088677;s:9:\"longitude\";d:105.8547464;}', 1792000039);

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
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint UNSIGNED NOT NULL,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `featured_products`
--

CREATE TABLE `featured_products` (
  `id` bigint UNSIGNED NOT NULL,
  `product_id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `featured_products`
--

INSERT INTO `featured_products` (`id`, `product_id`, `created_at`, `updated_at`) VALUES
(1, 1, '2026-09-08 19:12:37', '2026-09-08 19:12:37'),
(5, 17, '2026-09-08 19:12:57', '2026-09-08 19:12:57'),
(10, 30, '2026-09-08 19:21:37', '2026-09-08 19:21:37'),
(11, 28, '2026-09-08 19:21:37', '2026-09-08 19:21:37'),
(12, 23, '2026-09-08 19:21:37', '2026-09-08 19:21:37'),
(13, 29, '2026-09-08 19:21:37', '2026-09-08 19:21:37'),
(14, 22, '2026-09-08 19:21:37', '2026-09-08 19:21:37'),
(18, 19, '2026-09-14 08:06:00', '2026-09-14 08:06:00');

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
  `status` enum('scheduled','active','expired','inactive') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'scheduled',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `flash_sales`
--

INSERT INTO `flash_sales` (`id`, `product_id`, `normal_price`, `sale_price`, `discount_percentage`, `stock`, `start_at`, `end_at`, `status`, `created_at`, `updated_at`) VALUES
(1, 31, '950000.00', '499999.99', '47.37', 5, '2026-09-03 14:13:00', '2026-10-15 17:15:00', 'active', '2026-09-08 19:21:37', '2026-09-13 20:28:26'),
(2, 24, '1890000.00', '1000000.00', '47.09', 7, '2026-09-03 14:13:00', '2026-10-15 17:15:00', 'active', '2026-09-08 19:21:37', '2026-09-08 19:21:37'),
(3, 19, '500000.00', '200000.00', '60.00', 5, '2026-09-08 19:52:00', '2026-10-15 19:52:00', 'active', '2026-09-08 19:21:37', '2026-09-14 08:24:01');

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
(1, 'Asia', '900000.00', '900000.00', '2026-09-09 02:12:31', '2026-09-14 17:46:40'),
(2, 'Asia Tenggara', '450000.00', '450000.00', '2026-09-09 02:12:31', '2026-09-14 17:46:40'),
(3, 'Eropa', '1300000.00', '1300000.00', '2026-09-09 02:12:31', '2026-09-14 17:46:40'),
(4, 'Amerika Utara', '1300000.00', '1300000.00', '2026-09-09 02:12:31', '2026-09-14 17:46:40'),
(5, 'Australia & Selandia Baru', '750000.00', '750000.00', '2026-09-14 17:46:40', '2026-09-14 17:46:40'),
(6, 'Amerika Selatan', '1400000.00', '1400000.00', '2026-09-14 17:46:40', '2026-09-14 17:46:40'),
(7, 'Timur Tengah', '1150000.00', '1150000.00', '2026-09-14 17:46:40', '2026-09-14 17:46:40'),
(8, 'Afrika', '1350000.00', '1350000.00', '2026-09-14 17:46:40', '2026-09-14 17:46:40');

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint UNSIGNED NOT NULL,
  `queue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` tinyint UNSIGNED NOT NULL,
  `reserved_at` int UNSIGNED DEFAULT NULL,
  `available_at` int UNSIGNED NOT NULL,
  `created_at` int UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `job_batches`
--

CREATE TABLE `job_batches` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_jobs` int NOT NULL,
  `pending_jobs` int NOT NULL,
  `failed_jobs` int NOT NULL,
  `failed_job_ids` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `options` mediumtext COLLATE utf8mb4_unicode_ci,
  `cancelled_at` int DEFAULT NULL,
  `created_at` int NOT NULL,
  `finished_at` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int UNSIGNED NOT NULL,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
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
(9, '2026_09_03_000001_create_flash_sales_table', 1),
(10, '2026_09_06_000001_add_midtrans_fields_to_transactions_table', 1),
(11, '2026_09_06_000002_add_payment_status_and_paid_at_to_transactions_table', 1),
(12, '2026_09_07_000001_add_shipping_fields_to_transactions_table', 1),
(13, '2026_09_07_000002_add_payment_due_at_to_transactions_table', 1),
(14, '2026_09_07_000003_add_weight_and_dimensions_to_products_table', 1),
(15, '2026_09_07_000004_create_featured_products_table', 1),
(16, '2026_09_07_000005_create_shipping_tables', 1),
(17, '2026_09_07_000006_add_shipping_snapshot_to_transactions_table', 1),
(19, '2026_09_14_000001_create_addresses_table', 2),
(20, '2026_09_14_000002_add_address_snapshot_to_transactions_table', 2),
(21, '2026_09_15_000001_add_variant_details_and_product_status', 3),
(23, '2026_09_16_000002_add_image_to_product_variants_table', 4);

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `category` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active',
  `image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
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

INSERT INTO `products` (`id`, `name`, `category`, `status`, `image`, `description`, `stock`, `price`, `weight`, `length`, `width`, `height`, `created_at`, `updated_at`) VALUES
(1, 'Celana Jeans', 'Bottoms', 'active', 'images/products/Fbc8UQUgpwvCxmiR8fbJRwMlsbQ8ZiXPk7s7LYhi.jpg', 'celana jeans kekinian dan terbaru 2026', 8, '300000.00', '500.00', '40.00', '30.00', '5.00', '2026-09-08 19:12:37', '2026-09-14 16:08:17'),
(16, 'Hoodie Black mamba', 'Outerwear', 'active', 'images/products/MMGkdTmHX3HtdQU73bMmAkoCqlcyPaueJFXygFQ7.jpg', 'Hoodie Black Mamba streetwear premium', 101, '250000.00', '600.00', '40.00', '30.00', '5.00', '2026-09-08 19:12:37', '2026-09-14 16:08:17'),
(17, 'Topi Merah', 'Hats', 'active', 'images/products/dhDNPcGSdGvkrYOuEVIHnf0JHwwnpnvAIhpRA4cd.jpg', 'topi merah polos', 52, '75000.00', '300.00', '10.00', '13.00', '15.00', '2026-09-08 19:12:57', '2026-09-14 16:08:17'),
(19, 'Sepatu Kulit', 'Footwear', 'active', 'images/products/CPau4usMBGuNUxLdnZrqYjkdlY7h1yp4OWAIAUqJ.jpg', 'sepatu kulit warna hitam', 98, '500000.00', '800.00', '32.00', '20.00', '12.00', '2026-09-08 19:12:57', '2026-09-14 16:08:17'),
(22, 'Sepatu lari', 'Footwear', 'active', 'images/products/CSC4ir1CgWbjiyjTLsQdKz2hFnn6eUP2jlwko2Dh.jpg', 'Sepatu lari olahraga kasual adem dan empuk', 101, '400000.00', '750.00', '30.00', '20.00', '12.00', '2026-09-08 19:12:57', '2026-09-14 16:08:17'),
(23, 'Nike Air Jordan Wanita', 'Footwear', 'active', 'images/products/ucpvuE3z6uGYQeExweXKlTtZLVJifmtGdT8PNdvP.jpg', 'Nike Air Jordan Gorpcore Indie Sneakers Wanita white pink', 98, '700000.00', '850.00', '32.00', '22.00', '14.00', '2026-09-08 19:12:57', '2026-09-14 16:08:17'),
(24, 'Stay Bag', 'Bags', 'active', 'images/products/JApW0FN7fcX1esggJWOY8gNWTVBzKeZsTBvpuMpk.webp', 'Stay Bag Black and Brown', 993, '1890000.00', '300.00', '40.00', '30.00', '20.00', '2026-09-08 19:12:57', '2026-09-14 16:08:17'),
(25, 'Berto\'s Hat', 'Hats', 'active', 'images/products/F0BSfxSn0nM0IodKu7zjgWpzbQ2EtojF51T3g5EM.webp', 'Topi Berto\'s Hat edisi terbatas', 109, '1250000.00', '200.00', '20.00', '20.00', '12.00', '2026-09-08 19:12:57', '2026-09-14 16:08:17'),
(26, 'Kaos Kasual Pria', 'Casual T-Shirt', 'active', 'images/products/2j4rh4MDlV84hAvvH9Rrn3DY1vIvZ6CtnDKmPazm.webp', 'Kaos kasual pria warna putih', 99, '75000.00', '220.00', '30.00', '20.00', '2.00', '2026-09-08 19:12:57', '2026-09-14 16:08:17'),
(27, 'Kaos Kasual Pria', 'Casual T-Shirt', 'active', 'images/products/pNNyFcN4llcCid6fn0byzEKVyyO97EypzCctW8Tk.webp', 'Kaos kasual pria warna putih', 100, '75000.00', '220.00', '30.00', '20.00', '2.00', '2026-09-08 19:12:57', '2026-09-14 16:08:17'),
(28, 'Jaket The North Face', 'Outerwear', 'active', 'images/products/FjsxbEyu7IDrip2ezo6aU89WXLhslTyB0XkDFRbl.jpg', 'Jaket The North Face outdoor waterproof', 101, '850000.00', '700.00', '40.00', '30.00', '5.00', '2026-09-08 19:12:57', '2026-09-14 16:08:17'),
(29, 'Tas Coklat', 'Bags', 'active', 'images/products/htNA5swNtrpJT9UXmZrcibIZzJIGX10EF7Jhpwks.jpg', 'Tas bahu bahan kulit sintetis warna coklat', 100, '250000.00', '450.00', '35.00', '25.00', '10.00', '2026-09-08 19:12:57', '2026-09-14 16:08:17'),
(30, 'Jas Hitam', 'Formal Wear', 'active', 'images/products/Q4c1RfIgfiIEZI0HTeQhgbie0mxiXwujuRhHs8zX.jpg', 'Jas formal pria warna hitam executive', 100, '500000.00', '800.00', '45.00', '35.00', '5.00', '2026-09-08 19:12:57', '2026-09-14 16:08:17'),
(31, 'Sepatu Nike', 'Footwear', 'active', 'images/products/SZIXgjMhAMTxU34ajEJUNd2HSVcR8zrmNSxJ7poh.jpg', 'Sepatu sneakers Nike edisi khusus warna merah', 1003, '950000.00', '850.00', '32.00', '22.00', '14.00', '2026-09-08 19:12:57', '2026-09-14 17:05:10'),
(32, 'Jaket Kulit Hitam', 'Outerwear', 'active', 'images/products/H1PpCwgLIpbSgkHhWGq8D7s9XIXGTbJCwxwPtPQS.jpg', 'Jaket kulit asli warna hitam pria', 100, '500000.00', '900.00', '42.00', '32.00', '6.00', '2026-09-08 19:12:57', '2026-09-14 16:08:17');

-- --------------------------------------------------------

--
-- Table structure for table `product_variants`
--

CREATE TABLE `product_variants` (
  `id` bigint UNSIGNED NOT NULL,
  `product_id` bigint UNSIGNED NOT NULL,
  `color` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `size` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sku` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `stock` int NOT NULL DEFAULT '0',
  `price` decimal(12,2) DEFAULT NULL,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `product_variants`
--

INSERT INTO `product_variants` (`id`, `product_id`, `color`, `size`, `sku`, `name`, `stock`, `price`, `image`, `created_at`, `updated_at`) VALUES
(60, 31, 'Warna Hitam', '50 CM', 'TS-BLK-50CM', 'Warna Hitam - 50 CM', 10, '950000.00', 'product-variants/uLDC0edV2R78gsthTbZO1Z5zEH2QzZpeocuRbMtb.png', '2026-09-14 17:04:17', '2026-09-14 17:21:01');

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
(1, 2, 'Singapura', '450000.00', '450000.00', 1, '2026-09-09 02:12:31', '2026-09-14 17:46:40'),
(2, 2, 'Malaysia', '470000.00', '470000.00', 1, '2026-09-09 02:12:31', '2026-09-14 17:46:40'),
(3, 2, 'Thailand', '600000.00', '600000.00', 1, '2026-09-09 02:12:31', '2026-09-14 17:46:40'),
(4, 1, 'Jepang', '900000.00', '900000.00', 1, '2026-09-09 02:12:31', '2026-09-14 17:46:40'),
(5, 1, 'Korea Selatan', '900000.00', '900000.00', 1, '2026-09-09 02:12:31', '2026-09-14 17:46:40'),
(6, 3, 'Belanda', '1200000.00', '1200000.00', 1, '2026-09-09 02:12:31', '2026-09-14 17:46:40'),
(7, 3, 'Inggris', '1200000.00', '1200000.00', 1, '2026-09-09 02:12:31', '2026-09-14 17:46:40'),
(8, 4, 'Amerika Serikat', '1300000.00', '1300000.00', 1, '2026-09-09 02:12:31', '2026-09-14 17:46:40'),
(9, 2, 'Brunei', '500000.00', '500000.00', 1, '2026-09-14 17:46:40', '2026-09-14 17:46:40'),
(10, 2, 'Filipina', '630000.00', '630000.00', 1, '2026-09-14 17:46:40', '2026-09-14 17:46:40'),
(11, 2, 'Vietnam', '640000.00', '640000.00', 1, '2026-09-14 17:46:40', '2026-09-14 17:46:40'),
(12, 1, 'China', '900000.00', '900000.00', 1, '2026-09-14 17:46:40', '2026-09-14 17:46:40'),
(13, 1, 'Taiwan', '900000.00', '900000.00', 1, '2026-09-14 17:46:40', '2026-09-14 17:46:40'),
(14, 1, 'Hong Kong', '900000.00', '900000.00', 1, '2026-09-14 17:46:40', '2026-09-14 17:46:40'),
(15, 5, 'Australia', '750000.00', '750000.00', 1, '2026-09-14 17:46:40', '2026-09-14 17:46:40'),
(16, 5, 'Selandia Baru', '750000.00', '750000.00', 1, '2026-09-14 17:46:40', '2026-09-14 17:46:40'),
(17, 3, 'Jerman', '1200000.00', '1200000.00', 1, '2026-09-14 17:46:40', '2026-09-14 17:46:40'),
(18, 3, 'Prancis', '1200000.00', '1200000.00', 1, '2026-09-14 17:46:40', '2026-09-14 17:46:40'),
(19, 3, 'Italia', '1250000.00', '1250000.00', 1, '2026-09-14 17:46:40', '2026-09-14 17:46:40'),
(20, 3, 'Spanyol', '1250000.00', '1250000.00', 1, '2026-09-14 17:46:40', '2026-09-14 17:46:40'),
(21, 4, 'Kanada', '1350000.00', '1350000.00', 1, '2026-09-14 17:46:40', '2026-09-14 17:46:40'),
(22, 6, 'Brasil', '1450000.00', '1450000.00', 1, '2026-09-14 17:46:40', '2026-09-14 17:46:40'),
(23, 6, 'Argentina', '1500000.00', '1500000.00', 1, '2026-09-14 17:46:40', '2026-09-14 17:46:40'),
(24, 6, 'Chile', '1400000.00', '1400000.00', 1, '2026-09-14 17:46:40', '2026-09-14 17:46:40'),
(25, 7, 'Uni Emirat Arab', '1150000.00', '1150000.00', 1, '2026-09-14 17:46:40', '2026-09-14 17:46:40'),
(26, 7, 'Arab Saudi', '1150000.00', '1150000.00', 1, '2026-09-14 17:46:40', '2026-09-14 17:46:40'),
(27, 7, 'Qatar', '1200000.00', '1200000.00', 1, '2026-09-14 17:46:40', '2026-09-14 17:46:40'),
(28, 7, 'Kuwait', '1200000.00', '1200000.00', 1, '2026-09-14 17:46:40', '2026-09-14 17:46:40'),
(29, 8, 'Afrika Selatan', '1350000.00', '1350000.00', 1, '2026-09-14 17:46:40', '2026-09-14 17:46:40'),
(30, 8, 'Mesir', '1350000.00', '1350000.00', 1, '2026-09-14 17:46:40', '2026-09-14 17:46:40');

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
(1, 'JNE', 'domestic', 1, '2026-09-09 02:12:31', '2026-09-09 02:12:31'),
(2, 'J&T Express', 'domestic', 1, '2026-09-09 02:12:31', '2026-09-09 02:12:31'),
(3, 'DHL Express', 'international', 1, '2026-09-09 02:12:31', '2026-09-09 02:12:31'),
(4, 'POS Indonesia', 'domestic', 1, '2026-09-09 02:12:31', '2026-09-09 02:12:31');

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
(1, 'Indonesia', 'Jakarta', '-6.2000000', '106.8166660', 'none', 0, '2026-09-09 02:12:31', '2026-09-09 02:12:31');

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
(1, 'Zona 1', 0, 10, '10000.00', '10000.00', 1, '2026-09-09 02:12:31', '2026-09-14 04:55:59'),
(2, 'Zona 2', 11, 20, '12000.00', '12000.00', 1, '2026-09-09 02:12:31', '2026-09-09 02:12:31'),
(3, 'Zona 3', 21, 30, '15000.00', '15000.00', 1, '2026-09-09 02:12:31', '2026-09-09 02:12:31'),
(4, 'Zona 4', 31, 100, '18000.00', '18000.00', 1, '2026-09-09 02:12:31', '2026-09-09 02:12:31'),
(5, 'Zona 5', 101, 200, '22000.00', '22000.00', 1, '2026-09-09 02:12:31', '2026-09-09 02:12:31'),
(6, 'Zona 6', 201, 500, '25000.00', '25000.00', 1, '2026-09-09 02:12:31', '2026-09-09 02:12:31'),
(7, 'Zona 7', 501, 700, '30000.00', '30000.00', 1, '2026-09-09 02:12:31', '2026-09-09 02:12:31'),
(8, 'Zona 8', 701, 1060, '35000.00', '35000.00', 1, '2026-09-09 02:12:31', '2026-09-09 02:12:31'),
(9, 'Zona 9', 1061, NULL, '150000.00', '60000.00', 1, '2026-09-09 02:12:31', '2026-09-14 16:31:21');

-- --------------------------------------------------------

--
-- Table structure for table `transactions`
--

CREATE TABLE `transactions` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `address_id` bigint UNSIGNED DEFAULT NULL,
  `total_price` decimal(12,2) NOT NULL DEFAULT '0.00',
  `shipping_cost` decimal(12,2) NOT NULL DEFAULT '0.00',
  `payment_method` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'midtrans',
  `midtrans_order_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `midtrans_snap_token` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `shipping_address` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `shipping_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `shipping_phone` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `shipping_country` varchar(120) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `shipping_province` varchar(120) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `shipping_city` varchar(120) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `shipping_district` varchar(120) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `shipping_postal_code` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `shipping_note` varchar(500) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `shipping_label` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
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
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `payment_status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `shipping_courier` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tracking_number` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `shipping_status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'menunggu_diproses',
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

INSERT INTO `transactions` (`id`, `user_id`, `address_id`, `total_price`, `shipping_cost`, `payment_method`, `midtrans_order_id`, `midtrans_snap_token`, `shipping_address`, `shipping_name`, `shipping_phone`, `shipping_country`, `shipping_province`, `shipping_city`, `shipping_district`, `shipping_postal_code`, `shipping_note`, `shipping_label`, `shipping_type`, `origin_country`, `destination_country`, `destination_city`, `destination_state`, `destination_postal_code`, `shipping_distance`, `actual_weight`, `volumetric_weight`, `billable_weight`, `shipping_zone`, `status`, `payment_status`, `shipping_courier`, `tracking_number`, `shipping_status`, `estimated_delivery_start`, `estimated_delivery_end`, `shipped_at`, `delivered_at`, `shipping_updated_at`, `paid_at`, `payment_due_at`, `created_at`, `updated_at`) VALUES
(1, 2, NULL, '499000.00', '25000.00', 'midtrans', NULL, NULL, 'Budiono Siregar\n085268819235\nJl. Eldora Rapid No. 12, Lake Abigayletown', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'cancelled', 'pending', NULL, NULL, 'menunggu_diproses', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-09-05 13:12:37', '2026-08-26 02:12:37'),
(2, 4, NULL, '1025000.00', '0.00', 'midtrans', NULL, NULL, 'Sandy Walker Jr.\n086480012152\nJl. Raynor Square No. 167, Cartwrightfort', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'completed', 'pending', NULL, NULL, 'menunggu_diproses', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-08-20 16:12:37', '2026-07-16 02:12:37'),
(3, 7, NULL, '1733000.00', '0.00', 'midtrans', NULL, NULL, 'Clare Lowe\n089187994096\nJl. Jovany Station No. 118, Jamisonborough', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'completed', 'pending', NULL, NULL, 'menunggu_diproses', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-08-25 12:12:37', '2026-07-28 02:12:37'),
(4, 1, NULL, '987000.00', '0.00', 'midtrans', NULL, NULL, 'Admin\n086287107947\nJl. Natalia Parkway No. 178, Malcolmtown', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'processing', 'pending', NULL, NULL, 'menunggu_diproses', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-07-11 23:12:37', '2026-08-07 02:12:37'),
(5, 3, NULL, '627000.00', '0.00', 'midtrans', NULL, NULL, 'Katherine Luettgen\n080102562521\nJl. Roberts River No. 39, Port Jordan', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'completed', 'pending', NULL, NULL, 'menunggu_diproses', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-08-08 20:12:38', '2026-09-08 02:12:38'),
(6, 6, NULL, '1336000.00', '0.00', 'midtrans', NULL, NULL, 'Maverick Upton\n083828047959\nJl. Shaun Mill No. 40, Port Eveline', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'completed', 'pending', NULL, NULL, 'menunggu_diproses', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-08-23 05:12:38', '2026-08-27 02:12:38'),
(7, 8, NULL, '249000.00', '25000.00', 'midtrans', NULL, NULL, 'Prof. Rafael McCullough\n087730103491\nJl. Mitchel Court No. 115, Herzogbury', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'completed', 'pending', NULL, NULL, 'menunggu_diproses', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-07-16 23:12:38', '2026-08-06 02:12:38'),
(8, 5, NULL, '1325000.00', '0.00', 'midtrans', NULL, NULL, 'Ena Dach\n086933235894\nJl. Alessandro Turnpike No. 50, Norvalberg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'pending', 'pending', NULL, NULL, 'menunggu_diproses', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-08-07 05:12:38', '2026-08-17 02:12:38'),
(9, 5, NULL, '159000.00', '25000.00', 'midtrans', NULL, NULL, 'Ena Dach\n087597030365\nJl. Cary Union No. 119, Evangelineville', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'cancelled', 'pending', NULL, NULL, 'menunggu_diproses', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-07-16 15:12:38', '2026-08-01 02:12:38'),
(10, 7, NULL, '666000.00', '0.00', 'midtrans', NULL, NULL, 'Clare Lowe\n085032713387\nJl. Haag Wells No. 178, Lake Emelieshire', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'cancelled', 'pending', NULL, NULL, 'menunggu_diproses', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-08-24 04:12:38', '2026-07-27 02:12:38'),
(11, 6, NULL, '698000.00', '0.00', 'midtrans', NULL, NULL, 'Maverick Upton\n086791578219\nJl. Malvina Village No. 11, Skylamouth', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'completed', 'pending', NULL, NULL, 'menunggu_diproses', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-08-21 06:12:38', '2026-07-21 02:12:38'),
(12, 4, NULL, '717000.00', '0.00', 'midtrans', NULL, NULL, 'Sandy Walker Jr.\n083279840518\nJl. Maeve Landing No. 100, Connborough', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'completed', 'pending', NULL, NULL, 'menunggu_diproses', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-07-24 02:12:38', '2026-08-11 02:12:38'),
(13, 14, NULL, '1434000.00', '0.00', 'midtrans', NULL, NULL, 'Genevieve Daugherty PhD\n088275942993\nJl. Mraz Parkways No. 28, East Isom', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'cancelled', 'pending', NULL, NULL, 'menunggu_diproses', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-07-23 17:12:57', '2026-07-30 02:12:57'),
(14, 10, NULL, '536000.00', '0.00', 'midtrans', NULL, NULL, 'Ozella Kris\n086160192986\nJl. Dooley Branch No. 100, Port Eliezerton', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'completed', 'pending', NULL, NULL, 'menunggu_diproses', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-09-02 10:12:57', '2026-09-06 02:12:57'),
(15, 11, NULL, '89000.00', '25000.00', 'midtrans', NULL, NULL, 'Brennan Schultz\n086852238642\nJl. Desiree Pines No. 78, Bashirianview', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'cancelled', 'pending', NULL, NULL, 'menunggu_diproses', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-08-05 22:12:57', '2026-08-08 02:12:57'),
(16, 13, NULL, '916000.00', '0.00', 'midtrans', NULL, NULL, 'Dr. Aliza Veum II\n085159746436\nJl. Lubowitz Trafficway No. 1, Lake Lisabury', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'processing', 'pending', NULL, NULL, 'menunggu_diproses', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-08-30 09:12:57', '2026-08-22 02:12:57'),
(17, 2, NULL, '698000.00', '0.00', 'midtrans', NULL, NULL, 'Budiono Siregar\n082443000821\nJl. Frederik Court No. 195, Salvadorton', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'cancelled', 'pending', NULL, NULL, 'menunggu_diproses', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-08-09 01:12:57', '2026-08-21 02:12:57'),
(18, 2, NULL, '1454000.00', '0.00', 'midtrans', NULL, NULL, 'Budiono Siregar\n080709403980\nJl. Gerald Viaduct No. 54, Sigmundside', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'pending', 'pending', NULL, NULL, 'menunggu_diproses', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-08-23 18:12:57', '2026-09-07 02:12:57'),
(19, 2, NULL, '239000.00', '25000.00', 'midtrans', NULL, NULL, 'Budiono Siregar\n085730113818\nJl. Adan Highway No. 200, Georgefort', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'cancelled', 'pending', NULL, NULL, 'menunggu_diproses', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-08-31 00:12:57', '2026-08-29 02:12:57'),
(20, 12, NULL, '389000.00', '25000.00', 'midtrans', NULL, NULL, 'Domenick Bashirian\n085165095395\nJl. Deon Meadow No. 75, Kuvalisfort', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'completed', 'pending', NULL, NULL, 'menunggu_diproses', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-07-19 07:12:57', '2026-08-25 02:12:57'),
(21, 2, NULL, '2694000.00', '0.00', 'midtrans', NULL, NULL, 'Budiono Siregar\n088722123125\nJl. Rutherford Trafficway No. 19, North Rey', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'pending', 'pending', NULL, NULL, 'menunggu_diproses', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-07-23 21:12:57', '2026-08-03 02:12:57'),
(22, 12, NULL, '1955000.00', '0.00', 'midtrans', NULL, NULL, 'Domenick Bashirian\n088889022987\nJl. Wintheiser Shores No. 127, Hermannton', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'cancelled', 'pending', NULL, NULL, 'menunggu_diproses', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-07-26 07:12:57', '2026-08-15 02:12:57'),
(23, 10, NULL, '1305000.00', '0.00', 'midtrans', NULL, NULL, 'Ozella Kris\n089624834723\nJl. Trenton Common No. 180, Hirtheshire', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'cancelled', 'pending', NULL, NULL, 'menunggu_diproses', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-07-19 21:12:57', '2026-08-16 02:12:57'),
(24, 12, NULL, '478000.00', '25000.00', 'midtrans', NULL, NULL, 'Domenick Bashirian\n085341537907\nJl. Kristina Ramp No. 118, North Theafurt', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'completed', 'pending', NULL, NULL, 'menunggu_diproses', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-07-25 17:12:57', '2026-08-30 02:12:57'),
(25, 28, NULL, '656000.00', '0.00', 'midtrans', NULL, NULL, 'Mr. Laverna Ebert\n084685003757\nJl. Wisoky Underpass No. 151, Bartonshire', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'pending', 'pending', NULL, NULL, 'menunggu_diproses', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-08-17 02:21:37', '2026-08-10 02:21:37'),
(26, 2, NULL, '4058000.00', '0.00', 'midtrans', NULL, NULL, 'Budiono Siregar\n087724803751\nJl. Sammie Plains No. 103, Maximilliaborough', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'completed', 'pending', NULL, NULL, 'menunggu_diproses', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-08-15 20:21:37', '2026-08-23 02:21:37'),
(27, 29, NULL, '1656000.00', '0.00', 'midtrans', NULL, NULL, 'Giovanny Pagac Sr.\n087010644830\nJl. Sauer Locks No. 17, O\'Reillymouth', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'completed', 'pending', NULL, NULL, 'menunggu_diproses', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-08-06 19:21:37', '2026-07-16 02:21:37'),
(28, 28, NULL, '314000.00', '25000.00', 'midtrans', NULL, NULL, 'Mr. Laverna Ebert\n088401765332\nJl. Meta Valleys No. 40, Dannieville', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'processing', 'pending', NULL, NULL, 'menunggu_diproses', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-07-20 19:21:37', '2026-07-14 02:21:37'),
(29, 29, NULL, '2098000.00', '0.00', 'midtrans', NULL, NULL, 'Giovanny Pagac Sr.\n080807993674\nJl. Ernesto Rapid No. 115, Felipahaven', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'completed', 'pending', NULL, NULL, 'menunggu_diproses', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-07-27 02:21:37', '2026-08-21 02:21:37'),
(30, 30, NULL, '1973000.00', '0.00', 'midtrans', NULL, NULL, 'Angie Stroman\n086095656471\nJl. Herman Causeway No. 72, South Annette', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'processing', 'pending', NULL, NULL, 'menunggu_diproses', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-08-13 05:21:37', '2026-08-07 02:21:37'),
(31, 32, NULL, '1724000.00', '0.00', 'midtrans', NULL, NULL, 'Mr. Jace Dickens DDS\n081177287747\nJl. Kreiger Key No. 14, West Jeremy', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'completed', 'pending', NULL, NULL, 'menunggu_diproses', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-08-15 11:21:37', '2026-08-21 02:21:37'),
(32, 32, NULL, '498000.00', '25000.00', 'midtrans', NULL, NULL, 'Mr. Jace Dickens DDS\n087472613445\nJl. Stark Field No. 91, Bahringerborough', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'processing', 'pending', NULL, NULL, 'menunggu_diproses', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-09-03 23:21:37', '2026-08-13 02:21:37'),
(33, 28, NULL, '178000.00', '25000.00', 'midtrans', NULL, NULL, 'Mr. Laverna Ebert\n086028755887\nJl. Collins Mills No. 93, East Owenport', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'cancelled', 'pending', NULL, NULL, 'menunggu_diproses', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-08-23 21:21:37', '2026-09-09 02:21:37'),
(34, 30, NULL, '558000.00', '0.00', 'midtrans', NULL, NULL, 'Angie Stroman\n080306679288\nJl. Watsica Cliffs No. 161, Friedahaven', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'completed', 'pending', NULL, NULL, 'menunggu_diproses', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-08-07 04:21:37', '2026-09-01 02:21:37'),
(35, 1, NULL, '897000.00', '0.00', 'midtrans', NULL, NULL, 'Admin\n088526297418\nJl. Titus Mountain No. 194, East Tryciaton', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'pending', 'pending', NULL, NULL, 'menunggu_diproses', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-09-01 08:21:37', '2026-07-21 02:21:37'),
(36, 32, NULL, '4058000.00', '0.00', 'midtrans', NULL, NULL, 'Mr. Jace Dickens DDS\n089699216123\nJl. Wisoky Forges No. 82, South Daphneside', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'processing', 'pending', NULL, NULL, 'menunggu_diproses', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-09-06 14:21:37', '2026-09-05 02:21:37'),
(37, 33, NULL, '499999.99', '164000.00', 'midtrans', 'ORDER-33-1788921956', '160e1606-045f-45e9-887b-0827b8f97c88', 'bahtra\n0895347660703\nJALLAN JALAN, PAPUA BARAT, MANOKWARI 40123\nIndonesia', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'domestic', 'Indonesia', 'Indonesia', 'MANOKWARI', 'PAPUA BARAT', '40123', '3081.69', '0.85', '1.64', '1.64', 'Zona 9', 'cancelled', 'cancelled', 'JNE', NULL, 'dibatalkan', NULL, NULL, NULL, NULL, '2026-09-09 02:47:36', NULL, '2026-09-09 03:00:57', '2026-09-09 02:45:57', '2026-09-09 02:47:36'),
(38, 33, NULL, '999999.98', '329000.00', 'midtrans', 'ORDER-33-1788922118', '85dd8cbb-34f7-41c0-a64c-ad8d8ab86a29', 'bahtra\n0895347660703\nJalan, PAPUA BARAT, MANOKWARI 40123\nIndonesia', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'domestic', 'Indonesia', 'Indonesia', 'MANOKWARI', 'PAPUA BARAT', '40123', '3081.69', '1.70', '3.29', '3.29', 'Zona 9', 'completed', 'paid', 'JNE', 'JNEE2764747', 'pesanan_diterima', '2026-09-09', '2026-09-10', '2026-09-09 17:00:00', '2026-09-09 02:52:12', '2026-09-09 02:52:12', '2026-09-09 02:49:37', '2026-09-09 03:03:39', '2026-09-09 02:48:39', '2026-09-09 02:52:12'),
(39, 37, NULL, '912000.00', '0.00', 'midtrans', NULL, NULL, 'Mr. Kale Waelchi V\n085567102350\nJl. Beahan Mount No. 6, Mistychester', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'pending', 'pending', NULL, NULL, 'menunggu_diproses', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-07-15 07:31:17', '2026-08-13 06:31:17'),
(40, 36, NULL, '1335000.00', '0.00', 'midtrans', NULL, NULL, 'Mr. Brian Harvey\n081685740322\nJl. Mayer Mews No. 79, East Ralphland', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'completed', 'pending', NULL, NULL, 'menunggu_diproses', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-08-15 02:31:17', '2026-09-08 06:31:17'),
(41, 36, NULL, '1983000.00', '0.00', 'midtrans', NULL, NULL, 'Mr. Brian Harvey\n083925842577\nJl. Olson Isle No. 93, East Yasmeenbury', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'cancelled', 'pending', NULL, NULL, 'menunggu_diproses', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-08-30 18:31:17', '2026-08-21 06:31:17'),
(42, 35, NULL, '823000.00', '0.00', 'midtrans', NULL, NULL, 'Dr. Julius Feeney\n084415393640\nJl. Liliane Shores No. 47, Lake Keeleyton', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'completed', 'pending', NULL, NULL, 'menunggu_diproses', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-07-19 19:31:18', '2026-08-29 06:31:18'),
(43, 2, NULL, '1074000.00', '0.00', 'midtrans', NULL, NULL, 'Budiono Siregar\n080119049315\nJl. Magdalena Junctions No. 38, West Darrionview', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'completed', 'pending', NULL, NULL, 'menunggu_diproses', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-08-26 13:31:18', '2026-07-15 06:31:18'),
(44, 37, NULL, '178000.00', '25000.00', 'midtrans', NULL, NULL, 'Mr. Kale Waelchi V\n083613545277\nJl. Susie Overpass No. 68, Reichelmouth', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'cancelled', 'pending', NULL, NULL, 'menunggu_diproses', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-08-25 23:31:18', '2026-08-05 06:31:18'),
(45, 2, NULL, '598000.00', '0.00', 'midtrans', NULL, NULL, 'Budiono Siregar\n081181641485\nJl. Nelle Ford No. 57, Kathleenmouth', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'completed', 'pending', NULL, NULL, 'menunggu_diproses', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-07-17 17:31:18', '2026-09-04 06:31:18'),
(46, 40, NULL, '700000.00', '0.00', 'midtrans', NULL, NULL, 'Yessenia Pfeffer\n084549443775\nJl. Jay Extensions No. 5, Mosciskichester', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'completed', 'pending', NULL, NULL, 'menunggu_diproses', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-08-10 17:31:18', '2026-08-24 06:31:18'),
(47, 36, NULL, '178000.00', '25000.00', 'midtrans', NULL, NULL, 'Mr. Brian Harvey\n082717566641\nJl. Nader Hills No. 53, VonRuedenfurt', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'cancelled', 'pending', NULL, NULL, 'menunggu_diproses', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-07-21 23:31:18', '2026-08-02 06:31:18'),
(48, 1, NULL, '850000.00', '0.00', 'midtrans', NULL, NULL, 'Admin\n082906528233\nJl. Dee Centers No. 124, Archibaldberg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'cancelled', 'pending', NULL, NULL, 'menunggu_diproses', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-07-18 14:31:18', '2026-08-22 06:31:18'),
(49, 40, NULL, '1847000.00', '0.00', 'midtrans', NULL, NULL, 'Yessenia Pfeffer\n081157705193\nJl. Crystal Valley No. 180, Beaulahbury', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'completed', 'pending', NULL, NULL, 'menunggu_diproses', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-09-07 18:31:18', '2026-08-24 06:31:18'),
(50, 35, NULL, '878000.00', '0.00', 'midtrans', NULL, NULL, 'Dr. Julius Feeney\n081212897592\nJl. Stark Forge No. 45, Emmetttown', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'completed', 'pending', NULL, NULL, 'menunggu_diproses', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-08-08 19:31:18', '2026-07-29 06:31:18'),
(51, 2, NULL, '250000.00', '22000.00', 'midtrans', 'ORDER-2-1789022954', 'cee4ec4b-7cf6-4d1c-801f-96a8a4aede80', 'Budiono Siregar\n085881978741\nJalan, Jawa Barat, Bandung 40123\nIndonesia', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'domestic', 'Indonesia', 'Indonesia', 'Bandung', 'Jawa Barat', '40123', '112.85', '0.60', '1.00', '1.00', 'Zona 5', 'pending_payment', 'pending', 'JNE', NULL, 'menunggu_diproses', NULL, NULL, NULL, NULL, NULL, NULL, '2026-09-10 07:04:15', '2026-09-10 06:49:15', '2026-09-10 06:49:15'),
(52, 2, NULL, '700000.00', '36080.00', 'midtrans', 'ORDER-2-1789323444', '6474459f-660c-448e-9ba4-93229965038e', 'Budiono Siregar\n085881978741\nJalan cilandak, Jawa Barat, Bandung 40123\nIndonesia', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'domestic', 'Indonesia', 'Indonesia', 'Bandung', 'Jawa Barat', '40123', '112.85', '0.85', '1.64', '1.64', 'Zona 5', 'pending', 'paid', 'JNE', NULL, 'menunggu_diproses', NULL, NULL, NULL, NULL, NULL, '2026-09-13 18:18:10', '2026-09-13 18:32:25', '2026-09-13 18:17:25', '2026-09-13 18:18:10'),
(53, 2, NULL, '499999.99', '16400.00', 'midtrans', 'ORDER-2-1789356504', '3a65fd77-46a9-455d-9528-05e2a8a028fe', 'Budiono Siregar\n085881978741\nJalan jalan, Jakarta, Jakarta Timur 13570\nIndonesia', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'domestic', 'Indonesia', 'Indonesia', 'Jakarta Timur', 'Jakarta', '13570', '7.49', '0.85', '1.64', '1.64', 'Zona 1', 'pending', 'paid', 'JNE', NULL, 'menunggu_diproses', NULL, NULL, NULL, NULL, NULL, '2026-09-14 03:29:20', '2026-09-14 03:43:26', '2026-09-14 03:28:26', '2026-09-14 03:29:20'),
(56, 7, NULL, '2325000.00', '0.00', 'midtrans', NULL, NULL, 'Clare Lowe\n080392346004\nJl. Cody Drive No. 35, North Juneview', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'completed', 'pending', NULL, NULL, 'menunggu_diproses', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-09-09 18:08:17', '2026-07-20 16:08:17'),
(57, 14, NULL, '850000.00', '0.00', 'midtrans', NULL, NULL, 'Genevieve Daugherty PhD\n088041931681\nJl. Emilia Run No. 45, Hansborough', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'pending', 'pending', NULL, NULL, 'menunggu_diproses', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-08-12 07:08:17', '2026-08-26 16:08:17'),
(58, 20, NULL, '4460000.00', '0.00', 'midtrans', NULL, NULL, 'Matt Franecki\n081854750510\nJl. Flatley Drives No. 63, West Ettiebury', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'completed', 'pending', NULL, NULL, 'menunggu_diproses', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-08-21 07:08:17', '2026-08-19 16:08:17'),
(59, 23, NULL, '400000.00', '25000.00', 'midtrans', NULL, NULL, 'Prof. Junius Schroeder III\n087319830398\nJl. Klocko Drives No. 65, Hellenbury', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'pending', 'pending', NULL, NULL, 'menunggu_diproses', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-08-30 13:08:17', '2026-09-11 16:08:17'),
(60, 5, NULL, '1700000.00', '0.00', 'midtrans', NULL, NULL, 'Ena Dach\n086741080303\nJl. Carmine Dale No. 189, Missouriview', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'cancelled', 'pending', NULL, NULL, 'menunggu_diproses', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-08-24 19:08:17', '2026-08-13 16:08:17'),
(61, 22, NULL, '5000000.00', '0.00', 'midtrans', NULL, NULL, 'Ida Mraz\n084441903466\nJl. Lester Mews No. 186, Bergnaumborough', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'cancelled', 'pending', NULL, NULL, 'menunggu_diproses', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-08-14 14:08:17', '2026-08-10 16:08:17'),
(62, 10, NULL, '500000.00', '0.00', 'midtrans', NULL, NULL, 'Ozella Kris\n085163217579\nJl. Jakubowski Rapid No. 52, North Anika', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'completed', 'pending', NULL, NULL, 'menunggu_diproses', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-08-09 01:08:17', '2026-09-09 16:08:17'),
(63, 30, NULL, '215000.00', '25000.00', 'midtrans', NULL, NULL, 'Angie Stroman\n081609583800\nJl. Jude Bridge No. 77, Vonville', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'cancelled', 'pending', NULL, NULL, 'menunggu_diproses', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-08-09 23:08:17', '2026-08-24 16:08:17'),
(64, 16, NULL, '710000.00', '0.00', 'midtrans', NULL, NULL, 'Mr. Dalton Bernier Sr.\n080728105412\nJl. Salvador Hollow No. 74, Gerdaville', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'pending', 'pending', NULL, NULL, 'menunggu_diproses', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-08-23 22:08:17', '2026-08-28 16:08:17'),
(65, 32, NULL, '1750000.00', '0.00', 'midtrans', NULL, NULL, 'Mr. Jace Dickens DDS\n082002531183\nJl. Tina Underpass No. 14, Lake Presleyhaven', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'cancelled', 'pending', NULL, NULL, 'menunggu_diproses', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-09-12 00:08:17', '2026-07-25 16:08:17'),
(66, 9, NULL, '700000.00', '0.00', 'midtrans', NULL, NULL, 'Isom Schumm\n085654992415\nJl. Emmerich Rue No. 134, East Leannebury', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'completed', 'pending', NULL, NULL, 'menunggu_diproses', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-08-18 04:08:17', '2026-08-25 16:08:17'),
(67, 22, NULL, '2398000.00', '0.00', 'midtrans', NULL, NULL, 'Ida Mraz\n083174453799\nJl. Lucious Parks No. 127, Yazminshire', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'pending', 'pending', NULL, NULL, 'menunggu_diproses', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-09-13 14:08:17', '2026-08-25 16:08:17');

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
(4, 2, 1, NULL, 1, '299000.00', '2026-09-09 02:12:37', '2026-09-09 02:12:37'),
(23, 13, 32, NULL, 3, '267000.00', '2026-09-09 02:12:57', '2026-09-09 02:12:57'),
(25, 14, 19, NULL, 3, '447000.00', '2026-09-09 02:12:57', '2026-09-09 02:12:57'),
(26, 14, 32, NULL, 1, '89000.00', '2026-09-09 02:12:57', '2026-09-09 02:12:57'),
(27, 15, 32, NULL, 1, '89000.00', '2026-09-09 02:12:57', '2026-09-09 02:12:57'),
(28, 16, 26, NULL, 2, '538000.00', '2026-09-09 02:12:57', '2026-09-09 02:12:57'),
(30, 17, 23, NULL, 2, '698000.00', '2026-09-09 02:12:57', '2026-09-09 02:12:57'),
(31, 18, 26, NULL, 3, '807000.00', '2026-09-09 02:12:57', '2026-09-09 02:12:57'),
(32, 18, 25, NULL, 1, '329000.00', '2026-09-09 02:12:57', '2026-09-09 02:12:57'),
(33, 18, 30, NULL, 2, '318000.00', '2026-09-09 02:12:57', '2026-09-09 02:12:57'),
(34, 19, 31, NULL, 1, '239000.00', '2026-09-09 02:12:57', '2026-09-09 02:12:57'),
(37, 21, 27, NULL, 2, '998000.00', '2026-09-09 02:12:57', '2026-09-09 02:12:57'),
(38, 21, 24, NULL, 2, '498000.00', '2026-09-09 02:12:57', '2026-09-09 02:12:57'),
(42, 23, 23, NULL, 3, '1047000.00', '2026-09-09 02:12:57', '2026-09-09 02:12:57'),
(43, 23, 29, NULL, 2, '258000.00', '2026-09-09 02:12:57', '2026-09-09 02:12:57'),
(44, 24, 31, NULL, 2, '478000.00', '2026-09-09 02:12:57', '2026-09-09 02:12:57'),
(45, 25, 16, NULL, 2, '178000.00', '2026-09-09 02:21:37', '2026-09-09 02:21:37'),
(46, 25, 31, NULL, 2, '478000.00', '2026-09-09 02:21:37', '2026-09-09 02:21:37'),
(47, 26, 23, NULL, 3, '2100000.00', '2026-09-09 02:21:37', '2026-09-09 02:21:37'),
(48, 26, 29, NULL, 2, '258000.00', '2026-09-09 02:21:37', '2026-09-09 02:21:37'),
(49, 26, 28, NULL, 2, '1700000.00', '2026-09-09 02:21:37', '2026-09-09 02:21:37'),
(50, 27, 25, NULL, 2, '658000.00', '2026-09-09 02:21:37', '2026-09-09 02:21:37'),
(51, 27, 27, NULL, 2, '998000.00', '2026-09-09 02:21:37', '2026-09-09 02:21:37'),
(52, 28, 16, NULL, 1, '89000.00', '2026-09-09 02:21:37', '2026-09-09 02:21:37'),
(53, 28, 26, NULL, 3, '225000.00', '2026-09-09 02:21:37', '2026-09-09 02:21:37'),
(54, 29, 17, NULL, 2, '598000.00', '2026-09-09 02:21:37', '2026-09-09 02:21:37'),
(55, 29, 30, NULL, 3, '1500000.00', '2026-09-09 02:21:37', '2026-09-09 02:21:37'),
(56, 30, 17, NULL, 3, '897000.00', '2026-09-09 02:21:37', '2026-09-09 02:21:37'),
(57, 30, 25, NULL, 1, '329000.00', '2026-09-09 02:21:37', '2026-09-09 02:21:37'),
(58, 30, 24, NULL, 3, '747000.00', '2026-09-09 02:21:37', '2026-09-09 02:21:37'),
(59, 31, 26, NULL, 1, '75000.00', '2026-09-09 02:21:37', '2026-09-09 02:21:37'),
(60, 31, 19, NULL, 1, '149000.00', '2026-09-09 02:21:37', '2026-09-09 02:21:37'),
(61, 31, 30, NULL, 3, '1500000.00', '2026-09-09 02:21:37', '2026-09-09 02:21:37'),
(62, 32, 24, NULL, 2, '498000.00', '2026-09-09 02:21:37', '2026-09-09 02:21:37'),
(63, 33, 16, NULL, 2, '178000.00', '2026-09-09 02:21:37', '2026-09-09 02:21:37'),
(64, 34, 22, NULL, 2, '558000.00', '2026-09-09 02:21:37', '2026-09-09 02:21:37'),
(65, 35, 1, NULL, 3, '897000.00', '2026-09-09 02:21:37', '2026-09-09 02:21:37'),
(66, 36, 28, NULL, 2, '1700000.00', '2026-09-09 02:21:37', '2026-09-09 02:21:37'),
(67, 36, 29, NULL, 2, '258000.00', '2026-09-09 02:21:37', '2026-09-09 02:21:37'),
(68, 36, 23, NULL, 3, '2100000.00', '2026-09-09 02:21:37', '2026-09-09 02:21:37'),
(69, 37, 31, NULL, 1, '499999.99', '2026-09-09 02:45:57', '2026-09-09 02:45:57'),
(70, 38, 31, NULL, 1, '499999.99', '2026-09-09 02:48:39', '2026-09-09 02:48:39'),
(71, 38, 31, NULL, 1, '499999.99', '2026-09-09 02:48:39', '2026-09-09 02:48:39'),
(72, 39, 26, NULL, 1, '75000.00', '2026-09-09 06:31:17', '2026-09-09 06:31:17'),
(73, 39, 22, NULL, 3, '837000.00', '2026-09-09 06:31:17', '2026-09-09 06:31:17'),
(74, 40, 22, NULL, 3, '837000.00', '2026-09-09 06:31:17', '2026-09-09 06:31:17'),
(75, 40, 24, NULL, 2, '498000.00', '2026-09-09 06:31:17', '2026-09-09 06:31:17'),
(76, 41, 22, NULL, 1, '279000.00', '2026-09-09 06:31:17', '2026-09-09 06:31:17'),
(77, 41, 31, NULL, 3, '717000.00', '2026-09-09 06:31:17', '2026-09-09 06:31:17'),
(78, 41, 25, NULL, 3, '987000.00', '2026-09-09 06:31:17', '2026-09-09 06:31:17'),
(79, 42, 26, NULL, 3, '225000.00', '2026-09-09 06:31:18', '2026-09-09 06:31:18'),
(80, 42, 17, NULL, 2, '598000.00', '2026-09-09 06:31:18', '2026-09-09 06:31:18'),
(81, 43, 16, NULL, 2, '178000.00', '2026-09-09 06:31:18', '2026-09-09 06:31:18'),
(82, 43, 19, NULL, 2, '298000.00', '2026-09-09 06:31:18', '2026-09-09 06:31:18'),
(83, 43, 17, NULL, 2, '598000.00', '2026-09-09 06:31:18', '2026-09-09 06:31:18'),
(84, 44, 16, NULL, 2, '178000.00', '2026-09-09 06:31:18', '2026-09-09 06:31:18'),
(85, 45, 1, NULL, 2, '598000.00', '2026-09-09 06:31:18', '2026-09-09 06:31:18'),
(86, 46, 23, NULL, 1, '700000.00', '2026-09-09 06:31:18', '2026-09-09 06:31:18'),
(87, 47, 32, NULL, 2, '178000.00', '2026-09-09 06:31:18', '2026-09-09 06:31:18'),
(88, 48, 28, NULL, 1, '850000.00', '2026-09-09 06:31:18', '2026-09-09 06:31:18'),
(89, 49, 19, NULL, 3, '447000.00', '2026-09-09 06:31:18', '2026-09-09 06:31:18'),
(90, 49, 23, NULL, 2, '1400000.00', '2026-09-09 06:31:18', '2026-09-09 06:31:18'),
(91, 50, 23, NULL, 1, '700000.00', '2026-09-09 06:31:18', '2026-09-09 06:31:18'),
(92, 50, 32, NULL, 2, '178000.00', '2026-09-09 06:31:18', '2026-09-09 06:31:18'),
(93, 51, 16, NULL, 1, '250000.00', '2026-09-10 06:49:15', '2026-09-10 06:49:15'),
(94, 52, 23, NULL, 1, '700000.00', '2026-09-13 18:17:25', '2026-09-13 18:17:25'),
(95, 53, 31, NULL, 1, '499999.99', '2026-09-14 03:28:26', '2026-09-14 03:28:26'),
(97, 56, 23, NULL, 2, '1400000.00', '2026-09-14 16:08:17', '2026-09-14 16:08:17'),
(98, 56, 26, NULL, 1, '75000.00', '2026-09-14 16:08:17', '2026-09-14 16:08:17'),
(99, 56, 28, NULL, 1, '850000.00', '2026-09-14 16:08:17', '2026-09-14 16:08:17'),
(100, 57, 26, NULL, 2, '150000.00', '2026-09-14 16:08:17', '2026-09-14 16:08:17'),
(101, 57, 23, NULL, 1, '700000.00', '2026-09-14 16:08:17', '2026-09-14 16:08:17'),
(102, 58, 17, NULL, 3, '210000.00', '2026-09-14 16:08:17', '2026-09-14 16:08:17'),
(103, 58, 25, NULL, 3, '3750000.00', '2026-09-14 16:08:17', '2026-09-14 16:08:17'),
(104, 58, 29, NULL, 2, '500000.00', '2026-09-14 16:08:17', '2026-09-14 16:08:17'),
(105, 59, 22, NULL, 1, '400000.00', '2026-09-14 16:08:17', '2026-09-14 16:08:17'),
(106, 60, 29, NULL, 2, '500000.00', '2026-09-14 16:08:17', '2026-09-14 16:08:17'),
(107, 60, 22, NULL, 3, '1200000.00', '2026-09-14 16:08:17', '2026-09-14 16:08:17'),
(108, 61, 25, NULL, 3, '3750000.00', '2026-09-14 16:08:17', '2026-09-14 16:08:17'),
(109, 61, 28, NULL, 1, '850000.00', '2026-09-14 16:08:17', '2026-09-14 16:08:17'),
(110, 61, 22, NULL, 1, '400000.00', '2026-09-14 16:08:17', '2026-09-14 16:08:17'),
(111, 62, 16, NULL, 2, '500000.00', '2026-09-14 16:08:17', '2026-09-14 16:08:17'),
(112, 63, 17, NULL, 2, '140000.00', '2026-09-14 16:08:17', '2026-09-14 16:08:17'),
(113, 63, 26, NULL, 1, '75000.00', '2026-09-14 16:08:17', '2026-09-14 16:08:17'),
(114, 64, 17, NULL, 3, '210000.00', '2026-09-14 16:08:17', '2026-09-14 16:08:17'),
(115, 64, 29, NULL, 2, '500000.00', '2026-09-14 16:08:17', '2026-09-14 16:08:17'),
(116, 65, 16, NULL, 3, '750000.00', '2026-09-14 16:08:17', '2026-09-14 16:08:17'),
(117, 65, 19, NULL, 2, '1000000.00', '2026-09-14 16:08:17', '2026-09-14 16:08:17'),
(118, 66, 22, NULL, 1, '400000.00', '2026-09-14 16:08:17', '2026-09-14 16:08:17'),
(119, 66, 26, NULL, 2, '150000.00', '2026-09-14 16:08:17', '2026-09-14 16:08:17'),
(120, 66, 27, NULL, 2, '150000.00', '2026-09-14 16:08:17', '2026-09-14 16:08:17'),
(121, 67, 22, NULL, 1, '400000.00', '2026-09-14 16:08:17', '2026-09-14 16:08:17'),
(122, 67, 23, NULL, 2, '1400000.00', '2026-09-14 16:08:18', '2026-09-14 16:08:18'),
(123, 67, 1, NULL, 2, '598000.00', '2026-09-14 16:08:18', '2026-09-14 16:08:18');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `username` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `role` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'user',
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active',
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `username`, `email`, `role`, `status`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'Admin', 'admin', 'adminalgo@gmail.com', 'admin', 'active', NULL, '$2y$12$fmLh/g8jL4.cFCKkeNneI.xMI9FRBLNrO3Oevkt4Qz6zzmBeuMWXa', NULL, '2026-09-08 19:12:32', '2026-09-14 16:08:17'),
(2, 'Budiono Siregar', 'budi', 'usernamealgo@gmail.com', 'user', 'active', NULL, '$2y$12$w5oT2gRe4dNfI5cl5IV8T.31RpdrD9QS2dwdrBT1wfXpZnmTaQ1R2', NULL, '2026-09-08 19:12:33', '2026-09-14 16:08:17'),
(3, 'Katherine Luettgen', 'art830', 'rosemarie.green@example.org', 'user', 'active', NULL, '$2y$12$LvMgIWATOCBZkml4hCTgB.04Rz8x/wn5fMk0S5m2vQauOPkQT/jYS', NULL, '2026-09-08 19:12:34', '2026-09-14 16:08:17'),
(4, 'Sandy Walker Jr.', 'rosenbaum.clemmie1', 'gilda.smith@example.net', 'user', 'suspended', NULL, '$2y$12$g3UBm/pU3StY5ehXbcny9.5Vgl3qybsWz4sTW5r8ASZOFEu7WmY9G', NULL, '2026-09-08 19:12:35', '2026-09-14 16:08:17'),
(5, 'Ena Dach', 'lkohler2', 'tristin.west@example.net', 'user', 'suspended', NULL, '$2y$12$3TVndunU.pbV9KOQ/aXSD.5sDbDBeIoxhrxdykK0urIvRE6nw.p2q', NULL, '2026-09-08 19:12:35', '2026-09-14 16:08:17'),
(6, 'Maverick Upton', 'ursula763', 'vankunding@example.org', 'user', 'active', NULL, '$2y$12$kaQn.AspMS4D.dxlkMAH6efQPIG3hc5RTdkMJosmz1C2UZFQrLG6K', NULL, '2026-09-08 19:12:36', '2026-09-14 16:08:17'),
(7, 'Clare Lowe', 'laney484', 'emard.rosella@example.com', 'user', 'active', NULL, '$2y$12$Rkdb1e.HlrupeOjUqK9rsOjxY/DbyQI4C8CF.up5egkPg5K/ORRrC', NULL, '2026-09-08 19:12:36', '2026-09-14 16:08:17'),
(8, 'Prof. Rafael McCullough', 'zander.thiel5', 'kelvin12@example.net', 'user', 'active', NULL, '$2y$12$rHgCLzpzP4RrrgNyz9PnieCsR7vj10Y9pDH5G/C9RJa1N0WX2VnA.', NULL, '2026-09-08 19:12:37', '2026-09-14 16:08:17'),
(9, 'Isom Schumm', 'cole.kuphal0', 'mark.hodkiewicz@example.org', 'user', 'active', NULL, '$2y$12$Q9o0QDLxcQAT4M2p4weiTewqEl0Rf2LQGVQbfL5tD4ucpINY/QL7O', NULL, '2026-09-08 19:12:55', '2026-09-14 16:08:17'),
(10, 'Ozella Kris', 'kody921', 'oswaniawski@example.com', 'user', 'active', NULL, '$2y$12$KeuP/JnvUi752AEKZBbhVeHYUPTIYnYUnDumkSCkAddn3wVR7HzxW', NULL, '2026-09-08 19:12:55', '2026-09-14 16:08:17'),
(11, 'Brennan Schultz', 'marcelle592', 'yasmeen.dickinson@example.org', 'user', 'active', NULL, '$2y$12$eNAToRc4zopCLiJmg3lENeVLp86zsw1CtEb7d8wX147nBlpLL21Tq', NULL, '2026-09-08 19:12:56', '2026-09-14 16:08:17'),
(12, 'Domenick Bashirian', 'ewell.lindgren3', 'elwyn09@example.org', 'user', 'suspended', NULL, '$2y$12$z00VyW4/86Gqw..QkYFCtOcufeFEJsR40V..uMDX1mXyMDgdXFW3q', NULL, '2026-09-08 19:12:56', '2026-09-14 16:08:17'),
(13, 'Dr. Aliza Veum II', 'braun.stephany4', 'srenner@example.org', 'user', 'active', NULL, '$2y$12$Bmix7/BFZatIVocxB9PuFehYCyKbxyiqZDOBZ2ZxyFVKVkeMb9J4q', NULL, '2026-09-08 19:12:57', '2026-09-14 16:08:17'),
(14, 'Genevieve Daugherty PhD', 'kris.tressie5', 'zack47@example.com', 'user', 'active', NULL, '$2y$12$8gVRuHB3TaR08xhQ4p79l.ytt.sZBo7ead8m.tU0k0slxDE8KE2qe', NULL, '2026-09-08 19:12:57', '2026-09-14 16:08:17'),
(15, 'Prof. Aileen Nienow III', 'schamberger.alice0', 'gottlieb.aliyah@example.net', 'user', 'suspended', NULL, '$2y$12$KCzif9hA0Akq5c8dWo5ebOE.yRAsYd0sqJjQDI0Oa5lEoXq26yTxG', NULL, '2026-09-08 19:20:40', '2026-09-14 16:08:17'),
(16, 'Mr. Dalton Bernier Sr.', 'demarco961', 'freeda.donnelly@example.com', 'user', 'active', NULL, '$2y$12$2iev0ai146S0bZEPfSFF.e0jEvnaPD/97fX..3CexD5Z/y8BeKWNe', NULL, '2026-09-08 19:20:41', '2026-09-14 16:08:17'),
(17, 'Paula Howe', 'annette.yundt2', 'pledner@example.org', 'user', 'active', NULL, '$2y$12$dcKx.M3qLJvVUVi80KDRTefGWGPi.2Q9LQ6w28eHASovRpkGEUf9.', NULL, '2026-09-08 19:20:41', '2026-09-14 16:08:17'),
(18, 'Ms. Susan Langworth V', 'hrogahn3', 'kihn.manuela@example.org', 'user', 'active', NULL, '$2y$12$hxBFbGShXIyFj5CvB8XgBORq7dIR/nkpKU17cqGb2w/rMvlF2m1QC', NULL, '2026-09-08 19:20:41', '2026-09-14 16:08:17'),
(19, 'Avery Nolan', 'flo.paucek4', 'mervin.sporer@example.net', 'user', 'suspended', NULL, '$2y$12$WJ7csqe0OjHam7u2DQOoi.K2gafv9RN9MSsdqZzh76GwEprXR3m1a', NULL, '2026-09-08 19:20:42', '2026-09-14 16:08:17'),
(20, 'Matt Franecki', 'elody385', 'marquardt.jazlyn@example.com', 'user', 'active', NULL, '$2y$12$RSlJBNiiQNZ97hVtlgSZp.Zlsdbv4Yu958oDgWHX4cHW1mhvXvPpa', NULL, '2026-09-08 19:20:42', '2026-09-14 16:08:17'),
(21, 'Miss Romaine Powlowski', 'schimmel.hudson0', 'makayla16@example.org', 'user', 'active', NULL, '$2y$12$vI8b.tkCaU9KSPFrvbHUreFe9hp68crmXxEwnD3PgM6dUaYUNZfsC', NULL, '2026-09-08 19:21:11', '2026-09-14 16:08:17'),
(22, 'Ida Mraz', 'gschamberger1', 'lia37@example.com', 'user', 'active', NULL, '$2y$12$6oIN9E4Iv27P4tXubFK.2eHffQyTkT/RuRqZbkfky4wxIQhWDwSke', NULL, '2026-09-08 19:21:11', '2026-09-14 16:08:17'),
(23, 'Prof. Junius Schroeder III', 'anais032', 'vblock@example.com', 'user', 'active', NULL, '$2y$12$YprnZFP8dW6xgfScGpllw.LBszOF2.39AdjPW4/cm4vT8zNaJ4amS', NULL, '2026-09-08 19:21:12', '2026-09-14 16:08:17'),
(24, 'Tito Rutherford', 'clinton593', 'xkoepp@example.com', 'user', 'active', NULL, '$2y$12$dQnRnrLG.U.tQMJr5Mx60enaB74kz7w5NhpijPpGkWc0OIW/ayHkS', NULL, '2026-09-08 19:21:12', '2026-09-14 16:08:17'),
(25, 'Prof. Ludie Stokes II', 'labadie.lelah4', 'burley98@example.org', 'user', 'active', NULL, '$2y$12$ccwxiF2Hy8ZjjPSaSSYQ/OGf03cO1qGS4PpnYOYwypMbVHCN.q23W', NULL, '2026-09-08 19:21:13', '2026-09-14 16:08:17'),
(26, 'Miracle Morissette', 'jmclaughlin5', 'stokes.cynthia@example.com', 'user', 'active', NULL, '$2y$12$irWIlCXb2VZnvlfKZ5xrbuTzzj.tqXFArk5MgKXut5OGXlS3M8EDu', NULL, '2026-09-08 19:21:13', '2026-09-14 16:08:17'),
(27, 'Dr. Ewell Mayert V', 'leffler.agustin0', 'drew.bednar@example.net', 'user', 'active', NULL, '$2y$12$1ZfUgmDJtTUdDk.vLdCbH.IBC.hi1FAFsaVZE2MF9xEG2QcxCvIQq', NULL, '2026-09-08 19:21:34', '2026-09-14 16:08:17'),
(28, 'Mr. Laverna Ebert', 'susan.hoeger1', 'ankunding.gilberto@example.org', 'user', 'active', NULL, '$2y$12$UlmqgwE4Wd4Xncv/mPGsCeS/XpurdlIOe4XiVQ87Wy2oiucl6LuoW', NULL, '2026-09-08 19:21:35', '2026-09-14 16:08:17'),
(29, 'Giovanny Pagac Sr.', 'dbogisich2', 'kilback.cornelius@example.org', 'user', 'active', NULL, '$2y$12$XLQPFDDdNuv8O/F3kv6P4OJ1h9qbI4nib0FrSf1PMA3/cy/M64j4W', NULL, '2026-09-08 19:21:35', '2026-09-14 16:08:17'),
(30, 'Angie Stroman', 'mills.lance3', 'gschiller@example.net', 'user', 'active', NULL, '$2y$12$S8yVMdb5hQhmjnERiDYJ/eUg2DT2.id5KwyH3pzvvKKRtQUqTWgPW', NULL, '2026-09-08 19:21:36', '2026-09-14 16:08:17'),
(31, 'Newell Hermiston', 'scole4', 'upagac@example.com', 'user', 'suspended', NULL, '$2y$12$fucIpNEbG8Iwb9sGmJWSmuQx0pB2bE7rXSEBmRYAfdje/yKxv3dK2', NULL, '2026-09-08 19:21:36', '2026-09-14 16:08:17'),
(32, 'Mr. Jace Dickens DDS', 'hassan.stanton5', 'kuhn.arch@example.net', 'user', 'active', NULL, '$2y$12$X01diFGg3mpgdJWECoE0qOecHXRHzUCdLLzHw1bOiIWwfmqO9/SHW', NULL, '2026-09-08 19:21:37', '2026-09-14 16:08:17'),
(33, 'bahtra', 'bahtra', 'bhatrabata2@gmail.com', 'user', 'active', NULL, '$2y$12$i3P7M16HYHjCCenP2/ptk.oZgkf7cgbtjVk873SRbQS2uME7Iya/2', NULL, '2026-09-08 19:40:25', '2026-09-14 16:08:17'),
(35, 'Dr. Julius Feeney', 'owiegand0', 'jakubowski.rex@example.org', 'user', 'active', NULL, '$2y$12$BM4/AQ48v5OUJNkypn8.7.M0AC/7UM.i41DUp0qSFWZUKRAeW.RCS', NULL, '2026-09-08 23:31:15', '2026-09-14 16:08:17'),
(36, 'Mr. Brian Harvey', 'murray.reilly1', 'dhirthe@example.net', 'user', 'active', NULL, '$2y$12$XvirUuFLD2FOPkjCUTYEuOE/CHKFC0twhBrecSaGOhEy7TE6iuH1G', NULL, '2026-09-08 23:31:15', '2026-09-14 16:08:17'),
(37, 'Mr. Kale Waelchi V', 'becker.suzanne2', 'mdavis@example.net', 'user', 'active', NULL, '$2y$12$rqUg5LU9BG/G8QzWG/UI3ugXF2PRimVzETtSCjsu2ds4JfPiStiiO', NULL, '2026-09-08 23:31:16', '2026-09-14 16:08:17'),
(38, 'Tristian Glover', 'ferry.anastacio3', 'osborne.bruen@example.com', 'user', 'active', NULL, '$2y$12$g.J4n0Z08QxGpTB1OrOweeXQBqOi8Rtn4nbMEOh9utpFoeFvIWSgC', NULL, '2026-09-08 23:31:16', '2026-09-14 16:08:17'),
(39, 'Prof. Elbert Langworth', 'bernice084', 'henry69@example.org', 'user', 'active', NULL, '$2y$12$5XPRM3ahwqFahkmFx7GqCOqzehUpzxZa68DddaobNMIDEeO5.febq', NULL, '2026-09-08 23:31:17', '2026-09-14 16:08:17'),
(40, 'Yessenia Pfeffer', 'lora.waters5', 'ggraham@example.com', 'user', 'suspended', NULL, '$2y$12$VLdE2V5yONKbrH/CQDyLleiadHdP8mdAthtf6KzEpaolvass/YzFm', NULL, '2026-09-08 23:31:17', '2026-09-14 16:08:17'),
(43, 'gssggsg hddhhddhhd', 'dhud', 'priatuaqw@gmail.com', 'user', 'active', NULL, '$2y$12$qaqJ5neEzxdxZC8JypHViu0Q8caQWTBxFqoAc/JVPcZpE8vmW9Ajy', NULL, '2026-09-10 06:21:40', '2026-09-14 16:08:17'),
(44, 'gssggsg hddhhddhhd', 'dhdhd', 'ewjebdjbjw1wjdj@gmail.com', 'user', 'active', NULL, '$2y$12$qPKaZ7l.smO8/EC9qc7Li.zXHjvqSUMPnaJGCEb5Pg3Kvv6GDHSYC', NULL, '2026-09-13 20:03:54', '2026-09-14 16:08:17');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `addresses`
--
ALTER TABLE `addresses`
  ADD PRIMARY KEY (`id`),
  ADD KEY `addresses_user_id_is_default_index` (`user_id`,`is_default`);

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
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

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
  ADD UNIQUE KEY `product_variants_sku_unique` (`sku`),
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
  ADD KEY `transactions_user_id_foreign` (`user_id`),
  ADD KEY `transactions_address_id_foreign` (`address_id`);

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
-- AUTO_INCREMENT for table `addresses`
--
ALTER TABLE `addresses`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `featured_products`
--
ALTER TABLE `featured_products`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `flash_sales`
--
ALTER TABLE `flash_sales`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `international_regions`
--
ALTER TABLE `international_regions`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT for table `product_variants`
--
ALTER TABLE `product_variants`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=61;

--
-- AUTO_INCREMENT for table `shipping_countries`
--
ALTER TABLE `shipping_countries`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

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
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `transactions`
--
ALTER TABLE `transactions`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=68;

--
-- AUTO_INCREMENT for table `transaction_details`
--
ALTER TABLE `transaction_details`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=124;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=45;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `addresses`
--
ALTER TABLE `addresses`
  ADD CONSTRAINT `addresses_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

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
  ADD CONSTRAINT `transactions_address_id_foreign` FOREIGN KEY (`address_id`) REFERENCES `addresses` (`id`) ON DELETE SET NULL,
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
