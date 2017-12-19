<?php

require_once("duunissa/class/ict2017/Workshift.php");
require_once("duunissa/class/ict2017/Log.php");
require_once("duunissa/class/ict2017/Work.php");
require_once("duunissa/class/ict2017/DateTimeCalculator.php");
require_once("duunissa/class/ict2017/Employee.php");
require_once("duunissa/class/ict2017/Product.php");

class WorkshiftTest extends \Codeception\Test\Unit
{
    const DATETIME_FORMAT = "d.m.Y H:i";

    protected function _before()
    {
        // Valid workshift
        $this->employee = new Employee(123, "Jyrki", "Kolehmainen");
        $this->work     = new Work(123, new DateTime('17.11.2017 09:00'), null,
            new Location(60.99596, 24.46434), new Location(60.99596, 24.46434));

        $this->workshift = new Workshift($this->employee, $this->work);

        // Lunchbreak
        $breakStartTime = new DateTime('21.10.2017 11:30');
        $breakEndTime   = new DateTime('21.10.2017 12:00');
        $this->lunch    = new LunchBreak($breakStartTime, $breakEndTime);

    }

    protected function _after()
    {
    }

    public function testWorkshiftClassFound()
    {
        $this->assertInstanceOf(Workshift::class, $this->workshift);
    }

    public function testWorkshiftWithShiftbreak()
    {

        $shiftbreak = new Shiftbreak(123, new DateTime('17.11.2017 10:00'),
            new DateTime('17.11.2017 10:15'));
        $this->workshift->setShiftBreak($shiftbreak);

        $work = $this->workshift->getWork();
        $work->setWorkEndTime(new DateTime('17.11.2017 16:30'));
        $this->workshift->setWork($work);

        $this->assertEquals($this->workshift->getWorkTime(), "7h 15min (7,25)");
    }

    public function testWorkshiftWithTravel()
    {
        $travel = new Travel(1, new DateTime('17.11.2017 9:00'),
            new DateTime('17.11.2017 10:00'));
        $this->workshift->setTravel($travel);

        $work = $this->workshift->getWork();
        $work->setWorkEndTime(new DateTime('17.11.2017 16:30'));
        $this->workshift->setWork($work);

        $this->assertEquals($this->workshift->getWorkTime(), "6h 30min (6,50)");
        $this->assertEquals($this->workshift->getTravelTime(), "1h 0min (1,00)");
    }

    public function testWorkshiftIncorrectTravelTime()
    {
        $travel = new Travel(1, new DateTime('17.11.2017 9:00'), null);
        $this->workshift->setTravel($travel);

        $work = $this->workshift->getWork();
        $work->setWorkEndTime(new DateTime('17.11.2017 16:30'));
        $this->workshift->setWork($work);

        $this->assertEquals($this->workshift->getWorkTime(), "7h 30min (7,50)");
        $this->assertEquals($this->workshift->getTravelTime(), "0h 0min (0,00)");
    }

    public function testWorkshiftIncorrectShiftbreakTime()
    {
        $shiftbreak = new Shiftbreak(123, new DateTime('17.11.2017 12:00'), null);
        $this->workshift->setShiftBreak($shiftbreak);

        $work = $this->workshift->getWork();
        $work->setWorkEndTime(new DateTime('17.11.2017 16:30'));
        $this->workshift->setWork($work);

        $this->assertEquals($this->workshift->getWorkTime(), "7h 30min (7,50)");
        $this->assertEquals($this->workshift->getTravelTime(), "0h 0min (0,00)");
    }

    public function testWorkshiftWithTravelAndShiftbreakTime()
    {
        $travel = new Travel(1, new DateTime('17.11.2017 9:15'),
            new DateTime('17.11.2017 9:45'));
        $this->workshift->setTravel($travel);

        $shiftbreak = new Shiftbreak(123, new DateTime('17.11.2017 12:00'),
            new DateTime('17.11.2017 12:45'));
        $this->workshift->setShiftBreak($shiftbreak);

        $work = $this->workshift->getWork();
        $work->setWorkEndTime(new DateTime('17.11.2017 16:30'));
        $this->workshift->setWork($work);

        $this->assertEquals($this->workshift->getWorkTime(), "6h 15min (6,25)");
        $this->assertEquals($this->workshift->getTravelTime(), "0h 30min (0,50)");
        $this->assertEquals($this->workshift->getShiftbreakTime(), "0h 45min (0,75)");
    }

    public function testWorkshiftIsNotRemote()
    {
        $this->assertEquals(false, $this->workshift->getWork()->getRemoteWorkStatus());
    }

    public function testWorkshiftIsRemote()
    {
        $work = $this->workshift->getWork();
        $work->startRemoteWorking();

        $this->assertEquals(true, $work->getRemoteWorkStatus());
    }

    // Product gets added to workshift and product count is 1
    public function testAddingOneProduct()
    {
        $work = new Work(123, new DateTime('17.11.2017 09:00'),
            new DateTime('17.11.2017 16:30'), new Location(60.99596, 24.46434),
            new Location(60.89596, 24.26434));
        $this->workshift->setWork($work);

        $product = new Product(2, 'Plank', 'default', 'pcs', 9.99, 4, 0.24,
            new DateTime('17.11.2017 12:00'));
        $this->workshift->addProduct($product);

        $this->assertEquals(1, count($this->workshift->getProducts()));
    }

    // Products gets added to workshift and product count is 2
    public function testAddingMultipleProducts()
    {
        $work = new Work(123, new DateTime('17.11.2017 09:00'),
            new DateTime('17.11.2017 16:30'), new Location(60.99596, 24.46434),
            new Location(60.89596, 24.26434));
        $this->workshift->setWork($work);
        $product  = new Product(2, 'Plank', 'default', 'pcs', 9.99, 4, 0.24,
            new DateTime('17.11.2017 12:00'));
        $product2 = new Product(5, 'Soap', 'default', 'kg', 1.50, 2, 0.24,
            new DateTime('17.11.2017 12:30'));
        $this->workshift->addProduct($product);
        $this->workshift->addProduct($product2);

        $this->assertEquals(2, count($this->workshift->getProducts()));
    }

    public function testCreatingWorkshiftInvalidEmployee()
    {
        $this->setExpectedException(Exception::class, 'Employee ID should be greater than 0.');

        $employee = new Employee(null, null, null);
        $work     = new Work(123, new DateTime('17.11.2017 09:00'),
            new DateTime('17.11.2017 16:30'), new Location(60.99596, 24.46434),
            new Location(60.89596, 24.26434));

        new Workshift($employee, $work);
    }

    // test that getting allowance array works.
    public function testGetAllowances()
    {
        $this->workshift->getAllowance();
    }

    // test add one allowance to array and check its size of 1
    public function testSetAllowance()
    {
        $allowance = new Allowance(1, 'kilometrikorvaus', 'kilometrikorvaus', 'KM', 0.42, 60,
            new DateTime('17.11.2017 09:00'), 'Työmatkani');

        $this->workshift->setAllowance($allowance);
        $this->assertEquals(1, count($this->workshift->getAllowance()));

    }

    // test add two different allowance to array and check its size of 2
    public function testSetTwoDifferentAllowances()
    {
        $allowanceTravel = new Allowance(1, 'kilometrikorvaus', 'kilometrikorvaus', 'KM', 0.42, 60,
            new DateTime('17.11.2017 09:00'), 'Työmatkani');
        $allowanceMeal   = new Allowance(2, 'Ateria korvaus', 'Ateria korvaus', 'KPL', 1.90, 1,
            new DateTime('17.11.2017 12:00'), 'Lounas');

        $this->workshift->setAllowance($allowanceTravel);
        $this->workshift->setAllowance($allowanceMeal);

        $this->assertEquals(2, count($this->workshift->getAllowance()));

    }

    // test add two same allowance to array and it replaces old entry with new entry.
    public function testSetTwoSameAllowances()
    {
        $allowanceToWork = new Allowance(5, 'kilometrikorvaus', 'kilometrikorvaus', 'KM', 0.42, 30,
            new DateTime('17.11.2017 09:00'), 'Työmatkani töihin');
        $allowanceToHome = new Allowance(5, 'kilometrikorvaus', 'kilometrikorvaus', 'KM', 0.42, 60,
            new DateTime('17.11.2017 16:00'), 'Työmatkani kotiin');

        /*
            Enter travel allowance with amount value of 30.
            Test sees that allowances array has 1 item in it
            Since its first item index should be 0
            And that items amount value is 30.
        */
        $this->workshift->setAllowance($allowanceToWork);
        $allos = $this->workshift->getAllowance();
        $this->assertEquals(1, count($this->workshift->getAllowance()));
        $this->assertEquals(30, $allos[0]->getAmount());

        /*
            Enter travel allowance with amount value of 60.
            Since this is same kind of allowance then previosly
            This should replace the old entry
            Test sees that allowances array has 1 item in it
            Since its first item index should be 0
            And that items amount value is 60.
        */

        $this->workshift->setAllowance($allowanceToHome);
        $allos = $this->workshift->getAllowance();
        $this->assertEquals(1, count($this->workshift->getAllowance()));
        $this->assertEquals(60, $allos[0]->getAmount());
    }

    // test calculating costs of 0 allowances
    public function testCalculateCostZeroAllowanceItem()
    {
        $this->assertEquals(0, $this->workshift->getTotalCostOfAllAllowances($this->workshift->getAllowance()));
    }

    // test calculating costs of 1 allowances
    public function testCalculateCostOneAllowanceItem()
    {
        $allowanceTravel = new Allowance(1, 'kilometrikorvaus', 'kilometrikorvaus', 'KM', 0.42, 60,
            new DateTime('17.11.2017 09:00'), 'Työmatkani');
        $this->workshift->setAllowance($allowanceTravel);

        $this->assertEquals(25.2, $this->workshift->getTotalCostOfAllAllowances($this->workshift->getAllowance()));
    }

    // test calculating costs of 2 allowances
    public function testCalculateCostTwoAllowanceItem()
    {
        $allowanceTravel = new Allowance(1, 'kilometrikorvaus', 'kilometrikorvaus', 'KM', 0.42, 60,
            new DateTime('17.11.2017 09:00'), 'Työmatkani');
        $allowanceMeal   = new Allowance(2, 'Ateria korvaus', 'Ateria korvaus', 'KPL', 1.90, 1,
            new DateTime('17.11.2017 12:00'), 'Lounas');
        $this->workshift->setAllowance($allowanceTravel);
        $this->workshift->setAllowance($allowanceMeal);

        $this->assertEquals(27.1, $this->workshift->getTotalCostOfAllAllowances($this->workshift->getAllowance()));
    }

    // test getting three shiftbreaks
    public function testGetMultipleShiftbreaks()
    {
        $shiftbreak1 = new Shiftbreak(1, new DateTime('17.11.2017 11:00'), new DateTime('17.11.2017 11:15'));
        $shiftbreak2 = new Shiftbreak(2, new DateTime('17.11.2017 12:15'), new DateTime('17.11.2017 12:45'));
        $shiftbreak3 = new Shiftbreak(3, new DateTime('17.11.2017 14:15'), new DateTime('17.11.2017 14:30'));

        $this->workshift->setShiftbreak($shiftbreak1);
        $this->workshift->setShiftbreak($shiftbreak2);
        $this->workshift->setShiftbreak($shiftbreak3);

        $this->assertEquals(3, count($this->workshift->getShiftBreak()));
    }

    // test getting total time of two shiftbreaks
    public function testWorkshiftWithTwoShiftbreaks()
    {
        $work = $this->workshift->getWork();
        $work->setWorkEndTime(new DateTime('17.11.2017 16:30'));
        $this->workshift->setWork($work);

        $shiftbreak1 = new Shiftbreak(1, new DateTime('17.11.2017 11:00'), new DateTime('17.11.2017 11:15'));
        $shiftbreak2 = new Shiftbreak(2, new DateTime('17.11.2017 14:15'), new DateTime('17.11.2017 14:45'));

        $this->workshift->setShiftbreak($shiftbreak1);
        $this->workshift->setShiftbreak($shiftbreak2);

        $this->assertEquals("6h 45min (6,75)", $this->workshift->getWorkTime());
        $this->assertEquals(2, count($this->workshift->getShiftBreak()));
        $this->assertEquals('0h 45min (0,75)', $this->workshift->getShiftBreakTime());
    }

    // test multiple travels
    public function testGetMultipleTravels()
    {
        $travel1 = new Travel(1, new DateTime('17.11.2017 9:15'), new DateTime('17.11.2017 9:45'));
        $travel2 = new Travel(2, new DateTime('17.11.2017 12:30'), new DateTime('17.11.2017 13:00'));
        $travel3 = new Travel(3, new DateTime('17.11.2017 16:30'), new DateTime('17.11.2017 17:00'));

        $this->workshift->setTravel($travel1);
        $this->workshift->setTravel($travel2);
        $this->workshift->setTravel($travel3);

        $this->assertEquals(3, count($this->workshift->getTravel()));
    }

    // test getting total time of two travels
    public function testWorkShiftWithTwoTravels()
    {
        $work = $this->workshift->getWork();
        $work->setWorkEndTime(new DateTime('17.11.2017 16:30'));
        $this->workshift->setWork($work);

        $travel1 = new Travel(1, new DateTime('17.11.2017 9:15'), new DateTime('17.11.2017 9:45'));
        $travel2 = new Travel(2, new DateTime('17.11.2017 16:30'), new DateTime('17.11.2017 17:00'));

        $this->workshift->setTravel($travel1);
        $this->workshift->setTravel($travel2);

        $this->assertEquals("6h 30min (6,50)", $this->workshift->getWorkTime());
        $this->assertEquals(2, count($this->workshift->getTravel()));
        $this->assertEquals('1h 0min (1,00)', $this->workshift->getTravelTime());
    }

    // test getting total time of two travels and shiftbreaks
    public function testWorkshiftWithTwoShiftbreaksAndTwoTravels()
    {
        $work = $this->workshift->getWork();
        $work->setWorkEndTime(new DateTime('17.11.2017 16:30'));
        $this->workshift->setWork($work);

        $travel1     = new Travel(1, new DateTime('17.11.2017 9:15'), new DateTime('17.11.2017 9:45'));
        $travel2     = new Travel(2, new DateTime('17.11.2017 16:30'), new DateTime('17.11.2017 17:00'));
        $shiftbreak1 = new Shiftbreak(1, new DateTime('17.11.2017 11:00'), new DateTime('17.11.2017 11:15'));
        $shiftbreak2 = new Shiftbreak(2, new DateTime('17.11.2017 14:15'), new DateTime('17.11.2017 14:45'));

        $this->workshift->setShiftbreak($shiftbreak1);
        $this->workshift->setShiftbreak($shiftbreak2);
        $this->workshift->setTravel($travel1);
        $this->workshift->setTravel($travel2);

        $this->assertEquals("5h 45min (5,75)", $this->workshift->getWorkTime());
        $this->assertEquals(2, count($this->workshift->getTravel()));
        $this->assertEquals('1h 0min (1,00)', $this->workshift->getTravelTime());
        $this->assertEquals(2, count($this->workshift->getShiftBreak()));
        $this->assertEquals('0h 45min (0,75)', $this->workshift->getShiftBreakTime());
    }

    // test having multiple workshifts in one day
    public function testTwoWorkshiftStartingInSameDay()
    {
        $work = $this->workshift->getWork();
        $work->setWorkEndTime(new DateTime('17.11.2017 16:30'));
        $this->workshift->setWork($work);

        $employee = $this->workshift->getEmployee();
        $work2    = new Work(666, new DateTime('17.11.2017 23:00'), new DateTime('18.11.2017 05:57'));

        $this->workshift2 = new Workshift($employee, $work2);

        $this->assertEquals("7h 30min (7,50)", $this->workshift->getWorkTime());
        $this->assertEquals("6h 57min (6,95)", $this->workshift2->getWorkTime());
    }

    // Test Getters & Setters
    public function testCreateLunchbreak()
    {
        $this->workshift->setLunchBreak($this->lunch);
        $this->assertEquals($this->lunch, $this->workshift->getLunchBreak());
    }

    public function testGetLunchbreakTime()
    {
        //lunchbreak is 30min.
        $this->workshift->setLunchBreak($this->lunch);
        $this->assertEquals("0h 30min (0,50)", $this->workshift->getLunchBreakTime());
    }

    public function testGetWorkTimeMinusLunch()
    {
        // workshift is between 9 and 16.30.
        $this->work->setWorkEndTime(new DateTime('17.11.2017 16:30'));
        $this->workshift->setWork($this->work);

        // lunchbreak is 30min
        $this->workshift->setLunchBreak($this->lunch);

        // total worktime should be 7h (worktime - lunch)
        $this->assertEquals('7h 0min (7,00)', $this->workshift->getWorkTimeMinusLunch());
    }

    public function testGetWorkTimeMinusLunchWithBreaks()
    {
        // workshift is between 9 and 16.30.
        $this->work->setWorkEndTime(new DateTime('17.11.2017 16:30'));
        $this->workshift->setWork($this->work);

        // Two shiftbreaks total of 30min
        $shiftbreak  = new Shiftbreak(123, new DateTime('17.11.2017 09:45'), new DateTime('17.11.2017 10:00'));
        $shiftbreak2 = new Shiftbreak(123, new DateTime('17.11.2017 14:00'), new DateTime('17.11.2017 14:15'));
        $this->workshift->setShiftBreak($shiftbreak);
        $this->workshift->setShiftBreak($shiftbreak2);

        // lunchbreak is 30min
        $this->workshift->setLunchBreak($this->lunch);

        // total worktime should be 6.5h (worktime - lunch - breaks)
        $this->assertEquals('6h 30min (6,50)', $this->workshift->getWorkTimeMinusLunch());
    }

    public function testGetWorkTimeMinusLunchWithTravel()
    {
        // workshift is between 9 and 16.30.
        $this->work->setWorkEndTime(new DateTime('17.11.2017 16:30'));
        $this->workshift->setWork($this->work);

        // Traveltime is 1h
        $travel = new Travel(1, new DateTime('17.11.2017 9:15'), new DateTime('17.11.2017 10:15'));
        $this->workshift->setTravel($travel);

        // lunchbreak is 30min
        $this->workshift->setLunchBreak($this->lunch);

        // total worktime should be 6.5h (worktime - lunch - travel)
        $this->assertEquals('6h 0min (6,00)', $this->workshift->getWorkTimeMinusLunch());

    }

    public function testGetWorkTimeMinusLunchWithTravelAndBreaks()
    {
        // workshift is between 9 and 16.30.
        $this->work->setWorkEndTime(new DateTime('17.11.2017 16:30'));
        $this->workshift->setWork($this->work);

        // Traveltime is 1h
        $travel = new Travel(1, new DateTime('17.11.2017 9:15'), new DateTime('17.11.2017 10:15'));
        $this->workshift->setTravel($travel);

        // Two shiftbreaks total of 30min
        $shiftbreak  = new Shiftbreak(123, new DateTime('17.11.2017 09:45'), new DateTime('17.11.2017 10:00'));
        $shiftbreak2 = new Shiftbreak(123, new DateTime('17.11.2017 14:00'), new DateTime('17.11.2017 14:15'));
        $this->workshift->setShiftBreak($shiftbreak);
        $this->workshift->setShiftBreak($shiftbreak2);

        // lunchbreak is 30min
        $this->workshift->setLunchBreak($this->lunch);

        // total worktime should be 6.5h (worktime - lunch - travel)
        $this->assertEquals('5h 30min (5,50)', $this->workshift->getWorkTimeMinusLunch());
    }

    /*
        This test emulates one workday
    */
    public function testWorkshiftWithTravelsBreaksLocationsProductsAndAllowances()
    {
        $work = $this->workshift->getWork();
        $work->setWorkEndTime(new DateTime('17.11.2017 16:30'));
        $this->workshift->setWork($work);

        // Set project
        $project = new Project(1, 'Project1');
        $this->workshift->setProject($project);

        // Set two travels
        $travel1 = new Travel(1, new DateTime('17.11.2017 8:15'), new DateTime('17.11.2017 8:45'),
            new Location(60.99596, 24.46434), new Location(60.89596, 24.26434));
        $travel2 = new Travel(2, new DateTime('17.11.2017 16:35'), new DateTime('17.11.2017 17:05'),
            new Location(60.89596, 24.26434), new Location(60.99596, 24.46434));
        $this->workshift->setTravel($travel1);
        $this->workshift->setTravel($travel2);

        // Set two shiftbreaks
        $shiftbreak1 = new Shiftbreak(1, new DateTime('17.11.2017 11:00'), new DateTime('17.11.2017 11:15'),
            new Location(60.89596, 24.26434), new Location(60.89596, 24.26434));
        $shiftbreak2 = new Shiftbreak(2, new DateTime('17.11.2017 14:15'), new DateTime('17.11.2017 14:45'),
            new Location(60.89596, 24.26434), new Location(60.89596, 24.26434));
        $this->workshift->setShiftbreak($shiftbreak1);
        $this->workshift->setShiftbreak($shiftbreak2);

        // Add two products
        $product1 = new Product(2, 'Plank', 'default', 'pcs', 9.99, 4, 0.24, new DateTime('17.11.2017 10:00'));
        $product2 = new Product(5, 'Soap', 'default', 'kg', 1.50, 2, 0.24, new DateTime('17.11.2017 13:30'));
        $this->workshift->addProduct($product1);
        $this->workshift->addProduct($product2);

        // Set travel and meal allowances
        $allowanceTravel = new Allowance(1, 'kilometrikorvaus', 'kilometrikorvaus', 'KM', 0.42, 60,
            new DateTime('17.11.2017 09:00'), 'Työmatkani');
        $allowanceMeal   = new Allowance(2, 'Ateria korvaus', 'Ateria korvaus', 'KPL', 1.90, 1,
            new DateTime('17.11.2017 12:00'), 'Lounas');
        $this->workshift->setAllowance($allowanceTravel);
        $this->workshift->setAllowance($allowanceMeal);

        // Add two logs
        $this->message1   = 'Workshift log message 1.';
        $this->timestamp1 = '29.11.2017 09:15';
        $log1             = new Log(1, $this->message1, new DateTime($this->timestamp1));

        $this->message2   = 'Workshift log message 2.';
        $this->timestamp2 = '29.11.2017 15:06';
        $log2             = new Log(2, $this->message2, new DateTime($this->timestamp2));

        $this->workshift->addLog($log1);
        $this->workshift->addLog($log2);

        // test total time of work
        $this->assertEquals("5h 45min (5,75)", $this->workshift->getWorkTime());
        // test get project
        $this->assertEquals($project, $this->workshift->getProject());
        // test total time of travels and that there is two travels
        $this->assertEquals(2, count($this->workshift->getTravel()));
        $this->assertEquals('1h 0min (1,00)', $this->workshift->getTravelTime());
        // test total time of shiftbreaks and that there is two breaks
        $this->assertEquals(2, count($this->workshift->getShiftBreak()));
        $this->assertEquals('0h 45min (0,75)', $this->workshift->getShiftBreakTime());
        // test that there is two products
        $this->assertEquals(2, count($this->workshift->getProducts()));
        // test total cost of allowances and that there is two allowances
        $this->assertEquals(2, count($this->workshift->getAllowance()));
        $this->assertEquals(27.1, $this->workshift->getTotalCostOfAllAllowances($this->workshift->getAllowance()));
        //test that there is two logs
        $this->assertEquals(2, count($this->workshift->getLogs()));

        // work start location
        $this->assertEquals(new Location(60.99596, 24.46434), $this->workshift->getWork()->getWorkStartLocation());
        $this->assertEquals(60.99596, $this->workshift->getWork()->getWorkStartLocation()->getLatitude());
        $this->assertEquals(24.46434, $this->workshift->getWork()->getWorkStartLocation()->getLongitude());
        // work end location
        $this->assertEquals(new Location(60.99596, 24.46434), $this->workshift->getWork()->getWorkEndLocation());
        $this->assertEquals(60.99596, $this->workshift->getWork()->getWorkEndLocation()->getLatitude());
        $this->assertEquals(24.46434, $this->workshift->getWork()->getWorkEndLocation()->getLongitude());

        $travels = $this->workshift->getTravel();
        // travel1 start location
        $this->assertEquals(new Location(60.99596, 24.46434), $travels[0]->getTravelStartLocation());
        $this->assertEquals(60.99596, $travels[0]->getTravelStartLocation()->getLatitude());
        $this->assertEquals(24.46434, $travels[0]->getTravelStartLocation()->getLongitude());
        // travel1 end location
        $this->assertEquals(new Location(60.89596, 24.26434), $travels[0]->getTravelEndLocation());
        $this->assertEquals(60.89596, $travels[0]->getTravelEndLocation()->getLatitude());
        $this->assertEquals(24.26434, $travels[0]->getTravelEndLocation()->getLongitude());

        // travel2 start location
        $this->assertEquals(new Location(60.89596, 24.26434), $travels[1]->getTravelStartLocation());
        $this->assertEquals(60.89596, $travels[1]->getTravelStartLocation()->getLatitude());
        $this->assertEquals(24.26434, $travels[1]->getTravelStartLocation()->getLongitude());
        // travel2 end location
        $this->assertEquals(new Location(60.99596, 24.46434), $travels[1]->getTravelEndLocation());
        $this->assertEquals(60.99596, $travels[1]->getTravelEndLocation()->getLatitude());
        $this->assertEquals(24.46434, $travels[1]->getTravelEndLocation()->getLongitude());

        $breaks = $this->workshift->getShiftbreak();
        // shiftbreak1 start location
        $this->assertEquals(new Location(60.89596, 24.26434), $breaks[0]->getBreakStartLocation());
        $this->assertEquals(60.89596, $breaks[0]->getBreakStartLocation()->getLatitude());
        $this->assertEquals(24.26434, $breaks[0]->getBreakStartLocation()->getLongitude());
        // shiftbreak1 end location
        $this->assertEquals(new Location(60.89596, 24.26434), $breaks[0]->getBreakEndLocation());
        $this->assertEquals(60.89596, $breaks[0]->getBreakEndLocation()->getLatitude());
        $this->assertEquals(24.26434, $breaks[0]->getBreakEndLocation()->getLongitude());

        // shiftbreak2 start location
        $this->assertEquals(new Location(60.89596, 24.26434), $breaks[1]->getBreakStartLocation());
        $this->assertEquals(60.89596, $breaks[1]->getBreakStartLocation()->getLatitude());
        $this->assertEquals(24.26434, $breaks[1]->getBreakStartLocation()->getLongitude());
        // shiftbreak2 end location
        $this->assertEquals(new Location(60.89596, 24.26434), $breaks[1]->getBreakEndLocation());
        $this->assertEquals(60.89596, $breaks[1]->getBreakEndLocation()->getLatitude());
        $this->assertEquals(24.26434, $breaks[1]->getBreakEndLocation()->getLongitude());
    }

    // test getting total price of all products without taxes
    public function testGetTotalPriceOfAllProductsWithoutTaxes()
    {
        // Add two products: two planks each costs 9.99 and one soap costs 1.50
        $product1 = new Product(2, 'Plank', 'default', 'pcs', 9.99, 2, 0.24,
            DateTime::createFromFormat('d.m.Y H:i', '17.11.2017 10:00'));
        $product2 = new Product(5, 'Soap', 'default', 'kg', 1.50, 1, 0.24,
            DateTime::createFromFormat('d.m.Y H:i', '17.11.2017 13:30'));
        $this->workshift->addProduct($product1);
        $this->workshift->addProduct($product2);

        $this->assertEquals(21.48, $this->workshift->getTotalPriceOfAllProductsWithoutTaxes());
    }

    // test getting total price of all products with taxes
    public function testGetTotalPriceOfAllProductsWithTaxes()
    {
        // Add two products: two planks each costs 9.99 tax 0.24 and one soap costs 1.50 tax 0.24
        $product1 = new Product(2, 'Plank', 'default', 'pcs', 9.99, 2, 0.24,
            DateTime::createFromFormat('d.m.Y H:i', '17.11.2017 10:00'));
        $product2 = new Product(5, 'Soap', 'default', 'kg', 1.50, 1, 0.24,
            DateTime::createFromFormat('d.m.Y H:i', '17.11.2017 13:30'));
        $this->workshift->addProduct($product1);
        $this->workshift->addProduct($product2);

        $this->assertEquals(26.64, $this->workshift->getTotalPriceOfAllProductsWithTaxes());
    }

    // test getting total price by product rows without taxes
    public function testGetTotalPriceByProductRowsWithoutTaxes()
    {
        // Add two products: two planks each costs 9.99 tax 0.24 and one soap costs 1.50 tax 0.24
        $product1 = new Product(2, 'Plank', 'default', 'pcs', 9.99, 2, 0.24,
            DateTime::createFromFormat('d.m.Y H:i', '17.11.2017 10:00'));
        $product2 = new Product(5, 'Soap', 'default', 'kg', 1.50, 3, 0.24,
            DateTime::createFromFormat('d.m.Y H:i', '17.11.2017 13:30'));
        $this->workshift->addProduct($product1);
        $this->workshift->addProduct($product2);

        $products = $this->workshift->getTotalPriceByProductRowsWithoutTaxes();

        $this->assertEquals(19.98, $products[0]);
        $this->assertEquals(4.50, $products[1]);
    }

    // test getting total price by product rows with taxes
    public function testGetTotalPriceByProductRowsWithTaxes()
    {
        // Add two products: two planks each costs 9.99 tax 0.24 and one soap costs 1.50 tax 0.24
        $product1 = new Product(2, 'Plank', 'default', 'pcs', 9.99, 2, 0.24,
            DateTime::createFromFormat('d.m.Y H:i', '17.11.2017 10:00'));
        $product2 = new Product(5, 'Soap', 'default', 'kg', 1.50, 3, 0.24,
            DateTime::createFromFormat('d.m.Y H:i', '17.11.2017 13:30'));
        $this->workshift->addProduct($product1);
        $this->workshift->addProduct($product2);

        $products = $this->workshift->getTotalPriceByProductRowsWithTaxes();

        $this->assertEquals(24.78, $products[2]);
        $this->assertEquals(5.58, $products[5]);
    }

    // test getting total cost by allowance rows
    public function testGetTotalCostByAllowanceRows()
    {
        // Set travel and meal allowances
        $allowanceTravel = new Allowance(1, 'kilometrikorvaus', 'kilometrikorvaus', 'KM', 0.42, 60,
            new DateTime('17.11.2017 09:00'), 'Työmatkani');
        $allowanceMeal   = new Allowance(2, 'Ateria korvaus', 'Ateria korvaus', 'KPL', 1.90, 1,
            new DateTime('17.11.2017 12:00'), 'Lounas');
        $this->workshift->setAllowance($allowanceTravel);
        $this->workshift->setAllowance($allowanceMeal);

        $allowances = $this->workshift->getTotalCostByAllowanceRows();

        $this->assertEquals(25.20, $allowances[1]);
        $this->assertEquals(1.90, $allowances[2]);
    }

    public function testIsOvertime()
    {
        $work = $this->workshift->getWork();

        $work->setWorkStartTime(DateTime::createFromFormat(self::DATETIME_FORMAT, '04.12.2017 09:00'));
        $work->setWorkEndTime(DateTime::createFromFormat(self::DATETIME_FORMAT, '04.12.2017 10:00'));
        $this->assertEquals(0, $this->workshift->getOvertime());

        $work->setWorkStartTime(DateTime::createFromFormat(self::DATETIME_FORMAT, '04.12.2017 09:00'));
        $work->setWorkEndTime(DateTime::createFromFormat(self::DATETIME_FORMAT, '04.12.2017 23:00'));
        $this->assertEquals(360, $this->workshift->getOvertime());
    }

    public function testIsOvertimeWithOtherLimit()
    {
        $work = $this->workshift->getWork();

        $shiftLimit = new ShiftLimit(4);
        $this->workshift->setShiftLimit($shiftLimit);

        $work->setWorkStartTime(DateTime::createFromFormat(self::DATETIME_FORMAT, '04.12.2017 09:00'));
        $work->setWorkEndTime(DateTime::createFromFormat(self::DATETIME_FORMAT, '04.12.2017 10:00'));
        $this->assertEquals(0, $this->workshift->getOvertime());

        $work->setWorkStartTime(DateTime::createFromFormat(self::DATETIME_FORMAT, '04.12.2017 09:00'));
        $work->setWorkEndTime(DateTime::createFromFormat(self::DATETIME_FORMAT, '04.12.2017 14:00'));
        $this->assertEquals(60, $this->workshift->getOvertime());
    }

    public function testIsEveningShift()
    {
        $work = $this->workshift->getWork();

        $work->setWorkStartTime(DateTime::createFromFormat(self::DATETIME_FORMAT, '04.12.2017 09:00'));
        $work->setWorkEndTime(DateTime::createFromFormat(self::DATETIME_FORMAT, '04.12.2017 10:00'));
        $this->assertEquals(0, $this->workshift->getEveningShiftTime());

        $work->setWorkStartTime(DateTime::createFromFormat(self::DATETIME_FORMAT, '04.12.2017 09:00'));
        $work->setWorkEndTime(DateTime::createFromFormat(self::DATETIME_FORMAT, '04.12.2017 23:00'));
        $this->assertEquals(300, $this->workshift->getEveningShiftTime());
    }

    public function testIsEveningShiftWithOtherPeriod()
    {
        $work = $this->workshift->getWork();

        $eveningshiftPeriod = new EveningshiftPeriod('12:00', '18:00');
        $this->workshift->setEveningshiftPeriod($eveningshiftPeriod);

        $work->setWorkStartTime(DateTime::createFromFormat(self::DATETIME_FORMAT, '04.12.2017 09:00'));
        $work->setWorkEndTime(DateTime::createFromFormat(self::DATETIME_FORMAT, '04.12.2017 10:00'));
        $this->assertEquals(0, $this->workshift->getEveningShiftTime());

        $work->setWorkStartTime(DateTime::createFromFormat(self::DATETIME_FORMAT, '04.12.2017 13:00'));
        $work->setWorkEndTime(DateTime::createFromFormat(self::DATETIME_FORMAT, '04.12.2017 17:00'));
        $this->assertEquals(240, $this->workshift->getEveningShiftTime());
    }

    public function testIsNightShift()
    {
        $work = $this->workshift->getWork();

        $work->setWorkStartTime(DateTime::createFromFormat(self::DATETIME_FORMAT, '04.12.2017 09:00'));
        $work->setWorkEndTime(DateTime::createFromFormat(self::DATETIME_FORMAT, '04.12.2017 10:00'));
        $this->assertEquals(0, $this->workshift->getNightShiftTime());

        $work->setWorkStartTime(DateTime::createFromFormat(self::DATETIME_FORMAT, '04.12.2017 09:00'));
        $work->setWorkEndTime(DateTime::createFromFormat(self::DATETIME_FORMAT, '05.12.2017 10:00'));
        $this->assertEquals(420, $this->workshift->getNightShiftTime());
    }

    public function testIsNightShiftWithOtherPeriod()
    {
        $work = $this->workshift->getWork();

        $nightshiftPeriod = new NightshiftPeriod('17:00', '05:00');
        $this->workshift->setNightshiftPeriod($nightshiftPeriod);

        $work->setWorkStartTime(DateTime::createFromFormat(self::DATETIME_FORMAT, '04.12.2017 09:00'));
        $work->setWorkEndTime(DateTime::createFromFormat(self::DATETIME_FORMAT, '04.12.2017 10:00'));
        $this->assertEquals(0, $this->workshift->getNightShiftTime());

        $work->setWorkStartTime(DateTime::createFromFormat(self::DATETIME_FORMAT, '04.12.2017 18:00'));
        $work->setWorkEndTime(DateTime::createFromFormat(self::DATETIME_FORMAT, '05.12.2017 03:00'));
        $this->assertEquals(540, $this->workshift->getNightShiftTime());
    }
}