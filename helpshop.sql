-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- 생성 시간: 24-12-20 10:43
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
-- 테이블 구조 `account`
--

CREATE TABLE `account` (
  `ix` int(11) NOT NULL,
  `user_ix` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `account_manager` varchar(100) NOT NULL,
  `manager_contact` varchar(15) NOT NULL,
  `contact` varchar(15) NOT NULL,
  `site` varchar(255) NOT NULL,
  `address` varchar(255) NOT NULL,
  `account_number` varchar(50) NOT NULL,
  `memo` text NOT NULL,
  `create_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- 테이블의 덤프 데이터 `account`
--

INSERT INTO `account` (`ix`, `user_ix`, `name`, `account_manager`, `manager_contact`, `contact`, `site`, `address`, `account_number`, `memo`, `create_at`, `updated_at`) VALUES
(10, 1, 'NS', '한상민', '010-4244-4144', '032-868-5427', 'https://www.nsrod.co.kr/', '인천광역시 남동구 염전로411번길 38 ', '139-81-07784', 'test 메모ㅁㅇㅁㄴㅇㄹㅁㄴㅇㄹㅇㄴㄹ\r\n\r\n테스트 해봅니다.\r\n\r\n1.2ㅣㅇㄹ\r\n\r\n3.', '2024-11-29 11:50:44', '2024-11-29 11:50:44'),
(25, 1, '윤성조구', '', '1577-6160', '1577-6160', 'https://www.yoonsunginc.kr/', '경기도 양주시 은현면 은현로 221번길 13 주식회사윤성 A/S팀', '142-81-81209', '', '2024-12-03 18:39:38', '2024-12-03 18:39:38'),
(26, 1, '해원레포츠', '', '051-203-0997', '051-203-0997', '', '부산광역시 사하구 구평로 16번길 6', '', '', '2024-12-03 18:41:38', '2024-12-03 18:41:38'),
(27, 1, '원더랜드', '', '032-575-4085', '032-575-4085', '', '인천 서구 파랑로 536 한성컴퓨터 3층 원더랜드', '', '', '2024-12-03 18:42:44', '2024-12-03 18:42:44'),
(28, 1, '피싱코리아', '김영돈', '031-747-5804', '031-747-5804', 'https://fikorea.co.kr/', '경기도 성남시 중원구 갈마치로288번길 14 (상대원동) SK V1 타워 A동 227호 피싱코리아(주)', '810-81-00653', '602hb 주문해야함', '2024-12-03 18:44:22', '2024-12-03 18:44:22');

-- --------------------------------------------------------

--
-- 테이블 구조 `category`
--

CREATE TABLE `category` (
  `ix` int(11) NOT NULL,
  `user_ix` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- 테이블의 덤프 데이터 `category`
--

INSERT INTO `category` (`ix`, `user_ix`, `name`, `created_at`, `updated_at`) VALUES
(1, 1, '낚시대', '2024-12-09 16:31:34', '2024-12-09 16:31:34');

-- --------------------------------------------------------

--
-- 테이블 구조 `db_match`
--

CREATE TABLE `db_match` (
  `ix` int(11) NOT NULL,
  `name_of_excel` varchar(500) NOT NULL,
  `combination_ix` int(11) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- 테이블 구조 `market`
--

CREATE TABLE `market` (
  `ix` int(11) NOT NULL,
  `user_ix` int(11) NOT NULL,
  `market_name` varchar(200) NOT NULL,
  `basic_fee` varchar(50) NOT NULL,
  `linked_fee` varchar(50) NOT NULL,
  `ship_fee` varchar(50) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- 테이블의 덤프 데이터 `market`
--

INSERT INTO `market` (`ix`, `user_ix`, `market_name`, `basic_fee`, `linked_fee`, `ship_fee`, `created_at`, `updated_at`) VALUES
(1, 1, '네이버 스마트스토어', '2.75', '2', '2.75', '2024-11-17 08:17:27', '2024-11-28 18:13:27'),
(2, 1, '쿠팡', '10.8', '0', '3.3', '2024-11-17 08:17:27', '2024-11-28 18:13:27');

-- --------------------------------------------------------

--
-- 테이블 구조 `orders`
--

CREATE TABLE `orders` (
  `ix` int(11) NOT NULL,
  `order_number` varchar(50) NOT NULL,
  `order_date` date NOT NULL,
  `market_ix` int(11) NOT NULL,
  `user_ix` int(11) NOT NULL,
  `division_val` varchar(1000) NOT NULL COMMENT '주문 여러번했는지 구분값',
  `total_payment` decimal(10,2) NOT NULL,
  `total_shipping` decimal(10,2) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- 테이블 구조 `order_details`
--

CREATE TABLE `order_details` (
  `ix` int(11) NOT NULL,
  `orders_ix` int(11) NOT NULL,
  `product_ix` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `price` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- 테이블 구조 `product`
--

CREATE TABLE `product` (
  `ix` int(11) NOT NULL,
  `user_ix` int(11) NOT NULL,
  `account_ix` int(11) NOT NULL,
  `category_ix` int(11) NOT NULL,
  `name` varchar(500) NOT NULL,
  `memo` text NOT NULL,
  `create_at` datetime NOT NULL DEFAULT current_timestamp(),
  `update_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- 테이블 구조 `product_option`
--

CREATE TABLE `product_option` (
  `ix` int(11) NOT NULL,
  `product_ix` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `value` varchar(255) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- 테이블 구조 `product_option_combination`
--

CREATE TABLE `product_option_combination` (
  `ix` int(11) NOT NULL,
  `product_ix` int(11) NOT NULL,
  `combination_key` varchar(255) NOT NULL,
  `cost_price` decimal(10,2) NOT NULL,
  `stock` int(11) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- 테이블 구조 `product_option_market_price`
--

CREATE TABLE `product_option_market_price` (
  `ix` int(11) NOT NULL,
  `product_option_comb_ix` int(11) NOT NULL,
  `market_ix` int(11) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- 테이블 구조 `user`
--

CREATE TABLE `user` (
  `ix` int(11) NOT NULL,
  `id` varchar(200) NOT NULL,
  `pwd` varchar(200) NOT NULL,
  `name` varchar(200) NOT NULL,
  `contact` varchar(200) NOT NULL,
  `email` varchar(200) NOT NULL,
  `create_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- 테이블의 덤프 데이터 `user`
--

INSERT INTO `user` (`ix`, `id`, `pwd`, `name`, `contact`, `email`, `create_at`, `updated_at`) VALUES
(1, 'wkdgh5430', '81dc9bdb52d04dc20036dbd8313ed055', '한장호', '010-5613-5430', 'wkdgh5430@naver.com', '2024-11-17 08:23:27', '2024-11-28 17:55:07');

--
-- 덤프된 테이블의 인덱스
--

--
-- 테이블의 인덱스 `account`
--
ALTER TABLE `account`
  ADD PRIMARY KEY (`ix`),
  ADD KEY `fk_account_user` (`user_ix`);

--
-- 테이블의 인덱스 `category`
--
ALTER TABLE `category`
  ADD PRIMARY KEY (`ix`),
  ADD KEY `fk_category_user` (`user_ix`);

--
-- 테이블의 인덱스 `db_match`
--
ALTER TABLE `db_match`
  ADD KEY `fk_combination_db` (`combination_ix`);

--
-- 테이블의 인덱스 `market`
--
ALTER TABLE `market`
  ADD PRIMARY KEY (`ix`),
  ADD KEY `fk_market_user` (`user_ix`);

--
-- 테이블의 인덱스 `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`ix`),
  ADD KEY `fk_orders_user` (`user_ix`);

--
-- 테이블의 인덱스 `order_details`
--
ALTER TABLE `order_details`
  ADD PRIMARY KEY (`ix`),
  ADD KEY `fk_order_details_orders` (`orders_ix`);

--
-- 테이블의 인덱스 `product`
--
ALTER TABLE `product`
  ADD PRIMARY KEY (`ix`),
  ADD KEY `fk_product_user` (`user_ix`),
  ADD KEY `fk_product_account` (`account_ix`),
  ADD KEY `fk_product_category` (`category_ix`);

--
-- 테이블의 인덱스 `product_option`
--
ALTER TABLE `product_option`
  ADD PRIMARY KEY (`ix`),
  ADD KEY `fk_product_option_product` (`product_ix`);

--
-- 테이블의 인덱스 `product_option_combination`
--
ALTER TABLE `product_option_combination`
  ADD PRIMARY KEY (`ix`),
  ADD KEY `fk_product_option_combination_product` (`product_ix`);

--
-- 테이블의 인덱스 `product_option_market_price`
--
ALTER TABLE `product_option_market_price`
  ADD PRIMARY KEY (`ix`),
  ADD KEY `fk_option_market_option_comb` (`product_option_comb_ix`),
  ADD KEY `fk_option_market_market` (`market_ix`);

--
-- 테이블의 인덱스 `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`ix`);

--
-- 덤프된 테이블의 AUTO_INCREMENT
--

--
-- 테이블의 AUTO_INCREMENT `account`
--
ALTER TABLE `account`
  MODIFY `ix` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- 테이블의 AUTO_INCREMENT `category`
--
ALTER TABLE `category`
  MODIFY `ix` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- 테이블의 AUTO_INCREMENT `market`
--
ALTER TABLE `market`
  MODIFY `ix` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- 테이블의 AUTO_INCREMENT `orders`
--
ALTER TABLE `orders`
  MODIFY `ix` int(11) NOT NULL AUTO_INCREMENT;

--
-- 테이블의 AUTO_INCREMENT `order_details`
--
ALTER TABLE `order_details`
  MODIFY `ix` int(11) NOT NULL AUTO_INCREMENT;

--
-- 테이블의 AUTO_INCREMENT `product`
--
ALTER TABLE `product`
  MODIFY `ix` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=52;

--
-- 테이블의 AUTO_INCREMENT `product_option`
--
ALTER TABLE `product_option`
  MODIFY `ix` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=220;

--
-- 테이블의 AUTO_INCREMENT `product_option_combination`
--
ALTER TABLE `product_option_combination`
  MODIFY `ix` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=170;

--
-- 테이블의 AUTO_INCREMENT `product_option_market_price`
--
ALTER TABLE `product_option_market_price`
  MODIFY `ix` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=299;

--
-- 테이블의 AUTO_INCREMENT `user`
--
ALTER TABLE `user`
  MODIFY `ix` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- 덤프된 테이블의 제약사항
--

--
-- 테이블의 제약사항 `account`
--
ALTER TABLE `account`
  ADD CONSTRAINT `fk_account_user` FOREIGN KEY (`user_ix`) REFERENCES `user` (`ix`) ON DELETE CASCADE;

--
-- 테이블의 제약사항 `category`
--
ALTER TABLE `category`
  ADD CONSTRAINT `fk_category_user` FOREIGN KEY (`user_ix`) REFERENCES `user` (`ix`) ON DELETE CASCADE;

--
-- 테이블의 제약사항 `db_match`
--
ALTER TABLE `db_match`
  ADD CONSTRAINT `fk_combination_db` FOREIGN KEY (`combination_ix`) REFERENCES `product_option_combination` (`ix`) ON DELETE CASCADE;

--
-- 테이블의 제약사항 `market`
--
ALTER TABLE `market`
  ADD CONSTRAINT `fk_market_user` FOREIGN KEY (`user_ix`) REFERENCES `user` (`ix`) ON DELETE CASCADE;

--
-- 테이블의 제약사항 `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `fk_orders_user` FOREIGN KEY (`user_ix`) REFERENCES `user` (`ix`) ON DELETE CASCADE;

--
-- 테이블의 제약사항 `order_details`
--
ALTER TABLE `order_details`
  ADD CONSTRAINT `fk_order_details_orders` FOREIGN KEY (`orders_ix`) REFERENCES `orders` (`ix`) ON DELETE CASCADE;

--
-- 테이블의 제약사항 `product`
--
ALTER TABLE `product`
  ADD CONSTRAINT `fk_product_account` FOREIGN KEY (`account_ix`) REFERENCES `account` (`ix`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_product_category` FOREIGN KEY (`category_ix`) REFERENCES `category` (`ix`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_product_user` FOREIGN KEY (`user_ix`) REFERENCES `user` (`ix`) ON DELETE CASCADE;

--
-- 테이블의 제약사항 `product_option`
--
ALTER TABLE `product_option`
  ADD CONSTRAINT `fk_product_option_product` FOREIGN KEY (`product_ix`) REFERENCES `product` (`ix`) ON DELETE CASCADE;

--
-- 테이블의 제약사항 `product_option_combination`
--
ALTER TABLE `product_option_combination`
  ADD CONSTRAINT `fk_product_option_combination_product` FOREIGN KEY (`product_ix`) REFERENCES `product` (`ix`) ON DELETE CASCADE;

--
-- 테이블의 제약사항 `product_option_market_price`
--
ALTER TABLE `product_option_market_price`
  ADD CONSTRAINT `fk_option_market_market` FOREIGN KEY (`market_ix`) REFERENCES `market` (`ix`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_option_market_option_comb` FOREIGN KEY (`product_option_comb_ix`) REFERENCES `product_option_combination` (`ix`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
