-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 17, 2025 at 02:12 AM
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
-- Database: `drugdatabase`
--

DELIMITER $$
--
-- Procedures
--
CREATE DEFINER=`root`@`localhost` PROCEDURE `getsellerorders` (IN `seller_id` VARCHAR(50))   BEGIN
    SELECT oid, pid, price, quantity, uid 
    FROM orders 
    WHERE sid = seller_id;
END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `id` int(11) NOT NULL,
  `sid` varchar(20) NOT NULL,
  `pass` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id`, `sid`, `pass`) VALUES
(1, 'admin', 'admin');

-- --------------------------------------------------------

--
-- Table structure for table `customer`
--

CREATE TABLE `customer` (
  `uid` varchar(20) NOT NULL,
  `pass` varchar(20) DEFAULT NULL,
  `fname` varchar(15) DEFAULT NULL,
  `lname` varchar(15) DEFAULT NULL,
  `email` varchar(30) DEFAULT NULL,
  `address` varchar(128) DEFAULT NULL,
  `phno` bigint(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `customer`
--

INSERT INTO `customer` (`uid`, `pass`, `fname`, `lname`, `email`, `address`, `phno`) VALUES
('1', 'vimala', 'Vimala', 'G', 'vimala@gmail.com', '19A, Vimalam Street, Trichy', 9878987674),
('22299', 'shyam', 'Shyam', 'P M', 'shyam@gmail.com', '7, K K nagar, Trichy', 9897656789),
('ram', 'ram', 'Ram', 'Kumar', 'ram34@gmail.com', '7, K.K Nagar, Trichy', 8989898989);

-- --------------------------------------------------------

--
-- Table structure for table `doctor`
--

CREATE TABLE `doctor` (
  `dname` varchar(50) NOT NULL,
  `pass` varchar(50) NOT NULL,
  `address` varchar(50) NOT NULL,
  `phno` double NOT NULL,
  `sid` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `doctor`
--

INSERT INTO `doctor` (`dname`, `pass`, `address`, `phno`, `sid`) VALUES
('Balu', 'Balu', '19A, Vimalam Street, Thanjavur', 9878987678, ''),
('Aravind', 'aravind', 'aravind@gmail.com', 8688577661, 'aravind'),
('Riaz', 'riaz', '7, Kamalam nagar, Trichy', 9897656776, 'riaz');

-- --------------------------------------------------------

--
-- Table structure for table `inventory`
--

CREATE TABLE `inventory` (
  `pid` varchar(15) NOT NULL,
  `pname` varchar(20) DEFAULT NULL,
  `quantity` int(10) UNSIGNED DEFAULT NULL,
  `sid` varchar(15) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `inventory`
--

INSERT INTO `inventory` (`pid`, `pname`, `quantity`, `sid`) VALUES
('556', 'Resitan 50', 101, 'jjpharma'),
('676868', 'Citaz K', 81, 'jjpharma'),
('A2', 'Perinorm', 50, '111'),
('D4566', 'Dolo-650', 50, '111'),
('k1112', 'Resitan-50', 21, '111'),
('k113', 'Citaz M', 70, '111'),
('L8', 'Live52', 50, '111');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `oid` int(11) NOT NULL,
  `pid` varchar(15) DEFAULT NULL,
  `sid` varchar(15) DEFAULT NULL,
  `uid` varchar(15) DEFAULT NULL,
  `quantity` int(10) UNSIGNED DEFAULT NULL,
  `price` int(10) UNSIGNED DEFAULT NULL,
  `orderdatetime` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`oid`, `pid`, `sid`, `uid`, `quantity`, `price`, `orderdatetime`) VALUES
(1, 'A2', 'jjpharma', '22299', 10, 180, '2025-03-15 22:22:32'),
(2, 'D4566', '111', '22299', 8, 80, '0000-00-00 00:00:00'),
(3, 'A2', '111', '22299', 8, 144, '0000-00-00 00:00:00'),
(4, 'A2', '111', '22299', 5, 90, '0000-00-00 00:00:00'),
(5, 'A2', '111', '22299', 5, 90, '0000-00-00 00:00:00'),
(6, 'A2', '111', '22299', 5, 90, '2021-03-18 22:56:00'),
(7, 'L8', 'jjpharma', '22299', 20, 200, '2025-03-15 23:19:57'),
(8, '556', 'jjpharma', '22299', 2, 8, '2025-03-16 23:39:22');

-- --------------------------------------------------------

--
-- Table structure for table `payment`
--

CREATE TABLE `payment` (
  `id` int(11) NOT NULL,
  `cid` int(11) NOT NULL,
  `total` decimal(10,2) NOT NULL,
  `cardtype` enum('Debit','Credit') NOT NULL,
  `cardno` varchar(16) NOT NULL,
  `cardname` varchar(255) NOT NULL,
  `cvv` int(11) NOT NULL,
  `pin` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pres`
--

CREATE TABLE `pres` (
  `id` int(11) NOT NULL,
  `pid` varchar(50) NOT NULL,
  `mname` varchar(100) NOT NULL,
  `pur` varchar(50) NOT NULL,
  `dr` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `pres`
--

INSERT INTO `pres` (`id`, `pid`, `mname`, `pur`, `dr`) VALUES
(2, '22299', 'Rantax 50', 'giddiness', 'riaz'),
(3, '22299', 'Perinorm89', 'Vommiting', 'riaz'),
(4, 'aa', 'a', 'a', 'a'),
(5, 'a', 'aa', 'a', NULL),
(6, 'as', 's', 's', NULL),
(7, 's', 's', 'a', NULL),
(8, 'e', 'ee', 'e', NULL),
(10, '1', 'Citaz K', 'Sinus', 'aravind'),
(11, '1', 'Restitan', 'BP', 'aravind');

-- --------------------------------------------------------

--
-- Table structure for table `product`
--

CREATE TABLE `product` (
  `pid` varchar(15) NOT NULL,
  `pname` varchar(20) DEFAULT NULL,
  `manufacturer` varchar(20) DEFAULT NULL,
  `mfg` date DEFAULT NULL,
  `exp` date DEFAULT NULL,
  `price` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `product`
--

INSERT INTO `product` (`pid`, `pname`, `manufacturer`, `mfg`, `exp`, `price`) VALUES
('556', 'Resitan 50', 'Elive Pharmas', '2024-07-07', '2026-07-07', 4),
('676868', 'Citaz K', 'Zion pharmas', '2024-12-16', '2025-04-16', 7),
('A2', 'Perinorm', 'Pfizer', '2020-09-10', '2022-09-11', 18),
('D4566', 'Dolo-650', 'Pfizer', '2021-03-01', '2023-03-01', 10),
('k1112', 'Resitan-50', 'Zion pharmas', '2021-09-01', '2022-09-01', 25),
('k113', 'Citaz M', 'Zion pharmas', '2021-09-10', '2022-09-11', 30),
('L8', 'Live52', 'Zion pharmas', '2019-03-01', '2021-03-01', 20);

-- --------------------------------------------------------

--
-- Table structure for table `quickorder`
--

CREATE TABLE `quickorder` (
  `id` int(11) NOT NULL,
  `cid` varchar(50) NOT NULL,
  `msg` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `quickorder`
--

INSERT INTO `quickorder` (`id`, `cid`, `msg`) VALUES
(1, '22299', 'Citaz m'),
(2, '22299', 'Restian'),
(3, 'ram', 'provaccine'),
(4, '22299', 'Meftal Plus\r\nUybi-Y');

-- --------------------------------------------------------

--
-- Table structure for table `rorder`
--

CREATE TABLE `rorder` (
  `id` int(11) NOT NULL,
  `cid` varchar(50) NOT NULL,
  `sid` varchar(50) NOT NULL,
  `order` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `rorder`
--

INSERT INTO `rorder` (`id`, `cid`, `sid`, `order`) VALUES
(1, '22299', '111', 'Neuroben'),
(2, '22299', '111', 'Citaz M');

-- --------------------------------------------------------

--
-- Table structure for table `seller`
--

CREATE TABLE `seller` (
  `sid` varchar(15) NOT NULL,
  `sname` varchar(20) DEFAULT NULL,
  `pass` varchar(20) DEFAULT NULL,
  `address` varchar(128) DEFAULT NULL,
  `phno` bigint(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `seller`
--

INSERT INTO `seller` (`sid`, `sname`, `pass`, `address`, `phno`) VALUES
('1', 'Raja', 'Raja', '19A, Vimalam Street, Trichy', 9878987678),
('111', 'Riaz', 'riaz', '7, Kamalam nagar, Trichy', 9897656776),
('jjpharma', 'JJ Pharma', 'jjpharma', 'jjpharma@gmail.com', 9789876789),
('priya', 'JJ Pharma', 'priya', '7, Usuf Colony, Trichy', 8987674345),
('rajam', 'Rajam', 'rajam', '7, K K nagar, Trichy', 9897656789),
('sumithra', 'Sumithra', 'sumithra', '70, K K nagar, Trichy', 9897656789);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `customer`
--
ALTER TABLE `customer`
  ADD PRIMARY KEY (`uid`);

--
-- Indexes for table `doctor`
--
ALTER TABLE `doctor`
  ADD PRIMARY KEY (`sid`);

--
-- Indexes for table `inventory`
--
ALTER TABLE `inventory`
  ADD PRIMARY KEY (`pid`,`sid`),
  ADD KEY `fk02` (`pname`),
  ADD KEY `fk03` (`sid`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`oid`),
  ADD KEY `fk04` (`pid`),
  ADD KEY `fk05` (`sid`),
  ADD KEY `fk06` (`uid`);

--
-- Indexes for table `payment`
--
ALTER TABLE `payment`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pres`
--
ALTER TABLE `pres`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `product`
--
ALTER TABLE `product`
  ADD PRIMARY KEY (`pid`),
  ADD UNIQUE KEY `pname` (`pname`);

--
-- Indexes for table `quickorder`
--
ALTER TABLE `quickorder`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `rorder`
--
ALTER TABLE `rorder`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `seller`
--
ALTER TABLE `seller`
  ADD PRIMARY KEY (`sid`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `oid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `payment`
--
ALTER TABLE `payment`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `pres`
--
ALTER TABLE `pres`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `quickorder`
--
ALTER TABLE `quickorder`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `rorder`
--
ALTER TABLE `rorder`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `inventory`
--
ALTER TABLE `inventory`
  ADD CONSTRAINT `fk01` FOREIGN KEY (`pid`) REFERENCES `product` (`pid`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk02` FOREIGN KEY (`pname`) REFERENCES `product` (`pname`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk03` FOREIGN KEY (`sid`) REFERENCES `seller` (`sid`) ON DELETE CASCADE;

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `fk04` FOREIGN KEY (`pid`) REFERENCES `product` (`pid`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk05` FOREIGN KEY (`sid`) REFERENCES `seller` (`sid`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk06` FOREIGN KEY (`uid`) REFERENCES `customer` (`uid`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
