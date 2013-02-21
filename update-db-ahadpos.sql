/* 
== update-db-ahadpos.sql ================================================
   	version: 1.01

	Part of AhadPOS : http://ahadpos.com
	License: GPL v2
			http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
			http://vlsm.org/etc/gpl-unofficial.id.html

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License v2 (links provided above) for more details.

===========================================================

 This script will UPDATE any old/existing AhadPOS database as follows :

(#) Non-destructively : no data will be harmed (deleted)

(#) Optimizations : will enable extra performance gains

=========================================================== 

-- ------------------ STRUCTURE UPDATES ------------------------ 
*/

-- version: 1.2.0

ALTER TABLE  `kategori_barang` CHANGE  `idKategoriBarang`  `idKategoriBarang` INT( 5 ) NOT NULL AUTO_INCREMENT;


-- version 1.5.0

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


-- version 1.6.0

CREATE TABLE IF NOT EXISTS `arsip_barang` (
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
  KEY `namaBarang` (`namaBarang`),
  KEY `idSupplier` (`idSupplier`),
  KEY `idKategoriBarang_2` (`idKategoriBarang`),
  KEY `idSupplier_2` (`idSupplier`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;



-- ------------------ OPTIMIZATIONS ---------------------------- */

-- version: 1.2.5

ALTER TABLE  `barang` ADD INDEX (`idKategoriBarang`);
ALTER TABLE  `barang` ADD INDEX (`idSupplier`);
ALTER TABLE  `barang` ADD FULLTEXT (`namaBarang`);

ALTER TABLE  `detail_beli` ADD INDEX (`isSold`);
ALTER TABLE  `detail_beli` ADD INDEX (`jumBarang`);
ALTER TABLE  `detail_beli` ADD INDEX (`idBarang`);
ALTER TABLE  `detail_beli` ADD INDEX (`barcode`);

ALTER TABLE  `detail_jual` ADD INDEX (`username`);
ALTER TABLE  `detail_jual` ADD INDEX (`nomorStruk`);
ALTER TABLE  `detail_jual` ADD INDEX (`barcode`);

ALTER TABLE  `tmp_detail_beli` ADD INDEX (`idSupplier`);
ALTER TABLE  `tmp_detail_beli` ADD INDEX (`username`);

ALTER TABLE  `transaksijual` ADD INDEX (`idUser`);
ALTER TABLE  `transaksijual` ADD INDEX (`tglTransaksiJual`);
ALTER TABLE  `transaksijual` ADD INDEX (`nominal`);

-- ------------------------------------------------------------------


