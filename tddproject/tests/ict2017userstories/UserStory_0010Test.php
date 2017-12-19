<?php

require_once "duunissa/class/ict2017/ReportService.php";
require_once "duunissa/class/ict2017/Employee.php";
require_once "duunissa/class/ict2017/Work.php";
require_once "duunissa/class/ict2017/LunchBreak.php";
require_once "duunissa/class/ict2017/Workshift.php";

class UserStory_0010Test extends \Codeception\Test\Unit
{
    const DATETIME_FORMAT = "d.m.Y H:i";

    /** @var \ReportService */
    private $reportService;

    public function _before()
    {
        $this->reportService = new ReportService();
    }

    public function _after()
    {
    }

    /*
    * Scenario 1:
    * Given workshift (08:00-16:00)
    * and lunchbreak (12:00 - 12:30)
    * Then workshift hours are 7.5
    * and there is no overtime
    */
    public function testScenario1ReportGroupedByWorkshift()
    {
        $employee   = new Employee(1, 'John', 'Doe');
        $lunchBreak = new LunchBreak(DateTime::createFromFormat(self::DATETIME_FORMAT, '11.12.2017 12:00'),
            DateTime::createFromFormat(self::DATETIME_FORMAT, '11.12.2017 12:30'));

        $work      = new Work(1, DateTime::createFromFormat(self::DATETIME_FORMAT, '11.12.2017 08:00'),
            DateTime::createFromFormat(self::DATETIME_FORMAT, '11.12.2017 16:00'));
        $workshift = new Workshift($employee, $work);
        $workshift->setLunchBreak($lunchBreak);

        $work      = new Work(1, DateTime::createFromFormat(self::DATETIME_FORMAT, '11.12.2017 08:00'),
            DateTime::createFromFormat(self::DATETIME_FORMAT, '11.12.2017 17:30'));
        $workshift = new Workshift($employee, $work);
        $workshift->setLunchBreak($lunchBreak);

        $work      = new Work(1, DateTime::createFromFormat(self::DATETIME_FORMAT, '11.12.2017 08:00'),
            DateTime::createFromFormat(self::DATETIME_FORMAT, '11.12.2017 18:30'));
        $workshift = new Workshift($employee, $work);
        $workshift->setLunchBreak($lunchBreak);

        $work      = new Work(1, DateTime::createFromFormat(self::DATETIME_FORMAT, '11.12.2017 08:00'),
            DateTime::createFromFormat(self::DATETIME_FORMAT, '11.12.2017 19:30'));
        $workshift = new Workshift($employee, $work);
        $workshift->setLunchBreak($lunchBreak);

        $work      = new Work(1, DateTime::createFromFormat(self::DATETIME_FORMAT, '11.12.2017 08:00'),
            DateTime::createFromFormat(self::DATETIME_FORMAT, '11.12.2017 20:30'));
        $workshift = new Workshift($employee, $work);
        $workshift->setLunchBreak($lunchBreak);

        $reports = $this->reportService->getSummaryGroupByWorkshift($employee);

        $this->assertEquals(7.5 * 60, $reports[0]->getWorkshiftWithoutLunchTotalTime());
        $this->assertEquals(0, $reports[0]->getOvertimeTotalTime());
        $this->assertEquals(0, $reports[0]->getOvertime50Time());
        $this->assertEquals(0, $reports[0]->getOvertime100Time());

        $this->assertEquals(9 * 60, $reports[1]->getWorkshiftWithoutLunchTotalTime());
        $this->assertEquals(1 * 60, $reports[1]->getOvertimeTotalTime());
        $this->assertEquals(1 * 60, $reports[1]->getOvertime50Time());
        $this->assertEquals(0, $reports[1]->getOvertime100Time());

        $this->assertEquals(10 * 60, $reports[2]->getWorkshiftWithoutLunchTotalTime());
        $this->assertEquals(2 * 60, $reports[2]->getOvertimeTotalTime());
        $this->assertEquals(2 * 60, $reports[2]->getOvertime50Time());
        $this->assertEquals(0, $reports[2]->getOvertime100Time());
        $this->assertEquals(0.5 * 60, $reports[2]->getEveningTotalTime());

        $this->assertEquals(11 * 60, $reports[3]->getWorkshiftWithoutLunchTotalTime());
        $this->assertEquals(3 * 60, $reports[3]->getOvertimeTotalTime());
        $this->assertEquals(2 * 60, $reports[3]->getOvertime50Time());
        $this->assertEquals(1 * 60, $reports[3]->getOvertime100Time());
        $this->assertEquals(1.5 * 60, $reports[3]->getEveningTotalTime());

        $this->assertEquals(12 * 60, $reports[4]->getWorkshiftWithoutLunchTotalTime());
        $this->assertEquals(4 * 60, $reports[4]->getOvertimeTotalTime());
        $this->assertEquals(2 * 60, $reports[4]->getOvertime50Time());
        $this->assertEquals(2 * 60, $reports[4]->getOvertime100Time());
        $this->assertEquals(2.5 * 60, $reports[4]->getEveningTotalTime());
    }

    public function testScenario2ReportGroupedByWorkshift()
    {
        $employee   = new Employee(1, 'John', 'Doe');
        $lunchBreak = new LunchBreak(DateTime::createFromFormat(self::DATETIME_FORMAT, '11.12.2017 14:00'),
            DateTime::createFromFormat(self::DATETIME_FORMAT, '11.12.2017 14:30'));

        $work      = new Work(1, DateTime::createFromFormat(self::DATETIME_FORMAT, '11.12.2017 14:00'),
            DateTime::createFromFormat(self::DATETIME_FORMAT, '11.12.2017 22:00'));
        $workshift = new Workshift($employee, $work);
        $workshift->setLunchBreak($lunchBreak);

        $work      = new Work(1, DateTime::createFromFormat(self::DATETIME_FORMAT, '11.12.2017 14:00'),
            DateTime::createFromFormat(self::DATETIME_FORMAT, '11.12.2017 23:30'));
        $workshift = new Workshift($employee, $work);
        $workshift->setLunchBreak($lunchBreak);

        $work      = new Work(1, DateTime::createFromFormat(self::DATETIME_FORMAT, '11.12.2017 14:00'),
            DateTime::createFromFormat(self::DATETIME_FORMAT, '12.12.2017 00:30'));
        $workshift = new Workshift($employee, $work);
        $workshift->setLunchBreak($lunchBreak);

        $work      = new Work(1, DateTime::createFromFormat(self::DATETIME_FORMAT, '11.12.2017 14:00'),
            DateTime::createFromFormat(self::DATETIME_FORMAT, '12.12.2017 01:30'));
        $workshift = new Workshift($employee, $work);
        $workshift->setLunchBreak($lunchBreak);

        $work      = new Work(1, DateTime::createFromFormat(self::DATETIME_FORMAT, '11.12.2017 14:00'),
            DateTime::createFromFormat(self::DATETIME_FORMAT, '12.12.2017 02:30'));
        $workshift = new Workshift($employee, $work);
        $workshift->setLunchBreak($lunchBreak);

        $reports = $this->reportService->getSummaryGroupByWorkshift($employee);

        $this->assertEquals(7.5 * 60, $reports[0]->getWorkshiftWithoutLunchTotalTime());
        $this->assertEquals(0, $reports[0]->getOvertimeTotalTime());
        $this->assertEquals(0, $reports[0]->getOvertime50Time());
        $this->assertEquals(0, $reports[0]->getOvertime100Time());
        $this->assertEquals(4 * 60, $reports[0]->getEveningTotalTime());

        $this->assertEquals(9 * 60, $reports[1]->getWorkshiftWithoutLunchTotalTime());
        $this->assertEquals(1 * 60, $reports[1]->getOvertimeTotalTime());
        $this->assertEquals(1 * 60, $reports[1]->getOvertime50Time());
        $this->assertEquals(0, $reports[1]->getOvertime100Time());
        $this->assertEquals(5 * 60, $reports[1]->getEveningTotalTime());
        $this->assertEquals(0.5 * 60, $reports[1]->getNightTotalTime());

        $this->assertEquals(10 * 60, $reports[2]->getWorkshiftWithoutLunchTotalTime());
        $this->assertEquals(2 * 60, $reports[2]->getOvertimeTotalTime());
        $this->assertEquals(2 * 60, $reports[2]->getOvertime50Time());
        $this->assertEquals(0, $reports[2]->getOvertime100Time());
        $this->assertEquals(5 * 60, $reports[2]->getEveningTotalTime());
        $this->assertEquals(1.5 * 60, $reports[2]->getNightTotalTime());

        $this->assertEquals(11 * 60, $reports[3]->getWorkshiftWithoutLunchTotalTime());
        $this->assertEquals(3 * 60, $reports[3]->getOvertimeTotalTime());
        $this->assertEquals(2 * 60, $reports[3]->getOvertime50Time());
        $this->assertEquals(1 * 60, $reports[3]->getOvertime100Time());
        $this->assertEquals(5 * 60, $reports[3]->getEveningTotalTime());
        $this->assertEquals(2.5 * 60, $reports[3]->getNightTotalTime());

        $this->assertEquals(12 * 60, $reports[4]->getWorkshiftWithoutLunchTotalTime());
        $this->assertEquals(4 * 60, $reports[4]->getOvertimeTotalTime());
        $this->assertEquals(2 * 60, $reports[4]->getOvertime50Time());
        $this->assertEquals(2 * 60, $reports[4]->getOvertime100Time());
        $this->assertEquals(5 * 60, $reports[4]->getEveningTotalTime());
        $this->assertEquals(3.5 * 60, $reports[4]->getNightTotalTime());
    }

    public function testScenario3ReportGroupedByWorkshift()
    {
        $employee   = new Employee(1, 'John', 'Doe');
        $lunchBreak = new LunchBreak(DateTime::createFromFormat(self::DATETIME_FORMAT, '11.12.2017 18:30'),
            DateTime::createFromFormat(self::DATETIME_FORMAT, '11.12.2017 19:00'));

        $work      = new Work(1, DateTime::createFromFormat(self::DATETIME_FORMAT, '11.12.2017 18:00'),
            DateTime::createFromFormat(self::DATETIME_FORMAT, '12.12.2017 02:00'));
        $workshift = new Workshift($employee, $work);
        $workshift->setLunchBreak($lunchBreak);

        $work      = new Work(1, DateTime::createFromFormat(self::DATETIME_FORMAT, '11.12.2017 18:00'),
            DateTime::createFromFormat(self::DATETIME_FORMAT, '12.12.2017 03:30'));
        $workshift = new Workshift($employee, $work);
        $workshift->setLunchBreak($lunchBreak);

        $work      = new Work(1, DateTime::createFromFormat(self::DATETIME_FORMAT, '11.12.2017 18:00'),
            DateTime::createFromFormat(self::DATETIME_FORMAT, '12.12.2017 04:30'));
        $workshift = new Workshift($employee, $work);
        $workshift->setLunchBreak($lunchBreak);

        $work      = new Work(1, DateTime::createFromFormat(self::DATETIME_FORMAT, '11.12.2017 18:00'),
            DateTime::createFromFormat(self::DATETIME_FORMAT, '12.12.2017 05:30'));
        $workshift = new Workshift($employee, $work);
        $workshift->setLunchBreak($lunchBreak);

        $work      = new Work(1, DateTime::createFromFormat(self::DATETIME_FORMAT, '11.12.2017 18:00'),
            DateTime::createFromFormat(self::DATETIME_FORMAT, '12.12.2017 06:30'));
        $workshift = new Workshift($employee, $work);
        $workshift->setLunchBreak($lunchBreak);

        $reports = $this->reportService->getSummaryGroupByWorkshift($employee);

        $this->assertEquals(7.5 * 60, $reports[0]->getWorkshiftWithoutLunchTotalTime());
        $this->assertEquals(0, $reports[0]->getOvertimeTotalTime());
        $this->assertEquals(0, $reports[0]->getOvertime50Time());
        $this->assertEquals(0, $reports[0]->getOvertime100Time());
        $this->assertEquals(5 * 60, $reports[0]->getEveningTotalTime());
        $this->assertEquals(3 * 60, $reports[0]->getNightTotalTime());

        $this->assertEquals(9 * 60, $reports[1]->getWorkshiftWithoutLunchTotalTime());
        $this->assertEquals(1 * 60, $reports[1]->getOvertimeTotalTime());
        $this->assertEquals(1 * 60, $reports[1]->getOvertime50Time());
        $this->assertEquals(0, $reports[1]->getOvertime100Time());
        $this->assertEquals(5 * 60, $reports[1]->getEveningTotalTime());
        $this->assertEquals(4.5 * 60, $reports[1]->getNightTotalTime());

        $this->assertEquals(10 * 60, $reports[2]->getWorkshiftWithoutLunchTotalTime());
        $this->assertEquals(2 * 60, $reports[2]->getOvertimeTotalTime());
        $this->assertEquals(2 * 60, $reports[2]->getOvertime50Time());
        $this->assertEquals(0, $reports[2]->getOvertime100Time());
        $this->assertEquals(5 * 60, $reports[2]->getEveningTotalTime());
        $this->assertEquals(5.5 * 60, $reports[2]->getNightTotalTime());

        $this->assertEquals(11 * 60, $reports[3]->getWorkshiftWithoutLunchTotalTime());
        $this->assertEquals(3 * 60, $reports[3]->getOvertimeTotalTime());
        $this->assertEquals(2 * 60, $reports[3]->getOvertime50Time());
        $this->assertEquals(1 * 60, $reports[3]->getOvertime100Time());
        $this->assertEquals(5 * 60, $reports[3]->getEveningTotalTime());
        $this->assertEquals(6.5 * 60, $reports[3]->getNightTotalTime());

        $this->assertEquals(12 * 60, $reports[4]->getWorkshiftWithoutLunchTotalTime());
        $this->assertEquals(4 * 60, $reports[4]->getOvertimeTotalTime());
        $this->assertEquals(2 * 60, $reports[4]->getOvertime50Time());
        $this->assertEquals(2 * 60, $reports[4]->getOvertime100Time());
        $this->assertEquals(5 * 60, $reports[4]->getEveningTotalTime());
        $this->assertEquals(7 * 60, $reports[4]->getNightTotalTime());
    }

    /*
    * Scenario 4:
    * Given valid employee
    * And valid workshift (13:00-00:00)
    * And lunchbreak (17:00 - 17:30)
    * And shiftbreak (19:00 - 19:30)
    * And travel (21:00 - 21:22)
    * And two valid products
    * And two valid allowances
    * When i want summary of totals by workshift
    * Then workshift hours are 10h 8min
    * And workshift without lunch is 9h 38min
    * And travel time total is 22min
    * And shiftbreak time total is 30min
    * And prices of products by row are correct
    * And products total cost is 20€ (24,80€ with taxes)
    * And prices of allowances by row are correct
    * And allowances total cost is 9.10€
    * And total overtime work is 2h 30min (lunch not calculated), 50% is 2h 0min, 100% is 30min
    * And evening work (18:00 - 23:00) is 5h 0min
    * And night work (23:00 - 06:00) is 1h 0min
    */
    public function testScenario4ReportGroupedByWorkshift()
    {
        $employee  = new Employee(1, 'John', 'Doe');
        $work      = new Work(1, DateTime::createFromFormat(self::DATETIME_FORMAT, '12.12.2017 13:00'),
            DateTime::createFromFormat(self::DATETIME_FORMAT, '13.12.2017 00:00'));
        $workshift = new Workshift($employee, $work);

        $lunchBreak = new Lunchbreak(DateTime::createFromFormat(self::DATETIME_FORMAT, '12.12.2017 17:00'),
            DateTime::createFromFormat(self::DATETIME_FORMAT, '12.12.2017 17:30'));
        $shiftBreak = new Shiftbreak(1, DateTime::createFromFormat(self::DATETIME_FORMAT, '12.12.2017 19:00'),
            DateTime::createFromFormat(self::DATETIME_FORMAT, '12.12.2017 19:30'));
        $travel     = new Travel(1, DateTime::createFromFormat(self::DATETIME_FORMAT, '12.12.2017 21:00'),
            DateTime::createFromFormat(self::DATETIME_FORMAT, '12.12.2017 21:22'));
        $product1   = new Product(1, 'Sausage', 'default', 'pcs', 1, 15, 0.24,
            new DateTime('12.12.2017 14:00'));
        $product2   = new Product(2, 'Soap', 'default', 'pcs', 2, 2.5, 0.24,
            new DateTime('12.12.2017 18:00'));
        $allowance1 = new Allowance(1, 'travelallowance', 'travelallowance', 'KM', 0.20, 23,
            new DateTime('12.12.2017 21:22'), 'My travel to work');
        $allowance2 = new Allowance(2, 'mealallowance', 'mealallowance', 'MIN', 0.15, 30,
            new DateTime('12.12.2017 17:30'), 'Lunch');

        // Set values to workshift
        $workshift->setLunchBreak($lunchBreak);
        $workshift->setShiftBreak($shiftBreak);
        $workshift->setTravel($travel);
        $workshift->addProduct($product1);
        $workshift->addProduct($product2);
        $workshift->setAllowance($allowance1);
        $workshift->setAllowance($allowance2);

        $reports = $this->reportService->getSummaryGroupByWorkshift($employee);

        // Total workshift time
        $this->assertEquals(608, $reports[0]->getWorkshiftTotalTime());

        // Total workshift time without lunch
        $this->assertEquals(578, $reports[0]->getWorkshiftWithoutLunchTotalTime());

        // Total shiftbreak time
        $this->assertEquals(0.5 * 60, $reports[0]->getShiftBreaksTotalTime());

        // Total travel time
        $this->assertEquals(22, $reports[0]->getTravelTotalTime());

        // Prices of products by row with taxes
        $products = $reports[0]->getProductsRows();

        $this->assertEquals(15 * 1.24, $products[1]);
        $this->assertEquals(5 * 1.24, $products[2]);

        // Total price of products with taxes
        $this->assertEquals(20 * 1.24, $reports[0]->getProductsTotal());

        // Prices of allowances by row
        $allowances = $reports[0]->getAllowancesRows();

        $this->assertEquals(4.60, $allowances[1]);
        $this->assertEquals(4.50, $allowances[2]);

        // Total price of allowances
        $this->assertEquals(9.10, $reports[0]->getAllowancesTotal());

        // Overtime total, 50% and 100%
        $this->assertEquals(2.5 * 60, $reports[0]->getOvertimeTotalTime());
        $this->assertEquals(2 * 60, $reports[0]->getOvertime50Time());
        $this->assertEquals(0.5 * 60, $reports[0]->getOvertime100Time());

        // Evening work
        $this->assertEquals(5 * 60, $reports[0]->getEveningTotalTime());

        // Night work
        $this->assertEquals(1 * 60, $reports[0]->getNightTotalTime());
    }

    /*
    * Scenario 5:
    * Given valid employee
    * And two valid workshifts starting in same day (6:00-12:00 & 23:00-10:00)
    * And lunchbreaks in both workshifts (09:00-09:30 & 03:00-03:30)
    * And shiftbreaks in both workshifts (10:30-11:00 & 06:00-06:30)
    * And travels in both workshifts (07:00-07:15 & 08:00-08:15)
    * And three valid products (2 in first, 1 in second workshift)
    * And three valid allowances (2 in first, 1 in second workshift)
    * When i want summary of totals by workday
    * Then workshift starting date is 12.12.2017
    * And workshift hours are 15h 30min
    * And workshift without lunch is 14h 30min
    * And travel time total is 30min
    * And shiftbreak time total is 1h 0min
    * And prices of products by row are correct
    * And products total cost is 35€ (43,40€ with taxes)
    * And prices of allowances by row are correct
    * And allowances total cost is 13.70€
    * CURRENTLY GIVES SUM OF OVERTIMES IN SEPARATE WORKSHIFT, DAILY OVERTIME IS NOT CALCULATED
    * And total overtime work is 8h 0min (lunch not calculated), 50% is 2h 0min, 100% is 6h 0min
    * And evening work (18:00 - 23:00) is 0h 0min
    * And night work (23:00 - 06:00) is 7h 0min
    */
    public function testScenario5ReportGroupedByWorkday()
    {
        $employee = new Employee(1, 'John', 'Doe');

        $work      = new Work(1, DateTime::createFromFormat(self::DATETIME_FORMAT, '12.12.2017 06:00'),
            DateTime::createFromFormat(self::DATETIME_FORMAT, '12.12.2017 12:00'));
        $workshift = new Workshift($employee, $work);

        $work2      = new Work(2, DateTime::createFromFormat(self::DATETIME_FORMAT, '12.12.2017 23:00'),
            DateTime::createFromFormat(self::DATETIME_FORMAT, '13.12.2017 10:00'));
        $workshift2 = new Workshift($employee, $work2);

        $lunchBreak = new Lunchbreak(DateTime::createFromFormat(self::DATETIME_FORMAT, '12.12.2017 09:00'),
            DateTime::createFromFormat(self::DATETIME_FORMAT, '12.12.2017 09:30'));
        $shiftBreak = new Shiftbreak(1, DateTime::createFromFormat(self::DATETIME_FORMAT, '12.12.2017 10:30'),
            DateTime::createFromFormat(self::DATETIME_FORMAT, '12.12.2017 11:00'));
        $travel     = new Travel(1, DateTime::createFromFormat(self::DATETIME_FORMAT, '12.12.2017 07:00'),
            DateTime::createFromFormat(self::DATETIME_FORMAT, '12.12.2017 07:15'));
        $product1   = new Product(1, 'Sausage', 'default', 'pcs', 1, 15, 0.24,
            new DateTime('12.12.2017 14:00'));
        $product2   = new Product(2, 'Soap', 'default', 'pcs', 2, 2.5, 0.24,
            new DateTime('12.12.2017 18:00'));
        $allowance1 = new Allowance(1, 'travelallowance', 'travelallowance', 'KM', 0.20, 23,
            new DateTime('12.12.2017 21:22'), 'My travel to work');
        $allowance2 = new Allowance(2, 'mealallowance', 'mealallowance', 'MIN', 0.15, 30,
            new DateTime('12.12.2017 17:30'), 'Lunch');

        $lunchBreak2 = new Lunchbreak(DateTime::createFromFormat(self::DATETIME_FORMAT, '13.12.2017 03:00'),
            DateTime::createFromFormat(self::DATETIME_FORMAT, '13.12.2017 03:30'));
        $shiftBreak2 = new Shiftbreak(1, DateTime::createFromFormat(self::DATETIME_FORMAT, '13.12.2017 06:00'),
            DateTime::createFromFormat(self::DATETIME_FORMAT, '13.12.2017 06:30'));
        $travel2     = new Travel(1, DateTime::createFromFormat(self::DATETIME_FORMAT, '13.12.2017 08:00'),
            DateTime::createFromFormat(self::DATETIME_FORMAT, '13.12.2017 08:15'));
        $product3    = new Product(1, 'Sausage', 'default', 'pcs', 1, 15, 0.24,
            new DateTime('12.12.2017 14:00'));
        $allowance3  = new Allowance(1, 'travelallowance', 'travelallowance', 'KM', 0.20, 23,
            new DateTime('12.12.2017 21:22'), 'My travel to work');

        // Set values to workshifts
        $workshift->setLunchBreak($lunchBreak);
        $workshift->setShiftBreak($shiftBreak);
        $workshift->setTravel($travel);
        $workshift->addProduct($product1);
        $workshift->addProduct($product2);
        $workshift->setAllowance($allowance1);
        $workshift->setAllowance($allowance2);

        $workshift2->setLunchBreak($lunchBreak2);
        $workshift2->setShiftBreak($shiftBreak2);
        $workshift2->setTravel($travel2);
        $workshift2->addProduct($product3);
        $workshift2->setAllowance($allowance3);

        $reports = $this->reportService->getSummaryGroupByWorkday($employee);

        // Workshift date
        $this->assertEquals("12.12.2017", $reports[0]->getShiftStartDate());

        // Total workshift time
        $this->assertEquals(15.5 * 60, $reports[0]->getWorkshiftTotalTime());

        // Total workshift time without lunch
        $this->assertEquals(14.5 * 60, $reports[0]->getWorkshiftWithoutLunchTotalTime());

        // Total shiftbreak time
        $this->assertEquals(1 * 60, $reports[0]->getShiftBreaksTotalTime());

        // Total travel time
        $this->assertEquals(0.5 * 60, $reports[0]->getTravelTotalTime());

        // Prices of products by row with taxes
        $products = $reports[0]->getProductsRows();

        $this->assertEquals(37.2, $products[1]);
        $this->assertEquals(6.20, $products[2]);

        // Total price of products with taxes
        $this->assertEquals(35 * 1.24, $reports[0]->getProductsTotal());

        // Prices of allowances by row
        $allowances = $reports[0]->getAllowancesRows();

        $this->assertEquals(9.20, $allowances[1]);
        $this->assertEquals(4.50, $allowances[2]);

        // Total price of allowances
        $this->assertEquals(13.70, $reports[0]->getAllowancesTotal());

        // Overtime total, 50% and 100%
        // CURRENTLY GIVES SUM OF OVERTIMES AS IT IS IN THE SAME DAY
        $this->assertEquals(7.5 * 60, $reports[0]->getOvertimeTotalTime());
        $this->assertEquals(2 * 60, $reports[0]->getOvertime50Time());
        $this->assertEquals(5.5 * 60, $reports[0]->getOvertime100Time());

        // Evening work
        $this->assertEquals(0, $reports[0]->getEveningTotalTime());

        // Night work
        $this->assertEquals(7 * 60, $reports[0]->getNightTotalTime());
    }

    public function testScenario6ReportGroupedByWeek()
    {
        $employee = new Employee(1, 'John', 'Doe');

        $work = new Work(1, DateTime::createFromFormat(self::DATETIME_FORMAT, '11.12.2017 08:00'),
            DateTime::createFromFormat(self::DATETIME_FORMAT, '11.12.2017 20:00'));
        new Workshift($employee, $work);

        $work = new Work(1, DateTime::createFromFormat(self::DATETIME_FORMAT, '12.12.2017 08:00'),
            DateTime::createFromFormat(self::DATETIME_FORMAT, '12.12.2017 20:00'));
        new Workshift($employee, $work);

        $work = new Work(1, DateTime::createFromFormat(self::DATETIME_FORMAT, '13.12.2017 08:00'),
            DateTime::createFromFormat(self::DATETIME_FORMAT, '13.12.2017 20:00'));
        new Workshift($employee, $work);

        $work = new Work(1, DateTime::createFromFormat(self::DATETIME_FORMAT, '14.12.2017 08:00'),
            DateTime::createFromFormat(self::DATETIME_FORMAT, '14.12.2017 20:00'));
        new Workshift($employee, $work);

        $work = new Work(1, DateTime::createFromFormat(self::DATETIME_FORMAT, '15.12.2017 08:00'),
            DateTime::createFromFormat(self::DATETIME_FORMAT, '15.12.2017 20:00'));
        new Workshift($employee, $work);

        $work = new Work(1, DateTime::createFromFormat(self::DATETIME_FORMAT, '16.12.2017 08:00'),
            DateTime::createFromFormat(self::DATETIME_FORMAT, '16.12.2017 20:00'));
        new Workshift($employee, $work);

        $work = new Work(1, DateTime::createFromFormat(self::DATETIME_FORMAT, '17.12.2017 08:00'),
            DateTime::createFromFormat(self::DATETIME_FORMAT, '17.12.2017 20:00'));
        new Workshift($employee, $work);
        
        /** @var \Report $report */
        $reports = $this->reportService->getSummaryGroupByWeek($employee);
        $report  = array_values($reports)[0];

        $this->assertEquals(84 * 60, $report->getWorkshiftTotalTime());
        $this->assertEquals(16 * 60, $report->getWeeklyOvertime());
    }
}