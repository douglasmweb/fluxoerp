<?php
class Core_Plugin_ControlNavigation extends Zend_Controller_Plugin_Abstract
{
    public function preDispatch(Zend_Controller_Request_Abstract $request)
    {
        $module = $request->getModuleName();
        $path = null;

        if ($module != "default" && $module == "creator")
            $path = APPLICATION_PATH . "/modules/{$module}/configs/navigation.xml";
        else
            $path = APPLICATION_PATH . "/configs/navigation.xml";

        $view = Zend_Layout::getMvcInstance()->getView();
        if (file_exists($path))
        {
            $config = new Zend_Config_Xml($path, 'nav');
            $navigation = new Zend_Navigation($config);
            $view->navigation($navigation);
        }
    }
}
?>