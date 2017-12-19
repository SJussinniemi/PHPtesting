<?php

require_once("duunissa/class/ict2017/LunchBreak.php");

class LunchBreakTest extends \Codeception\Test\Unit
{
    /**
     * @var \Ict2017Tester
     */
    protected $tester;

    protected function _before()
    {
        $breakStartTime = new DateTime('21.10.2017 11:30');
        $breakEndTime = new DateTime('21.10.2017 12:00');
        $this->lunch = new LunchBreak($breakStartTime, $breakEndTime);
    }

    protected function _after()
    {
    }

    // Class is found
    public function testLunchbreakClassIsFound()
    {
        $this->assertInstanceOf(LunchBreak::class, $this->lunch);
    }

    // test getters and setters
    public function testLunchbreakExists()
    {
        $this->assertEquals(new DateTime('21.10.2017 11:30'), $this->lunch->getBreakStartTime());
        $this->assertEquals(new DateTime('21.10.2017 12:00'), $this->lunch->getBreakEndTime());

        $this->lunch->setBreakStartTime(new DateTime('22.10.2017 12:30'));
        $this->lunch->setBreakEndTime(new DateTime('22.10.2017 12:45'));

        $this->assertEquals(new DateTime('22.10.2017 12:30'), $this->lunch->getBreakStartTime());
        $this->assertEquals(new DateTime('22.10.2017 12:45'), $this->lunch->getBreakEndTime());
    }

    // test invalid start time
    public function testSetBreakStartTimeInvalid()
    {
        $this->setExpectedException(Exception::class, 'Lunch start time required a valid start time.');
        $this->lunch->setBreakStartTime(null);
    }
}