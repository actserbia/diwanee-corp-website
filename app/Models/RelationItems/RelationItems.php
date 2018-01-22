<?php
namespace App\Models\RelationItems;

class RelationItems {
    protected $object = null;
    
    public function __construct($object) {
      $this->object = $object;
    }

    protected function attachItem($newItemId, $relation) {
        $this->object->sortableField($relation) === null ? $this->attachNotSortableItem($newItemId, $relation)  : $this->attachSortableItem($newItemId, $relation);
    }
    
    public static function saveRelationItems($object, $relation, $newItemsIds) {
        if($object->isMultiple($relation)) {
            $relationItemsObject = new MultipleRelationItems($object);
        } else {
            $relationItemsObject = new SingleRelationItems($object);
        }
        
        $relationItemsObject->saveItems($newItemsIds, $relation);
    }
}