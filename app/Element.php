<?php

namespace App;

use App\Constants\Models;
use App\Constants\ElementType;
use App\Constants\ElementDataHeadingType;
use App\Constants\Settings;
use App\Converters\ToHtmlConverter;
use App\Converters\ToMarkdownConverter;
use App\Utils\ImagesManager;

class Element extends AppModel {
    protected $allAttributesFields = ['id', 'type', 'data', 'created_at', 'updated_at', 'deleted_at'];
    
    protected $allFieldsFromPivots = ['element_id'];

    protected $fillable = ['type', 'data'];
    
    protected $filterFields = [
        'id' => false,
        'type' => true,
        'data:text' => true,
        'data:heading' => true,
        'data:heading_h1' => true,
        'data:heading_h2' => true,
        'data:heading_h3' => true,
        'data:heading_h4' => true,
        'data:heading_h5' => true,
        'data:heading_type' => true,
        'data:quote' => true,
        'data:cite' => true,
        'data:list' => true,
        'data:seoname' => true,
        'data:seoalt' => true,
        'data:caption' => true,
        'data:copyright' => true,
        'data:hash' => false,
        'data:source' => true,
        'data:remote_id' => true,
        'created_at' => true,
        'updated_at' => true
    ];

    protected $casts = [
        'data' => 'array'
    ];

    protected $attributeType = [
        'type' => Models::AttributeType_Enum,
        'data:heading_type' => Models::AttributeType_Enum,
        'data:source' => Models::AttributeType_Enum,
        'element_id' => Models::AttributeType_Number
    ];

    protected $jsonCustomAttribute = [
        'data:heading' => ['field' => 'text', 'in' => ['type' => [ElementType::Heading]]],
        'data:quote' => ['field' => 'text', 'in' => ['type' => [ElementType::Quote]]],
        'data:list' => ['field' => 'content', 'in' => ['type' => [ElementType::ElementList]]],
        'data:heading_h1' => ['field' => 'text', 'in' => ['type' => [ElementType::Heading]], 'in_json' => ['heading_type' => ElementDataHeadingType::H1]],
        'data:heading_h2' => ['field' => 'text', 'in' => ['type' => [ElementType::Heading]], 'in_json' => ['heading_type' => ElementDataHeadingType::H2]],
        'data:heading_h3' => ['field' => 'text', 'in' => ['type' => [ElementType::Heading]], 'in_json' => ['heading_type' => ElementDataHeadingType::H3]],
        'data:heading_h4' => ['field' => 'text', 'in' => ['type' => [ElementType::Heading]], 'in_json' => ['heading_type' => ElementDataHeadingType::H4]],
        'data:heading_h5' => ['field' => 'text', 'in' => ['type' => [ElementType::Heading]], 'in_json' => ['heading_type' => ElementDataHeadingType::H5]]
    ];


    protected $relationsSettings = [
        'nodes' => [
            'relationType' => 'belongsToMany',
            'model' => 'App\\Node',
            'pivot' => 'node_element',
            'foreignKey' => 'element_id',
            'relationKey' => 'node_id'
        ]
    ];

    protected $multipleFields = ['nodes'];

    public function getRelation($relation) {
        return $this->relations[$relation];
    }


    public function getDataAttribute($value) {
        $data = json_decode($value);

        $data->id = $this->id;

        if(in_array($this->type, ElementType::imageTypes) && !isset($data->file->url)) {
            $data->file->url = ImagesManager::getS3Path($data->file->hash, false);
            unset($data->file->hash);
        }

        return $data;
    }

    public function getEditorContentAttribute() {
        return array(
            'type' => $this->type,
            'data' => $this->data
        );
    }

    public static function prepareElementData($elementData) {
        $converter = new ToMarkdownConverter(Settings::MarkdownConverterConfig);
        $preparedElementData = $converter->convertElementData($elementData);

        unset($preparedElementData['data']['id']);

        if(in_array($elementData['type'], ElementType::imageTypes)) {
            $preparedElementData['data']['file']['hash'] = ImagesManager::getHashFromS3Path($preparedElementData['data']['file']['url']);
            unset($preparedElementData['data']['file']['url']);
        }
        return $preparedElementData;
    }

    public function changeFormat($toHtml = false) {
        $converter = $toHtml ? new ToHtmlConverter() : new ToMarkdownConverter();
        $converter->convertElementData($this);
    }

    public static function formatElements($elements, $toHtml = false) {
        foreach($elements as $element) {
            $element->changeFormat($toHtml);
        }
    }
}