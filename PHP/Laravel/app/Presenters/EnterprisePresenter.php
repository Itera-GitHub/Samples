<?php

namespace App\Presenters;

use App\Transformers\EnterpriseTransformer;
use Prettus\Repository\Presenter\FractalPresenter;

/**
 * Class EnterprisePresenter.
 *
 * @package namespace App\Presenters;
 */
class EnterprisePresenter extends FractalPresenter
{
    /**
     * Transformer
     *
     * @return \League\Fractal\TransformerAbstract
     */
    public function getTransformer()
    {
        return new EnterpriseTransformer();
    }
}
