<?php

/* index.php ------------------------------------------------------
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

?><html>
<head>
<title>Halaman Login AhadPOS</title>
<link href="../config/adminstyle.css" rel="stylesheet" type="text/css" />
</head>
<body>

  <div id="login">
		<h2>Login</h2>
    <img src="../image/login-welcome.gif" width="97" height="105" hspace="10" align="left">

<form method="POST" action="cek_login.php">
<table>
<tr><td>Username</td><td> : <input type="text" name="username"></td></tr>
<tr><td>Password</td><td> : <input type="password" name="password"></td></tr>
<tr><td colspan="2">&nbsp;</td></tr>
<tr><td colspan="2" align="center"><input type="submit" value="Login"></td></tr>
</table>
</form>

<p>&nbsp;</p>
  </div>
	

</body>
</html><?php



/* CHANGELOG -----------------------------------------------------------

 1.0.2  : Gregorius Arief		: initial release

------------------------------------------------------------------------ */

?>
