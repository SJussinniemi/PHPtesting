<?php

class Project
{
    private $id;
    private $description;

    public function __construct($id, $description)
    {
        if ($id == null || $id <= 0) {
            throw new Exception('Project ID should be greater than 0.');
        }

        $this->id          = $id;
        $this->description = $description;
    }

    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        if ($id == null || $id <= 0) {
            throw new Exception('Project ID should be greater than 0.');
        }

        $this->id = $id;
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function setDescription($description)
    {
        $this->description = $description;
    }
}