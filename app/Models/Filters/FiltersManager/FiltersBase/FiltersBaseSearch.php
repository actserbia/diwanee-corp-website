<?php
namespace App\Models\Filters\FiltersManager\FiltersBase;

use App\Constants\Models;
use App\Constants\Filters;

abstract class FiltersBaseSearch extends FiltersBaseParams {
    protected $filters = [];

    protected $fieldSettings = null;
    protected $orderBy = true;

    public function filterByParam($query, $params, $paramName, $orderBy) {
        $this->orderBy = $orderBy;

        $this->setAllSearchSettings($query, $params, $paramName);

        $this->prepareParams();
        if(!empty($this->paramsArray)) {
            $this->addAllParamsToQuery();
        }

        $this->additionalWithAttribute();
    }

    protected function additionalWithAttribute() {}

    protected function addAllParamsToQuery() {
        $this->query->where(function ($query) {
            foreach($this->paramsArray as $index => $param) {
                $this->qWithAttribute($query, $param, $index);
            }
        });
    }
    
    protected function getAttribute() {
        return str_replace('*', '.', $this->attribute);
    }

    private function prepareParams() {
        if($this->fieldModel->attributeType($this->attribute) === Models::AttributeType_Date) {
            foreach($this->paramsArray as $index => $param) {
                if(empty($param) && $this->searchTypesArray[$index] !== Filters::SearchEmptyOrNull) {
                    unset($this->paramsArray[$index]);
                    unset($this->searchTypesArray[$index]);
                }
            }
        }
    }

    protected function addTrashed($query) {
        if(!isset($this->relation)) {
            return;
        }

        if($this->fieldModel->checkIfUseSoftDeletes()) {
            $query->withTrashed();
        }
    }
}