<?php

require_once("duunissa/class/ict2017/Allowance.php");

class AllowanceTest extends \Codeception\Test\Unit
{
    const DATETIME_FORMAT = "d.m.Y H:i";

    protected function _before()
    {
        $this->allowance = new Allowance(
            1,
            'kilometrikorvaus', 
            'kilometrikorvaus',
            'KM',
            0.42, 
            60,
            new DateTime('17.11.2017 09:00'),
            'Työmatkani');
    }

    protected function _after()
    {
    }

    // Test Allowance class is found
    public function testAllowanceClassIsFound()
    {
        $this->assertInstanceOf(Allowance::class, $this->allowance);
    }

    // Test getting attributes given in constructor
    public function testAllowanceExists()
    {
        $this->assertEquals(1, $this->allowance->getID());
        $this->assertEquals('kilometrikorvaus', $this->allowance->getName());
        $this->assertEquals('kilometrikorvaus', $this->allowance->getType());
        $this->assertEquals('KM', $this->allowance->getUnit());
        $this->assertEquals(0.42, $this->allowance->getUnitCost());
        $this->assertEquals(60, $this->allowance->getAmount());
        $this->assertEquals(new DateTime('17.11.2017 09:00'), $this->allowance->getTimeStamp());
        $this->assertEquals('Työmatkani', $this->allowance->getDescription());
    }

    public function testCreateAllowanceObjectIdInvalid()
    {
        $this->setExpectedException(Exception::class, 'Allowance ID should be greater than 0.');
        new Allowance(-1, 'kilometrikorvaus', 'kilometrikorvaus', 'KM', 0.42, 60,
            new DateTime('17.11.2017 09:00'), 'Työmatkani');
    }
}