<?php
namespace App\Models\RelationManager;

class RelationManager {
    protected $object = null;
    protected $relation = '';
    protected $relationData = [];
    
    public function __construct($object, $relation) {
        $this->object = $object;
        $this->relation = $relation;
    }
    
    public function getObjectRelationItems() {
        $relationsSettings = $this->object->getRelationSettings($this->relation);
        
        $query = $this->getAllRelationItemsQuery($relationsSettings);

        if(isset($relationsSettings['filters'])) {
            $relationModel = $this->object->getRelationModel($this->relation);
            $relationModel::filter($relationsSettings['filters'], $query);
        }

        $this->object->relationExtraData($query, $this->relation);

        return $query;
    }
    
    private static function getRelationManager($object, $relation) {
        $relationSettings = $object->getRelationSettings($relation);
        
        switch($relationSettings['relationType']) {
            case 'belongsToMany':
                if($object->hasMultipleValues($relation)) {
                    $relationManager = new MultipleRelationManager($object, $relation);
                } else {
                    $relationManager = new SingleRelationManager($object, $relation);
                }
                break;
                
            case 'belongsTo':
                $relationManager = new BelongsToRelationManager($object, $relation);
                break;
              
            case 'hasMany':
                $relationManager = new HasManyRelationManager($object, $relation);
                break;
              
            case 'hasOne':
                $relationManager = new HasOneRelationManager($object, $relation);
                break;
        }
        
        return $relationManager;
    }
    
    
    public static function getRelationItems($object, $relation) {
        return self::getRelationManager($object, $relation)->getObjectRelationItems();
    }
    
    
    public static function save($object, $relation, $data) {
        $relationSettings = $object->getRelationSettings($relation);
        if(in_array($relationSettings['relationType'], ['belongsToMany', 'hasOne']) && (!isset($relationSettings['automaticSave']) || $relationSettings['automaticSave'])) {
            self::getRelationManager($object, $relation)->saveRelation($data);
            $object->load($relation);
        }
    }
}