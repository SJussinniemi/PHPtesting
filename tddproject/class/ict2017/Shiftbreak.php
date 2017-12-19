<?php

class Shiftbreak
{
    private $id;
    private $breakStartTime;
    private $breakEndTime;
    private $breakStartLocation;
    private $breakEndLocation;

    public function __construct(
        $id,
        $breakStartTime,
        $breakEndTime = null,
        $breakStartLocation = null,
        $breakEndLocation = null
    ) {
        if ($id == null || $id <= 0) {
            throw new Exception('Shiftbreak ID should be greater than 0.');
        }

        $this->id                 = $id;
        $this->breakStartTime     = $breakStartTime;
        $this->breakEndTime       = $breakEndTime;
        $this->breakStartLocation = $breakStartLocation;
        $this->breakEndLocation   = $breakEndLocation;
    }

    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        if ($id == null || $id <= 0) {
            throw new Exception('Shiftbreak ID should be greater than 0.');
        }

        $this->id = $id;
    }

    public function getBreakStartTime()
    {
        return $this->breakStartTime;
    }

    public function setBreakStartTime($breakStartTime)
    {
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

    public function getBreakStartLocation()
    {
        return $this->breakStartLocation;
    }

    public function setBreakStartLocation($breakStartLocation)
    {
        $this->breakStartLocation = $breakStartLocation;
    }

    public function getBreakEndLocation()
    {
        return $this->breakEndLocation;
    }

    public function setBreakEndLocation($breakEndLocation)
    {
        $this->breakEndLocation = $breakEndLocation;
    }
}

?>
