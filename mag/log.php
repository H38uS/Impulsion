<?php
include("connection.php");

$mail = $_POST['mail']; 
$pass = $_POST['pass']; 

$select  = "SELECT * FROM cpt WHERE mail='$mail' AND pass='$pass'";
$contenu = mysql_query($select,$connexion) or die('requete =>'.$select.'<br>error->'.mysql_error());
$nb      = mysql_numrows($contenu);

if ($nb > 0)
	{
	session_start();
	$ligne=mysql_fetch_array($contenu);
	$_SESSION['qui']  = $ligne["nom"];
	$_SESSION['ID']   = $ligne["ID"];
	$_SESSION['mail'] = $ligne["mail"];
	$_SESSION['ID_panier'] = 0;
	header("LOCATION: page.php");
	}
else
	{
	header("LOCATION: index.php?msg=-1");

	}
?>