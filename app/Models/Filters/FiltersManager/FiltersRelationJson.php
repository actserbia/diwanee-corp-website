<?php
namespace App\Models\Filters\FiltersManager;

use App\Models\Filters\FiltersManager\FiltersBase\FiltersBaseSearch;

class FiltersRelationJson extends FiltersBaseSearch {
    use AttributesJson;

    protected function qWithAttribute($query, $param, $index) {
        $this->setSearchTypeForParam($index);

        $functionName = $this->getConnectionTypeFunction('functionRelation');
        $query->$functionName($this->relation, function($q) use($param) {
            $this->queryWithAttribute($q, $param);
        });
    }
}