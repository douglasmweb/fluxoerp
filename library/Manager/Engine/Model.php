<?php
class Manager_Engine_Model extends Manager_File
{
    protected $_tabela;
    protected $_formulario;
    protected $_campos;
    
    public function __construct($controlador,$modulo, Zend_Db_Table_Row $formulario = null, Zend_Db_Table_Rowset $campos = null)
    {
        $this->setModulo($modulo);
        $this->setControlador($controlador);
        $this->setTabela(($this->getModulo())?strtolower($this->getModulo()) . "_" . $this->getControlador():strtolower($this->getControlador()));
        $this->setFormulario($formulario);
        $this->setCampos($campos);
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

        $colunas = array();
        $after = "";
        $after_cod = "";
        $null = "";
        $tamanho=11;
        $size = "";
        $chave = "";
        $primary = "";
        
        //Campos
        foreach($this->getCampos() as $k =>$v)
        {
            //if($after != "")
                //$after_cod = "AFTER `{$after}`";
            
            if($v->tamanho)
                $tamanho = $v->tamanho;
                
            $size = ($v->tipo == 'TEXT' or $v->tipo == 'DOUBLE')?"":"({$tamanho})";
            
            $null = ($v->nullo==0)?"NOT NULL":"DEFAULT NULL";
            $chave = ($v->chave_primaria==1)?" AUTO_INCREMENT":"";
            $primary .= ($v->chave_primaria==1)?"PRIMARY KEY (`".$v->campo."`),":"";
                //ALTER TABLE {$this->getTabela()} ADD
            $colunas[] = " `{$v->campo}` {$v->tipo}{$size} {$null} $after_cod";
            $after = $v->campo;
            $size = "";
        }
        $colunas[] = $primary;
        $colunas = implode(",",$colunas);
        
        //cria a tabela
        $table = new Creator_Model_MyTable;
        $db    = $table->getAdapter();
        $result = $db->query("CREATE TABLE IF NOT EXISTS {$this->getTabela()} ( $colunas ) ENGINE=InnoDB DEFAULT CHARSET=latin1");

        return $retorno;
    }

    public function editar()
    {

    }

    public function deletar()
    {
        unlink($this->getModelFile());
        //deletar a tabela
        $table = new Creator_Model_MyTable;
        $db    = $table->getAdapter();
        $result = $db->query("DROP TABLE {$this->getTabela()}");

    }



}
