<?php
namespace App\Traits;

trait Pagination  {
    public function scopePaginateIfParamExists($query, $params) {
        $perPage = isset($params['perPage']) && is_numeric($params['perPage']) ? $params['perPage'] : 0;
        return $perPage > 0 ? $query->paginate($perPage) : $query->get();
    }
}
