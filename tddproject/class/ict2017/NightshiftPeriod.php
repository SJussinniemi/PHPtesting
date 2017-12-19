<?php

class NightshiftPeriod
{
    private $start;
    private $end;

    public function __construct($start = '23:00', $end = '06:00')
    {
        $this->start = $start;
        $this->end   = $end;
    }

    public function getStart()
    {
        return $this->start;
    }

    public function getEnd()
    {
        return $this->end;
    }
}