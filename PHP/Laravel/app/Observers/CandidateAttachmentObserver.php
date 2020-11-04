<?php

namespace App\Observers;

use App\Models\CandidateAttachment;
use App\Services\Helpers\ModelHelperService;

class CandidateAttachmentObserver
{
    private $modelHelperService;

    public function __construct(ModelHelperService $modelHelperService)
    {
        $this->modelHelperService = $modelHelperService;
    }


    /**
     * Handle the candidate attachment "creating" event.
     *
     * @param  \App\CandidateAttachment  $candidateAttachment
     * @return void
     */
    public function creating(CandidateAttachment $candidateAttachment)
    {
        $candidateAttachment->id_pj = $this->modelHelperService->generateHexId();
    }

    /**
     * Handle the candidate attachment "updated" event.
     *
     * @param  \App\CandidateAttachment  $candidateAttachment
     * @return void
     */
    public function updated(CandidateAttachment $candidateAttachment)
    {
        //
    }

    /**
     * Handle the candidate attachment "deleted" event.
     *
     * @param  \App\CandidateAttachment  $candidateAttachment
     * @return void
     */
    public function deleted(CandidateAttachment $candidateAttachment)
    {
        //
    }

    /**
     * Handle the candidate attachment "restored" event.
     *
     * @param  \App\CandidateAttachment  $candidateAttachment
     * @return void
     */
    public function restored(CandidateAttachment $candidateAttachment)
    {
        //
    }

    /**
     * Handle the candidate attachment "force deleted" event.
     *
     * @param  \App\CandidateAttachment  $candidateAttachment
     * @return void
     */
    public function forceDeleted(CandidateAttachment $candidateAttachment)
    {
        //
    }
}
