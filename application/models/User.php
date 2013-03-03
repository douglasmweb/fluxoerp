<?php
/**
 * @Entity
 * @Table(name="users")
 */
class Model_User
{
    /**
     * @Id @Column(type="integer")
     * @GeneratedValue(strategy="AUTO")
     */
    private $id;
 
    /** @Column(type="string") */
    private $name;
    
    /** @Column(type="string") */
    private $valor;
 
    public function setName($string) {
        $this->name = $string;
        return true;
    }
    
    public function setValor($int) {
        $this->valor = $int;
        return true;
    }
}