<?php

namespace App\Validators;

use \Prettus\Validator\Contracts\ValidatorInterface;
use \Prettus\Validator\LaravelValidator;

/**
 * Class CandidateValidator.
 *
 * @package namespace App\Validators;
 */
class CandidateValidator extends LaravelValidator
{
    const RULE_UPLOAD_CV = 'cv_file_upload';
    /**
     * Validation Rules
     *
     * @var array
     */
    protected $rules = [
        ValidatorInterface::RULE_CREATE => [
            'nom_candidat' => 'string|required_without:prenom_candidat,email_candidat',
            'prenom_candidat' => 'string|required_without:nom_candidat,email_candidat',
            'email_candidat' => 'email|required_without:nom_candidat,prenom_candidat|unique:cv_candidat,email_candidat',
        ],
        ValidatorInterface::RULE_UPDATE => [],
        self::RULE_UPLOAD_CV => [
            'cv_file.*' => 'required|mimes:doc,pdf,docx,rtf',
        ],

    ];

    protected $messages = [
        'required' => 'The :attribute field is required.',
        'cv_files.required' => 'A file is required',
        'cv_files.mimes'  => 'The file should be doc,pdf or rtf',
    ];
}
