<?php

namespace App;

use Illuminate\Database\Eloquent\SoftDeletes;

use Illuminate\Support\Facades\DB;
use App\Constants\FieldTypeCategory;

class FieldType extends AppModel {
    use SoftDeletes;
    
    protected $fillable = ['name', 'category'];

    protected $allFields = ['id', 'name', 'category', 'created_at', 'updated_at', 'deleted_at'];
    
    protected $allFieldsFromPivots = [];
    
    protected $attributeType = [];

    protected $requiredFields = ['name', 'category'];
    
    protected $defaultDropdownColumn = 'name';

    protected $relationsSettings = [
        'fields' => [
            'relationType' => 'hasMany',
            'model' => 'App\\Field',
            'foreignKey' => 'field_type_id',
            'relationKey' => 'id'
        ],
        'tags' => [
            'relationType' => 'hasMany',
            'model' => 'App\\Tag',
            'foreignKey' => 'tag_type_id',
            'relationKey' => 'id'
        ]
    ];
    
    protected $multipleRelations = ['fields', 'tags'];

    protected $dependsOn = [];

    public function saveData(array $data) {
        $isNew = !isset($this->id);
            
        parent::saveData($data);
            
        if($isNew) {
            $this->addFieldForTagFieldType();
        }
    }
    
    private function addFieldForTagFieldType() {
        if($this->category === FieldTypeCategory::Tag) {
            $fieldObject = new Field;
            $fieldData = [
                'title' => $this->name,
                'fieldType' => $this->id
            ];
            $fieldObject->saveData($fieldData);
            $this->load('tags');
        }
    }
    
    public function deleteObject() {
        DB::beginTransaction();
        try {
            $areAllDeleted = true;
            
            if($this->category === FieldTypeCategory::Tag) {
                $areAllDeleted = $this->deleteRelationItems('fields');
            }
            
            if($areAllDeleted && $this->delete()) {
                DB::commit();
                return true;
            } else {
                DB::rollBack();
                return false;
            }
        } catch(Exception $e) {
            DB::rollBack();
            return false;
        }
    }
}