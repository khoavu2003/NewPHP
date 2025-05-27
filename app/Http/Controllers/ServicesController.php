<?php

namespace App\Http\Controllers;

use App\Http\Requests\Services\SearchServicesRequest;
use App\Repositories\Services\ServicesRepository;
use Illuminate\Http\Request;

class ServicesController extends Controller
{
    protected $serviceRepository;
    public function __construct(ServicesRepository $servicesRepository){
        $this->serviceRepository = $servicesRepository;
    }
    public function showServicesManager(){
        return view('services.manager');
    }

    public function searchServices(SearchServicesRequest $request)
    {
        
         $services = $this->serviceRepository->searchServices($request->validated());
         return response()->json([
             'status' => 'success',
             'serviceList' => $services->items(),
             'pagination' => [
                 'current_page' => $services->currentPage(),
                 'last_page' => $services->lastPage(),
                 'per_page' => $services->perPage(),
                 'total' => $services->total(),
             ]
         ]);
    }
}
