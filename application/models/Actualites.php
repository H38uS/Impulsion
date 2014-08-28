<?php

class Default_Model_Actualites extends Default_Model_AbstractBdd {

    const TABLE_NAME = "actualites";
    
    private function toFrMonth($date) {
        $mois = array("", "Janvier", "Février", "Mars", "Avril", "Mai", "Juin", "Juillet", "Août", "Septembre", "Octobre", "Novembre", "Décembre");
        return $mois[date("n", strtotime($date))];
    }
    
    private function toFrDay($date) {
        $jour = array("Dimanche", "Lundi", "Mardi", "Mercredi", "Jeudi", "Vendredi", "Samedi");
        return $jour[date("w", strtotime($date))];
    }

    /**
     *
     * @return array Les actualités sous forme de tableau. Autant de ligne que
     *  d'actualités. 
     *  Chaque ligne contient quatre éléments : 
     *      - la description [description]
     *      - une date au format Lundi 14 Septembre 2014 à 19h30 [date]
     * 
     * Sachant que l'heure de fin peut être vide.
     */
    public function getActualites() {

     
        // connexion
        $connexion = $this->connect();

        $query = "select * from " . self::TABLE_NAME . " ORDER BY date, heureDebut;";
        $result = mysql_query($query, $connexion);

        if ($result) {
            $i = 0;
            date_default_timezone_set('Europe/Paris');
            $actualites = array();
            while ($actualite = mysql_fetch_assoc($result)) {

                $actualites[$i]['description'] = $actualite['description'];
                $d = $actualite['date'];
                $actualites[$i]['date'] = $this->toFrDay($d)
                        . " "
                        . date("j", strtotime($d))
                        . " "
                        . $this->toFrMonth($d);
                                
                $deb = $actualite['heureDebut'];
                $deb = date("G", strtotime($deb))
                        . "h"
                        . date("i", strtotime($deb));
                
                $fin = $actualite['heureFin'];
                if ($fin !== "" && $fin !== null) {
                    $fin = date("G", strtotime($fin))
                            . "h"
                            . date("i", strtotime($fin));
                    $actualites[$i]['date'] .= " de " . $deb . " à " . $fin;
                } else {
                    $actualites[$i]['date'] .= " à " . $deb;
                }

                $i++;
            }
            // deco
            $this->deco($connexion);
            return $actualites;
        }

        // deco
        $this->deco($connexion);
    }

}

?>
