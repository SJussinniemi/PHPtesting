<?php
require_once ("duunissa/class/ict2017/Location.php");

class LocationTest extends \Codeception\Test\Unit
{
    protected function _before()
    {
        $latitude = 60.99596;
        $longitude = 24.46434;
        $this->location = new Location($latitude, $longitude);
    }

    protected function _after()
    {
    }

    // tests
    public function testWorkClassIsFound()
    {
        $this->assertInstanceOf(Location::class, $this->location);
    }

    public function testGetLatitude()
    {
        $this->assertEquals(60.99596, $this->location->getLatitude());
    }

    public function testGetLongitude()
    {
        $this->assertEquals(24.46434, $this->location->getLongitude());
    }
}