-- phpMyAdmin SQL Dump
-- version 3.2.4
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: May 05, 2016 at 09:34 PM
-- Server version: 5.1.44
-- PHP Version: 5.3.1

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `sakulati`
--

-- --------------------------------------------------------

--
-- Table structure for table `cluster`
--

CREATE TABLE IF NOT EXISTS `cluster` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `lahan_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `note` varchar(2048) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

--
-- Dumping data for table `cluster`
--

INSERT INTO `cluster` (`id`, `lahan_id`, `name`, `note`) VALUES
(1, 1, 'Cluster Mawar', 'Cluster pinggir sungai'),
(2, 1, 'Cluster Orange', 'Cluster depan jalan raya'),
(3, 2, 'Cluster Ciliwung', 'Lahan Utama'),
(4, 2, 'Cluster Cisadane', '');

-- --------------------------------------------------------

--
-- Table structure for table `groups`
--

CREATE TABLE IF NOT EXISTS `groups` (
  `group_id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `group_name` varchar(32) NOT NULL DEFAULT '',
  `group_desc` varchar(255) NOT NULL DEFAULT '',
  `store_id` int(11) NOT NULL COMMENT 'If store id = 0, mean public group and cannot edit or delete.',
  `allow_delete` tinyint(1) NOT NULL,
  `allow_edit` tinyint(1) NOT NULL,
  `allow_see_other_location` tinyint(1) NOT NULL,
  PRIMARY KEY (`group_id`),
  UNIQUE KEY `idx_groups` (`group_name`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=31 ;

--
-- Dumping data for table `groups`
--

INSERT INTO `groups` (`group_id`, `group_name`, `group_desc`, `store_id`, `allow_delete`, `allow_edit`, `allow_see_other_location`) VALUES
(14, 'Admin', 'Administrator [Default]', 0, 1, 1, 1),
(25, 'Operator', 'Operator Lapangan', 0, 0, 0, 0),
(29, 'Investor', 'Investor Group', 0, 0, 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `group_module_priv`
--

CREATE TABLE IF NOT EXISTS `group_module_priv` (
  `group_id` smallint(5) unsigned NOT NULL DEFAULT '0',
  `module_id` smallint(6) NOT NULL DEFAULT '0',
  `priv` smallint(2) DEFAULT NULL,
  PRIMARY KEY (`group_id`,`module_id`),
  UNIQUE KEY `group_id` (`group_id`,`module_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `group_module_priv`
--

INSERT INTO `group_module_priv` (`group_id`, `module_id`, `priv`) VALUES
(14, 111, 1),
(14, 161, 1),
(14, 112, 1),
(14, 115, 1),
(14, 145, 1),
(14, 185, 1),
(14, 1, 1),
(14, 2, 1),
(14, 3, 1),
(14, 4, 1),
(14, 186, 1),
(14, 11, 1),
(29, 112, 1),
(29, 145, 1),
(14, 246, 1),
(25, 246, 1),
(14, 245, 1),
(25, 244, 1),
(14, 244, 1);

-- --------------------------------------------------------

--
-- Table structure for table `karyawan`
--

CREATE TABLE IF NOT EXISTS `karyawan` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `email` varchar(50) NOT NULL,
  `karyawan_divisi_id` int(11) NOT NULL,
  `lokasi_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=50 ;

--
-- Dumping data for table `karyawan`
--

INSERT INTO `karyawan` (`id`, `name`, `email`, `karyawan_divisi_id`, `lokasi_id`) VALUES
(41, 'Tri Susilo', '', 1, 3),
(42, 'Anny Sri Andarini', '', 3, 3),
(43, 'Sudarwin', '', 1, 3),
(44, 'Eka Yanti', '', 3, 3),
(45, 'M. Bernando', '', 2, 3),
(46, 'Bambang', '', 2, 3),
(48, 'Yersi Dheryabhin S', 'yersi@gmail.com', 1, 1),
(49, 'Rahadian Reza', 'rezha@bhakti.com', 4, 1);

-- --------------------------------------------------------

--
-- Table structure for table `karyawan_divisi`
--

CREATE TABLE IF NOT EXISTS `karyawan_divisi` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `remark` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

--
-- Dumping data for table `karyawan_divisi`
--

INSERT INTO `karyawan_divisi` (`id`, `name`, `remark`) VALUES
(1, 'Marketing', 'Marketing Perusahaan'),
(2, 'Operational', 'Bagian Operational'),
(3, 'GA', 'General Afair'),
(4, 'BOD', 'Board Of Director');

-- --------------------------------------------------------

--
-- Table structure for table `lahan`
--

CREATE TABLE IF NOT EXISTS `lahan` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `note` varchar(2048) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `lahan`
--

INSERT INTO `lahan` (`id`, `name`, `note`) VALUES
(1, 'Perkebunan Palu Utara', ''),
(2, 'Perkebunan Perhutani Sulawesi II', '');

-- --------------------------------------------------------

--
-- Table structure for table `log`
--

CREATE TABLE IF NOT EXISTS `log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `datetime` datetime NOT NULL,
  `module_id` int(11) NOT NULL,
  `ip_address` varchar(15) NOT NULL,
  `keterangan` varchar(900) NOT NULL,
  `store_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `user_id` (`user_id`,`datetime`,`module_id`,`ip_address`,`keterangan`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `log`
--

INSERT INTO `log` (`id`, `user_id`, `datetime`, `module_id`, `ip_address`, `keterangan`, `store_id`) VALUES
(1, 2, '2016-05-05 17:07:47', 1, '::1', 'Login Success', 0),
(2, 2, '2016-05-05 17:38:25', 1, '::1', 'Login Success', 0);

-- --------------------------------------------------------

--
-- Table structure for table `lokasi`
--

CREATE TABLE IF NOT EXISTS `lokasi` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(3) NOT NULL,
  `remark` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `lokasi`
--

INSERT INTO `lokasi` (`id`, `name`, `remark`) VALUES
(1, 'JKT', 'Jakarta'),
(2, 'BDG', 'Bandung'),
(3, 'SLO', 'Solo');

-- --------------------------------------------------------

--
-- Table structure for table `module`
--

CREATE TABLE IF NOT EXISTS `module` (
  `module_id` smallint(6) NOT NULL AUTO_INCREMENT,
  `siteID` int(11) unsigned DEFAULT NULL,
  `name` varchar(100) DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL,
  `is_public` tinyint(1) DEFAULT '0',
  `is_visible` tinyint(1) DEFAULT '1',
  `is_mandatory` tinyint(1) DEFAULT '0',
  `flag` smallint(2) DEFAULT NULL,
  `module_root_id` int(11) NOT NULL,
  PRIMARY KEY (`module_id`),
  UNIQUE KEY `mod_site` (`siteID`,`name`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=248 ;

--
-- Dumping data for table `module`
--

INSERT INTO `module` (`module_id`, `siteID`, `name`, `title`, `is_public`, `is_visible`, `is_mandatory`, `flag`, `module_root_id`) VALUES
(111, 3, 'admin', 'System Management', 0, 1, 0, 12, 0),
(112, 3, 'utama', 'Halaman Utama', 1, 0, 0, 2, 0),
(115, 3, 'static', 'Static Content', 1, 0, 0, 9, 0),
(145, 3, 'edit_profil', 'Edit Profil', 0, 0, 0, 9, 0),
(185, 3, 'admin_log', 'Activity', 0, 0, 0, 9, 111),
(161, 3, 'adm', 'Master', 0, 1, 0, NULL, 0),
(186, 3, 'openticket', 'Konsultasi', 0, 1, 0, 15, 0),
(1, 3, 'admin&sub=aplikasi', 'Menu', 0, 0, 0, 7, 111),
(2, 3, 'admin&sub=user', 'User', 0, 0, 0, 1, 111),
(3, 3, 'admin&sub=grup', 'Group', 0, 0, 0, 4, 111),
(4, 3, 'documentation', 'Dokumentasi', 0, 0, 0, 20, 0),
(244, 3, 'lahan', 'Management Lahan', 0, 0, 1, NULL, 161),
(245, 3, 'cluster', 'Management Cluster', 0, 0, 1, NULL, 161),
(246, 3, 'pohon', 'Management Pohon', 0, 0, 1, NULL, 161),
(247, 3, 'training_pengambilan_data', 'Pengambilan Data', 0, 1, 1, NULL, 0),
(11, 3, 'karyawan', 'Management Karyawan', 0, 0, 0, 7, 161);

-- --------------------------------------------------------

--
-- Table structure for table `open_ticket`
--

CREATE TABLE IF NOT EXISTS `open_ticket` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ticket_number` varchar(20) NOT NULL,
  `sender_user_id` int(11) NOT NULL,
  `receiver_user_id` int(11) NOT NULL,
  `subject` varchar(1024) NOT NULL,
  `content` text NOT NULL,
  `status` enum('o','c','p') NOT NULL,
  `type` enum('l','k','q','o','p') NOT NULL,
  `date` datetime NOT NULL,
  `parent_ticket_number` varchar(20) NOT NULL,
  `public_status` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=42 ;

--
-- Dumping data for table `open_ticket`
--


-- --------------------------------------------------------

--
-- Table structure for table `pohon`
--

CREATE TABLE IF NOT EXISTS `pohon` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `unique_id` varchar(100) NOT NULL,
  `cluster_id` int(11) NOT NULL,
  `koordinat` int(11) NOT NULL,
  `nama_klon` int(11) NOT NULL,
  `umur_pohon` int(11) NOT NULL COMMENT 'dalam tahun',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `pohon`
--


-- --------------------------------------------------------

--
-- Table structure for table `sites`
--

CREATE TABLE IF NOT EXISTS `sites` (
  `siteID` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `siteName` varchar(32) NOT NULL DEFAULT '',
  `path` text,
  `modulePath` varchar(255) DEFAULT NULL,
  `blockPath` varchar(255) DEFAULT NULL,
  `theme` varchar(100) DEFAULT NULL,
  `default_app` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`siteID`),
  UNIQUE KEY `siteName` (`siteName`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `sites`
--

INSERT INTO `sites` (`siteID`, `siteName`, `path`, `modulePath`, `blockPath`, `theme`, `default_app`) VALUES
(3, 'sakulati', '/Applications/XAMPP/xamppfiles/htdocs/sakulati/', 'modules', 'blocks', 'default', 'utama');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE IF NOT EXISTS `user` (
  `user_id` int(11) NOT NULL AUTO_INCREMENT,
  `is_superuser` tinyint(1) DEFAULT '0',
  `fullname` varchar(100) DEFAULT NULL,
  `username` varchar(32) NOT NULL DEFAULT '',
  `userpassword` varchar(255) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `email` varchar(250) DEFAULT NULL,
  `lastlogin` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `ket` varchar(50) DEFAULT NULL,
  `login_session_id` varchar(255) DEFAULT NULL,
  `additional_info` varchar(255) DEFAULT NULL,
  `lokasi_id` int(11) NOT NULL COMMENT 'Mengacu ke table Lokasi',
  `store_id` int(11) NOT NULL,
  PRIMARY KEY (`user_id`),
  UNIQUE KEY `indeksBaru` (`username`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=62 ;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`user_id`, `is_superuser`, `fullname`, `username`, `userpassword`, `is_active`, `email`, `lastlogin`, `ket`, `login_session_id`, `additional_info`, `lokasi_id`, `store_id`) VALUES
(2, 1, 'Admin Name', 'admin', 'e10adc3949ba59abbe56e057f20f883e', 1, 'reza@indowebapps.com', '2016-05-05 17:38:25', NULL, '1119fefd9d5d2b2e90e32a33d644de91', NULL, 1, 0),
(41, 0, 'Reza Farasdak', 'admin_reza', 'e10adc3949ba59abbe56e057f20f883e', 1, 'rezafarasdak@yahoo.com', '2016-04-12 21:39:41', NULL, 'aebaa897b064bcf03c7f087551c6f4b8', '', 1, 0),
(60, 0, 'I Komang Agus', 'operator_komang', 'e10adc3949ba59abbe56e057f20f883e', 1, 'komang@sakulati.id', '2016-05-05 17:45:04', NULL, NULL, '', 1, 0),
(61, 0, 'Ferry Agustya Swastika', 'investor_ferry', 'e10adc3949ba59abbe56e057f20f883e', 1, 'ferry@sakulati.id', '2016-05-05 17:46:14', NULL, NULL, '', 1, 0);

-- --------------------------------------------------------

--
-- Table structure for table `user_group`
--

CREATE TABLE IF NOT EXISTS `user_group` (
  `user_id` int(11) NOT NULL DEFAULT '0',
  `group_id` smallint(5) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`user_id`,`group_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `user_group`
--

INSERT INTO `user_group` (`user_id`, `group_id`) VALUES
(2, 14),
(41, 14),
(60, 25),
(61, 29);
