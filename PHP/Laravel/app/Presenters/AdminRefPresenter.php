<?php

namespace App\Presenters;

use App\Transformers\AdminRefTransformer;
use Prettus\Repository\Presenter\FractalPresenter;

/**
 * Class AdminRefPresenter.
 *
 * @package namespace App\Presenters;
 */
class AdminRefPresenter extends FractalPresenter
{
    /**
     * Transformer
     *
     * @return \League\Fractal\TransformerAbstract
     */
    public function getTransformer()
    {
        return new AdminRefTransformer();
    }
}
