<?php
namespace App\Models;

use App\Utils\Utils;
use App\Constants\Models;

trait Statistics {
    public function scopeStatistics($query, $fullFieldName) {
        $statisticsManager = $this->getStatisticsManager($fullFieldName);
        return $statisticsManager->getStatistics($query);
    }

    private function getStatisticsManager($fullFieldName) {
        $fieldSettings = $this->getFieldSettings($fullFieldName);
        if(!empty($fieldSettings)) {
            $fieldType = $fieldSettings['field_type'] === Models::FieldType_AttributeAggregate ? Models::FieldType_Attribute : $fieldSettings['field_type'];
            $statisticsManagerClassName = 'App\\Models\\Statistics\\StatisticsManager\\Statistics' . Utils::getFormattedName($fieldType);
            return new $statisticsManagerClassName($fieldSettings);
        }
    }

    public function getStatisticFieldsWithLabels() {
        $statisticsFields = [];

        foreach($this->getStatisticFields() as $fullFieldName) {
            $statisticsFields[$fullFieldName] = $this->fieldLabel($fullFieldName);
        }

        return $statisticsFields;
    }
    
    protected function getStatisticFields() {
        return $this->statisticFields;
    }
}