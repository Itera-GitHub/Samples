<?php


namespace App\Services\Parsers;


use Exception;

class MyCvConverterService extends ApiBaseService
{

    protected function makeUrl($method)
    {
        return $this->api_base.$method;
    }

    public function parseResume($file)
    {
        $params = [
            'headers' => [
                'Accept'                => '*/*',
            ],
            'multipart' => [
                [
                    'name' => 'mycv-api-token',
                    'contents' => $this->api_key,
                ],
                [
                    'name'     => 'file',
                    'contents' => file_get_contents($file),
                    'filename' => basename($file)
                ]
            ]
        ];
        try {
            $response = $this->post('text-extraction/document',$params);
            if($response){
                return $response;
            }
        } catch (Exception $e) {
            return false;
        }
        return false;
    }

}
