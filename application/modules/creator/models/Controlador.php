<?php
class Creator_Model_Controlador extends Zend_Db_Table
{
    protected $_name = 'creator_controlador';
    
    protected $_dependentTables = array('Creator_Model_Formulario');
    
    protected $_referenceMap = array(
        'Modulo' => array(
            'columns' => array('modulo'),
            'refTableClass' => 'Creator_Model_Modulo',
            'refColumns' => array('id')
        )
    );
}