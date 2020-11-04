<?php


namespace App\Services\Parsers;


use Exception;
use SoapBox\Formatter\Formatter;
use SoapBox\Formatter\Parsers\Parser;

class AttributedXMLParser extends Parser
{
    private $xml;

    public function __construct($data)
    {
        $this->xml = $this->objectify($data);
    }

    private function objectify($value)
    {
        $temp = is_string($value) ?
            simplexml_load_string($value, 'SimpleXMLElement', LIBXML_NOCDATA) :
            $value;

        $result = [];
        foreach ((array) $temp as $key => $value) {
            if ($key === "@attributes") {
                if(is_array($value) or is_object($value)){
                    foreach ($value as $k => $attribute){
                        $result[$k] = $attribute;
                    }
                } else {
                    $result[key($value)] = $value[key($value)];
                }
            } elseif (is_array($value) && count($value) < 1) {
                $result[$key] = '';
            } else {
                $result[$key] = (is_array($value) or is_object($value)) ? $this->objectify($value) : $value;
            }
        }

        return $result;
    }

    public function toArray()
    {
        return (array) $this->xml;
    }

}

class AttributedXMLFormatter extends Formatter
{
    const XML_ATTRIBUTED = 'xml_attributed';

    private $parser;

    private function __construct($parser)
    {
        $this->parser = $parser;
    }

    public function toJson()
    {
        return $this->parser->toJson();
    }
    public function toArray()
    {
        return $this->parser->toArray();
    }

    public function toYaml()
    {
        return $this->parser->toYaml();
    }

    public function toXml($baseNode = 'xml', $encoding = 'utf-8', $formated = false)
    {
        return $this->parser->toXml($baseNode, $encoding, $formated);
    }

    public function toCsv($newline = "\n", $delimiter = ",", $enclosure = '"', $escape = "\\")
    {
        return $this->parser->toCsv($newline, $delimiter, $enclosure, $escape);
    }


    public static function make($data, $type, $delimiter = null)
    {
        if ($type == self::XML_ATTRIBUTED) {
            $parser = new AttributedXMLParser($data);
            return new AttributedXMLFormatter($parser);
        } else {
            return parent::make($data,$type,$delimiter);
        }
        throw new InvalidArgumentException(
            'make function only accepts [csv, json, xml, array] for $type but ' . $type . ' was provided.'
        );
    }


}

class UiXmlParserService
{
    public $formatter;

    public function transformXmlToJson($input,$fileName)
    {
        $result = false;
        try {
            $this->formatter = AttributedXMLFormatter::make($input,AttributedXMLFormatter::XML_ATTRIBUTED);
            $result = $this->transformToFormFormat($fileName);
        } catch (Exception $exception) {
            return $result;
        }
        return $result;
    }

    public function transformToFormFormat($fileName)
    {
        $collectionToTransform = collect($this->formatter->toArray());
        $result = [
            'form_blocks' => [
                'file' => $fileName,
                'title' => 'FORM BLOCK TITLE',
                'fields' => []
            ]
        ];
        if($collectionToTransform->has('element')){
            foreach ($collectionToTransform['element'] as $element) {
                $newField = [
                    'field_title' => $element['lib'] ?? '',
                    'field_type' => $element['type'] ?? '',
                    'field_name' => $element['nom'] ?? '',
                    'field_type_select' => $element['type_select'] ?? '',
                    'field_options' => $element['option'] ?? '',
                    'field_value' => $element['value'] ?? '',
                    'field_class' => $element['class'] ?? '',
                    'field_group' => $element['group'] ?? '',
                    'field_group_id' => $element['group_id'] ?? '',
                ];
                $result['form_blocks']['fields'][] = $newField;
            }
            return json_encode($result);
        }
    }
}
