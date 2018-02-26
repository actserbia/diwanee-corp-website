<?php
namespace App;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Collection;
use App\Constants\Models;
use App\Constants\FieldTypeCategory;


class NodeList extends AppModel {
    use SoftDeletes;

    protected $allAttributesFields = ['id', 'name', 'node_type_id', 'order_by_field_id', 'order', 'limit', 'created_at', 'updated_at', 'deleted_at'];

    protected $fillable = ['name', 'node_type_id', 'order_by_field_id', 'order', 'limit'];

    protected $requiredFields = ['name', 'node_type', 'limit'];

    protected $attributeType = [
        'node_type_id' => Models::AttributeType_Number,
        'order_by_field_id' => Models::AttributeType_Number,
        'order' => Models::AttributeType_Enum,
        'limit' => Models::AttributeType_Number
    ];

    protected $defaultDropdownColumn = 'name';

    protected $relationsSettings = [
        'node_type' => [
            'relationType' => 'belongsTo',
            'model' => 'App\\NodeType',
            'foreignKey' => 'node_type_id'
        ],
        'order_by_field' => [
            'relationType' => 'belongsTo',
            'model' => 'App\\Field',
            'foreignKey' => 'order_by_field_id',
            'filters' => ['field_type.category' => [FieldTypeCategory::Attribute]]
        ],
        'tags' => [
            'relationType' => 'belongsToMany',
            'model' => 'App\\Tag',
            'pivot' => 'node_list_tag',
            'foreignKey' => 'node_list_id',
            'relationKey' => 'tag_id',
            'sortBy' => 'ordinal_number'
        ]
    ];
    
    protected $multipleFields = [
        'tags' => true
    ];
    
    protected $dependsOn = [
        'order_by_field' => ['node_type'],
        'tags' => ['node_type']
    ];
    
    public function orderByFieldRelationValues($dependsOnValues = null) {
        $nodeType = $this->getDependsOnValue('node_type', $dependsOnValues);
        
        if(!isset($nodeType->id)) {
            return [];
        }
        
        $fieldTypes = FieldType::where('category', '=', FieldTypeCategory::GlobalAttribute)->get();
        
        $fields = new Collection([]);
        foreach($fieldTypes as $fieldType) {
            $fields = $fields->merge($fieldType->fields);
        }
        
        return $fields->merge($nodeType->attribute_fields);
    }
    
    public function tagsRelationValues($dependsOnValues = null) {
        $nodeType = $this->getDependsOnValue('node_type', $dependsOnValues);
        
        if(!isset($nodeType->id)) {
            return [];
        }
        
        $tags = new Collection([]);
        foreach($nodeType->tag_fields as $tagField) {
            $tags = $tags->merge($tagField->tag_field_type->tags);
        }
        
        return $tags;
    }
}