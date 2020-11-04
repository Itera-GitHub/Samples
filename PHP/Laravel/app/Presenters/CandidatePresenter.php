<?php

namespace App\Presenters;

use App\Transformers\CandidateTransformer;
use Prettus\Repository\Presenter\FractalPresenter;

/**
 * Class CandidatePresenter.
 *
 * @package namespace App\Presenters;
 */
class CandidatePresenter extends FractalPresenter
{
    /**
     * Transformer
     *
     * @return \League\Fractal\TransformerAbstract
     */
    public function getTransformer()
    {
        return new CandidateTransformer();
    }
}
