<?php

/**
 * Index controller
 *
 * Main controller for this application.
 *
 */
class IndexController extends Zend_Controller_Action {

    /**
     * Default action for this controller
     *
     * @return void
     */
    public function indexAction() {
        if (!strstr($this->getFrontController()->getBaseUrl(), "index.php")) {
            $this->_redirect("index.php");
        }
        $model = new Default_Model_Actualites();
        $this->view->actualites = $model->getActualites();
    }

}
