<?php

class Creator_Form_Controlador extends Zend_Form
{

    public function init()
    {
        $this->setMethod('POST');
        
        $titulo = new Zend_Dojo_Form_Element_TextBox('titulo');
        $titulo->setLabel('Título:');
        $titulo->setRequired(true);
        $this->addElement($titulo);
        
        $tabela = new Zend_Dojo_Form_Element_TextBox('tabela');
        $tabela->setLabel('Tabela:');
        $tabela->setRequired(true);
        $tabela->addValidator(
            'Db_NoRecordExists',
                false,
                array(
                    'table' => 'creator_modulo',
                    'field' => 'tabela',
                    'messages' => array(Zend_Validate_Db_Abstract::ERROR_RECORD_FOUND => '%value% - Já existe no banco de dados.')
                    //'exclude' => array ('field' => 'tabela', 'value' => $this->request->get('tabela'))
                )
            );
        $this->addElement($tabela);
        
        $modulo = new Zend_Dojo_Form_Element_FilteringSelect('modulo');
        $modulo->setLabel('Módulo:')
            ->setAutoComplete(true)
            ->setStoreId('userStore')
            ->setStoreType('dojo.data.ItemFileReadStore')
            ->setStoreParams(array('url'=>'/creator/modulo/modulo/'))
            ->setAttrib("searchAttr", "identificacao")
            //->setAttrib('displayedValue', "nome") 
            ->setRequired(true);
        $this->addElement($modulo);
        
        $crud = new Zend_Dojo_Form_Element_RadioButton('crud');
        $crud->setLabel("Crud:");
        $crud->addMultiOptions(array(
            0 => 'Não',
            1 => 'Sim',
            ));
        $crud->setValue(0);
        $crud->setRequired(true);
        $this->addElement($crud);

        $button = new Zend_Dojo_Form_Element_SubmitButton('Salvar');
        $this->addElement($button);
    }


}

