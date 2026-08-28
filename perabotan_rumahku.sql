-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Aug 27, 2026 at 01:59 PM
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
-- Database: `perabotan_rumahku`
--

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
(8, '2025_01_01_000005_create_transaction_details_table', 1);

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
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `name`, `category`, `image`, `description`, `stock`, `price`, `created_at`, `updated_at`) VALUES
(1, 'Celana Jeans', 'Bottoms', 'products/Fbc8UQUgpwvCxmiR8fbJRwMlsbQ8ZiXPk7s7LYhi.jpg', 'celana jeans kekinian dan terbaru 2026', 7, '300000.00', '2026-08-05 04:55:23', '2026-08-26 23:04:59'),
(16, 'Hoodie Black mamba', 'Outerwear', 'products/MMGkdTmHX3HtdQU73bMmAkoCqlcyPaueJFXygFQ7.jpg', NULL, 100, '250000.00', '2026-08-19 21:04:45', '2026-08-19 21:04:45'),
(17, 'Topi Merah', 'Hats', 'products/dhDNPcGSdGvkrYOuEVIHnf0JHwwnpnvAIhpRA4cd.jpg', 'topi merah polos', 52, '75000.00', '2026-08-19 21:21:58', '2026-08-27 04:42:32'),
(19, 'Sepatu Kulit', 'Footwear', 'products/CPau4usMBGuNUxLdnZrqYjkdlY7h1yp4OWAIAUqJ.jpg', 'sepatu kulit warna hitam', 99, '500000.00', '2026-08-19 21:28:48', '2026-08-26 23:04:59'),
(22, 'Sepatu lari', 'Footwear', 'products/CSC4ir1CgWbjiyjTLsQdKz2hFnn6eUP2jlwko2Dh.jpg', NULL, 100, '400000.00', '2026-08-25 03:54:46', '2026-08-25 09:27:21'),
(23, 'Nike Air Jordan Wanita', 'Footwear', 'products/ucpvuE3z6uGYQeExweXKlTtZLVJifmtGdT8PNdvP.jpg', 'Nike Air Jordan Gorpcore Indie Sneakers Wanita white pink', 97, '700000.00', '2026-08-25 07:42:33', '2026-08-27 04:57:25'),
(24, 'Stay Bag', 'Bags', 'products/JApW0FN7fcX1esggJWOY8gNWTVBzKeZsTBvpuMpk.webp', 'Stay Bag Black and Brown', 999, '1890000.00', '2026-08-25 07:45:00', '2026-08-25 10:27:35'),
(25, 'Berto\'s Hat', 'Hats', 'products/F0BSfxSn0nM0IodKu7zjgWpzbQ2EtojF51T3g5EM.webp', NULL, 89, '1250000.00', '2026-08-25 07:52:39', '2026-08-25 10:28:53'),
(26, 'Kaos Kasual Pria', 'Casual T-Shirt', 'products/2j4rh4MDlV84hAvvH9Rrn3DY1vIvZ6CtnDKmPazm.webp', 'Kaos kasual pria warna putih', 99, '75000.00', '2026-08-25 09:19:11', '2026-08-27 04:58:01'),
(27, 'Kaos Kasual Pria', 'Casual T-Shirt', 'products/pNNyFcN4llcCid6fn0byzEKVyyO97EypzCctW8Tk.webp', 'Kaos kasual pria warna putih', 100, '75000.00', '2026-08-25 09:21:02', '2026-08-25 09:21:02'),
(28, 'Jaket The North Face', 'Outerwear', 'products/FjsxbEyu7IDrip2ezo6aU89WXLhslTyB0XkDFRbl.jpg', NULL, 100, '850000.00', '2026-08-25 09:29:03', '2026-08-25 09:29:03'),
(29, 'Tas Coklat', 'Bags', 'products/htNA5swNtrpJT9UXmZrcibIZzJIGX10EF7Jhpwks.jpg', NULL, 100, '250000.00', '2026-08-25 09:30:23', '2026-08-25 09:30:23'),
(30, 'Jas Hitam', 'Formal Wear', 'products/Q4c1RfIgfiIEZI0HTeQhgbie0mxiXwujuRhHs8zX.jpg', NULL, 100, '500000.00', '2026-08-25 09:44:48', '2026-08-25 09:44:48'),
(31, 'Sepatu Nike Merah', 'Footwear', 'products/SZIXgjMhAMTxU34ajEJUNd2HSVcR8zrmNSxJ7poh.jpg', NULL, 1000, '950000.00', '2026-08-27 06:56:09', '2026-08-27 06:56:09'),
(32, 'Jaket Kulit Hitam', 'Outerwear', 'products/H1PpCwgLIpbSgkHhWGq8D7s9XIXGTbJCwxwPtPQS.jpg', NULL, 100, '500000.00', '2026-08-27 06:58:25', '2026-08-27 06:58:25');

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
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `payload` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('bLtm84yBx5UvsnLGjoyCh3ZESeRuYtte3pCt4B9n', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36 Edg/151.0.0.0', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiVW1Odk04OTRKMHhLRzJkOWhVbFAxbXA1cnN5Q0tXTGY0Q0hyM1NLVSI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJuZXciO2E6MDp7fXM6Mzoib2xkIjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MjE6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMCI7czo1OiJyb3V0ZSI7czo0OiJob21lIjt9fQ==', 1787839108);

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
  `shipping_address` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `transactions`
--

INSERT INTO `transactions` (`id`, `user_id`, `total_price`, `shipping_cost`, `payment_method`, `shipping_address`, `status`, `created_at`, `updated_at`) VALUES
(14, 11, '900000.00', '0.00', 'COD', 'user\n08888888888\njalan jalan, ddddddd 40123', 'pending', '2026-08-25 09:54:14', '2026-08-25 09:54:14'),
(15, 11, '75000.00', '25000.00', 'COD', 'user\n08888888888\njalan jalan, ddddddd 40123', 'pending', '2026-08-25 10:23:20', '2026-08-25 10:23:20'),
(16, 11, '1890000.00', '0.00', 'COD', 'user\n08888888888\njalan jalan, ddddddd 40123', 'pending', '2026-08-25 10:27:35', '2026-08-25 10:27:35'),
(17, 11, '1250000.00', '0.00', 'COD', 'user\n08888888888\njalan jalan, ddddddd 40123', 'pending', '2026-08-25 10:28:53', '2026-08-25 10:28:53'),
(18, 11, '1250000.00', '0.00', 'COD', 'user\n08888888888\njalan jalan, ddddddd 40123', 'pending', '2026-08-26 23:04:59', '2026-08-26 23:04:59'),
(19, 11, '75000.00', '25000.00', 'MIDTRANS', 'user\n08888888888\njalan jalan, ddddddd 40123\nkurir ganteng', 'pending', '2026-08-27 04:04:39', '2026-08-27 04:04:39'),
(20, 11, '75000.00', '25000.00', 'MIDTRANS', 'user\n08888888888\njalanan, ddddddd 40123', 'pending', '2026-08-27 04:05:56', '2026-08-27 04:05:56'),
(21, 11, '75000.00', '25000.00', 'MIDTRANS', 'user\n0895347660703\njalan jalan, jember 40123', 'pending', '2026-08-27 04:31:20', '2026-08-27 04:31:20'),
(22, 11, '75000.00', '25000.00', 'MIDTRANS', 'user\n0895347660703\njln jln, jember 40123', 'pending', '2026-08-27 04:42:11', '2026-08-27 04:42:11'),
(23, 11, '75000.00', '25000.00', 'COD', 'user\n0895347660703\njln jln, jember 40123', 'pending', '2026-08-27 04:42:32', '2026-08-27 04:42:32'),
(24, 11, '700000.00', '0.00', 'MIDTRANS', 'user\n0895347660703\njlnjljn, jember 40123', 'pending', '2026-08-27 04:47:05', '2026-08-27 04:47:05'),
(25, 11, '700000.00', '0.00', 'MIDTRANS', 'user\n0895347660703\njln jln, jember 40123', 'pending', '2026-08-27 04:52:10', '2026-08-27 04:52:10'),
(26, 11, '700000.00', '0.00', 'COD', 'user\n0895347660703\njln  jln, jember 40123', 'pending', '2026-08-27 04:57:25', '2026-08-27 04:57:25'),
(27, 11, '75000.00', '25000.00', 'MIDTRANS', 'user\n0895347660703\njln jln, jember 40123', 'pending', '2026-08-27 04:58:01', '2026-08-27 04:58:01');

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
(24, 14, 1, NULL, 3, '900000.00', '2026-08-25 09:54:14', '2026-08-25 09:54:14'),
(25, 15, 17, NULL, 1, '75000.00', '2026-08-25 10:23:20', '2026-08-25 10:23:20'),
(26, 16, 24, NULL, 1, '1890000.00', '2026-08-25 10:27:35', '2026-08-25 10:27:35'),
(27, 17, 25, NULL, 1, '1250000.00', '2026-08-25 10:28:53', '2026-08-25 10:28:53'),
(28, 18, 17, NULL, 2, '150000.00', '2026-08-26 23:04:59', '2026-08-26 23:04:59'),
(29, 18, 19, NULL, 1, '500000.00', '2026-08-26 23:04:59', '2026-08-26 23:04:59'),
(30, 18, 1, NULL, 2, '600000.00', '2026-08-26 23:04:59', '2026-08-26 23:04:59'),
(31, 19, 17, NULL, 1, '75000.00', '2026-08-27 04:04:39', '2026-08-27 04:04:39'),
(32, 20, 17, NULL, 1, '75000.00', '2026-08-27 04:05:56', '2026-08-27 04:05:56'),
(33, 21, 17, NULL, 1, '75000.00', '2026-08-27 04:31:20', '2026-08-27 04:31:20'),
(34, 22, 17, NULL, 1, '75000.00', '2026-08-27 04:42:11', '2026-08-27 04:42:11'),
(35, 23, 17, NULL, 1, '75000.00', '2026-08-27 04:42:32', '2026-08-27 04:42:32'),
(36, 24, 23, NULL, 1, '700000.00', '2026-08-27 04:47:05', '2026-08-27 04:47:05'),
(37, 25, 23, NULL, 1, '700000.00', '2026-08-27 04:52:10', '2026-08-27 04:52:10'),
(38, 26, 23, NULL, 1, '700000.00', '2026-08-27 04:57:25', '2026-08-27 04:57:25'),
(39, 27, 26, NULL, 1, '75000.00', '2026-08-27 04:58:01', '2026-08-27 04:58:01');

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
(9, 'bsbsshs', 'priatuaqw@gmail.com', 'priatuaqw@gmail.com', 'admin', 'active', NULL, '$2y$12$JzGQuQ.W0t5uAO3N0C34w.fi09zfVS6uHAMWuing.cEiNoY4SbIaq', NULL, '2026-08-11 23:31:28', '2026-08-12 08:08:55'),
(11, 'user', 'nama gue', 'usernameeee333@gmail.com', 'user', 'active', NULL, '$2y$12$KCqvw5t6Hmn1EF8HrH3Ca.fA5uGLySI6cXs6WYwtzUzZsvJZ2WR7.', 'CDVB8Nr3gl3FqqslLvNQk2D27YBUiuJr6X5bW3DbyRtuXiQnIQJpoD2xNP7v', '2026-08-25 07:26:08', '2026-08-25 09:55:26');

--
-- Indexes for dumped tables
--

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
  ADD KEY `product_variants_product_id_foreign` (`product_id`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `transactions`
--
ALTER TABLE `transactions`
  ADD PRIMARY KEY (`id`),
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
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT for table `product_variants`
--
ALTER TABLE `product_variants`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `transactions`
--
ALTER TABLE `transactions`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT for table `transaction_details`
--
ALTER TABLE `transaction_details`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `product_variants`
--
ALTER TABLE `product_variants`
  ADD CONSTRAINT `product_variants_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

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
