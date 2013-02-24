<?php
class Creator_Model_FormularioCampo extends Zend_Db_Table
{
    protected $_name = 'creator_formulario_campo';
    
    protected $_referenceMap = array(
        'Formulario' => array(
            'columns' => array('formulario'),
            'refTableClass' => 'Creator_Model_Formulario',
            'refColumns' => array('id')
        )
    );
}