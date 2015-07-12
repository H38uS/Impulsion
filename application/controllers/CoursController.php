<?php

/**
 *
 */
class CoursController extends Zend_Controller_Action 
{  
    public function indexAction() {
        
    }
    
    public function planAction() {
        
    }

    public function planningAction() {
    	$model = new Default_Model_Groupes();
    	$this->view->groupes = $model->getPlanningArray();
    }

    public function tarifAction() {
        
    }

    public function dossierAction() {
        
    }
}
