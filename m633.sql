-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 08, 2026 at 04:08 PM
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
-- Database: `m633`
--

-- --------------------------------------------------------

--
-- Table structure for table `cart`
--

CREATE TABLE `cart` (
  `id` int(11) NOT NULL,
  `users_id` int(11) NOT NULL,
  `products_id` int(11) NOT NULL,
  `cart_qty` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `customers_info`
--

CREATE TABLE `customers_info` (
  `id` int(11) NOT NULL,
  `users_id` int(11) NOT NULL,
  `order_number` text NOT NULL,
  `payment_method` text NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `notes` text NOT NULL COMMENT '(optional)'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `customers_info`
--

INSERT INTO `customers_info` (`id`, `users_id`, `order_number`, `payment_method`, `amount`, `notes`) VALUES
(2, 5, 'M494-2026-511', 'Gcash', 1360.00, 'fcdsfdsfd'),
(3, 5, 'M308-2026-920', 'Gcash', 640.00, 'asdasd'),
(4, 5, 'M211-2026-891', 'Gcash', 4120.00, ''),
(5, 5, 'M505-2026-472', 'Gcash', 2440.00, 'asdasd'),
(6, 5, 'M137-2026-023', 'Gcash', 2440.00, 'asdasd'),
(7, 5, 'M897-2026-302', 'Gcash', 2960.00, 'asdasd'),
(8, 5, 'M910-2026-204', 'COD', 520.00, ''),
(9, 5, 'M499-2026-183', 'Gcash', 640.00, 'asdasdasd'),
(10, 5, 'M261-2026-085', 'COD', 520.00, '');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `order_number` text NOT NULL,
  `users_id` int(11) NOT NULL,
  `products_id` int(11) NOT NULL,
  `qty` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `order_number`, `users_id`, `products_id`, `qty`) VALUES
(7, 'M494-2026-511', 5, 3, 7),
(8, 'M494-2026-511', 5, 4, 1),
(9, 'M308-2026-920', 5, 4, 1),
(10, 'M308-2026-920', 5, 3, 1),
(11, 'M211-2026-891', 5, 4, 7),
(12, 'M505-2026-472', 5, 4, 4),
(13, 'M137-2026-023', 5, 4, 4),
(14, 'M137-2026-023', 5, 3, 3),
(15, 'M897-2026-302', 5, 4, 5),
(16, 'M897-2026-302', 5, 3, 3),
(17, 'M910-2026-204', 5, 4, 1),
(18, 'M499-2026-183', 5, 3, 1),
(19, 'M499-2026-183', 5, 4, 1),
(20, 'M261-2026-085', 5, 4, 1);

-- --------------------------------------------------------

--
-- Table structure for table `pos_transactions`
--

CREATE TABLE `pos_transactions` (
  `id` int(11) NOT NULL,
  `transaction_no` varchar(50) NOT NULL,
  `total` decimal(10,2) NOT NULL,
  `payment_method` varchar(50) NOT NULL,
  `amount_received` decimal(10,2) NOT NULL,
  `change_amount` decimal(10,2) NOT NULL,
  `transaction_date` datetime NOT NULL,
  `status` enum('completed','cancelled','pending') DEFAULT 'completed'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pos_transactions`
--

INSERT INTO `pos_transactions` (`id`, `transaction_no`, `total`, `payment_method`, `amount_received`, `change_amount`, `transaction_date`, `status`) VALUES
(12, 'TXN-20260208-B8DCB8', 1160.00, 'Cash', 1500.00, 340.00, '2026-02-08 13:57:31', 'completed');

-- --------------------------------------------------------

--
-- Table structure for table `pos_transaction_items`
--

CREATE TABLE `pos_transaction_items` (
  `id` int(11) NOT NULL,
  `transaction_id` int(11) NOT NULL,
  `products_id` int(11) NOT NULL,
  `product_name` varchar(255) NOT NULL,
  `quantity` int(11) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `total` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pos_transaction_items`
--

INSERT INTO `pos_transaction_items` (`id`, `transaction_id`, `products_id`, `product_name`, `quantity`, `price`, `total`) VALUES
(11, 12, 4, 'Cofee Cake', 2, 520.00, 1040.00),
(12, 12, 3, 'Cake', 1, 120.00, 120.00);

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `images` text NOT NULL,
  `name` text NOT NULL,
  `description` text NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `quantity` int(11) NOT NULL DEFAULT current_timestamp(),
  `selling` int(11) NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `images`, `name`, `description`, `price`, `quantity`, `selling`, `timestamp`) VALUES
(3, 'black-forest-cake-1658850950.webp', 'Cake', 'asdasdas', 120.00, 93, 10, '2026-02-08 06:02:01'),
(4, 'coffee-cake-1658850950.webp', 'Cofee Cake', 'Cofee Cake Cofee Cake', 520.00, 93, 13, '2026-02-08 06:03:10');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `users_id` int(11) NOT NULL,
  `images` text NOT NULL,
  `name` text NOT NULL,
  `email` text NOT NULL,
  `password` text NOT NULL,
  `role` tinyint(1) NOT NULL COMMENT 'customer=0,admin=1',
  `code` int(11) NOT NULL,
  `timestamp` int(11) NOT NULL,
  `contact` text NOT NULL,
  `address` text NOT NULL,
  `city` text NOT NULL,
  `region` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`users_id`, `images`, `name`, `email`, `password`, `role`, `code`, `timestamp`, `contact`, `address`, `city`, `region`) VALUES
(4, '', 'Admin', 'admin@gmail.com', '$2y$10$/519.hmpb8jXlp.bOArj8OSxn24Jbg9RBS0reEhfMZFlp0q7xPdk6', 1, 0, 0, '', '', '', ''),
(5, '', 'godeg tes t test', 'godegkola@gmail.com', '$2y$10$/519.hmpb8jXlp.bOArj8OSxn24Jbg9RBS0reEhfMZFlp0q7xPdk6', 0, 356658, 0, '435435435', 'dfg', 'fdg', 'Region XII - SOCCSKSARGEN');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `customers_info`
--
ALTER TABLE `customers_info`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pos_transactions`
--
ALTER TABLE `pos_transactions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `transaction_no` (`transaction_no`);

--
-- Indexes for table `pos_transaction_items`
--
ALTER TABLE `pos_transaction_items`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`users_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `cart`
--
ALTER TABLE `cart`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=46;

--
-- AUTO_INCREMENT for table `customers_info`
--
ALTER TABLE `customers_info`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `pos_transactions`
--
ALTER TABLE `pos_transactions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `pos_transaction_items`
--
ALTER TABLE `pos_transaction_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `users_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
