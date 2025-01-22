-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Jan 22, 2025 at 07:23 PM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `accounts`
--

-- --------------------------------------------------------

--
-- Table structure for table `account_info`
--

CREATE TABLE `account_info` (
  `card_number` varchar(16) NOT NULL,
  `expiry_date` date NOT NULL,
  `atm_id` varchar(50) NOT NULL,
  `unique_transaction_id` varchar(50) NOT NULL,
  `pin` varchar(10) NOT NULL,
  `withdrawal_amount` decimal(10,2) NOT NULL,
  `balance` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `account_info`
--

INSERT INTO `account_info` (`card_number`, `expiry_date`, `atm_id`, `unique_transaction_id`, `pin`, `withdrawal_amount`, `balance`) VALUES
('1234567890123456', '2025-12-31', 'ATM001', 'TXN001', '1234', 500.00, 998999.00),
('2345678901234567', '2026-01-31', 'ATM002', 'TXN002', '2345', 300.00, 1000000.00),
('3456789012345678', '2024-11-30', 'ATM003', 'TXN003', '3456', 1000.00, 1000000.00),
('4567890123456789', '2027-06-15', 'ATM004', 'TXN004', '4567', 750.00, 1000000.00),
('5678901234567890', '2023-10-20', 'ATM005', 'TXN005', '5678', 250.00, 1000000.00);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `account_info`
--
ALTER TABLE `account_info`
  ADD PRIMARY KEY (`unique_transaction_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
