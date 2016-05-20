-- phpMyAdmin SQL Dump
-- version 3.2.4
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: May 20, 2016 at 10:45 PM
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

INSERT INTO `detail_panen` (`id`, `id_panen`, `id_objek`, `add_data`) VALUES
(1, 1, 2, '{"Beancount":10,"Fatcontain":50,"Shell":30}'),
(2, 1, 4, '{"Beancount":5,"Fatcontain":70,"Shell":20}'),
(3, 2, 12, '{"Beancount":7,"Fatcontain":60,"Shell":40}'),
(4, 2, 13, '{"Beancount":5,"Fatcontain":80,"Shell":10}');

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
  `id` int(11) NOT NULL,
  `name` varchar(50) DEFAULT NULL,
  `id_user` int(11) DEFAULT NULL,
  `lokasi` text,
  `luas` decimal(10,2) DEFAULT NULL,
  `id_jenispertanian` int(11) DEFAULT NULL,
  `tipe` char(1) DEFAULT NULL,
  `status` tinyint(1) DEFAULT NULL,
  `id_lahanutama` int(11) DEFAULT NULL,
  `terakhir_panen` date DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `id_lahanutama` (`id_lahanutama`),
  KEY `id_jenispertanian` (`id_jenispertanian`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `lahan`
--

INSERT INTO `lahan` (`id`, `name`, `id_user`, `lokasi`, `luas`, `id_jenispertanian`, `tipe`, `status`, `id_lahanutama`, `terakhir_panen`) VALUES
(1, 'palundarmain1', 3, '-0.902900, 119.848412', 1000.00, 1, 'M', 1, NULL, NULL),
(2, 'palundarclus1', 3, '-0.902900, 119.848412', 400.00, 1, 'C', 1, 1, '2016-01-19'),
(3, 'palundarclus2', 3, '-0.902900, 119.848412', 300.00, 1, 'C', 1, 1, '2015-10-08'),
(4, 'palundarclus3', 3, '-0.902900, 119.848412', 300.00, 1, 'C', 1, 1, '2015-12-05'),
(5, 'paluzulmain1', 4, '-0.903901, 119.848311', 300.00, 1, 'M', 1, NULL, '2016-01-05'),
(6, 'palujaumain1', 5, '-0.903901, 119.848311', 300.00, 1, 'M', 1, NULL, NULL),
(7, 'palujauclus1', 5, '-0.903901, 119.848311', 150.00, 1, 'C', 1, 6, '2015-12-12'),
(8, 'palujauclus2', 5, '-0.903901, 119.848311', 150.00, 1, 'C', 1, 6, '2015-11-12');

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
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=8 ;

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
(7, 2, '2016-05-20 20:16:21', 1, '::1', 'Login Success', 0);

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
-- Table structure for table `objek`
--

CREATE TABLE IF NOT EXISTS `objek` (
  `id` int(11) NOT NULL,
  `name` varchar(50) DEFAULT NULL,
  `unique_code` varchar(1024) DEFAULT NULL,
  `id_lahan` int(11) DEFAULT NULL,
  `awal_tanam` date DEFAULT NULL,
  `kondisi` text,
  `catatan` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `objek`
--

INSERT INTO `objek` (`id`, `name`, `unique_code`, `id_lahan`, `awal_tanam`, `kondisi`, `catatan`) VALUES
(1, 'lokal labuan', '12001001', 2, '2006-01-19', '{"Pytoptor":2,"VSD":"true"}', '{"Daun_muda":"DK","Daun_dewasa":"DG","Bunga":12,"Buah_kecil":7}'),
(2, 'lokal labuan', '12001002', 3, '2006-01-19', '{"Pytoptor":5}', '{"Daun_muda":"DK","Daun_dewasa":"DN","Buah_kecil":7,"Buah_dewasa":4,"Buah_siap_panen":1}'),
(3, 'lokal labuan', '12001003', 2, '2006-01-19', 'sehat', '{"Daun_muda":"DN","Daun_dewasa":"DN","Bunga":21,"Buah_kecil":64,"Buah_dewasa":24,"Buah_siap_panen":9}'),
(4, 'lokal labuan', '12002001', 3, '2006-01-19', 'sehat', '{"Daun_muda":"DN","Daun_dewasa":"DN","Bunga":63,"Buah_kecil":26,"Buah_dewasa":7,"Buah_siap_panen":3}'),
(5, 'lokal labuan', '12002002', 4, '2006-01-19', '{"Pytoptor":23}', '{"Daun_muda":"DK","Daun_dewasa":"DG","Bunga":33,"Buah_kecil":73,"Buah_dewasa":13,"Buah_siap_panen":5}'),
(6, 'lokal labuan', '13001001', 5, '2006-01-19', '{"Pytoptor":1}', '{"Daun_muda":"PC","Daun_dewasa":"DK","Bunga":2,"Buah_kecil":4}'),
(7, 'lokal labuan', '13001002', 5, '2006-01-19', '{"Pytoptor":6}', '{"Daun_muda":"PC","Daun_dewasa":"DK","Bunga":6,"Buah_kecil":9,"Buah_dewasa":1}'),
(8, 'lokal labuan', '13001003', 5, '2006-01-19', '{"Pytoptor":3,"VSD":"true"}', '{"Daun_muda":"PC","Daun_dewasa":"DC","Bunga":55,"Buah_kecil":42,"Buah_dewasa":25,"Buah_siap_panen":24}'),
(9, 'lokal labuan', '13001004', 5, '2001-01-19', NULL, '{"Daun_muda":"PC","Daun_dewasa":"DK"}'),
(10, 'lokal labuan', '14001001', 7, '2006-01-19', '{"Pytoptor":6,"VSD":"true"}', '{"Daun_muda":"DK","Daun_dewasa":"DG","Bunga":3,"Buah_kecil":13,"Buah_dewasa":2,"Buah_siap_panen":1}'),
(11, 'lokal labuan', '14001002', 7, '2006-01-19', 'sehat', '{"Daun_muda":"DK","Daun_dewasa":"DN","Bunga":1,"Buah_kecil":5,"Buah_dewasa":2,"Buah_siap_panen":4}'),
(12, 'lokal labuan', '14002001', 8, '2006-01-19', 'sehat', '{"Daun_muda":"DN","Daun_dewasa":"DC","Bunga":14,"Buah_kecil":6,"Buah_dewasa":12,"Buah_siap_panen":4}'),
(13, 'lokal labuan', '14001001', 8, '2001-01-19', '{"Pytoptor":5}', '{"Daun_muda":"DN","Daun_dewasa":"DK","Bunga":10,"Buah_kecil":16,"Buah_dewasa":9}');

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
-- Table structure for table `relation`
--

CREATE TABLE IF NOT EXISTS `relation` (
  `id` int(11) NOT NULL,
  `id_user` int(11) DEFAULT NULL,
  `type` char(1) DEFAULT NULL,
  `id_petani` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `id_user` (`id_user`),
  KEY `id_petani` (`id_petani`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `relation`
--

INSERT INTO `relation` (`id`, `id_user`, `type`, `id_petani`) VALUES
(1, 1, 'I', 3),
(2, 1, 'I', 4),
(3, 2, 'I', 5),
(4, 6, 'S', 3),
(5, 7, 'S', 4),
(6, 8, 'S', 5);

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
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=62 ;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`user_id`, `is_superuser`, `fullname`, `username`, `userpassword`, `is_active`, `email`, `lastlogin`, `ket`, `login_session_id`, `additional_info`, `lokasi_id`, `store_id`) VALUES
(2, 1, 'Admin Name', 'admin', 'e10adc3949ba59abbe56e057f20f883e', 1, 'reza@indowebapps.com', '2016-05-20 20:16:21', NULL, 'e449c64731526c0eab7c718bb87f5bee', NULL, 1, 0),
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
