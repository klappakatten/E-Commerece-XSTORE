-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 05, 2023 at 11:28 PM
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
-- Database: `xstore`
--
CREATE DATABASE IF NOT EXISTS `xstore` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `xstore`;

-- --------------------------------------------------------

--
-- Table structure for table `category`
--

CREATE TABLE `category` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `description` text DEFAULT NULL,
  `image` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `category`
--

INSERT INTO `category` (`id`, `name`, `description`, `image`) VALUES(1, 'Computer', NULL, 'icons/compicon.png');
INSERT INTO `category` (`id`, `name`, `description`, `image`) VALUES(2, 'Console', NULL, 'icons/consoleicon.png');
INSERT INTO `category` (`id`, `name`, `description`, `image`) VALUES(3, 'Monitor', NULL, 'icons/monitoricon.png');
INSERT INTO `category` (`id`, `name`, `description`, `image`) VALUES(4, 'Mouse', NULL, 'icons/mouseicon.png');
INSERT INTO `category` (`id`, `name`, `description`, `image`) VALUES(5, 'Keyboard', NULL, 'icons/keyboardicon.png');
INSERT INTO `category` (`id`, `name`, `description`, `image`) VALUES(6, 'Games', NULL, 'icons/gameicon.png');
INSERT INTO `category` (`id`, `name`, `description`, `image`) VALUES(11, 'Misc', NULL, 'icons/defaulticon.png');

-- --------------------------------------------------------

--
-- Table structure for table `orderdetails`
--

CREATE TABLE `orderdetails` (
  `order_details_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `product_qty` int(11) NOT NULL,
  `product_price` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `subtotal` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orderdetails`
--

INSERT INTO `orderdetails` (`order_details_id`, `product_id`, `product_qty`, `product_price`, `order_id`, `subtotal`) VALUES(1, 1, 2, 10000, 1, 20000);
INSERT INTO `orderdetails` (`order_details_id`, `product_id`, `product_qty`, `product_price`, `order_id`, `subtotal`) VALUES(2, 1, 2, 10000, 2, 20000);
INSERT INTO `orderdetails` (`order_details_id`, `product_id`, `product_qty`, `product_price`, `order_id`, `subtotal`) VALUES(12, 1, 1, 10000, 31, 10000);
INSERT INTO `orderdetails` (`order_details_id`, `product_id`, `product_qty`, `product_price`, `order_id`, `subtotal`) VALUES(13, 16, 1, 5999, 31, 5999);
INSERT INTO `orderdetails` (`order_details_id`, `product_id`, `product_qty`, `product_price`, `order_id`, `subtotal`) VALUES(14, 3, 1, 700, 32, 700);
INSERT INTO `orderdetails` (`order_details_id`, `product_id`, `product_qty`, `product_price`, `order_id`, `subtotal`) VALUES(15, 5, 3, 250, 33, 750);
INSERT INTO `orderdetails` (`order_details_id`, `product_id`, `product_qty`, `product_price`, `order_id`, `subtotal`) VALUES(16, 3, 1, 700, 34, 700);
INSERT INTO `orderdetails` (`order_details_id`, `product_id`, `product_qty`, `product_price`, `order_id`, `subtotal`) VALUES(17, 5, 1, 250, 35, 250);

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `order_id` int(11) NOT NULL,
  `date` date NOT NULL,
  `total` int(11) NOT NULL,
  `user_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`order_id`, `date`, `total`, `user_id`) VALUES(1, '2023-04-26', 20000, 8);
INSERT INTO `orders` (`order_id`, `date`, `total`, `user_id`) VALUES(2, '2023-04-27', 20000, 19);
INSERT INTO `orders` (`order_id`, `date`, `total`, `user_id`) VALUES(31, '2023-05-03', 15999, 23);
INSERT INTO `orders` (`order_id`, `date`, `total`, `user_id`) VALUES(32, '2023-05-05', 700, 9);
INSERT INTO `orders` (`order_id`, `date`, `total`, `user_id`) VALUES(33, '2023-05-05', 750, 23);
INSERT INTO `orders` (`order_id`, `date`, `total`, `user_id`) VALUES(34, '2023-05-05', 700, 23);
INSERT INTO `orders` (`order_id`, `date`, `total`, `user_id`) VALUES(35, '2023-05-05', 250, 23);

-- --------------------------------------------------------

--
-- Table structure for table `product`
--

CREATE TABLE `product` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `description` text DEFAULT NULL,
  `image` varchar(50) DEFAULT 'icons/stockimage.png',
  `price` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `category` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `product`
--

INSERT INTO `product` (`id`, `name`, `description`, `image`, `price`, `quantity`, `category`) VALUES(1, 'Computer', 'Cool super PC', 'icons/stockimage.png', 10000, 1, 'Computer');
INSERT INTO `product` (`id`, `name`, `description`, `image`, `price`, `quantity`, `category`) VALUES(2, 'GameBoy', 'Handheld console made by Nintendo', 'icons/stockimage.png', 500, 5, 'Console');
INSERT INTO `product` (`id`, `name`, `description`, `image`, `price`, `quantity`, `category`) VALUES(3, 'GameBoy Advanced', 'Handheld console made by Nintendo', 'icons/stockimage.png', 700, 2, 'Console');
INSERT INTO `product` (`id`, `name`, `description`, `image`, `price`, `quantity`, `category`) VALUES(4, 'Alpaca Gaming Mouse', 'A lightweight mouse from the mysterious Q Company', 'icons/stockimage.png', 300, 1, 'Mouse');
INSERT INTO `product` (`id`, `name`, `description`, `image`, `price`, `quantity`, `category`) VALUES(5, 'Pokemon', 'You got to catch em all', 'icons/stockimage.png', 250, 7, 'Games');
INSERT INTO `product` (`id`, `name`, `description`, `image`, `price`, `quantity`, `category`) VALUES(8, 'Zelda', 'A link to the past', 'icons/stockimage.png', 250, 3, 'Games');
INSERT INTO `product` (`id`, `name`, `description`, `image`, `price`, `quantity`, `category`) VALUES(10, 'Zelda: A link to the past', 'Best game ever', 'icons/stockimage.png', 333, 2, 'Games');
INSERT INTO `product` (`id`, `name`, `description`, `image`, `price`, `quantity`, `category`) VALUES(11, 'X Console', 'Mysterious console of dreams', 'icons/stockimage.png', 5300, 3, 'Console');
INSERT INTO `product` (`id`, `name`, `description`, `image`, `price`, `quantity`, `category`) VALUES(12, 'Coder Keyboard', '+5 stats to PHP', 'icons/stockimage.png', 9999, 1, 'Keyboard');
INSERT INTO `product` (`id`, `name`, `description`, `image`, `price`, `quantity`, `category`) VALUES(13, 'Creative Monitor', 'This monitor is great for creating Art, Great colors and accuracy!', 'icons/stockimage.png', 5250, 2, 'Monitor');
INSERT INTO `product` (`id`, `name`, `description`, `image`, `price`, `quantity`, `category`) VALUES(15, 'World of Warcraft', 'Latest expansion included', 'icons/stockimage.png', 499, 5, 'Games');
INSERT INTO `product` (`id`, `name`, `description`, `image`, `price`, `quantity`, `category`) VALUES(16, 'Laptop Z', 'Cheap alternative to the super computer', 'icons/stockimage.png', 5999, 1, 'Computer');

-- --------------------------------------------------------

--
-- Table structure for table `promotedproduct`
--

CREATE TABLE `promotedproduct` (
  `id` int(11) NOT NULL,
  `promotedid` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `promotedproduct`
--

INSERT INTO `promotedproduct` (`id`, `promotedid`) VALUES(5, 1);
INSERT INTO `promotedproduct` (`id`, `promotedid`) VALUES(6, 2);
INSERT INTO `promotedproduct` (`id`, `promotedid`) VALUES(7, 3);
INSERT INTO `promotedproduct` (`id`, `promotedid`) VALUES(8, 4);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `email` varchar(64) NOT NULL,
  `password` varchar(60) NOT NULL,
  `fname` varchar(50) NOT NULL,
  `lname` varchar(50) NOT NULL,
  `adress` varchar(100) NOT NULL,
  `postcode` int(11) NOT NULL,
  `country` varchar(30) NOT NULL,
  `city` varchar(90) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `admin` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `email`, `password`, `fname`, `lname`, `adress`, `postcode`, `country`, `city`, `phone`, `admin`) VALUES(8, 'martinnyman.mail@gmail.com', '$2y$10$myoEd9yn2v8KK8Sk/oyYNOETvSK/to/zszGbDWtuDdKdn3.YHXz1i', 'Martin', 'Nyman', 'Tegelbruksvägen 5', 18432, 'SE', 'Åkersberga', '739758810', 1);
INSERT INTO `users` (`user_id`, `email`, `password`, `fname`, `lname`, `adress`, `postcode`, `country`, `city`, `phone`, `admin`) VALUES(9, 'kleptokatten@gmail.com', '$2y$10$GVGp9k1ItoYCT1apVO3tnubve/6Z1h.znp0NO/TGMIjjOGrsLxzOi', 'Martin', 'Nyman', 'Tegelbruksvägen 5', 18432, 'SE', 'Åkersberga', '739758810', 0);
INSERT INTO `users` (`user_id`, `email`, `password`, `fname`, `lname`, `adress`, `postcode`, `country`, `city`, `phone`, `admin`) VALUES(19, 'clapthecat@gmail.com', '$2y$10$O2Go0ulwQDgmPR/c1WNYne40xTj0/lDYgA4fDlHczq7WJ0ZaX0jDm', 'Erik', 'Larsson', 'Pitvägen 33', 883283, 'SE', 'Luleå', '7388433', 0);
INSERT INTO `users` (`user_id`, `email`, `password`, `fname`, `lname`, `adress`, `postcode`, `country`, `city`, `phone`, `admin`) VALUES(20, 'Stefan@larson.com', '$2y$10$AYU/2e67rqVM3MyRntYpOuvyVWU/pxY1qJzvs4DuimbhTL1fGnEvW', 'Erik', 'Larsson', 'Kogatan 3', 18432, 'SE', 'Luleå', '73874323', 0);
INSERT INTO `users` (`user_id`, `email`, `password`, `fname`, `lname`, `adress`, `postcode`, `country`, `city`, `phone`, `admin`) VALUES(22, 'katten@martinnyman.dev', '$2y$10$yJftDxAGzJAtwynNvT1.eeDTjMxQvjPrCnPK4yiNXjwuTodyLWQ7W', 'Martin', 'asdasd', 'Tegelvägen 5', 23283, 'SE', 'SADadsad', '9349438834', 0);
INSERT INTO `users` (`user_id`, `email`, `password`, `fname`, `lname`, `adress`, `postcode`, `country`, `city`, `phone`, `admin`) VALUES(23, 'administrator@admin.com', '$2y$10$KsAFgNlGQcLc/ccW.JUc1O9R.zoL2WeDZNw6clHi8pu6jpiN6W8HS', 'admin', 'admin', 'adminv5', 11111, 'SE', 'Stockholm', '876556', 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `category`
--
ALTER TABLE `category`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indexes for table `orderdetails`
--
ALTER TABLE `orderdetails`
  ADD PRIMARY KEY (`order_details_id`),
  ADD KEY `product_id` (`product_id`),
  ADD KEY `order_id` (`order_id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`order_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `product`
--
ALTER TABLE `product`
  ADD PRIMARY KEY (`id`),
  ADD KEY `category` (`category`);

--
-- Indexes for table `promotedproduct`
--
ALTER TABLE `promotedproduct`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `promotedid` (`promotedid`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`) USING BTREE,
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `category`
--
ALTER TABLE `category`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `orderdetails`
--
ALTER TABLE `orderdetails`
  MODIFY `order_details_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `order_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT for table `product`
--
ALTER TABLE `product`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `promotedproduct`
--
ALTER TABLE `promotedproduct`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `orderdetails`
--
ALTER TABLE `orderdetails`
  ADD CONSTRAINT `orderdetails_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `product` (`id`),
  ADD CONSTRAINT `orderdetails_ibfk_3` FOREIGN KEY (`order_id`) REFERENCES `orders` (`order_id`);

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `product`
--
ALTER TABLE `product`
  ADD CONSTRAINT `product_ibfk_1` FOREIGN KEY (`category`) REFERENCES `category` (`name`);

--
-- Constraints for table `promotedproduct`
--
ALTER TABLE `promotedproduct`
  ADD CONSTRAINT `promotedproduct_ibfk_1` FOREIGN KEY (`promotedid`) REFERENCES `product` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
