<?php
class UserController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
        $registry = Zend_Registry::getInstance();
        $this->_em = $registry->entitymanager;
    }

    public function indexAction()
    {
        $testEntity = new Model_User;

        
        $testEntity->setName('Hector Pinol');
        $testEntity->setValor('123,12');

        $this->_em->persist($testEntity);
        $this->_em->flush();
    }

}
