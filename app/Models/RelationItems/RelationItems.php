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
    
    public static function save($object, $relation, $data) {
        $relationSettings = $object->getRelationSettings($relation);
        
        switch($relationSettings['relationType']) {
            case 'hasOne':
                $relationItemsObject = new HasOneRelationItems($object, $relation, $data);
                break;
              
            case 'belongsToMany':
                if($object->hasMultipleValues($relation)) {
                    $relationItemsObject = new MultipleRelationItems($object, $relation, $data);
                } else {
                    $relationItemsObject = new SingleRelationItems($object, $relation, $data);
                }
                break;
        }
        
        $relationItemsObject->saveRelation();
    }
}