<?php

/**
 *
 */
class CompagnieController extends Zend_Controller_Action {

    public function indexAction() {
        /*
          // Crée une connexion de base de données SQLite en mémoire
          require_once 'Zend/Db/Adapter/Pdo/Sqlite.php';
          $dbAdapter = new Zend_Db_Adapter_Pdo_Sqlite(array('dbname' => 'logins'));

          // Construit une requête de création de table
          $sqlCreate = 'CREATE TABLE [logins] ( '
          . '[login] VARCHAR(10) NOT NULL PRIMARY KEY, '
          . '[password] CHAR(128) NULL)';

          // Crée la table de crédits d'authentification
          $dbAdapter->query($sqlCreate);

          // Construit la requête pour insérer une ligne pour laquelle
          // l'authentification pourra réussir
          $sqlInsert = 'INSERT INTO logins (login, password) '
          . 'VALUES ("mosioj", "MdB3G8Ym1rKNhS8mD8fTXhRu2tWlHH5A406Lg5jaI5i.eQbgpk8Z3B5boV3I1QjEUjCpxmyxsMsvkQrr3WOyQ/")';
                                MdB3G8Ym1rKNhS8mD8fTXhRu2tWlHH5A406Lg5jaI5i.eQbgpk8Z3B5boV3I1QjEUjCpxmyxsMsvkQrr3WOyQ/
          // Insertion des données
          $dbAdapter->query($sqlInsert); */
        
        /*
        $dbAdapter = new Zend_Db_Adapter_Pdo_Sqlite(array('dbname' => 'logins'));
        $dbAdapter->query('INSERT INTO logins (login, password) '
          . 'VALUES ("lauramosio", "W0.k8vp2N4eMX74PhkNpeEn/uSoTiPSENEY3FvcSS0BJNGh2KWpmREb33MmexrBblSVpoOzLAdUM6BUgSE.l80")');
        
        $res = $dbAdapter->query("select l.password from logins l where l.login = 'lauramosio'");
        print_r($res->fetchAll());*/
    }

}

?>
