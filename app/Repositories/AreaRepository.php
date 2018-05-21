<?php

namespace App\Repositories;

use Exception;
use App\Models\Area;
use Illuminate\Support\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class AreaRepository
{
    private  $source_path;
    private $limit;
    
    public function getLimit()
    {
        return empty($this->limit) ? $this->getSize(): $this->limit;
    }
    
    public function setLimit($limit)
    {
        $this->limit = $limit;
    }

    public function __construct($limit = null)
    {
        $this->source_path = app_path() . '\Collections\areas.php';
        $this->limit = $limit;
    }

    public function get($offset = 0, $size = null)
    {
        $areas = $this->getFromFile();
        
        if (empty($size)) {
            $collection = array_slice($areas, $offset, $this->limit);
        } else {
            $collection = array_slice($areas, $offset, $size);
        }
        
        return collect($collection);
    }

    public function getByName($name)
    {
        $area = null;
        $areas = $this->getFromFile();
        
        if (array_key_exists($name, $areas)) {
            $area = new Area(
                $name, 
                $areas[$name]['long'], 
                $areas[$name]['lat']
            );
        }
        
        return $area;
    }


    public function getFromFile()
    {
        $areas = @include($this->source_path);
       
        if (empty($areas) && !is_array($areas)) {
            throw new Exception("Areas not found in ".$this->source_path);
        }
        
        return $areas;
    }

    public function sortByDistance(Area $currentArea, Collection $areasCollection, $saveDistance = false)
    {
        return $areasCollection->sortBy(
            function ($area, $name) use($currentArea, $areasCollection, $saveDistance)
            {
                if (empty($area['distance'])) {
                    $tempArea = new Area($name, $area['long'], $area['lat']);
                    $area['distance'] = $currentArea->getDistance($tempArea);
                    if ($saveDistance == true) {
                        $areasCollection->put($name, $area);
                    }
                }
                
                return $area['distance'];
            }
        );
    }

    public function sortByName(Collection $areasCollection)
    {
        return $areasCollection->sortKeys();
    }

    public function search($params) 
    {
        $currentPage = LengthAwarePaginator::resolveCurrentPage();
        $areas = $this->getAll();

        if (empty($params['name'])) {
            $collection = $this->sortByName($areas);
        } else {
            $currentArea = $this->getByName($params['name']);
            if (!empty($currentArea)) {
                $collection = $this->sortByDistance($currentArea, $areas, true);
            } else {
                $collection = $this->sortByName($areas);
            }
        }
        $collection =  $collection->slice($currentPage * $this->getLimit() - $this->getLimit(), $this->getLimit());

        return $collection;
    }
    
    public function getSize() {
        return count($this->getFromFile());
    }

    public function getAll() {
        return $this->get(0, $this->getSize());
    }
}