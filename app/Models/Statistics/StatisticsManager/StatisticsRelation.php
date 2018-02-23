<?php
namespace App\Models\Statistics\StatisticsManager;

use Illuminate\Support\Facades\DB;

class StatisticsRelation extends StatisticsManager {
    protected function attributeName() {
        return $this->attribute;
    }
    
    protected function fieldColumnAlias() {
        return $this->relation;
    }
    
    protected function getStatisticsQuery($query) {
        $queryJoin = $this->relationJoin($query);
        return $queryJoin->select(DB::raw($this->column() . ' AS value, COUNT(*) AS count'));
    }
    
    protected function getAllPossibleFieldValues() {
        $fieldValues = [];
        
        $relationValues = $this->model->getRelationValues($this->relation);
        $fieldName = $this->attribute;
        foreach($relationValues as $relationValue) {
            $fieldValues[] = $relationValue->$fieldName;
        }
        
        return $fieldValues;
    }
}