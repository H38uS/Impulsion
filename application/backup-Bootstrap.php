<?php

/**
 * Application bootstrap
 * 
 * @uses    Zend_Application_Bootstrap_Bootstrap
 * @package QuickStart
 */
class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{
    /**
     * Bootstrap autoloader for application resources
     * 
     * @return Zend_Application_Module_Autoloader
     */
    protected function _initAutoload()
    {
        $autoloader = new Zend_Application_Module_Autoloader(array(
            'namespace' => 'Default',
            'basePath'  => dirname(__FILE__),
        ));
        
        // To allow error display 
	    error_reporting(E_ALL|E_STRICT) ;
        ini_set('display_errors',1) ;
        
        // Databases init
		$config =
			new Zend_Config_Ini(APPLICATION_PATH . '/configs/dbconfig.ini', 'general');
		$registry = Zend_Registry::getInstance();
		$registry->set('config', $config);

		$db = Zend_Db::factory($config->db);
		Zend_Db_Table::setDefaultAdapter($db);
       
        return $autoloader;
    }

    /**
	 * Defines the constant ROOT_TO_PUBLIC if it's not set yet
	 * This constant contains the path between the Web server root directory
	 *    and the public directory. (could be empty)
	 * 
	 * @return void
	 */    
    protected function _initPublicPath()
    {
	    // We define the constant only if it doesn't exist yet...
		if (!defined('ROOT_TO_PUBLIC')) {
			
			// building the real public path
			$fold = explode("\\", APPLICATION_PATH);
			$public_path = "";
			for ($i=0; $i<count($fold)-1; $i++) {
				$public_path .= $fold[$i]."/";
			}
			$public_path .= "public";
			
			// Web server root directory		 
			$doc_root = getenv("DOCUMENT_ROOT");
			
			$miss_path = "";	       
			if ($doc_root != $public_path) {
				$fold_root = explode("/", $doc_root);
				$fold_public = explode("/", $public_path); 
				for ($j=0; $j<count($fold_public); $j++) {
					if ($j<count($fold_root) && $fold_public[$j] != $fold_root[$j]) {
						echo "ERROR!";   
					}
					if ($j >= count($fold_root)) {
						$miss_path .= "/".$fold_public[$j];
					}
				}
			}
			define('ROOT_TO_PUBLIC', $miss_path);
		}
	}
    
	/**
	 * Bootstrap the different parameters given by the config file
	 * 
	 * @return void
	 */
    protected function _initConfiguration()
    {    		    
   		$fichier = APPLICATION_PATH . "/configs/config.ini";
		$tabfich=file($fichier);
		$comm_exist = false;
		$indice = 0;
		for ($i=0; $i<count($tabfich); $i++) {
			$tabfich[$i] = trim($tabfich[$i]);
			if (ereg("^//", $tabfich[$i]) || ereg("^##", $tabfich[$i]) || ereg("^!!", $tabfich[$i])) {
				// comment line
				continue;
			}
			if (ereg("=", $tabfich[$i])) {
				$param = explode("=", $tabfich[$i]);
				$name = trim($param[0]);
				Zend_Registry::getInstance()->set($name, trim($param[1]));
			}
		}
    }

    /**
     * Bootstrap the view doctype
     * 
     * @return void
     */
    protected function _initDoctype()
    {
        $this->bootstrap('view');
        $view = $this->getResource('view');
        $view->doctype('XHTML1_STRICT');
    }
}
