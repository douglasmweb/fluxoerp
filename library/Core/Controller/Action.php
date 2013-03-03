<?php
abstract class Core_Controller_Action extends Zend_Controller_Action
{
    protected $_redirector;
    protected $_flashMessenger = null;
    protected $_model;
    protected $_form;
    protected $_colunas;
    protected $_request;
    protected $_em;

    public function init()
    {
        $cme = new \Doctrine\ORM\Tools\Export\ClassMetadataExporter();
$exporter = $cme->getExporter('yml', '/doctrine/schema/');
$classes = array(
  $em->getClassMetadata('Entities\Profile')
);
$exporter->setMetadata($classes);
$exporter->export();
        
        /*
        $registry = Zend_Registry::getInstance();
        $this->_em = $registry->entitymanager;
        
        $tool = new \Doctrine\ORM\Tools\SchemaTool($this->_em);
        
        $classes = array(
          $this->_em->getClassMetadata('Cadastros_Model_Categoria'),
        );
        $tool->dropSchema($classes);
        $tool->createSchema($classes);
        /**/
                        
        $link = new Zend_View_Helper_Url();
        $this->_redirector = $this->_helper->getHelper('Redirector');
        $this->view->adicionar = "<a href='" . $link->url(array(
            'module' => $this->_request->getModuleName(),
            'controller' => $this->_request->getControllerName(),
            'action' => 'criar'), null, true) . "' class='adicionar'>Adicionar</a>";

        
        $Module = $this->_request->getModuleName();
        $Controller = $this->_request->getControllerName();
        
        
        $ObjModulo = new Creator_Model_Modulo();
        $ResModulo = $ObjModulo->fetchAll("tabela = '$Module'")->current();
        $ObjControlador = $ResModulo->findDependentRowset("Creator_Model_Controlador")
                         ->getTable()
                         ->fetchAll("tabela = '$Controller'")->current();
        $ObjFormulario = $ObjControlador->findDependentRowset("Creator_Model_Formulario")
                         ->getTable()
                         ->fetchAll("principal = '1'")->current();
                         
        $ObjFormCampo = $ObjFormulario->findDependentRowset("Creator_Model_FormularioCampo")
        ->getTable()
        ->fetchAll("formulario = '$ObjFormulario->id'");
        
        $this->view->titulo = "<h3>" . (($ObjControlador->titulo) ? $ObjControlador->titulo : "Sem Título") . "</h3>";
        
        foreach($ObjFormCampo as $k => $v)
        {
            $this->_colunas[$v->titulo] = $v->campo;
        }
        
        $this->view->colunas = $this->_colunas;
        $this->view->col_cell = $ObjFormCampo;
        $this->view->request = $this->_request;
        
        $this->view->setScriptPath(APPLICATION_PATH . "/projeto/templates");
        
        $this->initView();
    }
    /**/

    public function indexAction()
    {
        
        $this->_em->persist($testEntity);
        $this->_em->flush();
        $dados = $this->_model->fetchAll();
        $this->view->dados = $dados;
        $this->_helper->viewRenderer($this->def['tpl']."/index",null,true);
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
        $this->_helper->viewRenderer($this->def['tpl']."/criar",null,true);
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
        $this->_helper->viewRenderer($this->def['tpl']."/editar",null,true);
    }

    public function deletarAction()
    {
        // action body
        $this->_helper->viewRenderer($this->def['tpl']."/deletar",null,true);
    }

    protected function flash($message, $to)
    {
        $this->_flashMessenger->addMessage($message);
        $this->_redirector->gotoUrl($to);
    }

    public function postDispatch()
    {
        parent::postDispatch();

    }

}
?>