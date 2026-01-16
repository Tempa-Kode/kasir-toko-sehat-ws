-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jan 13, 2026 at 02:25 PM
-- Server version: 8.0.30
-- PHP Version: 8.2.22

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_kasir_toko_sehat`
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
(2, '2025_09_14_095015_create_personal_access_tokens_table', 1),
(3, '2025_09_14_101136_create_satuans_table', 1),
(4, '2025_09_14_102242_create_kategori_produks_table', 1),
(5, '2025_09_14_102821_create_produks_table', 2),
(6, '2025_09_14_105247_create_transaksis_table', 3),
(7, '2025_09_14_111020_create_detail_transaksis_table', 4),
(8, '2025_11_17_094608_tambah-harga-modal-pada-tabel-produk', 5),
(9, '2025_11_17_103406_tambah_kolom_subtotal_modal_pada_tabel_detail_transaksi', 5),
(10, '2025_11_22_161036_create_riwayat_produk_masuks_table', 6);

-- --------------------------------------------------------

--
-- Table structure for table `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint UNSIGNED NOT NULL,
  `tokenable_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint UNSIGNED NOT NULL,
  `name` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `personal_access_tokens`
--

INSERT INTO `personal_access_tokens` (`id`, `tokenable_type`, `tokenable_id`, `name`, `token`, `abilities`, `last_used_at`, `expires_at`, `created_at`, `updated_at`) VALUES
(1, 'App\\Models\\User', 4, 'api-token', 'b29aa0d6e4cfc9b51c0f20e6a9d5e97c43b67a29ccd45bc36aaafbf9fd630a3e', '[\"*\"]', '2025-09-14 05:32:11', NULL, '2025-09-14 05:31:36', '2025-09-14 05:32:11'),
(2, 'App\\Models\\User', 1, 'api-token', '0b502fa29dc301d9050ccf1f9c7f782e739bede24af2a11a4aa1908d027ff82f', '[\"*\"]', NULL, NULL, '2025-09-14 05:45:24', '2025-09-14 05:45:24'),
(3, 'App\\Models\\User', 5, 'api-token', '2c39e3c667b8b6456bd54115cc66c7afe89b0295f72a40f009ccd7289cd375ff', '[\"*\"]', '2025-09-16 14:00:10', NULL, '2025-09-14 05:51:11', '2025-09-16 14:00:10'),
(4, 'App\\Models\\User', 1, 'api-token', '40560cc5532e7b5ff794109d1cb0c3290b4d86aa063c8a4b6de0d7a4b690a3aa', '[\"*\"]', '2025-09-16 14:07:36', NULL, '2025-09-16 14:00:43', '2025-09-16 14:07:36'),
(5, 'App\\Models\\User', 1, 'api-token', '940b4de881cd6559262f0a86c556b45b6eb01a6aa7ebd82e214ef3e13f7889b7', '[\"*\"]', NULL, NULL, '2025-10-25 16:53:25', '2025-10-25 16:53:25'),
(6, 'App\\Models\\User', 1, 'api-token', 'f430b859c54e81d0896db371fb52d2cdb263ed11914db43d452d718ee64ded48', '[\"*\"]', NULL, NULL, '2025-10-28 09:31:54', '2025-10-28 09:31:54'),
(7, 'App\\Models\\User', 1, 'api-token', '4b4924693b2e137961dfaa0af74f0ce90a444a53c0d3614c96c993adf6b8b278', '[\"*\"]', NULL, NULL, '2025-10-28 09:32:39', '2025-10-28 09:32:39'),
(8, 'App\\Models\\User', 1, 'api-token', '7159b034fd72433a2874a4cd82e50718c16d93891d1d67904321028b6e8e53e3', '[\"*\"]', '2025-10-28 13:47:00', NULL, '2025-10-28 09:37:46', '2025-10-28 13:47:00'),
(9, 'App\\Models\\User', 1, 'api-token', '8346a6f1782d64ff025044aba083f89d7bdf68759a0b4fbd023315f94d53a3f4', '[\"*\"]', '2025-10-28 10:23:40', NULL, '2025-10-28 10:20:24', '2025-10-28 10:23:40'),
(10, 'App\\Models\\User', 1, 'api-token', 'aa959a23fa784582b76be10590bb61456c0ed243dae3512407cb79e11ebf6ffe', '[\"*\"]', '2025-10-28 13:47:38', NULL, '2025-10-28 13:47:21', '2025-10-28 13:47:38'),
(11, 'App\\Models\\User', 1, 'api-token', '5a339b2e87794216a08cb84eb3a95abd6b2c27471c0cd58a6a91a8e090c30c93', '[\"*\"]', '2025-10-28 14:36:09', NULL, '2025-10-28 13:47:51', '2025-10-28 14:36:09'),
(12, 'App\\Models\\User', 1, 'api-token', 'e5a863f4a86c129312db2f69d3383d04e68b002928daba63903199c0c4ebd79e', '[\"*\"]', '2025-10-28 17:47:49', NULL, '2025-10-28 14:05:36', '2025-10-28 17:47:49'),
(13, 'App\\Models\\User', 2, 'api-token', 'e5b865498df83340dd151e29033f8c8d760b0cd029852136fd93e39e941e8ba3', '[\"*\"]', '2025-10-28 14:38:27', NULL, '2025-10-28 14:38:16', '2025-10-28 14:38:27'),
(14, 'App\\Models\\User', 1, 'api-token', 'b9f2db2185659a27f968186e82a54c08a633241f57552915db6e845b6aaf1adc', '[\"*\"]', '2025-10-28 14:48:17', NULL, '2025-10-28 14:47:53', '2025-10-28 14:48:17'),
(15, 'App\\Models\\User', 7, 'api-token', 'e69b1dfc32cf1314d8de96e25b62e8d0e8dd1cca96ca9d911078d2c2c18154e5', '[\"*\"]', '2025-10-28 15:30:50', NULL, '2025-10-28 14:48:27', '2025-10-28 15:30:50'),
(16, 'App\\Models\\User', 1, 'api-token', '2158e8fccb1a4569e007d8f4569d6c57d8619d1a35dc0d540d3061d40d5b9c56', '[\"*\"]', '2025-10-28 15:31:02', NULL, '2025-10-28 15:30:59', '2025-10-28 15:31:02'),
(17, 'App\\Models\\User', 7, 'api-token', '6322e82b2dc41ac2eff97f903a95a3b8f1787e641ec4fb4806ae4c0baf490b75', '[\"*\"]', '2025-10-28 15:32:18', NULL, '2025-10-28 15:31:24', '2025-10-28 15:32:18'),
(18, 'App\\Models\\User', 1, 'api-token', '02ee568881901acdbb102b35ffbb8d586913c1303b989ad8e42a78cb62c7ae45', '[\"*\"]', '2025-10-29 07:01:16', NULL, '2025-10-28 18:27:11', '2025-10-29 07:01:16'),
(19, 'App\\Models\\User', 1, 'api-token', '8392eb097fba110337958c03f14395be5ab9bb2969ba723d663302b1d4dbba30', '[\"*\"]', NULL, NULL, '2025-10-29 14:17:05', '2025-10-29 14:17:05'),
(20, 'App\\Models\\User', 1, 'api-token', 'd60744286e11f3c7d4bc0bfdf04ab5e766080a5d9f04fd339f86a6b7a074780a', '[\"*\"]', '2025-10-29 14:18:03', NULL, '2025-10-29 14:17:56', '2025-10-29 14:18:03'),
(21, 'App\\Models\\User', 1, 'api-token', 'cd08c53970a755140517abfb3a55b6002746e80209fcda9fcc9da3ceb20a371c', '[\"*\"]', NULL, NULL, '2025-10-29 14:57:24', '2025-10-29 14:57:24'),
(22, 'App\\Models\\User', 1, 'api-token', '7ed871e69cbb9164f81d375eb85b1ba2706e7f2788366928fd1c586ac5885be5', '[\"*\"]', NULL, NULL, '2025-10-29 17:00:43', '2025-10-29 17:00:43'),
(23, 'App\\Models\\User', 1, 'api-token', 'b84ff8a2c0c6c68512b6c0e33adc7f56e9ec90503a274dd23dc00a710c6c381d', '[\"*\"]', '2025-10-29 17:10:54', NULL, '2025-10-29 17:03:24', '2025-10-29 17:10:54'),
(24, 'App\\Models\\User', 1, 'api-token', 'f2810801ed0d672a4e40c232f83c20701600f038ccca1706c090c5705bf314a4', '[\"*\"]', '2025-10-29 17:11:54', NULL, '2025-10-29 17:11:06', '2025-10-29 17:11:54'),
(25, 'App\\Models\\User', 1, 'api-token', '7ee90d6a2d64a67f944b3ecbffb27ebae9e8a3990741313b8cdb94baf6f46a3d', '[\"*\"]', NULL, NULL, '2025-10-29 17:49:12', '2025-10-29 17:49:12'),
(26, 'App\\Models\\User', 1, 'api-token', '4d3935e5a162e9ec8a673bf92838d10010317130ff1bee7b3fe06401576115b3', '[\"*\"]', NULL, NULL, '2025-10-29 17:51:56', '2025-10-29 17:51:56'),
(27, 'App\\Models\\User', 1, 'api-token', 'e3a7ea626c03d09124e89f29d9ca9608408f198f6b7c738b38596d9a8cb02753', '[\"*\"]', NULL, NULL, '2025-10-29 17:55:28', '2025-10-29 17:55:28'),
(28, 'App\\Models\\User', 1, 'api-token', '4e06a30184d3f87f88d7eea191ab9e50e6e307736ddd137db5cd155a43315ee6', '[\"*\"]', '2025-10-29 18:01:11', NULL, '2025-10-29 18:00:08', '2025-10-29 18:01:11'),
(29, 'App\\Models\\User', 1, 'api-token', 'c5bfba4008f60f5c48518c9bc4886523ad90e9ce785620471b37871a34fd8a24', '[\"*\"]', NULL, NULL, '2025-10-29 19:15:14', '2025-10-29 19:15:14'),
(30, 'App\\Models\\User', 7, 'api-token', 'dd49438e0dfe52b827c04762c5f029051fb84d442d3df5b27576f14f9a220f99', '[\"*\"]', NULL, NULL, '2025-10-29 19:17:38', '2025-10-29 19:17:38'),
(31, 'App\\Models\\User', 1, 'api-token', '6386c5ab76a8104c63599451f4a8affe570b643688ad5ccce6b6ad4963c2971a', '[\"*\"]', '2025-10-29 19:24:34', NULL, '2025-10-29 19:19:46', '2025-10-29 19:24:34'),
(32, 'App\\Models\\User', 1, 'api-token', '1878cc0ec04f2bfa4fc504de50a9640b78ad11ba8c2482747ca959011d7e820a', '[\"*\"]', '2025-10-30 17:17:17', NULL, '2025-10-30 17:17:03', '2025-10-30 17:17:17'),
(33, 'App\\Models\\User', 2, 'api-token', 'f0b4f55610e094ad2cfafd6eaa07045f7ab678677e0dae32f782ba4ad41167d0', '[\"*\"]', '2025-10-30 17:18:54', NULL, '2025-10-30 17:18:52', '2025-10-30 17:18:54'),
(34, 'App\\Models\\User', 7, 'api-token', '1d9582de3e2a020a1023c8dd519dce5ed7f20c229fe3bd98b7864b463c93fe27', '[\"*\"]', '2025-10-30 17:19:54', NULL, '2025-10-30 17:19:17', '2025-10-30 17:19:54'),
(35, 'App\\Models\\User', 1, 'api-token', '4bf1b22bf7d3ada50b9f45499c4eb0f2945bd6fd92049511df13d1ccfbae8967', '[\"*\"]', '2025-10-30 17:39:08', NULL, '2025-10-30 17:30:59', '2025-10-30 17:39:08'),
(36, 'App\\Models\\User', 7, 'api-token', 'aaf3291b8bedc31fce058a75661146174d708303f77829bb83a1518f78126016', '[\"*\"]', '2025-10-30 17:41:18', NULL, '2025-10-30 17:39:54', '2025-10-30 17:41:18'),
(37, 'App\\Models\\User', 1, 'api-token', 'fd41826262fbcb84a95f30e094dca11faafc2b9eb2af09e542e1160e1958fcf0', '[\"*\"]', '2025-11-04 18:02:20', NULL, '2025-11-03 05:58:28', '2025-11-04 18:02:20'),
(38, 'App\\Models\\User', 7, 'api-token', '80dde7b649be6548f4943834dfcd6c78eff05e2e336c2e3ff04ce92cb2665885', '[\"*\"]', '2025-11-04 18:04:32', NULL, '2025-11-04 18:03:42', '2025-11-04 18:04:32'),
(39, 'App\\Models\\User', 1, 'api-token', 'fc422c4b2e9b49aa50f0bca2e3d6e7c8b8ee16076bbf9b127329ec8161ce3997', '[\"*\"]', '2025-11-04 18:28:41', NULL, '2025-11-04 18:18:51', '2025-11-04 18:28:41'),
(40, 'App\\Models\\User', 7, 'api-token', '65164ff1b17b679526a34beab58072031fb54febb9866c60972bca4b0eb4706e', '[\"*\"]', '2025-11-04 18:30:10', NULL, '2025-11-04 18:29:29', '2025-11-04 18:30:10'),
(41, 'App\\Models\\User', 1, 'api-token', '6a0d0c69ed48da5d6cd5f5c6fb4173db78ebcfb70ec29b21f603aef6bab6244f', '[\"*\"]', '2025-11-05 03:25:42', NULL, '2025-11-04 18:30:36', '2025-11-05 03:25:42'),
(42, 'App\\Models\\User', 8, 'api-token', '312921f1a8044ce50a0641d86a1109eebf44a738b3cb950999ffbf7c0454a63c', '[\"*\"]', '2025-11-05 09:44:01', NULL, '2025-11-05 09:43:43', '2025-11-05 09:44:01'),
(43, 'App\\Models\\User', 7, 'api-token', '0c9ca1ea29a5ed52a11d0e31ea7b9900d38579293c9a0305b6ae60201e527ce7', '[\"*\"]', NULL, NULL, '2025-11-05 09:44:21', '2025-11-05 09:44:21'),
(44, 'App\\Models\\User', 8, 'api-token', '824876ef820514fc8e184c1d77318cfcb28ca067b59cd32666caf2082c12786b', '[\"*\"]', '2025-11-05 09:48:12', NULL, '2025-11-05 09:45:02', '2025-11-05 09:48:12'),
(45, 'App\\Models\\User', 8, 'api-token', 'c2b2971f17a4ee7aa64b89292c35ab546fc33d5a0dbe786eb366fdf6e80b49a1', '[\"*\"]', '2025-11-05 09:48:32', NULL, '2025-11-05 09:48:31', '2025-11-05 09:48:32'),
(46, 'App\\Models\\User', 7, 'api-token', '59818930476c4fa013d6667acf10f30a1b35dd66cece8127aab599a729b08261', '[\"*\"]', '2025-11-05 09:55:56', NULL, '2025-11-05 09:54:54', '2025-11-05 09:55:56'),
(47, 'App\\Models\\User', 8, 'api-token', 'd79c768a554637fdf9d20659b4963b2a5d2d98ab6e5281df9e7ff0541fa29183', '[\"*\"]', '2025-11-05 09:57:12', NULL, '2025-11-05 09:57:01', '2025-11-05 09:57:12'),
(48, 'App\\Models\\User', 8, 'api-token', '18835916b605283d165233a1154e0fbda6055cb096ffba2bc3cb41cf68fb0118', '[\"*\"]', '2025-11-12 10:57:08', NULL, '2025-11-12 10:47:29', '2025-11-12 10:57:08'),
(49, 'App\\Models\\User', 7, 'api-token', 'c7817022585799457aba051f7f7eb6a13bad3bff4f08bb6e1f37c8a9660dd72d', '[\"*\"]', '2025-11-12 11:02:18', NULL, '2025-11-12 10:58:04', '2025-11-12 11:02:18'),
(50, 'App\\Models\\User', 8, 'api-token', 'b838ba3d3d9af40011a550a35cef1acf99e4f52a176c53e2447afe9dc9bb9d77', '[\"*\"]', '2025-11-18 08:48:08', NULL, '2025-11-18 08:36:47', '2025-11-18 08:48:08'),
(51, 'App\\Models\\User', 8, 'api-token', '3dc39a8dca79d833bd97ea588513ed741703b7d662731815a015c17a8616fd0f', '[\"*\"]', '2025-11-18 08:53:15', NULL, '2025-11-18 08:50:31', '2025-11-18 08:53:15'),
(52, 'App\\Models\\User', 7, 'api-token', 'f41fcf1bca31f122d22c6bea621b40bcb554be257b03ab80daa37d7ec0cbc51f', '[\"*\"]', '2025-11-18 08:55:42', NULL, '2025-11-18 08:54:18', '2025-11-18 08:55:42'),
(53, 'App\\Models\\User', 8, 'api-token', '3f071e089e3a4c239b3973516a65ec468690714deba4e532929dd7745a275ef4', '[\"*\"]', '2025-11-18 09:18:07', NULL, '2025-11-18 08:56:25', '2025-11-18 09:18:07'),
(54, 'App\\Models\\User', 8, 'api-token', '4c568b57abf1793781ce485c8efe17b3522066d5fabab08dcff165d06bec3533', '[\"*\"]', '2025-11-18 09:19:22', NULL, '2025-11-18 09:19:21', '2025-11-18 09:19:22'),
(55, 'App\\Models\\User', 7, 'api-token', '083bcff9e113f77af620f3cbcd8d71437288dc014f65752b51e733b793d90498', '[\"*\"]', '2025-11-18 11:10:18', NULL, '2025-11-18 09:19:54', '2025-11-18 11:10:18'),
(56, 'App\\Models\\User', 7, 'api-token', 'c4c45c3f1a25cd18712265258ac4d1264058856175786fb318114ca6db578b86', '[\"*\"]', '2025-11-18 11:11:23', NULL, '2025-11-18 11:11:07', '2025-11-18 11:11:23'),
(57, 'App\\Models\\User', 8, 'api-token', 'd2754dfc2b300d721da2b0495f439219ad45bd08a0077bbf5a7dadcd16aa6e55', '[\"*\"]', '2025-11-19 07:18:34', NULL, '2025-11-18 11:11:57', '2025-11-19 07:18:34'),
(58, 'App\\Models\\User', 7, 'api-token', 'ad88f4d6d9fb127e0927d731941c4e9fd61365ce41ac70ad32f70ae4d49f6c31', '[\"*\"]', '2025-11-19 07:20:35', NULL, '2025-11-19 07:19:08', '2025-11-19 07:20:35'),
(59, 'App\\Models\\User', 8, 'api-token', '1118db3030bfe28b4a52df40c981625e7a56b66831c66a8bdf7d1b222b175460', '[\"*\"]', '2025-11-19 08:01:51', NULL, '2025-11-19 07:21:13', '2025-11-19 08:01:51'),
(60, 'App\\Models\\User', 7, 'api-token', '80a22c70abccac775f819a8df2d37dcee791f63832182e679b5aa872708c0edb', '[\"*\"]', '2025-11-19 08:03:44', NULL, '2025-11-19 08:03:27', '2025-11-19 08:03:44'),
(61, 'App\\Models\\User', 8, 'api-token', '907b59b9dd8371d98648d50eb6ba7da7b33f48fb59432210b15582f0caa5f47b', '[\"*\"]', '2025-11-20 08:26:27', NULL, '2025-11-19 08:03:59', '2025-11-20 08:26:27'),
(62, 'App\\Models\\User', 7, 'api-token', 'cad0cc18953732cbca03a738b6b1d380eefca8bf6a6a740a6afa42c62d89daec', '[\"*\"]', '2025-11-20 08:30:56', NULL, '2025-11-20 08:27:25', '2025-11-20 08:30:56'),
(63, 'App\\Models\\User', 8, 'api-token', '0a2410510ef60a6751a3e22e0890993326a9c0148d0d42ecb83d47c54d02d8be', '[\"*\"]', '2025-11-20 08:38:27', NULL, '2025-11-20 08:31:20', '2025-11-20 08:38:27'),
(64, 'App\\Models\\User', 7, 'api-token', '200c35539e4b26d65404c0bd1ea8d9ea3b87a5939e69df9f05323e3de99c4238', '[\"*\"]', '2025-11-20 08:43:55', NULL, '2025-11-20 08:39:06', '2025-11-20 08:43:55'),
(65, 'App\\Models\\User', 8, 'api-token', '4191fc61a22cadc3bbd36b57ce624050e410385d759b8a4e02f72fe613a7c021', '[\"*\"]', '2025-11-20 09:15:34', NULL, '2025-11-20 08:44:25', '2025-11-20 09:15:34'),
(66, 'App\\Models\\User', 7, 'api-token', 'fa37bd7ec7cc3b72eef03e1bcd840b43ea3873fa4d79d245e483c937a617e899', '[\"*\"]', '2025-11-20 09:17:47', NULL, '2025-11-20 09:16:01', '2025-11-20 09:17:47'),
(67, 'App\\Models\\User', 8, 'api-token', 'd388e0252897311f208c8b3a3ba51b86d50c8cda019ac6850b9933d6eb484bc1', '[\"*\"]', '2025-11-20 09:19:10', NULL, '2025-11-20 09:18:08', '2025-11-20 09:19:10'),
(68, 'App\\Models\\User', 8, 'api-token', 'dacf1c8e29113fc3340cb56aec69b06ab03a1d56f6bcdf10b8a4631a929e9bd9', '[\"*\"]', '2025-11-20 09:25:38', NULL, '2025-11-20 09:21:27', '2025-11-20 09:25:38'),
(69, 'App\\Models\\User', 2, 'api-token', '2b440f2319687e91bd3394d6d5290732dcb537e8d0ebd18138190ee8034bad6c', '[\"*\"]', NULL, NULL, '2025-11-20 09:26:34', '2025-11-20 09:26:34'),
(70, 'App\\Models\\User', 8, 'api-token', '851c480f0e8aca6bb9884143ee59f685b0624b73902df1d0262ba97874a4fc6a', '[\"*\"]', '2025-12-06 13:20:44', NULL, '2025-11-20 09:27:11', '2025-12-06 13:20:44'),
(71, 'App\\Models\\User', 8, 'api-token', '8f764c30c409282913c83e6592a20f08b35eae99b0a693bba4e969b2169ca106', '[\"*\"]', '2025-12-06 13:21:22', NULL, '2025-12-06 13:21:19', '2025-12-06 13:21:22'),
(72, 'App\\Models\\User', 7, 'api-token', '2283d15d42d2e3dfa571e501b0c28eb26877558796dbb419d684a9eb6e06761c', '[\"*\"]', '2025-12-06 13:23:41', NULL, '2025-12-06 13:21:43', '2025-12-06 13:23:41'),
(73, 'App\\Models\\User', 8, 'api-token', '59717674f3fc252b9da759565fc78e952bfec5eec563a6a28d20d917c4b1c087', '[\"*\"]', NULL, NULL, '2025-12-06 13:24:02', '2025-12-06 13:24:02'),
(74, 'App\\Models\\User', 7, 'api-token', '381c639e3c62a23de3a2abeb355dedb59eaf930d737608ca1b405be432bc0611', '[\"*\"]', '2025-12-11 16:29:46', NULL, '2025-12-11 16:29:28', '2025-12-11 16:29:46'),
(75, 'App\\Models\\User', 8, 'api-token', 'a33449d5dd468d7ed6758f9c25d1450ff9760bea1ad3ea337bbcabcfad66b240', '[\"*\"]', '2025-12-11 16:30:19', NULL, '2025-12-11 16:30:18', '2025-12-11 16:30:19'),
(76, 'App\\Models\\User', 7, 'api-token', 'e1dcbd2240b38b3ef2a75f8ce30648e642bc04eba0bd14900d02dc577540191d', '[\"*\"]', '2025-12-14 08:48:07', NULL, '2025-12-11 16:31:12', '2025-12-14 08:48:07'),
(77, 'App\\Models\\User', 8, 'api-token', 'b1e94d44bd3daafe048e603ebfb5356b8ec0a33597fe48e1fed9e339ecf5b704', '[\"*\"]', '2025-12-14 19:21:18', NULL, '2025-12-14 08:48:31', '2025-12-14 19:21:18'),
(78, 'App\\Models\\User', 7, 'api-token', '896f01d40d0b5fcc043a033caa44238484e636bfe1cd26d3dd55772cf827fe17', '[\"*\"]', '2025-12-14 19:23:38', NULL, '2025-12-14 19:22:35', '2025-12-14 19:23:38'),
(79, 'App\\Models\\User', 8, 'api-token', '02dd2fd593bfabf6bbe0ec836d1f4364fac1fa1484b0b32db012072d7d05943e', '[\"*\"]', NULL, NULL, '2025-12-14 23:47:33', '2025-12-14 23:47:33'),
(80, 'App\\Models\\User', 2, 'api-token', '78dc5e0932f6e8addcdd056faf8d2ac931c3222c068ee84d891a348d42c84a34', '[\"*\"]', NULL, NULL, '2025-12-15 00:18:52', '2025-12-15 00:18:52'),
(81, 'App\\Models\\User', 8, 'api-token', '1c1039292e6ae8e2f521d8a43c10786b13c0c48b6a85a32fc14188c49d289d26', '[\"*\"]', '2025-12-15 00:49:21', NULL, '2025-12-15 00:40:59', '2025-12-15 00:49:21'),
(82, 'App\\Models\\User', 7, 'api-token', 'b620955a3128c66cf60a5c340a3a21b2ab7da353d144a2fbc709edc32602384d', '[\"*\"]', '2025-12-15 00:55:43', NULL, '2025-12-15 00:50:06', '2025-12-15 00:55:43'),
(83, 'App\\Models\\User', 8, 'api-token', '5cae34f805a3942e4e2205b75b8ec7db8ddd266f8d04e1ae28ea5a2ca6868882', '[\"*\"]', '2025-12-15 01:12:30', NULL, '2025-12-15 00:56:00', '2025-12-15 01:12:30'),
(84, 'App\\Models\\User', 7, 'api-token', 'b543d09016797e9850565fc74b7c42f726e5ec59cb6622cc20cc00bb7ac84292', '[\"*\"]', '2025-12-15 01:15:17', NULL, '2025-12-15 01:12:54', '2025-12-15 01:15:17'),
(85, 'App\\Models\\User', 8, 'api-token', 'ceea2f545366748d5776a974694f270ecc8737ed064ed1e02143e669fe849cb3', '[\"*\"]', '2025-12-15 01:31:49', NULL, '2025-12-15 01:16:53', '2025-12-15 01:31:49');

-- --------------------------------------------------------

--
-- Table structure for table `tb_detail_transaksi`
--

CREATE TABLE `tb_detail_transaksi` (
  `id` bigint UNSIGNED NOT NULL,
  `transaksi_id` bigint UNSIGNED NOT NULL,
  `produk_id` bigint UNSIGNED NOT NULL,
  `jumlah` int NOT NULL,
  `subtotal` decimal(12,2) NOT NULL,
  `subtotal_modal` decimal(12,2) NOT NULL DEFAULT '0.00',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tb_detail_transaksi`
--

INSERT INTO `tb_detail_transaksi` (`id`, `transaksi_id`, `produk_id`, `jumlah`, `subtotal`, `subtotal_modal`, `created_at`, `updated_at`) VALUES
(18, 14, 27, 2, '524000.00', '488000.00', '2025-11-19 07:20:22', '2025-11-19 07:20:22'),
(19, 15, 33, 1, '425000.00', '399000.00', '2025-11-20 08:30:46', '2025-11-20 08:30:46'),
(20, 16, 13, 1, '263000.00', '249000.00', '2025-11-20 09:16:48', '2025-11-20 09:16:48'),
(21, 17, 10, 5, '825000.00', '780000.00', '2025-12-06 13:23:16', '2025-12-06 13:23:16'),
(22, 18, 12, 10, '1150000.00', '1000000.00', '2025-12-06 13:23:42', '2025-12-06 13:23:42'),
(23, 19, 27, 1, '262000.00', '244000.00', '2025-12-11 16:32:20', '2025-12-11 16:32:20'),
(24, 20, 26, 1, '612000.00', '595000.00', '2025-12-14 08:48:07', '2025-12-14 08:48:07'),
(25, 21, 27, 10, '2620000.00', '2440000.00', '2025-12-15 00:51:51', '2025-12-15 00:51:51'),
(26, 22, 31, 100, '25500000.00', '23000000.00', '2025-12-15 01:14:27', '2025-12-15 01:14:27');

-- --------------------------------------------------------

--
-- Table structure for table `tb_kategori_produk`
--

CREATE TABLE `tb_kategori_produk` (
  `id` bigint UNSIGNED NOT NULL,
  `nama_kategori` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `deskripsi` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tb_kategori_produk`
--

INSERT INTO `tb_kategori_produk` (`id`, `nama_kategori`, `deskripsi`, `created_at`, `updated_at`) VALUES
(1, 'Sembako', 'set', '2025-09-14 03:26:09', '2025-10-28 13:56:04'),
(2, 'Minuman', NULL, '2025-09-14 03:26:09', '2025-09-14 03:26:09'),
(3, 'Makanan Ringan & Snack', NULL, '2025-09-14 03:26:09', '2025-09-14 03:26:09'),
(4, 'Rokok & Produk Tembakau', NULL, '2025-09-14 03:26:09', '2025-09-14 03:26:09'),
(5, 'Produk Kebersihan & Rumah Tangga', NULL, '2025-09-14 03:26:09', '2025-09-14 03:26:09'),
(6, 'Kebutuhan Dapur & Masak', NULL, '2025-09-14 03:26:09', '2025-09-14 03:26:09'),
(7, 'Perawatan Diri & Kosmetik', NULL, '2025-09-14 03:26:09', '2025-09-14 03:26:09'),
(8, 'Bahan Segar', NULL, '2025-09-14 03:26:09', '2025-09-14 03:26:09'),
(9, 'Alat Tulis Kantor (ATK)', NULL, '2025-09-14 03:26:09', '2025-09-14 03:26:09'),
(13, 'kosmetik', NULL, '2025-10-30 17:35:03', '2025-10-30 17:35:03');

-- --------------------------------------------------------

--
-- Table structure for table `tb_pengguna`
--

CREATE TABLE `tb_pengguna` (
  `id` bigint UNSIGNED NOT NULL,
  `nama` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `username` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `hak_akses` enum('admin','pemilik','kasir') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tb_pengguna`
--

INSERT INTO `tb_pengguna` (`id`, `nama`, `username`, `password`, `hak_akses`, `created_at`, `updated_at`) VALUES
(2, 'pemilik', 'pemilik', '$2y$12$v1zizDNWH32o6HVUe7ikrOEFp/WHtV2qPpIai5qE3iKx0d9g9IAb.', 'pemilik', '2025-09-14 03:26:08', '2025-09-14 03:26:08'),
(7, 'Limeii', 'limeiilim', '$2y$12$oKYzag51os0p9TV/.vcPjuyBIu0xp/PUVKQE2anzT/8zHHLfHza0e', 'kasir', '2025-10-28 14:48:17', '2025-11-05 03:22:27'),
(8, 'admin', 'admin', '$2y$12$dIXjcOQQLox.XN9xiRGwfO1l/qpi3DkThjCK2r0MCDcNE7UHt73km', 'admin', '2025-11-05 09:42:17', '2025-11-05 09:42:17');

-- --------------------------------------------------------

--
-- Table structure for table `tb_produk`
--

CREATE TABLE `tb_produk` (
  `id` bigint UNSIGNED NOT NULL,
  `satuan_id` bigint UNSIGNED NOT NULL,
  `kategori_id` bigint UNSIGNED NOT NULL,
  `kode_produk` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `nama_produk` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `harga_modal` decimal(15,2) NOT NULL DEFAULT '0.00',
  `harga` decimal(10,2) NOT NULL,
  `stok` bigint NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tb_produk`
--

INSERT INTO `tb_produk` (`id`, `satuan_id`, `kategori_id`, `kode_produk`, `nama_produk`, `harga_modal`, `harga`, `stok`, `created_at`, `updated_at`) VALUES
(10, 65, 2, '123', 'LARUTAN CAP BADAK', '156000.00', '165000.00', 445, '2025-11-18 09:18:06', '2025-12-15 00:59:14'),
(11, 12, 2, '111', 'POCARISWEAT 2000ML', '115000.00', '125000.00', 400, '2025-11-18 11:18:57', '2025-11-18 11:18:57'),
(12, 12, 2, '222', 'YAKULT', '100000.00', '115000.00', 290, '2025-11-18 11:21:15', '2025-12-06 13:23:42'),
(13, 12, 2, '333', 'BENECOL ORANGE', '249000.00', '263000.00', 399, '2025-11-18 11:25:31', '2025-11-20 09:16:48'),
(14, 12, 2, '444', 'TEH RIO', '19000.00', '28000.00', 300, '2025-11-18 11:28:33', '2025-11-18 11:28:33'),
(15, 12, 2, '555', 'SUSU CLEVO 115ML', '105000.00', '114990.00', 250, '2025-11-18 11:30:39', '2025-11-18 11:30:39'),
(16, 12, 3, '777', 'ROMA BISCUIT KELAPA', '235000.00', '248000.00', 500, '2025-11-18 13:33:31', '2025-11-18 13:33:31'),
(17, 12, 3, '888', 'NIISIN SOES', '243000.00', '252000.00', 320, '2025-11-18 16:00:14', '2025-11-18 16:00:14'),
(18, 12, 7, '987', 'EMERON 20SACET', '97000.00', '112000.00', 400, '2025-11-18 16:17:45', '2025-11-18 16:17:45'),
(19, 12, 7, '321', 'DOVE(480SACET)', '388000.00', '400000.00', 370, '2025-11-18 16:21:58', '2025-11-18 16:21:58'),
(20, 12, 7, '112', 'SUNSLIK', '388000.00', '399000.00', 420, '2025-11-18 16:28:56', '2025-11-18 16:28:56'),
(21, 12, 7, '133', 'DETOL', '532000.00', '542000.00', 340, '2025-11-18 16:31:13', '2025-11-18 16:31:13'),
(22, 12, 7, '223', 'ZWITSAL', '675000.00', '686000.00', 300, '2025-11-18 16:35:09', '2025-11-18 16:35:09'),
(23, 11, 9, '224', 'MAX ISI STAPLER', '85000.00', '98000.00', 240, '2025-11-18 19:24:49', '2025-11-18 19:24:49'),
(24, 11, 9, '443', 'SPIDOL SNOWMAN', '230000.00', '241000.00', 310, '2025-11-18 19:38:17', '2025-11-18 19:38:17'),
(25, 11, 9, '551', 'HAKTER', '225000.00', '240000.00', 400, '2025-11-18 19:41:14', '2025-11-18 19:41:14'),
(26, 11, 9, '664', 'PENGGARIS', '595000.00', '612000.00', 199, '2025-11-19 05:52:25', '2025-12-14 08:48:07'),
(27, 14, 4, '556', 'SAMPOERNA MILD (12)', '244000.00', '262000.00', 287, '2025-11-19 06:52:46', '2025-12-15 00:51:51'),
(28, 14, 4, '998', 'SAMPOERNA MILD(16)', '341000.00', '363000.00', 500, '2025-11-19 06:55:36', '2025-11-19 06:55:36'),
(29, 14, 4, '711', 'MARLBORO FILTER BLACK (12)', '230000.00', '251000.00', 320, '2025-11-19 06:57:40', '2025-11-19 06:57:40'),
(30, 14, 4, '944', 'MARLBORO FILTER BLACK (16)', '303000.00', '323000.00', 200, '2025-11-19 07:02:28', '2025-11-19 07:02:28'),
(31, 14, 4, '288', 'MAGNUM', '230000.00', '255000.00', 0, '2025-11-19 07:04:59', '2025-12-15 01:14:27'),
(32, 14, 4, '677', 'MARLBORO FILTER HARDPACK (20)', '496000.00', '520000.00', 300, '2025-11-19 07:11:31', '2025-11-19 07:11:31'),
(33, 14, 4, '977', 'ESSE MILD FILTER (20)', '399000.00', '425000.00', 319, '2025-11-19 07:15:23', '2025-11-20 08:30:46'),
(34, 14, 4, '955', 'DJI SAMSOE KRETEK (16)', '261000.00', '282000.00', 320, '2025-11-19 07:17:24', '2025-11-19 07:17:24'),
(35, 14, 4, '227', 'DUNHIL FINE CUT MILD (16)', '293000.00', '320000.00', 200, '2025-11-19 08:07:16', '2025-11-19 08:07:16'),
(36, 14, 4, '911', 'LUCKY STRIKE(20)', '283000.00', '312000.00', 300, '2025-11-19 08:11:07', '2025-11-19 08:11:07'),
(37, 14, 4, '700', 'ESSE CHANGE FILTER DOUBLE (20)', '422000.00', '453000.00', 250, '2025-11-19 08:14:43', '2025-11-19 08:14:43'),
(38, 14, 4, '799', 'GUDANG GARAM FILTER SURYA (16)', '339000.00', '362000.00', 157, '2025-11-19 08:22:45', '2025-11-19 08:22:45');

-- --------------------------------------------------------

--
-- Table structure for table `tb_riwayat_produk_masuk`
--

CREATE TABLE `tb_riwayat_produk_masuk` (
  `id` bigint UNSIGNED NOT NULL,
  `produk_id` bigint UNSIGNED NOT NULL,
  `stok` int NOT NULL,
  `distributor` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tanggal_masuk` timestamp NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tb_riwayat_produk_masuk`
--

INSERT INTO `tb_riwayat_produk_masuk` (`id`, `produk_id`, `stok`, `distributor`, `tanggal_masuk`, `created_at`, `updated_at`) VALUES
(1, 10, 50, 'Grosindo', '2025-12-06 13:19:07', '2025-12-06 13:19:07', '2025-12-06 13:19:07'),
(2, 10, 100, 'sampoerna', '2025-12-15 00:59:14', '2025-12-15 00:59:14', '2025-12-15 00:59:14');

-- --------------------------------------------------------

--
-- Table structure for table `tb_satuan`
--

CREATE TABLE `tb_satuan` (
  `id` bigint UNSIGNED NOT NULL,
  `kode_satuan` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `nama_satuan` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `deskripsi` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tb_satuan`
--

INSERT INTO `tb_satuan` (`id`, `kode_satuan`, `nama_satuan`, `deskripsi`, `created_at`, `updated_at`) VALUES
(1, 'PCS', 'Pieces', NULL, '2025-09-14 03:26:07', '2025-09-14 03:26:07'),
(2, 'UNIT', 'Unit', NULL, '2025-09-14 03:26:07', '2025-09-14 03:26:07'),
(3, 'BTL', 'Botol', NULL, '2025-09-14 03:26:07', '2025-09-14 03:26:07'),
(4, 'KLG', 'Kaleng', NULL, '2025-09-14 03:26:07', '2025-09-14 03:26:07'),
(5, 'SCT', 'Sachet', NULL, '2025-09-14 03:26:07', '2025-09-14 03:26:07'),
(6, 'BKS', 'Bungkus', NULL, '2025-09-14 03:26:07', '2025-09-14 03:26:07'),
(7, 'TAB', 'Tabung', NULL, '2025-09-14 03:26:07', '2025-09-14 03:26:07'),
(8, 'KPS', 'Kapsul', NULL, '2025-09-14 03:26:07', '2025-09-14 03:26:07'),
(9, 'TBL', 'Tablet/Kapsul', NULL, '2025-09-14 03:26:07', '2025-09-14 03:26:07'),
(10, 'ROL', 'Rol', NULL, '2025-09-14 03:26:07', '2025-09-14 03:26:07'),
(11, 'PACK', 'Pack', NULL, '2025-09-14 03:26:07', '2025-09-14 03:26:07'),
(12, 'DUS', 'Karton/Dus', NULL, '2025-09-14 03:26:07', '2025-09-14 03:26:07'),
(13, 'BALL', 'Ball', NULL, '2025-09-14 03:26:07', '2025-09-14 03:26:07'),
(14, 'SLOP', 'Slop', NULL, '2025-09-14 03:26:07', '2025-09-14 03:26:07'),
(15, 'RTG', 'Renteng', NULL, '2025-09-14 03:26:07', '2025-09-14 03:26:07'),
(16, 'KG', 'Kilogram', NULL, '2025-09-14 03:26:07', '2025-09-14 03:26:07'),
(17, 'GR', 'Gram', NULL, '2025-09-14 03:26:07', '2025-09-14 03:26:07'),
(18, 'LTR', 'Liter', NULL, '2025-09-14 03:26:07', '2025-09-14 03:26:07'),
(19, 'ML', 'Mililiter', NULL, '2025-09-14 03:26:07', '2025-09-14 03:26:07'),
(20, 'SAK', 'Sak', NULL, '2025-09-14 03:26:07', '2025-09-14 03:26:07'),
(21, 'KRG', 'Karung', NULL, '2025-09-14 03:26:07', '2025-09-14 03:26:07'),
(22, 'GLN', 'Galon', NULL, '2025-09-14 03:26:07', '2025-09-14 03:26:07'),
(23, 'DRM', 'Drum', NULL, '2025-09-14 03:26:07', '2025-09-14 03:26:07'),
(24, 'MTR', 'Meter', NULL, '2025-09-14 03:26:07', '2025-09-14 03:26:07'),
(25, 'LMBR', 'Lembar', NULL, '2025-09-14 03:26:07', '2025-09-14 03:26:07'),
(26, 'PKT', 'Paket', NULL, '2025-09-14 03:26:07', '2025-09-14 03:26:07'),
(27, 'BOX', 'Box', NULL, '2025-09-14 03:26:07', '2025-09-14 03:26:07'),
(28, 'SET', 'Set', NULL, '2025-09-14 03:26:07', '2025-09-14 03:26:07'),
(29, 'PAIR', 'Pair (Pasang)', NULL, '2025-09-14 03:26:07', '2025-09-14 03:26:07'),
(30, 'STRIP', 'Strip', NULL, '2025-09-14 03:26:07', '2025-09-14 03:26:07'),
(65, 'LS', 'Lusin', NULL, '2025-11-20 09:23:49', '2025-11-20 09:23:49');

-- --------------------------------------------------------

--
-- Table structure for table `tb_transaksi`
--

CREATE TABLE `tb_transaksi` (
  `id` bigint UNSIGNED NOT NULL,
  `no_nota` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `tgl_transaksi` datetime NOT NULL,
  `harga_total` decimal(10,2) NOT NULL,
  `kasir_id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tb_transaksi`
--

INSERT INTO `tb_transaksi` (`id`, `no_nota`, `tgl_transaksi`, `harga_total`, `kasir_id`, `created_at`, `updated_at`) VALUES
(14, 'NT2511190001', '2025-11-19 14:20:22', '524000.00', 7, '2025-11-19 07:20:22', '2025-11-19 07:20:22'),
(15, 'NT2511200015', '2025-11-20 15:30:46', '425000.00', 7, '2025-11-20 08:30:46', '2025-11-20 08:30:46'),
(16, 'NT2511200016', '2025-11-20 16:16:48', '263000.00', 7, '2025-11-20 09:16:48', '2025-11-20 09:16:48'),
(17, 'NT2512060017', '2025-12-06 20:23:16', '825000.00', 7, '2025-12-06 13:23:16', '2025-12-06 13:23:16'),
(18, 'NT2512060018', '2025-12-06 20:23:41', '1150000.00', 7, '2025-12-06 13:23:41', '2025-12-06 13:23:41'),
(19, 'NT2512110019', '2025-12-11 23:32:20', '262000.00', 7, '2025-12-11 16:32:20', '2025-12-11 16:32:20'),
(20, 'NT2512140020', '2025-12-14 15:48:07', '612000.00', 7, '2025-12-14 08:48:07', '2025-12-14 08:48:07'),
(21, 'NT2512150021', '2025-12-15 07:51:51', '2620000.00', 7, '2025-12-15 00:51:51', '2025-12-15 00:51:51'),
(22, 'NT2512150022', '2025-12-15 08:14:27', '25500000.00', 7, '2025-12-15 01:14:27', '2025-12-15 01:14:27');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`),
  ADD KEY `personal_access_tokens_expires_at_index` (`expires_at`);

--
-- Indexes for table `tb_detail_transaksi`
--
ALTER TABLE `tb_detail_transaksi`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tb_detail_transaksi_transaksi_id_foreign` (`transaksi_id`),
  ADD KEY `tb_detail_transaksi_produk_id_foreign` (`produk_id`);

--
-- Indexes for table `tb_kategori_produk`
--
ALTER TABLE `tb_kategori_produk`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `tb_kategori_produk_nama_kategori_unique` (`nama_kategori`);

--
-- Indexes for table `tb_pengguna`
--
ALTER TABLE `tb_pengguna`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `tb_pengguna_username_unique` (`username`);

--
-- Indexes for table `tb_produk`
--
ALTER TABLE `tb_produk`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `tb_produk_kode_produk_unique` (`kode_produk`),
  ADD KEY `tb_produk_satuan_id_foreign` (`satuan_id`),
  ADD KEY `tb_produk_kategori_id_foreign` (`kategori_id`);

--
-- Indexes for table `tb_riwayat_produk_masuk`
--
ALTER TABLE `tb_riwayat_produk_masuk`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tb_riwayat_produk_masuk_produk_id_foreign` (`produk_id`);

--
-- Indexes for table `tb_satuan`
--
ALTER TABLE `tb_satuan`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `tb_satuan_kode_satuan_unique` (`kode_satuan`);

--
-- Indexes for table `tb_transaksi`
--
ALTER TABLE `tb_transaksi`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `tb_transaksi_no_nota_unique` (`no_nota`),
  ADD KEY `tb_transaksi_kasir_id_foreign` (`kasir_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=86;

--
-- AUTO_INCREMENT for table `tb_detail_transaksi`
--
ALTER TABLE `tb_detail_transaksi`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT for table `tb_kategori_produk`
--
ALTER TABLE `tb_kategori_produk`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `tb_pengguna`
--
ALTER TABLE `tb_pengguna`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `tb_produk`
--
ALTER TABLE `tb_produk`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;

--
-- AUTO_INCREMENT for table `tb_riwayat_produk_masuk`
--
ALTER TABLE `tb_riwayat_produk_masuk`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `tb_satuan`
--
ALTER TABLE `tb_satuan`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=66;

--
-- AUTO_INCREMENT for table `tb_transaksi`
--
ALTER TABLE `tb_transaksi`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `tb_detail_transaksi`
--
ALTER TABLE `tb_detail_transaksi`
  ADD CONSTRAINT `tb_detail_transaksi_produk_id_foreign` FOREIGN KEY (`produk_id`) REFERENCES `tb_produk` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `tb_detail_transaksi_transaksi_id_foreign` FOREIGN KEY (`transaksi_id`) REFERENCES `tb_transaksi` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `tb_produk`
--
ALTER TABLE `tb_produk`
  ADD CONSTRAINT `tb_produk_kategori_id_foreign` FOREIGN KEY (`kategori_id`) REFERENCES `tb_kategori_produk` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `tb_produk_satuan_id_foreign` FOREIGN KEY (`satuan_id`) REFERENCES `tb_satuan` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `tb_riwayat_produk_masuk`
--
ALTER TABLE `tb_riwayat_produk_masuk`
  ADD CONSTRAINT `tb_riwayat_produk_masuk_produk_id_foreign` FOREIGN KEY (`produk_id`) REFERENCES `tb_produk` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `tb_transaksi`
--
ALTER TABLE `tb_transaksi`
  ADD CONSTRAINT `tb_transaksi_kasir_id_foreign` FOREIGN KEY (`kasir_id`) REFERENCES `tb_pengguna` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
