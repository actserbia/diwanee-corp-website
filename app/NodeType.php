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
        'attributes_fields' => [
            'relationType' => 'belongsToMany',
            'model' => 'App\\Field',
            'pivot' => 'node_type_field',
            'foreignKey' => 'node_type_id',
            'relationKey' => 'field_id',
            'filters' => ['field_type.category' => [FieldTypeCategory::Attribute]],
            'sortBy' => 'ordinal_number',
            'extraFields' => ['active', 'required']
        ],
      
        'tags_fields' => [
            'relationType' => 'belongsToMany',
            'model' => 'App\\Field',
            'pivot' => 'node_type_field',
            'foreignKey' => 'node_type_id',
            'relationKey' => 'field_id',
            'filters' => ['field_type.category' => [FieldTypeCategory::Tag]],
            'sortBy' => 'ordinal_number',
            'extraFields' => ['active', 'required', 'multiple']
        ],
      
        'sir_trevor_fields' => [
            'relationType' => 'belongsToMany',
            'model' => 'App\\Field',
            'pivot' => 'node_type_field',
            'foreignKey' => 'node_type_id',
            'relationKey' => 'field_id',
            'filters' => ['field_type.category' => [FieldTypeCategory::SirTrevor]],
            'sortBy' => 'ordinal_number',
            'extraFields' => ['active', 'required']
        ]
    ];
    
    protected $multipleFields = [
        'attributes_fields' => true,
        'tags_fields' => true,
        'sir_trevor_fields' => true
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
        foreach($this->sir_trevor_fields as $field) {
            $stFields[] = str_replace(' ', '', $field->title);
        }
        return $stFields;
    }

    public function getRequiredSTFieldsArray() {
        $reqFields = array();
        foreach($this->sir_trevor_fields as $field) {
            if($field->pivot->required) {
                $reqFields[] = str_replace(' ', '', $field->title);
            }
        }
        return $reqFields;
    }
}