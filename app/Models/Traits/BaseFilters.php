<?php
namespace App\Models\Traits;

trait BaseFilters {
    public function scopeWithAttributesEqual($query, $paramsName, $params, $attribute, $isNumeric = true) {
        if(isset($params[$paramsName])) {
            $paramsArray = is_array($params[$paramsName]) ? $params[$paramsName] : explode(',', $params[$paramsName]);
            $paramsString = $isNumeric ? implode(',', $paramsArray) : '"' . implode('","', $paramsArray) . '"';
           
            $query = $query->whereIn($attribute, $paramsArray)->orderByRaw('FIELD(' . $attribute . ', ' . $paramsString . ')');
        }
        
        return $query;
    }
    
    public function scopeWithAttributesLike($query, $paramsName, $params, $attribute) {
        if(isset($params[$paramsName])) {
            $paramsArray = is_array($params[$paramsName]) ? $params[$paramsName] : explode(',', $params[$paramsName]);
            
            foreach($paramsArray as $param) {
                $query = $query->orWhere($attribute, 'like', '%' . $param . '%');
            }
        }
        
        return $query;
    }
}
