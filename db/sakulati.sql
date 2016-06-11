-- phpMyAdmin SQL Dump
-- version 3.2.4
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Jun 12, 2016 at 03:49 AM
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
-- Table structure for table `checklist`
--

CREATE TABLE IF NOT EXISTS `checklist` (
  `id` int(11) NOT NULL,
  `id_edu` int(11) DEFAULT NULL,
  `name` text,
  `keterangan` text,
  PRIMARY KEY (`id`),
  KEY `id_edu` (`id_edu`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `checklist`
--

INSERT INTO `checklist` (`id`, `id_edu`, `name`, `keterangan`) VALUES
(1, 3, 'Sanitasi lingkungan', 'Pembersihan lingkungan dari sisa cangkang buah, tanaman pengganggu dan membersihkan tanaman yang terinfeksi'),
(2, 3, 'Pemangkasan', 'Mempertahankan 3-4 batang primer'),
(3, 3, 'Pemupukan', 'Pemupukan dilakukan dengan membuat alur 10 CM disekeliling batang'),
(4, 3, 'Penyiraman', 'Penyiraman tidak perlu berlebihan'),
(5, 4, '{"Bebas pengerat buah cacao":"1","Ada pengerat buah cacao":"2"}', '{"1":"do nothing","2":"Gunakan pestisida alami untuk mengusir pengerat buah cacao"}'),
(6, 4, '{"Daun hijau":"1","Daun berwarna kuning":"2","Pinggiran daun berwarna coklat":"3","Daun berwarna coklat":"4","Daun muda Menggulung":"5"}', '{"1":"Daun sehat","2":"Tanaman kekurangan nitrogen","3":"tanaman kekurangan kalium","4":"Tanaman kekurangan phospor","5":"Tanaman kekurangan unsur mikro"}');

-- --------------------------------------------------------

--
-- Table structure for table `cities`
--

CREATE TABLE IF NOT EXISTS `cities` (
  `id` int(11) NOT NULL,
  `name` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `cities`
--

INSERT INTO `cities` (`id`, `name`) VALUES
(1, 'Jakarta'),
(2, 'Palu');

-- --------------------------------------------------------

--
-- Table structure for table `cost`
--

CREATE TABLE IF NOT EXISTS `cost` (
  `id` int(11) NOT NULL,
  `des` text,
  `id_masatanam` int(11) DEFAULT NULL,
  `id_lahan` int(11) DEFAULT NULL,
  `harga` decimal(12,2) DEFAULT NULL,
  `id_stock` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `id_masatanam` (`id_masatanam`),
  KEY `id_lahan` (`id_lahan`),
  KEY `id_stock` (`id_stock`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `cost`
--

INSERT INTO `cost` (`id`, `des`, `id_masatanam`, `id_lahan`, `harga`, `id_stock`) VALUES
(1, 'Penggunaan pupuk kompos', 1, 3, 120000.00, 1),
(2, 'Penggunaan gunting tanaman selama masa tanam', 1, 3, 7500.00, 2),
(3, 'Sewa genset untuk penerangan saat panen', 1, 3, 150000.00, NULL),
(4, 'Penggunaan pupuk kompos', 2, 8, 120000.00, 3),
(5, 'Sewa genset untuk penerangan saat panen', 2, 8, 150000.00, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `detail_lahan`
--

CREATE TABLE IF NOT EXISTS `detail_lahan` (
  `id` int(11) DEFAULT NULL,
  `id_lahan` int(11) DEFAULT NULL,
  `ph_tanah` varchar(20) DEFAULT NULL,
  `kadar_air` varchar(50) DEFAULT NULL,
  `add_data` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `detail_lahan`
--

INSERT INTO `detail_lahan` (`id`, `id_lahan`, `ph_tanah`, `kadar_air`, `add_data`) VALUES
(1, 7, '6.5', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `detail_panen`
--

CREATE TABLE IF NOT EXISTS `detail_panen` (
  `id` int(11) NOT NULL,
  `id_panen` int(11) DEFAULT NULL,
  `id_objek` int(11) DEFAULT NULL,
  `add_data` text,
  PRIMARY KEY (`id`),
  KEY `id_panen` (`id_panen`),
  KEY `id_objek` (`id_objek`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `detail_panen`
--


-- --------------------------------------------------------

--
-- Table structure for table `detail_penyusutan`
--

CREATE TABLE IF NOT EXISTS `detail_penyusutan` (
  `id` int(11) NOT NULL,
  `id_stock` int(11) DEFAULT NULL,
  `keterangan` text,
  `nilai_penyusutan` decimal(12,2) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `id_stock` (`id_stock`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `detail_penyusutan`
--

INSERT INTO `detail_penyusutan` (`id`, `id_stock`, `keterangan`, `nilai_penyusutan`) VALUES
(1, 1, 'penggunaan pupuk pada tanggal 2015-04-25', 120000.00),
(2, 2, 'penggunaan gunting untuk masa tanam april 2015 sampai october 2015', 7500.00),
(3, 3, 'penggunaan pupuk pada tanggal 2015-05-26', 120000.00);

-- --------------------------------------------------------

--
-- Table structure for table `education`
--

CREATE TABLE IF NOT EXISTS `education` (
  `id` int(11) NOT NULL,
  `name` varchar(50) DEFAULT NULL,
  `id_jenispertanian` int(11) DEFAULT NULL,
  `add_data` text,
  PRIMARY KEY (`id`),
  KEY `id_jenispertanian` (`id_jenispertanian`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `education`
--

INSERT INTO `education` (`id`, `name`, `id_jenispertanian`, `add_data`) VALUES
(1, 'Pemilihan bibit', 1, 'ebook'),
(2, 'tehnik penyemaian', 1, 'ebook'),
(3, 'Pemeliharaan tanaman', 1, 'ebook'),
(4, 'Penanggulangan hama', 1, 'ebook'),
(5, 'Pembuatan pestisida untuk pengerat buah cacao', 1, 'ebook');

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
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=32 ;

--
-- Dumping data for table `groups`
--

INSERT INTO `groups` (`group_id`, `group_name`, `group_desc`, `store_id`, `allow_delete`, `allow_edit`, `allow_see_other_location`) VALUES
(14, 'Admin', 'Administrator [Default]', 0, 1, 1, 1),
(25, 'Operator', 'Operator Lapangan', 0, 0, 0, 0),
(29, 'Investor', 'Investor Group', 0, 0, 0, 0),
(31, 'Petani', 'Group Petani', 0, 0, 0, 0);

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
(29, 251, 1),
(25, 245, 1),
(25, 251, 1),
(29, 112, 1),
(29, 145, 1),
(25, 250, 1),
(25, 247, 1),
(25, 161, 1),
(14, 252, 1),
(14, 251, 1),
(14, 250, 1),
(14, 246, 1),
(25, 246, 1),
(14, 245, 1),
(29, 250, 1),
(14, 244, 1);

-- --------------------------------------------------------

--
-- Table structure for table `jadwal`
--

CREATE TABLE IF NOT EXISTS `jadwal` (
  `id` int(11) NOT NULL,
  `id_petani` int(11) DEFAULT NULL,
  `id_lahan` int(11) DEFAULT NULL,
  `id_checklist` int(11) DEFAULT NULL,
  `tipe` varchar(20) DEFAULT NULL,
  `execute` text,
  `id_masatanam` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `id_petani` (`id_petani`),
  KEY `id_lahan` (`id_lahan`),
  KEY `id_checklist` (`id_checklist`),
  KEY `id_masatanam` (`id_masatanam`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `jadwal`
--

INSERT INTO `jadwal` (`id`, `id_petani`, `id_lahan`, `id_checklist`, `tipe`, `execute`, `id_masatanam`) VALUES
(1, 3, 3, 1, 'day', 'monday', 7),
(2, 3, 3, 2, 'full-day', '2015-10-17,2015-10-24,2015-10-31,2015-11-7,2015-11-14,2015-11-21,2015-11-28', 7),
(3, 3, 3, 3, 'date', '19', 7),
(4, 3, 3, 4, 'all-day', NULL, 7);

-- --------------------------------------------------------

--
-- Table structure for table `jenis_klon`
--

CREATE TABLE IF NOT EXISTS `jenis_klon` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `jenis_klon`
--

INSERT INTO `jenis_klon` (`id`, `name`) VALUES
(1, 'Lokal Labuan'),
(2, 'Dark Chocolate');

-- --------------------------------------------------------

--
-- Table structure for table `jenis_pertanian`
--

CREATE TABLE IF NOT EXISTS `jenis_pertanian` (
  `id` int(11) NOT NULL,
  `name` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `jenis_pertanian`
--

INSERT INTO `jenis_pertanian` (`id`, `name`) VALUES
(1, 'Cocoa'),
(2, 'Padi');

-- --------------------------------------------------------

--
-- Table structure for table `kondisi_daun`
--

CREATE TABLE IF NOT EXISTS `kondisi_daun` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `remark` varchar(100) NOT NULL,
  `code` varchar(5) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `kondisi_daun`
--

INSERT INTO `kondisi_daun` (`id`, `name`, `remark`, `code`) VALUES
(1, 'Daun Normal', 'Sempurna dan hijau', 'DN'),
(2, 'Daun berwarna kuning', 'Kekurangan Nitrogen', 'DK'),
(3, 'Pinggiran daun berwarna coklat', 'Kekurangan Kalium', 'PC'),
(4, 'Seluruh helai daun berwarna coklat', 'Kekurangan Phospor', 'DC'),
(5, 'Daun muda menggulung', 'Kekurangan unsur mikro', 'DG'),
(6, 'Daun ke 2-3 dari pucuk jika di petik pangkal daun terdapat 3 noktah merah', 'Serangan VSD', 'VSD');

-- --------------------------------------------------------

--
-- Table structure for table `lahan`
--

CREATE TABLE IF NOT EXISTS `lahan` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) DEFAULT NULL,
  `latitude_longtitude` varchar(200) DEFAULT NULL,
  `luas` decimal(10,2) DEFAULT NULL,
  `id_jenispertanian` int(11) DEFAULT NULL,
  `type` char(1) DEFAULT NULL,
  `status` tinyint(1) DEFAULT NULL,
  `id_lahanutama` int(11) DEFAULT NULL,
  `terakhir_panen` date DEFAULT NULL,
  `jumlah_pohon` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id_lahanutama` (`id_lahanutama`),
  KEY `id_jenispertanian` (`id_jenispertanian`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=16 ;

--
-- Dumping data for table `lahan`
--

INSERT INTO `lahan` (`id`, `name`, `latitude_longtitude`, `luas`, `id_jenispertanian`, `type`, `status`, `id_lahanutama`, `terakhir_panen`, `jumlah_pohon`) VALUES
(1, 'palundarmain1', '-0.902900, 119.848412', 1000.00, 1, 'M', 1, NULL, NULL, 0),
(2, 'palundarclus1', '-0.902900, 119.848412', 400.00, 1, 'C', 1, 1, '2016-01-19', 0),
(3, 'palundarclus2', '-0.902900, 119.848412', 300.00, 1, 'C', 1, 1, '2015-10-08', 0),
(4, 'palundarclus3', '-0.902900, 119.848412', 300.00, 1, 'C', 1, 1, '2015-12-05', 0),
(5, 'paluzulmain1', '-0.903901, 119.848311', 300.00, 1, 'M', 1, NULL, '2016-01-05', 0),
(6, 'palujaumain1', '-0.903901, 119.848311', 300.00, 1, 'M', 1, NULL, NULL, 0),
(7, 'palu jau cluter cemara', '-0.903901, 119.848311', 185.00, 1, 'C', 1, 6, '2015-12-12', 0),
(8, 'palujauclus2', '-0.903901, 119.848311', 150.00, 1, 'C', 1, 6, '2015-11-12', 0),
(9, 'Palu Utara', '-0.924900, 129.848412', NULL, NULL, 'M', 0, NULL, NULL, 0),
(11, 'Parung Tenggara', '-0.924955, 129.848412', 900.00, NULL, 'M', 1, NULL, NULL, 0),
(12, 'Lewuimunding', '-0.924900, 129.848412', 350.00, NULL, 'M', 1, NULL, NULL, 0),
(14, 'Inkopad Selatan', '-0.924900, 129.848412', 350.00, 1, 'C', 1, 12, NULL, 500),
(15, 'Ciracas Subur 1', '-0.884900, 119.848412', 900.00, 1, 'C', 1, 11, NULL, 850);

-- --------------------------------------------------------

--
-- Table structure for table `lahan_role`
--

CREATE TABLE IF NOT EXISTS `lahan_role` (
  `id_user` int(11) NOT NULL,
  `id_lahan` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `lahan_role`
--

INSERT INTO `lahan_role` (`id_user`, `id_lahan`) VALUES
(60, 15),
(60, 14);

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
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=42 ;

--
-- Dumping data for table `log`
--

INSERT INTO `log` (`id`, `user_id`, `datetime`, `module_id`, `ip_address`, `keterangan`, `store_id`) VALUES
(1, 2, '2016-05-05 17:07:47', 1, '::1', 'Login Success', 0),
(2, 2, '2016-05-05 17:38:25', 1, '::1', 'Login Success', 0),
(3, 2, '2016-05-06 00:05:48', 1, '::1', 'Login Success', 0),
(4, 2, '2016-05-06 00:27:26', 244, '::1', 'Add Management Lahan [] Success', 0),
(5, 2, '2016-05-06 00:31:11', 244, '::1', 'Update Management Lahan-3', 0),
(6, 2, '2016-05-06 00:34:28', 244, '::1', 'Delete Barang 3', 0),
(7, 2, '2016-05-20 20:16:21', 1, '::1', 'Login Success', 0),
(8, 2, '2016-05-21 00:48:17', 1, '::1', 'Login Success', 0),
(9, 2, '2016-05-21 00:52:27', 111, '::1', 'Logout Success', 0),
(10, 2, '2016-05-21 00:53:03', 1, '::1', 'Login Success', 0),
(11, 2, '2016-05-21 21:39:23', 1, '::1', 'Login Success', 0),
(12, 2, '2016-05-21 22:14:21', 244, '::1', 'Add Management Lahan [] Success', 0),
(13, 2, '2016-05-21 22:17:17', 244, '::1', 'Add Management Lahan [] Success', 0),
(14, 2, '2016-05-21 22:18:49', 244, '::1', 'Add Management Lahan [] Success', 0),
(15, 2, '2016-05-21 22:25:01', 244, '::1', 'Update Management Lahan-11', 0),
(16, 2, '2016-05-21 23:30:23', 245, '::1', 'Add Cluster [Inkopad Selatan] Success', 0),
(17, 2, '2016-05-21 23:31:07', 245, '::1', 'Add Cluster [Inkopad Selatan] Success', 0),
(18, 2, '2016-05-22 00:05:43', 245, '::1', 'Update Cluster-7', 0),
(19, 2, '2016-05-22 00:59:42', 246, '::1', 'Add Cluster [ISLL0001] Success', 0),
(20, 2, '2016-05-22 01:00:38', 246, '::1', 'Add Cluster [ISLL0001] Success', 0),
(21, 2, '2016-05-22 01:20:55', 246, '::1', 'Update Pohon-1', 0),
(22, 2, '2016-05-22 01:21:56', 246, '::1', 'Update Pohon-1', 0),
(23, 2, '2016-05-22 01:22:25', 246, '::1', 'Update Pohon-1', 0),
(24, 2, '2016-05-22 01:34:19', 246, '::1', 'Add Cluster [ISLL0001] Success', 0),
(25, 2, '2016-05-22 01:35:02', 246, '::1', 'Add Cluster [ISLL0002] Success', 0),
(26, 2, '2016-05-22 11:13:30', 1, '::1', 'Login Success', 0),
(27, 2, '2016-05-28 14:17:47', 1, '::1', 'Login Success', 0),
(28, 2, '2016-06-02 22:59:26', 1, '::1', 'Login Success', 0),
(29, 2, '2016-06-04 23:43:21', 1, '::1', 'Login Success', 0),
(30, 2, '2016-06-05 00:02:14', 247, '::1', 'Add Training Pengambilan Data [] Success', 0),
(31, 2, '2016-06-07 05:25:18', 1, '::1', 'Login Success', 0),
(32, 2, '2016-06-07 05:29:41', 247, '::1', 'Add Training Pengambilan Data [2] Success', 0),
(33, 2, '2016-06-09 05:12:29', 1, '::1', 'Login Success', 0),
(34, 2, '2016-06-09 06:02:20', 247, '::1', 'Add Training Pengambilan Data [3] Success', 0),
(35, 2, '2016-06-11 05:08:46', 1, '::1', 'Login Success', 0),
(36, 2, '2016-06-11 05:17:34', 245, '::1', 'Update Cluster-14', 0),
(37, 2, '2016-06-11 05:20:46', 245, '::1', 'Add Cluster [Ciracas Subur 1] Success', 0),
(38, 2, '2016-06-12 00:43:38', 1, '::1', 'Login Success', 0),
(39, 60, '2016-06-12 00:54:01', 1, '::1', 'Login Success', 0),
(40, 60, '2016-06-12 02:09:52', 251, '::1', 'Logout Success', 0),
(41, 61, '2016-06-12 02:09:58', 1, '::1', 'Login Success', 0);

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
-- Table structure for table `masa_tanam`
--

CREATE TABLE IF NOT EXISTS `masa_tanam` (
  `id` int(11) NOT NULL,
  `id_lahan` int(11) DEFAULT NULL,
  `awal_masa` date DEFAULT NULL,
  `akhir_masa` date DEFAULT NULL,
  `status` char(1) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `id_lahan` (`id_lahan`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `masa_tanam`
--

INSERT INTO `masa_tanam` (`id`, `id_lahan`, `awal_masa`, `akhir_masa`, `status`) VALUES
(1, 3, '2015-04-28', '2015-10-08', 'D'),
(2, 8, '2015-05-29', '2015-11-12', 'D'),
(3, 4, '2015-06-29', '2015-12-05', 'D'),
(4, 7, '2015-06-28', '2015-12-12', 'D'),
(5, 5, '2015-07-25', '2016-01-05', 'D'),
(6, 2, '2015-07-30', '2016-01-19', 'D'),
(7, 3, '2015-10-25', NULL, 'I'),
(8, 8, '2015-11-28', NULL, 'I'),
(9, 4, '2015-12-26', NULL, 'I'),
(10, 7, '2015-12-27', NULL, 'I'),
(11, 5, '2016-01-24', NULL, 'I'),
(12, 2, '2016-01-29', NULL, 'I');

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
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=253 ;

--
-- Dumping data for table `module`
--

INSERT INTO `module` (`module_id`, `siteID`, `name`, `title`, `is_public`, `is_visible`, `is_mandatory`, `flag`, `module_root_id`) VALUES
(111, 3, 'admin', 'System Management', 0, 1, 0, 12, 0),
(112, 3, 'utama', 'Halaman Utama', 1, 0, 0, 2, 0),
(115, 3, 'static', 'Static Content', 1, 0, 0, 9, 0),
(145, 3, 'edit_profil', 'Edit Profil', 0, 0, 0, 9, 0),
(185, 3, 'admin_log', 'Activity', 0, 0, 0, 9, 111),
(161, 3, 'adm', 'Master Data', 0, 1, 0, NULL, 0),
(186, 3, 'openticket', 'Konsultasi', 0, 1, 0, 15, 0),
(1, 3, 'admin&sub=aplikasi', 'Menu', 0, 0, 0, 7, 111),
(2, 3, 'admin&sub=user', 'User', 0, 0, 0, 1, 111),
(3, 3, 'admin&sub=grup', 'Group', 0, 0, 0, 4, 111),
(4, 3, 'documentation', 'Dokumentasi', 0, 0, 0, 20, 0),
(244, 3, 'lahan', 'Management Lahan', 0, 0, 1, NULL, 161),
(245, 3, 'cluster', 'Management Cluster', 0, 0, 1, NULL, 161),
(246, 3, 'pohon', 'Management Pohon', 0, 0, 1, NULL, 161),
(247, 3, 'training_pengambilan_data', 'Pengambilan Data', 0, 1, 0, 4, 0),
(0, 3, 'Root', 'Root Menu', 0, 0, 0, 1, 0),
(249, 3, 'pohon_sakit', 'Input Pohon Sakit', 0, 1, 0, NULL, 0),
(250, 3, 'report', 'Report', 0, 1, 0, NULL, 0),
(251, 3, 'report_pengambilan_data', 'Pengambilan Data', 0, 0, 1, NULL, 250),
(252, 3, 'operator_lahan', 'Lahan Role', 0, 0, 1, NULL, 161);

-- --------------------------------------------------------

--
-- Table structure for table `objek`
--

CREATE TABLE IF NOT EXISTS `objek` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_pohon` int(11) NOT NULL,
  `date` date NOT NULL,
  `daun_tua` varchar(100) NOT NULL,
  `daun_muda` varchar(100) NOT NULL,
  `bunga` int(11) NOT NULL,
  `buah_kecil` int(11) NOT NULL,
  `buah_dewasa` int(11) NOT NULL,
  `buah_siap_panen` int(11) NOT NULL,
  `PH` varchar(10) NOT NULL,
  `BO` int(11) NOT NULL,
  `KTK` int(11) NOT NULL,
  `sehat_status` int(11) NOT NULL,
  `pytoptora` int(11) NOT NULL,
  `pbk` int(11) NOT NULL,
  `vsd_status` int(11) NOT NULL,
  `panen_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `objek`
--

INSERT INTO `objek` (`id`, `id_pohon`, `date`, `daun_tua`, `daun_muda`, `bunga`, `buah_kecil`, `buah_dewasa`, `buah_siap_panen`, `PH`, `BO`, `KTK`, `sehat_status`, `pytoptora`, `pbk`, `vsd_status`, `panen_id`) VALUES
(1, 3, '2016-06-03', '1', '1', 200, 13, 8, 8, '90', 100, 101, 0, 0, 0, 0, 0),
(2, 2, '2016-06-22', '3', '2', 90, 10, 9, 7, '88,5', 10, 99, 0, 0, 0, 0, 0),
(3, 3, '2016-05-04', '3', '2', 500, 10, 5, 6, '7', 9, 12, 1, 15, 23, 1, 0);

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
-- Table structure for table `panen`
--

CREATE TABLE IF NOT EXISTS `panen` (
  `id` int(11) NOT NULL,
  `tanggal` date DEFAULT NULL,
  `id_petani` int(11) DEFAULT NULL,
  `id_lahan` int(11) DEFAULT NULL,
  `id_masatanam` int(11) DEFAULT NULL,
  `jumlah_panen` decimal(10,2) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `id_petani` (`id_petani`),
  KEY `id_lahan` (`id_lahan`),
  KEY `id_masatanam` (`id_masatanam`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `panen`
--

INSERT INTO `panen` (`id`, `tanggal`, `id_petani`, `id_lahan`, `id_masatanam`, `jumlah_panen`) VALUES
(1, '2015-10-08', 3, 3, 1, 198.40),
(2, '2015-11-12', 5, 8, 2, 234.60),
(3, '2015-12-05', 3, 4, 3, 207.30),
(4, '2015-12-12', 5, 7, 4, 197.40),
(5, '2016-01-05', 4, 5, 5, 230.90),
(6, '2016-01-19', 3, 2, 6, 175.50);

-- --------------------------------------------------------

--
-- Table structure for table `panen_detail`
--

CREATE TABLE IF NOT EXISTS `panen_detail` (
  `id` int(11) NOT NULL,
  `id_panen` int(11) DEFAULT NULL,
  `id_objek` int(11) DEFAULT NULL,
  `add_data` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `panen_detail`
--


-- --------------------------------------------------------

--
-- Table structure for table `pengerjaan`
--

CREATE TABLE IF NOT EXISTS `pengerjaan` (
  `id` int(11) NOT NULL,
  `id_jadwal` int(11) DEFAULT NULL,
  `tanggal` date DEFAULT NULL,
  `status` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `id_jadwal` (`id_jadwal`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `pengerjaan`
--

INSERT INTO `pengerjaan` (`id`, `id_jadwal`, `tanggal`, `status`) VALUES
(1, 1, '2015-10-11', 1),
(2, 4, '2015-10-11', 1),
(3, 4, '2015-10-12', 1),
(4, 4, '2015-10-13', 1),
(5, 4, '2015-10-14', 1),
(6, 4, '2015-10-15', 0),
(7, 4, '2015-10-16', 1),
(8, 2, '2015-10-17', 1),
(9, 4, '2015-10-17', 1),
(10, 4, '2015-10-18', 1),
(11, 3, '2015-10-19', 1),
(12, 4, '2015-10-19', 1);

-- --------------------------------------------------------

--
-- Table structure for table `pohon`
--

CREATE TABLE IF NOT EXISTS `pohon` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `unique_code` varchar(100) NOT NULL,
  `id_lahan` int(11) NOT NULL,
  `latitude_longtitude` varchar(100) NOT NULL,
  `id_jenis_klon` int(11) NOT NULL,
  `umur_pohon` int(11) NOT NULL COMMENT 'dalam tahun',
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_code` (`unique_code`,`id_lahan`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `pohon`
--

INSERT INTO `pohon` (`id`, `unique_code`, `id_lahan`, `latitude_longtitude`, `id_jenis_klon`, `umur_pohon`) VALUES
(1, 'ISLL00012', 3, '-0.924901, 129.848412', 1, 11),
(2, 'ISLL0001', 14, '-0.924900, 129.848412', 1, 10),
(3, 'ISLL0002', 14, '-0.924900, 129.848412', 1, 10);

-- --------------------------------------------------------

--
-- Table structure for table `relation`
--

CREATE TABLE IF NOT EXISTS `relation` (
  `id` int(11) NOT NULL,
  `id_user` int(11) DEFAULT NULL,
  `type` char(1) DEFAULT NULL,
  `id_petani` int(11) DEFAULT NULL,
  `id_lahan` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id_user` (`id_user`),
  KEY `id_petani` (`id_petani`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `relation`
--

INSERT INTO `relation` (`id`, `id_user`, `type`, `id_petani`, `id_lahan`) VALUES
(1, 1, 'I', 3, 0),
(2, 1, 'I', 4, 0),
(3, 2, 'I', 5, 0),
(4, 6, 'S', 3, 0),
(5, 7, 'S', 4, 0),
(6, 8, 'S', 5, 0);

-- --------------------------------------------------------

--
-- Table structure for table `sale`
--

CREATE TABLE IF NOT EXISTS `sale` (
  `id` int(11) DEFAULT NULL,
  `id_pembeli` int(11) DEFAULT NULL,
  `id_penjual` int(11) DEFAULT NULL,
  `description` text,
  `jumlah` int(11) DEFAULT NULL,
  `harga` decimal(12,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `sale`
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
-- Table structure for table `stock`
--

CREATE TABLE IF NOT EXISTS `stock` (
  `id` int(11) NOT NULL,
  `id_user` int(11) DEFAULT NULL,
  `name` varchar(50) DEFAULT NULL,
  `jumlah` int(11) DEFAULT NULL,
  `harga` decimal(12,2) DEFAULT NULL,
  `penyusutan` decimal(12,2) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `id_user` (`id_user`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `stock`
--

INSERT INTO `stock` (`id`, `id_user`, `name`, `jumlah`, `harga`, `penyusutan`) VALUES
(1, 3, 'Pupuk kompos', 50, 200000.00, 120000.00),
(2, 3, 'Gunting tanaman', 1, 15000.00, 7500.00),
(3, 5, 'Pupuk kompos', 100, 400000.00, 120000.00);

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
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=63 ;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`user_id`, `is_superuser`, `fullname`, `username`, `userpassword`, `is_active`, `email`, `lastlogin`, `ket`, `login_session_id`, `additional_info`, `lokasi_id`, `store_id`) VALUES
(2, 1, 'Admin Name', 'admin', 'e10adc3949ba59abbe56e057f20f883e', 1, 'reza@indowebapps.com', '2016-06-12 00:43:38', NULL, 'ab786f46eb1331e2269c736182e18ed7', NULL, 1, 0),
(41, 0, 'Reza Farasdak', 'admin_reza', 'e10adc3949ba59abbe56e057f20f883e', 1, 'rezafarasdak@yahoo.com', '2016-04-12 21:39:41', NULL, 'aebaa897b064bcf03c7f087551c6f4b8', '', 1, 0),
(60, 0, 'I Komang Agus', 'operator_komang', 'e10adc3949ba59abbe56e057f20f883e', 1, 'komang@sakulati.id', '2016-06-12 00:54:01', NULL, '55c571488debc285b3b6285754ddd86b', '', 1, 0),
(61, 0, 'Ferry Agustya Swastika', 'investor_ferry', 'e10adc3949ba59abbe56e057f20f883e', 1, 'ferry@sakulati.id', '2016-06-12 02:09:58', NULL, '9d78be1589647a2fdf77789a3571a91e', '', 1, 0),
(62, 0, 'Joko Supomo', 'Supomo', 'e10adc3949ba59abbe56e057f20f883e', 1, 'joko@gmail.com', '2016-05-22 00:13:24', NULL, NULL, '', 1, 0);

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
(61, 29),
(62, 31);

-- --------------------------------------------------------

--
-- Table structure for table `weather`
--

CREATE TABLE IF NOT EXISTS `weather` (
  `id` int(11) DEFAULT NULL,
  `tanggal` date DEFAULT NULL,
  `id_cities` int(11) DEFAULT NULL,
  `suhu` varchar(20) DEFAULT NULL,
  `tekanan_udara` varchar(20) DEFAULT NULL,
  `angin` varchar(20) DEFAULT NULL,
  `kelembapan` varchar(20) DEFAULT NULL,
  `curah_hujan` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `weather`
--


--
-- Constraints for dumped tables
--

--
-- Constraints for table `checklist`
--
ALTER TABLE `checklist`
  ADD CONSTRAINT `checklist_ibfk_1` FOREIGN KEY (`id_edu`) REFERENCES `education` (`id`);

--
-- Constraints for table `cost`
--
ALTER TABLE `cost`
  ADD CONSTRAINT `cost_ibfk_1` FOREIGN KEY (`id_masatanam`) REFERENCES `masa_tanam` (`id`),
  ADD CONSTRAINT `cost_ibfk_2` FOREIGN KEY (`id_lahan`) REFERENCES `lahan` (`id`),
  ADD CONSTRAINT `cost_ibfk_3` FOREIGN KEY (`id_stock`) REFERENCES `stock` (`id`);

--
-- Constraints for table `detail_panen`
--
ALTER TABLE `detail_panen`
  ADD CONSTRAINT `detail_panen_ibfk_1` FOREIGN KEY (`id_panen`) REFERENCES `panen` (`id`),
  ADD CONSTRAINT `detail_panen_ibfk_2` FOREIGN KEY (`id_objek`) REFERENCES `objek` (`id`);

--
-- Constraints for table `detail_penyusutan`
--
ALTER TABLE `detail_penyusutan`
  ADD CONSTRAINT `detail_penyusutan_ibfk_1` FOREIGN KEY (`id_stock`) REFERENCES `stock` (`id`);

--
-- Constraints for table `education`
--
ALTER TABLE `education`
  ADD CONSTRAINT `education_ibfk_1` FOREIGN KEY (`id_jenispertanian`) REFERENCES `jenis_pertanian` (`id`);

--
-- Constraints for table `jadwal`
--
ALTER TABLE `jadwal`
  ADD CONSTRAINT `jadwal_ibfk_1` FOREIGN KEY (`id_petani`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `jadwal_ibfk_2` FOREIGN KEY (`id_lahan`) REFERENCES `lahan` (`id`),
  ADD CONSTRAINT `jadwal_ibfk_3` FOREIGN KEY (`id_checklist`) REFERENCES `checklist` (`id`),
  ADD CONSTRAINT `jadwal_ibfk_4` FOREIGN KEY (`id_masatanam`) REFERENCES `masa_tanam` (`id`);

--
-- Constraints for table `lahan`
--
ALTER TABLE `lahan`
  ADD CONSTRAINT `lahan_ibfk_1` FOREIGN KEY (`id_lahanutama`) REFERENCES `lahan` (`id`),
  ADD CONSTRAINT `lahan_ibfk_2` FOREIGN KEY (`id_jenispertanian`) REFERENCES `jenis_pertanian` (`id`);

--
-- Constraints for table `masa_tanam`
--
ALTER TABLE `masa_tanam`
  ADD CONSTRAINT `masa_tanam_ibfk_1` FOREIGN KEY (`id_lahan`) REFERENCES `lahan` (`id`);

--
-- Constraints for table `panen`
--
ALTER TABLE `panen`
  ADD CONSTRAINT `panen_ibfk_1` FOREIGN KEY (`id_petani`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `panen_ibfk_2` FOREIGN KEY (`id_lahan`) REFERENCES `lahan` (`id`),
  ADD CONSTRAINT `panen_ibfk_3` FOREIGN KEY (`id_masatanam`) REFERENCES `masa_tanam` (`id`);

--
-- Constraints for table `pengerjaan`
--
ALTER TABLE `pengerjaan`
  ADD CONSTRAINT `pengerjaan_ibfk_1` FOREIGN KEY (`id_jadwal`) REFERENCES `jadwal` (`id`);

--
-- Constraints for table `relation`
--
ALTER TABLE `relation`
  ADD CONSTRAINT `relation_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `relation_ibfk_2` FOREIGN KEY (`id_petani`) REFERENCES `users` (`id`);

--
-- Constraints for table `stock`
--
ALTER TABLE `stock`
  ADD CONSTRAINT `stock_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `users` (`id`);
