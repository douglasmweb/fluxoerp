<?php
class Core_Plugin_ControlLayout extends Zend_Controller_Plugin_Abstract
{
    public function preDispatch(Zend_Controller_Request_Abstract $request)
    {
        $module = $request->getModuleName();
        $action = $request->getActionName();
        $layout = Zend_Layout::getMvcInstance();
        // check module and automatically set layout
        $layoutsDir = $layout->getLayoutPath();
        // check if module layout exists else use default
        if(file_exists($layoutsDir . DIRECTORY_SEPARATOR . $module . ".phtml")) {
            $layout->setLayout($module);
        } else if($action == 'create' or $action == 'edit' or $action == 'delete' or $action == 'list' or $module == 'admin'){
            $layout->setLayout('admin');
            //$layout->disableLayout();
        } else if($module == 'creator'){
            $layout->setLayoutPath(APPLICATION_PATH.'/modules/creator/layouts/scripts/');
            $layout->setLayout('layout');
        } else {
            $layout->setLayout("layout");
        }
    }
}
