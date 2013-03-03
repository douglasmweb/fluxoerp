<?php
class Manager_Engine_Modulos extends Manager_File
{
    function __construct($modulo)
    {
        $this->setModulo($modulo);
    }

    function criar($nameModulo = null)
    {
        if (!file_exists($this->getModulePath()))
        {
            if (mkdir($this->getModulePath() . '/', 0777))
            {
                mkdir($this->getModulePath() . '/' . '/configs', 0777);
                mkdir($this->getModulePath() . '/' . '/controllers', 0777);
                mkdir($this->getModulePath() . '/' . '/forms', 0777);
                mkdir($this->getModulePath() . '/' . '/layouts', 0777);
                mkdir($this->getModulePath() . '/' . '/models', 0777);
                mkdir($this->getModulePath() . '/' . '/views/', 0777);
                mkdir($this->getModulePath() . '/' . '/views/filters', 0777);
                mkdir($this->getModulePath() . '/' . '/views/helpers', 0777);
                mkdir($this->getModulePath() . '/' . '/views/scripts', 0777);
                
                $Classe = new Zend_CodeGenerator_Php_Class();
                $Classe->setName(ucfirst($this->getModulo()) . '_Bootstrap');
                $Classe->setExtendedClass('Zend_Application_Module_Bootstrap');
                $Nova = new Zend_CodeGenerator_Php_File();
                $Nova->setClass($Classe);
                $transfer = file_put_contents($this->getModulePath() . '/Bootstrap.php', $Nova->generate());
                
                //rmdir($this->getModulePath());
                $controlador = new Manager_Engine_Controlador("index",$this->getModulo());
                $controlador->criar(true);

                return true;
            } else
            {
                throw new Exception("A pasta '{$this->getModulo()}' não pode ser criada em modules/", 002);
                return false;
            }
        } else
        {
            throw new Exception("Já existe uma pasta com o nome '{$this->getModulo()}' em modules/", 001);
            return false;
        }
    }

    function deletar($nameModulo = null)
    {
        $retorno = true;
        $nameModulo = $this->getModulePath();
        if ($this->getModulo() != 'default' || $this->getModulo() == '')
        {
            $retorno = $this->delTree($nameModulo);
        } else
        {
            throw new Exception('Você não pode deletar o módulo default.');
        }
        return $retorno;
    }

}
