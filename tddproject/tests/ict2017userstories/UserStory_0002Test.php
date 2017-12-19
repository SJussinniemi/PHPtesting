<?php

require_once("duunissa/class/ict2017/Shiftbreak.php");
require_once("duunissa/class/ict2017/Workshift.php");

class UserStory_0002Test extends \Codeception\Test\Unit
{
    /*
    As an Employee 
    I want to mark my shiftbreaks during my workday 
    so that I can log my daily breaks.
    */

    const DATETIME_FORMAT = "d.m.Y H:i";

    protected function _before()
    {
        $employee = new Employee(123, "Jyrki", "Kolehmainen");
        $work     = new Work(123, new DateTime('17.11.2017 09:00'),
            new DateTime('17.11.2017 16:30'), new Location(60.99596, 24.46434),
            new Location(60.89596, 24.26434));

        $this->workshift = new Workshift($employee, $work);
        $this->ID        = 123;
    }

    protected function _after()
    {
    }

    /* 
    Given shiftbreak start time (17.11 10:00) 
    And shiftbreak end time (17.11 10:15) 
    When I submit these times 
    Then I get break time of 0h 15min (0,25) 
    */
    public function testScenario1ShiftbreakWithRegularTimes()
    {
        $startTime = new DateTime('17.11.2017 10:00');
        $endTime   = new DateTime('17.11.2017 10:15');

        $shiftbreak = new Shiftbreak($this->ID, $startTime, $endTime);
        $this->workshift->setShiftBreak($shiftbreak);

        $this->assertEquals('0h 15min (0,25)', $this->workshift->getShiftbreakTime());
    }

    /*
    Given shiftbreak start time (17.11 10:15) 
    And shiftbreak end time (17.11 10:00) 
    When I submit these times 
    Then I get an Error because starting time is later than end time 
    */
    public function testScenario2ShiftbreakWithEndTimeBeforeStartTime()
    {
        $startTime = new DateTime('17.11.2017 10:15');
        $endTime   = new DateTime('17.11.2017 10:00');

        $shiftbreak = new Shiftbreak($this->ID, $startTime, $endTime);
        $this->workshift->setShiftBreak($shiftbreak);

        $this->ExpectException(Exception::class);
        $this->expectExceptionMessage('End time is before start time');
        $this->workshift->getShiftbreakTime();
    }

    /*
    Given shiftbreak start time (17.11 23:55) 
    And shiftbreak end time (18.11 00:10) 
    When I submit these times 
    Then I get break time of 0h 15min (0,25) 
    */
    public function testScenario3ShiftbreakWithChangingDate()
    {
        $startTime = new DateTime('17.11.2017 23:55');
        $endTime   = new DateTime('18.11.2017 00:10');

        $shiftbreak = new Shiftbreak($this->ID, $startTime, $endTime);
        $this->workshift->setShiftBreak($shiftbreak);

        $this->assertEquals('0h 15min (0,25)', $this->workshift->getShiftbreakTime());
    }

    /*
    Given shiftbreak start time (17.11 9:00) 
    And no shiftbreak end time 
    When I submit these times 
    Then I get total time of 0h 0min (0,0) 
    */
    public function testScenraio4ShiftbreakWithoutEndTime()
    {
        $startTime = new DateTime('17.11.2017 23:55');
        $endTime   = null;

        $shiftbreak = new Shiftbreak($this->ID, $startTime, $endTime);
        $this->workshift->setShiftBreak($shiftbreak);

        $this->assertEquals('0h 0min (0,00)', $this->workshift->getShiftbreakTime());
    }

    /*
    Given shiftbreak start time (17.11 9:00) 
    And shiftbreak end time (17.11. 9:15)
    And another shiftbreak start time (17.11 12:00)
    And another shiftbreak end time (17.11 12:30)
    When I submit these times 
    Then I get total time of 0h 45min (0,75) 
    */
    public function testScenario5TwoRegularShiftbreaks(){
        $work = new Work(123, new DateTime('17.11.2017 07:00'),new DateTime('17.11.2017 14:30'), new Location(60.99596, 24.46434), new Location(60.89596, 24.26434));
        $this->workshift->setWork($work);
        $shiftbreak1 = new Shiftbreak(1, new DateTime('17.11.2017 9:00'), new DateTime('17.11.2017 09:15'));
        $shiftbreak2 = new Shiftbreak(2, new DateTime('17.11.2017 12:00'), new DateTime('17.11.2017 12:30'));
        $this->workshift->setShiftbreak($shiftbreak1);
        $this->workshift->setShiftbreak($shiftbreak2);
        
        $this->assertEquals(2, count($this->workshift->getShiftBreak()));
        $this->assertEquals('0h 45min (0,75)', $this->workshift->getShiftBreakTime());
    }

    /*
    Given shiftbreak start time (17.11 10:02) 
    And shiftbreak end time (17.11. 10:17)
    And another shiftbreak start time (17.11 12:00)
    And another shiftbreak end time (17.11 11:45)
    When I submit these times 
    Then I get total time of 0h 45min (0,75) 
    */
    public function testScenario6TwoShiftbreaksAndOtherEndTimeIsBeforeStartTme(){
        $work = new Work(123, new DateTime('17.11.2017 8:00'),new DateTime('17.11.2017 15:30'), new Location(60.99596, 24.46434), new Location(60.89596, 24.26434));
        $this->workshift->setWork($work);
        $shiftbreak1 = new Shiftbreak(1, new DateTime('17.11.2017 10:02'), new DateTime('17.11.2017 10:17'));
        $shiftbreak2 = new Shiftbreak(2, new DateTime('17.11.2017 12:00'), new DateTime('17.11.2017 11:45'));
        $this->workshift->setShiftbreak($shiftbreak1);
        $this->workshift->setShiftbreak($shiftbreak2);
        
        $this->setExpectedException(Exception::class, "End time is before start time");
        $this->workshift->getShiftBreakTime();
    }

    /*
    Given shiftbreak start time (17.11 9:00) 
    And shiftbreak end time (17.11. 9:15)
    And another shiftbreak start time (17.11 12:00)
    And another shiftbreak end time is null
    When I submit these times 
    Then I get total time of 0h 15min (0,25) 
    */
    public function testScenario7TwoShiftbreaksWithoutOtherEndTime(){
        $work = new Work(123, new DateTime('17.11.2017 07:00'),new DateTime('17.11.2017 14:30'), new Location(60.99596, 24.46434), new Location(60.89596, 24.26434));
        $this->workshift->setWork($work);
        $shiftbreak1 = new Shiftbreak(1, new DateTime('17.11.2017 9:00'), new DateTime('17.11.2017 09:15'));
        $shiftbreak2 = new Shiftbreak(2, new DateTime('17.11.2017 12:00'), null);
        $this->workshift->setShiftbreak($shiftbreak1);
        $this->workshift->setShiftbreak($shiftbreak2);
        
        $this->assertEquals(2, count($this->workshift->getShiftBreak()));
        $this->assertEquals('0h 15min (0,25)', $this->workshift->getShiftBreakTime());
    }

    /*
    Given shiftbreak start time (17.11. 23:55) 
    And shiftbreak end time (18.11. 0:10)
    And another shiftbreak start time (18.11. 02:35)
    And another shiftbreak end time (18.11. 02:55)
    When I submit these times 
    Then I get total time of 0h 35min (0,58) 
    */
    public function testScenario8TwoShiftbreaksWithChangingDate(){
        $work = new Work(123, new DateTime('17.11.2017 22:00'),new DateTime('19.11.2017 06:00'), new Location(60.99596, 24.46434), new Location(60.89596, 24.26434));
        $this->workshift->setWork($work);
        $shiftbreak1 = new Shiftbreak(1, new DateTime('18.11.2017 00:05'), new DateTime('18.11.2017 00:20'));
        $shiftbreak2 = new Shiftbreak(2, new DateTime('18.11.2017 02:35'), new DateTime('18.11.2017 02:55'));
        $this->workshift->setShiftbreak($shiftbreak1);
        $this->workshift->setShiftbreak($shiftbreak2);

        $this->assertEquals(2, count($this->workshift->getShiftBreak()));
        $this->assertEquals('0h 35min (0,58)', $this->workshift->getShiftBreakTime());
    }

    /*
    Given shiftbreak start time (17.11 9:00) 
    And shiftbreak end time (17.11. 9:15)
    And another shiftbreak start time (17.11 12:00)
    And another shiftbreak end time (17.11 12:30)
    And another shiftbreak start time (17.11 14:00)
    And another shiftbreak end time (17.11 14:15)
    When I submit these times 
    Then I get total time of 1h 0min (1,0) 
    */
    public function testScenario9ThreeRegularShiftbreaks(){
        $work = new Work(123, new DateTime('17.11.2017 07:00'),new DateTime('17.11.2017 14:30'), new Location(60.99596, 24.46434), new Location(60.89596, 24.26434));
        $this->workshift->setWork($work);
        $shiftbreak1 = new Shiftbreak(1, new DateTime('17.11.2017 9:00'), new DateTime('17.11.2017 09:15'));
        $shiftbreak2 = new Shiftbreak(2, new DateTime('17.11.2017 12:00'), new DateTime('17.11.2017 12:30'));
        $shiftbreak3 = new Shiftbreak(2, new DateTime('17.11.2017 14:00'), new DateTime('17.11.2017 14:15'));
        $this->workshift->setShiftbreak($shiftbreak1);
        $this->workshift->setShiftbreak($shiftbreak2);
        $this->workshift->setShiftbreak($shiftbreak3);
        
        $this->assertEquals(3, count($this->workshift->getShiftBreak()));
        $this->assertEquals('1h 0min (1,00)', $this->workshift->getShiftBreakTime());
    }


}