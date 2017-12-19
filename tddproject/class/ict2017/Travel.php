<?php

class Travel
{
    private $id;
    private $travelStartTime;
    private $travelEndTime;
    private $travelStartLocation;
    private $travelEndLocation;

    public function __construct(
        $id,
        $travelStartTime,
        $travelEndTime = null,
        $travelStartLocation = null,
        $travelEndLocation = null
    ) {
        if ($id == null || $id <= 0) {
            throw new Exception('Travel ID should be greater than 0.');
        }

        $this->id                  = $id;
        $this->travelStartTime     = $travelStartTime;
        $this->travelEndTime       = $travelEndTime;
        $this->travelStartLocation = $travelStartLocation;
        $this->travelEndLocation   = $travelEndLocation;
    }

    public function getTravelId()
    {
        return $this->id;
    }

    public function setTravelId($id)
    {
        if ($id == null || $id <= 0) {
            throw new Exception('Travel ID should be greater than 0.');
        }

        $this->id = $id;
    }

    public function getTravelStartTime()
    {
        return $this->travelStartTime;
    }

    public function setTravelStartTime($travelStartTime)
    {
        $this->travelStartTime = $travelStartTime;
    }

    public function getTravelEndTime()
    {
        return $this->travelEndTime;
    }

    public function setTravelEndTime($travelEndTime)
    {
        $this->travelEndTime = $travelEndTime;
    }

    public function getTravelStartLocation()
    {
        return $this->travelStartLocation;
    }

    public function setTravelStartLocation($travelStartLocation)
    {
        $this->travelStartLocation = $travelStartLocation;
    }

    public function getTravelEndLocation()
    {
        return $this->travelEndLocation;
    }

    public function setTravelEndLocation($travelEndLocation)
    {
        $this->travelEndLocation = $travelEndLocation;
    }
}

?>