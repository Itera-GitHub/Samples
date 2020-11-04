<?php


namespace App\Services\FormBuilders;


use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Support\Facades\Storage;

class GenericFormsService
{
    const FORM_EXTENSION = '.definition.xml.json';

    private $parsedJsonDir;

    public function __construct()
    {
        $this->parsedJsonDir = env('UI_XML_JSON_DIR','ui_templates/json');
    }

    public function getFormJson($formName)
    {
        if(Storage::disk('local')->exists($this->parsedJsonDir.'/'.$formName.self::FORM_EXTENSION)) {
            try {
                return Storage::disk('local')->get($this->parsedJsonDir.'/'.$formName.self::FORM_EXTENSION);
            } catch(FileNotFoundException $e) {
                return false;
            }
        }
        return false;
    }

    public function prepareFormData($formJson)
    {
        $decoded = json_decode($formJson, true);
        if(isset($decoded['form_blocks']) && isset($decoded['form_blocks']['fields'])){
            $fields = $decoded['form_blocks']['fields'];
            $result = '';
            foreach($fields as $field){
                switch($field['field_type']){
                    case 'debut_div':
                        $result = '';
                        break;
                    case 'text':
                        break;
                    case 'hierselect':
                        break;
                    case 'select':
                        break;
                    case 'hidden':
                        break;
                    case 'advcheckbox':
                        break;
                    case 'date_text':
                        break;
                    default:
                }
            }
            return $result;
        }


        return $formJson;
    }

    public function getPreparedForm($formName)
    {
        return $this->prepareFormData($this->getFormJson($formName));
    }
}
