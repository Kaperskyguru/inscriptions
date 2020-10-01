<?php

namespace App\Providers;

use App\Dancer;
use App\Routine;
use App\Observers\DancerObserver;
use App\Observers\RoutineObserver;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
        Dancer::observe(DancerObserver::class);
        
        Routine::observe(RoutineObserver::class);
    }
}
