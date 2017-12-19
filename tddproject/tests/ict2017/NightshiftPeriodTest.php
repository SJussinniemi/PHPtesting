<?php

require_once("duunissa/class/ict2017/NightshiftPeriod.php");

class NightshiftPeriodTest extends \Codeception\Test\Unit
{
    private $nightshift;
    private $start;
    private $end;

    public function _before()
    {
        $this->start      = '22:00';
        $this->end        = '07:00';
        $this->nightshift = new NightshiftPeriod($this->start, $this->end);
    }

    public function _after()
    {
    }

    public function testGetStartNightshift()
    {
        $this->assertEquals($this->nightshift->getStart(), $this->start);
    }

    public function testGetEndNightshift()
    {
        $this->assertEquals($this->nightshift->getEnd(), $this->end);
    }
}