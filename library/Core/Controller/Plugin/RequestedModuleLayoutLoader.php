<?php
class Core_Controller_Plugin_RequestedModuleLayoutLoader extends Zend_Controller_Plugin_Abstract
{
    public function preDispatch(Zend_Controller_Request_Abstract $request)
    {
        $config = Zend_Controller_Front::getInstance()->getParam('bootstrap')->getOptions();
        $moduleName = $request->getModuleName();
var_dump($config);
        if (isset($config[$moduleName]['resources']['layout']))
        {
            var_dump($config[$moduleName]['resources']['layout']);die;
            Zend_Layout::startMvc($config[$moduleName]['resources']['layout']);
        }
    }
}
