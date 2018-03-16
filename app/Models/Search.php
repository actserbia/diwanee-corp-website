<?php
namespace App\Models;

use App\Constants\Models;
use App\Constants\Filters;
use App\Models\Filters\FiltersUtils;
use Auth;
use Illuminate\Support\Str;

trait Search {
    public function scopeFilterByAllParams($query, $params, $orderBy = true) {
        $preparedParams = FiltersUtils::prepareParams($params);

        foreach(array_keys($params) as $fullFieldName) {
            $filtersManager = $this->getFiltersManager($fullFieldName);

            if(!empty($filtersManager)) {
                $filtersManager->filterByParam($query, $preparedParams, $fullFieldName, $orderBy);
            }
        }
    }

    public function scopeFilterByParam($query, $fullFieldName, $params, $customFieldName, $orderBy = true) {
        if(isset($params[$customFieldName])) {
            $filtersManager = $this->getFiltersManager($fullFieldName);

            if(!empty($filtersManager)) {
                $filtersManager->filterByParam($query, FiltersUtils::prepareParams($params), $customFieldName, $orderBy);
            }
        }
    }

    private function getFiltersManager($fullFieldName) {
        if(strpos($fullFieldName, 'searchTypes') !== false || strpos($fullFieldName, 'connectionType') !== false) {
            return null;
        }
        
        $fieldSettings = $this->getFieldSettings($fullFieldName);

        if(!empty($fieldSettings)) {
            $filtersManagerClassName = 'App\\Models\\Filters\\FiltersManager\\Filters' . Str::studly($fieldSettings['field_type']);
            return new $filtersManagerClassName($fieldSettings);
        }

        return null;
    }

    public function getSearchTypesForDropdown($fullFieldName) {
        $filtersManager = $this->getFiltersManager($fullFieldName);
        return $filtersManager->getSearchTypesForDropdown();
    }

    public function getValidatorParams($relation = '') {
        $params = [];

        $attributePrefix = empty($relation) ? '' : $relation . ':';

        foreach($this->getAllAttributes() as $attribute) {
            if($this->attributeType($attribute) === Models::AttributeType_Date) {
                $params[$attributePrefix . $attribute . '.0'] = 'nullable|date';
                $params[$attributePrefix . $attribute . '.1'] = 'nullable|date|after_or_equal:' . $attributePrefix . $attribute . '.0';
            }
        }

        if(empty($relation)) {
            foreach($this->getSupportedRelations() as $relation) {
                $params = array_merge($params, $this->getRelationModel($relation)->getValidatorParams($relation));
            }
        }

        return $params;
    }

    public function replaceWithAttributtesNames($text, $fullFieldName) {
        $replacedText = str_replace(str_replace('_', ' ', $fullFieldName), $this->fieldLabel($fullFieldName), $text);

        $filtersManager = $this->getFiltersManager($fullFieldName);
        return $filtersManager->replaceWithAttributtesNames($replacedText);
    }
    
    public function filterAttributeType($fullFieldName) {
        $modelManager = $this->getModelManager($fullFieldName);
        $type = $modelManager->attributeType();
        return $type === Models::AttributeType_Email ? Models::AttributeType_Text : $type;
    }
    
    public function filterIsTextType($fullFieldName) {
        $type = $this->filterAttributeType($fullFieldName);
        return $type === Models::AttributeType_Text ? true : false;
    }
    
    public function filterDefaultSearchType($fullFieldName) {
        return $this->filterIsTextType($fullFieldName) ? Filters::DefaultSearchType['text'] : Filters::DefaultSearchType['number'];
    }
    
    public function fullIsMultipleRelation($fullFieldName) {
        $fieldSettings = $this->getFieldSettings($fullFieldName);
        return isset($fieldSettings['relation']) && $this->hasMultipleValues($fieldSettings['relation']);
    }
    
    public function getFilterFieldsWithLabels() {
        $fieldsWithLabels = [];

        $fields = $this->getFilterFields();
        foreach($fields as $fullFieldName => $visible) {
            if (Auth::admin() || $visible === true) {
                $fieldsWithLabels[$fullFieldName] = $this->fieldLabel($fullFieldName);
            }
        }

        return $fieldsWithLabels;
    }
    
    protected function getFilterFields() {
        return $this->filterFields;
    }
}