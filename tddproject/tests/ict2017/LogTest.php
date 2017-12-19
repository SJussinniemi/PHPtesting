<?php

require_once("duunissa/class/ict2017/Log.php");
require_once("duunissa/class/ict2017/Work.php");
require_once("duunissa/class/ict2017/Workshift.php");

class LogTest extends \Codeception\Test\Unit
{
    const DATETIME_FORMAT = "d.m.Y H:i";

    private $log;
    private $message;
    private $timestamp;

    protected function _before()
    {
        $this->message   = 'Workshift log message.';
        $this->timestamp = '29.11.2017 09:15';
        $this->log       = new Log(1, $this->message, new DateTime($this->timestamp));
    }

    protected function _after()
    {
    }

    public function testLogClassIsFound()
    {
        $this->assertInstanceOf(Log::class, $this->log);
    }

    public function testGetMessage()
    {
        $this->assertEquals($this->log->getMessage(), $this->message);
    }

    public function testGetTimestamp()
    {
        $this->assertEquals($this->log->getTimestamp()->format('d.m.Y H:i'), $this->timestamp);
    }

    public function testCreateLogObjectIdInvalid()
    {
        $this->setExpectedException(Exception::class, 'Log ID should be greater than 0.');
        $log = new Log(-1, $this->message, new DateTime($this->timestamp));
        $log->setID(0);
    }
}