<?php

/**
 * Controller qui permet d'interroger la base de données.
 */
class BddController extends Zend_Controller_Action {

    /**
     * La connexion à la base de données.
     * 
     * @var 
     */
    protected $_connexion;

    /**
     * Vérification des droits.
     */
    public function preDispatch() {
        // checks if the user is logged in
        $auth = Zend_Auth::getInstance();
        if (!$auth->hasIdentity()
                || $auth->getIdentity()->username !== "connexion_bdd") {
            // nop... redirecting him/her...
            $this->_redirect('/index');
        }
    }
    
    /**
     * Connection à la bd si pas déjà fait.
     */
    private function connect() {
        if (!$this->_connexion) {
            $front = Zend_Controller_Front::getInstance();
            $bootstrap = $front->getParam('bootstrap');
            $options = $bootstrap->getOptions();
            $conf = $options['db'];
            $this->_connexion = mysql_connect($conf['server_name'], 
                                              $conf['login'], 
                                              $conf['pwd']);
            mysql_select_db($conf['bd_name']);
        }
    }
    
    /**
     * Affiche un tag avec sa valeur, sous forme <tagName>value</tagName>.
     * 
     * @param string $tagName Le nom du tag.
     * @param string $value Sa valeur.
     * @return string The tag.
     */
    private function buildTag($tagName, $value) {
        return "<" . $tagName . ">"
                     . $value
             . "</" . $tagName . ">";
    }
    
    /**
     * Affiche une erreur sur la sortie standard.
     * 
     * @param string $message Le message.
     */
    private function printError($message) {
        echo $this->buildTag("Error", 
                             $this->buildTag("Message", 
                                             $message));
    }

    /**
     * Moteur d'exécution des requêtes.<br/>
     * <br/>
     * Attend deux paramètres : type et request.<br/>
     * <ul>
     * <li>Type doit contenir soit "select", soit "autre".</li>
     * <li>Request doit contenir la requête à exécuter, en fonction du type.</li>
     * </ul>
     * <br/>
     * Lorsque le type vaut "autre", une balise <Impulsion> vide est retournée si
     * aucune erreur n'est survenue.<br/>
     * Sinon le retour contient une balise <Erreur>, elle-même contenant une 
     * balise <Message> décrivant l'erreur.<br/>
     * <br/>
     * S'il s'agit d'un "select", les lignes sont retournées (si pas d'erreur) avec 
     * le format suivant:<br/>
     * <Impulsion>
     *    <ColumnNames>
     *       <ColumnName>nom1</ColumnName>
     *       <ColumnName>nom2</ColumnName>
     *       ...
     *    </ColumnNames>
     *    <Row>
     *       <Column>value1</Column>
     *       ...
     *    </Row>
     *    ...
     * </Impulsion>
     * 
     */
    public function indexAction() {
        
        echo "<Impulsion>";
        
        // désactivation du layout
        $this->_helper->layout()->disableLayout();
        
        // Si la connexion est inexistante, on la crée
        $this->connect();
        
        $type  = $this->_request->getParam("type");
        $query = $this->_request->getParam("request");
        
        if ($type !== "update" && $type !== "query") {
            // type non valide
            $this->printError("Invalid type provided");
            echo "</Impulsion>";
            return;
        }
        
        $query = stripslashes($query); // TODO à voir si obligatoire...

        // On exécute la requête, quelque soit le type
        $ret = mysql_query($query, $this->_connexion);
        if (!$ret) {
            // Une erreur s'est produite
            $this->printError(mysql_error($this->_connexion));
            echo "</Impulsion>";
            return;
        }
        
        if ($type === "update") {
            // Le travail est terminé :)
            echo "</Impulsion>";
            return;
        }
        
        // On fait un select

        // On affiche le nom des colonnes dans tous les cas
        $numfields = mysql_num_fields($ret);
        $content = "";
        for ($i = 0; $i < $numfields; $i++) {
            $content .= $this->buildTag("ColumnName", mysql_field_name($ret, $i));
        }
        echo $this->buildTag("ColumnNames", $content);

        // Et les données !
        mysql_data_seek($ret, 0);
        while ($line = mysql_fetch_row($ret)) {
            $columns = "";
            for ($j = 0 ; $j < count($line); $j++) {
                $columns .= $this->buildTag("Column", $line[$j]);
            }
            $row = $this->buildTag("Row", $columns);
            echo $row;
        }
        
        echo "</Impulsion>";
    }
}
?>
