<?php

namespace App\Repositories;

use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Repositories\CandidateRepository;
use App\Models\Candidate;
use App\Validators\CandidateValidator;

/**
 * Class CandidateRepositoryEloquent.
 *
 * @package namespace App\Repositories;
 */
class CandidateRepositoryEloquent extends FullTextBaseRepository implements CandidateRepository
{
    /**
     * Fulltext Index Fields
     * @var array
     */
    protected $fulltextSearchableFields = [
        'nom_candidat',
        'prenom_candidat',
        'adresse_candidat',
        'code_postal_candidat',
        ' loisir_candidat',
        'ville_candidat',
        ' titre_candidat',
        ' region_candidat',
        'resume_competence',
        'resume_formation',
        'resume_experience',
        'resume_langue',
        'titre_mini',
        'desc_mini',
        'poste_recherche_candidat'
    ];

    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return Candidate::class;
    }

    /**
    * Specify Validator class name
    *
    * @return mixed
    */
    public function validator()
    {

        return CandidateValidator::class;
    }


    /**
     * Boot up the repository',' pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }

}
