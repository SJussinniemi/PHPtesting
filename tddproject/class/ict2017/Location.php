<?php

class Location {

    private $latitude;
    private $longitude;

    public function __construct($latitude = null, $longitude = null){
        $this->latitude = $latitude;
        $this->longitude = $longitude;
    }

    public function setLatitude($latitude){
        $this->latitude = $latitude;
    }

    public function getLatitude(){
        return $this->latitude;
    }

    public function setLongitude($longitude){
        $this->longitude = $longitude;
    }

    public function getLongitude(){
        return $this->longitude;
    }

}

?>