-- phpMyAdmin SQL Dump
-- version 5.0.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Feb 16, 2025 at 12:35 PM
-- Server version: 10.4.11-MariaDB
-- PHP Version: 7.4.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `rehab`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id`, `username`, `password`) VALUES
(1, 'admin', '21232f297a57a5a743894a0e4a801fc3');

-- --------------------------------------------------------

--
-- Table structure for table `appointments`
--

CREATE TABLE `appointments` (
  `id` int(11) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `tgl_lahir` date NOT NULL,
  `nik` varchar(50) NOT NULL,
  `no_hp` varchar(13) NOT NULL,
  `dokter` varchar(100) NOT NULL,
  `tgl_kunjungan` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `appointments`
--

INSERT INTO `appointments` (`id`, `nama`, `tgl_lahir`, `nik`, `no_hp`, `dokter`, `tgl_kunjungan`) VALUES
(1, 'a', '2025-02-06', '1232134', '', 'dr. Maulana Kurniawan, Sp. KFR', '2025-02-18'),
(2, 'b', '2025-02-04', '34', '', 'dr. Maulana Kurniawan, Sp. KFR', '2025-02-19'),
(3, 'c', '2025-01-28', '234', '', 'dr. Agus Prasetyo, Sp. KFR', '2025-02-18'),
(4, 'aaa', '2025-02-06', '45', '', 'dr. Maulana Kurniawan, Sp. KFR', '2025-02-18'),
(5, '555', '2025-02-20', '543', '', 'dr. Maulana Kurniawan, Sp. KFR', '2025-02-18'),
(6, 'aaaa', '2025-02-04', '1232134', '', 'dr. Agus Prasetyo, Sp. KFR', '2025-02-27'),
(7, 'aaaa', '2025-02-04', '1232134', '', 'dr. Agus Prasetyo, Sp. KFR', '2025-02-28'),
(8, 'aaaa', '2025-02-04', '1232134', '', 'dr. Agus Prasetyo, Sp. KFR', '2025-02-28'),
(9, 'aaaa', '2025-02-04', '1232134', '', 'dr. Agus Prasetyo, Sp. KFR', '2025-02-28'),
(10, 'aaaa', '2025-02-04', '1232134', '', 'dr. Agus Prasetyo, Sp. KFR', '2025-02-28'),
(11, 'aaaa', '2025-02-12', '34', '46345', 'dr. Maulana Kurniawan, Sp. KFR', '2025-02-26'),
(12, 'b', '2025-01-28', '34', '46345', 'dr. Agus Prasetyo, Sp. KFR', '2025-02-21'),
(13, 'b', '2025-01-28', '34', '46345', 'dr. Agus Prasetyo, Sp. KFR', '2025-02-25'),
(14, 'aaaa', '2025-02-11', '4654', '6456', 'dr. Maulana Kurniawan, Sp. KFR', '2025-02-21'),
(15, 'aaaas', '2025-02-11', '4654', '6456', 'dr. Maulana Kurniawan, Sp. KFR', '2025-02-19'),
(16, 'aaaas', '2025-02-11', '4654', '6456', 'dr. Agus Prasetyo, Sp. KFR', '2025-02-19'),
(17, 'reg', '2025-02-05', '23432', '423423', 'dr. Agus Prasetyo, Sp. KFR', '2025-02-20'),
(18, 'reg', '2025-02-05', '23432', '423423', 'dr. Agus Prasetyo, Sp. KFR', '2025-02-20'),
(19, 'reg', '2025-02-05', '23432', '423423', 'dr. Agus Prasetyo, Sp. KFR', '2025-02-20'),
(20, 'reg', '2025-02-05', '23432', '423423', 'dr. Agus Prasetyo, Sp. KFR', '2025-02-25'),
(21, 'reg', '2025-02-05', '23432', '423423', 'dr. Agus Prasetyo, Sp. KFR', '2025-02-20'),
(22, 'reg', '2025-02-05', '23432', '423423', 'dr. Agus Prasetyo, Sp. KFR', '2025-02-21'),
(23, 'Gf', '2025-02-02', '12', '12', 'dr. Maulana Kurniawan, Sp. KFR', '2025-02-19'),
(24, '46', '2025-01-28', '3423', '24234', 'dr. Maulana Kurniawan, Sp. KFR', '2025-02-28'),
(25, '46', '2025-01-28', '3423', '24234', 'dr. Agus Prasetyo, Sp. KFR', '2025-02-28'),
(26, 'edrh', '2025-01-28', '464', '6346', 'dr. Agus Prasetyo, Sp. KFR', '2025-02-28'),
(27, 'edrh', '2025-01-28', '464', '6346', 'dr. Agus Prasetyo, Sp. KFR', '2025-02-27'),
(28, 'edrh', '2025-01-28', '464', '6346', 'dr. Agus Prasetyo, Sp. KFR', '2025-02-27'),
(29, 'reg', '2025-01-27', '35', '435', 'dr. Agus Prasetyo, Sp. KFR', '2025-03-01'),
(30, 'reg', '2025-01-27', '35', '435', 'dr. Agus Prasetyo, Sp. KFR', '2025-03-01');

-- --------------------------------------------------------

--
-- Table structure for table `dokter_kuota`
--

CREATE TABLE `dokter_kuota` (
  `id` int(11) NOT NULL,
  `dokter` varchar(100) NOT NULL,
  `kuota` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `dokter_kuota`
--

INSERT INTO `dokter_kuota` (`id`, `dokter`, `kuota`) VALUES
(1, 'dr. Agus Prasetyo, Sp. KFR', 6),
(2, 'dr. Maulana Kurniawan, Sp. KFR', 4);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `appointments`
--
ALTER TABLE `appointments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `dokter_kuota`
--
ALTER TABLE `dokter_kuota`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `dokter` (`dokter`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `appointments`
--
ALTER TABLE `appointments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `dokter_kuota`
--
ALTER TABLE `dokter_kuota`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
