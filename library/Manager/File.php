<?php
class Manager_File
{
    private $_basePath = APPLICATION_PATH;
    private $_modulo;
    private $_controlador;
    private $_acao;

    public function __construct()
    {

    }

    public function setTabela($value)
    {
        $this->_tabela = $value;
        return $this;
    }

    public function getTabela()
    {
        return $this->_tabela;
    }

    protected function getModelTabela()
    {
        return ($this->getModulo()) ? strtolower($this->getModulo()) . "_" . $this->getControlador() : strtolower($this->getControlador());
    }

    protected function getPath()
    {
        return $this->_basePath;
    }
    protected function setPath($folderPath)
    {
        $this->_basePath = $folderPath;
        return $this;
    }

    protected function checkControllerExiste()
    {
        if (file_exists($this->getControllerPath()))
        {
            return true;
        } else
        {
            return false;
        }
    }

    protected function getModulePath()
    {
        if ($this->getModulo())
        {
            return $this->_basePath . '/modules/' . $this->getModulo();
        }
    }

    protected function getControllerPath()
    {
        return $this->getModulePath() . '/controllers/' . $this->getControllerName() . 'Controller.php';
    }

    protected function getControllerName()
    {
        $controllerName = "";
        $inflector = new Zend_Filter_Word_UnderscoreToCamelCase();
        $controllerName = $inflector->filter($this->getControlador());

        $inflector = new Zend_Filter_Word_DashToCamelCase();
        $controllerName = $inflector->filter($controllerName);

        $inflector = new Zend_Filter_Word_SeparatorToCamelCase();
        $controllerName = $inflector->filter($controllerName);

        return $controllerName;
    }

    protected function getScriptControllerPath()
    {
        if ($this->getControlador())
            return $this->getModulePath() . '/views/scripts/' . str_replace('_', '-', strtolower($this->getControlador()));
        else
            return false;
    }

    protected function getModelPath()
    {
        return $this->getModulePath() . "/models";
    }

    protected function getModelFile()
    {
        return $this->getModulePath() . "/models/" . $this->getControllerName() . ".php";
    }

    protected function getModelName()
    {
        return $this->getModuloName() . "Model_" . $this->getControllerName();
    }

    protected function getFormPath()
    {
        return $this->getModulePath() . "/forms";
    }

    protected function getFormFile()
    {
        return $this->getFormPath() . "/" . $this->getControllerName() . ".php";
    }

    protected function getFormName()
    {
        return $this->getModuloName() . "Form_" . $this->getControllerName();
    }

    protected function setFormulario($value)
    {
        $this->_formulario = $value;
        return $this;
    }

    protected function getFormulario()
    {
        return $this->_formulario;
    }

    protected function setCampos($value)
    {
        $this->_campos = $value;
        return $this;
    }

    protected function getCampos()
    {
        return $this->_campos;
    }

    public function setModulo($valor)
    {
        if (is_numeric($valor))
        {
            $model = new Creator_Model_Modulo();
            $result = $model->find($valor)->current();
            $valor = $result->tabela;
        }
        $this->_modulo = $valor;
        return $this;
    }

    public function getModulo()
    {
        return $this->_modulo;
    }

    public function getModuloName()
    {
        $retorno = "";
        if ($this->_modulo == "" or $this->_modulo == "default")
        {
            $retorno = "";
        } else
        {
            $retorno = ucfirst($this->_modulo) . "_";
        }
        return $retorno;
    }

    public function setControlador($valor)
    {
        if (is_numeric($valor))
        {
            $model = new Creator_Model_Controlador();
            $result = $model->find($valor)->current();
            $valor = $result->tabela;
        }
        $this->_controlador = $valor;
        return $this;
    }

    public function getControlador()
    {
        return $this->_controlador;
    }

    public function setAcao($valor)
    {
        $this->_acao = $valor;
        return $this;
    }

    public function getAcao()
    {
        return $this->_acao;
    }

    protected function CopyScripts($pastaOrigem, $pastaDestino)
    {
        $files = array_diff(scandir($pastaOrigem), array('.', '..'));

        foreach ($files as $file)
        {
            $nome = pathinfo($file);

            copy($pastaOrigem . '/' . $file, $this->getScriptControllerPath() . '/' . $nome['filename'] . '.' . $nome['extension']);
        }
    }

    protected function delTree($dir)
    {
        if ($this->getModulo() == "" || $this->getModulo() == "default")
        {
            throw new Exception('Você não pode deletar o módulo default.');
            return false;
        }

        $files = array_diff(scandir($dir), array('.', '..'));
        foreach ($files as $file)
        {
            (is_dir("$dir/$file")) ? $this->delTree("$dir/$file") : unlink("$dir/$file");
        }
        return rmdir($dir);
    }
}
