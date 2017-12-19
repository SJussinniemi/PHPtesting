<?php

require_once("duunissa/class/ict2017/Workshift.php");

class UserStory_0001Test extends \Codeception\Test\Unit
{
    /*
    As an Employee 
    I want to add simple workshifts 
    so that I can log my daily work hours.
    */ 

    const DATETIME_FORMAT = "d.m.Y H:i";

    protected function _before()
    {
        $employee = new Employee(123, "Jyrki", "Kolehmainen");
        $work = new Work(123, new DateTime('17.11.2017 09:00'),new DateTime('17.11.2017 16:30'),new Location(60.99596, 24.46434), new Location(60.89596, 24.26434));

        $this->workshift = new Workshift($employee, $work);
    }

    protected function _after()
    {
    }

    /*
    Scenario 1:  
    Given workshift start time (17.11 9:00) 
    And workshift end time (17.11 16:30) 
    When I submit these times 
    Then I get total work time of 7h 30min (7,5) 
    */
    public function testScenario1WorkTime()
    {  
        $work = new Work(123, new DateTime('17.11.2017 09:00'),new DateTime('17.11.2017 16:30'),new Location(60.99596, 24.46434), new Location(60.89596, 24.26434));
        $this->workshift->setWork($work);

        // Then I get total remote work time of 7h 30min (7,5)
        $this->assertEquals('7h 30min (7,50)', $this->workshift->getWorkTime());
    }

    /*
    Scenario 2:  
    Given workshift start time (17.11 16:30) 
    And workshift end time (17.11 9:00) 
    When I submit these times 
    Then I get an Error because starting time is later then end time 
    */
    public function testScenario2ErrorWhenStartTimeIsLaterThenEndTime()
    {
        $work = new Work(123, new DateTime('17.11.2017 16:30'),new DateTime('17.11.2017 09:00'), new Location(60.99596, 24.46434), new Location(60.89596, 24.26434));
        $this->workshift->setWork($work);
 
        //Then I get an Error because starting time is later then end time
        $this->ExpectException(Exception::class); 
        $this->expectExceptionMessage('End time is before start time');
        $this->workshift->getWorkTime();
    }

    /*
    Scenario 3 
    Given workshift start time (17.11 18:00) 
    And workshift end time (18.11 2:00) 
    When I submit these times 
    Then I get total work time of 8h 00min (8,0) 
    */
    public function testScenario3WorkTimeOvernight()
    {
        $work = new Work(123, new DateTime('17.11.2017 18:00'),new DateTime('18.11.2017 02:00'), new Location(60.99596, 24.46434), new Location(60.89596, 24.26434));
        $this->workshift->setWork($work);
        //Then I get total work time of 8h 00min (8,0) 
        $this->assertEquals('8h 0min (8,00)', $this->workshift->getWorkTime());
    }

    /*
    Scenario 4 
    Given workshift start time (17.11 9:00) 
    And no workshift end time 
    When I submit these times 
    Then I get total work time of 0h 00min (0,0) 
    */
    public function testScenario4WorkTimeWithNoEndTime()
    {
        // If end time is null 
        $work = new Work(123, new DateTime('17.11.2017 18:00'), null, new Location(60.99596, 24.46434), new Location(60.89596, 24.26434));
        $this->workshift->setWork($work);
        // Then I get total work time of 0h 00min (0,0)
        $this->assertEquals('0h 0min (0,00)', $this->workshift->getWorkTime());
    }

    /*
    Scenario 5:  
    Given employee identity (random integer, greater than 0, for example 231)
    And workshift start time (17.11 9:00) 
    And workshift end time (17.11 16:30) 
    When I submit these times 
    Then I get total work time of 7h 30min (7,5)
    And then workshift employee identity is 231.
    */

    /*
    A few questions

    Is there a way to return time in number or only a string? Do you force to use the current format? (7h 15min (7,25))
    How you can identify workshift from another workshift?
    Can workshift exist without employee?

    */

    public function testScenario5WorkTimeWithEmployee()
    {
        // Given employee identity (random integer, greater than 0, for example 231)
        $employee = new Employee(231, "Jyrki", "Kolehmainen");
        // And workshift start time (17.11 9:00), And workshift end time (17.11 16:30)
        $work = new Work(123, new DateTime('17.11.2017 09:00'),new DateTime('17.11.2017 16:30'),new Location(60.99596, 24.46434), new Location(60.89596, 24.26434));

        $workshift = new Workshift($employee, $work);
        
        //Then I get total work time of 7h 30min (7,5)
        $this->assertEquals('7h 30min (7,50)', $workshift->getWorkTime());

        //And then workshift employee identity is 231.
        $this->assertEquals(231, $employee->getID());

    }
}