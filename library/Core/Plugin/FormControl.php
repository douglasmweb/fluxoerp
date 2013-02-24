<?php
class Core_Plugin_FormControl extends Zend_Controller_Plugin_Abstract
{
    public function postDispatch(Zend_Controller_Request_Abstract $request)
    {

        $module = $request->getModuleName();
        $layout = Zend_Layout::getMvcInstance();
        
        $act = $request->getActionName();
        $botao = new Zend_Form();
        $botao->getElement('enviar');
        $dubmit = $botao->getValue('enviar');
        
        var_dump($dubmit);
        

/*
        // check module and automatically set layout
        $layoutsDir = $layout->getLayoutPath();
        // check if module layout exists else use default
        
        if(file_exists($layoutsDir . DIRECTORY_SEPARATOR . $module . ".phtml")) {
            $layout->setLayout($module);
        } else {
            $layout->setLayout("layout");
        }
        /**/

    }
}
