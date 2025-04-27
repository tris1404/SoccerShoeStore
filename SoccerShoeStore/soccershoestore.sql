-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Máy chủ: 127.0.0.1
-- Thời gian đã tạo: Th4 27, 2025 lúc 06:46 AM
-- Phiên bản máy phục vụ: 10.4.32-MariaDB
-- Phiên bản PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Cơ sở dữ liệu: `soccershoestore`
--

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `admins`
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
-- Đang đổ dữ liệu cho bảng `admins`
--

INSERT INTO `admins` (`id`, `name`, `email`, `email_verified_at`, `password`, `remember_token`, `phone`, `address`, `status`, `role`, `created_at`, `updated_at`) VALUES
(1, 'admin', 'admin@gmail.com', NULL, 'admin', NULL, '0937248684', 'Long An', 'Active', 'Admin', NULL, NULL),
(2, 'staff', 'staff@gmail.com', '2025-03-29 17:41:57', 'staff', '123', '04645545632', 'Long An', 'Active', 'Staff', NULL, NULL);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `brands`
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
-- Đang đổ dữ liệu cho bảng `brands`
--

INSERT INTO `brands` (`id`, `name`, `slug`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Nike', 'nike', 'Active', '2025-03-26 03:29:28', '2025-03-26 03:29:28'),
(2, 'Adidas', 'adidas', 'Active', '2025-03-26 03:29:28', '2025-03-26 03:29:28'),
(3, 'Puma', 'puma', 'Active', '2025-03-26 03:29:28', '2025-03-26 03:29:28');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `cart`
--

CREATE TABLE `cart` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `cart`
--

INSERT INTO `cart` (`id`, `user_id`, `created_at`, `updated_at`) VALUES
(1, 1, '2025-04-18 15:18:26', '2025-04-18 15:18:26'),
(2, 21, '2025-04-27 04:00:04', '2025-04-27 04:00:04');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `cart_items`
--

CREATE TABLE `cart_items` (
  `id` int(10) UNSIGNED NOT NULL,
  `cart_id` bigint(20) UNSIGNED NOT NULL,
  `product_id` int(11) NOT NULL,
  `qty` int(10) UNSIGNED NOT NULL DEFAULT 1,
  `price` double NOT NULL,
  `size` varchar(10) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `discount_price` double NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `categories`
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
-- Đang đổ dữ liệu cho bảng `categories`
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
(77, 'Zocker Inspire', 'zocker-inspire', 'Active', NULL, '2025-04-08 16:16:51', NULL);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `failed_jobs`
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
-- Cấu trúc bảng cho bảng `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `migrations`
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
-- Cấu trúc bảng cho bảng `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `order_code` varchar(50) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `full_name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `address` text NOT NULL,
  `delivery_method` enum('Giao hàng tận nhà','Nhận hàng tại cửa hàng') NOT NULL,
  `store_id` varchar(50) DEFAULT NULL,
  `payment_method` enum('Thanh toán khi nhận hàng','Chuyển khoản ngân hàng','Thẻ tín dụng/ ghi nợ') NOT NULL,
  `order_note` text DEFAULT NULL,
  `status` enum('Đang chờ','Đang xử lý','Đã vận chuyển','Đã giao','Đã hủy') DEFAULT 'Đang chờ',
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `order_items`
--

CREATE TABLE `order_items` (
  `id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `discount_price` decimal(10,2) NOT NULL,
  `size` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `password_resets`
--

CREATE TABLE `password_resets` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `personal_access_tokens`
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
-- Cấu trúc bảng cho bảng `products`
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
  `discount` int(11) DEFAULT 0,
  `status` tinyint(1) DEFAULT 1,
  `created_at` datetime DEFAULT current_timestamp(),
  `product_type` enum('normal','new','sale','hot') DEFAULT 'normal',
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `discount_price` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `products`
--

INSERT INTO `products` (`id`, `name`, `size`, `price`, `brand`, `shoe_type`, `quantity`, `image`, `discount`, `status`, `created_at`, `product_type`, `description`, `discount_price`) VALUES
(1, 'Nike Zoom Mercurial Superfly 9 Elite \"Marcus Rashford\"', '38,39,40,41,42', 8649000, 'Nike', 'Sân tự nhiên', 15, 'https://static.nike.com/a/images/t_default/8e644f9b-4db8-4d24-91d3-1edf45ae1e3c/ZOOM+SUPERFLY+9+ELITE+MR+FG.png', 10, 1, '2025-04-15 10:22:09', 'normal', 'Giày đá bóng Nike Zoom Mercurial Superfly 9 Elite là phiên bản cao cấp, được thiết kế đặc biệt cho Marcus Rashford với công nghệ Zoom Air mới giúp tăng tốc độ và phản xạ trên sân cỏ tự nhiên.', 7784100),
(2, 'F50 Elite Firm Ground Boots', '38,39,40,41,42', 5799999, 'Adidas', 'Sân tự nhiên', 13, 'https://thumblr.uniid.it/product/376821/4d93fadfee06.jpg?width=3840&format=webp&q=75', 11, 1, '2025-04-15 10:23:45', 'normal', 'F50 Elite Firm Ground Boots là mẫu giày bóng đá chuyên dụng cho sân cỏ tự nhiên, nổi bật với trọng lượng nhẹ, thiết kế khí động học và phần đế hỗ trợ tăng tốc tối đa cho các pha bứt tốc.', 5161999),
(3, 'PUMA FUTURE 7 PRO FG/AG', '38,39,40,41,42', 3600000, 'Puma', 'Sân tự nhiên', 6, 'https://images.puma.com/image/upload/f_auto,q_auto,b_rgb:fafafa,w_1000,h_1000/global/107707/01/sv01/fnd/THA/fmt/png/FUTURE-7-PRO-FG/AG-Football-Boots', 8, 1, '2025-04-15 10:30:10', 'normal', 'PUMA FUTURE 7 PRO FG/AG là mẫu giày linh hoạt dùng được cho cả sân cỏ tự nhiên và nhân tạo, thiết kế cổ thun ôm chân, hỗ trợ kiểm soát bóng và di chuyển linh hoạt trong mọi tình huống.', 3312000),
(4, 'Nike United Mercurial Superfly 10 Elite', '38,39,40,41,42', 8350000, 'Nike', 'Sân tự nhiên', 8, 'https://static.nike.com/a/images/t_default/d04f3004-bc36-4c38-b7ff-ed01479e39a9/ZM+SUPERFLY+10+ELITE+FG+NU1.png', 7, 1, '2025-04-15 10:34:42', 'sale', 'Nike United Mercurial Superfly 10 Elite thuộc dòng giày cao cấp của Nike, thiết kế dành cho các cầu thủ tấn công với phần upper siêu nhẹ và công nghệ Flyknit giúp tối ưu tốc độ và cảm giác bóng.', 7765500),
(5, 'Nike Superfly 10 Academy Mercurial Dream Speed', '38,39,40,41,42', 3060000, 'Nike', 'Sân tự nhiên', 15, 'https://static.nike.com/a/images/t_default/acf1c7f9-bebd-41fc-b81f-9af24b7e2ddb/ZM+SUPERFLY+10+ACAD+MDS+FG%2FMG.png', 4, 1, '2025-04-15 13:13:53', 'hot', 'Nike Superfly 10 Academy Mercurial Dream Speed là lựa chọn lý tưởng cho các cầu thủ trẻ yêu thích tốc độ, thiết kế theo cảm hứng từ những cầu thủ hàng đầu như Cristiano Ronaldo.', 2937600),
(6, 'NIKE ZOOM MERCURIAL SUPERFLY 9 KM ACADEMY TF - DO9347-400', '38,39,40,41,42', 3050000, 'Nike', 'Sân nhân tạo', 4, 'https://cdn.chiaki.vn/unsafe/0x480/left/top/smart/filters:quality(75)/https://chiaki.vn/upload/product/2024/03/giay-da-bong-nike-zoom-mercurial-superfly-9-academy-km-tf-do9347-400-65e6c7b166cef-05032024142017.png', 20, 1, '2025-04-15 13:17:15', 'hot', 'NIKE ZOOM MERCURIAL SUPERFLY 9 KM ACADEMY TF là phiên bản sân cỏ nhân tạo với thiết kế lấy cảm hứng từ Kylian Mbappé, mang lại cảm giác thoải mái, linh hoạt và kiểm soát bóng tối ưu.', 2440000),
(7, 'Adidas Predator League Fold-over Tongue TF Pure Victory - Rød/Hvid/Sort', '38,39,40,41,42', 3500000, 'Adidas', 'Futsal', 17, 'https://thumblr.uniid.it/product/377479/d120eedae5dd.jpg?width=3840&format=webp&q=75', 26, 1, '2025-04-15 13:21:23', 'hot', 'Adidas Predator League Fold-over Tongue TF mang phong cách cổ điển với lưỡi gà gập, phù hợp cho sân cỏ nhân tạo. Giày hỗ trợ kiểm soát bóng chính xác và tăng độ bám sân trong từng pha xử lý.', 2590000),
(8, 'PUMA ULTRA 5 PRO CAGE', '38,39,40,41,42', 2990000, 'Puma', 'Futsal', 9, 'https://product.hstatic.net/200000740801/product/untitled-1-01_4f268638fff14ab18cbfda5b37b0f026_master.jpg', 24, 1, '2025-04-15 13:25:34', 'hot', 'PUMA ULTRA 5 PRO CAGE là mẫu giày sân bóng mini (sân phủi) với thiết kế nhẹ, đế cao su chống trượt giúp di chuyển linh hoạt và chắc chắn trong các trận đấu tốc độ cao.', 2272400),
(9, 'PUMA x NEYMAR JR FUTURE 7 ULTIMATE FG/AG', '38,39,40,41,42', 2899999, 'Puma', 'Sân tự nhiên', 6, 'https://images.puma.com/image/upload/f_auto,q_auto,b_rgb:fafafa,w_1000,h_1000/global/107839/01/sv01/fnd/THA/fmt/png/PUMA-x-NEYMAR-JR-FUTURE-7-ULTIMATE-FG/AG-Football-Boots', 10, 1, '2025-04-15 13:30:30', 'new', 'PUMA x NEYMAR JR FUTURE 7 ULTIMATE FG/AG là phiên bản đặc biệt kết hợp giữa PUMA và Neymar Jr, tích hợp công nghệ tiên tiến hỗ trợ kiểm soát bóng và dứt điểm với độ chính xác cao.', 2609999),
(10, 'Adidas Predator Freak+ FG Meteorite Pack', '38,39,40,41,42', 2400000, 'Adidas', 'Sân tự nhiên', 3, 'https://venifutebol.com.br/cdn/shop/products/Chuteira-Predator-Freak-FG-Meteorite-Pack-1.jpg?v=1670451646', 30, 1, '2025-04-15 13:33:39', 'new', 'Adidas Predator Freak+ FG Meteorite Pack là đôi giày sân cỏ tự nhiên không dây, nổi bật với công nghệ Demonskin giúp tăng khả năng xoáy bóng và kiểm soát bóng hoàn hảo trong mọi điều kiện thi đấu.', 1680000),
(11, 'Adidas X Crazyfast.1 IC Messi Spark Gen10s ​​Pack Futsal Shoes', '38,39,40,41,42', 2299998, 'Adidas', 'Futsal', 19, 'https://venifutebol.com.br/cdn/shop/files/ChuteiraFutsalAdidasXCrazyfast1.MessiSparkGen10sPack1.png?v=1712946425', 10, 1, '2025-04-15 13:39:01', 'new', 'Adidas X Crazyfast.1 IC Messi Spark Gen10s Pack là mẫu giày futsal trong nhà dành riêng cho fan của Lionel Messi, mang thiết kế năng động, đế chống trượt và trọng lượng siêu nhẹ để tăng tốc tối đa.', 2069998),
(12, ' Puma Ultra 5 Carbon TF Ayrton Senna Pack Soccer Cleats', '38,39,40,41,42', 2500000, 'Puma', 'Sân nhân tạo', 8, 'https://venifutebol.com.br/cdn/shop/files/ChuteiraCampoeSocietyPumaUltra5CarbonMGVolumeUpPack1.png?v=1733938699', 25, 1, '2025-04-15 13:42:23', 'new', 'Puma Ultra 5 Carbon TF Ayrton Senna Pack là phiên bản giới hạn lấy cảm hứng từ tay đua huyền thoại Ayrton Senna, với đế TF chuyên dụng cho sân cỏ nhân tạo, nhẹ, bám sân tốt và thiết kế độc đáo, đậm chất tốc độ.', 1875000),
(14, 'Nike Jr. Mercurial Vapor 16 Academy \'Kylian Mbappé\'', '37,38,39,40,41,42', 2039000, 'Nike', 'Sân tự nhiên', 15, 'https://static.nike.com/a/images/c_limit,w_592,f_auto/t_product_v1/7c520669-a3c0-4fc0-8724-4d92b0339b47/JR+ZOOM+VAPOR+16+ACAD+KM+FG%2FMG.png', 5, 1, '2025-04-27 10:20:37', 'new', '', 1937050);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `products_admin`
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
  `discount` int(11) DEFAULT 0,
  `status` tinyint(1) DEFAULT 1,
  `product_type` enum('normal','new','sale','hot') DEFAULT 'normal',
  `discount_price` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `products_admin`
--

INSERT INTO `products_admin` (`id`, `name`, `size`, `price`, `category`, `shoe_type`, `quantity`, `image`, `discount`, `status`, `product_type`, `discount_price`) VALUES
(1, 'Nike Zoom Mercurial Superfly 9 Elite \"Marcus Rashford\"', '38,39,40,41,42', 8649000, 'Nike', 'Sân tự nhiên', 15, 'https://static.nike.com/a/images/t_default/8e644f9b-4db8-4d24-91d3-1edf45ae1e3c/ZOOM+SUPERFLY+9+ELITE+MR+FG.png', 10, 1, 'normal', 7784100.00),
(2, 'F50 Elite Firm Ground Boots', '38,39,40,41,42', 5799999, 'Adidas', 'Sân tự nhiên', 13, 'https://thumblr.uniid.it/product/376821/4d93fadfee06.jpg?width=3840&format=webp&q=75', 11, 1, 'normal', 5161999.11),
(3, 'PUMA FUTURE 7 PRO FG/AG', '38,39,40,41,42', 3600000, 'Puma', 'Sân tự nhiên', 6, 'https://images.puma.com/image/upload/f_auto,q_auto,b_rgb:fafafa,w_1000,h_1000/global/107707/01/sv01/fnd/THA/fmt/png/FUTURE-7-PRO-FG/AG-Football-Boots', 8, 1, 'normal', 3312000.00),
(4, 'Nike United Mercurial Superfly 10 Elite', '38,39,40,41,42', 8350000, 'Nike', 'Sân tự nhiên', 8, 'https://static.nike.com/a/images/t_default/d04f3004-bc36-4c38-b7ff-ed01479e39a9/ZM+SUPERFLY+10+ELITE+FG+NU1.png', 7, 1, 'sale', 7765500.00),
(5, 'Nike Superfly 10 Academy Mercurial Dream Speed', '38,39,40,41,42', 3060000, 'Nike', 'Sân tự nhiên', 15, 'https://static.nike.com/a/images/t_default/acf1c7f9-bebd-41fc-b81f-9af24b7e2ddb/ZM+SUPERFLY+10+ACAD+MDS+FG%2FMG.png', 4, 1, 'hot', 2937600.00),
(6, 'NIKE ZOOM MERCURIAL SUPERFLY 9 KM ACADEMY TF - DO9347-400', '38,39,40,41,42', 3050000, 'Nike', 'Sân nhân tạo', 4, 'https://cdn.chiaki.vn/unsafe/0x480/left/top/smart/filters:quality(75)/https://chiaki.vn/upload/product/2024/03/giay-da-bong-nike-zoom-mercurial-superfly-9-academy-km-tf-do9347-400-65e6c7b166cef-05032024142017.png', 20, 1, 'hot', 2440000.00),
(7, 'Adidas Predator League Fold-over Tongue TF Pure Victory - Rød/Hvid/Sort', '38,39,40,41,42', 3500000, 'Adidas', 'Futsal', 17, 'https://thumblr.uniid.it/product/377479/d120eedae5dd.jpg?width=3840&format=webp&q=75', 26, 1, 'hot', 2590000.00),
(8, 'PUMA ULTRA 5 PRO CAGE', '38,39,40,41,42', 2990000, 'Puma', 'Futsal', 9, 'https://product.hstatic.net/200000740801/product/untitled-1-01_4f268638fff14ab18cbfda5b37b0f026_master.jpg', 24, 1, 'hot', 2272400.00),
(9, 'PUMA x NEYMAR JR FUTURE 7 ULTIMATE FG/AG', '38,39,40,41,42', 2899999, 'Puma', 'Sân tự nhiên', 6, 'https://images.puma.com/image/upload/f_auto,q_auto,b_rgb:fafafa,w_1000,h_1000/global/107839/01/sv01/fnd/THA/fmt/png/PUMA-x-NEYMAR-JR-FUTURE-7-ULTIMATE-FG/AG-Football-Boots', 10, 1, 'new', 2609999.10),
(10, 'Adidas Predator Freak+ FG Meteorite Pack', '38,39,40,41,42', 2400000, 'Adidas', 'Sân tự nhiên', 3, 'https://venifutebol.com.br/cdn/shop/products/Chuteira-Predator-Freak-FG-Meteorite-Pack-1.jpg?v=1670451646', 30, 1, 'new', 1680000.00),
(11, 'Adidas X Crazyfast.1 IC Messi Spark Gen10s ​​Pack Futsal Shoes', '38,39,40,41,42', 2299998, 'Adidas', 'Futsal', 19, 'https://venifutebol.com.br/cdn/shop/files/ChuteiraFutsalAdidasXCrazyfast1.MessiSparkGen10sPack1.png?v=1712946425', 10, 1, 'new', 2069998.20),
(12, ' Puma Ultra 5 Carbon TF Ayrton Senna Pack Soccer Cleats', '38,39,40,41,42', 2500000, 'Puma', 'Sân nhân tạo', 8, 'https://venifutebol.com.br/cdn/shop/files/ChuteiraCampoeSocietyPumaUltra5CarbonMGVolumeUpPack1.png?v=1733938699', 25, 1, 'new', 1875000.00),
(14, 'Nike Jr. Mercurial Vapor 16 Academy \'Kylian Mbappé\'', '37,38,39,40,41,42', 2039000, 'Nike', 'Sân tự nhiên', 15, 'https://static.nike.com/a/images/c_limit,w_592,f_auto/t_product_v1/7c520669-a3c0-4fc0-8724-4d92b0339b47/JR+ZOOM+VAPOR+16+ACAD+KM+FG%2FMG.png', 5, 1, 'new', 1937050.00);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `reviews`
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
-- Cấu trúc bảng cho bảng `users`
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
-- Đang đổ dữ liệu cho bảng `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `remember_token`, `phone`, `address`, `status`, `created_at`, `updated_at`, `role`) VALUES
(1, 'user', 'user@gmail.com', NULL, 'user', NULL, '0123456789', 'Long An', 'Active', NULL, NULL, 'User'),
(2, 'user2', 'user2@gmail.com', NULL, 'User2@123', NULL, '', '', 'Active', NULL, NULL, 'User'),
(21, 'Nguyễn Tài Trí', 'tri@gmail.com', NULL, 'Tri@12345', NULL, '', '', 'Active', NULL, NULL, 'User');

--
-- Chỉ mục cho các bảng đã đổ
--

--
-- Chỉ mục cho bảng `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `admins_email_unique` (`email`);

--
-- Chỉ mục cho bảng `brands`
--
ALTER TABLE `brands`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `brands_slug_unique` (`slug`);

--
-- Chỉ mục cho bảng `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Chỉ mục cho bảng `cart_items`
--
ALTER TABLE `cart_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `cart_id` (`cart_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Chỉ mục cho bảng `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `categories_slug_unique` (`slug`),
  ADD KEY `fk_categories_parent` (`parent_id`);

--
-- Chỉ mục cho bảng `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Chỉ mục cho bảng `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `order_code` (`order_code`),
  ADD KEY `user_id` (`user_id`);

--
-- Chỉ mục cho bảng `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Chỉ mục cho bảng `password_resets`
--
ALTER TABLE `password_resets`
  ADD KEY `password_resets_email_index` (`email`);

--
-- Chỉ mục cho bảng `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`);

--
-- Chỉ mục cho bảng `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `products_admin`
--
ALTER TABLE `products_admin`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `reviews`
--
ALTER TABLE `reviews`
  ADD PRIMARY KEY (`id`),
  ADD KEY `reviews_user_id_foreign` (`user_id`),
  ADD KEY `reviews_product_id_foreign` (`product_id`);

--
-- Chỉ mục cho bảng `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- AUTO_INCREMENT cho các bảng đã đổ
--

--
-- AUTO_INCREMENT cho bảng `admins`
--
ALTER TABLE `admins`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT cho bảng `brands`
--
ALTER TABLE `brands`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT cho bảng `cart`
--
ALTER TABLE `cart`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT cho bảng `cart_items`
--
ALTER TABLE `cart_items`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=45;

--
-- AUTO_INCREMENT cho bảng `categories`
--
ALTER TABLE `categories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=78;

--
-- AUTO_INCREMENT cho bảng `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT cho bảng `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT cho bảng `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT cho bảng `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT cho bảng `products_admin`
--
ALTER TABLE `products_admin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT cho bảng `reviews`
--
ALTER TABLE `reviews`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- Các ràng buộc cho các bảng đã đổ
--

--
-- Các ràng buộc cho bảng `cart`
--
ALTER TABLE `cart`
  ADD CONSTRAINT `cart_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `cart_items`
--
ALTER TABLE `cart_items`
  ADD CONSTRAINT `cart_items_ibfk_1` FOREIGN KEY (`cart_id`) REFERENCES `cart` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `cart_items_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `categories`
--
ALTER TABLE `categories`
  ADD CONSTRAINT `fk_categories_parent` FOREIGN KEY (`parent_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Các ràng buộc cho bảng `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `order_items_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
