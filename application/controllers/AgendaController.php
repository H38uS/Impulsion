<?php

/**
 * Agenda controller
 *
 * Handles the actuality of the association.
 *
 */
class AgendaController extends Zend_Controller_Action {

    /**
     * Default action for this controller
     *
     * @return void
     */
    public function indexAction() {
        $model = new Default_Model_Actualites();
        $this->view->actualites = $model->getAllActualites();
    }

}
