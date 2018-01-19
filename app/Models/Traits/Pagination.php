<?php
namespace App\Models\Traits;

trait Pagination  {
    public function scopeWithPagination($query, $params) {
        $perPage = isset($params['perPage']) && is_numeric($params['perPage']) ? $params['perPage'] : 0;
        return $perPage > 0 ? $query->paginate($perPage) : $query->get();
    }
}
