<?php

namespace App\Observers;

use App\Models\Tag;
use App\Services\Helpers\ModelHelperService;

class TagObserver
{
    private $modelHelperService;

    public function __construct(ModelHelperService $modelHelperService)
    {
        $this->modelHelperService = $modelHelperService;
    }

    /**
     * Handle the tag "creating" event.
     *
     * @param  \App\Models\Tag  $tag
     * @return void
     */
    public function creating(Tag $tag)
    {
        $tag->id_tag = $this->modelHelperService->generateHexId();
    }
}
