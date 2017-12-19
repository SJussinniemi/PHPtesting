<?php

require_once("duunissa/class/ict2017/EveningshiftPeriod.php");

class EveningshiftPeriodTest extends \Codeception\Test\Unit
{
    private $eveningshift;
    private $start;
    private $end;

    public function _before()
    {
        $this->start        = '18:00';
        $this->end          = '22:00';
        $this->eveningshift = new EveningshiftPeriod($this->start, $this->end);
    }

    public function _after()
    {
    }

    public function testGetStartNightshift()
    {
        $this->assertEquals($this->eveningshift->getStart(), $this->start);
    }

    public function testGetEndNightshift()
    {
        $this->assertEquals($this->eveningshift->getEnd(), $this->end);
    }
}