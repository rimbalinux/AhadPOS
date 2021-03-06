<?php
/* mod_beli_barang.php ----------------------------------------
  version: 1.01

  Part of AhadPOS : http://ahadpos.com
  License: GPL v2
  http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
  http://vlsm.org/etc/gpl-unofficial.id.html

  This program is distributed in the hope that it will be useful,
  but WITHOUT ANY WARRANTY; without even the implied warranty of
  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
  GNU General Public License v2 (links provided above) for more details.
  ---------------------------------------------------------------- */


check_user_access(basename($_SERVER['SCRIPT_NAME']));
session_start();

//HS javascript untuk menampilkan dialog input "tambah barang baru"
?>	<script type="text/javascript">
	$(document).ready(function()
	{
		$('#layer1').Draggable(
				{
					zIndex: 60,
					ghosting: false,
					opacity: 0.7,
					handle: '#layer1_handle'
				}
		);
		$('#layer1_form').ajaxForm({
			target: '#frmTambahBarang',
			success: function()
			{
				$("#layer1").hide();
			}
		});
		$("#layer1").hide();

		$('#tambahbarang').click(function()
		{
			$("#layer1").show();
		});

		$('#close').click(function()
		{
			$("#layer1").hide();
		});
	});

	function popupform(myform, windowname)
	{
		if (!window.focus)
			return true;
		window.open('', windowname, 'type=fullWindow,fullscreen,scrollbars=yes');
		myform.target = windowname;
		return true;
	}

</script>

<style type="text/css">

	#layer1 
	{
		position: absolute;
		left:200px;
		top:100px;
		width:450px;
		background-color:#f0f5FF;
		border: 1px solid #000;
		z-index: 50;
	}
	#layer1_handle 
	{
		background-color:#5588bb;
		padding:2px;
		text-align:center;
		font-weight:bold;
		color: #FFFFFF;
		vertical-align:middle;
	}
	#layer1_content 
	{
		padding:5px;
	}
	#close
	{
		float:right;
		text-decoration:none;
		color:#FFFFFF;
	}
</style>

<?php
switch ($_GET[act]) { // ---------------------------------------------------------------------------------------------------------------------------
	// Tampil Satuan Barang
	default:
		echo "<h2>Pembelian Barang</h2>
		<table>
		<tr>
                    <td>
			<form method=POST action='?module=pembelian_barang&act=pembelianbarang'>
			<input type=submit value='(b) Pembelian Barang' accesskey='b'>
			</form>
                    </td>
                    <td>
			<form method=POST action='?module=pembelian_barang&act=laporanpembeliantanggal'>
			<input type=submit value='(l) Laporan Pembelian' accesskey='l'><br /><center>(per Tanggal)</center>
			</form>
                    </td>
                    <td>
			<form method=POST action='?module=pembelian_barang&act=laporanpembelian'>
			<input type=submit value='(s) Laporan Pembelian' accesskey='s'><br /><center>(per Supplier)</center>
			</form>
                    </td>
                    <td>
			<form method=POST action='?module=pembelian_barang&act=pemesananbarang'>
			<input type=submit value='(p) Pemesanan Barang' accesskey='p'>
			</form>
                    </td>
	
		</tr>


		<tr>
                    <td>
			<form method=POST action='?module=pembelian_barang&act=returpembelian'>
			<input type=submit value='(r) Retur Pembelian' accesskey='r'>
			</form>
                    </td>
                    <td>
			<form method=POST action='?module=pembelian_barang&act=cetakretur'>
			<input type=submit value='(c) Cetak Nota Retur' accesskey='c'>
			</form>
                    </td>
		</tr>



		<tr>
                    <td>
			<form method=POST action='?module=pembelian_barang&act=inputeprocurement1'>
			<input type=submit value='Input Beli Otomatis'>
			</form>
                    </td>
                    <td>
		</tr>

		<tr>
                    <td>
			<form method=POST action='?module=pembelian_barang&act=buatrpo1'>
			<input type=submit value='(o) Buat RPO' accesskey='o'>
			</form>
                    </td>
                    <td>
			<form method=POST action='?module=pembelian_barang&act=rposup1'>
			<input type=submit value='(o) Buat RPO Per Supplier' accesskey='o'>
			</form>
                    </td>
		</tr>

		</table>";
		break;




	case "cetakretur"; // =========================================================================================================================

		echo "<h2>Cetak Nota Retur</h2>

            <form method=POST action='?module=pembelian_barang&act=cetakretur&action=lihatlaporan'>
                <br/>Periode Laporan : Bulan :
                <select name=bulanLaporan>";
		$dataBulan = getMonth("detail_retur_beli", "tglRetur");
		while ($bulan = mysql_fetch_array($dataBulan)) {
			echo "<option value=$bulan[bulan]>" . getBulanku($bulan[bulan]) . "</option>";
		}
		echo "</select>, Tahun :
            <select name=tahunLaporan>";
		$dataTahun = getYear();
		while ($tahun = mysql_fetch_array($dataTahun)) {
			echo "<option value=$tahun[tahun]>$tahun[tahun]</option>";
		}
		echo "</select>
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type=submit value=Lihat>
            </form>
            ";


		if ($_GET[action] == 'lihatlaporan') {

			if ($_POST[bulanLaporan] < 10) {
				$periode = $_POST[tahunLaporan] . "-0" . $_POST[bulanLaporan];
			} else {
				$periode = $_POST[tahunLaporan] . "-" . $_POST[bulanLaporan];
			}

			echo "
			<table><tr><th>Nota<br />Beli</th><th>Tanggal</th><th>Supplier</th><th>Total</th><th>User</th><th>AKSI</th></tr>";
			/*
			$sql = "SELECT d.idTransaksiBeli, d.tglRetur, d.nominal, d.username, s.namaSupplier, d.idSupplier  
			FROM detail_retur_beli AS d, supplier AS s 
			WHERE s.idSupplier = d.idSupplier AND tglRetur LIKE '$periode%' GROUP BY tglRetur ORDER BY tglRetur DESC";
			*/
			// edited by abufathir; Jika ada retur barang untuk supplier yang sama di tanggal yang sama
			$sql = "SELECT d.idTransaksiBeli, d.tglRetur, d.nominal, d.username, s.namaSupplier, d.idSupplier  
					FROM detail_retur_beli d
					JOIN supplier s ON s.idSupplier = d.idSupplier
					WHERE tglRetur LIKE '{$periode}%'
					GROUP BY d.idTransaksiBeli, d.tglRetur,d.username, s.namaSupplier, d.idSupplier 
					ORDER BY d.tglRetur DESC";
			
			$hasil = mysql_query($sql);


			while ($x = mysql_fetch_array($hasil)) {

				echo "
				<tr>
				<td class=td>$x[idTransaksiBeli]</td>
				<td class=td>$x[tglRetur]</td>
				<td class=td>$x[namaSupplier]</td>
				<td class=td>$x[nominal]</td>
				<td class=td>$x[username]</td>
				<td class=td>
					<form method=POST action='modul/js_cetak_retur_beli.php' onSubmit=\"popupform(this, 'Cetak Nota Retur')\">
					<input type=submit name=\"cetak\" value=Cetak>
					<input type=submit name=\"download\" value=\"Download\"/>
					<input type=hidden name=idSupplier value='$x[idSupplier]'>
					<input type=hidden name=tglRetur value='$x[tglRetur]'>
					<input type=hidden name=idTransaksiBeli value='$x[idTransaksiBeli]'>
					</form>
				</td>
				</tr>
			";
			} // ($x = mysql_fetch_array($hasil))
			echo "</table>";
		} // ($_GET[action] == 'lihatlaporan')

		break;


	case "returpembelian"; // =======================================================================================================================
		echo "<h2>Retur Pembelian</h2>
            <form method=POST action='?module=pembelian_barang&act=returpembelian&action=lihatlaporan'>
                Supplier : 
                <select name=supplierId>";
		$supplier = getSupplier();
		while ($dataSupplier = mysql_fetch_array($supplier)) {
			echo "<option value=$dataSupplier[idSupplier]>$dataSupplier[namaSupplier]::$dataSupplier[idSupplier]::$dataSupplier[alamatSupplier]</option>";
		}
		echo "  </select>
                <br/>Periode Laporan : Bulan :
                <select name=bulanLaporan>";
		$dataBulan = getMonth();
		while ($bulan = mysql_fetch_array($dataBulan)) {
			echo "<option value=$bulan[bulan]>" . getBulanku($bulan[bulan]) . "</option>";
		}
		echo "</select>, Tahun :
            <select name=tahunLaporan>";
		$dataTahun = getYear();
		while ($tahun = mysql_fetch_array($dataTahun)) {
			echo "<option value=$tahun[tahun]>$tahun[tahun]</option>";
		}
		echo "</select>
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type=submit value=Lihat>
            </form>
            ";


		if ($_GET[action] == 'lihatlaporan') {
			$detail = getDetailSupplier($_POST[supplierId]);
			$detailSupplier = mysql_fetch_array($detail);
			echo "<hr/>
                <br/>Nama Supplier : $detailSupplier[namaSupplier]
                <br/>Alamat Supplier : $detailSupplier[alamatSupplier]
                <br/>Periode : " . getBulan($_POST[bulanLaporan]) . " - $_POST[tahunLaporan]";
			$pembelian = getDataPembelian($_POST[supplierId], $_POST[bulanLaporan], $_POST[tahunLaporan]);
			$jmlPembelian = mysql_num_rows($pembelian);
			if ($jmlPembelian != 0) {
				echo "<br/><br/><table width=500><tr><th>No</th><th>No Nota</th><th>Tgl Pembelian</th><th>Nominal</th><th>Detail</th></tr>";
				$totalPembelian = 0;
				$no = 1;
				while ($dataPembelian = mysql_fetch_array($pembelian)) {
					if (($no % 2) == 0) {
						$warna = "#EAF0F7";
					} else {
						$warna = "#FFFFFF";
					}
					echo "<tr bgcolor=$warna>"; //end warna
					echo "<td class=td>$no</td>
                            <td class=td align=right>$dataPembelian[noNota]</td>
                            <td class=td align=center>" . tgl_indo($dataPembelian[tglNota]) . "</td>
                            <td class=td align=right>" . uang($dataPembelian[nominal]) . "</td>
                            <td class=td><a href=?module=pembelian_barang&act=detailretur&idnota=$dataPembelian[noNota]>Detail</a></td>
                        </tr>";
					$totalPembelian += $dataPembelian[nominal];
					$no++;
				}
				echo "<tr><td colspan=3 align=right class=td><b>Total</b></td><td class=td align=right><b>" . uang($totalPembelian) . "</b></td><td class=td>&nbsp;</td></tr>
                    </table>";
			} else {
				echo "<br/><br/>Belum ada pembelian dari supplier ini.";
			}
		}
		break;





	case "pemesananbarang"; // =======================================================================================================================
		echo "<h2>Pemesanan Barang</h2>
            <form method=POST action='?module=pembelian_barang&act=pemesananbarang&action=pesanbarang'>
                Supplier : 
                <select name=supplierId>";
		$supplier = getSupplier();
		while ($dataSupplier = mysql_fetch_array($supplier)) {
			echo "<option value=$dataSupplier[idSupplier]>$dataSupplier[namaSupplier]::$dataSupplier[alamatSupplier]</option>";
		}
		echo "</select>
		<br />
		Tampilkan hanya barang dengan jumlah lebih kecil dari : <input type=text name=jumlahmin value=0 size=3>
		<br />
            &nbsp;&nbsp;&nbsp;&nbsp;
            <input type=submit value=Pilih>
            </form>";
		if ($_GET[action] == 'pesanbarang') {

			$supplier = getDetailSupplier($_POST[supplierId]);
			$detailSupplier = mysql_fetch_array($supplier);
			echo "<h2>Pesan Barang di Supplier $detailSupplier[namaSupplier]</h2>
            <br/>Alamat Supplier : $detailSupplier[alamatSupplier]<br/><br/>
            <form method=POST action='modul/js_cetak_PO.php'   onSubmit=\"popupform(this, 'Purchase_Order')\">
            <table width=500>
                <tr><th>#</th><th>No</th><th>Barcode</th><th>Nama Barang</th><th>Stok<br />Saat Ini</th></tr>";
			$no = 0;
			$queryBarang = getDaftarBarangSupplier($_POST[supplierId], $_POST[jumlahmin]);
			while ($barangSupplier = mysql_fetch_array($queryBarang)) {
				if (($no % 2) == 0) {
					$warna = "#EAF0F7";
				} else {
					$warna = "#FFFFFF";
				}
				echo "<tr bgcolor=$warna>"; //end warna
				echo "<td class=td align=center><input type=checkbox name=cek[] value=$barangSupplier[idBarang] id=id$no checked=true></td>";
				$no++;
				echo "<td class=td>$no</td>
                        <td class=td>$barangSupplier[barcode]</td>
                        <td class=td>$barangSupplier[namaBarang]</td>
                        <td class=td align=right><center>$barangSupplier[jumBarang]</center></td>
                        </tr>";
			}

			echo "<input type=hidden name=idSupplier value=$_POST[supplierId]>";
			echo "<tr><td colspan=5 align=center class=td>
            <input type=radio name=pilih onClick='for (i=0;i<$no;i++){document.getElementById(\"id\"+i).checked=true;}'>Check All
            <input type=radio name=pilih onClick='for (i=0;i<$no;i++){document.getElementById(\"id\"+i).checked=false;}'>Uncheck All
            </td></tr>
            <tr>
		<td colspan=3 class=td> 		<input type=checkbox name=cetakcsv> Cetak Excel / CSV</td>
		<td colspan=2 align=right class=td>	<input type=submit value=Cetak></form></td></tr>";
			echo "</table>";
		}
		break;


	case "laporanpembelian": // ===============================================================================================================
		echo "<h2>Laporan Pembelian (per Supplier)</h2>
            <form method=POST action='?module=pembelian_barang&act=laporanpembelian&action=lihatlaporan'>
                Supplier : 
                <select name=supplierId>";
		$supplier = getSupplier();
		while ($dataSupplier = mysql_fetch_array($supplier)) {
			echo "<option value=$dataSupplier[idSupplier]>$dataSupplier[namaSupplier]::$dataSupplier[idSupplier]::$dataSupplier[alamatSupplier]</option>";
		}
		echo "  </select>
                <br/>Periode Laporan : Bulan :
                <select name=bulanLaporan>";
		$dataBulan = getMonth();
		while ($bulan = mysql_fetch_array($dataBulan)) {
			echo "<option value=$bulan[bulan]>" . getBulanku($bulan[bulan]) . "</option>";
		}
		echo "</select>, Tahun :
            <select name=tahunLaporan>";
		$dataTahun = getYear();
		while ($tahun = mysql_fetch_array($dataTahun)) {
			echo "<option value=$tahun[tahun]>$tahun[tahun]</option>";
		}
		echo "</select>
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type=submit value=Lihat>
            </form>
            ";


		if ($_GET[action] == 'lihatlaporan') {  // -------------------------------------------------------------------
			$detail = getDetailSupplier($_POST[supplierId]);
			$detailSupplier = mysql_fetch_array($detail);
			echo "<hr/>
                <br/>Nama Supplier : $detailSupplier[namaSupplier]
                <br/>Alamat Supplier : $detailSupplier[alamatSupplier]
                <br/>Periode : " . getBulan($_POST[bulanLaporan]) . " - $_POST[tahunLaporan]";
			$pembelian = getDataPembelian($_POST[supplierId], $_POST[bulanLaporan], $_POST[tahunLaporan]);
			$jmlPembelian = mysql_num_rows($pembelian);
			if ($jmlPembelian != 0) {
				echo "<br/><br/><table width=500><tr><th>No</th><th>No Nota</th><th>Tgl Pembelian</th><th>Nominal</th><th>Detail</th></tr>";
				$totalPembelian = 0;
				$no = 1;
				while ($dataPembelian = mysql_fetch_array($pembelian)) {
					if (($no % 2) == 0) {
						$warna = "#EAF0F7";
					} else {
						$warna = "#FFFFFF";
					}
					echo "<tr bgcolor=$warna>"; //end warna
					echo "<td class=td>$no</td>
                            <td class=td align=right>$dataPembelian[noNota]</td>
                            <td class=td align=center>" . tgl_indo($dataPembelian[tglNota]) . "</td>
                            <td class=td align=right>" . uang($dataPembelian[nominal]) . "</td>
                            <td class=td><a href=?module=pembelian_barang&act=detaillaporan&idnota=$dataPembelian[noNota]>Detail</a></td>
                        </tr>";
					$totalPembelian += $dataPembelian[nominal];
					$no++;
				}
				echo "<tr><td colspan=3 align=right class=td><b>Total</b></td><td class=td align=right><b>" . uang($totalPembelian) . "</b></td><td class=td>&nbsp;</td></tr>
                    </table>";
			} else {
				echo "<br/><br/>Belum ada pembelian dari supplier ini.";
			}
		}
		break;


	case "detailretur"; // ===========================================================================================================
		$data = getDataNotaPembelian($_GET[idnota]);
		$dataBeli = mysql_fetch_array($data);
		echo "<h2>Retur Nota No : $_GET[idnota]</h2>
            <table>
                <tr>
                    <td>Nama Supplier</td><td> : </td><td>$dataBeli[namaSupplier]</td><td width=20>&nbsp;</td>
                    <td>Alamat</td><td> : </td><td>$dataBeli[alamatSupplier]</td>
                </tr>
                <tr>
                    <td>Tgl Transaksi</td><td> : </td><td>" . tgl_indo($dataBeli[tglNota]) . "</td><td width=20>&nbsp;</td>
                    <td>Nominal</td><td> : </td><td>" . uang($dataBeli[nominal]) . "</td>
                </tr>
                <tr>
                    <td>Nomor Invoice</td><td> : </td><td>$dataBeli[NomorInvoice]</td><td width=20>&nbsp;</td>
                    <td colspan=3 align=right>
                        <form method=POST action='aksi.php?module=inputreturbeli&act=inputtemp'>
                        <input type=hidden name=idNota value=$_GET[idnota]>
			<input type=submit value='(i) Input Retur' accesskey='i'>
			</form></a>
                    </td>
                </tr>
            </table>            
            <br/>";

		$detail = getDetailNotaPembelian($_GET[idnota]);
		if (mysql_num_rows($detail) != 0) {
			echo "<table width=620>
                <tr><th>NO</th><th>Id Barang</th><th>Barcode</th><th>Nama Barang</th><th>Tgl Expire</th><th>Jumlah</th><th>Harga Beli</th><th>Total</th></tr>";

			$no = 1;
			$total = 0;
			while ($dataDetail = mysql_fetch_array($detail)) {
				if (($no % 2) == 0) {
					$warna = "#EAF0F7";
				} else {
					$warna = "#FFFFFF";
				}
				$subTotal = $dataDetail[jumBarang] * $dataDetail[hargaBeli];
				$total += $subTotal;
				echo "<tr bgcolor=$warna>"; //end warna
				echo "<td class=td>$no</td>
                            <td class=td>$dataDetail[idBarang]</td>
                            <td class=td>$dataDetail[barcode]</td>
                            <td class=td>$dataDetail[namaBarang]</td>
                            <td class=td>$dataDetail[tglExpire]</td>
                            <td class=td align=right>$dataDetail[jumBarang]</td>
                            <td class=td align=right>" . uang($dataDetail[hargaBeli]) . "</td>
                            <td class=td align=right>" . uang($subTotal) . "</td>
                            </tr>";
			}
			echo "<td colspan=7 align=right class=td>TOTAL</td><td align=right class=td>" . uang($total) . "</td>";
			echo "</table>";

			// Jika sudah ada - tampilkan detail retur untuk Nota ini
			echo "<h2>Nota Retur Yang Sudah Ada</h2>
		
		<table width=620>
		<tr><th>NOTA</th><th>Id Barang</th><th>Barcode</th>
		<th>Jumlah<br />Retur</th><th>Harga Beli</th><th>Total</th></tr>";

			$sql = "SELECT * FROM detail_retur_beli WHERE idTransaksiBeli = $_GET[idnota]";
			$hasil = mysql_query($sql);

			$currentTotal = 0;
			$oldTotal = 0;
			$ctr = 1;
			$totalRecord = mysql_num_rows($hasil);
			while ($x = mysql_fetch_array($hasil)) {

				$currentTotal = $x[nominal];
				if (($currentTotal !== $oldTotal) && ($oldTotal > 0)) {
					echo "<tr align=right bgcolor=#EAF0F7><td colspan=6 class=td>
				" . uang($oldTotal) . "<br />
				(username: $oldUser)<br />
				(tanggal : $oldTgl)
			";
				};

				if (($no % 2) == 0) {
					$warna = "#EAF0F7";
				} else {
					$warna = "#FFFFFF";
				}
				echo "<tr bgcolor=$warna>"; //end warna
				echo "<td class=td>$x[idTransaksiBeli]</td>
                            <td class=td>$x[idBarang]</td>
                            <td class=td>$x[barcode]</td>
                            <td class=td>$x[jumRetur]</td>
                            <td class=td align=right>" . uang($x[hargaBeli]) . "</td>
			<td class=td align=right>";

				echo "</td></tr>";
				$ctr++;
				$oldTotal = $currentTotal;
				$oldTgl = $x[tglRetur];
				$oldUser = $x[username];
			} // while ($x = mysql_fetch_array($hasil))
			echo "<tr align=right bgcolor=#EAF0F7><td colspan=6 class=td>
		" . uang($oldTotal) . "<br />
		(username: $oldUser)<br />
		(tanggal : $oldTgl)
		</td></tr>
		";
			echo "</table>";
		} else {
			echo "Tidak Ada Data Detail Barang yang dibeli";
		}

		break;


	case "inputreturbeli"; // ===========================================================================================================
		//fixme: 
		// 	belum disimpan ke table 'retur' (baru ke table 'detail_retur_beli')

		if (isset($_GET[idnota])) {
			$idNota = $_GET[idnota];
		}

		$data = getDataNotaPembelian($idNota);
		$dataBeli = mysql_fetch_array($data);
		echo "<h2>RETUR PEMBELIAN : Detail Nota No : $idNota</h2>
            <table>
                <tr>
                    <td>Nama Supplier</td><td> : </td><td>$dataBeli[namaSupplier]</td><td width=20>&nbsp;</td>
                    <td>Alamat</td><td> : </td><td>$dataBeli[alamatSupplier]</td>
                </tr>
                <tr>
                    <td>Tgl Transaksi</td><td> : </td><td>" . tgl_indo($dataBeli[tglNota]) . "</td><td width=20>&nbsp;</td>
                    <td>Nominal</td><td> : </td><td>" . uang($dataBeli[nominal]) . "</td>
                </tr>
                <tr>
                    <td>Nomor Invoice</td><td> : </td><td>$dataBeli[NomorInvoice]</td><td width=20>&nbsp;</td>
                    <td colspan=3 align=right>&nbsp;
                    </td>
                </tr>
            </table>
            <br/>";

		if ($_GET[action] == 'ubahdata') {
			ubahTempEditDetailReturPembelian($_POST[idDetailBeli], $_POST[tglExpire], $_POST[jumBarang], $_POST[hargaBeli], $_POST[jumRetur]);
		}


		echo "Nota no : $idNota";
		$detail = getDetailTmpEditReturPembelian($idNota);
		if (mysql_num_rows($detail) != 0) {
			echo "<table width=650>
                <tr><th>NO</th><th>Id Barang</th><th>Nama Barang</th><th>Tgl Expire</th><th>Jumlah</th><th>Harga Beli</th><th>Total</th><th>Jumlah<br />RETUR</th><th>AKSI</th></tr>";

			$no = 1;
			$total = 0;
			$totalRetur = 0;
			while ($dataDetail = mysql_fetch_array($detail)) {
				if (($no % 2) == 0) {
					$warna = "#EAF0F7";
				} else {
					$warna = "#FFFFFF";
				}
				$subTotal = $dataDetail[jumBarang] * $dataDetail[hargaBeli];
				$total += $subTotal;
				$subTotalRetur = $dataDetail[jumRetur] * $dataDetail[hargaBeli];
				$totalRetur += $subTotalRetur;
				echo "<tr bgcolor=$warna>"; //end warna
				echo "<td class=td>$no</td>
                            <form method=POST action='?module=pembelian_barang&act=inputreturbeli&action=ubahdata&idnota=$idNota'>
                            <td class=td>$dataDetail[idBarang]</td>
                            <td class=td>$dataDetail[namaBarang]</td>
                            <td class=td><input type=text name=tglExpire size=8	     readonly 	value=$dataDetail[tglExpire]></td>
                            <td class=td align=right><input type=text name=jumBarang size=3 readonly 	value=$dataDetail[jumBarang]></td>
                            <td class=td align=right><input type=text name=hargaBeli size=8 readonly 	value=$dataDetail[hargaBeli]></td>
                            <td class=td align=right>" . uang($subTotal) . "</td>
			    <td class=td align=center><input type=text name=jumRetur size=3     	value=$dataDetail[jumRetur]></td>
			<td class=td align=right>
                                <input type=hidden name=idDetailBeli value=$dataDetail[idDetailBeli]>                                
                                <input type=submit value=Ubah>
                                </form>
                            </td>
                            </tr>";
			}
			echo "<tr><td colspan=8 align=right class=td>&nbsp;</td></tr>";
			echo "<td colspan=6 align=right class=td>TOTAL</td><td align=right class=td>" . uang($total) . "</td>
		<td align=right class=td>" . uang($totalRetur) . "</td>
                <td class=td>
                    <form method=POST action='aksi.php?module=inputreturbeli&act=simpanretur'>
                        <input type=hidden name=idNota value=$idNota>
                        <input type=submit value=Simpan>
                    </form>
                </td>";
			echo "</table>";
		} else {
			echo "Tidak Ada Data Detail Barang yang dibeli";
		}
		break;



	case "detaillaporan"; // ===========================================================================================================
		$data = getDataNotaPembelian($_GET[idnota]);
		$dataBeli = mysql_fetch_array($data);
		echo "<h2>Detail Nota No : $_GET[idnota]</h2>
            <table>
                <tr>
                    <td>Nama Supplier</td><td> : </td><td>$dataBeli[namaSupplier]</td><td width=20>&nbsp;</td>
                    <td>Alamat</td><td> : </td><td>$dataBeli[alamatSupplier]</td>
                </tr>
                <tr>
                    <td>Tgl Transaksi</td><td> : </td><td>" . tgl_indo($dataBeli[tglNota]) . "</td><td width=20>&nbsp;</td>
                    <td>Nominal</td><td> : </td><td>" . uang($dataBeli[nominal]) . "</td>
                </tr>
                <tr>
                    <td>Nomor Invoice</td><td> : </td><td>$dataBeli[NomorInvoice]</td><td width=20>&nbsp;</td>
                    <td colspan=3 align=right>
                        <form method=POST action='aksi.php?module=editlaporanpembelian&act=inputtemp'>
                        <input type=hidden name=idNota value=$_GET[idnota]>
			<input type=submit value='(e) Edit Laporan Pembelian' accesskey='e'>
			</form></a>
                    </td>
                </tr>
            </table>            
            <br/>";

		$detail = getDetailNotaPembelian($_GET[idnota]);
		if (mysql_num_rows($detail) != 0) {
			echo "<table width=620>
                <tr><th>NO</th><th>Id Barang</th><th>Barcode</th><th>Nama Barang</th><th>Tgl Expire</th><th>Jumlah</th><th>Harga Beli</th><th>Total</th></tr>";

			$no = 1;
			$total = 0;
			while ($dataDetail = mysql_fetch_array($detail)) {
				if (($no % 2) == 0) {
					$warna = "#EAF0F7";
				} else {
					$warna = "#FFFFFF";
				}
				$subTotal = $dataDetail[jumBarang] * $dataDetail[hargaBeli];
				$total += $subTotal;
				echo "<tr bgcolor=$warna>"; //end warna
				echo "<td class=td>$no</td>
                            <td class=td>$dataDetail[idBarang]</td>
                            <td class=td>$dataDetail[barcode]</td>
                            <td class=td>$dataDetail[namaBarang]</td>
                            <td class=td>$dataDetail[tglExpire]</td>
                            <td class=td align=right>$dataDetail[jumBarang]</td>
                            <td class=td align=right>" . uang($dataDetail[hargaBeli]) . "</td>
                            <td class=td align=right>" . uang($subTotal) . "</td>
                            </tr>";
				$no++;
			}
			echo "<td colspan=7 align=right class=td>TOTAL</td><td align=right class=td>" . uang($total) . "</td>";
			echo "</table>";
		} else {
			echo "Tidak Ada Data Detail Barang yang dibeli";
		}

		break;


	case "editlaporan"; // ===============================================================================================================
		if (isset($_GET[idnota])) {
			$idNota = $_GET[idnota];
		}

		$data = getDataNotaPembelian($idNota);
		$dataBeli = mysql_fetch_array($data);
		echo "<h2>Detail Nota No : $idNota</h2>
            <table>
                <tr>
                    <td>Nama Supplier</td><td> : </td><td>$dataBeli[namaSupplier]</td><td width=20>&nbsp;</td>
                    <td>Alamat</td><td> : </td><td>$dataBeli[alamatSupplier]</td>
                </tr>
                <tr>
                    <td>Tgl Transaksi</td><td> : </td><td>" . tgl_indo($dataBeli[tglNota]) . "</td><td width=20>&nbsp;</td>
                    <td>Nominal</td><td> : </td><td>" . uang($dataBeli[nominal]) . "</td>
                </tr>
                <tr>
                    <td>Nomor Invoice</td><td> : </td><td>$dataBeli[NomorInvoice]</td><td width=20>&nbsp;</td>
                    <td colspan=3 align=right>&nbsp;
                    </td>
                </tr>
            </table>
            <br/>";
		if ($_GET[action] == 'ubahdata') {
			ubahTempEditDetailPembelian($_POST[idDetailBeli], $_POST[tglExpire], $_POST[jumBarang], $_POST[hargaBeli]);
		}
		echo "Nota no : $idNota";
		$detail = getDetailTmpEditPembelian($idNota);
		if (mysql_num_rows($detail) != 0) {
			echo "<table width=650>
                <tr><th>NO</th><th>Id Barang</th><th>Nama Barang</th><th>Tgl Expire</th><th>Jumlah</th><th>Harga Beli</th><th>Total</th><th>AKSI</th></tr>";

			$no = 1;
			$total = 0;
			while ($dataDetail = mysql_fetch_array($detail)) {
				if (($no % 2) == 0) {
					$warna = "#EAF0F7";
				} else {
					$warna = "#FFFFFF";
				}
				$subTotal = $dataDetail[jumBarang] * $dataDetail[hargaBeli];
				$total += $subTotal;
				echo "<tr bgcolor=$warna>"; //end warna
				echo "<td class=td>$no</td>
                            <form method=POST action='?module=pembelian_barang&act=editlaporan&action=ubahdata&idnota=$idNota'>
                            <td class=td>$dataDetail[idBarang]</td>
                            <td class=td>$dataDetail[namaBarang]</td>
                            <td class=td><input type=text name=tglExpire value=$dataDetail[tglExpire]></td>
                            <td class=td align=right><input type=text name=jumBarang value=$dataDetail[jumBarang]></td>
                            <td class=td align=right><input type=text name=hargaBeli value=$dataDetail[hargaBeli]></td>
                            <td class=td align=right>" . uang($subTotal) . "</td>
                            <td class=td align=right>
                                <input type=hidden name=idDetailBeli value=$dataDetail[idDetailBeli]>                                
                                <input type=submit value=Ubah>
                                </form>
                            </td>
                            </tr>";
			}
			echo "<tr><td colspan=8 align=right class=td>&nbsp;</td></tr>";
			echo "<td colspan=6 align=right class=td>TOTAL</td><td align=right class=td>" . uang($total) . "</td>
                <td class=td>
                    <form method=POST action='aksi.php?module=editlaporanpembelian&act=simpanedit'>
                        <input type=hidden name=idNota value=$idNota>
                        <input type=submit value=Simpan>
                    </form>
                </td>";
			echo "</table>";
		} else {
			echo "Tidak Ada Data Detail Barang yang dibeli";
		}
		break;



	case "pembelianbarang": // =================================================================================================================
		$sql1 = getSupplier();

		echo "<h2>Pembelian Barang</h2>
	              <form method=POST action='?module=pembelian_barang&act=carisupplier'>
			<select name=idSupplier>";

		while ($data = mysql_fetch_array($sql1)) {

			echo "<option value=$data[idSupplier]>$data[namaSupplier] :: $data[alamatSupplier]";
		};

		echo "  </select>
			<input type=submit value='(c) Cari' accesskey='c' name='cariSupplier' />
	              </form>";
		break;


	case "carisupplier": // ====================================================================================================================

		if (isset($_POST['idSupplier'])) {
			$x = findSupplier($_POST['idSupplier']);
		} else {
			$x = findSupplier($_GET['idSupplier']);
		};

		//echo "POST : ".$_POST['idSupplier']." GET : ".$_GET['idSupplier']." SESSION"; var_dump($_SESSION);

		echo "<h2>Pembelian Barang</h2>Pembelian Barang dari supplier :
                    " . $_SESSION['namaSupplier'];
		echo "<form method=POST action='?module=pembelian_barang&act=carisupplier&action=cek&idSupplier=" . $_SESSION['idSupplier'] . "'>
		
			<br /><br /> (1) Pilihan barcode : ";

		$sql1 = mysql_query("SELECT DISTINCT barcode, namaBarang   
                                FROM barang WHERE idSupplier=" . $_SESSION['idSupplier'] . " ORDER BY namaBarang ASC");

		echo "<select name=barcode accesskey='1'>";
		while ($data = mysql_fetch_array($sql1)) {
			echo "<option value=$data[barcode]>$data[barcode] :: $data[namaBarang]";
		};
		echo "</select>";

		echo "	<input type=submit value='(2) Pilih barcode !' accesskey='2' /> 
		<input type='hidden' name='xppn' value='$_POST[xppn]'>
                </form>";


		echo "  <form method=POST action='?module=pembelian_barang&act=carisupplier&action=cek&idSupplier=" . $_SESSION['idSupplier'] . "'>
	
		<br /> (3) Pilihan barang : ";

		$sql1 = mysql_query("SELECT DISTINCT barcode, namaBarang   
                                FROM barang WHERE idSupplier=$_SESSION[idSupplier] ORDER BY namaBarang ASC");

		echo "<select name=barcode accesskey='3'>";
		while ($data = mysql_fetch_array($sql1)) {
			echo "<option value=$data[barcode]>$data[namaBarang] :: $data[barcode]";
		};
		echo "</select>";

		echo "	<input type=submit value='(4) Pilih barang !' accesskey='4' /> 
		<input type='hidden' name='xppn' value='$_POST[xppn]'>
                </form>";


		//HS tombol "Tambah Barang" akan memunculkan form dialog jQuery
		echo "	<form method=POST action='?module=pembelian_barang&act=carisupplier&action=cek&idSupplier=" . $_SESSION['idSupplier'] . "'>
		<center><input type=\"button\" id=\"tambahbarang\" accesskey='b' value='(b) Tambah Barang Baru' /> </center>
		<input type='hidden' name='xppn' value='$_POST[xppn]'>
		</form> <br />";




		if ($_GET[action] == 'cek') { // ===============================================================================================================
			$barang = cekBarang($_POST[barcode]);
			if (!$barang) {
				echo "Data belum ada !";
				break;
			}
		}

		echo '
			<script>
				function RecalcHargaBarangLama() {
					var HargaBeli = 0;
					var HargaJual = 0;
					var Subtotal 	= parseInt(document.getElementById("xsubtotal").value);
					var PPN 	= parseInt(document.getElementById("xppn").value);
					var JumlahBarang = parseInt(document.getElementById("jumBarang").value);
					var PersenProfit = parseInt(document.getElementById("xPersenProfit").value);
					var DiskonPersen = parseInt(document.getElementById("xDiskonPersen").value);
					var DiskonRupiah = parseInt(document.getElementById("xDiskonRupiah").value);

					if(Subtotal){
						HargaBeli = (Subtotal / JumlahBarang) 
						// hitung diskon dulu !
						HargaBeli = HargaBeli  - ((HargaBeli / 100) * DiskonPersen) - DiskonRupiah;
						// baru kemudian hitung PPN
						HargaBeli = HargaBeli + ((HargaBeli / 100) * PPN);

						HargaJual = HargaBeli + ((HargaBeli / 100) * PersenProfit);
					}

                                        // mencegah keliru input barcode di kolom JumlahBarang
                                        if(JumlahBarang > 2000){
                                                JumlahBarang = 0;
                                        }


					document.getElementById("hargaBeliBaru").value 	= HargaBeli;
					document.getElementById("hargaJualBaru").value 	= HargaJual;
                                        document.getElementById("jumBarang").value 	= JumlahBarang;

				}

			</script>';

		// inisialisasi variabel xppn
		if (!$_POST[xppn]) {
			$_POST[xppn] = 0;
		};
		echo "<br/> 

		<div id=\"frmTambahBarang\">
            <form method=POST action='?module=pembelian_barang&act=carisupplier&action=tambah'>
                <table>
                    <tr>
                        <td>Barcode</td><td> : <input type=text name='barcode' value='$barang[barcode]' readonly='readonly' />
				<input type=hidden name='idBarang' value='$barang[idBarang]' /></td>
			<td><a name='#jumlah'>  <u>J</u>umlah yang dibeli </a></td>
			<td> : <input type=text name='jumBarang' id='jumBarang' tabindex=1 accesskey='j'/></td>
                    </tr>
                    <tr>
                        <td>Nama Barang</td><td> : <input type=text name='namaBarang' value='$barang[namaBarang]' readonly='readonly' /></td>
                        <td>Subtotal</td><td> : Rp <input type=text name='subtotal' value='0' id='xsubtotal'  tabindex=2 /></td>                        
                    </tr>
			<tr>
				<td>Diskon %</td><td> : <input type=text name='xDiskonPersen' value='0' id='xDiskonPersen' /></td>		
				<td>% PPN</td><td> : <input type=text name='xppn' value='$_POST[xppn]' id='xppn'  tabindex=3 /></td>                     		
			</tr>
			<tr>
				<td>Diskon Rp</td><td> : <input type=text name='xDiskonRupiah' value='0' id='xDiskonRupiah' /></td>
				<td>% Profit</td><td> : <input type=text name='persenprofit' value='0' id='xPersenProfit' tabindex=4 /><br />
					&nbsp;&nbsp;&nbsp;&nbsp; [<a href='#jumlah' onclick=\"RecalcHargaBarangLama();\" accesskey='6'>  (6) Hitung Harga ]
				</td>                     		
			</tr>
			<tr>
				<td>Harga Beli Sekarang</td><td> : <input type=text name='hargaBeliLama' value='$barang[hargaBeli]' readonly='readonly' /></td>
	                        <td>Harga Beli Barang</td><td> : <input type=text name='hargaBeliBaru' id='hargaBeliBaru' tabindex=5 /></td>
			</tr>
                    <tr>
                        <td>Harga Jual Sekarang</td><td> : <input type=text name='hargaJualLama' value='$barang[hargaJual]' readonly='readonly' /></td>
                        <td>Harga Jual Barang</td><td> : <input type=text name='hargaJualBaru' id='hargaJualBaru' value='$barang[hargaJual]' tabindex=6 /></td>
                    </tr>                    
                    <tr>
                        <td colspan=2>&nbsp</td>
                        <td>Tanggal Expire</td><td> : <input type=text name='tglExpire' size=10 tabindex=7 />(yyyy-mm-dd)</td>
                    </tr>
                    <tr>

                        <td align=right colspan=4>
				<input type=submit accesskey='t' value='(t) Tambah' name=btTambah tabindex=8 >
				<input type='hidden' name='idSupplier' value='" . $_SESSION['idSupplier'] . "'>
			</td>
                    </tr>
                </table>
            </form>
		</div>

		<script>
			var txtBox=document.getElementById(\"jumBarang\" );
			if (txtBox!=null ) txtBox.focus();
		</script>
        ";

		//fixme : perlu validasi input
		//	# tidak boleh kosong jumlah barang
		//	# tidak boleh kosong harga beli	
		//	# tidak boleh kosong harga jual
		// bisa pakai fasilitas dari jQuery : http://www.position-absolute.com/articles/jquery-form-validator-because-form-validation-is-a-mess/





		if ($_GET[action] == 'tambah') { // =============================================================================================================
			//fixme: item dg barcode "0" pasti selalu ikut terinput - cek dari log query MySQL
			$true = cekBarangTemp($_SESSION[idSupplier], $_POST[barcode]);
			if ($_POST[barcode] <> 0) {
				if ($true != 0) {
					tambahBarangAda($_SESSION[idSupplier], $_POST[barcode], $_POST[jumBarang]);
				} else {
					tambahBarang($_SESSION[idSupplier], $_POST[barcode], $_POST[jumBarang], $_POST[hargaBeliBaru], $_POST[hargaJualBaru], $_POST[tglExpire]);
				}
			}
		}
		if ($_GET[action] == 'ubahjumlah') {
			//echo "Ubah Jumlah  :  $_POST[barcode],$_POST[jumlahBarang],$_POST[hargaBeli],$_POST[hargaJual]";

			$true = cekBarangTemp($_SESSION[idSupplier], $_POST[barcode]);
			if ($true != 0) {
				ubahJumlahBarangBeliTemp($_SESSION[idSupplier], $_POST[idBarang], $_POST[jumlahBarang]);
			}
		}
		$sql = "SELECT *
                                from tmp_detail_beli tdb, barang b
                                where tdb.barcode = b.barcode and tdb.idSupplier = '$_SESSION[idSupplier]' and tdb.username = '$_SESSION[uname]'";
		//echo $sql;
		$query = mysql_query($sql);

		$r = mysql_fetch_row($query);
		echo "<hr/>";

		if ($r) { // -------------------- tampilkan data yang sudah di input sejauh ini ---------
			//echo "Ada $r[0] data";
			echo "<table class=tableku width=700>
                    <tr><th>Barcode</th><th>Nama Barang</th>
                        <th>Tgl Expire</th><th>Jumlah</th>
                        <th>Harga Beli</th>
			<th>Harga Jual</th>
                        <th>Total</th>
			<th>Aksi</th>
			</tr>";
			$no = 1;
			$tot_pembelian;
			$sql = "SELECT tdb.barcode, tdb.idBarang, tdb.hargaJual, namaBarang, tglExpire, tdb.jumBarang, hargaBeli
                                FROM tmp_detail_beli tdb, barang b
                                WHERE tdb.barcode = b.barcode AND tdb.idSupplier = '$_SESSION[idSupplier]' AND tdb.username = '$_SESSION[uname]'
				ORDER BY idBarang";
			//echo $sql;
			$query2 = mysql_query($sql);
			while ($data = mysql_fetch_array($query2)) {
				$total = $data[hargaBeli] * $data[jumBarang];
				//untuk mewarnai tabel menjadi selang-seling
				if (($no % 2) == 0) {
					$warna = "#EAF0F7";
				} else {
					$warna = "#FFFFFF";
				}
				echo "<tr bgcolor=$warna>";
				echo "	<form method=POST action='?module=pembelian_barang&act=carisupplier&action=ubahjumlah'>
                                <td>$data[barcode]</td>
				<td>$data[namaBarang]</td>
                        	<td>$data[tglExpire]</td>
				<td align=right><input type=text name=jumlahBarang value=$data[jumBarang] size=5></td>
                        	<td align=right>$data[hargaBeli]</td>
                        	<td align=right>$data[hargaJual]</td>

                                <input type=hidden name=barcode value=$data[barcode]>
                                <input type=hidden name=idBarang value=$data[idBarang]>

				<td align=right>" . number_format($total, 0, ',', '.') . "</td>
                                <td width=120><input type=submit name=update value=Update></form> |
                                <a href='./aksi.php?module=pembelian_barang&act=hapus_detil&id=$data[idBarang]'>Hapus</a></td>
                        </tr>";
				$tot_pembelian += $total;
				$no++;
			}

//fixme: tombol update membuat jumlah stok jadi sama semua
			//HS total invoice :
			//	subtotal - (DiskonPersen x subtotal) - (DiskonRupiah) + PPN
			if (empty($_POST[DiskonPersen])) {
				$_POST[DiskonPersen] = 0;
			};
			if (empty($_POST[DiskonRupiah])) {
				$_POST[DiskonPersen] = 0;
			};
			if (empty($_POST[PPN])) {
				$_POST[PPN] = 0;
			};
			$tot_pembelian = $tot_pembelian - ($_POST[DiskonPersen] * $tot_pembelian) - $_POST[DiskonRupiah] + $_POST[PPN];


			echo '
			<script>
				function Recalc() {
					var total = 0;
					var GrandTotal   = parseInt(document.getElementById("tot_pembayaran").value);
					var PPN 	 = parseInt(document.getElementById("ppn").value);
					var DiskonPersen = parseInt(document.getElementById("diskonpersen").value);
					var DiskonRupiah = parseInt(document.getElementById("diskonrupiah").value);

					if(GrandTotal){
						total = GrandTotal; 
						total = total - (GrandTotal / 100 * DiskonPersen);
						total = total - DiskonRupiah;
						total = total + (GrandTotal / 100 * PPN);
					}
					document.getElementById("grandtotal").value = total;
					document.getElementById("tot_pembayaran").value = total;
				}

			</script>';

			echo "</table>";
			$pmbyrn = mysql_query("SELECT * from pembayaran");
			echo "<form method=POST action='./aksi.php?module=pembelian_barang&act=input'>
                        <input type=hidden name='tot_pembayaran' value='$tot_pembelian' id='tot_pembayaran'>
                    <table class=tableku width=600>
                        <tr><td width=65% align=right><a name='#total'>Total Pembelian</a><br />
				<a href='#total' onclick=\"Recalc();\" accesskey='u'>Hitung (U)lang</a></td><td align=right><input id='grandtotal' readonly='readonly' value='" . number_format($tot_pembelian, 0, ',', '.') . "' tabindex=9></td></tr>
                        <tr><td width=65% align=right>Tipe Pembayaran</td>
                            <td align=right><select name='tipePembayaran' tabindex=10>
                                        <option value='0'>-Tipe Pembayaran-</option>";
			while ($pembayaran = mysql_fetch_array($pmbyrn)) {
				echo "<option value='$pembayaran[idTipePembayaran]'>$pembayaran[tipePembayaran]</option>";
			}
			echo "</select></td></tr>
                        <tr><td width=65% align=right>Tanggal Pembayaran (hutang)</td><td align=right><input type=text name='tglBayar' tabindex=11></td></tr>
                        <tr><td width=65% align=right>Nomor Invoice</td><td align=right><input type=text name='NomorInvoice' value=0 tabindex=12></td></tr>
                        <tr><td width=65% align=right>Tanggal Invoice</td><td align=right><input type=text name='TanggalInvoice' 
			value='" . date("Y-m-d") . "' tabindex=13></td></tr>
			<tr><td width=65% align=right>Diskon (%)</td><td align=right><input type=text id='diskonpersen' name='DiskonPersen' value=0 tabindex=14> %</td></tr>
			<tr><td width=65% align=right>Diskon (Rp)</td><td align=right>Rp <input type=text id='diskonrupiah' name='DiskonRupiah' value=0 tabindex=15></td></tr>
			<tr><td width=65% align=right>PPn</td><td align=right><input type=text id='ppn' name='PPN' value=0 tabindex=16> %</td></tr>";



			echo "
                        <tr><td colspan=2>&nbsp;</td></tr>
                        <tr>
				<td><a href='aksi.php?module=pembelian_barang&act=batal'>BATAL</a></td>

				<td>
					<input type='hidden' name='idSupplier' value='" . $_SESSION['idSupplier'] . "'>
					<input type=submit value='Simpan' tabindex=17>
				</td>
			</tr>
                        </table></form>
			";

			//fixme : Pembatalan Nota (code di atas & bawah komentar ini) perlu merujuk ke user ybs,
			// agar jangan keliru menghapus nota yang sedang di input oleh user yang lainnya 
		} else {

			echo "Belum ada barang yang dibeli<br /><a href='aksi.php?module=pembelian_barang&act=batal'>BATAL</a>";
		}

		break;



	case "laporanpembeliantanggal": // ===============================================================================================================

		echo "<h2>Laporan Pembelian (per Tanggal)</h2>
            <form method=POST action='?module=pembelian_barang&act=laporanpembeliantanggal&action=pilihtanggal'>

                <br />Periode Laporan : Bulan :
                <select name=bulanLaporan>";
		$dataBulan = getMonth();
		while ($bulan = mysql_fetch_array($dataBulan)) {
			echo "<option value=$bulan[bulan]>" . getBulanku($bulan[bulan]) . "</option>";
		}
		echo "</select>, Tahun :
            <select name=tahunLaporan>";
		$dataTahun = getYear();
		while ($tahun = mysql_fetch_array($dataTahun)) {
			echo "<option value=$tahun[tahun]>$tahun[tahun]</option>";
		}
		echo "</select>
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type=submit value=Lihat>
            </form>
            ";


		if ($_GET[action] == 'pilihtanggal') { // ------------------------------------------------------------
			$sql = "SELECT tglTransaksiBeli, idTransaksiBeli FROM transaksibeli 
				WHERE month(tglTransaksiBeli)='$_POST[bulanLaporan]' AND year(tglTransaksiBeli)='$_POST[tahunLaporan]' 
				ORDER BY tglTransaksiBeli, idTransaksiBeli ASC";
			$hasil = mysql_query($sql);

			echo "<hr />
	            <form method=GET action='?module=pembelian_barang&act=detaillaporan'>
		
			Tanggal :
			<select name=idnota>
		";
			while ($x = mysql_fetch_array($hasil)) {
				echo "<option value=$x[idTransaksiBeli]> $x[tglTransaksiBeli] : Nmr.Nota #$x[idTransaksiBeli]</option>";
			}; // while ($x = mysql_fetch_array($hasil)) {
			echo "</select>
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type=submit value=Lihat>

			<input type=hidden name=module value=pembelian_barang>
			<input type=hidden name=act value=detaillaporan>

		            </form>
		";
		}; // if($_GET[action] == 'pilihtanggal'){


		break;




	case "inputeprocurement1"; // =======================================================================================================================
		echo "<h2>Input Transaksi Pembelian Elektronik</h2>
            <form method=POST action='?module=pembelian_barang&act=inputeprocurement2'  enctype='multipart/form-data'> 
                Pilih File Transaksi : 
		<input type=file  name=csvfile /> <br />
		Tipe File :
		<select name=jenistransaksi>
			<option value='gudangahad' selected>Gudang.AhadMart.com</option>
			<option value='transferahad' selected>Transfer antar Ahad mart</option>
		</select> <br /><br />

		<input type=submit value=Posting /> 
		</form>
	";



		break;



	case "inputeprocurement2"; // =============================================================================================================

		$ctr = 0;
		$tgl = date("Y-m-d");
		$totalInvoice = 0;

		if (empty($_FILES) && empty($_POST) && isset($_SERVER['REQUEST_METHOD']) && strtolower($_SERVER['REQUEST_METHOD']) == 'post') {
			$poidsMax = ini_get('post_max_size');
			echo "<h2>ERROR : file yang di upload lebih besar daripada limit di server, yaitu : $poidsMax. <br />Tidak merubah apapun.";
			exit;
		};


		// edited by abufathir: tambahan untuk nomor invoice jika ada
		$namaFileSaja = explode('.', $_FILES["csvfile"]["name"]);
		$namaFile = explode('-', $namaFileSaja[0]);
		$nomorInvoice = explode('-', $namaFile[0]);
		// =======================================


		$csvfile = $_FILES['csvfile']['tmp_name'];

		echo "<h2>Input Transaksi Pembelian Elektronik</h2>

		<form method=POST action='?module=pembelian_barang&act=inputeprocurement3'> ";
		// tambahan untuk nomor invoice jika ada
		?>
		Nomor Invoice (opt): <input type="text" name="nomorInvoice" size="10" value ="<?php echo is_numeric($nomorInvoice[0]) ? $nomorInvoice[0] : '' ?>" />
		<br /><br />
		<?php
		// =======================================
		echo "
		<table border=1>
		<tr>
			<td><center><b>Barcode
			</b></center></td>
			<td><center><b>Nama Barang
			</b></center></td>
			<td><center><b>Jumlah
			</b></center></td>
			<td><center><b>Harga Beli
			</b></center></td>
			<td><center><b>Harga Jual Lama
			</b></center></td>
			<td bgcolor=#dddddd><center><b>RRP / Harga Jual Baru
			</b></center></td>
			<td><center><b>Satuan
			</b></center></td>
			<td><center><b>Kategori
			</b></center></td>
		</tr>
		";

		//echo "File: $csvfile";
		// mulai memproses isi file CSV
		$n = 1;
		if (($handle = fopen($csvfile, "r")) !== FALSE) {
			while (($data = fgetcsv($handle, 1000, ',', '"')) !== FALSE) {
				// sometimes there are empty lines in the CSV file - don't process that
				if (!is_null($data[0]) AND ($data[0] !== 'barcode')) {
					// format isi file CSV :
					// $data[0]  = barcode
					// $data[1]  = idBarang - ignored
					// $data[2]  = namaBarang
					// $data[3]  = jumlah Barang / jumBarang
					// $data[4]  = hargaBeli - ignored
					// $data[5]  = hargaJual (di Gudang) 
					// $data[6]  = RRP (Recommended Retail Price)
					// $data[7]  = namaSatuanBarang
					// $data[8]  = namaKategoriBarang
					// $data[9]  = Supplier - ignored
					// $data[10] = username - ignored
					// cari harga jual dari barang ybs pada saat ini
					$hargaSekarang = 0;
					$sql = "SELECT hargaJual FROM barang WHERE barcode='" . $data[0] . "'";
					$hasil = mysql_query($sql);
					$z = mysql_fetch_array($hasil);
					$hargaSekarang = $z[hargaJual];

					echo "
				<tr>
					<td><input type=text name=barcode$n value='" . $data[0] . "' size=10 readonly>
					</td>
					<td><center><input type=text name=namabarang$n value='" . $data[2] . "' readonly>
					</td>
					<td><input type=text name=jumlah$n value='" . $data[3] . "' size=2 readonly>
					</td>
					<td><input type=text name=harga$n value='" . $data[5] . "' size=5 readonly>
					</td>
					<td><center>$hargaSekarang
					</td>
					<td bgcolor=#dddddd><input type=text name=rrp$n value='" . $data[6] . "' size=5>
					</td>
					<td><input type=text name=satuan$n value='" . $data[7] . "' size=2 readonly>
					</td>
					<td><input type=text name=kategori$n value='" . $data[8] . "' size=6 readonly>
					</td>
				</tr>
				";
					$n++;
				}; // if (!is_null($data[0]) AND ($data[0]  !== 'barcode')) {		
			}; // while (($data = fgetcsv($handle, 1000, ',', '"')) !== FALSE) {
		}; // if (($handle = fopen($csvfile, "r")) !== FALSE) {

		echo "
		</table>
		
		<input type=hidden name=count value=$n>
		<input type=hidden name=jenistransaksi value=" . $_POST['jenistransaksi'] . ">

		<input type=submit value=SIMPAN /> 
		</form>
	";

		break;




	case "inputeprocurement3"; // =============================================================================================================

		$tgl = date("Y-m-d");
		$totalInvoice = 0;
		$ctr = 1;

		// cek apakah ada Supplier "Gudang.AhadMart.com" di table 
		if ($_POST['jenistransaksi'] == 'transferahad') {
			$hasil = mysql_query("SELECT idSupplier FROM supplier WHERE namaSupplier='Transfer.AhadMart.com'");
		} elseif ($_POST['jenistransaksi'] == 'gudangahad') {
			$hasil = mysql_query("SELECT idSupplier FROM supplier WHERE namaSupplier='Gudang.AhadMart.com'");
		};
		// jika tidak - bikin 1 recordnya
		if (mysql_num_rows($hasil) < 1) {
			if ($_POST['jenistransaksi'] == 'transferahad') {
				$sql = mysql_query("INSERT INTO supplier (namaSupplier, alamatSupplier, telpSupplier, Keterangan, last_update) 
				VALUES ('Transfer.AhadMart.com', 'Jakarta', '021-7359407', 'http://transfer.ahadmart.com', '" . date('Y-m-d') . "')");
			} elseif ($_POST['jenistransaksi'] == 'gudangahad') {
				$sql = mysql_query("INSERT INTO supplier (namaSupplier, alamatSupplier, telpSupplier, Keterangan, last_update) 
				VALUES ('Gudang.AhadMart.com', 'Jakarta', '021-7330923', 'http://gudang.ahadmart.com', '" . date('Y-m-d') . "')");
			};

			mysql_query($sql);
			$hasil = mysql_query("SELECT LAST_INSERT_ID() FROM supplier");
		};
		$x = mysql_fetch_array($hasil);
		$idSupplier = $x[0];


		// bikin record di table transaksibeli
		// tambahan nomorInvoice jika ada : edited by abufathir;
		
		$sql = "INSERT INTO transaksibeli(tglTransaksiBeli, idSupplier, nominal, idTipePembayaran, username, last_update, NomorInvoice)
                    VALUES('$tgl', " . $idSupplier . ", '9999',1, '$_SESSION[uname]','$tgl',{$_POST['nomorInvoice']})";
		$hasil = mysql_query($sql);
		// simpan nomor transaksi beli nya
		$hasil = mysql_query("SELECT LAST_INSERT_ID() FROM transaksibeli");
		$x = mysql_fetch_array($hasil);
		$idTransaksiBeli = $x[0];

		while ($ctr < $_POST[count]) {

			// semua transaksi dicatat di table detail_beli 
			// bikin idBarang dulu, via tmp_detail_beli
			$hasil = mysql_query("INSERT INTO tmp_detail_beli (idSupplier, tglTransaksi, tglExpire, jumBarang, hargaBeli, hargaJual) VALUES (1,'$tgl','$tgl',1,2,3)");
			$hasil = mysql_query("SELECT LAST_INSERT_ID() FROM tmp_detail_beli");
			$x = mysql_fetch_array($hasil);
			$idBarang = $x[0];
			// hapus record temporary tadi
			$hasil = mysql_query("DELETE FROM tmp_detail_beli WHERE idBarang=$idBarang");
			// baru bikin record item ini di detail_beli
			mysql_query("INSERT INTO detail_beli (idTransaksiBeli, idBarang, tglExpire, jumBarang, hargaBeli, 
							isSold, barcode, username, jumBarangAsli) 
						VALUES ($idTransaksiBeli, $idBarang, '', " . $_POST["jumlah$ctr"] . ", " . $_POST["harga$ctr"] . ", 'N', 
							'" . $_POST["barcode$ctr"] . "', '$_SESSION[uname]', " . $_POST["jumlah$ctr"] . ")");
			echo "<br /><hr>--- Menambahkan detail_beli: barcode <b>" . $_POST["barcode$ctr"] . "</b>";

			// cek apakah ada namaSatuanBarang ini di table satuan_barang ?
			$hasil = mysql_query("SELECT idSatuanBarang FROM satuan_barang WHERE namaSatuanBarang='" . $_POST["satuan$ctr"] . "'");
			// jika tidak - bikin 1 recordnya
			if (mysql_num_rows($hasil) < 1) {
				mysql_query("INSERT INTO satuan_barang (namaSatuanBarang) VALUES ('" . $_POST["satuan$ctr"] . "')");
				$hasil = mysql_query("SELECT LAST_INSERT_ID() FROM satuan_barang");
				$x = mysql_fetch_array($hasil);
				$idSatuanBarang = $x[0];
				echo "<br />--- Menambahkan satuan barang: " . $_POST["satuan$ctr"] . "";
			} else {
				$x = mysql_fetch_array($hasil);
				$idSatuanBarang = $x[idSatuanBarang];
			};

			// cek apakah ada KategoriBarang ini di table kategori_barang ?
			$sql = "SELECT idKategoriBarang FROM kategori_barang 
							WHERE namaKategoriBarang='" . $_POST["kategori$ctr"] . "'";
			$hasil = mysql_query($sql);
			// jika tidak - bikin 1 recordnya
			if (mysql_num_rows($hasil) < 1) {
				mysql_query("INSERT INTO kategori_barang (namaKategoriBarang) VALUES ('" . $_POST["kategori$ctr"] . "')");
				$hasil = mysql_query("SELECT LAST_INSERT_ID() FROM tmp_detail_beli");
				$x = mysql_fetch_array($hasil);
				$idKategoriBarang = $x[0];
				echo "<br />--- Menambahkan kategori barang: " . $_POST["kategori$ctr"] . " ";
			} else {
				$x = mysql_fetch_array($hasil);
				$idKategoriBarang = $x[idKategoriBarang];
			};

			// cek apakah ada barcode ini di table barang ?
			$hasil = mysql_query("SELECT jumBarang, namaBarang FROM barang WHERE barcode='" . $_POST["barcode$ctr"] . "'");
			$x = mysql_fetch_array($hasil);
			// jika ada - tambah quantity nya
			if (mysql_num_rows($hasil) > 0) {
				$sql = "UPDATE barang SET jumBarang=" . ($x[jumBarang] + $_POST["jumlah$ctr"]) . ", 
								idKategoriBarang = $idKategoriBarang, 
								idSatuanBarang = $idSatuanBarang,
								idSupplier = $idSupplier, 
								hargaJual = " . $_POST["rrp$ctr"] . " 
							WHERE barcode='" . $_POST["barcode$ctr"] . "'";
				$hasil = mysql_query($sql);
				echo "<br />=== Barcode sudah ada: di <u>Database</u>: <b>$x[namaBarang]</b>, 
							di <u>Invoice</u>: <b>" . $_POST["namabarang$ctr"] . "</b>";
				echo "<br />### Update data barang: $sql - " . $_POST["namabarang$ctr"] . " <br />";
				// jika tidak - bikin 1 recordnya
			} else {
				$sql = "INSERT INTO barang (idBarang, namaBarang, idKategoriBarang, idSatuanBarang, jumBarang, hargaJual,
									 last_update, idSupplier, barcode, username, idRak)
						VALUES ($idBarang, '" . $_POST["namabarang$ctr"] . "', $idKategoriBarang, $idSatuanBarang, 
							" . $_POST["jumlah$ctr"] . ", " . $_POST["rrp$ctr"] . ", '$tgl', 
							$idSupplier, '" . $_POST["barcode$ctr"] . "', '$_SESSION[uname]', '999')";
				$hasil = mysql_query($sql);
				echo "<br />### Menambahkan data barang: $sql <br />";
			};

			// hitung total 
			$totalInvoice = $totalInvoice + ($_POST["jumlah$ctr"] * $_POST["harga$ctr"]);
			// tambahkan counter
			$ctr++;
		}; // while ($ctr <= $_POST[count]) {
		// update nilai invoice di table transaksibeli
		$hasil = mysql_query("UPDATE transaksibeli SET nominal=$totalInvoice WHERE idTransaksiBeli=$idTransaksiBeli");
		// laporkan jumlah record yang kita proses
		echo "<br /><h2>Jumlah item di invoice ini: " . ($ctr - 1) . " <br />
		Total Pembelian : Rp " . uang($totalInvoice) . "</h2><br />Selesai.";

		break;



	case "buatrpo1": // ===============================================================================================================
		echo "<h2>Buat RPO (Rencana Purchase Order) :: Step 1</h2>
            <form method=POST action='?module=pembelian_barang&act=buatrpo2'>

                Supplier : 
                <select name=supplierid>";
		$supplier = getSupplier();
		while ($dataSupplier = mysql_fetch_array($supplier)) {
			echo "<option value=$dataSupplier[idSupplier]>$dataSupplier[namaSupplier]::$dataSupplier[idSupplier]::$dataSupplier[alamatSupplier]</option>";
		}
		echo "  </select>


            <input type=submit value='Pilih Supplier'>
            </form>
            ";

		break;


	case "buatrpo2": // ===============================================================================================================
		echo "<h2>Buat RPO (Rencana Purchase Order) :: Step 2</h2>
            <form method=POST action='modul/js_buat_rpo.php?act=mulairpo' onSubmit=\"popupform(this, 'Buat_RPO')\">

		<input type=hidden name=supplierid value='" . $_POST['supplierid'] . "'>";

		// cari periode delivery supplier ybs
		$sql = "SELECT `interval` FROM supplier WHERE idSupplier=" . $_POST['supplierid'];
		$hasil = mysql_query($sql);
		$x = mysql_fetch_array($hasil);

		echo "
	<table>

	<tr><td>Periode delivery Supplier </td>
		<td> : <input type=text size=4 			name=periode value='" . $x['interval'] . "'> hari</td>
	</tr>

	<tr><td>Range analisa penjualan </td>
		<td> : <input type=text size=4 			name=range value='30'> hari</td>
	</tr>

	<tr><td>Jumlah pemesanan </td>
		<td> : untuk persediaan <input type=text size=4 name=persediaan value='" . $x['interval'] . "'> hari</td>
	</tr>

	</table>

            <input type=submit value='Mulai RPO'>
            </form>
            ";

		break;


	case "rposup1": // ===============================================================================================================
		echo "<h2>Buat RPO (Rencana Purchase Order) Per SUPPLIER :: Step 1</h2>
            <form method=POST action='?module=pembelian_barang&act=rposup2'>

                Supplier : 
                <select name=supplierid>";
		$supplier = getSupplier();
		while ($dataSupplier = mysql_fetch_array($supplier)) {
			echo "<option value=$dataSupplier[idSupplier]>$dataSupplier[namaSupplier]::$dataSupplier[idSupplier]::$dataSupplier[alamatSupplier]</option>";
		}
		echo "  </select>


            <input type=submit value='Pilih Supplier'>
            </form>
            ";

		break;



	case "rposup2": // ===============================================================================================================
		echo "<h2>Buat RPO (Rencana Purchase Order) Per SUPPLIER :: Step 2</h2>
            <form method=POST action='modul/js_cetak_rposup.php?&init=yes' onSubmit=\"popupform(this, 'RPO')\">

		<input type=hidden name=supplierid value='" . $_POST['supplierid'] . "'>";
		// echo "Supplier ID = {$_POST['supplierid']}";
		// cari periode delivery supplier ybs
		$sql = "SELECT `interval`, namaSupplier FROM supplier WHERE idSupplier=" . $_POST['supplierid'];
		$hasil = mysql_query($sql);
		$x = mysql_fetch_array($hasil);
		?>

		<script>
			function RecalcTotal(totalsementara) {
				var total = 0;
				var periode = parseInt(document.getElementById("periode").value);
				var tibagudang = parseInt(document.getElementById("tibagudang").value);
				var tibatoko = parseInt(document.getElementById("tibatoko").value);

				total = periode + tibagudang + tibatoko;

				document.getElementById("persediaan").value = total;
			}
		</script>

		<?php
		echo "
	<table>

	<tr><td>Range analisa penjualan </td>
		<td> : <input type=text size=4 			name=range value='30'> hari</td>
	</tr>

	<tr><td>Buffer Stock </td>
		<td> : <input type=text size=4 			name=buffer value='30'> %</td>
	</tr>

	<tr><td></td>
		<td>Periode delivery Supplier </td>
		<td> : <input type=text size=4 			name=periode 		id=periode		value='" . $x['interval'] . "' readonly> hari</td>
	</tr>

	<tr><td></td>
		<td>Pesanan tiba di Gudang</td>
		<td> : <input type=text size=4 			name=tibagudang 	id=tibagudang	value='2' onBlur='RecalcTotal(1)'> hari</td>
	</tr>

	<tr><td></td>
		<td>Pesanan tiba di Toko</td>
		<td> : <input type=text size=4 			name=tibatoko 		id=tibatoko		value='3'  onBlur='RecalcTotal(1)'> hari</td>
	</tr>



	<tr><td>Jumlah pemesanan </td>
		<td> : untuk persediaan </td>
		<td> : <input type=text size=4 			name=persediaan 	id=persediaan	value='" . ($x['interval'] + 2 + 3) . "'> hari</td>
	</tr>

	</table>

            <input type=submit value='Mulai RPO'>
            
            <input type=hidden name=namasupplier value='" . $x['namaSupplier'] . "'>
            </form>
            ";

		break;
} // switch($_GET[act]){ =======================================================================================
// ========================================================================================================================================
//HS form input "tambah barang"  -- awalnya hidden, baru akan muncul jika tombol "Tambah Barang" dipencet
//HS ditaruh paling bawah agar tidak mengacaukan form yang lainnya

echo "\n\n";
?>	

<div id="layer1">
	<div id="layer1_handle">			
		<a href="#" id="close">[ X ]</a>
		Tambah Barang
	</div>
	<div id="layer1_content">
		<form id="layer1_form" method="post" action="modul/js_tambah_barang.php">

			<?php
			$ambilSupplier = mysql_query("select * from supplier");
			$ambilKategoriBarang = mysql_query("select * from kategori_barang");
			$ambilSatuanBarang = mysql_query("select * from satuan_barang");
			$ambilRak = mysql_query("select * from rak");



			echo '
			<script>
				function RecalcHargaBarangBaru() {
					var HargaBeli = 0;
					var HargaJual = 0;
					var Subtotal 	= parseInt(document.getElementById("subtotal").value);
					var PPN 	= parseInt(document.getElementById("bppn").value);
					var JumlahBarang = parseInt(document.getElementById("tjumBarang").value);
					var PersenProfit = parseInt(document.getElementById("PersenProfit").value);

					if(Subtotal > 0){
						HargaBeli = (Subtotal / JumlahBarang);
					}

					if(Subtotal < 1){
						HargaBeli = parseInt(document.getElementById("hargaBeli").value);
					}

					// mencegah keliru input barcode di kolom JumlahBarang
					if(JumlahBarang > 2000){
						JumlahBarang = 0;
					}

					HargaJual = HargaBeli + ((HargaBeli / 100) * PPN) + ((HargaBeli / 100) * PersenProfit);

					document.getElementById("hargaBeli").value = HargaBeli;
					document.getElementById("hargaJual").value = HargaJual;
                                        document.getElementById("tjumBarang").value = JumlahBarang;

				}

			</script>';


			echo "
		          <table>
			          <tr><td>* <u>B</u>arcode</td><td> : <input type=text accesskey='b' name='barcode' id='barcode' size=30 value='$_GET[id]' tabindex=20 > 
				</td></tr>


			          <tr><td>* Nama Barang</td><td> : <input type=text name='namaBarang' size=30 maxlength=30 tabindex=21></td></tr>
			          <tr><td>* Supplier</td>
			                <td> : <select name='supplier' tabindex=22>";
			while ($supplier = mysql_fetch_array($ambilSupplier)) {
				if ($supplier[idSupplier] == $_SESSION[idSupplier]) {
					echo "<option value='$supplier[idSupplier]'>$supplier[namaSupplier]</option>";
				};
			}
			echo "</select></td></tr> 
			        <tr><td>* Kategori Barang</td>
				<td> : <select name='kategori_barang' tabindex=23>
	                            <option value='0'>- Kategori Barang-</option>";
			while ($kategori = mysql_fetch_array($ambilKategoriBarang)) {
				echo "<option value='$kategori[idKategoriBarang]'>$kategori[namaKategoriBarang]</option>";
			}
			echo "</select></td></tr>
          
				<tr><td>* Satuan Barang</td>
		                <td> : <select name='satuan_barang' tabindex=24>
	                            <option value='0'>- Satuan Barang-</option>";
			while ($satuan = mysql_fetch_array($ambilSatuanBarang)) {
				echo "<option value='$satuan[idSatuanBarang]'>$satuan[namaSatuanBarang]</option>";
			}
			echo "</select></td></tr>

                                <tr><td>* Rak</td>
                                <td> : <select name='rak' tabindex=25>
                                    <option value='0'>- Rak -</option>";
			while ($rak = mysql_fetch_array($ambilRak)) {
				echo "<option value='$rak[idRak]'>$rak[idRak] :: $rak[namaRak]</option>";
			}
			echo "</select></td></tr>



			<tr background=#666666><td>Subtotal</td><td> : <input type=text name='subtotal' id='subtotal' value=0 tabindex=25 /></td></tr>
			<tr background=#666666><td>* Jumlah Barang</td><td> : <input type=text name='jumBarang' id='tjumBarang' value=1 tabindex=26 /></td></tr>
			<tr background=#666666><td>PPN </td><td> : <input type=text name='ppn' id='bppn' value='10' tabindex=27 /> %</td></tr>
			<tr background=#666666><td>% Profit</td><td> : <input type=text value='0' name='PersenProfit' id='PersenProfit' tabindex=28 /></td></tr>

			<tr><td>* Harga Beli Barang</td><td> : <input type=text name='hargaBeli' id='hargaBeli' value=0 tabindex=29 /> <a href='#' onclick=\"RecalcHargaBarangBaru();\" accesskey='h'>  (h) <i><b>H</b></i>itung Harga</td></tr>
			<tr><td>* Harga Jual Barang</td><td> : <input type=text name='hargaJual' id='hargaJual' value=0 tabindex=30 /></td></tr>
			<tr><td>Tanggal Expire</td><td> : <input type=text name='tglExpire' size=10 tabindex=31 />(yyyy-mm-dd)</td></tr>

			<tr><td colspan=2>&nbsp;
				<input type=hidden name=username value='$_SESSION[uname]'>
			</td></tr>


		          <tr><td colspan=2 align='right'><input type=submit value='(s) Simpan' accesskey='s' tabindex=32>&nbsp;&nbsp;&nbsp;
                            </td></tr>
          </table>
	";
			?>

		</form>
	</div>
</div>


<?php
/* CHANGELOG -----------------------------------------------------------

  1.6.0 / 20130224 : Harry Sufehmi	: Form Pemesanan Barang :
  ## bugfix: dropdown list Supplier kini menampilkan nama supplier (tadinya ID supplier)
  ## fitur : bisa output menjadi file Excel (csv)

  1.0.4 / 20110724 : Harry Sufehmi	: Input Beli @ Gudang:
  ## bugfix: tidak semua pembelian tercatat di detail_beli
  ## revisi: jika barcode sudah ada di barang, maka cetak namaBarang di database & di Invoice,
  agar user bisa membandingkan, dan mengkoreksi jika diperlukan.
  (contoh: barcode sama, tapi nama barang nya berbeda)
  1.0.3 / 20110720 : Harry Sufehmi	: fitur Pembelian - Input Beli @ Gudang
  1.0.2	: Harry Sufehmi		: kini tidak bisa keliru input barcode di kolom quantity barang
  1.0.1	: Harry Sufehmi		: perhitungan PPN dibetulkan : kurangi diskon dulu - baru kemudian hitung PPN
  # saat input nota, daftar barang kini diurut berdasarkan urutan input (FIFO)
  0.6.5  : Gregorius Arief	: initial release

  ------------------------------------------------------------------------ */
?>
