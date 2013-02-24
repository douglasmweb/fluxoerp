<?php

class Core_View_Helper_GetSearchResultUrl
{
    protected $_view;
    
    function setView($view)
    {
        $this->_view = $view;
    }
    
    function getSearchResultUrl($class, $id)
    {
        $id = (int)$id;
        $class = strtolower($class);
        $class = str_replace('model_','',$class);
        $url = $this->_view->url(array('controller'=>$class, 
                'action'=>'mostrar', 'id'=>$id),null,true);
        return $url;        
        
    }
}
