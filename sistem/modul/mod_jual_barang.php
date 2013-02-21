<?php
/* mod_jual_barang.php ------------------------------------------------------
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

	// ambil daftar customer
	$sql="SELECT idCustomer, namaCustomer   
		FROM customer ORDER BY namaCustomer ASC";
	$namaCustomer=mysql_query($sql);

        echo "<h2>Penjualan Barang</h2>
              <form method=POST action='modul/js_jual_barang.php?act=caricustomer' onSubmit=\"popupform(this, 'jual_barang')\">
              (i) ID Customer : <select name='idCustomer' accesskey='i'>";

	while($cust = mysql_fetch_array($namaCustomer)){
		if ($cust[idCustomer]==1) {
			echo "<option value='$cust[idCustomer]' selected>$cust[namaCustomer] :: $cust[idCustomer]</option>\n";
		} else {
			echo "<option value='$cust[idCustomer]'>$cust[namaCustomer] :: $cust[idCustomer]</option>\n";
		};
	}	

	echo "
              </select><p>
		<input type=submit value='(p) Pilih Customer' name='cariCustomer' accesskey='p'/>
              </form>";


/* CHANGELOG -----------------------------------------------------------

 1.0.1 / 2010-06-03 : Harry Sufehmi		: various enhancements, bugfixes
 0.6.5		    : Gregorius Arief		: initial release

------------------------------------------------------------------------ */

?>
