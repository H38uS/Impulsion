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
    	$todayYear = date("Y");
    	$this->view->anneeEnCours = date("m") > 7 ? $todayYear . " - " . ($todayYear+1) : ($todayYear-1) . " - " . $todayYear;
    }

    public function tarifAction() {
    	$model = new Default_Model_Groupes();
    	$this->view->groupes = $model->getPlanningArray();
    }

    public function dossierAction() {
        
    }
}
