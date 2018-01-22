<?php
namespace App\Models\RelationItems;

class SingleRelationItems extends RelationItems {
    public function saveItems($newItemsIds, $relation) {
        
        if(isset($this->object->$relation) && $this->object->$relation->id != $newItemsIds) {
            $this->object->$relation()->detach($this->object->$relation->id);
        }
        
        if(isset($newItemsIds) && !empty($newItemsIds)) {
            $this->attachItem($newItemsIds, $relation);
        }
    }

    protected function attachNotSortableItem($newItemId, $relation) {
        if(!isset($this->object->$relation) || $this->object->$relation->id != $newItemId) {
            $this->object->$relation()->attach($newItemId);
        }
    }

    protected function attachSortableItem($newItemId, $relation) {
        if(!isset($this->object->$relation) || $this->object->$relation->id != $newItemId) {
            $this->object->$relation()->attach([$newItemId => [$this->object->sortableField($relation) => 0]]);
        }
    }
}