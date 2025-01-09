-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 09, 2025 at 07:50 PM
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
-- Database: `uiu-canteen`
--

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `restaurant_name` varchar(100) NOT NULL,
  `customer_name` varchar(100) DEFAULT NULL,
  `food_items` text NOT NULL,
  `total_price` int(11) NOT NULL,
  `order_date` datetime DEFAULT current_timestamp(),
  `status` enum('Pending','Completed','Cancelled') DEFAULT 'Pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `restaurant_name`, `customer_name`, `food_items`, `total_price`, `order_date`, `status`) VALUES
(9, 'Khan\'s Kitchen', 'John Doe', 'Samosa, Burger', 250, '2025-01-01 12:30:00', 'Completed'),
(10, 'Khan\'s Kitchen', 'Jane Smith', 'Pizza, Coke', 400, '2025-01-02 18:15:00', 'Completed'),
(11, 'Olympia Cafe', 'Alice Brown', 'Coffee, Croissant', 150, '2025-01-01 09:00:00', 'Completed'),
(12, 'Neptune Diner', 'Bob Marley', 'Grilled Fish', 500, '2025-01-01 13:45:00', 'Completed'),
(13, 'Khan\'s Kitchen', 'Mark Taylor', 'Chicken Curry, Rice', 350, '2025-01-02 14:00:00', 'Pending'),
(14, 'Olympia Cafe', 'Emma Stone', 'Pasta, Garlic Bread', 320, '2025-01-02 19:30:00', 'Completed'),
(15, 'Neptune Diner', 'Chris Pine', 'Steak, Salad', 700, '2025-01-03 20:00:00', 'Cancelled'),
(16, 'Khan\'s Kitchen', 'Lucy Liu', 'Biryani, Soda', 300, '2025-01-03 15:00:00', 'Completed'),
(17, 'Khan\'s Kitchen', 'John Doe', 'Samosa, Burger', 250, '2025-01-10 00:38:08', 'Completed'),
(18, 'Khan\'s Kitchen', 'Jane Smith', 'Pizza, Coke', 400, '2025-01-10 00:38:08', 'Completed'),
(19, 'Olympia Cafe', 'Alice Brown', 'Coffee, Croissant', 150, '2025-01-10 00:38:08', 'Completed'),
(20, 'Neptune Diner', 'Bob Marley', 'Grilled Fish', 500, '2025-01-10 00:38:08', 'Completed'),
(21, 'Khan\'s Kitchen', 'Mark Taylor', 'Chicken Curry, Rice', 350, '2025-01-10 00:38:08', 'Pending'),
(22, 'Olympia Cafe', 'Emma Stone', 'Pasta, Garlic Bread', 320, '2025-01-10 00:38:08', 'Pending'),
(23, 'Neptune Diner', 'Chris Pine', 'Steak, Salad', 700, '2025-01-10 00:38:08', 'Pending');

-- --------------------------------------------------------

--
-- Table structure for table `owners`
--

CREATE TABLE `owners` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `restaurant_name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `owners`
--

INSERT INTO `owners` (`id`, `username`, `password`, `restaurant_name`) VALUES
(1, 'KK', '1234', 'Khan\'s Kitchen'),
(2, 'OC', '1234', 'Olympia Cafe'),
(8, 'neptune_admin', 'neptunepass', 'Neptune Diner');

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
(1, 'Khan\'s Kitchen', 'Samosa', 20, 'uploads/istockphoto-1430060145-612x612.jpg', 10, 'Available', 'Snacks'),
(2, 'Khan\'s Kitchen', 'singara', 15, 'uploads/images.jpeg', 5, 'Available', 'Snacks'),
(7, 'Khan\'s Kitchen', 'Samosas', 12, 'uploads/istockphoto-1430060145-612x612.jpg', 12, 'Available', 'Snacks'),
(9, 'Khan\'s Kitchen', 'steak', 12, 'uploads/images (2).jpeg', 456, 'Available', '14');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password_hash` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password_hash`) VALUES
(12, 'emad', '12314'),
(13, 'ada', '$2y$10$DlzG167tLn1kd3It8fOLF..N.MCirVQgR8LJDVEGlUep5e6uJxtA2'),
(14, 'asda', '$2y$10$XuZ.cZ6elfQQmiOT45JvOeywn8UB01r4KYxuo/Ve9vhdEeYM6rE1u'),
(15, '12', '$2y$10$vZnfcCtLvMD/ICib5VnWEuVLERZnn0bPKRBKQq4wfNuBCtKpzzEam'),
(16, 'ee', '$2y$10$4SLDbcKQbAz56JKFAgzY7exNN3sdjdT/jvJn6RNHiw3ZPe0ZNTG8y'),
(17, 'aad', '$2y$10$0tiSWl/jxxKoE6cdwW2zbOk3p6/yz0ew8ZxZOhmGfwnKf.Qu1Y09.'),
(18, 'aads', '$2y$10$bwmIVx1X5/mppVZjV7bixedcQrjDVDwdjw5vm.JZ.3X/htoEABYaq'),
(19, 'rrr', '$2y$10$HZWNWA27ueSAhoPOsZIAPen8gBwEKF4tttJ2FR.i84Af71GFqhHLG'),
(20, 'autumnsimp', '$2y$10$bm4Z056X/J.llMbn4p9WSeM5m5inAZlDB8dsoXnEoCwv0HniExx22'),
(21, 'eee', '$2y$10$H2s4IMil5XOlf9EAvBAdSeQ.iJLMjhW1Vp/hwHSt3.VlyP8LaLeyu'),
(22, 'emada', '$2y$10$fAGgqKmvNDzomGK781zpE.qjZkb2HLjHbah92hUM9.3OHod/BjtQ.'),
(23, 'aaaa', '$2y$10$V6/8WHnp4Q.jbIJaCexOBOLvj5K2b42xjzNXEc1D7NYnbmSgJLI2u');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `restaurant_name` (`restaurant_name`);

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
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `owners`
--
ALTER TABLE `owners`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `restaurants`
--
ALTER TABLE `restaurants`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`restaurant_name`) REFERENCES `owners` (`restaurant_name`) ON DELETE CASCADE;

--
-- Constraints for table `restaurants`
--
ALTER TABLE `restaurants`
  ADD CONSTRAINT `restaurants_ibfk_1` FOREIGN KEY (`restaurant_name`) REFERENCES `owners` (`restaurant_name`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
