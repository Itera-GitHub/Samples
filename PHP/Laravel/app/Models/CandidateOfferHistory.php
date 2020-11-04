<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;

/**
 * Class CandidateOfferHistory.
 *
 * @package namespace App\Models;
 */
class CandidateOfferHistory extends Model implements Transformable
{
    use TransformableTrait;

    /**
     * @var string Primary key
     */
    protected $primaryKey = 'id_candidature';

    /**
     * @var bool disable autoincrement
     */
    public $incrementing = false;

    /**
     * The table name for model
     *
     * @var string
     */

    /**
     * @var bool disable timestamps
     */
    public $timestamps = false;

    /**
     * The table name for model
     *
     * @var string
     */
    protected $table = 'cv_candidature';


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id_candidature',
        'nom_candidature',
        'id_candidat',
        'id_contact',
        'date_candidature',
        'id_offre',
        'suivi_candidature',
        'media_candidature',
        'timestamp',
        'id_plateforme',
        'dernier_suivi_posi',
        'origine_candidature',
        'campaign_id',
        'der_date_suivi',
        'der_heure_suivi',
        'der_description_action_suivi',
        'der_bilan_suivi',
        'der_id_contact_suivi',
        'line'
    ];

    public function contact()
    {
        return $this->hasOne(Contact::class,'id_contact','id_contact');
    }

    public function candidate()
    {
        return $this->belongsTo(Candidate::class,'id_candidat','id_candidat','id_offre');
    }

    public function offer()
    {
        return $this->hasOne(Offer::class, 'id_offre', 'id_offre');
    }

    public function last_positioning()
    {
        return $this->hasOne(AdminRef::class,'id_ref','dernier_suivi_posi');
    }

}
