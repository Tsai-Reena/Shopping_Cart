-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 08, 2023 at 01:19 AM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.0.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `finalproject`
--

-- --------------------------------------------------------

--
-- Table structure for table `includes`
--

CREATE TABLE `includes` (
  `orderID` int(3) UNSIGNED NOT NULL,
  `productID` int(3) UNSIGNED NOT NULL,
  `Quantity` int(10) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `includes`
--

INSERT INTO `includes` (`orderID`, `productID`, `Quantity`) VALUES
(26, 1, 1),
(27, 1, 1),
(29, 3, 1);

-- --------------------------------------------------------

--
-- Table structure for table `order`
--

CREATE TABLE `order` (
  `orderID` int(3) UNSIGNED ZEROFILL NOT NULL,
  `CustomerName` varchar(10) NOT NULL,
  `DateShipped` date DEFAULT NULL,
  `Total` tinyint(3) UNSIGNED DEFAULT NULL,
  `PayType` enum('Cash','Credit_Card','Money_Transfer') DEFAULT NULL,
  `shopperID` int(3) UNSIGNED DEFAULT NULL,
  `Checkout_time` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `order`
--

INSERT INTO `order` (`orderID`, `CustomerName`, `DateShipped`, `Total`, `PayType`, `shopperID`, `Checkout_time`) VALUES
(002, 'Reena', '2017-06-01', 255, '', NULL, '2023-06-07 13:30:55'),
(003, 'Reena', '2017-06-01', 255, '', NULL, '2023-06-07 13:31:44'),
(004, 'Reena', '2017-06-01', 255, '', NULL, '2023-06-07 13:32:08'),
(005, 'Reena', '2017-06-01', 255, '', NULL, '2023-06-07 13:32:38'),
(006, 'Reena', '2017-06-01', 0, '', NULL, '2023-06-07 13:32:46'),
(007, 'test', '2017-06-01', 255, 'Money_Transfer', NULL, '2023-06-07 13:35:39'),
(008, 'test', '2017-06-01', 255, 'Credit_Card', NULL, '2023-06-07 13:36:03'),
(009, 'Reena', '2017-06-29', 255, 'Money_Transfer', NULL, '2023-06-07 13:46:19'),
(010, 'test', '2017-06-01', 255, 'Cash', NULL, '2023-06-07 13:47:20'),
(011, 'test', '2017-06-01', 255, 'Money_Transfer', NULL, '2023-06-07 13:47:58'),
(012, 'test', '2017-06-01', 255, 'Money_Transfer', NULL, '2023-06-07 13:50:18'),
(013, 'Reena', '2017-06-01', 255, 'Money_Transfer', NULL, '2023-06-07 13:57:27'),
(014, 'Reena', '2017-06-01', 255, 'Money_Transfer', NULL, '2023-06-07 13:57:58'),
(015, 'Reena', '2017-06-01', 255, 'Money_Transfer', NULL, '2023-06-07 13:58:35'),
(016, 'Reena', '2017-06-01', 255, 'Money_Transfer', NULL, '2023-06-07 13:59:34'),
(017, 'Reena', '2017-06-01', 255, 'Money_Transfer', NULL, '2023-06-07 13:59:51'),
(018, 'Reena', '2017-06-01', 255, 'Money_Transfer', NULL, '2023-06-07 14:00:24'),
(019, 'Reena', '2017-06-01', 255, 'Money_Transfer', NULL, '2023-06-07 14:29:46'),
(020, 'Reena', '2017-06-01', 255, 'Money_Transfer', NULL, '2023-06-07 14:30:43'),
(021, 'Reena', '2017-06-01', 255, 'Money_Transfer', NULL, '2023-06-07 14:31:03'),
(022, 'Reena', '2017-06-01', 255, 'Money_Transfer', NULL, '2023-06-07 14:32:28'),
(023, 'Reena', '2017-06-01', 255, 'Money_Transfer', NULL, '2023-06-07 14:33:27'),
(024, 'Reena', '2017-06-01', 255, 'Money_Transfer', NULL, '2023-06-07 14:33:41'),
(025, 'Reena', '2017-06-01', 255, 'Money_Transfer', NULL, '2023-06-07 14:34:02'),
(026, 'Reena', '2017-06-01', 255, 'Money_Transfer', NULL, '2023-06-07 14:35:39'),
(027, 'test', '2017-06-01', 255, 'Money_Transfer', NULL, '2023-06-07 14:51:29'),
(028, 'test0445', '2017-06-01', 0, 'Money_Transfer', NULL, '2023-06-07 20:36:49'),
(029, 'test0607', '2017-06-27', 40, 'Money_Transfer', NULL, '2023-06-08 01:16:15');

-- --------------------------------------------------------

--
-- Table structure for table `pc`
--

CREATE TABLE `pc` (
  `productID` int(3) UNSIGNED ZEROFILL NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `phone`
--

CREATE TABLE `phone` (
  `productID` int(3) UNSIGNED ZEROFILL NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `phone`
--

INSERT INTO `phone` (`productID`) VALUES
(001),
(003);

-- --------------------------------------------------------

--
-- Table structure for table `product`
--

CREATE TABLE `product` (
  `pID` int(3) UNSIGNED ZEROFILL NOT NULL,
  `BrandName` varchar(20) NOT NULL,
  `ProductName` varchar(20) NOT NULL,
  `Price` tinyint(4) UNSIGNED NOT NULL,
  `Description` text NOT NULL,
  `SecondHand` tinyint(1) NOT NULL,
  `sellerID` int(3) UNSIGNED DEFAULT NULL,
  `Quantity` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `product`
--

INSERT INTO `product` (`pID`, `BrandName`, `ProductName`, `Price`, `Description`, `SecondHand`, `sellerID`, `Quantity`) VALUES
(001, 'ASUS', 'ASUS Zenbook', 255, 'ASUS Zenbook Description', 1, 1, 4),
(003, 'Apple', 'iPad 10', 40, 'Test', 0, 2, 5),
(004, 'Apple', 'iPad 7', 40, 'Test \r\nTest\r\nTEst', 0, 2, 5);

-- --------------------------------------------------------

--
-- Table structure for table `seler`
--

CREATE TABLE `seler` (
  `ID` int(3) UNSIGNED ZEROFILL NOT NULL,
  `Password` varchar(10) NOT NULL,
  `Username` varchar(20) NOT NULL,
  `Phone` varchar(20) NOT NULL,
  `Address` varchar(50) NOT NULL,
  `Gender` enum('F','M') NOT NULL,
  `Login` tinyint(1) NOT NULL,
  `Logintime` datetime NOT NULL,
  `Email` varchar(50) NOT NULL,
  `Admin` int(3) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `seler`
--

INSERT INTO `seler` (`ID`, `Password`, `Username`, `Phone`, `Address`, `Gender`, `Login`, `Logintime`, `Email`, `Admin`) VALUES
(001, '12345678', 'seller001', '0987654321', 'Taipei, Taiwan', 'M', 1, '2023-06-06 22:33:31', '12345678@gmail.com', NULL),
(002, '12345678', 'test000000', '0952265300', 'Taipei', 'F', 1, '2023-06-08 01:06:31', 'TsaiReena@gmail.com', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `sell_locations`
--

CREATE TABLE `sell_locations` (
  `sellerID` int(3) UNSIGNED NOT NULL,
  `Slocation` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `shipping_info`
--

CREATE TABLE `shipping_info` (
  `ShippingCost` tinyint(4) DEFAULT NULL,
  `ShippingType` enum('711','Family','OK','Fedex') DEFAULT NULL,
  `ShippingAddress` varchar(50) NOT NULL,
  `ReceiveName` varchar(20) NOT NULL,
  `orderID` int(3) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `shipping_info`
--

INSERT INTO `shipping_info` (`ShippingCost`, `ShippingType`, `ShippingAddress`, `ReceiveName`, `orderID`) VALUES
(60, '711', 'Taipei, Taiwan', 'Reena', 29),
(60, '711', 'Taipei, Taiwan', 'test', 12),
(60, '711', 'Taipei, Taiwan', 'test', 13),
(60, '711', 'Taipei, Taiwan', 'test', 14),
(60, '711', 'Taipei, Taiwan', 'test', 15),
(60, '711', 'Taipei, Taiwan', 'test', 16),
(60, '711', 'Taipei, Taiwan', 'test', 17),
(60, '711', 'Taipei, Taiwan', 'test', 18),
(60, '711', 'Taipei', 'test', 28),
(60, '711', '水源街45巷2號4樓', 'TSAI JUI YUN', 19),
(60, '711', '水源街45巷2號4樓', 'TSAI JUI YUN', 20),
(60, '711', '水源街45巷2號4樓', 'TSAI JUI YUN', 21),
(60, '711', '水源街45巷2號4樓', 'TSAI JUI YUN', 22),
(60, '711', '水源街45巷2號4樓', 'TSAI JUI YUN', 23),
(60, '711', '水源街45巷2號4樓', 'TSAI JUI YUN', 24),
(60, '711', '水源街45巷2號4樓', 'TSAI JUI YUN', 25),
(60, '711', '水源街45巷2號4樓', 'TSAI JUI YUN', 26),
(60, '711', '水源街45巷2號4樓', '蔡睿芸', 27);

-- --------------------------------------------------------

--
-- Table structure for table `shopper`
--

CREATE TABLE `shopper` (
  `ID` int(3) UNSIGNED ZEROFILL NOT NULL,
  `Password` varchar(20) NOT NULL,
  `Username` varchar(20) NOT NULL,
  `Phone` varchar(20) NOT NULL,
  `Address` varchar(50) NOT NULL,
  `Gender` enum('F','M') NOT NULL,
  `Login` tinyint(1) NOT NULL,
  `Logintime` datetime NOT NULL,
  `Birthday` date NOT NULL,
  `Email` varchar(50) NOT NULL,
  `Jointime` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `shopper`
--

INSERT INTO `shopper` (`ID`, `Password`, `Username`, `Phone`, `Address`, `Gender`, `Login`, `Logintime`, `Birthday`, `Email`, `Jointime`) VALUES
(001, '$2y$10$zyJkstJsspTrF', 'root1111', '0952265300', '新北市永和區水源街45巷2號4樓', 'F', 0, '0000-00-00 00:00:00', '2023-05-19', 'TsaiReena@gmail.com', '2023-06-07 15:58:19'),
(002, '12345678', 'test0607', '0987654321', 'Taipei', 'F', 1, '2023-06-08 01:14:24', '2023-06-07', 'tsaireena@gmail.com', '2023-06-07 16:02:11'),
(003, '123456789', 'test0445', '0987654321', 'Taipei', 'F', 1, '2023-06-07 21:10:25', '2023-06-07', 'tsaireena@gmail.com', '2023-06-07 16:46:01');

-- --------------------------------------------------------

--
-- Table structure for table `tablet`
--

CREATE TABLE `tablet` (
  `productID` int(3) UNSIGNED ZEROFILL NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `tablet`
--

INSERT INTO `tablet` (`productID`) VALUES
(001);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `includes`
--
ALTER TABLE `includes`
  ADD PRIMARY KEY (`orderID`,`productID`),
  ADD KEY `orderdetail_product` (`productID`);

--
-- Indexes for table `order`
--
ALTER TABLE `order`
  ADD PRIMARY KEY (`orderID`,`CustomerName`),
  ADD KEY `shopperID` (`shopperID`);

--
-- Indexes for table `pc`
--
ALTER TABLE `pc`
  ADD PRIMARY KEY (`productID`);

--
-- Indexes for table `phone`
--
ALTER TABLE `phone`
  ADD PRIMARY KEY (`productID`);

--
-- Indexes for table `product`
--
ALTER TABLE `product`
  ADD PRIMARY KEY (`pID`),
  ADD KEY `seller_product` (`sellerID`);

--
-- Indexes for table `seler`
--
ALTER TABLE `seler`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `SellerAdmin` (`Admin`);

--
-- Indexes for table `sell_locations`
--
ALTER TABLE `sell_locations`
  ADD PRIMARY KEY (`sellerID`,`Slocation`);

--
-- Indexes for table `shipping_info`
--
ALTER TABLE `shipping_info`
  ADD PRIMARY KEY (`ReceiveName`,`orderID`),
  ADD KEY `orderID` (`orderID`);

--
-- Indexes for table `shopper`
--
ALTER TABLE `shopper`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `tablet`
--
ALTER TABLE `tablet`
  ADD PRIMARY KEY (`productID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `order`
--
ALTER TABLE `order`
  MODIFY `orderID` int(3) UNSIGNED ZEROFILL NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT for table `pc`
--
ALTER TABLE `pc`
  MODIFY `productID` int(3) UNSIGNED ZEROFILL NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `phone`
--
ALTER TABLE `phone`
  MODIFY `productID` int(3) UNSIGNED ZEROFILL NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `product`
--
ALTER TABLE `product`
  MODIFY `pID` int(3) UNSIGNED ZEROFILL NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT for table `seler`
--
ALTER TABLE `seler`
  MODIFY `ID` int(3) UNSIGNED ZEROFILL NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `shopper`
--
ALTER TABLE `shopper`
  MODIFY `ID` int(3) UNSIGNED ZEROFILL NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `tablet`
--
ALTER TABLE `tablet`
  MODIFY `productID` int(3) UNSIGNED ZEROFILL NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `includes`
--
ALTER TABLE `includes`
  ADD CONSTRAINT `orderdetail_order` FOREIGN KEY (`orderID`) REFERENCES `order` (`orderID`),
  ADD CONSTRAINT `orderdetail_product` FOREIGN KEY (`productID`) REFERENCES `product` (`pID`);

--
-- Constraints for table `order`
--
ALTER TABLE `order`
  ADD CONSTRAINT `shopperID` FOREIGN KEY (`shopperID`) REFERENCES `shopper` (`ID`);

--
-- Constraints for table `pc`
--
ALTER TABLE `pc`
  ADD CONSTRAINT `PCProduct` FOREIGN KEY (`productID`) REFERENCES `product` (`pID`);

--
-- Constraints for table `phone`
--
ALTER TABLE `phone`
  ADD CONSTRAINT `PhoneProduct` FOREIGN KEY (`productID`) REFERENCES `product` (`pID`);

--
-- Constraints for table `product`
--
ALTER TABLE `product`
  ADD CONSTRAINT `seller_product` FOREIGN KEY (`sellerID`) REFERENCES `seler` (`ID`);

--
-- Constraints for table `seler`
--
ALTER TABLE `seler`
  ADD CONSTRAINT `SellerAdmin` FOREIGN KEY (`Admin`) REFERENCES `seler` (`ID`);

--
-- Constraints for table `sell_locations`
--
ALTER TABLE `sell_locations`
  ADD CONSTRAINT `sellerID` FOREIGN KEY (`sellerID`) REFERENCES `seler` (`ID`);

--
-- Constraints for table `shipping_info`
--
ALTER TABLE `shipping_info`
  ADD CONSTRAINT `orderID` FOREIGN KEY (`orderID`) REFERENCES `order` (`orderID`);

--
-- Constraints for table `tablet`
--
ALTER TABLE `tablet`
  ADD CONSTRAINT `TabletProduct` FOREIGN KEY (`productID`) REFERENCES `product` (`pID`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
