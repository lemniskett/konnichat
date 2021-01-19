-- phpMyAdmin SQL Dump
-- version 5.0.4
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Jan 19, 2021 at 12:47 AM
-- Server version: 10.5.8-MariaDB
-- PHP Version: 7.4.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `dbHaloChat`
--

-- --------------------------------------------------------

--
-- Table structure for table `tbGroupChat`
--

CREATE TABLE `tbGroupChat` (
  `id` varchar(6) COLLATE utf8_unicode_ci NOT NULL,
  `name` char(50) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tbGroupChatMessage`
--

CREATE TABLE `tbGroupChatMessage` (
  `id` smallint(6) NOT NULL,
  `id_groupchat` varchar(6) COLLATE utf8_unicode_ci NOT NULL,
  `username_username` varchar(24) COLLATE utf8_unicode_ci NOT NULL,
  `message` text COLLATE utf8_unicode_ci NOT NULL,
  `type` char(10) COLLATE utf8_unicode_ci NOT NULL,
  `sent_on` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tbGroupChatOwnership`
--

CREATE TABLE `tbGroupChatOwnership` (
  `id_groupchat` varchar(6) COLLATE utf8_unicode_ci NOT NULL,
  `username_username` varchar(24) COLLATE utf8_unicode_ci NOT NULL,
  `privilege` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tbPublicChat`
--

CREATE TABLE `tbPublicChat` (
  `id` mediumint(8) UNSIGNED NOT NULL,
  `username_username` varchar(24) COLLATE utf8_unicode_ci NOT NULL,
  `message` text COLLATE utf8_unicode_ci NOT NULL,
  `type` char(10) COLLATE utf8_unicode_ci NOT NULL,
  `sent_on` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tbUsername`
--

CREATE TABLE `tbUsername` (
  `username` varchar(24) COLLATE utf8_unicode_ci NOT NULL,
  `fullname` char(50) COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(40) COLLATE utf8_unicode_ci NOT NULL,
  `status` char(255) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tbGroupChat`
--
ALTER TABLE `tbGroupChat`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbGroupChatMessage`
--
ALTER TABLE `tbGroupChatMessage`
  ADD PRIMARY KEY (`id`),
  ADD KEY `username_username_groupchatmessage` (`username_username`),
  ADD KEY `id_groupchat_groupchatmessage` (`id_groupchat`);

--
-- Indexes for table `tbGroupChatOwnership`
--
ALTER TABLE `tbGroupChatOwnership`
  ADD KEY `id_groupchat_groupchatownership` (`id_groupchat`),
  ADD KEY `username_username_groupchatownership` (`username_username`);

--
-- Indexes for table `tbPublicChat`
--
ALTER TABLE `tbPublicChat`
  ADD PRIMARY KEY (`id`),
  ADD KEY `username_username_publicchat` (`username_username`) USING BTREE;

--
-- Indexes for table `tbUsername`
--
ALTER TABLE `tbUsername`
  ADD PRIMARY KEY (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tbGroupChatMessage`
--
ALTER TABLE `tbGroupChatMessage`
  MODIFY `id` smallint(6) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbPublicChat`
--
ALTER TABLE `tbPublicChat`
  MODIFY `id` mediumint(8) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `tbGroupChatMessage`
--
ALTER TABLE `tbGroupChatMessage`
  ADD CONSTRAINT `id_groupchat_groupchatmessage` FOREIGN KEY (`id_groupchat`) REFERENCES `tbGroupChat` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `username_username_groupchatmessage` FOREIGN KEY (`username_username`) REFERENCES `tbUsername` (`username`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `tbGroupChatOwnership`
--
ALTER TABLE `tbGroupChatOwnership`
  ADD CONSTRAINT `id_groupchat_groupchatownership` FOREIGN KEY (`id_groupchat`) REFERENCES `tbGroupChat` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `username_username_groupchatownership` FOREIGN KEY (`username_username`) REFERENCES `tbUsername` (`username`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `tbPublicChat`
--
ALTER TABLE `tbPublicChat`
  ADD CONSTRAINT `username_username` FOREIGN KEY (`username_username`) REFERENCES `tbUsername` (`username`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
