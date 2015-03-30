<?php

/**
 * Application bootstrap
 * 
 * @uses    Zend_Application_Bootstrap_Bootstrap
 * @package QuickStart
 */
class Bootstrap extends Zend_Application_Bootstrap_Bootstrap {

    /**
     * Bootstrap autoloader for application resources
     * 
     * @return Zend_Application_Module_Autoloader
     */
    protected function _initAutoload() {
        $autoloader = new Zend_Application_Module_Autoloader(array(
                    'namespace' => 'Default',
                    'basePath' => dirname(__FILE__),
                ));

        // To allow error display 
        error_reporting(E_ALL | E_STRICT);
        ini_set('display_errors', 1);

        // Databases init
        /*
        $config =
                new Zend_Config_Ini(APPLICATION_PATH . '/configs/dbconfig.ini', 'general');
        $registry = Zend_Registry::getInstance();
        $registry->set('config', $config);

        $db = Zend_Db::factory($config->db);
        Zend_Db_Table::setDefaultAdapter($db);*/
                
        // session init
        Zend_Session::start();
        
        return $autoloader;
    }

    /**
     * Defines the constant ROOT_TO_PUBLIC if it's not set yet
     * This constant contains the path between the Web server root directory
     *    and the public directory. (could be empty)
     * 
     * @return void
     */
    protected function _initPublicPath() {
        // We define the constant only if it doesn't exist yet...
        if (!defined('ROOT_TO_PUBLIC')) {
        	$aConfig = $this->getOptions();
            define('ROOT_TO_PUBLIC', $aConfig["root2public"]);
        }
    }

    /**
     * Bootstrap the view doctype
     * 
     * @return void
     */
    protected function _initDoctype() {
        $this->bootstrap('view');
        $view = $this->getResource('view');
        // helper
        $view->addHelperPath('My/Helper/', 'My_Helper');
        $view->doctype('XHTML1_STRICT');
    }

}
