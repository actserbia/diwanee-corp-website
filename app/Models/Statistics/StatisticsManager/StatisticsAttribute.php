<?php
namespace App\Models\Statistics\StatisticsManager;

use Illuminate\Support\Facades\DB;

class StatisticsAttribute extends StatisticsManager {
    protected function attributeName() {
        return $this->attribute;
    }
    
    protected function fieldColumnAlias() {
        return $this->fieldModel->getTable();
    }
    
    protected function getStatisticsQuery($query) {
        return $query->select(DB::raw($this->column() . ' AS value, COUNT(*) AS count'));
    }
}