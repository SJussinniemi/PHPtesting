<?php

require_once "ShiftLimit.php";
require_once "EveningshiftPeriod.php";
require_once "NightshiftPeriod.php";
require_once "DateTimeCalculator.php";

class Work
{
    private $id;
    private $workStartTime;
    private $workEndTime;
    private $remoteWork;
    private $workStartLocation;
    private $workEndLocation;

    public function __construct(
        $id,
        $workStartTime,
        $workEndTime = null,
        $workStartLocation = null,
        $workEndLocation = null
    ) {
        if (!$workStartTime instanceof DateTime) {
            throw new Exception('Work start time required a valid start time.');
        }

        if ($id == null || $id <= 0) {
            throw new Exception('Work ID should be greater than 0.');
        }

        $this->id                = $id;
        $this->workStartTime     = $workStartTime;
        $this->workEndTime       = $workEndTime;
        $this->workStartLocation = $workStartLocation;
        $this->workEndLocation   = $workEndLocation;
        $this->remoteWork        = false;


    }

    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        if ($id !== null && $id <= 0) {
            throw new Exception('Work ID should be null or greater than 1.');
        }

        $this->id = $id;
    }

    public function getWorkStartTime()
    {
        return $this->workStartTime;
    }

    public function setWorkStartTime($workStartTime)
    {
        if (!$workStartTime instanceof DateTime) {
            throw new Exception('Work start time required a valid start time.');
        }

        $this->workStartTime = $workStartTime;
    }

    public function getWorkEndTime()
    {
        return $this->workEndTime;
    }

    public function setWorkEndTime($workEndTime)
    {
        if (!$workEndTime instanceof DateTime) {
            throw new Exception('Work start time required a valid start time.');
        }

        $this->workEndTime = $workEndTime;
    }

    public function getRemoteWorkStatus()
    {
        return $this->remoteWork;
    }

    public function startRemoteWorking()
    {
        $this->remoteWork = true;
    }

    public function stopRemoteWorking()
    {
        $this->remoteWork = false;
    }

    public function getWorkStartLocation()
    {
        return $this->workStartLocation;
    }

    public function setWorkStartLocation($workStartLocation)
    {
        $this->workStartLocation = $workStartLocation;
    }

    public function getWorkEndLocation()
    {
        return $this->workEndLocation;
    }

    public function setWorkEndLocation($workEndLocation)
    {
        $this->workEndLocation = $workEndLocation;
    }

    public function isRemoteWork()
    {
        return $this->remoteWork;
    }

    public function setRemoteWork($remoteWork)
    {
        $this->remoteWork = $remoteWork;
    }
}