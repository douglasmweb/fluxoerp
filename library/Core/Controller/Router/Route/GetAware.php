<?php
class Core_Controller_Router_Route_GetAware extends
        Zend_Controller_Router_Route
{
    public static function getInstance(Zend_Config $config)
    {
        var_dump('teste');exit;
        $reqs = ($config->reqs instanceof Zend_Config)
                ? $config->reqs->toArray() : array();
        $defs = ($config->defaults instanceof Zend_Config)
                ? $config->defaults->toArray() : array();
        return new self($config->route, $defs, $reqs);
    }

    public function match($path)
    {
        foreach ($_GET as $k => $v) {
            if (is_array($v)) {
                $v = implode(',', $v);
            }
            $path .= "{$this->_urlDelimiter}{$k}{$this->_urlDelimiter}{$v}";
        }
        parent::match($path);
    }
}
