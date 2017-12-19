<?php

require_once("duunissa/class/ict2017/Employee.php");
require_once("duunissa/class/ict2017/Work.php");
require_once("duunissa/class/ict2017/Workshift.php");

class EmployeeTest extends \Codeception\Test\Unit
{
    const DATETIME_FORMAT = "d.m.Y H:i";

    protected function _before()
    {
        $this->employee = new Employee(13, 'Matti', 'Jokinen');
    }

    protected function _after()
    {
    }

    // Employee class is found
    public function testEmployeeClassIsFound()
    {
        $this->assertInstanceOf(Employee::class, $this->employee);
    }

    // Get employee id
    public function testEmployeeID()
    {
        $this->assertEquals(13, $this->employee->getID());
    }

    // Employee id is not less than 0
    public function testEmployeeIDNotUnderZero()
    {
        $this->assertEquals($this->employee->getID() > 0, $this->employee->getID());
    }

    // Test setting and getting Employees first name
    public function testEmployeeFirstName()
    {
        $this->employee->setFirstName('Mat');
        $this->assertEquals('Mat', $this->employee->getFirstName());
    }

    // Test setting and getting Employees last name
    public function testEmployeeLastName()
    {
        $this->employee->setLastName('Joki');
        $this->assertEquals('Joki', $this->employee->getLastName());
    }

    // Error when id is less than 0
    public function testCreateEmployeeObjectIdInvalid()
    {
        $this->setExpectedException(Exception::class, 'Employee ID should be greater than 0.');
        $employee = new Employee(-1, "John", "Doe");
        $employee->setID(0);
    }

    // Multiple workshifts with same employee
    public function testMultipleWorkshifts()
    {
        $work = new Work(1, new DateTime('01.12.2017 09:00'),
            new DateTime('01.12.2017 09:30'));
        new Workshift($this->employee, $work);

        $work2 = new Work(2, new DateTime('01.12.2017 10:00'));
        new Workshift($this->employee, $work2);

        $this->assertEquals(2, count($this->employee->getWorkshifts()));
    }

    // Multiple workshifts when if first is not finished
    public function testMultipleWorkshiftsWhereFirstIsUnfinished()
    {
        $this->setExpectedException(Exception::class, 'There is a work unfinished conflicting with the new one.');

        $work = new Work(1, new DateTime('01.12.2017 09:00'));
        new Workshift($this->employee, $work);

        $work2 = new Work(2, new DateTime('01.12.2017 10:00'));
        new Workshift($this->employee, $work2);
    }

    // Multiple Workshift when new is starting in middle of first shift
    public function testMultipleWorkshiftsWhereStartTimeIsInTheMiddle()
    {
        $this->setExpectedException(Exception::class, 'The new work is in the middle of existing work.');

        $work = new Work(1, new DateTime('01.12.2017 09:00'),
            new DateTime('01.12.2017 11:00'));
        new Workshift($this->employee, $work);

        $work2 = new Work(2, new DateTime('01.12.2017 10:00'));
        new Workshift($this->employee, $work2);
    }

    // Multiple Workshift when new is ending in middle of first shift
    public function testMultipleWorkshiftsWhereEndTimeIsInTheMiddle()
    {
        $this->setExpectedException(Exception::class, 'The new work is in the middle of existing work.');

        $work = new Work(1, new DateTime('01.12.2017 09:00'),
            new DateTime('01.12.2017 11:00'));
        new Workshift($this->employee, $work);

        $work2 = new Work(2, new DateTime('01.12.2017 8:00'),
            new DateTime('01.12.2017 10:00'));
        new Workshift($this->employee, $work2);
    }

    // Multiple Workshift when first shift is in the middle of new shift
    public function testMultipleWorkshiftsWhereExistingWorkIsInTheMiddle()
    {
        $this->setExpectedException(Exception::class, 'There is an existing work between the new one.');

        $work = new Work(1, new DateTime('01.12.2017 09:00'),
            new DateTime('01.12.2017 10:00'));
        new Workshift($this->employee, $work);

        $work2 = new Work(2, new DateTime('01.12.2017 8:00'),
            new DateTime('01.12.2017 11:00'));
        new Workshift($this->employee, $work2);
    }
}