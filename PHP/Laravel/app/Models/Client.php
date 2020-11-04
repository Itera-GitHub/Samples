<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;

/**
 * Class Client.
 *
 * @package namespace App\Models;
 */
class Client extends Model implements Transformable
{
    use TransformableTrait;

    /**
     * @var string Primary key
     */
    protected $primaryKey = 'id_client';

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
    protected $table = 'cv_client';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id_client',
        'id_contact',
        'id_entreprise',
        'login_client',
        'password_client',
        'civilite_client',
        'prenom_client',
        'nom_client',
        'fonction_client',
        'portable_client',
        'telephone_client',
        'telecopie_client',
        'email_client',
        'nom_entreprise_client',
        'adresse_entreprise_client',
        'ville_entreprise_client',
        'code_postal_entreprise_client',
        'activite_entreprise_client',
        'taille_entreprise_client',
        'site_web_entreprise_client',
        'logo_entreprise_client',
        'categorie_entreprise_client',
        'statut_client',
        'date_crea_client',
        'date_maj_client',
        'secteur_client',
        'metier_client',
        'localisation_client',
        'interlocuteur_client',
        'cl_telephone_std_client',
        'cl_nom_secretaire_client',
        'cl_tel_secretaire_client',
        'desc_client',
        'commentaire_client',
        'id_plateforme',
        'dernier_suivi'
    ];

}
