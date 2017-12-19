<?php

require_once "duunissa/class/ict2017/Report.php";
require_once "duunissa/class/ict2017/ReportService.php";
require_once "duunissa/class/ict2017/Employee.php";
require_once "duunissa/class/ict2017/Work.php";
require_once "duunissa/class/ict2017/LunchBreak.php";
require_once "duunissa/class/ict2017/Workshift.php";
require_once "duunissa/class/ict2017/Shiftbreak.php";
require_once "duunissa/class/ict2017/Travel.php";
require_once "duunissa/class/ict2017/Location.php";

class ReportTest extends \Codeception\Test\Unit
{
    /**
     * @var \Ict2017Tester
     */
    const DATETIME_FORMAT = "d.m.Y H:i";

    protected $tester;
    private $workshift, $report, $reportService;

    protected function _before()
    {
        $this->report = new Report();  
        $this->reportService = new ReportService();     

        $employee = new Employee(123, "Jyrki", "Kolehmainen");
        $work     = new Work(123, new DateTime('17.11.2017 09:00'),
            new DateTime('17.11.2017 19:00'), new Location(60.99596, 24.46434),
            new Location(60.89596, 24.26434));

        $this->workshift = new Workshift($employee, $work);
        $this->ID        = 123;

        // Add lunchbreak
        $lunchBreak = new LunchBreak(DateTime::createFromFormat(self::DATETIME_FORMAT, '11.12.2017 12:00'),
        DateTime::createFromFormat(self::DATETIME_FORMAT, '11.12.2017 12:30'));
        $this->workshift->setLunchBreak($lunchBreak);

        // Add two products: two planks each costs 9.99 tax 0.24 and one soap costs 1.50 tax 0.24
        $product1 = new Product(2, 'Plank', 'default', 'pcs', 9.99, 2, 0.24,
            DateTime::createFromFormat('d.m.Y H:i', '17.11.2017 10:00'));
        $product2 = new Product(5, 'Soap', 'default', 'kg', 1.50, 3, 0.24,
            DateTime::createFromFormat('d.m.Y H:i', '17.11.2017 13:30'));
        $this->workshift->addProduct($product1);
        $this->workshift->addProduct($product2);

        // Set travel and meal allowances
        $allowanceTravel = new Allowance(1, 'kilometrikorvaus', 'kilometrikorvaus', 'KM', 0.42, 60,
        new DateTime('17.11.2017 09:00'), 'Työmatkani');
        $allowanceMeal   = new Allowance(2, 'Ateria korvaus', 'Ateria korvaus', 'KPL', 1.90, 1,
        new DateTime('17.11.2017 12:00'), 'Lounas');
        $this->workshift->setAllowance($allowanceTravel);
        $this->workshift->setAllowance($allowanceMeal);
    }

    protected function _after()
    {
    }

    // Get Shiftbreak total time
    public function testGetShiftbreaksTotalTime()
    {
        $startTime = new DateTime('17.11.2017 10:00');
        $endTime   = new DateTime('17.11.2017 10:15');

        $shiftbreak = new Shiftbreak($this->ID, $startTime, $endTime);
        $this->workshift->setShiftBreak($shiftbreak);

        $this->report->setShiftBreaksTotalTime($this->workshift->getShiftbreakTime(true));
        $this->assertEquals(15, $this->report->getShiftBreaksTotalTime());
    }

    // Get travel total time
    public function testGetTravelTotalTime(){
        $travel = new Travel($this->ID, new DateTime('17.11.2017 9:15'),
        new DateTime('17.11.2017 9:53'));
        $this->workshift->setTravel($travel);

        $this->report->setTravelTotalTime($this->workshift->getTravelTime(true));
        $this->assertEquals(38, $this->report->getTravelTotalTime());
    }

    // Get workshift total time
    public function testGetWorkshiftTotalTime(){
        $this->report->setWorkshiftTotalTime($this->workshift->getWorkTime(true));
        $this->assertEquals(10 * 60, $this->report->getWorkshiftTotalTime());
    }

    // Get workshift without lunch total time
    public function testGetWorkshiftWithoutLunchTotalTime(){
        $this->report->setWorkshiftTotalTime($this->workshift->getWorkTimeMinusLunch(true));
        $this->assertEquals(9.5 * 60, $this->report->getWorkshiftTotalTime());
    }

    // Get overtime total time
    public function testGetOvertimeTotalTime(){
        $this->report->setOvertimeTotalTime($this->workshift->getOvertime());
        $this->assertEquals(2 * 60, $this->report->getOvertimeTotalTime());
    }

    // Get evening total time
    public function testGetEveningTotalTime(){
        $this->report->setEveningTotalTime($this->workshift->getEveningShiftTime());
        $this->assertEquals(60, $this->report->getEveningTotalTime());
    }

    // Get night total time
    public function testGetNightTotalTime(){
        $this->report->setNightTotalTime($this->workshift->getNightShiftTime());
        $this->assertEquals(0, $this->report->getNightTotalTime());
    }

    // Get products total
    public function testGetProductsTotal(){
        $this->report->setProductsTotal($this->workshift->getTotalPriceOfAllProductsWithTaxes());
        $this->assertEquals(30.36, $this->report->getProductsTotal());
    }

    // Get products rows
    public function testGetProductsRows(){
        $this->report->setProductsRows($this->workshift->getTotalPriceByProductRowsWithTaxes());
        
        $products = array();
        $products = $this->report->getProductsRows();
        
        $this->assertEquals(24.78, $products[2]);
        $this->assertEquals(5.58, $products[5]);
    }

    // Get allowances total
    public function testGetAllowancesTotal(){
        $this->report->setAllowancesTotal($this->workshift->getTotalCostOfAllAllowances());
        $this->assertEquals(27.10, $this->report->getAllowancesTotal());
    }

    // Get allowances rows
    public function testGetAllowancesRows(){
        $this->report->setAllowancesRows($this->workshift->getTotalCostByAllowanceRows());
        
        $allos = array();
        $allos = $this->report->getAllowancesRows();
        
        $this->assertEquals(25.20, $allos[1]);
        $this->assertEquals(1.90, $allos[2]);
    }
}