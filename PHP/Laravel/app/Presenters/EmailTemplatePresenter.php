<?php

namespace App\Presenters;

use App\Transformers\EmailTemplateTransformer;
use Prettus\Repository\Presenter\FractalPresenter;

/**
 * Class EmailTemplatePresenter.
 *
 * @package namespace App\Presenters;
 */
class EmailTemplatePresenter extends FractalPresenter
{
    /**
     * Transformer
     *
     * @return \League\Fractal\TransformerAbstract
     */
    public function getTransformer()
    {
        return new EmailTemplateTransformer();
    }
}
