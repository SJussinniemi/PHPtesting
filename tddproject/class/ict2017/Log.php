<?php

class Log
{
    private $id;
    private $message;
    private $timestamp;

    public function __construct($id, $message, $timestamp)
    {
        if ($id == null || $id <= 0) {
            throw new Exception('Log ID should be greater than 0.');
        }

        $this->id        = $id;
        $this->message   = $message;
        $this->timestamp = $timestamp;
    }

    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        if ($id == null || $id <= 0) {
            throw new Exception('Log ID should be greater than 0.');
        }

        $this->id = $id;
    }

    public function getMessage()
    {
        return $this->message;
    }

    public function setMessage($message)
    {
        $this->message = $message;
    }

    public function getTimestamp()
    {
        return $this->timestamp;
    }

    public function setTimestamp($timestamp)
    {
        $this->timestamp = $timestamp;
    }
}