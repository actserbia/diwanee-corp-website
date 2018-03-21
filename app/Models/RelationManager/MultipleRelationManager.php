<?php
namespace App\Models\RelationManager;

class MultipleRelationManager extends RelationManager {
    private $index = 0;
    
    private $data = [];
    private $indexIdsPairs = [];

    protected function getAllRelationItemsQuery($relationsSettings) {
        if(isset($relationsSettings['pivotModel'])) {
            $query = $this->object->belongsToMany($relationsSettings['model'], $relationsSettings['pivot'])->using($relationsSettings['pivotModel']);
        } else {
            $query = $this->object->belongsToMany($relationsSettings['model'], $relationsSettings['pivot'], $relationsSettings['foreignKey'], $relationsSettings['relationKey']);
        }
        
        return $this->getRelationItemsWithPivotFiltersQuery($query, $relationsSettings);
    }
    
    private function getRelationItemsWithPivotFiltersQuery($query, $relationsSettings) {
        if(isset($relationsSettings['pivotFilters'])) {
            foreach($relationsSettings['pivotFilters'] as $key => $values) {
                $query = $query->whereIn($relationsSettings['pivot'] . '.' . $key, $values);
            }
        }
        
        return $query;
    }
    
    
    public function saveRelation($data) {
        $this->data = $data;
        $relation = $this->relation;
        
        $this->addNewRelationItems();

        $this->populateRelationData();

        foreach($this->object->$relation as $item) {
            if(!isset($this->relationData[$item->id])) {
                $this->object->$relation()->detach($item);
            }
        }
        
        $this->index = 0;
        foreach($this->relationData as $relationItemId => $relationItemData) {
            $this->attach($relation, $relationItemId, $relationItemData);
        }
    }
    
    private function addNewRelationItems() {
        $relation = $this->relation;

        if(isset($this->data['new_items'][$relation])) {
            $relationsSettings = $this->object->getRelationSettings($relation);

            $this->indexIdsPairs = [];
            foreach($this->data['new_items'][$relation] as $key => $relationItemData) {
                $object = new $relationsSettings['model'];
                $object->saveObject($relationItemData);

                $this->indexIdsPairs[$key] = $object->id;
            }
        }
    }

    protected function populateRelationData() {
        $this->relationData = [];
        
        $relation = $this->relation;
        if(isset($this->data[$relation])) {
            $this->populateRelationDataFromRequestData();

            $relationsSettings = $this->object->getRelationSettings($relation);
            if(isset($relationsSettings['pivotModel'])) {
                $this->relationData = $relationsSettings['pivotModel']::populatePivotData($this->relationData);
            }
        }
    }

    private function populateRelationDataFromRequestData() {
        $this->relationData = [];

        $relation = $this->relation;
        $data = $this->data;
        foreach($data[$relation] as $index => $itemId) {
            if(strpos($itemId, '-new') !== false) {
                $data[$relation][$index] = $this->indexIdsPairs[$itemId];

                $relationItemData = $data['new_items'][$relation][$itemId];
                $this->relationData[$this->indexIdsPairs[$itemId]] = isset($relationItemData['pivot']) ? $relationItemData['pivot'] : [];
            } elseif(!empty($itemId)) {
                $this->relationData[$itemId] = isset($data['relation_items'][$relation][$itemId]['pivot']) ? $data['relation_items'][$relation][$itemId]['pivot'] : [];
            }
        }
    }

    protected function attach($relation, $relationItemId, $relationItemData) {
        if($this->object->sortableField($relation) !== null) {
            $relationItemData[$this->object->sortableField($relation)] = $this->index++;
        }
        
        if(!$this->object->$relation->contains($relationItemId)) {
            $this->object->$relation()->attach([$relationItemId => $relationItemData]);
        } elseif(!empty($relationItemData)) {
            $this->object->$relation()->updateExistingPivot($relationItemId, $relationItemData);
        }
    }
}