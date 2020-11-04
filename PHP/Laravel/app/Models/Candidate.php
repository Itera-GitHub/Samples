<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;

/**
 * Class Candidate.
 *
 * @package namespace App\Models;
 */
class Candidate extends Model implements Transformable
{
    use TransformableTrait;

    /**
     * time stamps fields
     */
    const CREATED_AT = 'date_creation_candidat';
    const UPDATED_AT = 'date_modification_candidat';

    /**
     * @var string Primary key
     */
    protected $primaryKey = 'id_candidat';

    /**
     * @var bool disable autoincrement
     */
    public $incrementing = false;

    protected $hidden = [
       'login_candidat',
       'password_candidat'
    ];

    protected $appends = ['created_at_humans','full_name','score','selected','expanded'];

    /**
     * The table name for model
     *
     * @var string
     */
    protected $table = 'cv_candidat';


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id_candidat',
        'id_client',
        'id_client2',
        'id_contact',
        'nb_annee_experience_candidat',
        'date_creation_candidat',
        'date_modification_candidat',
        'heure_modification_candidat',
        'civilite_candidat',
        'genre',
        'niveau_etudes_candidat',
        'password_candidat',
        'login_candidat',
        'loisir_candidat',
        'objectif_candidat',
        'date_disponibilite_candidat',
        'permis_voiture_candidat',
        'situation_candidat',
        'date_naissance_candidat',
        'nom_candidat',
        'prenom_candidat',
        'adresse_candidat',
        'code_postal_candidat',
        'ville_candidat',
        'email_candidat',
        'telephone_candidat',
        'site_web_candidat',
        'telecopie_candidat',
        'titre_candidat',
        'mobile_candidat',
        'nb_consultation_candidat',
        'actif_candidat',
        'photo_candidat',
        'categorie_titre_candidat',
        'region_candidat',
        'pays_candidat',
        'cacher_infos_perso_candidat',
        'nb_etoile_candidat',
        'cv_candidat',
        'competence_resume_candidat',
        'resume_competence',
        'resume_formation',
        'resume_formation2',
        'resume_experience',
        'resume_langue',
        'dernier_diplome_candidat',
        'dernier_etablissement_candidat',
        'id_list',
        'id_declinaison',
        'titre_mini',
        'desc_mini',
        'nationalite_candidat',
        'langue1_candidat',
        'langue2_candidat',
        'langue3_candidat',
        'niveau_langue1_candidat',
        'niveau_langue2_candidat',
        'niveau_langue3_candidat',
        'pays_actuel',
        'region_recherche',
        'evaluation_candidat',
        'sexe_candidat',
        'nb_enfant_candidat',
        'nb_personne_charge',
        'type_permis_candidat',
        'vehicule_candidat',
        'formation_complentaire_candidat',
        'desc_formation_complementaire_candidat',
        'informatique_candidat',
        'anciennete_entreprise_candidat',
        'anciennete_poste_candidat',
        'projet_candidat',
        'des_projet_candidat',
        'poste_recherche_candidat',
        'secteur_activite_candidat',
        'code_naf_candidat',
        'nb_encadre_candidat',
        'type_contrat_candidat',
        'cycle_travail_candidat',
        'type_emploi_candidat',
        'remuneration_candidat',
        'mobilite_candidat',
        'preavis_candidat',
        'preavis_effectue_candidat',
        'convention_reclassement_candidat',
        'conge_reclassement_candidat',
        'date_debut_reclassement_candidat',
        'date_fin_reclassement_candidat',
        'pare_candidat',
        'identifiant_anpe_candidat',
        'demandeur_emploi_candidat',
        'des_action_anpe_candidat',
        'pre_retraite_candidat',
        'statut_particulier_candidat',
        'solution_candidat',
        'date_solution_candidat',
        'cl_adresse_candidat2',
        'cl_adresse_candidat3',
        'cl_lieu_rdv',
        'cl_autre_telephone_candidat',
        'cl_telephone_travail_candidat',
        'cl_orientation',
        'cl_orga_prescripteur',
        'cl_ref_prescription',
        'cl_fiche_relais',
        'cl_pre_contact_tel',
        'cl_entretien_pres',
        'cl_suite_donnee',
        'cl_avis_accueil',
        'cl_retour_prescripteur',
        'cl_demarrage',
        'cl_action_tr',
        'cl_group_accueil',
        'cl_sgroup_accueil',
        'cl_type_contact',
        'cl_id_etat',
        'cl_poste_occupe',
        'cl_reactivite_action',
        'cl_questionnement',
        'cl_risque',
        'cl_phasing',
        'cl_manques',
        'cl_lien_emploi',
        'cl_autono_recherche',
        'cl_emploi_situation_vie',
        'cl_cores_potentiel',
        'cl_solidite_motivation',
        'cl_potentiel_affirme',
        'cl_situation_vie_rech_trav',
        'cl_potentiel_tm',
        'cl_motivation_travail',
        'cl_relation_travail',
        'cl_situation_vie_travail',
        'cl_test_vert',
        'cl_test_rouge',
        'cl_test_bleu',
        'cl_test_cons_chercher',
        'cl_test_cons_soin',
        'cl_test_cons_presenter',
        'cl_test_cons_initiative',
        'cl_cap_ini_da',
        'cl_cap_coo_da',
        'cl_cap_imp_da',
        'cl_cap_org_da',
        'cl_cap_vend_da',
        'cl_cap_prod_da',
        'cl_cap_ini_txt',
        'cl_cap_vend_txt',
        'cl_cap_imp_txt',
        'cl_cap_coo_txt',
        'cl_cap_org_txt',
        'cl_cap_prod_txt',
        'cl_con_indication',
        'cl_motif_app_contrib',
        'cl_motif_util_aide',
        'cl_ent_passion',
        'cl_motif_challenge_rp',
        'cl_envi_pro_env',
        'cl_fonc_role_env',
        'cl_motif',
        'cl_qualite',
        'cl_att_emploi_exp',
        'cl_qualite_pepite',
        'cl_argu_cle_reu',
        'cl_descrimination',
        'cl_client_emploi1',
        'cl_client_levier_emploi1',
        'cl_client_frein_emploi1',
        'cl_client_emploi2',
        'cl_client_levier_emplo2',
        'cl_client_frein_emploi2',
        'cl_client_emploi3',
        'cl_client_levier_emploi3',
        'cl_client_frein_emploi3',
        'cl_trans_mobilie',
        'cl_cond_trav_fav',
        'cl_poste_occ_societe',
        'cl_poste_occ_contrat',
        'cl_poste_occ_duree',
        'cl_poste_occ_date_fin_cont',
        'cl_partenaire_orientation',
        'cl_partenaire_retour',
        'cl_partenaire_rdv',
        'cl_partenaire_dem',
        'cl_partenaire_contact',
        'cl_partenaire_desc',
        'cl_declic',
        'cl_type_conf_emploi',
        'cl_matching',
        'cl_mode_acompagnement',
        'cl_focal_coach',
        'cl_vigilance',
        'cl_tendance',
        'cl_envie',
        'cl_ressentis',
        'cl_focale_placement',
        'cl_num_ident1',
        'cl_num_ident2',
        'cl_facilite_contact',
        'cl_ref_coaching',
        'cl_date_ret_presc',
        'cl_date_orien_presc',
        'cl_reval_qtp',
        'cl_predisposition',
        'cl_posture_accomp',
        'cl_rythme_accom',
        'cl_degre_appro_qtp',
        'cl_risque_no_apport',
        'cl_date_fin_accomp',
        'cl_salaire_souhaite',
        'cl_competence_clef',
        'cl_eval_globale',
        'cl_mobilite_verif',
        'cl_dispo_verif',
        'cl_souhait_exprim',
        'cl_competence_technique',
        'cl_auto_dyna',
        'cl_communication',
        'cl_mobilite_intelec',
        'cl_integration',
        'cl_salaire_proposer',
        'cl_contexte_familiale',
        'cl_commentaire',
        'cl_commentaire_exp',
        'cl_ref1_soc',
        'cl_ref1_nom',
        'cl_ref1_depart',
        'cl_ref2_soc',
        'cl_ref2_nom',
        'cl_ref2_depart',
        'cl_ref3_soc',
        'cl_ref3_nom',
        'cl_ref3_depart',
        'cl_decompo_salaire',
        'cl_piste1_societe',
        'cl_piste2_societe',
        'cl_piste3_societe',
        'cl_piste1_avancement',
        'cl_piste2_avancement',
        'cl_piste3_avancement',
        'cl_piste1_bilan',
        'cl_piste2_bilan',
        'cl_piste3_bilan',
        'cl_nature_org_presc',
        'img_candidat',
        'cl_acc_prolonge',
        'cl_date_lieu_rdv',
        'cl_commentaire_tel',
        'cl_motif2',
        'cl_avis_cand_acc',
        'cl_proposition_affec',
        'cl_question_j1',
        'cl_objectif_presc',
        'cl_qualite_bvr',
        'cl_qualite_cppd',
        'cl_question_j2',
        'cl_releve_decision',
        'cl_partenaire_accomp',
        'cl_question_ref',
        'cl_type_pros',
        'cl_piste1_decla',
        'cl_apprecia_match1',
        'cl_piste2_decla',
        'cl_apprecia_match2',
        'cl_degre_qtp2',
        'resume_precision_motivation',
        'cl_piste3_decla',
        'cl_apprecia_match3',
        'cl_degre_qtp3',
        'cl_mode_trans',
        'cl_aire_mobil',
        'cl_comment_mob_permis',
        'cl_signature_plus_tot',
        'cl_origine',
        'cl_exp_ref',
        'cl_synt_qtp',
        'possede_cv',
        'sta_qtp',
        'envoyer_sqtp',
        'tricona_bdd_candidat',
        'timestamp',
        'id_plateforme',
        'in_mosaique',
        'dernier_suivi_candidat',
        'dernier_suivi_prestation',
        'parent_titre_candidat',
        'modifier_par',
        'dernier_employeur',
        'rs_viadeo',
        'rs_facebook',
        'rs_linkedln',
        'id_import_csv',
        'sys_blocage_action',
        'sys_blocage_id_plateforme_orig',
        'sys_blocage_id_plateforme_dest',
        'sys_blocage_date_debut',
        'sys_blocage_date_fin'
    ];

    /**
     * One to many attachments relation
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function attachments() {
        return $this->hasMany(CandidateAttachment::class, 'id_candidat','id_candidat');
    }

    /**
     * ManyToMany Relations for candidates tags
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function tags() {
        return $this->belongsToMany(Tag::class,'cv_tag_candidat','id_candidat','id_tag');
    }

    /**
     * One to One Relations for candidates status
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function status() {
        return $this->hasOne(AdminRef::class,'id_ref','actif_candidat');
    }

    /**
     * One to One Relations for candidates System City
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function system_city() {
        return $this->hasOne(SystemCity::class,'cpostal','code_postal_candidat');
    }

    /**
     * One to Many Relations for candidates offers
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function offer_history() {
        return $this->hasMany(CandidateOfferHistory::class,'id_candidat','id_candidat');
    }

    /**
     * One to Many Relations for candidates offers
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function offers() {
        return $this->offer_history();
    }



    /**
     * Humans created at attribute
     * @return string
     */
    public function getCreatedAtHumansAttribute()
    {
        if(isset($this->date_creation_candidat)) {
            Carbon::setLocale('fr');
            return $this->date_creation_candidat->diffForHumans();
        }
        return '';

    }

    public function getFullNameAttribute()
    {
        return ($this->prenom_candidat ?? '') . ' ' . $this->nom_candidat ?? '';
    }

    //TODO change to the API calculation when info will be provided by Philippe
    public function getScoreAttribute()
    {
        return rand(1,100);
    }

    public function getExpandedAttribute()
    {
        return false;
    }

    public function getSelectedAttribute()
    {
        return false;
    }

}
