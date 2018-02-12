<?php
namespace App\Models\RelationItems;

class SingleRelationItems extends RelationItems {
    public function save() {
        $relationItemId = array_keys($this->relationData)[0];
        $relationItemData = $this->relationData[$relationItemId];
        
        $relation = $this->relation;
        
        if(isset($this->object->$relation) && $this->object->$relation->id != $relationItemId) {
            $this->object->$relation()->detach($this->object->$relation->id);
        }
        
        if($relationItemId > 0) {
            $this->attach($relation, $relationItemId, $relationItemData);
        }
    }
    
    protected function populateRelationData($data) {
        $this->relationData = [];
        
        if(isset($data[$this->relation])) {
            $relationItemId = $data[$this->relation];
            $this->relationData[$relationItemId] = [];
            
            foreach($data as $key => $value) {
                if(strpos($key, $this->relation . '_') !== false) {
                    $additionalField = str_replace($this->relation . '_', '', $key);
                    
                    $itemId = $data[$this->relation];
                    $this->relationData[$itemId][$additionalField] = $value;
                }
            }
        }
    }

    protected function attach($relation, $relationItemId, $relationItemData) {
        if($this->object->sortableField($relation) !== null) {
            $relationItemData[$this->object->sortableField($relation)] = 0;
        }
        
        if(!isset($this->object->$relation) || $this->object->$relation->id != $relationItemId) {
            $this->object->$relation()->attach([$relationItemId => $relationItemData]);
        }
    }
}