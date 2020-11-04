<?php

namespace App\Presenters;

use App\Transformers\AdminListTransformer;
use Prettus\Repository\Presenter\FractalPresenter;

/**
 * Class AdminListPresenter.
 *
 * @package namespace App\Presenters;
 */
class AdminListPresenter extends FractalPresenter
{
    /**
     * Transformer
     *
     * @return \League\Fractal\TransformerAbstract
     */
    public function getTransformer()
    {
        return new AdminListTransformer();
    }
}
