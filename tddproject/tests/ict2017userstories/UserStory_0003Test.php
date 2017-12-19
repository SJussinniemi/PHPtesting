<?php

require_once("duunissa/class/ict2017/Travel.php");
require_once("duunissa/class/ict2017/Workshift.php");

class UserStory_0003Test extends \Codeception\Test\Unit
{
    /*
    As an Employee 
    I want to mark my travel times during my workday 
    so that I can log my daily travels.
    */

    const DATETIME_FORMAT = "d.m.Y H:i";

    private $workshift;

    protected function _before()
    {
        $employee = new Employee(123, "Jyrki", "Kolehmainen");
        $work     = new Work(123, new DateTime('17.11.2017 09:00'),
            new DateTime('17.11.2017 16:30'), new Location(60.99596, 24.46434),
            new Location(60.89596, 24.26434));

        $this->workshift = new Workshift($employee, $work);
    }

    protected function _after()
    {
    }

    /*
    Given travel start time (17.11 10:00) 
    And travel end time (17.11 10:15) 
    When I submit these times 
    Then I get travel time of 0h 15min (0,25) 
    */
    public function testScenario1RegularTravelStartAndEndTimes()
    {
        $travel = new Travel(1, new DateTime('17.11.2017 10:00'),
            new DateTime('17.11.2017 10:15'));
        $this->workshift->setTravel($travel);

        $this->assertEquals("0h 15min (0,25)", $this->workshift->getTravelTime());
    }

    /*
    Given travel start time (17.11 10:15) 
    And travel end time (17.11 10:00) 
    When I submit these times 
    Then I get an Error because starting time is later then end time 
    */
    public function testScenario2TravelEndTimeIsBeforeStartTime()
    {
        $travel = new Travel(1, new DateTime('17.11.2017 10:15'),
            new DateTime('17.11.2017 10:00'));
        $this->workshift->setTravel($travel);

        $this->setExpectedException(Exception::class, "End time is before start time");
        $this->assertEquals("0h 15min (0,25)", $this->workshift->getTravelTime());
    }

    /*
    Given travel start time (17.11 23:55) 
    And travel end time (18.11 00:10) 
    When I submit these times 
    Then I get travel time of 0h 15min (0,25)  
    */
    public function testScenario3DateChangesBetweenTravelStartAndEndTimes()
    {
        $travel = new Travel(1, new DateTime('17.11.2017 23:55'),
            new DateTime('18.11.2017 00:10'));
        $this->workshift->setTravel($travel);

        $this->assertEquals("0h 15min (0,25)", $this->workshift->getTravelTime());
    }

    /*
    Given travel start time (17.11 9:00) 
    And no travel end time 
    When I submit these times 
    Then I get total time of 0h 00min (0,00) 
    */
    public function testScenario4NoTravelEndTimeIsGiven()
    {
        $travel = new Travel(1, new DateTime('17.11.2017 9:00'), null);
        $this->workshift->setTravel($travel);

        $this->assertEquals("0h 0min (0,00)", $this->workshift->getTravelTime());
    }

    /*
    Given travel start time (17.11 9:00) 
    And travel end time (17.11. 9:30)
    And another travel start time (17.11. 16:45)
    And another travel end time (17.11. 17:15)
    When I submit these times 
    Then I get total time of 1h 00min (1,00) 
    */
    public function testScenario5TwoRegularTravels()
    {
        $travel1 = new Travel(1, new DateTime('17.11.2017 9:00'), new DateTime('17.11.2017 9:30'));
        $this->workshift->setTravel($travel1);
        $travel2 = new Travel(2, new DateTime('17.11.2017 16:45'), new DateTime('17.11.2017 17:15'));
        $this->workshift->setTravel($travel2);

        $this->assertEquals("1h 0min (1,00)", $this->workshift->getTravelTime());
    }

    /*
    Given travel start time (17.11 9:00) 
    And travel end time (17.11. 9:23)
    And another travel start time (17.11. 16:45)
    And another travel end time (17.11. 16:22)
    When I submit these times 
    Then I get total time of 1h 00min (1,00) 
    */
    public function testScenario6TwoTravelsAndOtherTravelEndTimeIsBeforeStartTime()
    {
        $travel1 = new Travel(1, new DateTime('17.11.2017 9:00'), new DateTime('17.11.2017 9:23'));
        $this->workshift->setTravel($travel1);
        $travel2 = new Travel(2, new DateTime('17.11.2017 16:45'), new DateTime('17.11.2017 16:22'));
        $this->workshift->setTravel($travel2);

        $this->setExpectedException(Exception::class, "End time is before start time");
        $this->workshift->getTravelTime();
    }

    /*
    Given travel start time (17.11 15:00) 
    And travel end time (17.11. 16:12)
    And another travel start time (17.11. 23:03)
    And another travel end time (18.11. 00:15)
    When I submit these times 
    Then I get total time of 2h 24min (2,40) 
    */
    public function testScenario7TwoTravelsWithChangingDate()
    {
        $travel1 = new Travel(1, new DateTime('17.11.2017 15:00'), new DateTime('17.11.2017 16:12'));
        $this->workshift->setTravel($travel1);
        $travel2 = new Travel(2, new DateTime('17.11.2017 23:03'), new DateTime('18.11.2017 00:15'));
        $this->workshift->setTravel($travel2);

        $this->assertEquals("2h 24min (2,40)", $this->workshift->getTravelTime());
    }

    /*
    Given travel start time (17.11 8:23) 
    And travel end time (17.11. 08:57)
    And another travel start time (17.11. 16:11)
    And another travel end time null
    When I submit these times 
    Then I get total time of 0h 34min (0,57) 
    */
    public function testScenario8TwoTravelsWithoutOtherEndTime()
    {
        $travel1 = new Travel(1, new DateTime('17.11.2017 8:23'), new DateTime('17.11.2017 8:57'));
        $this->workshift->setTravel($travel1);
        $travel2 = new Travel(2, new DateTime('17.11.2017 16:11'), null);
        $this->workshift->setTravel($travel2);

        $this->assertEquals("0h 34min (0,57)", $this->workshift->getTravelTime());
    }

    /*
    Given travel start time (17.11 9:00) 
    And travel end time (17.11. 9:30)
    And another travel start time (17.11. 12:47)
    And another travel end time (17.11. 13:32)
    And another travel start time (17.11. 16:45)
    And another travel end time (17.11. 17:15)
    When I submit these times 
    Then I get total time of 1h 45min (1,75) 
    */
    public function testScenario9ThreeRegularTravels()
    {
        $travel1 = new Travel(1, new DateTime('17.11.2017 9:00'), new DateTime('17.11.2017 9:30'));
        $this->workshift->setTravel($travel1);
        $travel2 = new Travel(2, new DateTime('17.11.2017 12:47'), new DateTime('17.11.2017 13:32'));
        $this->workshift->setTravel($travel2);
        $travel3 = new Travel(2, new DateTime('17.11.2017 16:45'), new DateTime('17.11.2017 17:15'));
        $this->workshift->setTravel($travel3);

        $this->assertEquals("1h 45min (1,75)", $this->workshift->getTravelTime());
    }

}