<?php
class Core_View_Helper_BaseUrl2 extends Zend_Controller_Action_Helper_Abstract
{
    function baseUrl2()
    {
        $fc = Zend_Controller_Front::getInstance();
        return $fc->getBaseUrl();
    }
}
