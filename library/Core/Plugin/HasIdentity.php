<?php
class Core_Plugin_HasIdentity extends Zend_Controller_Plugin_Abstract
{
    /*
    Nome do módulo, controlador e ação que o usuário terá acesso caso não esteja logado.
    */
    const module = 'admin';
    const controller = 'auth';
    const action = 'login';

    public function preDispatch(Zend_Controller_Request_Abstract $request)
    {
        $module = $request->getModuleName();
        $controller = $request->getControllerName();
        $action = $request->getActionName();
        // Verifica se o usuário não está logado

        if ($module == 'admin')
            if (!Zend_Auth::getInstance()->hasIdentity())
            {
                // Verifica se a requisição é diferente do permitido
                // Se for diferente rotea para a página de login
                if ($action != 'ajax')
                {
                    $request->setModuleName('admin');
                    $request->setControllerName('index');
                    $request->setActionName('login');
                }
                /**/
            }

        if ($controller == 'painel')
            if (!Zend_Auth::getInstance()->hasIdentity())
            {
                // Verifica se a requisição é diferente do permitido
                // Se for diferente rotea para a página de login
                $request->setModuleName('default');
                $request->setControllerName('index');
                $request->setActionName('acesso-negado');
            }

        if ($module == 'matrix')
            if (!Zend_Auth::getInstance()->hasIdentity())
            {
                // Verifica se a requisição é diferente do permitido
                // Se for diferente rotea para a página de login
                $request->setModuleName('matrix');
                $request->setControllerName('index');
                $request->setActionName('index');
            }
    }
}
