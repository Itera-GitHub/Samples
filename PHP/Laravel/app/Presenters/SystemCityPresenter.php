<?php

namespace App\Presenters;

use App\Transformers\SystemCityTransformer;
use Prettus\Repository\Presenter\FractalPresenter;

/**
 * Class SystemCityPresenter.
 *
 * @package namespace App\Presenters;
 */
class SystemCityPresenter extends FractalPresenter
{
    /**
     * Transformer
     *
     * @return \League\Fractal\TransformerAbstract
     */
    public function getTransformer()
    {
        return new SystemCityTransformer();
    }
}
