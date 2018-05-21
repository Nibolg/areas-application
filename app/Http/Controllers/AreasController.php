<?php

namespace App\Http\Controllers;

use Illuminate\Pagination\LengthAwarePaginator;
use App\Repositories\AreaRepository;
use Illuminate\Http\Request;


class AreasController extends Controller
{
    protected $areaRepository;
    
    public function __construct(AreaRepository $areaRepository)
    {
        $this->areaRepository = $areaRepository;
    }

    public function index(Request $request)
    {
        $this->areaRepository->setLimit(25);
        $sortedAreas = $this->areaRepository->search($request->all());
        $path = $request->Url();
        
        if (!empty($request->input('name'))) {
            $path .= "?name=".$request->input('name');
        }
        
        $sortedAreas = new LengthAwarePaginator(
            $sortedAreas, 
            $this->areaRepository->getSize(), 
            $this->areaRepository->getLimit()
        );
        
        $sortedAreas->setPath($path);
        $areas  = $this->areaRepository->getFromFile();
        $size = count($areas);

        return view('areas', [
            'sortedAreas' => $sortedAreas,
            'size' => $size,
            'areas' => $areas
        ]);
    }
}