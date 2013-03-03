<?php
/**
 * Scenario 1: THIS IS NOT POSSIBLE CURRENTLY
 * @Entity @Table(name="product")
 */
class Product
{
    /**
     * @Id @Column(type="integer")
     * @GeneratedValue(strategy="AUTO")
     */    
    private $id;
    /** @Column(type="string") */
    private $name;
    /** @Column(type="string") */
    private $attributes = array();
 
}