<?php

namespace App;

use Illuminate\Database\Eloquent\SoftDeletes;
use App\Constants\Models;
use App\Constants\FieldTypeCategory;
use App\Models\Node\NodeModelDBGenerator;
use App\Models\Node\NodeModelClassGenerator;

class NodeType extends AppModel {
    use SoftDeletes;

    protected $fillable = ['name'];

    protected $allAttributesFields = ['id', 'name', 'created_at', 'updated_at', 'deleted_at'];
    
    protected $allFieldsFromPivots = [];

    protected $requiredFields = ['name'];
    
    protected $defaultDropdownColumn = 'name';

    protected $attributeType = [
        'status' => Models::AttributeType_Enum
    ];

    protected $relationsSettings = [
        'fields' => [
            'relationType' => 'belongsToMany',
            'model' => 'App\\Field',
            'pivot' => 'node_type_field',
            'pivotModel' => 'App\\NodeTypeField',
            'filters' => ['field_type.category' => [FieldTypeCategory::Attribute]],
            'sortBy' => 'ordinal_number',
            'extraFields' => ['active', 'required'],
            'automaticSave' => false
        ],
        FieldTypeCategory::Attribute . '_fields' => [
            'parent' => 'fields',
            'filters' => ['field_type.category' => [FieldTypeCategory::Attribute]],
            'automaticSave' => true
        ],
        FieldTypeCategory::Tag . '_fields' => [
            'parent' => 'fields',
            'filters' => ['field_type.category' => [FieldTypeCategory::Tag]],
            'extraFields' => ['active', 'required', 'multiple_list'],
            'automaticSave' => true
        ],
        FieldTypeCategory::SirTrevor . '_fields' => [
            'parent' => 'fields',
            'filters' => ['field_type.category' => [FieldTypeCategory::SirTrevor]],
            'automaticSave' => true
        ],
      
        'nodes' => [
            'relationType' => 'hasMany',
            'model' => 'App\\Node',
            'foreignKey' => 'node_type_id',
            'relationKey' => 'id',
            'automaticSave' => false
        ]
    ];
    
    protected $multipleFields = [
        'fields' => true,
        FieldTypeCategory::Attribute . '_fields' => true,
        FieldTypeCategory::Tag . '_fields' => true,
        FieldTypeCategory::SirTrevor . '_fields' => true,
        'nodes' => true
    ];

    protected $dependsOn = [];
    
    public function saveData(array $data) {
        $oldName = $this->name;

        parent::saveData($data);
        
        $dbGenerator = new NodeModelDBGenerator($this, $oldName);
        $dbGenerator->generate();

        $classGenerator = new NodeModelClassGenerator($this, $oldName);
        $classGenerator->generate();
    }

    public function getSTFieldsArray() {
        $stFields = array();
        $sirTrevorRelationName = FieldTypeCategory::SirTrevor . '_fields';
        foreach($this->$sirTrevorRelationName as $field) {
            $stFields[] = str_replace(' ', '', $field->title);
        }
        return $stFields;
    }

    public function getRequiredSTFieldsArray() {
        $reqFields = array();
        $sirTrevorRelationName = FieldTypeCategory::SirTrevor . '_fields';
        foreach($this->$sirTrevorRelationName as $field) {
            if($field->pivot->required) {
                $reqFields[] = str_replace(' ', '', $field->title);
            }
        }
        return $reqFields;
    }
    
    protected function checkIfCanRemoveRelationItem($relation) {
        if(strpos($relation, 'fields') !== false) {
            return (count($this->nodes) === 0);
        }
        
        return true;
    }
}