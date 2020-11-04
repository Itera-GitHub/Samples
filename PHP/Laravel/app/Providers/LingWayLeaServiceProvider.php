<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\Parsers\LingWayLeaService;

class LingWayLeaServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(LingWayLeaService::class, function ($app) {
            $api_key =  env('LINGWAY_API_KEY', '');
            $api_base = env('LIGWAY_API_ENDPOINT', 'http://asp.lingway.info/leaws-dispatch/rest/');
            $format = env('LINGWAY_RESPONSE_FORMAT','hrxml');
            $xml_save_path = env('CV_FILES_PARSED_XML_DIR','cv_files/parsed_xml');
            return new LingWayLeaService($api_base,$api_key,$format,$xml_save_path);
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
