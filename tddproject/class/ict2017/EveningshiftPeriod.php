<?php

class EveningshiftPeriod
{
    private $start;
    private $end;

    public function __construct($start = '18:00', $end = '23:00')
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