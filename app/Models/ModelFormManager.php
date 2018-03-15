<?php
namespace App\Models;

use App\Constants\Models;
use App\Utils\Utils;
use Request;

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

    public function formValue($fullFieldName) {
        $fieldName = $this->getFieldName($fullFieldName);
        return Request::old('_token') !== null ? Request::old($fieldName) : $this->attributeValue($fieldName);
    }
    
    public function formReadonlyData($fullFieldName, $column = null) {
        $fieldName = $this->getFieldName($fullFieldName);
        if($this->isRelation($fieldName)) {
            $dropdownColumn = ($column !== null) ? $column : $this->getRelationModel($fieldName)->defaultDropdownColumn;
            return $this->getRelationItemData($fieldName, $dropdownColumn);
        } elseif($this->attributeType($fieldName) === Models::AttributeType_Enum) {
            return [[
                'label' => __('constants.' . $this->modelName . Utils::getFormattedName($fieldName))[$this->attributeValue($fieldName)],
                'value' => $this->attributeValue($fieldName)
            ]];
        } else {
            return [[
                'label' => $this->attributeValue($fieldName),
                'value' => $this->attributeValue($fieldName)
            ]];
        }
    }
    
    private function getRelationItemData($relation, $attribute) {
        $values = [];
        
        if($this->hasMultipleValues($relation)) {
            foreach($this->$relation as $relationItem) {
                $values[] = [
                    'label' => $relationItem->$attribute,
                    'value' => $relationItem->id,
                    'url' => $relationItem->editUrl
                ];
            }
        } elseif(isset($this->$relation)) {
            $values[] = [
                'label' => $this->$relation->$attribute,
                'value' => $this->$relation->id,
                'url' => $this->$relation->editUrl
            ];
        }
        
        return $values;
    }

    public function checkFormSelectValue($fullFieldName, $itemValue) {
        $fieldName = $this->getFieldName($fullFieldName);
        if(Request::old('_token') !== null && Request::old($fieldName) == $itemValue) {
            return true;
        }
        
        if(Request::post('_token') !== null && Request::post($fieldName) == $itemValue) {
            return true;
        }

        $attributeValue = $this->attributeValue($fieldName);
        if($this->attributeValue($fieldName) == $itemValue) {
            return $attributeValue !== null;
        }

        return false;
    }

    public function formRelationValues($relation) {
        if(Request::old('_token') !== null) {
            if($this->checkDependsOn($relation)) {
                return static::formRelationValuesByOld($relation);
            }
        }

        return $this->getRelationValues($relation);
    }

    private function formRelationValuesByOld($relation) {
        $items = [];
        $dependsOn = $this->dependsOn($relation, false);
        foreach($dependsOn as $depsOn) {
            $items[$depsOn] = Request::old($depsOn);
        }
        return $this->getRelationValues($relation, $items);
    }

    public function checkFormSelectRelationValue($relation, $item, $level = null) {
        if($level === null) {
            if(Request::old('_token') !== null && Request::old($relation) == $item->id) {
                return true;
            }

            if(Request::post('_token') !== null && Request::post($relation) == $item->id) {
                return true;
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

    public function formSelectedValues($relation) {
        if(Request::old('_token') !== null) {
            $oldValue = [];
            if(Request::old($relation) !== null) {
                $oldValue = $this->getRelationModel($relation)::find(Request::old($relation));
            }
            return $oldValue;
        }

        if(isset($this->$relation)) {
            return $this->$relation;
        }

        return [];
    }
    
    public function formFieldName($fullFieldName, $prefix = '') {
        return empty($prefix) ? $fullFieldName : $prefix . '[' . $fullFieldName . ']';
    }

    public function formInputRelationValue($relation, $column) {
        if(Request::old('_token') !== null) {
            return Request::old($relation);
        }

        if(Request::post('_token') !== null) {
            return Request::post($relation);
        }

        if(isset($this->$relation->id)) {
            return $this->$relation->$column;
        }

        return '';
    }
    
    public function formCheckboxListValue($fullFieldName, $index, $type = 'single') {
        $formValue = $this->formValue($fullFieldName);
        
        if(empty($formValue)) {
            return $formValue;
        }
        
        if($type === 'list') {
            return array_slice($formValue, $index);
        } else {
            return $formValue[$index];
        }
    }
}