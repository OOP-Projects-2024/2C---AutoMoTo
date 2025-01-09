-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 17, 2024 at 03:01 AM
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
-- Database: `auto_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `carparts`
--

CREATE TABLE `carparts` (
  `id` int(11) NOT NULL,
  `parts` varchar(255) NOT NULL,
  `price` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `partsdescription` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `carparts`
--

INSERT INTO `carparts` (`id`, `parts`, `price`, `quantity`, `partsdescription`, `created_at`, `updated_at`) VALUES
(2, 'Engine', 10000, 25, 'The engine is the heart of the car, converting fuel into mechanical energy to power the vehicle.', '2024-12-17 01:59:57', '2024-12-17 01:59:57'),
(3, 'Brake Pads', 500, 70, 'Brake pads are friction materials that press against the brake rotor to slow down or stop the vehicle.', '2024-12-17 02:00:36', '2024-12-17 02:00:36'),
(4, 'Suspension', 999, 15, 'The suspension system absorbs shocks and impacts, ensuring a smooth ride and better handling by connecting the vehicle\'s body to the wheels.', '2024-12-17 02:01:04', '2024-12-17 02:01:04');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `token`, `created_at`) VALUES
(4, 'jeiloHarvey', '$2y$10$nFGA1ajGXEhnN0aDTC6dIezcmszT70Iwr2Fq7zwtGuel9MuGxMzmK', 'YTllN2Q0N2QyNjBlMmY2YjQ2ODhiY2ExMjcwYjU0YTMxMDg0NTNiMzFiNDI2YTI2MTBkZjliY2YxMDIwNTc4NA==', '2024-12-16 14:19:11'),
(5, 'carlChes', '$2y$10$Ulkwyb8RTkj4fDS/xhtaZePKhUurbThB4FVAQ25OH8Yh5r3M.Tpeu', 'Njg5MGY3YTYxZTUxYmY4ZmZhMWI1ZTQ2ZDhkMTg2MmUxMzMzNDQ1ZWNiN2ExNTEzYTIwOTE0NzlhODkyM2ZmZA==', '2024-12-16 14:19:23');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `carparts`
--
ALTER TABLE `carparts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `carparts`
--
ALTER TABLE `carparts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
