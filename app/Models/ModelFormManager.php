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
            return Models::FormFieldType_Relation;
        }
        
        $modelManager = $this->getModelManager($fieldName);
        return $modelManager->formFieldType();
    }

    public function fieldLabel($fullFieldName) {
        $fieldName = $this->getFieldName($fullFieldName);
        if($this->isRelation($fieldName)) {
            return __('models_labels.' . $this->modelName . '.' . $fieldName . '_label');
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
        return Request::old('_token') !== null ? Request::old($fieldName) : $this->$fieldName;
    }
    
    public function formReadonlyValue($fullFieldName) {
        $fieldName = $this->getFieldName($fullFieldName);
        if($this->isRelation($fieldName)) {
            return $this->getRelationItemValue($fieldName, 'id');
        } else {
            return [$this->$fieldName];
        }
    }
    
    public function formReadonlyText($fullFieldName, $column = null) {
        $fieldName = $this->getFieldName($fullFieldName);
        if($this->isRelation($fieldName)) {
            $dropdownColumn = ($column !== null) ? $column : $this->getRelationModel($fieldName)->defaultDropdownColumn;
            return $this->getRelationItemValue($fieldName, $dropdownColumn);
        } elseif($this->attributeType($fieldName) === Models::AttributeType_Enum) {
            return [__('constants.' . $this->modelName . Utils::getFormattedName($fieldName))[$this->$fieldName]];
        } else {
            return [$this->$fieldName];
        }
    }
    
    private function getRelationItemValue($relation, $attribute) {
        $values = [];
        
        if($this->isMultiple($relation)) {
            foreach($this->$relation as $value) {
                $values[] = $value->$attribute;
            }
        } else {
            $values[] = isset($this->$relation) ? $this->$relation->$attribute : '';
        }
        
        return $values;
    }

    public function checkFormSelectValue($fullFieldName, $itemValue) {
        $fieldName = $this->getFieldName($fullFieldName);
        if(Request::old('_token') !== null && Request::old($fieldName) == $itemValue) {
            return true;
        }

        if($this->$fieldName == $itemValue) {
            return true;
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

    public function checkFormSelectRelationValue($relation, $item) {
        if(Request::old('_token') !== null && Request::old($relation) == $item->id) {
            return true;
        }

        if(isset($this->$relation->id) && $this->$relation->id == $item->id) {
            return true;
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


}