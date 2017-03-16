<?php
require_once realpath(__DIR__).'/../vendor/autoload.php';
use GuzzleHttp\Client;

class MLSDataRequester
{
    private $config = array(
        'BASE_URI'=>'https://api2....'//sample api url
    );

    /**
     * @return mixed
     */
    public function getConfig($key)
    {
        $result = false;
        if(array_key_exists($key,$this->config)){
            $result =  $this->config[$key];    
        }
        return $result;
    }

    /**
     * @param mixed $config
     */
    public function setConfig($config)
    {
        $this->config = $config;
    }

    /**
     * MLSDataRequester constructor.
     * @param $config
     */
    public function __construct($config=false)
    {
        if($config) $this->config = array_merge($this->config,$config);
    }
    
    public function getIndividualData($agencyId){
        $method = '/Listing.svc/PropertySearch_Post';
        $client = new Client(array('base_url'=>$this->getConfig('BASE_URI')));
        $request = $client->post($method,array('body'=>array(
            'CultureId' => 1,
            'ApplicationId' => 1,
            'ReferenceNumber' => $agencyId,
            'IncludeTombstones' => 1
        )));
        $result = $request->json();
        return $result;
    }

    public function decodeData($response, $section) {
        $result = array();
        if (isset($response['Results']) && is_array($response['Results'])) {
            $results = array_shift($response['Results']);
            if (isset($results[$section])) {
                $sectionResult = $results[$section];
                $result = array_shift($sectionResult);
            }
        }
        return $result;
    }

    public function decodePhonesData($sectionData, $phoneType) {
        $result = array();
        if (is_array($sectionData)) {
            foreach($sectionData as $item) {
                if($item['PhoneType'] == $phoneType) {
                    $result[] = $item;
                }
            }
        }
        return $result;
    }
    
    public function requestApiData($params){
        $method = '/Listing.svc/PropertySearch_Post';
        $client = new Client(array('base_url'=>$this->getConfig('BASE_URI')));
        $request = $client->post($method, array('body'=>array($params)));
        $result = $request->json();
        return $result;
    }
}

/* Example to use
 * set MLS as getIndividualData parameter
 * in result['Results'] there is array named 'Individual' with all needed data
 * if result['Results'] is empty then there is no fresh data for this MLS

$requester = new MLSDataRequester();
var_dump($requester->getIndividualData('C3590145'));

*/