<?php

namespace App\Console\Commands;

use App\Console\Traits\PrependOutputTrait;
use App\Console\Traits\PrependsTimestampTrait;
use App\Services\Parsers\UiXmlParserService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Exception;

class TransformUiXmlToJson extends Command
{
    use PrependOutputTrait, PrependsTimestampTrait;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cv:transform-ui-xml';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Transform the UI XML from Mycvtheque to JSON';

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
    public function handle(UiXmlParserService $uiXmlParserService)
    {
        $inputDir =  env('UI_XML_UPLOADED_DIR','ui_templates/xml_to_parse');
        $progressDir =  env('UI_XML_INPROGRESS_DIR','ui_templates/xml_in_progress');
        $parsedJson = env('UI_XML_JSON_DIR','ui_templates/json');
        $this->info('Starting process:');
        $filesToProcess = collect(Storage::allFiles($inputDir));
        $this->info('Files count:' . $filesToProcess->count());
        if ($filesToProcess->count()) {
            $this->info('Starting moving Files to the in_progress dir to avoid double parsing ...');
            $filesToProcess->each(function ($item) use ($progressDir) {
                $this->moveFile($item, $progressDir . '/' . basename($item));
            });
            $this->info('Files are moved to in_progress dir');
            foreach ($filesToProcess as $file) {
                $fileName = basename($file);
                $this->info('Start transforming file: ' . $fileName);
                $content = Storage::disk('local')->get($progressDir . '/' . $fileName);
                if ($parsed = $uiXmlParserService->transformXmlToJson($content,$fileName)) {
                    Storage::disk('local')->put($parsedJson. '/' . $fileName.'.json',$parsed);
                }
            }
        }
    }

    private function moveFile($from, $to)
    {
        try {
            if( Storage::disk('local')->exists($to)){
                if(Storage::disk('local')->rename($to, $to.'_old')) {
                    if(Storage::disk('local')->move($from, $to)){
                        Storage::disk('local')->delete($to.'_old');
                        return true;
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
