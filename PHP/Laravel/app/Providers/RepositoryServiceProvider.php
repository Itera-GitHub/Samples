<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->app->bind(\App\Repositories\CandidateRepository::class, \App\Repositories\CandidateRepositoryEloquent::class);
        $this->app->bind(\App\Repositories\CandidateAttachmentRepository::class, \App\Repositories\CandidateAttachmentRepositoryEloquent::class);
        $this->app->bind(\App\Repositories\TagRepository::class, \App\Repositories\TagRepositoryEloquent::class);
        $this->app->bind(\App\Repositories\CandidateTagRepository::class, \App\Repositories\CandidateTagRepositoryEloquent::class);
        $this->app->bind(\App\Repositories\AdminRefRepository::class, \App\Repositories\AdminRefRepositoryEloquent::class);
        $this->app->bind(\App\Repositories\AdminListRepository::class, \App\Repositories\AdminListRepositoryEloquent::class);
        $this->app->bind(\App\Repositories\AdminListTypeRepository::class, \App\Repositories\AdminListTypeRepositoryEloquent::class);
        $this->app->bind(\App\Repositories\CandidateOfferHistoryRepository::class, \App\Repositories\CandidateOfferHistoryRepositoryEloquent::class);
        $this->app->bind(\App\Repositories\ContactRepository::class, \App\Repositories\ContactRepositoryEloquent::class);
        $this->app->bind(\App\Repositories\OfferRepository::class, \App\Repositories\OfferRepositoryEloquent::class);
        $this->app->bind(\App\Repositories\EnterpriseRepository::class, \App\Repositories\EnterpriseRepositoryEloquent::class);
        $this->app->bind(\App\Repositories\ClientRepository::class, \App\Repositories\ClientRepositoryEloquent::class);
        $this->app->bind(\App\Repositories\EmailTemplateRepository::class, \App\Repositories\EmailTemplateRepositoryEloquent::class);
        $this->app->bind(\App\Repositories\SystemCityRepository::class, \App\Repositories\SystemCityRepositoryEloquent::class);
        //:end-bindings:
    }
}
