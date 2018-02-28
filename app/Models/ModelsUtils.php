<?php
namespace App\Models;

class ModelsUtils {
    public static function addQueryRelationFilters($query, $model, $relation) {
        //foreach($model->getRelationFilters($relation) as $filterField => $filterValues) {
        //    $query->whereIn($filterField, $filterValues);
        //}

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
}