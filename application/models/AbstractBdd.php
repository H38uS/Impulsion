<?php

/**
 *
 * @author Jordan Mosio
 */
abstract class Default_Model_AbstractBdd {
    
    protected $_conf;

    public function Default_Model_AbstractBdd() {
        $front = Zend_Controller_Front::getInstance();
        $bootstrap = $front->getParam('bootstrap');
        $options = $bootstrap->getOptions();
        $this->_conf = $options['db'];
    }

    /**
     *
     * @param date $time L'heure de départ.
     * @return string La date formatée.
     */
    protected function formatTime($time) {
        return date("G", strtotime($time))
                . "h"
                . date("i", strtotime($time));
    }
    
    /**
     * Connection à la bd si pas déjà fait.
     * 
     * @return La connexion à la base de données.
     */
    protected function connect() {
        $connexion = mysql_connect($this->_conf['server_name'], $this->_conf['login'], $this->_conf['pwd']);
        if (!$connexion) {
            throw new Zend_Db_Adapter_Exception(
                    "Connexion à la base de données impossible. " .
                    " Veuillez patienter, le site devrait redevenir disponible d'ici peu !" .
                    " <br/>Message détaillé : " . mysql_error());
        }
            
        mysql_select_db($this->_conf['bd_name']);
        return $connexion;
    }

    /**
     * Deco de la bd si la connexion existe 
     */
    protected function deco($connexion) {
        if ($connexion)
            mysql_close($connexion);
    }    
}

?>
