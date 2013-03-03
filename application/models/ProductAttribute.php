<?php
/**
 * Scenario 1: THIS IS NOT POSSIBLE CURRENTLY
 * @Entity @Table(name="product_attributes")
 */
class ProductAttribute
{
    /** @Id @ManyToOne(targetEntity="Product") */
    private $product;
    /** @Id @Column(type="string", name="attribute_name") */
    private $name;
    /** @Column(type="string", name="attribute_value") */
    private $value;
}
