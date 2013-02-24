<?php
class Creator_Form_FormularioCampo extends Zend_Form
{
    public function init()
    {
        $this->setMethod('POST');

        $titulo = new Zend_Dojo_Form_Element_TextBox('titulo');
        $titulo->setLabel('Título:');
        $titulo->setRequired(true);
        $this->addElement($titulo);

        $tabela = new Zend_Dojo_Form_Element_TextBox('campo');
        $tabela->setLabel('Campo:');
        $tabela->setRequired(true);
        $this->addElement($tabela);

        $tipo = new Zend_Dojo_Form_Element_FilteringSelect('tipo');
        $tipo->setLabel('Tipo:');
        $tipo->setAutoComplete(true);
        $tipo->addMultiOptions(
array(
'INT'=>'INT', 'VARCHAR'=>'VARCHAR', 'TEXT'=>'TEXT', 'DATE'=>'DATE', 'TINYINT'=>'TINYINT', 'SMALLINT'=>'SMALLINT', 
'MEDIUMINT'=>'MEDIUMINT', 'INT'=>'INT', 'BIGINT'=>'BIGINT', 'DECIMAL'=>'DECIMAL', 'FLOAT'=>'FLOAT', 'DOUBLE'=>'DOUBLE', 
'REAL'=>'REAL', 'BIT'=>'BIT', 'BOOLEAN'=>'BOOLEAN', 'SERIAL'=>'SERIAL', 'DATETIME'=>'DATETIME', 'TIMESTAMP'=>'TIMESTAMP', 
'TIME'=>'TIME', 'YEAR'=>'YEAR', 'TINYTEXT'=>'TINYTEXT', 'TEXT'=>'TEXT', 'MEDIUMTEXT'=>'MEDIUMTEXT', 'LONGTEXT'=>'LONGTEXT', 
'BINARY'=>'BINARY', 'VARBINARY'=>'VARBINARY', 'TINYBLOB'=>'TINYBLOB', 'MEDIUMBLOB'=>'MEDIUMBLOB', 'BLOB'=>'BLOB', 
'LONGBLOB'=>'LONGBLOB', 'ENUM'=>'ENUM', 'SET'=>'SET', 'POINT'=>'POINT', 'LINESTRING'=>'LINESTRING', 'POLYGON'=>'POLYGON', 
'MULTIPOINT'=>'MULTIPOINT', 'MULTILINESTRING'=>'MULTILINESTRING', 'MULTIPOLYGON'=>'MULTIPOLYGON', 
'GEOMETRYCOLLECTION'=>'GEOMETRYCOLLECTION')
        );
        $tipo->setRequired(true);
        $this->addElement($tipo);
        
        $tamanho = new Zend_Dojo_Form_Element_TextBox('tamanho');
        $tamanho->setLabel('Tamanho:');
        $this->addElement($tamanho);
        
        $valor_padrao = new Zend_Dojo_Form_Element_TextBox('valor_padrao');
        $valor_padrao->setLabel('Valor Padrão:');
        $this->addElement($valor_padrao);
        
        $chave = new Zend_Dojo_Form_Element_RadioButton('chave_primaria');
        $chave->setLabel('Chave Primária:');
        $chave->setValue(0);
        $chave->addMultiOptions(array(0=>'Não',1=>'Sim'));
        $this->addElement($chave);
        
        $nullo = new Zend_Dojo_Form_Element_RadioButton('nullo');
        $nullo->setLabel('Nullo:');
        $nullo->setValue(0);
        $nullo->addMultiOptions(array(0=>'Não',1=>'Sim'));
        $this->addElement($nullo);
        
        $form = new Zend_Form_Element_Hidden('formulario');
        $this->addElement($form);

        $button = new Zend_Dojo_Form_Element_SubmitButton('Salvar');
        $this->addElement($button);
    }
}
