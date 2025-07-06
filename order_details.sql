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
-- 테이블 구조 `order_details`
--

CREATE TABLE `order_details` (
  `ix` int(11) NOT NULL,
  `orders_ix` int(11) NOT NULL,
  `name` varchar(400) NOT NULL,
  `cost` decimal(10,2) NOT NULL,
  `quantity` int(11) NOT NULL,
  `price` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- 테이블의 덤프 데이터 `order_details`
--

INSERT INTO `order_details` (`ix`, `orders_ix`, `name`, `cost`, `quantity`, `price`) VALUES
(30, 15, '쎄네 삼봉 만세기', 3150.00, 5, 4900.00),
(31, 15, 'NS 로드스알파 메탈리코 - S-682', 45000.00, 1, 68000.00),
(32, 16, '다미끼 랜스 롱 지그 300g 01', 10000.00, 1, 15000.00),
(33, 16, '다미끼 랜스 롱 지그 300g 47', 10000.00, 1, 15000.00),
(34, 17, '다미끼 스플릿링 7호', 2400.00, 5, 4000.00),
(35, 18, '다미끼 더 락 볼베어링 7호', 2400.00, 1, 4000.00);

--
-- 덤프된 테이블의 인덱스
--

--
-- 테이블의 인덱스 `order_details`
--
ALTER TABLE `order_details`
  ADD PRIMARY KEY (`ix`),
  ADD KEY `fk_order_details_orders` (`orders_ix`);

--
-- 덤프된 테이블의 AUTO_INCREMENT
--

--
-- 테이블의 AUTO_INCREMENT `order_details`
--
ALTER TABLE `order_details`
  MODIFY `ix` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- 덤프된 테이블의 제약사항
--

--
-- 테이블의 제약사항 `order_details`
--
ALTER TABLE `order_details`
  ADD CONSTRAINT `fk_order_details_orders` FOREIGN KEY (`orders_ix`) REFERENCES `orders` (`ix`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
