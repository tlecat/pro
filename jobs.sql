-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 19, 2024 at 09:21 AM
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
-- Database: `work`
--

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `job_id` int(50) NOT NULL COMMENT 'รหัสงาน',
  `user_id` int(50) NOT NULL COMMENT 'รหัสพนักงาน',
  `supervisor_id` int(50) NOT NULL COMMENT 'รหัสผู้สั่งงาน',
  `job_title` varchar(255) NOT NULL COMMENT 'ชื่อเอกสาร',
  `job_level` varchar(10) NOT NULL COMMENT 'ระดับงาน',
  `job_description` text NOT NULL COMMENT 'รายละเอียดงาน',
  `due_datetime` datetime NOT NULL COMMENT 'วันและเวลาที่กำหนด',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() COMMENT 'วันที่สร้าง',
  `jobs_file` varchar(100) NOT NULL COMMENT 'ไฟล์ที่แนบ',
  `end_date` datetime NOT NULL COMMENT 'วันที่ส่ง'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `jobs`
--

INSERT INTO `jobs` (`job_id`, `user_id`, `supervisor_id`, `job_title`, `job_level`, `job_description`, `due_datetime`, `created_at`, `jobs_file`, `end_date`) VALUES
(11, 1, 0, 'test', '', 'retyui', '2024-11-18 13:37:00', '2024-08-06 06:37:21', 'วิธีการใช้งานเครื่องปริ้น.pdf', '0000-00-00 00:00:00'),
(12, 3, 0, 'lm', '', '2344', '2024-11-18 15:15:00', '2024-08-16 08:23:19', '5DO_5DON\'T.pdf', '2024-08-16 00:00:00'),
(13, 3, 0, 'การบ้าน', '', '123', '2024-11-18 14:33:00', '2024-08-26 07:33:26', 'ระเบียบปฏิบัติหน้า1.pdf', '2024-08-27 00:00:00');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`job_id`),
  ADD KEY `fk_mable` (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `job_id` int(50) NOT NULL AUTO_INCREMENT COMMENT 'รหัสงาน', AUTO_INCREMENT=14;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `jobs`
--
ALTER TABLE `jobs`
  ADD CONSTRAINT `fk_mable` FOREIGN KEY (`user_id`) REFERENCES `mable` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
