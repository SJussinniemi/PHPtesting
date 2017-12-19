<?php

require_once("duunissa/class/ict2017/DateTimeCalculator.php");

class DateTimeCalculatorTest extends \Codeception\Test\Unit
{
    const DATETIME_FORMAT = "d.m.Y H:i";

    public $startTime;
    public $endTime;

    protected function _before()
    {
        $this->startTime            = new DateTime('22.11.2017 08:14');
        $this->endTime              = new DateTime('22.11.2017 09:46');
        $this->dateChangesStartTime = new DateTime('22.11.2017 23:55');
        $this->dateChangesEndTime   = new DateTime('23.11.2017 00:05');

        $this->calculator = new DateTimeCalculator();
    }

    protected function _after()
    {
    }

    // Test that DateTimeCalculator -class is found
    public function testDatetimecalculatorClassIsFound()
    {
        $this->assertInstanceOf(DateTimeCalculator::class, $this->calculator);
    }

    // Tests for GetTimeInHoursAndMinutesFromMinutes -function
    public function testGetTimeInHoursAndMinutes()
    {
        // Test with 0 minutes
        $this->assertEquals('0h 0min (0,00)', $this->calculator->getTimeInHoursAndMinutesFromMinutes(0));

        // Test with 36 minutes
        $this->assertEquals('0h 36min (0,60)', $this->calculator->getTimeInHoursAndMinutesFromMinutes(36));

        // Test with 157 minutes
        $this->assertEquals('2h 37min (2,62)', $this->calculator->getTimeInHoursAndMinutesFromMinutes(157));

        // Test with 2880 minutes
        $this->assertEquals('48h 0min (48,00)', $this->calculator->getTimeInHoursAndMinutesFromMinutes(2880));
    }

    // Tests for GetTimeInMinutes -function
    public function testGetTimeInMinutes()
    {
        $this->assertEquals(92, $this->calculator->getTimeInMinutes($this->startTime, $this->endTime));

        //Date changes between start and end time
        $this->assertEquals(10,
            $this->calculator->getTimeInMinutes($this->dateChangesStartTime, $this->dateChangesEndTime));

        //Start time is null
        $this->assertEquals(0, $this->calculator->getTimeInMinutes(null, $this->endTime));

        //End time is null
        $this->assertEquals(0, $this->calculator->getTimeInMinutes($this->dateChangesStartTime, null));

        //Get error if end time is before start time
        $this->ExpectException(Exception::class);
        $this->expectExceptionMessage('End time is before start time');
        $this->calculator->getTimeInMinutes($this->endTime, $this->startTime);
    }

    public function testOverlap()
    {
        $startOne = new DateTime('05.12.2017 10:00');
        $endOne = new DateTime('05.12.2017 12:00');

        $startTwo = new DateTime('05.12.2017 09:00');
        $endTwo = new DateTime('05.12.2017 11:00');

        $this->assertEquals(60, $this->calculator->getOverlapInMinutes($startOne, $endOne, $startTwo, $endTwo));
    }

    public function testNoOverlap()
    {
        $startOne = new DateTime('05.12.2017 10:00');
        $endOne = new DateTime('05.12.2017 12:00');

        $startTwo = new DateTime('05.12.2017 13:00');
        $endTwo = new DateTime('05.12.2017 14:00');

        $this->assertEquals(0, $this->calculator->getOverlapInMinutes($startOne, $endOne, $startTwo, $endTwo));
    }
}