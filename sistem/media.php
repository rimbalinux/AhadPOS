<?php
/* media.php ------------------------------------------------------
   	version: 1.0.2

	Part of AhadPOS : http://AhadPOS.com
	License: GPL v2
			http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
			http://vlsm.org/etc/gpl-unofficial.id.html

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License v2 (links provided above) for more details.
----------------------------------------------------------------*/

session_start();
include "../config/config.php";
if (empty($_SESSION[namauser]) AND empty($_SESSION[passuser])){
  echo "<link href='../config/adminstyle.css' rel='stylesheet' type='text/css'>
 <center>Untuk mengakses modul, Anda harus login <br>";
  echo "<a href=index.php><b>LOGIN</b></a></center>";
}
else{
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Halaman AhadPOS</title>
<link href="../config/adminstyle.css" rel="stylesheet" type="text/css" />

	<script type="text/javascript" src="../js/jquery.js"></script>
	<script type="text/javascript" src="../js/interface.js"></script>
	<script type="text/javascript" src="../js/jquery.form.js"></script>
</head>

<body>
<table width="850" align="center" class=body>
  <tr>
    <td colspan="2">
      <div id="header">      
      </div>
    </td>
  </tr>
  <tr>
    <td width="200" height="400" valign=top class=bckgrndmenu>
      
    	<div id="menu">
	      <ul>
	        <li><a href=?module=home>&#187; Home</a></li>
	        <?php include "menu.php"; ?>
	        <li><a href=logout.php>&#187; Logout</a></li>
	      </ul>
		    
 		</div>
 	  
    </td>
    <td width="650" valign=top>
      <div id="content">
		<?php include "content.php"; ?>
      </div>
    </td>
  </tr>
  <tr>
    <td colspan="2" align=right>
    	<div id="footer">
			<a href="http://ahadpos.com/"><font color=white>AhadPOS</a></a> Copyright &copy; 2011 by Rimbalinux.com ::Tim Support IT:: 
		</div>
	</td>
  </tr>
</table>
</body>
</html>

<?php
}



/* CHANGELOG -----------------------------------------------------------

 1.0.2  : Gregorius Arief		: initial release

------------------------------------------------------------------------ */


?>
