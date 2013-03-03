<?php
class Manager_Engine_Form extends Manager_File
{
    protected $_tabela;

    public function __construct($controlador, $modulo)
    {
        $this->setModulo($modulo);
        $this->setControlador($controlador);
    }

    public function criar()
    {
        $Classe = new Zend_CodeGenerator_Php_Class();
        $Classe->setName($this->getFormName());
        $Classe->setExtendedClass("Zend_Form");

        $PhpFile = new Zend_CodeGenerator_Php_File();
        $PhpFile->setClass($Classe);
        $PhpFile->getClass()->setMethod(array('name' => 'init', 'body' => ''));
        $retorno = file_put_contents($this->getFormFile(), $PhpFile->generate());
        return $retorno;
    }
    public function editar()
    {
        $Classe = new Zend_CodeGenerator_Php_Class();
        $Classe->setName($this->getFormName());
        $Classe->setExtendedClass("Zend_Form");

        $PhpFile = new Zend_CodeGenerator_Php_File();
        $PhpFile->setClass($Classe);
        $PhpFile->getClass()->setMethod(array('name' => 'init', 'body' => ''));
        $retorno = file_put_contents($this->getFormFile(), $PhpFile->generate());
        return $retorno;
    }
    public function deletar()
    {
        unlink($this->getFormFile());
    }

}
