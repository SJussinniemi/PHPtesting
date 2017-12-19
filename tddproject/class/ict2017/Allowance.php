<?php

class Allowance
{
    private $id;            // allowance identifier (integer)
    private $name;          // name or title
    private $type;          // allowance type (kilometrikorvaus, kotimaan kokopäiväraha)
    private $unit;          // unit (kg, km)
    private $unitCost;      // unit cost (euro)
    private $amount;         // amount
    private $timeStamp;     // timestamp when added
    private $description;    // description or other simple additional message

    public function __construct($id, $name, $type, $unit, $unitCost, $amount, $timeStamp, $description)
    {
        if ($id == null || $id <= 0) {
            throw new Exception('Allowance ID should be greater than 0.');
        }

        $this->id          = $id;
        $this->name        = $name;
        $this->type        = $type;
        $this->unit        = $unit;
        $this->unitCost    = $unitCost;
        $this->amount      = $amount;
        $this->timeStamp   = $timeStamp;
        $this->description = $description;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getType()
    {
        return $this->type;
    }

    public function getUnit()
    {
        return $this->unit;
    }

    public function getUnitCost()
    {
        return $this->unitCost;
    }

    public function getAmount()
    {
        return $this->amount;
    }

    public function getTimeStamp()
    {
        return $this->timeStamp;
    }

    public function getDescription()
    {
        return $this->description;
    }

}

?>