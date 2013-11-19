<?php

        include "../config/config.php";

        // GLOBAL VARIABLES
        $temporary_barcode = 2323232323;                // barcode prefix for items with non-unique barcode
        $sdb            = 'gandul2';              // source database
        $tdb            = 'newgandul2';              // target database
        $idSupplier     = 368;


// ====START CODE==================================================================================================================

	// konek ke source & target database
	$sourcedb = mysql_connect($server,$username,$password, true) or die("Koneksi gagal");
	mysql_select_db($sdb, $sourcedb);

	// 65536 = bisa multiple SQL statement di satu mysql_query() !
	$targetdb = mysql_connect($server,$username,$password, true, 65536) or die("Koneksi gagal");
	mysql_select_db($tdb, $targetdb);


	// OPTIMIZATIONS
	$sql ="ALTER TABLE `pembelian_detail` ADD INDEX ( `kd_produk` ) ";
	//mysql_query($sql, $sourcedb);


	// ### supplier : dari supplier
	echo " selesai</h1><h1> Sedang memproses: data SUPPLIER .....";
	
	$hasil = mysql_query("select * from m_supplier", $sourcedb) or die("Error : ".mysql_error());
	while($x = mysql_fetch_array($hasil)) {
		$sql = "INSERT INTO supplier (last_update,idSupplier,namaSupplier,alamatSupplier,telpSupplier,Keterangan) 
				VALUES ('2010-06-17', $x[suppliercode], '$x[suppliername]', '$x[address1]', 
				'Telp: $x[phone], Fax: $x[fax]', 'CP: $x[contactname], Ket: $x[remarks],')
			";
		mysql_query($sql,$targetdb);
	};



	//### satuan_barang : dari satuan_ukuran
	echo "selesai</h1><h1>Sedang memproses : data SATUAN .....";	
/*
	$hasil = mysql_query("select * from satuan_ukuran", $sourcedb) or die("Error : ".mysql_error());
	while($x = mysql_fetch_array($hasil)) {
		$sql = "INSERT INTO satuan_barang (idSatuanBarang,namaSatuanBarang) 
				VALUES ($x[id_satuan_ukuran], '$x[satuan_ukuran]')
			";

		// CATATAN : masih belum berhasil menemukan data satuan ukuran 
		// di database software Race4
		//mysql_query($sql,$targetdb);
	};
*/

	// ### rak : dari rak
	echo "selesai</h1><h1>Sedang memproses : data RAK .....";	
/*
	$hasil = mysql_query("select * from rak", $sourcedb) or die("Error : ".mysql_error());
	while($x = mysql_fetch_array($hasil)) {
		$sql = "INSERT INTO rak (idRak,namaRak) 
				VALUES ($x[id_rak],'$x[no_rak]')
			";

		// CATATAN : masih belum berhasil menemukan data Rak 
		// di database software Race4
		//mysql_query($sql,$targetdb);
	};
*/



	// ### kategori : dari kategori
	echo "selesai</h1><h1>Sedang memproses : data KATEGORI .....";	
/*
	// hapus dulu isi table kategori_barang di $targetdb
	mysql_query("delete from kategori_barang;",$targetdb);
	// mulai copy data kategori
	$hasil = mysql_query("SELECT * FROM kategori", $sourcedb) or die("Error : ".mysql_error());
	while($x = mysql_fetch_array($hasil)) {
		$sql = "INSERT INTO kategori_barang (idKategoriBarang,namaKategoriBarang) 
				VALUES ($x[id_kategori],'$x[kategori]')
			";


		// CATATAN : masih belum berhasil menemukan data kategori barnag 
		// di database software Race4
		// mysql_query($sql,$targetdb) or die("Error : ".mysql_error());
	};
*/


// ====================================================================
	echo "selesai</h1><h1>Sedang memproses : data BARANG .....";	

	$ctr= 0;
	$errorctr=0;
	/*$sql = "SELECT s.id_supplier,s.jumlah_gudang,s.jumlah,s.harga_jual,s.kd_barang, s.nama_barang,s.id_kategori,s.id_satuan_ukuran,
			s.id_rak, 
			b.harga
		FROM stock as s, (SELECT bb.harga FROM pembelian_detail as bb, stock as ss 
					WHERE ss.kd_barang=bb.kd_produk ORDER BY bb.trx_time DESC LIMIT 1) as b  
		LIMIT $ctr, ".($ctr+10000);
	*/


	$sql	= "SELECT m.suppliercode, m.endstock, m.sellingprice, m.barcode1, m.lastbuyingprice, m.itemname
		FROM m_item AS m 
		LIMIT $ctr, ".($ctr+10000);


	$hasil = mysql_query($sql, $sourcedb) or die("Error : ".mysql_error()." -- SQL: $sql");
	while (mysql_num_rows($hasil) > 0) {	
		echo "<br> data: ".($ctr+1)." s/d ".($ctr+10000)." ....";
		
	while(($x = mysql_fetch_array($hasil)) !== false) {

		// jika barcode1 = ''
		// maka baca data barcode dari itemcode
		if ($x[barcode1] == '') {
			$sql 	= "SELECT itemcode FROM m_item WHERE itemname = '".$x[itemname]."'";
			$hasil2 = mysql_query($sql, $sourcedb);
			$z = mysql_fetch_array($hasil2);
			$x[barcode1]	= $z[itemcode];		

			echo "\n\n ===== barcode dibaca dari itemcode : $z[itemcode]";
		};

		// cari HargaBeli
		$HargaBeli = $x[lastbuyingprice];		
		if (empty($HargaBeli)) { $HargaBeli = 0;};
		
		// ### tmp_detail_beli :: dari stock
		// perlu simpan ke tmp_detail_beli dulu, untuk mendapatkan idBarang yang unique (di generate oleh MySQL)
		$sql = "INSERT INTO tmp_detail_beli (username,tglExpire,tglTransaksi,idSupplier,jumBarang, 
					hargaBeli,hargaJual,barcode) 
				VALUES ('admin','0000-00-00','2010-06-17',$x[suppliercode],".($x[endstock]).",
					$HargaBeli,$x[sellingprice],'$x[barcode1]')
			";
		mysql_query($sql,$targetdb) or die("Error : ".mysql_error()." sql: ".$sql." -- HargaBeli: ".$HargaBeli);
		/*
		username='admin'
		tglExpire='0000-00-00'
		tglTransaksi='2010-06-17'
		get:
		# idSupplier 		(num) suppliercode
		# jumBarang 		(num) endstock
		# hargaBeli 		(num) lastbuyingprice
		# hargaJual 		(num) sellingprice
		# barcode 		(text) barcode1
		*/


		// cari Idbarang
		//$Idbarang = mysql_insert_id($targetdb) or die("Error : ".mysql_error());
		$z = mysql_query("SELECT LAST_INSERT_ID() FROM tmp_detail_beli",$targetdb) or die("Error : ".mysql_error()." -- SQL: $sql");
		$zz = mysql_fetch_array($z);
		$IdBarang = $zz["LAST_INSERT_ID()"];
	
		// ganti ' menjadi `, agar tidak error saat INSERT
		$x[nama_barang] = str_replace("'","`",$x[itemname]);

		// ### barang	:: untuk setiap record di tabel tmp_detail_beli, buat juga record di tabel barang
		$sql = "INSERT INTO barang (idSupplier,username,last_update,idBarang,namaBarang,idKategoriBarang,idSatuanBarang,jumBarang,
				hargaJual,barcode,idRak) 
			VALUES ($x[suppliercode],'admin','2010-06-17',$IdBarang,'$x[itemname]', 1, 3,
				".($x[endstock]).", $x[sellingprice],'$x[barcode1]', 1)
			";
		$hasil3 = mysql_query($sql,$targetdb); 

		if (!$hasil3) {
			$errorctr++;
			echo "Error : ".mysql_error()." sql: ".$sql;

			$temporary_barcode++;
		};


	}; // end while(($x = mysql_fetch_array($hasil)) !== false) 
	

		// siap-siap untuk looping berikutnya
		$ctr = $ctr + 10000;

		/*$sql = "SELECT s.id_supplier,s.jumlah_gudang,s.jumlah,s.harga_jual,s.kd_barang, s.nama_barang,s.id_kategori,s.id_satuan_ukuran,
			s.id_rak, 
			b.harga
		FROM stock as s, (SELECT bb.harga FROM pembelian_detail as bb, stock as ss 
					WHERE ss.kd_barang=bb.kd_produk ORDER BY bb.trx_time DESC  LIMIT 1) as b  
		LIMIT $ctr, ".($ctr+10000);
		*/

		$sql	= "SELECT m.suppliercode, m.endstock, m.sellingprice, m.barcode1, m.lastbuyingprice, m.itemname
			FROM m_item AS m 
			LIMIT $ctr, ".($ctr+10000);

		$hasil = mysql_query($sql, $sourcedb) or die("Error : ".mysql_error()." -- SQL: $sql");
	}; // end while (mysql_num_rows($hasil) > 0) 


// selesai input data ke tmp_detail_beli,
// mulai pindahkan ke transaksi_beli


	// ### transaksi_beli :: baca dari tmp_detail_beli, grouped per supplier
	echo "selesai</h1><h1>Sedang memproses : data TRANSAKSI BELI ..... </h1>";	
	
	$hasil = mysql_query("SELECT DISTINCT idSupplier FROM tmp_detail_beli", $targetdb) or die("Error : ".mysql_error());
	while($x = mysql_fetch_array($hasil)) {

		echo "<br> Supplier: $x[idSupplier]";
		
		// simpan transaksi beli nya
		$sql = "INSERT INTO transaksibeli (username,idUser,last_update,idTipePembayaran,tglTransaksibeli,
					NomorInvoice,nominal,idSupplier) 
			VALUES ('admin',1,'2010-06-17',1,'2010-06-17',0,0,$x[idSupplier])
			";
		mysql_query($sql,$targetdb) or die("Error : ".mysql_error()." -- SQL: $sql");
		/*
		username='admin'
		idUser=1
		last_update='2010-06-17'
		idTipePembayaran=1
		tglTransaksiBeli='2010-06-17'
		NomorInvoice=0
		nominal=0
		get:
		# idSupplier 		: dari tmp_detail_beli.idSupplier
		*/

		// cari IdTransaksiBeli
		$z = mysql_query("SELECT LAST_INSERT_ID() FROM transaksibeli",$targetdb) or die("Error : ".mysql_error());
		$zz = mysql_fetch_array($z);
		$IdTransaksiBeli = $zz["LAST_INSERT_ID()"];

		// kumpulkan semua Detail transaksi beli supplier ybs
		$hasil2 = mysql_query("SELECT * FROM tmp_detail_beli WHERE idSupplier = $x[idSupplier]", $targetdb) or die("Error : ".mysql_error());
		while($y = mysql_fetch_array($hasil2)) {

			$sql = "INSERT INTO detail_beli (username,isSold,IdTransaksiBeli,tglExpire,idBarang,jumBarang,jumBarangAsli,hargaBeli,barcode) 
					VALUES ('admin','N', $IdTransaksiBeli,'0000-00-00',$y[idBarang],$y[jumBarang],$y[jumBarang],$y[hargaBeli],
						'$y[barcode]')
				";
			
			mysql_query($sql,$targetdb) or die("Error : ".mysql_error()." sql: ".$sql);
			/*
			username='admin'
			isSold='N'
			tglExpire='0000-00-00'
			get :
			# idTransaksiBeli 	: dari transaksi_beli.idTransaksiBeli
			# idBarang 		: dari tmp_detail_beli.idBarang
			# jumBarang		: dari tmp_detail_beli.jumBarang 	
			# jumBarangAsli 	: dari tmp_detail_beli.jumBarang
			# hargaBeli 	 	: dari tmp_detail_beli.hargaBeli
			# barcode 		: dari tmp_detail_beli.barcode
			*/
		}; // while($y = mysql_fetch_array($hasil2))

	}; // while($x = mysql_fetch_array($hasil))



/*
users:
admin
awan
hamdani
harry
aie
ayu
lela
*/


	// ### finish : hapus semua isi tmp_detail_beli

	echo "s<h1>Sedang memproses : hapus data sementara .....";	
	
	mysql_query("DELETE FROM tmp_detail_beli", $targetdb) or die("Error : ".mysql_error());	
	

	// hapus duplikasi di detail_beli
	$sql = "CREATE TABLE sementara AS SELECT * FROM detail_beli WHERE 1 GROUP BY barcode";
	mysql_query($sql,$targetdb);
	$sql = "DROP TABLE detail_beli";
	mysql_query($sql,$targetdb);
	$sql = "RENAME TABLE sementara TO detail_beli";
	mysql_query($sql,$targetdb);

	
	echo " SELESAI !</h1> \n\n Error : $errorctr \n\n";
	exit;









/* ----------------
   daftar perintah vim, 
	untuk konversi backup data software race4 (mysql 4.0.x :-(
	menjadi format mysqldump versi 5.x

### backup menggunakan statement INSERT INTO yang lambat (bukan bulk)
### maka agar restore bisa cepat, musti menggunakan engine MEMORY
### jika tidak, maka proses restore bisa memakan waktu puluhan jam !!
:%s/TYPE=InnoDB/ENGINE=MEMORY/g

:%s/mediumtext/varchar(250)/g
:%s/decimal(8,3) NOT NULL default '0.000'/bigint(20) default '0'/g
:%s/decimal(13,0) NOT NULL default '0'/bigint(20) default '0'/g
:%s/decimal(13,2) NOT NULL default '0.00'/bigint(20) default '0'/g
:%s/decimal(8,4) NOT NULL default '0.0000'/bigint(20) default '0'/g
:%s/decimal(6,2) NOT NULL default '0.00'/bigint(20) default '0'/g
:%s/decimal(10,3) NOT NULL default '0.000'/bigint(20) default '0'/g
:%s/decimal(10,0) NOT NULL default '0'/bigint(20) default '0'/g
:%s/decimal(12,2) NOT NULL default '0.00'/bigint(20) default '0'/g
:%s/decimal(12,0) NOT NULL default '0'/bigint(20) default '0'/g
:%s/decimal(8,0) NOT NULL default '0'/bigint(20) default '0'/g
:%s/decimal(8,2) NOT NULL default '0.00'/bigint(20) default '0'/g
:%s/decimal(5,2) NOT NULL default '0.00'/bigint(20) default '0'/g
:%s/decimal(9,0) NOT NULL default '0'/bigint(20) default '0'/g

:%s/decimal(8,3) NOT NULL default '0.001'/bigint(20) default '0'/g
:%s/decimal(15,2) NOT NULL default '0.00'/bigint(20) default '0'/g
:%s/decimal(21,3) default NULL/bigint(20) default '0'/g
:%s/decimal(13,0) default NULL/bigint(20) default '0'/g
:%s/decimal(9,2) NOT NULL default '0.00'/bigint(20) default '0'/g

:%s//bigint(20) default '0'/g
:%s//bigint(20) default '0'/g
:%s//bigint(20) default '0'/g
:%s//bigint(20) default '0'/g
:%s//bigint(20) default '0'/g
:%s//bigint(20) default '0'/g
:%s//bigint(20) default '0'/g
:%s//bigint(20) default '0'/g

------------------- */

?>
