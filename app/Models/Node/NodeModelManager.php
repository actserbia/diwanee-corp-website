<?php
namespace App\Models\Node;

use App\Utils\Utils;
use App\Constants\Settings;
use App\NodeType;
use App\Constants\FieldTypeCategory;

trait NodeModelManager {
    public function __call($method, $parameters) {
        if(strpos($method, 'additional_fields_from_') !== false && !$this->isRelation($method)) {
            $nodeTypeName = Utils::getFormattedName(str_replace('additional_fields_from_', '', $method));
            $this->relationsSettings[$method] = [
                'relationType' => 'hasOne',
                'model' => 'App\\NodeModel\\' . ucfirst(Settings::NodeModelPrefix) . $nodeTypeName,
                'foreignKey' => 'node_id',
                'relationKey' => 'id'
            ];
        }
        
        return parent::__call($method, $parameters);
    }
    
    public function populateData($attributes = null) {
        if(isset($this->id) || isset($attributes['model_type_id'])) {
            $this->modelType = isset($this->id) ? $this->model_type : NodeType::find($attributes['model_type_id']);
            
            $this->populateAttributesFieldsData();
            $this->populateTagFieldsData();
        }
    }

    private function populateAttributesFieldsData() {
        $this->relationsSettings['additional_data'] = [
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

        if(isset($this->relationsSettings['additional_data'])) {
            $model = new $this->relationsSettings['additional_data']['model'];
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
        
        if(isset($this->relationsSettings['additional_data'])) {
            $model = new $this->relationsSettings['additional_data']['model'];
            $attributes = array_merge($attributes, $model->getAllAttributes());
        }
        
        return $attributes;
    }

    public function getRequiredAttributes() {
        $requiredFields = parent::getRequiredAttributes();
        
        if(isset($this->relationsSettings['additional_data'])) {
            $model = new $this->relationsSettings['additional_data']['model'];
            $requiredFields = array_merge($requiredFields, $model->getRequiredAttributes());
        }

        return $requiredFields;
    }
    
    public function getAllAttributesTypes() {
        $attributesTypes = parent::getAllAttributesTypes();
        
        if(isset($this->relationsSettings['additional_data'])) {
            $model = new $this->relationsSettings['additional_data']['model'];
            $attributesTypes = array_merge($attributesTypes, $model->getAllAttributesTypes());
        }
        
        return $attributesTypes;
    }

    public function attributeValue($field) {
        if(isset($this->additional_data)) {
            if($this->additional_data->attributeValue($field) !== null) {
                return $this->additional_data->attributeValue($field);
            }
        }

        return parent::attributeValue($field);
    }
}