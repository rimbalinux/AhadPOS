<?php   
/* js_cari_barang.php ------------------------------------------------------
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


?>

<SCRIPT TYPE="text/javascript">
<!--
function targetopener(mylink, closeme, closeonly)
{
if (! (window.focus && window.opener))return true;
window.opener.focus();
if (! closeonly)window.opener.location.href=mylink.href;
if (closeme)window.close();
return false;
}
//-->
</SCRIPT>


<?php

	$caller = $_GET[caller];

	echo "

		<link href='../../config/adminstyle.css' rel='stylesheet' type='text/css' />

			<form method=post action='$caller.php?act=caricustomer&action=tambah'>

                    <table>
                        <tr>
		";

	//echo "<td>Barcode</td><td>: <select name='barcode' id='barcode1'>";

	// ambil daftar barang
	//$sql="SELECT namaBarang,barcode,hargaJual   
	//	FROM barang FORCE INDEX (barcode) ORDER BY barcode ASC";
	//$namaBarang=mysql_query($sql);
	//while($brg = mysql_fetch_array($namaBarang)){
	//	echo "<option value='$brg[barcode]'>$brg[barcode] - $brg[namaBarang] - Rp ".number_format($brg[hargaJual],0,',','.')."</option>\n";
	//}	
	//echo "</select> </td> <td><input type=submit name=PilihBarcode value='Pilih Barcode' onClick=\"return targetopener(this,true)\">";

	echo "
		</td></tr>
                </table>
	";

	//$sql = "SELECT * FROM barang WHERE namaBarang LIKE '%".$_POST[namabarang]."%' ORDER BY namaBarang ASC";
	//$sql = "SELECT * FROM barang WHERE match(namaBarang) against ('+\"".$_POST[namabarang]."\"' in boolean mode) ORDER BY namaBarang ASC ";
	$sql = "SELECT * FROM barang WHERE match(namaBarang) against ('".$_POST[namabarang]."' in boolean mode) ORDER BY namaBarang ASC ";
	//echo $sql;
        $query = mysql_query($sql);

	echo "<table>
		<tr><th>Barcode</th><th>Nama Barang</th><th>Harga</th><th>Pilih</th></tr>
	";
                while ($data=mysql_fetch_array($query)){
                    //untuk mewarnai tabel menjadi selang-seling
                    if(($no % 2) == 0){
                        $warna = "#EAF0F7";
                    }
                    else{
                        $warna = "#FFFFFF";
                    }
                    echo "<tr bgcolor=$warna>";
                    echo "<td>&nbsp; $data[barcode] &nbsp;</td><td>&nbsp; $data[namaBarang] &nbsp;</td>
                        <td align=right>&nbsp; ".number_format($data[hargaJual],0,',','.')." &nbsp;</td>
			<td>&nbsp; [<a href='$caller.php?act=caricustomer&action=tambah&barcode=$data[barcode]' onClick=\"return targetopener(this,true)\"> Pilih </a>] 
			</td>
                        </tr>";
                    $tot_pembelian += $total;
                    $no++;
                }
	echo "</table>";



?>

			</form>


<?php

/* CHANGELOG -----------------------------------------------------------

 1.0.1 / 2010-11.22 : Harry Sufehmi		: $_GET[caller] enable this script to be called from various module 
							and return the result back properly
 1.0.1 / 2010-06-03 : Harry Sufehmi		: various enhancements, bugfixes
 0.7.5		    : Harry Sufehmi		: initial release

------------------------------------------------------------------------ */
?>
