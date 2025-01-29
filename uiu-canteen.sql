-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 29, 2025 at 10:41 AM
-- Server version: 10.4.27-MariaDB
-- PHP Version: 8.2.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `uiu-canteen`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `id` int(11) NOT NULL,
  `username` varchar(100) NOT NULL,
  `password` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id`, `username`, `password`) VALUES
(1, 'admin', 'admin');

-- --------------------------------------------------------

--
-- Table structure for table `cart`
--

CREATE TABLE `cart` (
  `id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `phone_number` varchar(15) DEFAULT NULL,
  `room_number` varchar(50) DEFAULT NULL,
  `restaurant_name` varchar(100) NOT NULL,
  `food_name` varchar(100) NOT NULL,
  `quantity` int(11) NOT NULL,
  `price_per_unit` int(11) NOT NULL,
  `total_price` int(11) GENERATED ALWAYS AS (`quantity` * `price_per_unit`) STORED,
  `added_at` datetime DEFAULT current_timestamp(),
  `status` enum('Pending','Ordered','Cancelled') DEFAULT 'Pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cart`
--

INSERT INTO `cart` (`id`, `username`, `phone_number`, `room_number`, `restaurant_name`, `food_name`, `quantity`, `price_per_unit`, `added_at`, `status`) VALUES
(5, 'emad', NULL, NULL, 'Eastern housing', 'sada', 1, 50, '2025-01-11 20:38:21', 'Ordered'),
(6, 'emad', NULL, NULL, 'Eastern housing', 'fasf', 4, 453, '2025-01-11 20:38:27', 'Ordered'),
(7, 'emad', NULL, NULL, 'Khan\'s Kitchen', 'Steak', 4, 544, '2025-01-11 20:44:14', 'Ordered'),
(8, 'emad', NULL, NULL, 'Khan\'s Kitchen', 'samosa', 1, 70, '2025-01-18 11:53:54', 'Ordered'),
(9, 'aad', NULL, NULL, 'Khan\'s Kitchen', 'Steak', 3, 544, '2025-01-18 12:41:37', 'Ordered'),
(10, 'aad', NULL, NULL, 'Khan\'s Kitchen', 'samosa', 2, 70, '2025-01-18 12:51:47', 'Ordered'),
(11, 'aad', NULL, NULL, 'Khan\'s Kitchen', 'Steak', 1, 544, '2025-01-18 12:59:29', 'Ordered'),
(12, 'aad', NULL, NULL, 'Khan\'s Kitchen', 'Steak', 1, 544, '2025-01-18 13:01:58', 'Ordered'),
(13, 'emad', NULL, NULL, 'Khan\'s Kitchen', 'samosa', 1, 70, '2025-01-18 15:45:40', 'Ordered'),
(14, 'emad', NULL, NULL, 'Khan\'s Kitchen', 'Steak', 1, 544, '2025-01-18 15:45:45', 'Ordered'),
(15, 'emad', NULL, NULL, 'Olympia Cafe', 'random', 1, 54, '2025-01-18 15:45:53', 'Ordered'),
(16, 'emad', NULL, NULL, 'Khan\'s Kitchen', 'samosa', 1, 70, '2025-01-18 15:46:51', 'Ordered'),
(17, 'emad', NULL, NULL, 'Khan\'s Kitchen', 'samosa', 2, 70, '2025-01-18 15:47:04', 'Ordered'),
(18, 'emad', NULL, NULL, 'Khan\'s Kitchen', 'samosa', 1, 70, '2025-01-20 21:59:03', 'Ordered'),
(19, 'emad', NULL, NULL, 'Khan\'s Kitchen', 'Steak', 2, 544, '2025-01-20 22:01:05', 'Ordered'),
(20, 'emad', NULL, NULL, 'Khan\'s Kitchen', 'Steak', 1, 544, '2025-01-21 20:08:26', 'Ordered'),
(21, 'emad', NULL, NULL, 'Khan\'s Kitchen', 'asdas', 1, 3, '2025-01-21 20:09:15', 'Ordered'),
(22, 'emad', NULL, NULL, 'Khan\'s Kitchen', 'random', 2, 546, '2025-01-21 20:09:18', 'Ordered'),
(24, 'emad', NULL, NULL, 'Khan\'s Kitchen', 'asd', 1, 4, '2025-01-25 14:40:43', 'Ordered'),
(25, 'mht', NULL, NULL, 'Eastern housing', 'fasf', 1, 453, '2025-01-28 19:19:35', 'Ordered'),
(26, 'mht', NULL, NULL, 'Khan\'s Kitchen', 'samosa', 5, 70, '2025-01-29 14:33:56', 'Pending'),
(27, 'emad', NULL, NULL, 'Khan\'s Kitchen', 'samosa', 1, 70, '2025-01-29 14:51:21', 'Pending'),
(28, 'emad', NULL, NULL, 'Khan\'s Kitchen', 'Steak', 3, 544, '2025-01-29 14:51:55', 'Pending');

-- --------------------------------------------------------

--
-- Table structure for table `check_for_rider`
--

CREATE TABLE `check_for_rider` (
  `id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `restaurant_name` varchar(100) NOT NULL,
  `customer_name` varchar(100) NOT NULL,
  `phone_number` varchar(15) NOT NULL,
  `room_number` varchar(50) NOT NULL,
  `total_price` int(11) NOT NULL,
  `status` enum('Awaiting','Pending','Confirmed','Completed','Cancelled') DEFAULT 'Awaiting',
  `rider_username` varchar(255) DEFAULT NULL,
  `added_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `check_for_rider`
--

INSERT INTO `check_for_rider` (`id`, `order_id`, `restaurant_name`, `customer_name`, `phone_number`, `room_number`, `total_price`, `status`, `rider_username`, `added_at`) VALUES
(11, 11, 'Khan\'s Kitchen', 'emad', '123', '123', 614, 'Completed', 'a', '2025-01-18 15:45:49'),
(13, 13, 'Khan\'s Kitchen', 'emad', 'g', 'tr', 70, 'Completed', 'a', '2025-01-18 15:46:55'),
(14, 14, 'Khan\'s Kitchen', 'emad', '12', '31', 140, 'Completed', 'a', '2025-01-18 15:47:09'),
(15, 15, 'Khan\'s Kitchen', 'emad', '1233', '432342', 70, 'Completed', 'a', '2025-01-20 21:59:08'),
(16, 16, 'Khan\'s Kitchen', 'emad', '545', '543', 1088, 'Completed', 'a', '2025-01-20 22:01:10'),
(17, 17, 'Khan\'s Kitchen', 'emad', 'asfa', 'asdas', 544, 'Completed', 'a', '2025-01-21 20:08:30'),
(18, 18, 'Khan\'s Kitchen', 'emad', '123', '1321', 1095, 'Completed', 'a', '2025-01-21 20:09:23'),
(19, 21, 'Khan\'s Kitchen', 'emad', '123', '456', 4, 'Completed', 'a', '2025-01-25 14:44:16'),
(20, 22, 'Eastern housing', 'mht', '21321', '112', 453, 'Completed', 'a', '2025-01-28 19:20:23');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `restaurant_name` varchar(100) NOT NULL,
  `customer_name` varchar(100) NOT NULL,
  `total_price` int(11) NOT NULL,
  `phone_number` varchar(15) NOT NULL,
  `room_number` varchar(50) NOT NULL,
  `order_date` datetime DEFAULT current_timestamp(),
  `status` enum('Awaiting','Pending','Confirmed','Completed','Cancelled') DEFAULT 'Awaiting'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `restaurant_name`, `customer_name`, `total_price`, `phone_number`, `room_number`, `order_date`, `status`) VALUES
(11, 'Khan\'s Kitchen', 'emad', 614, '123', '123', '2025-01-18 15:45:49', 'Completed'),
(13, 'Khan\'s Kitchen', 'emad', 70, 'g', 'tr', '2025-01-18 15:46:55', 'Completed'),
(14, 'Khan\'s Kitchen', 'emad', 140, '12', '31', '2025-01-18 15:47:09', 'Completed'),
(15, 'Khan\'s Kitchen', 'emad', 70, '1233', '432342', '2025-01-20 21:59:08', 'Completed'),
(16, 'Khan\'s Kitchen', 'emad', 1088, '545', '543', '2025-01-20 22:01:10', 'Completed'),
(17, 'Khan\'s Kitchen', 'emad', 544, 'asfa', 'asdas', '2025-01-21 20:08:30', 'Completed'),
(18, 'Khan\'s Kitchen', 'emad', 1095, '123', '1321', '2025-01-25 20:09:23', 'Completed'),
(21, 'Khan\'s Kitchen', 'emad', 4, '123', '456', '2025-01-25 14:44:16', 'Completed'),
(22, 'Eastern housing', 'mht', 453, '21321', '112', '2025-01-25 19:20:23', 'Completed');

-- --------------------------------------------------------

--
-- Table structure for table `order_details`
--

CREATE TABLE `order_details` (
  `id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `food_name` varchar(100) NOT NULL,
  `quantity` int(11) NOT NULL,
  `price_per_unit` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `order_details`
--

INSERT INTO `order_details` (`id`, `order_id`, `food_name`, `quantity`, `price_per_unit`) VALUES
(13, 11, 'samosa', 1, 70),
(14, 11, 'Steak', 1, 544),
(16, 13, 'samosa', 1, 70),
(17, 14, 'samosa', 2, 70),
(18, 15, 'samosa', 1, 70),
(19, 16, 'Steak', 2, 544),
(20, 17, 'Steak', 1, 544),
(21, 18, 'asdas', 1, 3),
(22, 18, 'random', 2, 546),
(23, 21, 'asd', 1, 4),
(24, 22, 'fasf', 1, 453);

-- --------------------------------------------------------

--
-- Table structure for table `owners`
--

CREATE TABLE `owners` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `restaurant_name` varchar(100) NOT NULL,
  `is_blocked` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `owners`
--

INSERT INTO `owners` (`id`, `username`, `password`, `restaurant_name`, `is_blocked`) VALUES
(1, 'KK', '1234', 'Khan\'s Kitchen', 0),
(2, 'OC', '1234', 'Olympia Cafe', 0),
(8, 'neptune_admin', 'neptunepass', 'Neptune Diner', 0),
(9, 'EE', '1234', 'Eastern housing', 0),
(10, 'BB', '1234', 'Uiu Cafe', 0);

-- --------------------------------------------------------

--
-- Table structure for table `restaurants`
--

CREATE TABLE `restaurants` (
  `id` int(11) NOT NULL,
  `restaurant_name` varchar(100) NOT NULL,
  `food_name` varchar(100) NOT NULL,
  `food_quantity` int(11) NOT NULL,
  `food_image` varchar(255) NOT NULL,
  `price` int(11) NOT NULL,
  `availability` enum('Available','Not Available') NOT NULL,
  `food_category` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `restaurants`
--

INSERT INTO `restaurants` (`id`, `restaurant_name`, `food_name`, `food_quantity`, `food_image`, `price`, `availability`, `food_category`) VALUES
(19, 'Khan\'s Kitchen', 'samosa', 5, '../resources/samosa.png', 70, 'Available', 'Snacks'),
(20, 'Khan\'s Kitchen', 'Steak', 12, '../resources/360_F_252388016_KjPnB9vglSCuUJAumCDNbmMzGdzPAucK.jpg', 544, 'Available', 'Dinner'),
(21, 'Olympia Cafe', 'random', 2, '../resources/images (2).jpeg', 54, 'Available', 'Snacks'),
(22, 'Olympia Cafe', 'sdas', 5, '../resources/360_F_252388016_KjPnB9vglSCuUJAumCDNbmMzGdzPAucK.jpg', 6, 'Available', 'Snacks'),
(23, 'Eastern housing', 'sada', 3, '../resources/SHF_home-slide-1.jpg', 50, 'Available', 'Snacks'),
(24, 'Eastern housing', 'fasf', 12, '../resources/images (3).jpeg', 453, 'Available', 'Snacks'),
(25, 'Uiu Cafe', 'burger', 2, '../resources/images (2).jpeg', 76, 'Available', 'Dinner'),
(26, 'Uiu Cafe', 'Samosa', 6, '../resources/FAW-recipes-pasta-sausage-basil-and-mustard-hero-06-cfd1c0a2989e474ea7e574a38182bbee.jpg', 6, 'Available', 'Snacks'),
(27, 'Khan\'s Kitchen', 'asd', 4, '../resources/images.jpeg', 4, 'Available', 'Snacks'),
(28, 'Khan\'s Kitchen', 'asdas', 6, '../resources/food-truck-indulgence-pork-burger-topped-with-cheese-paired-with-fries-ai-generated-photo.jpg', 3, 'Not Available', 'Snacks'),
(29, 'Khan\'s Kitchen', 'random', 18, '../resources/170206165040-dubai-michelin-dining-boca.jpg', 546, 'Available', 'Snacks');

-- --------------------------------------------------------

--
-- Table structure for table `riders`
--

CREATE TABLE `riders` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `phone_number` varchar(15) NOT NULL,
  `availability` enum('Available','Busy') DEFAULT 'Available',
  `is_blocked` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `riders`
--

INSERT INTO `riders` (`id`, `username`, `password`, `phone_number`, `availability`, `is_blocked`) VALUES
(1, 'a', 'a', '012', 'Available', 0);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `is_blocked` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password_hash`, `is_blocked`) VALUES
(13, 'ada', '$2y$10$DlzG167tLn1kd3It8fOLF..N.MCirVQgR8LJDVEGlUep5e6uJxtA2', 0),
(14, 'asda', '$2y$10$XuZ.cZ6elfQQmiOT45JvOeywn8UB01r4KYxuo/Ve9vhdEeYM6rE1u', 0),
(15, '12', '$2y$10$vZnfcCtLvMD/ICib5VnWEuVLERZnn0bPKRBKQq4wfNuBCtKpzzEam', 0),
(18, 'aads', '$2y$10$bwmIVx1X5/mppVZjV7bixedcQrjDVDwdjw5vm.JZ.3X/htoEABYaq', 0),
(19, 'rrr', '$2y$10$HZWNWA27ueSAhoPOsZIAPen8gBwEKF4tttJ2FR.i84Af71GFqhHLG', 0),
(20, 'autumnsimp', '$2y$10$bm4Z056X/J.llMbn4p9WSeM5m5inAZlDB8dsoXnEoCwv0HniExx22', 0),
(21, 'eee', '$2y$10$H2s4IMil5XOlf9EAvBAdSeQ.iJLMjhW1Vp/hwHSt3.VlyP8LaLeyu', 0),
(22, 'emada', '$2y$10$fAGgqKmvNDzomGK781zpE.qjZkb2HLjHbah92hUM9.3OHod/BjtQ.', 0),
(23, 'aaaa', '$2y$10$V6/8WHnp4Q.jbIJaCexOBOLvj5K2b42xjzNXEc1D7NYnbmSgJLI2u', 0),
(24, 'emad', '$2y$10$jkWd/dDli5p/j2r/pRwoXuRbNNAhxmCFEkTwPpWEhbey0aPNFYwqS', 0),
(25, 'mht', '$2y$10$4l2mOXg7kNEGl5oPWv.qAe0kVqc6X44iJgICEdFrxezUtSgMScQQ6', 0);

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
-- Indexes for table `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`id`),
  ADD KEY `restaurant_name` (`restaurant_name`);

--
-- Indexes for table `check_for_rider`
--
ALTER TABLE `check_for_rider`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_id` (`order_id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `order_details`
--
ALTER TABLE `order_details`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_id` (`order_id`);

--
-- Indexes for table `owners`
--
ALTER TABLE `owners`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `owner_username` (`username`),
  ADD UNIQUE KEY `restaurant_name` (`restaurant_name`);

--
-- Indexes for table `restaurants`
--
ALTER TABLE `restaurants`
  ADD PRIMARY KEY (`id`),
  ADD KEY `restaurant_name` (`restaurant_name`);

--
-- Indexes for table `riders`
--
ALTER TABLE `riders`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

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
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `cart`
--
ALTER TABLE `cart`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT for table `check_for_rider`
--
ALTER TABLE `check_for_rider`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `order_details`
--
ALTER TABLE `order_details`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `owners`
--
ALTER TABLE `owners`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `restaurants`
--
ALTER TABLE `restaurants`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT for table `riders`
--
ALTER TABLE `riders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `cart`
--
ALTER TABLE `cart`
  ADD CONSTRAINT `cart_ibfk_1` FOREIGN KEY (`restaurant_name`) REFERENCES `restaurants` (`restaurant_name`) ON DELETE CASCADE;

--
-- Constraints for table `check_for_rider`
--
ALTER TABLE `check_for_rider`
  ADD CONSTRAINT `check_for_rider_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `order_details`
--
ALTER TABLE `order_details`
  ADD CONSTRAINT `order_details_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `restaurants`
--
ALTER TABLE `restaurants`
  ADD CONSTRAINT `restaurants_ibfk_1` FOREIGN KEY (`restaurant_name`) REFERENCES `owners` (`restaurant_name`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
