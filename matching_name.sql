-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- 생성 시간: 25-02-20 16:00
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
-- 테이블 구조 `matching_name`
--

CREATE TABLE `matching_name` (
  `ix` int(11) NOT NULL,
  `user_ix` int(11) NOT NULL,
  `matching_name` varchar(400) NOT NULL,
  `cost` decimal(10,2) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- 테이블의 덤프 데이터 `matching_name`
--

INSERT INTO `matching_name` (`ix`, `user_ix`, `matching_name`, `cost`, `created_at`) VALUES
(3, 1, '다미끼 스플릿 링 7호', 2400.00, '2025-02-20 23:48:30');

--
-- 덤프된 테이블의 인덱스
--

--
-- 테이블의 인덱스 `matching_name`
--
ALTER TABLE `matching_name`
  ADD PRIMARY KEY (`ix`);

--
-- 덤프된 테이블의 AUTO_INCREMENT
--

--
-- 테이블의 AUTO_INCREMENT `matching_name`
--
ALTER TABLE `matching_name`
  MODIFY `ix` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
