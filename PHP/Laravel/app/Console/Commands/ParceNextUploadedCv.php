<?php

namespace App\Console\Commands;

use App\Console\Traits\PrependOutputTrait;
use App\Console\Traits\PrependsTimestampTrait;
use App\Repositories\CandidateAttachmentRepository;
use App\Services\Parsers\MyCvConverterService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class ParceNextUploadedCv extends Command
{
    use PrependOutputTrait, PrependsTimestampTrait;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cv:parse-next-uploaded';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command parses the cv_pj files which was uploaded to the existing candidate';

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
    public function handle(MyCvConverterService $myCvConverterService, CandidateAttachmentRepository $candidateAttachmentRepository)
    {
        $parsedFilesPath = env('CV_FILES_PARSED_DIR','cv_files/parsed');
        if($cvToParse = $candidateAttachmentRepository->findByField('resume_pj','')){
            $this->info('Starting to Parse files from CV_PJ table: '.count($cvToParse).' files found');
            foreach ($cvToParse as $cv){
                $this->info('Processing file: '.$cv['nom_pj']);
                if(Storage::disk('local')->exists($parsedFilesPath.'/'.$cv['nom_pj'])){
                    $this->info('Send API request.');
                    $parsedCv = $myCvConverterService->parseResume(Storage::disk('local')->path($parsedFilesPath.'/'.$cv['nom_pj']));
                    if($parsedCv){
                        $this->info('File parsed - update record in the DB.');
                        $candidateAttachmentRepository->update(['resume_pj'=>$parsedCv],$cv['id_pj']);
                        $this->info('Resume updated!');
                    } else {
                        $this->error('API Request Error - ');
                    }
                } else {
                    $this->error('File not found on the storage!');
                }
            }
        } else {
            $this->error('No files found');
        }
        $this->info('Process finished.');

    }
}
