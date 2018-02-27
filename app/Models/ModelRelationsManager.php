<?php
namespace App\Models;

use App\Models\RelationManager\RelationManager;
use App\Utils\Utils;

trait ModelRelationsManager {
    public function __call($method, $parameters) {
        if($this->isRelation($method)) {
            return RelationManager::getRelationItems($this, $method);
        } else {
            return parent::__call($method, $parameters);
        }
    }
    
    public function getAttribute($key) {
        if($this->isRelation($key)) {
            if ($this->relationLoaded($key)) {
                return $this->relations[$key];
            }

            $relationItems = $this->hasMultipleValues($key) ? $this->$key()->get() : $this->$key()->first();
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
    
    public function checkRelationType($field, $mainModelNames, $relationName) {
        if(in_array($this->modelClass, $mainModelNames) && $this->isRelation($field)) {
            if($field === $relationName) {
                return true;
            }

            $relationsSettings = $this->relationsSettings[$field];
            if(isset($relationsSettings['parent']) && $relationsSettings['parent'] === $relationName) {
                return true;
            }
        }

        return false;
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
    
    public function relationExtraData($query, $relation) {
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

    public function hasMultipleValues($relation, $level = null) {
        if(!isset($this->multipleFields[$relation])) {
            return false;
        }

        if(is_bool($this->multipleFields[$relation])) {
            return $this->multipleFields[$relation];
        }

        if(is_array($this->multipleFields[$relation])) {
            return $level === null || (isset($this->multipleFields[$relation][$level - 1]) && $this->multipleFields[$relation][$level - 1]);
        }

        return false;
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
        return $this->hasMultipleValues($relation) && $this->sortableField($relation) !== null;
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
        $relationValuesMethod = Utils::getFormattedName($relation) . 'RelationValues';
        if(method_exists($this, $relationValuesMethod)) {
            return $this->$relationValuesMethod($dependsOnValues);
        } else {
            return $this->relationValues($relation);
        }
    }
    
    public function saveRelations($data) {
        foreach(array_keys($this->relationsSettings) as $relation) {
            RelationManager::save($this, $relation, $data);
        }
    }
    
    public function populateBelongsToRelations($data) {
        foreach(array_keys($this->relationsSettings) as $relation) {
            $relationSettings = $this->getRelationSettings($relation);
            if($relationSettings['relationType'] === 'belongsTo' && (!isset($relationSettings['automaticSave']) || $relationSettings['automaticSave']) && isset($data[$relation])) {
                $attribute = $relationSettings['foreignKey'];
                $this->$attribute = $data[$relation];
            }
        }
    }
    
    public function getAutomaticRenderRelations() {
        $relations = [];

        foreach(array_keys($this->relationsSettings) as $relation) {
            $relationSettings = $this->getRelationSettings($relation);
            if(isset($relationSettings['automaticRender']) && $relationSettings['automaticRender']) {
                $relations[] = $relation;
            }
        }

        return $relations;
    }
    
    public function getAutomaticRenderAtributesAndRelations() {
        return array_merge($this->getAutomaticRenderAtributes(), $this->getAutomaticRenderRelations());
    }
    
    protected function checkIfItemIsInRelation($relation, $item) {
        $found = false;
        
        foreach($this->$relation as $relationItem) {
            if($relationItem->id === $item->id) {
                $found = true;
                break;
            }
        }
        
        return $found;
    }
}