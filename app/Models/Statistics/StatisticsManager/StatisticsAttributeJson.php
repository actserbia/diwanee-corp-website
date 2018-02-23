<?php
namespace App\Models\Statistics\StatisticsManager;

class StatisticsAttributeJson extends StatisticsManager {
    protected function attributeName() {
        return $this->attribute . ':' . $this->jsonField();
    }
    
    protected function fieldColumnAlias() {
        return $this->fieldModel->getTable();
    }
    
    protected function jsonField() {
        return isset($this->json_attribute['settings']['field']) ? $this->json_attribute['settings']['field'] : $this->json_attribute['name'];
    }
    
    protected function getStatisticsQuery($query) {
        return $this->jsonStatistics($query);
    }
}