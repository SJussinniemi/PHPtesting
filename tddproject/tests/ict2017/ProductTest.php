<?php

require_once("duunissa/class/ict2017/Product.php");

class ProductTest extends \Codeception\Test\Unit
{
    const DATETIME_FORMAT = "d.m.Y H:i";

    protected function _before()
    {
        $this->product = new Product(2, 'Plank', 'default', 'pcs', 9.99, 4, 0.24,
            new DateTime('17.11.2017 12:00'), 'Wooden plank');
    }

    protected function _after()
    {
    }

    // Test that Product -class is found
    public function testProductClassIsFound()
    {
        $this->assertInstanceOf(Product::class, $this->product);
    }

    // Test getting Product id
    public function testGetProductId()
    {
        $this->assertEquals(2, $this->product->getProductId());
    }

    // Test getting Product name
    public function testGetProductName()
    {
        $this->assertEquals('Plank', $this->product->getProductName());
    }

    // Test getting Product type
    public function testGetProductType()
    {
        $this->assertEquals('default', $this->product->getProductType());
    }

    // Test getting Product unit
    public function testGetProductUnit()
    {
        $this->assertEquals('pcs', $this->product->getProductUnit());
    }

    // Test getting Product price
    public function testGetProductPrice()
    {
        $this->assertEquals(9.99, $this->product->getProductPrice());
    }

    // Test getting Product amount
    public function testGetProductAmount()
    {
        $this->assertEquals(4, $this->product->getProductAmount());
    }

    // Test getting Product tax
    public function testGetProductTax()
    {
        $this->assertEquals(0.24, $this->product->getProductTax());
    }

    // Test getting Product timestamp
    public function testGetProductTimestamp()
    {
        $this->assertEquals(new DateTime('17.11.2017 12:00'), $this->product->getProductTimestamp());
    }

    // Test getting Product description
    public function testGetProductDescription()
    {
        $this->assertEquals('Wooden plank', $this->product->getProductDescription());
    }

    public function testCreateProductObjectIdInvalid()
    {
        $this->setExpectedException(Exception::class, 'Product ID should be greater than 0.');
        new Product(-1, 'Plank', 'default', 'pcs', 9.99, 4, 0.24,
            new DateTime('17.11.2017 12:00'), 'Wooden plank');
    }
}