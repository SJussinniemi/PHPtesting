<?php

require_once("duunissa/class/ict2017/ShiftLimit.php");

class ShiftLimitTest extends \Codeception\Test\Unit
{
    private $hours;
    private $overtime;

    public function _before()
    {
        $this->hours    = 8;
        $this->overtime = new ShiftLimit($this->hours);
    }

    public function _after()
    {
    }

    public function getGetShiftLimitInMinutes()
    {
        $this->assertEquals(480, $this->overtime->getShiftLimitInMinutes());
    }
}