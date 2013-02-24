<?php
class Creator_FormularioCampoController extends Zend_Controller_Action
{
    protected $_form;
    protected $_model;
    protected $_modelform;
    protected $_titulo;
    protected $_idform;
    
    public function init()
    {
        $this->_form = new Creator_Form_FormularioCampo();
        $this->_model = new Creator_Model_FormularioCampo();
        $this->_modelform = new Creator_Model_Formulario();
        $this->_titulo = "Campos do Formulário";
        $this->_idform = $this->_request->getParam('form');
        
        $link = new Zend_View_Helper_Url();
        $this->view->adicionar = "<a href='" . $link->url(array(
            'module' => $this->_request->getModuleName(),
            'controller' => $this->_request->getControllerName(),
            'action' => 'criar',
            'form' => $this->_idform), null, true) . "' class='adicionar'>Adicionar</a>";

        $this->view->titulo = "<h2>" . (($this->_titulo) ? $this->_titulo : "Sem Título") . "</h2>";
    }

    public function indexAction()
    {
        $dados = $this->_model->fetchAll();
        $this->view->dados = $dados;
    }

    public function criarAction()
    {
        $this->_form->getElement('formulario')->setValue($this->_idform);
        
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
                $this->view->message = "O registro foi inserido.";
                $this->_forward('index');
            }
        } else
        {
            $this->view->message = "O registro não foi inserido.";
        }
        $this->view->form = $this->_form;
    }

    public function editarAction()
    {
        $this->_form->getElement('formulario')->setValue($this->_idform);
        //var_dump($this->_request);
        $id = $this->_request->getParam('id');
        $result = $this->_model->find($id);
        $data = $result->current();

        $this->_form->populate($data->toArray());

        if ($this->_request->isPost())
        {
            if ($this->_form->isValid($_POST))
            {
                $data = array();
                foreach ($this->_model->info('cols') as $k => $v):
                    if ($this->_request->getParam($v))
                        $data[$v] = $this->_request->getParam($v);
                endforeach;

                $this->_model->update($data, "id='$id'");
                $this->view->message = "O registro foi alterado.";
                $this->_forward('index');

            } else
            {
                $this->view->message = "O registro não foi inserido.";
            }
        }
        $this->view->form = $this->_form;
    }

    public function deletarAction()
    {
        $Id = $this->_request->getParam('id');
        $data = $this->_model->find($Id)->current();

        $this->_model->delete("id='$Id'");
        $this->_forward('index');

    }
}