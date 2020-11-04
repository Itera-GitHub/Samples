<?php

namespace App\Presenters;

use App\Transformers\CandidateAttachmentTransformer;
use Prettus\Repository\Presenter\FractalPresenter;

/**
 * Class CandidateAttachmentPresenter.
 *
 * @package namespace App\Presenters;
 */
class CandidateAttachmentPresenter extends FractalPresenter
{
    /**
     * Transformer
     *
     * @return \League\Fractal\TransformerAbstract
     */
    public function getTransformer()
    {
        return new CandidateAttachmentTransformer();
    }
}
