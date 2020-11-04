<?php

namespace App\Validators;

use \Prettus\Validator\Contracts\ValidatorInterface;
use \Prettus\Validator\LaravelValidator;

/**
 * Class CandidateTagValidator.
 *
 * @package namespace App\Validators;
 */
class CandidateTagValidator extends LaravelValidator
{
    const RULE_BULK_CREATE = 'bulk_tag_assign';
    /**
     * Validation Rules
     *
     * @var array
     */
    protected $rules = [
        ValidatorInterface::RULE_CREATE => [],
        ValidatorInterface::RULE_UPDATE => [],
        self::RULE_BULK_CREATE => [
            'candidates'=>'sometimes|required|array',
            'offers'=>'sometimes|required|array',
            'tags'=>'array',
            'candidates.*.id_candidat' => 'sometimes|required|string|exists:cv_candidat,id_candidat',
            'offers.*.id_offre' => 'sometimes|required|string|exists:cv_offre,id_offre',
            'tags.*.id_tag' => 'required|string|exists:cv_tag,id_tag',
        ],
    ];
}
