<?php
/* mod_barang.php ------------------------------------------------------
   	version: 1.5.0

	Part of AhadPOS : http://ahadpos.com
	License: GPL v2
			http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
			http://vlsm.org/etc/gpl-unofficial.id.html

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License v2 (links provided above) for more details.
----------------------------------------------------------------*/

if (basename($_SERVER['SCRIPT_NAME']) <> 'media.php') { 
	// when this module is called directly, we'll need to initialize the session properly
	session_start();
	include "../../config/config.php";
	include "function.php"; 
};

check_user_access(basename($_SERVER['SCRIPT_NAME']));




	//HS javascript untuk menampilkan popup
?>	
	
	<SCRIPT TYPE="text/javascript">
	<!--
	function popupform(myform, windowname)
	{
		if (! window.focus)return true;
		window.open('', windowname, 'type=fullWindow,fullscreen,scrollbars=yes');
		myform.target=windowname;
		return true;
	}
	//-->
	</SCRIPT>

<?php




$ambilSupplier = mysql_query("select * from supplier");
$ambilRak = mysql_query("select * from rak");
$ambilKategoriBarang = mysql_query("select * from kategori_barang");
$ambilSatuanBarang = mysql_query("select * from satuan_barang");

	echo "
		<SCRIPT TYPE=\"text/javascript\">
		<!--
		function popupform(myform, windowname)
		{
		if (! window.focus)return true;
		window.open('', windowname, 'type=fullWindow,fullscreen,scrollbars=yes,menubar=yes');
		myform.target=windowname;
		return true;
		}
		//-->
		</SCRIPT>
	";


switch($_GET['act']){
  // Tampil barang 
  default:  // ========================================================================================================================
    echo "<h2>Data barang</h2>
		
	<div style=\"float:left\">
          <form method=POST action='?module=barang&act=tambahbarang'>
          <input type=submit accesskey='t' value='(t) Tambah Barang'></form>
	</div>

	<div style=\"float:left\">
          <form method=POST action='?module=barang&act=caribarang1'>
          <input type=submit accesskey='c' value='(c) Cari Barang'></form>
	</div>

	<div style=\"float:left\">
          <form method=POST action='?module=barang&act=cetaklabel1'>
          <input type=submit accesskey='l' value='(l) Cetak Label'></form>
	</div>

	<div style=\"float:left\">
          <form method=POST action='?module=barang&act=cetakbarang1'>
          <input type=submit accesskey='b' value='(b) Cetak Stock Barang'></form>
	</div>

	<br /><br />

	<div style=\"float:left\">
          <form method=POST action='?module=barang&act=cetakSO'>
          <input type=submit accesskey='s' value='(s) Cetak Stock Opname' ></form>
	</div>

	<div style=\"float:left\">
          <form method=POST action='?module=barang&act=inputSO'>
          <input type=submit accesskey='o' value='(o) Input Stock Opname' ></form>
	</div>

	<div style=\"float:left\">
          <form method=POST action='?module=barang&act=inputrak' onSubmit=\"popupform(this, 'inputrak')\" >
          <input type=submit accesskey='i' value='(i) Input Cepat Rak' ></form>
	</div>

	<div style=\"float:left\">
          <form method=POST action='../tools/fast-stock-opname/fast-SO.php'>
          || <input type=submit value='Input Fast SO' ></form>
	</div>

	<div style=\"float:left\">
          <form method=POST action='?module=barang&act=ApproveFastSO1'>
          <input type=submit value='Approve Fast SO' ></form>
	</div>

	<br /><br />
	
	<div style=\"float:left\">
          <form method=POST action='?module=barang&act=returbarang1' onSubmit=\"popupform(this, 'inputrak')\" >
	  <form method=POST action='modul/js_input_retur_barang.php?act=caricustomer' onSubmit=\"popupform(this, 'INPUT_RETUR_BARANG')\">
          <input type=submit accesskey='r' value='(r) Input Retur'></form>
	</div>


          <br/>
          <table class=tableku>
          <tr><th>no</th><th>Barcode</th><th>Nama Barang</th><th>Kategori Barang</th>
                <th>Satuan Barang</th><th>Jumlah</th><th>Harga Jual</th><th>aksi</th></tr>";

	if ($_GET[p]) { $mulai = $_GET[p] * 100;} else { $mulai = 0;};

//HS query terlampir buggy !! dan juga amat lambat
//    $tampil=mysql_query("SELECT idBarang,namaBarang,namaKategoriBarang,
//                        namaSatuanBarang,jumBarang,hargaJual, barcode 
//                        FROM barang b, kategori_barang kb, satuan_barang sb
//                        ORDER BY b.namaBarang ASC LIMIT $mulai,100 ");

//    $tampil=mysql_query("SELECT b.idBarang,b.namaBarang,b.jumBarang,b.hargaJual,b.barcode, k.namaKategoriBarang, s.namaSatuanBarang   
//                        FROM barang AS b, kategori_barang AS k, satuan_barang AS s 
//			WHERE b.idKategoriBarang = k.idKategoriBarang AND b.idSatuanBarang = s.idSatuanBarang 
//                        ORDER BY namaBarang ASC LIMIT $mulai,100 ");

	// query ini lebih cepat & rapi
	// credit : Insan Fajar 
	$tampil = mysql_query("SELECT
				`barang`.`idBarang`,
				`barang`.`namaBarang`,
				`barang`.`idKategoriBarang`,
				`kategori_barang`.`namaKategoriBarang`,
				`barang`.`idSatuanBarang`,
				`satuan_barang`.`namaSatuanBarang`,
				`barang`.`jumBarang`,
				`barang`.`hargaJual`,
				`barang`.`barcode`
			FROM `barang`
				LEFT JOIN `kategori_barang`
					ON `barang`.`idKategoriBarang` = `kategori_barang`.`idKategoriBarang`
				LEFT JOIN `satuan_barang`
					ON `barang`.`idSatuanBarang` = `satuan_barang`.`idSatuanBarang`
				ORDER BY `namaBarang` ASC LIMIT $mulai,100");


    $no=1; $ctr=1;
    while (($r=mysql_fetch_array($tampil)) or ($ctr < 100)){
        //untuk mewarnai tabel menjadi selang-seling
        if(($no % 2) == 0){
            $warna = "#EAF0F7";
	}
	else{
            $warna = "#FFFFFF";
	}
	echo "<tr bgcolor=$warna>";//end warna
       echo "<td align=right class=td>$no</td>
             <td class=td>$r[barcode]</td>
             <td class=td>$r[namaBarang]</td>
             <td align=center class=td>$r[namaKategoriBarang]</td>
             <td align=center class=td>$r[namaSatuanBarang]</td>
             <td align=right class=td>$r[jumBarang]</td>
             <td align=right class=td>$r[hargaJual]</td>
             <td class=td><a href=?module=barang&act=editbarang&id=$r[barcode]>Ubah</a>|Ha<a href=./aksi.php?module=barang&act=hapus&id=$r[idBarang]>pus</a>
             </td></tr>";
      $no++; $ctr++;
    }
    echo "</table> <br />";

	$sql1 = "SELECT DISTINCT COUNT(barcode) FROM barang";
	$proses1 = mysql_query($sql1);
	$output1 = mysql_fetch_array($proses1);
	$jumlah_barang = $output1[0] / 100;

	for ($i = 0; $i <= $jumlah_barang; $i++) {
		echo "[<a href='media.php?module=barang&p=$i'> $i </a>] ";
	};

    echo "
    <p>&nbsp;</p>
    <a href=javascript:history.go(-1)><< Kembali</a>";
    break;



  case "returbarang1": // ========================================================================================================================


		// pilih printer
		$sql 	= "SELECT namaWorkstation,printer_commands,workstation_address FROM workstation ";
		$hasil 	= mysql_query($sql) or die(mysql_error());

		echo "
			<form method=POST action='modul/js_input_retur_barang.php?act=caricustomer' onSubmit=\"popupform(this, 'INPUT_RETUR_BARANG')\">	
		<table>
        	<tr>
			<td>Pilih Printer </td>
			<td>: <select name='namaPrinter'>";
		
		while($printer = mysql_fetch_array($hasil)){
			echo "<option value='$printer[printer_commands]'>$printer[namaWorkstation]</option>\n";
		}	

		echo "
			</td>
			</tr>

        		<tr><td colspan=2>&nbsp;</td></tr>
        		<tr><td colspan=2><input type=submit value='Pilih Printer'>&nbsp;&nbsp;&nbsp;
        	                        <input type=reset value='Batal'></td></tr>
			</table>
			</form>
		";

	break;



  case "tambahbarang": // ========================================================================================================================
    echo "<h2>Tambah Barang</h2>
          <form method=POST action='./aksi.php?module=barang&act=input' name='tambahbarang'>
          <table>
          <tr><td>Barcode</td><td> : <input type=text name='barcode' size=30 value=$_GET[id]></td></tr>
          <tr><td>Nama Barang</td><td> : <input type=text name='namaBarang' size=30></td></tr>
          <tr><td>Supplier</td>
                <td> : <select name='supplier'>
                            <option value='0'>- Supplier -</option>";
                            while($supplier = mysql_fetch_array($ambilSupplier)){
                                echo "<option value='$supplier[idSupplier]'>$supplier[namaSupplier]</option>";
                            }
            echo "</select></td></tr>
          <tr><td>Kategori Barang</td>
                <td> : <select name='kategori_barang'>
                            <option value='0'>- Kategori Barang-</option>";
                            while($kategori = mysql_fetch_array($ambilKategoriBarang)){
                                echo "<option value='$kategori[idKategoriBarang]'>$kategori[namaKategoriBarang]</option>";
                            }
            echo "</select></td></tr>
          <tr><td>Satuan Barang</td>
                <td> : <select name='satuan_barang'>
                            <option value='0'>- Satuan Barang-</option>";
                            while($satuan = mysql_fetch_array($ambilSatuanBarang)){
                                echo "<option value='$satuan[idSatuanBarang]'>$satuan[namaSatuanBarang]</option>";
                            }
            echo "</select></td></tr>
          <tr><td colspan=2>&nbsp;</td></tr>
          <tr><td colspan=2 align='right'><input type=submit value=Simpan>&nbsp;&nbsp;&nbsp;
                            <input type=button value=Batal onclick=self.history.back()></td></tr>
          </table></form>";
     break;




  case "caribarang1": // ========================================================================================================================

 	echo "
	
		<h2>Cari Barang</h2>
		<form method=POST action='?module=barang&act=caribarang2'>

          <table>
		<tr><td> (r) Pilih Rak ? </td>
                <td> : <select name='rak' accesskey='r'>
			<option value='0'>-- Tidak Usah --</option>";

                            while($rak = mysql_fetch_array($ambilRak)){
                                    echo "<option value='$rak[idRak]'>$rak[namaRak]</option>";
                            }
            echo "</select></td></tr>

          <tr><td>(b) Barcode</td><td> : <input accesskey='b' type=text name='barcode' size=30 value='0'></td></tr>
          <tr><td>(n) Nama Barang</td><td> : <input accesskey='n' type=text name='namaBarang' size=30 value=''></td></tr>
		<tr><td><input type=submit accesskey='c' value='(c) Cari Barang'></td></tr>

		</table></form>";

     break;


  case "caribarang2":


	if ($_POST[rak] == '0') {
		$q_rak = "";
	} else {
		$q_rak = " idRak=$_POST[rak] AND ";
	};

	if ($_POST[barcode] == '0') {
		$sql = "SELECT b.idBarang,b.namaBarang,b.jumBarang,b.hargaJual,b.barcode, k.namaKategoriBarang, s.namaSatuanBarang   
                        FROM barang AS b, kategori_barang AS k, satuan_barang AS s 
			WHERE $q_rak namaBarang LIKE '%$_POST[namaBarang]%' AND 
				b.idKategoriBarang = k.idKategoriBarang AND b.idSatuanBarang = s.idSatuanBarang 
                        ORDER BY namaBarang ASC";
	} else {
		$sql = "SELECT b.idBarang,b.namaBarang,b.jumBarang,b.hargaJual,b.barcode, k.namaKategoriBarang, s.namaSatuanBarang   
                        FROM barang AS b, kategori_barang AS k, satuan_barang AS s 
			WHERE $q_rak barcode LIKE '$_POST[barcode]%' AND 
				b.idKategoriBarang = k.idKategoriBarang AND b.idSatuanBarang = s.idSatuanBarang 
                        ORDER BY namaBarang ASC";
	};
	$cari=mysql_query($sql);	
    	//echo $sql;

    echo "<table class=tableku>
          <tr><th>no</th><th>Barcode</th><th>Nama Barang</th><th>Kategori Barang</th>
                <th>Satuan Barang</th><th>Jumlah</th><th>Harga Jual</th><th>aksi</th></tr>";

    $no=1;
    while ($r=mysql_fetch_array($cari)) {
        //untuk mewarnai tabel menjadi selang-seling
        if(($no % 2) == 0){
            $warna = "#EAF0F7";
	}
	else{
            $warna = "#FFFFFF";
	}
	echo "<tr bgcolor=$warna>";//end warna
       echo "<td align=right class=td>$no</td>
             <td class=td>$r[barcode]</td>
             <td class=td>$r[namaBarang]</td>
             <td align=center class=td>$r[namaKategoriBarang]</td>
             <td align=center class=td>$r[namaSatuanBarang]</td>
             <td align=right class=td>$r[jumBarang]</td>
             <td align=right class=td>$r[hargaJual]</td>
             <td class=td><a href=?module=barang&act=editbarang&id=$r[barcode]>Ubah</a>|Ha<a href=./aksi.php?module=barang&act=hapus&id=$r[idBarang]>pus</a>
             </td></tr>";
      $no++; 
    }
    echo "</table> <br />";

    break;




  case "cetaklabel1": // ========================================================================================================================

 	echo "
	
		<h2>Cetak Label Barang</h2>
		<form method=POST action='?module=barang&act=cetaklabel2'>

          <table>
          <tr><td>(s) Supplier</td>
                <td> : <select name='supplier' accesskey='s'>
			<option value='0'>-- Pilih Supplier --</option>";
                            while($supplier = mysql_fetch_array($ambilSupplier)){
                                    echo "<option value='$supplier[idSupplier]'>$supplier[namaSupplier]</option>";
                            }
            echo "</select></td></tr>
          <tr><td>(r) Rak</td>
                <td> : <select name='rak' accesskey='r'>
			<option value='0'>-- Pilih Rak --</option>";
                            while($rak = mysql_fetch_array($ambilRak)){
                                    echo "<option value='$rak[idRak]'>$rak[namaRak]</option>";
                            }
            echo "</select></td></tr>

		<tr><td><input type=submit accesskey='l' value='(l) Cetak Label Barang'></td></tr>

		</table></form>";

     break;


  case "cetaklabel2":


	if ($_POST[rak] == '0') {
		$sql = "SELECT b.idBarang,b.namaBarang,b.jumBarang,b.hargaJual,b.barcode, k.namaKategoriBarang, s.namaSatuanBarang   
                        FROM barang AS b, kategori_barang AS k, satuan_barang AS s 
			WHERE idSupplier=$_POST[supplier] AND 
				b.idKategoriBarang = k.idKategoriBarang AND b.idSatuanBarang = s.idSatuanBarang 
                        ORDER BY namaBarang ASC";
		$cari=mysql_query($sql);	
		$q='sup'; $sql=$_POST[supplier];
	} else {
		$sql = "SELECT b.idBarang,b.namaBarang,b.jumBarang,b.hargaJual,b.barcode, k.namaKategoriBarang, s.namaSatuanBarang   
                        FROM barang AS b, kategori_barang AS k, satuan_barang AS s 
			WHERE idRak=$_POST[rak] AND 
				b.idKategoriBarang = k.idKategoriBarang AND b.idSatuanBarang = s.idSatuanBarang 
                        ORDER BY namaBarang ASC";
		$cari=mysql_query($sql);		
		$q='rak'; $sql=$_POST[rak];
	};

	$jumlah_pilihan = mysql_num_rows($cari);

	echo "
		<h2>Cetak Label Barang - Pilih Barang</h2>
		<form method=POST action='modul/mod_barang.php?act=cetaklabel3' onSubmit=\"popupform(this, 'cetaklabel')\">
		<input type=hidden name=total value=$jumlah_pilihan> 
		<input type=hidden name=q value=$q>
		<input type=hidden name=sql value=$sql>


	  <table class=tableku>
          <tr><th>no</th><th>Barcode</th><th>Nama Barang</th><th>Kategori Barang</th>
                <th>Satuan Barang</th><th>Jumlah</th><th>Harga Jual</th><th>Cetak ?</th></tr>";

    $no=1;$ctr=1;
    while ($r=mysql_fetch_array($cari)) {
        //untuk mewarnai tabel menjadi selang-seling
        if(($no % 2) == 0){
            $warna = "#EAF0F7";
	}
	else{
            $warna = "#FFFFFF";
	}
	echo "<tr bgcolor=$warna>";//end warna
       echo "<td align=right class=td>$no</td>
             <td class=td>$r[barcode]</td>
             <td class=td>$r[namaBarang]</td>
             <td align=center class=td>$r[namaKategoriBarang]</td>
             <td align=center class=td>$r[namaSatuanBarang]</td>
             <td align=right class=td>$r[jumBarang]</td>
             <td align=right class=td>$r[hargaJual]</td>
             <td class=td><center>  <input type=checkbox name=cl$ctr checked=yes> </center></td></tr>";
      $no++; $ctr++;
    }	
    echo "

	</table> <br />
	<input type=submit accesskey='l' value='(l) Cetak Label Barang'></td></tr>
	";

    
	break;


  case "cetaklabel3": 

	include "../../config/config.php";

	if ($_POST[q] == 'sup') {
		$cari=mysql_query("SELECT * FROM barang WHERE idSupplier=$_POST[sql] ORDER BY namaBarang ASC");	
	} else {
		$cari=mysql_query("SELECT * FROM barang WHERE idRak=$_POST[sql] ORDER BY namaBarang ASC");		
	};

	$lebar_label 		= 200;
	$tinggi_label 		= 112;
	$label_per_baris 	= 3;
	$baris_per_halaman 	= 7;

    $total = $_POST[total];
    $baris=1; $kolom=1;
    echo "<div style=\"float:none\">"; 

	for ($i = 1; $i <= $total; $i++) {

		$r = mysql_fetch_array($cari);
		if ($_POST["cl$i"] == 'on') {

			$clear = "";
			// cek posisi saat ini
			if ($kolom > $label_per_baris) { 
				$kolom = 1;
				$baris++;
				$clear = " clear:left; "; //echo "</div><div style=\"float:none\">"; // ganti baris
			};
			if ($baris > $baris_per_halaman) {
				$baris = 1;
				echo '<p style="page-break-after: always" />';
			};

			$namaBarang = $r[namaBarang];
			// jika terlalu panjang nama barangnya 
			if (strlen($namaBarang) > 15) {
				// bikin menjadi 2 baris
				$namaBarang = substr($namaBarang,0,15) .
					"</p><p style=\"line-height:0px; letter-spacing:-2px; text-align:center; font-family:Arial; font-size:12pt; font-weight:normal; text-transform:uppercase;  \">". substr($namaBarang,15);
			};

			// cetak label
			echo "\n 

				<div style=\"border: thin solid #000000; $clear float:left; margin-right:10px; margin-bottom:10px; width:".$lebar_label."px; height:".$tinggi_label."px\">
				<p style=\"line-height:0px; letter-spacing:-2px; text-align:center; font-family:Arial; font-size:12pt; font-weight:normal; text-transform:uppercase;  \">
					$namaBarang	
				</p>
				<p style=\"line-height:0px; letter-spacing:+2px; text-align:center; font-family:Arial; font-size:26pt; \">
					".number_format($r[hargaJual],0,',','.')."	</p>
				<p style=\"line-height:0px; text-align:left; font-family:Arial; font-size:6pt; \">
					$r[barcode] - $r[idRak]
				</div>
			";

			$kolom++;
		};
	} // for

	echo "</div>";

    break;





  case "inputrak": // ========================================================================================================================

	include "../../config/config.php";

	if ($_POST[masuk]) {
		$tgl = date("Y-m-d");
		$sql = "UPDATE barang SET idRak = '$_POST[rak]' 
                    WHERE barcode = '$_POST[barcode]'";
		//echo $sql;
		mysql_query($sql);
	};

    echo "
	  <h2>Input Cepat Rak</h2>
          <form method=POST action='?module=barang&act=inputrak' name='inputrakbarang'>

          <table>
          <tr><td>(r) Rak</td>
                <td> : <select name='rak' accesskey='r'>
			<option value='0'>-- Pilih Rak --</option>";
                            while($rak = mysql_fetch_array($ambilRak)){
                                if($rak[idRak] == $_POST[rak]){
                                    echo "<option value='$rak[idRak]' selected>$rak[namaRak]</option>";
                                }
                                else{
                                    echo "<option value='$rak[idRak]'>$rak[namaRak]</option>";
				}
                            }
            echo "</select></td></tr>
          <tr><td>(b) Barcode</td><td> : <input type=text name='barcode' id='barcode' accesskey='b' size=30 value=''>	</td></tr>
	  </table>
	<input type=submit accesskey='i' name='masuk' value='(i) Input'></td></tr>

	";

	echo "
		<script>
			var txtBox=document.getElementById(\"barcode\");
			if (txtBox!=null ) txtBox.focus();
		</script>";

  break;




  case "cetakSO": // ========================================================================================================================

 	echo "
	
		<h2>Cetak Stock Opname</h2>
		<form method=POST action='modul/mod_barang.php?act=cetakSO2' onSubmit=\"popupform(this, 'Cetak Stock Opname')\">

          <table>

          <tr><td>(r) Rak</td>
                <td> : <select name='rak' accesskey='r'>
			<option value='0'>-- Pilih Rak --</option>";
                            while($rak = mysql_fetch_array($ambilRak)){
                                    echo "<option value='$rak[idRak]'>$rak[namaRak]</option>";
                            }
            echo "</select></td></tr>

		<tr><td colspan=2><input type=submit accesskey='c' value='(c) Cetak Stock Opname' ></td></tr>

		</table></form>";

     break;



		
  case "cetakSO2": 

	include "../../config/config.php";

	$cari=mysql_query("SELECT * FROM barang WHERE idRak=$_POST[rak] ORDER BY namaBarang ASC");		

	echo "
	<h1>Rak #$_POST[rak]</h1>
	  <table class=tableku>
          <tr><th>no</th><th>Barcode</th><th>Nama Barang</th><th>Harga <br />Jual</th>
                <th>Jml <br />Tercatat</th><th>Selisih</th></tr>";

    $no=1;$ctr=1;
    while ($r=mysql_fetch_array($cari)) {
        //untuk mewarnai tabel menjadi selang-seling
        if(($no % 2) == 0){
            $warna = "#EAF0F7";
	}
	else{
            $warna = "#FFFFFF";
	}
	echo "<tr bgcolor=$warna>";//end warna
       echo "<td align=left class=td>$no</td>
             <td class=td>$r[barcode]</td>
             <td class=td>$r[namaBarang]</td>
             <td align=right class=td>".number_format($r[hargaJual],0,',','.')."	</td>
             <td align=right class=td><center>$r[jumBarang]</center>			</td>
             <td align=right class=td>  						</td>
		</tr>";
      $no++; $ctr++;
    }	
    echo "

	</table> <br />
	";

    break;



  case "inputSO": // ========================================================================================================================

 	echo "
	
		<h2>Input Stock Opname - Pilih Rak</h2>
		<form method=POST action='?module=barang&act=inputSO2'>

          <table>

          <tr><td>(r) Rak</td>
                <td> : <select name='rak' accesskey='r'>
			<option value='0'>-- Pilih Rak --</option>";
                            while($rak = mysql_fetch_array($ambilRak)){
                                    echo "<option value='$rak[idRak]'>$rak[namaRak]</option>";
                            }
            echo "</select></td></tr>

		<tr><td colspan=2><input type=submit accesskey='i' value='(i) Mulai Input Stock Opname' ></td></tr>

		</table></form>";

     break;



		
  case "inputSO2":  // mulai input hasil Stock Opname

	include "../../config/config.php";
	
	$sql = "SELECT * FROM barang WHERE idRak=$_POST[rak] ORDER BY namaBarang ASC";
	$cari=mysql_query($sql);		
	//echo $sql;

	echo "
		<h2>Input Stock Opname (ID Rak: $_POST[rak])</h2>
		<form method=POST action='?module=barang&act=inputSO3'>


	  <table class=tableku>
          <tr><th>no</th><th>Barcode</th><th>Nama Barang</th><th>Harga <br />Jual</th>
                <th>Jml <br />Tercatat</th><th>Selisih</th></tr>";

    $no=1;$ctr=1;
    while ($r=mysql_fetch_array($cari)) {
        //untuk mewarnai tabel menjadi selang-seling
        if(($no % 2) == 0){
            $warna = "#EAF0F7";
	}
	else{
            $warna = "#FFFFFF";
	}
	echo "<tr bgcolor=$warna>";//end warna

       echo "<td align=left class=td>$no</td>
             <td class=td>$r[barcode]</td>
					<input type=hidden name='barcode$ctr' value='$r[barcode]'>
             <td class=td>$r[namaBarang]</td>
					<input type=hidden name='namaBarang$ctr' value='$r[namaBarang]'>
             <td align=right class=td>".number_format($r[hargaJual],0,',','.')."		</td>
             <td align=right class=td><center>$r[jumBarang]</center>				
					<input type=hidden name='jmlTercatat$ctr' value='$r[jumBarang]'>	</td>
             <td align=right class=td>	<input type=text name='selisih$ctr' size=2 value='0'>	</td>
		</tr>";

      $no++; $ctr++;
    }	
    echo "

		<tr><td colspan=2><input type=submit value='Input Stock Opname' ></td></tr>

	</table> <br />
	

		<input type=hidden name=rak value='$_POST[rak]'>
		<input type=hidden name=username value='$_SESSION[uname]'>
		<input type=hidden name=ctr value='$ctr'>
	
	";

    break;


  case "inputSO3":  // simpan di database

	include "../../config/config.php";

	// default max_input_vars hanya 1000, ini sangat tidak mencukupi pada rak yang ada banyak jenis barangnya
	ini_set('max_input_vars','20000');
	ini_set('suhosin.post.max_vars','20000');
	ini_set('suhosin.request.max_vars','20000');

	$sql = "INSERT INTO stock_opname (username, tanggalSO, idRak) VALUES 
		('$_POST[username]','".date("Y-m-d")."', $_POST[rak])";
	$hasil = mysql_query($sql) or die("Gagal simpan hasil Stock Opname: ".mysql_error()." SQL: $sql -- tekan tombol BACK !");
	$idStockOpname = mysql_insert_id();
	$ctr = $_POST[ctr];

	echo "Stock Opname sudah disimpan di database, nota SO nomor: ".$idStockOpname." <br /><br /> Mulai menyimpan transaksi Stock Opname : <br /><br />";

	for ($i = 1; $i <= $ctr; $i++) {

		if ($_POST["selisih$i"] <> 0) { // simpan hanya yang ada selisihnya

			$sql = "INSERT INTO detail_stock_opname (idStockOpname,barcode,namaBarang,jmlTercatat,selisih) 
				VALUES ($idStockOpname,'".$_POST["barcode$i"]."','".$_POST["namaBarang$i"]."',".$_POST["jmlTercatat$i"].",
					".$_POST["selisih$i"].") ";
			$hasil = mysql_query($sql);

			//fixme: ubah jumlah barang - komprehensif
			//	# cari seluruh stok dari barang ybs di detail_beli
			//	# pilih yang paling awal
			//	# apply selisih di salah satunya
			//		# jika jadi minus = jadikan nol, lalu pilih record barang tsb yang berikutnya
			//	# sesuaikan jmlBarang di tabel barang

			////////////// update jumlah stok di tabel barang
			// StokSekarang = jmlTercatat + Selisih
			$StokSekarang = $_POST["jmlTercatat$i"]+$_POST["selisih$i"];
			$sql = "UPDATE barang SET jumBarang = '".$StokSekarang."' WHERE barcode = '".$_POST["barcode$i"]."'";        
			$hasil = mysql_query($sql);

			echo "
			Transaksi SO : Nama Barang: ".$_POST["namaBarang$i"].", Selisih: ".$_POST["selisih$i"]." - sudah disimpan<br />
			";
		}; // if ($_POST[selisih$i] !== 0)
	}; // for ($i = 1; $i <= $_POST[ctr]; $i++)


	echo "Selesai !";

	break;
		




  case "editbarang": // ========================================================================================================================
    $edit=mysql_query("SELECT * FROM barang WHERE barcode='$_GET[id]'");
    $data=mysql_fetch_array($edit);

    echo "<h2>Edit Barang</h2>
          <form method=POST action=./aksi.php?module=barang&act=update name='editbarang'>
          <input type=hidden name='idBarang' value='$data[idBarang]'>
          <table>
          <tr><td>Barcode</td><td> : <input type=text name='barcode' size=30 value='$data[barcode]'></td></tr>
          <tr><td>Nama Barang</td><td> : <input type=text name='namaBarang' size=30 value='$data[namaBarang]'></td></tr>
          <tr><td>Kategori Barang</td>
                <td> : <select name='kategori_barang'>";
                            while($kategori = mysql_fetch_array($ambilKategoriBarang)){
                                if($kategori[idKategoriBarang] == $data[idKategoriBarang]){
                                    echo "<option value='$kategori[idKategoriBarang]' selected>$kategori[namaKategoriBarang]</option>";
                                }
                                else{
                                    echo "<option value='$kategori[idKategoriBarang]'>$kategori[namaKategoriBarang]</option>";
                                }
                            }
            echo "</select></td></tr>
          <tr><td>Satuan Barang</td>
                <td> : <select name='satuan_barang'>";
                            while($satuan = mysql_fetch_array($ambilSatuanBarang)){
                                if($satuan[idSatuanBarang] == $data[idSatuanBarang]){
                                    echo "<option value='$satuan[idSatuanBarang]' selected>$satuan[namaSatuanBarang]</option>";
                                }
                                else{
                                    echo "<option value='$satuan[idSatuanBarang]'>$satuan[namaSatuanBarang]</option>";
                                }
                            }
            echo "</select></td></tr>
          <tr><td>Supplier</td>
                <td> : <select name='supplier'>";
                            while($supplier = mysql_fetch_array($ambilSupplier)){
                                if($supplier[idSupplier] == $data[idSupplier]){
                                    echo "<option value='$supplier[idSupplier]' selected>$supplier[namaSupplier]</option>";
                                }
                                else{
                                    echo "<option value='$supplier[idSupplier]'>$supplier[namaSupplier]</option>";
                                }
                            }
            echo "</select></td></tr>
          <tr><td>Rak</td>
                <td> : <select name='rak'>";
                            while($rak = mysql_fetch_array($ambilRak)){
                                if($rak[idRak] == $data[idRak]){
                                    echo "<option value='$rak[idRak]' selected>$rak[namaRak]</option>";
                                }
  	                              else{
                                    echo "<option value='$rak[idRak]'>$rak[namaRak]</option>";
				}
                            }
            echo "</select></td></tr>
          <tr><td>Harga Jual</td><td> : <input type=text name='hargaJual' size=20 value='$data[hargaJual]'></td></tr>
          <tr><td colspan=2>&nbsp;</td></tr>
          <tr><td colspan=2 align='right'><input type=submit value=Simpan>&nbsp;&nbsp;&nbsp;
                            <input type=button value=Batal onclick=self.history.back()></td></tr>

		<input type=hidden name='oldbarcode' value='$data[barcode]'>

          </table></form> <br /><br />";

	// tampilkan seluruh stok ybs yang masih ada di toko / belum laku terjual
	$sql = "SELECT t.tglTransaksiBeli,d.hargaBeli, d.jumBarang  
		FROM detail_beli AS d, transaksibeli AS t  
		WHERE d.barcode = '$data[barcode]' AND d.idTransaksiBeli = t.idTransaksiBeli AND d.isSold='N' ORDER BY d.idTransaksiBeli DESC";
	$hasil = mysql_query($sql);
	$jumlah = mysql_num_rows($hasil);
	while ($x = mysql_fetch_array($hasil)) {
		echo "Tgl.Beli : $x[tglTransaksiBeli], Harga Beli : Rp ".number_format($x[hargaBeli],0,',','.')." (jumlah: $x[jumBarang])<br />";
	}

	// jika stok nya sudah laku semua,
	// cetak 2 stok yang terakhir (sekedar untuk informasi harga)
	if ($jumlah < 1) {
		$sql = "SELECT d.idTransaksiBeli, d.hargaBeli, d.isSold, d.jumBarang FROM detail_beli AS d  
			WHERE d.barcode = '$data[barcode]' ORDER BY d.idTransaksiBeli DESC LIMIT 2";
		$hasil = mysql_query($sql);
		$jumlah = mysql_num_rows($hasil);
		while ($x = mysql_fetch_array($hasil)) {
			echo "ID: ".$x[idTransaksiBeli].", Harga Beli : Rp ".number_format($x[hargaBeli],0,',','.').", Status: ";
			if ($x[isSold]=='Y') {
				echo " Habis";
			} else {
				echo " Ada";
			}; 
			echo " (jumlah: $x[jumBarang]) <br />";
		}
		
	}
  

	break;



  case "cetakbarang1": // ========================================================================================================================



	// cari tahu jumlah rak yang ada di toko ini
	$cari=mysql_query("SELECT idRak FROM rak");	
	$jumlah_rak = mysql_num_rows($cari);

	// cari daftar workstation kasir yang ada
	$daftarKasir=mysql_query("SELECT idWorkstation,namaWorkstation FROM workstation");	


 	echo "
		<h2>Cetak Stock Barang</h2>
		<form method=GET action='modul/mod_barang.php'  onSubmit=\"popupform(this, 'CETAK_STOCK_BARANG')\">

	Disini Anda bisa mencetak daftar stok Barang yang masih ada / jumlahnya tidak nol. 
	Biasanya digunakan pada saat Tutup Buku, untuk secara acak memeriksa stok barang yang sebenarnya.

	<br /><br />

	<table>
	<tr>	
		<td>(d) Dari Rak</td>
		<td> : <input type=text name=darirak value=1 accesskey='d' size=4></td>
	</tr>	
	<tr>	
		<td>Sampai Rak</td>
		<td> : <input type=text name=sampairak value=$jumlah_rak size=4></td>
	</tr>	
		<td><br /> (p) Cetak ke </td>
                <td><br /> : <select name='printer' accesskey='p'>
			<option value='0'>-- Cetak Ke Browser --</option>";
                            while($printer = mysql_fetch_array($daftarKasir)){
                                    echo "<option value='$printer[idWorkstation]'>$printer[namaWorkstation]</option>";
                            }
            echo "</select></td></tr>


		<tr><td colspan=2><br /><input type=submit accesskey='b' value='(b) Cetak Stock Barang'></td></tr>
					<input type=hidden name=act value=cetakbarang2>
		</table></form>";

     break;


  case "cetakbarang2":


	echo "
	<form method='post'>
		<input type=button value='Tutup Window Ini' onclick='window.close()'>
	</form>
	";

	// ambil data barang yang akan dicetak
	$sql = "SELECT idRak, namaBarang, hargaJual, jumBarang
		FROM barang WHERE jumBarang <> 0 AND idRak BETWEEN ".$_GET['darirak']." AND ".$_GET['sampairak']." 
		ORDER BY idRak,namaBarang ASC";
	$daftarBarang = mysql_query($sql);	
	$jumlahBarang = mysql_num_rows($daftarBarang);
	//echo $sql;

	// mulai mencetak
	if ($_GET[printer] == '0') {

		$rakSebelum  = 0;
		$rakSekarang = 0;
		$gantiBaris  = 0;
		for ($i = 1; $i <= $jumlahBarang; $i++) {

			// ambil 1 record
			$x = mysql_fetch_array($daftarBarang);
			$rakSekarang = $x[idRak];

			if ($rakSebelum <> $rakSekarang) {
				// cetak header
				$hasil 	= mysql_query("SELECT namaRak FROM rak WHERE idRak = $x[idRak]");
				$r	= mysql_fetch_array($hasil);

				echo "
					</table>

					<h2>
						Rak #$x[idRak] : $r[namaRak]
					</h2>

					<table border=1>	
				
					<tr>
						<td><center><b>	Nama Barang	</b></center>
						</td>
						<td><center><b>	Harga	</b></center>
						</td>
						<td><center><b>	Jml	</b></center>
						</td>
						<td><center><b>	Nama Barang	</b></center>
						</td>
						<td><center><b>	Harga	</b></center>
						</td>
						<td><center><b>	Jml	</b></center>
						</td>
					</tr>
					<tr>	
				";
				$rakSebelum = $rakSekarang;
			}; // if ($rakSebelum <> $rakSekarang)

			if ($gantiBaris > 1) {
				// ganti baris
				echo "</tr><tr>";
				$gantiBaris = 0;
			}; // if ($gantiBaris > 1)

			// cetak data barang
			echo "
			<td>		$x[namaBarang]
			</td>
			<td><center>	$x[hargaJual]	</center>
			</td>
			<td><center>	$x[jumBarang]	</center>
			</td>
			";

			$gantiBaris++;
		}; // for ($i = 1; $i <= $jumlahBarang; $i++)


	} else {

		// ambil daftar printer_command untuk idWorkstation ybs
		$hasil 	= mysql_query("SELECT printer_commands FROM workstation WHERE idWorkstation = $_GET[printer]");
		$r	= mysql_fetch_array($hasil);
		$perintahPrinter = $r[printer_commands];

		$rakSebelum  = 0;
		$rakSekarang = 0;
		$struk 	     = "";
		for ($i = 1; $i <= $jumlahBarang; $i++) {

			// ambil 1 record
			$x = mysql_fetch_array($daftarBarang);
			$rakSekarang = $x[idRak];

			// kalau ganti rak, cetak dulu $struk
			if ($rakSebelum <> $rakSekarang) {
				// kirim ke printer
				$perintah = "echo \"$struk\" |lpr $perintahPrinter -l";
				exec($perintah, $output);
				//echo $struk;

				$struk = "";
				$rakSebelum = $rakSekarang;

				// cetak header
				$hasil 	= mysql_query("SELECT namaRak FROM rak WHERE idRak = $x[idRak]");
				$r	= mysql_fetch_array($hasil);
				$struk .= "\n\nRak #$x[idRak] : $r[namaRak] \n ===============";
			}; // if ($rakSebelum <> $rakSekarang)

			// cetak data barang
			$struk .= "\n $x[namaBarang] \n Harga: $x[hargaJual], Jumlah: $x[jumBarang]";

		}; // for ($i = 1; $i <= $jumlahBarang; $i++)

		// cetak baris terakhir
		$perintah = "echo \"$struk\" |lpr $perintahPrinter -l";
		exec($perintah, $output);
		//echo $struk;


	}; // if ($_GET[printer] == '0')


     break;




  case "ApproveFastSO1": // ========================================================================================================================



	// cari SO yang belum di approve
	$sql 	= "SELECT DISTINCT tanggalSO FROM fast_stock_opname WHERE approved=0 ORDER BY tanggalSO ASC";
	$hasil	= mysql_query($sql);	

 	echo "
		<h2>Approve Fast Stock Opname</h2>
		<form method=GET action='media.php'>

	<br /><br />

	<table>
	<tr>	
		<td><br /> (t) Pilih Tanggal SO </td>
                <td><br /> : <select name='tanggalSO' accesskey='t'>";

	while($x = mysql_fetch_array($hasil)){
		echo "<option value='$x[tanggalSO]'>$x[tanggalSO]</option>";
	}
	
	echo "</select></td>
	</tr>

	<tr>
		<td colspan=2><br /><input type=submit accesskey='s' value='(s) Submit'></td>
	</tr>

		<input type=hidden name=module value=barang>	
		<input type=hidden name=act value=ApproveFastSO2>
		</table></form>";

     break;



  case "ApproveFastSO2":  // ----------------------------------------------------------------------------


	// cari SO yang belum di approve di tanggalSO
	$sql 	= "SELECT * FROM fast_stock_opname WHERE tanggalSO='$_GET[tanggalSO]' AND approved=0 ORDER BY idRak,jmlTercatat DESC";
	$hasil1	= mysql_query($sql);	

 	echo "
		<h2>Approve Fast Stock Opname</h2>
		<form method=POST action='?module=barang&act=ApproveFastSO3'>

	<br /><br />

	<table border=1>
	<tr>	
		<td><center>
			Rak
		</center></td>
		<td>Barcode
		</td>
		<td>Nama Barang
		</td>
		<td><center>
			Jumlah<br />Tercatat
		</center></td>
		<td><center>
			Selisih
		</center></td>
		<td><center>
			Approve
		</center></td>
		<td><center>#</center>
		</td>
		<td><center>
			Salah<br />Rak
		</center></td>
		<td><center>
			Hapus<br />Barang
		</center></td>
	</tr>
	";

	$x = mysql_fetch_array($hasil1);
	$rakSekarang 	= $x[idRak];
	$rakSebelum	= $x[idRak];
	$ctr 		= 1;
	$ctrRec		= 1;
	$jumlahRecord	= mysql_num_rows($hasil1);

	do {

		$rakSekarang = $x[idRak];

		if (strlen($x[namaBarang]) > 0) {
		echo "
			<tr>
			<td><center>
				$x[idRak]
			</center></td>
			<td>$x[barcode] 	<input type=hidden name=barcode$ctr value=$x[barcode]>
			</td>
			<td>$x[namaBarang]
			</td>
			<td><center>
				$x[jmlTercatat]
			</center></td>
			<td><center>
				$x[selisih]	<input type=hidden name=selisih$ctr value=$x[selisih]>
			</center></td>
			<td><center>
				<input type=checkbox name=appr$ctr checked=yes>
			</center></td>
			<td><center>#</center>
			</td>
			<td><center>
				<input type=checkbox name=salahrak$ctr>
			</center></td>
			<td><center>
				<input type=checkbox name=hapus$ctr>
			</center></td>
			</tr>
		";
		}; // if (strlen($x[namaBarang]) > 0) {

		if (($rakSebelum <> $rakSekarang) || ($ctrRec == $jumlahRecord)) {
			// cari barang di rak yang sama, namun tidak masuk di dalam SO = sebetulnya berada di rak yang lain / sudah tidak ada lagi
			$sql	= "SELECT b.* 
					FROM barang AS b LEFT JOIN fast_stock_opname AS f ON b.barcode = f.barcode 
					WHERE b.idRak=$rakSekarang AND f.idRak IS NULL ORDER BY b.namaBarang ASC";
			//echo $sql;
			$hasil2	= mysql_query($sql);	
			while($z = mysql_fetch_array($hasil2)){
				$ctr++;
				echo "
					<tr>
					<td><center>
						$z[idRak]
					</center></td>
					<td>$z[barcode] <input type=hidden name=barcode$ctr value=$z[barcode]>
					</td>
					<td>$z[namaBarang]
					</td>
					<td><center>
						$z[jumBarang]
					</center></td>
					<td><center>
					</center></td>
					<td><center>
					</center></td>
					<td><center>#</center>
					</td>
					<td><center>
						<input type=checkbox name=salahrak$ctr checked=yes>
					</center></td>
					<td><center>
						<input type=checkbox name=hapus$ctr>
					</center></td>
					</tr>
				";

			}; // while($z = mysql_fetch_array($hasil)){
		}; // if ($rakSebelum <> $rakSekarang) {

		$rakSebelum = $rakSekarang;
		$ctr++;
		$ctrRec++; // tidak menghitung record yang didapat dari barang (ketika mencari barang yg salah rak)

	} while ($x = mysql_fetch_array($hasil1));

	echo "</table>	

		<input type=submit accesskey='s' value='(s) Submit'>	
		<input type=hidden name=ctr value=$ctr>

		</form>";

	break;


  case "ApproveFastSO3":  // ----------------------------------------------------------------------------


 	echo "
		<h2>Proses Fast Stock Opname</h2>
	<br /><br />
	";

	for ($i = 1; $i <= $_POST[ctr]; $i++) {

		// cek barang dihapus
		if ($_POST["hapus$i"] == 'on') {
			// ....still having thoughts about it, for now just ignore.

		// cek barang yang salah rak (tercatat di barang.idRak di rak ybs - tapi, tidak ketemu di rak tsb pada saat SO)
		} elseif ($_POST["salahrak$i"] == 'on') {
			// ganti barang.idRak ybs menjadi 999999
			$sql = "UPDATE barang SET idRak=999999 WHERE barcode='".$_POST["barcode$i"]."'";
			$hasil1 = mysql_query($sql);
			echo "Salah Rak : ".$_POST["barcode$i"].", sudah diganti raknya menjadi 999999 <br />";

		// cek barang yang di approve SO nya
		} elseif ($_POST["appr$i"] == 'on') {
			// cari barang.jumBarang ybs
			$sql 	= "SELECT jumBarang FROM barang WHERE barcode='".$_POST["barcode$i"]."'";
			$hasil1 = mysql_query($sql);
			$x	= mysql_fetch_array($hasil1);

			// hitung jumlah barang yang seharusnya
			$jumBarang 	= $x[jumBarang] + $_POST["selisih$i"];
			
			// update barang.jumBarang untuk barcode ybs
			$sql = "UPDATE barang SET jumBarang=$jumBarang WHERE barcode='".$_POST["barcode$i"]."'";
			$hasil1 = mysql_query($sql);

			// ganti fast_stock_opname.approved menjadi 1 / true
			$sql = "UPDATE fast_stock_opname SET approved=1 WHERE barcode='".$_POST["barcode$i"]."'";
			$hasil1 = mysql_query($sql);
			echo "Approved : ".$_POST["barcode$i"].", stok tercatat: $x[jumBarang], selisih: ".$_POST["selisih$i"].", total: $jumBarang <br />";
			//var_dump($_POST);	
		};
	}; // for ($i = 0; $i <= $_POST[ctr]; $i++) {

	break;



}


/* CHANGELOG -----------------------------------------------------------

 1.5.5 / 2013-01-25 : Harry Sufehmi 	: bugfix: https://github.com/sufehmi/AhadPOS/issues/1 , 
						terimakasih http://www.facebook.com/civo.pras untuk laporannya.

 1.5.0 / 2012-11-25 : Harry Sufehmi 	: optimisasi : query yang menampilkan seluruh data barang.
						Credit : Insan Fajar
 1.5.0 / 2012-09-09 : Harry Sufehmi	: bugfix: form inputSO3 gagal jika ada > 250 item di suatu rak. 
					Ternyata... default setting max_input_vars = 1000, sedangkan setiap item menyimpan 4 jenis informasi = lebih besar dari batas max_input_vars
					Solusi: set max_input_vars di php.ini menjadi 20000 atau lebih

 1.2.5 / 2012-03-02 : Harry Sufehmi 	: bugfix: editbarang kini menemukan item berdasarkan barcode - bukan idBarang
 1.0.3 / 2011-10-21 : Harry Sufehmi	: Kategori Barang & Satuan Barang kini muncul pada tabel daftar barang (tadinya kosong)
					Juga pada sub-modul : 
						# caribarang2
						# cetaklabel2

					Thanks kepada Alexander (mr.s4scha@gmail.com) untuk laporannya.

 1.0.2 / 2011-07-14 : Harry Sufehmi	: menu "editbarang", bisa mendeteksi perubahan barcode.
					(sehingga di aksi.php bisa update juga field barcode di table-table lainnya)

 1.0.1 / 2010-06-03 : Harry Sufehmi	: various enhancements, bugfixes
					# fitur Stock Opname
					# Cari Barang : bisa per Rak
	2010-12-16  : Harry Sufehmi	: Cetak Stock Barang

	2011-01-07  : Harry Sufehmi	: Input Fast Stock Opname & Approve Fast Stock Opname


 0.6.5		    : Gregorius Arief	: initial release

------------------------------------------------------------------------ */

?>
