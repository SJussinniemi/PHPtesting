<?php

class Employee
{
    private $id;
    private $firstName;
    private $lastName;
    private $workshifts;

    public function __construct($id, $firstName, $lastName)
    {
        if ($id == null || $id <= 0) {
            throw new Exception('Employee ID should be greater than 0.');
        }

        $this->id         = $id;
        $this->firstName  = $firstName;
        $this->lastName   = $lastName;
        $this->workshifts = array();
    }

    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        if ($id == null || $id <= 0) {
            throw new Exception('Employee ID should be greater than 0.');
        }

        $this->id = $id;
    }

    public function getFirstName()
    {
        return $this->firstName;
    }

    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;
    }

    public function setLastName($lastName)
    {
        $this->lastName = $lastName;
    }

    public function getLastName()
    {
        return $this->lastName;
    }

    public function addWorkshift(Workshift $newWorkshift)
    {
        $newWork = $newWorkshift->getWork();

        foreach ($this->workshifts as $workshift) {
            $work = $workshift->getWork();

            if (!$work->getWorkEndTime() && $work->getWorkStartTime() < $newWork->getWorkStartTime()) {
                throw new Exception('There is a work unfinished conflicting with the new one.');
            }

            if ($work->getWorkEndTime() && $work->getWorkStartTime() < $newWork->getWorkStartTime() && $work->getWorkEndTime() > $newWork->getWorkStartTime()) {
                throw new Exception('The new work is in the middle of existing work.');
            }

            if ($work->getWorkEndTime() && $work->getWorkStartTime() < $newWork->getWorkEndTime() && $work->getWorkEndTime() > $newWork->getWorkEndTime()) {
                throw new Exception('The new work is in the middle of existing work.');
            }

            if ($work->getWorkEndTime() && $work->getWorkStartTime() > $newWork->getWorkStartTime() && $work->getWorkEndTime() < $newWork->getWorkEndTime()) {
                throw new Exception('There is an existing work between the new one.');
            }
        }

        $this->workshifts[] = $newWorkshift;
    }

    public function getWorkshifts()
    {
        return $this->workshifts;
    }
}

?>