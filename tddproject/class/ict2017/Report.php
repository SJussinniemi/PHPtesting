<?php

class Report
{
    private $shiftStartDate;
    private $shiftBreaksTotalTime;

    private $travelTotalTime;

    private $workshiftTotalTime;
    private $workshiftWithoutLunchTotalTime;

    private $overtimeTotalTime;
    private $overtime50Time;
    private $overtime100Time;

    private $eveningTotalTime;
    private $nightTotalTime;

    private $productsTotal;
    private $productsRows;

    private $allowacesTotal;
    private $allowancesRows;

    public function __construct()
    {
        $this->shiftBreaksTotalTime = 0;

        $this->travelTotalTime = 0;

        $this->workshiftTotalTime             = 0;
        $this->workshiftWithoutLunchTotalTime = 0;

        $this->overtimeTotalTime = 0;
        $this->overtime50Time    = 0;
        $this->overtime100Time   = 0;

        $this->eveningTotalTime = 0;
        $this->nightTotalTime   = 0;

        $this->productsTotal = 0;
        $this->productsRows  = array();

        $this->allowacesTotal = 0;
        $this->allowancesRows = array();
    }

    public function getShiftStartDate()
    {
        return $this->shiftStartDate;
    }

    public function setShiftStartDate($shiftStartDate)
    {
        $this->shiftStartDate = $shiftStartDate;
    }

    /**
     * @return int
     */
    public function getShiftBreaksTotalTime()
    {
        return $this->shiftBreaksTotalTime;
    }

    /**
     * @param int $shiftBreaksTotalTime
     *
     * @return Report
     */
    public function setShiftBreaksTotalTime($shiftBreaksTotalTime)
    {
        $this->shiftBreaksTotalTime = $shiftBreaksTotalTime;

        return $this;
    }

    public function addShiftBreaksTotalTime($shiftBreaksTotalTime)
    {
        $this->shiftBreaksTotalTime += $shiftBreaksTotalTime;

        return $this;
    }

    /**
     * @return int
     */
    public function getTravelTotalTime()
    {
        return $this->travelTotalTime;
    }

    /**
     * @param int $travelTotalTime
     *
     * @return Report
     */
    public function setTravelTotalTime($travelTotalTime)
    {
        $this->travelTotalTime = $travelTotalTime;

        return $this;
    }

    public function addTravelTotalTime($travelTotalTime)
    {
        $this->travelTotalTime += $travelTotalTime;

        return $this;
    }

    /**
     * @return int
     */
    public function getWorkshiftTotalTime()
    {
        return $this->workshiftTotalTime;
    }

    /**
     * @param int $workshiftTotalTime
     *
     * @return Report
     */
    public function setWorkshiftTotalTime($workshiftTotalTime)
    {
        $this->workshiftTotalTime = $workshiftTotalTime;

        return $this;
    }

    public function addWorkshiftTotalTime($workshiftTotalTime)
    {
        $this->workshiftTotalTime += $workshiftTotalTime;

        return $this;
    }

    /**
     * @return int
     */
    public function getWorkshiftWithoutLunchTotalTime()
    {
        return $this->workshiftWithoutLunchTotalTime;
    }

    /**
     * @param int $workshiftWithoutLunchTotalTime
     *
     * @return Report
     */
    public function setWorkshiftWithoutLunchTotalTime($workshiftWithoutLunchTotalTime)
    {
        $this->workshiftWithoutLunchTotalTime = $workshiftWithoutLunchTotalTime;

        return $this;
    }

    public function addWorkshiftWithoutLunchTotalTime($workshiftWithoutLunchTotalTime)
    {
        $this->workshiftWithoutLunchTotalTime += $workshiftWithoutLunchTotalTime;

        return $this;
    }

    /**
     * @return int
     */
    public function getOvertimeTotalTime()
    {
        return $this->overtimeTotalTime;
    }

    /**
     * @param int $overtimeTotalTime
     *
     * @return Report
     */
    public function setOvertimeTotalTime($overtimeTotalTime)
    {
        $this->overtimeTotalTime = $overtimeTotalTime;

        return $this;
    }

    public function addOvertimeTotalTime($overtimeTotalTime)
    {
        $this->overtimeTotalTime += $overtimeTotalTime;

        return $this;
    }

    /**
     * @return int
     */
    public function getOvertime50Time()
    {
        return $this->overtime50Time;
    }

    /**
     * @param int $overtime50Time
     *
     * @return Report
     */
    public function setOvertime50Time($overtime50Time)
    {
        $this->overtime50Time = $overtime50Time;

        return $this;
    }

    public function addOvertime50Time($overtime50Time)
    {
        $this->overtime50Time += $overtime50Time;

        return $this;
    }

    /**
     * @return int
     */
    public function getOvertime100Time()
    {
        return $this->overtime100Time;
    }

    /**
     * @param int $overtime100Time
     *
     * @return Report
     */
    public function setOvertime100Time($overtime100Time)
    {
        $this->overtime100Time = $overtime100Time;

        return $this;
    }

    public function addOvertime100Time($overtime100Time)
    {
        $this->overtime100Time += $overtime100Time;

        return $this;
    }

    /**
     * @return int
     */
    public function getEveningTotalTime()
    {
        return $this->eveningTotalTime;
    }

    /**
     * @param int $eveningTotalTime
     *
     * @return \Report
     */
    public function setEveningTotalTime($eveningTotalTime)
    {
        $this->eveningTotalTime = $eveningTotalTime;

        return $this;
    }

    public function addEveningTotalTime($eveningTotalTime)
    {
        $this->eveningTotalTime += $eveningTotalTime;

        return $this;
    }

    /**
     * @return int
     */
    public function getNightTotalTime()
    {
        return $this->nightTotalTime;
    }

    /**
     * @param int $nightTotalTime
     *
     * @return \Report
     */
    public function setNightTotalTime($nightTotalTime)
    {
        $this->nightTotalTime = $nightTotalTime;

        return $this;
    }

    public function addNightTotalTime($nightTotalTime)
    {
        $this->nightTotalTime += $nightTotalTime;

        return $this;
    }

    /**
     * @return int
     */
    public function getProductsTotal()
    {
        return $this->productsTotal;
    }

    /**
     * @param int $productsTotal
     *
     * @return Report
     */
    public function setProductsTotal($productsTotal)
    {
        $this->productsTotal = $productsTotal;

        return $this;
    }

    public function addProductsTotal($productsTotal)
    {
        $this->productsTotal += $productsTotal;

        return $this;
    }

    /**
     * @return array
     */
    public function getProductsRows()
    {
        return $this->productsRows;
    }

    /**
     * @param array $productsRows
     *
     * @return Report
     */
    public function setProductsRows($productsRows)
    {
        $this->productsRows = $productsRows;

        return $this;
    }

    public function addProductsRows($productsRows)
    {
        $this->productsRows = $this->sumArrayValues($this->productsRows, $productsRows);

        return $this;
    }

    /**
     * @return int
     */
    public function getAllowancesTotal()
    {
        return $this->allowacesTotal;
    }

    /**
     * @param int $allowacesTotal
     *
     * @return Report
     */
    public function setAllowancesTotal($allowacesTotal)
    {
        $this->allowacesTotal = $allowacesTotal;

        return $this;
    }

    public function addAllowancesTotal($allowacesTotal)
    {
        $this->allowacesTotal += $allowacesTotal;

        return $this;
    }

    /**
     * @return array
     */
    public function getAllowancesRows()
    {
        return $this->allowancesRows;
    }

    /**
     * @param array $allowancesRows
     *
     * @return Report
     */
    public function setAllowancesRows($allowancesRows)
    {
        $this->allowancesRows = $allowancesRows;

        return $this;
    }

    public function addAllowancesRows($allowancesRows)
    {
        $this->allowancesRows = $this->sumArrayValues($this->allowancesRows, $allowancesRows);

        return $this;
    }

    public function getWeeklyOvertime()
    {
        $nonDailyOvertime = $this->getWorkshiftTotalTime() - $this->getOvertimeTotalTime();

        return $nonDailyOvertime - (40 * 60);
    }

    private function sumArrayValues($array1, $array2)
    {
        $array  = array($array1, $array2);
        $result = array();

        array_walk_recursive($array, function ($item, $key) use (&$result) {
            $result[$key] = isset($result[$key]) ? $item + $result[$key] : $item;
        });

        return $result;
    }
}