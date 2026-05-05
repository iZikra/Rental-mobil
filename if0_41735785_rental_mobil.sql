-- phpMyAdmin SQL Dump
-- version 4.9.0.1
-- https://www.phpmyadmin.net/
--
-- Host: sql212.infinityfree.com
-- Generation Time: May 03, 2026 at 10:11 PM
-- Server version: 11.4.10-MariaDB
-- PHP Version: 7.2.22

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `if0_41735785_rental_mobil`
--

-- --------------------------------------------------------

--
-- Table structure for table `branches`
--

CREATE TABLE `branches` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `rental_id` bigint(20) UNSIGNED NOT NULL,
  `nama_cabang` varchar(255) NOT NULL,
  `kota` varchar(255) NOT NULL,
  `alamat_lengkap` text NOT NULL,
  `nomor_telepon_cabang` varchar(255) NOT NULL,
  `koordinat_lokasi` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `branches`
--

INSERT INTO `branches` (`id`, `rental_id`, `nama_cabang`, `kota`, `alamat_lengkap`, `nomor_telepon_cabang`, `koordinat_lokasi`, `created_at`, `updated_at`) VALUES
(1, 1, 'FZ RENT CAR', 'Pekanbaru', 'Jl.Teropong, Perum Surya Mandiri Blok h-4', '085375285567', NULL, '2026-04-05 00:24:11', '2026-04-16 08:58:00'),
(2, 2, 'PUTRA WIDJAYA RENT CAR JAKARTA', 'Jakarta', 'Jl.Cempaka Putih Tengah XXXI no.5b , jakarta pusat', '083845966102', NULL, '2026-04-05 00:38:01', '2026-04-16 08:59:13'),
(3, 3, 'AA RENT CAR', 'Pekanbaru', 'Bencah Lesung, Kec. Tenayan Raya, Kota Pekanbaru, Riau 28281', '085278820591', NULL, '2026-04-07 09:26:51', '2026-04-07 09:26:51'),
(4, 4, 'EVAN RENTAL mobil pekanbaru', 'Pekanbaru', 'Jl. HR Subrantas Gg Amal Perumahan Dahlia Garden Blok B3', '081374328872', NULL, '2026-04-10 00:16:31', '2026-04-10 00:16:31'),
(5, 5, 'PT. TRANS NUSANTARA GEMILANG RENT CAR', 'Pekanbaru', 'Gg. Perwira I No.9, Labuh Baru Tim., Kec. Payung Sekaki, Kota Pekanbaru, Riau', '081277864411', NULL, '2026-04-10 00:42:03', '2026-04-10 00:42:03');

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
-- Table structure for table `cars`
--

CREATE TABLE `cars` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nama_mobil` varchar(255) NOT NULL,
  `merek` varchar(255) NOT NULL,
  `type` varchar(255) DEFAULT NULL,
  `slug` varchar(255) NOT NULL,
  `gambar` varchar(255) NOT NULL,
  `harga_sewa` int(11) NOT NULL,
  `harga_mobil_12h` int(11) DEFAULT 0,
  `harga_mobil_24h` int(11) DEFAULT 0,
  `harga_driver_12h` int(11) DEFAULT 0,
  `harga_driver_24h` int(11) DEFAULT 0,
  `harga_bbm_4h` int(11) DEFAULT 0,
  `harga_bbm_12h` int(11) DEFAULT 0,
  `harga_bbm_24h` int(11) DEFAULT 0,
  `harga_allin_4h` int(11) DEFAULT 0,
  `harga_allin_6h` int(11) DEFAULT 0,
  `harga_allin_12h` int(11) DEFAULT 0,
  `harga_allin_24h` int(11) DEFAULT 0,
  `bahan_bakar` varchar(255) NOT NULL,
  `jumlah_kursi` int(11) NOT NULL,
  `transmisi` varchar(255) NOT NULL,
  `status` enum('tersedia','tidak tersedia') NOT NULL DEFAULT 'tersedia',
  `deskripsi` text NOT NULL,
  `p3k` varchar(255) NOT NULL,
  `charger` varchar(255) NOT NULL,
  `ac` varchar(255) NOT NULL,
  `audio` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `chat_logs`
--

CREATE TABLE `chat_logs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `session_id` varchar(255) DEFAULT NULL,
  `user_message` text NOT NULL,
  `bot_response` text NOT NULL,
  `rental_id` bigint(20) UNSIGNED DEFAULT NULL,
  `model_used` varchar(255) NOT NULL DEFAULT 'Llama-3',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `chat_logs`
--

INSERT INTO `chat_logs` (`id`, `user_id`, `session_id`, `user_message`, `bot_response`, `rental_id`, `model_used`, `created_at`, `updated_at`) VALUES
(1, NULL, 'ayRiXiwF79VjjQUrCmjTlRO80tqTfntUrfPkkcXx', 'hai', 'Maaf, ada kendala teknis. Bisa ulangi?', 1, 'Llama-3-RAG-Hybrid', '2026-04-27 18:20:58', '2026-04-27 18:20:58'),
(2, NULL, 'ayRiXiwF79VjjQUrCmjTlRO80tqTfntUrfPkkcXx', 'hai', 'Maaf, ada kendala teknis. Bisa ulangi?', 1, 'Llama-3-RAG-Hybrid', '2026-04-27 18:28:16', '2026-04-27 18:28:16'),
(3, NULL, 'ayRiXiwF79VjjQUrCmjTlRO80tqTfntUrfPkkcXx', 'hai', 'Maaf, ada kendala teknis. Bisa ulangi?', 1, 'Llama-3-RAG-Hybrid', '2026-04-27 18:28:39', '2026-04-27 18:28:39'),
(4, NULL, 'ayRiXiwF79VjjQUrCmjTlRO80tqTfntUrfPkkcXx', 'hai', 'Maaf, ada kendala teknis. Bisa ulangi?', 1, 'Llama-3-RAG-Hybrid', '2026-04-27 19:33:22', '2026-04-27 19:33:22'),
(5, NULL, 'ayRiXiwF79VjjQUrCmjTlRO80tqTfntUrfPkkcXx', 'hai', 'Maaf, ada kendala teknis. Bisa ulangi?', 1, 'Llama-3-RAG-Hybrid', '2026-04-27 19:41:43', '2026-04-27 19:41:43'),
(6, NULL, 'ayRiXiwF79VjjQUrCmjTlRO80tqTfntUrfPkkcXx', 'hai', 'Maaf, ada kendala teknis. Bisa ulangi?', 1, 'Llama-3-RAG-Hybrid', '2026-04-27 19:44:54', '2026-04-27 19:44:54'),
(7, NULL, 'ayRiXiwF79VjjQUrCmjTlRO80tqTfntUrfPkkcXx', 'hai', 'Hai! Selamat datang di FZ Rent Car. Bagaimana saya bisa membantu Anda hari ini?', 1, 'Llama-3-RAG-Hybrid', '2026-04-27 19:48:22', '2026-04-27 19:48:22'),
(8, NULL, 'ayRiXiwF79VjjQUrCmjTlRO80tqTfntUrfPkkcXx', 'hai', 'Hai! Selamat datang di FZ Rent Car. Bagaimana saya bisa membantu Anda hari ini?', 1, 'Llama-3-RAG-Hybrid', '2026-04-27 19:54:18', '2026-04-27 19:54:18'),
(9, NULL, 'ayRiXiwF79VjjQUrCmjTlRO80tqTfntUrfPkkcXx', 'hai', 'Hai! Selamat datang di FZ Rent Car. Bagaimana saya dapat membantu Anda hari ini?', 1, 'Llama-3-RAG-Hybrid', '2026-04-27 19:55:36', '2026-04-27 19:55:36'),
(10, NULL, 'ayRiXiwF79VjjQUrCmjTlRO80tqTfntUrfPkkcXx', 'hai', 'Selamat datang di platform pencarian mobil! Saya siap membantu Anda menemukan mobil yang sesuai dengan kebutuhan Anda. Apa yang Anda cari? Mobil untuk perjalanan bisnis, keluarga, atau solo?', 1, 'Llama-3-RAG-Hybrid', '2026-04-27 20:04:25', '2026-04-27 20:04:25'),
(11, NULL, 'ayRiXiwF79VjjQUrCmjTlRO80tqTfntUrfPkkcXx', 'saya sedang mencari mobil untuk bepergian', 'Baiklah, saya akan membantu Anda menemukan mobil yang sesuai untuk perjalanan Anda. Apakah Anda memiliki preferensi tertentu, seperti jenis mobil, jumlah penumpang, atau kota tujuan?', 1, 'Llama-3-RAG-Hybrid', '2026-04-27 20:04:41', '2026-04-27 20:04:41'),
(12, NULL, 'ayRiXiwF79VjjQUrCmjTlRO80tqTfntUrfPkkcXx', 'saya mencari mobil irit untuk saya tumpangi sendiri', 'Mobil irit untuk satu orang, itu sangat baik! Kami memiliki beberapa pilihan mobil irit yang mungkin sesuai untuk Anda. Apakah Anda ingin melihat beberapa opsi mobil yang kami tawarkan?', 1, 'Llama-3-RAG-Hybrid', '2026-04-27 20:05:14', '2026-04-27 20:05:14'),
(13, NULL, 'ayRiXiwF79VjjQUrCmjTlRO80tqTfntUrfPkkcXx', 'iya', 'Kami memiliki beberapa pilihan mobil irit yang mungkin sesuai untuk Anda. Berikut beberapa opsi mobil yang kami tawarkan:<br><br>1. Toyota Ayla - Mobil irit dengan harga Rp 300.000/hari<br>2. Daihatsu Sigra - Mobil irit dengan harga Rp 300.000/hari<br>3. Honda Brio - Mobil irit dengan harga Rp 500.000/hari<br>4. Daihatsu Rocky - Mobil irit dengan harga Rp 350.000/hari<br><br>Silakan pilih salah satu opsi mobil yang Anda inginkan.', 1, 'Llama-3-RAG-Hybrid', '2026-04-27 20:05:52', '2026-04-27 20:05:52'),
(14, NULL, 'ayRiXiwF79VjjQUrCmjTlRO80tqTfntUrfPkkcXx', 'saya mau brio', 'Anda telah memilih Honda Brio sebagai pilihan mobil. Harga sewa untuk Honda Brio adalah Rp 500.000/hari. Apakah Anda ingin memesan mobil ini?', 1, 'Llama-3-RAG-Hybrid', '2026-04-27 20:07:32', '2026-04-27 20:07:32'),
(15, NULL, 'ayRiXiwF79VjjQUrCmjTlRO80tqTfntUrfPkkcXx', 'apakah kamu tau lokasi saya', 'Tolong informasikan lokasi Anda, sehingga saya dapat mengetahui ketersediaan Honda Brio di lokasi Anda.', 1, 'Llama-3-RAG-Hybrid', '2026-04-27 20:07:55', '2026-04-27 20:07:55'),
(16, NULL, 'ayRiXiwF79VjjQUrCmjTlRO80tqTfntUrfPkkcXx', 'saya di riau', 'Honda Brio tersedia di Jakarta. Apakah Anda ingin memesan mobil ini? Jika ya, saya dapat membantu Anda memesan mobil ini di Jakarta dan Anda dapat mengambilnya di sana. Jika Anda ingin memesan mobil lain di Riau, saya dapat membantu Anda mencari alternatif lain.', 1, 'Llama-3-RAG-Hybrid', '2026-04-27 20:08:36', '2026-04-27 20:08:36'),
(17, NULL, 'ayRiXiwF79VjjQUrCmjTlRO80tqTfntUrfPkkcXx', 'hai', 'Selamat datang! Saya siap membantu Anda menemukan mobil yang sesuai dengan kebutuhan Anda. Dari mana Anda ingin menyewa mobil?', 1, 'Llama-3-RAG-Hybrid', '2026-04-27 20:35:00', '2026-04-27 20:35:00'),
(18, NULL, 'ayRiXiwF79VjjQUrCmjTlRO80tqTfntUrfPkkcXx', 'saya mencari mobil irit', 'Mobil irit biasanya memiliki konsumsi BBM yang rendah. Apakah Anda memiliki preferensi untuk jenis mobil tertentu, seperti SUV, MPV, atau City Car?', 1, 'Llama-3-RAG-Hybrid', '2026-04-27 20:35:47', '2026-04-27 20:35:47'),
(19, NULL, 'ayRiXiwF79VjjQUrCmjTlRO80tqTfntUrfPkkcXx', 'mobil apa saja bebas asalkan irit', 'Beberapa contoh mobil irit yang tersedia di Pekanbaru adalah Daihatsu Sigra, Toyota Agya, dan Daihatsu Terios. Mereka memiliki konsumsi BBM yang rendah dan cocok untuk perjalanan dalam kota. Apakah Anda ingin tahu lebih lanjut tentang salah satu dari mobil tersebut?', 1, 'Llama-3-RAG-Hybrid', '2026-04-27 20:36:12', '2026-04-27 20:36:12'),
(20, NULL, 'ayRiXiwF79VjjQUrCmjTlRO80tqTfntUrfPkkcXx', 'iyaa', 'Daihatsu Sigra adalah mobil irit yang memiliki konsumsi BBM sekitar 14 km/liter. Mobil ini memiliki tipe Compact MPV, transmisi manual, dan memuat 7 kursi. Harganya sekitar Rp 300.000/hari. Apakah Anda ingin tahu lebih lanjut tentang mobil ini atau ingin melihat opsi lain?', 1, 'Llama-3-RAG-Hybrid', '2026-04-27 20:37:50', '2026-04-27 20:37:50'),
(21, NULL, 'ayRiXiwF79VjjQUrCmjTlRO80tqTfntUrfPkkcXx', 'mobil lain', 'Mobil lain yang irit di Pekanbaru adalah Toyota Agya. Mobil ini memiliki tipe City Car, transmisi manual, dan memuat 5 kursi. Harganya sekitar Rp 300.000/hari. Apakah Anda ingin tahu lebih lanjut tentang mobil ini?', 1, 'Llama-3-RAG-Hybrid', '2026-04-27 20:38:17', '2026-04-27 20:38:17'),
(22, NULL, 'ayRiXiwF79VjjQUrCmjTlRO80tqTfntUrfPkkcXx', 'iya', 'Toyota Agya adalah mobil irit yang memiliki konsumsi BBM sekitar 21 km/liter. Mobil ini memiliki tipe City Car, transmisi manual, dan memuat 5 kursi. Harganya sekitar Rp 300.000/hari. Apakah Anda ingin menyewanya?', 1, 'Llama-3-RAG-Hybrid', '2026-04-27 20:38:49', '2026-04-27 20:38:49'),
(23, NULL, 'ayRiXiwF79VjjQUrCmjTlRO80tqTfntUrfPkkcXx', 'iya', 'Mohon konfirmasi tanggal sewa dan jam mulai untuk proses booking Toyota Agya dengan harga Rp 300.000/hari.', 1, 'Llama-3-RAG-Hybrid', '2026-04-27 20:39:38', '2026-04-27 20:39:38'),
(24, NULL, 'ayRiXiwF79VjjQUrCmjTlRO80tqTfntUrfPkkcXx', '29 april sampai 1 mei', 'Saya telah menyiapkan Toyota Agya untuk Anda. Silakan mengambil mobil pada tanggal 29 April. Mohon membayar biaya sewa sebesar Rp 300.000/hari dan biaya tambahan lainnya jika ada. Terima kasih!<br><br><a href=\"http://127.0.0.1:8000/guest-booking/969b0fb6-dd74-4844-85d4-eb132335eb43\" class=\"text-blue-600 font-bold underline hover:text-blue-800 break-all border-b border-blue-600\" target=\"_blank\">Klik Disini untuk Booking</a>', 1, 'Llama-3-RAG-Hybrid', '2026-04-27 20:50:30', '2026-04-27 20:50:30'),
(25, NULL, 'HfP76aEUyrH2oLnzd8oeEgxS3YQSnW3hQmkT6Np3', 'hai', 'Halo! Ada yang bisa saya bantu?', 1, 'Llama-3-RAG-Hybrid', '2026-04-29 11:02:31', '2026-04-29 11:02:31'),
(26, NULL, 'HfP76aEUyrH2oLnzd8oeEgxS3YQSnW3hQmkT6Np3', 'ada', 'Baik, silakan tanya saja. Saya akan membantu dengan senang hati!', 1, 'Llama-3-RAG-Hybrid', '2026-04-29 11:02:38', '2026-04-29 11:02:38'),
(27, NULL, 'HfP76aEUyrH2oLnzd8oeEgxS3YQSnW3hQmkT6Np3', 'hai', 'Halo! Ada yang bisa saya bantu?', 1, 'Llama-3-RAG-Hybrid', '2026-04-29 12:40:17', '2026-04-29 12:40:17'),
(28, NULL, 'HfP76aEUyrH2oLnzd8oeEgxS3YQSnW3hQmkT6Np3', 'halo', 'Halo! Ada yang bisa saya bantu?', 1, 'Llama-3-RAG-Hybrid', '2026-04-29 12:40:25', '2026-04-29 12:40:25'),
(29, NULL, 'HfP76aEUyrH2oLnzd8oeEgxS3YQSnW3hQmkT6Np3', 'apa bedanya kamu dengan smart search', 'Kak! Saya bukanlah Smart Search, tapi asisten rental mobil yang siap membantu kamu mencari dan menyewa mobil yang sesuai kebutuhanmu. Kami memiliki database rental mobil yang luas dan terpercaya, sehingga kamu bisa mendapatkan informasi yang akurat dan terbaru tentang rental mobil di berbagai kota. Saya juga memiliki kemampuan untuk memahami pertanyaan dan memberikan jawaban yang relevan, sehingga kamu bisa mendapatkan jawaban yang cepat dan tepat.', 1, 'Llama-3-RAG-Hybrid', '2026-04-29 12:40:56', '2026-04-29 12:40:56'),
(30, NULL, 'HfP76aEUyrH2oLnzd8oeEgxS3YQSnW3hQmkT6Np3', 'bantu saya mencari mobil', 'Baiklah! Saya dapat membantu kamu mencari mobil yang sesuai kebutuhanmu. Apa yang kamu cari? Apakah kamu ingin mencari mobil berdasarkan merek, jenis, harga, atau lokasi?\n\nSaya juga ingin tahu, apakah kamu memiliki budget tertentu untuk menyewa mobil? Dan berapa lama waktu kamu ingin menyewa mobil?', 1, 'Llama-3-RAG-Hybrid', '2026-04-29 12:41:32', '2026-04-29 12:41:32'),
(31, NULL, 'HfP76aEUyrH2oLnzd8oeEgxS3YQSnW3hQmkT6Np3', 'saya mencari mobil irit bbm untuk penggunaan dikota pekanbaru', 'Kota Pekanbaru! Saya dapat membantu kamu mencari mobil yang irit BBM dan sesuai untuk penggunaan di kota Pekanbaru.\n\nDari database saya, ada beberapa mobil yang dapat kamu pertimbangkan:\n\n1. Daihatsu Xenia (ID: 1) - Harga: Rp 300.000/hari, BBM: Bensin, Transmisi: Manual\n2. Toyota Avanza (ID: 26) - Harga: Rp 300.000/hari, BBM: Bensin, Transmisi: Manual\n3. Daihatsu Terios (ID: 33) - Harga: Rp 400.000/hari, BBM: Bensin, Transmisi: Manual\n4. Daihatsu Xenia (ID: 13) - Harga: Rp 300.000/hari, BBM: Bensin, Transmisi: Manual\n5. Toyota Innova (ID: 34) - Harga: Rp 600.000/hari, BBM: Solar, Transmisi: Manual\n\nNamun, perlu diingat bahwa harga dan ketersediaan mobil dapat berubah-ubah. Saya sarankan kamu untuk memeriksa ketersediaan dan harga mobil secara langsung dengan kami.\n\nApakah kamu ingin memesan salah satu dari mobil di atas? Atau kamu ingin mencari mobil lain yang lebih sesuai dengan kebutuhanmu?', 1, 'Llama-3-RAG-Hybrid', '2026-04-29 12:42:11', '2026-04-29 12:42:11'),
(32, NULL, 'HfP76aEUyrH2oLnzd8oeEgxS3YQSnW3hQmkT6Np3', 'saya pilih avanza', 'Toyota Avanza adalah pilihan yang sangat baik! Saya dapat membantu kamu memesan Toyota Avanza di kota Pekanbaru.\n\nBerikut adalah informasi tentang Toyota Avanza yang kamu pilih:\n\n* Nama: Toyota Avanza\n* ID: 26\n* Harga: Rp 300.000/hari\n* BBM: Bensin\n* Transmisi: Manual\n* Kursi: 7\n* Ketersediaan: Tersedia\n\nSaya akan memproses pesanan kamu. Namun, sebelum itu, saya ingin meminta beberapa informasi tambahan:\n\n* Berapa lama waktu kamu ingin menyewa Toyota Avanza?\n* Apakah kamu ingin memilih tambahan fasilitas, seperti asuransi atau layanan antar-jemput?\n* Apakah kamu memiliki kartu kredit atau metode pembayaran lain yang dapat digunakan untuk membayar sewa mobil?\n\nMohon jawab pertanyaan-pertanyaan di atas, dan saya akan memproses pesanan kamu dengan cepat!', 1, 'Llama-3-RAG-Hybrid', '2026-04-29 12:43:27', '2026-04-29 12:43:27'),
(33, NULL, 'JJzHIqfwz1JBTVT3gW8KtgNH5spAxMVnRLZ3GHNk', 'halo', 'Selamat pagi, saya siap membantu Anda mencari mobil rental yang sesuai kebutuhan Anda.', 1, 'Llama-3-RAG-Hybrid', '2026-04-29 17:14:26', '2026-04-29 17:14:26'),
(34, NULL, 'JJzHIqfwz1JBTVT3gW8KtgNH5spAxMVnRLZ3GHNk', 'siapa jokowi?', 'Joko Widodo, atau biasa disebut Jokowi, adalah Presiden Republik Indonesia ke-7.', 1, 'Llama-3-RAG-Hybrid', '2026-04-29 17:14:32', '2026-04-29 17:14:32'),
(35, NULL, 'JJzHIqfwz1JBTVT3gW8KtgNH5spAxMVnRLZ3GHNk', 'mbg itu apa', 'Mobil', 1, 'Llama-3-RAG-Hybrid', '2026-04-29 17:15:01', '2026-04-29 17:15:01'),
(36, NULL, 'SpcfPquiiwsiz1c4V5BMJMoHWTOLnvBkgJNggS8O', 'halo', 'Halo! Selamat datang di rental mobil kami. Bagaimana saya bisa membantu Anda hari ini?', 1, 'Llama-3-RAG-Hybrid', '2026-04-30 11:28:31', '2026-04-30 11:28:31'),
(37, NULL, 'bDDW3MMj2bdERndQtdIA3lbL9dySuiHxLBYzB7nJ', 'aku mau pesan', 'Selamat datang! Kami dengan senang hati membantu Anda memesan mobil. Silakan beritahu kami jenis mobil yang Anda cari atau tanggal penyewaan yang Anda inginkan.', 1, 'Llama-3-RAG-Hybrid', '2026-05-02 09:12:14', '2026-05-02 09:12:14'),
(38, NULL, 'bDDW3MMj2bdERndQtdIA3lbL9dySuiHxLBYzB7nJ', 'aku mau pesan daihatsu terios 2 hari', 'Maaf, kami memiliki beberapa Daihatsu Terios di cabang Pekanbaru dan Jakarta. Untuk memastikan ketersediaan, silakan beritahu kami tanggal penyewaan yang Anda inginkan dan cabang yang Anda pilih. Kami akan membantu Anda dengan senang hati.', 1, 'Llama-3-RAG-Hybrid', '2026-05-02 09:12:35', '2026-05-02 09:12:35'),
(39, NULL, 'bDDW3MMj2bdERndQtdIA3lbL9dySuiHxLBYzB7nJ', 'aku mau pesan daihatsu terios 2 hari dari cabang pekanbaru', 'Daihatsu Terios di cabang Pekanbaru tersedia. Harga Rp 400.000/hari. Total biaya Rp 800.000 untuk 2 hari. Silakan konfirmasi untuk melanjutkan pemesanan.<br><br><a href=\"http://www.rental-mobil.fwh.is/guest-booking/5aeefffc-f950-405b-929d-ca6e6db7dc20\" class=\"text-blue-600 font-bold underline hover:text-blue-800 break-all border-b border-blue-600\" target=\"_blank\">Klik Disini untuk Booking</a>', 1, 'Llama-3-RAG-Hybrid', '2026-05-02 09:12:53', '2026-05-02 09:12:53');

-- --------------------------------------------------------

--
-- Table structure for table `customers`
--

CREATE TABLE `customers` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nama_customer` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `no_telp` varchar(255) NOT NULL,
  `alamat` text NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) UNSIGNED NOT NULL,
  `reserved_at` int(10) UNSIGNED DEFAULT NULL,
  `available_at` int(10) UNSIGNED NOT NULL,
  `created_at` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `job_batches`
--

CREATE TABLE `job_batches` (
  `id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `total_jobs` int(11) NOT NULL,
  `pending_jobs` int(11) NOT NULL,
  `failed_jobs` int(11) NOT NULL,
  `failed_job_ids` longtext NOT NULL,
  `options` mediumtext DEFAULT NULL,
  `cancelled_at` int(11) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `finished_at` int(11) DEFAULT NULL
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
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2025_10_20_162304_create_cars_table', 1),
(5, '2025_10_20_162305_create_customers_table', 1),
(6, '2025_10_20_162642_create_reservations_table', 1),
(7, '2025_10_23_064111_add_kode_booking_to_reservations_table', 1),
(8, '2025_10_23_085144_add_merek_to_cars_table', 1),
(9, '2025_10_23_141745_add_location_fields_to_reservations_table', 1),
(10, '2025_10_25_190305_add_detailed_pricing_to_cars_table', 1),
(11, '2025_12_19_130614_create_tentang_kamis_table', 1),
(12, '2026_01_31_191346_create_chat_logs_table', 1),
(13, '2026_01_31_193424_add_fields_to_users_table', 1),
(14, '2026_02_07_150206_create_rentals_table', 1),
(15, '2026_02_07_150248_create_branches_table', 1),
(16, '2026_02_08_090000_create_mobils_table', 1),
(17, '2026_02_08_091111_create_transaksis_table', 1),
(18, '2026_03_09_143814_update_status_enum_in_transaksis_table', 1),
(19, '2026_03_14_024139_add_rental_id_to_users_table', 1),
(20, '2026_03_16_074720_add_rekening_to_rentals_table', 1),
(21, '2026_03_25_092742_add_snk_to_rentals_table', 1),
(22, '2026_03_26_112344_add_rental_id_to_users_table', 1),
(23, '2026_04_02_111550_add_snap_token_to_transaksis_table', 1),
(24, '2026_04_05_000000_add_tipe_mobil_to_mobils_table', 1),
(25, '2026_04_05_010000_add_fee_fields_to_rentals_table', 1),
(26, '2026_04_13_140933_change_alamat_to_ttl_in_users_table', 2),
(27, '2026_04_17_191507_modify_user_id_nullable_in_transaksis', 3),
(28, '2026_04_18_021500_modify_user_id_nullable_in_transaksis_table', 3),
(29, '2026_04_22_000001_add_biaya_tambahan_to_transaksis_table', 4),
(30, '2026_04_28_010000_update_chat_logs_table_structure', 5);

-- --------------------------------------------------------

--
-- Table structure for table `mobils`
--

CREATE TABLE `mobils` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `rental_id` bigint(20) UNSIGNED DEFAULT NULL,
  `branch_id` bigint(20) UNSIGNED DEFAULT NULL,
  `merk` varchar(255) NOT NULL,
  `model` varchar(255) NOT NULL,
  `no_plat` varchar(255) NOT NULL,
  `harga_sewa` decimal(15,2) NOT NULL,
  `tipe_mobil` varchar(255) DEFAULT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'tersedia',
  `tahun_buat` int(11) NOT NULL,
  `transmisi` varchar(255) NOT NULL,
  `bahan_bakar` varchar(255) NOT NULL,
  `jumlah_kursi` int(11) NOT NULL,
  `gambar` varchar(255) DEFAULT NULL,
  `deskripsi` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `mobils`
--

INSERT INTO `mobils` (`id`, `rental_id`, `branch_id`, `merk`, `model`, `no_plat`, `harga_sewa`, `tipe_mobil`, `status`, `tahun_buat`, `transmisi`, `bahan_bakar`, `jumlah_kursi`, `gambar`, `deskripsi`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 'Daihatsu', 'Xenia', 'UNIT-CTEY92LKER', '300000.00', 'Compact MPV', 'tersedia', 2023, 'matic', 'Bensin', 7, 'mobil_images/ofH07oh1s41p75KhMZk4v3gPLUTkYrjq5WIdmUzl.jpg', NULL, '2026-04-05 00:35:12', '2026-04-26 01:00:09'),
(2, 2, 2, 'Daihatsu', 'Terios', 'UNIT-WBFKITXXPB', '600000.00', 'SUV', 'tersedia', 2025, 'matic', 'Bensin', 7, 'mobil_images/Q9gsbj1y5DKuDZJYeitTrCy18j75sB4WNpTeG5YY.jpg', NULL, '2026-04-05 00:39:43', '2026-04-25 23:42:39'),
(3, 2, 2, 'Toyota', 'Avanza', 'UNIT-HS15WIUP1N', '550000.00', 'Compact MPV', 'tersedia', 2025, 'manual', 'Bensin', 7, 'mobil_images/5q5RW8zHGBVKUz38Eeos7n9JPYR6zSP2u1twLE9K.jpg', NULL, '2026-04-05 00:51:03', '2026-04-25 23:42:49'),
(4, 1, 1, 'Toyota', 'Ayla', 'UNIT-Z0C8JE33PE', '300000.00', 'City Car', 'tersedia', 2025, 'manual', 'Bensin', 4, 'mobil_images/ubnEZSluJiGi4jj0wr5OGXCN2WkQwdUxLYN8PcqR.jpg', NULL, '2026-04-07 09:16:25', '2026-04-26 00:55:00'),
(5, 3, 3, 'Toyota', 'Innova Reborn', 'UNIT-EMBHG7JWAY', '600000.00', 'Compact MPV', 'tersedia', 2020, 'manual', 'Solar', 7, 'mobil_images/VgFR0Vo64XfxFMFRBkJsWZeC9BBeb69I29khaFE0.png', NULL, '2026-04-07 09:37:17', '2026-04-26 00:21:13'),
(6, 1, 1, 'Daihatsu', 'Sigra', 'UNIT-41NOIJ81OT', '300000.00', 'Compact MPV', 'tersedia', 2020, 'manual', 'Bensin', 7, 'mobil_images/pNi10IYymWw0pgoLEYQAtzhsdpzCnVlte7vfRycE.png', NULL, '2026-04-09 04:59:18', '2026-04-21 03:15:34'),
(8, 2, 2, 'Toyota', 'Yaris', 'UNIT-2ZHYJQ6ODN', '900000.00', 'City Car', 'tersedia', 2023, 'manual', 'Bensin', 4, 'mobil_images/iAV9XSD6hPYYdSMapXbVcCqSMxuCzwlZYdVgtP7p.jpg', NULL, '2026-04-25 23:29:31', '2026-04-25 23:29:31'),
(9, 2, 2, 'Misubishi', 'Pajero Sport', 'UNIT-XNIWTKZXD0', '1900000.00', 'SUV', 'tersedia', 2023, 'matic', 'Bensin', 7, 'mobil_images/P58Slx2y5fhacvDwRViGw7N6XOxog7P5Ydc5lvG9.jpg', NULL, '2026-04-25 23:32:17', '2026-04-25 23:42:23'),
(10, 2, 2, 'Toyota', 'Innova Venturer', 'UNIT-RCXFPH6COY', '1300000.00', 'Compact MPV', 'tersedia', 2022, 'matic', 'Solar', 7, 'mobil_images/JqcJ8AQCc3uazyLcIjAR4b2N2dFZYZQws8EJvfel.jpg', NULL, '2026-04-25 23:33:56', '2026-04-25 23:33:56'),
(11, 2, 2, 'Honda', 'Brio', 'UNIT-R6KRP9N2JQ', '500000.00', 'City Car', 'tersedia', 2023, 'manual', 'Bensin', 4, 'mobil_images/cbZTjTugmZBPeQgdBQPBZExIWgBFyRUluGEeGZxS.jpg', NULL, '2026-04-25 23:35:18', '2026-04-25 23:35:18'),
(12, 2, 2, 'Daihatsu', 'Rocky', 'UNIT-FOMP18WTFR', '500000.00', 'City Car', 'tersedia', 2022, 'manual', 'Bensin', 4, 'mobil_images/oa0gEIxVzG1cbL1MJrXC8lGz4nTe7Pz97BRPSZAA.jpg', NULL, '2026-04-25 23:36:55', '2026-04-25 23:36:55'),
(13, 3, 3, 'Daihatsu', 'Xenia', 'UNIT-O2ZIVOOCRP', '300000.00', 'Compact MPV', 'tersedia', 2023, 'manual', 'Bensin', 7, 'mobil_images/hLHuDHQrMPeMpWZuWhckirm95VqTxui4lifhqI0b.jpg', NULL, '2026-04-25 23:51:38', '2026-04-26 00:19:02'),
(14, 3, 3, 'Mitsubishi', 'Xpander', 'UNIT-5AFFAKZDYQ', '450000.00', 'Compact MPV', 'tersedia', 2020, 'manual', 'Bensin', 7, 'mobil_images/XmwuZHKpSBxducIHVdkljW4uii1NSjudJeDjNe9e.jpg', NULL, '2026-04-25 23:54:23', '2026-04-26 00:19:50'),
(15, 3, 3, 'Toyota', 'Fortuner', 'UNIT-VUTPVBAGSH', '1600000.00', 'SUV', 'tersedia', 2023, 'matic', 'Solar', 7, 'mobil_images/ZV9Gd6dLIv0Xwa1YCSSvqCzlnG5HuGp1wVxMJX9a.jpg', NULL, '2026-04-25 23:58:26', '2026-04-26 00:20:03'),
(16, 3, 3, 'Toyota', 'Innove Venturer', 'UNIT-WCSG7TLAN5', '850000.00', 'Compact MPV', 'tersedia', 2022, 'manual', 'Bensin', 7, 'mobil_images/Ce3Ndw3WHL8HorUVAup7Ai4gWP1y9ol8DuZKes48.jpg', NULL, '2026-04-26 00:00:36', '2026-04-26 00:20:19'),
(17, 3, 3, 'Daihatsu', 'Xenia', 'UNIT-T9IXIHBNMO', '350000.00', 'Compact MPV', 'tersedia', 2023, 'manual', 'Bensin', 7, 'mobil_images/Noc3BRjBq3FQvwfAYv33btEJ6PBLXcUY1vDHys77.jpg', NULL, '2026-04-26 00:01:26', '2026-04-26 00:18:51'),
(19, 4, 4, 'Toyota', 'Fortuner', 'UNIT-MZJQB3ESER', '1600000.00', 'SUV', 'tersedia', 2023, 'manual', 'Bensin', 7, 'mobil_images/Isqun6SUfi90OkkwEYzNWYEh9VH8Z7ObH2HhaWIZ.jpg', NULL, '2026-04-26 00:29:29', '2026-04-26 00:45:32'),
(20, 4, 4, 'Mitsubishi', 'Pajero Sport', 'UNIT-NWVLWWFA0B', '1600000.00', 'SUV', 'tersedia', 2022, 'matic', 'Solar', 7, 'mobil_images/hjyvNqs1A11lLk56UOEPwhrr8iGE7Fm8C24gQepO.jpg', NULL, '2026-04-26 00:30:27', '2026-04-26 00:45:24'),
(21, 4, 4, 'Mitsubishi', 'Xpander', 'UNIT-UPVTSZSQZB', '450000.00', 'Compact MPV', 'tersedia', 2021, 'manual', 'Bensin', 7, 'mobil_images/UD9A9IGNppGoHpnP7EfwN81thOT0Ogst9c5Xl0fu.jpg', NULL, '2026-04-26 00:31:03', '2026-04-26 00:45:45'),
(22, 4, 4, 'Daihatsu', 'Xenia', 'UNIT-U8QLHJ0E72', '300000.00', 'Compact MPV', 'tersedia', 2020, 'manual', 'Bensin', 7, 'mobil_images/o9qwl37k05836mvHpB4tHSDB2J2Ikhh1IJXbHoyz.jpg', NULL, '2026-04-26 00:33:47', '2026-04-26 00:33:47'),
(23, 4, 4, 'Toyota', 'Hiace Premio', 'UNIT-3Y633IS8Q1', '1600000.00', 'Minivan', 'tersedia', 2020, 'manual', 'Solar', 14, 'mobil_images/ixDAjFsnJGaWTwA1ziwABSs83L51G2CKwmYB9s8y.jpg', NULL, '2026-04-26 00:38:11', '2026-04-26 00:46:20'),
(25, 4, 4, 'Daihatsu', 'Rocky', 'UNIT-N5ANEANJZB', '350000.00', 'City Car', 'tersedia', 2023, 'manual', 'Bensin', 4, 'mobil_images/PcY8fiwgO2aA7DBKUiCsozPvj2PyyIVUmZPe1ti7.jpg', NULL, '2026-04-26 00:44:53', '2026-04-26 00:44:53'),
(26, 5, 5, 'Toyota', 'Avanza', 'UNIT-R57LGQEGGY', '300000.00', 'Compact MPV', 'tersedia', 2020, 'manual', 'Bensin', 7, 'mobil_images/53q8TII6Zp5e5p0Qvm7IH6yBwA6DAX3FfMEKUr5R.jpg', NULL, '2026-04-26 00:48:28', '2026-04-26 00:48:28'),
(27, 5, 5, 'Daihatsu', 'Xenia', 'UNIT-HHW8PBLGD2', '300000.00', 'Compact MPV', 'tersedia', 2020, 'manual', 'Bensin', 7, 'mobil_images/yNtSRjCackg9CfRgZoDcWqrOf3AfSJD7LkFlecct.jpg', NULL, '2026-04-26 00:49:12', '2026-04-26 00:49:12'),
(28, 5, 5, 'Mitsubishi', 'Xpander', 'UNIT-LV6YK8MIM6', '750000.00', 'Compact MPV', 'tersedia', 2022, 'manual', 'Bensin', 7, 'mobil_images/wWAP8hZsjhYeLZBPiOrDlxHpA9uBK2BxwwCIee8a.jpg', NULL, '2026-04-26 00:49:57', '2026-04-26 00:49:57'),
(29, 5, 5, 'Toyota', 'Innova', 'UNIT-M117KSZHXU', '900000.00', 'Compact MPV', 'tersedia', 2021, 'matic', 'Solar', 7, 'mobil_images/RcH7S3ovDEFxMJQiPw33Qh3mVM1yFuckC2ezmZFw.png', NULL, '2026-04-26 00:50:52', '2026-04-26 00:50:52'),
(31, 5, 5, 'Toyota', 'Fortuner', 'UNIT-OFB7SSGIVL', '1700000.00', 'SUV', 'tersedia', 2022, 'matic', 'Solar', 7, 'mobil_images/yedCFzTG9wPfXm8UNCJcI1rSnjD8hvI3cZsv3v2t.jpg', NULL, '2026-04-26 00:51:58', '2026-04-26 00:51:58'),
(32, 5, 5, 'Mitsubishi', 'Pajero Sport', 'UNIT-Q5FUVKYTDP', '1700000.00', 'SUV', 'tersedia', 2024, 'manual', 'Bensin', 7, 'mobil_images/0msAbBuVsvBGobFgraMkzyYlPkGGxjp1dYWQ8f2L.jpg', NULL, '2026-04-26 00:52:59', '2026-04-26 00:52:59'),
(33, 1, 1, 'Daihatsu', 'Terios', 'UNIT-CNFLTMPB1B', '400000.00', 'SUV', 'tersedia', 2026, 'manual', 'Bensin', 7, 'mobil_images/8gHpi4RF3ISLH0xqWj2XvKxb8bULIudD1v8Om8wf.jpg', NULL, '2026-04-26 00:56:47', '2026-04-26 00:56:47'),
(34, 1, 1, 'Toyota', 'Innova', 'UNIT-RZ2VIEMW7D', '600000.00', 'Compact MPV', 'tersedia', 2020, 'manual', 'Solar', 7, 'mobil_images/wb6kcNLubplfZSdWfmloCLB2WoQppO5M2tUOUTea.jpg', NULL, '2026-04-26 00:59:45', '2026-04-26 00:59:45'),
(35, 1, 1, 'Daihatsu', 'Xenia', 'UNIT-S2LONKBEZQ', '350000.00', 'Compact MPV', 'tersedia', 2025, 'manual', 'Bensin', 7, 'mobil_images/X0wu4jkwb8Ja8oSItJZUby6nNWV8zIY1MUeAUiiW.jpg', NULL, '2026-04-26 01:00:54', '2026-04-26 01:00:54');

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `rentals`
--

CREATE TABLE `rentals` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `nama_rental` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `no_telp_bisnis` varchar(255) DEFAULT NULL,
  `alamat` text DEFAULT NULL,
  `syarat_ketentuan` text DEFAULT NULL,
  `biaya_sopir_per_hari` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `biaya_bandara_per_trip` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `nama_bank` varchar(255) DEFAULT NULL,
  `no_rekening` varchar(255) DEFAULT NULL,
  `atas_nama_rekening` varchar(255) DEFAULT NULL,
  `deskripsi` text DEFAULT NULL,
  `logo` varchar(255) DEFAULT NULL,
  `no_izin_usaha` varchar(255) DEFAULT NULL,
  `nomor_rekening` varchar(255) DEFAULT NULL,
  `bank` varchar(255) DEFAULT NULL,
  `status` enum('active','inactive','banned') NOT NULL DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `rentals`
--

INSERT INTO `rentals` (`id`, `user_id`, `nama_rental`, `slug`, `no_telp_bisnis`, `alamat`, `syarat_ketentuan`, `biaya_sopir_per_hari`, `biaya_bandara_per_trip`, `nama_bank`, `no_rekening`, `atas_nama_rekening`, `deskripsi`, `logo`, `no_izin_usaha`, `nomor_rekening`, `bank`, `status`, `created_at`, `updated_at`) VALUES
(1, 1, 'FZ RENT CAR', 'fz-rent-car', '085375285567', 'Jl.Teropong, Perum Surya Mandiri Blok h-4', 'Ketentuan & Persyaratan Sewa Mobil\r\n1. PIHAK KEDUA wajib menyerahkan identitas diri yang asli seperti KTP, SIM, KK dan wajib menitipkan kendaraannya pada PIHAK PERTAMA.\r\n2. Kendaraan yang disewa harus dikembalikan tepat pada waktunya (dihitung saat pengambilan kendaraan tersebut).\r\nApabila terjadi keterlambatan dalam pengembalian kendaraan tersebut maka akan dikenakan denda 10%/jam.\r\nJika waktu pengembaliannya telah melebihi 5 (lima) jam maka akan dihitung 1 (satu) hari sewa.\r\n3. Apabila terjadi suatu musibah seperti kecelakaan dan kehilangan kendaraan yang disebabkan oleh PIHAK KEDUA, maka PIHAK KEDUA harus bertanggung jawab sepenuhnya atas kelalaian tersebut, dan apabila dalam masa penyewaan kendaraan tersebut PIHAK KEDUA menyalahgunakan kendaraan tersebut dan melanggar hukum maka hal ini menjadi tanggung jawab PIHAK KEDUA sepenuhnya dan PIHAK PERTAMA dibebaskan segala tuntutan dari pihak manapun.\r\n4. Apabila terjadi suatu musibah seperti yang dijelaskan pada nomor 3 maka PIHAK KEDUA tetap berkewajiban membayar biaya sewa kendaraan tersebut selama perawatan atau perbaikan di bengkel, serta urusan kepada pihak yang berwajib dan lain-lainnya yang berhubungan dengan penyewaan kendaraan sampai kendaraan tersebut diterima kembali oleh PIHAK PERTAMA.\r\n5. Dalam masa perjanjian sewa PIHAK KEDUA tidak dibenarkan untuk mengubah dan memindahkan atau mengalihkan kendaraan tersebut dengan pihak lain, apabila ini terjadi maka PIHAK PERTAMA berhak untuk melaporkan PIHAK KEDUA tersebut kepada pihak yang berwajib.\r\n6. Hal-hal yang belum diatur dalam Surat Perjanjian penyewaan kendaraan bermotor ini akan disepakati oleh kedua belah pihak secara musyawarah dan mufakat.\r\n7. PIHAK KEDUA (Penyewa) memakai mobil sesuai waktu yang disepakati (hari, tanggal, jam).\r\n8. Keadaan mobil ketika kembali harus bersih seperti semula atau membayar upah cuci mobil Rp. 50.000,-', 100000, 100000, 'BRI', '1234567654', 'FZ RENT CAR', NULL, NULL, NULL, NULL, NULL, 'active', '2026-04-05 00:24:11', '2026-04-21 03:10:43'),
(2, 2, 'PUTRA WIDJAYA RENT CAR JAKARTA', 'putra-wijaya-rent-car', '083845966102', 'Jl.Cempaka Putih Tengah XXXI no.5b , jakarta pusat', 'Ketentuan & Persyaratan Sewa Mobil\r\n1. PIHAK KEDUA wajib menyerahkan identitas diri yang asli seperti KTP, SIM, KK dan wajib menitipkan kendaraannya pada PIHAK PERTAMA.\r\n2. Kendaraan yang disewa harus dikembalikan tepat pada waktunya (dihitung saat pengambilan kendaraan tersebut).\r\nApabila terjadi keterlambatan dalam pengembalian kendaraan tersebut maka akan dikenakan denda 10%/jam.\r\nJika waktu pengembaliannya telah melebihi 5 (lima) jam maka akan dihitung 1 (satu) hari sewa.\r\n3. Apabila terjadi suatu musibah seperti kecelakaan dan kehilangan kendaraan yang disebabkan oleh PIHAK KEDUA, maka PIHAK KEDUA harus bertanggung jawab sepenuhnya atas kelalaian tersebut, dan apabila dalam masa penyewaan kendaraan tersebut PIHAK KEDUA menyalahgunakan kendaraan tersebut dan melanggar hukum maka hal ini menjadi tanggung jawab PIHAK KEDUA sepenuhnya dan PIHAK PERTAMA dibebaskan segala tuntutan dari pihak manapun.\r\n4. Apabila terjadi suatu musibah seperti yang dijelaskan pada nomor 3 maka PIHAK KEDUA tetap berkewajiban membayar biaya sewa kendaraan tersebut selama perawatan atau perbaikan di bengkel, serta urusan kepada pihak yang berwajib dan lain-lainnya yang berhubungan dengan penyewaan kendaraan sampai kendaraan tersebut diterima kembali oleh PIHAK PERTAMA.\r\n5. Dalam masa perjanjian sewa PIHAK KEDUA tidak dibenarkan untuk mengubah dan memindahkan atau mengalihkan kendaraan tersebut dengan pihak lain, apabila ini terjadi maka PIHAK PERTAMA berhak untuk melaporkan PIHAK KEDUA tersebut kepada pihak yang berwajib.\r\n6. Hal-hal yang belum diatur dalam Surat Perjanjian penyewaan kendaraan bermotor ini akan disepakati oleh kedua belah pihak secara musyawarah dan mufakat.\r\n7. PIHAK KEDUA (Penyewa) memakai mobil sesuai waktu yang disepakati (hari, tanggal, jam).\r\n8. Keadaan mobil ketika kembali harus bersih seperti semula atau membayar upah cuci mobil Rp. 50.000,-', 250000, 0, 'BCA', '123456789', 'PT. PUTRA WIDJAYA RENT CAR JAKARTA', NULL, NULL, NULL, NULL, NULL, 'active', '2026-04-05 00:38:01', '2026-04-25 23:43:41'),
(3, 4, 'AA RENT CAR', 'aa-rent-car', '085278820591', 'Bencah Lesung, Kec. Tenayan Raya, Kota Pekanbaru, Riau 28281', 'Ketentuan & Persyaratan Sewa Mobil\r\n1. PIHAK KEDUA wajib menyerahkan identitas diri yang asli seperti KTP, SIM, KK dan wajib menitipkan kendaraannya pada PIHAK PERTAMA.\r\n2. Kendaraan yang disewa harus dikembalikan tepat pada waktunya (dihitung saat pengambilan kendaraan tersebut).\r\nApabila terjadi keterlambatan dalam pengembalian kendaraan tersebut maka akan dikenakan denda 10%/jam.\r\nJika waktu pengembaliannya telah melebihi 5 (lima) jam maka akan dihitung 1 (satu) hari sewa.\r\n3. Apabila terjadi suatu musibah seperti kecelakaan dan kehilangan kendaraan yang disebabkan oleh PIHAK KEDUA, maka PIHAK KEDUA harus bertanggung jawab sepenuhnya atas kelalaian tersebut, dan apabila dalam masa penyewaan kendaraan tersebut PIHAK KEDUA menyalahgunakan kendaraan tersebut dan melanggar hukum maka hal ini menjadi tanggung jawab PIHAK KEDUA sepenuhnya dan PIHAK PERTAMA dibebaskan segala tuntutan dari pihak manapun.\r\n4. Apabila terjadi suatu musibah seperti yang dijelaskan pada nomor 3 maka PIHAK KEDUA tetap berkewajiban membayar biaya sewa kendaraan tersebut selama perawatan atau perbaikan di bengkel, serta urusan kepada pihak yang berwajib dan lain-lainnya yang berhubungan dengan penyewaan kendaraan sampai kendaraan tersebut diterima kembali oleh PIHAK PERTAMA.\r\n5. Dalam masa perjanjian sewa PIHAK KEDUA tidak dibenarkan untuk mengubah dan memindahkan atau mengalihkan kendaraan tersebut dengan pihak lain, apabila ini terjadi maka PIHAK PERTAMA berhak untuk melaporkan PIHAK KEDUA tersebut kepada pihak yang berwajib.\r\n6. Hal-hal yang belum diatur dalam Surat Perjanjian penyewaan kendaraan bermotor ini akan disepakati oleh kedua belah pihak secara musyawarah dan mufakat.\r\n7. PIHAK KEDUA (Penyewa) memakai mobil sesuai waktu yang disepakati (hari, tanggal, jam).\r\n8. Keadaan mobil ketika kembali harus bersih seperti semula atau membayar upah cuci mobil Rp. 50.000,-', 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'active', '2026-04-07 09:26:51', '2026-04-07 10:07:09'),
(4, 6, 'EVAN RENTAL mobil pekanbaru', 'evan-rental-mobil-pekanbaru', '081374328872', 'Jl. HR Subrantas Gg Amal Perumahan Dahlia Garden Blok B3', NULL, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'active', '2026-04-10 00:16:31', '2026-04-10 00:17:20'),
(5, 7, 'PT. TRANS NUSANTARA GEMILANG RENT CAR', 'pt-trans-nusantara-gemilang-rent-car', '081277864411', 'Gg. Perwira I No.9, Labuh Baru Tim., Kec. Payung Sekaki, Kota Pekanbaru, Riau', 'Ketentuan & Persyaratan Sewa Mobil\r\n1. PIHAK KEDUA wajib menyerahkan identitas diri yang asli seperti KTP, SIM, KK dan wajib menitipkan kendaraannya pada PIHAK PERTAMA.\r\n2. Kendaraan yang disewa harus dikembalikan tepat pada waktunya (dihitung saat pengambilan kendaraan tersebut).\r\nApabila terjadi keterlambatan dalam pengembalian kendaraan tersebut maka akan dikenakan denda 10%/jam.\r\nJika waktu pengembaliannya telah melebihi 5 (lima) jam maka akan dihitung 1 (satu) hari sewa.\r\n3. Apabila terjadi suatu musibah seperti kecelakaan dan kehilangan kendaraan yang disebabkan oleh PIHAK KEDUA, maka PIHAK KEDUA harus bertanggung jawab sepenuhnya atas kelalaian tersebut, dan apabila dalam masa penyewaan kendaraan tersebut PIHAK KEDUA menyalahgunakan kendaraan tersebut dan melanggar hukum maka hal ini menjadi tanggung jawab PIHAK KEDUA sepenuhnya dan PIHAK PERTAMA dibebaskan segala tuntutan dari pihak manapun.\r\n4. Apabila terjadi suatu musibah seperti yang dijelaskan pada nomor 3 maka PIHAK KEDUA tetap berkewajiban membayar biaya sewa kendaraan tersebut selama perawatan atau perbaikan di bengkel, serta urusan kepada pihak yang berwajib dan lain-lainnya yang berhubungan dengan penyewaan kendaraan sampai kendaraan tersebut diterima kembali oleh PIHAK PERTAMA.\r\n5. Dalam masa perjanjian sewa PIHAK KEDUA tidak dibenarkan untuk mengubah dan memindahkan atau mengalihkan kendaraan tersebut dengan pihak lain, apabila ini terjadi maka PIHAK PERTAMA berhak untuk melaporkan PIHAK KEDUA tersebut kepada pihak yang berwajib.\r\n6. Hal-hal yang belum diatur dalam Surat Perjanjian penyewaan kendaraan bermotor ini akan disepakati oleh kedua belah pihak secara musyawarah dan mufakat.\r\n7. PIHAK KEDUA (Penyewa) memakai mobil sesuai waktu yang disepakati (hari, tanggal, jam).\r\n8. Keadaan mobil ketika kembali harus bersih seperti semula atau membayar upah cuci mobil Rp. 50.000,-', 100000, 100000, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'active', '2026-04-10 00:42:03', '2026-04-16 08:03:11');

-- --------------------------------------------------------

--
-- Table structure for table `reservations`
--

CREATE TABLE `reservations` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `kode_booking` varchar(255) NOT NULL,
  `customer_id` bigint(20) UNSIGNED NOT NULL,
  `car_id` bigint(20) UNSIGNED NOT NULL,
  `tanggal_mulai` datetime NOT NULL,
  `tanggal_selesai` datetime NOT NULL,
  `total_harga` int(11) NOT NULL,
  `biaya_tambahan` int(11) NOT NULL DEFAULT 0,
  `status` enum('menunggu','dikonfirmasi','selesai','dibatalkan') NOT NULL DEFAULT 'menunggu',
  `tipe_pengambilan` varchar(255) NOT NULL DEFAULT 'kantor',
  `alamat_pengambilan` text DEFAULT NULL,
  `tipe_pengembalian` varchar(255) NOT NULL DEFAULT 'kantor',
  `alamat_pengembalian` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('ayRiXiwF79VjjQUrCmjTlRO80tqTfntUrfPkkcXx', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36 Edg/147.0.0.0', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoicFl6b0lyd3JWanVVa0RnbDVIS05oTE90dmE1TmY2WEFlUUtIN3YxYyI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NzI6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9ndWVzdC1ib29raW5nLzk2OWIwZmI2LWRkNzQtNDg0NC04NWQ0LWViMTMyMzM1ZWI0MyI7czo1OiJyb3V0ZSI7czoxODoiZ3Vlc3QuYm9va2luZy5mb3JtIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czoxNToiY2hhdGJvdF9oaXN0b3J5IjthOjg6e2k6MDthOjI6e3M6NDoidXNlciI7czozOiJoYWkiO3M6MzoiYm90IjtzOjEyNjoiU2VsYW1hdCBkYXRhbmchIFNheWEgc2lhcCBtZW1iYW50dSBBbmRhIG1lbmVtdWthbiBtb2JpbCB5YW5nIHNlc3VhaSBkZW5nYW4ga2VidXR1aGFuIEFuZGEuIERhcmkgbWFuYSBBbmRhIGluZ2luIG1lbnlld2EgbW9iaWw/Ijt9aToxO2E6Mjp7czo0OiJ1c2VyIjtzOjIzOiJzYXlhIG1lbmNhcmkgbW9iaWwgaXJpdCI7czozOiJib3QiO3M6MTQ3OiJNb2JpbCBpcml0IGJpYXNhbnlhIG1lbWlsaWtpIGtvbnN1bXNpIEJCTSB5YW5nIHJlbmRhaC4gQXBha2FoIEFuZGEgbWVtaWxpa2kgcHJlZmVyZW5zaSB1bnR1ayBqZW5pcyBtb2JpbCB0ZXJ0ZW50dSwgc2VwZXJ0aSBTVVYsIE1QViwgYXRhdSBDaXR5IENhcj8iO31pOjI7YToyOntzOjQ6InVzZXIiO3M6MzM6Im1vYmlsIGFwYSBzYWphIGJlYmFzIGFzYWxrYW4gaXJpdCI7czozOiJib3QiO3M6MjY2OiJCZWJlcmFwYSBjb250b2ggbW9iaWwgaXJpdCB5YW5nIHRlcnNlZGlhIGRpIFBla2FuYmFydSBhZGFsYWggRGFpaGF0c3UgU2lncmEsIFRveW90YSBBZ3lhLCBkYW4gRGFpaGF0c3UgVGVyaW9zLiBNZXJla2EgbWVtaWxpa2kga29uc3Vtc2kgQkJNIHlhbmcgcmVuZGFoIGRhbiBjb2NvayB1bnR1ayBwZXJqYWxhbmFuIGRhbGFtIGtvdGEuIEFwYWthaCBBbmRhIGluZ2luIHRhaHUgbGViaWggbGFuanV0IHRlbnRhbmcgc2FsYWggc2F0dSBkYXJpIG1vYmlsIHRlcnNlYnV0PyI7fWk6MzthOjI6e3M6NDoidXNlciI7czo0OiJpeWFhIjtzOjM6ImJvdCI7czoyNzM6IkRhaWhhdHN1IFNpZ3JhIGFkYWxhaCBtb2JpbCBpcml0IHlhbmcgbWVtaWxpa2kga29uc3Vtc2kgQkJNIHNla2l0YXIgMTQga20vbGl0ZXIuIE1vYmlsIGluaSBtZW1pbGlraSB0aXBlIENvbXBhY3QgTVBWLCB0cmFuc21pc2kgbWFudWFsLCBkYW4gbWVtdWF0IDcga3Vyc2kuIEhhcmdhbnlhIHNla2l0YXIgUnAgMzAwLjAwMC9oYXJpLiBBcGFrYWggQW5kYSBpbmdpbiB0YWh1IGxlYmloIGxhbmp1dCB0ZW50YW5nIG1vYmlsIGluaSBhdGF1IGluZ2luIG1lbGloYXQgb3BzaSBsYWluPyI7fWk6NDthOjI6e3M6NDoidXNlciI7czoxMDoibW9iaWwgbGFpbiI7czozOiJib3QiO3M6MjE0OiJNb2JpbCBsYWluIHlhbmcgaXJpdCBkaSBQZWthbmJhcnUgYWRhbGFoIFRveW90YSBBZ3lhLiBNb2JpbCBpbmkgbWVtaWxpa2kgdGlwZSBDaXR5IENhciwgdHJhbnNtaXNpIG1hbnVhbCwgZGFuIG1lbXVhdCA1IGt1cnNpLiBIYXJnYW55YSBzZWtpdGFyIFJwIDMwMC4wMDAvaGFyaS4gQXBha2FoIEFuZGEgaW5naW4gdGFodSBsZWJpaCBsYW5qdXQgdGVudGFuZyBtb2JpbCBpbmk/Ijt9aTo1O2E6Mjp7czo0OiJ1c2VyIjtzOjM6Iml5YSI7czozOiJib3QiO3M6MjEzOiJUb3lvdGEgQWd5YSBhZGFsYWggbW9iaWwgaXJpdCB5YW5nIG1lbWlsaWtpIGtvbnN1bXNpIEJCTSBzZWtpdGFyIDIxIGttL2xpdGVyLiBNb2JpbCBpbmkgbWVtaWxpa2kgdGlwZSBDaXR5IENhciwgdHJhbnNtaXNpIG1hbnVhbCwgZGFuIG1lbXVhdCA1IGt1cnNpLiBIYXJnYW55YSBzZWtpdGFyIFJwIDMwMC4wMDAvaGFyaS4gQXBha2FoIEFuZGEgaW5naW4gbWVueWV3YW55YT8iO31pOjY7YToyOntzOjQ6InVzZXIiO3M6MzoiaXlhIjtzOjM6ImJvdCI7czoxMDY6Ik1vaG9uIGtvbmZpcm1hc2kgdGFuZ2dhbCBzZXdhIGRhbiBqYW0gbXVsYWkgdW50dWsgcHJvc2VzIGJvb2tpbmcgVG95b3RhIEFneWEgZGVuZ2FuIGhhcmdhIFJwIDMwMC4wMDAvaGFyaS4iO31pOjc7YToyOntzOjQ6InVzZXIiO3M6MjE6IjI5IGFwcmlsIHNhbXBhaSAxIG1laSI7czozOiJib3QiO3M6NDI2OiJTYXlhIHRlbGFoIG1lbnlpYXBrYW4gVG95b3RhIEFneWEgdW50dWsgQW5kYS4gU2lsYWthbiBtZW5nYW1iaWwgbW9iaWwgcGFkYSB0YW5nZ2FsIDI5IEFwcmlsLiBNb2hvbiBtZW1iYXlhciBiaWF5YSBzZXdhIHNlYmVzYXIgUnAgMzAwLjAwMC9oYXJpIGRhbiBiaWF5YSB0YW1iYWhhbiBsYWlubnlhIGppa2EgYWRhLiBUZXJpbWEga2FzaWghPGJyPjxicj48YSBocmVmPSJodHRwOi8vMTI3LjAuMC4xOjgwMDAvZ3Vlc3QtYm9va2luZy85NjliMGZiNi1kZDc0LTQ4NDQtODVkNC1lYjEzMjMzNWViNDMiIGNsYXNzPSJ0ZXh0LWJsdWUtNjAwIGZvbnQtYm9sZCB1bmRlcmxpbmUgaG92ZXI6dGV4dC1ibHVlLTgwMCBicmVhay1hbGwgYm9yZGVyLWIgYm9yZGVyLWJsdWUtNjAwIiB0YXJnZXQ9Il9ibGFuayI+S2xpayBEaXNpbmkgdW50dWsgQm9va2luZzwvYT4iO319fQ==', 1777323039);

-- --------------------------------------------------------

--
-- Table structure for table `tentang_kamis`
--

CREATE TABLE `tentang_kamis` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `judul` varchar(255) NOT NULL,
  `isi` text NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `transaksis`
--

CREATE TABLE `transaksis` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `mobil_id` bigint(20) UNSIGNED NOT NULL,
  `rental_id` bigint(20) UNSIGNED NOT NULL,
  `branch_id` bigint(20) UNSIGNED DEFAULT NULL,
  `nama` varchar(255) DEFAULT NULL,
  `no_hp` varchar(20) DEFAULT NULL,
  `alamat` text DEFAULT NULL,
  `foto_identitas` varchar(255) DEFAULT NULL,
  `tgl_ambil` date NOT NULL,
  `jam_ambil` time NOT NULL,
  `tgl_kembali` date NOT NULL,
  `jam_kembali` time NOT NULL,
  `lokasi_ambil` varchar(255) DEFAULT NULL,
  `lokasi_kembali` varchar(255) DEFAULT NULL,
  `alamat_lengkap` text DEFAULT NULL,
  `alamat_jemput` text DEFAULT NULL,
  `alamat_antar` text DEFAULT NULL,
  `tujuan` varchar(255) DEFAULT NULL,
  `sopir` enum('dengan_sopir','tanpa_sopir') NOT NULL DEFAULT 'tanpa_sopir',
  `lama_sewa` int(11) NOT NULL DEFAULT 1,
  `total_harga` decimal(15,2) NOT NULL,
  `biaya_tambahan` bigint(20) NOT NULL DEFAULT 0,
  `bukti_bayar` varchar(255) DEFAULT NULL,
  `status` varchar(50) NOT NULL DEFAULT 'pending',
  `snap_token` varchar(255) DEFAULT NULL,
  `catatan` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `foto_sim` varchar(255) DEFAULT NULL,
  `booking_token` varchar(255) DEFAULT NULL,
  `token_expires_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `transaksis`
--

INSERT INTO `transaksis` (`id`, `user_id`, `mobil_id`, `rental_id`, `branch_id`, `nama`, `no_hp`, `alamat`, `foto_identitas`, `tgl_ambil`, `jam_ambil`, `tgl_kembali`, `jam_kembali`, `lokasi_ambil`, `lokasi_kembali`, `alamat_lengkap`, `alamat_jemput`, `alamat_antar`, `tujuan`, `sopir`, `lama_sewa`, `total_harga`, `biaya_tambahan`, `bukti_bayar`, `status`, `snap_token`, `catatan`, `created_at`, `updated_at`, `foto_sim`, `booking_token`, `token_expires_at`) VALUES
(1, 3, 5, 3, 3, 'ZIKRALLAH AL HADY', '083896517385', 'Jl.Alam Raya GG Naras II', 'identitas/s17BCyNp730LHW8v8nfqSAclgt64QSbFkToqiub3.png', '2026-04-08', '20:00:00', '2026-04-09', '20:00:00', 'kantor', 'kantor', NULL, 'Ambil di Kantor', 'Kembalikan ke Kantor', 'dalam kota', 'tanpa_sopir', 1, '550000.00', 0, NULL, 'Selesai', 'd1439689-906d-4789-9931-b8843bb64671', NULL, '2026-04-07 09:55:34', '2026-04-09 05:27:46', 'sim_pelanggan/wlzUE0S5k5FCSsOF2hbOtv9OsIJC9XsX2cejsMSn.png', NULL, NULL),
(2, 3, 3, 2, 2, 'ZIKRALLAH AL HADY', '083896517385', 'Jl.Alam Raya GG Naras II', 'identitas/BWFb18ZzWLMHgVDhtASwlBuL4yrHqudnxubMweKY.png', '2026-04-08', '19:45:00', '2026-04-10', '19:45:00', 'kantor', 'kantor', NULL, 'Ambil di Kantor', 'Kembalikan ke Kantor', 'dalam kota', 'tanpa_sopir', 2, '600000.00', 0, NULL, 'Dibatalkan', '939280d5-0231-4e52-b86e-f3f4ffd0674b', NULL, '2026-04-07 10:17:38', '2026-04-09 05:21:12', 'sim_pelanggan/mGan7iRgFarVVH8BqxVKZ4KQaz01mRD9TtzXfHTi.png', NULL, NULL),
(3, 3, 6, 1, 1, 'ZIKRALLAH AL HADY', '083896517385', 'Jl.Alam Raya GG Naras II', 'identitas/LijP0IWP2JK7EUmjJ7iEjpjhpAQ1EiizuqfZiyXx.png', '2026-04-10', '15:00:00', '2026-04-13', '15:00:00', 'kantor', 'kantor', NULL, 'Ambil di Kantor', 'Kembalikan ke Kantor', 'dalam kota', 'tanpa_sopir', 3, '900000.00', 0, NULL, 'Selesai', '62401d3d-9dc2-4f06-9a19-83ca5304bbf5', NULL, '2026-04-09 05:05:01', '2026-04-09 05:27:00', 'sim_pelanggan/Lb6OULONn9zfshB5Z0Z5aIjeo4u4OWjmEdmBHuKd.png', NULL, NULL),
(4, 3, 3, 2, 2, 'ZIKRALLAH AL HADY', '083896517385', 'Jl.Alam Raya GG Naras II', 'identitas/xRmOYohfb5AklF0OOhumjB1DPYCNIkewmyBRdtFz.png', '2026-04-10', '20:30:00', '2026-04-12', '20:30:00', 'kantor', 'kantor', NULL, 'Ambil di Kantor', 'Kembalikan ke Kantor', 'dalam kota', 'dengan_sopir', 2, '900000.00', 0, NULL, 'Selesai', '37dcf251-1ed6-4d35-bdbd-d17d31a76bc0', NULL, '2026-04-10 03:19:25', '2026-04-10 03:39:47', 'sim_pelanggan/r7w5pFllmODI1H9XNeCxr1WbjqEzWRkrlyXsZcPL.png', NULL, NULL),
(5, 3, 2, 2, 2, 'ZIKRALLAH AL HADY', '083896517385', 'Jl.Alam Raya GG Naras II', 'identitas/zUNpk9pj94fmFCOzRdF0cUlfBbqtorbKigz0xXrF.png', '2026-04-10', '20:45:00', '2026-04-12', '20:45:00', 'kantor', 'kantor', NULL, 'Ambil di Kantor', 'Kembalikan ke Kantor', 'dalam kota', 'tanpa_sopir', 2, '700000.00', 0, NULL, 'Selesai', 'cd020942-b4a5-45b7-98f4-c8b0c56afa5d', NULL, '2026-04-10 03:36:45', '2026-04-10 03:39:50', 'sim_pelanggan/IUZcPRqj26SMwlQMXoDOzKiwhtHbHTi3rOCBLAZO.png', NULL, NULL),
(6, 9, 1, 1, 1, 'Julian olga', '082278824170', 'Gading marpoyan', 'identitas/9DOSVV01uPZb9FQeDBdVyxyqnRqFvm6pFXq9Hy1u.jpg', '2026-04-11', '21:30:00', '2026-04-13', '21:30:00', 'kantor', 'kantor', NULL, 'Ambil di Kantor', 'Kembalikan ke Kantor', 'Luar kota', 'tanpa_sopir', 2, '700000.00', 0, NULL, 'Ditolak', '105fd3b2-660e-4654-b6fa-dde795fd6d6b', NULL, '2026-04-10 06:21:32', '2026-04-17 20:39:19', 'sim_pelanggan/hFH4rEZj4REs0vI6l8VTR63K7T2tGeULRgj9OTMf.jpg', NULL, NULL),
(7, 3, 2, 2, 2, 'ZIKRALLAH AL HADY', '083896517385', 'Jl.Alam Raya GG Naras II', 'identitas/YAymkFvDe3VFGzMX6PXEyFNWOQLFg6G8mXMzamDl.png', '2026-04-11', '20:30:00', '2026-04-13', '20:30:00', 'kantor', 'kantor', NULL, 'Ambil di Kantor', 'Kembalikan ke Kantor', 'dalam kota', 'tanpa_sopir', 2, '700000.00', 0, NULL, 'Selesai', 'e3c5c340-e88e-4edf-93f6-5c59d40b5c60', NULL, '2026-04-10 23:17:27', '2026-04-17 20:38:13', 'sim_pelanggan/QOl7vVHrXG47n8yqbQd8VkDItBB5BJBUyGc8y9T0.png', NULL, NULL),
(8, NULL, 6, 1, 1, NULL, NULL, NULL, NULL, '2026-04-25', '09:00:00', '2026-04-25', '09:00:00', NULL, NULL, NULL, NULL, NULL, NULL, 'tanpa_sopir', 1, '300000.00', 0, NULL, 'Ditolak', NULL, NULL, '2026-04-17 12:52:42', '2026-04-17 20:39:17', NULL, NULL, NULL),
(9, NULL, 5, 3, 3, NULL, NULL, NULL, NULL, '2026-04-20', '09:00:00', '2026-04-20', '09:00:00', NULL, NULL, NULL, NULL, NULL, NULL, 'tanpa_sopir', 1, '550000.00', 0, NULL, 'Ditolak', NULL, 'Temporary draft from Chatbot. Tanggal request: 20 April 2026', '2026-04-17 13:11:50', '2026-04-17 20:39:44', NULL, '031c92de-37f5-4f78-a741-68120db23c53', '2026-04-17 13:26:50'),
(11, NULL, 4, 1, 1, NULL, NULL, NULL, NULL, '2026-04-17', '09:00:00', '2026-04-17', '09:00:00', NULL, NULL, NULL, NULL, NULL, NULL, 'tanpa_sopir', 1, '300000.00', 0, NULL, 'Ditolak', NULL, 'Temporary draft from Chatbot. Tanggal request: tanggal 20 april', '2026-04-17 13:49:15', '2026-04-17 20:39:14', NULL, 'dc936931-92b2-4a79-bc6f-4645a499a2a3', '2026-04-17 14:04:15'),
(12, NULL, 1, 1, 1, NULL, NULL, NULL, NULL, '2026-04-22', '09:00:00', '2026-04-22', '09:00:00', NULL, NULL, NULL, NULL, NULL, NULL, 'tanpa_sopir', 1, '350000.00', 0, NULL, 'Ditolak', NULL, 'Temporary draft from Chatbot. Tanggal request: 22 april', '2026-04-20 10:34:12', '2026-04-21 03:06:22', NULL, '42ed3c09-f74a-4d59-ba0d-94da5854cbff', '2026-04-20 10:49:12'),
(13, NULL, 2, 2, 2, 'ABDUL MUTALIB', '083896517385', 'Jl.Alam Raya GG Naras II', 'identitas/o6YRqZgndJ9uMVMURDKdUSz7QUqbCmMfOym1oY3R.png', '2026-04-23', '20:00:00', '2026-04-26', '20:00:00', 'Kantor Rental', 'Kantor Rental', NULL, NULL, NULL, NULL, 'tanpa_sopir', 3, '1050000.00', 0, NULL, 'Selesai', NULL, 'Pemesanan Guest via AI Bot', '2026-04-20 11:02:21', '2026-04-21 03:29:27', 'sim/FE4bAtN7Cw4oHSaTGq8HeKoTxifkWhUqUWMCVxtF.png', NULL, NULL),
(14, NULL, 6, 1, 1, NULL, NULL, NULL, NULL, '2026-04-22', '09:00:00', '2026-04-22', '09:00:00', NULL, NULL, NULL, NULL, NULL, NULL, 'tanpa_sopir', 1, '300000.00', 0, NULL, 'Ditolak', NULL, 'Temporary draft from Chatbot. Tanggal request: 22 april', '2026-04-21 02:25:17', '2026-04-21 03:06:26', NULL, 'f12aed96-023b-412a-b37b-365956987db5', '2026-04-21 02:40:17'),
(15, NULL, 6, 1, 1, 'ADITIA', '083896517385', 'JL. KULIM RAYA', 'identitas/ofuqSIiEHrD4mEElsZRKqe7qh2yFJec3GSDca9uU.png', '2026-04-22', '09:00:00', '2026-04-25', '09:00:00', 'Kantor Rental', 'Kantor Rental', NULL, NULL, NULL, NULL, 'tanpa_sopir', 3, '900000.00', 0, NULL, 'Selesai', NULL, 'Pemesanan Guest via AI Bot', '2026-04-21 02:51:05', '2026-04-21 03:09:12', 'sim/N14uJxoBX3znjy0PQr5ihDJuc5ri2nbxu3W3YrlC.png', NULL, NULL),
(16, NULL, 6, 1, 1, 'ADITIA', '083896517385', 'harapan raya', 'identitas/exJCfconkY6eUoI6libvrnzebrU732xIytUSKqhC.png', '2026-04-22', '09:00:00', '2026-04-26', '09:00:00', 'Kantor Rental', 'Kantor Rental', NULL, NULL, NULL, NULL, 'tanpa_sopir', 4, '1200000.00', 0, NULL, 'Selesai', NULL, 'Pemesanan Guest via AI Bot', '2026-04-21 03:14:02', '2026-04-21 12:54:43', 'sim/SlIFh1oD0JC72hHIS4ZUR31CqhFbFybhQJgnoIU6.png', NULL, NULL),
(17, NULL, 4, 1, 1, NULL, NULL, NULL, NULL, '2026-04-23', '09:00:00', '2026-04-23', '09:00:00', NULL, NULL, NULL, NULL, NULL, NULL, 'tanpa_sopir', 1, '300000.00', 0, NULL, 'Ditolak', NULL, 'Temporary draft from Chatbot. Tanggal request: 23 April', '2026-04-21 12:43:36', '2026-04-21 14:56:27', NULL, '0c50b2dd-b65e-4972-99aa-4332b7596f46', '2026-04-21 12:58:36'),
(18, NULL, 4, 1, 1, NULL, NULL, NULL, NULL, '2026-04-23', '09:00:00', '2026-04-23', '09:00:00', NULL, NULL, NULL, NULL, NULL, NULL, 'tanpa_sopir', 1, '300000.00', 0, NULL, 'Ditolak', NULL, 'Temporary draft from Chatbot. Tanggal request: 23 April', '2026-04-21 13:16:39', '2026-04-21 14:56:24', NULL, '891f7162-27c7-440b-9f7f-6e8d5971ba89', '2026-04-21 13:31:39'),
(19, NULL, 4, 1, 1, NULL, NULL, NULL, NULL, '2026-04-23', '09:00:00', '2026-04-23', '09:00:00', NULL, NULL, NULL, NULL, NULL, NULL, 'tanpa_sopir', 1, '300000.00', 0, NULL, 'Ditolak', NULL, 'Temporary draft from Chatbot. Tanggal request: 23 April', '2026-04-21 13:47:23', '2026-04-21 14:56:21', NULL, '7eac0e54-912a-404d-8174-059bd90f7767', '2026-04-21 14:02:23'),
(20, NULL, 4, 1, 1, NULL, NULL, NULL, NULL, '2026-04-23', '09:00:00', '2026-04-23', '09:00:00', NULL, NULL, NULL, NULL, NULL, NULL, 'tanpa_sopir', 1, '300000.00', 0, NULL, 'Ditolak', NULL, 'Temporary draft from Chatbot. Tanggal request: 23 April', '2026-04-21 14:05:09', '2026-04-21 14:56:18', NULL, '2f9fca99-3a60-4db5-a0a3-860102d2eb1b', '2026-04-21 14:20:09'),
(21, NULL, 4, 1, 1, NULL, NULL, NULL, NULL, '2026-04-23', '09:00:00', '2026-04-23', '09:00:00', NULL, NULL, NULL, NULL, NULL, NULL, 'tanpa_sopir', 1, '300000.00', 0, NULL, 'Ditolak', NULL, 'Temporary draft from Chatbot. Tanggal request: 23 April', '2026-04-21 14:22:33', '2026-04-21 14:56:15', NULL, '6fb42d8d-0ff0-40e4-8783-61830ddfe4fd', '2026-04-21 14:37:33'),
(22, NULL, 4, 1, 1, NULL, NULL, NULL, NULL, '2026-04-23', '09:00:00', '2026-04-23', '09:00:00', NULL, NULL, NULL, NULL, NULL, NULL, 'tanpa_sopir', 1, '300000.00', 0, NULL, 'Ditolak', NULL, 'Temporary draft from Chatbot. Tanggal request: 23 April', '2026-04-21 14:49:20', '2026-04-21 14:56:12', NULL, '04b97dee-fb2d-4838-a840-d49a2e1f9647', '2026-04-21 15:04:20'),
(23, NULL, 4, 1, 1, 'midlane standar', '082261540328', '-', 'identitas/At3dlzHiPw4SgvoMwF0eLYEjIAqbahnovQlC6Akn.png', '2026-04-23', '09:00:00', '2026-04-23', '09:00:00', NULL, NULL, NULL, NULL, NULL, NULL, 'tanpa_sopir', 1, '500000.00', 200000, NULL, 'Ditolak', NULL, 'Pemesanan Guest via AI Bot', '2026-04-21 14:58:12', '2026-04-21 15:19:40', 'sim/DnS85sfLl10gz0u5NdK9GR2mKPUlNhDUuUIK1khy.png', NULL, NULL),
(24, NULL, 2, 2, 2, NULL, NULL, NULL, NULL, '2026-04-23', '09:00:00', '2026-04-23', '09:00:00', NULL, NULL, NULL, NULL, NULL, NULL, 'tanpa_sopir', 1, '350000.00', 0, NULL, 'Ditolak', NULL, 'Temporary draft from Chatbot. Tanggal request: 23 April', '2026-04-21 15:12:07', '2026-04-21 15:27:41', NULL, '03ea3b60-9079-4f2b-899d-d76d5f53105e', '2026-04-21 15:27:07'),
(25, NULL, 2, 2, 2, 'KRATOS', '083896517385', '-', 'identitas/ybLWlBTc6JRHjU3yKQul3On07D08MSToYDSiLTpD.png', '2026-04-23', '09:00:00', '2026-04-26', '09:00:00', 'Toserba Garuda Sakti, Jalan Garuda Sakti, Air Putih Subdistrict, Tuah Madani District, Pekanbaru, Tuah Madani, Riau, Sumatra, 16547, Indonesia', NULL, NULL, NULL, 'Toserba Garuda Sakti, Jalan Garuda Sakti, Air Putih Subdistrict, Tuah Madani District, Pekanbaru, Tuah Madani, Riau, Sumatra, 16547, Indonesia', NULL, 'tanpa_sopir', 3, '1350000.00', 300000, NULL, 'Selesai', NULL, 'Pemesanan Guest via AI Bot (Reusable Link)', '2026-04-21 15:20:07', '2026-04-21 15:31:21', 'sim/b1xuoUUn83VtvVoEmZbvZ9RZ6uHHhmjjs89DV3fW.png', NULL, NULL),
(26, NULL, 4, 1, 1, 'Guest from AI', '-', NULL, NULL, '2026-04-23', '09:00:00', '2026-04-23', '09:00:00', NULL, NULL, NULL, NULL, NULL, NULL, 'tanpa_sopir', 1, '300000.00', 0, NULL, 'Ditolak', NULL, 'Temporary draft from Chatbot. Tanggal request: 23 April', '2026-04-21 15:28:32', '2026-04-21 22:33:13', NULL, '3582dc44-de09-4790-9485-6bc8c48d9c00', '2027-04-21 15:28:31'),
(27, NULL, 4, 1, 1, 'midlane standar', '082261540328', '-', 'identitas/EFQmxPg0s51ERoJzEWKzLbidb0ImjY53wD9CM3fA.png', '2026-04-23', '09:00:00', '2026-04-24', '09:00:00', 'Gedung PKM UIR, Jalan Kaharuddin Nasution, Air Dingin Subdistrict, Bukit Raya District, Pekanbaru, Marpoyan Damai, Riau, Sumatra, 28284, Indonesia', 'Gedung PKM UIR, Jalan Kaharuddin Nasution, Kelurahan Air Dingin, Bukit Raya, Pekanbaru, Marpoyan Damai, Riau, Sumatra, 28284, Indonesia', NULL, 'Gedung PKM UIR, Jalan Kaharuddin Nasution, Kelurahan Air Dingin, Bukit Raya, Pekanbaru, Marpoyan Damai, Riau, Sumatra, 28284, Indonesia', 'Gedung PKM UIR, Jalan Kaharuddin Nasution, Air Dingin Subdistrict, Bukit Raya District, Pekanbaru, Marpoyan Damai, Riau, Sumatra, 28284, Indonesia', NULL, 'tanpa_sopir', 1, '500000.00', 200000, NULL, 'Selesai', NULL, 'Pemesanan Guest via AI Bot (Reusable Link)', '2026-04-21 15:31:10', '2026-04-21 22:51:34', 'sim/EXNmg6ruDDEbebAbSyjI27UR5N7bkrFtwZHZQZh9.png', NULL, NULL),
(28, NULL, 4, 1, 1, 'ZIKRALLAH AL hady', '083896517385', '-', 'identitas/CnU9aY02wPve14cMjKQDbH3PSbXNlLRHe3cFMA3n.png', '2026-04-26', '09:00:00', '2026-04-29', '09:00:00', 'Sialang Sakti Subdistrict, Tenayan Raya District, Pekanbaru, Riau, Sumatra, 28112, Indonesia', 'Sialang Sakti Subdistrict, Tenayan Raya District, Pekanbaru, Riau, Sumatra, 28112, Indonesia', NULL, 'Sialang Sakti Subdistrict, Tenayan Raya District, Pekanbaru, Riau, Sumatra, 28112, Indonesia', 'Sialang Sakti Subdistrict, Tenayan Raya District, Pekanbaru, Riau, Sumatra, 28112, Indonesia', NULL, 'tanpa_sopir', 3, '1100000.00', 200000, NULL, 'Selesai', NULL, 'Pemesanan Guest via AI Bot', '2026-04-24 08:05:11', '2026-04-24 08:07:25', 'sim/3KY21CpDu3UXc46JiSYjKjXGtXvU3FpAuZuCk4lJ.png', NULL, NULL),
(29, NULL, 2, 2, 2, 'Guest from AI', '-', NULL, NULL, '2026-04-24', '09:00:00', '2026-04-24', '09:00:00', NULL, NULL, NULL, NULL, NULL, NULL, 'tanpa_sopir', 1, '350000.00', 0, NULL, 'Draft', NULL, 'Temporary draft from Chatbot. Tanggal request: Belum diketahui', '2026-04-24 08:09:10', '2026-04-24 08:09:10', NULL, 'fa28fdbe-e1ce-410a-94fb-d09f97d3d449', '2026-04-24 08:39:10'),
(30, NULL, 1, 1, 1, 'Guest from Smart Search', '-', NULL, NULL, '2026-04-24', '09:00:00', '2026-04-24', '09:00:00', NULL, NULL, NULL, NULL, NULL, NULL, 'tanpa_sopir', 1, '350000.00', 0, NULL, 'Draft', NULL, 'Temporary draft from Smart Search', '2026-04-24 09:20:25', '2026-04-24 09:20:25', NULL, 'ef303f27-9ca3-43cd-bd74-db08ecad8be9', '2026-04-24 09:50:25'),
(31, NULL, 6, 1, 1, 'Guest from Smart Search', '-', NULL, NULL, '2026-04-24', '09:00:00', '2026-04-24', '09:00:00', NULL, NULL, NULL, NULL, NULL, NULL, 'tanpa_sopir', 1, '300000.00', 0, NULL, 'Draft', NULL, 'Temporary draft from Smart Search', '2026-04-24 09:20:50', '2026-04-24 09:20:50', NULL, '8bad9de2-1397-4224-a3a9-509032453fc6', '2026-04-24 09:50:50'),
(32, NULL, 6, 1, 1, 'Guest from Smart Search', '-', NULL, NULL, '2026-04-24', '09:00:00', '2026-04-24', '09:00:00', NULL, NULL, NULL, NULL, NULL, NULL, 'tanpa_sopir', 1, '300000.00', 0, NULL, 'Draft', NULL, 'Temporary draft from Smart Search', '2026-04-24 09:21:09', '2026-04-24 09:21:09', NULL, 'b70dbc4c-5f34-4175-b756-40eab6fbe8a0', '2026-04-24 09:51:09'),
(33, NULL, 6, 1, 1, 'Guest from Smart Search', '-', NULL, NULL, '2026-04-24', '09:00:00', '2026-04-24', '09:00:00', NULL, NULL, NULL, NULL, NULL, NULL, 'tanpa_sopir', 1, '300000.00', 0, NULL, 'Draft', NULL, 'Temporary draft from Smart Search', '2026-04-24 09:21:36', '2026-04-24 09:21:36', NULL, '2b6568dd-b8bd-4897-8fe3-8d8ed2ab1a4e', '2026-04-24 09:51:36'),
(34, NULL, 6, 1, 1, 'Guest from Smart Search', '-', NULL, NULL, '2026-04-24', '09:00:00', '2026-04-24', '09:00:00', NULL, NULL, NULL, NULL, NULL, NULL, 'tanpa_sopir', 1, '300000.00', 0, NULL, 'Draft', NULL, 'Temporary draft from Smart Search', '2026-04-24 09:23:36', '2026-04-24 09:23:36', NULL, '0e002df1-a9ed-419d-b41e-5c100ef4d49a', '2026-04-24 09:53:36'),
(35, NULL, 6, 1, 1, 'Guest from Smart Search', '-', NULL, NULL, '2026-04-24', '09:00:00', '2026-04-24', '09:00:00', NULL, NULL, NULL, NULL, NULL, NULL, 'tanpa_sopir', 1, '300000.00', 0, NULL, 'Draft', NULL, 'Temporary draft from Smart Search', '2026-04-24 09:39:03', '2026-04-24 09:39:03', NULL, '4156b31f-6a26-405e-a771-409575a24db6', '2026-04-24 10:09:03'),
(36, NULL, 6, 1, 1, 'Guest from Smart Search', '-', NULL, NULL, '2026-04-24', '09:00:00', '2026-04-24', '09:00:00', NULL, NULL, NULL, NULL, NULL, NULL, 'tanpa_sopir', 1, '300000.00', 0, NULL, 'Draft', NULL, 'Temporary draft from Smart Search', '2026-04-24 09:39:50', '2026-04-24 09:39:50', NULL, 'eeb4613d-b226-41fe-aaf8-b6e14f3b4a00', '2026-04-24 10:09:50'),
(37, NULL, 1, 1, 1, 'Guest from Smart Search', '-', NULL, NULL, '2026-04-24', '09:00:00', '2026-04-24', '09:00:00', NULL, NULL, NULL, NULL, NULL, NULL, 'tanpa_sopir', 1, '350000.00', 0, NULL, 'Draft', NULL, 'Temporary draft from Smart Search', '2026-04-24 09:39:52', '2026-04-24 09:39:52', NULL, 'aafb169c-25e2-44bd-a162-3e63e3ba116c', '2026-04-24 10:09:52'),
(38, NULL, 6, 1, 1, 'Guest from Smart Search', '-', NULL, NULL, '2026-04-24', '09:00:00', '2026-04-24', '09:00:00', NULL, NULL, NULL, NULL, NULL, NULL, 'tanpa_sopir', 1, '300000.00', 0, NULL, 'Draft', NULL, 'Temporary draft from Smart Search', '2026-04-24 09:39:56', '2026-04-24 09:39:56', NULL, 'f954a630-47a5-4994-9bc4-3808a8b7567c', '2026-04-24 10:09:56'),
(39, NULL, 6, 1, 1, 'Guest from Smart Search', '-', NULL, NULL, '2026-04-24', '09:00:00', '2026-04-24', '09:00:00', NULL, NULL, NULL, NULL, NULL, NULL, 'tanpa_sopir', 1, '300000.00', 0, NULL, 'Draft', NULL, 'Temporary draft from Smart Search', '2026-04-24 09:40:04', '2026-04-24 09:40:04', NULL, 'c7b8bb7c-84cc-4a4d-a910-e814bd57a690', '2026-04-24 10:10:04'),
(40, NULL, 1, 1, 1, 'Guest from Smart Search', '-', NULL, NULL, '2026-04-24', '09:00:00', '2026-04-24', '09:00:00', NULL, NULL, NULL, NULL, NULL, NULL, 'tanpa_sopir', 1, '350000.00', 0, NULL, 'Draft', NULL, 'Temporary draft from Smart Search', '2026-04-24 09:40:31', '2026-04-24 09:40:31', NULL, '43c599fb-e659-4b89-9491-2291a2a50716', '2026-04-24 10:10:31'),
(41, NULL, 5, 3, 3, 'Guest from Smart Search', '-', NULL, NULL, '2026-04-24', '09:00:00', '2026-04-24', '09:00:00', NULL, NULL, NULL, NULL, NULL, NULL, 'tanpa_sopir', 1, '550000.00', 0, NULL, 'Draft', NULL, 'Temporary draft from Smart Search', '2026-04-24 09:45:44', '2026-04-24 09:45:44', NULL, '0dcfd688-dbb3-42ae-9f86-f257bc3bc91a', '2026-04-24 10:15:44'),
(42, NULL, 5, 3, 3, 'Guest from Smart Search', '-', NULL, NULL, '2026-04-26', '09:00:00', '2026-04-26', '09:00:00', NULL, NULL, NULL, NULL, NULL, NULL, 'tanpa_sopir', 1, '550000.00', 0, NULL, 'Draft', NULL, 'Temporary draft from Smart Search', '2026-04-25 20:38:06', '2026-04-25 20:38:06', NULL, 'a8319f03-5e28-47f6-932e-70049b251064', '2026-04-25 21:08:06'),
(43, NULL, 1, 1, 1, 'Guest from Smart Search', '-', NULL, NULL, '2026-04-26', '09:00:00', '2026-04-26', '09:00:00', NULL, NULL, NULL, NULL, NULL, NULL, 'tanpa_sopir', 1, '350000.00', 0, NULL, 'Draft', NULL, 'Temporary draft from Smart Search', '2026-04-25 20:38:06', '2026-04-25 20:38:06', NULL, '23c00f83-43a1-4262-b5be-5e7969192a32', '2026-04-25 21:08:06'),
(44, NULL, 6, 1, 1, 'Guest from Smart Search', '-', NULL, NULL, '2026-04-26', '09:00:00', '2026-04-26', '09:00:00', NULL, NULL, NULL, NULL, NULL, NULL, 'tanpa_sopir', 1, '300000.00', 0, NULL, 'Draft', NULL, 'Temporary draft from Smart Search', '2026-04-25 20:38:06', '2026-04-25 20:38:06', NULL, 'e8682a2a-7f28-4b55-b73c-60d56c1c1c41', '2026-04-25 21:08:06'),
(45, NULL, 3, 2, 2, 'Guest from Smart Search', '-', NULL, NULL, '2026-04-26', '09:00:00', '2026-04-26', '09:00:00', NULL, NULL, NULL, NULL, NULL, NULL, 'tanpa_sopir', 1, '300000.00', 0, NULL, 'Draft', NULL, 'Temporary draft from Smart Search', '2026-04-25 20:38:06', '2026-04-25 20:38:06', NULL, '8237afe2-75a1-451f-9b90-1d6f3fe91b78', '2026-04-25 21:08:06'),
(46, NULL, 4, 1, 1, 'Guest from Smart Search', '-', NULL, NULL, '2026-04-26', '09:00:00', '2026-04-26', '09:00:00', NULL, NULL, NULL, NULL, NULL, NULL, 'tanpa_sopir', 1, '300000.00', 0, NULL, 'Draft', NULL, 'Temporary draft from Smart Search', '2026-04-25 20:38:06', '2026-04-25 20:38:06', NULL, 'f0fe8bdf-836d-4435-8166-a514f9261761', '2026-04-25 21:08:06'),
(47, NULL, 2, 2, 2, 'Guest from Smart Search', '-', NULL, NULL, '2026-04-26', '09:00:00', '2026-04-26', '09:00:00', NULL, NULL, NULL, NULL, NULL, NULL, 'tanpa_sopir', 1, '350000.00', 0, NULL, 'Draft', NULL, 'Temporary draft from Smart Search', '2026-04-25 20:38:06', '2026-04-25 20:38:06', NULL, 'd382619f-b47f-49a3-84ab-06710be1aa17', '2026-04-25 21:08:06'),
(49, NULL, 3, 2, 2, 'Guest from Smart Search', '-', NULL, NULL, '2026-04-26', '09:00:00', '2026-04-26', '09:00:00', NULL, NULL, NULL, NULL, NULL, NULL, 'tanpa_sopir', 1, '300000.00', 0, NULL, 'Draft', NULL, 'Temporary draft from Smart Search', '2026-04-25 20:38:06', '2026-04-25 20:38:06', NULL, 'd5b7f6bc-544c-4fff-aea9-dc1a146196f7', '2026-04-25 21:08:06'),
(50, NULL, 4, 1, 1, 'Guest from Smart Search', '-', NULL, NULL, '2026-04-26', '09:00:00', '2026-04-26', '09:00:00', NULL, NULL, NULL, NULL, NULL, NULL, 'tanpa_sopir', 1, '300000.00', 0, NULL, 'Draft', NULL, 'Temporary draft from Smart Search', '2026-04-25 20:38:06', '2026-04-25 20:38:06', NULL, '9741afc0-fbc2-4e51-a089-188670a0471f', '2026-04-25 21:08:06'),
(51, NULL, 2, 2, 2, 'Guest from Smart Search', '-', NULL, NULL, '2026-04-26', '09:00:00', '2026-04-26', '09:00:00', NULL, NULL, NULL, NULL, NULL, NULL, 'tanpa_sopir', 1, '350000.00', 0, NULL, 'Draft', NULL, 'Temporary draft from Smart Search', '2026-04-25 20:38:06', '2026-04-25 20:38:06', NULL, '689e91d6-60b9-4098-941b-4013285a328d', '2026-04-25 21:08:06'),
(52, NULL, 5, 3, 3, 'Guest from Smart Search', '-', NULL, NULL, '2026-04-26', '09:00:00', '2026-04-26', '09:00:00', NULL, NULL, NULL, NULL, NULL, NULL, 'tanpa_sopir', 1, '550000.00', 0, NULL, 'Draft', NULL, 'Temporary draft from Smart Search', '2026-04-25 20:38:27', '2026-04-25 20:38:27', NULL, '060a661f-755f-4b3d-bfce-7a252b86a446', '2026-04-25 21:08:27'),
(53, NULL, 1, 1, 1, 'Guest from Smart Search', '-', NULL, NULL, '2026-04-26', '09:00:00', '2026-04-26', '09:00:00', NULL, NULL, NULL, NULL, NULL, NULL, 'tanpa_sopir', 1, '350000.00', 0, NULL, 'Draft', NULL, 'Temporary draft from Smart Search', '2026-04-25 20:38:27', '2026-04-25 20:38:27', NULL, '3138009e-96ee-4d1b-ac2a-15b724d6c1ec', '2026-04-25 21:08:27'),
(54, NULL, 6, 1, 1, 'Guest from Smart Search', '-', NULL, NULL, '2026-04-26', '09:00:00', '2026-04-26', '09:00:00', NULL, NULL, NULL, NULL, NULL, NULL, 'tanpa_sopir', 1, '300000.00', 0, NULL, 'Draft', NULL, 'Temporary draft from Smart Search', '2026-04-25 20:38:27', '2026-04-25 20:38:27', NULL, 'e7c437eb-2410-4efb-bdd2-8280a9c3cb97', '2026-04-25 21:08:27'),
(55, NULL, 3, 2, 2, 'Guest from Smart Search', '-', NULL, NULL, '2026-04-26', '09:00:00', '2026-04-26', '09:00:00', NULL, NULL, NULL, NULL, NULL, NULL, 'tanpa_sopir', 1, '300000.00', 0, NULL, 'Draft', NULL, 'Temporary draft from Smart Search', '2026-04-25 20:38:27', '2026-04-25 20:38:27', NULL, '5482c49e-b430-4e3f-a078-7c4759045d67', '2026-04-25 21:08:27'),
(56, NULL, 4, 1, 1, 'Guest from Smart Search', '-', NULL, NULL, '2026-04-26', '09:00:00', '2026-04-26', '09:00:00', NULL, NULL, NULL, NULL, NULL, NULL, 'tanpa_sopir', 1, '300000.00', 0, NULL, 'Draft', NULL, 'Temporary draft from Smart Search', '2026-04-25 20:38:27', '2026-04-25 20:38:27', NULL, '1006e24b-efa0-4514-8a25-60317b6cdf1e', '2026-04-25 21:08:27'),
(58, NULL, 2, 2, 2, 'Guest from Smart Search', '-', NULL, NULL, '2026-04-26', '09:00:00', '2026-04-26', '09:00:00', NULL, NULL, NULL, NULL, NULL, NULL, 'tanpa_sopir', 1, '350000.00', 0, NULL, 'Draft', NULL, 'Temporary draft from Smart Search', '2026-04-25 20:38:27', '2026-04-25 20:38:27', NULL, '5821d3c6-f2b7-4d8d-b47e-5aa699b9b326', '2026-04-25 21:08:27'),
(59, NULL, 3, 2, 2, 'Guest from Smart Search', '-', NULL, NULL, '2026-04-26', '09:00:00', '2026-04-26', '09:00:00', NULL, NULL, NULL, NULL, NULL, NULL, 'tanpa_sopir', 1, '300000.00', 0, NULL, 'Draft', NULL, 'Temporary draft from Smart Search', '2026-04-25 20:38:27', '2026-04-25 20:38:27', NULL, '0dac1948-b15e-4af7-bbc6-6a34d129fe09', '2026-04-25 21:08:27'),
(60, NULL, 2, 2, 2, 'Guest from Smart Search', '-', NULL, NULL, '2026-04-26', '09:00:00', '2026-04-26', '09:00:00', NULL, NULL, NULL, NULL, NULL, NULL, 'tanpa_sopir', 1, '350000.00', 0, NULL, 'Draft', NULL, 'Temporary draft from Smart Search', '2026-04-25 20:39:25', '2026-04-25 20:39:25', NULL, 'b98ae732-fb17-4917-89cf-8d204617c797', '2026-04-25 21:09:25'),
(61, NULL, 3, 2, 2, 'Guest from Smart Search', '-', NULL, NULL, '2026-04-26', '09:00:00', '2026-04-26', '09:00:00', NULL, NULL, NULL, NULL, NULL, NULL, 'tanpa_sopir', 1, '300000.00', 0, NULL, 'Draft', NULL, 'Temporary draft from Smart Search', '2026-04-25 20:39:25', '2026-04-25 20:39:25', NULL, 'c7b9722c-a7af-4cfb-99d5-46d1ad8bdb0b', '2026-04-25 21:09:25'),
(62, NULL, 1, 1, 1, 'Guest from Smart Search', '-', NULL, NULL, '2026-04-26', '09:00:00', '2026-04-26', '09:00:00', NULL, NULL, NULL, NULL, NULL, NULL, 'tanpa_sopir', 1, '350000.00', 0, NULL, 'Draft', NULL, 'Temporary draft from Smart Search', '2026-04-25 20:39:25', '2026-04-25 20:39:25', NULL, 'f653e1a4-3bcc-4f36-a947-d879430b6f57', '2026-04-25 21:09:25'),
(63, NULL, 5, 3, 3, 'Guest from Smart Search', '-', NULL, NULL, '2026-04-26', '09:00:00', '2026-04-26', '09:00:00', NULL, NULL, NULL, NULL, NULL, NULL, 'tanpa_sopir', 1, '550000.00', 0, NULL, 'Draft', NULL, 'Temporary draft from Smart Search', '2026-04-25 20:39:25', '2026-04-25 20:39:25', NULL, 'ddea1104-f4ab-4f16-aa66-ad3c26289572', '2026-04-25 21:09:25'),
(64, NULL, 6, 1, 1, 'Guest from Smart Search', '-', NULL, NULL, '2026-04-26', '09:00:00', '2026-04-26', '09:00:00', NULL, NULL, NULL, NULL, NULL, NULL, 'tanpa_sopir', 1, '300000.00', 0, NULL, 'Draft', NULL, 'Temporary draft from Smart Search', '2026-04-25 20:39:25', '2026-04-25 20:39:25', NULL, 'c91ef565-c4e4-47ab-8a34-28238fd64d34', '2026-04-25 21:09:25'),
(66, NULL, 4, 1, 1, 'Guest from Smart Search', '-', NULL, NULL, '2026-04-26', '09:00:00', '2026-04-26', '09:00:00', NULL, NULL, NULL, NULL, NULL, NULL, 'tanpa_sopir', 1, '300000.00', 0, NULL, 'Draft', NULL, 'Temporary draft from Smart Search', '2026-04-25 20:39:25', '2026-04-25 20:39:25', NULL, 'e5b1d14d-b44f-4b40-b10e-7f9ef4c2ef7d', '2026-04-25 21:09:25'),
(67, NULL, 2, 2, 2, 'Guest from Smart Search', '-', NULL, NULL, '2026-04-26', '09:00:00', '2026-04-26', '09:00:00', NULL, NULL, NULL, NULL, NULL, NULL, 'tanpa_sopir', 1, '350000.00', 0, NULL, 'Draft', NULL, 'Temporary draft from Smart Search', '2026-04-25 20:46:35', '2026-04-25 20:46:35', NULL, '883cc027-006d-4068-8391-dbb57e3f7012', '2026-04-25 21:16:35'),
(68, NULL, 3, 2, 2, 'Guest from Smart Search', '-', NULL, NULL, '2026-04-26', '09:00:00', '2026-04-26', '09:00:00', NULL, NULL, NULL, NULL, NULL, NULL, 'tanpa_sopir', 1, '300000.00', 0, NULL, 'Draft', NULL, 'Temporary draft from Smart Search', '2026-04-25 20:46:35', '2026-04-25 20:46:35', NULL, '29560b33-5dbd-4595-88f0-3f02d1f08bb3', '2026-04-25 21:16:35'),
(69, NULL, 1, 1, 1, 'Guest from Smart Search', '-', NULL, NULL, '2026-04-26', '09:00:00', '2026-04-26', '09:00:00', NULL, NULL, NULL, NULL, NULL, NULL, 'tanpa_sopir', 1, '350000.00', 0, NULL, 'Draft', NULL, 'Temporary draft from Smart Search', '2026-04-25 20:46:35', '2026-04-25 20:46:35', NULL, '751cae52-5b94-4b46-99ed-4afecab0efc9', '2026-04-25 21:16:35'),
(70, NULL, 5, 3, 3, 'Guest from Smart Search', '-', NULL, NULL, '2026-04-26', '09:00:00', '2026-04-26', '09:00:00', NULL, NULL, NULL, NULL, NULL, NULL, 'tanpa_sopir', 1, '550000.00', 0, NULL, 'Draft', NULL, 'Temporary draft from Smart Search', '2026-04-25 20:46:51', '2026-04-25 20:46:51', NULL, '2c9ecae8-aadb-449c-ac53-89d91951c5de', '2026-04-25 21:16:51'),
(71, NULL, 1, 1, 1, 'Guest from Smart Search', '-', NULL, NULL, '2026-04-26', '09:00:00', '2026-04-26', '09:00:00', NULL, NULL, NULL, NULL, NULL, NULL, 'tanpa_sopir', 1, '350000.00', 0, NULL, 'Draft', NULL, 'Temporary draft from Smart Search', '2026-04-25 20:46:51', '2026-04-25 20:46:51', NULL, '09c8d003-e202-42b8-91d1-cc6e6542e3b6', '2026-04-25 21:16:51'),
(72, NULL, 6, 1, 1, 'Guest from Smart Search', '-', NULL, NULL, '2026-04-26', '09:00:00', '2026-04-26', '09:00:00', NULL, NULL, NULL, NULL, NULL, NULL, 'tanpa_sopir', 1, '300000.00', 0, NULL, 'Draft', NULL, 'Temporary draft from Smart Search', '2026-04-25 20:46:51', '2026-04-25 20:46:51', NULL, '1767fa5a-be02-4b01-aa36-6c5839c45a04', '2026-04-25 21:16:51'),
(73, NULL, 5, 3, 3, 'Guest from Smart Search', '-', NULL, NULL, '2026-04-26', '09:00:00', '2026-04-26', '09:00:00', NULL, NULL, NULL, NULL, NULL, NULL, 'tanpa_sopir', 1, '550000.00', 0, NULL, 'Draft', NULL, 'Temporary draft from Smart Search', '2026-04-25 20:47:06', '2026-04-25 20:47:06', NULL, '632de1f9-8666-4933-84e0-e4fd8151396f', '2026-04-25 21:17:06'),
(74, NULL, 5, 3, 3, 'Guest from Smart Search', '-', NULL, NULL, '2026-04-26', '09:00:00', '2026-04-26', '09:00:00', NULL, NULL, NULL, NULL, NULL, NULL, 'tanpa_sopir', 1, '550000.00', 0, NULL, 'Draft', NULL, 'Temporary draft from Smart Search', '2026-04-25 20:47:16', '2026-04-25 20:47:16', NULL, 'bbfe857e-2d6a-488c-b3ff-d16de9897c3b', '2026-04-25 21:17:16'),
(75, NULL, 5, 3, 3, 'Guest from Smart Search', '-', NULL, NULL, '2026-04-26', '09:00:00', '2026-04-26', '09:00:00', NULL, NULL, NULL, NULL, NULL, NULL, 'tanpa_sopir', 1, '550000.00', 0, NULL, 'Draft', NULL, 'Temporary draft from Smart Search', '2026-04-25 20:47:20', '2026-04-25 20:47:20', NULL, '4a7d07c5-e9b3-42af-b962-f9536525fecf', '2026-04-25 21:17:20'),
(76, NULL, 1, 1, 1, 'Guest from Smart Search', '-', NULL, NULL, '2026-04-26', '09:00:00', '2026-04-26', '09:00:00', NULL, NULL, NULL, NULL, NULL, NULL, 'tanpa_sopir', 1, '350000.00', 0, NULL, 'Draft', NULL, 'Temporary draft from Smart Search', '2026-04-25 20:47:20', '2026-04-25 20:47:20', NULL, '7353927e-17f7-4065-b1e1-174ee18a694f', '2026-04-25 21:17:20'),
(77, NULL, 6, 1, 1, 'Guest from Smart Search', '-', NULL, NULL, '2026-04-26', '09:00:00', '2026-04-26', '09:00:00', NULL, NULL, NULL, NULL, NULL, NULL, 'tanpa_sopir', 1, '300000.00', 0, NULL, 'Draft', NULL, 'Temporary draft from Smart Search', '2026-04-25 20:47:20', '2026-04-25 20:47:20', NULL, 'df318a9b-b140-435e-9c5f-689c4771bcb7', '2026-04-25 21:17:20'),
(78, NULL, 5, 3, 3, 'Guest from Smart Search', '-', NULL, NULL, '2026-04-26', '09:00:00', '2026-04-26', '09:00:00', NULL, NULL, NULL, NULL, NULL, NULL, 'tanpa_sopir', 1, '550000.00', 0, NULL, 'Draft', NULL, 'Temporary draft from Smart Search', '2026-04-25 20:47:38', '2026-04-25 20:47:38', NULL, '7ed1e728-4771-4aa9-8706-2e13b0ca8afd', '2026-04-25 21:17:38'),
(79, NULL, 5, 3, 3, 'Guest from Smart Search', '-', NULL, NULL, '2026-04-26', '09:00:00', '2026-04-26', '09:00:00', NULL, NULL, NULL, NULL, NULL, NULL, 'tanpa_sopir', 1, '550000.00', 0, NULL, 'Draft', NULL, 'Temporary draft from Smart Search', '2026-04-25 20:48:03', '2026-04-25 20:48:03', NULL, 'acc0fbe6-6f84-42f7-9890-13a1b512a264', '2026-04-25 21:18:03'),
(80, NULL, 6, 1, 1, 'Guest from Smart Search', '-', NULL, NULL, '2026-04-26', '09:00:00', '2026-04-26', '09:00:00', NULL, NULL, NULL, NULL, NULL, NULL, 'tanpa_sopir', 1, '300000.00', 0, NULL, 'Draft', NULL, 'Temporary draft from Smart Search', '2026-04-25 20:48:03', '2026-04-25 20:48:03', NULL, '4580c32d-6475-4f54-a943-1c27fdecc6bf', '2026-04-25 21:18:03'),
(81, NULL, 1, 1, 1, 'Guest from Smart Search', '-', NULL, NULL, '2026-04-26', '09:00:00', '2026-04-26', '09:00:00', NULL, NULL, NULL, NULL, NULL, NULL, 'tanpa_sopir', 1, '350000.00', 0, NULL, 'Draft', NULL, 'Temporary draft from Smart Search', '2026-04-25 20:48:03', '2026-04-25 20:48:03', NULL, 'e8f42667-950b-49a8-b052-6a94cc74ba59', '2026-04-25 21:18:03'),
(82, NULL, 4, 1, 1, 'Guest from Smart Search', '-', NULL, NULL, '2026-04-26', '09:00:00', '2026-04-26', '09:00:00', NULL, NULL, NULL, NULL, NULL, NULL, 'tanpa_sopir', 1, '300000.00', 0, NULL, 'Draft', NULL, 'Temporary draft from Smart Search', '2026-04-25 20:48:03', '2026-04-25 20:48:03', NULL, 'af648efd-7c38-44a4-bd47-4b2f0602fe1b', '2026-04-25 21:18:03'),
(84, NULL, 3, 2, 2, 'Guest from Smart Search', '-', NULL, NULL, '2026-04-26', '09:00:00', '2026-04-26', '09:00:00', NULL, NULL, NULL, NULL, NULL, NULL, 'tanpa_sopir', 1, '300000.00', 0, NULL, 'Draft', NULL, 'Temporary draft from Smart Search', '2026-04-25 20:48:03', '2026-04-25 20:48:03', NULL, '41f203a9-1269-42eb-a186-f6b2bc7aaf96', '2026-04-25 21:18:03'),
(85, NULL, 2, 2, 2, 'Guest from Smart Search', '-', NULL, NULL, '2026-04-26', '09:00:00', '2026-04-26', '09:00:00', NULL, NULL, NULL, NULL, NULL, NULL, 'tanpa_sopir', 1, '350000.00', 0, NULL, 'Draft', NULL, 'Temporary draft from Smart Search', '2026-04-25 20:48:03', '2026-04-25 20:48:03', NULL, 'b0dbf418-a352-4de2-a055-3e978a236fe6', '2026-04-25 21:18:03'),
(86, NULL, 5, 3, 3, 'Guest from Smart Search', '-', NULL, NULL, '2026-04-26', '09:00:00', '2026-04-26', '09:00:00', NULL, NULL, NULL, NULL, NULL, NULL, 'tanpa_sopir', 1, '550000.00', 0, NULL, 'Draft', NULL, 'Temporary draft from Smart Search', '2026-04-25 20:48:36', '2026-04-25 20:48:36', NULL, '777ac806-18d7-4a75-aee5-aa8d34b7021a', '2026-04-25 21:18:36'),
(87, NULL, 1, 1, 1, 'Guest from Smart Search', '-', NULL, NULL, '2026-04-26', '09:00:00', '2026-04-26', '09:00:00', NULL, NULL, NULL, NULL, NULL, NULL, 'tanpa_sopir', 1, '350000.00', 0, NULL, 'Draft', NULL, 'Temporary draft from Smart Search', '2026-04-25 20:48:36', '2026-04-25 20:48:36', NULL, '657e3c65-cd35-4757-a2ec-74b16900b2c7', '2026-04-25 21:18:36'),
(88, NULL, 6, 1, 1, 'Guest from Smart Search', '-', NULL, NULL, '2026-04-26', '09:00:00', '2026-04-26', '09:00:00', NULL, NULL, NULL, NULL, NULL, NULL, 'tanpa_sopir', 1, '300000.00', 0, NULL, 'Draft', NULL, 'Temporary draft from Smart Search', '2026-04-25 20:48:36', '2026-04-25 20:48:36', NULL, 'cd2357b6-50b7-4422-82ec-4fdb83fe9939', '2026-04-25 21:18:36'),
(89, NULL, 5, 3, 3, 'Guest from Smart Search', '-', NULL, NULL, '2026-04-26', '09:00:00', '2026-04-26', '09:00:00', NULL, NULL, NULL, NULL, NULL, NULL, 'tanpa_sopir', 1, '550000.00', 0, NULL, 'Draft', NULL, 'Temporary draft from Smart Search', '2026-04-25 20:50:12', '2026-04-25 20:50:12', NULL, 'f2f5cd7e-96b5-4ce3-9579-06166405a3c8', '2026-04-25 21:20:12'),
(90, NULL, 1, 1, 1, 'Guest from Smart Search', '-', NULL, NULL, '2026-04-26', '09:00:00', '2026-04-26', '09:00:00', NULL, NULL, NULL, NULL, NULL, NULL, 'tanpa_sopir', 1, '350000.00', 0, NULL, 'Draft', NULL, 'Temporary draft from Smart Search', '2026-04-25 20:50:13', '2026-04-25 20:50:13', NULL, '50fc2dba-1bae-479d-a34c-33610c24ebfd', '2026-04-25 21:20:13'),
(91, NULL, 6, 1, 1, 'Guest from Smart Search', '-', NULL, NULL, '2026-04-26', '09:00:00', '2026-04-26', '09:00:00', NULL, NULL, NULL, NULL, NULL, NULL, 'tanpa_sopir', 1, '300000.00', 0, NULL, 'Draft', NULL, 'Temporary draft from Smart Search', '2026-04-25 20:50:13', '2026-04-25 20:50:13', NULL, 'df06a281-fcc5-48ee-b440-d81daccd8ce9', '2026-04-25 21:20:13'),
(92, NULL, 4, 1, 1, 'Guest from Smart Search', '-', NULL, NULL, '2026-04-26', '09:00:00', '2026-04-26', '09:00:00', NULL, NULL, NULL, NULL, NULL, NULL, 'tanpa_sopir', 1, '300000.00', 0, NULL, 'Draft', NULL, 'Temporary draft from Smart Search', '2026-04-25 20:50:13', '2026-04-25 20:50:13', NULL, 'f371c662-6951-4b13-a009-9bff56f66f5a', '2026-04-25 21:20:13'),
(94, NULL, 5, 3, 3, 'Guest from Smart Search', '-', NULL, NULL, '2026-04-26', '09:00:00', '2026-04-26', '09:00:00', NULL, NULL, NULL, NULL, NULL, NULL, 'tanpa_sopir', 1, '550000.00', 0, NULL, 'Draft', NULL, 'Temporary draft from Smart Search', '2026-04-25 20:50:29', '2026-04-25 20:50:29', NULL, 'b24ada91-0f0e-4487-8894-1a9785e86f23', '2026-04-25 21:20:29'),
(95, NULL, 6, 1, 1, 'Guest from Smart Search', '-', NULL, NULL, '2026-04-26', '09:00:00', '2026-04-26', '09:00:00', NULL, NULL, NULL, NULL, NULL, NULL, 'tanpa_sopir', 1, '300000.00', 0, NULL, 'Draft', NULL, 'Temporary draft from Smart Search', '2026-04-25 20:50:29', '2026-04-25 20:50:29', NULL, 'd4109bc1-fa36-415a-8afc-ab1e4d451c98', '2026-04-25 21:20:29'),
(96, NULL, 1, 1, 1, 'Guest from Smart Search', '-', NULL, NULL, '2026-04-26', '09:00:00', '2026-04-26', '09:00:00', NULL, NULL, NULL, NULL, NULL, NULL, 'tanpa_sopir', 1, '350000.00', 0, NULL, 'Draft', NULL, 'Temporary draft from Smart Search', '2026-04-25 20:50:29', '2026-04-25 20:50:29', NULL, '20e0f9d5-4401-46f0-9b8e-3c8b5e0ef121', '2026-04-25 21:20:29'),
(97, NULL, 4, 1, 1, 'Guest from Smart Search', '-', NULL, NULL, '2026-04-26', '09:00:00', '2026-04-26', '09:00:00', NULL, NULL, NULL, NULL, NULL, NULL, 'tanpa_sopir', 1, '300000.00', 0, NULL, 'Draft', NULL, 'Temporary draft from Smart Search', '2026-04-25 20:50:29', '2026-04-25 20:50:29', NULL, '68d3edb3-bc40-4610-88b6-20e30b87884a', '2026-04-25 21:20:29'),
(99, NULL, 3, 2, 2, 'Guest from Smart Search', '-', NULL, NULL, '2026-04-26', '09:00:00', '2026-04-26', '09:00:00', NULL, NULL, NULL, NULL, NULL, NULL, 'tanpa_sopir', 1, '300000.00', 0, NULL, 'Draft', NULL, 'Temporary draft from Smart Search', '2026-04-25 20:50:29', '2026-04-25 20:50:29', NULL, '0ac69033-3418-444a-8fee-0211a1d37f22', '2026-04-25 21:20:29'),
(100, NULL, 2, 2, 2, 'Guest from Smart Search', '-', NULL, NULL, '2026-04-26', '09:00:00', '2026-04-26', '09:00:00', NULL, NULL, NULL, NULL, NULL, NULL, 'tanpa_sopir', 1, '350000.00', 0, NULL, 'Draft', NULL, 'Temporary draft from Smart Search', '2026-04-25 20:50:29', '2026-04-25 20:50:29', NULL, '0af3285b-d3ea-4a7b-b103-d803b92bfa27', '2026-04-25 21:20:29'),
(101, NULL, 5, 3, 3, 'Guest from Smart Search', '-', NULL, NULL, '2026-04-26', '09:00:00', '2026-04-26', '09:00:00', NULL, NULL, NULL, NULL, NULL, NULL, 'tanpa_sopir', 1, '550000.00', 0, NULL, 'Draft', NULL, 'Temporary draft from Smart Search', '2026-04-25 20:50:41', '2026-04-25 20:50:41', NULL, 'dc9bffa9-d9cc-4036-9b80-7c6ec12ace00', '2026-04-25 21:20:41'),
(102, NULL, 5, 3, 3, 'Guest from Smart Search', '-', NULL, NULL, '2026-04-26', '09:00:00', '2026-04-26', '09:00:00', NULL, NULL, NULL, NULL, NULL, NULL, 'tanpa_sopir', 1, '550000.00', 0, NULL, 'Draft', NULL, 'Temporary draft from Smart Search', '2026-04-25 20:50:45', '2026-04-25 20:50:45', NULL, '7730ad36-637b-4f77-9377-12331662e893', '2026-04-25 21:20:45'),
(103, NULL, 1, 1, 1, 'Guest from Smart Search', '-', NULL, NULL, '2026-04-26', '09:00:00', '2026-04-26', '09:00:00', NULL, NULL, NULL, NULL, NULL, NULL, 'tanpa_sopir', 1, '350000.00', 0, NULL, 'Draft', NULL, 'Temporary draft from Smart Search', '2026-04-25 20:50:45', '2026-04-25 20:50:45', NULL, 'bb1a8dca-5d34-4179-9122-0f50f3af036f', '2026-04-25 21:20:45'),
(104, NULL, 6, 1, 1, 'Guest from Smart Search', '-', NULL, NULL, '2026-04-26', '09:00:00', '2026-04-26', '09:00:00', NULL, NULL, NULL, NULL, NULL, NULL, 'tanpa_sopir', 1, '300000.00', 0, NULL, 'Draft', NULL, 'Temporary draft from Smart Search', '2026-04-25 20:50:45', '2026-04-25 20:50:45', NULL, '972a9ea3-3fb8-44e5-af0a-04475505f479', '2026-04-25 21:20:45'),
(105, NULL, 5, 3, 3, 'Guest from Smart Search', '-', NULL, NULL, '2026-04-26', '09:00:00', '2026-04-26', '09:00:00', NULL, NULL, NULL, NULL, NULL, NULL, 'tanpa_sopir', 1, '550000.00', 0, NULL, 'Draft', NULL, 'Temporary draft from Smart Search', '2026-04-25 21:12:26', '2026-04-25 21:12:26', NULL, '9011021d-0240-43ed-bb9c-e9a313a2bd33', '2026-04-25 21:42:26'),
(106, NULL, 6, 1, 1, 'Guest from Smart Search', '-', NULL, NULL, '2026-04-26', '09:00:00', '2026-04-26', '09:00:00', NULL, NULL, NULL, NULL, NULL, NULL, 'tanpa_sopir', 1, '300000.00', 0, NULL, 'Draft', NULL, 'Temporary draft from Smart Search', '2026-04-25 21:12:26', '2026-04-25 21:12:26', NULL, '84215549-649a-4ccf-8467-e6c466b901d0', '2026-04-25 21:42:26'),
(107, NULL, 1, 1, 1, 'Guest from Smart Search', '-', NULL, NULL, '2026-04-26', '09:00:00', '2026-04-26', '09:00:00', NULL, NULL, NULL, NULL, NULL, NULL, 'tanpa_sopir', 1, '350000.00', 0, NULL, 'Draft', NULL, 'Temporary draft from Smart Search', '2026-04-25 21:12:26', '2026-04-25 21:12:26', NULL, '471c0380-7966-49a8-8420-8aa0205941d2', '2026-04-25 21:42:26'),
(108, NULL, 4, 1, 1, 'Guest from Smart Search', '-', NULL, NULL, '2026-04-26', '09:00:00', '2026-04-26', '09:00:00', NULL, NULL, NULL, NULL, NULL, NULL, 'tanpa_sopir', 1, '300000.00', 0, NULL, 'Draft', NULL, 'Temporary draft from Smart Search', '2026-04-25 21:12:26', '2026-04-25 21:12:26', NULL, 'c6726cae-5bdd-41cb-acb0-f8972f9b2959', '2026-04-25 21:42:26'),
(110, NULL, 3, 2, 2, 'Guest from Smart Search', '-', NULL, NULL, '2026-04-26', '09:00:00', '2026-04-26', '09:00:00', NULL, NULL, NULL, NULL, NULL, NULL, 'tanpa_sopir', 1, '300000.00', 0, NULL, 'Draft', NULL, 'Temporary draft from Smart Search', '2026-04-25 21:12:26', '2026-04-25 21:12:26', NULL, '407d96c0-2d1c-4e53-88bd-9c189ae6fa53', '2026-04-25 21:42:26'),
(111, NULL, 2, 2, 2, 'Guest from Smart Search', '-', NULL, NULL, '2026-04-26', '09:00:00', '2026-04-26', '09:00:00', NULL, NULL, NULL, NULL, NULL, NULL, 'tanpa_sopir', 1, '350000.00', 0, NULL, 'Draft', NULL, 'Temporary draft from Smart Search', '2026-04-25 21:12:26', '2026-04-25 21:12:26', NULL, '4536a4e3-5251-4853-8c66-4ca5f522f9d8', '2026-04-25 21:42:26'),
(112, NULL, 4, 1, 1, 'Guest from Smart Search', '-', NULL, NULL, '2026-04-26', '09:00:00', '2026-04-26', '09:00:00', NULL, NULL, NULL, NULL, NULL, NULL, 'tanpa_sopir', 1, '300000.00', 0, NULL, 'Draft', NULL, 'Temporary draft from Smart Search', '2026-04-25 21:30:31', '2026-04-25 21:30:31', NULL, '59d0e1e7-3ff0-4fd5-ab69-806c91b13100', '2026-04-25 22:00:31'),
(113, NULL, 6, 1, 1, 'Guest from Smart Search', '-', NULL, NULL, '2026-04-26', '09:00:00', '2026-04-26', '09:00:00', NULL, NULL, NULL, NULL, NULL, NULL, 'tanpa_sopir', 1, '300000.00', 0, NULL, 'Draft', NULL, 'Temporary draft from Smart Search', '2026-04-25 21:30:31', '2026-04-25 21:30:31', NULL, '4af70fa8-81d6-4f26-93b9-dc1d4ca6900a', '2026-04-25 22:00:31'),
(114, NULL, 1, 1, 1, 'Guest from Smart Search', '-', NULL, NULL, '2026-04-26', '09:00:00', '2026-04-26', '09:00:00', NULL, NULL, NULL, NULL, NULL, NULL, 'tanpa_sopir', 1, '350000.00', 0, NULL, 'Draft', NULL, 'Temporary draft from Smart Search', '2026-04-25 21:30:31', '2026-04-25 21:30:31', NULL, '9f571a7f-b2c7-4a46-bb03-e2c7fa29c304', '2026-04-25 22:00:31'),
(115, NULL, 5, 3, 3, 'Guest from Smart Search', '-', NULL, NULL, '2026-04-26', '09:00:00', '2026-04-26', '09:00:00', NULL, NULL, NULL, NULL, NULL, NULL, 'tanpa_sopir', 1, '550000.00', 0, NULL, 'Draft', NULL, 'Temporary draft from Smart Search', '2026-04-25 21:30:31', '2026-04-25 21:30:31', NULL, '0920db50-345c-4389-924b-5ec6be68877d', '2026-04-25 22:00:31'),
(117, NULL, 4, 1, 1, 'Guest from Smart Search', '-', NULL, NULL, '2026-04-26', '09:00:00', '2026-04-26', '09:00:00', NULL, NULL, NULL, NULL, NULL, NULL, 'tanpa_sopir', 1, '300000.00', 0, NULL, 'Draft', NULL, 'Temporary draft from Smart Search', '2026-04-25 21:31:12', '2026-04-25 21:31:12', NULL, '14066bc0-3a66-4b9b-bc51-093d20ac4b4e', '2026-04-25 22:01:12'),
(118, NULL, 6, 1, 1, 'Guest from Smart Search', '-', NULL, NULL, '2026-04-26', '09:00:00', '2026-04-26', '09:00:00', NULL, NULL, NULL, NULL, NULL, NULL, 'tanpa_sopir', 1, '300000.00', 0, NULL, 'Draft', NULL, 'Temporary draft from Smart Search', '2026-04-25 21:31:12', '2026-04-25 21:31:12', NULL, '87f8bff0-be86-49d6-8942-dc0c409964a6', '2026-04-25 22:01:12'),
(119, NULL, 1, 1, 1, 'Guest from Smart Search', '-', NULL, NULL, '2026-04-26', '09:00:00', '2026-04-26', '09:00:00', NULL, NULL, NULL, NULL, NULL, NULL, 'tanpa_sopir', 1, '350000.00', 0, NULL, 'Draft', NULL, 'Temporary draft from Smart Search', '2026-04-25 21:31:12', '2026-04-25 21:31:12', NULL, 'd6b432ef-f634-4bfb-b974-4e7ae759ed65', '2026-04-25 22:01:12'),
(120, NULL, 5, 3, 3, 'Guest from Smart Search', '-', NULL, NULL, '2026-04-26', '09:00:00', '2026-04-26', '09:00:00', NULL, NULL, NULL, NULL, NULL, NULL, 'tanpa_sopir', 1, '550000.00', 0, NULL, 'Draft', NULL, 'Temporary draft from Smart Search', '2026-04-25 21:31:12', '2026-04-25 21:31:12', NULL, '040d1855-b21d-46cb-bd41-2f9b788f9394', '2026-04-25 22:01:12'),
(122, NULL, 4, 1, 1, 'Guest from Smart Search', '-', NULL, NULL, '2026-04-26', '09:00:00', '2026-04-26', '09:00:00', NULL, NULL, NULL, NULL, NULL, NULL, 'tanpa_sopir', 1, '300000.00', 0, NULL, 'Draft', NULL, 'Temporary draft from Smart Search', '2026-04-25 21:31:23', '2026-04-25 21:31:23', NULL, '9bd97ea9-198b-419b-a8f9-85bf0d9f6e46', '2026-04-25 22:01:23'),
(123, NULL, 6, 1, 1, 'Guest from Smart Search', '-', NULL, NULL, '2026-04-26', '09:00:00', '2026-04-26', '09:00:00', NULL, NULL, NULL, NULL, NULL, NULL, 'tanpa_sopir', 1, '300000.00', 0, NULL, 'Draft', NULL, 'Temporary draft from Smart Search', '2026-04-25 21:31:23', '2026-04-25 21:31:23', NULL, '5f91e2e4-4811-4c9c-b39f-f24b17bfd385', '2026-04-25 22:01:23'),
(124, NULL, 1, 1, 1, 'Guest from Smart Search', '-', NULL, NULL, '2026-04-26', '09:00:00', '2026-04-26', '09:00:00', NULL, NULL, NULL, NULL, NULL, NULL, 'tanpa_sopir', 1, '350000.00', 0, NULL, 'Draft', NULL, 'Temporary draft from Smart Search', '2026-04-25 21:31:23', '2026-04-25 21:31:23', NULL, 'febb69d9-450b-428b-a9a3-c33c74d3984b', '2026-04-25 22:01:23'),
(125, NULL, 5, 3, 3, 'Guest from Smart Search', '-', NULL, NULL, '2026-04-26', '09:00:00', '2026-04-26', '09:00:00', NULL, NULL, NULL, NULL, NULL, NULL, 'tanpa_sopir', 1, '550000.00', 0, NULL, 'Draft', NULL, 'Temporary draft from Smart Search', '2026-04-25 21:31:23', '2026-04-25 21:31:23', NULL, 'b4b81cad-ecd4-4721-8c06-cabb3be8d0b4', '2026-04-25 22:01:23'),
(126, NULL, 3, 2, 2, 'Guest from Smart Search', '-', NULL, NULL, '2026-04-26', '09:00:00', '2026-04-26', '09:00:00', NULL, NULL, NULL, NULL, NULL, NULL, 'tanpa_sopir', 1, '300000.00', 0, NULL, 'Draft', NULL, 'Temporary draft from Smart Search', '2026-04-25 21:31:23', '2026-04-25 21:31:23', NULL, 'ef8d2885-7096-4e43-bda0-e8e5ca45863e', '2026-04-25 22:01:23'),
(127, NULL, 2, 2, 2, 'Guest from Smart Search', '-', NULL, NULL, '2026-04-26', '09:00:00', '2026-04-26', '09:00:00', NULL, NULL, NULL, NULL, NULL, NULL, 'tanpa_sopir', 1, '350000.00', 0, NULL, 'Draft', NULL, 'Temporary draft from Smart Search', '2026-04-25 21:31:23', '2026-04-25 21:31:23', NULL, '7bbc12bd-65cf-48e0-86ff-46c327a9ae5c', '2026-04-25 22:01:23'),
(129, NULL, 4, 1, 1, 'Guest from Smart Search', '-', NULL, NULL, '2026-04-26', '09:00:00', '2026-04-26', '09:00:00', NULL, NULL, NULL, NULL, NULL, NULL, 'tanpa_sopir', 1, '300000.00', 0, NULL, 'Draft', NULL, 'Temporary draft from Smart Search', '2026-04-25 21:31:40', '2026-04-25 21:31:40', NULL, '5fce19d4-6737-46dc-a13d-3265bd328db0', '2026-04-25 22:01:40'),
(130, NULL, 6, 1, 1, 'Guest from Smart Search', '-', NULL, NULL, '2026-04-26', '09:00:00', '2026-04-26', '09:00:00', NULL, NULL, NULL, NULL, NULL, NULL, 'tanpa_sopir', 1, '300000.00', 0, NULL, 'Draft', NULL, 'Temporary draft from Smart Search', '2026-04-25 21:31:40', '2026-04-25 21:31:40', NULL, '0f0c1c9c-015e-4c1c-8b8b-a3afe65e1bd8', '2026-04-25 22:01:40'),
(131, NULL, 5, 3, 3, 'Guest from Smart Search', '-', NULL, NULL, '2026-04-26', '09:00:00', '2026-04-26', '09:00:00', NULL, NULL, NULL, NULL, NULL, NULL, 'tanpa_sopir', 1, '550000.00', 0, NULL, 'Draft', NULL, 'Temporary draft from Smart Search', '2026-04-25 21:31:40', '2026-04-25 21:31:40', NULL, 'd1e989d8-03f4-478c-ab14-03ae0fe62452', '2026-04-25 22:01:40'),
(132, NULL, 1, 1, 1, 'Guest from Smart Search', '-', NULL, NULL, '2026-04-26', '09:00:00', '2026-04-26', '09:00:00', NULL, NULL, NULL, NULL, NULL, NULL, 'tanpa_sopir', 1, '350000.00', 0, NULL, 'Draft', NULL, 'Temporary draft from Smart Search', '2026-04-25 21:31:40', '2026-04-25 21:31:40', NULL, 'd0ee8965-955a-4471-8e01-cb1dca9ae619', '2026-04-25 22:01:40'),
(133, NULL, 4, 1, 1, 'Guest from Smart Search', '-', NULL, NULL, '2026-04-26', '09:00:00', '2026-04-26', '09:00:00', NULL, NULL, NULL, NULL, NULL, NULL, 'tanpa_sopir', 1, '300000.00', 0, NULL, 'Draft', NULL, 'Temporary draft from Smart Search', '2026-04-25 21:39:52', '2026-04-25 21:39:52', NULL, '8214b379-87b9-4143-a9df-f0fb02c04831', '2026-04-25 22:09:52'),
(134, NULL, 6, 1, 1, 'Guest from Smart Search', '-', NULL, NULL, '2026-04-26', '09:00:00', '2026-04-26', '09:00:00', NULL, NULL, NULL, NULL, NULL, NULL, 'tanpa_sopir', 1, '300000.00', 0, NULL, 'Draft', NULL, 'Temporary draft from Smart Search', '2026-04-25 21:39:52', '2026-04-25 21:39:52', NULL, '710f2f9e-cc5c-481d-b600-977a8b0f8c8c', '2026-04-25 22:09:52'),
(135, NULL, 5, 3, 3, 'Guest from Smart Search', '-', NULL, NULL, '2026-04-26', '09:00:00', '2026-04-26', '09:00:00', NULL, NULL, NULL, NULL, NULL, NULL, 'tanpa_sopir', 1, '550000.00', 0, NULL, 'Draft', NULL, 'Temporary draft from Smart Search', '2026-04-25 21:39:52', '2026-04-25 21:39:52', NULL, '713924eb-7766-466f-98bc-d0549e5ba3b1', '2026-04-25 22:09:52'),
(136, NULL, 1, 1, 1, 'Guest from Smart Search', '-', NULL, NULL, '2026-04-26', '09:00:00', '2026-04-26', '09:00:00', NULL, NULL, NULL, NULL, NULL, NULL, 'tanpa_sopir', 1, '350000.00', 0, NULL, 'Draft', NULL, 'Temporary draft from Smart Search', '2026-04-25 21:39:52', '2026-04-25 21:39:52', NULL, 'cf0b6567-97f7-433e-b95d-183cbffae39d', '2026-04-25 22:09:52'),
(137, NULL, 4, 1, 1, 'Guest from Smart Search', '-', NULL, NULL, '2026-04-26', '09:00:00', '2026-04-26', '09:00:00', NULL, NULL, NULL, NULL, NULL, NULL, 'tanpa_sopir', 1, '300000.00', 0, NULL, 'Draft', NULL, 'Temporary draft from Smart Search', '2026-04-25 21:39:59', '2026-04-25 21:39:59', NULL, 'b925c91c-4262-476c-8130-5e1154c851a5', '2026-04-25 22:09:59'),
(138, NULL, 6, 1, 1, 'Guest from Smart Search', '-', NULL, NULL, '2026-04-26', '09:00:00', '2026-04-26', '09:00:00', NULL, NULL, NULL, NULL, NULL, NULL, 'tanpa_sopir', 1, '300000.00', 0, NULL, 'Draft', NULL, 'Temporary draft from Smart Search', '2026-04-25 21:39:59', '2026-04-25 21:39:59', NULL, 'c3d9c794-1939-44df-b4c2-c7eff89f611c', '2026-04-25 22:09:59'),
(139, NULL, 5, 3, 3, 'Guest from Smart Search', '-', NULL, NULL, '2026-04-26', '09:00:00', '2026-04-26', '09:00:00', NULL, NULL, NULL, NULL, NULL, NULL, 'tanpa_sopir', 1, '550000.00', 0, NULL, 'Draft', NULL, 'Temporary draft from Smart Search', '2026-04-25 21:39:59', '2026-04-25 21:39:59', NULL, '248566c2-23c6-43d2-bb4a-c09013466763', '2026-04-25 22:09:59'),
(140, NULL, 1, 1, 1, 'Guest from Smart Search', '-', NULL, NULL, '2026-04-26', '09:00:00', '2026-04-26', '09:00:00', NULL, NULL, NULL, NULL, NULL, NULL, 'tanpa_sopir', 1, '350000.00', 0, NULL, 'Draft', NULL, 'Temporary draft from Smart Search', '2026-04-25 21:39:59', '2026-04-25 21:39:59', NULL, 'f2b9c818-8e6f-4adf-87d4-addd076ac10c', '2026-04-25 22:09:59'),
(141, NULL, 4, 1, 1, 'Guest from Smart Search', '-', NULL, NULL, '2026-04-26', '09:00:00', '2026-04-26', '09:00:00', NULL, NULL, NULL, NULL, NULL, NULL, 'tanpa_sopir', 1, '300000.00', 0, NULL, 'Draft', NULL, 'Temporary draft from Smart Search', '2026-04-25 22:13:58', '2026-04-25 22:13:58', NULL, '27b57013-a6bd-40cd-8350-114544dd1d1c', '2026-04-25 22:43:58'),
(142, NULL, 1, 1, 1, 'Guest from Smart Search', '-', NULL, NULL, '2026-04-26', '09:00:00', '2026-04-26', '09:00:00', NULL, NULL, NULL, NULL, NULL, NULL, 'tanpa_sopir', 1, '350000.00', 0, NULL, 'Draft', NULL, 'Temporary draft from Smart Search', '2026-04-25 22:13:58', '2026-04-25 22:13:58', NULL, '70820451-76a0-4f92-b287-7f7fc6a0cec9', '2026-04-25 22:43:58');
INSERT INTO `transaksis` (`id`, `user_id`, `mobil_id`, `rental_id`, `branch_id`, `nama`, `no_hp`, `alamat`, `foto_identitas`, `tgl_ambil`, `jam_ambil`, `tgl_kembali`, `jam_kembali`, `lokasi_ambil`, `lokasi_kembali`, `alamat_lengkap`, `alamat_jemput`, `alamat_antar`, `tujuan`, `sopir`, `lama_sewa`, `total_harga`, `biaya_tambahan`, `bukti_bayar`, `status`, `snap_token`, `catatan`, `created_at`, `updated_at`, `foto_sim`, `booking_token`, `token_expires_at`) VALUES
(143, NULL, 6, 1, 1, 'Guest from Smart Search', '-', NULL, NULL, '2026-04-26', '09:00:00', '2026-04-26', '09:00:00', NULL, NULL, NULL, NULL, NULL, NULL, 'tanpa_sopir', 1, '300000.00', 0, NULL, 'Draft', NULL, 'Temporary draft from Smart Search', '2026-04-25 22:13:58', '2026-04-25 22:13:58', NULL, '56288d39-19fe-4939-838a-872d6f22010f', '2026-04-25 22:43:58'),
(144, NULL, 3, 2, 2, 'Guest from Smart Search', '-', NULL, NULL, '2026-04-26', '09:00:00', '2026-04-26', '09:00:00', NULL, NULL, NULL, NULL, NULL, NULL, 'tanpa_sopir', 1, '300000.00', 0, NULL, 'Draft', NULL, 'Temporary draft from Smart Search', '2026-04-25 22:13:58', '2026-04-25 22:13:58', NULL, '4920ff74-8367-4e95-8494-6edaace1da32', '2026-04-25 22:43:58'),
(146, NULL, 4, 1, 1, 'Guest from Smart Search', '-', NULL, NULL, '2026-04-26', '09:00:00', '2026-04-26', '09:00:00', NULL, NULL, NULL, NULL, NULL, NULL, 'tanpa_sopir', 1, '300000.00', 0, NULL, 'Draft', NULL, 'Temporary draft from Smart Search', '2026-04-25 22:14:25', '2026-04-25 22:14:25', NULL, '6f93985f-d23e-4da0-ab75-9e1e5f5c0333', '2026-04-25 22:44:25'),
(147, NULL, 1, 1, 1, 'Guest from Smart Search', '-', NULL, NULL, '2026-04-26', '09:00:00', '2026-04-26', '09:00:00', NULL, NULL, NULL, NULL, NULL, NULL, 'tanpa_sopir', 1, '350000.00', 0, NULL, 'Draft', NULL, 'Temporary draft from Smart Search', '2026-04-25 22:14:25', '2026-04-25 22:14:25', NULL, 'dbdfc99c-85d3-429a-8be3-a8d562c72b08', '2026-04-25 22:44:25'),
(148, NULL, 6, 1, 1, 'Guest from Smart Search', '-', NULL, NULL, '2026-04-26', '09:00:00', '2026-04-26', '09:00:00', NULL, NULL, NULL, NULL, NULL, NULL, 'tanpa_sopir', 1, '300000.00', 0, NULL, 'Draft', NULL, 'Temporary draft from Smart Search', '2026-04-25 22:14:25', '2026-04-25 22:14:25', NULL, '439edada-f09c-46e3-8293-ed1ad02f509c', '2026-04-25 22:44:25'),
(150, NULL, 3, 2, 2, 'Guest from Smart Search', '-', NULL, NULL, '2026-04-26', '09:00:00', '2026-04-26', '09:00:00', NULL, NULL, NULL, NULL, NULL, NULL, 'tanpa_sopir', 1, '300000.00', 0, NULL, 'Draft', NULL, 'Temporary draft from Smart Search', '2026-04-25 22:14:25', '2026-04-25 22:14:25', NULL, 'cf533c5a-5dbe-4a96-8031-41eb3d71c520', '2026-04-25 22:44:25'),
(151, NULL, 5, 3, 3, 'Guest from Smart Search', '-', NULL, NULL, '2026-04-26', '09:00:00', '2026-04-26', '09:00:00', NULL, NULL, NULL, NULL, NULL, NULL, 'tanpa_sopir', 1, '550000.00', 0, NULL, 'Draft', NULL, 'Temporary draft from Smart Search', '2026-04-25 22:14:25', '2026-04-25 22:14:25', NULL, '29515cf0-56f5-4fbe-a2b1-66e867497392', '2026-04-25 22:44:25'),
(152, NULL, 2, 2, 2, 'Guest from Smart Search', '-', NULL, NULL, '2026-04-26', '09:00:00', '2026-04-26', '09:00:00', NULL, NULL, NULL, NULL, NULL, NULL, 'tanpa_sopir', 1, '350000.00', 0, NULL, 'Draft', NULL, 'Temporary draft from Smart Search', '2026-04-25 22:14:25', '2026-04-25 22:14:25', NULL, 'a4244ea1-abbc-4de2-aed5-6d3a5766d3c3', '2026-04-25 22:44:25'),
(153, NULL, 3, 2, 2, 'Guest from Smart Search', '-', NULL, NULL, '2026-04-26', '09:00:00', '2026-04-26', '09:00:00', NULL, NULL, NULL, NULL, NULL, NULL, 'tanpa_sopir', 1, '300000.00', 0, NULL, 'Draft', NULL, 'Temporary draft from Smart Search', '2026-04-25 22:14:25', '2026-04-25 22:14:25', NULL, 'a19a140c-b23a-4ea9-bd40-6de6d55f0c4d', '2026-04-25 22:44:25'),
(154, NULL, 1, 1, 1, 'Guest from Smart Search', '-', NULL, NULL, '2026-04-26', '09:00:00', '2026-04-26', '09:00:00', NULL, NULL, NULL, NULL, NULL, NULL, 'tanpa_sopir', 1, '350000.00', 0, NULL, 'Draft', NULL, 'Temporary draft from Smart Search', '2026-04-25 22:14:25', '2026-04-25 22:14:25', NULL, '7457001e-79ea-4c87-bda1-6dc80848336b', '2026-04-25 22:44:25'),
(155, NULL, 6, 1, 1, 'Guest from Smart Search', '-', NULL, NULL, '2026-04-26', '09:00:00', '2026-04-26', '09:00:00', NULL, NULL, NULL, NULL, NULL, NULL, 'tanpa_sopir', 1, '300000.00', 0, NULL, 'Draft', NULL, 'Temporary draft from Smart Search', '2026-04-25 22:14:25', '2026-04-25 22:14:25', NULL, '230ffc85-e416-4faa-96de-d27adca04f77', '2026-04-25 22:44:25'),
(156, NULL, 4, 1, 1, 'Guest from Smart Search', '-', NULL, NULL, '2026-04-26', '09:00:00', '2026-04-26', '09:00:00', NULL, NULL, NULL, NULL, NULL, NULL, 'tanpa_sopir', 1, '300000.00', 0, NULL, 'Draft', NULL, 'Temporary draft from Smart Search', '2026-04-25 22:26:43', '2026-04-25 22:26:43', NULL, '512ee945-f634-41f3-9305-787ae80e1eee', '2026-04-25 22:56:43'),
(158, NULL, 1, 1, 1, 'Guest from Smart Search', '-', NULL, NULL, '2026-04-26', '09:00:00', '2026-04-26', '09:00:00', NULL, NULL, NULL, NULL, NULL, NULL, 'tanpa_sopir', 1, '350000.00', 0, NULL, 'Draft', NULL, 'Temporary draft from Smart Search', '2026-04-25 22:26:43', '2026-04-25 22:26:43', NULL, 'a63c3daf-1f73-45e3-8b8a-c3342cd7d5e3', '2026-04-25 22:56:43'),
(159, NULL, 6, 1, 1, 'Guest from Smart Search', '-', NULL, NULL, '2026-04-26', '09:00:00', '2026-04-26', '09:00:00', NULL, NULL, NULL, NULL, NULL, NULL, 'tanpa_sopir', 1, '300000.00', 0, NULL, 'Draft', NULL, 'Temporary draft from Smart Search', '2026-04-25 22:26:43', '2026-04-25 22:26:43', NULL, 'b2e41cd0-355a-4d00-8992-9fa56790ed4e', '2026-04-25 22:56:43'),
(160, NULL, 3, 2, 2, 'Guest from Smart Search', '-', NULL, NULL, '2026-04-26', '09:00:00', '2026-04-26', '09:00:00', NULL, NULL, NULL, NULL, NULL, NULL, 'tanpa_sopir', 1, '300000.00', 0, NULL, 'Draft', NULL, 'Temporary draft from Smart Search', '2026-04-25 22:26:43', '2026-04-25 22:26:43', NULL, '4527c778-ad4b-4917-bde0-d0a333a7db5d', '2026-04-25 22:56:43'),
(161, NULL, 5, 3, 3, 'Guest from Smart Search', '-', NULL, NULL, '2026-04-26', '09:00:00', '2026-04-26', '09:00:00', NULL, NULL, NULL, NULL, NULL, NULL, 'tanpa_sopir', 1, '550000.00', 0, NULL, 'Draft', NULL, 'Temporary draft from Smart Search', '2026-04-25 22:26:43', '2026-04-25 22:26:43', NULL, 'bb518a69-3ddd-4693-9a6e-fc5c69d64e00', '2026-04-25 22:56:43'),
(162, NULL, 2, 2, 2, 'Guest from Smart Search', '-', NULL, NULL, '2026-04-26', '09:00:00', '2026-04-26', '09:00:00', NULL, NULL, NULL, NULL, NULL, NULL, 'tanpa_sopir', 1, '350000.00', 0, NULL, 'Draft', NULL, 'Temporary draft from Smart Search', '2026-04-25 22:26:43', '2026-04-25 22:26:43', NULL, 'ec745758-7c0c-4750-a483-9c1624c64e13', '2026-04-25 22:56:43'),
(163, NULL, 4, 1, 1, 'Guest from Smart Search', '-', NULL, NULL, '2026-04-26', '09:00:00', '2026-04-26', '09:00:00', NULL, NULL, NULL, NULL, NULL, NULL, 'tanpa_sopir', 1, '300000.00', 0, NULL, 'Draft', NULL, 'Temporary draft from Smart Search', '2026-04-25 22:26:43', '2026-04-25 22:26:43', NULL, 'bba8b909-940b-4a83-9365-1c405fa2dcdc', '2026-04-25 22:56:43'),
(165, NULL, 1, 1, 1, 'Guest from Smart Search', '-', NULL, NULL, '2026-04-26', '09:00:00', '2026-04-26', '09:00:00', NULL, NULL, NULL, NULL, NULL, NULL, 'tanpa_sopir', 1, '350000.00', 0, NULL, 'Draft', NULL, 'Temporary draft from Smart Search', '2026-04-25 22:26:43', '2026-04-25 22:26:43', NULL, 'b98f0aac-e206-4241-9a0a-9d9150d901cf', '2026-04-25 22:56:43'),
(166, NULL, 6, 1, 1, 'Guest from Smart Search', '-', NULL, NULL, '2026-04-26', '09:00:00', '2026-04-26', '09:00:00', NULL, NULL, NULL, NULL, NULL, NULL, 'tanpa_sopir', 1, '300000.00', 0, NULL, 'Draft', NULL, 'Temporary draft from Smart Search', '2026-04-25 22:26:43', '2026-04-25 22:26:43', NULL, 'bbc75487-dd5e-4143-a933-e8c3ea494ff6', '2026-04-25 22:56:43'),
(167, NULL, 4, 1, 1, 'Guest from Smart Search', '-', NULL, NULL, '2026-04-26', '09:00:00', '2026-04-26', '09:00:00', NULL, NULL, NULL, NULL, NULL, NULL, 'tanpa_sopir', 1, '300000.00', 0, NULL, 'Draft', NULL, 'Temporary draft from Smart Search', '2026-04-25 22:27:30', '2026-04-25 22:27:30', NULL, '547076a9-8da1-42cd-92e7-91449dda46a2', '2026-04-25 22:57:30'),
(169, NULL, 1, 1, 1, 'Guest from Smart Search', '-', NULL, NULL, '2026-04-26', '09:00:00', '2026-04-26', '09:00:00', NULL, NULL, NULL, NULL, NULL, NULL, 'tanpa_sopir', 1, '350000.00', 0, NULL, 'Draft', NULL, 'Temporary draft from Smart Search', '2026-04-25 22:27:30', '2026-04-25 22:27:30', NULL, '73a0a50b-7880-4cea-b46b-2087a1d7ea89', '2026-04-25 22:57:30'),
(170, NULL, 6, 1, 1, 'Guest from Smart Search', '-', NULL, NULL, '2026-04-26', '09:00:00', '2026-04-26', '09:00:00', NULL, NULL, NULL, NULL, NULL, NULL, 'tanpa_sopir', 1, '300000.00', 0, NULL, 'Draft', NULL, 'Temporary draft from Smart Search', '2026-04-25 22:27:30', '2026-04-25 22:27:30', NULL, '7e626229-a5c0-409b-aed3-8104dfd7e066', '2026-04-25 22:57:30'),
(171, NULL, 3, 2, 2, 'Guest from Smart Search', '-', NULL, NULL, '2026-04-26', '09:00:00', '2026-04-26', '09:00:00', NULL, NULL, NULL, NULL, NULL, NULL, 'tanpa_sopir', 1, '300000.00', 0, NULL, 'Draft', NULL, 'Temporary draft from Smart Search', '2026-04-25 22:27:30', '2026-04-25 22:27:30', NULL, '56796524-809a-43f2-925d-c7b595554a94', '2026-04-25 22:57:30'),
(172, NULL, 4, 1, 1, 'Guest from Smart Search', '-', NULL, NULL, '2026-04-26', '09:00:00', '2026-04-26', '09:00:00', NULL, NULL, NULL, NULL, NULL, NULL, 'tanpa_sopir', 1, '300000.00', 0, NULL, 'Draft', NULL, 'Temporary draft from Smart Search', '2026-04-25 22:27:56', '2026-04-25 22:27:56', NULL, 'd6abaf81-57c4-42d1-a89c-faaf42121b61', '2026-04-25 22:57:56'),
(174, NULL, 1, 1, 1, 'Guest from Smart Search', '-', NULL, NULL, '2026-04-26', '09:00:00', '2026-04-26', '09:00:00', NULL, NULL, NULL, NULL, NULL, NULL, 'tanpa_sopir', 1, '350000.00', 0, NULL, 'Draft', NULL, 'Temporary draft from Smart Search', '2026-04-25 22:27:56', '2026-04-25 22:27:56', NULL, 'fcd7cc05-3d52-4086-a3c4-23d2cef3ebec', '2026-04-25 22:57:56'),
(175, NULL, 6, 1, 1, 'Guest from Smart Search', '-', NULL, NULL, '2026-04-26', '09:00:00', '2026-04-26', '09:00:00', NULL, NULL, NULL, NULL, NULL, NULL, 'tanpa_sopir', 1, '300000.00', 0, NULL, 'Draft', NULL, 'Temporary draft from Smart Search', '2026-04-25 22:27:56', '2026-04-25 22:27:56', NULL, '5f119786-5f7c-443d-b74b-5656973807f4', '2026-04-25 22:57:56'),
(176, NULL, 3, 2, 2, 'Guest from Smart Search', '-', NULL, NULL, '2026-04-26', '09:00:00', '2026-04-26', '09:00:00', NULL, NULL, NULL, NULL, NULL, NULL, 'tanpa_sopir', 1, '300000.00', 0, NULL, 'Draft', NULL, 'Temporary draft from Smart Search', '2026-04-25 22:27:56', '2026-04-25 22:27:56', NULL, 'd6d2bc4a-3afc-4231-aebb-b6d71b9328ae', '2026-04-25 22:57:56'),
(177, NULL, 4, 1, 1, 'Guest from Smart Search', '-', NULL, NULL, '2026-04-26', '09:00:00', '2026-04-26', '09:00:00', NULL, NULL, NULL, NULL, NULL, NULL, 'tanpa_sopir', 1, '300000.00', 0, NULL, 'Draft', NULL, 'Temporary draft from Smart Search', '2026-04-25 22:41:47', '2026-04-25 22:41:47', NULL, '2efde50a-5ee0-45b2-b02d-f9cd30b0acbc', '2026-04-25 23:11:47'),
(179, NULL, 1, 1, 1, 'Guest from Smart Search', '-', NULL, NULL, '2026-04-26', '09:00:00', '2026-04-26', '09:00:00', NULL, NULL, NULL, NULL, NULL, NULL, 'tanpa_sopir', 1, '350000.00', 0, NULL, 'Draft', NULL, 'Temporary draft from Smart Search', '2026-04-25 22:41:47', '2026-04-25 22:41:47', NULL, '760f7dcf-97bd-46bf-9572-69f71db0a1b4', '2026-04-25 23:11:47'),
(180, NULL, 6, 1, 1, 'Guest from Smart Search', '-', NULL, NULL, '2026-04-26', '09:00:00', '2026-04-26', '09:00:00', NULL, NULL, NULL, NULL, NULL, NULL, 'tanpa_sopir', 1, '300000.00', 0, NULL, 'Draft', NULL, 'Temporary draft from Smart Search', '2026-04-25 22:41:47', '2026-04-25 22:41:47', NULL, '6dd35838-6695-4cf1-a303-79e08303017f', '2026-04-25 23:11:47'),
(181, NULL, 5, 3, 3, 'Guest from Smart Search', '-', NULL, NULL, '2026-04-26', '09:00:00', '2026-04-26', '09:00:00', NULL, NULL, NULL, NULL, NULL, NULL, 'tanpa_sopir', 1, '550000.00', 0, NULL, 'Draft', NULL, 'Temporary draft from Smart Search', '2026-04-25 22:41:47', '2026-04-25 22:41:47', NULL, '69fb1996-d230-43e9-bece-db3daef05a77', '2026-04-25 23:11:47'),
(182, NULL, 4, 1, 1, 'Guest from Smart Search', '-', NULL, NULL, '2026-04-26', '09:00:00', '2026-04-26', '09:00:00', NULL, NULL, NULL, NULL, NULL, NULL, 'tanpa_sopir', 1, '300000.00', 0, NULL, 'Draft', NULL, 'Temporary draft from Smart Search', '2026-04-25 23:14:31', '2026-04-25 23:14:31', NULL, '4a0a9ba9-d93b-4874-9e8a-426ce763ea9f', '2026-04-25 23:44:31'),
(183, NULL, 1, 1, 1, 'Guest from Smart Search', '-', NULL, NULL, '2026-04-26', '09:00:00', '2026-04-26', '09:00:00', NULL, NULL, NULL, NULL, NULL, NULL, 'tanpa_sopir', 1, '350000.00', 0, NULL, 'Draft', NULL, 'Temporary draft from Smart Search', '2026-04-25 23:14:31', '2026-04-25 23:14:31', NULL, 'dbc85deb-9b4d-496c-8cd2-6f05f26399d3', '2026-04-25 23:44:31'),
(185, NULL, 35, 1, 1, 'Guest from Smart Search', '-', NULL, NULL, '2026-04-26', '09:00:00', '2026-04-26', '09:00:00', NULL, NULL, NULL, NULL, NULL, NULL, 'tanpa_sopir', 1, '350000.00', 0, NULL, 'Draft', NULL, 'Temporary draft from Smart Search', '2026-04-26 01:51:53', '2026-04-26 01:51:53', NULL, '44da2deb-36c2-4fbc-ab54-4c9c7239a4b7', '2026-04-26 02:21:53'),
(186, NULL, 14, 3, 3, 'Guest from Smart Search', '-', NULL, NULL, '2026-04-26', '09:00:00', '2026-04-26', '09:00:00', NULL, NULL, NULL, NULL, NULL, NULL, 'tanpa_sopir', 1, '450000.00', 0, NULL, 'Draft', NULL, 'Temporary draft from Smart Search', '2026-04-26 01:51:53', '2026-04-26 01:51:53', NULL, '311d0545-ced3-4d3d-a9be-6c40a24d0533', '2026-04-26 02:21:53'),
(187, NULL, 22, 4, 4, 'Guest from Smart Search', '-', NULL, NULL, '2026-04-26', '09:00:00', '2026-04-26', '09:00:00', NULL, NULL, NULL, NULL, NULL, NULL, 'tanpa_sopir', 1, '300000.00', 0, NULL, 'Draft', NULL, 'Temporary draft from Smart Search', '2026-04-26 01:51:53', '2026-04-26 01:51:53', NULL, '5b970d0f-1d1d-4dfd-9725-56f8ccdaf9fb', '2026-04-26 02:21:53'),
(188, NULL, 26, 5, 5, 'Guest from Smart Search', '-', NULL, NULL, '2026-04-26', '09:00:00', '2026-04-26', '09:00:00', NULL, NULL, NULL, NULL, NULL, NULL, 'tanpa_sopir', 1, '300000.00', 0, NULL, 'Draft', NULL, 'Temporary draft from Smart Search', '2026-04-26 01:51:53', '2026-04-26 01:51:53', NULL, '3e5c74f8-0e73-4cd4-96c5-b91e0b72a54b', '2026-04-26 02:21:53'),
(189, NULL, 17, 3, 3, 'Guest from Smart Search', '-', NULL, NULL, '2026-04-26', '09:00:00', '2026-04-26', '09:00:00', NULL, NULL, NULL, NULL, NULL, NULL, 'tanpa_sopir', 1, '350000.00', 0, NULL, 'Draft', NULL, 'Temporary draft from Smart Search', '2026-04-26 01:51:53', '2026-04-26 01:51:53', NULL, '2d4426a4-d832-441d-8090-e47fb402722d', '2026-04-26 02:21:53'),
(190, NULL, 4, 1, 1, 'Guest from Smart Search', '-', NULL, NULL, '2026-04-26', '09:00:00', '2026-04-26', '09:00:00', NULL, NULL, NULL, NULL, NULL, NULL, 'tanpa_sopir', 1, '300000.00', 0, NULL, 'Draft', NULL, 'Temporary draft from Smart Search', '2026-04-26 01:51:53', '2026-04-26 01:51:53', NULL, 'cbd1d3b4-0183-4333-952d-0484eac197a1', '2026-04-26 02:21:53'),
(191, NULL, 13, 3, 3, 'Guest from Smart Search', '-', NULL, NULL, '2026-04-26', '09:00:00', '2026-04-26', '09:00:00', NULL, NULL, NULL, NULL, NULL, NULL, 'tanpa_sopir', 1, '300000.00', 0, NULL, 'Draft', NULL, 'Temporary draft from Smart Search', '2026-04-26 01:51:53', '2026-04-26 01:51:53', NULL, '058a3943-e73a-4e8d-9622-fcf83496bf37', '2026-04-26 02:21:53'),
(192, NULL, 27, 5, 5, 'Guest from Smart Search', '-', NULL, NULL, '2026-04-26', '09:00:00', '2026-04-26', '09:00:00', NULL, NULL, NULL, NULL, NULL, NULL, 'tanpa_sopir', 1, '300000.00', 0, NULL, 'Draft', NULL, 'Temporary draft from Smart Search', '2026-04-26 01:51:53', '2026-04-26 01:51:53', NULL, '7556de33-4234-4d0f-a31a-e2c76bcb528d', '2026-04-26 02:21:53'),
(193, NULL, 28, 5, 5, 'Guest from Smart Search', '-', NULL, NULL, '2026-04-26', '09:00:00', '2026-04-26', '09:00:00', NULL, NULL, NULL, NULL, NULL, NULL, 'tanpa_sopir', 1, '750000.00', 0, NULL, 'Draft', NULL, 'Temporary draft from Smart Search', '2026-04-26 01:51:53', '2026-04-26 01:51:53', NULL, '1194b9a4-6e17-4efb-81e7-d079b9939d89', '2026-04-26 02:21:53'),
(194, NULL, 21, 4, 4, 'Guest from Smart Search', '-', NULL, NULL, '2026-04-26', '09:00:00', '2026-04-26', '09:00:00', NULL, NULL, NULL, NULL, NULL, NULL, 'tanpa_sopir', 1, '450000.00', 0, NULL, 'Draft', NULL, 'Temporary draft from Smart Search', '2026-04-26 01:51:53', '2026-04-26 01:51:53', NULL, '8d4ad706-55e5-481e-bfed-82812325b2cd', '2026-04-26 02:21:53'),
(195, NULL, 1, 1, 1, 'Guest from Smart Search', '-', NULL, NULL, '2026-04-26', '09:00:00', '2026-04-26', '09:00:00', NULL, NULL, NULL, NULL, NULL, NULL, 'tanpa_sopir', 1, '300000.00', 0, NULL, 'Draft', NULL, 'Temporary draft from Smart Search', '2026-04-26 01:51:53', '2026-04-26 01:51:53', NULL, '9d82bd80-b533-4508-a1fb-ca03f4d83399', '2026-04-26 02:21:53'),
(196, NULL, 4, 1, 1, 'Guest from AI', '-', NULL, NULL, '2026-04-28', '09:00:00', '2026-04-28', '09:00:00', NULL, NULL, NULL, NULL, NULL, NULL, 'tanpa_sopir', 1, '300000.00', 0, NULL, 'Draft', NULL, 'Temporary draft from Chatbot. Tanggal request: 29 April - 1 Mei', '2026-04-27 20:50:30', '2026-04-27 20:50:30', NULL, '969b0fb6-dd74-4844-85d4-eb132335eb43', '2026-04-27 21:20:30'),
(197, NULL, 9, 2, 2, 'Guest from Smart Search', '-', NULL, NULL, '2026-04-29', '09:00:00', '2026-04-29', '09:00:00', NULL, NULL, NULL, NULL, NULL, NULL, 'tanpa_sopir', 1, '1900000.00', 0, NULL, 'Draft', NULL, 'Temporary draft from Smart Search', '2026-04-29 17:13:13', '2026-04-29 17:13:13', NULL, '77b08336-23ef-46ba-910b-38441bbdffa8', '2026-04-29 17:43:13'),
(198, NULL, 15, 3, 3, 'Guest from Smart Search', '-', NULL, NULL, '2026-04-29', '09:00:00', '2026-04-29', '09:00:00', NULL, NULL, NULL, NULL, NULL, NULL, 'tanpa_sopir', 1, '1600000.00', 0, NULL, 'Draft', NULL, 'Temporary draft from Smart Search', '2026-04-29 17:13:13', '2026-04-29 17:13:13', NULL, 'e2500005-653a-4bd3-b05a-2dc3eaef641e', '2026-04-29 17:43:13'),
(199, NULL, 20, 4, 4, 'Guest from Smart Search', '-', NULL, NULL, '2026-04-29', '09:00:00', '2026-04-29', '09:00:00', NULL, NULL, NULL, NULL, NULL, NULL, 'tanpa_sopir', 1, '1600000.00', 0, NULL, 'Draft', NULL, 'Temporary draft from Smart Search', '2026-04-29 17:13:13', '2026-04-29 17:13:13', NULL, '1987e77b-d589-471d-8cea-150eb61d6e28', '2026-04-29 17:43:13'),
(200, NULL, 9, 2, 2, 'Guest from Smart Search', '-', NULL, NULL, '2026-04-30', '09:00:00', '2026-04-30', '09:00:00', NULL, NULL, NULL, NULL, NULL, NULL, 'tanpa_sopir', 1, '1900000.00', 0, NULL, 'Draft', NULL, 'Temporary draft from Smart Search', '2026-04-30 11:28:47', '2026-04-30 11:28:47', NULL, 'f5b83056-003b-4ea2-aed0-1a08e369f1cf', '2026-04-30 11:58:47'),
(201, NULL, 10, 2, 2, 'Guest from Smart Search', '-', NULL, NULL, '2026-04-30', '09:00:00', '2026-04-30', '09:00:00', NULL, NULL, NULL, NULL, NULL, NULL, 'tanpa_sopir', 1, '1300000.00', 0, NULL, 'Draft', NULL, 'Temporary draft from Smart Search', '2026-04-30 11:28:47', '2026-04-30 11:28:47', NULL, 'fd91fbfb-adb2-4fba-8ff7-b11a9c05bc94', '2026-04-30 11:58:47'),
(202, NULL, 2, 2, 2, 'Guest from Smart Search', '-', NULL, NULL, '2026-04-30', '09:00:00', '2026-04-30', '09:00:00', NULL, NULL, NULL, NULL, NULL, NULL, 'tanpa_sopir', 1, '600000.00', 0, NULL, 'Draft', NULL, 'Temporary draft from Smart Search', '2026-04-30 11:28:47', '2026-04-30 11:28:47', NULL, 'a8f90107-db25-4244-9203-e6f9e5a73e79', '2026-04-30 11:58:47'),
(203, NULL, 19, 4, 4, 'Guest from Smart Search', '-', NULL, NULL, '2026-04-30', '09:00:00', '2026-04-30', '09:00:00', NULL, NULL, NULL, NULL, NULL, NULL, 'tanpa_sopir', 1, '1600000.00', 0, NULL, 'Draft', NULL, 'Temporary draft from Smart Search', '2026-04-30 11:29:47', '2026-04-30 11:29:47', NULL, '023970c0-1673-4afe-830f-87fe6eab1a57', '2026-04-30 11:59:47'),
(204, NULL, 20, 4, 4, 'Guest from Smart Search', '-', NULL, NULL, '2026-04-30', '09:00:00', '2026-04-30', '09:00:00', NULL, NULL, NULL, NULL, NULL, NULL, 'tanpa_sopir', 1, '1600000.00', 0, NULL, 'Draft', NULL, 'Temporary draft from Smart Search', '2026-04-30 11:29:47', '2026-04-30 11:29:47', NULL, 'f6ab8aa4-f2c2-4448-80d0-04bc7e260367', '2026-04-30 11:59:47'),
(205, NULL, 31, 5, 5, 'Guest from Smart Search', '-', NULL, NULL, '2026-04-30', '09:00:00', '2026-04-30', '09:00:00', NULL, NULL, NULL, NULL, NULL, NULL, 'tanpa_sopir', 1, '1700000.00', 0, NULL, 'Draft', NULL, 'Temporary draft from Smart Search', '2026-04-30 11:29:47', '2026-04-30 11:29:47', NULL, 'e86fbcbd-caaa-49b6-b067-6508f8e3a6ca', '2026-04-30 11:59:47'),
(206, NULL, 32, 5, 5, 'Guest from Smart Search', '-', NULL, NULL, '2026-04-30', '09:00:00', '2026-04-30', '09:00:00', NULL, NULL, NULL, NULL, NULL, NULL, 'tanpa_sopir', 1, '1700000.00', 0, NULL, 'Draft', NULL, 'Temporary draft from Smart Search', '2026-04-30 11:29:47', '2026-04-30 11:29:47', NULL, '4d001096-cf93-40e0-8c56-29ea5281abd1', '2026-04-30 11:59:47'),
(207, NULL, 33, 1, 1, 'Guest from AI', '-', NULL, NULL, '2026-05-02', '09:00:00', '2026-05-02', '09:00:00', NULL, NULL, NULL, NULL, NULL, NULL, 'tanpa_sopir', 1, '400000.00', 0, NULL, 'Draft', NULL, 'Temporary draft from Chatbot. Tanggal request: 2 hari', '2026-05-02 09:12:53', '2026-05-02 09:12:53', NULL, '5aeefffc-f950-405b-929d-ca6e6db7dc20', '2026-05-02 09:42:53');

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
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `no_hp` varchar(255) DEFAULT NULL,
  `tempat_lahir` varchar(255) DEFAULT NULL,
  `tanggal_lahir` date DEFAULT NULL,
  `ktp_image` varchar(255) DEFAULT NULL,
  `no_sim` varchar(255) DEFAULT NULL,
  `role` varchar(255) NOT NULL DEFAULT 'user',
  `rental_id` bigint(20) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`, `no_hp`, `tempat_lahir`, `tanggal_lahir`, `ktp_image`, `no_sim`, `role`, `rental_id`) VALUES
(1, 'Zulfardi', 'zulfardi@gmail.com', NULL, '$2y$12$eg09.Y34OmiGJmx9mcrIleyPtlMhn3aUJe.r2Be1juczxa8nfRIx6', NULL, '2026-04-05 00:24:11', '2026-04-05 00:24:11', '085375285567', NULL, NULL, NULL, NULL, 'mitra', 1),
(2, 'Eko Basuki', 'ekobasuki@gmail.com', NULL, '$2y$12$GVm9bR3SmuC7OMaW0nsSaewWOXXPUEJI4TfGfbGuc5fewstrIxs8q', NULL, '2026-04-05 00:38:01', '2026-04-05 00:38:01', '083845966102', NULL, NULL, NULL, NULL, 'mitra', 2),
(3, 'ZIKRALLAH AL HADY', 'zikrallahalhady@gmail.com', NULL, '$2y$12$1thilyufzH1BiahipKWyJu4jlei81nGFcg8O1aL/1iM.Vp4QCI.BW', 'SkUwaQHVD0XvCOvPPyhFDmbSgpfXzcFTFTGwPKVMDAe16pootQQAKFpG5Msv', '2026-04-05 00:40:46', '2026-04-05 00:40:46', '083896517385', NULL, NULL, NULL, NULL, 'customer', NULL),
(4, 'Muhammad Annur', 'annur@gmail.com', NULL, '$2y$12$qfHZNd.97VPaggSu/A2dDeQ6momChUdtXq.4aPGpVPqicEdx9pjdy', NULL, '2026-04-07 09:26:51', '2026-04-07 09:26:51', '085278820591', NULL, NULL, NULL, NULL, 'mitra', 3),
(5, 'Admin Sistem', 'admin@gmail.com', NULL, '$2y$12$27AzU9ooZU0asJH8ago3NOQxcHRHIq5fQBK0uDUtWOHimK1DmsArG', NULL, '2026-04-09 23:51:02', '2026-04-09 23:51:02', NULL, NULL, NULL, NULL, NULL, 'admin', NULL),
(6, 'Widya Sari', 'widyasari@gmail.com', NULL, '$2y$12$HJYblGVmlBeVOvRzeLx4HeMBs.Z/QruLCLZmW8jOfwm7VVS5GhKCO', NULL, '2026-04-10 00:16:31', '2026-04-10 00:16:31', '081374328872', NULL, NULL, NULL, NULL, 'mitra', 4),
(7, 'Wan Fitri Anggria', 'wanfitri@gmail.com', NULL, '$2y$12$kGsad.miiYrstoC2hAmQ/usPy8cdppP4wpWt2bkbLbXKl4rIBfpYm', NULL, '2026-04-10 00:42:03', '2026-04-10 00:42:03', '081277864411', NULL, NULL, NULL, NULL, 'mitra', 5),
(8, 'IBNU', 'xiomyyy929@gmail.com', NULL, '$2y$12$GIWIh4lOfd9VrMeU.a9cJO4ZhbSlsjp6UAhkMIkMZNA9pHSNVukHq', NULL, '2026-04-10 02:09:43', '2026-04-10 02:09:43', '085314574890', NULL, NULL, NULL, NULL, 'customer', NULL),
(9, 'Julian olga', 'julianoolgano@gmail.com', NULL, '$2y$12$XYKwp/xdjzQS3vjo7M37tOL7k1p0bbTgbom39vDIbythks9o0/8Zu', NULL, '2026-04-10 06:13:12', '2026-04-10 06:13:12', '082278824170', NULL, NULL, NULL, NULL, 'customer', NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `branches`
--
ALTER TABLE `branches`
  ADD PRIMARY KEY (`id`),
  ADD KEY `branches_rental_id_foreign` (`rental_id`);

--
-- Indexes for table `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `cars`
--
ALTER TABLE `cars`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `cars_slug_unique` (`slug`);

--
-- Indexes for table `chat_logs`
--
ALTER TABLE `chat_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `chat_logs_user_id_foreign` (`user_id`);

--
-- Indexes for table `customers`
--
ALTER TABLE `customers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `customers_email_unique` (`email`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

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
-- Indexes for table `mobils`
--
ALTER TABLE `mobils`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `mobils_no_plat_unique` (`no_plat`),
  ADD KEY `mobils_rental_id_foreign` (`rental_id`),
  ADD KEY `mobils_branch_id_foreign` (`branch_id`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `rentals`
--
ALTER TABLE `rentals`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `rentals_slug_unique` (`slug`),
  ADD KEY `rentals_user_id_foreign` (`user_id`);

--
-- Indexes for table `reservations`
--
ALTER TABLE `reservations`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `reservations_kode_booking_unique` (`kode_booking`),
  ADD KEY `reservations_customer_id_foreign` (`customer_id`),
  ADD KEY `reservations_car_id_foreign` (`car_id`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `tentang_kamis`
--
ALTER TABLE `tentang_kamis`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `transaksis`
--
ALTER TABLE `transaksis`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `transaksis_booking_token_unique` (`booking_token`),
  ADD KEY `transaksis_mobil_id_foreign` (`mobil_id`),
  ADD KEY `transaksis_rental_id_foreign` (`rental_id`),
  ADD KEY `transaksis_branch_id_foreign` (`branch_id`),
  ADD KEY `transaksis_user_id_foreign` (`user_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`),
  ADD KEY `users_rental_id_foreign` (`rental_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `branches`
--
ALTER TABLE `branches`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `cars`
--
ALTER TABLE `cars`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `chat_logs`
--
ALTER TABLE `chat_logs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;

--
-- AUTO_INCREMENT for table `customers`
--
ALTER TABLE `customers`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `mobils`
--
ALTER TABLE `mobils`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT for table `rentals`
--
ALTER TABLE `rentals`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `reservations`
--
ALTER TABLE `reservations`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tentang_kamis`
--
ALTER TABLE `tentang_kamis`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `transaksis`
--
ALTER TABLE `transaksis`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=208;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `branches`
--
ALTER TABLE `branches`
  ADD CONSTRAINT `branches_rental_id_foreign` FOREIGN KEY (`rental_id`) REFERENCES `rentals` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `chat_logs`
--
ALTER TABLE `chat_logs`
  ADD CONSTRAINT `chat_logs_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `mobils`
--
ALTER TABLE `mobils`
  ADD CONSTRAINT `mobils_branch_id_foreign` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `mobils_rental_id_foreign` FOREIGN KEY (`rental_id`) REFERENCES `rentals` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `rentals`
--
ALTER TABLE `rentals`
  ADD CONSTRAINT `rentals_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `reservations`
--
ALTER TABLE `reservations`
  ADD CONSTRAINT `reservations_car_id_foreign` FOREIGN KEY (`car_id`) REFERENCES `cars` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `reservations_customer_id_foreign` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `transaksis`
--
ALTER TABLE `transaksis`
  ADD CONSTRAINT `transaksis_branch_id_foreign` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `transaksis_mobil_id_foreign` FOREIGN KEY (`mobil_id`) REFERENCES `mobils` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `transaksis_rental_id_foreign` FOREIGN KEY (`rental_id`) REFERENCES `rentals` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `transaksis_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_rental_id_foreign` FOREIGN KEY (`rental_id`) REFERENCES `rentals` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
