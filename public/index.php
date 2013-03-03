<?php
// Define path to application directory
defined('APPLICATION_PATH') || define('APPLICATION_PATH', realpath(dirname(__file__) . '/../application'));
// Define application environment
defined('APPLICATION_ENV') || define('APPLICATION_ENV', (getenv('APPLICATION_ENV') ? getenv('APPLICATION_ENV') : 'production'));
// Ensure library/ is on include_path
set_include_path(dirname(__FILE__).'/../library' 
        . PATH_SEPARATOR . dirname(__FILE__).'/../library/doctrine'
        . PATH_SEPARATOR . dirname(__FILE__).'/models'
        . PATH_SEPARATOR . dirname(__FILE__).'/models/generated'
        . PATH_SEPARATOR . get_include_path());

/** Zend_Application */
require_once 'Zend/Application.php';

// Create application, bootstrap, and run
$application = new Zend_Application(APPLICATION_ENV, APPLICATION_PATH . '/configs/application.ini');
$application->bootstrap()->run();
