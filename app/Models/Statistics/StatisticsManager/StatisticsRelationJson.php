<?php
namespace App\Models\Statistics\StatisticsManager;

class StatisticsRelationJson extends StatisticsManager {
    protected function attributeName() {
        return $this->attribute . ':' . $this->jsonField();
    }
    
    protected function fieldColumnAlias() {
        return $this->relation;
    }
    
    protected function jsonField() {
        return isset($this->json_attribute['settings']['field']) ? $this->json_attribute['settings']['field'] : $this->json_attribute['name'];
    }
    
    protected function getStatisticsQuery($query) {
        $queryJoin = $this->relationJoin($query);
        return $this->jsonStatistics($queryJoin);
    }
}