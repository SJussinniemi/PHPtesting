<?php

require_once("duunissa/class/ict2017/Allowance.php");
require_once("duunissa/class/ict2017/Employee.php");
require_once("duunissa/class/ict2017/Workshift.php");
require_once("duunissa/class/ict2017/Work.php");

class UserStory_0007Test extends \Codeception\Test\Unit
{
    /**
     * As an employee 
     * I want to mark allowances/competences to myworkshift 
     * so that I can report them to my employer.
     */

    const DATETIME_FORMAT = "d.m.Y H:i";

    protected $tester;
    private $employee;
    private $work;
    private $workshift;

    protected function _before()
    {
        $employee = new Employee(123, "Jyrki", "Kolehmainen");
        $work     = new Work(123, new DateTime('17.11.2017 09:00'), null, new Location(60.99596, 24.46434), new Location(60.89596, 24.26434));

        // Given valid workshift
        $this->workshift = new Workshift($employee, $work);

        // https://www.vero.fi/en/individuals/vehicles/kilometre_and_per_diem_allowances/

        $partialPerDiem = 19;   // Partial per diem: €19 (trips longer than 6 hours)
        $fullPerDiem = 42;      //Full per diem: €42 (trips longer than 10 hours)
        $kmAllowance = 0.42;    // For 2018, the basic allowance equals 42 cents per kilometre
        $mealMoney = 10.50;     // Meal money: €10,50
        $other = 19.99;        // For example employee buys tools etc.

        // Travel allowance (kilometrikorvaus)
        $this->travelAllowance = new Allowance(1, 'kilometrikorvaus', 'kilometrikorvaus', 'KM', $kmAllowance, 60, new DateTime('17.11.2017 09:00'), 'Työmatka omalla autolla');
        // Daily allowance  (kotimaan kokopäiväraha)
        $this->DailyAllowance = new Allowance(2, 'kokopäiväraha', 'kokopäiväraha', 'KPL', $fullPerDiem, 1, new DateTime('1.12.2017 09:00'), 'Työmatkani');
        // Part daily allowance (osapäiväraha)
        $this->partDailyAllowance = new Allowance(3, 'osapäiväraha', 'osapäiväraha', 'KPL', $partialPerDiem, 1, new DateTime('17.11.2017 09:00'), 'Työmatkani');
        // Meal allowance (ateriakorvaus)
        $this->mealAllowance = new Allowance(4, 'ateriakorvaus', 'ateriakorvaus', 'KPL', $mealMoney, 1, new DateTime('17.11.2017 09:00'), 'Työmatkani');
        // Other allowance (muu korvaus)
        $this->otherAllowance = new Allowance(5, 'muu', 'muu', 'KPL', $other, 1, new DateTime('17.11.2017 09:00'), 'Korvauspyyntö työkaluista');
    }

    protected function _after()
    {
    }

    /*
    ### Scenario 1

    Given valid workshift
    And submit "Kilometrikorvaus", 60km (amount)
    Then workshift allowance count is 1
    and workshift allowance equals given allowance.

    */

    // One allowance type of travel
    public function testScenario1TravelAllowanceInWorkshift()
    {
        // And submit "Kilometrikorvaus", 60km (amount)
        $this->workshift->setAllowance($this->travelAllowance);
        // Then workshift allowance count is 1
        $this->assertEquals(1, count($this->workshift->getAllowance()));
        // and workshift allowance equals given allowance.
        $this->assertEquals(25.2, $this->workshift->getTotalCostOfAllAllowances());
    }

    // One allowance type of Full Per Diem
    public function testScenario2FullperdiemAllowanceInWorkshift()
    {
        // And submit "kokopäiväraha", 1kpl (amount)
        $this->workshift->setAllowance($this->DailyAllowance);
        // Then workshift allowance count is 1
        $this->assertEquals(1, count($this->workshift->getAllowance()));
        // and workshift allowance equals given allowance.
        $this->assertEquals(42, $this->workshift->getTotalCostOfAllAllowances());
    }

    // One allowance type of Part Per Diem
    public function testScenario3PartperdiemAllowanceInWorkshift()
    {
        // And submit "osapäiväraha", 1kpl (amount)
        $this->workshift->setAllowance($this->partDailyAllowance);
        // Then workshift allowance count is 1
        $this->assertEquals(1, count($this->workshift->getAllowance()));
        // and workshift allowance equals given allowance.
        $this->assertEquals(19, $this->workshift->getTotalCostOfAllAllowances());
    }

    // One allowance type of Meal
    public function testScenario4MealAllowanceInWorkshift()
    {
        // And submit "ateriakorvaus", 1kpl (amount)
        $this->workshift->setAllowance($this->mealAllowance);
        // Then workshift allowance count is 1
        $this->assertEquals(1, count($this->workshift->getAllowance()));
        // and workshift allowance equals given allowance.
        $this->assertEquals(10.5, $this->workshift->getTotalCostOfAllAllowances());
    }

    // One allowance type of Other
    public function testScenario5OtherAllowanceInWorkshift()
    {
        // And submit "muu", 1kpl (amount)
        $this->workshift->setAllowance($this->otherAllowance);
        // Then workshift allowance count is 1
        $this->assertEquals(1, count($this->workshift->getAllowance()));
        // and workshift allowance equals given allowance.
        $this->assertEquals(19.99, $this->workshift->getTotalCostOfAllAllowances());
    }

    // Test adding and calculating multiple allowances for workshift
    public function testScenario6CalculateTotalCosts()
    {
        // And submit "Kilometrikorvaus", 60km (amount)
        $this->workshift->setAllowance($this->travelAllowance);
        // Then workshift allowance count is 1
        $this->assertEquals(1, count($this->workshift->getAllowance()));
        // Calculating 1 allowance
        $this->assertEquals(25.2, $this->workshift->getTotalCostOfAllAllowances());

        // And submit "kokopäiväraha", 1kpl (amount)
        $this->workshift->setAllowance($this->DailyAllowance);
        // Then workshift allowance count is 2
        $this->assertEquals(2, count($this->workshift->getAllowance()));
        // Calculating 2 allowances
        $this->assertEquals(67.2, $this->workshift->getTotalCostOfAllAllowances());

        // And submit "ateriakorvaus", 1kpl (amount)
        $this->workshift->setAllowance($this->mealAllowance);
        // Then workshift allowance count is 3
        $this->assertEquals(3, count($this->workshift->getAllowance()));
        // Calculating 3 allowances
        $this->assertEquals(77.7, $this->workshift->getTotalCostOfAllAllowances());

        // And submit "muu", 1kpl (amount)
        $this->workshift->setAllowance($this->otherAllowance);
        // Then workshift allowance count is 4
        $this->assertEquals(4, count($this->workshift->getAllowance()));
        // Calculating 4 allowances
        $this->assertEquals(97.69, $this->workshift->getTotalCostOfAllAllowances());

    }
}