<?php
namespace App\Models;

use App\Constants\Models;

trait ModelAttributesManager {
    protected $globalAttributeType = [
        'id' => Models::AttributeType_Number,
        'created_at' => Models::AttributeType_Date,
        'updated_at' => Models::AttributeType_Date,
        'deleted_at' => Models::AttributeType_Date,
        'ordinal_number' => Models::AttributeType_Number
    ];
    
    public function getFillableAttributes() {
        return $this->fillable;
    }
    
    protected function getAllAttributes() {
        return array_merge($this->allAttributesFields, $this->allFieldsFromPivots);
    }

    public function getRequiredAttributes() {
        return $this->requiredFields;
    }
    
    public function getAllAttributesTypes() {
        return array_merge($this->globalAttributeType, $this->attributeType);
    }

    public function getModelNameAttribute() {
        return str_replace('App\\', '', $this->getModelClassAttribute());
    }
    
    public function getModelClassAttribute() {
        return get_class($this);
    }
    
    public function getDefaultDropdownColumnAttribute() {
        return $this->defaultDropdownColumn;
    }
    
    public function getDefaultDropdownColumnValueAttribute() {
        $defaultDropdownColumn = $this->defaultDropdownColumn;
        return $this->$defaultDropdownColumn;
    }
    
    public function isAttribute($field) {
        return in_array($field, $this->getAllAttributes());
    }

    public function isRequired($field) {
        return in_array($field, $this->getRequiredAttributes());
    }

    public function attributeType($fullFieldName) {
        $fieldName = str_replace('_confirmation', '', $this->getFieldName($fullFieldName));
        
        $attributesTypes = $this->getAllAttributesTypes();
        return isset($attributesTypes[$fieldName]) ? $attributesTypes[$fieldName] : Models::AttributeType_Text;
    }

    public function getJsonAttributeFilters($jsonAttribute) {
        return isset($this->jsonCustomAttribute[$jsonAttribute]) ? $this->jsonCustomAttribute[$jsonAttribute] : [];
    }
    
    public function attributeValue($field) {
        if(isset($this->pivot->$field)) {
            return $this->pivot->$field;
        } else {
            return isset($this->$field) ? $this->$field : $this->defaultAttributeValue($field);
        }
    }
    
    public function defaultAttributeValue($field) {
        return isset($this->defaultFieldsValues[$field]) ? $this->defaultFieldsValues[$field] : null;
    }
    
    protected function getAutomaticRenderAtributes() {
        $fields = [];

        foreach($this->fillable as $field) {
            if(strpos($field, '_id') === false) {
                $fields[] = $field;
            }
        }

        return $fields;
    }
    
    public function modelTypeIdValue() {
        return isset($this->modelType->id) ? $this->modelType->id : '';
    }
}