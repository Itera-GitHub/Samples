<?php


namespace App\Services\Helpers;


use App\Mail\EmailQueuing;
use App\Repositories\CandidateRepository;
use App\Repositories\ContactRepository;
use App\Repositories\OfferRepository;
use Illuminate\Support\Facades\Storage;

class EmailHelperService
{
    public function prepareCandidatesContactEmailDetails($request, ModelHelperService $modelHelperService, CandidateRepository $candidateRepository, ContactRepository $contactRepository, OfferRepository $offerRepository)
    {
        $details = [];
        $request['candidates'] = json_decode($request['candidates'], true);
        $request['current_user'] = json_decode($request['current_user'], true);

        //TODO Change this to the current loggedin user after login will be implemented
        $curr_contact = [];
        if(isset($request['current_user']['id_contact']) && $request['current_user']['id_contact']){
            $curr_contact = $contactRepository->findWhere(['id_contact' => $request['current_user']['id_contact']])->first();
        }
        $offer = [];
        if(isset($request['offer_id']) && $request['offer_id']){
            $offer = $offerRepository->findWhere(['id_offre' => $request['offer_id']])->first();
        }
        $attachments = [];
        if(isset($request['attachment'])){
            foreach ($request['attachment'] as $attachment){
                $file_name = $modelHelperService->generateHexId().'_'.str_replace(' ', '_', $attachment->getClientOriginalName());
                $attachment->storeAs('temp',$file_name,'local');
                array_push($attachments,Storage::disk('local')->path('temp/'.$file_name));
            }
        }
        if(is_array($request['candidates'])){
            foreach ($request['candidates'] as $candidate) {
                $new_detail['subject'] = $request['subject'] ?? '';
                $new_detail['from'] = $request['from_mail'] ?? '';
                if($curr_candidate = $candidateRepository->findWhere(['id_candidat' => $candidate['id_candidat']])->first()){
                    if(!$curr_candidate['email_candidat']) continue;
                    $new_detail['to'] = $curr_candidate['email_candidat'];
                    $new_detail['html_body'] = $this->prepareCandidatesContactHtmlBody($request['mail'],$curr_candidate,$curr_contact,$offer);
                    $new_detail['attachments'] = $attachments;
                    array_push($details,$new_detail);
                }
            }
        } else {
            return $details;
        }
        return $details;
    }

    public function prepareCandidatesContactHtmlBody($body,$candidate,$contact,$offer)
    {
        $body = str_replace("@@email_candidat@@", $candidate['email_candidat'] ?? '', $body);
        $body = str_replace("@@nom_candidat@@", $candidate['nom_candidat'] ?? '', $body);
        $body = str_replace("@@prenom_candidat@@", $candidate['prenom_candidat'] ?? '', $body);
        $body = str_replace("@@civilite_candidat@@",'', $body);
        $body = str_replace("@@login_candidat@@", $candidate['login_candidat'] ?? '', $body);
        $body = str_replace("@@passe_candidat@@", '', $body);
        $body = str_replace("@@nom_recruteur@@", $contact['nom_contact'] ?? '', $body);
        $body = str_replace("@@email_recruteur@@", $contact['email_contact'] ?? '', $body);
        $body = str_replace("@@prenom_recruteur@@", $contact['prenom_contact'], $body);
        $body = str_replace("@@intitule_offre@@", $offer['intitule_offre'] ?? '', $body);
        $body = str_replace("@@reference_offre@@", $offer['reference_offre'] ?? '', $body);
        $body = str_replace("@@id_offre@@", $offer['id_offre'] ?? '', $body);
        //TODO change to the Offer URL
        //$body = str_replace("@@lien_url_offre@@",  $offer['id_offre'] . ".html", $body);
        $body = str_replace("@@telephone_candidat@@", $candidate['telephone_candidat'] ?? '', $body);
        $body = str_replace("@@mobile_candidat@@", $candidate['mobile_candidat'] ?? '', $body);
        $body = str_replace("@@civilite_recruteur@@", '', $body);
        $body = str_replace("@@fonction_recruteur@@", $contact['fonction_contact'] ?? '', $body);
        $body = str_replace("@@telephone_recruteur@@", $contact['telephone_contact'] ?? '', $body);
        $body = str_replace("@@mobile_recruteur@@", $contact['portable_contact'] ?? '', $body);
        return $body;
    }
}
