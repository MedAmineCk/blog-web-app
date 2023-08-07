-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 18, 2023 at 12:37 AM
-- Server version: 10.4.24-MariaDB
-- PHP Version: 8.1.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `ecomlocal`
--

-- --------------------------------------------------------

--
-- Table structure for table `areas`
--

CREATE TABLE `areas` (
  `id_area` int(11) NOT NULL,
  `area` varchar(45) DEFAULT NULL,
  `shipping` int(11) DEFAULT NULL,
  `return` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `areas`
--

INSERT INTO `areas` (`id_area`, `area`, `shipping`, return_price, `created_at`, `updated_at`) VALUES
(1, 'oujda', 20, 0, '2023-03-13 14:47:00', '2023-03-13 14:47:00'),
(2, 'taourirt', 30, 10, '2023-03-13 14:47:00', '2023-03-13 14:47:00'),
(3, 'berkane', 25, 5, '2023-03-13 14:47:00', '2023-03-13 14:47:00');

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id_category` int(11) NOT NULL,
  `category` varchar(45) DEFAULT NULL,
  `thumbnail` varchar(45) DEFAULT NULL,
  `description` varchar(135) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id_category`, `category`, `thumbnail`, `description`) VALUES
(1, 'all', 'placeholder.jpg', 'this category for all products'),
(2, 'clothes', 'placeholder.jpg', 'this category for clothes products'),
(3, 'watches', 'placeholder.jpg', 'this category for watches products');

-- --------------------------------------------------------

--
-- Table structure for table `clients_packs`
--

CREATE TABLE `clients_packs` (
  `id_client_pack` int(11) NOT NULL,
  `id_area` int(11) NOT NULL,
  `client_name` varchar(45) DEFAULT NULL,
  `client_phone` varchar(45) DEFAULT NULL,
  `client_address` varchar(135) DEFAULT NULL,
  `label` varchar(135) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `clients_packs`
--

INSERT INTO `clients_packs` (`id_client_pack`, `id_area`, `client_name`, `client_phone`, `client_address`, `label`, `created_at`, `updated_at`) VALUES
(1, 3, 'Brahim Abda ', '0610405060', 'hey el Qods rue 04, p 08', 'sifthali qbl nhar lhed', '2023-03-14 16:28:58', '2023-03-14 16:28:58'),
(2, 2, 'Brahim Abda ', '0610405060', 'hey el Qods rue 04, p 08', 'sifthali qbl nhar lhed', '2023-03-14 16:29:55', '2023-03-14 16:29:55'),
(3, 1, 'Brahim Abda ', '0610405060', 'hey el Qods rue 04, p 08', 'sifthali qbl nhar lhed', '2023-03-14 16:30:10', '2023-03-14 16:30:10'),
(4, 1, 'Brahim Abda ', '0610405060', 'hey el Qods rue 04, p 08', 'sifthali qbl nhar lhed', '2023-03-14 16:30:32', '2023-03-14 16:30:32');

-- --------------------------------------------------------

--
-- Table structure for table `deliverers`
--

CREATE TABLE `deliverers` (
  `id_deliverer` int(11) NOT NULL,
  `first_name` varchar(45) DEFAULT NULL,
  `last_name` varchar(45) DEFAULT NULL,
  `phone_number` varchar(45) DEFAULT NULL,
  `CIN` varchar(45) DEFAULT NULL,
  `id_area` int(11) NOT NULL,
  `address` varchar(45) DEFAULT NULL,
  `profile_pic` varchar(45) DEFAULT NULL,
  `price_delivered` int(11) DEFAULT NULL,
  `price_returned` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `deliverers`
--

INSERT INTO `deliverers` (`id_deliverer`, `first_name`, `last_name`, `phone_number`, `CIN`, `id_area`, `address`, `profile_pic`, `price_delivered`, `price_returned`, `created_at`, `updated_at`) VALUES
(1, 'Aymen', 'Habib', '0610203040', 'OJ563247', 1, 'hey elqods', '2023-03-17-21-59-17-1-9a61ad9c.jpg', 10, 0, '2023-03-17 14:48:11', '2023-03-17 20:59:17');

-- --------------------------------------------------------

--
-- Table structure for table `deliverer_packs`
--

CREATE TABLE `deliverer_packs` (
  `id_deliverer_pack` int(11) NOT NULL,
  `id_deliverer` int(11) DEFAULT NULL,
  `created_date` date DEFAULT NULL,
  `label` varchar(45) DEFAULT NULL,
  `status` varchar(45) DEFAULT 'Pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `deliverer_packs`
--

INSERT INTO `deliverer_packs` (`id_deliverer_pack`, `id_deliverer`, `created_date`, `label`, `status`, `created_at`, `updated_at`) VALUES
(1, 1, '2023-03-17', '', 'Pending', '2023-03-17 17:26:58', '2023-03-17 19:45:32');

-- --------------------------------------------------------

--
-- Table structure for table `deliveries`
--

CREATE TABLE `deliveries` (
  `id_delivery` int(11) NOT NULL,
  `id_order` int(11) DEFAULT NULL COMMENT 'Dashboard / Client / Deliverer',
  `id_deliverer` int(11) DEFAULT NULL,
  `role_deliverer` varchar(45) DEFAULT NULL COMMENT 'Dashboard / Deliverer',
  `delivery_status` varchar(45) DEFAULT NULL COMMENT 'Pending / Processing / Confirm / unreachable / Cancel / Deliver',
  `comment` varchar(45) DEFAULT NULL,
  `id_invoice` int(11) DEFAULT NULL,
  `isPaid` bit(1) DEFAULT b'0',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `deliveries`
--

INSERT INTO `deliveries` (`id_delivery`, `id_order`, `id_deliverer`, `role_deliverer`, `delivery_status`, `comment`, `id_invoice`, `isPaid`, `created_at`, `updated_at`) VALUES
(1, 3, 1, 'Deliverer', 'Deliver', '', NULL, b'0', '2023-03-17 22:15:53', '2023-03-17 23:15:53'),
(2, 4, 1, 'Deliverer', 'Return', '', NULL, b'0', '2023-03-17 22:16:03', '2023-03-17 23:16:03'),
(3, 5, 1, 'Deliverer', 'Deliver', '', NULL, b'0', '2023-03-17 22:16:09', '2023-03-17 23:16:09');

-- --------------------------------------------------------

--
-- Table structure for table `invoices`
--

CREATE TABLE `invoices` (
  `id_invoice` int(11) NOT NULL,
  `id_user` int(11) DEFAULT NULL,
  `full_name` varchar(45) DEFAULT NULL,
  `type` varchar(10) DEFAULT NULL COMMENT 'client / deliverer',
  `credit` int(11) DEFAULT NULL,
  `bill` int(11) DEFAULT NULL,
  `creation_date` datetime DEFAULT NULL,
  `comment` varchar(100) DEFAULT NULL,
  `status` varchar(10) DEFAULT 'unPaid' COMMENT 'Paid / unPaid'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `id_notification` int(11) NOT NULL,
  `id_target` int(11) DEFAULT NULL,
  `target` varchar(45) DEFAULT NULL COMMENT 'Client / Deliverer / Dashboard',
  `content` varchar(45) DEFAULT NULL,
  `datetime` datetime DEFAULT NULL,
  `is_open` bit(1) DEFAULT b'0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `notifications`
--

INSERT INTO `notifications` (`id_notification`, `id_target`, `target`, `content`, `datetime`, `is_open`) VALUES
(1, 1, 'Admin', 'the Order #3LCZ4Z4Y have been Deliver by Deli', '2023-03-17 23:04:08', b'0'),
(2, 1, 'Admin', 'the Order #JM9HWI3N have been Deliver by Deli', '2023-03-17 23:04:24', b'0'),
(3, 1, 'Admin', 'the Order #02NWGTVQ have been Deliver by Deli', '2023-03-17 23:05:20', b'0'),
(4, 1, 'Admin', 'the Order #TOPLHVBF have been Return by Deliv', '2023-03-17 23:05:30', b'0'),
(5, 1, 'Admin', 'the Order #02NWGTVQ have been Deliver by Deli', '2023-03-17 23:15:53', b'0'),
(6, 1, 'Admin', 'the Order #3LCZ4Z4Y have been Return by Deliv', '2023-03-17 23:16:03', b'0'),
(7, 1, 'Admin', 'the Order #JM9HWI3N have been Deliver by Deli', '2023-03-17 23:16:09', b'0');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id_order` int(11) NOT NULL,
  `id_product` int(11) NOT NULL,
  `items_quantity` int(11) DEFAULT NULL,
  `id_deliverer_pack` int(11) DEFAULT NULL,
  `id_client_pack` int(11) NOT NULL,
  `id_variant` int(11) DEFAULT NULL,
  `total` int(11) DEFAULT NULL,
  `status` varchar(45) DEFAULT 'Pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id_order`, `id_product`, `items_quantity`, `id_deliverer_pack`, `id_client_pack`, `id_variant`, `total`, `status`, `created_at`, `updated_at`) VALUES
(1, 3, 1, NULL, 1, NULL, 450, 'Pending', '2023-03-14 16:28:58', '2023-03-14 18:16:09'),
(2, 2, 2, NULL, 2, 7, 500, 'Pending', '2023-03-14 16:29:55', '2023-03-14 18:16:10'),
(3, 1, 1, 1, 3, 2, 299, 'Deliver', '2023-03-14 16:30:10', '2023-03-17 23:15:53'),
(4, 3, 1, 1, 4, NULL, 450, 'Return', '2023-03-14 16:30:32', '2023-03-17 23:16:03'),
(5, 2, 2, 1, 4, 7, 500, 'Deliver', '2023-03-14 16:30:32', '2023-03-17 23:16:09'),
(6, 1, 1, 1, 4, 2, 299, 'Pending', '2023-03-14 16:30:32', '2023-03-17 23:14:36');

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id_product` int(11) NOT NULL,
  `thumbnail` varchar(45) DEFAULT NULL,
  `title` varchar(135) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `price` decimal(10,2) DEFAULT NULL,
  `compared_price` int(11) DEFAULT NULL,
  `has_variants` blob DEFAULT 0,
  `quantity` int(11) DEFAULT NULL,
  `type` varchar(45) DEFAULT NULL,
  `is_published` blob DEFAULT 0,
  `meta_title` varchar(255) DEFAULT NULL,
  `meta_description` text DEFAULT NULL,
  `meta_keywords` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id_product`, `thumbnail`, `title`, `description`, `price`, `compared_price`, `has_variants`, `quantity`, `type`, `is_published`, `meta_title`, `meta_description`, `meta_keywords`, `created_at`, `updated_at`) VALUES
(1, '2023-03-13-12-57-59-fc1e3938.png', 'Amazfit – mini montre connectée GTS 2 mini, 68 Modes de sport, surveillance du sommeil, application Zepp pour Android et iOS, nouvelle ', '<p><img src=\"https://ae01.alicdn.com/kf/S0468c962445e4a29b096bff8e754b7e0D.jpg\"></p>', '299.00', 350, 0x796573, 25, 'watch', 0x31, 'electronic watch', 'electronic watch', 'watch, electronic', '2023-03-13 12:00:31', '2023-03-13 12:00:31'),
(2, '2023-03-13-21-22-54-3c7cd8f6.jpg', 'tshirt', '<p>Hello, World!</p>', '250.00', 360, 0x796573, 100, 'tshirt', 0x31, '', '', '', '2023-03-13 20:24:07', '2023-03-13 20:24:07'),
(3, '2023-03-14-13-58-02-a797153f.jpg', 'Multiplicateur de couple torsadé de 1/2 pouces, clé pour enlever les écrous de cosse, démontage des pneus de voiture, économie de trava', '<p><img src=\"https://ae01.alicdn.com/kf/H79f2dd87c6cd414cbeef313a9c9aed41a.jpg\" data-spm-anchor-id=\"a2g0o.detail.1000023.i0.4b763f7bjtKN1c\"><img src=\"https://ae01.alicdn.com/kf/H99c5e678fbf24b11a49d7129c4628543f.jpg\"></p>\n<p><img src=\"https://ae01.alicdn.com/kf/Hb633bcf358614b48b43d3b7f68d73962Y.jpg\"><img src=\"https://ae01.alicdn.com/kf/H4516e5b880474cd1b0b2a9b4e24c2467A.jpg\"><img src=\"https://ae01.alicdn.com/kf/H01aa9cdde28a42f7b14090ae7c133aa4b.jpg\"><img src=\"https://ae01.alicdn.com/kf/Hf2fa394eacfd431187116c746ade2aacd.jpg\"></p>', '450.00', 599, 0x6e6f, 15, 'tools', 0x31, '', '', '', '2023-03-14 12:58:53', '2023-03-14 12:58:53');

-- --------------------------------------------------------

--
-- Table structure for table `products_categories`
--

CREATE TABLE `products_categories` (
  `id` int(11) NOT NULL,
  `id_product` int(11) NOT NULL,
  `id_category` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `products_categories`
--

INSERT INTO `products_categories` (`id`, `id_product`, `id_category`) VALUES
(1, 1, 1),
(2, 1, 3),
(3, 2, 1),
(4, 2, 2),
(5, 3, 1);

-- --------------------------------------------------------

--
-- Table structure for table `product_images`
--

CREATE TABLE `product_images` (
  `id_product_image` int(11) NOT NULL,
  `image` varchar(45) DEFAULT NULL,
  `alt` varchar(45) DEFAULT NULL,
  `id_product` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `product_images`
--

INSERT INTO `product_images` (`id_product_image`, `image`, `alt`, `id_product`) VALUES
(1, '2023-03-13-12-55-58-a402c86a.jpg', NULL, 1),
(2, '2023-03-13-12-55-58-e9037143.jpg', NULL, 1),
(3, '2023-03-13-12-55-58-0c593c8f.jpg', NULL, 1),
(4, '2023-03-13-21-22-48-21acc6ef.jpg', NULL, 2),
(5, '2023-03-13-21-22-48-1e7ad732.jpg', NULL, 2),
(6, '2023-03-14-13-57-58-4455ae3c.jpg', NULL, 3),
(7, '2023-03-14-13-57-58-5006d427.jpg', NULL, 3),
(8, '2023-03-14-13-57-58-6a4d31e8.jpg', NULL, 3),
(9, '2023-03-14-13-57-58-1a9ec479.jpg', NULL, 3),
(10, '2023-03-14-13-57-58-f3dd3d7c.jpg', NULL, 3);

-- --------------------------------------------------------

--
-- Table structure for table `shop`
--

CREATE TABLE `shop` (
  `name` varchar(45) DEFAULT NULL,
  `logo` varchar(45) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `id_user` int(11) DEFAULT NULL,
  `email` varchar(45) DEFAULT NULL,
  `password` varchar(12) DEFAULT NULL,
  `role` varchar(12) DEFAULT NULL COMMENT 'admin/delivery/agent',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `id_user`, `email`, `password`, `role`, `created_at`, `updated_at`) VALUES
(4, 1, 'root', '0000', 'admin', '2023-03-13 11:49:59', '2023-03-13 11:49:59'),
(5, 1, 'OJ563247@qissarya.ma', '0000', 'deliverer', '2023-03-17 14:48:11', '2023-03-17 23:26:40');

-- --------------------------------------------------------

--
-- Table structure for table `variants`
--

CREATE TABLE `variants` (
  `id_variant` int(11) NOT NULL,
  `variant` varchar(45) DEFAULT NULL,
  `quantity` int(11) DEFAULT NULL,
  `price` int(11) DEFAULT NULL,
  `image` varchar(45) DEFAULT NULL,
  `id_product` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `variants`
--

INSERT INTO `variants` (`id_variant`, `variant`, `quantity`, `price`, `image`, `id_product`) VALUES
(1, '{\"color\":\"pink\"}', 299, 20, '2023-03-13-12-55-58-a402c86a.jpg', 1),
(2, '{\"color\":\"blue\"}', 299, 20, '2023-03-13-12-55-58-e9037143.jpg', 1),
(3, '{\"color\":\"black\"}', 299, 20, '2023-03-13-12-55-58-0c593c8f.jpg', 1),
(4, '{\"size\":\"small\",\"Color\":\"red\"}', 250, 10, '2023-03-13-21-22-48-21acc6ef.jpg', 2),
(5, '{\"size\":\"small\",\"Color\":\"white\"}', 250, 10, '2023-03-13-21-22-48-1e7ad732.jpg', 2),
(6, '{\"size\":\"medium\",\"Color\":\"red\"}', 250, 10, '2023-03-13-21-22-48-21acc6ef.jpg', 2),
(7, '{\"size\":\"medium\",\"Color\":\"white\"}', 250, 10, '2023-03-13-21-22-48-1e7ad732.jpg', 2),
(8, '{\"size\":\"large\",\"Color\":\"red\"}', 250, 10, '2023-03-13-21-22-48-21acc6ef.jpg', 2),
(9, '{\"size\":\"large\",\"Color\":\"white\"}', 250, 10, '2023-03-13-21-22-48-1e7ad732.jpg', 2);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `areas`
--
ALTER TABLE `areas`
  ADD PRIMARY KEY (`id_area`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id_category`);

--
-- Indexes for table `clients_packs`
--
ALTER TABLE `clients_packs`
  ADD PRIMARY KEY (`id_client_pack`),
  ADD KEY `fk_clients_packs_cities1_idx` (`id_area`);

--
-- Indexes for table `deliverers`
--
ALTER TABLE `deliverers`
  ADD PRIMARY KEY (`id_deliverer`),
  ADD KEY `fk_deliverers_cities1_idx` (`id_area`);

--
-- Indexes for table `deliverer_packs`
--
ALTER TABLE `deliverer_packs`
  ADD PRIMARY KEY (`id_deliverer_pack`),
  ADD KEY `fk_deliverer_packs_deliverers1_idx` (`id_deliverer`);

--
-- Indexes for table `deliveries`
--
ALTER TABLE `deliveries`
  ADD PRIMARY KEY (`id_delivery`),
  ADD KEY `fk_deliveries_deliverers1` (`id_deliverer`),
  ADD KEY `fk_deliveries_orders1` (`id_order`),
  ADD KEY `fk_deliveries_invoices1` (`id_invoice`);

--
-- Indexes for table `invoices`
--
ALTER TABLE `invoices`
  ADD PRIMARY KEY (`id_invoice`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id_notification`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id_order`),
  ADD KEY `fk_orders_deliverer_packs1_idx` (`id_deliverer_pack`),
  ADD KEY `fk_orders_products1_idx` (`id_product`),
  ADD KEY `fk_orders_clients_packs1_idx` (`id_client_pack`),
  ADD KEY `fk_orders_variants1_idx` (`id_variant`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id_product`);

--
-- Indexes for table `products_categories`
--
ALTER TABLE `products_categories`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_products_categories_categories1_idx` (`id_category`),
  ADD KEY `fk_products_categories_products1` (`id_product`);

--
-- Indexes for table `product_images`
--
ALTER TABLE `product_images`
  ADD PRIMARY KEY (`id_product_image`),
  ADD KEY `fk_product_images_products1_idx` (`id_product`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `variants`
--
ALTER TABLE `variants`
  ADD PRIMARY KEY (`id_variant`),
  ADD UNIQUE KEY `id_variant_UNIQUE` (`id_variant`),
  ADD KEY `fk_variants_products1_idx` (`id_product`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `areas`
--
ALTER TABLE `areas`
  MODIFY `id_area` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id_category` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `clients_packs`
--
ALTER TABLE `clients_packs`
  MODIFY `id_client_pack` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `deliverers`
--
ALTER TABLE `deliverers`
  MODIFY `id_deliverer` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `deliverer_packs`
--
ALTER TABLE `deliverer_packs`
  MODIFY `id_deliverer_pack` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `deliveries`
--
ALTER TABLE `deliveries`
  MODIFY `id_delivery` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `invoices`
--
ALTER TABLE `invoices`
  MODIFY `id_invoice` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id_notification` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id_order` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id_product` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `products_categories`
--
ALTER TABLE `products_categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `product_images`
--
ALTER TABLE `product_images`
  MODIFY `id_product_image` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `variants`
--
ALTER TABLE `variants`
  MODIFY `id_variant` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `clients_packs`
--
ALTER TABLE `clients_packs`
  ADD CONSTRAINT `fk_clients_packs_cities1` FOREIGN KEY (`id_area`) REFERENCES `areas` (`id_area`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `deliverers`
--
ALTER TABLE `deliverers`
  ADD CONSTRAINT `fk_deliverers_cities1` FOREIGN KEY (`id_area`) REFERENCES `areas` (`id_area`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `deliverer_packs`
--
ALTER TABLE `deliverer_packs`
  ADD CONSTRAINT `fk_deliverer_packs_deliverers1` FOREIGN KEY (`id_deliverer`) REFERENCES `deliverers` (`id_deliverer`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `deliveries`
--
ALTER TABLE `deliveries`
  ADD CONSTRAINT `fk_deliveries_deliverers1` FOREIGN KEY (`id_deliverer`) REFERENCES `deliverers` (`id_deliverer`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_deliveries_invoices1` FOREIGN KEY (`id_invoice`) REFERENCES `invoices` (`id_invoice`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_deliveries_orders1` FOREIGN KEY (`id_order`) REFERENCES `orders` (`id_order`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `fk_orders_clients_packs1` FOREIGN KEY (`id_client_pack`) REFERENCES `clients_packs` (`id_client_pack`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_orders_deliverer_packs1` FOREIGN KEY (`id_deliverer_pack`) REFERENCES `deliverer_packs` (`id_deliverer_pack`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_orders_products1` FOREIGN KEY (`id_product`) REFERENCES `products` (`id_product`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_orders_variants1` FOREIGN KEY (`id_variant`) REFERENCES `variants` (`id_variant`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `products_categories`
--
ALTER TABLE `products_categories`
  ADD CONSTRAINT `fk_products_categories_categories1` FOREIGN KEY (`id_category`) REFERENCES `categories` (`id_category`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_products_categories_products1` FOREIGN KEY (`id_product`) REFERENCES `products` (`id_product`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `product_images`
--
ALTER TABLE `product_images`
  ADD CONSTRAINT `fk_product_images_products1` FOREIGN KEY (`id_product`) REFERENCES `products` (`id_product`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `variants`
--
ALTER TABLE `variants`
  ADD CONSTRAINT `fk_variants_products1` FOREIGN KEY (`id_product`) REFERENCES `products` (`id_product`) ON DELETE NO ACTION ON UPDATE NO ACTION;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
