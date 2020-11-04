<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;

/**
 * Class Contact.
 *
 * @package namespace App\Models;
 */
class Contact extends Model implements Transformable
{
    use TransformableTrait;

    /**
     * @var string Primary key
     */
    protected $primaryKey = 'id_contact';

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
    protected $table = 'cv_contact';

    /**
     * Hide password and login
     * @var array
     */
    protected $hidden = [
        'login_contact',
        'password_contact'
    ];

    /**
     *  Appends
     */
    protected $appends = ['full_name'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id_contact',
        'login_contact',
        'password_contact',
        'civilite_contact',
        'prenom_contact',
        'nom_contact',
        'fonction_contact',
        'portable_contact',
        'telephone_contact',
        'email_contact',
        'nom_entreprise',
        'adresse_entreprise',
        'ville_entreprise',
        'code_postal_entreprise',
        'activite_entreprise',
        'taille_entreprise',
        'site_web_entreprise',
        'logo_entreprise',
        'categorie_entreprise',
        'telecopie_contact',
        'date_modification_contact',
        'nb_offre_contact',
        'nb_cv_contact',
        'duree_cv_contact',
        'date_debut_acces_cv_contact',
        'statut_contact',
        'filiale_entreprise',
        'id_parent_contact',
        'id_client',
        'id_client2',
        'date_crea_contact',
        'date_modif_contact',
        'id_plateforme',
        'bb_username',
        'bb_userpass'
    ];

    public function getFullNameAttribute()
    {
        return ($this->nom_contact ?? '') . ' ' . $this->prenom_contact ?? '';
    }

}
