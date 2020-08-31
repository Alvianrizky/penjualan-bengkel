-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Aug 27, 2020 at 08:18 AM
-- Server version: 5.7.26
-- PHP Version: 7.2.18

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `bengkel`
--

-- --------------------------------------------------------

--
-- Table structure for table `groups`
--

DROP TABLE IF EXISTS `groups`;
CREATE TABLE IF NOT EXISTS `groups` (
  `id` mediumint(8) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(20) NOT NULL,
  `description` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `groups`
--

INSERT INTO `groups` (`id`, `name`, `description`) VALUES
(1, 'admin', 'Administrator'),
(2, 'members', 'General User');

-- --------------------------------------------------------

--
-- Table structure for table `item`
--

DROP TABLE IF EXISTS `item`;
CREATE TABLE IF NOT EXISTS `item` (
  `ItemID` int(11) NOT NULL AUTO_INCREMENT,
  `NamaItem` varchar(100) NOT NULL,
  `Deskripsi` varchar(300) NOT NULL,
  `Stok` smallint(6) NOT NULL,
  `HargaBeli` float NOT NULL,
  `HargaJual` float NOT NULL,
  `Terjual` smallint(6) NOT NULL,
  `ReOrder` int(6) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`ItemID`)
) ENGINE=MyISAM AUTO_INCREMENT=21 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `item`
--

INSERT INTO `item` (`ItemID`, `NamaItem`, `Deskripsi`, `Stok`, `HargaBeli`, `HargaJual`, `Terjual`, `ReOrder`, `created_at`, `updated_at`) VALUES
(5, 'Kampas Kopling', 'Kampas Kopling', 99, 50000, 55000, 3, 20, '2020-07-08 11:06:01', '2020-07-22 01:25:14'),
(9, 'Minyak Rem(besar)', 'Minyak Rem(besar)', 0, 17500, 20000, 0, 20, '2020-07-21 09:31:07', '0000-00-00 00:00:00'),
(8, 'Minyak Rem(kecil)', 'Minyak Rem(kecil)', 0, 7000, 10000, 0, 20, '2020-07-21 09:30:07', '0000-00-00 00:00:00'),
(10, 'Air Radiator', 'Air Radiator', 0, 17000, 20000, 0, 20, '2020-07-21 09:31:39', '0000-00-00 00:00:00'),
(11, 'Oli Mesran', 'Oli Mesran', 0, 22000, 25000, 0, 20, '2020-07-21 09:32:00', '0000-00-00 00:00:00'),
(12, 'MPX 1', 'MPX 1', 0, 40000, 45000, 0, 20, '2020-07-21 09:32:27', '0000-00-00 00:00:00'),
(13, 'Chain lube', 'Chain lube', 0, 12000, 15000, 0, 20, '2020-07-21 09:32:47', '0000-00-00 00:00:00'),
(14, 'Busy 4T', 'Busy 4T', 0, 12000, 15000, 0, 20, '2020-07-21 09:33:14', '0000-00-00 00:00:00'),
(15, 'Busy 2T', 'Busy 2T', 0, 12000, 15000, 0, 20, '2020-07-21 09:33:59', '0000-00-00 00:00:00'),
(16, 'Bohlam Depan Honda', 'Bohlam Depan Honda', 0, 18000, 20000, 0, 20, '2020-07-21 09:34:22', '0000-00-00 00:00:00'),
(17, 'Kampas Ganda Depan', 'Kampas Ganda Depan', 0, 35000, 38000, 0, 20, '2020-07-21 09:34:42', '0000-00-00 00:00:00'),
(18, 'Kabel Gas Mio', 'Kabel Gas Mio', 0, 22000, 25000, 0, 20, '2020-07-21 09:35:03', '0000-00-00 00:00:00'),
(19, 'Ban Dalam', 'Ban Dalam', 0, 17500, 20000, 0, 20, '2020-07-21 09:36:02', '0000-00-00 00:00:00'),
(20, 'Bohlam Sein', 'Bohlam Sein', 0, 10000, 15000, 0, 20, '2020-07-21 09:36:18', '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `jasaservice`
--

DROP TABLE IF EXISTS `jasaservice`;
CREATE TABLE IF NOT EXISTS `jasaservice` (
  `JasaServiceID` int(11) NOT NULL AUTO_INCREMENT,
  `NamaService` varchar(20) NOT NULL,
  `BiayaService` float NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`JasaServiceID`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `jasaservice`
--

INSERT INTO `jasaservice` (`JasaServiceID`, `NamaService`, `BiayaService`, `created_at`, `updated_at`) VALUES
(1, 'Service Ringan - 1', 10000, '2020-07-13 01:54:31', '0000-00-00 00:00:00'),
(2, 'Service Ringan - 2', 20000, '2020-07-13 01:55:33', '0000-00-00 00:00:00'),
(3, 'Service Ringan - 3', 30000, '2020-07-13 01:55:52', '0000-00-00 00:00:00'),
(4, 'Service Sedang', 75000, '2020-07-13 01:56:09', '0000-00-00 00:00:00'),
(5, 'Service Berat', 150000, '2020-07-13 01:56:23', '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `kendaraan`
--

DROP TABLE IF EXISTS `kendaraan`;
CREATE TABLE IF NOT EXISTS `kendaraan` (
  `KendaraanID` int(11) NOT NULL AUTO_INCREMENT,
  `NoPolisi` varchar(10) NOT NULL,
  `NoRangka` varchar(50) NOT NULL,
  `NoMesin` varchar(20) NOT NULL,
  `TipeMotor` varchar(100) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`KendaraanID`)
) ENGINE=MyISAM AUTO_INCREMENT=9 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `kendaraan`
--

INSERT INTO `kendaraan` (`KendaraanID`, `NoPolisi`, `NoRangka`, `NoMesin`, `TipeMotor`, `created_at`, `updated_at`) VALUES
(2, 'AB 4510 FA', 'GJT5O8R0F9S21H7PG', 'JPK0UOC4S', 'HONDA CBR105R', '2020-07-03 06:48:04', '2020-07-21 09:39:06'),
(4, 'B 4581 AA', '701RO6T8KHMY2NPUB', 'OM1YHDWPT', 'YAMAHA JUPITER MX', '2020-07-06 05:19:38', '2020-07-21 09:39:19'),
(5, 'F 4510 BD', 'F5987TSIJWE6VD0Y3', 'BZLTU8JC7', 'HONDA VARIO 125', '2020-07-06 05:20:51', '2020-07-21 09:39:42'),
(6, 'AB 4350 SA', 'UAJKP8FYBSVO1IDCH', 'LP5FVAWUQ', 'HONDA SUPRA X 125', '2020-07-06 05:21:36', '2020-07-21 09:40:02'),
(7, 'AD 0124 MT', 'KU6XAFS12LJC0BE79', 'ADQFNLV6M', 'YAMAHA NMAX', '2020-07-06 05:23:04', '2020-07-21 09:40:36'),
(8, 'AB 3987 PH', 'L17W0AMVN93U2CZFG', 'KC8U5RFYD', 'PCX', '2020-07-06 05:24:50', '2020-07-21 09:40:45');

-- --------------------------------------------------------

--
-- Table structure for table `kustomer`
--

DROP TABLE IF EXISTS `kustomer`;
CREATE TABLE IF NOT EXISTS `kustomer` (
  `KustomerID` int(11) NOT NULL AUTO_INCREMENT,
  `Nama` varchar(100) NOT NULL,
  `NoHp` varchar(13) NOT NULL,
  `Alamat` text NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`KustomerID`)
) ENGINE=MyISAM AUTO_INCREMENT=17 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `kustomer`
--

INSERT INTO `kustomer` (`KustomerID`, `Nama`, `NoHp`, `Alamat`, `created_at`, `updated_at`) VALUES
(13, 'Andi', '083065872149', 'Mlati sleman no 25', '2020-07-07 03:27:15', '2020-07-21 09:36:51'),
(14, 'Maryono', '083584296107', 'jl.parangtritis km 15 yogyakarta', '2020-07-07 03:27:36', '2020-07-21 09:37:06'),
(15, 'Yanto', '085914382706', 'jl.magelang km 25', '2020-07-07 03:28:03', '2020-07-21 09:37:46'),
(16, 'Maryadi', '083479102568', ' wonokromo pleret bantul', '2020-07-07 03:28:27', '2020-07-21 09:37:40');

-- --------------------------------------------------------

--
-- Table structure for table `login_attempts`
--

DROP TABLE IF EXISTS `login_attempts`;
CREATE TABLE IF NOT EXISTS `login_attempts` (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `ip_address` varchar(45) NOT NULL,
  `login` varchar(100) NOT NULL,
  `time` int(11) UNSIGNED DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `mekanik`
--

DROP TABLE IF EXISTS `mekanik`;
CREATE TABLE IF NOT EXISTS `mekanik` (
  `MekanikID` int(11) NOT NULL AUTO_INCREMENT,
  `NamaMekanik` varchar(100) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`MekanikID`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `mekanik`
--

INSERT INTO `mekanik` (`MekanikID`, `NamaMekanik`, `created_at`, `updated_at`) VALUES
(2, 'Gilang', '2020-07-07 03:28:44', '2020-07-21 09:41:46'),
(3, 'Karsa', '2020-07-07 03:28:49', '2020-07-21 09:41:52'),
(4, 'Artawan', '2020-07-07 03:28:56', '2020-07-21 09:42:53'),
(5, 'Daliono', '2020-07-07 03:29:06', '2020-07-21 09:43:11');

-- --------------------------------------------------------

--
-- Table structure for table `pembelian`
--

DROP TABLE IF EXISTS `pembelian`;
CREATE TABLE IF NOT EXISTS `pembelian` (
  `PembelianID` int(11) NOT NULL AUTO_INCREMENT,
  `TotalHarga` float NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`PembelianID`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `pembelian`
--

INSERT INTO `pembelian` (`PembelianID`, `TotalHarga`, `created_at`, `updated_at`) VALUES
(2, 55000, '2020-07-22 01:24:05', '0000-00-00 00:00:00'),
(3, 55000, '2020-07-22 01:25:18', '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `service`
--

DROP TABLE IF EXISTS `service`;
CREATE TABLE IF NOT EXISTS `service` (
  `ServiceID` int(11) NOT NULL AUTO_INCREMENT,
  `ServiceTiketID` int(11) NOT NULL,
  `MekanikID` int(11) NOT NULL,
  `TotalHarga` float NOT NULL,
  `JasaServiceID` int(11) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`ServiceID`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `servicetiket`
--

DROP TABLE IF EXISTS `servicetiket`;
CREATE TABLE IF NOT EXISTS `servicetiket` (
  `ServiceTiketID` int(11) NOT NULL AUTO_INCREMENT,
  `KustomerID` int(11) NOT NULL,
  `KendaraanID` int(11) NOT NULL,
  `Keterangan` text NOT NULL,
  `Status` int(2) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`ServiceTiketID`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `servicetiket`
--

INSERT INTO `servicetiket` (`ServiceTiketID`, `KustomerID`, `KendaraanID`, `Keterangan`, `Status`, `created_at`, `updated_at`) VALUES
(1, 13, 2, 'Ganti Oli', 0, '2020-07-08 09:38:30', '2020-07-13 05:06:54'),
(2, 14, 4, 'Ganti Oli', 0, '2020-07-08 09:38:37', '2020-07-09 09:31:16'),
(4, 15, 5, 'Ganti Oli', 0, '2020-07-08 09:39:19', '2020-07-13 05:15:56');

-- --------------------------------------------------------

--
-- Table structure for table `subpembelian`
--

DROP TABLE IF EXISTS `subpembelian`;
CREATE TABLE IF NOT EXISTS `subpembelian` (
  `SubPembelianID` int(11) NOT NULL AUTO_INCREMENT,
  `PembelianID` int(11) NOT NULL,
  `ItemID` int(11) NOT NULL,
  `Jumlah` smallint(6) NOT NULL,
  `TotalHarga` float NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`SubPembelianID`)
) ENGINE=MyISAM AUTO_INCREMENT=11 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `subpembelian`
--

INSERT INTO `subpembelian` (`SubPembelianID`, `PembelianID`, `ItemID`, `Jumlah`, `TotalHarga`, `created_at`, `updated_at`) VALUES
(9, 2, 5, 1, 55000, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(10, 3, 5, 1, 55000, '0000-00-00 00:00:00', '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `subpembelian_t`
--

DROP TABLE IF EXISTS `subpembelian_t`;
CREATE TABLE IF NOT EXISTS `subpembelian_t` (
  `SubPembelianID` int(11) NOT NULL AUTO_INCREMENT,
  `ItemID` int(11) NOT NULL,
  `Jumlah` smallint(6) NOT NULL,
  `TotalHarga` float NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`SubPembelianID`)
) ENGINE=MyISAM AUTO_INCREMENT=11 DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `subservice`
--

DROP TABLE IF EXISTS `subservice`;
CREATE TABLE IF NOT EXISTS `subservice` (
  `SubServiceID` int(11) NOT NULL,
  `ServiceID` int(11) NOT NULL,
  `ItemID` int(11) NOT NULL,
  `Jumlah` smallint(6) NOT NULL,
  `Total` float NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`SubServiceID`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `subservice_t`
--

DROP TABLE IF EXISTS `subservice_t`;
CREATE TABLE IF NOT EXISTS `subservice_t` (
  `SubServiceID` int(11) NOT NULL AUTO_INCREMENT,
  `ItemID` int(11) NOT NULL,
  `Jumlah` smallint(6) NOT NULL,
  `Total` float NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`SubServiceID`)
) ENGINE=MyISAM AUTO_INCREMENT=23 DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `ip_address` varchar(45) NOT NULL,
  `username` varchar(100) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `salt` varchar(255) DEFAULT NULL,
  `email` varchar(254) NOT NULL,
  `activation_selector` varchar(255) DEFAULT NULL,
  `activation_code` varchar(255) DEFAULT NULL,
  `forgotten_password_selector` varchar(255) DEFAULT NULL,
  `forgotten_password_code` varchar(255) DEFAULT NULL,
  `forgotten_password_time` int(11) UNSIGNED DEFAULT NULL,
  `remember_selector` varchar(255) DEFAULT NULL,
  `remember_code` varchar(255) DEFAULT NULL,
  `created_on` int(11) UNSIGNED NOT NULL,
  `last_login` int(11) UNSIGNED DEFAULT NULL,
  `active` tinyint(1) UNSIGNED DEFAULT NULL,
  `first_name` varchar(50) DEFAULT NULL,
  `last_name` varchar(50) DEFAULT NULL,
  `company` varchar(100) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uc_email` (`email`),
  UNIQUE KEY `uc_activation_selector` (`activation_selector`),
  UNIQUE KEY `uc_forgotten_password_selector` (`forgotten_password_selector`),
  UNIQUE KEY `uc_remember_selector` (`remember_selector`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `ip_address`, `username`, `password`, `salt`, `email`, `activation_selector`, `activation_code`, `forgotten_password_selector`, `forgotten_password_code`, `forgotten_password_time`, `remember_selector`, `remember_code`, `created_on`, `last_login`, `active`, `first_name`, `last_name`, `company`, `phone`) VALUES
(1, '127.0.0.1', 'administrator', '$2y$08$NoJvhiZoU77l3Oqax5.V0u5HBkj4yvDc0YQ0G7NKXCWz7BqhEAYMq', NULL, 'admin@admin.com', NULL, '', NULL, NULL, NULL, NULL, NULL, 1268889823, 2020, 1, 'Admin', 'istrator', 'ADMIN', '0'),
(3, '::1', 'member', '$2y$08$fPKElG4aLDqzasS7WNfNse06TjK0FpTclrAfw.xtkVjlcVnULyD9.', NULL, 'member@member.com', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2020, 2020, 1, 'member', NULL, '', '');

-- --------------------------------------------------------

--
-- Table structure for table `users_groups`
--

DROP TABLE IF EXISTS `users_groups`;
CREATE TABLE IF NOT EXISTS `users_groups` (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` int(11) UNSIGNED NOT NULL,
  `group_id` mediumint(8) UNSIGNED NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uc_users_groups` (`user_id`,`group_id`),
  KEY `fk_users_groups_users1_idx` (`user_id`),
  KEY `fk_users_groups_groups1_idx` (`group_id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `users_groups`
--

INSERT INTO `users_groups` (`id`, `user_id`, `group_id`) VALUES
(3, 1, 1),
(4, 3, 2);

--
-- Constraints for dumped tables
--

--
-- Constraints for table `users_groups`
--
ALTER TABLE `users_groups`
  ADD CONSTRAINT `fk_users_groups_groups1` FOREIGN KEY (`group_id`) REFERENCES `groups` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_users_groups_users1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
