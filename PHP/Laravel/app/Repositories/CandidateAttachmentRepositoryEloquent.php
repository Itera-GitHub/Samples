<?php

namespace App\Repositories;

use App\Repositories\FullTextBaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Repositories\CandidateAttachmentRepository;
use App\Models\CandidateAttachment;
use App\Validators\CandidateAttachmentValidator;

/**
 * Class CandidateAttachmentRepositoryEloquent.
 *
 * @package namespace App\Repositories;
 */
class CandidateAttachmentRepositoryEloquent extends FullTextBaseRepository implements CandidateAttachmentRepository
{
    /**
     * Fulltext Index Fields
     * @var array
     */
    protected $fulltextSearchableFields = [
        'resume_pj'
    ];



    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return CandidateAttachment::class;
    }

    /**
    * Specify Validator class name
    *
    * @return mixed
    */
    public function validator()
    {

        return CandidateAttachmentValidator::class;
    }


    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }

}
