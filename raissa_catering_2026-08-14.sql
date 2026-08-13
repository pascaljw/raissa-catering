# ************************************************************
# Sequel Ace SQL dump
# Version 20100
#
# https://sequel-ace.com/
# https://github.com/Sequel-Ace/Sequel-Ace
#
# Host: 127.0.0.1 (MySQL 9.5.0)
# Database: raissa_catering
# Generation Time: 2026-08-13 4:55:19 PM +0000
# ************************************************************


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
SET NAMES utf8mb4;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE='NO_AUTO_VALUE_ON_ZERO', SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


# Dump of table blocked_dates
# ------------------------------------------------------------

DROP TABLE IF EXISTS `blocked_dates`;

CREATE TABLE `blocked_dates` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `date` date NOT NULL,
  `reason` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



# Dump of table cache
# ------------------------------------------------------------

DROP TABLE IF EXISTS `cache`;

CREATE TABLE `cache` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` bigint NOT NULL,
  PRIMARY KEY (`key`),
  KEY `cache_expiration_index` (`expiration`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



# Dump of table cache_locks
# ------------------------------------------------------------

DROP TABLE IF EXISTS `cache_locks`;

CREATE TABLE `cache_locks` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` bigint NOT NULL,
  PRIMARY KEY (`key`),
  KEY `cache_locks_expiration_index` (`expiration`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



# Dump of table failed_jobs
# ------------------------------------------------------------

DROP TABLE IF EXISTS `failed_jobs`;

CREATE TABLE `failed_jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`),
  KEY `failed_jobs_connection_queue_failed_at_index` (`connection`,`queue`,`failed_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



# Dump of table item_package
# ------------------------------------------------------------

DROP TABLE IF EXISTS `item_package`;

CREATE TABLE `item_package` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `package_id` bigint unsigned NOT NULL,
  `item_id` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `item_package_package_id_item_id_unique` (`package_id`,`item_id`),
  KEY `item_package_item_id_foreign` (`item_id`),
  CONSTRAINT `item_package_item_id_foreign` FOREIGN KEY (`item_id`) REFERENCES `items` (`id`) ON DELETE CASCADE,
  CONSTRAINT `item_package_package_id_foreign` FOREIGN KEY (`package_id`) REFERENCES `packages` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

LOCK TABLES `item_package` WRITE;
/*!40000 ALTER TABLE `item_package` DISABLE KEYS */;

INSERT INTO `item_package` (`id`, `package_id`, `item_id`, `created_at`, `updated_at`)
VALUES
	(1,4,1,NULL,NULL),
	(2,4,2,NULL,NULL),
	(3,4,3,NULL,NULL),
	(4,4,4,NULL,NULL),
	(5,4,5,NULL,NULL),
	(6,4,6,NULL,NULL),
	(7,4,7,NULL,NULL),
	(8,4,8,NULL,NULL),
	(9,4,9,NULL,NULL),
	(10,4,10,NULL,NULL),
	(11,4,11,NULL,NULL),
	(12,4,12,NULL,NULL),
	(13,4,13,NULL,NULL),
	(14,4,14,NULL,NULL),
	(15,4,15,NULL,NULL);

/*!40000 ALTER TABLE `item_package` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table items
# ------------------------------------------------------------

DROP TABLE IF EXISTS `items`;

CREATE TABLE `items` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `category` enum('lauk','minuman','buah','protein','vegetable','soup','condiment','dessert','beverage') COLLATE utf8mb4_unicode_ci NOT NULL,
  `additional_price` decimal(10,2) NOT NULL DEFAULT '0.00',
  `description` text COLLATE utf8mb4_unicode_ci,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

LOCK TABLES `items` WRITE;
/*!40000 ALTER TABLE `items` DISABLE KEYS */;

INSERT INTO `items` (`id`, `name`, `category`, `additional_price`, `description`, `is_active`, `created_at`, `updated_at`)
VALUES
	(1,'Ayam Goreng Crispy','protein',0.00,'Menu item untuk paket custom',1,'2026-06-22 11:55:31','2026-06-22 11:55:31'),
	(2,'Ayam Bakar Madu','protein',2000.00,'Menu item untuk paket custom',1,'2026-06-22 11:55:31','2026-06-22 11:55:31'),
	(3,'Ayam Bakar Bumbu Khas','protein',2000.00,'Menu item untuk paket custom',1,'2026-06-22 11:55:31','2026-06-22 11:55:31'),
	(4,'Daging Rendang','protein',5000.00,'Menu item untuk paket custom',1,'2026-06-22 11:55:31','2026-06-22 11:55:31'),
	(5,'Daging Sate','protein',4000.00,'Menu item untuk paket custom',1,'2026-06-22 11:55:31','2026-06-22 11:55:31'),
	(6,'Ikan Bakar','protein',3000.00,'Menu item untuk paket custom',1,'2026-06-22 11:55:31','2026-06-22 11:55:31'),
	(7,'Ikan Goreng','protein',2500.00,'Menu item untuk paket custom',1,'2026-06-22 11:55:31','2026-06-22 11:55:31'),
	(8,'Udang Goreng','protein',6000.00,'Menu item untuk paket custom',1,'2026-06-22 11:55:31','2026-06-22 11:55:31'),
	(9,'Cumi Goreng','protein',5000.00,'Menu item untuk paket custom',1,'2026-06-22 11:55:31','2026-06-22 11:55:31'),
	(10,'Telur Dadar','protein',1000.00,'Menu item untuk paket custom',1,'2026-06-22 11:55:31','2026-06-22 11:55:31'),
	(11,'Perkedel Kentang','vegetable',0.00,'Menu item untuk paket custom',1,'2026-06-22 11:55:31','2026-06-22 11:55:31'),
	(12,'Tempe Goreng','vegetable',0.00,'Menu item untuk paket custom',1,'2026-06-22 11:55:31','2026-06-22 11:55:31'),
	(13,'Tempe Orek','vegetable',500.00,'Menu item untuk paket custom',1,'2026-06-22 11:55:31','2026-06-22 11:55:31'),
	(14,'Tahu Goreng','vegetable',0.00,'Menu item untuk paket custom',1,'2026-06-22 11:55:31','2026-06-22 11:55:31'),
	(15,'Tumis Brokoli','vegetable',1500.00,'Menu item untuk paket custom',1,'2026-06-22 11:55:31','2026-06-22 11:55:31'),
	(16,'Sayur Buncis Goreng','vegetable',1500.00,'Menu item untuk paket custom',1,'2026-06-22 11:55:31','2026-06-22 11:55:31'),
	(17,'Cabai Goreng','vegetable',1000.00,'Menu item untuk paket custom',1,'2026-06-22 11:55:31','2026-06-22 11:55:31'),
	(18,'Acar / Asinan','vegetable',0.00,'Menu item untuk paket custom',1,'2026-06-22 11:55:31','2026-06-22 11:55:31'),
	(19,'Lalapan Segar','vegetable',1000.00,'Menu item untuk paket custom',1,'2026-06-22 11:55:31','2026-06-22 11:55:31'),
	(20,'Sambal Goreng Ati','soup',1000.00,'Menu item untuk paket custom',1,'2026-06-22 11:55:31','2026-06-22 11:55:31'),
	(21,'Sambal Goreng Telur Puyuh','soup',1500.00,'Menu item untuk paket custom',1,'2026-06-22 11:55:31','2026-06-22 11:55:31'),
	(22,'Kurma Ayam','soup',2000.00,'Menu item untuk paket custom',1,'2026-06-22 11:55:31','2026-06-22 11:55:31'),
	(23,'Lodeh Sayuran','soup',0.00,'Menu item untuk paket custom',1,'2026-06-22 11:55:31','2026-06-22 11:55:31'),
	(24,'Kuah Kental','soup',1000.00,'Menu item untuk paket custom',1,'2026-06-22 11:55:31','2026-06-22 11:55:31'),
	(25,'Sambal Matah','condiment',0.00,'Menu item untuk paket custom',1,'2026-06-22 11:55:31','2026-06-22 11:55:31'),
	(26,'Sambal Kencur','condiment',0.00,'Menu item untuk paket custom',1,'2026-06-22 11:55:31','2026-06-22 11:55:31'),
	(27,'Kecap Manis','condiment',0.00,'Menu item untuk paket custom',1,'2026-06-22 11:55:31','2026-06-22 11:55:31'),
	(28,'Kerupuk','condiment',0.00,'Menu item untuk paket custom',1,'2026-06-22 11:55:31','2026-06-22 11:55:31'),
	(29,'Buah Potong Segar','dessert',2000.00,'Menu item untuk paket custom',1,'2026-06-22 11:55:31','2026-06-22 11:55:31'),
	(30,'Puding Rasa Buah','dessert',2500.00,'Menu item untuk paket custom',1,'2026-06-22 11:55:31','2026-06-22 11:55:31'),
	(31,'Kue Lapis','dessert',1500.00,'Menu item untuk paket custom',1,'2026-06-22 11:55:31','2026-06-22 11:55:31'),
	(32,'Brownies Homemade','dessert',2000.00,'Menu item untuk paket custom',1,'2026-06-22 11:55:31','2026-06-22 11:55:31'),
	(33,'Onde-onde','dessert',1500.00,'Menu item untuk paket custom',1,'2026-06-22 11:55:31','2026-06-22 11:55:31'),
	(34,'Air Mineral 600ml','beverage',2000.00,'Menu item untuk paket custom',1,'2026-06-22 11:55:31','2026-06-22 11:55:31'),
	(35,'Teh Kotak','beverage',2000.00,'Menu item untuk paket custom',1,'2026-06-22 11:55:31','2026-06-22 11:55:31'),
	(36,'Jus Segar','beverage',3000.00,'Menu item untuk paket custom',1,'2026-06-22 11:55:31','2026-06-22 11:55:31');

/*!40000 ALTER TABLE `items` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table job_batches
# ------------------------------------------------------------

DROP TABLE IF EXISTS `job_batches`;

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
  `finished_at` int DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



# Dump of table jobs
# ------------------------------------------------------------

DROP TABLE IF EXISTS `jobs`;

CREATE TABLE `jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `queue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` smallint unsigned NOT NULL,
  `reserved_at` int unsigned DEFAULT NULL,
  `available_at` int unsigned NOT NULL,
  `created_at` int unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_index` (`queue`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



# Dump of table migrations
# ------------------------------------------------------------

DROP TABLE IF EXISTS `migrations`;

CREATE TABLE `migrations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;

INSERT INTO `migrations` (`id`, `migration`, `batch`)
VALUES
	(1,'0001_01_01_000000_create_users_table',1),
	(2,'0001_01_01_000001_create_cache_table',1),
	(3,'0001_01_01_000002_create_jobs_table',1),
	(4,'2024_01_01_000002_create_packages_table',1),
	(5,'2024_01_01_000003_create_orders_table',1),
	(6,'2024_01_01_000004_create_payments_table',1),
	(7,'2024_01_01_000005_create_reviews_blocked_dates_table',1),
	(8,'2026_05_23_021021_add_role_phone_to_users_table',1),
	(9,'2026_05_23_030000_create_pages_table',1),
	(10,'2026_06_12_000001_add_custom_fields_to_orders_table',2),
	(11,'2026_06_12_000002_create_items_table',2),
	(12,'2026_06_12_000003_create_item_package_table',2),
	(13,'2026_06_12_000004_create_order_line_items_table',2),
	(14,'2026_07_02_000001_add_confirmation_fields_to_orders_table',3),
	(15,'2026_08_13_133354_add_kecamatan_kelurahan_to_orders_table',4);

/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table notifications_log
# ------------------------------------------------------------

DROP TABLE IF EXISTS `notifications_log`;

CREATE TABLE `notifications_log` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `message` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `link` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `read_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `notifications_log_user_id_foreign` (`user_id`),
  CONSTRAINT `notifications_log_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



# Dump of table order_line_items
# ------------------------------------------------------------

DROP TABLE IF EXISTS `order_line_items`;

CREATE TABLE `order_line_items` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `order_id` bigint unsigned NOT NULL,
  `package_id` bigint unsigned NOT NULL,
  `item_id` bigint unsigned NOT NULL,
  `item_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `category` enum('lauk','minuman','buah') COLLATE utf8mb4_unicode_ci NOT NULL,
  `quantity` int NOT NULL DEFAULT '1',
  `unit_price` decimal(10,2) NOT NULL,
  `additional_price` decimal(10,2) NOT NULL DEFAULT '0.00',
  `total_price` decimal(12,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `order_line_items_order_id_foreign` (`order_id`),
  KEY `order_line_items_package_id_foreign` (`package_id`),
  KEY `order_line_items_item_id_foreign` (`item_id`),
  CONSTRAINT `order_line_items_item_id_foreign` FOREIGN KEY (`item_id`) REFERENCES `items` (`id`),
  CONSTRAINT `order_line_items_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  CONSTRAINT `order_line_items_package_id_foreign` FOREIGN KEY (`package_id`) REFERENCES `packages` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



# Dump of table orders
# ------------------------------------------------------------

DROP TABLE IF EXISTS `orders`;

CREATE TABLE `orders` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `order_number` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint unsigned NOT NULL,
  `package_id` bigint unsigned NOT NULL,
  `quantity` int NOT NULL,
  `price_per_box` decimal(10,2) NOT NULL,
  `subtotal` decimal(12,2) NOT NULL,
  `addon_total` decimal(12,2) NOT NULL DEFAULT '0.00',
  `total_amount` decimal(12,2) NOT NULL,
  `dp_amount` decimal(12,2) NOT NULL,
  `remaining_amount` decimal(12,2) NOT NULL,
  `event_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `event_location` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `event_address` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `kecamatan` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `kelurahan` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `event_date` date NOT NULL,
  `delivery_time` time NOT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci,
  `is_custom` tinyint(1) NOT NULL DEFAULT '0',
  `custom_request` text COLLATE utf8mb4_unicode_ci,
  `status` enum('pending','dp_paid','confirmed','processing','delivering','delivered','completed','cancelled') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `payment_status` enum('unpaid','dp_pending','dp_paid','full_pending','fully_paid') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'unpaid',
  `payment_scheme` enum('dp','full') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `confirmed_at` timestamp NULL DEFAULT NULL,
  `selected_addons` json DEFAULT NULL,
  `contact_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `contact_phone` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `confirmed_by` bigint unsigned DEFAULT NULL,
  `admin_confirmation_notes` text COLLATE utf8mb4_unicode_ci,
  PRIMARY KEY (`id`),
  UNIQUE KEY `orders_order_number_unique` (`order_number`),
  KEY `orders_user_id_foreign` (`user_id`),
  KEY `orders_package_id_foreign` (`package_id`),
  KEY `orders_confirmed_by_foreign` (`confirmed_by`),
  CONSTRAINT `orders_confirmed_by_foreign` FOREIGN KEY (`confirmed_by`) REFERENCES `users` (`id`),
  CONSTRAINT `orders_package_id_foreign` FOREIGN KEY (`package_id`) REFERENCES `packages` (`id`),
  CONSTRAINT `orders_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

LOCK TABLES `orders` WRITE;
/*!40000 ALTER TABLE `orders` DISABLE KEYS */;

INSERT INTO `orders` (`id`, `order_number`, `user_id`, `package_id`, `quantity`, `price_per_box`, `subtotal`, `addon_total`, `total_amount`, `dp_amount`, `remaining_amount`, `event_name`, `event_location`, `event_address`, `kecamatan`, `kelurahan`, `event_date`, `delivery_time`, `notes`, `is_custom`, `custom_request`, `status`, `payment_status`, `payment_scheme`, `confirmed_at`, `selected_addons`, `contact_name`, `contact_phone`, `created_at`, `updated_at`, `confirmed_by`, `admin_confirmation_notes`)
VALUES
	(1,'RC-20260701-0001',2,1,20,35000.00,700000.00,0.00,700000.00,0.00,700000.00,'Pesanan Katering Paket Acara 1','Lokasi Pengantaran','test',NULL,NULL,'2026-07-03','00:51:00','test',0,NULL,'confirmed','full_pending','full','2026-07-01 17:08:04','[]','Budi Santoso','085752924301','2026-07-01 16:52:01','2026-07-01 17:08:09',1,NULL),
	(2,'RC-20260701-0002',2,1,20,35000.00,700000.00,0.00,700000.00,0.00,700000.00,'Pesanan Katering Paket Acara 1','Lokasi Pengantaran','test',NULL,NULL,'2026-07-03','01:10:00','test',0,NULL,'confirmed','full_pending','full','2026-07-01 17:11:13','[]','Budi Santoso','085752924301','2026-07-01 17:10:43','2026-07-01 17:11:27',1,NULL),
	(3,'RC-20260701-0003',2,1,20,35000.00,700000.00,0.00,700000.00,350000.00,350000.00,'Pesanan Katering Paket Acara 1','Lokasi Pengantaran','test',NULL,NULL,'2026-07-03','01:11:00','tes',0,NULL,'confirmed','dp_pending','dp','2026-07-01 17:12:29','[]','Budi Santoso','081908455473','2026-07-01 17:12:02','2026-07-01 17:13:00',1,NULL),
	(4,'RC-20260712-0001',2,1,20,35000.00,700000.00,0.00,700000.00,0.00,700000.00,'Pesanan Katering Paket Acara 1','Lokasi Pengantaran','test',NULL,NULL,'2026-07-13','21:04:00','test',0,NULL,'confirmed','full_pending','full','2026-07-12 13:04:42','[]','Budi Santoso','08190748842','2026-07-12 13:04:32','2026-07-12 13:04:48',1,NULL),
	(5,'RC-20260803-0001',2,1,20,35000.00,700000.00,0.00,700000.00,0.00,700000.00,'Pesanan Katering Paket Acara 1','Lokasi Pengantaran','test',NULL,NULL,'2026-08-04','14:43:00','test',0,NULL,'completed','fully_paid','full','2026-08-03 06:43:51','[]','Budi Santoso','081908455473','2026-08-03 06:43:37','2026-08-13 13:30:51',1,NULL),
	(6,'RC-20260813-0001',2,1,20,35000.00,700000.00,0.00,700000.00,0.00,700000.00,'Pesanan Katering Paket Acara 1','Lokasi Pengantaran','test',NULL,NULL,'2026-08-14','21:19:00','test',0,NULL,'completed','fully_paid','full','2026-08-13 13:20:01','[]','Budi Santoso','081908455741','2026-08-13 13:19:36','2026-08-13 13:22:27',1,NULL),
	(7,'RC-20260813-0002',2,1,20,35000.00,700000.00,0.00,700000.00,0.00,700000.00,'Pesanan Katering Paket Acara 1','Lokasi Pengantaran','test',NULL,NULL,'2026-08-15','21:22:00','test',0,NULL,'completed','fully_paid','full','2026-08-13 13:22:41','[]','Budi Santoso','test','2026-08-13 13:22:20','2026-08-13 13:38:50',1,NULL),
	(8,'RC-20260813-0003',2,1,20,35000.00,700000.00,0.00,700000.00,0.00,700000.00,'Pesanan Katering Paket Acara 1','Lokasi Pengantaran','test',NULL,NULL,'2026-08-14','21:26:00','test',0,NULL,'completed','fully_paid','full','2026-08-13 13:26:24','[]','Budi Santoso','081908455741','2026-08-13 13:26:13','2026-08-13 13:26:35',1,NULL),
	(9,'RC-20260813-0004',3,1,54,35000.00,1890000.00,0.00,1890000.00,945000.00,945000.00,'Fuga quia qui.','Gedung Fa Aryani Marbun (Persero) Tbk','Jr. Baranang Siang Indah No. 250, Bukittinggi 80947, NTT','Samarinda Ilir','Sidodamai','2026-07-26','20:07:00','Maxime soluta sunt eos ea doloremque ut enim officiis.',0,NULL,'dp_paid','dp_paid',NULL,NULL,NULL,'Umaya Purwa Firgantoro S.Ked','0878 0480 8750','2026-04-15 13:38:09','2026-08-13 13:38:54',NULL,NULL),
	(10,'RC-20260813-0005',3,3,27,45000.00,1215000.00,0.00,1215000.00,607500.00,607500.00,'Hic magni possimus qui quo.','Gedung CV Kuswandari Mulyani (Persero) Tbk','Kpg. Jakarta No. 123, Sorong 32622, Babel','Samarinda Kota','Bugis','2025-11-06','04:24:00',NULL,0,NULL,'delivering','dp_paid',NULL,NULL,NULL,'Umaya Purwa Firgantoro S.Ked','0878 0480 8750','2026-01-08 02:56:14','2026-08-13 13:38:54',NULL,NULL),
	(11,'RC-20260813-0006',3,3,31,45000.00,1395000.00,0.00,1395000.00,697500.00,697500.00,'Voluptas sit.','Gedung CV Hariyah Kusumo Tbk','Psr. Imam No. 777, Pontianak 90153, NTB','Sungai Kunjang','Loa Buah','2026-04-15','12:55:00',NULL,0,NULL,'completed','fully_paid',NULL,NULL,NULL,'Umaya Purwa Firgantoro S.Ked','0878 0480 8750','2025-12-30 07:53:42','2026-08-13 13:38:54',NULL,NULL),
	(12,'RC-20260813-0007',3,2,89,55000.00,4895000.00,0.00,4895000.00,2447500.00,2447500.00,'Est iusto harum.','Gedung CV Hastuti Lestari Tbk','Ds. Jend. A. Yani No. 398, Denpasar 83741, Bali','Samarinda Utara','Sempaja Selatan','2025-10-03','14:32:00','Eius vel qui praesentium ea ipsum modi fuga.',0,NULL,'completed','fully_paid',NULL,NULL,NULL,'Umaya Purwa Firgantoro S.Ked','0878 0480 8750','2025-08-14 21:24:14','2026-08-13 13:38:54',NULL,NULL),
	(13,'RC-20260813-0008',3,1,64,35000.00,2240000.00,0.00,2240000.00,1120000.00,1120000.00,'Doloribus odit.','Gedung UD Habibi Ramadan (Persero) Tbk','Psr. Babadak No. 278, Cirebon 56509, Sumut','Palaran','Rawa Makmur','2026-06-25','06:03:00',NULL,0,NULL,'pending','unpaid',NULL,NULL,NULL,'Umaya Purwa Firgantoro S.Ked','0878 0480 8750','2026-04-01 04:45:10','2026-08-13 13:38:54',NULL,NULL),
	(14,'RC-20260813-0009',3,3,60,45000.00,2700000.00,0.00,2700000.00,1350000.00,1350000.00,'Asperiores voluptatem quia veritatis.','Gedung Perum Haryanti Tbk','Gg. Jagakarsa No. 897, Banjar 30797, Aceh','Sungai Kunjang','Karang Asam Ilir','2026-08-19','20:05:00',NULL,0,NULL,'delivered','fully_paid',NULL,NULL,NULL,'Umaya Purwa Firgantoro S.Ked','0878 0480 8750','2026-04-29 19:46:48','2026-08-13 13:38:54',NULL,NULL),
	(15,'RC-20260813-0010',3,3,39,45000.00,1755000.00,0.00,1755000.00,877500.00,877500.00,'Voluptatem itaque voluptatem.','Gedung PT Widodo Mardhiyah','Dk. Supomo No. 859, Banda Aceh 12780, Sulteng','Palaran','Bantuas','2025-12-10','19:50:00',NULL,0,NULL,'delivered','fully_paid',NULL,NULL,NULL,'Umaya Purwa Firgantoro S.Ked','0878 0480 8750','2026-07-09 22:50:26','2026-08-13 13:38:54',NULL,NULL),
	(16,'RC-20260813-0011',3,3,51,45000.00,2295000.00,0.00,2295000.00,1147500.00,1147500.00,'Exercitationem culpa illo vitae.','Gedung PT Hasanah Tbk','Jln. Lada No. 555, Tanjung Pinang 45288, Aceh','Sungai Kunjang','Loa Bakung','2026-04-29','13:56:00',NULL,0,NULL,'dp_paid','dp_paid',NULL,NULL,NULL,'Umaya Purwa Firgantoro S.Ked','0878 0480 8750','2025-11-22 18:59:12','2026-08-13 13:38:54',NULL,NULL),
	(17,'RC-20260813-0012',3,3,47,45000.00,2115000.00,0.00,2115000.00,1057500.00,1057500.00,'Nihil suscipit numquam.','Gedung PJ Widiastuti Wibisono Tbk','Psr. Mahakam No. 772, Padang 60441, Riau','Samarinda Utara','Tanah Merah','2026-03-31','11:55:00',NULL,0,NULL,'completed','fully_paid',NULL,NULL,NULL,'Umaya Purwa Firgantoro S.Ked','0878 0480 8750','2025-09-25 20:25:33','2026-08-13 13:38:54',NULL,NULL),
	(18,'RC-20260813-0013',3,2,76,55000.00,4180000.00,0.00,4180000.00,2090000.00,2090000.00,'Reiciendis officia et et.','Gedung Fa Nasyiah Hariyah','Psr. Sumpah Pemuda No. 904, Probolinggo 74356, Pabar','Sambutan','Sungai Kapih','2026-01-20','00:51:00','Facere quas vel ullam tenetur.',0,NULL,'processing','dp_paid',NULL,NULL,NULL,'Umaya Purwa Firgantoro S.Ked','0878 0480 8750','2025-12-16 19:48:12','2026-08-13 13:38:54',NULL,NULL),
	(19,'RC-20260813-0014',3,3,37,45000.00,1665000.00,0.00,1665000.00,832500.00,832500.00,'Iste hic et non voluptatum.','Gedung PD Wahyudin (Persero) Tbk','Gg. Hasanuddin No. 924, Sorong 17986, Sulsel','Samarinda Kota','Sungai Pinang Luar','2026-07-13','04:51:00','Deleniti sit recusandae beatae odio facere.',0,NULL,'dp_paid','dp_paid',NULL,NULL,NULL,'Umaya Purwa Firgantoro S.Ked','0878 0480 8750','2026-08-01 18:16:51','2026-08-13 13:38:54',NULL,NULL),
	(20,'RC-20260813-0015',3,1,37,35000.00,1295000.00,0.00,1295000.00,647500.00,647500.00,'Quas illum est.','Gedung UD Anggriawan Oktaviani','Kpg. Dr. Junjunan No. 199, Lhokseumawe 41056, Kalsel','Sambutan','Pulau Atas','2025-12-05','15:52:00',NULL,0,NULL,'processing','dp_paid',NULL,NULL,NULL,'Umaya Purwa Firgantoro S.Ked','0878 0480 8750','2026-08-02 17:51:37','2026-08-13 13:38:54',NULL,NULL),
	(21,'RC-20260813-0016',3,4,49,40000.00,1960000.00,0.00,1960000.00,980000.00,980000.00,'Deleniti est eius explicabo.','Gedung PJ Zulaika (Persero) Tbk','Gg. Baranang No. 231, Bekasi 45331, Papua','Loa Janan Ilir','Tani Aman','2026-03-25','07:41:00',NULL,0,NULL,'pending','unpaid',NULL,NULL,NULL,'Umaya Purwa Firgantoro S.Ked','0878 0480 8750','2026-07-04 14:38:40','2026-08-13 13:38:54',NULL,NULL),
	(22,'RC-20260813-0017',3,1,46,35000.00,1610000.00,0.00,1610000.00,805000.00,805000.00,'Nisi non sint temporibus.','Gedung PJ Hasanah','Kpg. Katamso No. 742, Sibolga 90465, Gorontalo','Samarinda Ulu','Dadi Mulya','2026-04-11','03:06:00',NULL,0,NULL,'cancelled','unpaid',NULL,NULL,NULL,'Umaya Purwa Firgantoro S.Ked','0878 0480 8750','2025-11-16 01:45:48','2026-08-13 13:38:54',NULL,NULL),
	(23,'RC-20260813-0018',3,2,97,55000.00,5335000.00,0.00,5335000.00,2667500.00,2667500.00,'Reprehenderit labore provident aut.','Gedung PD Yuliarti Pratama','Dk. Agus Salim No. 396, Pariaman 64857, Sulbar','Samarinda Seberang','Baqa','2025-12-26','01:46:00','Aut et harum voluptas minima.',0,NULL,'pending','unpaid',NULL,NULL,NULL,'Umaya Purwa Firgantoro S.Ked','0878 0480 8750','2026-02-07 23:59:36','2026-08-13 13:38:54',NULL,NULL),
	(24,'RC-20260813-0019',4,4,11,40000.00,440000.00,0.00,440000.00,220000.00,220000.00,'Sit saepe tempore eaque.','Gedung PT Yolanda','Kpg. Halim No. 981, Samarinda 33345, Jatim','Palaran','Bantuas','2026-03-29','17:02:00','Ex tenetur omnis aut.',0,NULL,'delivering','dp_paid',NULL,NULL,NULL,'Victoria Mulyani S.Psi','(+62) 567 6772 265','2026-03-21 09:11:33','2026-08-13 13:38:54',NULL,NULL),
	(25,'RC-20260813-0020',4,3,30,45000.00,1350000.00,0.00,1350000.00,675000.00,675000.00,'Qui non doloribus.','Gedung CV Mandasari','Gg. Salatiga No. 472, Serang 76879, Kepri','Sungai Kunjang','Karang Asam Ilir','2025-11-05','00:15:00','Corrupti itaque magnam aut voluptas et.',0,NULL,'dp_paid','dp_paid',NULL,NULL,NULL,'Victoria Mulyani S.Psi','(+62) 567 6772 265','2026-05-24 17:22:56','2026-08-13 13:38:54',NULL,NULL),
	(26,'RC-20260813-0021',4,4,50,40000.00,2000000.00,0.00,2000000.00,1000000.00,1000000.00,'Ut facilis eius.','Gedung PJ Pranowo Tbk','Jr. Jayawijaya No. 930, Bekasi 42337, Sulut','Sungai Kunjang','Karang Asam Ulu','2025-12-04','21:12:00','Vel quod alias explicabo neque temporibus aspernatur.',0,NULL,'pending','unpaid',NULL,NULL,NULL,'Victoria Mulyani S.Psi','(+62) 567 6772 265','2025-11-21 06:45:55','2026-08-13 13:38:54',NULL,NULL),
	(27,'RC-20260813-0022',4,2,88,55000.00,4840000.00,0.00,4840000.00,2420000.00,2420000.00,'Eos rem omnis eius.','Gedung PT Hartati Laksmiwati','Jr. Agus Salim No. 454, Subulussalam 74251, Aceh','Sungai Pinang','Temindung Permai','2026-02-19','09:30:00',NULL,0,NULL,'dp_paid','dp_paid',NULL,NULL,NULL,'Victoria Mulyani S.Psi','(+62) 567 6772 265','2026-01-13 12:23:14','2026-08-13 13:38:54',NULL,NULL),
	(28,'RC-20260813-0023',4,2,96,55000.00,5280000.00,0.00,5280000.00,2640000.00,2640000.00,'Molestiae est dignissimos.','Gedung CV Sihombing Riyanti (Persero) Tbk','Ki. R.E. Martadinata No. 160, Sibolga 56476, Kalteng','Samarinda Kota','Pelabuhan','2025-12-04','17:58:00','Consequatur perspiciatis saepe sed error fugit perspiciatis enim.',0,NULL,'processing','dp_paid',NULL,NULL,NULL,'Victoria Mulyani S.Psi','(+62) 567 6772 265','2026-06-16 15:30:48','2026-08-13 13:38:54',NULL,NULL),
	(29,'RC-20260813-0024',4,3,53,45000.00,2385000.00,0.00,2385000.00,1192500.00,1192500.00,'Et consequatur illo veritatis ad.','Gedung Yayasan Pangestu Tbk','Jln. Juanda No. 815, Bogor 31904, DKI','Samarinda Kota','Sungai Pinang Luar','2025-09-13','10:37:00','Enim enim distinctio quam commodi.',0,NULL,'processing','dp_paid',NULL,NULL,NULL,'Victoria Mulyani S.Psi','(+62) 567 6772 265','2025-10-08 10:40:00','2026-08-13 13:38:54',NULL,NULL),
	(30,'RC-20260813-0025',4,2,66,55000.00,3630000.00,0.00,3630000.00,1815000.00,1815000.00,'Occaecati a id quia.','Gedung PJ Anggraini Rahayu','Psr. Villa No. 28, Palopo 47550, Riau','Palaran','Rawa Makmur','2025-12-01','21:18:00','Voluptate quia labore voluptatem occaecati dolorum blanditiis.',0,NULL,'pending','unpaid',NULL,NULL,NULL,'Victoria Mulyani S.Psi','(+62) 567 6772 265','2025-11-05 01:03:06','2026-08-13 13:38:54',NULL,NULL),
	(31,'RC-20260813-0026',4,2,96,55000.00,5280000.00,0.00,5280000.00,2640000.00,2640000.00,'Quisquam nam ullam.','Gedung Perum Firgantoro Siregar (Persero) Tbk','Gg. Imam No. 911, Depok 55902, Sulsel','Samarinda Ulu','Teluk Lerong Ilir','2025-12-11','18:31:00','Quos sunt voluptate itaque molestias.',0,NULL,'dp_paid','dp_paid',NULL,NULL,NULL,'Victoria Mulyani S.Psi','(+62) 567 6772 265','2026-03-30 21:13:12','2026-08-13 13:38:54',NULL,NULL),
	(32,'RC-20260813-0027',4,4,50,40000.00,2000000.00,0.00,2000000.00,1000000.00,1000000.00,'Ullam ducimus vel tempore.','Gedung Fa Astuti','Dk. Raden No. 126, Bengkulu 34323, Sultra','Samarinda Seberang','Gunung Panjang','2025-12-30','17:43:00','Quis et nemo temporibus corrupti quibusdam.',0,NULL,'dp_paid','dp_paid',NULL,NULL,NULL,'Victoria Mulyani S.Psi','(+62) 567 6772 265','2026-03-19 06:23:12','2026-08-13 13:38:54',NULL,NULL),
	(33,'RC-20260813-0028',4,1,21,35000.00,735000.00,0.00,735000.00,367500.00,367500.00,'Voluptas autem autem.','Gedung Yayasan Samosir','Jr. B.Agam 1 No. 798, Jayapura 43375, Bengkulu','Samarinda Seberang','Gunung Panjang','2026-03-07','21:30:00',NULL,0,NULL,'confirmed','dp_paid',NULL,NULL,NULL,'Victoria Mulyani S.Psi','(+62) 567 6772 265','2026-04-30 11:45:13','2026-08-13 13:38:54',NULL,NULL),
	(34,'RC-20260813-0029',5,2,90,55000.00,4950000.00,0.00,4950000.00,2475000.00,2475000.00,'Distinctio repudiandae.','Gedung Fa Agustina Safitri','Dk. Ciumbuleuit No. 846, Depok 74870, Kalbar','Loa Janan Ilir','Simpang Tiga','2026-02-24','17:07:00','Ut sunt est voluptatem ut molestias.',0,NULL,'cancelled','unpaid',NULL,NULL,NULL,'Syahrini Padmasari','0400 3746 4492','2025-08-14 16:32:59','2026-08-13 13:38:55',NULL,NULL),
	(35,'RC-20260813-0030',5,2,81,55000.00,4455000.00,0.00,4455000.00,2227500.00,2227500.00,'Et ducimus ipsa.','Gedung Yayasan Lazuardi (Persero) Tbk','Psr. PHH. Mustofa No. 574, Bengkulu 15173, Jabar','Samarinda Ulu','Gunung Kelua','2026-03-25','07:39:00',NULL,0,NULL,'completed','fully_paid',NULL,NULL,NULL,'Syahrini Padmasari','0400 3746 4492','2025-11-28 19:00:59','2026-08-13 13:38:55',NULL,NULL),
	(36,'RC-20260813-0031',5,2,77,55000.00,4235000.00,0.00,4235000.00,2117500.00,2117500.00,'Possimus aut et.','Gedung PD Haryanti (Persero) Tbk','Ds. Jend. Sudirman No. 950, Tegal 55776, DIY','Samarinda Utara','Tanah Merah','2026-01-21','12:26:00',NULL,0,NULL,'confirmed','dp_paid',NULL,NULL,NULL,'Syahrini Padmasari','0400 3746 4492','2026-05-21 07:49:34','2026-08-13 13:38:55',NULL,NULL),
	(37,'RC-20260813-0032',5,2,73,55000.00,4015000.00,0.00,4015000.00,2007500.00,2007500.00,'Eum dolorem ipsa.','Gedung Fa Purnawati (Persero) Tbk','Ds. Ujung No. 714, Banjarmasin 40120, Gorontalo','Samarinda Ulu','Bukit Pinang','2025-09-16','20:11:00',NULL,0,NULL,'completed','fully_paid',NULL,NULL,NULL,'Syahrini Padmasari','0400 3746 4492','2025-11-22 09:47:33','2026-08-13 13:38:55',NULL,NULL),
	(38,'RC-20260813-0033',5,4,35,40000.00,1400000.00,0.00,1400000.00,700000.00,700000.00,'Aliquam rerum et sed.','Gedung PJ Purwanti Santoso Tbk','Jr. Kebonjati No. 810, Tual 99862, NTT','Palaran','Simpang Pasir','2026-03-03','11:14:00',NULL,0,NULL,'delivered','fully_paid',NULL,NULL,NULL,'Syahrini Padmasari','0400 3746 4492','2026-08-08 07:51:34','2026-08-13 13:38:55',NULL,NULL),
	(39,'RC-20260813-0034',5,1,41,35000.00,1435000.00,0.00,1435000.00,717500.00,717500.00,'Ea nobis quaerat laborum ipsum.','Gedung Fa Wijaya Yuniar','Ds. Supomo No. 348, Tebing Tinggi 25446, Sulbar','Samarinda Utara','Sempaja Timur','2026-06-26','06:37:00',NULL,0,NULL,'confirmed','dp_paid',NULL,NULL,NULL,'Syahrini Padmasari','0400 3746 4492','2026-05-19 02:55:18','2026-08-13 13:38:55',NULL,NULL),
	(40,'RC-20260813-0035',5,3,21,45000.00,945000.00,0.00,945000.00,472500.00,472500.00,'Consequatur et omnis dignissimos.','Gedung PD Pertiwi Tbk','Ki. Ters. Jakarta No. 690, Kotamobagu 16570, Babel','Samarinda Ulu','Jawa','2026-02-08','15:09:00',NULL,0,NULL,'processing','dp_paid',NULL,NULL,NULL,'Syahrini Padmasari','0400 3746 4492','2026-07-20 22:34:53','2026-08-13 13:38:55',NULL,NULL),
	(41,'RC-20260813-0036',5,4,20,40000.00,800000.00,0.00,800000.00,400000.00,400000.00,'Dolorum atque est.','Gedung Perum Habibi Pertiwi Tbk','Jln. Baranang No. 993, Payakumbuh 82871, Pabar','Samarinda Ilir','Selili','2026-07-28','08:18:00',NULL,0,NULL,'delivering','dp_paid',NULL,NULL,NULL,'Syahrini Padmasari','0400 3746 4492','2026-01-14 01:15:33','2026-08-13 13:38:55',NULL,NULL),
	(42,'RC-20260813-0037',5,3,45,45000.00,2025000.00,0.00,2025000.00,1012500.00,1012500.00,'Qui reiciendis ab aliquam assumenda.','Gedung PD Sihombing Nasyidah','Kpg. Kyai Mojo No. 315, Tanjungbalai 22952, Papua','Sambutan','Sindang Sari','2025-09-21','03:10:00',NULL,0,NULL,'processing','dp_paid',NULL,NULL,NULL,'Syahrini Padmasari','0400 3746 4492','2026-03-18 10:45:46','2026-08-13 13:38:55',NULL,NULL),
	(43,'RC-20260813-0038',5,2,68,55000.00,3740000.00,0.00,3740000.00,1870000.00,1870000.00,'Veniam autem.','Gedung PJ Prastuti Tbk','Psr. Cikutra Barat No. 287, Parepare 56840, Maluku','Sungai Pinang','Sungai Pinang Dalam','2026-08-30','18:00:00',NULL,0,NULL,'delivered','fully_paid',NULL,NULL,NULL,'Syahrini Padmasari','0400 3746 4492','2025-12-21 01:00:14','2026-08-13 13:38:55',NULL,NULL),
	(44,'RC-20260813-0039',5,1,34,35000.00,1190000.00,0.00,1190000.00,595000.00,595000.00,'Qui totam molestias.','Gedung Fa Hutagalung','Psr. Sudirman No. 641, Kediri 23574, Jambi','Samarinda Ilir','Sidodamai','2025-12-05','04:41:00',NULL,0,NULL,'pending','unpaid',NULL,NULL,NULL,'Syahrini Padmasari','0400 3746 4492','2025-08-18 04:56:12','2026-08-13 13:38:55',NULL,NULL),
	(45,'RC-20260813-0040',5,1,45,35000.00,1575000.00,0.00,1575000.00,787500.00,787500.00,'Occaecati dolorem voluptatem.','Gedung Perum Ramadan Siregar Tbk','Psr. Babadak No. 535, Batam 20524, Jabar','Samarinda Ulu','Teluk Lerong Ilir','2026-03-28','15:37:00',NULL,0,NULL,'completed','fully_paid',NULL,NULL,NULL,'Syahrini Padmasari','0400 3746 4492','2026-01-26 06:25:22','2026-08-13 13:38:55',NULL,NULL),
	(46,'RC-20260813-0041',5,2,73,55000.00,4015000.00,0.00,4015000.00,2007500.00,2007500.00,'Omnis labore esse esse.','Gedung Yayasan Sudiati Sihombing','Psr. Banda No. 669, Tomohon 89325, NTT','Samarinda Ulu','Teluk Lerong Ilir','2026-01-06','21:50:00',NULL,0,NULL,'delivered','fully_paid',NULL,NULL,NULL,'Syahrini Padmasari','0400 3746 4492','2026-07-13 23:16:53','2026-08-13 13:38:55',NULL,NULL),
	(47,'RC-20260813-0042',5,4,43,40000.00,1720000.00,0.00,1720000.00,860000.00,860000.00,'Eius dignissimos esse.','Gedung PJ Thamrin Januar (Persero) Tbk','Dk. Arifin No. 72, Sukabumi 71175, Lampung','Palaran','Handil Bakti','2025-11-18','06:02:00',NULL,0,NULL,'delivered','fully_paid',NULL,NULL,NULL,'Syahrini Padmasari','0400 3746 4492','2026-03-05 16:24:03','2026-08-13 13:38:55',NULL,NULL),
	(48,'RC-20260813-0043',5,4,19,40000.00,760000.00,0.00,760000.00,380000.00,380000.00,'Mollitia assumenda voluptatibus.','Gedung Yayasan Pradana (Persero) Tbk','Ds. Otista No. 706, Kupang 85604, Sultra','Samarinda Ulu','Air Hitam','2026-07-18','08:38:00','Libero sit voluptas sed nam voluptas doloribus illo.',0,NULL,'confirmed','dp_paid',NULL,NULL,NULL,'Syahrini Padmasari','0400 3746 4492','2025-09-01 01:03:27','2026-08-13 13:38:55',NULL,NULL),
	(49,'RC-20260813-0044',6,4,30,40000.00,1200000.00,0.00,1200000.00,600000.00,600000.00,'Itaque tempore.','Gedung PD Gunarto','Ki. Ujung No. 365, Binjai 50324, Kepri','Samarinda Utara','Budaya Pampang','2025-11-27','12:24:00','Non dolorem eum voluptates repellat at rerum.',0,NULL,'processing','dp_paid',NULL,NULL,NULL,'Jane Ratna Nasyidah','(+62) 506 7360 6693','2025-11-13 21:33:07','2026-08-13 13:38:55',NULL,NULL),
	(50,'RC-20260813-0045',6,3,27,45000.00,1215000.00,0.00,1215000.00,607500.00,607500.00,'Qui sit.','Gedung Perum Hasanah','Jln. Baiduri No. 983, Ambon 57484, Kalteng','Samarinda Kota','Pelabuhan','2026-05-18','06:40:00','Odio nemo ut reprehenderit aut eius.',0,NULL,'delivering','dp_paid',NULL,NULL,NULL,'Jane Ratna Nasyidah','(+62) 506 7360 6693','2026-03-08 05:43:13','2026-08-13 13:38:55',NULL,NULL),
	(51,'RC-20260813-0046',6,2,72,55000.00,3960000.00,0.00,3960000.00,1980000.00,1980000.00,'Incidunt soluta accusantium.','Gedung Perum Rahimah Iswahyudi Tbk','Psr. Antapani Lama No. 495, Banjar 54595, Jabar','Samarinda Utara','Budaya Pampang','2026-08-31','20:35:00',NULL,0,NULL,'confirmed','dp_paid',NULL,NULL,NULL,'Jane Ratna Nasyidah','(+62) 506 7360 6693','2026-03-29 15:19:07','2026-08-13 13:38:55',NULL,NULL),
	(52,'RC-20260813-0047',6,2,79,55000.00,4345000.00,0.00,4345000.00,2172500.00,2172500.00,'Harum voluptatem dolor voluptatibus.','Gedung Fa Prasetya (Persero) Tbk','Kpg. Mulyadi No. 656, Administrasi Jakarta Selatan 72831, Jambi','Samarinda Ulu','Dadi Mulya','2025-09-15','21:38:00',NULL,0,NULL,'pending','unpaid',NULL,NULL,NULL,'Jane Ratna Nasyidah','(+62) 506 7360 6693','2025-10-01 20:59:01','2026-08-13 13:38:55',NULL,NULL),
	(53,'RC-20260813-0048',6,4,30,40000.00,1200000.00,0.00,1200000.00,600000.00,600000.00,'Cumque unde aperiam et.','Gedung Fa Pudjiastuti Tbk','Jln. Veteran No. 125, Dumai 70703, Kalsel','Samarinda Ulu','Gunung Kelua','2026-05-04','04:54:00','Voluptatibus voluptatum aut perferendis ut.',0,NULL,'confirmed','dp_paid',NULL,NULL,NULL,'Jane Ratna Nasyidah','(+62) 506 7360 6693','2025-12-15 06:47:10','2026-08-13 13:38:55',NULL,NULL),
	(54,'RC-20260813-0049',6,2,90,55000.00,4950000.00,0.00,4950000.00,2475000.00,2475000.00,'Eos natus.','Gedung PJ Uwais (Persero) Tbk','Jln. Bhayangkara No. 28, Administrasi Jakarta Utara 89580, Riau','Loa Janan Ilir','Tani Aman','2026-02-14','22:34:00',NULL,0,NULL,'delivering','dp_paid',NULL,NULL,NULL,'Jane Ratna Nasyidah','(+62) 506 7360 6693','2026-06-02 19:40:00','2026-08-13 13:38:55',NULL,NULL),
	(55,'RC-20260813-0050',6,3,16,45000.00,720000.00,0.00,720000.00,360000.00,360000.00,'Eum iure.','Gedung Perum Nasyidah (Persero) Tbk','Gg. Tambak No. 279, Cimahi 74007, Kalbar','Sungai Kunjang','Teluk Lerong Ulu','2026-07-23','00:14:00','Dolor est est non itaque dolor quas fuga.',0,NULL,'completed','fully_paid',NULL,NULL,NULL,'Jane Ratna Nasyidah','(+62) 506 7360 6693','2026-04-28 08:36:06','2026-08-13 13:38:55',NULL,NULL),
	(56,'RC-20260813-0051',6,2,64,55000.00,3520000.00,0.00,3520000.00,1760000.00,1760000.00,'Accusantium maiores velit sit.','Gedung UD Riyanti (Persero) Tbk','Dk. Peta No. 11, Medan 81364, Bengkulu','Samarinda Utara','Sempaja Timur','2026-01-20','06:53:00','Excepturi delectus rerum nobis deleniti vero error repudiandae.',0,NULL,'delivering','dp_paid',NULL,NULL,NULL,'Jane Ratna Nasyidah','(+62) 506 7360 6693','2025-08-23 19:47:54','2026-08-13 13:38:55',NULL,NULL),
	(57,'RC-20260813-0052',6,2,56,55000.00,3080000.00,0.00,3080000.00,1540000.00,1540000.00,'Corrupti ut eius.','Gedung CV Prasasta Sihombing','Jln. Bambon No. 655, Tomohon 13205, Kalbar','Loa Janan Ilir','Tani Aman','2026-07-31','10:56:00','Ut saepe sunt molestiae officiis.',0,NULL,'delivering','dp_paid',NULL,NULL,NULL,'Jane Ratna Nasyidah','(+62) 506 7360 6693','2025-12-03 22:51:00','2026-08-13 13:38:55',NULL,NULL),
	(58,'RC-20260813-0053',6,1,39,35000.00,1365000.00,0.00,1365000.00,682500.00,682500.00,'Quis doloremque harum.','Gedung PJ Budiyanto','Kpg. Babadak No. 757, Banjarmasin 55649, NTT','Sungai Kunjang','Karang Anyar','2026-01-25','15:02:00','Nihil voluptatem quia minus repudiandae.',0,NULL,'pending','unpaid',NULL,NULL,NULL,'Jane Ratna Nasyidah','(+62) 506 7360 6693','2026-01-18 04:42:27','2026-08-13 13:38:55',NULL,NULL),
	(59,'RC-20260813-0054',6,1,48,35000.00,1680000.00,0.00,1680000.00,840000.00,840000.00,'Earum accusantium qui.','Gedung PD Lailasari Winarno','Dk. Yosodipuro No. 157, Payakumbuh 87528, Sulsel','Sungai Pinang','Mugirejo','2026-04-14','21:43:00',NULL,0,NULL,'delivering','dp_paid',NULL,NULL,NULL,'Jane Ratna Nasyidah','(+62) 506 7360 6693','2025-12-03 00:50:08','2026-08-13 13:38:55',NULL,NULL),
	(60,'RC-20260813-0055',7,1,44,35000.00,1540000.00,0.00,1540000.00,770000.00,770000.00,'Nostrum mollitia odio officiis nemo.','Gedung PT Yuniar','Ki. Padang No. 740, Bogor 16958, Sultra','Palaran','Bukuan','2025-12-13','23:23:00',NULL,0,NULL,'completed','fully_paid',NULL,NULL,NULL,'Saka Danuja Damanik M.M.','(+62) 335 5231 574','2025-11-20 16:12:40','2026-08-13 13:38:55',NULL,NULL),
	(61,'RC-20260813-0056',7,4,45,40000.00,1800000.00,0.00,1800000.00,900000.00,900000.00,'Quia quam explicabo.','Gedung UD Maryati (Persero) Tbk','Ki. Setia Budi No. 632, Singkawang 74957, Sulbar','Palaran','Handil Bakti','2026-04-09','22:45:00','Quis est velit est non libero inventore consequatur voluptatem.',0,NULL,'cancelled','unpaid',NULL,NULL,NULL,'Saka Danuja Damanik M.M.','(+62) 335 5231 574','2026-05-03 13:21:14','2026-08-13 13:38:55',NULL,NULL),
	(62,'RC-20260813-0057',7,4,12,40000.00,480000.00,0.00,480000.00,240000.00,240000.00,'Qui voluptatibus sint eum.','Gedung CV Wibowo (Persero) Tbk','Ki. Baja No. 944, Binjai 41104, Jateng','Palaran','Bantuas','2026-07-17','09:02:00',NULL,0,NULL,'delivering','dp_paid',NULL,NULL,NULL,'Saka Danuja Damanik M.M.','(+62) 335 5231 574','2025-08-28 12:47:27','2026-08-13 13:38:55',NULL,NULL),
	(63,'RC-20260813-0058',7,4,58,40000.00,2320000.00,0.00,2320000.00,1160000.00,1160000.00,'Odit et soluta.','Gedung CV Thamrin Lailasari','Jr. Lumban Tobing No. 415, Administrasi Jakarta Timur 11264, Sulteng','Loa Janan Ilir','Simpang Tiga','2026-03-03','05:15:00','Aut molestias illo deleniti aut laudantium.',0,NULL,'cancelled','unpaid',NULL,NULL,NULL,'Saka Danuja Damanik M.M.','(+62) 335 5231 574','2026-05-10 21:30:50','2026-08-13 13:38:55',NULL,NULL),
	(64,'RC-20260813-0059',7,3,40,45000.00,1800000.00,0.00,1800000.00,900000.00,900000.00,'Dolorem est quod.','Gedung Yayasan Puspita','Ki. Raya Ujungberung No. 753, Gunungsitoli 53754, Kalbar','Samarinda Utara','Tanah Merah','2026-01-31','14:41:00','Nisi repudiandae error adipisci repudiandae qui voluptatem.',0,NULL,'confirmed','dp_paid',NULL,NULL,NULL,'Saka Danuja Damanik M.M.','(+62) 335 5231 574','2025-12-15 02:49:36','2026-08-13 13:38:55',NULL,NULL),
	(65,'RC-20260813-0060',7,3,40,45000.00,1800000.00,0.00,1800000.00,900000.00,900000.00,'Dolorum nemo est.','Gedung PJ Halimah Suryatmi Tbk','Ki. Sadang Serang No. 22, Cilegon 25159, Malut','Palaran','Simpang Pasir','2025-11-23','18:39:00',NULL,0,NULL,'cancelled','unpaid',NULL,NULL,NULL,'Saka Danuja Damanik M.M.','(+62) 335 5231 574','2025-09-11 03:17:34','2026-08-13 13:38:55',NULL,NULL),
	(66,'RC-20260813-0061',7,2,56,55000.00,3080000.00,0.00,3080000.00,1540000.00,1540000.00,'Quia ea sit.','Gedung CV Laksmiwati (Persero) Tbk','Psr. Ketandan No. 633, Tanjungbalai 27813, Banten','Samarinda Utara','Budaya Pampang','2025-11-22','16:25:00',NULL,0,NULL,'cancelled','unpaid',NULL,NULL,NULL,'Saka Danuja Damanik M.M.','(+62) 335 5231 574','2026-01-10 07:28:11','2026-08-13 13:38:55',NULL,NULL),
	(67,'RC-20260813-0062',7,1,20,35000.00,700000.00,0.00,700000.00,350000.00,350000.00,'Reprehenderit et quis.','Gedung PD Melani Wibowo','Dk. Kali No. 937, Metro 14399, Aceh','Samarinda Seberang','Mangkupalas','2026-09-08','20:23:00','Velit possimus molestiae nisi.',0,NULL,'confirmed','dp_paid',NULL,NULL,NULL,'Saka Danuja Damanik M.M.','(+62) 335 5231 574','2025-10-25 02:44:03','2026-08-13 13:38:55',NULL,NULL),
	(68,'RC-20260813-0063',7,4,36,40000.00,1440000.00,0.00,1440000.00,720000.00,720000.00,'Enim adipisci velit.','Gedung Fa Saefullah (Persero) Tbk','Psr. Kyai Mojo No. 987, Sawahlunto 41998, Kalteng','Samarinda Utara','Sungai Siring','2026-07-10','01:32:00',NULL,0,NULL,'pending','unpaid',NULL,NULL,NULL,'Saka Danuja Damanik M.M.','(+62) 335 5231 574','2025-09-05 01:50:52','2026-08-13 13:38:55',NULL,NULL),
	(69,'RC-20260813-0064',7,3,46,45000.00,2070000.00,0.00,2070000.00,1035000.00,1035000.00,'Numquam excepturi qui.','Gedung Perum Rajasa','Psr. Bata Putih No. 928, Bengkulu 68442, Jateng','Sambutan','Pulau Atas','2026-08-30','05:42:00',NULL,0,NULL,'confirmed','dp_paid',NULL,NULL,NULL,'Saka Danuja Damanik M.M.','(+62) 335 5231 574','2026-02-24 23:06:48','2026-08-13 13:38:55',NULL,NULL);

/*!40000 ALTER TABLE `orders` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table package_addons
# ------------------------------------------------------------

DROP TABLE IF EXISTS `package_addons`;

CREATE TABLE `package_addons` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `package_id` bigint unsigned NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `price` decimal(10,2) NOT NULL DEFAULT '0.00',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `package_addons_package_id_foreign` (`package_id`),
  CONSTRAINT `package_addons_package_id_foreign` FOREIGN KEY (`package_id`) REFERENCES `packages` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

LOCK TABLES `package_addons` WRITE;
/*!40000 ALTER TABLE `package_addons` DISABLE KEYS */;

INSERT INTO `package_addons` (`id`, `package_id`, `name`, `price`, `created_at`, `updated_at`)
VALUES
	(1,1,'Tambah Air Mineral 600ml',4000.00,'2026-05-25 16:12:07','2026-05-25 16:12:07'),
	(2,1,'Upgrade Ayam Bakar Special',8000.00,'2026-05-25 16:12:07','2026-05-25 16:12:07'),
	(3,1,'Tambah Puding',5000.00,'2026-05-25 16:12:07','2026-05-25 16:12:07'),
	(4,2,'Tambah Sup Soto Ayam',10000.00,'2026-05-25 16:12:07','2026-05-25 16:12:07'),
	(5,2,'Upgrade Box Premium Bertutup',5000.00,'2026-05-25 16:12:07','2026-05-25 16:12:07'),
	(6,2,'Tambah Kue Lapis 2 pcs',8000.00,'2026-05-25 16:12:07','2026-05-25 16:12:07'),
	(7,2,'Tambah Jus Buah',10000.00,'2026-05-25 16:12:07','2026-05-25 16:12:07'),
	(8,4,'Upgrade Protein Premium',8000.00,'2026-06-22 11:55:31','2026-06-22 11:55:31'),
	(9,4,'Tambah 1 Lauk Pendamping',3000.00,'2026-06-22 11:55:31','2026-06-22 11:55:31'),
	(10,4,'Tambah Minuman',2000.00,'2026-06-22 11:55:31','2026-06-22 11:55:31'),
	(11,4,'Upgrade Dessert Premium',3000.00,'2026-06-22 11:55:31','2026-06-22 11:55:31');

/*!40000 ALTER TABLE `package_addons` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table packages
# ------------------------------------------------------------

DROP TABLE IF EXISTS `packages`;

CREATE TABLE `packages` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `price_per_box` decimal(10,2) NOT NULL,
  `min_order` int NOT NULL,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `event_type` enum('pernikahan','ulang_tahun','meeting','syukuran','lainnya') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'lainnya',
  `menu_items` json DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `sort_order` int NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `packages_slug_unique` (`slug`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

LOCK TABLES `packages` WRITE;
/*!40000 ALTER TABLE `packages` DISABLE KEYS */;

INSERT INTO `packages` (`id`, `name`, `slug`, `description`, `price_per_box`, `min_order`, `image`, `event_type`, `menu_items`, `is_active`, `sort_order`, `created_at`, `updated_at`)
VALUES
	(1,'Paket Acara 1','paket-acara-1','Paket nasi kotak premium untuk berbagai acara. Cocok untuk meeting, syukuran, dan acara keluarga. Setiap kotak sudah termasuk nasi, lauk pilihan, sayur, dan kerupuk.',35000.00,20,'packages/r0qmTVH4LR7jEB3DCxfJnoSS2782Ipjs3Ezjfikw.jpg','lainnya','[\"Nasi Putih\", \"Ayam Goreng / Ayam Bakar (pilihan)\", \"Tempe Orek\", \"Sayur Buncis\", \"Sambal Goreng\", \"Kerupuk\", \"Buah Potong\"]',1,1,'2026-05-25 16:12:07','2026-07-12 15:07:39'),
	(2,'Paket Acara 2','paket-acara-2','Paket nasi kotak premium spesial untuk pernikahan dan acara resmi. Tampilan lebih mewah dengan box cantik, menu lengkap, dan kualitas bahan terbaik.',55000.00,50,NULL,'pernikahan','[\"Nasi Putih / Nasi Kuning (pilihan)\", \"Ayam Bakar Madu / Ayam Goreng Crispy\", \"Rendang Daging Sapi\", \"Perkedel Kentang\", \"Tumis Brokoli & Wortel\", \"Sambal Goreng Ati\", \"Acar\", \"Kerupuk Udang\", \"Buah Segar\", \"Teh Kotak / Air Mineral\"]',1,2,'2026-05-25 16:12:07','2026-05-25 16:12:07'),
	(3,'Paket Meeting','paket-meeting','Paket snack dan makan siang praktis untuk rapat kantor dan seminar. Termasuk snack pagi dan makan siang lengkap.',45000.00,10,NULL,'meeting','[\"Snack Pagi (Kue & Gorengan)\", \"Nasi Kotak Siang\", \"Lauk Pilihan (Ayam/Ikan)\", \"Air Mineral\", \"Teh / Kopi\"]',1,3,'2026-05-25 16:12:07','2026-05-25 16:12:07'),
	(4,'Paket Menu Custom','paket-menu-custom','Buat paket catering impian Anda! Pilih sendiri menu utama, lauk pendamping, sayur, sambal, dan hidangan penutup sesuai dengan preferensi Anda.',40000.00,10,NULL,'lainnya','[\"Nasi Putih\", \"Pilih 1 Menu Utama\", \"Pilih 1 Lauk Pendamping\", \"Pilih 1 Sambal/Kuah\", \"Kerupuk & Acar\", \"Buah Segar atau Dessert\"]',1,10,'2026-06-22 11:55:31','2026-06-22 11:55:31');

/*!40000 ALTER TABLE `packages` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table pages
# ------------------------------------------------------------

DROP TABLE IF EXISTS `pages`;

CREATE TABLE `pages` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `subtitle` text COLLATE utf8mb4_unicode_ci,
  `body` longtext COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `pages_slug_unique` (`slug`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

LOCK TABLES `pages` WRITE;
/*!40000 ALTER TABLE `pages` DISABLE KEYS */;

INSERT INTO `pages` (`id`, `slug`, `title`, `subtitle`, `body`, `created_at`, `updated_at`)
VALUES
	(1,'about','Solusi Catering Profesional untuk Setiap Acara','Raissa Catering hadir sebagai partner catering terpercaya di Samarinda. Kami menyediakan berbagai paket nasi kotak dan catering prasmanan untuk pernikahan, ulang tahun, meeting kantor, syukuran, dan acara keluarga.','Raissa Catering menyediakan paket catering dengan pilihan menu yang fleksibel, dari nasi kotak standar sampai paket premium untuk tamu istimewa.\n\nKami juga melayani permintaan tambahan seperti minuman, dessert, dan kebutuhan khusus menu halal.\n\nSetiap pesanan didampingi dokumentasi pesanan, sehingga Anda bisa memantau status secara online.','2026-05-25 16:12:07','2026-05-25 16:12:07');

/*!40000 ALTER TABLE `pages` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table password_reset_tokens
# ------------------------------------------------------------

DROP TABLE IF EXISTS `password_reset_tokens`;

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



# Dump of table payment_confirmations
# ------------------------------------------------------------

DROP TABLE IF EXISTS `payment_confirmations`;

CREATE TABLE `payment_confirmations` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `payment_id` bigint unsigned NOT NULL,
  `confirmed_by` bigint unsigned DEFAULT NULL,
  `action` enum('approve','reject') COLLATE utf8mb4_unicode_ci NOT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci,
  `confirmed_at` timestamp NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `payment_confirmations_payment_id_foreign` (`payment_id`),
  KEY `payment_confirmations_confirmed_by_foreign` (`confirmed_by`),
  CONSTRAINT `payment_confirmations_confirmed_by_foreign` FOREIGN KEY (`confirmed_by`) REFERENCES `users` (`id`),
  CONSTRAINT `payment_confirmations_payment_id_foreign` FOREIGN KEY (`payment_id`) REFERENCES `payments` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



# Dump of table payments
# ------------------------------------------------------------

DROP TABLE IF EXISTS `payments`;

CREATE TABLE `payments` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `order_id` bigint unsigned NOT NULL,
  `xendit_invoice_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `xendit_payment_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `payment_reference` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` enum('dp','full_payment') COLLATE utf8mb4_unicode_ci NOT NULL,
  `amount` decimal(12,2) NOT NULL,
  `method` enum('xendit_va','xendit_ewallet','xendit_qris','cash','manual_transfer') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` enum('pending','paid','failed','expired','refunded') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `proof_image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `paid_at` timestamp NULL DEFAULT NULL,
  `xendit_response` json DEFAULT NULL,
  `admin_notes` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `payments_payment_reference_unique` (`payment_reference`),
  UNIQUE KEY `payments_xendit_invoice_id_unique` (`xendit_invoice_id`),
  KEY `payments_order_id_foreign` (`order_id`),
  CONSTRAINT `payments_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

LOCK TABLES `payments` WRITE;
/*!40000 ALTER TABLE `payments` DISABLE KEYS */;

INSERT INTO `payments` (`id`, `order_id`, `xendit_invoice_id`, `xendit_payment_id`, `payment_reference`, `type`, `amount`, `method`, `status`, `proof_image`, `paid_at`, `xendit_response`, `admin_notes`, `created_at`, `updated_at`)
VALUES
	(1,1,'6a45497971a9a0c319ad33b8',NULL,'RC-PAY-6A45497887315','full_payment',700000.00,NULL,'pending',NULL,NULL,'{\"id\": \"6a45497971a9a0c319ad33b8\", \"items\": [{\"name\": \"Pelunasan - Paket Acara 1 (20 kotak)\", \"price\": 700000, \"quantity\": 1}], \"amount\": 700000, \"status\": \"PENDING\", \"created\": \"2026-07-01T17:08:09.826Z\", \"updated\": \"2026-07-01T17:08:09.826Z\", \"user_id\": \"6a1109df59f1ecd18ff5c425\", \"currency\": \"IDR\", \"customer\": {\"email\": \"customer@demo.com\", \"given_names\": \"Budi Santoso\", \"mobile_number\": \"+6285752924301\"}, \"metadata\": null, \"description\": \"Pelunasan - Pesanan RC-20260701-0001 (Paket Acara 1)\", \"expiry_date\": \"2026-07-02T17:08:09.602Z\", \"external_id\": \"RC-PAY-6A45497887315\", \"invoice_url\": \"https://checkout-staging.xendit.co/web/6a45497971a9a0c319ad33b8\", \"merchant_name\": \"Raissa Catering\", \"available_banks\": [{\"bank_code\": \"BSI\", \"bank_branch\": \"Virtual Account\", \"collection_type\": \"POOL\", \"identity_amount\": 0, \"transfer_amount\": 700000, \"account_holder_name\": \"RAISSA CATERING\"}, {\"bank_code\": \"CIMB\", \"bank_branch\": \"Virtual Account\", \"collection_type\": \"POOL\", \"identity_amount\": 0, \"transfer_amount\": 700000, \"account_holder_name\": \"RAISSA CATERING\"}, {\"bank_code\": \"MANDIRI\", \"bank_branch\": \"Virtual Account\", \"collection_type\": \"POOL\", \"identity_amount\": 0, \"transfer_amount\": 700000, \"account_holder_name\": \"RAISSA CATERING\"}, {\"bank_code\": \"BCA\", \"bank_branch\": \"Virtual Account\", \"collection_type\": \"POOL\", \"identity_amount\": 0, \"transfer_amount\": 700000, \"account_holder_name\": \"RAISSA CATERING\"}, {\"bank_code\": \"SAHABAT_SAMPOERNA\", \"bank_branch\": \"Virtual Account\", \"collection_type\": \"POOL\", \"identity_amount\": 0, \"transfer_amount\": 700000, \"account_holder_name\": \"RAISSA CATERING\"}, {\"bank_code\": \"BRI\", \"bank_branch\": \"Virtual Account\", \"collection_type\": \"POOL\", \"identity_amount\": 0, \"transfer_amount\": 700000, \"account_holder_name\": \"RAISSA CATERING\"}, {\"bank_code\": \"BNC\", \"bank_branch\": \"Virtual Account\", \"collection_type\": \"POOL\", \"identity_amount\": 0, \"transfer_amount\": 700000, \"account_holder_name\": \"RAISSA CATERING\"}, {\"bank_code\": \"MUAMALAT\", \"bank_branch\": \"Virtual Account\", \"collection_type\": \"POOL\", \"identity_amount\": 0, \"transfer_amount\": 700000, \"account_holder_name\": \"RAISSA CATERING\"}, {\"bank_code\": \"PERMATA\", \"bank_branch\": \"Virtual Account\", \"collection_type\": \"POOL\", \"identity_amount\": 0, \"transfer_amount\": 700000, \"account_holder_name\": \"RAISSA CATERING\"}, {\"bank_code\": \"BJB\", \"bank_branch\": \"Virtual Account\", \"collection_type\": \"POOL\", \"identity_amount\": 0, \"transfer_amount\": 700000, \"account_holder_name\": \"RAISSA CATERING\"}, {\"bank_code\": \"BNI\", \"bank_branch\": \"Virtual Account\", \"collection_type\": \"POOL\", \"identity_amount\": 0, \"transfer_amount\": 700000, \"account_holder_name\": \"RAISSA CATERING\"}], \"should_send_email\": false, \"available_ewallets\": [{\"ewallet_type\": \"OVO\"}, {\"ewallet_type\": \"SHOPEEPAY\"}, {\"ewallet_type\": \"NEXCASH\"}, {\"ewallet_type\": \"DANA\"}, {\"ewallet_type\": \"ASTRAPAY\"}, {\"ewallet_type\": \"LINKAJA\"}, {\"ewallet_type\": \"JENIUSPAY\"}, {\"ewallet_type\": \"GOPAY\"}], \"available_qr_codes\": [{\"qr_code_type\": \"QRIS\"}], \"available_paylaters\": [{\"paylater_type\": \"KREDIVO\"}, {\"paylater_type\": \"AKULAKU\"}, {\"paylater_type\": \"ATOME\"}], \"failure_redirect_url\": \"http://127.0.0.1:8001/customer/orders/RC-20260701-0001\", \"success_redirect_url\": \"http://127.0.0.1:8001/customer/orders/RC-20260701-0001\", \"available_direct_debits\": [{\"direct_debit_type\": \"DD_BRI\"}, {\"direct_debit_type\": \"DD_MANDIRI\"}], \"available_retail_outlets\": [{\"retail_outlet_name\": \"INDOMARET\"}, {\"retail_outlet_name\": \"ALFAMART\"}], \"should_exclude_credit_card\": false, \"merchant_profile_picture_url\": \"https://du8nwjtfkinx.cloudfront.net/xendit.png\"}',NULL,'2026-07-01 17:08:08','2026-07-01 17:08:09'),
	(2,2,'6a454a3ef07617c3fc764755',NULL,'RC-PAY-6A454A3E22831','full_payment',700000.00,NULL,'pending',NULL,NULL,'{\"id\": \"6a454a3ef07617c3fc764755\", \"items\": [{\"name\": \"Pelunasan - Paket Acara 1 (20 kotak)\", \"price\": 700000, \"quantity\": 1}], \"amount\": 700000, \"status\": \"PENDING\", \"created\": \"2026-07-01T17:11:27.082Z\", \"updated\": \"2026-07-01T17:11:27.082Z\", \"user_id\": \"6a1109df59f1ecd18ff5c425\", \"currency\": \"IDR\", \"customer\": {\"email\": \"customer@demo.com\", \"given_names\": \"Budi Santoso\", \"mobile_number\": \"+6285752924301\"}, \"metadata\": null, \"description\": \"Pelunasan - Pesanan RC-20260701-0002 (Paket Acara 1)\", \"expiry_date\": \"2026-07-02T17:11:26.830Z\", \"external_id\": \"RC-PAY-6A454A3E22831\", \"invoice_url\": \"https://checkout-staging.xendit.co/web/6a454a3ef07617c3fc764755\", \"merchant_name\": \"Raissa Catering\", \"available_banks\": [{\"bank_code\": \"BSI\", \"bank_branch\": \"Virtual Account\", \"collection_type\": \"POOL\", \"identity_amount\": 0, \"transfer_amount\": 700000, \"account_holder_name\": \"RAISSA CATERING\"}, {\"bank_code\": \"CIMB\", \"bank_branch\": \"Virtual Account\", \"collection_type\": \"POOL\", \"identity_amount\": 0, \"transfer_amount\": 700000, \"account_holder_name\": \"RAISSA CATERING\"}, {\"bank_code\": \"MANDIRI\", \"bank_branch\": \"Virtual Account\", \"collection_type\": \"POOL\", \"identity_amount\": 0, \"transfer_amount\": 700000, \"account_holder_name\": \"RAISSA CATERING\"}, {\"bank_code\": \"BCA\", \"bank_branch\": \"Virtual Account\", \"collection_type\": \"POOL\", \"identity_amount\": 0, \"transfer_amount\": 700000, \"account_holder_name\": \"RAISSA CATERING\"}, {\"bank_code\": \"SAHABAT_SAMPOERNA\", \"bank_branch\": \"Virtual Account\", \"collection_type\": \"POOL\", \"identity_amount\": 0, \"transfer_amount\": 700000, \"account_holder_name\": \"RAISSA CATERING\"}, {\"bank_code\": \"BRI\", \"bank_branch\": \"Virtual Account\", \"collection_type\": \"POOL\", \"identity_amount\": 0, \"transfer_amount\": 700000, \"account_holder_name\": \"RAISSA CATERING\"}, {\"bank_code\": \"BNC\", \"bank_branch\": \"Virtual Account\", \"collection_type\": \"POOL\", \"identity_amount\": 0, \"transfer_amount\": 700000, \"account_holder_name\": \"RAISSA CATERING\"}, {\"bank_code\": \"MUAMALAT\", \"bank_branch\": \"Virtual Account\", \"collection_type\": \"POOL\", \"identity_amount\": 0, \"transfer_amount\": 700000, \"account_holder_name\": \"RAISSA CATERING\"}, {\"bank_code\": \"PERMATA\", \"bank_branch\": \"Virtual Account\", \"collection_type\": \"POOL\", \"identity_amount\": 0, \"transfer_amount\": 700000, \"account_holder_name\": \"RAISSA CATERING\"}, {\"bank_code\": \"BJB\", \"bank_branch\": \"Virtual Account\", \"collection_type\": \"POOL\", \"identity_amount\": 0, \"transfer_amount\": 700000, \"account_holder_name\": \"RAISSA CATERING\"}, {\"bank_code\": \"BNI\", \"bank_branch\": \"Virtual Account\", \"collection_type\": \"POOL\", \"identity_amount\": 0, \"transfer_amount\": 700000, \"account_holder_name\": \"RAISSA CATERING\"}], \"should_send_email\": false, \"available_ewallets\": [{\"ewallet_type\": \"OVO\"}, {\"ewallet_type\": \"SHOPEEPAY\"}, {\"ewallet_type\": \"NEXCASH\"}, {\"ewallet_type\": \"DANA\"}, {\"ewallet_type\": \"ASTRAPAY\"}, {\"ewallet_type\": \"LINKAJA\"}, {\"ewallet_type\": \"JENIUSPAY\"}, {\"ewallet_type\": \"GOPAY\"}], \"available_qr_codes\": [{\"qr_code_type\": \"QRIS\"}], \"available_paylaters\": [{\"paylater_type\": \"KREDIVO\"}, {\"paylater_type\": \"AKULAKU\"}, {\"paylater_type\": \"ATOME\"}], \"failure_redirect_url\": \"http://127.0.0.1:8001/customer/orders/RC-20260701-0002\", \"success_redirect_url\": \"http://127.0.0.1:8001/customer/orders/RC-20260701-0002\", \"available_direct_debits\": [{\"direct_debit_type\": \"DD_BRI\"}, {\"direct_debit_type\": \"DD_MANDIRI\"}], \"available_retail_outlets\": [{\"retail_outlet_name\": \"INDOMARET\"}, {\"retail_outlet_name\": \"ALFAMART\"}], \"should_exclude_credit_card\": false, \"merchant_profile_picture_url\": \"https://du8nwjtfkinx.cloudfront.net/xendit.png\"}',NULL,'2026-07-01 17:11:26','2026-07-01 17:11:27'),
	(3,2,'6a454a4371a9a0c319ad357c',NULL,'RC-PAY-6A454A436955F','full_payment',700000.00,NULL,'pending',NULL,NULL,'{\"id\": \"6a454a4371a9a0c319ad357c\", \"items\": [{\"name\": \"Pelunasan - Paket Acara 1 (20 kotak)\", \"price\": 700000, \"quantity\": 1}], \"amount\": 700000, \"status\": \"PENDING\", \"created\": \"2026-07-01T17:11:32.197Z\", \"updated\": \"2026-07-01T17:11:32.197Z\", \"user_id\": \"6a1109df59f1ecd18ff5c425\", \"currency\": \"IDR\", \"customer\": {\"email\": \"customer@demo.com\", \"given_names\": \"Budi Santoso\", \"mobile_number\": \"+6285752924301\"}, \"metadata\": null, \"description\": \"Pelunasan - Pesanan RC-20260701-0002 (Paket Acara 1)\", \"expiry_date\": \"2026-07-02T17:11:32.066Z\", \"external_id\": \"RC-PAY-6A454A436955F\", \"invoice_url\": \"https://checkout-staging.xendit.co/web/6a454a4371a9a0c319ad357c\", \"merchant_name\": \"Raissa Catering\", \"available_banks\": [{\"bank_code\": \"BSI\", \"bank_branch\": \"Virtual Account\", \"collection_type\": \"POOL\", \"identity_amount\": 0, \"transfer_amount\": 700000, \"account_holder_name\": \"RAISSA CATERING\"}, {\"bank_code\": \"CIMB\", \"bank_branch\": \"Virtual Account\", \"collection_type\": \"POOL\", \"identity_amount\": 0, \"transfer_amount\": 700000, \"account_holder_name\": \"RAISSA CATERING\"}, {\"bank_code\": \"MANDIRI\", \"bank_branch\": \"Virtual Account\", \"collection_type\": \"POOL\", \"identity_amount\": 0, \"transfer_amount\": 700000, \"account_holder_name\": \"RAISSA CATERING\"}, {\"bank_code\": \"BCA\", \"bank_branch\": \"Virtual Account\", \"collection_type\": \"POOL\", \"identity_amount\": 0, \"transfer_amount\": 700000, \"account_holder_name\": \"RAISSA CATERING\"}, {\"bank_code\": \"SAHABAT_SAMPOERNA\", \"bank_branch\": \"Virtual Account\", \"collection_type\": \"POOL\", \"identity_amount\": 0, \"transfer_amount\": 700000, \"account_holder_name\": \"RAISSA CATERING\"}, {\"bank_code\": \"BRI\", \"bank_branch\": \"Virtual Account\", \"collection_type\": \"POOL\", \"identity_amount\": 0, \"transfer_amount\": 700000, \"account_holder_name\": \"RAISSA CATERING\"}, {\"bank_code\": \"BNC\", \"bank_branch\": \"Virtual Account\", \"collection_type\": \"POOL\", \"identity_amount\": 0, \"transfer_amount\": 700000, \"account_holder_name\": \"RAISSA CATERING\"}, {\"bank_code\": \"MUAMALAT\", \"bank_branch\": \"Virtual Account\", \"collection_type\": \"POOL\", \"identity_amount\": 0, \"transfer_amount\": 700000, \"account_holder_name\": \"RAISSA CATERING\"}, {\"bank_code\": \"PERMATA\", \"bank_branch\": \"Virtual Account\", \"collection_type\": \"POOL\", \"identity_amount\": 0, \"transfer_amount\": 700000, \"account_holder_name\": \"RAISSA CATERING\"}, {\"bank_code\": \"BJB\", \"bank_branch\": \"Virtual Account\", \"collection_type\": \"POOL\", \"identity_amount\": 0, \"transfer_amount\": 700000, \"account_holder_name\": \"RAISSA CATERING\"}, {\"bank_code\": \"BNI\", \"bank_branch\": \"Virtual Account\", \"collection_type\": \"POOL\", \"identity_amount\": 0, \"transfer_amount\": 700000, \"account_holder_name\": \"RAISSA CATERING\"}], \"should_send_email\": false, \"available_ewallets\": [{\"ewallet_type\": \"OVO\"}, {\"ewallet_type\": \"SHOPEEPAY\"}, {\"ewallet_type\": \"NEXCASH\"}, {\"ewallet_type\": \"DANA\"}, {\"ewallet_type\": \"ASTRAPAY\"}, {\"ewallet_type\": \"LINKAJA\"}, {\"ewallet_type\": \"JENIUSPAY\"}, {\"ewallet_type\": \"GOPAY\"}], \"available_qr_codes\": [{\"qr_code_type\": \"QRIS\"}], \"available_paylaters\": [{\"paylater_type\": \"KREDIVO\"}, {\"paylater_type\": \"AKULAKU\"}, {\"paylater_type\": \"ATOME\"}], \"failure_redirect_url\": \"http://127.0.0.1:8001/customer/orders/RC-20260701-0002\", \"success_redirect_url\": \"http://127.0.0.1:8001/customer/orders/RC-20260701-0002\", \"available_direct_debits\": [{\"direct_debit_type\": \"DD_BRI\"}, {\"direct_debit_type\": \"DD_MANDIRI\"}], \"available_retail_outlets\": [{\"retail_outlet_name\": \"INDOMARET\"}, {\"retail_outlet_name\": \"ALFAMART\"}], \"should_exclude_credit_card\": false, \"merchant_profile_picture_url\": \"https://du8nwjtfkinx.cloudfront.net/xendit.png\"}',NULL,'2026-07-01 17:11:31','2026-07-01 17:11:32'),
	(4,3,'6a454a9c71a9a0c319ad361f',NULL,'RC-PAY-6A454A9BF165D','dp',350000.00,NULL,'pending',NULL,NULL,'{\"id\": \"6a454a9c71a9a0c319ad361f\", \"items\": [{\"name\": \"DP 50% - Paket Acara 1 (20 kotak)\", \"price\": 350000, \"quantity\": 1}], \"amount\": 350000, \"status\": \"PENDING\", \"created\": \"2026-07-01T17:13:00.855Z\", \"updated\": \"2026-07-01T17:13:00.855Z\", \"user_id\": \"6a1109df59f1ecd18ff5c425\", \"currency\": \"IDR\", \"customer\": {\"email\": \"customer@demo.com\", \"given_names\": \"Budi Santoso\", \"mobile_number\": \"+6281908455473\"}, \"metadata\": null, \"description\": \"DP 50% - Pesanan RC-20260701-0003 (Paket Acara 1)\", \"expiry_date\": \"2026-07-02T17:13:00.693Z\", \"external_id\": \"RC-PAY-6A454A9BF165D\", \"invoice_url\": \"https://checkout-staging.xendit.co/web/6a454a9c71a9a0c319ad361f\", \"merchant_name\": \"Raissa Catering\", \"available_banks\": [{\"bank_code\": \"BSI\", \"bank_branch\": \"Virtual Account\", \"collection_type\": \"POOL\", \"identity_amount\": 0, \"transfer_amount\": 350000, \"account_holder_name\": \"RAISSA CATERING\"}, {\"bank_code\": \"CIMB\", \"bank_branch\": \"Virtual Account\", \"collection_type\": \"POOL\", \"identity_amount\": 0, \"transfer_amount\": 350000, \"account_holder_name\": \"RAISSA CATERING\"}, {\"bank_code\": \"MANDIRI\", \"bank_branch\": \"Virtual Account\", \"collection_type\": \"POOL\", \"identity_amount\": 0, \"transfer_amount\": 350000, \"account_holder_name\": \"RAISSA CATERING\"}, {\"bank_code\": \"BCA\", \"bank_branch\": \"Virtual Account\", \"collection_type\": \"POOL\", \"identity_amount\": 0, \"transfer_amount\": 350000, \"account_holder_name\": \"RAISSA CATERING\"}, {\"bank_code\": \"SAHABAT_SAMPOERNA\", \"bank_branch\": \"Virtual Account\", \"collection_type\": \"POOL\", \"identity_amount\": 0, \"transfer_amount\": 350000, \"account_holder_name\": \"RAISSA CATERING\"}, {\"bank_code\": \"BRI\", \"bank_branch\": \"Virtual Account\", \"collection_type\": \"POOL\", \"identity_amount\": 0, \"transfer_amount\": 350000, \"account_holder_name\": \"RAISSA CATERING\"}, {\"bank_code\": \"BNC\", \"bank_branch\": \"Virtual Account\", \"collection_type\": \"POOL\", \"identity_amount\": 0, \"transfer_amount\": 350000, \"account_holder_name\": \"RAISSA CATERING\"}, {\"bank_code\": \"MUAMALAT\", \"bank_branch\": \"Virtual Account\", \"collection_type\": \"POOL\", \"identity_amount\": 0, \"transfer_amount\": 350000, \"account_holder_name\": \"RAISSA CATERING\"}, {\"bank_code\": \"PERMATA\", \"bank_branch\": \"Virtual Account\", \"collection_type\": \"POOL\", \"identity_amount\": 0, \"transfer_amount\": 350000, \"account_holder_name\": \"RAISSA CATERING\"}, {\"bank_code\": \"BJB\", \"bank_branch\": \"Virtual Account\", \"collection_type\": \"POOL\", \"identity_amount\": 0, \"transfer_amount\": 350000, \"account_holder_name\": \"RAISSA CATERING\"}, {\"bank_code\": \"BNI\", \"bank_branch\": \"Virtual Account\", \"collection_type\": \"POOL\", \"identity_amount\": 0, \"transfer_amount\": 350000, \"account_holder_name\": \"RAISSA CATERING\"}], \"should_send_email\": false, \"available_ewallets\": [{\"ewallet_type\": \"OVO\"}, {\"ewallet_type\": \"SHOPEEPAY\"}, {\"ewallet_type\": \"NEXCASH\"}, {\"ewallet_type\": \"DANA\"}, {\"ewallet_type\": \"ASTRAPAY\"}, {\"ewallet_type\": \"LINKAJA\"}, {\"ewallet_type\": \"JENIUSPAY\"}, {\"ewallet_type\": \"GOPAY\"}], \"available_qr_codes\": [{\"qr_code_type\": \"QRIS\"}], \"available_paylaters\": [{\"paylater_type\": \"KREDIVO\"}, {\"paylater_type\": \"AKULAKU\"}, {\"paylater_type\": \"ATOME\"}], \"failure_redirect_url\": \"http://127.0.0.1:8001/customer/orders/RC-20260701-0003\", \"success_redirect_url\": \"http://127.0.0.1:8001/customer/orders/RC-20260701-0003\", \"available_direct_debits\": [{\"direct_debit_type\": \"DD_BRI\"}, {\"direct_debit_type\": \"DD_MANDIRI\"}], \"available_retail_outlets\": [{\"retail_outlet_name\": \"INDOMARET\"}, {\"retail_outlet_name\": \"ALFAMART\"}], \"should_exclude_credit_card\": false, \"merchant_profile_picture_url\": \"https://du8nwjtfkinx.cloudfront.net/xendit.png\", \"customer_notification_preference\": {\"invoice_paid\": [\"whatsapp\", \"email\"], \"invoice_created\": [\"whatsapp\", \"email\"], \"invoice_reminder\": [\"whatsapp\", \"email\"]}}',NULL,'2026-07-01 17:12:59','2026-07-01 17:13:00'),
	(5,4,'6a5390efe3f0aa9c76ff715b',NULL,'RC-PAY-6A5390EF516A9','full_payment',700000.00,NULL,'pending',NULL,NULL,'{\"id\": \"6a5390efe3f0aa9c76ff715b\", \"items\": [{\"name\": \"Pelunasan - Paket Acara 1 (20 kotak)\", \"price\": 700000, \"quantity\": 1}], \"amount\": 700000, \"status\": \"PENDING\", \"created\": \"2026-07-12T13:04:48.262Z\", \"updated\": \"2026-07-12T13:04:48.262Z\", \"user_id\": \"6a1109df59f1ecd18ff5c425\", \"currency\": \"IDR\", \"customer\": {\"email\": \"customer@demo.com\", \"given_names\": \"Budi Santoso\", \"mobile_number\": \"+628190748842\"}, \"metadata\": null, \"description\": \"Pelunasan - Pesanan RC-20260712-0001 (Paket Acara 1)\", \"expiry_date\": \"2026-07-13T13:04:48.033Z\", \"external_id\": \"RC-PAY-6A5390EF516A9\", \"invoice_url\": \"https://checkout-staging.xendit.co/web/6a5390efe3f0aa9c76ff715b\", \"merchant_name\": \"Raissa Catering\", \"available_banks\": [{\"bank_code\": \"CIMB\", \"bank_branch\": \"Virtual Account\", \"collection_type\": \"POOL\", \"identity_amount\": 0, \"transfer_amount\": 700000, \"account_holder_name\": \"RAISSA CATERING\"}, {\"bank_code\": \"BJB\", \"bank_branch\": \"Virtual Account\", \"collection_type\": \"POOL\", \"identity_amount\": 0, \"transfer_amount\": 700000, \"account_holder_name\": \"RAISSA CATERING\"}, {\"bank_code\": \"SAHABAT_SAMPOERNA\", \"bank_branch\": \"Virtual Account\", \"collection_type\": \"POOL\", \"identity_amount\": 0, \"transfer_amount\": 700000, \"account_holder_name\": \"RAISSA CATERING\"}, {\"bank_code\": \"MANDIRI\", \"bank_branch\": \"Virtual Account\", \"collection_type\": \"POOL\", \"identity_amount\": 0, \"transfer_amount\": 700000, \"account_holder_name\": \"RAISSA CATERING\"}, {\"bank_code\": \"BRI\", \"bank_branch\": \"Virtual Account\", \"collection_type\": \"POOL\", \"identity_amount\": 0, \"transfer_amount\": 700000, \"account_holder_name\": \"RAISSA CATERING\"}, {\"bank_code\": \"BSI\", \"bank_branch\": \"Virtual Account\", \"collection_type\": \"POOL\", \"identity_amount\": 0, \"transfer_amount\": 700000, \"account_holder_name\": \"RAISSA CATERING\"}, {\"bank_code\": \"BCA\", \"bank_branch\": \"Virtual Account\", \"collection_type\": \"POOL\", \"identity_amount\": 0, \"transfer_amount\": 700000, \"account_holder_name\": \"RAISSA CATERING\"}, {\"bank_code\": \"BNI\", \"bank_branch\": \"Virtual Account\", \"collection_type\": \"POOL\", \"identity_amount\": 0, \"transfer_amount\": 700000, \"account_holder_name\": \"RAISSA CATERING\"}, {\"bank_code\": \"BNC\", \"bank_branch\": \"Virtual Account\", \"collection_type\": \"POOL\", \"identity_amount\": 0, \"transfer_amount\": 700000, \"account_holder_name\": \"RAISSA CATERING\"}, {\"bank_code\": \"MUAMALAT\", \"bank_branch\": \"Virtual Account\", \"collection_type\": \"POOL\", \"identity_amount\": 0, \"transfer_amount\": 700000, \"account_holder_name\": \"RAISSA CATERING\"}, {\"bank_code\": \"PERMATA\", \"bank_branch\": \"Virtual Account\", \"collection_type\": \"POOL\", \"identity_amount\": 0, \"transfer_amount\": 700000, \"account_holder_name\": \"RAISSA CATERING\"}], \"should_send_email\": false, \"available_ewallets\": [{\"ewallet_type\": \"OVO\"}, {\"ewallet_type\": \"SHOPEEPAY\"}, {\"ewallet_type\": \"NEXCASH\"}, {\"ewallet_type\": \"DANA\"}, {\"ewallet_type\": \"ASTRAPAY\"}, {\"ewallet_type\": \"LINKAJA\"}, {\"ewallet_type\": \"JENIUSPAY\"}, {\"ewallet_type\": \"GOPAY\"}], \"available_qr_codes\": [{\"qr_code_type\": \"QRIS\"}], \"available_paylaters\": [{\"paylater_type\": \"KREDIVO\"}, {\"paylater_type\": \"AKULAKU\"}, {\"paylater_type\": \"ATOME\"}], \"failure_redirect_url\": \"http://127.0.0.1:8000/customer/orders/RC-20260712-0001\", \"success_redirect_url\": \"http://127.0.0.1:8000/customer/orders/RC-20260712-0001\", \"available_direct_debits\": [{\"direct_debit_type\": \"DD_BRI\"}, {\"direct_debit_type\": \"DD_MANDIRI\"}], \"available_retail_outlets\": [{\"retail_outlet_name\": \"INDOMARET\"}, {\"retail_outlet_name\": \"ALFAMART\"}], \"should_exclude_credit_card\": false, \"merchant_profile_picture_url\": \"https://du8nwjtfkinx.cloudfront.net/xendit.png\"}',NULL,'2026-07-12 13:04:47','2026-07-12 13:04:48'),
	(6,5,NULL,NULL,'RC-PAY-6A7038B2B0527','full_payment',700000.00,NULL,'pending',NULL,NULL,NULL,NULL,'2026-08-03 06:44:02','2026-08-03 06:44:02'),
	(7,5,NULL,NULL,'RC-PAY-6A7038B5198E0','full_payment',700000.00,NULL,'pending',NULL,NULL,NULL,NULL,'2026-08-03 06:44:05','2026-08-03 06:44:05'),
	(8,5,NULL,NULL,'RC-PAY-6A7DC1F34376E','full_payment',700000.00,NULL,'pending',NULL,NULL,NULL,NULL,'2026-08-13 13:09:07','2026-08-13 13:09:07'),
	(9,5,'6a7dc2695a693600d08c4b14','6a7dc2695a693600d08c4b14','RC-PAY-6A7DC268C667E','full_payment',700000.00,'xendit_va','paid',NULL,'2026-08-13 13:30:51','{\"id\": \"6a7dc2695a693600d08c4b14\", \"items\": [{\"name\": \"Pelunasan - Paket Acara 1 (20 kotak)\", \"price\": 700000, \"quantity\": 1}], \"amount\": 700000, \"status\": \"PAID\", \"created\": \"2026-08-13T13:11:05.757Z\", \"is_high\": false, \"paid_at\": \"2026-08-13T13:15:45.000Z\", \"updated\": \"2026-08-13T13:15:45.600Z\", \"user_id\": \"6a672608aa55a83ca6211c59\", \"currency\": \"IDR\", \"bank_code\": \"BCA\", \"payment_id\": \"963ca07e-6bf5-4a81-bebb-d67643d86674\", \"description\": \"Pelunasan - Pesanan RC-20260803-0001 (Paket Acara 1)\", \"external_id\": \"RC-PAY-6A7DC268C667E\", \"paid_amount\": 700000, \"merchant_name\": \"Raisa Cattering\", \"payment_method\": \"BANK_TRANSFER\", \"payment_channel\": \"BCA\", \"payment_destination\": \"3816575646931\", \"failure_redirect_url\": \"http://127.0.0.1:8000/customer/orders/RC-20260803-0001\", \"success_redirect_url\": \"http://127.0.0.1:8000/customer/orders/RC-20260803-0001\"}',NULL,'2026-08-13 13:11:04','2026-08-13 13:30:51'),
	(10,6,'6a7dc4865a693600d08c4fc4','6a7dc4865a693600d08c4fc4','RC-PAY-6A7DC485798B0','full_payment',700000.00,'xendit_va','paid',NULL,'2026-08-13 13:35:51','{\"id\": \"6a7dc4865a693600d08c4fc4\", \"items\": [{\"name\": \"Pelunasan - Paket Acara 1 (20 kotak)\", \"price\": 700000, \"quantity\": 1}], \"amount\": 700000, \"status\": \"PAID\", \"created\": \"2026-08-13T13:20:06.543Z\", \"is_high\": false, \"paid_at\": \"2026-08-13T13:20:12.000Z\", \"updated\": \"2026-08-13T13:20:12.791Z\", \"user_id\": \"6a672608aa55a83ca6211c59\", \"currency\": \"IDR\", \"bank_code\": \"BCA\", \"payment_id\": \"5ff8f825-2bbe-4d8a-ab7c-8c35723f7820\", \"description\": \"Pelunasan - Pesanan RC-20260813-0001 (Paket Acara 1)\", \"external_id\": \"RC-PAY-6A7DC485798B0\", \"paid_amount\": 700000, \"merchant_name\": \"Raisa Cattering\", \"payment_method\": \"BANK_TRANSFER\", \"payment_channel\": \"BCA\", \"payment_destination\": \"3816542441845\", \"failure_redirect_url\": \"http://127.0.0.1:8000/customer/orders/RC-20260813-0001\", \"success_redirect_url\": \"http://127.0.0.1:8000/customer/orders/RC-20260813-0001\"}','Pembayaran dikonfirmasi manual oleh Admin (webhook tidak diterima).','2026-08-13 13:20:05','2026-08-13 13:35:51'),
	(11,7,'6a7dc524948acde717dfa94f','6a7dc524948acde717dfa94f','RC-PAY-6A7DC5239BC43','full_payment',700000.00,'xendit_va','paid',NULL,'2026-08-13 13:38:50','{\"id\": \"6a7dc524948acde717dfa94f\", \"items\": [{\"name\": \"Pelunasan - Paket Acara 1 (20 kotak)\", \"price\": 700000, \"quantity\": 1}], \"amount\": 700000, \"status\": \"PAID\", \"created\": \"2026-08-13T13:22:44.529Z\", \"is_high\": false, \"paid_at\": \"2026-08-13T13:22:49.000Z\", \"updated\": \"2026-08-13T13:22:50.125Z\", \"user_id\": \"6a672608aa55a83ca6211c59\", \"currency\": \"IDR\", \"bank_code\": \"SAHABAT_SAMPOERNA\", \"payment_id\": \"5d46e725-3046-4a47-8856-a7e348e87415\", \"description\": \"Pelunasan - Pesanan RC-20260813-0002 (Paket Acara 1)\", \"external_id\": \"RC-PAY-6A7DC5239BC43\", \"paid_amount\": 700000, \"merchant_name\": \"Raisa Cattering\", \"payment_method\": \"BANK_TRANSFER\", \"payment_channel\": \"SAHABAT_SAMPOERNA\", \"payment_destination\": \"4010290771984\", \"failure_redirect_url\": \"http://127.0.0.1:8000/customer/orders/RC-20260813-0002\", \"success_redirect_url\": \"http://127.0.0.1:8000/customer/orders/RC-20260813-0002\"}',NULL,'2026-08-13 13:22:43','2026-08-13 13:38:50'),
	(12,8,'6a7dc604948acde717dfab28','6a7dc604948acde717dfab28','RC-PAY-6A7DC603B2D16','full_payment',700000.00,'xendit_va','paid',NULL,'2026-08-13 13:26:35','{\"id\": \"6a7dc604948acde717dfab28\", \"items\": [{\"name\": \"Pelunasan - Paket Acara 1 (20 kotak)\", \"price\": 700000, \"quantity\": 1}], \"amount\": 700000, \"status\": \"PAID\", \"created\": \"2026-08-13T13:26:28.455Z\", \"is_high\": false, \"paid_at\": \"2026-08-13T13:26:34.000Z\", \"updated\": \"2026-08-13T13:26:34.317Z\", \"user_id\": \"6a672608aa55a83ca6211c59\", \"currency\": \"IDR\", \"bank_code\": \"BCA\", \"payment_id\": \"f7359f4e-f0bb-474f-8199-1cee60551ed6\", \"description\": \"Pelunasan - Pesanan RC-20260813-0003 (Paket Acara 1)\", \"external_id\": \"RC-PAY-6A7DC603B2D16\", \"paid_amount\": 700000, \"merchant_name\": \"Raisa Cattering\", \"payment_method\": \"BANK_TRANSFER\", \"payment_channel\": \"BCA\", \"payment_destination\": \"3816562605047\", \"failure_redirect_url\": \"http://127.0.0.1:8000/customer/orders/RC-20260813-0003\", \"success_redirect_url\": \"http://127.0.0.1:8000/customer/orders/RC-20260813-0003\"}',NULL,'2026-08-13 13:26:27','2026-08-13 13:26:35');

/*!40000 ALTER TABLE `payments` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table reviews
# ------------------------------------------------------------

DROP TABLE IF EXISTS `reviews`;

CREATE TABLE `reviews` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `order_id` bigint unsigned NOT NULL,
  `package_id` bigint unsigned NOT NULL,
  `rating` tinyint NOT NULL,
  `comment` text COLLATE utf8mb4_unicode_ci,
  `event_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_approved` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `reviews_user_id_foreign` (`user_id`),
  KEY `reviews_order_id_foreign` (`order_id`),
  KEY `reviews_package_id_foreign` (`package_id`),
  CONSTRAINT `reviews_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  CONSTRAINT `reviews_package_id_foreign` FOREIGN KEY (`package_id`) REFERENCES `packages` (`id`) ON DELETE CASCADE,
  CONSTRAINT `reviews_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



# Dump of table sessions
# ------------------------------------------------------------

DROP TABLE IF EXISTS `sessions`;

CREATE TABLE `sessions` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

LOCK TABLES `sessions` WRITE;
/*!40000 ALTER TABLE `sessions` DISABLE KEYS */;

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`)
VALUES
	('7rTzpmGrsvjknOd6OLr1afvwjukvcGPl7EvfscFO',NULL,'127.0.0.1','axios/1.10.0','eyJfdG9rZW4iOiJFcXNzVHdSN3EyOGxObXVhME1Bc3V2cUk5d3E2b01yYlhLNHF1SVdIIiwiX2ZsYXNoIjp7Im9sZCI6W10sIm5ldyI6W119fQ==',1786627852),
	('fHdfZiFAIHnbhTbj2nuCSxdlQC18JIXqE59nqhZJ',2,'127.0.0.1','Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36','eyJfdG9rZW4iOiI3R29CTlBKV2FSSm1vdVFVOXpEY1FreVYwbXJSS1V6ZDNyNzBib1VSIiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cLzEyNy4wLjAuMTo4MDAwXC9jdXN0b21lclwvb3JkZXJzXC9SQy0yMDI2MDgwMy0wMDAxIiwicm91dGUiOiJjdXN0b21lci5vcmRlcnMuc2hvdyJ9LCJfZmxhc2giOnsib2xkIjpbXSwibmV3IjpbXX0sImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjoyfQ==',1786626968),
	('g9TEznzeBOiHr7nQlTjXGQNQQnsroUwAYzCLF0D8',NULL,'127.0.0.1','axios/1.10.0','eyJfdG9rZW4iOiJSUkhmM01zQ2F4Y0VOOWJRTVhXNk5VU2dPdFBrVE16ZVFab3ZGOWNyIiwiX2ZsYXNoIjp7Im9sZCI6W10sIm5ldyI6W119fQ==',1786627595),
	('hYhJ98vRsXjWwCktlk5eZqSa7Jw5irz6a90G35FP',NULL,'127.0.0.1','axios/1.10.0','eyJfdG9rZW4iOiJMVkhzbVNhS1VCOXZONGJZU2hCcDEzeEVNS21ZckYyUU1NR011VzV0IiwiX2ZsYXNoIjp7Im9sZCI6W10sIm5ldyI6W119fQ==',1786627490),
	('IkfDVKvxZXumW421WYfWMQGMFqlr8OvFG0LYFIFk',NULL,'127.0.0.1','axios/1.10.0','eyJfdG9rZW4iOiIxdFpwUmVhbUNjbFY1ZklNaG5UU1dGSmpaVHlSQU5RQlVhZldZU0VDIiwiX2ZsYXNoIjp7Im9sZCI6W10sIm5ldyI6W119fQ==',1786627370),
	('OmUeHLH3gnbvTFsdDuBECFLTKirhZpIlvxaIia1x',2,'127.0.0.1','Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36','eyJfdG9rZW4iOiJ2YklEbk5QaFdidmVtcVpCWERJS0hOc1lVdjBvOGRFZjVUMjByNktvIiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cLzEyNy4wLjAuMTo4MDAwXC9jdXN0b21lclwvb3JkZXJzIiwicm91dGUiOiJjdXN0b21lci5vcmRlcnMuaW5kZXgifSwiX2ZsYXNoIjp7Im9sZCI6W10sIm5ldyI6W119LCJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI6Mn0=',1786628347),
	('omvZxhW95F0rbuyIPcinjRwUAVpQxk68ZVnyCEZc',1,'127.0.0.1','Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36','eyJfdG9rZW4iOiJhcTM5WWdaclJjZVlEWkJ5Z05qSHNDQ3RsMUdKbHpsN0wweklpV0pJIiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cLzEyNy4wLjAuMTo4MDAwXC9hZG1pblwvb3JkZXJzIiwicm91dGUiOiJhZG1pbi5vcmRlcnMuaW5kZXgifSwiX2ZsYXNoIjp7Im9sZCI6W10sIm5ldyI6W119LCJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI6MX0=',1786628382),
	('pUYMvrReRC29UvyEcRLEFPCE5G1Nj0eSoDbTVs1g',NULL,'127.0.0.1','axios/1.10.0','eyJfdG9rZW4iOiJyRTJvRUtGU0Q0Vng4WmtDaTUzSU8yRWxybVRRZTBTWWE3ZDM4cTJ6IiwiX2ZsYXNoIjp7Im9sZCI6W10sIm5ldyI6W119fQ==',1786627306),
	('ZU6HrV0CaP7g6N1ravbih3gS4iXRN766SnbO6IgR',NULL,'127.0.0.1','axios/1.10.0','eyJfdG9rZW4iOiJWNEYydXBPa1V4S215UjBjUjJHb3hGeW9EY29TYXlncFgyeXVKM1R5IiwiX2ZsYXNoIjp7Im9sZCI6W10sIm5ldyI6W119fQ==',1786628330),
	('zY2gpJeQxLNFSfFLQOScjHzANz7ek6lRM55H7rmX',NULL,'127.0.0.1','axios/1.10.0','eyJfdG9rZW4iOiJPUFEzdTR6OHZZUHNHYm1JMWR2YUJPbTlBaW1Xd0RaeXNicko2Qkg1IiwiX2ZsYXNoIjp7Im9sZCI6W10sIm5ldyI6W119fQ==',1786628151);

/*!40000 ALTER TABLE `sessions` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table users
# ------------------------------------------------------------

DROP TABLE IF EXISTS `users`;

CREATE TABLE `users` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `role` enum('customer','admin') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'customer',
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;

INSERT INTO `users` (`id`, `name`, `email`, `phone`, `address`, `role`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`)
VALUES
	(1,'Admin Raissa','admin@raissacatering.com',NULL,NULL,'admin','2026-06-22 11:55:30','$2y$12$oRt0SiobxWsAw3ZayPJxVuTk50qvjpW9hgqQ9gzhEbVUCNL4Lc3SG',NULL,'2026-05-25 16:12:07','2026-06-22 11:55:30'),
	(2,'Budi Santoso','customer@demo.com','081234567890',NULL,'customer','2026-06-22 11:55:31','$2y$12$yV.9LH.guilVWGQWdEopU.lNhfBeFGoqqV5cV4EMB6qEyeZW6il7K',NULL,'2026-05-25 16:12:07','2026-06-22 11:55:31'),
	(3,'Umaya Purwa Firgantoro S.Ked','tarihoran.ratna@example.org','0878 0480 8750',NULL,'customer','2026-08-13 13:38:54','$2y$12$LmzcmQZKQv80UdWgbrS.jO/9pOl98sJXyerffwFsRaFctLqlZtY.2',NULL,'2026-08-13 13:38:54','2026-08-13 13:38:54'),
	(4,'Victoria Mulyani S.Psi','raina.hakim@example.net','(+62) 567 6772 265',NULL,'customer','2026-08-13 13:38:54','$2y$12$A5wCordtWLhM9Z8j5sv9kOBA8ZRC46lBmegqV0dLU/3WRkLty836.',NULL,'2026-08-13 13:38:54','2026-08-13 13:38:54'),
	(5,'Syahrini Padmasari','haryanto.baktiadi@example.org','0400 3746 4492',NULL,'customer','2026-08-13 13:38:55','$2y$12$iXyDdrsqVIpkS.P78gIUZOc0o6BKNTo.k5UaawjChwhKFFBK5PuhS',NULL,'2026-08-13 13:38:55','2026-08-13 13:38:55'),
	(6,'Jane Ratna Nasyidah','pertiwi.wardi@example.net','(+62) 506 7360 6693',NULL,'customer','2026-08-13 13:38:55','$2y$12$464l6mWIATGWMVYOlgqpquY2gi6fd28LxBacL.cYiMjcPNVDGjeYC',NULL,'2026-08-13 13:38:55','2026-08-13 13:38:55'),
	(7,'Saka Danuja Damanik M.M.','intan23@example.com','(+62) 335 5231 574',NULL,'customer','2026-08-13 13:38:55','$2y$12$5iK3IE2VG1.2bMlZkabWp.7TDbT68S9dazDCK06MSn6fuKrCAMEh.',NULL,'2026-08-13 13:38:55','2026-08-13 13:38:55');

/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;



/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
