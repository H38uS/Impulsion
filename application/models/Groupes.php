<?php

class Default_Model_Groupes extends Default_Model_AbstractBdd {

    const TABLE_NAME = "groupes";
    
    /**
     * 
     * @param string $annees Les années de naissance
     * @return string L'âge à afficher
     */
    private function getAge($annee) {
    	
    	$todayYear = date("Y");
    	
    	// Cas trivial : une seule année de naissance.
    	if (!strpos($annee, "-")) {
    		return ($todayYear - $annee) . " ans";
    	}
    	
		// On ne termine pas par un "-", ie on a un interval.
		// Il faut récupérer les deux années, on split.    	
    	if (substr_compare ( $annee, "-", - 1, 1 ) !== 0) {
    		
    		$res = "";
    		$ans = explode("-", $annee);
    		foreach (array_reverse($ans) as $an) {
    			
    			$an = $todayYear - trim($an);
    			$res .= $an . " - ";
    			
    		}

    		// On supprime le dernier " - " et on retourne la chaine
    		return substr($res, 0, strlen($res) - 3) . " ans";
		} 
		
		// Dernier cas - On termine par "-"
		// On récupère l'année
		$age = $todayYear - substr($annee, 0, 4	);
		if ($age > 17)
			return "Adultes";
		return $age . " ans et plus";
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
				 "ORDER BY id;";
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
                
                
                $annee = $groupe['annees_naissance'];
                // L'année de naissance 
                $groupes[$i]['annees_naissance'] = $annee;
    			if (substr_compare ( $annee, "-", - 1, 1 ) === 0) {
    				// Si on termine par "-", cela signifie "et moins"
    				$groupes[$i]['annees_naissance'] = substr($annee, 0, 4) . " et moins";
				}

                // L'âge
                $groupes[$i]['age'] = $this->getAge($annee);

                // Horaire
                // Le début du cours...
                $deb = $this->formatTime($groupe['debut']);
                // ... Et la fin !
                $fin = $this->formatTime($groupe['fin']);
                $groupes[$i]['horaire'] = $deb . " - " . $fin;
                                
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
