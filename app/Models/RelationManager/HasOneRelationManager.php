<?php
namespace App\Models\RelationManager;

use App\Utils\Utils;
use ReflectionClass;

class HasOneRelationManager extends RelationManager {
    protected function getAllRelationItemsQuery($relationsSettings) {
        return $this->object->hasOne($relationsSettings['model'], $relationsSettings['foreignKey'], $relationsSettings['relationKey']);
    }
    
    public function saveRelation($data) {
        $this->populateRelationData($data);
        
        $relation = $this->relation;
        
        $relationSettings = $this->object->getRelationSettings($relation);
        
        $relationObject = isset($this->object->$relation) ? $this->object->$relation : new $relationSettings['model'];
        $refClass = new ReflectionClass($this->object);
        $inverseRelationName = Utils::getFormattedDBName($refClass->getShortName());
        $relationObject->$inverseRelationName()->associate($this->object);
        if(count($relationObject->getFillableAttributes()) > 0) {
            $relationObject->fill($this->relationData);
        }        
        $relationObject->save();
    }
    
    protected function populateRelationData($data) {
        $this->relationData = $data;
    }
}