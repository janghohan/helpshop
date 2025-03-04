-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- 생성 시간: 25-03-04 15:44
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
-- 테이블 구조 `memo`
--

CREATE TABLE `memo` (
  `ix` int(11) NOT NULL,
  `user_ix` int(11) NOT NULL,
  `title` varchar(300) NOT NULL,
  `contents` mediumtext NOT NULL DEFAULT '',
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- 테이블의 덤프 데이터 `memo`
--

INSERT INTO `memo` (`ix`, `user_ix`, `title`, `contents`, `created_at`, `updated_at`) VALUES
(1, 1, '테스트메모', 'ㅁㅇㄴㄹㅇㄴㄹ ㅁㄴㅇㄹ\n\n\nㅁㄴㅇ라\n\n테스트 완료\n\n글 작성중', '2025-03-04 23:14:54', '2025-03-04 23:14:54');

--
-- 덤프된 테이블의 인덱스
--

--
-- 테이블의 인덱스 `memo`
--
ALTER TABLE `memo`
  ADD PRIMARY KEY (`ix`);

--
-- 덤프된 테이블의 AUTO_INCREMENT
--

--
-- 테이블의 AUTO_INCREMENT `memo`
--
ALTER TABLE `memo`
  MODIFY `ix` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
