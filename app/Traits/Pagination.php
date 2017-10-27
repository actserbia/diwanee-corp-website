<?php
namespace App\Traits;

trait Pagination  {
    public function scopeWithPagination($query, $params) {
        $skip = isset($params['skip']) && is_numeric($params['skip']) ? $params['skip'] : 0;
        $limit = isset($params['limit']) && is_numeric($params['limit']) ? $params['limit'] : 0;
        
        if($skip > 0 && $limit == 0) {
            $limit = 10;
        }
        
        if($skip > 0 || $limit > 0) {
            $query = $query->skip($skip)->take($limit);
        }
        
        return $query;
    }
}
