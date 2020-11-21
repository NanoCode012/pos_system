-- phpMyAdmin SQL Dump
-- version 4.9.5
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Nov 21, 2020 at 07:32 PM
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

DELIMITER $$
--
-- Procedures
--
DROP PROCEDURE IF EXISTS `create_branch`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `create_branch` (IN `user_id` INT, IN `branch_name` VARCHAR(255), IN `branch_address` VARCHAR(255))  NO SQL
BEGIN
	INSERT INTO branches (name, address) VALUES (branch_name, branch_address);
    INSERT INTO assignments (assignments.user_id, branch_id) VALUES (user_id, (SELECT LAST_INSERT_ID()));
END$$

DROP PROCEDURE IF EXISTS `Get dashboard`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `Get dashboard` (IN `start_date` DATE, IN `end_date` DATE, IN `frequency` VARCHAR(255), IN `user_id` INT)  NO SQL
BEGIN
	SET @start_date = start_date;
    SET @end_date = end_date;
	CALL `get_dates_range`(@start_date, @end_date, frequency, @freq_t);
    CALL `get_dates_range_prev`(@start_date, @end_date, @freq_t, @past_start_date, @past_end_date);
    
    #SELECT @start_date, @end_date, @past_start_date, @past_end_date, @freq_t;
    
	SELECT `get_total_sales`(@start_date, @end_date, user_id) INTO @total_sales;
    SELECT `get_total_sales`(@past_start_date, @past_end_date, user_id) INTO @total_sales_prev;
    SELECT `get_ratio`(@total_sales, @total_sales_prev) INTO @total_sales_ratio;
    
    SELECT `get_total_profit`(@start_date, @end_date, user_id) INTO @total_profit;
    SELECT `get_total_profit`(@past_start_date, @past_end_date, user_id) INTO @total_profit_prev;
    SELECT `get_ratio`(@total_profit, @total_profit_prev) INTO @total_profit_ratio;
    
    SELECT `get_total_transactions`(@start_date, @end_date, user_id) INTO @total_transactions;
    SELECT `get_total_transactions`(@past_start_date, @past_end_date, user_id) INTO @total_transactions_prev;
    SELECT `get_ratio`(@total_transactions, @total_transactions_prev) INTO @total_transactions_ratio;
    
    SELECT `get_best_selling`(@start_date, @end_date, user_id) INTO @best_selling;
    SELECT `get_best_selling`(@past_start_date, @past_end_date, user_id) INTO @best_selling_prev;

    
    SELECT @total_sales, @total_profit, @total_transactions, @best_selling, @total_sales_ratio, @total_profit_ratio, @total_transactions_ratio, @best_selling_prev;
END$$

DROP PROCEDURE IF EXISTS `Get revenue time`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `Get revenue time` (IN `start_date` DATE, IN `end_date` DATE, IN `frequency` VARCHAR(255), IN `user_id` INT)  NO SQL
BEGIN
	SET @start_date = start_date;
    SET @end_date = end_date;
	CALL `get_dates_range`(@start_date, @end_date, frequency, @freq_t);
    
    SELECT date, SUM(profit_per_prod) AS revenue FROM (
    SELECT DATE(t.created_at) AS date, p.id, SUM(-1*t.quantity) AS num_sold, (p.sell_price-p.buy_price) AS profit_per_stock, SUM(-1*t.quantity)*(p.sell_price-p.buy_price) AS profit_per_prod FROM transactions t, stocks s, products p WHERE t.created_at BETWEEN @start_date AND @end_date AND t.quantity < 0 AND t.stock_id = s.id AND s.product_id = p.id AND s.branch_id IN (SELECT branch_id FROM assignments a WHERE a.user_id = user_id) GROUP BY DATE(t.created_at), p.id) AS prof GROUP BY date;
END$$

DROP PROCEDURE IF EXISTS `Get sales per category`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `Get sales per category` (IN `start_date` DATE, IN `end_date` DATE, IN `frequency` VARCHAR(255), IN `user_id` INT)  NO SQL
BEGIN
	SET @start_date = start_date;
    SET @end_date = end_date;
	CALL `get_dates_range`(@start_date, @end_date, frequency, @freq_t);
    
    SELECT p.category, SUM(t.quantity*(-1)) AS quantity FROM transactions t,  products p, stocks s WHERE t.quantity < 0 AND t.stock_id = s.id AND s.product_id = p.id AND s.branch_id IN (SELECT branch_id FROM assignments a WHERE a.user_id = user_id) AND t.created_at BETWEEN @start_date AND @end_date GROUP BY p.category;
END$$

DROP PROCEDURE IF EXISTS `Get sales time`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `Get sales time` (IN `start_date` DATE, IN `end_date` DATE, IN `frequency` VARCHAR(255), IN `user_id` INT)  NO SQL
BEGIN
	SET @start_date = start_date;
    SET @end_date = end_date;
	CALL `get_dates_range`(@start_date, @end_date, frequency, @freq_t);
    
    SELECT DATE(t.created_at) AS date, SUM(t.quantity*(-1)) AS num_sales FROM transactions t, stocks s WHERE t.created_at BETWEEN @start_date AND @end_date AND t.quantity < 0 AND t.stock_id = s.id AND s.branch_id IN (SELECT branch_id FROM assignments a WHERE a.user_id = user_id) GROUP BY DATE(t.created_at);
END$$

DROP PROCEDURE IF EXISTS `Get transactions time`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `Get transactions time` (IN `start_date` DATE, IN `end_date` DATE, IN `frequency` VARCHAR(255), IN `user_id` INT)  NO SQL
BEGIN
	SET @start_date = start_date;
    SET @end_date = end_date;
	CALL `get_dates_range`(@start_date, @end_date, frequency, @freq_t);
    
    SELECT DATE(t.created_at) AS date, COUNT(*) AS num_transactions FROM transactions t, stocks s WHERE t.created_at BETWEEN @start_date AND @end_date AND t.quantity < 0 AND t.stock_id = s.id AND s.branch_id IN (SELECT branch_id FROM assignments a WHERE a.user_id = user_id) GROUP BY DATE(t.created_at);
END$$

DROP PROCEDURE IF EXISTS `get_dates_range`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `get_dates_range` (INOUT `start_date` DATE, INOUT `end_date` DATE, IN `frequency` VARCHAR(255), OUT `freq_t` INT)  NO SQL
BEGIN
	IF NOT frequency = '' THEN
    	IF frequency = 'DAILY' THEN
            SET freq_t = 1;
        ELSEIF frequency = 'WEEKLY' THEN
            SET freq_t = 7;
        ELSEIF frequency = 'MONTHLY' THEN
            SET freq_t = 30;
        END IF;

        SET start_date = (SELECT TIMESTAMPADD(DAY, -(freq_t/2), NOW()));
        SET end_date = (SELECT TIMESTAMPADD(DAY, (freq_t/2), NOW()));
        
    ELSE 
    	SET start_date = start_date;
        SET end_date = end_date;
        SET freq_t = (SELECT TIMESTAMPDIFF(DAY,start_date,end_date));
        # throw error if end < start date
    END IF;
END$$

DROP PROCEDURE IF EXISTS `get_dates_range_prev`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `get_dates_range_prev` (IN `start` DATE, IN `end` DATE, IN `freq_t` INT, OUT `past_start` DATE, OUT `past_end` DATE)  NO SQL
BEGIN
    SET past_start = (SELECT TIMESTAMPADD(DAY, -freq_t, start));
    SET past_end = (SELECT TIMESTAMPADD(DAY, 0, start));
END$$

DROP PROCEDURE IF EXISTS `Set stock`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `Set stock` (IN `sid` INT, IN `quantity` INT)  NO SQL
BEGIN
	SET @old_quantity = (SELECT s.quantity FROM stocks s where s.id = sid);
    SET @diff = quantity - @old_quantity;
    INSERT INTO transactions (stock_id, quantity) VALUES (sid, @diff);
    SELECT @diff,@old_quantity, quantity;
END$$

--
-- Functions
--
DROP FUNCTION IF EXISTS `get_best_selling`$$
CREATE DEFINER=`root`@`localhost` FUNCTION `get_best_selling` (`start` DATE, `end` DATE, `user_id` INT) RETURNS VARCHAR(255) CHARSET utf8 NO SQL
BEGIN
	DECLARE best_sell VARCHAR(255);
	SELECT name INTO best_sell FROM (
    SELECT p.name, SUM(-1*t.quantity) AS num_sold FROM transactions t, stocks s, products p WHERE t.created_at BETWEEN start AND end AND t.quantity < 0 AND t.stock_id = s.id AND s.branch_id IN (SELECT branch_id FROM assignments a WHERE a.user_id = user_id) AND s.product_id = p.id GROUP BY p.name) AS sold ORDER BY num_sold DESC LIMIT 1;
    RETURN best_sell;
END$$

DROP FUNCTION IF EXISTS `get_ratio`$$
CREATE DEFINER=`root`@`localhost` FUNCTION `get_ratio` (`cur` FLOAT, `past` FLOAT) RETURNS FLOAT NO SQL
BEGIN
	DECLARE percentage FLOAT;
    IF past = 0 THEN 
    	RETURN NULL;
    END IF;
    
    IF cur = past THEN 
    	RETURN 0;
    END IF;
    
    IF cur > past THEN
        SET percentage = (SELECT ((cur - past)/past)*100 - 1);
    ELSE 
        SET percentage = (SELECT ((past - cur)/past)*100 - 1);
    END IF;
    RETURN percentage;
END$$

DROP FUNCTION IF EXISTS `get_total_profit`$$
CREATE DEFINER=`root`@`localhost` FUNCTION `get_total_profit` (`start` DATE, `end` DATE, `user_id` INT) RETURNS FLOAT NO SQL
BEGIN
	DECLARE profit FLOAT;
    SELECT SUM(profit_per_prod) INTO profit FROM (
    SELECT p.id, SUM(-1*t.quantity) AS num_sold, (p.sell_price-p.buy_price) AS profit_per_stock, SUM(-1*t.quantity)*(p.sell_price-p.buy_price) AS profit_per_prod FROM transactions t, stocks s, products p WHERE t.created_at BETWEEN start AND end AND t.quantity < 0 AND t.stock_id = s.id AND s.product_id = p.id AND s.branch_id IN (SELECT branch_id FROM assignments a WHERE a.user_id = user_id) GROUP BY p.id) AS prof;
    RETURN profit;
END$$

DROP FUNCTION IF EXISTS `get_total_sales`$$
CREATE DEFINER=`root`@`localhost` FUNCTION `get_total_sales` (`start` DATE, `end` DATE, `user_id` INT) RETURNS INT(11) NO SQL
BEGIN
	DECLARE sales INT;
    SELECT SUM(-1*t.quantity) INTO sales FROM transactions t, stocks s WHERE t.created_at BETWEEN start AND end AND t.quantity < 0 AND t.stock_id = s.id 
AND s.branch_id IN (SELECT branch_id FROM assignments a WHERE a.user_id = user_id);
    RETURN sales;
END$$

DROP FUNCTION IF EXISTS `get_total_transactions`$$
CREATE DEFINER=`root`@`localhost` FUNCTION `get_total_transactions` (`start` DATE, `end` DATE, `user_id` INT) RETURNS INT(11) NO SQL
BEGIN
	DECLARE transactions INT;
    SELECT COUNT(*) INTO transactions FROM transactions t, stocks s WHERE t.created_at BETWEEN start AND end AND t.quantity < 0 AND t.stock_id = s.id 
AND s.branch_id IN (SELECT branch_id FROM assignments a WHERE a.user_id = user_id);
    RETURN transactions;
END$$

DELIMITER ;

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
(4, 3, 1),
(12, 2, 3),
(13, 2, 4),
(14, 2, 6),
(18, 1, 1),
(19, 1, 8),
(20, 1, 10);

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
(1, 'Branch A', '3916 Grim Avenue San Diego Califori 92107', '2020-11-02 09:48:58', '2020-11-20 11:52:13'),
(2, 'Branch B', '142 Fleming Street Montgomery Alabam 36107', '2020-11-02 09:48:58', '2020-11-18 11:39:39'),
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

--
-- Triggers `branches`
--
DROP TRIGGER IF EXISTS `Add all products to branch`;
DELIMITER $$
CREATE TRIGGER `Add all products to branch` AFTER INSERT ON `branches` FOR EACH ROW BEGIN
	INSERT INTO stocks(product_id, branch_id) 
    SELECT p.id, NEW.id FROM products p;
END
$$
DELIMITER ;
DROP TRIGGER IF EXISTS `Update modified at branches`;
DELIMITER $$
CREATE TRIGGER `Update modified at branches` BEFORE UPDATE ON `branches` FOR EACH ROW SET NEW.modified_at := NOW()
$$
DELIMITER ;

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
(3, 'Apple Juice', 'DRINKS', 5, 3, '2020-11-07 21:16:14', '2020-11-07 21:16:14'),
(4, 'Hotdog', 'SNACKS', 8, 5, '2020-11-07 23:11:36', '2020-11-07 23:11:36'),
(5, 'Icecream', 'SNACKS', 60, 30, '2020-11-20 12:09:57', '2020-11-20 12:09:57'),
(6, 'Airplane', 'ELECTRONICS', 34, 23, '2020-11-22 02:26:11', '2020-11-22 02:26:11');

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
DROP TRIGGER IF EXISTS `Update modified at product`;
DELIMITER $$
CREATE TRIGGER `Update modified at product` BEFORE UPDATE ON `products` FOR EACH ROW SET NEW.modified_at := NOW()
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
(58, 3, 1, 0, '2020-11-07 21:16:14', '2020-11-21 22:00:37'),
(59, 3, 2, 4, '2020-11-07 21:16:14', '2020-11-22 02:14:28'),
(60, 3, 3, 0, '2020-11-07 21:16:14', '2020-11-07 21:16:14'),
(61, 3, 4, 0, '2020-11-07 21:16:14', '2020-11-07 21:16:14'),
(62, 3, 5, 0, '2020-11-07 21:16:14', '2020-11-07 21:16:14'),
(63, 3, 6, 0, '2020-11-07 21:16:14', '2020-11-07 21:16:14'),
(64, 3, 7, 0, '2020-11-07 21:16:14', '2020-11-07 21:16:14'),
(65, 3, 8, 16, '2020-11-07 21:16:14', '2020-11-22 01:53:43'),
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
(83, 3, 26, 0, '2020-11-07 21:16:14', '2020-11-07 21:16:14'),
(89, 4, 1, 16, '2020-11-07 23:11:36', '2020-11-21 22:08:30'),
(90, 4, 2, 0, '2020-11-07 23:11:36', '2020-11-07 23:11:36'),
(91, 4, 3, 0, '2020-11-07 23:11:36', '2020-11-07 23:11:36'),
(92, 4, 4, 0, '2020-11-07 23:11:36', '2020-11-07 23:11:36'),
(93, 4, 5, 0, '2020-11-07 23:11:36', '2020-11-07 23:11:36'),
(94, 4, 6, 0, '2020-11-07 23:11:36', '2020-11-07 23:11:36'),
(95, 4, 7, 0, '2020-11-07 23:11:36', '2020-11-07 23:11:36'),
(96, 4, 8, 0, '2020-11-07 23:11:36', '2020-11-07 23:11:36'),
(97, 4, 9, 0, '2020-11-07 23:11:36', '2020-11-07 23:11:36'),
(98, 4, 10, 0, '2020-11-07 23:11:36', '2020-11-07 23:11:36'),
(99, 4, 11, 0, '2020-11-07 23:11:36', '2020-11-07 23:11:36'),
(100, 4, 12, 0, '2020-11-07 23:11:36', '2020-11-07 23:11:36'),
(101, 4, 13, 0, '2020-11-07 23:11:36', '2020-11-07 23:11:36'),
(102, 4, 14, 0, '2020-11-07 23:11:36', '2020-11-07 23:11:36'),
(103, 4, 15, 0, '2020-11-07 23:11:36', '2020-11-07 23:11:36'),
(104, 4, 16, 0, '2020-11-07 23:11:36', '2020-11-07 23:11:36'),
(105, 4, 17, 0, '2020-11-07 23:11:36', '2020-11-07 23:11:36'),
(106, 4, 18, 0, '2020-11-07 23:11:36', '2020-11-07 23:11:36'),
(107, 4, 19, 0, '2020-11-07 23:11:36', '2020-11-07 23:11:36'),
(108, 4, 20, 0, '2020-11-07 23:11:36', '2020-11-07 23:11:36'),
(109, 4, 21, 0, '2020-11-07 23:11:36', '2020-11-07 23:11:36'),
(110, 4, 22, 0, '2020-11-07 23:11:36', '2020-11-07 23:11:36'),
(111, 4, 23, 0, '2020-11-07 23:11:36', '2020-11-07 23:11:36'),
(112, 4, 24, 0, '2020-11-07 23:11:36', '2020-11-07 23:11:36'),
(113, 4, 25, 0, '2020-11-07 23:11:36', '2020-11-07 23:11:36'),
(114, 4, 26, 0, '2020-11-07 23:11:36', '2020-11-07 23:11:36'),
(115, 5, 1, 15, '2020-11-20 12:09:57', '2020-11-22 00:23:17'),
(117, 5, 2, 0, '2020-11-20 12:09:57', '2020-11-20 12:09:57'),
(118, 5, 3, 0, '2020-11-20 12:09:57', '2020-11-20 12:09:57'),
(119, 5, 4, 0, '2020-11-20 12:09:57', '2020-11-20 12:09:57'),
(120, 5, 5, 0, '2020-11-20 12:09:57', '2020-11-20 12:09:57'),
(121, 5, 6, 0, '2020-11-20 12:09:57', '2020-11-20 12:09:57'),
(122, 5, 7, 0, '2020-11-20 12:09:57', '2020-11-20 12:09:57'),
(123, 5, 8, 0, '2020-11-20 12:09:57', '2020-11-20 12:09:57'),
(124, 5, 9, 0, '2020-11-20 12:09:57', '2020-11-20 12:09:57'),
(125, 5, 10, 0, '2020-11-20 12:09:57', '2020-11-20 12:09:57'),
(126, 5, 11, 0, '2020-11-20 12:09:57', '2020-11-20 12:09:57'),
(127, 5, 12, 0, '2020-11-20 12:09:57', '2020-11-20 12:09:57'),
(128, 5, 13, 0, '2020-11-20 12:09:57', '2020-11-20 12:09:57'),
(129, 5, 14, 0, '2020-11-20 12:09:57', '2020-11-20 12:09:57'),
(130, 5, 15, 0, '2020-11-20 12:09:57', '2020-11-20 12:09:57'),
(131, 5, 16, 0, '2020-11-20 12:09:57', '2020-11-20 12:09:57'),
(132, 5, 17, 0, '2020-11-20 12:09:57', '2020-11-20 12:09:57'),
(133, 5, 18, 0, '2020-11-20 12:09:57', '2020-11-20 12:09:57'),
(134, 5, 19, 0, '2020-11-20 12:09:57', '2020-11-20 12:09:57'),
(135, 5, 20, 0, '2020-11-20 12:09:57', '2020-11-20 12:09:57'),
(136, 5, 21, 0, '2020-11-20 12:09:57', '2020-11-20 12:09:57'),
(137, 5, 22, 0, '2020-11-20 12:09:57', '2020-11-20 12:09:57'),
(138, 5, 23, 0, '2020-11-20 12:09:57', '2020-11-20 12:09:57'),
(139, 5, 24, 0, '2020-11-20 12:09:57', '2020-11-20 12:09:57'),
(140, 5, 25, 0, '2020-11-20 12:09:57', '2020-11-20 12:09:57'),
(141, 5, 26, 0, '2020-11-20 12:09:57', '2020-11-20 12:09:57'),
(142, 6, 1, 4, '2020-11-22 02:26:11', '2020-11-22 02:26:31'),
(143, 6, 2, 0, '2020-11-22 02:26:11', '2020-11-22 02:26:11'),
(144, 6, 3, 0, '2020-11-22 02:26:11', '2020-11-22 02:26:11'),
(145, 6, 4, 0, '2020-11-22 02:26:11', '2020-11-22 02:26:11'),
(146, 6, 5, 0, '2020-11-22 02:26:11', '2020-11-22 02:26:11'),
(147, 6, 6, 0, '2020-11-22 02:26:11', '2020-11-22 02:26:11'),
(148, 6, 7, 0, '2020-11-22 02:26:11', '2020-11-22 02:26:11'),
(149, 6, 8, 0, '2020-11-22 02:26:11', '2020-11-22 02:26:11'),
(150, 6, 9, 0, '2020-11-22 02:26:11', '2020-11-22 02:26:11'),
(151, 6, 10, 0, '2020-11-22 02:26:11', '2020-11-22 02:26:11'),
(152, 6, 11, 0, '2020-11-22 02:26:11', '2020-11-22 02:26:11'),
(153, 6, 12, 0, '2020-11-22 02:26:11', '2020-11-22 02:26:11'),
(154, 6, 13, 0, '2020-11-22 02:26:11', '2020-11-22 02:26:11'),
(155, 6, 14, 0, '2020-11-22 02:26:11', '2020-11-22 02:26:11'),
(156, 6, 15, 0, '2020-11-22 02:26:11', '2020-11-22 02:26:11'),
(157, 6, 16, 0, '2020-11-22 02:26:11', '2020-11-22 02:26:11'),
(158, 6, 17, 0, '2020-11-22 02:26:11', '2020-11-22 02:26:11'),
(159, 6, 18, 0, '2020-11-22 02:26:11', '2020-11-22 02:26:11'),
(160, 6, 19, 0, '2020-11-22 02:26:11', '2020-11-22 02:26:11'),
(161, 6, 20, 0, '2020-11-22 02:26:11', '2020-11-22 02:26:11'),
(162, 6, 21, 0, '2020-11-22 02:26:11', '2020-11-22 02:26:11'),
(163, 6, 22, 0, '2020-11-22 02:26:11', '2020-11-22 02:26:11'),
(164, 6, 23, 0, '2020-11-22 02:26:11', '2020-11-22 02:26:11'),
(165, 6, 24, 0, '2020-11-22 02:26:11', '2020-11-22 02:26:11'),
(166, 6, 25, 0, '2020-11-22 02:26:11', '2020-11-22 02:26:11'),
(167, 6, 26, 0, '2020-11-22 02:26:11', '2020-11-22 02:26:11');

--
-- Triggers `stocks`
--
DROP TRIGGER IF EXISTS `Update modified at stocks`;
DELIMITER $$
CREATE TRIGGER `Update modified at stocks` BEFORE UPDATE ON `stocks` FOR EACH ROW SET NEW.modified_at := NOW()
$$
DELIMITER ;

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
-- Dumping data for table `transactions`
--

INSERT INTO `transactions` (`id`, `stock_id`, `quantity`, `created_at`) VALUES
(7, 58, 1, '2020-11-18 21:51:24'),
(8, 58, 0, '2020-11-20 15:53:53'),
(9, 58, -1, '2020-11-21 22:00:37'),
(10, 65, 20, '2020-11-21 22:03:20'),
(11, 65, -2, '2020-11-21 22:03:26'),
(12, 89, 20, '2020-11-21 22:08:21'),
(13, 89, -4, '2020-11-21 22:08:30'),
(14, 115, 25, '2020-11-19 23:30:52'),
(15, 115, -10, '2020-11-20 16:30:52'),
(16, 65, -2, '2020-11-22 01:53:43'),
(17, 59, 17, '2020-11-22 02:14:28'),
(18, 59, -13, '2020-11-22 02:14:28'),
(19, 142, 10, '2020-11-22 02:26:24'),
(20, 142, -6, '2020-11-22 02:26:31');

--
-- Triggers `transactions`
--
DROP TRIGGER IF EXISTS `Aggregate stock quantity after delete`;
DELIMITER $$
CREATE TRIGGER `Aggregate stock quantity after delete` AFTER DELETE ON `transactions` FOR EACH ROW BEGIN
	UPDATE stocks 
    SET quantity = COALESCE((SELECT SUM(quantity) FROM transactions WHERE stock_id = OLD.stock_id), 0)
    WHERE id = OLD.stock_id;
END
$$
DELIMITER ;
DROP TRIGGER IF EXISTS `Aggregate stock quantity after insert`;
DELIMITER $$
CREATE TRIGGER `Aggregate stock quantity after insert` AFTER INSERT ON `transactions` FOR EACH ROW BEGIN
	UPDATE stocks 
    SET quantity = COALESCE((SELECT SUM(quantity) FROM transactions WHERE stock_id = NEW.stock_id), 0)
    WHERE id = NEW.stock_id;
END
$$
DELIMITER ;
DROP TRIGGER IF EXISTS `Aggregate stock quantity after update`;
DELIMITER $$
CREATE TRIGGER `Aggregate stock quantity after update` AFTER UPDATE ON `transactions` FOR EACH ROW BEGIN
	UPDATE stocks 
    SET quantity = COALESCE((SELECT SUM(quantity) FROM transactions WHERE stock_id = OLD.stock_id), 0)
    WHERE id = OLD.stock_id;
    
    UPDATE stocks 
    SET quantity = COALESCE((SELECT SUM(quantity) FROM transactions WHERE stock_id = NEW.stock_id), 0)
    WHERE id = NEW.stock_id;
END
$$
DELIMITER ;
DROP TRIGGER IF EXISTS `Check if enough stocks`;
DELIMITER $$
CREATE TRIGGER `Check if enough stocks` BEFORE INSERT ON `transactions` FOR EACH ROW BEGIN
	SELECT s.quantity INTO @stock_quantity FROM stocks s WHERE s.id=NEW.stock_id;
    IF @stock_quantity + NEW.quantity < 0 THEN
    	SIGNAL SQLSTATE '45000'
          SET MESSAGE_TEXT = 'Cannot decrease more stock than currently available!';
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
(1, 'test', 'test', 'a', '$2y$10$Rftr4xlRfvblhUJG/LE7MOPvHEaYZhfI0/lqSumpRXO88BztkByV6', 'IT', '2020-11-04 01:09:32', '2020-11-20 12:10:45'),
(2, 'aba', 'transform', 'b', '$2y$10$ycH4/zhkNOsYDcHz4bxH0eiXg2zP2/bvVMFPe.i2v3VLEvpYRmL/m', 'EXECUTIVE', '2020-11-04 01:14:19', '2020-11-20 11:19:09'),
(3, 'Frederico', 'Kami-sama', 'c', '$2y$10$kR5MUsWDnnit/lJG3oFCB.AoBeU.cbQ0YfWD8AmX/3aIdR0QfGCyS', 'MANAGER', '2020-11-05 23:23:42', '2020-11-05 23:23:42');

--
-- Triggers `users`
--
DROP TRIGGER IF EXISTS `Add default branch`;
DELIMITER $$
CREATE TRIGGER `Add default branch` AFTER INSERT ON `users` FOR EACH ROW INSERT INTO assignments (user_id, branch_id) VALUES (NEW.id, 1)
$$
DELIMITER ;
DROP TRIGGER IF EXISTS `Update modified at users`;
DELIMITER $$
CREATE TRIGGER `Update modified at users` BEFORE UPDATE ON `users` FOR EACH ROW SET NEW.modified_at := NOW()
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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `branches`
--
ALTER TABLE `branches`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `stocks`
--
ALTER TABLE `stocks`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=173;

--
-- AUTO_INCREMENT for table `transactions`
--
ALTER TABLE `transactions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

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
  ADD CONSTRAINT `assignments_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `assignments_ibfk_2` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `stocks`
--
ALTER TABLE `stocks`
  ADD CONSTRAINT `stocks_ibfk_1` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `stocks_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `transactions`
--
ALTER TABLE `transactions`
  ADD CONSTRAINT `transactions_ibfk_1` FOREIGN KEY (`stock_id`) REFERENCES `stocks` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
