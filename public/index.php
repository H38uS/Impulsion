<?php
// Set the initial include_path. You may need to change this to ensure that 
// Zend Framework is in the include_path; additionally, for performance 
// reasons, it's best to move this to your web server configuration or php.ini 
// for production.
set_include_path(implode(PATH_SEPARATOR, array(
    realpath(dirname(__FILE__) . '/../library'),
    realpath(dirname(__FILE__) . '/../application/models'),
    realpath(dirname(__FILE__) . '/../application/forms'),
    get_include_path(),
)));

// Define path to application directory
defined('APPLICATION_PATH')
    || define('APPLICATION_PATH', realpath(dirname(__FILE__) . '/../application'));

// Define application environment
defined('APPLICATION_ENV')
    || define('APPLICATION_ENV', ($_SERVER['SERVER_ADDR'] != '127.0.0.1' ? 'production' : 'development'));

/** Zend_Application */
require_once 'Zend/Application.php';

// Create application
$application = new Zend_Application(
    APPLICATION_ENV, 
    APPLICATION_PATH . '/configs/application.ini'
);
// Create bootstrap
$application->bootstrap();

// Include of personnal path
$autoloader = Zend_Loader_Autoloader::getInstance();
$autoloader->registerNamespace('My_');

// And run
$application->run();