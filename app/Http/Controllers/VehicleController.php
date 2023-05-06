<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Repositories\CarRepositoryInterface;
use App\Http\Repositories\MotorcycleRepositoryInterface;
use App\Http\Requests\VehicleRequest;

class VehicleController extends Controller
{
    private $carRepository;
    private $motorcycleRepository;

    public function __construct(CarRepositoryInterface $carRepository, MotorcycleRepositoryInterface $motorcycleRepository)
    {
        $this->carRepository = $carRepository;
        $this->motorcycleRepository = $motorcycleRepository;
    }

    public function registerVehicle(VehicleRequest $request)
    {
        $vehicle = NULL;
        switch (strtolower($request->vehicle_type)) {
            case "car":
                $vehicle = $this->carRepository->create($request->all());
                break;
            case "motorcycle":
                $vehicle = $this->motorcycleRepository->create($request->all());
                break;
        }
        return response()->json([
            'success' => true,
            'data' => $vehicle
        ], Response::HTTP_OK);
    }

    public function getQuotaVehicle() 
    {
        $car = $this->carRepository->all()->toArray();
        $motorcycle = $this->motorcycleRepository->all()->toArray();

        $data = (object)[
            'car' => (object)[
                'quota' => count($this->carRepository->all()->toArray()),
                'data' => $car,
            ],
            'motorcycle' => (object)[
                'quota' => count($this->motorcycleRepository->all()->toArray()),
                'data' => $motorcycle,
            ],
        ];

        return response()->json([
            'success' => true,
            'data' => $data
        ], Response::HTTP_OK);
    }
}
