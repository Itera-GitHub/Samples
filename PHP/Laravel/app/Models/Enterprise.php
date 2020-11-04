<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;

/**
 * Class Enterprise.
 *
 * @package namespace App\Models;
 */
class Enterprise extends Model implements Transformable
{
    use TransformableTrait;

    /**
     * @var string Primary key
     */
    protected $primaryKey = 'id_enterprise';

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
    protected $table = 'cv_entreprise';


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id_entreprise',
        'id_contact',
        'date_crea_entreprise',
        'date_maj_entreprise',
        'id_par_entreprise',
        'nom_entreprise',
        'adresse_entreprise',
        'ville_entreprise',
        'code_postal_entreprise',
        'telephone_entreprise',
        'telecopie_entreprise',
        'email_entreprise',
        'adresse2_entreprise',
        'ville2_entreprise',
        'code_postal2_entreprise',
        'telephone2_entreprise',
        'telecopie2_entreprise',
        'email2_entreprise',
        'adresse3_entreprise',
        'ville3_entreprise',
        'code_postal3_entreprise',
        'telephone3_entreprise',
        'telecopie3_entreprise',
        'email3_entreprise',
        'adresse4_entreprise',
        'ville4_entreprise',
        'code_postal4_entreprise',
        'telephone4_entreprise',
        'telecopie4_entreprise',
        'email4_entreprise',
        'activite_entreprise',
        'taille_entreprise',
        'site_web_entreprise',
        'logo_entreprise',
        'categorie_entreprise',
        'cl_valeur_entreprise',
        'cl_autre_activite_entreprise',
        'cl_type_enteprise',
        'localisation_entreprise',
        'potentiel_entreprise',
        'login_entreprise',
        'passe_entreprise',
        'id_plateforme'
    ];

}
