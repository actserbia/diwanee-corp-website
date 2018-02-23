<?php
namespace App\Models\Statistics\StatisticsManager;

use App\Constants\Models;
use Illuminate\Support\Facades\DB;

abstract class StatisticsManager {
    protected $fieldModel = null;

    public function __construct($fieldSettings) {
        foreach($fieldSettings as $settingName => $fieldSetting) {
            $this->$settingName = $fieldSetting;
        }

        if(isset($this->relation)) {
            $this->fieldModel = $this->model->getRelationModel($this->relation);
        } else {
            $this->fieldModel = $this->model;
        }
    }

    public function getStatistics($query) {
        $statistics = $this->getStatisticsQuery($query)->groupBy('value')->orderBy('count', 'desc')->get();
        return $this->populateStatistics($statistics);
    }
    
    protected function populateStatistics($statistics) {
        $returnList = [];

        foreach($statistics as $statistic) {
            $returnList[$statistic->value] = $this->getStatisticItem($statistic->value, $statistic->count);
        }

        $fieldValues = $this->getAllPossibleFieldValues();
        
        foreach($fieldValues as $fieldValue) {
            if(!isset($returnList[$fieldValue])) {
                $returnList[$fieldValue] = $this->getStatisticItem($fieldValue, 0);
            }
        }

        return $returnList;
    }
    
    private function getStatisticItem($value, $count) {
        $item = new \stdClass();
        
        $item->value = $value;
        $item->count = $count;
        
        return $item;
    }
    
    protected function getAllPossibleFieldValues() {
        if($this->fieldModel->attributeType($this->attributeName()) === Models::AttributeType_Enum) {
            $fieldValues = $this->fieldModel->getEnumListForDropdown($this->attributeName());
        }
        
        return isset($fieldValues) ? array_keys($fieldValues) : [];
    }

    protected function column() {
        $column = $this->fieldColumnAlias() . '.' . $this->attribute;
        if($this->fieldModel->attributeType($this->attribute) === Models::AttributeType_Date) {
            $column = 'DATE(' . $column . ')';
        }
        return $column;
    }
    
    protected function relationJoin($statisticsQuery) {
        $relationSettings = $this->model->getRelationSettings($this->relation);

        $modelTable = $this->model->getTable();
        $joinTable = $this->fieldModel->getTable();
        $joinTableAlias = $this->relation;

        if(isset($relationSettings['pivot'])) {
            $statisticsJoin = $statisticsQuery->join($relationSettings['pivot'], $modelTable . '.id', '=', $relationSettings['pivot'] . '.' . $relationSettings['foreignKey'])
                ->join(DB::raw($joinTable . ' AS ' . $joinTableAlias), $relationSettings['pivot'] . '.' . $relationSettings['relationKey'], '=', $joinTableAlias . '.id');
        } else {
            $statisticsJoin = $statisticsQuery->join(DB::raw($joinTable . ' AS ' . $joinTableAlias), $modelTable . '.' . $relationSettings['foreignKey'], '=', $joinTableAlias . '.id');
        }

        $filters = $this->model->getRelationFilters($this->relation);
        foreach($filters as $filterField => $filterValues) {
            $statisticsJoin->whereIn($joinTableAlias . '.' . $filterField, $filterValues);
        }

        return $statisticsJoin;
    }
    
    protected function jsonStatistics($statisticsQuery) {
        $column = $this->column();
        $jsonField = $this->jsonField();
        return $statisticsQuery->select(DB::raw('SUBSTRING(' . $column . ', LOCATE(\'"' . $jsonField . '":"\', ' . $column . ') + CHAR_LENGTH(\'"' . $jsonField . '":"\'), LOCATE(\'"\', ' . $column . ', LOCATE(\'"' . $jsonField . '":"\', ' . $column . ') + CHAR_LENGTH(\'"' . $jsonField . '":"\') ) - (LOCATE(\'"' . $jsonField . '":"\', ' . $column . ') + CHAR_LENGTH(\'"' . $jsonField . '":"\')) ) AS value, ' . 'COUNT(*) AS count'))
            ->where(DB::raw('LOCATE(\'"' . $jsonField . '":"\', ' . $column . ')'), '>', '0');
    }
}