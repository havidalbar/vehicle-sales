<?php

namespace App\Models;

use App\Models\Vehicle;

class Car extends Vehicle
{
    protected $connection = 'mongodb';
    protected $collection = 'vehicles';

    public function __construct(array $attributes = [])
    {
        $fillable = ['passenger_capacity', 'type', 'engine'];
        $this->fillable = array_merge($fillable, $this->fillable);
        parent::__construct($attributes);
    }

    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class);
    }
}
