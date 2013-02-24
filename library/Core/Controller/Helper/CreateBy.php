<?php
class Core_Controller_Helper_CreatedBy
    extends Zend_Controller_Action_Helper_Abstract {
    
    public function postDispatch(){
        var_dump(get_class_methods($this->_actionController()));exit;
    }
}