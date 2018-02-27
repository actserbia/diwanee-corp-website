<?php
namespace App\Models;

use App\Constants\Models;

trait ModelDataManager {
    use ModelAttributesManager;
    use ModelRelationsManager;
    use ModelFormManager;
    
    private $modelManagers = [];
    
    public $modelType = null;
    protected static $modelTypeField = '';

    public static function hasModelTypes() {
        return !empty(static::$modelTypeField);
    }

    public static function scopeFilterByModelType($query, $modelTypeId) {
        if(static::hasModelTypes()) {
            $query->where(static::$modelTypeField, '=', $modelTypeId);
        }
    }

    public function __construct(array $attributes = array()) {
        parent::__construct($attributes);

        if(static::hasModelTypes() && isset($attributes['model_type_id'])) {
            $this->populateDataByModelType($attributes['model_type_id']);
        }
    }

    public static function __callStatic($method, $parameters) {
        if(static::hasModelTypes()) {
            if(in_array($method, ['findOrFail', 'find'])) {
                $object = (new static)->$method(...$parameters);
                $object->populateDataByModelType();
                return $object;
            } elseif(isset(end($parameters)['model_type_id'])) {
                return (new static(array_pop($parameters)))->$method(...$parameters);
            }
        }

        return parent::__callStatic($method, $parameters);
    }

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
            
            if(empty($params)) {
                $params[] = 'id';
            }
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
        if(strpos($settings['attribute'], '_count') !== false) {
            $settings['field_type'] = Models::FieldType_AttributeAggregate;
        } elseif(isset($settings['relation'])) {
            $settings['field_type'] = isset($settings['json_attribute']) ? Models::FieldType_RelationJson : Models::FieldType_Relation;
        } else {
            $settings['field_type'] = isset($settings['json_attribute']) ? Models::FieldType_AttributeJson : Models::FieldType_Attribute;
        }
        $settings['model'] = $this;
    }
    
    public static function filter($filterParams, $query = null) {
        if($query === null) {
            $query = self::select('*');
        }
        
        foreach($filterParams as $filterField => $filterValues) {
            if(strpos($filterField, '.') !== false) {
                $model = new static;
                $filterFieldNameList = explode('.', $filterField);
                $filterRelation = $model->getRelationModel($filterFieldNameList[0]);
                $filterRelationItems = $filterRelation::whereIn($filterFieldNameList[1], $filterValues)->get();
                
                $filterRelationSettings = $model->getRelationSettings($filterFieldNameList[0]);
                
                $query->whereIn($filterRelationSettings['foreignKey'], self::getIds($filterRelationItems));
            } else {
                $query->whereIn($filterField, $filterValues);
            }
        }
        
        return $query;
    }
    
    private static function getIds($items) {
        $ids = [];
        foreach($items as $item) {
            $ids[] = $item->id;
        }
        return $ids;
    }
}