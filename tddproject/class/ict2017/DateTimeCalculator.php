<?php

class DateTimeCalculator
{
    public function getTimeInHoursAndMinutesFromMinutes($totalminutes)
    {
        $hours   = floor($totalminutes / 60);
        $minutes = $totalminutes - $hours * 60;
        $decimal = round(($totalminutes / 60), 2);

        return ($hours . 'h ' . $minutes . 'min (' . number_format($decimal, 2, ',', '.') . ')');

    }

    public function getTimeInMinutes($startTime, $endTime)
    {
        if ($startTime == null || $endTime == null) {
            return 0;
        } else {
            if ($startTime > $endTime) {
                throw new Exception('End time is before start time');
            } else {
                if ($startTime < $endTime) {
                    $dateTime = $startTime->diff($endTime);
                    $minutes  = 0;
                    $minutes  += $dateTime->format('%d') * 1440;
                    $minutes  += $dateTime->format('%h') * 60;
                    $minutes  += $dateTime->format('%i');

                    return $minutes;
                }
            }
        }
    }

    public function getOverlapInMinutes($start_one, $end_one, $start_two, $end_two)
    {
        $dp1 = new DatePeriod($start_one, new DateInterval('PT1M'), $end_one);
        $dp2 = new DatePeriod($start_two, new DateInterval('PT1M'), $end_two);

        $startOne = $dp1->getStartDate();
        $endOne   = $dp1->getEndDate();

        $startTwo = $dp2->getStartDate();
        $endTwo   = $dp2->getEndDate();

        if ($startOne < $endTwo && $endOne > $startTwo) {
            return iterator_count(new DatePeriod(
                max($startTwo, $startOne),
                new DateInterval('PT1M'),
                min($endOne, $endTwo)
            ));
        }

        return 0;
    }
}

?>
