-- phpMyAdmin SQL Dump
-- version 4.9.5
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Nov 07, 2020 at 02:57 PM
-- Server version: 5.7.24
-- PHP Version: 7.4.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_pos`
--
CREATE DATABASE IF NOT EXISTS `db_pos` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
USE `db_pos`;

-- --------------------------------------------------------

--
-- Table structure for table `assignments`
--

DROP TABLE IF EXISTS `assignments`;
CREATE TABLE `assignments` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `branch_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- RELATIONSHIPS FOR TABLE `assignments`:
--   `user_id`
--       `users` -> `id`
--   `branch_id`
--       `branches` -> `id`
--

--
-- Dumping data for table `assignments`
--

INSERT INTO `assignments` (`id`, `user_id`, `branch_id`) VALUES
(1, 1, 1),
(2, 1, 2),
(3, 2, 3),
(4, 3, 1);

-- --------------------------------------------------------

--
-- Table structure for table `branches`
--

DROP TABLE IF EXISTS `branches`;
CREATE TABLE `branches` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `address` varchar(255) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `modified_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- RELATIONSHIPS FOR TABLE `branches`:
--

--
-- Dumping data for table `branches`
--

INSERT INTO `branches` (`id`, `name`, `address`, `created_at`, `modified_at`) VALUES
(1, 'Branch A', '3916 Grim Avenue San Diego California 92103', '2020-11-02 09:48:58', '2020-11-02 09:48:58'),
(2, 'Branch B', '142 Fleming Street Montgomery Alabama 36107', '2020-11-02 09:48:58', '2020-11-02 09:48:58'),
(3, 'Branch C', '3599 Joy Lane Woodland Hills California 91303', '2020-11-02 09:56:11', '2020-11-02 09:56:11'),
(4, 'Branch D', '2078 Oliver Street Fort Worth Texas 76118', '2020-11-02 09:56:11', '2020-11-02 09:56:11'),
(5, 'Branch E', '1961 Blackwell Street BATTLE CREEK Michigan 49016', '2020-11-02 09:56:11', '2020-11-02 09:56:11'),
(6, 'Branch F', '1645 Norma Avenue FALLS CITY Nebraska 68355', '2020-11-02 09:56:11', '2020-11-02 09:56:11'),
(7, 'Branch G', '3842 Stark Hollow Road Denver Colorado 80221', '2020-11-02 09:56:11', '2020-11-02 09:56:11'),
(8, 'Branch H', '234 Tenmile San Diego California 92117', '2020-11-02 09:56:11', '2020-11-02 09:56:11'),
(9, 'Branch I', '2793 Southside Lane California 92614', '2020-11-02 09:56:11', '2020-11-02 09:56:11'),
(10, 'Branch J', '1918 Augusta Park Huntington West Virginia 25701', '2020-11-02 09:56:11', '2020-11-02 09:56:11'),
(11, 'Branch K', '3215 Green Acres Road Las Vegas Nevada 89114', '2020-11-02 09:56:11', '2020-11-02 09:56:11'),
(12, 'Branch L', '1758 Woodbridge Lane Detroit Michigan 48219', '2020-11-02 09:56:11', '2020-11-02 09:56:11'),
(13, 'Branch M', '2181 Villa Drive Mishawaka Indiana 46544', '2020-11-02 09:56:11', '2020-11-02 09:56:11'),
(14, 'Branch N', '3817 Peck Street TULSA Oklahoma 74147', '2020-11-02 09:56:11', '2020-11-02 09:56:11'),
(15, 'Branch O', '4149 Selah Way Island Pond Vermont 05846', '2020-11-02 09:56:11', '2020-11-02 09:56:11'),
(16, 'Branch P', '4439 Langtown Road Andrew Iowa 52030', '2020-11-02 10:28:17', '2020-11-02 10:28:17'),
(17, 'Branch Q', '4021 Little Acres Lane Clayton Illinois', '2020-11-02 10:28:17', '2020-11-02 10:28:17'),
(18, 'Branch R', '1531 Rivendell Drive MAGNOLIA Minnesota 56158', '2020-11-02 10:28:17', '2020-11-02 10:28:17'),
(19, 'Branch S', '57 Gladwell Street Collierville Tennessee', '2020-11-02 10:28:17', '2020-11-02 10:28:17'),
(20, 'Branch T', '4048 Asylum Avenue Derby Connecticut', '2020-11-02 10:28:17', '2020-11-02 10:28:17'),
(21, 'Branch U', '1453 Oliver Street Fort Worth Texas 76147\r\n', '2020-11-02 10:28:17', '2020-11-02 10:28:17'),
(22, 'Branch V', '106 Court Street Fairview Heights Missouri 62208', '2020-11-02 10:28:17', '2020-11-02 10:28:17'),
(23, 'Branch W', '3379 Grant Street MOUNT OLIVE Alabama', '2020-11-02 10:28:17', '2020-11-02 10:28:17'),
(24, 'Branch X', '198 Jarvis Street Buffalo New York 14216', '2020-11-02 10:28:17', '2020-11-02 10:28:17'),
(25, 'Branch Y', '3157 Ingram Street Dayton Ohio 45408', '2020-11-02 10:28:17', '2020-11-02 10:28:17'),
(26, 'Branch Z', '3792 Prospect Valley Road Los Angeles California 90046', '2020-11-02 10:28:17', '2020-11-02 10:28:17');

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

DROP TABLE IF EXISTS `products`;
CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `category` varchar(255) NOT NULL,
  `sell_price` int(11) NOT NULL,
  `buy_price` int(11) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `modified_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- RELATIONSHIPS FOR TABLE `products`:
--

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `name`, `category`, `sell_price`, `buy_price`, `created_at`, `modified_at`) VALUES
(1, 'Icecream', 'SNACKS', 50, 30, '2020-11-05 23:50:46', '2020-11-05 23:50:46'),
(3, 'Apple Juice', 'DRINKS', 5, 3, '2020-11-07 21:16:14', '2020-11-07 21:16:14');

--
-- Triggers `products`
--
DROP TRIGGER IF EXISTS `Add stocks to each product`;
DELIMITER $$
CREATE TRIGGER `Add stocks to each product` AFTER INSERT ON `products` FOR EACH ROW BEGIN
	INSERT INTO stocks (product_id, branch_id) 
    SELECT NEW.id, b.id FROM branches b;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `stocks`
--

DROP TABLE IF EXISTS `stocks`;
CREATE TABLE `stocks` (
  `id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `branch_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL DEFAULT '0',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `modified_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- RELATIONSHIPS FOR TABLE `stocks`:
--   `branch_id`
--       `branches` -> `id`
--   `product_id`
--       `products` -> `id`
--

--
-- Dumping data for table `stocks`
--

INSERT INTO `stocks` (`id`, `product_id`, `branch_id`, `quantity`, `created_at`, `modified_at`) VALUES
(1, 1, 1, 0, '2020-11-05 23:50:46', '2020-11-05 23:50:46'),
(2, 1, 2, 0, '2020-11-05 23:50:46', '2020-11-05 23:50:46'),
(3, 1, 3, 0, '2020-11-05 23:50:46', '2020-11-05 23:50:46'),
(4, 1, 4, 0, '2020-11-05 23:50:46', '2020-11-05 23:50:46'),
(5, 1, 5, 0, '2020-11-05 23:50:46', '2020-11-05 23:50:46'),
(6, 1, 6, 0, '2020-11-05 23:50:46', '2020-11-05 23:50:46'),
(7, 1, 7, 0, '2020-11-05 23:50:46', '2020-11-05 23:50:46'),
(8, 1, 8, 0, '2020-11-05 23:50:46', '2020-11-05 23:50:46'),
(9, 1, 9, 0, '2020-11-05 23:50:46', '2020-11-05 23:50:46'),
(10, 1, 10, 0, '2020-11-05 23:50:46', '2020-11-05 23:50:46'),
(11, 1, 11, 0, '2020-11-05 23:50:46', '2020-11-05 23:50:46'),
(12, 1, 12, 0, '2020-11-05 23:50:46', '2020-11-05 23:50:46'),
(13, 1, 13, 0, '2020-11-05 23:50:46', '2020-11-05 23:50:46'),
(14, 1, 14, 0, '2020-11-05 23:50:46', '2020-11-05 23:50:46'),
(15, 1, 15, 0, '2020-11-05 23:50:46', '2020-11-05 23:50:46'),
(16, 1, 16, 0, '2020-11-05 23:50:46', '2020-11-05 23:50:46'),
(17, 1, 17, 0, '2020-11-05 23:50:46', '2020-11-05 23:50:46'),
(18, 1, 18, 0, '2020-11-05 23:50:46', '2020-11-05 23:50:46'),
(19, 1, 19, 0, '2020-11-05 23:50:46', '2020-11-05 23:50:46'),
(20, 1, 20, 0, '2020-11-05 23:50:46', '2020-11-05 23:50:46'),
(21, 1, 21, 0, '2020-11-05 23:50:46', '2020-11-05 23:50:46'),
(22, 1, 22, 0, '2020-11-05 23:50:46', '2020-11-05 23:50:46'),
(23, 1, 23, 0, '2020-11-05 23:50:46', '2020-11-05 23:50:46'),
(24, 1, 24, 0, '2020-11-05 23:50:46', '2020-11-05 23:50:46'),
(25, 1, 25, 0, '2020-11-05 23:50:46', '2020-11-05 23:50:46'),
(26, 1, 26, 0, '2020-11-05 23:50:46', '2020-11-05 23:50:46'),
(58, 3, 1, 0, '2020-11-07 21:16:14', '2020-11-07 21:16:14'),
(59, 3, 2, 0, '2020-11-07 21:16:14', '2020-11-07 21:16:14'),
(60, 3, 3, 0, '2020-11-07 21:16:14', '2020-11-07 21:16:14'),
(61, 3, 4, 0, '2020-11-07 21:16:14', '2020-11-07 21:16:14'),
(62, 3, 5, 0, '2020-11-07 21:16:14', '2020-11-07 21:16:14'),
(63, 3, 6, 0, '2020-11-07 21:16:14', '2020-11-07 21:16:14'),
(64, 3, 7, 0, '2020-11-07 21:16:14', '2020-11-07 21:16:14'),
(65, 3, 8, 0, '2020-11-07 21:16:14', '2020-11-07 21:16:14'),
(66, 3, 9, 0, '2020-11-07 21:16:14', '2020-11-07 21:16:14'),
(67, 3, 10, 0, '2020-11-07 21:16:14', '2020-11-07 21:16:14'),
(68, 3, 11, 0, '2020-11-07 21:16:14', '2020-11-07 21:16:14'),
(69, 3, 12, 0, '2020-11-07 21:16:14', '2020-11-07 21:16:14'),
(70, 3, 13, 0, '2020-11-07 21:16:14', '2020-11-07 21:16:14'),
(71, 3, 14, 0, '2020-11-07 21:16:14', '2020-11-07 21:16:14'),
(72, 3, 15, 0, '2020-11-07 21:16:14', '2020-11-07 21:16:14'),
(73, 3, 16, 0, '2020-11-07 21:16:14', '2020-11-07 21:16:14'),
(74, 3, 17, 0, '2020-11-07 21:16:14', '2020-11-07 21:16:14'),
(75, 3, 18, 0, '2020-11-07 21:16:14', '2020-11-07 21:16:14'),
(76, 3, 19, 0, '2020-11-07 21:16:14', '2020-11-07 21:16:14'),
(77, 3, 20, 0, '2020-11-07 21:16:14', '2020-11-07 21:16:14'),
(78, 3, 21, 0, '2020-11-07 21:16:14', '2020-11-07 21:16:14'),
(79, 3, 22, 0, '2020-11-07 21:16:14', '2020-11-07 21:16:14'),
(80, 3, 23, 0, '2020-11-07 21:16:14', '2020-11-07 21:16:14'),
(81, 3, 24, 0, '2020-11-07 21:16:14', '2020-11-07 21:16:14'),
(82, 3, 25, 0, '2020-11-07 21:16:14', '2020-11-07 21:16:14'),
(83, 3, 26, 0, '2020-11-07 21:16:14', '2020-11-07 21:16:14');

-- --------------------------------------------------------

--
-- Table structure for table `transactions`
--

DROP TABLE IF EXISTS `transactions`;
CREATE TABLE `transactions` (
  `id` int(11) NOT NULL,
  `stock_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- RELATIONSHIPS FOR TABLE `transactions`:
--   `stock_id`
--       `stocks` -> `id`
--

--
-- Triggers `transactions`
--
DROP TRIGGER IF EXISTS `Modify stock`;
DELIMITER $$
CREATE TRIGGER `Modify stock` BEFORE INSERT ON `transactions` FOR EACH ROW BEGIN
	SELECT s.quantity INTO @stock_quantity FROM stocks s WHERE s.id=NEW.stock_id;
    IF @stock_quantity - NEW.quantity < 0 THEN
    	SIGNAL SQLSTATE '45000'
          SET MESSAGE_TEXT = 'Cannot decrease more stock than currently available!';
	ELSE 
    	UPDATE stocks s SET s.quantity = s.quantity - NEW.quantity WHERE s.id=NEW.stock_id;
    END IF;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `first_name` varchar(255) NOT NULL,
  `last_name` varchar(255) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `position` varchar(255) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `modified_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- RELATIONSHIPS FOR TABLE `users`:
--

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `first_name`, `last_name`, `username`, `password`, `position`, `created_at`, `modified_at`) VALUES
(1, 'test', 'test', 'a', '$2y$10$Rftr4xlRfvblhUJG/LE7MOPvHEaYZhfI0/lqSumpRXO88BztkByV6', 'STAFF', '2020-11-04 01:09:32', '2020-11-04 01:09:32'),
(2, 'abac', 'transform', 'b', '$2y$10$ycH4/zhkNOsYDcHz4bxH0eiXg2zP2/bvVMFPe.i2v3VLEvpYRmL/m', 'MANAGER', '2020-11-04 01:14:19', '2020-11-04 01:14:19'),
(3, 'Frederico', 'Kami-sama', 'c', '$2y$10$kR5MUsWDnnit/lJG3oFCB.AoBeU.cbQ0YfWD8AmX/3aIdR0QfGCyS', 'MANAGER', '2020-11-05 23:23:42', '2020-11-05 23:23:42');

--
-- Triggers `users`
--
DROP TRIGGER IF EXISTS `Add default branch`;
DELIMITER $$
CREATE TRIGGER `Add default branch` AFTER INSERT ON `users` FOR EACH ROW INSERT INTO assignments (user_id, branch_id) VALUES (NEW.id, 1)
$$
DELIMITER ;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `assignments`
--
ALTER TABLE `assignments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `uid` (`user_id`),
  ADD KEY `bid` (`branch_id`);

--
-- Indexes for table `branches`
--
ALTER TABLE `branches`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `BRANCH` (`name`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `NAME` (`name`);

--
-- Indexes for table `stocks`
--
ALTER TABLE `stocks`
  ADD PRIMARY KEY (`id`),
  ADD KEY `branch_id` (`branch_id`) USING BTREE,
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `transactions`
--
ALTER TABLE `transactions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `stock_id` (`stock_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `USERNAME` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `assignments`
--
ALTER TABLE `assignments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `branches`
--
ALTER TABLE `branches`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `stocks`
--
ALTER TABLE `stocks`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=89;

--
-- AUTO_INCREMENT for table `transactions`
--
ALTER TABLE `transactions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `assignments`
--
ALTER TABLE `assignments`
  ADD CONSTRAINT `assignments_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `assignments_ibfk_2` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`);

--
-- Constraints for table `stocks`
--
ALTER TABLE `stocks`
  ADD CONSTRAINT `stocks_ibfk_1` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `stocks_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `transactions`
--
ALTER TABLE `transactions`
  ADD CONSTRAINT `transactions_ibfk_1` FOREIGN KEY (`stock_id`) REFERENCES `stocks` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
