<?php
namespace App\Models\Filters\FiltersManager;

use App\Models\Filters\FiltersManager\FiltersBase\FiltersBaseSearch;

class FiltersAttribute extends FiltersBaseSearch {
    use Attributes;

    protected function qWithAttribute($query, $param, $index) {
        $this->setSearchTypeForParam($index);

        $functionName = $this->getConnectionTypeFunction();
        $query->$functionName(function ($q) use ($param) {
            $this->queryWithAttribute($q, $param);
        });
    }

    protected function additionalWithAttribute() {
        if($this->orderBy && $this->checkIfAllSearchTypesAreEqual()) {
            $this->query->orderByRaw('FIELD(`' . $this->attribute . '`, "' . implode('","', $this->paramsArray) . '")');
        }
    }
}