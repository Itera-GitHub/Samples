<?php

namespace App\Transformers;

use League\Fractal\TransformerAbstract;
use App\Models\EmailTemplate;

/**
 * Class EmailTemplateTransformer.
 *
 * @package namespace App\Transformers;
 */
class EmailTemplateTransformer extends TransformerAbstract
{
    /**
     * Transform the EmailTemplate entity.
     *
     * @param \App\Models\EmailTemplate $model
     *
     * @return array
     */
    public function transform(EmailTemplate $model)
    {
        return [
            'id_mail' => $model->id_mail,
            'sujet_mail' => $model->sujet_mail, // Subject
            'message_mail' => $model->message_mail,
            'pos_mail' => $model->pos_mail,
            'libelle_mail' => $model->libelle_mail, //Label
        ];
    }
}
