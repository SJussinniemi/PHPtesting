<?php

require_once("duunissa/class/ict2017/Shiftbreak.php");

class ShiftbreakTest extends \Codeception\Test\Unit
{
    const DATETIME_FORMAT = "d.m.Y H:i";

    protected function _before()
    {
        $ID                 = 123;
        $breakStartTime     = New DateTime('22.11.2017 13:25');
        $breakEndTime       = New DateTime('22.11.2017 13:35');
        $breakStartLocation = new Location(60.99596, 24.46434);
        $breakEndLocation   = new Location(60.89596, 24.26434);
        $this->shiftbreak   = new Shiftbreak($ID, $breakStartTime, $breakEndTime, $breakStartLocation,
            $breakEndLocation);
    }

    protected function _after()
    {
    }

    // tests
    // Test if Shiftbreak class is found
    public function testShiftbreakClassIsFound()
    {
        $this->assertInstanceOf(Shiftbreak::class, $this->shiftbreak);
    }

    public function testGetBreakStartTime()
    {
        $this->assertEquals(New DateTime('22.11.2017 13:25'), $this->shiftbreak->getBreakStartTime());
    }

    public function testGetBreakEndTime()
    {
        $this->assertEquals(New DateTime('22.11.2017 13:35'), $this->shiftbreak->getBreakEndTime());
    }

    public function testBreakId()
    {
        $this->shiftbreak->setID(123);
        $this->assertEquals(123, $this->shiftbreak->getID());
    }

    // Get start location
    public function testGetBreakStartLocation()
    {
        $this->assertEquals(new Location(60.99596, 24.46434), $this->shiftbreak->getBreakStartLocation());
        $this->assertEquals(60.99596, $this->shiftbreak->getBreakStartLocation()->getLatitude());
        $this->assertEquals(24.46434, $this->shiftbreak->getBreakStartLocation()->getLongitude());
    }

    // Get end location
    public function testGetBreakEndLocation()
    {
        $this->assertEquals(new Location(60.89596, 24.26434), $this->shiftbreak->getBreakEndLocation());
        $this->assertEquals(60.89596, $this->shiftbreak->getBreakEndLocation()->getLatitude());
        $this->assertEquals(24.26434, $this->shiftbreak->getBreakEndLocation()->getLongitude());
    }

    public function testCreateProjectObjectIdInvalid()
    {
        $this->setExpectedException(Exception::class, 'Shiftbreak ID should be greater than 0.');
        $shiftbreak = new Shiftbreak(-1, new DateTime('22.11.2017 13:25'), new DateTime('22.11.2017 13:25'));
        $shiftbreak->setID(0);
    }

}