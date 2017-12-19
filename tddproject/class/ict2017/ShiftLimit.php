<?php

class ShiftLimit
{
    private $hours;

    public function __construct($hours = 8)
    {
        
        $this->hours = $hours;
    }

    public function getShiftLimitInMinutes()
    {
        return $this->hours * 60;
    }
}