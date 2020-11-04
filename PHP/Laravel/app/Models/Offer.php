<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;

/**
 * Class Offer.
 *
 * @package namespace App\Models;
 */
class Offer extends Model implements Transformable
{
    use TransformableTrait;

    /**
     * @var string Primary key
     */
    protected $primaryKey = 'id_offre';

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
    protected $table = 'cv_offre';


    protected $appends = ['offer_list_title'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id_offre',
        'id_contact',
        'id_client',
        'id_entreprise',
        'date_depot_offre',
        'reference_offre',
        'intitule_offre',
        'lieu_offre',
        'type_offre',
        'type_contrat_offre',
        'date_debut_offre',
        'description_offre',
        'profil_candidat_offre',
        'nb_consultation_offre',
        'salaire_min_offre',
        'salaire_max_offre',
        'region_offre',
        'niveau_qualification_offre',
        'categorie_titre_offre',
        'pays_offre',
        'date_limit_offre',
        'mission_offre',
        'condition_offre',
        'nb_candidature_offre',
        'nb_suivi_offre',
        'active_offre',
        'date_publi_offre',
        'date_modification_offre',
        'email_offre',
        'mail_reponse_auto',
        'duree_mission',
        'emploi_statut_offre',
        'naf_offre',
        'nb_an_exp_offre',
        'cl_autre_type_contrat',
        'cl_hor_trav_jour',
        'cl_duree_hebdo',
        'cl_evol_possible',
        'cl_motifs',
        'cl_motifs_comm',
        'cl_impulsion',
        'cl_impulsion_comm',
        'cl_modes_action',
        'cl_modes_action_comm',
        'cl_capacite',
        'cl_capacite_comm',
        'cl_role_poste',
        'cl_talent_temperament',
        'cl_fonctionnel',
        'cl_analyste',
        'cl_synthetique',
        'cl_idealiste',
        'cl_bleu',
        'cl_vert',
        'cl_rouge',
        'cl_melancolique',
        'cl_flegmatique',
        'cl_sanguin',
        'cl_colerique',
        'cl_introverti',
        'cl_extraverti',
        'cl_candidat',
        'cl_produire_niveau',
        'cl_produire_temps',
        'cl_produire_importance',
        'cl_produire_desc',
        'cl_vendre_niveau',
        'cl_vendre_temps',
        'cl_vendre_importance',
        'cl_vendre_desc',
        'cl_coop_niveau',
        'cl_coop_temps',
        'cl_coop_importance',
        'cl_coop_desc',
        'cl_ini_niveau',
        'cl_ini_temps',
        'cl_ini_importance',
        'cl_ini_desc',
        'cl_org_niveau',
        'cl_org_temps',
        'cl_org_importance',
        'cl_org_desc',
        'cl_imp_niveau',
        'cl_imp_temps',
        'cl_imp_importance',
        'cl_imp_desc',
        'cl_outils_travail',
        'cl_facteurs_discriminant',
        'cl_metier_offre',
        'cl_statut_offre',
        'cl_nb_poste_ouvert',
        'date_publi_envoi_offre',
        'id_media_offre',
        'date_job_offre',
        'id_job_offre',
        'job_lesjeudi_region',
        'job_lesjeudi_experience',
        'job_lesjeudi_fonctions',
        'job_lesjeudi_contrat',
        'job_lesjeudi',
        'job_cadremploi',
        'job_cadremploi_secteur',
        'job_cadremploi_fonctions',
        'job_cadremploi_dept',
        'job_cadremploi_contrat',
        'job_cadremploi_exp',
        'job_regionsjob',
        'job_monster',
        'cp_offre',
        'ville_offre',
        'commentaire_offre',
        'sta_qtp',
        'id_client2',
        'id_plateforme',
        'in_mosaique',
        'timestamp',
        'timestamp_on_update',
        'sys_langue_offre',
        'sys_publicite_offre',
        'sys_statut_offre',
        'sys_mail_alerte_candidature_offre'
    ];

    public function enterprise()
    {
        return $this->hasOne(Enterprise::class, 'id_entreprise','id_entreprise');
    }

    public function client()
    {
        return $this->hasOne(Client::class, 'id_client','id_client');
    }

    public function getOfferListTitleAttribute()
    {
        return $this->intitule_offre
            . (isset($this->enterprise->nom_entreprise) ? '-' . $this->enterprise->nom_entreprise : '')
            . (isset($this->client->nom_client) ? '-' . $this->client->nom_client : '');
    }

}
