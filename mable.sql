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
-- Table structure for table `mable`
--

CREATE TABLE `mable` (
  `id` int(50) NOT NULL COMMENT 'ลำดับผู้ใช้',
  `user_id` int(50) NOT NULL COMMENT 'รหัสพนักงาน',
  `password` varchar(50) NOT NULL,
  `nametitle` varchar(10) NOT NULL COMMENT 'คำนำหน้า',
  `firstname` varchar(100) NOT NULL,
  `lastname` varchar(100) NOT NULL,
  `phone` varchar(10) NOT NULL,
  `email` varchar(50) NOT NULL,
  `userlevel` varchar(1) NOT NULL COMMENT 'ระดับผู้ใช้',
  `img_path` varchar(255) DEFAULT NULL COMMENT 'รูปผู้ใช้',
  `datesave` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `mable`
--

INSERT INTO `mable` (`id`, `user_id`, `password`, `nametitle`, `firstname`, `lastname`, `phone`, `email`, `userlevel`, `img_path`, `datesave`) VALUES
(1, 1111, 'b59c67bf196a4758191e42f76670ceba', 'นาง', 'dek', 'dee', '0610793221', 'fgkbmfg@gmail.com', 'm', '2.jpg', '2024-06-27 08:51:41'),
(2, 2222, '934b535800b1cba8f96a5d72f72f1611', '', 'admin', '123', '0996424444', 'kkpgv@hgvghj', 'a', 'วิธีการใช้งานเครื่องปริ้น.png', '2024-06-28 02:39:15'),
(3, 3333, '2be9bd7a3434f7038ca27d1918de58bd', '', 'ppp', 'ววว', '0610793221', 'dfvgh@dfgh', 'm', 'วิธีการใช้งานเครื่องปริ้น.png', '2024-07-02 03:13:22'),
(5, 4869, '20cf775fa6b5dfe621ade096f5d85d52', 'นางสาว', 'ดลยา', 'บุญครอบ', '09008900', 'pai.got11@gmail.com', 'm', 'unnamed.jpg', '2024-09-01 14:00:46'),
(4, 5555, '6074c6aa3488f3c2dddff2a7ca821aab', 'นาย', 'kanlapaphuek', 'mingchanthuek', '0610793221', '6414631025@rbru.ac.th', 'a', '', '2024-07-05 04:21:25');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `mable`
--
ALTER TABLE `mable`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `id` (`id`),
  ADD UNIQUE KEY `firstname` (`firstname`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `mable`
--
ALTER TABLE `mable`
  MODIFY `id` int(50) NOT NULL AUTO_INCREMENT COMMENT 'ลำดับผู้ใช้', AUTO_INCREMENT=6;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
