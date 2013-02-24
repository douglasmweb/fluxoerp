<?php
class Creator_FormularioController extends Zend_Controller_Action
{
    protected $_form;
    protected $_model;
    protected $_titulo;


    public function init()
    {
        
        $this->_form = new Creator_Form_Formulario();
        $this->_model = new Creator_Model_Formulario();
        $this->_titulo = "Formulários";
        
        $link = new Zend_View_Helper_Url();
        $this->view->adicionar = "<a href='" . $link->url(array(
            'module' => $this->_request->getModuleName(),
            'controller' => $this->_request->getControllerName(),
            'action' => 'criar',
            ), null, true) . "' class='adicionar'>Adicionar</a>";

        $this->view->titulo = "<h2>" . (($this->_titulo) ? $this->_titulo : "Sem Título") . "</h2>";
    }

    public function indexAction()
    {
        $id = $this->_request->getParam('id');
        $dados = $this->_model->fetchAll();
        $this->view->dados = $dados;
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
    
    public function sincronizarAction()
    {
        $Id = $this->_request->getParam('id');
        
        $formulario = $this->_model->find($Id)->current();
        $controlador = $formulario->findParentRow('Creator_Model_Controlador','Controlador');
        $modulo = $controlador->findParentRow('Creator_Model_Modulo','Modulo');
        
        
        $campos = $formulario->findDependentRowset('Creator_Model_FormularioCampo',"Formulario");

        $model = new Manager_Engine_Model($controlador->tabela,$modulo->tabela);
        $model->criar();
        $form = new Manager_Engine_Form($controlador->tabela,$modulo->tabela,$formulario,$campos);
        $form->criar();
    }

    public function deletarAction()
    {
        $Id = $this->_request->getParam('id');
        $data = $this->_model->find($Id)->current();

        $this->_model->delete("id='$Id'");
        $this->_forward('index');

    }
}
