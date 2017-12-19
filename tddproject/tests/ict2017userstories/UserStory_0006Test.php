<?php
require_once("duunissa/class/ict2017/Work.php");
require_once("duunissa/class/ict2017/Workshift.php");
require_once("duunissa/class/ict2017/Product.php");

class UserStory_0006Test extends \Codeception\Test\Unit
{
    /*
     *As an employee 
     *I want to mark used products to my workshift 
     *so that I can report them to my employer. 
     */
    
    const DATETIME_FORMAT = "d.m.Y H:i";

    protected function _before()
    {
        $employee = new Employee(123, "Jyrki", "Kolehmainen");
        $work     = new Work(123, new DateTime('17.11.2017 09:00'), null,
            new Location(60.99596, 24.46434), new Location(60.89596, 24.26434));

        $this->workshift = new Workshift($employee, $work);
    }

    protected function _after()
    {
    }

    /*
     * Scenario 1:
     * Given valid workshift 
     * And submit one valid product to workshift 
     * Then workshift product count is 1 
     * and latest product equals given submitted product. 
     */
    public function testScenario1OneProductSubmittedToWorkshift()
    {
        $product = new Product(2, 'Plank', 'default', 'pcs', 9.99, 4, 0.24, new DateTime('17.11.2017 12:00'));
        $this->workshift->addProduct($product);
        $products = $this->workshift->getProducts();

        // Count is 1
        $this->assertEquals(1, count($products));
        // Latest product
        $this->assertEquals($product, $products[count($products) - 1]);
    }

    /*
     * Scenario 2:
     * Given valid workshift 
     * And submit two different valid product to workshift 
     * Then workshift product count is 2 
     * and latest product equals second submitted product 
     * and earliest product equals first submitted product.  
     */
    public function testScenario2TwoProductsSubmittedToWorkshift()
    {
        $product = new Product(2, 'Plank', 'default', 'pcs', 9.99, 4, 0.24, new DateTime('17.11.2017 12:00'));
        $product2 = new Product(4, 'Soap', 'standard', 'kg', 1.15, 2, 0.24, new DateTime('17.11.2017 12:50'), 'Very expensive');
        $this->workshift->addProduct($product);
        $this->workshift->addProduct($product2);
        $products = $this->workshift->getProducts();

        // Count is 1
        $this->assertEquals(2, count($products));
        // Latest product
        $this->assertEquals($product2, $products[count($products) - 1]);
        // Earliest product
        $this->assertEquals($product, $products[0]);
    }

    /*
     * Scenario 3:
     * Given valid workshift 
     * And submit zero valid product to workshift 
     * Then workshift product count is 0.  
     */
    public function testScenario3ZeroProductsAdded()
    {
        // No products are submitted
        $products = $this->workshift->getProducts();
        // Count is 1
        $this->assertEquals(0, count($products));
    }
}