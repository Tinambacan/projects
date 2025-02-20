-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 06, 2024 at 03:27 AM
-- Server version: 10.4.27-MariaDB
-- PHP Version: 8.1.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `u952592082_logistics`
--

-- --------------------------------------------------------

--
-- Table structure for table `courier_admin`
--
-- Create a schema named 'example_schema'

CREATE TABLE `courier_admin` (
  `admin_id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `courier_admin`
--

INSERT INTO `courier_admin` (`admin_id`, `username`, `password`) VALUES
(1, 'admin.cybertech', '$2y$10$af/THPKFxNNy7Lr.06x.fODxPwlbMLUHVLXVV.zqaEpIZ87ugdNAu');

-- --------------------------------------------------------

--
-- Table structure for table `delivery`
--

CREATE TABLE `delivery` (
  `del_reference_id` int(11) NOT NULL,
  `src_address` longtext NOT NULL,
  `source_name` varchar(255) NOT NULL,
  `destination_address` longtext NOT NULL,
  `receiver_name` varchar(255) NOT NULL,
  `status` tinyint(1) NOT NULL,
  `partner_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `delivery`
--

INSERT INTO `delivery` (`del_reference_id`, `src_address`, `source_name`, `destination_address`, `receiver_name`, `status`, `partner_id`) VALUES
(16588, 'Buli, Muntinlupa', 'LEX PH', 'Alabang, Muntinlupa City', 'Angel Delama', 0, 1),
(23451, 'Silang, Cavite', 'Thrift Shop', 'General Santos, Taguig', 'Grace Felicity', 0, NULL),
(45203, 'Sun Valley, Paranque', 'AMIA TECH', 'Santo Tomas, Pasig', 'Jane Nicola', 0, NULL),
(98314, 'Don Bosco, Paranaque ', 'Penshoppe', 'Tanay, Rizal', 'Abigail Aba\r\n', 0, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `delivery_partner`
--

CREATE TABLE `delivery_partner` (
  `partner_id` int(11) NOT NULL,
  `rider_name` varchar(255) NOT NULL,
  `vehicle` varchar(255) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `delivery_partner`
--

INSERT INTO `delivery_partner` (`partner_id`, `rider_name`, `vehicle`, `username`, `password`) VALUES
(1, 'Fame Manalo', 'Motor', 'fame.manalo', '$2y$10$gIXy8.OvBiYnYK1x7ZlvLuWVdQlc5x7F8DFCJokYfiBrGUYNiqwou'),
(2, 'Ferb Delacruz', 'Motorcycle', 'ferb.dc', '$2y$10$lYsQwhg0lC38M1Z9KkNxduEAvKVKZgrRV4ZFvSZCyCC0Qrc7nADBe'),
(3, 'Ferlin Datu', 'L300', 'datu_ferlin', '$2y$10$QCTH2P6SZJV7QpnHFxfFDeiW4fBw.xJ2IVwVegYvf2BOSjplzvrm6'),
(4, 'Frixter Gomez', 'Closed Van', 'gomezfrixter', '$2y$10$YJ7.zgP.34uzKDyq/qbauuNzkmcMIFHw0hpYbYO7qNyCUUDvz6ZNO');

-- --------------------------------------------------------

--
-- Table structure for table `history`
--

CREATE TABLE `history` (
  `del_history_id` int(11) NOT NULL,
  `description` longtext NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT current_timestamp(),
  `checkpoint_location` varchar(255) NOT NULL,
  `del_reference_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `history`
--

INSERT INTO `history` (`del_history_id`, `description`, `timestamp`, `checkpoint_location`, `del_reference_id`) VALUES
(1, 'Your parcel has been picked up by our logistics partner.', '2024-01-06 02:15:30', 'Buli, Muntinlupa', 16588),
(2, 'Parcel has departed from sorting facility:', '2024-01-08 02:21:05', 'Muntinlupa Sorting Facility', 16588),
(3, 'Parcel has arrived at sorting facility:', '2024-01-09 02:18:26', 'PARANAQUE NCR South Warehouse', 16588),
(4, 'Parcel is out for delivery.', '2024-01-10 02:22:07', 'MUNTINLUPA Alabang - 1', 16588),
(5, 'Parcel has been delivered.', '2024-01-12 02:22:07', 'Alabang, Muntinlupa', 16588);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `delivery`
--
ALTER TABLE `delivery`
  ADD PRIMARY KEY (`del_reference_id`),
  ADD KEY `fk_partner_id` (`partner_id`);

--
-- Indexes for table `delivery_partner`
--
ALTER TABLE `delivery_partner`
  ADD PRIMARY KEY (`partner_id`);

--
-- Indexes for table `history`
--
ALTER TABLE `history`
  ADD PRIMARY KEY (`del_history_id`),
  ADD KEY `fk_delivery_reference` (`del_reference_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `delivery_partner`
--
ALTER TABLE `delivery_partner`
  MODIFY `partner_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `history`
--
ALTER TABLE `history`
  MODIFY `del_history_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `delivery`
--
ALTER TABLE `delivery`
  ADD CONSTRAINT `fk_partner_id` FOREIGN KEY (`partner_id`) REFERENCES `delivery_partner` (`partner_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `history`
--
ALTER TABLE `history`
  ADD CONSTRAINT `fk_delivery_reference` FOREIGN KEY (`del_reference_id`) REFERENCES `delivery` (`del_reference_id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
