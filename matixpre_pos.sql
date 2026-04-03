-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Mar 24, 2026 at 11:42 AM
-- Server version: 5.7.44-log-cll-lve
-- PHP Version: 8.1.34

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `matixpre_pos`
--

-- --------------------------------------------------------

--
-- Table structure for table `activity_logs`
--

CREATE TABLE `activity_logs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `action` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `subject_type` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `subject_id` bigint(20) UNSIGNED DEFAULT NULL,
  `old_values` json DEFAULT NULL,
  `new_values` json DEFAULT NULL,
  `description` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `activity_logs`
--

INSERT INTO `activity_logs` (`id`, `user_id`, `action`, `subject_type`, `subject_id`, `old_values`, `new_values`, `description`, `created_at`, `updated_at`) VALUES
(1, 2, 'updated', 'App\\Models\\Product', 120, '{\"sku\": \"STN92553\", \"name\": \"Hauser Darkies Extra Dark Pencil\", \"unit\": \"piece\", \"image\": null, \"barcode\": null, \"company\": null, \"quantity\": 0, \"is_active\": true, \"cost_price\": \"7.00\", \"category_id\": 1, \"description\": null, \"selling_price\": \"12.00\", \"alert_quantity\": 3}', '{\"sku\": \"STN92553\", \"name\": \"Hauser Darkies Extra Dark Pencil\", \"unit\": \"piece\", \"image\": null, \"barcode\": null, \"company\": null, \"quantity\": 0, \"is_active\": true, \"cost_price\": \"7.00\", \"category_id\": 1, \"description\": \"This is a test description\", \"selling_price\": \"12.00\", \"alert_quantity\": 3}', 'Product updated', '2026-03-17 04:06:13', '2026-03-17 04:06:13'),
(2, 1, 'created', 'App\\Models\\Product', 121, NULL, '{\"sku\": \"STN76229\", \"name\": \"Wastage Bin Multipurpose - Small\", \"unit\": \"piece\", \"image\": null, \"barcode\": null, \"company\": \"RFL\", \"quantity\": 6, \"is_active\": true, \"cost_price\": \"60.00\", \"category_id\": \"5\", \"description\": null, \"selling_price\": \"90.00\", \"alert_quantity\": 2}', 'Product created', '2026-03-17 16:26:32', '2026-03-17 16:26:32'),
(3, 1, 'created', 'App\\Models\\Product', 122, NULL, '{\"sku\": \"STN62943\", \"name\": \"Rabbit Sharpener\", \"unit\": \"piece\", \"image\": null, \"barcode\": null, \"company\": \"Good Luck\", \"quantity\": 18, \"is_active\": true, \"cost_price\": \"10.00\", \"category_id\": \"1\", \"description\": null, \"selling_price\": \"15.00\", \"alert_quantity\": 5}', 'Product created', '2026-03-17 16:32:04', '2026-03-17 16:32:04'),
(4, 1, 'updated', 'App\\Models\\Product', 78, '{\"sku\": \"STN38537\", \"name\": \"Doms Cutter\", \"unit\": \"piece\", \"image\": null, \"barcode\": null, \"company\": null, \"quantity\": 20, \"is_active\": true, \"cost_price\": \"3.25\", \"category_id\": 1, \"description\": null, \"selling_price\": \"5.00\", \"alert_quantity\": 5}', '{\"sku\": \"STN38537\", \"name\": \"Doms Cutter - Sharpener\", \"unit\": \"piece\", \"image\": null, \"barcode\": null, \"company\": null, \"quantity\": 20, \"is_active\": true, \"cost_price\": \"3.25\", \"category_id\": 1, \"description\": null, \"selling_price\": \"5.00\", \"alert_quantity\": 5}', 'Product updated', '2026-03-17 16:32:35', '2026-03-17 16:32:35'),
(5, 1, 'created', 'App\\Models\\Product', 123, NULL, '{\"sku\": \"STN77283\", \"name\": \"Stylo Sharpener\", \"unit\": \"piece\", \"image\": null, \"barcode\": null, \"company\": \"Good Luck\", \"quantity\": 28, \"is_active\": true, \"cost_price\": \"6.00\", \"category_id\": \"1\", \"description\": null, \"selling_price\": \"10.00\", \"alert_quantity\": 5}', 'Product created', '2026-03-17 16:33:57', '2026-03-17 16:33:57'),
(6, 1, 'created', 'App\\Models\\Product', 124, NULL, '{\"sku\": \"STN67052\", \"name\": \"Color Pencil - Big\", \"unit\": \"piece\", \"image\": null, \"barcode\": null, \"company\": \"Good Luck\", \"quantity\": 3, \"is_active\": true, \"cost_price\": \"90.00\", \"category_id\": \"1\", \"description\": null, \"selling_price\": \"140.00\", \"alert_quantity\": 1}', 'Product created', '2026-03-17 16:36:20', '2026-03-17 16:36:20'),
(7, 1, 'created', 'App\\Models\\Product', 125, NULL, '{\"sku\": \"STN49042\", \"name\": \"Color Pencil - Small\", \"unit\": \"piece\", \"image\": null, \"barcode\": null, \"company\": \"Good Luck\", \"quantity\": 3, \"is_active\": true, \"cost_price\": \"65.00\", \"category_id\": \"1\", \"description\": null, \"selling_price\": \"105.00\", \"alert_quantity\": 1}', 'Product created', '2026-03-17 16:37:12', '2026-03-17 16:37:12'),
(8, 1, 'updated', 'App\\Models\\Product', 124, '{\"sku\": \"STN67052\", \"name\": \"Color Pencil - Big\", \"unit\": \"piece\", \"image\": null, \"barcode\": null, \"company\": \"Good Luck\", \"quantity\": 3, \"is_active\": true, \"cost_price\": \"90.00\", \"category_id\": 1, \"description\": null, \"selling_price\": \"140.00\", \"alert_quantity\": 1}', '{\"sku\": \"STN67052\", \"name\": \"Color Pencil - Big\", \"unit\": \"piece\", \"image\": null, \"barcode\": null, \"company\": \"Good Luck\", \"quantity\": 3, \"is_active\": true, \"cost_price\": \"90.00\", \"category_id\": 1, \"description\": null, \"selling_price\": \"130.00\", \"alert_quantity\": 1}', 'Product updated', '2026-03-17 16:37:28', '2026-03-17 16:37:28'),
(9, 1, 'created', 'App\\Models\\Product', 126, NULL, '{\"sku\": \"STN23165\", \"name\": \"Toothbrush - Big\", \"unit\": \"piece\", \"image\": null, \"barcode\": null, \"company\": \"RFL\", \"quantity\": 24, \"is_active\": true, \"cost_price\": \"19.00\", \"category_id\": \"8\", \"description\": null, \"selling_price\": \"40.00\", \"alert_quantity\": 5}', 'Product created', '2026-03-17 16:40:15', '2026-03-17 16:40:15'),
(10, 1, 'updated', 'App\\Models\\Product', 126, '{\"sku\": \"STN23165\", \"name\": \"Toothbrush - Big\", \"unit\": \"piece\", \"image\": null, \"barcode\": null, \"company\": \"RFL\", \"quantity\": 24, \"is_active\": true, \"cost_price\": \"19.00\", \"category_id\": 8, \"description\": null, \"selling_price\": \"40.00\", \"alert_quantity\": 5}', '{\"sku\": \"STN23165\", \"name\": \"Toothbrush - Adult\", \"unit\": \"piece\", \"image\": null, \"barcode\": null, \"company\": \"RFL\", \"quantity\": 24, \"is_active\": true, \"cost_price\": \"19.00\", \"category_id\": 8, \"description\": null, \"selling_price\": \"40.00\", \"alert_quantity\": 5}', 'Product updated', '2026-03-17 16:40:32', '2026-03-17 16:40:32'),
(11, 1, 'created', 'App\\Models\\Product', 127, NULL, '{\"sku\": \"STN57201\", \"name\": \"Toothbrush - Kids\", \"unit\": \"piece\", \"image\": null, \"barcode\": null, \"company\": \"RFL\", \"quantity\": 12, \"is_active\": true, \"cost_price\": \"20.00\", \"category_id\": \"8\", \"description\": null, \"selling_price\": \"40.00\", \"alert_quantity\": 4}', 'Product created', '2026-03-17 16:40:58', '2026-03-17 16:40:58'),
(12, 1, 'updated', 'App\\Models\\Product', 30, '{\"sku\": \"STN41526\", \"name\": \"Matador All-time\", \"unit\": \"piece\", \"image\": \"uploads/products/product_69b6b4b1e476f1.32185070.jpg\", \"barcode\": null, \"company\": null, \"quantity\": 45, \"is_active\": true, \"cost_price\": \"5.00\", \"category_id\": 1, \"description\": null, \"selling_price\": \"6.00\", \"alert_quantity\": 10}', '{\"sku\": \"STN41526\", \"name\": \"Matador All-time\", \"unit\": \"piece\", \"image\": \"uploads/products/product_69bb9c36559eb1.50563325.jpg\", \"barcode\": null, \"company\": null, \"quantity\": 45, \"is_active\": true, \"cost_price\": \"5.00\", \"category_id\": 1, \"description\": null, \"selling_price\": \"6.00\", \"alert_quantity\": 10}', 'Product updated', '2026-03-19 16:48:22', '2026-03-19 16:48:22'),
(13, 1, 'updated', 'App\\Models\\Product', 34, '{\"sku\": \"STN01565\", \"name\": \"Matador I-teen Rio Black\", \"unit\": \"piece\", \"image\": null, \"barcode\": null, \"company\": null, \"quantity\": 26, \"is_active\": true, \"cost_price\": \"8.67\", \"category_id\": 1, \"description\": null, \"selling_price\": \"10.00\", \"alert_quantity\": 10}', '{\"sku\": \"STN01565\", \"name\": \"Matador I-teen Rio Black\", \"unit\": \"piece\", \"image\": \"uploads/products/product_69bbb97a976841.60293417.jpg\", \"barcode\": null, \"company\": null, \"quantity\": 26, \"is_active\": true, \"cost_price\": \"8.67\", \"category_id\": 1, \"description\": null, \"selling_price\": \"10.00\", \"alert_quantity\": 10}', 'Product updated', '2026-03-19 18:53:14', '2026-03-19 18:53:14'),
(14, 1, 'created', 'App\\Models\\Product', 128, NULL, '{\"sku\": \"STN13116\", \"name\": \"Computer Compose\", \"unit\": \"piece\", \"image\": null, \"barcode\": null, \"company\": null, \"quantity\": 10000, \"is_active\": true, \"cost_price\": \"3.00\", \"category_id\": \"7\", \"description\": null, \"selling_price\": \"25.00\", \"alert_quantity\": 20}', 'Product created', '2026-03-20 19:54:56', '2026-03-20 19:54:56');

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`, `description`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'Pens & Pencils', 'Writing instruments including ballpoint pens, gel pens, and pencils', 1, '2026-03-14 09:33:19', '2026-03-14 09:33:19'),
(2, 'Notebooks & Papers', 'Notebooks, registers, loose sheets, and paper products', 1, '2026-03-14 09:33:19', '2026-03-14 09:33:19'),
(3, 'Files & Folders', 'File folders, binders, and document organizers', 1, '2026-03-14 09:33:19', '2026-03-14 09:33:19'),
(4, 'Art Supplies', 'Colors, brushes, sketch books, and craft materials', 1, '2026-03-14 09:33:19', '2026-03-14 09:33:19'),
(5, 'Office Supplies', 'Staplers, scissors, tape, and general office items', 1, '2026-03-14 09:33:19', '2026-03-14 09:33:19'),
(6, 'School Bags', 'Backpacks, sling bags, and pouches', 1, '2026-03-14 09:33:19', '2026-03-14 09:33:19'),
(7, 'Printing Service', 'BW Print, Color Print, Photocopy', 1, '2026-03-14 09:51:32', '2026-03-14 09:51:32'),
(8, 'Others', 'All others', 1, '2026-03-16 04:06:49', '2026-03-16 04:07:01');

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2019_12_14_000001_create_personal_access_tokens_table', 1),
(2, '2024_01_01_000001_create_users_table', 1),
(3, '2024_01_01_000002_create_categories_table', 1),
(4, '2024_01_01_000003_create_products_table', 1),
(5, '2024_01_01_000004_create_sales_table', 1),
(6, '2024_01_01_000005_create_sale_items_table', 1),
(7, '2024_01_01_000006_create_settings_table', 2),
(8, '2024_01_01_000007_make_payment_methods_configurable', 3),
(9, '2024_01_01_000008_add_timezone_setting', 3),
(10, '2026_03_14_000009_add_company_to_products_table', 3),
(11, '2026_03_17_000001_create_activity_logs_table', 4);

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tokenable_type` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text COLLATE utf8mb4_unicode_ci,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `category_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `company` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sku` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `barcode` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `cost_price` decimal(10,2) NOT NULL,
  `selling_price` decimal(10,2) NOT NULL,
  `quantity` int(11) NOT NULL DEFAULT '0',
  `alert_quantity` int(11) NOT NULL DEFAULT '10',
  `unit` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'piece',
  `image` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `category_id`, `name`, `company`, `sku`, `barcode`, `description`, `cost_price`, `selling_price`, `quantity`, `alert_quantity`, `unit`, `image`, `is_active`, `created_at`, `updated_at`) VALUES
(45, 6, 'Matador All-time Scale (30cm)', NULL, 'STN20676', NULL, NULL, 11.25, 15.00, 4, 3, 'piece', NULL, 1, '2026-03-15 08:08:35', '2026-03-15 08:58:34'),
(44, 6, 'Matador Double Decker Pencil Box', NULL, 'STN77564', NULL, NULL, 55.00, 75.00, 12, 3, 'piece', NULL, 1, '2026-03-15 08:07:15', '2026-03-15 08:07:15'),
(43, 6, 'Matador Pencil Box (small)', NULL, 'STN21689', NULL, NULL, 30.00, 40.00, 10, 3, 'piece', NULL, 1, '2026-03-15 08:06:51', '2026-03-16 09:10:02'),
(42, 1, 'Matador Woodmark Eraser', NULL, 'STN26112', NULL, NULL, 8.13, 10.00, 24, 10, 'piece', NULL, 1, '2026-03-15 08:06:10', '2026-03-15 08:06:10'),
(41, 1, 'Matador i-teen Eraser', NULL, 'STN51683', NULL, NULL, 3.83, 5.00, 30, 10, 'piece', NULL, 1, '2026-03-15 08:05:49', '2026-03-15 08:05:49'),
(40, 1, 'Matador Pluto Pencil 2B', NULL, 'STN78027', NULL, NULL, 4.58, 10.00, 11, 10, 'piece', NULL, 1, '2026-03-15 08:03:45', '2026-03-24 23:48:50'),
(39, 1, 'Matador I-teen Gel Green', NULL, 'STN36415', NULL, NULL, 10.00, 15.00, 24, 10, 'piece', NULL, 1, '2026-03-15 07:50:26', '2026-03-15 07:50:26'),
(35, 1, 'Matador Pencilic Black', NULL, 'STN36917', NULL, NULL, 4.00, 5.00, 20, 10, 'piece', NULL, 1, '2026-03-15 07:45:53', '2026-03-15 07:45:53'),
(34, 1, 'Matador I-teen Rio Black', NULL, 'STN01565', NULL, NULL, 8.67, 10.00, 26, 10, 'piece', 'uploads/products/product_69bbb97a976841.60293417.jpg', 1, '2026-03-15 07:44:07', '2026-03-19 18:53:14'),
(38, 1, 'Matador I-teen Gel Blue', NULL, 'STN10926', NULL, NULL, 10.00, 15.00, 24, 10, 'piece', NULL, 1, '2026-03-15 07:50:03', '2026-03-15 07:50:03'),
(33, 1, 'Matador Orbit', NULL, 'STN55686', NULL, NULL, 4.50, 5.00, 26, 10, 'piece', NULL, 1, '2026-03-15 07:43:34', '2026-03-15 09:10:44'),
(32, 1, 'Matador Pin-Point Black', NULL, 'STN68406', NULL, NULL, 4.50, 5.00, 54, 10, 'piece', NULL, 1, '2026-03-15 07:33:26', '2026-03-25 01:30:10'),
(31, 1, 'Matador Hi-School Black', NULL, 'STN42326', NULL, NULL, 4.50, 5.00, 72, 10, 'piece', NULL, 1, '2026-03-15 07:33:01', '2026-03-22 16:21:17'),
(30, 1, 'Matador All-time', NULL, 'STN41526', NULL, NULL, 5.00, 6.00, 45, 10, 'piece', 'uploads/products/product_69bb9c36559eb1.50563325.jpg', 1, '2026-03-15 07:27:50', '2026-03-19 16:48:22'),
(37, 1, 'Matador I-teen Gel Red', NULL, 'STN91111', NULL, NULL, 10.00, 15.00, 24, 10, 'piece', NULL, 1, '2026-03-15 07:48:20', '2026-03-15 07:49:36'),
(36, 1, 'Matador I-teen Gel Black', NULL, 'STN76635', NULL, NULL, 10.00, 15.00, 24, 10, 'piece', NULL, 1, '2026-03-15 07:48:01', '2026-03-15 07:48:01'),
(29, 7, 'Photocopy', NULL, 'STN75415', NULL, 'Print from computer using Toshiba eStudio 2523AD photocopier', 1.00, 3.00, 9934, 10, 'piece', NULL, 1, '2026-03-15 07:19:19', '2026-03-25 01:29:26'),
(28, 7, 'Color Print', NULL, 'STN31434', NULL, 'Color Print from computer using Epson L130', 1.00, 20.00, 9996, 10, 'piece', NULL, 1, '2026-03-15 07:18:13', '2026-03-22 14:53:22'),
(27, 7, 'BW Print', NULL, 'STN50296', NULL, 'Print from computer using Toshiba eStudio 2523AD photocopier', 1.00, 10.00, 9985, 10, 'piece', NULL, 1, '2026-03-14 09:53:10', '2026-03-25 01:31:07'),
(46, 6, 'Matador All-time Scale (15cm)', NULL, 'STN08954', NULL, NULL, 7.92, 10.00, 12, 3, 'piece', NULL, 1, '2026-03-15 08:08:58', '2026-03-15 08:08:58'),
(47, 1, 'Matador smoothy Pencil 2B', NULL, 'STN47338', NULL, NULL, 5.83, 10.00, 24, 6, 'piece', NULL, 1, '2026-03-15 08:09:32', '2026-03-15 08:09:32'),
(48, 5, 'Matador Officemate Stapler', NULL, 'STN64518', NULL, NULL, 88.00, 120.00, 6, 2, 'piece', NULL, 1, '2026-03-15 08:10:03', '2026-03-15 08:10:03'),
(49, 5, 'Matador Officemate Correction Pen', NULL, 'STN35328', NULL, NULL, 30.00, 40.00, 6, 2, 'piece', NULL, 1, '2026-03-15 08:10:43', '2026-03-15 08:10:43'),
(50, 6, 'Matador Clip Bloard', NULL, 'STN57357', NULL, NULL, 75.00, 100.00, 6, 2, 'piece', NULL, 1, '2026-03-15 08:11:11', '2026-03-15 08:11:11'),
(51, 6, 'Matador Paper Clip Bloard', NULL, 'STN77237', NULL, NULL, 50.00, 65.00, 6, 2, 'piece', NULL, 1, '2026-03-15 08:11:32', '2026-03-15 08:11:32'),
(52, 3, 'Matador Clear Bag (A4)', NULL, 'STN85257', NULL, NULL, 13.00, 20.00, 12, 3, 'piece', NULL, 1, '2026-03-15 08:12:11', '2026-03-15 08:12:11'),
(53, 3, 'Matador Clear Bag (FC)', NULL, 'STN46203', NULL, NULL, 16.00, 25.00, 12, 3, 'piece', NULL, 1, '2026-03-15 08:12:30', '2026-03-15 08:12:30'),
(54, 5, 'Aica Gum', NULL, 'STN31808', NULL, NULL, 15.00, 20.00, 10, 3, 'piece', NULL, 1, '2026-03-15 08:13:11', '2026-03-15 08:13:11'),
(55, 5, 'Transparent Tape', NULL, 'STN03634', NULL, NULL, 6.67, 10.00, 12, 3, 'piece', NULL, 1, '2026-03-15 08:13:56', '2026-03-15 08:13:56'),
(56, 5, 'Wiring Tape', NULL, 'STN42217', NULL, NULL, 11.43, 20.00, 28, 3, 'piece', NULL, 1, '2026-03-15 08:14:25', '2026-03-15 08:14:25'),
(57, 5, 'Cartoon Tape', NULL, 'STN58696', NULL, NULL, 30.00, 40.00, 6, 3, 'piece', NULL, 1, '2026-03-15 08:14:49', '2026-03-15 08:14:49'),
(58, 5, 'Super Glue', NULL, 'STN79492', NULL, NULL, 5.83, 10.00, 12, 3, 'piece', NULL, 1, '2026-03-15 08:15:09', '2026-03-15 08:15:09'),
(59, 2, 'Math Khata (124p)', NULL, 'STN47576', NULL, NULL, 38.33, 45.00, 12, 3, 'piece', NULL, 1, '2026-03-15 08:15:43', '2026-03-15 08:15:43'),
(60, 2, 'Math Khata (84p)', NULL, 'STN10303', NULL, NULL, 27.08, 35.00, 10, 3, 'piece', NULL, 1, '2026-03-15 08:16:02', '2026-03-24 23:48:50'),
(61, 2, 'A4 Creative 70gsm', NULL, 'STN40457', NULL, NULL, 0.65, 1.00, 2500, 500, 'piece', NULL, 1, '2026-03-15 08:17:24', '2026-03-15 08:19:44'),
(62, 2, 'A4 Chandan 80gsm', NULL, 'STN49255', NULL, NULL, 0.75, 2.00, 3000, 500, 'piece', NULL, 1, '2026-03-15 08:18:17', '2026-03-15 08:18:46'),
(63, 5, 'kangaro Stapler Pin', NULL, 'STN60017', NULL, NULL, 21.00, 25.00, 14, 5, 'piece', NULL, 1, '2026-03-15 08:20:19', '2026-03-15 08:20:19'),
(64, 5, 'Sunlite AA Battery', NULL, 'STN87928', NULL, NULL, 17.50, 25.00, 10, 4, 'piece', NULL, 1, '2026-03-15 08:20:44', '2026-03-15 08:20:44'),
(65, 5, 'Sunlite AAA Battery', NULL, 'STN39110', NULL, NULL, 26.00, 30.00, 6, 2, 'piece', NULL, 1, '2026-03-15 08:21:06', '2026-03-15 08:21:06'),
(66, 5, 'kangaro Stapler', NULL, 'STN01242', NULL, NULL, 140.00, 160.00, 5, 2, 'piece', NULL, 1, '2026-03-15 08:21:59', '2026-03-15 08:21:59'),
(67, 2, '200p Pencil Khata', NULL, 'STN48857', NULL, NULL, 85.00, 100.00, 30, 10, 'piece', NULL, 1, '2026-03-15 08:22:27', '2026-03-15 08:22:27'),
(68, 2, '1 Dista Paper (News)', NULL, 'STN00515', NULL, NULL, 12.00, 15.00, 84, 10, 'piece', NULL, 1, '2026-03-15 08:23:16', '2026-03-15 08:23:16'),
(69, 2, '55gsm Basundhara Rim Dista Paper', NULL, 'STN18505', NULL, NULL, 0.90, 1.50, 2000, 500, 'piece', NULL, 1, '2026-03-15 08:25:46', '2026-03-15 08:25:46'),
(70, 2, '84p Pencil Khata', NULL, 'STN09085', NULL, NULL, 25.00, 30.00, 65, 10, 'piece', NULL, 1, '2026-03-15 08:26:26', '2026-03-24 23:48:50'),
(71, 2, '21 Dista White', NULL, 'STN28985', NULL, NULL, 21.00, 25.00, 83, 10, 'piece', NULL, 1, '2026-03-15 08:26:53', '2026-03-15 08:26:53'),
(72, 2, 'Karnafuli Print Dista', NULL, 'STN15500', NULL, NULL, 27.00, 35.00, 83, 10, 'piece', NULL, 1, '2026-03-15 08:27:13', '2026-03-15 08:27:13'),
(73, 2, 'Drawing Khata', NULL, 'STN45238', NULL, NULL, 17.00, 25.00, 21, 10, 'piece', NULL, 1, '2026-03-15 08:27:34', '2026-03-15 08:27:34'),
(74, 2, '300p Math Khata (News)', NULL, 'STN32184', NULL, NULL, 41.00, 50.00, 26, 10, 'piece', NULL, 1, '2026-03-15 08:27:51', '2026-03-15 08:54:51'),
(75, 2, '65tk Ring Math Khata', NULL, 'STN31901', NULL, NULL, 48.00, 55.00, 52, 10, 'piece', NULL, 1, '2026-03-15 08:28:11', '2026-03-15 08:28:11'),
(76, 2, '85tk Ring Math Khata', NULL, 'STN53376', NULL, NULL, 68.00, 75.00, 36, 10, 'piece', NULL, 1, '2026-03-15 08:28:31', '2026-03-15 08:28:31'),
(77, 1, 'Linc Pen', NULL, 'STN99535', NULL, NULL, 12.00, 15.00, 9, 3, 'piece', NULL, 1, '2026-03-15 08:28:47', '2026-03-15 08:28:47'),
(78, 1, 'Doms Cutter - Sharpener', NULL, 'STN38537', NULL, NULL, 3.25, 5.00, 20, 5, 'piece', NULL, 1, '2026-03-15 08:29:06', '2026-03-17 16:32:35'),
(79, 6, 'Scale (Steel)', NULL, 'STN82317', NULL, NULL, 17.00, 25.00, 6, 2, 'piece', NULL, 1, '2026-03-15 08:30:09', '2026-03-15 08:30:09'),
(80, 3, 'Punch File', NULL, 'STN07526', NULL, NULL, 11.67, 17.00, 12, 4, 'piece', NULL, 1, '2026-03-15 08:30:33', '2026-03-15 08:30:33'),
(81, 5, 'Water Gum', NULL, 'STN72970', NULL, NULL, 15.00, 25.00, 4, 2, 'piece', NULL, 1, '2026-03-15 08:30:55', '2026-03-15 08:30:55'),
(82, 5, 'A4 Envelop', NULL, 'STN90311', NULL, NULL, 2.20, 5.00, 21, 5, 'piece', NULL, 1, '2026-03-15 08:31:22', '2026-03-15 09:00:15'),
(83, 5, 'Yellow Envelope', NULL, 'STN68107', NULL, NULL, 0.75, 2.00, 100, 20, 'piece', NULL, 1, '2026-03-15 08:31:54', '2026-03-15 08:31:54'),
(84, 5, '11/5 Khaki Envelope', NULL, 'STN68992', NULL, NULL, 0.85, 3.00, 100, 20, 'piece', NULL, 1, '2026-03-15 08:32:23', '2026-03-15 08:32:23'),
(85, 5, 'Photo Envelope', NULL, 'STN93029', NULL, NULL, 0.18, 0.50, 250, 50, 'piece', NULL, 1, '2026-03-15 08:39:03', '2026-03-15 08:39:03'),
(86, 5, 'Suta (সুতা) Small', NULL, 'STN97148', NULL, NULL, 2.44, 5.00, 9, 3, 'piece', NULL, 1, '2026-03-15 08:39:24', '2026-03-15 08:39:24'),
(87, 5, 'Suta (সুতা) Big', NULL, 'STN44661', NULL, NULL, 17.50, 20.00, 2, 1, 'piece', NULL, 1, '2026-03-15 08:39:49', '2026-03-15 08:39:49'),
(88, 5, 'Highlighter Pen', NULL, 'STN52186', NULL, NULL, 21.00, 25.00, 5, 2, 'piece', NULL, 1, '2026-03-15 08:40:25', '2026-03-15 08:40:25'),
(89, 5, 'White board Marker', NULL, 'STN18034', NULL, NULL, 15.00, 20.00, 2, 1, 'piece', NULL, 1, '2026-03-15 08:41:02', '2026-03-15 08:41:02'),
(90, 5, 'Permanent Marker', NULL, 'STN17774', NULL, NULL, 15.00, 20.00, 3, 1, 'piece', NULL, 1, '2026-03-15 08:41:17', '2026-03-15 08:41:17'),
(91, 5, 'kangaro Pin Remover', NULL, 'STN50531', NULL, NULL, 55.00, 65.00, 2, 1, 'piece', NULL, 1, '2026-03-15 08:41:33', '2026-03-15 08:41:33'),
(92, 5, 'Gold Pin Remover', NULL, 'STN89563', NULL, NULL, 42.00, 50.00, 2, 1, 'piece', NULL, 1, '2026-03-15 08:42:14', '2026-03-15 08:42:14'),
(93, 5, '32\" Paper Clip', NULL, 'STN32157', NULL, NULL, 3.33, 5.00, 6, 2, 'piece', NULL, 1, '2026-03-15 08:42:40', '2026-03-15 08:42:40'),
(94, 5, '41\" Paper Clip', NULL, 'STN37061', NULL, NULL, 5.50, 10.00, 6, 2, 'piece', NULL, 1, '2026-03-15 08:43:04', '2026-03-15 08:43:04'),
(95, 5, 'Petra Punch Machine', NULL, 'STN35876', NULL, NULL, 115.00, 130.00, 2, 1, 'piece', NULL, 1, '2026-03-15 08:43:29', '2026-03-15 08:43:29'),
(96, 5, 'kangaro Punch Machine', NULL, 'STN81732', NULL, NULL, 130.00, 145.00, 2, 1, 'piece', NULL, 1, '2026-03-15 08:43:47', '2026-03-15 08:43:52'),
(97, 1, 'Push Pencil', NULL, 'STN85931', NULL, NULL, 8.50, 12.00, 12, 3, 'piece', NULL, 1, '2026-03-15 08:44:12', '2026-03-15 08:44:12'),
(98, 5, 'Artline Pad', NULL, 'STN07138', NULL, NULL, 65.00, 80.00, 6, 3, 'piece', NULL, 1, '2026-03-15 08:44:31', '2026-03-15 08:44:31'),
(99, 6, 'ABEL Transparent Scale', NULL, 'STN88620', NULL, NULL, 6.00, 10.00, 20, 3, 'piece', NULL, 1, '2026-03-15 08:45:11', '2026-03-15 08:45:11'),
(100, 5, '10 No Register', NULL, 'STN50458', NULL, NULL, 38.00, 50.00, 6, 3, 'piece', NULL, 1, '2026-03-15 08:45:53', '2026-03-15 08:45:53'),
(101, 5, '16 No Register', NULL, 'STN63689', NULL, NULL, 45.00, 60.00, 6, 3, 'piece', NULL, 1, '2026-03-15 08:46:11', '2026-03-15 08:46:11'),
(102, 5, '24 No Register', NULL, 'STN87458', NULL, NULL, 65.00, 75.00, 3, 1, 'piece', NULL, 1, '2026-03-15 08:46:29', '2026-03-15 08:46:29'),
(103, 5, 'Sticky Note', NULL, 'STN47239', NULL, NULL, 22.00, 30.00, 4, 1, 'piece', NULL, 1, '2026-03-15 08:46:47', '2026-03-15 08:46:47'),
(104, 1, 'Good Luck Craze Ball Pen', NULL, 'STN71272', NULL, NULL, 8.00, 10.00, 5, 2, 'piece', NULL, 1, '2026-03-15 08:47:49', '2026-03-22 14:52:47'),
(105, 1, 'Matador i-teen Premium Ballpen', NULL, 'STN65713', NULL, NULL, 8.00, 10.00, 5, 2, 'piece', NULL, 1, '2026-03-15 08:48:33', '2026-03-23 19:54:56'),
(106, 1, 'Matador smothy Premium Ballpen with oil gel ink', NULL, 'STN79035', NULL, NULL, 8.00, 10.00, 5, 2, 'piece', NULL, 1, '2026-03-15 08:49:10', '2026-03-22 14:52:47'),
(107, 5, 'ID Card Holder', NULL, 'STN48559', NULL, NULL, 7.30, 10.00, 10, 2, 'piece', NULL, 1, '2026-03-15 08:49:39', '2026-03-15 08:49:39'),
(108, 5, 'Scientific Calculator 100', NULL, 'STN19725', NULL, NULL, 200.00, 300.00, 2, 1, 'piece', NULL, 1, '2026-03-15 08:50:06', '2026-03-15 08:50:06'),
(109, 5, 'Mega Calculator', NULL, 'STN86732', NULL, NULL, 450.00, 550.00, 2, 1, 'piece', NULL, 1, '2026-03-15 08:50:24', '2026-03-15 08:50:24'),
(110, 5, 'Gitizen Calculator', NULL, 'STN48128', NULL, NULL, 280.00, 350.00, 2, 1, 'piece', NULL, 1, '2026-03-15 08:50:40', '2026-03-15 08:50:40'),
(111, 5, 'Furoni (ফুড়োনি)', NULL, 'STN11809', NULL, NULL, 5.42, 10.00, 12, 3, 'piece', NULL, 1, '2026-03-15 08:51:02', '2026-03-15 08:51:02'),
(112, 5, 'Fevicol Aica Gum', NULL, 'STN70850', NULL, NULL, 60.00, 80.00, 3, 1, 'piece', NULL, 1, '2026-03-15 08:51:18', '2026-03-15 08:51:18'),
(113, 5, 'Sign Pen', NULL, 'STN10182', NULL, NULL, 3.57, 10.00, 26, 5, 'piece', NULL, 1, '2026-03-15 08:51:41', '2026-03-23 15:58:25'),
(114, 1, 'Matador Aqua gel', NULL, 'STN65357', NULL, NULL, 0.00, 12.00, 4, 0, 'piece', NULL, 1, '2026-03-15 09:15:30', '2026-03-15 09:15:30'),
(115, 8, 'Matador Tiktok masala Candy', NULL, 'STN10916', NULL, NULL, 1.50, 2.00, 139, 20, 'piece', NULL, 1, '2026-03-16 04:09:13', '2026-03-16 04:10:42'),
(116, 8, 'Matador Pop-out Mango Candy', NULL, 'STN97362', NULL, NULL, 1.33, 2.00, 150, 20, 'piece', NULL, 1, '2026-03-16 04:09:50', '2026-03-16 04:09:50'),
(117, 7, 'Photo to Photo', NULL, 'STN66649', NULL, NULL, 1.00, 10.00, 10000, 20, 'piece', NULL, 1, '2026-03-16 04:44:28', '2026-03-16 04:44:28'),
(118, 7, 'Photo to Photo 4 Copies', NULL, 'STN12931', NULL, NULL, 4.00, 30.00, 9990, 20, 'piece', NULL, 1, '2026-03-16 04:45:40', '2026-03-23 16:22:09'),
(119, 5, 'Stamp Pad Ink', NULL, 'STN94419', NULL, NULL, 80.00, 100.00, 3, 1, 'piece', NULL, 1, '2026-03-16 06:25:04', '2026-03-16 06:25:04'),
(120, 1, 'Hauser Darkies Extra Dark Pencil', NULL, 'STN92553', NULL, 'This is a test description', 7.00, 12.00, 0, 3, 'piece', NULL, 1, '2026-03-16 06:26:25', '2026-03-17 04:06:12'),
(121, 5, 'Wastage Bin Multipurpose - Small', 'RFL', 'STN76229', NULL, NULL, 60.00, 90.00, 6, 2, 'piece', NULL, 1, '2026-03-17 16:26:32', '2026-03-17 16:26:32'),
(122, 1, 'Rabbit Sharpener', 'Good Luck', 'STN62943', NULL, NULL, 10.00, 15.00, 18, 5, 'piece', NULL, 1, '2026-03-17 16:32:04', '2026-03-17 16:32:04'),
(123, 1, 'Stylo Sharpener', 'Good Luck', 'STN77283', NULL, NULL, 6.00, 10.00, 27, 5, 'piece', NULL, 1, '2026-03-17 16:33:57', '2026-03-23 14:38:13'),
(124, 1, 'Color Pencil - Big', 'Good Luck', 'STN67052', NULL, NULL, 90.00, 130.00, 3, 1, 'piece', NULL, 1, '2026-03-17 16:36:20', '2026-03-17 16:37:28'),
(125, 1, 'Color Pencil - Small', 'Good Luck', 'STN49042', NULL, NULL, 65.00, 105.00, 3, 1, 'piece', NULL, 1, '2026-03-17 16:37:12', '2026-03-17 16:37:12'),
(126, 8, 'Toothbrush - Adult', 'RFL', 'STN23165', NULL, NULL, 19.00, 40.00, 24, 5, 'piece', NULL, 1, '2026-03-17 16:40:15', '2026-03-17 16:40:32'),
(127, 8, 'Toothbrush - Kids', 'RFL', 'STN57201', NULL, NULL, 20.00, 40.00, 12, 4, 'piece', NULL, 1, '2026-03-17 16:40:58', '2026-03-17 16:40:58'),
(128, 7, 'Computer Compose', NULL, 'STN13116', NULL, NULL, 3.00, 25.00, 9998, 20, 'piece', NULL, 1, '2026-03-20 19:54:56', '2026-03-20 19:55:25');

-- --------------------------------------------------------

--
-- Table structure for table `sales`
--

CREATE TABLE `sales` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `invoice_number` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `customer_name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `customer_phone` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `subtotal` decimal(12,2) NOT NULL,
  `discount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `tax` decimal(12,2) NOT NULL DEFAULT '0.00',
  `total` decimal(12,2) NOT NULL,
  `paid_amount` decimal(12,2) NOT NULL,
  `change_amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `payment_method` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'cash',
  `status` enum('completed','pending','cancelled') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'completed',
  `notes` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sales`
--

INSERT INTO `sales` (`id`, `invoice_number`, `user_id`, `customer_name`, `customer_phone`, `subtotal`, `discount`, `tax`, `total`, `paid_amount`, `change_amount`, `payment_method`, `status`, `notes`, `created_at`, `updated_at`) VALUES
(1, 'INV202603140001', 1, NULL, NULL, 20.00, 5.00, 0.00, 15.00, 15.00, 0.00, 'cash', 'cancelled', NULL, '2026-03-14 09:54:42', '2026-03-15 07:11:56'),
(2, 'INV202603150001', 1, NULL, NULL, 40.00, 10.00, 0.00, 30.00, 30.00, 0.00, 'cash', 'cancelled', NULL, '2026-03-15 03:16:00', '2026-03-15 07:12:02'),
(3, 'INV202603150002', 1, NULL, NULL, 18.00, 3.00, 0.00, 15.00, 15.00, 0.00, 'cash', 'completed', NULL, '2026-03-15 08:53:49', '2026-03-15 08:53:49'),
(4, 'INV202603150003', 1, NULL, NULL, 10.00, 0.00, 0.00, 10.00, 10.00, 0.00, 'cash', 'completed', NULL, '2026-03-15 08:54:20', '2026-03-15 08:54:20'),
(5, 'INV202603150004', 1, NULL, NULL, 50.00, 5.00, 0.00, 45.00, 45.00, 0.00, 'cash', 'completed', NULL, '2026-03-15 08:54:51', '2026-03-15 08:54:51'),
(6, 'INV202603150005', 1, NULL, NULL, 40.00, 5.00, 0.00, 35.00, 35.00, 0.00, 'cash', 'completed', NULL, '2026-03-15 08:57:24', '2026-03-15 08:57:24'),
(7, 'INV202603150006', 1, NULL, NULL, 45.00, 0.00, 0.00, 45.00, 45.00, 0.00, 'cash', 'completed', NULL, '2026-03-15 08:57:47', '2026-03-15 08:57:47'),
(8, 'INV202603150007', 1, NULL, NULL, 120.00, 5.00, 0.00, 115.00, 115.00, 0.00, 'cash', 'completed', NULL, '2026-03-15 08:58:34', '2026-03-15 08:58:34'),
(9, 'INV202603150008', 1, NULL, NULL, 10.00, 0.00, 0.00, 10.00, 10.00, 0.00, 'cash', 'completed', NULL, '2026-03-15 08:59:33', '2026-03-15 08:59:33'),
(10, 'INV202603150009', 1, NULL, NULL, 20.00, 0.00, 0.00, 20.00, 20.00, 0.00, 'cash', 'completed', NULL, '2026-03-15 09:00:15', '2026-03-15 09:00:15'),
(11, 'INV202603150010', 1, NULL, NULL, 10.00, 0.00, 0.00, 10.00, 10.00, 0.00, 'cash', 'completed', NULL, '2026-03-15 09:07:23', '2026-03-15 09:07:23'),
(12, 'INV202603160001', 1, NULL, NULL, 10.00, 0.00, 0.00, 10.00, 20.00, 10.00, 'cash', 'completed', NULL, '2026-03-16 04:10:42', '2026-03-16 04:10:42'),
(13, 'INV202603160002', 1, NULL, NULL, 167.00, 2.00, 0.00, 165.00, 200.00, 35.00, 'cash', 'completed', NULL, '2026-03-16 04:46:35', '2026-03-16 04:46:35'),
(14, 'INV202603160003', 1, NULL, NULL, 47.00, 2.00, 0.00, 45.00, 45.00, 0.00, 'cash', 'completed', NULL, '2026-03-16 04:47:12', '2026-03-16 04:47:12'),
(15, 'INV202603160004', 1, NULL, NULL, 36.00, 1.00, 0.00, 35.00, 35.00, 0.00, 'cash', 'completed', NULL, '2026-03-16 05:59:33', '2026-03-16 05:59:33'),
(16, 'INV202603160005', 1, NULL, NULL, 6.00, 1.00, 0.00, 5.00, 5.00, 0.00, 'cash', 'completed', NULL, '2026-03-16 05:59:58', '2026-03-16 05:59:58'),
(17, 'INV202603160006', 1, NULL, NULL, 12.00, 2.00, 0.00, 10.00, 10.00, 0.00, 'cash', 'completed', NULL, '2026-03-16 06:34:37', '2026-03-16 06:34:37'),
(18, 'INV202603160007', 1, NULL, NULL, 60.00, 10.00, 0.00, 50.00, 50.00, 0.00, 'cash', 'completed', NULL, '2026-03-16 06:35:32', '2026-03-16 06:35:32'),
(19, 'INV202603160008', 1, NULL, NULL, 320.00, 60.00, 0.00, 260.00, 500.00, 240.00, 'cash', 'completed', NULL, '2026-03-16 09:10:02', '2026-03-16 09:10:02'),
(20, 'INV202603160009', 1, NULL, NULL, 15.00, 0.00, 0.00, 15.00, 15.00, 0.00, 'cash', 'completed', NULL, '2026-03-16 11:08:15', '2026-03-16 11:08:15'),
(21, 'INV202603170001', 1, NULL, NULL, 20.00, 0.00, 0.00, 20.00, 20.00, 0.00, 'cash', 'completed', NULL, '2026-03-17 21:30:01', '2026-03-17 21:30:01'),
(22, 'INV202603200001', 1, NULL, NULL, 50.00, 0.00, 0.00, 50.00, 50.00, 0.00, 'cash', 'completed', NULL, '2026-03-20 19:55:25', '2026-03-20 19:55:25'),
(23, 'INV202603220001', 1, NULL, NULL, 20.00, 0.00, 0.00, 20.00, 100.00, 80.00, 'cash', 'completed', NULL, '2026-03-22 14:52:47', '2026-03-22 14:52:47'),
(24, 'INV202603220002', 1, NULL, NULL, 80.00, 30.00, 0.00, 50.00, 50.00, 0.00, 'cash', 'completed', 'police officer', '2026-03-22 14:53:22', '2026-03-22 14:53:22'),
(25, 'INV202603220003', 4, NULL, NULL, 10.00, 0.00, 0.00, 10.00, 10.00, 0.00, 'cash', 'completed', NULL, '2026-03-22 16:21:17', '2026-03-22 16:21:17'),
(26, 'INV202603230001', 4, NULL, NULL, 10.00, 0.00, 0.00, 10.00, 10.00, 0.00, 'cash', 'completed', NULL, '2026-03-23 14:38:13', '2026-03-23 14:38:13'),
(27, 'INV202603230002', 1, NULL, NULL, 10.00, 0.00, 0.00, 10.00, 10.00, 0.00, 'cash', 'completed', NULL, '2026-03-23 15:58:24', '2026-03-23 15:58:24'),
(28, 'INV202603230003', 1, NULL, NULL, 136.00, 6.00, 0.00, 130.00, 130.00, 0.00, 'cash', 'completed', NULL, '2026-03-23 16:22:09', '2026-03-23 16:22:09'),
(29, 'INV202603230004', 1, NULL, NULL, 10.00, 0.00, 0.00, 10.00, 10.00, 0.00, 'cash', 'completed', NULL, '2026-03-23 19:54:56', '2026-03-23 19:54:56'),
(30, 'INV202603230005', 1, NULL, NULL, 30.00, 0.00, 0.00, 30.00, 30.00, 0.00, 'cash', 'completed', NULL, '2026-03-23 21:47:03', '2026-03-23 21:47:03'),
(31, 'INV202603240001', 1, NULL, NULL, 36.00, 1.00, 0.00, 35.00, 100.00, 65.00, 'cash', 'completed', NULL, '2026-03-24 18:18:33', '2026-03-24 18:18:33'),
(32, 'INV202603240002', 1, NULL, NULL, 80.00, 5.00, 0.00, 75.00, 500.00, 425.00, 'cash', 'completed', NULL, '2026-03-24 23:48:50', '2026-03-24 23:48:50'),
(33, 'INV202603240003', 1, NULL, NULL, 30.00, 4.00, 0.00, 26.00, 26.00, 0.00, 'cash', 'completed', NULL, '2026-03-25 01:29:26', '2026-03-25 01:29:26'),
(34, 'INV202603240004', 1, NULL, NULL, 90.00, 0.00, 0.00, 90.00, 90.00, 0.00, 'cash', 'completed', NULL, '2026-03-25 01:30:10', '2026-03-25 01:30:10'),
(35, 'INV202603240005', 1, NULL, NULL, 20.00, 0.00, 0.00, 20.00, 20.00, 0.00, 'cash', 'completed', 'eticket purchase consultation fee', '2026-03-25 01:31:07', '2026-03-25 01:31:07');

-- --------------------------------------------------------

--
-- Table structure for table `sale_items`
--

CREATE TABLE `sale_items` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `sale_id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `product_name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `unit_price` decimal(10,2) NOT NULL,
  `cost_price` decimal(10,2) NOT NULL,
  `quantity` int(11) NOT NULL,
  `discount` decimal(10,2) NOT NULL DEFAULT '0.00',
  `total` decimal(12,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sale_items`
--

INSERT INTO `sale_items` (`id`, `sale_id`, `product_id`, `product_name`, `unit_price`, `cost_price`, `quantity`, `discount`, `total`, `created_at`, `updated_at`) VALUES
(1, 1, 27, 'BW Print', 10.00, 1.00, 2, 0.00, 20.00, '2026-03-14 09:54:42', '2026-03-14 09:54:42'),
(2, 2, 1, 'Blue Ballpoint Pen', 10.00, 5.00, 4, 0.00, 40.00, '2026-03-15 03:16:00', '2026-03-15 03:16:00'),
(3, 3, 30, 'Matador All-time', 6.00, 5.00, 3, 0.00, 18.00, '2026-03-15 08:53:49', '2026-03-15 08:53:49'),
(4, 4, 113, 'Sign Pen', 10.00, 3.57, 1, 0.00, 10.00, '2026-03-15 08:54:20', '2026-03-15 08:54:20'),
(5, 5, 74, '300p Math Khata (News)', 50.00, 41.00, 1, 0.00, 50.00, '2026-03-15 08:54:51', '2026-03-15 08:54:51'),
(6, 6, 60, 'Math Khata (84p)', 35.00, 27.08, 1, 0.00, 35.00, '2026-03-15 08:57:24', '2026-03-15 08:57:24'),
(7, 6, 32, 'Matador Pin-Point Black', 5.00, 4.50, 1, 0.00, 5.00, '2026-03-15 08:57:24', '2026-03-15 08:57:24'),
(8, 7, 32, 'Matador Pin-Point Black', 5.00, 4.50, 9, 0.00, 45.00, '2026-03-15 08:57:47', '2026-03-15 08:57:47'),
(9, 8, 45, 'Matador All-time Scale (30cm)', 15.00, 11.25, 8, 0.00, 120.00, '2026-03-15 08:58:34', '2026-03-15 08:58:34'),
(10, 9, 32, 'Matador Pin-Point Black', 5.00, 4.50, 2, 0.00, 10.00, '2026-03-15 08:59:33', '2026-03-15 08:59:33'),
(11, 10, 82, 'A4 Envelop', 5.00, 2.20, 4, 0.00, 20.00, '2026-03-15 09:00:15', '2026-03-15 09:00:15'),
(12, 11, 31, 'Matador Hi-School Black', 5.00, 4.50, 2, 0.00, 10.00, '2026-03-15 09:07:23', '2026-03-15 09:07:23'),
(13, 12, 31, 'Matador Hi-School Black', 5.00, 4.50, 1, 0.00, 5.00, '2026-03-16 04:10:42', '2026-03-16 04:10:42'),
(14, 12, 29, 'Photocopy', 3.00, 1.00, 1, 0.00, 3.00, '2026-03-16 04:10:42', '2026-03-16 04:10:42'),
(15, 12, 115, 'Matador Tiktok masala Candy', 2.00, 1.50, 1, 0.00, 2.00, '2026-03-16 04:10:42', '2026-03-16 04:10:42'),
(16, 13, 118, 'Photo to Photo 4 Copies', 30.00, 4.00, 5, 0.00, 150.00, '2026-03-16 04:46:35', '2026-03-16 04:46:35'),
(17, 13, 31, 'Matador Hi-School Black', 5.00, 4.50, 1, 0.00, 5.00, '2026-03-16 04:46:35', '2026-03-16 04:46:35'),
(18, 13, 29, 'Photocopy', 3.00, 1.00, 4, 0.00, 12.00, '2026-03-16 04:46:35', '2026-03-16 04:46:35'),
(19, 14, 118, 'Photo to Photo 4 Copies', 30.00, 4.00, 1, 0.00, 30.00, '2026-03-16 04:47:12', '2026-03-16 04:47:12'),
(20, 14, 29, 'Photocopy', 3.00, 1.00, 4, 0.00, 12.00, '2026-03-16 04:47:12', '2026-03-16 04:47:12'),
(21, 14, 32, 'Matador Pin-Point Black', 5.00, 4.50, 1, 0.00, 5.00, '2026-03-16 04:47:12', '2026-03-16 04:47:12'),
(22, 15, 118, 'Photo to Photo 4 Copies', 30.00, 4.00, 1, 0.00, 30.00, '2026-03-16 05:59:33', '2026-03-16 05:59:33'),
(23, 15, 29, 'Photocopy', 3.00, 1.00, 2, 0.00, 6.00, '2026-03-16 05:59:33', '2026-03-16 05:59:33'),
(24, 16, 29, 'Photocopy', 3.00, 1.00, 2, 0.00, 6.00, '2026-03-16 05:59:58', '2026-03-16 05:59:58'),
(25, 17, 29, 'Photocopy', 3.00, 1.00, 4, 0.00, 12.00, '2026-03-16 06:34:37', '2026-03-16 06:34:37'),
(26, 18, 29, 'Photocopy', 3.00, 1.00, 20, 0.00, 60.00, '2026-03-16 06:35:32', '2026-03-16 06:35:32'),
(27, 19, 43, 'Matador Pencil Box (small)', 40.00, 30.00, 2, 0.00, 80.00, '2026-03-16 09:10:02', '2026-03-16 09:10:02'),
(28, 19, 120, 'Hauser Darkies Extra Dark Pencil', 12.00, 7.00, 10, 0.00, 120.00, '2026-03-16 09:10:02', '2026-03-16 09:10:02'),
(29, 19, 40, 'Matador Pluto Pencil 2B', 10.00, 4.58, 12, 0.00, 120.00, '2026-03-16 09:10:02', '2026-03-16 09:10:02'),
(30, 20, 29, 'Photocopy', 3.00, 1.00, 5, 0.00, 15.00, '2026-03-16 11:08:16', '2026-03-16 11:08:16'),
(31, 21, 32, 'Matador Pin-Point Black', 5.00, 4.50, 4, 0.00, 20.00, '2026-03-17 21:30:01', '2026-03-17 21:30:01'),
(32, 22, 128, 'Computer Compose', 25.00, 3.00, 2, 0.00, 50.00, '2026-03-20 19:55:25', '2026-03-20 19:55:25'),
(33, 23, 104, 'Good Luck Craze Ball Pen', 10.00, 8.00, 1, 0.00, 10.00, '2026-03-22 14:52:47', '2026-03-22 14:52:47'),
(34, 23, 106, 'Matador smothy Premium Ballpen with oil gel ink', 10.00, 8.00, 1, 0.00, 10.00, '2026-03-22 14:52:47', '2026-03-22 14:52:47'),
(35, 24, 28, 'Color Print', 20.00, 1.00, 4, 0.00, 80.00, '2026-03-22 14:53:22', '2026-03-22 14:53:22'),
(36, 25, 31, 'Matador Hi-School Black', 5.00, 4.50, 2, 0.00, 10.00, '2026-03-22 16:21:17', '2026-03-22 16:21:17'),
(37, 26, 123, 'Stylo Sharpener', 10.00, 6.00, 1, 0.00, 10.00, '2026-03-23 14:38:13', '2026-03-23 14:38:13'),
(38, 27, 113, 'Sign Pen', 10.00, 3.57, 1, 0.00, 10.00, '2026-03-23 15:58:25', '2026-03-23 15:58:25'),
(39, 28, 27, 'BW Print', 10.00, 1.00, 4, 0.00, 40.00, '2026-03-23 16:22:09', '2026-03-23 16:22:09'),
(40, 28, 118, 'Photo to Photo 4 Copies', 30.00, 4.00, 3, 0.00, 90.00, '2026-03-23 16:22:09', '2026-03-23 16:22:09'),
(41, 28, 29, 'Photocopy', 3.00, 1.00, 2, 0.00, 6.00, '2026-03-23 16:22:09', '2026-03-23 16:22:09'),
(42, 29, 105, 'Matador i-teen Premium Ballpen', 10.00, 8.00, 1, 0.00, 10.00, '2026-03-23 19:54:56', '2026-03-23 19:54:56'),
(43, 30, 27, 'BW Print', 10.00, 1.00, 3, 0.00, 30.00, '2026-03-23 21:47:03', '2026-03-23 21:47:03'),
(44, 31, 29, 'Photocopy', 3.00, 1.00, 12, 0.00, 36.00, '2026-03-24 18:18:33', '2026-03-24 18:18:33'),
(45, 32, 60, 'Math Khata (84p)', 35.00, 27.08, 1, 0.00, 35.00, '2026-03-24 23:48:50', '2026-03-24 23:48:50'),
(46, 32, 70, '84p Pencil Khata', 30.00, 25.00, 1, 0.00, 30.00, '2026-03-24 23:48:50', '2026-03-24 23:48:50'),
(47, 32, 32, 'Matador Pin-Point Black', 5.00, 4.50, 1, 0.00, 5.00, '2026-03-24 23:48:50', '2026-03-24 23:48:50'),
(48, 32, 40, 'Matador Pluto Pencil 2B', 10.00, 4.58, 1, 0.00, 10.00, '2026-03-24 23:48:50', '2026-03-24 23:48:50'),
(49, 33, 29, 'Photocopy', 3.00, 1.00, 10, 0.00, 30.00, '2026-03-25 01:29:26', '2026-03-25 01:29:26'),
(50, 34, 32, 'Matador Pin-Point Black', 5.00, 4.50, 6, 0.00, 30.00, '2026-03-25 01:30:10', '2026-03-25 01:30:10'),
(51, 34, 27, 'BW Print', 10.00, 1.00, 6, 0.00, 60.00, '2026-03-25 01:30:10', '2026-03-25 01:30:10'),
(52, 35, 27, 'BW Print', 10.00, 1.00, 2, 0.00, 20.00, '2026-03-25 01:31:07', '2026-03-25 01:31:07');

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

CREATE TABLE `settings` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `key` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `settings`
--

INSERT INTO `settings` (`id`, `key`, `value`, `created_at`, `updated_at`) VALUES
(1, 'currency_symbol', '৳', '2026-03-14 09:39:50', '2026-03-14 09:39:50'),
(2, 'currency_code', 'BDT', '2026-03-14 09:39:50', '2026-03-14 09:39:50'),
(3, 'shop_name', 'Stationery POS', '2026-03-14 09:39:50', '2026-03-14 09:39:50'),
(4, 'shop_address', 'Nowdapara, Shah Makhdum, Rajshahi', '2026-03-14 09:39:50', '2026-03-14 09:40:32'),
(5, 'shop_phone', '01716284815', '2026-03-14 09:39:50', '2026-03-14 09:40:32'),
(6, 'tax_percentage', '0', '2026-03-14 09:39:50', '2026-03-14 09:39:50'),
(7, 'payment_methods', '[{\"code\":\"cash\",\"name\":\"Cash\"},{\"code\":\"card\",\"name\":\"Card\"}]', '2026-03-15 03:25:59', '2026-03-15 03:25:59'),
(8, 'timezone', 'Asia/Dhaka', '2026-03-16 04:02:33', '2026-03-16 04:02:33');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `role` enum('admin','cashier') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'cashier',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `role`, `is_active`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'Admin', 'admin@pos.com', NULL, '$2y$12$.n/5Vng2l4e183gtA2WX0uwVHVzYqCZLyJrZ4m/boJsURnyJAuoga', 'admin', 1, NULL, '2026-03-14 09:33:19', '2026-03-18 13:27:55'),
(2, 'Moktadir Rahman', 'moktadir@pos.com', NULL, '$2y$12$XaxprI0QV0NCCRISvexNOuax4C/C9nypPGbrkkSfiqcYBWu2O2S4y', 'cashier', 1, NULL, '2026-03-17 03:55:58', '2026-03-17 04:10:32'),
(4, 'Amzad Hossain', 'amzad@pos.com', NULL, '$2y$12$7OMaZMSk9ui7VLbqr/6WLOWxfJ8cdI6J6AlIpDVnBrwJlSext4j5e', 'admin', 1, 'c0qQ5QwCmrBjP4kNMpnqtQoGPMcaSatlVlYFJuJ9XwhP1DCi2Uv2kQUw2idX', '2026-03-17 17:04:28', '2026-03-17 17:04:28');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `activity_logs`
--
ALTER TABLE `activity_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `activity_logs_subject_type_subject_id_index` (`subject_type`,`subject_id`),
  ADD KEY `activity_logs_user_id_created_at_index` (`user_id`,`created_at`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

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
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `products_sku_unique` (`sku`),
  ADD UNIQUE KEY `products_barcode_unique` (`barcode`),
  ADD KEY `products_category_id_foreign` (`category_id`);

--
-- Indexes for table `sales`
--
ALTER TABLE `sales`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `sales_invoice_number_unique` (`invoice_number`),
  ADD KEY `sales_user_id_foreign` (`user_id`);

--
-- Indexes for table `sale_items`
--
ALTER TABLE `sale_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sale_items_sale_id_foreign` (`sale_id`),
  ADD KEY `sale_items_product_id_foreign` (`product_id`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `settings_key_unique` (`key`);

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
-- AUTO_INCREMENT for table `activity_logs`
--
ALTER TABLE `activity_logs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=129;

--
-- AUTO_INCREMENT for table `sales`
--
ALTER TABLE `sales`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT for table `sale_items`
--
ALTER TABLE `sale_items`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=53;

--
-- AUTO_INCREMENT for table `settings`
--
ALTER TABLE `settings`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
