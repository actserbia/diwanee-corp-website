<?php
namespace App\Models\RelationManager;

class BelongsToRelationManager extends RelationManager {
    protected function getAllRelationItemsQuery($relationsSettings) {
        return $this->object->belongsTo($relationsSettings['model'], $relationsSettings['foreignKey']);
    }
}