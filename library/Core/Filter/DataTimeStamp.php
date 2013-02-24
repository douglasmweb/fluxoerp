<?php
class Core_Filter_DataTimeStamp implements Zend_Filter_Interface
{
    public function filter($data)
    {
        $retorno = '';
        $dn = explode('/', $data);
        $retorno = $dn[2].'-'.$dn[1].'-'.$dn[0].' 00:00:00'; 
        return $retorno;
    }
}
