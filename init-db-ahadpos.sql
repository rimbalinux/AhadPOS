
/*
SQLyog - Free MySQL GUI v5.14
Host - 5.0.45-community-nt : Database - ahadpos
*********************************************************************
Server version : 5.0.45-community-nt
*/


SET NAMES utf8;

SET SQL_MODE='';
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO';

CREATE DATABASE IF NOT EXISTS `ahadpos`;
USE `ahadpos`;

/*Table structure for table `bank` */


CREATE TABLE IF NOT EXISTS `bank` (
  `idBank` int(3) NOT NULL,
  `namaBank` varchar(20) DEFAULT NULL,
  `noRekening` varchar(30) DEFAULT NULL,
  PRIMARY KEY  (`idBank`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

/*Data for the table `bank` */

/*Table structure for table `barang` */


CREATE TABLE IF NOT EXISTS `barang` (
  `idBarang` bigint(20) NOT NULL DEFAULT '0',
  `namaBarang` varchar(30) DEFAULT ' ',
  `idKategoriBarang` int(5) DEFAULT '0',
  `idSatuanBarang` int(5) DEFAULT '0',
  `jumBarang` int(10) DEFAULT '0',
  `hargaJual` bigint(20) DEFAULT '0',
  `last_update` date DEFAULT '2000-01-01',
  `idSupplier` bigint(20) DEFAULT '0',
  `barcode` varchar(25) DEFAULT NULL,
  `username` varchar(30) DEFAULT NULL,
  `idRak` bigint(5) DEFAULT NULL,
  UNIQUE KEY `barcode` (`barcode`),
  KEY `idKategoriBarang` (`idKategoriBarang`),
  KEY `idSupplier` (`idSupplier`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

/*Data for the table `barang` */

insert into `barang` (`idBarang`,`namaBarang`,`idKategoriBarang`,`idSatuanBarang`,`jumBarang`,`hargaJual`,`last_update`,`idSupplier`,`barcode`,`username`,`idRak`) values (1,'Mie Sedap Goreng Kari Spesial',4,3,160,1250,'2010-03-26',5,'80808080',NULL,NULL);
insert into `barang` (`idBarang`,`namaBarang`,`idKategoriBarang`,`idSatuanBarang`,`jumBarang`,`hargaJual`,`last_update`,`idSupplier`,`barcode`,`username`,`idRak`) values (2,'Mie Sedap Rebus Kari Ayam',4,3,160,1094,'2010-03-26',5,'82828282',NULL,NULL);
insert into `barang` (`idBarang`,`namaBarang`,`idKategoriBarang`,`idSatuanBarang`,`jumBarang`,`hargaJual`,`last_update`,`idSupplier`,`barcode`,`username`,`idRak`) values (3,'Sirup ABC Lemon',3,3,20,9375,'2010-03-26',3,'90909090',NULL,NULL);
insert into `barang` (`idBarang`,`namaBarang`,`idKategoriBarang`,`idSatuanBarang`,`jumBarang`,`hargaJual`,`last_update`,`idSupplier`,`barcode`,`username`,`idRak`) values (4,'Sirup ABC Strawberry',3,3,20,10000,'2010-03-26',3,'91919191',NULL,NULL);

/*Table structure for table `config` */


CREATE TABLE IF NOT EXISTS `config` (
  `idConfig` bigint(20) NOT NULL auto_increment,
  `option` varchar(30) NOT NULL,
  `value` varchar(50) NOT NULL,
  `description` varchar(50) DEFAULT NULL,
  PRIMARY KEY  (`idConfig`),
  KEY `option` (`option`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;

/*Data for the table `config` */

insert into `config` (`idConfig`,`option`,`value`,`description`) values (1,'store_name','Ahad mart ','name of the shop');
insert into `config` (`idConfig`,`option`,`value`,`description`) values (2,'receipt_footer1','Terimakasih telah berbelanja di Ahad mart',NULL);
insert into `config` (`idConfig`,`option`,`value`,`description`) values (3,'receipt_footer2','Murah, Lengkap, dan Islami',NULL);
insert into `config` (`idConfig`,`option`,`value`,`description`) values (4,'receipt_header1','---------------------',NULL);
insert into `config` (`idConfig`,`option`,`value`,`description`) values (5,'temporary_space','/tmp/',NULL);
insert into `config` (`idConfig`,`option`,`value`,`description`) values (6,'version','a:3:{i:0;i:1;i:1;i:5;i:2;i:0;}',NULL);


/*Table structure for table `customer` */


CREATE TABLE IF NOT EXISTS `customer` (
  `idCustomer` varchar(10) NOT NULL,
  `namaCustomer` varchar(30) DEFAULT NULL,
  `alamatCustomer` varchar(50) DEFAULT NULL,
  `telpCustomer` varchar(15) DEFAULT NULL,
  `keterangan` text,
  `uname` varchar(8) DEFAULT NULL,
  `pwd` varchar(35) DEFAULT NULL,
  `last_update` date DEFAULT NULL,
  PRIMARY KEY  (`idCustomer`)
) ENGINE=myISAM DEFAULT CHARSET=latin1;

/*Data for the table `customer` */

insert into `customer` (`idCustomer`,`namaCustomer`,`alamatCustomer`,`telpCustomer`,`keterangan`,`uname`,`pwd`,`last_update`) values ('1','Umum','Customer Umum / Non Member','','Customer Umum / Non Member',NULL,NULL,'2009-12-01');
insert into `customer` (`idCustomer`,`namaCustomer`,`alamatCustomer`,`telpCustomer`,`keterangan`,`uname`,`pwd`,`last_update`) values ('2','Rosari Prima','JL. GelarSena 1','0272325540','rosa',NULL,NULL,'2009-11-21');
insert into `customer` (`idCustomer`,`namaCustomer`,`alamatCustomer`,`telpCustomer`,`keterangan`,`uname`,`pwd`,`last_update`) values ('3','Priska','STM Pembangunan','08562969601','kantin',NULL,NULL,'2009-12-06');

/*Table structure for table `detail_beli` */


CREATE TABLE IF NOT EXISTS `detail_beli` (
  `idDetailBeli` bigint(20) NOT NULL auto_increment,
  `idTransaksiBeli` bigint(20) NOT NULL,
  `idBarang` bigint(20) NOT NULL,
  `tglExpire` date NOT NULL,
  `jumBarang` int(10) NOT NULL,
  `hargaBeli` bigint(20) NOT NULL,
  `isSold` varchar(1) character set latin1 DEFAULT 'N',
  `barcode` varchar(25) DEFAULT NULL,
  `username` varchar(30) character set latin1 DEFAULT NULL,
  `jumBarangAsli` int(11) DEFAULT NULL COMMENT 'Jumlah Barang pada saat Pembelian',
  PRIMARY KEY  (`idDetailBeli`),
  KEY `isSold` (`isSold`), 
  KEY `jumBarang` (`jumBarang`),
  KEY `idBarang` (`idBarang`), 
  KEY `barcode` (`barcode`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci COMMENT='latin1_swedish_ci';

/*Data for the table `detail_beli` */

insert into `detail_beli` (`idDetailBeli`,`idTransaksiBeli`,`idBarang`,`tglExpire`,`jumBarang`,`hargaBeli`,`isSold`,`barcode`,`username`,`jumBarangAsli`) values (1,1,1,'0000-00-00',160,1000,'N',80808080,'ariefadmin',160);
insert into `detail_beli` (`idDetailBeli`,`idTransaksiBeli`,`idBarang`,`tglExpire`,`jumBarang`,`hargaBeli`,`isSold`,`barcode`,`username`,`jumBarangAsli`) values (2,1,2,'0000-00-00',160,875,'N',82828282,'ariefadmin',160);
insert into `detail_beli` (`idDetailBeli`,`idTransaksiBeli`,`idBarang`,`tglExpire`,`jumBarang`,`hargaBeli`,`isSold`,`barcode`,`username`,`jumBarangAsli`) values (3,2,4,'0000-00-00',20,8000,'N',91919191,'ariefadmin',20);
insert into `detail_beli` (`idDetailBeli`,`idTransaksiBeli`,`idBarang`,`tglExpire`,`jumBarang`,`hargaBeli`,`isSold`,`barcode`,`username`,`jumBarangAsli`) values (4,2,3,'0000-00-00',20,7500,'N',90909090,'ariefadmin',20);

/*Table structure for table `detail_jual` */


CREATE TABLE IF NOT EXISTS `detail_jual` (
  `idTransaksiJual` bigint(20) NOT NULL,
  `idBarang` bigint(20) NOT NULL,
  `jumBarang` int(10) NOT NULL,
  `hargaBeli` bigint(20) DEFAULT NULL,
  `hargaJual` bigint(20) NOT NULL,
  `username` varchar(30) character set latin1 DEFAULT NULL,
  `diskon` bigint(20) NOT NULL,
  `barcode` varchar(25) character set latin1 DEFAULT NULL,
  `nomorStruk` bigint(20) DEFAULT NULL,
  KEY `username` (`username`),
  KEY `nomorStruk` (`nomorStruk`), 
  KEY `barcode` (`barcode`) 
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

/*Data for the table `detail_jual` */

insert into `detail_jual` (`idTransaksiJual`,`idBarang`,`jumBarang`,`hargaBeli`,`hargaJual`,`username`,`diskon`,`barcode`,`nomorStruk`) values (1,2,2,875,1094,'ariefadmin',0,'82828282',1);
insert into `detail_jual` (`idTransaksiJual`,`idBarang`,`jumBarang`,`hargaBeli`,`hargaJual`,`username`,`diskon`,`barcode`,`nomorStruk`) values (1,1,2,1000,1250,'ariefadmin',0,'80808080',1);
insert into `detail_jual` (`idTransaksiJual`,`idBarang`,`jumBarang`,`hargaBeli`,`hargaJual`,`username`,`diskon`,`barcode`,`nomorStruk`) values (2,4,1,8000,10000,'kasir1',0,'91919191',2);
insert into `detail_jual` (`idTransaksiJual`,`idBarang`,`jumBarang`,`hargaBeli`,`hargaJual`,`username`,`diskon`,`barcode`,`nomorStruk`) values (2,3,1,7500,9375,'kasir1',0,'90909090',2);

/*Table structure for table `hutang` */


CREATE TABLE IF NOT EXISTS `hutang` (
  `idTransaksiBeli` bigint(20) NOT NULL,
  `nominal` bigint(20) NOT NULL,
  `tglBayar` date NOT NULL,
  `last_update` date NOT NULL,
  `username` varchar(30) DEFAULT NULL,
  PRIMARY KEY  (`idTransaksiBeli`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1  COLLATE=latin1_swedish_ci;

/*Data for the table `hutang` */

insert into `hutang` (`idTransaksiBeli`,`nominal`,`tglBayar`,`last_update`,`username`) values (1,300000,'2010-04-19','2010-03-26','ariefadmin');

/*Table structure for table `kasir` */


CREATE TABLE IF NOT EXISTS `kasir` (
  `idTransKasir` int(15) NOT NULL auto_increment,
  `tglBukaKasir` datetime DEFAULT NULL,
  `idUser` int(3) DEFAULT NULL,
  `kasAwal` float DEFAULT NULL,
  `kasSeharusnya` float DEFAULT NULL,
  `kasTutup` float DEFAULT NULL,
  `currentWorkstation` bigint(20) DEFAULT NULL,
  `tglTutupKasir` datetime DEFAULT NULL,
  `totalTransaksi` bigint(20) DEFAULT NULL,
  `totalProfit` bigint(20) DEFAULT NULL,
  `totalRetur` bigint(20) DEFAULT NULL,
  `totalTransaksiKas` bigint(20) DEFAULT NULL,
  `totalTransaksiKartu` bigint(20) DEFAULT NULL,
  PRIMARY KEY  (`idTransKasir`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

/*Data for the table `kasir` */

insert into `kasir` (`idTransKasir`,`tglBukaKasir`,`idUser`,`kasAwal`,`kasSeharusnya`,`kasTutup`,`currentWorkstation`,`tglTutupKasir`,`totalTransaksi`,`totalProfit`,`totalRetur`,`totalTransaksiKas`,`totalTransaksiKartu`) values (1,'2010-03-26 20:37:52',10,100000,NULL,NULL,1,NULL,NULL,NULL,NULL,NULL,NULL);

/*Table structure for table `kategori_barang` */


CREATE TABLE IF NOT EXISTS `kategori_barang` (
  `idKategoriBarang` int(5) NOT NULL auto_increment,
  `namaKategoriBarang` varchar(30) DEFAULT NULL,
  PRIMARY KEY  (`idKategoriBarang`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

/*Data for the table `kategori_barang` */

insert into `kategori_barang` (`idKategoriBarang`,`namaKategoriBarang`) values (1,'wafer');
insert into `kategori_barang` (`idKategoriBarang`,`namaKategoriBarang`) values (2,'biskuit');
insert into `kategori_barang` (`idKategoriBarang`,`namaKategoriBarang`) values (3,'sirup');
insert into `kategori_barang` (`idKategoriBarang`,`namaKategoriBarang`) values (4,'mie');
insert into `kategori_barang` (`idKategoriBarang`,`namaKategoriBarang`) values (5,'kopi');
insert into `kategori_barang` (`idKategoriBarang`,`namaKategoriBarang`) values (6,'isotonik drink');
insert into `kategori_barang` (`idKategoriBarang`,`namaKategoriBarang`) values (7,'makanan');
insert into `kategori_barang` (`idKategoriBarang`,`namaKategoriBarang`) values (8,'Gulaku');
insert into `kategori_barang` (`idKategoriBarang`,`namaKategoriBarang`) values (9,'kosmetik');
insert into `kategori_barang` (`idKategoriBarang`,`namaKategoriBarang`) values (10,'Perlengkapan');
insert into `kategori_barang` (`idKategoriBarang`,`namaKategoriBarang`) values (11,'sabun cuci');
insert into `kategori_barang` (`idKategoriBarang`,`namaKategoriBarang`) values (12,'minuman');
insert into `kategori_barang` (`idKategoriBarang`,`namaKategoriBarang`) values (13,'Susu');
insert into `kategori_barang` (`idKategoriBarang`,`namaKategoriBarang`) values (14,'ATK');
insert into `kategori_barang` (`idKategoriBarang`,`namaKategoriBarang`) values (15,'Elektronik');
insert into `kategori_barang` (`idKategoriBarang`,`namaKategoriBarang`) values (16,'Bayi');
insert into `kategori_barang` (`idKategoriBarang`,`namaKategoriBarang`) values (17,'Detergent/Obat Nyamuk');
insert into `kategori_barang` (`idKategoriBarang`,`namaKategoriBarang`) values (18,'Pecah Belah');
insert into `kategori_barang` (`idKategoriBarang`,`namaKategoriBarang`) values (19,'Muslim');
insert into `kategori_barang` (`idKategoriBarang`,`namaKategoriBarang`) values (20,'Sabun/Shampo');
insert into `kategori_barang` (`idKategoriBarang`,`namaKategoriBarang`) values (21,'Mainan');
insert into `kategori_barang` (`idKategoriBarang`,`namaKategoriBarang`) values (22,'Pakaian');
insert into `kategori_barang` (`idKategoriBarang`,`namaKategoriBarang`) values (23,'Obat');

/*Table structure for table `leveluser` */


CREATE TABLE IF NOT EXISTS `leveluser` (
  `idLevelUser` int(2) NOT NULL,
  `levelUser` varchar(20) collate latin1_swedish_ci DEFAULT NULL,
  PRIMARY KEY  (`idLevelUser`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

/*Data for the table `leveluser` */

insert into `leveluser` (`idLevelUser`,`levelUser`) values (1,'semua');
insert into `leveluser` (`idLevelUser`,`levelUser`) values (2,'admin');
insert into `leveluser` (`idLevelUser`,`levelUser`) values (3,'gudang');
insert into `leveluser` (`idLevelUser`,`levelUser`) values (4,'kasir');

/*Table structure for table `modul` */


CREATE TABLE IF NOT EXISTS `modul` (
  `idModul` int(3) NOT NULL,
  `namaModul` varchar(50) collate latin1_swedish_ci DEFAULT NULL,
  `link` varchar(50) collate latin1_swedish_ci DEFAULT NULL,
  `publish` enum('Y','N') collate latin1_swedish_ci DEFAULT NULL,
  `idLevelUser` int(2) DEFAULT NULL,
  `urutan` int(3) DEFAULT NULL,
  `last_update` date DEFAULT NULL,
  PRIMARY KEY  (`idModul`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

/*Data for the table `modul` */

insert into `modul` (`idModul`,`namaModul`,`link`,`publish`,`idLevelUser`,`urutan`,`last_update`) values (1,'Manajemen User','?module=user','N',2,101,'2009-10-19');
insert into `modul` (`idModul`,`namaModul`,`link`,`publish`,`idLevelUser`,`urutan`,`last_update`) values (2,'Supplier','?module=supplier','Y',3,5,'2009-10-19');
insert into `modul` (`idModul`,`namaModul`,`link`,`publish`,`idLevelUser`,`urutan`,`last_update`) values (3,'Customer','?module=customer','Y',4,6,'2009-10-19');
insert into `modul` (`idModul`,`namaModul`,`link`,`publish`,`idLevelUser`,`urutan`,`last_update`) values (4,'Barang','?module=barang','Y',3,3,'2009-10-19');
insert into `modul` (`idModul`,`namaModul`,`link`,`publish`,`idLevelUser`,`urutan`,`last_update`) values (5,'Rak','?module=rak','Y',3,4,'2009-10-19');
insert into `modul` (`idModul`,`namaModul`,`link`,`publish`,`idLevelUser`,`urutan`,`last_update`) values (6,'Satuan Barang','?module=satuan_barang','Y',3,1,'2009-10-19');
insert into `modul` (`idModul`,`namaModul`,`link`,`publish`,`idLevelUser`,`urutan`,`last_update`) values (7,'Kategori Barang','?module=kategori_barang','Y',3,2,'2009-10-19');
insert into `modul` (`idModul`,`namaModul`,`link`,`publish`,`idLevelUser`,`urutan`,`last_update`) values (8,'Pembelian','?module=pembelian_barang','Y',3,7,'2009-10-19');
insert into `modul` (`idModul`,`namaModul`,`link`,`publish`,`idLevelUser`,`urutan`,`last_update`) values (9,'Penjualan','?module=penjualan_barang','Y',4,8,'2009-10-19');
insert into `modul` (`idModul`,`namaModul`,`link`,`publish`,`idLevelUser`,`urutan`,`last_update`) values (10,'Ganti Password','?module=ganti_password','N',1,100,'2009-10-19');
insert into `modul` (`idModul`,`namaModul`,`link`,`publish`,`idLevelUser`,`urutan`,`last_update`) values (11,'Hutang','?module=hutang','Y',3,9,'2009-10-19');
insert into `modul` (`idModul`,`namaModul`,`link`,`publish`,`idLevelUser`,`urutan`,`last_update`) values (12,'Piutang','?module=piutang','Y',4,10,'2009-10-19');
insert into `modul` (`idModul`,`namaModul`,`link`,`publish`,`idLevelUser`,`urutan`,`last_update`) values (13,'Manajemen Modul','?module=modul','N',2,102,'2009-10-19');
insert into `modul` (`idModul`,`namaModul`,`link`,`publish`,`idLevelUser`,`urutan`,`last_update`) values (14,'Kasir','?module=kasir','Y',2,11,NULL);
insert into `modul` (`idModul`,`namaModul`,`link`,`publish`,`idLevelUser`,`urutan`,`last_update`) values (15,'Laporan Manajemen','?module=laporan','Y',2,12,NULL);
insert into `modul` (`idModul`,`namaModul`,`link`,`publish`,`idLevelUser`,`urutan`,`last_update`) values (16,'Manajemen Workstation','?module=workstation','Y',2,13,NULL);

                ALTER TABLE `modul` ADD `script_name` VARCHAR( 50 ) NOT NULL;
                ALTER TABLE `modul` ADD INDEX (`script_name`);

                UPDATE `modul` SET `script_name` = 'mod_user.php' WHERE `modul`.`link` = '?module=user' ;
                UPDATE `modul` SET `script_name` = 'mod_supplier.php' WHERE `modul`.`link` = '?module=supplier' ;
                UPDATE `modul` SET `script_name` = 'mod_customer.php' WHERE `modul`.`link` = '?module=customer' ;
                UPDATE `modul` SET `script_name` = 'mod_barang.php' WHERE `modul`.`link` = '?module=barang' ;
                UPDATE `modul` SET `script_name` = 'mod_rak.php' WHERE `modul`.`link` = '?module=rak' ;
                UPDATE `modul` SET `script_name` = 'mod_satuan_barang.php' WHERE `modul`.`link` = '?module=satuan_barang' ;
                UPDATE `modul` SET `script_name` = 'mod_kategori_barang.php' WHERE `modul`.`link` = '?module=kategori_barang' ;
                UPDATE `modul` SET `script_name` = 'mod_beli_barang.php' WHERE `modul`.`link` = '?module=pembelian_barang' ;
                UPDATE `modul` SET `script_name` = 'mod_jual_barang.php' WHERE `modul`.`link` = '?module=penjualan_barang' ;
                UPDATE `modul` SET `script_name` = 'mod_hutang.php' WHERE `modul`.`link` = '?module=hutang' ;
                UPDATE `modul` SET `script_name` = 'mod_piutang.php' WHERE `modul`.`link` = '?module=piutang' ;
                UPDATE `modul` SET `script_name` = 'mod_modul.php' WHERE `modul`.`link` = '?module=modul' ;
                UPDATE `modul` SET `script_name` = 'mod_kasir.php' WHERE `modul`.`link` = '?module=kasir' ;
                UPDATE `modul` SET `script_name` = 'mod_laporan.php' WHERE `modul`.`link` = '?module=laporan' ;
                UPDATE `modul` SET `script_name` = 'mod_manage_workstation.php' WHERE `modul`.`link` = '?module=workstation' ;


/*Table structure for table `pembayaran` */


CREATE TABLE IF NOT EXISTS `pembayaran` (
  `idTipePembayaran` int(3) NOT NULL auto_increment,
  `tipePembayaran` varchar(30) DEFAULT NULL,
  PRIMARY KEY  (`idTipePembayaran`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;

/*Data for the table `pembayaran` */

insert into `pembayaran` (`idTipePembayaran`,`tipePembayaran`) values (1,'CASH');
insert into `pembayaran` (`idTipePembayaran`,`tipePembayaran`) values (2,'Tempo');
insert into `pembayaran` (`idTipePembayaran`,`tipePembayaran`) values (3,'Voucher');
insert into `pembayaran` (`idTipePembayaran`,`tipePembayaran`) values (4,'Debit');
insert into `pembayaran` (`idTipePembayaran`,`tipePembayaran`) values (5,'Kredit');

/*Table structure for table `piutang` */


CREATE TABLE IF NOT EXISTS `piutang` (
  `idTransaksiJual` bigint(20) NOT NULL,
  `nominal` bigint(20) unsigned DEFAULT NULL,
  `tglDiBayar` date DEFAULT NULL,
  `idUser` int(3) DEFAULT NULL,
  `last_update` date DEFAULT NULL,
  PRIMARY KEY  (`idTransaksiJual`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

/*Data for the table `piutang` */

/*Table structure for table `rak` */


CREATE TABLE IF NOT EXISTS `rak` (
  `idRak` bigint(5) NOT NULL auto_increment,
  `namaRak` varchar(30) DEFAULT NULL,
  PRIMARY KEY  (`idRak`)
) ENGINE=MyISAM AUTO_INCREMENT=101 DEFAULT CHARSET=latin1;

/*Data for the table `rak` */

insert into `rak` (`idRak`,`namaRak`) values (1,'Rak Depan Kasir');
insert into `rak` (`idRak`,`namaRak`) values (2,'Susu 1');
insert into `rak` (`idRak`,`namaRak`) values (3,'Susu 2');
insert into `rak` (`idRak`,`namaRak`) values (4,'Susu 3');
insert into `rak` (`idRak`,`namaRak`) values (5,'Susu 4');
insert into `rak` (`idRak`,`namaRak`) values (6,'Susu 5');
insert into `rak` (`idRak`,`namaRak`) values (7,'Susu 6');
insert into `rak` (`idRak`,`namaRak`) values (8,'Rak 8');
insert into `rak` (`idRak`,`namaRak`) values (9,'Rak 9');
insert into `rak` (`idRak`,`namaRak`) values (10,'Rak 10');
insert into `rak` (`idRak`,`namaRak`) values (11,'Rak 11');
insert into `rak` (`idRak`,`namaRak`) values (12,'Rak 12');
insert into `rak` (`idRak`,`namaRak`) values (13,'Rak 13');
insert into `rak` (`idRak`,`namaRak`) values (14,'Rak 14');
insert into `rak` (`idRak`,`namaRak`) values (15,'Rak 15');
insert into `rak` (`idRak`,`namaRak`) values (16,'Rak 16');
insert into `rak` (`idRak`,`namaRak`) values (17,'Rak 17');
insert into `rak` (`idRak`,`namaRak`) values (18,'Rak 18');
insert into `rak` (`idRak`,`namaRak`) values (19,'Rak 19');
insert into `rak` (`idRak`,`namaRak`) values (20,'Rak 20');

/*Table structure for table `retur` */


CREATE TABLE IF NOT EXISTS `retur` (
  `idRetur` int(10) NOT NULL auto_increment,
  `idCustomer` varchar(10) collate latin1_swedish_ci DEFAULT NULL,
  `idJenisRetur` int(2) DEFAULT NULL,
  `idTransaksi` bigint(20) DEFAULT NULL,
  `idAksiRetur` int(2) DEFAULT NULL,
  PRIMARY KEY  (`idRetur`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

/*Data for the table `retur` */

/*Table structure for table `satuan_barang` */


CREATE TABLE IF NOT EXISTS `satuan_barang` (
  `idSatuanBarang` int(5) NOT NULL,
  `namaSatuanBarang` varchar(30) DEFAULT NULL,
  PRIMARY KEY  (`idSatuanBarang`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

/*Data for the table `satuan_barang` */

insert into `satuan_barang` (`idSatuanBarang`,`namaSatuanBarang`) values (1,'Kg');
insert into `satuan_barang` (`idSatuanBarang`,`namaSatuanBarang`) values (2,'Ons');
insert into `satuan_barang` (`idSatuanBarang`,`namaSatuanBarang`) values (3,'Pcs');
insert into `satuan_barang` (`idSatuanBarang`,`namaSatuanBarang`) values (4,'Kardus');

/*Table structure for table `supplier` */


CREATE TABLE IF NOT EXISTS `supplier` (
  `idSupplier` bigint(20) NOT NULL auto_increment,
  `namaSupplier` varchar(30) DEFAULT NULL,
  `alamatSupplier` varchar(100) DEFAULT NULL,
  `telpSupplier` varchar(15) DEFAULT NULL,
  `Keterangan` text,
  `last_update` date DEFAULT NULL,
  PRIMARY KEY  (`idSupplier`)
) ENGINE=myISAM AUTO_INCREMENT=368 DEFAULT CHARSET=latin1;

/*Data for the table `supplier` */

insert into `supplier` (`idSupplier`,`namaSupplier`,`alamatSupplier`,`telpSupplier`,`Keterangan`,`last_update`) values (1,'Catur Edi','Jl. Wuluh 5, Papringan, Sleman','0274567876','Thanx','2009-11-30');
insert into `supplier` (`idSupplier`,`namaSupplier`,`alamatSupplier`,`telpSupplier`,`Keterangan`,`last_update`) values (2,'Albertus Supriyadi','Jl. Jonggrangan 1 No.3, Jonggrangan Baru\r\nKlaten','0282435009','Tenda LUV','2009-10-22');
insert into `supplier` (`idSupplier`,`namaSupplier`,`alamatSupplier`,`telpSupplier`,`Keterangan`,`last_update`) values (3,'MAKRO','Ciputat','','sembako','2010-02-05');
insert into `supplier` (`idSupplier`,`namaSupplier`,`alamatSupplier`,`telpSupplier`,`Keterangan`,`last_update`) values (4,'Jepara','Jembatan Lima','','','2010-02-06');

/*Table structure for table `tmp_detail_beli` */


CREATE TABLE IF NOT EXISTS `tmp_detail_beli` (
  `idSupplier` int(10) NOT NULL,
  `tglTransaksi` date NOT NULL,
  `idBarang` bigint(20) NOT NULL auto_increment,
  `tglExpire` date NOT NULL,
  `jumBarang` int(10) NOT NULL,
  `hargaBeli` float NOT NULL,
  `hargaJual` float NOT NULL,
  `barcode` varchar(25) DEFAULT NULL,
  `username` varchar(30) DEFAULT NULL,
  KEY `idBarang` (`idBarang`),
  KEY `idSupplier` (`idSupplier`),
  KEY `username` (`username`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;

/*Data for the table `tmp_detail_beli` */

/*Table structure for table `tmp_detail_jual` */


CREATE TABLE IF NOT EXISTS `tmp_detail_jual` (
  `idCustomer` bigint(20) NOT NULL,
  `tglTransaksi` datetime NOT NULL,
  `barcode` varchar(25) character set latin1 NOT NULL,
  `jumBarang` int(10) NOT NULL,
  `hargaBeli` float NOT NULL,
  `hargaJual` float NOT NULL,
  `username` varchar(30) character set latin1 DEFAULT NULL,
  `uid` bigint(20) NOT NULL auto_increment,
  `idBarang` bigint(20) DEFAULT NULL,
  PRIMARY KEY  (`uid`),
  KEY `barcode` (`barcode`),
  KEY `username` (`username`),
  KEY `idCustomer` (`idCustomer`)
) ENGINE=MyISAM AUTO_INCREMENT=65 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

/*Data for the table `tmp_detail_jual` */

/*Table structure for table `tmp_edit_detail_beli` */


CREATE TABLE IF NOT EXISTS `tmp_edit_detail_beli` (
  `idDetailBeli` bigint(20) NOT NULL auto_increment,
  `idTransaksiBeli` bigint(20) NOT NULL,
  `idBarang` bigint(20) NOT NULL,
  `tglExpire` date NOT NULL,
  `jumBarang` int(10) NOT NULL,
  `hargaBeli` bigint(20) NOT NULL,
  PRIMARY KEY  (`idDetailBeli`)
) ENGINE=MyISAM AUTO_INCREMENT=112276 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

/*Data for the table `tmp_edit_detail_beli` */

/*Table structure for table `tmp_pesan_barang` */


CREATE TABLE IF NOT EXISTS `tmp_pesan_barang` (
  `username` varchar(30) collate latin1_swedish_ci NOT NULL,
  `idSupplier` int(3) NOT NULL,
  `idBarang` bigint(20) NOT NULL,
  `barcode` varchar(25) collate latin1_swedish_ci NOT NULL,
  `jumBarang` int(10) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

/*Data for the table `tmp_pesan_barang` */

CREATE TABLE IF NOT EXISTS `tmp_edit_detail_retur_beli` (
  `idDetailBeli` bigint(20) NOT NULL AUTO_INCREMENT,
  `idTransaksiBeli` bigint(20) NOT NULL,
  `idBarang` bigint(20) NOT NULL,
  `tglExpire` date NOT NULL,
  `jumBarang` int(10) NOT NULL,
  `hargaBeli` bigint(20) NOT NULL,
  `barcode` varchar(25) COLLATE latin1_swedish_ci NOT NULL,
  `jumRetur` int(11) NOT NULL,
  PRIMARY KEY (`idDetailBeli`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci AUTO_INCREMENT=10047776 ;


CREATE TABLE IF NOT EXISTS `detail_retur_beli` (
  `idTransaksiBeli` bigint(20) NOT NULL,
  `idBarang` bigint(20) NOT NULL,
  `tglExpire` date NOT NULL,
  `jumRetur` int(10) NOT NULL,
  `hargaBeli` bigint(20) NOT NULL,
  `barcode` varchar(25) CHARACTER SET latin1 DEFAULT NULL,
  `username` varchar(30) CHARACTER SET latin1 DEFAULT NULL,
  `idSupplier` varchar(10) COLLATE latin1_swedish_ci DEFAULT NULL,
  `nominal` bigint(20) DEFAULT '0',
  `idTipePembayaran` int(3) DEFAULT NULL,
  `NomorInvoice` varchar(15) COLLATE latin1_swedish_ci NOT NULL DEFAULT '0',
  `idReturBeli` bigint(20) NOT NULL AUTO_INCREMENT,
  `tglRetur` date NOT NULL,
  PRIMARY KEY (`idReturBeli`),
  KEY `idBarang` (`idTransaksiBeli`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci COMMENT='latin1_swedish_ci' AUTO_INCREMENT=20 ;


/*Table structure for table `transaksibeli` */


CREATE TABLE IF NOT EXISTS `transaksibeli` (
  `idTransaksiBeli` bigint(20) NOT NULL auto_increment,
  `tglTransaksiBeli` date DEFAULT NULL,
  `idSupplier` varchar(10) DEFAULT NULL,
  `nominal` bigint(20) DEFAULT '0',
  `idTipePembayaran` int(3) DEFAULT NULL,
  `idUser` int(3) DEFAULT NULL,
  `last_update` date DEFAULT NULL,
  `NomorInvoice` varchar(15) NOT NULL DEFAULT '0',
  `username` varchar(30) DEFAULT NULL,
  PRIMARY KEY  (`idTransaksiBeli`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=latin1 COMMENT='' COLLATE=latin1_swedish_ci;

/*Data for the table `transaksibeli` */

insert into `transaksibeli` (`idTransaksiBeli`,`tglTransaksiBeli`,`idSupplier`,`nominal`,`idTipePembayaran`,`idUser`,`last_update`,`NomorInvoice`,`username`) values (1,'2010-03-26','5',300000,2,NULL,'2010-03-26','001','ariefadmin');
insert into `transaksibeli` (`idTransaksiBeli`,`tglTransaksiBeli`,`idSupplier`,`nominal`,`idTipePembayaran`,`idUser`,`last_update`,`NomorInvoice`,`username`) values (2,'2010-03-26','3',310000,1,NULL,'2010-03-26','0','ariefadmin');

/*Table structure for table `transaksijual` */


CREATE TABLE IF NOT EXISTS `transaksijual` (
  `idTransaksiJual` bigint(20) NOT NULL auto_increment,
  `tglTransaksiJual` datetime DEFAULT NULL,
  `idCustomer` varchar(10) DEFAULT NULL,
  `tglKirimBarang` date DEFAULT NULL,
  `idTipePembayaran` int(3) DEFAULT NULL,
  `nominal` bigint(20) DEFAULT '0',
  `idUser` int(3) DEFAULT NULL,
  `last_update` date DEFAULT NULL,
  `uangDibayar` bigint(20) DEFAULT NULL,
  PRIMARY KEY  (`idTransaksiJual`),
  KEY `idUser` (`idUser`),
  KEY `tglTransaksiJual` (`tglTransaksiJual`),
  KEY `nominal` (`nominal`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

/*Data for the table `transaksijual` */

insert into `transaksijual` (`idTransaksiJual`,`tglTransaksiJual`,`idCustomer`,`tglKirimBarang`,`idTipePembayaran`,`nominal`,`idUser`,`last_update`,`uangDibayar`) values (1,'2010-03-26 20:32:45','1',NULL,1,4688,1,'2010-03-26',5000);
insert into `transaksijual` (`idTransaksiJual`,`tglTransaksiJual`,`idCustomer`,`tglKirimBarang`,`idTipePembayaran`,`nominal`,`idUser`,`last_update`,`uangDibayar`) values (2,'2010-03-26 20:36:14','1',NULL,1,19375,10,'2010-03-26',20000);

/*Table structure for table `transaksikas` */


CREATE TABLE IF NOT EXISTS `transaksikas` (
  `idTransaksiKas` bigint(20) NOT NULL auto_increment,
  `tglTransaksiKas` date DEFAULT NULL,
  `idUser` int(3) DEFAULT NULL,
  `kasAwal` bigint(20) DEFAULT NULL,
  `kasAkhir` bigint(20) DEFAULT '0',
  `kasSeharusnya` bigint(20) DEFAULT '0',
  PRIMARY KEY  (`idTransaksiKas`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

/*Data for the table `transaksikas` */

/*Table structure for table `transaksikasir` */


CREATE TABLE IF NOT EXISTS `transaksikasir` (
  `idTransKasir` bigint(20) NOT NULL auto_increment,
  `idUser` int(11) NOT NULL COMMENT 'idUser of the Cashier',
  `jumlahTransaksi` bigint(20) NOT NULL,
  `description` varchar(100) DEFAULT NULL,
  `approvedBy` int(11) NOT NULL COMMENT 'idUser of the Approver',
  `tglTransaksi` datetime NOT NULL,
  PRIMARY KEY  (`idTransKasir`),
  KEY `idUser` (`idUser`,`tglTransaksi`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

/*Data for the table `transaksikasir` */

/*Table structure for table `user` */


CREATE TABLE IF NOT EXISTS `user` (
  `idUser` int(3) NOT NULL,
  `namaUser` varchar(30) collate latin1_swedish_ci DEFAULT NULL,
  `idLevelUser` int(2) DEFAULT NULL,
  `uname` varchar(30) collate latin1_swedish_ci DEFAULT NULL,
  `pass` varchar(35) collate latin1_swedish_ci DEFAULT NULL,
  `currentWorkstation` bigint(20) DEFAULT NULL,
  PRIMARY KEY  (`idUser`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

/*Data for the table `user` */

insert into `user` (`idUser`,`namaUser`,`idLevelUser`,`uname`,`pass`,`currentWorkstation`) values (7,'admin',2,'admin','21232f297a57a5a743894a0e4a801fc3',NULL);
insert into `user` (`idUser`,`namaUser`,`idLevelUser`,`uname`,`pass`,`currentWorkstation`) values (8,'input',3,'input','a43c1b0aa53a0c908810c06ab1ff3967',NULL);
insert into `user` (`idUser`,`namaUser`,`idLevelUser`,`uname`,`pass`,`currentWorkstation`) values (10,'kasir1',4,'kasir1','29c748d4d8f4bd5cbc0f3f60cb7ed3d0',NULL);



/*Table structure for table `workstation` */


CREATE TABLE IF NOT EXISTS `workstation` (
  `idWorkstation` bigint(20) NOT NULL AUTO_INCREMENT,
  `namaWorkstation` varchar(30) NOT NULL,
  `workstation_address` varchar(30) NOT NULL,
  `keterangan` varchar(50) DEFAULT NULL,
  `printer_type` enum('pdf','rlpr') NOT NULL DEFAULT 'pdf' COMMENT 'types: pdf - Adobe PDF, rlpr: remote lpr (for unix/linux)',
  `printer_commands` varchar(100) NOT NULL,
  PRIMARY KEY (`idWorkstation`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `workstation`
--

INSERT INTO `workstation` (`idWorkstation`, `namaWorkstation`, `workstation_address`, `keterangan`, `printer_type`, `printer_commands`) VALUES
(1, 'kasir1', '192.168.1.1', NULL, 'pdf', '');










/*Table structure for table `detail_retur_barang` */


CREATE TABLE IF NOT EXISTS `detail_retur_barang` (
  `uid` BIGINT NOT NULL AUTO_INCREMENT,
  `idTransaksiRetur` bigint(20) NOT NULL,
  `tglTransaksi` datetime NOT NULL,
  `idBarang` bigint(20) NOT NULL,
  `jumBarang` int(10) NOT NULL,
  `hargaBeli` bigint(20) DEFAULT NULL,
  `hargaJual` bigint(20) NOT NULL,
  `username` varchar(30) character set latin1 DEFAULT NULL,
  `barcode` varchar(25) character set latin1 DEFAULT NULL, 
	PRIMARY KEY (  `uid` )
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

/*Table structure for table `tmp_detail_retur_barang` */


CREATE TABLE IF NOT EXISTS `tmp_detail_retur_barang` (
  `uid` BIGINT NOT NULL AUTO_INCREMENT,
  `idTransaksiRetur` bigint(20) NOT NULL,
  `tglTransaksi` datetime NOT NULL,
  `idBarang` bigint(20) NOT NULL,
  `jumBarang` int(10) NOT NULL,
  `hargaBeli` bigint(20) DEFAULT NULL,
  `hargaJual` bigint(20) NOT NULL,
  `username` varchar(30) character set latin1 DEFAULT NULL,
  `barcode` varchar(25) character set latin1 DEFAULT NULL, 
	PRIMARY KEY (  `uid` )
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;




--
-- Table structure for table `stock_opname`
--

CREATE TABLE IF NOT EXISTS `stock_opname` (
`idStockOpname` BIGINT NOT NULL AUTO_INCREMENT ,
`idRak` BIGINT( 5 ) NOT NULL ,
`tanggalSO` DATE NOT NULL ,
`username` VARCHAR( 30 ) NOT NULL ,
PRIMARY KEY ( `idStockOpname` ) 
) ENGINE = MYISAM CHARACTER SET latin1 COLLATE latin1_swedish_ci;

--
-- Table structure for table `detail_stock_opname`
--

CREATE TABLE IF NOT EXISTS `detail_stock_opname` (
  `uid` bigint(20) NOT NULL AUTO_INCREMENT,
  `idStockOpname` bigint(20) NOT NULL,
  `barcode` varchar(25) NOT NULL,
  `namaBarang` varchar(30) NOT NULL,
  `jmlTercatat` int(11) NOT NULL,
  `selisih` int(11) NOT NULL,
  PRIMARY KEY (`uid`),
  KEY `idStockOpname` (`idStockOpname`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=84 ;




CREATE TABLE IF NOT EXISTS `fast_stock_opname` (
`uid` BIGINT NOT NULL AUTO_INCREMENT ,
`idRak` BIGINT NOT NULL ,
`tanggalSO` DATE NOT NULL ,
`username` VARCHAR( 30 ) NOT NULL ,
`barcode` VARCHAR( 25 ) NOT NULL ,
`namaBarang` VARCHAR( 30 ) NOT NULL ,
`jmlTercatat` INT NOT NULL ,
`selisih` INT NOT NULL ,
`approved` BOOLEAN NOT NULL ,
PRIMARY KEY ( `uid` ) ,
INDEX ( `idRak` , `tanggalSO` , `approved` )
) ENGINE = MYISAM ;



--
-- Table structure for table `audit`
--

CREATE TABLE IF NOT EXISTS `audit` (
  `uid` bigint(20) NOT NULL AUTO_INCREMENT,
  `tglTransaksi` datetime NOT NULL,
  `jenisTransaksi` varchar(30) NOT NULL COMMENT 'returnotajual, batalitemjual, returnotabeli, batalitembeli',
  `username` varchar(30) NOT NULL,
  `nomorStruk` bigint(20) NOT NULL,
  `nominalStruk` bigint(20) NOT NULL DEFAULT '0',
  `barcode` varchar(25) NOT NULL,
  `hargaBeli` bigint(20) NOT NULL DEFAULT '0',
  `hargaJual` bigint(20) NOT NULL DEFAULT '0',
  `jumBarang` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`uid`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;


SET SQL_MODE=@OLD_SQL_MODE;
