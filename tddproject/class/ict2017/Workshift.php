<?php

require_once "Shiftbreak.php";
require_once "Travel.php";
require_once "Log.php";
require_once "Work.php";
require_once "DateTimeCalculator.php";
require_once "Employee.php";
require_once "Product.php";
require_once "LunchBreak.php";

class Workshift
{
    private $employee;
    private $work;
    private $shiftBreaks;
    private $travels;
    private $logs;
    private $products;
    private $allowance;
    private $lunchbreak;
    private $project;
    private $shiftLimit;
    private $eveningshiftPeriod;
    private $nightshiftPeriod;

    private $dateTimeCalculator;

    public function __construct(Employee $employee, Work $work)
    {
        if ($employee->getId() === null) {
            throw new Exception('To start a workshift, a valid employee is required.');
        }

        $this->employee           = $employee;
        $this->work               = $work;
        $this->dateTimeCalculator = new DateTimeCalculator();
        $this->products           = array();
        $this->allowance          = array();

        $this->shiftLimit         = new ShiftLimit();
        $this->eveningshiftPeriod = new EveningshiftPeriod();
        $this->nightshiftPeriod   = new NightshiftPeriod();

        $this->employee->addWorkshift($this);
    }

    public function getEmployee()
    {
        return $this->employee;
    }

    public function getWork()
    {
        return $this->work;
    }

    public function setWork(Work $work)
    {
        $this->work = $work;
    }

    public function getShiftBreak()
    {
        return $this->shiftBreaks;
    }

    public function setShiftBreak(Shiftbreak $shiftBreak)
    {
        $this->shiftBreaks[] = $shiftBreak;
    }

    public function getTravel()
    {
        return $this->travels;
    }

    public function setTravel(Travel $travel)
    {
        if (!$this->getWork() || !$this->getWork()->getWorkStartTime()) {
            throw new Exception("Workshift not started!");
        }
        $this->travels[] = $travel;
    }

    public function addLog(Log $log)
    {
        if (!$this->getWork() || !$this->getWork()->getWorkStartTime()) {
            throw new Exception("Workshift not started!");
        }

        $this->logs[] = $log;
    }

    public function getLogs()
    {
        return $this->logs;
    }

    public function addProduct(Product $product)
    {
        if (!$this->getWork() || !$this->getWork()->getWorkStartTime()) {
            throw new Exception("Workshift not started!");
        }

        $this->products[] = $product;
    }

    public function getProducts()
    {
        return $this->products;
    }

    public function getAllowance()
    {
        return $this->allowance;
    }

    public function setAllowance(Allowance $allowance)
    {
        /*
            Comparison is done by Allowance ID, change to something else if needed
        */

        for ($i = 0; $i < sizeof($this->allowance); $i++) {
            if ($allowance->getID() === $this->allowance[$i]->getID()) {
                unset($this->allowance[$i]);                            // if entry of same type is found. Replace old entry with new entry.
                $this->allowance = array_values($this->allowance);      // Reorder Index values, so no empty indexes are left.
            }
        }

        $this->allowance[] = $allowance;
    }

    public function getLunchBreak()
    {
        return $this->lunchbreak;
    }

    public function setLunchBreak(LunchBreak $lunchbreak)
    {
        $this->lunchbreak = $lunchbreak;
    }

    public function getProject()
    {
        return $this->project;
    }

    public function setProject(Project $project)
    {
        $this->project = $project;
    }

    // Worktime - shiftbreaks - traveltime
    public function getWorkTime($inMinutes = false)
    {
        $diffWorktime = $this->dateTimeCalculator->getTimeInMinutes($this->getWork()->getWorkStartTime(),
            $this->getWork()->getWorkEndTime());

        $diffWorktime -= $this->getShiftbreakTime(true);
        $diffWorktime -= $this->getTravelTime(true);

        if ($inMinutes) {
            return $diffWorktime;
        }

        return $this->dateTimeCalculator->getTimeInHoursAndMinutesFromMinutes($diffWorktime);
    }

    // Worktime - shiftbreaks - traveltime - lunchbreaktime
    public function getWorkTimeMinusLunch($inMinutes = false)
    {
        $diffWorktime = $this->dateTimeCalculator->getTimeInMinutes($this->getWork()->getWorkStartTime(),
            $this->getWork()->getWorkEndTime());

        $diffWorktime -= $this->getShiftbreakTime(true);
        $diffWorktime -= $this->getTravelTime(true);
        $diffWorktime -= $this->getLunchBreakTime(true);

        if ($inMinutes) {
            return $diffWorktime;
        }

        return $this->dateTimeCalculator->getTimeInHoursAndMinutesFromMinutes($diffWorktime);
    }

    public function getShiftbreakTime($inMinutes = false)
    {
        $shiftTime = 0;

        if ($this->getShiftBreak()) {
            foreach ($this->shiftBreaks as $break) {
                $shiftTime += $this->dateTimeCalculator->getTimeInMinutes($break->getBreakStartTime(),
                    $break->getBreakEndTime());
            }
        }

        if ($inMinutes) {
            return $shiftTime;
        }

        return $this->dateTimeCalculator->getTimeInHoursAndMinutesFromMinutes($shiftTime);
    }

    public function getTravelTime($inMinutes = false)
    {
        $travelTime = 0;

        if ($this->getTravel()) {
            foreach ($this->travels as $travel) {
                $travelTime += $this->dateTimeCalculator->getTimeInMinutes($travel->getTravelStartTime(),
                    $travel->getTravelEndTime());
            }
        }

        if ($inMinutes) {
            return $travelTime;
        }

        return $this->dateTimeCalculator->getTimeInHoursAndMinutesFromMinutes($travelTime);
    }

    public function getTotalCostOfAllAllowances()
    {
        $totalcost = 0; // Set totalcost to 0, if the array is empty it will return zero
        $allos     = $this->getAllowance(); // gets our allowance array

        for ($i = 0; $i < sizeof($allos); $i++) {
            $totalcost = $totalcost + ($allos[$i]->getUnitCost() * $allos[$i]->getAmount());
        }

        return $totalcost;
    }

    public function getTotalCostByAllowanceRows()
    {
        $costByAllowanceRows = array();
        $allowances          = $this->getAllowance();

        foreach ($allowances as $allowance) {
            $costByAllowanceRows[$allowance->getId()] = number_format($allowance->getUnitCost() * $allowance->getAmount(), 2);
        }

        return $costByAllowanceRows;
    }

    public function getTotalPriceOfAllProductsWithoutTaxes()
    {
        $totalcost = 0; // Set totalcost to 0, if the array is empty it will return zero
        $products  = $this->getProducts(); // gets our products array

        foreach ($products as $product) {
            $totalcost += $product->getProductPrice() * $product->getProductAmount();
        }

        return $totalcost;
    }

    public function getTotalPriceOfAllProductsWithTaxes()
    {
        $totalcost = 0; // Set totalcost to 0, if the array is empty it will return zero
        $products  = $this->getProducts(); // gets our products array

        foreach ($products as $product) {
            $totalcost += ($product->getProductPrice() + $product->getProductPrice() * $product->getProductTax()) * $product->getProductAmount();
        }

        return number_format($totalcost, 2);
    }

    public function getTotalPriceByProductRowsWithoutTaxes()
    {
        $priceByRows = array();
        $products    = $this->getProducts(); // gets our products array

        foreach ($products as $product) {
            $priceByRows[] = $product->getProductPrice() * $product->getProductAmount();
        }

        return $priceByRows;
    }

    public function getTotalPriceByProductRowsWithTaxes()
    {
        $priceByProductRows = array();
        $products           = $this->getProducts(); // gets our products array

        foreach ($products as $product) {
            $priceByProductRows[$product->getProductId()] = number_format(($product->getProductPrice() + $product->getProductPrice() * $product->getProductTax()) * $product->getProductAmount(),
                2);
        }

        return $priceByProductRows;
    }


    public function getLunchBreakTime($inMinutes = false)
    {
        $lunchbreakTime = 0;

        if ($this->lunchbreak) {
            $lunchbreakTime = $this->dateTimeCalculator->getTimeInMinutes($this->lunchbreak->getBreakStartTime(),
                $this->lunchbreak->getBreakEndTime());
        }

        if ($inMinutes) {
            return $lunchbreakTime;
        }

        return $this->dateTimeCalculator->getTimeInHoursAndMinutesFromMinutes($lunchbreakTime);
    }


    public function getShiftLimit()
    {
        return $this->shiftLimit;
    }

    public function setShiftLimit($shiftLimit)
    {
        $this->shiftLimit = $shiftLimit;
    }

    public function getEveningshiftPeriod()
    {
        return $this->eveningshiftPeriod;
    }

    public function setEveningshiftPeriod($eveningshiftPeriod)
    {
        $this->eveningshiftPeriod = $eveningshiftPeriod;
    }

    public function getNightshiftPeriod()
    {
        return $this->nightshiftPeriod;
    }

    public function setNightshiftPeriod($nightshiftPeriod)
    {
        $this->nightshiftPeriod = $nightshiftPeriod;
    }

    public function getOvertime()
    {
        if ($this->getWork()->getWorkStartTime() && $this->getWork()->getWorkEndTime()) {
            $dateTimeCalculator = new DateTimeCalculator();

            $workedHours = $dateTimeCalculator->getTimeInMinutes($this->getWork()->getWorkStartTime(),
                $this->getWork()->getWorkEndTime());

            if ($this->getLunchBreak()) {
                $workedHours -= $dateTimeCalculator->getOverlapInMinutes($this->getWork()->getWorkStartTime(),
                    $this->getWork()->getWorkEndTime(), $this->getLunchBreak()->getBreakStartTime(),
                    $this->getLunchBreak()->getBreakEndTime());
            }

            if ($workedHours <= $this->shiftLimit->getShiftLimitInMinutes()) {
                return 0;
            }

            return ($workedHours - $this->shiftLimit->getShiftLimitInMinutes());
        }

        return 0;
    }

    public function getEveningShiftTime()
    {
        if ($this->getWork()->getWorkStartTime() && $this->getWork()->getWorkEndTime()) {
            $eveningStartTime = DateTime::createFromFormat('d.m.Y H:i',
                sprintf('%s %s', $this->getWork()->getWorkStartTime()->format('d.m.Y'),
                    $this->eveningshiftPeriod->getStart()));
            $eveningEndTime   = DateTime::createFromFormat('d.m.Y H:i',
                sprintf('%s %s', $this->getWork()->getWorkStartTime()->format('d.m.Y'),
                    $this->eveningshiftPeriod->getEnd()));

            $dateTimeCalculator = new DateTimeCalculator();

            return $dateTimeCalculator->getOverlapInMinutes($this->getWork()->getWorkStartTime(),
                $this->getWork()->getWorkEndTime(), $eveningStartTime, $eveningEndTime);
        }

        return 0;
    }

    public function getNightShiftTime()
    {
        if ($this->getWork()->getWorkStartTime() && $this->getWork()->getWorkEndTime()) {
            $copy = clone $this->getWork()->getWorkStartTime();
            $copy->add(new DateInterval('P1D'));

            $nightStartTime = DateTime::createFromFormat('d.m.Y H:i',
                sprintf('%s %s', $this->getWork()->getWorkStartTime()->format('d.m.Y'),
                    $this->nightshiftPeriod->getStart()));
            $nightEndTime   = DateTime::createFromFormat('d.m.Y H:i',
                sprintf('%s %s', $copy->format('d.m.Y'), $this->nightshiftPeriod->getEnd()));

            $dateTimeCalculator = new DateTimeCalculator();

            return $dateTimeCalculator->getOverlapInMinutes($this->getWork()->getWorkStartTime(),
                $this->getWork()->getWorkEndTime(), $nightStartTime, $nightEndTime);
        }

        return 0;
    }
}
