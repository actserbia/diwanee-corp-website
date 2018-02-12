<?php
namespace App\Models\RelationItems;

class RelationItems {
    protected $object = null;
    protected $relation = '';
    protected $relationData = [];
    
    public function __construct($object, $relation, $data) {
        $this->object = $object;
        $this->relation = $relation;
        $this->populateRelationData($data);
    }
    
    public static function saveRelationItems($object, $relation, $data) {
        if($object->isMultiple($relation)) {
            $relationItemsObject = new MultipleRelationItems($object, $relation, $data);
        } else {
            $relationItemsObject = new SingleRelationItems($object, $relation, $data);
        }
        
        $relationItemsObject->save();
    }
}