<?php

require_once("duunissa/class/ict2017/Project.php");

class ProjectTest extends \Codeception\Test\Unit
{
    private $id;
    private $description;
    private $project;

    public function _before()
    {
        $this->id          = 99;
        $this->description = "Project description.";
        $this->project     = new Project($this->id, $this->description);
    }

    public function _after()
    {
    }

    public function testProjectClassIsFound()
    {
        $this->assertInstanceOf(Project::class, $this->project);
    }

    public function testGetId()
    {
        $this->assertEquals($this->project->getId(), $this->id);
    }

    public function testGetDescription()
    {
        $this->assertEquals($this->project->getDescription(), $this->description);
    }

    public function testCreateProjectObjectIdInvalid()
    {
        $this->setExpectedException(Exception::class, 'Project ID should be greater than 0.');
        $project = new Project(-1, $this->description);
        $project->setID(0);
    }
}