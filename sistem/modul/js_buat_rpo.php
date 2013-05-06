<?php
/* js_buat_rpo.php ----------------------------------------
   	version: 1.01

	Part of AhadPOS : http://ahadpos.com
	License: GPL v2
			http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
			http://vlsm.org/etc/gpl-unofficial.id.html

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License v2 (links provided above) for more details.
----------------------------------------------------------------*/


include "../../config/config.php";
include "function.php";


session_start();
if (empty($_SESSION['namauser'])){
  echo "<link href='../config/adminstyle.css' rel='stylesheet' type='text/css'>
 <center>Untuk mengakses modul, Anda harus login <br>";
  echo "<a href=index.php><b>LOGIN</b></a></center>";
}
else{

	if(!isset($_SESSION['idCustomer'])){
		findSupplier($_POST['supplierid']);
		$_SESSION['idCustomer'] = $_SESSION['idSupplier'];
	};

	if(!isset($_SESSION['periode'])){
		$_SESSION['periode'] 	= $_POST['periode'];
	};
	if(!isset($_SESSION['range'])){
		$_SESSION['range'] 	= $_POST['range'];
	};
	if(!isset($_SESSION['persediaan'])){
		$_SESSION['persediaan'] = $_POST['persediaan'];
	};

	//var_dump($_SESSION);


	//HS javascript untuk menampilkan popup
?>	
	<head>


	<SCRIPT TYPE="text/javascript">
	<!--
	function popupform(myform, windowname)
	{
		if (! window.focus)return true;
		window.open('', windowname, 'height=400,width=700,scrollbars=yes');
		myform.target=windowname;
		return true;
	}

	function number_format(a, b, c, d) {
	// credit: http://www.krisnanda.web.id/2009/06/09/javascript-number-format/
	
		 a = Math.round(a * Math.pow(10, b)) / Math.pow(10, b);

		 e = a + '';
		 f = e.split('.');
		 if (!f[0]) {  f[0] = '0';}
		 if (!f[1]) {  f[1] = '';  }

		 if (f[1].length < b) {
			  g = f[1];
			  for (i=f[1].length + 1; i <= b; i++) {
				   g += '0';
			  }
			  f[1] = g;
		 }

		 if(d != '' && f[0].length > 3) {
			  h = f[0];
			  f[0] = '';
			  for(j = 3; j < h.length; j+=3) {
				   i = h.slice(h.length - j, h.length - j + 3);
				   f[0] = d + i +  f[0] + '';
			  }
			  j = h.substr(0, (h.length % 3 == 0) ? 3 : (h.length % 3));
			  f[0] = j + f[0];
		 }

		 c = (b <= 0) ? '' : c;
		return f[0] + c + f[1];
	}


	function RecalcTotal(tot_pembelian) {
		var totalBeli = 0;
		var Kembali = 0;
		var uangDibayar 	= parseInt(document.getElementById("uangDibayar").value);
		var surcharge	 	= parseInt(document.getElementById("surcharge").value);

		totalSurcharge		= ((tot_pembelian / 100) * surcharge);
		totalBeli 		= tot_pembelian + totalSurcharge;
		Kembali 		= uangDibayar - totalBeli;

		document.getElementById("uangKembali").value = Kembali;
		document.getElementById("kembalian").innerHTML = '<span style="font-size:48pt">' + number_format(Kembali,0,',','.') + '</span>';

		document.getElementById("TotalSurcharge").value = number_format(totalSurcharge,0,',','.');
		document.getElementById("tot_pembelian").innerHTML = '<span style="font-size:48pt">' + number_format(totalBeli,0,',','.') + '</span>';
	}

	-->
	</SCRIPT>


	
 	<link href='../../config/adminstyle.css' rel='stylesheet' type='text/css' />

	</head>
<?php

if ($_GET[doit]=='hapus') {
	$sql = "DELETE FROM tmp_detail_jual WHERE uid = $_GET[uid]";
	$hasil = mysql_query($sql);
}



switch($_GET[act]){ // ============================================================================================================

    case "caricustomer": 
    case "mulairpo": // ========================================================================================================
        
	// display header	
	echo "<h2>Buat RPO</h2>";
	echo "<div style='float:right' id='tot_pembelian'><span style='font-size:48pt'>".number_format($_SESSION[tot_pembelian],0,',','.')."
		</span></div>";


		echo "
		Supplier : ".$_SESSION['namaSupplier']."<br />Untuk Persediaan : ".$_SESSION['persediaan']." hari";

        	echo "<h3>Barang yang dipesan</h3>";

		echo "
                    <table>
                        <tr>
                            <td>

				<form method=POST action='js_buat_rpo.php?act=mulairpo&action=tambah'>
				(b) Barcode</td><td>: <input type=text name='barcode' accesskey='b' id='barcode'></td>

				<td>(q) Qty</td><td>: <input type=text name='jumBarang' value='0' size=5 accesskey='q'></td>
				<td align=right><input type=submit name='btnTambah' value='(t) Tambah' accesskey='t'></td>
			</form>";
			

	echo "
			<td>
			<FORM METHOD=POST ACTION=\"js_cari_barang.php?caller=js_buat_rpo\" onSubmit=\"popupform(this, 'cari1')\">
			<input type=text name='namabarang' accesskey='c'>
			<input type=submit name='btnCari' id='btnCari' value='(c) Cari Barang'>
			</form>
			</td>

                        </tr>
                    </table>
                </form>

		<script>
			var dropBox=document.getElementById(\"barcode\" );
			if (dropBox!=null ) dropBox.focus();
		</script>

            ";

        if($_GET['action']=='tambah'){

		if($_GET['barcode']) {$_POST['barcode']=$_GET['barcode'];};
		
		$sudahAda = cekBarangTempRPO($_SESSION['idCustomer'],$_POST['barcode']);
		if ($sudahAda != 0) {
			tambahBarangRPOAda($_SESSION['idCustomer'],$_POST['barcode'],$_POST['jumBarang']);
		} else {
			tambahBarangRPO($_POST['barcode'],$_POST['jumBarang'],$_SESSION['range'],$_SESSION['periode'],$_SESSION['persediaan']);
		}
        }

	$sql 	= "SELECT * FROM tmp_detail_jual tdj, barang b
			WHERE tdj.barcode = b.barcode AND tdj.idCustomer = '".$_SESSION['idCustomer']."' 
				AND tdj.username = '$_SESSION[uname]'";
        $query 	= mysql_query($sql);
        $r 	= mysql_fetch_row($query);
        echo "<hr/>";

        if ($r) {
            echo "<table class=tableku width=600>
                    <tr><th>Barcode</th><th>Nama Barang</th>
			<th>Saran Order</th>
			<th>Stok Saat Ini</th>
                        <th>Jumlah Pesanan</th>
                        <th>Harga</th>
                        <th>Total</th>
			<th>Aksi</th></tr>";
                $no=1;
                $tot_pembelian=0;

                $query2 = mysql_query("SELECT tdj.uid, tdj.barcode, tdj.idBarang, tdj.hargaBeli, b.namaBarang, 
						tdj.jumBarang, tdj.hargaJual, tdj.tglTransaksi
                                        FROM tmp_detail_jual tdj, barang b
                                        WHERE tdj.barcode = b.barcode AND tdj.idCustomer = '$_SESSION[idCustomer]' 
						AND tdj.username = '$_SESSION[uname]' ORDER BY tglTransaksi DESC");
		
                while ($data=mysql_fetch_array($query2)){
                    //untuk mewarnai tabel menjadi selang-seling
                    if(($no % 2) == 0){
                        $warna = "#EAF0F7";
                    }
                    else{
                        $warna = "#FFFFFF";
                    }


		$total = $data[hargaJual] * $data[jumBarang];

                    echo "<tr bgcolor=$warna>";
                    echo "<td>$data[barcode]</td><td>$data[namaBarang]</td>
                        <td align=right>$data[idBarang]</td>
                        <td align=right>$data[hargaBeli]</td>
                        <td align=right>$data[jumBarang]</td>
                        <td align=right>$data[hargaJual]</td>
			<td align=right>".number_format($total,0,',','.')."</td>
			<td align=right> <a href='js_buat_rpo.php?act=caricustomer&doit=hapus&uid=$data[uid]'>Hapus</a></td>
                        </tr>";
                    $tot_pembelian += $total;
                    $no++;
                }

                echo "</table>";
                $pmbyrn = mysql_query("SELECT * from pembayaran");
                echo "

			<form method=POST action='../aksi.php?module=buat_rpo&act=input'>
                        <input type=hidden name='tot_pembayaran' value='$tot_pembelian'>

			<div id='kembalian' style='float:right'></div>

			<table class=tableku width=600>
                        <tr><td width=65% align=right>Total Pembelian</td><td align=right><div id='TotalBeli'>".number_format($tot_pembelian,0,',','.')."</div></td></tr>

		<script>
			document.getElementById('tot_pembelian').innerHTML = '<span style=\"font-size:48pt\">".number_format($tot_pembelian,0,',','.')."</span>';
		</script>

		";


		$_SESSION['tot_pembelian'] = $tot_pembelian;

		echo "

                        <tr><td> [<a href='../aksi.php?module=penjualan_barang&act=batal'> BATAL </a>]</td><td align=right>&nbsp;&nbsp;&nbsp;<input type=submit value='Simpan' onclick='this.disabled=true;'></td></tr>
                        </table></form>";


        }
        else{
            echo "Belum ada barang yang dipilih<br />
            <a href='../aksi.php?module=penjualan_barang&act=batal'>BATAL</a>
		";
        }
        
	echo "		<div style='float:right'><img src='../../image/logo-ahadpos-1.gif'></div>";

        break;
}

} // if (empty($_SESSION[namauser])



/* CHANGELOG -----------------------------------------------------------

 1.6.0 / 2013-05-06 : Harry Sufehmi	: initial release
------------------------------------------------------------------------ */

?>
