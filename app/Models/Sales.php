<?php

namespace App\Models;

use Jenssegers\Mongodb\Eloquent\Model;
use App\Models\User;
use App\Models\Vehicle;
use App\Models\Car;
use App\Models\Motorcycle;

class Sales extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'sales';

    protected $fillable = [
        'vehicle_id', 'user_id' ,'vehicle_type', 'region', 'price', 'sales_person'
    ];

    public static function boot()
    {
        parent::boot();

        static::creating( function($model) {
            $model->forceFill(['order_date' => date('Y-m-d H:i:s')]); 
        });

    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function vehicle()
    {
        return $this->hasOne(Vehicle::class); 
    }
}
