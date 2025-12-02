-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Waktu pembuatan: 30 Nov 2025 pada 06.52
-- Versi server: 8.0.30
-- Versi PHP: 8.3.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `bioskop_test`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `cabangs`
--

CREATE TABLE `cabangs` (
  `id_cabang` bigint UNSIGNED NOT NULL,
  `nama_cabang` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `alamat` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `kode_cabang_kota` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `detail_transaksis`
--

CREATE TABLE `detail_transaksis` (
  `id_detail_transaksi` bigint UNSIGNED NOT NULL,
  `id_transaksi` bigint UNSIGNED DEFAULT NULL,
  `id_jadwal` bigint UNSIGNED DEFAULT NULL,
  `seat_id` bigint UNSIGNED DEFAULT NULL,
  `id_studio` bigint UNSIGNED DEFAULT NULL,
  `harga` int DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `failed_jobs`
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
-- Struktur dari tabel `films`
--

CREATE TABLE `films` (
  `id_film` bigint UNSIGNED NOT NULL,
  `judul` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sinopsis` text COLLATE utf8mb4_unicode_ci,
  `durasi_menit` int DEFAULT NULL,
  `rating_usia` varchar(5) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `harga_film` int DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `jadwal_tayangs`
--

CREATE TABLE `jadwal_tayangs` (
  `id_jadwal` bigint UNSIGNED NOT NULL,
  `id_studio` bigint UNSIGNED DEFAULT NULL,
  `id_film` bigint UNSIGNED DEFAULT NULL,
  `waktu_mulai` time DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `jobs`
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
-- Struktur dari tabel `job_batches`
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
-- Struktur dari tabel `log_transaksis`
--

CREATE TABLE `log_transaksis` (
  `id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `id_cabang` bigint UNSIGNED DEFAULT NULL,
  `id_pelanggan` bigint UNSIGNED DEFAULT NULL,
  `id_studio` bigint UNSIGNED DEFAULT NULL,
  `id_film` bigint UNSIGNED DEFAULT NULL,
  `id_jadwal` bigint UNSIGNED DEFAULT NULL,
  `seat_id` bigint UNSIGNED DEFAULT NULL,
  `id_transaksi` bigint UNSIGNED DEFAULT NULL,
  `id_detail_transaksi` bigint UNSIGNED DEFAULT NULL,
  `total_bayar` int DEFAULT NULL,
  `waktu_transaksi` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `migrations`
--

CREATE TABLE `migrations` (
  `id` int UNSIGNED NOT NULL,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(25, '0001_01_01_000000_create_users_table', 1),
(26, '0001_01_01_000001_create_cache_table', 1),
(27, '0001_01_01_000002_create_jobs_table', 1),
(28, '2025_11_29_000001_create_cabangs_table', 1),
(29, '2025_11_29_000002_create_films_table', 1),
(30, '2025_11_29_000003_create_pelanggans_table', 1),
(31, '2025_11_29_000004_create_studios_table', 1),
(32, '2025_11_29_000005_create_seat_maps_table', 1),
(33, '2025_11_29_000006_create_jadwal_tayangs_table', 1),
(34, '2025_11_29_000007_create_transaksis_table', 1),
(35, '2025_11_29_000008_create_detail_transaksis_table', 1),
(36, '2025_11_29_140533_create_log_transaksis_table', 1);

-- --------------------------------------------------------

--
-- Struktur dari tabel `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `pelanggans`
--

CREATE TABLE `pelanggans` (
  `id_pelanggan` bigint UNSIGNED NOT NULL,
  `nama` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `telp` varchar(15) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `seat_maps`
--

CREATE TABLE `seat_maps` (
  `seat_id` bigint UNSIGNED NOT NULL,
  `id_studio` bigint UNSIGNED DEFAULT NULL,
  `seat_code` varchar(5) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `no_baris` varchar(4) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `no_kolom` varchar(4) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `studios`
--

CREATE TABLE `studios` (
  `id_studio` bigint UNSIGNED NOT NULL,
  `id_cabang` bigint UNSIGNED DEFAULT NULL,
  `nama_studio` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tipe_studio` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `kapasitas` int DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `transaksis`
--

CREATE TABLE `transaksis` (
  `id_transaksi` bigint UNSIGNED NOT NULL,
  `id_pelanggan` bigint UNSIGNED DEFAULT NULL,
  `id_cabang` bigint UNSIGNED DEFAULT NULL,
  `waktu_transaksi` timestamp NULL DEFAULT NULL,
  `metode_pembayaran` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `total_bayar` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `users`
--

CREATE TABLE `users` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `role` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'kasir',
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `cabangs`
--
ALTER TABLE `cabangs`
  ADD PRIMARY KEY (`id_cabang`);

--
-- Indeks untuk tabel `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`);

--
-- Indeks untuk tabel `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`);

--
-- Indeks untuk tabel `detail_transaksis`
--
ALTER TABLE `detail_transaksis`
  ADD PRIMARY KEY (`id_detail_transaksi`),
  ADD KEY `detail_transaksis_id_transaksi_foreign` (`id_transaksi`),
  ADD KEY `detail_transaksis_id_jadwal_foreign` (`id_jadwal`),
  ADD KEY `detail_transaksis_seat_id_foreign` (`seat_id`),
  ADD KEY `detail_transaksis_id_studio_foreign` (`id_studio`);

--
-- Indeks untuk tabel `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indeks untuk tabel `films`
--
ALTER TABLE `films`
  ADD PRIMARY KEY (`id_film`);

--
-- Indeks untuk tabel `jadwal_tayangs`
--
ALTER TABLE `jadwal_tayangs`
  ADD PRIMARY KEY (`id_jadwal`),
  ADD KEY `jadwal_tayangs_id_studio_foreign` (`id_studio`),
  ADD KEY `jadwal_tayangs_id_film_foreign` (`id_film`);

--
-- Indeks untuk tabel `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Indeks untuk tabel `job_batches`
--
ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `log_transaksis`
--
ALTER TABLE `log_transaksis`
  ADD PRIMARY KEY (`id`),
  ADD KEY `log_transaksis_id_cabang_foreign` (`id_cabang`),
  ADD KEY `log_transaksis_id_pelanggan_foreign` (`id_pelanggan`),
  ADD KEY `log_transaksis_id_studio_foreign` (`id_studio`),
  ADD KEY `log_transaksis_id_film_foreign` (`id_film`),
  ADD KEY `log_transaksis_id_jadwal_foreign` (`id_jadwal`),
  ADD KEY `log_transaksis_seat_id_foreign` (`seat_id`),
  ADD KEY `log_transaksis_id_transaksi_foreign` (`id_transaksi`),
  ADD KEY `log_transaksis_id_detail_transaksi_foreign` (`id_detail_transaksi`);

--
-- Indeks untuk tabel `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indeks untuk tabel `pelanggans`
--
ALTER TABLE `pelanggans`
  ADD PRIMARY KEY (`id_pelanggan`);

--
-- Indeks untuk tabel `seat_maps`
--
ALTER TABLE `seat_maps`
  ADD PRIMARY KEY (`seat_id`),
  ADD KEY `seat_maps_id_studio_foreign` (`id_studio`);

--
-- Indeks untuk tabel `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indeks untuk tabel `studios`
--
ALTER TABLE `studios`
  ADD PRIMARY KEY (`id_studio`),
  ADD KEY `studios_id_cabang_foreign` (`id_cabang`);

--
-- Indeks untuk tabel `transaksis`
--
ALTER TABLE `transaksis`
  ADD PRIMARY KEY (`id_transaksi`),
  ADD KEY `transaksis_id_pelanggan_foreign` (`id_pelanggan`),
  ADD KEY `transaksis_id_cabang_foreign` (`id_cabang`);

--
-- Indeks untuk tabel `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `cabangs`
--
ALTER TABLE `cabangs`
  MODIFY `id_cabang` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `detail_transaksis`
--
ALTER TABLE `detail_transaksis`
  MODIFY `id_detail_transaksi` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `films`
--
ALTER TABLE `films`
  MODIFY `id_film` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `jadwal_tayangs`
--
ALTER TABLE `jadwal_tayangs`
  MODIFY `id_jadwal` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `log_transaksis`
--
ALTER TABLE `log_transaksis`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT untuk tabel `pelanggans`
--
ALTER TABLE `pelanggans`
  MODIFY `id_pelanggan` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `seat_maps`
--
ALTER TABLE `seat_maps`
  MODIFY `seat_id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `studios`
--
ALTER TABLE `studios`
  MODIFY `id_studio` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `transaksis`
--
ALTER TABLE `transaksis`
  MODIFY `id_transaksi` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `detail_transaksis`
--
ALTER TABLE `detail_transaksis`
  ADD CONSTRAINT `detail_transaksis_id_jadwal_foreign` FOREIGN KEY (`id_jadwal`) REFERENCES `jadwal_tayangs` (`id_jadwal`),
  ADD CONSTRAINT `detail_transaksis_id_studio_foreign` FOREIGN KEY (`id_studio`) REFERENCES `studios` (`id_studio`),
  ADD CONSTRAINT `detail_transaksis_id_transaksi_foreign` FOREIGN KEY (`id_transaksi`) REFERENCES `transaksis` (`id_transaksi`) ON DELETE CASCADE,
  ADD CONSTRAINT `detail_transaksis_seat_id_foreign` FOREIGN KEY (`seat_id`) REFERENCES `seat_maps` (`seat_id`);

--
-- Ketidakleluasaan untuk tabel `jadwal_tayangs`
--
ALTER TABLE `jadwal_tayangs`
  ADD CONSTRAINT `jadwal_tayangs_id_film_foreign` FOREIGN KEY (`id_film`) REFERENCES `films` (`id_film`) ON DELETE CASCADE,
  ADD CONSTRAINT `jadwal_tayangs_id_studio_foreign` FOREIGN KEY (`id_studio`) REFERENCES `studios` (`id_studio`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `log_transaksis`
--
ALTER TABLE `log_transaksis`
  ADD CONSTRAINT `log_transaksis_id_cabang_foreign` FOREIGN KEY (`id_cabang`) REFERENCES `cabangs` (`id_cabang`),
  ADD CONSTRAINT `log_transaksis_id_detail_transaksi_foreign` FOREIGN KEY (`id_detail_transaksi`) REFERENCES `detail_transaksis` (`id_detail_transaksi`),
  ADD CONSTRAINT `log_transaksis_id_film_foreign` FOREIGN KEY (`id_film`) REFERENCES `films` (`id_film`),
  ADD CONSTRAINT `log_transaksis_id_jadwal_foreign` FOREIGN KEY (`id_jadwal`) REFERENCES `jadwal_tayangs` (`id_jadwal`),
  ADD CONSTRAINT `log_transaksis_id_pelanggan_foreign` FOREIGN KEY (`id_pelanggan`) REFERENCES `pelanggans` (`id_pelanggan`),
  ADD CONSTRAINT `log_transaksis_id_studio_foreign` FOREIGN KEY (`id_studio`) REFERENCES `studios` (`id_studio`),
  ADD CONSTRAINT `log_transaksis_id_transaksi_foreign` FOREIGN KEY (`id_transaksi`) REFERENCES `transaksis` (`id_transaksi`),
  ADD CONSTRAINT `log_transaksis_seat_id_foreign` FOREIGN KEY (`seat_id`) REFERENCES `seat_maps` (`seat_id`);

--
-- Ketidakleluasaan untuk tabel `seat_maps`
--
ALTER TABLE `seat_maps`
  ADD CONSTRAINT `seat_maps_id_studio_foreign` FOREIGN KEY (`id_studio`) REFERENCES `studios` (`id_studio`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `studios`
--
ALTER TABLE `studios`
  ADD CONSTRAINT `studios_id_cabang_foreign` FOREIGN KEY (`id_cabang`) REFERENCES `cabangs` (`id_cabang`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `transaksis`
--
ALTER TABLE `transaksis`
  ADD CONSTRAINT `transaksis_id_cabang_foreign` FOREIGN KEY (`id_cabang`) REFERENCES `cabangs` (`id_cabang`) ON DELETE CASCADE,
  ADD CONSTRAINT `transaksis_id_pelanggan_foreign` FOREIGN KEY (`id_pelanggan`) REFERENCES `pelanggans` (`id_pelanggan`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
