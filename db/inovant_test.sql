-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 10, 2025 at 11:27 AM
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
-- Database: `inovant_test`
--

-- --------------------------------------------------------

--
-- Table structure for table `cart`
--

CREATE TABLE `cart` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `action` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cart`
--

INSERT INTO `cart` (`id`, `user_id`, `product_id`, `quantity`, `action`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 2, 1, '2025-03-22 04:54:42', '2025-03-22 10:24:42'),
(2, 1, 2, 1, 1, '2025-03-22 04:54:42', '2025-03-22 10:24:42'),
(3, 1, 6, 1, 0, '2025-03-23 18:44:25', NULL),
(4, 1, 7, 1, 0, '2025-03-23 18:44:47', NULL),
(5, 1, 9, 2, 0, '2025-07-10 09:21:08', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(11) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `status` int(11) NOT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `name`, `description`, `price`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Product A', 'Description for Product A', 19.99, 1, '2025-03-22 12:03:07', '2025-07-10 12:19:22'),
(2, 'Product B', 'Description for Product Ba', 30.00, 1, '2025-03-22 12:03:07', '2025-07-10 12:19:28'),
(6, 'glass', 'dsfdf sdf', 4555.00, 1, '2025-03-22 14:45:22', '2025-07-10 12:19:36'),
(7, 'glassa test', 'Nose Pin', 500.00, 1, '2025-03-23 22:50:06', '2025-07-10 12:19:44'),
(8, 'LCD PC Monitor', 'Lenovo L-Series 68.58 cm (27 inch) Full HD LED Backlit IPS Panel with 99% sRGB, 1xHDMI1.4, 1xVGA, Tilt-able Stand, 3WX2 Inbuilt Speakers, Customization Artery, Smart Display Monitor (L27i-4A)  (Response Time: 1 ms, 100 Hz Refresh Rate)', 12000.00, 0, '2025-07-10 12:20:37', '2025-07-10 13:26:03'),
(9, 'Motorbike Helmet', 'Steelbird SBA-7 7Wings ISI Certified Flip-Up for Men and Women Motorbike Helmet  (Dashing Black)', 800.00, 0, '2025-07-10 14:50:51', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `product_images`
--

CREATE TABLE `product_images` (
  `id` int(11) UNSIGNED NOT NULL,
  `product_id` int(11) UNSIGNED NOT NULL,
  `image_path` varchar(255) NOT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `product_images`
--

INSERT INTO `product_images` (`id`, `product_id`, `image_path`, `created_at`) VALUES
(1, 1, 'uploads/product1_image1.jpg', '2025-03-22 12:03:22'),
(2, 1, 'uploads/product1_image2.jpg', '2025-03-22 12:03:22'),
(3, 2, 'uploads/product2_image1.jpg', '2025-03-22 12:03:22'),
(10, 7, 'uploads/1742750407_5137228dbcbf0410e7cf.png', '2025-03-23 22:50:07'),
(11, 8, 'uploads/1752130238_4135748f9952abc16027.webp', '2025-07-10 12:20:38'),
(12, 8, 'uploads/1752130238_71ed0f80d2fe145a8438.webp', '2025-07-10 12:20:38'),
(13, 9, 'uploads/1752139252_db063ad4c5bbf51c2950.webp', '2025-07-10 14:50:52'),
(14, 9, 'uploads/1752139252_d19af61bb378df53f0a7.webp', '2025-07-10 14:50:52');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `product_images`
--
ALTER TABLE `product_images`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_product_images_product_id` (`product_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `cart`
--
ALTER TABLE `cart`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `product_images`
--
ALTER TABLE `product_images`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `product_images`
--
ALTER TABLE `product_images`
  ADD CONSTRAINT `fk_product_images_product_id` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
