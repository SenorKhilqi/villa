-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 01 Des 2024 pada 13.21
-- Versi server: 10.4.32-MariaDB
-- Versi PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `villa_panjalu`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `bookings`
--

CREATE TABLE `bookings` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `villa_id` int(11) DEFAULT NULL,
  `booking_date` date DEFAULT NULL,
  `payment_status` enum('pending','completed') DEFAULT 'pending',
  `payment_method` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `bookings`
--

INSERT INTO `bookings` (`id`, `user_id`, `villa_id`, `booking_date`, `payment_status`, `payment_method`) VALUES
(2, 5, 4, '2024-11-15', 'completed', 'Gopay'),
(3, 5, 3, '2024-11-09', 'completed', 'Dana'),
(4, 5, 3, '2024-12-01', 'pending', 'Dana'),
(5, 5, 3, '2024-11-21', 'completed', 'OVO'),
(6, 5, 3, '2024-11-27', 'pending', 'Dana'),
(7, 5, 3, '2024-11-28', 'pending', 'Dana'),
(8, 5, 4, '2025-01-25', 'pending', 'Dana'),
(9, 5, 3, '2025-02-01', 'pending', 'Dana'),
(10, 5, 3, '2024-11-30', 'pending', 'dana'),
(11, 5, 3, '2024-11-25', 'pending', 'dana'),
(12, 5, 3, '2024-11-24', 'completed', 'dana'),
(13, 13, 3, '2024-11-06', 'completed', 'dana'),
(14, 13, 3, '2025-01-07', 'pending', 'dana'),
(15, 13, 3, '2024-11-04', 'pending', 'gopay'),
(16, 13, 3, '2024-11-07', 'pending', 'dana'),
(17, 5, 3, '2024-11-12', 'pending', 'dana'),
(18, 5, 3, '2025-04-10', 'pending', 'dana'),
(19, 5, 3, '2025-01-02', 'pending', 'dana'),
(20, 5, 3, '2025-01-03', 'pending', 'dana'),
(21, 5, 3, '2025-02-08', 'pending', 'dana'),
(22, 5, 3, '2025-02-02', 'pending', 'dana'),
(23, 5, 3, '2025-04-11', 'pending', 'dana'),
(24, 5, 3, '2025-04-12', 'pending', 'dana'),
(25, 5, 3, '2025-04-18', 'pending', 'dana'),
(26, 5, 3, '2025-01-11', 'pending', 'dana');

-- --------------------------------------------------------

--
-- Struktur dari tabel `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('user','admin') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `role`) VALUES
(3, 'maula_13', '$2y$10$yvFrM5LW6nS8Qg6ifKW06OwEjPtzT0uYhO7H8e41Yb38X8VE7cada', 'admin'),
(5, 'khilqi', '$2y$10$AKjQA.LwcHW4wGnCxIj.G.qPrlh7xdTeMEbhapId.gSAhZbUkDUta', 'user'),
(6, 'senor', '$2y$10$GhAd5ZWLbYhVfqjboyRZMO1w6Vkoz5xqHtMIJa2QPEUjYuMaGPDu2', 'user'),
(7, 'admin', '$2y$10$4IHTp8JtXvkqqf2U9DYIN.O4ZMVj7vY7eQYm3sa/9e0nSVXaTtvuO', 'admin'),
(10, 'leli', '$2y$10$kPybblarkZVwwoW0CResg.1rtVQ11QaJp3TGx.6corIkdIzIm83Ya', 'admin'),
(11, 'sasa', '$2y$10$xPaK0rcUjuzwp82Ebmeif.SxO7NQxWLb/F.A7tbpK2DL.biOhojUG', 'user'),
(12, 'desti', '$2y$10$vA3AohVpL/TuxjSLfKHqSOJ8DVszdh8ugiCYPa2ItlM.GDxz0NHGq', 'user'),
(13, 'lely', '$2y$10$.hVAt4AvsSgyMUP/6lzeue/8knU/FLIjDQpdI.dQvWp4Dgt..VyHC', 'user'),
(16, 'laa', '$2y$10$RNsc3L17c4A/I8BtgTVDj.1/v7j28jO27yBUD3fWXU2M4Al.KljP.', 'user');

-- --------------------------------------------------------

--
-- Struktur dari tabel `villas`
--

CREATE TABLE `villas` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `price` decimal(10,2) NOT NULL DEFAULT 0.00,
  `image` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `villas`
--

INSERT INTO `villas` (`id`, `name`, `price`, `image`) VALUES
(3, 'Villa Kayu Hujung', 2000000.00, 'villa1.jpg'),
(4, 'Villa Bata Dukuh', 2000000.00, 'villa2.jpg');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `bookings`
--
ALTER TABLE `bookings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `villa_id` (`villa_id`);

--
-- Indeks untuk tabel `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indeks untuk tabel `villas`
--
ALTER TABLE `villas`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `bookings`
--
ALTER TABLE `bookings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT untuk tabel `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT untuk tabel `villas`
--
ALTER TABLE `villas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `bookings`
--
ALTER TABLE `bookings`
  ADD CONSTRAINT `bookings_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `bookings_ibfk_2` FOREIGN KEY (`villa_id`) REFERENCES `villas` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
