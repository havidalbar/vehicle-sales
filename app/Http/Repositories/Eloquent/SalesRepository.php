<?php

namespace App\Http\Repositories\Eloquent;

use App\Models\Sales;
use App\Http\Repositories\SalesRepositoryInterface;

class SalesRepository extends BaseRepository implements SalesRepositoryInterface
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
    public function __construct(Sales $model)
    {
        $this->model = $model;
    }
}