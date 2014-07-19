<?php
session_start();
$qui = $_SESSION['qui'];
$ID = $_SESSION['ID'];
$ID_panier = $_SESSION['ID_panier'];
if ($qui == "") header("LOCATION: index.php");
//include("connection.php");
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
  <head  >
  <meta http-equiv="content-type" content="text/html; charset=windows-1250">
  <title>Photo impulsion</title>
  <script src="jquery.js"></script>
  <link rel="stylesheet" href="styles.css" type="text/css" />
  <script language="javascript">
function R1600()
{
	var ua = navigator.userAgent.toLowerCase();
	var isAndroid = ua.indexOf("android") > -1;
	if(isAndroid) 
		{
  		var wdth = 480; //Changez cete variable pour correspondre ŕ la résolution désirée, si vous voulez forcer le navigateur ŕ afficher votre page en 1600x1200, écrivez 1600, si vous la voulez en 800x600, changez 1600 pour 800... etc...
		document.body.style.zoom = screen.width/480;
		}
}
function listDossier(rep)
{
	var posting = $.post( "query.php", { modul: 1, rep: rep } );
	posting.done(function( data ) {	$("#contenu").html(data);});
}		
function page_photo(id)
	{
	if (id == 69) tmp = 8; else tmp = 0;
	var posting = $.post( "query.php", { modul: tmp, rep: 'photos' } );
	posting.done(function( data ) {	$("#contenu").html(data);	});
	}
function valide_panier()
	{
	var posting = $.post( "query.php", { modul: 6 } );
	posting.done(function( data ) {
		$("#contenu").html(data);	
		var posting2 = $.post( "query.php", { modul: 3 } );
		posting2.done(function( data ){$("#panier").html(data);});
		});
	}
function compte()
	{
	var posting = $.post( "query.php", { modul: 8 } );
	posting.done(function( data ) {	$("#contenu").html(data);	});
	}
<?php 
	if ($ID == 69)
		{?>
function valcom()
	{
	if (confirm('photos Commandées ?'))
		{
			var posting = $.post( "query.php", { modul: 12 } );
			posting.done(function( data ) 
				{
					var posting2 = $.post( "query.php", { modul: 8 } );
					posting2.done(function( data ){$("#contenu").html(data);});
				});
		}
	}
		
function DetailCmd()
	{
	var posting = $.post( "query.php", { modul: 9 } );
	posting.done(function( data ) {	$("#contenu").html(data);	});
	}<?php } ?>
</script>

  </head>
  <body   onload="R1600();">
<div id=menu style="POSITION: fixed; top:10; left:10; width:190; height:700; z-order:2;"><center><a href=index.php>Deconnection</a><br><?php echo $qui?><br>

<?php
if ($ID == 69)
	echo "<a href='javascript:compte();'>Paniers Validés</a><br><a href='javascript:DetailCmd();'>Détails commandes</a>";
else echo "<a href='javascript:compte();'>Mon compte</a>";
 ?>
</center>
<div id=panier style="POSITION: absolute; top:80; left:2; width:184; height:616; z-order:2;"></div>
</div>
<div id=contenu style="POSITION: absolute; top:10; left:220; width:1000; height:*;z-order:2;"></div>
<script language="javascript">
<?php 
	if ($ID != 69)
		{?>
	var posting2 = $.post( "query.php", { modul: 3 } );
	posting2.done(function( data ){$("#panier").html(data);});
		<?php } ?>
page_photo(<?php echo $ID;?>);
var posting = $.post( "query.php", { modul: '3b' } );
	posting.done(function( data ) {	

 posting = $.post( "query.php", { modul: 3 } );
	posting.done(function( data ) {	$("#panier").html(data);	});});
</script>
<div ID='bigtof' style="POSITION: fixed; top:20; left:230; width:980; height:680; z-order:0;"></div>
<script>$( "#bigtof" ).hide();$('#bigtof').click(function() {$( '#bigtof' ).hide();});</script>

  </body>
</html>