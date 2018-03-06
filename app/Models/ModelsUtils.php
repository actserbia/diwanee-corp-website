<?php
namespace App\Models;

class ModelsUtils {
    public static function addQueryRelationFilters($query, $model, $relation) {
        $relationModel = $model->getRelationModel($relation);
        $relationModel::filter($model->getRelationFilters($relation), $query);
    }

    public static function addQueryJsonFieldFilters($query, $jsonFieldSettings) {
        if(isset($jsonFieldSettings['in'])) {
            foreach($jsonFieldSettings['in'] as $filterField => $filterValues) {
                $query->whereIn($filterField, $filterValues);
            }
        }

        if(isset($jsonFieldSettings['in_json'])) {
            foreach($jsonFieldSettings['in_json'] as $filterField => $filterValue) {
                $dataFilter = '"' . $filterField . '":"' . $filterValue . '"';
                $query->where($jsonFieldSettings['attribute'], 'like', '%' . $dataFilter . '%');
            }
        }
    }

    public static function getItemsFieldsList($items, $field = 'id') {
        $ids = [];
        foreach($items as $item) {
            $ids[] = $item->$field;
        }
        return $ids;
    }
}