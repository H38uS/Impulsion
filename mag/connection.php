<?php
$tva = 1.196;
$host="sql.free.fr";
$nom_base="impulsion.danse";
$login="impulsion.danse";
$passe="a5501mp38";

//$host="localhost";
//$nom_base = "lauraphoto";
//$login="root";
//$passe="";
//mysql_connect($host, $login, $passe) or  die('Problème de connection a mysql');
//mysql_select_db($base) or die("ERREUR de connection"); 
	
$connexion = mysql_connect($host, $login, $passe) or  die('Problème de connection à mysql');
mysql_select_db($nom_base,$connexion) or die('Prob de DB');
	
function round_good($num,$nb_digit)
	{
		if (!is_float($num)) return $num;
		
		$num = round($num,$nb_digit);
		$l   = strlen($num);
		$ok  = 0;
		$cp  = 0;
		for ($i = 1; $i <= $l; $i++)
			{
				$c = substr($num,$i,1);
				if ($ok) $cp++;
				if ($c == ".")	$ok = 1;
			}
		if (!$ok) 
			{
				$num .= ".";
				for ($i = 0; $i < $nb_digit; $i++) $num .= "0";
			}
		else	for ($i = $cp; $i <= $nb_digit; $i++) $num .= "0";
		return $num;
	}
	
function text_to_base($t)
	{
		$t = preg_replace('/"/',"&quot;",$t);
		$t = preg_replace("/'/","´",$t);
		return $t;
	}		
	

function conv_date($d)
	{
$mois["01"] = "Janvier";
$mois["02"] = "Février";
$mois["03"] = "Mars";
$mois["04"] = "Avril";
$mois["05"] = "mai";
$mois["06"] = "Juin";
$mois["07"] = "Juillet";
$mois["08"] = "Aout";
$mois["09"] = "Septembre";
$mois["10"] = "Octobre";
$mois["11"] = "Novembre";
$mois["12"] = "Décembre";

		$d = substr($d,6,2)." ".$mois[substr($d,4,2)]." ".substr($d,0,4);
		return $d;
	}

function conv_date2($d)
	{
		$d = substr($d,6,2)."<b>/</b>".substr($d,4,2)."<b>/</b>".substr($d,0,4);
		return $d;
	}
?>