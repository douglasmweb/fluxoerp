<?php
class Creator_Form_Formulario extends Zend_Form
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
        $this->addElement($tabela);

        $controlador = new Zend_Dojo_Form_Element_FilteringSelect('controlador');
        $controlador->setLabel('Controlador:')->setAutoComplete(true)->setStoreId('userStore')->setStoreType('dojo.data.ItemFileReadStore')->setStoreParams(array('url' => '/creator/controlador/controlador/'))->setAttrib("searchAttr", "identificacao")
            //->setAttrib('displayedValue', "nome")
            ->setRequired(true);
        $this->addElement($controlador);

        $modulo = new Zend_Dojo_Form_Element_FilteringSelect('classe_extendida');
        $modulo->setLabel('Classe Extendida do Form.:')->setAutoComplete(true)->addMultiOptions(array(
            'Zend_Form' => 'Zend_Form',
            'Zend_Dojo' => 'Zend_Dojo',
            'Zend_Jquery' => 'Zend_Jquery')) //->setAttrib('displayedValue', "nome")
            ->setRequired(true);
        $this->addElement($modulo);

        $button = new Zend_Dojo_Form_Element_SubmitButton('Salvar');
        $this->addElement($button);
    }
}
