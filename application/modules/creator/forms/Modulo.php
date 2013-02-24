<?php
class Creator_Form_Modulo extends Zend_Form
{
    public $_id_usuario = null;
    public $_acao = 'add';
    public function init()
    {

        $this->setMethod('POST');

        $nome = new Zend_Dojo_Form_Element_TextBox('titulo');
        $nome->setLabel('Título:');
        $nome->setRequired(true);
        $this->addElement($nome);

        $tabela = new Zend_Dojo_Form_Element_TextBox('tabela');
        $tabela->setLabel('Tabela:');
        $tabela->setRequired(true);
        $this->addElement($tabela);

        $secao = new Zend_Dojo_Form_Element_TextBox('secao');
        $secao->setLabel('Seção:');
        $secao->setRequired(true);
        $this->addElement($secao);

        $button = new Zend_Dojo_Form_Element_SubmitButton('Salvar');
        $this->addElement($button);
    }

}
