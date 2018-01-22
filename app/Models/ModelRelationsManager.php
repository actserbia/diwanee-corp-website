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
        if(isset($relationsSettings['filters'])) {
            foreach($relationsSettings['filters'] as $filterField => $filterValues) {
                $query = $modelClass::whereIn($filterField, $filterValues);
            }
            return $query->get();
        } else {
            return $modelClass::get();
        }
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
        if($this->isRelation($relation)) {
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

            $this->filterAndSortRelation($query, $relation);
        }

        return $query;
    }

    private function filterAndSortRelation($query, $relation) {
        $relationsFilters = $this->getRelationFilters($relation);
        foreach($relationsFilters as $filterField => $filterValues) {
            $query->whereIn($filterField, $filterValues);
        }

        $sortable = $this->sortableField($relation);
        if(!empty($sortable)) {
            $query->withPivot([$sortable])->orderBy($sortable);
        }

        return $query;
    }

    public function getDefaultDropdownColumn($relation) {
        return $this->getRelationModel($relation)->defaultDropdownColumn;
    }

    public function isMultiple($relation) {
        return isset($this->multiple) && in_array($relation, $this->multiple);
    }

    public function sortableField($relation) {
        $relationsSettings = $this->getRelationSettings($relation);
        return isset($relationsSettings['sortBy']) ? $relationsSettings['sortBy'] : null;
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
    
    public function saveRelationItems($newItemsIds, $relation) {
        RelationItems::saveRelationItems($this, $relation, $newItemsIds);
        $this->load($relation);
    }
    
    public function saveBelongsToManyRelations($data) {
        foreach($this->relationsSettings as $relation => $relationSettings) {
            if($relationSettings['relationType'] === 'belongsToMany') {
                RelationItems::saveRelationItems($this, $relation, $data[$relation]);
                $this->load($relation);
            }
        }
    }
    
    public function populateBelongsToRelations($data) {
        foreach($this->relationsSettings as $relation => $relationSettings) {
            if($relationSettings['relationType'] === 'belongsTo') {
                $attribute = $relationSettings['foreignKey'];
                $this->$attribute = $data[$relation];
            }
        }
    }
}