<?php

function deleteUser($user) {
    // Crée une connexion de base de données SQLite en mémoire
    require_once 'Zend/Db/Adapter/Pdo/Sqlite.php';
    $dbAdapter = new Zend_Db_Adapter_Pdo_Sqlite(array('dbname' => 'logins'));

    // Construit la requête pour insérer une ligne pour laquelle
    // l'authentification pourra réussir
    $sqlInsert = 'DELETE FROM logins WHERE login="' . $user . '"';
    // Insertion des données
    $dbAdapter->query($sqlInsert);
    echo "done";
}

?>
