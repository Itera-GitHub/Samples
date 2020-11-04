<?php

namespace App\Providers;

use App\Services\Helpers\EmailHelperService;
use Illuminate\Support\ServiceProvider;

class EmailHelperServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(EmailHelperService::class, function ($app) {
            return new EmailHelperService();
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
