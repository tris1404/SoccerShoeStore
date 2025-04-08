-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Apr 08, 2025 at 11:10 AM
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
-- Database: `soccershoestore`
--

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `phone` varchar(30) NOT NULL,
  `address` text NOT NULL,
  `status` enum('Active','Inactive') NOT NULL DEFAULT 'Active',
  `role` enum('Admin','Staff') NOT NULL DEFAULT 'Staff',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`id`, `name`, `email`, `email_verified_at`, `password`, `remember_token`, `phone`, `address`, `status`, `role`, `created_at`, `updated_at`) VALUES
(1, 'admin', 'admin@gmail.com', NULL, 'admin', NULL, '0937248684', 'Long An', 'Active', 'Admin', NULL, NULL),
(2, 'staff', 'staff@gmail.com', '2025-03-29 17:41:57', 'staff', '123', '04645545632', 'Long An', 'Active', 'Staff', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `brands`
--

CREATE TABLE `brands` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `status` enum('Active','Inactive') NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `brands`
--

INSERT INTO `brands` (`id`, `name`, `slug`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Nike', 'nike', 'Active', '2025-03-26 03:29:28', '2025-03-26 03:29:28'),
(2, 'Adidas', 'adidas', 'Active', '2025-03-26 03:29:28', '2025-03-26 03:29:28'),
(3, 'Puma', 'puma', 'Active', '2025-03-26 03:29:28', '2025-03-26 03:29:28');

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `status` enum('Active','Inactive') NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `parent_id` bigint(20) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`, `slug`, `status`, `created_at`, `updated_at`, `parent_id`) VALUES
(1, 'Trang chủ', 'trang-chu', 'Active', NULL, NULL, NULL),
(2, 'Giày bóng đá', 'giay-bong-da', 'Active', NULL, NULL, NULL),
(3, 'Giày cỏ tự nhiên', 'giay-co-tu-nhien', 'Active', NULL, NULL, NULL),
(4, 'Giày cỏ nhân tạo', 'giay-co-nhan-tao', 'Active', NULL, NULL, NULL),
(5, 'Giày futsal', 'giay-futsal', 'Active', NULL, NULL, NULL),
(6, 'Thương hiệu', 'thuong-hieu', 'Active', NULL, NULL, NULL),
(7, 'Sale', 'sale', 'Active', NULL, NULL, NULL),
(8, 'Tất cả sản phẩm', 'tat-ca-san-pham', 'Active', NULL, NULL, 2),
(9, 'Hàng mới về', 'hang-moi-ve', 'Active', NULL, NULL, 2),
(10, 'Giày giá rẻ', 'giay-gia-re', 'Active', NULL, NULL, 2),
(11, 'Giày trẻ em', 'giay-tre-em', 'Active', NULL, NULL, 2),
(12, 'Giày cỏ tự nhiên Nike\r\n', 'nike-co-tu-nhien', 'Active', NULL, NULL, 3),
(13, 'Adidas', 'adidas-co-tu-nhien', 'Active', NULL, NULL, 3),
(14, 'Puma', 'puma-co-tu-nhien', 'Active', NULL, NULL, 3),
(15, 'Mizuno', 'mizuno-co-tu-nhien', 'Active', NULL, NULL, 3),
(16, 'Kamito', 'kamito-co-tu-nhien', 'Active', NULL, NULL, 3),
(17, 'Nike', 'nike-co-nhan-tao', 'Active', NULL, NULL, 4),
(18, 'Adidas', 'adidas-co-nhan-tao', 'Active', NULL, NULL, 4),
(19, 'Puma', 'puma-co-nhan-tao', 'Active', NULL, NULL, 4),
(20, 'Mizuno', 'mizuno-co-nhan-tao', 'Active', NULL, NULL, 4),
(21, 'Kamito', 'kamito-co-nhan-tao', 'Active', NULL, NULL, 4),
(22, 'Nike', 'nike-futsal', 'Active', NULL, NULL, 5),
(23, 'Adidas', 'adidas-futsal', 'Active', NULL, NULL, 5),
(24, 'Mizuno', 'mizuno-futsal', 'Active', NULL, NULL, 5),
(25, 'Kamito', 'kamito-futsal', 'Active', NULL, NULL, 5),
(36, 'Nike', 'nike', 'Active', NULL, NULL, 6),
(37, 'Adidas', 'adidas', 'Active', NULL, NULL, 6),
(38, 'Puma', 'puma', 'Active', NULL, NULL, 6),
(39, 'Mizuno', 'mizuno', 'Active', NULL, NULL, 6),
(40, 'Kamito', 'kamito', 'Active', NULL, NULL, 6),
(41, 'Joma', 'joma', 'Active', NULL, NULL, 6),
(42, 'Asics', 'asics', 'Active', NULL, NULL, 6),
(43, 'Desporte', 'desporte', 'Active', NULL, NULL, 6),
(44, 'Zocker', 'zocker', 'Active', NULL, NULL, 6),
(45, 'Nike Mercurial', 'nike-mercurial', 'Active', NULL, NULL, 36),
(46, 'Nike Tiempo', 'nike-tiempo', 'Active', NULL, NULL, 36),
(47, 'Nike Phantom', 'nike-phantom', 'Active', NULL, NULL, 36),
(48, 'Nike React Gato', 'nike-react-gato', 'Active', NULL, NULL, 36),
(49, 'Nike Lunar', 'nike-lunar', 'Active', NULL, NULL, 36),
(50, 'Nike Street Gato', 'nike-street-gato', 'Active', NULL, NULL, 36),
(51, 'Adidas F50', 'adidas-f50', 'Active', NULL, NULL, 37),
(52, 'Adidas Predator', 'adidas-predator', 'Active', NULL, NULL, 37),
(53, 'Adidas Copa', 'adidas-copa', 'Active', NULL, NULL, 37),
(54, 'Adidas X', 'adidas-x', 'Active', NULL, NULL, 37),
(55, 'Adidas Top Sala', 'adidas-top-sala', 'Active', NULL, NULL, 37),
(56, 'Puma Future', 'puma-future', 'Active', NULL, NULL, 38),
(57, 'Puma Ultra', 'puma-ultra', 'Active', NULL, NULL, 38),
(58, 'Puma King', 'puma-king', 'Active', NULL, NULL, 38),
(59, 'Mizuno Monarcida', 'mizuno-monarcida', 'Active', NULL, NULL, 39),
(60, 'Mizuno Alpha', 'mizuno-alpha', 'Active', NULL, NULL, 39),
(61, 'Mizuno Morelia', 'mizuno-morelia', 'Active', NULL, NULL, 39),
(62, 'Kamito TA11', 'kamito-ta11', 'Active', NULL, NULL, 40),
(63, 'Kamito Velocidad', 'kamito-velocidad', 'Active', NULL, NULL, 40),
(64, 'Joma Top Flex', 'joma-top-flex', 'Active', NULL, NULL, 41),
(65, 'Joma Regate', 'joma-regate', 'Active', NULL, NULL, 41),
(66, 'Joma Cancha', 'joma-cancha', 'Active', NULL, NULL, 41),
(67, 'Joma Mundial', 'joma-mundial', 'Active', NULL, NULL, 41),
(68, 'Asics Destaque', 'asics-destaque', 'Active', NULL, NULL, 42),
(69, 'Asics Caletto', 'asics-caletto', 'Active', NULL, NULL, 42),
(70, 'Asics DS Light', 'asics-ds-light', 'Active', NULL, NULL, 42),
(71, 'Desporte Campinas', 'desporte-campinas', 'Active', NULL, NULL, 43),
(72, 'Desporte Tessa Light', 'desporte-tessa-light', 'Active', NULL, NULL, 43),
(73, 'Desporte Boa Vistra', 'desporte-boa-vistra', 'Active', NULL, NULL, 43),
(74, 'Desporte Sao Luis', 'desporte-sao-luis', 'Active', NULL, NULL, 43),
(75, 'Zocker Winner Energy', 'zocker-winner-energy', 'Active', NULL, NULL, 44),
(76, 'Zocker Inspire Pro', 'zocker-inspire-pro', 'Active', NULL, NULL, 44),
(77, 'Zocker Inspire', 'zocker-inspire', 'Active', NULL, NULL, 44);

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
(1, '2014_10_12_000000_create_users_table', 1),
(2, '2014_10_12_100000_create_password_resets_table', 1),
(3, '2019_08_19_000000_create_failed_jobs_table', 1),
(4, '2019_12_14_000001_create_personal_access_tokens_table', 1),
(5, '2023_10_03_123426_create_admins_table', 1),
(6, '2023_10_03_130747_create_categories_table', 2),
(7, '2023_10_03_130946_create_brands_table', 2),
(8, '2023_10_03_132635_create_products_table', 3),
(9, '2023_10_03_135606_create_reviews_table', 4),
(10, '2023_10_04_080710_create_orders_table', 5),
(11, '2023_10_04_081411_create_order_details_table', 6);

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `status` enum('Processing','Confirmed','Shipping','Delivered','Cancelled') NOT NULL DEFAULT 'Processing',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `user_id`, `status`, `created_at`, `updated_at`) VALUES
(1, 1, 'Processing', '2025-04-06 13:56:15', '2025-04-06 13:56:15');

-- --------------------------------------------------------

--
-- Table structure for table `order_details`
--

CREATE TABLE `order_details` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `order_id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `price` double NOT NULL,
  `qty` tinyint(4) NOT NULL,
  `total` double NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `password_resets`
--

CREATE TABLE `password_resets` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `size` varchar(50) NOT NULL,
  `price` int(11) NOT NULL,
  `brand` varchar(50) NOT NULL,
  `shoe_type` varchar(50) NOT NULL,
  `quantity` int(11) NOT NULL,
  `image` varchar(255) NOT NULL,
  `discount` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `name`, `size`, `price`, `brand`, `shoe_type`, `quantity`, `image`, `discount`) VALUES
(1, 'adidas1', '29, 40', 2000000, 'Adidas', 'Sân tự nhiên', 7, 'adidas_messi.webp', 0),
(2, 'nike1', '40', 4000000, 'Nike', 'Sân tự nhiên', 5, 'nike_2.png', 12),
(3, 'puma1', '36,37', 1700000, 'Puma', 'Sân tự nhiên', 8, 'puma_1.jpg', 8);

-- --------------------------------------------------------

--
-- Table structure for table `products_admin`
--

CREATE TABLE `products_admin` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `size` varchar(50) NOT NULL,
  `price` int(11) NOT NULL,
  `category` varchar(50) NOT NULL,
  `shoe_type` varchar(50) NOT NULL,
  `quantity` int(11) NOT NULL,
  `image` varchar(255) NOT NULL,
  `discount` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products_admin`
--

INSERT INTO `products_admin` (`id`, `name`, `size`, `price`, `category`, `shoe_type`, `quantity`, `image`, `discount`) VALUES
(1, 'adidas1', '29, 40', 2000000, 'Adidas', 'Sân tự nhiên', 7, 'adidas_messi.webp', 0),
(2, 'nike1', '40', 4000000, 'Nike', 'Sân tự nhiên', 5, 'nike_2.png', 12),
(3, 'puma1', '36,37', 1700000, 'Puma', 'Sân tự nhiên', 8, 'puma_1.jpg', 8);

-- --------------------------------------------------------

--
-- Table structure for table `reviews`
--

CREATE TABLE `reviews` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `comment` text NOT NULL,
  `rating` tinyint(4) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `phone` varchar(30) NOT NULL,
  `address` text NOT NULL,
  `status` enum('Active','Inactive') NOT NULL DEFAULT 'Active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `role` enum('User') NOT NULL DEFAULT 'User'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `remember_token`, `phone`, `address`, `status`, `created_at`, `updated_at`, `role`) VALUES
(1, 'user', 'user@gmail.com', NULL, 'user', NULL, '0123456789', 'Long An', 'Active', NULL, NULL, 'User'),
(18, 'user2', 'user2@gmail.com', NULL, 'User2@123', NULL, '', '', 'Active', NULL, NULL, 'User');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `admins_email_unique` (`email`);

--
-- Indexes for table `brands`
--
ALTER TABLE `brands`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `brands_slug_unique` (`slug`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `categories_slug_unique` (`slug`),
  ADD KEY `fk_categories_parent` (`parent_id`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `orders_user_id_foreign` (`user_id`);

--
-- Indexes for table `order_details`
--
ALTER TABLE `order_details`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_details_order_id_foreign` (`order_id`),
  ADD KEY `order_details_product_id_foreign` (`product_id`);

--
-- Indexes for table `password_resets`
--
ALTER TABLE `password_resets`
  ADD KEY `password_resets_email_index` (`email`);

--
-- Indexes for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `products_admin`
--
ALTER TABLE `products_admin`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `reviews`
--
ALTER TABLE `reviews`
  ADD PRIMARY KEY (`id`),
  ADD KEY `reviews_user_id_foreign` (`user_id`),
  ADD KEY `reviews_product_id_foreign` (`product_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `brands`
--
ALTER TABLE `brands`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=78;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `order_details`
--
ALTER TABLE `order_details`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `products_admin`
--
ALTER TABLE `products_admin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `reviews`
--
ALTER TABLE `reviews`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `categories`
--
ALTER TABLE `categories`
  ADD CONSTRAINT `fk_categories_parent` FOREIGN KEY (`parent_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `order_details`
--
ALTER TABLE `order_details`
  ADD CONSTRAINT `order_details_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
