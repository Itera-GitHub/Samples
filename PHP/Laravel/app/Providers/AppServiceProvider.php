<?php

namespace App\Providers;

use App\Models\Candidate;
use App\Models\CandidateAttachment;
use App\Models\EmailTemplate;
use App\Models\Tag;
use App\Observers\CandidateAttachmentObserver;
use App\Observers\CandidateObserver;
use App\Observers\EmailTemplateObserver;
use App\Observers\TagObserver;
use Illuminate\Support\ServiceProvider;
use App\Providers\RepositoryServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        if ($this->app->environment() !== 'production') {
            $this->app->register(\Barryvdh\LaravelIdeHelper\IdeHelperServiceProvider::class);
        }
        $this->app->register(RepositoryServiceProvider::class);
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //boot Observers to set the id_candidat
        Candidate::observe($this->app->make(CandidateObserver::class));
        CandidateAttachment::observe($this->app->make(CandidateAttachmentObserver::class));
        Tag::observe($this->app->make(TagObserver::class));
        EmailTemplate::observe($this->app->make(EmailTemplateObserver::class));
    }
}
