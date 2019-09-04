<?php

class Default_Model_Groupes extends Default_Model_AbstractBdd {

    const TABLE_NAME = "groupes";
    
    /**
     * 
     * @param string $age L'âge à afficher
     * @return string Les années de naissance
     */
    private function getAnnee($age) {
    	
    	$todayYear = date("Y");
    	$offset = date("m") > 7 ? 0 : -1;
    	
    	// Cas trivial : une seule année de naissance.
    	if (!strpos($age, "-") && !strpos($age, "+")) {
    		return ($todayYear - $age) + $offset;
    	}
    	
		// On ne termine pas par un "+", ie on a un interval.
		// Il faut récupérer les deux années, on split.    	
    	if (substr_compare ( $age, "+", - 1, 1 ) !== 0) {
    		
    		$res = "";
    		$ages = explode("-", $age);
    		foreach (array_reverse($ages) as $a) {
    			
    			$a = $todayYear - trim($a) + $offset;
    			$res .= $a . " - ";
    			
    		}

    		// On supprime le dernier " - " et on retourne la chaine
    		return substr($res, 0, strlen($res) - 3);
		} 
		
		// Dernier cas - On termine par "+"
		// On récupère l'âge
		$annee = $todayYear - substr($age, 0, -1) + $offset;
		return $annee . " et moins";
    }
	
	/**
     *
     * @return array Le planning des groupes sous forme de tableau. Autant de ligne que
     *  de groupe. 
     *  Chaque ligne contient les six éléments du tableau (âge, années naissance, le nom, jour, horaire et lieu).
     *  
     */
    public function getPlanningArray() {

        // connexion
        $connexion = $this->connect();

        $query = "select * from " . self::TABLE_NAME . " " .
				 "ORDER BY ordre;";
        $result = mysql_query($query, $connexion);

        if ($result) {

        	$i = 0;
            date_default_timezone_set('Europe/Paris');
            $groupes = array();
            
            while ($groupe = mysql_fetch_assoc($result)) {

            	// Le nom
                $groupes[$i]['nom'] = $groupe['nom'];
                
                // Le jour
                $groupes[$i]['jour'] = $groupe['jour'];

                // Le lieu
                $lieu = $groupe['lieu'];
                if ($lieu == "") {
					$lieu = "A définir";                	
                }
                $groupes[$i]['lieu'] = $lieu;

                // L'âge
                $age = $groupe['age'];
    			if (substr_compare ( $age, "+", - 1, 1 ) === 0) {
    				// Si on termine par "+", cela signifie "et plus"
    				$groupes[$i]['age'] = substr($age, 0, -1) . " ans et plus";
				} else {
				    $groupes[$i]['age'] = $age . " ans";
				}
				if ($age > 17)
				    $groupes[$i]['age'] = "Adultes";
                
				// L'année de naissance
                $groupes[$i]['annees_naissance'] = $this->getAnnee($age);

                // Horaire
                // Le début du cours...
                $deb = $this->formatTime($groupe['debut']);
                // ... Et la fin !
                $fin = $this->formatTime($groupe['fin']);
                $groupes[$i]['horaire'] = $deb . " - " . $fin;
                
                $minutes = (strtotime($groupe['fin']) - strtotime($groupe['debut'])) / 60;
                if ($minutes == 60) {
                    $minutes = "1 heure";
                }
                else if ($minutes > 60) {
                    $initial = $minutes;
                    if ($minutes > 120) {
                        $minutes = ((int) ($minutes / 60)) . " heures";
                    } else {
                        $minutes = "1 heure";
                    }
                    $minutes .= " " . ($initial % 60);
                }
                else {
                    $minutes = $minutes . " min";
                }
                
                $groupes[$i]['duree'] = $minutes;
                $groupes[$i]['tarif'] = $groupe['tarif'];

                $i++;
            }
            
            // deco
            $this->deco($connexion);
            return $groupes;
        }

        // deco
        $this->deco($connexion);
    }

}

?>
