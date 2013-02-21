<?php
/* mod_rak.php ------------------------------------------------------
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
    // Tampil Rak Barang
    default:
        echo "<h2>Tambah Rak Barang</h2>
              <form method=POST action='./aksi.php?module=rak&act=input'>
              <table>
                <tr><td>Tambah Rak</td><td> : <input type=text name='namaRak' size=30></td></tr>
                <tr><td colspan=2 align=right><input type=submit value='Simpan'>&nbsp;&nbsp;&nbsp;
                                <input type=reset value='Batal'></td></tr>
              </table>
               </form>
              <br/>
              <h2>Data Rak Barang</h2>
              <table class=tableku>
              <tr><th>no</th><th>Rak</th><th>aksi</th></tr>";
                $tampil=mysql_query("SELECT * from rak");
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
                         <td class=td>$r[namaRak]</td>
                         <td class=td><a href=?module=rak&act=editrak&id=$r[idRak]>Edit</a> |
                                   <a href=./aksi.php?module=rak&act=hapus&id=$r[idRak]>Hapus</a>
                         </td></tr>";
                  $no++;
                }
                echo "</table>
                <p>&nbsp;</p>
                <a href=javascript:history.go(-1)><< Kembali</a>";
        break;

    case "editrak":
        $edit = mysql_query("select * from rak where idRak = '$_GET[id]'");
        $data = mysql_fetch_array($edit);
        echo "<h2>Edit Rak Barang</h2>
            <form method=POST action='./aksi.php?module=rak&act=update' name='editrak'>
              <input type=hidden name='idRak' value='$data[idRak]'>
              <table>
                <tr><td>Edit Rak</td><td> : <input type=text name='namaRak' size=30 value='$data[namaRak]'></td></tr>
                <tr><td colspan=2 align=right><input type=submit value='Simpan'>&nbsp;&nbsp;&nbsp;
                                <input type=button value=Batal onclick=self.history.back()></td></tr>
              </table>
               </form>
            <br/>
              <h2>Data Rak Barang</h2>
              <table class=tableku>
              <tr><th>no</th><th>rak</th><th>aksi</th></tr>";
                $tampil=mysql_query("SELECT * from rak");
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
                         <td class=td>$r[namaRak]</td>
                         <td class=td><a href=?module=rak&act=editrak&id=$r[idRak]>Edit</a> |
                                   <a href=./aksi.php?module=rak&act=hapus&id=$r[idRak]>Hapus</a>
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
