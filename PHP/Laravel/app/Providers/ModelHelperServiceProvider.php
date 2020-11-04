<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\Helpers\ModelHelperService;

class ModelHelperServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(ModelHelperService::class, function ($app) {
            return new ModelHelperService();
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
