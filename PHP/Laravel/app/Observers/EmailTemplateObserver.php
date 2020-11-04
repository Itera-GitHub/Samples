<?php

namespace App\Observers;

use App\Models\EmailTemplate;
use App\Services\Helpers\ModelHelperService;

class EmailTemplateObserver
{

    private $modelHelperService;

    public function __construct(ModelHelperService $modelHelperService)
    {
        $this->modelHelperService = $modelHelperService;
    }

    /**
     * Handle the tag "creating" event.
     *
     * @param  \App\Models\EmailTemplate  $template
     * @return void
     */
    public function creating(EmailTemplate $template)
    {
        $template->id_mail = $this->modelHelperService->generateHexId();
    }
//
}
