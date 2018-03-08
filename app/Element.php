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
        'updated_at' => true,
        'node:title' => true,
        'node:status' => true,
        'node:created_at' => false,
        'node:updated_at' => false,
        'node:deleted_at' => false
    ];
    
    protected $statisticFields = [
        'type',
        'data:heading_type',
        'data:source'
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
        'element_item' => [
            'relationType' => 'belongsToMany',
            'model' => 'App\\Node',
            'pivot' => 'element_item',
            'foreignKey' => 'element_id',
            'relationKey' => 'item_id'
        ],
        'node' => [
            'relationType' => 'belongsToMany',
            'model' => 'App\\Node',
            'pivot' => 'node_element',
            'foreignKey' => 'element_id',
            'relationKey' => 'node_id',
            'automaticSave' => false
        ]
    ];

    public function getDataAttribute($value) {
        $data = json_decode($value);

        $data->id = $this->id;

        if(in_array($this->type, array_keys(ElementType::itemsTypesSettings))) {
            $data = $this->populateElementItemData($data);
        }

        if(in_array($this->type, ElementType::imageTypes) && !isset($data->file->url)) {
            $data->file->url = ImagesManager::getS3Path($data->file->hash, false);
            unset($data->file->hash);
        }

        return $data;
    }
    
    private function populateElementItemData($data) {
        $this->populateElementItemRelationSettings();
        
        $data->item_name = $this->element_item->defaultDropdownColumnValue;
        
        if(isset(ElementType::itemsTypesSettings[$this->type]['filter'])) {
            $filter = ElementType::itemsTypesSettings[$this->type]['filter'];
            if($this->element_item->isRelation($filter)) {
                $data->filter = $this->element_item->hasMultipleValues($filter) ? 0 : $this->element_item->$filter->id;
            } else {
                $data->filter = $this->element_item->$filter;
            }
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

        if(in_array($elementData['type'], array_keys(ElementType::itemsTypesSettings))) {
            unset($preparedElementData['data']['item_name']);
            unset($preparedElementData['data']['filter']);
        }

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

    public function saveItems($elementData) {
        if(in_array($elementData['type'], array_keys(ElementType::itemsTypesSettings))) {
            if(!isset($this->element_item) || $this->element_item->id != $elementData['data']['item_id']) {
                $this->element_item()->detach();
                $this->element_item()->attach([$elementData['data']['item_id']]);
            }
        }
    }

    public function __call($method, $parameters) {
        if($method === 'element_item') {
            $this->populateElementItemRelationSettings();
        }
        
        if(strpos($method, 'element_item_') !== false && !$this->isRelation($method)) {
            $itemTypeName = str_replace('element_item_', '', $method);
            $this->relationsSettings[$method] = [
                'relationType' => 'belongsToMany',
                'model' => ElementType::itemsTypesSettings['diwanee_' . $itemTypeName]['model'],
                'pivot' => 'element_item',
                'foreignKey' => 'element_id',
                'relationKey' => 'item_id'
            ];
        }

        return parent::__call($method, $parameters);
    }
    
    private function populateElementItemRelationSettings() {
        if(in_array($this->type, array_keys(ElementType::itemsTypesSettings))) {
            $this->relationsSettings['element_item']['model'] = ElementType::itemsTypesSettings[$this->type]['model'];
        }
    }
}