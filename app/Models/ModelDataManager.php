<?php
namespace App\Models;

use App\Constants\Models;

trait ModelDataManager {
    use ModelAttributesManager;
    use ModelRelationsManager;
    use ModelFormManager;
    
    private $modelManagers = [];
    
    private function getModelManager($fullFieldName) {
        if(!isset($this->modelManagers[$fullFieldName])) {
            $this->setModelManager($fullFieldName);
        }
        
        return $this->modelManagers[$fullFieldName];
    }
    
    private function setModelManager($fullFieldName) {
        $fieldSettings = $this->getFieldSettings($fullFieldName);
        if(!empty($fieldSettings)) {
            $modelManagerClassShortName = isset($fieldSettings['json_attribute']) ? 'ModelAttributeJson' : 'ModelAttribute';
            $modelManagerClassName = 'App\\Models\\ModelManager\\' . $modelManagerClassShortName;
            $this->modelManagers[$fullFieldName] = new $modelManagerClassName($fieldSettings);
        }
    }
    
    public function checkIfUseSoftDeletes() {
        return in_array('Illuminate\Database\Eloquent\SoftDeletes', class_uses($this->modelClass));
    }
    
    public function scopeWithAll($query) {
        if($this->checkIfUseSoftDeletes()) {
            $query->withTrashed();
        }

        $withRelations = [];

        foreach($this->getSupportedRelations() as $relation) {
            $usesSoftDeletes = $this->getRelationModel($relation)->checkIfUseSoftDeletes();
            $withRelations[$relation] = $usesSoftDeletes ? function ($q) { 
                $q->withTrashed(); 
            } : function ($q) { };
        }

        $query->with($withRelations);
    }
    
    public function getFieldSettings($fullFieldName) {
        $settings = [];

        $params = explode(':', $fullFieldName);
        
        $model = $this;
        if(!empty($params) && $this->isRelation($this->getFieldName($params[0]))) {
            $model = $this->getRelationModel($this->getFieldName($params[0]));
            $settings['relation'] = array_shift($params);
        }
        
        if(!empty($params) && $model->isAttribute(str_replace('_confirmation', '', $this->getFieldName($params[0])))) {
            $settings['attribute'] = array_shift($params);
            if(!empty($params)) {
                $settings['json_attribute']['name'] = array_shift($params);
                $settings['json_attribute']['settings'] = $model->getJsonAttributeFilters($settings['attribute'] . ':' . $settings['json_attribute']['name']);
            }
        }

        if(empty($params)) {
            $this->populateFieldType($settings);
        } else {
            $settings = [];
        }

        return $settings;
    }
    
    private function getFieldName($fullFieldName) {
        $params = explode('*', $fullFieldName);
        return end($params);
    }
    
    private function populateFieldType(&$settings) {
        if(strpos($settings['attribute'], 'count') !== false) {
            $settings['field_type'] = Models::FieldType_AttributeAggregate;
        } elseif(isset($settings['relation'])) {
            $settings['field_type'] = isset($settings['json_attribute']) ? Models::FieldType_RelationJson : Models::FieldType_Relation;
        } else {
            $settings['field_type'] = isset($settings['json_attribute']) ? Models::FieldType_AttributeJson : Models::FieldType_Attribute;
        }
        $settings['model'] = $this;
    }
}