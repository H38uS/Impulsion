<?php
include("connection.php");

if (ISSET($_POST['mail'])) $mail = $_POST['mail'];
else header("LOCATION: index.php");
if (ISSET($_POST['pass'])) $pass = $_POST['pass'];
else header("LOCATION: index.php");
if (ISSET($_POST['nom'])) $nom = $_POST['nom'];
else header("LOCATION: index.php");

/// verif si il n'y a pas deja ce mail existant ////
$select  = "SELECT * FROM cpt WHERE mail='$mail'";
$contenu = mysql_query($select,$connexion) or die('requete =>'.$select.'<br>error->'.mysql_error());
$nb      = mysql_numrows($contenu);

if ($nb)
	{
		mysql_free_result($contenu);
		header("LOCATION: newF.php?msg=-1");
	}
else
	{
		$requete_ajout = "INSERT INTO cpt VALUES('','$mail','$pass','$nom')";
		mysql_query($requete_ajout) or die('Erreur SQL !'.$requete_ajout.'<br>'.mysql_error()); 
		
		$select  = "SELECT * FROM cpt WHERE mail='$mail' AND pass='$pass'";
		$contenu = mysql_query($select,$connexion) or die('requete =>'.$select.'<br>error->'.mysql_error());
		$nb      = mysql_numrows($contenu);
		
		if ($nb 	> 0)
			{
			session_start();
			$ligne=mysql_fetch_array($contenu);
			$_SESSION['qui']  = $ligne["nom"];
			$_SESSION['ID']   = $ligne["ID"];
			$_SESSION['mail'] = $ligne["mail"];
			$_SESSION['ID_panier'] = 0;
			}
		header("LOCATION: page.php");
	}
//header("LOCATION: index.php?msg=-2");
?><a href="index.php">Retour</A>