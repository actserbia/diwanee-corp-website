<?php
namespace App\Models\RelationItems;

class MultipleRelationItems extends RelationItems {
    private $index = 0;
    
    public function save() {
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
            foreach($data[$this->relation] as $relationItemId) {
                $this->relationData[$relationItemId] = [];
            }
            
            foreach($data as $key => $values) {
                if(strpos($key, $this->relation . '_') !== false) {
                    $additionalField = str_replace($this->relation . '_', '', $key);
                    foreach($values as $index => $value) {
                        $itemId = $data[$this->relation][$index];
                        $this->relationData[$itemId][$additionalField] = $value;
                    }
                }
            }
        }
    }

    protected function attach($relation, $relationItemId, $relationItemData) {
        if($this->object->sortableField($relation) !== null) {
            $relationItemData[$this->object->sortableField($relation)] = $this->index++;
        }
        
        if($this->object->$relation->contains($relationItemId)) {
            $this->object->$relation()->updateExistingPivot($relationItemId, $relationItemData);
        } else {
            $this->object->$relation()->attach([$relationItemId => $relationItemData]);
        }
    }
}