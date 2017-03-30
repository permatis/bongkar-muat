/*
Navicat MySQL Data Transfer

Source Server         : testing
Source Server Version : 50617
Source Host           : 127.0.0.1:3306
Source Database       : bongkar

Target Server Type    : MYSQL
Target Server Version : 50617
File Encoding         : 65001

Date: 2014-12-10 05:24:04
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for anggota
-- ----------------------------
DROP TABLE IF EXISTS `anggota`;
CREATE TABLE `anggota` (
  `id_anggota` int(11) NOT NULL AUTO_INCREMENT,
  `nik` varchar(12) NOT NULL,
  `nama` varchar(50) NOT NULL,
  `alamat` text NOT NULL,
  `no_hp` varchar(12) NOT NULL,
  PRIMARY KEY (`id_anggota`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of anggota
-- ----------------------------
INSERT INTO `anggota` VALUES ('1', '213123213', 'Defri', 'test', '213213213123');
INSERT INTO `anggota` VALUES ('2', '211321312', 'Fajar', 'test again', '32423423423');

-- ----------------------------
-- Table structure for barang
-- ----------------------------
DROP TABLE IF EXISTS `barang`;
CREATE TABLE `barang` (
  `id_barang` int(11) NOT NULL AUTO_INCREMENT,
  `nama` varchar(255) DEFAULT NULL,
  `satuan` varchar(50) DEFAULT NULL,
  `harga` int(11) DEFAULT NULL,
  PRIMARY KEY (`id_barang`)
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of barang
-- ----------------------------
INSERT INTO `barang` VALUES ('4', 'Semen 40 Kg', 'Kg', '300');
INSERT INTO `barang` VALUES ('5', 'Semen 50 Kg', 'Kg', '350');
INSERT INTO `barang` VALUES ('6', 'Genteng tipe A', 'Per Biji', '200');
INSERT INTO `barang` VALUES ('7', 'Genteng tipe B', 'Per Biji', '300');
INSERT INTO `barang` VALUES ('8', 'Genteng tipe C', 'Per Biji', '500');
INSERT INTO `barang` VALUES ('9', 'Triplek tipe A', '-', '150');
INSERT INTO `barang` VALUES ('10', 'Triplek tipe B', '-', '300');
INSERT INTO `barang` VALUES ('11', 'Triplek tipe C', '-', '500');
INSERT INTO `barang` VALUES ('12', 'Keramik tipe A', '-', '250');
INSERT INTO `barang` VALUES ('13', 'Keramik tipe B', '-', '500');
INSERT INTO `barang` VALUES ('14', 'Keramik tipe C', '-', '1000');
INSERT INTO `barang` VALUES ('15', 'Kloset', 'Per Box', '2000');
INSERT INTO `barang` VALUES ('16', 'Cat', 'Per Galon/Dus', '400');
INSERT INTO `barang` VALUES ('17', 'Paku ', 'Per Dus', '500');
INSERT INTO `barang` VALUES ('18', 'Besi ', 'Per Ton', '1250');
INSERT INTO `barang` VALUES ('19', 'Karpet', 'Per Gulung', '1000');
INSERT INTO `barang` VALUES ('20', 'Talang', 'Per Gulung', '1000');
INSERT INTO `barang` VALUES ('21', 'Sempak', 'per kg', '2000');

-- ----------------------------
-- Table structure for bongkarmuat
-- ----------------------------
DROP TABLE IF EXISTS `bongkarmuat`;
CREATE TABLE `bongkarmuat` (
  `id_bongkarmuat` int(11) NOT NULL AUTO_INCREMENT COMMENT '	',
  `jumlah_barang` int(11) DEFAULT NULL,
  `total_harga` int(11) DEFAULT NULL,
  `status` enum('bongkar','muat','bongkar-muat') DEFAULT NULL COMMENT '	',
  `tanggal` date DEFAULT NULL,
  `perusahaan_id` int(11) NOT NULL,
  `barang_id` int(11) NOT NULL COMMENT '	',
  PRIMARY KEY (`id_bongkarmuat`,`perusahaan_id`,`barang_id`),
  KEY `fk_bongkarmuat_perusahaan1` (`perusahaan_id`),
  KEY `fk_bongkarmuat_barang1` (`barang_id`),
  CONSTRAINT `fk_bongkarmuat_barang1` FOREIGN KEY (`barang_id`) REFERENCES `barang` (`id_barang`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_bongkarmuat_perusahaan1` FOREIGN KEY (`perusahaan_id`) REFERENCES `perusahaan` (`id_perusahaan`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of bongkarmuat
-- ----------------------------
INSERT INTO `bongkarmuat` VALUES ('1', '3000', '12000000', 'bongkar-muat', '2014-11-29', '2', '21');
INSERT INTO `bongkarmuat` VALUES ('2', '300', '600000', 'bongkar', '2014-11-29', '3', '21');

-- ----------------------------
-- Table structure for perusahaan
-- ----------------------------
DROP TABLE IF EXISTS `perusahaan`;
CREATE TABLE `perusahaan` (
  `id_perusahaan` int(11) NOT NULL AUTO_INCREMENT,
  `nama` varchar(50) DEFAULT NULL,
  `alamat` text,
  `telepon` varchar(12) DEFAULT NULL,
  PRIMARY KEY (`id_perusahaan`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of perusahaan
-- ----------------------------
INSERT INTO `perusahaan` VALUES ('2', 'PT. Sempak Jaya Makmur', 'jl Sempak no 76 Semarang', '02483985923');
INSERT INTO `perusahaan` VALUES ('3', 'PT. Es', 'yrdtssfds', '39924923848');

-- ----------------------------
-- Table structure for relasi_anggota
-- ----------------------------
DROP TABLE IF EXISTS `relasi_anggota`;
CREATE TABLE `relasi_anggota` (
  `id_relasi_anggota` int(11) NOT NULL AUTO_INCREMENT,
  `anggota_id` int(11) NOT NULL,
  `bongkarmuat_id` int(11) NOT NULL,
  PRIMARY KEY (`id_relasi_anggota`),
  UNIQUE KEY `unique_index` (`bongkarmuat_id`,`anggota_id`),
  KEY `fk_relasi_anggota_anggota` (`anggota_id`),
  KEY `fk_relasi_anggota_bongkarmuat1` (`bongkarmuat_id`),
  CONSTRAINT `fk_relasi_anggota_anggota` FOREIGN KEY (`anggota_id`) REFERENCES `anggota` (`id_anggota`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_relasi_anggota_bongkarmuat1` FOREIGN KEY (`bongkarmuat_id`) REFERENCES `bongkarmuat` (`id_bongkarmuat`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=52 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of relasi_anggota
-- ----------------------------
INSERT INTO `relasi_anggota` VALUES ('43', '1', '1');
INSERT INTO `relasi_anggota` VALUES ('51', '2', '1');
INSERT INTO `relasi_anggota` VALUES ('48', '2', '2');

-- ----------------------------
-- Table structure for user
-- ----------------------------
DROP TABLE IF EXISTS `user`;
CREATE TABLE `user` (
  `id_user` int(11) NOT NULL AUTO_INCREMENT,
  `nama` varchar(50) DEFAULT NULL,
  `password` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id_user`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of user
-- ----------------------------
INSERT INTO `user` VALUES ('1', 'admin', 's/wEVG2gXsWghjHv47hFkcGWiMANV3yi2q/+6pkiXPE=');
