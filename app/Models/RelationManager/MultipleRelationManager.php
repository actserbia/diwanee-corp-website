<?php
namespace App\Models\RelationManager;

class MultipleRelationManager extends RelationManager {
    private $index = 0;
    
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
                $query = $query->whereIn($key, $values);
            }
        }
        
        return $query;
    }
    
    
    public function saveRelation($data) {
        $this->populateRelationData($data);
        
        $relation = $this->relation;
        
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
    
    protected function populateRelationData($data) {
        $this->relationData = [];
        
        if(isset($data[$this->relation])) {
            foreach($data[$this->relation] as $itemId) {
                if(!empty($itemId)) {
                    $this->relationData[$itemId] = [];
                }
            }

            foreach($data as $key => $value) {
                if(strpos($key, 'pivot_' . $this->relation) === 0) {
                    $this->relationData = $value;
                }
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