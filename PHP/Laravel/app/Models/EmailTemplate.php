<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;

/**
 * Class EmailTemplate.
 *
 * @package namespace App\Models;
 */
class EmailTemplate extends Model implements Transformable
{
    use TransformableTrait;

    /**
     * @var string Primary key
     */
    protected $primaryKey = 'id_mail';

    /**
     * @var bool disable autoincrement
     */
    public $incrementing = false;

    /**
     * @var bool disable timestamps
     */
    public $timestamps = false;

    /**
     * The table name for model
     *
     * @var string
     */
    protected $table = 'cv_mail';


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id_mail',
        'sujet_mail',
        'message_mail',
        'pos_mail',
        'mail_auto',
        'mail_auto_suivi',
        'mail_candidat',
        'mail_contact',
        'id_plateforme',
        'alerte_mail_inscription_candidat',
        'alerte_mail_candidature_candidat',
        'alerte_mail_inscription_candidat_where',
        'alerte_mail_candidature_candidat_where',
        'libelle_mail',
        'mail_client',
        'mail_contact_offre',
        'mail_generique'
    ];

}
