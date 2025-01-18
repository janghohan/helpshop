-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- 생성 시간: 25-01-18 08:51
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
(1, 1, '네이버', '2.75', '2', '2.75', '2024-11-17 08:17:27', '2024-11-28 18:13:27'),
(2, 1, '쿠팡', '11.88', '0', '3.3', '2024-11-17 08:17:27', '2024-11-28 18:13:27');

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
(442, '2025011805401000011', '2025011581298531', '2025-01-15', 1, 1, '', 119000.00, 0.00, '2025-01-18 13:40:10'),
(443, '2025011805401000012', '2025011582920491', '2025-01-15', 1, 1, '', 71400.00, 0.00, '2025-01-18 13:40:10'),
(444, '2025011805401000013', '2025011584876971', '2025-01-15', 1, 1, '', 30000.00, 3000.00, '2025-01-18 13:40:10'),
(445, '2025011805401000014', '2025011586691681', '2025-01-15', 1, 1, '', 107800.00, 4000.00, '2025-01-18 13:40:10'),
(446, '2025011805401000015', '2025011590723891', '2025-01-15', 1, 1, '', 16200.00, 3000.00, '2025-01-18 13:40:10'),
(447, '2025011805401000016', '2025011693220261', '2025-01-16', 1, 1, '', 77000.00, 0.00, '2025-01-18 13:40:10'),
(448, '2025011805401000017', '2025011697303411', '2025-01-16', 1, 1, '', 60000.00, 0.00, '2025-01-18 13:40:10'),
(449, '2025011805401000018', '2025011699270681', '2025-01-16', 1, 1, '', 58000.00, 0.00, '2025-01-18 13:40:10'),
(450, '2025011805401000019', '2025011699503581', '2025-01-16', 1, 1, '', 39000.00, 0.00, '2025-01-18 13:40:10'),
(451, '20250118054010000110', '2025011699987501', '2025-01-16', 1, 1, '', 11000.00, 3000.00, '2025-01-18 13:40:10'),
(452, '20250118054010000111', '2025011610579721', '2025-01-16', 1, 1, '', 54000.00, 3000.00, '2025-01-18 13:40:10'),
(453, '20250118054010000112', '2025011611059411', '2025-01-16', 1, 1, '', 79800.00, 0.00, '2025-01-18 13:40:10'),
(454, '20250118054010000113', '2025011611580231', '2025-01-16', 1, 1, '', 57400.00, 3000.00, '2025-01-18 13:40:10'),
(455, '20250118054010000114', '2025011613396571', '2025-01-16', 1, 1, '', 16500.00, 3000.00, '2025-01-18 13:40:10'),
(456, '20250118054010000115', '2025011613622361', '2025-01-16', 1, 1, '', 24000.00, 3000.00, '2025-01-18 13:40:10'),
(457, '20250118054010000116', '2025011613794221', '2025-01-16', 1, 1, '', 19000.00, 3000.00, '2025-01-18 13:40:10'),
(459, '2025011806162800011', '2025011054131101', '2025-01-10', 1, 1, '', 60000.00, 0.00, '2025-01-18 14:16:28'),
(460, '2025011806162800012', '2025011164211751', '2025-01-11', 1, 1, '', 111240.00, 0.00, '2025-01-18 14:16:28'),
(461, '2025011806162800013', '2025011164509021', '2025-01-11', 1, 1, '', 7000.00, 3000.00, '2025-01-18 14:16:28'),
(462, '2025011806162800014', '2025011177916901', '2025-01-11', 1, 1, '', 8000.00, 3000.00, '2025-01-18 14:16:28'),
(463, '2025011806162800015', '2025011284337331', '2025-01-12', 1, 1, '', 45000.00, 3000.00, '2025-01-18 14:16:28'),
(464, '2025011806162800016', '2025011287708531', '2025-01-12', 1, 1, '', 43200.00, 3000.00, '2025-01-18 14:16:28'),
(465, '2025011806162800017', '2025011289574601', '2025-01-12', 1, 1, '', 9000.00, 3000.00, '2025-01-18 14:16:28'),
(466, '2025011806162800018', '2025011291884101', '2025-01-12', 1, 1, '', 24500.00, 3000.00, '2025-01-18 14:16:28'),
(467, '2025011806162800019', '2025011210326561', '2025-01-12', 1, 1, '', 12000.00, 3000.00, '2025-01-18 14:16:28'),
(468, '20250118061628000110', '2025011311754051', '2025-01-13', 1, 1, '', 29500.00, 3000.00, '2025-01-18 14:16:28'),
(469, '20250118061628000111', '2025011314725291', '2025-01-13', 1, 1, '', 13800.00, 3000.00, '2025-01-18 14:16:28'),
(470, '20250118061628000112', '2025011314869381', '2025-01-13', 1, 1, '', 54000.00, 0.00, '2025-01-18 14:16:28'),
(471, '20250118061628000113', '2025011315540921', '2025-01-13', 1, 1, '', 29000.00, 0.00, '2025-01-18 14:16:28'),
(472, '20250118061628000114', '2025011318014921', '2025-01-13', 1, 1, '', 84000.00, 0.00, '2025-01-18 14:16:28'),
(483, '2025011806242800011', '2025011325607521', '2025-01-13', 1, 1, '', 20000.00, 3000.00, '2025-01-18 14:24:28'),
(484, '2025011806242800012', '2025011326995981', '2025-01-13', 1, 1, '', 15600.00, 3000.00, '2025-01-18 14:24:28'),
(485, '2025011806242800013', '2025011332019971', '2025-01-13', 1, 1, '', 25100.00, 3000.00, '2025-01-18 14:24:28'),
(486, '2025011806242800014', '2025011333490321', '2025-01-13', 1, 1, '', 35200.00, 3000.00, '2025-01-18 14:24:28'),
(487, '2025011806242800015', '2025011334698601', '2025-01-13', 1, 1, '', 30000.00, 0.00, '2025-01-18 14:24:28'),
(488, '2025011806242800016', '2025011334826821', '2025-01-13', 1, 1, '', 21000.00, 3000.00, '2025-01-18 14:24:28'),
(489, '2025011806242800017', '2025011334979081', '2025-01-13', 1, 1, '', 5500.00, 3000.00, '2025-01-18 14:24:28'),
(490, '2025011806242800018', '2025011339041681', '2025-01-13', 1, 1, '', 110300.00, 0.00, '2025-01-18 14:24:28'),
(491, '2025011806242800019', '2025011440382111', '2025-01-14', 1, 1, '', 27000.00, 3000.00, '2025-01-18 14:24:28'),
(492, '20250118062428000110', '2025011443652971', '2025-01-14', 1, 1, '', 58800.00, 3000.00, '2025-01-18 14:24:28'),
(493, '20250118062428000111', '2025011444690441', '2025-01-14', 1, 1, '', 142000.00, 0.00, '2025-01-18 14:24:28'),
(494, '20250118062428000112', '2025011450592121', '2025-01-14', 1, 1, '', 41700.00, 3000.00, '2025-01-18 14:24:28');

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
(864, 442, '잇베이트 지맨 방어 롱 지깅 메탈지그 180g 01 / 무게: 300g / 색상: #08', 0.00, 2, 23800.00),
(865, 442, '잇베이트 지맨 방어 롱 지깅 메탈지그 180g 01 / 무게: 300g / 색상: #07', 0.00, 2, 23800.00),
(866, 442, '잇베이트 지맨 방어 롱 지깅 메탈지그 180g 01 / 무게: 300g / 색상: #06', 0.00, 2, 23800.00),
(867, 442, '잇베이트 지맨 방어 롱 지깅 메탈지그 180g 01 / 무게: 300g / 색상: #05', 0.00, 2, 23800.00),
(868, 442, '잇베이트 지맨 방어 롱 지깅 메탈지그 180g 01 / 무게: 300g / 색상: #04', 0.00, 2, 23800.00),
(869, 443, '잇베이트 지맨 방어 롱 지깅 메탈지그 180g 01 / 무게: 300g / 색상: #06', 0.00, 2, 23800.00),
(870, 443, '잇베이트 지맨 방어 롱 지깅 메탈지그 180g 01 / 무게: 300g / 색상: #04', 0.00, 2, 23800.00),
(871, 443, '잇베이트 지맨 방어 롱 지깅 메탈지그 180g 01 / 무게: 300g / 색상: #05', 0.00, 2, 23800.00),
(872, 444, '해동 김프로 원투 비자립 막대찌 감성돔막대찌 HF-454 / 호수: 2.0호', 0.00, 2, 12000.00),
(873, 444, '해동 김프로 원투 비자립 막대찌 감성돔막대찌 HF-454 / 호수: 1.5호', 0.00, 2, 12000.00),
(874, 444, '해동 김프로 원투 비자립 막대찌 감성돔막대찌 HF-454 / 호수: 1.0호', 0.00, 1, 6000.00),
(875, 445, '다미끼 랜스 방어 롱 메탈지그 지깅 메탈채비 200g 01 / 무게: 350g / 색상: 501C (크롬)', 0.00, 2, 35200.00),
(876, 445, '다미끼 랜스 방어 롱 메탈지그 지깅 메탈채비 200g 01 / 무게: 300g / 색상: 01 (리얼베이트)', 0.00, 2, 31200.00),
(877, 445, '다미끼 랜스 방어 롱 메탈지그 지깅 메탈채비 200g 01 / 무게: 250g / 색상: 501C (크롬)', 0.00, 3, 41400.00),
(878, 446, '씨호크 지깅 훅 대부시리 방어 바늘 6/0호 빅게임 어시스트 훅 / 규격: SJ(기본형)', 0.00, 3, 16200.00),
(879, 447, '다미끼 랜스 방어 롱 메탈지그 지깅 메탈채비 200g 01 / 무게: 250g / 색상: 47 (프리즘 케이무라)', 0.00, 1, 13800.00),
(880, 447, 'GB 겨울 3컷 낚시장갑 네오 티타늄 블랙 M L XL / 사이즈: M', 0.00, 1, 26000.00),
(881, 447, '다미끼 랜스 방어 롱 메탈지그 지깅 메탈채비 200g 01 / 무게: 300g / 색상: 01 (리얼베이트)', 0.00, 1, 15600.00),
(882, 447, '다미끼 벡스 지깅 바늘 대부시리 방어 틴셀 어시스트 훅 7/0호 DJR / 타입: 어필(DJB) 9/0호', 0.00, 2, 21600.00),
(883, 448, '모리겐 볼락 카드채비 돌돔덜덜이 어구가자미 채비 어피바늘 8호 / 모리겐카드채비(바늘 - 지선 - 본선): 10호 - 2호 - 3호', 0.00, 10, 60000.00),
(884, 449, '월척조구 카드채비 우럭 삼치 열기 선상 대물 닭털 묶음바늘 20호 / 바늘 - 지선 - 본선: 20호 - 6호 - 10호', 0.00, 10, 29000.00),
(885, 449, '월척조구 카드채비 우럭 삼치 열기 선상 대물 닭털 묶음바늘 20호 / 바늘 - 지선 - 본선: 22호 - 6호 -10호', 0.00, 10, 29000.00),
(886, 450, '모리겐 볼락 카드채비 돌돔덜덜이 어구가자미 채비 어피바늘 8호 / 모리겐카드채비(바늘 - 지선 - 본선): 11호 - 2호 - 3호', 0.00, 5, 39000.00),
(887, 451, '원터치 소품 태클박스 찌낚시 원투낚시 채비 케이스 / ', 0.00, 2, 11000.00),
(888, 452, '다미끼 쏙 메탈 대구 전용 지깅 메탈지그 400g / 무게: 400g / 색상: 50GL (핑크 실버)', 0.00, 1, 8500.00),
(889, 452, '다미끼 쏙 메탈 대구 전용 지깅 메탈지그 400g / 무게: 400g / 색상: 56GL (홀로 레드헤드)', 0.00, 1, 8500.00),
(890, 452, '다미끼 쏙 메탈 대구 전용 지깅 메탈지그 400g / 무게: 400g / 색상: 53GL (그린 골드)', 0.00, 1, 8500.00),
(891, 452, '다미끼 쏙 메탈 대구 전용 지깅 메탈지그 400g / 무게: 450g / 색상: 52GL (홀로 퍼플)', 0.00, 1, 9500.00),
(892, 452, '다미끼 쏙 메탈 대구 전용 지깅 메탈지그 400g / 무게: 450g / 색상: 56GL (홀로 레드헤드)', 0.00, 1, 9500.00),
(893, 452, '다미끼 쏙 메탈 대구 전용 지깅 메탈지그 400g / 무게: 450g / 색상: 53GL (그린 골드)', 0.00, 1, 9500.00),
(894, 453, '잇베이트 롱 메탈지그 가방 부시리 방어 빅게임 메탈 지그백 50cm / 색상: 핑크', 0.00, 1, 19000.00),
(895, 453, '다미끼 랜스 방어 롱 메탈지그 지깅 메탈채비 200g 01 / 무게: 250g / 색상: 47 (프리즘 케이무라)', 0.00, 1, 13800.00),
(896, 453, '다미끼 랜스 방어 롱 메탈지그 지깅 메탈채비 200g 01 / 무게: 250g / 색상: 43 (엔쵸비)', 0.00, 1, 13800.00),
(897, 453, '다미끼 랜스 방어 롱 메탈지그 지깅 메탈채비 200g 01 / 무게: 300g / 색상: 01 (리얼베이트)', 0.00, 1, 15600.00),
(898, 453, '다미끼 랜스 방어 롱 메탈지그 지깅 메탈채비 200g 01 / 무게: 350g / 색상: 47 (프리즘케이무라)', 0.00, 1, 17600.00),
(899, 454, '다미끼 벡스 지깅 바늘 대부시리 방어 틴셀 어시스트 훅 7/0호 DJR / 타입: 기본(DJR) 9/0호', 0.00, 2, 18000.00),
(900, 454, '다미끼 벡스 지깅 바늘 대부시리 방어 틴셀 어시스트 훅 7/0호 DJR / 타입: 어필(DJB) 9/0호', 0.00, 2, 21600.00),
(901, 454, '다미끼 스플릿 링 빅게임 부시리 방어 지깅 메탈지그 훅 연결 채비 / 호수: #7', 0.00, 2, 7000.00),
(902, 454, '다미끼 더 락 볼베어링 도래 빅게임 지깅용 대방어 대부시리 채비 / 호수: #7', 0.00, 3, 10800.00),
(903, 455, '모리겐 볼락 카드채비 돌돔덜덜이 어구가자미 채비 어피바늘 8호 / 모리겐카드채비(바늘 - 지선 - 본선): 13호 - 3호 - 5호', 0.00, 1, 8500.00),
(904, 455, '모리겐 볼락 카드채비 돌돔덜덜이 어구가자미 채비 어피바늘 8호 / 모리겐카드채비(바늘 - 지선 - 본선): 12호 - 3호 - 5호', 0.00, 1, 8000.00),
(905, 456, '다미끼 랜스 방어 롱 메탈지그 지깅 메탈채비 200g 01 / 무게: 200g / 색상: 45 (메크로)', 0.00, 1, 12000.00),
(906, 456, '다미끼 랜스 방어 롱 메탈지그 지깅 메탈채비 200g 01 / 무게: 200g / 색상: 501C (크롬)', 0.00, 1, 12000.00),
(907, 457, '잇베이트 롱 메탈지그 가방 부시리 방어 빅게임 메탈 지그백 50cm / 색상: 엘로우', 0.00, 1, 19000.00),
(908, 459, '월척조구 카드채비 WA-6 볼락 열기 외줄낚시 15호 / 바늘 - 지선 - 본선: 17호 - 5호 - 8호', 0.00, 15, 30000.00),
(909, 459, '월척조구 카드채비 WA-6 볼락 열기 외줄낚시 15호 / 바늘 - 지선 - 본선: 15호 - 5호 - 8호', 0.00, 15, 30000.00),
(910, 460, '다미끼 랜스 롱 지그 빅게임 메탈 부시리 방어 200g 01 / 무게: 250g / 색상: 47 (프리즘 케이무라)', 0.00, 1, 13560.00),
(911, 460, '다미끼 랜스 롱 지그 빅게임 메탈 부시리 방어 200g 01 / 무게: 200g / 색상: 46 (다이나믹 핑크)', 0.00, 1, 11760.00),
(912, 460, '다미끼 랜스 롱 지그 빅게임 메탈 부시리 방어 200g 01 / 무게: 250g / 색상: 58 (레드헤드)', 0.00, 2, 27120.00),
(913, 460, '다미끼 랜스 롱 지그 빅게임 메탈 부시리 방어 200g 01 / 무게: 200g / 색상: 501C (크롬)', 0.00, 2, 23520.00),
(914, 460, '다미끼 랜스 롱 지그 빅게임 메탈 부시리 방어 200g 01 / 무게: 200g / 색상: 43 (엔쵸비)', 0.00, 1, 11760.00),
(915, 460, '다미끼 랜스 롱 지그 빅게임 메탈 부시리 방어 200g 01 / 무게: 200g / 색상: 01 (리얼베이트)', 0.00, 2, 23520.00),
(916, 461, '가마가츠 토너먼트 치누 지누 감성돔 바늘 2호 3호 / 호수: 3호', 0.00, 1, 3500.00),
(917, 461, '가마가츠 토너먼트 치누 지누 감성돔 바늘 2호 3호 / 호수: 2호', 0.00, 1, 3500.00),
(918, 462, '노바코어 야광 삼봉 학꽁치포 7개입 한치 심해갑오징어 미끼 / 타입: 야광', 0.00, 2, 8000.00),
(919, 463, '델리온 델리리그 쭈갑채비 쭈꾸미 갑오징어 유동 가지 채비 프리리그 30cm / 옵션: 지선 30cm 본선 100cm', 0.00, 10, 45000.00),
(920, 464, '다미끼 벡스 지깅 바늘 대부시리 방어 틴셀 어시스트 훅 7/0호 DJR / 타입: 어필(DJB) 9/0호', 0.00, 4, 43200.00),
(921, 465, '잇베이트 리틀핀 볼락 마이크로 메탈지그 풀치 전갱이 아징 메탈 / 무게: 3g / 색상: #6', 0.00, 1, 3000.00),
(922, 465, '잇베이트 리틀핀 볼락 마이크로 메탈지그 풀치 전갱이 아징 메탈 / 무게: 3g / 색상: #4', 0.00, 1, 3000.00),
(923, 465, '잇베이트 리틀핀 볼락 마이크로 메탈지그 풀치 전갱이 아징 메탈 / 무게: 3g / 색상: #1', 0.00, 1, 3000.00),
(924, 466, '장산 염주 메탈 수중찌 염주찌 염주봉돌 수제수중찌 1호 / 호수: 2호', 0.00, 2, 11000.00),
(925, 466, '장산 염주 메탈 수중찌 염주찌 염주봉돌 수제수중찌 1호 / 호수: 1.5호', 0.00, 3, 13500.00),
(926, 467, '잇베이트 리틀핀 볼락 마이크로 메탈지그 풀치 전갱이 아징 메탈 / 무게: 3g / 색상: #7', 0.00, 1, 3000.00),
(927, 467, '잇베이트 리틀핀 볼락 마이크로 메탈지그 풀치 전갱이 아징 메탈 / 무게: 5g / 색상: #6', 0.00, 1, 3000.00),
(928, 467, '잇베이트 리틀핀 볼락 마이크로 메탈지그 풀치 전갱이 아징 메탈 / 무게: 3g / 색상: #4', 0.00, 1, 3000.00),
(929, 467, '잇베이트 리틀핀 볼락 마이크로 메탈지그 풀치 전갱이 아징 메탈 / 무게: 3g / 색상: #1', 0.00, 1, 3000.00),
(930, 468, '선라인 시그론 8합사 0.4호 150m 합사 멀티컬러 / 길이: 300m / 호수: 3호 (50LB)', 0.00, 1, 29500.00),
(931, 469, '쭈스타 심해 제주갑오징어채비 먼바다 버림채비 바늘 4개입 / 선택: 심해용 80cm', 0.00, 6, 13800.00),
(932, 470, '다이와 크로스파이어 KS 인쇼어대 볼락 로드 인쇼어 낚시대 762ULFS / 규격: 762ULFS', 0.00, 1, 54000.00),
(933, 471, '모리겐 볼락 카드채비 돌돔덜덜이 어구가자미 채비 어피바늘 8호 / 모리겐카드채비(바늘 - 지선 - 본선): 10호 - 2호 - 3호', 0.00, 5, 29000.00),
(934, 472, '모리겐 볼락 카드채비 돌돔덜덜이 어구가자미 채비 어피바늘 8호 / 모리겐카드채비(바늘 - 지선 - 본선): 13호 - 3호 - 5호', 0.00, 10, 84000.00),
(935, 483, '월척조구 우럭채비 돌돔덜덜이 카드 열기 채비 WA-6 / 바늘 - 지선 - 본선: 15호 - 5호 - 8호', 0.00, 10, 20000.00),
(936, 484, '다미끼 랜스 롱 지그 빅게임 메탈 부시리 방어 200g 01 / 무게: 300g / 색상: 01 (리얼베이트)', 0.00, 1, 15600.00),
(937, 485, '다미끼 벡스 지깅 바늘 대부시리 방어 틴셀 어시스트 훅 7/0호 DJR / 타입: 어필(DJB) 7/0호', 0.00, 2, 21600.00),
(938, 485, '7호 / 다미끼 스플릿 링: 7호', 0.00, 1, 3500.00),
(939, 486, '다미끼 랜스 롱 지그 빅게임 메탈 부시리 방어 200g 01 / 무게: 350g / 색상: 57 (다이나믹 블루)', 0.00, 1, 17600.00),
(940, 486, '다미끼 랜스 롱 지그 빅게임 메탈 부시리 방어 200g 01 / 무게: 350g / 색상: 01 (리얼베이트)', 0.00, 1, 17600.00),
(941, 487, '모리겐 볼락 카드채비 돌돔덜덜이 어구가자미 채비 어피바늘 8호 / 모리겐카드채비(바늘 - 지선 - 본선): 10호 - 2호 - 3호', 0.00, 5, 30000.00),
(942, 488, '잇베이트 리틀핀 볼락 마이크로 메탈지그 풀치 전갱이 아징 메탈 / 무게: 3g / 색상: #7', 0.00, 1, 3000.00),
(943, 488, '잇베이트 리틀핀 볼락 마이크로 메탈지그 풀치 전갱이 아징 메탈 / 무게: 3g / 색상: #6', 0.00, 1, 3000.00),
(944, 488, '잇베이트 리틀핀 볼락 마이크로 메탈지그 풀치 전갱이 아징 메탈 / 무게: 3g / 색상: #5', 0.00, 1, 3000.00),
(945, 488, '잇베이트 리틀핀 볼락 마이크로 메탈지그 풀치 전갱이 아징 메탈 / 무게: 3g / 색상: #4', 0.00, 1, 3000.00),
(946, 488, '잇베이트 리틀핀 볼락 마이크로 메탈지그 풀치 전갱이 아징 메탈 / 무게: 3g / 색상: #3', 0.00, 1, 3000.00),
(947, 488, '잇베이트 리틀핀 볼락 마이크로 메탈지그 풀치 전갱이 아징 메탈 / 무게: 3g / 색상: #2', 0.00, 1, 3000.00),
(948, 488, '잇베이트 리틀핀 볼락 마이크로 메탈지그 풀치 전갱이 아징 메탈 / 무게: 3g / 색상: #1', 0.00, 1, 3000.00),
(949, 489, '원터치 소품 태클박스 찌낚시 원투낚시 채비 케이스 / ', 0.00, 1, 5500.00),
(950, 490, '다미끼 랜스 롱 지그 빅게임 메탈 부시리 방어 200g 01 / 무게: 250g / 색상: 45 (메크로)', 0.00, 1, 13800.00),
(951, 490, '다미끼 랜스 롱 지그 빅게임 메탈 부시리 방어 200g 01 / 무게: 250g / 색상: 47 (프리즘 케이무라)', 0.00, 1, 13800.00),
(952, 490, '다미끼 랜스 롱 지그 빅게임 메탈 부시리 방어 200g 01 / 무게: 250g / 색상: 501C (크롬)', 0.00, 1, 13800.00),
(953, 490, '다미끼 랜스 롱 지그 빅게임 메탈 부시리 방어 200g 01 / 무게: 300g / 색상: 01 (리얼베이트)', 0.00, 1, 15600.00),
(954, 490, '다미끼 랜스 롱 지그 빅게임 메탈 부시리 방어 200g 01 / 무게: 300g / 색상: 48 (골드워시)', 0.00, 1, 15600.00),
(955, 490, '어필 9/0호 / 벡스 지깅 훅: 어필 9/0호', 0.00, 1, 10900.00),
(956, 490, '어필 7/0호 / 벡스 지깅 훅: 어필 7/0호', 0.00, 1, 10900.00),
(957, 490, '일반 9/0호 / 벡스 지깅 훅: 일반 9/0호', 0.00, 1, 8900.00),
(958, 490, '7호 / 스플릿 링: 7호', 0.00, 2, 7000.00),
(959, 491, '다미끼 벡스 지깅 바늘 대부시리 방어 틴셀 어시스트 훅 7/0호 DJR / 타입: 기본(DJR) 9/0호', 0.00, 3, 27000.00),
(960, 492, '다미끼 랜스 롱 지그 빅게임 메탈 부시리 방어 200g 01 / 무게: 250g / 색상: 42 (마히마히)', 0.00, 1, 13800.00),
(961, 492, '다미끼 랜스 롱 지그 빅게임 메탈 부시리 방어 200g 01 / 무게: 250g / 색상: 47 (프리즘 케이무라)', 0.00, 1, 13800.00),
(962, 492, '다미끼 랜스 롱 지그 빅게임 메탈 부시리 방어 200g 01 / 무게: 300g / 색상: 01 (리얼베이트)', 0.00, 1, 15600.00),
(963, 492, '다미끼 랜스 롱 지그 빅게임 메탈 부시리 방어 200g 01 / 무게: 300g / 색상: 48 (골드워시)', 0.00, 1, 15600.00),
(964, 493, 'HDF 해동 밑밥주걱 기본 72cm / ', 0.00, 1, 3000.00),
(965, 493, '선라인 토르네도 블랙스트림 방어 부시리 열기 쇼크리더 12호 카본 목줄 / 호수: 12호', 0.00, 2, 68000.00),
(966, 493, '선라인 토르네도 블랙스트림 방어 부시리 열기 쇼크리더 12호 카본 목줄 / 호수: 14호', 0.00, 2, 71000.00),
(967, 494, 'HDF 슬릿 폼 지그헤드 케이스 훅 루어 태클박스 S / 사이즈(화이트): M', 0.00, 1, 4500.00),
(968, 494, '워터맨 TGR 텅스텐지그헤드 볼락 지그 0.5g / 무게: 1g', 0.00, 2, 7200.00),
(969, 494, '잇베이트 리틀핀 산천어 마이크로 메탈지그 볼락 메탈 / 무게: 3g / 색상: #1', 0.00, 2, 6000.00),
(970, 494, '잇베이트 리틀핀 산천어 마이크로 메탈지그 볼락 메탈 / 무게: 3g / 색상: #5', 0.00, 2, 6000.00),
(971, 494, '잇베이트 리틀핀 산천어 마이크로 메탈지그 볼락 메탈 / 무게: 3g / 색상: #7', 0.00, 2, 6000.00),
(972, 494, '잇베이트 리틀핀 산천어 마이크로 메탈지그 볼락 메탈 / 무게: 5g / 색상: #6', 0.00, 2, 6000.00),
(973, 494, '잇베이트 리틀핀 산천어 마이크로 메탈지그 볼락 메탈 / 무게: 5g / 색상: #4', 0.00, 2, 6000.00);

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

--
-- 테이블의 덤프 데이터 `product`
--

INSERT INTO `product` (`ix`, `user_ix`, `account_ix`, `category_ix`, `name`, `memo`, `create_at`, `update_at`) VALUES
(52, 1, 10, 1, 'NS 로드스알파 메탈리코', '', '2025-01-09 17:55:54', '2025-01-09 17:55:54');

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

--
-- 테이블의 덤프 데이터 `product_option`
--

INSERT INTO `product_option` (`ix`, `product_ix`, `name`, `value`, `created_at`, `updated_at`) VALUES
(220, 52, '규격', 'S-682', '2025-01-09 17:55:54', '2025-01-09 17:55:54'),
(221, 52, '규격', 'B-662', '2025-01-09 17:55:54', '2025-01-09 17:55:54');

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

--
-- 테이블의 덤프 데이터 `product_option_combination`
--

INSERT INTO `product_option_combination` (`ix`, `product_ix`, `combination_key`, `cost_price`, `stock`, `created_at`, `updated_at`) VALUES
(170, 52, 'S-682', 45000.00, 10, '2025-01-09 17:55:54', '2025-01-09 17:55:54'),
(171, 52, 'B-662', 45000.00, 10, '2025-01-09 17:55:54', '2025-01-09 17:55:54');

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

--
-- 테이블의 덤프 데이터 `product_option_market_price`
--

INSERT INTO `product_option_market_price` (`ix`, `product_option_comb_ix`, `market_ix`, `price`, `created_at`, `updated_at`) VALUES
(299, 170, 1, 68000.00, '2025-01-09 17:55:54', '2025-01-09 17:55:54'),
(300, 170, 2, 68000.00, '2025-01-09 17:55:54', '2025-01-09 17:55:54'),
(301, 171, 1, 68000.00, '2025-01-09 17:55:54', '2025-01-09 17:55:54'),
(302, 171, 2, 68000.00, '2025-01-09 17:55:54', '2025-01-09 17:55:54');

-- --------------------------------------------------------

--
-- Stand-in structure for view `product_search_view`
-- (See below for the actual view)
--
CREATE TABLE `product_search_view` (
`product_name` varchar(500)
,`combination_key` varchar(255)
,`cost_price` decimal(10,2)
,`stock` int(11)
,`price` decimal(10,2)
,`market_ix` int(11)
,`market_name` varchar(200)
,`product_user_ix` int(11)
,`market_user_ix` int(11)
);

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

-- --------------------------------------------------------

--
-- 뷰 구조 `product_search_view`
--
DROP TABLE IF EXISTS `product_search_view`;

CREATE ALGORITHM=UNDEFINED DEFINER=`` SQL SECURITY DEFINER VIEW `product_search_view`  AS SELECT `p`.`name` AS `product_name`, `poc`.`combination_key` AS `combination_key`, `poc`.`cost_price` AS `cost_price`, `poc`.`stock` AS `stock`, `pomp`.`price` AS `price`, `pomp`.`market_ix` AS `market_ix`, `m`.`market_name` AS `market_name`, `p`.`user_ix` AS `product_user_ix`, `m`.`user_ix` AS `market_user_ix` FROM (((`product` `p` join `product_option_combination` `poc` on(`p`.`ix` = `poc`.`product_ix`)) join `product_option_market_price` `pomp` on(`poc`.`ix` = `pomp`.`product_option_comb_ix`)) join `market` `m` on(`m`.`ix` = `pomp`.`market_ix`)) ;

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
  ADD UNIQUE KEY `idx_market_order` (`market_ix`,`order_number`),
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
  MODIFY `ix` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=514;

--
-- 테이블의 AUTO_INCREMENT `order_details`
--
ALTER TABLE `order_details`
  MODIFY `ix` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=974;

--
-- 테이블의 AUTO_INCREMENT `product`
--
ALTER TABLE `product`
  MODIFY `ix` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=53;

--
-- 테이블의 AUTO_INCREMENT `product_option`
--
ALTER TABLE `product_option`
  MODIFY `ix` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=222;

--
-- 테이블의 AUTO_INCREMENT `product_option_combination`
--
ALTER TABLE `product_option_combination`
  MODIFY `ix` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=172;

--
-- 테이블의 AUTO_INCREMENT `product_option_market_price`
--
ALTER TABLE `product_option_market_price`
  MODIFY `ix` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=303;

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
