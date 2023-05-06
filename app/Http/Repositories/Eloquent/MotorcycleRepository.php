<?php

namespace App\Http\Repositories\Eloquent;

use App\Models\Motorcycle;
use App\Http\Repositories\MotorcycleRepositoryInterface;

class MotorcycleRepository extends BaseRepository implements MotorcycleRepositoryInterface
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
    public function __construct(Motorcycle $model)
    {
        $this->model = $model;
    }
}