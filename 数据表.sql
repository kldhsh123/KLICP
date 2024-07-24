-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- 主机： localhost
-- 生成日期： 2024-07-25 07:25:03
-- 服务器版本： 5.6.50
-- PHP 版本： 8.1.27

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- 数据库： `icp_kldhsh_top`
--

-- --------------------------------------------------------

--
-- 表的结构 `ApprovedSites`
--

CREATE TABLE `ApprovedSites` (
  `id` int(11) NOT NULL,
  `number` varchar(255) NOT NULL,
  `siteName` varchar(255) NOT NULL,
  `address` varchar(255) NOT NULL,
  `description` varchar(255) NOT NULL,
  `owner` char(255) NOT NULL,
  `email` text NOT NULL,
  `verification_date` date DEFAULT NULL,
  `ip_address` varchar(45) NOT NULL,
  `exclude` tinyint(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- 表的结构 `PendingSites`
--

CREATE TABLE `PendingSites` (
  `id` int(11) NOT NULL,
  `number` varchar(255) NOT NULL,
  `siteName` varchar(255) NOT NULL,
  `address` varchar(255) NOT NULL,
  `description` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `email` text NOT NULL,
  `ip_address` varchar(45) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- 表的结构 `Violations`
--

CREATE TABLE `Violations` (
  `id` int(11) NOT NULL,
  `number` varchar(255) NOT NULL,
  `siteName` varchar(255) NOT NULL,
  `address` varchar(255) NOT NULL,
  `description` varchar(255) NOT NULL,
  `owner` char(255) NOT NULL,
  `email` text NOT NULL,
  `verification_date` date NOT NULL,
  `ip_address` varchar(45) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- 转储表的索引
--

--
-- 表的索引 `ApprovedSites`
--
ALTER TABLE `ApprovedSites`
  ADD PRIMARY KEY (`id`);

--
-- 表的索引 `PendingSites`
--
ALTER TABLE `PendingSites`
  ADD PRIMARY KEY (`id`);

--
-- 表的索引 `Violations`
--
ALTER TABLE `Violations`
  ADD UNIQUE KEY `id` (`id`);

--
-- 在导出的表使用AUTO_INCREMENT
--

--
-- 使用表AUTO_INCREMENT `ApprovedSites`
--
ALTER TABLE `ApprovedSites`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- 使用表AUTO_INCREMENT `PendingSites`
--
ALTER TABLE `PendingSites`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
