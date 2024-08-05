-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jul 22, 2024 at 02:42 PM
-- Server version: 8.0.30
-- PHP Version: 8.1.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `laundryku`
--

-- --------------------------------------------------------

--
-- Table structure for table `cucian`
--

CREATE TABLE `layanan` (
  `id_layanan` int NOT NULL,
  `nama_layanan` varchar(50) NOT NULL,
  `harga_layanan` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

CREATE TABLE `cucian` (
  `id_cucian` int NOT NULL,
  `nama_konsumen` varchar(15) NOT NULL,
  `nohp_konsumen` varchar(13) NOT NULL,
  `berat_cucian` int NOT NULL,
  `status` tinyint(1) NOT NULL,
  `tanggal_cuci` date NOT NULL,
  `id_layanan` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `cucian`
--

INSERT INTO `cucian` (`id_cucian`, `nama_konsumen`, `nohp_konsumen`, `berat_cucian`, `status`, `tanggal_cuci`, `id_layanan`) VALUES
(15, 'ada', '22212', 2, 1, '2024-07-20', 40),
(17, 'ewqpoweiqo', '0321323', 2, 1, '2024-07-20', 40),
(19, 'dejan', '3321', 2, 0, '2024-07-20', 40),
(20, 'me', '081234567890', 2, 0, '2024-07-22', 43);

-- --------------------------------------------------------

--
-- Table structure for table `layanan`
--



--
-- Dumping data for table `layanan`
--

INSERT INTO `layanan` (`id_layanan`, `nama_layanan`, `harga_layanan`) VALUES
(40, 'Cuci Cepat ⚡', 2000),
(41, 'Cuci Basah 🥵', 2000),
(42, 'Paket Cukimak', 3000),
(43, 'Honda Beat 2020', 25000000);

-- --------------------------------------------------------

--
-- Table structure for table `pembayaran`

CREATE TABLE `pengguna` (
  `id_pengguna` int NOT NULL,
  `nama_pengguna` varchar(15) NOT NULL,
  `peran` varchar(15) NOT NULL,
  `kata_sandi` varchar(60) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
--

CREATE TABLE `pembayaran` (
  `id_pembayaran` int NOT NULL,
  `tanggal_pembayaran` date NOT NULL,
  `total_harga` int NOT NULL,
  `id_pengguna` int NOT NULL,
  `id_cucian` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `pembayaran`
--

INSERT INTO `pembayaran` (`id_pembayaran`, `tanggal_pembayaran`, `total_harga`, `id_pengguna`, `id_cucian`) VALUES
(1, '2024-07-17', 31231, 13, 15);

-- --------------------------------------------------------

--
-- Table structure for table `pengguna`
--



--
-- Dumping data for table `pengguna`
--

INSERT INTO `pengguna` (`id_pengguna`, `nama_pengguna`, `peran`, `kata_sandi`) VALUES
(13, 'admin', 'manajer', '$2y$10$X06aDTgPTGKKDGDv4k3qT.L8sdKWuOj0mKWTBgXKnRrQKn8kzfMzm'),
(14, 'kasir', 'kasir', '$2y$10$8u5y2TSrzLQWuX0xzDwbH.wlHSLRIHeCbVYrIrTNRxWPQ9AyzwnsS'),
(16, 'dejan', 'kasir', '$2y$10$5oEP59.QsFtIcSZ3JCgwEeIP149RGscCsbFe953qcPLWf5.PMX4CW'),
(17, 'izan', 'manajer', '$2y$10$6P0NpopKJJqStyWZO.S9weq0OnTORZMV1eKdXuiFChNwzaJfzKHOW');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cucian`
--
ALTER TABLE `cucian`
  ADD PRIMARY KEY (`id_cucian`),
  ADD KEY `id_layanan` (`id_layanan`);

--
-- Indexes for table `layanan`
--
ALTER TABLE `layanan`
  ADD PRIMARY KEY (`id_layanan`);

--
-- Indexes for table `pembayaran`
--
ALTER TABLE `pembayaran`
  ADD PRIMARY KEY (`id_pembayaran`),
  ADD KEY `id_cucian` (`id_cucian`),
  ADD KEY `id_pengguna` (`id_pengguna`);

--
-- Indexes for table `pengguna`
--
ALTER TABLE `pengguna`
  ADD PRIMARY KEY (`id_pengguna`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `cucian`
--
ALTER TABLE `cucian`
  MODIFY `id_cucian` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `layanan`
--
ALTER TABLE `layanan`
  MODIFY `id_layanan` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=44;

--
-- AUTO_INCREMENT for table `pembayaran`
--
ALTER TABLE `pembayaran`
  MODIFY `id_pembayaran` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `pengguna`
--
ALTER TABLE `pengguna`
  MODIFY `id_pengguna` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `cucian`
--
ALTER TABLE `cucian`
  ADD CONSTRAINT `id_layanan` FOREIGN KEY (`id_layanan`) REFERENCES `layanan` (`id_layanan`) ON DELETE RESTRICT ON UPDATE CASCADE;

--
-- Constraints for table `pembayaran`
--
ALTER TABLE `pembayaran`
  ADD CONSTRAINT `id_cucian` FOREIGN KEY (`id_cucian`) REFERENCES `cucian` (`id_cucian`),
  ADD CONSTRAINT `id_pengguna` FOREIGN KEY (`id_pengguna`) REFERENCES `pengguna` (`id_pengguna`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
