<?php

require_once("duunissa/class/ict2017/Project.php");
require_once("duunissa/class/ict2017/Work.php");
require_once("duunissa/class/ict2017/Workshift.php");


class UserStory_0008Test extends \Codeception\Test\Unit
{
    /**
     * As an employee 
     * I want to mark project to my workshift 
     * so that I can see what was the context
     */

    const DATETIME_FORMAT = "d.m.Y H:i";

    public function _before()
    {
    }

    public function _after()
    {
    }

    /**
     * Scenario 1:
     * Given workshift start time 17.11.2017 9:00
     * And the project 55 "Project X"
     * And the employee 20 "John Doe"
     * Then workshift's project ID should be 55
     * And workshift's project description should be "Project X"
     */
    public function testScenario1ValidWorkshiftWithProject()
    {
        $id = 55;
        $description = 'Project X';

        $work = new Work(1, new DateTime('17.11.2017 09:00'), null);
        $project = new Project($id, $description);
        $employee = new Employee(20, 'John', 'Doe');

        $workshift = new Workshift($employee, $work);
        $workshift->setProject($project);

        $project = $workshift->getProject();

        $this->assertEquals($project->getId(), $id);
        $this->assertEquals($project->getDescription(), $description);
    }

    /**
     * Scenario 2:
     * Given workshift start time 17.11.2017 9:00
     * And no project is informed
     * And the employee 20 "John Doe"
     * Then workshift's project should be null
     */
    public function testScenario2ValidWorkshiftWithoutProject()
    {
        $work = new Work(1, new DateTime('17.11.2017 09:00'), null);
        $employee = new Employee(20, 'John', 'Doe');

        $workshift = new Workshift($employee,$work);

        $this->assertEquals($workshift->getProject(), null);
    }
}