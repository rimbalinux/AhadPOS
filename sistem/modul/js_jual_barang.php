<?php
/* js_jual_barang.php ----------------------------------------
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
if (empty($_SESSION[namauser]) AND empty($_SESSION[passuser])){
  echo "<link href='../config/adminstyle.css' rel='stylesheet' type='text/css'>
 <center>Untuk mengakses modul, Anda harus login <br>";
  echo "<a href=index.php><b>LOGIN</b></a></center>";
}
else{

        if(!isset($_SESSION[idCustomer])){
           findCustomer($_POST[idCustomer]);
        }


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


	//fixme: hargaBeli TIDAK tersimpan di detail_jual !!!



switch($_GET[act]){ // ============================================================================================================


    case "caricustomer": // ========================================================================================================
        
        echo "<h2>Penjualan Barang</h2>

		<div style='float:right' id='tot_pembelian'><span style='font-size:48pt'>".number_format($_SESSION[tot_pembelian],0,',','.')."</span></div>

		Penjualan Barang untuk customer : $_SESSION[namaCustomer]
                
		";

        echo "<h3>Barang yang dijual</h3>

                    <table>
                        <tr>
                            <td colspan=3>

				<form method=POST action='js_jual_barang.php?act=caricustomer&action=tambah'>
				(b) Barcode</td><td>: <input type=text name='barcode' accesskey='b' id='barcode'></td>
		";

	// ----- TERLALU LAMBAT ! ----- jangan gunakan dropbox terlampir untuk memilih barcode
	// ambil daftar barang
	//$sql="SELECT namaBarang,barcode,hargaJual   
	//	FROM barang FORCE INDEX (barcode) ORDER BY barcode ASC";
	//$namaBarang=mysql_query($sql);
	//while($brg = mysql_fetch_array($namaBarang)){
	//	echo "<option value='$brg[barcode]'>$brg[barcode] - $brg[namaBarang] - Rp ".number_format($brg[hargaJual],0,',','.')."</option>\n";
	//}	

	echo "
                            <td>(q) Qty</td><td>: <input type=text name='jumBarang' value='1' size=5 accesskey='q'></td>
                            <td align=right><input type=submit name='btnTambah' value='(t) Tambah' accesskey='t'></td>
			    </form>

			<td>
			<FORM METHOD=POST ACTION=\"js_cari_barang.php?caller=js_jual_barang\" onSubmit=\"popupform(this, 'cari1')\">
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

        if($_GET[action]=='tambah'){

	if($_GET[barcode]) {$_POST[barcode]=$_GET[barcode];};
            $trueJual = cekBarangTempJual($_SESSION[idCustomer],$_POST[barcode]);
//            echo "$trueJual";
            if($trueJual != 0){
                
                tambahBarangJualAda($_SESSION[idCustomer],$_POST[barcode],$_POST[jumBarang]);
            }
            else{
                
              tambahBarangJual($_POST[barcode],$_POST[jumBarang]);
            }
        }
	$sql = "SELECT *
                                FROM tmp_detail_jual tdj, barang b
                                WHERE tdj.barcode = b.barcode AND tdj.idCustomer = '$_SESSION[idCustomer]' AND tdj.username = '$_SESSION[uname]'";
	//echo $sql;
        $query = mysql_query($sql);
        $r = mysql_fetch_row($query);
        echo "<hr/>";
        if($r){
            //echo "Ada $r[0] data";
            echo "<table class=tableku width=600>
                    <tr><th>Barcode</th><th>Nama Barang</th>
                        <th>Jumlah</th>
                        <th>Harga</th>
                        <th>Total</th>
			<th>Aksi</th></tr>";
                $no=1;
                $tot_pembelian=0;

                $query2 = mysql_query("SELECT tdj.uid, tdj.barcode, b.namaBarang, tdj.jumBarang, tdj.hargaJual, tdj.tglTransaksi
                                        FROM tmp_detail_jual tdj, barang b
                                        WHERE tdj.barcode = b.barcode AND tdj.idCustomer = '$_SESSION[idCustomer]' 
						AND tdj.username = '$_SESSION[uname]' ORDER BY tglTransaksi DESC");
		
                while ($data=mysql_fetch_array($query2)){
                    $total = $data[hargaJual] * $data[jumBarang];
                    //untuk mewarnai tabel menjadi selang-seling
                    if(($no % 2) == 0){
                        $warna = "#EAF0F7";
                    }
                    else{
                        $warna = "#FFFFFF";
                    }
                    echo "<tr bgcolor=$warna>";
                    echo "<td>$data[barcode]</td><td>$data[namaBarang]</td>
                        <td align=right>$data[jumBarang]</td>
                        <td align=right>$data[hargaJual]</td>
			<td align=right>".number_format($total,0,',','.')."</td>
			<td align=right> <a href='js_jual_barang.php?act=caricustomer&doit=hapus&uid=$data[uid]'>Hapus</a></td>
                        </tr>";
                    $tot_pembelian += $total;
                    $no++;
                }

                echo "</table>";
                $pmbyrn = mysql_query("SELECT * from pembayaran");
                echo "

			<form method=POST action='../aksi.php?module=penjualan_barang&act=input'>
                        <input type=hidden name='tot_pembayaran' value='$tot_pembelian'>

			<div id='kembalian' style='float:right'></div>

			<table class=tableku width=600>
                        <tr><td width=65% align=right>Total Pembelian</td><td align=right><div id='TotalBeli'>".number_format($tot_pembelian,0,',','.')."</div></td></tr>

		<script>
			document.getElementById('tot_pembelian').innerHTML = '<span style=\"font-size:48pt\">".number_format($tot_pembelian,0,',','.')."</span>';
		</script>

		";

		$_SESSION[tot_pembelian] = $tot_pembelian;

		echo "
                        <tr><td width=65% align=right>(a) Tipe Pembayaran</td>
                            <td align=right><select name='tipePembayaran' accesskey='a' tabindex=1>
                                        <option value='0'>-Tipe Pembayaran-</option>";

		while($pembayaran = mysql_fetch_array($pmbyrn)){
			if ($pembayaran[tipePembayaran]=='CASH') {
				echo "<option value='$pembayaran[idTipePembayaran]' selected>$pembayaran[tipePembayaran]</option>";
			} else {
				echo "<option value='$pembayaran[idTipePembayaran]'>$pembayaran[tipePembayaran]</option>";
			}
		}

                        echo "</select></td></tr>
                        <tr><td width=65% align=right>Surcharge</td><td align=right><input type=text name='surcharge' id='surcharge' value=0 size=2 tabindex=2> % (Rp <input type=text name='TotalSurcharge' id='TotalSurcharge' value=0 size=6  tabindex=100 readonly>)</td></tr>
                        <tr><td width=65% align=right>Uang Dibayar</td><td align=right><input type=text name='uangDibayar' id='uangDibayar' value=0 onBlur='RecalcTotal($tot_pembelian)'  tabindex=3></td></tr>
                        <tr><td width=65% align=right>Kembali</td><td align=right><input type=text name='uangKembali' id='uangKembali' value=0></td></tr>
                        <tr><td colspan=2>&nbsp;</td></tr>


                        <tr><td> [<a href='../aksi.php?module=penjualan_barang&act=batal'> BATAL </a>]</td><td align=right>&nbsp;&nbsp;&nbsp;<input type=submit value='Simpan' onclick='this.disabled=true;'></td></tr>
                        </table></form>";


        }
        else{
            echo "Belum ada barang yang dibeli<br />
            <a href='../aksi.php?module=penjualan_barang&act=batal'>BATAL</a>
		";
        }
        
	echo "		<div style='float:right'><img src='../../image/logo-ahadpos-1.gif'></div>";

        break;
}

} // if (empty($_SESSION[namauser])



/* CHANGELOG -----------------------------------------------------------

 1.0.1 / 2010-06-03 : Harry Sufehmi		: perhitungan Surcharge dibetulkan
 0.9.2 / 2010-03-03 : Harry Sufehmi 		: initial release
------------------------------------------------------------------------ */

?>
