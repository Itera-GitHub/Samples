<?php

namespace App\Providers;

use App\Services\Parsers\MyCvConverterService;
use Illuminate\Support\ServiceProvider;

class MyCvConverterServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(MyCvConverterService::class, function ($app) {
            $api_key =  env('MYCV_CONVERTER_API_KEY','');
            $api_base = env('MYCV_CONVERTER_API_ENDPOINT','');
            return new MyCvConverterService($api_base,$api_key,null);
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
