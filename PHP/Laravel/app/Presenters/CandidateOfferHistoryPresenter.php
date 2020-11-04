<?php

namespace App\Presenters;

use App\Transformers\CandidateOfferHistoryTransformer;
use Prettus\Repository\Presenter\FractalPresenter;

/**
 * Class CandidateOfferHistoryPresenter.
 *
 * @package namespace App\Presenters;
 */
class CandidateOfferHistoryPresenter extends FractalPresenter
{
    /**
     * Transformer
     *
     * @return \League\Fractal\TransformerAbstract
     */
    public function getTransformer()
    {
        return new CandidateOfferHistoryTransformer();
    }
}
