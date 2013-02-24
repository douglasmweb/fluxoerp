<?php
abstract class Core_Controller_Action extends Zend_Controller_Action
{
    protected $_redirector;
    protected $_flashMessenger = null;

    public function init()
    {
        parent::init();
        $link = new Zend_View_Helper_Url();
        $this->view->adicionar = "<a href='" . $link->url(array(
            'module' => $this->_request->getModuleName(),
            'controller' => $this->_request->getControllerName(),
            'action' => 'criar'), null, true) . "' class='adicionar'>Adicionar</a>";
            
        $this->view->titulo = "<h2>".(($this->_titulo)?$this->_titulo:"Sem Título")."</h2>";

        $this->_redirector = $this->_helper->getHelper('Redirector');
        $this->initView();
    }

    public function criarAction()
    {
        if ($this->_request->isPost())
        {
            if ($this->_form->isValid($_POST))
            {
                $data = array();
                foreach ($this->_model->info('cols') as $k => $v):
                    if ($this->_request->getParam($v))
                        $data[$v] = $this->_request->getParam($v);
                endforeach;
                $this->_model->insert($data);
            } else
            {
                $this->view->message = "O registro não foi inserido.";
            }
        }
        $this->view->form = $this->_form;
    }
    
    public function editarAction()
    {
        if ($this->_request->isPost())
        {
            if ($this->_form->isValid($_POST))
            {
                $data = array();
                foreach ($this->_model->info('cols') as $k => $v):
                    if ($this->_request->getParam($v))
                        $data[$v] = $this->_request->getParam($v);
                endforeach;
                $this->_model->update($data);
            } else
            {
                $this->view->message = "O registro não foi inserido.";
            }
        }
        $this->view->form = $this->_form;
    }

    protected function flash($message, $to)
    {
        $this->_flashMessenger->addMessage($message);
        $this->_redirector->gotoUrl($to);
    }

    public function indexAction()
    {
        $dados = $this->_model->fetchAll();
        $this->view->dados = $dados;
    }
    


    public function deletarAction()
    {
        // action body
    }

    public function postDispatch()
    {
        parent::postDispatch();

    }

}
?>