<?php
namespace App\Models;

use App\Constants\Models;

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
    
    public static function checkIfNodeTypeIdIsInPredefinedTypesList($nodeTypeId) {
        return in_array($nodeTypeId, Models::NodeType_PredefinedList);
    }
    
    public static function checkIfFieldIdIsInPredefinedFieldsList($fieldId) {
        return in_array($fieldId, Models::Field_PredefinedList);
    }
}