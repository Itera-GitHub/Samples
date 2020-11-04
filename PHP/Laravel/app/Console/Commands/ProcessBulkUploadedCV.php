<?php

namespace App\Console\Commands;

use App\Console\Traits\PrependOutputTrait;
use App\Console\Traits\PrependsTimestampTrait;
use App\Models\Candidate;
use App\Repositories\CandidateRepository;
use App\Services\Helpers\ModelHelperService;
use App\Services\Parsers\LingWayLeaService;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class ProcessBulkUploadedCV extends Command
{
    use PrependOutputTrait, PrependsTimestampTrait;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cv:process-bulk-uploaded';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command processes the uploaded cvs';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle(
        LingWayLeaService $lingWayLeaService,
        ModelHelperService $modelHelperService,
        CandidateRepository $candidateRepo
    ) {
        $bulkInProgressPath = env('CV_FILES_BULK_INPROGRESS_DIR', 'cv_files/bulk_in_progress');
        $bulkUploadedPath = env('CV_FILES_BULK_UPLOADED_DIR', 'cv_files/bulk_uploaded');
        $bulkProcessedPath = env('CV_FILES_PARSED_DIR', 'cv_files/parsed');
        $bulkErrorsPath = env('CV_FILES_BULK_ERRORS_DIR', 'cv_files/bulk_errors');
        $this->info('Starting process:');
        $filesToMove = collect(Storage::disk('local')->allFiles($bulkUploadedPath));
        $this->info('Files count:' . $filesToMove->count());
        if ($filesToMove->count()) {
            $this->info('Starting moving Files to the in_progress dir to avoid double parsing ...');
            $filesToMove->each(function ($item) use ($bulkInProgressPath) {
                Storage::move($item, $bulkInProgressPath . '/' . basename($item));
            });
            $this->info('Files are moved to in_progress dir');
            $this->info('Starting to parse');
            foreach ($filesToMove as $file) {
                $fileName = basename($file);
                $this->info('Processing file: ' . $fileName);
                if ($parsed = $lingWayLeaService->parseResume(Storage::disk('local')->path($bulkInProgressPath . '/' . $fileName),
                    $fileName)) {
                    $preparedModel = $modelHelperService->prepareCandidateModel($parsed);
                    //if  Name, Surname and Email not parsed we can't create candidat - log error
                    if (!$preparedModel['nom_candidat'] && !$preparedModel['prenom_candidat'] && !$preparedModel['email_candidat']) {
                        $this->error('Name, SurName and email is not parsed in file - moving it to error dir');
                        $this->moveFile($bulkInProgressPath . '/' .$fileName,$bulkErrorsPath . '/' . $fileName);
                        $this->info('File ' . $fileName . ' Moved');
                    } else {//Email or name or Surname Parsed
                        try {
                            $this->moveFile($bulkInProgressPath . '/' .$fileName, $bulkProcessedPath . '/' . $fileName);
                            $this->info('Parsed Candidat Surname - Name - Email');
                            $this->info($preparedModel['nom_candidat'] . ' - ' . $preparedModel['prenom_candidat'] . ' - ' . $preparedModel['email_candidat']);
                            if ($preparedModel['email_candidat'] && $foundCandidat = $candidateRepo->findByField('email_candidat',
                                    $preparedModel['email_candidat'], ['id_candidat'])->first()) {
                                $this->info('Finding existing candidat by email:');
                                $this->info('Candidat found. ID_CANDIDAT = ' . $foundCandidat->id_candidat);
                                $this->info('Creating attachment and assign it to the candidat');
                                $modelHelperService->storeCandidateAttachment($foundCandidat, $fileName);
                                $this->info('Attachment created. Continue parsing.');
                            } else {// Create candidat without email and assign attachment to new created candidat
                                $this->info('Candidat not found');
                                $this->info('Creating candidat with parsed data, creating attachment and assign it to the candidat');
                                //Set Candidat status = Imported from bulk upload - IMPORT CV par lot
                                $preparedModel['actif_candidat'] = 'c9c1b116fffb0';
                                $newCandidat = Candidate::create($preparedModel);
                                if ($newCandidat->id_candidat) {
                                    $modelHelperService->storeCandidateAttachment($newCandidat, $fileName);
                                    $this->info('Candidat and Attachment are created. Continue parsing.');
                                }
                            }
                        } catch (\Exception $e) {
                            $this->error('Create Attachment error occured:' . $e->getMessage());
                            $this->moveFile($bulkInProgressPath . '/' .$fileName, $bulkErrorsPath . '/' . $fileName);
                            $this->info('File ' . $fileName . ' Moved to error dir');
                        }
                    }
                } else {
                    $this->error('File parsing error - moving it to error dir');
                    Storage::disk('local')->move($bulkInProgressPath . '/' .$fileName, $bulkErrorsPath . '/' . $fileName);
                    $this->info('File ' . $fileName . ' Moved');
                }
            }
        } else {
            $this->info('No Files to process. Finished');
        }
        $this->info('Process finished');
    }

    private function moveFile($from, $to)
    {
        try {
            if( Storage::disk('local')->exists($to)){
                if(Storage::disk('local')->rename($to, $to.'_old')) {
                    if($result = Storage::disk('local')->move($from, $to)){
                        Storage::disk('local')->delete($to.'_old');
                        return $result;
                    } else {
                        $this->info('File moving error');
                        Storage::disk('local')->rename($to.'_old', $to);
                        return false;
                    }
                }
            } else {
               return Storage::disk('local')->move($from, $to);
            }
        } catch (Exception $e) {
            return false;
        }

    }
}
