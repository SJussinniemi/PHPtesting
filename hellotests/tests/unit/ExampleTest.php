<?php
include 'index.php';

class ExampleTest extends \Codeception\Test\Unit
{
    /**
     * @var \UnitTester
     */
    protected $tester;

    protected function _before()
    {
    }

    protected function _after()
    {
    }

    // tests
	
	public function testMethodTests()
	{
		$this->assertEquals(test(),5);
		
		$this->assertFalse(returnFalse());	

		$this->assertEquals(doubleValue(1),2);
		$this->assertEquals(doubleValue(999),1998);
		$this->assertEquals(doubleValue(-999),-1998);
	}
	
	public function testDbUserNotDb()
	{	
		$this->tester->seeInDatabase('contact', ['Lastname' => 'Terava', 'Firstname' => 'Teemu']);
	}
	
	public function testDbUserNotInDb()
	{
		$this->tester->dontseeInDatabase('contact', ['Lastname' => 'Jutila', 'Firstname' => 'Timo']);
	}
	
	public function testDbAddUser()
	{
		$this->tester->haveInDatabase('contact', array('ID' => '3', 'Job' => 'Worker', 'Firstname' => 'Patrik', 'Lastname' => 'Laine'));
		$numrows = $this->tester->grabNumRecords('contact');
		$this->assertEquals($numrows,3);
	}
	
	public function testDbNumberOfRowsOverZero()
	{
		$numrows = $this->tester->grabNumRecords('contact');
		$this->assertGreaterThan(0,$numrows);
	}
	
}