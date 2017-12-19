<?php

require_once ("duunissa/class/ict2017/Travel.php");

class TravelTest extends \Codeception\Test\Unit
{
    const DATETIME_FORMAT = "d.m.Y H:i";
    
    public $id, $startTravel, $endTravel, $dateChangesStartTravel, $dateChangesEndTravel;

    protected function _before()
    {
        $this->id=1;
        $this->startTravel = new DateTime('22.11.2017 08:14');
        $this->endTravel = new DateTime('22.11.2017 08:42');
        $this->travelStartLocation = new Location(60.99596, 24.46434);
        $this->travelEndLocation = new Location(60.89596, 24.26434);

        $this->travel = new Travel($this->id, $this->startTravel, $this->endTravel, $this->travelStartLocation, $this->travelEndLocation);
    }

    protected function _after()
    {
    }

    // Test that Travel -class is found
    public function testTravelClassIsFound(){
        $this->assertInstanceOf(Travel::class, $this->travel);
    }

    // Test getting Travel id
    public function testGettingTravelId(){
        $this->assertEquals(1, $this->travel->getTravelId());
    }

    // Test setter and getter for travel start
    public function testGettingTravelStartTime(){
        $this->travel->setTravelStartTime(new DateTime('22.11.2017 08:14'));
        $this->assertEquals(new DateTime('22.11.2017 08:14'), $this->travel->getTravelStartTime());
    }
    
    // Test setter and getter for travel end
    public function testGettingTravelEndTime(){
        $this->travel->setTravelEndTime(new DateTime('22.11.2017 08:42'));
        $this->assertEquals(new DateTime('22.11.2017 08:42'), $this->travel->getTravelEndTime());
    }
    
    // Get start location
    public function testGetTravelStartLocation()
    {
        $this->assertEquals(new Location(60.99596, 24.46434), $this->travel->getTravelStartLocation());
        $this->assertEquals(60.99596, $this->travel->getTravelStartLocation()->getLatitude());
        $this->assertEquals(24.46434, $this->travel->getTravelStartLocation()->getLongitude());
    }

    // Get end location
    public function testGetTravelEndLocation()
    {
        $this->assertEquals(new Location(60.89596, 24.26434), $this->travel->getTravelEndLocation());
        $this->assertEquals(60.89596, $this->travel->getTravelEndLocation()->getLatitude());
        $this->assertEquals(24.26434, $this->travel->getTravelEndLocation()->getLongitude());
    }

    public function testCreateTravelObjectIdInvalid()
    {
        $this->setExpectedException(Exception::class, 'Travel ID should be greater than 0.');
        $travel = new Travel(-1, new DateTime('22.11.2017 13:25'), new DateTime('22.11.2017 13:25'));
        $travel->setTravelId(0);
    }
}