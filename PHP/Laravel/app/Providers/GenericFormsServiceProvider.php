<?php

namespace App\Providers;

use App\Services\FormBuilders\GenericFormsService;
use Illuminate\Support\ServiceProvider;

class GenericFormsServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(GenericFormsService::class, function ($app) {
            return new GenericFormsService();
        });
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
