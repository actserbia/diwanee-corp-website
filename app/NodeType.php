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

    protected $allFields = ['id', 'name', 'created_at', 'updated_at', 'deleted_at'];
    
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
            'foreignKey' => 'node_type_id',
            'relationKey' => 'field_id',
            'filters' => ['fieldType.category' => [FieldTypeCategory::Field]],
            'sortBy' => 'ordinal_number',
            'extraFields' => ['active', 'required']
        ],
      
        'tags' => [
            'relationType' => 'belongsToMany',
            'model' => 'App\\Field',
            'pivot' => 'node_type_field',
            'foreignKey' => 'node_type_id',
            'relationKey' => 'field_id',
            'filters' => ['fieldType.category' => [FieldTypeCategory::Tag]],
            'sortBy' => 'ordinal_number',
            'extraFields' => ['active', 'required', 'multiple', 'sortable']
        ],
      
        'sirTrevor' => [
            'relationType' => 'belongsToMany',
            'model' => 'App\\Field',
            'pivot' => 'node_type_field',
            'foreignKey' => 'node_type_id',
            'relationKey' => 'field_id',
            'filters' => ['fieldType.category' => [FieldTypeCategory::SirTrevor]],
            'sortBy' => 'ordinal_number',
            'extraFields' => ['active', 'required']
        ]
    ];
    
    protected $multipleRelations = ['fields', 'tags', 'sirTrevor'];

    protected $dependsOn = [];
    
    public function saveData(array $data) {
        $oldName = $this->name;

        parent::saveData($data);
        
        $dbGenerator = new NodeModelDBGenerator($this, $oldName);
        $dbGenerator->generate();

        $classGenerator = new NodeModelClassGenerator($this, $oldName);
        $classGenerator->generate();
    }
}