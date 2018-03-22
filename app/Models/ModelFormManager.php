<?php
namespace App\Models;

use App\Constants\Models;
use App\Utils\Utils;
use Request;
use Illuminate\Support\Str;

trait ModelFormManager {
    public function formFieldType($fullFieldName, $readonly = false) {
        if($readonly) {
            return Models::FormFieldType_Readonly;
        }
        
        $fieldName = $this->getFieldName($fullFieldName);
        if($this->isRelation($fieldName)) {
            return $this->relationFormType($fieldName);
        }
        
        $modelManager = $this->getModelManager($fieldName);
        return $modelManager->formFieldType();
    }

    public function fieldLabel($fullFieldName) {
        $fieldName = $this->getFieldName($fullFieldName);
        if($this->isRelation($fieldName)) {
            return Utils::translate('models_labels.' . $this->modelName . '.' . $fieldName . '_label', $fieldName);
        }
        
        $modelManager = $this->getModelManager($fieldName);
        return $modelManager->fieldLabel($fieldName);
    }

    public function fieldValue($fullFieldName, $value = null) {
        $fieldName = $this->getFieldName($fullFieldName);
        $modelManager = $this->getModelManager($fieldName);
        return $modelManager->fieldValue($value);
    }

    public function getEnumListForDropdown($fullFieldName) {
        $fieldName = $this->getFieldName($fullFieldName);
        $modelManager = $this->getModelManager($fieldName);
        return $modelManager->getEnumListForDropdown();
    }

    public function getTypeaheadItems($fullFieldName) {
        $fieldName = $this->getFieldName($fullFieldName);
        $modelManager = $this->getModelManager($fieldName);
        return $modelManager->getTypeaheadItems();
    }

    public function formValue($fullFieldName, $prefix = '') {
        $fieldName = $this->getFieldName($fullFieldName);

        if(Request::old('_token') !== null) {
            return $this->formGetRequestPrefixData($fieldName, $prefix);
        }

        return $this->attributeValue($fieldName);
    }
    
    public function formReadonlyData($fullFieldName, $withCategory = false) {
        $fieldName = $this->getFieldName($fullFieldName);
        if($this->isRelation($fieldName)) {
            return $this->getRelationItemData($fieldName, $withCategory);
        } elseif($this->attributeType($fieldName) === Models::AttributeType_Enum) {
            return [[
                'label' => $withCategory ? $this->getNameWithCategoryField() : __('constants.' . $this->modelName . Str::studly($fieldName))[$this->attributeValue($fieldName)],
                'value' => $this->attributeValue($fieldName)
            ]];
        } else {
            return [[
                'label' => $withCategory ? $this->getNameWithCategoryField() : $this->attributeValue($fieldName),
                'value' => $this->attributeValue($fieldName)
            ]];
        }
    }
    
    private function getRelationItemData($relation, $withCategory) {
        $values = [];
        
        $representationField = $this->getRelationModel($relation)->representationField;

        if($this->hasMultipleValues($relation)) {
            foreach($this->$relation as $relationItem) {
                $values[] = [
                    'label' => $withCategory ? $relationItem->getNameWithCategoryField() : $relationItem->$representationField,
                    'value' => $relationItem->id,
                    'url' => $relationItem->editUrl
                ];
            }
        } elseif(isset($this->$relation)) {
            $values[] = [
                'label' => $withCategory ? $this->$relation->getNameWithCategoryField() : $this->$relation->$representationField,
                'value' => $this->$relation->id,
                'url' => $this->$relation->editUrl
            ];
        }
        
        return $values;
    }

    public function checkFormSelectValue($fullFieldName, $itemValue, $prefix = '') {
        $fieldName = $this->getFieldName($fullFieldName);
        
        if(Request::old('_token') !== null || Request::post('_token') !== null) {
            return ($this->formGetRequestPrefixData($fieldName, $prefix) == $itemValue);
        }

        $attributeValue = $this->attributeValue($fieldName);
        if($this->attributeValue($fieldName) == $itemValue) {
            return $attributeValue !== null;
        }

        return false;
    }

    public function formRelationValues($relation, $prefix = '') {
        if(Request::old('_token') !== null && $this->checkDependsOn($relation)) {
            return static::formRelationValuesByOld($relation, $prefix);
        }

        return $this->getRelationValues($relation);
    }

    private function formRelationValuesByOld($relation, $prefix) {
        $items = [];
        $dependsOn = $this->dependsOn($relation, $prefix, false);
        foreach($dependsOn as $depsOn) {
            $items[$depsOn] = $this->formGetRequestPrefixData($depsOn, $prefix);
        }
        return $this->getRelationValues($relation, $items);
    }

    public function checkFormSelectRelationValue($relation, $item, $prefix = '', $level = null) {
        if($level === null) {
            if(Request::old('_token') !== null || Request::post('_token') !== null) {
                return ($this->formGetRequestPrefixData($relation, $prefix) == $item->id);
            }
            
            if(isset($this->$relation->id)) {
                return ($this->$relation->id == $item->id);
            } else {
                return ($this->defaultAttributeValue($relation) == $item->id);
            }
        } elseif(!$this->hasMultipleValues($relation, $level) && isset($this->$relation)) {
            foreach($this->$relation as $relationItem) {
                if($relationItem->id === $item->id) {
                    return true;
                }
            }
        }

        return false;
    }
    
    public function checkFormDisabledRelationValue($relation, $item, $level = null) {
        if($this->hasMultipleValues($relation, $level)) {
            foreach($this->$relation as $relationItem) {
                if($relationItem->id === $item->id) {
                    return true;
                }
            }
        }

        return false;
    }

    public function formSelectedValues($relation, $prefix = '') {
        if(Request::old('_token') !== null) {
            return $this->formGetSelectedValuesFromRequest($relation, $prefix);
        }

        if(isset($this->$relation)) {
            return $this->$relation;
        }

        return [];
    }
    
    private function formGetSelectedValuesFromRequest($relation, $prefix = '') {
        $requestRelationData = $this->formGetRequestPrefixData($relation, $prefix);

        if($requestRelationData === null) {
            return [];
        }

        if(!is_array($requestRelationData)) {
            return $this->getRelationModel($relation)::find($requestRelationData);
        }

        $relationItemsList = [];
        foreach($requestRelationData as $relationItemId) {
            $relationItemsList[$relationItemId] = $this->getRelationModel($relation);
            if(strpos($relationItemId, '-new') === false) {
                $relationItemsList[$relationItemId] = $relationItemsList[$relationItemId]::find($relationItemId);
            }
        }
        return $relationItemsList;
    }

    public function formFieldName($fullFieldName, $prefix = '') {
        return empty($prefix) ? $fullFieldName : $prefix . '[' . $fullFieldName . ']';
    }

    public function formInputRelationValue($relation, $column, $prefix = '') {
        if(Request::old('_token') !== null || Request::post('_token') !== null) {
            return $this->formGetRequestPrefixData($relation, $prefix);
        }

        if(isset($this->$relation->id)) {
            return ($column === 'id') ? $this->id : $this->$relation->getNameWithCategoryField();
        }

        return '';
    }

    private function formGetRequestPrefixData($fieldName, $prefix) {
        $requestData = $this->formGetRequestData();

        if(empty($prefix)) {
            return $requestData[$fieldName];
        }

        $pos = strpos($prefix, '[');
        if($pos === false) {
            $requestPrefixData = $requestData[$prefix];
        } else {
            $firstAttribute = substr($prefix, 0, $pos);
            $otherAttributesList = str_replace(['[', ']'], ['[\'', '\']'], substr($prefix, $pos));

            eval('$requestPrefixData = isset($requestData[\'' . $firstAttribute . '\']' . $otherAttributesList . ') ? $requestData[\'' . $firstAttribute . '\']' . $otherAttributesList . ' : \'\';');
        }

        return isset($requestPrefixData[$fieldName]) ? $requestPrefixData[$fieldName] : '';
    }

    private function formGetRequestData($param = null) {
        if(Request::old('_token') !== null) {
            return ($param !== null) ? Request::old($param) : Request::old();
        }

        if(Request::post('_token') !== null) {
            return ($param !== null) ? Request::post($param) : Request::post();
        }

        return null;
    }

    public function formHasError($errors, $fieldName, $prefix = '') {
        $errorField = str_replace(['[', ']'], ['.', ''], $prefix) . '.' . $fieldName;
        return $errors->has($errorField);
    }

    public function formErrorMessage($errors, $fieldName, $prefix = '') {
        $errorField = str_replace(['[', ']'], ['.', ''], $prefix) . '.' . $fieldName;
        return $errors->has($errorField) ? str_replace('The ' . $errorField, __('validation.this_field'), $errors->first($errorField)) : '';
    }
}