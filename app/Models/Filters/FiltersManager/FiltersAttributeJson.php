<?php
namespace App\Models\Filters\FiltersManager;

use App\Models\Filters\FiltersManager\FiltersBase\FiltersBaseSearch;

class FiltersAttributeJson extends FiltersBaseSearch {
    use AttributesJson;

    protected function qWithAttribute($query, $param, $index) {
        $this->setSearchTypeForParam($index);

        $functionName = $this->getConnectionTypeFunction();
        $query->$functionName(function ($q) use ($param) {
            $this->queryWithAttribute($q, $param);
        });
    }
}