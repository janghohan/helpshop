-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- 생성 시간: 25-01-13 10:31
-- 서버 버전: 10.4.32-MariaDB
-- PHP 버전: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- 데이터베이스: `helpshop`
--

-- --------------------------------------------------------

--
-- 테이블 구조 `orders`
--

CREATE TABLE `orders` (
  `ix` int(11) NOT NULL,
  `global_order_number` varchar(50) NOT NULL,
  `order_number` varchar(50) NOT NULL DEFAULT '',
  `order_date` date NOT NULL,
  `market_ix` int(11) NOT NULL,
  `user_ix` int(11) NOT NULL,
  `division_val` varchar(1000) NOT NULL DEFAULT '' COMMENT '주문 여러번했는지 구분값',
  `total_payment` decimal(10,2) NOT NULL,
  `total_shipping` decimal(10,2) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- 테이블의 덤프 데이터 `orders`
--

INSERT INTO `orders` (`ix`, `global_order_number`, `order_number`, `order_date`, `market_ix`, `user_ix`, `division_val`, `total_payment`, `total_shipping`, `created_at`) VALUES
(15, '202501131003420001', '202501131003420001', '2025-01-03', 1, 1, '', 92500.00, 3000.00, '2025-01-13 18:03:42'),
(16, '202501131004400001', '202501131004400001', '0000-00-00', 1, 1, '', 30000.00, 3000.00, '2025-01-13 18:04:40'),
(17, '202501131008130001', '202501131008130001', '2025-01-13', 1, 1, '', 20000.00, 3000.00, '2025-01-13 18:08:13'),
(18, '202501131008520001', '202501131008520001', '2025-01-13', 1, 1, '', 4000.00, 3000.00, '2025-01-13 18:08:52');

--
-- 덤프된 테이블의 인덱스
--

--
-- 테이블의 인덱스 `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`ix`),
  ADD UNIQUE KEY `idx_market_order` (`market_ix`,`order_number`),
  ADD KEY `fk_orders_user` (`user_ix`);

--
-- 덤프된 테이블의 AUTO_INCREMENT
--

--
-- 테이블의 AUTO_INCREMENT `orders`
--
ALTER TABLE `orders`
  MODIFY `ix` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- 덤프된 테이블의 제약사항
--

--
-- 테이블의 제약사항 `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `fk_orders_user` FOREIGN KEY (`user_ix`) REFERENCES `user` (`ix`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
