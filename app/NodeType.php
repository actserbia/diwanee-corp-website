<?php

namespace App;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use App\Constants\Models;
use App\Constants\FieldTypeCategory;
use App\Constants\FieldType;
use App\Utils\Utils;
use App\Models\NodeModelGenerator;

class NodeType extends AppModel {
    use SoftDeletes;

    protected $fillable = ['name'];

    protected $allFields = ['id', 'name', 'created_at', 'updated_at', 'deleted_at'];
    
    protected $allFieldsFromPivots = [];

    protected $requiredFields = ['name'];
    
    protected $defaultDropdownColumn = 'name';

    protected $attributeType = [
        'status' => Models::AttributeType_Enum,
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
            'extraFields' => ['active', 'required', 'multiple', 'sortable']
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
            'extraFields' => ['active', 'required', 'multiple', 'sortable']
        ]
    ];
    
    protected $multipleRelations = ['fields', 'tags', 'sirTrevor'];

    protected $dependsOn = [];
    
    public function saveData(array $data) {
        $isNew = !isset($this->id);
            
        parent::saveData($data);
            
        if($isNew) {
            $this->createDBTable();
        }
        
        $this->saveNodeFieldsModel();
    }
    
    private function createDBTable() {
        $tableName = Utils::getFormattedDBName($this->name) . 's';
        Schema::create($tableName, function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('node_id');
            
            $this->addDBFields($table);
                
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::table($tableName, function($table) {
            $table->foreign('node_id')->references('id')->on('nodes');
        });
    }
    
    private function addDBFields($table) {
        foreach($this->fields as $field) {
            switch($field->fieldType->name) {
                case FieldType::Text:
                    $table->string($field->formattedName, 255);
                    break;
                      
                case FieldType::Integer:
                    $table->unsignedInteger($field->formattedName);
                    break;
                  
                case FieldType::Date:
                    $table->timestamp($field->formattedName);
                    break;
            }
        }
    }
    
    private function saveNodeFieldsModel() {
        $generator = new NodeModelGenerator($this);
        $generator->generate();
    }
}