<?php
class Creator_ControladorController extends Zend_Controller_Action
{
    protected $_form;
    protected $_model;
    protected $_titulo;

    public function init()
    {
        $this->_form = new Creator_Form_Controlador();
        $this->_model = new Creator_Model_Controlador();
        $this->_titulo = "Controladores";
        $link = new Zend_View_Helper_Url();
        $this->view->adicionar = "<a href='" . $link->url(array(
            'module' => $this->_request->getModuleName(),
            'controller' => $this->_request->getControllerName(),
            'action' => 'criar'), null, true) . "' class='adicionar'>Adicionar</a>";

        $this->view->titulo = "<h2>" . (($this->_titulo) ? $this->_titulo : "Sem Título") . "</h2>";
    }

    public function indexAction()
    {
        $dados = $this->_model->fetchAll();
        $this->view->dados = $dados;
    }

    public function sincronizarAction()
    {
        $id = $this->_request->getParam('id');
        $form = new Creator_Form_Formulario();
        $formulario = new Creator_Model_Formulario();

        $this->view->form = $form;
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

                $Form = new Manager_Engine_Form($data['tabela'], $data['modulo']);
                $Form->criar();

                try
                {
                    $controlador = new Manager_Engine_Controlador($data['tabela'], $data['modulo']);
                    $controlador->criar();
                    
                    $id = $this->_model->insert($data);

                    $ArrayForm = array(
                        'titulo' => $data['titulo'],
                        'tabela' => $data['titulo'],
                        'classe_extendida' => 'Zend_Form',
                        'controlador' => $id,
                        'principal' => 1,
                        'criado_em' => date('Y-m-d G:i:s'));

                    $ObjFormModel = new Creator_Model_Formulario();
                    $ObjFormModel->insert($ArrayForm);

                    $this->view->message = "O registro foi inserido.";
                    $this->_forward('index');
                }
                catch (exception $e)
                {
                    $this->view->message = "Registro não inserido";
                }

            }
        } else
        {
            $this->view->message = "O registro não foi inserido.";
        }
        $this->view->form = $this->_form;
    }

    public function editarAction()
    {
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
                    if ($this->_request->getParam($v) !== null)
                        $data[$v] = $this->_request->getParam($v);
                endforeach;

                $controlador = new Manager_Engine_Controlador($data['tabela'], $data['modulo']);
                try
                {
                    $controlador->editar($data);
                    $this->_model->update($data, "id='$id'");
                    $this->_forward('index');
                }
                catch (exception $e)
                {

                }
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

        try
        {
            $controlador = new Manager_Engine_Controlador($data['tabela'], $data['modulo']);
            if ($controlador->deletar())
            {
                $this->_model->delete("id='$Id'");
                $this->_forward('index');
            } else
            {
                $this->view->message = "O registro não foi deletado.";
            }
        }
        catch (exception $e)
        {
            $this->view->message = "Cod.::" . $e->getCode() . " - " . $e->getMessage();
        }

    }

    public function controladorAction()
    {
        $result = $this->_model->fetchAll();
        $db = $result->getTable()->select()->from('creator_controlador', array("
        CONCAT_WS(' - ',titulo,tabela,(SELECT cm.titulo FROM creator_modulo as cm WHERE cm.id = creator_controlador.modulo)) as identificacao", "*"));
        $table = $db->query()->fetchAll();
        $data = new Zend_Dojo_Data('id', $table);
        $this->_helper->autoCompleteDojo($data);
    }

}
