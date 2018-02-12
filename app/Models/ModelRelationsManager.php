<?php
namespace App\Models;

use App\Models\RelationItems\RelationItems;

trait ModelRelationsManager {
    public function __call($method, $parameters) {
        if($this->isRelation($method)) {
            return $this->getRelationItems($method);
        } else {
            return parent::__call($method, $parameters);
        }
    }
    
    public function getAttribute($key) {
        if($this->isRelation($key)) {
            if ($this->relationLoaded($key)) {
                return $this->relations[$key];
            }

            $relationItems = $this->isMultiple($key) ? $this->$key()->get() : $this->$key()->first();
            $this->setRelation($key, $relationItems);
            return $relationItems;
        } else {
            return parent::getAttribute($key);
        }
    }
    
    public function relationValues($relation) {
        $relationsSettings = $this->getRelationSettings($relation);
        $modelClass = $relationsSettings['model'];
        
        $query = $modelClass::select('*');
        if(isset($relationsSettings['filters'])) {
            $relationModel = $this->getRelationModel($relation);
            $relationModel::filter($relationsSettings['filters'], $query);
        }
        
        return $query->get();
    }
    
    public function getSupportedRelations() {
        return isset($this->relationsSettings) ? array_keys($this->relationsSettings) : [];
    }

    public function isRelation($field) {
        return isset($this->relationsSettings[$field]);
    }

    public function getRelationSettings($relation) {
        if($this->isRelation($relation)) {
            $relationsSettings = $this->relationsSettings[$relation];
            if(isset($relationsSettings['parent'])) {
                return array_merge($this->relationsSettings[$relationsSettings['parent']], $relationsSettings);
            } else {
                return $relationsSettings;
            }
        }
        
        return null;
    }

    public function getRelationModel($relation) {
        if($this->isRelation($relation)) {
            $relationsSettings = $this->getRelationSettings($relation);
            return new $relationsSettings['model'];
        }

        return null;
    }

    public function getRelationFilters($relation) {
        $relationsSettings = $this->getRelationSettings($relation);
        return isset($relationsSettings['filters']) ? $relationsSettings['filters'] : [];
    }

    private function getRelationItems($relation) {
        $relationsSettings = $this->getRelationSettings($relation);
        $relationType = $relationsSettings['relationType'];
        switch($relationsSettings['relationType']) {
            case 'belongsToMany':
                $query = $this->$relationType($relationsSettings['model'], $relationsSettings['pivot'], $relationsSettings['foreignKey'], $relationsSettings['relationKey']);
                break;

            case 'belongsTo':
                $query = $this->$relationType($relationsSettings['model'], $relationsSettings['foreignKey']);
                break;

            case 'hasMany':
                $query = $this->$relationType($relationsSettings['model'], $relationsSettings['foreignKey'], $relationsSettings['relationKey']);
                break;

            case 'hasOne':
                $query = $this->$relationType($relationsSettings['model'], $relationsSettings['foreignKey'], $relationsSettings['relationKey']);
                break;
        }
            
        if(isset($relationsSettings['filters'])) {
            $relationModel = $this->getRelationModel($relation);
            $relationModel::filter($relationsSettings['filters'], $query);
        }

        $this->relationExtraData($query, $relation);

        return $query;
    }
    
    private function relationExtraData($query, $relation) {
        $extraFields = $this->extraFields($relation);
        $sortable = $this->sortableField($relation);
        if(!empty($sortable)) {
            $extraFields[] = $sortable;
        }
        
        if(!empty($extraFields)) {
            $query->withPivot($extraFields);
        }
        
        if(!empty($sortable)) {
            $query->orderBy($sortable);
        }
    }

    public function getDefaultDropdownColumn($relation) {
        return $this->getRelationModel($relation)->defaultDropdownColumn;
    }

    public function isMultiple($relation) {
        return in_array($relation, $this->multipleRelations);
    }

    public function sortableField($relation) {
        $relationsSettings = $this->getRelationSettings($relation);
        return isset($relationsSettings['sortBy']) ? $relationsSettings['sortBy'] : null;
    }
    
    public function extraFields($relation) {
        $relationsSettings = $this->getRelationSettings($relation);
        return isset($relationsSettings['extraFields']) ? $relationsSettings['extraFields'] : [];
    }
    
    public function isSortable($relation) {
        return $this->isMultiple($relation) && $this->sortableField($relation) !== null;
    }

    public function checkDependsOn($relation) {
        return isset($this->dependsOn[$relation]);
    }

    public function dependsOn($relation, $encode = true) {
        $dependsOn = isset($this->dependsOn[$relation]) ? $this->dependsOn[$relation] : [];
        return $encode ? json_encode($dependsOn) : $dependsOn;
    }

    public function getDependsOnValue($field, $dependsOnValues) {
        return $dependsOnValues !== null ? $this->getDependsOnValueFromSentValues($field, $dependsOnValues) : $this->$field;
    }
    
    private function getDependsOnValueFromSentValues($field, $dependsOnValues) {
        $value = [];
        
        if(isset($dependsOnValues[$field])) {
            if($this->isRelation($field)) {
                $value = $this->getRelationModel($field)::find($dependsOnValues[$field]);
            } else {
                $value = $dependsOnValues[$field];
            }
        }

        return $value;
    }

    public function getRelationValues($relation, $dependsOnValues = null) {
        $relationValuesMethod = $relation . 'RelationValues';
        if(method_exists($this, $relationValuesMethod)) {
            return $this->$relationValuesMethod($dependsOnValues);
        } else {
            return $this->relationValues($relation);
        }
    }
    
    public function saveRelationItems($relation, $data) {
        RelationItems::saveRelationItems($this, $relation, $data);
        $this->load($relation);
    }
    
    public function saveBelongsToManyRelations($data) {
        foreach(array_keys($this->relationsSettings) as $relation) {
            $relationSettings = $this->getRelationSettings($relation);
            if($relationSettings['relationType'] === 'belongsToMany') {
                $this->saveRelationItems($relation, $data);
            }
        }
    }
    
    public function populateBelongsToRelations($data) {
        foreach(array_keys($this->relationsSettings) as $relation) {
            $relationSettings = $this->getRelationSettings($relation);
            if($relationSettings['relationType'] === 'belongsTo') {
                $attribute = $relationSettings['foreignKey'];
                $this->$attribute = $data[$relation];
            }
        }
    }
    
    public function relationIds($relation) {
        $ids = [];

        foreach($this->$relation as $relationNode) {
            $ids[] = $relationNode->id;
            $ids = array_merge($ids, $relationNode->relationIds($relation));
        }

        return $ids;
    }
    
    public function getFillableRelations() {
        $relations = [];
        foreach(array_keys($this->relationsSettings) as $relation) {
            $relationSettings = $this->getRelationSettings($relation);
            if(isset($relationSettings['fillable']) && $relationSettings['fillable']) {
                $relations[] = $relation;
            }
        }
        return $relations;
    }
}