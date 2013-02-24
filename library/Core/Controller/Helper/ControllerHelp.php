<?php
class Zend_Controller_Action_Helper_ControllerHelp extends Zend_Controller_Action_Helper_Abstract
{    
    /**
     * @var Zend_Loader_PluginLoader
     */
    public $pluginLoader;
    
    public function __construct()
    {
        $this->pluginLoader = new Zend_Loader_PluginLoader();
    }

    public function direct()
    {
        return var_dump($this->pluginLoader);
    }

    public function ver()
    {
        return 312;
    }
}
?>