<?php
class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{

    public static $frontController = null;
    public static $root = '';
    public static $registry = null;

    protected $view = null;

    protected function _initRegistry()
    {
        $registry = Zend_Registry::getInstance();
        return $registry;
    }

    protected function _initAutoload()
    {
        $autoloader = new Zend_Application_Module_Autoloader(array(
            'basePath' => APPLICATION_PATH, 
            'namespace' => ''));
        return $autoloader;
    }

    /**
     * Initialize Doctrine
     * @return Doctrine_Manager
     */
    public function _initDoctrine()
    {
        // include and register Doctrine's class loader
        require_once ('Doctrine/Common/ClassLoader.php');
        $classLoader = new \Doctrine\Common\ClassLoader('Doctrine', APPLICATION_PATH . '/../library/');
        $classLoader->register();

        // create the Doctrine configuration
        $config = new \Doctrine\ORM\Configuration();

        // setting the cache ( to ArrayCache. Take a look at
        // the Doctrine manual for different options ! )
        $cache = new \Doctrine\Common\Cache\ArrayCache;
        $config->setMetadataCacheImpl($cache);
        $config->setQueryCacheImpl($cache);

        // choosing the driver for our database schema
        // we'll use annotations
        $driver = $config->newDefaultAnnotationDriver(APPLICATION_PATH . '/models');
        $config->setMetadataDriverImpl($driver);

        // set the proxy dir and set some options
        $config->setProxyDir(APPLICATION_PATH . '/models/Proxies');
        $config->setAutoGenerateProxyClasses(true);
        $config->setProxyNamespace('App\Proxies');

        // now create the entity manager and use the connection
        // settings we defined in our application.ini
        $connectionSettings = $this->getOption('doctrine');
        $conn = array(
            'driver' => $connectionSettings['conn']['driv'],
            'user' => $connectionSettings['conn']['user'],
            'password' => $connectionSettings['conn']['pass'],
            'dbname' => $connectionSettings['conn']['dbname'],
            'host' => $connectionSettings['conn']['host']);
        $entityManager = \Doctrine\ORM\EntityManager::create($conn, $config);

        // push the entity manager into our registry for later use
        $registry = Zend_Registry::getInstance();
        $registry->entitymanager = $entityManager;

        return $entityManager;
    }

    public function _initSetup()
    {
        $this->bootstrap('layout');
        $layout = $this->getResource('layout');
        $this->view = $layout->getView();
        //$this->view->addScriptPath(APPLICATION_PATH . '/views/scripts');
        $usuario = Zend_Auth::getInstance()->getIdentity();
    }



    public function _initPlugins()
    {
        $frontController = Zend_Controller_Front::getInstance();
        $frontController->registerPlugin(new Core_Plugin_ControlLayout());
        $frontController->registerPlugin(new Core_Plugin_ControlNavigation());
    }

    protected function _initViewHelpers()
    {
        $view = new Zend_View();
        $viewRenderer = new Zend_Controller_Action_Helper_ViewRenderer();
        $view->addHelperPath('Zend/Dojo/View/Helper/', 'Zend_Dojo_View_Helper');
        $view->addHelperPath("ZendX/JQuery/View/Helper", 'ZendX_JQuery_View_Helper');
        $view->addHelperPath("Core/View/Helper", 'Core_View_Helper');

        $viewRenderer->setView($view);
        Zend_Controller_Action_HelperBroker::addHelper($viewRenderer);
        Zend_Controller_Action_HelperBroker::addPath('Core/Controller/Helper/');
    }

    protected function _initCustomHelpers()
    {
        ZendX_JQuery::enableView($this->view);
        Zend_Dojo::enableView($this->view);
    }

    protected function _initTranslate()
    {
        try
        {
            $translate = new Zend_Translate('Array', APPLICATION_PATH . '/languages/pt_BR/Zend_Validate.php', 'pt_BR');
            Zend_Validate_Abstract::setDefaultTranslator($translate);
            Zend_Registry::set('Zend_Translate', $translate);
        }
        catch (exception $e)
        {
            die($e->getMessage());
        }
    }

    protected function _initCurrency()
    {
        $options = $this->getOptions();
        $moeda = $options['resources']['locale']['default'];
        $locale = new Zend_Locale($moeda);
        Zend_Registry::set('Zend_Locale', $locale);

    }

}
