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

    protected $representationField = '';

    protected $relationsSettings = [];
    
    protected $multipleFields = [];
    
    protected $dependsOn = [];

    protected $observables = [ 'savedWithRelations' ];
    
    public function saveObject(array $data) {
        DB::beginTransaction();
        try {
            $this->saveData($data);

            DB::commit();
            $this->fireModelEvent('savedWithRelations', false);
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
    
    public function deleteObject() {
        DB::beginTransaction();
        try {
            $this->deleteData();

            DB::commit();
            return true;
        } catch(Exception $e) {
            DB::rollBack();
            return false;
        }
    }

    public function deleteData() {
        return $this->delete();
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
}