<?php
/**
 * Created by PhpStorm.
 * User: evgeniy
 * Date: 01.12.16
 * Time: 17:27
 */
namespace console\components;
use Yii;
use yii\base\Component;
use yii\base\InvalidConfigException;
use linslin\yii2\curl;
use garyjl\simplehtmldom;
use console\models\Category;
use console\models\Startup;
use console\models\Site;

class StartengineParserComponent extends Component
{
    const API_URL = '';//API URL
    const STARTUP_SITE_URL = '';//Startup Site URL
    const SITE_URL = '';//Site URL
    const API_CATEGORY_ENDPOINT = '/categories';
    const API_STARTUP_ENDPOINT = '/startups';
    const SITE_NAME = 'StartEngine';

    private $curlClient;
    /**
     * StartengineParserComponent constructor.
     * @param $curlClient
     */
    public function __construct()
    {
        $this->curlClient = new curl\Curl();
    }
    /**
     * @return curl\Curl
     */
    public function getCurlClient()
    {
        return $this->curlClient;
    }

    /**
     * @param curl\Curl $curlClient
     */
    public function setCurlClient($curlClient)
    {
        $this->curlClient = $curlClient;
    }

    public function parse()
    {
        return $this->parseCategories() && $this->parseStartups();
    }


    public function isCategoryExist($category){
        if($site = Site::find()->where(['name'=>self::SITE_NAME])->one()){
            return Category::find()->where(['parsed_id'=>$category['id'],'site_id'=>$site->id])->one();
        }
        return false;
    }

    public function saveCategory($category){
        $site = Site::find()->where(['name'=>self::SITE_NAME])->one();
        if($site){
            $newCategory = new Category();
            $newCategory->name = $category['name'];
            $newCategory->parsed_id = $category['id'];
            $newCategory->keywords = $category['name'];
            $newCategory->site_id = $site->id;
            $newCategory->save();
        }
    }

    public function updateCategory($currCategory,$newCategory){
        if($currCategory && $newCategory){
            $currCategory->name = $newCategory['name'];
            $currCategory->parsed_id = $newCategory['id'];
            $currCategory->save();
        }
    }

    public function parseCategories(){
        $result = true;
        $response = $this->curlClient->get(self::API_URL.self::API_CATEGORY_ENDPOINT);
        $categories = json_decode($response,true);
        if($categories){
            echo date("Y-m-d H:i:s"). ' Categories founded!'."\r\n";
            foreach ($categories['categories'] as $category){
                if($currCategory = $this->isCategoryExist($category)){
                    $this->updateCategory($currCategory,$category);
                } else {
                    $this->saveCategory($category);
                }
            }
        }
        return $result;
    }

    public function isStartupExist($startup){
        if($site = Site::find()->where(['name'=>self::SITE_NAME])->one()){
            return Startup::find()->where(['parsed_id'=>$startup['id'],'site_id'=>$site->id])->one();
        }
        return false;

    }

    public function saveStartup($startup){
        $site = Site::find()->where(['name'=>self::SITE_NAME])->one();
        if($site){
            $startupCategory = Category::find()->where(['parsed_id'=>$startup['category_id']])->one();
            if($startupCategory){
                $newStartup = new Startup();
                $newStartup->name = $startup['name'];
                $newStartup->live_date = isset($startup['live_date'])?date('Y-m-d H:i:s',strtotime($startup['live_date'])):null;
                $newStartup->funding_start_date = isset($startup['funding_start_date'])?$startup['funding_start_date']:null;
                $newStartup->funding_end_date = isset($startup['funding_end_date'])?$startup['funding_end_date']:null;
                $newStartup->funding_goal = isset($startup['funding_goal'])?$startup['funding_goal']:null;
                $newStartup->funds_commited = isset($startup['funds_committed'])?$startup['funds_committed']:null;
                $newStartup->max_funding_amount = isset($startup['maximum_funding_amount'])?$startup['maximum_funding_amount']:null;
                $newStartup->legal_company_name = isset($startup['legal_company_name'])?$startup['legal_company_name']:null;
                preg_match('/^\swith\s(\d*)d\sleft/', $newStartup['days_left'], $matches);
                if(isset($newStartup['funding_end_date'])){
                    $dateDiff = strtotime($newStartup['funding_end_date'])-time();
                    $daysLeft = floor($dateDiff / (60 * 60 * 24));
                }
                $newStartup->days_left = isset($matches[1])?$matches[1]:(isset($daysLeft)?$daysLeft:null);
                $newStartup->maximum_investment_amount = isset($startup['maximum_investment_amount'])?$startup['maximum_investment_amount']:null;
                $newStartup->minimum_investment_amount = isset($startup['minimum_investment_amount'])?$startup['minimum_investment_amount']:null;
                $newStartup->number_of_contributors = isset($startup['number_of_contributors'])?$startup['number_of_contributors']:null;
                $newStartup->status = isset($startup['status'])?$startup['status']:null;
                $newStartup->parsed_id = $startup['id'];
                $newStartup->category_id = $startupCategory->id;
                $newStartup->site_id = $site->id;
                $newStartup->startup_url=self::STARTUP_SITE_URL.'/'.$startup['link'];
                $newStartup->parsed_json = json_encode($startup);
                return $newStartup->save();
            }
        }
        return false;
    }

    public function updateStartup($currStartup,$newStartup){
        $site = Site::find()->where(['name'=>self::SITE_NAME])->one();
        if($site) {
            $startupCategory = Category::find()->where(['parsed_id' => $newStartup['category_id']])->one();
            if ($startupCategory) {
                $currStartup->name = $newStartup['name'];
                $currStartup->live_date = isset($newStartup['live_date']) ? date('Y-m-d H:i:s',strtotime($newStartup['live_date'])) : null;
                $currStartup->funding_start_date = isset($newStartup['funding_start_date']) ? $newStartup['funding_start_date'] : null;
                $currStartup->funding_end_date = isset($newStartup['funding_end_date']) ? $newStartup['funding_end_date'] : null;
                $currStartup->funding_goal = isset($newStartup['funding_goal']) ? $newStartup['funding_goal'] : null;
                $currStartup->funds_commited = isset($newStartup['funds_committed']) ? $newStartup['funds_committed'] : null;
                $currStartup->max_funding_amount = isset($newStartup['maximum_funding_amount']) ? $newStartup['maximum_funding_amount'] : null;
                $currStartup->legal_company_name = isset($newStartup['legal_company_name']) ? $newStartup['legal_company_name'] : null;
                preg_match('/^\swith\s(\d*)d\sleft/', $newStartup['days_left'], $matches);
                if(isset($newStartup['funding_end_date'])){
                    $dateDiff = strtotime($newStartup['funding_end_date'])-time();
                    $daysLeft = floor($dateDiff / (60 * 60 * 24));
                }
                $currStartup->days_left = isset($matches[1])?$matches[1]:(isset($daysLeft)?$daysLeft:null);
                $currStartup->maximum_investment_amount = isset($newStartup['maximum_investment_amount'])?$newStartup['maximum_investment_amount']:null;
                $currStartup->minimum_investment_amount = isset($newStartup['minimum_investment_amount'])?$newStartup['minimum_investment_amount']:null;
                $currStartup->number_of_contributors = isset($newStartup['number_of_contributors']) ? $newStartup['number_of_contributors'] : null;
                $currStartup->status = isset($newStartup['status']) ? $newStartup['status'] : null;
                $currStartup->parsed_id = $newStartup['id'];
                $currStartup->category_id = $startupCategory->id;
                $currStartup->site_id = $site->id;
                $currStartup->startup_url = self::STARTUP_SITE_URL . '/' . $newStartup['link'];
                $currStartup->parsed_json = json_encode($newStartup);
                return $currStartup->save();
            }
        }
    }

    public function parseStartups(){
        $html = simplehtmldom\SimpleHtmlDom::file_get_html(self::SITE_URL);
        $scripts = $html->find('script[!src]');
        foreach ($scripts as $script) {
            $result = false;
            if(strpos((string)$script,'window.startups_list')!==FALSE){
                $result = str_replace('<script>','',
                          str_replace('</script>','',
                          str_replace("\t",'',str_replace('window.startups_list = ','',
                          str_replace('];',']',(string)$script)))));
                break;
            }
        }
        if($result){
            echo date("Y-m-d H:i:s"). ' Startups founded!'."\r\n";
            $startupsnew = false;
            try{
                $startupsnew = json_decode($result,true);
            } catch(Exception $e) {
                echo date("Y-m-d H:i:s"). 'Parsed Startups from HTML Json decode Error'."\r\n";
                var_dump($result);
                echo '-------------------------Json decode Error END ___________________';
            }
            if($startupsnew){
                foreach($startupsnew as $startup){
                    $resp = $this->curlClient->get(self::API_URL.self::API_STARTUP_ENDPOINT.'/'.$startup['link']);
                    $parsed = false;
                    try{
                        $parsed = json_decode($resp,true);
                        $startups['startups'][] = $parsed;
                    } catch(Exception $e) {
                        echo date("Y-m-d H:i:s"). 'Parsed Startup from API Json decode Error'."\r\n";
                        var_dump($resp);
                        echo '-------------------------Json decode Error END ___________________';
                    }
                }
                if($parsed){
                    foreach ($startups['startups'] as $startup){
                        if($currStartup = $this->isStartupExist($startup)){
                            $this->updateStartup($currStartup,$startup);
                        } else {
                            $this->saveStartup($startup);
                        }
                    }
                    echo date("Y-m-d H:i:s").'reverse check startups'."\r\n";
                    if($site = Site::find()->where(['name'=>self::SITE_NAME])->one()){
                        $existingStartups  = Startup::find()->where(['not in','status',['deleted']])->andWhere(['site_id'=>$site->id])->all();
                        foreach($existingStartups as $existingStartup){
                            $found = false;
                            foreach ($startups['startups'] as $parsedStartup){
                                if($parsedStartup['id'] == $existingStartup->parsed_id){
                                    $found = true;
                                    echo 'Founded:'.$parsedStartup['name']."\r\n";
                                    break;
                                }
                            }
                            if(!$found) {
                                $existingStartup->status = 'deleted';
                                echo 'Not Founded:'.$existingStartup->name."\r\n";
                                $existingStartup->save();
                            }
                        }
                    }
                }
            }
        }
    }
}
