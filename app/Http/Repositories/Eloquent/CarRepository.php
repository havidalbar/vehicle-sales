<?php

namespace App\Http\Repositories\Eloquent;

use App\Models\Car;
use App\Http\Repositories\CarRepositoryInterface;

class CarRepository extends BaseRepository implements CarRepositoryInterface
{
    /**
     * @var Model
     */
    protected $model;

    /**
     * BaseRepository constructor.
     *
     * @param Model $model
     */
    public function __construct(Car $model)
    {
        $this->model = $model;
    }
}