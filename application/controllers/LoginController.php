<?php

/**
 * Description of LoginController
 *
 * @author Jordan
 */
class LoginController extends Zend_Controller_Action {

    public function getForm() {
        return new Default_Form_LoginForm(array('action' => $this->view->url(array('controller' => "login", "action" => "process"), "", true), 'method' => 'post'));
    }

    public function indexAction() {
        $auth = Zend_Auth::getInstance();
        if ($auth->hasIdentity()) {
            $this->_redirect('/index');
        }
        $this->view->form = $this->getForm();
    }

    public function processAction() {
        $request = $this->getRequest();
        // Vérifie que nous avons bien à faire à une requête POST
        if (!$request->isPost()) {
            $this->_redirect('login/index');
        }

        // Récupérons le formulaire et validons le
        $form = $this->getForm();
        if (!$form->isValid($request->getPost())) {
            // Entrées invalides
            $this->view->form = $form;
            return $this->render('index'); // rechargeons le formulaire
        }
        
        $vals = $form->getValues();
        $login = $vals['username'];
        $password = substr(crypt($vals['password'], '$6$$'), 4);
            
        // initialisation de la connection
        $dbAdapter = new Zend_Db_Adapter_Pdo_Sqlite(array('dbname' => 'logins'));
        // Configure une instance avec des méthodes de réglage
        $authAdapter = new Zend_Auth_Adapter_DbTable($dbAdapter);
        
        $authAdapter->setTableName('logins')
                ->setIdentityColumn('login')
                ->setCredentialColumn('password')
                ->setIdentity($login)
                ->setCredential($password);
        
        // check du login/password
	$authAuthenticate = $authAdapter->authenticate();
        if ($authAuthenticate->isValid()) {
            $storage = Zend_Auth::getInstance()->getStorage();
            $storage->write($authAdapter->getResultRowObject(null, 'password'));
            $auth = Zend_Auth::getInstance();
            $auth->getIdentity()->username = $login;
            if ($login === "connexion_bdd") {
                $this->_redirect("bdd");
            }
            $this->_redirect('livreor/suppression');
        } else {
            // Identifiants invalides
            $form->setDescription('Le login ou le mot de passe est incorrect.');
            $this->view->form = $form;
            return $this->render('index'); // rechargeons le formulaire
        }
    }

    public function logoutAction() {
        Zend_Auth::getInstance()->clearIdentity();
        $this->_redirect("/index");
    }

}

?>
