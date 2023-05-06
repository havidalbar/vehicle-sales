<?php

namespace App\Models;

use App\Models\Vehicle;

class Motorcycle extends Vehicle
{
    protected $connection = 'mongodb';
    protected $collection = 'vehicles';

    public function __construct(array $attributes = [])
    {
        $fillable = ['suspension_type', 'transmissi_type', 'engine'];
        $this->fillable = array_merge($fillable, $this->fillable);
        parent::__construct($attributes);
    }

    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class);
    }
}
