mysqldump: [Warning] Using a password on the command line interface can be insecure.
-- MySQL dump 10.13  Distrib 5.7.44, for Linux (x86_64)
--
-- Host: localhost    Database: matixpre_pos
-- ------------------------------------------------------
-- Server version	5.7.44-log-cll-lve

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
mysqldump: Error: 'Access denied; you need (at least one of) the PROCESS privilege(s) for this operation' when trying to dump tablespaces

--
-- Table structure for table `activity_logs`
--

DROP TABLE IF EXISTS `activity_logs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `activity_logs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `action` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `subject_type` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `subject_id` bigint(20) unsigned DEFAULT NULL,
  `old_values` json DEFAULT NULL,
  `new_values` json DEFAULT NULL,
  `description` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `activity_logs_subject_type_subject_id_index` (`subject_type`,`subject_id`),
  KEY `activity_logs_user_id_created_at_index` (`user_id`,`created_at`),
  CONSTRAINT `activity_logs_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `activity_logs`
--

LOCK TABLES `activity_logs` WRITE;
/*!40000 ALTER TABLE `activity_logs` DISABLE KEYS */;
/*!40000 ALTER TABLE `activity_logs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `categories`
--

DROP TABLE IF EXISTS `categories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `categories` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `categories`
--

LOCK TABLES `categories` WRITE;
/*!40000 ALTER TABLE `categories` DISABLE KEYS */;
INSERT INTO `categories` VALUES (1,'Pens & Pencils','Writing instruments including ballpoint pens, gel pens, and pencils',1,'2026-03-14 13:33:19','2026-03-14 13:33:19'),(2,'Notebooks & Papers','Notebooks, registers, loose sheets, and paper products',1,'2026-03-14 13:33:19','2026-03-14 13:33:19'),(3,'Files & Folders','File folders, binders, and document organizers',1,'2026-03-14 13:33:19','2026-03-14 13:33:19'),(4,'Art Supplies','Colors, brushes, sketch books, and craft materials',1,'2026-03-14 13:33:19','2026-03-14 13:33:19'),(5,'Office Supplies','Staplers, scissors, tape, and general office items',1,'2026-03-14 13:33:19','2026-03-14 13:33:19'),(6,'School Bags','Backpacks, sling bags, and pouches',1,'2026-03-14 13:33:19','2026-03-14 13:33:19'),(7,'Printing Service','BW Print, Color Print, Photocopy',1,'2026-03-14 13:51:32','2026-03-14 13:51:32'),(8,'Others','All others',1,'2026-03-16 08:06:49','2026-03-16 08:07:01');
/*!40000 ALTER TABLE `categories` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `due_payments`
--

DROP TABLE IF EXISTS `due_payments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `due_payments` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `sale_id` bigint(20) unsigned NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `payment_method` enum('cash','card','bank_transfer','other') COLLATE utf8mb4_unicode_ci NOT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci,
  `user_id` bigint(20) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `due_payments_sale_id_foreign` (`sale_id`),
  KEY `due_payments_user_id_foreign` (`user_id`),
  CONSTRAINT `due_payments_sale_id_foreign` FOREIGN KEY (`sale_id`) REFERENCES `sales` (`id`) ON DELETE CASCADE,
  CONSTRAINT `due_payments_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `due_payments`
--

LOCK TABLES `due_payments` WRITE;
/*!40000 ALTER TABLE `due_payments` DISABLE KEYS */;
/*!40000 ALTER TABLE `due_payments` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `expenses`
--

DROP TABLE IF EXISTS `expenses`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `expenses` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned NOT NULL,
  `title` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `category` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Other',
  `amount` decimal(12,2) NOT NULL,
  `expense_date` date NOT NULL,
  `payment_method` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'cash',
  `notes` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `expenses_user_id_foreign` (`user_id`),
  CONSTRAINT `expenses_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `expenses`
--

LOCK TABLES `expenses` WRITE;
/*!40000 ALTER TABLE `expenses` DISABLE KEYS */;
/*!40000 ALTER TABLE `expenses` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `inventory_batches`
--

DROP TABLE IF EXISTS `inventory_batches`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `inventory_batches` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `product_id` bigint(20) unsigned NOT NULL,
  `purchase_item_id` bigint(20) unsigned DEFAULT NULL,
  `cost_price` decimal(10,2) NOT NULL,
  `selling_price` decimal(10,2) DEFAULT NULL,
  `quantity_initial` int(11) NOT NULL,
  `quantity_remaining` int(11) NOT NULL,
  `batch_date` date NOT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `inventory_batches_purchase_item_id_foreign` (`purchase_item_id`),
  KEY `inventory_batches_product_id_batch_date_index` (`product_id`,`batch_date`),
  KEY `inventory_batches_product_id_quantity_remaining_index` (`product_id`,`quantity_remaining`),
  CONSTRAINT `inventory_batches_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  CONSTRAINT `inventory_batches_purchase_item_id_foreign` FOREIGN KEY (`purchase_item_id`) REFERENCES `purchase_items` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `inventory_batches`
--

LOCK TABLES `inventory_batches` WRITE;
/*!40000 ALTER TABLE `inventory_batches` DISABLE KEYS */;
/*!40000 ALTER TABLE `inventory_batches` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `migrations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migrations`
--

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` VALUES (1,'2019_12_14_000001_create_personal_access_tokens_table',1),(2,'2024_01_01_000001_create_users_table',1),(3,'2024_01_01_000002_create_categories_table',1),(4,'2024_01_01_000003_create_products_table',1),(5,'2024_01_01_000004_create_sales_table',1),(6,'2024_01_01_000005_create_sale_items_table',1),(7,'2024_01_01_000006_create_settings_table',1),(8,'2024_01_01_000007_make_payment_methods_configurable',1),(9,'2024_01_01_000008_add_timezone_setting',1),(10,'2026_03_14_000009_add_company_to_products_table',1),(11,'2026_03_17_000001_create_activity_logs_table',1),(12,'2026_03_19_000001_create_suppliers_table',1),(13,'2026_03_19_000002_create_purchases_table',1),(14,'2026_03_19_000003_create_purchase_items_table',1),(15,'2026_03_19_000004_create_expenses_table',1),(16,'2026_03_28_142156_add_due_amount_to_sales_table',1),(17,'2026_03_28_142504_create_due_payments_table',1),(18,'2026_04_03_121112_create_inventory_batches_table',1),(19,'2026_04_03_121232_create_fifo_batches_for_existing_inventory',1),(20,'2026_04_03_174827_add_custom_price_fields_to_products_and_sale_items',1);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `password_reset_tokens`
--

DROP TABLE IF EXISTS `password_reset_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `password_reset_tokens` (
  `email` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `password_reset_tokens`
--

LOCK TABLES `password_reset_tokens` WRITE;
/*!40000 ALTER TABLE `password_reset_tokens` DISABLE KEYS */;
/*!40000 ALTER TABLE `password_reset_tokens` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `personal_access_tokens`
--

DROP TABLE IF EXISTS `personal_access_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `personal_access_tokens` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `tokenable_type` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint(20) unsigned NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text COLLATE utf8mb4_unicode_ci,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `personal_access_tokens`
--

LOCK TABLES `personal_access_tokens` WRITE;
/*!40000 ALTER TABLE `personal_access_tokens` DISABLE KEYS */;
/*!40000 ALTER TABLE `personal_access_tokens` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `products`
--

DROP TABLE IF EXISTS `products`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `products` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `category_id` bigint(20) unsigned NOT NULL,
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
  `requires_custom_price` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `products_sku_unique` (`sku`),
  UNIQUE KEY `products_barcode_unique` (`barcode`),
  KEY `products_category_id_foreign` (`category_id`),
  CONSTRAINT `products_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=172 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `products`
--

LOCK TABLES `products` WRITE;
/*!40000 ALTER TABLE `products` DISABLE KEYS */;
INSERT INTO `products` VALUES (27,7,'BW Print',NULL,'STN50296',NULL,'Print from computer using Toshiba eStudio 2523AD photocopier',1.00,10.00,9839,10,'piece',NULL,1,0,'2026-03-14 13:53:10','2026-04-04 05:02:20'),(28,7,'Color Print',NULL,'STN31434',NULL,'Color Print from computer using Epson L130',1.00,20.00,9992,10,'piece',NULL,1,0,'2026-03-15 11:18:13','2026-03-30 04:25:38'),(29,7,'Photocopy',NULL,'STN75415',NULL,'Print from computer using Toshiba eStudio 2523AD photocopier',1.00,3.00,9567,10,'piece',NULL,1,0,'2026-03-15 11:19:19','2026-04-04 05:02:20'),(30,1,'Matador All-time',NULL,'STN41526',NULL,NULL,5.00,6.00,33,10,'piece','uploads/products/product_69bb9c36559eb1.50563325.jpg',1,0,'2026-03-15 11:27:50','2026-03-31 21:35:02'),(31,1,'Matador Hi-School Black',NULL,'STN42326',NULL,NULL,4.50,5.00,72,10,'piece',NULL,1,0,'2026-03-15 11:33:01','2026-03-22 20:21:17'),(32,1,'Matador Pin-Point Black',NULL,'STN68406',NULL,NULL,4.50,5.00,58,10,'piece',NULL,1,0,'2026-03-15 11:33:26','2026-04-03 05:11:44'),(33,1,'Matador Orbit',NULL,'STN55686',NULL,NULL,4.50,5.00,23,10,'piece',NULL,1,0,'2026-03-15 11:43:34','2026-04-01 01:17:56'),(34,1,'Matador I-teen Rio Black',NULL,'STN01565',NULL,NULL,8.67,10.00,24,10,'piece','uploads/products/product_69bbb97a976841.60293417.jpg',1,0,'2026-03-15 11:44:07','2026-04-01 01:17:56'),(35,1,'Matador Pencilic Black',NULL,'STN36917',NULL,NULL,4.00,5.00,19,10,'piece',NULL,1,0,'2026-03-15 11:45:53','2026-03-26 17:42:19'),(36,1,'Matador I-teen Gel Black',NULL,'STN76635',NULL,NULL,10.00,15.00,24,10,'piece',NULL,1,0,'2026-03-15 11:48:01','2026-03-15 11:48:01'),(37,1,'Matador I-teen Gel Red',NULL,'STN91111',NULL,NULL,10.00,15.00,24,10,'piece',NULL,1,0,'2026-03-15 11:48:20','2026-03-15 11:49:36'),(38,1,'Matador I-teen Gel Blue',NULL,'STN10926',NULL,NULL,10.00,15.00,23,10,'piece',NULL,1,0,'2026-03-15 11:50:03','2026-03-28 00:59:09'),(39,1,'Matador I-teen Gel Green',NULL,'STN36415',NULL,NULL,10.00,15.00,24,10,'piece',NULL,1,0,'2026-03-15 11:50:26','2026-03-15 11:50:26'),(40,1,'Matador Pluto Pencil 2B',NULL,'STN78027',NULL,NULL,4.58,10.00,7,3,'piece',NULL,1,0,'2026-03-15 12:03:45','2026-04-04 05:02:20'),(41,1,'Matador i-teen Eraser',NULL,'STN51683',NULL,NULL,3.83,5.00,29,10,'piece',NULL,1,0,'2026-03-15 12:05:49','2026-03-31 21:35:02'),(42,1,'Matador Woodmark Eraser',NULL,'STN26112',NULL,NULL,8.13,10.00,20,10,'piece',NULL,1,0,'2026-03-15 12:06:10','2026-04-04 05:02:20'),(43,6,'Matador Pencil Box (small)',NULL,'STN21689',NULL,NULL,30.00,40.00,10,3,'piece',NULL,1,0,'2026-03-15 12:06:51','2026-03-16 13:10:02'),(44,6,'Matador Double Decker Pencil Box',NULL,'STN77564',NULL,NULL,55.00,75.00,12,3,'piece',NULL,1,0,'2026-03-15 12:07:15','2026-03-15 12:07:15'),(45,6,'Matador All-time Scale (30cm)',NULL,'STN20676',NULL,NULL,11.25,15.00,4,3,'piece',NULL,1,0,'2026-03-15 12:08:35','2026-03-15 12:58:34'),(46,6,'Matador All-time Scale (15cm)',NULL,'STN08954',NULL,NULL,7.92,10.00,12,3,'piece',NULL,1,0,'2026-03-15 12:08:58','2026-03-15 12:08:58'),(47,1,'Matador smoothy Pencil 2B',NULL,'STN47338',NULL,NULL,5.83,10.00,21,6,'piece',NULL,1,0,'2026-03-15 12:09:32','2026-04-02 01:05:57'),(48,5,'Matador Officemate Stapler',NULL,'STN64518',NULL,NULL,88.00,120.00,6,2,'piece',NULL,1,0,'2026-03-15 12:10:03','2026-03-15 12:10:03'),(49,5,'Matador Officemate Correction Pen',NULL,'STN35328',NULL,NULL,30.00,40.00,5,2,'piece',NULL,1,0,'2026-03-15 12:10:43','2026-03-25 17:13:55'),(50,6,'Matador Clip Bloard',NULL,'STN57357',NULL,NULL,75.00,100.00,6,2,'piece',NULL,1,0,'2026-03-15 12:11:11','2026-03-15 12:11:11'),(51,6,'Matador Paper Clip Bloard',NULL,'STN77237',NULL,NULL,50.00,65.00,6,2,'piece',NULL,1,0,'2026-03-15 12:11:32','2026-03-15 12:11:32'),(52,3,'Matador Clear Bag (A4)',NULL,'STN85257',NULL,NULL,13.00,20.00,12,3,'piece',NULL,1,0,'2026-03-15 12:12:11','2026-03-15 12:12:11'),(53,3,'Matador Clear Bag (FC)',NULL,'STN46203',NULL,NULL,16.00,25.00,12,3,'piece',NULL,1,0,'2026-03-15 12:12:30','2026-03-15 12:12:30'),(54,5,'Aica Gum',NULL,'STN31808',NULL,NULL,15.00,20.00,9,3,'piece',NULL,1,0,'2026-03-15 12:13:11','2026-04-04 05:02:20'),(55,5,'Transparent Tape',NULL,'STN03634',NULL,NULL,6.67,10.00,12,3,'piece',NULL,1,0,'2026-03-15 12:13:56','2026-03-15 12:13:56'),(56,5,'Wiring Tape',NULL,'STN42217',NULL,NULL,11.43,20.00,25,3,'piece',NULL,1,0,'2026-03-15 12:14:25','2026-03-30 01:10:01'),(57,5,'Cartoon Tape',NULL,'STN58696',NULL,NULL,30.00,40.00,4,3,'piece',NULL,1,0,'2026-03-15 12:14:49','2026-04-01 01:14:24'),(58,5,'Super Glue',NULL,'STN79492',NULL,NULL,5.83,10.00,9,3,'piece',NULL,1,0,'2026-03-15 12:15:09','2026-03-29 17:22:16'),(59,2,'Math Khata (124p)',NULL,'STN47576',NULL,NULL,38.33,45.00,12,3,'piece',NULL,1,0,'2026-03-15 12:15:43','2026-03-15 12:15:43'),(60,2,'Math Khata (84p)',NULL,'STN10303',NULL,NULL,27.08,30.00,2,3,'piece',NULL,1,0,'2026-03-15 12:16:02','2026-04-04 05:02:20'),(61,2,'A4 Creative 70gsm',NULL,'STN40457',NULL,NULL,0.65,1.00,2500,500,'piece',NULL,1,0,'2026-03-15 12:17:24','2026-03-15 12:19:44'),(62,2,'A4 Chandan 80gsm',NULL,'STN49255',NULL,NULL,0.75,2.00,3000,500,'piece',NULL,1,0,'2026-03-15 12:18:17','2026-03-15 12:18:46'),(63,5,'kangaro Stapler Pin 24/6',NULL,'STN60017',NULL,NULL,21.00,25.00,12,5,'piece',NULL,1,0,'2026-03-15 12:20:19','2026-04-03 18:23:45'),(64,5,'Sunlite AA Battery',NULL,'STN87928',NULL,NULL,18.00,20.00,13,4,'piece',NULL,1,0,'2026-03-15 12:20:44','2026-04-02 06:33:33'),(65,5,'Sunlite AAA Battery 2 Pcs',NULL,'STN39110',NULL,NULL,26.00,30.00,5,2,'piece',NULL,1,0,'2026-03-15 12:21:06','2026-03-31 17:32:38'),(66,5,'kangaro Stapler',NULL,'STN01242',NULL,NULL,140.00,160.00,4,2,'piece',NULL,1,0,'2026-03-15 12:21:59','2026-03-26 01:56:43'),(67,2,'200p Pencil Khata',NULL,'STN48857',NULL,NULL,85.00,100.00,24,10,'piece',NULL,1,0,'2026-03-15 12:22:27','2026-03-30 01:07:56'),(68,2,'1 Dista Paper (News)',NULL,'STN00515',NULL,NULL,12.00,15.00,83,10,'piece',NULL,1,0,'2026-03-15 12:23:16','2026-04-04 17:22:25'),(69,2,'55gsm Basundhara Rim Dista Paper',NULL,'STN18505',NULL,NULL,0.90,1.50,1750,500,'piece',NULL,1,0,'2026-03-15 12:25:46','2026-03-26 04:05:31'),(70,2,'84p Pencil Khata',NULL,'STN09085',NULL,NULL,25.00,30.00,58,10,'piece',NULL,1,0,'2026-03-15 12:26:26','2026-03-31 21:35:02'),(71,2,'21 Dista White',NULL,'STN28985',NULL,NULL,21.00,25.00,82,10,'piece',NULL,1,0,'2026-03-15 12:26:53','2026-03-31 00:37:21'),(72,2,'Karnafuli Print Dista',NULL,'STN15500',NULL,NULL,27.00,35.00,82,10,'piece',NULL,1,0,'2026-03-15 12:27:13','2026-04-01 17:49:07'),(73,2,'Drawing Khata',NULL,'STN45238',NULL,NULL,17.00,25.00,19,10,'piece',NULL,1,0,'2026-03-15 12:27:34','2026-03-26 05:19:53'),(74,2,'300p Math Khata (News)',NULL,'STN32184',NULL,NULL,41.00,50.00,25,10,'piece',NULL,1,0,'2026-03-15 12:27:51','2026-03-31 03:19:17'),(75,2,'65tk Ring Math Khata',NULL,'STN31901',NULL,NULL,48.00,55.00,52,10,'piece',NULL,1,0,'2026-03-15 12:28:11','2026-03-15 12:28:11'),(76,2,'85tk Ring Math Khata',NULL,'STN53376',NULL,NULL,68.00,75.00,36,10,'piece',NULL,1,0,'2026-03-15 12:28:31','2026-03-15 12:28:31'),(77,1,'Linc Pen',NULL,'STN99535',NULL,NULL,12.00,15.00,9,3,'piece',NULL,1,0,'2026-03-15 12:28:47','2026-03-15 12:28:47'),(78,1,'Doms Cutter - Sharpener',NULL,'STN38537',NULL,NULL,3.25,5.00,18,5,'piece',NULL,1,0,'2026-03-15 12:29:06','2026-03-31 00:37:21'),(79,6,'Scale (Steel)',NULL,'STN82317',NULL,NULL,17.00,25.00,5,2,'piece',NULL,1,0,'2026-03-15 12:30:09','2026-03-26 01:56:43'),(80,3,'Punch File',NULL,'STN07526',NULL,NULL,11.67,17.00,12,4,'piece',NULL,1,0,'2026-03-15 12:30:33','2026-03-15 12:30:33'),(81,5,'Water Gum',NULL,'STN72970',NULL,NULL,15.00,25.00,1,2,'piece',NULL,1,0,'2026-03-15 12:30:55','2026-03-31 21:35:02'),(82,5,'A4 Envelop',NULL,'STN90311',NULL,NULL,2.20,5.00,20,5,'piece',NULL,1,0,'2026-03-15 12:31:22','2026-04-03 01:12:03'),(83,5,'Yellow Envelope',NULL,'STN68107',NULL,NULL,0.75,2.00,100,20,'piece',NULL,1,0,'2026-03-15 12:31:54','2026-03-15 12:31:54'),(84,5,'11/5 Khaki Envelope',NULL,'STN68992',NULL,NULL,0.85,3.00,99,20,'piece',NULL,1,0,'2026-03-15 12:32:23','2026-03-28 00:59:09'),(85,5,'Photo Envelope',NULL,'STN93029',NULL,NULL,0.18,0.50,250,50,'piece',NULL,1,0,'2026-03-15 12:39:03','2026-03-15 12:39:03'),(86,5,'Suta (সুতা) Small',NULL,'STN97148',NULL,NULL,2.44,5.00,8,3,'piece',NULL,1,0,'2026-03-15 12:39:24','2026-04-03 01:12:03'),(87,5,'Suta (সুতা) Big',NULL,'STN44661',NULL,NULL,17.50,20.00,2,1,'piece',NULL,1,0,'2026-03-15 12:39:49','2026-03-15 12:39:49'),(88,5,'Highlighter Pen',NULL,'STN52186',NULL,NULL,21.00,25.00,4,2,'piece',NULL,1,0,'2026-03-15 12:40:25','2026-03-31 00:37:21'),(89,5,'White board Marker',NULL,'STN18034',NULL,NULL,15.00,20.00,2,1,'piece',NULL,1,0,'2026-03-15 12:41:02','2026-03-15 12:41:02'),(90,5,'Permanent Marker',NULL,'STN17774',NULL,NULL,15.00,20.00,13,1,'piece',NULL,1,0,'2026-03-15 12:41:17','2026-03-31 05:19:56'),(91,5,'kangaro Pin Remover',NULL,'STN50531',NULL,NULL,55.00,65.00,2,1,'piece',NULL,1,0,'2026-03-15 12:41:33','2026-03-15 12:41:33'),(92,5,'Gold Pin Remover',NULL,'STN89563',NULL,NULL,42.00,50.00,2,1,'piece',NULL,1,0,'2026-03-15 12:42:14','2026-03-15 12:42:14'),(93,5,'32\" Paper Clip',NULL,'STN32157',NULL,NULL,3.33,5.00,4,2,'piece',NULL,1,0,'2026-03-15 12:42:40','2026-03-26 01:56:43'),(94,5,'41\" Paper Clip',NULL,'STN37061',NULL,NULL,5.50,10.00,4,2,'piece',NULL,1,0,'2026-03-15 12:43:04','2026-03-26 01:56:43'),(95,5,'Petra Punch Machine',NULL,'STN35876',NULL,NULL,115.00,130.00,2,1,'piece',NULL,1,0,'2026-03-15 12:43:29','2026-03-15 12:43:29'),(96,5,'kangaro Punch Machine',NULL,'STN81732',NULL,NULL,130.00,145.00,2,1,'piece',NULL,1,0,'2026-03-15 12:43:47','2026-03-15 12:43:52'),(97,1,'Push Pencil',NULL,'STN85931',NULL,NULL,8.50,12.00,11,3,'piece',NULL,1,0,'2026-03-15 12:44:12','2026-03-28 01:44:03'),(98,5,'Artline Pad',NULL,'STN07138',NULL,NULL,65.00,80.00,6,3,'piece',NULL,1,0,'2026-03-15 12:44:31','2026-03-15 12:44:31'),(99,6,'ABEL Transparent Scale',NULL,'STN88620',NULL,NULL,6.00,10.00,19,3,'piece',NULL,1,0,'2026-03-15 12:45:11','2026-03-30 20:51:23'),(100,5,'10 No Register',NULL,'STN50458',NULL,NULL,38.00,50.00,6,3,'piece',NULL,1,0,'2026-03-15 12:45:53','2026-03-15 12:45:53'),(101,5,'16 No Register',NULL,'STN63689',NULL,NULL,45.00,60.00,6,3,'piece',NULL,1,0,'2026-03-15 12:46:11','2026-03-15 12:46:11'),(102,5,'24 No Register',NULL,'STN87458',NULL,NULL,65.00,75.00,2,1,'piece',NULL,1,0,'2026-03-15 12:46:29','2026-04-01 01:17:56'),(103,5,'Sticky Note',NULL,'STN47239',NULL,NULL,25.00,35.00,9,1,'piece',NULL,1,0,'2026-03-15 12:46:47','2026-04-02 17:24:00'),(104,1,'Good Luck Craze Ball Pen',NULL,'STN71272',NULL,NULL,8.00,10.00,4,2,'piece',NULL,1,0,'2026-03-15 12:47:49','2026-04-04 05:02:20'),(105,1,'Matador i-teen Premium Ballpen',NULL,'STN65713',NULL,NULL,8.00,10.00,4,2,'piece',NULL,1,0,'2026-03-15 12:48:33','2026-03-27 18:16:27'),(106,1,'Matador smothy Premium Ballpen with oil gel ink',NULL,'STN79035',NULL,NULL,8.00,10.00,4,2,'piece',NULL,1,0,'2026-03-15 12:49:10','2026-04-02 01:05:57'),(107,5,'ID Card Holder',NULL,'STN48559',NULL,NULL,7.30,10.00,10,2,'piece',NULL,1,0,'2026-03-15 12:49:39','2026-03-15 12:49:39'),(108,5,'Scientific Calculator 100',NULL,'STN19725',NULL,NULL,200.00,300.00,2,1,'piece',NULL,1,0,'2026-03-15 12:50:06','2026-03-15 12:50:06'),(109,5,'Mega Calculator',NULL,'STN86732',NULL,NULL,450.00,550.00,2,1,'piece',NULL,1,0,'2026-03-15 12:50:24','2026-03-15 12:50:24'),(110,5,'Gitizen Calculator',NULL,'STN48128',NULL,NULL,280.00,350.00,1,1,'piece',NULL,1,0,'2026-03-15 12:50:40','2026-03-27 18:16:27'),(111,5,'Furoni (ফুড়োনি)',NULL,'STN11809',NULL,NULL,5.42,10.00,11,3,'piece',NULL,1,0,'2026-03-15 12:51:02','2026-04-03 01:12:03'),(112,5,'Fevicol Aica Gum 125gm',NULL,'STN70850',NULL,NULL,60.00,70.00,3,1,'piece',NULL,1,0,'2026-03-15 12:51:18','2026-04-02 06:15:37'),(113,5,'Sign Pen',NULL,'STN10182',NULL,NULL,3.57,10.00,26,5,'piece',NULL,1,0,'2026-03-15 12:51:41','2026-03-23 19:58:25'),(114,1,'Matador Aqua gel',NULL,'STN65357',NULL,NULL,0.00,12.00,4,0,'piece',NULL,1,0,'2026-03-15 13:15:30','2026-03-15 13:15:30'),(115,8,'Matador Tiktok masala Candy',NULL,'STN10916',NULL,NULL,1.50,2.00,100,20,'piece',NULL,1,0,'2026-03-16 08:09:13','2026-04-04 05:02:20'),(116,8,'Matador Pop-out Mango Candy',NULL,'STN97362',NULL,NULL,1.33,2.00,112,20,'piece',NULL,1,0,'2026-03-16 08:09:50','2026-04-03 01:12:03'),(117,7,'Photo to Photo',NULL,'STN66649',NULL,NULL,1.00,10.00,9994,20,'piece',NULL,1,0,'2026-03-16 08:44:28','2026-03-31 03:19:17'),(118,7,'Photo to Photo 4 Copies',NULL,'STN12931',NULL,NULL,4.00,30.00,9980,20,'piece',NULL,1,0,'2026-03-16 08:45:40','2026-04-01 01:14:24'),(119,5,'Stamp Pad Ink',NULL,'STN94419',NULL,NULL,80.00,100.00,3,1,'piece',NULL,1,0,'2026-03-16 10:25:04','2026-03-16 10:25:04'),(120,1,'Hauser Darkies Extra Dark Pencil',NULL,'STN92553',NULL,'This is a test description',7.00,12.00,0,3,'piece',NULL,1,0,'2026-03-16 10:26:25','2026-03-17 08:06:12'),(121,5,'Wastage Bin / Busket Multipurpose - Small','RFL','STN76229',NULL,NULL,60.00,90.00,6,2,'piece',NULL,1,0,'2026-03-17 20:26:32','2026-03-29 18:19:41'),(122,1,'Rabbit Sharpener','Good Luck','STN62943',NULL,NULL,10.00,15.00,18,5,'piece',NULL,1,0,'2026-03-17 20:32:04','2026-03-17 20:32:04'),(123,1,'Stylo Sharpener','Good Luck','STN77283',NULL,NULL,6.00,10.00,26,5,'piece',NULL,1,0,'2026-03-17 20:33:57','2026-04-04 05:02:20'),(124,1,'Color Pencil - Big','Good Luck','STN67052',NULL,NULL,90.00,130.00,2,1,'piece',NULL,1,0,'2026-03-17 20:36:20','2026-03-26 05:19:53'),(125,1,'Color Pencil - Small','Good Luck','STN49042',NULL,NULL,65.00,105.00,3,1,'piece',NULL,1,0,'2026-03-17 20:37:12','2026-03-17 20:37:12'),(126,8,'Toothbrush - Adult','RFL','STN23165',NULL,NULL,19.00,40.00,24,5,'piece',NULL,1,0,'2026-03-17 20:40:15','2026-03-17 20:40:32'),(127,8,'Toothbrush - Kids','RFL','STN57201',NULL,NULL,20.00,40.00,12,4,'piece',NULL,1,0,'2026-03-17 20:40:58','2026-03-17 20:40:58'),(128,7,'Computer Compose',NULL,'STN13116',NULL,NULL,3.00,25.00,9997,20,'piece',NULL,1,0,'2026-03-20 23:54:56','2026-04-01 03:25:50'),(129,2,'Graph Paper','Others','STN56556',NULL,NULL,3.75,5.00,46,10,'piece',NULL,1,0,'2026-03-25 23:29:32','2026-03-28 00:59:09'),(130,5,'Gum Stick (Small)','Others','STN77478',NULL,NULL,5.50,10.00,11,5,'piece',NULL,1,0,'2026-03-25 23:31:13','2026-04-04 17:22:25'),(131,5,'Gum Stick (Big)','Others','STN20997',NULL,NULL,13.00,20.00,12,5,'piece',NULL,1,0,'2026-03-25 23:31:40','2026-03-25 23:31:40'),(132,5,'5\'\' Scissor','Others','STN18054',NULL,NULL,25.00,30.00,4,1,'piece',NULL,1,0,'2026-03-25 23:32:51','2026-04-02 06:22:25'),(133,5,'7\'\' Scissor','Others','STN97631',NULL,NULL,28.00,45.00,2,1,'piece',NULL,1,0,'2026-03-25 23:33:28','2026-03-29 18:53:20'),(134,1,'Mechanical Pencil','Others','STN62202',NULL,NULL,37.00,55.00,6,2,'piece',NULL,1,0,'2026-03-25 23:34:15','2026-03-25 23:34:15'),(135,1,'Mechanical Pencil Refill','Others','STN43532',NULL,NULL,15.00,20.00,6,2,'piece',NULL,1,0,'2026-03-25 23:34:51','2026-03-25 23:34:51'),(136,5,'Anti Cutter','Others','STN39083',NULL,NULL,20.00,30.00,3,1,'piece',NULL,1,0,'2026-03-25 23:36:14','2026-03-25 23:37:42'),(137,5,'Anti Cutter Blade (Small)','Others','STN75490',NULL,NULL,3.50,5.00,10,2,'piece',NULL,1,0,'2026-03-25 23:37:35','2026-03-25 23:37:35'),(138,5,'Anti Cutter Blade (Big)','Others','STN72032',NULL,NULL,7.50,10.00,10,2,'piece',NULL,1,0,'2026-03-25 23:38:32','2026-03-25 23:38:32'),(139,5,'Fevicol Glue Stick ','Others','STN04586',NULL,NULL,20.00,25.00,6,2,'piece',NULL,1,0,'2026-03-25 23:42:29','2026-03-31 17:56:11'),(140,5,'Mondete Stapler M-203S','Others','STN04993',NULL,NULL,40.00,50.00,5,2,'piece',NULL,1,0,'2026-03-25 23:43:47','2026-04-03 18:22:59'),(141,5,'STARLER Stapler HL-203','Others','STN56642',NULL,NULL,32.00,45.00,5,2,'piece',NULL,1,0,'2026-03-25 23:44:27','2026-04-03 18:21:37'),(142,8,'Naruto Card','Others','STN57352',NULL,NULL,14.00,20.00,11,2,'piece',NULL,1,0,'2026-03-25 23:46:19','2026-03-31 00:37:21'),(143,5,'Kippy Gum','Others','STN34035',NULL,NULL,10.00,15.00,13,1,'piece',NULL,1,0,'2026-03-25 23:47:09','2026-03-31 05:22:20'),(144,2,'Art Paper','Others','STN40838',NULL,NULL,7.50,10.00,10,3,'piece',NULL,1,0,'2026-03-25 23:49:09','2026-03-25 23:49:09'),(145,2,'Kids Math Khata 160 Page','Others','STN21317',NULL,NULL,27.00,35.00,10,3,'piece',NULL,1,0,'2026-03-26 01:58:39','2026-03-31 00:18:56'),(146,2,'Kids Math Khata 124 Page','Basundhara','STN63877',NULL,NULL,25.00,30.00,12,3,'piece',NULL,1,0,'2026-03-31 00:19:54','2026-03-31 00:28:02'),(147,5,'Toilet Tissue White','Basundhara','STN48440',NULL,NULL,17.00,25.00,12,3,'piece',NULL,1,0,'2026-03-31 00:21:11','2026-03-31 00:21:11'),(148,5,'Toilet Tissue Gold','Basundhara','STN51565',NULL,NULL,24.50,35.00,12,3,'piece',NULL,1,0,'2026-03-31 00:22:06','2026-03-31 00:22:06'),(149,5,'100 Box Tissue','Basundhara','STN64321',NULL,NULL,56.50,65.00,12,3,'piece',NULL,1,0,'2026-03-31 00:23:52','2026-03-31 00:23:52'),(150,5,'Pocket Tissue','Basundhara','STN41191',NULL,NULL,7.50,10.00,22,6,'piece',NULL,1,0,'2026-03-31 00:25:14','2026-03-31 17:23:27'),(151,5,'Paper Napkin Tissue','Basundhara','STN97928',NULL,NULL,50.00,65.00,6,2,'piece',NULL,1,0,'2026-03-31 00:27:02','2026-03-31 00:27:02'),(152,2,'Kids English Khata 124 Page','Basundhara','STN64303',NULL,NULL,25.00,30.00,12,3,'piece',NULL,1,0,'2026-03-31 00:28:23','2026-03-31 00:28:23'),(153,5,'Hand Towel Tissue','Basundhara','STN20881',NULL,NULL,33.00,45.00,3,1,'piece',NULL,1,0,'2026-03-31 00:29:58','2026-03-31 00:29:58'),(154,2,'55 Top math Khata','Others','STN19083',NULL,NULL,42.00,50.00,31,5,'piece',NULL,1,0,'2026-03-31 03:16:21','2026-03-31 03:19:17'),(155,5,'Matador Highlighter Pen','Matador','STN37025',NULL,NULL,25.00,35.00,6,2,'piece',NULL,1,0,'2026-03-31 05:29:48','2026-04-02 17:24:00'),(156,5,'Bundle Steel Tag','Other','STN29953',NULL,NULL,45.00,55.00,5,2,'piece',NULL,1,0,'2026-03-31 05:33:32','2026-04-02 17:24:00'),(157,5,'Double A Legal Paper','Dauble A','STN63434',NULL,NULL,630.00,650.00,2,1,'piece',NULL,1,0,'2026-03-31 05:38:27','2026-04-02 17:24:00'),(158,5,'Double A A4 Paper','Dauble A','STN71906',NULL,NULL,484.00,530.00,2,1,'piece',NULL,1,0,'2026-03-31 05:39:22','2026-04-02 17:24:00'),(159,4,'Water Color Pencils','Petra','STN81849',NULL,NULL,165.00,180.00,1,1,'piece',NULL,1,0,'2026-04-01 01:20:52','2026-04-01 01:21:23'),(160,5,'Fevicol Aica Gum Tube 50gm','Pidilite','STN74840',NULL,NULL,33.00,45.00,8,2,'piece',NULL,1,0,'2026-04-02 06:17:18','2026-04-02 06:17:18'),(161,5,'Whiteboard Marker Refill Ink 100ml','Others','STN21699',NULL,NULL,95.00,120.00,2,1,'piece',NULL,1,0,'2026-04-02 06:18:37','2026-04-02 06:18:37'),(162,5,'Whiteboard Marker Refill Ink 70ml','Others','STN30153',NULL,NULL,65.00,75.00,2,1,'piece',NULL,1,0,'2026-04-02 06:20:32','2026-04-02 06:20:32'),(163,5,'7\'\' Scissor DL3158','Others','STN35775',NULL,NULL,70.00,80.00,2,1,'piece',NULL,1,0,'2026-04-02 06:23:58','2026-04-02 06:23:58'),(164,5,'8(1/4)\'\' Scissor GuangBo','Others','STN37541',NULL,NULL,120.00,140.00,2,1,'piece',NULL,1,0,'2026-04-02 06:25:27','2026-04-02 06:25:27'),(165,5,'Foil Paper','Others','STN62900',NULL,NULL,5.60,10.00,25,5,'set',NULL,1,0,'2026-04-02 06:29:25','2026-04-02 06:29:25'),(166,5,'Dollar Whiteboard Marker ','Dollar','STN33854',NULL,NULL,35.00,40.00,13,4,'piece',NULL,1,0,'2026-04-02 06:31:40','2026-04-02 06:31:40'),(167,5,'Whiteboard Eraser/Duster ','Others','STN43074',NULL,NULL,15.00,20.00,6,2,'piece',NULL,1,0,'2026-04-02 06:32:33','2026-04-02 06:32:33'),(168,5,'Flax','Others','STN72748',NULL,NULL,550.00,750.00,1,1,'piece',NULL,1,0,'2026-04-02 16:57:20','2026-04-02 16:59:41'),(169,5,'Electric Kettle','Others','STN44953',NULL,NULL,850.00,1100.00,2,1,'piece',NULL,1,0,'2026-04-02 16:58:54','2026-04-02 17:39:10'),(170,2,'55gsm Basundhara Rim Dista Paper (500Page)',NULL,'STN46113',NULL,NULL,450.00,550.00,2,1,'piece',NULL,1,0,'2026-04-03 03:54:18','2026-04-03 05:11:44'),(171,5,'kangaro Stapler Pin 10','kangaro','STN42325',NULL,NULL,10.25,15.00,20,5,'piece',NULL,1,0,'2026-04-03 18:26:49','2026-04-03 18:26:49');
/*!40000 ALTER TABLE `products` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `purchase_items`
--

DROP TABLE IF EXISTS `purchase_items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `purchase_items` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `purchase_id` bigint(20) unsigned NOT NULL,
  `product_id` bigint(20) unsigned NOT NULL,
  `product_name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `cost_price` decimal(10,2) NOT NULL,
  `quantity` int(11) NOT NULL,
  `total` decimal(12,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `purchase_items_purchase_id_foreign` (`purchase_id`),
  KEY `purchase_items_product_id_foreign` (`product_id`),
  CONSTRAINT `purchase_items_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`),
  CONSTRAINT `purchase_items_purchase_id_foreign` FOREIGN KEY (`purchase_id`) REFERENCES `purchases` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `purchase_items`
--

LOCK TABLES `purchase_items` WRITE;
/*!40000 ALTER TABLE `purchase_items` DISABLE KEYS */;
/*!40000 ALTER TABLE `purchase_items` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `purchases`
--

DROP TABLE IF EXISTS `purchases`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `purchases` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `reference_number` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `supplier_id` bigint(20) unsigned NOT NULL,
  `user_id` bigint(20) unsigned NOT NULL,
  `purchase_date` date NOT NULL,
  `subtotal` decimal(12,2) NOT NULL DEFAULT '0.00',
  `discount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `tax` decimal(12,2) NOT NULL DEFAULT '0.00',
  `total` decimal(12,2) NOT NULL DEFAULT '0.00',
  `paid_amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `payment_method` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'cash',
  `status` enum('ordered','received','cancelled') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'ordered',
  `notes` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `purchases_reference_number_unique` (`reference_number`),
  KEY `purchases_supplier_id_foreign` (`supplier_id`),
  KEY `purchases_user_id_foreign` (`user_id`),
  CONSTRAINT `purchases_supplier_id_foreign` FOREIGN KEY (`supplier_id`) REFERENCES `suppliers` (`id`),
  CONSTRAINT `purchases_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `purchases`
--

LOCK TABLES `purchases` WRITE;
/*!40000 ALTER TABLE `purchases` DISABLE KEYS */;
/*!40000 ALTER TABLE `purchases` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sale_items`
--

DROP TABLE IF EXISTS `sale_items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sale_items` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `sale_id` bigint(20) unsigned NOT NULL,
  `product_id` bigint(20) unsigned NOT NULL,
  `product_name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `unit_price` decimal(10,2) NOT NULL,
  `custom_price` decimal(10,2) DEFAULT NULL,
  `cost_price` decimal(10,2) NOT NULL,
  `quantity` int(11) NOT NULL,
  `discount` decimal(10,2) NOT NULL DEFAULT '0.00',
  `total` decimal(12,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `sale_items_sale_id_foreign` (`sale_id`),
  KEY `sale_items_product_id_foreign` (`product_id`),
  CONSTRAINT `sale_items_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  CONSTRAINT `sale_items_sale_id_foreign` FOREIGN KEY (`sale_id`) REFERENCES `sales` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=217 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sale_items`
--

LOCK TABLES `sale_items` WRITE;
/*!40000 ALTER TABLE `sale_items` DISABLE KEYS */;
INSERT INTO `sale_items` VALUES (1,1,27,'BW Print',10.00,NULL,1.00,2,0.00,20.00,'2026-03-14 13:54:42','2026-03-14 13:54:42'),(3,3,30,'Matador All-time',6.00,NULL,5.00,3,0.00,18.00,'2026-03-15 12:53:49','2026-03-15 12:53:49'),(4,4,113,'Sign Pen',10.00,NULL,3.57,1,0.00,10.00,'2026-03-15 12:54:20','2026-03-15 12:54:20'),(5,5,74,'300p Math Khata (News)',50.00,NULL,41.00,1,0.00,50.00,'2026-03-15 12:54:51','2026-03-15 12:54:51'),(6,6,60,'Math Khata (84p)',35.00,NULL,27.08,1,0.00,35.00,'2026-03-15 12:57:24','2026-03-15 12:57:24'),(7,6,32,'Matador Pin-Point Black',5.00,NULL,4.50,1,0.00,5.00,'2026-03-15 12:57:24','2026-03-15 12:57:24'),(8,7,32,'Matador Pin-Point Black',5.00,NULL,4.50,9,0.00,45.00,'2026-03-15 12:57:47','2026-03-15 12:57:47'),(9,8,45,'Matador All-time Scale (30cm)',15.00,NULL,11.25,8,0.00,120.00,'2026-03-15 12:58:34','2026-03-15 12:58:34'),(10,9,32,'Matador Pin-Point Black',5.00,NULL,4.50,2,0.00,10.00,'2026-03-15 12:59:33','2026-03-15 12:59:33'),(11,10,82,'A4 Envelop',5.00,NULL,2.20,4,0.00,20.00,'2026-03-15 13:00:15','2026-03-15 13:00:15'),(12,11,31,'Matador Hi-School Black',5.00,NULL,4.50,2,0.00,10.00,'2026-03-15 13:07:23','2026-03-15 13:07:23'),(13,12,31,'Matador Hi-School Black',5.00,NULL,4.50,1,0.00,5.00,'2026-03-16 08:10:42','2026-03-16 08:10:42'),(14,12,29,'Photocopy',3.00,NULL,1.00,1,0.00,3.00,'2026-03-16 08:10:42','2026-03-16 08:10:42'),(15,12,115,'Matador Tiktok masala Candy',2.00,NULL,1.50,1,0.00,2.00,'2026-03-16 08:10:42','2026-03-16 08:10:42'),(16,13,118,'Photo to Photo 4 Copies',30.00,NULL,4.00,5,0.00,150.00,'2026-03-16 08:46:35','2026-03-16 08:46:35'),(17,13,31,'Matador Hi-School Black',5.00,NULL,4.50,1,0.00,5.00,'2026-03-16 08:46:35','2026-03-16 08:46:35'),(18,13,29,'Photocopy',3.00,NULL,1.00,4,0.00,12.00,'2026-03-16 08:46:35','2026-03-16 08:46:35'),(19,14,118,'Photo to Photo 4 Copies',30.00,NULL,4.00,1,0.00,30.00,'2026-03-16 08:47:12','2026-03-16 08:47:12'),(20,14,29,'Photocopy',3.00,NULL,1.00,4,0.00,12.00,'2026-03-16 08:47:12','2026-03-16 08:47:12'),(21,14,32,'Matador Pin-Point Black',5.00,NULL,4.50,1,0.00,5.00,'2026-03-16 08:47:12','2026-03-16 08:47:12'),(22,15,118,'Photo to Photo 4 Copies',30.00,NULL,4.00,1,0.00,30.00,'2026-03-16 09:59:33','2026-03-16 09:59:33'),(23,15,29,'Photocopy',3.00,NULL,1.00,2,0.00,6.00,'2026-03-16 09:59:33','2026-03-16 09:59:33'),(24,16,29,'Photocopy',3.00,NULL,1.00,2,0.00,6.00,'2026-03-16 09:59:58','2026-03-16 09:59:58'),(25,17,29,'Photocopy',3.00,NULL,1.00,4,0.00,12.00,'2026-03-16 10:34:37','2026-03-16 10:34:37'),(26,18,29,'Photocopy',3.00,NULL,1.00,20,0.00,60.00,'2026-03-16 10:35:32','2026-03-16 10:35:32'),(27,19,43,'Matador Pencil Box (small)',40.00,NULL,30.00,2,0.00,80.00,'2026-03-16 13:10:02','2026-03-16 13:10:02'),(28,19,120,'Hauser Darkies Extra Dark Pencil',12.00,NULL,7.00,10,0.00,120.00,'2026-03-16 13:10:02','2026-03-16 13:10:02'),(29,19,40,'Matador Pluto Pencil 2B',10.00,NULL,4.58,12,0.00,120.00,'2026-03-16 13:10:02','2026-03-16 13:10:02'),(30,20,29,'Photocopy',3.00,NULL,1.00,5,0.00,15.00,'2026-03-16 15:08:16','2026-03-16 15:08:16'),(31,21,32,'Matador Pin-Point Black',5.00,NULL,4.50,4,0.00,20.00,'2026-03-18 01:30:01','2026-03-18 01:30:01'),(32,22,128,'Computer Compose',25.00,NULL,3.00,2,0.00,50.00,'2026-03-20 23:55:25','2026-03-20 23:55:25'),(33,23,104,'Good Luck Craze Ball Pen',10.00,NULL,8.00,1,0.00,10.00,'2026-03-22 18:52:47','2026-03-22 18:52:47'),(34,23,106,'Matador smothy Premium Ballpen with oil gel ink',10.00,NULL,8.00,1,0.00,10.00,'2026-03-22 18:52:47','2026-03-22 18:52:47'),(35,24,28,'Color Print',20.00,NULL,1.00,4,0.00,80.00,'2026-03-22 18:53:22','2026-03-22 18:53:22'),(36,25,31,'Matador Hi-School Black',5.00,NULL,4.50,2,0.00,10.00,'2026-03-22 20:21:17','2026-03-22 20:21:17'),(37,26,123,'Stylo Sharpener',10.00,NULL,6.00,1,0.00,10.00,'2026-03-23 18:38:13','2026-03-23 18:38:13'),(38,27,113,'Sign Pen',10.00,NULL,3.57,1,0.00,10.00,'2026-03-23 19:58:25','2026-03-23 19:58:25'),(39,28,27,'BW Print',10.00,NULL,1.00,4,0.00,40.00,'2026-03-23 20:22:09','2026-03-23 20:22:09'),(40,28,118,'Photo to Photo 4 Copies',30.00,NULL,4.00,3,0.00,90.00,'2026-03-23 20:22:09','2026-03-23 20:22:09'),(41,28,29,'Photocopy',3.00,NULL,1.00,2,0.00,6.00,'2026-03-23 20:22:09','2026-03-23 20:22:09'),(42,29,105,'Matador i-teen Premium Ballpen',10.00,NULL,8.00,1,0.00,10.00,'2026-03-23 23:54:56','2026-03-23 23:54:56'),(43,30,27,'BW Print',10.00,NULL,1.00,3,0.00,30.00,'2026-03-24 01:47:03','2026-03-24 01:47:03'),(44,31,29,'Photocopy',3.00,NULL,1.00,12,0.00,36.00,'2026-03-24 22:18:33','2026-03-24 22:18:33'),(45,32,60,'Math Khata (84p)',35.00,NULL,27.08,1,0.00,35.00,'2026-03-25 03:48:50','2026-03-25 03:48:50'),(46,32,70,'84p Pencil Khata',30.00,NULL,25.00,1,0.00,30.00,'2026-03-25 03:48:50','2026-03-25 03:48:50'),(47,32,32,'Matador Pin-Point Black',5.00,NULL,4.50,1,0.00,5.00,'2026-03-25 03:48:50','2026-03-25 03:48:50'),(48,32,40,'Matador Pluto Pencil 2B',10.00,NULL,4.58,1,0.00,10.00,'2026-03-25 03:48:50','2026-03-25 03:48:50'),(49,33,29,'Photocopy',3.00,NULL,1.00,10,0.00,30.00,'2026-03-25 05:29:26','2026-03-25 05:29:26'),(50,34,32,'Matador Pin-Point Black',5.00,NULL,4.50,6,0.00,30.00,'2026-03-25 05:30:10','2026-03-25 05:30:10'),(51,34,27,'BW Print',10.00,NULL,1.00,6,0.00,60.00,'2026-03-25 05:30:10','2026-03-25 05:30:10'),(52,35,27,'BW Print',10.00,NULL,1.00,2,0.00,20.00,'2026-03-25 05:31:07','2026-03-25 05:31:07'),(53,36,49,'Matador Officemate Correction Pen',40.00,NULL,30.00,1,0.00,40.00,'2026-03-25 17:13:55','2026-03-25 17:13:55'),(54,37,29,'Photocopy',3.00,NULL,1.00,24,0.00,72.00,'2026-03-25 22:34:57','2026-03-25 22:34:57'),(55,38,29,'Photocopy',3.00,NULL,1.00,5,0.00,15.00,'2026-03-25 22:35:44','2026-03-25 22:35:44'),(56,38,118,'Photo to Photo 4 Copies',30.00,NULL,4.00,1,0.00,30.00,'2026-03-25 22:35:44','2026-03-25 22:35:44'),(57,38,117,'Photo to Photo',10.00,NULL,1.00,2,0.00,20.00,'2026-03-25 22:35:44','2026-03-25 22:35:44'),(58,39,29,'Photocopy',3.00,NULL,1.00,2,0.00,6.00,'2026-03-25 22:36:00','2026-03-25 22:36:00'),(59,40,27,'BW Print',10.00,NULL,1.00,8,0.00,80.00,'2026-03-25 23:28:41','2026-03-25 23:28:41'),(60,41,67,'200p Pencil Khata',100.00,NULL,85.00,1,0.00,100.00,'2026-03-26 01:26:24','2026-03-26 01:26:24'),(61,42,27,'BW Print',10.00,NULL,1.00,4,0.00,40.00,'2026-03-26 01:28:44','2026-03-26 01:28:44'),(62,43,32,'Matador Pin-Point Black',5.00,NULL,4.50,1,0.00,5.00,'2026-03-26 01:53:44','2026-03-26 01:53:44'),(63,44,79,'Scale (Steel)',25.00,NULL,17.00,1,0.00,25.00,'2026-03-26 01:56:43','2026-03-26 01:56:43'),(64,44,90,'Permanent Marker',20.00,NULL,15.00,2,0.00,40.00,'2026-03-26 01:56:43','2026-03-26 01:56:43'),(65,44,66,'kangaro Stapler',160.00,NULL,140.00,1,0.00,160.00,'2026-03-26 01:56:43','2026-03-26 01:56:43'),(66,44,63,'kangaro Stapler Pin',25.00,NULL,21.00,1,0.00,25.00,'2026-03-26 01:56:43','2026-03-26 01:56:43'),(67,44,81,'Water Gum',25.00,NULL,15.00,1,0.00,25.00,'2026-03-26 01:56:43','2026-03-26 01:56:43'),(68,44,94,'41\" Paper Clip',10.00,NULL,5.50,2,0.00,20.00,'2026-03-26 01:56:43','2026-03-26 01:56:43'),(69,44,93,'32\" Paper Clip',5.00,NULL,3.33,2,0.00,10.00,'2026-03-26 01:56:43','2026-03-26 01:56:43'),(70,45,145,'Math Khata Half 160 Page',35.00,NULL,28.00,1,0.00,35.00,'2026-03-26 01:59:47','2026-03-26 01:59:47'),(71,46,40,'Matador Pluto Pencil 2B',10.00,NULL,4.58,1,0.00,10.00,'2026-03-26 03:58:47','2026-03-26 03:58:47'),(72,46,32,'Matador Pin-Point Black',5.00,NULL,4.50,4,0.00,20.00,'2026-03-26 03:58:47','2026-03-26 03:58:47'),(73,46,78,'Doms Cutter - Sharpener',5.00,NULL,3.25,1,0.00,5.00,'2026-03-26 03:58:47','2026-03-26 03:58:47'),(74,46,70,'84p Pencil Khata',30.00,NULL,25.00,1,0.00,30.00,'2026-03-26 03:58:47','2026-03-26 03:58:47'),(75,47,69,'55gsm Basundhara Rim Dista Paper',1.50,NULL,0.90,250,0.00,375.00,'2026-03-26 04:05:31','2026-03-26 04:05:31'),(76,48,29,'Photocopy',3.00,NULL,1.00,4,0.00,12.00,'2026-03-26 04:06:23','2026-03-26 04:06:23'),(77,49,27,'BW Print',10.00,NULL,1.00,2,0.00,20.00,'2026-03-26 05:17:10','2026-03-26 05:17:10'),(78,50,73,'Drawing Khata',25.00,NULL,17.00,2,0.00,50.00,'2026-03-26 05:19:53','2026-03-26 05:19:53'),(79,50,145,'Math Khata Half 160 Page',35.00,NULL,27.00,1,0.00,35.00,'2026-03-26 05:19:53','2026-03-26 05:19:53'),(80,50,124,'Color Pencil - Big',130.00,NULL,90.00,1,0.00,130.00,'2026-03-26 05:19:53','2026-03-26 05:19:53'),(81,50,30,'Matador All-time',6.00,NULL,5.00,5,0.00,30.00,'2026-03-26 05:19:53','2026-03-26 05:19:53'),(82,51,56,'Wiring Tape',20.00,NULL,11.43,2,0.00,40.00,'2026-03-26 17:29:40','2026-03-26 17:29:40'),(83,52,35,'Matador Pencilic Black',5.00,NULL,4.00,1,0.00,5.00,'2026-03-26 17:42:19','2026-03-26 17:42:19'),(84,52,70,'84p Pencil Khata',30.00,NULL,25.00,1,0.00,30.00,'2026-03-26 17:42:19','2026-03-26 17:42:19'),(85,52,116,'Matador Pop-out Mango Candy',2.00,NULL,1.33,3,0.00,6.00,'2026-03-26 17:42:19','2026-03-26 17:42:19'),(86,53,28,'Color Print',20.00,NULL,1.00,2,0.00,40.00,'2026-03-26 17:44:19','2026-03-26 17:44:19'),(87,53,27,'BW Print',10.00,NULL,1.00,1,0.00,10.00,'2026-03-26 17:44:19','2026-03-26 17:44:19'),(88,54,29,'Photocopy',3.00,NULL,1.00,2,0.00,6.00,'2026-03-26 18:58:18','2026-03-26 18:58:18'),(89,55,110,'Gitizen Calculator',350.00,NULL,280.00,1,0.00,350.00,'2026-03-27 18:16:27','2026-03-27 18:16:27'),(90,55,27,'BW Print',10.00,NULL,1.00,3,0.00,30.00,'2026-03-27 18:16:27','2026-03-27 18:16:27'),(91,55,105,'Matador i-teen Premium Ballpen',10.00,NULL,8.00,1,0.00,10.00,'2026-03-27 18:16:27','2026-03-27 18:16:27'),(92,55,115,'Matador Tiktok masala Candy',2.00,NULL,1.50,5,0.00,10.00,'2026-03-27 18:16:27','2026-03-27 18:16:27'),(93,55,29,'Photocopy',3.00,NULL,1.00,2,0.00,6.00,'2026-03-27 18:16:27','2026-03-27 18:16:27'),(94,56,27,'BW Print',10.00,NULL,1.00,13,0.00,130.00,'2026-03-27 19:08:43','2026-03-27 19:08:43'),(95,57,84,'11/5 Khaki Envelope',3.00,NULL,0.85,1,0.00,3.00,'2026-03-28 00:59:09','2026-03-28 00:59:09'),(96,57,129,'Graph Paper',5.00,NULL,3.75,2,0.00,10.00,'2026-03-28 00:59:09','2026-03-28 00:59:09'),(97,57,38,'Matador I-teen Gel Blue',15.00,NULL,10.00,1,0.00,15.00,'2026-03-28 00:59:09','2026-03-28 00:59:09'),(98,58,97,'Push Pencil',12.00,NULL,8.50,1,0.00,12.00,'2026-03-28 01:44:03','2026-03-28 01:44:03'),(99,59,57,'Cartoon Tape',40.00,NULL,30.00,1,0.00,40.00,'2026-03-29 02:50:23','2026-03-29 02:50:23'),(100,59,29,'Photocopy',3.00,NULL,1.00,5,0.00,15.00,'2026-03-29 02:50:23','2026-03-29 02:50:23'),(101,59,27,'BW Print',10.00,NULL,1.00,5,0.00,50.00,'2026-03-29 02:50:23','2026-03-29 02:50:23'),(102,60,58,'Super Glue',10.00,NULL,5.83,3,0.00,30.00,'2026-03-29 17:22:16','2026-03-29 17:22:16'),(103,61,29,'Photocopy',3.00,NULL,1.00,4,0.00,12.00,'2026-03-29 18:50:46','2026-03-29 18:50:46'),(104,62,70,'84p Pencil Khata',30.00,NULL,25.00,1,0.00,30.00,'2026-03-29 18:53:20','2026-03-29 18:53:20'),(105,62,115,'Matador Tiktok masala Candy',2.00,NULL,1.50,5,0.00,10.00,'2026-03-29 18:53:20','2026-03-29 18:53:20'),(106,62,133,'7\'\' Scissor',45.00,NULL,28.00,1,0.00,45.00,'2026-03-29 18:53:20','2026-03-29 18:53:20'),(107,63,60,'Math Khata (84p)',30.00,NULL,27.08,1,0.00,30.00,'2026-03-30 00:22:39','2026-03-30 00:22:39'),(108,63,29,'Photocopy',3.00,NULL,1.00,19,0.00,57.00,'2026-03-30 00:22:39','2026-03-30 00:22:39'),(109,64,29,'Photocopy',3.00,NULL,1.00,5,0.00,15.00,'2026-03-30 00:24:07','2026-03-30 00:24:07'),(110,65,67,'200p Pencil Khata',100.00,NULL,85.00,5,0.00,500.00,'2026-03-30 01:07:56','2026-03-30 01:07:56'),(111,65,32,'Matador Pin-Point Black',5.00,NULL,4.50,7,0.00,35.00,'2026-03-30 01:07:56','2026-03-30 01:07:56'),(112,66,56,'Wiring Tape',20.00,NULL,11.43,1,0.00,20.00,'2026-03-30 01:10:01','2026-03-30 01:10:01'),(113,67,32,'Matador Pin-Point Black',5.00,NULL,4.50,2,0.00,10.00,'2026-03-30 01:40:23','2026-03-30 01:40:23'),(114,68,115,'Matador Tiktok masala Candy',2.00,NULL,1.50,1,0.00,2.00,'2026-03-30 04:25:38','2026-03-30 04:25:38'),(115,68,28,'Color Print',20.00,NULL,1.00,2,0.00,40.00,'2026-03-30 04:25:38','2026-03-30 04:25:38'),(116,69,27,'BW Print',10.00,NULL,1.00,2,0.00,20.00,'2026-03-30 05:32:57','2026-03-30 05:32:57'),(117,70,27,'BW Print',10.00,NULL,1.00,41,0.00,410.00,'2026-03-30 20:51:23','2026-03-30 20:51:23'),(118,70,29,'Photocopy',3.00,NULL,1.00,37,0.00,111.00,'2026-03-30 20:51:23','2026-03-30 20:51:23'),(119,70,99,'ABEL Transparent Scale',10.00,NULL,6.00,1,0.00,10.00,'2026-03-30 20:51:23','2026-03-30 20:51:23'),(120,70,64,'Sunlite AA Battery',20.00,NULL,17.50,1,0.00,20.00,'2026-03-30 20:51:23','2026-03-30 20:51:23'),(121,71,29,'Photocopy',3.00,NULL,1.00,29,0.00,87.00,'2026-03-31 00:37:21','2026-03-31 00:37:21'),(122,71,88,'Highlighter Pen',25.00,NULL,21.00,1,0.00,25.00,'2026-03-31 00:37:21','2026-03-31 00:37:21'),(123,71,118,'Photo to Photo 4 Copies',30.00,NULL,4.00,3,0.00,90.00,'2026-03-31 00:37:21','2026-03-31 00:37:21'),(124,71,117,'Photo to Photo',10.00,NULL,1.00,2,0.00,20.00,'2026-03-31 00:37:21','2026-03-31 00:37:21'),(125,71,32,'Matador Pin-Point Black',5.00,NULL,4.50,1,0.00,5.00,'2026-03-31 00:37:21','2026-03-31 00:37:21'),(126,71,115,'Matador Tiktok masala Candy',2.00,NULL,1.50,13,0.00,26.00,'2026-03-31 00:37:21','2026-03-31 00:37:21'),(127,71,71,'21 Dista White',25.00,NULL,21.00,1,0.00,25.00,'2026-03-31 00:37:21','2026-03-31 00:37:21'),(128,71,142,'Naruto Card',20.00,NULL,14.00,1,0.00,20.00,'2026-03-31 00:37:21','2026-03-31 00:37:21'),(129,71,78,'Doms Cutter - Sharpener',5.00,NULL,3.25,1,0.00,5.00,'2026-03-31 00:37:21','2026-03-31 00:37:21'),(130,71,42,'Matador Woodmark Eraser',10.00,NULL,8.13,1,0.00,10.00,'2026-03-31 00:37:21','2026-03-31 00:37:21'),(131,71,150,'Pocket Tissue',10.00,NULL,7.50,1,0.00,10.00,'2026-03-31 00:37:21','2026-03-31 00:37:21'),(132,72,74,'300p Math Khata (News)',50.00,NULL,41.00,1,0.00,50.00,'2026-03-31 03:19:17','2026-03-31 03:19:17'),(133,72,154,'55 Top math Khata',50.00,NULL,42.00,1,0.00,50.00,'2026-03-31 03:19:17','2026-03-31 03:19:17'),(134,72,29,'Photocopy',3.00,NULL,1.00,33,0.00,99.00,'2026-03-31 03:19:17','2026-03-31 03:19:17'),(135,72,117,'Photo to Photo',10.00,NULL,1.00,2,0.00,20.00,'2026-03-31 03:19:17','2026-03-31 03:19:17'),(136,72,40,'Matador Pluto Pencil 2B',10.00,NULL,4.58,1,0.00,10.00,'2026-03-31 03:19:17','2026-03-31 03:19:17'),(137,72,42,'Matador Woodmark Eraser',10.00,NULL,8.13,1,0.00,10.00,'2026-03-31 03:19:17','2026-03-31 03:19:17'),(138,73,118,'Photo to Photo 4 Copies',30.00,NULL,4.00,1,0.00,30.00,'2026-03-31 05:12:23','2026-03-31 05:12:23'),(139,73,29,'Photocopy',3.00,NULL,1.00,4,0.00,12.00,'2026-03-31 05:12:23','2026-03-31 05:12:23'),(140,74,33,'Matador Orbit',5.00,NULL,4.50,1,0.00,5.00,'2026-03-31 17:23:27','2026-03-31 17:23:27'),(141,74,150,'Pocket Tissue',10.00,NULL,7.50,1,0.00,10.00,'2026-03-31 17:23:27','2026-03-31 17:23:27'),(142,74,65,'Sunlite AAA Battery',30.00,NULL,26.00,1,0.00,30.00,'2026-03-31 17:23:27','2026-03-31 17:23:27'),(143,75,81,'Water Gum',25.00,NULL,15.00,1,0.00,25.00,'2026-03-31 17:29:51','2026-03-31 17:29:51'),(144,75,42,'Matador Woodmark Eraser',10.00,NULL,8.13,1,0.00,10.00,'2026-03-31 17:29:51','2026-03-31 17:29:51'),(145,76,41,'Matador i-teen Eraser',5.00,NULL,3.83,1,0.00,5.00,'2026-03-31 21:35:02','2026-03-31 21:35:02'),(146,76,70,'84p Pencil Khata',30.00,NULL,25.00,4,0.00,120.00,'2026-03-31 21:35:02','2026-03-31 21:35:02'),(147,76,81,'Water Gum',25.00,NULL,15.00,1,0.00,25.00,'2026-03-31 21:35:02','2026-03-31 21:35:02'),(148,76,60,'Math Khata (84p)',30.00,NULL,27.08,1,0.00,30.00,'2026-03-31 21:35:02','2026-03-31 21:35:02'),(149,76,29,'Photocopy',3.00,NULL,1.00,2,0.00,6.00,'2026-03-31 21:35:02','2026-03-31 21:35:02'),(150,76,30,'Matador All-time',6.00,NULL,5.00,7,0.00,42.00,'2026-03-31 21:35:02','2026-03-31 21:35:02'),(151,77,132,'5\'\' Scissor',35.00,NULL,23.00,1,0.00,35.00,'2026-03-31 21:41:47','2026-03-31 21:41:47'),(152,78,57,'Cartoon Tape',40.00,NULL,30.00,1,0.00,40.00,'2026-04-01 01:14:24','2026-04-01 01:14:24'),(153,78,33,'Matador Orbit',5.00,NULL,4.50,1,0.00,5.00,'2026-04-01 01:14:24','2026-04-01 01:14:24'),(154,78,29,'Photocopy',3.00,NULL,1.00,78,0.00,234.00,'2026-04-01 01:14:24','2026-04-01 01:14:24'),(155,78,27,'BW Print',10.00,NULL,1.00,5,0.00,50.00,'2026-04-01 01:14:24','2026-04-01 01:14:24'),(156,78,116,'Matador Pop-out Mango Candy',2.00,NULL,1.33,20,0.00,40.00,'2026-04-01 01:14:24','2026-04-01 01:14:24'),(157,78,118,'Photo to Photo 4 Copies',30.00,NULL,4.00,5,0.00,150.00,'2026-04-01 01:14:24','2026-04-01 01:14:24'),(158,79,34,'Matador I-teen Rio Black',10.00,NULL,8.67,2,0.00,20.00,'2026-04-01 01:17:56','2026-04-01 01:17:56'),(159,79,33,'Matador Orbit',5.00,NULL,4.50,1,0.00,5.00,'2026-04-01 01:17:56','2026-04-01 01:17:56'),(160,79,102,'24 No Register',75.00,NULL,65.00,1,0.00,75.00,'2026-04-01 01:17:56','2026-04-01 01:17:56'),(161,79,63,'kangaro Stapler Pin',25.00,NULL,21.00,1,0.00,25.00,'2026-04-01 01:17:56','2026-04-01 01:17:56'),(162,80,159,'Water Color Pencils',180.00,NULL,165.00,1,0.00,180.00,'2026-04-01 01:21:23','2026-04-01 01:21:23'),(163,81,115,'Matador Tiktok masala Candy',2.00,NULL,1.50,5,0.00,10.00,'2026-04-01 03:25:50','2026-04-01 03:25:50'),(164,81,29,'Photocopy',3.00,NULL,1.00,4,0.00,12.00,'2026-04-01 03:25:50','2026-04-01 03:25:50'),(165,81,128,'Computer Compose',25.00,NULL,3.00,1,0.00,25.00,'2026-04-01 03:25:50','2026-04-01 03:25:50'),(166,82,27,'BW Print',10.00,NULL,1.00,1,0.00,10.00,'2026-04-01 05:19:46','2026-04-01 05:19:46'),(167,83,29,'Photocopy',3.00,NULL,1.00,13,0.00,39.00,'2026-04-01 17:49:07','2026-04-01 17:49:07'),(168,83,72,'Karnafuli Print Dista',35.00,NULL,27.00,1,0.00,35.00,'2026-04-01 17:49:07','2026-04-01 17:49:07'),(169,83,47,'Matador smoothy Pencil 2B',10.00,NULL,5.83,2,0.00,20.00,'2026-04-01 17:49:07','2026-04-01 17:49:07'),(170,84,116,'Matador Pop-out Mango Candy',2.00,NULL,1.33,3,0.00,6.00,'2026-04-02 01:05:57','2026-04-02 01:05:57'),(171,84,29,'Photocopy',3.00,NULL,1.00,34,0.00,102.00,'2026-04-02 01:05:57','2026-04-02 01:05:57'),(172,84,27,'BW Print',10.00,NULL,1.00,6,0.00,60.00,'2026-04-02 01:05:57','2026-04-02 01:05:57'),(173,84,47,'Matador smoothy Pencil 2B',10.00,NULL,5.83,1,0.00,10.00,'2026-04-02 01:05:57','2026-04-02 01:05:57'),(174,84,106,'Matador smothy Premium Ballpen with oil gel ink',10.00,NULL,8.00,1,0.00,10.00,'2026-04-02 01:05:57','2026-04-02 01:05:57'),(175,85,29,'Photocopy',3.00,NULL,1.00,25,0.00,75.00,'2026-04-02 02:58:19','2026-04-02 02:58:19'),(176,86,29,'Photocopy',3.00,NULL,1.00,2,0.00,6.00,'2026-04-02 03:44:50','2026-04-02 03:44:50'),(177,87,157,'Double A Legal Paper',650.00,NULL,630.00,10,0.00,6500.00,'2026-04-02 16:29:30','2026-04-02 16:29:30'),(178,87,158,'Double A A4 Paper',520.00,NULL,484.00,50,0.00,26000.00,'2026-04-02 16:29:30','2026-04-02 16:29:30'),(179,87,155,'Matador Highlighter Pen',35.00,NULL,25.00,24,0.00,840.00,'2026-04-02 16:29:30','2026-04-02 16:29:30'),(180,87,156,'Bundle Steel Tag',55.00,NULL,45.00,25,0.00,1375.00,'2026-04-02 16:29:30','2026-04-02 16:29:30'),(181,87,103,'Sticky Note',30.00,NULL,25.00,20,0.00,600.00,'2026-04-02 16:29:30','2026-04-02 16:29:30'),(182,87,32,'Matador Pin-Point Black',5.00,NULL,4.50,40,0.00,200.00,'2026-04-02 16:29:30','2026-04-02 16:29:30'),(183,88,168,'Flax',750.00,NULL,550.00,1,0.00,750.00,'2026-04-02 16:59:41','2026-04-02 16:59:41'),(184,89,157,'Double A Legal Paper',650.00,NULL,630.00,10,0.00,6500.00,'2026-04-02 17:24:00','2026-04-02 17:24:00'),(185,89,158,'Double A A4 Paper',530.00,NULL,484.00,50,0.00,26500.00,'2026-04-02 17:24:00','2026-04-02 17:24:00'),(186,89,155,'Matador Highlighter Pen',35.00,NULL,25.00,24,0.00,840.00,'2026-04-02 17:24:00','2026-04-02 17:24:00'),(187,89,156,'Bundle Steel Tag',55.00,NULL,45.00,25,0.00,1375.00,'2026-04-02 17:24:00','2026-04-02 17:24:00'),(188,89,103,'Sticky Note',35.00,NULL,25.00,20,0.00,700.00,'2026-04-02 17:24:00','2026-04-02 17:24:00'),(189,89,32,'Matador Pin-Point Black',5.00,NULL,4.50,40,0.00,200.00,'2026-04-02 17:24:00','2026-04-02 17:24:00'),(190,90,27,'BW Print',10.00,NULL,1.00,7,0.00,70.00,'2026-04-02 17:26:25','2026-04-02 17:26:25'),(191,90,32,'Matador Pin-Point Black',5.00,NULL,4.50,4,0.00,20.00,'2026-04-02 17:26:25','2026-04-02 17:26:25'),(192,90,40,'Matador Pluto Pencil 2B',10.00,NULL,4.58,1,0.00,10.00,'2026-04-02 17:26:25','2026-04-02 17:26:25'),(193,90,29,'Photocopy',3.00,NULL,1.00,3,0.00,9.00,'2026-04-02 17:26:25','2026-04-02 17:26:25'),(194,91,29,'Photocopy',3.00,NULL,1.00,8,0.00,24.00,'2026-04-02 17:51:36','2026-04-02 17:51:36'),(195,92,29,'Photocopy',3.00,NULL,1.00,2,0.00,6.00,'2026-04-02 17:53:57','2026-04-02 17:53:57'),(196,92,115,'Matador Tiktok masala Candy',2.00,NULL,1.50,5,0.00,10.00,'2026-04-02 17:53:57','2026-04-02 17:53:57'),(197,93,29,'Photocopy',3.00,NULL,1.00,17,0.00,51.00,'2026-04-03 01:12:03','2026-04-03 01:12:03'),(198,93,27,'BW Print',10.00,NULL,1.00,26,0.00,260.00,'2026-04-03 01:12:03','2026-04-03 01:12:03'),(199,93,116,'Matador Pop-out Mango Candy',2.00,NULL,1.33,12,0.00,24.00,'2026-04-03 01:12:03','2026-04-03 01:12:03'),(200,93,86,'Suta (সুতা) Small',5.00,NULL,2.44,1,0.00,5.00,'2026-04-03 01:12:03','2026-04-03 01:12:03'),(201,93,111,'Furoni (ফুড়োনি)',10.00,NULL,5.42,1,0.00,10.00,'2026-04-03 01:12:03','2026-04-03 01:12:03'),(202,93,82,'A4 Envelop',5.00,NULL,2.20,1,0.00,5.00,'2026-04-03 01:12:03','2026-04-03 01:12:03'),(203,94,27,'BW Print',10.00,NULL,1.00,3,0.00,30.00,'2026-04-03 05:11:44','2026-04-03 05:11:44'),(204,94,170,'55gsm Basundhara Rim Dista Paper (500Page)',550.00,NULL,450.00,1,0.00,550.00,'2026-04-03 05:11:44','2026-04-03 05:11:44'),(205,94,32,'Matador Pin-Point Black',5.00,NULL,4.50,2,0.00,10.00,'2026-04-03 05:11:44','2026-04-03 05:11:44'),(206,95,27,'BW Print',10.00,NULL,1.00,19,0.00,190.00,'2026-04-04 05:02:20','2026-04-04 05:02:20'),(207,95,54,'Aica Gum',20.00,NULL,15.00,1,0.00,20.00,'2026-04-04 05:02:20','2026-04-04 05:02:20'),(208,95,60,'Math Khata (84p)',30.00,NULL,27.08,6,0.00,180.00,'2026-04-04 05:02:20','2026-04-04 05:02:20'),(209,95,40,'Matador Pluto Pencil 2B',10.00,NULL,4.58,1,0.00,10.00,'2026-04-04 05:02:20','2026-04-04 05:02:20'),(210,95,42,'Matador Woodmark Eraser',10.00,NULL,8.13,1,0.00,10.00,'2026-04-04 05:02:20','2026-04-04 05:02:20'),(211,95,123,'Stylo Sharpener',10.00,NULL,6.00,1,0.00,10.00,'2026-04-04 05:02:20','2026-04-04 05:02:20'),(212,95,104,'Good Luck Craze Ball Pen',10.00,NULL,8.00,1,0.00,10.00,'2026-04-04 05:02:20','2026-04-04 05:02:20'),(213,95,29,'Photocopy',3.00,NULL,1.00,4,0.00,12.00,'2026-04-04 05:02:20','2026-04-04 05:02:20'),(214,95,115,'Matador Tiktok masala Candy',2.00,NULL,1.50,5,0.00,10.00,'2026-04-04 05:02:20','2026-04-04 05:02:20'),(215,96,130,'Gum Stick (Small)',10.00,NULL,5.50,1,0.00,10.00,'2026-04-04 17:22:25','2026-04-04 17:22:25'),(216,96,68,'1 Dista Paper (News)',15.00,NULL,12.00,1,0.00,15.00,'2026-04-04 17:22:25','2026-04-04 17:22:25');
/*!40000 ALTER TABLE `sale_items` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sales`
--

DROP TABLE IF EXISTS `sales`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sales` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `invoice_number` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint(20) unsigned NOT NULL,
  `customer_name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `customer_phone` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `subtotal` decimal(12,2) NOT NULL,
  `discount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `tax` decimal(12,2) NOT NULL DEFAULT '0.00',
  `total` decimal(12,2) NOT NULL,
  `paid_amount` decimal(12,2) NOT NULL,
  `change_amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `due_amount` decimal(10,2) NOT NULL DEFAULT '0.00',
  `payment_status` enum('paid','partial','due') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'paid',
  `payment_method` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'cash',
  `status` enum('completed','pending','cancelled') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'completed',
  `notes` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `sales_invoice_number_unique` (`invoice_number`),
  KEY `sales_user_id_foreign` (`user_id`),
  CONSTRAINT `sales_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=97 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sales`
--

LOCK TABLES `sales` WRITE;
/*!40000 ALTER TABLE `sales` DISABLE KEYS */;
INSERT INTO `sales` VALUES (1,'INV202603140001',1,NULL,NULL,20.00,5.00,0.00,15.00,15.00,0.00,0.00,'paid','cash','cancelled',NULL,'2026-03-14 13:54:42','2026-03-15 11:11:56'),(2,'INV202603150001',1,NULL,NULL,40.00,10.00,0.00,30.00,30.00,0.00,0.00,'paid','cash','cancelled',NULL,'2026-03-15 07:16:00','2026-03-15 11:12:02'),(3,'INV202603150002',1,NULL,NULL,18.00,3.00,0.00,15.00,15.00,0.00,0.00,'paid','cash','completed',NULL,'2026-03-15 12:53:49','2026-03-15 12:53:49'),(4,'INV202603150003',1,NULL,NULL,10.00,0.00,0.00,10.00,10.00,0.00,0.00,'paid','cash','completed',NULL,'2026-03-15 12:54:20','2026-03-15 12:54:20'),(5,'INV202603150004',1,NULL,NULL,50.00,5.00,0.00,45.00,45.00,0.00,0.00,'paid','cash','completed',NULL,'2026-03-15 12:54:51','2026-03-15 12:54:51'),(6,'INV202603150005',1,NULL,NULL,40.00,5.00,0.00,35.00,35.00,0.00,0.00,'paid','cash','completed',NULL,'2026-03-15 12:57:24','2026-03-15 12:57:24'),(7,'INV202603150006',1,NULL,NULL,45.00,0.00,0.00,45.00,45.00,0.00,0.00,'paid','cash','completed',NULL,'2026-03-15 12:57:47','2026-03-15 12:57:47'),(8,'INV202603150007',1,NULL,NULL,120.00,5.00,0.00,115.00,115.00,0.00,0.00,'paid','cash','completed',NULL,'2026-03-15 12:58:34','2026-03-15 12:58:34'),(9,'INV202603150008',1,NULL,NULL,10.00,0.00,0.00,10.00,10.00,0.00,0.00,'paid','cash','completed',NULL,'2026-03-15 12:59:33','2026-03-15 12:59:33'),(10,'INV202603150009',1,NULL,NULL,20.00,0.00,0.00,20.00,20.00,0.00,0.00,'paid','cash','completed',NULL,'2026-03-15 13:00:15','2026-03-15 13:00:15'),(11,'INV202603150010',1,NULL,NULL,10.00,0.00,0.00,10.00,10.00,0.00,0.00,'paid','cash','completed',NULL,'2026-03-15 13:07:23','2026-03-15 13:07:23'),(12,'INV202603160001',1,NULL,NULL,10.00,0.00,0.00,10.00,20.00,10.00,0.00,'paid','cash','completed',NULL,'2026-03-16 08:10:42','2026-03-16 08:10:42'),(13,'INV202603160002',1,NULL,NULL,167.00,2.00,0.00,165.00,200.00,35.00,0.00,'paid','cash','completed',NULL,'2026-03-16 08:46:35','2026-03-16 08:46:35'),(14,'INV202603160003',1,NULL,NULL,47.00,2.00,0.00,45.00,45.00,0.00,0.00,'paid','cash','completed',NULL,'2026-03-16 08:47:12','2026-03-16 08:47:12'),(15,'INV202603160004',1,NULL,NULL,36.00,1.00,0.00,35.00,35.00,0.00,0.00,'paid','cash','completed',NULL,'2026-03-16 09:59:33','2026-03-16 09:59:33'),(16,'INV202603160005',1,NULL,NULL,6.00,1.00,0.00,5.00,5.00,0.00,0.00,'paid','cash','completed',NULL,'2026-03-16 09:59:58','2026-03-16 09:59:58'),(17,'INV202603160006',1,NULL,NULL,12.00,2.00,0.00,10.00,10.00,0.00,0.00,'paid','cash','completed',NULL,'2026-03-16 10:34:37','2026-03-16 10:34:37'),(18,'INV202603160007',1,NULL,NULL,60.00,10.00,0.00,50.00,50.00,0.00,0.00,'paid','cash','completed',NULL,'2026-03-16 10:35:32','2026-03-16 10:35:32'),(19,'INV202603160008',1,NULL,NULL,320.00,60.00,0.00,260.00,500.00,240.00,0.00,'paid','cash','completed',NULL,'2026-03-16 13:10:02','2026-03-16 13:10:02'),(20,'INV202603160009',1,NULL,NULL,15.00,0.00,0.00,15.00,15.00,0.00,0.00,'paid','cash','completed',NULL,'2026-03-16 15:08:15','2026-03-16 15:08:15'),(21,'INV202603170001',1,NULL,NULL,20.00,0.00,0.00,20.00,20.00,0.00,0.00,'paid','cash','completed',NULL,'2026-03-18 01:30:01','2026-03-18 01:30:01'),(22,'INV202603200001',1,NULL,NULL,50.00,0.00,0.00,50.00,50.00,0.00,0.00,'paid','cash','completed',NULL,'2026-03-20 23:55:25','2026-03-20 23:55:25'),(23,'INV202603220001',1,NULL,NULL,20.00,0.00,0.00,20.00,100.00,80.00,0.00,'paid','cash','completed',NULL,'2026-03-22 18:52:47','2026-03-22 18:52:47'),(24,'INV202603220002',1,NULL,NULL,80.00,30.00,0.00,50.00,50.00,0.00,0.00,'paid','cash','completed','police officer','2026-03-22 18:53:22','2026-03-22 18:53:22'),(25,'INV202603220003',4,NULL,NULL,10.00,0.00,0.00,10.00,10.00,0.00,0.00,'paid','cash','completed',NULL,'2026-03-22 20:21:17','2026-03-22 20:21:17'),(26,'INV202603230001',4,NULL,NULL,10.00,0.00,0.00,10.00,10.00,0.00,0.00,'paid','cash','completed',NULL,'2026-03-23 18:38:13','2026-03-23 18:38:13'),(27,'INV202603230002',1,NULL,NULL,10.00,0.00,0.00,10.00,10.00,0.00,0.00,'paid','cash','completed',NULL,'2026-03-23 19:58:24','2026-03-23 19:58:24'),(28,'INV202603230003',1,NULL,NULL,136.00,6.00,0.00,130.00,130.00,0.00,0.00,'paid','cash','completed',NULL,'2026-03-23 20:22:09','2026-03-23 20:22:09'),(29,'INV202603230004',1,NULL,NULL,10.00,0.00,0.00,10.00,10.00,0.00,0.00,'paid','cash','completed',NULL,'2026-03-23 23:54:56','2026-03-23 23:54:56'),(30,'INV202603230005',1,NULL,NULL,30.00,0.00,0.00,30.00,30.00,0.00,0.00,'paid','cash','completed',NULL,'2026-03-24 01:47:03','2026-03-24 01:47:03'),(31,'INV202603240001',1,NULL,NULL,36.00,1.00,0.00,35.00,100.00,65.00,0.00,'paid','cash','completed',NULL,'2026-03-24 22:18:33','2026-03-24 22:18:33'),(32,'INV202603240002',1,NULL,NULL,80.00,5.00,0.00,75.00,500.00,425.00,0.00,'paid','cash','completed',NULL,'2026-03-25 03:48:50','2026-03-25 03:48:50'),(33,'INV202603240003',1,NULL,NULL,30.00,4.00,0.00,26.00,26.00,0.00,0.00,'paid','cash','completed',NULL,'2026-03-25 05:29:26','2026-03-25 05:29:26'),(34,'INV202603240004',1,NULL,NULL,90.00,0.00,0.00,90.00,90.00,0.00,0.00,'paid','cash','completed',NULL,'2026-03-25 05:30:10','2026-03-25 05:30:10'),(35,'INV202603240005',1,NULL,NULL,20.00,0.00,0.00,20.00,20.00,0.00,0.00,'paid','cash','completed','eticket purchase consultation fee','2026-03-25 05:31:07','2026-03-25 05:31:07'),(36,'INV202603250001',1,NULL,NULL,40.00,0.00,0.00,40.00,100.00,60.00,0.00,'paid','cash','completed',NULL,'2026-03-25 17:13:55','2026-03-25 17:13:55'),(37,'INV202603250002',4,NULL,NULL,72.00,12.00,0.00,60.00,60.00,0.00,0.00,'paid','cash','completed',NULL,'2026-03-25 22:34:57','2026-03-25 22:34:57'),(38,'INV202603250003',4,NULL,NULL,65.00,0.00,0.00,65.00,65.00,0.00,0.00,'paid','cash','completed',NULL,'2026-03-25 22:35:44','2026-03-25 22:35:44'),(39,'INV202603250004',4,NULL,NULL,6.00,1.00,0.00,5.00,5.00,0.00,0.00,'paid','cash','completed',NULL,'2026-03-25 22:36:00','2026-03-25 22:36:00'),(40,'INV202603250005',4,NULL,NULL,80.00,0.00,0.00,80.00,80.00,0.00,0.00,'paid','cash','completed',NULL,'2026-03-25 23:28:41','2026-03-25 23:28:41'),(41,'INV202603250006',4,NULL,NULL,100.00,0.00,0.00,100.00,100.00,0.00,0.00,'paid','cash','completed',NULL,'2026-03-26 01:26:24','2026-03-26 01:26:24'),(42,'INV202603250007',4,NULL,NULL,40.00,0.00,0.00,40.00,40.00,0.00,0.00,'paid','cash','completed',NULL,'2026-03-26 01:28:44','2026-03-26 01:28:44'),(43,'INV202603250008',4,NULL,NULL,5.00,0.00,0.00,5.00,5.00,0.00,0.00,'paid','cash','completed',NULL,'2026-03-26 01:53:44','2026-03-26 01:53:44'),(44,'INV202603250009',4,NULL,NULL,305.00,20.00,0.00,285.00,285.00,0.00,0.00,'paid','cash','completed',NULL,'2026-03-26 01:56:43','2026-03-26 01:56:43'),(45,'INV202603250010',4,NULL,NULL,35.00,0.00,0.00,35.00,35.00,0.00,0.00,'paid','cash','completed',NULL,'2026-03-26 01:59:47','2026-03-26 01:59:47'),(46,'INV202603250011',4,NULL,NULL,65.00,0.00,0.00,65.00,65.00,0.00,0.00,'paid','cash','completed',NULL,'2026-03-26 03:58:47','2026-03-26 03:58:47'),(47,'INV202603250012',4,NULL,NULL,375.00,100.00,0.00,275.00,275.00,0.00,0.00,'paid','cash','completed',NULL,'2026-03-26 04:05:31','2026-03-26 04:05:31'),(48,'INV202603250013',4,NULL,NULL,12.00,2.00,0.00,10.00,10.00,0.00,0.00,'paid','cash','completed',NULL,'2026-03-26 04:06:23','2026-03-26 04:06:23'),(49,'INV202603250014',4,NULL,NULL,20.00,0.00,0.00,20.00,20.00,0.00,0.00,'paid','cash','completed',NULL,'2026-03-26 05:17:10','2026-03-26 05:17:10'),(50,'INV202603250015',4,NULL,NULL,245.00,0.00,0.00,245.00,245.00,0.00,0.00,'paid','cash','completed',NULL,'2026-03-26 05:19:53','2026-03-26 05:19:53'),(51,'INV202603260001',4,NULL,NULL,40.00,0.00,0.00,40.00,40.00,0.00,0.00,'paid','cash','completed',NULL,'2026-03-26 17:29:40','2026-03-26 17:29:40'),(52,'INV202603260002',4,NULL,NULL,41.00,1.00,0.00,40.00,40.00,0.00,0.00,'paid','cash','completed',NULL,'2026-03-26 17:42:19','2026-03-26 17:42:19'),(53,'INV202603260003',4,NULL,NULL,50.00,0.00,0.00,50.00,50.00,0.00,0.00,'paid','cash','completed',NULL,'2026-03-26 17:44:19','2026-03-26 17:44:19'),(54,'INV202603260004',1,NULL,NULL,6.00,0.00,0.00,6.00,6.00,0.00,0.00,'paid','cash','completed',NULL,'2026-03-26 18:58:18','2026-03-26 18:58:18'),(55,'INV202603270001',4,NULL,NULL,406.00,11.00,0.00,395.00,395.00,0.00,0.00,'paid','cash','completed',NULL,'2026-03-27 18:16:27','2026-03-27 18:16:27'),(56,'INV202603270002',1,NULL,NULL,130.00,0.00,0.00,130.00,130.00,0.00,0.00,'paid','cash','completed',NULL,'2026-03-27 19:08:43','2026-03-27 19:08:43'),(57,'INV202603270003',4,NULL,NULL,28.00,0.00,0.00,28.00,28.00,0.00,0.00,'paid','cash','completed',NULL,'2026-03-28 00:59:09','2026-03-28 00:59:09'),(58,'INV202603270004',4,NULL,NULL,12.00,0.00,3.00,15.00,15.00,0.00,0.00,'paid','cash','completed',NULL,'2026-03-28 01:44:03','2026-03-28 01:44:03'),(59,'INV202603280001',4,NULL,NULL,105.00,1.00,0.00,104.00,104.00,0.00,0.00,'paid','cash','completed',NULL,'2026-03-29 02:50:23','2026-03-29 02:50:23'),(60,'INV202603290001',4,NULL,NULL,30.00,5.00,0.00,25.00,25.00,0.00,0.00,'paid','cash','completed',NULL,'2026-03-29 17:22:16','2026-03-29 17:22:16'),(61,'INV202603280002',4,NULL,NULL,12.00,0.00,0.00,12.00,12.00,0.00,0.00,'paid','cash','completed',NULL,'2026-03-28 18:50:46','2026-03-28 18:50:46'),(62,'INV202603290002',4,NULL,NULL,85.00,0.00,0.00,85.00,85.00,0.00,0.00,'paid','cash','completed',NULL,'2026-03-29 18:53:20','2026-03-29 18:53:20'),(63,'INV202603290003',4,NULL,NULL,87.00,0.00,0.00,87.00,87.00,0.00,0.00,'paid','cash','completed',NULL,'2026-03-30 00:22:39','2026-03-30 00:22:39'),(64,'INV202603290004',4,NULL,NULL,15.00,0.00,0.00,15.00,15.00,0.00,0.00,'paid','cash','completed',NULL,'2026-03-30 00:24:07','2026-03-30 00:24:07'),(65,'INV202603290005',4,NULL,NULL,535.00,50.00,0.00,485.00,485.00,0.00,0.00,'paid','cash','completed',NULL,'2026-03-30 01:07:56','2026-03-30 01:07:56'),(66,'INV202603290006',4,NULL,NULL,20.00,5.00,0.00,15.00,15.00,0.00,0.00,'paid','cash','completed',NULL,'2026-03-30 01:10:01','2026-03-30 01:10:01'),(67,'INV202603290007',4,NULL,NULL,10.00,0.00,0.00,10.00,10.00,0.00,0.00,'paid','cash','completed',NULL,'2026-03-30 01:40:23','2026-03-30 01:40:23'),(68,'INV202603290008',4,NULL,NULL,42.00,5.00,0.00,37.00,37.00,0.00,0.00,'paid','cash','completed',NULL,'2026-03-30 04:25:38','2026-03-30 04:25:38'),(69,'INV202603290009',4,NULL,NULL,20.00,0.00,0.00,20.00,20.00,0.00,0.00,'paid','cash','completed',NULL,'2026-03-30 05:32:57','2026-03-30 05:32:57'),(70,'INV202603300001',4,NULL,NULL,551.00,1.00,5.00,555.00,555.00,0.00,0.00,'paid','cash','completed',NULL,'2026-03-30 20:51:23','2026-03-30 20:51:23'),(71,'INV202603300002',4,NULL,NULL,323.00,3.00,0.00,320.00,320.00,0.00,0.00,'paid','cash','completed',NULL,'2026-03-31 00:37:21','2026-03-31 00:37:21'),(72,'INV202603300003',4,NULL,NULL,239.00,0.00,0.00,239.00,239.00,0.00,0.00,'paid','cash','completed',NULL,'2026-03-31 03:19:17','2026-03-31 03:19:17'),(73,'INV202603300004',1,NULL,NULL,42.00,2.00,0.00,40.00,40.00,0.00,0.00,'paid','cash','completed',NULL,'2026-03-31 05:12:23','2026-03-31 05:12:23'),(74,'INV202603310001',4,NULL,NULL,45.00,0.00,0.00,45.00,45.00,0.00,0.00,'paid','cash','completed',NULL,'2026-03-31 17:23:27','2026-03-31 17:23:27'),(75,'INV202603310002',4,NULL,NULL,35.00,0.00,0.00,35.00,35.00,0.00,0.00,'paid','cash','completed',NULL,'2026-03-31 17:29:51','2026-03-31 17:29:51'),(76,'INV202603310003',4,NULL,NULL,228.00,3.00,0.00,225.00,225.00,0.00,0.00,'paid','cash','completed',NULL,'2026-03-31 21:35:02','2026-03-31 21:35:02'),(77,'INV202603310004',4,NULL,NULL,35.00,15.00,0.00,20.00,20.00,0.00,0.00,'paid','cash','completed',NULL,'2026-03-31 21:41:47','2026-03-31 21:41:47'),(78,'INV202603310005',4,NULL,NULL,519.00,3.00,0.00,516.00,516.00,0.00,0.00,'paid','cash','completed',NULL,'2026-04-01 01:14:24','2026-04-01 01:14:24'),(79,'INV202603310006',4,NULL,NULL,125.00,15.00,0.00,110.00,110.00,0.00,0.00,'paid','cash','completed',NULL,'2026-04-01 01:17:56','2026-04-01 01:17:56'),(80,'INV202603310007',4,NULL,NULL,180.00,5.00,0.00,175.00,175.00,0.00,0.00,'paid','cash','completed',NULL,'2026-04-01 01:21:23','2026-04-01 01:21:23'),(81,'INV202603310008',4,NULL,NULL,47.00,2.00,5.00,50.00,50.00,0.00,0.00,'paid','cash','completed',NULL,'2026-04-01 03:25:50','2026-04-01 03:25:50'),(82,'INV202603310009',4,NULL,NULL,10.00,0.00,0.00,10.00,10.00,0.00,0.00,'paid','cash','completed',NULL,'2026-04-01 05:19:46','2026-04-01 05:19:46'),(83,'INV202604010001',4,NULL,NULL,94.00,5.00,0.00,89.00,89.00,0.00,0.00,'paid','cash','completed',NULL,'2026-04-01 17:49:07','2026-04-01 17:49:07'),(84,'INV202604010002',4,NULL,NULL,188.00,0.00,0.00,188.00,188.00,0.00,0.00,'paid','cash','completed',NULL,'2026-04-02 01:05:57','2026-04-02 01:05:57'),(85,'INV202604010003',4,NULL,NULL,75.00,0.00,0.00,75.00,75.00,0.00,0.00,'paid','cash','completed',NULL,'2026-04-02 02:58:19','2026-04-02 02:58:19'),(86,'INV202604010004',4,NULL,NULL,6.00,1.00,0.00,5.00,5.00,0.00,0.00,'paid','cash','completed',NULL,'2026-04-02 03:44:50','2026-04-02 03:44:50'),(87,'INV202604020001',4,NULL,NULL,35515.00,0.00,600.00,36115.00,36115.00,0.00,0.00,'paid','cash','cancelled',NULL,'2026-04-02 16:29:30','2026-04-02 17:21:29'),(88,'INV202604020002',4,NULL,NULL,750.00,0.00,0.00,750.00,750.00,0.00,0.00,'paid','cash','completed',NULL,'2026-04-02 16:59:41','2026-04-02 16:59:41'),(89,'INV202604020003',4,NULL,NULL,36115.00,0.00,0.00,36115.00,36115.00,0.00,0.00,'paid','cash','completed',NULL,'2026-04-02 17:24:00','2026-04-02 17:24:00'),(90,'INV202604020004',4,NULL,NULL,109.00,0.00,1.00,110.00,110.00,0.00,0.00,'paid','cash','completed',NULL,'2026-04-02 17:26:25','2026-04-02 17:26:25'),(91,'INV202604010005',4,NULL,NULL,24.00,0.00,1.00,25.00,25.00,0.00,0.00,'paid','cash','completed',NULL,'2026-04-01 17:51:36','2026-04-01 17:51:36'),(92,'INV202604020005',4,NULL,NULL,16.00,0.00,1.00,17.00,17.00,0.00,0.00,'paid','cash','completed',NULL,'2026-04-02 17:53:57','2026-04-02 17:53:57'),(93,'INV202604020006',4,NULL,NULL,355.00,2.00,0.00,353.00,353.00,0.00,0.00,'paid','cash','completed',NULL,'2026-04-03 01:12:03','2026-04-03 01:12:03'),(94,'INV202604020007',4,NULL,NULL,590.00,0.00,0.00,590.00,590.00,0.00,0.00,'paid','cash','completed','due 350','2026-04-03 05:11:44','2026-04-03 05:11:44'),(95,'INV202604030001',4,NULL,NULL,452.00,0.00,0.00,452.00,452.00,0.00,0.00,'paid','cash','completed',NULL,'2026-04-04 05:02:20','2026-04-04 05:02:20'),(96,'INV202604040001',4,NULL,NULL,25.00,0.00,0.00,25.00,25.00,0.00,0.00,'paid','cash','completed',NULL,'2026-04-04 17:22:25','2026-04-04 17:22:25');
/*!40000 ALTER TABLE `sales` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sessions`
--

DROP TABLE IF EXISTS `sessions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sessions` (
  `id` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sessions`
--

LOCK TABLES `sessions` WRITE;
/*!40000 ALTER TABLE `sessions` DISABLE KEYS */;
/*!40000 ALTER TABLE `sessions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `settings`
--

DROP TABLE IF EXISTS `settings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `settings` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `key` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `settings_key_unique` (`key`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `settings`
--

LOCK TABLES `settings` WRITE;
/*!40000 ALTER TABLE `settings` DISABLE KEYS */;
INSERT INTO `settings` VALUES (1,'currency_symbol','৳','2026-04-04 14:24:50','2026-04-04 14:24:50'),(2,'currency_code','BDT','2026-04-04 14:24:50','2026-04-04 14:24:50'),(3,'shop_name','Stationery POS','2026-04-04 14:24:50','2026-04-04 14:24:50'),(4,'shop_address','','2026-04-04 14:24:50','2026-04-04 14:24:50'),(5,'shop_phone','','2026-04-04 14:24:50','2026-04-04 14:24:50'),(6,'tax_percentage','0','2026-04-04 14:24:50','2026-04-04 14:24:50'),(7,'payment_methods','[{\"code\":\"cash\",\"name\":\"Cash\"},{\"code\":\"card\",\"name\":\"Card\"},{\"code\":\"upi\",\"name\":\"UPI\"},{\"code\":\"mobile_banking\",\"name\":\"Mobile Banking\"}]','2026-04-04 14:24:50','2026-04-04 14:24:50'),(8,'timezone','Asia/Dhaka','2026-04-04 14:24:50','2026-04-04 14:24:50');
/*!40000 ALTER TABLE `settings` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `suppliers`
--

DROP TABLE IF EXISTS `suppliers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `suppliers` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `company` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` text COLLATE utf8mb4_unicode_ci,
  `notes` text COLLATE utf8mb4_unicode_ci,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `suppliers`
--

LOCK TABLES `suppliers` WRITE;
/*!40000 ALTER TABLE `suppliers` DISABLE KEYS */;
/*!40000 ALTER TABLE `suppliers` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `role` enum('admin','cashier') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'cashier',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'Admin','admin@pos.com',NULL,'$2y$12$.n/5Vng2l4e183gtA2WX0uwVHVzYqCZLyJrZ4m/boJsURnyJAuoga','admin',1,NULL,'2026-03-14 13:33:19','2026-03-18 17:27:55'),(2,'Moktadir Rahman','moktadir@pos.com',NULL,'$2y$12$XaxprI0QV0NCCRISvexNOuax4C/C9nypPGbrkkSfiqcYBWu2O2S4y','cashier',1,NULL,'2026-03-17 07:55:58','2026-03-17 08:10:32'),(4,'Amzad Hossain','amzad@pos.com',NULL,'$2y$12$7OMaZMSk9ui7VLbqr/6WLOWxfJ8cdI6J6AlIpDVnBrwJlSext4j5e','admin',1,'c0qQ5QwCmrBjP4kNMpnqtQoGPMcaSatlVlYFJuJ9XwhP1DCi2Uv2kQUw2idX','2026-03-17 21:04:28','2026-03-17 21:04:28');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2026-04-04  1:12:37
