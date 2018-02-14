<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Support\Facades\DB;
use App\Models\ModelDataManager;

class AppModel extends Model {
    use ModelDataManager;
    
    protected $allAttributesFields = [];
    
    protected $allFieldsFromPivots = [];

    protected $requiredFields = [];

    protected $attributeType = [];
    
    protected $defaultFieldsValues = [];

    protected $defaultDropdownColumn = '';

    protected $relationsSettings = [];
    
    protected $multipleFields = [];
    
    protected $dependsOn = [];

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
        $this->fill($data);
        $this->populateBelongsToRelations($data);
        $this->save();

        $this->saveBelongsToManyRelations($data);
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
}