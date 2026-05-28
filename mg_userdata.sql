-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 28, 2026 at 10:35 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `cursusphp`
--

-- --------------------------------------------------------

--
-- Table structure for table `mg_userdata`
--

CREATE TABLE `mg_userdata` (
  `id` int(11) NOT NULL,
  `spendEnergy_total` int(11) NOT NULL,
  `energy_lt` datetime NOT NULL,
  `money` int(11) NOT NULL DEFAULT 0,
  `damage_outp` decimal(10,2) NOT NULL DEFAULT 0.00
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `mg_userdata`
--

INSERT INTO `mg_userdata` (`id`, `spendEnergy_total`, `energy_lt`, `money`, `damage_outp`) VALUES
(1, 3900, '2026-05-28 10:26:43', 0, 42.95),
(2, 2505, '2026-05-28 09:45:48', 0, 60.00);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `mg_userdata`
--
ALTER TABLE `mg_userdata`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `mg_userdata`
--
ALTER TABLE `mg_userdata`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
