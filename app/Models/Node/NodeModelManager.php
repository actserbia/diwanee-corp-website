<?php
namespace App\Models\Node;

use App\Utils\Utils;
use App\Constants\Settings;
use App\NodeType;
use App\Constants\FieldTypeCategory;
use Request;

trait NodeModelManager {
    public function __construct(array $attributes = array()) {
        parent::__construct($attributes);
        
        // GRAPHQL!!!
        if(strpos(Request::url(), '/graphql') !== false) {
            $nodeTypeNames = NodeType::pluck('name');
            foreach($nodeTypeNames as $nodeTypeName) {
                $this->relationsSettings['additional_fields_from_' . Utils::getFormattedDBName($nodeTypeName)] = [
                    'relationType' => 'hasOne',
                    'model' => 'App\\NodeModel\\' . ucfirst(Settings::NodeModelPrefix) . $nodeTypeName,
                    'foreignKey' => 'node_id',
                    'relationKey' => 'id'
                ];
            }
        }
    }
    
    public function populateData($attributes = null) {
        if(isset($this->id) || isset($attributes['model_type_id'])) {
            $this->modelType = isset($this->id) ? $this->model_type : NodeType::find($attributes['model_type_id']);
            
            $this->populateAttributesFieldsData();
            $this->populateTagFieldsData();
        }
    }

    private function populateAttributesFieldsData() {
        $this->relationsSettings['additional_fields'] = [
            'relationType' => 'hasOne',
            'model' => 'App\\NodeModel\\' . ucfirst(Settings::NodeModelPrefix) . Utils::getFormattedName($this->modelType->name, ' '),
            'foreignKey' => 'node_id',
            'relationKey' => 'id'
        ];
    }

    private function populateTagFieldsData() {
        $tagFieldsRelationName = FieldTypeCategory::Tag . '_fields';
        foreach($this->modelType->$tagFieldsRelationName as $tagField) {
            $this->populateTagFieldData($tagField);
        }
    }
    
    private function populateTagFieldData($tagField) {
        if($tagField->pivot->active) {
            $relationSettings = [
                'parent' => 'tags',
                'filters' => ['tag_type_id' => [$tagField->field_type_id]],
                'automaticRender' => true,
                'automaticSave' => true
            ];
            $this->relationsSettings[$tagField->formattedTitle] = $relationSettings;

            if($tagField->pivot->required) {
                $this->requiredFields[] = $tagField->formattedTitle;
            }

            $this->multipleFields[$tagField->formattedTitle] = $tagField->pivot->multiple_list;
        }
    }

    protected function getAutomaticRenderAtributes() {
        $fields = parent::getAutomaticRenderAtributes();

        if(isset($this->relationsSettings['additional_fields'])) {
            $model = new $this->relationsSettings['additional_fields']['model'];
            foreach($model->getFillableAttributes() as $field) {
                if(strpos($field, '_id') === false) {
                    $fields[] = $field;
                }
            }
        }

        return $fields;
    }

    protected function getAllAttributes() {
        $attributes = parent::getAllAttributes();
        
        if(isset($this->relationsSettings['additional_fields'])) {
            $model = new $this->relationsSettings['additional_fields']['model'];
            $attributes = array_merge($attributes, $model->getAllAttributes());
        }
        
        return $attributes;
    }

    public function getRequiredAttributes() {
        $requiredFields = parent::getRequiredAttributes();
        
        if(isset($this->relationsSettings['additional_fields'])) {
            $model = new $this->relationsSettings['additional_fields']['model'];
            $requiredFields = array_merge($requiredFields, $model->getRequiredAttributes());
        }

        return $requiredFields;
    }
    
    public function getAllAttributesTypes() {
        $attributesTypes = parent::getAllAttributesTypes();
        
        if(isset($this->relationsSettings['additional_fields'])) {
            $model = new $this->relationsSettings['additional_fields']['model'];
            $attributesTypes = array_merge($attributesTypes, $model->getAllAttributesTypes());
        }
        
        return $attributesTypes;
    }

    public function attributeValue($field) {
        if(isset($this->additional_fields)) {
            if($this->additional_fields->attributeValue($field) !== null) {
                return $this->additional_fields->attributeValue($field);
            }
        }

        return parent::attributeValue($field);
    }
    
    public function checkFormSelectRelationValue($relation, $item, $level = null) {
        if($relation === 'model_type') {
            if(Request::old('_token') !== null && Request::old($relation) == $item->id) {
                return true;
            }

            if(Request::post('_token') !== null && Request::post($relation) == $item->id) {
                return true;
            }
            
            if(isset($this->modelType->id)) {
                return ($this->modelType->id == $item->id);
            } else {
                return ($this->defaultAttributeValue($relation) == $item->id);
            }
        } else {
            return parent::checkFormSelectRelationValue($relation, $item, $level);
        }
    }
}