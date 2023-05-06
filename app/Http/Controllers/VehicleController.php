<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Repositories\CarRepositoryInterface;
use App\Http\Repositories\MotorcycleRepositoryInterface;
use App\Http\Repositories\SalesRepositoryInterface;
use App\Models\Car;
use App\Models\Motorcycle;
use App\Http\Requests\VehicleRequest;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Arr;
use App\Helpers\CustomResponse;

class VehicleController extends Controller
{
    private $carRepository;
    private $motorcycleRepository;
    private $salesRepository;

    public function __construct(CarRepositoryInterface $carRepository, 
    MotorcycleRepositoryInterface $motorcycleRepository, SalesRepositoryInterface $salesRepository)
    {
        $this->carRepository = $carRepository;
        $this->motorcycleRepository = $motorcycleRepository;
        $this->salesRepository = $salesRepository;
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
        return CustomResponse::response($vehicle);
    }

    public function getAllQuotaVehicle() 
    {
        $car = $this->carRepository->all()->toArray();
        $motorcycle = $this->motorcycleRepository->all()->toArray();

        $data = (object)[
            'car' => (object)[
                'all quota' => count($car),
                'data' => $car,
            ],
            'motorcycle' => (object)[
                'all quota' => count($motorcycle),
                'data' => $motorcycle,
            ],
        ];

        return CustomResponse::response($data);
    }
    
    public function getLeftoverQuotaVehicle() 
    {
        $carAll = $this->carRepository->all();
        $motorcycleAll = $this->motorcycleRepository->all();

        $carSold = $this->salesRepository->all()->where('vehicle_type', Car::class);
        $motorcycleSold = $this->salesRepository->all()->where('vehicle_type', Motorcycle::class);

        $dataCarLeftover = $carAll->whereNotIn('_id', Arr::flatten($carSold->map->only('vehicle_id')));
        $dataMotorcycleLeftover = $motorcycleAll->whereNotIn('_id', Arr::flatten($motorcycleSold->map->only('vehicle_id')));

        $data = (object)[
            'car' => (object)[
                'leftover quota' => count($carAll->toArray()) - count($carSold->toArray()),
                'data' => $dataCarLeftover,
            ],
            'motorcycle' => (object)[
                'leftover quota' => count($motorcycleAll->toArray()) - count($motorcycleSold->toArray()),
                'data' => $dataMotorcycleLeftover,
            ],
        ];

        return CustomResponse::response($data);
    }

    public function getVehicleById($id)
    {
        $vehicle = NULL;
        
        try {
            $vehicle = $this->motorcycleRepository->findById($id);
        } catch (ModelNotFoundException $ex) {
            $vehicle = $this->carRepository->findById($id);
        }

        return CustomResponse::response($vehicle);
    }

}
