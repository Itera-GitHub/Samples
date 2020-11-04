<?php

namespace App\Validators;

use \Prettus\Validator\Contracts\ValidatorInterface;
use \Prettus\Validator\LaravelValidator;

/**
 * Class CandidateOfferHistoryValidator.
 *
 * @package namespace App\Validators;
 */
class CandidateOfferHistoryValidator extends LaravelValidator
{
    /**
     * Validation Rules
     *
     * @var array
     */
    protected $rules = [
        ValidatorInterface::RULE_CREATE => [
            'candidates' => 'required|array',
            'offers' => 'required|array',
            'candidates.*.id_candidat' => 'exists:cv_candidat,id_candidat',
            'offers.*.id_offre' => 'exists:cv_offre,id_offre',
        ],
        ValidatorInterface::RULE_UPDATE => [],
    ];
}
