<?php
namespace App\Models\RelationItems;

class MultipleRelationItems extends RelationItems {
    private $index = 0;
    
    public function saveItems($newItemsIds, $relation) {
        foreach($this->object->$relation as $item) {
            if(!isset($newItemsIds) || !in_array($item->id, $newItemsIds)) {
                $this->object->$relation()->detach($item);
            }
        }

        if(isset($newItemsIds)) {
            $this->index = 0;
            foreach($newItemsIds as $newItemId) {
                $this->attachItem($newItemId, $relation);
            }
        }
    }

    protected function attachNotSortableItem($newItemId, $relation) {
        if(!$this->object->$relation->contains($newItemId)) {
            $this->object->$relation()->attach($newItemId);
        }
    }

    protected function attachSortableItem($newItemId, $relation) {
        if($this->object->$relation->contains($newItemId)) {
            $this->object->$relation()->updateExistingPivot($newItemId, [$this->object->sortableField($relation) => $this->index++]);
        } else {
            $this->object->$relation()->attach([$newItemId => [$this->object->sortableField($relation) => $this->index++]]);
        }
    }
}