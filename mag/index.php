<?php
//include("connection.php");
session_start();
$_SESSION['qui'] = "";
session_destroy();
if (ISSET($_GET['msg'])) $msg = $_GET['msg'];
else $msg = 0; 
?><!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
  <head>
  <meta http-equiv="content-type" content="text/html; charset=windows-1250">
  <title>Photo impulsion</title>
  <link rel="stylesheet" href="styles.css" type="text/css" />
    <script language="javascript">
function R1600()
{
	var ua = navigator.userAgent.toLowerCase();
	var isAndroid = ua.indexOf("android") > -1;
	if(isAndroid) 
		{
  		var wdth = 320; //Changez cete variable pour correspondre ŕ la résolution désirée, si vous voulez forcer le navigateur ŕ afficher votre page en 1600x1200, écrivez 1600, si vous la voulez en 800x600, changez 1600 pour 800... etc...
		document.body.style.zoom = screen.width/320;
		}
}
</script>
  </head>
  <body   onload="R1600();">
    <center>
    <form method="post" action="log.php">
    <table border=0 cellpadding=5 >
	<tr>
        <td align=center colspan=4><img src='images/logob.png'><hr width=100% size=1 color=#FFFFFF></td>
      </tr>
      <tr valign=center>
        <td align=right>Mail</td><td width=10>:</td><td><input type=text size=20 maxlength=500 name=mail></td>
		<td rowspan=2 align=left><input type=image src='images/go_OUT.png' onmouseover="this.src='images/go_OVER.png';" onmouseout="this.src='images/go_OUT.png';"/></td>
      </tr>
      <tr>
        <td align=right>Pass</td><td width=10>:</td><td><input type=password size=20 maxlength=20 name=pass></td>
      </tr>
      <tr>
        <td align=center colspan=4><hr width=100% size=1 color=#FFFFFF><a href="newF.php"><img src='images/creeruncompte_OUT.png' onmouseover="this.src='images/creeruncompte_OVER.png';" onmouseout="this.src='images/creeruncompte_OUT.png';"></a><hr width=100% size=1 color=#FFFFFF><br><img src='images/taro2015c.png'></td>
      </tr>
	  <?php
	  	if ($msg == -2)
			echo "<tr><td align=center colspan=3>Erreur de creation de compte</td></tr>"; 
	  	if ($msg == -1)
			echo "<tr><td align=center colspan=3>Erreur de connection Login et/ou Pass Invalide</td></tr>"; 
	  	if ($msg == 1)
			echo "<tr><td align=center colspan=3>Pas de session active!</td></tr>"; 
	  ?>
	  </table
    </form>
    </center>
  </body>
</html>