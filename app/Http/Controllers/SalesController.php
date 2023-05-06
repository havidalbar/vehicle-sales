<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Requests\SalesRequest;
use App\Http\Repositories\CarRepositoryInterface;
use App\Http\Repositories\MotorcycleRepositoryInterface;
use App\Http\Repositories\SalesRepositoryInterface;
use App\Http\Repositories\UserRepositoryInterface;
use Illuminate\Support\Arr;
use App\Models\Car;
use App\Models\Motorcycle;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Helpers\CustomResponse;


class SalesController extends Controller
{
    private $salesRepository;
    private $carRepository;
    private $motorcycleRepository;
    private $userRepository;

    public function __construct(SalesRepositoryInterface $salesRepository, 
    CarRepositoryInterface $carRepository, MotorcycleRepositoryInterface $motorcycleRepository,
    UserRepositoryInterface $userRepository)
    {
        $this->salesRepository = $salesRepository;
        $this->carRepository = $carRepository;
        $this->motorcycleRepository = $motorcycleRepository;
        $this->userRepository = $userRepository;
    }

    public function orderVehicle(SalesRequest $request)
    {
        $vehicle = NULL;

        try {
            $vehicle = $this->motorcycleRepository->findById($request->vehicle_id);
        } catch (ModelNotFoundException $ex) {
            $vehicle = $this->carRepository->findById($request->vehicle_id);
        }

        try {
            $user = $this->userRepository->findById($request->user_id);
        } catch (ModelNotFoundException $ex) {
            return response()->json(['error' => 'user_id not found'], 404);
        }
        $data = $request->all();
        $data['vehicle_type'] = $vehicle->vehicle_type;
        
        $sales = $this->salesRepository->create($data);

        return CustomResponse::response($sales);
    }

    public function getAllOrderVehicle()
    {
        $sales = $this->salesRepository->all();
        $total_price = $sales->map->price->sum();
        $group_region = $sales->groupBy('region')->map->count();
        $group_region_price = $sales->groupBy('region')->map(
            function ($item) {
                return $item->sum('price');
            });
        $group_sales_person_price = $sales->groupBy('sales_person')->map(
            function ($item) {
                return $item->count('region');
            });

        $data = (object)[
            'total price' => $total_price,
            'total group region' => $group_region,
            'total price each region' => $group_region_price,
            'total sales of each marketer' => $group_sales_person_price,
            'invoice' => $sales
        ];

        return CustomResponse::response($data);
    }

    public function getOrderVehicleById($id)
    {
        $sales = $this->salesRepository->findById($id);
        return CustomResponse::response($sales);
    }

    public function getVehicleSold()
    {
        $carAll = $this->carRepository->all();
        $motorcycleAll = $this->motorcycleRepository->all();

        $carSold = $this->salesRepository->all()->where('vehicle_type', Car::class);
        $motorcycleSold = $this->salesRepository->all()->where('vehicle_type', Motorcycle::class);

        $mergeDataCar = $carAll->whereIn('_id', Arr::flatten($carSold->map->only('vehicle_id')))->all();
        $mergeDataMotorcycle = $motorcycleAll->whereIn('_id', Arr::flatten($motorcycleSold->map->only('vehicle_id')))->toArray();

        $total_price_car = $carSold->map->price->sum();
        $total_price_motorcycle = $motorcycleSold->map->price->sum();

        $group_region_car = $carSold->groupBy('region')->map->count();
        $group_region_motorcycle = $motorcycleSold->groupBy('region')->map->count();

        $group_region_price_car = $carSold->groupBy('region')->map(
            function ($item) {
                return $item->sum('price');
            });

        $group_region_price_motorcycle = $motorcycleSold->groupBy('region')->map(
            function ($item) {
                return $item->sum('price');
            });

        $group_sales_person_price_car = $carSold->groupBy('sales_person')->map(
            function ($item) {
                return $item->count('region');
            });
        
        $group_sales_person_price_motorcycle= $motorcycleSold->groupBy('sales_person')->map(
            function ($item) {
                return $item->count('region');
            });

        $data = (object)[
            'car' => (object)[
                'car sold' => count($carSold->toArray()),
                'total price' => $total_price_car,
                'total each region' => $group_region_car,
                'total price each region' => $group_region_price_car,
                'total sales of each marketer' => $group_sales_person_price_car,
                'invoice' => $mergeDataCar,
            ],
            'motorcycle' => (object)[
                'motorcycle sold' => count($motorcycleSold->toArray()),
                'total price' => $total_price_motorcycle,
                'total each region' => $group_region_motorcycle,
                'total price each region' => $group_region_price_motorcycle,
                'total sales of each marketer' => $group_sales_person_price_motorcycle,
                'invoice' => $mergeDataMotorcycle,
            ],
        ];

        return CustomResponse::response($data);
    }


}
