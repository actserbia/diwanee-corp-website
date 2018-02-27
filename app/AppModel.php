<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use App\Models\ModelDataManager;
use App\Models\Search;
use App\Models\Statistics;

class AppModel extends Model {
    use ModelDataManager;
    use Search;
    use Statistics;
    
    protected $allAttributesFields = [];
    
    protected $allFieldsFromPivots = [];

    protected $requiredFields = [];
    
    protected $filterFields = [];
    
    protected $statisticFields = [];

    protected $attributeType = [];
    
    protected $defaultFieldsValues = [];

    protected $defaultDropdownColumn = '';

    protected $relationsSettings = [];
    
    protected $multipleFields = [];
    
    protected $dependsOn = [];
    
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

    public function saveObject(array $data) {
        DB::beginTransaction();
        try {
            $this->saveData($data);

            DB::commit();
            return true;
        } catch(Exception $e) {
            DB::rollBack();
            return false;
        }
    }
    
    public function saveData(array $data) {
        if(count($this->fillable) > 0) {
            $this->fill($data);
        }
        
        $this->populateBelongsToRelations($data);
        $this->save();
        
        $this->saveRelations($data);
    }
    
    protected function deleteRelationItems($relation) {
        $areAllDeleted = true;
        
        foreach($this->$relation as $relationItem) {
            if(!$relationItem->delete()) {
                $areAllDeleted = false;
                    break;
            }
        }
        
        return $areAllDeleted;
    }
    
    public function checkIfCanRemoveSelectedRelationItem($relation, $item = null) {
        if($item !== null && !$this->checkIfItemIsInRelation($relation, $item)) {
            return true;
        }
        
        if(method_exists($this, 'checkIfCanRemoveRelationItem')) {
            return $this->checkIfCanRemoveRelationItem($relation);
        }
        
        return true;
    }
    
    public function checkIfCanRemoveItem() {
        if(method_exists($this, 'checkIfCanRemove')) {
            return $this->checkIfCanRemove();
        }
        
        return true;
    }
}