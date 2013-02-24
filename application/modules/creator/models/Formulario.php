<?php
class Creator_Model_Formulario extends Zend_Db_Table
{
    protected $_name = 'creator_formulario';
    
    protected $_dependentTables = array('Creator_Model_FormularioCampo');
    
    protected $_referenceMap = array(
        'Controlador' => array(
            'columns' => array('controlador'),
            'refTableClass' => 'Creator_Model_Controlador',
            'refColumns' => array('id')
        )
    );
}