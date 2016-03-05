<?php
session_start();
$_SESSION['qui'] = "";
$_SESSION['ID'] = "";
$_SESSION['mail'] = "";
session_destroy();
if (ISSET($_GET['msg'])) $msg = $_GET['msg'];
else $msg = 0; 
?><!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
  <head>
  <meta http-equiv="content-type" content="text/html; charset=windows-1250">
  <title>Photo impulsion</title>
  <link rel="stylesheet" href="styles.css" type="text/css" />
  <script src="jquery-1.11.1.min.js"></script>
  <script src="jquery.formvalidation.js"></script>

<script type="text/javascript">
	
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
function validateEmail(sEmail) {
	    var filter = /^([\w-\.]+)@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.)|(([\w-]+\.)+))([a-zA-Z]{2,4}|[0-9]{1,3})(\]?)$/;
	    if (filter.test(sEmail))  	return true;
	    else 	        			return false;
	    
	}
</script>
  </head>
  <body   onload="R1600();">
    <center>
    <form ID='myform' method="post" action="new.php">
    <table border=0>
	<tr>
        <td align=center colspan=3><img src='images/logob.png'><hr width=100% size=1 color=#FFFFFF></td>
      </tr>
      <tr>
        <td align=right>Mail</td><td width=10>:</td><td><input required="true" ID="monmail" type=text size=20 maxlength=500 name=mail><span ID='vali'><img src='images/red.png'></span></td>
      </tr>
      <tr>
        <td align=right>Pass</td><td width=10>:</td><td><input required="true" type=password size=20 maxlength=20 name=pass></td>
      </tr>
      <tr>
        <td align=right>Nom Prenom éléve</td><td width=10>:</td><td><input required="true" type=text size=20 maxlength=200 name=nom></td>
      </tr>
      <tr>
        <td align=center colspan=3><hr width=100% size=1 color=#FFFFFF><input type=image src='images/creer_OUT.png' onmouseover="this.src='images/creer_OVER.png';" onmouseout="this.src='images/creer_OUT.png';"/></td>
      </tr>
	  <?php
	  	if ($msg == -1)
			echo "<tr><td align=center colspan=3>Mail déja existant</td></tr>"; 
	  	if ($msg == 1)
			echo "<tr><td align=center colspan=3>Pas de session active!</td></tr>"; 
	  ?>
      <tr>
        <td align=center colspan=3><hr width=100% size=1 color=#FFFFFF><a href="index.php"><img src='images/acc_OUT.png' onmouseover="this.src='images/acc_OVER.png';" onmouseout="this.src='images/acc_OUT.png';"></a></td>
      </tr>
	  </table
    </form>
<script language="javascript">
var ok = false;
$(document).ready(function(){
	$('#monmail').keypress(function(){
		var sEmail = $('#monmail').val();
		ok = validateEmail(sEmail);
        if (!validateEmail(sEmail)) 	$('#vali').html("<img src='images/red.png'>"); 
		else  							$('#vali').html("<img src='images/green.png'>");
		});
		
	$("#myform").formValidation({
		alias		: "name",
		required	: "accept",
		err_list	: true
	}); 
 
	
	});


</script>

    </center>
  </body>
</html>