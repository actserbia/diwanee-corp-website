<?php
namespace App\Models\Node;

use App\Utils\Utils;
use App\Constants\Settings;
use App\Constants\Models;
use App\NodeType;
use App\Constants\FieldTypeCategory;
use Request;

trait NodeModelManager {
    public function isRelation($field) {
        // GRAPHQL!!!
        if(strpos($field, 'additional_fields_from_') !== false && !isset($this->relationsSettings[$field]) && strpos(Request::url(), '/graphql') !== false) {
            $this->relationsSettings[$field] = [
                'relationType' => 'hasOne',
                'model' => 'App\\NodeModel\\' . ucfirst(Settings::NodeModelPrefix) . Utils::getFormattedName(str_replace('additional_fields_from_', '', $field)),
                'foreignKey' => 'node_id',
                'relationKey' => 'id'
            ];
        }

        return parent::isRelation($field);
    }
    
    public function populateData($attributes = null) {
        if(isset($this->id) || isset($attributes['model_type_id'])) {
            $this->modelType = isset($this->id) ? $this->model_type : NodeType::find($attributes['model_type_id']);
            
            $this->populateAttributesFieldsData();
            $this->populateTagFieldsData();
            $this->populateRelationFieldsData();
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
            $this->populateFieldData($tagField, 'tags');
        }
    }
    
    private function populateRelationFieldsData() {
        $relationFieldsRelationName = FieldTypeCategory::Relation . '_fields';
        foreach($this->modelType->$relationFieldsRelationName as $relationField) {
            $this->populateRelationFieldData($relationField);
        }
    }
    
    private function populateRelationFieldData($relationField) {
        if($relationField->pivot->active) {
            $parentRelation = '';
            
            foreach($this->relationsSettings as $relation => $relationSettings) {
                if($relationSettings['relationType'] === 'belongsToMany' && $relationSettings['pivot'] === 'node_' . Utils::getFormattedDBName($relationField->title)) {
                    $parentRelation = $relation;
                    break;
                }
            }
          
            if($parentRelation !== '') {
                $this->populateFieldData($relationField, $parentRelation);
            }
        }
    }
    
    private function populateFieldData($field, $parentRelation) {
        $formType = $field->pivot->multiple_list[0] ? Models::FormFieldType_Relation_Select_TagsParenting : Models::FormFieldType_Relation_Input;
                
        $relationSettings = [
            'parent' => $parentRelation,
            'automaticRender' => true,
            'automaticSave' => true,
            'formType' => $formType
        ];
                
        $this->relationsSettings[$field->formattedTitle] = $relationSettings;

        if($field->pivot->required) {
            $this->requiredFields[] = $field->formattedTitle;
        }

        if($field->pivot->multiple_list[0]) {
            $this->multipleFields[$field->formattedTitle] = array_slice($field->pivot->multiple_list, 1);
        } else {
            $this->multipleFields[$field->formattedTitle] = (bool)$field->pivot->multiple_list[1];
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