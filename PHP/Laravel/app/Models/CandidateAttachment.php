<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;

/**
 * Class CandidateAttachment.
 *
 * @package namespace App\Models;
 */
class CandidateAttachment extends Model implements Transformable
{
    use TransformableTrait;

    const CREATED_AT = 'date_crea_pj';
    const UPDATED_AT = 'date_modif_pj';

    protected $primaryKey = 'id_pj';

    public $incrementing = false;


    protected $attributes = [
       'id_entreprise' => '',
       'id_plateforme' => '',
       'type_doc' => 0,
       'in_mosaique' => 0,
       'origin_url' => '',
       'origin_md5' => '',
       'resume_pj' => ''
    ];

    /**
     * The table name for model
     *
     * @var string
     */
    protected $table = 'cv_pj';

    protected $appends = ['thumbnail'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id_pj',
        'id_candidat',
        'id_offre',
        'id_entreprise',
        'nom_pj',
        'type_pj',
        'id_contact',
        'id_admin',
        'date_crea_pj',
        'date_modif_pj',
        'lib_pj',
        'id_tache',
        'id_prestation',
        'id_plateforme',
        'type_doc',
        'in_mosaique',
        'origin_url',
        'origin_md5',
        'resume_pj'
    ];

    public function candidate()
    {
        return $this->belongsTo(Candidate::class,'id_candidat','id_candidat');
    }

    public function getThumbnailAttribute()
    {

    }

}
