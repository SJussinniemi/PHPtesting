<?php

require_once("duunissa/class/ict2017/Work.php");
require_once("duunissa/class/ict2017/Location.php");

class WorkTest extends \Codeception\Test\Unit
{
    const DATETIME_FORMAT = "d.m.Y H:i";

    public $workStartTime, $workEndTime, $location;

    protected function _before()
    {
        $workStartTime     = new DateTime('22.11.2017 9:00');
        $workEndTime       = new DateTime('22.11.2017 16:30');
        $workStartLocation = new Location(60.99596, 24.46434);
        $workEndLocation   = new Location(60.89596, 24.26434);
        $this->work        = new Work(123, $workStartTime, $workEndTime, $workStartLocation, $workEndLocation);
    }

    protected function _after()
    {
    }

    // tests
    public function testWorkClassIsFound()
    {
        $this->assertInstanceOf(Work::class, $this->work);
    }

    // Get work starttime
    public function testGetWorkStartTime()
    {
        $this->assertEquals(new DateTime('22.11.2017 9:00'), $this->work->getWorkStartTime());
    }

    // Get work endtime
    public function testGetWorkEndTime()
    {
        $this->assertEquals(new Datetime('22.11.2017 16:30'), $this->work->getWorkEndTime());
    }

    // Employee is able to start remote working
    public function testStartRemoteWorking()
    {
        $this->work->startRemoteWorking();
        $this->assertEquals(true, $this->work->getRemoteWorkStatus());
    }

    // Employee is able to stop remote working
    public function testStopRemoteWorking()
    {
        $this->work->stopRemoteWorking();
        $this->assertEquals(false, $this->work->getRemoteWorkStatus());
    }

    // Get start location
    public function testGetWorkStartLocation()
    {
        $this->assertEquals(new Location(60.99596, 24.46434), $this->work->getWorkStartLocation());
        $this->assertEquals(60.99596, $this->work->getWorkStartLocation()->getLatitude());
        $this->assertEquals(24.46434, $this->work->getWorkStartLocation()->getLongitude());
    }

    // Get end location
    public function testGetWorkEndLocation()
    {
        $this->assertEquals(new Location(60.89596, 24.26434), $this->work->getWorkEndLocation());
        $this->assertEquals(60.89596, $this->work->getWorkEndLocation()->getLatitude());
        $this->assertEquals(24.26434, $this->work->getWorkEndLocation()->getLongitude());
    }

    // Get error with setting invalid start time
    public function testSetWorkStartTimeInvalid()
    {
        $this->setExpectedException(Exception::class, 'Work start time required a valid start time.');
        $this->work->setWorkStartTime(null);
    }

    // Get error when creating object with invalid start time
    public function testCreateWorkObjectStartTimeInvalid()
    {
        $this->setExpectedException(Exception::class, 'Work start time required a valid start time.');
        new Work(2, null);
    }

    // Get error when creating object with invalid id 
    public function testCreateWorkObjectIdInvalid()
    {
        $this->setExpectedException(Exception::class, 'Work ID should be greater than 0.');
        $work = new Work(-1, new DateTime('30.11.2017 09:00'));
        $work->setID(0);
    }
}