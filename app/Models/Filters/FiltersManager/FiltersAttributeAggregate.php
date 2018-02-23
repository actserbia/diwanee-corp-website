<?php
namespace App\Models\Filters\FiltersManager;

class FiltersAttributeAggregate extends FiltersAttribute {
    protected function addAllParamsToQuery() {
        foreach($this->paramsArray as $index => $param) {
            $this->setSearchTypeForParam($index);
            $this->queryWithAttributeAggregate($this->query, $param);
        }
    }
}