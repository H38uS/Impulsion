<?php
include("connection.php");

//$tarif = 1; /// 1 euro
function CalcTarif($nb)
	{
	$montant = $nb * 3;
	if ($nb <= 10) $montant = $nb * 3; /// de 0 a 10 photos -> 3�/unite
	if ($nb >= 11 && $nb <= 20) $montant = $nb * 2.5; /// de 11 a 20 photos -> 2.5�/unite
	if ($nb >= 21) $montant = $nb * 2; /// de 11 a 20 photos -> 2.5�/unite
	return $montant;
	}

if (isset($_POST['modul'])) $modul = $_POST['modul']; else $modul = -1;

if ($modul == 0) /// listage dossier 
	{
		if (isset($_POST['rep'])) $rep = $_POST['rep']; else $rep = "";
		if ($rep != "")
			{
				echo "<div id=loading style=\"POSITION: absolute; top:2; left:2; width:48; height:48; z-order:2;\"><img src='images/loading.gif'></div>";
				echo"<script>$( \"#loading\" ).hide();</script>";
				echo "<center><span class='BIG'>$rep</span></center><hr width=100% size=2>";
				echo "<table border=0 cellpadding=0 cellspacing=0 width=100%>";
				$Trep=opendir($rep);
				$nbDossier=0;
				while ($file = readdir($Trep))
				 {
				  if($file != '..' && $file !='.')// && $file !='')
				   { 
					if(is_dir("$rep/$file"))				   	 			 				
					{$tableau[$nbDossier] = $file; 	 
					$nbDossier++;}
				   }
				 }
				closedir($Trep);	   
				rsort($tableau);
				for ($i = 0; $i < $nbDossier; $i++)
					{
						$t = utf8_encode($tableau[$i]);
						
						echo "<tr class='BIG'><td align=right>".substr($t,0,4)."</td><td width=20>&nbsp;</td><td align=left><a href=javascript:listDossier(\"photos/".str_replace("'","\\'",$t)."\"); >".substr(str_replace("_"," ",$t),4,strlen($t)-4)."</a></td></tr>\n";
					}
			}
	}
	
if ($modul == 1) /// listage photo du dossier
	{
		
		if (isset($_POST['rep'])) $rep = $_POST['rep']; else $rep = "";
		if ($rep != "")
			{
				//$rep = $rep;
				$tmp = str_replace("photos/", "",$rep);
				echo "<div style=\"POSITION: absolute; top:2; left:2; width:200; height:20; z-order:2;\"><a href='javascript:page_photo();'>Retour au Dossier photos</a></div><center><span class='BIG'>".str_replace("_"," ",$tmp)."</span></center><hr width=100% size=2></center>";
				echo "<table border=0 cellpadding=0 cellspacing=0 width=100%>";
				$Trep=opendir(utf8_decode($rep));
				$nbfile=0;
				while ($file = readdir($Trep))
				 {
				  if($file != '..' && $file !='.' && $file !='v')
				   { 			 
					  if($file != '..' && $file !='.' && $file !='')
					   { 
						if (!is_dir($file))
						 {
						  $tableau[$nbfile] = $file; $nbfile++;
						 }
					   }
				   }
				 }
				closedir($Trep);	   
				sort($tableau);
				list($widthL, $heightL, $type, $attr) = getimagesize("images/logo.png"); 
				
				$i = 0;
				
				echo "<table border=0 width=980 height=650>";
				
				foreach($tableau as $file)
					{
					$ext = strtolower(substr($file, -3, 3));
					$nom = strtolower(substr($file,0,strlen($file)-4));
					$vignette=$rep."/v/".$file;
					if ($ext != "jpg") continue;

					if ($ext == "jpg")
						{
						$photo=utf8_decode($rep."/".$file);
						list($width, $height, $type, $attr) = getimagesize("$photo");  

						if (!file_exists($vignette))
							{
							$img_new = imagecreatefromjpeg("$photo");
							$x = 150;
							$y = round(($x * $height) / $width);
							$img_mini = imagecreatetruecolor ($x, $y);
							imagecopyresampled ($img_mini,$img_new,0,0,0,0,$x,$y,$width,$height);
							imagejpeg($img_mini,utf8_decode($vignette),45); 
							imagedestroy($img_mini);
							imagedestroy($img_new); 
							}
							
						/// reduit encore la photo puis ajoute un logo ////
						/*
						if ($width > 440 || $height > 440)
							{
							$img_new = imagecreatefromjpeg("$photo");
							$logo = imagecreatefrompng("images/logo.png");
							//$size = getimagesize("$rep/$file");
							if ($width > $height)
								{
								$x = 440;
								$y = round(($x * $height) / $width);
								}
							else
								{
								$y = 440;
								$x = round(($y * $width) / $height);
								}
							$img_mini = imagecreatetruecolor ($x, $y);
							imagecopyresampled ($img_mini,$img_new,0,0,0,0,$x,$y,$width,$height);
							imagecopyresampled ($img_mini,$logo,0,0,0,0,$x,$y,$widthL,$heightL);
							imagejpeg($img_mini,"$photo",55); 
							imagedestroy($img_mini);
							imagedestroy($img_new); 
							imagedestroy($logo); 
							}*/
						
							
						if (!$i) echo "<tr>";
						echo "<td align=center><img ID='$nom' border=3 src='".utf8_encode($vignette)."'><br><img src='images/p.png' ID='p$nom'>&nbsp;<img src='images/m.png' ID='m$nom'></td>";$i++;
						if ($i == 5) {echo "</tr>"; $i = 0;}
						
						?><script>
						$("#<?php echo $nom;?>").mouseover(function() {$("#<?php echo $nom;?>").fadeTo(500,1);});
						$("#<?php echo $nom;?>").mouseout(function() {$("#<?php echo $nom;?>").fadeTo(500,0.6);});
						$("#m<?php echo $nom;?>").click(function(event) {
						
							var posting = $.post( "query.php", { modul: 4, rep: '<?php echo $rep;?>', file: '<?php echo $file;?>' } );
							posting.done(function( data ) 
											{
											var posting2 = $.post( "query.php", { modul: 3 } );
											posting2.done(function( data ){$("#panier").html(data);});
											});
							});
						$("#p<?php echo $nom;?>").click(function(event) {
							var posting = $.post( "query.php", { modul: 2, rep: '<?php echo $rep;?>', file: '<?php echo $file;?>' } );
							posting.done(function( data ) 
											{
											
											var posting2 = $.post( "query.php", { modul: 3 } );
											posting2.done(function( data ){$("#panier").html(data);});
											});
							});
						$("#<?php echo $nom;?>").click(function() 
							{
							$("#<?php echo $nom;?>").stop().animate({opacity:1},0);
							$("#bigtof").html("<br><center><img ID='bigtof2' src='<?php echo $photo;?>' border=3></center>");
							$("#bigtof").fadeTo(100,1);
							$("#bigtof").show();});
							</script><?php
						}
						
					}
				echo "</table>\n";
			}
	}
if ($modul == 2) /// Ajout dans panier + Maj
	{
		if (isset($_POST['rep']))  $rep  = $_POST['rep'];  else $rep  = "";
		if (isset($_POST['file'])) $file = $_POST['file']; else $file = "";
		session_start();
		$ID_panier = $_SESSION['ID_panier'];
		$ID = $_SESSION['ID'];
		
		/// si pas de panier selectionner regarder si il y en a un ou en creer un /////
		if (!$ID_panier)
			{
			$select  = "SELECT * FROM panier WHERE ID_cpt='$ID' AND etat='0'";
			$contenu = mysql_query($select,$connexion) or die('requete =>'.$select.'<br>error->'.mysql_error());
			$nb      = mysql_numrows($contenu);
			if ($nb > 0) /// yen a un de pas fermer /////
				{
				$ligne=mysql_fetch_array($contenu);
				$_SESSION['ID_panier']   = $ligne["ID"];
				}	
			else	
				{
				/// j'en cree un /////
				$requete_ajout = "INSERT INTO panier VALUES('','$ID','".time()."','0','".time()."')";
				mysql_query($requete_ajout) or die('Erreur SQL !'.$requete_ajout.'<br>'.mysql_error()); 
				$select  = "SELECT * FROM panier WHERE ID_cpt='$ID' AND etat='0'";
				$contenu = mysql_query($select,$connexion) or die('requete =>'.$select.'<br>error->'.mysql_error());
				$nb      = mysql_numrows($contenu);
				if ($nb 	> 0)
					{
					$ligne=mysql_fetch_array($contenu);
					$_SESSION['ID_panier']   = $ligne["ID"];
					}	
				}
			}
		$ID_panier = $_SESSION['ID_panier'];
		
		/// ajouter la photo dans le panier /////
		$select  = "SELECT * FROM art WHERE ID_panier='$ID_panier' AND rep='$rep' AND file='$file'";
		$contenu = mysql_query($select,$connexion) or die('requete =>'.$select.'<br>error->'.mysql_error());
		$nb      = mysql_numrows($contenu);
		if ($nb > 0) /// yen a deja une /////
			{
			$ligne=mysql_fetch_array($contenu);
			$ID_art   = $ligne["ID"];
			$q   = $ligne["q"] + 1;
			$requete_ajout = "UPDATE art SET q='$q' WHERE ID='$ID_art'";
			mysql_query($requete_ajout) or die('Erreur SQL !'.$requete_ajout.'<br>'.mysql_error()); 
			}	
		else /// sinon l'ajouter /////
			{
				$requete_ajout = "INSERT INTO art VALUES('','$ID_panier','$rep','$file',1)";
				mysql_query($requete_ajout) or die('Erreur SQL !'.$requete_ajout.'<br>'.mysql_error()); 
			}
	}

if ($modul == 4) /// decremente du panier + Maj
	{
		if (isset($_POST['rep']))  $rep  = $_POST['rep'];  else $rep  = "";
		if (isset($_POST['file'])) $file = $_POST['file']; else $file = "";
		session_start();
		$ID_panier = $_SESSION['ID_panier'];
		$ID = $_SESSION['ID'];
		
		/// si pas de panier selectionner regarder si il y en a un ou en creer un /////
		if (!$ID_panier)
			{
			$select  = "SELECT * FROM panier WHERE ID_cpt='$ID' AND etat='0'";
			$contenu = mysql_query($select,$connexion) or die('requete =>'.$select.'<br>error->'.mysql_error());
			$nb      = mysql_numrows($contenu);
			if ($nb > 0) /// yen a un de pas fermer /////
				{
				$ligne=mysql_fetch_array($contenu);
				$_SESSION['ID_panier']   = $ligne["ID"];
				}	
			}
		$ID_panier = $_SESSION['ID_panier'];
		if ($ID_panier != 0)
			{
				/// suppr la photo dans le panier /////
				$select  = "SELECT * FROM art WHERE ID_panier='$ID_panier' AND rep='$rep' AND file='$file'";
				$contenu = mysql_query($select,$connexion) or die('requete =>'.$select.'<br>error->'.mysql_error());
				$nb      = mysql_numrows($contenu);
				if ($nb > 0) /// yen a deja une /////
					{
					$ligne=mysql_fetch_array($contenu);
					$ID_art   = $ligne["ID"];
					$q   = $ligne["q"] - 1;
					if (!$q)
						{
						$requete_ajout = "DELETE FROM art WHERE ID = '$ID_art'";
						mysql_query($requete_ajout) or die('Erreur SQL !'.$requete_ajout.'<br>'.mysql_error()); 
						}
					else
						{
						$requete_ajout = "UPDATE art SET q='$q' WHERE ID='$ID_art'";
						mysql_query($requete_ajout) or die('Erreur SQL !'.$requete_ajout.'<br>'.mysql_error()); 
						}
					}
			}
	}
	
if ($modul == 5) /// suppr 
	{
		if (isset($_POST['ID']))  $ID  = $_POST['ID'];  else $ID = 0;
		session_start();
		$ID_panier = $_SESSION['ID_panier'];
		/// si ya un panier et un art on le suppr /////
		if ($ID_panier && $ID)
			{
				$requete_ajout = "DELETE FROM art WHERE ID = '$ID'";
				mysql_query($requete_ajout) or die('Erreur SQL !'.$requete_ajout.'<br>'.mysql_error()); 
			}
	}

if ($modul == '3b') /// cherche un panier ouvert
	{
		session_start();
		$ID_panier = $_SESSION['ID_panier'];
		$ID = $_SESSION['ID'];

		if (!$ID_panier)
			{
			$select  = "SELECT * FROM panier WHERE ID_cpt='$ID' AND etat='0'";
			$contenu = mysql_query($select,$connexion) or die('requete =>'.$select.'<br>error->'.mysql_error());
			$nb      = mysql_numrows($contenu);
			if ($nb > 0) /// yen a un de pas fermer /////
				{
				$ligne=mysql_fetch_array($contenu);
				$_SESSION['ID_panier']   = $ligne["ID"];
				}	
		}
		$ID_panier = $_SESSION['ID_panier'];
	}	
	
if ($modul == 3) /// maj affichage panier
	{
		session_start();
		$ID_panier = $_SESSION['ID_panier'];
		$ID_cpt = $_SESSION['ID'];
		
		
		if ($ID_cpt == 69)
			{
				echo "<table border=0>";
				echo "<tr valign=top class='inf'><td><img src='images/d.png'></td><td>=</td><td>Panier payé ?</td></tr>";
				echo "<tr valign=top class='inf'><td><img src='images/l.png'></td><td>=</td><td>attente commande imprimeur / <br>Photos remises ?</td></tr>"; 
				echo "<tr valign=top class='inf'><td><img src='images/check.png'></td><td>=</td><td>Commande finalisée</td></tr>"; 
				echo "</table >";
			}
		else
			{
		
		$select  = "SELECT * FROM art WHERE ID_panier='$ID_panier' ORDER BY rep,file";
		$contenu = mysql_query($select,$connexion) or die('requete =>'.$select.'<br>error->'.mysql_error());
		$nb      = mysql_numrows($contenu);
		if ($nb > 0) /// listing du panier //////
			{
			$dossier = "";
		
			echo "<table border=0 width=100%>";
			echo "<tr class='lib'><td colspan=3 align=center ><img src='images/panier.png'>N°$ID_panier</td></tr>";
			echo "<tr class='lib'><td colspan=3 align=center><a href=\"javascript:valide_panier();\">Valider le panier</a></td></tr>";
			$combien = 0;
			$i = 0;
			while ($ligne=mysql_fetch_array($contenu))
				{
				$Lrep = $ligne["rep"];
				$Lfile = $ligne["file"];
				//$nom = strtolower(substr($Lfile,0,strlen($Lfile)-4));
				$Lq = $ligne["q"];
				$ID_art = $ligne["ID"];
				$combien+=$Lq;
				if ($Lrep != $dossier)
					{
						$tmp = str_replace("photos/", "",$Lrep);
						if (strlen($tmp)>20) $tmp = substr($tmp,0,20);
						echo "<tr class='lib'><td colspan=3 align=center >$tmp</td></tr>"; 
						$dossier = $Lrep;
					}
				if (strlen($Lfile) > 8) $tmp = substr($Lfile,0,8); else $tmp = $Lfile;
				echo "<tr class='inf'><td><img id='pv$i' src='$Lrep/v/$Lfile' width=20 height=20>&nbsp;$tmp</td><td width=20>x$Lq</td><td width=20><img id='tr$i' src='images/trash.png'></td>";
?>
<script>
$("#tr<?php echo $i;?>").click(function(event) 
	{
		var posting = $.post( "query.php", { modul: 5, ID: <?php echo $ID_art;?> } );
		posting.done(function( data ) 
		{
			if (confirm('Etes vous sur de vouloire supprimer :\nDossier : <?php echo str_replace("photos/", "",$Lrep);?>\nPhoto : <?php echo $Lfile;?>\nQuantité : <?php echo $Lq;?>'))
				{
				var posting2 = $.post( "query.php", { modul: 3 } );
				posting2.done(function( data ){$("#panier").html(data);});
				}
		});
	});

$("#pv<?php echo $i;?>").click(function() 
	{
		$("#bigtof").html("<br><center><img ID='bigtof2' src='<?php echo "$Lrep/$Lfile";?>' border=3></center>");
		$("#bigtof").fadeTo(100,1);
		$("#bigtof").show();
	});		
</script>
<?php				
echo "</tr>";
	$i++;
				}
			//$combien = $combien * $tarif;
			$combien = CalcTarif($combien);
			echo "<tr class='lib'><td colspan=3 align=right>$combien €</td></tr>";
			
			echo "</table>";
			}
		//else echo "Vide !";
		}
	}
	
if ($modul == 6) /// verif panier
	{
		session_start();
		echo "<div style=\"POSITION: absolute; top:2; left:2; width:200; height:20; z-order:2;\"><a href='javascript:page_photo();'>Retour au Dossier photos</a></div>";
		$ID_panier = $_SESSION['ID_panier'];
		$select  = "SELECT * FROM art WHERE ID_panier='$ID_panier' ORDER BY rep,file";
		$contenu = mysql_query($select,$connexion) or die('requete =>'.$select.'<br>error->'.mysql_error());
		$nb      = mysql_numrows($contenu);
		if ($nb > 0) /// listing du panier //////
			{
			$dossier = "";
			
			echo "<table border=0 width=100%>";
			echo "<tr class='lib'><td colspan=3 align=center ><img src='images/panier.png'>N°$ID_panier</td></tr>";
			$combien = 0;
			$i = 0;
			while ($ligne=mysql_fetch_array($contenu))
				{
				$Lrep = $ligne["rep"];
				$Lfile = $ligne["file"];
				//$nom = strtolower(substr($Lfile,0,strlen($Lfile)-4));
				$Lq = $ligne["q"];
				$ID_art = $ligne["ID"];
				$combien += $Lq;
				if ($Lrep != $dossier)
					{
						$tmp = str_replace("photos/", "",$Lrep);
						echo "<tr class='lib'><td colspan=3 align=center >$tmp</td></tr>"; 
						$dossier = $Lrep;
					}
				$tmp = $Lfile;
				echo "<tr class='inf'><td><img id='Vpv$i' src='$Lrep/v/$Lfile' width=40 height=40>&nbsp;$tmp</td><td width=20>x$Lq</td><td width=20><img id='Vtr$i' src='images/trash.png'></td>";
?>
<script>
$("#Vtr<?php echo $i;?>").click(function(event) 
	{
		var posting = $.post( "query.php", { modul: 5, ID: <?php echo $ID_art;?> } );
		posting.done(function( data ) 
		{
			if (confirm('Etes vous sur de vouloire supprimer :\nDossier : <?php echo str_replace("photos/", "",$Lrep);?>\nPhoto : <?php echo $Lfile;?>\nQuantité : <?php echo $Lq;?>'))
				{
				var posting2 = $.post( "query.php", { modul: 3 } );
				posting2.done(function( data ){$("#panier").html(data);});
				var posting2 = $.post( "query.php", { modul: 6 } );
				posting2.done(function( data ){$("#contenu").html(data);});
				}
		});
	});

$("#Vpv<?php echo $i;?>").click(function() 
	{
		$("#bigtof").html("<br><center><img ID='bigtof2' src='<?php echo "$Lrep/$Lfile";?>' border=3></center>");
		$("#bigtof").fadeTo(100,1);
		$("#bigtof").show();
	});		
</script>
<?php				
echo "</tr>";
	$i++;
				}
			//$combien = $combien * $tarif;
			$combien = CalcTarif($combien);
			echo "<tr class='lib'><td colspan=3 align=right>$combien €</td></tr>";
			echo "</table>";
			echo "<img id='valide' <img type=image src='images/valide_OUT.png' onmouseover=\"this.src='images/valide_OVER.png';\" onmouseout=\"this.src='images/valide_OUT.png';\">";
			//echo "<img id='valide' src='images/valide.png'>";?>
<script language="javascript">
$("#valide").click(function(event) {
	if (confirm('Valider le panier N°<?php echo $ID_panier;?>'))
		{
			var posting = $.post( "query.php", { modul: 7, prix: <?php echo $combien;?> } );
			posting.done(function( data ) {	$("#contenu").html(data);	});
			var posting2 = $.post( "query.php", { modul: 3 } );
			posting2.done(function( data ){$("#panier").html(data);});
		}
	});
</script><?php			
			}
		else echo " Vide !";
	}
	
if ($modul == 7) /// valide panier
	{
		session_start();
		if (isset($_POST['prix']))  $prix  = $_POST['prix'];  else $prix  = "";
		echo "<div style=\"POSITION: absolute; top:2; left:2; width:200; height:20; z-order:2;\"><a href='javascript:page_photo();'>Retour au Dossier photos</a></div>";
		$ID_panier = $_SESSION['ID_panier'];
		$select  = "SELECT * FROM panier WHERE ID='$ID_panier'";
		$contenu = mysql_query($select,$connexion) or die('requete =>'.$select.'<br>error->'.mysql_error());
		$nb      = mysql_numrows($contenu);
		if ($nb > 0) /// si le panier existe //////
			{
			$requete_ajout = "UPDATE panier SET etat='1',lastdate='".time()."' WHERE ID='$ID_panier'";
			mysql_query($requete_ajout) or die('Erreur SQL !'.$requete_ajout.'<br>'.mysql_error()); 
			$_SESSION['ID_panier'] = 0;
			echo "Commande validée<br>Merci d'établir un chèque à l'ordre de l'association IMPULSION d'un montant de $prix €.<br>Et d'inscrire au dos le N° du panier ($ID_panier)";
			}
		else echo "modul 7 Vide !";
	}
	
if ($modul == 8) /// mon compte
	{
		session_start();
		$ID_panier = $_SESSION['ID_panier'];
		$ID_cpt = $_SESSION['ID'];
		if ($ID_cpt == 69) /// admin /////
			{
				echo "<table border=0 width=100%>";
				echo "<tr class='lib'><td align=center>Eleve</td><td align=center>Panier</td><td align=center>Photos</td><td align=center>Q</td><td align=center>Prix</td></td>";
				$select  = "SELECT * FROM panier where etat > 0 ORDER BY ID DESC";
				$contenu = mysql_query($select,$connexion) or die('requete =>'.$select.'<br>error->'.mysql_error());
				$nb      = mysql_numrows($contenu);
				if ($nb > 0) /// listing des paniers validé/attente paiement //////
					{
					while ($ligne=mysql_fetch_array($contenu))
						{
						$ID = $ligne["ID"];
						$etat = $ligne["etat"];
						$ID_cpt_panier = $ligne["ID_cpt"];
						$date     = date('Y-m-d H:i',$ligne["date"]);
						$lastdate = date('Y-m-d H:i',$ligne["lastdate"]);
						$Nom = "-";
						/// recup info cpt du panier /////
						$select  = "SELECT * FROM cpt WHERE ID='$ID_cpt_panier'";
						$contenu2 = mysql_query($select,$connexion) or die('requete =>'.$select.'<br>error->'.mysql_error());
						if (mysql_numrows($contenu2) > 0)
							{
							while ($ligne2=mysql_fetch_array($contenu2)) $Nom = $ligne2["nom"];
							}	
						
						echo "<tr class='lib'><td align=center>$Nom</td><td align=center>N°$ID</td>";
						if ($etat == 4) echo "<td align=center colspan=3><img src='images/check.png'></td></tr>";
						else 	echo "<td align=center colspan=3></td></tr>";
						
						if ($etat > 0 && $etat != 4)
							{
								$select  = "SELECT * FROM art WHERE ID_panier='$ID' ORDER BY rep,file";
								$contenu2 = mysql_query($select,$connexion) or die('requete =>'.$select.'<br>error->'.mysql_error());
								$nbArt      = mysql_numrows($contenu2);
								if ($nbArt > 0) /// listing du panier //////
									{
									$dossier = "";
									$combien = 0;
									while ($ligne2=mysql_fetch_array($contenu2))
										{
										$Lrep   = $ligne2["rep"];
										$Lfile  = $ligne2["file"];
										$Lq     = $ligne2["q"];
										$ID_art = $ligne2["ID"];
										$combien+=$Lq;
										if ($Lrep != $dossier)
											{
												$tmp = str_replace("photos/", "",$Lrep);
												echo "<tr class='inf2'><td colspan=5 align=center >$tmp</td></tr>"; 
												$dossier = $Lrep;
											}
										echo "<tr class='inf'><td colspan=3 align=right>$Lfile</td><td align=center>x$Lq</td><td ></td></tr>";
										}
									//$combien = $combien * $tarif;
									$combien = CalcTarif($combien);
									echo "<tr class='lib'><td colspan=3></td><td align=center>";
									if ($etat == 1) echo "<img id='p$ID' alt=\"Panier payé ?\" src='images/d.png'></td>"; /// payer ?
									if ($etat == 2) echo "en cours (attente commande imprimeur)"; /// livré ?
									if ($etat == 3) echo "<img alt=\"Photos remises ?\" id='p$ID' src='images/l.png'></td>"; /// livré ?
									echo "<td align=right>$combien €</td></tr>";
									?>
<script language="javascript">
$("#p<?php echo $ID;?>").click(function(event) {
<?php if ($etat == 1) {?>
	if (confirm('Chèque reçu pour le panier N°<?php echo $ID;?> de <?php echo $Nom;?>'))
		{
			var posting = $.post( "query.php", { modul: 10, ID: <?php echo $ID;?> } );
			posting.done(function( data ) 
				{
			var posting2 = $.post( "query.php", { modul: 8 } );
			posting2.done(function( data ){$("#contenu").html(data);});
				});
		}
<?php }if ($etat == 3) {?>
	if (confirm('les photos du panier N°<?php echo $ID;?> de <?php echo $Nom;?> ont elles été remises ?'))
		{
			var posting = $.post( "query.php", { modul: 11, ID: <?php echo $ID;?> } );
			posting.done(function( data ) 
				{
			var posting2 = $.post( "query.php", { modul: 8 } );
			posting2.done(function( data ){$("#contenu").html(data);});
				});
		}
<?php }?>
	});
</script><?php			
								}
							}
						}
				echo "</table>";
				}
			}
		else	
			{
				echo "<div style=\"POSITION: absolute; top:2; left:2; width:200; height:20; z-order:2;\"><a href='javascript:page_photo();'>Retour au Dossier photos</a></div>";
				$select  = "SELECT * FROM panier where ID_cpt='$ID_cpt'";
				$contenu = mysql_query($select,$connexion) or die('requete =>'.$select.'<br>error->'.mysql_error());
				$nb      = mysql_numrows($contenu);
				if ($nb > 0) /// listing des panier //////
					{
					$dossier = "";
					
					echo "<table border=0 width=100%>";
					echo "<tr class='lib'><td align=center>Panier</td><td align=center>Date creation</td><td align=center>Nb. de photos</td><td align=center>Prix</td><td align=center>Etat</td></td>";
					while ($ligne=mysql_fetch_array($contenu))
						{
						$ID = $ligne["ID"];
						$date     = date('Y-m-d H:i',$ligne["date"]);
						$lastdate = date('Y-m-d H:i',$ligne["lastdate"]);
						$etat = $ligne["etat"];
						if ($etat == 0) $etat = "Panier en cours";
						if ($etat == 1) $etat = "Validée<br>Attente paiement";
						if ($etat == 2) $etat = "paiement recu<br>En cours";
						if ($etat == 3) $etat = "attente reception<br>En cours";
						if ($etat == 4) $etat = "Livré";
						$combien = 0;
						$select  = "SELECT * FROM art WHERE ID_panier='$ID' ORDER BY rep,file";
						$contenu2 = mysql_query($select,$connexion) or die('requete =>'.$select.'<br>error->'.mysql_error());
						$nbArt      = mysql_numrows($contenu2);
						if ($nbArt > 0) /// listing du panier //////
							{
							while ($ligne2=mysql_fetch_array($contenu2))
								{
								$Lq = $ligne2["q"];
								$combien+=$Lq;
								}
							$combien = $combien * $tarif;
							}				
						
						echo "<tr class='lib'><td align=center>$ID</td><td align=center>$date</td><td align=center>$nbArt</td><td align=center>$combien €</td><td align=center>$etat<br>$lastdate</td></td>";

						}
					}
				else echo "Pas d'historique !";
			}
	}
	
if ($modul == 9) /// cmd
	{
		session_start();
		$ID_panier = $_SESSION['ID_panier'];
		$ID_cpt = $_SESSION['ID'];
		if ($ID_cpt == 69) /// admin /////
			{
				echo "<table border=0 width=100%>";
				echo "<tr class='lib'><td align=center>Dossier / Fichier</td><td align=center>Quantité</td></td>";
					
				$select  = "SELECT * FROM art,panier WHERE panier.etat='2' AND art.ID_panier=panier.ID ORDER BY rep,file";
				$contenu2 = mysql_query($select,$connexion) or die('requete =>'.$select.'<br>error->'.mysql_error());
				//$contenu3 = $contenu2;
				$nbArt      = mysql_numrows($contenu2);
				if ($nbArt > 0) /// listing du panier //////
					{
					$dossier = "";
					while ($ligne2=mysql_fetch_array($contenu2))
						{
						$Lrep   = $ligne2["rep"];
						$Lfile  = $ligne2["file"];
						$tmp = str_replace("photos/", "",$Lrep);
						$tab[$tmp][$Lfile] = 0;
						}
					//$contenu2 = $contenu3;
					$contenu2 = mysql_query($select,$connexion) or die('requete =>'.$select.'<br>error->'.mysql_error());
					while ($ligne2=mysql_fetch_array($contenu2))
						{
						$Lrep   = $ligne2["rep"];
						$Lfile  = $ligne2["file"];
						$tmp = str_replace("photos/", "",$Lrep);
						$Lq     = $ligne2["q"];
						$tab[$tmp][$Lfile] += $Lq;
						}
					foreach ($tab as $tt => $v1) {
						foreach ($v1 as $v2 => $v3) {
							$Lrep = $tt;
							$Lfile = $v2;
							$Lq = $v3;
							if ($Lrep != $dossier)
								{
									echo "<tr class='inf2'><td align=center >$Lrep</td><td align=center ></td></tr>"; 
									$dossier = $Lrep;
								}
							echo "<tr class='inf'><td align=center >$Lfile</td><td align=center >$Lq</td></tr>"; 
		
							}
						}
					}
				echo "</table>";
				if ($nbArt > 0) 
					{
				echo "<a href=\"javascript:valcom();\">Panier concerné en commande (Imprimeur): ";
				$select  = "SELECT * FROM panier WHERE panier.etat='2'";
				$contenu2 = mysql_query($select,$connexion) or die('requete =>'.$select.'<br>error->'.mysql_error());
				$nbArt      = mysql_numrows($contenu2);
				if ($nbArt > 0) /// listing du panier //////
					{
					while ($ligne2=mysql_fetch_array($contenu2)) echo "N°".$ligne2["ID"]. " - ";
					}
				echo "</a>";
				}
			}
	}
	
if ($modul == 10) /// valide paiement panier /////
	{
		session_start();
		$ID_cpt = $_SESSION['ID'];
		if ($ID_cpt == 69) /// admin /////
			{
				if (isset($_POST['ID'])) 
					{
						$ID  = $_POST['ID']; 
					$requete_ajout = "UPDATE panier SET etat='2',lastdate='".time()."' WHERE ID='$ID'";
					mysql_query($requete_ajout) or die('Erreur SQL !'.$requete_ajout.'<br>'.mysql_error()); 
					}

			}
	}
if ($modul == 11) ///  panier livrer /////
	{
		session_start();
		$ID_cpt = $_SESSION['ID'];
		if ($ID_cpt == 69) /// admin /////
			{
				if (isset($_POST['ID'])) 
					{
						$ID  = $_POST['ID']; 
					$requete_ajout = "UPDATE panier SET etat='4',lastdate='".time()."' WHERE ID='$ID'";
					mysql_query($requete_ajout) or die('Erreur SQL !'.$requete_ajout.'<br>'.mysql_error()); 
					}

			}
	}
if ($modul == 12) ///  panier commander imprimeur /////
	{
		session_start();
		$ID_cpt = $_SESSION['ID'];
		if ($ID_cpt == 69) /// admin /////
			{
				$requete_ajout = "UPDATE panier SET etat='3',lastdate='".time()."' WHERE etat='2'";
				mysql_query($requete_ajout) or die('Erreur SQL !'.$requete_ajout.'<br>'.mysql_error()); 

			}
	}

?>