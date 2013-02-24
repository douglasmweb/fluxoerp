<?php
class Core_Plugin_Acl extends Zend_Controller_Plugin_Abstract
{
    const MODULE_NO_AUTH = 'admin';
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
    }

    public function preDispatch(Zend_Controller_Request_Abstract $request)
    {
        $this->_controller = $this->getRequest()->getControllerName();
        $this->_module = $this->getRequest()->getModuleName();
        $this->_action = $this->getRequest()->getActionName();
        $redirect = true;

        if ($this->_module != 'default')
            $recurso = $this->_module . ':' . $this->_controller;
        else
            $recurso = $this->_controller;
        $role = '';
        $roles = new Admin_Model_AdminRole();
        $rolec = $roles->fetchAll("nome LIKE 'Visitante'");
        if ($rolec->count() > 0)
            $role = (int)$rolec->current()->id;

        $resource = new Admin_Model_AdminResource();
        $resourcec = $resource->fetchAll("nome LIKE '" . $recurso . "'");

        $action = new Admin_Model_AdminAction();
        $actionc = $action->fetchAll("nome LIKE '" . $this->_action . "'");

        if ($rolec->count() == 0 or $resourcec->count() == 0 or $actionc->count() == 0)
            $this->liberaPermissoes();

        $roles = new Admin_Model_AdminRole();
        $rolec = $roles->fetchAll("nome LIKE 'Visitante'");
        if ($rolec->count() > 0)
            $role = (int)$rolec->current()->id;
        // Pega o role do usuário
        if ($this->_auth->hasIdentity())
            $role = $this->_auth->getIdentity()->role;

        $frontendOptions = array('lifetime' => 900, 'automatic_serialization' => true);
        $backendOptions = array('cache_dir' => APPLICATION_PATH . '/../var/cache/');
        $cache = Zend_Cache::factory('Core', 'File', $frontendOptions, $backendOptions);

        if ($recurso == 'admin:permissao')
        {
            $cache->remove('ACL');
            $cache->clean(Zend_Cache::CLEANING_MODE_ALL);
        }

        if (!($data = $cache->load('ACL')))
        {
            $this->setRecursos($this->_acl);
            $this->setPapeis($this->_acl);
            $this->permitirMassa();
            $data = $this->_acl;
            $cache->save($data, 'ACL');
        }
        $this->_acl = $data;
        $this->_acl->allow($role, 'index');
        $this->_acl->allow($role, 'index', 'index');
        $this->_acl->allow($role, 'admin:index');
        $this->_acl->allow($role, 'auth');
        $this->_acl->allow(1, $recurso, $this->_action);

        Zend_Registry::set('acl', $this->_acl);
        //var_dump($this->_acl->isAllowed($role, $recurso, $this->_action));
        //var_dump($role, $recurso, $this->_action);
        if (!$this->_acl->isAllowed($role, $recurso, $this->_action))
        {
            $request->setModuleName('default');
            $request->setControllerName('index');
            $request->setActionName('index');
            $request->setDispatched(false);
        }

    }

    private function setRecursos($acl)
    {
        $this->_acl = $acl;
        // Recursos
        $resources = new Admin_Model_AdminResource();
        $resources = $resources->fetchAll();
        foreach ($resources as $resource)
            if (!$this->_acl->has($resource->nome))
                $this->_acl->add(new Zend_Acl_Resource($resource->nome));

        if (!$this->_acl->has('index'))
            $this->_acl->add(new Zend_Acl_Resource('index'));

        if (!$this->_acl->has('admin:index'))
            $this->_acl->add(new Zend_Acl_Resource('admin:index'));
    }

    private function setPapeis()
    {
        //Papéis
        $obj = new Admin_Model_AdminRole();
        $roles = $obj->fetchAll();
        foreach ($roles as $role)
            if (!$this->_acl->hasRole($role->id))
                $this->_acl->addRole(new Zend_Acl_Role($role->id), ($role->pai == 0) ? null : $role->pai);
    }

    public function permitirMassa()
    {
        $regras = new Admin_Model_AdminPrivilege();
        $regras = $regras->fetchAll();
        if ($regras->count() > 0)
        {
            foreach ($regras as $k => $v)
            {
                if ($v->status != 'deny')
                {
                    $this->_acl->allow($v->role, $v->resource, $v->privileges);
                } else
                {
                    $this->_acl->deny($v->role, $v->resource, $v->privileges);
                }
            }
        } else
        {
            $this->_acl->deny($role);
        }
    }

    private function permitir($resource, $role, $privilege)
    {
        $regras = new Admin_Model_AdminPrivilege();
        $regras = $regras->fetchAll("resource = '$resource' AND role = '$role' AND privileges = '$privilege'");
        if ($regras->count() > 0)
        {
            foreach ($regras as $k => $v)
            {
                if ($v->status == 'allow')
                {
                    $this->_acl->allow($v->role, $v->resource, $v->privileges);
                } else
                {
                    $this->_acl->deny($v->role, $v->resource, $v->privileges);
                }
            }
        } else
        {
            $this->_acl->deny($role);
        }
    }

    /**
     * Checa se o usuário está logado retorna true e retorna false caso não esteja.
     * 
     * @param Zend_Auth $auth
     * @return boolean
     */
    private function _isAuth(Zend_Auth $auth)
    {
        if (!empty($auth) && ($auth instanceof Zend_Auth))
        {
            return $auth->hasIdentity();
        }
        return false;
    }

    public function liberaPermissoes()
    {
        $this->setaPermissao("/controllers");
        $this->setaPermissao("/modules/admin/controllers", 'admin');
        return "Permissões liberadas";
    }

    public function setaPermissao($caminho, $module = null)
    {

        $resour = new Admin_Model_AdminResource();
        $roles = new Admin_Model_AdminRole();
        $roles_count = $roles->fetchAll();

        if ($roles_count->count() == 0)
        {
            $insert = $roles->insert(array('nome' => 'Superadministrador', 'pai' => ''));
            $insert = $roles->insert(array('nome' => 'Administrador', 'pai' => $insert));
            $insert = $roles->insert(array('nome' => 'Usuário', 'pai' => $insert));
            $insert = $roles->insert(array('nome' => 'Visitante', 'pai' => $insert));
        }

        $privilege = new Admin_Model_AdminPrivilege();
        $actions = new Admin_Model_AdminAction();

        // Abre a pasta dos controllers
        $d = dir(APPLICATION_PATH . $caminho);

        // Lê linha por linha e checa se é um arquivo de controller
        while (false !== ($entry = $d->read()))
        {

            // Pula para a próxima linha caso seja navegador de pastas . ou ..
            if ($entry == '.' or $entry == '..')
                continue;

            // obtém o nome do controller
            $resource = (($module) ? $module . ':' : '') . str_replace('Controller.php', '', ($entry));
            
            if(strpos($resource,':'))
            {
                $resource = strtolower($resource);
            }else{
                $inflector = new Zend_Filter_Inflector(':string'); 
                $inflector->addRules(array(':string' => array('Word_CamelCaseToDash')));
                $resource = $inflector->filter(array('string'=>$resource));
                $resource = strtolower($resource);
            }
            
            // Busca esse recurso
            $result = $resour->fetchAll("nome = '$resource'");

            // Caso não exista insere no banco
            if ($result->count() == 0)
                $resour->insert(array('nome' => $resource));

            // Checa se o arquivo realmente existe
            if (file_exists($d->path . '/' . $entry))
            {

                // Abre o arquivo Controlador
                $file = file_get_contents($d->path . '/' . $entry);

                // Explode as linhas para procurar padrões
                $linhas = explode("\n", $file);
                foreach ($linhas as $k => $linha):

                    // Caso não seja a linha da declaração da função pula o resto do procedimento
                    if (!stripos($linha, 'public function'))
                        continue;

                    // Caso seja uma função porém não for ACTION pula novamente o resto do procedimento
                    if (!stripos($linha, 'Action'))
                        continue;

                    // Retira qualquer espaço em branco e também as palavras "public" e "function"
                    $linha = ltrim(str_replace('public ', '', str_replace('function ', '', str_replace('Action()', '', $linha))), " ");

                    // Checa se o recurso e a action existe
                    $obj_action = $actions->fetchAll("resource = '$resource' and nome = '$linha'");

                    // Caso não exista a insere
                    if ($obj_action->count() == 0)
                        $actions->insert(array('resource' => $resource, 'nome' => $linha));

                    foreach ($roles_count as $papel):
                        // Busca o privilegio
                        $obj_previlegio = $privilege->fetchAll("role = '$papel->id' AND resource = '$resource' AND privileges = '$linha'");
                        // Caso não exista a insere no Banco
                        if ($obj_previlegio->count() == 0)
                        {
                            $privilege->insert(array(
                                'role' => $papel->id,
                                'resource' => $resource,
                                'privileges' => $linha));
                        }
                    endforeach;
                endforeach;
            }
        }
        // Fecha a pasta
        $d->close();
    }
}
