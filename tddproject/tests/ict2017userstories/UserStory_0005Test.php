<?php

require_once("duunissa/class/ict2017/Log.php");
require_once("duunissa/class/ict2017/Work.php");
require_once("duunissa/class/ict2017/Workshift.php");
require_once("duunissa/class/ict2017/Location.php");

class UserStory_0005Test extends \Codeception\Test\Unit
{
    /*
     * As an employee
     * I want to log events during my workday
     * so that I can add details to my workshift for my employer.
     */

    const DATETIME_FORMAT = "d.m.Y H:i";

    private $log;
    private $message;

    public function _before()
    {
    }

    public function _after()
    {
    }

    /*
     * Scenario 1:
     * Given workshift start time 17.11.2017 9:00
     * When I submit log message "Lorem ipsum dolor sit amet, meis alterum mei in." at 17.11.2017 10:43
     * Then my latest log message is "Lorem ipsum dolor sit amet, meis alterum mei in."
     * And then log message timestamp is 17.11.2017 10:43
     */
    public function testScenario1LogForWorkshift()
    {
        $employee = new Employee(123, "Jyrki", "Kolehmainen");
        $work      = new Work(123, new DateTime('17.11.2017 09:00'), null, new Location(60.99596, 24.46434), new Location(60.89596, 24.26434));

        $workshift = new Workshift($employee, $work);

        $message   = 'Lorem ipsum dolor sit amet, meis alterum mei in.';
        $timestamp = '17.11.2017 10:43';

        $log = new Log(1, $message, new DateTime($timestamp));
        $workshift->addLog($log);

        $logs = $workshift->getLogs();
        $this->assertEquals($logs[0]->getMessage(), $message);
        $this->assertEquals($logs[0]->getTimestamp()->format('d.m.Y H:i'), $timestamp);
    }

    /*
     * Scenario 2:
     * Given workshift start time 17.11 9:00
     * When I submit event to my workshift "Lorem ipsum dolor sit amet, meis alterum mei in." at 17.11 10:30
     * And I submit another event "Ei mea vituperata dissentias reprehendunt, falli iisque mea cu." at 17.11 13:18
     * Then my latest log message is "Ei mea vituperata dissentias reprehendunt, falli iisque mea cu." with timestamp 17.11 13:18
     * Then then my earliest log message is "Lorem ipsum dolor sit amet, meis alterum mei in." with timestamp 17.11 10:30
     */
    public function testScenario2MultipleLogsForWorkshift()
    {
        $employee = new Employee(123, "Jyrki", "Kolehmainen");
        $work      = new Work(123, new DateTime('17.11.2017 09:00'), null, new Location(60.99596, 24.46434), new Location(60.89596, 24.26434));

        $workshift = new Workshift($employee, $work);

        $message1   = 'Lorem ipsum dolor sit amet, meis alterum mei in.';
        $timestamp1 = '17.11.2017 10:30';

        $log1 = new Log(1, $message1, new DateTime($timestamp1));
        $workshift->addLog($log1);

        $message2   = 'Ei mea vituperata dissentias reprehendunt, falli iisque mea cu.';
        $timestamp2 = '17.11.2017 13:18';

        $log2 = new Log(2, $message2, new DateTime($timestamp2));
        $workshift->addLog($log2);

        $logs = $workshift->getLogs();

        $this->assertEquals($logs[0]->getMessage(), $message1);
        $this->assertEquals($logs[0]->getTimestamp()->format('d.m.Y H:i'), $timestamp1);

        $this->assertEquals($logs[1]->getMessage(), $message2);
        $this->assertEquals($logs[1]->getTimestamp()->format('d.m.Y H:i'), $timestamp2);

    }

    /*
     * Scenario 3:
     * Given workshift valid simple workshift
     * When zero events is logged during workshift
     * Then workshift should return zero logged events.
     */
    public function testScenario3ZeroLogsForWorkshift()
    {
        $employee = new Employee(123, "Jyrki", "Kolehmainen");
        $work      = new Work(123, new DateTime('17.11.2017 09:00'), null, new Location(60.99596, 24.46434), new Location(60.89596, 24.26434));

        $workshift = new Workshift($employee, $work);

        $this->assertEquals(count( $workshift->getLogs()), 0);
    }
}