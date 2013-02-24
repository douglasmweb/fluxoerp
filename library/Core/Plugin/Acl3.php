<?php
class Core_Plugin_Acl extends Zend_Controller_Plugin_Abstract
{
    const MODULE_NO_AUTH='admin';
    private $_controller;
    private $_module;
    private $_action;
    private $_role;
    protected $_auth = null;
    protected $_acl = null;

    public function __construct(Zend_Auth $auth, Zend_Acl $acl)
    {
        $this->_auth = $auth;
        $this->_acl = $acl;
        $this->registro($this->_acl);
    }

    public function preDispatch(Zend_Controller_Request_Abstract $request)
    {
        $this->_controller = $this->getRequest()->getControllerName();
        $this->_module = $this->getRequest()->getModuleName();
        $this->_action = $this->getRequest()->getActionName();
        $redirect=true;

    }

    private function registro(Zend_Acl $_acl){
        // Recursos
        $resources = new Admin_Model_AdminResource();
        $resources = $resources->fetchAll();
        foreach ($resources as $resource)
            if (!$_acl->has($resource->nome))
                $_acl->add(new Zend_Acl_Resource($resource->nome));
        
        //Papéis
        $obj = new Admin_Model_AdminRole();
        $roles = $obj->fetchAll();
        foreach($roles as $role)
            if (!$_acl->hasRole($role->id))
                $_acl->addRole(new Zend_Acl_Role($role->id), $role->pai);
                
        return $_acl;
    }

    /**
     * Check user identity using Zend_Auth
     * 
     * @param Zend_Auth $auth
     * @return boolean
     */
    private function _isAuth(Zend_Auth $auth)
    {
    	if (!empty($auth) && ($auth instanceof Zend_Auth)) {
        	return $auth->hasIdentity();
    	} 
    	return false;	
    }



    private function auth(Zend_Acl $_acl, $perfil, $recurso, $privilegio)
    {

        // Recursos/*
        $resources = new Admin_Model_AdminResource();
        $resources = $resources->fetchAll();
        foreach ($resources as $resource):
            if (!$_acl->has($resource->nome))
                $_acl->add(new Zend_Acl_Resource($resource->nome));
        endforeach;
        /**/

        // Papéis
        // Papel - Usuário terá todos os privilégios que o visitante
        $roles = new Admin_Model_AdminRole();
        $roles = $roles->fetchAll();
        foreach ($roles as $role):

            $pais = new Admin_Model_AdminRole();
            if ($role->pai == '')
            {
                $parent = null;
            } else
            {
                $parent = $pais->find($role->pai)->current();
                $parent = $parent->id;
            }

            if (!$_acl->hasRole($role->id)):
                $_acl->addRole(new Zend_Acl_Role($role->id), $parent);
            endif;

        endforeach;
        /**/

        // Privilégios
        $_acl->allow(1);

        $privileges = new Admin_Model_AdminPrivilege();
        $privileges2 = $privileges->fetchAll();
        foreach ($privileges2 as $privilege):
            $res = $privilege->resource;
            $rol = $privilege->role;
            $pre = $privilege->privileges;
            $status = $privilege->status;
            if ($_acl->hasRole($privilege->role))
            {
                if ($status == 'allow')
                    $_acl->allow($rol, $res, $pre);
                else
                    $_acl->deny($rol, $res, $pre);
            }
        endforeach;

        // Administrador tem acesso a tudo!
        $arrayResources = $_acl->getResources();

        Zend_Registry::set('acl', $_acl);

        if (!$this->_acl->isAllowed($perfil, $recurso, $privilegio))
        {
            $this->_request->setModuleName('default');
            $this->_request->setControllerName('index');
            $this->_request->setActionName('index');
        }
    }
}
