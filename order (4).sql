-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 26, 2022 at 12:44 AM
-- Server version: 10.4.21-MariaDB
-- PHP Version: 7.4.23

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `order`
--

-- --------------------------------------------------------

--
-- Table structure for table `request_table`
--

CREATE TABLE `request_table` (
  `id` int(11) NOT NULL,
  `food_name` varchar(255) NOT NULL,
  `customer_username` varchar(255) NOT NULL,
  `merchant_id` int(11) NOT NULL,
  `escrow_id` int(11) NOT NULL,
  `amount` int(11) NOT NULL,
  `order_type` varchar(255) NOT NULL,
  `gram` int(11) NOT NULL,
  `outcome` int(11) NOT NULL,
  `status` varchar(100) NOT NULL,
  `date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `request_table`
--

INSERT INTO `request_table` (`id`, `food_name`, `customer_username`, `merchant_id`, `escrow_id`, `amount`, `order_type`, `gram`, `outcome`, `status`, `date`) VALUES
(1, 'chicken', 'sydee', 1, 0, 1234, 'selling', 78, 3, 'pending', '2022-05-23 19:33:10'),
(2, 'jellof rice', 'king', 2, 0, 67, 'selling', 12, 23, 'pending', '2022-05-23 23:09:30'),
(3, 'jellof rice', 'abdul', 1, 0, 4700, 'buying', 899, 7, 'pending', '2022-05-25 11:54:24'),
(4, 'chicken', 'abdul', 1, 4, 900, 'buying', 89, 7, 'pending', '2022-05-25 12:36:56'),
(5, 'jellof rice', 'king12', 1, 5, 78, 'selling', 8, 9, 'pending', '2022-05-25 12:47:18'),
(6, 'chicken', 'king12', 1, 5, 1200, 'buying', 8, 8, 'completed', '2022-05-25 16:20:00'),
(7, 'chicken', 'king', 4, 5, 1200, 'buying', 12, 2000, 'pending', '2022-05-25 19:47:51'),
(8, 'chicken', 'king', 4, 5, 300, 'buying', 10, 20, 'Awaiting Seller Delivery', '2022-05-25 19:48:13'),
(9, 'chicken', 'king', 4, 5, 300, 'buying', 10, 20, 'completed', '2022-05-25 19:49:20');

-- --------------------------------------------------------

--
-- Table structure for table `transit_transaction`
--

CREATE TABLE `transit_transaction` (
  `id` int(11) NOT NULL,
  `request_id` int(11) NOT NULL,
  `escrow_id` int(11) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `merchant_id` int(11) NOT NULL,
  `trx_track_id` varchar(50) NOT NULL,
  `status` varchar(255) NOT NULL,
  `transit_level` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `transit_transaction`
--

INSERT INTO `transit_transaction` (`id`, `request_id`, `escrow_id`, `customer_id`, `merchant_id`, `trx_track_id`, `status`, `transit_level`, `created_at`, `updated_at`) VALUES
(5, 9, 5, 1, 4, '4310a13d47', 'completed', 4, '2022-05-25 20:07:00', '2022-05-25 21:13:47'),
(6, 8, 5, 1, 4, '1b00b05fd0', 'Awaiting Seller Delivery', 2, '2022-05-25 20:10:49', '2022-05-25 23:34:02'),
(7, 6, 5, 4, 1, '52b6d247aa', 'completed', 4, '2022-05-25 20:36:17', '2022-05-25 21:46:52');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `fullname` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `account_number` varchar(11) NOT NULL,
  `bank_code` varchar(255) NOT NULL,
  `phone` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `user_type` varchar(255) NOT NULL DEFAULT 'user',
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `fullname`, `email`, `account_number`, `bank_code`, `phone`, `password`, `user_type`, `status`, `created_at`, `updated_at`) VALUES
(1, 'king', 'aka\'aba musa akidi', 'king@gmail.com', '12345678934', '058', '07064355463', '$2y$10$753X484Flqz0PZyOGDg4uuUzK9Fj4ZtOqhD.lK37v3xDF1v8y6H7S', 'admin', 1, '2022-05-23 19:29:30', NULL),
(2, 'sydee', 'sydee stack', 'sydee@gmail.com', '00012345678', '044', '07064355345', '$2y$10$2pNB9Dw4kEVzDBqfPNY8buymIADleCHC8d/5fhVAKCMnagc0SR2RK', 'user', 1, '2022-05-23 19:30:57', NULL),
(3, 'abdul', 'dkkd', 'kdkkdk@gmail.com', '13344', 'guaranty trust bank', '98089876765', '$2y$10$2KZC1OdgISBhqmsmBQbGDuof/UtyAWaAsIKOmc0H/tacAZOsfmXJW', 'user', 1, '2022-05-25 10:37:05', NULL),
(4, 'king12', 'kdkdk', 'kdkdkdk@gmail.com', '78', 'access bank', '09087898761', '$2y$10$z5r9XroTFhF0fMbopekAg.qyj9qRsvsYNB4te4PssQUbcFeJG.o4q', 'escrow', 1, '2022-05-25 10:44:26', NULL),
(5, 'king787', 'kingakkd', 'kkddi@gmail.com', '092348987', 'keystone bank', '09087876787', '$2y$10$8Pb4HjVU9papaRfriTlca.WJwiH.FCGgSEBHNGeiMOlg9feRpLORi', 'escrow', 1, '2022-05-25 10:46:10', NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `request_table`
--
ALTER TABLE `request_table`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `transit_transaction`
--
ALTER TABLE `transit_transaction`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `request_table`
--
ALTER TABLE `request_table`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `transit_transaction`
--
ALTER TABLE `transit_transaction`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
