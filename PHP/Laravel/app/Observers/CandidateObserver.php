<?php

namespace App\Observers;

use App\Models\Candidate;
use App\Services\Helpers\ModelHelperService;

class CandidateObserver
{
    private $modelHelperService;

    public function __construct(ModelHelperService $modelHelperService)
    {
        $this->modelHelperService = $modelHelperService;
    }

    /**
     * Handle the candidate "creating" event.
     *
     * @param  \App\Candidate  $candidate
     * @return void
     */
    public function creating(Candidate $candidate)
    {
        $candidate->id_candidat = $this->modelHelperService->generateHexId();
    }

    /**
     * Handle the candidate "updated" event.
     *
     * @param  \App\Candidate  $candidate
     * @return void
     */
    public function updated(Candidate $candidate)
    {
        //
    }

    /**
     * Handle the candidate "deleted" event.
     *
     * @param  \App\Candidate  $candidate
     * @return void
     */
    public function deleted(Candidate $candidate)
    {
        //
    }

    /**
     * Handle the candidate "restored" event.
     *
     * @param  \App\Candidate  $candidate
     * @return void
     */
    public function restored(Candidate $candidate)
    {
        //
    }

    /**
     * Handle the candidate "force deleted" event.
     *
     * @param  \App\Candidate  $candidate
     * @return void
     */
    public function forceDeleted(Candidate $candidate)
    {
        //
    }
}
