<?php


namespace App\Services\Parsers;


use Exception;
use Illuminate\Support\Facades\Storage;
use SoapBox\Formatter\Formatter;

class LingWayLeaService extends ApiBaseService
{

    protected $xml_save_path = null;

    public function __construct($api_base, $api_key, $format, $xml_save_path)
    {
        parent::__construct($api_base,$api_key,$format);
        $this->xml_save_path =  $xml_save_path;
    }

    protected function makeUrl($method)
    {
        return $this->api_base.$method.'/'.$this->api_key.'/'.$this->response_format;
    }

    public function parseResume($file, $fileName)
    {

        $isLingwayEnabled = env('LINGWAY_SEMANTIC_ENABLED',true);
        if($isLingwayEnabled) {
            $params = [
                'multipart' => [
                    [
                        'name'     => 'FileContents',
                        'contents' => file_get_contents($file),
                        'filename' => basename($file)
                    ]
                ]];
            try {
                $response = $this->post('parse',$params);
                if($response){
                    Storage::disk('local')->put($this->xml_save_path.'/'.$fileName.'.xml', $response);
                    $response = str_replace('lea:','lea_',$response);
                    $formatter = Formatter::make($response, Formatter::XML);
                    return $formatter->toArray();
                }
            } catch (Exception $e) {
                return false;
            }
        }
        return false;
    }

}
