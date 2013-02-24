<?php
class Creator_ModuloController extends Zend_Controller_Action
{
    protected $_form;
    protected $_model;
    protected $_titulo;
    protected $_id;

    public function init()
    {
        $this->_form = new Creator_Form_Modulo();
        $this->_model = new Creator_Model_Modulo();
        $this->_titulo = "Módulos";
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

                $modulos = new Manager_Engine_Modulos($data['tabela']);
                if ($modulos->criar())
                {

                    $id = $this->_model->insert($data);
                    $controlller = new Creator_Model_Controlador();
                    $idc = $controlller->insert(array(
                        "titulo" => "Index",
                        "tabela" => "index",
                        "modulo" => $id,
                        "crud" => 0));
                    $action = new Creator_Model_Acao();
                    $action->insert(array(
                        "titulo" => "Index",
                        "nome" => "index",
                        "controlador" => $idc,
                        ));

                    $this->view->message = "O registro foi inserido.";
                    $this->_forward('index');
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
        // Obtém o id na hora da edição
        $this->_id = $id = $this->getRequest()->getParam('id');
        //var_dump($this->_id);
        // Busca no banco a linha deste registro
        $result = $this->_model->find($id);
        // Retorna somente os dados sem o objeto inteiro
        $data = $result->current();
        // Preenche o formulário com os valores
        $this->_form->populate($data->toArray());
        // Obtém todos os elementos do formulário
        $elements = $this->_form->getElements();
        foreach ($elements as $element)
        {
            // Limpo todos os validadores existentes
            $element->clearValidators();
        }
        // Checa se o formulário foi enviado
        if ($this->_request->isPost())
        {
            // Pega os valores submitados
            $data = $this->_request->getPost();
            // Retira o ultimo elemento que pode ser o botão Enviar
            array_pop($data);
            // Valida o formulário
            if ($this->_form->isValid($_POST))
            {
                foreach ($data as $k => $v):
                    $dados[$k] = $v;
                endforeach;

                $menu = new Core_Layout_Menu();
                // Atualiza o banco
                $this->_model->update($dados, "id = '$id'");
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
            $modulo = new Manager_Engine_Modulos($data['tabela']);
            if ($modulo->deletar())
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

    public function isValid($data)
    {
        $this->getElement('transacao')->addValidator('Db_NoRecordExists', false, array(
            'table' => 'creator_modulo',
            'field' => 'tabela',
            'messages' => array(Zend_Validate_Db_Abstract::ERROR_RECORD_FOUND => '%value% - Já existe no banco de dados.'),
            //'exclude' => array ('field' => 'tabela', 'value' => $this->request->get('tabela'))
            'exclude' => array('field' => 'tabela', 'value' => $this->getValue('id'))));
        return parent::isValid($data);
    }

    public function moduloAction()
    {
        $result = $this->_model->fetchAll();
        $db = $result->getTable()->select()->from('creator_modulo', array("
        CONCAT_WS(' - ',titulo,tabela) as identificacao", "*"));
        $table = $db->query()->fetchAll();
        $data = new Zend_Dojo_Data('id', $table);
        $this->_helper->autoCompleteDojo($data);
    }

}
