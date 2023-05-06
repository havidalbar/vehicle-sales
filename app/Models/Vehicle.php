<?php

namespace App\Models;

use Jenssegers\Mongodb\Eloquent\Model;
use App\Models\User;
use App\Models\Car;
use App\Models\Motorcycle;

class Vehicle extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'vehicles';

    protected $fillable = [
        'year_release', 'color', 'price'
    ];

    protected $hidden = [
        'vehicle_type',
    ];

    public static function boot()
    {
        parent::boot();

        static::creating( function($model) {
            $model->forceFill(['vehicle_type' => static::class]); 
        });

    }

    public static function booted()
    {
        static::addGlobalScope(static::class, function($builder) {
            $builder->where('vehicle_type', static::class);
        });
    }

    protected $appends = [
        'remaining_cars_quota',
        'remaining_motorcycle_quota'
    ];

    public function getRemainingCarsQuotaAttribute()
    {
        return Car::where('vehicle_type', Car::class)->count();
    }

    public function getRemainingMotorcycleQuotaAttribute()
    {
        return Motorcycle::where('vehicle_type', Motorcycle::class)->count();
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function cars()
    {
        return $this->hasOne(Car::class); 
    }

    public function motorcycle()
    {
        return $this->hasOne(Motorcycle::class); 
    }

}
