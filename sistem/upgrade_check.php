<?php  
/* upgrade_check.php ------------------------------------------------------
   	version: 1.5.0

	Part of AhadPOS : http://AhadPOS.com
	License: GPL v2
			http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
			http://vlsm.org/etc/gpl-unofficial.id.html

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License v2 (links provided above) for more details.
----------------------------------------------------------------*/

/* -------------------------------------------------------------
This script will automatically upgrade the database 
as required by the current version of AhadPOS  

NO DESTRUCTIVE QUERY ALLOWED HERE
----------------------------------------------------------------*/

include "../config/config.php";


// Software Version : 1.5.0
// probably a good idea to move these next 3 lines into config.php instead
$major 		= 1;
$minor		= 6;
$revision	= 0;

// serialize this
$current_version	= array($major, $minor, $revision);

// ===============================================================

// get version number from database
$sql	= "SELECT value FROM config WHERE `option` = 'version'";
$hasil	= mysql_query($sql);
$x	= mysql_fetch_array($hasil);

// if no version, means current database structure is from version < 1.5.0
if (mysql_num_rows($hasil) < 1) {

	$dbmajor	= 1;
	$dbminor	= 2;
	$dbrevision	= 0;

} else { // ======= get the major, minor, and revision number 

	$dbversion	= unserialize($x[value]);
	$dbmajor	= $dbversion[0];
	$dbminor	= $dbversion[1];
	$dbrevision	= $dbversion[2];
};


// if up to date, don't do anything at all
if ($major == $dbmajor && $minor == $dbminor && $revision == $dbrevision) { 

	header('location:media.php?module=home');
}


// ---------------------- start upgrading if database version < software version

echo "Current database version : $dbmajor.$dbminor.$dbrevision <br />";
echo "Current software version : $major.$minor.$revision <br /><br />";

if ($major >= 1 && $dbmajor <= $major) { 	// ------- upgrade semua patch versi 1.x
	echo "Checking database version 1.x.x \n <br />";
        check_minor_major1($dbminor, $minor, $dbrevision, $revision);
} else { selesai(); };

if ($major >= 2 && $dbmajor <= $major) { 	// ------- upgrade semua patch versi 2.x
        echo "Checking database version 2.x.x \n <br />";
        check_minor_major2($dbminor, $minor, $dbrevision, $revision);
} else { selesai(); }

if ($major >= 3 && $dbmajor <= $major) { 	// ------- upgrade semua patch versi 3.x
        echo "Checking database version 3.x.x \n <br />";
	check_minor_major3($dbminor, $minor, $dbrevision, $revision);
} else { selesai(); }



exit;

	

// =================================== PATCH VERSI 1.x.x ==========================================
function check_minor_major1($dbminor, $minor, $dbrevision, $revision) {

	if ($minor >= 2 && $dbminor < $minor) {	// ------- upgrade semua patch versi 1.2.x 
	        echo "Upgrading database to version 1.2.x \n <br />";
		check_revision_minor2_major1($dbminor, $minor, $dbrevision, $revision);
	}

	if ($minor >= 5 && $dbminor < $minor) { 	// ------- upgrade semua patch versi 1.5.x
                echo "Upgrading database to version 1.5.x \n <br />";
		check_revision_minor5_major1($dbminor, $minor, $dbrevision, $revision);
	}

	if ($minor >= 6 && $dbminor < $minor) { 	// ------- upgrade semua patch versi 1.6.x
                echo "Upgrading database to version 1.6.x \n <br />";
		check_revision_minor6_major1($dbminor, $minor, $dbrevision, $revision);
	}

}


function check_revision_minor2_major1($dbminor, $minor, $dbrevision, $revision) {

        echo "Upgrading database to version 1.2.0 \n <br />";
	upgrade_old_to_120();

        echo "Upgrading database from 1.2.0 to version 1.2.5 \n <br />";
	upgrade_120_to_125();
}


function check_revision_minor5_major1($dbminor, $minor, $dbrevision, $revision) {

        echo "Upgrading database from 1.2.5 to version 1.5.0 \n <br />";
	upgrade_125_to_150();
}

function check_revision_minor6_major1($dbminor, $minor, $dbrevision, $revision) {

        echo "Upgrading database from 1.5.0 to version 1.6.0 \n <br />";
	upgrade_150_to_160();
}


// ------------------------------------------------------------------------------------
// -----------------------------------------------------------------------------------

function upgrade_old_to_120() {

	// nothing to do here
}


function upgrade_120_to_125() {


        // database structure upgrade -------------------------------------------------
	$sql = "ALTER TABLE  `kategori_barang` CHANGE  `idKategoriBarang`  `idKategoriBarang` INT( 5 ) NOT NULL AUTO_INCREMENT";
	$hasil	= exec_query($sql);

        // optimizations --------------------------------------------------------------
        $sql = "ALTER TABLE  `barang` ADD INDEX (`idKategoriBarang`);
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
		";
        $hasil  = exec_query($sql);
	echo mysql_error();

        // update version number ------------------------------------------------------
	$sql 	= "SELECT * FROM config WHERE `option` = 'version'";
	$hasil	= mysql_query($sql);

	if (mysql_num_rows($hasil) > 0) {
	        $sql = "UPDATE config SET value = '".serialize(array(1,2,5))."' WHERE `option` = 'version'";
	} else {
		$sql  = "INSERT INTO config (`option`, value, description) VALUES ('version', '".serialize(array(1,2,5))."', '')";
	};
        $hasil  = mysql_query($sql);

}


function upgrade_125_to_150() {

        // database structure upgrade -------------------------------------------------
        $sql = "
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

		";
        $hasil  = exec_query($sql);
	echo mysql_error();

        // optimizations --------------------------------------------------------------
        // no optimizations for 1.2.5 --> 1.5.0
	//$sql = "";
        //$hasil  = exec_query($sql);

        // update version number ------------------------------------------------------
        $sql    = "SELECT * FROM config WHERE `option` = 'version'";
        $hasil  = mysql_query($sql);

        if (mysql_num_rows($hasil) > 0) {
                $sql = "UPDATE config SET value = '".serialize(array(1,5,0))."' WHERE `option` = 'version'";
        } else {
                $sql  = "INSERT INTO config (`option`, value, description) VALUES ('version', '".serialize(array(1,5,0))."', '')";
        };
        $hasil  = mysql_query($sql);

}


function upgrade_150_to_160() {

	$sql 	= "CREATE TABLE IF NOT EXISTS `arsip_barang` (
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
		";
        $hasil  = exec_query($sql);
	echo mysql_error();

        // update version number ------------------------------------------------------
        $sql    = "SELECT * FROM config WHERE `option` = 'version'";
        $hasil  = mysql_query($sql);

        if (mysql_num_rows($hasil) > 0) {
                $sql = "UPDATE config SET value = '".serialize(array(1,6,0))."' WHERE `option` = 'version'";
        } else {
                $sql  = "INSERT INTO config (`option`, value, description) VALUES ('version', '".serialize(array(1,6,0))."', '')";
        };
        $hasil  = mysql_query($sql);

}


// =================================== PATCH VERSI 2.x.x ==========================================
function check_minor_major2($dbminor, $minor, $dbrevision, $revision) {

	// nothing here yet
}



// =================================== PATCH VERSI 3.x.x ==========================================
function check_minor_major3($dbminor, $minor, $dbrevision, $revision) {

        // nothing here yet
}

// ==================== general functions ==============================

function exec_query($sql) {
// able to loop through & execute MULTIPLE query lines

	$queries = preg_split("/;+(?=([^'|^\\\']*['|\\\'][^'|^\\\']*['|\\\'])*[^'|^\\\']*[^'|^\\\']$)/", $sql); 
	foreach ($queries as $query){ 
		if (strlen(trim($query)) > 0) mysql_query($query); 
	} 
}



function selesai() {

	echo "Database upgrade finished, thank you. <br /> Silakan <a href=index.php> <b>LOGIN</b> </a> lagi.";
	exit;
}




/* CHANGELOG -----------------------------------------------------------

 1.6.0 / 2013-02-07  : Harry Sufehmi	: table arsip_barang

 1.5.0 / 2012-11-25  : Harry Sufehmi	: initial release

------------------------------------------------------------------------ */

?>
