<?php
namespace App;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Collection;
use App\Constants\Models;
use App\Constants\FieldTypeCategory;
use App\Models\ModelParentingTagsManager;

class NodeList extends AppModel {
    use SoftDeletes;
    use ModelParentingTagsManager;

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
            'filters' => ['field_type.category' => [FieldTypeCategory::GlobalAttribute, FieldTypeCategory::Attribute]]
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
        'order_by_field' => ['node_type']
    ];
    
    protected static $modelTypeField = 'node_type_id';

    public function populateDataByModelType($modelTypeId = null) {
        $this->modelType = !empty($this->node_type) ? $this->node_type : NodeType::find($modelTypeId);

        $this->populateTagFieldsData();
    }

    private function populateTagFieldsData() {
        $tagFieldsRelationName = FieldTypeCategory::Tag . '_fields';
        foreach($this->modelType->$tagFieldsRelationName as $tagField) {
            $this->populateTagFieldData($tagField);
        }
    }

    private function populateTagFieldData($tagField) {
        if($tagField->pivot->active) {
            $relationSettings = [
                'parent' => 'tags',
                'filters' => ['tag_type_id' => [$tagField->field_type_id]],
                'automaticRender' => true,
                'automaticSave' => true
            ];
            $this->relationsSettings[$tagField->formattedTitle] = $relationSettings;
            $this->multipleFields[$tagField->formattedTitle] = true;
        }
    }


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
    
    public function getItemsAttribute() {
        $additionalDataTable = $this->node_type->additionalDataTableName;
        $items = Node::join($this->node_type->additionalDataTableName, 'nodes.id', '=', $additionalDataTable . '.node_id')->where('node_type_id', '=', $this->node_type->id);
        
        foreach($this->tags as $tag) {
            $items = $items->whereHas('tags', function($query) use ($tag) {
                $query->where('tag_id', '=', $tag->id);
            });
        }
        
        if(isset($this->order_by_field->id)) {
            if($this->order_by_field->fieldTypeCategory === FieldTypeCategory::GlobalAttribute) {
                $items = $items->orderBy($this->order_by_field->formattedTitle, $this->order ? 'asc' : 'desc');
            } else {
                $items = $items->orderBy($additionalDataTable . '.' . $this->order_by_field->formattedTitle, $this->order ? 'asc' : 'desc');
            }
        }
        
        return $items->limit($this->limit)->get();
    }
}