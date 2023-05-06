<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Http\Repositories\Eloquent\BaseRepository;
use App\Http\Repositories\Eloquent\UserRepository;
use App\Http\Repositories\Eloquent\CarRepository;
use App\Http\Repositories\Eloquent\MotorcycleRepository;

use App\Http\Repositories\EloquentRepositoryInterface;
use App\Http\Repositories\UserRepositoryInterface;
use App\Http\Repositories\CarRepositoryInterface;
use App\Http\Repositories\MotorcycleRepositoryInterface;


class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(EloquentRepositoryInterface::class, BaseRepository::class);
        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);
        $this->app->bind(CarRepositoryInterface::class, CarRepository::class);
        $this->app->bind(MotorcycleRepositoryInterface::class, MotorcycleRepository::class);


    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
