<?php


namespace App\Services\Helpers;


use App\Models\CandidateAttachment;
use Illuminate\Support\Facades\Storage;
use Spatie\PdfToImage\Pdf;

class ModelHelperService
{

    /**
     * Generate unique ID
     *
     * */
    public function generateHexId ()
    {
        $micro = substr (microtime(), 2, 6) ;
        $concat = time() . $micro ;
        $dec_1 = substr ($concat, 0, 8) ;
        $dec_2 = substr ($concat, 8, 8) ;
        $hex_1 = dechex ($dec_1) ;
        $hex_2 = dechex ($dec_2) ;
        $id = $hex_1 . $hex_2 ;
        return $id;
    }

    /**
     * Prepare candidate model based on Lingway Parsed Data
     *
     * @param $parsedData
     * @return array
     */
    public function prepareCandidateModel ($parsedData)
    {
        $candidateModel = [];
        if($parsedData) {
            $candidateModel['civilite_candidat'] = $parsedData['CandidateProfile']['PersonalData']['PersonDescriptors']['BiologicalDescriptors']['GenderCode'] ?? '';
            $candidateModel['nom_candidat'] = $parsedData['Resume']['StructuredXMLResume']['ContactInfo']['PersonName']['FamilyName'] ?? '';
            $candidateModel['prenom_candidat'] = $parsedData['Resume']['StructuredXMLResume']['ContactInfo']['PersonName']['GivenName'] ?? '';
            $candidateModel['pare_candidat'] = $parsedData['CandidateProfile']['PersonalData']['PersonDescriptors']['BiologicalDescriptors']['Age'] ?? '';
            $candidateModel['desc_mini'] = $parsedData['Resume']['NonXMLResume']['TextResume'] ?? '';
            $candidateHobbie = $parsedData['Resume']['StructuredXMLResume']['ResumeAdditionalItems']['ResumeAdditionalItem']['Description'][0] ?? '';
            $candidateModel['loisir_candidat'] = $candidateHobbie ? $candidateHobbie :' ';
            $qualificationsSummary = is_array($parsedData['Resume']['StructuredXMLResume']['Qualifications']['QualificationSummary']) ?
                null : $parsedData['Resume']['StructuredXMLResume']['Qualifications']['QualificationSummary'];
            $candidateModel['resume_competence'] = $qualificationsSummary ?? '';
            $candidateModel['titre_candidat'] = ($parsedData['Resume']['StructuredXMLResume']['ExecutiveSummary'] ?? '') . ' ' . ($parsedData['UserArea']['lea_LeaExtension']['lea_OverallTitle'] ?? '');
            $candidateModel['poste_recherche_candidat'] = ($parsedData['Resume']['StructuredXMLResume']['ExecutiveSummary'] ?? '') . ' ' . ($parsedData['UserArea']['lea_LeaExtension']['lea_OverallTitle'] ?? '');
            $candidateModel['situation_candidat'] = $parsedData['CandidateProfile']['PersonalData']['PersonDescriptors']['DemographicDescriptors']['MaritalStatus'] ?? '';

            //Contact block parsing
            $contactInfoPrepared = $this->getContactInfo($parsedData);
            $candidateModel['adresse_candidat'] = $contactInfoPrepared['address'] ?? '';
            $candidateModel['code_postal_candidat'] = $contactInfoPrepared['postal_code'] ?? '';
            $candidateModel['ville_candidat'] = $contactInfoPrepared['city'] ?? '';
            $candidateModel['region_candidat'] = $contactInfoPrepared['postal_code'] ?? '';
            $candidateModel['pays_candidat'] = $contactInfoPrepared['country_code'] ?? '';//TODO Ask Philippe on what it should be changed
            $candidateModel['mobile_candidat'] = $contactInfoPrepared['mobile'] ?? '';
            $candidateModel['telephone_candidat'] = $contactInfoPrepared['phone'] ?? '';
            $candidateModel['email_candidat'] = $contactInfoPrepared['email'] ?? '';

            //Experience block parsing
            $experienceInfoParsed = $this->getExperienceInfo($parsedData);
            $candidateModel['nb_annee_experience_candidat'] = $experienceInfoParsed['level'] ? $experienceInfoParsed['level'] : '0';
            $candidateModel['resume_experience'] = $experienceInfoParsed['job_history'] ?? '';
            $candidateModel['resume_formation'] = $experienceInfoParsed['education_history'] ?? '';
        }
        $this->addCandidateFormBlock($candidateModel);
        return $candidateModel;
    }

    public function addCandidateFormBlock(&$candidateModel)
    {
        $candidateModel['form_blocks'] = [
            [
                'title' => 'Identité et coordonnées',
                'fields' => [
                    [
                        'field_title' => 'Nom',
                        'field_type' => 'text',
                        'required' => true,
                        'field_name' => 'nom_candidat',
                        'field_value' => $candidateModel['nom_candidat'] ?? '',
                    ],
                    [
                        'field_title' => 'Prénom',
                        'field_type' => 'text',
                        'required' => true,
                        'field_name' => 'prenom_candidat',
                        'field_value' => $candidateModel['prenom_candidat'] ?? '',
                    ],
                    [
                        'field_title' => 'E-mail',
                        'field_type' => 'text',
                        'required' => true,
                        'field_name' => 'email_candidat',
                        'field_value' => $candidateModel['email_candidat'] ?? '',
                    ],
                    [
                        'field_title' => 'Téléphone',
                        'field_type' => 'text',
                        'field_name' => 'telephone_candidat',
                        'field_value' => $candidateModel['telephone_candidat'] ?? '',
                    ],
                    [
                        'field_title' => 'Tel Mobile',
                        'field_type' => 'text',
                        'field_name' => 'mobile_candidat',
                        'field_value' => $candidateModel['mobile_candidat'] ?? '',
                    ],
                    [
                        'field_title' => 'Adresse',
                        'field_type' => 'wysihtml5',
                        'field_name' => 'adresse_candidat',
                        'field_value' => $candidateModel['adresse_candidat'] ?? '',
                    ],
                    [
                        'field_title' => 'CP',
                        'field_type' => 'text',
                        'field_name' => 'code_postal_candidat',
                        'field_value' => $candidateModel['code_postal_candidat'] ?? '',
                    ],
                    [
                        'field_title' => 'Ville',
                        'field_type' => 'text',
                        'field_name' => 'ville_candidat',
                        'field_value' => $candidateModel['ville_candidat'] ?? '',
                    ],
                ]
            ],
            [
                'title' => 'Curriculum Vitae',
                'fields' => [
                    [
                        'field_title' => 'Titre donné à votre candidature',
                        'field_type' => 'text',
                        'field_name' => 'titre_candidat',
                        'field_value' => $candidateModel['titre_candidat'] ?? ''
                    ],
                    [
                        'field_title' => 'Métier',
                        'field_type' => 'select',
                        'field_name' => 'categorie_titre_candidat',
                        'field_options' =>[
                            ['value' => '', 'text' => '-----------------'],
                            ['value' => 'c04a7364605e',  'text' => 'Achat'],
                            ['value' => 'c04a73118d76e', 'text' => 'Administratif / Financier'],
                            ['value' => 'c04a7354fdd4b', 'text' => 'Bases de données'],
                            ['value' => 'c04a742f51fd2', 'text' => 'CAO-DAO / Dessinateur-Projeteur'],
                            ['value' => 'c04a7436e0edb', 'text' => 'Chimie'],
                            ['value' => 'c04a7450d0058', 'text' => 'Communication / Marketing'],
                            ['value' => 'c04a7552fd59', 'text' => 'Conception / Bureau d\'Etudes'],
                            ['value' => 'c04a7519e33dd', 'text' => 'Décisionnel / Datawarehouse'],
                            ['value' => 'c04a75448c9a8', 'text' => 'Electrique / Electrotechnique'],
                            ['value' => 'c04a755c3458a', 'text' => 'Electronique / Microélectronique'],
                            ['value' => 'c04a76a717c3', 'text' => 'Génie logiciel / Développement'],
                            ['value' => 'c04a76173280b', 'text' => 'Hydraulique'],
                            ['value' => 'c04a76272d82f', 'text' => 'Industrialisation / Méthodes'],
                            ['value' => 'c04a76302874f', 'text' => 'Ingénieurs d\'Affaires'],
                            ['value' => 'c04a763faf433', 'text' => 'Instrumentation / Automatisme'],
                            ['value' => 'c04a764e3e473', 'text' => 'Logistique'],
                            ['value' => 'c04a765a40298', 'text' => 'Maîtrise d\'ouvrage / AMO'],
                            ['value' => 'c04a7722f9ff', 'text' => 'Matériaux'],
                            ['value' => 'c04a77c89287', 'text' => 'Mécanique des fluides'],
                            ['value' => 'c04a7714d86a7', 'text' => 'Mécanique des structures'],
                            ['value' => 'c04a771dc0e56', 'text' => 'Mécatronique'],
                            ['value' => 'c04a772a8f25c', 'text' => 'Optique / Optronique'],
                            ['value' => 'c04a7730576c1', 'text' => 'Qualité'],
                            ['value' => 'c04a7734359b7', 'text' => 'RH'],
                            ['value' => 'c04a7742ef2ab', 'text' => 'Systèmes & réseaux'],
                            ['value' => 'c04a781bee6', 'text' => 'Juridique'],
                        ],
                        'field_value' => ''
                    ],
                    [
                        'field_title' => 'Niveau d\'études',
                        'field_type' => 'select',
                        'field_name' => 'niveau_etudes_candidat',
                        'field_options' =>[
                            ['value' => '', 'text' => '-----------------'],
                            ['value' => 'abea4c59f1535', 'text' => 'CAP / BEP'],
                            ['value' => 'abea4c5e41943', 'text' => 'Bac'],
                            ['value' => 'abea4d2a51cf', 'text' => 'BTS / DUT / Bac+2'],
                            ['value' => 'abea4d65fc9e', 'text' => 'Licence / Maîtrise / Bac + 3 et + 4'],
                            ['value' => 'aec13eb201e8', 'text' => 'DESS / DEA / MASTER / Bac + 5'],
                            ['value' => 'b38b5f4acd063', 'text' => 'Ecole d\'ingénieur'],
                            ['value' => 'b38b5f565b97c', 'text' => 'Ecole de commerce'],
                        ],
                        'field_value' => ''
                    ],
                    [
                        'field_title' => 'Formations/Diplômes',
                        'field_type' => 'wysihtml5',
                        'field_name' => 'resume_formation',
                        'field_value' => $candidateModel['resume_formation'] ?? '',
                    ],
                    [
                        'field_title' => 'Niveau d\'expérience',
                        'field_type' => 'select',
                        'field_name' => 'nb_annee_experience_candidat',
                        'field_options' =>[
                            ['value' => '', 'text' => '-----------------'],
                            ['value' => 'abea4e12769cb', 'text' => 'Débutant (0 à 2 ans d\'exp.)'],
                            ['value' => 'abea4e16413a3', 'text' => 'Intermédiaire (2 à 5 ans d\'exp.)'],
                            ['value' => 'abea4e19f87f8', 'text' => 'Expérimenté (5 à 10 ans d\'exp.)'],
                            ['value' => 'aec144a58592', 'text' => 'Senior (+ de 10 ans d\'exp.)'],
                        ],
                        'field_value' => $candidateModel['nb_annee_experience_candidat'] ?? '',
                    ],
                    [
                        'field_title' => 'Expérience professionnelle',
                        'field_type' => 'wysihtml5',
                        'field_name' => 'resume_experience',
                        'field_value' => $candidateModel['resume_experience'] ?? '',
                    ],
                    [
                        'field_title' => 'Parlez-vous des langues étrangères ? A quel niveau ?',
                        'field_type' => 'wysihtml5',
                        'field_name' => 'resume_langue',
                        'field_value' => '',
                    ],
                    [
                        'field_title' => 'Activités et compétences extra-professionnelles',
                        'field_type' => 'wysihtml5',
                        'field_name' => 'loisir_candidat',
                        'field_value' => $candidateModel['loisir_candidat'] ?? '',
                    ],
                    [
                        'field_title' => 'Résumé du CV',
                        'field_type' => 'wysihtml5',
                        'field_name' => 'desc_mini',
                        'field_value' => $candidateModel['desc_mini'] ?? '',
                    ],
                    [
                        'field_title' => 'Salaire actuel',
                        'field_type' => 'select',
                        'field_name' => 'cl_salaire_souhaite',
                        'field_options' =>[
                            ['value' => '', 'text' => '-----------------'],
                            ['value' => 'c22c853e7c8af', 'text' => 'SMIC'],
                            ['value' => 'c22c85470adb2', 'text' => '20 000'],
                            ['value' => 'c22c854f04782', 'text' => '25 000'],
                            ['value' => 'c22c8556c1e3d', 'text' => '30 000'],
                            ['value' => 'c22c855f47596', 'text' => '35 000'],
                            ['value' => 'c22c868b4d4d', 'text' => '40 000'],
                            ['value' => 'c22c8610eefd7', 'text' => '45 000'],
                            ['value' => 'c22c8619566df', 'text' => '50 000'],
                            ['value' => 'c22c862326d43', 'text' => '55 000'],
                            ['value' => 'c22c862be4023', 'text' => '60 000'],
                            ['value' => 'c22c863424b75', 'text' => '65 000'],
                            ['value' => 'c22c863c5ee0f', 'text' => '70 000'],
                            ['value' => 'c22c8644fe46e', 'text' => '75 000'],
                            ['value' => 'c22c864ecc800', 'text' => '>= 80 000'],
                        ],
                    ]
                ]
            ]
        ];
    }

    /**
     * Parse Contact Info from Lingway API
     * @param $parsedData
     * @return array
     */
    private function getContactInfo($parsedData)
    {
        $contactInfo = [
            'mobile' => '',
            'phone' => '',
            'address' => '',
            'phone' => '',
            'postal_code' => '',
            'city' => '',
            'country_code' => '',
            'email' => '',
        ];
        $contactMethod = $parsedData['Resume']['StructuredXMLResume']['ContactInfo']['ContactMethod'] ?? [];
        if($contactMethod) {
            $this->transformToArray($contactMethod);
            for ($i = 0; $i < count($contactMethod); $i++) {
                $contactInfo['mobile'] .= $contactMethod[$i]['Mobile']['FormattedNumber'] ?? '';
                $contactInfo['phone'] .= $contactMethod[$i]['Telephone']['FormattedNumber'] ?? '';
                $contactInfo['email'] .= $contactMethod[$i]['InternetEmailAddress'] ?? '';
                $contactInfo['country_code'] .= $contactMethod[$i]['PostalAddress']['CountryCode'] ?? '';
                $contactInfo['postal_code'] .= $contactMethod[$i]['PostalAddress']['PostalCode'] ?? '';
                $contactInfo['city'] .= $contactMethod[$i]['PostalAddress']['Municipality'] ?? '';
                $this->transformToArray($contactMethod[$i]['PostalAddress']['DeliveryAddress']);
                for ($i2 = 0; $i2 < count($contactMethod[$i]['PostalAddress']['DeliveryAddress']); $i2++) {
                    $this->transformToArray($contactMethod[$i]['PostalAddress']['DeliveryAddress'][$i2]['AddressLine']);
                    for ($i22 = 0; $i22 < count($contactMethod[$i]['PostalAddress']['DeliveryAddress'][$i2]['AddressLine']); $i22++) {
                        $contactInfo['address'] .= $contactMethod[$i]['PostalAddress']['DeliveryAddress'][$i2]['AddressLine'][$i22] ?? '';
                    }
                }
            }
        }
        return $contactInfo;
    }

    /**
     * Parse Experience Info from Lingway API
     * @param $parsedData
     * @return array
     */
    private function getExperienceInfo($parsedData)
    {
        $experienceInfo = [
            'education_history' => '',
            'job_history' => '',
            'level' => ''
        ];
        //parse education
        $educationHistory = $parsedData['Resume']['StructuredXMLResume']['EducationHistory']['SchoolOrInstitution'] ?? [];
        if($educationHistory) {
           $this->transformToArray($educationHistory);
           foreach ($educationHistory as $key => $history){
              $this->transformToArray($history['Degree']);
              foreach ($history['Degree'] as $key2 => $degree) {
                  $experienceInfo['education_history'] .= ($degree['UserArea']['lea_Description'] ?? '') ."\n";
                  $experienceInfo['level'] .= $degree['UserArea']['lea_LeaExtension']['lea_EmploymentDuration']['lea_Duration'] ?? '';
               }
           }
           unset($history);
        }
        //parse job history
        $jobHistory = $parsedData['Resume']['StructuredXMLResume']['EmploymentHistory'] ?? [];
        if($jobHistory) {
            $this->transformToArray($jobHistory);
            foreach ($jobHistory as $history) {
                $this->transformToArray($history['EmployerOrg']['PositionHistory']);
                foreach ($history['EmployerOrg']['PositionHistory'] as $positionHistory) {
                    $experienceInfo['job_history'] .= ($positionHistory['Description'] ?? '') ."\n";
                }
            }
        }
        return $experienceInfo;
    }

    /**
     * Transform to Array for generic uses
     * @param $item
     */
    private function transformToArray(&$item)
    {
        if(!isset($item[0]) || is_string($item[0])) {
            $item = array ($item);
        }
    }


    public function storeCandidateAttachment($candidate, $fileName)
    {
        $bulkProcessedPath = env('CV_FILES_PARSED_DIR', 'cv_files/parsed');
        $xmlSavePath = env('CV_FILES_PARSED_XML_DIR', 'cv_files/parsed_xml');
        $thumbSavePath = env('CV_FILES_THUMBS_DIR', 'cv_files/parsed_thumbs');
        $explodedFName = explode('.', $fileName);
        $fExt = $explodedFName[1] ?? '';
        $candidateAttachment = new CandidateAttachment();
        $candidateAttachment->id_candidat = $candidate->id_candidat;
        $candidateAttachment->lib_pj = 'Curriculum Vitae';
        $candidateAttachment->save();
        $candidateAttachment->refresh();
        $candidateAttachment->nom_pj = $candidate->id_candidat.'_@@_'.$candidateAttachment->id_pj.'.'.$fExt;
        $candidateAttachment->type_pj = $fExt;
        Storage::disk('local')->rename($bulkProcessedPath . '/' . $fileName,$bulkProcessedPath . '/' . $candidateAttachment->nom_pj);
        if($fExt == 'pdf') {
            $pdf = new Pdf(Storage::disk('local')->path($bulkProcessedPath . '/' . $candidateAttachment->nom_pj));
            if(!Storage::disk('local')->exists($thumbSavePath)) {
                Storage::disk('local')->createDir($thumbSavePath);
            }
            $pdf->saveImage(Storage::disk('local')->path($thumbSavePath .'/'. $candidateAttachment->nom_pj . '.png'));
        }
        if (Storage::disk('local')->exists($xmlSavePath . '/' . $fileName . '.xml')) {
            Storage::disk('local')->rename($xmlSavePath . '/' . $fileName . '.xml',$xmlSavePath . '/' . $candidateAttachment->nom_pj . '.xml');
        }
        $candidateAttachment->save();
        return $candidateAttachment;
    }


    /**
     *  Prepare Model History for Candidates List response
     */
    public function prepareOfferHistory($model)
    {
        $result = [];
        $model_histories = $model->offer_history;
        foreach($model_histories as $model_history){
            $item = $model_history->toArray();
            $item['contact'] = $model_history->contact()->select(['email_contact', 'id_contact', 'nom_contact', 'prenom_contact'])->first();
            $item['last_positioning'] =  $model_history->last_positioning;
            $item['offer'] = $model_history->offer()->with([
                'client' => function($query){
                    $query->select(['id_client','nom_client','prenom_client','fonction_client']);
                },
                'enterprise' => function($query){
                    $query->select(['id_entreprise','nom_entreprise']);
                }
            ])->select(['id_offre','id_contact','id_client','id_entreprise','intitule_offre'])->first();
            array_push($result,$item);
        }
        return $result;
    }

    /**
     * Prepare model from request to bulk Assign Tags to Candidates
     */
    public function prepareBulkCandidatesTagsModel($request)
    {
        $result = false;
        if(count($request['candidates']) && count($request['tags'])) {
            $result = [];
            foreach($request['candidates'] as $candidate){
                foreach ($request['tags'] as $tag){
                    array_push($result,['id_candidat' => $candidate['id_candidat'],'id_tag' => $tag['id_tag']]);
                }
            }
        }
        return $result;
    }

    /**
     * Prepare model from request to bulk Assign Tags to Offers
     */
    public function prepareBulkOffersTagsModel($request)
    {
        $result = false;
        if(count($request['offers']) && count($request['tags'])) {
            $result = [];
            foreach($request['offers'] as $offer){
                foreach ($request['tags'] as $tag){
                    array_push($result,['id_candidat' => $tag['id_tag'].$offer['id_offre'],'id_offre' => $offer['id_offre'],'id_tag' => $tag['id_tag']]);
                }
            }
        }
        return $result;
    }
    /**
     * Prepare model from request to bulk Assign Offers to Candidates
     */
    public function prepareBulkCandidatesOfferHistoryModel($request)
    {
        $result = false;
        if(count($request['candidates']) && count($request['offers'])) {
            $result = [];
            foreach($request['candidates'] as $candidate){
                foreach ($request['tags'] as $tag){
                    array_push($result,['id_candidat' => $candidate['id_candidat'],'id_tag' => $tag['id_tag']]);
                }
            }
        }
        return $result;
    }

}
