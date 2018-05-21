<?php

namespace App\Models;

class Area
{
    private $longitude;
    private $latitude;
    private $name;


    public function getName()
    {
        return $this->name;
    }


    public function setName($name)
    {
        $this->name = $name;
    }

    public function getLongitude()
    {
        return $this->longitude;
    }


    public function setLongitude($longitude)
    {
        $this->longitude = $longitude;
    }

    public function getLatitude()
    {
        return $this->latitude;
    }


    public function setLatitude($latitude)
    {
        $this->latitude = $latitude;
    }

    public function __construct($name, $long, $lat)
    {
        $this->latitude = $lat;
        $this->longitude = $long;
        $this->name = $name;
    }

    public function getDistance(Area $area) {
        $earthRadius = 6371;
        // convert from degrees to radians
        $latFrom = deg2rad($this->latitude);
        $lonFrom = deg2rad($this->longitude );
        $latTo = deg2rad($area->latitude);
        $lonTo = deg2rad($area->longitude );

        $lonDelta = $lonTo - $lonFrom;
        $a = pow(cos($latTo) * sin($lonDelta), 2) +
            pow(cos($latFrom) * sin($latTo) - sin($latFrom) * cos($latTo) * cos($lonDelta), 2);
        $b = sin($latFrom) * sin($latTo) + cos($latFrom) * cos($latTo) * cos($lonDelta);

        $angle = atan2(sqrt($a), $b);
        return $angle * $earthRadius;
    }

}