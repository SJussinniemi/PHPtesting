<?php

require_once "Workshift.php";
require_once "Report.php";

class ReportService
{
    /**
     * @param \Employee $employee
     *
     * @return array
     */
    public function getSummaryGroupByWorkshift(Employee $employee)
    {
        $reports = array();

        foreach ($employee->getWorkshifts() as $workshift) {
            /** @var \Workshift $workshift */
            $report = new Report();
            $report->setWorkshiftTotalTime($workshift->getWorkTime(true))
                   ->setWorkshiftWithoutLunchTotalTime($workshift->getWorkTimeMinusLunch(true))
                   ->setOvertimeTotalTime($workshift->getOvertime())
                   ->setOvertime50Time($this->getOvertime50PercentHours($workshift->getOvertime()))
                   ->setOvertime100Time($this->getOvertime100PercentHours($workshift->getOvertime()))
                   ->setEveningTotalTime($workshift->getEveningShiftTime())
                   ->setNightTotalTime($workshift->getNightShiftTime())
                   ->setTravelTotalTime($workshift->getTravelTime(true))
                   ->setShiftBreaksTotalTime($workshift->getShiftbreakTime(true))
                   ->setProductsTotal($workshift->getTotalPriceOfAllProductsWithTaxes())
                   ->setProductsRows($workshift->getTotalPriceByProductRowsWithTaxes())
                   ->setAllowancesTotal($workshift->getTotalCostOfAllAllowances())
                   ->setAllowancesRows($workshift->getTotalCostByAllowanceRows());

            $reports[] = $report;
        }

        return $reports;
    }

    /**
     * @param \Employee $employee
     *
     * @return array
     */
    public function getSummaryGroupByWorkday(Employee $employee)
    {
        $reports = array();

        foreach ($employee->getWorkshifts() as $workshift) {
            /** @var \Workshift $workshift */
            $startDateTime = $workshift->getWork()->getWorkStartTime();
            $startDate     = $startDateTime->format('d.m.Y');

            $i = 0;
            do {
                if (count($reports) > 0 && $startDate == $reports[$i]->getShiftStartDate()) {
                    $workTotal  = $reports[$i]->getWorkshiftTotalTime() + $workshift->getWorkTime(true);
                    $shiftlimit = $workshift->getShiftLimit()->getShiftLimitInMinutes();

                    $overtime = 0;
                    if ($workTotal > $shiftlimit) {
                        $overtime = $workTotal - $shiftlimit;
                    }

                    $reports[$i]->addWorkshiftTotalTime($workshift->getWorkTime(true))
                                ->addWorkshiftWithoutLunchTotalTime($workshift->getWorkTimeMinusLunch(true))
                                ->setOvertimeTotalTime($overtime)
                                ->setOvertime50Time($this->getOvertime50PercentHours($overtime))
                                ->setOvertime100Time($this->getOvertime100PercentHours($overtime))
                                ->addEveningTotalTime($workshift->getEveningShiftTime())
                                ->addNightTotalTime($workshift->getNightShiftTime())
                                ->addTravelTotalTime($workshift->getTravelTime(true))
                                ->addShiftBreaksTotalTime($workshift->getShiftbreakTime(true))
                                ->addProductsTotal($workshift->getTotalPriceOfAllProductsWithTaxes())
                                ->addProductsRows($workshift->getTotalPriceByProductRowsWithTaxes())
                                ->addAllowancesTotal($workshift->getTotalCostOfAllAllowances())
                                ->addAllowancesRows($workshift->getTotalCostByAllowanceRows());
                } else {
                    $report = new Report();
                    $report->setShiftStartDate($startDate);
                    $report->setWorkshiftTotalTime($workshift->getWorkTime(true))
                           ->setWorkshiftWithoutLunchTotalTime($workshift->getWorkTimeMinusLunch(true))
                           ->setOvertimeTotalTime($workshift->getOvertime())
                           ->setOvertime50Time($this->getOvertime50PercentHours($workshift->getOvertime()))
                           ->setOvertime100Time($this->getOvertime100PercentHours($workshift->getOvertime()))
                           ->setEveningTotalTime($workshift->getEveningShiftTime())
                           ->setNightTotalTime($workshift->getNightShiftTime())
                           ->setTravelTotalTime($workshift->getTravelTime(true))
                           ->setShiftBreaksTotalTime($workshift->getShiftbreakTime(true))
                           ->setProductsTotal($workshift->getTotalPriceOfAllProductsWithTaxes())
                           ->setProductsRows($workshift->getTotalPriceByProductRowsWithTaxes())
                           ->setAllowancesTotal($workshift->getTotalCostOfAllAllowances())
                           ->setAllowancesRows($workshift->getTotalCostByAllowanceRows());

                    $reports[] = $report;
                }
                $i++;

            } while ($i < count($reports));
        }

        return $reports;
    }

    public function getSummaryGroupByWeek(Employee $employee)
    {
        $days    = array();
        $reports = array();

        foreach ($employee->getWorkshifts() as $workshift) {
            /** @var \Workshift $workshift */
            $startDate                         = $workshift->getWork()->getWorkStartTime();
            $key                               = $startDate->format('W');
            $days[$startDate->format('d.m.Y')] = true;

            if (!isset($reports[$key])) {
                $report = new Report();
                $report->setWorkshiftTotalTime($workshift->getWorkTime(true))
                       ->setWorkshiftWithoutLunchTotalTime($workshift->getWorkTimeMinusLunch(true))
                       ->setOvertimeTotalTime($workshift->getOvertime())
                       ->setOvertime50Time($this->getOvertime50PercentHours($workshift->getOvertime()))
                       ->setOvertime100Time($this->getOvertime100PercentHours($workshift->getOvertime()))
                       ->setEveningTotalTime($workshift->getEveningShiftTime())
                       ->setNightTotalTime($workshift->getNightShiftTime())
                       ->setTravelTotalTime($workshift->getTravelTime(true))
                       ->setShiftBreaksTotalTime($workshift->getShiftbreakTime(true))
                       ->setProductsTotal($workshift->getTotalPriceOfAllProductsWithTaxes())
                       ->setProductsRows($workshift->getTotalPriceByProductRowsWithTaxes())
                       ->setAllowancesTotal($workshift->getTotalCostOfAllAllowances())
                       ->setAllowancesRows($workshift->getTotalCostByAllowanceRows());

                $reports[$key] = $report;
            } else {
                $workTotal  = $reports[$key]->getWorkshiftTotalTime() + $workshift->getWorkTime(true);
                $shiftlimit = $workshift->getShiftLimit()->getShiftLimitInMinutes() * count($days);

                $overtime = 0;
                if ($workTotal > $shiftlimit) {
                    $overtime = $workTotal - $shiftlimit;
                }

                $reports[$key]->addWorkshiftTotalTime($workshift->getWorkTime(true))
                              ->addWorkshiftWithoutLunchTotalTime($workshift->getWorkTimeMinusLunch(true))
                              ->setOvertimeTotalTime($overtime)
                              ->setOvertime50Time($this->getOvertime50PercentHours($overtime))
                              ->setOvertime100Time($this->getOvertime100PercentHours($overtime))
                              ->addEveningTotalTime($workshift->getEveningShiftTime())
                              ->addNightTotalTime($workshift->getNightShiftTime())
                              ->addTravelTotalTime($workshift->getTravelTime(true))
                              ->addShiftBreaksTotalTime($workshift->getShiftbreakTime(true))
                              ->addProductsTotal($workshift->getTotalPriceOfAllProductsWithTaxes())
                              ->addProductsRows($workshift->getTotalPriceByProductRowsWithTaxes())
                              ->addAllowancesTotal($workshift->getTotalCostOfAllAllowances())
                              ->addAllowancesRows($workshift->getTotalCostByAllowanceRows());
            }
        }

        return $reports;
    }

    private function getOvertime50PercentHours($overtime)
    {
        if ($overtime > 120) {
            return 120;
        }

        return $overtime;
    }

    private function getOvertime100PercentHours($overtime)
    {
        if ($overtime > 120) {
            return $overtime - 120;
        }

        return 0;
    }
}