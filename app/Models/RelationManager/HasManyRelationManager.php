<?php
namespace App\Models\RelationManager;

class HasManyRelationManager extends RelationManager {
    protected function getAllRelationItemsQuery($relationsSettings) {
        return $this->object->hasMany($relationsSettings['model'], $relationsSettings['foreignKey'], $relationsSettings['relationKey']);
    }
}