<?php

/**
 *
 */
class LivreorController extends Zend_Controller_Action {

    public function indexAction() {
        $table = new Default_Model_Messages();
        $this->view->messages = $table->getMessages();
    }

    public function suppressionAction() {
        $auth = Zend_Auth::getInstance();
        if (!$auth->hasIdentity()) {
            $this->_redirect('/login/index');
        }
        $table = new Default_Model_Messages();
        if ($id = $this->getRequest()->getParam('id')) {
            $table->supprimer($id);
            $this->_redirect("/livreor/suppression");
        }
        $this->view->messages = $table->getMessages();
    }

    public function newmessageAction() {
        $table = new Default_Model_Messages();
        if ($this->_request->isPost()) {
            // on vire l'html
            $escape = new My_Escape();
            $auteur = $escape->escapeHTML($this->getRequest()->getPost("auteur"));
            $commentaire = $escape->escapeHTML($this->getRequest()->getPost("commentaire"));
            date_default_timezone_set('Europe/Paris');
            $table->ajouter($auteur, date("Y/m/d G:i:s"), str_replace("\n", "<br />", $commentaire));
            $this->_redirect("/livreor/index");
        }
    }

}

?>
