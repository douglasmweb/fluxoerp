<?php
class Core_Plugin_TagHead extends Zend_Controller_Plugin_Abstract{
    
    protected $_view;
    
    function dispatchLoopStartup(Zend_Controller_Request_Abstract $request){
        $viewRenderer = Zend_Controller_Action_HelperBroker::getStaticHelper('viewRenderer');
        $viewRenderer->init();
        
        $view = $viewRenderer->view;
        $this->_view = $view;
        
        $view->originalModule = $request->getModuleName();
        $view->originalController = $request->getControllerName();
        $view->originalAction = $request->getActionName();
        
        $view->doctype('XHTML1_STRICT');
        
        $prefix = 'Pessoa_View_Helper';

        
    }
}