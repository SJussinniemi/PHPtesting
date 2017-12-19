<?php

class LunchBreak
{
    private $breakStartTime;
    private $breakEndTIme;

    public function __construct($breakStartTime, $breakEndTIme)
    {
        $this->breakStartTime = $breakStartTime;
        $this->breakEndTime = $breakEndTIme;
    }

    public function getBreakStartTime()
    {
        return $this->breakStartTime;
    }

    public function setBreakStartTime($breakStartTime)
    {
        if(!$breakStartTime instanceof DateTime){
            throw new Exception('Lunch start time required a valid start time.');
        }
        
        $this->breakStartTime = $breakStartTime;
    }

    public function getBreakEndTime()
    {
        return $this->breakEndTime;
    }

    public function setBreakEndTime($breakEndTime)
    {
        $this->breakEndTime = $breakEndTime;
    }

}

?>