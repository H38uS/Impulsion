<?php

class Default_Model_Messages {
  
    protected $_connexion;
    protected $_conf;
    
    const TABLE_NAME = "messages";
    
    public function Default_Model_Messages() {
        $front = Zend_Controller_Front::getInstance();
        $bootstrap = $front->getParam('bootstrap');
        $options = $bootstrap->getOptions();
        $this->_conf = $options['db'];
    }
    
    /**
     * Connection à la bd si pas déjà fait 
     */
    private function connect() {
        if (!$this->_connexion) {
            $this->_connexion = mysql_connect($this->_conf['server_name'],
                    $this->_conf['login'], $this->_conf['pwd']);
            mysql_select_db($this->_conf['bd_name']);
        }
    }
    
    /**
     * Deco de la bd si la connexion existe 
     */
    private function deco() {
        if ($this->_connexion)
            mysql_close($this->_connexion);
    }
    
    // /!\ Insérer les dates au format américain, ie année/mois/jour
    public function ajouter($auteur, $date, $commentaire) {
        // connexion
        $this->connect();
        $query = sprintf("INSERT INTO " . self::TABLE_NAME 
                . "(Auteur, Commentaire, Date) VALUES ('%s', '%s', '%s');", 
                mysql_real_escape_string($auteur), 
                mysql_real_escape_string($commentaire),
                mysql_real_escape_string($date));
        mysql_query($query, $this->_connexion);
        // deco
        $this->deco();
    }

    public function supprimer($id) {
        // connexion
        $this->connect();
        $query = sprintf("DELETE FROM " . self::TABLE_NAME 
                . " WHERE Keyfield = '%s';", 
                mysql_real_escape_string($id));
        mysql_query($query, $this->_connexion);   
        // deco
        $this->deco(); 
    }
    
    public function getMessages() {
        // connexion
        $this->connect();
        
        $query = "select * from " . self::TABLE_NAME  . " ORDER BY Date;";
        $result = mysql_query($query, $this->_connexion);
        if ($result) {
            $i = 0;
            date_default_timezone_set('Europe/Paris');
            $messages = array();
            while ($message = mysql_fetch_assoc($result)) {
                $messages[$i] = $message;
                $messages[$i]['Date'] = date("d/m/Y", strtotime($message['Date']));
                $messages[$i]['Heure'] = date("G", strtotime($message['Date'])) 
                    . "h"
                    . date("i", strtotime($message['Date']));
                $i++;
            }
            // deco
            $this->deco();
            return array_reverse($messages);
        }
        // deco
        $this->deco();
    }
}

?>
