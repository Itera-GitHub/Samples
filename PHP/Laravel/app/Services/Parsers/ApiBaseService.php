<?php


namespace App\Services\Parsers;


use Exception;
use GuzzleHttp\Client;

class ApiBaseService
{

    protected $api_key = null;
    protected $api_base = null;
    protected $response_format = null;

    public function __construct($api_base, $api_key, $format)
    {
        $this->api_base = $api_base;
        $this->api_key =  $api_key;
        $this->response_format =  $format;
    }

    protected function makeUrl($method)
    {
        return $this->api_base.$method;
    }

    protected function createClient($url)
    {
        return new Client(['base_uri'=>$url]);
    }

    protected function get($method)
    {
        $client = $this->createClient($this->makeUrl($method));
        try {
            $response  = $client->request('GET');
            return $response->getBody()->getContents();
        } catch (Exception $e){
            throw $e;
        }
    }

    protected function post($method,$params)
    {
        $client = $this->createClient($this->makeUrl($method));
        try {
            $response =  $client->request('POST','',$params);
            return $response->getBody()->getContents();
        } catch (Exception $e) {
            return false;
        }
    }

}
