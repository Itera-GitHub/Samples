<?php

namespace App\Transformers;

use App\Services\Helpers\ModelHelperService;
use League\Fractal\TransformerAbstract;
use App\Models\Candidate;

/**
 * Class CandidateTransformer.
 *
 * @package namespace App\Transformers;
 */
class CandidateTransformer extends TransformerAbstract
{

    private $modelHelperService;

    public function __construct()
    {
        $this->modelHelperService = new ModelHelperService();
    }

    /**
     * Transform the Candidate entity.
     *
     * @param \App\Models\Candidate $model
     *
     * @return array
     */
    public function transform(Candidate $model)
    {
        $result = $model->toArray();
        $result['status'] = [
            'id_ref'=>$model->status->id_ref,
            'lib_ref'=>$model->status->lib_ref,
            'id_liste'=>$model->status->id_liste,
            'color'=>$model->status->color,
        ];
        $result['tags'] = $model->tags;
        $result['offer_history'] = $this->modelHelperService->prepareOfferHistory($model);
        $this->modelHelperService->addCandidateFormBlock($result);
        return $result;
    }

}
