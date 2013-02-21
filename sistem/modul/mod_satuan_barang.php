<?php
/* mod_satuan_barang.php ------------------------------------------------------
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


switch($_GET[act]){
    // Tampil Satuan Barang
    default:
        echo "<h2>Tambah Satuan Barang</h2>
              <form method=POST action='./aksi.php?module=satuan_barang&act=input'>
              <table>
                <tr><td>Tambah Satuan</td><td> : <input type=text name='namaSatuanBarang' size=30></td></tr>
                <tr><td colspan=2 align=right><input type=submit value='Simpan'>&nbsp;&nbsp;&nbsp;
                                <input type=reset value='Batal'></td></tr>
              </table>
               </form>
              <br/>
              <h2>Data Satuan Barang</h2>
              <table class=tableku>
              <tr><th>no</th><th>Satuan</th><th>aksi</th></tr>";
                $tampil=mysql_query("SELECT * from satuan_barang");
                $no=1;
                while ($r=mysql_fetch_array($tampil)){
                    //untuk mewarnai tabel menjadi selang-seling
                    if(($no % 2) == 0){
                        $warna = "#EAF0F7";
                    }
                    else{
                        $warna = "#FFFFFF";
                    }
                    echo "<tr bgcolor=$warna>";//end warna
                   echo "<td class=td>$no</td>
                         <td class=td>$r[namaSatuanBarang]</td>
                         <td class=td><a href=?module=satuan_barang&act=editsatuan&id=$r[idSatuanBarang]>Edit</a> |
                                   <a href=./aksi.php?module=satuan_barang&act=hapus&id=$r[idSatuanBarang]>Hapus</a>
                         </td></tr>";
                  $no++;
                }
                echo "</table>
                <p>&nbsp;</p>
                <a href=javascript:history.go(-1)><< Kembali</a>";
        break;
        
    case "editsatuan":
        $edit = mysql_query("select * from satuan_barang where idSatuanBarang = '$_GET[id]'");
        $data = mysql_fetch_array($edit);
        echo "<h2>Edit Satuan Barang</h2>
            <form method=POST action='./aksi.php?module=satuan_barang&act=update' name='editsatuan'>
              <input type=hidden name='idSatuanBarang' value='$data[idSatuanBarang]'>
              <table>
                <tr><td>Edit Satuan</td><td> : <input type=text name='namaSatuanBarang' size=30 value='$data[namaSatuanBarang]'></td></tr>
                <tr><td colspan=2 align=right><input type=submit value='Simpan'>&nbsp;&nbsp;&nbsp;
                                <input type=button value=Batal onclick=self.history.back()></td></tr>
              </table>
               </form>
            <br/>
              <h2>Data Satuan Barang</h2>
              <table class=tableku>
              <tr><th>no</th><th>Satuan</th><th>aksi</th></tr>";
                $tampil=mysql_query("SELECT * from satuan_barang");
                $no=1;
                while ($r=mysql_fetch_array($tampil)){
                    //untuk mewarnai tabel menjadi selang-seling
                    if(($no % 2) == 0){
                        $warna = "#EAF0F7";
                    }
                    else{
                        $warna = "#FFFFFF";
                    }
                    echo "<tr bgcolor=$warna>";//end warna
                   echo "<td class=td>$no</td>
                         <td class=td>$r[namaSatuanBarang]</td>
                         <td class=td><a href=?module=satuan_barang&act=editsatuan&id=$r[idSatuanBarang]>Edit</a> |
                                   <a href=./aksi.php?module=satuan_barang&act=hapus&id=$r[idSatuanBarang]>Hapus</a>
                         </td></tr>";
                  $no++;
                }
                echo "</table>";
        break;
}


/* CHANGELOG -----------------------------------------------------------

 1.0.1 / 2010-06-03 : Harry Sufehmi		: various enhancements, bugfixes
 0.6.5		    : Gregorius Arief		: initial release

------------------------------------------------------------------------ */

?>
