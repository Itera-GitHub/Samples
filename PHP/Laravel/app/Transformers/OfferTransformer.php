<?php

namespace App\Transformers;

use League\Fractal\TransformerAbstract;
use App\Models\Offer;

/**
 * Class OfferTransformer.
 *
 * @package namespace App\Transformers;
 */
class OfferTransformer extends TransformerAbstract
{
    /**
     * Transform the Offer entity.
     *
     * @param \App\Models\Offer $model
     *
     * @return array
     */
    public function transform(Offer $model)
    {
        $result = $model->toArray();
        $result['entreprise'] =  $model->enterprise;
        $result['client'] = $model->client;
        return $result;
    }
}
