<?php
class Zend_Controller_Action_Helper_Admin extends Zend_Controller_Action_Helper_Abstract
{

    public $pluginLoader;
    protected $_model = null;
    protected $_config = null;
    protected $_endereco = null;

    public function __construct()
    {
        // TODO Auto-generated Constructor
        $this->pluginLoader = new Zend_Loader_PluginLoader();
        $this->_config = new Admin_Model_Configuracao();
        $this->_model = new Model_Users();
        $this->_endereco = new Model_Users();
    }

    function admin()
    {
        $config = $this->_config->find(1)->current();
        if($config != null){
        $user = $this->_model->find($config->administrador)->current();
        return $user;
        } else {
            throw new Zend_Controller_Action_Exception('Banco de dados sem registro de configuração.');
        }
    }
    
    function endereco($num = 1)
    {
        $admin = $this->admin();

        if($admin != null){
        $endereco = new Model_Endereco();
        $end = $admin->findDependentRowset($endereco,'Enderecos');
        $result = $end->getRow($num);
        //$cidade = $result->findParentRow('Model_Cidade','Cidade');
        //$result->cidade = $cidade->nome;
        return $result;
        } else {
            throw new Zend_Controller_Action_Exception('Banco de dados sem registro de configuração.');
        }
    }
    
    function configuracao()
    {
        $config = $this->_config->find(1)->current();
        if($config == null){
            throw new Zend_Controller_Action_Exception('Banco de dados sem registro de configuração.');
        }
        return $config;
    }
}
