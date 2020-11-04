<?php

namespace App\Providers;

use App\Services\Parsers\UiXmlParserService;
use Illuminate\Support\ServiceProvider;

class UiXmlParserServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(UiXmlParserService::class, function ($app) {
            return new UiXmlParserService();
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
