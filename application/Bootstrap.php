<?php
class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{

    public static $frontController = null;
    public static $root = '';
    public static $registry = null;

    protected $view = null;

    public function _initSetup()
    {
        $this->bootstrap('layout');
        $layout = $this->getResource('layout');
        $this->view = $layout->getView();
        $this->view->addScriptPath(APPLICATION_PATH . '/views/scripts');
        $usuario = Zend_Auth::getInstance()->getIdentity();
    }

    protected function _initAutoload()
    {
        $autoloader = new Zend_Application_Module_Autoloader(array('basePath' => APPLICATION_PATH, 'namespace' => ''));
        return $autoloader;
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

/*
    protected function _initSearch()
    {
        // setup search indexer observer on the database rows
        Model_SearchIndexer::setIndexDirectory(ROOT_DIR . '/var/search_index');
        Core_Db_Table_Row_Observable::attachObserver('Model_SearchIndexer');
    }/**/

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
