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
-- 테이블 구조 `db_match`
--

CREATE TABLE `db_match` (
  `ix` int(11) NOT NULL,
  `name_of_excel` varchar(500) NOT NULL,
  `matching_ix` int(11) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- 테이블의 덤프 데이터 `db_match`
--

INSERT INTO `db_match` (`ix`, `name_of_excel`, `matching_ix`, `created_at`, `updated_at`) VALUES
(1, '7호 / 다미끼 스플릿 링: 7호', 3, '2025-02-20 23:50:26', '2025-02-20 23:50:26');

--
-- 덤프된 테이블의 인덱스
--

--
-- 테이블의 인덱스 `db_match`
--
ALTER TABLE `db_match`
  ADD PRIMARY KEY (`ix`);

--
-- 덤프된 테이블의 AUTO_INCREMENT
--

--
-- 테이블의 AUTO_INCREMENT `db_match`
--
ALTER TABLE `db_match`
  MODIFY `ix` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
