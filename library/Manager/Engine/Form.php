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
        $Classe->setExtendedClass("Zend_Db_Table");
        $Classe->setProperties(
            array(
                array(
                    'name'         => '_name',
                    'visibility'   => 'protected',
                    'defaultValue' => $this->getTabela(),
                )
            )
        );
        
        $PhpFile = new Zend_CodeGenerator_Php_File();
        $PhpFile->setClass($Classe);
        
        $retorno = file_put_contents($this->getModelFile(),$PhpFile->generate());

        return $retorno;
    }
    public function editar()
    {
        
    }
    public function deletar()
    {
        
    }
    

}
